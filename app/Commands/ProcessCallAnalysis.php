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
<<<<<<< HEAD
                '/var/www/petsfolio/beta/api/' . $cleanPath,
                '/var/www/petsfolio/beta/api/public/' . preg_replace('#^public/#', '', $cleanPath),
                '/var/www/petsfolio/beta/admin/' . $cleanPath,
                '/var/www/petsfolio/beta/admin/public/' . preg_replace('#^public/#', '', $cleanPath),
=======
                
>>>>>>> origin/main
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

<<<<<<< HEAD
            $isClientCall = (!empty($recording['customer_id']) || !empty($recording['booking_id']) || in_array($recording['call_type'] ?? '', ['inbound', 'outbound', 'client_sp', 'client_provider']));
            $bookedService = strtolower($recording['service_type'] ?? 'walking');
            if (!in_array($bookedService, ['walking', 'grooming', 'training', 'sitting'])) {
                $bookedService = 'walking';
            }

=======
            $db = \Config\Database::connect();
            $isClientCall = (!empty($recording['customer_id']) || !empty($recording['booking_id']) || in_array($recording['call_type'] ?? '', ['inbound', 'outbound', 'client_sp', 'client_provider']));
            $bookedService = strtolower($recording['service_type'] ?? '');

            // 0a. Check calls table if call_id is set
            if (!$bookedService && !empty($recording['call_id'])) {
                $callRecord = $db->table('calls')->where('id', (int)$recording['call_id'])->get()->getRowArray();
                if ($callRecord && !empty($callRecord['booking_id'])) {
                    $bkCode = strtoupper(trim((string)$callRecord['booking_id']));
                    if (preg_match('/(STT|STTE|STTP|STTT)$/', $bkCode)) {
                        $bookedService = 'sitting';
                    } elseif (preg_match('/(WLK|WLKE|WLKP|WLKT)$/', $bkCode)) {
                        $bookedService = 'walking';
                    } elseif (preg_match('/(GRM|GRME|GRMP|GRMT)$/', $bkCode)) {
                        $bookedService = 'grooming';
                    } elseif (preg_match('/(TRN|TRNE|TRNP|TRNT)$/', $bkCode)) {
                        $bookedService = 'training';
                    } elseif (preg_match('/(BRD)$/', $bkCode)) {
                        $bookedService = 'boarding';
                    }
                }
            }

            // 0b. Check booking code suffix if booking_id is provided
            if (!$bookedService && !empty($recording['booking_id'])) {
                $bkCode = strtoupper(trim((string)$recording['booking_id']));
                if (preg_match('/(STT|STTE|STTP|STTT)$/', $bkCode)) {
                    $bookedService = 'sitting';
                } elseif (preg_match('/(WLK|WLKE|WLKP|WLKT)$/', $bkCode)) {
                    $bookedService = 'walking';
                } elseif (preg_match('/(GRM|GRME|GRMP|GRMT)$/', $bkCode)) {
                    $bookedService = 'grooming';
                } elseif (preg_match('/(TRN|TRNE|TRNP|TRNT)$/', $bkCode)) {
                    $bookedService = 'training';
                } elseif (preg_match('/(BRD)$/', $bkCode)) {
                    $bookedService = 'boarding';
                }
            }

            // 0c. Primary: Look up exact booking in sp_bookings
            if (!$bookedService && !empty($recording['booking_id'])) {
                $bookingIdVal = $recording['booking_id'];
                $spBookingQuery = $db->table('sp_bookings')
                    ->join('services', 'services.id = sp_bookings.service_id', 'left')
                    ->select('services.name, sp_bookings.service_id');

                if (is_numeric($bookingIdVal)) {
                    $spBookingQuery->groupStart()
                        ->where('sp_bookings.id', (int)$bookingIdVal)
                        ->orWhere('sp_bookings.booking_id', (string)$bookingIdVal)
                    ->groupEnd();
                } else {
                    $spBookingQuery->where('sp_bookings.booking_id', (string)$bookingIdVal);
                }

                $spBooking = $spBookingQuery->get()->getRowArray();
                if ($spBooking && !empty($spBooking['name'])) {
                    $bookedService = strtolower($spBooking['name']);
                }
            }

            if (!$bookedService && !empty($recording['provider_id'])) {
                $filename = basename($recording['recording_url'] ?? '');

                // 1. Look up exact review for this recording file in sp_reviews (stored by submitServiceVerification)
                if (!empty($filename)) {
                    $reviewService = $db->table('sp_reviews')
                        ->join('services', 'services.id = sp_reviews.service_id')
                        ->select('services.name, sp_reviews.service_id')
                        ->like('sp_reviews.call_record', $filename)
                        ->get()
                        ->getRowArray();
                    if ($reviewService && !empty($reviewService['name'])) {
                        $bookedService = strtolower($reviewService['name']);
                    }
                }

                // 2. Look up latest sp_reviews by provider_id
                if (!$bookedService) {
                    $reviewService = $db->table('sp_reviews')
                        ->join('services', 'services.id = sp_reviews.service_id')
                        ->select('services.name, sp_reviews.service_id')
                        ->where('sp_reviews.provider_id', (int)$recording['provider_id'])
                        ->where('sp_reviews.call_record IS NOT NULL')
                        ->where('sp_reviews.call_record !=', '')
                        ->orderBy('sp_reviews.id', 'DESC')
                        ->get()
                        ->getRowArray();
                    if ($reviewService && !empty($reviewService['name'])) {
                        $bookedService = strtolower($reviewService['name']);
                    }
                }

                // 3. Fallback to sp_services
                if (!$bookedService) {
                    $spService = $db->table('sp_services')
                        ->join('services', 'services.id = sp_services.service_id')
                        ->select('services.name, sp_services.service_id')
                        ->where('sp_services.provider_id', (int)$recording['provider_id'])
                        ->orderBy('sp_services.id', 'DESC')
                        ->get()
                        ->getRowArray();
                    if ($spService && !empty($spService['name'])) {
                        $bookedService = strtolower($spService['name']);
                    }
                }
            }
            if (!in_array($bookedService, ['walking', 'grooming', 'training', 'sitting', 'boarding'])) {
                $bookedService = 'walking';
            }

            // Sync discovered service_type into recording record if missing
            if (empty($recording['service_type']) && !empty($bookedService)) {
                $recordingModel->update($recordingId, ['service_type' => $bookedService]);
            }

>>>>>>> origin/main
            if ($isClientCall) {
                CLI::write("Running Python Client-SP AI analysis on file: {$absolutePath}", 'blue');
                $rawAnalysis = $aiService->analyzeClientRecording($absolutePath, $bookedService);
                $clientRatingService = new \App\Services\ClientCallRatingService();
                $formattedData = $clientRatingService->formatAnalysisPayload($rawAnalysis, $bookedService);
            } else {
<<<<<<< HEAD
                CLI::write("Running Python Verification AI analysis on file: {$absolutePath}", 'blue');
                $rawAnalysis = $aiService->analyzeRecording($absolutePath);
=======
                CLI::write("Running Python Verification AI analysis on file: {$absolutePath} (Service: {$bookedService}, Provider ID: " . ($recording['provider_id'] ?? 'N/A') . ")", 'blue');
                $providerId = !empty($recording['provider_id']) ? (int)$recording['provider_id'] : null;

                $serviceIdMap = ['boarding' => 1, 'grooming' => 2, 'training' => 3, 'walking' => 4, 'sitting' => 6];
                $serviceId = $serviceIdMap[$bookedService] ?? 4;

                $questionModel = new \App\Models\VerificationQuestionModel();
                $filteredQuestions = $questionModel->getFilteredQuestionsForProvider($serviceId, $providerId);
                CLI::write("Loaded " . count($filteredQuestions) . " verification questions for {$bookedService} (Provider: " . ($providerId ?: 'None') . ")", 'cyan');

                $rawAnalysis = $aiService->analyzeRecording($absolutePath, $bookedService, $providerId, $filteredQuestions);
>>>>>>> origin/main
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

<<<<<<< HEAD
=======
            // Check critical violation trigger (Contact Sharing + Off-App Payment)
            $isContactShare   = !empty($rawAnalysis['contact_sharing']);
            $isOffPlatformPay = !empty($rawAnalysis['off_platform_payment']) || !empty($rawAnalysis['platform_bypass']) || !empty($rawAnalysis['gst_bypass']);
            if ($isContactShare && $isOffPlatformPay) {
                try {
                    $workflow = new \App\Services\SpViolationWorkflowService();
                    $wfRes = $workflow->handleCriticalViolationDetected(
                        $recordingId,
                        $rawAnalysis,
                        !empty($recording['provider_id']) ? (int)$recording['provider_id'] : null,
                        !empty($recording['customer_id']) ? (int)$recording['customer_id'] : null
                    );
                    CLI::write("🚨 Critical Violation Case created: " . ($wfRes['case_number'] ?? 'N/A') . " (Hold placed)", 'red');
                } catch (\Throwable $we) {
                    log_message('error', 'SpViolationWorkflowService error: ' . $we->getMessage());
                }
            }

>>>>>>> origin/main
            CLI::write("Successfully completed call analysis job #{$jobId} (Overall Rating: {$formattedData['analysis']['overall_rating']})", 'green');

        } catch (\Throwable $e) {
            $errorMessage = $e->getMessage();
            CLI::error("Job #{$jobId} failed: " . $errorMessage);
            $jobModel->markFailed($jobId, $errorMessage);
            $recordingModel->updateStatus($recordingId, 'failed');
        }
    }
}
