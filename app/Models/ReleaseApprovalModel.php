<?php

namespace App\Models;

use CodeIgniter\Model;

class ReleaseApprovalModel extends Model
{
    protected $table            = 'release_approvals';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'version_id', 'approval_type', 'status',
        'approved_by', 'approved_at', 'comments'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';

    /**
     * Approval type labels
     */
    protected $typeLabels = [
        'developer'       => 'Developer',
        'qa'              => 'QA',
        'product'         => 'Product / Management',
        'management'      => 'Management',
        'release_manager' => 'Release Manager',
    ];

    /**
     * Approval type icons
     */
    protected $typeIcons = [
        'developer'       => 'bi-code-slash',
        'qa'              => 'bi-clipboard-check',
        'product'         => 'bi-briefcase',
        'management'      => 'bi-building',
        'release_manager' => 'bi-rocket-takeoff',
    ];

    public function getTypeLabel(string $type): string
    {
        return $this->typeLabels[$type] ?? ucfirst($type);
    }

    public function getTypeIcon(string $type): string
    {
        return $this->typeIcons[$type] ?? 'bi-person-check';
    }

    /**
     * Initialize approval records for a version (creates pending rows for all roles)
     */
    public function initializeApprovals(int $versionId): bool
    {
        $existing = $this->where('version_id', $versionId)->countAllResults();
        if ($existing > 0) {
            return false; // Already initialized
        }

        $types = ['developer', 'qa', 'product', 'management', 'release_manager'];
        $batch = [];
        foreach ($types as $type) {
            $batch[] = [
                'version_id'    => $versionId,
                'approval_type' => $type,
                'status'        => 'pending',
                'created_at'    => date('Y-m-d H:i:s'),
            ];
        }

        return $this->db->table('release_approvals')->insertBatch($batch) !== false;
    }

    /**
     * Get all approvals for a version with approver info
     */
    public function getApprovalsByVersion(int $versionId): array
    {
        return $this->db->table('release_approvals ra')
            ->select('ra.*, e.name as approver_name')
            ->join('employees e', 'e.id = ra.approved_by', 'left')
            ->where('ra.version_id', $versionId)
            ->orderBy('ra.id', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Submit an approval decision
     */
    public function submitApproval(int $versionId, string $type, int $userId, string $status, ?string $comments = null): bool
    {
        return $this->db->table('release_approvals')
            ->where('version_id', $versionId)
            ->where('approval_type', $type)
            ->update([
                'status'      => $status,
                'approved_by' => $userId,
                'approved_at' => date('Y-m-d H:i:s'),
                'comments'    => $comments,
            ]);
    }

    /**
     * Check if all required approvals are in
     */
    public function areAllApproved(int $versionId): bool
    {
        $pending = $this->where('version_id', $versionId)
            ->where('status !=', 'approved')
            ->countAllResults();
        return $pending === 0;
    }

    /**
     * Check if a specific approval type is approved
     */
    public function isApproved(int $versionId, string $type): bool
    {
        $row = $this->where('version_id', $versionId)
            ->where('approval_type', $type)
            ->where('status', 'approved')
            ->first();
        return $row !== null;
    }

    /**
     * Get approval summary for dashboard display
     */
    public function getApprovalSummary(int $versionId): array
    {
        $approvals = $this->getApprovalsByVersion($versionId);
        $summary = ['total' => 0, 'approved' => 0, 'rejected' => 0, 'pending' => 0];
        foreach ($approvals as $a) {
            $summary['total']++;
            $summary[$a['status']]++;
        }
        return $summary;
    }
}
