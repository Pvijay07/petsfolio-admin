<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class UpdateBannersSchema extends BaseCommand
{
    protected $group = 'Database';
    protected $name = 'banners:update-schema';
    protected $description = 'Updates banners table schema to support video banners, screen configurations, and popups.';
    protected $usage = 'banners:update-schema';

    public function run(array $params)
    {
        CLI::write('=== Updating Banners Table Schema ===', 'cyan');
        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();

        // 1. Ensure 'id' is Primary Key and Auto-Increment
        try {
            $pkCheck = $db->query("SHOW KEYS FROM `banners` WHERE Key_name = 'PRIMARY'")->getResult();
            if (empty($pkCheck)) {
                CLI::write('Setting id as AUTO_INCREMENT PRIMARY KEY...', 'yellow');
                $db->query("ALTER TABLE `banners` MODIFY COLUMN `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY");
                CLI::write('Primary key configured.', 'green');
            } else {
                CLI::write('Primary key already exists on id.', 'light_gray');
            }
        } catch (\Throwable $e) {
            CLI::error('Note on Primary Key: ' . $e->getMessage());
        }

        // 2. Add columns if not already present
        $columns = [
            'media_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'image',
                'null'       => false,
            ],
            'banner_video' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
            ],
            'video_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
            ],
            'screen' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'default'    => 'all',
                'null'       => false,
            ],
            'banner_image' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
            ],
            'show_popup' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
            ],
            'popup_image' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
            ],
            'popup_link' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
            ],
        ];

        foreach ($columns as $columnName => $definition) {
            if (!$db->fieldExists($columnName, 'banners')) {
                CLI::write("Adding column '{$columnName}'...", 'yellow');
                $forge->addColumn('banners', [$columnName => $definition]);
                CLI::write("Column '{$columnName}' added successfully.", 'green');
            } else {
                CLI::write("Column '{$columnName}' already exists.", 'light_gray');
            }
        }

        // 3. Relax strict constraints on existing columns if needed
        try {
            $db->query("ALTER TABLE `banners` MODIFY COLUMN `url` VARCHAR(255) NULL DEFAULT ''");
            $db->query("ALTER TABLE `banners` MODIFY COLUMN `type` VARCHAR(255) NULL DEFAULT ''");
            $db->query("ALTER TABLE `banners` MODIFY COLUMN `link_id` VARCHAR(255) NULL DEFAULT ''");
            $db->query("ALTER TABLE `banners` MODIFY COLUMN `is_active` TINYINT(1) NOT NULL DEFAULT 1");
            $db->query("ALTER TABLE `banners` MODIFY COLUMN `banner_type` ENUM('promo','service','seasonal','emergency') NOT NULL DEFAULT 'promo'");
            $db->query("ALTER TABLE `banners` MODIFY COLUMN `image` VARCHAR(255) NULL DEFAULT NULL");
            CLI::write("Updated column constraints and defaults successfully.", 'green');
        } catch (\Throwable $e) {
            CLI::error('Note on column constraints: ' . $e->getMessage());
        }

        CLI::newLine();
        CLI::write('=== Current Banners Table Columns ===', 'cyan');
        $fields = $db->getFieldData('banners');
        foreach ($fields as $field) {
            CLI::write(sprintf(" - %-15s %-15s (Null: %s, Default: %s)", 
                $field->name, 
                $field->type, 
                $field->nullable ? 'YES' : 'NO', 
                $field->default ?? 'NULL'
            ), 'light_gray');
        }

        CLI::newLine();
        CLI::write('Banners schema update complete!', 'green');
    }
}
