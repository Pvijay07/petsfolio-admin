<?php

namespace App\Models;

use CodeIgniter\Model;

class LoyaltySettingModel extends Model
{
  protected $table = 'loyalty_settings';
  protected $primaryKey = 'id';

  protected $allowedFields = [
    'key',
    'value',
    'type',
    'description'
  ];
}