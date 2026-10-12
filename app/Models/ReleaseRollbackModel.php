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
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
