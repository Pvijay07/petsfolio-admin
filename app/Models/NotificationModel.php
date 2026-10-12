<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'user_push_subscriptions ';
    protected $primaryKey = 'id';
    protected $allowedFields = ['endpoint', 'public_key', 'auth_token', 'user_id', 'created_at'];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function saveSubscription($subscription, $userId = null)
    {
        // Debug the subscription structure
        log_message('debug', 'Subscription data: ' . print_r($subscription, true));
        
        // Handle both object and array formats
        if (is_array($subscription)) {
            $endpoint = $subscription['endpoint'] ?? null;
            $keys = $subscription['keys'] ?? [];
            $p256dh = is_array($keys) ? ($keys['p256dh'] ?? null) : ($keys->p256dh ?? null);
            $auth = is_array($keys) ? ($keys['auth'] ?? null) : ($keys->auth ?? null);
        } else {
            $endpoint = $subscription->endpoint ?? null;
            $p256dh = $subscription->keys->p256dh ?? null;
            $auth = $subscription->keys->auth ?? null;
        }

        // Validate required fields
        if (!$endpoint || !$p256dh || !$auth) {
            throw new \Exception('Invalid subscription data: missing required fields');
        }

        $data = [
            'endpoint' => $endpoint,
            'public_key' => $p256dh,
            'auth_token' => $auth,
            'user_id' => $userId ?? 1
        ];

        // Check if subscription already exists
        $existing = $this->where('endpoint', $endpoint)->first();
        
        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            return $this->insert($data);
        }
    }

    public function getSubscriptions($userId = null)
    {
        if ($userId) {
            return $this->where('user_id', $userId)->findAll();
        }
        return $this->findAll();
    }

    public function deleteSubscription($endpoint)
    {
        return $this->where('endpoint', $endpoint)->delete();
    }

    public function getSubscriptionByEndpoint($endpoint)
    {
        return $this->where('endpoint', $endpoint)->first();
    }
<<<<<<< HEAD
=======

    /**
     * Auto delete notifications from specified table after a retention period in days.
     *
     * @param int    $days     Retention threshold in days (default: 30)
     * @param string $table    Table to purge (default: 'notifications')
     * @param bool   $readOnly Only purge read notifications
     * @return array Purge results summary
     */
    public function purgeOldNotifications($days = 30, $table = 'notifications', $readOnly = false)
    {
        $purgeService = new \App\Libraries\NotificationPurgeService();
        return $purgeService->purge([
            'days'      => $days,
            'table'     => $table,
            'read_only' => $readOnly
        ]);
    }
>>>>>>> origin/main
}