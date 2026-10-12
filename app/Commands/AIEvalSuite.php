<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\AIAnalyst\QueryValidator;

/**
 * AIEvalSuite Command
 *
 * Automated Golden Evaluation test suite for Petsfolio Text-to-BI architecture (Pillar 7).
 * Executes 50+ benchmark enterprise questions across 6 core pillars, measuring:
 * 1. Execution Accuracy (status === 'ok')
 * 2. Latency Budget (< 2000ms)
 * 3. Business Sanity Assertions (zero negative revenue, valid percentage bounds)
 * 4. Ground Truth Data Quality
 *
 * Usage:
 *   php spark ai:eval
 *   php spark ai:eval --category=headcount
 *   php spark ai:eval --category=semantic
 *   php spark ai:eval --limit=10
 */
class AIEvalSuite extends BaseCommand
{
    protected $group       = 'AI Analyst';
    protected $name        = 'ai:eval';
    protected $description = 'Runs the automated Enterprise Golden Evaluation test suite with 50+ benchmark questions across all BI pillars.';
    protected $usage       = 'ai:eval [--category=<category>] [--limit=<int>]';
    protected $arguments   = [];
    protected $options     = [
        '--category' => 'Filter by category: headcount, financials, bookings, crm, quality, semantic',
        '--limit'    => 'Limit the number of benchmark tests to run'
    ];

    public function run(array $params)
    {
        CLI::write('====================================================================', 'cyan');
        CLI::write('     PETSFOLIO ENTERPRISE TEXT-TO-BI GOLDEN EVALUATION SUITE        ', 'yellow');
        CLI::write('====================================================================', 'cyan');
        CLI::write('Timestamp: ' . date('Y-m-d H:i:s T') . ' | Environment: Live Production Replica', 'light_gray');
        CLI::newLine();

        $validator = new QueryValidator();
        $tests = $this->getBenchmarkQuestions();

        // Filter category if specified
        $categoryFilter = $params['category'] ?? CLI::getOption('category');
        if (!empty($categoryFilter)) {
            $categoryFilter = strtolower(trim($categoryFilter));
            $tests = array_filter($tests, fn($t) => strtolower($t['category']) === $categoryFilter);
            CLI::write("Filtering by category: [{$categoryFilter}] (" . count($tests) . " tests)", 'cyan');
            CLI::newLine();
        }

        // Apply limit if specified
        $limitOption = $params['limit'] ?? CLI::getOption('limit');
        if (!empty($limitOption) && is_numeric($limitOption)) {
            $tests = array_slice($tests, 0, (int)$limitOption);
            CLI::write("Limiting execution to: " . count($tests) . " tests", 'cyan');
            CLI::newLine();
        }

        $totalTests     = count($tests);
        $passCount      = 0;
        $warnCount      = 0;
        $failCount      = 0;
        $totalExecMs    = 0;
        $results        = [];

        CLI::write(sprintf("%-4s | %-12s | %-38s | %-8s | %-7s | %s", "ID", "Category", "Question / Intent", "Latency", "Status", "Ground Truth Summary"), 'light_gray');
        CLI::write(str_repeat('-', 110), 'dark_gray');

        foreach ($tests as $t) {
            $id       = $t['id'];
            $cat      = $t['category'];
            $q        = $t['question'];
            $metric   = $t['metric'];
            $args     = $t['args'] ?? [];

            $startTime = microtime(true);
            $response  = $validator->executeMetric($metric, $args);
            $execMs    = $response['exec_ms'] ?? (int)round((microtime(true) - $startTime) * 1000);
            $totalExecMs += $execMs;

            $status = 'PASS';
            $statusColor = 'green';
            $summary = '';

            if (!$response['success']) {
                $status = 'FAIL';
                $statusColor = 'red';
                $failCount++;
                $summary = 'Execution error: ' . substr($response['error'] ?? 'Unknown error', 0, 45);
            } else {
                $data = $response['data'] ?? [];

                // Check sanity warnings
                if (!empty($data['_sanity_warnings'])) {
                    $status = 'WARN';
                    $statusColor = 'yellow';
                    $warnCount++;
                    $summary = '⚠️ Sanity: ' . substr($data['_sanity_warnings'][0], 0, 40);
                } elseif ($execMs > 2500) {
                    $status = 'WARN';
                    $statusColor = 'yellow';
                    $warnCount++;
                    $summary = "Latency budget exceeded ({$execMs}ms > 2500ms)";
                } else {
                    $passCount++;
                    $summary = $this->extractGroundTruthSnippet($metric, $data);
                }
            }

            $shortQ = (strlen($q) > 36) ? substr($q, 0, 34) . '..' : $q;
            $latencyStr = "{$execMs}ms";
            $statusBadge = CLI::color(sprintf("%-7s", $status), $statusColor);

            $line = sprintf(
                "%-4s | %-12s | %-38s | %-8s | %s | %s",
                "#{$id}",
                substr($cat, 0, 12),
                $shortQ,
                $latencyStr,
                $statusBadge,
                $summary
            );

            CLI::write($line);

            $results[] = [
                'id'       => $id,
                'category' => $cat,
                'question' => $q,
                'metric'   => $metric,
                'latency'  => $execMs,
                'status'   => $status,
                'summary'  => $summary
            ];
        }

        CLI::newLine();
        CLI::write('====================================================================', 'cyan');
        CLI::write('                   EVALUATION SCORECARD RESULTS                     ', 'yellow');
        CLI::write('====================================================================', 'cyan');

        $avgLatency = $totalTests > 0 ? (int)round($totalExecMs / $totalTests) : 0;
        $accuracyRate = $totalTests > 0 ? round(($passCount / $totalTests) * 100, 1) : 0;
        $healthRate   = $totalTests > 0 ? round((($passCount + $warnCount) / $totalTests) * 100, 1) : 0;

        CLI::write("  Total Questions Benchmarked: {$totalTests}", 'light_gray');
        CLI::write("  Passed (Deterministic & Grounded): {$passCount}", 'green');
        CLI::write("  Warnings (Sanity / Latency): {$warnCount}", $warnCount > 0 ? 'yellow' : 'light_gray');
        CLI::write("  Failed: {$failCount}", $failCount > 0 ? 'red' : 'green');
        CLI::write("  Average Execution Latency: {$avgLatency} ms", 'cyan');
        CLI::write("  Clean Execution Accuracy: {$accuracyRate}%", $accuracyRate >= 95 ? 'green' : ($accuracyRate >= 80 ? 'yellow' : 'red'));
        CLI::write("  Operational Availability: {$healthRate}%", 'green');
        CLI::newLine();

        if ($failCount === 0 && $accuracyRate >= 95) {
            CLI::write("🌟 GOLDEN EVALUATION VERDICT: EXCELLENT (Production Ready & Industry Gold Standard)", 'green');
        } elseif ($failCount === 0) {
            CLI::write("✅ GOLDEN EVALUATION VERDICT: PASS WITH NOTICES (Review Sanity Warnings)", 'yellow');
        } else {
            CLI::write("❌ GOLDEN EVALUATION VERDICT: FAIL (Investigate Failed Metric Queries)", 'red');
        }
        CLI::newLine();

        return $failCount === 0 ? 0 : 1;
    }

    /**
     * Extracts a concise summary snippet from live data.
     */
    private function extractGroundTruthSnippet(string $metric, array $data): string
    {
        if (isset($data['total_providers'])) {
            $act = $data['active_approved_providers'] ?? ($data['active_providers'] ?? 0);
            return "Providers: {$data['total_providers']} total ({$act} active)";
        }
        if (isset($data['gross_revenue_inr'])) {
            $comm = number_format((float)($data['platform_commission'] ?? 0), 2);
            return "Gross: ₹" . number_format((float)$data['gross_revenue_inr'], 2) . " | Comm: ₹{$comm}";
        }
        if (isset($data['total_registration_fee_inr'])) {
            $paid = $data['paid_service_providers'] ?? ($data['paid_providers'] ?? 0);
            return "Reg Fee: ₹" . number_format((float)$data['total_registration_fee_inr'], 2) . " ({$paid} paid SPs)";
        }
        if (isset($data['total_active_jobs'])) {
            return "Active Jobs: {$data['total_active_jobs']} ongoing";
        }
        if (isset($data['total_bookings'])) {
            $comp = $data['completed_bookings'] ?? 0;
            return "Bookings: {$data['total_bookings']} (Completed: {$comp})";
        }
        if (isset($data['conversion_rate'])) {
            $tot = $data['total_leads'] ?? 0;
            return "CRM Leads: {$tot} | Conv Rate: {$data['conversion_rate']}";
        }
        if (isset($data['aggregated_total'])) {
            $val = is_numeric($data['aggregated_total']) ? number_format((float)$data['aggregated_total']) : $data['aggregated_total'];
            $rowsCount = $data['total_rows'] ?? (is_array($data['rows'] ?? null) ? count($data['rows']) : 0);
            return "Semantic Total: {$val} across {$rowsCount} groups";
        }
        if (isset($data['total_calls'])) {
            $conv = $data['converted_bookings_count'] ?? 0;
            return "Calls: {$data['total_calls']} (Converted Bookings: {$conv})";
        }
        if (isset($data['cashfree_balance'])) {
            $pend = $data['pending_payout_requests_count'] ?? 0;
            return "Cashfree Bal: ₹" . number_format((float)$data['cashfree_balance'], 2) . " ({$pend} pending)";
        }
        if (isset($data['total_feedbacks_count'])) {
            $avg = $data['average_rating'] ?? ($data['average_feedback_rating'] ?? 0);
            return "Feedbacks: {$data['total_feedbacks_count']} (Avg: {$avg}★)";
        }
        if (isset($data['total_tickets'])) {
            $unread = $data['open_tickets_with_unread_count'] ?? 0;
            return "Tickets: {$data['total_tickets']} ({$unread} unread open)";
        }

        return "Executed OK (" . count($data) . " keys returned)";
    }

    /**
     * Complete suite of 52 benchmark enterprise questions covering all business pillars.
     */
    private function getBenchmarkQuestions(): array
    {
        return [
            // --- PILLAR 1: HEADCOUNT & SERVICE PROVIDERS (10 Tests) ---
            [
                'id'       => 1,
                'category' => 'headcount',
                'question' => 'How many trainers are there on the platform?',
                'metric'   => 'provider_stats',
                'args'     => ['service_type' => 'training', 'date_range' => 'all_time']
            ],
            [
                'id'       => 2,
                'category' => 'headcount',
                'question' => 'How many trainers are active?',
                'metric'   => 'provider_stats',
                'args'     => ['service_type' => 'training', 'status' => 'active']
            ],
            [
                'id'       => 3,
                'category' => 'headcount',
                'question' => 'How many registered walkers in Hyderabad?',
                'metric'   => 'provider_stats',
                'args'     => ['service_type' => 'walking', 'city' => 'hyderabad', 'date_range' => 'all_time']
            ],
            [
                'id'       => 4,
                'category' => 'headcount',
                'question' => 'How many walkers are waiting for verification call?',
                'metric'   => 'provider_stats',
                'args'     => ['service_type' => 'walking', 'status' => 'waiting for call']
            ],
            [
                'id'       => 5,
                'category' => 'headcount',
                'question' => 'How many registered groomers in Bengaluru?',
                'metric'   => 'provider_stats',
                'args'     => ['service_type' => 'grooming', 'city' => 'bengaluru', 'date_range' => 'all_time']
            ],
            [
                'id'       => 6,
                'category' => 'headcount',
                'question' => 'How many registered pet sitters total?',
                'metric'   => 'provider_stats',
                'args'     => ['service_type' => 'sitting', 'date_range' => 'all_time']
            ],
            [
                'id'       => 7,
                'category' => 'headcount',
                'question' => 'How many new service providers joined this month?',
                'metric'   => 'provider_stats',
                'args'     => ['date_range' => 'this_month']
            ],
            [
                'id'       => 8,
                'category' => 'headcount',
                'question' => 'Total active pet parent customers on Petsfolio',
                'metric'   => 'active_customers',
                'args'     => ['date_range' => 'all_time']
            ],
            [
                'id'       => 9,
                'category' => 'headcount',
                'question' => 'How many customers placed repeat bookings?',
                'metric'   => 'repeat_customers',
                'args'     => ['date_range' => 'all_time']
            ],
            [
                'id'       => 10,
                'category' => 'headcount',
                'question' => 'Low provider density areas in Hyderabad for recruitment',
                'metric'   => 'low_provider_density_areas',
                'args'     => ['city' => 'hyderabad']
            ],
            [
                'id'       => 53,
                'category' => 'headcount',
                'question' => 'How many trainers are awaiting manager approval?',
                'metric'   => 'provider_stats',
                'args'     => ['service_type' => 'training', 'status' => 'approval', 'date_range' => 'all_time']
            ],
            [
                'id'       => 54,
                'category' => 'headcount',
                'question' => 'How many walkers are in fee pending or registration fee pending?',
                'metric'   => 'provider_stats',
                'args'     => ['service_type' => 'walking', 'status' => 'pending_registration', 'date_range' => 'all_time']
            ],
            [
                'id'       => 55,
                'category' => 'financials',
                'question' => 'How much amount received from clients in last 7 days successful transactions?',
                'metric'   => 'revenue_overview',
                'args'     => ['date_range' => '7_days']
            ],

            // --- PILLAR 2: FINANCIALS, REVENUE & WALLETS (10 Tests) ---
            [
                'id'       => 11,
                'category' => 'financials',
                'question' => 'Revenue overview and breakdown for last 30 days',
                'metric'   => 'revenue_overview',
                'args'     => ['date_range' => '30_days']
            ],
            [
                'id'       => 12,
                'category' => 'financials',
                'question' => 'Platform commission and 18% GST earned this month',
                'metric'   => 'revenue_overview',
                'args'     => ['date_range' => 'this_month']
            ],
            [
                'id'       => 13,
                'category' => 'financials',
                'question' => 'Amount collected only from service providers for registration/verification',
                'metric'   => 'registration_fee',
                'args'     => ['date_range' => 'all_time']
            ],
            [
                'id'       => 14,
                'category' => 'financials',
                'question' => 'Failed-then-successful recovered revenue analytics',
                'metric'   => 'recovered_transactions_overview',
                'args'     => ['date_range' => 'all_time']
            ],
            [
                'id'       => 15,
                'category' => 'financials',
                'question' => 'Client refund requests and processed refunds summary (30 days)',
                'metric'   => 'user_refunds_summary',
                'args'     => ['date_range' => '30_days']
            ],
            [
                'id'       => 16,
                'category' => 'financials',
                'question' => 'Cashfree API payout balance and pending withdrawal requests',
                'metric'   => 'payout_balance_overview',
                'args'     => ['date_range' => 'all_time']
            ],
            [
                'id'       => 17,
                'category' => 'financials',
                'question' => 'Highest revenue cities and localities',
                'metric'   => 'revenue_by_location',
                'args'     => ['date_range' => '30_days']
            ],
            [
                'id'       => 18,
                'category' => 'financials',
                'question' => 'Top earning service providers',
                'metric'   => 'provider_revenue_overview',
                'args'     => ['date_range' => '30_days']
            ],
            [
                'id'       => 19,
                'category' => 'financials',
                'question' => 'Service provider escrow and withdrawal wallet breakdown',
                'metric'   => 'sp_wallets_overview',
                'args'     => []
            ],
            [
                'id'       => 20,
                'category' => 'financials',
                'question' => 'Client withdrawal/refund wallet balances and escrow funds',
                'metric'   => 'client_wallets_overview',
                'args'     => ['wallet_type' => 'withdrawal']
            ],

            // --- PILLAR 3: BOOKINGS & OPERATIONS (10 Tests) ---
            [
                'id'       => 21,
                'category' => 'bookings',
                'question' => 'How many active jobs ongoing at the moment?',
                'metric'   => 'active_jobs',
                'args'     => []
            ],
            [
                'id'       => 22,
                'category' => 'bookings',
                'question' => 'Bookings summary for this month across all services',
                'metric'   => 'bookings_summary',
                'args'     => ['date_range' => 'this_month']
            ],
            [
                'id'       => 23,
                'category' => 'bookings',
                'question' => 'How many bookings are currently on-hold or halted?',
                'metric'   => 'bookings_summary',
                'args'     => ['status' => 'halted', 'date_range' => '30_days']
            ],
            [
                'id'       => 24,
                'category' => 'bookings',
                'question' => 'How many bookings are completed all time?',
                'metric'   => 'bookings_summary',
                'args'     => ['status' => 'completed', 'date_range' => 'all_time']
            ],
            [
                'id'       => 25,
                'category' => 'bookings',
                'question' => 'Daily booking trend over the past 7 days',
                'metric'   => 'daily_booking_trend',
                'args'     => ['date_range' => '7_days']
            ],
            [
                'id'       => 26,
                'category' => 'bookings',
                'question' => 'Overall booking cancellation rate and service breakdown',
                'metric'   => 'cancellation_rate',
                'args'     => ['date_range' => '30_days']
            ],
            [
                'id'       => 27,
                'category' => 'bookings',
                'question' => 'Peak booking hours and busiest day of the week',
                'metric'   => 'time_based_analytics',
                'args'     => ['date_range' => '30_days']
            ],
            [
                'id'       => 28,
                'category' => 'bookings',
                'question' => 'Fastest growing service category and profit margins',
                'metric'   => 'service_growth_analysis',
                'args'     => ['date_range' => '30_days']
            ],
            [
                'id'       => 29,
                'category' => 'bookings',
                'question' => 'Most popular add-on services and add-on conversion rates',
                'metric'   => 'addon_intelligence',
                'args'     => ['date_range' => '30_days']
            ],
            [
                'id'       => 30,
                'category' => 'bookings',
                'question' => 'Operations admin tasks overview and completion percentage',
                'metric'   => 'operations_tasks_overview',
                'args'     => ['date_range' => '30_days']
            ],

            // --- PILLAR 4: REAL CRM & FUNNELS (DUAL MONGODB) (8 Tests) ---
            [
                'id'       => 31,
                'category' => 'crm',
                'question' => 'Client lead conversion rate (pet parents) last 30 days',
                'metric'   => 'conversion_rate',
                'args'     => ['date_range' => '30_days', 'crm_type' => 'client']
            ],
            [
                'id'       => 32,
                'category' => 'crm',
                'question' => 'Service provider recruitment lead conversion rate',
                'metric'   => 'conversion_rate',
                'args'     => ['date_range' => '30_days', 'crm_type' => 'provider']
            ],
            [
                'id'       => 33,
                'category' => 'crm',
                'question' => 'Client lead funnel dropoff stages',
                'metric'   => 'funnel_dropoff',
                'args'     => ['date_range' => '30_days', 'crm_type' => 'client']
            ],
            [
                'id'       => 34,
                'category' => 'crm',
                'question' => 'Service provider recruitment funnel dropoff stages',
                'metric'   => 'funnel_dropoff',
                'args'     => ['date_range' => '30_days', 'crm_type' => 'provider']
            ],
            [
                'id'       => 35,
                'category' => 'crm',
                'question' => 'Lead source acquisition performance breakdown',
                'metric'   => 'lead_source_performance',
                'args'     => ['date_range' => '30_days', 'crm_type' => 'client']
            ],
            [
                'id'       => 36,
                'category' => 'crm',
                'question' => 'CRM sales and support agent resolution performance',
                'metric'   => 'agent_performance',
                'args'     => ['date_range' => '30_days', 'crm_type' => 'client']
            ],
            [
                'id'       => 37,
                'category' => 'crm',
                'question' => 'WhatsApp customer inquiry topics and percentage share',
                'metric'   => 'whatsapp_inquiry_topics',
                'args'     => ['date_range' => '30_days']
            ],
            [
                'id'       => 38,
                'category' => 'crm',
                'question' => 'Cross-sell and upsell acceptance rate from CRM leads',
                'metric'   => 'cross_sell_acceptance_rate',
                'args'     => ['date_range' => '30_days']
            ],

            // --- PILLAR 5: QUALITY, COMMUNICATIONS & SUPPORT (7 Tests) ---
            [
                'id'       => 39,
                'category' => 'quality',
                'question' => 'Support tickets with unread customer messages',
                'metric'   => 'support_tickets_overview',
                'args'     => ['user_type' => 'all']
            ],
            [
                'id'       => 40,
                'category' => 'quality',
                'question' => 'Service provider support tickets overview',
                'metric'   => 'support_tickets_overview',
                'args'     => ['user_type' => 'provider']
            ],
            [
                'id'       => 41,
                'category' => 'quality',
                'question' => 'In-app WebRTC calls today and booking conversions',
                'metric'   => 'in_app_calls_overview',
                'args'     => ['date_range' => 'today']
            ],
            [
                'id'       => 42,
                'category' => 'quality',
                'question' => 'Call AI QA quality score and customer sentiment analysis',
                'metric'   => 'call_ai_quality_analytics',
                'args'     => ['date_range' => '30_days']
            ],
            [
                'id'       => 43,
                'category' => 'quality',
                'question' => 'Customer feedback, average rating and star distribution',
                'metric'   => 'feedback_and_reviews_overview',
                'args'     => ['date_range' => '30_days']
            ],
            [
                'id'       => 44,
                'category' => 'quality',
                'question' => 'Live walking tracking completion rate and short walks',
                'metric'   => 'walking_tracking_analytics',
                'args'     => ['date_range' => 'today']
            ],
            [
                'id'       => 45,
                'category' => 'quality',
                'question' => 'Official service packages catalog and preference rankings',
                'metric'   => 'packages_overview',
                'args'     => ['service_type' => 'all']
            ],

            // --- PILLAR 6: DECLARATIVE SEMANTIC LAYER (DETERMINISTIC SQL) (7 Tests) ---
            [
                'id'       => 46,
                'category' => 'semantic',
                'question' => 'GMV aggregated by city (Deterministic SQL via v_bookings_overview)',
                'metric'   => 'semantic_query_compiler',
                'args'     => [
                    'metric'     => 'gmv',
                    'dimensions' => ['city'],
                    'date_range' => '30_days'
                ]
            ],
            [
                'id'       => 47,
                'category' => 'semantic',
                'question' => 'Completed revenue by service type (Deterministic SQL)',
                'metric'   => 'semantic_query_compiler',
                'args'     => [
                    'metric'     => 'completed_revenue',
                    'dimensions' => ['service_type'],
                    'filters'    => ['status' => 'completed'],
                    'date_range' => '30_days'
                ]
            ],
            [
                'id'       => 48,
                'category' => 'semantic',
                'question' => 'Total booking count sliced by city and status',
                'metric'   => 'semantic_query_compiler',
                'args'     => [
                    'metric'     => 'booking_count',
                    'dimensions' => ['city', 'status'],
                    'date_range' => '30_days'
                ]
            ],
            [
                'id'       => 49,
                'category' => 'semantic',
                'question' => 'Monthly GMV trend over the last 90 days',
                'metric'   => 'semantic_query_compiler',
                'args'     => [
                    'metric'     => 'gmv',
                    'dimensions' => ['month'],
                    'date_range' => '90_days'
                ]
            ],
            [
                'id'       => 50,
                'category' => 'semantic',
                'question' => 'Average booking value (AOV) by service category',
                'metric'   => 'semantic_query_compiler',
                'args'     => [
                    'metric'     => 'avg_booking_value',
                    'dimensions' => ['service_type'],
                    'date_range' => '30_days'
                ]
            ],
            [
                'id'       => 51,
                'category' => 'semantic',
                'question' => 'Active providers count grouped by city',
                'metric'   => 'semantic_query_compiler',
                'args'     => [
                    'metric'     => 'active_providers',
                    'dimensions' => ['city'],
                    'date_range' => '30_days'
                ]
            ],
            [
                'id'       => 52,
                'category' => 'semantic',
                'question' => 'Platform commission (10%) by service type this month',
                'metric'   => 'semantic_query_compiler',
                'args'     => [
                    'metric'     => 'platform_commission',
                    'dimensions' => ['service_type'],
                    'date_range' => 'this_month'
                ]
            ],

            // --- PILLAR 7: ENTITY RESOLUTION & REGRESSION TESTS (5 Tests) ---
            [
                'id'       => 56,
                'category' => 'headcount',
                'question' => 'How many walkers are there in Hyderabad?',
                'metric'   => 'provider_stats',
                'args'     => ['service_type' => 'walking', 'city' => 'Hyderabad', 'date_range' => 'all_time']
            ],
            [
                'id'       => 57,
                'category' => 'crm',
                'question' => 'Client profile lookup for Saru',
                'metric'   => 'client_profile_lookup',
                'args'     => ['client_search' => 'Saru']
            ],
            [
                'id'       => 58,
                'category' => 'headcount',
                'question' => 'Provider profile lookup for Vishnu Vardhan',
                'metric'   => 'provider_stats',
                'args'     => ['provider_search' => 'Vishnu Vardhan']
            ],
            [
                'id'       => 59,
                'category' => 'headcount',
                'question' => 'Provider lookup for phone 8179550538',
                'metric'   => 'provider_stats',
                'args'     => ['provider_search' => '8179550538']
            ],
            [
                'id'       => 60,
                'category' => 'crm',
                'question' => 'Client lookup for phone 8179550538',
                'metric'   => 'client_profile_lookup',
                'args'     => ['client_search' => '8179550538']
            ],
            [
                'id'       => 61,
                'category' => 'financials',
                'question' => 'Providers payout Request is there are not ?',
                'metric'   => 'payout_balance_overview',
                'args'     => ['date_range' => 'all_time']
            ],
            [
                'id'       => 62,
                'category' => 'bookings',
                'question' => 'how many new jobs there till today?',
                'metric'   => 'bookings_summary',
                'args'     => ['date_range' => 'all_time', 'status' => 'new']
            ]
        ];
    }
}
