<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\AIAnalyst\GeminiAnalyst;
use App\Libraries\AIAnalyst\SemanticResolver;

/**
 * E2E Frontend Alignment Test
 *
 * Tests the FULL GeminiAnalyst->ask() pipeline (same as the actual frontend)
 * to verify that SemanticResolver fixes actually work in production, not just in the eval suite.
 */
class AIE2ETest extends BaseCommand
{
    protected $group       = 'AI Analyst';
    protected $name        = 'ai:e2e';
    protected $description = 'End-to-end test through full GeminiAnalyst pipeline to verify frontend alignment.';

    public function run(array $params)
    {
        CLI::write('=== E2E FRONTEND ALIGNMENT TEST ===', 'cyan');
        CLI::write('Tests run through GeminiAnalyst->ask() — same path as the actual frontend.', 'light_gray');
        CLI::newLine();

        $analyst = new GeminiAnalyst();

        $tests = [
            [
                'q' => 'Providers payout Request is there are not ?',
                'must_contain' => 'payout_balance_overview',
                'must_not_contain' => ['provider_stats'],
                'desc' => 'Payout request → payout_balance_overview (NOT provider_stats)'
            ],
            [
                'q' => 'how many active jobs there?',
                'must_contain' => 'active_jobs',
                'must_not_contain' => ['provider_stats'],
                'desc' => 'Active jobs → active_jobs (NOT provider_stats)'
            ],
            [
                'q' => 'how many new jobs there till today?',
                'must_contain' => 'bookings_summary',
                'must_not_contain' => ['provider_stats'],
                'desc' => 'New jobs → bookings_summary (NOT provider_stats)'
            ],
            [
                'q' => 'how many halted bookings',
                'must_contain' => 'bookings_summary',
                'must_not_contain' => ['provider_stats'],
                'desc' => 'Halted bookings → bookings_summary (NOT provider_stats)'
            ],
            [
                'q' => 'how many active bookings there?',
                'must_contain' => 'active_jobs',
                'must_not_contain' => ['provider_stats'],
                'desc' => 'Active bookings → active_jobs (NOT provider_stats)'
            ],
            [
                'q' => 'how many pending payouts?',
                'must_contain' => 'payout_balance_overview',
                'must_not_contain' => ['provider_stats'],
                'desc' => 'Pending payouts → payout_balance_overview'
            ],
            [
                'q' => 'how many walkers in Hyderabad?',
                'must_contain' => 'provider_stats',
                'must_not_contain' => [],
                'desc' => 'Walkers in city → provider_stats (correct)'
            ],
            [
                'q' => 'how many completed bookings this month?',
                'must_contain' => 'bookings_summary',
                'must_not_contain' => ['provider_stats'],
                'desc' => 'Completed bookings → bookings_summary'
            ],
            [
                'q' => 'how many booking completed till now?',
                'must_contain' => 'bookings_summary',
                'must_not_contain' => ['provider_stats'],
                'desc' => 'Completed till now → bookings_summary'
            ],
            [
                'q' => 'how many bookings cancelled and what is reason to ?',
                'must_contain' => 'bookings_summary',
                'must_not_contain' => ['provider_stats'],
                'desc' => 'Cancelled with reasons → bookings_summary'
            ],
            [
                'q' => 'how many on-hold bookings',
                'must_contain' => 'bookings_summary',
                'must_not_contain' => ['provider_stats'],
                'desc' => 'On-hold bookings → bookings_summary'
            ],
            [
                'q' => 'how many active bookings walks/sessions untracked till now?',
                'must_contain' => 'walking_tracking_analytics',
                'must_not_contain' => ['active_jobs', 'provider_stats'],
                'desc' => 'Active bookings untracked walks/sessions → walking_tracking_analytics'
            ],
            [
                'q' => 'how many walks untracked till now?',
                'must_contain' => 'walking_tracking_analytics',
                'must_not_contain' => ['active_jobs', 'provider_stats'],
                'desc' => 'Untracked walks till now → walking_tracking_analytics'
            ],
            [
                'q' => 'how many verifications call happen today ?',
                'must_contain' => 'call_verification_overview',
                'must_not_contain' => ['in_app_calls_overview'],
                'desc' => 'Verification calls today → call_verification_overview'
            ],
            [
                'q' => 'which client,service provider try to discussion off-platform from calls',
                'must_contain' => 'call_ai_quality_analytics',
                'must_not_contain' => [],
                'desc' => 'Off-platform discussion query → call_ai_quality_analytics'
            ],
            [
                'q' => 'how many service providers there in manager approval?',
                'must_contain' => 'provider_stats',
                'must_not_contain' => [],
                'desc' => 'Manager approval providers → provider_stats (approval)'
            ],
            [
                'q' => 'how many community channels there?',
                'must_contain' => 'community_overview',
                'must_not_contain' => ['provider_stats', 'active_jobs'],
                'desc' => 'Community channels → community_overview'
            ],
        ];

        $passed = 0;
        $failed = 0;

        foreach ($tests as $i => $test) {
            // Step 1: SemanticResolver check
            $resolved = SemanticResolver::resolve($test['q']);
            $resolverMetric = '';
            if ($resolved && !empty($resolved['metrics'])) {
                $resolverMetric = $resolved['metrics'][0]['name'] ?? '(none)';
            } else {
                $resolverMetric = '(no match)';
            }

            // Step 2: Full GeminiAnalyst pipeline (same as frontend)
            $res = $analyst->ask($test['q'], [], null, 'quick');
            
            $metricsCalled = $res['metrics'] ?? [];
            $metricNames = array_map(fn($m) => $m['name'] ?? '', $metricsCalled);
            $metricStr = implode(', ', $metricNames);

            $pass = true;
            $reason = '';

            // Check must_contain
            if (!in_array($test['must_contain'], $metricNames)) {
                $pass = false;
                $reason = "Expected [{$test['must_contain']}] but got [{$metricStr}]";
            }

            // Check must_not_contain
            if ($pass) {
                foreach ($test['must_not_contain'] as $banned) {
                    if (in_array($banned, $metricNames)) {
                        $pass = false;
                        $reason = "MUST NOT contain [{$banned}] but got [{$metricStr}]";
                        break;
                    }
                }
            }

            $status = $pass ? 'PASS' : 'FAIL';
            $color = $pass ? 'green' : 'red';
            
            CLI::write(sprintf("  #%-2d | %-5s | %s", $i + 1, $status, $test['desc']), $color);
            CLI::write(sprintf("       Resolver: %-25s | E2E Metrics: %s", $resolverMetric, $metricStr), 'light_gray');

            if (!$pass) {
                CLI::write("       ⚠  {$reason}", 'red');
                $failed++;
            } else {
                $passed++;
            }
        }

        CLI::newLine();
        CLI::write("=== RESULTS: {$passed}/" . count($tests) . " passed, {$failed} failed ===", $failed > 0 ? 'red' : 'green');
        
        if ($failed > 0) {
            CLI::write('WARNING: FRONTEND IS NOT ALIGNED WITH EVAL SUITE!', 'red');
        } else {
            CLI::write('FRONTEND IS FULLY ALIGNED - All fixes verified end-to-end!', 'green');
        }
    }
}
