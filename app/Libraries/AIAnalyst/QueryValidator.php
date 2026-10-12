<?php

namespace App\Libraries\AIAnalyst;

use Config\Database;

/**
 * QueryValidator
 * 
 * Validates, executes pre-approved metric function calls, and logs query execution audit records in ai_analyst_queries.
 */
class QueryValidator
{
    private CRMMetrics $crmMetrics;
    private MySQLMetrics $mysqlMetrics;
    private $db;

    public function __construct()
    {
        $this->crmMetrics   = new CRMMetrics();
        $this->mysqlMetrics = new MySQLMetrics();
        $this->db           = Database::connect();
    }

    /**
     * Dispatch a pre-approved metric function call.
     */
    public function executeMetric(string $functionName, array $args = [], ?int $messageId = null): array
    {
        $startTime = microtime(true);
        $status    = 'ok';
        $errorMsg  = null;
        $result    = [];
        $source    = 'mysql';

        $crmFunctions = [
            'conversion_rate',
            'funnel_dropoff',
            'lead_source_performance',
            'avg_resolution_time',
            'cross_sell_acceptance_rate',
            'agent_performance',
            'whatsapp_inquiry_topics'
        ];

        $mysqlFunctions = [
            'active_jobs',
            'bookings_summary',
            'bookings_by_area',
            'daily_booking_trend',
            'cancellation_rate',
            'provider_cancellation_rate',
            'revenue_overview',
            'revenue_by_location',
            'registration_fee',
            'recovered_transactions_overview',
            'active_customers',
            'repeat_customers',
            'provider_stats',
            'low_provider_density_areas',
            'bids_conversion_analytics',
            'provider_performance_score',
            'provider_revenue_overview',
            'locality_demand_supply',
            'fraud_anomaly_detection',
            'user_refunds_summary',
            'churned_inactive_customers',
            'time_based_analytics',
            'addon_intelligence',
            'service_growth_analysis',
            'executive_summary',
            'business_forecast',
            'insurance_overview',
            'loyalty_overview',
            'community_overview',
            'sms_credits_overview',
            'payout_balance_overview',
            'verification_executives_overview',
            'executive_payouts_overview',
            'services_settings_overview',
            'support_tickets_overview',
            'booking_payments_overview',
            'notification_campaigns_overview',
            'call_verification_overview',
            'provider_reconciliation_overview',
            'client_wallet_reconciliation',
            'client_profile_lookup',
            'pet_demographics',
            'feedback_and_reviews_overview',
            'call_ai_quality_analytics',
            'upgrade_and_premium_requests',
            'operations_tasks_overview',
            'walking_tracking_analytics',
            'banners_overview',
            'tutorials_overview',
            'doctors_overview',
            'memberships_overview',
            'coupons_overview',
            'provider_strikes_attendance',
            'referrals_overview',
            'cameras_overview',
            'booking_conversations_overview',
            'dog_breeds_overview',
            'chat_overview',
            'table_data_overview',
            'roles_and_permissions_overview',
            'service_states_overview',
            'verification_questions_overview',
            'sp_wallets_overview',
            'client_wallets_overview',
            'packages_overview',
            'in_app_calls_overview',
            'aadhaar_verification_attempts',
            'provider_media_overview',
            'agreements_overview',
            'funnel_analysis',
            'dropoff_reasons_analysis',
            'business_events_overview',
            'customer_journey_timeline',
            'semantic_query_compiler',
            'provider_notification_bidding_analysis',
            'daily_management_action_matrix',
            'target_vs_actual_performance',
            'net_revenue_waterfall',
            'management_kpi_governance',
            'quotation_details_lookup'
        ];

        $aliases = [
            'quotation_details'                   => 'quotation_details_lookup',
            'quote_details'                       => 'quotation_details_lookup',
            'booking_quotations'                  => 'quotation_details_lookup',
            'booking_quotes'                      => 'quotation_details_lookup',
            'management_matrix'                   => 'daily_management_action_matrix',
            'action_matrix'                       => 'daily_management_action_matrix',
            'daily_management'                    => 'daily_management_action_matrix',
            'daily_management_question'           => 'daily_management_action_matrix',
            'supply_gap_matrix'                   => 'daily_management_action_matrix',
            'revenue_at_risk_matrix'              => 'daily_management_action_matrix',
            'target_vs_actual'                    => 'target_vs_actual_performance',
            'targets'                             => 'target_vs_actual_performance',
            'kpi_targets'                         => 'target_vs_actual_performance',
            'achievement_percentage'              => 'target_vs_actual_performance',
            'net_revenue'                         => 'net_revenue_waterfall',
            'revenue_waterfall'                   => 'net_revenue_waterfall',
            'contribution_margin'                 => 'net_revenue_waterfall',
            'service_profitability'               => 'net_revenue_waterfall',
            'kpi_governance'                      => 'management_kpi_governance',
            'dashboard_priorities'                => 'management_kpi_governance',
            'first_dashboard_priorities'          => 'management_kpi_governance',
            'management_dashboard'                => 'management_kpi_governance',
            'kpi_catalog'                         => 'management_kpi_governance',
            'demand_to_provider_ratio'            => 'locality_demand_supply',
            'demand_provider_ratio'               => 'locality_demand_supply',
            'area_ratios'                         => 'locality_demand_supply',
            'locality_ratio'                      => 'locality_demand_supply',
            'notification_bidding_analysis'       => 'provider_notification_bidding_analysis',
            'provider_notifications'              => 'provider_notification_bidding_analysis',
            'notification_logs_analysis'          => 'provider_notification_bidding_analysis',
            'funnel'                              => 'funnel_analysis',
            'conversion_funnel'                   => 'funnel_analysis',
            'walking_funnel'           => 'funnel_analysis',
            'grooming_funnel'          => 'funnel_analysis',
            'training_funnel'          => 'funnel_analysis',
            'boarding_funnel'          => 'funnel_analysis',
            'funnel_dropoffs'          => 'funnel_analysis',
            'dropoffs'                 => 'dropoff_reasons_analysis',
            'dropoff_analysis'         => 'dropoff_reasons_analysis',
            'halt_reasons'             => 'dropoff_reasons_analysis',
            'cancellation_reasons'     => 'dropoff_reasons_analysis',
            'booking_halts'            => 'dropoff_reasons_analysis',
            'business_events'          => 'business_events_overview',
            'event_stream'             => 'business_events_overview',
            'platform_events'          => 'business_events_overview',
            'customer_journey'         => 'customer_journey_timeline',
            'journey_timeline'         => 'customer_journey_timeline',
            'user_timeline'            => 'customer_journey_timeline',
            'agreements'               => 'agreements_overview',
            'provider_agreements'      => 'agreements_overview',
            'sp_agreements'            => 'agreements_overview',
            'client_agreements'        => 'agreements_overview',
            'signed_agreements'        => 'agreements_overview',
            'agreement_stats'          => 'agreements_overview',
            'provider_media'           => 'provider_media_overview',
            'service_media'            => 'provider_media_overview',
            'sp_service_images'        => 'provider_media_overview',
            'service_photos'           => 'provider_media_overview',
            'service_videos'           => 'provider_media_overview',
            'provider_photos'          => 'provider_media_overview',
            'provider_videos'          => 'provider_media_overview',
            'aadhaar_attempts'         => 'aadhaar_verification_attempts',
            'kyc_attempts'             => 'aadhaar_verification_attempts',
            'kyc_verification_attempts' => 'aadhaar_verification_attempts',
            'aadhaar_logs'             => 'aadhaar_verification_attempts',
            'semantic_query'           => 'semantic_query_compiler',
            'query_compiler'           => 'semantic_query_compiler',
            'client_stats'             => 'active_customers',
            'client_overview'          => 'active_customers',
            'client_sources'           => 'active_customers',
            'customer_stats'           => 'active_customers',
            'customer_overview'        => 'active_customers',
            'incomplete_users'         => 'active_customers',
            'client_lookup'            => 'client_profile_lookup',
            'client_search'            => 'client_profile_lookup',
            'client_details'           => 'client_profile_lookup',
            'user_lookup'              => 'client_profile_lookup',
            'customer_lookup'          => 'client_profile_lookup',
            'addons_overview'          => 'addon_intelligence',
            'addon_overview'           => 'addon_intelligence',
            'calls_overview'           => 'in_app_calls_overview',
            'in_app_calls'             => 'in_app_calls_overview',
            'call_details'             => 'in_app_calls_overview',
            'tickets_overview'         => 'support_tickets_overview',
            'support_tickets'          => 'support_tickets_overview',
            'support_ticket'           => 'support_tickets_overview',
            'tickets'                  => 'support_tickets_overview',
            'wallets_overview'         => 'sp_wallets_overview',
            'all_breeds_overview'      => 'dog_breeds_overview',
            'service_packages'         => 'packages_overview',
            'packages'                 => 'packages_overview',
            'refunds_overview'         => 'user_refunds_summary',
            'refunds'                  => 'user_refunds_summary',
        ];

        $resolvedFunction = $aliases[$functionName] ?? $functionName;

        try {
            if (in_array($resolvedFunction, $crmFunctions, true)) {
                $source = 'mongodb';
                $result = $this->callWithNamedArgs($this->crmMetrics, $resolvedFunction, $args);
            } elseif (in_array($resolvedFunction, $mysqlFunctions, true)) {
                $source = 'mysql';
                try {
                    $this->db->query("SET SESSION MAX_EXECUTION_TIME = 10000;");
                } catch (\Throwable $ignored) {
                }
                $result = $this->callWithNamedArgs($this->mysqlMetrics, $resolvedFunction, $args);
            } else {
                $status   = 'rejected';
                $errorMsg = "Unauthorized or unknown metric function: {$functionName}";
                log_message('error', $errorMsg);
            }
        } catch (\Throwable $e) {
            $status   = 'error';
            $errorMsg = $e->getMessage();
            log_message('error', "AI Analyst Metric Error [{$resolvedFunction}]: " . $errorMsg);
        }

        $execMs   = (int) round((microtime(true) - $startTime) * 1000);
        $rowCount = is_array($result) ? count($result) : 1;

        // Apply pre-synthesis business sanity checks (Pillar 5 & ResultValidator)
        if ($status === 'ok' && is_array($result)) {
            $warnings = ResultValidator::validate($resolvedFunction, $result, $args);
            if (!empty($warnings)) {
                $result['_sanity_warnings'] = array_merge($result['_sanity_warnings'] ?? [], $warnings);
            }
            $this->applySanityChecks($resolvedFunction, $result);

            // If result is empty array or contains no data, enrich with query status meta
            if (empty($result) || (isset($result['total_records']) && $result['total_records'] === 0) || (isset($result['total_providers']) && $result['total_providers'] === 0 && !empty($args['status']) && $args['status'] !== 'all')) {
                $result['_meta'] = [
                    'query_executed'  => true,
                    'filters_applied' => $args,
                    'note'            => 'Query executed successfully against live database. If results are 0, consider broadening filters (e.g. date_range to all_time or status to all).'
                ];
            }
        }

        // Log query audit record
        $this->logQueryAudit($messageId, $resolvedFunction, $args, $source, $rowCount, $execMs, $status, $errorMsg);

        return [
            'success'     => $status === 'ok',
            'function'    => $resolvedFunction,
            'source'      => $source,
            'data'        => $result,
            'exec_ms'     => $execMs,
            'error'       => $errorMsg
        ];
    }

    /**
     * Pre-synthesis Business Sanity Assertions (Pillar 5).
     * Validates that returned numbers make real-world business sense.
     * Flags negative revenues, impossible percentages (> 100%), and negative count anomalies.
     */
    public function applySanityChecks(string $metric, array &$result): void
    {
        $warnings = [];

        // 1. Check for negative monetary values
        $moneyKeys = [
            'revenue',
            'gross_revenue',
            'net_revenue',
            'total_amount',
            'gmv',
            'platform_commission',
            'service_commission',
            'total_registration_fee_inr',
            'total_recovered_revenue_inr',
            'total_user_refunds_inr',
            'cashfree_balance',
            'pending_payout_amount_total',
            'avg_booking_value',
            'converted_revenue_inr'
        ];
        foreach ($moneyKeys as $k) {
            if (isset($result[$k]) && is_numeric($result[$k]) && (float)$result[$k] < 0) {
                $warnings[] = "Business sanity violation: Negative financial value detected for '{$k}': " . $result[$k];
            }
        }

        // 2. Check for percentage bounds [0% - 100%]
        $pctKeys = [
            'conversion_rate',
            'cancellation_rate',
            'completion_rate',
            'dropoff_rate',
            'acceptance_rate',
            'retention_rate',
            'repeat_rate',
            'satisfaction_rate'
        ];
        foreach ($pctKeys as $k) {
            if (isset($result[$k])) {
                $rawVal = $result[$k];
                $numVal = is_string($rawVal) ? (float) str_replace(['%', ' '], '', $rawVal) : (float) $rawVal;
                if ($numVal < 0 || $numVal > 100.0) {
                    $warnings[] = "Business sanity violation: Percentage out of valid [0-100%] bounds for '{$k}': " . $rawVal;
                }
            }
        }

        // 3. Check for negative counts
        $countKeys = [
            'total_records',
            'total_providers',
            'active_providers',
            'pending_providers',
            'total_bookings',
            'completed_bookings',
            'cancelled_bookings',
            'total_leads',
            'converted_leads',
            'total_tickets',
            'open_tickets_count',
            'total_active_jobs'
        ];
        foreach ($countKeys as $k) {
            if (isset($result[$k]) && is_numeric($result[$k]) && (int)$result[$k] < 0) {
                $warnings[] = "Business sanity violation: Negative count detected for '{$k}': " . $result[$k];
            }
        }

        // 4. Multi-row sanity check for semantic query compiler
        if (isset($result['rows']) && is_array($result['rows'])) {
            foreach ($result['rows'] as $idx => $row) {
                if (isset($row['gmv']) && is_numeric($row['gmv']) && (float)$row['gmv'] < 0) {
                    $warnings[] = "Business sanity violation: Negative row GMV in group #{$idx}";
                    break;
                }
            }
        }

        if (!empty($warnings)) {
            $result['_sanity_warnings'] = $warnings;
            foreach ($warnings as $w) {
                log_message('warning', "AI Analyst Sanity Check [{$metric}]: {$w}");
            }
        }
    }

    /**
     * Pre-execution validation & hardened Direct SQL execution (Pillars 2 & 4).
     * Enforces: SELECT-only, table whitelist, single statement, EXPLAIN query plan, MAX_EXECUTION_TIME, and forced LIMIT 1000.
     * Provides self-repair hints for LLM retry loops.
     */
    public function executeDirectSQL(string $rawSql, ?int $messageId = null): array
    {
        $startTime = microtime(true);
        $sql = trim($rawSql);

        // 1. Single statement check (no semicolons separating multiple statements)
        $sqlNoSemicolon = rtrim($sql, ';');
        if (str_contains($sqlNoSemicolon, ';')) {
            return [
                'success' => false,
                'error'   => 'Security violation: Multi-statement SQL is strictly prohibited.',
                'phase'   => 'syntax_security'
            ];
        }

        // 2. Read-only validation (SELECT or WITH only)
        if (!preg_match('/^(SELECT|WITH)\s+/i', $sql)) {
            return [
                'success' => false,
                'error'   => 'Security violation: Only read-only SELECT queries are allowed.',
                'phase'   => 'access_control'
            ];
        }

        // 3. Dangerous keyword blocking
        $blocked = ['INSERT', 'UPDATE', 'DELETE', 'DROP', 'ALTER', 'CREATE', 'RENAME', 'TRUNCATE', 'GRANT', 'REVOKE', 'REPLACE', 'EXEC', 'CALL', 'FLUSH', 'LOAD_FILE', 'INTO OUTFILE', 'INTO DUMPFILE'];
        foreach ($blocked as $b) {
            if (preg_match('/\b' . $b . '\b/i', $sql)) {
                return [
                    'success' => false,
                    'error'   => "Security violation: Blocked SQL keyword '{$b}' detected.",
                    'phase'   => 'access_control'
                ];
            }
        }

        // 4. Table Whitelist Verification
        $allowedTables = [
            'v_bookings_overview',
            'users',
            'service_providers',
            'sp_services',
            'walking_service_bookings',
            'grooming_service_bookings',
            'training_service_bookings',
            'sitting_service_bookings',
            'transactions',
            'tickets',
            'ticket_messages',
            'packages',
            'calls',
            'pet_details',
            'platform_wallet_histories',
            'sp_wallet_histories',
            'client_wallet_histories',
            'user_refunds',
            'insurance_enquiries',
            'insurance_claims',
            'client_agreements',
            'coupons',
            'banners',
            'push_campaigns',
            'dog_breeds',
            'service_states',
            'general_settings',
            'roles',
            'permissions',
            'role_has_permissions',
            'feedback_reviews',
            'user_loyalty_points',
            'user_spin_rewards'
        ];

        preg_match_all('/\b(?:FROM|JOIN)\s+`?([a-zA-Z0-9_]+)`?/i', $sql, $matches);
        $referencedTables = array_map('strtolower', $matches[1] ?? []);
        foreach ($referencedTables as $table) {
            if (!in_array($table, $allowedTables, true)) {
                return [
                    'success' => false,
                    'error'   => "Access violation: Table '{$table}' is not in the authorized read-only whitelist.",
                    'phase'   => 'whitelist_enforcement'
                ];
            }
        }

        // 5. Force LIMIT 1000
        if (!preg_match('/\bLIMIT\s+\d+/i', $sql)) {
            $sql = rtrim($sql, ';') . ' LIMIT 1000';
        } else {
            $sql = preg_replace_callback('/\bLIMIT\s+(\d+)(?:\s*,\s*(\d+))?/i', function ($m) {
                $lim = isset($m[2]) ? (int)$m[2] : (int)$m[1];
                $safeLim = min($lim, 1000);
                return isset($m[2]) ? "LIMIT {$m[1]}, {$safeLim}" : "LIMIT {$safeLim}";
            }, $sql);
        }

        // 6. Pre-Exec Validation: Pre-flight EXPLAIN to catch syntax and query planning errors
        try {
            $this->db->query("EXPLAIN " . $sql);
        } catch (\Throwable $explainErr) {
            $msg = $explainErr->getMessage();
            log_message('error', "Direct SQL EXPLAIN failed: {$msg} | SQL: {$sql}");
            return [
                'success'     => false,
                'error'       => $msg,
                'phase'       => 'pre_exec_explain',
                'sql'         => $sql,
                'repair_hint' => 'Query failed pre-execution EXPLAIN plan check. Check column names, table aliases, and MySQL syntax.'
            ];
        }

        // 7. Execution Hardening: SET SESSION MAX_EXECUTION_TIME = 10000;
        try {
            $this->db->query("SET SESSION MAX_EXECUTION_TIME = 10000;");
            $query = $this->db->query($sql);
            $rows = $query->getResultArray();
            $rowCount = count($rows);
            $execMs = (int) round((microtime(true) - $startTime) * 1000);

            $resData = [
                'rows'       => $rows,
                'row_count'  => $rowCount,
                'sql'        => $sql,
                'exec_ms'    => $execMs
            ];

            // Sanity checks on direct SQL result
            $this->applySanityChecks('direct_sql', $resData);

            // Log query audit
            $this->logQueryAudit($messageId, 'direct_sql', ['sql' => $sql], 'mysql', $rowCount, $execMs, 'ok', null);

            return [
                'success' => true,
                'data'    => $resData,
                'exec_ms' => $execMs
            ];
        } catch (\Throwable $e) {
            $execMs = (int) round((microtime(true) - $startTime) * 1000);
            $errorMsg = $e->getMessage();
            log_message('error', "Direct SQL execution error: {$errorMsg} | SQL: {$sql}");
            $this->logQueryAudit($messageId, 'direct_sql', ['sql' => $sql], 'mysql', 0, $execMs, 'error', $errorMsg);

            return [
                'success'     => false,
                'error'       => $errorMsg,
                'phase'       => 'execution',
                'sql'         => $sql,
                'repair_hint' => 'MySQL runtime error. Verify table schema and data types.'
            ];
        }
    }

    /**
     * Log audit record to ai_analyst_queries.
     */
    private function logQueryAudit(?int $messageId, string $metricName, array $params, string $source, int $rowCount, int $execMs, string $status, ?string $errorMsg): void
    {
        try {
            if (empty($this->db->connID) || !@mysqli_ping($this->db->connID)) {
                $this->db->reconnect();
            }
            if ($this->db->tableExists('ai_analyst_queries')) {
                $this->db->table('ai_analyst_queries')->insert([
                    'message_id'    => $messageId,
                    'metric_name'   => $metricName,
                    'metric_params' => json_encode($params),
                    'data_source'   => $source,
                    'row_count'     => $rowCount,
                    'exec_ms'       => $execMs,
                    'status'        => $status,
                    'error_msg'     => $errorMsg,
                    'created_at'    => date('Y-m-d H:i:s')
                ]);
            }
        } catch (\Throwable $ex) {
            log_message('error', 'Failed to log ai_analyst_queries audit: ' . $ex->getMessage());
        }
    }

    /**
     * Invoke a method using named arguments from Gemini function calls.
     * Maps associative array keys to the method's parameter names.
     */
    private function callWithNamedArgs(object $instance, string $method, array $namedArgs): mixed
    {
        try {
            $ref = new \ReflectionMethod($instance, $method);
            $orderedArgs = [];

            foreach ($ref->getParameters() as $param) {
                $name = $param->getName();
                if (array_key_exists($name, $namedArgs)) {
                    $orderedArgs[] = $namedArgs[$name];
                } elseif ($param->isDefaultValueAvailable()) {
                    $orderedArgs[] = $param->getDefaultValue();
                } else {
                    $orderedArgs[] = null;
                }
            }

            return $ref->invokeArgs($instance, $orderedArgs);
        } catch (\Throwable $e) {
            log_message('error', "callWithNamedArgs failed [{$method}]: " . $e->getMessage());
            return call_user_func([$instance, $method]);
        }
    }
}
