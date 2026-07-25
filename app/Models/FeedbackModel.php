<?php

namespace App\Models;

use CodeIgniter\Model;

class FeedbackModel extends Model
{
  protected $table = 'feedbacks';
  protected $primaryKey = 'id';
  protected $allowedFields = [
    'user_id',
    'user_type',
    'rating',
    'comment',
    'created_at',
    'updated_at'
  ];
}
