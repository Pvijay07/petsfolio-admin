<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\CallRecordingModel;
use App\Models\CallAnalysisModel;
use App\Models\CallKeywordModel;
use App\Models\CallJobModel;
use App\Services\RecordingService;
use App\Services\ClientCallRatingService;
use App\Services\SpViolationWorkflowService;

class ClientCallAnalysisController extends BaseController
{
    protected CallRecordingModel $recordingModel;
    protected CallAnalysisModel $analysisModel;
    protected CallKeywordModel $keywordModel;
    protected CallJobModel $jobModel;
    protected RecordingService $recordingService;
    protected ClientCallRatingService $ratingService;
    protected SpViolationWorkflowService $workflowService;
    protected $db;

    public function __construct()
    {
        $this->recordingModel   = new CallRecordingModel();
        $this->analysisModel    = new CallAnalysisModel();
        $this->keywordModel     = new CallKeywordModel();
        $this->jobModel         = new CallJobModel();
        $this->recordingService = new RecordingService();
        $this->ratingService    = new ClientCallRatingService();
        $this->workflowService  = new SpViolationWorkflowService();
        $this->db               = \Config\Database::connect();

        $this->ensureColumnsExist();
    }

    /**
     * Display the Client <-> Provider Call Intelligence Dashboard.
     */
    public function index()
    {
        $this->ensureColumnsExist();

        $selectedService = $this->request->getGet('service') ? strtolower($this->request->getGet('service')) : null;
        $selectedRating  = $this->request->getGet('rating') ? strtolower($this->request->getGet('rating')) : null;
        $selectedFlag    = $this->request->getGet('flag') ? strtolower($this->request->getGet('flag')) : null;

        $clientCallWhere = "(cr.call_type IN ('client_provider', 'client_sp') OR (cr.customer_id IS NOT NULL AND cr.customer_id > 0 AND (cr.verifier_id IS NULL OR cr.verifier_id = 0)))";

        // Base query for client-provider calls
        $baseBuilder = $this->db->table('call_recordings cr')
            ->where($clientCallWhere);

        // Total Counts & KPI Metrics
        $totalRecordings = (clone $baseBuilder)->countAllResults();
        $completedCount  = (clone $baseBuilder)->where('cr.status', 'completed')->countAllResults();
        $queuedCount     = (clone $baseBuilder)->where('cr.status', 'queued')->countAllResults();
        $failedCount     = (clone $baseBuilder)->where('cr.status', 'failed')->countAllResults();

        // Aggregated KPI Stats
        $kpiRow = $this->db->table('call_recordings cr')
            ->select("
                ROUND(AVG(COALESCE(ca.service_relevance_score, 8.5)), 1) as avg_relevance,
                ROUND(AVG(COALESCE(ca.overall_rating, 8.5)), 1) as avg_rating,
                SUM(CASE WHEN ca.off_platform_payment = 1 THEN 1 ELSE 0 END) as off_platform_count,
                SUM(CASE WHEN ca.contact_sharing = 1 THEN 1 ELSE 0 END) as contact_sharing_count,
                SUM(CASE WHEN ca.has_complaint = 1 OR ca.refund_requested = 1 THEN 1 ELSE 0 END) as complaint_count,
                SUM(CASE WHEN ca.outside_service_request = 1 THEN 1 ELSE 0 END) as outside_svc_count,
                SUM(CASE WHEN ca.ai_rating_category = 'critical' OR ca.overall_rating < 5.0 THEN 1 ELSE 0 END) as critical_count,
                SUM(CASE WHEN ca.ai_rating_category = 'review_recommended' THEN 1 ELSE 0 END) as review_count,
                SUM(CASE WHEN ca.service_related_pct >= 80.0 OR ca.overall_rating >= 8.0 THEN 1 ELSE 0 END) as high_relevance_count
            ")
            ->join('call_analysis ca', 'ca.recording_id = cr.id', 'left')
            ->where($clientCallWhere)
            ->get()
            ->getRowArray();

        $avgRelevance      = (float)($kpiRow['avg_relevance'] ?? 9.0);
        $avgRating         = (float)($kpiRow['avg_rating'] ?? 9.0);
        $offPlatformCount  = (int)($kpiRow['off_platform_count'] ?? 0);
        $contactShareCount = (int)($kpiRow['contact_sharing_count'] ?? 0);
        $complaintCount    = (int)($kpiRow['complaint_count'] ?? 0);
        $outsideSvcCount   = (int)($kpiRow['outside_svc_count'] ?? 0);
        $criticalCount     = (int)($kpiRow['critical_count'] ?? 0);
        $reviewCount       = (int)($kpiRow['review_count'] ?? 0);
        $highRelevancePct  = $completedCount > 0 ? round(((int)($kpiRow['high_relevance_count'] ?? 0) / $completedCount) * 100, 1) : 100;
        $openCasesCount    = $this->db->table('sp_violation_cases')->where('status', 'open')->countAllResults();

        // Fetch Call Recordings List with Client, Provider, Analysis & Violation Case Info
        $callsQuery = $this->db->table('call_recordings cr')
            ->select("
                cr.*,
                c.started_at as call_started_at,
                c.ended_at as call_ended_at,
                c.created_at as call_initiated_at,
                ca.id as analysis_id,
                ca.summary,
                ca.service_type as analyzed_service,
                ca.service_relevance_score,
                ca.service_related_pct,
                ca.unrelated_pct,
                ca.overall_rating,
                ca.customer_sentiment,
                ca.provider_sentiment,
                ca.ai_rating_category,
                ca.off_platform_payment,
                ca.contact_sharing,
                ca.outside_service_request,
                ca.refund_requested,
                ca.has_complaint,
                ca.has_abuse,
                ca.admin_alert_required,
                ca.admin_review_status,
                ca.admin_remarks,
                ca.service_topics,
                ca.alerts,
                u.name as client_name,
                u.phone as client_phone,
                p.name as provider_name,
                p.phone as provider_phone,
                p.is_on_hold as provider_is_on_hold,
                p.hold_expires_at as provider_hold_expires_at,
                p.hold_reason as provider_hold_reason,
                vc.id as violation_case_id,
                vc.case_number as violation_case_number,
                vc.status as violation_case_status,
                vc.severity as violation_severity,
                vc.has_active_services,
                vc.affected_bookings_count
            ")
            ->join('calls c', 'c.id = cr.call_id', 'left')
            ->join('call_analysis ca', 'ca.recording_id = cr.id', 'left')
            ->join('users u', 'u.id = cr.customer_id', 'left')
            ->join('service_providers p', 'p.id = cr.provider_id', 'left')
            ->join('sp_violation_cases vc', 'vc.recording_id = cr.id', 'left')
            ->where($clientCallWhere);

        // Apply service filter
        if ($selectedService && in_array($selectedService, ['walking', 'grooming', 'training', 'sitting'])) {
            $callsQuery->groupStart()
                ->where('cr.service_type', $selectedService)
                ->orWhere('ca.service_type', $selectedService)
                ->groupEnd();
        }

        // Apply rating filter
        if ($selectedRating && in_array($selectedRating, ['good', 'review_recommended', 'critical'])) {
            $callsQuery->where('ca.ai_rating_category', $selectedRating);
        }

        // Apply policy flag filter
        if ($selectedFlag) {
            if ($selectedFlag === 'off_platform') {
                $callsQuery->where('ca.off_platform_payment', 1);
            } elseif ($selectedFlag === 'contact_sharing') {
                $callsQuery->where('ca.contact_sharing', 1);
            } elseif ($selectedFlag === 'refund') {
                $callsQuery->where('ca.refund_requested', 1);
            } elseif ($selectedFlag === 'outside_service') {
                $callsQuery->where('ca.outside_service_request', 1);
            } elseif ($selectedFlag === 'complaint') {
                $callsQuery->where('ca.has_complaint', 1);
            }
        }

        // Pagination and Search query parameters
        $page     = max(1, (int)($this->request->getGet('page') ?: 1));
        $perPage  = max(5, min(100, (int)($this->request->getGet('per_page') ?: 25)));
        $search   = trim((string)$this->request->getGet('search'));

        // Apply search keyword filter
        if (!empty($search)) {
            $callsQuery->groupStart()
                ->like('u.name', $search)
                ->orLike('u.phone', $search)
                ->orLike('p.name', $search)
                ->orLike('p.phone', $search)
                ->orLike('cr.id', $search)
                ->orLike('cr.booking_id', $search)
            ->groupEnd();
        }

        // Count total filtered calls for pagination
        $totalFiltered = (clone $callsQuery)->countAllResults(false);
        $totalPages    = max(1, (int)ceil($totalFiltered / $perPage));

        $calls = $callsQuery->orderBy('cr.created_at', 'DESC')
            ->orderBy('cr.id', 'DESC')
            ->limit($perPage, ($page - 1) * $perPage)
            ->get()
            ->getResultArray();

        // Pager Service
        $pager = service('pager');
        $pager->only(['service', 'rating', 'flag', 'search', 'per_page']);
        $pagerLinks = $pager->makeLinks($page, $perPage, $totalFiltered, 'default_full');

        // Format duration & call timings for each call
        foreach ($calls as &$c) {
            $durSec = (int)($c['duration'] ?? 0);
            if ($durSec > 0) {
                $mins = floor($durSec / 60);
                $secs = $durSec % 60;
                $c['formatted_duration'] = ($mins > 0 ? "{$mins}m " : "") . sprintf("%02ds", $secs);
            } else {
                $c['formatted_duration'] = '-';
            }

            // Call Start & End Time
            $startRaw = !empty($c['call_started_at']) && $c['call_started_at'] !== '0000-00-00 00:00:00'
                ? $c['call_started_at']
                : (!empty($c['call_initiated_at']) && $c['call_initiated_at'] !== '0000-00-00 00:00:00'
                    ? $c['call_initiated_at']
                    : (!empty($c['created_at']) && $c['created_at'] !== '0000-00-00 00:00:00' ? $c['created_at'] : null));

            if (!empty($startRaw)) {
                $startTs = strtotime($startRaw);
                $c['start_time_formatted'] = date('h:i:s A', $startTs);
                $c['start_time_short']     = date('h:i A', $startTs);
                $c['start_date_formatted'] = date('M d, Y', $startTs);

                if (!empty($c['call_ended_at']) && $c['call_ended_at'] !== '0000-00-00 00:00:00') {
                    $endTs = strtotime($c['call_ended_at']);
                } else {
                    $endTs = $startTs + $durSec;
                }
                $c['end_time_formatted'] = date('h:i:s A', $endTs);
                $c['end_time_short']     = date('h:i A', $endTs);
            } else {
                $c['start_time_formatted'] = '-';
                $c['start_time_short']     = '-';
                $c['start_date_formatted'] = '-';
                $c['end_time_formatted']   = '-';
                $c['end_time_short']       = '-';
            }
        }

        $data = [
            'totalRecordings'   => $totalRecordings,
            'completedCount'    => $completedCount,
            'queuedCount'       => $queuedCount,
            'failedCount'       => $failedCount,
            'avgRelevance'      => $avgRelevance,
            'avgRating'         => $avgRating,
            'highRelevancePct'  => $highRelevancePct,
            'offPlatformCount'  => $offPlatformCount,
            'contactShareCount' => $contactShareCount,
            'complaintCount'    => $complaintCount,
            'outsideSvcCount'   => $outsideSvcCount,
            'criticalCount'     => $criticalCount,
            'reviewCount'       => $reviewCount,
            'openCasesCount'    => $openCasesCount,
            'selectedService'   => $selectedService,
            'selectedRating'    => $selectedRating,
            'selectedFlag'      => $selectedFlag,
            'search'            => $search,
            'page'              => $page,
            'perPage'           => $perPage,
            'totalFiltered'     => $totalFiltered,
            'totalPages'        => $totalPages,
            'pager'             => $pager,
            'pagerLinks'        => $pagerLinks,
            'calls'             => $calls,
        ];

        return view('admin/client_call_analysis/index', $data);
    }

    /**
     * AJAX endpoint to return complete analysis payload for offcanvas drawer.
     */
    public function details(int $id)
    {
        $this->ensureColumnsExist();

        $call = $this->db->table('call_recordings cr')
            ->select("
                cr.*,
                c.started_at as call_started_at,
                c.ended_at as call_ended_at,
                c.created_at as call_initiated_at,
                ca.*,
                u.name as client_name,
                u.phone as client_phone,
                u.email as client_email,
                p.name as provider_name,
                p.phone as provider_phone,
                p.email as provider_email,
                p.is_on_hold as provider_is_on_hold,
                p.hold_expires_at as provider_hold_expires_at,
                p.hold_reason as provider_hold_reason,
                vc.id as violation_case_id,
                vc.case_number as violation_case_number,
                vc.status as violation_case_status,
                vc.severity as violation_severity,
                vc.evidence_snippet as violation_evidence_snippet,
                vc.evidence_timestamp as violation_evidence_timestamp,
                vc.has_active_services as violation_has_active_services,
                vc.affected_bookings_count as violation_affected_bookings_count
            ")
            ->join('calls c', 'c.id = cr.call_id', 'left')
            ->join('call_analysis ca', 'ca.recording_id = cr.id', 'left')
            ->join('users u', 'u.id = cr.customer_id', 'left')
            ->join('service_providers p', 'p.id = cr.provider_id', 'left')
            ->join('sp_violation_cases vc', 'vc.recording_id = cr.id', 'left')
            ->where('cr.id', $id)
            ->get()
            ->getRowArray();

        if (!$call) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => false,
                'message' => 'Call recording record not found.'
            ]);
        }

        // Call Start & End Time
        $startRaw = !empty($call['call_started_at']) && $call['call_started_at'] !== '0000-00-00 00:00:00'
            ? $call['call_started_at']
            : (!empty($call['call_initiated_at']) && $call['call_initiated_at'] !== '0000-00-00 00:00:00'
                ? $call['call_initiated_at']
                : (!empty($call['created_at']) && $call['created_at'] !== '0000-00-00 00:00:00' ? $call['created_at'] : null));

        $durSec = (int)($call['duration'] ?? 0);
        if (!empty($startRaw)) {
            $startTs = strtotime($startRaw);
            $call['start_time_formatted']     = date('h:i:s A', $startTs);
            $call['start_datetime_formatted'] = date('d M Y, h:i:s A', $startTs);

            if (!empty($call['call_ended_at']) && $call['call_ended_at'] !== '0000-00-00 00:00:00') {
                $endTs = strtotime($call['call_ended_at']);
            } else {
                $endTs = $startTs + $durSec;
            }
            $call['end_time_formatted']     = date('h:i:s A', $endTs);
            $call['end_datetime_formatted'] = date('d M Y, h:i:s A', $endTs);
        } else {
            $call['start_time_formatted']     = '-';
            $call['start_datetime_formatted'] = '-';
            $call['end_time_formatted']       = '-';
            $call['end_datetime_formatted']   = '-';
        }

        // Decode JSON fields safely
        $call['service_topics']           = json_decode($call['service_topics'] ?? '[]', true) ?: [];
        $call['important_statements']     = json_decode($call['important_statements'] ?? '[]', true) ?: [];
        $call['unrelated_statements']     = json_decode($call['unrelated_statements'] ?? '[]', true) ?: [];
        $call['complaint_statements']     = json_decode($call['complaint_statements'] ?? '[]', true) ?: [];
        $call['policy_violations']        = json_decode($call['policy_violations'] ?? '[]', true) ?: [];
        $call['highlighted_conversations'] = json_decode($call['highlighted_conversations'] ?? '[]', true) ?: [];
        $call['conversation_segments']    = json_decode($call['conversation_segments'] ?? '[]', true) ?: [];
        $call['alerts']                   = json_decode($call['alerts'] ?? '[]', true) ?: [];
        $call['recommendations']          = json_decode($call['recommendations'] ?? '[]', true) ?: [];
        $call['tags']                     = json_decode($call['tags'] ?? '[]', true) ?: [];

        // Extracted keywords
        $keywords = $this->db->table('call_keywords')
            ->where('analysis_id', $call['id'] ?? 0)
            ->orderBy('frequency', 'DESC')
            ->get()
            ->getResultArray();

        $call['keywords'] = $keywords ?: [];

        if (!empty($call['violation_case_id'])) {
            $call['violation_case'] = $this->workflowService->getCaseDetails((int)$call['violation_case_id']);
        } elseif (!empty($call['provider_id'])) {
            $call['active_services'] = $this->workflowService->getActiveServices((int)$call['provider_id']);
        }

        return $this->response->setJSON([
            'status' => true,
            'data'   => $call
        ]);
    }

    /**
     * AJAX endpoint to upload a client-provider call recording.
     */
    public function upload()
    {
        $file = $this->request->getFile('recording');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'No valid audio file uploaded.'
            ]);
        }

        try {
            $serviceType = strtolower($this->request->getPost('service_type') ?: 'walking');
            if (!in_array($serviceType, ['walking', 'grooming', 'training', 'sitting'])) {
                $serviceType = 'walking';
            }

            $metadata = [
                'booking_id'   => $this->request->getPost('booking_id'),
                'provider_id'  => $this->request->getPost('provider_id'),
                'customer_id'  => $this->request->getPost('customer_id'),
                'call_type'    => 'client_provider',
                'service_type' => $serviceType,
                'duration'     => (int)($this->request->getPost('duration') ?: 0),
            ];

            $result = $this->recordingService->handleUpload($file, $metadata);

            // Update service_type in recording row
            $this->recordingModel->update($result['recording_id'], [
                'call_type'    => 'client_provider',
                'service_type' => $serviceType
            ]);

            return $this->response->setJSON([
                'status'       => true,
                'message'      => 'Client-Provider call recording uploaded and queued for AI conversation analysis.',
                'recording_id' => $result['recording_id'],
                'job_id'       => $result['job_id'],
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * AJAX endpoint to save Admin review status and remarks.
     */
    public function saveAdminReview()
    {
        $this->ensureColumnsExist();

        $recordingId = (int)$this->request->getPost('recording_id');
        $reviewStatus = $this->request->getPost('admin_review_status') ?: 'reviewed';
        $remarks      = trim((string)$this->request->getPost('admin_remarks'));

        if (!$recordingId) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'Missing recording_id.'
            ]);
        }

        $existing = $this->analysisModel->where('recording_id', $recordingId)->first();
        if ($existing) {
            $this->analysisModel->update($existing['id'], [
                'admin_review_status' => $reviewStatus,
                'admin_remarks'       => $remarks
            ]);
        }

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Admin review and remarks successfully recorded.'
        ]);
    }

    /**
     * AJAX Analytics Endpoint for Chart.js.
     */
    public function getAnalyticsData()
    {
        $this->ensureColumnsExist();

        $clientCallWhere = "(cr.call_type IN ('client_provider', 'client_sp') OR (cr.customer_id IS NOT NULL AND cr.customer_id > 0 AND (cr.verifier_id IS NULL OR cr.verifier_id = 0)))";

        // 1. Service Type Breakdown & Relevance
        $serviceBreakdown = $this->db->query("
            SELECT 
                COALESCE(ca.service_type, cr.service_type, 'walking') as service_name,
                COUNT(cr.id) as total_calls,
                ROUND(AVG(COALESCE(ca.service_relevance_score, 8.5)), 1) as avg_relevance,
                ROUND(AVG(COALESCE(ca.overall_rating, 8.5)), 1) as avg_rating
            FROM call_recordings cr
            LEFT JOIN call_analysis ca ON ca.recording_id = cr.id
            WHERE {$clientCallWhere}
            GROUP BY service_name
        ")->getResultArray();

        // 2. Policy Violation & Flag Totals
        $policyViolations = $this->db->query("
            SELECT 
                SUM(CASE WHEN ca.off_platform_payment = 1 THEN 1 ELSE 0 END) as off_platform_pay,
                SUM(CASE WHEN ca.contact_sharing = 1 THEN 1 ELSE 0 END) as contact_sharing,
                SUM(CASE WHEN ca.outside_service_request = 1 THEN 1 ELSE 0 END) as outside_service,
                SUM(CASE WHEN ca.refund_requested = 1 THEN 1 ELSE 0 END) as refund_requests,
                SUM(CASE WHEN ca.has_complaint = 1 THEN 1 ELSE 0 END) as service_complaints,
                SUM(CASE WHEN ca.has_abuse = 1 THEN 1 ELSE 0 END) as abusive_language
            FROM call_recordings cr
            JOIN call_analysis ca ON ca.recording_id = cr.id
            WHERE {$clientCallWhere}
        ")->getRowArray();

        // 3. AI Rating Category Distribution
        $ratingDistribution = $this->db->query("
            SELECT 
                COALESCE(ca.ai_rating_category, 'good') as category,
                COUNT(*) as count
            FROM call_recordings cr
            JOIN call_analysis ca ON ca.recording_id = cr.id
            WHERE {$clientCallWhere}
            GROUP BY category
        ")->getResultArray();

        // 4. Sentiment Distribution
        $sentimentDistribution = $this->db->query("
            SELECT 
                COALESCE(ca.customer_sentiment, 'Positive') as sentiment,
                COUNT(*) as count
            FROM call_recordings cr
            JOIN call_analysis ca ON ca.recording_id = cr.id
            WHERE {$clientCallWhere}
            GROUP BY sentiment
        ")->getResultArray();

        return $this->response->setJSON([
            'status'             => true,
            'serviceBreakdown'   => $serviceBreakdown,
            'policyViolations'   => $policyViolations,
            'ratingDistribution' => $ratingDistribution,
            'sentimentDistribution' => $sentimentDistribution
        ]);
    }

    /**
     * Machine-to-Machine Next Job Worker Endpoint for Client Calls.
     */
    public function nextJob()
    {
        $job = $this->jobModel->fetchNextQueuedJob();

        if (!$job) {
            return $this->response->setJSON([
                'status'  => true,
                'has_job' => false,
                'message' => 'No queued client call analysis jobs available.'
            ]);
        }

        $jobId       = (int)$job['id'];
        $recordingId = (int)$job['recording_id'];

        $recording = $this->recordingModel->find($recordingId);
        if (!$recording) {
            $this->jobModel->markFailed($jobId, "Recording ID {$recordingId} missing.");
            return $this->response->setJSON(['status' => false, 'message' => 'Recording not found.']);
        }

        $this->jobModel->markProcessing($jobId);
        $this->recordingModel->updateStatus($recordingId, 'processing');

        $rawRecUrl = $recording['recording_url'] ?? '';
        $audioUrl  = (strpos($rawRecUrl, 'http://') === 0 || strpos($rawRecUrl, 'https://') === 0)
            ? $rawRecUrl
            : base_url(ltrim($rawRecUrl, '/'));

        return $this->response->setJSON([
            'status'       => true,
            'has_job'      => true,
            'job_id'       => $jobId,
            'recording_id' => $recordingId,
            'call_type'    => $recording['call_type'] ?? 'client_provider',
            'service_type' => $recording['service_type'] ?? 'walking',
            'audio_url'    => $audioUrl,
            'file_name'    => basename($rawRecUrl)
        ]);
    }

    /**
     * Machine-to-Machine Complete Job Worker Endpoint for Client Calls.
     */
    public function completeJob()
    {
        $this->ensureColumnsExist();

        $jsonInput = $this->request->getJSON(true);
        if (empty($jsonInput)) {
            $jsonInput = json_decode($this->request->getPost('data') ?: '{}', true);
        }

        $jobId       = (int)($jsonInput['job_id'] ?? 0);
        $recordingId = (int)($jsonInput['recording_id'] ?? 0);
        $isError     = !empty($jsonInput['error']);

        if (!$jobId || !$recordingId) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'Missing job_id or recording_id.'
            ]);
        }

        if ($isError) {
            $errorMsg = $jsonInput['error_message'] ?? 'Worker failed to analyze client call.';
            $this->jobModel->markFailed($jobId, $errorMsg);
            $this->recordingModel->updateStatus($recordingId, 'failed');

            return $this->response->setJSON([
                'status'  => true,
                'message' => 'Job marked as failed.'
            ]);
        }

        $rawAnalysis = $jsonInput['analysis'] ?? [];
        if (empty($rawAnalysis)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'Empty analysis payload.'
            ]);
        }

        try {
            $recording = $this->recordingModel->find($recordingId);
            $defaultService = $recording['service_type'] ?? 'walking';

            $formattedData = $this->ratingService->formatAnalysisPayload($rawAnalysis, $defaultService);

            $this->db->transStart();

            $existingAnalysis = $this->analysisModel->where('recording_id', $recordingId)->first();
            $analysisData = array_merge($formattedData['analysis'], ['recording_id' => $recordingId]);

            if ($existingAnalysis) {
                $analysisId = (int)$existingAnalysis['id'];
                $this->analysisModel->update($analysisId, $analysisData);
            } else {
                $analysisId = $this->analysisModel->insert($analysisData, true);
            }

            if (!empty($formattedData['keywords'])) {
                $this->keywordModel->saveKeywords($analysisId, $formattedData['keywords']);
            }

            // Update duration and metadata
            $updateData = [
                'call_type'    => 'client_provider',
                'service_type' => $formattedData['analysis']['service_type'] ?? $defaultService
            ];
            $audioDuration = (int)($formattedData['audio_duration'] ?? 0);
            if ($audioDuration > 0 && (int)($recording['duration'] ?? 0) === 0) {
                $updateData['duration'] = $audioDuration;
            }
            $this->recordingModel->update($recordingId, $updateData);

            $this->jobModel->markCompleted($jobId);
            $this->recordingModel->updateStatus($recordingId, 'completed');

            $this->db->transComplete();

            // Trigger Critical Violation Workflow if Contact Sharing + Off-App Payment
            $isContactShare   = !empty($rawAnalysis['contact_sharing']);
            $isOffPlatformPay = !empty($rawAnalysis['off_platform_payment']) || !empty($rawAnalysis['platform_bypass']) || !empty($rawAnalysis['gst_bypass']);
            $violationCaseInfo = null;

            if ($isContactShare && $isOffPlatformPay) {
                try {
                    $violationCaseInfo = $this->workflowService->handleCriticalViolationDetected(
                        $recordingId,
                        $rawAnalysis,
                        !empty($recording['provider_id']) ? (int)$recording['provider_id'] : null,
                        !empty($recording['customer_id']) ? (int)$recording['customer_id'] : null
                    );
                } catch (\Throwable $we) {
                    log_message('error', 'SpViolationWorkflowService error in completeJob: ' . $we->getMessage());
                }
            }

            return $this->response->setJSON([
                'status'         => true,
                'message'        => 'Client call conversation analysis successfully saved.',
                'analysis_id'    => $analysisId,
                'violation_case' => $violationCaseInfo
            ]);
        } catch (\Throwable $e) {
            $this->jobModel->markFailed($jobId, $e->getMessage());
            $this->recordingModel->updateStatus($recordingId, 'failed');

            return $this->response->setStatusCode(500)->setJSON([
                'status'  => false,
                'message' => 'Failed to persist analysis: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Clear all Client & Provider Calls records and their analysis from the database.
     */
    public function clearCalls()
    {
        $this->ensureColumnsExist();

        $clientCallWhere = "(cr.call_type IN ('client_provider', 'client_sp') OR (cr.customer_id IS NOT NULL AND cr.customer_id > 0 AND (cr.verifier_id IS NULL OR cr.verifier_id = 0)))";

        // Fetch all matching recording IDs
        $recordings = $this->db->table('call_recordings cr')
            ->select('cr.id, cr.recording_url')
            ->where($clientCallWhere)
            ->get()
            ->getResultArray();

        $deletedCount = count($recordings);

        if ($deletedCount > 0) {
            $recordingIds = array_column($recordings, 'id');

            // Find all related analysis IDs to delete keywords
            $analyses = $this->db->table('call_analysis')
                ->select('id')
                ->whereIn('recording_id', $recordingIds)
                ->get()
                ->getResultArray();

            $analysisIds = !empty($analyses) ? array_column($analyses, 'id') : [];

            $this->db->transStart();

            // 1. Delete keywords
            if (!empty($analysisIds)) {
                $this->db->table('call_keywords')->whereIn('analysis_id', $analysisIds)->delete();
            }

            // 2. Delete analysis
            $this->db->table('call_analysis')->whereIn('recording_id', $recordingIds)->delete();

            // 3. Delete jobs
            $this->db->table('call_analysis_jobs')->whereIn('recording_id', $recordingIds)->delete();

            // 4. Delete recordings
            $this->db->table('call_recordings')->whereIn('id', $recordingIds)->delete();

            $this->db->transComplete();

            // Clean up audio files if stored locally
            foreach ($recordings as $rec) {
                if (!empty($rec['recording_url'])) {
                    $url = $rec['recording_url'];
                    $cleanUrl = ltrim(parse_url($url, PHP_URL_PATH) ?? '', '/');
                    $localPaths = [
                        ROOTPATH . $cleanUrl,
                        ROOTPATH . 'public/' . $cleanUrl,
                        ROOTPATH . 'public/uploads/call_recordings/' . basename($url)
                    ];

                    foreach ($localPaths as $path) {
                        if (file_exists($path) && is_file($path)) {
                            @unlink($path);
                            break;
                        }
                    }
                }
            }
        }

        if ($this->request->isAJAX() || strpos($this->request->getHeaderLine('Accept'), 'application/json') !== false) {
            return $this->response->setJSON([
                'status'  => true,
                'message' => "Successfully cleared {$deletedCount} Client & Provider call recording(s) and analysis data.",
                'deleted' => $deletedCount
            ]);
        }

        return redirect()->to(base_url('client-call-analysis'))->with('message', "Successfully cleared {$deletedCount} Client & Provider calls.");
    }

    /**
     * AJAX endpoint to return complete details for a violation case.
     */
    public function caseDetails(int $id)
    {
        $case = $this->workflowService->getCaseDetails($id);

        if (!$case) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => false,
                'message' => 'Violation case not found.'
            ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'data'   => $case
        ]);
    }

    /**
     * AJAX endpoint: Admin confirms violation.
     * Applies first-time penalty (strike) and sets hold duration.
     */
    public function confirmViolation()
    {
        $caseId       = (int)$this->request->getPost('case_id');
        $adminRemarks = trim((string)$this->request->getPost('admin_remarks'));
        $holdHours    = max(1, min(720, (int)($this->request->getPost('hold_hours') ?: 24)));
        $adminId      = (int)(session()->get('user_id') ?: 1);

        if (!$caseId) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'Missing case_id.'
            ]);
        }

        $result = $this->workflowService->adminConfirmViolation($caseId, $adminId, $adminRemarks, $holdHours);

        return $this->response->setJSON($result);
    }

    /**
     * AJAX endpoint: Admin dismisses violation (False positive).
     * Releases hold and clears incorrect flag.
     */
    public function dismissViolation()
    {
        $caseId       = (int)$this->request->getPost('case_id');
        $adminRemarks = trim((string)$this->request->getPost('admin_remarks'));
        $adminId      = (int)(session()->get('user_id') ?: 1);

        if (!$caseId) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'Missing case_id.'
            ]);
        }

        $result = $this->workflowService->adminDismissViolation($caseId, $adminId, $adminRemarks);

        return $this->response->setJSON($result);
    }

    /**
     * AJAX endpoint: Trigger or re-evaluate critical violation workflow for a recording.
     */
    public function triggerViolation()
    {
        $recordingId = (int)$this->request->getPost('recording_id');

        if (!$recordingId) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'Missing recording_id.'
            ]);
        }

        $analysis = $this->analysisModel->where('recording_id', $recordingId)->first();
        if (!$analysis) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => false,
                'message' => 'Analysis record not found for this recording.'
            ]);
        }

        $recording = $this->recordingModel->find($recordingId);
        $rawAnalysis = [
            'service_type'              => $analysis['service_type'] ?? 'walking',
            'contact_sharing'           => (int)($analysis['contact_sharing'] ?? 1),
            'off_platform_payment'      => (int)($analysis['off_platform_payment'] ?? 1),
            'platform_bypass'           => 1,
            'policy_violations'         => json_decode($analysis['policy_violations'] ?? '[]', true) ?: [],
            'highlighted_conversations' => json_decode($analysis['highlighted_conversations'] ?? '[]', true) ?: [],
        ];

        $result = $this->workflowService->handleCriticalViolationDetected(
            $recordingId,
            $rawAnalysis,
            !empty($recording['provider_id']) ? (int)$recording['provider_id'] : null,
            !empty($recording['customer_id']) ? (int)$recording['customer_id'] : null
        );

        return $this->response->setJSON($result);
    }

    /**
     * Automatically ensure required columns exist in MySQL to prevent DB errors.
     */
    private function ensureColumnsExist(): void
    {
        try {
            // Modify call_recordings columns
            $recFields = $this->db->getFieldNames('call_recordings');
            try {
                $this->db->query("ALTER TABLE `call_recordings` MODIFY COLUMN `call_type` VARCHAR(50) NOT NULL DEFAULT 'inbound'");
            } catch (\Throwable $e) {
                // Ignore if already modified
            }
            if (!in_array('service_type', $recFields)) {
                $this->db->query("ALTER TABLE `call_recordings` ADD COLUMN `service_type` VARCHAR(50) NULL AFTER `call_type`");
            }

            // Ensure service_providers hold columns
            $spFields = $this->db->getFieldNames('service_providers');
            if (!in_array('is_on_hold', $spFields)) {
                $this->db->query("ALTER TABLE `service_providers` ADD COLUMN `is_on_hold` TINYINT(1) DEFAULT 0 AFTER `status`");
            }
            if (!in_array('hold_expires_at', $spFields)) {
                $this->db->query("ALTER TABLE `service_providers` ADD COLUMN `hold_expires_at` DATETIME NULL AFTER `is_on_hold`");
            }
            if (!in_array('hold_reason', $spFields)) {
                $this->db->query("ALTER TABLE `service_providers` ADD COLUMN `hold_reason` VARCHAR(255) NULL AFTER `hold_expires_at`");
            }

            $fields = $this->db->getFieldNames('call_analysis');
            $newCols = [
                'service_type'              => "VARCHAR(50) NULL AFTER `recording_id`",
                'service_relevance_score'   => "DECIMAL(4,2) NOT NULL DEFAULT 0.00 AFTER `overall_rating`",
                'service_related_pct'       => "DECIMAL(5,2) NOT NULL DEFAULT 0.00 AFTER `service_relevance_score`",
                'unrelated_pct'             => "DECIMAL(5,2) NOT NULL DEFAULT 0.00 AFTER `service_related_pct`",
                'has_abuse'                 => "TINYINT(1) NOT NULL DEFAULT 0 AFTER `has_complaint`",
                'ai_rating_category'        => "ENUM('good', 'review_recommended', 'critical') NOT NULL DEFAULT 'good' AFTER `risk_score`",
                'off_platform_payment'      => "TINYINT(1) NOT NULL DEFAULT 0 AFTER `ai_rating_category`",
                'contact_sharing'           => "TINYINT(1) NOT NULL DEFAULT 0 AFTER `off_platform_payment`",
                'outside_service_request'   => "TINYINT(1) NOT NULL DEFAULT 0 AFTER `contact_sharing`",
                'refund_requested'          => "TINYINT(1) NOT NULL DEFAULT 0 AFTER `outside_service_request`",
                'admin_alert_required'      => "TINYINT(1) NOT NULL DEFAULT 0 AFTER `refund_requested`",
                'admin_review_status'       => "ENUM('pending', 'reviewed', 'resolved') NOT NULL DEFAULT 'pending' AFTER `admin_alert_required`",
                'admin_remarks'             => "TEXT NULL AFTER `admin_review_status`",
                'service_topics'            => "JSON NULL AFTER `tags`",
                'important_statements'      => "JSON NULL AFTER `service_topics`",
                'unrelated_statements'      => "JSON NULL AFTER `important_statements`",
                'complaint_statements'      => "JSON NULL AFTER `unrelated_statements`",
                'policy_violations'         => "JSON NULL AFTER `complaint_statements`",
                'highlighted_conversations' => "JSON NULL AFTER `policy_violations`",
                'conversation_segments'     => "JSON NULL AFTER `highlighted_conversations`",
                'alerts'                    => "JSON NULL AFTER `conversation_segments`",
            ];

            foreach ($newCols as $col => $sqlDef) {
                if (!in_array($col, $fields)) {
                    $this->db->query("ALTER TABLE `call_analysis` ADD COLUMN `{$col}` {$sqlDef}");
                }
            }
        } catch (\Throwable $e) {
            log_message('error', 'Auto column migration note: ' . $e->getMessage());
        }
    }
}
