<?php

namespace App\Models;

use CodeIgniter\Model;

class LoyaltyTransactionModel extends Model
{
  protected $table = 'loyalty_transactions';
  protected $primaryKey = 'id';

  protected $allowedFields = [
    'user_id',
    'points',
    'type',
    'source',
    'reference_id',
    'description',
    'expiry_date'
  ];
}
