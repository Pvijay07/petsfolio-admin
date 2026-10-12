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
     * Segregates SP Verification Calls & Registration Executive Calls from Client-Provider calls.
     */
    public function index()
    {
        $db = \Config\Database::connect();

        // 1. Auto-sync any un-imported executive call recordings from sp_reviews / executive_call_records
        // $this->syncExecutiveCallRecords();

        // Base filter: Strictly executive-to-provider verification & registration calls (excludes client-provider calls)
        $execCallWhere = "(cr.call_type NOT IN ('client_provider', 'client_sp') AND (cr.customer_id IS NULL OR cr.customer_id = 0))";

        // Query parameters for filtering (Default: SP Verification Calls)
        $selectedType     = $this->request->getGet('type') ? strtolower(trim($this->request->getGet('type'))) : 'verification';
        $selectedVerifier = $this->request->getGet('verifier_id') ? (int)$this->request->getGet('verifier_id') : null;
        $selectedStatus   = $this->request->getGet('status') ? strtolower(trim($this->request->getGet('status'))) : null;

        // Base count builder
        $baseBuilder = $db->table('call_recordings cr')->where($execCallWhere);

        // Summary KPI Metrics (Scoped to Executive Calls)
        $totalRecordings = (clone $baseBuilder)->countAllResults();
        $completedCount  = (clone $baseBuilder)->where('cr.status', 'completed')->countAllResults();
        $queuedCount     = (clone $baseBuilder)->where('cr.status', 'queued')->countAllResults();
        $failedCount     = (clone $baseBuilder)->where('cr.status', 'failed')->countAllResults();

        // Registration & Verification specific counts
        $registrationCount = $db->table('call_recordings cr')
            ->join('employees e', 'e.id = cr.verifier_id', 'left')
            ->where($execCallWhere)
            ->groupStart()
                ->whereIn('cr.call_type', ['registration', 'register', 'registration_call', 'registration_executive'])
                ->orWhere('e.role_id', 13)
            ->groupEnd()
            ->countAllResults();

        $verificationCount = max(0, $totalRecordings - $registrationCount);

        $avgRatingRow = $db->table('call_analysis ca')
            ->join('call_recordings cr', 'cr.id = ca.recording_id')
            ->where($execCallWhere)
            ->selectAvg('ca.overall_rating', 'avg_rating')
            ->selectAvg('ca.risk_score', 'avg_risk')
            ->selectAvg('ca.qa_score', 'avg_qa')
            ->get()
            ->getRowArray();

        $avgRating = round((float)($avgRatingRow['avg_rating'] ?? 0), 1);
        $avgRisk   = round((float)($avgRatingRow['avg_risk'] ?? 0), 1);
        $avgQa     = round((float)($avgRatingRow['avg_qa'] ?? 0), 1);

        $positiveCount = $db->table('call_analysis ca')
            ->join('call_recordings cr', 'cr.id = ca.recording_id')
            ->where($execCallWhere)
            ->where('ca.customer_sentiment', 'Positive')
            ->countAllResults();

        $positivePercentage = $completedCount > 0 ? round(($positiveCount / $completedCount) * 100, 1) : 0;

        // Fetch recent call recordings list with analysis, verifier info, executive role & provider details
        $callsQuery = $db->table('call_recordings cr')
            ->select('cr.*, ca.overall_rating, ca.customer_sentiment, ca.risk_score, ca.summary, ca.communication, ca.professionalism, ca.empathy, ca.compliance, ca.verification_status, ca.qa_score, ca.is_escalated, ca.has_complaint, employees.name as verifier_name, employees.role_id as verifier_role_id, roles.name as verifier_role_name, sp.name as provider_name, sp.phone as provider_phone')
            ->join('call_analysis ca', 'ca.recording_id = cr.id', 'left')
            ->join('employees', 'employees.id = cr.verifier_id', 'left')
            ->join('roles', 'roles.id = employees.role_id', 'left')
            ->join('service_providers sp', 'sp.id = cr.provider_id', 'left')
            ->where($execCallWhere);

        // Apply call type filter
        if ($selectedType === 'registration') {
            $callsQuery->groupStart()
                ->whereIn('cr.call_type', ['registration', 'register', 'registration_call', 'registration_executive'])
                ->orWhere('employees.role_id', 13)
                ->groupEnd();
        } elseif ($selectedType === 'verification') {
            $callsQuery->groupStart()
                ->whereNotIn('cr.call_type', ['registration', 'register', 'registration_call', 'registration_executive'])
                ->groupStart()
                    ->where('employees.role_id !=', 13)
                    ->orWhere('employees.role_id IS NULL')
                ->groupEnd()
                ->groupEnd();
        }

        // Apply verifier filter
        if ($selectedVerifier) {
            $callsQuery->where('cr.verifier_id', $selectedVerifier);
        }

        // Apply verification status filter
        if ($selectedStatus && in_array($selectedStatus, ['approved', 're_verification', 'escalated', 'rejected', 'pending'])) {
            $callsQuery->where('ca.verification_status', $selectedStatus);
        }

        // Pagination and Search query parameters
        $page     = max(1, (int)($this->request->getGet('page') ?: 1));
        $perPage  = max(5, min(100, (int)($this->request->getGet('per_page') ?: 25)));
        $search   = trim((string)$this->request->getGet('search'));

        // Apply search keyword filter
        if (!empty($search)) {
            $callsQuery->groupStart()
                ->like('sp.name', $search)
                ->orLike('sp.phone', $search)
                ->orLike('employees.name', $search)
                ->orLike('cr.id', $search)
                ->orLike('cr.provider_id', $search)
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
        $pager->only(['type', 'verifier_id', 'status', 'search', 'per_page']);
        $pagerLinks = $pager->makeLinks($page, $perPage, $totalFiltered, 'default_full');

        // Add normalized executive type tag and formatted duration for each call
        foreach ($calls as &$c) {
            $cRole = (int)($c['verifier_role_id'] ?? 0);
            $cType = strtolower($c['call_type'] ?? '');
            if ($cRole === 13 || in_array($cType, ['registration', 'register', 'registration_call', 'registration_executive'])) {
                $c['display_type']  = 'Registration';
                $c['is_register']   = true;
                $c['role_badge']    = 'Registration Executive';
            } else {
                $c['display_type']  = 'Verification';
                $c['is_register']   = false;
                $c['role_badge']    = 'Verification Executive';
            }

            // Duration formatting
            $durSec = (int)($c['duration'] ?? 0);
            if ($durSec > 0) {
                $mins = floor($durSec / 60);
                $secs = $durSec % 60;
                $c['formatted_duration'] = ($mins > 0 ? "{$mins}m " : "") . sprintf("%02ds", $secs);
            } else {
                $c['formatted_duration'] = '-';
            }
        }

        // Fetch Reviewer / Verifier Performance Metrics (scoped strictly to executive calls)
        $verifierStats = $this->analysisModel->getVerifierPerformanceStats($selectedVerifier);

        // Fetch Verifiers list (Employees) for dropdowns with role information
        $verifiers = $db->table('employees e')
            ->select('e.id, e.name, e.email, e.role_id, r.name as role_name')
            ->join('roles r', 'r.id = e.role_id', 'left')
            ->where('e.status', 1)
            ->orderBy('e.name', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'totalRecordings'    => $totalRecordings,
            'completedCount'     => $completedCount,
            'queuedCount'        => $queuedCount,
            'failedCount'        => $failedCount,
            'registrationCount'  => $registrationCount,
            'verificationCount'  => $verificationCount,
            'avgRating'          => $avgRating,
            'avgRisk'            => $avgRisk,
            'avgQa'              => $avgQa,
            'positivePercentage' => $positivePercentage,
            'selectedType'       => $selectedType,
            'selectedVerifier'   => $selectedVerifier,
            'selectedStatus'     => $selectedStatus,
            'search'             => $search,
            'page'               => $page,
            'perPage'            => $perPage,
            'totalFiltered'      => $totalFiltered,
            'totalPages'         => $totalPages,
            'pager'              => $pager,
            'pagerLinks'         => $pagerLinks,
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

        $call = $db->table('call_recordings cr')
            ->select('cr.*, ca.*, employees.name as verifier_name, employees.role_id as verifier_role_id, roles.name as verifier_role_name, sp.name as provider_name, sp.phone as provider_phone, sp.email as provider_email')
            ->join('call_analysis ca', 'ca.recording_id = cr.id', 'left')
            ->join('employees', 'employees.id = cr.verifier_id', 'left')
            ->join('roles', 'roles.id = employees.role_id', 'left')
            ->join('service_providers sp', 'sp.id = cr.provider_id', 'left')
            ->where('cr.id', $id)
            ->get()
            ->getRowArray();

        if (!$call) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => false,
                'message' => 'Call recording record not found.'
            ]);
        }

        // Tag executive role display
        $cRole = (int)($call['verifier_role_id'] ?? 0);
        $cType = strtolower($call['call_type'] ?? '');
        if ($cRole === 13 || in_array($cType, ['registration', 'register', 'registration_call', 'registration_executive'])) {
            $call['display_type'] = 'Registration';
            $call['role_badge']   = 'Registration Executive';
        } else {
            $call['display_type'] = 'Verification';
            $call['role_badge']   = 'Verification Executive';
        }

        // Decode JSON fields for clean frontend rendering
        $call['strengths']            = json_decode($call['strengths'] ?? '[]', true) ?: [];
        $call['weaknesses']           = json_decode($call['weaknesses'] ?? '[]', true) ?: [];
        $call['recommendations']      = json_decode($call['recommendations'] ?? '[]', true) ?: [];
        $call['tags']                 = json_decode($call['tags'] ?? '[]', true) ?: [];
        $call['abuse_instances']      = json_decode($call['abuse_instances'] ?? '[]', true) ?: [];
        $call['negative_speech_flags'] = json_decode($call['negative_speech_flags'] ?? '[]', true) ?: [];
        $call['verification_qa']      = json_decode($call['verification_qa'] ?? 'null', true) ?: null;

        // Fetch extracted keywords
        $keywords = $db->table('call_keywords')
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
            $callType = strtolower($this->request->getPost('call_type') ?: 'verification');
            $verifierId = $this->request->getPost('verifier_id') ? (int)$this->request->getPost('verifier_id') : (session()->get('user')->id ?? null);

            // Auto-detect if verifier is Registration Executive (Role 13)
            if ($verifierId) {
                $db = \Config\Database::connect();
                $emp = $db->table('employees')->select('role_id')->where('id', $verifierId)->get()->getRowArray();
                if (!empty($emp['role_id']) && (int)$emp['role_id'] === 13 && $callType === 'verification') {
                    $callType = 'registration';
                }
            }

            $metadata = [
                'booking_id'  => $this->request->getPost('booking_id'),
                'provider_id' => $this->request->getPost('provider_id'),
                'customer_id' => null, // Strictly null for executive-provider verification & registration calls
                'verifier_id' => $verifierId,
                'call_type'   => $callType,
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
     * AJAX Endpoint for Chart.js Analytics (Strictly scoped to Executive Calls).
     */
    public function getAnalyticsData()
    {
        $db = \Config\Database::connect();
        $execCallWhere = "call_recordings.call_type NOT IN ('client_provider', 'client_sp') AND (call_recordings.customer_id IS NULL OR call_recordings.customer_id = 0)";

        // 1. Daily Average Rating (last 14 days)
        $dailyRatings = $db->query("
            SELECT DATE(call_recordings.created_at) as call_date, 
                   AVG(call_analysis.overall_rating) as avg_rating,
                   COUNT(call_recordings.id) as total_calls
            FROM call_recordings
            JOIN call_analysis ON call_analysis.recording_id = call_recordings.id
            WHERE call_recordings.created_at >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
              AND {$execCallWhere}
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
              AND {$execCallWhere}
            GROUP BY provider_id
            ORDER BY avg_rating DESC
            LIMIT 10
        ")->getResultArray();

        // 3. Verifier QA Performance Ranking
        $verifierRankings = $db->query("
            SELECT cr.verifier_id,
                   COALESCE(e.name, 'Unassigned') as verifier_name,
                   r.name as role_name,
                   AVG(ca.qa_score) as avg_qa_score,
                   COUNT(cr.id) as total_calls,
                   SUM(CASE WHEN ca.verification_status = 'approved' THEN 1 ELSE 0 END) as approved_calls
            FROM call_recordings cr
            JOIN call_analysis ca ON ca.recording_id = cr.id
            LEFT JOIN employees e ON e.id = cr.verifier_id
            LEFT JOIN roles r ON r.id = e.role_id
            WHERE cr.call_type NOT IN ('client_provider', 'client_sp') 
              AND (cr.customer_id IS NULL OR cr.customer_id = 0)
            GROUP BY cr.verifier_id
            ORDER BY avg_qa_score DESC
        ")->getResultArray();

        // 4. Sentiment Breakdown
        $sentimentStats = $db->query("
            SELECT ca.customer_sentiment, COUNT(*) as total
            FROM call_analysis ca
            JOIN call_recordings cr ON cr.id = ca.recording_id
            WHERE cr.call_type NOT IN ('client_provider', 'client_sp') 
              AND (cr.customer_id IS NULL OR cr.customer_id = 0)
            GROUP BY ca.customer_sentiment
        ")->getResultArray();

        // 5. Top Keywords / Complaints
        $topKeywords = $db->query("
            SELECT ck.keyword, SUM(ck.frequency) as total_freq
            FROM call_keywords ck
            JOIN call_analysis ca ON ca.id = ck.analysis_id
            JOIN call_recordings cr ON cr.id = ca.recording_id
            WHERE cr.call_type NOT IN ('client_provider', 'client_sp') 
              AND (cr.customer_id IS NULL OR cr.customer_id = 0)
            GROUP BY ck.keyword
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
     * Auto-sync un-imported executive recordings from sp_reviews and executive_call_records into call_recordings table.
     */
    protected function syncExecutiveCallRecords(): void
    {
        try {
            $db = \Config\Database::connect();
            $jobModel = new \App\Models\CallJobModel();

            // Find reviews with call_record audio files that are not yet in call_recordings
            $reviews = $db->table('sp_reviews')
                ->select('sp_reviews.id, sp_reviews.provider_id, sp_reviews.service_id, sp_reviews.call_record, sp_reviews.admin_id, sp_reviews.created_at, employees.role_id')
                ->join('employees', 'employees.id = sp_reviews.admin_id', 'left')
                ->where('sp_reviews.call_record IS NOT NULL')
                ->where('sp_reviews.call_record !=', '')
                ->where('sp_reviews.call_record !=', '0')
                ->get()
                ->getResultArray();

            foreach ($reviews as $rev) {
                $rawRecord = trim($rev['call_record']);
                if (empty($rawRecord)) continue;

                $filename = basename(parse_url($rawRecord, PHP_URL_PATH) ?? $rawRecord);
                if (empty($filename)) continue;

                $relPath = 'public/uploads/providers/call_records/' . $filename;
                $fullPath = ROOTPATH . $relPath;

                // Check if file exists on disk
                if (!file_exists($fullPath)) {
                    // Try alternative path
                    $altPath = ROOTPATH . 'public/uploads/call_recordings/' . $filename;
                    if (file_exists($altPath)) {
                        $relPath = 'public/uploads/call_recordings/' . $filename;
                    } else {
                        continue;
                    }
                }

                // Check if already in call_recordings
                $exists = $db->table('call_recordings')
                    ->groupStart()
                        ->where('recording_url', $relPath)
                        ->orLike('recording_url', $filename)
                    ->groupEnd()
                    ->countAllResults();

                if ($exists === 0) {
                    $isRegRole = ((int)($rev['role_id'] ?? 0) === 13);
                    $callType  = $isRegRole ? 'registration' : 'verification';
                    $verifierId = !empty($rev['admin_id']) ? (int)$rev['admin_id'] : null;
                    $duration   = \App\Services\RecordingService::extractAudioDuration($fullPath);

                    $serviceMap = [
                        2 => 'grooming',
                        3 => 'training',
                        4 => 'walking',
                        6 => 'sitting',
                        1 => 'sitting'
                    ];
                    $serviceName = $serviceMap[(int)($rev['service_id'] ?? 0)] ?? null;

                    $recId = $this->recordingModel->insert([
                        'provider_id'   => (int)$rev['provider_id'],
                        'customer_id'   => null,
                        'verifier_id'   => $verifierId,
                        'call_type'     => $callType,
                        'service_type'  => $serviceName,
                        'recording_url' => $relPath,
                        'duration'      => $duration,
                        'status'        => 'queued',
                        'created_at'    => $rev['created_at'] ?: date('Y-m-d H:i:s'),
                        'updated_at'    => $rev['created_at'] ?: date('Y-m-d H:i:s'),
                    ], true);

                    if ($recId) {
                        $jobModel->insert([
                            'recording_id' => $recId,
                            'status'       => 'queued',
                            'retry_count'  => 0,
                        ]);
                    }
                }
            }

            // Backfill existing call_recordings with 0 duration if audio file exists on disk
            $zeroDurationRecs = $db->table('call_recordings')
                ->select('id, recording_url')
                ->groupStart()
                    ->where('duration', 0)
                    ->orWhere('duration IS NULL')
                ->groupEnd()
                ->limit(60)
                ->get()
                ->getResultArray();

            foreach ($zeroDurationRecs as $z) {
                $rawUrl = trim($z['recording_url'] ?? '');
                if (empty($rawUrl)) continue;

                $pathCandidates = [
                    ROOTPATH . ltrim(parse_url($rawUrl, PHP_URL_PATH) ?? $rawUrl, '/'),
                    ROOTPATH . 'public/' . ltrim(parse_url($rawUrl, PHP_URL_PATH) ?? $rawUrl, '/'),
                    ROOTPATH . 'public/uploads/providers/call_records/' . basename($rawUrl),
                    ROOTPATH . 'public/uploads/call_recordings/' . basename($rawUrl),
                ];

                foreach ($pathCandidates as $cand) {
                    if (file_exists($cand) && filesize($cand) > 0) {
                        $dur = \App\Services\RecordingService::extractAudioDuration($cand);
                        if ($dur > 0) {
                            $db->table('call_recordings')->where('id', $z['id'])->update(['duration' => $dur]);
                            break;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            log_message('error', 'syncExecutiveCallRecords error: ' . $e->getMessage());
        }
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

    /**
     * Direct endpoint to re-run AI analysis on a specific recording ID.
     * GET /api/re-analyze/{recordingId}
     */
    public function reAnalyzeRecording(int $recordingId)
    {
        $logFile = "/tmp/ca_{$recordingId}.log";
        @unlink($logFile);
        $sparkPath = realpath(APPPATH . '../spark');
        $cmd = "php " . escapeshellarg($sparkPath) . " call-analysis:process " . (int)$recordingId . " > " . escapeshellarg($logFile) . " 2>&1 &";
        shell_exec($cmd);

        return $this->response->setJSON([
            'status' => true,
            'message' => "Call analysis for recording #{$recordingId} dispatched in background.",
            'recording_id' => $recordingId,
            'log_file' => $logFile,
        ]);
    }

    /**
     * Get analysis summary and QA evaluation for a given recording ID.
     * GET /api/analysis-status/{recordingId}
     */
    public function getAnalysisStatus(int $recordingId)
    {
        $db = \Config\Database::connect();
        $analysis = $db->table('call_analysis ca')
            ->select('ca.id, ca.recording_id, ca.service_type, ca.overall_rating, ca.qa_score, ca.verification_status, ca.summary, ca.verification_qa, cr.provider_id')
            ->join('call_recordings cr', 'cr.id = ca.recording_id', 'left')
            ->where('ca.recording_id', $recordingId)
            ->get()->getRowArray();

        if (!$analysis) {
            return $this->response->setJSON(['status' => false, 'error' => "No analysis found for recording #{$recordingId}."]);
        }

        $qa = !empty($analysis['verification_qa']) ? json_decode($analysis['verification_qa'], true) : [];

        return $this->response->setJSON([
            'status' => true,
            'recording_id' => (int)$analysis['recording_id'],
            'provider_id' => (int)$analysis['provider_id'],
            'service' => $analysis['service_type'],
            'overall_rating' => (float)$analysis['overall_rating'],
            'qa_score' => (float)$analysis['qa_score'],
            'verification_status' => $analysis['verification_status'],
            'summary' => $analysis['summary'],
            'total_questions_count' => $qa['total_questions_count'] ?? null,
            'questions_asked_count' => $qa['questions_asked_count'] ?? null,
            'question_coverage_pct' => $qa['question_coverage_pct'] ?? null,
            'mandatory_questions_status' => $qa['mandatory_questions_status'] ?? null,
            'missed_mandatory_questions' => $qa['missed_mandatory_questions'] ?? [],
            'qa_checklist' => $qa['qa_checklist'] ?? [],
        ]);
    }

    /**
     * List recent recordings and their analysis status.
     * GET /api/list-recordings
     */
    public function listRecentRecordings()
    {
        $db = \Config\Database::connect();
        $recordings = $db->table('call_recordings cr')
            ->select('cr.id, cr.provider_id, cr.call_type, cr.service_type, cr.status, cr.recording_url, cr.created_at, ca.id as analysis_id, ca.overall_rating, ca.verification_status')
            ->join('call_analysis ca', 'ca.recording_id = cr.id', 'left')
            ->orderBy('cr.id', 'DESC')
            ->limit(15)
            ->get()->getResultArray();

        return $this->response->setJSON(['status' => true, 'data' => $recordings]);
    }
}
