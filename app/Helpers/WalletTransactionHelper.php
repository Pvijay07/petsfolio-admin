<?php

namespace App\Helpers;

use CodeIgniter\Database\ConnectionInterface;

class WalletTransactionHelper
{
    protected $db;
    
    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    /**
     * Process Walking Service Payment
     * 
     * @param int $bookingId
     * @param int $userId
     * @param int $providerId
     * @param string $status ('approved', 'rejected', 'cancelled')
     * @param float $walkAmount
     * @param int $totalPets
     * @return bool
     */
    public function processWalkingPayment($bookingId, $userId, $providerId, $status, $walkAmount, $totalPets = 1)
    {
        $platformPercentage = (float)getenv('PLATFORM_PERCENTAGE') ?? 10;
        $totalWalkAmount = $walkAmount * $totalPets;
        $platformCharges = ($totalWalkAmount * $platformPercentage) / 100;
        $providerAmount = $totalWalkAmount - $platformCharges;
        
        $this->db->transStart();
        
        try {
            switch ($status) {
                case 'approved':
                    // Debit from client service wallet
                    $this->debitClientServiceWallet($userId, $totalWalkAmount, $bookingId, 'walking', 'Walk approved');
                    
                    // Credit to provider service wallet
                    $this->creditProviderServiceWallet($providerId, $providerAmount, $bookingId, 'walking', 'Walk completed');
                    
                    // Record platform charges
                    $this->recordPlatformCharges($bookingId, 'walking', $platformCharges);
                    break;
                    
                case 'rejected':
                    // Debit from client service wallet
                    $this->debitClientServiceWallet($userId, $totalWalkAmount, $bookingId, 'walking', 'Walk rejected');
                    
                    // Credit to client withdrawal wallet (refund)
                    $this->creditClientWithdrawalWallet($userId, $totalWalkAmount, $bookingId, 'walking', 'Walk rejected - refund');
                    break;
                    
                case 'cancelled':
                    // Get remaining balance from client service wallet
                    $remainingBalance = $this->getClientServiceWalletBalance($userId, 'walking');
                    
                    if ($remainingBalance > 0) {
                        // Debit all remaining from client service wallet
                        $this->debitClientServiceWallet($userId, $remainingBalance, $bookingId, 'walking', 'Service cancelled');
                        
                        // Credit to client withdrawal wallet
                        $this->creditClientWithdrawalWallet($userId, $remainingBalance, $bookingId, 'walking', 'Service cancelled - full refund');
                    }
                    
                    // Clear provider service wallet to withdrawal wallet
                    $providerBalance = $this->getProviderServiceWalletBalance($providerId, 'walking');
                    if ($providerBalance > 0) {
                        $this->debitProviderServiceWallet($providerId, $providerBalance, $bookingId, 'walking', 'Service cancelled');
                        $this->creditProviderWithdrawalWallet($providerId, $providerBalance, $bookingId, 'walking', 'Service cancelled - transfer to withdrawal');
                    }
                    break;
            }
            
            $this->db->transComplete();
            return $this->db->transStatus();
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Walking payment processing error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Process Grooming Service Payment
     * 
     * @param int $bookingId
     * @param int $userId
     * @param int $providerId
     * @param float $addonPrice
     * @param string $addonName
     * @param bool $isPackageIncluded
     * @return bool
     */
    public function processGroomingPayment($bookingId, $userId, $providerId, $addonPrice, $addonName, $isPackageIncluded = false)
    {
        $platformPercentage = (float)getenv('PLATFORM_PERCENTAGE') ?? 10;
        $platformCharges = ($addonPrice * $platformPercentage) / 100;
        $providerAmount = $addonPrice - $platformCharges;
        
        $this->db->transStart();
        
        try {
            $description = $isPackageIncluded ? "Package service: {$addonName}" : "Addon: {$addonName}";
            
            // Debit from client service wallet
            $this->debitClientServiceWallet($userId, $addonPrice, $bookingId, 'grooming', $description);
            
            // Credit to provider service wallet
            $this->creditProviderServiceWallet($providerId, $providerAmount, $bookingId, 'grooming', $description);
            
            // Record platform charges
            $this->recordPlatformCharges($bookingId, 'grooming', $platformCharges, $addonName);
            
            $this->db->transComplete();
            return $this->db->transStatus();
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Grooming payment processing error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Process Training Service Payment
     * 
     * @param int $bookingId
     * @param int $userId
     * @param int $providerId
     * @param float $commandPrice
     * @param string $commandName
     * @param bool $isPackageCommand
     * @param float $discount
     * @return bool
     */
    public function processTrainingPayment($bookingId, $userId, $providerId, $commandPrice, $commandName, $isPackageCommand = false, $discount = 0)
    {
        $platformPercentage = (float)getenv('PLATFORM_PERCENTAGE') ?? 10;
        
        // Apply discount
        $discountAmount = ($discount > 0) ? ($commandPrice * $discount) / 100 : 0;
        $finalClientAmount = $commandPrice - $discountAmount;
        
        // Calculate platform charges and provider amount
        $platformCharges = ($finalClientAmount * $platformPercentage) / 100;
        $providerAmount = $finalClientAmount - $platformCharges;
        
        $this->db->transStart();
        
        try {
            $description = $isPackageCommand ? "Package command: {$commandName}" : "Extra command: {$commandName}";
            
            // Debit from client service wallet
            $this->debitClientServiceWallet($userId, $finalClientAmount, $bookingId, 'training', $description);
            
            // Credit to provider service wallet
            $this->creditProviderServiceWallet($providerId, $providerAmount, $bookingId, 'training', $description);
            
            // Credit to provider withdrawal wallet (ready for withdrawal)
            $this->creditProviderWithdrawalWallet($providerId, $providerAmount, $bookingId, 'training', $description);
            
            // Record platform charges
            $this->recordPlatformCharges($bookingId, 'training', $platformCharges, $commandName);
            
            $this->db->transComplete();
            return $this->db->transStatus();
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Training payment processing error: ' . $e->getMessage());
            return false;
        }
    }

    // ========== Wallet Operations ==========

    private function debitClientServiceWallet($userId, $amount, $bookingId, $serviceType, $description)
    {
        // Update wallet balance
        $this->db->table('user_service_wallet')
            ->where('user_id', $userId)
            ->set("{$serviceType}_amount", "{$serviceType}_amount - {$amount}", false)
            ->update();
        
        // Record transaction history
        $this->db->table('user_wallet_histories')->insert([
            'user_id' => $userId,
            'booking_id' => $bookingId,
            'transaction_type' => 'debit',
            'wallet_type' => 'service',
            'service_type' => $serviceType,
            'amount' => $amount,
            'description' => $description,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function creditClientWithdrawalWallet($userId, $amount, $bookingId, $serviceType, $description)
    {
        // Update wallet balance
        $this->db->table('user_withdrawal_wallet')
            ->where('user_id', $userId)
            ->set('amount', "amount + {$amount}", false)
            ->update();
        
        // Record transaction history
        $this->db->table('user_wallet_histories')->insert([
            'user_id' => $userId,
            'booking_id' => $bookingId,
            'transaction_type' => 'credit',
            'wallet_type' => 'withdrawal',
            'service_type' => $serviceType,
            'amount' => $amount,
            'description' => $description,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function creditProviderServiceWallet($providerId, $amount, $bookingId, $serviceType, $description)
    {
        // Update wallet balance
        $this->db->table('sp_service_wallet')
            ->where('provider_id', $providerId)
            ->set("{$serviceType}_amount", "{$serviceType}_amount + {$amount}", false)
            ->update();
        
        // Record transaction history
        $this->db->table('sp_wallet_histories')->insert([
            'provider_id' => $providerId,
            'booking_id' => $bookingId,
            'transaction_type' => 'credit',
            'wallet_type' => 'service',
            'service_type' => $serviceType,
            'amount' => $amount,
            'description' => $description,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function debitProviderServiceWallet($providerId, $amount, $bookingId, $serviceType, $description)
    {
        // Update wallet balance
        $this->db->table('sp_service_wallet')
            ->where('provider_id', $providerId)
            ->set("{$serviceType}_amount", "{$serviceType}_amount - {$amount}", false)
            ->update();
        
        // Record transaction history
        $this->db->table('sp_wallet_histories')->insert([
            'provider_id' => $providerId,
            'booking_id' => $bookingId,
            'transaction_type' => 'debit',
            'wallet_type' => 'service',
            'service_type' => $serviceType,
            'amount' => $amount,
            'description' => $description,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function creditProviderWithdrawalWallet($providerId, $amount, $bookingId, $serviceType, $description)
    {
        // Update wallet balance
        $this->db->table('sp_withdrawal_wallet')
            ->where('provider_id', $providerId)
            ->set('amount', "amount + {$amount}", false)
            ->update();
        
        // Record transaction history
        $this->db->table('sp_wallet_histories')->insert([
            'provider_id' => $providerId,
            'booking_id' => $bookingId,
            'transaction_type' => 'credit',
            'wallet_type' => 'withdrawal',
            'service_type' => $serviceType,
            'amount' => $amount,
            'description' => $description,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function recordPlatformCharges($bookingId, $serviceType, $amount, $description = '')
    {
        $this->db->table('platform_charges')->insert([
            'booking_id' => $bookingId,
            'service_type' => $serviceType,
            'amount' => $amount,
            'description' => $description,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function getClientServiceWalletBalance($userId, $serviceType)
    {
        $result = $this->db->table('user_service_wallet')
            ->select("{$serviceType}_amount")
            ->where('user_id', $userId)
            ->get()
            ->getRow();
        
        return $result ? (float)$result->{"{$serviceType}_amount"} : 0;
    }

    private function getProviderServiceWalletBalance($providerId, $serviceType)
    {
        $result = $this->db->table('sp_service_wallet')
            ->select("{$serviceType}_amount")
            ->where('provider_id', $providerId)
            ->get()
            ->getRow();
        
        return $result ? (float)$result->{"{$serviceType}_amount"} : 0;
    }
}