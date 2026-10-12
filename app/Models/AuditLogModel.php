<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table            = 'audit_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = false;
    protected $allowedFields    = [
        'entity_type',
        'entity_id',
        'actor_type',
        'actor_id',
        'user_name',
        'user_email',
        'role_name',
        'action',
        'method',
        'url',
        'description',
        'old_state',
        'new_state',
        'metadata',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    /**
     * Builds base query with filters
     */
    protected function buildFilterQuery(array $filters = [])
    {
        $builder = $this->db->table($this->table);

        if (!empty($filters['user_id'])) {
            $builder->where('actor_id', $filters['user_id']);
        }

        if (!empty($filters['action'])) {
            if ($filters['action'] === 'delete') {
                $builder->whereIn('action', ['delete', 'cancel', 'remove', 'destroy', 'reject']);
            } elseif ($filters['action'] === 'update') {
                $builder->whereIn('action', ['update', 'edit', 'change', 'modify']);
            } else {
                $builder->where('action', $filters['action']);
            }
        }

        if (!empty($filters['entity_type'])) {
            $builder->where('entity_type', $filters['entity_type']);
        }

        if (!empty($filters['date_from'])) {
            $builder->where('created_at >=', $filters['date_from'] . ' 00:00:00');
        }

        if (!empty($filters['date_to'])) {
            $builder->where('created_at <=', $filters['date_to'] . ' 23:59:59');
        }

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $builder->groupStart()
                ->like('user_name', $search)
                ->orLike('user_email', $search)
                ->orLike('description', $search)
                ->orLike('entity_id', $search)
                ->orLike('url', $search)
                ->orLike('ip_address', $search)
                ->groupEnd();
        }

        return $builder;
    }

    /**
     * Returns paginated filtered logs
     */
    public function getFilteredLogs(array $filters = [], int $limit = 25, int $offset = 0): array
    {
        $builder = $this->buildFilterQuery($filters);
        return $builder->orderBy('id', 'DESC')
            ->limit($limit, $offset)
            ->get()
            ->getResult();
    }

    /**
     * Counts total logs matching filters
     */
    public function countFilteredLogs(array $filters = []): int
    {
        $builder = $this->buildFilterQuery($filters);
        return $builder->countAllResults();
    }

    /**
     * Metric KPI counters for the dashboard
     */
    public function getLogMetrics(): array
    {
        $today = date('Y-m-d');

        $totalLogs = $this->countAllResults();

        $loginsToday = $this->where('action', 'login')
            ->where('created_at >=', $today . ' 00:00:00')
            ->where('created_at <=', $today . ' 23:59:59')
            ->countAllResults();

        $updatesCount = $this->whereIn('action', ['update', 'edit', 'change', 'modify'])
            ->countAllResults();

        $deletesCount = $this->whereIn('action', ['delete', 'cancel', 'remove', 'destroy', 'reject'])
            ->countAllResults();

        return [
            'total_logs'    => $totalLogs,
            'logins_today'  => $loginsToday,
            'updates_count' => $updatesCount,
            'deletes_count' => $deletesCount,
        ];
    }

    /**
     * Distinct users for filter dropdown
     */
    public function getDistinctActors(): array
    {
        return $this->db->table($this->table)
            ->select('actor_id, user_name, user_email, role_name, COUNT(*) as activity_count')
            ->where('actor_id IS NOT NULL', null, false)
            ->where('actor_id !=', '')
            ->groupBy('actor_id')
            ->orderBy('activity_count', 'DESC')
            ->get()
            ->getResult();
    }

    /**
     * Distinct entity types for filter dropdown
     */
    public function getDistinctEntityTypes(): array
    {
        $results = $this->db->table($this->table)
            ->select('entity_type, COUNT(*) as count')
            ->where('entity_type IS NOT NULL', null, false)
            ->groupBy('entity_type')
            ->orderBy('count', 'DESC')
            ->get()
            ->getResult();

        return array_map(function ($row) {
            return $row->entity_type;
        }, $results);
    }
}
