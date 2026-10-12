<?php

namespace App\Controllers\admin;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\NotificationModel;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class Notifications extends Controller
{
  protected $notificationModel;
  protected $webPush;

  public function __construct()
  {
    $this->notificationModel = new NotificationModel();
    $this->initializeWebPush();
  }

  private function initializeWebPush()
  {
    $auth = [
      'VAPID' => [
        'subject' => 'mailto:admin@petsfolio.in',
        'publicKey' => getenv('VAPID_PUBLIC_KEY') ?: 'BNHqXVP4Jik26W4ySvAnBFETvDjnWB_REtwZw7QkFBri-8dFcCNSOuTQzfed_9w2RFFdT3foOm9EsEn2W2w8plU',
        'privateKey' => getenv('VAPID_PRIVATE_KEY') ?: 'P5qPydlfRw2rI1-ld6Fnvyg6SxgN3IMTwxeYEU8YygI',
      ],
    ];

    $this->webPush = new WebPush($auth);
  }

  // Serve Service Worker
  public function serviceWorker()
  {
    $swPath = ROOTPATH . 'sw.js';

    if (file_exists($swPath)) {
      return $this->response->setContentType('application/javascript')
        ->setBody(file_get_contents($swPath));
    }

    return $this->response->setStatusCode(404);
  }

  public function subscribe()
  {
    // if (!$this->request->isAJAX()) {
    //   return $this->response->setStatusCode(405)->setJSON(['error' => 'Method not allowed']);
    // }

    $requestData = $this->request->getJSON(true);

    // Log the incoming data for debugging
    log_message('debug', 'Raw subscription data: ' . print_r($requestData, true));

    if (!isset($requestData['subscription'])) {
      return $this->response->setStatusCode(400)->setJSON([
        'success' => false,
        'error' => 'Missing subscription data'
      ]);
    }

    $subscription = $requestData['subscription'];

    try {
      $this->notificationModel->saveSubscription(
        $subscription,
        session()->get('user_id') // Adjust based on your auth system
      );

      return $this->response->setJSON([
        'success' => true,
        'message' => 'Subscription saved successfully'
      ]);
    } catch (\Exception $e) {
      log_message('error', 'Subscription save error: ' . $e->getMessage());
      return $this->response->setStatusCode(500)->setJSON([
        'success' => false,
        'error' => $e->getMessage(),
        'debug_data' => $subscription // Include for debugging
      ]);
    }
  }

  public function sendNotification()
  {
    $subscriptions = $this->notificationModel->getSubscriptions();
    $results = [];

    foreach ($subscriptions as $sub) {
      try {
        // Create subscription object for web-push library
        $subscription = Subscription::create([
          'endpoint' => $sub['endpoint'],
          'publicKey' => $sub['public_key'],
          'authToken' => $sub['auth_token'],
        ]);

        $payload = json_encode([
          'title' => $this->request->getVar('title') ?? 'Notification from PetsFolio',
          'body' => $this->request->getVar('body') ?? 'You have a new notification',
          'icon' => $this->request->getVar('icon') ?? '/icons/icon-192x192.png',
          'image' => $this->request->getVar('image'),
          'url' => $this->request->getVar('url'),
          'sound' => $this->request->getVar('sound') ?? true,
          'soundUrl' => $this->request->getVar('sound_url') ?? 'https://staging-api.petsfolio.in/admin/public/assets/sounds/bark.mp3',
          'tag' => $this->request->getVar('tag') ?? 'general',
          'requireInteraction' => $this->request->getVar('require_interaction') ?? false,
          'silent' => $this->request->getVar('silent') ?? false
        ]);

        $this->webPush->queueNotification($subscription, $payload);
      } catch (\Exception $e) {
        // Log error and remove invalid subscription
        log_message('error', 'Error queueing notification: ' . $e->getMessage());
        $this->notificationModel->deleteSubscription($sub['endpoint']);
        $results[] = "Failed to queue for {$sub['endpoint']}: {$e->getMessage()}";
      }
    }

    // Send all notifications
    foreach ($this->webPush->flush() as $report) {
      $endpoint = $report->getRequest()->getUri()->__toString();

      if ($report->isSuccess()) {
        $results[] = "Message sent successfully for subscription {$endpoint}";
      } else {
        $errorMsg = $report->getReason();
        $results[] = "Message failed to sent for subscription {$endpoint}: {$errorMsg}";

        // Remove failed subscription
        $this->notificationModel->deleteSubscription($endpoint);
      }
    }

    return $this->response->setJSON([
      'success' => true,
      'results' => $results
    ]);
  }

  public function sendChatNotification()
  {
    log_message('debug', '=== SEND CHAT NOTIFICATION CALLED ===');

    $input = $this->request->getJSON(true) ?? $this->request->getPost();
    log_message('debug', 'Raw input data: ' . print_r($input, true));

    $chatId = $input['chat_id'] ?? 'unknown_chat';
    $message = $input['message'] ?? 'New message received';
    $senderName = $input['sender_name'] ?? 'Customer';
    $senderType = $input['sender_type'] ?? 'client';
    $messageId = $input['message_id'] ?? uniqid();

    // FIX: Better title handling
    $notificationTitle = "💬 New Message";
    if ($senderName && $senderName !== 'Customer' && $senderName !== 'undefined') {
      $notificationTitle = "💬 You have message from {$senderName}";
    }

    $notificationBody = $message;
    if (strlen($notificationBody) > 100) {
      $notificationBody = substr($notificationBody, 0, 100) . '...';
    }

    log_message('debug', "Notification content - Title: {$notificationTitle}, Body: {$notificationBody}");

    // Get all subscriptions
    $subscriptions = $this->notificationModel->getSubscriptions();
    log_message('debug', 'Found ' . count($subscriptions) . ' subscriptions');

    if (empty($subscriptions)) {
      log_message('debug', 'No subscriptions found');
      return $this->response->setJSON([
        'success' => false,
        'error' => 'No subscriptions found'
      ]);
    }

    $sentCount = 0;

    // FIX: Simplified notification data without nested arrays
    $notificationData = [
      'title' => $notificationTitle,
      'body' => $notificationBody,
      'icon' => base_url('public/assets/images/icons/Petsfolio.png'),
      'badge' => base_url('public/assets/images/icons/Petsfolio.png'),
      'url' => base_url("admin/supportticket/{$chatId}"), // FIXED URL PATH
      'tag' => "chat-{$chatId}",
      'requireInteraction' => true,
      'chat_id' => $chatId,
      'message_id' => $messageId,
      'sender_type' => $senderType,
      'sender_name' => $senderName,
      'message' => $message,
      'timestamp' => time()
    ];

    log_message('debug', 'Final notification payload: ' . json_encode($notificationData));

    foreach ($subscriptions as $index => $sub) {
      try {
        $subscription = Subscription::create([
          'endpoint' => $sub['endpoint'],
          'publicKey' => $sub['public_key'],
          'authToken' => $sub['auth_token'],
        ]);

        $payload = json_encode($notificationData);
        log_message('debug', "Sending payload: " . $payload);

        $this->webPush->queueNotification($subscription, $payload);
        log_message('debug', "Queued for subscription {$index}");
      } catch (\Exception $e) {
        log_message('error', "Queue error: " . $e->getMessage());
      }
    }

    // Flush notifications
    $results = [];
    foreach ($this->webPush->flush() as $report) {
      $endpoint = $report->getRequest()->getUri()->__toString();

      if ($report->isSuccess()) {
        $sentCount++;
        log_message('debug', "✅ Sent to: {$endpoint}");
        $results[] = "✅ {$endpoint}";
      } else {
        $reason = $report->getReason();
        log_message('error', "❌ Failed: {$endpoint} - {$reason}");
        $results[] = "❌ {$endpoint}";

        // Remove invalid subscription
        $this->notificationModel->deleteSubscription($endpoint);
      }
    }

    log_message('debug', "=== NOTIFICATION SEND COMPLETE: {$sentCount}/" . count($subscriptions) . " ===");

    return $this->response->setJSON([
      'success' => true,
      'sent_count' => $sentCount,
      'total_subscriptions' => count($subscriptions),
      'results' => $results,
      'notification_data' => $notificationData
    ]);
  }

  private function getTicketInfo($ticketId)
  {

    try {
      $ticketModel = new \App\Models\TicketModel();
      return $ticketModel->getTicket($ticketId);
    } catch (\Exception $e) {
      log_message('error', 'Failed to get ticket info: ' . $e->getMessage());
      return null;
    }
  }
  public function testPush()
  {
    return view('admin/test_notification');
  }

  // Debug endpoint to check subscription format
  public function debugSubscription()
  {
    if (!$this->request->isAJAX()) {
      return $this->response->setStatusCode(405)->setJSON(['error' => 'Method not allowed']);
    }

    $requestData = $this->request->getJSON(true);
    $subscription = $requestData['subscription'] ?? null;

    return $this->response->setJSON([
      'success' => true,
      'subscription_type' => gettype($subscription),
      'subscription_keys_type' => gettype($subscription['keys'] ?? 'not set'),
      'subscription_structure' => $subscription,
      'keys_structure' => $subscription['keys'] ?? 'not set'
    ]);
  }
  public function verifySubscription()
  {
    $input = $this->request->getJSON(true);
    $subscription = $input['subscription'] ?? null;

    if (!$subscription) {
      return $this->response->setJSON(['valid' => false, 'error' => 'No subscription provided']);
    }

    try {
      // Check if subscription exists in database
      $endpoint = $subscription['endpoint'];
      $existing = $this->notificationModel->where('endpoint', $endpoint)->first();

      if ($existing) {
        return $this->response->setJSON(['valid' => true, 'message' => 'Subscription is valid']);
      } else {
        return $this->response->setJSON(['valid' => false, 'message' => 'Subscription not found in database']);
      }
    } catch (\Exception $e) {
      return $this->response->setJSON(['valid' => false, 'error' => $e->getMessage()]);
    }
  }

  /**
   * Purge old notifications via API / admin request.
   */
  public function purgeOld()
  {
    $days = (int)($this->request->getVar('days') ?? 30);
    $table = (string)($this->request->getVar('table') ?? 'notifications');
    $readOnly = (bool)($this->request->getVar('read_only') ?? false);
    $dryRun = (bool)($this->request->getVar('dry_run') ?? false);

    try {
      $purgeService = new \App\Libraries\NotificationPurgeService();
      $result = $purgeService->purge([
        'days'      => $days,
        'table'     => $table,
        'read_only' => $readOnly,
        'dry_run'   => $dryRun
      ]);

      return $this->response->setJSON($result);
    } catch (\Exception $e) {
      log_message('error', 'Purge error: ' . $e->getMessage());
      return $this->response->setStatusCode(500)->setJSON([
        'success' => false,
        'error'   => $e->getMessage()
      ]);
    }
  }
}
