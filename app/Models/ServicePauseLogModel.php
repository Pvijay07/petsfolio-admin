<?php

namespace App\Models;

use CodeIgniter\Model;

class ServicePauseLogModel extends Model
{
  protected $table = 'service_pause_logs';
  protected $primaryKey = 'id';
  protected $allowedFields = [
    'booking_id',
    'pause_start_date',
    'resume_date',
    'remaining_days',
    'status',
    'created_at',
    'updated_at'
  ];
}
