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
        'version_id', 'ticket_id', 'description', 'status',
        'priority', 'assigned_to'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';

    /**
     * Get tickets by version with assignee info
     */
    public function getTicketsByVersion(int $versionId): array
    {
        return $this->db->table('release_tickets rt')
            ->select('rt.*, e.name as assigned_name')
            ->join('employees e', 'e.id = rt.assigned_to', 'left')
            ->where('rt.version_id', $versionId)
            ->orderBy('rt.priority', 'ASC')
            ->orderBy('rt.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get ticket stats for a version
     */
    public function getTicketStats(int $versionId): array
    {
        $results = $this->db->table('release_tickets')
            ->select('status, COUNT(*) as count')
            ->where('version_id', $versionId)
            ->groupBy('status')
            ->get()
            ->getResultArray();

        $stats = ['open' => 0, 'in_progress' => 0, 'fixed' => 0, 'completed' => 0, 'wontfix' => 0];
        foreach ($results as $row) {
            $stats[$row['status']] = (int)$row['count'];
        }
        $stats['total'] = array_sum($stats);
        return $stats;
    }
}
