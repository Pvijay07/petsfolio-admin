<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVideoAndScreenToBanners extends Migration
{
    public function up()
    {
        $fields = [];

        if (!$this->db->fieldExists('media_type', 'banners')) {
            $fields['media_type'] = [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'image',
                'null'       => false,
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
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'default'    => 'all',
                'null'       => false,
                'after'      => 'video_url',
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
    }

    public function down()
    {
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
        }
    }
}
