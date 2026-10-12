<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class ReleaseVersionModel extends Model
{
    protected $table            = 'release_versions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'version_number',
        'build_number',
        'platform',
        'release_type',
        'status',
        'previous_version_id',
        'rollback_version_id',
        'release_notes',
        'planned_release_at',
        'released_at',
        'monitoring_period',
        'monitoring_ends_at',
        'created_by',
        'approved_at',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ensure tables exist upon access (self-healing deployment)
     */
    public function ensureTablesExist()
    {
        $db = Database::connect();
        try {
            if (!$db->tableExists('release_versions')) {
                $migration = new \App\Database\Migrations\CreateVersionReleaseManagementTables();
                $migration->up();
                $this->seedInitialVersion();
            }
        } catch (\Throwable $e) {
            log_message('error', 'Error ensuring version release tables: ' . $e->getMessage());
        }
    }

    /**
     * Seed a starter live version if database is empty
     */
    public function seedInitialVersion()
    {
        $count = $this->countAllResults();
        if ($count === 0) {
            $initialData = [
                'version_number'     => '2.4.0',
                'build_number'       => '104',
                'platform'           => 'both',
                'release_type'       => 'minor',
                'status'             => 'live',
                'release_notes'      => "Live Production Release:\n- Enhanced pet tracking and OTP verification.\n- Integrated Cashfree instant payout notifications.\n- Fixed pet onboarding photo upload compression.\n- Optimized service provider matching speed.",
                'planned_release_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'released_at'        => date('Y-m-d H:i:s', strtotime('-10 days')),
                'created_by'         => 1,
                'created_at'         => date('Y-m-d H:i:s', strtotime('-10 days')),
                'updated_at'         => date('Y-m-d H:i:s', strtotime('-10 days')),
            ];
            $id = $this->insert($initialData);

            if ($id) {
                // Populate default checklists for the initial version
                $checklistModel = new ReleaseChecklistModel();
                $checklistModel->populateDefaultChecklists($id);

                // Add sample updates
                $updateModel = new ReleaseUpdateModel();
                $updateModel->insert([
                    'version_id'  => $id,
                    'category'    => 'new_features',
                    'title'       => 'Live Walking Path Tracking in Client App',
                    'description' => 'Real-time GPS coordinate streaming for pet parents during walking sessions.',
                    'ticket_id'   => 'PF-2041',
                    'status'      => 'completed'
                ]);
                $updateModel->insert([
                    'version_id'  => $id,
                    'category'    => 'bug_fixes',
                    'title'       => 'Fixed OTP SMS timeout on login screen',
                    'description' => 'Extended timeout threshold and fallback to WhatsApp OTP service.',
                    'ticket_id'   => 'PF-2055',
                    'status'      => 'completed'
                ]);

                // Mark checklists as passed
                $itemModel = new ReleaseChecklistItemModel();
                $itemModel->where('version_id', $id)->set([
                    'status'     => 'passed',
                    'checked_by' => 1,
                    'checked_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
                    'comment'    => 'Pre-release checks verified successfully for v2.4.0 live baseline'
                ])->update();
            }
        }
    }

    /**
     * Get all versions with progress metrics & update counts
     */
    public function getAllVersionsWithMetrics()
    {
        $this->ensureTablesExist();

        $versions = $this->orderBy('id', 'DESC')->findAll();
        $db = Database::connect();

        foreach ($versions as &$version) {
            $vid = $version['id'];

            // Checklist stats
            $totalItems = $db->table('release_checklist_items')->where('version_id', $vid)->countAllResults();
            $passedItems = $db->table('release_checklist_items')->where('version_id', $vid)->where('status', 'passed')->countAllResults();
            $failedItems = $db->table('release_checklist_items')->where('version_id', $vid)->where('status', 'failed')->countAllResults();
            $pendingItems = $db->table('release_checklist_items')->where('version_id', $vid)->where('status', 'pending')->countAllResults();

            $percentage = $totalItems > 0 ? round(($passedItems / $totalItems) * 100) : 0;

            $version['checklist_stats'] = [
                'total'      => $totalItems,
                'passed'     => $passedItems,
                'failed'     => $failedItems,
                'pending'    => $pendingItems,
                'percentage' => $percentage
            ];

            // Updates count
            $version['updates_count'] = $db->table('release_updates')->where('version_id', $vid)->countAllResults();

            // Creator Name
            if (!empty($version['created_by'])) {
                $creator = $db->table('employees')->select('name, email')->where('id', $version['created_by'])->get()->getRowArray();
                $version['creator_name'] = $creator['name'] ?? 'Admin User';
            } else {
                $version['creator_name'] = 'System';
            }
        }

        return $versions;
    }

    /**
     * Get full details for a version with all related entities
     */
    public function getVersionFullDetails($id)
    {
        $this->ensureTablesExist();
        $version = $this->find($id);
        if (!$version) {
            return null;
        }

        $db = Database::connect();

        // Creator details
        $creator = $db->table('employees')->select('name, email')->where('id', $version['created_by'])->get()->getRowArray();
        $version['creator_name'] = $creator['name'] ?? 'Admin';

        // Previous version details
        if (!empty($version['previous_version_id'])) {
            $prev = $this->find($version['previous_version_id']);
            $version['previous_version'] = $prev;
        }

        // Rollback version details
        if (!empty($version['rollback_version_id'])) {
            $roll = $this->find($version['rollback_version_id']);
            $version['rollback_version'] = $roll;
        }

        // Updates grouped by category
        $updates = $db->table('release_updates')->where('version_id', $id)->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->get()->getResultArray();
        $groupedUpdates = [
            'new_features' => [],
            'enhancements' => [],
            'bug_fixes'    => [],
            'ui_ux'        => [],
            'api_backend'  => [],
            'database'     => [],
            'security'     => [],
            'third_party'  => []
        ];
        foreach ($updates as $u) {
            $cat = $u['category'];
            if (!isset($groupedUpdates[$cat])) {
                $groupedUpdates[$cat] = [];
            }
            $groupedUpdates[$cat][] = $u;
        }
        $version['updates'] = $updates;
        $version['grouped_updates'] = $groupedUpdates;

        // Checklists and their items
        $checklists = $db->table('release_checklists')->where('version_id', $id)->orderBy('sort_order', 'ASC')->get()->getResultArray();
        foreach ($checklists as &$chk) {
            $items = $db->table('release_checklist_items')
                ->select('release_checklist_items.*, employees.name as checker_name')
                ->join('employees', 'employees.id = release_checklist_items.checked_by', 'left')
                ->where('checklist_id', $chk['id'])
                ->orderBy('sort_order', 'ASC')
                ->orderBy('id', 'ASC')
                ->get()->getResultArray();
            $chk['items'] = $items;

            $total = count($items);
            $passed = count(array_filter($items, fn($i) => $i['status'] === 'passed'));
            $failed = count(array_filter($items, fn($i) => $i['status'] === 'failed'));
            $pending = count(array_filter($items, fn($i) => $i['status'] === 'pending'));
            $chk['stats'] = [
                'total'      => $total,
                'passed'     => $passed,
                'failed'     => $failed,
                'pending'    => $pending,
                'percentage' => $total > 0 ? round(($passed / $total) * 100) : 0
            ];
        }
        $version['checklists'] = $checklists;

        // Backup details
        $version['backup'] = $db->table('release_backups')->where('version_id', $id)->orderBy('id', 'DESC')->get()->getRowArray();

        // Approvals
        $approvals = $db->table('release_approvals')
            ->select('release_approvals.*, employees.name as approver_name')
            ->join('employees', 'employees.id = release_approvals.approved_by', 'left')
            ->where('version_id', $id)
            ->get()->getResultArray();
        $approvalsMap = [];
        foreach ($approvals as $appr) {
            $approvalsMap[$appr['approval_type']] = $appr;
        }
        $version['approvals'] = $approvalsMap;

        // Rollbacks
        $version['rollbacks'] = $db->table('release_rollbacks')
            ->select('release_rollbacks.*, employees.name as approver_name')
            ->join('employees', 'employees.id = release_rollbacks.approved_by', 'left')
            ->where('version_id', $id)
            ->orderBy('id', 'DESC')
            ->get()->getResultArray();

        // Comments
        $version['comments'] = $db->table('release_comments')
            ->select('release_comments.*, employees.name as author_name')
            ->join('employees', 'employees.id = release_comments.created_by', 'left')
            ->where('version_id', $id)
            ->orderBy('id', 'ASC')
            ->get()->getResultArray();

        // Audit logs
        $version['audit_logs'] = $db->table('release_audit_logs')
            ->select('release_audit_logs.*, employees.name as user_name')
            ->join('employees', 'employees.id = release_audit_logs.user_id', 'left')
            ->where('version_id', $id)
            ->orderBy('created_at', 'DESC')
            ->limit(100)
            ->get()->getResultArray();

        // Tickets
        $version['tickets'] = $db->table('release_tickets')
            ->where('version_id', $id)
            ->orderBy('id', 'ASC')
            ->get()->getResultArray();

        // Emails
        $version['emails'] = $db->table('release_emails')
            ->select('release_emails.*, employees.name as sender_name')
            ->join('employees', 'employees.id = release_emails.sent_by', 'left')
            ->where('version_id', $id)
            ->orderBy('created_at', 'DESC')
            ->get()->getResultArray();

        return $version;
    }

    /**
     * Get the latest live or monitoring version
     */
    public function getLiveVersion()
    {
        $this->ensureTablesExist();
        return $this->whereIn('status', ['live', 'monitoring', 'successful'])
                    ->orderBy('released_at', 'DESC')
                    ->orderBy('id', 'DESC')
                    ->first();
    }
}
