<?php

namespace App\Models;

use CodeIgniter\Model;

class CommunityCommentDislikeModel extends Model
{
    protected $table = 'community_comment_dislikes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['comment_id', 'user_id'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';
}
