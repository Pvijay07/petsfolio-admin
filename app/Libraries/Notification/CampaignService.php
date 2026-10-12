<?php

namespace App\Libraries\Notification;

use App\Models\NotificationCampaignModel;
use App\Models\NotificationLogModel;
use App\Libraries\PushNotify;

/**
 * CampaignService
 * Handles campaign audience building and batch execution
 */
class CampaignService
{
    protected $campaignModel;
    protected $logModel;
    protected $parser;
    protected $pushService;

    public function __construct()
    {
        $this->campaignModel = new NotificationCampaignModel();
        $this->logModel      = new NotificationLogModel();
        $this->parser        = new TemplateParser();
    }

    /**
     * Execute a campaign — build audience, send notifications, log results
     *
     * @param int|object $campaign Campaign ID or campaign object
     * @return array Execution results
     */
    public function executeCampaign($campaign)
    {
        // Load campaign if ID is passed
        if (is_numeric($campaign)) {
            $campaign = $this->campaignModel->getCampaign($campaign);
        }

        if (!$campaign) {
            return ['success' => false, 'error' => 'Campaign not found'];
        }

        // Update status to sending
        $this->campaignModel->updateCampaign($campaign->id, [
            'status' => 'sending',
        ]);

        try {
            // Build audience
            $audience = $this->campaignModel->getAudienceTokens(
                $campaign->target,
                $campaign->segment_rules
            );

            $totalAudience = count($audience);

            if ($totalAudience === 0) {
                $this->campaignModel->updateCampaign($campaign->id, [
                    'status'         => 'failed',
                    'error_message'  => 'No audience members found for the selected criteria',
                    'total_audience' => 0,
                ]);
                return ['success' => false, 'error' => 'No audience found'];
            }

            // Update total audience count
            $this->campaignModel->updateCampaign($campaign->id, [
                'total_audience' => $totalAudience,
            ]);

            // Parse template variables if template is used
            $title = $campaign->title;
            $body  = $campaign->body;

            // Collect tokens for batch push
            $tokens = [];
            $tokenUserMap = [];
            $logEntries = [];

            foreach ($audience as $member) {
                $token = $member->device_token ?? null;

                if (empty($token) || $token === 'default_token') {
                    continue;
                }

                $tokens[] = $token;
                $tokenUserMap[$token] = $member;

                // Personalize message for each user
                $personalizedTitle = $this->parser->parse($title, [
                    'user_name' => $member->name ?? 'Pet Parent',
                    'app_name'  => 'Petsfolio',
                ]);
                $personalizedBody = $this->parser->parse($body, [
                    'user_name' => $member->name ?? 'Pet Parent',
                    'app_name'  => 'Petsfolio',
                ]);

                $logEntries[] = [
                    'user_id'      => $member->user_type === 'user' ? $member->id : null,
                    'provider_id'  => $member->user_type === 'provider' ? $member->id : null,
                    'user_type'    => $member->user_type ?? 'user',
                    'campaign_id'  => $campaign->id,
                    'channel'      => $campaign->channel === 'all' ? 'push' : $campaign->channel,
                    'title'        => $personalizedTitle,
                    'body'         => $personalizedBody,
                    'status'       => 'queued',
                    'device_token' => $token,
                    'created_at'   => date('Y-m-d H:i:s'),
                ];
            }

            // Insert all log entries
            if (!empty($logEntries)) {
                $this->logModel->createBatchLogs($logEntries);
            }

            // Send via push (batch)
            $sentCount   = 0;
            $failedCount = 0;

            if (!empty($tokens) && ($campaign->channel === 'push' || $campaign->channel === 'all')) {
                $sentCount = $this->sendBatchPush(
                    $tokens,
                    $title,
                    $body,
                    $campaign->route_name,
                    [],
                    $sentCount,
                    $failedCount,
                    $campaign->id
                );
            }

            // Update campaign stats
            $finalStatus = ($sentCount > 0) ? 'sent' : 'failed';

            $this->campaignModel->updateCampaign($campaign->id, [
                'status'       => $finalStatus,
                'sent_count'   => $sentCount,
                'failed_count' => $failedCount,
                'sent_at'      => date('Y-m-d H:i:s'),
            ]);

            return [
                'success'        => true,
                'total_audience' => $totalAudience,
                'sent'           => $sentCount,
                'failed'         => $failedCount,
            ];
        } catch (\Exception $e) {
            log_message('error', 'CampaignService Error: ' . $e->getMessage());

            $this->campaignModel->updateCampaign($campaign->id, [
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send batch push notifications
     */
    protected function sendBatchPush($tokens, $title, $body, $routeName = null, $data = [], &$sentCount = 0, &$failedCount = 0, $campaignId = null)
    {
        try {
            if (!$this->pushService) {
                $this->pushService = new PushNotify();
            }

            $responses = $this->pushService->notifyMultipleParallel(
                $tokens,
                $title,
                $body,
                $routeName,
                $data,
                50 // batch size
            );

            $sentTokens = [];
            $failedTokensByError = [];

            foreach ($responses as $response) {
                if (isset($response['status']) && $response['status'] === 'sent') {
                    $sentCount++;
                    if (!empty($response['token'])) {
                        $sentTokens[] = $response['token'];
                    }
                } else {
                    $failedCount++;
                    if (!empty($response['token'])) {
                        $errMsg = !empty($response['error']) ? $response['error'] : 'FCM delivery failed';
                        $failedTokensByError[$errMsg][] = $response['token'];
                    }
                }
            }

            // Update notification logs in DB
            if ($campaignId) {
                $db = \Config\Database::connect();
                if (!empty($sentTokens)) {
                    foreach (array_chunk($sentTokens, 500) as $chunk) {
                        $db->table('notification_logs')
                            ->where('campaign_id', $campaignId)
                            ->whereIn('device_token', $chunk)
                            ->update(['status' => 'sent', 'sent_at' => date('Y-m-d H:i:s')]);
                    }
                }
                if (!empty($failedTokensByError)) {
                    foreach ($failedTokensByError as $errMsg => $fTokens) {
                        foreach (array_chunk($fTokens, 500) as $chunk) {
                            $db->table('notification_logs')
                                ->where('campaign_id', $campaignId)
                                ->whereIn('device_token', $chunk)
                                ->update(['status' => 'failed', 'error_message' => $errMsg]);
                        }
                    }
                }
            }

            return $sentCount;
        } catch (\Exception $e) {
            log_message('error', 'Batch push error: ' . $e->getMessage());
            $failedCount += count($tokens);
            return $sentCount;
        }
    }

    /**
     * Schedule a campaign for later execution
     */
    public function scheduleCampaign($campaignId, $scheduledAt)
    {
        return $this->campaignModel->updateCampaign($campaignId, [
            'status'        => 'scheduled',
            'schedule_type' => 'scheduled',
            'scheduled_at'  => $scheduledAt,
        ]);
    }

    /**
     * Cancel a scheduled campaign
     */
    public function cancelCampaign($campaignId)
    {
        $campaign = $this->campaignModel->getCampaign($campaignId);

        if (!$campaign) {
            return ['success' => false, 'error' => 'Campaign not found'];
        }

        if (!in_array($campaign->status, ['draft', 'scheduled'])) {
            return ['success' => false, 'error' => 'Cannot cancel a campaign that is already ' . $campaign->status];
        }

        $this->campaignModel->updateCampaign($campaignId, [
            'status' => 'cancelled',
        ]);

        return ['success' => true];
    }

    /**
     * Process all due scheduled campaigns (called by cron)
     */
    public function processScheduledCampaigns()
    {
        $campaigns = $this->campaignModel->getScheduledCampaigns();
        $results = [];

        foreach ($campaigns as $campaign) {
            log_message('info', "Processing scheduled campaign #{$campaign->id}: {$campaign->name}");
            $result = $this->executeCampaign($campaign);
            $results[$campaign->id] = $result;
        }

        return $results;
    }

    /**
     * Get audience preview count for campaign builder
     */
    public function previewAudience($target, $segmentRules = null)
    {
        return $this->campaignModel->getAudienceCount($target, $segmentRules);
    }
}
