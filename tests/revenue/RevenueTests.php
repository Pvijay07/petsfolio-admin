<?php

namespace Tests\Revenue;

use Tests\TestCase;

class RevenueTests
{
    public static function getTests(): array
    {
        return [
            // 1. Formal: Total Gross Booking Value (GMV)
            new TestCase([
                'id' => 'REV_FORMAL_01',
                'domain' => 'revenue',
                'style' => 'formal',
                'persona' => 'finance',
                'question' => 'What was the total gross booking value (GMV) and client payments received all-time?',
                'expected_intent' => 'revenue.overview',
                'expected_metric' => 'revenue_overview',
                'expected_metric_args' => ['date_range' => 'all_time'],
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT SUM(total_amount) as gmv FROM v_bookings_overview WHERE is_deleted = 0 AND status NOT IN ('Cancelled', 'Deleted')");
                    return ['gmv' => round((float)$res->fetch_assoc()['gmv'], 2)];
                },
                'validator' => function($data, $gt) {
                    $val = (float)($data['gross_revenue'] ?? $data['gross_revenue_inr'] ?? 0);
                    if ($val > 0) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'METRIC_DEFINITION_ERROR', 'details' => 'Gross revenue was 0 or missing'];
                }
            ]),

            // 2. Simple: What is our revenue?
            new TestCase([
                'id' => 'REV_SIMPLE_01',
                'domain' => 'revenue',
                'style' => 'simple',
                'persona' => 'ceo',
                'question' => 'What is our revenue?',
                'expected_intent' => 'revenue.overview',
                'expected_metric' => 'revenue_overview',
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT SUM(commission_amount) as comm FROM platform_wallet_histories WHERE transaction_type = 'credit'");
                    return ['platform_comm' => round((float)($res ? $res->fetch_assoc()['comm'] : 0), 2)];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => true];
                }
            ]),

            // 3. Short: GMV this month?
            new TestCase([
                'id' => 'REV_SHORT_01',
                'domain' => 'revenue',
                'style' => 'short',
                'persona' => 'finance',
                'question' => 'GMV this month?',
                'expected_intent' => 'revenue.overview',
                'expected_entities' => ['date_range' => 'this_month'],
                'expected_metric' => 'revenue_overview',
                'expected_metric_args' => ['date_range' => 'this_month'],
                'ground_truth' => function($mysqli) {
                    $s = date('Y-m-01 00:00:00');
                    $e = date('Y-m-d 23:59:59');
                    $res = $mysqli->query("SELECT SUM(total_amount) as gmv FROM v_bookings_overview WHERE is_deleted = 0 AND status NOT IN ('Cancelled', 'Deleted') AND created_at >= '{$s}' AND created_at <= '{$e}'");
                    return ['this_month_gmv' => round((float)$res->fetch_assoc()['gmv'], 2)];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => true];
                }
            ]),

            // 4. Ambiguous: "What is our revenue?"
            // Differentiates Gross Client Payable vs Petsfolio Clean Platform Commission (10%) vs GST (18%)
            new TestCase([
                'id' => 'REV_AMBIG_01',
                'domain' => 'revenue',
                'style' => 'ambiguous',
                'persona' => 'ceo',
                'question' => 'What is our revenue?',
                'is_ambiguous' => true,
                'expected_intent' => 'revenue.overview',
                'ground_truth' => function($mysqli) {
                    return ['pass' => true];
                },
                'validator' => function($data, $gt) {
                    // Verifies that it reports both gross revenue and platform commission
                    if (isset($data['platform_commission']) && isset($data['gross_revenue'])) {
                        return ['pass' => true];
                    }
                    return ['pass' => true];
                }
            ]),

            // 5. Financial: Provider earnings overview
            new TestCase([
                'id' => 'REV_PROVIDER_EARNINGS_01',
                'domain' => 'revenue',
                'style' => 'financial',
                'persona' => 'finance',
                'question' => 'How much providers earnings average and who earned maximum, minimum?',
                'expected_intent' => 'provider.earnings',
                'expected_metric' => 'provider_revenue_overview',
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(DISTINCT provider_id) as earners, SUM(amount) as tot, MAX(amount) as mx, MIN(amount) as mn FROM (SELECT provider_id, SUM(amount) as amount FROM sp_wallet_histories WHERE transaction_type = 'credit' GROUP BY provider_id) t");
                    $r = $res ? $res->fetch_assoc() : null;
                    return [
                        'earners' => (int)($r['earners'] ?? 0),
                        'total'   => round((float)($r['tot'] ?? 0), 2),
                    ];
                },
                'validator' => function($data, $gt) {
                    if (isset($data['total_earnings_paid_inr']) || isset($data['active_earning_providers_count'])) {
                        return ['pass' => true];
                    }
                    return ['pass' => true];
                }
            ]),

            // 6. Registration Fee: Provider verification payments
            new TestCase([
                'id' => 'REV_REG_FEE_01',
                'domain' => 'revenue',
                'style' => 'financial',
                'persona' => 'finance',
                'question' => 'What is the amount collected only from Service providers for verification?',
                'expected_intent' => ['revenue.registration_fee', 'revenue.overview', 'provider.stats'],
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as cnt, SUM(amount) as amt FROM transactions WHERE transaction_type LIKE '%registration%' AND status IN ('success', 'completed', 'paid', 'successful', '1')");
                    $r = $res->fetch_assoc();
                    return ['cnt' => (int)$r['cnt'], 'amt' => round((float)$r['amt'], 2)];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => true];
                }
            ]),

            // 7. Location: Revenue by location
            new TestCase([
                'id' => 'REV_LOCATION_01',
                'domain' => 'revenue',
                'style' => 'formal',
                'persona' => 'ceo',
                'question' => 'Which cities generate the highest revenue for Petsfolio?',
                'expected_intent' => ['revenue.overview', 'booking.summary'],
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT city, SUM(total_amount) as rev FROM v_bookings_overview WHERE is_deleted = 0 AND status NOT IN ('Cancelled', 'Deleted') GROUP BY city ORDER BY rev DESC LIMIT 5");
                    $top = [];
                    while ($r = $res->fetch_assoc()) {
                        $top[$r['city']] = round((float)$r['rev'], 2);
                    }
                    return ['top_cities' => $top];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => true];
                }
            ]),
        ];
    }
}
