<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationLogModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Log a notification
     */
    public function createLog($data)
    {
        $this->db->table('notification_logs')->insert($data);
        return $this->db->insertID();
    }

    /**
     * Batch insert logs
     */
    public function createBatchLogs($logs)
    {
        if (!empty($logs)) {
            $this->db->table('notification_logs')->insertBatch($logs);
        }
    }

    /**
     * Update log status
     */
    public function updateLogStatus($id, $status, $extraData = [])
    {
        $data = ['status' => $status];

        switch ($status) {
            case 'sent':
                $data['sent_at'] = date('Y-m-d H:i:s');
                break;
            case 'delivered':
                $data['delivered_at'] = date('Y-m-d H:i:s');
                break;
            case 'opened':
                $data['opened_at'] = date('Y-m-d H:i:s');
                break;
            case 'clicked':
                $data['clicked_at'] = date('Y-m-d H:i:s');
                break;
            case 'failed':
                if (isset($extraData['error_message'])) {
                    $data['error_message'] = $extraData['error_message'];
                }
                break;
        }

        return $this->db->table('notification_logs')
            ->where('id', $id)
            ->update($data);
    }

    /**
     * Get logs with filters
     */
    public function getLogs($filters = [], $limit = 100, $offset = 0)
    {
        $builder = $this->db->table('notification_logs nl');
        $builder->select('nl.*, u.name as user_name, u.phone as user_phone');
        $builder->join('users u', 'u.id = nl.user_id AND nl.user_type = "user"', 'left');

        if (!empty($filters['campaign_id'])) {
            $builder->where('nl.campaign_id', $filters['campaign_id']);
        }
        if (!empty($filters['user_id'])) {
            $builder->where('nl.user_id', $filters['user_id']);
        }
        if (!empty($filters['user_type'])) {
            $builder->where('nl.user_type', $filters['user_type']);
        }
        if (!empty($filters['channel'])) {
            $builder->where('nl.channel', $filters['channel']);
        }
        if (!empty($filters['status'])) {
            $builder->where('nl.status', $filters['status']);
        }
        if (!empty($filters['date_from'])) {
            $builder->where('nl.created_at >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $builder->where('nl.created_at <=', $filters['date_to'] . ' 23:59:59');
        }
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('nl.title', $filters['search'])
                ->orLike('u.name', $filters['search'])
                ->orLike('u.phone', $filters['search'])
                ->groupEnd();
        }

        $builder->orderBy('nl.created_at', 'DESC');

        // Get total count for pagination
        $countBuilder = clone $builder;

        $builder->limit($limit, $offset);
        $logs = $builder->get()->getResult();

        $total = $countBuilder->countAllResults(false);

        return [
            'logs'  => $logs,
            'total' => $total,
        ];
    }

    /**
     * Get logs by campaign
     */
    public function getLogsByCampaign($campaignId)
    {
        return $this->db->table('notification_logs')
            ->where('campaign_id', $campaignId)
            ->orderBy('created_at', 'DESC')
            ->get()->getResult();
    }

    /**
     * Get logs by user
     */
    public function getLogsByUser($userId, $userType = 'user')
    {
        return $this->db->table('notification_logs')
            ->where('user_id', $userId)
            ->where('user_type', $userType)
            ->orderBy('created_at', 'DESC')
            ->limit(50)
            ->get()->getResult();
    }

    /**
     * Get analytics data
     */
    public function getAnalytics($dateFrom = null, $dateTo = null)
    {
        $dateFrom = $dateFrom ?: date('Y-m-d', strtotime('-30 days'));
        $dateTo   = $dateTo ?: date('Y-m-d');

        // Overall stats
        $overall = $this->db->table('notification_logs')
            ->select("
                COUNT(*) as total,
                SUM(status IN ('sent','delivered','opened','clicked')) as sent,
                SUM(status = 'delivered') as delivered,
                SUM(status = 'failed') as failed,
                SUM(status = 'opened') as opened,
                SUM(status = 'clicked') as clicked
            ")
            ->where('created_at >=', $dateFrom)
            ->where('created_at <=', $dateTo . ' 23:59:59')
            ->get()->getRow();

        // Channel breakdown
        $channels = $this->db->table('notification_logs')
            ->select("channel, COUNT(*) as total, SUM(status = 'sent' OR status = 'delivered') as success, SUM(status = 'failed') as failed")
            ->where('created_at >=', $dateFrom)
            ->where('created_at <=', $dateTo . ' 23:59:59')
            ->groupBy('channel')
            ->get()->getResult();

        // Daily trend
        $dailyTrend = $this->db->table('notification_logs')
            ->select("DATE(created_at) as date, COUNT(*) as total, SUM(status IN ('sent','delivered')) as success")
            ->where('created_at >=', $dateFrom)
            ->where('created_at <=', $dateTo . ' 23:59:59')
            ->groupBy('DATE(created_at)')
            ->orderBy('DATE(created_at)', 'ASC')
            ->get()->getResult();

        return [
            'overall'    => $overall,
            'channels'   => $channels,
            'dailyTrend' => $dailyTrend,
        ];
    }

    /**
     * Get total logs count with optional filters
     */
    public function getLogsCount($filters = [])
    {
        $builder = $this->db->table('notification_logs');

        if (!empty($filters['campaign_id'])) {
            $builder->where('campaign_id', $filters['campaign_id']);
        }
        if (!empty($filters['status'])) {
            $builder->where('status', $filters['status']);
        }
        if (!empty($filters['channel'])) {
            $builder->where('channel', $filters['channel']);
        }

        return $builder->countAllResults();
    }
}
