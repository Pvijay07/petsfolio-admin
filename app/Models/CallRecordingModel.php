<?php

namespace App\Models;

use CodeIgniter\Model;

class CallRecordingModel extends Model
{
    protected $table            = 'call_recordings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'call_id',
        'storage_path',
        'downloaded_at',
        'booking_id',
        'provider_id',
        'customer_id',
        'verifier_id',
        'call_type',
        'service_type',
        'recording_url',
        'duration',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function updateStatus(int $recordingId, string $status): bool
    {
        return $this->update($recordingId, [
            'status'     => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}
