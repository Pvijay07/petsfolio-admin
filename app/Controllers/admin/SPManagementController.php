<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\DemandSupplyModel;
use App\Libraries\SPScoringLibrary;
use App\Libraries\SPMatchingLibrary;
<<<<<<< HEAD
=======
use App\Libraries\AIAnalyst\GeminiAnalyst;
>>>>>>> origin/main

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
<<<<<<< HEAD
    $data['density_map'] = $this->getDensityMapData();
=======
    $data['city_demand_supply'] = $this->getCityDemandSupplyData();
    $data['google_maps_key'] = env('gMapKey') ?: 'AIzaSyAK-fLJRaxrzL1S6_2TuM9BARw9Ehb2rP8';
>>>>>>> origin/main

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
<<<<<<< HEAD
=======
   * Computes live demand vs supply metrics strictly from real database tables:
   * - Real bookings from walking_service_bookings, grooming_service_bookings, 
   *   training_service_bookings, and sitting_service_bookings joined with user_addresses.
   * - Real active provider counts from service_providers joined with sp_services.
   */
  public function getCityDemandSupplyData(?string $selectedCity = 'Hyderabad', ?string $selectedService = 'All Services', int $days = 30): array
  {
    $db = \Config\Database::connect();
    $city = !empty($selectedCity) ? trim($selectedCity) : 'Hyderabad';
    $service = !empty($selectedService) ? trim($selectedService) : 'All Services';

    // 1. Fetch available cities dynamically from live service providers and addresses
    $citiesRes = $db->query("
      SELECT DISTINCT COALESCE(NULLIF(TRIM(city), ''), 'Hyderabad') as city_name
      FROM service_providers
      WHERE city IS NOT NULL AND TRIM(city) != ''
      ORDER BY (city = 'Hyderabad') DESC, (city = 'Bengaluru') DESC, (city = 'Secunderabad') DESC, city ASC
    ")->getResultArray();

    $availableCities = ['Hyderabad', 'Bengaluru', 'Mumbai', 'Secunderabad', 'Chennai', 'Delhi', 'Pune'];
    foreach ($citiesRes as $cr) {
      $cName = ucwords(strtolower(trim($cr['city_name'])));
      if (!empty($cName) && !in_array($cName, $availableCities, true) && strlen($cName) > 2) {
        $availableCities[] = $cName;
      }
    }

    // 2. Service Filter Where Clause
    $serviceWhere = "";
    $spServiceWhere = "";
    if (strtolower($service) !== 'all services' && strtolower($service) !== 'all' && !empty($service)) {
      $cleanSvc = strtolower($service);
      $escaped = $db->escapeLikeString($cleanSvc);
      $serviceWhere = " AND b.service_type LIKE '%{$escaped}%' ";
      $spServiceWhere = " AND (LOWER(s.name) LIKE '%{$escaped}%' OR LOWER(sps.service_id) LIKE '%{$escaped}%') ";
    }

    // Date Filter Where Clause
    $dateWhere = "";
    if ($days > 0) {
      $dateWhere = " AND b.created_at >= DATE_SUB(NOW(), INTERVAL {$days} DAY) ";
    }

    // 3. Real Demand Query across all 4 booking tables with GPS coordinates
    $demandSql = "
      SELECT 
        COALESCE(NULLIF(TRIM(ua.city), ''), 'Hyderabad') as city,
        COALESCE(NULLIF(TRIM(ua.area), ''), NULLIF(TRIM(ua.landmark_areaname), ''), 'General') as area,
        b.service_type,
        AVG(NULLIF(ua.latitude, 0)) as avg_lat,
        AVG(NULLIF(ua.longitude, 0)) as avg_lng,
        COUNT(b.id) as total_demand
      FROM (
        SELECT id, address_id, created_at, 'walking' as service_type FROM walking_service_bookings
        UNION ALL
        SELECT id, address_id, created_at, 'grooming' as service_type FROM grooming_service_bookings
        UNION ALL
        SELECT id, address_id, created_at, 'training' as service_type FROM training_service_bookings
        UNION ALL
        SELECT id, address_id, created_at, 'sitting' as service_type FROM sitting_service_bookings
      ) b
      JOIN user_addresses ua ON ua.id = b.address_id
      WHERE 1=1
      {$dateWhere}
      {$serviceWhere}
      GROUP BY city, area, b.service_type
    ";
    $demandRows = $db->query($demandSql)->getResultArray();

    // 4. Real Supply Query from service_providers with GPS coordinates
    $supplySql = "
      SELECT 
        COALESCE(NULLIF(TRIM(sp.city), ''), 'Hyderabad') as city,
        COALESCE(NULLIF(TRIM(sp.area), ''), 'General') as area,
        AVG(NULLIF(COALESCE(sp.service_latitude, sp.permanent_latitude), 0)) as avg_lat,
        AVG(NULLIF(COALESCE(sp.service_longitude, sp.permanent_longitude), 0)) as avg_lng,
        COUNT(DISTINCT sp.id) as active_sp_count
      FROM service_providers sp
      LEFT JOIN sp_services sps ON sps.provider_id = sp.id
      LEFT JOIN services s ON s.id = sps.service_id
      WHERE sp.status = 'active'
      {$spServiceWhere}
      GROUP BY city, area
    ";
    $supplyRows = $db->query($supplySql)->getResultArray();

    // Curated GPS Coordinates Dictionary for India Metros
    $knownCoords = [
      'Masab Tank'         => ['lat' => 17.4042, 'lng' => 78.4526],
      'Basheer Bagh'       => ['lat' => 17.4022, 'lng' => 78.4764],
      'Miyapur'            => ['lat' => 17.4968, 'lng' => 78.3614],
      'West Marredpally'   => ['lat' => 17.4486, 'lng' => 78.5085],
      'Malkajgiri'         => ['lat' => 17.4475, 'lng' => 78.5303],
      'Hastinapuram'       => ['lat' => 17.3228, 'lng' => 78.5524],
      'Serilingampalle'    => ['lat' => 17.4837, 'lng' => 78.3158],
      'Kukatpally'         => ['lat' => 17.4849, 'lng' => 78.4138],
      'Madhapur'           => ['lat' => 17.4483, 'lng' => 78.3915],
      'Gachibowli'         => ['lat' => 17.4401, 'lng' => 78.3489],
      'Jubilee Hills'      => ['lat' => 17.4319, 'lng' => 78.4073],
      'Banjara Hills'      => ['lat' => 17.4156, 'lng' => 78.4350],
      'Hitec City'         => ['lat' => 17.4435, 'lng' => 78.3772],
      'Begumpet'           => ['lat' => 17.4447, 'lng' => 78.4664],
      'Ameerpet'           => ['lat' => 17.4375, 'lng' => 78.4482],
      'Dilsukhnagar'       => ['lat' => 17.3688, 'lng' => 78.5247],
      'Sanjeeva Reddy Nagar' => ['lat' => 17.4431, 'lng' => 78.4439],
      'Nizampet'           => ['lat' => 17.5186, 'lng' => 78.3846],
      'Kondapur'           => ['lat' => 17.4699, 'lng' => 78.3578],
      'Bachupally'         => ['lat' => 17.5332, 'lng' => 78.3644],
      'Kompally'           => ['lat' => 17.5385, 'lng' => 78.4862],
      'Sainikpuri'         => ['lat' => 17.4891, 'lng' => 78.5471],
      'Padmarao Nagar'     => ['lat' => 17.4265, 'lng' => 78.5085],
      'Kachiguda'          => ['lat' => 17.3917, 'lng' => 78.4977],
      'Peerzadiguda'       => ['lat' => 17.4089, 'lng' => 78.5833],
      'Himayatnagar'       => ['lat' => 17.4038, 'lng' => 78.4882],
      'Amberpet'           => ['lat' => 17.3949, 'lng' => 78.5173],
      'Kothapet'           => ['lat' => 17.3648, 'lng' => 78.5463],
      'Quthbullapur'       => ['lat' => 17.5029, 'lng' => 78.4657],
      'Hafeezpet'          => ['lat' => 17.4883, 'lng' => 78.3378],
      'Suraram'            => ['lat' => 17.5255, 'lng' => 78.4452],
      'Borabanda'          => ['lat' => 17.4563, 'lng' => 78.4061],
      'Yousufguda'         => ['lat' => 17.4402, 'lng' => 78.4312],
      'Kavadiguda'         => ['lat' => 17.4194, 'lng' => 78.4921],
      'Moti Nagar'         => ['lat' => 17.4526, 'lng' => 78.4168],
      'Osman Nagar'        => ['lat' => 17.4611, 'lng' => 78.2933],
      'Manikonda'          => ['lat' => 17.4024, 'lng' => 78.3791],
      'Sun City'           => ['lat' => 17.3486, 'lng' => 78.3978],
      'Gudimalkapur'       => ['lat' => 17.3824, 'lng' => 78.4411],
      'Nanakramguda'       => ['lat' => 17.4239, 'lng' => 78.3478],
      'Hyderabad Central'  => ['lat' => 17.3850, 'lng' => 78.4867],
      'Koramangala'        => ['lat' => 12.9352, 'lng' => 77.6245],
      'Indiranagar'        => ['lat' => 12.9784, 'lng' => 77.6408],
      'HSR Layout'         => ['lat' => 12.9121, 'lng' => 77.6446],
      'Whitefield'         => ['lat' => 12.9698, 'lng' => 77.7500],
      'Bellandur'          => ['lat' => 12.9260, 'lng' => 77.6762],
      'Electronic City'    => ['lat' => 12.8452, 'lng' => 77.6602],
      'Jayanagar'          => ['lat' => 12.9308, 'lng' => 77.5838],
      'JP Nagar'           => ['lat' => 12.9063, 'lng' => 77.5857],
      'Marathahalli'       => ['lat' => 12.9591, 'lng' => 77.6974],
      'Hebbal'             => ['lat' => 13.0358, 'lng' => 77.5970],
      'Bengaluru Central'  => ['lat' => 12.9716, 'lng' => 77.5946],
      'Bandra'             => ['lat' => 19.0596, 'lng' => 72.8295],
      'Andheri'            => ['lat' => 19.1136, 'lng' => 72.8697],
      'Juhu'               => ['lat' => 19.1075, 'lng' => 72.8263],
      'Powai'              => ['lat' => 19.1176, 'lng' => 72.9060],
      'Worli'              => ['lat' => 19.0178, 'lng' => 72.8178],
      'Malad'              => ['lat' => 19.1874, 'lng' => 72.8484],
      'Thane'              => ['lat' => 19.2183, 'lng' => 72.9781],
      'Colaba'             => ['lat' => 18.9067, 'lng' => 72.8147],
      'Mumbai Central'     => ['lat' => 18.9690, 'lng' => 72.8205]
    ];

    $cityCenters = [
      'Hyderabad'    => ['lat' => 17.3850, 'lng' => 78.4867, 'zoom' => 11],
      'Secunderabad' => ['lat' => 17.4399, 'lng' => 78.4983, 'zoom' => 12],
      'Bengaluru'    => ['lat' => 12.9716, 'lng' => 77.5946, 'zoom' => 11],
      'Mumbai'       => ['lat' => 19.0760, 'lng' => 72.8777, 'zoom' => 11],
      'Chennai'      => ['lat' => 13.0827, 'lng' => 80.2707, 'zoom' => 11],
      'Delhi'        => ['lat' => 28.6139, 'lng' => 77.2090, 'zoom' => 11],
      'Pune'         => ['lat' => 18.5204, 'lng' => 73.8567, 'zoom' => 11],
      'All Cities'   => ['lat' => 17.3850, 'lng' => 78.4867, 'zoom' => 6],
    ];

    // 5. Aggregate by City and Area
    $zoneAgg = [];
    foreach ($demandRows as $dr) {
      $c = ucwords(strtolower(trim($dr['city'])));
      $a = ucwords(strtolower(trim($dr['area'])));
      if ($a === 'General' || empty($a)) continue;
      if (strcasecmp($a, $c) === 0) {
        $a = "{$c} Central";
      }
      $key = "{$c}|{$a}";
      if (!isset($zoneAgg[$key])) {
        $zoneAgg[$key] = [
          'city' => $c, 
          'area' => $a, 
          'demand' => 0, 
          'supply' => 0, 
          'services' => [],
          'lat' => (float)$dr['avg_lat'],
          'lng' => (float)$dr['avg_lng']
        ];
      }
      $zoneAgg[$key]['demand'] += (int)$dr['total_demand'];
      if (!empty($dr['avg_lat']) && empty($zoneAgg[$key]['lat'])) {
        $zoneAgg[$key]['lat'] = (float)$dr['avg_lat'];
        $zoneAgg[$key]['lng'] = (float)$dr['avg_lng'];
      }
      $st = $dr['service_type'];
      $zoneAgg[$key]['services'][$st] = ($zoneAgg[$key]['services'][$st] ?? 0) + (int)$dr['total_demand'];
    }

    foreach ($supplyRows as $sr) {
      $c = ucwords(strtolower(trim($sr['city'])));
      $a = ucwords(strtolower(trim($sr['area'])));
      if ($a === 'General' || empty($a)) continue;
      if (strcasecmp($a, $c) === 0) {
        $a = "{$c} Central";
      }
      $key = "{$c}|{$a}";
      if (!isset($zoneAgg[$key])) {
        $zoneAgg[$key] = [
          'city' => $c, 
          'area' => $a, 
          'demand' => 0, 
          'supply' => 0, 
          'services' => [],
          'lat' => (float)$sr['avg_lat'],
          'lng' => (float)$sr['avg_lng']
        ];
      }
      $zoneAgg[$key]['supply'] += (int)$sr['active_sp_count'];
      if (!empty($sr['avg_lat']) && empty($zoneAgg[$key]['lat'])) {
        $zoneAgg[$key]['lat'] = (float)$sr['avg_lat'];
        $zoneAgg[$key]['lng'] = (float)$sr['avg_lng'];
      }
    }

    // Filter by selected city
    $cleanCityLower = strtolower($city);
    $cityZones = [];
    $activeCityCenter = $cityCenters[$city] ?? $cityCenters['Hyderabad'];

    foreach ($zoneAgg as $z) {
      if ($cleanCityLower !== 'all cities' && strtolower($z['city']) !== $cleanCityLower) {
        // Twin city handling: include Secunderabad under Hyderabad region
        if ($cleanCityLower === 'hyderabad' && strtolower($z['city']) === 'secunderabad') {
          // allow
        } else {
          continue;
        }
      }

      $dem = (int)$z['demand'];
      $sup = (int)$z['supply'];
      $gap = $sup - $dem;
      $cov = $dem > 0 ? (int)round(($sup / $dem) * 100) : ($sup > 0 ? 100 : 0);
      if ($cov < 75 || $gap <= -2) {
        $status = 'shortage';
      } elseif ($cov < 95 || $gap < 0) {
        $status = 'tight';
      } else {
        $status = 'balanced';
      }

      $topService = 'walkers';
      if (!empty($z['services'])) {
        arsort($z['services']);
        $topKey = array_key_first($z['services']);
        $topService = $topKey === 'walking' ? 'walkers' : ($topKey === 'grooming' ? 'groomers' : ($topKey === 'training' ? 'trainers' : 'sitters'));
      }

      // Real GPS Resolution
      $lat = (!empty($z['lat']) && $z['lat'] != 0) ? $z['lat'] : null;
      $lng = (!empty($z['lng']) && $z['lng'] != 0) ? $z['lng'] : null;

      if (!$lat || !$lng) {
        if (isset($knownCoords[$z['area']])) {
          $lat = $knownCoords[$z['area']]['lat'];
          $lng = $knownCoords[$z['area']]['lng'];
        } else {
          // Deterministic fallback spread around city center
          $seed = crc32($z['area']);
          $angle = ($seed % 360) * (M_PI / 180);
          $distKm = (($seed % 100) / 100) * 12 + 2; // 2 to 14 km
          $lat = $activeCityCenter['lat'] + ($distKm / 111) * cos($angle);
          $lng = $activeCityCenter['lng'] + ($distKm / (111 * cos($activeCityCenter['lat'] * (M_PI / 180)))) * sin($angle);
        }
      }

      $cityZones[] = [
        'zone'        => $z['area'],
        'city'        => $z['city'],
        'demand'      => $dem,
        'supply'      => $sup,
        'coverage'    => $cov,
        'gap'         => $gap,
        'status'      => $status,
        'top_service' => $topService,
        'lat'         => round((float)$lat, 5),
        'lng'         => round((float)$lng, 5)
      ];
    }

    // Sort: highest demand first, then lowest gap
    usort($cityZones, function($a, $b) {
      if ($a['demand'] === $b['demand']) {
        return $a['gap'] <=> $b['gap'];
      }
      return $b['demand'] <=> $a['demand'];
    });

    // Extract shortage areas
    $shortages = array_values(array_filter($cityZones, fn($item) => $item['gap'] < 0));
    usort($shortages, fn($a, $b) => $a['gap'] <=> $b['gap']);

    // Dynamic AI Recommendation
    $topShortage = $shortages[0] ?? null;
    if ($topShortage) {
      $headline = "Need " . abs($topShortage['gap']) . " more {$topShortage['top_service']} in {$topShortage['zone']}";
      $subtext  = "Based on live demand ({$topShortage['demand']} booking requests) vs active supply ({$topShortage['supply']} active SPs) resulting in an immediate local deficit.";
    } else {
      $headline = "Optimal Service Coverage Across {$city}";
      $subtext  = "Active provider capacity balances with booking request volume across major zones.";
    }

    // Take top 15 zones as real map markers
    $mapMarkers = array_slice($cityZones, 0, 15);

    return [
      'selected_city'    => $city,
      'selected_service' => $service,
      'date_days'        => $days,
      'available_cities' => $availableCities,
      'subtitle'         => "{$city} - Active SPs vs Demand",
      'city_center'      => $activeCityCenter,
      'recommendation'   => [
        'headline' => $headline,
        'subtext'  => $subtext
      ],
      'markers'          => $mapMarkers,
      'nodes'            => $mapMarkers,
      'shortage_areas'   => array_slice($shortages, 0, 5),
      'zones'            => $cityZones
    ];
  }

  /**
   * AJAX endpoint: Retrieve live dynamic City Demand vs Supply dataset.
   */
  public function getCityDataAjax()
  {
    $city    = $this->request->getGet('city') ?: 'Hyderabad';
    $service = $this->request->getGet('service') ?: 'All Services';
    $daysParam = $this->request->getGet('days');
    $days    = ($daysParam !== null && $daysParam !== '') ? (int)$daysParam : 30;

    $data = $this->getCityDemandSupplyData($city, $service, $days);
    return $this->response->setJSON([
      'status' => 'success',
      'data'   => $data
    ]);
  }

  /**
>>>>>>> origin/main
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
<<<<<<< HEAD
    $allSps = $db->table('service_providers')
      ->select('id, area, city, state, service_address, service_latitude, service_longitude, status')
=======
    $allSps = $db->table('service_providers sp')
      ->select('sp.id, sp.area, sp.city, sp.state, sp.service_address, sp.service_latitude, sp.service_longitude, sp.status, GROUP_CONCAT(DISTINCT LOWER(s.name) SEPARATOR ",") as services_offered')
      ->join('sp_services ss', 'ss.provider_id = sp.id', 'left')
      ->join('services s', 's.id = ss.service_id', 'left')
      ->groupBy('sp.id')
>>>>>>> origin/main
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
<<<<<<< HEAD
=======
            'services'      => [],
>>>>>>> origin/main
            'lats'          => [],
            'lngs'          => [],
            'default_lat'   => $lat,
            'default_lng'   => $lng
          ];
        }

        $areaGroups[$key]['sp_count']++;
<<<<<<< HEAD
=======
        if (!empty($sp['services_offered'])) {
          $areaGroups[$key]['services'][] = strtolower($sp['services_offered']);
        }
>>>>>>> origin/main
        if (isset($genuineMap[$spId])) {
          $areaGroups[$key]['genuine_count']++;
        }
        $areaGroups[$key]['lats'][] = $lat;
        $areaGroups[$key]['lngs'][] = $lng;
      }
    }

<<<<<<< HEAD
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
=======
    // ─── 3. Count real bookings per area from quotations table ───
    $thirtyDaysAgo = date('Y-m-d', strtotime('-30 days'));
    $demandCounts = [];

    $bookingRows = $db->query("
      SELECT DISTINCT q.booking_id, q.provider_id
      FROM quotations q
      WHERE q.status IN ('Accepted', 'Completed')
        AND q.created_at >= ?
    ", [$thirtyDaysAgo])->getResultArray();

    foreach ($bookingRows as $row) {
      $pId = (int)$row['provider_id'];
      if (isset($providerLocations[$pId])) {
        $loc = $providerLocations[$pId];
        $key = $loc['state'] . '|' . $loc['city'] . '|' . $loc['area'] . '|' . $loc['pincode'];
        $demandCounts[$key] = ($demandCounts[$key] ?? 0) + 1;
>>>>>>> origin/main
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

<<<<<<< HEAD
      $stateCityData[$state][$city]['areas'][] = [
        'name'    => $areaName,
        'pincode' => $pincode,
        'demand'  => $demand,
        'supply'  => $spCount,
        'genuine' => $genuine,
        'level'   => $level,
        'lat'     => $avgLat,
        'lng'     => $avgLng
=======
      $servicesStr = !empty($group['services']) ? implode(',', array_unique(explode(',', implode(',', $group['services'])))) : 'walking,grooming,training,sitting,boarding,vet';

      $stateCityData[$state][$city]['areas'][] = [
        'name'     => $areaName,
        'pincode'  => $pincode,
        'demand'   => $demand,
        'supply'   => $spCount,
        'genuine'  => $genuine,
        'level'    => $level,
        'services' => $servicesStr,
        'lat'      => $avgLat,
        'lng'      => $avgLng
>>>>>>> origin/main
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
<<<<<<< HEAD
}
=======

  /**
   * AJAX endpoint: AI SP Operations & Demand-Supply Advisor Insights
   */
  public function aiInsights()
  {
    $liveDemand  = $this->demandSupplyModel->getLiveDemandStats();
    $liveSupply  = $this->demandSupplyModel->getLiveSupplyStats();
    $gridSummary = $this->demandSupplyModel->getDemandSupplySummary();
    $alerts      = $this->getAdminAlerts();

    $activeSp    = (int)($liveSupply['total_active_sp'] ?? 0);
    $genuineSp   = (int)($liveSupply['genuine_sp_count'] ?? 0);
    $totalDemand = (int)($liveDemand['total_daily_jobs'] ?? 0);
    $callPending = (int)($liveSupply['waiting_call_verification'] ?? 0);

    // Count critical alerts
    $criticalCount = 0;
    foreach ($alerts as $city => $cats) {
        foreach ($cats as $cat => $alertItems) {
            foreach ($alertItems as $al) {
                if (($al['level'] ?? '') === 'critical') {
                    $criticalCount++;
                }
            }
        }
    }

    $prompt = "You are Petsfolio AI Service Provider Operations Advisor. Provide a crisp, concise executive operational summary (maximum 150-200 words). Do NOT output long essays or redundant headers. Focus strictly on:\n" .
        "1. Active SP capacity ({$activeSp} active, {$genuineSp} genuine score>=70, {$callPending} paid providers waiting for call verification).\n" .
        "2. Key operational bottleneck and top 3 priority action items for platform operations.";

    $aiSummary = "Platform SP Operations Overview: Genuine SP capacity is {$genuineSp}/{$activeSp} active providers. " .
        "Daily active booking demand is {$totalDemand} jobs across active cities. " .
        "Primary Bottleneck: {$callPending} paid providers are currently waiting for call verification to complete onboarding and unlock active supply capacity.";

    $improvements = [
        "Accelerate call verifications for {$callPending} paid pending providers to immediately boost active supply density.",
        "Deploy targeted SP recruitment in critical supply gap areas where supply-to-demand ratio is below 0.8.",
        "Optimize auto-matching scoring to prioritize high-rated genuine providers with low active job loads."
    ];

    $supplyGapWarnings = [];
    if ($criticalCount > 0) {
        $supplyGapWarnings[] = "Detected {$criticalCount} critical zone capacity drops where genuine SP count is below threshold.";
    } else {
        $supplyGapWarnings[] = "All key coverage zones currently meet baseline SP capacity requirements.";
    }

    try {
        $analyst = new GeminiAnalyst();
        $aiRes = $analyst->ask($prompt);
        if (!empty($aiRes['reply']) && strpos($aiRes['reply'], 'Error:') === false) {
            $aiSummary = $aiRes['reply'];
        }
    } catch (\Throwable $e) {
        // Fallback to static summary
    }

    return $this->response->setJSON([
        'status' => 'success',
        'data' => [
            'total_active_sp'          => $activeSp,
            'genuine_sp_count'         => $genuineSp,
            'total_daily_jobs'         => $totalDemand,
            'waiting_verification'     => $callPending,
            'critical_alerts_count'    => $criticalCount,
            'ai_executive_summary'     => $aiSummary,
            'operational_improvements' => $improvements,
            'supply_gap_warnings'      => $supplyGapWarnings
        ]
    ]);
  }

  /**
   * AJAX endpoint: Interactive Ask AI SP Management Advisor
   */
  public function askAi()
  {
    $question = trim((string)($this->request->getPost('question') ?: $this->request->getPost('message') ?: $this->request->getGet('question')));
    if (empty($question)) {
      return $this->response->setJSON(['status' => false, 'message' => 'Please ask an operational question about service provider management.']);
    }

    $liveSupply = $this->demandSupplyModel->getLiveSupplyStats();
    $liveDemand = $this->demandSupplyModel->getLiveDemandStats();

    $contextPrompt = "User Question regarding SP Management: \"{$question}\"\n\n" .
        "Live System Operational Context: Active SPs = " . ($liveSupply['total_active_sp'] ?? 0) . 
        ", Genuine High-Rank SPs = " . ($liveSupply['genuine_sp_count'] ?? 0) . 
        ", Daily Demand = " . ($liveDemand['total_daily_jobs'] ?? 0) . 
        ", Call Verification Pending = " . ($liveSupply['waiting_call_verification'] ?? 0) . ".\n" .
        "Answer the operational question thoroughly with actionable insights.";

    try {
      $analyst = new GeminiAnalyst();
      $res = $analyst->ask($contextPrompt);
      return $this->response->setJSON([
          'status'  => true,
          'reply'   => $res['reply'] ?? 'Unable to process request.',
          'metrics' => $res['metrics'] ?? [],
          'chart'   => $res['chart'] ?? null
      ]);
    } catch (\Throwable $e) {
      return $this->response->setJSON([
          'status' => false,
          'reply'  => 'Error querying AI SP Advisor: ' . $e->getMessage()
      ]);
    }
  }
}

>>>>>>> origin/main
