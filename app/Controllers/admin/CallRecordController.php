<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;

class CallRecordController extends BaseController
{
    public function analyzeCallRecord()
    {
        $file = $this->request->getFile('call_record');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['status' => false, 'message' => 'Invalid or missing file.']);
        }

        $newName = $file->getRandomName();
        $uploadPath = ROOTPATH . 'public/uploads/providers/call_records/';
        $file->move($uploadPath, $newName);
        $filePath = $uploadPath . $newName;

        // Call Sarvam AI API for Speech-to-Text Translation
        $sarvamApiKey = getenv('SARVAM_API_KEY') ?: env('SARVAM_API_KEY'); // Ensure you set this in your .env file

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.sarvam.ai/speech-to-text-translate");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        $cFile = new \CURLFile($filePath, $file->getClientMimeType(), $newName);
        $postData = [
            'file' => $cFile
        ];

        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "api-subscription-key: " . $sarvamApiKey,
            "Content-Type: multipart/form-data"
        ]);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return $this->response->setJSON(['status' => false, 'message' => 'Sarvam API Error: ' . $err]);
        }

        $result = json_decode($response, true);
        $transcript = $result['transcript'] ?? '';

        // Provide a rating based on the transcript
        $rating = $this->generateRatingFromTranscript($transcript);

        return $this->response->setJSON([
            'status' => true,
            'transcript' => $transcript,
            'rating' => $rating,
            'file_name' => $newName,
            'message' => 'Call record analyzed successfully.'
        ]);
    }

    /**
     * Initiates a batch job for longer audio files, uploads the file to Sarvam via presigned URL, and starts processing.
     * The client should subsequently poll `getBatchStatus` with the returned job_id.
     */
    public function analyzeCallRecordBatch()
    {
        $file = $this->request->getFile('call_record');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['status' => false, 'message' => 'Invalid or missing file.']);
        }

        $newName = $file->getRandomName();
        $uploadPath = ROOTPATH . 'public/uploads/providers/call_records/';
        $file->move($uploadPath, $newName);
        $filePath = $uploadPath . $newName;

        $sarvamApiKey = getenv('SARVAM_API_KEY') ?: env('SARVAM_API_KEY');

        // Step 1: Initiate Job
        $ch = curl_init("https://api.sarvam.ai/speech-to-text/job/v1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            "job_parameters" => ["model" => "saaras:v3"]
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "api-subscription-key: " . $sarvamApiKey,
            "Content-Type: application/json"
        ]);
        $initResponse = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) return $this->response->setJSON(['status' => false, 'message' => 'Error initiating job: ' . $err]);
        
        $initData = json_decode($initResponse, true);
        $jobId = $initData['job_id'] ?? null;
        if (!$jobId) return $this->response->setJSON(['status' => false, 'message' => 'Failed to get job_id', 'raw' => $initData]);

        // Step 2: Request Upload URL(s)
        $ch2 = curl_init("https://api.sarvam.ai/speech-to-text/job/v1/upload-files");
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_POST, true);
        curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode(["job_id" => $jobId]));
        curl_setopt($ch2, CURLOPT_HTTPHEADER, [
            "api-subscription-key: " . $sarvamApiKey,
            "Content-Type: application/json"
        ]);
        $uploadResp = curl_exec($ch2);
        curl_close($ch2);
        $uploadData = json_decode($uploadResp, true);

        // Usually returns an array of files/presigned URLs. Assuming single file for now.
        $presignedUrl = $uploadData['upload_url'] ?? (isset($uploadData['urls'][0]) ? $uploadData['urls'][0] : null); 
        
        if ($presignedUrl) {
            // PUT file to presigned URL
            $chPut = curl_init($presignedUrl);
            curl_setopt($chPut, CURLOPT_PUT, true);
            $fp = fopen($filePath, 'r');
            curl_setopt($chPut, CURLOPT_INFILE, $fp);
            curl_setopt($chPut, CURLOPT_INFILESIZE, filesize($filePath));
            curl_setopt($chPut, CURLOPT_RETURNTRANSFER, true);
            curl_exec($chPut);
            fclose($fp);
            curl_close($chPut);
        }

        // Step 3: Start Job
        $ch3 = curl_init("https://api.sarvam.ai/speech-to-text/job/v1/start-job");
        curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch3, CURLOPT_POST, true);
        curl_setopt($ch3, CURLOPT_POSTFIELDS, json_encode(["job_id" => $jobId]));
        curl_setopt($ch3, CURLOPT_HTTPHEADER, [
            "api-subscription-key: " . $sarvamApiKey,
            "Content-Type: application/json"
        ]);
        curl_exec($ch3);
        curl_close($ch3);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Batch job started successfully.',
            'job_id' => $jobId,
            'file_name' => $newName
        ]);
    }

    /**
     * Poll this endpoint with job_id to get the status and final transcript of the batch job.
     */
    public function getBatchStatus()
    {
        $jobId = $this->request->getGet('job_id');
        if (!$jobId) {
            return $this->response->setJSON(['status' => false, 'message' => 'job_id is required']);
        }

        $sarvamApiKey = getenv('SARVAM_API_KEY') ?: env('SARVAM_API_KEY');

        // Note: The actual endpoint might be /get-status or /status
        $ch = curl_init("https://api.sarvam.ai/speech-to-text/job/v1/get-status?job_id=" . urlencode($jobId));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "api-subscription-key: " . $sarvamApiKey
        ]);
        
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return $this->response->setJSON(['status' => false, 'message' => 'API Error: ' . $err]);
        }

        $result = json_decode($response, true);
        
        // If completed, attempt to fetch results if separate download is needed, 
        // or return them directly if they are included in status.
        if (isset($result['status']) && strtolower($result['status']) === 'completed') {
            
            // To fetch the actual transcript file, often /download-files is used
            $chDl = curl_init("https://api.sarvam.ai/speech-to-text/job/v1/download-files");
            curl_setopt($chDl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($chDl, CURLOPT_POST, true);
            curl_setopt($chDl, CURLOPT_POSTFIELDS, json_encode(["job_id" => $jobId]));
            curl_setopt($chDl, CURLOPT_HTTPHEADER, [
                "api-subscription-key: " . $sarvamApiKey,
                "Content-Type: application/json"
            ]);
            $dlResponse = curl_exec($chDl);
            curl_close($chDl);
            
            $dlData = json_decode($dlResponse, true);
            
            // Assuming transcript text is somewhere in the dlData or downloaded JSON
            $transcript = $dlData['transcript'] ?? json_encode($dlData); // Adjust based on actual payload
            $rating = $this->generateRatingFromTranscript($transcript);
            
            return $this->response->setJSON([
                'status' => true,
                'job_status' => 'completed',
                'transcript' => $transcript,
                'rating' => $rating,
                'raw_result' => $dlData
            ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'job_status' => $result['status'] ?? 'processing',
            'raw_status' => $result
        ]);
    }

    /**
     * Basic logic to generate a rating from the transcribed text.
     * You can replace this with an LLM call (e.g., OpenAI) for advanced rating.
     */
    private function generateRatingFromTranscript($transcript)
    {
        if (empty($transcript)) {
            return 1;
        }

        $wordCount = str_word_count($transcript);

        if ($wordCount > 50) {
            return 5;
        } elseif ($wordCount > 30) {
            return 4;
        } elseif ($wordCount > 15) {
            return 3;
        } elseif ($wordCount > 5) {
            return 2;
        }

        return 1;
    }
}
