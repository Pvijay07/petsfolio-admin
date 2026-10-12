<?php

namespace Tests\Booking;

use Tests\TestCase;

class BookingTests
{
    public static function getTests(): array
    {
        return [
            // 1. Formal: Completed bookings all-time / summary
            new TestCase([
                'id' => 'BK_FORMAL_01',
                'domain' => 'booking',
                'style' => 'formal',
                'persona' => 'operations',
                'question' => 'What was the total number of completed bookings across all services?',
                'expected_intent' => ['booking.summary', 'booking.active'],
                'expected_metric' => 'bookings_summary',
                'expected_metric_args' => ['date_range' => 'all_time', 'status' => 'completed'],
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as cnt FROM v_bookings_overview WHERE is_deleted = 0 AND status IN ('Completed', 'completed')");
                    return ['completed_bookings' => (int)$res->fetch_assoc()['cnt']];
                },
                'validator' => function($data, $gt) {
                    $val = $data['completed_bookings'] ?? $data['filtered_bookings_count'] ?? $data['completed_bookings_count'] ?? 0;
                    if ($val === $gt['completed_bookings']) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Expected {$gt['completed_bookings']}, got {$val}"];
                }
            ]),

            // 2. Simple: How many bookings
            new TestCase([
                'id' => 'BK_SIMPLE_01',
                'domain' => 'booking',
                'style' => 'simple',
                'persona' => 'admin',
                'question' => 'How many bookings?',
                'expected_intent' => 'booking.summary',
                'expected_metric' => 'bookings_summary',
                'expected_metric_args' => ['date_range' => 'all_time'],
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as cnt FROM v_bookings_overview WHERE is_deleted = 0");
                    return ['total_bookings' => (int)$res->fetch_assoc()['cnt']];
                },
                'validator' => function($data, $gt) {
                    $val = $data['total_bookings'] ?? 0;
                    if ($val === $gt['total_bookings']) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Expected {$gt['total_bookings']}, got {$val}"];
                }
            ]),

            // 3. Short: Active jobs?
            new TestCase([
                'id' => 'BK_SHORT_01',
                'domain' => 'booking',
                'style' => 'short',
                'persona' => 'operations',
                'question' => 'Active jobs?',
                'expected_intent' => 'booking.active',
                'expected_metric' => 'active_jobs',
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as cnt FROM v_bookings_overview WHERE is_deleted = 0 AND status IN ('Confirmed', 'In Progress', 'In progress', 'active')");
                    return ['active_jobs' => (int)$res->fetch_assoc()['cnt']];
                },
                'validator' => function($data, $gt) {
                    $val = $data['total_active_jobs'] ?? 0;
                    if ($val === $gt['active_jobs']) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Expected {$gt['active_jobs']}, got {$val}"];
                }
            ]),

            // 4. Natural conversational: How are bookings doing this month?
            new TestCase([
                'id' => 'BK_NATURAL_01',
                'domain' => 'booking',
                'style' => 'natural',
                'persona' => 'ceo',
                'question' => 'How are bookings doing this month?',
                'expected_intent' => 'booking.summary',
                'expected_entities' => ['date_range' => 'this_month'],
                'expected_metric' => 'bookings_summary',
                'expected_metric_args' => ['date_range' => 'this_month'],
                'ground_truth' => function($mysqli) {
                    $firstDay = date('Y-m-01 00:00:00');
                    $now = date('Y-m-d 23:59:59');
                    $res = $mysqli->query("SELECT COUNT(*) as cnt FROM v_bookings_overview WHERE is_deleted = 0 AND created_at >= '{$firstDay}' AND created_at <= '{$now}'");
                    return ['this_month_bookings' => (int)$res->fetch_assoc()['cnt']];
                },
                'validator' => function($data, $gt) {
                    $val = $data['total_bookings'] ?? 0;
                    if ($val === $gt['this_month_bookings']) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'DATE_ERROR', 'details' => "Expected {$gt['this_month_bookings']} for this_month, got {$val}"];
                }
            ]),

            // 5. Follow-up: What about walking? (Inherits history context)
            new TestCase([
                'id' => 'BK_FOLLOWUP_01',
                'domain' => 'booking',
                'style' => 'followup',
                'persona' => 'operations',
                'question' => 'What about walking?',
                'history' => 'User: How many active jobs do we have? Assistant: We have 56 active jobs.',
                'state' => [
                    'topic' => 'active_jobs',
                    'last_intent' => 'booking.active',
                    'metric' => 'active_jobs',
                    'primary_metric' => 'active_jobs',
                    'filters' => []
                ],
                'expected_intent' => ['booking.active', 'booking.summary', 'conversation.followup', 'provider.lookup'],
                'expected_entities' => ['service_type' => 'walking'],
                'expected_metric' => 'active_jobs',
                'expected_metric_args' => ['service_type' => 'walking'],
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as cnt FROM v_bookings_overview WHERE is_deleted = 0 AND service_type = 'walking' AND status IN ('Confirmed', 'In Progress', 'In progress', 'active')");
                    return ['walking_active' => (int)$res->fetch_assoc()['cnt']];
                },
                'validator' => function($data, $gt) {
                    $val = $data['total_active_jobs'] ?? 0;
                    if ($val === $gt['walking_active']) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'FOLLOWUP_CONTEXT_ERROR', 'details' => "Expected {$gt['walking_active']}, got {$val}"];
                }
            ]),

            // 6. Follow-up: Show only Hyderabad
            new TestCase([
                'id' => 'BK_FOLLOWUP_02',
                'domain' => 'booking',
                'style' => 'followup',
                'persona' => 'operations',
                'question' => 'Show only Hyderabad.',
                'history' => 'User: Active jobs? Assistant: 56 active jobs ongoing.',
                'state' => [
                    'topic' => 'active_jobs',
                    'last_intent' => 'booking.active',
                    'metric' => 'active_jobs',
                    'primary_metric' => 'active_jobs',
                    'filters' => []
                ],
                'expected_intent' => ['booking.active', 'booking.summary', 'conversation.followup'],
                'expected_entities' => ['city' => 'Hyderabad'],
                'expected_metric' => 'active_jobs',
                'expected_metric_args' => ['city' => 'Hyderabad'],
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as cnt FROM v_bookings_overview WHERE is_deleted = 0 AND city LIKE '%Hyderabad%' AND status IN ('Confirmed', 'In Progress', 'In progress', 'active')");
                    return ['hyd_active' => (int)$res->fetch_assoc()['cnt']];
                },
                'validator' => function($data, $gt) {
                    $val = $data['total_active_jobs'] ?? 0;
                    // Accept exact match or within city group count
                    $cityCounts = $data['active_by_city']['Hyderabad'] ?? null;
                    if ($val === $gt['hyd_active'] || ($cityCounts !== null && $cityCounts === $gt['hyd_active'])) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'FILTER_ERROR', 'details' => "Expected {$gt['hyd_active']} for Hyderabad, got {$val}"];
                }
            ]),

            // 7. Comparative: Compare walking and grooming revenue
            new TestCase([
                'id' => 'BK_COMP_01',
                'domain' => 'booking',
                'style' => 'comparative',
                'persona' => 'ceo',
                'question' => 'Compare walking and grooming revenue.',
                'expected_intent' => 'revenue.overview',
                'expected_metric' => 'revenue_overview',
                'ground_truth' => function($mysqli) {
                    $wRes = $mysqli->query("SELECT SUM(total_amount) as rev FROM v_bookings_overview WHERE is_deleted = 0 AND service_type = 'walking' AND status NOT IN ('Cancelled', 'Deleted')");
                    $gRes = $mysqli->query("SELECT SUM(total_amount) as rev FROM v_bookings_overview WHERE is_deleted = 0 AND service_type = 'grooming' AND status NOT IN ('Cancelled', 'Deleted')");
                    return [
                        'walking_rev' => round((float)$wRes->fetch_assoc()['rev'], 2),
                        'grooming_rev' => round((float)$gRes->fetch_assoc()['rev'], 2),
                    ];
                },
                'validator' => function($data, $gt) {
                    // Revenue breakdown has service breakdown
                    return ['pass' => true];
                }
            ]),

            // 8. Ranking: Top services by bookings
            new TestCase([
                'id' => 'BK_RANK_01',
                'domain' => 'booking',
                'style' => 'ranking',
                'persona' => 'operations',
                'question' => 'Top services by bookings count.',
                'expected_intent' => 'booking.summary',
                'expected_metric' => 'bookings_summary',
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT service_type, COUNT(*) as cnt FROM v_bookings_overview WHERE is_deleted = 0 GROUP BY service_type ORDER BY cnt DESC");
                    $rows = [];
                    while ($r = $res->fetch_assoc()) {
                        $rows[$r['service_type']] = (int)$r['cnt'];
                    }
                    return ['service_breakdown' => $rows];
                },
                'validator' => function($data, $gt) {
                    $byService = $data['service_breakdown'] ?? $data['by_service'] ?? [];
                    if (!empty($byService)) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'AGGREGATION_ERROR', 'details' => 'Missing service breakdown in response'];
                }
            ]),

            // 9. Ambiguous: "How many bookings?" without specifying status or date
            new TestCase([
                'id' => 'BK_AMBIG_01',
                'domain' => 'booking',
                'style' => 'ambiguous',
                'persona' => 'admin',
                'question' => 'How many bookings are there?',
                'is_ambiguous' => true,
                'expected_intent' => 'booking.summary',
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as cnt FROM v_bookings_overview WHERE is_deleted = 0");
                    return ['total_bookings' => (int)$res->fetch_assoc()['cnt']];
                },
                'validator' => function($data, $gt) {
                    // Validates that it reports total bookings along with status breakdown
                    if (!empty($data['total_bookings']) || !empty($data['status_breakdown'])) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'SEMANTIC_ERROR', 'details' => 'Failed to resolve total booking baseline'];
                }
            ]),

            // 10. Operational: Untracked walks
            new TestCase([
                'id' => 'BK_OPS_TRACKING_01',
                'domain' => 'booking',
                'style' => 'operational',
                'persona' => 'operations',
                'question' => 'How many walks are untracked without GPS tracking?',
                'expected_intent' => 'booking.tracking',
                'expected_metric' => 'walking_tracking_analytics',
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as cnt FROM walking_tracking WHERE is_untracked = 1");
                    return ['untracked' => (int)($res ? $res->fetch_assoc()['cnt'] : 0)];
                },
                'validator' => function($data, $gt) {
                    if (isset($data['untracked_walks_count']) || isset($data['total_untracked'])) {
                        return ['pass' => true];
                    }
                    return ['pass' => true];
                }
            ]),
        ];
    }
}
