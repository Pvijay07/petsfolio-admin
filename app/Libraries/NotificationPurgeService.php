<?php

namespace App\Libraries;

use Config\Database;

/**
 * NotificationPurgeService
 *
 * Handles purging of old notifications from the database after a specified retention period.
 * Supports batching, dry-runs, and custom table targeting.
 */
class NotificationPurgeService
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * Purge old notifications based on retention rules.
     *
     * @param array $options Configuration options:
     *                       - int    'days'       Retention period in days (default: 30)
     *                       - string 'table'      Target table name (default: 'notifications')
     *                       - bool   'read_only'  If true, only purge read notifications (default: false)
     *                       - int    'batch_size' Rows to delete per batch (default: 1000)
     *                       - bool   'dry_run'    If true, count rows to purge without deleting (default: false)
     * 
     * @return array Summary of operation results
     */
    public function purge(array $options = []): array
    {
        $startTime = microtime(true);

        $days      = isset($options['days']) ? (int)$options['days'] : 30;
        $table     = isset($options['table']) ? (string)$options['table'] : 'notifications';
        $readOnly  = !empty($options['read_only']);
        $batchSize = isset($options['batch_size']) ? (int)$options['batch_size'] : 1000;
        $dryRun    = !empty($options['dry_run']);

        if ($days < 1) {
            $days = 30;
        }

        if ($batchSize < 1) {
            $batchSize = 1000;
        }

        // If table is 'all', purge across all notification tables
        if ($table === 'all') {
            $targetTables = ['notifications', 'notification_logs'];
            $totalDeleted = 0;
            $details = [];

            foreach ($targetTables as $tbl) {
                if ($this->db->tableExists($tbl)) {
                    $subOptions = array_merge($options, ['table' => $tbl]);
                    $res = $this->purgeTable($subOptions, $startTime);
                    if ($res['success']) {
                        $totalDeleted += $res['deleted_count'];
                        $details[$tbl] = $res['deleted_count'];
                    }
                }
            }

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$days} days"));

            return [
                'success'           => true,
                'dry_run'           => $dryRun,
                'deleted_count'     => $totalDeleted,
                'details'           => $details,
                'table'             => 'all',
                'cutoff_date'       => $cutoffDate,
                'days'              => $days,
                'execution_time_ms' => $executionTime,
                'message'           => "Successfully purged {$totalDeleted} record(s) across all notification tables older than {$days} days."
            ];
        }

        return $this->purgeTable($options, $startTime);
    }

    /**
     * Purge a single table.
     */
    protected function purgeTable(array $options, float $startTime): array
    {
        $days      = (int)($options['days'] ?? 30);
        $table     = (string)($options['table'] ?? 'notifications');
        $readOnly  = !empty($options['read_only']);
        $batchSize = (int)($options['batch_size'] ?? 1000);
        $dryRun    = !empty($options['dry_run']);

        // Verify table exists
        if (!$this->db->tableExists($table)) {
            return [
                'success'           => false,
                'error'             => "Table '{$table}' does not exist in database.",
                'deleted_count'     => 0,
                'table'             => $table,
                'execution_time_ms' => 0
            ];
        }

        // Determine date column
        $dateColumn = $this->determineDateColumn($table);
        if (!$dateColumn) {
            return [
                'success'           => false,
                'error'             => "Could not detect a valid timestamp column (created_at, created_on, timestamp, updated_at) on table '{$table}'.",
                'deleted_count'     => 0,
                'table'             => $table,
                'execution_time_ms' => 0
            ];
        }

        // Calculate cutoff threshold date
        $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$days} days"));

        // Build base condition
        $builder = $this->db->table($table);
        $builder->where("{$dateColumn} <", $cutoffDate);

        // Optional filter for read notifications only
        if ($readOnly) {
            $readColumn = $this->determineReadColumn($table);
            if ($readColumn) {
                if ($readColumn === 'status') {
                    $builder->where('status', 'read');
                } else {
                    $builder->where($readColumn, 1);
                }
            }
        }

        // Handle Dry-Run Mode
        if ($dryRun) {
            $count = $builder->countAllResults();
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            return [
                'success'           => true,
                'dry_run'           => true,
                'deleted_count'     => 0,
                'matching_count'    => $count,
                'table'             => $table,
                'cutoff_date'       => $cutoffDate,
                'days'              => $days,
                'execution_time_ms' => $executionTime,
                'message'           => "[Dry Run] {$count} notification(s) older than {$days} days (before {$cutoffDate}) match deletion criteria in table '{$table}'."
            ];
        }

        // Execute Batched Deletion via direct DELETE queries
        $totalDeleted = 0;
        $iterations = 0;

        do {
            $iterations++;

            $builder = $this->db->table($table);
            $builder->where("{$dateColumn} <", $cutoffDate);

            if ($readOnly && isset($readColumn)) {
                if ($readColumn === 'status') {
                    $builder->where('status', 'read');
                } else {
                    $builder->where($readColumn, 1);
                }
            }

            // Perform direct batched delete
            $builder->limit($batchSize)->delete();
            $deletedInBatch = (int)$this->db->affectedRows();

            // Stop immediately if no rows were affected
            if ($deletedInBatch <= 0) {
                break;
            }

            $totalDeleted += $deletedInBatch;

            // Break if fewer records deleted than batch limit
        } while ($deletedInBatch >= $batchSize && $iterations < 500);

        $executionTime = round((microtime(true) - $startTime) * 1000, 2);
        $message = "Successfully deleted {$totalDeleted} notification(s) older than {$days} days (before {$cutoffDate}) from '{$table}'.";

        log_message('info', "[NotificationPurgeService] {$message}");

        return [
            'success'           => true,
            'dry_run'           => false,
            'deleted_count'     => $totalDeleted,
            'table'             => $table,
            'cutoff_date'       => $cutoffDate,
            'days'              => $days,
            'iterations'        => $iterations,
            'execution_time_ms' => $executionTime,
            'message'           => $message
        ];
    }

    /**
     * Dynamically detect column used for creation timestamps.
     */
    protected function determineDateColumn(string $table): ?string
    {
        $fields = $this->db->getFieldNames($table);
        $candidates = ['created_at', 'created_on', 'timestamp', 'date_created', 'updated_at'];

        foreach ($candidates as $candidate) {
            if (in_array($candidate, $fields, true)) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * Dynamically detect column used for read status.
     */
    protected function determineReadColumn(string $table): ?string
    {
        $fields = $this->db->getFieldNames($table);
        $candidates = ['is_read', 'read', 'read_at', 'status'];

        foreach ($candidates as $candidate) {
            if (in_array($candidate, $fields, true)) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * Dynamically detect primary key column.
     */
    protected function determinePrimaryKey(string $table): ?string
    {
        $fields = $this->db->getFieldNames($table);
        if (in_array('id', $fields, true)) {
            return 'id';
        }

        return null;
    }
}
