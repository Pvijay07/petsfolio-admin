<?php

namespace Tests\Feedback;

use Tests\TestCase;

class FeedbackTests
{
    public static function getTests(): array
    {
        return [
            // 1. Formal: Customer satisfaction and average rating
            new TestCase([
                'id' => 'FB_FORMAL_01',
                'domain' => 'feedback',
                'style' => 'formal',
                'persona' => 'operations',
                'question' => 'What is our overall customer satisfaction and rating across bookings?',
                'expected_intent' => 'feedback.reviews',
                'expected_metric' => 'feedback_and_reviews_overview',
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as cnt, AVG(rating) as avg_r FROM feedbacks");
                    $r = $res->fetch_assoc();
                    return ['total_feedbacks' => (int)$r['cnt'], 'avg_rating' => round((float)$r['avg_r'], 2)];
                },
                'validator' => function($data, $gt) {
                    $cnt = $data['total_feedbacks_count'] ?? 0;
                    if ($cnt === $gt['total_feedbacks']) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Expected {$gt['total_feedbacks']} feedbacks, got {$cnt}"];
                }
            ]),

            // 2. Simple: How are customer reviews?
            new TestCase([
                'id' => 'FB_SIMPLE_01',
                'domain' => 'feedback',
                'style' => 'simple',
                'persona' => 'ceo',
                'question' => 'How are customer reviews?',
                'expected_intent' => 'feedback.reviews',
                'expected_metric' => 'feedback_and_reviews_overview',
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) FROM feedbacks");
                    return ['cnt' => (int)$res->fetch_row()[0]];
                },
                'validator' => function($data, $gt) {
                    if (isset($data['average_rating']) || isset($data['total_feedbacks_count'])) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => 'Failed to resolve feedback reviews'];
                }
            ]),

            // 3. Natural: Customer ratings and feedback summary
            new TestCase([
                'id' => 'FB_NATURAL_01',
                'domain' => 'feedback',
                'style' => 'natural',
                'persona' => 'admin',
                'question' => 'Show customer ratings and feedback summary.',
                'expected_intent' => 'feedback.reviews',
                'expected_metric' => 'feedback_and_reviews_overview',
                'ground_truth' => function($mysqli) {
                    return ['pass' => true];
                },
                'validator' => function($data, $gt) {
                    if (!empty($data['rating_distribution']) || isset($data['average_rating'])) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => 'No rating distribution returned'];
                }
            ]),
        ];
    }
}
