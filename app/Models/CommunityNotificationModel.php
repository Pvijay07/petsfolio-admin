<?php

namespace App\Models;

use CodeIgniter\Model;

class CommunityNotificationModel extends Model
{
    protected $table = 'community_notifications';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'actor_id', 'type', 'reference_id', 'is_read'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';
}
