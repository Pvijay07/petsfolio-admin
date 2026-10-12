<?php

namespace App\Controllers\admin;

use App\Libraries\CashfreePayoutService;
use CodeIgniter\Controller;

class PaymentController extends Controller
{
  protected $payoutService;
  protected $db;

  public function __construct()
  {
    $this->payoutService = new CashfreePayoutService();
    $this->db            = \Config\Database::connect();
    helper(['form', 'url']);
  }

  /**
   * Display payment management page
   */
  public function index()
  {
    $builder = $this->db->table('wallet_withdrawals ww');

    $payments = $builder->select('ww.*, u.name, u.phone, 
                                      bd.account_holder_name, bd.account_number, 
                                      bd.ifsc_code, bd.bank_name, bd.type, bd.upi_id')
      ->join('service_providers u', 'u.id = ww.user_id', 'left')
      ->join('sp_bank_details bd', 'bd.id = ww.bank_id', 'left')
      ->orderBy('ww.requested_at', 'DESC')
      ->get()
      ->getResult();

    $data['payments'] = $payments;

    return view('admin/payment_management', $data);
  }

  /**
   * Process single payout - called when admin approves
   */
  public function processPayout()
  {
    if (!$this->request->isAJAX()) {
      return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
    }

    $withdrawalId = $this->request->getPost('id');

    if (!$withdrawalId) {
      return $this->response->setJSON([
        'status'  => 'error',
        'message' => 'Withdrawal ID is required'
      ]);
    }

    try {
      $this->db->transStart();

      // Update status to in_progress
      $this->db->table('wallet_withdrawals')
        ->where('id', $withdrawalId)
        ->update([
          'status'     => 'in_progress',
          'updated_at' => date('Y-m-d H:i:s')
        ]);

      // Process the payout through Cashfree
      $result = $this->payoutService->processWithdrawalPayout($withdrawalId);

      if ($result['success']) {
        $this->db->transComplete();

        // Send push & in-app notification to provider
        $withdrawal = $this->db->table('wallet_withdrawals')->where('id', $withdrawalId)->get()->getRowArray();
        if ($withdrawal) {
          $this->payoutService->sendProviderPayoutNotification(
            $withdrawal['user_id'],
            $withdrawal['amount'],
            $result['utr'] ?? ($result['transfer_id'] ?? ('PAYOUT-' . $withdrawalId))
          );
        }

        return $this->response->setJSON([
          'status'      => 'success',
          'message'     => 'Payout initiated successfully',
          'transfer_id' => $result['transfer_id'] ?? null,
          'utr'         => $result['utr'] ?? null
        ]);
      } else {
        // Revert status if payout failed
        $this->db->table('wallet_withdrawals')
          ->where('id', $withdrawalId)
          ->update([
            'status'     => 'pending',
            'updated_at' => date('Y-m-d H:i:s')
          ]);

        $this->db->transComplete();

        return $this->response->setJSON([
          'status'  => 'error',
          'message' => $result['error'] ?? 'Payout initiation failed'
        ]);
      }
    } catch (\Exception $e) {
      $this->db->transRollback();
      log_message('error', 'Payout processing error: ' . $e->getMessage());

      return $this->response->setJSON([
        'status'  => 'error',
        'message' => 'An error occurred while processing payout'
      ]);
    }
  }

  /**
   * Update payment status manually
   */
  public function updateStatus()
  {
    if (!$this->request->isAJAX()) {
      return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
    }

    $status     = $this->request->getPost('status');
    $id         = $this->request->getPost('id');
    $providerId = $this->request->getPost('provider_id');
    $amount     = $this->request->getPost('amount');

    if (!$id || !$status) {
      return $this->response->setJSON([
        'status'  => 'error',
        'message' => 'Missing required parameters'
      ]);
    }

    try {
      $this->db->transStart();

      // If status is "completed", process through Cashfree
      if ($status === 'completed') {
        $result = $this->payoutService->processWithdrawalPayout($id);

        if (!$result['success']) {
          $this->db->transRollback();
          return $this->response->setJSON([
            'status'  => 'error',
            'message' => $result['error'] ?? 'Payout processing failed'
          ]);
        }
      } else {
        // For other statuses, just update
        $updateData = [
          'status'     => $status,
          'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($status === 'rejected') {
          $updateData['processed_at'] = date('Y-m-d H:i:s');

          // Return amount to user's wallet if rejected
          $this->db->table('users')
            ->where('id', $providerId)
            ->set('wallet_balance', 'wallet_balance + ' . $amount, false)
            ->update();
        }

        $this->db->table('wallet_withdrawals')
          ->where('id', $id)
          ->update($updateData);
      }

      $this->db->transComplete();

      if ($this->db->transStatus() === false) {
        return $this->response->setJSON([
          'status'  => 'error',
          'message' => 'Transaction failed'
        ]);
      }

      if ($status === 'completed' && $providerId && $amount) {
        $this->payoutService->sendProviderPayoutNotification($providerId, $amount, 'MANUAL-PAYOUT-' . $id);
      }

      return $this->response->setJSON([
        'status'  => 'success',
        'message' => 'Status updated successfully'
      ]);
    } catch (\Exception $e) {
      log_message('error', 'Status update error: ' . $e->getMessage());

      return $this->response->setJSON([
        'status'  => 'error',
        'message' => 'An error occurred while updating status'
      ]);
    }
  }

  /**
   * Upload receipt
   */
  public function uploadReceipt()
  {
    if (!$this->request->isAJAX()) {
      return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
    }

    $id   = $this->request->getPost('id');
    $file = $this->request->getFile('file');

    if (!$file || !$file->isValid()) {
      return $this->response->setJSON([
        'status'  => 'error',
        'message' => 'Invalid file upload'
      ]);
    }

    try {
      // Validate file type
      $validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
      if (!in_array($file->getMimeType(), $validTypes)) {
        return $this->response->setJSON([
          'status'  => 'error',
          'message' => 'Invalid file type. Only JPG, PNG, and PDF allowed'
        ]);
      }

      // Generate unique filename
      $newName = 'receipt_' . $id . '_' . time() . '.' . $file->getExtension();

      // Move file to uploads directory
      $uploadPath = FCPATH . 'public/uploads/receipts/';
      if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0755, true);
      }

      $file->move($uploadPath, $newName);

      // Update database
      $this->db->table('wallet_withdrawals')
        ->where('id', $id)
        ->update([
          'receipt'    => $newName,
          'status'     => 'completed',
          'updated_at' => date('Y-m-d H:i:s')
        ]);

      // Send push & in-app notification to provider
      $withdrawal = $this->db->table('wallet_withdrawals')->where('id', $id)->get()->getRowArray();
      if ($withdrawal) {
        $this->payoutService->sendProviderPayoutNotification(
          $withdrawal['user_id'],
          $withdrawal['amount'],
          $newName
        );
      }

      return $this->response->setJSON([
        'status'   => 'success',
        'message'  => 'Receipt uploaded successfully',
        'filename' => $newName
      ]);
    } catch (\Exception $e) {
      log_message('error', 'Receipt upload error: ' . $e->getMessage());

      return $this->response->setJSON([
        'status'  => 'error',
        'message' => 'Failed to upload receipt'
      ]);
    }
  }

  /**
   * Bulk process payouts
   */
  public function bulkProcess()
  {
    if (!$this->request->isAJAX()) {
      return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
    }

    $withdrawalIds = $this->request->getPost('withdrawal_ids');

    if (empty($withdrawalIds) || !is_array($withdrawalIds)) {
      return $this->response->setJSON([
        'status'  => 'error',
        'message' => 'No withdrawals selected'
      ]);
    }

    try {
      $result = $this->payoutService->bulkProcessWithdrawals($withdrawalIds);

      return $this->response->setJSON([
        'status'  => 'success',
        'message' => "Processed {$result['successful']} of {$result['total']} payouts",
        'data'    => $result
      ]);
    } catch (\Exception $e) {
      log_message('error', 'Bulk processing error: ' . $e->getMessage());

      return $this->response->setJSON([
        'status'  => 'error',
        'message' => 'Bulk processing failed'
      ]);
    }
  }

  /**
   * Check payout status
   */
  public function checkStatus()
  {
    if (!$this->request->isAJAX()) {
      return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
    }

    $withdrawalId = $this->request->getPost('id');

    try {
      $this->db->transStart();

      $withdrawal = $this->db->table('wallet_withdrawals')
        ->where('id', $withdrawalId)
        ->get()
        ->getRowArray();
      if (!$withdrawal || empty($withdrawal['cf_reference_id'])) {
        $this->db->transComplete();
        return $this->response->setJSON([
          'status'  => 'error',
          'message' => 'No payout reference found'
        ]);
      }

      $result = $this->payoutService->getTransferStatus($withdrawal['cf_reference_id']);
      if ($result['success']) {

        // Update local status based on Cashfree response
        $this->payoutService->updateWithdrawalFromPayout(
          $withdrawal['cf_reference_id'],
          $result['data'],
          $withdrawal['user_id']
        );

        $this->db->transComplete();

        return $this->response->setJSON([
          'status'        => 'success',
          'status_updated' => true,
          'payout_status' => $result['data']['status'] ?? 'UNKNOWN',
          'utr'           => $result['data']['utr'] ?? null
        ]);
      }

      $this->db->transComplete();

      return $this->response->setJSON([
        'status'  => 'error',
        'message' => $result['error'] ?? 'Failed to check status'
      ]);
    } catch (\Exception $e) {
      $this->db->transRollback();
      log_message('error', 'Status check error: ' . $e->getMessage());

      return $this->response->setJSON([
        'status'  => 'error',
        'message' => 'Failed to check payout status'
      ]);
    }
  }

  /**
   * Get payment statistics
   */
  public function getStats()
  {
    $period = $this->request->getGet('period') ?? 'month';

    $stats = $this->payoutService->getWithdrawalStats($period);

    return $this->response->setJSON([
      'status' => 'success',
      'data'   => $stats
    ]);
  }





  /**
   * Main Webhook Endpoint
   */
  public function handleWebhook()
  {
    // Get raw request
    $rawBody = file_get_contents('php://input');
    $headers = $this->getAllHeaders();

    // Log incoming request
    log_message('info', 'Webhook received: ' . json_encode([
      'headers' => array_intersect_key($headers, array_flip([
        'x-webhook-signature',
        'x-webhook-timestamp',
        'x-webhook-version'
      ])),
      'body_length' => strlen($rawBody)
    ]));

    // Verify signature
    $signature = $headers['x-webhook-signature'] ?? $headers['X-Webhook-Signature'] ?? '';
    $timestamp = $headers['x-webhook-timestamp'] ?? $headers['X-Webhook-Timestamp'] ?? '';

    if (!$this->payoutService->verifyWebhookSignatureV2($signature, $rawBody, $timestamp)) {
      return $this->response->setStatusCode(401)->setJSON([
        'success' => false,
        'error' => 'Invalid signature',
        'timestamp' => date('Y-m-d H:i:s')
      ]);
    }

    // Parse and process
    $webhookData = json_decode($rawBody, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
      return $this->response->setStatusCode(400)->setJSON([
        'success' => false,
        'error' => 'Invalid JSON: ' . json_last_error_msg()
      ]);
    }

    // Process webhook
    $result = $this->payoutService->processPayoutWebhook($webhookData, $headers);

    // Return appropriate response
    if ($result['success']) {
      return $this->response->setStatusCode(200)->setJSON([
        'success' => true,
        'message' => 'Webhook processed successfully',
        'event_id' => $result['event_id'] ?? null
      ]);
    } else {
      return $this->response->setStatusCode(200)->setJSON([
        'success' => false,
        'error' => $result['error'] ?? 'Processing failed',
        'event_id' => $result['event_id'] ?? null
      ]);
    }
  }

  /**
   * Retry Failed Webhooks (Admin Endpoint)
   */
  public function retryFailedWebhooks()
  {
    // Add authentication/authorization check here

    $maxAge = $this->request->getGet('max_age') ?? 24;
    $limit = $this->request->getGet('limit') ?? 10;

    $result = $this->payoutService->retryFailedWebhooks($maxAge, $limit);

    return $this->response->setJSON([
      'success' => true,
      'data' => $result
    ]);
  }

  /**
   * Webhook Statistics (Admin Endpoint)
   */
  public function webhookStats()
  {
    // Add authentication/authorization check here

    $period = $this->request->getGet('period') ?? 'today';
    $stats = $this->payoutService->getWebhookStats($period);

    return $this->response->setJSON([
      'success' => true,
      'period' => $period,
      'data' => $stats
    ]);
  }

  /**
   * Test Webhook Endpoint
   */
  public function testWebhook()
  {
    // Simulate a test webhook
    $testData = [
      'event_type' => 'TRANSFER_STATUS_UPDATE',
      'event_id' => 'test_' . uniqid(),
      'timestamp' => time(),
      'data' => [
        'transfer_id' => 'TRF_TEST_' . time(),
        'transfer_status' => 'SUCCESS',
        'transfer_utr' => 'TEST123456789',
        'transfer_amount' => 100.00,
        'transfer_currency' => 'INR',
        'transfer_mode' => 'banktransfer'
      ]
    ];

    $result = $this->payoutService->processPayoutWebhook($testData);

    return $this->response->setJSON([
      'success' => $result['success'],
      'message' => 'Test webhook processed',
      'result' => $result
    ]);
  }

  private function getAllHeaders(): array
  {
    $headers = [];
    foreach ($_SERVER as $key => $value) {
      if (substr($key, 0, 5) === 'HTTP_') {
        $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
        $headers[$header] = $value;
      }
    }
    return $headers;
  }
}
