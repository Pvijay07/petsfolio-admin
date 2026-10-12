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
        'version_id',
        'approval_type',
        'status',
        'approved_by',
        'approved_at',
        'comments',
        'created_at'
    ];

    protected $useTimestamps = false;
}
