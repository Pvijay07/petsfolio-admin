<?php

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
  }
}

if (!function_exists('deg2rad')) {
  function deg2rad($deg)
  {
    return $deg * (pi() / 180);
  }
}

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

    if (!$user) {
      return false;
    }

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
    }

    return false;
  }
}

if (!function_exists('calculateEndDate')) {
  function calculateEndDate($startDateStr, $totalDays, $serviceDays)
  {
    $startDate = new DateTime($startDateStr);
    $endDate   = clone $startDate;
    $daysAdded = 1;

    while ($daysAdded < $totalDays) {
      $endDate->modify('+1 day');

      if ($serviceDays === 'weekdays') {
        if ($endDate->format('N') < 7) {
          $daysAdded++;
        }
      } else {
        $daysAdded++;
      }
    }

    return $endDate->format('Y-m-d');
  }
}
