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
        'experience',
        'effective_experience',
        'in_grace_period',
        'last_evaluated_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get Walker Leaderboard Data
     */
    /**
     * Get Walker Leaderboard Data with Live Database Metrics
     */
    public function getLeaderboard()
    {
        $completionSubquery = "(SELECT q.provider_id, COUNT(CASE WHEN LOWER(wsb.status) = 'completed' THEN 1 END) AS real_completed_bookings, COUNT(CASE WHEN LOWER(wsb.status) = 'cancelled' THEN 1 END) AS real_cancelled_bookings, COUNT(CASE WHEN LOWER(wsb.status) = 'completed' AND wsb.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 END) AS real_last_30d_walks, COUNT(*) AS real_accepted_bookings, ROUND(COUNT(CASE WHEN LOWER(wsb.status) = 'completed' THEN 1 END) * 100.0 / COUNT(*), 2) AS real_completion_rate FROM quotations q JOIN walking_service_bookings wsb ON wsb.id = q.booking_id WHERE q.service_id = 4 AND LOWER(q.status) IN ('accepted', 'completed', 'cancelled') GROUP BY q.provider_id) comp_stats";

        $walkTrackingSubquery = "(SELECT provider_id, COUNT(*) as track_total_walks, SUM(CASE WHEN LOWER(status) = 'completed' THEN 1 ELSE 0 END) as track_completed_walks, SUM(CASE WHEN LOWER(status) = 'cancelled' THEN 1 ELSE 0 END) as track_cancelled_walks, SUM(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) as track_last_30d_walks FROM walking_tracking GROUP BY provider_id) wt_stats";

        $repeatSubquery = "(SELECT repeat_sq.provider_id, COUNT(DISTINCT repeat_sq.user_id) as real_repeat_client_count, SUM(repeat_sq.completed_bookings) as real_repeat_bookings FROM (SELECT q.provider_id, wsb.user_id, COUNT(*) AS completed_bookings FROM quotations q INNER JOIN walking_service_bookings wsb ON wsb.id = q.booking_id WHERE q.service_id = 4 AND LOWER(q.status) = 'completed' AND LOWER(wsb.status) = 'completed' GROUP BY q.provider_id, wsb.user_id HAVING COUNT(*) >= 2) AS repeat_sq GROUP BY repeat_sq.provider_id) repeat_stats";

        return $this->db->table('service_providers sp')
            ->select('
                sp.id as provider_id, 
                sp.name as provider_name, 
                sp.profile as provider_profile, 
                sp.phone, 
                sp.email, 
                sp.status as account_status, 
                sp.is_verified, 
                sp.created_at as joined_at, 
                sp.service_address as address, 
                sp.city, 
                sp.area,

                GREATEST(COALESCE(wt_stats.track_completed_walks, 0), COALESCE(comp_stats.real_completed_bookings, 0), COALESCE(sp_stats.completed_walks, 0)) as completed_walks,
                GREATEST(COALESCE(wt_stats.track_total_walks, 0), COALESCE(comp_stats.real_accepted_bookings, 0), COALESCE(sp_stats.total_walks, 0)) as total_walks,
                GREATEST(COALESCE(wt_stats.track_cancelled_walks, 0), COALESCE(comp_stats.real_cancelled_bookings, 0), COALESCE(sp_stats.cancelled_walks, 0)) as cancelled_walks,
                GREATEST(COALESCE(wt_stats.track_last_30d_walks, 0), COALESCE(comp_stats.real_last_30d_walks, 0), COALESCE(sp_stats.last_30d_walks, 0)) as last_30d_walks,
                COALESCE(repeat_stats.real_repeat_client_count, COALESCE(sp_stats.repeat_client_count, 0)) as repeat_client_count,

                (CASE 
                    WHEN GREATEST(COALESCE(wt_stats.track_completed_walks, 0), COALESCE(comp_stats.real_completed_bookings, 0), COALESCE(sp_stats.completed_walks, 0)) = 0 THEN 3
                    WHEN sp_stats.tier IS NOT NULL THEN sp_stats.tier
                    WHEN GREATEST(COALESCE(wt_stats.track_completed_walks, 0), COALESCE(comp_stats.real_completed_bookings, 0)) >= 30 AND GREATEST(COALESCE(wt_stats.track_last_30d_walks, 0), COALESCE(comp_stats.real_last_30d_walks, 0)) >= 10 THEN 1
                    WHEN GREATEST(COALESCE(wt_stats.track_completed_walks, 0), COALESCE(comp_stats.real_completed_bookings, 0)) >= 10 AND GREATEST(COALESCE(wt_stats.track_last_30d_walks, 0), COALESCE(comp_stats.real_last_30d_walks, 0)) >= 5 THEN 2
                    ELSE 3 
                END) as tier,

                (CASE 
                    WHEN GREATEST(COALESCE(wt_stats.track_completed_walks, 0), COALESCE(comp_stats.real_completed_bookings, 0), COALESCE(sp_stats.completed_walks, 0)) = 0 THEN 0.0 
                    ELSE COALESCE(sp_stats.ranking_score, 0.0) 
                END) as ranking_score,

                (CASE 
                    WHEN GREATEST(COALESCE(wt_stats.track_completed_walks, 0), COALESCE(comp_stats.real_completed_bookings, 0), COALESCE(sp_stats.completed_walks, 0)) = 0 THEN 0.0 
                    ELSE COALESCE(sp_stats.avg_rating, 0.0) 
                END) as avg_rating,

                (CASE 
                    WHEN GREATEST(COALESCE(wt_stats.track_completed_walks, 0), COALESCE(comp_stats.real_completed_bookings, 0), COALESCE(sp_stats.completed_walks, 0)) = 0 THEN 0.0 
                    ELSE COALESCE(sp_stats.weighted_rating, 0.0) 
                END) as weighted_rating,

                (CASE 
                    WHEN GREATEST(COALESCE(wt_stats.track_completed_walks, 0), COALESCE(comp_stats.real_completed_bookings, 0), COALESCE(sp_stats.completed_walks, 0)) = 0 THEN 0.0 
                    ELSE COALESCE(sp_stats.completion_rate, COALESCE(comp_stats.real_completion_rate, 0.0)) 
                END) as completion_rate,

                (CASE 
                    WHEN GREATEST(COALESCE(wt_stats.track_completed_walks, 0), COALESCE(comp_stats.real_completed_bookings, 0), COALESCE(sp_stats.completed_walks, 0)) = 0 THEN 0.0 
                    ELSE COALESCE(sp_stats.on_time_percentage, 0.0) 
                END) as on_time_percentage,

                (CASE 
                    WHEN GREATEST(COALESCE(wt_stats.track_completed_walks, 0), COALESCE(comp_stats.real_completed_bookings, 0), COALESCE(sp_stats.completed_walks, 0)) = 0 THEN 0.0 
                    ELSE COALESCE(sp_stats.last_30d_on_time_pct, 0.0) 
                END) as last_30d_on_time_pct,

                COALESCE(sp_stats.last_30d_cancellation_rate, 0.0) as last_30d_cancellation_rate,
                COALESCE(sp_stats.cancellation_rate, 0.0) as cancellation_rate,
                COALESCE(sp_stats.strike_count, 0) as strike_count,
                COALESCE(sp_stats.experience, 0.0) as experience,
                COALESCE(sp_stats.effective_experience, COALESCE(sp_stats.experience, 0.0)) as effective_experience,
                COALESCE(sp_stats.in_grace_period, 0) as in_grace_period,
                sp_stats.last_evaluated_at,
                sp_stats.id as id
            ')
            ->join('sp_stats', 'sp_stats.provider_id = sp.id', 'left')
            ->join($repeatSubquery, 'repeat_stats.provider_id = sp.id', 'left')
            ->join($completionSubquery, 'comp_stats.provider_id = sp.id', 'left')
            ->join($walkTrackingSubquery, 'wt_stats.provider_id = sp.id', 'left')
            ->join('sp_services ss', 'ss.provider_id = sp.id AND ss.service_id = 4 AND ss.status = "approved"', 'inner')
            ->where('sp.status', 'active')
            ->orderBy('tier', 'ASC')
            ->orderBy('ranking_score', 'DESC')
            ->get()
            ->getResult();
    }

    /**
     * Get total count of all registered walkers (service_id = 4) regardless of status
     */
    public function getTotalRegisteredWalkers()
    {
        return $this->db->table('service_providers sp')
            ->join('sp_services ss', 'ss.provider_id = sp.id AND ss.service_id = 4', 'inner')
            ->countAllResults();
    }
}
