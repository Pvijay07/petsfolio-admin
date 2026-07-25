<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCallAnalysisTables extends Migration
{
    public function up()
    {
        // 1. call_recordings
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'booking_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'provider_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'customer_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'call_type' => [
                'type'       => 'ENUM',
                'constraint' => ['inbound', 'outbound', 'verification', 'support'],
                'default'    => 'inbound',
            ],
            'recording_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
            ],
            'duration' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['uploaded', 'queued', 'processing', 'completed', 'failed'],
                'default'    => 'uploaded',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('provider_id');
        $this->forge->addKey('booking_id');
        $this->forge->createTable('call_recordings', true);

        // 2. call_analysis_jobs
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'recording_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['queued', 'processing', 'completed', 'failed'],
                'default'    => 'queued',
            ],
            'retry_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'started_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'completed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'error' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('recording_id');
        $this->forge->addKey('status');
        $this->forge->createTable('call_analysis_jobs', true);

        // 3. call_analysis
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'recording_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'transcript' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'summary' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'overall_rating' => [
                'type'       => 'DECIMAL',
                'constraint' => '4,2',
                'default'    => 0.00,
            ],
            'communication' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'professionalism' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'empathy' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'compliance' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'customer_sentiment' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'provider_sentiment' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'resolution' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'risk_score' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'strengths' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'weaknesses' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'recommendations' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'tags' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('recording_id', false, true); // UNIQUE
        $this->forge->createTable('call_analysis', true);

        // 4. call_keywords
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'analysis_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'keyword' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'frequency' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('analysis_id');
        $this->forge->addKey('keyword');
        $this->forge->createTable('call_keywords', true);
    }

    public function down()
    {
        $this->forge->dropTable('call_keywords', true);
        $this->forge->dropTable('call_analysis', true);
        $this->forge->dropTable('call_analysis_jobs', true);
        $this->forge->dropTable('call_recordings', true);
    }
}
