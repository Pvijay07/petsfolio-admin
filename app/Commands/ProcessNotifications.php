<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\Notification\CampaignService;

/**
 * ProcessNotifications Command
 *
 * Processes scheduled notification campaigns that are due.
 * Intended to be run via cron:
 *   * /5 * * * * cd /var/www/petsfolio/beta/admin && php spark notifications:process
 */
class ProcessNotifications extends BaseCommand
{
    /**
     * The Command's Group
     */
    protected $group = 'Notifications';

    /**
     * The Command's Name
     */
    protected $name = 'notifications:process';

    /**
     * The Command's Description
     */
    protected $description = 'Process scheduled notification campaigns that are due for sending.';

    /**
     * The Command's Usage
     */
<<<<<<< HEAD
    protected $usage = 'notifications:process';
=======
    protected $usage = 'notifications:process [--purge] [--purge-days 30]';

    /**
     * The Command's Options
     */
    protected $options = [
        '--purge'      => 'Also run auto-purge for old notifications.',
        '--purge-days' => 'Retention period in days when purging (default: 30).'
    ];
>>>>>>> origin/main

    /**
     * Execute the command
     */
    public function run(array $params)
    {
        CLI::write('=== Notification Campaign Processor ===', 'cyan');
        CLI::write('Started at: ' . date('Y-m-d H:i:s'), 'light_gray');
        CLI::newLine();

        try {
            $campaignService = new CampaignService();
            $results = $campaignService->processScheduledCampaigns();

            if (empty($results)) {
                CLI::write('No scheduled campaigns to process.', 'yellow');
            } else {
                foreach ($results as $campaignId => $result) {
                    if ($result['success'] ?? false) {
                        CLI::write(
                            "✅ Campaign #{$campaignId}: Sent {$result['sent']}/{$result['total_audience']}, Failed: {$result['failed']}",
                            'green'
                        );
                    } else {
                        CLI::write(
                            "❌ Campaign #{$campaignId}: " . ($result['error'] ?? 'Unknown error'),
                            'red'
                        );
                    }
                }

                CLI::newLine();
                CLI::write('Processed ' . count($results) . ' campaign(s).', 'cyan');
            }

<<<<<<< HEAD
=======
            // Execute purge if --purge option is passed
            $hasPurgeOption = $this->getOptionValue($params, 'purge', null) !== null;
            if ($hasPurgeOption) {
                CLI::newLine();
                CLI::write('--- Auto-Purging Old Notifications ---', 'yellow');
                $purgeDays = (int)$this->getOptionValue($params, 'purge-days', 30);
                $purgeService = new \App\Libraries\NotificationPurgeService();
                $purgeResult = $purgeService->purge(['days' => $purgeDays, 'table' => 'all']);
                CLI::write($purgeResult['message'] ?? 'Purge completed.', 'light_gray');
            }

>>>>>>> origin/main
        } catch (\Exception $e) {
            CLI::error('Error: ' . $e->getMessage());
            log_message('error', 'ProcessNotifications command error: ' . $e->getMessage());
        }

        CLI::newLine();
        CLI::write('Completed at: ' . date('Y-m-d H:i:s'), 'light_gray');
    }
<<<<<<< HEAD
=======

    /**
     * Helper to retrieve CLI options robustly from CLI::getOption, $params array, or raw CLI args.
     */
    protected function getOptionValue(array $params, string $name, $default = null)
    {
        $val = CLI::getOption($name);
        if ($val !== null) {
            return $val;
        }

        if (isset($params[$name])) {
            return $params[$name];
        }

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
        }

        return $default;
    }
>>>>>>> origin/main
}
