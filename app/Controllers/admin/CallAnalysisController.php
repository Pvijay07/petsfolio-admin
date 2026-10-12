<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\CallRecordingModel;
use App\Models\CallAnalysisModel;
use App\Models\CallKeywordModel;
use App\Services\RecordingService;

class CallAnalysisController extends BaseController
{
    protected CallRecordingModel $recordingModel;
    protected CallAnalysisModel $analysisModel;
    protected CallKeywordModel $keywordModel;
    protected RecordingService $recordingService;

    public function __construct()
    {
        $this->recordingModel   = new CallRecordingModel();
        $this->analysisModel    = new CallAnalysisModel();
        $this->keywordModel     = new CallKeywordModel();
        $this->recordingService = new RecordingService();
    }

    /**
     * Display the Call Analysis Executive Dashboard with Reviewer / Verifier Performance.
     */
    public function index()
    {
        $db = \Config\Database::connect();

        // Summary KPI Metrics
        $totalRecordings = $this->recordingModel->countAllResults();
        $completedCount  = $this->recordingModel->where('status', 'completed')->countAllResults();
        $queuedCount     = $this->recordingModel->where('status', 'queued')->countAllResults();
        $failedCount     = $this->recordingModel->where('status', 'failed')->countAllResults();

        $avgRatingRow = $db->table('call_analysis')
            ->selectAvg('overall_rating', 'avg_rating')
            ->selectAvg('risk_score', 'avg_risk')
            ->selectAvg('qa_score', 'avg_qa')
            ->get()
            ->getRowArray();

        $avgRating = round((float)($avgRatingRow['avg_rating'] ?? 0), 1);
        $avgRisk   = round((float)($avgRatingRow['avg_risk'] ?? 0), 1);
        $avgQa     = round((float)($avgRatingRow['avg_qa'] ?? 0), 1);

        $positiveCount = $db->table('call_analysis')
            ->where('customer_sentiment', 'Positive')
            ->countAllResults();

        $positivePercentage = $completedCount > 0 ? round(($positiveCount / $completedCount) * 100, 1) : 0;

        // Fetch recent call recordings list with analysis & verifier info
        $calls = $db->table('call_recordings')
            ->select('call_recordings.*, call_analysis.overall_rating, call_analysis.customer_sentiment, call_analysis.risk_score, call_analysis.summary, call_analysis.communication, call_analysis.professionalism, call_analysis.empathy, call_analysis.compliance, call_analysis.verification_status, call_analysis.qa_score, call_analysis.is_escalated, call_analysis.has_complaint, employees.name as verifier_name')
            ->join('call_analysis', 'call_analysis.recording_id = call_recordings.id', 'left')
            ->join('employees', 'employees.id = call_recordings.verifier_id', 'left')
            ->orderBy('call_recordings.id', 'DESC')
            ->limit(50)
            ->get()
            ->getResultArray();

        // Fetch Reviewer / Verifier Performance Metrics (all 8 metrics)
        $verifierStats = $this->analysisModel->getVerifierPerformanceStats();

        // Fetch Verifiers list (Employees) for dropdowns
        $verifiers = $db->table('employees')
            ->select('id, name, email')
            ->where('status', 1)
            ->get()
            ->getResultArray();

        $data = [
            'totalRecordings'    => $totalRecordings,
            'completedCount'     => $completedCount,
            'queuedCount'        => $queuedCount,
            'failedCount'        => $failedCount,
            'avgRating'          => $avgRating,
            'avgRisk'            => $avgRisk,
            'avgQa'              => $avgQa,
            'positivePercentage' => $positivePercentage,
            'calls'              => $calls,
            'verifierStats'      => $verifierStats,
            'verifiers'          => $verifiers,
        ];

        return view('admin/call_analysis/index', $data);
    }

    /**
     * AJAX endpoint to return verifier quality & performance metrics.
     */
    public function getVerifierPerformance()
    {
        $verifierId = $this->request->getGet('verifier_id') ? (int)$this->request->getGet('verifier_id') : null;
        $dateFrom   = $this->request->getGet('date_from');
        $dateTo     = $this->request->getGet('date_to');

        $stats = $this->analysisModel->getVerifierPerformanceStats($verifierId, $dateFrom, $dateTo);

        return $this->response->setJSON([
            'status' => true,
            'data'   => $stats
        ]);
    }

    /**
     * AJAX endpoint to save or update verifier quality audit for a call.
     */
    public function saveVerifierAudit()
    {
        $recordingId = (int)$this->request->getPost('recording_id');
        $verifierId  = $this->request->getPost('verifier_id') ? (int)$this->request->getPost('verifier_id') : null;

        if (!$recordingId) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'Missing recording_id.'
            ]);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Update call_recordings with verifier_id if provided
        if ($verifierId !== null) {
            $this->recordingModel->update($recordingId, ['verifier_id' => $verifierId]);
        }

        // Update call_analysis metrics
        $auditData = [];
        if ($this->request->getPost('verification_status')) {
            $auditData['verification_status'] = $this->request->getPost('verification_status');
        }
        if ($this->request->getPost('qa_score') !== null) {
            $auditData['qa_score'] = min(10.0, max(0.0, (float)$this->request->getPost('qa_score')));
        }
        if ($this->request->getPost('is_escalated') !== null) {
            $auditData['is_escalated'] = (int)$this->request->getPost('is_escalated');
        }
        if ($this->request->getPost('has_complaint') !== null) {
            $auditData['has_complaint'] = (int)$this->request->getPost('has_complaint');
        }

        if (!empty($auditData)) {
            $existing = $this->analysisModel->where('recording_id', $recordingId)->first();
            if ($existing) {
                $this->analysisModel->update($existing['id'], $auditData);
            } else {
                $auditData['recording_id'] = $recordingId;
                $this->analysisModel->insert($auditData);
            }
        }

        $db->transComplete();

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Verifier audit & performance details successfully updated.'
        ]);
    }

    /**
     * AJAX endpoint to return detailed analysis payload for offcanvas drawer.
     */
    public function details(int $id)
    {
        $db = \Config\Database::connect();

        $call = $db->table('call_recordings')
            ->select('call_recordings.*, call_analysis.*, employees.name as verifier_name')
            ->join('call_analysis', 'call_analysis.recording_id = call_recordings.id', 'left')
            ->join('employees', 'employees.id = call_recordings.verifier_id', 'left')
            ->where('call_recordings.id', $id)
            ->get()
            ->getRowArray();

        if (!$call) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => false,
                'message' => 'Call recording record not found.'
            ]);
        }

        // Decode JSON fields for clean frontend rendering
        $call['strengths']            = json_decode($call['strengths'] ?? '[]', true);
        $call['weaknesses']           = json_decode($call['weaknesses'] ?? '[]', true);
        $call['recommendations']      = json_decode($call['recommendations'] ?? '[]', true);
        $call['tags']                 = json_decode($call['tags'] ?? '[]', true);
        $call['abuse_instances']      = json_decode($call['abuse_instances'] ?? '[]', true);
        $call['negative_speech_flags'] = json_decode($call['negative_speech_flags'] ?? '[]', true);

        // Fetch extracted keywords
        $keywords = $db->table('call_keywords')
            ->where('analysis_id', $call['id'] ?? 0)
            ->orderBy('frequency', 'DESC')
            ->get()
            ->getResultArray();

        $call['keywords'] = $keywords;

        return $this->response->setJSON([
            'status' => true,
            'data'   => $call
        ]);
    }

    /**
     * AJAX endpoint to upload recording file and queue background analysis job.
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
            $metadata = [
                'booking_id'  => $this->request->getPost('booking_id'),
                'provider_id' => $this->request->getPost('provider_id'),
                'customer_id' => $this->request->getPost('customer_id'),
                'verifier_id' => $this->request->getPost('verifier_id') ?: (session()->get('user')->id ?? null),
                'call_type'   => $this->request->getPost('call_type') ?: 'verification',
                'duration'    => (int)($this->request->getPost('duration') ?: 0),
            ];

            $result = $this->recordingService->handleUpload($file, $metadata);

            return $this->response->setJSON([
                'status'       => true,
                'message'      => 'Call recording uploaded and queued for AI analysis.',
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
     * AJAX Endpoint for Chart.js Analytics.
     */
    public function getAnalyticsData()
    {
        $db = \Config\Database::connect();

        // 1. Daily Average Rating (last 14 days)
        $dailyRatings = $db->query("
            SELECT DATE(call_recordings.created_at) as call_date, 
                   AVG(call_analysis.overall_rating) as avg_rating,
                   COUNT(call_recordings.id) as total_calls
            FROM call_recordings
            JOIN call_analysis ON call_analysis.recording_id = call_recordings.id
            WHERE call_recordings.created_at >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
            GROUP BY DATE(call_recordings.created_at)
            ORDER BY call_date ASC
        ")->getResultArray();

        // 2. Provider Performance Ranking
        $providerRankings = $db->query("
            SELECT provider_id, 
                   AVG(call_analysis.overall_rating) as avg_rating, 
                   COUNT(call_recordings.id) as total_calls
            FROM call_recordings
            JOIN call_analysis ON call_analysis.recording_id = call_recordings.id
            WHERE provider_id IS NOT NULL AND provider_id > 0
            GROUP BY provider_id
            ORDER BY avg_rating DESC
            LIMIT 10
        ")->getResultArray();

        // 3. Verifier QA Performance Ranking
        $verifierRankings = $db->query("
            SELECT cr.verifier_id,
                   COALESCE(e.name, 'Unassigned') as verifier_name,
                   AVG(ca.qa_score) as avg_qa_score,
                   COUNT(cr.id) as total_calls,
                   SUM(CASE WHEN ca.verification_status = 'approved' THEN 1 ELSE 0 END) as approved_calls
            FROM call_recordings cr
            JOIN call_analysis ca ON ca.recording_id = cr.id
            LEFT JOIN employees e ON e.id = cr.verifier_id
            GROUP BY cr.verifier_id
            ORDER BY avg_qa_score DESC
        ")->getResultArray();

        // 4. Sentiment Breakdown
        $sentimentStats = $db->query("
            SELECT customer_sentiment, COUNT(*) as total
            FROM call_analysis
            GROUP BY customer_sentiment
        ")->getResultArray();

        // 5. Top Keywords / Complaints
        $topKeywords = $db->query("
            SELECT keyword, SUM(frequency) as total_freq
            FROM call_keywords
            GROUP BY keyword
            ORDER BY total_freq DESC
            LIMIT 10
        ")->getResultArray();

        return $this->response->setJSON([
            'status'           => true,
            'dailyRatings'     => $dailyRatings,
            'providerRankings' => $providerRankings,
            'verifierRankings' => $verifierRankings,
            'sentimentStats'   => $sentimentStats,
            'topKeywords'      => $topKeywords
        ]);
    }

    /**
     * Endpoint for Local PC Worker: Get next queued call analysis job.
     */
    public function nextJob()
    {
        $jobModel       = new \App\Models\CallJobModel();
        $recordingModel = new \App\Models\CallRecordingModel();

        $job = $jobModel->fetchNextQueuedJob();

        if (!$job) {
            return $this->response->setJSON([
                'status'  => true,
                'has_job' => false,
                'message' => 'No queued call analysis jobs available.'
            ]);
        }

        $jobId       = (int)$job['id'];
        $recordingId = (int)$job['recording_id'];

        $recording = $recordingModel->find($recordingId);
        if (!$recording) {
            $jobModel->markFailed($jobId, "Recording ID {$recordingId} missing.");
            return $this->response->setJSON(['status' => false, 'message' => 'Recording not found.']);
        }

        $jobModel->markProcessing($jobId);
        $recordingModel->updateStatus($recordingId, 'processing');

        $rawRecUrl = $recording['recording_url'] ?? '';
        $audioUrl  = (strpos($rawRecUrl, 'http://') === 0 || strpos($rawRecUrl, 'https://') === 0)
            ? $rawRecUrl
            : base_url(ltrim($rawRecUrl, '/'));

        return $this->response->setJSON([
            'status'       => true,
            'has_job'      => true,
            'job_id'       => $jobId,
            'recording_id' => $recordingId,
            'audio_url'    => $audioUrl,
            'file_name'    => basename($rawRecUrl)
        ]);
    }

    /**
     * Endpoint for Local PC Worker: Post completed AI analysis result back to Hostinger CI4.
     */
    public function completeJob()
    {
        $jsonInput = $this->request->getJSON(true);

        if (empty($jsonInput)) {
            $jsonInput = json_decode($this->request->getPost('data') ?: '{}', true);
        }

        $jobId       = (int)($jsonInput['job_id'] ?? 0);
        $recordingId = (int)($jsonInput['recording_id'] ?? 0);
        $isError     = !empty($jsonInput['error']);

        $jobModel       = new \App\Models\CallJobModel();
        $recordingModel = new \App\Models\CallRecordingModel();
        $analysisModel  = new \App\Models\CallAnalysisModel();
        $keywordModel   = new \App\Models\CallKeywordModel();
        $ratingService  = new \App\Services\RatingService();

        if (!$jobId || !$recordingId) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'Missing job_id or recording_id.'
            ]);
        }

        if ($isError) {
            $errorMsg = $jsonInput['error_message'] ?? 'Worker failed to analyze call.';
            $jobModel->markFailed($jobId, $errorMsg);
            $recordingModel->updateStatus($recordingId, 'failed');

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
            $recording = $recordingModel->find($recordingId);
            $isClientCall = (!empty($recording['customer_id']) || !empty($recording['booking_id']) || in_array($recording['call_type'] ?? '', ['client_provider', 'client_sp']));

            if ($isClientCall) {
                $clientRatingService = new \App\Services\ClientCallRatingService();
                $defaultService = strtolower($recording['service_type'] ?? 'walking');
                $formattedData = $clientRatingService->formatAnalysisPayload($rawAnalysis, $defaultService);
            } else {
                $formattedData = $ratingService->formatAnalysisPayload($rawAnalysis);
            }

            $db = \Config\Database::connect();
            $db->transStart();

            $existingAnalysis = $analysisModel->where('recording_id', $recordingId)->first();
            $analysisData = array_merge($formattedData['analysis'], ['recording_id' => $recordingId]);

            if ($existingAnalysis) {
                $analysisId = (int)$existingAnalysis['id'];
                $analysisModel->update($analysisId, $analysisData);
            } else {
                $analysisId = $analysisModel->insert($analysisData, true);
            }

            if (!empty($formattedData['keywords'])) {
                $keywordModel->saveKeywords($analysisId, $formattedData['keywords']);
            }

            // Update call_recordings metadata (duration, service_type)
            $updateRecordingData = [];
            if ($isClientCall && empty($recording['service_type']) && !empty($formattedData['analysis']['service_type'])) {
                $updateRecordingData['service_type'] = $formattedData['analysis']['service_type'];
            }
            $audioDuration = (int)($formattedData['audio_duration'] ?? 0);
            if ($audioDuration > 0 && (int)($recording['duration'] ?? 0) === 0) {
                $updateRecordingData['duration'] = $audioDuration;
            }
            if (!empty($updateRecordingData)) {
                $recordingModel->update($recordingId, $updateRecordingData);
            }

            $jobModel->markCompleted($jobId);
            $recordingModel->updateStatus($recordingId, 'completed');

            $db->transComplete();

            return $this->response->setJSON([
                'status'      => true,
                'message'     => 'Call analysis result successfully saved to database.',
                'analysis_id' => $analysisId
            ]);
        } catch (\Throwable $e) {
            $jobModel->markFailed($jobId, $e->getMessage());
            $recordingModel->updateStatus($recordingId, 'failed');

            return $this->response->setStatusCode(500)->setJSON([
                'status'  => false,
                'message' => 'Failed to persist analysis: ' . $e->getMessage()
            ]);
        }
    }

    public function uploadCallRecording()
    {
        // 1. Verify Secret Token
        if ($this->request->getPost('token') !== '3x391f5722a2414e1c41682db1c27683469dc5c') {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => false,
                'message' => 'Invalid token.'
            ]);
        }

        // 2. Retrieve Uploaded File
        $file = $this->request->getFile('file');

        if (!$file || !$file->isValid()) {
            $errorString = $file ? $file->getErrorString() . ' (' . $file->getError() . ')' : 'No file received';
            log_message('error', 'API Call Recording Receive Error: ' . $errorString);

            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'File upload error: ' . $errorString
            ]);
        }

        // 3. Ensure Target Directory Exists
        $targetDir = ROOTPATH . 'public/uploads/call_recordings/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // 4. Move File (Preserving filename if passed, or using random name)
        $filename = $this->request->getPost('filename') ?: $file->getRandomName();
        $file->move($targetDir, $filename);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'File uploaded successfully',
            'url'    => base_url('public/uploads/call_recordings/' . $filename)
        ]);
    }
}
