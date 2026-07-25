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

  public function getDogBreeds()
  {
    $breeds = $this->model->getBreeds();
    return $this->response->setJSON([
      'success' => true,
      'breeds'  => $breeds
    ]);
  }

  public function addEnquiry()
  {
    if (!$this->request->isAJAX()) {
      return $this->response->setStatusCode(405)->setJSON([
        'status'  => false,
        'message' => 'Method not allowed'
      ]);
    }

    $json = $this->request->getJSON();

    $userId = $json->user_id ?? null;
    $petId = $json->pet_id ?? null;

    // Handle new client creation or new pet for existing client
    $isNewPet = $json->is_new_pet ?? 0;

    if (isset($json->is_new_client) && $json->is_new_client == 1) {
      // Create user in the main users table
      $newUserId = $this->model->createUser([
        'name'       => $json->new_client_name,
        'email'      => $json->new_client_email ?? '',
        'phone'      => $json->new_client_phone,
        'status'     => 'active',
        'created_at' => date('Y-m-d H:i:s')
      ]);

      // Create pet first to get pet_id
      $petData = [
        'user_id'          => $newUserId,
        'name'             => $json->new_pet_name,
        'breed'            => $json->new_pet_breed,
        'dob'              => $json->new_pet_dob,
        'gender'           => $json->new_pet_gender,
        'last_vaccination' => $json->new_pet_vaccination,
        'aggressiveness_level'       => strtolower($json->new_pet_aggressive) ?? 'no',
        'vaccinated'       => strtolower($json->new_pet_vaccinated) ?? 'yes',
        'created_at'       => date('Y-m-d H:i:s')
      ];
      $petId = $this->model->createPet($petData);

      // Prepare user information using new fields
      $userInfo = [
        'user_id'   => $newUserId,
        'full_name' => $json->new_client_name,
        'email'     => $json->new_client_email ?? '',
        'phone'     => $json->new_client_phone,
        'address'   => $json->new_client_address ?? ''
      ];

      // Update local variables for policy creation
      $json->full_name = $json->new_client_name;
      $json->email = $json->new_client_email ?? '';
      $json->phone = $json->new_client_phone;
    } elseif ($isNewPet == 1) {
      // Existing client, but new pet
      $petData = [
        'user_id'          => $userId,
        'name'             => $json->new_pet_name,
        'breed'            => $json->new_pet_breed,
        'dob'              => $json->new_pet_dob,
        'gender'           => $json->new_pet_gender,
        'last_vaccination' => $json->new_pet_vaccination,
        'aggressiveness_level'       => strtolower($json->new_pet_aggressive) ?? 'no',
        'vaccinated'       => strtolower($json->new_pet_vaccinated) ?? 'yes',
        'created_at'       => date('Y-m-d H:i:s')
      ];
      $petId = $this->model->createPet($petData);

      $userInfo = [
        'user_id'   => $userId,
        'full_name' => $json->full_name,
        'email'     => $json->email,
        'phone'     => $json->phone
      ];
    } else {
      // For existing clients, check if pet already has a policy
      $existing = $this->model
        ->where('pet_id', $petId)
        ->whereIn('status', ['Pending', 'Active'])
        ->first();

      if ($existing) {
        return $this->response->setJSON([
          'status'  => false,
          'message' => 'This pet already has an active or pending insurance policy.'
        ]);
      }

      $userInfo = [
        'user_id'   => $userId,
        'full_name' => $json->full_name,
        'email'     => $json->email,
        'phone'     => $json->phone
      ];
    }

    // Create or get insurance user (mirror record for policy)
    $insuranceUserId = $this->model->createInsuranceUser($userInfo);

    // Prepare policy information
    $policyInfo = [
      'policy_number'     => 'PF' . random_int(111111, 999999),
      'insurance_user_id' => $insuranceUserId,
      'pet_id'            => $petId,
      'coverage_type'     => $json->coverage_type,
      'duration_years'    => $json->duration_years,
      'start_date'        => $json->start_date,
      'end_date'          => $json->end_date,
      'created_at'        => date('Y-m-d H:i:s'),
      'status'            => 'Pending' // Set as Pending for enquiries
    ];

    // Create insurance policy
    $policy = $this->model->createInsurance($policyInfo);

    if ($policy) {
      return $this->response->setJSON([
        'status'  => true,
        'message' => 'Enquiry added successfully'
      ]);
    }

    return $this->response->setJSON([
      'status'  => false,
      'message' => 'Something went wrong, please try again'
    ]);
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

    $userId = $json->user_id ?? null;
    $petId = $json->pet_id ?? null;

    // Handle new client creation or new pet for existing client
    $isNewPet = $json->is_new_pet ?? 0;

    if (isset($json->is_new_client) && $json->is_new_client == 1) {
      // Create user in the main users table
      $newUserId = $this->model->createUser([
        'name'       => $json->new_client_name,
        'email'      => $json->new_client_email ?? '',
        'phone'      => $json->new_client_phone,
        // 'user_type'  => 'Client',
        'status'     => 'active',
        'created_at' => date('Y-m-d H:i:s'),

      ]);

      // Create pet first to get pet_id
      $petData = [
        'user_id'          => $newUserId,
        'name'             => $json->new_pet_name,
        'breed'            => $json->new_pet_breed,
        'dob'              => $json->new_pet_dob,
        'gender'           => $json->new_pet_gender,
        'last_vaccination' => $json->new_pet_vaccination,
        'aggressiveness_level'       => strtolower($json->new_pet_aggressive) ?? 'no',
        'vaccinated'       => strtolower($json->new_pet_vaccinated) ?? 'yes',
        'created_at'       => date('Y-m-d H:i:s')
      ];
      $petId = $this->model->createPet($petData);

      // Prepare user information using new fields
      $userInfo = [
        'user_id'   => $newUserId,
        'full_name' => $json->new_client_name,
        'email'     => $json->new_client_email ?? '',
        'phone'     => $json->new_client_phone,
        'address'   => $json->new_client_address ?? ''
      ];

      // Update local variables for policy creation
      $json->full_name = $json->new_client_name;
      $json->email = $json->new_client_email ?? '';
      $json->phone = $json->new_client_phone;
    } elseif ($isNewPet == 1) {
      // Existing client, but new pet
      $petData = [
        'user_id'          => $userId,
        'name'             => $json->new_pet_name,
        'breed'            => $json->new_pet_breed,
        'dob'              => $json->new_pet_dob,
        'gender'           => $json->new_pet_gender,
        'last_vaccination' => $json->new_pet_vaccination,
        'aggressiveness_level'       => strtolower($json->new_pet_aggressive) ?? 'no',
        'vaccinated'       => strtolower($json->new_pet_vaccinated) ?? 'yes',
        'created_at'       => date('Y-m-d H:i:s')
      ];
      $petId = $this->model->createPet($petData);

      $userInfo = [
        'user_id'   => $userId,
        'full_name' => $json->full_name,
        'email'     => $json->email,
        'phone'     => $json->phone
      ];
    } else {
      // For existing clients, check if pet already has a policy
      $existing = $this->model
        ->where('pet_id', $petId)
        ->whereIn('status', ['Pending', 'Active'])
        ->first();

      if ($existing) {
        return $this->response->setJSON([
          'status'  => false,
          'message' => 'This pet already has an active or pending insurance policy.'
        ]);
      }

      $userInfo = [
        'user_id'   => $userId,
        'full_name' => $json->full_name,
        'email'     => $json->email,
        'phone'     => $json->phone
      ];
    }

    // Create or get insurance user
    $insuranceUserId = $this->model->createInsuranceUser($userInfo);

    // Prepare policy information
    $policyInfo = [
      'policy_number'     => 'PF' . random_int(111111, 999999),
      'insurance_user_id' => $insuranceUserId,
      'pet_id'            => $petId,
      'coverage_type'     => $json->coverage_type,
      'duration_years'    => $json->duration_years,
      'start_date'        => $json->start_date,
      'end_date'          => $json->end_date,
      'created_at'        => date('Y-m-d H:i:s'),
      'status'            => 'Active' // Since admin is adding directly, set as Active
    ];

    // Create insurance policy
    $policy = $this->model->createInsurance($policyInfo);

    if ($policy) {
      // Create quotation record
      $quotationData = [
        'policy_id'  => $policyInfo['policy_number'],
        'amount'     => (float) $json->premium,
        'created_at' => date('Y-m-d H:i:s')
      ];

      $quote = $this->model->createQuotation($quotationData);

      if ($quote) {
        return $this->response->setJSON([
          'status'  => true,
          'message' => 'Insurance policy and quotation created successfully'
        ]);
      } else {
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
  public function searchActivePolicies()
  {
    $query = $this->request->getJSON()->query;
    $db = \Config\Database::connect();
    $results = $db->table('insurance_policies as ip')
      ->select('ip.id, ip.policy_number, iu.full_name as client_name, up.name as pet_name')
      ->join('insurance_users as iu', 'iu.id = ip.insurance_user_id', 'left')
      ->join('user_pets as up', 'up.id = ip.pet_id', 'left')
      ->where('ip.status', 'Active')
      ->groupStart()
      ->like('ip.policy_number', $query)
      ->orLike('iu.full_name', $query)
      ->orLike('iu.phone', $query)
      ->groupEnd()
      ->limit(10)
      ->get()->getResult();

    return $this->response->setJSON([
      'success' => true,
      'policies' => $results
    ]);
  }

  public function addClaim()
  {
    $json = $this->request->getJSON();

    $claimData = [
      'policy_id' => $json->policy_id,
      'claim_type' => $json->claim_type,
      'amount' => $json->amount,
      'status' => 'Pending',
      'remarks' => $json->remarks,
      'settlement_date' => null,
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s')
    ];

    $claimId = $this->model->createClaim($claimData);

    if ($claimId) {
      return $this->response->setJSON([
        'status' => true,
        'message' => 'Claim added successfully'
      ]);
    }

    return $this->response->setJSON([
      'status' => false,
      'message' => 'Failed to add claim'
    ]);
  }

  public function updateEnquiryStatus()
  {
    $json = $this->request->getJSON();
    $id = $json->id;
    $status = $json->status;
    $remarks = $json->remarks ?? '';

    $db = \Config\Database::connect();
    $updated = $db->table('insurance_policies')
      ->where('id', $id)
      ->update(['status' => $status, 'remarks' => $remarks, 'updated_at' => date('Y-m-d H:i:s')]);

    if ($updated) {
      return $this->response->setJSON([
        'status' => true,
        'message' => 'Status updated successfully'
      ]);
    }

    return $this->response->setJSON([
      'status' => false,
      'message' => 'Failed to update status'
    ]);
  }
}
