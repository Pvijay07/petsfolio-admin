<?php

namespace App\Models;

use CodeIgniter\Model;

class ReleaseRollbackModel extends Model
{
    protected $table            = 'release_rollbacks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
<<<<<<< HEAD
        'version_id', 'current_live_version', 'rollback_to_version',
        'rollback_reason', 'critical_issue', 'approved_by', 'approved_at',
        'started_at', 'completed_at', 'status', 'post_rollback_notes'
=======
        'version_id',
        'current_live_version',
        'rollback_to_version',
        'rollback_reason',
        'critical_issue',
        'approved_by',
        'approved_at',
        'started_at',
        'completed_at',
        'status',
        'post_rollback_notes',
        'created_at',
        'updated_at'
>>>>>>> origin/main
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
<<<<<<< HEAD

    /**
     * Get rollback record for a version
     */
    public function getRollbackByVersion(int $versionId): ?array
    {
        return $this->db->table('release_rollbacks rr')
            ->select('rr.*, e.name as approver_name')
            ->join('employees e', 'e.id = rr.approved_by', 'left')
            ->where('rr.version_id', $versionId)
            ->orderBy('rr.created_at', 'DESC')
            ->get()
            ->getRowArray();
    }

    /**
     * Initiate a rollback
     */
    public function initiateRollback(array $data): int
    {
        $data['status'] = 'requested';
        $this->insert($data);
        return $this->getInsertID();
    }

    /**
     * Approve a rollback
     */
    public function approveRollback(int $id, int $userId): bool
    {
        return $this->update($id, [
            'status'      => 'approved',
            'approved_by' => $userId,
            'approved_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Start the rollback process
     */
    public function startRollback(int $id): bool
    {
        return $this->update($id, [
            'status'     => 'in_progress',
            'started_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Complete the rollback
     */
    public function completeRollback(int $id, ?string $notes = null): bool
    {
        $data = [
            'status'       => 'completed',
            'completed_at' => date('Y-m-d H:i:s'),
        ];
        if ($notes) {
            $data['post_rollback_notes'] = $notes;
        }
        return $this->update($id, $data);
    }

    /**
     * Get rollback timeline events for audit display
     */
    public function getRollbackTimeline(int $versionId): array
    {
        $rollback = $this->getRollbackByVersion($versionId);
        if (!$rollback) return [];

        $timeline = [];

        $timeline[] = [
            'event' => "{$rollback['current_live_version']} Released",
            'time'  => $rollback['created_at'],
            'icon'  => 'bi-rocket-takeoff',
            'color' => 'success',
        ];

        if ($rollback['rollback_reason']) {
            $timeline[] = [
                'event' => 'Issue Detected: ' . mb_substr($rollback['rollback_reason'], 0, 100),
                'time'  => $rollback['created_at'],
                'icon'  => 'bi-exclamation-triangle',
                'color' => 'warning',
            ];
        }

        if ($rollback['approved_at']) {
            $timeline[] = [
                'event' => 'Rollback approved by ' . ($rollback['approver_name'] ?? 'Admin'),
                'time'  => $rollback['approved_at'],
                'icon'  => 'bi-check-circle',
                'color' => 'primary',
            ];
        }

        if ($rollback['started_at']) {
            $timeline[] = [
                'event' => 'Rollback initiated',
                'time'  => $rollback['started_at'],
                'icon'  => 'bi-arrow-counterclockwise',
                'color' => 'danger',
            ];
        }

        if ($rollback['completed_at']) {
            $timeline[] = [
                'event' => "{$rollback['rollback_to_version']} restored — System stabilized",
                'time'  => $rollback['completed_at'],
                'icon'  => 'bi-shield-check',
                'color' => 'success',
            ];
        }

        return $timeline;
    }
=======
>>>>>>> origin/main
}
