<?php

namespace App\Models;

use CodeIgniter\Model;

class CommunityCommentModel extends Model
{
    protected $table = 'community_post_comments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['post_id', 'user_id', 'user_type', 'parent_comment_id', 'comment', 'likes_count', 'dislikes_count'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';
}
