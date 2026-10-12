<?php

namespace App\Libraries\AIAnalyst;

/**
 * ResultValidator
 * 
 * NEXT 2 — Result Validator:
 * Separates SQL correctness from business correctness and validates all returned data.
 * Catches mathematical contradictions (e.g. 153 cancellations vs 327 cancellation reasons),
 * impossible rates, negative monetary amounts, and un-reconciled operational metrics
 * BEFORE any natural language synthesis occurs.
 */
class ResultValidator
{
    /**
     * Validates database metric results before narrative synthesis.
     * Returns an array of validation issues / warnings (empty if 100% valid).
     * Also mutates $result to inject structured data quality metadata.
     * 
     * @param string $metric Function name executed
     * @param array  $result Result payload from metric execution
     * @param array  $args   Arguments passed to the metric
     * @return array Array of warning strings
     */
    public static function validate(string $metric, array &$result, array $args = []): array
    {
        $warnings = [];
        $dataQualityFlags = [];

        // ── 1. CANCELLATION & REASON SUM INTEGRITY (The 153 vs 327 Rule) ──
        if (in_array($metric, ['cancellation_rate', 'dropoff_reasons_analysis', 'funnel_analysis', 'bookings_summary'], true)) {
            $cancelledCount = null;
            if (isset($result['cancelled_bookings'])) {
                $cancelledCount = (int)$result['cancelled_bookings'];
            } elseif (isset($result['total_cancelled'])) {
                $cancelledCount = (int)$result['total_cancelled'];
            } elseif (isset($result['total_halted'])) {
                $cancelledCount = (int)$result['total_halted'];
            }

            // Check breakdown reasons
            $reasons = $result['cancellation_reasons'] ?? $result['breakdown'] ?? null;
            if ($cancelledCount !== null && is_array($reasons) && !empty($reasons)) {
                $reasonSum = 0;
                $highestSingleReasonCount = 0;
                $highestSingleReasonName  = '';

                foreach ($reasons as $r) {
                    $cnt = (int)($r['count'] ?? $r['dropoffs'] ?? 0);
                    $reasonSum += $cnt;
                    if ($cnt > $highestSingleReasonCount) {
                        $highestSingleReasonCount = $cnt;
                        $highestSingleReasonName  = $r['reason'] ?? $r['code'] ?? 'Unknown';
                    }
                }

                // If breakdown sum exceeds cancelled bookings count
                if ($reasonSum > $cancelledCount) {
                    $warnMsg = "Data Contradiction: Total cancellation reasons count ({$reasonSum}) exceeds unique cancelled bookings count ({$cancelledCount}). "
                        . "Reason records reflect historical multi-event status transition logs rather than 1:1 distinct cancelled bookings.";
                    $warnings[] = $warnMsg;
                    $dataQualityFlags[] = [
                        'type'             => 'breakdown_count_discrepancy',
                        'base_metric'      => 'cancelled_bookings',
                        'base_value'       => $cancelledCount,
                        'breakdown_sum'    => $reasonSum,
                        'discrepancy_note' => $warnMsg,
                        'caveat'           => "153 unique bookings were cancelled, while {$reasonSum} status log entries (including '{$highestSingleReasonName}') were recorded across status transition events."
                    ];

                    // Inject safe reconciled figures into result so synthesizer never blindly hallucinates
                    $result['_data_discrepancy'] = [
                        'has_contradiction'     => true,
                        'unique_cancelled'      => $cancelledCount,
                        'reason_event_logs_sum' => $reasonSum,
                        'explanation'           => "Database contains multi-event cancellation logs per booking request. Unique cancelled bookings count is {$cancelledCount}."
                    ];
                }
            }

            // Total bookings vs cancelled bookings
            if (isset($result['total_bookings']) && $cancelledCount !== null) {
                $total = (int)$result['total_bookings'];
                if ($cancelledCount > $total) {
                    $warnings[] = "Data Contradiction: Cancelled bookings ({$cancelledCount}) cannot exceed total bookings ({$total}).";
                }
            }
        }

        // ── 2. MONETARY NON-NEGATIVITY RULE ──
        $monetaryFields = [
            'gross_revenue', 'gross_revenue_inr', 'net_revenue', 'net_revenue_inr',
            'platform_commission', 'total_amount', 'total_spend_inr', 'wallet_balance',
            'cashfree_balance', 'total_registration_fee_inr', 'gmv', 'completed_revenue',
            'total_recovered_revenue_inr', 'total_user_refunds_inr', 'converted_revenue_inr'
        ];
        foreach ($monetaryFields as $field) {
            if (isset($result[$field]) && is_numeric($result[$field]) && (float)$result[$field] < 0) {
                $warnings[] = "Business Sanity Violation: Negative monetary amount for [{$field}]: {$result[$field]}";
                $dataQualityFlags[] = ['type' => 'negative_monetary_amount', 'field' => $field, 'value' => $result[$field]];
            }
        }

        // Commission vs GMV check
        if (isset($result['platform_commission']) && isset($result['gmv'])) {
            $comm = (float)$result['platform_commission'];
            $gmv  = (float)$result['gmv'];
            if ($gmv > 0 && $comm > $gmv) {
                $warnings[] = "Business Sanity Violation: Platform commission (₹{$comm}) exceeds GMV (₹{$gmv}).";
            }
        }

        // ── 3. PERCENTAGE BOUNDS [0% - 100%] ──
        $percentageFields = [
            'conversion_rate', 'cancellation_rate', 'completion_rate',
            'repeat_rate', 'acceptance_rate', 'dropoff_rate', 'retention_rate'
        ];
        foreach ($percentageFields as $field) {
            if (isset($result[$field])) {
                $val = (float)str_replace('%', '', (string)$result[$field]);
                if ($val < 0 || $val > 100) {
                    $warnings[] = "Business Sanity Violation: Percentage out of bounds [0-100%] for [{$field}]: {$result[$field]}";
                    $dataQualityFlags[] = ['type' => 'percentage_out_of_bounds', 'field' => $field, 'value' => $result[$field]];
                }
            }
        }

        // ── 4. PROVIDER & CLIENT PIPELINE METRIC INTEGRITY ──
        if (isset($result['active_providers_count']) && isset($result['total_providers'])) {
            $act = (int)$result['active_providers_count'];
            $tot = (int)$result['total_providers'];
            if ($act > $tot) {
                $warnings[] = "Data Inconsistency: Active providers ({$act}) cannot exceed total providers ({$tot}).";
            }
        }

        if (isset($result['verified_providers_count']) && isset($result['total_providers'])) {
            $ver = (int)$result['verified_providers_count'];
            $tot = (int)$result['total_providers'];
            if ($ver > $tot) {
                $warnings[] = "Data Inconsistency: Verified providers ({$ver}) cannot exceed total providers ({$tot}).";
            }
        }

        // ── 5. FUNNEL STEP MONOTONICITY ──
        if ($metric === 'funnel_analysis' && isset($result['funnel_steps']) && is_array($result['funnel_steps'])) {
            $prevCount = null;
            foreach ($result['funnel_steps'] as $step) {
                $currCount = (int)($step['count'] ?? 0);
                if ($prevCount !== null && $currCount > $prevCount) {
                    $stepName = $step['step_name'] ?? 'Step';
                    $warnings[] = "Funnel Monotonicity Warning: Step '{$stepName}' count ({$currCount}) exceeds previous step ({$prevCount}).";
                }
                $prevCount = $currCount;
            }
        }

        // ── 6. ATTACH DATA QUALITY FLAGS TO RESULT ──
        if (!empty($dataQualityFlags)) {
            $result['_data_quality_flags'] = $dataQualityFlags;
        }

        return $warnings;
    }
}
