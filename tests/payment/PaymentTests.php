<?php

namespace Tests\Payment;

use Tests\TestCase;

class PaymentTests
{
    public static function getTests(): array
    {
        return [
            // 1. Core Production User Question: Client payments done for bookings, success, fail, fail reason
            new TestCase([
                'id' => 'PAY_USER_CORE_01',
                'domain' => 'payment',
                'style' => 'natural',
                'persona' => 'finance',
                'question' => 'how many client payments done for bookings ,how many success,fail ,what is the fail reason?',
                'expected_intent' => 'payments.booking_transactions',
                'expected_metric' => 'booking_payments_overview',
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as total_txns FROM transactions WHERE transaction_type = 'booking'");
                    $total = (int)$res->fetch_assoc()['total_txns'];

                    $succRes = $mysqli->query("SELECT COUNT(*) as succ FROM transactions WHERE transaction_type = 'booking' AND status IN ('COMPLETED', 'PAID', 'SUCCESS')");
                    $succ = (int)$succRes->fetch_assoc()['succ'];

                    $failRes = $mysqli->query("SELECT COUNT(*) as failed FROM transactions WHERE transaction_type = 'booking' AND status IN ('FAILED', 'CANCELLED', 'USER_DROPPED', 'FLAGGED', 'CREATED')");
                    $failed = (int)$failRes->fetch_assoc()['failed'];

                    $pendRes = $mysqli->query("SELECT COUNT(*) as pend FROM transactions WHERE transaction_type = 'booking' AND status IN ('PENDING', 'ACTIVE')");
                    $pend = (int)$pendRes->fetch_assoc()['pend'];

                    return [
                        'total_transactions' => $total,
                        'success'            => $succ,
                        'failed'             => $failed,
                        'pending'            => $pend,
                    ];
                },
                'validator' => function($data, $gt) {
                    $tot = $data['total_transactions'] ?? 0;
                    $succ = $data['status_tabs']['success']['count'] ?? 0;
                    $fail = $data['status_tabs']['failed']['count'] ?? 0;

                    if ($tot !== $gt['total_transactions']) {
                        return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Expected {$gt['total_transactions']} total txns, got {$tot}"];
                    }
                    if ($succ !== $gt['success']) {
                        return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Expected {$gt['success']} success txns, got {$succ}"];
                    }
                    if ($fail !== $gt['failed']) {
                        return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Expected {$gt['failed']} failed txns, got {$fail}"];
                    }
                    if (empty($data['failure_categories'])) {
                        return ['pass' => false, 'error_type' => 'METRIC_DEFINITION_ERROR', 'details' => 'Missing failure categories'];
                    }

                    return ['pass' => true];
                }
            ]),

            // 2. Simple: Payment transactions
            new TestCase([
                'id' => 'PAY_SIMPLE_01',
                'domain' => 'payment',
                'style' => 'simple',
                'persona' => 'admin',
                'question' => 'How many payment transactions for bookings?',
                'expected_intent' => 'payments.booking_transactions',
                'expected_metric' => 'booking_payments_overview',
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as cnt FROM transactions WHERE transaction_type = 'booking'");
                    return ['total_transactions' => (int)$res->fetch_assoc()['cnt']];
                },
                'validator' => function($data, $gt) {
                    $tot = $data['total_transactions'] ?? 0;
                    if ($tot === $gt['total_transactions']) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Expected {$gt['total_transactions']}, got {$tot}"];
                }
            ]),

            // 3. Short: Failed payments?
            new TestCase([
                'id' => 'PAY_SHORT_FAIL_01',
                'domain' => 'payment',
                'style' => 'short',
                'persona' => 'finance',
                'question' => 'Failed payments for bookings?',
                'expected_intent' => 'payments.booking_transactions',
                'expected_metric' => 'booking_payments_overview',
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as cnt FROM transactions WHERE transaction_type = 'booking' AND status IN ('FAILED', 'CANCELLED', 'USER_DROPPED', 'FLAGGED', 'CREATED')");
                    return ['failed_cnt' => (int)$res->fetch_assoc()['cnt']];
                },
                'validator' => function($data, $gt) {
                    $val = $data['status_tabs']['failed']['count'] ?? 0;
                    if ($val === $gt['failed_cnt']) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Expected {$gt['failed_cnt']} failed, got {$val}"];
                }
            ]),

            // 4. Financial / Root Cause: Why did booking payments fail?
            new TestCase([
                'id' => 'PAY_FAIL_REASONS_01',
                'domain' => 'payment',
                'style' => 'financial',
                'persona' => 'finance',
                'question' => 'What is the failure reason for booking payments?',
                'expected_intent' => 'payments.booking_transactions',
                'expected_metric' => 'booking_payments_overview',
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as cnt FROM transactions WHERE transaction_type = 'booking' AND response_code = 'TXN_AUTO_FAILED'");
                    return ['auto_failed' => (int)$res->fetch_assoc()['cnt']];
                },
                'validator' => function($data, $gt) {
                    $cat = $data['failure_categories']['Gateway Auto-Failed / Timeout']['count'] ?? 0;
                    if ($cat >= $gt['auto_failed']) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'METRIC_DEFINITION_ERROR', 'details' => "Expected at least {$gt['auto_failed']} auto-failed, got {$cat}"];
                }
            ]),
        ];
    }
}
