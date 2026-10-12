<?php

namespace App\Models;

use CodeIgniter\Model;

class CallJobModel extends Model
{
    protected $table            = 'call_analysis_jobs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'recording_id',
        'status',
        'retry_count',
        'started_at',
        'completed_at',
        'error',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Fetch the next queued job for processing.
     */
    public function fetchNextQueuedJob(): ?array
    {
        $job = $this->db->table($this->table)
            ->where('status', 'queued')
            ->orderBy('id', 'ASC')
            ->get(1)
            ->getRowArray();

        return $job ?: null;
    }

    public function markProcessing(int $jobId): bool
    {
        return $this->update($jobId, [
            'status'     => 'processing',
            'started_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function markCompleted(int $jobId): bool
    {
        return $this->update($jobId, [
            'status'       => 'completed',
            'completed_at' => date('Y-m-d H:i:s'),
            'error'        => null,
            'updated_at'   => date('Y-m-d H:i:s')
        ]);
    }

    public function markFailed(int $jobId, string $errorMessage): bool
    {
        $job = $this->find($jobId);
        $retryCount = ($job['retry_count'] ?? 0) + 1;
        $status = ($retryCount >= 3) ? 'failed' : 'queued';

        return $this->update($jobId, [
            'status'      => $status,
            'retry_count' => $retryCount,
            'error'       => $errorMessage,
            'updated_at'  => date('Y-m-d H:i:s')
        ]);
    }
}
