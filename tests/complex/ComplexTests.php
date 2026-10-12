<?php

namespace Tests\Complex;

use Tests\TestCase;

class ComplexTests
{
    public static function getTests(): array
    {
        return [
            // ─────────────────────────────────────────────
            // POSITIVE ANALYTICAL QUESTIONS (Success, High Performance, Quality, Recovery)
            // ─────────────────────────────────────────────

            // 1. Positive / Quality: Highest performance scores and completion quality
            new TestCase([
                'id' => 'CMPLX_POS_SP_QUAL_01',
                'domain' => 'complex',
                'style' => 'ranking',
                'persona' => 'ceo',
                'question' => 'Which service providers have the highest performance scores and completion quality?',
                'expected_intent' => 'provider.performance',
                'expected_metric' => 'provider_performance_score',
                'ground_truth' => function($mysqli) {
                    $cnt = (int)$mysqli->query("SELECT COUNT(*) FROM service_providers WHERE status IN ('active', 'approved', 'verified', '1')")->fetch_row()[0];
                    return ['active_providers' => $cnt];
                },
                'validator' => function($data, $gt) {
                    if (isset($data['top_providers']) && isset($data['total_analyzed'])) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => 'Missing provider performance score data'];
                }
            ]),

            // 2. Positive / Financial Recovery: Recovered dropped transactions
            new TestCase([
                'id' => 'CMPLX_POS_RECOVERED_01',
                'domain' => 'complex',
                'style' => 'financial',
                'persona' => 'finance',
                'question' => 'How much revenue and how many dropped transactions were successfully recovered?',
                'expected_intent' => ['finance.recovered', 'revenue.overview'],
                'expected_metric' => ['recovered_transactions_overview', 'revenue_overview'],
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) FROM transactions WHERE status IN ('completed', 'success', 'paid')");
                    return ['txns' => (int)$res->fetch_row()[0]];
                },
                'validator' => function($data, $gt) {
                    if (isset($data['total_recovered_bookings']) || isset($data['total_recovered_transactions']) || isset($data['total_recovered_revenue_inr']) || !empty($data['metric'])) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => 'Failed to resolve recovered transactions'];
                }
            ]),

            // 3. Positive / Marketing Acquisition: Best converting lead sources
            new TestCase([
                'id' => 'CMPLX_POS_LEAD_SRC_01',
                'domain' => 'complex',
                'style' => 'business',
                'persona' => 'marketing',
                'question' => 'Which lead acquisition sources have our best conversion rate and lowest dropoff?',
                'expected_intent' => 'marketing.lead_sources',
                'expected_metric' => 'lead_source_performance',
                'ground_truth' => function($mysqli) {
                    return ['pass' => true];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => ($data['metric'] ?? '') === 'lead_source_performance'];
                }
            ]),

            // 4. Positive / Geographic Growth: Highest gross revenue & bookings city
            new TestCase([
                'id' => 'CMPLX_POS_TOP_CITY_01',
                'domain' => 'complex',
                'style' => 'ranking',
                'persona' => 'operations',
                'question' => 'Which city generated the highest gross revenue and bookings this month?',
                'expected_intent' => ['revenue.overview', 'booking.summary'],
                'expected_metric' => ['revenue_overview', 'bookings_summary'],
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT city, SUM(total_amount) as rev FROM v_bookings_overview WHERE is_deleted = 0 AND status NOT IN ('Cancelled', 'Deleted') GROUP BY city ORDER BY rev DESC LIMIT 1");
                    $r = $res->fetch_assoc();
                    return ['top_city' => $r['city'] ?? 'hyderabad'];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => !empty($data['metric'])];
                }
            ]),

            // ─────────────────────────────────────────────
            // NEGATIVE ANALYTICAL QUESTIONS (Strikes, Churn, Fraud, Shortages, Failures)
            // ─────────────────────────────────────────────

            // 5. Negative / Provider Disciplinary: Strikes & attendance violations
            new TestCase([
                'id' => 'CMPLX_NEG_STRIKES_01',
                'domain' => 'complex',
                'style' => 'operational',
                'persona' => 'operations',
                'question' => 'Which service providers received disciplinary strikes or attendance violations?',
                'expected_intent' => 'operations.strikes',
                'expected_metric' => 'provider_strikes_attendance',
                'ground_truth' => function($mysqli) {
                    $cnt = (int)$mysqli->query("SELECT COUNT(*) FROM sp_strikes")->fetch_row()[0];
                    return ['strikes_count' => $cnt];
                },
                'validator' => function($data, $gt) {
                    if (isset($data['total_strikes']) && isset($data['total_attendance_logs'])) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => 'Missing strikes & attendance data'];
                }
            ]),

            // 6. Negative / Customer Churn: Inactive or churned customers
            new TestCase([
                'id' => 'CMPLX_NEG_CHURN_01',
                'domain' => 'complex',
                'style' => 'analytical',
                'persona' => 'marketing',
                'question' => 'How many customers are inactive or churned without bookings in the last 90 days?',
                'expected_intent' => 'customer.churn',
                'expected_metric' => 'churned_inactive_customers',
                'ground_truth' => function($mysqli) {
                    $cnt = (int)$mysqli->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
                    return ['total_users' => $cnt];
                },
                'validator' => function($data, $gt) {
                    if (isset($data['total_inactive_clients']) || isset($data['churned_clients_count']) || ($data['metric'] ?? '') === 'churned_inactive_customers') {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => 'Missing churn data'];
                }
            ]),

            // 7. Negative / Fraud & Risk: Completion spikes and cancellation spikes
            new TestCase([
                'id' => 'CMPLX_NEG_FRAUD_01',
                'domain' => 'complex',
                'style' => 'adversarial',
                'persona' => 'admin',
                'question' => 'Detect any fraud anomalies, completion spikes, or abnormal cancellation patterns.',
                'expected_intent' => 'operations.fraud_anomalies',
                'expected_metric' => 'fraud_anomaly_detection',
                'ground_truth' => function($mysqli) {
                    return ['pass' => true];
                },
                'validator' => function($data, $gt) {
                    if (isset($data['anomalies_detected']) && (isset($data['anomaly_list']) || isset($data['anomalies']))) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => 'Missing fraud anomaly data'];
                }
            ]),

            // 8. Negative / Supply Bottlenecks: Low provider density
            new TestCase([
                'id' => 'CMPLX_NEG_DENSITY_01',
                'domain' => 'complex',
                'style' => 'operational',
                'persona' => 'operations',
                'question' => 'Which areas have severe provider shortages with low active provider density?',
                'expected_intent' => 'operations.density',
                'expected_metric' => 'low_provider_density_areas',
                'ground_truth' => function($mysqli) {
                    $cnt = (int)$mysqli->query("SELECT COUNT(DISTINCT city) FROM service_providers")->fetch_row()[0];
                    return ['cities' => $cnt];
                },
                'validator' => function($data, $gt) {
                    if (isset($data['low_density_areas']) || isset($data['total_areas_analyzed']) || ($data['metric'] ?? '') === 'low_provider_density_areas') {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => 'Missing low provider density data'];
                }
            ]),

            // 9. Negative / Financial Failures: Payment failures and transaction errors
            new TestCase([
                'id' => 'CMPLX_NEG_PAY_FAIL_01',
                'domain' => 'complex',
                'style' => 'financial',
                'persona' => 'finance',
                'question' => 'What are the primary failure reasons and error patterns for failed booking payments?',
                'expected_intent' => 'payments.booking_transactions',
                'expected_metric' => 'booking_payments_overview',
                'ground_truth' => function($mysqli) {
                    $cnt = (int)$mysqli->query("SELECT COUNT(*) FROM transactions WHERE status = 'failed'")->fetch_row()[0];
                    return ['failed_cnt' => $cnt];
                },
                'validator' => function($data, $gt) {
                    if (isset($data['failed_transactions']) || isset($data['failed_payments_count']) || !empty($data['metric'])) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => 'Missing payment failure analysis'];
                }
            ]),

            // ─────────────────────────────────────────────
            // COMPLEX MULTI-ASPECT QUESTIONS (Cross-Metric, Imbalance, Net Financials)
            // ─────────────────────────────────────────────

            // 10. Complex Comparative: Top revenue service vs cancellations
            new TestCase([
                'id' => 'CMPLX_CMP_REV_CANC_01',
                'domain' => 'complex',
                'style' => 'comparative',
                'persona' => 'ceo',
                'question' => 'Compare our highest revenue generating service against our cancellation rate.',
                'expected_intent' => ['revenue.overview', 'cancellation.overview', 'booking.summary'],
                'expected_metric' => ['revenue_overview', 'cancellation_rate', 'bookings_summary'],
                'ground_truth' => function($mysqli) {
                    return ['pass' => true];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => !empty($data['metric'])];
                }
            ]),

            // 11. Complex Multi-Clause: Locality Demand vs Supply Imbalance
            new TestCase([
                'id' => 'CMPLX_MLT_DEMAND_SUPPLY_01',
                'domain' => 'complex',
                'style' => 'business',
                'persona' => 'operations',
                'question' => 'Which localities have high customer booking demand but inadequate provider supply?',
                'expected_intent' => 'operations.demand_supply',
                'expected_metric' => 'locality_demand_supply',
                'ground_truth' => function($mysqli) {
                    $cnt = (int)$mysqli->query("SELECT COUNT(DISTINCT city) FROM service_providers")->fetch_row()[0];
                    return ['sp_cities' => $cnt];
                },
                'validator' => function($data, $gt) {
                    if (isset($data['demand_by_city']) || isset($data['supply_by_city']) || ($data['metric'] ?? '') === 'locality_demand_supply') {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => 'Missing demand vs supply data'];
                }
            ]),

            // 12. Complex Multi-Financial: Net revenue after customer refunds
            new TestCase([
                'id' => 'CMPLX_MLT_REV_REFUND_01',
                'domain' => 'complex',
                'style' => 'financial',
                'persona' => 'finance',
                'question' => 'Analyze gross revenue alongside total refunded amounts to customers.',
                'expected_intent' => ['finance.refunds', 'revenue.overview'],
                'expected_metric' => ['user_refunds_summary', 'revenue_overview'],
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COALESCE(SUM(amount), 0) as amt FROM user_refunds");
                    return ['refund_sum' => (float)$res->fetch_row()[0]];
                },
                'validator' => function($data, $gt) {
                    if (isset($data['total_refund_amount_inr']) || isset($data['total_gross_booking_value_inr']) || isset($data['total_revenue_inr']) || !empty($data['metric'])) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => 'Missing refund & revenue data'];
                }
            ]),

            // 13. Complex Multi-Risk: At-risk service providers with low scores
            new TestCase([
                'id' => 'CMPLX_MLT_AT_RISK_SP_01',
                'domain' => 'complex',
                'style' => 'adversarial',
                'persona' => 'ceo',
                'question' => 'Identify at-risk service providers with low performance scores and review ratings.',
                'expected_intent' => 'provider.performance',
                'expected_metric' => 'provider_performance_score',
                'ground_truth' => function($mysqli) {
                    return ['pass' => true];
                },
                'validator' => function($data, $gt) {
                    if (isset($data['at_risk_providers']) && isset($data['top_providers'])) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => 'Missing at-risk provider breakdown'];
                }
            ]),
        ];
    }
}
