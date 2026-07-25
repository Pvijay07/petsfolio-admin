<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * ForecastSPDemand CLI Command
 * 
 * Run via CLI: php spark demand:forecast
 * 
 * Aggregates recent bookings to forecast required genuine SPs using the
 * UrbanCompany formula and triggers warning notifications.
 */
class ForecastSPDemand extends BaseCommand
{
  protected $group       = 'Cron';
  protected $name        = 'demand:forecast';
  protected $description = 'Analyzes demand-supply patterns, forecasts required SPs, and fires capacity alerts.';

  public function run(array $params)
  {
    CLI::write('--- SERVICE PROVIDER DEMAND FORECAST START ---', 'yellow');

    $db = \Config\Database::connect();
    $todayDate = date('Y-m-d');

    // Check if the database has any bookings at all to decide on mockup fallback
    $hasRealBookings = $db->table('walking_service_bookings')->countAllResults() > 0;

    // 1. Fetch active booking locations/pincodes in the system dynamically
    $pincodes = $this->getActivePincodes();
    $services = [
      4 => 'walking',
      2 => 'grooming',
      3 => 'training',
      6 => 'sitting',
    ];

    foreach ($pincodes as $pin) {
      foreach ($services as $srvId => $srvName) {
        CLI::write("Analyzing Pincode: $pin | Service: $srvName ...", 'cyan');

        $areaName = $this->getAreaName($pin);

        $tableName = $srvName . '_service_bookings';

        // 2. Count daily bookings in the last 7 days for this pincode/service
        $dailyJobsQuery = $db->table($tableName . ' wsb')
          ->join('quotations q', 'q.booking_id = wsb.id', 'left')
          ->join('service_providers sp', 'q.provider_id = sp.id', 'left')
          ->groupStart()
            ->like('sp.area', $pin)
            ->orLike('sp.service_address', $pin)
            ->orLike('sp.area', $areaName)
            ->orLike('sp.service_address', $areaName)
          ->groupEnd()
          ->where('wsb.service_start_date >=', date('Y-m-d', strtotime('-7 days')))
          ->countAllResults();

        $dailyJobs = ceil($dailyJobsQuery / 7.0);

        // 3. Count active SPs in this area
        $activeSps = $db->table('service_providers sp')
          ->join('sp_services sps', 'sps.provider_id = sp.id', 'left')
          ->groupStart()
             ->where('sps.service_id', $srvId)
             ->orWhere('sps.service_id IS NULL') // Fallback if sp_services not fully populated
          ->groupEnd()
          ->where('sp.status', 'active')
          ->groupStart()
            ->like('sp.area', $pin)
            ->orLike('sp.service_address', $pin)
            ->orLike('sp.area', $areaName)
            ->orLike('sp.service_address', $areaName)
          ->groupEnd()
          ->countAllResults();

        // 4. Count genuine SPs (Genuine score > 70) in this area
        $genuineSps = $db->table('service_providers sp')
          ->join('sp_services sps', 'sps.provider_id = sp.id', 'left')
          ->join('sp_stats ss', 'ss.provider_id = sp.id', 'left')
          ->groupStart()
             ->where('sps.service_id', $srvId)
             ->orWhere('sps.service_id IS NULL')
          ->groupEnd()
          ->where('sp.status', 'active')
          ->where('ss.ranking_score >=', 70.0)
          ->groupStart()
            ->like('sp.area', $pin)
            ->orLike('sp.service_address', $pin)
            ->orLike('sp.area', $areaName)
            ->orLike('sp.service_address', $areaName)
          ->groupEnd()
          ->countAllResults();

        // 5. UrbanCompany Formula: Required Genuine SP = (Daily Jobs * Peak Load Factor) / Average Capacity
        $peakLoadFactor = 1.4;
        $avgCapacity = 3.0; // default for walker (4)
        if ($srvId == 2) $avgCapacity = 2.0; // grooming
        if ($srvId == 3) $avgCapacity = 1.0; // training
        if ($srvId == 6) $avgCapacity = 2.0; // sitting

        $requiredSp = (int)ceil(($dailyJobs * $peakLoadFactor) / $avgCapacity);
        $gap = $requiredSp - $genuineSps;

        CLI::write("  Jobs: $dailyJobs | Active: $activeSps | Genuine: $genuineSps | Required: $requiredSp | Gap: $gap", 'white');

        // 6. Persist to demand_supply_snapshot table
        $snapshotData = [
          'pincode' => $pin,
          'service_type' => $srvName,
          'date' => $todayDate,
          'daily_jobs' => $dailyJobs,
          'active_sp_count' => $activeSps,
          'genuine_sp_count' => $genuineSps
        ];

        // Check if already snapshot exists for today
        $exists = $db->table('demand_supply_snapshot')
          ->where('pincode', $pin)
          ->where('service_type', $srvName)
          ->where('date', $todayDate)
          ->countAllResults();

        if ($exists > 0) {
          $db->table('demand_supply_snapshot')
            ->where('pincode', $pin)
            ->where('service_type', $srvName)
            ->where('date', $todayDate)
            ->update($snapshotData);
        } else {
          $db->table('demand_supply_snapshot')->insert($snapshotData);
        }

        // 7. Generate Alerts if supply drops below warning thresholds
        if ($gap > 5) {
          // Fire Critical Alert Notification to admin_notifications table
          $this->triggerAdminAlert($pin, $srvName, $gap, $genuineSps, $requiredSp);
        }
      }
    }

    CLI::write('--- SERVICE PROVIDER DEMAND FORECAST COMPLETED ---', 'green');
  }

  /**
   * Helper to write critical logs into admin_notifications table.
   */
  protected function triggerAdminAlert(string $pincode, string $serviceName, int $gap, int $current, int $required)
  {
    $db = \Config\Database::connect();

    $title = "CRITICAL: SP Gap in $pincode";
    $message = "Capacity drop detected for '$serviceName' service in zone $pincode. Active Genuine SPs: $current, Required: $required (Gap of $gap). Immediate recruitment required.";

    $alertData = [
      'type' => 'sp_capacity_gap',
      'title' => $title,
      'message' => $message,
      'entity_type' => 'pincode',
      'entity_id' => $pincode,
      'priority' => 'critical',
      'is_read' => 0,
      'metadata' => json_encode([
        'pincode' => $pincode,
        'service' => $serviceName,
        'gap' => $gap,
        'current_sp' => $current,
        'required_sp' => $required
      ]),
      'created_at' => date('Y-m-d H:i:s')
    ];

    // Insert alert if similar unread alert does not exist for this pincode today
    $similar = $db->table('admin_notifications')
      ->where('type', 'sp_capacity_gap')
      ->where('entity_id', $pincode)
      ->where('is_read', 0)
      ->countAllResults();

    if ($similar === 0) {
      $db->table('admin_notifications')->insert($alertData);
      CLI::write("  >>> Alert Created: $title", 'red');
    }
  }

  /**
   * Fetches all active pincodes dynamically across India from the database.
   */
  protected function getActivePincodes(): array
  {
    $pincodes = [];
    $db = \Config\Database::connect();

    // 1. Fetch from service_providers addresses
    $providers = $db->table('service_providers')
      ->select('service_address, area, city')
      ->where('status', 'active')
      ->get()->getResultArray();

    foreach ($providers as $prov) {
      if (!empty($prov['service_address']) && preg_match('/\b([1-9][0-9]{5})\b/', $prov['service_address'], $matches)) {
        $pincodes[] = $matches[1];
      }
      if (!empty($prov['area']) && preg_match('/^([1-9][0-9]{5})$/', trim($prov['area']), $matches)) {
        $pincodes[] = $matches[1];
      }
    }

    // 2. Fetch from existing snap records
    $snapshots = $db->table('demand_supply_snapshot')
      ->distinct()
      ->select('pincode')
      ->get()->getResultArray();
    foreach ($snapshots as $snap) {
      if (!empty($snap['pincode'])) {
        $pincodes[] = trim($snap['pincode']);
      }
    }

    // Filter, validate, unique, sort
    $validPincodes = [];
    foreach ($pincodes as $pin) {
      $pin = trim($pin);
      if (preg_match('/^[1-9][0-9]{5}$/', $pin)) {
        $validPincodes[] = $pin;
      }
    }

    $validPincodes = array_unique($validPincodes);
    sort($validPincodes);

    // Fallback: Hyderabad defaults
    if (empty($validPincodes)) {
      $validPincodes = ['500081', '500032', '500003', '500072', '500084'];
    }

    return $validPincodes;
  }

  private function getAreaName(string $pincode): string
  {
      $pincodeToArea = [
          '500081' => 'Hitec City',
          '500032' => 'Gachibowli',
          '500003' => 'Secunderabad',
          '500072' => 'Kukatpally',
          '500084' => 'Kondapur'
      ];
      return $pincodeToArea[$pincode] ?? 'Unknown Area';
  }
}
