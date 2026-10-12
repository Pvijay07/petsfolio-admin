<?php echo $this->extend('admin/includes/header'); ?>
<?php echo $this->section('content'); ?>

<?php
  // Helper for human-readable relative time
  if (!function_exists('time_ago_sv')) {
    function time_ago_sv($datetime) {
      if (empty($datetime)) return 'Unknown';
      $time = strtotime($datetime);
      if (!$time) return esc($datetime);
      $diff = time() - $time;
      if ($diff < 60) return 'Just now';
      if ($diff < 3600) return floor($diff / 60) . 'm ago';
      if ($diff < 86400) return floor($diff / 3600) . 'h ago';
      if ($diff < 604800) return floor($diff / 86400) . 'd ago';
      return date('M j, Y', $time);
    }
  }

  // Helper for Stage / Transition Information
  if (!function_exists('get_stage_info_sv')) {
    function get_stage_info_sv($type) {
      $t = strtoupper(trim($type ?? ''));
      if ($t === 'ENTER' || $t === 'GEOFENCE_ENTER') {
        return [
          'label' => 'Arrived',
          'badge' => 'bg-danger text-white',
          'icon' => 'bi-box-arrow-in-right',
          'desc' => 'Entered 100m customer zone'
        ];
      } elseif ($t === 'DWELL' || $t === 'GEOFENCE_DWELL') {
        return [
          'label' => 'Lingered (Stayed)',
          'badge' => 'bg-warning text-dark',
          'icon' => 'bi-hourglass-split',
          'desc' => 'Stayed in zone for 5+ min'
        ];
      } elseif ($t === 'EXIT' || $t === 'GEOFENCE_EXIT') {
        return [
          'label' => 'Departed',
          'badge' => 'bg-secondary text-white',
          'icon' => 'bi-box-arrow-right',
          'desc' => 'Left customer zone'
        ];
      } elseif ($t === 'PROXIMITY_PING' || $t === 'PING') {
        return [
          'label' => 'Proximity Detected',
          'badge' => 'bg-info text-white',
          'icon' => 'bi-broadcast-pin',
          'desc' => 'Detected within 100m zone'
        ];
      }
      return [
        'label' => $t ?: 'Detected',
        'badge' => 'bg-dark text-white',
        'icon' => 'bi-geo-alt',
        'desc' => 'Location telemetry ping'
      ];
    }
  }

  // Helper for Risk Information
  if (!function_exists('get_risk_info_sv')) {
    function get_risk_info_sv($bStatus) {
      $status = ucfirst(strtolower(trim($bStatus ?? '')));
      if ($status === 'Completed') {
        return [
          'type' => 'COMPLETED',
          'title' => 'Completed Booking Visit',
          'short' => 'Completed Client',
          'badgeClass' => 'bg-warning-subtle text-dark border border-warning-subtle',
          'headerBadge' => 'bg-warning text-dark',
          'alertClass' => 'alert-warning border-warning-subtle bg-warning-subtle text-dark',
          'icon' => 'bi-clock-history',
          'riskLevel' => 'Moderate Risk',
          'reason' => 'Provider was detected near this customer after their booking was completed. Lingering without an active booking indicates potential offline direct work.'
        ];
      } elseif ($status === 'Cancelled') {
        return [
          'type' => 'CANCELLED',
          'title' => 'Cancelled Booking Visit',
          'short' => 'Cancelled Client',
          'badgeClass' => 'bg-danger-subtle text-danger border border-danger-subtle',
          'headerBadge' => 'bg-danger text-white',
          'alertClass' => 'alert-danger border-danger-subtle bg-danger-subtle text-danger',
          'icon' => 'bi-x-circle-fill',
          'riskLevel' => 'High Risk',
          'reason' => 'Provider was detected near this customer after the booking was cancelled. Visiting after cancellation strongly indicates off-platform bypass.'
        ];
      } else {
        return [
          'type' => 'NO_BOOKING',
          'title' => 'No Booking on Record',
          'short' => 'No Active Booking',
          'badgeClass' => 'bg-danger-subtle text-danger border border-danger-subtle',
          'headerBadge' => 'bg-danger text-white',
          'alertClass' => 'alert-danger border-danger-subtle bg-danger-subtle text-danger',
          'icon' => 'bi-shield-slash-fill',
          'riskLevel' => 'Critical Risk',
          'reason' => 'Provider was detected near this customer address with no registered booking on Petsfolio. High risk of off-platform direct engagement.'
        ];
      }
    }
  }

  // Pre-calculate statistics
  $totalVisits = !empty($visits) ? count($visits) : 0;
  $uniqueProviders = [];
  $uniqueCustomers = [];
  $completedCount = 0;
  $cancelledCount = 0;
  $noBookingCount = 0;
  $enterCount = 0;
  $dwellCount = 0;
  $exitCount = 0;
  $proximityCount = 0;

  if (!empty($visits)) {
    foreach ($visits as $v) {
      if (!empty($v->provider_id)) {
        $uniqueProviders[$v->provider_id] = $v->provider_name ?? ('SP #' . $v->provider_id);
      }
      if (!empty($v->customer_id)) {
        $uniqueCustomers[$v->customer_id] = $v->customer_name ?? ('Customer #' . $v->customer_id);
      }
      $bStatus = !empty($v->booking_details->booking_status) ? ucfirst(strtolower(trim($v->booking_details->booking_status))) : '';
      if ($bStatus === 'Completed') {
        $completedCount++;
      } elseif ($bStatus === 'Cancelled') {
        $cancelledCount++;
      } else {
        $noBookingCount++;
      }

      $tType = strtoupper(trim($v->transition_type ?? ''));
      if ($tType === 'ENTER' || $tType === 'GEOFENCE_ENTER') $enterCount++;
      elseif ($tType === 'DWELL' || $tType === 'GEOFENCE_DWELL') $dwellCount++;
      elseif ($tType === 'EXIT' || $tType === 'GEOFENCE_EXIT') $exitCount++;
      elseif ($tType === 'PROXIMITY_PING' || $tType === 'PING') $proximityCount++;
    }
  }
?>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?php echo $this->include('admin/nav_bar') ?>
  </div>

  <div class="container-fluid content-inner pb-4 px-3 px-md-4" id="page_layout">
    
    <!-- Hero Header Section -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" 
         style="background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);">
      <div class="card-body p-4 p-md-5 text-white position-relative">
        <div class="position-relative z-2">
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
              <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-10 border border-white border-opacity-15 mb-3">
                <span class="pulse-indicator bg-danger"></span>
                <span class="text-uppercase fw-semibold font-11 tracking-wider text-light">Automated Geofence Detection</span>
              </div>
              <h2 class="fw-bold text-white mb-2 d-flex align-items-center gap-2">
                <i class="bi bi-shield-exclamation text-danger fs-3"></i>
                Unauthorized Provider Visits
              </h2>
              <p class="text-white-75 mb-0 max-w-750 font-14">
                Monitors service providers detected within <strong>100 meters</strong> of past customer addresses for 5+ minutes with <strong>Completed</strong> or <strong>Cancelled</strong> bookings (no active booking). Detects offline direct work and prevents platform bypass.
              </p>
            </div>
            
            <div class="d-flex flex-wrap gap-2">
              <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3 py-2 d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#geofenceInfoModal">
                <i class="bi bi-question-circle"></i>
                <span>How It Works</span>
              </button>
              <button type="button" onclick="exportTableToCSV('unauthorized-visits-report.csv')" class="btn btn-light btn-sm rounded-pill px-3 py-2 d-flex align-items-center gap-2 shadow-sm text-dark fw-semibold">
                <i class="bi bi-download text-primary"></i>
                <span>Export CSV</span>
              </button>
              <button type="button" onclick="window.location.reload();" class="btn btn-primary btn-sm rounded-pill px-3 py-2 d-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-arrow-clockwise"></i>
                <span>Refresh</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Decorative Watermark Icon -->
        <i class="bi bi-geo-alt-fill position-absolute end-0 bottom-0 opacity-10 text-white" 
           style="font-size: 14rem; transform: translate(15%, 25%) rotate(-10deg); pointer-events: none;"></i>
      </div>
    </div>

    <!-- How It Works — Visual Stepper Flow -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
      <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-lightbulb text-warning fs-5"></i> How does unauthorized visit detection work?
          </h6>
          <span class="badge bg-light text-muted border font-11 px-2 py-1">4-Step Automated Trigger</span>
        </div>
        <div class="row g-3 text-center">
          <div class="col-12 col-sm-6 col-lg-3">
            <div class="step-card p-3 rounded-3 h-100 bg-light border border-light-subtle position-relative">
              <span class="step-number badge bg-primary text-white position-absolute top-0 start-0 m-2 rounded-circle font-11">1</span>
              <div class="rounded-circle bg-primary bg-opacity-10 text-primary mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <i class="bi bi-phone-vibrate fs-4"></i>
              </div>
              <div class="fw-bold text-dark font-13 mb-1">Provider App Telemetry</div>
              <div class="text-muted font-12">Provider's mobile app sends periodic GPS location telemetry in the background.</div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-lg-3">
            <div class="step-card p-3 rounded-3 h-100 bg-light border border-light-subtle position-relative">
              <span class="step-number badge bg-warning text-dark position-absolute top-0 start-0 m-2 rounded-circle font-11">2</span>
              <div class="rounded-circle bg-warning bg-opacity-10 text-warning mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <i class="bi bi-geo-alt fs-4"></i>
              </div>
              <div class="fw-bold text-dark font-13 mb-1">Within 100m of Customer</div>
              <div class="text-muted font-12">System matches coordinates within 100 meters of a past customer's saved address.</div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-lg-3">
            <div class="step-card p-3 rounded-3 h-100 bg-light border border-light-subtle position-relative">
              <span class="step-number badge bg-danger text-white position-absolute top-0 start-0 m-2 rounded-circle font-11">3</span>
              <div class="rounded-circle bg-danger bg-opacity-10 text-danger mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <i class="bi bi-clock-history fs-4"></i>
              </div>
              <div class="fw-bold text-dark font-13 mb-1">Stayed 5+ Minutes</div>
              <div class="text-muted font-12">Provider remained or lingered in the geofenced area for 5 minutes or longer.</div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-lg-3">
            <div class="step-card p-3 rounded-3 h-100 bg-light border border-light-subtle position-relative">
              <span class="step-number badge bg-dark text-white position-absolute top-0 start-0 m-2 rounded-circle font-11">4</span>
              <div class="rounded-circle bg-dark bg-opacity-10 text-dark mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <i class="bi bi-shield-x fs-4"></i>
              </div>
              <div class="fw-bold text-dark font-13 mb-1">No Active Booking</div>
              <div class="text-muted font-12">Past booking is Completed or Cancelled. Alert is generated for admin investigation.</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Summary KPI Cards (Clickable quick-filters) -->
    <div class="row g-3 g-md-4 mb-4">
      <!-- Total Flagged Visits -->
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 border-danger transition-hover cursor-pointer" onclick="setQuickFilter('risk', 'ALL')">
          <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="text-muted fw-semibold small text-uppercase tracking-wider">Total Flagged Visits</span>
              <div class="stat-icon bg-danger-subtle text-danger rounded-3 p-2 d-flex align-items-center justify-content-center">
                <i class="bi bi-flag-fill fs-4"></i>
              </div>
            </div>
            <h3 class="fw-bold text-dark mb-1"><?= number_format($totalVisits) ?></h3>
            <div class="d-flex align-items-center text-muted small">
              <span class="badge bg-danger bg-opacity-10 text-danger me-2 font-11">All Incidents</span>
              <span>Across entire platform</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Completed Bookings Visits -->
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 border-warning transition-hover cursor-pointer" onclick="setQuickFilter('risk', 'COMPLETED')">
          <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="text-muted fw-semibold small text-uppercase tracking-wider">Completed Booking Visits</span>
              <div class="stat-icon bg-warning-subtle text-warning rounded-3 p-2 d-flex align-items-center justify-content-center">
                <i class="bi bi-check2-circle fs-4"></i>
              </div>
            </div>
            <h3 class="fw-bold text-warning mb-1"><?= number_format($completedCount) ?></h3>
            <div class="d-flex align-items-center text-muted small">
              <span class="badge bg-warning bg-opacity-20 text-dark me-2 font-11">Completed</span>
              <span>Visited after service ended</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Cancelled Bookings Visits -->
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 border-danger transition-hover cursor-pointer" onclick="setQuickFilter('risk', 'CANCELLED')">
          <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="text-muted fw-semibold small text-uppercase tracking-wider">Cancelled Booking Visits</span>
              <div class="stat-icon bg-danger-subtle text-danger rounded-3 p-2 d-flex align-items-center justify-content-center">
                <i class="bi bi-x-circle fs-4"></i>
              </div>
            </div>
            <h3 class="fw-bold text-danger mb-1"><?= number_format($cancelledCount) ?></h3>
            <div class="d-flex align-items-center text-muted small">
              <span class="badge bg-danger bg-opacity-10 text-danger me-2 font-11">Cancelled</span>
              <span>Visited after cancellation</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Providers Involved -->
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 border-info transition-hover">
          <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="text-muted fw-semibold small text-uppercase tracking-wider">Providers Involved</span>
              <div class="stat-icon bg-info-subtle text-info rounded-3 p-2 d-flex align-items-center justify-content-center">
                <i class="bi bi-people fs-4"></i>
              </div>
            </div>
            <h3 class="fw-bold text-dark mb-1"><?= number_format(count($uniqueProviders)) ?></h3>
            <div class="d-flex align-items-center text-muted small">
              <span class="badge bg-info bg-opacity-10 text-info me-2 font-11">Providers</span>
              <span>Across <?= number_format(count($uniqueCustomers)) ?> customer addresses</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Data Table & Filter Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
      
      <!-- Filter and Search Toolbar -->
      <div class="card-header bg-white py-3 px-4 border-bottom">
        <div class="row g-3 align-items-center justify-content-between">
          
          <!-- Live Search -->
          <div class="col-12 col-lg-4">
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0 text-muted ps-3">
                <i class="bi bi-search"></i>
              </span>
              <input type="text" id="searchInput" class="form-control bg-light border-start-0 ps-1" 
                     placeholder="Search provider, customer, phone, booking ID..." 
                     onkeyup="filterVisitsTable()">
              <button class="btn btn-light border border-start-0 text-muted" type="button" onclick="clearSearch()" title="Clear Search">
                <i class="bi bi-x-lg font-12"></i>
              </button>
            </div>
          </div>

          <!-- Dropdown Filters -->
          <div class="col-12 col-lg-8">
            <div class="d-flex flex-wrap align-items-center justify-content-lg-end gap-2">
              
              <!-- Filter by Booking Status -->
              <div class="filter-select-wrapper">
                <select id="filterRisk" class="form-select form-select-sm rounded-3 py-2" onchange="filterVisitsTable()">
                  <option value="ALL">All Risk Types (<?= $totalVisits ?>)</option>
                  <option value="COMPLETED">⚠️ Completed Client (<?= $completedCount ?>)</option>
                  <option value="CANCELLED">❌ Cancelled Client (<?= $cancelledCount ?>)</option>
                  <option value="NO_BOOKING">🚨 No Booking Found (<?= $noBookingCount ?>)</option>
                </select>
              </div>

              <!-- Filter by Visit Stage -->
              <div class="filter-select-wrapper">
                <select id="filterTransition" class="form-select form-select-sm rounded-3 py-2" onchange="filterVisitsTable()">
                  <option value="ALL">All Stages</option>
                  <option value="PROXIMITY_PING">Nearby Ping (<?= $proximityCount ?>)</option>
                  <option value="ENTER">Arrived (<?= $enterCount ?>)</option>
                  <option value="DWELL">Stayed / Lingered (<?= $dwellCount ?>)</option>
                  <option value="EXIT">Departed (<?= $exitCount ?>)</option>
                </select>
              </div>

              <!-- Filter by Date Period -->
              <div class="filter-select-wrapper">
                <select id="filterDate" class="form-select form-select-sm rounded-3 py-2" onchange="filterVisitsTable()">
                  <option value="ALL">All Dates</option>
                  <option value="TODAY">Today</option>
                  <option value="7_DAYS">Last 7 Days</option>
                  <option value="30_DAYS">Last 30 Days</option>
                </select>
              </div>

              <!-- Reset Filters Button -->
              <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 py-2 px-3 d-flex align-items-center gap-1" onclick="resetAllFilters()">
                <i class="bi bi-arrow-counterclockwise"></i>
                <span>Reset</span>
              </button>

            </div>
          </div>

        </div>

        <!-- Table Count & Active Filter Indicator -->
        <div class="d-flex align-items-center justify-content-between pt-3 mt-2 border-top font-13 text-muted">
          <div>
            Showing <span id="visibleCount" class="fw-bold text-dark"><?= $totalVisits ?></span> of <span class="fw-bold text-dark"><?= $totalVisits ?></span> flagged visits
          </div>
          <div id="filterIndicator" class="badge bg-light text-primary border" style="display: none;">
            <i class="bi bi-funnel-fill me-1"></i>Filters active
          </div>
        </div>
      </div>

      <!-- Table Body -->
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table align-middle mb-0 table-hover" id="suspiciousVisitsTable">
            <thead class="table-light text-muted font-12 text-uppercase">
              <tr>
                <th class="ps-4 py-3" style="min-width: 170px;">Risk Level</th>
                <th class="py-3" style="min-width: 220px;">Provider</th>
                <th class="py-3" style="min-width: 220px;">Customer Visited</th>
                <th class="py-3" style="min-width: 190px;">Booking Status</th>
                <th class="py-3" style="min-width: 180px;">Visit &amp; Duration</th>
                <th class="py-3" style="min-width: 180px;">Location</th>
                <th class="pe-4 py-3 text-end" style="width: 140px;">Action</th>
              </tr>
            </thead>
            <tbody class="font-14">
              <?php if (!empty($visits)): ?>
                <?php foreach ($visits as $idx => $visit): ?>
                  <?php
                    $bStatus = !empty($visit->booking_details->booking_status) ? ucfirst(strtolower(trim($visit->booking_details->booking_status))) : '';
                    $riskInfo = get_risk_info_sv($bStatus);
                    $riskCategory = $riskInfo['type'];

                    $tType = strtoupper(trim($visit->transition_type ?? 'UNKNOWN'));
                    $stageInfo = get_stage_info_sv($tType);

                    $visitDate = !empty($visit->visit_timestamp) ? date('Y-m-d', strtotime($visit->visit_timestamp)) : date('Y-m-d', strtotime($visit->created_at ?? 'now'));
                    $formattedTime = !empty($visit->visit_timestamp) ? date('d M Y, h:i A', strtotime($visit->visit_timestamp)) : date('d M Y, h:i A', strtotime($visit->created_at ?? 'now'));
                    $relativeTime = time_ago_sv($visit->visit_timestamp ?? $visit->created_at);
                    
                    // Coordinates & Accuracy
                    $lat = $visit->lat ?? null;
                    $lng = $visit->lng ?? null;
                    $hasCoords = (!empty($lat) && !empty($lng));
                    $acc = $visit->accuracy ?? null;
                    $dwellMin = $visit->dwell_time ? round((float)$visit->dwell_time / 60, 1) : null;
                    $distanceMeters = !empty($visit->distance) ? $visit->distance : null;

                    // Provider Initials
                    $spName = $visit->provider_name ?? ('Provider #' . $visit->provider_id);
                    $spInitials = '';
                    $spWords = explode(' ', trim($spName));
                    foreach (array_slice($spWords, 0, 2) as $w) {
                      if (!empty($w)) $spInitials .= strtoupper($w[0]);
                    }
                    if (empty($spInitials)) $spInitials = 'SP';

                    // Customer Name & Initials
                    $custName = $visit->customer_name ?? ('Customer #' . $visit->customer_id);
                    $custInitials = '';
                    $custWords = explode(' ', trim($custName));
                    foreach (array_slice($custWords, 0, 2) as $w) {
                      if (!empty($w)) $custInitials .= strtoupper($w[0]);
                    }
                    if (empty($custInitials)) $custInitials = 'CU';

                    // Raw JSON string
                    $rawJson = $visit->raw_telemetry ?? '{}';
                    if (is_array($rawJson) || is_object($rawJson)) {
                      $rawJson = json_encode($rawJson, JSON_PRETTY_PRINT);
                    } else {
                      $decoded = json_decode($rawJson);
                      $rawJson = $decoded ? json_encode($decoded, JSON_PRETTY_PRINT) : $rawJson;
                    }

                    $modalId = 'modal-' . md5($visit->id . '_' . $idx);
                  ?>
                  <tr class="visit-row" 
                      data-risk="<?= esc($riskCategory) ?>" 
                      data-transition="<?= esc($tType) ?>" 
                      data-date="<?= esc($visitDate) ?>"
                      data-provider="<?= esc($visit->provider_id) ?>"
                      data-search-text="<?= esc(strtolower(($visit->id ?? '') . ' ' . $spName . ' ' . ($visit->provider_phone ?? '') . ' ' . ($visit->provider_id ?? '') . ' ' . $custName . ' ' . ($visit->customer_phone ?? '') . ' ' . ($visit->customer_id ?? '') . ' ' . ($visit->associated_booking_id ?? '') . ' ' . $bStatus . ' ' . ($visit->provider_city ?? '') . ' ' . ($visit->customer_city ?? '') . ' ' . $tType . ' ' . $stageInfo['label'])) ?>">
                    
                    <!-- 1. Risk Level -->
                    <td class="ps-4">
                      <div class="d-flex flex-column gap-1">
                        <span class="badge <?= $riskInfo['badgeClass'] ?> px-2 py-1 font-11 fw-semibold d-inline-flex align-items-center gap-1 w-fit">
                          <i class="bi <?= $riskInfo['icon'] ?>"></i> <?= esc($riskInfo['short']) ?>
                        </span>
                        <span class="font-11 <?= ($riskCategory === 'COMPLETED') ? 'text-muted' : 'text-danger fw-semibold' ?>">
                          <?= esc($riskInfo['title']) ?>
                        </span>
                        
                        <div class="d-flex align-items-center gap-1 mt-1">
                          <code class="font-11 text-muted text-truncate" style="max-width: 85px;" title="<?= esc($visit->id) ?>">
                            #<?= esc(substr($visit->id, 0, 8)) ?>
                          </code>
                          <button type="button" class="btn btn-link btn-sm p-0 text-muted hover-primary" 
                                  onclick="copyText('<?= esc($visit->id) ?>', this)" title="Copy Visit ID">
                            <i class="bi bi-clipboard font-11"></i>
                          </button>
                        </div>
                      </div>
                    </td>

                    <!-- 2. Service Provider -->
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <div class="avatar-circle rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center flex-shrink-0" 
                             style="width: 38px; height: 38px; font-size: 13px;">
                          <?= esc($spInitials) ?>
                        </div>
                        <div class="min-w-0">
                          <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="fw-bold text-dark text-truncate d-block" title="<?= esc($spName) ?>">
                              <?= esc($spName) ?>
                            </span>
                            <span class="badge bg-light text-secondary border font-11 py-0 px-1">
                              ID: <?= esc($visit->provider_id) ?>
                            </span>
                          </div>
                          
                          <div class="d-flex align-items-center gap-2 mt-1 font-12 text-muted flex-wrap">
                            <?php if (!empty($visit->provider_phone)): ?>
                              <a href="tel:<?= esc($visit->provider_phone) ?>" class="text-decoration-none text-muted hover-primary d-inline-flex align-items-center gap-1">
                                <i class="bi bi-telephone font-11 text-primary"></i> <?= esc($visit->provider_phone) ?>
                              </a>
                              <a href="https://wa.me/<?= esc(preg_replace('/[^0-9]/', '', $visit->provider_phone)) ?>" target="_blank" class="text-success hover-opacity" title="Chat on WhatsApp">
                                <i class="bi bi-whatsapp font-12"></i>
                              </a>
                            <?php else: ?>
                              <span class="text-muted font-11">No phone</span>
                            <?php endif; ?>

                            <?php if (!empty($visit->provider_city)): ?>
                              <span class="badge bg-light text-muted border font-10">
                                <i class="bi bi-geo-alt font-9 me-1"></i><?= esc($visit->provider_city) ?>
                              </span>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>
                    </td>

                    <!-- 3. Customer Visited -->
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <div class="avatar-circle rounded-circle bg-info bg-opacity-10 text-info fw-bold d-flex align-items-center justify-content-center flex-shrink-0" 
                             style="width: 38px; height: 38px; font-size: 13px;">
                          <?= esc($custInitials) ?>
                        </div>
                        <div class="min-w-0">
                          <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="fw-bold text-dark text-truncate" title="<?= esc($custName) ?>">
                              <?= esc($custName) ?>
                            </span>
                            <span class="badge bg-light text-secondary border font-11 py-0 px-1">
                              ID: <?= esc($visit->customer_id) ?>
                            </span>
                          </div>

                          <div class="d-flex align-items-center gap-2 mt-1 font-12 text-muted flex-wrap">
                            <?php if (!empty($visit->customer_phone)): ?>
                              <a href="tel:<?= esc($visit->customer_phone) ?>" class="text-decoration-none text-muted hover-primary d-inline-flex align-items-center gap-1">
                                <i class="bi bi-telephone font-11 text-info"></i> <?= esc($visit->customer_phone) ?>
                              </a>
                              <a href="https://wa.me/<?= esc(preg_replace('/[^0-9]/', '', $visit->customer_phone)) ?>" target="_blank" class="text-success hover-opacity" title="Chat on WhatsApp">
                                <i class="bi bi-whatsapp font-12"></i>
                              </a>
                            <?php else: ?>
                              <span class="text-muted font-11">No phone</span>
                            <?php endif; ?>

                            <?php if (!empty($visit->customer_city)): ?>
                              <span class="badge bg-light text-muted border font-10">
                                <i class="bi bi-house-door font-9 me-1"></i><?= esc($visit->customer_city) ?>
                              </span>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>
                    </td>

                    <!-- 4. Booking Status -->
                    <td>
                      <?php if (!empty($visit->associated_booking_id)): ?>
                        <div>
                          <div class="d-flex align-items-center gap-1">
                            <?php if ($bStatus === 'Completed'): ?>
                              <span class="badge bg-success-subtle text-success border border-success-subtle font-12 px-2 py-1 fw-bold">
                                <i class="bi bi-check2-circle me-1"></i>Booking #<?= esc($visit->associated_booking_id) ?>
                              </span>
                            <?php elseif ($bStatus === 'Cancelled'): ?>
                              <span class="badge bg-danger-subtle text-danger border border-danger-subtle font-12 px-2 py-1 fw-bold">
                                <i class="bi bi-x-circle me-1"></i>Booking #<?= esc($visit->associated_booking_id) ?>
                              </span>
                            <?php else: ?>
                              <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-12 px-2 py-1 fw-bold">
                                <i class="bi bi-bookmark me-1"></i>Booking #<?= esc($visit->associated_booking_id) ?>
                              </span>
                            <?php endif; ?>
                          </div>
                          <?php if (!empty($visit->booking_details)): ?>
                            <div class="mt-1 font-11 text-muted">
                              <span class="text-capitalize fw-semibold text-dark"><?= esc($visit->booking_details->service_type ?? 'Service') ?></span>
                              <?php if (!empty($bStatus)): ?>
                                &bull; <span class="badge <?= $bStatus === 'Completed' ? 'bg-success text-white' : ($bStatus === 'Cancelled' ? 'bg-danger text-white' : 'bg-light text-secondary border') ?> font-10"><?= esc($bStatus) ?></span>
                              <?php endif; ?>
                            </div>
                          <?php else: ?>
                            <small class="text-muted d-block mt-1 font-11">Past booking match</small>
                          <?php endif; ?>
                        </div>
                      <?php else: ?>
                        <div>
                          <span class="badge bg-danger text-white font-11 px-2 py-1 d-inline-flex align-items-center gap-1">
                            <i class="bi bi-x-circle"></i> No Booking Found
                          </span>
                          <small class="text-danger fw-semibold d-block mt-1 font-11">Possible off-platform work</small>
                        </div>
                      <?php endif; ?>
                    </td>

                    <!-- 5. Visit & Duration -->
                    <td>
                      <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                          <span class="badge <?= $stageInfo['badge'] ?> px-2 py-1 font-11 d-inline-flex align-items-center gap-1">
                            <i class="bi <?= $stageInfo['icon'] ?>"></i> <?= esc($stageInfo['label']) ?>
                          </span>

                          <?php if (!empty($dwellMin)): ?>
                            <span class="badge bg-light text-dark border font-11" title="How long the provider stayed">
                              <i class="bi bi-stopwatch me-1 text-danger"></i><?= $dwellMin ?> min
                            </span>
                          <?php else: ?>
                            <span class="badge bg-light text-dark border font-11" title="Stayed 5+ minutes">
                              <i class="bi bi-stopwatch me-1 text-warning"></i>5+ min
                            </span>
                          <?php endif; ?>
                        </div>

                        <div class="mt-1 font-12 text-dark fw-semibold">
                          <?= esc($formattedTime) ?>
                        </div>
                        <small class="text-muted font-11">
                          <i class="bi bi-clock me-1"></i><?= esc($relativeTime) ?>
                        </small>
                      </div>
                    </td>

                    <!-- 6. Location -->
                    <td>
                      <?php if ($hasCoords): ?>
                        <div>
                          <div class="d-flex align-items-center gap-1 font-12 font-monospace text-dark">
                            <i class="bi bi-geo-alt-fill text-danger font-11"></i>
                            <span><?= number_format((float)$lat, 4) ?>, <?= number_format((float)$lng, 4) ?></span>
                          </div>
                          
                          <div class="d-flex align-items-center gap-2 mt-1 font-11 flex-wrap">
                            <span class="badge bg-light text-muted border font-10">
                              <i class="bi bi-pin-map me-1"></i>Within 100m
                            </span>
                            <a href="https://www.google.com/maps?q=<?= esc($lat) ?>,<?= esc($lng) ?>" target="_blank" 
                               class="btn btn-xs btn-outline-primary py-0 px-2 rounded-pill font-11 d-inline-flex align-items-center gap-1"
                               title="See location on Google Maps">
                              <i class="bi bi-box-arrow-up-right font-9"></i> View Map
                            </a>
                          </div>
                        </div>
                      <?php else: ?>
                        <span class="text-muted font-12 fst-italic">
                          <i class="bi bi-geo-slash me-1"></i>Location not available
                        </span>
                      <?php endif; ?>
                    </td>

                    <!-- 7. Action / Details Button -->
                    <td class="pe-4 text-end">
                      <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1 font-12 d-inline-flex align-items-center gap-1 shadow-sm" 
                              data-bs-toggle="modal" data-bs-target="#<?= esc($modalId) ?>">
                        <i class="bi bi-eye"></i>
                        <span>Inspect Visit</span>
                      </button>

                      <!-- REDESIGNED HIGH-CLARITY INSPECTION MODAL -->
                      <div class="modal fade text-start" id="<?= esc($modalId) ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                          <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                            
                            <!-- Modal Header -->
                            <div class="modal-header bg-dark text-white p-4 position-relative">
                              <div class="w-100 pe-4">
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                  <span class="badge <?= $riskInfo['headerBadge'] ?> px-2 py-1 font-11 fw-bold">
                                    <i class="bi <?= $riskInfo['icon'] ?> me-1"></i><?= esc($riskInfo['title']) ?>
                                  </span>
                                  <span class="badge <?= $stageInfo['badge'] ?> px-2 py-1 font-11">
                                    <i class="bi <?= $stageInfo['icon'] ?> me-1"></i><?= esc($stageInfo['label']) ?>
                                  </span>
                                  <span class="badge bg-secondary bg-opacity-75 text-light font-11">
                                    <?= esc($riskInfo['riskLevel']) ?>
                                  </span>
                                </div>
                                <h4 class="modal-title text-white fw-bold mb-1 d-flex align-items-center gap-2">
                                  <span>Visit Investigation Details</span>
                                </h4>
                                <div class="d-flex align-items-center gap-2 text-white-50 font-12">
                                  <span class="font-monospace">Event ID: <?= esc($visit->id) ?></span>
                                  <button type="button" class="btn btn-xs btn-outline-light py-0 px-2 rounded-pill font-11" onclick="copyText('<?= esc($visit->id) ?>', this)">
                                    <i class="bi bi-clipboard font-10 me-1"></i>Copy
                                  </button>
                                </div>
                              </div>
                              <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-4" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <!-- Modal Body -->
                            <div class="modal-body p-4 bg-light">
                              
                              <!-- 1. What Happened? Narrative Summary Box -->
                              <div class="alert <?= $riskInfo['alertClass'] ?> rounded-4 mb-4 p-4 shadow-sm">
                                <div class="d-flex align-items-start gap-3">
                                  <div class="rounded-circle p-2 bg-white shadow-sm flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                    <i class="bi <?= $riskInfo['icon'] ?> fs-4 <?= ($riskCategory === 'COMPLETED') ? 'text-warning' : 'text-danger' ?>"></i>
                                  </div>
                                  <div class="w-100">
                                    <div class="fw-bold text-uppercase font-12 tracking-wider mb-1 opacity-75">
                                      Detection Narrative &amp; Finding
                                    </div>
                                    <h6 class="fw-bold mb-2">
                                      <?php if ($bStatus === 'Completed'): ?>
                                        Service Provider lingered near a past customer with completed booking
                                      <?php elseif ($bStatus === 'Cancelled'): ?>
                                        Service Provider visited customer after booking was cancelled
                                      <?php else: ?>
                                        Service Provider visited customer with no booking on record
                                      <?php endif; ?>
                                    </h6>
                                    <p class="mb-3 font-13 lh-base">
                                      <strong><?= esc($spName) ?></strong> was detected within <strong>100 meters</strong> of customer <strong><?= esc($custName) ?></strong>'s registered address on <strong><?= esc($formattedTime) ?></strong>.
                                      <?= esc($riskInfo['reason']) ?>
                                    </p>
                                    
                                    <!-- Summary Highlight Pills -->
                                    <div class="d-flex flex-wrap gap-2 pt-2 border-top border-dark border-opacity-10">
                                      <div class="badge bg-white text-dark shadow-sm font-12 px-3 py-2 border">
                                        <i class="bi bi-person text-primary me-1"></i> Provider: <strong><?= esc($spName) ?></strong> (ID: #<?= esc($visit->provider_id) ?>)
                                      </div>
                                      <div class="badge bg-white text-dark shadow-sm font-12 px-3 py-2 border">
                                        <i class="bi bi-house-door text-info me-1"></i> Customer: <strong><?= esc($custName) ?></strong> (ID: #<?= esc($visit->customer_id) ?>)
                                      </div>
                                      <div class="badge bg-white text-dark shadow-sm font-12 px-3 py-2 border">
                                        <i class="bi bi-clock text-warning me-1"></i> Stayed: <strong><?= $dwellMin ? esc($dwellMin) . ' min' : '5+ min' ?></strong>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <!-- 2. Dual Entity Cards (Provider & Customer) -->
                              <div class="row g-3 mb-4">
                                
                                <!-- Provider Card -->
                                <div class="col-12 col-md-6">
                                  <div class="card border-0 shadow-sm rounded-4 h-100 bg-white overflow-hidden">
                                    <div class="card-header bg-primary bg-opacity-10 py-3 px-4 border-0 d-flex align-items-center justify-content-between">
                                      <span class="fw-bold text-primary font-12 text-uppercase tracking-wider">
                                        <i class="bi bi-person-badge me-1"></i> Service Provider
                                      </span>
                                      <span class="badge bg-primary text-white font-11">ID: #<?= esc($visit->provider_id) ?></span>
                                    </div>
                                    <div class="card-body p-4">
                                      <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="avatar-circle rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center flex-shrink-0" 
                                             style="width: 50px; height: 50px; font-size: 18px;">
                                          <?= esc($spInitials) ?>
                                        </div>
                                        <div class="min-w-0">
                                          <h6 class="fw-bold text-dark mb-1 text-truncate"><?= esc($spName) ?></h6>
                                          <span class="badge bg-light text-muted border font-11">Service Partner</span>
                                        </div>
                                      </div>

                                      <div class="font-13 text-muted mb-4 d-flex flex-column gap-2">
                                        <div class="d-flex align-items-center gap-2">
                                          <i class="bi bi-telephone text-primary font-14"></i>
                                          <span class="fw-semibold text-dark"><?= !empty($visit->provider_phone) ? esc($visit->provider_phone) : 'Phone not provided' ?></span>
                                        </div>
                                        <?php if (!empty($visit->provider_email)): ?>
                                          <div class="d-flex align-items-center gap-2 text-truncate">
                                            <i class="bi bi-envelope text-primary font-14"></i>
                                            <span class="text-truncate"><?= esc($visit->provider_email) ?></span>
                                          </div>
                                        <?php endif; ?>
                                        <?php if (!empty($visit->provider_city)): ?>
                                          <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-geo-alt text-primary font-14"></i>
                                            <span>City: <strong><?= esc($visit->provider_city) ?></strong></span>
                                          </div>
                                        <?php endif; ?>
                                      </div>

                                      <?php if (!empty($visit->provider_phone)): ?>
                                        <div class="row g-2 pt-2 border-top">
                                          <div class="col-6">
                                            <a href="tel:<?= esc($visit->provider_phone) ?>" class="btn btn-sm btn-outline-primary rounded-pill w-100 font-12 py-2 d-flex align-items-center justify-content-center gap-1">
                                              <i class="bi bi-telephone-fill font-11"></i> Call
                                            </a>
                                          </div>
                                          <div class="col-6">
                                            <a href="https://wa.me/<?= esc(preg_replace('/[^0-9]/', '', $visit->provider_phone)) ?>" target="_blank" class="btn btn-sm btn-success rounded-pill w-100 font-12 py-2 d-flex align-items-center justify-content-center gap-1">
                                              <i class="bi bi-whatsapp font-12"></i> WhatsApp
                                            </a>
                                          </div>
                                        </div>
                                      <?php endif; ?>
                                    </div>
                                  </div>
                                </div>

                                <!-- Customer Card -->
                                <div class="col-12 col-md-6">
                                  <div class="card border-0 shadow-sm rounded-4 h-100 bg-white overflow-hidden">
                                    <div class="card-header bg-info bg-opacity-10 py-3 px-4 border-0 d-flex align-items-center justify-content-between">
                                      <span class="fw-bold text-info font-12 text-uppercase tracking-wider">
                                        <i class="bi bi-house-door me-1"></i> Customer / Client
                                      </span>
                                      <span class="badge bg-info text-white font-11">ID: #<?= esc($visit->customer_id) ?></span>
                                    </div>
                                    <div class="card-body p-4">
                                      <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="avatar-circle rounded-circle bg-info bg-opacity-10 text-info fw-bold d-flex align-items-center justify-content-center flex-shrink-0" 
                                             style="width: 50px; height: 50px; font-size: 18px;">
                                          <?= esc($custInitials) ?>
                                        </div>
                                        <div class="min-w-0">
                                          <h6 class="fw-bold text-dark mb-1 text-truncate"><?= esc($custName) ?></h6>
                                          <span class="badge bg-light text-muted border font-11">Pet Owner</span>
                                        </div>
                                      </div>

                                      <div class="font-13 text-muted mb-4 d-flex flex-column gap-2">
                                        <div class="d-flex align-items-center gap-2">
                                          <i class="bi bi-telephone text-info font-14"></i>
                                          <span class="fw-semibold text-dark"><?= !empty($visit->customer_phone) ? esc($visit->customer_phone) : 'Phone not provided' ?></span>
                                        </div>
                                        <?php if (!empty($visit->customer_email)): ?>
                                          <div class="d-flex align-items-center gap-2 text-truncate">
                                            <i class="bi bi-envelope text-info font-14"></i>
                                            <span class="text-truncate"><?= esc($visit->customer_email) ?></span>
                                          </div>
                                        <?php endif; ?>
                                        <?php if (!empty($visit->customer_city)): ?>
                                          <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-geo-alt text-info font-14"></i>
                                            <span>City: <strong><?= esc($visit->customer_city) ?></strong></span>
                                          </div>
                                        <?php endif; ?>
                                      </div>

                                      <?php if (!empty($visit->customer_phone)): ?>
                                        <div class="row g-2 pt-2 border-top">
                                          <div class="col-6">
                                            <a href="tel:<?= esc($visit->customer_phone) ?>" class="btn btn-sm btn-outline-info rounded-pill w-100 font-12 py-2 d-flex align-items-center justify-content-center gap-1">
                                              <i class="bi bi-telephone-fill font-11"></i> Call
                                            </a>
                                          </div>
                                          <div class="col-6">
                                            <a href="https://wa.me/<?= esc(preg_replace('/[^0-9]/', '', $visit->customer_phone)) ?>" target="_blank" class="btn btn-sm btn-success rounded-pill w-100 font-12 py-2 d-flex align-items-center justify-content-center gap-1">
                                              <i class="bi bi-whatsapp font-12"></i> WhatsApp
                                            </a>
                                          </div>
                                        </div>
                                      <?php endif; ?>
                                    </div>
                                  </div>
                                </div>

                              </div>

                              <!-- 3. Associated Booking Context Card -->
                              <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
                                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                                  <div class="font-12 fw-bold text-dark text-uppercase tracking-wider">
                                    <i class="bi bi-journal-text me-1 text-primary"></i> Linked Booking Information
                                  </div>
                                  <?php if (!empty($visit->associated_booking_id)): ?>
                                    <span class="badge <?= $bStatus === 'Completed' ? 'bg-success' : ($bStatus === 'Cancelled' ? 'bg-danger' : 'bg-primary') ?> text-white font-11 px-2 py-1">
                                      Booking #<?= esc($visit->associated_booking_id) ?> &bull; <?= esc($bStatus) ?>
                                    </span>
                                  <?php else: ?>
                                    <span class="badge bg-danger text-white font-11 px-2 py-1">
                                      No Booking Found
                                    </span>
                                  <?php endif; ?>
                                </div>
                                <div class="card-body p-4 font-13">
                                  <?php if (!empty($visit->associated_booking_id)): ?>
                                    <div class="row g-3">
                                      <div class="col-6 col-md-3">
                                        <span class="text-muted d-block small mb-1">Booking ID</span>
                                        <strong class="text-dark font-14">#<?= esc($visit->associated_booking_id) ?></strong>
                                      </div>
                                      <div class="col-6 col-md-3">
                                        <span class="text-muted d-block small mb-1">Service Type</span>
                                        <strong class="text-dark text-capitalize font-14">
                                          <?= esc($visit->booking_details->service_type ?? ($visit->booking_details->service_name ?? 'Pet Service')) ?>
                                        </strong>
                                      </div>
                                      <div class="col-6 col-md-3">
                                        <span class="text-muted d-block small mb-1">Booking Status</span>
                                        <strong class="<?= $bStatus === 'Completed' ? 'text-success' : ($bStatus === 'Cancelled' ? 'text-danger' : 'text-primary') ?> font-14">
                                          <?= esc($bStatus) ?>
                                        </strong>
                                      </div>
                                      <div class="col-6 col-md-3">
                                        <span class="text-muted d-block small mb-1">Total Amount</span>
                                        <strong class="text-dark font-14">
                                          <?= !empty($visit->booking_details->total_amount) ? '₹' . number_format((float)$visit->booking_details->total_amount, 2) : 'N/A' ?>
                                        </strong>
                                      </div>
                                    </div>
                                    <?php if (!empty($visit->booking_details->service_start_date)): ?>
                                      <div class="mt-3 pt-3 border-top d-flex align-items-center gap-2 text-muted font-12">
                                        <i class="bi bi-calendar3 text-primary"></i>
                                        <span>Service Dates: <strong><?= esc(date('d M Y', strtotime($visit->booking_details->service_start_date))) ?></strong> <?= !empty($visit->booking_details->service_end_date) ? 'to <strong>' . esc(date('d M Y', strtotime($visit->booking_details->service_end_date))) . '</strong>' : '' ?></span>
                                      </div>
                                    <?php endif; ?>
                                  <?php else: ?>
                                    <div class="p-2 text-danger font-13 d-flex align-items-center gap-2">
                                      <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                                      <div>
                                        <strong>No past or ongoing booking found for this provider-customer pair.</strong>
                                        <div class="text-muted small">This visit was detected purely from proximity telemetry, suggesting an off-platform arrangement.</div>
                                      </div>
                                    </div>
                                  <?php endif; ?>
                                </div>
                              </div>

                              <!-- 4. Visit Details Grid (Organized into Clear Multi-Column Info Tiles - NO OVERLAPPING!) -->
                              <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
                                <div class="card-header bg-white py-3 px-4 border-bottom font-12 fw-bold text-dark text-uppercase tracking-wider">
                                  <i class="bi bi-geo-alt-fill me-1 text-danger"></i> Telemetry &amp; Geofence Detection Details
                                </div>
                                <div class="card-body p-4">
                                  <div class="row g-3 font-13">
                                    
                                    <!-- Tile 1: Stage -->
                                    <div class="col-12 col-sm-6 col-md-4">
                                      <div class="p-3 rounded-3 bg-light border border-light-subtle h-100">
                                        <span class="text-muted d-block small mb-1">
                                          <i class="bi bi-broadcast text-primary me-1"></i> Visit Stage
                                        </span>
                                        <div class="d-flex align-items-center gap-2 mt-1">
                                          <span class="badge <?= $stageInfo['badge'] ?> px-2 py-1 font-12">
                                            <i class="bi <?= $stageInfo['icon'] ?> me-1"></i><?= esc($stageInfo['label']) ?>
                                          </span>
                                        </div>
                                        <small class="text-muted d-block mt-1 font-11"><?= esc($stageInfo['desc']) ?></small>
                                      </div>
                                    </div>

                                    <!-- Tile 2: Duration -->
                                    <div class="col-12 col-sm-6 col-md-4">
                                      <div class="p-3 rounded-3 bg-light border border-light-subtle h-100">
                                        <span class="text-muted d-block small mb-1">
                                          <i class="bi bi-hourglass-split text-warning me-1"></i> Stay Duration
                                        </span>
                                        <strong class="text-dark font-14 d-block mt-1">
                                          <?= $dwellMin ? esc($dwellMin) . ' minutes' : '5+ minutes' ?>
                                        </strong>
                                        <small class="text-muted d-block mt-1 font-11">Lingering detected at location</small>
                                      </div>
                                    </div>

                                    <!-- Tile 3: Distance -->
                                    <div class="col-12 col-sm-6 col-md-4">
                                      <div class="p-3 rounded-3 bg-light border border-light-subtle h-100">
                                        <span class="text-muted d-block small mb-1">
                                          <i class="bi bi-bullseye text-danger me-1"></i> Distance to Address
                                        </span>
                                        <strong class="text-dark font-14 d-block mt-1">
                                          <?= !empty($distanceMeters) ? esc($distanceMeters) . ' meters away' : 'Within 100 meters' ?>
                                        </strong>
                                        <small class="text-muted d-block mt-1 font-11">Geofence boundary radius</small>
                                      </div>
                                    </div>

                                    <!-- Tile 4: When Detected -->
                                    <div class="col-12 col-sm-6 col-md-4">
                                      <div class="p-3 rounded-3 bg-light border border-light-subtle h-100">
                                        <span class="text-muted d-block small mb-1">
                                          <i class="bi bi-calendar-event text-info me-1"></i> Detected Timestamp
                                        </span>
                                        <strong class="text-dark font-14 d-block mt-1"><?= esc($formattedTime) ?></strong>
                                        <small class="text-muted d-block mt-1 font-11"><?= esc($relativeTime) ?></small>
                                      </div>
                                    </div>

                                    <!-- Tile 5: GPS Coordinates -->
                                    <div class="col-12 col-sm-6 col-md-4">
                                      <div class="p-3 rounded-3 bg-light border border-light-subtle h-100">
                                        <span class="text-muted d-block small mb-1">
                                          <i class="bi bi-crosshair text-danger me-1"></i> GPS Coordinates
                                        </span>
                                        <?php if ($hasCoords): ?>
                                          <div class="d-flex align-items-center justify-content-between mt-1">
                                            <strong class="text-dark font-monospace font-13"><?= esc($lat) ?>, <?= esc($lng) ?></strong>
                                            <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill py-0 px-2 font-11" 
                                                    onclick="copyText('<?= esc($lat) ?>,<?= esc($lng) ?>', this)" title="Copy Coords">
                                              <i class="bi bi-clipboard"></i>
                                            </button>
                                          </div>
                                          <small class="text-muted d-block mt-1 font-11"><?= $acc ? '&plusmn;' . esc($acc) . 'm GPS accuracy' : 'Accuracy standard' ?></small>
                                        <?php else: ?>
                                          <strong class="text-muted font-13 d-block mt-1">Coordinates not provided</strong>
                                        <?php endif; ?>
                                      </div>
                                    </div>

                                    <!-- Tile 6: Phone & Device Telemetry -->
                                    <div class="col-12 col-sm-6 col-md-4">
                                      <div class="p-3 rounded-3 bg-light border border-light-subtle h-100">
                                        <span class="text-muted d-block small mb-1">
                                          <i class="bi bi-battery-charging text-success me-1"></i> Phone Battery &amp; Log
                                        </span>
                                        <strong class="text-dark font-14 d-block mt-1">
                                          Battery: <?= !empty($visit->battery) ? esc($visit->battery) . '%' : 'N/A' ?>
                                        </strong>
                                        <small class="text-muted d-block mt-1 font-11">Recorded: <?= esc(substr($visit->created_at ?? 'N/A', 0, 19)) ?></small>
                                      </div>
                                    </div>

                                  </div>

                                  <?php if ($hasCoords): ?>
                                    <!-- Google Maps Direct Preview Bar -->
                                    <div class="mt-4 p-3 rounded-3 bg-danger bg-opacity-10 border border-danger border-opacity-20 d-flex flex-wrap align-items-center justify-content-between gap-2">
                                      <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-geo-alt-fill text-danger fs-4"></i>
                                        <div>
                                          <div class="fw-bold text-dark font-13">View exact spot on Google Maps</div>
                                          <div class="text-muted font-12 font-monospace">Lat: <?= esc($lat) ?> &bull; Lng: <?= esc($lng) ?></div>
                                        </div>
                                      </div>
                                      <a href="https://www.google.com/maps?q=<?= esc($lat) ?>,<?= esc($lng) ?>" target="_blank" 
                                         class="btn btn-sm btn-danger rounded-pill px-3 py-2 font-12 d-flex align-items-center gap-1 shadow-sm">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                        <span>Open in Google Maps</span>
                                      </a>
                                    </div>
                                  <?php endif; ?>

                                </div>
                              </div>

                              <!-- 5. Recommended Admin Action Guide -->
                              <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
                                <div class="card-header bg-white py-3 px-4 border-bottom font-12 fw-bold text-dark text-uppercase tracking-wider">
                                  <i class="bi bi-shield-check me-1 text-success"></i> Recommended Admin Next Steps
                                </div>
                                <div class="card-body p-4 font-13">
                                  <div class="row g-3">
                                    <div class="col-12 col-md-4">
                                      <div class="p-3 rounded-3 bg-light border border-light-subtle h-100">
                                        <div class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                                          <span class="badge bg-primary rounded-circle p-1" style="width: 20px; height: 20px; line-height: 12px;">1</span>
                                          <span>Contact Provider</span>
                                        </div>
                                        <p class="text-muted font-12 mb-2">Ask why they visited the customer's location without an active booking on Petsfolio.</p>
                                        <?php if (!empty($visit->provider_phone)): ?>
                                          <a href="tel:<?= esc($visit->provider_phone) ?>" class="btn btn-xs btn-outline-primary rounded-pill px-2 py-1 font-11">
                                            <i class="bi bi-telephone me-1"></i> Call <?= esc($spName) ?>
                                          </a>
                                        <?php endif; ?>
                                      </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                      <div class="p-3 rounded-3 bg-light border border-light-subtle h-100">
                                        <div class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                                          <span class="badge bg-info text-white rounded-circle p-1" style="width: 20px; height: 20px; line-height: 12px;">2</span>
                                          <span>Verify with Customer</span>
                                        </div>
                                        <p class="text-muted font-12 mb-2">Check if the customer received any offline direct service or paid directly in cash.</p>
                                        <?php if (!empty($visit->customer_phone)): ?>
                                          <a href="tel:<?= esc($visit->customer_phone) ?>" class="btn btn-xs btn-outline-info rounded-pill px-2 py-1 font-11">
                                            <i class="bi bi-telephone me-1"></i> Call <?= esc($custName) ?>
                                          </a>
                                        <?php endif; ?>
                                      </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                      <div class="p-3 rounded-3 bg-light border border-light-subtle h-100">
                                        <div class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                                          <span class="badge bg-danger rounded-circle p-1" style="width: 20px; height: 20px; line-height: 12px;">3</span>
                                          <span>Enforce Platform Rules</span>
                                        </div>
                                        <p class="text-muted font-12 mb-2">If off-platform work is confirmed, issue a warning or restrict provider payouts/account.</p>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle font-11">Compliance Action</span>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <!-- 6. Developer / Technical Raw JSON (Collapsible Accordion) -->
                              <div class="accordion rounded-4 overflow-hidden shadow-sm" id="accordionRaw-<?= esc(md5($visit->id . '_' . $idx)) ?>">
                                <div class="accordion-item border-0">
                                  <h2 class="accordion-header" id="headingRaw-<?= esc(md5($visit->id . '_' . $idx)) ?>">
                                    <button class="accordion-button collapsed bg-dark text-white font-12 font-monospace py-3 px-4 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRaw-<?= esc(md5($visit->id . '_' . $idx)) ?>" aria-expanded="false">
                                      <i class="bi bi-code-slash text-warning me-2 fs-6"></i> Technical Payload &amp; Raw JSON Telemetry (Click to inspect)
                                    </button>
                                  </h2>
                                  <div id="collapseRaw-<?= esc(md5($visit->id . '_' . $idx)) ?>" class="accordion-collapse collapse" data-bs-parent="#accordionRaw-<?= esc(md5($visit->id . '_' . $idx)) ?>">
                                    <div class="accordion-body bg-dark text-light p-3 position-relative">
                                      <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted font-11">Raw JSON received from mobile app</span>
                                        <button type="button" class="btn btn-xs btn-outline-light rounded-pill px-2 py-0 font-11" onclick="copyText('<?= esc(addslashes($rawJson)) ?>', this)">
                                          <i class="bi bi-clipboard me-1"></i> Copy JSON
                                        </button>
                                      </div>
                                      <pre class="bg-black text-success p-3 mb-0 font-monospace rounded-3" style="font-size: 11px; max-height: 250px; overflow-y: auto;"><?= esc($rawJson) ?></pre>
                                    </div>
                                  </div>
                                </div>
                              </div>

                            </div>

                            <!-- Modal Footer -->
                            <div class="modal-footer bg-white px-4 py-3 border-top d-flex align-items-center justify-content-between">
                              <span class="text-muted font-12">
                                <i class="bi bi-shield-lock me-1"></i>Petsfolio Automated Geofence Monitoring
                              </span>
                              <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                            </div>

                          </div>
                        </div>
                      </div>

                    </td>

                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7" class="text-center py-5">
                    <div class="py-4">
                      <div class="rounded-circle bg-success bg-opacity-10 text-success p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-shield-check fs-1"></i>
                      </div>
                      <h5 class="fw-bold text-dark mb-1">All Clear — No Unauthorized Visits</h5>
                      <p class="text-muted font-14 mb-0">No providers have been detected near customer homes without an active booking. Everything looks good!</p>
                    </div>
                  </td>
                </tr>
              <?php endif; ?>

              <!-- Empty search result row -->
              <tr id="noSearchResultsRow" style="display: none;">
                <td colspan="7" class="text-center py-5">
                  <div class="py-4">
                    <i class="bi bi-search fs-1 text-muted d-block mb-2"></i>
                    <h6 class="fw-bold text-dark">No matching visits found</h6>
                    <p class="text-muted small mb-3">Try adjusting your search terms or clearing active filters.</p>
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="resetAllFilters()">
                      Reset All Filters
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>

  </div>
</main>

<!-- How It Works Comprehensive Info Modal -->
<div class="modal fade" id="geofenceInfoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
      <div class="modal-header bg-dark text-white p-4">
        <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2">
          <i class="bi bi-info-circle text-info"></i>
          How Unauthorized Visit Detection Works
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 font-14">
        <div class="mb-4">
          <h6 class="fw-bold text-dark mb-2"><i class="bi bi-question-circle text-primary me-2"></i>When does a visit get flagged?</h6>
          <p class="text-muted mb-0 lh-base">
            A visit is automatically flagged when a service provider's phone is detected <strong>within 100 meters</strong> of a registered customer's address, they stay for <strong>5 minutes or longer</strong>, and there is <strong>no active ongoing booking</strong> between them on Petsfolio.
          </p>
        </div>
        <hr class="my-3 text-muted">
        <div class="mb-4">
          <h6 class="fw-bold text-dark mb-2"><i class="bi bi-shield-check text-success me-2"></i>Why is this monitored?</h6>
          <ul class="text-muted ps-3 mb-0 lh-base d-flex flex-column gap-2">
            <li><strong>Prevent off-platform deals:</strong> Stops providers from soliciting private direct cash payments from pet owners.</li>
            <li><strong>Protect pets &amp; owners:</strong> Direct offline work bypasses Petsfolio's insurance coverage, dispute resolution, and safety measures.</li>
            <li><strong>Fairness &amp; Transparency:</strong> Protects platform revenue and ensures provider ratings reflect verified service quality.</li>
          </ul>
        </div>
        <hr class="my-3 text-muted">
        <div class="mb-4">
          <h6 class="fw-bold text-dark mb-2"><i class="bi bi-clipboard-check text-warning me-2"></i>Recommended Admin Response Workflow</h6>
          <ol class="text-muted ps-3 mb-0 lh-base d-flex flex-column gap-2">
            <li><strong>Review the visit inspection modal</strong> — check provider name, customer address, timestamp, and linked booking status.</li>
            <li><strong>Call or WhatsApp the provider</strong> — ask why they were present at the customer's home outside of a booking.</li>
            <li><strong>Contact the customer</strong> — verify if an offline service was performed.</li>
            <li><strong>Take appropriate action</strong> — issue a formal policy warning or restrict the provider's account if off-platform bypass is confirmed.</li>
          </ol>
        </div>
        <hr class="my-3 text-muted">
        <div>
          <h6 class="fw-bold text-dark mb-2"><i class="bi bi-lightbulb text-info me-2"></i>Admin Tip</h6>
          <p class="text-muted mb-0 lh-base">
            Occasional single pings can happen if a provider passes by a neighborhood. Look for <strong>repeated visits</strong> or <strong>long dwell durations (5-30+ minutes)</strong> with the same customer to identify true off-platform arrangements.
          </p>
        </div>
      </div>
      <div class="modal-footer bg-light px-4 py-3 border-top">
        <button type="button" class="btn btn-primary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Understood</button>
      </div>
    </div>
  </div>
</div>

<!-- Custom Page Styling -->
<style>
  .font-9 { font-size: 9px; }
  .font-10 { font-size: 10px; }
  .font-11 { font-size: 11px; }
  .font-12 { font-size: 12px; }
  .font-13 { font-size: 13px; }
  .font-14 { font-size: 14px; }
  .tracking-wider { letter-spacing: 0.05em; }
  .max-w-750 { max-width: 750px; }
  .w-fit { width: fit-content; }
  .cursor-pointer { cursor: pointer; }
  .hover-primary:hover { color: #0d6efd !important; }
  .hover-opacity:hover { opacity: 0.8; }
  .transition-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
  .transition-hover:hover { transform: translateY(-3px); box-shadow: 0 0.75rem 1.5rem rgba(0,0,0,0.08) !important; }
  
  .step-card {
    transition: transform 0.2s ease, border-color 0.2s ease;
  }
  .step-card:hover {
    transform: translateY(-2px);
    border-color: #0d6efd !important;
  }

  .pulse-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 0 rgba(220, 53, 69, 0.4);
    animation: pulse 2s infinite;
  }
  @keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
    70% { box-shadow: 0 0 0 8px rgba(220, 53, 69, 0); }
    100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
  }

  .stat-icon {
    width: 46px;
    height: 46px;
  }

  .filter-select-wrapper select {
    min-width: 160px;
    border-color: #dee2e6;
  }

  .avatar-circle {
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  }

  /* Table styling */
  #suspiciousVisitsTable th {
    font-weight: 600;
    letter-spacing: 0.03em;
  }
  #suspiciousVisitsTable td {
    padding-top: 14px;
    padding-bottom: 14px;
  }

  /* Modal custom refinements */
  .modal-dialog-scrollable .modal-body {
    max-height: calc(100vh - 180px);
  }
</style>

<!-- Interactive Table Filtering, Quick Stat Click, & CSV Export Script -->
<script>
  function filterVisitsTable() {
    const searchVal = document.getElementById('searchInput').value.toLowerCase().trim();
    const riskVal = document.getElementById('filterRisk').value;
    const transVal = document.getElementById('filterTransition').value;
    const dateVal = document.getElementById('filterDate').value;

    const rows = document.querySelectorAll('.visit-row');
    let visibleCount = 0;

    const now = new Date();
    const todayStr = now.toISOString().split('T')[0];
    const sevenDaysAgo = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
    const thirtyDaysAgo = new Date(now.getTime() - 30 * 24 * 60 * 60 * 1000);

    rows.forEach(row => {
      const text = row.getAttribute('data-search-text') || '';
      const rowRisk = row.getAttribute('data-risk') || '';
      const rowTrans = row.getAttribute('data-transition') || '';
      const rowDateStr = row.getAttribute('data-date') || '';
      const rowDate = new Date(rowDateStr);

      // Search match
      const matchesSearch = !searchVal || text.includes(searchVal);

      // Risk match
      const matchesRisk = (riskVal === 'ALL') || (rowRisk === riskVal);

      // Transition match
      let matchesTrans = true;
      if (transVal !== 'ALL') {
        if (transVal === 'PROXIMITY_PING') {
          matchesTrans = (rowTrans === 'PROXIMITY_PING' || rowTrans === 'PING');
        } else if (transVal === 'ENTER') {
          matchesTrans = (rowTrans === 'ENTER' || rowTrans === 'GEOFENCE_ENTER');
        } else if (transVal === 'DWELL') {
          matchesTrans = (rowTrans === 'DWELL' || rowTrans === 'GEOFENCE_DWELL');
        } else if (transVal === 'EXIT') {
          matchesTrans = (rowTrans === 'EXIT' || rowTrans === 'GEOFENCE_EXIT');
        } else {
          matchesTrans = (rowTrans === transVal);
        }
      }

      // Date match
      let matchesDate = true;
      if (dateVal === 'TODAY') {
        matchesDate = (rowDateStr === todayStr);
      } else if (dateVal === '7_DAYS') {
        matchesDate = (rowDate >= sevenDaysAgo);
      } else if (dateVal === '30_DAYS') {
        matchesDate = (rowDate >= thirtyDaysAgo);
      }

      if (matchesSearch && matchesRisk && matchesTrans && matchesDate) {
        row.style.display = '';
        visibleCount++;
      } else {
        row.style.display = 'none';
      }
    });

    // Update visible counter
    document.getElementById('visibleCount').textContent = visibleCount;

    // Show/hide no results row
    const noResultsRow = document.getElementById('noSearchResultsRow');
    if (noResultsRow) {
      noResultsRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
    }

    // Indicator badge
    const isFiltered = searchVal || riskVal !== 'ALL' || transVal !== 'ALL' || dateVal !== 'ALL';
    const indicator = document.getElementById('filterIndicator');
    if (indicator) {
      indicator.style.display = isFiltered ? '' : 'none';
    }
  }

  function setQuickFilter(type, value) {
    if (type === 'risk') {
      document.getElementById('filterRisk').value = value;
    }
    filterVisitsTable();
    // Smooth scroll down to table
    document.getElementById('suspiciousVisitsTable').scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  function clearSearch() {
    document.getElementById('searchInput').value = '';
    filterVisitsTable();
  }

  function resetAllFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterRisk').value = 'ALL';
    document.getElementById('filterTransition').value = 'ALL';
    document.getElementById('filterDate').value = 'ALL';
    filterVisitsTable();
  }

  function copyText(text, btn) {
    if (!text) return;
    navigator.clipboard.writeText(text).then(() => {
      const originalHTML = btn.innerHTML;
      btn.innerHTML = '<i class="bi bi-check-lg text-success"></i> Copied';
      setTimeout(() => {
        btn.innerHTML = originalHTML;
      }, 1800);
    }).catch(err => {
      console.error('Failed to copy text: ', err);
    });
  }

  function exportTableToCSV(filename) {
    let csv = [];

    // Header row
    csv.push([
      'Visit Event ID',
      'Risk Category',
      'Provider ID',
      'Provider Name',
      'Provider Phone',
      'Customer ID',
      'Customer Name',
      'Customer Phone',
      'Linked Booking ID',
      'Stage',
      'Visit Timestamp',
      'Latitude',
      'Longitude'
    ].map(escapeCSV).join(','));

    // Data rows
    document.querySelectorAll('.visit-row').forEach(row => {
      if (row.style.display !== 'none') {
        const riskAttr = row.getAttribute('data-risk');
        const risk = (riskAttr === 'COMPLETED') ? 'Completed Booking Visit' : ((riskAttr === 'CANCELLED') ? 'Cancelled Booking Visit' : 'No Booking Found');
        const trans = row.getAttribute('data-transition') || '';
        const date = row.getAttribute('data-date') || '';
        
        // Extract cells
        const spEl = row.querySelector('td:nth-child(2)');
        const custEl = row.querySelector('td:nth-child(3)');
        const bookEl = row.querySelector('td:nth-child(4)');
        const timeEl = row.querySelector('td:nth-child(5)');
        const gpsEl = row.querySelector('td:nth-child(6)');

        const spName = spEl ? (spEl.querySelector('.fw-bold')?.textContent?.trim() || '') : '';
        const spPhone = spEl ? (spEl.querySelector('a[href^="tel:"]')?.textContent?.trim() || '') : '';
        const spId = row.getAttribute('data-provider') || '';

        const custName = custEl ? (custEl.querySelector('.fw-bold')?.textContent?.trim() || '') : '';
        const custPhone = custEl ? (custEl.querySelector('a[href^="tel:"]')?.textContent?.trim() || '') : '';

        const bookId = bookEl ? (bookEl.querySelector('.badge')?.textContent?.replace('#', '')?.trim() || '') : '';
        const timeStr = timeEl ? (timeEl.querySelector('.fw-semibold')?.textContent?.trim() || date) : date;

        const gpsText = gpsEl ? (gpsEl.querySelector('.font-monospace')?.textContent?.trim() || '') : '';
        const gpsParts = gpsText.split(',');
        const lat = gpsParts[0] ? gpsParts[0].trim() : '';
        const lng = gpsParts[1] ? gpsParts[1].trim() : '';

        csv.push([
          row.querySelector('code')?.getAttribute('title') || '',
          risk,
          spId,
          spName,
          spPhone,
          '',
          custName,
          custPhone,
          bookId,
          trans,
          timeStr,
          lat,
          lng
        ].map(escapeCSV).join(','));
      }
    });

    const csvFile = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
    const downloadLink = document.createElement('a');
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
  }

  function escapeCSV(val) {
    if (val === null || val === undefined) return '""';
    return '"' + String(val).replace(/"/g, '""') + '"';
  }
</script>

<?php echo $this->endSection(); ?>
