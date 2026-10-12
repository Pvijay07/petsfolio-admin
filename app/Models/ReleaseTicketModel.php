<?php

namespace App\Models;

use CodeIgniter\Model;

class ReleaseTicketModel extends Model
{
    protected $table            = 'release_tickets';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'version_id',
        'ticket_id',
        'description',
        'status',
        'priority',
        'assigned_to',
        'created_at'
    ];

    protected $useTimestamps = false;
}
