<?php

namespace App\Models;

use CodeIgniter\Model;

class LoyaltyUserMilestoneModel extends Model
{
  protected $table = 'loyalty_user_milestones';
  protected $primaryKey = 'id';

  protected $allowedFields = [
    'user_id',
    'milestone_id',
    'is_unlocked',
    'unlocked_at',
    'is_spin_used',
    'spins_remaining',
    'is_collected'
  ];
}
