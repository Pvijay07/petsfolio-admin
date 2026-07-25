<?php

namespace App\Controllers\Cli;

use CodeIgniter\Controller;
use DateTime;
use DateTimeZone;

class WalkerTierEvaluator extends Controller
{
  public function evaluate()
  {
    $isCli = is_cli();
    if ($isCli) {
      echo "Starting Walker Tier Evaluation...\n";
    } else {
    }

    set_time_limit(0); // Prevent timeout for large datasets
    $db = \Config\Database::connect();

    // 1. Get all active walkers (service_id = 4)
    $walkers = $db->table('sp_services sps')
      ->select('sps.provider_id, sp.created_at')
      ->join('service_providers sp', 'sp.id = sps.provider_id')
      ->where('sps.service_id', 4)
      ->where('sps.status', 'active')
      ->get()
      ->getResult();

    $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));

    foreach ($walkers as $walker) {
      $providerId = $walker->provider_id;
      try {

        if ($isCli) {
          echo "Evaluating Provider ID: $providerId\n";
        }

        // --- Lifetime Stats ---
        // Total Walks (Ever assigned)
        $totalWalks = $db->table('walking_tracking')->where('provider_id', $providerId)->countAllResults();

        // Completed Walks
        $completedWalks = $db->table('walking_tracking')->where('provider_id', $providerId)->where('status', 'completed')->countAllResults();

        // Cancelled by Walker
        $cancelledWalks = $db->table('walking_tracking')->where('provider_id', $providerId)->where('status', 'cancelled')->countAllResults();

        // Untracked Walks
        $untrackedWalks = $db->table('walking_tracking')->where('provider_id', $providerId)->where('status', 'untracked')->countAllResults();

        // Rejected Walks
        $rejectedWalks = $db->table('walking_tracking')->where('provider_id', $providerId)->where('status', 'rejected')->countAllResults();

        $completionRate = $totalWalks > 0 ? ($completedWalks / $totalWalks) * 100 : 0;

        // --- Experience Calculation ---
        $wd                = $db->table('sp_walking_details')->where('provider_id', $providerId)->get()->getRow();
        $initialExperience = (float) ($wd->experience ?? 0);
        $joinedAt          = new DateTime($walker->created_at, new DateTimeZone('UTC'));
        $nowDt             = new DateTime('now', new DateTimeZone('UTC'));
        $interval          = $joinedAt->diff($nowDt);
        $daysSinceJoined   = $interval->days;

        $effectiveExperience = $initialExperience;
        if ($daysSinceJoined >= 180) { // 6 months threshold
          $effectiveExperience += ($daysSinceJoined / 365);
        }
        $cancellationRate = $totalWalks > 0 ? ($cancelledWalks / $totalWalks) * 100 : 0;

        // Avg Rating
        $ratingData = $db->table('sp_reviews')
          ->select('AVG(rating) as avg_rating, COUNT(id) as total')
          ->where('provider_id', $providerId)->where('service_id', 4)
          ->get()->getRow();
        $avgRating  = $ratingData->avg_rating ?? 0;

        // Repeat Clients (Count distinct users who booked > 1 time)
        $repeatQuery   = $db->query("SELECT COUNT(*) as repeat_clients FROM (
                SELECT user_id FROM walking_service_bookings wsb 
                JOIN quotations q ON q.booking_id = wsb.id 
                WHERE q.provider_id = ? AND wsb.status = 'Completed' 
                GROUP BY user_id HAVING COUNT(wsb.id) > 1
            ) as t", [$providerId]);
        $repeatClients = $repeatQuery->getRow()->repeat_clients ?? 0;

        // --- 30-Day Stats ---
        $last30Walks = $db->table('walking_tracking')
          ->where('provider_id', $providerId)
          ->where('status', 'completed')
          ->where('created_at >=', $thirtyDaysAgo)
          ->countAllResults();

        $last30Untracked = $db->table('walking_tracking')
          ->where('provider_id', $providerId)
          ->where('status', 'untracked')
          ->where('created_at >=', $thirtyDaysAgo)
          ->countAllResults();

        $last30Rejected = $db->table('walking_tracking')
          ->where('provider_id', $providerId)
          ->where('status', 'rejected')
          ->where('created_at >=', $thirtyDaysAgo)
          ->countAllResults();

        $last30Cancelled = $db->table('quotations')
          ->where('provider_id', $providerId)
          ->where('status', 'Cancelled')
          ->where('created_at >=', $thirtyDaysAgo)
          ->countAllResults();

        $last30RatingData = $db->table('sp_reviews')
          ->select('AVG(rating) as avg_rating')
          ->where('provider_id', $providerId)->where('service_id', 4)
          ->where('created_at >=', $thirtyDaysAgo)
          ->get()->getRow();
        $last30AvgRating  = (float) ($last30RatingData->avg_rating ?? 0);

        // On-time Calculation
        $onTimeData = $db->table('walking_tracking')
          ->select('COUNT(*) as total_timed, SUM(CASE WHEN start_time <= DATE_ADD(CONCAT(tracking_date, " ", service_time), INTERVAL 10 MINUTE) THEN 1 ELSE 0 END) as on_time_count')
          ->where('provider_id', $providerId)
          ->where('status', 'completed')
          ->get()->getRow();
        $onTimePct  = ($onTimeData->total_timed > 0) ? ($onTimeData->on_time_count / $onTimeData->total_timed) * 100 : 100;

        $last30OnTimeData = $db->table('walking_tracking')
          ->select('COUNT(*) as total_timed, SUM(CASE WHEN start_time <= DATE_ADD(CONCAT(tracking_date, " ", service_time), INTERVAL 10 MINUTE) THEN 1 ELSE 0 END) as on_time_count')
          ->where('provider_id', $providerId)
          ->where('status', 'completed')
          ->where('tracking_date >=', date('Y-m-d', strtotime('-30 days')))
          ->get()->getRow();
        $last30OnTimePct  = ($last30OnTimeData->total_timed > 0) ? ($last30OnTimeData->on_time_count / $last30OnTimeData->total_timed) * 100 : 100;

        // Complaints
        $complaintCount = $db->table('sp_bookings')
          ->where('provider_id', $providerId)
          ->where('report_reason !=', '')
          ->where('updated_at >=', $thirtyDaysAgo)
          ->countAllResults();

        // Strikes
        $strikeData  = $db->table('sp_strikes')
          ->select('SUM(strike_weight) as strike_count')
          ->where('provider_id', $providerId)
          ->where('is_active', 1)
          ->where('expires_at >', date('Y-m-d H:i:s'))
          ->get()->getRow();
        $strikeCount = (int) ($strikeData->strike_count ?? 0);

        // Rates
        $cancellationRate = $totalWalks > 0 ? ($cancelledWalks / $totalWalks) * 100 : 0;
        $untrackedRate    = $totalWalks > 0 ? ($untrackedWalks / $totalWalks) * 100 : 0;
        $rejectedRate     = $totalWalks > 0 ? ($rejectedWalks / $totalWalks) * 100 : 0;
        $complaintRate    = $completedWalks > 0 ? ($complaintCount / $completedWalks) * 100 : 0;

        $last30dTotal            = $last30Walks + $last30Cancelled + $last30Untracked + $last30Rejected;
        $last30dUntrackedRate    = ($last30dTotal > 0) ? ($last30Untracked / $last30dTotal) * 100 : 0;
        $last30dRejectedRate     = ($last30dTotal > 0) ? ($last30Rejected / $last30dTotal) * 100 : 0;
        $last30dCancellationRate = ($last30dTotal > 0) ? ($last30Cancelled / $last30dTotal) * 100 : 0;

        // Effective Rating: 60% recent + 40% lifetime
        $effectiveRating = ($last30AvgRating > 0)
          ? ($last30AvgRating * 0.6) + ($avgRating * 0.4)
          : $avgRating;

        // --- Ranking Score (out of 100) ---
        $scoreRating     = ($effectiveRating / 5.0) * 100 * 0.35;
        $scoreOnTime     = $onTimePct * 0.20;
        $scoreCompletion = $completionRate * 0.15;
        $scoreActivity   = min($last30Walks * 5, 100) * 0.15;
        $scoreRepeat     = min($repeatClients * 10, 100) * 0.10;
        $scoreExperience = min($effectiveExperience * 20, 100) * 0.05;

        $baseScore = $scoreRating + $scoreOnTime + $scoreCompletion + $scoreActivity + $scoreRepeat + $scoreExperience;

        $lifetimePenalty = ($untrackedRate * 0.10) + ($rejectedRate * 0.10) + ($cancellationRate * 0.05);
        $recentPenalty   = ($last30dUntrackedRate * 0.15) + ($last30dRejectedRate * 0.15) + ($last30dCancellationRate * 0.10);
        $penalty         = $lifetimePenalty + $recentPenalty;

        $rankingScore = max(0, min(100, round($baseScore - $penalty, 2)));

        // --- Tier Assignment ---
        $tier = 3;

        $meetsT1 = (
          $effectiveRating >= 4.5 && $completedWalks >= 30 && $onTimePct >= 90
          && $cancellationRate <= 5 && $completionRate >= 85
          && $untrackedRate <= 5 && $rejectedRate <= 3
          && $last30Walks >= 10 && $last30dUntrackedRate <= 5 && $last30dRejectedRate <= 3
          && $complaintRate < 2 && $strikeCount < 5 && $effectiveExperience >= 2
        );

        $meetsT2 = (
          $effectiveRating >= 4.0 && $completedWalks >= 10 && $onTimePct >= 80
          && $cancellationRate <= 10 && $untrackedRate <= 15 && $rejectedRate <= 10
          && $last30Walks >= 5 && $strikeCount < 8 && $effectiveExperience >= 1
        );

        if ($meetsT1) $tier = 1;
        elseif ($meetsT2) $tier = 2;

        // Save to DB
        $statsData = [
          'provider_id'                => $providerId,
          'total_walks'                => $totalWalks,
          'completed_walks'            => $completedWalks,
          'untracked_walks'            => $untrackedWalks,
          'rejected_walks'             => $rejectedWalks,
          'cancelled_walks'            => $cancelledWalks,
          'completion_rate'            => $completionRate,
          'cancellation_rate'          => $cancellationRate,
          'on_time_percentage'         => $onTimePct,
          'avg_rating'                 => $avgRating,
          'weighted_rating'            => round($effectiveRating, 2),
          'complaint_count'            => $complaintCount,
          'complaint_rate'             => round($complaintRate, 2),
          'repeat_client_count'        => $repeatClients,
          'last_30d_walks'             => $last30Walks,
          'last_30d_avg_rating'        => round($last30AvgRating, 2),
          'last_30d_on_time_pct'       => round($last30OnTimePct, 2),
          'last_30d_cancellation_rate' => round($last30dCancellationRate, 2),
          'last_30d_complaint_rate'    => round($complaintRate, 2),
          'ranking_score'              => $rankingScore,
          'tier'                       => $tier,
          'experience'                 => round($effectiveExperience, 2),
          'strike_count'               => $strikeCount,
          'last_evaluated_at'          => date('Y-m-d H:i:s'),
          'updated_at'                 => date('Y-m-d H:i:s')
        ];

        // Insert or Update
        $exists = $db->table('sp_stats')->where('provider_id', $providerId)->countAllResults();
        if ($exists > 0) {
          $db->table('sp_stats')->where('provider_id', $providerId)->update($statsData);
        } else {
          $statsData['created_at'] = date('Y-m-d H:i:s');
          $db->table('sp_stats')->insert($statsData);
        }
      } catch (\Throwable $e) {
        log_message('error', "[TierEval] Failed for Provider {$providerId}: " . $e->getMessage());
      }
    }

    if ($isCli) {
      echo "Evaluation Complete.\n";
    }

    return $this->response->setJSON(['status' => true, 'message' => 'Evaluation complete']);
  }
}
