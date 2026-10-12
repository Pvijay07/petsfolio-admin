<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\NotificationTemplateModel;
use App\Models\NotificationCampaignModel;
use App\Models\NotificationLogModel;
use App\Libraries\Notification\CampaignService;
use App\Libraries\Notification\TemplateParser;

class NotificationCampaignController extends BaseController
{
    protected $templateModel;
    protected $campaignModel;
    protected $logModel;
    protected $campaignService;
    protected $parser;

    public function __construct()
    {
        $this->templateModel   = new NotificationTemplateModel();
        $this->campaignModel   = new NotificationCampaignModel();
        $this->logModel        = new NotificationLogModel();
        $this->campaignService = new CampaignService();
        $this->parser          = new TemplateParser();
    }

    /**
     * Notification Hub Dashboard
     */
    public function index()
    {
        $data = [];
        $data['stats']           = $this->campaignModel->getDashboardStats();
        $data['recentCampaigns'] = $this->campaignModel->getRecentCampaigns(10);
        $data['templateCount']   = $this->templateModel->getCountByStatus();

        return $this->loadView('admin/notifications/notification_hub', $data);
    }

    /**
     * Template Management Page
     */
    public function templates()
    {
        $data = [];
        $data['templates'] = $this->templateModel->getTemplates();
        $data['eventKeys'] = $this->templateModel->getEventKeys();

        return $this->loadView('admin/notifications/templates', $data);
    }

    /**
     * Save Template (Create/Update) — AJAX
     */
    public function saveTemplate()
    {
        $id = $this->request->getPost('id');

        $templateData = [
            'name'      => $this->request->getPost('name'),
            'event_key' => $this->request->getPost('event_key'),
            'title'     => $this->request->getPost('title'),
            'content'   => $this->request->getPost('content'),
            'body'      => $this->request->getPost('body'),
            'type'      => $this->request->getPost('type') ?: 'general',
            'channel'   => $this->request->getPost('channel') ?: 'push',
            'variables' => $this->request->getPost('variables'),
            'status'    => $this->request->getPost('status') ? 1 : 0,
        ];

        // Remove empty placeholders field
        if (empty($templateData['content'])) {
            $templateData['content'] = $templateData['body'] ?? '';
        }
        $templateData['placeholders'] = $this->request->getPost('variables') ?: '';

        if ($id) {
            $this->templateModel->updateTemplate($id, $templateData);
            return $this->response->setJSON([
                'status'  => true,
                'message' => 'Template updated successfully',
            ]);
        } else {
            $newId = $this->templateModel->createTemplate($templateData);
            return $this->response->setJSON([
                'status'  => true,
                'message' => 'Template created successfully',
                'id'      => $newId,
            ]);
        }
    }

    /**
     * Delete Template — AJAX
     */
    public function deleteTemplate()
    {
        $id = $this->request->getPost('id');

        if (!$id) {
            return $this->response->setJSON(['status' => false, 'message' => 'Invalid template ID']);
        }

        $this->templateModel->deleteTemplate($id);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Template deleted successfully',
        ]);
    }

    /**
     * Get template variables for event key — AJAX
     */
    public function getTemplateVariables()
    {
        $eventKey = $this->request->getGet('event_key');
        $variables = $this->templateModel->getVariablesForEvent($eventKey);

        return $this->response->setJSON([
            'status'    => true,
            'variables' => $variables,
        ]);
    }

    /**
     * Preview template — AJAX
     */
    public function previewTemplate()
    {
        $title = $this->request->getPost('title');
        $body  = $this->request->getPost('body');

        return $this->response->setJSON([
            'status' => true,
            'title'  => $this->parser->preview($title),
            'body'   => $this->parser->preview($body),
        ]);
    }

    /**
     * Campaign List Page
     */
    public function campaigns()
    {
        $filters = [
            'status'  => $this->request->getGet('status'),
            'type'    => $this->request->getGet('type'),
            'channel' => $this->request->getGet('channel'),
            'search'  => $this->request->getGet('search'),
        ];

        $data = [];
        $data['campaigns'] = $this->campaignModel->getCampaigns($filters);
        $data['filters']   = $filters;
        $data['templates'] = $this->templateModel->getActiveTemplates();

        return $this->loadView('admin/notifications/campaigns', $data);
    }

    /**
     * Save Campaign (Create/Update) — AJAX
     */
    public function saveCampaign()
    {
        $id = $this->request->getPost('id');

        // Build segment rules JSON
        $segmentRules = null;
        if ($this->request->getPost('target') === 'segment') {
            $segmentRules = json_encode([
                'city'              => $this->request->getPost('segment_city'),
                'pet_type'          => $this->request->getPost('segment_pet_type'),
                'service_type'      => $this->request->getPost('segment_service_type'),
                'registered_after'  => $this->request->getPost('segment_registered_after'),
                'registered_before' => $this->request->getPost('segment_registered_before'),
                'has_app'           => $this->request->getPost('segment_has_app'),
                'source'            => $this->request->getPost('segment_source'),
            ]);
        }

        $campaignData = [
            'name'           => $this->request->getPost('name'),
            'type'           => $this->request->getPost('type') ?: 'promotional',
            'target'         => $this->request->getPost('target') ?: 'all',
            'segment_rules'  => $segmentRules,
            'title'          => $this->request->getPost('title'),
            'body'           => $this->request->getPost('body'),
            'image_url'      => $this->request->getPost('image_url'),
            'route_name'     => $this->request->getPost('route_name'),
            'channel'        => $this->request->getPost('channel') ?: 'push',
            'template_id'    => $this->request->getPost('template_id') ?: null,
            'schedule_type'  => $this->request->getPost('schedule_type') ?: 'immediate',
            'scheduled_at'   => $this->request->getPost('scheduled_at') ?: null,
            'status'         => 'draft',
            'created_by'     => session()->get('user')->id ?? null,
        ];

        if ($id) {
            $this->campaignModel->updateCampaign($id, $campaignData);
            return $this->response->setJSON([
                'status'  => true,
                'message' => 'Campaign updated successfully',
            ]);
        } else {
            $newId = $this->campaignModel->createCampaign($campaignData);
            return $this->response->setJSON([
                'status'  => true,
                'message' => 'Campaign created successfully',
                'id'      => $newId,
            ]);
        }
    }

    /**
     * Send Campaign Immediately — AJAX
     */
    public function sendCampaign()
    {
        $id = $this->request->getPost('id');

        if (!$id) {
            return $this->response->setJSON(['status' => false, 'message' => 'Invalid campaign ID']);
        }

        $campaign = $this->campaignModel->getCampaign($id);

        if (!$campaign) {
            return $this->response->setJSON(['status' => false, 'message' => 'Campaign not found']);
        }

        if (!in_array($campaign->status, ['draft', 'scheduled', 'failed', 'sent'])) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Campaign cannot be queued — current status: ' . $campaign->status,
            ]);
        }

        // Queue campaign for background execution (status = scheduled)
        $scheduledAt = (!empty($campaign->scheduled_at) && $campaign->schedule_type === 'scheduled')
            ? $campaign->scheduled_at
            : date('Y-m-d H:i:s');

        $this->campaignService->scheduleCampaign($id, $scheduledAt);

        // Asynchronously trigger background worker so notifications start sending immediately in background without web UI delay
        $sparkPath = ROOTPATH . 'spark';
        if (file_exists($sparkPath)) {
            @exec("php " . escapeshellarg($sparkPath) . " notifications:process > /dev/null 2>&1 &");
        }

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Campaign queued successfully for immediate background sending! Dispatching notifications in background queue without UI loading delay.',
            'data'    => [
                'campaign_id'  => $id,
                'status'       => 'scheduled',
                'scheduled_at' => $scheduledAt,
            ],
        ]);
    }

    /**
     * Cancel Campaign — AJAX
     */
    public function cancelCampaign()
    {
        $id = $this->request->getPost('id');

        $result = $this->campaignService->cancelCampaign($id);

        return $this->response->setJSON([
            'status'  => $result['success'],
            'message' => $result['success'] ? 'Campaign cancelled' : ($result['error'] ?? 'Failed to cancel'),
        ]);
    }

    /**
     * Preview audience count for segment rules — AJAX
     */
    public function previewAudience()
    {
        $target = $this->request->getPost('target') ?: 'all';

        $segmentRules = null;
        if ($target === 'segment') {
            $segmentRules = [
                'city'              => $this->request->getPost('segment_city'),
                'pet_type'          => $this->request->getPost('segment_pet_type'),
                'service_type'      => $this->request->getPost('segment_service_type'),
                'registered_after'  => $this->request->getPost('segment_registered_after'),
                'registered_before' => $this->request->getPost('segment_registered_before'),
                'has_app'           => $this->request->getPost('segment_has_app'),
                'source'            => $this->request->getPost('segment_source'),
            ];
        }

        $count = $this->campaignService->previewAudience($target, $segmentRules);

        return $this->response->setJSON([
            'status' => true,
            'count'  => $count,
        ]);
    }

    /**
     * Notification Logs Page
     */
    public function logs()
    {
        $datePreset = $this->request->getGet('date_preset');
        $dateFrom   = $this->request->getGet('date_from');
        $dateTo     = $this->request->getGet('date_to');

        if (!empty($datePreset) && (empty($dateFrom) || empty($dateTo))) {
            switch ($datePreset) {
                case 'today':
                    $dateFrom = date('Y-m-d');
                    $dateTo   = date('Y-m-d');
                    break;
                case 'yesterday':
                    $dateFrom = date('Y-m-d', strtotime('-1 day'));
                    $dateTo   = date('Y-m-d', strtotime('-1 day'));
                    break;
                case 'last_7_days':
                    $dateFrom = date('Y-m-d', strtotime('-6 days'));
                    $dateTo   = date('Y-m-d');
                    break;
                case 'last_30_days':
                    $dateFrom = date('Y-m-d', strtotime('-29 days'));
                    $dateTo   = date('Y-m-d');
                    break;
                case 'this_month':
                    $dateFrom = date('Y-m-01');
                    $dateTo   = date('Y-m-t');
                    break;
                case 'last_month':
                    $dateFrom = date('Y-m-01', strtotime('first day of last month'));
                    $dateTo   = date('Y-m-t', strtotime('last day of last month'));
                    break;
            }
        }

        $filters = [
            'campaign_id'   => $this->request->getGet('campaign_id'),
            'channel'       => $this->request->getGet('channel'),
            'status'        => $this->request->getGet('status'),
            'user_type'     => $this->request->getGet('user_type'),
            'type_category' => $this->request->getGet('type_category'),
            'booking_id'    => $this->request->getGet('booking_id'),
            'service'       => $this->request->getGet('service'),
            'booking_event' => $this->request->getGet('booking_event'),
            'date_preset'   => $datePreset,
            'date_from'     => $dateFrom,
            'date_to'       => $dateTo,
            'search'        => $this->request->getGet('search'),
        ];

        $page  = max(1, (int) $this->request->getGet('page'));
        $limit = 50;
        $offset = ($page - 1) * $limit;

        $result = $this->logModel->getLogs($filters, $limit, $offset);

        // If page is out of range (e.g. user visited page=4 with few records), reset to page 1
        if ($page > 1 && $offset >= $result['total'] && $result['total'] > 0) {
            $page = 1;
            $offset = 0;
            $result = $this->logModel->getLogs($filters, $limit, $offset);
        }

        $data = [];
        $data['logs']          = $result['logs'];
        $data['totalLogs']     = $result['total'];
        $data['filters']       = $filters;
        $data['page']          = $page;
        $data['limit']         = $limit;
        $data['totalPages']    = ceil($result['total'] / $limit);
        $data['summaryCounts'] = $this->logModel->getSummaryCounts();

        return $this->loadView('admin/notifications/logs', $data);
    }

    /**
     * Job Notification Analytics & SP Health Report Dashboard
     */
    public function analytics()
    {
        $dateFrom = $this->request->getGet('date_from') ?: date('Y-m-d', strtotime('-30 days'));
        $dateTo   = $this->request->getGet('date_to') ?: date('Y-m-d');

        if ($this->request->isAJAX() || $this->request->getGet('format') === 'json') {
            $analytics = $this->logModel->getAnalytics($dateFrom, $dateTo);
            return $this->response->setJSON([
                'status' => true,
                'data'   => $analytics,
            ]);
        }

        $jobAnalytics = $this->logModel->getJobNotificationAnalytics($dateFrom, $dateTo);

        $data = [
            'dateFrom'     => $dateFrom,
            'dateTo'       => $dateTo,
            'analytics'    => $jobAnalytics,
            'funnel'       => $jobAnalytics['funnel'],
            'benchmarks'   => $jobAnalytics['benchmarks'],
            'spReport'     => $jobAnalytics['spReport'],
            'rootCauses'   => $jobAnalytics['rootCauses'],
        ];

        return $this->loadView('admin/notifications/analytics', $data);
    }
}
