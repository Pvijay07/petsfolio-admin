<?php

namespace App\Libraries\AIAnalyst\IntentEngine;

/**
 * SemanticPlanValidator
 * 
 * NEXT 1 — Semantic Plan Validator:
 * Validates execution plans produced by MetricPlanner before executing SQL or metric methods.
 * Prevents logically invalid queries, mismatched dimensions, conflicting filters, and unindexed bloat.
 */
class SemanticPlanValidator
{
    /**
     * Allowed metric names catalog across CRM and MySQL metrics.
     */
    private const ALLOWED_METRICS = [
        'active_jobs', 'bookings_summary', 'bookings_by_area', 'daily_booking_trend',
        'cancellation_rate', 'provider_cancellation_rate', 'revenue_overview', 'revenue_by_service', 'gst_liability',
        'pending_payouts', 'platform_wallet_balance', 'provider_stats', 'provider_pipeline',
        'provider_revenue_overview', 'provider_utilization', 'provider_lead_funnel',
        'top_performing_providers', 'churn_rate', 'customer_acquisition_funnel',
        'active_customers', 'inactive_customers', 'customer_ltv', 'customer_repeat_rate',
        'kyc_verification_stats', 'funnel_analysis', 'dropoff_reasons_analysis',
        'business_events_overview', 'customer_journey_timeline', 'semantic_query_compiler',
        'provider_notification_bidding_analysis', 'community_overview', 'loyalty_overview',
        'client_profile_lookup', 'conversion_rate', 'funnel_dropoff', 'lead_source_performance',
        'avg_resolution_time', 'cross_sell_acceptance_rate', 'agent_performance',
        'whatsapp_inquiry_topics', 'cashfree_payout_balance',
        'registration_fee', 'support_tickets_overview', 'booking_payments_overview',
        'executive_payouts_overview', 'payout_balance_overview', 'client_wallets_overview',
        'sp_wallets_overview', 'call_verification_overview', 'churned_inactive_customers',
        'repeat_customers', 'executive_summary', 'services_settings_overview',
        'pet_demographics', 'dog_breeds_overview', 'feedback_and_reviews_overview',
        'user_refunds_summary', 'coupons_overview', 'referrals_overview',
        'walking_tracking_analytics', 'bids_conversion_analytics', 'tutorials_overview',
        'verification_questions_overview', 'provider_performance_score',
        'recovered_transactions_overview', 'provider_strikes_attendance',
        'fraud_anomaly_detection', 'low_provider_density_areas', 'locality_demand_supply',
        'daily_management_action_matrix', 'target_vs_actual_performance', 'net_revenue_waterfall', 'management_kpi_governance'
    ];

    /**
     * Allowed service types.
     */
    private const VALID_SERVICES = ['walking', 'grooming', 'training', 'boarding', 'sitting', 'all'];

    /**
     * Allowed standard date ranges.
     */
    private const VALID_DATE_RANGES = [
        'today', 'yesterday', 'this_week', 'last_week', '7_days', 'this_month',
        'last_month', '30_days', '90_days', 'this_year', 'last_year', 'all_time'
    ];

    /**
     * Allowed dimensions for semantic_query_compiler.
     */
    private const VALID_DIMENSIONS = ['city', 'service_type', 'status', 'month', 'week', 'day', 'date'];

    /**
     * Validate and sanitize a metric execution plan.
     * 
     * @param array $plan Plan from MetricPlanner::plan() ['type' => string, 'metrics' => [...]]
     * @return array ['is_valid' => bool, 'sanitized_plan' => array, 'warnings' => array, 'errors' => array]
     */
    public static function validate(array $plan): array
    {
        $warnings = [];
        $errors   = [];
        $sanitizedMetrics = [];

        if (empty($plan['metrics']) || !is_array($plan['metrics'])) {
            return [
                'is_valid'       => false,
                'sanitized_plan' => $plan,
                'warnings'       => [],
                'errors'         => ['Plan contains no executable metric functions.']
            ];
        }

        // Base filter synchronization for multi-metric plans
        $sharedService   = null;
        $sharedDateRange = null;

        foreach ($plan['metrics'] as $idx => $metricDef) {
            $metricName = trim((string)($metricDef['name'] ?? ''));
            $args       = is_array($metricDef['args'] ?? null) ? $metricDef['args'] : [];

            // 1. Metric Whitelist Check
            if (!in_array($metricName, self::ALLOWED_METRICS, true)) {
                $errors[] = "Metric function '{$metricName}' is not in the authorized pre-approved catalog.";
                continue;
            }

            // 2. Validate and Sanitize Date Range
            if (isset($args['date_range'])) {
                $dr = strtolower(trim((string)$args['date_range']));
                if (!in_array($dr, self::VALID_DATE_RANGES, true) && !preg_match('/^[a-z]{3,9}_\d{4}$/i', $dr)) {
                    $warnings[] = "Unrecognized date_range '{$dr}' in {$metricName}; defaulted to 'all_time'.";
                    $args['date_range'] = 'all_time';
                } else {
                    $args['date_range'] = $dr;
                }
            }

            // Check explicit start_date and end_date
            if (!empty($args['start_date']) && !empty($args['end_date'])) {
                if ($args['start_date'] > $args['end_date']) {
                    $warnings[] = "start_date ({$args['start_date']}) is after end_date ({$args['end_date']}); inverted to fix.";
                    $tmp = $args['start_date'];
                    $args['start_date'] = $args['end_date'];
                    $args['end_date']   = $tmp;
                }
            }

            // 3. Validate and Sanitize Service Type
            if (isset($args['service_type'])) {
                $st = strtolower(trim((string)$args['service_type']));
                if (!in_array($st, self::VALID_SERVICES, true)) {
                    // Try to normalize common plural forms
                    $map = ['walkers' => 'walking', 'walker' => 'walking', 'groomers' => 'grooming', 'groomer' => 'grooming', 'trainers' => 'training', 'trainer' => 'training', 'boarders' => 'boarding', 'sitters' => 'sitting'];
                    if (isset($map[$st])) {
                        $args['service_type'] = $map[$st];
                    } else {
                        $warnings[] = "Invalid service_type '{$st}' in {$metricName}; cleared.";
                        $args['service_type'] = null;
                    }
                } else {
                    $args['service_type'] = $st;
                }
            }

            // 4. Validate Dimensions for semantic_query_compiler
            if ($metricName === 'semantic_query_compiler' && isset($args['dimensions'])) {
                $validDims = [];
                foreach ((array)$args['dimensions'] as $dim) {
                    $dLower = strtolower(trim((string)$dim));
                    if (in_array($dLower, self::VALID_DIMENSIONS, true)) {
                        $validDims[] = $dLower;
                    } else {
                        $warnings[] = "Invalid OLAP dimension '{$dim}' skipped in semantic_query_compiler.";
                    }
                }
                $args['dimensions'] = array_values(array_unique($validDims));
            }

            // 5. Cap limit
            if (isset($args['limit'])) {
                $args['limit'] = max(1, min(1000, (int)$args['limit']));
            }

            // Synchronize multi-metric plans
            if ($idx === 0) {
                $sharedService   = $args['service_type'] ?? null;
                $sharedDateRange = $args['date_range'] ?? null;
            } else {
                if ($sharedService !== null && !isset($args['service_type'])) {
                    $args['service_type'] = $sharedService;
                }
                if ($sharedDateRange !== null && !isset($args['date_range'])) {
                    $args['date_range'] = $sharedDateRange;
                }
            }

            $sanitizedMetrics[] = [
                'name' => $metricName,
                'args' => $args
            ];
        }

        $isValid = empty($errors) && !empty($sanitizedMetrics);
        $sanitizedPlan = $plan;
        $sanitizedPlan['metrics'] = $sanitizedMetrics;

        return [
            'is_valid'       => $isValid,
            'sanitized_plan' => $sanitizedPlan,
            'warnings'       => $warnings,
            'errors'         => $errors
        ];
    }
}
