<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\CallJobModel;
use App\Models\CallRecordingModel;
use App\Models\CallAnalysisModel;
use App\Models\CallKeywordModel;
use App\Services\AIService;
use App\Services\RatingService;

class ProcessCallAnalysis extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Call Analysis';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'call-analysis:process';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Processes the next pending queued call recording AI analysis job using Python CLI.';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'call-analysis:process';

    public function run(array $params)
    {
        CLI::write('Starting call analysis job worker...', 'green');

        $jobModel       = new CallJobModel();
        $recordingModel = new CallRecordingModel();
        $analysisModel  = new CallAnalysisModel();
        $keywordModel   = new CallKeywordModel();
        $aiService      = new AIService();
        $ratingService  = new RatingService();

        $job = $jobModel->fetchNextQueuedJob();

        if (!$job) {
            CLI::write('No queued call analysis jobs found.', 'yellow');
            return;
        }

        $jobId       = (int)$job['id'];
        $recordingId = (int)$job['recording_id'];

        CLI::write("Processing Job ID: {$jobId} (Recording ID: {$recordingId})...", 'cyan');

        $jobModel->markProcessing($jobId);
        $recordingModel->updateStatus($recordingId, 'processing');

        try {
            $recording = $recordingModel->find($recordingId);
            if (!$recording) {
                throw new \RuntimeException("Recording ID {$recordingId} not found in database.");
            }

            $relativeUrl = $recording['recording_url'];
            $absolutePath = ROOTPATH . 'public/' . ltrim($relativeUrl, '/');

            if (!file_exists($absolutePath)) {
                // Check fallback uploads path if ROOTPATH prefix is different
                $fallbackPath = FCPATH . ltrim($relativeUrl, '/');
                if (file_exists($fallbackPath)) {
                    $absolutePath = $fallbackPath;
                } else {
                    throw new \RuntimeException("Audio file not found on disk at: {$absolutePath}");
                }
            }

            CLI::write("Running Python AI analysis on file: {$absolutePath}", 'blue');
            $rawAnalysis = $aiService->analyzeRecording($absolutePath);

            CLI::write('AI analysis completed. Formatting ratings & insights...', 'blue');
            $formattedData = $ratingService->formatAnalysisPayload($rawAnalysis);

            $db = \Config\Database::connect();
            $db->transStart();

            // Insert or update call_analysis
            $existingAnalysis = $analysisModel->where('recording_id', $recordingId)->first();
            $analysisData = array_merge($formattedData['analysis'], ['recording_id' => $recordingId]);

            if ($existingAnalysis) {
                $analysisId = (int)$existingAnalysis['id'];
                $analysisModel->update($analysisId, $analysisData);
            } else {
                $analysisId = $analysisModel->insert($analysisData, true);
            }

            // Save keywords
            if (!empty($formattedData['keywords'])) {
                $keywordModel->saveKeywords($analysisId, $formattedData['keywords']);
            }

            $jobModel->markCompleted($jobId);
            $recordingModel->updateStatus($recordingId, 'completed');

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \RuntimeException("Database commit failed for recording ID {$recordingId}");
            }

            CLI::write("Successfully completed call analysis job #{$jobId} (Overall Rating: {$formattedData['analysis']['overall_rating']})", 'green');

        } catch (\Throwable $e) {
            $errorMessage = $e->getMessage();
            CLI::error("Job #{$jobId} failed: " . $errorMessage);
            $jobModel->markFailed($jobId, $errorMessage);
            $recordingModel->updateStatus($recordingId, 'failed');
        }
    }
}
