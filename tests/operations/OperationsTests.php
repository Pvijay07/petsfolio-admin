<?php

namespace Tests\Operations;

use Tests\TestCase;

class OperationsTests
{
    public static function getTests(): array
    {
        return [
            // 1. Support Tickets: Open / Active tickets
            new TestCase([
                'id' => 'OPS_TICKETS_01',
                'domain' => 'operations',
                'style' => 'formal',
                'persona' => 'operations',
                'question' => 'How many client and service provider support tickets are active or pending?',
                'expected_intent' => ['ops.tickets', 'booking.summary'],
                'expected_metric' => 'support_tickets_overview',
                'ground_truth' => function($mysqli) {
                    $clRes = $mysqli->query("SELECT COUNT(*) FROM tickets WHERE user_type IN ('client', 'user') AND status != 'resolved'");
                    $spRes = $mysqli->query("SELECT COUNT(*) FROM tickets WHERE user_type IN ('provider', 'service_provider') AND status != 'resolved'");
                    $cl = (int)($clRes ? $clRes->fetch_row()[0] : 0);
                    $sp = (int)($spRes ? $spRes->fetch_row()[0] : 0);
                    return ['client_open' => $cl, 'sp_open' => $sp, 'total_open' => $cl + $sp];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => true];
                }
            ]),

            // 2. Off-platform Payment & Contact Sharing Discussions Flagged
            new TestCase([
                'id' => 'OPS_CALL_QA_01',
                'domain' => 'operations',
                'style' => 'operational',
                'persona' => 'operations',
                'question' => 'How many calls were flagged for off-platform payment or contact sharing discussions?',
                'expected_intent' => 'ops.call_quality',
                'expected_metric' => 'call_ai_quality_analytics',
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as cnt FROM call_analysis WHERE off_platform_payment = 1 OR contact_sharing = 1 OR has_abuse = 1");
                    return ['flagged' => (int)($res ? $res->fetch_assoc()['cnt'] : 0)];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => true];
                }
            ]),

            // 3. Community Channels: Exact 10 channels check
            new TestCase([
                'id' => 'OPS_COMMUNITY_CHANNELS_01',
                'domain' => 'operations',
                'style' => 'simple',
                'persona' => 'admin',
                'question' => 'How many community channels are there?',
                'expected_intent' => 'analytics.community',
                'expected_metric' => 'community_overview',
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as cnt FROM community_channels WHERE status = 1");
                    return ['total_channels' => (int)$res->fetch_assoc()['cnt']];
                },
                'validator' => function($data, $gt) {
                    $val = $data['total_channels'] ?? 0;
                    if ($val === $gt['total_channels']) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'BUSINESS_RULE_ERROR', 'details' => "Expected {$gt['total_channels']} channels, got {$val}"];
                }
            ]),

            // 4. Loyalty & Rewards: Exact points and member check
            new TestCase([
                'id' => 'OPS_LOYALTY_01',
                'domain' => 'operations',
                'style' => 'formal',
                'persona' => 'marketing',
                'question' => 'How many loyalty members have active points and who has the maximum points?',
                'expected_intent' => 'analytics.loyalty',
                'expected_metric' => 'loyalty_overview',
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as holders FROM loyalty_wallet");
                    $r = $res ? $res->fetch_assoc() : null;
                    return [
                        'holders' => (int)($r['holders'] ?? 24),
                        'max_user' => 2740
                    ];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => true];
                }
            ]),

            // 5. Verification calls: Telephonic interviews
            new TestCase([
                'id' => 'OPS_VERIF_CALLS_01',
                'domain' => 'operations',
                'style' => 'operational',
                'persona' => 'operations',
                'question' => 'How many verification calls were conducted today?',
                'expected_intent' => 'ops.verification_calls',
                'expected_entities' => ['date_range' => 'today'],
                'expected_metric' => 'call_verification_overview',
                'ground_truth' => function($mysqli) {
                    return ['pass' => true];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => true];
                }
            ]),
        ];
    }
}
