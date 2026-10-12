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
<<<<<<< HEAD
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
=======
        'version_id',
        'section',
        'section_label',
        'sort_order',
        'created_at'
    ];

    /**
     * Populate the 3 standard Developer & QA Checklists for a given version
     */
    public function populateDefaultChecklists($versionId)
    {
        $itemModel = new ReleaseChecklistItemModel();

        // 1. Frontend Developer – While Uploading
        $frontendId = $this->insert([
            'version_id'    => $versionId,
            'section'       => 'application',
            'section_label' => 'Frontend Developer – While Uploading',
            'sort_order'    => 1
        ]);

        $frontendChecks = [
            'Correct Version Number uploaded',
            'Correct Version Code / Build Number uploaded',
            'All approved requirements/changes are included',
            'No old or unwanted code included',
            'Production API/URL configured correctly',
            'Force Update version configured correctly',
            'App builds/install successfully',
            'Final APK / iOS Build uploaded is the latest approved build'
        ];

        foreach ($frontendChecks as $idx => $text) {
            $itemModel->insert([
                'checklist_id' => $frontendId,
                'version_id'   => $versionId,
                'item_text'    => $text,
                'status'       => 'pending',
                'sort_order'   => $idx + 1
            ]);
        }

        // 2. Backend Developer – While Deploying
        $backendId = $this->insert([
            'version_id'    => $versionId,
            'section'       => 'technical',
            'section_label' => 'Backend Developer – While Deploying',
            'sort_order'    => 2
        ]);

        $backendChecks = [
            'Correct backend code/version deployed',
            'All approved requirements/changes deployed',
            'Required API changes completed',
            'Required Database changes completed',
            'Production configuration verified',
            'No test/staging configuration is active',
            'Backend deployment completed successfully',
            'APIs are working with the new Frontend version'
        ];

        foreach ($backendChecks as $idx => $text) {
            $itemModel->insert([
                'checklist_id' => $backendId,
                'version_id'   => $versionId,
                'item_text'    => $text,
                'status'       => 'pending',
                'sort_order'   => $idx + 1
            ]);
        }

        // 3. QA / Tester
        $qaId = $this->insert([
            'version_id'    => $versionId,
            'section'       => 'qa',
            'section_label' => 'QA / Tester – Verification',
            'sort_order'    => 3
        ]);

        $qaChecks = [
            'Correct Frontend version received',
            'Correct Backend version verified',
            'All new requirements tested',
            'All existing functionality tested',
            'Changed functionality tested',
            'API integration tested',
            'Payment/Booking critical flows tested',
            'Force Update tested',
            'Android/iOS functionality checked',
            'No Critical/Blocker issues',
            'Final QA Pass/Fail status updated'
        ];

        foreach ($qaChecks as $idx => $text) {
            $itemModel->insert([
                'checklist_id' => $qaId,
                'version_id'   => $versionId,
                'item_text'    => $text,
                'status'       => 'pending',
                'sort_order'   => $idx + 1
            ]);
        }

        return true;
>>>>>>> origin/main
    }
}
