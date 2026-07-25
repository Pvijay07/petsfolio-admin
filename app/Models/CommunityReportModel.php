<?php

namespace App\Models;

use CodeIgniter\Model;

class CommunityReportModel extends Model
{
    protected $table = 'community_post_reports';
    protected $primaryKey = 'id';
    protected $allowedFields = ['post_id', 'user_id', 'reason'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';
}
