<?php

namespace App\Services;

use App\Models\CallRecordingModel;
use App\Models\CallJobModel;
use CodeIgniter\HTTP\Files\UploadedFile;

class RecordingService
{
    protected CallRecordingModel $recordingModel;
    protected CallJobModel $jobModel;

    public function __construct()
    {
        $this->recordingModel = new CallRecordingModel();
        $this->jobModel       = new CallJobModel();
    }

    /**
     * Store uploaded recording and queue analysis job.
     */
    public function handleUpload(UploadedFile $file, array $metadata = []): array
    {
        if (!$file->isValid()) {
            throw new \RuntimeException('Invalid file upload: ' . $file->getErrorString());
        }

        $allowedMimes = ['audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/x-wav', 'audio/ogg', 'audio/mp4', 'audio/m4a', 'audio/x-m4a'];
        if (!in_array($file->getMimeType(), $allowedMimes) && !in_array($file->getClientExtension(), ['mp3', 'wav', 'ogg', 'm4a', 'mp4', 'aac'])) {
            throw new \InvalidArgumentException('Unsupported audio format: ' . $file->getMimeType());
        }

        $uploadDirectory = ROOTPATH . 'public/uploads/call_recordings/';
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }

        $newName = $file->getRandomName();
        $file->move($uploadDirectory, $newName);

        $relativeUrl = 'public/uploads/call_recordings/' . $newName;
        $absolutePath = $uploadDirectory . $newName;

        $db = \Config\Database::connect();
        $db->transStart();

        $duration = (int)($metadata['duration'] ?? 0);
        if ($duration <= 0) {
            $duration = self::extractAudioDuration($absolutePath);
        }

        $recordingId = $this->recordingModel->insert([
            'booking_id'    => $metadata['booking_id'] ?? null,
            'provider_id'   => $metadata['provider_id'] ?? null,
            'customer_id'   => $metadata['customer_id'] ?? null,
            'verifier_id'   => !empty($metadata['verifier_id']) ? (int)$metadata['verifier_id'] : null,
            'call_type'     => $metadata['call_type'] ?? 'inbound',
            'recording_url' => $relativeUrl,
            'duration'      => $duration,
            'status'        => 'queued',
        ], true);

        $jobId = $this->jobModel->insert([
            'recording_id' => $recordingId,
            'status'       => 'queued',
            'retry_count'  => 0,
        ], true);

        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new \RuntimeException('Database transaction failed while creating recording record.');
        }

        return [
            'recording_id'  => $recordingId,
            'job_id'        => $jobId,
            'relative_url'  => $relativeUrl,
            'absolute_path' => $absolutePath,
            'duration'      => $duration
        ];
    }

    /**
     * Extract duration in seconds from an audio file using ffprobe.
     */
    public static function extractAudioDuration(string $filePath): int
    {
        if (!file_exists($filePath) || filesize($filePath) === 0) {
            return 0;
        }

        try {
            $cmd = sprintf('ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 %s 2>/dev/null', escapeshellarg($filePath));
            $output = trim((string)shell_exec($cmd));
            if (is_numeric($output) && (float)$output > 0) {
                return (int)round((float)$output);
            }
        } catch (\Throwable $e) {}

        return 0;
    }
}
