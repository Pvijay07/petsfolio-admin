<?php

namespace App\Models;

use CodeIgniter\Model;

class CommunityPostDislikeModel extends Model
{
    protected $table = 'community_post_dislikes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['post_id', 'user_id'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';
}
