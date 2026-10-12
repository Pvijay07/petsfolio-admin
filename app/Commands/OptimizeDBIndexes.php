<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * OptimizeDBIndexes Command
 *
 * Checks and creates composite indexes across booking and financial ledger tables.
 * Command: php spark db:optimize-indexes
 */
class OptimizeDBIndexes extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:optimize-indexes';
    protected $description = 'Safely checks and adds high-performance composite indexes for AI / BI queries.';
    protected $usage       = 'db:optimize-indexes';

    public function run(array $params)
    {
        CLI::write('=== Petsfolio Database Index Optimization Engine ===', 'cyan');
        CLI::write('Started at: ' . date('Y-m-d H:i:s'), 'light_gray');
        CLI::newLine();

        $db = \Config\Database::connect();

        $indexesToVerify = [
            [
                'table'   => 'walking_service_bookings',
                'name'    => 'idx_walk_created_status_del',
                'columns' => 'created_at, status, is_deleted'
            ],
            [
                'table'   => 'grooming_service_bookings',
                'name'    => 'idx_groom_created_status_del',
                'columns' => 'created_at, status, is_deleted'
            ],
            [
                'table'   => 'training_service_bookings',
                'name'    => 'idx_train_created_status_del',
                'columns' => 'created_at, status, is_deleted'
            ],
            [
                'table'   => 'sitting_service_bookings',
                'name'    => 'idx_sit_created_status_del',
                'columns' => 'created_at, status, is_deleted'
            ],
            [
                'table'   => 'platform_wallet_histories',
                'name'    => 'idx_platform_comm_created',
                'columns' => 'created_at, service_type'
            ],
            [
                'table'   => 'sp_wallet_histories',
                'name'    => 'idx_sp_wallet_prov_cat_type',
                'columns' => 'provider_id, category, transaction_type'
            ]
        ];

        foreach ($indexesToVerify as $idx) {
            $table   = $idx['table'];
            $idxName = $idx['name'];
            $cols    = $idx['columns'];

            try {
                // Check if index exists
                $res = $db->query("SHOW INDEX FROM `{$table}` WHERE Key_name = '{$idxName}'")->getResultArray();
                if (!empty($res)) {
                    CLI::write("  ℹ️ Index `{$idxName}` on `{$table}` already exists. Skipping.", 'light_gray');
                } else {
                    CLI::write("  ⚡ Creating index `{$idxName}` on `{$table}` ({$cols})...", 'yellow');
                    $db->query("ALTER TABLE `{$table}` ADD INDEX `{$idxName}` ({$cols})");
                    CLI::write("  ✅ Successfully created `{$idxName}` on `{$table}`.", 'green');
                }
            } catch (\Throwable $e) {
                CLI::write("  ❌ Error on `{$table}`: " . $e->getMessage(), 'red');
            }
        }

        CLI::newLine();
        CLI::write('✅ Index optimization routine finished.', 'green');
    }
}
