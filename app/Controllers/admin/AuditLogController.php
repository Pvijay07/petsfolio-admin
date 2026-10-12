<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\AuditLogModel;

class AuditLogController extends BaseController
{
    protected AuditLogModel $auditModel;

    public function __construct()
    {
        $this->auditModel = new AuditLogModel();
    }

    public function index()
    {
        $metrics = $this->auditModel->getLogMetrics();
        $actors = $this->auditModel->getDistinctActors();
        $entityTypes = $this->auditModel->getDistinctEntityTypes();

        $data = [
            'title'       => 'Activity & Audit Trail',
            'page'        => 'audit_logs',
            'metrics'     => $metrics,
            'actors'      => $actors,
            'entityTypes' => $entityTypes,
        ];

        return $this->loadView('admin/audit_logs/index', $data);
    }

    public function data()
    {
        $request = $this->request;

        $filters = [
            'search'      => $request->getGet('search') ?: '',
            'user_id'     => $request->getGet('user_id') ?: '',
            'action'      => $request->getGet('action') ?: '',
            'entity_type' => $request->getGet('entity_type') ?: '',
            'date_from'   => $request->getGet('date_from') ?: '',
            'date_to'     => $request->getGet('date_to') ?: '',
        ];

        $page = max(1, (int) ($request->getGet('page') ?: 1));
        $perPage = max(10, min(100, (int) ($request->getGet('per_page') ?: 25)));
        $offset = ($page - 1) * $perPage;

        $totalRecords = $this->auditModel->countFilteredLogs($filters);
        $logs = $this->auditModel->getFilteredLogs($filters, $perPage, $offset);

        // Format dates and relative time
        $formattedLogs = array_map(function ($log) {
            $timeAgo = $this->timeElapsedString($log->created_at);
            return [
                'id'          => $log->id,
                'entity_type' => $log->entity_type,
                'entity_id'   => $log->entity_id,
                'actor_id'    => $log->actor_id,
                'user_name'   => $log->user_name ?: 'Unknown',
                'user_email'  => $log->user_email ?: '--',
                'role_name'   => $log->role_name ?: '--',
                'action'      => $log->action,
                'method'      => $log->method ?: 'GET',
                'url'         => $log->url,
                'description' => $log->description,
                'ip_address'  => $log->ip_address,
                'user_agent'  => $log->user_agent,
                'has_payload' => !empty($log->new_state) || !empty($log->metadata) || !empty($log->old_state),
                'created_at'  => date('M d, Y h:i:s A', strtotime($log->created_at)),
                'time_ago'    => $timeAgo,
            ];
        }, $logs);

        $totalPages = ceil($totalRecords / $perPage);

        return $this->response->setJSON([
            'success'      => true,
            'data'         => $formattedLogs,
            'pagination'   => [
                'current_page'  => $page,
                'per_page'      => $perPage,
                'total_records' => $totalRecords,
                'total_pages'   => $totalPages,
            ],
            'metrics'      => $this->auditModel->getLogMetrics(),
        ]);
    }

    public function details($id)
    {
        $log = $this->auditModel->find($id);
        if (!$log) {
            return $this->response->setJSON(['success' => false, 'message' => 'Log entry not found']);
        }

        $metadata = !empty($log->metadata) ? json_decode($log->metadata, true) : null;
        $oldState = !empty($log->old_state) ? json_decode($log->old_state, true) : null;
        $newState = !empty($log->new_state) ? json_decode($log->new_state, true) : null;

        return $this->response->setJSON([
            'success' => true,
            'log'     => [
                'id'          => $log->id,
                'entity_type' => $log->entity_type,
                'entity_id'   => $log->entity_id,
                'actor_id'    => $log->actor_id,
                'user_name'   => $log->user_name,
                'user_email'  => $log->user_email,
                'role_name'   => $log->role_name,
                'action'      => $log->action,
                'method'      => $log->method,
                'url'         => $log->url,
                'description' => $log->description,
                'ip_address'  => $log->ip_address,
                'user_agent'  => $log->user_agent,
                'metadata'    => $metadata,
                'old_state'   => $oldState,
                'new_state'   => $newState,
                'created_at'  => date('M d, Y h:i:s A', strtotime($log->created_at)),
            ],
        ]);
    }

    protected function timeElapsedString($datetime)
    {
        $now = new \DateTime();
        $ago = new \DateTime($datetime);
        $diff = $now->diff($ago);

        if ($diff->y > 0) return $diff->y . 'y ago';
        if ($diff->m > 0) return $diff->m . 'mo ago';
        if ($diff->d > 0) return $diff->d . 'd ago';
        if ($diff->h > 0) return $diff->h . 'h ago';
        if ($diff->i > 0) return $diff->i . 'm ago';
        return 'just now';
    }
}
