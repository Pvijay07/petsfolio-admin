<?php

namespace App\Models;

use CodeIgniter\Model;

class ReleaseVersionModel extends Model
{
    protected $table            = 'release_versions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'version_number', 'build_number', 'platform', 'release_type',
        'status', 'previous_version_id', 'rollback_version_id',
        'release_notes', 'planned_release_at', 'released_at',
        'monitoring_period', 'monitoring_ends_at',
        'created_by', 'approved_at', 'created_at', 'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * State machine: allowed transitions map
     */
    protected $allowedTransitions = [
        'draft'                => ['testing'],
        'testing'              => ['qa_approved', 'draft'],
        'qa_approved'          => ['backup_completed', 'testing'],
        'backup_completed'     => ['management_approved', 'qa_approved'],
        'management_approved'  => ['scheduled', 'backup_completed'],
        'scheduled'            => ['releasing', 'management_approved'],
        'releasing'            => ['live', 'scheduled'],
        'live'                 => ['monitoring', 'rollback_requested'],
        'monitoring'           => ['successful', 'rollback_requested'],
        'successful'           => [],
        'rollback_requested'   => ['rolling_back'],
        'rolling_back'         => ['rolled_back'],
        'rolled_back'          => [],
    ];

    /**
     * Status display labels
     */
    protected $statusLabels = [
        'draft'                => 'Draft',
        'testing'              => 'Testing',
        'qa_approved'          => 'QA Approved',
        'backup_completed'     => 'Backup Completed',
        'management_approved'  => 'Management Approved',
        'scheduled'            => 'Scheduled',
        'releasing'            => 'Releasing',
        'live'                 => 'Live',
        'monitoring'           => 'Monitoring',
        'successful'           => 'Successful',
        'rollback_requested'   => 'Rollback Requested',
        'rolling_back'         => 'Rolling Back',
        'rolled_back'          => 'Rolled Back',
    ];

    /**
     * Status badge colors (Bootstrap)
     */
    protected $statusColors = [
        'draft'                => 'secondary',
        'testing'              => 'info',
        'qa_approved'          => 'primary',
        'backup_completed'     => 'primary',
        'management_approved'  => 'success',
        'scheduled'            => 'warning',
        'releasing'            => 'warning',
        'live'                 => 'success',
        'monitoring'           => 'info',
        'successful'           => 'success',
        'rollback_requested'   => 'danger',
        'rolling_back'         => 'danger',
        'rolled_back'          => 'dark',
    ];

    // ---------------------------------------------------------------
    // GETTERS
    // ---------------------------------------------------------------

    public function getStatusLabel(string $status): string
    {
        return $this->statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }

    public function getStatusColor(string $status): string
    {
        return $this->statusColors[$status] ?? 'secondary';
    }

    public function getAllStatusLabels(): array
    {
        return $this->statusLabels;
    }

    // ---------------------------------------------------------------
    // LIST & DETAIL QUERIES
    // ---------------------------------------------------------------

    /**
     * Get paginated version list with previous version info
     */
    public function getVersionsList(array $filters = []): array
    {
        $builder = $this->db->table('release_versions rv')
            ->select('rv.*, pv.version_number as previous_version_number, e.name as creator_name')
            ->join('release_versions pv', 'pv.id = rv.previous_version_id', 'left')
            ->join('employees e', 'e.id = rv.created_by', 'left')
            ->orderBy('rv.created_at', 'DESC');

        if (!empty($filters['status'])) {
            $builder->where('rv.status', $filters['status']);
        }
        if (!empty($filters['platform'])) {
            $builder->where('rv.platform', $filters['platform']);
        }
        if (!empty($filters['release_type'])) {
            $builder->where('rv.release_type', $filters['release_type']);
        }
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('rv.version_number', $filters['search'])
                ->orLike('rv.release_notes', $filters['search'])
                ->groupEnd();
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get full version detail with creator + previous version info
     */
    public function getVersionDetail(int $id): ?array
    {
        return $this->db->table('release_versions rv')
            ->select('rv.*, pv.version_number as previous_version_number,
                      rbv.version_number as rollback_version_number,
                      e.name as creator_name')
            ->join('release_versions pv', 'pv.id = rv.previous_version_id', 'left')
            ->join('release_versions rbv', 'rbv.id = rv.rollback_version_id', 'left')
            ->join('employees e', 'e.id = rv.created_by', 'left')
            ->where('rv.id', $id)
            ->get()
            ->getRowArray();
    }

    /**
     * Get the version chain (thread) for a given version
     */
    public function getVersionThread(int $id): array
    {
        $thread = [];

        // Walk backwards to find the root
        $currentId = $id;
        $ancestors = [];
        $safety = 0;
        while ($currentId && $safety < 50) {
            $version = $this->find($currentId);
            if (!$version) break;
            array_unshift($ancestors, $version);
            $currentId = $version['previous_version_id'];
            $safety++;
        }

        // Walk forwards to find descendants
        $descendants = [];
        $nextVersions = $this->db->table('release_versions')
            ->where('previous_version_id', $id)
            ->orderBy('created_at', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($nextVersions as $nv) {
            $descendants[] = $nv;
            // One more level deep
            $children = $this->db->table('release_versions')
                ->where('previous_version_id', $nv['id'])
                ->orderBy('created_at', 'ASC')
                ->get()
                ->getResultArray();
            $descendants = array_merge($descendants, $children);
        }

        // Merge: ancestors (without current) + current + descendants
        $thread = $ancestors;
        foreach ($descendants as $d) {
            $thread[] = $d;
        }

        return $thread;
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(): array
    {
        $stats = [];
        $statuses = ['draft', 'testing', 'qa_approved', 'live', 'monitoring', 'successful', 'rolled_back'];
        foreach ($statuses as $status) {
            $stats[$status] = $this->where('status', $status)->countAllResults(false);
        }
        $stats['total'] = $this->countAllResults(false);

        // Active releases (not terminal)
        $stats['active'] = $this->whereNotIn('status', ['successful', 'rolled_back'])
            ->countAllResults(false);

        return $stats;
    }

    /**
     * Get all versions for dropdown selects
     */
    public function getVersionsForDropdown(): array
    {
        return $this->select('id, version_number, status')
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    // ---------------------------------------------------------------
    // STATE MACHINE
    // ---------------------------------------------------------------

    /**
     * Validate and execute a status transition
     *
     * @return array ['success' => bool, 'message' => string]
     */
    public function transitionStatus(int $id, string $newStatus, int $userId): array
    {
        $version = $this->find($id);
        if (!$version) {
            return ['success' => false, 'message' => 'Version not found.'];
        }

        $currentStatus = $version['status'];

        if (!isset($this->allowedTransitions[$currentStatus])) {
            return ['success' => false, 'message' => "Current status '{$currentStatus}' is invalid."];
        }

        if (!in_array($newStatus, $this->allowedTransitions[$currentStatus])) {
            $allowed = implode(', ', $this->allowedTransitions[$currentStatus]);
            return [
                'success' => false,
                'message' => "Cannot transition from '{$currentStatus}' to '{$newStatus}'. Allowed: {$allowed}"
            ];
        }

        // Additional validations per transition
        $validation = $this->validateTransition($id, $currentStatus, $newStatus);
        if (!$validation['success']) {
            return $validation;
        }

        // Perform the transition
        $updateData = ['status' => $newStatus];

        // Set timestamps for specific transitions
        if ($newStatus === 'live' || $newStatus === 'releasing') {
            $updateData['released_at'] = date('Y-m-d H:i:s');
        }
        if (in_array($newStatus, ['management_approved', 'qa_approved'])) {
            $updateData['approved_at'] = date('Y-m-d H:i:s');
        }
        if ($newStatus === 'monitoring') {
            $period = $version['monitoring_period'] ?? '1hr';
            $minutes = ['30min' => 30, '1hr' => 60, '4hr' => 240, '24hr' => 1440];
            $mins = $minutes[$period] ?? 60;
            $updateData['monitoring_ends_at'] = date('Y-m-d H:i:s', strtotime("+{$mins} minutes"));
        }

        $this->update($id, $updateData);

        // Log the audit trail
        $auditModel = new ReleaseAuditLogModel();
        $auditModel->log($id, $userId, 'status_changed', $currentStatus, $newStatus,
            "Status changed from {$this->getStatusLabel($currentStatus)} to {$this->getStatusLabel($newStatus)}");

        return ['success' => true, 'message' => "Status changed to {$this->getStatusLabel($newStatus)}."];
    }

    /**
     * Validate business rules for specific transitions
     */
    protected function validateTransition(int $id, string $from, string $to): array
    {
        // QA Approved → Backup Completed: require backup record
        if ($to === 'backup_completed') {
            $backupCount = $this->db->table('release_backups')
                ->where('version_id', $id)
                ->countAllResults();
            if ($backupCount === 0) {
                return ['success' => false, 'message' => 'Cannot proceed: No backup record found. Please create a backup first.'];
            }
        }

        // Backup Completed → Management Approved: require QA approval
        if ($to === 'management_approved') {
            $qaApproval = $this->db->table('release_approvals')
                ->where('version_id', $id)
                ->where('approval_type', 'qa')
                ->where('status', 'approved')
                ->countAllResults();
            if ($qaApproval === 0) {
                return ['success' => false, 'message' => 'Cannot proceed: QA approval is required before management approval.'];
            }
        }

        return ['success' => true, 'message' => ''];
    }

    /**
     * Get allowed next statuses for a version
     */
    public function getAllowedTransitions(string $currentStatus): array
    {
        return $this->allowedTransitions[$currentStatus] ?? [];
    }

    /**
     * Get the QA checklist completion percentage for a version
     */
    public function getChecklistProgress(int $versionId): float
    {
        $total = $this->db->table('release_checklist_items')
            ->where('version_id', $versionId)
            ->countAllResults();

        if ($total === 0) return 0;

        $passed = $this->db->table('release_checklist_items')
            ->where('version_id', $versionId)
            ->whereIn('status', ['passed', 'na'])
            ->countAllResults();

        return round(($passed / $total) * 100, 1);
    }
}
