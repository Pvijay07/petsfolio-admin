<?php

namespace App\Controllers\Insurance;

use App\Controllers\BaseController;
use App\Models\Booking;
use App\Models\InsurancePolicy;

class InsuranceManagement extends BaseController
{
  protected $model;
  public function __construct()
  {
    $this->model = new InsurancePolicy();
  }

  public function index()
  {
    $data['enquiries'] = $this->model->getEnquiries();
    return $this->loadView('admin/insurance/enquiries', $data);
  }
  public function quotations()
  {
    $data['quotations'] = $this->model->getQuotations();
    return $this->loadView('admin/insurance/quotations', $data);
  }
  public function active()
  {
    $data['insurances'] = $this->model->getActiveInsurances();

    return $this->loadView('admin/insurance/active', $data);
  }
  public function expiring()
  {
    $data['insurances'] = $this->model->getExpiringInsurances();

    return $this->loadView('admin/insurance/expiring', $data);
  }
  public function claims()
  {
    $data['claims'] = $this->model->getClaims();

    return $this->loadView('admin/insurance/claims', $data);
  }

  public function sendQuotation()
  {
    $json = $this->request->getPost();

    if ($this->model->quotationExists($json['policy_id'])) {
      return $this->response->setJSON([
        'status'  => false,
        'message' => 'Quotation already created for this policy'
      ]);
    }

    $data = [
      'policy_id' => $json['policy_id'],
      'amount'    => $json['amount'],
      'subject'   => $json['subject'],
      'message'   => $json['message']
    ];

    $quote = $this->model->createQuotation($data);
    if ($quote) {
      return $this->response->setJSON(['status' => true, 'message' => 'Quote Sent Successfully', 'emailData' => (array) $json]);
    }

    return $this->response->setJSON(['status' => false, 'message' => 'Something went wrong ,please try again']);
  }

  public function updateClaim()
  {
    $claimId        = $this->request->getPost('claimId');
    $claimAmount    = $this->request->getPost('claimAmount');
    $status         = $this->request->getPost('status');
    $settlementDate = $this->request->getPost('settlementDate');
    $remarks        = $this->request->getPost('remarks');

    // Update database here
    $data = [
      'amount'          => $claimAmount,
      'status'          => $status,
      'settlement_date' => $settlementDate ?: null,
      'remarks'         => $remarks,
      'updated_at'      => date('Y-m-d H:i:s')
    ];

    $result = $this->model->updateClaim($claimId, $data);

    if ($result) {
      return $this->response->setJSON([
        'status'  => true,
        'message' => 'Claim updated successfully'
      ]);
    } else {
      return $this->response->setJSON([
        'status'  => false,
        'message' => 'Failed to update claim'
      ]);
    }
  }
  public function sendAjaxMail()
  {
    $email   = $this->request->getPost('email');
    $subject = $this->request->getPost('subject');
    $message = $this->request->getPost('message');

    $emailSender = new SmsEmailLibrary();

    $emailSender->sendEmail($email, $subject, $message);

    return $this->response->setJSON(['status' => true, 'message' => 'Mail Sent Successfully']);
  }


  public function searchClient()
  {
    if (!$this->request->isAJAX()) {
      return $this->response->setStatusCode(405)->setJSON(['success' => false, 'message' => 'Method not allowed']);
    }

    $phone = $this->request->getJSON()->phone;

    $clientModel = new \App\Models\User();
    $client      = $clientModel->getClient($phone);

    if ($client) {
      return $this->response->setJSON([
        'success' => true,
        'client'  => [
          'id'        => $client->id,
          'full_name' => $client->name,
          'email'     => $client->email,
          'phone'     => $client->phone
        ]
      ]);
    } else {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'Client not found with this phone number'
      ]);
    }
  }

  public function getClientPets()
  {
    if (!$this->request->isAJAX()) {
      return $this->response->setStatusCode(405)->setJSON(['success' => false, 'message' => 'Method not allowed']);
    }

    $clientId = $this->request->getJSON()->client_id;

    $petModel = new \App\Models\User();
    $pets     = $petModel->getUserPets($clientId);

    if ($pets) {
      return $this->response->setJSON([
        'success' => true,
        'pets'    => $pets
      ]);
    } else {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'No pets found for this client'
      ]);
    }
  }

  public function addPolicy()
  {
    if (!$this->request->isAJAX()) {
      return $this->response->setStatusCode(405)->setJSON([
        'status'  => false,
        'message' => 'Method not allowed'
      ]);
    }

    $json = $this->request->getJSON();

    $existing = $this->model
      ->where('pet_id', $json->pet_id)
      ->whereIn('status', ['Pending', 'Active'])
      ->first();

    if ($existing) {
      return $this->response->setJSON([
        'status'  => false,
        'message' => 'This pet already has an active or pending insurance policy.'
      ]);
    }

    // Prepare user information
    $userInfo = [
      'user_id'   => $json->user_id,
      'full_name' => $json->full_name,
      'email'     => $json->email,
      'phone'     => $json->phone
    ];

    // Create or get insurance user
    $insuranceUserId = $this->model->createInsuranceUser($userInfo);

    // Prepare policy information
    $policyInfo = [
      'policy_number'     => 'PF' . random_int(111111, 999999),
      'insurance_user_id' => $insuranceUserId,
      'pet_id'            => $json->pet_id,
      'coverage_type'     => $json->coverage_type,
      'duration_years'    => $json->duration_years,
      // 'premium'           => $json->premium,
      // 'coverage_amount'   => $json->coverage_amount,
      'start_date'        => $json->start_date,
      'end_date'          => $json->end_date,
      // 'policy_details'    => $json->policy_details ?? '',
      'status'            => 'Active' // Since admin is adding directly, set as Active
    ];

    // Create insurance policy
    $policy = $this->model->createInsurance($policyInfo);

    if ($policy) {
      // Create quotation record
      $quotationData = [
        'policy_id'  => $policyInfo['policy_number'], // Assuming this returns the inserted policy ID
        'amount'     => (float) $json->premium,
        // 'status'     => 'Approved', // Since admin is creating it directly
        'created_at' => date('Y-m-d H:i:s')
      ];

      $quote = $this->model->createQuotation($quotationData);

      if ($quote) {
        return $this->response->setJSON([
          'status'  => true,
          'message' => 'Insurance policy and quotation created successfully'
        ]);
      } else {
        // If quotation fails, you might want to handle this scenario
        return $this->response->setJSON([
          'status'  => true,
          'message' => 'Insurance policy created but quotation failed',
          'warning' => true
        ]);
      }
    }

    return $this->response->setJSON([
      'status'  => false,
      'message' => 'Something went wrong, please try again'
    ]);
  }
}
