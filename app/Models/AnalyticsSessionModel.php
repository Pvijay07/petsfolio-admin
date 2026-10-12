<?php

namespace App\Models;

use CodeIgniter\Model;

class AnalyticsSessionModel extends Model
{
    protected $table            = 'analytics_sessions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'session_id',
        'user_id',
        'platform',
        'device',
        'app_version',
        'city',
        'is_new_user',
        'started_at',
        'last_event_at',
        'event_count',
        'last_event_name',
        'last_screen_name',
        'highest_funnel_step',
        'has_dropped',
        'dropoff_step',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Upsert session activity
     */
    public function updateSessionActivity(array $event)
    {
        $db = \Config\Database::connect();
        $sessionId = $event['session_id'] ?? null;
        if (!$sessionId) return null;

        $existing = $this->where('session_id', $sessionId)->first();
        $now = $event['created_at'] ?? date('Y-m-d H:i:s');

        if ($existing) {
            $data = [
                'last_event_at'   => $now,
                'event_count'     => ($existing['event_count'] ?? 1) + 1,
                'last_event_name' => $event['event_name'] ?? $existing['last_event_name'],
                'last_screen_name'=> $event['screen_name'] ?? $existing['last_screen_name'],
            ];
            if (!empty($event['user_id']) && empty($existing['user_id'])) {
                $data['user_id'] = $event['user_id'];
            }
            if (!empty($event['city']) && empty($existing['city'])) {
                $data['city'] = $event['city'];
            }
            $this->update($existing['id'], $data);
            return $existing['id'];
        } else {
            $data = [
                'session_id'      => $sessionId,
                'user_id'         => $event['user_id'] ?? null,
                'platform'        => $event['platform'] ?? 'unknown',
                'device'          => $event['device'] ?? null,
                'app_version'     => $event['app_version'] ?? null,
                'city'            => $event['city'] ?? null,
                'is_new_user'     => !empty($event['is_new_user']) ? 1 : 0,
                'started_at'      => $now,
                'last_event_at'   => $now,
                'event_count'     => 1,
                'last_event_name' => $event['event_name'] ?? null,
                'last_screen_name'=> $event['screen_name'] ?? null,
                'has_dropped'     => 0,
            ];
            return $this->insert($data);
        }
    }
}
