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
        'version_id',
        'current_prod_version',
        'current_build_number',
        'apk_aab_reference',
        'ipa_reference',
        'database_backup',
        'api_backup',
        'config_backup',
        'backup_date',
        'verified_by',
        'verified_at',
        'rollback_version',
        'notes',
        'created_at'
    ];

    protected $useTimestamps = false;
}
