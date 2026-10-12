<?php

namespace App\Models;

use CodeIgniter\Model;

class RefundModel extends Model
{
    protected $table = 'user_refunds';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'amount',
        'reason',
        'attachment',
        'receipt',
        'transaction_id',
        'admin_comments',
        'status',
        'requested_by',
        'processed_by',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = false;

    public function pendingRefunds()
    {
        return $this->db->table('user_refunds')
            ->select('user_refunds.*, users.name, users.phone, users.id as user_id, user_withdrawal_wallet.amount as wallet_balance, employees.name as requested_by_name')
            ->join('users', 'users.id = user_refunds.user_id')
            ->join('user_withdrawal_wallet', 'user_withdrawal_wallet.user_id = users.id', 'left')
            ->join('employees', 'employees.id = user_refunds.requested_by', 'left')
            ->where('user_refunds.status', 'pending')
            ->orderBy('user_refunds.created_at', 'DESC')
            ->get()->getResult();
    }

    public function completedRefunds()
    {
        return $this->db->table('user_refunds')
            ->select('user_refunds.*, users.name, users.phone, employees.name as processed_by_name')
            ->join('users', 'users.id = user_refunds.user_id')
            ->join('employees', 'employees.id = user_refunds.processed_by', 'left')
            ->where('user_refunds.status', 'completed')
            ->orderBy('user_refunds.updated_at', 'DESC')
            ->get()->getResult();
    }
}
