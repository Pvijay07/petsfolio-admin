<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;

class IdentityVerificationController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        helper(['Common_helper', 'url']);
    }

    /**
     * Display Aadhaar / DigiLocker Verification Logs with KPI metrics and filtering.
     */
    public function index()
    {
        $status    = trim($this->request->getGet('status') ?? '');
        $dateRange = trim($this->request->getGet('date_range') ?? 'all');
        $search    = trim($this->request->getGet('search') ?? '');
        $page      = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage   = 30;
        $offset    = ($page - 1) * $perPage;

        // KPI Totals across all attempts
        $kpiQuery = $this->db->query("
            SELECT 
                COUNT(*) AS total_attempts,
                SUM(CASE WHEN status = 'SUCCESS' THEN 1 ELSE 0 END) AS total_success,
                SUM(CASE WHEN status = 'FAILED' THEN 1 ELSE 0 END) AS total_failed,
                SUM(CASE WHEN status = 'MISMATCH' THEN 1 ELSE 0 END) AS total_mismatch,
                SUM(CASE WHEN status = 'PENDING' THEN 1 ELSE 0 END) AS total_pending,
                SUM(CASE WHEN status = 'CONSENT_DENIED' THEN 1 ELSE 0 END) AS total_consent_denied,
                SUM(CASE WHEN DATE(started_at) = CURRENT_DATE() THEN 1 ELSE 0 END) AS today_attempts,
                SUM(CASE WHEN status = 'SUCCESS' AND DATE(started_at) = CURRENT_DATE() THEN 1 ELSE 0 END) AS today_success
            FROM provider_identity_verification_attempts
        ");
        $kpis = $kpiQuery->getRowArray() ?? [
            'total_attempts'       => 0,
            'total_success'        => 0,
            'total_failed'         => 0,
            'total_mismatch'       => 0,
            'total_pending'        => 0,
            'total_consent_denied' => 0,
            'today_attempts'       => 0,
            'today_success'        => 0,
        ];

        // Build filtered query
        $builder = $this->db->table('provider_identity_verification_attempts a')
            ->select('
                a.id,
                a.provider_identity_verification_id,
                a.provider_id,
                a.verification_id,
                a.cashfree_reference_id,
                a.status,
                a.failure_reason,
                a.started_at,
                a.completed_at,
                a.created_at,
                sp.name AS provider_name,
                sp.phone AS provider_phone,
                sp.email AS provider_email,
                sp.profile AS provider_image,
                v.aadhaar_last4,
                v.name AS verified_name,
                v.dob AS verified_dob,
                v.gender AS verified_gender,
                v.document_status,
                v.verified_at
            ')
            ->join('service_providers sp', 'sp.id = a.provider_id', 'left')
            ->join('provider_identity_verifications v', 'v.id = a.provider_identity_verification_id OR (a.provider_identity_verification_id IS NULL AND v.verification_id = a.verification_id)', 'left');

        // Apply Status Filter
        if (!empty($status) && $status !== 'all') {
            $builder->where('a.status', $status);
        }

        // Apply Date Filter
        if ($dateRange === 'today') {
            $builder->where('DATE(a.started_at) = CURRENT_DATE()');
        } elseif ($dateRange === 'last_7_days') {
            $builder->where('a.started_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)');
        } elseif ($dateRange === 'last_30_days') {
            $builder->where('a.started_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)');
        }

        // Apply Search Query
        if (!empty($search)) {
            $builder->groupStart()
                ->like('sp.name', $search)
                ->orLike('sp.phone', $search)
                ->orLike('sp.email', $search)
                ->orLike('a.provider_id', $search)
                ->orLike('a.verification_id', $search)
                ->orLike('a.cashfree_reference_id', $search)
                ->orLike('a.failure_reason', $search)
                ->groupEnd();
        }

        // Clone builder for total count
        $countBuilder = clone $builder;
        $totalRecords = $countBuilder->countAllResults(false);

        // Fetch logs paginated
        $logs = $builder->orderBy('a.id', 'DESC')
            ->limit($perPage, $offset)
            ->get()
            ->getResultArray();

        $totalPages = ceil($totalRecords / $perPage);

        $data = [
            'title'        => 'Aadhaar Verification Logs',
            'page'         => 'aadhaar-verification-logs',
            'kpis'         => $kpis,
            'logs'         => $logs,
            'filters'      => [
                'status'     => $status,
                'date_range' => $dateRange,
                'search'     => $search,
                'page'       => $page,
                'per_page'   => $perPage,
            ],
            'pagination'   => [
                'total_records' => $totalRecords,
                'total_pages'   => $totalPages,
                'current_page'  => $page,
                'per_page'      => $perPage,
            ],
        ];

        return view('admin/provider/aadhaar_verification_logs', $data);
    }

    /**
     * AJAX endpoint to fetch detailed data of a single attempt.
     */
    public function getAttemptDetails($id)
    {
        $id = (int) $id;

        $attempt = $this->db->table('provider_identity_verification_attempts a')
            ->select('
                a.id,
                a.provider_identity_verification_id,
                a.provider_id,
                a.verification_id,
                a.cashfree_reference_id,
                a.status,
                a.failure_reason,
                a.started_at,
                a.completed_at,
                a.created_at,
                a.updated_at,
                sp.name AS provider_name,
                sp.phone AS provider_phone,
                sp.email AS provider_email,
                sp.profile AS provider_image,
                sp.permanent_address AS provider_registered_address,
                v.id AS verification_record_id,
                v.verification_type,
                v.document_status,
                v.aadhaar_last4,
                v.name AS verified_name,
                v.dob AS verified_dob,
                v.gender AS verified_gender,
                v.verification_url,
                v.document_data,
                v.verified_at,
                v.document_fetched_at
            ')
            ->join('service_providers sp', 'sp.id = a.provider_id', 'left')
            ->join('provider_identity_verifications v', 'v.id = a.provider_identity_verification_id OR (a.provider_identity_verification_id IS NULL AND v.verification_id = a.verification_id)', 'left')
            ->where('a.id', $id)
            ->get()
            ->getRowArray();

        if (!$attempt) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Verification attempt not found.',
            ])->setStatusCode(404);
        }

        // Parse document_data JSON if available
        $documentData = null;
        if (!empty($attempt['document_data'])) {
            $parsed = json_decode($attempt['document_data'], true);
            if (is_array($parsed)) {
                // Mask sensitive aadhaar number
                if (isset($parsed['aadhaar_number'])) {
                    $raw = (string) $parsed['aadhaar_number'];
                    $parsed['aadhaar_number_masked'] = strlen($raw) >= 4 ? str_repeat('X', strlen($raw) - 4) . substr($raw, -4) : 'XXXX-XXXX';
                    unset($parsed['aadhaar_number']); // do not expose raw aadhaar
                }
                $documentData = $parsed;
            }
        }
        $attempt['document_data_parsed'] = $documentData;
        unset($attempt['document_data']);

        // Check if provider has other attempts
        $allAttempts = $this->db->table('provider_identity_verification_attempts')
            ->select('id, verification_id, status, failure_reason, started_at, completed_at')
            ->where('provider_id', $attempt['provider_id'])
            ->orderBy('id', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        $attempt['provider_history'] = $allAttempts;

        return $this->response->setJSON([
            'success' => true,
            'data'    => $attempt,
        ]);
    }

    /**
     * CSV Export of filtered verification logs.
     */
    public function exportCsv()
    {
        $status    = trim($this->request->getGet('status') ?? '');
        $dateRange = trim($this->request->getGet('date_range') ?? 'all');
        $search    = trim($this->request->getGet('search') ?? '');

        $builder = $this->db->table('provider_identity_verification_attempts a')
            ->select('
                a.id,
                a.provider_id,
                sp.name AS provider_name,
                sp.phone AS provider_phone,
                a.verification_id,
                a.cashfree_reference_id,
                a.status,
                a.failure_reason,
                v.aadhaar_last4,
                v.name AS verified_name,
                a.started_at,
                a.completed_at
            ')
            ->join('service_providers sp', 'sp.id = a.provider_id', 'left')
            ->join('provider_identity_verifications v', 'v.id = a.provider_identity_verification_id OR (a.provider_identity_verification_id IS NULL AND v.verification_id = a.verification_id)', 'left');

        if (!empty($status) && $status !== 'all') {
            $builder->where('a.status', $status);
        }

        if ($dateRange === 'today') {
            $builder->where('DATE(a.started_at) = CURRENT_DATE()');
        } elseif ($dateRange === 'last_7_days') {
            $builder->where('a.started_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)');
        } elseif ($dateRange === 'last_30_days') {
            $builder->where('a.started_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)');
        }

        if (!empty($search)) {
            $builder->groupStart()
                ->like('sp.name', $search)
                ->orLike('sp.phone', $search)
                ->orLike('a.provider_id', $search)
                ->orLike('a.verification_id', $search)
                ->orLike('a.cashfree_reference_id', $search)
                ->orLike('a.failure_reason', $search)
                ->groupEnd();
        }

        $logs = $builder->orderBy('a.id', 'DESC')->limit(1000)->get()->getResultArray();

        $filename = 'aadhaar_verification_logs_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $fp = fopen('php://output', 'w');

        fputcsv($fp, ['Attempt ID', 'Provider ID', 'Provider Name', 'Phone', 'Verification ID', 'Cashfree Ref ID', 'Status', 'Failure Reason', 'Aadhaar Last 4', 'Verified Name', 'Started At', 'Completed At']);

        foreach ($logs as $row) {
            fputcsv($fp, [
                $row['id'],
                $row['provider_id'],
                $row['provider_name'],
                $row['provider_phone'],
                $row['verification_id'],
                $row['cashfree_reference_id'],
                $row['status'],
                $row['failure_reason'],
                $row['aadhaar_last4'],
                $row['verified_name'],
                $row['started_at'],
                $row['completed_at'],
            ]);
        }

        fclose($fp);
        exit;
    }
}
