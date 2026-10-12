<?php

<<<<<<< HEAD
/**
 * Common Helper Functions for Petsfolio Admin
 */

if (!function_exists('ageCalculator')) {
  /**
   * Calculates age string from Date of Birth (DOB).
   *
   * @param string|null $dob Date of birth (Y-m-d)
   * @return string e.g. "25 yrs" or "2 mos" or "N/A"
   */
  function ageCalculator($dob)
  {
    if (empty($dob) || $dob == '0000-00-00' || $dob == '1970-01-01') {
      return 'N/A';
    }

    try {
      $birthDate = new DateTime($dob);
      $today     = new DateTime('today');
      $diff      = $birthDate->diff($today);

      if ($diff->y > 0) {
        return $diff->y . ' yrs';
      } elseif ($diff->m > 0) {
        return $diff->m . ' mos';
      } else {
        return $diff->d . ' days';
      }
    } catch (\Throwable $e) {
      return 'N/A';
    }
  }
}

if (!function_exists('haversineGreatCircleDistance')) {
  /**
   * Calculates the great-circle distance between two points.
   */
  function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371)
  {
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo   = deg2rad($latitudeTo);
    $lonTo   = deg2rad($longitudeTo);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
      cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
    return $angle * $earthRadius;
=======
if (!function_exists('ageCalculator')) {
  function ageCalculator($dob)
  {
    $dob      = new \DateTime($dob);
    $today    = new \DateTime();
    $interval = $today->diff($dob);

    if ($interval->y >= 1) {
      // If age is more than or equal to 1 year
      $age = $interval->y . ' Year' . ($interval->y > 1 ? 's' : '');
    } elseif ($interval->m >= 1) {
      // If age is less than 1 year but more than or equal to 1 month
      $age = ($interval->y * 12 + $interval->m) . ' Month' . ($interval->m > 1 ? 's' : '');
    } else {
      // If age is less than a month
      $age = $interval->days . ' Day' . ($interval->days > 1 ? 's' : '');
    }

    return $age;
  }
}

if (!function_exists('distanceCalculator')) {
  /**
   * Calculate distance between two points in kilometers using the Haversine formula.
   *
   * @param float $lat1 Latitude of point 1
   * @param float $lon1 Longitude of point 1
   * @param float $lat2 Latitude of point 2
   * @param float $lon2 Longitude of point 2
   * @return float Distance in kilometers, rounded to 4 decimal places
   */
  function distanceCalculator($lat1, $lon1, $lat2, $lon2)
  {


    $earthRadius = 6371; // Earth's radius in kilometers

    // Convert degrees to radians
    $lat1 = deg2rad($lat1);
    $lon1 = deg2rad($lon1);
    $lat2 = deg2rad($lat2);
    $lon2 = deg2rad($lon2);

    // Differences in coordinates
    $dLat = $lat2 - $lat1;
    $dLon = $lon2 - $lon1;

    // Haversine formula
    $a        = sin($dLat / 2) * sin($dLat / 2) +
      cos($lat1) * cos($lat2) *
      sin($dLon / 2) * sin($dLon / 2);
    $c        = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $distance = $earthRadius * $c;

    return round($distance, 4);
>>>>>>> origin/main
  }
}

if (!function_exists('deg2rad')) {
<<<<<<< HEAD
=======
  /**
   * Convert degrees to radians.
   *
   * @param float $deg Degrees
   * @return float Radians
   */
>>>>>>> origin/main
  function deg2rad($deg)
  {
    return $deg * (pi() / 180);
  }
}

<<<<<<< HEAD
if (!function_exists('hasPermission')) {
  /**
   * Check if current user has permission (supports Super Admin bypass, Direct User Overrides, & Role Permissions).
   *
   * @param string|int $permissionName Permission ID or Name
   * @return bool
   */
  function hasPermission($permissionName)
  {
    $session = \Config\Services::session();
    $user    = $session->get('user');

=======
if (!function_exists('getUserRoleSlug')) {
  /**
   * Get the role slug for the specified user or currently logged-in user.
   */
  function getUserRoleSlug($user = null): ?string
  {
    if (!$user) {
      $user = session()->get('user');
    }
    if (!$user) {
      return null;
    }

    $roleSlug = is_object($user) ? ($user->role_slug ?? null) : ($user['role_slug'] ?? null);
    if (!empty($roleSlug)) {
      return $roleSlug;
    }

    $roleId = (int)(is_object($user) ? ($user->role_id ?? 0) : ($user['role_id'] ?? 0));
    if ($roleId > 0) {
      $db = \Config\Database::connect();
      $role = $db->table('roles')->select('slug')->where('id', $roleId)->get()->getRowArray();
      $slug = $role['slug'] ?? null;
      if (is_object($user)) {
        $user->role_slug = $slug;
      }
      return $slug;
    }

    return null;
  }
}

if (!function_exists('isSuperAdmin')) {
  /**
   * Determine if the user is a Super Admin.
   */
  function isSuperAdmin($user = null): bool
  {
    if (!$user) {
      $user = session()->get('user');
    }
>>>>>>> origin/main
    if (!$user) {
      return false;
    }

<<<<<<< HEAD
    // Super Admin (Role 1) always has full access
    if (isset($user->role_id) && (int)$user->role_id === 1) {
      return true;
    }

    $db       = \Config\Database::connect();
    $userId   = (int)($user->id ?? 0);
    $userRole = (int)($user->role_id ?? 0);

    // 1. Direct User-level Permission Overrides
    if ($userId > 0 && $db->tableExists('user_permissions')) {
      $userPerm = $db->table('user_permissions')
        ->join('permissions', 'permissions.id = user_permissions.permission_id')
        ->where('user_permissions.user_id', $userId)
        ->groupStart()
          ->where('permissions.id', $permissionName)
          ->orWhere('permissions.name', $permissionName)
        ->groupEnd()
        ->countAllResults();

      if ($userPerm > 0) {
        return true;
      }
    }

    // 2. Role-wise Permissions
    if ($userRole > 0) {
      return $db->table('role_permissions')
        ->join('permissions', 'permissions.id = role_permissions.permission_id')
        ->where('role_permissions.role_id', $userRole)
        ->groupStart()
          ->where('permissions.id', $permissionName)
          ->orWhere('permissions.name', $permissionName)
        ->groupEnd()
        ->countAllResults() > 0;
=======
    $roleId = (int)(is_object($user) ? ($user->role_id ?? 0) : ($user['role_id'] ?? 0));
    if ($roleId === 1) {
      return true;
    }

    $roleSlug = getUserRoleSlug($user);
    return $roleSlug === 'super_admin';
  }
}

if (!function_exists('hasRole')) {
  /**
   * Check if a user has a specific role by slug, array of slugs, or ID.
   *
   * @param string|int|array $slugOrId
   * @param object|array|null $user
   * @return bool
   */
  function hasRole($slugOrId, $user = null): bool
  {
    if (!$user) {
      $user = session()->get('user');
    }
    if (!$user) {
      return false;
    }

    $userRoleId = (int)(is_object($user) ? ($user->role_id ?? 0) : ($user['role_id'] ?? 0));
    $userRoleSlug = getUserRoleSlug($user);

    if (is_array($slugOrId)) {
      foreach ($slugOrId as $target) {
        if (is_numeric($target) && (int)$target === $userRoleId) {
          return true;
        }
        if (is_string($target) && $userRoleSlug !== null && strtolower($target) === strtolower($userRoleSlug)) {
          return true;
        }
      }
      return false;
    }

    if (is_numeric($slugOrId)) {
      return (int)$slugOrId === $userRoleId;
    }

    if (is_string($slugOrId)) {
      return $userRoleSlug !== null && strtolower($slugOrId) === strtolower($userRoleSlug);
>>>>>>> origin/main
    }

    return false;
  }
}

<<<<<<< HEAD
=======
if (!function_exists('hasPermission')) {
  function hasPermission($slugOrId)
  {
    $session = session();
    $user    = $session->get('user');
    if (!$user) {
      return false;
    }

    // Super Admin always gets full access
    if (isSuperAdmin($user)) {
      return true;
    }

    $userRole = (int)(is_object($user) ? ($user->role_id ?? 0) : ($user['role_id'] ?? 0));

    // Load cached permissions for performance (1 DB query per login session instead of 30+ per page load)
    $cachedPerms = $session->get('_user_permissions');
    if ($cachedPerms === null || !is_array($cachedPerms)) {
      $userId = (int)(is_object($user) ? ($user->id ?? 0) : ($user['id'] ?? 0));
      $cachedPerms = _loadUserPermissions($userId, $userRole);
      $session->set('_user_permissions', $cachedPerms);
    }

    if (is_numeric($slugOrId)) {
      return in_array((int)$slugOrId, $cachedPerms['ids'] ?? []);
    }

    // Direct slug match
    return in_array($slugOrId, $cachedPerms['slugs'] ?? []);
  }
}

if (!function_exists('hasAnyProviderPermission')) {
  function hasAnyProviderPermission()
  {
    $session = session();
    $user    = $session->get('user');
    if (!$user) {
      return false;
    }

    if (isSuperAdmin($user)) {
      return true;
    }

    $userRole = (int)(is_object($user) ? ($user->role_id ?? 0) : ($user['role_id'] ?? 0));

    $cachedPerms = $session->get('_user_permissions');
    if ($cachedPerms === null || !is_array($cachedPerms)) {
      $userId = (int)(is_object($user) ? ($user->id ?? 0) : ($user['id'] ?? 0));
      $cachedPerms = _loadUserPermissions($userId, $userRole);
      $session->set('_user_permissions', $cachedPerms);
    }

    $providerSubSlugs = [
      'fee_pending.view',
      'wait_for_call.view',
      'wait_for_call_24.view',
      'manager_approval.view',
      'kyc_pending.view',
      'verified.view',
      'old_non_verified.view',
      'old_verified.view',
      'suspended.view',
      'service_providers.view',
      'providers.view',
      'providers.manage',
      'providers.verify',
      'service_providers.edit',
      'service_providers.delete'
    ];
    foreach ($providerSubSlugs as $subSlug) {
      if (in_array($subSlug, $cachedPerms['slugs'] ?? [])) {
        return true;
      }
    }
    return false;
  }
}

if (!function_exists('_loadUserPermissions')) {
  function _loadUserPermissions(int $userId, int $roleId): array
  {
    $db = \Config\Database::connect();

    // Fetch role permissions
    $rolePerms = $db->table('role_permissions rp')
      ->select('p.id, p.slug')
      ->join('permissions p', 'p.id = rp.permission_id')
      ->where('rp.role_id', $roleId)
      ->get()
      ->getResultArray();

    // Fetch direct user permission overrides
    $userPerms = [];
    if ($db->tableExists('user_permissions')) {
      $userPerms = $db->table('user_permissions up')
        ->select('p.id, p.slug')
        ->join('permissions p', 'p.id = up.permission_id')
        ->where('up.user_id', $userId)
        ->get()
        ->getResultArray();
    }

    $merged = array_merge($rolePerms, $userPerms);
    $ids    = array_unique(array_column($merged, 'id'));
    $slugs  = array_unique(array_filter(array_column($merged, 'slug')));

    return [
      'ids'   => array_values(array_map('intval', $ids)),
      'slugs' => array_values($slugs)
    ];
  }
}

if (!function_exists('clearPermissionCache')) {
  function clearPermissionCache()
  {
    session()->remove('_user_permissions');
  }
}

if (!function_exists('getDefaultPermittedRoute')) {
  function getDefaultPermittedRoute($user = null)
  {
    if (!$user) {
      $user = session()->get('user');
    }
    if (!$user) {
      return base_url('login');
    }

    // Super Admin or user with dashboard.view
    if (isSuperAdmin($user) || hasPermission('dashboard.view')) {
      return base_url('dashboard');
    }

    // Priority route mapping based on assigned permission slugs
    $priorityRoutes = [
      'fee_pending.view'         => 'providers?status=pending_registration',
      'wait_for_call.view'       => 'providers?status=pending_verification',
      'wait_for_call_24.view'    => 'providers?status=pending_verification&overdue=1',
      'manager_approval.view'    => 'providers?status=approval',
      'kyc_pending.view'         => 'providers?status=pending_kyc',
      'verified.view'            => 'providers?status=active',
      'old_non_verified.view'    => 'providers?status=non_verified',
      'old_verified.view'        => 'providers?status=non_verified',
      'suspended.view'           => 'providers?status=inactive',
      'providers.view'           => 'providers?status=pending_registration',
      'service_providers.view'   => 'providers?status=pending_registration',
      'insurance.view'           => 'enquiries',
      'new_enquiries.view'       => 'enquiries',
      'active_insurance.view'    => 'active',
      'insurance_claims.view'    => 'claims',
      'expiring_policies.view'   => 'expiring',
      'clients.view'             => 'clients?status=active',
      'bookings.view_requests'   => 'bookingRequests',
      'bookings.walking'         => 'bookings/in_progress',
      'bookings.grooming'        => 'bookings/grooming',
      'bookings.training'        => 'bookings/training',
      'bookings.boarding'        => 'bookings/boarding',
      'bookings.sitting'         => 'bookings/sitting',
      'bookings.premium'         => 'bookings/premium_requests',
      'bookings.missed_walks'    => 'bookings/missed_walks',
      'bookings.all'             => 'bookings',
      'support.client'           => 'support',
      'support.provider'         => 'sp-support',
      'employees.view'           => 'employees',
      'finance.sp_earnings'      => 'sp-earnings',
      'finance.client_spendings' => 'client-spendings',
      'finance.sp_payments'      => 'sp-payments',
      'finance.company_earnings' => 'company-earnings',
      'call_analysis.view'       => 'call-analysis',
      'community.view'           => 'community-channels',
      'loyalty.view'             => 'loyalty-programs',
      'notifications.view'       => 'notifications',
      'notifications.campaigns'  => 'notification-manager',
      'permissions.view'         => 'permissions',
      'walker.leaderboard'       => 'walker-leaderboard',
      'walker.sp_management'     => 'sp-management',
    ];

    foreach ($priorityRoutes as $permSlug => $routePath) {
      if (hasPermission($permSlug)) {
        return base_url($routePath);
      }
    }

    if (hasRole(['verification_executive', 10], $user)) {
      return base_url('providers?status=pending_registration');
    }

    return base_url('unauthorized');
  }
}

>>>>>>> origin/main
if (!function_exists('calculateEndDate')) {
  function calculateEndDate($startDateStr, $totalDays, $serviceDays)
  {
    $startDate = new DateTime($startDateStr);
    $endDate   = clone $startDate;
<<<<<<< HEAD
    $daysAdded = 1;
=======
    $daysAdded = 1; // Start with 1 because the start date is included.
>>>>>>> origin/main

    while ($daysAdded < $totalDays) {
      $endDate->modify('+1 day');

      if ($serviceDays === 'weekdays') {
<<<<<<< HEAD
=======
        // Skip Sunday (N=7)
>>>>>>> origin/main
        if ($endDate->format('N') < 7) {
          $daysAdded++;
        }
      } else {
<<<<<<< HEAD
=======
        // Include all days
>>>>>>> origin/main
        $daysAdded++;
      }
    }

    return $endDate->format('Y-m-d');
  }
}
<<<<<<< HEAD
=======

if (!function_exists('phonepeResponseDescription')) {
  function phonepeResponseDescription($code)
  {
    $map = [
      'TXN_AUTO_FAILED'             => 'Payment couldn’t be completed due to issues related to customer or their bank.',
      'TXN_FAILED'                  => 'Payment failed due to temporary technical issue.',
      'PENDING'                     => 'Payment is being processed.',
      'REQUEST_CANCEL_BY_REQUESTER' => 'You cancelled the payment request.',
      'CANCELLED_BY_USER'           => 'Customer didn’t complete the authentication process in time.',
      'ISSUER_BANK_UNAVAILABLE'     => 'Technical issue at the customer’s bank.',
      'REQUEST_TIME_OUT'            => 'Payment request timed-out.',
      'INSUFFICIENT_FUNDS'          => 'Payment failed due to insufficient balance in customer’s account.',
      'Z9'                          => 'Payment failed due to insufficient balance in customer’s account.',
      'S96'                         => 'echnical issue at the customer’s bank.',
      '500'                         => 'Internal Server Error'
    ];

    return $map[$code] ?? 'Unknown payment status';
  }
}

if (!function_exists('getGoogleDistance')) {

  function getGoogleDistance($lat1, $lon1, $lat2, $lon2, $mode = "driving")
  {
    $apiKey = getenv('gMapKey');


    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?" .
      "origins={$lat1},{$lon1}&destinations={$lat2},{$lon2}" .
      "&mode={$mode}&units=metric&key={$apiKey}";

    $response = file_get_contents($url);
    $data = json_decode($response, true);
    if (!empty($data['rows'][0]['elements'][0]['distance']['value'])) {
      // distance in meters
      $meters = $data['rows'][0]['elements'][0]['distance']['value'];
      return round($meters / 1000, 1) . " Km";
    }

    return null;
  }
}

if (!function_exists('getGoogleDirections')) {

  function getGoogleDirections($lat1, $lon1, $lat2, $lon2, $mode = "driving")
  {
    $apiKey = getenv('gMapKey');


    $url = "https://maps.googleapis.com/maps/api/directions/json?" .
      "origin={$lat1},{$lon1}&destination={$lat2},{$lon2}" .
      "&mode={$mode}&key={$apiKey}";

    $response = file_get_contents($url);
    $data = json_decode($response, true);
    if (!empty($data['routes'][0]['legs'][0]['distance'])) {
      $distance = $data['routes'][0]['legs'][0]['distance'];
      $meters   = (int) $distance['value']; // raw meters
      $text     = $distance['text'];        // "1 m", "850 m", "1.2 km"

      // ✅ If Google says 0 meters, but text exists → fallback to text
      if ($meters === 0) {
        return $text;
      }

      // if less than 1000m → return meters
      if ($meters < 1000) {
        return $meters . " m";
      }

      // else return in km
      return round($meters / 1000, 1) . " Km";
    }

    return null;
  }
}

if (!function_exists('utcToIst')) {
  /**
   * Convert UTC datetime string to IST (Asia/Kolkata) formatted string.
   *
   * @param string|null $time UTC datetime string (e.g. '2026-10-06 08:56:54')
   * @param string $format Target format (default 'd M Y, h:i A')
   * @return string
   */
  function utcToIst($time, string $format = 'd M Y, h:i A'): string
  {
    if (empty($time) || $time === '0000-00-00 00:00:00' || $time === '--') {
      return '--';
    }

    try {
      $dt = new \DateTime($time, new \DateTimeZone('UTC'));
      $dt->setTimezone(new \DateTimeZone('Asia/Kolkata'));
      return $dt->format($format);
    } catch (\Throwable $e) {
      return !empty($time) ? date($format, strtotime($time)) : '--';
    }
  }
}
>>>>>>> origin/main
