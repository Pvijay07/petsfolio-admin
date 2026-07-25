<?php

namespace App\Models;

use CodeIgniter\Model;

class CommunityPostTagModel extends Model
{
    protected $table = 'community_post_tags';
    protected $primaryKey = 'id';
    protected $allowedFields = ['post_id', 'tag_id'];
}
