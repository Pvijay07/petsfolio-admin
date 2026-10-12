<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\ReleaseVersionModel;
use App\Models\ReleaseUpdateModel;
use App\Models\ReleaseTicketModel;
use App\Models\ReleaseChecklistModel;
use App\Models\ReleaseBackupModel;
use App\Models\ReleaseApprovalModel;
use App\Models\ReleaseEmailModel;
use App\Models\ReleaseDeploymentModel;
use App\Models\ReleaseRollbackModel;
use App\Models\ReleaseCommentModel;
use App\Models\ReleaseAuditLogModel;

class ReleaseManagementController extends BaseController
{
    protected $versionModel;
    protected $updateModel;
    protected $ticketModel;
    protected $checklistModel;
    protected $backupModel;
    protected $approvalModel;
    protected $emailModel;
    protected $deploymentModel;
    protected $rollbackModel;
    protected $commentModel;
    protected $auditModel;

    public function __construct()
    {
        $this->versionModel    = new ReleaseVersionModel();
        $this->updateModel     = new ReleaseUpdateModel();
        $this->ticketModel     = new ReleaseTicketModel();
        $this->checklistModel  = new ReleaseChecklistModel();
        $this->backupModel     = new ReleaseBackupModel();
        $this->approvalModel   = new ReleaseApprovalModel();
        $this->emailModel      = new ReleaseEmailModel();
        $this->deploymentModel = new ReleaseDeploymentModel();
        $this->rollbackModel   = new ReleaseRollbackModel();
        $this->commentModel    = new ReleaseCommentModel();
        $this->auditModel      = new ReleaseAuditLogModel();
    }

    /**
     * Get current user ID from session
     */
    protected function getUserId(): int
    {
        return (int)(session()->get('user')->id ?? 0);
    }

    /**
     * Get current user name from session
     */
    protected function getUserName(): string
    {
        return session()->get('user')->name ?? 'Unknown';
    }

    // =================================================================
    // DASHBOARD & LIST
    // =================================================================

    /**
     * Release Management Dashboard
     */
    public function index()
    {
        $filters = [
            'status'       => $this->request->getGet('status'),
            'platform'     => $this->request->getGet('platform'),
            'release_type' => $this->request->getGet('release_type'),
            'search'       => $this->request->getGet('search'),
        ];

        $versions = $this->versionModel->getVersionsList($filters);

        // Enrich each version with QA progress and approval summary
        foreach ($versions as &$v) {
            $v['qa_progress']      = $this->versionModel->getChecklistProgress($v['id']);
            $v['approval_summary'] = $this->approvalModel->getApprovalSummary($v['id']);
            $v['status_label']     = $this->versionModel->getStatusLabel($v['status']);
            $v['status_color']     = $this->versionModel->getStatusColor($v['status']);
        }

        $data = [
            'versions'      => $versions,
            'stats'         => $this->versionModel->getDashboardStats(),
            'recentActivity'=> $this->auditModel->getRecentActivity(10),
            'filters'       => $filters,
            'statusLabels'  => $this->versionModel->getAllStatusLabels(),
        ];

        return view('admin/releases/dashboard', $data);
    }

    // =================================================================
    // CREATE & EDIT VERSION
    // =================================================================

    /**
     * Show create version form
     */
    public function create()
    {
        $data = [
            'previousVersions' => $this->versionModel->getVersionsForDropdown(),
        ];
        return view('admin/releases/create', $data);
    }

    /**
     * Store a new version
     */
    public function store()
    {
        $userId = $this->getUserId();

        $data = [
            'version_number'      => $this->request->getPost('version_number'),
            'build_number'        => $this->request->getPost('build_number'),
            'platform'            => $this->request->getPost('platform') ?: 'both',
            'release_type'        => $this->request->getPost('release_type') ?: 'minor',
            'status'              => 'draft',
            'previous_version_id' => $this->request->getPost('previous_version_id') ?: null,
            'release_notes'       => $this->request->getPost('release_notes'),
            'planned_release_at'  => $this->request->getPost('planned_release_at') ?: null,
            'monitoring_period'   => $this->request->getPost('monitoring_period') ?: '1hr',
            'created_by'          => $userId,
        ];

        $versionId = $this->versionModel->insert($data);

        if ($versionId) {
            // Initialize approvals and checklist
            $this->approvalModel->initializeApprovals($versionId);
            $this->checklistModel->initializeChecklist($versionId);

            // Audit log
            $this->auditModel->log($versionId, $userId, 'version_created', null, $data['version_number'],
                "Version {$data['version_number']} created");

            return redirect()->to("/releases/detail/{$versionId}")
                ->with('success', "Version {$data['version_number']} created successfully.");
        }

        return redirect()->back()->with('error', 'Failed to create version.')->withInput();
    }

    // =================================================================
    // VERSION DETAIL (Tabbed Page)
    // =================================================================

    /**
     * Version detail page with all tabs
     */
    public function detail(int $id)
    {
        $version = $this->versionModel->getVersionDetail($id);
        if (!$version) {
            return redirect()->to('/releases')->with('error', 'Version not found.');
        }

        $data = [
            'version'           => $version,
            'statusLabel'       => $this->versionModel->getStatusLabel($version['status']),
            'statusColor'       => $this->versionModel->getStatusColor($version['status']),
            'allowedTransitions'=> $this->versionModel->getAllowedTransitions($version['status']),
            'statusLabels'      => $this->versionModel->getAllStatusLabels(),

            // Overview data
            'qaProgress'        => $this->versionModel->getChecklistProgress($id),
            'updateStats'       => $this->updateModel->getUpdateStats($id),
            'ticketStats'       => $this->ticketModel->getTicketStats($id),
            'approvalSummary'   => $this->approvalModel->getApprovalSummary($id),
            'commentCounts'     => $this->commentModel->getCommentCounts($id),

            // Tab data
            'updates'           => $this->updateModel->getUpdatesByVersion($id),
            'tickets'           => $this->ticketModel->getTicketsByVersion($id),
            'checklist'         => $this->checklistModel->getChecklistByVersion($id),
            'checklistCounts'   => $this->checklistModel->getStatusCounts($id),
            'backup'            => $this->backupModel->getBackupByVersion($id),
            'approvals'         => $this->approvalModel->getApprovalsByVersion($id),
            'latestEmail'       => $this->emailModel->getLatestEmail($id),
            'emails'            => $this->emailModel->getEmailsByVersion($id),
            'deployment'        => $this->deploymentModel->getDeploymentByVersion($id),
            'deploymentProgress'=> $this->deploymentModel->getDeploymentProgress($id),
            'rollback'          => $this->rollbackModel->getRollbackByVersion($id),
            'rollbackTimeline'  => $this->rollbackModel->getRollbackTimeline($id),
            'comments'          => $this->commentModel->getCommentsByVersion($id),
            'auditLogs'         => $this->auditModel->getLogsByVersion($id),
            'versionThread'     => $this->versionModel->getVersionThread($id),

            // Helpers
            'categories'        => $this->updateModel->getAllCategories(),
            'updateModel'       => $this->updateModel,
            'approvalModel'     => $this->approvalModel,
            'deploymentModel'   => $this->deploymentModel,
            'commentModel'      => $this->commentModel,
            'versionModel'      => $this->versionModel,
            'previousVersions'  => $this->versionModel->getVersionsForDropdown(),

            // Employees for dropdowns
            'employees'         => $this->getEmployeesList(),
        ];

        return view('admin/releases/detail', $data);
    }

    /**
     * Update version fields
     */
    public function update(int $id)
    {
        $version = $this->versionModel->find($id);
        if (!$version) {
            return $this->response->setJSON(['success' => false, 'message' => 'Version not found.']);
        }

        $data = [];
        $fields = ['version_number', 'build_number', 'platform', 'release_type',
                    'release_notes', 'planned_release_at', 'monitoring_period',
                    'previous_version_id', 'rollback_version_id'];

        foreach ($fields as $field) {
            $value = $this->request->getPost($field);
            if ($value !== null) {
                $data[$field] = $value ?: null;
            }
        }

        if (!empty($data)) {
            $this->versionModel->update($id, $data);
            $this->auditModel->log($id, $this->getUserId(), 'version_updated',
                json_encode($version), json_encode($data), 'Version details updated');
        }

        return redirect()->to("/releases/detail/{$id}")->with('success', 'Version updated.');
    }

    /**
     * Delete a version
     */
    public function delete(int $id)
    {
        $version = $this->versionModel->find($id);
        if (!$version) {
            return redirect()->to('/releases')->with('error', 'Version not found.');
        }

        if ($version['status'] !== 'draft') {
            return redirect()->to("/releases/detail/{$id}")
                ->with('error', 'Only draft versions can be deleted.');
        }

        $this->versionModel->delete($id);
        return redirect()->to('/releases')->with('success', "Version {$version['version_number']} deleted.");
    }

    // =================================================================
    // STATE MACHINE TRANSITIONS
    // =================================================================

    /**
     * Transition version status (state machine)
     */
    public function transitionStatus(int $id)
    {
        $newStatus = $this->request->getPost('new_status');
        if (!$newStatus) {
            return redirect()->to("/releases/detail/{$id}")->with('error', 'No status specified.');
        }

        $result = $this->versionModel->transitionStatus($id, $newStatus, $this->getUserId());

        if ($result['success']) {
            return redirect()->to("/releases/detail/{$id}")->with('success', $result['message']);
        }

        return redirect()->to("/releases/detail/{$id}")->with('error', $result['message']);
    }

    // =================================================================
    // UPDATES (AJAX)
    // =================================================================

    public function getUpdates(int $id)
    {
        $updates = $this->updateModel->getUpdatesByVersion($id);
        return $this->response->setJSON(['success' => true, 'data' => $updates]);
    }

    public function saveUpdate()
    {
        $data = [
            'version_id'   => $this->request->getPost('version_id'),
            'category'     => $this->request->getPost('category'),
            'title'        => $this->request->getPost('title'),
            'description'  => $this->request->getPost('description'),
            'ticket_id'    => $this->request->getPost('ticket_id'),
            'developer_id' => $this->request->getPost('developer_id') ?: null,
            'status'       => $this->request->getPost('status') ?: 'pending',
            'comments'     => $this->request->getPost('comments'),
        ];

        $updateId = $this->request->getPost('update_id');

        if ($updateId) {
            $this->updateModel->update($updateId, $data);
            $this->auditModel->log($data['version_id'], $this->getUserId(), 'update_modified',
                null, $data['title'], "Update modified: {$data['title']}");
        } else {
            $updateId = $this->updateModel->insert($data);
            $this->auditModel->log($data['version_id'], $this->getUserId(), 'update_added',
                null, $data['title'], "Update added: {$data['title']}");
        }

        return $this->response->setJSON(['success' => true, 'id' => $updateId]);
    }

    public function deleteUpdate(int $id)
    {
        $update = $this->updateModel->find($id);
        if ($update) {
            $this->updateModel->delete($id);
            $this->auditModel->log($update['version_id'], $this->getUserId(), 'update_deleted',
                $update['title'], null, "Update deleted: {$update['title']}");
        }
        return $this->response->setJSON(['success' => true]);
    }

    // =================================================================
    // TICKETS (AJAX)
    // =================================================================

    public function saveTicket()
    {
        $data = [
            'version_id'  => $this->request->getPost('version_id'),
            'ticket_id'   => $this->request->getPost('ticket_id'),
            'description' => $this->request->getPost('description'),
            'status'      => $this->request->getPost('status') ?: 'open',
            'priority'    => $this->request->getPost('priority') ?: 'medium',
            'assigned_to' => $this->request->getPost('assigned_to') ?: null,
        ];

        $ticketDbId = $this->request->getPost('id');

        if ($ticketDbId) {
            $this->ticketModel->update($ticketDbId, $data);
        } else {
            $ticketDbId = $this->ticketModel->insert($data);
            $this->auditModel->log($data['version_id'], $this->getUserId(), 'ticket_added',
                null, $data['ticket_id'], "Ticket {$data['ticket_id']} linked");
        }

        return $this->response->setJSON(['success' => true, 'id' => $ticketDbId]);
    }

    public function deleteTicket(int $id)
    {
        $ticket = $this->ticketModel->find($id);
        if ($ticket) {
            $this->ticketModel->delete($id);
            $this->auditModel->log($ticket['version_id'], $this->getUserId(), 'ticket_deleted',
                $ticket['ticket_id'], null, "Ticket {$ticket['ticket_id']} removed");
        }
        return $this->response->setJSON(['success' => true]);
    }

    // =================================================================
    // QA CHECKLIST
    // =================================================================

    public function getChecklist(int $id)
    {
        $checklist = $this->checklistModel->getChecklistByVersion($id);
        $counts    = $this->checklistModel->getStatusCounts($id);
        $progress  = $this->checklistModel->getCompletionPercentage($id);

        return $this->response->setJSON([
            'success'  => true,
            'data'     => $checklist,
            'counts'   => $counts,
            'progress' => $progress,
        ]);
    }

    public function initChecklist(int $id)
    {
        $result = $this->checklistModel->initializeChecklist($id);
        if ($result) {
            $this->auditModel->log($id, $this->getUserId(), 'checklist_initialized',
                null, null, 'Go-Live checklist initialized');
        }
        return $this->response->setJSON(['success' => $result]);
    }

    public function updateChecklistItem()
    {
        $itemId    = $this->request->getPost('item_id');
        $status    = $this->request->getPost('status');
        $comment   = $this->request->getPost('comment');
        $versionId = $this->request->getPost('version_id');

        $result = $this->checklistModel->updateItemStatus($itemId, $status, $this->getUserId(), $comment);

        if ($result && $versionId) {
            $this->auditModel->log($versionId, $this->getUserId(), 'checklist_item_updated',
                null, $status, "Checklist item updated to {$status}");
        }

        $progress = $versionId ? $this->checklistModel->getCompletionPercentage($versionId) : 0;

        return $this->response->setJSON([
            'success'  => $result,
            'progress' => $progress,
        ]);
    }

    // =================================================================
    // BACKUPS
    // =================================================================

    public function saveBackup()
    {
        $data = [
            'version_id'           => $this->request->getPost('version_id'),
            'current_prod_version' => $this->request->getPost('current_prod_version'),
            'current_build_number' => $this->request->getPost('current_build_number'),
            'apk_aab_reference'    => $this->request->getPost('apk_aab_reference'),
            'ipa_reference'        => $this->request->getPost('ipa_reference'),
            'database_backup'      => $this->request->getPost('database_backup'),
            'api_backup'           => $this->request->getPost('api_backup'),
            'config_backup'        => $this->request->getPost('config_backup'),
            'backup_date'          => $this->request->getPost('backup_date') ?: date('Y-m-d H:i:s'),
            'rollback_version'     => $this->request->getPost('rollback_version'),
            'notes'                => $this->request->getPost('notes'),
        ];

        $backupId = $this->request->getPost('backup_id');

        if ($backupId) {
            $this->backupModel->update($backupId, $data);
        } else {
            $backupId = $this->backupModel->insert($data);
        }

        $this->auditModel->log($data['version_id'], $this->getUserId(), 'backup_created',
            null, $data['database_backup'], 'Backup record saved');

        return redirect()->to("/releases/detail/{$data['version_id']}?tab=backup")
            ->with('success', 'Backup record saved.');
    }

    public function verifyBackup(int $id)
    {
        $backup = $this->backupModel->find($id);
        if (!$backup) {
            return $this->response->setJSON(['success' => false, 'message' => 'Backup not found.']);
        }

        $this->backupModel->verifyBackup($id, $this->getUserId());
        $this->auditModel->log($backup['version_id'], $this->getUserId(), 'backup_verified',
            null, null, 'Backup verified by ' . $this->getUserName());

        return $this->response->setJSON(['success' => true, 'message' => 'Backup verified.']);
    }

    // =================================================================
    // APPROVALS
    // =================================================================

    public function submitApproval()
    {
        $versionId = $this->request->getPost('version_id');
        $type      = $this->request->getPost('approval_type');
        $status    = $this->request->getPost('status');
        $comments  = $this->request->getPost('comments');

        $result = $this->approvalModel->submitApproval(
            $versionId, $type, $this->getUserId(), $status, $comments
        );

        if ($result) {
            $action = $status === 'approved' ? 'approval_granted' : 'approval_rejected';
            $this->auditModel->log($versionId, $this->getUserId(), $action,
                null, "{$type}: {$status}",
                ucfirst($type) . " {$status} by " . $this->getUserName());
        }

        return redirect()->to("/releases/detail/{$versionId}?tab=approvals")
            ->with('success', ucfirst($type) . " {$status}.");
    }

    // =================================================================
    // EMAIL
    // =================================================================

    public function generateEmail(int $id)
    {
        $emailData = $this->emailModel->generateEmailBody($id);

        // Save as draft
        $emailId = $this->emailModel->insert([
            'version_id' => $id,
            'subject'    => $emailData['subject'],
            'body_html'  => $emailData['body'],
            'recipients' => json_encode([]),
            'status'     => 'draft',
        ]);

        $this->auditModel->log($id, $this->getUserId(), 'email_generated',
            null, null, 'Approval email generated');

        return redirect()->to("/releases/detail/{$id}?tab=email")
            ->with('success', 'Approval email generated.');
    }

    public function previewEmail(int $id)
    {
        $email = $this->emailModel->find($id);
        if (!$email) {
            return $this->response->setJSON(['success' => false, 'message' => 'Email not found.']);
        }

        return $this->response->setJSON([
            'success' => true,
            'data'    => $email,
        ]);
    }

    public function sendEmail(int $id)
    {
        $email = $this->emailModel->find($id);
        if (!$email) {
            return redirect()->back()->with('error', 'Email not found.');
        }

        $recipients = $this->request->getPost('recipients');
        if ($recipients) {
            $this->emailModel->update($id, ['recipients' => $recipients]);
        }

        // Update body if edited
        $bodyHtml = $this->request->getPost('body_html');
        if ($bodyHtml) {
            $this->emailModel->update($id, ['body_html' => $bodyHtml]);
            $email['body_html'] = $bodyHtml;
        }

        // Attempt to send the email
        try {
            $emailService = \Config\Services::email();
            $recipientList = json_decode($recipients ?: $email['recipients'], true) ?: [];

            if (empty($recipientList)) {
                return redirect()->to("/releases/detail/{$email['version_id']}?tab=email")
                    ->with('error', 'No recipients specified.');
            }

            $emailService->setTo($recipientList);
            $emailService->setSubject($email['subject']);
            $emailService->setMessage($email['body_html']);
            $emailService->setMailType('html');

            if ($emailService->send()) {
                $this->emailModel->markSent($id, $this->getUserId());
                $this->auditModel->log($email['version_id'], $this->getUserId(), 'email_sent',
                    null, implode(', ', $recipientList), 'Approval email sent');

                return redirect()->to("/releases/detail/{$email['version_id']}?tab=email")
                    ->with('success', 'Approval email sent successfully.');
            } else {
                $error = $emailService->printDebugger(['headers']);
                $this->emailModel->markFailed($id, $error);
                return redirect()->to("/releases/detail/{$email['version_id']}?tab=email")
                    ->with('error', 'Failed to send email.');
            }
        } catch (\Exception $e) {
            $this->emailModel->markFailed($id, $e->getMessage());
            return redirect()->to("/releases/detail/{$email['version_id']}?tab=email")
                ->with('error', 'Email error: ' . $e->getMessage());
        }
    }

    // =================================================================
    // GO-LIVE & DEPLOYMENT
    // =================================================================

    public function goLive(int $id)
    {
        $version = $this->versionModel->find($id);
        if (!$version) {
            return redirect()->to('/releases')->with('error', 'Version not found.');
        }

        // Start deployment
        $deploymentId = $this->deploymentModel->startDeployment($id, $this->getUserId());

        // Transition to releasing
        $this->versionModel->transitionStatus($id, 'releasing', $this->getUserId());

        $this->auditModel->log($id, $this->getUserId(), 'go_live_initiated',
            null, null, 'Go-Live process initiated by ' . $this->getUserName());

        return redirect()->to("/releases/detail/{$id}?tab=golive")
            ->with('success', 'Go-Live process started.');
    }

    public function updateDeploymentStep()
    {
        $deploymentId = $this->request->getPost('deployment_id');
        $step         = $this->request->getPost('step');
        $versionId    = $this->request->getPost('version_id');
        $notes        = $this->request->getPost('notes');

        $result = $this->deploymentModel->updateDeploymentStep($deploymentId, $step);

        if ($notes) {
            $this->deploymentModel->update($deploymentId, ['notes' => $notes]);
        }

        // Auto-transition version status based on deployment step
        if ($step === 'deployed') {
            $this->versionModel->transitionStatus($versionId, 'live', $this->getUserId());
        } elseif ($step === 'monitoring') {
            $this->versionModel->transitionStatus($versionId, 'monitoring', $this->getUserId());
        } elseif ($step === 'successful') {
            $this->versionModel->transitionStatus($versionId, 'successful', $this->getUserId());
        }

        if ($versionId) {
            $this->auditModel->log($versionId, $this->getUserId(), 'deployment_step_updated',
                null, $step, "Deployment step: {$step}");
        }

        return $this->response->setJSON(['success' => $result]);
    }

    // =================================================================
    // ROLLBACK
    // =================================================================

    public function initiateRollback()
    {
        $versionId = $this->request->getPost('version_id');
        $version   = $this->versionModel->find($versionId);

        if (!$version) {
            return redirect()->to('/releases')->with('error', 'Version not found.');
        }

        $data = [
            'version_id'          => $versionId,
            'current_live_version'=> $version['version_number'],
            'rollback_to_version' => $this->request->getPost('rollback_to_version'),
            'rollback_reason'     => $this->request->getPost('rollback_reason'),
            'critical_issue'      => $this->request->getPost('critical_issue'),
        ];

        $rollbackId = $this->rollbackModel->initiateRollback($data);

        // Transition version to rollback_requested
        $this->versionModel->transitionStatus($versionId, 'rollback_requested', $this->getUserId());

        $this->auditModel->log($versionId, $this->getUserId(), 'rollback_initiated',
            $version['version_number'], $data['rollback_to_version'],
            "Rollback initiated: {$version['version_number']} → {$data['rollback_to_version']}");

        return redirect()->to("/releases/detail/{$versionId}?tab=rollback")
            ->with('success', 'Rollback initiated.');
    }

    public function completeRollback(int $id)
    {
        $rollback = $this->rollbackModel->find($id);
        if (!$rollback) {
            return redirect()->back()->with('error', 'Rollback not found.');
        }

        $notes = $this->request->getPost('post_rollback_notes');

        // Progress through the rollback steps
        $this->rollbackModel->approveRollback($id, $this->getUserId());
        $this->rollbackModel->startRollback($id);
        $this->rollbackModel->completeRollback($id, $notes);

        // Transition version to rolled_back
        $versionId = $rollback['version_id'];
        $this->versionModel->transitionStatus($versionId, 'rolling_back', $this->getUserId());
        $this->versionModel->transitionStatus($versionId, 'rolled_back', $this->getUserId());

        $this->auditModel->log($versionId, $this->getUserId(), 'rollback_completed',
            $rollback['current_live_version'], $rollback['rollback_to_version'],
            "Rollback completed: {$rollback['rollback_to_version']} restored");

        return redirect()->to("/releases/detail/{$versionId}?tab=rollback")
            ->with('success', 'Rollback completed. Previous version restored.');
    }

    // =================================================================
    // COMMENTS
    // =================================================================

    public function addComment()
    {
        $data = [
            'version_id'   => $this->request->getPost('version_id'),
            'comment_type' => $this->request->getPost('comment_type') ?: 'general',
            'comment'      => $this->request->getPost('comment'),
            'created_by'   => $this->getUserId(),
        ];

        $this->commentModel->insert($data);
        $this->auditModel->log($data['version_id'], $this->getUserId(), 'comment_added',
            null, $data['comment_type'], ucfirst($data['comment_type']) . ' comment added');

        return redirect()->to("/releases/detail/{$data['version_id']}?tab=overview")
            ->with('success', 'Comment added.');
    }

    // =================================================================
    // AUDIT LOG (AJAX)
    // =================================================================

    public function getAuditLog(int $id)
    {
        $logs = $this->auditModel->getLogsByVersion($id);
        return $this->response->setJSON(['success' => true, 'data' => $logs]);
    }

    // =================================================================
    // VERSION THREAD (AJAX)
    // =================================================================

    public function getVersionThread(int $id)
    {
        $thread = $this->versionModel->getVersionThread($id);
        return $this->response->setJSON(['success' => true, 'data' => $thread]);
    }

    // =================================================================
    // HELPERS
    // =================================================================

    /**
     * Get employees list for dropdowns
     */
    protected function getEmployeesList(): array
    {
        $db = \Config\Database::connect();
        return $db->table('employees')
            ->select('id, name, email')
            ->where('status', 'active')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();
    }
}
