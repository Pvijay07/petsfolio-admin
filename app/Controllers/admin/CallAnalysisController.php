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
        $this->recordingModel  = new CallRecordingModel();
        $this->analysisModel   = new CallAnalysisModel();
        $this->keywordModel    = new CallKeywordModel();
        $this->recordingService = new RecordingService();
    }

    /**
     * Display the Call Analysis Executive Dashboard.
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
            ->get()
            ->getRowArray();

        $avgRating = round((float)($avgRatingRow['avg_rating'] ?? 0), 1);
        $avgRisk   = round((float)($avgRatingRow['avg_risk'] ?? 0), 1);

        $positiveCount = $db->table('call_analysis')
            ->where('customer_sentiment', 'Positive')
            ->countAllResults();

        $positivePercentage = $completedCount > 0 ? round(($positiveCount / $completedCount) * 100, 1) : 0;

        // Fetch recent call recordings list with analysis
        $calls = $db->table('call_recordings')
            ->select('call_recordings.*, call_analysis.overall_rating, call_analysis.customer_sentiment, call_analysis.risk_score, call_analysis.summary, call_analysis.communication, call_analysis.professionalism, call_analysis.empathy, call_analysis.compliance')
            ->join('call_analysis', 'call_analysis.recording_id = call_recordings.id', 'left')
            ->orderBy('call_recordings.id', 'DESC')
            ->limit(50)
            ->get()
            ->getResultArray();

        $data = [
            'totalRecordings'    => $totalRecordings,
            'completedCount'     => $completedCount,
            'queuedCount'        => $queuedCount,
            'failedCount'        => $failedCount,
            'avgRating'          => $avgRating,
            'avgRisk'            => $avgRisk,
            'positivePercentage' => $positivePercentage,
            'calls'              => $calls,
        ];

        return view('admin/call_analysis/index', $data);
    }

    /**
     * AJAX endpoint to return detailed analysis payload for offcanvas drawer.
     */
    public function details(int $id)
    {
        $db = \Config\Database::connect();

        $call = $db->table('call_recordings')
            ->select('call_recordings.*, call_analysis.*')
            ->join('call_analysis', 'call_analysis.recording_id = call_recordings.id', 'left')
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
        $call['strengths']       = json_decode($call['strengths'] ?? '[]', true);
        $call['weaknesses']      = json_decode($call['weaknesses'] ?? '[]', true);
        $call['recommendations'] = json_decode($call['recommendations'] ?? '[]', true);
        $call['tags']            = json_decode($call['tags'] ?? '[]', true);

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
                'call_type'   => $this->request->getPost('call_type') ?: 'inbound',
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

        // 3. Sentiment Breakdown
        $sentimentStats = $db->query("
            SELECT customer_sentiment, COUNT(*) as total
            FROM call_analysis
            GROUP BY customer_sentiment
        ")->getResultArray();

        // 4. Top Keywords / Complaints
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

        $audioUrl = base_url(ltrim($recording['recording_url'], '/'));

        return $this->response->setJSON([
            'status'       => true,
            'has_job'      => true,
            'job_id'       => $jobId,
            'recording_id' => $recordingId,
            'audio_url'    => $audioUrl,
            'file_name'    => basename($recording['recording_url'])
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
            $formattedData = $ratingService->formatAnalysisPayload($rawAnalysis);

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
}
