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
        'version_id',
        'user_id',
        'action',
        'old_value',
        'new_value',
        'description',
        'ip_address',
        'user_agent',
        'created_at'
    ];

    protected $useTimestamps = false;

    public static function log($versionId, $action, $description = null, $oldValue = null, $newValue = null)
    {
        $session = session();
        $user = $session->get('user');
        $userId = is_object($user) ? ($user->id ?? 1) : ($user['id'] ?? 1);
        $request = service('request');

        $model = new self();
        return $model->insert([
            'version_id'  => $versionId,
            'user_id'     => $userId ?: 1,
            'action'      => $action,
            'old_value'   => is_array($oldValue) ? json_encode($oldValue) : (string)$oldValue,
            'new_value'   => is_array($newValue) ? json_encode($newValue) : (string)$newValue,
            'description' => $description,
            'ip_address'  => $request ? $request->getIPAddress() : '127.0.0.1',
            'user_agent'  => $request && $request->getUserAgent() ? (string)$request->getUserAgent() : 'CLI/Web',
            'created_at'  => date('Y-m-d H:i:s')
        ]);
    }
}
