<?php

namespace App\Libraries\AIAnalyst\IntentEngine;

/**
 * IntentResolver
 * 
 * Stage 4 of the Intent Engine pipeline.
 * Resolves ambiguity and applies business rules to finalize the intent:
 * 
 *   1. If classification is unambiguous → pass through
 *   2. If ambiguous → apply domain-priority rules and context from entities/history
 *   3. Handle conversational follow-ups by resolving from history context
 *   4. Apply reconciliation context routing (provider vs customer)
 */
class IntentResolver
{
    /**
     * Resolve the classified result into a final confirmed intent.
     * 
     * @param array  $classification  Output from IntentClassifier::classify()
     * @param array  $entities        Extracted entities
     * @param string $historyNorm     Normalized history text
     * @return array  ['intent_id' => string|null, 'resolved' => bool, 'reason' => string, 'entities' => array]
     */
    public static function resolve(array $classification, array $entities, string $historyNorm = ''): array
    {
        $top = $classification['top'] ?? null;

        // No candidates at all
        if ($top === null) {
            return [
                'intent_id' => null,
                'resolved'  => false,
                'reason'    => 'no_matching_intent',
                'entities'  => $entities,
            ];
        }

        $intentId   = $top['intent_id'];
        $confidence = $classification['confidence'];
        $isAmbiguous = $classification['is_ambiguous'];

        // ── Handle follow-up resolution ──
        if ($intentId === 'conversation.followup') {
            return self::resolveFollowup($entities, $historyNorm);
        }

        // ── Handle reconciliation context routing ──
        if ($intentId === 'provider.reconciliation') {
            $role = $entities['role_context'] ?? 'ambiguous';
            if ($role === 'customer') {
                // Re-route to customer reconciliation
                return [
                    'intent_id' => 'customer.reconciliation',
                    'resolved'  => true,
                    'reason'    => 'context_rerouted_to_customer',
                    'entities'  => $entities,
                ];
            }
        }

        // ── Unambiguous case ──
        if (!$isAmbiguous) {
            return [
                'intent_id' => $intentId,
                'resolved'  => true,
                'reason'    => "classified_with_confidence_{$confidence}",
                'entities'  => $entities,
            ];
        }

        // ── Ambiguous case: try to break tie ──
        $candidates = $classification['candidates'];

        // Rule 1: If top two candidates are in different domains, 
        // and one domain has entity support, prefer that one
        if (count($candidates) >= 2) {
            $first  = $candidates[0];
            $second = $candidates[1];

            // Entity support check
            $firstEntityMatch  = self::countEntityMatches($first['intent_id'], $entities);
            $secondEntityMatch = self::countEntityMatches($second['intent_id'], $entities);

            if ($firstEntityMatch > $secondEntityMatch) {
                return [
                    'intent_id' => $first['intent_id'],
                    'resolved'  => true,
                    'reason'    => 'tie_broken_by_entity_support',
                    'entities'  => $entities,
                ];
            }
            if ($secondEntityMatch > $firstEntityMatch) {
                return [
                    'intent_id' => $second['intent_id'],
                    'resolved'  => true,
                    'reason'    => 'tie_broken_by_entity_support',
                    'entities'  => $entities,
                ];
            }

            // Rule 2: Prefer the one with lower priority number (higher business priority)
            if ($first['priority'] !== $second['priority']) {
                return [
                    'intent_id' => $first['intent_id'],
                    'resolved'  => true,
                    'reason'    => 'tie_broken_by_priority',
                    'entities'  => $entities,
                ];
            }
        }

        // Still ambiguous — accept the top candidate with a warning
        return [
            'intent_id' => $intentId,
            'resolved'  => true,
            'reason'    => 'accepted_top_candidate_despite_ambiguity',
            'entities'  => $entities,
        ];
    }

    /**
     * Resolve a conversational follow-up by inspecting history context.
     */
    private static function resolveFollowup(array $entities, string $historyNorm): array
    {
        if (empty($historyNorm)) {
            return [
                'intent_id' => null,
                'resolved'  => false,
                'reason'    => 'followup_no_history',
                'entities'  => $entities,
            ];
        }

        // Detect the topic from history
        if (preg_match('/\b(?:bookings?|jobs?|service\s*requests?|orders?)\b/i', $historyNorm)) {
            return [
                'intent_id' => 'booking.followup',
                'resolved'  => true,
                'reason'    => 'followup_resolved_from_history_bookings',
                'entities'  => $entities,
                'followup_metrics' => [
                    ['name' => 'active_jobs', 'args' => []],
                    ['name' => 'bookings_summary', 'args' => ['date_range' => 'all_time']],
                ],
            ];
        }

        if (preg_match('/\b(?:customers?|clients?|pet\s*parents?)\b/i', $historyNorm)) {
            return [
                'intent_id' => 'customer.followup',
                'resolved'  => true,
                'reason'    => 'followup_resolved_from_history_customers',
                'entities'  => $entities,
                'followup_metrics' => [
                    ['name' => 'active_customers', 'args' => ['date_range' => 'all_time', 'sort_by' => 'bookings']],
                ],
            ];
        }

        if (preg_match('/\b(?:providers?|sps?|walkers?|groomers?|trainers?)\b/i', $historyNorm)) {
            return [
                'intent_id' => 'provider.followup',
                'resolved'  => true,
                'reason'    => 'followup_resolved_from_history_providers',
                'entities'  => $entities,
                'followup_metrics' => [
                    ['name' => 'provider_stats', 'args' => ['date_range' => 'all_time']],
                ],
            ];
        }

        return [
            'intent_id' => null,
            'resolved'  => false,
            'reason'    => 'followup_unresolvable_topic',
            'entities'  => $entities,
        ];
    }

    /**
     * Count how many of an intent's optional entities are actually present.
     * Higher count = better entity support = more likely the correct intent.
     */
    private static function countEntityMatches(string $intentId, array $entities): int
    {
        $def = IntentRegistry::getIntent($intentId);
        if ($def === null) return 0;

        $optionalEntities = $def['optional_entities'] ?? [];
        $count = 0;
        foreach ($optionalEntities as $entityType) {
            if (!empty($entities[$entityType])) {
                $count++;
            }
        }
        return $count;
    }
}
