<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationCampaignModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Get all campaigns with optional filters
     */
    public function getCampaigns($filters = [])
    {
        $builder = $this->db->table('notification_campaigns nc');
        $builder->select('nc.*, e.name as creator_name');
        $builder->join('employees e', 'e.id = nc.created_by', 'left');

        if (!empty($filters['status'])) {
            $builder->where('nc.status', $filters['status']);
        }
        if (!empty($filters['type'])) {
            $builder->where('nc.type', $filters['type']);
        }
        if (!empty($filters['channel'])) {
            $builder->where('nc.channel', $filters['channel']);
        }
        if (!empty($filters['date_from'])) {
            $builder->where('nc.created_at >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $builder->where('nc.created_at <=', $filters['date_to'] . ' 23:59:59');
        }
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('nc.name', $filters['search'])
                ->orLike('nc.title', $filters['search'])
                ->groupEnd();
        }

        $builder->orderBy('nc.created_at', 'DESC');
        return $builder->get()->getResult();
    }

    /**
     * Get a single campaign by ID
     */
    public function getCampaign($id)
    {
        return $this->db->table('notification_campaigns nc')
            ->select('nc.*, e.name as creator_name')
            ->join('employees e', 'e.id = nc.created_by', 'left')
            ->where('nc.id', $id)
            ->get()->getRow();
    }

    /**
     * Create a new campaign
     */
    public function createCampaign($data)
    {
        $this->db->table('notification_campaigns')->insert($data);
        return $this->db->insertID();
    }

    /**
     * Update a campaign
     */
    public function updateCampaign($id, $data)
    {
        return $this->db->table('notification_campaigns')
            ->where('id', $id)
            ->update($data);
    }

    /**
     * Delete a campaign (only drafts)
     */
    public function deleteCampaign($id)
    {
        return $this->db->table('notification_campaigns')
            ->where('id', $id)
            ->where('status', 'draft')
            ->delete();
    }

    /**
     * Get campaigns that are scheduled and due for processing
     */
    public function getScheduledCampaigns()
    {
        return $this->db->table('notification_campaigns')
            ->where('status', 'scheduled')
            ->where('scheduled_at <=', date('Y-m-d H:i:s'))
            ->get()->getResult();
    }

    /**
     * Get audience count based on segment rules
     */
    public function getAudienceCount($target, $segmentRules = null)
    {
        $result = $this->buildAudienceQuery($target, $segmentRules, true);
        return $result;
    }

    /**
     * Get audience tokens for sending
     */
    public function getAudienceTokens($target, $segmentRules = null)
    {
        return $this->buildAudienceQuery($target, $segmentRules, false);
    }

    /**
     * Build audience query based on target and segment rules
     */
    private function buildAudienceQuery($target, $segmentRules = null, $countOnly = false)
    {
        $rules = is_string($segmentRules) ? json_decode($segmentRules, true) : $segmentRules;
        $results = [];

        // Query clients
        if ($target === 'all' || $target === 'clients' || $target === 'segment') {
            $builder = $this->db->table('users');

            if ($countOnly) {
                $builder->select('COUNT(*) as count');
            } else {
                $builder->select('id, name, phone, device_token, city, "user" as user_type');
            }

            $builder->where('status', 'active');
            $builder->where('device_token IS NOT NULL');
            $builder->where('device_token !=', 'default_token');
            $builder->where('device_token !=', '');

            // Apply segment rules
            if ($target === 'segment' && !empty($rules)) {
                $this->applySegmentRules($builder, $rules, 'users');
            }

            if ($target === 'providers') {
                // Skip users query
            } else {
                if ($countOnly) {
                    $row = $builder->get()->getRow();
                    $results['clients'] = (int) ($row->count ?? 0);
                } else {
                    $results['clients'] = $builder->get()->getResult();
                }
            }
        }

        // Query providers
        if ($target === 'all' || $target === 'providers' || $target === 'segment') {
            $builder = $this->db->table('service_providers');

            if ($countOnly) {
                $builder->select('COUNT(*) as count');
            } else {
                $builder->select('id, name, phone, device_token, city, "provider" as user_type');
            }

            $builder->where('status', 'active');
            $builder->where('device_token IS NOT NULL');
            $builder->where('device_token !=', 'default_token');
            $builder->where('device_token !=', '');

            // Apply segment rules
            if ($target === 'segment' && !empty($rules)) {
                $this->applySegmentRules($builder, $rules, 'service_providers');
            }

            if ($target === 'clients') {
                // Skip providers query
            } else {
                if ($countOnly) {
                    $row = $builder->get()->getRow();
                    $results['providers'] = (int) ($row->count ?? 0);
                } else {
                    $results['providers'] = $builder->get()->getResult();
                }
            }
        }

        if ($countOnly) {
            return ($results['clients'] ?? 0) + ($results['providers'] ?? 0);
        }

        // Merge and return all audience members
        $audience = array_merge($results['clients'] ?? [], $results['providers'] ?? []);
        return $audience;
    }

    /**
     * Apply segment rules to query builder
     */
    private function applySegmentRules($builder, $rules, $table)
    {
        if (!empty($rules['city'])) {
            $cities = is_array($rules['city']) ? $rules['city'] : [$rules['city']];
            $builder->whereIn("{$table}.city", $cities);
        }

        if (!empty($rules['status'])) {
            $builder->where("{$table}.status", $rules['status']);
        }

        if (!empty($rules['registered_after'])) {
            $builder->where("{$table}.created_at >=", $rules['registered_after']);
        }

        if (!empty($rules['registered_before'])) {
            $builder->where("{$table}.created_at <=", $rules['registered_before']);
        }

        if (!empty($rules['has_app']) && $table === 'users') {
            $builder->where("{$table}.has_app_installed", 'yes');
        }

        if (!empty($rules['source'])) {
            if ($table === 'users') {
                $builder->where("{$table}.source", $rules['source']);
            }
        }

        // Pet type filter (only for clients)
        if (!empty($rules['pet_type']) && $table === 'users') {
            $builder->join('user_pets', "user_pets.user_id = {$table}.id", 'inner');
            $builder->join('pet_types', 'pet_types.id = user_pets.pet_type', 'inner');
            $petTypes = is_array($rules['pet_type']) ? $rules['pet_type'] : [$rules['pet_type']];
            $builder->whereIn('pet_types.name', $petTypes);
            $builder->groupBy("{$table}.id");
        }

        // Service type filter (for providers)
        if (!empty($rules['service_type']) && $table === 'service_providers') {
            $builder->join('provider_services', "provider_services.provider_id = {$table}.id", 'inner');
            $builder->join('services', 'services.id = provider_services.service_id', 'inner');
            $serviceTypes = is_array($rules['service_type']) ? $rules['service_type'] : [$rules['service_type']];
            $builder->whereIn('services.name', $serviceTypes);
            $builder->groupBy("{$table}.id");
        }
    }

    /**
     * Update campaign statistics
     */
    public function updateStats($campaignId, $stats)
    {
        $data = [];
        if (isset($stats['sent_count'])) {
            $data['sent_count'] = $stats['sent_count'];
        }
        if (isset($stats['failed_count'])) {
            $data['failed_count'] = $stats['failed_count'];
        }
        if (isset($stats['total_audience'])) {
            $data['total_audience'] = $stats['total_audience'];
        }

        if (!empty($data)) {
            $this->db->table('notification_campaigns')
                ->where('id', $campaignId)
                ->update($data);
        }
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats()
    {
        $today = date('Y-m-d');

        // Campaign stats
        $campaigns = $this->db->table('notification_campaigns')
            ->select("
                COUNT(*) as total_campaigns,
                SUM(status = 'sent') as sent_campaigns,
                SUM(status = 'scheduled') as scheduled_campaigns,
                SUM(status = 'draft') as draft_campaigns,
                SUM(status = 'sending') as sending_campaigns,
                SUM(sent_count) as total_sent,
                SUM(failed_count) as total_failed,
                SUM(opened_count) as total_opened,
                SUM(clicked_count) as total_clicked
            ")
            ->get()->getRow();

        // Today's stats
        $todayStats = $this->db->table('notification_logs')
            ->select("
                COUNT(*) as total_today,
                SUM(status = 'sent' OR status = 'delivered') as sent_today,
                SUM(status = 'failed') as failed_today,
                SUM(status = 'opened') as opened_today
            ")
            ->where('DATE(created_at)', $today)
            ->get()->getRow();

        // Last 7 days chart data
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $row = $this->db->table('notification_logs')
                ->select("COUNT(*) as count")
                ->where('DATE(created_at)', $date)
                ->get()->getRow();
            $chartData[] = [
                'date'  => date('M d', strtotime($date)),
                'count' => (int) ($row->count ?? 0),
            ];
        }

        // Channel breakdown
        $channels = $this->db->table('notification_logs')
            ->select("channel, COUNT(*) as count")
            ->where('created_at >=', date('Y-m-d', strtotime('-30 days')))
            ->groupBy('channel')
            ->get()->getResult();

        return [
            'campaigns'    => $campaigns,
            'today'        => $todayStats,
            'chart'        => $chartData,
            'channels'     => $channels,
        ];
    }

    /**
     * Get recent campaigns for dashboard
     */
    public function getRecentCampaigns($limit = 10)
    {
        return $this->db->table('notification_campaigns nc')
            ->select('nc.*, e.name as creator_name')
            ->join('employees e', 'e.id = nc.created_by', 'left')
            ->orderBy('nc.created_at', 'DESC')
            ->limit($limit)
            ->get()->getResult();
    }
}
