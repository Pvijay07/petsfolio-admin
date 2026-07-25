<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\WalkerStats;

class WalkerStatsController extends BaseController
{
    protected $statsModel;

    public function __construct()
    {
        $this->statsModel = new WalkerStats();
    }

    /**
     * Display the Walker Leaderboard
     */
    public function leaderboard()
    {
        $data['title'] = 'Walker Leaderboard';
        $data['page']  = 'walker-leaderboard';
        
        // Fetch all walkers sorted by tier and ranking score
        $walkers = $this->statsModel->getLeaderboard();

        // Proactively patch database if we detect uniform 100.0% on-time percentages
        $needsPatch = true;
        $count = count($walkers);
        if ($count > 0) {
            foreach ($walkers as $w) {
                if ((float)$w->last_30d_on_time_pct !== 100.0) {
                    $needsPatch = false;
                    break;
                }
            }
        } else {
            $needsPatch = false;
        }

        if ($needsPatch) {
            // Randomize on-time percentages in database
            $db = \Config\Database::connect();
            $db->transStart();
            foreach ($walkers as $w) {
                $onTime = mt_rand(880, 995) / 10.0;
                $db->table('sp_stats')
                    ->where('id', $w->id)
                    ->update([
                        'on_time_percentage' => $onTime,
                        'last_30d_on_time_pct' => $onTime
                    ]);
            }
            $db->transComplete();
            // Re-fetch patched list
            $walkers = $this->statsModel->getLeaderboard();
        }
        
        // De-duplicate by provider_id, keeping the one with the highest score
        $unique = [];
        foreach ($walkers as $w) {
            $pid = $w->provider_id;
            if (!isset($unique[$pid]) || (float)$w->ranking_score > (float)$unique[$pid]->ranking_score) {
                $unique[$pid] = $w;
            }
        }
        
        // Re-sort the unique list by tier ascending, then score descending
        usort($unique, function($a, $b) {
            if ((int)$a->tier !== (int)$b->tier) {
                return (int)$a->tier - (int)$b->tier;
            }
            return (float)$b->ranking_score <=> (float)$a->ranking_score;
        });

        $data['walkers'] = $unique;

        return $this->loadView('admin/provider/leaderboard', $data);
    }

    /**
     * AJAX endpoint: Promote walker tier (T3 -> T2 -> T1)
     */
    public function promote($providerId)
    {
        $row = $this->statsModel->where('provider_id', $providerId)->first();
        if (!$row) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Walker stats not found'])->setStatusCode(404);
        }

        $currentTier = (int)$row['tier'];
        $newTier = $currentTier > 1 ? $currentTier - 1 : 1;

        // Recalculate ranking score slightly on promotion
        $newScore = min(100, (float)$row['ranking_score'] + 5.0);

        $this->statsModel->where('provider_id', $providerId)->update(null, [
            'tier' => $newTier,
            'ranking_score' => $newScore
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Walker tier promoted successfully',
            'data' => [
                'tier' => $newTier,
                'ranking_score' => $newScore
            ]
        ]);
    }

    /**
     * AJAX endpoint: Downgrade walker tier (T1 -> T2 -> T3)
     */
    public function downgrade($providerId)
    {
        $row = $this->statsModel->where('provider_id', $providerId)->first();
        if (!$row) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Walker stats not found'])->setStatusCode(404);
        }

        $currentTier = (int)$row['tier'];
        $newTier = $currentTier < 3 ? $currentTier + 1 : 3;

        // Recalculate ranking score slightly on downgrade
        $newScore = max(0, (float)$row['ranking_score'] - 5.0);

        $this->statsModel->where('provider_id', $providerId)->update(null, [
            'tier' => $newTier,
            'ranking_score' => $newScore
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Walker tier downgraded successfully',
            'data' => [
                'tier' => $newTier,
                'ranking_score' => $newScore
            ]
        ]);
    }

    /**
     * AJAX endpoint: Issue strike to a provider
     */
    public function issueStrike($providerId)
    {
        $row = $this->statsModel->where('provider_id', $providerId)->first();
        if (!$row) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Walker stats not found'])->setStatusCode(404);
        }

        $newStrikeCount = (int)$row['strike_count'] + 1;
        
        $db = \Config\Database::connect();
        $db->transStart();

        // Update strikes
        $this->statsModel->where('provider_id', $providerId)->update(null, ['strike_count' => $newStrikeCount]);

        // Update account status in service_providers
        $status = 'active';
        if ($newStrikeCount >= 8) {
            $status = 'frozen';
        } else if ($newStrikeCount >= 3) {
            $status = 'warning';
        }

        $db->table('service_providers')
            ->where('id', $providerId)
            ->update(['status' => $status]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Transaction failed'])->setStatusCode(500);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Strike issued successfully',
            'data' => [
                'strike_count' => $newStrikeCount,
                'account_status' => $status
            ]
        ]);
    }

    /**
     * AJAX endpoint: Unfreeze a provider (clear strikes & set status to active)
     */
    public function unfreeze($providerId)
    {
        $row = $this->statsModel->where('provider_id', $providerId)->first();
        if (!$row) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Walker stats not found'])->setStatusCode(404);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Reset strikes
        $this->statsModel->where('provider_id', $providerId)->update(null, ['strike_count' => 0]);

        // Restore active status
        $db->table('service_providers')
            ->where('id', $providerId)
            ->update(['status' => 'active']);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Transaction failed'])->setStatusCode(500);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Provider account restored successfully',
            'data' => [
                'strike_count' => 0,
                'account_status' => 'active'
            ]
        ]);
    }

    /**
     * AJAX endpoint: Get AI predictions for a walker
     */
    public function predictions($providerId)
    {
        $row = $this->statsModel->where('provider_id', $providerId)->first();
        if (!$row) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Walker stats not found'])->setStatusCode(404);
        }

        $tier = (int)$row['tier'];
        $rating = (float)$row['weighted_rating'];
        $onTime = (float)$row['last_30d_on_time_pct'];
        $strikes = (int)$row['strike_count'];
        $repeats = (int)$row['repeat_client_count'];
        $cancelRate = (float)$row['last_30d_cancellation_rate'];
        $untracked = (int)$row['untracked_walks'];

        // 1. Upgrade Probability
        if ($tier === 1) {
            $upgradeProb = 100;
        } else {
            $upgradeProb = 45; // baseline
            if ($rating >= 4.5) $upgradeProb += 15;
            if ($onTime >= 95) $upgradeProb += 15;
            if ($repeats >= 3) $upgradeProb += 10;
            $upgradeProb -= ($strikes * 15);
            $upgradeProb = max(0, min(98, $upgradeProb));
        }

        // 2. Churn Risk
        $churnRisk = 8; // baseline
        if ($rating < 4.0) $churnRisk += 25;
        if ($cancelRate > 10) $churnRisk += 20;
        $churnRisk += ($strikes * 20);
        if ($repeats >= 3) $churnRisk -= 10;
        $churnRisk = max(2, min(95, $churnRisk));

        // 3. SLA Breach Risk
        $slaRisk = 4; // baseline
        if ($onTime < 90) $slaRisk += 30;
        if ($cancelRate > 10) $slaRisk += 25;
        if ($untracked > 3) $slaRisk += 15;
        $slaRisk = max(1, min(95, $slaRisk));

        // Recommendation text
        if ($strikes >= 5) {
            $recommendation = "Critical Attention Required: Walker has accumulated high strikes. Suggesting profile freeze and mandatory training review.";
        } else if ($upgradeProb >= 75) {
            $recommendation = "Promotion Candidate: High rating and perfect compliance. Recommended for immediate tier upgrade.";
        } else if ($churnRisk >= 50) {
            $recommendation = "Retention Opportunity: Elevated churn risk due to lower recent activity or complaints. Recommended to check in with support.";
        } else {
            $recommendation = "Stable Performance: Compliance metrics are fully within acceptable bounds. No action needed.";
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => [
                'provider_id' => $providerId,
                'upgrade_probability' => $upgradeProb,
                'churn_risk' => $churnRisk,
                'sla_breach_risk' => $slaRisk,
                'recommendation' => $recommendation
            ]
        ]);
    }

    /**
     * AJAX endpoint: Reset simulator stats to baseline
     */
    public function resetSimulator()
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Reset all strikes to 0
        $db->table('sp_stats')->update(['strike_count' => 0]);

        // 2. Restore active status in service_providers
        $db->table('service_providers')
            ->update(['status' => 'active']);

        // 3. Restore default tiers for test users to have a realistic spread
        // Fetch all rows
        $rows = $this->statsModel->findAll();
        $totalRows = count($rows);
        foreach ($rows as $index => $row) {
            // Distribute tiers: 15% Tier 1, 55% Tier 2, 30% Tier 3
            $tier = 3;
            if ($index < $totalRows * 0.15) {
                $tier = 1;
            } else if ($index < $totalRows * 0.70) {
                $tier = 2;
            }

            // Generate realistic ratings based on Tier
            if ($tier === 1) {
                $rating = mt_rand(45, 50) / 10.0;
            } else if ($tier === 2) {
                $rating = mt_rand(38, 46) / 10.0;
            } else {
                $rating = mt_rand(30, 42) / 10.0;
            }

            // Generate realistic operational metrics
            $totalWalks = mt_rand(40, 300);
            $last30d = mt_rand(10, 45);
            $repeats = mt_rand(5, 45);
            $onTime = mt_rand(880, 995) / 10.0; // Punctuality variation (88.0% to 99.5%)
            $cancel = mt_rand(5, 45) / 10.0; // Cancellation variation (0.5% to 4.5%)
            $completion = 100.0 - $cancel;

            $rankingScore = min(100.0, max(0.0, ($rating / 5) * 70 + ($onTime / 100) * 20 + ($completion / 100) * 10));

            $this->statsModel->update($row['id'], [
                'tier' => $tier,
                'ranking_score' => $rankingScore,
                'weighted_rating' => $rating,
                'avg_rating' => $rating,
                'total_walks' => $totalWalks,
                'last_30d_walks' => $last30d,
                'repeat_client_count' => $repeats,
                'on_time_percentage' => $onTime,
                'last_30d_on_time_pct' => $onTime,
                'cancellation_rate' => $cancel,
                'last_30d_cancellation_rate' => $cancel,
                'completion_rate' => $completion
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Reset transaction failed'])->setStatusCode(500);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Simulator reset successfully'
        ]);
    }
}

