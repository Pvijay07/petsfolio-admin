<?php

namespace App\Libraries\Notification;

use App\Models\NotificationTemplateModel;
use App\Models\NotificationLogModel;
use App\Libraries\PushNotify;

/**
 * NotificationEngine
 * Main orchestrator for sending notifications via templates
 */
class NotificationEngine
{
    protected $templateModel;
    protected $logModel;
    protected $parser;
    protected $pushService;

    public function __construct()
    {
        $this->templateModel = new NotificationTemplateModel();
        $this->logModel      = new NotificationLogModel();
        $this->parser        = new TemplateParser();
    }

    /**
     * Send a notification based on an event key
     *
     * @param string $event     Event key (e.g., 'booking.created')
     * @param object $recipient Recipient object with id, name, device_token, user_type
     * @param array  $data      Variables to replace in template
     * @return array Result of the send operation
     */
    public function send($event, $recipient, $data = [])
    {
        // Load template for this event
        $template = $this->templateModel->getByEventKey($event);

        if (!$template) {
            log_message('warning', "NotificationEngine: No template found for event '{$event}'");
            return ['success' => false, 'error' => 'Template not found'];
        }

        // Parse the template
        $title = $this->parser->parse($template->title, $data);
        $body  = $this->parser->parse($template->body ?: $template->content, $data);

        // Determine channel
        $channel = $template->channel ?: 'push';

        // Send based on channel
        $result = $this->dispatch($channel, $recipient, $title, $body, $data);

        // Log the notification
        $this->logModel->createLog([
            'user_id'      => $recipient->user_type === 'user' ? $recipient->id : null,
            'provider_id'  => $recipient->user_type === 'provider' ? $recipient->id : null,
            'user_type'    => $recipient->user_type ?? 'user',
            'template_id'  => $template->id,
            'channel'      => $channel === 'all' ? 'push' : $channel,
            'title'        => $title,
            'body'         => $body,
            'status'       => $result['success'] ? 'sent' : 'failed',
            'error_message' => $result['error'] ?? null,
            'device_token' => $recipient->device_token ?? null,
            'sent_at'      => $result['success'] ? date('Y-m-d H:i:s') : null,
        ]);

        return $result;
    }

    /**
     * Send a notification directly (without template)
     *
     * @param string $channel   Channel to send via
     * @param object $recipient Recipient object
     * @param string $title     Notification title
     * @param string $body      Notification body
     * @param array  $data      Additional data payload
     * @return array Result
     */
    public function sendDirect($channel, $recipient, $title, $body, $data = [])
    {
        $result = $this->dispatch($channel, $recipient, $title, $body, $data);

        $this->logModel->createLog([
            'user_id'      => ($recipient->user_type ?? 'user') === 'user' ? $recipient->id : null,
            'provider_id'  => ($recipient->user_type ?? 'provider') === 'provider' ? $recipient->id : null,
            'user_type'    => $recipient->user_type ?? 'user',
            'channel'      => $channel,
            'title'        => $title,
            'body'         => $body,
            'status'       => $result['success'] ? 'sent' : 'failed',
            'error_message' => $result['error'] ?? null,
            'device_token' => $recipient->device_token ?? null,
            'sent_at'      => $result['success'] ? date('Y-m-d H:i:s') : null,
        ]);

        return $result;
    }

    /**
     * Dispatch notification to the appropriate channel
     */
    protected function dispatch($channel, $recipient, $title, $body, $data = [])
    {
        switch ($channel) {
            case 'push':
                return $this->sendPush($recipient, $title, $body, $data);

            case 'sms':
                return $this->sendSms($recipient, $body);

            case 'email':
                return $this->sendEmail($recipient, $title, $body);

            case 'whatsapp':
                return $this->sendWhatsApp($recipient, $body);

            case 'all':
                // Send via all available channels
                $results = [];
                $results['push'] = $this->sendPush($recipient, $title, $body, $data);
                // SMS and others are optional
                return $results['push']; // Return push result as primary

            default:
                return ['success' => false, 'error' => 'Unknown channel: ' . $channel];
        }
    }

    /**
     * Send push notification via Firebase
     */
    protected function sendPush($recipient, $title, $body, $data = [])
    {
        $token = $recipient->device_token ?? null;

        if (empty($token) || $token === 'default_token') {
            return ['success' => false, 'error' => 'No valid device token'];
        }

        try {
            if (!$this->pushService) {
                $this->pushService = new PushNotify();
            }

            $result = $this->pushService->notify($token, $title, $body);

            if (isset($result['status_code']) && $result['status_code'] === 200) {
                return ['success' => true];
            }

            return [
                'success' => false,
                'error'   => $result['message'] ?? 'Push send failed with status ' . ($result['status_code'] ?? 'unknown'),
            ];
        } catch (\Exception $e) {
            log_message('error', 'NotificationEngine Push Error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send SMS notification (stub — integrate with SMS provider)
     */
    protected function sendSms($recipient, $message)
    {
        $phone = $recipient->phone ?? null;

        if (empty($phone)) {
            return ['success' => false, 'error' => 'No phone number'];
        }

        // TODO: Integrate with SMS provider (MSG91, Twilio, etc.)
        log_message('info', "SMS stub: Sending to {$phone}: {$message}");
        return ['success' => true, 'stub' => true];
    }

    /**
     * Send Email notification (stub — integrate with email service)
     */
    protected function sendEmail($recipient, $subject, $body)
    {
        $email = $recipient->email ?? null;

        if (empty($email)) {
            return ['success' => false, 'error' => 'No email address'];
        }

        // TODO: Integrate with email service (CI4 Email, SendGrid, etc.)
        log_message('info', "Email stub: Sending to {$email}: {$subject}");
        return ['success' => true, 'stub' => true];
    }

    /**
     * Send WhatsApp notification (stub — integrate with WhatsApp Business API)
     */
    protected function sendWhatsApp($recipient, $message)
    {
        $phone = $recipient->phone ?? null;

        if (empty($phone)) {
            return ['success' => false, 'error' => 'No phone number'];
        }

        // TODO: Integrate with WhatsApp Business API (Meta API)
        log_message('info', "WhatsApp stub: Sending to {$phone}: {$message}");
        return ['success' => true, 'stub' => true];
    }
}
