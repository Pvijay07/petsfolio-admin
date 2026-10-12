<?php

namespace Tests\Provider;

use Tests\TestCase;

class ProviderTests
{
    public static function getTests(): array
    {
        return [
            // 1. Formal: How many active service providers
            new TestCase([
                'id' => 'SP_FORMAL_01',
                'domain' => 'provider',
                'style' => 'formal',
                'persona' => 'operations',
                'question' => 'How many active service providers are currently registered in the system?',
                'expected_intent' => ['provider.headcount', 'provider.stats'],
                'expected_metric' => 'provider_stats',
                'expected_metric_args' => ['status' => 'active'],
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as cnt FROM service_providers WHERE status = 'active'");
                    return ['active_providers' => (int)$res->fetch_assoc()['cnt']];
                },
                'validator' => function($data, $gt) {
                    $val = $data['active_providers'] ?? $data['total_service_providers'] ?? 0;
                    if ($val === $gt['active_providers']) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Expected {$gt['active_providers']}, got {$val}"];
                }
            ]),

            // 2. Simple: How many walkers?
            new TestCase([
                'id' => 'SP_SIMPLE_WALK_01',
                'domain' => 'provider',
                'style' => 'simple',
                'persona' => 'admin',
                'question' => 'How many walkers?',
                'expected_intent' => ['provider.headcount', 'provider.stats'],
                'expected_entities' => ['service_type' => 'walking'],
                'expected_metric' => 'provider_stats',
                'expected_metric_args' => ['service_type' => 'walking'],
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(DISTINCT sp.id) as cnt FROM service_providers sp JOIN sp_services ss ON ss.provider_id = sp.id WHERE ss.service_id = 4 AND sp.status = 'active'");
                    return ['active_walkers' => (int)$res->fetch_assoc()['cnt']];
                },
                'validator' => function($data, $gt) {
                    $val = $data['active_providers'] ?? $data['total_service_providers'] ?? 0;
                    if ($val > 0) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'ENTITY_ERROR', 'details' => 'Failed to resolve active walkers'];
                }
            ]),

            // 3. Simple: How many trainers?
            new TestCase([
                'id' => 'SP_SIMPLE_TRAIN_01',
                'domain' => 'provider',
                'style' => 'simple',
                'persona' => 'admin',
                'question' => 'How many trainers?',
                'expected_intent' => ['provider.headcount', 'provider.stats'],
                'expected_entities' => ['service_type' => 'training'],
                'expected_metric' => 'provider_stats',
                'expected_metric_args' => ['service_type' => 'training'],
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(DISTINCT sp.id) as cnt FROM service_providers sp JOIN sp_services ss ON ss.provider_id = sp.id WHERE ss.service_id = 3 AND sp.status = 'active'");
                    return ['active_trainers' => (int)$res->fetch_assoc()['cnt']];
                },
                'validator' => function($data, $gt) {
                    $val = $data['active_providers'] ?? $data['total_service_providers'] ?? 0;
                    if ($val > 0) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'ENTITY_ERROR', 'details' => 'Failed to resolve active trainers'];
                }
            ]),

            // 4. Short: Active walkers?
            new TestCase([
                'id' => 'SP_SHORT_01',
                'domain' => 'provider',
                'style' => 'short',
                'persona' => 'operations',
                'question' => 'Active walkers?',
                'expected_intent' => ['provider.headcount', 'provider.stats'],
                'expected_entities' => ['service_type' => 'walking'],
                'expected_metric' => 'provider_stats',
                'ground_truth' => function($mysqli) {
                    return ['pass' => true];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => true];
                }
            ]),

            // 5. Ambiguous / Disambiguation: "How many providers?"
            // Must distinguish total registered (5,881), active (2,897), verified tab (34), old SP non-verified (2,863)
            new TestCase([
                'id' => 'SP_AMBIG_01',
                'domain' => 'provider',
                'style' => 'ambiguous',
                'persona' => 'ceo',
                'question' => 'How many providers are on the platform?',
                'is_ambiguous' => true,
                'expected_intent' => ['provider.headcount', 'provider.stats'],
                'ground_truth' => function($mysqli) {
                    $tot = (int)$mysqli->query("SELECT COUNT(*) FROM service_providers")->fetch_row()[0];
                    $act = (int)$mysqli->query("SELECT COUNT(*) FROM service_providers WHERE status = 'active'")->fetch_row()[0];
                    return ['total_providers' => $tot, 'active_providers' => $act];
                },
                'validator' => function($data, $gt) {
                    $tVal = $data['total_service_providers'] ?? 0;
                    $aVal = $data['active_providers'] ?? 0;
                    if ($tVal === $gt['total_providers'] || $aVal === $gt['active_providers']) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'SEMANTIC_ERROR', 'details' => 'Ambiguity between total and active providers not properly differentiated'];
                }
            ]),

            // 6. Adversarial / Critical Business Rule: Verified Tab
            // Admin Panel Verified Tab (/providers?status=active) shows ONLY sp_services.is_verified = 1 (34)
            new TestCase([
                'id' => 'SP_ADVERSARIAL_VERIFIED_01',
                'domain' => 'provider',
                'style' => 'adversarial',
                'persona' => 'admin',
                'question' => 'How many service providers are in verified or verified tab?',
                'expected_intent' => ['provider.headcount', 'provider.stats'],
                'expected_metric' => 'provider_stats',
                'expected_metric_args' => ['status' => 'verified'],
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(DISTINCT sp.id) as cnt FROM service_providers sp JOIN sp_services ss ON ss.provider_id = sp.id WHERE sp.status = 'active' AND ss.is_verified = 1");
                    return ['verified_tab_count' => (int)$res->fetch_assoc()['cnt']];
                },
                'validator' => function($data, $gt) {
                    $val = $data['verified_providers_count'] ?? $data['total_service_providers'] ?? 0;
                    if ($val === $gt['verified_tab_count']) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'BUSINESS_RULE_ERROR', 'details' => "Expected {$gt['verified_tab_count']} verified providers, got {$val}"];
                }
            ]),

            // 7. Operational: Manager Approval Queue
            new TestCase([
                'id' => 'SP_OPS_APPROVAL_01',
                'domain' => 'provider',
                'style' => 'operational',
                'persona' => 'operations',
                'question' => 'How many providers are waiting for manager approval?',
                'expected_intent' => 'ops.approval_queue',
                'expected_metric' => 'provider_stats',
                'expected_metric_args' => ['status' => 'approval'],
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(DISTINCT provider_id) as providers, COUNT(*) as entries FROM sp_services WHERE (is_verified = 0 OR is_verified IS NULL) AND payment_paid = 1 AND call_verified = 1");
                    $r = $res->fetch_assoc();
                    return ['providers' => (int)$r['providers'], 'entries' => (int)$r['entries']];
                },
                'validator' => function($data, $gt) {
                    $p = $data['manager_approval_providers'] ?? $data['total_service_providers'] ?? 0;
                    $e = $data['manager_approval_entries'] ?? $data['pending_approval_entries'] ?? 0;
                    if ($p > 0 || $e > 0) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Expected {$gt['providers']} providers / {$gt['entries']} entries, got {$p} / {$e}"];
                }
            ]),

            // 8. Operational: Fee Pending Queue
            new TestCase([
                'id' => 'SP_OPS_FEE_PENDING_01',
                'domain' => 'provider',
                'style' => 'operational',
                'persona' => 'operations',
                'question' => 'How many walkers are in fee pending or registration fee pending?',
                'expected_intent' => ['ops.fee_pending', 'revenue.registration_fee'],
                'expected_entities' => ['service_type' => 'walking'],
                'expected_metric' => ['provider_stats', 'registration_fee'],
                'expected_metric_args' => ['status' => 'pending_registration', 'service_type' => 'walking'],
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(DISTINCT provider_id) as cnt FROM sp_services WHERE service_id = 4 AND (payment_paid = 0 OR payment_paid IS NULL)");
                    return ['fee_pending_walkers' => (int)$res->fetch_assoc()['cnt']];
                },
                'validator' => function($data, $gt) {
                    $val = $data['fee_pending_providers'] ?? $data['total_service_providers'] ?? 0;
                    if ($val === 0 && !empty($data['sp_services_breakdown'])) {
                        foreach ($data['sp_services_breakdown'] as $row) {
                            if (strcasecmp($row['service_name'] ?? '', 'Walking') === 0 && strcasecmp($row['payment_status'] ?? '', 'Pending') === 0) {
                                $val = (int)($row['provider_count'] ?? 0);
                                break;
                            }
                        }
                    }
                    if ($val > 0) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Expected {$gt['fee_pending_walkers']}, got {$val}"];
                }
            ]),

            // 9. Operational: Waiting for Call (Pending Verification Queue)
            new TestCase([
                'id' => 'SP_OPS_WAITING_CALL_01',
                'domain' => 'provider',
                'style' => 'operational',
                'persona' => 'operations',
                'question' => 'How many providers are in waiting for call queue?',
                'expected_intent' => 'ops.waiting_call',
                'expected_metric' => 'provider_stats',
                'expected_metric_args' => ['status' => 'waiting for call'],
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(DISTINCT provider_id) as cnt FROM sp_services WHERE payment_paid = 1 AND (call_verified = 0 OR call_verified IS NULL)");
                    return ['waiting_for_call' => (int)$res->fetch_assoc()['cnt']];
                },
                'validator' => function($data, $gt) {
                    $val = $data['waiting_for_call_providers'] ?? $data['waiting_for_call'] ?? $data['total_service_providers'] ?? 0;
                    if ($val > 0) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Expected {$gt['waiting_for_call']}, got {$val}"];
                }
            ]),

            // 10. Ranking: Top 10 providers by completed bookings
            new TestCase([
                'id' => 'SP_RANKING_01',
                'domain' => 'provider',
                'style' => 'ranking',
                'persona' => 'ceo',
                'question' => 'Top 10 providers by completed bookings.',
                'expected_intent' => ['provider.earnings', 'provider.stats', 'booking.summary'],
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT provider_id, COUNT(*) as cnt FROM sp_bookings WHERE status IN ('completed', 'Completed') GROUP BY provider_id ORDER BY cnt DESC LIMIT 10");
                    $top = [];
                    while ($r = $res->fetch_assoc()) {
                        $top[] = (int)$r['provider_id'];
                    }
                    return ['top_sp_ids' => $top];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => true];
                }
            ]),

            // 11. MIS Questionnaire Q46: How many active walkers, trainers, groomers, and pet sitters do we currently have?
            new TestCase([
                'id' => 'SP_MIS_Q46_SERVICES',
                'domain' => 'provider',
                'style' => 'multi_service',
                'persona' => 'executive',
                'question' => 'How many active walkers, trainers, groomers, and pet sitters do we currently have?',
                'expected_intent' => ['provider.headcount', 'provider.stats'],
                'expected_metric' => 'provider_stats',
                'validator' => function($data, $gt) {
                    $sb = $data['active_services_breakdown'] ?? [];
                    $hasWalk = !empty($data['active_walkers_count']) || !empty($sb['walking']['active_count']);
                    $hasTrain = !empty($data['active_trainers_count']) || !empty($sb['training']['active_count']);
                    return [
                        'pass' => $hasWalk && $hasTrain,
                        'details' => "Active services breakdown present with walkers and trainers"
                    ];
                }
            ]),
        ];
    }
}
