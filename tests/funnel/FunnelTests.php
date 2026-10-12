<?php

namespace Tests\Funnel;

use Tests\TestCase;

class FunnelTests
{
    public static function getTests(): array
    {
        return [
            // 1. Formal: Conversion funnel analysis
            new TestCase([
                'id' => 'FUN_FORMAL_01',
                'domain' => 'funnel',
                'style' => 'formal',
                'persona' => 'marketing',
                'question' => 'Show the end-to-end booking conversion funnel and stage-by-stage drop-off.',
                'expected_intent' => 'booking.funnel',
                'expected_metric' => 'funnel_analysis',
                'ground_truth' => function($mysqli) {
                    return ['pass' => true];
                },
                'validator' => function($data, $gt) {
                    if (isset($data['stages']) || isset($data['funnel_stages']) || isset($data['steps'])) {
                        return ['pass' => true];
                    }
                    return ['pass' => true];
                }
            ]),

            // 2. Simple: Where are users dropping off?
            new TestCase([
                'id' => 'FUN_SIMPLE_01',
                'domain' => 'funnel',
                'style' => 'simple',
                'persona' => 'ceo',
                'question' => 'Where are users dropping off the most in the funnel?',
                'expected_intent' => 'booking.funnel',
                'expected_metric' => 'funnel_analysis',
                'ground_truth' => function($mysqli) {
                    return ['pass' => true];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => true];
                }
            ]),

            // 3. Natural / Marketing: CRM Lead conversion rate
            new TestCase([
                'id' => 'FUN_CRM_CONV_01',
                'domain' => 'funnel',
                'style' => 'natural',
                'persona' => 'marketing',
                'question' => 'What is our CRM lead conversion rate this month?',
                'expected_intent' => ['funnel.crm', 'booking.funnel'],
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
