<?php

namespace App\Models;

use CodeIgniter\Model;

class CommunityPostModel extends Model
{
    protected $table = 'community_posts';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'channel_id',
        'user_id',
        'user_type',
        'post_text',
        'post_type',
        'media_url',
        'location',
        'likes_count',
        'dislikes_count',
        'comments_count',
        'shares_count',
        'saves_count',
        'category',
        'visibility',
        'status',
        'is_pinned',
        'scheduled_time',
        'comment_section'
    ];

    protected $useTimestamps = true;
}
