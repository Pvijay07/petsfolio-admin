<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVideoAndScreenToBanners extends Migration
{
    public function up()
    {
<<<<<<< HEAD
        $db = \Config\Database::connect();

        // 1. Ensure 'id' is PRIMARY KEY and AUTO_INCREMENT if not already set
        $result = $db->query("SHOW KEYS FROM `banners` WHERE Key_name = 'PRIMARY'")->getResult();
        if (empty($result)) {
            $db->query("ALTER TABLE `banners` MODIFY COLUMN `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY");
        }

        // 2. Add columns if not already existing
        $fields = [
            'media_type' => [
=======
        $fields = [];

        if (!$this->db->fieldExists('media_type', 'banners')) {
            $fields['media_type'] = [
>>>>>>> origin/main
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'image',
                'null'       => false,
<<<<<<< HEAD
                'after'      => 'link_id',
            ],
            'banner_video' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
                'after'      => 'media_type',
            ],
            'video_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
                'after'      => 'banner_video',
            ],
            'screen' => [
=======
                'after'      => 'id',
            ];
        }

        if (!$this->db->fieldExists('banner_video', 'banners')) {
            $fields['banner_video'] = [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'banner_image',
            ];
        }

        if (!$this->db->fieldExists('video_url', 'banners')) {
            $fields['video_url'] = [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'banner_video',
            ];
        }

        if (!$this->db->fieldExists('screen', 'banners')) {
            $fields['screen'] = [
>>>>>>> origin/main
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'default'    => 'all',
                'null'       => false,
                'after'      => 'video_url',
<<<<<<< HEAD
            ],
            'banner_image' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
                'after'      => 'screen',
            ],
            'show_popup' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
                'after'      => 'banner_image',
            ],
            'popup_image' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
                'after'      => 'show_popup',
            ],
            'popup_link' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
                'after'      => 'popup_image',
            ],
        ];

        foreach ($fields as $fieldName => $fieldDef) {
            if (!$db->fieldExists($fieldName, 'banners')) {
                $this->forge->addColumn('banners', [$fieldName => $fieldDef]);
            }
        }

        // 3. Ensure defaults on existing columns for MySQL strict mode
        $db->query("ALTER TABLE `banners` MODIFY COLUMN `url` VARCHAR(255) NULL DEFAULT ''");
        $db->query("ALTER TABLE `banners` MODIFY COLUMN `type` VARCHAR(255) NULL DEFAULT ''");
        $db->query("ALTER TABLE `banners` MODIFY COLUMN `link_id` VARCHAR(255) NULL DEFAULT ''");
        $db->query("ALTER TABLE `banners` MODIFY COLUMN `is_active` TINYINT(1) NOT NULL DEFAULT 1");
        $db->query("ALTER TABLE `banners` MODIFY COLUMN `banner_type` ENUM('promo','service','seasonal','emergency') NOT NULL DEFAULT 'promo'");
        $db->query("ALTER TABLE `banners` MODIFY COLUMN `image` VARCHAR(255) NULL DEFAULT NULL");
=======
            ];
        }

        if (!empty($fields)) {
            $this->forge->addColumn('banners', $fields);
        }

        // Modify banner_image to be nullable so video banners don't require an image
        $this->forge->modifyColumn('banners', [
            'banner_image' => [
                'name'       => 'banner_image',
                'type'       => 'VARCHAR',
                'constraint' => 225,
                'null'       => true,
            ],
            'show_popup' => [
                'name'       => 'show_popup',
                'type'       => 'VARCHAR',
                'constraint' => 225,
                'null'       => true,
                'default'    => '0',
            ],
            'popup_image' => [
                'name'       => 'popup_image',
                'type'       => 'VARCHAR',
                'constraint' => 225,
                'null'       => true,
            ],
        ]);
>>>>>>> origin/main
    }

    public function down()
    {
<<<<<<< HEAD
        $columnsToDrop = [
            'media_type',
            'banner_video',
            'video_url',
            'screen',
            'banner_image',
            'show_popup',
            'popup_image',
            'popup_link',
        ];

        $db = \Config\Database::connect();
        foreach ($columnsToDrop as $column) {
            if ($db->fieldExists($column, 'banners')) {
                $this->forge->dropColumn('banners', $column);
            }
=======
        if ($this->db->fieldExists('media_type', 'banners')) {
            $this->forge->dropColumn('banners', 'media_type');
        }
        if ($this->db->fieldExists('banner_video', 'banners')) {
            $this->forge->dropColumn('banners', 'banner_video');
        }
        if ($this->db->fieldExists('video_url', 'banners')) {
            $this->forge->dropColumn('banners', 'video_url');
        }
        if ($this->db->fieldExists('screen', 'banners')) {
            $this->forge->dropColumn('banners', 'screen');
>>>>>>> origin/main
        }
    }
}
