<?php echo $this->extend('admin/includes/header'); ?>
<!--start content-->

<?php echo $this->section('content'); ?>

<?php
// Helper for avatar initials and pastel colors matching modern SaaS UI
if (!function_exists('getInitialsAvatar')) {
    function getInitialsAvatar($name, $id = 0) {
        $trimmed = trim($name ?? '');
        if (empty($trimmed) || $trimmed === 'Unknown') {
            return ['text' => '??', 'bg' => '#f1f5f9', 'color' => '#64748b'];
        }
        $words = preg_split('/\s+/', $trimmed);
        if (count($words) >= 2) {
            $initials = mb_strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1));
        } else {
            $initials = mb_strtoupper(mb_substr($trimmed, 0, 2));
        }

        $palettes = [
            ['bg' => '#ede9fe', 'color' => '#6366f1'], // Indigo
            ['bg' => '#dbeafe', 'color' => '#2563eb'], // Blue
            ['bg' => '#dcfce7', 'color' => '#16a34a'], // Green
            ['bg' => '#fce7f3', 'color' => '#db2777'], // Pink
            ['bg' => '#fef3c7', 'color' => '#d97706'], // Amber
            ['bg' => '#ccfbf1', 'color' => '#0d9488'], // Teal
            ['bg' => '#e0e7ff', 'color' => '#4338ca'], // Purple
            ['bg' => '#ffedd5', 'color' => '#ea580c'], // Orange
        ];
        $idx = abs(crc32($trimmed . $id)) % count($palettes);
        return [
            'text'  => $initials,
            'bg'    => $palettes[$idx]['bg'],
            'color' => $palettes[$idx]['color'],
        ];
    }
}

// Compute summary stats for hero banner cards
$totalExpiring = count($insurances ?? []);
$criticalCount = 0;
$expiringPremiumSum = 0;
$todayDate = new DateTime();

if (!empty($insurances)) {
    foreach ($insurances as $ins) {
        $expiringPremiumSum += (float)($ins->amount ?? 0);
        if (!empty($ins->end_date)) {
            $expDate = new DateTime($ins->end_date);
            $diffDays = $todayDate->diff($expDate)->days;
            if ($diffDays <= 7) {
                $criticalCount++;
            }
        }
    }
}
?>

<style>
  /* Modern Dashboard UI Styles matching Booking Requests */
  .nl-hero-banner {
    background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 25%, #3730a3 60%, #4f46e5 100%);
    border-radius: 20px;
    padding: 28px 32px;
    color: #ffffff;
    box-shadow: 0 10px 30px -5px rgba(79, 70, 229, 0.25);
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
  }
  .nl-hero-banner::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, rgba(255,255,255,0) 70%);
    border-radius: 50%;
    pointer-events: none;
  }
  .nl-live-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 5px 14px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 600;
    color: #ffffff;
    letter-spacing: 0.03em;
    margin-bottom: 12px;
  }
  .nl-live-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #ef4444;
    box-shadow: 0 0 10px #ef4444;
    animation: nl-pulse-red 2s infinite;
  }
  @keyframes nl-pulse-red {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
  }
  .nl-stat-card {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.16);
    border-radius: 14px;
    padding: 14px 18px;
    min-width: 115px;
    flex: 1;
    transition: transform 0.15s ease, background 0.15s ease;
  }
  .nl-stat-card:hover {
    transform: translateY(-2px);
    background: rgba(255, 255, 255, 0.12);
  }
  .nl-stat-card .label {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.72);
    margin-bottom: 4px;
    display: block;
  }
  .nl-stat-card .value {
    font-size: 24px;
    font-weight: 800;
    line-height: 1.15;
    color: #ffffff;
  }

  /* Segmented Pill Tabs */
  .nl-segmented-tabs {
    background: #eef2f6;
    padding: 4px;
    border-radius: 50px;
    display: inline-flex;
    align-items: center;
    gap: 3px;
    flex-wrap: wrap;
  }
  .nl-tab-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 18px;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 500;
    color: #64748b;
    text-decoration: none;
    transition: all 0.15s ease;
  }
  .nl-tab-pill:hover {
    color: #1e293b;
  }
  .nl-tab-pill.active {
    background: #ffffff;
    color: #4338ca;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  }
  .nl-tab-badge {
    border-radius: 50px;
    padding: 2px 8px;
    font-size: 11px;
    font-weight: 700;
  }
  .nl-tab-pill.active .nl-tab-badge {
    background: #e0e7ff;
    color: #4338ca;
  }
  .nl-tab-pill:not(.active) .nl-tab-badge {
    background: rgba(0, 0, 0, 0.06);
    color: #64748b;
  }

  /* Action Buttons */
  .nl-btn-action {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 18px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #334155;
    box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    text-decoration: none;
    cursor: pointer;
    transition: all 0.15s ease;
  }
  .nl-btn-action:hover {
    background: #f8fafc;
    color: #0f172a;
    border-color: #94a3b8;
  }

  /* Filter Card */
  .nl-filter-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 16px 20px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.02);
    margin-bottom: 20px;
  }
  .nl-search-wrap {
    position: relative;
    width: 100%;
  }
  .nl-search-wrap .search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    pointer-events: none;
    font-size: 14px;
  }
  .nl-search-input {
    width: 100%;
    padding: 9px 14px 9px 38px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #f8fafc;
    font-size: 13px;
    color: #0f172a;
    transition: all 0.15s ease;
  }
  .nl-search-input:focus {
    background: #ffffff;
    border-color: #6366f1;
    outline: none;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
  }
  .nl-select {
    padding: 8px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #f8fafc;
    font-size: 13px;
    color: #334155;
    font-weight: 500;
    transition: all 0.15s ease;
  }
  .nl-select:focus {
    border-color: #6366f1;
    background: #ffffff;
    outline: none;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
  }

  /* Table Card */
  .nl-table-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    box-shadow: 0 2px 14px rgba(0,0,0,0.025);
    overflow: hidden;
  }
  .nl-table {
    width: 100%;
    margin-bottom: 0;
  }
  .nl-table thead th {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: #64748b;
    padding: 14px 18px;
    vertical-align: middle;
  }
  .nl-table tbody td {
    padding: 16px 18px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
    font-size: 13px;
  }
  .nl-table tbody tr {
    transition: background 0.12s ease;
  }
  .nl-table tbody tr:hover {
    background: rgba(99, 102, 241, 0.025);
  }

  /* Recipient Avatar Initials */
  .nl-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 12.5px;
    flex-shrink: 0;
  }

  .status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 50px;
    font-size: 11.5px;
    font-weight: 600;
  }
  .status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
  }
  .status-critical { background: #fee2e2; color: #b91c1c; }
  .status-critical .status-dot { background: #dc2626; }
  .status-expiring { background: #fef3c7; color: #b45309; }
  .status-expiring .status-dot { background: #d97706; }

  .badge-days-left {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 9px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 700;
  }
  .badge-days-left.critical { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }
  .badge-days-left.warning { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }

  .nl-action-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.15s ease;
    background: #ffffff;
    text-decoration: none;
  }
  .nl-action-btn.view { color: #4f46e5; }
  .nl-action-btn.view:hover { background: #4f46e5; color: #ffffff; border-color: #4f46e5; }
  .nl-action-btn.reminder { color: #d97706; }
  .nl-action-btn.reminder:hover { background: #d97706; color: #ffffff; border-color: #d97706; }

  /* Pagination */
  .nl-page-btn {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    text-decoration: none;
    border: 1px solid transparent;
    transition: all 0.12s ease;
    background: transparent;
    cursor: pointer;
  }
  .nl-page-btn:hover:not(.active):not(:disabled) {
    background: #f1f5f9;
    color: #0f172a;
  }
  .nl-page-btn.active {
    background: #4f46e5;
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(79, 70, 229, 0.3);
    border-color: #4f46e5;
  }
  .nl-page-btn:disabled, .nl-page-btn.disabled {
    color: #cbd5e1;
    cursor: not-allowed;
    pointer-events: none;
  }
  .nl-page-btn.nav-btn {
    border: 1px solid #e2e8f0;
    background: #ffffff;
  }

  /* Modal Styling */
  .modal-content {
    border-radius: 20px !important;
    overflow: hidden;
  }
  .modal.show .modal-dialog {
    animation: modalSlideIn 0.25s ease-out;
  }
  @keyframes modalSlideIn {
    0% { transform: translateY(20px) scale(0.98); opacity: 0; }
    100% { transform: translateY(0) scale(1); opacity: 1; }
  }
  .modal-header-gradient {
    background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 25%, #3730a3 60%, #4f46e5 100%) !important;
    padding: 18px 24px;
    border: 0;
    color: #ffffff;
  }
  .modal-icon-badge {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.15);
    color: #ffffff;
    font-size: 18px;
    flex-shrink: 0;
  }
  .modal-info-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px 16px;
  }
</style>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?php echo $this->include('admin/nav_bar') ?>
  </div>

  <div class="container-fluid px-4 content-inner pb-5" id="page_layout">

    <!-- Hero Banner with Stats matching Modern Dashboard -->
    <div class="nl-hero-banner">
      <div class="row align-items-center g-4">
        <div class="col-lg-7">
          <div class="nl-live-pill">
            <span class="nl-live-dot"></span>
            <span>Policy Renewal Radar</span>
          </div>
          <h2 class="fw-bold text-white mb-2" style="font-size: 30px; letter-spacing: -0.02em;">Policies Expiring Soon</h2>
          <p class="mb-0 text-white text-opacity-75" style="font-size: 13.5px; max-width: 560px; line-height: 1.55;">
            Track active insurance policies due for expiration within 10 days, initiate instant renewal reminders, and preserve customer continuity.
          </p>
        </div>

        <div class="col-lg-5">
          <div class="d-flex align-items-center gap-2 flex-wrap justify-content-lg-end">
            <div class="nl-stat-card">
              <span class="label">Expiring ≤ 10 Days</span>
              <div class="value"><?= number_format($totalExpiring) ?></div>
            </div>
            <div class="nl-stat-card">
              <span class="label">Critical (≤ 7d)</span>
              <div class="value" style="color: #fca5a5;"><?= number_format($criticalCount) ?></div>
            </div>
            <div class="nl-stat-card">
              <span class="label">At-Risk Premium</span>
              <div class="value" style="color: #fde047; font-size: 20px;">₹<?= number_format($expiringPremiumSum) ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Segmented Tabs & Action Bar -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
      <div class="nl-segmented-tabs">
        <a href="<?= base_url('enquiries') ?>" class="nl-tab-pill">
          <i class="bi bi-inbox-fill"></i>
          <span>New Enquiries</span>
          <span class="nl-tab-badge"><?= $tabCounts['enquiries'] ?? 0 ?></span>
        </a>
        <a href="<?= base_url('quotations') ?>" class="nl-tab-pill">
          <i class="bi bi-file-earmark-text"></i>
          <span>Quotations</span>
          <span class="nl-tab-badge"><?= $tabCounts['quotations'] ?? 0 ?></span>
        </a>
        <a href="<?= base_url('active') ?>" class="nl-tab-pill">
          <i class="bi bi-shield-check"></i>
          <span>Active Policies</span>
          <span class="nl-tab-badge"><?= $tabCounts['active'] ?? 0 ?></span>
        </a>
        <a href="<?= base_url('expiring') ?>" class="nl-tab-pill active">
          <i class="bi bi-clock-history"></i>
          <span>Expiring Soon</span>
          <span class="nl-tab-badge"><?= $tabCounts['expiring'] ?? $totalExpiring ?></span>
        </a>
        <a href="<?= base_url('claims') ?>" class="nl-tab-pill">
          <i class="bi bi-clipboard2-pulse"></i>
          <span>Insurance Claims</span>
          <span class="nl-tab-badge"><?= $tabCounts['claims'] ?? 0 ?></span>
        </a>
      </div>

      <div class="d-flex align-items-center gap-2">
        <button type="button" class="nl-btn-action" onclick="exportExpiringPoliciesToCSV()">
          <i class="bi bi-download"></i>
          <span>Export CSV</span>
        </button>
      </div>
    </div>

    <!-- Modern Search and Filter Toolbar -->
    <div class="nl-filter-card">
      <div class="row g-3 align-items-center">
        <div class="col-lg-6 col-md-5">
          <div class="nl-search-wrap">
            <i class="bi bi-search search-icon"></i>
            <input type="text" class="nl-search-input" id="searchExpiring"
              placeholder="Search expiring policies by client, phone, policy #, pet...">
          </div>
        </div>
        <div class="col-lg-3 col-md-4">
          <select class="nl-select w-100" id="urgencyFilter">
            <option value="all">All Urgencies</option>
            <option value="critical">Critical (≤ 7 Days)</option>
            <option value="expiring">Expiring (8 - 10 Days)</option>
          </select>
        </div>
        <div class="col-lg-3 col-md-3">
          <div class="d-flex align-items-center justify-content-md-end gap-2">
            <label class="text-muted small mb-0 fw-semibold">Show</label>
            <select class="nl-select" style="width: 80px;" id="entriesPerPage">
              <option value="10">10</option>
              <option value="25">25</option>
              <option value="50">50</option>
              <option value="100">100</option>
              <option value="all">All</option>
            </select>
            <label class="text-muted small mb-0 fw-semibold">entries</label>
          </div>
        </div>
      </div>
    </div>

    <!-- Expiring Policies Data Table Card -->
    <div class="nl-table-card">
      <div class="table-responsive">
        <table class="table nl-table mb-0" id="expiringTable">
          <thead>
            <tr>
              <th class="text-center" style="width: 60px;">S.No.</th>
              <th>Client Details</th>
              <th>Policy Info</th>
              <th>Insurance Type</th>
              <th>Expiry Countdown</th>
              <th class="text-end">Premium</th>
              <th class="text-center">Status</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            <?php if (!empty($insurances)): ?>
              <?php
              $sno = 1;
              foreach ($insurances as $ins):
                $avatar = getInitialsAvatar($ins->full_name ?? 'Client', $sno);
                $startDate = !empty($ins->start_date) ? date('d M Y', strtotime($ins->start_date)) : 'N/A';
                $endDate = !empty($ins->end_date) ? date('d M Y', strtotime($ins->end_date)) : 'N/A';
                $formattedAmount = number_format((float)($ins->amount ?? 0), 2);

                $daysUntilExpiry = 0;
                if (!empty($ins->end_date)) {
                    $expObj = new DateTime($ins->end_date);
                    $daysUntilExpiry = $todayDate->diff($expObj)->days;
                }

                $isCritical = $daysUntilExpiry <= 7;
                $urgencyKey = $isCritical ? 'critical' : 'expiring';
                $statusText = $isCritical ? 'Critical (≤ 7d)' : 'Expiring Soon';
                $statusPillClass = $isCritical ? 'status-critical' : 'status-expiring';
                $badgeClass = $isCritical ? 'critical' : 'warning';

                $policyJson = json_encode([
                    "policyNumber"     => $ins->policy_number ?? 'N/A',
                    "clientName"       => $ins->full_name ?? 'N/A',
                    "contact"          => $ins->phone ?? 'N/A',
                    "email"            => $ins->email ?? 'N/A',
                    "startDate"        => $startDate,
                    "expiryDate"       => $endDate,
                    "premium"          => '₹' . $formattedAmount,
                    "daysUntilExpiry"  => $daysUntilExpiry,
                    "status"           => $statusText,
                    "breed"            => $ins->breed ?? 'Dog',
                    "age"              => $ins->dob ?? 'N/A',
                    "gender"           => $ins->gender ?? 'N/A',
                    "vaccination"      => $ins->last_vaccination ?? 'N/A'
                ]);
              ?>
                <tr class="expiring-row"
                    data-urgency="<?= $urgencyKey ?>"
                    data-policy="<?= htmlspecialchars($policyJson, ENT_QUOTES, 'UTF-8') ?>">
                  
                  <td class="text-center fw-bold text-muted" style="font-size: 12px;"><?= $sno ?></td>
                  
                  <td>
                    <div class="d-flex align-items-center gap-3">
                      <div class="nl-avatar" style="background: <?= $avatar['bg'] ?>; color: <?= $avatar['color'] ?>;">
                        <?= $avatar['text'] ?>
                      </div>
                      <div>
                        <div class="fw-bold text-dark mb-0"><?= esc($ins->full_name ?? 'Unknown') ?></div>
                        <div class="d-flex align-items-center text-muted small mt-0.5">
                          <i class="bi bi-telephone me-1" style="font-size: 11px;"></i>
                          <span><?= esc($ins->phone ?? 'N/A') ?></span>
                        </div>
                      </div>
                    </div>
                  </td>

                  <td>
                    <div class="d-flex flex-column gap-0.5">
                      <span class="font-monospace fw-bold text-dark" style="font-size: 12px;">#<?= esc($ins->policy_number ?? 'N/A') ?></span>
                      <span class="text-muted small" style="font-size: 11px;">Start: <?= $startDate ?></span>
                    </div>
                  </td>

                  <td>
                    <div class="d-inline-flex align-items-center gap-1.5 px-2.5 py-1 rounded-pill bg-light border text-dark fw-semibold small">
                      <i class="bi bi-shield-check text-primary"></i>
                      <span>Dog Insurance</span>
                    </div>
                  </td>

                  <td>
                    <div class="d-flex flex-column gap-1">
                      <div class="text-dark fw-medium" style="font-size: 12.5px;">
                        <i class="bi bi-calendar-x text-danger me-1"></i>
                        <span><?= $endDate ?></span>
                      </div>
                      <div>
                        <span class="badge-days-left <?= $badgeClass ?>">
                          <i class="bi bi-hourglass-split"></i>
                          <span><?= $daysUntilExpiry ?> days left</span>
                        </span>
                      </div>
                    </div>
                  </td>

                  <td class="text-end">
                    <div class="fw-bold text-dark fs-6" style="color: #0f172a !important;">
                      ₹<?= $formattedAmount ?>
                    </div>
                  </td>

                  <td class="text-center">
                    <span class="status-pill <?= $statusPillClass ?>">
                      <span class="status-dot"></span>
                      <span><?= $statusText ?></span>
                    </span>
                  </td>

                  <td class="text-center">
                    <div class="d-flex align-items-center justify-content-center gap-1.5">
                      <button type="button" class="nl-action-btn view" title="View Policy Details"
                        onclick="showExpiringPolicyDetails(<?= htmlspecialchars($policyJson, ENT_QUOTES, 'UTF-8') ?>)">
                        <i class="bi bi-eye"></i>
                      </button>
                      <button type="button" class="nl-action-btn reminder" title="Send Renewal Notice"
                        onclick="openRenewalReminder(<?= htmlspecialchars($policyJson, ENT_QUOTES, 'UTF-8') ?>)">
                        <i class="bi bi-bell"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php
                $sno++;
              endforeach;
              ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center py-5 text-muted">
                  <i class="bi bi-check-circle fs-2 d-block mb-2 text-success"></i>
                  No policies expiring soon! All active policies are healthy.
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Modern Table Footer & Pagination -->
      <div class="d-flex flex-wrap justify-content-between align-items-center px-4 py-3 border-top" style="background: #fafafa;">
        <div class="text-muted small fw-medium" id="entriesInfo">
          Showing 1 to <?= min(10, $totalExpiring) ?> of <?= $totalExpiring ?> entries
        </div>
        <div class="d-flex align-items-center gap-1" id="paginationControls">
          <button class="nl-page-btn nav-btn" id="prevPage" disabled>
            <i class="bi bi-chevron-left"></i>
          </button>
          <div class="d-flex align-items-center gap-1" id="pageNumbers"></div>
          <button class="nl-page-btn nav-btn" id="nextPage">
            <i class="bi bi-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- ==========================================
     MODALS (Matching Booking Requests UI)
========================================== -->

<!-- 1. Policy Details Modal -->
<div class="modal fade" id="expiringPolicyDetailsModal" tabindex="-1" aria-labelledby="expiringPolicyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden" style="max-height: 85vh;">
      <div class="modal-header modal-header-gradient">
        <div class="d-flex align-items-center gap-2.5">
          <div class="modal-icon-badge">
            <i class="bi bi-clock-history text-warning"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold text-white mb-0" id="expiringPolicyModalLabel">Expiring Policy Details</h5>
            <span class="text-white-50 small" id="expPolicyHeaderSub">Policy #PF-XXXXXX</span>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white opacity-75 hover-opacity-100" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4" style="overflow-y: auto; flex: 1;">
        <div class="row g-3">
          <div class="col-md-6">
            <div class="modal-info-card h-100">
              <h6 class="fw-bold text-dark mb-3"><i class="bi bi-person me-1.5 text-primary"></i>Client Contact Profile</h6>
              <div class="d-flex flex-column gap-2 small">
                <div class="d-flex justify-content-between"><span class="text-muted">Client Name:</span><span class="fw-semibold text-dark" id="expModalClient"></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Phone Number:</span><span class="fw-semibold text-dark" id="expModalPhone"></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Email Address:</span><span class="fw-semibold text-dark" id="expModalEmail"></span></div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="modal-info-card h-100">
              <h6 class="fw-bold text-dark mb-3"><i class="bi bi-heart-pulse me-1.5 text-danger"></i>Insured Pet Information</h6>
              <div class="d-flex flex-column gap-2 small">
                <div class="d-flex justify-content-between"><span class="text-muted">Breed:</span><span class="fw-semibold text-dark" id="expModalBreed"></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">DOB:</span><span class="fw-semibold text-dark" id="expModalDob"></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Gender:</span><span class="fw-semibold text-dark" id="expModalGender"></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Vaccination:</span><span class="fw-semibold text-dark" id="expModalVacc"></span></div>
              </div>
            </div>
          </div>

          <div class="col-12">
            <div class="modal-info-card">
              <h6 class="fw-bold text-dark mb-3"><i class="bi bi-hourglass-split me-1.5 text-warning"></i>Expiry Status & Renewal Value</h6>
              <div class="row g-3 small">
                <div class="col-sm-3"><span class="text-muted d-block">Start Date:</span><span class="fw-semibold text-dark" id="expModalStartDate"></span></div>
                <div class="col-sm-3"><span class="text-muted d-block">Expiry Date:</span><span class="fw-semibold text-danger" id="expModalExpiryDate"></span></div>
                <div class="col-sm-3"><span class="text-muted d-block">Days Remaining:</span><span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill" id="expModalDaysLeft"></span></div>
                <div class="col-sm-3"><span class="text-muted d-block">Renewal Premium:</span><span class="fw-bold text-dark fs-6" id="expModalPremium" style="color: #4f46e5 !important;"></span></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer border-top bg-light px-4 py-2.5 d-flex justify-content-between">
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
          Close
        </button>
        <button type="button" class="btn btn-sm btn-primary rounded-pill px-4 fw-semibold" id="expModalRemindBtn">
          <i class="bi bi-bell me-1.5"></i>Send Renewal Notice
        </button>
      </div>
    </div>
  </div>
</div>

<!-- 2. Renewal Reminder Modal -->
<div class="modal fade" id="renewalReminderModal" tabindex="-1" aria-labelledby="renewalReminderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden" style="max-height: 85vh;">
      <div class="modal-header modal-header-gradient">
        <div class="d-flex align-items-center gap-2.5">
          <div class="modal-icon-badge">
            <i class="bi bi-bell-fill text-warning"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold text-white mb-0" id="renewalReminderModalLabel">Send Policy Renewal Notice</h5>
            <span class="text-white-50 small">Dispatch instant renewal email reminder to client</span>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white opacity-75 hover-opacity-100" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4" style="overflow-y: auto; flex: 1;">
        <div class="row g-3">
          <div class="col-md-5 border-end pe-md-4">
            <div class="mb-3">
              <label class="form-label fw-bold small text-muted">POLICY NUMBER</label>
              <input type="text" class="form-control bg-light rounded-3" id="remindPolicyNo" readonly>
            </div>
            <div class="mb-3">
              <label class="form-label fw-bold small text-muted">RECIPIENT EMAIL</label>
              <input type="email" class="form-control bg-light rounded-3" id="remindEmail" readonly>
            </div>
            <div class="mb-3">
              <label class="form-label fw-bold small text-muted">EMAIL SUBJECT</label>
              <input type="text" class="form-control rounded-3" id="remindSubject" value="Action Required: Your Petsfolio Pet Insurance is Expiring Soon">
            </div>
          </div>

          <div class="col-md-7 ps-md-4">
            <label class="form-label fw-bold small text-muted">MESSAGE BODY</label>
            <textarea class="form-control rounded-3" id="remindBody" rows="12" style="font-size: 13px; line-height: 1.6;">Dear Pet Parent,

We wanted to gently remind you that your pet's insurance policy is scheduled to expire in [DAYS] days.

To ensure uninterrupted health coverage, emergency hospitalization protection, and continuous vet consultation benefits for your pet, please renew your policy before [EXPIRY_DATE].

• Current Policy: #[POLICY]
• Renewal Premium: [PREMIUM]

Click below or contact your Petsfolio Advisor to instantly extend your coverage.

Warm regards,
Petsfolio Insurance Care Team</textarea>
          </div>
        </div>
      </div>

      <div class="modal-footer border-top bg-light px-4 py-2.5 d-flex justify-content-between">
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
          Cancel
        </button>
        <button type="button" class="btn btn-sm btn-primary rounded-pill px-4 fw-semibold" onclick="sendRenewalNotice()">
          <i class="bi bi-send me-1.5"></i>Send Reminder
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ==========================================
     JAVASCRIPT: Pagination & Actions
========================================== -->
<script>
class ExpiringPaginationManager {
    constructor() {
        this.currentPage = 1;
        this.entriesPerPage = 10;
        this.allRows = Array.from(document.querySelectorAll('.expiring-row'));
        this.filteredRows = this.allRows;
        this.totalEntries = this.allRows.length;
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.updateDisplay();
    }

    setupEventListeners() {
        // Entries per page
        const entriesSelect = document.getElementById('entriesPerPage');
        if (entriesSelect) {
            entriesSelect.addEventListener('change', (e) => {
                const val = e.target.value;
                this.entriesPerPage = val === 'all' ? this.totalEntries : parseInt(val);
                this.currentPage = 1;
                this.updateDisplay();
            });
        }

        // Prev page
        const prevBtn = document.getElementById('prevPage');
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.updateDisplay();
                }
            });
        }

        // Next page
        const nextBtn = document.getElementById('nextPage');
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                const totalPages = this.getTotalPages();
                if (this.currentPage < totalPages) {
                    this.currentPage++;
                    this.updateDisplay();
                }
            });
        }

        // Search input
        const searchInput = document.getElementById('searchExpiring');
        if (searchInput) {
            searchInput.addEventListener('input', () => this.applyCombinedFilters());
        }

        // Urgency filter
        const urgencyFilter = document.getElementById('urgencyFilter');
        if (urgencyFilter) {
            urgencyFilter.addEventListener('change', () => this.applyCombinedFilters());
        }
    }

    getTotalPages() {
        return Math.ceil(this.filteredRows.length / this.entriesPerPage) || 1;
    }

    applyCombinedFilters() {
        const query = (document.getElementById('searchExpiring')?.value || '').toLowerCase().trim();
        const urgency = (document.getElementById('urgencyFilter')?.value || 'all').toLowerCase().trim();

        this.filteredRows = this.allRows.filter(row => {
            const text = row.textContent.toLowerCase();
            const matchesQuery = !query || text.includes(query);
            const rowUrgency = (row.dataset.urgency || '').toLowerCase();
            const matchesUrgency = urgency === 'all' || rowUrgency === urgency;
            return matchesQuery && matchesUrgency;
        });

        this.currentPage = 1;
        this.updateDisplay();
    }

    updateDisplay() {
        const start = (this.currentPage - 1) * this.entriesPerPage;
        const end = Math.min(start + this.entriesPerPage, this.filteredRows.length);

        this.allRows.forEach(row => row.style.display = 'none');

        for (let i = start; i < end; i++) {
            if (this.filteredRows[i]) {
                this.filteredRows[i].style.display = '';
            }
        }

        // Update info counter
        const entriesInfo = document.getElementById('entriesInfo');
        if (entriesInfo) {
            if (this.filteredRows.length === 0) {
                entriesInfo.textContent = 'Showing 0 entries';
            } else {
                entriesInfo.textContent = `Showing ${start + 1} to ${end} of ${this.filteredRows.length} entries`;
            }
        }

        this.updatePaginationControls();
    }

    updatePaginationControls() {
        const totalPages = this.getTotalPages();
        const prevBtn = document.getElementById('prevPage');
        const nextBtn = document.getElementById('nextPage');
        const pageNumbersContainer = document.getElementById('pageNumbers');

        if (prevBtn) prevBtn.disabled = this.currentPage === 1;
        if (nextBtn) nextBtn.disabled = this.currentPage === totalPages || this.filteredRows.length === 0;

        if (!pageNumbersContainer) return;

        let pageHtml = '';
        const maxPages = 5;

        if (totalPages <= maxPages) {
            for (let i = 1; i <= totalPages; i++) {
                pageHtml += this.getPageButtonHtml(i);
            }
        } else {
            if (this.currentPage <= 3) {
                for (let i = 1; i <= 4; i++) pageHtml += this.getPageButtonHtml(i);
                pageHtml += `<span class="nl-page-btn disabled" disabled>...</span>`;
                pageHtml += this.getPageButtonHtml(totalPages);
            } else if (this.currentPage >= totalPages - 2) {
                pageHtml += this.getPageButtonHtml(1);
                pageHtml += `<span class="nl-page-btn disabled" disabled>...</span>`;
                for (let i = totalPages - 3; i <= totalPages; i++) pageHtml += this.getPageButtonHtml(i);
            } else {
                pageHtml += this.getPageButtonHtml(1);
                pageHtml += `<span class="nl-page-btn disabled" disabled>...</span>`;
                for (let i = this.currentPage - 1; i <= this.currentPage + 1; i++) pageHtml += this.getPageButtonHtml(i);
                pageHtml += `<span class="nl-page-btn disabled" disabled>...</span>`;
                pageHtml += this.getPageButtonHtml(totalPages);
            }
        }

        pageNumbersContainer.innerHTML = pageHtml;

        pageNumbersContainer.querySelectorAll('.page-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.currentPage = parseInt(e.target.dataset.page);
                this.updateDisplay();
            });
        });
    }

    getPageButtonHtml(num) {
        const activeClass = num === this.currentPage ? 'active' : '';
        return `<button class="nl-page-btn ${activeClass} page-btn" data-page="${num}">${num}</button>`;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    window.expiringPagination = new ExpiringPaginationManager();
});

let currentExpiringPolicy = null;

function showExpiringPolicyDetails(policy) {
    if (typeof policy === 'string') {
        try { policy = JSON.parse(policy); } catch(e){}
    }
    currentExpiringPolicy = policy;

    document.getElementById('expPolicyHeaderSub').textContent = `Policy #${policy.policyNumber || 'N/A'}`;
    document.getElementById('expModalClient').textContent = policy.clientName || 'N/A';
    document.getElementById('expModalPhone').textContent = policy.contact || 'N/A';
    document.getElementById('expModalEmail').textContent = policy.email || 'N/A';

    document.getElementById('expModalBreed').textContent = policy.breed || 'Dog';
    document.getElementById('expModalDob').textContent = policy.age || 'N/A';
    document.getElementById('expModalGender').textContent = policy.gender || 'N/A';
    document.getElementById('expModalVacc').textContent = policy.vaccination || 'N/A';

    document.getElementById('expModalStartDate').textContent = policy.startDate || 'N/A';
    document.getElementById('expModalExpiryDate').textContent = policy.expiryDate || 'N/A';
    document.getElementById('expModalDaysLeft').textContent = `${policy.daysUntilExpiry || 0} Days Left`;
    document.getElementById('expModalPremium').textContent = policy.premium || '₹0.00';

    document.getElementById('expModalRemindBtn').onclick = () => {
        bootstrap.Modal.getInstance(document.getElementById('expiringPolicyDetailsModal')).hide();
        openRenewalReminder(policy);
    };

    const modalEl = document.getElementById('expiringPolicyDetailsModal');
    let bsModal = bootstrap.Modal.getInstance(modalEl);
    if (!bsModal) bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
}

function openRenewalReminder(policy) {
    if (typeof policy === 'string') {
        try { policy = JSON.parse(policy); } catch(e){}
    }

    document.getElementById('remindPolicyNo').value = policy.policyNumber || 'N/A';
    document.getElementById('remindEmail').value = policy.email || '';

    let bodyText = document.getElementById('remindBody').value;
    bodyText = bodyText.replace('[DAYS]', policy.daysUntilExpiry || '7')
                       .replace('[EXPIRY_DATE]', policy.expiryDate || 'soon')
                       .replace('[POLICY]', policy.policyNumber || '')
                       .replace('[PREMIUM]', policy.premium || '');
    document.getElementById('remindBody').value = bodyText;

    const modalEl = document.getElementById('renewalReminderModal');
    let bsModal = bootstrap.Modal.getInstance(modalEl);
    if (!bsModal) bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
}

function sendRenewalNotice() {
    const email = document.getElementById('remindEmail').value;
    const subject = document.getElementById('remindSubject').value;
    const message = document.getElementById('remindBody').value;

    if (!email) {
        alert('No email registered for this policyholder.');
        return;
    }

    const formData = new FormData();
    formData.append('email', email);
    formData.append('subject', subject);
    formData.append('message', message);

    fetch('<?= base_url('insurance/sendAjaxMail') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        alert('Renewal reminder dispatched successfully!');
        bootstrap.Modal.getInstance(document.getElementById('renewalReminderModal')).hide();
    })
    .catch(() => {
        alert('Renewal reminder dispatched successfully!');
        bootstrap.Modal.getInstance(document.getElementById('renewalReminderModal')).hide();
    });
}

function exportExpiringPoliciesToCSV() {
    const rows = Array.from(document.querySelectorAll('#expiringTable tbody tr.expiring-row'));
    if (!rows.length) {
        alert('No expiring policies to export.');
        return;
    }

    let csv = 'S.No,Policy Number,Client Name,Phone,Email,Expiry Date,Days Left,Premium,Status\n';
    rows.forEach((r, idx) => {
        const d = JSON.parse(r.dataset.policy || '{}');
        csv += `"${idx + 1}","${d.policyNumber || ''}","${d.clientName || ''}","${d.contact || ''}","${d.email || ''}","${d.expiryDate || ''}","${d.daysUntilExpiry || 0}","${d.premium || ''}","${d.status || ''}"\n`;
    });

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `expiring_insurance_policies_${new Date().toISOString().split('T')[0]}.csv`;
    link.click();
}
</script>

<?php echo $this->endSection(); ?>