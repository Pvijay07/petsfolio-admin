<?php

namespace App\Models;

use CodeIgniter\Model;

class LoyaltyMilestoneModel extends Model
{
  protected $table = 'loyalty_milestones';
  protected $primaryKey = 'id';

  protected $allowedFields = [
    'spend_amount',
    'points_required',
    'tier_name',
    'spin_enabled'
  ];
}
