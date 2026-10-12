<?php

namespace Tests\Tracking;

use Tests\TestCase;

class TrackingTests
{
    public static function getTests(): array
    {
        return [
            // 1. Operational: Tracked walking sessions
            new TestCase([
                'id' => 'TRK_OPS_01',
                'domain' => 'operations',
                'style' => 'operational',
                'persona' => 'operations',
                'question' => 'How many walking sessions were tracked with GPS tracking?',
                'expected_intent' => ['operations.tracking_analytics', 'booking.tracking'],
                'expected_metric' => 'walking_tracking_analytics',
                'ground_truth' => function($mysqli) {
                    $cnt = (int)$mysqli->query("SELECT COUNT(*) FROM walking_tracking WHERE is_untracked = 0 OR is_untracked IS NULL")->fetch_row()[0];
                    return ['tracked_walks' => $cnt];
                },
                'validator' => function($data, $gt) {
                    $val = $data['total_tracked_walks'] ?? 0;
                    if ($val > 0) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Expected tracked walks > 0, got {$val}"];
                }
            ]),

            // 2. Natural: Live tracking statistics
            new TestCase([
                'id' => 'TRK_NATURAL_01',
                'domain' => 'operations',
                'style' => 'natural',
                'persona' => 'operations',
                'question' => 'What are our live tracking statistics for dog walks?',
                'expected_intent' => 'operations.tracking_analytics',
                'expected_metric' => 'walking_tracking_analytics',
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as total, SUM(CASE WHEN is_untracked = 1 THEN 1 ELSE 0 END) as untracked FROM walking_tracking");
                    $r = $res->fetch_assoc();
                    return ['total' => (int)$r['total'], 'untracked' => (int)$r['untracked']];
                },
                'validator' => function($data, $gt) {
                    if (isset($data['total_tracked_walks']) || isset($data['completed_walks_count'])) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => 'Failed to resolve tracking analytics'];
                }
            ]),

            // 3. Formal: Detailed tracking analytics
            new TestCase([
                'id' => 'TRK_FORMAL_01',
                'domain' => 'operations',
                'style' => 'formal',
                'persona' => 'ceo',
                'question' => 'Provide a detailed overview of dog walking tracking analytics.',
                'expected_intent' => 'operations.tracking_analytics',
                'expected_metric' => 'walking_tracking_analytics',
                'ground_truth' => function($mysqli) {
                    return ['pass' => true];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => !empty($data['metric'])];
                }
            ]),
        ];
    }
}
