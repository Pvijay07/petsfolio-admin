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
            'bookings_summary',
            'daily_booking_trend',
            'cancellation_rate',
            'revenue_overview',
            'active_customers',
            'repeat_customers',
            'provider_stats',
            'low_provider_density_areas',
            'bids_conversion_analytics',
            'provider_performance_score',
            'locality_demand_supply',
            'fraud_anomaly_detection',
            'executive_summary',
            'business_forecast'
        ];

        try {
            if (in_array($functionName, $crmFunctions, true)) {
                $source = 'mongodb';
                $result = $this->callWithNamedArgs($this->crmMetrics, $functionName, $args);
            } elseif (in_array($functionName, $mysqlFunctions, true)) {
                $source = 'mysql';
                $result = $this->callWithNamedArgs($this->mysqlMetrics, $functionName, $args);
            } else {
                $status   = 'rejected';
                $errorMsg = "Unauthorized or unknown metric function: {$functionName}";
                log_message('error', $errorMsg);
            }
        } catch (\Throwable $e) {
            $status   = 'error';
            $errorMsg = $e->getMessage();
            log_message('error', "AI Analyst Metric Error [{$functionName}]: " . $errorMsg);
        }

        $execMs   = (int) round((microtime(true) - $startTime) * 1000);
        $rowCount = is_array($result) ? count($result) : 1;

        // Log query audit record
        $this->logQueryAudit($messageId, $functionName, $args, $source, $rowCount, $execMs, $status, $errorMsg);

        return [
            'success'     => $status === 'ok',
            'function'    => $functionName,
            'source'      => $source,
            'data'        => $result,
            'exec_ms'     => $execMs,
            'error'       => $errorMsg
        ];
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
