<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAbuseFieldsToCallAnalysis extends Migration
{
    public function up()
    {
        $this->forge->addColumn('call_analysis', [
            'has_abuse' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
                'after'      => 'has_complaint',
            ],
            'abuse_instances' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'has_abuse',
            ],
            'negative_speech_flags' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'abuse_instances',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('call_analysis', 'has_abuse');
        $this->forge->dropColumn('call_analysis', 'abuse_instances');
        $this->forge->dropColumn('call_analysis', 'negative_speech_flags');
    }
}
