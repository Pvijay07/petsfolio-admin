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
    protected $usage = 'notifications:process';

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

        } catch (\Exception $e) {
            CLI::error('Error: ' . $e->getMessage());
            log_message('error', 'ProcessNotifications command error: ' . $e->getMessage());
        }

        CLI::newLine();
        CLI::write('Completed at: ' . date('Y-m-d H:i:s'), 'light_gray');
    }
}
