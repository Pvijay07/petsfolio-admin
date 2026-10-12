<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class UpdateBannersSchema extends BaseCommand
{
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
    }
}
