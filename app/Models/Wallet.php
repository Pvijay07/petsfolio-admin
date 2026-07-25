<?php

namespace App\Models;

use CodeIgniter\Model;
use DateTime;
use DateTimeZone;

class Wallet extends Model
{
  protected $user_service_wallet;
  protected $user_wallet_histories;
  protected $sp_wallets;
  protected $sp_wallet_histories;
  protected $transactions;

  public function __construct()
  {
    parent::__construct();
    $this->user_service_wallet = 'user_service_wallet';
    $this->user_wallet_histories = 'user_wallet_histories';
    $this->sp_wallets = 'sp_withdrawal_wallet';
    $this->sp_wallet_histories = 'sp_wallet_histories';
    $this->transactions = 'transactions';
  }
  public function createUserWallet($user_id)
  {
    $wallet = [
      'user_id' => $user_id,
      'balance' => 0,
      'created_at' => gmdate('Y-m-d H:i:s')
    ];
    $this->db->table($this->user_service_wallet)->insert($wallet);
    return $this->db->insertID();
  }
  // Function to get service wallet sum
  public function getServiceWalletSum($user_id)
  {
    $serviceAmountResult = $this->db->table($this->user_service_wallet)
      ->selectSum('walking_amount', 'walking_amount')
      ->selectSum('training_amount', 'training_amount')
      ->selectSum('boarding_amount', 'boarding_amount')
      ->selectSum('grooming_amount', 'grooming_amount')
      ->selectSum('sitting_amount', 'sitting_amount')

      ->where('user_id', $user_id)
      ->get()
      ->getRow();

    $totalServiceAmount = [
      'service_amount' => ($serviceAmountResult->walking_amount ?? 0) +
        ($serviceAmountResult->training_amount ?? 0) +
        ($serviceAmountResult->boarding_amount ?? 0) +
        ($serviceAmountResult->sitting_amount ?? 0) +
        ($serviceAmountResult->grooming_amount ?? 0),
      'walking_amount' => $serviceAmountResult->walking_amount ?? 0,
      'training_amount' => $serviceAmountResult->training_amount ?? 0,
      'boarding_amount' => $serviceAmountResult->boarding_amount ?? 0,
      'grooming_amount' => $serviceAmountResult->grooming_amount ?? 0,
      'sitting_amount' => $serviceAmountResult->sitting_amount ?? 0,

    ];

    return $totalServiceAmount;
  }


  // Function to get withdrawal wallet sum
  public function getWithdrawalWalletSum($user_id)
  {
    $withdrawalAmountResult = $this->db->table('user_withdrawal_wallet')
      ->selectSum('amount', 'withdrawal_amount')
      ->where('user_id', $user_id)
      ->get()
      ->getRow();

    return $withdrawalAmountResult->withdrawal_amount ?? 0;
  }

  // Function to get wallet transactions
  public function getUserWalletTransactions($user_id)
  {
    return $this->db->table('user_wallet_histories')
      ->select('user_wallet_histories.*')
      ->where('user_wallet_histories.user_id', $user_id)

      ->orderBy('id', 'DESC')
      ->get()
      ->getResult();
  }

  public function getUserWalletDetails($user_id)
  {
    $service_amount = $this->getServiceWalletSum($user_id);
    $withdrawal_amount = $this->getWithdrawalWalletSum($user_id);
    $transactions = $this->getUserWalletTransactions($user_id);

    $result = [
      'withdrawal_amount' => $withdrawal_amount,
      'service_amount' => $service_amount,
      'transactions' => []
    ];

    $calcuttaTZ = new DateTimeZone('Asia/Calcutta');

    foreach ($transactions as $transaction) {

      $utcTime = new DateTime($transaction->created_at, new DateTimeZone('UTC'));
      $utcTime->setTimezone($calcuttaTZ);
      $transaction->created_at = $utcTime->format('Y-m-d h:i:s');

      $result['transactions'][] = [
        'amount' => $transaction->amount,
        'transaction_type' => $transaction->transaction_type,
        'description' => $transaction->description,
        'created_at' => $transaction->created_at,
      ];
    }

    return $result;
  }

  public function addMoney($data)
  {
    $this->db->table($this->user_wallets)
      ->where('user_id', $data->user_id)
      ->set('balance', 'balance+' . $data->amount, false)
      ->set('updated_at', gmdate('Y-m-d H:i:s'))
      ->update();

    $transactionData = [
      'wallet_id' => $data->wallet_id,
      'amount' => $data->amount,
      'transaction_type' => 'credit',
      'transaction_for' => 'add_money',
      'description' => 'Money added to wallet',
      'transaction_at' => date('Y-m-d H:i:s'),
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s')
    ];
    return $this->db->table($this->user_wallet_histories)
      ->insert($transactionData);
  }
  public function createProviderWallet($provider_id)
  {
    $wallet = [
      'provider_id' => $provider_id,
      'amount' => 0,
      'created_at' => gmdate('Y-m-d H:i:s')
    ];
    $this->db->table($this->sp_wallets)->insert($wallet);
    return $this->db->insertID();
  }
  public function getProviderServiceWalletSum($provider_id)
  {
    $serviceAmountResult = $this->db->table('sp_service_wallet')
      ->selectSum('walking_amount', 'walking_amount')
      ->selectSum('training_amount', 'training_amount')
      ->selectSum('boarding_amount', 'boarding_amount')
      ->selectSum('grooming_amount', 'grooming_amount')
      ->selectSum('sitting_amount', 'sitting_amount')
      ->where('provider_id', $provider_id)
      ->get()
      ->getRow();


    $totalServiceAmount = [
      'service_amount' => ($serviceAmountResult->walking_amount ?? 0) +
        ($serviceAmountResult->training_amount ?? 0) +
        ($serviceAmountResult->boarding_amount ?? 0) +
        ($serviceAmountResult->sitting_amount ?? 0) +
        ($serviceAmountResult->grooming_amount ?? 0),
      'walking_amount' => $serviceAmountResult->walking_amount ?? 0,
      'training_amount' => $serviceAmountResult->training_amount ?? 0,
      'boarding_amount' => $serviceAmountResult->boarding_amount ?? 0,
      'grooming_amount' => $serviceAmountResult->grooming_amount ?? 0,
      'sitting_amount' => $serviceAmountResult->sitting_amount ?? 0
    ];

    return $totalServiceAmount;
  }
  public function getProviderWithdrawalWalletSum($provider_id)
  {
    $withdrawalAmountResult = $this->db->table('sp_withdrawal_wallet')
      ->selectSum('amount', 'withdrawal_amount')
      ->where('provider_id', $provider_id)
      ->get()
      ->getRow();

    return $withdrawalAmountResult->withdrawal_amount ?? 0;
  }
  public function getProviderWalletTransactions($provider_id)
  {
    return $this->db->table('sp_wallet_histories')
      ->select('sp_wallet_histories.*')
      ->where('sp_wallet_histories.provider_id', $provider_id)

      ->orderBy('id', 'DESC')
      ->get()
      ->getResult();
  }

  public function getProviderWalletDetails($provider_id)
  {
    $service_amount = $this->getProviderServiceWalletSum($provider_id);
    $withdrawal_amount = $this->getProviderWithdrawalWalletSum($provider_id);
    $transactions = $this->getProviderWalletTransactions($provider_id);
    $withdrawal = $this->getPaymentsRequests($provider_id);
    $banks = $this->getProviderBank($provider_id);
    $actualEarningAmount = $withdrawal_amount;

    $result = [
      'withdrawal_amount' => $withdrawal_amount,
      'service_wallet' => $service_amount,
      'transactions' => [],
      'withdrawal_status' => $withdrawal->withdrawal_status ?? '',
      'banks' => !empty($banks) ? true : false,
      'actualEarningAmount' => $actualEarningAmount ?? 0
    ];
    $calcuttaTZ = new DateTimeZone('Asia/Calcutta');

    foreach ($transactions as $transaction) {

      $utcTime = new DateTime($transaction->created_at, new DateTimeZone('UTC'));
      $utcTime->setTimezone($calcuttaTZ);
      $transaction->created_at = $utcTime->format('Y-m-d h:i:s');

      $result['transactions'][] = [
        'amount' => $transaction->amount,
        'transaction_type' => $transaction->transaction_type,
        'description' => $transaction->description,
        'created_at' => $transaction->created_at,
      ];
    }

    return $result;
  }
  public function getProviderBank($provider_id)
  {
    return $this->db->table('sp_verified_bank_details')
      ->select('sp_verified_bank_details.*')
      ->where('sp_verified_bank_details.provider_id', $provider_id)
      ->get()
      ->getRow();
  }
  public function getPaymentsRequests($provider_id)
  {
    $data = $this->db->table('wallet_withdrawals')
      ->select('wallet_withdrawals.status as withdrawal_status')
      ->join('sp_bank_details', 'sp_bank_details.provider_id = wallet_withdrawals.user_id', 'left')

      ->whereIn('wallet_withdrawals.status', ['pending', 'in_progress'])
      ->where('wallet_withdrawals.amount>', 0)
      ->where('wallet_withdrawals.user_id', $provider_id)
      ->get()
      ->getRow();
    return $data;
  }
  public function getUserWallet($userId)
  {
    return $this->db->table('user_wallets')->where('user_id', $userId)->get()->getRow();
  }

  public function updateWallet($user_id, $wallet)
  {
    $this->db->table($this->user_wallets)

      ->where('user_id', $user_id)
      ->set('balance', 'balance + ' . $wallet['amount'], false)
      ->update();

    $this->db->table($this->user_wallet_histories)
      ->insert($wallet);
  }
  public function createTransaction($data)
  {
    return $this->db->table($this->transactions)->insert($data);
  }
  public function updateTransaction($data)
  {
    return $this->db->table($this->transactions)
      ->set('status', $data['status'])
      ->set('payment_mode', $data['payment_mode'])
      ->set('response_code', $data['response_code'])
      ->set('transaction_date', $data['transaction_date'])
      ->where('transaction_id', $data['transaction_id'])
      ->update();
  }
  public function getTransactionByTransactionId($transaction_id)
  {
    return $this->db->table('transactions')->where('transaction_id', $transaction_id)->get()->getRow();
  }

  public function checkPetService($user_id, $pet_id)
  {
    return $this->db->table($this->user_service_wallet)
      ->where('user_id', $user_id)
      ->where('pet_id', $pet_id)
      ->get()
      ->getRow();
  }
  public function checkProviderPetService($provider_id, $pet_id)
  {
    return $this->db->table('sp_service_wallet')
      ->where('provider_id', $provider_id)
      ->where('pet_id', $pet_id)
      ->get()
      ->getRow();
  }
  public function updateProviderWalkingWalletAmount($wallet)
  {
    return $this->db->table('sp_service_wallet')

      ->set('walking_amount', 'walking_amount+' . $wallet['walking_amount'], false)
      ->where('provider_id', $wallet['provider_id'])
      ->where('pet_id', $wallet['pet_id'])
      ->update();
  }

  public function updateProviderGroomingWalletAmount($wallet)
  {
    $builder = $this->db->table('sp_service_wallet');
    $builder->set('grooming_amount', 'grooming_amount+' . $wallet['grooming_amount'], false);
    $builder->where('provider_id', $wallet['provider_id']);
    $builder->where('pet_id', $wallet['pet_id']);

    $result = $builder->update();

    return $result;
  }

  public function addProviderWalkingWalletAmount($wallet)
  {
    return $this->db->table('sp_service_wallet')
      ->set('walking_amount', 'walking_amount+' . $wallet['walking_amount'], false)
      ->set('provider_id', $wallet['provider_id'])
      ->set('pet_id', $wallet['pet_id'])
      ->insert();
  }
  public function addProviderGroomingWalletAmount($wallet)
  {
    return $this->db->table('sp_service_wallet')
      ->set('grooming_amount', 'grooming_amount+' . $wallet['grooming_amount'], false)
      ->set('provider_id', $wallet['provider_id'])
      ->set('pet_id', $wallet['pet_id'])
      ->insert();
  }

  public function addProviderTrainingWalletAmount($wallet)
  {
    return $this->db->table('sp_service_wallet')
      ->set('training_amount', 'training_amount+' . $wallet['training_amount'], false)
      ->set('provider_id', $wallet['provider_id'])
      ->set('pet_id', $wallet['pet_id'])
      ->insert();
  }
  public function updateProviderTrainingWalletAmount($wallet)
  {
    $builder = $this->db->table('sp_service_wallet');
    $builder->set('training_amount', 'training_amount+' . $wallet['training_amount'], false);
    $builder->where('provider_id', $wallet['provider_id']);
    $builder->where('pet_id', $wallet['pet_id']);

    $result = $builder->update();

    return $result;
  }


  public function getServiceWallet($user_id, $pet_id)
  {
    return $this->db->table($this->user_service_wallet)
      ->select('user_service_wallet.walking_amount as balance')
      ->where('user_id', $user_id)
      ->where('pet_id', $pet_id)
      ->get()
      ->getRow();
  }
  public function getWithdrawlWallet($user_id, $pet_id)
  {
    return $this->db->table('user_withdrawal_wallet')
      ->select('user_withdrawal_wallet.amount as balance')
      ->where('user_id', $user_id)
      // ->where('pet_id', $pet_id)
      ->get()
      ->getRow();
  }
  public function debitWallet($user_id, $pet_id, $clientDebitAmount)
  {
    return $this->db->table($this->user_service_wallet)
      ->set('walking_amount', 'walking_amount-' . $clientDebitAmount, false)
      ->where('user_id', $user_id)
      ->where('pet_id', $pet_id)
      ->update();
  }
  public function debitWithdrawlWallet($user_id, $pet_id, $clientDebitAmount)
  {
    return $this->db->table('user_withdrawal_wallet')
      ->set('amount', 'amount-' . $clientDebitAmount, false)
      ->where('user_id', $user_id)
      // ->where('pet_id', $pet_id)
      ->update();
  }
  public function creditWallet($provider_id, $providerCreditAmount)
  {
    // Check if the provider_id exists in the table
    $exists = $this->db->table('sp_withdrawal_wallet')
      ->where('provider_id', $provider_id)
      ->countAllResults();

    if ($exists > 0) {
      // Update the amount if the provider_id exists
      return $this->db->table('sp_withdrawal_wallet')
        ->set('amount', 'amount+' . $providerCreditAmount, false)
        ->where('provider_id', $provider_id)
        ->update();
    } else {
      // Insert a new record if the provider_id does not exist
      $data = [
        'provider_id' => $provider_id,
        'amount' => $providerCreditAmount,
      ];
      return $this->db->table('sp_withdrawal_wallet')
        ->insert($data);
    }
  }



  public function logTransaction($user_id, $serviceType, $booking_id, $type, $amount, $balanceAfter, $refundAmount, $category, $desc, $table, $idempotencyKey = null, $txnId = null)
  {
    $idField = ($table == 'user_wallet_histories') ? 'user_id' : 'provider_id';
    $walletField = ($table == 'user_wallet_histories') ? 'refund_balance' : 'earned_balance';

    // Fetch previous checksum for this user
    $prev = $this->db->table($table)
      ->select('checksum')
      ->where($idField, $user_id)
      ->orderBy('id', 'DESC')
      ->get()
      ->getRow('checksum');

    // Prepare the core data string
    $dataString = implode('|', [
      $user_id,
      $serviceType,
      $booking_id,
      $type,
      number_format($amount, 2, '.', ''),
      number_format($balanceAfter, 2, '.', ''),
      number_format($refundAmount, 2, '.', ''),
      $category,
      $desc,
      $idempotencyKey ?? '',
      $txnId ?? '',
      $prev ?? ''
    ]);

    // Generate new checksum using SHA256
    $checksum = hash('sha256', $dataString);

    // Insert with checksum chain
    return $this->db->table($table)->insert([
      $idField => $user_id,
      'booking_id' => $booking_id,
      'service_id' => $serviceType,
      'transaction_type' => $type,
      'amount' => $amount,
      'balance_after' => $balanceAfter,
      $walletField => $refundAmount,
      'category' => $category,
      'description' => $desc,
      'idempotency_key' => $idempotencyKey,
      'txn_id' => $txnId,
      'prev_checksum' => $prev,
      'checksum' => $checksum
    ]);
  }

  public function logPlatformTransaction($booking_id, $provider_id, $user_id, $serviceType, $amount, $type, $desc, $table, $idempotencyKey = null, $txnId = null)
  {
    return $this->db->table($table)
      ->set('provider_id', $provider_id)
      ->set('user_id', $user_id)
      ->set('booking_id', $booking_id)
      ->set('service_type', $serviceType)
      ->set('transaction_type', $type)
      ->set('commission_amount', $amount)
      ->set('description', $desc)
      ->set('idempotency_key', $idempotencyKey)
      ->set('txn_id', $txnId)
      ->insert();
  }

  public function addRefundAmount($user_id, $amount)
  {
    return $this->db->table('user_withdrawal_wallet')
      ->set('amount', 'amount+' . $amount, false)
      ->set('refund_reason', 'cancel')
      ->where('user_id', $user_id)
      ->update();
  }
  public function debitRefundAmount($user_id, $amount, $pet_id)
  {
    return $this->db->table('user_service_wallet')
      ->set('walking_amount', 'walking_amount-' . $amount, false)
      ->where('pet_id', $pet_id)
      ->where('user_id', $user_id)
      ->update();
  }
  public function debitSpRefundAmount($provider_id, $amount, $pet_id)
  {
    return $this->db->table('sp_service_wallet')
      ->set('walking_amount', 'walking_amount-' . $amount, false)
      ->where('pet_id', $pet_id)
      ->where('provider_id', $provider_id)
      ->update();
  }


  public function debitGroomingRefundAmount($user_id, $amount, $pet_id)
  {
    return $this->db->table('user_service_wallet')
      ->set('grooming_amount', 'grooming_amount-' . $amount, false)
      ->where('pet_id', $pet_id)
      ->where('user_id', $user_id)
      ->update();
  }
  public function debitSpGroomingRefundAmount($provider_id, $amount, $pet_id)
  {
    return $this->db->table('sp_service_wallet')
      ->set('grooming_amount', 'grooming_amount-' . $amount, false)
      ->where('pet_id', $pet_id)
      ->where('provider_id', $provider_id)
      ->update();
  }

  public function creditRefundWallet($user_id, $amount)
  {
    return $this->db->table('user_withdrawal_wallet')
      ->set('amount', 'amount+' . $amount, false)
      ->where('user_id', $user_id)
      ->update();
  }
  public function checkUserWallet($id)
  {
    return $this->db->table('user_withdrawal_wallet')
      ->where('user_id', $id)
      ->get()->getRow();
  }
  public function checkProviderWallet($id)
  {
    return $this->db->table('sp_withdrawal_wallet')
      ->where('provider_id', $id)
      ->get()->getRow();
  }
  public function getTotalWithdrawalAmount($id)
  {
    return $this->db->table('sp_withdrawal_wallet')
      ->select('amount')
      ->where('provider_id', $id)
      ->get()->getRow();
  }

  public function debitProviderWallet($provider_id, $DebitAmount)
  {
    return $this->db->table('sp_withdrawal_wallet')
      ->set('amount', 'amount-' . $DebitAmount, false)
      ->where('provider_id', $provider_id)
      ->update();
  }
  public function debitProviderServiceWallet($provider_id, $pet_id, $DebitAmount)
  {
    return $this->db->table('sp_service_wallet')
      ->set('walking_amount', 'walking_amount-' . $DebitAmount, false)
      ->where('provider_id', $provider_id)
      ->where('pet_id', $pet_id)
      ->update();
  }
  public function createUserWWallet($user_id)
  {
    $wallet = [
      'user_id' => $user_id,
      'amount' => 0,
      'created_at' => gmdate('Y-m-d H:i:s')
    ];
    $this->db->table('user_withdrawal_wallet')->insert($wallet);
    return $this->db->insertID();
  }
  public function getPaymentStatus($transactionId)
  {
    return $this->db->table('transactions')
      ->select('transactions.status')
      ->where('transaction_id', $transactionId)
      ->get()->getRow();
  }
  public function updateWithdrawalWallet($user_id, $newBalance)
  {
    return $this->db->table('user_withdrawal_wallet')
      ->where('user_id', $user_id)
      ->update(['amount' => $newBalance]);
  }
  public function getWithdrawlAmount($user_id)
  {
    return $this->db->table('user_withdrawal_wallet')
      ->where('user_id', $user_id)
      ->get()->getRow();
  }


  public function updateWalletAmount(array $data, string $serviceType, string $table)
  {
    $validTypes = ['walking', 'grooming', 'training', 'boarding'];
    if (!in_array(strtolower($serviceType), $validTypes)) {
      throw new \InvalidArgumentException('Invalid service type');
    }
    if ($table === 'user_service_wallet') {
      $where = [
        'user_id' => $data['user_id'],
        'pet_id' => $data['pet_id']
      ];
      $column = $serviceType . '_amount';
    } elseif ($table === 'sp_service_wallet') {
      $where = [
        'provider_id' => $data['provider_id'],
        'pet_id' => $data['pet_id']
      ];
      $column = $serviceType . '_amount';
    } elseif ($table === 'user_withdrawal_wallet') {
      $where = ['user_id' => $data['user_id']];
      $column = 'amount';
    } elseif ($table === 'sp_withdrawal_wallet') {
      $where = ['provider_id' => $data['provider_id']];
      $column = 'amount';
    } else {
      throw new \InvalidArgumentException('Invalid wallet table');
    }

    $builder = $this->db->table($table);
    $record = $builder->where($where)->get()->getRow();

    if ($record) {
      // For existing records, update with the amount (can be positive or negative)
      return $builder->set($column, $column . ' + ' . $data[$column], false)
        ->where($where)
        ->update();
    }

    // For new records, ensure we don't insert negative amounts
    $insertData = array_merge($where, [$column => max($data[$column], 0)]);
    return $builder->insert($insertData);
  }
  public function updateWalkingWalletAmount($wallet)
  {
    return $this->db->table('user_service_wallet')

      ->set('walking_amount', 'walking_amount+' . $wallet['walking_amount'], false)
      ->where('user_id', $wallet['user_id'])
      ->where('pet_id', $wallet['pet_id'])
      ->update();
  }
  public function addWalkingWalletAmount($wallet)
  {
    return $this->db->table('user_service_wallet')
      ->set('walking_amount', 'walking_amount+' . $wallet['walking_amount'], false)
      ->set('user_id', $wallet['user_id'])
      ->set('pet_id', $wallet['pet_id'])
      ->insert();
  }
}
