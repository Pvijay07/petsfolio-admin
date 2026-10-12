<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;

class AddSlugToRoles extends Migration
{
    public function up()
    {
        $db = Database::connect();
        if (!$db->fieldExists('slug', 'roles')) {
            $this->forge->addColumn('roles', [
                'slug' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'after'      => 'name',
                ],
            ]);

            // Add unique index on slug
            try {
                $db->query('ALTER TABLE `roles` ADD UNIQUE KEY `roles_slug_unique` (`slug`)');
            } catch (\Throwable $e) {
                // Ignore if index already exists
            }
        }

        // Backfill existing roles with slugs based on name
        $roles = $db->table('roles')->get()->getResultArray();
        foreach ($roles as $r) {
            if (empty($r['slug'])) {
                $baseSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9_]+/', '_', $r['name'] ?? ''), '_'));
                if (empty($baseSlug)) {
                    $baseSlug = 'role_' . $r['id'];
                }
                $slug = $baseSlug;
                $counter = 1;
                while ($db->table('roles')->where('slug', $slug)->where('id !=', $r['id'])->countAllResults() > 0) {
                    $counter++;
                    $slug = $baseSlug . '_' . $counter;
                }

                $db->table('roles')->where('id', $r['id'])->update(['slug' => $slug]);
            }
        }
    }

    public function down()
    {
        $db = Database::connect();
        if ($db->fieldExists('slug', 'roles')) {
            $this->forge->dropColumn('roles', 'slug');
        }
    }
}
