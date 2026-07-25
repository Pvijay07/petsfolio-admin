<?php
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
  }
}

if (!function_exists('deg2rad')) {
  /**
   * Convert degrees to radians.
   *
   * @param float $deg Degrees
   * @return float Radians
   */
  function deg2rad($deg)
  {
    return $deg * (pi() / 180);
  }
}

if (!function_exists('hasPermission')) {
  function hasPermission($permissionName)
  {
    $db       = \Config\Database::connect();
    $userRole = session()->get('user')->role_id;
    return $db->table('role_permissions')
      ->join('permissions', 'permissions.id = role_permissions.permission_id')
      ->where('role_permissions.role_id', $userRole)
      ->where('permissions.id', $permissionName)
      ->countAllResults() > 0;
  }
}

if (!function_exists('calculateEndDate')) {
  function calculateEndDate($startDateStr, $totalDays, $serviceDays)
  {
    $startDate = new DateTime($startDateStr);
    $endDate   = clone $startDate;
    $daysAdded = 1; // Start with 1 because the start date is included.

    while ($daysAdded < $totalDays) {
      $endDate->modify('+1 day');

      if ($serviceDays === 'weekdays') {
        // Skip Sunday (N=7)
        if ($endDate->format('N') < 7) {
          $daysAdded++;
        }
      } else {
        // Include all days
        $daysAdded++;
      }
    }

    return $endDate->format('Y-m-d');
  }
}

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
