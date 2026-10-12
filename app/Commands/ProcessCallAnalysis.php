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

        $recordingIdParam = $params[0] ?? CLI::getOption('recording');
        if ($recordingIdParam) {
            $recId = (int)$recordingIdParam;
            $job = $jobModel->where('recording_id', $recId)->orderBy('id', 'DESC')->first();
            if (!$job) {
                $insertedJobId = $jobModel->insert([
                    'recording_id' => $recId,
                    'status'       => 'queued',
                    'retry_count'  => 0,
                ], true);
                $job = $jobModel->find($insertedJobId);
            }
        } else {
            $job = $jobModel->fetchNextQueuedJob();
        }

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

            $rawUrl = $recording['recording_url'] ?? '';
            $storagePath = $recording['storage_path'] ?? '';

            $urlPath = parse_url($rawUrl, PHP_URL_PATH) ?? $rawUrl;
            $cleanPath = ltrim($urlPath, '/');
            $cleanStoragePath = ltrim($storagePath, '/');

            $candidatePaths = [
                ROOTPATH . $cleanPath,
                FCPATH . $cleanPath,
                ROOTPATH . 'public/' . $cleanPath,
                FCPATH . 'public/' . $cleanPath,
                strpos($cleanPath, 'public/') === 0 ? ROOTPATH . preg_replace('#^public/#', '', $cleanPath) : ROOTPATH . $cleanPath,
                strpos($cleanPath, 'public/') === 0 ? FCPATH . preg_replace('#^public/#', '', $cleanPath) : FCPATH . $cleanPath,
                '/var/www/petsfolio/live/api/' . $cleanPath,
                '/var/www/petsfolio/live/api/public/' . preg_replace('#^public/#', '', $cleanPath),
                '/var/www/petsfolio/live/admin/' . $cleanPath,
                '/var/www/petsfolio/live/admin/public/' . preg_replace('#^public/#', '', $cleanPath),
                '/var/www/petsfolio/beta/api/' . $cleanPath,
                '/var/www/petsfolio/beta/api/public/' . preg_replace('#^public/#', '', $cleanPath),
                '/var/www/petsfolio/beta/admin/' . $cleanPath,
                '/var/www/petsfolio/beta/admin/public/' . preg_replace('#^public/#', '', $cleanPath),
            ];

            if (!empty($cleanStoragePath)) {
                $candidatePaths[] = ROOTPATH . $cleanStoragePath;
                $candidatePaths[] = '/var/www/petsfolio/live/api/' . $cleanStoragePath;
                $candidatePaths[] = '/var/www/petsfolio/live/api/public/' . preg_replace('#^public/#', '', $cleanStoragePath);
                $candidatePaths[] = '/var/www/petsfolio/live/admin/' . $cleanStoragePath;
                $candidatePaths[] = '/var/www/petsfolio/live/admin/public/' . preg_replace('#^public/#', '', $cleanStoragePath);
                $candidatePaths[] = '/var/www/petsfolio/beta/api/' . $cleanStoragePath;
                $candidatePaths[] = '/var/www/petsfolio/beta/api/public/' . preg_replace('#^public/#', '', $cleanStoragePath);
            }

            $candidatePaths = array_unique($candidatePaths);

            $absolutePath = null;
            foreach ($candidatePaths as $path) {
                if (file_exists($path) && is_file($path)) {
                    $absolutePath = $path;
                    break;
                }
            }

            // If not found locally on disk, try downloading if it's a valid remote URL
            if (!$absolutePath && filter_var($rawUrl, FILTER_VALIDATE_URL)) {
                $targetDir = ROOTPATH . 'public/uploads/call_recordings/';
                if (!is_dir($targetDir)) {
                    @mkdir($targetDir, 0777, true);
                }
                $downloadedFile = $targetDir . basename($cleanPath);
                $ch = curl_init($rawUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $fileData = curl_exec($ch);
                $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpStatus === 200 && !empty($fileData)) {
                    file_put_contents($downloadedFile, $fileData);
                    if (file_exists($downloadedFile) && filesize($downloadedFile) > 0) {
                        $absolutePath = $downloadedFile;
                    }
                }
            }

            if (!$absolutePath) {
                throw new \RuntimeException("Audio file not found on disk at: {$rawUrl} (tried path: " . (ROOTPATH . $cleanPath) . ")");
            }

            $isClientCall = (!empty($recording['customer_id']) || !empty($recording['booking_id']) || in_array($recording['call_type'] ?? '', ['inbound', 'outbound', 'client_sp', 'client_provider']));
            $bookedService = strtolower($recording['service_type'] ?? 'walking');
            if (!in_array($bookedService, ['walking', 'grooming', 'training', 'sitting'])) {
                $bookedService = 'walking';
            }

            if ($isClientCall) {
                CLI::write("Running Python Client-SP AI analysis on file: {$absolutePath}", 'blue');
                $rawAnalysis = $aiService->analyzeClientRecording($absolutePath, $bookedService);
                $clientRatingService = new \App\Services\ClientCallRatingService();
                $formattedData = $clientRatingService->formatAnalysisPayload($rawAnalysis, $bookedService);
            } else {
                CLI::write("Running Python Verification AI analysis on file: {$absolutePath}", 'blue');
                $rawAnalysis = $aiService->analyzeRecording($absolutePath);
                $formattedData = $ratingService->formatAnalysisPayload($rawAnalysis);
            }

            CLI::write('AI analysis completed. Formatting ratings & insights...', 'blue');

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

            // Update call_recordings metadata (duration, service_type, call_type)
            $updateRecordingData = [];
            if ($isClientCall) {
                $updateRecordingData['call_type'] = 'client_provider';
                $detectedSvc = $formattedData['analysis']['service_type'] ?? $bookedService;
                $updateRecordingData['service_type'] = $detectedSvc;
            }
            $audioDuration = (int)($formattedData['audio_duration'] ?? 0);
            if ($audioDuration > 0) {
                $currentRecording = $recordingModel->find($recordingId);
                if ($currentRecording && (int)($currentRecording['duration'] ?? 0) === 0) {
                    $updateRecordingData['duration'] = $audioDuration;
                }
            }
            if (!empty($updateRecordingData)) {
                $recordingModel->update($recordingId, $updateRecordingData);
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
