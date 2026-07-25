<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;

class UserController extends BaseController
{
  protected $userModel;
  public function __construct()
  {
    $this->userModel = model('User');
    helper('Common_helper');
  }
  public function index()
  {
    $status          = $this->request->getGet('status');
    $data['clients'] = $this->userModel->getAll($status);
    $data['title']   = '';
    $data['page']    = 'client';
    if (!empty($data['clients'])) {
      foreach ($data['clients'] as $clients) {
        if (!empty($clients->pets)) {
          array_walk($clients->pets, function ($pet) {
            $pet->age = ageCalculator($pet->dob);
          });
        }
      }
    }

    return $this->loadView('admin/user/index', $data);
  }
  public function refundAmount()
  {
    $id           = $this->request->getPost('id');
    $refundAmount = $this->request->getPost('refundAmount');
    $receipt      = $this->request->getFile('receipt');

    if (!$receipt->isValid()) {
      return $this->response->setJSON([
        'status'  => false,
        'message' => $receipt->getErrorString()
      ]);
    }

    $newName = $receipt->getRandomName();
    if ($receipt->move(ROOTPATH . 'public/uploads/receipts/', $newName)) {
      // Update wallet & add refund history
      $status = $this->userModel->refundAmount($id, $refundAmount);
      $this->userModel->addRefundHistory($id, $refundAmount, $newName);
      $balanceAfter = $this->userModel->getServiceWalletSum($id)['service_amount'];
      $refundAmount = $this->userModel->getWithdrawalWalletSum($id);
      $this->userModel->logTransaction(
        $id,
        0,
        0,
        'debit',
        $refundAmount,
        $balanceAfter ?? 0,
        $refundAmount ?? 0,
        'withdrawal_amount',
        "Withdrawal amount",
        'user_wallet_histories'
      );
      return $this->response->setJSON([
        'status'  => $status,
        'message' => 'Refund processed successfully.'
      ]);
    }

    return $this->response->setJSON([
      'status'  => false,
      'message' => 'Failed to upload receipt.'
    ]);
  }
  public function clientList()
  {
    $start = $this->request->getGet('start_date');
    $end   = $this->request->getGet('end_date');

    $applyFilter                = ($start && $end);
    $data['start']              = $start;
    $data['end']                = $end;
    $data['downloaded_clients'] = $this->userModel->getDownloadedOnly($start, $end, $applyFilter);
    $data['posted_clients']     = $this->userModel->getPostedButNotAssigned($start, $end, $applyFilter);
    $data['assigned_clients']   = $this->userModel->getAssignedClients($start, $end, $applyFilter);

    return $this->loadView('admin/user/client_list', $data);
  }


  public function saveClientComment()
  {
    $client_id = $this->request->getPost('client_id');
    $comment   = $this->request->getPost('comment');

    $this->db = \Config\Database::connect();

    $this->db->table('users')->where('id', $client_id)->update([
      'sales_comment' => $comment
    ]);

    return $this->response->setJSON(['status' => 'success']);
  }
}
