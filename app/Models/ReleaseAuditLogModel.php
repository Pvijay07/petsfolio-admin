<?php

namespace App\Models;

use CodeIgniter\Model;

class ReleaseAuditLogModel extends Model
{
    protected $table            = 'release_audit_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'version_id', 'user_id', 'action', 'old_value',
        'new_value', 'description', 'ip_address', 'user_agent'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';

    /**
     * Log an audit event
     */
    public function log(
        int $versionId,
        int $userId,
        string $action,
        ?string $oldValue = null,
        ?string $newValue = null,
        ?string $description = null
    ): int {
        $request = service('request');

        $this->insert([
            'version_id'  => $versionId,
            'user_id'     => $userId,
            'action'      => $action,
            'old_value'   => $oldValue,
            'new_value'   => $newValue,
            'description' => $description,
            'ip_address'  => $request->getIPAddress(),
            'user_agent'  => $request->getUserAgent()->getAgentString(),
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        return $this->getInsertID();
    }

    /**
     * Get audit logs for a version (chronological)
     */
    public function getLogsByVersion(int $versionId, int $limit = 100): array
    {
        return $this->db->table('release_audit_logs ral')
            ->select('ral.*, e.name as user_name')
            ->join('employees e', 'e.id = ral.user_id', 'left')
            ->where('ral.version_id', $versionId)
            ->orderBy('ral.created_at', 'ASC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Get recent activity across all versions (for dashboard)
     */
    public function getRecentActivity(int $limit = 50): array
    {
        return $this->db->table('release_audit_logs ral')
            ->select('ral.*, e.name as user_name, rv.version_number')
            ->join('employees e', 'e.id = ral.user_id', 'left')
            ->join('release_versions rv', 'rv.id = ral.version_id', 'left')
            ->orderBy('ral.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Get action summary for a version
     */
    public function getActionSummary(int $versionId): array
    {
        return $this->db->table('release_audit_logs')
            ->select('action, COUNT(*) as count')
            ->where('version_id', $versionId)
            ->groupBy('action')
            ->get()
            ->getResultArray();
    }
}
