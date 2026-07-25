<?php

namespace App\Models;

use CodeIgniter\Model;

class CommunityFollowModel extends Model
{
    protected $table = 'community_user_follows';
    protected $primaryKey = 'id';
    protected $allowedFields = ['follower_id', 'following_id'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';
}
