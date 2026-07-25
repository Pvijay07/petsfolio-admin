<?php

namespace App\Models;

use CodeIgniter\Model;

class LoyaltyRewardModel extends Model
{
  protected $table = 'loyalty_rewards';
  protected $primaryKey = 'id';

  protected $allowedFields = [
    'milestone_id',
    'reward_name',
    'reward_type',
    'reward_value',
    'probability',
    'metadata',
    'is_active',
    'image'
  ];
}
