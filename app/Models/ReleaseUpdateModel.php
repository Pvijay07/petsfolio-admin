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
<<<<<<< HEAD
        'version_id', 'category', 'title', 'description',
        'ticket_id', 'developer_id', 'status', 'comments', 'sort_order'
=======
        'version_id',
        'category',
        'title',
        'description',
        'ticket_id',
        'developer_id',
        'status',
        'comments',
        'sort_order',
        'created_at',
        'updated_at'
>>>>>>> origin/main
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

<<<<<<< HEAD
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
=======
    public static function getCategories()
    {
        return [
            'new_features' => [
                'label' => 'New Features',
                'badge' => 'bg-success',
                'icon'  => 'bi-plus-circle-fill'
            ],
            'enhancements' => [
                'label' => 'Feature Enhancements',
                'badge' => 'bg-info',
                'icon'  => 'bi-arrow-up-right-circle-fill'
            ],
            'bug_fixes' => [
                'label' => 'Bug Fixes',
                'badge' => 'bg-danger',
                'icon'  => 'bi-bug-fill'
            ],
            'ui_ux' => [
                'label' => 'UI/UX Changes',
                'badge' => 'bg-purple',
                'icon'  => 'bi-palette-fill'
            ],
            'api_backend' => [
                'label' => 'API/Backend Changes',
                'badge' => 'bg-primary',
                'icon'  => 'bi-hdd-network-fill'
            ],
            'database' => [
                'label' => 'Database Changes',
                'badge' => 'bg-warning text-dark',
                'icon'  => 'bi-server'
            ],
            'security' => [
                'label' => 'Security Changes',
                'badge' => 'bg-dark',
                'icon'  => 'bi-shield-lock-fill'
            ],
            'third_party' => [
                'label' => 'AI / Third-party Integrations',
                'badge' => 'bg-teal',
                'icon'  => 'bi-robot'
            ]
        ];
>>>>>>> origin/main
    }
}
