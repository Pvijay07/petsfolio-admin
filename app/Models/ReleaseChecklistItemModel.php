<?php

namespace App\Models;

use CodeIgniter\Model;

class ReleaseChecklistItemModel extends Model
{
    protected $table            = 'release_checklist_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'checklist_id',
        'version_id',
        'item_text',
        'status',
        'checked_by',
        'checked_at',
        'comment',
        'sort_order',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
