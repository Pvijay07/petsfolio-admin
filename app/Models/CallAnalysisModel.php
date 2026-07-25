<?php

namespace App\Models;

use CodeIgniter\Model;

class CallAnalysisModel extends Model
{
    protected $table            = 'call_analysis';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'recording_id',
        'transcript',
        'summary',
        'overall_rating',
        'communication',
        'professionalism',
        'empathy',
        'compliance',
        'customer_sentiment',
        'provider_sentiment',
        'resolution',
        'risk_score',
        'strengths',
        'weaknesses',
        'recommendations',
        'tags',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAnalysisWithRecording(int $recordingId): ?array
    {
        return $this->db->table($this->table)
            ->select('call_analysis.*, call_recordings.recording_url, call_recordings.duration, call_recordings.provider_id, call_recordings.customer_id, call_recordings.call_type, call_recordings.created_at as call_date')
            ->join('call_recordings', 'call_recordings.id = call_analysis.recording_id', 'left')
            ->where('call_analysis.recording_id', $recordingId)
            ->get()
            ->getRowArray();
    }
}
