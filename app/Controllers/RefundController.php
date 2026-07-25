<?php

namespace App\Controllers;

use App\Models\Booking;
use App\Models\RefundModel;
use App\Models\UserModel;
use App\Models\WalletModel;
use CodeIgniter\API\ResponseTrait;

class RefundController extends BaseController
{
  use ResponseTrait;

  public function clientWallets()
  {
    $walletModel = new Booking();
    $refundModel = new RefundModel();

    // Get client wallets with user info
    $wallets = $walletModel->getClientWallets();

    // Get refund status for each wallet
    foreach ($wallets as $wallet) {
      $pendingRefund = $refundModel->where('user_id', $wallet->user_id)
        ->where('status', 'pending')
        ->first();

      $completedRefund = $refundModel->where('user_id', $wallet->user_id)
        ->where('status', 'completed')
        ->orderBy('updated_at', 'DESC')
        ->first();

      if ($pendingRefund) {
        $wallet->refund_status    = 'pending';
        $wallet->requested_amount = $pendingRefund['amount'];
      } elseif ($completedRefund) {
        $wallet->refund_status    = 'completed';
        $wallet->requested_amount = $completedRefund['amount'];
      } else {
        $wallet->refund_status    = null;
        $wallet->requested_amount = null;
      }
    }

    $data['wallets'] = $wallets;

    // Get pending refunds
    $data['pending_refunds'] = $refundModel->pendingRefunds();

    // Get completed refunds
    $data['completed_refunds'] = $refundModel->completedRefunds();

    return $this->loadView('admin/user/client-wallets', $data);
  }

  public function requestRefund()
  {
    $refundModel = new RefundModel();
    $walletModel = new Booking();

    $userId      = $this->request->getPost('user_id');
    $amount      = $this->request->getPost('amount');
    $reason      = $this->request->getPost('reason');
    $requestedBy = session()->get('user')->id;

    // Validation
    if (!$reason || !$amount) {
      return $this->respond([
        'status'  => false,
        'message' => 'Reason and amount are required.'
      ]);
    }

    if ($amount <= 0) {
      return $this->respond([
        'status'  => false,
        'message' => 'Amount must be greater than 0.'
      ]);
    }

    // Check wallet balance
    $wallet = $walletModel->getUserWallet($userId);
    if (!$wallet || $amount > $wallet->amount) {
      return $this->respond([
        'status'  => false,
        'message' => 'Amount exceeds wallet balance.'
      ]);
    }

    // Check for existing pending refund
    $existing = $refundModel->where('user_id', $userId)
      ->where('status', 'pending')
      ->first();

    if ($existing) {
      return $this->respond([
        'status'  => false,
        'message' => 'A pending refund request already exists for this client.'
      ]);
    }

    // Handle file upload
    $file     = $this->request->getFile('attachment');
    $fileName = null;
    if ($file && $file->isValid()) {
      // Validate file size and type
      if ($file->getSize() > 5242880) { // 5MB
        return $this->respond([
          'status'  => false,
          'message' => 'File size must be less than 5MB.'
        ]);
      }

      $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
      if (!in_array($file->getMimeType(), $allowedTypes)) {
        return $this->respond([
          'status'  => false,
          'message' => 'Only JPG, PNG, PDF, and DOC files are allowed.'
        ]);
      }

      $fileName = $file->getRandomName();
      $file->move(ROOTPATH . 'public/uploads/refund_attachments/', $fileName);
    }

    // Create refund request
    $refundData = [
      'user_id'      => $userId,
      'amount'       => $amount,
      'reason'       => $reason,
      'attachment'   => $fileName, // Changed from 'receipt' to 'attachment'
      'requested_by' => $requestedBy,
      'status'       => 'pending',
      'created_at'   => date('Y-m-d H:i:s'),
      'updated_at'   => date('Y-m-d H:i:s')
    ];

    $refundModel->insert($refundData);

    return $this->respond([
      'status'  => true,
      'message' => 'Refund request submitted successfully and is waiting for approval.'
    ]);
  }

public function refundProcess()
  {
    $request = \Config\Services::request();

    if (!$request->isAJAX()) {
      return $this->respond(['status' => 'error', 'message' => 'Invalid request'], 400);
    }

    $refundId = $request->getPost('id') ?? $request->getPost('refund_id');

    if (!$refundId) {
      return $this->respond([
        'status'  => 'error',
        'message' => 'Refund ID is required'
      ], 400);
    }

    try {
      $payoutService = new \App\Libraries\CashfreePayoutService();

      // Update status to pending before processing
      $db = \Config\Database::connect();
      $db->table('user_refunds')
        ->where('id', $refundId)
        ->update([
          'status'     => 'pending',
          'updated_at' => date('Y-m-d H:i:s')
        ]);

      // Process the refund through Cashfree
      $result = $payoutService->processUserRefundPayout((int)$refundId);

      if ($result['success']) {
        return $this->respond([
          'status'      => 'success',
          'message'     => 'Refund payout initiated successfully',
          'transfer_id' => $result['transfer_id'] ?? null
        ]);
      } else {
        return $this->respond([
          'status'  => 'error',
          'message' => $result['error'] ?? 'Refund payout initiation failed'
        ], 500);
      }
    } catch (\Exception $e) {
      log_message('error', 'Refund processing error: ' . $e->getMessage());

      return $this->respond([
        'status'  => 'error',
        'message' => 'An error occurred while processing refund: ' . $e->getMessage()
      ], 500);
    }
  }  public function processRefund()
  {
    $input = $this->request->getPost();
    $db    = \Config\Database::connect();

    $data   = [
      'user_id'             => $input['user_id'],
      'amount'              => $input['amount'],
      'account_holder_name' => $input['account_holder_name'],
      'account_number'      => $input['account_number'],
      'bank_name'           => $input['bank_name'],
      'ifsc_code'           => $input['ifsc_code'],
      'city'                => $input['city'] ?? '',
      'branch'              => $input['branch'] ?? '',
      'status'              => 'pending',
      'processed_by'        => session()->get('user')->id,
      'requested_by'        => session()->get('user')->id,
      'created_at'          => date('Y-m-d H:i:s')
    ];
    $exists = $db->table('user_refunds')
      ->where('user_id', $input['user_id'])
      ->whereIn('status', ['requested', 'pending'])
      ->countAllResults();

    if ($exists > 0) {
      return $this->response->setJSON(['status' => 'error', 'message' => 'Refund record already exists for this user.']);
    }
    $inserted = $db->table('user_refunds')->insert($data);

    if ($inserted) {
      return $this->response->setJSON(['status' => 'ok', 'message' => 'Refund record created successfully.']);
    } else {
      return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to create refund record.']);
    }
  }
}
