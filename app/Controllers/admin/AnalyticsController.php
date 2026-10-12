<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\AnalyticsEventModel;
use App\Models\AnalyticsFunnelModel;
use App\Services\FunnelAnalyticsService;

class AnalyticsController extends BaseController
{
    protected AnalyticsEventModel $eventModel;
    protected AnalyticsFunnelModel $funnelModel;
    protected FunnelAnalyticsService $analyticsService;

    public function __construct()
    {
        $this->eventModel       = new AnalyticsEventModel();
        $this->funnelModel      = new AnalyticsFunnelModel();
        $this->analyticsService = new FunnelAnalyticsService();
    }

    /**
     * Display Customer Journey Funnel Dashboard
     */
    public function index()
    {
        $db = \Config\Database::connect();
        $this->funnelModel->ensureTables();

        // Check if database has zero events; auto-seed initial realistic demo data if empty
        $eventsCount = $this->eventModel->countAllResults();
        if ($eventsCount === 0) {
            $this->analyticsService->generateSampleData(14, 180);
        }

        $activeFunnels = $this->funnelModel->getActiveFunnels();
        $selectedKey = $this->request->getGet('funnel') ?: 'customer_booking';
        $currentFunnel = $this->funnelModel->getByKey($selectedKey) ?: $this->funnelModel->getDefaultFunnel();

        $filterOptions = $this->eventModel->getFilterDropdownOptions();

        $data = [
            'title'          => 'Customer Journey & Funnel Analytics',
            'activeFunnels'  => $activeFunnels,
            'currentFunnel'  => $currentFunnel,
            'filterOptions'  => $filterOptions,
            'initialFunnelKey' => $currentFunnel['funnel_key'] ?? 'customer_booking',
        ];

        return $this->loadView('admin/analytics/customer_journey', $data);
    }

    /**
     * AJAX: Get computed funnel metrics and drop-off statistics
     */
    public function getFunnelData()
    {
        $funnelKey = $this->request->getGet('funnel_key') ?: 'customer_booking';
        $funnel = $this->funnelModel->getByKey($funnelKey) ?: $this->funnelModel->getDefaultFunnel();

        if (!$funnel) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Funnel not found.']);
        }

        $filters = $this->extractFilters();
        $result = $this->analyticsService->calculateFunnel($funnel, $filters);

        return $this->response->setJSON([
            'status' => 'success',
            'funnel' => [
                'key'         => $funnel['funnel_key'],
                'name'        => $funnel['name'],
                'description' => $funnel['description'],
                'category'    => $funnel['category'],
            ],
            'kpis'   => $result['kpis'],
            'steps'  => $result['steps'],
            'chart'  => $result['chart'],
        ]);
    }

    /**
     * AJAX: Get Problem & Rage Signals (errors, rage clicks, drop-off correlations)
     */
    public function getProblemSignals()
    {
        $filters = $this->extractFilters();
        $signals = $this->analyticsService->getProblemSignals($filters);

        return $this->response->setJSON([
            'status'  => 'success',
            'data'    => $signals,
        ]);
    }

    /**
     * AJAX: Get list of users who dropped off at a specific step
     */
    public function getDropoffUsers()
    {
        $funnelKey = $this->request->getGet('funnel_key') ?: 'customer_booking';
        $stepKey   = $this->request->getGet('step_key');

        if (empty($stepKey)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'step_key is required.']);
        }

        $filters = $this->extractFilters();
        $users = $this->analyticsService->getDropoffUsers($funnelKey, $stepKey, $filters);

        return $this->response->setJSON([
            'status' => 'success',
            'count'  => count($users),
            'users'  => $users,
        ]);
    }

    /**
     * AJAX: Live / paginated events stream
     */
    public function getEventsStream()
    {
        $filters = $this->extractFilters();
        $limit = min((int)($this->request->getGet('limit') ?: 50), 150);
        $search = $this->request->getGet('search');

        $builder = $this->eventModel->buildFilteredQuery($filters);
        if (!empty($search)) {
            $builder->groupStart()
                ->like('event_name', $search)
                ->orLike('screen_name', $search)
                ->orLike('session_id', $search)
                ->orLike('user_id', $search)
                ->groupEnd();
        }

        $builder->orderBy('created_at', 'DESC');
        $builder->limit($limit);
        $events = $builder->get()->getResultArray();

        foreach ($events as &$ev) {
            if (!empty($ev['metadata']) && is_string($ev['metadata'])) {
                $decoded = json_decode($ev['metadata'], true);
                $ev['metadata_decoded'] = $decoded ?: $ev['metadata'];
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'count'  => count($events),
            'events' => $events,
        ]);
    }

    /**
     * AJAX: Save or update dynamic funnel configuration
     */
    public function saveFunnel()
    {
        $id          = $this->request->getPost('id');
        $funnelKey   = strtolower(trim(preg_replace('/[^a-zA-Z0-9_]/', '_', $this->request->getPost('funnel_key'))));
        $name        = trim($this->request->getPost('name'));
        $description = trim($this->request->getPost('description'));
        $category    = trim($this->request->getPost('category')) ?: 'customer';
        $inactivity  = (int)($this->request->getPost('inactivity_window_minutes') ?: 30);
        $stepsJson   = $this->request->getPost('steps');

        if (empty($name)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Funnel name is required.']);
        }

        $decodedSteps = is_string($stepsJson) ? json_decode($stepsJson, true) : $stepsJson;
        if (empty($decodedSteps) || !is_array($decodedSteps)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'At least two funnel steps are required.']);
        }

        $data = [
            'name'                      => $name,
            'description'               => $description,
            'category'                  => $category,
            'inactivity_window_minutes' => $inactivity,
            'steps'                     => json_encode($decodedSteps, JSON_UNESCAPED_UNICODE),
            'is_active'                 => 1,
        ];

        if (!empty($id)) {
            $this->funnelModel->update($id, $data);
            $msg = 'Funnel configuration updated successfully.';
        } else {
            if (empty($funnelKey)) {
                $funnelKey = 'funnel_' . time();
            }
            $data['funnel_key'] = $funnelKey;
            $data['is_system']  = 0;
            $this->funnelModel->insert($data);
            $msg = 'New funnel configuration created successfully.';
        }

        return $this->response->setJSON([
            'status'     => 'success',
            'message'    => $msg,
            'funnel_key' => $funnelKey,
        ]);
    }

    /**
     * AJAX: Delete custom funnel
     */
    public function deleteFunnel($id)
    {
        $funnel = $this->funnelModel->find($id);
        if (!$funnel) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Funnel not found.']);
        }

        if (!empty($funnel['is_system'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'System default funnels cannot be deleted.']);
        }

        $this->funnelModel->delete($id);
        return $this->response->setJSON(['status' => 'success', 'message' => 'Funnel deleted successfully.']);
    }

    /**
     * AJAX: Seed realistic demo telemetry
     */
    public function generateSampleData()
    {
        $days = (int)($this->request->getPost('days') ?: 14);
        $count = (int)($this->request->getPost('journeys') ?: 180);

        $res = $this->analyticsService->generateSampleData($days, $count);
        return $this->response->setJSON($res);
    }

    /**
     * Extract filter array from HTTP request
     */
    protected function extractFilters(): array
    {
        return [
            'start_date'       => $this->request->getGet('start_date'),
            'end_date'         => $this->request->getGet('end_date'),
            'platform'         => $this->request->getGet('platform'),
            'app_version'      => $this->request->getGet('app_version'),
            'customer_type'    => $this->request->getGet('customer_type'),
            'service_id'       => $this->request->getGet('service_id'),
            'service_category' => $this->request->getGet('service_category'),
            'city'             => $this->request->getGet('city'),
            'provider_id'      => $this->request->getGet('provider_id'),
            'payment_method'   => $this->request->getGet('payment_method'),
            'campaign_source'  => $this->request->getGet('campaign_source'),
        ];
    }
}
