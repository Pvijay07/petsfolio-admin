<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\CallRecordingModel;
use App\Models\CallAnalysisModel;
use App\Models\CallKeywordModel;
use App\Models\CallJobModel;
use App\Services\RecordingService;
use App\Services\ClientCallRatingService;

class ClientCallAnalysisController extends BaseController
{
    protected CallRecordingModel $recordingModel;
    protected CallAnalysisModel $analysisModel;
    protected CallKeywordModel $keywordModel;
    protected CallJobModel $jobModel;
    protected RecordingService $recordingService;
    protected ClientCallRatingService $ratingService;
    protected $db;

    public function __construct()
    {
        $this->recordingModel   = new CallRecordingModel();
        $this->analysisModel    = new CallAnalysisModel();
        $this->keywordModel     = new CallKeywordModel();
        $this->jobModel         = new CallJobModel();
        $this->recordingService = new RecordingService();
        $this->ratingService    = new ClientCallRatingService();
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

        // Fetch Call Recordings List with Client, Provider & Analysis Info
        $callsQuery = $this->db->table('call_recordings cr')
            ->select("
                cr.*,
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
                p.phone as provider_phone
            ")
            ->join('call_analysis ca', 'ca.recording_id = cr.id', 'left')
            ->join('users u', 'u.id = cr.customer_id', 'left')
            ->join('service_providers p', 'p.id = cr.provider_id', 'left')
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

        $calls = $callsQuery->orderBy('cr.id', 'DESC')
            ->limit(100)
            ->get()
            ->getResultArray();

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
            'selectedService'   => $selectedService,
            'selectedRating'    => $selectedRating,
            'selectedFlag'      => $selectedFlag,
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
                ca.*,
                u.name as client_name,
                u.phone as client_phone,
                u.email as client_email,
                p.name as provider_name,
                p.phone as provider_phone,
                p.email as provider_email
            ")
            ->join('call_analysis ca', 'ca.recording_id = cr.id', 'left')
            ->join('users u', 'u.id = cr.customer_id', 'left')
            ->join('service_providers p', 'p.id = cr.provider_id', 'left')
            ->where('cr.id', $id)
            ->get()
            ->getRowArray();

        if (!$call) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => false,
                'message' => 'Call recording record not found.'
            ]);
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

            return $this->response->setJSON([
                'status'      => true,
                'message'     => 'Client call conversation analysis successfully saved.',
                'analysis_id' => $analysisId
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

            // Normalize client recordings call_type
            try {
                $this->db->query("
                    UPDATE `call_recordings` 
                    SET `call_type` = 'client_provider' 
                    WHERE (`customer_id` > 0 OR `booking_id` > 0 OR `call_type` = '' OR `call_type` = 'client_sp' OR `call_type` = 'inbound') 
                      AND (`verifier_id` IS NULL OR `verifier_id` = 0)
                ");
            } catch (\Throwable $e) {
                // Ignore normalization errors
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
