<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;

/**
 * Alias command to support singular "php spark notification:process"
 */
class ProcessNotificationAlias extends BaseCommand
{
    protected $group = 'Notifications';
    protected $name = 'notification:process';
    protected $description = 'Alias for notifications:process (runs scheduled campaigns).';
    protected $usage = 'notification:process [--purge] [--purge-days 30]';

    public function run(array $params)
    {
        $cmd = new ProcessNotifications($this->logger, $this->commands);
        return $cmd->run($params);
    }
}
