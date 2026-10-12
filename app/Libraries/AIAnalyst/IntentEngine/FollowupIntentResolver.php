<?php

namespace App\Libraries\AIAnalyst\IntentEngine;

/**
 * FollowupIntentResolver
 * 
 * NEXT 4 — Follow-up Intent Resolver:
 * Enables ChatGPT-like conversational flow by interpreting ambiguous or incomplete
 * follow-up queries ("how to solve this issue?", "which walkers?", "show Hyderabad only")
 * relative to the active analytical conversation state.
 * 
 * Ensures the system NEVER resets the conversation when an admin asks for next steps.
 */
class FollowupIntentResolver
{
    /**
     * Check if a user message is a conversational follow-up and resolve it against active state.
     * 
     * @param string     $userMessage
     * @param array|null $state Active state from ConversationStateManager
     * @param array      $extractedEntities
     * @return array|null Resolved follow-up package or null if message is a fresh standalone intent
     */
    public static function resolve(string $userMessage, ?array $state, array $extractedEntities = []): ?array
    {
        if (empty($state)) {
            return null;
        }

        $msgLower = strtolower(trim($userMessage));
        $topic    = $state['topic'] ?? 'business_performance';

        // ── 1. ROOT CAUSE SOLUTION / RECOMMENDATION FOLLOW-UP ──
        // e.g. "how to solve this issue?", "how to fix this?", "how to reduce cancellations?", "recommendations"
        $isSolutionQuery = (bool) preg_match(
            '/\b(?:how\s*(?:to|can\s*we|do\s*we)\s*(?:solve|fix|resolve|reduce|address|improve|prevent|tackle|handle)\s*(?:this|it|the\s*issue|the\s*problem|these)?|'
            . 'what\s*(?:can\s*we\s*do|is\s*the\s*solution|are\s*the\s*solutions|should\s*we\s*do|action\s*plan)|'
            . 'give\s*me\s*solutions?|recommendations?|actionable\s*steps?|next\s*steps?|how\s*to\s*avoid\s*this)\b/i',
            $msgLower
        );

        if ($isSolutionQuery) {
            $intentId = match ($topic) {
                'booking_cancellations'        => 'cancellation.root_cause_solution',
                'provider_onboarding_pipeline' => 'pipeline.bottleneck_solution',
                'conversion_funnel'            => 'funnel.dropoff_solution',
                default                        => 'analytical.root_cause_solution'
            };

            return [
                'is_followup'       => true,
                'intent_id'         => $intentId,
                'parent_intent'     => $state['last_intent'] ?? 'cancellation_analysis',
                'topic'             => $topic,
                'action'            => 'recommend_solutions',
                'context_inherited' => true,
                'inherited_state'   => $state,
                'entities'          => array_merge($state['filters'] ?? [], $extractedEntities),
                'metrics'           => [] // Solutions are synthesized from existing state metrics + reasoning
            ];
        }

        // ── 2. ROOT CAUSE INQUIRY FOLLOW-UP ──
        // e.g. "why is this happening?", "why?", "what is the reason for this?"
        $isWhyQuery = (bool) preg_match(
            '/\b(?:why\s*(?:is\s*this|is\s*that|so\s*many|are\s*they|did\s*this\s*happen)?|what\s*(?:is\s*the\s*reason|are\s*the\s*causes?|is\s*driving\s*this)|root\s*cause)\b/i',
            $msgLower
        );

        if ($isWhyQuery) {
            return [
                'is_followup'       => true,
                'intent_id'         => 'cancellation.root_cause_analysis',
                'parent_intent'     => $state['last_intent'] ?? 'cancellation_analysis',
                'topic'             => $topic,
                'action'            => 'deep_dive_reasons',
                'context_inherited' => true,
                'inherited_state'   => $state,
                'entities'          => array_merge($state['filters'] ?? [], $extractedEntities),
                'metrics'           => [
                    ['name' => 'dropoff_reasons_analysis', 'args' => array_merge($state['filters'] ?? [], ['service_type' => $state['service'] ?? null])]
                ]
            ];
        }

        // ── 3. PROVIDER / ENTITY DRILLDOWN FOLLOW-UP ──
        // e.g. "which walkers?", "which providers?", "who are they?", "who cancelled most?"
        $isDrilldownQuery = (bool) preg_match(
            '/\b(?:which\s*(?:walkers?|groomers?|trainers?|providers?|sps?|users?|customers?)|who\s*(?:are\s*they|is\s*cancelling|cancelled\s*most))\b/i',
            $msgLower
        );

        if ($isDrilldownQuery) {
            $drilldownService = $state['service'] ?? null;
            if (preg_match('/walker/i', $msgLower))   $drilldownService = 'walking';
            if (preg_match('/groomer/i', $msgLower))  $drilldownService = 'grooming';
            if (preg_match('/trainer/i', $msgLower))  $drilldownService = 'training';

            return [
                'is_followup'       => true,
                'intent_id'         => 'provider.cancellation_drilldown',
                'parent_intent'     => $state['last_intent'] ?? 'cancellation_analysis',
                'topic'             => $topic,
                'action'            => 'entity_drilldown',
                'context_inherited' => true,
                'inherited_state'   => $state,
                'entities'          => array_merge($state['filters'] ?? [], ['service' => $drilldownService]),
                'metrics'           => [
                    [
                        'name' => 'semantic_query_compiler',
                        'args' => [
                            'metric'     => 'cancelled_bookings',
                            'dimensions' => ['city'],
                            'filters'    => ['service_type' => $drilldownService, 'status' => 'cancelled'],
                            'date_range' => $state['date_range'] ?? '30_days'
                        ]
                    ]
                ]
            ];
        }

        // ── 4. FILTER MUTATION / SLICING FOLLOW-UP ──
        // e.g. "show Hyderabad only", "what about Bangalore?", "for grooming only?", "what about last month?"
        $hasCityChange    = !empty($extractedEntities['city']);
        $hasServiceChange = !empty($extractedEntities['service_type']) || !empty($extractedEntities['service']);
        $hasDateChange    = !empty($extractedEntities['date_range']);

        $isFilterSliceOnly = (bool) preg_match(
            '/^(?:show\s+)?(?:only\s+)?(?:for\s+)?(?:in\s+)?(?:what\s*about\s+)?(?:just\s+)?(?:hyderabad|bangalore|bengaluru|mumbai|delhi|walking|grooming|training|boarding|sitting|this\s*month|last\s*month|yesterday|today)\b/i',
            $msgLower
        );

        if ($isFilterSliceOnly && ($hasCityChange || $hasServiceChange || $hasDateChange)) {
            $newFilters = array_merge($state['filters'] ?? [], array_filter([
                'city'         => $extractedEntities['city'] ?? $state['city'] ?? null,
                'service_type' => $extractedEntities['service_type'] ?? $extractedEntities['service'] ?? $state['service_type'] ?? $state['service'] ?? null,
                'date_range'   => $extractedEntities['date_range'] ?? $state['date_range'] ?? '30_days'
            ]));

            $metricName = $state['primary_metric'] ?? $state['metric'] ?? 'bookings_summary';
            if ($metricName === 'cancelled_bookings') {
                $metricName = 'cancellation_rate';
            }

            return [
                'is_followup'       => true,
                'intent_id'         => $state['last_intent'] ?? 'metric_slice',
                'parent_intent'     => $state['last_intent'] ?? 'metric_analysis',
                'topic'             => $topic,
                'action'            => 'filter_mutation',
                'context_inherited' => true,
                'inherited_state'   => $state,
                'entities'          => $newFilters,
                'metrics'           => [
                    [
                        'name' => $metricName,
                        'args' => $newFilters
                    ]
                ]
            ];
        }

        return null;
    }
}
