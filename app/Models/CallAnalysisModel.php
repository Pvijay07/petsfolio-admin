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
        'service_type',
        'transcript',
        'summary',
        'overall_rating',
        'service_relevance_score',
        'service_related_pct',
        'unrelated_pct',
        'communication',
        'professionalism',
        'empathy',
        'compliance',
        'customer_sentiment',
        'provider_sentiment',
        'resolution',
        'verification_status',
        'qa_score',
        'is_escalated',
        'has_complaint',
        'risk_score',
        'ai_rating_category',
        'off_platform_payment',
        'contact_sharing',
        'outside_service_request',
        'refund_requested',
        'admin_alert_required',
        'admin_review_status',
        'admin_remarks',
        'strengths',
        'weaknesses',
        'recommendations',
        'tags',
        'service_topics',
        'important_statements',
        'unrelated_statements',
        'complaint_statements',
        'policy_violations',
        'highlighted_conversations',
        'conversation_segments',
        'alerts',
        'has_abuse',
        'abuse_instances',
        'negative_speech_flags',
        'verification_qa',
        'verifier_id',
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
            ->select('call_analysis.*, call_recordings.recording_url, call_recordings.duration, call_recordings.provider_id, call_recordings.customer_id, call_recordings.verifier_id, call_recordings.call_type, call_recordings.created_at as call_date, employees.name as verifier_name')
            ->join('call_recordings', 'call_recordings.id = call_analysis.recording_id', 'left')
            ->join('employees', 'employees.id = call_recordings.verifier_id', 'left')
            ->where('call_analysis.recording_id', $recordingId)
            ->get()
            ->getRowArray();
    }

    /**
     * Fetch verifier quality and performance metrics grouped by verifier (Employee).
     * Calculates all 8 reviewer metrics requested by management.
     */
    public function getVerifierPerformanceStats(?int $verifierId = null, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        $builder = $this->db->table('call_recordings cr')
            ->select("
                cr.verifier_id,
                e.name as verifier_name,
                e.email as verifier_email,
                e.role_id,
                r.name as role_name,
                COUNT(cr.id) as calls_completed,
                ROUND(AVG(cr.duration), 1) as avg_duration_seconds,
                ROUND(AVG(ca.overall_rating), 2) as avg_verification_score,
                ROUND(AVG(COALESCE(ca.qa_score, ca.overall_rating)), 2) as avg_qa_rating,
                SUM(CASE WHEN ca.verification_status = 'approved' THEN 1 ELSE 0 END) as approved_count,
                SUM(CASE WHEN ca.verification_status = 're_verification' THEN 1 ELSE 0 END) as re_verification_count,
                SUM(CASE WHEN ca.is_escalated = 1 OR ca.verification_status = 'escalated' THEN 1 ELSE 0 END) as escalation_count,
                SUM(CASE WHEN ca.has_complaint = 1 THEN 1 ELSE 0 END) as complaint_count
            ")
            ->join('call_analysis ca', 'ca.recording_id = cr.id', 'left')
            ->join('employees e', 'e.id = cr.verifier_id', 'inner')
            ->join('roles r', 'r.id = e.role_id', 'left')
            ->where('cr.status', 'completed')
            ->where("(cr.call_type NOT IN ('client_provider', 'client_sp') AND (cr.customer_id IS NULL OR cr.customer_id = 0))")
            ->where("(cr.call_type = 'verification' OR cr.call_type IS NULL OR cr.call_type = '' OR cr.call_type = 'inbound')")
            ->where("cr.verifier_id IS NOT NULL")
            ->where("cr.verifier_id >", 0)
            ->where("e.id IS NOT NULL")
            ->where("(e.role_id = 10 OR r.name LIKE '%Verification%')")
            ->where("COALESCE(e.role_id, 0) != 13");

        if ($verifierId) {
            $builder->where('cr.verifier_id', $verifierId);
        }

        if ($dateFrom) {
            $builder->where('cr.created_at >=', $dateFrom . ' 00:00:00');
        }

        if ($dateTo) {
            $builder->where('cr.created_at <=', $dateTo . ' 23:59:59');
        }

        $builder->groupBy('cr.verifier_id');
        $builder->orderBy('avg_qa_rating', 'DESC');

        $rows = $builder->get()->getResultArray();

        // Calculate rate percentages for each verifier
        foreach ($rows as &$row) {
            $total = (int)$row['calls_completed'];
            if ($total > 0) {
                $row['approval_rate']          = round(((int)$row['approved_count'] / $total) * 100, 1);
                $row['re_verification_rate']   = round(((int)$row['re_verification_count'] / $total) * 100, 1);
                $row['escalation_rate']        = round(((int)$row['escalation_count'] / $total) * 100, 1);
                $row['customer_complaint_rate']= round(((int)$row['complaint_count'] / $total) * 100, 1);
            } else {
                $row['approval_rate']          = 0.0;
                $row['re_verification_rate']   = 0.0;
                $row['escalation_rate']        = 0.0;
                $row['customer_complaint_rate']= 0.0;
            }

            // Format duration as mm:ss string
            $seconds = (int)$row['avg_duration_seconds'];
            $mins = floor($seconds / 60);
            $secs = $seconds % 60;
            $row['formatted_duration'] = sprintf('%dm %02ds', $mins, $secs);

            // Tag executive role type as Verification Executive
            $row['executive_type'] = 'verification';
            $row['role_title']     = 'Verification Executive';
        }

        return $rows;
    }
}
