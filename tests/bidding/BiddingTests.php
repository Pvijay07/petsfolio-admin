<?php

namespace Tests\Bidding;

use Tests\TestCase;

class BiddingTests
{
    public static function getTests(): array
    {
        return [
            // 1. Operational: Bid conversion rate
            new TestCase([
                'id' => 'BID_CONV_01',
                'domain' => 'bidding',
                'style' => 'operational',
                'persona' => 'operations',
                'question' => 'What is the provider bid conversion rate on booking requests?',
                'expected_intent' => 'operations.bidding',
                'expected_metric' => 'bids_conversion_analytics',
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) FROM quotations");
                    return ['total_bids' => (int)$res->fetch_row()[0]];
                },
                'validator' => function($data, $gt) {
                    if (isset($data['total_booking_requests']) || isset($data['total_bids_submitted']) || isset($data['bid_conversion_rate_percent'])) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => 'Failed to resolve bid conversion analytics'];
                }
            ]),

            // 2. Natural: Bids submitted
            new TestCase([
                'id' => 'BID_SUBMITTED_01',
                'domain' => 'bidding',
                'style' => 'natural',
                'persona' => 'operations',
                'question' => 'How many bids are submitted by service providers for bookings?',
                'expected_intent' => 'operations.bidding',
                'expected_metric' => 'bids_conversion_analytics',
                'ground_truth' => function($mysqli) {
                    return ['pass' => true];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => !empty($data['metric'])];
                }
            ]),

            // 3. Business: Bidding behavior analysis
            new TestCase([
                'id' => 'BID_BIZ_01',
                'domain' => 'bidding',
                'style' => 'business',
                'persona' => 'ceo',
                'question' => 'Analyze provider bidding behavior and conversion performance.',
                'expected_intent' => 'operations.bidding',
                'expected_metric' => 'bids_conversion_analytics',
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
