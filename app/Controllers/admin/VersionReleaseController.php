<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\ReleaseVersionModel;
use App\Models\ReleaseUpdateModel;
use App\Models\ReleaseChecklistModel;
use App\Models\ReleaseChecklistItemModel;
use App\Models\ReleaseBackupModel;
use App\Models\ReleaseApprovalModel;
use App\Models\ReleaseEmailModel;
use App\Models\ReleaseRollbackModel;
use App\Models\ReleaseAuditLogModel;
use App\Models\ReleaseCommentModel;
use App\Models\ReleaseTicketModel;
use App\Models\ReleaseDeploymentModel;
use Config\Database;

class VersionReleaseController extends BaseController
{
    protected $versionModel;
    protected $updateModel;
    protected $checklistModel;
    protected $itemModel;
    protected $backupModel;
    protected $approvalModel;
    protected $emailModel;
    protected $rollbackModel;
    protected $commentModel;
    protected $ticketModel;
    protected $deploymentModel;

    public function __construct()
    {
        $this->versionModel    = new ReleaseVersionModel();
        $this->updateModel     = new ReleaseUpdateModel();
        $this->checklistModel  = new ReleaseChecklistModel();
        $this->itemModel       = new ReleaseChecklistItemModel();
        $this->backupModel     = new ReleaseBackupModel();
        $this->approvalModel   = new ReleaseApprovalModel();
        $this->emailModel      = new ReleaseEmailModel();
        $this->rollbackModel   = new ReleaseRollbackModel();
        $this->commentModel    = new ReleaseCommentModel();
        $this->ticketModel     = new ReleaseTicketModel();
        $this->deploymentModel = new ReleaseDeploymentModel();

        // Ensure database tables exist automatically
        $this->versionModel->ensureTablesExist();
    }

    /**
     * Main dashboard view for Version Release Management
     */
    public function index()
    {
        $versions = $this->versionModel->getAllVersionsWithMetrics();

        // Determine active version to display details
        $requestedId = $this->request->getGet('version_id');
        $activeVersion = null;

        if ($requestedId) {
            $activeVersion = $this->versionModel->getVersionFullDetails($requestedId);
        }

        if (!$activeVersion && !empty($versions)) {
            // Find most recent draft/testing or fallback to first
            $inProgress = array_filter($versions, fn($v) => in_array($v['status'], ['draft', 'testing', 'qa_approved', 'backup_completed', 'management_approved', 'scheduled', 'releasing', 'monitoring']));
            if (!empty($inProgress)) {
                $firstInProgress = reset($inProgress);
                $activeVersion = $this->versionModel->getVersionFullDetails($firstInProgress['id']);
            } else {
                $activeVersion = $this->versionModel->getVersionFullDetails($versions[0]['id']);
            }
        }

        $liveVersion = $this->versionModel->getLiveVersion();
        $categories  = ReleaseUpdateModel::getCategories();

        // Fetch employees for developer / assignee selection
        $db = Database::connect();
        $employees = [];
        try {
            $employees = $db->table('employees')->select('id, name, email')->orderBy('name', 'ASC')->get()->getResultArray();
        } catch (\Throwable $e) {
            $employees = [];
        }

        $data = [
            'title'          => 'Version Release Management',
            'versions'       => $versions,
            'active_version' => $activeVersion,
            'live_version'   => $liveVersion,
            'categories'     => $categories,
            'employees'      => $employees,
            'active_tab'     => $this->request->getGet('tab') ?? 'details'
        ];

        return $this->loadView('admin/version_release/index', $data);
    }

    /**
     * View specific version
     */
    public function details($id)
    {
        return redirect()->to(base_url('version-releases?version_id=' . $id));
    }

    /**
     * Create a new release version
     */
    public function store()
    {
        $session = session();
        $user = $session->get('user');
        $userId = is_object($user) ? ($user->id ?? 1) : ($user['id'] ?? 1);

        $versionNumber = trim($this->request->getPost('version_number'));
        if (empty($versionNumber)) {
            return redirect()->back()->with('error', 'Version number is required (e.g. 2.4.1).');
        }

        // Check if version number already exists
        $exists = $this->versionModel->where('version_number', $versionNumber)->first();
        if ($exists) {
            return redirect()->back()->with('error', "Version {$versionNumber} already exists.");
        }

        $liveVersion = $this->versionModel->getLiveVersion();
        $previousVersionId = $this->request->getPost('previous_version_id') ?: ($liveVersion['id'] ?? null);

        $data = [
            'version_number'      => $versionNumber,
            'build_number'        => trim($this->request->getPost('build_number')),
            'platform'            => $this->request->getPost('platform') ?: 'both',
            'release_type'        => $this->request->getPost('release_type') ?: 'minor',
            'status'              => 'draft',
            'previous_version_id' => $previousVersionId ?: null,
            'rollback_version_id' => $previousVersionId ?: null,
            'release_notes'       => $this->request->getPost('release_notes'),
            'planned_release_at'  => $this->request->getPost('planned_release_at') ?: null,
            'monitoring_period'   => $this->request->getPost('monitoring_period') ?: '1hr',
            'created_by'          => $userId ?: 1,
            'created_at'          => date('Y-m-d H:i:s')
        ];

        $versionId = $this->versionModel->insert($data);

        if ($versionId) {
            // Automatically populate the 3 developer & QA checklists
            $this->checklistModel->populateDefaultChecklists($versionId);

            // Audit log
            ReleaseAuditLogModel::log($versionId, 'version_created', "Version {$versionNumber} created with status Draft", null, $data);

            return redirect()->to(base_url('version-releases?version_id=' . $versionId))
                ->with('success', "Version {$versionNumber} created successfully with standard Developer & QA checklists.");
        }

        return redirect()->back()->with('error', 'Failed to create version release.');
    }

    /**
     * Update version details
     */
    public function update($id)
    {
        $version = $this->versionModel->find($id);
        if (!$version) {
            return redirect()->back()->with('error', 'Version not found.');
        }

        $data = [
            'version_number'      => trim($this->request->getPost('version_number')),
            'build_number'        => trim($this->request->getPost('build_number')),
            'platform'            => $this->request->getPost('platform'),
            'release_type'        => $this->request->getPost('release_type'),
            'previous_version_id' => $this->request->getPost('previous_version_id') ?: null,
            'rollback_version_id' => $this->request->getPost('rollback_version_id') ?: null,
            'release_notes'       => $this->request->getPost('release_notes'),
            'planned_release_at'  => $this->request->getPost('planned_release_at') ?: null,
            'monitoring_period'   => $this->request->getPost('monitoring_period') ?: '1hr',
            'updated_at'          => date('Y-m-d H:i:s')
        ];

        $this->versionModel->update($id, $data);

        ReleaseAuditLogModel::log($id, 'version_updated', "Updated version details for {$data['version_number']}", $version, $data);

        return redirect()->to(base_url('version-releases?version_id=' . $id))
            ->with('success', 'Version details updated successfully.');
    }

    /**
     * Delete a version
     */
    public function delete($id)
    {
        $version = $this->versionModel->find($id);
        if (!$version) {
            return redirect()->back()->with('error', 'Version not found.');
        }

        if ($version['status'] === 'live') {
            return redirect()->back()->with('error', 'Cannot delete a version that is currently Live in Production.');
        }

        $this->versionModel->delete($id);

        return redirect()->to(base_url('version-releases'))
            ->with('success', "Version {$version['version_number']} deleted successfully.");
    }

    /**
     * Update lifecycle status of a version
     */
    public function updateStatus($id)
    {
        $version = $this->versionModel->find($id);
        if (!$version) {
            return $this->response->setJSON(['success' => false, 'message' => 'Version not found']);
        }

        $newStatus = $this->request->getPost('status');
        $validStatuses = [
            'draft', 'testing', 'qa_approved', 'backup_completed',
            'management_approved', 'scheduled', 'releasing', 'live',
            'monitoring', 'successful', 'rollback_requested', 'rolling_back', 'rolled_back'
        ];

        if (!in_array($newStatus, $validStatuses)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid status']);
        }

        $updateData = ['status' => $newStatus, 'updated_at' => date('Y-m-d H:i:s')];

        // Specific actions for key status transitions
        if ($newStatus === 'live') {
            $updateData['released_at'] = date('Y-m-d H:i:s');
            // Update previous live versions to successful or archived
            $this->versionModel->where('status', 'live')->where('id !=', $id)->set(['status' => 'successful'])->update();
        } elseif ($newStatus === 'monitoring') {
            $mins = 60;
            switch ($version['monitoring_period']) {
                case '30min': $mins = 30; break;
                case '1hr': $mins = 60; break;
                case '4hr': $mins = 240; break;
                case '24hr': $mins = 1440; break;
            }
            $updateData['monitoring_ends_at'] = date('Y-m-d H:i:s', strtotime("+{$mins} minutes"));
        }

        $this->versionModel->update($id, $updateData);

        ReleaseAuditLogModel::log($id, 'status_changed', "Status changed from {$version['status']} to {$newStatus}", $version['status'], $newStatus);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success'    => true,
                'new_status' => $newStatus,
                'message'    => "Version status updated to " . ucfirst(str_replace('_', ' ', $newStatus))
            ]);
        }

        return redirect()->to(base_url('version-releases?version_id=' . $id))
            ->with('success', "Version status updated to " . ucfirst(str_replace('_', ' ', $newStatus)));
    }

    /**
     * AJAX endpoint to toggle / update a checklist item
     * Status: Pending / Completed (passed) / Failed / NA
     * Automatically captures logged in user's name and current timestamp!
     */
    public function updateChecklistItem()
    {
        $itemId  = (int)$this->request->getPost('item_id');
        $status  = $this->request->getPost('status');
        $comment = $this->request->getPost('comment');

        $item = $this->itemModel->find($itemId);
        if (!$item) {
            return $this->response->setJSON(['success' => false, 'message' => 'Checklist item not found']);
        }

        // Map status: 'completed' from UI maps to 'passed' in DB schema
        if ($status === 'completed') {
            $status = 'passed';
        }

        $validStatuses = ['pending', 'in_progress', 'passed', 'failed', 'na'];
        if (!in_array($status, $validStatuses)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid status']);
        }

        $session = session();
        $user = $session->get('user');
        $userId   = is_object($user) ? ($user->id ?? 1) : ($user['id'] ?? 1);
        $userName = is_object($user) ? ($user->name ?? 'Admin User') : ($user['name'] ?? 'Admin User');
        $now = date('Y-m-d H:i:s');

        $updateData = [
            'status'     => $status,
            'checked_by' => $status === 'pending' ? null : $userId,
            'checked_at' => $status === 'pending' ? null : $now,
            'updated_at' => $now
        ];

        if ($comment !== null) {
            $updateData['comment'] = trim($comment);
        }

        $this->itemModel->update($itemId, $updateData);

        // Recalculate statistics for this version and checklist
        $versionId   = $item['version_id'];
        $checklistId = $item['checklist_id'];
        $db = Database::connect();

        $totalVersionItems  = $db->table('release_checklist_items')->where('version_id', $versionId)->countAllResults();
        $passedVersionItems = $db->table('release_checklist_items')->where('version_id', $versionId)->where('status', 'passed')->countAllResults();
        $failedVersionItems = $db->table('release_checklist_items')->where('version_id', $versionId)->where('status', 'failed')->countAllResults();
        $versionPercentage  = $totalVersionItems > 0 ? round(($passedVersionItems / $totalVersionItems) * 100) : 0;

        $totalChkItems  = $db->table('release_checklist_items')->where('checklist_id', $checklistId)->countAllResults();
        $passedChkItems = $db->table('release_checklist_items')->where('checklist_id', $checklistId)->where('status', 'passed')->countAllResults();
        $chkPercentage  = $totalChkItems > 0 ? round(($passedChkItems / $totalChkItems) * 100) : 0;

        return $this->response->setJSON([
            'success'      => true,
            'item_id'      => $itemId,
            'status'       => $status,
            'checked_by'   => $status === 'pending' ? '' : $userName,
            'checked_at'   => $status === 'pending' ? '' : date('M d, Y h:i A', strtotime($now)),
            'comment'      => $updateData['comment'] ?? $item['comment'],
            'chk_stats'    => [
                'total'      => $totalChkItems,
                'passed'     => $passedChkItems,
                'percentage' => $chkPercentage
            ],
            'version_stats' => [
                'total'      => $totalVersionItems,
                'passed'     => $passedVersionItems,
                'failed'     => $failedVersionItems,
                'percentage' => $versionPercentage
            ]
        ]);
    }

    /**
     * Re-seed or initialize the 3 Developer & QA checklists
     */
    public function populateDefaultChecklists($versionId)
    {
        $existing = $this->checklistModel->where('version_id', $versionId)->countAllResults();
        if ($existing === 0) {
            $this->checklistModel->populateDefaultChecklists($versionId);
            return redirect()->to(base_url('version-releases?version_id=' . $versionId . '&tab=checklists'))
                ->with('success', 'Developer & QA Checklists initialized successfully.');
        }

        return redirect()->to(base_url('version-releases?version_id=' . $versionId . '&tab=checklists'))
            ->with('info', 'Checklists already exist for this version.');
    }

    /**
     * Add or update an update/changelog item
     */
    public function saveUpdate()
    {
        $versionId = (int)$this->request->getPost('version_id');
        $updateId  = (int)$this->request->getPost('update_id');

        $data = [
            'version_id'   => $versionId,
            'category'     => $this->request->getPost('category'),
            'title'        => trim($this->request->getPost('title')),
            'description'  => trim($this->request->getPost('description')),
            'ticket_id'    => trim($this->request->getPost('ticket_id')),
            'developer_id' => $this->request->getPost('developer_id') ?: null,
            'status'       => $this->request->getPost('status') ?: 'completed',
            'comments'     => trim($this->request->getPost('comments')),
        ];

        if (empty($data['title'])) {
            return redirect()->back()->with('error', 'Update title is required.');
        }

        if ($updateId > 0) {
            $this->updateModel->update($updateId, $data);
            $msg = 'Update item modified successfully.';
        } else {
            $this->updateModel->insert($data);
            $msg = 'New update item added successfully.';
        }

        ReleaseAuditLogModel::log($versionId, 'update_saved', "Saved update item: {$data['title']}", null, $data);

        return redirect()->to(base_url('version-releases?version_id=' . $versionId . '&tab=updates'))
            ->with('success', $msg);
    }

    /**
     * Delete an update item
     */
    public function deleteUpdate($id)
    {
        $update = $this->updateModel->find($id);
        if (!$update) {
            return redirect()->back()->with('error', 'Update item not found.');
        }

        $versionId = $update['version_id'];
        $this->updateModel->delete($id);

        ReleaseAuditLogModel::log($versionId, 'update_deleted', "Deleted update item: {$update['title']}");

        return redirect()->to(base_url('version-releases?version_id=' . $versionId . '&tab=updates'))
            ->with('success', 'Update item deleted.');
    }

    /**
     * Save production backup verification details
     */
    public function saveBackup()
    {
        $session = session();
        $user = $session->get('user');
        $userId = is_object($user) ? ($user->id ?? 1) : ($user['id'] ?? 1);

        $versionId = (int)$this->request->getPost('version_id');
        $version = $this->versionModel->find($versionId);
        if (!$version) {
            return redirect()->back()->with('error', 'Version not found.');
        }

        $existing = $this->backupModel->where('version_id', $versionId)->first();

        $data = [
            'version_id'           => $versionId,
            'current_prod_version' => trim($this->request->getPost('current_prod_version')),
            'current_build_number' => trim($this->request->getPost('current_build_number')),
            'apk_aab_reference'    => trim($this->request->getPost('apk_aab_reference')),
            'ipa_reference'        => trim($this->request->getPost('ipa_reference')),
            'database_backup'      => trim($this->request->getPost('database_backup')),
            'api_backup'           => trim($this->request->getPost('api_backup')),
            'config_backup'        => trim($this->request->getPost('config_backup')),
            'backup_date'          => $this->request->getPost('backup_date') ?: date('Y-m-d H:i:s'),
            'verified_by'          => $userId ?: 1,
            'verified_at'          => date('Y-m-d H:i:s'),
            'rollback_version'     => trim($this->request->getPost('rollback_version')),
            'notes'                => trim($this->request->getPost('notes')),
        ];

        if ($existing) {
            $this->backupModel->update($existing['id'], $data);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->backupModel->insert($data);
        }

        // Auto transition status to backup_completed if currently qa_approved
        if ($version['status'] === 'qa_approved') {
            $this->versionModel->update($versionId, ['status' => 'backup_completed', 'updated_at' => date('Y-m-d H:i:s')]);
            ReleaseAuditLogModel::log($versionId, 'status_changed', "Status advanced to backup_completed upon backup verification", 'qa_approved', 'backup_completed');
        }

        ReleaseAuditLogModel::log($versionId, 'backup_verified', "Production backup verified for version {$version['version_number']}", null, $data);

        return redirect()->to(base_url('version-releases?version_id=' . $versionId . '&tab=backup'))
            ->with('success', 'Production backup verified and recorded successfully.');
    }

    /**
     * Submit stage approval (Developer, QA, Product, Management, Release Manager)
     */
    public function submitApproval()
    {
        $session = session();
        $user = $session->get('user');
        $userId = is_object($user) ? ($user->id ?? 1) : ($user['id'] ?? 1);

        $versionId    = (int)$this->request->getPost('version_id');
        $approvalType = $this->request->getPost('approval_type');
        $status       = $this->request->getPost('status') ?: 'approved';
        $comments     = trim($this->request->getPost('comments'));

        $version = $this->versionModel->find($versionId);
        if (!$version) {
            return redirect()->back()->with('error', 'Version not found.');
        }

        $existing = $this->approvalModel->where('version_id', $versionId)->where('approval_type', $approvalType)->first();

        $data = [
            'version_id'    => $versionId,
            'approval_type' => $approvalType,
            'status'        => $status,
            'approved_by'   => $userId ?: 1,
            'approved_at'   => date('Y-m-d H:i:s'),
            'comments'      => $comments,
        ];

        if ($existing) {
            $this->approvalModel->update($existing['id'], $data);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->approvalModel->insert($data);
        }

        // Automatic workflow progression based on approval type
        if ($status === 'approved') {
            if ($approvalType === 'qa' && in_array($version['status'], ['draft', 'testing'])) {
                $this->versionModel->update($versionId, ['status' => 'qa_approved', 'updated_at' => date('Y-m-d H:i:s')]);
            } elseif ($approvalType === 'management' && in_array($version['status'], ['backup_completed', 'qa_approved'])) {
                $this->versionModel->update($versionId, ['status' => 'management_approved', 'approved_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
            }
        }

        ReleaseAuditLogModel::log($versionId, 'approval_submitted', "Approval {$approvalType} marked as {$status}", null, $data);

        return redirect()->to(base_url('version-releases?version_id=' . $versionId . '&tab=approvals'))
            ->with('success', ucfirst($approvalType) . " approval recorded as " . ucfirst($status) . ".");
    }

    /**
     * Preview or generate approval HTML email
     */
    public function previewEmail($versionId)
    {
        $version = $this->versionModel->getVersionFullDetails($versionId);
        if (!$version) {
            return $this->response->setStatusCode(404, 'Version not found');
        }

        $html = view('admin/version_release/email_preview', ['v' => $version]);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'subject' => "Petsfolio App Release Approval Request - Version {$version['version_number']} (Build {$version['build_number']})",
                'html'    => $html
            ]);
        }

        return $html;
    }

    /**
     * Dispatch or record approval email
     */
    public function sendApprovalEmail($versionId)
    {
        $version = $this->versionModel->getVersionFullDetails($versionId);
        if (!$version) {
            return redirect()->back()->with('error', 'Version not found.');
        }

        $session = session();
        $user = $session->get('user');
        $userId = is_object($user) ? ($user->id ?? 1) : ($user['id'] ?? 1);

        $recipientsInput = $this->request->getPost('recipients');
        $recipients = array_filter(array_map('trim', explode(',', $recipientsInput)));
        if (empty($recipients)) {
            $recipients = ['management@petsfolio.com', 'qa@petsfolio.com', 'techlead@petsfolio.com'];
        }

        $subject = "Release Approval: Petsfolio App v{$version['version_number']} (Build {$version['build_number']}) - Ready for Production";
        $bodyHtml = view('admin/version_release/email_preview', ['v' => $version]);

        // Attempt sending via CI4 email service if configured
        $emailSent = false;
        $errorMessage = null;
        try {
            $email = \Config\Services::email();
            $email->setTo($recipients);
            $email->setSubject($subject);
            $email->setMessage($bodyHtml);
            $email->setMailType('html');
            if ($email->send()) {
                $emailSent = true;
            } else {
                $errorMessage = $email->printDebugger(['headers']);
            }
        } catch (\Throwable $e) {
            $errorMessage = $e->getMessage();
        }

        // Record email in release_emails
        $this->emailModel->insert([
            'version_id'    => $versionId,
            'subject'       => $subject,
            'body_html'     => $bodyHtml,
            'recipients'    => json_encode($recipients),
            'sent_by'       => $userId ?: 1,
            'sent_at'       => date('Y-m-d H:i:s'),
            'status'        => $emailSent ? 'sent' : 'draft',
            'error_message' => $errorMessage,
            'created_at'    => date('Y-m-d H:i:s')
        ]);

        ReleaseAuditLogModel::log($versionId, 'approval_mail_sent', "Approval email dispatched to " . implode(', ', $recipients));

        return redirect()->to(base_url('version-releases?version_id=' . $versionId . '&tab=email'))
            ->with('success', 'Release Approval Email prepared & recorded successfully.');
    }

    /**
     * Emergency Rollback initiation
     */
    public function initiateRollback()
    {
        $session = session();
        $user = $session->get('user');
        $userId = is_object($user) ? ($user->id ?? 1) : ($user['id'] ?? 1);

        $versionId           = (int)$this->request->getPost('version_id');
        $currentLiveVersion  = trim($this->request->getPost('current_live_version'));
        $rollbackToVersion   = trim($this->request->getPost('rollback_to_version'));
        $rollbackReason      = trim($this->request->getPost('rollback_reason'));
        $criticalIssue       = trim($this->request->getPost('critical_issue'));

        if (empty($rollbackReason)) {
            return redirect()->back()->with('error', 'Rollback reason is required.');
        }

        $this->rollbackModel->insert([
            'version_id'           => $versionId,
            'current_live_version' => $currentLiveVersion,
            'rollback_to_version'  => $rollbackToVersion,
            'rollback_reason'      => $rollbackReason,
            'critical_issue'       => $criticalIssue,
            'approved_by'          => $userId ?: 1,
            'approved_at'          => date('Y-m-d H:i:s'),
            'started_at'           => date('Y-m-d H:i:s'),
            'status'               => 'in_progress',
            'created_at'           => date('Y-m-d H:i:s')
        ]);

        // Transition version status to rolling_back
        $this->versionModel->update($versionId, [
            'status'     => 'rolling_back',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        ReleaseAuditLogModel::log($versionId, 'rollback_initiated', "Emergency rollback initiated from {$currentLiveVersion} to {$rollbackToVersion}. Reason: {$rollbackReason}");

        return redirect()->to(base_url('version-releases?version_id=' . $versionId . '&tab=rollback'))
            ->with('warning', "Emergency rollback initiated. Live traffic is being redirected to stable version {$rollbackToVersion}.");
    }

    /**
     * Complete emergency rollback
     */
    public function completeRollback($id)
    {
        $rollback = $this->rollbackModel->find($id);
        if (!$rollback) {
            return redirect()->back()->with('error', 'Rollback record not found.');
        }

        $now = date('Y-m-d H:i:s');
        $this->rollbackModel->update($id, [
            'status'              => 'completed',
            'completed_at'        => $now,
            'post_rollback_notes' => trim($this->request->getPost('post_rollback_notes')),
            'updated_at'          => $now
        ]);

        // Set failed version to rolled_back
        $this->versionModel->update($rollback['version_id'], [
            'status'     => 'rolled_back',
            'updated_at' => $now
        ]);

        // Re-activate stable target version to live if found
        $target = $this->versionModel->where('version_number', $rollback['rollback_to_version'])->first();
        if ($target) {
            $this->versionModel->update($target['id'], [
                'status'     => 'live',
                'updated_at' => $now
            ]);
        }

        ReleaseAuditLogModel::log($rollback['version_id'], 'rollback_completed', "Rollback completed to {$rollback['rollback_to_version']}");

        return redirect()->to(base_url('version-releases?version_id=' . $rollback['version_id'] . '&tab=rollback'))
            ->with('success', "Rollback completed. Version {$rollback['rollback_to_version']} is now active as Live production build.");
    }

    /**
     * Add comment to version thread
     */
    public function addComment()
    {
        $session = session();
        $user = $session->get('user');
        $userId = is_object($user) ? ($user->id ?? 1) : ($user['id'] ?? 1);

        $versionId   = (int)$this->request->getPost('version_id');
        $commentType = $this->request->getPost('comment_type') ?: 'general';
        $comment     = trim($this->request->getPost('comment'));

        if (!empty($comment)) {
            $this->commentModel->insert([
                'version_id'   => $versionId,
                'comment_type' => $commentType,
                'comment'      => $comment,
                'created_by'   => $userId ?: 1,
                'created_at'   => date('Y-m-d H:i:s')
            ]);
        }

        return redirect()->to(base_url('version-releases?version_id=' . $versionId . '&tab=threads'))
            ->with('success', 'Comment posted to version thread.');
    }

    /**
     * Compare two versions (Version threads & diff)
     */
    public function compareVersions($id1, $id2)
    {
        $v1 = $this->versionModel->getVersionFullDetails($id1);
        $v2 = $this->versionModel->getVersionFullDetails($id2);

        if (!$v1 || !$v2) {
            return $this->response->setJSON(['success' => false, 'message' => 'Version not found']);
        }

        return $this->response->setJSON([
            'success' => true,
            'v1'      => $v1,
            'v2'      => $v2
        ]);
    }
}
