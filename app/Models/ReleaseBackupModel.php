<?php

namespace App\Models;

use CodeIgniter\Model;

class ReleaseBackupModel extends Model
{
    protected $table            = 'release_backups';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'version_id', 'current_prod_version', 'current_build_number',
        'apk_aab_reference', 'ipa_reference', 'database_backup',
        'api_backup', 'config_backup', 'backup_date',
        'verified_by', 'verified_at', 'rollback_version', 'notes'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';

    /**
     * Get backup record for a version
     */
    public function getBackupByVersion(int $versionId): ?array
    {
        return $this->db->table('release_backups rb')
            ->select('rb.*, e.name as verifier_name')
            ->join('employees e', 'e.id = rb.verified_by', 'left')
            ->where('rb.version_id', $versionId)
            ->orderBy('rb.created_at', 'DESC')
            ->get()
            ->getRowArray();
    }

    /**
     * Verify a backup record
     */
    public function verifyBackup(int $id, int $userId): bool
    {
        return $this->update($id, [
            'verified_by' => $userId,
            'verified_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Check if backup is verified for a version
     */
    public function isBackupVerified(int $versionId): bool
    {
        $backup = $this->where('version_id', $versionId)
            ->where('verified_by IS NOT NULL')
            ->first();
        return $backup !== null;
    }
}
