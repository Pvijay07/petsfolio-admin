<?php

namespace App\Models;

use CodeIgniter\Model;

class CommunityLikeModel extends Model
{
    protected $table = 'community_post_likes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['post_id', 'user_id'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';
}
