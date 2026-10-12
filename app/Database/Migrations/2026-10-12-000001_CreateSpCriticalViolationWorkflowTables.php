<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSpCriticalViolationWorkflowTables extends Migration
{
    public function up()
    {
        // 1. sp_violation_cases
        if (!$this->db->tableExists('sp_violation_cases')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'case_number' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'unique'     => true,
                ],
                'recording_id' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'unsigned'   => true,
                ],
                'call_id' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'unsigned'   => true,
                    'null'       => true,
                ],
                'provider_id' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'unsigned'   => true,
                ],
                'customer_id' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'unsigned'   => true,
                    'null'       => true,
                ],
                'service_type' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'default'    => 'walking',
                ],
                'violation_type' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'default'    => 'contact_share_and_off_app_pay',
                ],
                'severity' => [
                    'type'       => 'ENUM',
                    'constraint' => ['medium', 'high', 'critical'],
                    'default'    => 'critical',
                ],
                'evidence_snippet' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'evidence_timestamp' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => true,
                ],
                'has_active_services' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                ],
                'affected_bookings_count' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'default'    => 0,
                ],
                'affected_booking_ids' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'status' => [
                    'type'       => 'ENUM',
                    'constraint' => ['open', 'under_review', 'confirmed', 'dismissed', 'resolved'],
                    'default'    => 'open',
                ],
                'hold_id' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'unsigned'   => true,
                    'null'       => true,
                ],
                'penalty_id' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'unsigned'   => true,
                    'null'       => true,
                ],
                'reviewed_by' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'unsigned'   => true,
                    'null'       => true,
                ],
                'reviewed_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'admin_remarks' => [
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
            $this->forge->addKey('provider_id');
            $this->forge->addKey('status');
            $this->forge->addKey('recording_id');
            $this->forge->createTable('sp_violation_cases');
        }

        // 2. sp_account_holds
        if (!$this->db->tableExists('sp_account_holds')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'case_id' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'unsigned'   => true,
                ],
                'provider_id' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'unsigned'   => true,
                ],
                'hold_type' => [
                    'type'       => 'ENUM',
                    'constraint' => ['temporary_investigation_hold', 'penalty_hold', 'administrative_hold'],
                    'default'    => 'temporary_investigation_hold',
                ],
                'status' => [
                    'type'       => 'ENUM',
                    'constraint' => ['active', 'expired', 'released_by_admin', 'cancelled'],
                    'default'    => 'active',
                ],
                'reason' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                ],
                'started_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'expires_at' => [
                    'type' => 'DATETIME',
                    'null' => false,
                ],
                'released_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'released_by' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'unsigned'   => true,
                    'null'       => true,
                ],
                'release_note' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey(['provider_id', 'status']);
            $this->forge->addKey('expires_at');
            $this->forge->createTable('sp_account_holds');
        }

        // 3. sp_penalties
        if (!$this->db->tableExists('sp_penalties')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'case_id' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'unsigned'   => true,
                ],
                'provider_id' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'unsigned'   => true,
                ],
                'strike_count' => [
                    'type'       => 'TINYINT',
                    'constraint' => 3,
                    'unsigned'   => true,
                    'default'    => 1,
                ],
                'penalty_type' => [
                    'type'       => 'ENUM',
                    'constraint' => ['strike_warning', 'temporary_suspension_period', 'mandatory_compliance_acknowledgment'],
                    'default'    => 'strike_warning',
                ],
                'probation_until' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'notes' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
                'applied_by' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'unsigned'   => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('provider_id');
            $this->forge->createTable('sp_penalties');
        }

        // 4. service_providers fields
        $spFields = $this->db->getFieldNames('service_providers');
        $addCols = [];
        if (!in_array('is_on_hold', $spFields)) {
            $addCols['is_on_hold'] = [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'status',
            ];
        }
        if (!in_array('hold_expires_at', $spFields)) {
            $addCols['hold_expires_at'] = [
                'type'  => 'DATETIME',
                'null'  => true,
                'after' => 'is_on_hold',
            ];
        }
        if (!in_array('hold_reason', $spFields)) {
            $addCols['hold_reason'] = [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'hold_expires_at',
            ];
        }
        if (!empty($addCols)) {
            $this->forge->addColumn('service_providers', $addCols);
        }
    }

    public function down()
    {
        $this->forge->dropTable('sp_penalties', true);
        $this->forge->dropTable('sp_account_holds', true);
        $this->forge->dropTable('sp_violation_cases', true);
    }
}
