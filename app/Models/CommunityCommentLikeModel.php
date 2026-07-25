<?php

namespace App\Models;

use CodeIgniter\Model;

class CommunityCommentLikeModel extends Model
{
    protected $table = 'community_comment_likes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['comment_id', 'user_id'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';
}
