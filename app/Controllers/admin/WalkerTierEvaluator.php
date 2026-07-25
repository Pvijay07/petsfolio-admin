<?php

namespace App\Controllers\Cli;

use CodeIgniter\Controller;
use App\Models\Provider;

class WalkerTierEvaluator extends Controller
{
  public function evaluate()
  {
    if (!is_cli()) {
      return "This script must be run from the command line.";
    }

    echo "Starting Walker Tier Evaluation...\n";

    $db = \Config\Database::connect();

    // 1. Get all active walkers (service_id = 4)
    $walkers = $db->table('sp_services')
      ->select('provider_id')
      ->where('service_id', 4)
      ->where('status', 'active')
      ->get()
      ->getResult();

    $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));

    foreach ($walkers as $walker) {
      $providerId = $walker->provider_id;
      echo "Evaluating Provider ID: $providerId\n";

      // --- Lifetime Stats ---
      // Total Walks (Ever assigned)
      $totalWalks = $db->table('walking_tracking')->where('provider_id', $providerId)->countAllResults();

      // Completed Walks
      $completedWalks = $db->table('walking_tracking')->where('provider_id', $providerId)->where('status', 'completed')->countAllResults();

      // Cancelled by Walker (Assuming status = 'cancelled_by_sp' exists, if not using a placeholder logic)
      // *Adapt this depending on exact DB status*
      $cancelledWalks = $db->table('walking_tracking')->where('provider_id', $providerId)->where('status', 'cancelled')->countAllResults();

      $completionRate = $totalWalks > 0 ? ($completedWalks / $totalWalks) * 100 : 0;
      $cancellationRate = $totalWalks > 0 ? ($cancelledWalks / $totalWalks) * 100 : 0;

      // Avg Rating
      $ratingData = $db->table('sp_reviews')
        ->select('AVG(rating) as avg_rating, COUNT(id) as total')
        ->where('provider_id', $providerId)->where('service_id', 4)
        ->get()->getRow();
      $avgRating = $ratingData->avg_rating ?? 0;

      // Repeat Clients (Count distinct users who booked > 1 time)
      $repeatQuery = $db->query("SELECT COUNT(*) as repeat_clients FROM (
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

      $last30RatingData = $db->table('sp_reviews')
        ->select('AVG(rating) as avg_rating')
        ->where('provider_id', $providerId)->where('service_id', 4)
        ->where('created_at >=', $thirtyDaysAgo)
        ->get()->getRow();
      $last30AvgRating = $last30RatingData->avg_rating ?? 0;

      // Mock On-time calculation (assuming start_time vs service_time exists in walking_tracking)
      // Using placeholder 95% if not computable
      $onTimePct = 95.00;
      $last30OnTimePct = 95.00;
      $complaintCount = 0;
      $complaintRate = 0;
      $strikeCount = 0; // In reality, fetch from strike history table

      // --- Ranking Score Calculation ---
      // Normalized out of 100
      $scoreRating = ($avgRating / 5.0) * 40;
      $scoreOnTime = ($onTimePct / 100) * 20;
      $scoreCompletion = ($completionRate / 100) * 15;
      $scoreActivity = min($last30Walks / 30, 1) * 15; // Max points at 30 walks/month
      $scoreRepeat = min($repeatClients / 10, 1) * 10; // Max points at 10 repeat clients

      $rankingScore = $scoreRating + $scoreOnTime + $scoreCompletion + $scoreActivity + $scoreRepeat;

      // --- Tier Assignment ---
      $tier = 3; // Default

      if ($avgRating >= 4.5 && $completedWalks >= 30 && $onTimePct >= 90 && $cancellationRate <= 5 && $last30Walks >= 10 && $complaintRate < 2) {
        $tier = 1;
      } elseif ($avgRating >= 4.0 && $completedWalks >= 10 && $onTimePct >= 80 && $cancellationRate <= 10 && $last30Walks >= 5) {
        $tier = 2;
      }

      // Save to DB
      $statsData = [
        'provider_id' => $providerId,
        'total_walks' => $totalWalks,
        'completed_walks' => $completedWalks,
        'cancelled_walks' => $cancelledWalks,
        'completion_rate' => $completionRate,
        'cancellation_rate' => $cancellationRate,
        'on_time_percentage' => $onTimePct,
        'avg_rating' => $avgRating,
        'weighted_rating' => $avgRating, // simplified
        'complaint_count' => $complaintCount,
        'complaint_rate' => $complaintRate,
        'repeat_client_count' => $repeatClients,
        'last_30d_walks' => $last30Walks,
        'last_30d_avg_rating' => $last30AvgRating,
        'last_30d_on_time_pct' => $last30OnTimePct,
        'last_30d_cancellation_rate' => $cancellationRate,
        'last_30d_complaint_rate' => $complaintRate,
        'ranking_score' => $rankingScore,
        'tier' => $tier,
        'strike_count' => $strikeCount,
        'last_evaluated_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ];

      // Insert or Update
      $exists = $db->table('sp_stats')->where('provider_id', $providerId)->countAllResults();
      if ($exists > 0) {
        $db->table('sp_stats')->where('provider_id', $providerId)->update($statsData);
      } else {
        $statsData['created_at'] = date('Y-m-d H:i:s');
        $db->table('sp_stats')->insert($statsData);
      }
    }

    echo "Evaluation Complete.\n";
    return json_encode(['status' => true, 'message' => 'Evaluation complete']);
  }
}
