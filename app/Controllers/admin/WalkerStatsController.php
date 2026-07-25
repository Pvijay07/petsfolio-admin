<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\WalkerStats;

class WalkerStatsController extends BaseController
{
    protected $statsModel;

    public function __construct()
    {
        $this->statsModel = new WalkerStats();
    }

    /**
     * Display the Walker Leaderboard
     */
    public function leaderboard()
    {
        $data['title'] = 'Walker Leaderboard';
        $data['page']  = 'walker-leaderboard';
        
        // Fetch all walkers sorted by tier and ranking score
        $data['walkers'] = $this->statsModel->getLeaderboard();

        return $this->loadView('admin/provider/leaderboard', $data);
    }
}
