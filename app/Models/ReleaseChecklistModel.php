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
    }
}
