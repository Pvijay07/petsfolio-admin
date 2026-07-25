<?php

namespace App\Models;

use CodeIgniter\Model;

class LoyaltySpinModel extends Model
{
  protected $table = 'loyalty_spins';
  protected $primaryKey = 'id';

  protected $allowedFields = [
    'user_id',
    'milestone_id',
    'reward_id',
    'points_used',
    'spin_result',
    'is_collected'
  ];

  protected $useTimestamps = true;
}
