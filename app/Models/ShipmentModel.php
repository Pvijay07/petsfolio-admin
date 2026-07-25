<?php

namespace App\Models;

use CodeIgniter\Model;

class ShipmentModel extends Model
{
  protected $table = 'shipments';
  protected $allowedFields = [
    'order_id',
    'courier_name',
    'tracking_id',
    'tracking_url',
    'status',
    'shipped_at',
    'delivered_at'
  ];
}