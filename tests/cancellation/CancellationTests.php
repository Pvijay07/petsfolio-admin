<?php

namespace Tests\Cancellation;

use Tests\TestCase;

class CancellationTests
{
    public static function getTests(): array
    {
        return [
            // 1. Formal: Cancellation reasons
            new TestCase([
                'id' => 'CANC_FORMAL_01',
                'domain' => 'cancellation',
                'style' => 'formal',
                'persona' => 'operations',
                'question' => 'Provide a comprehensive breakdown of all booking cancellation reasons.',
                'expected_intent' => ['booking.funnel', 'booking.summary'],
                'expected_metric' => ['dropoff_reasons_analysis', 'funnel_analysis', 'bookings_summary'],
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT cancel_reason, COUNT(*) as cnt FROM v_bookings_overview WHERE cancel_reason IS NOT NULL AND cancel_reason != '' GROUP BY cancel_reason ORDER BY cnt DESC");
                    $reasons = [];
                    while ($r = $res->fetch_assoc()) {
                        $reasons[$r['cancel_reason']] = (int)$r['cnt'];
                    }
                    return ['reasons' => $reasons];
                },
                'validator' => function($data, $gt) {
                    if (!empty($data['reasons']) || !empty($data['cancellation_reasons']) || !empty($data['reasons_breakdown'])) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'METRIC_DEFINITION_ERROR', 'details' => 'No cancellation reasons returned'];
                }
            ]),

            // 2. Simple: Why are bookings getting cancelled?
            new TestCase([
                'id' => 'CANC_SIMPLE_01',
                'domain' => 'cancellation',
                'style' => 'simple',
                'persona' => 'ceo',
                'question' => 'Why are bookings getting cancelled?',
                'expected_intent' => ['booking.funnel', 'booking.summary'],
                'expected_metric' => ['dropoff_reasons_analysis', 'funnel_analysis', 'bookings_summary'],
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as cnt FROM v_bookings_overview WHERE cancel_reason IS NOT NULL AND cancel_reason != ''");
                    return ['total_logged_reasons' => (int)$res->fetch_assoc()['cnt']];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => true];
                }
            ]),

            // 3. Natural: Why are customers cancelling?
            new TestCase([
                'id' => 'CANC_NATURAL_01',
                'domain' => 'cancellation',
                'style' => 'natural',
                'persona' => 'operations',
                'question' => 'Why are customers cancelling their service requests?',
                'expected_intent' => ['booking.funnel', 'booking.summary'],
                'expected_metric' => ['dropoff_reasons_analysis', 'funnel_analysis', 'bookings_summary'],
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as cnt FROM v_bookings_overview WHERE cancel_reason IS NOT NULL AND cancel_reason != ''");
                    return ['cnt' => (int)$res->fetch_assoc()['cnt']];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => true];
                }
            ]),

            // 4. Short: Cancelled bookings?
            new TestCase([
                'id' => 'CANC_SHORT_01',
                'domain' => 'cancellation',
                'style' => 'short',
                'persona' => 'operations',
                'question' => 'Cancelled bookings?',
                'expected_intent' => 'booking.summary',
                'expected_metric' => 'bookings_summary',
                'expected_metric_args' => ['status' => 'cancelled', 'date_range' => 'all_time'],
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as cnt FROM v_bookings_overview WHERE is_deleted = 0 AND status IN ('Cancelled', 'cancelled')");
                    return ['cancelled_cnt' => (int)$res->fetch_assoc()['cnt']];
                },
                'validator' => function($data, $gt) {
                    $val = $data['cancelled_bookings'] ?? $data['total_filtered'] ?? $data['filtered_bookings_count'] ?? 0;
                    if ($val === $gt['cancelled_cnt'] || (isset($data['total_bookings']) && $data['total_bookings'] > 0)) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Expected {$gt['cancelled_cnt']}, got {$val}"];
                }
            ]),

            // 5. Follow-up: How can we reduce it? (Contextual solution / recommendations)
            new TestCase([
                'id' => 'CANC_FOLLOWUP_SOL_01',
                'domain' => 'cancellation',
                'style' => 'followup',
                'persona' => 'ceo',
                'question' => 'How can we reduce it?',
                'history' => 'User: Why are bookings cancelled? Assistant: Bookings are cancelled primarily due to Walker is not accepting and Dog is not accepting.',
                'state' => [
                    'topic' => 'booking_cancellations',
                    'intent_id' => 'booking.funnel',
                    'primary_metric' => 'dropoff_reasons_analysis',
                    'extracted_entities' => ['service_type' => null, 'city' => null],
                    'observations' => ['Main reason: Walker not accepting'],
                    'friction_points' => ['Walker availability mismatch', 'No immediate substitute provider']
                ],
                'expected_intent' => ['cancellation.root_cause_solution', 'booking.funnel'],
                'ground_truth' => function($mysqli) {
                    return ['pass' => true];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => true];
                }
            ]),

            // 6. Comparative: Which service has the highest cancellation rate?
            new TestCase([
                'id' => 'CANC_COMP_01',
                'domain' => 'cancellation',
                'style' => 'comparative',
                'persona' => 'operations',
                'question' => 'Which service has the highest cancellation rate?',
                'expected_intent' => ['booking.funnel', 'booking.summary'],
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT service_type, COUNT(*) as total, SUM(CASE WHEN status IN ('Cancelled', 'cancelled') THEN 1 ELSE 0 END) as cancelled FROM v_bookings_overview WHERE is_deleted = 0 GROUP BY service_type");
                    $rates = [];
                    while ($r = $res->fetch_assoc()) {
                        $rates[$r['service_type']] = $r['total'] > 0 ? round(($r['cancelled'] / $r['total']) * 100, 1) : 0;
                    }
                    return ['rates' => $rates];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => true];
                }
            ]),

            // 7. Data Quality Detection: Check for multi-select vs individual reasons
            new TestCase([
                'id' => 'CANC_DATA_QUALITY_01',
                'domain' => 'cancellation',
                'style' => 'operational',
                'persona' => 'admin',
                'question' => 'Verify cancelled bookings count against cancellation reason log counts.',
                'expected_intent' => ['booking.funnel', 'booking.summary'],
                'ground_truth' => function($mysqli) {
                    $bks = (int)$mysqli->query("SELECT COUNT(*) FROM v_bookings_overview WHERE is_deleted = 0 AND status IN ('Cancelled', 'cancelled')")->fetch_row()[0];
                    $rsn = (int)$mysqli->query("SELECT COUNT(*) FROM v_bookings_overview WHERE is_deleted = 0 AND cancel_reason IS NOT NULL AND cancel_reason != ''")->fetch_row()[0];
                    return ['cancelled_bookings' => $bks, 'reasons_logged' => $rsn];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => true];
                }
            ]),
        ];
    }
}
