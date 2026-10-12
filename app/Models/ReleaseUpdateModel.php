<?php

namespace App\Models;

use CodeIgniter\Model;

class ReleaseUpdateModel extends Model
{
    protected $table            = 'release_updates';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'version_id', 'category', 'title', 'description',
        'ticket_id', 'developer_id', 'status', 'comments', 'sort_order'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Category display labels
     */
    protected $categoryLabels = [
        'new_features'  => 'New Features',
        'enhancements'  => 'Feature Enhancements',
        'bug_fixes'     => 'Bug Fixes',
        'ui_ux'         => 'UI/UX Changes',
        'api_backend'   => 'API/Backend Changes',
        'database'      => 'Database Changes',
        'security'      => 'Security Changes',
        'third_party'   => 'AI / Third-Party Changes',
    ];

    /**
     * Category icons (Bootstrap Icons)
     */
    protected $categoryIcons = [
        'new_features'  => 'bi-stars',
        'enhancements'  => 'bi-arrow-up-circle',
        'bug_fixes'     => 'bi-bug',
        'ui_ux'         => 'bi-palette',
        'api_backend'   => 'bi-code-slash',
        'database'      => 'bi-database',
        'security'      => 'bi-shield-lock',
        'third_party'   => 'bi-plug',
    ];

    public function getCategoryLabel(string $category): string
    {
        return $this->categoryLabels[$category] ?? ucfirst(str_replace('_', ' ', $category));
    }

    public function getCategoryIcon(string $category): string
    {
        return $this->categoryIcons[$category] ?? 'bi-circle';
    }

    public function getAllCategories(): array
    {
        return $this->categoryLabels;
    }

    /**
     * Get updates grouped by category for a version
     */
    public function getUpdatesByVersion(int $versionId): array
    {
        $updates = $this->db->table('release_updates ru')
            ->select('ru.*, e.name as developer_name')
            ->join('employees e', 'e.id = ru.developer_id', 'left')
            ->where('ru.version_id', $versionId)
            ->orderBy('ru.category', 'ASC')
            ->orderBy('ru.sort_order', 'ASC')
            ->get()
            ->getResultArray();

        $grouped = [];
        foreach ($this->categoryLabels as $key => $label) {
            $grouped[$key] = [
                'label' => $label,
                'icon'  => $this->categoryIcons[$key],
                'items' => [],
            ];
        }

        foreach ($updates as $update) {
            $cat = $update['category'];
            if (isset($grouped[$cat])) {
                $grouped[$cat]['items'][] = $update;
            }
        }

        return $grouped;
    }

    /**
     * Get update stats (counts per category)
     */
    public function getUpdateStats(int $versionId): array
    {
        $results = $this->db->table('release_updates')
            ->select('category, COUNT(*) as count')
            ->where('version_id', $versionId)
            ->groupBy('category')
            ->get()
            ->getResultArray();

        $stats = [];
        foreach ($results as $row) {
            $stats[$row['category']] = (int)$row['count'];
        }
        return $stats;
    }

    /**
     * Get total updates count for a version
     */
    public function getTotalCount(int $versionId): int
    {
        return $this->where('version_id', $versionId)->countAllResults();
    }
}
