<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\DemandSupplyModel;
use App\Libraries\SPScoringLibrary;
use App\Libraries\SPMatchingLibrary;

/**
 * SPManagementController
 * 
 * Exposes admin dashboard tools, alert configurations, scoring engines, 
 * and auto-matching simulations.
 */
class SPManagementController extends BaseController
{
  protected $demandSupplyModel;
  protected $scoringLib;
  protected $matchingLib;

  public function __construct()
  {
    $this->demandSupplyModel = new DemandSupplyModel();
    $this->scoringLib = new SPScoringLibrary();
    $this->matchingLib = new SPMatchingLibrary();
  }

  /**
   * Renders the primary SP Management Dashboard.
   */
  public function index()
  {
    $data['title'] = 'AI-Service Providers Management';
    $data['page']  = 'sp-management';

    // Load summaries for server-side template load
    $data['live_demand'] = $this->demandSupplyModel->getLiveDemandStats();
    $data['live_supply'] = $this->demandSupplyModel->getLiveSupplyStats();
    $data['grid_data'] = $this->demandSupplyModel->getDemandSupplySummary();
    $data['alerts'] = $this->getAdminAlerts();
    $data['simulator_options'] = $this->demandSupplyModel->getSimulatorOptions();
    $data['density_map'] = $this->getDensityMapData();

    return view('admin/sp_management_dashboard', $data);
  }

  /**
   * AJAX endpoint: Retrieve live dashboard aggregate statistics.
   */
  public function getDashboardStats()
  {
    $liveDemand = $this->demandSupplyModel->getLiveDemandStats();
    $liveSupply = $this->demandSupplyModel->getLiveSupplyStats();
    $gridData = $this->demandSupplyModel->getDemandSupplySummary();
    $alerts = $this->getAdminAlerts();
    $densityMap = $this->getDensityMapData();

    return $this->response->setJSON([
      'status' => 'success',
      'data' => [
        'demand' => $liveDemand,
        'supply' => $liveSupply,
        'grid' => $gridData,
        'alerts' => $alerts,
        'density_map' => $densityMap
      ]
    ]);
  }

  /**
   * Compiles density map data from REAL live database tables.
   * - SP counts from `service_providers` (grouped by city/state/area).
   * - Demand (bookings) counted from all 4 service booking tables.
   * - Genuine SPs from `sp_stats` with ranking_score >= 70.
   * NO mock data. Only real records.
   */
  private function getDensityMapData(): array
  {
    $db = \Config\Database::connect();

    // ─── 1. Build a lookup of genuine provider_ids (ranking_score >= 70) ───
    $genuineMap = [];
    $genuineRows = $db->query("
      SELECT ss.provider_id
      FROM sp_stats ss
      JOIN service_providers sp ON sp.id = ss.provider_id
      WHERE ss.ranking_score >= 70
        AND sp.status = 'active'
    ")->getResultArray();
    foreach ($genuineRows as $gr) {
      $genuineMap[(int)$gr['provider_id']] = true;
    }

    // ─── 2. Fetch all service providers to normalize locations in PHP ───
    $allSps = $db->table('service_providers')
      ->select('id, area, city, state, service_address, service_latitude, service_longitude, status')
      ->get()->getResultArray();

    $pincodeToArea = [
      '500081' => 'Hitec City',
      '500032' => 'Gachibowli',
      '500003' => 'Secunderabad',
      '500072' => 'Kukatpally',
      '500084' => 'Kondapur',
      '500033' => 'Jubilee Hills',
      '500034' => 'Banjara Hills',
    ];

    $pincodeCoords = [
      '500081' => ['lat' => 17.4483, 'lng' => 78.3741],
      '500032' => ['lat' => 17.4195, 'lng' => 78.3489],
      '500003' => ['lat' => 17.4399, 'lng' => 78.4983],
      '500072' => ['lat' => 17.4855, 'lng' => 78.3885],
      '500084' => ['lat' => 17.4651, 'lng' => 78.3587],
      '500033' => ['lat' => 17.4300, 'lng' => 78.4000],
      '500034' => ['lat' => 17.4150, 'lng' => 78.4100],
    ];

    $prefixMappings = [
      '11' => ['state' => 'Delhi', 'city' => 'Delhi', 'lat' => 28.6139, 'lng' => 77.2090],
      '12' => ['state' => 'Haryana', 'city' => 'Gurugram', 'lat' => 28.4595, 'lng' => 77.0266],
      '13' => ['state' => 'Haryana', 'city' => 'Faridabad', 'lat' => 28.4089, 'lng' => 77.3178],
      '14' => ['state' => 'Punjab', 'city' => 'Amritsar', 'lat' => 31.6340, 'lng' => 74.8723],
      '15' => ['state' => 'Punjab', 'city' => 'Ludhiana', 'lat' => 30.9010, 'lng' => 75.8573],
      '16' => ['state' => 'Chandigarh', 'city' => 'Chandigarh', 'lat' => 30.7333, 'lng' => 76.7794],
      '17' => ['state' => 'Himachal Pradesh', 'city' => 'Shimla', 'lat' => 31.1048, 'lng' => 77.1734],
      '20' => ['state' => 'Uttar Pradesh', 'city' => 'Noida', 'lat' => 28.5355, 'lng' => 77.3910],
      '22' => ['state' => 'Uttar Pradesh', 'city' => 'Lucknow', 'lat' => 26.8467, 'lng' => 80.9462],
      '24' => ['state' => 'Uttarakhand', 'city' => 'Dehradun', 'lat' => 30.3165, 'lng' => 78.0322],
      '30' => ['state' => 'Rajasthan', 'city' => 'Jaipur', 'lat' => 26.9124, 'lng' => 75.7873],
      '31' => ['state' => 'Rajasthan', 'city' => 'Udaipur', 'lat' => 24.5854, 'lng' => 73.7125],
      '38' => ['state' => 'Gujarat', 'city' => 'Ahmedabad', 'lat' => 23.0225, 'lng' => 72.5714],
      '40' => ['state' => 'Maharashtra', 'city' => 'Mumbai', 'lat' => 19.0760, 'lng' => 72.8777],
      '41' => ['state' => 'Maharashtra', 'city' => 'Pune', 'lat' => 18.5204, 'lng' => 73.8567],
      '44' => ['state' => 'Maharashtra', 'city' => 'Nagpur', 'lat' => 21.1458, 'lng' => 79.0882],
      '45' => ['state' => 'Madhya Pradesh', 'city' => 'Indore', 'lat' => 22.7196, 'lng' => 75.8577],
      '46' => ['state' => 'Madhya Pradesh', 'city' => 'Bhopal', 'lat' => 23.2599, 'lng' => 77.4126],
      '49' => ['state' => 'Chhattisgarh', 'city' => 'Raipur', 'lat' => 21.2514, 'lng' => 81.6296],
      '50' => ['state' => 'Telangana', 'city' => 'Hyderabad', 'lat' => 17.3850, 'lng' => 78.4867],
      '53' => ['state' => 'Andhra Pradesh', 'city' => 'Visakhapatnam', 'lat' => 17.6868, 'lng' => 83.2185],
      '56' => ['state' => 'Karnataka', 'city' => 'Bengaluru', 'lat' => 12.9716, 'lng' => 77.5946],
      '57' => ['state' => 'Karnataka', 'city' => 'Mysore', 'lat' => 12.2958, 'lng' => 76.6394],
      '60' => ['state' => 'Tamil Nadu', 'city' => 'Chennai', 'lat' => 13.0827, 'lng' => 80.2707],
      '62' => ['state' => 'Tamil Nadu', 'city' => 'Madurai', 'lat' => 9.9252, 'lng' => 78.1198],
      '68' => ['state' => 'Kerala', 'city' => 'Kochi', 'lat' => 9.9312, 'lng' => 76.2673],
      '70' => ['state' => 'West Bengal', 'city' => 'Kolkata', 'lat' => 22.5726, 'lng' => 88.3639],
      '75' => ['state' => 'Odisha', 'city' => 'Bhubaneswar', 'lat' => 20.2961, 'lng' => 85.8245],
      '80' => ['state' => 'Bihar', 'city' => 'Patna', 'lat' => 25.5941, 'lng' => 85.1376],
      '83' => ['state' => 'Jharkhand', 'city' => 'Ranchi', 'lat' => 23.3441, 'lng' => 85.3096],
    ];

    // Pre-pass: Build dynamic mapping of pincode to Area Name from all SPs
    foreach ($allSps as $sp) {
      $pincode = '';
      if (!empty($sp['service_address']) && preg_match('/\b([1-9][0-9]{5})\b/', $sp['service_address'], $matches)) {
        $pincode = $matches[1];
      } elseif (!empty($sp['area']) && preg_match('/\b([1-9][0-9]{5})\b/', $sp['area'], $matches)) {
        $pincode = $matches[1];
      }

      if (!empty($pincode) && !isset($pincodeToArea[$pincode])) {
        $rawArea = trim($sp['area'] ?? '');
        if (!empty($rawArea) && !is_numeric($rawArea)) {
          $pincodeToArea[$pincode] = ucwords(strtolower($rawArea));
        }
      }
    }

    $providerLocations = [];
    $areaGroups = [];

    foreach ($allSps as $sp) {
      $spId = (int)$sp['id'];
      
      // Extract pincode
      $pincode = '';
      if (!empty($sp['service_address']) && preg_match('/\b([1-9][0-9]{5})\b/', $sp['service_address'], $matches)) {
        $pincode = $matches[1];
      } elseif (!empty($sp['area']) && preg_match('/\b([1-9][0-9]{5})\b/', $sp['area'], $matches)) {
        $pincode = $matches[1];
      }

      // Normalize state and city
      $state = !empty($sp['state']) ? ucwords(strtolower(trim($sp['state']))) : '';
      $city  = !empty($sp['city']) ? ucwords(strtolower(trim($sp['city']))) : '';

      if (empty($state) || empty($city)) {
        $prefix = substr($pincode, 0, 2);
        if (isset($prefixMappings[$prefix])) {
          if (empty($state)) $state = $prefixMappings[$prefix]['state'];
          if (empty($city))  $city  = $prefixMappings[$prefix]['city'];
        }
      }

      if (empty($state)) $state = 'Telangana';
      if (empty($city))  $city  = 'Hyderabad';

      // Normalize Area
      $areaName = '';
      if (!empty($pincode) && isset($pincodeToArea[$pincode])) {
        $areaName = $pincodeToArea[$pincode];
      } else {
        $rawArea = trim($sp['area'] ?? '');
        if (!empty($rawArea) && !is_numeric($rawArea)) {
          $areaName = ucwords(strtolower($rawArea));
        } else {
          // Try to see if address contains any standard area names
          foreach ($pincodeToArea as $pin => $aName) {
            if (stripos($sp['service_address'] ?? '', $aName) !== false) {
              $areaName = $aName;
              break;
            }
          }
          if (empty($areaName)) {
            $areaName = !empty($pincode) ? 'Zone ' . $pincode : 'Unknown';
          }
        }
      }

      // Lat/Lng coordinates
      $lat = (float)($sp['service_latitude'] ?? 0);
      $lng = (float)($sp['service_longitude'] ?? 0);

      if ($lat == 0 || $lng == 0) {
        if (!empty($pincode) && isset($pincodeCoords[$pincode])) {
          $lat = $pincodeCoords[$pincode]['lat'];
          $lng = $pincodeCoords[$pincode]['lng'];
        } else {
          $prefix = substr($pincode, 0, 2);
          if (isset($prefixMappings[$prefix])) {
            $lat = $prefixMappings[$prefix]['lat'];
            $lng = $prefixMappings[$prefix]['lng'];
          } else {
            $lat = 17.3850;
            $lng = 78.4867;
          }
        }
      }

      $providerLocations[$spId] = [
        'id'          => $spId,
        'status'      => $sp['status'],
        'state'       => $state,
        'city'        => $city,
        'area'        => $areaName,
        'pincode'     => $pincode,
        'lat'         => $lat,
        'lng'         => $lng,
        'is_genuine'  => isset($genuineMap[$spId])
      ];

      // Group active SPs
      if ($sp['status'] === 'active') {
        $key = $state . '|' . $city . '|' . $areaName . '|' . $pincode;
        if (!isset($areaGroups[$key])) {
          $areaGroups[$key] = [
            'state'         => $state,
            'city'          => $city,
            'area'          => $areaName,
            'pincode'       => $pincode,
            'sp_count'      => 0,
            'genuine_count' => 0,
            'lats'          => [],
            'lngs'          => [],
            'default_lat'   => $lat,
            'default_lng'   => $lng
          ];
        }

        $areaGroups[$key]['sp_count']++;
        if (isset($genuineMap[$spId])) {
          $areaGroups[$key]['genuine_count']++;
        }
        $areaGroups[$key]['lats'][] = $lat;
        $areaGroups[$key]['lngs'][] = $lng;
      }
    }

    // ─── 3. Count real bookings per area from ALL service booking tables ───
    $thirtyDaysAgo = date('Y-m-d', strtotime('-30 days'));
    $demandCounts = [];
    $tables = [
      'walking_service_bookings',
      'grooming_service_bookings',
      'training_service_bookings',
      'sitting_service_bookings'
    ];

    foreach ($tables as $table) {
      $bookingRows = $db->query("
        SELECT DISTINCT b.id, q.provider_id
        FROM {$table} b
        JOIN quotations q ON q.booking_id = b.id
        WHERE b.created_at >= ?
          AND q.status IN ('Accepted', 'Completed')
      ", [$thirtyDaysAgo])->getResultArray();

      foreach ($bookingRows as $row) {
        $pId = (int)$row['provider_id'];
        if (isset($providerLocations[$pId])) {
          $loc = $providerLocations[$pId];
          $key = $loc['state'] . '|' . $loc['city'] . '|' . $loc['area'] . '|' . $loc['pincode'];
          if (!isset($demandCounts[$key])) {
            $demandCounts[$key] = 0;
          }
          $demandCounts[$key]++;
        }
      }
    }

    // ─── 4. Assemble final structure grouped by State → City → Areas ───
    $stateCityData = [];

    foreach ($areaGroups as $key => $group) {
      $state    = $group['state'];
      $city     = $group['city'];
      $areaName = $group['area'];
      $pincode  = $group['pincode'];
      $spCount  = $group['sp_count'];
      $genuine  = $group['genuine_count'];

      $avgLat = count($group['lats']) > 0 ? array_sum($group['lats']) / count($group['lats']) : $group['default_lat'];
      $avgLng = count($group['lngs']) > 0 ? array_sum($group['lngs']) / count($group['lngs']) : $group['default_lng'];

      $demand = $demandCounts[$key] ?? 0;

      // Determine level based on supply vs demand
      if ($demand === 0) {
        $level = 'Balanced';
      } else {
        $ratio = $spCount / $demand;
        if ($ratio < 0.70) {
          $level = 'Shortage';
        } elseif ($ratio < 0.95) {
          $level = 'Tight';
        } else {
          $level = 'Balanced';
        }
      }

      if (!isset($stateCityData[$state])) {
        $stateCityData[$state] = [];
      }
      if (!isset($stateCityData[$state][$city])) {
        $stateCityData[$state][$city] = [
          'center' => ['lat' => $avgLat, 'lng' => $avgLng, 'zoom' => 12],
          'areas' => []
        ];
      }

      $stateCityData[$state][$city]['areas'][] = [
        'name'    => $areaName,
        'pincode' => $pincode,
        'demand'  => $demand,
        'supply'  => $spCount,
        'genuine' => $genuine,
        'level'   => $level,
        'lat'     => $avgLat,
        'lng'     => $avgLng
      ];
    }

    return $stateCityData;
  }

  /**
   * Helper to resolve geographical coordinates, city, and state dynamically from an Indian pincode.
   */
  private function resolvePincodeDetails(string $pincode): array
  {
    $db = \Config\Database::connect();
    
    // Try to query from service_providers dynamically first
    $sp = $db->table('service_providers')
      ->select('city, state, service_latitude, service_longitude, area')
      ->groupStart()
        ->like('service_address', $pincode)
        ->orLike('area', $pincode)
      ->groupEnd()
      ->where('service_latitude IS NOT NULL')
      ->where('service_longitude IS NOT NULL')
      ->where('service_latitude !=', 0)
      ->where('service_longitude !=', 0)
      ->get()->getRowArray();

    if (!empty($sp) && !empty($sp['city'])) {
      $spArea = ucwords(strtolower(trim($sp['area'] ?? '')));
      return [
        'city' => ucwords(strtolower(trim($sp['city']))),
        'state' => ucwords(strtolower(trim($sp['state'] ?? ''))),
        'lat' => (float)$sp['service_latitude'],
        'lng' => (float)$sp['service_longitude'],
        'area' => empty($spArea) ? $this->demandSupplyModel->resolveAreaName($pincode) : $spArea
      ];
    }

    // Pincode prefix-based defaults for all major states and cities in India
    $prefix = substr($pincode, 0, 2);
    $state = 'Other';
    $city = 'Zone ' . $pincode;
    $lat = 20.5937; // Center of India
    $lng = 78.9629;
    
    // Pincode prefix mapping
    $mappings = [
      '11' => ['state' => 'Delhi', 'city' => 'Delhi', 'lat' => 28.6139, 'lng' => 77.2090],
      '12' => ['state' => 'Haryana', 'city' => 'Gurugram', 'lat' => 28.4595, 'lng' => 77.0266],
      '13' => ['state' => 'Haryana', 'city' => 'Faridabad', 'lat' => 28.4089, 'lng' => 77.3178],
      '14' => ['state' => 'Punjab', 'city' => 'Amritsar', 'lat' => 31.6340, 'lng' => 74.8723],
      '15' => ['state' => 'Punjab', 'city' => 'Ludhiana', 'lat' => 30.9010, 'lng' => 75.8573],
      '16' => ['state' => 'Chandigarh', 'city' => 'Chandigarh', 'lat' => 30.7333, 'lng' => 76.7794],
      '17' => ['state' => 'Himachal Pradesh', 'city' => 'Shimla', 'lat' => 31.1048, 'lng' => 77.1734],
      '20' => ['state' => 'Uttar Pradesh', 'city' => 'Noida', 'lat' => 28.5355, 'lng' => 77.3910],
      '22' => ['state' => 'Uttar Pradesh', 'city' => 'Lucknow', 'lat' => 26.8467, 'lng' => 80.9462],
      '24' => ['state' => 'Uttarakhand', 'city' => 'Dehradun', 'lat' => 30.3165, 'lng' => 78.0322],
      '30' => ['state' => 'Rajasthan', 'city' => 'Jaipur', 'lat' => 26.9124, 'lng' => 75.7873],
      '31' => ['state' => 'Rajasthan', 'city' => 'Udaipur', 'lat' => 24.5854, 'lng' => 73.7125],
      '38' => ['state' => 'Gujarat', 'city' => 'Ahmedabad', 'lat' => 23.0225, 'lng' => 72.5714],
      '40' => ['state' => 'Maharashtra', 'city' => 'Mumbai', 'lat' => 19.0760, 'lng' => 72.8777],
      '41' => ['state' => 'Maharashtra', 'city' => 'Pune', 'lat' => 18.5204, 'lng' => 73.8567],
      '44' => ['state' => 'Maharashtra', 'city' => 'Nagpur', 'lat' => 21.1458, 'lng' => 79.0882],
      '45' => ['state' => 'Madhya Pradesh', 'city' => 'Indore', 'lat' => 22.7196, 'lng' => 75.8577],
      '46' => ['state' => 'Madhya Pradesh', 'city' => 'Bhopal', 'lat' => 23.2599, 'lng' => 77.4126],
      '49' => ['state' => 'Chhattisgarh', 'city' => 'Raipur', 'lat' => 21.2514, 'lng' => 81.6296],
      '50' => ['state' => 'Telangana', 'city' => 'Hyderabad', 'lat' => 17.3850, 'lng' => 78.4867],
      '53' => ['state' => 'Andhra Pradesh', 'city' => 'Visakhapatnam', 'lat' => 17.6868, 'lng' => 83.2185],
      '56' => ['state' => 'Karnataka', 'city' => 'Bengaluru', 'lat' => 12.9716, 'lng' => 77.5946],
      '57' => ['state' => 'Karnataka', 'city' => 'Mysore', 'lat' => 12.2958, 'lng' => 76.6394],
      '60' => ['state' => 'Tamil Nadu', 'city' => 'Chennai', 'lat' => 13.0827, 'lng' => 80.2707],
      '62' => ['state' => 'Tamil Nadu', 'city' => 'Madurai', 'lat' => 9.9252, 'lng' => 78.1198],
      '68' => ['state' => 'Kerala', 'city' => 'Kochi', 'lat' => 9.9312, 'lng' => 76.2673],
      '70' => ['state' => 'West Bengal', 'city' => 'Kolkata', 'lat' => 22.5726, 'lng' => 88.3639],
      '75' => ['state' => 'Odisha', 'city' => 'Bhubaneswar', 'lat' => 20.2961, 'lng' => 85.8245],
      '80' => ['state' => 'Bihar', 'city' => 'Patna', 'lat' => 25.5941, 'lng' => 85.1376],
      '83' => ['state' => 'Jharkhand', 'city' => 'Ranchi', 'lat' => 23.3441, 'lng' => 85.3096]
    ];

    if (isset($mappings[$prefix])) {
      $state = $mappings[$prefix]['state'];
      $city = $mappings[$prefix]['city'];
      $lat = $mappings[$prefix]['lat'];
      $lng = $mappings[$prefix]['lng'];
    }

    // Add slight random offset based on pincode to spread markers in the same city
    $offsetLat = ((int)substr($pincode, -3) - 500) / 4000;
    $offsetLng = ((int)substr($pincode, -2) - 50) / 4000;

    return [
      'city' => $city,
      'state' => $state,
      'lat' => $lat + $offsetLat,
      'lng' => $lng + $offsetLng,
      'area' => $this->demandSupplyModel->resolveAreaName($pincode)
    ];
  }

  /**
   * AJAX endpoint: Evaluate a specific SP and return detailed Genuine score factors.
   */
  public function runScoringEvaluation($providerId)
  {
    if (empty($providerId)) {
      return $this->response->setJSON(['status' => 'error', 'message' => 'Provider ID is required'])->setStatusCode(400);
    }

    $result = $this->scoringLib->calculateScore((int)$providerId);

    // Update database score cache in sp_stats
    $db = \Config\Database::connect();
    $db->table('sp_stats')
      ->where('provider_id', $providerId)
      ->update(['ranking_score' => $result['genuine_score']]);

    return $this->response->setJSON([
      'status' => 'success',
      'data' => $result
    ]);
  }

  /**
   * AJAX endpoint: Run matching simulator.
   */
  public function runMatchingSimulation()
  {
    $lat = (float)$this->request->getPost('latitude');
    $lng = (float)$this->request->getPost('longitude');
    $serviceId = (int)($this->request->getPost('service_id') ?? 4); // default walking
    $preferredTime = $this->request->getPost('preferred_time') ?? '08:00:00';

    $tags = $this->request->getPost('pet_tags') ?? []; // e.g. ['aggressive', 'senior']
    $breed = $this->request->getPost('pet_breed') ?? '';

    if (empty($lat) || empty($lng)) {
      return $this->response->setJSON(['status' => 'error', 'message' => 'Valid coordinates are required.'])->setStatusCode(400);
    }

    $petDetails = [
      'tags' => $tags,
      'breed' => $breed
    ];

    $matches = $this->matchingLib->findMatches($lat, $lng, $serviceId, $preferredTime, $petDetails);

    return $this->response->setJSON([
      'status' => 'success',
      'client_location' => ['lat' => $lat, 'lng' => $lng],
      'matches_count' => count($matches),
      'data' => $matches
    ]);
  }

  /**
   * Returns compile warnings and alerts for capacities.
   */
  public function getAdminAlerts(): array
  {
    $alerts = [];
    $db = \Config\Database::connect();

    // Helper to add alerts grouped
    $addAlert = function(&$alerts, $region, $level, $title, $message, $time) {
        $city = $this->demandSupplyModel->resolveCityName($region['pincode'] ?? '');
        $category = ucfirst($region['service_type'] ?? 'General');
        
        if (!isset($alerts[$city])) $alerts[$city] = [];
        if (!isset($alerts[$city][$category])) $alerts[$city][$category] = [];
        
        $alerts[$city][$category][] = [
            'id' => uniqid('alert_'),
            'level' => $level,
            'title' => $title,
            'message' => $message,
            'time' => $time
        ];
    };

    // 1. Critical Alert: SP-to-demand ratio < 0.8 in any location
    $criticalRegions = $db->table('demand_supply_snapshot')
      ->where('active_sp_count < (daily_jobs * 0.8)', null, false)
      ->where('daily_jobs >', 5)
      ->get()->getResultArray();

    foreach ($criticalRegions as $region) {
      $ratio = $region['daily_jobs'] > 0 ? round($region['active_sp_count'] / $region['daily_jobs'], 2) : 0;
      $areaName = $this->demandSupplyModel->resolveAreaName($region['pincode']);
      $addAlert($alerts, $region, 'critical', 
        "Low SP-to-demand ratio in {$areaName}", 
        "SP-to-demand ratio is currently {$ratio} (less than critical 0.8 threshhold) for {$region['service_type']} in {$areaName} ({$region['pincode']}).", 
        "Just now"
      );
    }

    // 2. Critical Alert: Low genuine SPs in any region
    $lowCoverageRegions = $db->table('demand_supply_snapshot')
      ->where('genuine_sp_count <', 5)
      ->get()->getResultArray();

    foreach ($lowCoverageRegions as $region) {
      $areaName = $this->demandSupplyModel->resolveAreaName($region['pincode']);
      $addAlert($alerts, $region, 'critical',
        "Severe capacity drop in {$areaName}",
        "Genuine service provider capacity dropped below 5 in {$areaName} ({$region['pincode']}), causing high booking risk.",
        "10 mins ago"
      );
    }

    // 3. Medium Alert: High gap regions
    $highGapRegions = $db->table('demand_supply_snapshot')
      ->where('daily_jobs >', 10)
      ->get()->getResultArray();

    foreach ($highGapRegions as $region) {
      $required = (int)ceil($region['daily_jobs'] * 1.4 / 3);
      if (($required - $region['genuine_sp_count']) > 5) {
        $areaName = $this->demandSupplyModel->resolveAreaName($region['pincode']);
        $addAlert($alerts, $region, 'warning',
          "High cancellations / Gap in {$areaName}",
          "High shortage of genuine workers in {$areaName}. Gap is " . ($required - $region['genuine_sp_count']) . " SPs.",
          "2 hrs ago"
        );
      }
    }

    // 5. Default healthy alert if nothing major exists
    if (empty($alerts)) {
      $alerts['Hyderabad']['System'][] = [
        'id' => 'healthy_default',
        'level' => 'healthy',
        'title' => 'System Capacities Normal',
        'message' => 'All active slots and service zones have sufficient genuine SP coverage.',
        'time' => 'All clear'
      ];
    }

    return $alerts;
  }
}
