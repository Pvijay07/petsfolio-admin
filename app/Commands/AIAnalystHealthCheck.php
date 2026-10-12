<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\AIAnalyst\MySQLMetrics;

/**
 * AIAnalystHealthCheck Command
 *
 * Runs automated data integrity, view synchronization, wallet audit, and AI metric tests.
 * Command: php spark ai:health-check
 */
class AIAnalystHealthCheck extends BaseCommand
{
    protected $group       = 'AI Analyst';
    protected $name        = 'ai:health-check';
    protected $description = 'Runs automated data integrity audits, view synchronization checks, and AI analyst metric tests.';
    protected $usage       = 'ai:health-check';

    public function run(array $params)
    {
        CLI::write('====================================================', 'cyan');
        CLI::write('   PETSFOLIO AI / BI DATA INTEGRITY HEALTH CHECK    ', 'yellow');
        CLI::write('====================================================', 'cyan');
        CLI::write('Started at: ' . date('Y-m-d H:i:s T'), 'light_gray');
        CLI::newLine();

        $db = \Config\Database::connect();
        $passed = 0;
        $warnings = 0;
        $failed = 0;

        // ---------------------------------------------------------
        // 1. VIEW VS UNDERLYING TABLES PARITY CHECK
        // ---------------------------------------------------------
        CLI::write('[1/4] Auditing v_bookings_overview vs Underlying Service Tables...', 'cyan');
        try {
            $viewTotal = $db->table('v_bookings_overview')->countAllResults();

            $walkTotal  = $db->table('walking_service_bookings')->countAllResults();
            $groomTotal = $db->table('grooming_service_bookings')->countAllResults();
            $trainTotal = $db->table('training_service_bookings')->countAllResults();
            $sitTotal   = $db->table('sitting_service_bookings')->countAllResults();
            $sumTotal   = $walkTotal + $groomTotal + $trainTotal + $sitTotal;

            if ($viewTotal === $sumTotal) {
                CLI::write("  ✅ Parity Match: Total rows in View ({$viewTotal}) === Sum of Tables ({$sumTotal})", 'green');
                $passed++;
            } else {
                CLI::write("  ⚠️ Difference: View has {$viewTotal} rows, Tables have {$sumTotal} rows.", 'yellow');
                $warnings++;
            }

            // Halted Bookings 30-Day Check
            $date30 = date('Y-m-d H:i:s', strtotime('-30 days'));
            $wHalt = $db->table('walking_service_bookings')->where('LOWER(status)', 'halted')->where('is_deleted', 0)->where("created_at >= '{$date30}'")->countAllResults();
            $gHalt = $db->table('grooming_service_bookings')->where('LOWER(status)', 'halted')->where('is_deleted', 0)->where("created_at >= '{$date30}'")->countAllResults();
            $tHalt = $db->table('training_service_bookings')->where('LOWER(status)', 'halted')->where('is_deleted', 0)->where("created_at >= '{$date30}'")->countAllResults();
            $sHalt = $db->table('sitting_service_bookings')->where('LOWER(status)', 'halted')->where('is_deleted', 0)->where("created_at >= '{$date30}'")->countAllResults();
            $totHalt = $wHalt + $gHalt + $tHalt + $sHalt;

            CLI::write("  📊 30-Day Halted Bookings: {$totHalt} (Walk: {$wHalt}, Groom: {$gHalt}, Train: {$tHalt}, Sit: {$sHalt})", 'light_gray');
        } catch (\Throwable $e) {
            CLI::write("  ❌ Error auditing views: " . $e->getMessage(), 'red');
            $failed++;
        }
        CLI::newLine();

        // ---------------------------------------------------------
        // 2. FINANCIAL LEDGER & WALLET INTEGRITY
        // ---------------------------------------------------------
        CLI::write('[2/4] Auditing Financial Wallets & Ledger Histories...', 'cyan');
        try {
            $metrics = new MySQLMetrics();
            
            // Payout balance & cashfree API check
            $payoutData = $metrics->payout_balance_overview('all_time');
            $cashfreeBal = $payoutData['cashfree_balance'] ?? ($payoutData['cashfree_payout_api']['available_balance'] ?? 0);
            $pendingReqs = $payoutData['pending_payout_requests_count'] ?? 0;
            $pendingAmt  = $payoutData['pending_payout_amount_total'] ?? '₹0.00';

            CLI::write("  ✅ Cashfree Balance: ₹" . number_format((float)$cashfreeBal, 2) . " | Pending Requests: {$pendingReqs} ({$pendingAmt})", 'green');
            $passed++;

            // Platform Commission sanity check
            $commRow = $db->table('platform_wallet_histories')->select('COALESCE(SUM(commission_amount), 0) as total, COUNT(*) as cnt')->get()->getRowArray();
            $totComm = (float)($commRow['total'] ?? 0);
            $commTxns = (int)($commRow['cnt'] ?? 0);
            CLI::write("  ✅ Platform Commission Ledger: ₹" . number_format($totComm, 2) . " across {$commTxns} transactions", 'green');
            $passed++;
        } catch (\Throwable $e) {
            CLI::write("  ❌ Error auditing financial ledgers: " . $e->getMessage(), 'red');
            $failed++;
        }
        CLI::newLine();

        // ---------------------------------------------------------
        // 3. AI METRICS EXECUTION ENGINE VERIFICATION
        // ---------------------------------------------------------
        CLI::write('[3/4] Testing Core AI Metric Subroutines...', 'cyan');
        try {
            $metrics = new MySQLMetrics();

            // 1. Active Jobs
            $activeJobs = $metrics->active_jobs();
            $actCount   = $activeJobs['total_active_jobs_count'] ?? 0;
            CLI::write("  ✅ MySQLMetrics::active_jobs() => {$actCount} Active Jobs Ongoing", 'green');
            $passed++;

            // 2. Loyalty Overview
            $loyalty = $metrics->loyalty_overview('all_time');
            $loyaltyHolders = $loyalty['total_loyalty_point_holders'] ?? 0;
            CLI::write("  ✅ MySQLMetrics::loyalty_overview() => {$loyaltyHolders} Point Holders", 'green');
            $passed++;

            // 3. Insurance Overview
            $insurance = $metrics->insurance_overview('all_time');
            $activePolicies = $insurance['active_policies_count'] ?? 0;
            CLI::write("  ✅ MySQLMetrics::insurance_overview() => {$activePolicies} Active Policies", 'green');
            $passed++;

            // 4. Support Tickets
            $tickets = $metrics->support_tickets_overview('30_days');
            $totTickets = $tickets['total_tickets'] ?? 0;
            CLI::write("  ✅ MySQLMetrics::support_tickets_overview() => {$totTickets} Tickets in 30 Days", 'green');
            $passed++;

            // 5. Bookings Summary Parity Check
            $bSummary = $metrics->bookings_summary('all_time');
            $totReq = $bSummary['total_service_requests'] ?? 0;
            $comp = $bSummary['completed_bookings'] ?? 0;
            $conf = $bSummary['active_confirmed_bookings'] ?? 0;
            $halt = $bSummary['halted_bookings'] ?? 0;
            $canc = $bSummary['cancelled_bookings'] ?? 0;
            $newR = $bSummary['new_pending_requests'] ?? 0;
            $miss = $bSummary['missed_bookings'] ?? 0;
            $oth  = $bSummary['other_bookings'] ?? 0;
            $del  = $bSummary['deleted_bookings'] ?? 0;
            $sumParts = $comp + $conf + $halt + $canc + $newR + $miss + $oth + $del;

            if ($totReq === $sumParts) {
                CLI::write("  ✅ Bookings Summary Status Parity: {$sumParts} === Total Requests ({$totReq})", 'green');
                $passed++;
            } else {
                CLI::write("  ⚠️ Bookings Summary Parity Discrepancy: Sum of parts ({$sumParts}) != Total ({$totReq})", 'yellow');
                $warnings++;
            }

            // 6. Tutorials Overview Check
            $tutorials = $metrics->tutorials_overview();
            $totTut = $tutorials['total_tutorial_links'] ?? 0;
            if ($totTut > 0) {
                CLI::write("  ✅ MySQLMetrics::tutorials_overview() => {$totTut} Tutorial Video Links Found", 'green');
                $passed++;
            } else {
                CLI::write("  ⚠️ MySQLMetrics::tutorials_overview() returned 0 tutorials", 'yellow');
                $warnings++;
            }

            // 7. GeminiAnalyst Fast-Path Check on user question
            $analyst = new \App\Libraries\AIAnalyst\GeminiAnalyst();
            $res1 = $analyst->askStream("how many tutorial video links?");
            $metricName1 = $res1['metrics'][0]['name'] ?? 'none';
            if ($metricName1 === 'tutorials_overview') {
                CLI::write("  ✅ GeminiAnalyst Routed 'how many tutorial video links?' => tutorials_overview", 'green');
                $passed++;
            } else {
                CLI::write("  ❌ GeminiAnalyst Routed 'how many tutorial video links?' => {$metricName1}", 'red');
                $failed++;
            }

            // 8. GeminiAnalyst Cancel Send Noise Resilience Check
            $res2 = $analyst->askStream("how many tutorial video links? how many tutorial video links? Cancel Send Cancel Send");
            $metricName2 = $res2['metrics'][0]['name'] ?? 'none';
            if ($metricName2 === 'tutorials_overview') {
                CLI::write("  ✅ GeminiAnalyst Cancel-Noise Resilient => tutorials_overview (not cancellation_rate)", 'green');
                $passed++;
            } else {
                CLI::write("  ❌ GeminiAnalyst Cancel-Noise Failed => {$metricName2}", 'red');
                $failed++;
            }

            // 9. Roles & Permissions Check
            $perms = $metrics->roles_and_permissions_overview();
            $totP = $perms['total_permissions'] ?? 0;
            $totR = $perms['total_roles'] ?? 0;
            if ($totP > 0 && $totR > 0) {
                CLI::write("  ✅ MySQLMetrics::roles_and_permissions_overview() => {$totR} Roles, {$totP} Permissions", 'green');
                $passed++;
            } else {
                CLI::write("  ❌ MySQLMetrics::roles_and_permissions_overview() returned empty data", 'red');
                $failed++;
            }

            // 10. GeminiAnalyst Permissions Routing Check
            $res3 = $analyst->askStream("I want to know About permissions ??");
            $metricName3 = $res3['metrics'][0]['name'] ?? 'none';
            if ($metricName3 === 'roles_and_permissions_overview') {
                CLI::write("  ✅ GeminiAnalyst Routed 'I want to know About permissions ??' => roles_and_permissions_overview", 'green');
                $passed++;
            } else {
                CLI::write("  ❌ GeminiAnalyst Routed 'I want to know About permissions ??' => {$metricName3}", 'red');
                $failed++;
            }

            // 11. Service Available States Metric Check
            $stateData = $metrics->service_states_overview();
            $totStates = $stateData['total_service_states'] ?? 0;
            if ($totStates === 36) {
                CLI::write("  ✅ MySQLMetrics::service_states_overview() => {$totStates} Service States Found (All Active)", 'green');
                $passed++;
            } else {
                CLI::write("  ❌ MySQLMetrics::service_states_overview() expected 36 states, got: {$totStates}", 'red');
                $failed++;
            }

            // 12. User Query Routing Check: 'k hw many service states are there:/'
            $res4 = $analyst->askStream("k hw many service states are there:/");
            $metricName4 = $res4['metrics'][0]['name'] ?? 'none';
            if ($metricName4 === 'service_states_overview') {
                CLI::write("  ✅ GeminiAnalyst Routed 'k hw many service states are there:/' => service_states_overview", 'green');
                $passed++;
            } else {
                CLI::write("  ❌ GeminiAnalyst Routed 'k hw many service states are there:/' => {$metricName4}", 'red');
                $failed++;
            }

            // 13. Platform Charge in September Check
            $res5 = $analyst->askStream("how much will petsfolio earned in september consider only platform charge");
            $metricName5 = $res5['metrics'][0]['name'] ?? 'none';
            $dr5 = $res5['metrics'][0]['args']['date_range'] ?? '';
            if ($metricName5 === 'revenue_overview' && str_contains(strtolower($dr5), 'september')) {
                CLI::write("  ✅ GeminiAnalyst Routed '...september consider only platform charge' => revenue_overview (september)", 'green');
                $passed++;
            } else {
                CLI::write("  ❌ GeminiAnalyst Routed '...september consider only platform charge' => {$metricName5} ({$dr5})", 'red');
                $failed++;
            }

            // 14. Platform Charge General Query Check
            $res6 = $analyst->askStream("i want to know only about platform charge earned by petsfolio");
            $metricName6 = $res6['metrics'][0]['name'] ?? 'none';
            if ($metricName6 === 'revenue_overview') {
                CLI::write("  ✅ GeminiAnalyst Routed 'i want to know only about platform charge...' => revenue_overview", 'green');
                $passed++;
            } else {
                CLI::write("  ❌ GeminiAnalyst Routed 'i want to know only about platform charge...' => {$metricName6}", 'red');
                $failed++;
            }

            // 15. Manager Approval uses a service-level workflow state, not
            // service_providers.status = 'approval'.
            $res7 = $analyst->askStream('How many trainers are there in manager approval?');
            $metricName7 = $res7['metrics'][0]['name'] ?? 'none';
            $status7 = $res7['metrics'][0]['args']['status'] ?? '';
            $service7 = $res7['metrics'][0]['args']['service_type'] ?? '';
            $area7 = $res7['metrics'][0]['args']['area'] ?? null;
            $total7 = $res7['metrics'][0]['data']['total_providers'] ?? 0;
            if ($metricName7 === 'provider_stats' && $status7 === 'approval' && $service7 === 'training' && empty($area7) && $total7 > 0) {
                CLI::write("  ✅ GeminiAnalyst Routed Manager Approval trainers => provider_stats (training, approval)", 'green');
                $passed++;
            } else {
                CLI::write("  ❌ GeminiAnalyst Manager Approval routing failed => {$metricName7} ({$service7}, {$status7}, area: {$area7}, total: {$total7})", 'red');
                $failed++;
            }
        } catch (\Throwable $e) {
            CLI::write("  ❌ Error testing metrics: " . $e->getMessage(), 'red');
            $failed++;
        }
        CLI::newLine();

        // ---------------------------------------------------------
        // 4. KNOWLEDGE BASE & SEMANTIC INTEGRITY
        // ---------------------------------------------------------
        CLI::write('[4/4] Checking Knowledge Base & Semantic Layer Files...', 'cyan');
        $kbFile = APPPATH . 'Libraries/AIAnalyst/bi_semantic_kb.json';
        $suppKbFile = APPPATH . 'Libraries/AIAnalyst/support_kb.json';

        if (file_exists($kbFile)) {
            $kbJson = json_decode(file_get_contents($kbFile), true);
            if (is_array($kbJson) && isset($kbJson['business_entities'])) {
                CLI::write("  ✅ bi_semantic_kb.json is valid (" . count($kbJson['business_entities']) . " entity groups defined)", 'green');
                $passed++;
            } else {
                CLI::write("  ⚠️ bi_semantic_kb.json format error", 'yellow');
                $warnings++;
            }
        } else {
            CLI::write("  ❌ bi_semantic_kb.json not found", 'red');
            $failed++;
        }

        if (file_exists($suppKbFile)) {
            $suppJson = json_decode(file_get_contents($suppKbFile), true);
            if (is_array($suppJson) && isset($suppJson['chunks'])) {
                CLI::write("  ✅ support_kb.json is valid (" . count($suppJson['chunks']) . " RAG chunks indexed)", 'green');
                $passed++;
            } else {
                CLI::write("  ⚠️ support_kb.json format error", 'yellow');
                $warnings++;
            }
        } else {
            CLI::write("  ❌ support_kb.json not found", 'red');
            $failed++;
        }
        CLI::newLine();

        // ---------------------------------------------------------
        // SUMMARY
        // ---------------------------------------------------------
        CLI::write('====================================================', 'cyan');
        CLI::write("HEALTH CHECK COMPLETED: Passed: {$passed} | Warnings: {$warnings} | Failed: {$failed}", $failed > 0 ? 'red' : ($warnings > 0 ? 'yellow' : 'green'));
        CLI::write('====================================================', 'cyan');
    }
}
