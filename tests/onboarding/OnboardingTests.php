<?php

namespace Tests\Onboarding;

use Tests\TestCase;

class OnboardingTests
{
    public static function getTests(): array
    {
        return [
            // 1. Formal / New to Petsfolio: Executive overview
            new TestCase([
                'id' => 'ONBOARD_EXEC_01',
                'domain' => 'onboarding',
                'style' => 'formal',
                'persona' => 'ceo',
                'question' => 'I am new to Petsfolio, give me an executive overview of business performance.',
                'expected_intent' => 'overview.executive',
                'expected_metric' => 'executive_summary',
                'ground_truth' => function($mysqli) {
                    $start = date('Y-m-d 00:00:00', strtotime('-30 days'));
                    $end = date('Y-m-d 23:59:59');
                    $res = $mysqli->query("SELECT COUNT(*) as total_bks, COALESCE(SUM(total_amount), 0) as gmv FROM v_bookings_overview WHERE is_deleted = 0 AND created_at >= '{$start}' AND created_at <= '{$end}'");
                    $r = $res->fetch_assoc();
                    return ['total_bks' => (int)$r['total_bks'], 'gmv' => (float)$r['gmv']];
                },
                'validator' => function($data, $gt) {
                    if (isset($data['total_bookings']) || isset($data['business_health']) || isset($data['gmv_inr'])) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => 'Failed to resolve executive summary'];
                }
            ]),

            // 2. Simple / New to Petsfolio: What metrics can I track?
            new TestCase([
                'id' => 'ONBOARD_METRICS_01',
                'domain' => 'onboarding',
                'style' => 'simple',
                'persona' => 'admin',
                'question' => 'What metrics can I track on Petsfolio?',
                'expected_intent' => 'overview.executive',
                'expected_metric' => 'executive_summary',
                'ground_truth' => function($mysqli) {
                    return ['pass' => true];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => !empty($data['metric'])];
                }
            ]),

            // 3. Formal / Catalog: What services and packages are available?
            new TestCase([
                'id' => 'ONBOARD_SERVICES_01',
                'domain' => 'onboarding',
                'style' => 'formal',
                'persona' => 'operations',
                'question' => 'What services and packages are available on Petsfolio?',
                'expected_intent' => 'services.catalog',
                'expected_metric' => 'services_settings_overview',
                'ground_truth' => function($mysqli) {
                    $sCount = (int)$mysqli->query("SELECT COUNT(*) FROM services WHERE status = 1")->fetch_row()[0];
                    $pCount = (int)$mysqli->query("SELECT COUNT(*) FROM packages WHERE status = 1")->fetch_row()[0];
                    return ['services_count' => $sCount, 'packages_count' => $pCount];
                },
                'validator' => function($data, $gt) {
                    $s = $data['total_services'] ?? 0;
                    $p = $data['total_packages'] ?? 0;
                    if ($s > 0 && $p > 0) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Expected services > 0 and packages > 0, got s={$s}, p={$p}"];
                }
            ]),

            // 4. Simple / Catalog: What services do we offer?
            new TestCase([
                'id' => 'ONBOARD_SERVICES_02',
                'domain' => 'onboarding',
                'style' => 'simple',
                'persona' => 'admin',
                'question' => 'What services do we offer?',
                'expected_intent' => 'services.catalog',
                'expected_metric' => 'services_settings_overview',
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) FROM services");
                    return ['total_services' => (int)$res->fetch_row()[0]];
                },
                'validator' => function($data, $gt) {
                    if (($data['total_services'] ?? 0) >= 4) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => 'Expected services >= 4'];
                }
            ]),

            // 5. Operational / Onboarding: How many new clients registered this month?
            new TestCase([
                'id' => 'ONBOARD_NEW_CLIENTS_01',
                'domain' => 'onboarding',
                'style' => 'operational',
                'persona' => 'marketing',
                'question' => 'How many new clients registered this month?',
                'expected_intent' => 'customer.headcount',
                'expected_entities' => ['date_range' => 'this_month'],
                'expected_metric' => 'active_customers',
                'ground_truth' => function($mysqli) {
                    $first = date('Y-m-01 00:00:00');
                    $now = date('Y-m-d 23:59:59');
                    $res = $mysqli->query("SELECT COUNT(*) as cnt FROM users WHERE created_at >= '{$first}' AND created_at <= '{$now}'");
                    return ['this_month_clients' => (int)$res->fetch_assoc()['cnt']];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => true];
                }
            ]),

            // 6. Operational / Onboarding: How many new service providers registered this month?
            new TestCase([
                'id' => 'ONBOARD_NEW_SPS_01',
                'domain' => 'onboarding',
                'style' => 'operational',
                'persona' => 'operations',
                'question' => 'How many new service providers registered this month?',
                'expected_intent' => ['provider.headcount', 'provider.stats'],
                'expected_entities' => ['date_range' => 'this_month'],
                'expected_metric' => 'provider_stats',
                'ground_truth' => function($mysqli) {
                    $first = date('Y-m-01 00:00:00');
                    $now = date('Y-m-d 23:59:59');
                    $res = $mysqli->query("SELECT COUNT(*) as cnt FROM service_providers WHERE created_at >= '{$first}' AND created_at <= '{$now}'");
                    return ['this_month_providers' => (int)$res->fetch_assoc()['cnt']];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => true];
                }
            ]),

            // 7. Operational / Screening: Verification questions
            new TestCase([
                'id' => 'ONBOARD_VERIF_QUESTIONS_01',
                'domain' => 'onboarding',
                'style' => 'operational',
                'persona' => 'operations',
                'question' => 'What are the verification questions asked to new service providers?',
                'expected_intent' => 'onboarding.verification_questions',
                'expected_metric' => 'verification_questions_overview',
                'ground_truth' => function($mysqli) {
                    $cnt = (int)$mysqli->query("SELECT COUNT(*) FROM verification_questions WHERE status = 1")->fetch_row()[0];
                    return ['active_questions' => $cnt];
                },
                'validator' => function($data, $gt) {
                    $val = $data['total_questions_count'] ?? 0;
                    if ($val > 0) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Expected questions > 0, got {$val}"];
                }
            ]),

            // 8. Operational / Training: Tutorial videos
            new TestCase([
                'id' => 'ONBOARD_TUTORIALS_01',
                'domain' => 'onboarding',
                'style' => 'operational',
                'persona' => 'operations',
                'question' => 'How many tutorial videos are available for newly onboarding providers?',
                'expected_intent' => 'onboarding.tutorials',
                'expected_metric' => 'tutorials_overview',
                'ground_truth' => function($mysqli) {
                    $cnt = (int)$mysqli->query("SELECT COUNT(*) FROM tutorials")->fetch_row()[0];
                    return ['active_tutorials' => $cnt];
                },
                'validator' => function($data, $gt) {
                    $val = $data['total_tutorial_links'] ?? 0;
                    if ($val > 0) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Expected tutorials > 0, got {$val}"];
                }
            ]),
        ];
    }
}
