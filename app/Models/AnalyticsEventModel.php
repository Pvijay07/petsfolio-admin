<?php

namespace App\Models;

use CodeIgniter\Model;

class AnalyticsEventModel extends Model
{
    protected $table            = 'analytics_events';
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
        'event_name',
        'screen_name',
        'booking_id',
        'service_id',
        'service_category',
        'provider_id',
        'city',
        'payment_method',
        'campaign_source',
        'is_new_user',
        'metadata',
        'ip_address',
        'user_agent',
        'created_at'
    ];

    protected $useTimestamps = false; // We set created_at manually or default

    /**
     * Insert a single event
     */
    public function logEvent(array $data)
    {
        if (isset($data['metadata']) && is_array($data['metadata'])) {
            $data['metadata'] = json_encode($data['metadata'], JSON_UNESCAPED_UNICODE);
        }
        if (empty($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        // Auto lookup service_category if service_id is provided
        if (!empty($data['service_id']) && empty($data['service_category'])) {
            $db = \Config\Database::connect();
            $svc = $db->table('services')->select('name')->where('id', (int)$data['service_id'])->get()->getRow();
            if ($svc) {
                $data['service_category'] = $svc->name;
            }
        }

        $insertId = $this->insert($data);

        // Update session summary in background
        if ($insertId && !empty($data['session_id'])) {
            $sessionModel = new AnalyticsSessionModel();
            $sessionModel->updateSessionActivity($data);
        }

        return $insertId;
    }

    /**
     * Batch log multiple events
     */
    public function logEventsBatch(array $events)
    {
        $db = \Config\Database::connect();
        $processed = [];
        $sessionModel = new AnalyticsSessionModel();

        foreach ($events as $item) {
            if (empty($item['event_name']) || empty($item['session_id'])) {
                continue;
            }
            $meta = $item['metadata'] ?? null;
            if (isset($meta) && is_array($meta)) {
                $meta = json_encode($meta, JSON_UNESCAPED_UNICODE);
            }

            $processed[] = [
                'session_id'       => (string)$item['session_id'],
                'user_id'          => !empty($item['user_id']) ? (int)$item['user_id'] : null,
                'platform'         => $item['platform'] ?? 'unknown',
                'device'           => $item['device'] ?? null,
                'app_version'      => $item['app_version'] ?? null,
                'event_name'       => (string)$item['event_name'],
                'screen_name'      => $item['screen_name'] ?? null,
                'booking_id'       => !empty($item['booking_id']) ? (int)$item['booking_id'] : null,
                'service_id'       => !empty($item['service_id']) ? (int)$item['service_id'] : null,
                'service_category' => $item['service_category'] ?? null,
                'provider_id'      => !empty($item['provider_id']) ? (int)$item['provider_id'] : null,
                'city'             => $item['city'] ?? null,
                'payment_method'   => $item['payment_method'] ?? null,
                'campaign_source'  => $item['campaign_source'] ?? null,
                'is_new_user'      => !empty($item['is_new_user']) ? 1 : 0,
                'metadata'         => $meta,
                'ip_address'       => $item['ip_address'] ?? null,
                'user_agent'       => $item['user_agent'] ?? null,
                'created_at'       => $item['created_at'] ?? date('Y-m-d H:i:s'),
            ];
        }

        if (empty($processed)) {
            return 0;
        }

        $inserted = $this->insertBatch($processed);

        // Update session summaries
        foreach ($processed as $p) {
            try {
                $sessionModel->updateSessionActivity($p);
            } catch (\Exception $e) {
                // Ignore session update error in batch
            }
        }

        return $inserted;
    }

    /**
     * Build base query with all multi-segment filters applied
     */
    public function buildFilteredQuery(array $filters = [])
    {
        $builder = $this->builder();

        // 1. Date Range Filter
        if (!empty($filters['start_date'])) {
            $builder->where('created_at >=', $filters['start_date'] . ' 00:00:00');
        }
        if (!empty($filters['end_date'])) {
            $builder->where('created_at <=', $filters['end_date'] . ' 23:59:59');
        }

        // 2. Platform (android / ios / web)
        if (!empty($filters['platform']) && $filters['platform'] !== 'all') {
            $builder->where('platform', strtolower($filters['platform']));
        }

        // 3. App Version
        if (!empty($filters['app_version']) && $filters['app_version'] !== 'all') {
            $builder->where('app_version', $filters['app_version']);
        }

        // 4. Customer Type (new vs returning)
        if (isset($filters['customer_type']) && $filters['customer_type'] !== 'all') {
            if ($filters['customer_type'] === 'new') {
                $builder->where('is_new_user', 1);
            } elseif ($filters['customer_type'] === 'returning') {
                $builder->where('is_new_user', 0);
            }
        }

        // 5. Service Category / Specific Service
        if (!empty($filters['service_id']) && $filters['service_id'] !== 'all') {
            $builder->where('service_id', (int)$filters['service_id']);
        } elseif (!empty($filters['service_category']) && $filters['service_category'] !== 'all') {
            $builder->where('service_category', $filters['service_category']);
        }

        // 6. City
        if (!empty($filters['city']) && $filters['city'] !== 'all') {
            $builder->where('city', $filters['city']);
        }

        // 7. Provider
        if (!empty($filters['provider_id']) && $filters['provider_id'] !== 'all') {
            $builder->where('provider_id', (int)$filters['provider_id']);
        }

        // 8. Payment Method
        if (!empty($filters['payment_method']) && $filters['payment_method'] !== 'all') {
            $builder->where('payment_method', strtolower($filters['payment_method']));
        }

        // 9. Campaign / Acquisition Source
        if (!empty($filters['campaign_source']) && $filters['campaign_source'] !== 'all') {
            $builder->where('campaign_source', $filters['campaign_source']);
        }

        return $builder;
    }

    /**
     * Get distinct filter dropdown values
     */
    public function getFilterDropdownOptions()
    {
        $db = \Config\Database::connect();

        // Platforms
        $platforms = $this->builder()->select('DISTINCT(platform) as val')->where('platform IS NOT NULL')->where('platform !=', '')->get()->getResultArray();
        
        // App Versions
        $versions = $this->builder()->select('DISTINCT(app_version) as val')->where('app_version IS NOT NULL')->where('app_version !=', '')->orderBy('app_version', 'DESC')->get()->getResultArray();

        // Cities (from events or service_providers or users)
        $cities = $this->builder()->select('DISTINCT(city) as val')->where('city IS NOT NULL')->where('city !=', '')->orderBy('city', 'ASC')->get()->getResultArray();

        // Services (from services table)
        $services = [];
        if ($db->tableExists('services')) {
            $services = $db->table('services')->select('id, name')->where('status', 'Active')->orderBy('name', 'ASC')->get()->getResultArray();
        }

        // Payment Methods
        $paymentMethods = $this->builder()->select('DISTINCT(payment_method) as val')->where('payment_method IS NOT NULL')->where('payment_method !=', '')->get()->getResultArray();

        return [
            'platforms'      => array_filter(array_column($platforms, 'val')),
            'versions'       => array_filter(array_column($versions, 'val')),
            'cities'         => array_filter(array_column($cities, 'val')),
            'services'       => $services,
            'paymentMethods' => array_filter(array_column($paymentMethods, 'val')),
        ];
    }
}
