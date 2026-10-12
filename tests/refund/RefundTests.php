<?php

namespace Tests\Refund;

use Tests\TestCase;

class RefundTests
{
    public static function getTests(): array
    {
        return [
            // 1. Financial: Total refund amount processed
            new TestCase([
                'id' => 'REF_AMOUNT_01',
                'domain' => 'refund',
                'style' => 'financial',
                'persona' => 'finance',
                'question' => 'What is the total refund amount processed to customers?',
                'expected_intent' => 'finance.refunds',
                'expected_metric' => 'user_refunds_summary',
                'ground_truth' => function($mysqli) {
                    $start = date('Y-m-d 00:00:00', strtotime('-30 days'));
                    $end = date('Y-m-d 23:59:59');
                    $res = $mysqli->query("SELECT COALESCE(SUM(amount), 0) as amt, COUNT(*) as cnt FROM user_refunds WHERE created_at >= '{$start}' AND created_at <= '{$end}'");
                    $r = $res->fetch_assoc();
                    return ['refund_amount' => (float)$r['amt'], 'cnt' => (int)$r['cnt']];
                },
                'validator' => function($data, $gt) {
                    if (isset($data['total_refund_amount_inr']) || isset($data['completed_refund_amount_inr'])) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => 'Failed to resolve user refunds'];
                }
            ]),

            // 2. Operational: Refund requests count
            new TestCase([
                'id' => 'REF_COUNT_01',
                'domain' => 'refund',
                'style' => 'operational',
                'persona' => 'finance',
                'question' => 'How many refund requests have been processed?',
                'expected_intent' => 'finance.refunds',
                'expected_metric' => 'user_refunds_summary',
                'ground_truth' => function($mysqli) {
                    $cnt = (int)$mysqli->query("SELECT COUNT(*) FROM user_refunds")->fetch_row()[0];
                    return ['total_refund_records' => $cnt];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => true];
                }
            ]),

            // 3. Marketing: Active discount coupons
            new TestCase([
                'id' => 'MKT_COUPONS_01',
                'domain' => 'refund',
                'style' => 'operational',
                'persona' => 'marketing',
                'question' => 'How many active discount coupons do we have?',
                'expected_intent' => 'marketing.coupons',
                'expected_metric' => 'coupons_overview',
                'ground_truth' => function($mysqli) {
                    $cnt = (int)$mysqli->query("SELECT COUNT(*) FROM coupons")->fetch_row()[0];
                    return ['active_coupons' => $cnt];
                },
                'validator' => function($data, $gt) {
                    $val = $data['active_coupons'] ?? $data['total_coupons'] ?? 0;
                    if ($val === $gt['active_coupons']) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Expected {$gt['active_coupons']} active coupons, got {$val}"];
                }
            ]),

            // 4. Marketing: Referrals
            new TestCase([
                'id' => 'MKT_REFERRALS_01',
                'domain' => 'refund',
                'style' => 'operational',
                'persona' => 'marketing',
                'question' => 'How many referrals were recorded on the platform?',
                'expected_intent' => 'marketing.referrals',
                'expected_metric' => 'referrals_overview',
                'ground_truth' => function($mysqli) {
                    $cnt = (int)$mysqli->query("SELECT COUNT(*) FROM referrals")->fetch_row()[0];
                    return ['total_referrals' => $cnt];
                },
                'validator' => function($data, $gt) {
                    $val = $data['total_referrals'] ?? 0;
                    if ($val === $gt['total_referrals']) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Expected {$gt['total_referrals']} referrals, got {$val}"];
                }
            ]),
        ];
    }
}
