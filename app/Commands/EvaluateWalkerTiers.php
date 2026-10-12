<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Controllers\Cli\WalkerTierEvaluator;

/**
 * EvaluateWalkerTiers Command
 *
 * Runs the Walker Tier Evaluation Engine across all service providers.
 * Command: php spark walker:evaluate-tiers
 */
class EvaluateWalkerTiers extends BaseCommand
{
    protected $group       = 'Walker';
    protected $name        = 'walker:evaluate-tiers';
    protected $description = 'Evaluates walker metrics, effective experience, SLA gates, grace periods, and ranking scores.';
    protected $usage       = 'walker:evaluate-tiers';

    public function run(array $params)
    {
        CLI::write('=== Petsfolio Walker Tier Evaluation Engine ===', 'cyan');
        CLI::write('Started at: ' . date('Y-m-d H:i:s'), 'light_gray');
        CLI::newLine();

        try {
            $evaluator = new WalkerTierEvaluator();
            $result = $evaluator->evaluate();

            CLI::write('✅ Walker tier evaluation executed successfully.', 'green');
            CLI::write($result, 'light_gray');
        } catch (\Exception $e) {
            CLI::error('Error: ' . $e->getMessage());
            log_message('error', 'EvaluateWalkerTiers command error: ' . $e->getMessage());
        }

        CLI::newLine();
        CLI::write('Completed at: ' . date('Y-m-d H:i:s'), 'light_gray');
    }
}
