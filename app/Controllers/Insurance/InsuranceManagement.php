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
        'status' => false,
        'message' => 'Quotation already created for this policy'
      ]);
    }

    $data = [
      'policy_id' => $json['policy_id'],
      'amount' => $json['amount'],
      'subject' => $json['subject'],
      'message' => $json['message']
    ];

    $quote = $this->model->createQuotation($data);
    if ($quote) {
      return $this->response->setJSON(['status' => true, 'message' => 'Quote Sent Successfully', 'emailData' => (array) $json]);
    }

    return $this->response->setJSON(['status' => false, 'message' => 'Something went wrong ,please try again']);
  }

  public function updateClaim()
  {
    $claimId = $this->request->getPost('claimId');
    $claimAmount = $this->request->getPost('claimAmount');
    $status = $this->request->getPost('status');
    $settlementDate = $this->request->getPost('settlementDate');
    $remarks = $this->request->getPost('remarks');

    // Update database here
    $data = [
      'amount' => $claimAmount,
      'status' => $status,
      'settlement_date' => $settlementDate ?: null,
      'remarks' => $remarks,
      'updated_at' => date('Y-m-d H:i:s')
    ];

    $result = $this->model->updateClaim($claimId, $data);

    if ($result) {
      return $this->response->setJSON([
        'status' => true,
        'message' => 'Claim updated successfully'
      ]);
    } else {
      return $this->response->setJSON([
        'status' => false,
        'message' => 'Failed to update claim'
      ]);
    }
  }
  public function sendAjaxMail()
  {
    $email = $this->request->getPost('email');
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
    $client = $clientModel->getClient($phone);

    if ($client) {
      return $this->response->setJSON([
        'success' => true,
        'client' => [
          'id' => $client->id,
          'full_name' => $client->name,
          'email' => $client->email,
          'phone' => $client->phone
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
    $pets = $petModel->getUserPets($clientId);

    if ($pets) {
      return $this->response->setJSON([
        'success' => true,
        'pets' => $pets
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
      'breeds' => $breeds
    ]);
  }

  public function addEnquiry()
  {
    if (!$this->request->isAJAX()) {
      return $this->response->setStatusCode(405)->setJSON([
        'status' => false,
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
        'name' => $json->new_client_name,
        'email' => $json->new_client_email ?? '',
        'phone' => $json->new_client_phone,
        'status' => 'active',
        'created_at' => date('Y-m-d H:i:s')
      ]);

      // Create pet first to get pet_id
      $petData = [
        'user_id' => $newUserId,
        'name' => $json->new_pet_name,
        'breed' => $json->new_pet_breed,
        'dob' => $json->new_pet_dob,
        'gender' => $json->new_pet_gender,
        'last_vaccination' => $json->new_pet_vaccination,
        'aggressiveness_level' => strtolower($json->new_pet_aggressive) ?? 'no',
        'vaccinated' => strtolower($json->new_pet_vaccinated) ?? 'yes',
        'created_at' => date('Y-m-d H:i:s')
      ];
      $petId = $this->model->createPet($petData);

      // Prepare user information using new fields
      $userInfo = [
        'user_id' => $newUserId,
        'full_name' => $json->new_client_name,
        'email' => $json->new_client_email ?? '',
        'phone' => $json->new_client_phone,
        'address' => $json->new_client_address ?? ''
      ];

      // Update local variables for policy creation
      $json->full_name = $json->new_client_name;
      $json->email = $json->new_client_email ?? '';
      $json->phone = $json->new_client_phone;
    } elseif ($isNewPet == 1) {
      // Existing client, but new pet
      $petData = [
        'user_id' => $userId,
        'name' => $json->new_pet_name,
        'breed' => $json->new_pet_breed,
        'dob' => $json->new_pet_dob,
        'gender' => $json->new_pet_gender,
        'last_vaccination' => $json->new_pet_vaccination,
        'aggressiveness_level' => strtolower($json->new_pet_aggressive) ?? 'no',
        'vaccinated' => strtolower($json->new_pet_vaccinated) ?? 'yes',
        'created_at' => date('Y-m-d H:i:s')
      ];
      $petId = $this->model->createPet($petData);

      $userInfo = [
        'user_id' => $userId,
        'full_name' => $json->full_name,
        'email' => $json->email,
        'phone' => $json->phone
      ];
    } else {
      // For existing clients, check if pet already has a policy
      $existing = $this->model
        ->where('pet_id', $petId)
        ->whereIn('status', ['Pending', 'Active'])
        ->first();

      if ($existing) {
        return $this->response->setJSON([
          'status' => false,
          'message' => 'This pet already has an active or pending insurance policy.'
        ]);
      }

      $userInfo = [
        'user_id' => $userId,
        'full_name' => $json->full_name,
        'email' => $json->email,
        'phone' => $json->phone
      ];
    }

    // Create or get insurance user (mirror record for policy)
    $insuranceUserId = $this->model->createInsuranceUser($userInfo);

    // Prepare policy information
    $policyInfo = [
      'policy_number' => 'PF' . random_int(111111, 999999),
      'insurance_user_id' => $insuranceUserId,
      'pet_id' => $petId,
      'coverage_type' => $json->coverage_type,
      'duration_years' => $json->duration_years,
      'start_date' => $json->start_date,
      'end_date' => $json->end_date,
      'created_at' => date('Y-m-d H:i:s'),
      'status' => 'Pending' // Set as Pending for enquiries
    ];

    // Create insurance policy
    $policy = $this->model->createInsurance($policyInfo);

    if ($policy) {
      return $this->response->setJSON([
        'status' => true,
        'message' => 'Enquiry added successfully'
      ]);
    }

    return $this->response->setJSON([
      'status' => false,
      'message' => 'Something went wrong, please try again'
    ]);
  }

  public function addPolicy()
  {
    if (!$this->request->isAJAX()) {
      return $this->response->setStatusCode(405)->setJSON([
        'status' => false,
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
        'name' => $json->new_client_name,
        'email' => $json->new_client_email ?? '',
        'phone' => $json->new_client_phone,
        // 'user_type'  => 'Client',
        'status' => 'active',
        'created_at' => date('Y-m-d H:i:s'),

      ]);

      // Create pet first to get pet_id
      $petData = [
        'user_id' => $newUserId,
        'name' => $json->new_pet_name,
        'breed' => $json->new_pet_breed,
        'dob' => $json->new_pet_dob,
        'gender' => $json->new_pet_gender,
        'last_vaccination' => $json->new_pet_vaccination,
        'aggressiveness_level' => strtolower($json->new_pet_aggressive) ?? 'no',
        'vaccinated' => strtolower($json->new_pet_vaccinated) ?? 'yes',
        'created_at' => date('Y-m-d H:i:s')
      ];
      $petId = $this->model->createPet($petData);

      // Prepare user information using new fields
      $userInfo = [
        'user_id' => $newUserId,
        'full_name' => $json->new_client_name,
        'email' => $json->new_client_email ?? '',
        'phone' => $json->new_client_phone,
        'address' => $json->new_client_address ?? ''
      ];

      // Update local variables for policy creation
      $json->full_name = $json->new_client_name;
      $json->email = $json->new_client_email ?? '';
      $json->phone = $json->new_client_phone;
    } elseif ($isNewPet == 1) {
      // Existing client, but new pet
      $petData = [
        'user_id' => $userId,
        'name' => $json->new_pet_name,
        'breed' => $json->new_pet_breed,
        'dob' => $json->new_pet_dob,
        'gender' => $json->new_pet_gender,
        'last_vaccination' => $json->new_pet_vaccination,
        'aggressiveness_level' => strtolower($json->new_pet_aggressive) ?? 'no',
        'vaccinated' => strtolower($json->new_pet_vaccinated) ?? 'yes',
        'created_at' => date('Y-m-d H:i:s')
      ];
      $petId = $this->model->createPet($petData);

      $userInfo = [
        'user_id' => $userId,
        'full_name' => $json->full_name,
        'email' => $json->email,
        'phone' => $json->phone
      ];
    } else {
      // For existing clients, check if pet already has a policy
      $existing = $this->model
        ->where('pet_id', $petId)
        ->whereIn('status', ['Pending', 'Active'])
        ->first();

      if ($existing) {
        return $this->response->setJSON([
          'status' => false,
          'message' => 'This pet already has an active or pending insurance policy.'
        ]);
      }

      $userInfo = [
        'user_id' => $userId,
        'full_name' => $json->full_name,
        'email' => $json->email,
        'phone' => $json->phone
      ];
    }

    // Create or get insurance user
    $insuranceUserId = $this->model->createInsuranceUser($userInfo);

    // Prepare policy information
    $policyInfo = [
      'policy_number' => 'PF' . random_int(111111, 999999),
      'insurance_user_id' => $insuranceUserId,
      'pet_id' => $petId,
      'coverage_type' => $json->coverage_type,
      'duration_years' => $json->duration_years,
      'start_date' => $json->start_date,
      'end_date' => $json->end_date,
      'created_at' => date('Y-m-d H:i:s'),
      'status' => 'Active' // Since admin is adding directly, set as Active
    ];

    // Create insurance policy
    $policy = $this->model->createInsurance($policyInfo);

    if ($policy) {
      // Create quotation record
      $quotationData = [
        'policy_id' => $policyInfo['policy_number'],
        'amount' => (float) $json->premium,
        'created_at' => date('Y-m-d H:i:s')
      ];

      $quote = $this->model->createQuotation($quotationData);

      if ($quote) {
        return $this->response->setJSON([
          'status' => true,
          'message' => 'Insurance policy and quotation created successfully'
        ]);
      } else {
        return $this->response->setJSON([
          'status' => true,
          'message' => 'Insurance policy created but quotation failed',
          'warning' => true
        ]);
      }
    }

    return $this->response->setJSON([
      'status' => false,
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


  public function chatbotWidget()
  {
    return $this->loadView('admin/chatbot_widget');
  }
  public function chatHistory()
  {
    $db = \Config\Database::connect();

    // Fetch unique session IDs and their last message time
    $sessions = $db->table('chatbot_logs')
      ->select('chatbot_logs.session_id as id, MAX(chatbot_logs.created_at) as last_message_at, users.name as user_name, users.phone')
      ->join('users', 'users.id = chatbot_logs.user_id', 'left')
      ->groupBy('chatbot_logs.session_id')
      ->orderBy('last_message_at', 'DESC')
      ->get()
      ->getResultArray();

    $conversations = [];
    foreach ($sessions as $session) {
      $latest = $db->table('chatbot_logs')
        ->where('session_id', $session['id'])
        ->orderBy('created_at', 'DESC')
        ->limit(1)
        ->get()->getRowArray();

      $conversations[] = [
        'id' => $session['id'],
        'user_name' => $session['user_name'] ?: 'Guest User',
        'phone' => $session['phone'] ?: '',
        'last_message_at' => $session['last_message_at'],
        'last_message' => $latest ? $latest['message'] : '',
        'call_id' => null
      ];
    }

    $data['conversations'] = $conversations;
    $data['theme'] = 'Insurance';
    $data['current_section'] = 'chat_history';
    return $this->loadView('admin/chat_history_view', $data);
  }

  public function chatMessages($sessionId)
  {
    $db = \Config\Database::connect();

    $messagesData = $db->table('chatbot_logs')
      ->where('session_id', $sessionId)
      ->orderBy('created_at', 'ASC')
      ->get()
      ->getResultArray();

    $sessionUser = $db->table('chatbot_logs')
      ->select('users.name as user_name, users.phone')
      ->join('users', 'users.id = chatbot_logs.user_id', 'left')
      ->where('chatbot_logs.session_id', $sessionId)
      ->limit(1)
      ->get()
      ->getRowArray();

    $messages = [];
    foreach ($messagesData as $msg) {
      $messages[] = [
        'sender' => $msg['role'] === 'model' ? 'bot' : 'user',
        'message' => $msg['message'],
        'created_at' => $msg['created_at']
      ];
    }

    return $this->response->setJSON([
      'status' => 'success',
      'data' => [
        'user' => [
          'user_name' => (!empty($sessionUser['user_name'])) ? $sessionUser['user_name'] : 'Guest User',
          'user_phone' => (!empty($sessionUser['phone'])) ? $sessionUser['phone'] : ''
        ],
        'messages' => $messages
      ]
    ]);
  }

  public function chatbot()
  {
    $json = $this->request->getJSON();
    $userMessage = $json->message ?? '';
    $fileData = $json->file_data ?? null;
    $mimeType = $json->mime_type ?? 'application/pdf';
    $sessionId = $json->session_id ?? uniqid('chat_');

    if (empty($userMessage) && empty($fileData)) {
      return $this->response->setJSON(['status' => false, 'message' => 'Empty message and no file provided']);
    }

    $db = \Config\Database::connect();

    $logMessage = $userMessage;
    if (!empty($fileData)) {
      $logMessage .= " [Attached File: " . $mimeType . "]";
    }

    $db->table('chatbot_logs')->insert([
      'session_id' => $sessionId,
      'role' => 'user',
      'user_id' => $json->user_id,
      'message' => $logMessage,
      'created_at' => date('Y-m-d H:i:s')
    ]);

    $apiKey = getenv('x-goog-api-key');
    if (empty($apiKey)) {
      return $this->response->setJSON(['status' => false, 'message' => 'API Key not configured']);
    }

    // Updated to gemma-4-26b-a4b-it
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent";

    $history = $json->history ?? [];

    $tools = [
      [
        "functionDeclarations" => [
          [
            "name" => "get_policy_details",
            "description" => "Get the details of an insurance policy by its policy number (e.g., PF123456)",
            "parameters" => [
              "type" => "OBJECT",
              "properties" => [
                "policy_number" => [
                  "type" => "STRING",
                  "description" => "The policy number to search for"
                ]
              ],
              "required" => ["policy_number"]
            ]
          ],
          [
            "name" => "get_claim_status",
            "description" => "Get the status and details of an insurance claim by its claim ID",
            "parameters" => [
              "type" => "OBJECT",
              "properties" => [
                "claim_id" => [
                  "type" => "STRING",
                  "description" => "The ID of the claim to search for"
                ]
              ],
              "required" => ["claim_id"]
            ]
          ],
          [
            "name" => "get_my_pets",
            "description" => "Get the list of pets owned by the current user. Call this when the user asks about their pets or how many pets they have. No parameters are required.",
            "parameters" => [
              "type" => "OBJECT",
              "properties" => (object) []
            ]
          ]

        ]
      ]
    ];

    $systemInstructionText = "You are a helpful and polite AI assistant for Petsfolio Insurance. Answer only questions related to pet insurance, policies, and claims. Do not answer off-topic questions. Use tools to fetch real-time data when a user asks about their specific policy number or claim ID. Keep your responses VERY SHORT, brief, and concise. Limit your answers to 1-2 sentences maximum. Do not provide long explanations unless explicitly asked. IMPORTANT: DO NOT use any Markdown formatting like **bold** text or asterisks. Keep your responses in plain text.

Follow these conversational flows based on the customer's intent:

1. New Insurance Purchase
If a customer wants to purchase insurance: Provide the insurance premium/cost. Explain the enrollment process and required information. Assist them through the purchase journey.

2. Active Insurance Policy
If a customer has an active insurance policy: Ask whether they would like to renew their policy. If yes: Provide the renewal premium and renewal process, guide them through it. If no: Thank them for their business and conclude.

3. Insurance Expiry & Claims
If a customer inquires about policy expiry or claims: Share the policy expiry date and claim eligibility. Ask whether they would like to file a claim. If yes: Explain the process, request documents, guide them. If no: Conclude.

4. Pet Information & Insurance Management
If a customer asks about their pets: Use the get_my_pets tool to fetch the current user's pet details automatically. Display the pet details. Ask whether they would like to add or remove a pet. If adding: Explain eligibility, pricing, enrollment. If removing: Explain process and implications. If neither: Conclude.

5. Policy Details
If a customer requests policy information: Provide details of their current policy (Coverage, Benefits, Exclusions, Add-ons). Ask if they want to make changes. If yes: Guide them. If no: Conclude.

6. Payment Options
If a customer asks about payment methods: Provide available options. Explain schedules, installments, discounts. Ask if they want to set up a plan. If yes: Guide them. If no: Conclude.

7. Claim Status
If a customer asks about claim status: Request claim details. Provide the latest claim status. Address concerns. Ask if they would like to file a new claim. If yes: Guide them. If no: Conclude.

8. Discounts & Promotions
If a customer asks about discounts: Share current offers. Explain eligibility and benefits. Ask if they want to enroll. If yes: Guide them. If no: Conclude.

9. Customer Support
If a customer requests support: Provide contact info, channels, and hours. Address specific concerns. If no further assistance is needed: Conclude.

10. Policy Cancellation
If a customer wants to cancel: Ask for the reason. Explain the cancellation process, timelines, fees/refunds. If they proceed: Guide them. If not: Conclude.

11. Policy Renewal
If a customer asks about renewal: Ask whether they want to renew or explore alternatives. If renewing: Provide pricing and process. If exploring: Present plans. If neither: Conclude.

12. Policy Coverage
If a customer asks about coverage: Request specific details if needed. Explain what is covered, exclusions, limitations. If they want to modify: Guide them. If not: Conclude.

13. Policy Benefits
If a customer asks about benefits: Explain available benefits, eligibility, conditions. If they wish to modify: Guide them. If not: Conclude.

14. Policy Claims
If a customer asks about claims: Request relevant details. Explain process and current info. If they want to file a new claim: Guide them. If not: Conclude.

15. Pet Death / Loss Support
If a customer reports a pet death or permanent disability: Express condolences and empathy. Guide them on the claim process and how to report the event. Inform them about the required documentation (e.g., vet certificate, death certificate). Explain the process for policy closure or claiming the death/disability benefit. Provide support and assure them of prompt assistance.

16. Policy Upgrade / Downgrade
If a customer wants to upgrade, downgrade, or change their policy: Ask what changes they want to make (e.g., increase cover amount, add riders, change plans). Provide options to compare plans. Explain the process, any additional premiums or refunds, and required documentation. Guide them through making the requested changes.";

    // In Gemini API, systemInstruction only supports text.
    // PDF/multimodal contents must be passed in the `contents` array.
    $systemInstruction = [
      "parts" => [
        ["text" => $systemInstructionText]
      ]
    ];

    $pdfParts = [];
    $cache = \Config\Services::cache();
    $pdfBrochure = ROOTPATH . 'public/assets/PET-Brochure-Final.pdf';
    $pdfWordings = ROOTPATH . 'public/assets/Pet-Insurance-Policy-Wordings-Final.pdf';

    if (file_exists($pdfBrochure)) {
      $brochureData = $cache->get('pdf_brochure_base64');
      if (!$brochureData) {
        $brochureData = base64_encode(file_get_contents($pdfBrochure));
        $cache->save('pdf_brochure_base64', $brochureData, 86400 * 30); // Cache for 30 days
      }
      $pdfParts[] = [
        "inlineData" => [
          "mimeType" => "application/pdf",
          "data" => $brochureData
        ]
      ];
    }

    if (file_exists($pdfWordings)) {
      $wordingsData = $cache->get('pdf_wordings_base64');
      if (!$wordingsData) {
        $wordingsData = base64_encode(file_get_contents($pdfWordings));
        $cache->save('pdf_wordings_base64', $wordingsData, 86400 * 30); // Cache for 30 days
      }
      $pdfParts[] = [
        "inlineData" => [
          "mimeType" => "application/pdf",
          "data" => $wordingsData
        ]
      ];
    }

    $contents = [];
    foreach ($history as $msg) {
      $role = $msg->role ?? 'user';
      if (in_array($role, ['user', 'model', 'tool'])) {
        if (isset($msg->parts) && is_array($msg->parts) && !empty($msg->parts)) {
          $contents[] = [
            "role" => $role,
            "parts" => $msg->parts
          ];
        } elseif (isset($msg->text) && trim($msg->text) !== '') {
          $contents[] = [
            "role" => $role,
            "parts" => [["text" => $msg->text]]
          ];
        }
      }
    }

    $userParts = [];
    if (!empty($userMessage)) {
      $userParts[] = ["text" => $userMessage];
    }
    if (!empty($fileData)) {
      $base64Data = preg_replace('#^data:[\w/\-]+;base64,#i', '', $fileData);
      $userParts[] = [
        "inlineData" => [
          "mimeType" => $mimeType,
          "data" => $base64Data
        ]
      ];
    }

    $contents[] = [
      "role" => "user",
      "parts" => $userParts
    ];

    // Prepend PDFs to the first user message in contents to give context
    if (!empty($pdfParts)) {
      foreach ($contents as &$msg) {
        if ($msg['role'] === 'user') {
          $msg['parts'] = array_merge($pdfParts, $msg['parts']);
          break;
        }
      }
      unset($msg);
    }

    $data = [
      "systemInstruction" => $systemInstruction,
      "tools" => $tools,
      "contents" => $contents,
      "generationConfig" => [
        "maxOutputTokens" => 250,
        "temperature" => 0.5
      ]
    ];

    $response = $this->callGeminiApi($url, $data, $apiKey);

    // Reconnect DB after potentially long API call to avoid "MySQL server has gone away"
    $db->close();
    $db = \Config\Database::connect();

    // Check if API call failed entirely
    if (empty($response) || !isset($response['candidates'][0]['content']['parts'][0])) {
      $errorMsg = 'Sorry, the AI service is temporarily unavailable. Please try again.';
      if (isset($response['error'])) {
        $errorMsg = 'API Error: ' . ($response['error']['message'] ?? json_encode($response['error']));
        error_log('Gemini API Error: ' . json_encode($response['error']));
      } elseif (isset($response['candidates'][0]['finishReason'])) {
        $errorMsg = 'Blocked by Finish Reason: ' . $response['candidates'][0]['finishReason'];
      } elseif (empty($response)) {
        $errorMsg = 'API Error: No response received from Gemini server.';
      }
      return $this->response->setJSON(['status' => false, 'message' => $errorMsg]);
    }

    if (isset($response['candidates'][0]['content']['parts'][0]['functionCall'])) {
      $functionCall = $response['candidates'][0]['content']['parts'][0]['functionCall'];
      $functionName = $functionCall['name'];
      $args = $functionCall['args'];

      $toolResponseData = [];
      if ($functionName === 'get_policy_details') {
        $policyNum = $args['policy_number'] ?? '';
        $policyDetails = $this->model->getPolicyByNumber($policyNum);
        $toolResponseData = $policyDetails ? $policyDetails : ['error' => 'Policy not found. Please check the policy number.'];
      } elseif ($functionName === 'get_claim_status') {
        $claimId = $args['claim_id'] ?? '';
        $claimDetails = $this->model->getClaimById($claimId);
        $toolResponseData = $claimDetails ? $claimDetails : ['error' => 'Claim not found. Please check the claim ID.'];
      } elseif ($functionName === 'get_my_pets') {
        $petModel = new \App\Models\User();
        // User ID is currently using the static ID for the chatbot session
        $userId = '268';
        $pets = $petModel->getUserPets($userId);
        $toolResponseData = $pets ? ['pets' => $pets] : ['message' => 'You do not have any registered pets.'];
      }

      $contents[] = $response['candidates'][0]['content'];
      $contents[] = [
        "role" => "tool",
        "parts" => [
          [
            "functionResponse" => [
              "name" => $functionName,
              "response" => [
                "name" => $functionName,
                "content" => $toolResponseData
              ]
            ]
          ]
        ]
      ];

      $data['contents'] = $contents;
      $response2 = $this->callGeminiApi($url, $data, $apiKey);

      // Reconnect DB again after second API call
      $db->close();
      $db = \Config\Database::connect();

      $finalText = '';
      $parts = $response2['candidates'][0]['content']['parts'] ?? [];
      foreach ($parts as $part) {
        if (!empty($part['thought']) || (isset($part['thought']) && $part['thought'] === true)) {
          continue;
        }
        if (isset($part['text'])) {
          $finalText .= preg_replace('/<think>.*?<\/think>/is', '', $part['text']) . "\n";
        }
      }
      $finalText = trim($finalText);
      if (empty($finalText)) {
        $finalText = 'Sorry, I could not process that.';
      }
      $contents[] = [
        "role" => "model",
        "parts" => [["text" => $finalText]]
      ];

      $db->table('chatbot_logs')->insert([
        'session_id' => $sessionId,
        'role' => 'model',
        'message' => $finalText,
        'created_at' => date('Y-m-d H:i:s')
      ]);

      return $this->response->setJSON([
        'status' => true,
        'reply' => $finalText,
        'history' => $contents
      ]);
    }

    $finalText = '';
    $parts = $response['candidates'][0]['content']['parts'] ?? [];
    foreach ($parts as $part) {
      if (!empty($part['thought']) || (isset($part['thought']) && $part['thought'] === true)) {
        continue;
      }
      if (isset($part['text'])) {
        $finalText .= preg_replace('/<think>.*?<\/think>/is', '', $part['text']) . "\n";
      }
    }
    $finalText = trim($finalText);
    if (empty($finalText)) {
      $finalText = 'Sorry, I could not process that.';
    }

    $contents[] = [
      "role" => "model",
      "parts" => [["text" => $finalText]]
    ];

    $db->table('chatbot_logs')->insert([
      'session_id' => $sessionId,
      'role' => 'model',
      'message' => $finalText,
      'created_at' => date('Y-m-d H:i:s')
    ]);

    return $this->response->setJSON([
      'status' => true,
      'reply' => $finalText,
      'history' => $contents
    ]);
  }

  private function callGeminiApi($url, $data, $apiKey = '')
  {
    $ch = curl_init($url);
    $headers = ['Content-Type: application/json'];
    if (!empty($apiKey)) {
      $headers[] = 'x-goog-api-key: ' . $apiKey;
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 25);

    $result = curl_exec($ch);

    if (curl_errno($ch)) {
      $error = curl_error($ch);
      error_log('Gemini cURL Error: ' . $error);
      curl_close($ch);
      return ['error' => ['message' => 'cURL Error: ' . $error]];
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
      error_log('Gemini API HTTP ' . $httpCode . ': ' . $result);
    }

    $decoded = json_decode($result, true);
    if ($decoded === null && !empty($result)) {
      return ['error' => ['message' => 'Failed to parse JSON. HTTP ' . $httpCode . ' Response: ' . substr($result, 0, 100)]];
    }

    return $decoded;
  }
}
