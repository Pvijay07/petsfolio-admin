<?php

namespace App\Libraries\AIAnalyst\IntentEngine;

/**
 * IntentClassifier
 * 
 * Stage 3 of the Intent Engine pipeline.
 * Classifies the normalized query into one or more candidate intents using
 * weighted pattern matching against the IntentRegistry.
 * 
 * Scoring algorithm:
 *   1. For each registered intent, sum weights of all matching patterns
 *   2. Apply exclusion filters (matching exclusion pattern → score = 0)
 *   3. Check required entities
 *   4. Apply requires_all_patterns constraint
 *   5. Rank candidates by score, break ties with priority
 *   6. Compute confidence = top_score / (top_score + second_score)
 */
class IntentClassifier
{
    /**
     * Minimum total score for an intent to be considered a candidate.
     */
    private const MIN_SCORE_THRESHOLD = 10;

    /**
     * Minimum confidence (ratio) to auto-accept the top intent without ambiguity warning.
     */
    private const CONFIDENCE_THRESHOLD = 0.6;

    /**
     * Classify a normalized query against all registered intents.
     * 
     * @param string $normalizedText  The normalized user message
     * @param array  $entities        Extracted entities from EntityExtractor
     * @param string $historyNorm     Normalized history text
     * @return array  [
     *   'candidates' => [['intent_id' => string, 'score' => int, 'matched_patterns' => int], ...],
     *   'top'        => ['intent_id' => string, 'score' => int] | null,
     *   'confidence' => float,
     *   'is_ambiguous' => bool,
     * ]
     */
    public static function classify(string $normalizedText, array $entities, string $historyNorm = ''): array
    {
        $allIntents = IntentRegistry::getIntents();
        $candidates = [];

        foreach ($allIntents as $intentId => $def) {
            $score = self::scoreIntent($intentId, $def, $normalizedText, $entities, $historyNorm);
            if ($score > 0) {
                $candidates[] = [
                    'intent_id' => $intentId,
                    'score'     => $score,
                    'priority'  => $def['priority'],
                    'domain'    => $def['domain'],
                ];
            }
        }

        // Sort: highest score first, then lowest priority number (higher priority)
        usort($candidates, function ($a, $b) {
            if ($b['score'] !== $a['score']) {
                return $b['score'] - $a['score'];
            }
            return $a['priority'] - $b['priority'];
        });

        // Compute confidence
        $top    = $candidates[0] ?? null;
        $second = $candidates[1] ?? null;
        $confidence = 1.0;

        if ($top !== null && $second !== null && ($top['score'] + $second['score']) > 0) {
            $confidence = $top['score'] / ($top['score'] + $second['score']);
        }

        $isAmbiguous = $top !== null && $confidence < self::CONFIDENCE_THRESHOLD;

        return [
            'candidates'  => $candidates,
            'top'         => $top,
            'confidence'  => round($confidence, 3),
            'is_ambiguous' => $isAmbiguous,
        ];
    }

    /**
     * Score a single intent against the normalized text and entities.
     */
    private static function scoreIntent(
        string $intentId,
        array $def,
        string $normalizedText,
        array $entities,
        string $historyNorm
    ): int {
        // Step 1: Check exclusion patterns — if any match, this intent is disqualified
        foreach ($def['exclusions'] ?? [] as $exclusionPattern) {
            if (preg_match($exclusionPattern, $normalizedText)) {
                return 0;
            }
        }

        // Step 2: Check required entities
        foreach ($def['required_entities'] ?? [] as $reqEntity) {
            if ($reqEntity === 'search_name') {
                // search_name can come from current or history
                if (empty($entities['search_name']) && empty($entities['search_name_from_history'])) {
                    return 0;
                }
            } elseif (empty($entities[$reqEntity])) {
                return 0;
            }
        }

        // Step 3: Check if history is required
        if (!empty($def['requires_history']) && empty($historyNorm)) {
            return 0;
        }

        // Step 4: Score patterns
        $totalScore = 0;
        $matchedCount = 0;
        $totalPatterns = count($def['patterns'] ?? []);

        foreach ($def['patterns'] ?? [] as $patternDef) {
            if (preg_match($patternDef['regex'], $normalizedText)) {
                $totalScore += $patternDef['weight'];
                $matchedCount++;
            }
        }

        // Step 5: Check requires_all_patterns constraint
        if (!empty($def['requires_all_patterns']) && $matchedCount < $totalPatterns) {
            return 0;
        }

        // Step 6: Apply minimum threshold
        if ($totalScore < self::MIN_SCORE_THRESHOLD) {
            return 0;
        }

        // Step 7: Guard conditions
        if (!empty($def['guard'])) {
            if ($def['guard'] === 'customer_context') {
                $role = $entities['role_context'] ?? 'ambiguous';
                if ($role !== 'customer') {
                    return 0;
                }
            }
        }

        // Bonus: Add small bonus if role context matches the domain
        $roleContext = $entities['role_context'] ?? 'ambiguous';
        if ($def['domain'] === 'providers' && $roleContext === 'provider') {
            $totalScore += 3;
        } elseif ($def['domain'] === 'customers' && $roleContext === 'customer') {
            $totalScore += 3;
        }

        return $totalScore;
    }
}
