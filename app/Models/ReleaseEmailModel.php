<?php

namespace App\Models;

use CodeIgniter\Model;

class ReleaseEmailModel extends Model
{
    protected $table            = 'release_emails';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'version_id', 'subject', 'body_html', 'recipients',
        'sent_by', 'sent_at', 'status', 'error_message'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';

    /**
     * Get emails for a version
     */
    public function getEmailsByVersion(int $versionId): array
    {
        return $this->db->table('release_emails re')
            ->select('re.*, e.name as sender_name')
            ->join('employees e', 'e.id = re.sent_by', 'left')
            ->where('re.version_id', $versionId)
            ->orderBy('re.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get the latest email for a version
     */
    public function getLatestEmail(int $versionId): ?array
    {
        return $this->db->table('release_emails re')
            ->select('re.*, e.name as sender_name')
            ->join('employees e', 'e.id = re.sent_by', 'left')
            ->where('re.version_id', $versionId)
            ->orderBy('re.created_at', 'DESC')
            ->get()
            ->getRowArray();
    }

    /**
     * Auto-generate email body from release data
     */
    public function generateEmailBody(int $versionId): array
    {
        $versionModel   = new ReleaseVersionModel();
        $updateModel    = new ReleaseUpdateModel();
        $approvalModel  = new ReleaseApprovalModel();
        $backupModel    = new ReleaseBackupModel();
        $checklistModel = new ReleaseChecklistModel();

        $version   = $versionModel->getVersionDetail($versionId);
        $updates   = $updateModel->getUpdatesByVersion($versionId);
        $approvals = $approvalModel->getApprovalsByVersion($versionId);
        $backup    = $backupModel->getBackupByVersion($versionId);
        $qaPercent = $checklistModel->getCompletionPercentage($versionId);

        if (!$version) {
            return ['subject' => '', 'body' => ''];
        }

        $subject = "Petsfolio Release Approval – Version {$version['version_number']}";

        // Build the email body
        $platformLabel = ucfirst($version['platform']);
        $typeLabel     = ucfirst($version['release_type']);
        $statusLabel   = $versionModel->getStatusLabel($version['status']);
        $plannedDate   = $version['planned_release_at']
            ? date('d-M-Y H:i', strtotime($version['planned_release_at']))
            : 'Not scheduled';

        $body = view('emails/release_approval', [
            'version'     => $version,
            'updates'     => $updates,
            'approvals'   => $approvals,
            'backup'      => $backup,
            'qaPercent'   => $qaPercent,
            'platformLabel' => $platformLabel,
            'typeLabel'   => $typeLabel,
            'statusLabel' => $statusLabel,
            'plannedDate' => $plannedDate,
        ]);

        return [
            'subject' => $subject,
            'body'    => $body,
        ];
    }

    /**
     * Mark an email as sent
     */
    public function markSent(int $id, int $userId): bool
    {
        return $this->update($id, [
            'status'  => 'sent',
            'sent_by' => $userId,
            'sent_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Mark an email as failed
     */
    public function markFailed(int $id, string $errorMessage): bool
    {
        return $this->update($id, [
            'status'        => 'failed',
            'error_message' => $errorMessage,
        ]);
    }
}
