<?php

namespace App\Libraries\AIAnalyst\IntentEngine;

/**
 * MetricPlanner
 * 
 * Stage 5 of the Intent Engine pipeline.
 * Converts a resolved intent + entities into concrete metric function calls
 * with populated arguments.
 * 
 * This is the ONLY component that produces the final output format consumed
 * by GeminiAnalyst's $addMetric() dispatcher.
 * 
 * Output format matches SemanticResolver::resolve() exactly:
 *   ['type' => string, 'metrics' => [['name' => string, 'args' => array], ...]]
 */
class MetricPlanner
{
    /**
     * Build the metric execution plan from a resolved intent.
     * 
     * @param array $resolution  Output from IntentResolver::resolve()
     * @return array|null  ['type' => string, 'metrics' => [...]] or null if unresolvable
     */
    public static function plan(array $resolution): ?array
    {
        if (!$resolution['resolved'] || empty($resolution['intent_id'])) {
            return null;
        }

        $intentId = $resolution['intent_id'];
        $entities = $resolution['entities'] ?? [];

        // Handle follow-up intents (dynamic metrics from IntentResolver)
        if (!empty($resolution['followup_metrics'])) {
            return [
                'type'    => $intentId,
                'metrics' => $resolution['followup_metrics'],
            ];
        }

        // Get intent definition
        $def = IntentRegistry::getIntent($intentId);
        if ($def === null) {
            if (function_exists('log_message')) {
                log_message('warning', "IntentEngine MetricPlanner: Unknown intent_id '{$intentId}'");
            }
            return null;
        }

        $metricsDef = $def['metrics'];

        // Handle dynamic metric resolution
        if ($metricsDef === 'funnel_dynamic') {
            return self::planFunnelMetrics($entities);
        }

        if ($metricsDef === 'followup_dynamic') {
            // Should have been handled by followup_metrics above
            return null;
        }

        // Standard metric resolution: populate arg templates with entity values
        $metrics = [];
        $dateRanges = $entities['date_ranges'] ?? [];
        if (count($dateRanges) > 1) {
            foreach ($metricsDef as $metricDef) {
                if (isset($metricDef['arg_map']['date_range'])) {
                    foreach ($dateRanges as $range) {
                        $entCopy = $entities;
                        $entCopy['date_range'] = $range;
                        $args = self::resolveArgMap($metricDef['arg_map'] ?? [], $entCopy);
                        $metrics[] = [
                            'name' => $metricDef['name'],
                            'args' => $args,
                        ];
                    }
                } else {
                    $args = self::resolveArgMap($metricDef['arg_map'] ?? [], $entities);
                    $metrics[] = [
                        'name' => $metricDef['name'],
                        'args' => $args,
                    ];
                }
            }
        } else {
            foreach ($metricsDef as $metricDef) {
                $args = self::resolveArgMap($metricDef['arg_map'] ?? [], $entities);
                $metrics[] = [
                    'name' => $metricDef['name'],
                    'args' => $args,
                ];
            }
        }

        return [
            'type'    => $intentId,
            'metrics' => $metrics,
        ];
    }

    /**
     * Resolve an argument map by substituting entity placeholders.
     * 
     * Placeholder format: {entity_name|default_value}
     *   - {phone}           → entities['phone'] or excluded if missing
     *   - {date_range|all_time} → entities['date_range'] or 'all_time'
     *   - {service_type|}   → entities['service_type'] or null (excluded)
     *   - {search_name|}    → entities['search_name'] or entities['search_name_from_history'] or null
     */
    private static function resolveArgMap(array $argMap, array $entities): array
    {
        $resolved = [];

        foreach ($argMap as $argName => $template) {
            if (is_string($template) && preg_match('/^\{(.+)\}$/', $template, $m)) {
                $parts = explode('|', $m[1], 2);
                $entityKey = $parts[0];
                $default   = $parts[1] ?? null;

                // Special handling for search_name: try current, then history
                if ($entityKey === 'search_name') {
                    $value = $entities['search_name'] ?? $entities['search_name_from_history'] ?? $default;
                } else {
                    $value = $entities[$entityKey] ?? $default;
                }

                // Only include the argument if value is non-empty
                if ($value !== null && $value !== '') {
                    $resolved[$argName] = $value;
                }
            } else {
                // Static value
                $resolved[$argName] = $template;
            }
        }

        return $resolved;
    }

    /**
     * Dynamic metric planning for funnel/dropoff intents.
     * Decides whether to return funnel analysis, dropoff reasons, or both.
     */
    private static function planFunnelMetrics(array $entities): array
    {
        $svc = $entities['service_type'] ?? null;
        $dr  = $entities['date_range'] ?? 'all_time';

        // Check if user is asking specifically for reasons (not the funnel stages)
        $isReasonsOnly = !empty($entities['_reasons_only']);

        if ($isReasonsOnly) {
            return [
                'type'    => 'dropoff_reasons',
                'metrics' => [
                    ['name' => 'dropoff_reasons_analysis', 'args' => ['service_type' => $svc, 'date_range' => $dr]],
                ],
            ];
        }

        // Default: return both funnel stages and reasons
        return [
            'type'    => 'funnel_analysis',
            'metrics' => [
                ['name' => 'funnel_analysis', 'args' => ['funnel_type' => $svc ?: 'walking', 'date_range' => $dr]],
                ['name' => 'dropoff_reasons_analysis', 'args' => ['service_type' => $svc, 'date_range' => $dr]],
            ],
        ];
    }
}
