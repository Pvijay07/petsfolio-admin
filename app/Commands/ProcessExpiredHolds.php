<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Services\SpViolationWorkflowService;

class ProcessExpiredHolds extends BaseCommand
{
    protected $group       = 'Violations';
    protected $name        = 'violations:process-holds';
    protected $description = 'Checks active provider account holds and automatically restores eligible account access when expired.';

    public function run(array $params)
    {
        CLI::write("Checking expired SP account holds...", 'yellow');

        $workflowService = new SpViolationWorkflowService();
        $result = $workflowService->processExpiredHolds();

        if ($result['processed'] > 0) {
            CLI::write("Processed {$result['processed']} hold(s). Restored {$result['restored_count']} provider account(s) to eligible status.", 'green');
        } else {
            CLI::write("No expired provider holds found.", 'white');
        }
    }
}
