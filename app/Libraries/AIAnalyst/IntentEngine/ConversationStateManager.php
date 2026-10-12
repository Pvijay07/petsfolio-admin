<?php

namespace App\Libraries\AIAnalyst\IntentEngine;

use Config\Database;

/**
 * ConversationStateManager
 * 
 * NEXT 3 — Conversation State Manager:
 * Persists and manages the active analytical conversation context across turns.
 * Stores topic, metric values, filters, findings, data quality warnings, and recommendations.
 * Allows conversational continuity so that follow-up questions ("how to solve this issue?", "which walkers?")
 * never reset the thread or lose previously established numbers.
 */
class ConversationStateManager
{
    private static ?array $memoryCache = [];

    /**
     * Get active analytical state for a conversation.
     * 
     * @param int $conversationId
     * @return array|null
     */
    public static function getState(int $conversationId): ?array
    {
        if ($conversationId <= 0) {
            return null;
        }

        if (isset(self::$memoryCache[$conversationId])) {
            return self::$memoryCache[$conversationId];
        }

        try {
            $db = Database::connect();
            $row = $db->table('ai_analyst_conversation_states')
                ->where('conversation_id', $conversationId)
                ->get()
                ->getRowArray();

            if (!$row) {
                return null;
            }

            $state = [
                'conversation_id'       => (int)$row['conversation_id'],
                'topic'                 => $row['topic'],
                'last_intent'           => $row['last_intent'],
                'domain'                => $row['domain'],
                'metric'                => $row['metric'],
                'base_metric_value'     => $row['base_metric_value'],
                'total_requests'        => $row['total_requests'],
                'service'               => $row['service'],
                'city'                  => $row['city'],
                'date_range'            => $row['date_range'],
                'breakdown_type'        => $row['breakdown_type'],
                'filters'               => json_decode($row['filters'] ?? '{}', true) ?: [],
                'metrics_data'          => json_decode($row['metrics_data'] ?? '{}', true) ?: [],
                'findings'              => json_decode($row['findings'] ?? '[]', true) ?: [],
                'data_quality_warnings' => json_decode($row['data_quality_warnings'] ?? '[]', true) ?: [],
                'recommendations'       => json_decode($row['recommendations'] ?? '[]', true) ?: [],
                'turn_count'            => (int)($row['turn_count'] ?? 1),
                'updated_at'            => $row['updated_at']
            ];

            self::$memoryCache[$conversationId] = $state;
            return $state;
        } catch (\Throwable $e) {
            log_message('error', 'ConversationStateManager::getState failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Persist or update the analytical state for a conversation.
     * 
     * @param int   $conversationId
     * @param array $state
     * @return bool
     */
    public static function saveState(int $conversationId, array $state): bool
    {
        if ($conversationId <= 0) {
            return false;
        }

        self::$memoryCache[$conversationId] = $state;

        try {
            $db = Database::connect();
            $existing = $db->table('ai_analyst_conversation_states')
                ->where('conversation_id', $conversationId)
                ->get()
                ->getRowArray();

            $turnCount = $existing ? ((int)($existing['turn_count'] ?? 1) + 1) : 1;

            $record = [
                'conversation_id'       => $conversationId,
                'topic'                 => $state['topic'] ?? null,
                'last_intent'           => $state['last_intent'] ?? null,
                'domain'                => $state['domain'] ?? null,
                'metric'                => $state['metric'] ?? null,
                'base_metric_value'     => (string)($state['base_metric_value'] ?? ''),
                'total_requests'        => (string)($state['total_requests'] ?? ''),
                'service'               => $state['service'] ?? null,
                'city'                  => $state['city'] ?? null,
                'date_range'            => $state['date_range'] ?? null,
                'breakdown_type'        => $state['breakdown_type'] ?? null,
                'filters'               => json_encode($state['filters'] ?? []),
                'metrics_data'          => json_encode($state['metrics_data'] ?? []),
                'findings'              => json_encode($state['findings'] ?? []),
                'data_quality_warnings' => json_encode($state['data_quality_warnings'] ?? []),
                'recommendations'       => json_encode($state['recommendations'] ?? []),
                'turn_count'            => $turnCount,
                'updated_at'            => date('Y-m-d H:i:s')
            ];

            if ($existing) {
                $db->table('ai_analyst_conversation_states')
                    ->where('conversation_id', $conversationId)
                    ->update($record);
            } else {
                $db->table('ai_analyst_conversation_states')->insert($record);
            }

            return true;
        } catch (\Throwable $e) {
            log_message('error', 'ConversationStateManager::saveState failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Build state payload from executed metrics and plan.
     */
    public static function buildStateFromExecution(
        int $conversationId,
        string $userMessage,
        ?string $intentId,
        array $metricsCalled,
        array $findings = [],
        array $dataWarnings = [],
        array $recommendations = []
    ): array {
        $state = [
            'conversation_id'       => $conversationId,
            'topic'                 => self::inferTopic($intentId, $metricsCalled, $userMessage),
            'last_intent'           => $intentId ?: 'metric_analysis',
            'domain'                => self::inferDomain($intentId, $metricsCalled),
            'metric'                => null,
            'base_metric_value'     => null,
            'total_requests'        => null,
            'service'               => null,
            'city'                  => null,
            'date_range'            => null,
            'breakdown_type'        => null,
            'filters'               => [],
            'metrics_data'          => [],
            'findings'              => $findings,
            'data_quality_warnings' => $dataWarnings,
            'recommendations'       => $recommendations,
        ];

        foreach ($metricsCalled as $mc) {
            $mName = $mc['name'] ?? '';
            $mData = $mc['data'] ?? [];
            $mArgs = $mc['args'] ?? [];

            $state['metrics_data'][$mName] = $mData;

            if (!empty($mArgs['service_type'])) $state['service'] = $mArgs['service_type'];
            if (!empty($mArgs['city'])) $state['city'] = $mArgs['city'];
            if (!empty($mArgs['date_range'])) $state['date_range'] = $mArgs['date_range'];

            // Extract primary metric value
            if ($mName === 'cancellation_rate' || $mName === 'dropoff_reasons_analysis') {
                $state['metric']            = 'cancelled_bookings';
                $state['base_metric_value'] = $mData['cancelled_bookings'] ?? $mData['total_halted'] ?? null;
                $state['total_requests']    = $mData['total_bookings'] ?? null;
                $state['breakdown_type']    = 'cancellation_reason';
            } elseif ($mName === 'revenue_overview') {
                $state['metric']            = 'platform_commission';
                $state['base_metric_value'] = $mData['clean_platform_commission'] ?? $mData['total_platform_commission'] ?? null;
                $state['total_requests']    = $mData['total_bookings'] ?? null;
            } elseif ($mName === 'active_jobs') {
                $state['metric']            = 'active_jobs';
                $state['base_metric_value'] = $mData['total_active_jobs'] ?? null;
            } elseif ($mName === 'provider_stats') {
                $state['metric']            = 'provider_count';
                $state['base_metric_value'] = $mData['total_providers'] ?? $mData['total_service_providers'] ?? null;
            }
        }

        return $state;
    }

    private static function inferTopic(?string $intentId, array $metrics, string $query): string
    {
        $qLower = strtolower($query);
        if (preg_match('/cancel/i', $qLower) || str_contains($intentId ?? '', 'cancel')) {
            return 'booking_cancellations';
        }
        if (preg_match('/revenue|earn|commission|gst/i', $qLower)) {
            return 'platform_revenue';
        }
        if (preg_match('/kyc|approval|fee\s*pending|pipeline/i', $qLower)) {
            return 'provider_onboarding_pipeline';
        }
        if (preg_match('/funnel|dropoff/i', $qLower)) {
            return 'conversion_funnel';
        }
        return 'business_performance';
    }

    private static function inferDomain(?string $intentId, array $metrics): string
    {
        if (str_starts_with($intentId ?? '', 'provider.')) return 'provider';
        if (str_starts_with($intentId ?? '', 'customer.')) return 'customer';
        if (str_starts_with($intentId ?? '', 'booking.'))  return 'booking';
        if (str_starts_with($intentId ?? '', 'revenue.'))  return 'finance';
        return 'general_bi';
    }
}
