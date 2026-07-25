<?php

namespace App\Models;

use CodeIgniter\Model;

class CommunityChannelJoinModel extends Model
{
    protected $table = 'community_channel_joins';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'channel_id'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';
}
