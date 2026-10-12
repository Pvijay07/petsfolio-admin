<?php

namespace App\Models;

use CodeIgniter\Model;

class ReleaseChecklistModel extends Model
{
    protected $table            = 'release_checklists';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'version_id', 'section', 'section_label', 'sort_order'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';

    /**
     * Default checklist template
     */
    protected $defaultTemplate = [
        'application' => [
            'label' => 'Application',
            'items' => [
                'Build successfully generated',
                'Version/build number verified',
                'Login/OTP tested',
                'Client registration tested',
                'Client profile tested',
                'Booking flow tested',
                'Payment tested',
                'Wallet tested',
                'Cancellation/refund tested',
                'Notifications tested',
            ]
        ],
        'service_provider' => [
            'label' => 'Service Provider',
            'items' => [
                'Provider login tested',
                'Provider onboarding tested',
                'Service availability tested',
                'Booking acceptance tested',
                'Wallet/earnings tested',
                'Withdrawal tested',
                'Provider notifications tested',
            ]
        ],
        'admin' => [
            'label' => 'Admin',
            'items' => [
                'Admin login tested',
                'User management tested',
                'Booking management tested',
                'Payment management tested',
                'Wallet management tested',
                'Provider management tested',
                'Reports tested',
                'Version management tested',
            ]
        ],
        'technical' => [
            'label' => 'Technical',
            'items' => [
                'API verification completed',
                'Database migration verified',
                'Production configuration verified',
                'Environment variables verified',
                'Third-party integrations verified',
                'Analytics verified',
                'Crash monitoring verified',
                'Performance checks completed',
            ]
        ],
        'qa' => [
            'label' => 'QA',
            'items' => [
                'Regression testing completed',
                'Critical bugs closed',
                'High-priority bugs closed',
                'Known issues documented',
                'Final QA approval completed',
            ]
        ],
    ];

    /**
     * Initialize the default checklist for a version
     */
    public function initializeChecklist(int $versionId): bool
    {
        // Check if already initialized
        $existing = $this->where('version_id', $versionId)->countAllResults();
        if ($existing > 0) {
            return false; // Already initialized
        }

        $this->db->transStart();

        $sortOrder = 0;
        foreach ($this->defaultTemplate as $sectionKey => $section) {
            // Create checklist section
            $this->db->table('release_checklists')->insert([
                'version_id'    => $versionId,
                'section'       => $sectionKey,
                'section_label' => $section['label'],
                'sort_order'    => $sortOrder++,
                'created_at'    => date('Y-m-d H:i:s'),
            ]);
            $checklistId = $this->db->insertID();

            // Create checklist items
            $itemOrder = 0;
            foreach ($section['items'] as $itemText) {
                $this->db->table('release_checklist_items')->insert([
                    'checklist_id' => $checklistId,
                    'version_id'   => $versionId,
                    'item_text'    => $itemText,
                    'status'       => 'pending',
                    'sort_order'   => $itemOrder++,
                    'created_at'   => date('Y-m-d H:i:s'),
                ]);
            }
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }

    /**
     * Get full checklist with items for a version
     */
    public function getChecklistByVersion(int $versionId): array
    {
        $sections = $this->db->table('release_checklists')
            ->where('version_id', $versionId)
            ->orderBy('sort_order', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($sections as &$section) {
            $section['items'] = $this->db->table('release_checklist_items rci')
                ->select('rci.*, e.name as checker_name')
                ->join('employees e', 'e.id = rci.checked_by', 'left')
                ->where('rci.checklist_id', $section['id'])
                ->orderBy('rci.sort_order', 'ASC')
                ->get()
                ->getResultArray();
        }

        return $sections;
    }

    /**
     * Update a checklist item status
     */
    public function updateItemStatus(int $itemId, string $status, int $userId, ?string $comment = null): bool
    {
        $data = [
            'status'     => $status,
            'checked_by' => $userId,
            'checked_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        if ($comment !== null) {
            $data['comment'] = $comment;
        }

        return $this->db->table('release_checklist_items')
            ->where('id', $itemId)
            ->update($data);
    }

    /**
     * Get completion percentage for a version
     */
    public function getCompletionPercentage(int $versionId): float
    {
        $total = $this->db->table('release_checklist_items')
            ->where('version_id', $versionId)
            ->countAllResults();

        if ($total === 0) return 0;

        $completed = $this->db->table('release_checklist_items')
            ->where('version_id', $versionId)
            ->whereIn('status', ['passed', 'na'])
            ->countAllResults();

        return round(($completed / $total) * 100, 1);
    }

    /**
     * Get checklist summary counts per status
     */
    public function getStatusCounts(int $versionId): array
    {
        $results = $this->db->table('release_checklist_items')
            ->select('status, COUNT(*) as count')
            ->where('version_id', $versionId)
            ->groupBy('status')
            ->get()
            ->getResultArray();

        $counts = ['pending' => 0, 'in_progress' => 0, 'passed' => 0, 'failed' => 0, 'na' => 0];
        foreach ($results as $row) {
            $counts[$row['status']] = (int)$row['count'];
        }
        $counts['total'] = array_sum($counts);
        return $counts;
    }
}
