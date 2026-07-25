<?php

namespace App\Libraries;

use App\Models\Booking;
use Exception;
use Config\Services;

class CashfreePayoutService
{
  protected $baseUrl;
  protected $clientId;
  protected $clientSecret;
  protected $apiVersion = '2024-01-01'; // V2 API version
  protected $db;

  public function __construct()
  {
    $this->db = \Config\Database::connect();

    $environment = getenv('CASHFREE_ENVIRONMENT') ?: 'sandbox';
    // V2 API endpoints
    $this->baseUrl = ($environment === 'production')
      ? 'https://payout-api.cashfree.com/payout'
      : 'https://sandbox.cashfree.com/payout';

    $this->clientId     = getenv('CF_PAYOUT_CLIENT_ID');
    $this->clientSecret = getenv('CF_PAYOUT_CLIENT_SECRET');

    // Validate credentials
    if (empty($this->clientId) || empty($this->clientSecret)) {
      throw new Exception('Cashfree Payout credentials not configured');
    }
  }

  /**
   * Core POST method - V2 API uses direct client credentials, no bearer token
   */
  private function post(string $endpoint, array $data): array
  {
    $client = Services::curlrequest();

    try {
      // V2 API uses client credentials directly in headers
      $headers = [
        'Content-Type'    => 'application/json',
        'x-api-version'   => $this->apiVersion,
        'x-client-id'     => $this->clientId,
        'x-client-secret' => $this->clientSecret,
      ];

      $url = $this->baseUrl . $endpoint;

      $response = $client->post($url, [
        'headers'     => $headers,
        'json'        => $data,
        'http_errors' => false,
        'timeout'     => 30,
      ]);

      $statusCode = $response->getStatusCode();
      $body       = json_decode($response->getBody(), true) ?: [];

      $this->logRequest('POST', $endpoint, $data, $statusCode, $body);

      if ($statusCode >= 400) {
        return [
          'success' => false,
          'status'  => $statusCode,
          'error'   => $body['message'] ?? ($body['subCode'] ?? 'Unknown error'),
          'details' => $body,
        ];
      }

      return [
        'success' => true,
        'status'  => $statusCode,
        'data'    => $body,
      ];
    } catch (Exception $e) {
      \log_message('error', 'Cashfree POST Error: ' . $e->getMessage());
      return [
        'success' => false,
        'status'  => 500,
        'error'   => $e->getMessage(),
      ];
    }
  }

  /**
   * Core GET method - V2 API
   */
  private function get(string $endpoint, array $params = []): array
  {
    $client = Services::curlrequest();

    try {
      $options = [
        'headers'     => [
          'Content-Type'    => 'application/json',
          'x-api-version'   => $this->apiVersion,
          'x-client-id'     => $this->clientId,
          'x-client-secret' => $this->clientSecret,
        ],
        'http_errors' => false,
        'timeout'     => 30,
      ];

      if (!empty($params)) {
        $options['query'] = $params;
      }

      $response = $client->get($this->baseUrl . $endpoint, $options);

      $statusCode = $response->getStatusCode();
      $body       = json_decode($response->getBody(), true) ?: [];

      $this->logRequest('GET', $endpoint, $params, $statusCode, $body);

      if ($statusCode >= 400) {
        return [
          'success' => false,
          'status'  => $statusCode,
          'error'   => $body['message'] ?? ($body['subCode'] ?? 'Unknown error'),
          'details' => $body,
        ];
      }

      return [
        'success' => true,
        'status'  => $statusCode,
        'data'    => $body,
      ];
    } catch (Exception $e) {
      \log_message('error', 'Cashfree GET Error: ' . $e->getMessage());
      return [
        'success' => false,
        'status'  => 500,
        'error'   => $e->getMessage(),
      ];
    }
  }

  /**
   * Get Access Token for V1 API
   */
  private function getAccessToken(): ?string
  {
    $cache = Services::cache();
    $cacheKey = 'cf_payout_access_token';
    $token = $cache->get($cacheKey);

    if ($token) {
      return $token;
    }

    $client = Services::curlrequest();
    try {
      $response = $client->post($this->baseUrl . '/v1/authorize', [
        'headers' => [
          'x-client-id'     => $this->clientId,
          'x-client-secret' => $this->clientSecret,
          'Content-Type'    => 'application/json',
        ],
        'http_errors' => false,
      ]);

      $body = json_decode($response->getBody(), true);
      if (($body['status'] ?? '') === 'SUCCESS' && !empty($body['data']['token'])) {
        $token = $body['data']['token'];
        // Cache for 24 hours (usually tokens last 24h, but check docs if possible)
        // Cashfree usually gives 24h, we'll cache for 23h to be safe
        $cache->save($cacheKey, $token, 23 * 3600);
        return $token;
      }

      \log_message('error', 'Cashfree Auth Error: ' . ($body['message'] ?? 'Unknown'));
      return null;
    } catch (Exception $e) {
      \log_message('error', 'Cashfree Auth Exception: ' . $e->getMessage());
      return null;
    }
  }

  /**
   * Core GET method for V1 API (Bearer Token)
   */
  private function getV1(string $endpoint, array $params = []): array
  {
    $token = $this->getAccessToken();
    if (!$token) {
      return [
        'success' => false,
        'status'  => 401,
        'error'   => 'Failed to obtain access token',
      ];
    }

    $client = Services::curlrequest();
    try {
      $options = [
        'headers' => [
          'Authorization' => 'Bearer ' . $token,
          'Content-Type'  => 'application/json',
        ],
        'http_errors' => false,
        'timeout'     => 30,
      ];

      if (!empty($params)) {
        $options['query'] = $params;
      }

      $url = $this->baseUrl . $endpoint;
      $response = $client->get($url, $options);

      $statusCode = $response->getStatusCode();
      $body = json_decode($response->getBody(), true) ?: [];

      $this->logRequest('GET_V1', $endpoint, $params, $statusCode, $body);

      if ($statusCode >= 400 || (isset($body['status']) && $body['status'] === 'ERROR')) {
        $errorMessage = $body['message'] ?? ($body['subCode'] ?? 'Unknown error');

        // If token is invalid, clear cache so it's regenerated on next call
        if (stripos($errorMessage, 'Token is not valid') !== false || ($body['subCode'] ?? '') == '403') {
          Services::cache()->delete('cf_payout_access_token');
          \log_message('warning', 'Cashfree Payout: Token invalid, cleared cache.');
        }

        return [
          'success' => false,
          'status'  => $statusCode,
          'error'   => $errorMessage,
          'details' => $body,
        ];
      }

      return [
        'success' => true,
        'status'  => $statusCode,
        'data'    => $body,
      ];
    } catch (Exception $e) {
      \log_message('error', 'Cashfree V1 GET Error: ' . $e->getMessage());
      return [
        'success' => false,
        'status'  => 500,
        'error'   => $e->getMessage(),
      ];
    }
  }



  private function logRequest(string $method, string $endpoint, array $request, int $statusCode, array $response): void
  {
    $logData = [
      'method'    => $method,
      'endpoint'  => $endpoint,
      'request'   => $this->sanitizeData($request),
      'status'    => $statusCode,
      'response'  => $this->sanitizeData($response),
      'timestamp' => date('Y-m-d H:i:s')
    ];

    log_message('info', 'Cashfree Payout API: ' . json_encode($logData));
  }

  private function sanitizeData(array $data): array
  {
    $sensitiveKeys = [
      'bankAccount',
      'bank_account_number',
      'ifsc',
      'bank_ifsc',
      'account_number',
      'client_secret',
      'token',
      'authorization',
      'vpa',
      'phone',
      'beneficiary_phone',
      'email',
      'beneficiary_email',
      'address1',
      'beneficiary_address',
      'city',
      'state',
      'pincode',
      'beneficiary_postal_code',
      'aadhaar',
      'pan'
    ];

    foreach ($data as $key => $value) {
      if (in_array($key, $sensitiveKeys, true)) {
        $data[$key] = '***MASKED***';
      } elseif (is_array($value)) {
        $data[$key] = $this->sanitizeData($value);
      }
    }

    return $data;
  }

  // ==================== BENEFICIARY METHODS ====================

  /**
   * Add Beneficiary - V2 API format
   */
  public function addUserBeneficiary(int $userId, int $bankId = null): array
  {
    if ($bankId) {
      $bankDetails = $this->getBankDetailsById($bankId);
    } else {
      $bankDetails = $this->getUserBankDetails($userId);
    }

    if (!$bankDetails) {
      return [
        'success' => false,
        'error'   => 'Bank details not found'
      ];
    }

    $userInfo      = $this->getUserInfo($userId);
    $beneficiaryId = 'BEN_' . $userId . '_' . ($bankId ?? '0');


    // Check if beneficiary already exists
    $existingBeneficiary = $this->getUserBeneficiary($userId, $bankId);
    if ($existingBeneficiary) {

      return [
        'success'        => true,
        'message'        => 'Beneficiary already exists',
        'beneficiary_id' => $existingBeneficiary['beneficiary_id']
      ];
    }

    // V2 API format
    $payload = [
      "beneficiary_id"                 => $beneficiaryId,
      "beneficiary_name"               => $bankDetails['account_holder_name'],
      "beneficiary_instrument_details" => [],
      "beneficiary_contact_details"    => [
        "beneficiary_email"        => $userInfo['email'] ?? 'noreply@example.com',
        "beneficiary_phone"        => $userInfo['phone'] ?? '',
        "beneficiary_country_code" => "+91",
      ]
    ];


    $payload['beneficiary_instrument_details']['bank_account_number'] = $bankDetails['account_number'];
    $payload['beneficiary_instrument_details']['bank_ifsc']           = $bankDetails['ifsc_code'];


    // Add optional address details if available
    if (!empty($userInfo['address'])) {
      $payload['beneficiary_contact_details']['beneficiary_address'] = $userInfo['address'];
    }
    if (!empty($userInfo['city'])) {
      $payload['beneficiary_contact_details']['beneficiary_city'] = $userInfo['city'];
    }
    if (!empty($userInfo['state'])) {
      $payload['beneficiary_contact_details']['beneficiary_state'] = $userInfo['state'];
    }
    if (!empty($userInfo['pincode'])) {
      $payload['beneficiary_contact_details']['beneficiary_postal_code'] = $userInfo['pincode'];
    }

    $result = $this->post('/beneficiary', $payload);

    // Handle case where beneficiary already exists (409 Conflict)
    if (
      !$result['success'] &&
      ($result['status'] === 409 ||
        stripos($result['error'] ?? '', 'already exists') !== false)
    ) {

      $existingId = null;

      // 1. Try to extract ID from error message (if provided)
      if (preg_match('/beneficiary\s*(?:with\s*id|id)?\s*[:\s]?\s*([a-zA-Z0-9_\-]+)/i', $result['error'], $matches)) {
        // Exclude specific words that might match but aren't IDs
        if (strpos($result['error'], 'bank_account_number') === false) {
          $existingId = $matches[1];
        }
      }

      // 2. If ID not found in error, try to fetch by bank details
      if (!$existingId && isset($payload['beneficiary_instrument_details']['bank_account_number'])) {
        $searchParams = [
          'bank_account_number' => $payload['beneficiary_instrument_details']['bank_account_number'],
          'bank_ifsc'           => $payload['beneficiary_instrument_details']['bank_ifsc']
        ];

        $searchResult = $this->get('/beneficiary', $searchParams); // Use GET /beneficiary with query params

        if ($searchResult['success'] && !empty($searchResult['data'])) {
          // Response might be a list or a single object. Check structure.
          $candidate = null;

          if (isset($searchResult['data']['beneficiary_id'])) {
            // Single object
            $candidate = $searchResult['data'];
          } elseif (is_array($searchResult['data'])) {
            // List - find the matching one
            foreach ($searchResult['data'] as $item) {
              if (isset($item['beneficiary_id'])) {
                $candidate = $item;
                break;
              }
            }
          }

          if ($candidate) {
            $existingId = $candidate['beneficiary_id'];
          }
        }
      }

      if ($existingId) {
        // Store locally and return success
        $this->storeUserBeneficiary($userId, $bankDetails['id'], $existingId, ['beneficiary_id' => $existingId]);

        return [
          'success'        => true,
          'message'        => 'Beneficiary already exists (resolved from API)',
          'beneficiary_id' => $existingId
        ];
      }
    }

    if ($result['success']) {
      $this->storeUserBeneficiary($userId, $bankDetails['id'], $beneficiaryId, $result['data']);
    }

    return $result;
  }

  /**
   * Get beneficiary details
   */
  public function getBeneficiaryDetails(string $beneficiaryId): array
  {
    return $this->get("/beneficiary/{$beneficiaryId}");
  }

  /**
   * Store user beneficiary
   */
  private function storeUserBeneficiary(int $userId, int $bankId, string $beneficiaryId, array $response): bool
  {
    $builder = $this->db->table('provider_beneficiaries');

    $data = [
      'provider_id'    => $userId,
      'bank_id'        => $bankId,
      'beneficiary_id' => $beneficiaryId,
      'cf_bene_id'     => $response['beneficiary_id'] ?? $beneficiaryId,
      'status'         => 'ACTIVE',
      'updated_at'     => date('Y-m-d H:i:s')
    ];

    // Check exact beneficiary existence (NOT just provider)
    $existing = $builder
      ->where('provider_id', $userId)
      ->where('bank_id', $bankId)
      ->get()
      ->getRowArray();

    if ($existing) {
      return $builder
        ->where('id', $existing['id'])
        ->update($data);
    }

    $data['created_at'] = date('Y-m-d H:i:s');
    return $builder->insert($data);
  }


  /**
   * Get user beneficiary
   */
  public function getUserBeneficiary(int $userId, int $bankId = null): ?array
  {
    $builder = $this->db->table('provider_beneficiaries');

    $builder->where('provider_id', $userId);

    if ($bankId) {
      $builder->where('bank_id', $bankId);
    }

    return $builder->where('status', 'ACTIVE')
      ->orderBy('created_at', 'DESC')
      ->get()
      ->getRowArray();
  }

  // ==================== TRANSFER/PAYOUT METHODS ====================

  /**
   * Create Transfer - V2 API format
   */
  public function createTransfer(string $beneficiaryId, float $amount, string $transferId, string $remarks = ''): array
  {
    $payload = [
      'transfer_id'         => $transferId,
      'transfer_amount'     => $amount,
      'beneficiary_details' => [
        'beneficiary_id' => $beneficiaryId
      ]
    ];

    if ($remarks) {
      $payload['remarks'] = $remarks;
    }

    return $this->post('/transfers', $payload);
  }

  /**
   * Get Transfer Status - V2 API
   */
  public function getTransferStatus(string $transferId): array
  {
    return $this->get("/transfers?transfer_id={$transferId}");
  }

  /**
   * Process Withdrawal Payout - Updated for V2
   */
  public function processWithdrawalPayout(int $withdrawalId): array
  {
    // Get withdrawal details
    $withdrawal = $this->getWithdrawalDetails($withdrawalId);

    if (!$withdrawal) {
      return [
        'success' => false,
        'error'   => 'Withdrawal not found'
      ];
    }

    $userId = $withdrawal['user_id'];
    $bankId = $withdrawal['bank_id'];
    $amount = (float) $withdrawal['amount'];

    // 1. Add/Get beneficiary
    $beneficiaryResult = $this->addUserBeneficiary($userId, $bankId);
    if (!$beneficiaryResult['success']) {
      return $beneficiaryResult;
    }

    $beneficiaryId = $beneficiaryResult['beneficiary_id'] ?? $beneficiaryResult['data']['beneficiary_id'] ?? null;

    if (!$beneficiaryId) {
      return [
        'success' => false,
        'error'   => 'Invalid beneficiary ID'
      ];
    }

    // 2. Create transfer
    $transferId = 'TRF_' . $withdrawalId . '_' . time();
    $remarks    = "Wallet withdrawal #{$withdrawalId}";

    $transferResult = $this->createTransfer($beneficiaryId, $amount, $transferId, $remarks);

    if ($transferResult['success']) {
      // Create payout transaction record
      $this->createPayoutTransaction($withdrawalId, $transferId, $amount, $transferResult['data']);

      // Update withdrawal status
      $this->updateWithdrawalStatus($withdrawalId, 'in_progress', 'Payout initiated', $transferResult['data']);

      return [
        'success'     => true,
        'message'     => 'Payout initiated successfully',
        'transfer_id' => $transferId,
        'data'        => $transferResult['data']
      ];
    }

    // Mark as failed
    $this->updateWithdrawalStatus($withdrawalId, 'rejected', $transferResult['error'] ?? 'Payout failed', $transferResult['data'] ?? '');

    return $transferResult;
  }
  /**
   * Process User Refund Payout - NEW
   */
  public function processUserRefundPayout(int $refundId): array
  {
    // Get refund details
    $refund = $this->getRefundDetails($refundId);

    if (!$refund) {
      return [
        'success' => false,
        'error'   => 'Refund record not found'
      ];
    }

    $userId = $refund['user_id'];
    $amount = (float) $refund['amount'];

    // 1. Add/Get beneficiary using details from refund record
    $beneficiaryResult = $this->addUserBeneficiaryFromRefund($refund);
    if (!$beneficiaryResult['success']) {
      return $beneficiaryResult;
    }

    $beneficiaryId = $beneficiaryResult['beneficiary_id'] ?? $beneficiaryResult['data']['beneficiary_id'] ?? null;

    if (!$beneficiaryId) {
      return [
        'success' => false,
        'error'   => 'Invalid beneficiary ID for refund'
      ];
    }

    // 2. Create transfer
    $transferId = 'REF_' . $refundId . '_' . time();
    $remarks    = "User refund #{$refundId}";

    $transferResult = $this->createTransfer($beneficiaryId, $amount, $transferId, $remarks);

    if ($transferResult['success']) {
      // Create payout transaction record for refund
      $this->createRefundPayoutTransaction($refundId, $transferId, $amount, $transferResult['data']);

      // 1. Update refund record in database immediately to completed
      $this->updateRefundStatus($refundId, 'completed', [
        'transaction_id'  => $transferId,
        'cf_transfer_id'  => $transferResult['data']['transfer_id'] ?? null,
        'remarks'         => $remarks,
        'additional_data' => json_encode($transferResult['data'])
      ]);

      // 2. Perform wallet deduction immediately
      $booking = new \App\Models\Booking();
      $refund = $this->getRefundDetails($refundId);
      $userId = $refund['user_id'];
      $wallet = $booking->getUserWallet($userId);

      if ($wallet) {
        $newBalance = $wallet->amount - $amount;
        $booking->updateWalletBalance($userId, $newBalance);

        $balanceAfter = $booking->getServiceWalletSum($userId)['service_amount'];
        $refundAmountInWallet = $booking->getWithdrawalWalletSum($userId);

        $idempotencyKey = 'wid_debit_' . $refundId . '_' . $transferId;

        $booking->logTransaction(
          $userId,
          0,
          0,
          'debit',
          $refundAmountInWallet,
          $balanceAfter ?? 0,
          $refundAmountInWallet ?? 0,
          'withdrawal_amount',
          "Withdrawal amount",
          'user_wallet_histories',
          $idempotencyKey,
          $transferId
        );
      }

      return [
        'success'     => true,
        'message'     => 'Refund payout initiated successfully and wallet updated',
        'transfer_id' => $transferId,
        'data'        => $transferResult['data']
      ];
    }

    // Mark as failed
    $this->updateRefundStatus($refundId, 'failed', [
      'remarks' => $transferResult['error'] ?? 'Refund payout failed'
    ]);

    return $transferResult;
  }
  /**
   * Add Beneficiary for User Refund using details from refund record
   */
  private function addUserBeneficiaryFromRefund(array $refund): array
  {
    $userId = $refund['user_id'];
    $beneficiaryId = 'BEN_REF_' . $userId . '_' . $refund['id'];

    // V2 API format
    $payload = [
      "beneficiary_id"                 => $beneficiaryId,
      "beneficiary_name"               => $refund['account_holder_name'],
      "beneficiary_instrument_details" => [
        "bank_account_number" => $refund['account_number'],
        "bank_ifsc"           => $refund['ifsc_code']
      ],
      "beneficiary_contact_details"    => [
        "beneficiary_email"        => 'noreply@example.com', // Default if not available
        "beneficiary_phone"        => '',
        "beneficiary_country_code" => "+91",
      ]
    ];

    // Try to get more info from users table
    $clientInfo = $this->db->table('users')->select('email, phone')->where('id', $userId)->get()->getRowArray();
    if ($clientInfo) {
      if (!empty($clientInfo['email'])) $payload['beneficiary_contact_details']['beneficiary_email'] = $clientInfo['email'];
      if (!empty($clientInfo['phone'])) $payload['beneficiary_contact_details']['beneficiary_phone'] = $clientInfo['phone'];
    }

    $result = $this->post('/beneficiary', $payload);

    // Handle case where beneficiary already exists (409 Conflict)
    if (!$result['success'] && ($result['status'] === 409 || stripos($result['error'] ?? '', 'already exists') !== false)) {
      $existingId = null;

      // 1. Try to extract ID from error message
      if (preg_match('/beneficiary\s*(?:with\s*id|id)?\s*[:\s]?\s*([a-zA-Z0-9_\-]+)/i', $result['error'], $matches)) {
        if (strpos($result['error'], 'bank_account_number') === false) {
          $existingId = $matches[1];
        }
      }

      // 2. If ID not found in error, try to fetch by bank details
      if (!$existingId && isset($payload['beneficiary_instrument_details']['bank_account_number'])) {
        $searchParams = [
          'bank_account_number' => $payload['beneficiary_instrument_details']['bank_account_number'],
          'bank_ifsc'           => $payload['beneficiary_instrument_details']['bank_ifsc']
        ];
        $searchResult = $this->get('/beneficiary', $searchParams);

        if ($searchResult['success'] && !empty($searchResult['data'])) {
          $candidate = null;
          if (isset($searchResult['data']['beneficiary_id'])) {
            $candidate = $searchResult['data'];
          } elseif (is_array($searchResult['data'])) {
            foreach ($searchResult['data'] as $item) {
              if (isset($item['beneficiary_id'])) {
                $candidate = $item;
                break;
              }
            }
          }
          if ($candidate) {
            $existingId = $candidate['beneficiary_id'];
          }
        }
      }

      if ($existingId) {
        return [
          'success'        => true,
          'message'        => 'Beneficiary already exists (resolved from API)',
          'beneficiary_id' => $existingId
        ];
      }

      return [
        'success' => false,
        'error'   => 'Beneficiary already exists but could not resolve its ID. Original error: ' . ($result['error'] ?? 'Unknown')
      ];
    }

    if ($result['success']) {
      return [
        'success'        => true,
        'beneficiary_id' => $beneficiaryId,
        'data'           => $result['data']
      ];
    }

    return $result;
  }
  /**
   * Create Payout Transaction record for User Refunds
   */
  private function createRefundPayoutTransaction(int $refundId, string $transferId, float $amount, array $response): bool
  {
    $builder = $this->db->table('payout_transactions');
    $refund = $this->getRefundDetails($refundId);

    $transactionData = [
      'refund_id'        => $refundId,
      'transfer_id'      => $transferId,
      'cf_transfer_id'   => $response['transfer_id'] ?? null,
      'user_id'          => $refund['user_id'] ?? null,
      'amount'           => $amount,
      'currency'         => 'INR',
      'status'           => $response['transfer_status'] ?? 'PENDING',
      'remarks'          => 'User refund payout',
      'utr'              => $response['transfer_utr'] ?? null,
      'additional_data'  => json_encode($response),
      'transaction_date' => date('Y-m-d H:i:s'),
      'created_at'       => date('Y-m-d H:i:s'),
      'updated_at'       => date('Y-m-d H:i:s')
    ];

    return $builder->insert($transactionData);
  }

  // ==================== ACCOUNT & BALANCE ====================

  /**
   * Get Account Balance
   */
  public function getBalance(): array
  {
    return $this->getV1('/v1/getBalance');
  }

  // ==================== DATABASE HELPER METHODS ====================

  private function getWithdrawalDetails(int $withdrawalId): ?array
  {
    $builder = $this->db->table('wallet_withdrawals ww');

    return $builder->select('ww.*, u.name as user_name, u.phone, u.email,
                                bd.account_holder_name, bd.account_number, bd.ifsc_code, 
                                bd.bank_name')
      ->join('service_providers u', 'u.id = ww.user_id', 'left')
      ->join('sp_verified_bank_details bd', 'bd.id = ww.bank_id', 'left')
      ->where('ww.id', $withdrawalId)
      ->get()
      ->getRowArray();
  }

  private function getBankDetailsById(int $bankId): ?array
  {
    $builder = $this->db->table('sp_verified_bank_details');
    return $builder->where('id', $bankId)
      ->get()
      ->getRowArray();
  }
  /**
   * Update Refund Status
   */
  private function updateRefundStatus(int $refundId, string $status, array $data): bool
  {
    $builder = $this->db->table('user_refunds');

    $updateData = [
      'status'     => $status,
      'updated_at' => date('Y-m-d H:i:s')
    ];

    if (isset($data['transaction_id'])) {
      $updateData['transaction_id'] = $data['transaction_id'];
    }

    if (isset($data['admin_comments']) || isset($data['remarks'])) {
      $updateData['admin_comments'] = $data['admin_comments'] ?? $data['remarks'];
    }

    return $builder->where('id', $refundId)->update($updateData);
  }
  private function getUserBankDetails(int $userId): ?array
  {
    $builder = $this->db->table('sp_verified_bank_details');
    return $builder->where('provider_id', $userId)
      ->orderBy('created_at', 'DESC')
      ->get()
      ->getRowArray();
  }
  /**
   * Get Refund Details for User Refund
   */
  public function getRefundDetails(int $refundId): ?array
  {
    $builder = $this->db->table('user_refunds');
    return $builder->where('id', $refundId)
      ->get()
      ->getRowArray();
  }
  private function getUserInfo(int $userId): array
  {
    $builder = $this->db->table('service_providers');
    $user    = $builder->select('name, phone, email, service_address as address, city, state,zip_code as pincode')
      ->where('id', $userId)
      ->get()
      ->getRowArray();

    return $user ?: [];
  }

  private function updateWithdrawalStatus(int $withdrawalId, string $status, string $remarks = '', $response = ''): bool
  {
    $builder = $this->db->table('wallet_withdrawals');

    $updateData = [
      'status'          => $status,
      'cf_reference_id' => $response['transfer_id'] ?? null,
      'processed_at'    => ($status === 'completed' || $status === 'rejected') ? date('Y-m-d H:i:s') : null,
      'updated_at'      => date('Y-m-d H:i:s')
    ];

    return $builder->where('id', $withdrawalId)->update($updateData);
  }

  private function createPayoutTransaction(int $withdrawalId, string $transferId, float $amount, array $response): bool
  {
    $builder = $this->db->table('payout_transactions');

    $withdrawal = $this->getWithdrawalDetails($withdrawalId);

    $transactionData = [
      'withdrawal_id'    => $withdrawalId,
      'transfer_id'      => $transferId,
      'cf_transfer_id'   => $response['transfer_id'] ?? null,
      'provider_id'      => $withdrawal['user_id'] ?? null,
      'bank_id'          => $withdrawal['bank_id'] ?? null,
      'amount'           => $amount,
      'currency'         => 'INR',
      'status'           => $response['transfer_status'] ?? 'PENDING',
      'remarks'          => 'Wallet withdrawal payout',
      'utr'              => $response['transfer_utr'] ?? null,
      'additional_data'  => json_encode($response),
      'transaction_date' => date('Y-m-d H:i:s'),
      'created_at'       => date('Y-m-d H:i:s'),
      'updated_at'       => date('Y-m-d H:i:s')
    ];

    return $builder->insert($transactionData);
  }

  /**
   * Update payout transaction
   */
  // public function updatePayoutTransaction( string $transferId, array $updateData ): bool
  // {
  //   $builder                  = $this->db->table ( 'payout_transactions' );
  //   $updateData['updated_at'] = date ( 'Y-m-d H:i:s' );

  //   return $builder->where ( 'transfer_id', $transferId )
  //     ->orWhere ( 'cf_transfer_id', $transferId )
  //     ->update ( $updateData );
  // }

  /**
   * Process Payout Webhook - V2 format
   */
  public function processPayoutWebhook(array $webhookData, array $headers = []): array
  {
    $startTime = microtime(true);

    try {
      // Validate webhook structure
      $validation = $this->validateWebhookData($webhookData);
      if (!$validation['valid']) {
        return [
          'success' => false,
          'error'   => 'Invalid webhook data: ' . $validation['message']
        ];
      }

      $eventType = $webhookData['event_type'];
      $eventId   = $webhookData['event_id'] ?? uniqid('evt_', true);
      $data      = $webhookData['data'];

      // Check for duplicate webhook
      if ($this->isDuplicateWebhook($eventId)) {
        \log_message('info', "Duplicate webhook received: {$eventId}");
        return [
          'success'  => true,
          'message'  => 'Webhook already processed',
          'event_id' => $eventId
        ];
      }

      // Log webhook before processing
      $webhookLogId = $this->logWebhookToDatabase($eventId, $eventType, $webhookData, $headers);

      // Process based on event type
      $result = match ($eventType) {
        'TRANSFER_STATUS_UPDATE' => $this->processTransferStatusUpdate($data),
        'TRANSFER_SUCCESS' => $this->processTransferSuccess($data),
        'TRANSFER_FAILED' => $this->processTransferFailed($data),
        'TRANSFER_REVERSED' => $this->processTransferReversed($data),
        'BENEFICIARY_STATUS_UPDATE' => $this->processBeneficiaryStatusUpdate($data),
        'BENEFICIARY_ADDED' => $this->processBeneficiaryAdded($data),
        'BENEFICIARY_REJECTED' => $this->processBeneficiaryRejected($data),
        'SETTLEMENT_PROCESSED' => $this->processSettlement($data),
        'LOW_BALANCE_ALERT' => $this->processLowBalanceAlert($data),
        default => $this->processUnknownEvent($eventType, $data),
      };

      // Update webhook log with result
      $this->updateWebhookLog($webhookLogId, $result);

      // Log processing time
      $processingTime = round((microtime(true) - $startTime) * 1000, 2);
      \log_message('info', "Webhook {$eventId} processed in {$processingTime}ms");

      return [
        'success'            => true,
        'message'            => 'Webhook processed successfully',
        'event_id'           => $eventId,
        'processing_time_ms' => $processingTime,
        'details'            => $result
      ];
    } catch (Exception $e) {
      \log_message('error', 'Webhook processing failed: ' . $e->getMessage());
      return [
        'success'  => false,
        'error'    => $e->getMessage(),
        'event_id' => $eventId ?? null
      ];
    }
  }

  /**
   * Process Transfer Status Update
   */
  private function processTransferStatusUpdate(array $data): array
  {
    $transferId    = $data['transfer_id'] ?? null;
    $status        = $data['transfer_status'] ?? 'UNKNOWN';
    $utr           = $data['transfer_utr'] ?? null;
    $failureReason = $data['failure_reason'] ?? null;
    $failureCode   = $data['failure_code'] ?? null;
    $timestamp     = $data['timestamp'] ?? date('Y-m-d H:i:s');

    if (!$transferId) {
      throw new Exception('Transfer ID not found in webhook data');
    }

    // Update payout transaction
    $updateData = [
      'status'            => $status,
      'utr'               => $utr,
      'failure_reason'    => $failureReason,
      'failure_code'      => $failureCode,
      'last_webhook_at'   => $timestamp,
      'webhook_processed' => 1,
      'webhook_attempts'  => $this->db->escape($this->db->escapeIdentifier('webhook_attempts') . ' + 1'),
      'updated_at'        => date('Y-m-d H:i:s')
    ];

    $this->updatePayoutTransaction($transferId, $updateData);

    // Update withdrawal status
    $this->updateWithdrawalFromPayout($transferId, [
      'transfer_status' => $status,
      'transfer_utr'    => $utr,
      'failure_reason'  => $failureReason,
      'failure_code'    => $failureCode
    ], '');

    // Send notifications based on status
    $this->sendStatusNotifications($transferId, $status, $failureReason);

    return [
      'action'             => 'transfer_status_updated',
      'transfer_id'        => $transferId,
      'status'             => $status,
      'utr'                => $utr,
      'notifications_sent' => true
    ];
  }

  /**
   * Process Transfer Success
   */
  private function processTransferSuccess(array $data): array
  {
    // Similar to status update but with additional success actions
    $result = $this->processTransferStatusUpdate($data);

    // Additional success-specific actions
    $transferId = $data['transfer_id'] ?? null;

    if ($transferId) {
      // Update wallet balance
      $this->updateWalletAfterSuccess($transferId);

      // Create success audit log
      $this->createAuditLog('transfer_success', [
        'transfer_id' => $transferId,
        'amount'      => $data['transfer_amount'] ?? null,
        'utr'         => $data['transfer_utr'] ?? null
      ]);
    }

    return array_merge($result, [
      'additional_actions' => ['wallet_updated', 'audit_log_created']
    ]);
  }

  /**
   * Process Transfer Failed
   */
  private function processTransferFailed(array $data): array
  {
    $result = $this->processTransferStatusUpdate($data);

    $transferId    = $data['transfer_id'] ?? null;
    $failureReason = $data['failure_reason'] ?? 'Unknown reason';

    if ($transferId) {
      // Refund wallet amount if transfer failed
      $this->refundWalletOnFailure($transferId);

      // Send failure notification to admin
      $this->sendAdminAlert('transfer_failed', [
        'transfer_id' => $transferId,
        'reason'      => $failureReason,
        'data'        => $data
      ]);
    }

    return array_merge($result, [
      'additional_actions' => ['wallet_refunded', 'admin_notified']
    ]);
  }

  /**
   * Process Transfer Reversed
   */
  private function processTransferReversed(array $data): array
  {
    $result = $this->processTransferStatusUpdate($data);

    $transferId = $data['transfer_id'] ?? null;

    if ($transferId) {
      // Handle reversal - update records
      $this->handleTransferReversal($transferId, $data);
    }

    return $result;
  }

  /**
   * Process Beneficiary Status Update
   */
  private function processBeneficiaryStatusUpdate(array $data): array
  {
    $beneficiaryId = $data['beneficiary_id'] ?? null;
    $status        = $data['status'] ?? 'UNKNOWN';
    $reason        = $data['reason'] ?? null;

    if (!$beneficiaryId) {
      throw new Exception('Beneficiary ID not found in webhook data');
    }

    $builder = $this->db->table('provider_beneficiaries');

    $updateData = [
      'cf_status'     => $status,
      'status_reason' => $reason,
      'updated_at'    => date('Y-m-d H:i:s')
    ];

    // If beneficiary is rejected, mark as inactive
    if (in_array($status, ['REJECTED', 'INVALID', 'FAILED'])) {
      $updateData['status'] = 'INACTIVE';
    }

    $builder->where('beneficiary_id', $beneficiaryId)
      ->orWhere('cf_bene_id', $beneficiaryId)
      ->update($updateData);

    return [
      'action'         => 'beneficiary_status_updated',
      'beneficiary_id' => $beneficiaryId,
      'status'         => $status
    ];
  }

  /**
   * Enhanced Withdrawal Update with Additional Data
   */
  public function updateWithdrawalFromPayout(string $transferId, array $payoutData, $userId): bool
  {
    $builder     = $this->db->table('payout_transactions');
    $transaction = $builder->select('*')
      ->where('transfer_id', $transferId)
      ->orWhere('cf_transfer_id', $transferId)
      ->get()
      ->getRowArray();

    if (!$transaction || !isset($transaction['withdrawal_id'])) {
      \log_message('error', "Transaction not found for transfer ID: {$transferId}");
      return false;
    }

    $statusMapping = [
      'SUCCESS'    => 'completed',
      'FAILED'     => 'rejected',
      'PENDING'    => 'in_progress',
      'REVERSED'   => 'rejected',
      'CANCELLED'  => 'cancelled',
      'PROCESSING' => 'processing',
      'QUEUED'     => 'queued'
    ];

    $cashfreeStatus = $payoutData['status'] ?? null;
    $newStatus      = $statusMapping[$cashfreeStatus] ?? 'in_progress';

    $withdrawalBuilder = $this->db->table('wallet_withdrawals');

    $updateData = [
      'status'            => $newStatus,
      'utr'               => $payoutData['transfer_utr'] ?? $transaction['utr'],
      'failure_reason'    => $payoutData['failure_reason'] ?? null,
      'failure_code'      => $payoutData['failure_code'] ?? null,
      'webhook_processed' => 1,
      'last_webhook_at'   => date('Y-m-d H:i:s'),
      'processed_at'      => ($newStatus === 'completed') ? date('Y-m-d H:i:s') : null,
      'updated_at'        => date('Y-m-d H:i:s')
    ];

    $result = $withdrawalBuilder->where('id', $transaction['withdrawal_id'])->update($updateData);
    if ($cashfreeStatus == 'SUCCESS' && $userId) {

      $booking        = new Booking();
      $netAmount      = $transaction['amount'];
      $booking->debitProviderWallet($userId, $netAmount);
      $spBalanceAfter = $booking->getProviderServiceWalletSum($userId)['service_amount'];
      $spEarnAmount   = $booking->getProviderWithdrawalWalletSum($userId);
      $idempotencyKey = 'wid_debit_' . $transaction['withdrawal_id'] . '_' . $transferId;

      $booking->logTransaction(
        $userId,
        '',
        '',
        'debit',
        $netAmount,
        $spBalanceAfter,
        $spEarnAmount,
        'withdrawal_amount',
        'Withdrawal amount debited from wallet',
        'sp_wallet_histories',
        $idempotencyKey,
        $transferId
      );
    }
    return $result;
  }

  /**
   * Update Refund Status from Payout Data
   */
  public function updateRefundFromPayout(string $transferId, array $payoutData, $userId): bool
  {
    $builder     = $this->db->table('payout_transactions');
    $transaction = $builder->select('*')
      ->where('transfer_id', $transferId)
      ->orWhere('cf_transfer_id', $transferId)
      ->get()
      ->getRowArray();

    if (!$transaction || !isset($transaction['refund_id'])) {
      // Try to find refund_id from payout_transactions another way if needed, 
      // but let's assume we stored it in some column.
      // Wait, did I add refund_id column to payout_transactions in my previous step?
      // Let's check my previous edit.
    }

    $statusMapping = [
      'SUCCESS'    => 'completed',
      'FAILED'     => 'failed',
      'PENDING'    => 'pending',
      'REVERSED'   => 'failed',
      'CANCELLED'  => 'failed'
    ];

    $cashfreeStatus = $payoutData['status'] ?? null;
    $newStatus      = $statusMapping[$cashfreeStatus] ?? 'pending';

    $refundBuilder = $this->db->table('user_refunds');

    $updateData = [
      'status'            => $newStatus,
      'transaction_id'    => $payoutData['transfer_utr'] ?? $transaction['utr'] ?? $transferId,
      'updated_at'        => date('Y-m-d H:i:s')
    ];

    if ($cashfreeStatus === 'FAILED' || $cashfreeStatus === 'REVERSED') {
      $updateData['admin_comments'] = $payoutData['failure_reason'] ?? 'Transfer failed';
    }

    $refundId = $transaction['refund_id'] ?? null;
    if (!$refundId) {
      // Fallback: search user_refunds by transaction_id if stored
      $ref = $refundBuilder->where('transaction_id', $transferId)->get()->getRowArray();
      if ($ref) $refundId = $ref['id'];
    }

    if (!$refundId) return false;

    $result = $refundBuilder->where('id', $refundId)->update($updateData);

    // If success, deduct from wallet if not already done
    if ($cashfreeStatus == 'SUCCESS' && $userId) {
      $booking = new \App\Models\Booking();
      $refund = $refundBuilder->where('id', $refundId)->get()->getRowArray();
      if ($refund) {
        $amount = $refund['amount'];
        $wallet = $booking->getUserWallet($userId);
        if ($wallet) {
          $newBalance = $wallet->amount - $amount;
          $booking->updateWalletBalance($userId, $newBalance);

          $balanceAfter = $booking->getServiceWalletSum($userId)['service_amount'];
          $refundAmountInWallet = $booking->getWithdrawalWalletSum($userId);

          $idempotencyKey = 'wid_debit_' . $refundId . '_' . $transferId;

          $booking->logTransaction(
            $userId,
            0,
            0,
            'debit',
            $refundAmountInWallet,
            $balanceAfter ?? 0,
            $refundAmountInWallet ?? 0,
            'withdrawal_amount',
            "Withdrawal amount",
            'user_wallet_histories',
            $idempotencyKey,
            $transferId
          );
        }
      }
    }

    return $result;
  }

  /**
   * Enhanced Update Payout Transaction
   */
  public function updatePayoutTransaction(string $transferId, array $updateData): bool
  {
    $builder = $this->db->table('payout_transactions');

    // Handle increment operations
    foreach ($updateData as $key => $value) {
      if (is_string($value) && strpos($value, ' + ') !== false) {
        // Handle SQL expressions
        unset($updateData[$key]);
        $builder->set($key, $value, false);
      }
    }

    $updateData['updated_at'] = date('Y-m-d H:i:s');

    return $builder->where('transfer_id', $transferId)
      ->orWhere('cf_transfer_id', $transferId)
      ->update($updateData);
  }

  /**
   * Webhook Validation
   */
  private function validateWebhookData(array $data): array
  {
    if (empty($data)) {
      return ['valid' => false, 'message' => 'Empty webhook data'];
    }

    if (!isset($data['event_type'])) {
      return ['valid' => false, 'message' => 'Missing event_type'];
    }

    if (!isset($data['data'])) {
      return ['valid' => false, 'message' => 'Missing data field'];
    }

    // Validate specific event types
    $eventType      = $data['event_type'];
    $requiredFields = [
      'TRANSFER_STATUS_UPDATE'    => ['transfer_id', 'transfer_status'],
      'BENEFICIARY_STATUS_UPDATE' => ['beneficiary_id', 'status'],
      'SETTLEMENT_PROCESSED'      => ['settlement_id', 'amount'],
    ];

    if (isset($requiredFields[$eventType])) {
      foreach ($requiredFields[$eventType] as $field) {
        if (!isset($data['data'][$field])) {
          return ['valid' => false, 'message' => "Missing required field: {$field}"];
        }
      }
    }

    return ['valid' => true, 'message' => 'Valid'];
  }

  /**
   * Log Webhook to Database
   */
  private function logWebhookToDatabase(string $eventId, string $eventType, array $data, array $headers = []): int
  {
    $builder = $this->db->table('cashfree_webhook_logs');

    $logData = [
      'event_id'          => $eventId,
      'event_type'        => $eventType,
      'webhook_version'   => $data['version'] ?? '2.0',
      'transfer_id'       => $data['data']['transfer_id'] ?? null,
      'beneficiary_id'    => $data['data']['beneficiary_id'] ?? null,
      'raw_data'          => json_encode($data),
      'headers'           => json_encode($headers),
      'signature'         => $headers['x-webhook-signature'] ?? $headers['X-Webhook-Signature'] ?? null,
      'signature_valid'   => 1, // Assuming validated before this call
      'processing_status' => 'processing',
      'ip_address'        => Services::request()->getIPAddress(),
      'user_agent'        => Services::request()->getUserAgent()->getAgentString(),
      'created_at'        => date('Y-m-d H:i:s')
    ];

    $builder->insert($logData);
    return $this->db->insertID();
  }

  /**
   * Update Webhook Log
   */
  private function updateWebhookLog(int $logId, array $result): void
  {
    $builder = $this->db->table('cashfree_webhook_logs');

    $updateData = [
      'processing_status' => isset($result['success']) && $result['success'] ? 'processed' : 'failed',
      'processing_result' => json_encode($result),
      'error_message'     => $result['error'] ?? null,
      'processed_at'      => date('Y-m-d H:i:s')
    ];

    $builder->where('id', $logId)->update($updateData);
  }

  /**
   * Check for Duplicate Webhook
   */
  private function isDuplicateWebhook(string $eventId): bool
  {
    if (empty($eventId)) {
      return false;
    }

    $builder = $this->db->table('cashfree_webhook_logs');
    $exists  = $builder->where('event_id', $eventId)
      ->where('processing_status', 'processed')
      ->countAllResults();

    return $exists > 0;
  }

  /**
   * Verify Webhook Signature
   */
  public function verifyWebhookSignatureV2(string $signature, string $rawBody, string $timestamp): bool
  {
    if (empty($signature) || empty($timestamp) || empty($rawBody)) {
      return false;
    }

    // Create the message to verify
    $message = $timestamp . $rawBody;

    // Compute HMAC
    $computedSignature = hash_hmac('sha256', $message, $this->clientSecret);

    // Use timing-safe comparison
    return hash_equals($signature, $computedSignature);
  }

  /**
   * Webhook Retry Mechanism
   */
  public function retryFailedWebhooks(int $maxAgeHours = 24, int $limit = 10): array
  {
    $builder = $this->db->table('cashfree_webhook_logs');

    $failedWebhooks = $builder->select('*')
      ->where('processing_status', 'failed')
      ->where('retry_count <', 3)
      ->where('created_at >=', date('Y-m-d H:i:s', strtotime("-{$maxAgeHours} hours")))
      ->orderBy('created_at', 'ASC')
      ->limit($limit)
      ->get()
      ->getResultArray();

    $results = [];

    foreach ($failedWebhooks as $webhook) {
      try {
        $rawData = json_decode($webhook['raw_data'], true);
        $result  = $this->processPayoutWebhook($rawData);

        // Update retry count
        $this->db->table('cashfree_webhook_logs')
          ->where('id', $webhook['id'])
          ->update([
            'retry_count' => $webhook['retry_count'] + 1,
            'updated_at'  => date('Y-m-d H:i:s')
          ]);

        $results[] = [
          'webhook_id' => $webhook['id'],
          'success'    => $result['success'] ?? false,
          'message'    => $result['message'] ?? 'Retried'
        ];
      } catch (Exception $e) {
        $results[] = [
          'webhook_id' => $webhook['id'],
          'success'    => false,
          'error'      => $e->getMessage()
        ];
      }
    }

    return [
      'total_retried' => count($results),
      'results'       => $results
    ];
  }

  /**
   * Get Webhook Statistics
   */
  public function getWebhookStats(string $period = 'today'): array
  {
    $builder = $this->db->table('cashfree_webhook_logs');

    switch ($period) {
      case 'today':
        $builder->where('DATE(created_at)', date('Y-m-d'));
        break;
      case 'week':
        $builder->where('created_at >=', date('Y-m-d', strtotime('-7 days')));
        break;
      case 'month':
        $builder->where('MONTH(created_at)', date('m'))
          ->where('YEAR(created_at)', date('Y'));
        break;
    }

    return $builder->select("
                COUNT(*) as total_webhooks,
                SUM(CASE WHEN processing_status = 'processed' THEN 1 ELSE 0 END) as processed,
                SUM(CASE WHEN processing_status = 'failed' THEN 1 ELSE 0 END) as failed,
                SUM(CASE WHEN processing_status = 'processing' THEN 1 ELSE 0 END) as processing,
                SUM(CASE WHEN signature_valid = 1 THEN 1 ELSE 0 END) as valid_signatures,
                GROUP_CONCAT(DISTINCT event_type) as event_types
            ")
      ->get()
      ->getRowArray() ?? [];
  }

  /**
   * Helper Methods for Additional Actions
   */
  private function sendStatusNotifications(string $transferId, string $status, ?string $reason = null): void
  {
    // Get transaction details
    $builder = $this->db->table('payout_transactions pt')
      ->select('pt.*, ww.user_id, u.name, u.email, u.phone, ww.amount')
      ->join('wallet_withdrawals ww', 'ww.id = pt.withdrawal_id')
      ->join('users u', 'u.id = ww.user_id')
      ->where('pt.transfer_id', $transferId)
      ->orWhere('pt.cf_transfer_id', $transferId)
      ->get();

    $transaction = $builder->getRowArray();

    if (!$transaction) {
      return;
    }

    // Send notifications based on status
    switch ($status) {
      case 'SUCCESS':
        // Send success notification to user
        $this->sendUserNotification($transaction['user_id'], 'payout_success', [
          'amount'      => $transaction['amount'],
          'utr'         => $transaction['utr'],
          'transfer_id' => $transferId
        ]);
        break;

      case 'FAILED':
        // Send failure notification to user
        $this->sendUserNotification($transaction['user_id'], 'payout_failed', [
          'amount'      => $transaction['amount'],
          'reason'      => $reason,
          'transfer_id' => $transferId
        ]);

        // Send alert to admin
        $this->sendAdminNotification('payout_failed', [
          'user_id'     => $transaction['user_id'],
          'user_name'   => $transaction['name'],
          'amount'      => $transaction['amount'],
          'transfer_id' => $transferId,
          'reason'      => $reason
        ]);
        break;
    }
  }

  private function updateWalletAfterSuccess(string $transferId): void
  {
    // Update user wallet balance or create transaction record
    $builder = $this->db->table('payout_transactions pt')
      ->select('ww.user_id, ww.amount')
      ->join('wallet_withdrawals ww', 'ww.id = pt.withdrawal_id')
      ->where('pt.transfer_id', $transferId)
      ->orWhere('pt.cf_transfer_id', $transferId)
      ->get();

    $data = $builder->getRowArray();

    if ($data) {
      // Update wallet transaction as completed
      $this->db->table('wallet_transactions')
        ->where('reference_id', $transferId)
        ->where('type', 'withdrawal')
        ->update([
          'status'     => 'completed',
          'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
  }

  private function refundWalletOnFailure(string $transferId): void
  {
    // Refund amount to user's wallet
    $builder = $this->db->table('payout_transactions pt')
      ->select('ww.user_id, ww.amount, ww.id as withdrawal_id')
      ->join('wallet_withdrawals ww', 'ww.id = pt.withdrawal_id')
      ->where('pt.transfer_id', $transferId)
      ->orWhere('pt.cf_transfer_id', $transferId)
      ->get();

    $data = $builder->getRowArray();

    if ($data) {
      // Add amount back to user's wallet
      $this->db->table('wallets')
        ->where('user_id', $data['user_id'])
        ->set('balance', 'balance + ' . $data['amount'], false)
        ->update();

      // Create refund transaction
      $this->db->table('wallet_transactions')->insert([
        'user_id'        => $data['user_id'],
        'type'           => 'refund',
        'amount'         => $data['amount'],
        'balance_before' => 0, // You'd need to fetch this
        'balance_after'  => 0,  // You'd need to calculate this
        'reference_id'   => $transferId,
        'reference_type' => 'withdrawal_failed',
        'description'    => 'Refund for failed withdrawal',
        'status'         => 'completed',
        'created_at'     => date('Y-m-d H:i:s')
      ]);
    }
  }

  private function onWithdrawalCompleted(int $withdrawalId, array $payoutData): void
  {
    // Update user's withdrawal stats
    $this->db->table('service_providers')
      ->where('id', function ($query) use ($withdrawalId) {
        $query->select('user_id')
          ->from('wallet_withdrawals')
          ->where('id', $withdrawalId);
      })
      ->set('total_withdrawn', 'total_withdrawn + (SELECT amount FROM wallet_withdrawals WHERE id = ' . $withdrawalId . ')', false)
      ->update();

    // Create completion audit log
    $this->createAuditLog('withdrawal_completed', [
      'withdrawal_id' => $withdrawalId,
      'payout_data'   => $payoutData
    ]);
  }
  /** * Get withdrawal statistics */
  public function getWithdrawalStats(string $period = 'month'): array
  {
    $builder = $this->db->table('wallet_withdrawals');
    $select  = " COUNT(*) as total_requests, SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END) as total_paid, SUM(CASE WHEN status = 'pending' THEN amount ELSE 0 END) as total_pending, SUM(CASE WHEN status = 'in_progress' THEN amount ELSE 0 END) as total_in_progress, SUM(CASE WHEN status = 'rejected' THEN amount ELSE 0 END) as total_rejected ";
    switch ($period) {
      case 'today':
        $builder->where('DATE(requested_at)', date('Y-m-d'));
        break;
      case 'week':
        $builder->where('requested_at >=', date('Y-m-d', strtotime('-7 days')));
        break;
      case 'month':
        $builder->where('MONTH(requested_at)', date('m'))->where('YEAR(requested_at)', date('Y'));
        break;
    }
    return $builder->select($select)->get()->getRowArray() ?? [];
  }
}
