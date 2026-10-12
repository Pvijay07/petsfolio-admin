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
        'version_id', 'comment_type', 'comment', 'created_by'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';

    /**
     * Comment type labels
     */
    protected $typeLabels = [
        'developer' => 'Developer Comment',
        'qa'        => 'QA Comment',
        'approval'  => 'Approval Comment',
        'general'   => 'General Comment',
    ];

    /**
     * Comment type colors
     */
    protected $typeColors = [
        'developer' => 'primary',
        'qa'        => 'info',
        'approval'  => 'success',
        'general'   => 'secondary',
    ];

    public function getTypeLabel(string $type): string
    {
        return $this->typeLabels[$type] ?? ucfirst($type);
    }

    public function getTypeColor(string $type): string
    {
        return $this->typeColors[$type] ?? 'secondary';
    }

    /**
     * Get comments by version, optionally filtered by type
     */
    public function getCommentsByVersion(int $versionId, ?string $type = null): array
    {
        $builder = $this->db->table('release_comments rc')
            ->select('rc.*, e.name as author_name')
            ->join('employees e', 'e.id = rc.created_by', 'left')
            ->where('rc.version_id', $versionId)
            ->orderBy('rc.created_at', 'DESC');

        if ($type) {
            $builder->where('rc.comment_type', $type);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get comment counts by type for a version
     */
    public function getCommentCounts(int $versionId): array
    {
        $results = $this->db->table('release_comments')
            ->select('comment_type, COUNT(*) as count')
            ->where('version_id', $versionId)
            ->groupBy('comment_type')
            ->get()
            ->getResultArray();

        $counts = ['developer' => 0, 'qa' => 0, 'approval' => 0, 'general' => 0];
        foreach ($results as $row) {
            $counts[$row['comment_type']] = (int)$row['count'];
        }
        $counts['total'] = array_sum($counts);
        return $counts;
    }
}
