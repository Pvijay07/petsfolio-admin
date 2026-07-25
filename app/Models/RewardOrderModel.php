<?php

namespace App\Models;

use CodeIgniter\Model;

class RewardOrderModel extends Model
{
  protected $table = 'reward_orders';
  protected $allowedFields = [
    'user_id',
    'reward_id',
    'order_status',
    'address',
    'houseno_floor',
    'building_blockno',
    'landmark_areaname',
    'area',
    'city',
    'state',
    'country',
    'zip_code',
    'latitude',
    'longitude',
    'type',
    'tracking_url'
  ];
  protected $useTimestamps = true;
}