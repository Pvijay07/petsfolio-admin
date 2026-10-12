<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class UpdateBannersSchema extends BaseCommand
{
<<<<<<< HEAD
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
=======
    protected $group       = 'Database';
    protected $name        = 'db:update-banners-schema';
    protected $description = 'Safely updates banners table schema to add video and screen configuration support.';
    protected $usage       = 'db:update-banners-schema';

    public function run(array $params)
    {
        CLI::write('=== Banners Schema Update Engine ===', 'cyan');
        CLI::write('Started at: ' . date('Y-m-d H:i:s'), 'light_gray');

        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();

        try {
            // Check and add media_type
            if (!$db->fieldExists('media_type', 'banners')) {
                CLI::write('  Adding `media_type` column...', 'yellow');
                $db->query("ALTER TABLE `banners` ADD COLUMN `media_type` VARCHAR(20) NOT NULL DEFAULT 'image' AFTER `id`");
                CLI::write('  ✅ `media_type` added.', 'green');
            } else {
                CLI::write('  ℹ️ `media_type` already exists.', 'light_gray');
            }

            // Check and add banner_video
            if (!$db->fieldExists('banner_video', 'banners')) {
                CLI::write('  Adding `banner_video` column...', 'yellow');
                $db->query("ALTER TABLE `banners` ADD COLUMN `banner_video` VARCHAR(255) NULL DEFAULT NULL AFTER `banner_image`");
                CLI::write('  ✅ `banner_video` added.', 'green');
            } else {
                CLI::write('  ℹ️ `banner_video` already exists.', 'light_gray');
            }

            // Check and add video_url
            if (!$db->fieldExists('video_url', 'banners')) {
                CLI::write('  Adding `video_url` column...', 'yellow');
                $db->query("ALTER TABLE `banners` ADD COLUMN `video_url` VARCHAR(255) NULL DEFAULT NULL AFTER `banner_video`");
                CLI::write('  ✅ `video_url` added.', 'green');
            } else {
                CLI::write('  ℹ️ `video_url` already exists.', 'light_gray');
            }

            // Check and add screen
            if (!$db->fieldExists('screen', 'banners')) {
                CLI::write('  Adding `screen` column...', 'yellow');
                $db->query("ALTER TABLE `banners` ADD COLUMN `screen` VARCHAR(255) NOT NULL DEFAULT 'all' AFTER `video_url`");
                CLI::write('  ✅ `screen` added.', 'green');
            } else {
                CLI::write('  ℹ️ `screen` already exists.', 'light_gray');
            }

            // Make banner_image and popup columns nullable/flexible
            CLI::write('  Modifying `banner_image` to be nullable...', 'yellow');
            $db->query("ALTER TABLE `banners` MODIFY COLUMN `banner_image` VARCHAR(225) NULL");
            $db->query("ALTER TABLE `banners` MODIFY COLUMN `show_popup` VARCHAR(225) NULL DEFAULT '0'");
            $db->query("ALTER TABLE `banners` MODIFY COLUMN `popup_image` VARCHAR(225) NULL");
            CLI::write('  ✅ Columns modified successfully.', 'green');

            CLI::newLine();
            CLI::write('✅ Banners table schema updated successfully!', 'green');

        } catch (\Throwable $e) {
            CLI::write('  ❌ Error: ' . $e->getMessage(), 'red');
        }
>>>>>>> origin/main
    }
}
