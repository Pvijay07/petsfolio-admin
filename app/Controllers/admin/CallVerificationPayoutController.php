<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;

class CallVerificationPayoutController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->ensureTablesExist();
    }

    /**
     * Ensure executive_call_records, executive_payouts, executive_wallets, and executive_wallet_transactions tables exist
     * and have the required columns for Registration Executives & Verification Executives segregation.
     */
    protected function ensureTablesExist()
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `executive_call_records` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `executive_id` INT UNSIGNED NOT NULL,
                `executive_type` ENUM('register', 'verification') NOT NULL DEFAULT 'verification',
                `unit_type` VARCHAR(50) NOT NULL DEFAULT 'call',
                `provider_id` INT UNSIGNED NOT NULL,
                `service_id` INT UNSIGNED NOT NULL,
                `source_table` VARCHAR(50) NOT NULL,
                `source_id` INT UNSIGNED DEFAULT NULL,
                `call_date` DATE NOT NULL,
                `payout_status` ENUM('pending', 'paid') DEFAULT 'pending',
                `payout_id` INT UNSIGNED DEFAULT NULL,
                `rate` DECIMAL(8,2) DEFAULT 0.00,
                `created_at` DATETIME NOT NULL,
                `updated_at` DATETIME DEFAULT NULL,
                INDEX `idx_exec_status` (`executive_id`, `payout_status`),
                INDEX `idx_exec_type_status` (`executive_type`, `executive_id`, `payout_status`),
                INDEX `idx_call_date` (`call_date`),
                UNIQUE KEY `unique_source_call` (`source_table`, `source_id`, `provider_id`, `service_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        if ($this->db->tableExists('executive_call_records')) {
            if (!$this->db->fieldExists('executive_type', 'executive_call_records')) {
                $this->db->query("ALTER TABLE `executive_call_records` ADD COLUMN `executive_type` ENUM('register', 'verification') NOT NULL DEFAULT 'verification' AFTER `service_id`");
            }
            if (!$this->db->fieldExists('unit_type', 'executive_call_records')) {
                $this->db->query("ALTER TABLE `executive_call_records` ADD COLUMN `unit_type` VARCHAR(50) NOT NULL DEFAULT 'call' AFTER `executive_type`");
            }
        }

        $this->db->query("
            CREATE TABLE IF NOT EXISTS `executive_payouts` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `executive_id` INT UNSIGNED NOT NULL,
                `executive_type` ENUM('register', 'verification') NOT NULL DEFAULT 'verification',
                `unit_type` VARCHAR(50) NOT NULL DEFAULT 'call',
                `total_calls` INT NOT NULL DEFAULT 0,
                `amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `total_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `bank_detail_id` INT UNSIGNED DEFAULT NULL,
                `bank_name` VARCHAR(150) DEFAULT NULL,
                `account_number` VARCHAR(100) DEFAULT NULL,
                `ifsc_code` VARCHAR(50) DEFAULT NULL,
                `account_holder_name` VARCHAR(150) DEFAULT NULL,
                `upi_id` VARCHAR(100) DEFAULT NULL,
                `payout_date` DATETIME DEFAULT NULL,
                `status` ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending',
                `transaction_ref` VARCHAR(255) DEFAULT NULL,
                `daily_breakdown` JSON DEFAULT NULL,
                `remarks` TEXT DEFAULT NULL,
                `created_at` DATETIME NOT NULL,
                `updated_at` DATETIME DEFAULT NULL,
                INDEX `idx_exec_payout` (`executive_id`, `payout_date`),
                INDEX `idx_exec_type_payout` (`executive_type`, `executive_id`, `payout_date`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        if ($this->db->tableExists('executive_payouts')) {
            if (!$this->db->fieldExists('executive_id', 'executive_payouts')) {
                $this->db->query("ALTER TABLE `executive_payouts` ADD COLUMN `executive_id` INT UNSIGNED NOT NULL AFTER `id`");
            }
            if (!$this->db->fieldExists('executive_type', 'executive_payouts')) {
                $this->db->query("ALTER TABLE `executive_payouts` ADD COLUMN `executive_type` ENUM('register', 'verification') NOT NULL DEFAULT 'verification' AFTER `executive_id`");
            }
            if (!$this->db->fieldExists('unit_type', 'executive_payouts')) {
                $this->db->query("ALTER TABLE `executive_payouts` ADD COLUMN `unit_type` VARCHAR(50) NOT NULL DEFAULT 'call' AFTER `executive_type`");
            }
            if (!$this->db->fieldExists('total_calls', 'executive_payouts')) {
                $this->db->query("ALTER TABLE `executive_payouts` ADD COLUMN `total_calls` INT NOT NULL DEFAULT 0 AFTER `executive_type`");
            }
            if (!$this->db->fieldExists('amount', 'executive_payouts')) {
                $this->db->query("ALTER TABLE `executive_payouts` ADD COLUMN `amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER `total_calls`");
            }
            if (!$this->db->fieldExists('total_amount', 'executive_payouts')) {
                $this->db->query("ALTER TABLE `executive_payouts` ADD COLUMN `total_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER `amount`");
            }
            if (!$this->db->fieldExists('bank_detail_id', 'executive_payouts')) {
                $this->db->query("ALTER TABLE `executive_payouts` ADD COLUMN `bank_detail_id` INT UNSIGNED DEFAULT NULL AFTER `total_amount`");
            }
            if (!$this->db->fieldExists('bank_name', 'executive_payouts')) {
                $this->db->query("ALTER TABLE `executive_payouts` ADD COLUMN `bank_name` VARCHAR(150) DEFAULT NULL AFTER `bank_detail_id`");
            }
            if (!$this->db->fieldExists('account_number', 'executive_payouts')) {
                $this->db->query("ALTER TABLE `executive_payouts` ADD COLUMN `account_number` VARCHAR(100) DEFAULT NULL AFTER `bank_name`");
            }
            if (!$this->db->fieldExists('ifsc_code', 'executive_payouts')) {
                $this->db->query("ALTER TABLE `executive_payouts` ADD COLUMN `ifsc_code` VARCHAR(50) DEFAULT NULL AFTER `account_number`");
            }
            if (!$this->db->fieldExists('account_holder_name', 'executive_payouts')) {
                $this->db->query("ALTER TABLE `executive_payouts` ADD COLUMN `account_holder_name` VARCHAR(150) DEFAULT NULL AFTER `ifsc_code`");
            }
            if (!$this->db->fieldExists('upi_id', 'executive_payouts')) {
                $this->db->query("ALTER TABLE `executive_payouts` ADD COLUMN `upi_id` VARCHAR(100) DEFAULT NULL AFTER `account_holder_name`");
            }
            if (!$this->db->fieldExists('payout_date', 'executive_payouts')) {
                $this->db->query("ALTER TABLE `executive_payouts` ADD COLUMN `payout_date` DATETIME DEFAULT NULL AFTER `upi_id`");
            }
            if (!$this->db->fieldExists('status', 'executive_payouts')) {
                $this->db->query("ALTER TABLE `executive_payouts` ADD COLUMN `status` ENUM('pending', 'paid', 'approved', 'processing', 'completed', 'rejected', 'cancelled') NOT NULL DEFAULT 'pending' AFTER `payout_date`");
            } else {
                $this->db->query("ALTER TABLE `executive_payouts` MODIFY COLUMN `status` ENUM('pending', 'paid', 'approved', 'processing', 'completed', 'rejected', 'cancelled') NOT NULL DEFAULT 'pending'");
            }
            if (!$this->db->fieldExists('transaction_ref', 'executive_payouts')) {
                $this->db->query("ALTER TABLE `executive_payouts` ADD COLUMN `transaction_ref` VARCHAR(255) DEFAULT NULL AFTER `status`");
            }
            if (!$this->db->fieldExists('daily_breakdown', 'executive_payouts')) {
                $this->db->query("ALTER TABLE `executive_payouts` ADD COLUMN `daily_breakdown` JSON DEFAULT NULL AFTER `transaction_ref`");
            }
            if (!$this->db->fieldExists('remarks', 'executive_payouts')) {
                $this->db->query("ALTER TABLE `executive_payouts` ADD COLUMN `remarks` TEXT DEFAULT NULL AFTER `daily_breakdown`");
            }
        }

        $this->db->query("
            CREATE TABLE IF NOT EXISTS `executive_wallets` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `executive_id` INT UNSIGNED NOT NULL UNIQUE,
                `balance` DECIMAL(12,2) DEFAULT 0.00,
                `total_earned` DECIMAL(12,2) DEFAULT 0.00,
                `total_withdrawn` DECIMAL(12,2) DEFAULT 0.00,
                `created_at` DATETIME NOT NULL,
                `updated_at` DATETIME DEFAULT NULL,
                INDEX `idx_exec_id` (`executive_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS `executive_wallet_transactions` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `wallet_id` INT UNSIGNED NOT NULL,
                `executive_id` INT UNSIGNED NOT NULL,
                `executive_type` ENUM('register', 'verification') NOT NULL DEFAULT 'verification',
                `payout_id` INT UNSIGNED DEFAULT NULL,
                `type` ENUM('credit', 'debit') NOT NULL DEFAULT 'credit',
                `amount` DECIMAL(10,2) NOT NULL,
                `balance_after` DECIMAL(12,2) NOT NULL,
                `description` VARCHAR(255) NOT NULL,
                `reference_no` VARCHAR(100) DEFAULT NULL,
                `created_at` DATETIME NOT NULL,
                INDEX `idx_exec_trans` (`executive_id`),
                INDEX `idx_wallet_id` (`wallet_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        if ($this->db->tableExists('executive_wallet_transactions')) {
            if (!$this->db->fieldExists('executive_type', 'executive_wallet_transactions')) {
                $this->db->query("ALTER TABLE `executive_wallet_transactions` ADD COLUMN `executive_type` ENUM('register', 'verification') NOT NULL DEFAULT 'verification' AFTER `executive_id`");
            }
        }

        $this->db->query("
            CREATE TABLE IF NOT EXISTS `executive_bank_details` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `executive_id` INT UNSIGNED NOT NULL UNIQUE,
                `bank_name` VARCHAR(150) NOT NULL,
                `account_number` VARCHAR(100) NOT NULL,
                `ifsc_code` VARCHAR(50) NOT NULL,
                `account_holder_name` VARCHAR(150) NOT NULL,
                `upi_id` VARCHAR(100) DEFAULT NULL,
                `cf_beneficiary_id` VARCHAR(100) DEFAULT NULL,
                `is_verified` TINYINT(1) DEFAULT 1,
                `created_at` DATETIME NOT NULL,
                `updated_at` DATETIME DEFAULT NULL,
                INDEX `idx_bank_exec` (`executive_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        if ($this->db->tableExists('executive_bank_details')) {
            if (!$this->db->fieldExists('cf_beneficiary_id', 'executive_bank_details')) {
                $this->db->query("ALTER TABLE `executive_bank_details` ADD COLUMN `cf_beneficiary_id` VARCHAR(100) DEFAULT NULL AFTER `upi_id`");
            }
        }

        $this->db->query("
            CREATE TABLE IF NOT EXISTS `executive_bank_otps` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `executive_id` INT UNSIGNED NOT NULL,
                `otp_code` VARCHAR(10) NOT NULL,
                `bank_data_payload` JSON NOT NULL,
                `expires_at` DATETIME NOT NULL,
                `is_used` TINYINT(1) DEFAULT 0,
                `created_at` DATETIME NOT NULL,
                `updated_at` DATETIME DEFAULT NULL,
                INDEX `idx_otp_exec` (`executive_id`, `is_used`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        // Sync existing payouts into wallets if transactions table is empty
        $this->syncWalletsFromPayouts();
    }

    /**
     * Backfills executive wallets and transaction entries for any existing released payouts.
     */
    protected function syncWalletsFromPayouts()
    {
        $payouts = $this->db->table('executive_payouts')
            ->where('status', 'paid')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($payouts as $p) {
            $execId   = (int)($p['executive_id'] ?? 0);
            $payoutId = (int)($p['id'] ?? 0);
            $amount   = floatval($p['total_amount'] ?? $p['amount'] ?? 0);
            $execType = $p['executive_type'] ?? 'verification';

            // Check if transaction log already exists for this payout
            $txExists = $this->db->table('executive_wallet_transactions')
                ->where('payout_id', $payoutId)
                ->countAllResults();

            if ($txExists === 0 && $execId > 0 && $payoutId > 0) {
                $wallet = $this->db->table('executive_wallets')
                    ->where('executive_id', $execId)
                    ->get()
                    ->getRowArray();

                if (!$wallet) {
                    $this->db->table('executive_wallets')->insert([
                        'executive_id'    => $execId,
                        'balance'         => 0.00,
                        'total_earned'    => 0.00,
                        'total_withdrawn' => 0.00,
                        'created_at'      => $p['created_at'] ?? date('Y-m-d H:i:s'),
                        'updated_at'      => date('Y-m-d H:i:s'),
                    ]);
                    $walletId = $this->db->insertID();
                    $currentBalance = 0.00;
                    $currentEarned = 0.00;
                } else {
                    $walletId = (int)$wallet['id'];
                    $currentBalance = floatval($wallet['balance'] ?? 0);
                    $currentEarned = floatval($wallet['total_earned'] ?? 0);
                }

                $newBalance = round($currentBalance + $amount, 2);
                $newEarned  = round($currentEarned + $amount, 2);

                $this->db->table('executive_wallets')
                    ->where('id', $walletId)
                    ->update([
                        'balance'      => $newBalance,
                        'total_earned' => $newEarned,
                        'updated_at'   => date('Y-m-d H:i:s'),
                    ]);

                $totalCalls = $p['total_calls'] ?? $p['calls'] ?? 0;
                $unitLabel  = ($execType === 'register') ? 'paid provider conversions' : 'completed verification calls';

                $this->db->table('executive_wallet_transactions')->insert([
                    'wallet_id'      => $walletId,
                    'executive_id'   => $execId,
                    'executive_type' => $execType,
                    'payout_id'      => $payoutId,
                    'type'           => 'credit',
                    'amount'         => $amount,
                    'balance_after'  => $newBalance,
                    'description'    => "Payout credited for {$totalCalls} {$unitLabel}",
                    'reference_no'   => ($p['transaction_ref'] ?? '') ?: ('PAYOUT-EXEC-' . $execId . '-' . $payoutId),
                    'created_at'     => $p['created_at'] ?? date('Y-m-d H:i:s'),
                ]);
            }
        }
    }

    /**
     * Resolve executive type ('register' vs 'verification') from employee role.
     */
    public function getExecutiveType(int $execId): string
    {
        if ($this->db->tableExists('employees')) {
            $emp = $this->db->table('employees e')
                ->select('e.role_id, r.slug as role_slug, r.name as role_name')
                ->join('roles r', 'r.id = e.role_id', 'left')
                ->where('e.id', $execId)
                ->get()
                ->getRowArray();

            if ($emp) {
                $roleId   = (int)($emp['role_id'] ?? 0);
                $roleSlug = strtolower($emp['role_slug'] ?? '');
                $roleName = strtolower($emp['role_name'] ?? '');

                if ($roleId === 13 || str_contains($roleSlug, 'regist') || str_contains($roleName, 'regist')) {
                    return 'register';
                }
                if ($roleId === 10 || str_contains($roleSlug, 'verif') || str_contains($roleName, 'verif')) {
                    return 'verification';
                }
            }
        }

        // Check recent records in executive_call_records
        $recentRec = $this->db->table('executive_call_records')
            ->select('executive_type')
            ->where('executive_id', $execId)
            ->orderBy('id', 'DESC')
            ->get()
            ->getRowArray();

        if ($recentRec && !empty($recentRec['executive_type'])) {
            return $recentRec['executive_type'];
        }

        return 'verification';
    }

    /**
     * Resolve executive role display name.
     */
    public function getExecutiveRoleName(int $execId): string
    {
        if ($this->db->tableExists('employees')) {
            $emp = $this->db->table('employees e')
                ->select('e.role_id, r.name as role_name')
                ->join('roles r', 'r.id = e.role_id', 'left')
                ->where('e.id', $execId)
                ->get()
                ->getRowArray();

            if (!empty($emp['role_name'])) {
                return $emp['role_name'];
            }
            if (!empty($emp['role_id'])) {
                if ((int)$emp['role_id'] === 13) return 'Registration Executive';
                if ((int)$emp['role_id'] === 10) return 'Verification Executive';
            }
        }

        $type = $this->getExecutiveType($execId);
        return ($type === 'register') ? 'Registration Executive' : 'Verification Executive';
    }

    /**
     * Calculate tiered rate based on executive type and daily count:
     * 1. Registration Executives (Paid Provider Conversions: sp_services.payment_paid = 1)
     *    Rate: < 15 paid providers = ₹15/paid, >= 15 paid providers = ₹20/paid
     * 2. Verification Executives (Service Verification Calls)
     *    Rate: < 15 calls = ₹10/call, >= 15 calls = ₹15/call
     */
    public function getTierRate(string $execType, int $dailyCount): float
    {
        if ($execType === 'register') {
            return ($dailyCount >= 15) ? 20.00 : 15.00;
        }

        // Default Verification Executive
        return ($dailyCount >= 15) ? 15.00 : 10.00;
    }

    /**
     * Main index page for Executive Payouts module.
     * Segregates Registration Executives (Paid Conversions: ₹15/₹20) and Verification Executives (Calls: ₹10/₹15).
     */
    public function index()
    {

        // Fetch datasets
        $pendingPayouts   = $this->calculatePendingPayouts();
        $payoutHistory    = $this->getPayoutHistory();
        $executiveWallets = $this->getAllExecutiveWallets();

        // Segregate pending payouts
        $registerPendingPayouts     = array_values(array_filter($pendingPayouts, fn($p) => ($p['executive_type'] ?? '') === 'register'));
        $verificationPendingPayouts = array_values(array_filter($pendingPayouts, fn($p) => ($p['executive_type'] ?? '') !== 'register'));

        // Segregate payout history
        $registerPayoutHistory     = array_values(array_filter($payoutHistory, fn($p) => ($p['executive_type'] ?? '') === 'register'));
        $verificationPayoutHistory = array_values(array_filter($payoutHistory, fn($p) => ($p['executive_type'] ?? '') !== 'register'));

        // Segregate wallets
        $registerWallets     = array_values(array_filter($executiveWallets, fn($w) => ($w['executive_type'] ?? '') === 'register'));
        $verificationWallets = array_values(array_filter($executiveWallets, fn($w) => ($w['executive_type'] ?? '') !== 'register'));

        // Calculate KPI Stats - Totals
        $totalPendingAmount = array_sum(array_column($pendingPayouts, 'total_pending_amount'));
        $totalPendingCalls  = array_sum(array_column($pendingPayouts, 'total_pending_calls'));
        $totalPaidAmount    = array_sum(array_column($payoutHistory, 'total_amount'));
        $totalPaidCalls     = array_sum(array_column($payoutHistory, 'total_calls'));
        $activeVerifiers    = count($pendingPayouts);
        $totalWalletBalance = array_sum(array_column($executiveWallets, 'balance'));

        // Calculate KPI Stats - Registration Executives (Paid Conversions: ₹15/₹20)
        $registerPendingAmount = array_sum(array_column($registerPendingPayouts, 'total_pending_amount'));
        $registerPendingCount  = array_sum(array_column($registerPendingPayouts, 'total_pending_calls'));
        $registerPaidAmount    = array_sum(array_column($registerPayoutHistory, 'total_amount'));
        $registerPaidCount     = array_sum(array_column($registerPayoutHistory, 'total_calls'));
        $activeRegisterExecs   = count($registerPendingPayouts);

        // Calculate KPI Stats - Verification Executives (Calls: ₹10/₹15)
        $verificationPendingAmount = array_sum(array_column($verificationPendingPayouts, 'total_pending_amount'));
        $verificationPendingCount  = array_sum(array_column($verificationPendingPayouts, 'total_pending_calls'));
        $verificationPaidAmount    = array_sum(array_column($verificationPayoutHistory, 'total_amount'));
        $verificationPaidCount     = array_sum(array_column($verificationPayoutHistory, 'total_calls'));
        $activeVerificationExecs   = count($verificationPendingPayouts);

        $data = [
            'title'                      => 'Executive Payouts & Wallets (Register & Verification)',
            'page'                       => 'call-verification-payouts',
            // Unified & Segregated Lists
            'pendingPayouts'             => $pendingPayouts,
            'registerPendingPayouts'     => $registerPendingPayouts,
            'verificationPendingPayouts' => $verificationPendingPayouts,
            'payoutHistory'              => $payoutHistory,
            'registerPayoutHistory'      => $registerPayoutHistory,
            'verificationPayoutHistory'  => $verificationPayoutHistory,
            'executiveWallets'           => $executiveWallets,
            'registerWallets'            => $registerWallets,
            'verificationWallets'        => $verificationWallets,
            // Combined KPI Stats
            'totalPendingAmount'         => $totalPendingAmount,
            'totalPendingCalls'          => $totalPendingCalls,
            'totalPaidAmount'            => $totalPaidAmount,
            'totalPaidCalls'             => $totalPaidCalls,
            'activeVerifiers'            => $activeVerifiers,
            'totalWalletBalance'         => $totalWalletBalance,
            // Register Executive KPIs
            'registerPendingAmount'      => $registerPendingAmount,
            'registerPendingCount'       => $registerPendingCount,
            'registerPaidAmount'         => $registerPaidAmount,
            'registerPaidCount'          => $registerPaidCount,
            'activeRegisterExecs'        => $activeRegisterExecs,
            // Verification Executive KPIs
            'verificationPendingAmount'  => $verificationPendingAmount,
            'verificationPendingCount'   => $verificationPendingCount,
            'verificationPaidAmount'     => $verificationPaidAmount,
            'verificationPaidCount'      => $verificationPaidCount,
            'activeVerificationExecs'    => $activeVerificationExecs,
        ];

        if ($this->response) {
            $this->response->setHeader('Content-Type', 'text/html; charset=UTF-8');
        }
        return $this->loadView('admin/call_verification_payouts', $data);
    }

    
    /**
     * Calculate an executive's current payout cycle:
     * Supports both Registration Executives (₹15 / ₹20 rate) and Verification Executives (₹10 / ₹15 rate).
     */
    protected function calculateExecutiveCycle(int $execId, float $targetAmount = 0, ?string $execType = null): array
    {
        $wallet       = $this->getExecutiveWallet($execId);
        $resolvedType = $execType ?: $this->getExecutiveType($execId);

        // 1. Get last released payout date
        $lastPayoutQuery = $this->db->table('executive_payouts')
            ->where('executive_id', $execId)
            ->where('status', 'paid');

        if ($execType) {
            $lastPayoutQuery->where('executive_type', $execType);
        }

        $lastPayout = $lastPayoutQuery->orderBy('payout_date', 'DESC')->get()->getRowArray();
        $lastPayoutDate = $lastPayout ? ($lastPayout['payout_date'] ?? $lastPayout['created_at']) : null;

        // 2. Fetch unreleased records filtered by executive_type
        // First look for records already credited/paid into wallet awaiting payout release
        $unreleasedQuery = "
            SELECT call_date, executive_type, rate, COUNT(id) as daily_count
            FROM executive_call_records
            WHERE executive_id = ? 
              AND executive_type = ?
              AND payout_status = 'paid'
              AND (payout_id IS NULL OR payout_id = 0 OR payout_id IN (SELECT id FROM executive_payouts WHERE status = 'pending'))
            GROUP BY call_date, executive_type, rate
            ORDER BY call_date ASC
        ";
        $unreleasedRecords = $this->db->query($unreleasedQuery, [$execId, $resolvedType])->getResultArray();

        // If no paid wallet records found, check pending unreleased records
        if (empty($unreleasedRecords)) {
            $unreleasedQuery = "
                SELECT call_date, executive_type, rate, COUNT(id) as daily_count
                FROM executive_call_records
                WHERE executive_id = ? 
                  AND executive_type = ?
                  AND payout_status = 'pending'
                  AND (payout_id IS NULL OR payout_id = 0 OR payout_id IN (SELECT id FROM executive_payouts WHERE status = 'pending'))
                GROUP BY call_date, executive_type, rate
                ORDER BY call_date ASC
            ";
            $unreleasedRecords = $this->db->query($unreleasedQuery, [$execId, $resolvedType])->getResultArray();
        }

        $totalCount     = 0;
        $totalAmount    = 0.00;
        $dailyBreakdown = [];

        foreach ($unreleasedRecords as $row) {
            $callDate   = $row['call_date'];
            $dailyCount = (int)$row['daily_count'];
            $rowType    = $row['executive_type'] ?? $resolvedType;

            // Apply rate formula:
            // Register: < 15 = ₹15, >= 15 = ₹20
            // Verification: < 15 = ₹10, >= 15 = ₹15
            $rate   = floatval(($row['rate'] ?? 0) > 0 ? $row['rate'] : $this->getTierRate($rowType, $dailyCount));
            $amount = round($dailyCount * $rate, 2);

            $totalCount     += $dailyCount;
            $totalAmount    += $amount;
            $dailyBreakdown[] = [
                'call_date'      => $callDate,
                'daily_calls'    => $dailyCount,
                'daily_count'    => $dailyCount,
                'rate'           => $rate,
                'amount'         => $amount,
                'executive_type' => $rowType,
                'unit_label'     => ($rowType === 'register') ? 'Paid Providers' : 'Calls',
            ];

            if ($targetAmount > 0 && $totalAmount >= $targetAmount) {
                break;
            }
        }

        // If target amount is explicitly set (from withdrawal request), strictly use target amount
        if ($targetAmount > 0) {
            $totalAmount = $targetAmount;
            if ($totalCount === 0) {
                $rate = ($resolvedType === 'register') ? 15.00 : 10.00;
                $totalCount = (int)ceil($targetAmount / $rate);
                if (empty($dailyBreakdown)) {
                    $dailyBreakdown[] = [
                        'call_date'      => date('Y-m-d'),
                        'daily_calls'    => $totalCount,
                        'daily_count'    => $totalCount,
                        'rate'           => $rate,
                        'amount'         => $targetAmount,
                        'executive_type' => $resolvedType,
                        'unit_label'     => ($resolvedType === 'register') ? 'Paid Providers' : 'Calls',
                    ];
                }
            }
        }

        return [
            'executive_id'         => $execId,
            'executive_name'       => $this->getExecutiveName($execId),
            'executive_type'       => $resolvedType,
            'executive_type_label' => ($resolvedType === 'register') ? 'Registration Executive' : 'Verification Executive',
            'role_name'            => $this->getExecutiveRoleName($execId),
            'unit_label'           => ($resolvedType === 'register') ? 'Paid Providers' : 'Completed Calls',
            'wallet_balance'       => $wallet['balance'],
            'total_earned'         => $wallet['total_earned'],
            'total_pending_calls'  => $totalCount,
            'total_pending_units'  => $totalCount,
            'total_pending_amount' => $totalAmount,
            'daily_breakdown'      => $dailyBreakdown,
            'last_payout_date'     => $lastPayoutDate,
        ];
    }

    /**
     * Calculate pending payout requests submitted by executives.
     * Categorizes and tags each request as Registration Executive (Role 13) or Verification Executive (Role 10).
     * Data only appears in Pending Payouts when an executive explicitly requests a payout.
     */
    protected function calculatePendingPayouts(?string $filterType = null)
    {
        $query = $this->db->table('executive_payouts')
            ->where('status', 'pending');

        if ($filterType && in_array($filterType, ['register', 'verification'])) {
            $query->where('executive_type', $filterType);
        }

        $pendingPayoutRows = $query->orderBy('id', 'DESC')->get()->getResultArray();
        $executiveGroups = [];

        foreach ($pendingPayoutRows as $pRow) {
            $payoutId = (int)$pRow['id'];
            $execId   = (int)($pRow['executive_id'] ?? 0);
            if ($execId <= 0) {
                continue;
            }

            $execType = $pRow['executive_type'] ?? $this->getExecutiveType($execId);

            // Strictly preserve the executive's requested withdrawal amount
            $reqAmount = floatval(($pRow['amount'] ?? 0) > 0 ? $pRow['amount'] : ($pRow['total_amount'] ?? 0));
            $reqCalls  = (int)(($pRow['total_calls'] ?? 0) > 0 ? $pRow['total_calls'] : ($pRow['calls'] ?? 0));
            $reqBreakdown = json_decode($pRow['daily_breakdown'] ?? '[]', true) ?: [];

            // Check if specific records are already linked to this payout request
            $linkedRecords = $this->db->query("
                SELECT call_date, COUNT(id) as daily_count, rate, SUM(rate) as subtotal
                FROM executive_call_records
                WHERE executive_id = ? 
                  AND executive_type = ?
                  AND payout_id = ?
                GROUP BY call_date, rate
                ORDER BY call_date ASC
            ", [$execId, $execType, $payoutId])->getResultArray();

            if (!empty($linkedRecords)) {
                $linkedCalls = array_sum(array_column($linkedRecords, 'daily_count'));
                $linkedAmt   = round(array_sum(array_column($linkedRecords, 'subtotal')), 2);
                $finalCalls  = $linkedCalls;
                $finalAmount = ($reqAmount > 0) ? $reqAmount : $linkedAmt;
                $finalBreakdown = [];
                foreach ($linkedRecords as $lr) {
                    $r = floatval(($lr['rate'] ?? 0) > 0 ? $lr['rate'] : $this->getTierRate($execType, (int)$lr['daily_count']));
                    $finalBreakdown[] = [
                        'call_date'      => $lr['call_date'],
                        'daily_calls'    => (int)$lr['daily_count'],
                        'daily_count'    => (int)$lr['daily_count'],
                        'rate'           => $r,
                        'amount'         => round((int)$lr['daily_count'] * $r, 2),
                        'executive_type' => $execType,
                        'unit_label'     => ($execType === 'register') ? 'Paid Providers' : 'Calls',
                    ];
                }
            } else {
                $cycle = $this->calculateExecutiveCycle($execId, $reqAmount, $execType);
                $finalCalls     = ($reqCalls > 0) ? $reqCalls : $cycle['total_pending_calls'];
                $finalAmount    = ($reqAmount > 0) ? $reqAmount : $cycle['total_pending_amount'];
                $finalBreakdown = !empty($reqBreakdown) ? $reqBreakdown : $cycle['daily_breakdown'];
            }

            // Ensure executive_payouts preserves the accurate requested amount, type, and breakdown
            $this->db->table('executive_payouts')
                ->where('id', $payoutId)
                ->update([
                    'executive_type'  => $execType,
                    'unit_type'       => ($execType === 'register') ? 'paid_provider' : 'call',
                    'total_calls'     => $finalCalls,
                    'amount'          => $finalAmount,
                    'total_amount'    => $finalAmount,
                    'daily_breakdown' => json_encode($finalBreakdown),
                    'updated_at'      => date('Y-m-d H:i:s'),
                ]);

            $bank = $this->db->table('executive_bank_details')
                ->where('executive_id', $execId)
                ->get()
                ->getRowArray();

            $wallet = $this->getExecutiveWallet($execId);

            $executiveGroups[] = [
                'executive_id'         => $execId,
                'executive_name'       => $this->getExecutiveName($execId),
                'executive_type'       => $execType,
                'executive_type_label' => ($execType === 'register') ? 'Registration Executive' : 'Verification Executive',
                'role_name'            => $this->getExecutiveRoleName($execId),
                'unit_label'           => ($execType === 'register') ? 'Paid Providers' : 'Calls',
                'wallet_balance'       => $wallet['balance'],
                'total_earned'         => $wallet['total_earned'],
                'total_pending_calls'  => $finalCalls,
                'total_pending_units'  => $finalCalls,
                'total_pending_amount' => $finalAmount,
                'daily_breakdown'      => $finalBreakdown,
                'payout_id'            => $payoutId,
                'last_payout_date'     => $cycle['last_payout_date'] ?? null,
                'bank_details'         => $bank,
                'created_at'           => $pRow['created_at'] ?? date('Y-m-d H:i:s'),
            ];
        }

        return $executiveGroups;
    }

    public function requestExecutiveWithdrawal()
    {
        $response    = $this->response ?: \Config\Services::response();
        $json        = $this->request ? ($this->request->getJSON(true) ?: []) : [];
        $executiveId = (int)($this->request ? ($this->request->getVar('executive_id') ?? ($this->request->getPost('executive_id') ?? ($json['executive_id'] ?? ($_POST['executive_id'] ?? 0)))) : ($_POST['executive_id'] ?? 0));
        $amount      = floatval($this->request ? ($this->request->getVar('amount') ?? ($this->request->getPost('amount') ?? ($json['amount'] ?? ($_POST['amount'] ?? 0)))) : ($_POST['amount'] ?? 0));
        $reqType     = $this->request ? ($this->request->getVar('executive_type') ?? ($this->request->getPost('executive_type') ?? ($json['executive_type'] ?? ($_POST['executive_type'] ?? null)))) : ($_POST['executive_type'] ?? null);

        if (!$executiveId || $amount <= 0) {
            return $response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'Please provide valid executive_id and withdrawal amount greater than zero.',
            ]);
        }

        $execType = in_array($reqType, ['register', 'verification']) ? $reqType : $this->getExecutiveType($executiveId);

        // 1. Fetch executive verified bank details
        $bankDetails = $this->db->table('executive_bank_details')
            ->where('executive_id', $executiveId)
            ->where('is_verified', 1)
            ->get()
            ->getRowArray();

        if (!$bankDetails) {
            return $response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'Verified bank details not found. Please add and verify bank account before requesting withdrawal.',
            ]);
        }

        // 2. Fetch executive wallet
        $wallet = $this->db->table('executive_wallets')
            ->where('executive_id', $executiveId)
            ->get()
            ->getRowArray();

        $currentBalance = $wallet ? floatval($wallet['balance'] ?? 0) : 0.00;

        if (!$wallet || $currentBalance < $amount) {
            return $response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => "Insufficient wallet balance! Your available balance is ₹" . number_format($currentBalance, 2) . ".",
                'balance' => $currentBalance
            ]);
        }

        // 3. Check for existing pending payout request
        $existingPending = $this->db->table('executive_payouts')
            ->where('executive_id', $executiveId)
            ->where('status', 'pending')
            ->get()
            ->getRowArray();

        if ($existingPending) {
            return $response->setStatusCode(400)->setJSON([
                'status'          => false,
                'message'         => 'You already have a pending withdrawal request under review. Please wait for it to be processed.',
                'pending_request' => $existingPending
            ]);
        }

        // 4. Calculate cycle completed records
        // First look for records already credited/paid into wallet for this executive & type
        $creditedRecords = $this->db->query("
            SELECT call_date, COUNT(id) as daily_count, rate
            FROM executive_call_records
            WHERE executive_id = ? 
              AND executive_type = ?
              AND payout_status = 'paid'
              AND (payout_id IS NULL OR payout_id = 0)
            GROUP BY call_date, rate
            ORDER BY call_date ASC
        ", [$executiveId, $execType])->getResultArray();

        $selectedDates  = [];
        $totalUnits     = 0;
        $calculatedAmt  = 0.00;
        $dailyBreakdown = [];

        if (!empty($creditedRecords)) {
            foreach ($creditedRecords as $row) {
                $callDate   = $row['call_date'];
                $dailyCount = (int)$row['daily_count'];
                $rate       = floatval(($row['rate'] ?? 0) > 0 ? $row['rate'] : $this->getTierRate($execType, $dailyCount));
                $subtotal   = round($dailyCount * $rate, 2);

                $totalUnits    += $dailyCount;
                $calculatedAmt += $subtotal;
                $selectedDates[] = $callDate;
                $dailyBreakdown[] = [
                    'call_date'      => $callDate,
                    'daily_calls'    => $dailyCount,
                    'daily_count'    => $dailyCount,
                    'rate'           => $rate,
                    'amount'         => $subtotal,
                    'executive_type' => $execType,
                    'unit_label'     => ($execType === 'register') ? 'Paid Providers' : 'Calls',
                ];

                if ($calculatedAmt >= $amount) {
                    break;
                }
            }
        }

        // If not enough credited records found, check pending unreleased records
        if ($calculatedAmt < $amount) {
            $unreleasedRecords = $this->db->query("
                SELECT call_date, COUNT(id) as daily_count
                FROM executive_call_records
                WHERE executive_id = ? 
                  AND executive_type = ?
                  AND payout_status = 'pending'
                  AND (payout_id IS NULL OR payout_id = 0)
                GROUP BY call_date
                ORDER BY call_date DESC
            ", [$executiveId, $execType])->getResultArray();

            foreach ($unreleasedRecords as $row) {
                $callDate   = $row['call_date'];
                $dailyCount = (int)$row['daily_count'];

                $rate     = $this->getTierRate($execType, $dailyCount);
                $subtotal = round($dailyCount * $rate, 2);

                $totalUnits    += $dailyCount;
                $calculatedAmt += $subtotal;
                $selectedDates[] = $callDate;
                $dailyBreakdown[] = [
                    'call_date'      => $callDate,
                    'daily_calls'    => $dailyCount,
                    'daily_count'    => $dailyCount,
                    'rate'           => $rate,
                    'amount'         => $subtotal,
                    'executive_type' => $execType,
                    'unit_label'     => ($execType === 'register') ? 'Paid Providers' : 'Calls',
                ];

                if ($calculatedAmt >= $amount) {
                    break;
                }
            }
        }

        if ($totalUnits === 0 && $amount > 0) {
            $rate = ($execType === 'register') ? 15.00 : 10.00;
            $totalUnits = (int)ceil($amount / $rate);
            $dailyBreakdown = [
                [
                    'call_date'      => date('Y-m-d'),
                    'daily_calls'    => $totalUnits,
                    'daily_count'    => $totalUnits,
                    'rate'           => $rate,
                    'amount'         => $amount,
                    'executive_type' => $execType,
                    'unit_label'     => ($execType === 'register') ? 'Paid Providers' : 'Calls',
                ]
            ];
        }

        // 5. Begin DB Transaction
        $this->db->transStart();

        $now = date('Y-m-d H:i:s');

        // Insert Payout Record WITH Units and Breakdown
        $this->db->table('executive_payouts')->insert([
            'executive_id'        => $executiveId,
            'executive_type'      => $execType,
            'unit_type'           => ($execType === 'register') ? 'paid_provider' : 'call',
            'total_calls'         => $totalUnits,
            'amount'              => $amount,
            'total_amount'        => $amount,
            'bank_detail_id'      => $bankDetails['id'] ?? null,
            'bank_name'           => $bankDetails['bank_name'] ?? null,
            'account_number'      => $bankDetails['account_number'] ?? null,
            'ifsc_code'           => $bankDetails['ifsc_code'] ?? null,
            'account_holder_name' => $bankDetails['account_holder_name'] ?? null,
            'upi_id'              => $bankDetails['upi_id'] ?? null,
            'status'              => 'pending',
            'daily_breakdown'     => json_encode($dailyBreakdown),
            'remarks'             => 'Withdrawal request submitted by ' . ($execType === 'register' ? 'Registration Executive' : 'Verification Executive'),
            'created_at'          => $now,
            'updated_at'          => $now,
        ]);
        $payoutId = $this->db->insertID();

        // Link ONLY selected matching records to this payout request
        if (!empty($selectedDates)) {
            $this->db->table('executive_call_records')
                ->where('executive_id', $executiveId)
                ->where('executive_type', $execType)
                ->whereIn('call_date', $selectedDates)
                ->where('(payout_id IS NULL OR payout_id = 0)')
                ->update([
                    'payout_id'  => $payoutId,
                    'updated_at' => $now,
                ]);
        }

        // Deduct Wallet Balance & Update total_withdrawn
        $newBalance        = max(0.00, round($currentBalance - $amount, 2));
        $newTotalWithdrawn = round(floatval($wallet['total_withdrawn'] ?? 0) + $amount, 2);

        $this->db->table('executive_wallets')
            ->where('executive_id', $executiveId)
            ->update([
                'balance'         => $newBalance,
                'total_withdrawn' => $newTotalWithdrawn,
                'updated_at'      => $now,
            ]);

        // Insert Debit Wallet Transaction
        $unitText = ($execType === 'register') ? 'paid provider conversions' : 'verification calls';
        $this->db->table('executive_wallet_transactions')->insert([
            'wallet_id'      => $wallet['id'],
            'executive_id'   => $executiveId,
            'executive_type' => $execType,
            'payout_id'      => $payoutId,
            'type'           => 'debit',
            'amount'         => $amount,
            'balance_after'  => $newBalance,
            'description'    => "Withdrawal Request #{$payoutId} for {$totalUnits} {$unitText}",
            'reference_no'   => "WD-" . strtoupper(substr($execType, 0, 3)) . "-{$payoutId}",
            'created_at'     => $now,
        ]);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $response->setStatusCode(500)->setJSON([
                'status'  => false,
                'message' => 'Failed to process withdrawal request. Please try again.',
            ]);
        }

        try {
            \Config\Services::cache()->delete('admin_sidebar_counts');
        } catch (\Throwable $e) {}

        return $response->setStatusCode(200)->setJSON([
            'status'          => true,
            'message'         => 'Withdrawal request submitted successfully!',
            'payout_id'       => $payoutId,
            'executive_type'  => $execType,
            'amount'          => $amount,
            'total_calls'     => $totalUnits,
            'total_units'     => $totalUnits,
            'daily_breakdown' => $dailyBreakdown,
            'new_balance'     => $newBalance,
            'bank_account'    => $bankDetails['account_number'],
            'ifsc_code'       => $bankDetails['ifsc_code'],
            'status_label'    => 'pending'
        ]);
    }

    /**
     * Alias for requestExecutiveWithdrawal.
     */
    public function requestExecutivePayout()
    {
        return $this->requestExecutiveWithdrawal();
    }

    /**
     * Fetch released payout history with wallet snapshot and executive type tagging.
     */
    protected function getPayoutHistory(?string $filterType = null)
    {
        $query = $this->db->table('executive_payouts')
            ->orderBy('id', 'DESC');

        if ($filterType && in_array($filterType, ['register', 'verification'])) {
            $query->where('executive_type', $filterType);
        }

        $history = $query->get()->getResultArray();
        $paidHistory = [];

        foreach ($history as &$row) {
            $rowStatus = strtolower(trim($row['status'] ?? ''));
            if (in_array($rowStatus, ['paid', 'success', 'completed', 'approved']) || (!empty($row['transaction_ref']) && $rowStatus !== 'pending')) {
                $execId   = (int)($row['executive_id'] ?? 0);
                $execType = $row['executive_type'] ?? $this->getExecutiveType($execId);

                $row['id']                   = $row['id'] ?? 0;
                $row['executive_id']         = $execId;
                $row['executive_type']       = $execType;
                $row['executive_type_label'] = ($execType === 'register') ? 'Registration Executive' : 'Verification Executive';
                $row['role_name']            = $this->getExecutiveRoleName($execId);
                $row['unit_label']           = ($execType === 'register') ? 'Paid Providers' : 'Calls';
                $row['executive_name']       = $this->getExecutiveName($execId);
                $row['total_calls']          = (int)($row['total_calls'] ?? $row['calls'] ?? 0);
                $row['total_units']          = $row['total_calls'];
                $row['total_amount']         = floatval(!empty($row['total_amount']) ? $row['total_amount'] : ($row['amount'] ?? 0));
                $row['payout_date']          = $row['payout_date'] ?? $row['created_at'] ?? date('Y-m-d H:i:s');
                $row['transaction_ref']      = $row['transaction_ref'] ?? '';
                $row['daily_breakdown']      = json_decode($row['daily_breakdown'] ?? '[]', true);
                $wallet                      = $this->getExecutiveWallet($execId);
                $row['wallet_balance']       = $wallet['balance'] ?? 0;
                $paidHistory[]               = $row;
            }
        }

        return $paidHistory;
    }

    /**
     * Fetch single executive wallet balance and totals.
     */
    protected function getExecutiveWallet(int $executiveId): array
    {
        $w = $this->db->table('executive_wallets')
            ->where('executive_id', $executiveId)
            ->get()
            ->getRowArray();

        $execType = $this->getExecutiveType($executiveId);

        if ($w) {
            $balance = floatval($w['balance'] ?? 0);
            $earned  = floatval(!empty($w['total_earned']) ? $w['total_earned'] : ($balance > 0 ? $balance : 0));
            return [
                'id'                   => (int)$w['id'],
                'executive_id'         => (int)$w['executive_id'],
                'executive_type'       => $execType,
                'executive_type_label' => ($execType === 'register') ? 'Registration Executive' : 'Verification Executive',
                'role_name'            => $this->getExecutiveRoleName($executiveId),
                'balance'              => $balance,
                'total_earned'         => $earned,
                'total_withdrawn'      => floatval($w['total_withdrawn'] ?? 0),
                'updated_at'           => $w['updated_at'] ?? $w['created_at'],
            ];
        }

        return [
            'id'                   => 0,
            'executive_id'         => $executiveId,
            'executive_type'       => $execType,
            'executive_type_label' => ($execType === 'register') ? 'Registration Executive' : 'Verification Executive',
            'role_name'            => $this->getExecutiveRoleName($executiveId),
            'balance'              => 0.00,
            'total_earned'         => 0.00,
            'total_withdrawn'      => 0.00,
            'updated_at'           => null,
        ];
    }

    /**
     * Fetch all executive wallets with transaction counts and executive role tagging.
     * Includes all Registration Executives (Role 13) and Verification Executives (Role 10).
     */
    protected function getAllExecutiveWallets(?string $filterType = null): array
    {
        $sql = "
            SELECT e.id as executive_id, e.name as executive_name, e.role_id, r.name as role_name, r.slug as role_slug,
                   COALESCE(ew.id, 0) as wallet_id,
                   COALESCE(ew.balance, 0.00) as balance,
                   COALESCE(ew.total_earned, 0.00) as total_earned,
                   COALESCE(ew.total_withdrawn, 0.00) as total_withdrawn,
                   ew.updated_at,
                   (SELECT COUNT(id) FROM executive_wallet_transactions WHERE executive_id = e.id) as tx_count
            FROM employees e
            LEFT JOIN roles r ON r.id = e.role_id
            LEFT JOIN executive_wallets ew ON ew.executive_id = e.id
            WHERE e.role_id IN (10, 13) AND e.status = 1
            ORDER BY (CASE WHEN e.role_id = 13 THEN 1 WHEN e.role_id = 10 THEN 2 ELSE 3 END), ew.balance DESC, e.name ASC
        ";

        $rows = $this->db->query($sql)->getResultArray();
        $result = [];

        foreach ($rows as $r) {
            $execId   = (int)$r['executive_id'];
            $execType = $this->getExecutiveType($execId);

            if ($filterType && $filterType !== $execType) {
                continue;
            }

            $roleName = $r['role_name'] ?: (($r['role_id'] == 13) ? 'Registration Executive' : 'Verification Executive');

            $result[] = [
                'id'                   => (int)$r['wallet_id'],
                'executive_id'         => $execId,
                'executive_name'       => $r['executive_name'] ?: $this->getExecutiveName($execId),
                'executive_type'       => $execType,
                'executive_type_label' => ($execType === 'register') ? 'Registration Executive' : 'Verification Executive',
                'role_name'            => $roleName,
                'balance'              => floatval($r['balance'] ?? 0),
                'total_earned'         => floatval($r['total_earned'] ?? 0),
                'total_withdrawn'      => floatval($r['total_withdrawn'] ?? 0),
                'tx_count'             => (int)($r['tx_count'] ?? 0),
                'updated_at'           => $r['updated_at'] ?? null,
            ];
        }

        return $result;
    }

    /**
     * Get executive display name from employees or admin tables.
     */
    protected function getExecutiveName(int $executiveId): string
    {
        if ($this->db->tableExists('employees')) {
            $emp = $this->db->table('employees')
                ->select('name')
                ->where('id', $executiveId)
                ->get()
                ->getRowArray();
            if ($emp && !empty($emp['name'])) {
                return $emp['name'];
            }
        }

        if ($this->db->tableExists('admin')) {
            $adm = $this->db->table('admin')
                ->select('username, name')
                ->where('id', $executiveId)
                ->get()
                ->getRowArray();
            if ($adm) {
                return $adm['name'] ?? $adm['username'] ?? 'Executive #' . $executiveId;
            }
        }

        return 'Executive #' . $executiveId;
    }

    /**
     * Release Payout for an Executive (Registration or Verification) and Update Wallet & Log Transactions.
     * Real money transfer via Cashfree Payout Gateway.
     */
    public function releasePayout()
    {
        $executiveId = (int)$this->request->getPost('executive_id');
        $payoutId    = (int)$this->request->getPost('payout_id');
        $inputAmount = floatval($this->request->getPost('amount'));
        $remarks     = trim($this->request->getPost('remarks') ?? '');
        $reqType     = $this->request->getPost('executive_type');

        if (!$executiveId) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Invalid executive ID provided.',
            ]);
        }

        $execType = in_array($reqType, ['register', 'verification']) ? $reqType : $this->getExecutiveType($executiveId);

        // 1. Fetch pending payout row if exists
        $pendingPayoutRow = null;
        if ($payoutId > 0) {
            $pendingPayoutRow = $this->db->table('executive_payouts')
                ->where('id', $payoutId)
                ->where('executive_id', $executiveId)
                ->get()
                ->getRowArray();
        }
        if (!$pendingPayoutRow) {
            $pendingPayoutRow = $this->db->table('executive_payouts')
                ->where('executive_id', $executiveId)
                ->where('status', 'pending')
                ->orderBy('id', 'DESC')
                ->get()
                ->getRowArray();
        }

        if ($pendingPayoutRow && !empty($pendingPayoutRow['executive_type'])) {
            $execType = $pendingPayoutRow['executive_type'];
        }

        // 2. Determine target payout amount accurately
        if ($inputAmount > 0) {
            $totalAmount = round($inputAmount, 2);
        } elseif ($pendingPayoutRow) {
            $totalAmount = floatval(($pendingPayoutRow['amount'] ?? 0) > 0 ? $pendingPayoutRow['amount'] : ($pendingPayoutRow['total_amount'] ?? 0));
        } else {
            $cycle = $this->calculateExecutiveCycle($executiveId, 0, $execType);
            $totalAmount = $cycle['total_pending_amount'];
        }

        if ($totalAmount <= 0) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Invalid payout amount. No pending amount found for this executive.',
            ]);
        }

        $totalCalls = $pendingPayoutRow ? (int)(!empty($pendingPayoutRow['total_calls']) ? $pendingPayoutRow['total_calls'] : ($pendingPayoutRow['calls'] ?? 0)) : 0;
        $dailyBreakdown = $pendingPayoutRow ? (json_decode($pendingPayoutRow['daily_breakdown'] ?? '[]', true) ?: []) : [];

        if ($totalCalls <= 0 || empty($dailyBreakdown)) {
            $cycle = $this->calculateExecutiveCycle($executiveId, $totalAmount, $execType);
            $totalCalls = ($totalCalls > 0) ? $totalCalls : $cycle['total_pending_calls'];
            $dailyBreakdown = !empty($dailyBreakdown) ? $dailyBreakdown : $cycle['daily_breakdown'];
        }

        $execName = $this->getExecutiveName($executiveId);

        // 3. Process Real Bank Transfer via Cashfree Payout Gateway
        $bankDetails = $this->db->table('executive_bank_details')
            ->where('executive_id', $executiveId)
            ->get()
            ->getRowArray();

        if (!$bankDetails || empty($bankDetails['account_number']) || empty($bankDetails['ifsc_code'])) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Executive has no verified bank details stored. Please add and verify bank details first before initiating Cashfree payout.',
            ]);
        }

        try {
            $payoutService = new \App\Libraries\CashfreePayoutService();
            $cashfreeRes = $payoutService->processExecutivePayout($executiveId, $bankDetails, $totalAmount, $execName, $remarks);

            if (!$cashfreeRes['success']) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Cashfree Payout Failed: ' . ($cashfreeRes['error'] ?? 'Transfer error'),
                ]);
            }

            $transactionRef = $cashfreeRes['utr'] ?? ($cashfreeRes['transfer_id'] ?? ('CF-' . time()));
            $cashfreeTransferId = $cashfreeRes['transfer_id'] ?? null;
            $transferRemarks = trim(($remarks ? $remarks . ' | ' : '') . "Cashfree Transfer ID: {$cashfreeTransferId} (UTR: {$transactionRef})");
        } catch (\Throwable $e) {
            log_message('error', 'Cashfree payout error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Cashfree Payout Error: ' . $e->getMessage(),
            ]);
        }

        $this->db->transStart();

        $refNo = $transactionRef ?: ('CF-' . strtoupper(substr($execType, 0, 3)) . '-' . $executiveId . '-' . time());
        $now = date('Y-m-d H:i:s');

        $payoutData = [
            'executive_id'    => $executiveId,
            'executive_type'  => $execType,
            'unit_type'       => ($execType === 'register') ? 'paid_provider' : 'call',
            'total_calls'     => $totalCalls,
            'amount'          => $totalAmount,
            'total_amount'    => $totalAmount,
            'payout_date'     => $now,
            'status'          => 'paid',
            'transaction_ref' => $refNo,
            'daily_breakdown' => json_encode($dailyBreakdown),
            'remarks'         => $transferRemarks,
            'updated_at'      => $now,
        ];

        if ($pendingPayoutRow) {
            $payoutId = (int)$pendingPayoutRow['id'];
            $this->db->table('executive_payouts')->where('id', $payoutId)->update($payoutData);
        } else {
            $payoutData['created_at'] = $now;
            $this->db->table('executive_payouts')->insert($payoutData);
            $payoutId = $this->db->insertID();
        }

        // Mark associated records as paid
        $this->db->table('executive_call_records')
            ->where('executive_id', $executiveId)
            ->where('executive_type', $execType)
            ->where('(payout_id IS NULL OR payout_id = 0 OR payout_id = ' . $payoutId . ')')
            ->update([
                'payout_status' => 'paid',
                'payout_id'     => $payoutId,
                'updated_at'    => $now,
            ]);

        // Update wallet
        $alreadyDebited = $this->db->table('executive_wallet_transactions')
            ->where('executive_id', $executiveId)
            ->where('payout_id', $payoutId)
            ->where('type', 'debit')
            ->countAllResults();

        $wallet = $this->db->table('executive_wallets')
            ->where('executive_id', $executiveId)
            ->get()
            ->getRowArray();

        $walletId   = $wallet ? (int)$wallet['id'] : 0;
        $newBalance = $wallet ? floatval($wallet['balance'] ?? 0) : 0.00;

        $unitText = ($execType === 'register') ? 'paid provider conversions' : 'verification calls';

        if ($alreadyDebited === 0) {
            if (!$wallet) {
                $this->db->table('executive_wallets')->insert([
                    'executive_id'    => $executiveId,
                    'balance'         => 0.00,
                    'total_earned'    => $totalAmount,
                    'total_withdrawn' => $totalAmount,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ]);
                $walletId   = $this->db->insertID();
                $newBalance = 0.00;
            } else {
                $currentBalance   = floatval($wallet['balance'] ?? 0);
                $currentWithdrawn = floatval($wallet['total_withdrawn'] ?? 0);

                $newBalance   = max(0.00, round($currentBalance - $totalAmount, 2));
                $newWithdrawn = round($currentWithdrawn + $totalAmount, 2);

                $this->db->table('executive_wallets')
                    ->where('id', $walletId)
                    ->update([
                        'balance'         => $newBalance,
                        'total_withdrawn' => $newWithdrawn,
                        'updated_at'      => $now,
                    ]);
            }

            // Log transaction entry
            $desc = "Cashfree Real Bank Transfer released for {$totalCalls} {$unitText} (UTR/Ref: {$refNo})";

            $this->db->table('executive_wallet_transactions')->insert([
                'wallet_id'      => $walletId,
                'executive_id'   => $executiveId,
                'executive_type' => $execType,
                'payout_id'      => $payoutId,
                'type'           => 'debit',
                'amount'         => $totalAmount,
                'balance_after'  => $newBalance,
                'description'    => $desc,
                'reference_no'   => $refNo,
                'created_at'     => $now,
            ]);
        } else {
            // Update reference on existing transaction
            $this->db->table('executive_wallet_transactions')
                ->where('executive_id', $executiveId)
                ->where('payout_id', $payoutId)
                ->update([
                    'executive_type' => $execType,
                    'reference_no'   => $refNo,
                    'description'    => "Cashfree Real Bank Transfer completed for {$totalCalls} {$unitText} (UTR: {$refNo})",
                ]);
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Failed to process payout release. Database transaction error.',
            ]);
        }

        // Send push & in-app notification after successful payout release
        $this->sendPayoutSuccessNotification($executiveId, $totalAmount, $refNo, true, $execType);

        try {
            \Config\Services::cache()->delete('admin_sidebar_counts');
        } catch (\Throwable $e) {}

        $msg = "Payout of ₹" . number_format($totalAmount, 2) . " transferred successfully via Cashfree to " . $execName . "'s bank account! UTR: " . $refNo;

        return $this->response->setJSON([
            'status'         => true,
            'message'        => $msg,
            'payout_id'      => $payoutId,
            'executive_type' => $execType,
            'amount'         => $totalAmount,
            'new_balance'    => $newBalance,
            'ref_no'         => $refNo,
        ]);
    }

    /**
     * Send 6-digit OTP code to executive for bank details verification.
     */
    public function sendBankOtp()
    {
        $execId         = (int)$this->request->getPost('executive_id');
        $bankName       = trim($this->request->getPost('bank_name') ?? '');
        $accNo          = trim($this->request->getPost('account_number') ?? '');
        $ifscCode       = strtoupper(trim($this->request->getPost('ifsc_code') ?? ''));
        $accHolderName  = trim($this->request->getPost('account_holder_name') ?? '');
        $upiId          = trim($this->request->getPost('upi_id') ?? '');

        if (!$execId || empty($bankName) || empty($accNo) || empty($ifscCode) || empty($accHolderName)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Please fill in all required bank details.',
            ]);
        }

        $payload = [
            'bank_name'           => $bankName,
            'account_number'      => $accNo,
            'ifsc_code'           => $ifscCode,
            'account_holder_name' => $accHolderName,
            'upi_id'              => $upiId,
        ];

        $otp = (string) mt_rand(100000, 999999);

        // Invalidate old OTPs for this exec
        $this->db->table('executive_bank_otps')
            ->where('executive_id', $execId)
            ->where('is_used', 0)
            ->update(['is_used' => 1]);

        $this->db->table('executive_bank_otps')->insert([
            'executive_id'      => $execId,
            'otp_code'          => $otp,
            'bank_data_payload' => json_encode($payload),
            'expires_at'        => date('Y-m-d H:i:s', strtotime('+15 minutes')),
            'is_used'           => 0,
            'created_at'        => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON([
            'status'   => true,
            'message'  => 'OTP code sent to executive mobile number.',
            'demo_otp' => $otp,
        ]);
    }

    /**
     * Verify OTP code and save bank details to executive_bank_details.
     */
    public function verifyBankOtp()
    {
        $execId  = (int)$this->request->getPost('executive_id');
        $otpCode = trim($this->request->getPost('otp_code') ?? '');

        if (!$execId || empty($otpCode)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Invalid executive ID or OTP code.',
            ]);
        }

        $otpRow = $this->db->table('executive_bank_otps')
            ->where('executive_id', $execId)
            ->where('otp_code', $otpCode)
            ->where('is_used', 0)
            ->where('expires_at >=', date('Y-m-d H:i:s'))
            ->get()
            ->getRowArray();

        if (!$otpRow) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Invalid or expired OTP verification code.',
            ]);
        }

        // Mark OTP as used
        $this->db->table('executive_bank_otps')
            ->where('id', $otpRow['id'])
            ->update(['is_used' => 1]);

        $payload = json_decode($otpRow['bank_data_payload'], true) ?: [];

        if (empty($payload)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Failed to parse stored bank data payload.',
            ]);
        }

        $existing = $this->db->table('executive_bank_details')
            ->where('executive_id', $execId)
            ->get()
            ->getRowArray();

        $bankData = [
            'executive_id'        => $execId,
            'bank_name'           => $payload['bank_name'],
            'account_number'      => $payload['account_number'],
            'ifsc_code'           => strtoupper($payload['ifsc_code']),
            'account_holder_name' => $payload['account_holder_name'],
            'upi_id'              => $payload['upi_id'] ?? null,
            'cf_beneficiary_id'   => null,
            'is_verified'         => 1,
            'updated_at'          => date('Y-m-d H:i:s'),
        ];

        if ($existing) {
            $this->db->table('executive_bank_details')
                ->where('id', $existing['id'])
                ->update($bankData);
        } else {
            $bankData['created_at'] = date('Y-m-d H:i:s');
            $this->db->table('executive_bank_details')->insert($bankData);
        }

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Bank details verified and stored successfully!',
        ]);
    }

    /**
     * AJAX endpoint to view executive wallet transactions & balance ledger.
     */
    public function getExecutiveWalletLedger(int $executiveId)
    {
        $execName = $this->getExecutiveName($executiveId);
        $wallet   = $this->getExecutiveWallet($executiveId);
        $execType = $this->getExecutiveType($executiveId);

        $transactions = $this->db->table('executive_wallet_transactions')
            ->where('executive_id', $executiveId)
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'status'               => true,
            'executive_id'         => $executiveId,
            'executive_name'       => $execName,
            'executive_type'       => $execType,
            'executive_type_label' => ($execType === 'register') ? 'Registration Executive' : 'Verification Executive',
            'wallet'               => $wallet,
            'transactions'         => $transactions,
        ]);
    }

    /**
     * AJAX endpoint to view line-item details of a released payout.
     */
    public function getPayoutDetails(int $id)
    {
        $payout = $this->db->table('executive_payouts')
            ->where('id', $id)
            ->get()
            ->getRowArray();

        if (!$payout) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => false,
                'message' => 'Payout record not found.',
            ]);
        }

        $execId   = (int)$payout['executive_id'];
        $execType = $payout['executive_type'] ?? $this->getExecutiveType($execId);

        $payout['executive_name']       = $this->getExecutiveName($execId);
        $payout['executive_type']       = $execType;
        $payout['executive_type_label'] = ($execType === 'register') ? 'Registration Executive' : 'Verification Executive';
        $payout['unit_label']           = ($execType === 'register') ? 'Paid Providers' : 'Calls';
        $payout['daily_breakdown']      = json_decode($payout['daily_breakdown'] ?? '[]', true);

        $calls = $this->db->table('executive_call_records ecr')
            ->select('ecr.*, sp.name as provider_name, sp.phone as provider_phone')
            ->join('service_providers sp', 'sp.id = ecr.provider_id', 'left')
            ->where('ecr.payout_id', $id)
            ->orderBy('ecr.call_date', 'DESC')
            ->get()
            ->getResultArray();

        $serviceNames = [
            1 => 'Boarding',
            2 => 'Grooming',
            3 => 'Training',
            4 => 'Walking',
            6 => 'Sitting',
        ];

        foreach ($calls as &$c) {
            $c['service_name'] = $serviceNames[$c['service_id']] ?? 'Service #' . $c['service_id'];
        }

        return $this->response->setJSON([
            'status'         => true,
            'payout'         => $payout,
            'executive_type' => $execType,
            'unit_label'     => ($execType === 'register') ? 'Paid Providers' : 'Calls',
            'calls'          => $calls,
        ]);
    }

    /**
     * AJAX endpoint to view line-item details of an executive's pending calls/conversions before payout release.
     */
    public function getExecutivePendingDetails(int $executiveId)
    {
        // $this->syncAllExecutiveRecords();
        $execName = $this->getExecutiveName($executiveId);
        $reqType  = $this->request ? ($this->request->getGet('executive_type') ?? $this->request->getVar('executive_type')) : null;
        $execType = in_array($reqType, ['register', 'verification']) ? $reqType : $this->getExecutiveType($executiveId);

        // Fetch pending payout row if exists
        $pendingPayoutRow = $this->db->table('executive_payouts')
            ->where('executive_id', $executiveId)
            ->where('status', 'pending')
            ->orderBy('id', 'DESC')
            ->get()
            ->getRowArray();

        if ($pendingPayoutRow && !empty($pendingPayoutRow['executive_type'])) {
            $execType = $pendingPayoutRow['executive_type'];
        }

        $reqAmount = $pendingPayoutRow ? floatval(!empty($pendingPayoutRow['total_amount']) ? $pendingPayoutRow['total_amount'] : ($pendingPayoutRow['amount'] ?? 0)) : 0;
        $reqCalls  = $pendingPayoutRow ? (int)(!empty($pendingPayoutRow['total_calls']) ? $pendingPayoutRow['total_calls'] : ($pendingPayoutRow['calls'] ?? 0)) : 0;
        $reqBreakdown = json_decode($pendingPayoutRow['daily_breakdown'] ?? '[]', true) ?: [];

        $cycle = $this->calculateExecutiveCycle($executiveId, $reqAmount, $execType);

        $totalCalls     = ($reqCalls > 0) ? $reqCalls : $cycle['total_pending_calls'];
        $totalAmount    = ($reqAmount > 0) ? $reqAmount : $cycle['total_pending_amount'];
        $dailyBreakdown = !empty($reqBreakdown) ? $reqBreakdown : $cycle['daily_breakdown'];

        $callsQuery = $this->db->table('executive_call_records ecr')
            ->select('ecr.*, sp.name as provider_name, sp.phone as provider_phone')
            ->join('service_providers sp', 'sp.id = ecr.provider_id', 'left')
            ->where('ecr.executive_id', $executiveId)
            ->where('ecr.executive_type', $execType);

        if ($pendingPayoutRow && !empty($pendingPayoutRow['id'])) {
            $callsQuery->where('ecr.payout_id', (int)$pendingPayoutRow['id']);
        } else {
            $callsQuery->where("(ecr.payout_status = 'pending' OR (ecr.payout_id IS NULL OR ecr.payout_id = 0))");
        }

        $calls = $callsQuery->orderBy('ecr.call_date', 'DESC')
            ->get()
            ->getResultArray();

        if (empty($calls)) {
            $calls = $this->db->table('executive_call_records ecr')
                ->select('ecr.*, sp.name as provider_name, sp.phone as provider_phone')
                ->join('service_providers sp', 'sp.id = ecr.provider_id', 'left')
                ->where('ecr.executive_id', $executiveId)
                ->where('ecr.executive_type', $execType)
                ->orderBy('ecr.call_date', 'DESC')
                ->limit(50)
                ->get()
                ->getResultArray();
        }

        $serviceNames = [
            1 => 'Boarding',
            2 => 'Grooming',
            3 => 'Training',
            4 => 'Walking',
            6 => 'Sitting',
        ];

        foreach ($calls as &$c) {
            $c['service_name']  = $serviceNames[$c['service_id']] ?? 'Service #' . $c['service_id'];
            $c['payout_status'] = (!empty($pendingPayoutRow) && strtolower($pendingPayoutRow['status'] ?? '') === 'paid') ? 'paid' : 'pending';
        }

        $response = $this->response ?: \Config\Services::response();
        return $response->setJSON([
            'status'               => true,
            'executive_id'         => $executiveId,
            'executive_name'       => $execName,
            'executive_type'       => $execType,
            'executive_type_label' => ($execType === 'register') ? 'Registration Executive' : 'Verification Executive',
            'unit_label'           => ($execType === 'register') ? 'Paid Providers' : 'Calls',
            'total_calls'          => $totalCalls > 0 ? $totalCalls : count($calls),
            'total_units'          => $totalCalls > 0 ? $totalCalls : count($calls),
            'total_amount'         => $totalAmount,
            'daily_breakdown'      => $dailyBreakdown,
            'calls'                => $calls,
        ]);
    }

    /**
     * Fetch saved bank details for an executive.
     */
    public function getExecutiveBankDetails(int $executiveId)
    {
        $execName = $this->getExecutiveName($executiveId);
        $details  = $this->db->table('executive_bank_details')
            ->where('executive_id', $executiveId)
            ->get()
            ->getRowArray();

        return $this->response->setJSON([
            'status'         => true,
            'executive_id'   => $executiveId,
            'executive_name' => $execName,
            'bank_details'   => $details,
        ]);
    }

    /**
     * Midnight Auto-Settlement: Automatically calculates daily completed payouts for completed past days (call_date < CURDATE()),
     * credits money directly to Executive Wallets, logs proof transactions, and generates released payout records.
     * Segregates Registration Executives (₹15 / ₹20) and Verification Executives (₹10 / ₹15).
     */
    public function autoSettleMidnightPayouts(bool $returnJson = true, ?string $targetExecType = null)
    {
        $today = date('Y-m-d');

        // Query pending records for all dates strictly before today
        $sql = "
            SELECT executive_id, executive_type, call_date, COUNT(id) as daily_count
            FROM executive_call_records
            WHERE payout_status = 'pending' AND call_date < ?
        ";
        $params = [$today];
        if ($targetExecType && in_array($targetExecType, ['register', 'verification'])) {
            $sql .= " AND executive_type = ? ";
            $params[] = $targetExecType;
        }
        $sql .= " GROUP BY executive_id, executive_type, call_date ORDER BY executive_id ASC, call_date ASC ";

        $pastPendingRecords = $this->db->query($sql, $params)->getResultArray();

        if (empty($pastPendingRecords)) {
            $res = [
                'status'        => true,
                'message'       => 'No pending records for past dates requiring auto-settlement.',
                'settled_count' => 0,
            ];
            return $returnJson ? $this->response->setJSON($res) : $res;
        }

        $settledCount = 0;
        $totalSettledAmount = 0.00;

        foreach ($pastPendingRecords as $group) {
            $execId     = (int)$group['executive_id'];
            $execType   = $group['executive_type'] ?? 'verification';
            $callDate   = $group['call_date'];
            $dailyCount = (int)$group['daily_count'];

            if ($execId <= 0 || $dailyCount <= 0) {
                continue;
            }

            // Apply segregated rate formula:
            // Register: < 15 = ₹15, >= 15 = ₹20
            // Verification: < 15 = ₹10, >= 15 = ₹15
            $rate   = $this->getTierRate($execType, $dailyCount);
            $amount = round($dailyCount * $rate, 2);

            // Fetch call details proof for this date & type
            $callProofs = $this->db->table('executive_call_records ecr')
                ->select('ecr.id, ecr.provider_id, ecr.service_id, ecr.source_table, ecr.source_id, sp.name as provider_name, sp.phone as provider_phone')
                ->join('service_providers sp', 'sp.id = ecr.provider_id', 'left')
                ->where('ecr.executive_id', $execId)
                ->where('ecr.executive_type', $execType)
                ->where('ecr.call_date', $callDate)
                ->where('ecr.payout_status', 'pending')
                ->get()
                ->getResultArray();

            $serviceNames = [
                1 => 'Boarding',
                2 => 'Grooming',
                3 => 'Training',
                4 => 'Walking',
                6 => 'Sitting',
            ];

            $proofItems = [];
            foreach ($callProofs as $cp) {
                $serviceName = $serviceNames[$cp['service_id']] ?? ('Service #' . $cp['service_id']);
                $proofItems[] = [
                    'record_id'      => (int)$cp['id'],
                    'provider_id'    => (int)$cp['provider_id'],
                    'provider_name'  => $cp['provider_name'] ?? 'N/A',
                    'provider_phone' => $cp['provider_phone'] ?? 'N/A',
                    'service_name'   => $serviceName,
                    'source_table'   => $cp['source_table'],
                    'source_id'      => (int)$cp['source_id'],
                ];
            }

            $unitText  = ($execType === 'register') ? 'paid provider conversions' : 'calls';
            $typeTitle = ($execType === 'register') ? 'Registration Conversion' : 'Call Verification';
            $refNo     = 'WALLET-CREDIT-' . strtoupper(substr($execType, 0, 3)) . '-' . $execId . '-' . str_replace('-', '', $callDate);

            $this->db->transStart();

            // 1. Mark records as credited to wallet for this date & type
            $this->db->query("
                UPDATE executive_call_records
                SET payout_status = 'paid', rate = ?, updated_at = ?
                WHERE executive_id = ? AND executive_type = ? AND call_date = ? AND payout_status = 'pending'
            ", [$rate, date('Y-m-d H:i:s'), $execId, $execType, $callDate]);

            // 2. Fetch or create Executive Wallet & Credit balance
            $wallet = $this->db->table('executive_wallets')
                ->where('executive_id', $execId)
                ->get()
                ->getRowArray();

            if (!$wallet) {
                $this->db->table('executive_wallets')->insert([
                    'executive_id'    => $execId,
                    'balance'         => 0.00,
                    'total_earned'    => 0.00,
                    'total_withdrawn' => 0.00,
                    'created_at'      => date('Y-m-d H:i:s'),
                    'updated_at'      => date('Y-m-d H:i:s'),
                ]);
                $walletId = $this->db->insertID();
                $currentBalance = 0.00;
                $currentEarned  = 0.00;
            } else {
                $walletId = (int)$wallet['id'];
                $currentBalance = floatval($wallet['balance'] ?? 0);
                $currentEarned  = floatval($wallet['total_earned'] ?? 0);
            }

            $newBalance = round($currentBalance + $amount, 2);
            $newEarned  = round($currentEarned + $amount, 2);

            $this->db->table('executive_wallets')
                ->where('id', $walletId)
                ->update([
                    'balance'      => $newBalance,
                    'total_earned' => $newEarned,
                    'updated_at'   => date('Y-m-d H:i:s'),
                ]);

            // 3. Record credit entry in executive_wallet_transactions
            $this->db->table('executive_wallet_transactions')->insert([
                'wallet_id'      => $walletId,
                'executive_id'   => $execId,
                'executive_type' => $execType,
                'payout_id'      => null,
                'type'           => 'credit',
                'amount'         => $amount,
                'balance_after'  => $newBalance,
                'description'    => "Midnight {$typeTitle} Credit ({$callDate}): {$dailyCount} {$unitText} @ ₹{$rate}/unit (Proof: " . count($proofItems) . " records)",
                'reference_no'   => $refNo,
                'created_at'     => date('Y-m-d H:i:s'),
            ]);

            $this->db->transComplete();

            if ($this->db->transStatus() !== false) {
                $settledCount++;
                $totalSettledAmount += $amount;
            }
        }

        $res = [
            'status'               => true,
            'message'              => "Auto-settled {$settledCount} daily payout batch(es) totaling ₹" . number_format($totalSettledAmount, 2) . " into executive wallets.",
            'settled_count'        => $settledCount,
            'total_settled_amount' => $totalSettledAmount,
        ];

        return $returnJson ? $this->response->setJSON($res) : $res;
    }

    /**
     * Send Push Notification & In-App Notification after successful payout release.
     */
    protected function sendPayoutSuccessNotification($providerId, $amount, $refNo, $isExecutive = true, string $execType = 'verification')
    {
        try {
            $db = \Config\Database::connect();
            $title = "💰 Payout Released Successfully!";
            $formattedAmount = "₹" . number_format((float)$amount, 2);

            if ($isExecutive) {
                $typeLabel = ($execType === 'register') ? 'provider registration conversion' : 'call verification';
                $msg = "Your {$typeLabel} payout of {$formattedAmount} has been processed and transferred to your bank account! Ref/UTR: {$refNo}";

                // Fetch executive mobile/device token
                $exec = $db->table('employees')->where('id', $providerId)->get()->getRowArray();
                $deviceToken = $exec['fcm_token'] ?? ($exec['device_token'] ?? null);

                if ($db->tableExists('notifications')) {
                    $notifData = [
                        'user_id'    => $providerId,
                        'message'    => $msg,
                        'type'       => 'payout_released',
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                    if ($db->fieldExists('title', 'notifications')) {
                        $notifData['title'] = $title;
                    }
                    if ($db->fieldExists('user_type', 'notifications')) {
                        $notifData['user_type'] = 'employee';
                    }
                    $db->table('notifications')->insert($notifData);
                }
            } else {
                $msg = "Your provider payout of {$formattedAmount} has been processed successfully! Ref/UTR: {$refNo}";

                $sp = $db->table('service_providers')->where('id', $providerId)->get()->getRowArray();
                $deviceToken = $sp['device_token'] ?? ($sp['fcm_token'] ?? null);

                if ($db->tableExists('notifications')) {
                    $notifData = [
                        'user_id'    => $providerId,
                        'message'    => $msg,
                        'type'       => 'payout_released',
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                    if ($db->fieldExists('title', 'notifications')) {
                        $notifData['title'] = $title;
                    }
                    if ($db->fieldExists('user_type', 'notifications')) {
                        $notifData['user_type'] = 'provider';
                    }
                    $db->table('notifications')->insert($notifData);
                }
            }

            // Send FCM Push Notification if device token exists
            if (!empty($deviceToken)) {
                $pushNotify = new \App\Libraries\PushNotify();
                $pushRes    = $pushNotify->notify($deviceToken, $title, $msg);

                $isSent = ($pushRes['status_code'] ?? 0) === 200;
                $db->table('notification_logs')->insert([
                    'provider_id'   => $providerId,
                    'user_type'     => 'provider',
                    'channel'       => 'push',
                    'title'         => $title,
                    'body'          => $msg,
                    'status'        => $isSent ? 'sent' : 'failed',
                    'error_message' => $isSent ? null : ($pushRes['message'] ?? 'Push failed'),
                    'device_token'  => $deviceToken,
                    'sent_at'       => $isSent ? date('Y-m-d H:i:s') : null,
                    'created_at'    => date('Y-m-d H:i:s'),
                ]);
            }
        } catch (\Throwable $e) {
            log_message('error', 'Failed to send payout notification: ' . $e->getMessage());
        }
    }
}
