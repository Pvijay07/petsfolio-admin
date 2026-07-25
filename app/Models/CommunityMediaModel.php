<?php

namespace App\Models;

use CodeIgniter\Model;

class CommunityMediaModel extends Model
{
    protected $table = 'community_post_media';
    protected $primaryKey = 'id';
    protected $allowedFields = ['post_id', 'media_type', 'media_url'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';
}
