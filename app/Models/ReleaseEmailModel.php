<?php

namespace App\Models;

use CodeIgniter\Model;

class ReleaseEmailModel extends Model
{
    protected $table            = 'release_emails';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'version_id',
        'subject',
        'body_html',
        'recipients',
        'sent_by',
        'sent_at',
        'status',
        'error_message',
        'created_at'
    ];

    protected $useTimestamps = false;
}
