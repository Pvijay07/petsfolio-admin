<?php

namespace App\Libraries\AIAnalyst\IntentEngine;

/**
 * IntentEngine
 * 
 * The single orchestrator and entry point for the Multi-Stage BI Intent Engine.
 * 
 * Pipeline:
 *   1. QueryNormalizer   — Normalize input (typos, fillers, synonyms)
 *   2. EntityExtractor   — Extract structured entities (phone, service, city, date, name, etc.)
 *   3. IntentClassifier  — Score all registered intents via weighted pattern matching
 *   4. IntentResolver    — Resolve ambiguity, apply business rules, handle follow-ups
 *   5. MetricPlanner     — Map confirmed intent → metric function calls with args
 * 
 * This class is a DROP-IN REPLACEMENT for SemanticResolver::resolve().
 * Same signature, same return format:
 *   ['type' => string, 'metrics' => [['name' => string, 'args' => array], ...]]
 * Returns null if no valid intent is detected.
 * 
 * Design principle: "No valid intent → No SQL → No answer."
 */
class IntentEngine
{
    /**
     * Resolve a user message into a deterministic metric execution plan.
     * 
     * @param string      $userMessage        Raw user input
     * @param string|null $historyText        Concatenated recent conversation history
     * @param array|null  $conversationState  Active analytical state from ConversationStateManager
     * @return array|null  ['type' => string, 'metrics' => [...]] or null
     */
    public static function resolve(string $userMessage, ?string $historyText = null, ?array $conversationState = null): ?array
    {
        $startTime = microtime(true);

        // ── Stage 1: Normalize ──
        $queryContext = QueryNormalizer::normalize($userMessage);
        $historyNorm  = QueryNormalizer::normalizeHistory($historyText);

        self::log('debug', "IntentEngine [Stage 1] Normalized: \"{$queryContext['normalized']}\"");

        // ── Stage 2: Extract Entities ──
        $entities = EntityExtractor::extract($queryContext, $historyNorm);

        self::log('debug', "IntentEngine [Stage 2] Entities: " . json_encode(
            array_filter($entities, fn($v) => $v !== null && $v !== '' && $v !== 'ambiguous')
        ));

        // ── Stage 2.5: Follow-up Intent Resolution (Conversational State Continuity) ──
        if (!empty($conversationState)) {
            $followup = FollowupIntentResolver::resolve($userMessage, $conversationState, $entities);
            if (!empty($followup)) {
                self::log('debug', "IntentEngine [Stage 2.5] Follow-up resolved: {$followup['intent_id']} (action={$followup['action']})");
                $plan = [
                    'type'      => $followup['action'] === 'recommend_solutions' ? 'followup_solution' : 'followup_drilldown',
                    'intent_id' => $followup['intent_id'],
                    'topic'     => $followup['topic'],
                    'action'    => $followup['action'],
                    'state'     => $conversationState,
                    'metrics'   => $followup['metrics'] ?? []
                ];
                if (!empty($plan['metrics'])) {
                    $validation = SemanticPlanValidator::validate($plan);
                    return $validation['is_valid'] ? $validation['sanitized_plan'] : $plan;
                }
                return $plan;
            }
        }

        // ── Stage 3: Classify Intent ──
        $classification = IntentClassifier::classify(
            $queryContext['normalized'],
            $entities,
            $historyNorm
        );

        $topIntent = $classification['top']['intent_id'] ?? 'none';
        $topScore  = $classification['top']['score'] ?? 0;
        $candidateCount = count($classification['candidates']);

        self::log('debug', "IntentEngine [Stage 3] Top intent: {$topIntent} (score={$topScore}, candidates={$candidateCount}, confidence={$classification['confidence']})");

        // ── Stage 4: Resolve ──
        $resolution = IntentResolver::resolve($classification, $entities, $historyNorm);

        $resolvedIntent = $resolution['intent_id'] ?? 'null';
        self::log('debug', "IntentEngine [Stage 4] Resolved: {$resolvedIntent} (reason={$resolution['reason']})");

        // ── Stage 5: Plan Metrics ──
        $plan = MetricPlanner::plan($resolution);

        // ── Stage 6: Semantic Plan Validator ──
        if ($plan && !empty($plan['metrics'])) {
            $validation = SemanticPlanValidator::validate($plan);
            if ($validation['is_valid']) {
                $plan = $validation['sanitized_plan'];
                if (!empty($validation['warnings'])) {
                    self::log('warning', "IntentEngine [Stage 6] Plan warnings: " . implode('; ', $validation['warnings']));
                }
            } else {
                self::log('error', "IntentEngine [Stage 6] Plan rejected by SemanticPlanValidator: " . implode('; ', $validation['errors']));
                return null;
            }
        }

        $elapsed = round((microtime(true) - $startTime) * 1000, 2);
        $metricCount = $plan ? count($plan['metrics']) : 0;
        self::log('debug', "IntentEngine [Stage 6] Validated Plan: " . ($plan ? $plan['type'] : 'null') . " ({$metricCount} metrics, {$elapsed}ms)");

        return $plan;
    }

    private static function log(string $level, string $message): void
    {
        if (function_exists('log_message')) {
            log_message($level, $message);
        }
    }

    /**
     * Debug mode: returns the full pipeline trace instead of just the plan.
     * Useful for regression testing and diagnostics.
     * 
     * @param string      $userMessage  Raw user input
     * @param string|null $historyText  Concatenated recent conversation history
     * @return array  Full pipeline trace with all intermediate results
     */
    public static function debug(string $userMessage, ?string $historyText = null, ?array $conversationState = null): array
    {
        $startTime = microtime(true);

        // Stage 1
        $queryContext = QueryNormalizer::normalize($userMessage);
        $historyNorm  = QueryNormalizer::normalizeHistory($historyText);

        // Stage 2
        $entities = EntityExtractor::extract($queryContext, $historyNorm);

        // Stage 2.5: Follow-up Intent Resolution
        if (!empty($conversationState)) {
            $followup = FollowupIntentResolver::resolve($userMessage, $conversationState, $entities);
            if (!empty($followup)) {
                $plan = [
                    'type'      => $followup['action'] === 'recommend_solutions' ? 'followup_solution' : 'followup_drilldown',
                    'intent_id' => $followup['intent_id'],
                    'topic'     => $followup['topic'],
                    'action'    => $followup['action'],
                    'state'     => $conversationState,
                    'metrics'   => $followup['metrics'] ?? []
                ];
                $elapsed = round((microtime(true) - $startTime) * 1000, 2);
                return [
                    'input'          => $userMessage,
                    'normalized'     => $queryContext['normalized'],
                    'entities'       => $entities,
                    'classification' => [
                        'top'          => ['intent_id' => $followup['intent_id'], 'score' => 100],
                        'confidence'   => 1.0,
                        'candidates'   => [],
                        'is_ambiguous' => false,
                    ],
                    'resolution'     => [
                        'intent_id' => $followup['intent_id'],
                        'resolved'  => true,
                        'reason'    => 'conversational_followup_resolved',
                    ],
                    'plan'           => $plan,
                    'elapsed_ms'     => $elapsed,
                ];
            }
        }

        // Stage 3
        $classification = IntentClassifier::classify(
            $queryContext['normalized'],
            $entities,
            $historyNorm
        );

        // Stage 4
        $resolution = IntentResolver::resolve($classification, $entities, $historyNorm);

        // Stage 5
        $plan = MetricPlanner::plan($resolution);

        $elapsed = round((microtime(true) - $startTime) * 1000, 2);

        return [
            'input'          => $userMessage,
            'normalized'     => $queryContext['normalized'],
            'entities'       => $entities,
            'classification' => [
                'top'        => $classification['top'],
                'confidence' => $classification['confidence'],
                'candidates' => array_slice($classification['candidates'], 0, 5),  // Top 5
                'is_ambiguous' => $classification['is_ambiguous'],
            ],
            'resolution'     => [
                'intent_id' => $resolution['intent_id'],
                'resolved'  => $resolution['resolved'],
                'reason'    => $resolution['reason'],
            ],
            'plan'           => $plan,
            'elapsed_ms'     => $elapsed,
        ];
    }
}
