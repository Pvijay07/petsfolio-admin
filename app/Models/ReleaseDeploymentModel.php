<?php

namespace App\Models;

use CodeIgniter\Model;

class ReleaseDeploymentModel extends Model
{
    protected $table            = 'release_deployments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'version_id',
        'deployment_status',
        'started_at',
        'deployed_at',
        'health_check_at',
        'monitoring_started',
        'completed_at',
        'deployed_by',
        'notes',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
