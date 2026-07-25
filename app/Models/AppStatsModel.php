<?php

namespace App\Models;

use CodeIgniter\Model;

class AppStatsModel extends Model
{
    protected $table = 'app_stats';
    protected $primaryKey = 'id';
    protected $allowedFields = ['total_installs', 'active_installs', 'uninstalls', 'created_at'];
    
    public function getLatestStats()
    {
        return $this->orderBy('created_at', 'DESC')->first();
    }
    
    public function saveStats(array $stats)
    {
        $data = [
            'total_installs' => $stats['total_installs'],
            'active_installs' => $stats['active_installs'],
            'uninstalls' => $stats['uninstalls'],
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->insert($data);
    }
}