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
$totalActive = count($insurances ?? []);
$totalPremiumSum = 0;
$comprehensiveCount = 0;

if (!empty($insurances)) {
    foreach ($insurances as $ins) {
        $totalPremiumSum += (float)($ins->amount ?? 0);
        if (stripos($ins->coverage_type ?? '', 'comp') !== false) {
            $comprehensiveCount++;
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
    background: #22c55e;
    box-shadow: 0 0 10px #22c55e;
    animation: nl-pulse 2s infinite;
  }
  @keyframes nl-pulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(34, 197, 94, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
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
  .nl-btn-primary {
    background: #4f46e5;
    color: #ffffff;
    border-color: #4f46e5;
  }
  .nl-btn-primary:hover {
    background: #4338ca;
    color: #ffffff;
    border-color: #4338ca;
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

  .badge-service {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 11px;
    border-radius: 50px;
    font-size: 11.5px;
    font-weight: 600;
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
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
  .status-active { background: #dcfce7; color: #15803d; }
  .status-active .status-dot { background: #16a34a; }

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
            <span>Active Policy Ledger</span>
          </div>
          <h2 class="fw-bold text-white mb-2" style="font-size: 30px; letter-spacing: -0.02em;">Active Insurance Policies</h2>
          <p class="mb-0 text-white text-opacity-75" style="font-size: 13.5px; max-width: 560px; line-height: 1.55;">
            Manage and audit active pet health and life coverage, track policy durations, verify premium receipts, and inspect insured pet profiles.
          </p>
        </div>

        <div class="col-lg-5">
          <div class="d-flex align-items-center gap-2 flex-wrap justify-content-lg-end">
            <div class="nl-stat-card">
              <span class="label">Active Policies</span>
              <div class="value"><?= number_format($totalActive) ?></div>
            </div>
            <div class="nl-stat-card">
              <span class="label">Annual Premium</span>
              <div class="value" style="color: #6ee7b7; font-size: 20px;">₹<?= number_format($totalPremiumSum) ?></div>
            </div>
            <div class="nl-stat-card">
              <span class="label">Comprehensive</span>
              <div class="value" style="color: #93c5fd;"><?= number_format($comprehensiveCount) ?></div>
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
        <a href="<?= base_url('active') ?>" class="nl-tab-pill active">
          <i class="bi bi-shield-check"></i>
          <span>Active Policies</span>
          <span class="nl-tab-badge"><?= $tabCounts['active'] ?? $totalActive ?></span>
        </a>
        <a href="<?= base_url('expiring') ?>" class="nl-tab-pill">
          <i class="bi bi-clock-history"></i>
          <span>Expiring Soon</span>
          <span class="nl-tab-badge"><?= $tabCounts['expiring'] ?? 0 ?></span>
        </a>
        <a href="<?= base_url('claims') ?>" class="nl-tab-pill">
          <i class="bi bi-clipboard2-pulse"></i>
          <span>Insurance Claims</span>
          <span class="nl-tab-badge"><?= $tabCounts['claims'] ?? 0 ?></span>
        </a>
      </div>

      <div class="d-flex align-items-center gap-2">
        <button type="button" class="nl-btn-action nl-btn-primary" onclick="openAddInsuranceModal()">
          <i class="bi bi-plus-lg"></i>
          <span>Add New Policy</span>
        </button>
        <button type="button" class="nl-btn-action" onclick="exportActivePoliciesToCSV()">
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
            <input type="text" class="nl-search-input" id="searchActivePolicies"
              placeholder="Search by client, phone, policy #, pet name, breed...">
          </div>
        </div>
        <div class="col-lg-3 col-md-4">
          <select class="nl-select w-100" id="coverageFilter">
            <option value="all">All Coverage Types</option>
            <option value="comprehensive">Comprehensive</option>
            <option value="standard">Standard</option>
            <option value="basic">Basic</option>
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

    <!-- Active Policies Data Table Card -->
    <div class="nl-table-card">
      <div class="table-responsive">
        <table class="table nl-table mb-0" id="activeTable">
          <thead>
            <tr>
              <th class="text-center" style="width: 60px;">S.No.</th>
              <th>Client Details</th>
              <th>Pet Profile</th>
              <th>Policy Info</th>
              <th>Policy Period</th>
              <th class="text-end">Premium</th>
              <th class="text-center">Status</th>
              <th class="text-center" style="width: 90px;">Actions</th>
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
                $coverageLower = strtolower($ins->coverage_type ?? 'standard');

                $policyJson = json_encode([
                    "policyNumber"   => $ins->policy_number ?? 'N/A',
                    "clientName"     => $ins->full_name ?? 'N/A',
                    "contact"        => $ins->phone ?? 'N/A',
                    "email"          => $ins->email ?? 'N/A',
                    "coverageType"   => $ins->coverage_type ?? 'Standard',
                    "startDate"      => $startDate,
                    "expiryDate"     => $endDate,
                    "premium"        => '₹' . $formattedAmount,
                    "status"         => 'Active',
                    "petName"        => $ins->name ?? 'N/A',
                    "breed"          => $ins->breed ?? 'N/A',
                    "age"            => $ins->dob ?? 'N/A',
                    "gender"         => $ins->gender ?? 'N/A',
                    "vaccination"    => $ins->last_vaccination ?? 'N/A',
                    "remarks"        => $ins->remarks ?? ''
                ]);
              ?>
                <tr class="policy-row"
                    data-coverage="<?= $coverageLower ?>"
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
                        <?php if (!empty($ins->remarks)): ?>
                          <div class="text-muted small mt-1" style="font-size: 11px; color: #6366f1 !important;">
                            <i class="bi bi-chat-left-text me-1"></i>
                            <?= htmlspecialchars(mb_substr($ins->remarks, 0, 45)) . (mb_strlen($ins->remarks) > 45 ? '...' : '') ?>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </td>

                  <td>
                    <div>
                      <div class="fw-semibold text-dark"><?= esc($ins->name ?? 'Insured Pet') ?></div>
                      <div class="text-muted small">
                        <span><?= esc($ins->breed ?? 'Breed N/A') ?></span>
                        <?php if (!empty($ins->gender)): ?>
                          <span class="mx-1">•</span><span><?= esc(ucfirst($ins->gender)) ?></span>
                        <?php endif; ?>
                      </div>
                    </div>
                  </td>

                  <td>
                    <div class="d-flex flex-column gap-1">
                      <span class="font-monospace fw-bold text-dark" style="font-size: 12px;">#<?= esc($ins->policy_number ?? 'N/A') ?></span>
                      <div class="badge-service" style="padding: 2px 8px; font-size: 10.5px;">
                        <i class="bi bi-shield-check"></i>
                        <span><?= esc($ins->coverage_type ?? 'Standard') ?></span>
                      </div>
                    </div>
                  </td>

                  <td>
                    <div class="d-flex flex-column" style="font-size: 12px;">
                      <div class="text-dark fw-medium">
                        <i class="bi bi-calendar-check text-success me-1"></i>
                        <span><?= $startDate ?></span>
                      </div>
                      <div class="text-muted mt-0.5">
                        <i class="bi bi-calendar-x text-muted me-1"></i>
                        <span>to <?= $endDate ?></span>
                      </div>
                    </div>
                  </td>

                  <td class="text-end">
                    <div class="fw-bold text-dark fs-6" style="color: #0f172a !important;">
                      ₹<?= $formattedAmount ?>
                    </div>
                  </td>

                  <td class="text-center">
                    <span class="status-pill status-active">
                      <span class="status-dot"></span>
                      <span>Active</span>
                    </span>
                  </td>

                  <td class="text-center">
                    <button type="button" class="nl-action-btn view" title="View Policy Details"
                      onclick="showPolicyDetails(<?= htmlspecialchars($policyJson, ENT_QUOTES, 'UTF-8') ?>)">
                      <i class="bi bi-eye"></i>
                    </button>
                  </td>
                </tr>
              <?php
                $sno++;
              endforeach;
              ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center py-5 text-muted">
                  <i class="bi bi-shield-slash fs-2 d-block mb-2 text-secondary"></i>
                  No active insurance policies found
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Modern Table Footer & Pagination -->
      <div class="d-flex flex-wrap justify-content-between align-items-center px-4 py-3 border-top" style="background: #fafafa;">
        <div class="text-muted small fw-medium" id="entriesInfo">
          Showing 1 to <?= min(10, $totalActive) ?> of <?= $totalActive ?> entries
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
<div class="modal fade" id="policyDetailsModal" tabindex="-1" aria-labelledby="policyDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden" style="max-height: 85vh;">
      <div class="modal-header modal-header-gradient">
        <div class="d-flex align-items-center gap-2.5">
          <div class="modal-icon-badge">
            <i class="bi bi-shield-check text-warning"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold text-white mb-0" id="policyDetailsModalLabel">Active Policy Certificate</h5>
            <span class="text-white-50 small" id="policyHeaderSub">Policy #PF-XXXXXX</span>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white opacity-75 hover-opacity-100" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4" style="overflow-y: auto; flex: 1;">
        <div class="row g-3">
          <div class="col-md-6">
            <div class="modal-info-card h-100">
              <h6 class="fw-bold text-dark mb-3"><i class="bi bi-person me-1.5 text-primary"></i>Policyholder Profile</h6>
              <div class="d-flex flex-column gap-2 small">
                <div class="d-flex justify-content-between"><span class="text-muted">Client Name:</span><span class="fw-semibold text-dark" id="modalPolicyClient"></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Phone Number:</span><span class="fw-semibold text-dark" id="modalPolicyPhone"></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Email Address:</span><span class="fw-semibold text-dark" id="modalPolicyEmail"></span></div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="modal-info-card h-100">
              <h6 class="fw-bold text-dark mb-3"><i class="bi bi-heart-pulse me-1.5 text-danger"></i>Insured Pet Profile</h6>
              <div class="d-flex flex-column gap-2 small">
                <div class="d-flex justify-content-between"><span class="text-muted">Pet Name:</span><span class="fw-semibold text-dark" id="modalPetName"></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Breed:</span><span class="fw-semibold text-dark" id="modalPetBreed"></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">DOB / Age:</span><span class="fw-semibold text-dark" id="modalPetAge"></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Gender:</span><span class="fw-semibold text-dark" id="modalPetGender"></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Vaccination:</span><span class="fw-semibold text-dark" id="modalPetVacc"></span></div>
              </div>
            </div>
          </div>

          <div class="col-12">
            <div class="modal-info-card">
              <h6 class="fw-bold text-dark mb-3"><i class="bi bi-shield-lock me-1.5 text-success"></i>Policy Term & Coverage Benefits</h6>
              <div class="row g-3 small">
                <div class="col-sm-3"><span class="text-muted d-block">Coverage Plan:</span><span class="fw-semibold text-dark" id="modalCoverageType"></span></div>
                <div class="col-sm-3"><span class="text-muted d-block">Start Date:</span><span class="fw-semibold text-dark" id="modalStartDate"></span></div>
                <div class="col-sm-3"><span class="text-muted d-block">Expiry Date:</span><span class="fw-semibold text-dark" id="modalExpiryDate"></span></div>
                <div class="col-sm-3"><span class="text-muted d-block">Annual Premium:</span><span class="fw-bold text-dark fs-6" id="modalPremium" style="color: #4f46e5 !important;"></span></div>
                <div class="col-12 pt-2 border-top" id="modalPolicyRemarksWrap" style="display: none;">
                  <span class="text-muted d-block">Policy Notes / Remarks:</span>
                  <span class="text-dark" id="modalPolicyRemarks"></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer border-top bg-light px-4 py-2.5 d-flex justify-content-between">
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
          Close
        </button>
        <button type="button" class="btn btn-sm btn-primary rounded-pill px-4 fw-semibold" onclick="window.print()">
          <i class="bi bi-printer me-1.5"></i>Print Certificate
        </button>
      </div>
    </div>
  </div>
</div>

<!-- 2. Add New Policy Wizard Modal -->
<div class="modal fade" id="addInsuranceModal" tabindex="-1" aria-labelledby="addInsuranceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden" style="max-height: 88vh;">
      <div class="modal-header modal-header-gradient">
        <div class="d-flex align-items-center gap-2.5">
          <div class="modal-icon-badge">
            <i class="bi bi-shield-plus text-warning"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold text-white mb-0" id="addInsuranceModalLabel">Issue Active Insurance Policy</h5>
            <span class="text-white-50 small">Direct policy issuance and quotation activation</span>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white opacity-75 hover-opacity-100" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4" style="overflow-y: auto; flex: 1;">
        <!-- Step 1: Search Client -->
        <div id="policySearchStep">
          <div class="modal-info-card mb-4">
            <label class="form-label fw-bold text-dark small text-uppercase mb-2">Search Registered Client by Phone</label>
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-telephone"></i></span>
              <input type="text" class="form-control border-start-0" id="policySearchPhone" placeholder="Enter 10-digit mobile number">
              <button class="btn btn-primary fw-semibold px-4" type="button" onclick="searchPolicyClient()">
                <i class="bi bi-search me-1"></i>Search
              </button>
            </div>
            <div id="policySearchFeedback" class="mt-2 small"></div>
          </div>
        </div>

        <!-- Step 1b: New Client Form -->
        <div id="policyNewClientSection" style="display: none;">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0 text-indigo"><i class="bi bi-person-plus me-1.5"></i>New Policyholder</h6>
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" onclick="backToPolicySearch()">
              <i class="bi bi-arrow-left me-1"></i>Back to Search
            </button>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">FULL NAME</label>
              <input type="text" class="form-control rounded-3" id="pol_new_name" placeholder="Client Name">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">EMAIL ADDRESS</label>
              <input type="email" class="form-control rounded-3" id="pol_new_email" placeholder="client@example.com">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">PHONE NUMBER</label>
              <input type="text" class="form-control rounded-3" id="pol_new_phone" placeholder="10-digit phone">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">ADDRESS</label>
              <textarea class="form-control rounded-3" id="pol_new_address" rows="1" placeholder="Address & City"></textarea>
            </div>
          </div>

          <h6 class="fw-bold mb-3 text-indigo mt-4"><i class="bi bi-heart-pulse me-1.5"></i>Pet Profile Details</h6>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">PET NAME</label>
              <input type="text" class="form-control rounded-3" id="pol_new_pet_name" placeholder="Pet Name">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">BREED</label>
              <select class="form-select rounded-3" id="pol_new_pet_breed"><option value="">Select Breed</option></select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">AGGRESSIVE</label>
              <select class="form-select rounded-3" id="pol_new_pet_aggressive"><option value="No">No</option><option value="Yes">Yes</option></select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">VACCINATED</label>
              <select class="form-select rounded-3" id="pol_new_pet_vaccinated"><option value="Yes">Yes</option><option value="No">No</option></select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold small text-muted">DOB</label>
              <input type="date" class="form-control rounded-3" id="pol_new_pet_dob">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold small text-muted">GENDER</label>
              <select class="form-select rounded-3" id="pol_new_pet_gender"><option value="Male">Male</option><option value="Female">Female</option></select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold small text-muted">LAST VACCINATION</label>
              <input type="date" class="form-control rounded-3" id="pol_new_pet_vacc">
            </div>
          </div>
          <hr class="my-4">
        </div>

        <!-- Step 2: Policy & Premium Form -->
        <form id="policyForm" style="display: none;">
          <input type="hidden" id="pol_foundClientId">
          <input type="hidden" id="pol_is_new_client" value="0">

          <div id="pol_existingClientDisplay">
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label fw-bold small text-muted">CLIENT NAME</label>
                <input type="text" class="form-control bg-light rounded-3" id="pol_displayClientName" readonly>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-bold small text-muted">CLIENT EMAIL</label>
                <input type="email" class="form-control bg-light rounded-3" id="pol_displayClientEmail" readonly>
                <input type="hidden" id="pol_displayClientPhone">
              </div>
            </div>

            <div class="row g-3 mb-3">
              <div class="col-md-12">
                <label class="form-label fw-bold small text-muted">SELECT PET</label>
                <select class="form-select rounded-3" id="pol_petSelect" required>
                  <option value="">Select a pet</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">COVERAGE TYPE</label>
              <select class="form-select rounded-3" id="pol_coverage_type" required>
                <option value="Comprehensive">Comprehensive Cover</option>
                <option value="Standard">Standard Cover</option>
                <option value="Basic">Basic Accidental Cover</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">ANNUAL PREMIUM (₹)</label>
              <div class="input-group">
                <span class="input-group-text bg-white">₹</span>
                <input type="number" class="form-control rounded-end-3" id="pol_premium" placeholder="e.g. 5999" required>
              </div>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <label class="form-label fw-bold small text-muted">DURATION (YEARS)</label>
              <select class="form-select rounded-3" id="pol_duration_years" onchange="calculatePolicyEndDate()">
                <option value="1">1 Year</option>
                <option value="2">2 Years</option>
                <option value="3">3 Years</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold small text-muted">START DATE</label>
              <input type="date" class="form-control rounded-3" id="pol_start_date" required onchange="calculatePolicyEndDate()">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold small text-muted">END DATE</label>
              <input type="date" class="form-control bg-light rounded-3" id="pol_end_date" readonly required>
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer border-top bg-light px-4 py-2.5 d-flex justify-content-between">
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
          Cancel
        </button>
        <button type="button" class="btn btn-sm btn-primary rounded-pill px-4 fw-semibold" id="submitPolicyBtn" style="display: none;" onclick="submitActivePolicy()">
          <i class="bi bi-shield-check me-1.5"></i>Issue Active Policy
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ==========================================
     JAVASCRIPT: Pagination & Actions
========================================== -->
<script>
class ActivePoliciesPaginationManager {
    constructor() {
        this.currentPage = 1;
        this.entriesPerPage = 10;
        this.allRows = Array.from(document.querySelectorAll('.policy-row'));
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
        const searchInput = document.getElementById('searchActivePolicies');
        if (searchInput) {
            searchInput.addEventListener('input', () => this.applyCombinedFilters());
        }

        // Coverage filter
        const coverageFilter = document.getElementById('coverageFilter');
        if (coverageFilter) {
            coverageFilter.addEventListener('change', () => this.applyCombinedFilters());
        }
    }

    getTotalPages() {
        return Math.ceil(this.filteredRows.length / this.entriesPerPage) || 1;
    }

    applyCombinedFilters() {
        const query = (document.getElementById('searchActivePolicies')?.value || '').toLowerCase().trim();
        const coverage = (document.getElementById('coverageFilter')?.value || 'all').toLowerCase().trim();

        this.filteredRows = this.allRows.filter(row => {
            const text = row.textContent.toLowerCase();
            const matchesQuery = !query || text.includes(query);
            const rowCoverage = (row.dataset.coverage || '').toLowerCase();
            const matchesCoverage = coverage === 'all' || rowCoverage === coverage || rowCoverage.includes(coverage);
            return matchesQuery && matchesCoverage;
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
    window.activePoliciesPagination = new ActivePoliciesPaginationManager();
});

// View Policy Details
function showPolicyDetails(policy) {
    if (typeof policy === 'string') {
        try { policy = JSON.parse(policy); } catch(e){}
    }

    document.getElementById('policyHeaderSub').textContent = `Policy #${policy.policyNumber || 'N/A'}`;
    document.getElementById('modalPolicyClient').textContent = policy.clientName || 'N/A';
    document.getElementById('modalPolicyPhone').textContent = policy.contact || 'N/A';
    document.getElementById('modalPolicyEmail').textContent = policy.email || 'N/A';

    document.getElementById('modalPetName').textContent = policy.petName || 'N/A';
    document.getElementById('modalPetBreed').textContent = policy.breed || 'N/A';
    document.getElementById('modalPetAge').textContent = policy.age || 'N/A';
    document.getElementById('modalPetGender').textContent = policy.gender || 'N/A';
    document.getElementById('modalPetVacc').textContent = policy.vaccination || 'N/A';

    document.getElementById('modalCoverageType').textContent = policy.coverageType || 'Comprehensive';
    document.getElementById('modalStartDate').textContent = policy.startDate || 'N/A';
    document.getElementById('modalExpiryDate').textContent = policy.expiryDate || 'N/A';
    document.getElementById('modalPremium').textContent = policy.premium || '₹0.00';

    if (policy.remarks) {
        document.getElementById('modalPolicyRemarks').textContent = policy.remarks;
        document.getElementById('modalPolicyRemarksWrap').style.display = 'block';
    } else {
        document.getElementById('modalPolicyRemarksWrap').style.display = 'none';
    }

    const modalEl = document.getElementById('policyDetailsModal');
    let bsModal = bootstrap.Modal.getInstance(modalEl);
    if (!bsModal) bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
}

// Add Policy Wizard Logic
function openAddInsuranceModal() {
    const modalEl = document.getElementById('addInsuranceModal');
    let bsModal = bootstrap.Modal.getInstance(modalEl);
    if (!bsModal) bsModal = new bootstrap.Modal(modalEl);

    const today = new Date().toISOString().split('T')[0];
    const startDateInput = document.getElementById('pol_start_date');
    if (startDateInput) {
        startDateInput.value = today;
        calculatePolicyEndDate();
    }

    bsModal.show();
}

function searchPolicyClient() {
    const phone = document.getElementById('policySearchPhone').value.trim();
    const feedback = document.getElementById('policySearchFeedback');

    if (!phone || phone.length < 10) {
        feedback.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-circle me-1"></i>Please enter a valid 10-digit phone number.</span>';
        return;
    }

    feedback.innerHTML = '<span class="text-muted"><i class="bi bi-arrow-repeat spin me-1"></i>Searching client database...</span>';

    fetch('<?= base_url('insurance/searchClient') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ phone: phone })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && data.client) {
            feedback.innerHTML = `<span class="text-success"><i class="bi bi-check-circle me-1"></i>Client found: <strong>${data.client.full_name}</strong></span>`;
            document.getElementById('pol_foundClientId').value = data.client.id;
            document.getElementById('pol_displayClientName').value = data.client.full_name;
            document.getElementById('pol_displayClientEmail').value = data.client.email || '';
            document.getElementById('pol_displayClientPhone').value = data.client.phone || '';
            document.getElementById('pol_is_new_client').value = '0';

            loadPolicyClientPets(data.client.id);
            document.getElementById('policyForm').style.display = 'block';
            document.getElementById('policyNewClientSection').style.display = 'none';
            document.getElementById('submitPolicyBtn').style.display = 'inline-flex';
        } else {
            feedback.innerHTML = `
                <div class="alert alert-warning py-2 px-3 small mt-2 mb-0 d-flex justify-content-between align-items-center">
                  <span>Client not registered with this phone.</span>
                  <button type="button" class="btn btn-sm btn-dark rounded-pill" onclick="showPolicyNewClientForm('${phone}')">
                    <i class="bi bi-plus me-1"></i>Create New Client
                  </button>
                </div>`;
        }
    });
}

function showPolicyNewClientForm(phone = '') {
    document.getElementById('policyNewClientSection').style.display = 'block';
    document.getElementById('policySearchStep').style.display = 'none';
    document.getElementById('policyForm').style.display = 'block';
    document.getElementById('pol_existingClientDisplay').style.display = 'none';
    document.getElementById('pol_is_new_client').value = '1';
    document.getElementById('pol_new_phone').value = phone;
    document.getElementById('submitPolicyBtn').style.display = 'inline-flex';
    loadPolicyDogBreeds();
}

function backToPolicySearch() {
    document.getElementById('policyNewClientSection').style.display = 'none';
    document.getElementById('policySearchStep').style.display = 'block';
    document.getElementById('policyForm').style.display = 'none';
    document.getElementById('pol_existingClientDisplay').style.display = 'block';
    document.getElementById('pol_is_new_client').value = '0';
    document.getElementById('submitPolicyBtn').style.display = 'none';
}

function loadPolicyDogBreeds() {
    const breedSelect = document.getElementById('pol_new_pet_breed');
    if (breedSelect.options.length > 1) return;

    fetch('<?= base_url('insurance/getDogBreeds') ?>')
    .then(r => r.json())
    .then(data => {
        if (data.success && data.breeds) {
            data.breeds.forEach(b => {
                const opt = document.createElement('option');
                opt.value = b.value;
                opt.textContent = b.value;
                breedSelect.appendChild(opt);
            });
        }
    });
}

function loadPolicyClientPets(clientId) {
    const petSelect = document.getElementById('pol_petSelect');
    petSelect.innerHTML = '<option value="">Loading pets...</option>';

    fetch('<?= base_url('insurance/getClientPets') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ client_id: clientId })
    })
    .then(r => r.json())
    .then(data => {
        petSelect.innerHTML = '<option value="">Select a pet</option>';
        if (data.success && data.pets && data.pets.length > 0) {
            data.pets.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = `${p.name} (${p.breed || 'Dog'})`;
                petSelect.appendChild(opt);
            });
        } else {
            petSelect.innerHTML = '<option value="">No registered pets found</option>';
        }
    });
}

function calculatePolicyEndDate() {
    const startDate = document.getElementById('pol_start_date').value;
    const duration = parseInt(document.getElementById('pol_duration_years').value) || 1;
    if (startDate) {
        const start = new Date(startDate);
        const end = new Date(start.setFullYear(start.getFullYear() + duration));
        document.getElementById('pol_end_date').value = end.toISOString().split('T')[0];
    }
}

function submitActivePolicy() {
    const isNew = document.getElementById('pol_is_new_client').value === '1';
    const premium = document.getElementById('pol_premium').value;

    if (!premium || parseFloat(premium) <= 0) {
        alert('Please enter a valid premium amount.');
        return;
    }

    const payload = {
        is_new_client: isNew ? 1 : 0,
        coverage_type: document.getElementById('pol_coverage_type').value,
        duration_years: document.getElementById('pol_duration_years').value,
        start_date: document.getElementById('pol_start_date').value,
        end_date: document.getElementById('pol_end_date').value,
        premium: premium
    };

    if (isNew) {
        payload.new_client_name = document.getElementById('pol_new_name').value.trim();
        payload.new_client_email = document.getElementById('pol_new_email').value.trim();
        payload.new_client_phone = document.getElementById('pol_new_phone').value.trim();
        payload.new_client_address = document.getElementById('pol_new_address').value.trim();
        payload.new_pet_name = document.getElementById('pol_new_pet_name').value.trim();
        payload.new_pet_breed = document.getElementById('pol_new_pet_breed').value;
        payload.new_pet_aggressive = document.getElementById('pol_new_pet_aggressive').value;
        payload.new_pet_vaccinated = document.getElementById('pol_new_pet_vaccinated').value;
        payload.new_pet_dob = document.getElementById('pol_new_pet_dob').value;
        payload.new_pet_gender = document.getElementById('pol_new_pet_gender').value;
        payload.new_pet_vaccination = document.getElementById('pol_new_pet_vacc').value;

        if (!payload.new_client_name || !payload.new_client_phone || !payload.new_pet_name) {
            alert('Please fill all required client and pet details.');
            return;
        }
    } else {
        payload.user_id = document.getElementById('pol_foundClientId').value;
        payload.full_name = document.getElementById('pol_displayClientName').value;
        payload.email = document.getElementById('pol_displayClientEmail').value;
        payload.phone = document.getElementById('pol_displayClientPhone').value;
        payload.pet_id = document.getElementById('pol_petSelect').value;

        if (!payload.pet_id) {
            alert('Please select a pet.');
            return;
        }
    }

    fetch('<?= base_url('insurance/addPolicy') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {
        if (data.status) {
            alert('Insurance policy issued successfully!');
            location.reload();
        } else {
            alert(data.message || 'Error issuing policy.');
        }
    });
}

function exportActivePoliciesToCSV() {
    const rows = Array.from(document.querySelectorAll('#activeTable tbody tr.policy-row'));
    if (!rows.length) {
        alert('No active policies to export.');
        return;
    }

    let csv = 'S.No,Policy Number,Client Name,Phone,Email,Pet Name,Breed,Coverage,Start Date,Expiry Date,Premium,Status\n';
    rows.forEach((r, idx) => {
        const d = JSON.parse(r.dataset.policy || '{}');
        csv += `"${idx + 1}","${d.policyNumber || ''}","${d.clientName || ''}","${d.contact || ''}","${d.email || ''}","${d.petName || ''}","${d.breed || ''}","${d.coverageType || ''}","${d.startDate || ''}","${d.expiryDate || ''}","${d.premium || ''}","${d.status || ''}"\n`;
    });

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `active_insurance_policies_${new Date().toISOString().split('T')[0]}.csv`;
    link.click();
}
</script>

<?php echo $this->endSection(); ?>