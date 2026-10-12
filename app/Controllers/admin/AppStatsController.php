<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\PlayStoreAPI;

class AppStatsController extends BaseController
{
  public function index()
  {
    $data = [
      'title' => 'App Statistics Dashboard',
      'stats' => false,
      'error' => null
    ];

    try {
      // Load our custom library.
      $reporter = new PlayStoreAPI();
      $stats = $reporter->getAppStatistics();

      if ($stats) {
        $data['stats'] = $stats;
      } else {
        $data['error'] = 'Failed to retrieve app statistics. Check the application logs for details.';
      }
    } catch (\Exception $e) {
      $data['error'] = $e->getMessage();
    }

    // Load the view and pass the data.
    return view('admin/app_stats', $data);
  }

  public function updateStats()
  {
    $playStore = new PlayStoreAPI();
    $stats = $playStore->getAppStatistics();

    if ($stats) {
      $statsModel = new \App\Models\AppStatsModel();
      $statsModel->saveStats($stats);

      return redirect()->to('/admin/app-stats')
        ->with('message', 'Statistics updated successfully');
    }

    return redirect()->to('/admin/app-stats')
      ->with('error', 'Failed to update statistics');
  }
}
