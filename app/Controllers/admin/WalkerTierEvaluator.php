<?php

namespace App\Controllers\Cli;

use CodeIgniter\Controller;

class WalkerTierEvaluator extends Controller
{
  public function evaluate()
  {
    $isCli = is_cli();
    if (!$isCli && strpos(current_url(), 'cli/evaluate-walker-tiers') === false) {
      // Allow execution from admin controller or CLI
    }

    $db = \Config\Database::connect();

    // Ensure table sp_stats has required columns: in_grace_period, effective_experience
    if (!$db->fieldExists('in_grace_period', 'sp_stats')) {
      $db->query("ALTER TABLE sp_stats ADD COLUMN in_grace_period TINYINT(1) DEFAULT 0");
    }
    if (!$db->fieldExists('effective_experience', 'sp_stats')) {
      $db->query("ALTER TABLE sp_stats ADD COLUMN effective_experience DECIMAL(4,2) DEFAULT 0.00");
    }

    $msg = "Starting Walker Tier Evaluation Engine...\n";
    if ($isCli) echo $msg;

    // 1. Get all service providers in system (joining sp_services for service_id = 4 details)
    $walkers = $db->table('service_providers sp')
      ->select('sp.id as provider_id, sp.name, sp.status as account_status, sp.is_verified, sp.created_at as joined_at, swd.experience as signup_experience')
      ->join('sp_services ss', 'ss.provider_id = sp.id AND ss.service_id = 4', 'left')
      ->join('sp_walking_details swd', 'swd.provider_id = sp.id', 'left')
      ->get()
      ->getResult();

    $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));
    $now = new \DateTime();

    $processedCount = 0;

    foreach ($walkers as $walker) {
      $providerId = (int)$walker->provider_id;
      
      // --- 1. Dynamic Experience Calculation ---
      // Initial Signup Experience (Years)
      $signupExperience = floatval($walker->signup_experience ?? 0);
      
      // Platform Tenure (Years)
      $joinedAt = !empty($walker->joined_at) ? new \DateTime($walker->joined_at) : new \DateTime('-1 month');
      $tenureDays = max(0, $now->diff($joinedAt)->days);
      $platformTenureYears = round($tenureDays / 365.25, 2);

      // Rule: If a walker has been with Petsfolio for > 6 months (0.5 years),
      // Effective Experience = Initial Experience + Platform Tenure.
      if ($platformTenureYears > 0.5) {
        $effectiveExperience = round($signupExperience + $platformTenureYears, 2);
      } else {
        $effectiveExperience = round($signupExperience, 2);
      }

      // --- 2. Lifetime & 30-Day Performance Aggregation ---
      // Lifetime Tracking Walks (Case-insensitive status matching)
      $trackTotal = $db->table('walking_tracking')->where('provider_id', $providerId)->countAllResults();
      $trackCompleted = $db->table('walking_tracking')->where('provider_id', $providerId)->where('LOWER(status)', 'completed')->countAllResults();
      $trackCancelled = $db->table('walking_tracking')->where('provider_id', $providerId)->where('LOWER(status)', 'cancelled')->countAllResults();

      // Quotations / Bookings Walks
      $compRow = $db->query("SELECT 
        COUNT(CASE WHEN LOWER(wsb.status) = 'completed' THEN 1 END) AS comp_completed,
        COUNT(CASE WHEN LOWER(wsb.status) = 'cancelled' THEN 1 END) AS comp_cancelled,
        COUNT(CASE WHEN LOWER(wsb.status) = 'completed' AND wsb.created_at >= ? THEN 1 END) AS comp_last_30d,
        COUNT(*) AS comp_total
        FROM quotations q 
        JOIN walking_service_bookings wsb ON wsb.id = q.booking_id 
        WHERE q.provider_id = ? AND q.service_id = 4 AND LOWER(q.status) IN ('accepted', 'completed', 'cancelled')", 
        [$thirtyDaysAgo, $providerId]
      )->getRow();

      $totalWalks     = max($trackTotal, (int)($compRow->comp_total ?? 0));
      $completedWalks = max($trackCompleted, (int)($compRow->comp_completed ?? 0));
      $cancelledWalks = max($trackCancelled, (int)($compRow->comp_cancelled ?? 0));

      $completionRate   = $totalWalks > 0 ? round(($completedWalks / $totalWalks) * 100, 2) : 0.00;
      $cancellationRate = $totalWalks > 0 ? round(($cancelledWalks / $totalWalks) * 100, 2) : 0.00;

      // 30-Day Completed Walks
      $track30dCompleted = $db->table('walking_tracking')
        ->where('provider_id', $providerId)
        ->where('LOWER(status)', 'completed')
        ->where('created_at >=', $thirtyDaysAgo)
        ->countAllResults();
      $last30Walks = max($track30dCompleted, (int)($compRow->comp_last_30d ?? 0));

      // Ratings (Lifetime & 30-Day) - Only valid if provider has completed walks
      if ($completedWalks > 0) {
        $ratingData = $db->table('sp_reviews')
          ->select('AVG(rating) as avg_rating, COUNT(id) as total_reviews')
          ->where('provider_id', $providerId)
          ->get()->getRow();
        $avgRating = floatval($ratingData->avg_rating ?? 0);
        $totalReviews = (int)($ratingData->total_reviews ?? 0);

        $last30RatingData = $db->table('sp_reviews')
          ->select('AVG(rating) as avg_rating')
          ->where('provider_id', $providerId)
          ->where('created_at >=', $thirtyDaysAgo)
          ->get()->getRow();
        $last30AvgRating = floatval($last30RatingData->avg_rating ?? 0);

        if ($avgRating > 0) {
          if ($last30AvgRating > 0 && $totalReviews > 0) {
            $weightedRating = round((0.7 * $last30AvgRating) + (0.3 * $avgRating), 2);
          } else {
            $weightedRating = round($avgRating, 2);
          }
        } else {
          // Active walker with completed walks but unrated by customer defaults to 5.00 rating
          $avgRating = 5.00;
          $weightedRating = 5.00;
          $last30AvgRating = 5.00;
        }
      } else {
        // Walker with 0 completed walks has 0 rating on platform
        $avgRating = 0.00;
        $last30AvgRating = 0.00;
        $weightedRating = 0.00;
      }

      // On-Time Performance (Start time within 10 mins of scheduled time)
      if ($completedWalks > 0) {
        // Query completed walks with start_time & service_time
        $onTimeQuery = $db->query("SELECT 
          COUNT(*) as total_tracked,
          SUM(CASE WHEN start_time IS NOT NULL AND (
            ABS(TIMESTAMPDIFF(MINUTE, CONCAT(tracking_date, ' ', service_time), start_time)) <= 10
            OR TIMESTAMPDIFF(MINUTE, CONCAT(tracking_date, ' ', service_time), start_time) <= 10
          ) THEN 1 ELSE 0 END) as on_time_count
          FROM walking_tracking 
          WHERE provider_id = ? AND LOWER(status) = 'completed' AND service_time IS NOT NULL AND tracking_date IS NOT NULL", 
          [$providerId]
        )->getRow();

        $totalTracked = (int)($onTimeQuery->total_tracked ?? 0);
        $onTimeCount = (int)($onTimeQuery->on_time_count ?? 0);

        if ($totalTracked > 0) {
          $onTimePct = round(($onTimeCount / $totalTracked) * 100, 2);
        } else {
          // If walker completed walks but no precise GPS tracking recorded, default to 100.0%
          $onTimePct = 100.00;
        }
      } else {
        // Strict Rule: If walker has completed 0 walks, on-time percentage is 0.0%
        $onTimePct = 0.00;
      }
      $last30OnTimePct = $onTimePct;

      // Repeat Clients
      $repeatQuery = $db->query("SELECT COUNT(*) as repeat_clients FROM (
        SELECT wsb.user_id 
        FROM quotations q 
        JOIN walking_service_bookings wsb ON wsb.id = q.booking_id 
        WHERE q.provider_id = ? AND LOWER(wsb.status) = 'completed' 
        GROUP BY wsb.user_id HAVING COUNT(wsb.id) > 1
      ) as t", [$providerId]);
      $repeatClients = (int)($repeatQuery->getRow()->repeat_clients ?? 0);

      // Complaints & Account Health (Strikes)
      $complaintCount = 0;
      $complaintRate  = $completedWalks > 0 ? round(($complaintCount / $completedWalks) * 100, 2) : 0.00;

      // Fetch current stats record for strike_count, previous tier & grace period state
      $existingStats = $db->table('sp_stats')->where('provider_id', $providerId)->get()->getRow();
      $strikeCount    = (int)($existingStats->strike_count ?? 0);
      $currentTier    = (int)($existingStats->tier ?? 3);
      $prevGracePeriod = (int)($existingStats->in_grace_period ?? 0);

      $isPoliceVerified = (int)($walker->is_verified ?? 0);

      // --- 3. Tier Qualification Evaluation ---
      // Tier 1 (Premium) Requirements
      $t1_exp        = ($effectiveExperience >= 2.0 || $completedWalks >= 30);
      $t1_rating     = ($avgRating >= 4.5);
      $t1_walks      = ($completedWalks >= 30);
      $t1_last30     = ($last30Walks >= 10);
      $t1_onTime     = ($onTimePct >= 90.0);
      $t1_cancel     = ($cancellationRate <= 5.0);
      $t1_completion = ($completionRate >= 85.0);
      $t1_complaint  = ($complaintRate < 2.0);
      $t1_verified   = ($isPoliceVerified === 1);
      $t1_strikes    = ($strikeCount < 5);

      $eligibleTier1 = $t1_exp && $t1_rating && $t1_walks && $t1_last30 && $t1_onTime && 
                       $t1_cancel && $t1_completion && $t1_complaint && $t1_verified && $t1_strikes;

      // Tier 2 (Standard) Requirements
      $t2_exp        = ($effectiveExperience >= 1.0 || $completedWalks >= 10);
      $t2_rating     = ($avgRating >= 4.0);
      $t2_walks      = ($completedWalks >= 10);
      $t2_last30     = ($last30Walks >= 5);
      $t2_onTime     = ($onTimePct >= 80.0);
      $t2_cancel     = ($cancellationRate <= 10.0);
      $t2_strikes    = ($strikeCount < 8);

      $eligibleTier2 = $t2_exp && $t2_rating && $t2_walks && $t2_last30 && $t2_onTime && $t2_cancel && $t2_strikes;

      // Tier 3 (Entry) Requirements (Completing 1 service automatically places walker into Tier 3 Entry level)
      $t3_exp        = ($effectiveExperience >= 0.5 || $completedWalks >= 1);
      $t3_rating     = ($avgRating >= 3.0 || $completedWalks >= 1);
      $t3_walks      = ($completedWalks >= 1);
      $t3_last30     = ($last30Walks >= 1 || $completedWalks >= 1);
      $t3_onTime     = ($onTimePct >= 70.0 || $completedWalks >= 1);
      $t3_cancel     = ($cancellationRate <= 15.0);
      $t3_strikes    = ($strikeCount < 10);

      $eligibleTier3 = $t3_exp && $t3_rating && $t3_walks && $t3_last30 && $t3_onTime && $t3_cancel && $t3_strikes;

      if ($completedWalks == 0) {
        // Walkers with 0 completed services stay in Joined card (Tier 3 DB representation with 0 walks, 0 rating, 0 score)
        $calculatedTier = 3;
      } elseif ($eligibleTier1) {
        $calculatedTier = 1;
      } elseif ($eligibleTier2) {
        $calculatedTier = 2;
      } else {
        // Automatic Tier 3 (Entry Level) for any walker who completes at least 1 service
        $calculatedTier = 3;
      }

      // --- 4. Special Business Rules: Account Freezing & Grace Period ---
      $accountStatus = strtolower(trim($walker->account_status ?? 'active'));
      $isFrozen = ($strikeCount >= 8) || in_array($accountStatus, ['frozen', 'suspended', 'inactive', 'blocked', 'disabled']);

      if ($isFrozen) {
        // Account Freezing: Freeze account if 8+ strikes, lock tier promotion, demote to Tier 3 if 8+ strikes
        if ($strikeCount >= 8) {
          $db->table('service_providers')->where('id', $providerId)->update(['status' => 'frozen']);
          $finalTier = 3;
        } else {
          $finalTier = max($currentTier, $calculatedTier); // Lock tier from promotion
        }
        $inGracePeriod = 0;
      } else {
        // Downgrade Protection (1-time Grace Period)
        if ($calculatedTier > $currentTier && $completedWalks > 0) { // Tier calculated is worse than current tier
          if ($prevGracePeriod === 1) {
            // Second consecutive failure: apply downgrade
            $finalTier = $calculatedTier;
            $inGracePeriod = 0;
          } else {
            // First failure: protect current tier for 1 cycle!
            $finalTier = $currentTier;
            $inGracePeriod = 1;
          }
        } else {
          // Meets or improves tier: reset grace period flag
          $finalTier = $calculatedTier;
          $inGracePeriod = 0;
        }
      }

      // --- 5. Composite Ranking Score Calculation (0-100) ---
      // If a walker has 0 completed walks or 0 reviews, operational scores evaluate to 0.
      if ($completedWalks == 0) {
        $rankingScore = 0.0000;
      } else {
        $scoreRating     = ($avgRating > 0) ? ($weightedRating / 5.0) * 40.0 : 0.0;
        $scoreOnTime     = ($onTimePct / 100.0) * 20.0;
        $scoreCompletion = ($totalWalks > 0) ? ($completionRate / 100.0) * 15.0 : 0.0;
        $scoreActivity   = min($last30Walks / 30.0, 1.0) * 15.0;
        $scoreRepeat     = min($repeatClients / 10.0, 1.0) * 10.0;

        $rankingScore = round($scoreRating + $scoreOnTime + $scoreCompletion + $scoreActivity + $scoreRepeat, 4);
      }

      // --- 6. Persist Calculated Stats ---
      $statsData = [
        'provider_id'                => $providerId,
        'total_walks'                => $totalWalks,
        'completed_walks'            => $completedWalks,
        'cancelled_walks'            => $cancelledWalks,
        'completion_rate'            => $completionRate,
        'cancellation_rate'          => $cancellationRate,
        'on_time_percentage'         => $onTimePct,
        'avg_rating'                 => $avgRating,
        'weighted_rating'            => $weightedRating,
        'complaint_count'            => $complaintCount,
        'complaint_rate'             => $complaintRate,
        'repeat_client_count'        => $repeatClients,
        'last_30d_walks'             => $last30Walks,
        'last_30d_avg_rating'        => $last30AvgRating,
        'last_30d_on_time_pct'       => $last30OnTimePct,
        'last_30d_cancellation_rate' => $cancellationRate,
        'last_30d_complaint_rate'    => $complaintRate,
        'ranking_score'              => $rankingScore,
        'tier'                       => $finalTier,
        'strike_count'               => $strikeCount,
        'experience'                 => $signupExperience,
        'effective_experience'       => $effectiveExperience,
        'in_grace_period'            => $inGracePeriod,
        'last_evaluated_at'          => date('Y-m-d H:i:s'),
        'updated_at'                 => date('Y-m-d H:i:s')
      ];

      if ($existingStats) {
        $db->table('sp_stats')->where('provider_id', $providerId)->update($statsData);
      } else {
        $statsData['created_at'] = date('Y-m-d H:i:s');
        $db->table('sp_stats')->insert($statsData);
      }

      $processedCount++;
    }

    $summary = "Evaluation Complete: Processed {$processedCount} walkers.\n";
    if ($isCli) echo $summary;

    return json_encode([
      'status' => true, 
      'message' => "Walker tier evaluation complete for {$processedCount} providers."
    ]);
  }
}
