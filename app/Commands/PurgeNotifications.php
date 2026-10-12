<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\NotificationPurgeService;

/**
 * PurgeNotifications Command
 *
 * Automatically purges old notifications from the database after a specified retention period.
 * 
 * Usage:
 *   php spark notifications:purge [--days 30] [--table notifications] [--read-only] [--batch-size 1000] [--dry-run]
 * 
 * Cron Example (Runs every day at 2:00 AM):
 *   0 2 * * * cd /var/www/petsfolio/live/admin && php spark notifications:purge --days=30 > /dev/null 2>&1
 */
class PurgeNotifications extends BaseCommand
{
    /**
     * The Command's Group
     */
    protected $group = 'Notifications';

    /**
     * The Command's Name
     */
    protected $name = 'notifications:purge';

    /**
     * The Command's Description
     */
    protected $description = 'Auto delete old notifications from notifications table after a specified retention period.';

    /**
     * The Command's Usage
     */
    protected $usage = 'notifications:purge [options]';

    /**
     * The Command's Options
     */
    protected $options = [
        '--days'       => 'Retention period in days (default: 30).',
        '--table'      => 'Target notifications table name (default: notifications).',
        '--read-only'  => 'Only purge notifications marked as read.',
        '--batch-size' => 'Maximum records deleted per iteration batch (default: 1000).',
        '--dry-run'    => 'Count and preview records to be deleted without removing them.'
    ];

    /**
     * Execute the command
     */
    public function run(array $params)
    {
        CLI::write('=== Notification Purge & Auto-Cleanup Engine ===', 'cyan');
        CLI::write('Started at: ' . date('Y-m-d H:i:s'), 'light_gray');
        CLI::newLine();

        $days      = $this->getOptionValue($params, 'days', 30);
        $table     = $this->getOptionValue($params, 'table', 'notifications');
        $readOnly  = (bool)$this->getOptionValue($params, 'read-only', false);
        $batchSize = $this->getOptionValue($params, 'batch-size', 1000);
        $dryRun    = (bool)$this->getOptionValue($params, 'dry-run', false);

        $options = [
            'days'       => (int)$days,
            'table'      => (string)$table,
            'read_only'  => (bool)$readOnly,
            'batch_size' => (int)$batchSize,
            'dry_run'    => (bool)$dryRun
        ];

        CLI::write("Target Table  : {$options['table']}", 'yellow');
        CLI::write("Retention     : {$options['days']} day(s)", 'yellow');
        CLI::write("Filter        : " . ($options['read_only'] ? 'Read notifications only' : 'All notifications'), 'yellow');
        CLI::write("Batch Size    : {$options['batch_size']} rows", 'yellow');
        CLI::write("Mode          : " . ($options['dry_run'] ? 'Dry Run (Preview Only)' : 'Live Deletion'), 'yellow');
        CLI::newLine();

        try {
            $purgeService = new NotificationPurgeService();
            $result = $purgeService->purge($options);

            if (!$result['success']) {
                CLI::error("❌ Error: " . ($result['error'] ?? 'Purge failed'));
                return;
            }

            if (!empty($result['dry_run'])) {
                CLI::write("ℹ️  {$result['message']}", 'cyan');
            } else {
                CLI::write("✅ {$result['message']}", 'green');
                CLI::write("Deleted Count : {$result['deleted_count']} row(s)", 'light_gray');
                if (!empty($result['details'])) {
                    foreach ($result['details'] as $tName => $tCount) {
                        CLI::write("  - {$tName}: {$tCount} deleted", 'light_gray');
                    }
                }
                CLI::write("Cutoff Date   : {$result['cutoff_date']}", 'light_gray');
                CLI::write("Execution Time: {$result['execution_time_ms']} ms", 'light_gray');
            }

        } catch (\Exception $e) {
            CLI::error('Error: ' . $e->getMessage());
            log_message('error', 'PurgeNotifications command error: ' . $e->getMessage());
        }

        CLI::newLine();
        CLI::write('Completed at: ' . date('Y-m-d H:i:s'), 'light_gray');
    }

    /**
     * Helper to retrieve CLI options robustly from CLI::getOption, $params array, or raw CLI args.
     */
    protected function getOptionValue(array $params, string $name, $default = null)
    {
        // 1. Try CodeIgniter CLI::getOption
        $val = CLI::getOption($name);
        if ($val !== null) {
            return $val;
        }

        // 2. Try associative key in $params
        if (isset($params[$name])) {
            return $params[$name];
        }

        // 3. Convert $params to zero-indexed array safely
        $indexedParams = array_values($params);
        $count = count($indexedParams);

        for ($i = 0; $i < $count; $i++) {
            $arg = (string)$indexedParams[$i];

            if (str_starts_with($arg, "--{$name}=") || str_starts_with($arg, "-{$name}=")) {
                return explode('=', $arg, 2)[1];
            }

            if ($arg === "--{$name}" || $arg === "-{$name}") {
                if (isset($indexedParams[$i + 1]) && !str_starts_with((string)$indexedParams[$i + 1], '-')) {
                    return $indexedParams[$i + 1];
                }
                return true;
            }

            if ($name === 'days' && is_numeric($arg)) {
                return (int)$arg;
            }
        }

        return $default;
    }
}
