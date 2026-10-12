<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBusinessEventsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'event_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
            ],
            'event_category' => [
                'type'       => 'VARCHAR',
                'constraint' => 32,
            ],
            'user_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'user_type' => [
                'type'       => 'ENUM',
                'constraint' => ['client', 'provider', 'admin', 'system'],
                'default'    => 'client',
            ],
            'booking_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'service_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'provider_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'reason' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'source' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'system',
            ],
            'metadata' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'event_time' => [
                'type' => 'DATETIME',
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('event_type');
        $this->forge->addKey('event_category');
        $this->forge->addKey('event_time');
        $this->forge->addKey('booking_id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('provider_id');
        $this->forge->addKey('service_id');
        $this->forge->addKey(['service_id', 'event_type', 'event_time']);

        $this->forge->createTable('business_events', true);
    }

    public function down()
    {
        $this->forge->dropTable('business_events', true);
    }
}
