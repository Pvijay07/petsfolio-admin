<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\WalkerStats;
use App\Libraries\AIAnalyst\GeminiAnalyst;

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
        $data['totalRegisteredWalkers'] = $this->statsModel->getTotalRegisteredWalkers();

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
     * AJAX endpoint: Get AI predictions & suggestions for a walker
     */
    public function predictions($providerId)
    {
        $row = $this->statsModel->where('provider_id', $providerId)->first();
        if (!$row) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Walker stats not found'])->setStatusCode(404);
        }

        $db = \Config\Database::connect();
        $spInfo = $db->table('service_providers')
            ->select('name, mobile, area, city, status, created_at')
            ->where('id', $providerId)
            ->get()->getRowArray() ?? [];

        $name       = $spInfo['name'] ?? 'Walker #' . $providerId;
        $tier       = (int)($row['tier'] ?? 3);
        $score      = (float)($row['ranking_score'] ?? 0);
        $rating     = (float)($row['weighted_rating'] ?? 5.0);
        $onTime     = (float)($row['last_30d_on_time_pct'] ?? 100);
        $strikes    = (int)($row['strike_count'] ?? 0);
        $repeats    = (int)($row['repeat_client_count'] ?? 0);
        $cancelRate = (float)($row['last_30d_cancellation_rate'] ?? 0);
        $untracked  = (int)($row['untracked_walks'] ?? 0);
        $totalWalks = (int)($row['total_walks_lifetime'] ?? 0);
        $compRate   = (float)($row['last_30d_completion_rate'] ?? 100);

        // 1. Upgrade Probability
        if ($tier === 1) {
            $upgradeProb = 100;
        } else {
            $upgradeProb = 45;
            if ($rating >= 4.5) $upgradeProb += 15;
            if ($onTime >= 95) $upgradeProb += 15;
            if ($repeats >= 3) $upgradeProb += 10;
            $upgradeProb -= ($strikes * 15);
            $upgradeProb = max(0, min(98, $upgradeProb));
        }

        // 2. Churn Risk
        $churnRisk = 8;
        if ($rating < 4.0) $churnRisk += 25;
        if ($cancelRate > 10) $churnRisk += 20;
        $churnRisk += ($strikes * 20);
        if ($repeats >= 3) $churnRisk -= 10;
        $churnRisk = max(2, min(95, $churnRisk));

        // 3. SLA Breach Risk
        $slaRisk = 4;
        if ($onTime < 90) $slaRisk += 30;
        if ($cancelRate > 10) $slaRisk += 25;
        if ($untracked > 3) $slaRisk += 15;
        $slaRisk = max(1, min(95, $slaRisk));

        // Build AI Prompt for Gemini Analyst
        $prompt = "You are an AI Walker Performance Analyst. Provide a brief, concise 2-3 sentence performance summary and recommendation for Walker {$name} (Tier {$tier}, Rating {$rating}/5, On-Time {$onTime}%, Strikes {$strikes}). Do NOT write a long essay or raw markdown headers. Focus strictly on key strengths and immediate advice.";

        $strengths = [];
        $improvements = [];
        $suggestions = [];

        if ($rating >= 4.5) $strengths[] = "High customer satisfaction rating (" . number_format($rating, 2) . "/5.00).";
        if ($onTime >= 95) $strengths[] = "Excellent punctuality adherence ({$onTime}% on-time arrival).";
        if ($repeats >= 3) $strengths[] = "Strong client loyalty with {$repeats} repeat pet owners.";
        if ($totalWalks >= 20) $strengths[] = "High experience volume with {$totalWalks} lifetime walks completed.";

        if (empty($strengths)) $strengths[] = "Verified active provider account ready for client walk assignments.";

        if ($cancelRate > 5) $improvements[] = "Reduce recent 30-day cancellation rate from {$cancelRate}% to below 5%.";
        if ($untracked > 0) $improvements[] = "Ensure full GPS tracking on all walks to eliminate untracked walk flags ({$untracked} untracked).";
        if ($strikes > 0) $improvements[] = "Address policy compliance to avoid accumulating additional strikes ({$strikes} current strikes).";
        if ($repeats < 2) $improvements[] = "Increase client repeat booking retention rate by providing detailed walk logs and photos.";

        if (empty($improvements)) $improvements[] = "Maintain current top-tier operational metrics and quick response time.";

        if ($strikes >= 5) {
            $recommendation = "Critical Attention Required: Walker has accumulated {$strikes} strikes. Suggest profile freeze, direct manager audit, and mandatory training review.";
            $suggestions[] = "Immediate account review required before assigning high-value walk requests.";
        } else if ($upgradeProb >= 75) {
            $recommendation = "Promotion Candidate: Walker displays high rating ({$rating}) and strong compliance ({$onTime}% on-time). Recommended for immediate Tier promotion.";
            $suggestions[] = "Promote to Tier " . max(1, $tier - 1) . " to unlock premium client walk dispatching.";
        } else if ($churnRisk >= 50) {
            $recommendation = "Retention Opportunity: Elevated churn risk ({$churnRisk}%) due to low recent walk activity or cancellations. Recommended to check in with support.";
            $suggestions[] = "Reach out via WhatsApp/Call support to resolve schedule bottlenecks.";
        } else {
            $recommendation = "Stable Performance: Compliance and service quality metrics are fully within acceptable bounds. No corrective action needed.";
            $suggestions[] = "Keep walker active in top priority dispatch queue for " . ($spInfo['area'] ?? 'assigned city area') . ".";
        }

        $aiSummaryText = $recommendation;

        try {
            $analyst = new GeminiAnalyst();
            $aiRes = $analyst->ask($prompt);
            if (!empty($aiRes['reply']) && strpos($aiRes['reply'], 'Error:') === false) {
                $aiSummaryText = $aiRes['reply'];
            }
        } catch (\Throwable $e) {
            // Graceful fallback to computed recommendation
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => [
                'provider_id'         => $providerId,
                'provider_name'       => $name,
                'tier'                => $tier,
                'ranking_score'       => $score,
                'upgrade_probability' => $upgradeProb,
                'churn_risk'          => $churnRisk,
                'sla_breach_risk'     => $slaRisk,
                'recommendation'      => $recommendation,
                'ai_summary'          => $aiSummaryText,
                'strengths'           => $strengths,
                'improvements'        => $improvements,
                'suggestions'         => $suggestions
            ]
        ]);
    }

    /**
     * AJAX endpoint: Fleet-wide Walker AI Insights & Recommendations
     */
    public function aiInsights()
    {
        $rawWalkers = $this->statsModel->getLeaderboard();

        // De-duplicate by provider_id, keeping the one with the highest score (matching leaderboard deduplication)
        $unique = [];
        foreach ($rawWalkers as $w) {
            $pid = $w->provider_id;
            if (!isset($unique[$pid]) || (float)$w->ranking_score > (float)$unique[$pid]->ranking_score) {
                $unique[$pid] = $w;
            }
        }
        $walkers = array_values($unique);

        $totalCount      = count($walkers);
        $t1Count         = 0;
        $t2Count         = 0;
        $t3Count         = 0;
        $joinedCount     = 0;
        $totalRating     = 0;
        $ratedCount      = 0;
        $atRiskCount     = 0;
        $promoCandidates = [];
        $atRiskList      = [];

        foreach ($walkers as $w) {
            $completed = (int)($w->completed_walks ?? 0);
            $tier      = (int)($w->tier ?? 3);
            $rating    = (float)($w->weighted_rating ?? $w->avg_rating ?? 0);
            $strikes   = (int)($w->strike_count ?? 0);
            $score     = (float)($w->ranking_score ?? 0);
            $status    = strtolower($w->account_status ?? 'active');

            if ($completed === 0) {
                $joinedCount++;
            } elseif ($tier === 1) {
                $t1Count++;
            } elseif ($tier === 2) {
                $t2Count++;
            } else {
                $t3Count++;
            }

            if ($rating > 0) {
                $totalRating += $rating;
                $ratedCount++;
            }

            // Evaluate at-risk: strikes >= 3, frozen status, or low rating < 3.5 for active providers with completed walks
            if ($strikes >= 3 || $status === 'frozen' || ($completed > 0 && $rating > 0 && $rating < 3.5)) {
                $atRiskCount++;
                if (count($atRiskList) < 5) {
                    $atRiskList[] = [
                        'provider_id' => $w->provider_id,
                        'name'        => $w->provider_name,
                        'tier'        => $tier,
                        'rating'      => $rating,
                        'strikes'     => $strikes,
                        'reason'      => $strikes >= 3 ? "{$strikes} strikes accumulated" : ($status === 'frozen' ? "Account frozen" : "Low rating (" . number_format($rating, 1) . ")")
                    ];
                }
            }

            if ($completed > 0 && ($tier === 2 || $tier === 3) && $score >= 70 && $rating >= 4.5 && $strikes === 0) {
                if (count($promoCandidates) < 5) {
                    $promoCandidates[] = [
                        'provider_id' => $w->provider_id,
                        'name'        => $w->provider_name,
                        'tier'        => $tier,
                        'rating'      => $rating,
                        'score'       => $score,
                        'target_tier' => max(1, $tier - 1)
                    ];
                }
            }
        }

        $avgRating = $ratedCount > 0 ? round($totalRating / $ratedCount, 2) : 3.50;

        // Group walkers dynamically by region/area for AI Area Intelligence
        $areaMap = [];
        foreach ($walkers as $w) {
            $areaName = !empty($w->city) ? $w->city : (!empty($w->area) ? $w->area : 'Primary Service Area');
            if (!isset($areaMap[$areaName])) {
                $areaMap[$areaName] = ['total' => 0, 't1' => 0, 't2' => 0, 't3' => 0, 'joined' => 0, 'sum_rating' => 0, 'rated_cnt' => 0];
            }
            $areaMap[$areaName]['total']++;
            $completed = (int)($w->completed_walks ?? 0);
            $tier      = (int)($w->tier ?? 3);
            $rating    = (float)($w->weighted_rating ?? $w->avg_rating ?? 0);

            if ($completed === 0) {
                $areaMap[$areaName]['joined']++;
            } elseif ($tier === 1) {
                $areaMap[$areaName]['t1']++;
            } elseif ($tier === 2) {
                $areaMap[$areaName]['t2']++;
            } else {
                $areaMap[$areaName]['t3']++;
            }

            if ($rating > 0) {
                $areaMap[$areaName]['sum_rating'] += $rating;
                $areaMap[$areaName]['rated_cnt']++;
            }
        }

        // 1. Dynamic AI Active Live Capacity Warnings
        $capacityWarnings = [];
        if ($joinedCount > 0) {
            $pctJoined = round(($joinedCount / max(1, $totalCount)) * 100, 1);
            $capacityWarnings[] = [
                'type'        => 'activation_bottleneck',
                'badge'       => 'CRITICAL WARNING',
                'badge_class' => 'bg-danger text-white',
                'icon'        => 'fas fa-user-clock text-danger',
                'title'       => 'First-Walk Onboarding Activation Bottleneck',
                'description' => "<span class='badge bg-info text-white me-1'>{$joinedCount} Joined Walkers ({$pctJoined}%)</span> have registered but completed 0 walks. AI Operational Directive: Direct onboarding call verification and first-walk completion incentives required."
            ];
        }

        if (($t1Count + $t2Count) < 5) {
            $capacityWarnings[] = [
                'type'        => 'tier_deficit',
                'badge'       => 'CAPACITY GAP',
                'badge_class' => 'bg-warning text-dark',
                'icon'        => 'fas fa-crown text-warning',
                'title'       => 'Tier 1 & Tier 2 Supply Deficit',
                'description' => "Only <span class='badge bg-warning text-dark me-1'>{$t1Count} Tier 1</span> & <span class='badge bg-primary text-white me-1'>{$t2Count} Tier 2</span> active walkers available platform-wide. AI Operational Directive: Fast-track promotion for top Tier 3 walkers with ranking score >= 70."
            ];
        }

        if ($atRiskCount > 0) {
            $capacityWarnings[] = [
                'type'        => 'at_risk_flag',
                'badge'       => 'COMPLIANCE RISK',
                'badge_class' => 'bg-danger text-white',
                'icon'        => 'fas fa-shield-virus text-danger',
                'title'       => 'At-Risk Compliance & Quality Alert',
                'description' => "<span class='badge bg-danger text-white me-1'>{$atRiskCount} At-Risk Walkers</span> flagged for low CSAT (< 3.5) or accumulated strikes. AI Operational Directive: Immediate quality review and account verification check-ins."
            ];
        }

        // 2. Dynamic AI Regional Area Intelligence & Demand-Supply Gaps
        $areaIntelligence = [];
        foreach ($areaMap as $area => $aStats) {
            $aAvg    = $aStats['rated_cnt'] > 0 ? round($aStats['sum_rating'] / $aStats['rated_cnt'], 2) : 3.50;
            $aTotal  = $aStats['total'];
            $aT1     = $aStats['t1'];
            $aT2     = $aStats['t2'];
            $aT3     = $aStats['t3'];
            $aJoined = $aStats['joined'];

            $demandStatus = 'Active Coverage';
            $badgeClass   = 'bg-info text-white';

            if ($aT1 === 0 && $aTotal >= 5) {
                $demandStatus = 'Critical Supply Deficit';
                $badgeClass   = 'bg-danger text-white fw-bold';
            } elseif ($aTotal >= 15) {
                $demandStatus = 'Very High Demand Zone';
                $badgeClass   = 'bg-warning text-dark fw-bold';
            } elseif ($aTotal >= 8) {
                $demandStatus = 'High Demand Zone';
                $badgeClass   = 'bg-primary text-white fw-bold';
            } elseif ($aJoined > $aTotal * 0.6) {
                $demandStatus = 'High Onboarding Backlog';
                $badgeClass   = 'bg-warning text-dark fw-bold';
            }

            $aiSuggestion = "";
            if ($aT1 === 0 && $aT2 > 0) {
                $aiSuggestion = "AI Regional Directive: Zero Tier 1 walkers in {$area}. Recommend promoting {$aT2} top-performing Tier 2 walker(s) to expand VIP fulfillment speed.";
            } elseif ($aT1 === 0 && $aT2 === 0) {
                $aiSuggestion = "AI Regional Directive: Critical Tier 1 & Tier 2 deficit in {$area} ({$aTotal} total walkers). Requires immediate first-walk activation calls for {$aJoined} joined signups.";
            } elseif ($aT1 >= 2) {
                $aiSuggestion = "AI Regional Directive: Strong premium coverage in {$area} ({$aT1} Tier 1, {$aT2} Tier 2). Fleet capacity meets peak daily client booking demand.";
            } elseif ($aAvg < 3.8) {
                $aiSuggestion = "AI Regional Directive: Service rating alert in {$area} ({$aAvg}/5.00 CSAT avg). Require mandatory quality & punctuality review for local walkers.";
            } else {
                $aiSuggestion = "AI Regional Directive: Operational performance stable in {$area} across {$aTotal} active providers with {$aAvg}/5.00 CSAT rating.";
            }

            $areaIntelligence[] = [
                'area'          => $area,
                'total_walkers' => $aTotal,
                't1'            => $aT1,
                't2'            => $aT2,
                't3'            => $aT3,
                'joined'        => $aJoined,
                'avg_rating'     => number_format($aAvg, 2),
                'demand_status' => $demandStatus,
                'badge_class'   => $badgeClass,
                'ai_suggestion' => $aiSuggestion
            ];
        }

        $areaSummaryStr = "";
        foreach ($areaIntelligence as $ai) {
            $areaSummaryStr .= "Area '{$ai['area']}': Status='{$ai['demand_status']}', Walkers={$ai['total_walkers']} (T1={$ai['t1']}, T2={$ai['t2']}, T3={$ai['t3']}, Joined={$ai['joined']}); ";
        }

        $prompt = "You are Petsfolio AI Walker Fleet Advisor. Provide a crisp executive summary (maximum 120 words) analyzing overall walker fleet performance and regional supply bottlenecks. Focus strictly on:\n" .
            "1. Roster health overview: Total registered active = {$totalCount} (Joined: {$joinedCount}, Tier 1: {$t1Count}, Tier 2: {$t2Count}, Tier 3: {$t3Count}), Avg Roster Rating = {$avgRating}/5.00, At-Risk = {$atRiskCount}.\n" .
            "2. Regional Area Bottlenecks: {$areaSummaryStr}\n" .
            "3. Top 2 operational directives for platform managers.";

        $aiSummary = "Walker Roster Health Overview: Roster contains {$totalCount} registered active providers ({$joinedCount} newly joined, {$t3Count} Tier 3, {$t2Count} Tier 2, and {$t1Count} Tier 1). " .
            "Average Roster Rating is {$avgRating}/5.00. Identified " . count($promoCandidates) . " candidates ready for Tier promotion and {$atRiskCount} at-risk providers requiring compliance check-ins.";

        $actionableImprovements = [
            "Support and incentivize onboarding walk completions for the {$joinedCount} newly joined providers.",
            "Promote top-performing Tier 2 walkers with rating >= 4.5 to Tier 1 to expand premium fulfillment capacity.",
            "Conduct targeted operational check-ins for the {$atRiskCount} at-risk providers to prevent SLA breaches."
        ];

        try {
            $analyst = new GeminiAnalyst();
            $aiRes = $analyst->ask($prompt);
            if (!empty($aiRes['reply']) && strpos($aiRes['reply'], 'Error:') === false) {
                $aiSummary = $aiRes['reply'];
            }
        } catch (\Throwable $e) {
            // Fallback to computed text
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'total_walkers'           => $totalCount,
                'joined_count'            => $joinedCount,
                'tier_counts'             => ['t1' => $t1Count, 't2' => $t2Count, 't3' => $t3Count, 'joined' => $joinedCount],
                'avg_rating'              => $avgRating,
                'at_risk_count'           => $atRiskCount,
                'promotion_candidates'    => $promoCandidates,
                'at_risk_walkers'         => $atRiskList,
                'ai_executive_summary'    => $aiSummary,
                'capacity_warnings'       => $capacityWarnings,
                'area_intelligence'       => $areaIntelligence,
                'actionable_improvements' => $actionableImprovements
            ]
        ]);
    }

    /**
     * AJAX endpoint: Interactive Ask AI Walker Analyst
     */
    public function askAi()
    {
        $question = trim((string)($this->request->getPost('question') ?: $this->request->getPost('message') ?: $this->request->getGet('question')));
        if (empty($question)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Please ask a question about walkers.']);
        }

        $walkers = $this->statsModel->getLeaderboard();
        $total = count($walkers);
        $top3 = array_slice($walkers, 0, 3);
        $topNames = array_map(fn($w) => "{$w->provider_name} (T{$w->tier}, Rating: {$w->weighted_rating})", $top3);

        $contextPrompt = "User Question regarding Walker Roster: \"{$question}\"\n\n" .
            "Current Roster Context: Total Walkers = {$total}. Top Ranked Walkers: " . implode(', ', $topNames) . ".\n" .
            "Analyze and answer the user question thoroughly using walker data intelligence.";

        try {
            $analyst = new GeminiAnalyst();
            $res = $analyst->ask($contextPrompt);
            return $this->response->setJSON([
                'status' => true,
                'reply'  => $res['reply'] ?? 'Unable to process query at the moment.',
                'metrics' => $res['metrics'] ?? [],
                'chart'   => $res['chart'] ?? null
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status' => false,
                'reply'  => 'Error querying AI Walker Analyst: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * AJAX endpoint: Reset simulator stats to baseline
     */
    public function resetSimulator()
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Reset all strikes to 0
        $db->table('sp_stats')->update(['strike_count' => 0, 'in_grace_period' => 0]);

        // 2. Restore active status in service_providers
        $db->table('service_providers')
            ->update(['status' => 'active']);

        $db->transComplete();

        // 3. Re-evaluate tiers using the official evaluation engine
        $evaluator = new \App\Controllers\Cli\WalkerTierEvaluator();
        $evaluator->evaluate();

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Simulator reset and walker tiers evaluated successfully'
        ]);
    }
}


