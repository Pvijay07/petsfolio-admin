<?php

namespace App\Models;

use CodeIgniter\Model;

class CommunityPostChannelModel extends Model
{
    protected $table = 'community_post_channels';
    protected $primaryKey = 'id';
    protected $allowedFields = ['post_id', 'channel_id'];
}
