<?php

namespace App\Models;

use CodeIgniter\Model;

class ReleaseCommentModel extends Model
{
    protected $table            = 'release_comments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'version_id',
        'comment_type',
        'comment',
        'created_by',
        'created_at'
    ];

    protected $useTimestamps = false;
}
