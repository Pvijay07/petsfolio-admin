<?php

namespace App\Models;

use CodeIgniter\Model;

class CommunityChannelModel extends Model
{
    protected $table = 'community_channels';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'handle', 'description', 'icon', 'status', 'is_private', 'created_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';
}
