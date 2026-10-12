<?php

namespace App\Models;

use CodeIgniter\Model;

class WalkerStats extends Model
{
    protected $table = 'sp_stats';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'provider_id',
        'total_walks',
        'completed_walks',
        'cancelled_walks',
        'completion_rate',
        'cancellation_rate',
        'on_time_percentage',
        'avg_rating',
        'weighted_rating',
        'complaint_count',
        'complaint_rate',
        'repeat_client_count',
        'last_30d_walks',
        'last_30d_avg_rating',
        'last_30d_on_time_pct',
        'last_30d_cancellation_rate',
        'last_30d_complaint_rate',
        'ranking_score',
        'tier',
        'strike_count',
        'last_evaluated_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get Walker Leaderboard Data
     */
    public function getLeaderboard()
    {
        return $this->db->table($this->table)
            ->select('sp_stats.*, sp.name as provider_name, sp.profile as provider_profile, sp.phone, sp.email, sp.status as account_status')
            ->join('service_providers sp', 'sp.id = sp_stats.provider_id', 'inner')
            ->orderBy('sp_stats.tier', 'ASC') // Tier 1 first
            ->orderBy('sp_stats.ranking_score', 'DESC') // Highest score first
            ->get()
            ->getResult();
    }
}
