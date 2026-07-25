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

  public function sendUserAgreementEmail($user_id)
  {

    $db       = \Config\Database::connect();
    $user = $this->userModel->getUserById($user_id);

    if (!$user || empty($user->email)) {
      return false;
    }
   

    $currentDate = date('d-M-Y');
    $data        = [
      'user'        => $user,
      'currentDate' => $currentDate,
    ];
    try {
      $htmlContent = view('admin/user/user_agreement', $data);
    } catch (\Exception $e) {
      log_message('error', 'Could not find user_agreement view: ' . $e->getMessage());
      return false;
    }
    if (!class_exists('\Dompdf\Dompdf')) {
      log_message('error', 'Dompdf library is not installed. Please run: composer require dompdf/dompdf');
      return false;
    }

    $options = new \Dompdf\Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new \Dompdf\Dompdf($options);
    $dompdf->loadHtml($htmlContent);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $pdfOutput = $dompdf->output();
    if (empty($pdfOutput)) {
      log_message('error', 'Dompdf failed to generate PDF output.');
      return false;
    }

    $email = \Config\Services::email();
    $email->setTo($user->email);

    $fromEmail = getenv('emailFrom') ?: 'info.petsfolio@gmail.com';
    $fromName  = getenv('emailDomain') ?: 'Petsfolio';

    $email->setFrom($fromEmail, $fromName);
    $email->setSubject('Petsfolio Terms & Conditions');

    // Attach the raw PDF buffer directly
    $email->attach($pdfOutput, 'attachment', 'Petsfolio_User_Agreement.pdf', 'application/pdf');

    // Simple email body
    $messageBody = 'Dear ' . $user->name . ',<br><br>';
    $messageBody .= 'Please find attached your Petsfolio User Agreement.<br><br>';
    $messageBody .= 'Best regards,<br>The Petsfolio Team';

    $email->setMessage($messageBody);
    $email->setMailType('html');

    $result = $email->send();

    return $result;
  }

  public function sendUserAgreementAjax()
  {
    $client_id = $this->request->getPost('id');
    $result = $this->sendUserAgreementEmail($client_id);
    if ($result) {
      return $this->response->setJSON(['status' => true, 'message' => 'User agreement sent successfully.']);
    }
    return $this->response->setJSON(['status' => false, 'message' => 'Failed to send user agreement.']);
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
