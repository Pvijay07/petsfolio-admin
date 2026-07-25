<?php

namespace App\Models;

use CodeIgniter\Model;

class LoyaltyWalletModel extends Model
{
  protected $table = 'loyalty_wallet';
  protected $primaryKey = 'id';

  protected $allowedFields = [
    'user_id',
    'total_points',
    'total_spent',
    'lifetime_points',
    'tier',
    'last_spin_at'
  ];

  protected $useTimestamps = true;
}
