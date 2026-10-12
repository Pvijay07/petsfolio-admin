<?php echo $this->extend('admin/includes/header'); ?>
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

// Variables initialization
$page             = $page ?? 1;
$perPage          = $perPage ?? 25;
$totalFiltered    = $totalFiltered ?? ($totalRecordings ?? 0);
$totalPages       = $totalPages ?? 1;
$search           = $search ?? '';
$selectedType     = $selectedType ?? 'verification';
$selectedVerifier = $selectedVerifier ?? null;
$selectedStatus   = $selectedStatus ?? '';
$totalRecordings  = $totalRecordings ?? 0;
$completedCount   = $completedCount ?? 0;
$queuedCount      = $queuedCount ?? 0;
$failedCount      = $failedCount ?? 0;
$registrationCount= $registrationCount ?? 0;
$verificationCount= $verificationCount ?? 0;
$avgRating        = $avgRating ?? 0;
$avgRisk          = $avgRisk ?? 0;
$avgQa            = $avgQa ?? $avgRating;
$positivePercentage = $positivePercentage ?? 0;
$calls            = $calls ?? [];
$verifierStats    = $verifierStats ?? [];
$verifiers        = $verifiers ?? [];

// Helper closure for building page URLs with query parameters
$buildPageUrl = function($pageNum) use ($selectedType, $selectedVerifier, $selectedStatus, $search, $perPage) {
    $params = [
        'page'     => $pageNum,
        'per_page' => $perPage,
    ];
    if (!empty($selectedType)) $params['type'] = $selectedType;
    if (!empty($selectedVerifier)) $params['verifier_id'] = $selectedVerifier;
    if (!empty($selectedStatus)) $params['status'] = $selectedStatus;
    if (!empty($search)) $params['search'] = $search;
    return base_url('call-analysis?' . http_build_query($params));
};
?>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<style>
  /* Modern Dashboard UI Styles matching Bookings */
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
    margin-bottom: 24px;
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
    cursor: pointer;
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

  /* Badges */
  .badge-service {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 600;
  }
  .badge-service.verification { background: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe; }
  .badge-service.registration { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
  .badge-service.inbound { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
  .badge-service.outbound { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
  .badge-service.support { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

  .badge-urgency {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 9px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 600;
  }
  .badge-urgency.upcoming { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
  .badge-urgency.today { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; font-weight: 700; }
  .badge-urgency.expired { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

  /* Status Pills matching Bookings */
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
  .status-approved, .status-completed { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
  .status-approved .status-dot, .status-completed .status-dot { background: #10b981; }
  .status-re_verification, .status-pending { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
  .status-re_verification .status-dot, .status-pending .status-dot { background: #f59e0b; }
  .status-escalated, .status-rejected, .status-failed { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
  .status-escalated .status-dot, .status-rejected .status-dot, .status-failed .status-dot { background: #ef4444; }

  /* Action Buttons */
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
  }
  .nl-action-btn.view { color: #4f46e5; }
  .nl-action-btn.view:hover { background: #4f46e5; color: #ffffff; border-color: #4f46e5; }

  /* Pagination */
  .nl-page-btn {
    min-width: 34px;
    height: 34px;
    padding: 0 10px;
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

  /* Score Cards & Drawer */
  .score-card-mini {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px;
    text-align: center;
  }
  .score-card-mini .val {
    font-size: 1.4rem;
    font-weight: 800;
    color: #4f46e5;
  }
  .score-card-mini .lbl {
    font-size: 0.75rem;
    color: #64748b;
    text-transform: uppercase;
    font-weight: 600;
  }
  .transcript-container {
    max-height: 350px;
    overflow-y: auto;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px;
    font-family: inherit;
    white-space: pre-wrap;
  }
</style>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?php echo $this->include('admin/nav_bar') ?>
  </div>

  <div class="container-fluid px-4 content-inner pb-5" id="page_layout">

    <!-- Hero Banner with Stats matching Bookings UI -->
    <div class="nl-hero-banner">
      <div class="row align-items-center g-4">
        <div class="col-lg-6">
          <div class="nl-live-pill">
            <span class="nl-live-dot"></span>
            <span>Live Voice Intelligence & QA</span>
          </div>
          <h2 class="fw-bold text-white mb-2" style="font-size: 30px; letter-spacing: -0.02em;">Call Analysis & Verifier QA</h2>
          <p class="mb-0 text-white text-opacity-75" style="font-size: 13.5px; max-width: 560px; line-height: 1.55;">
            Automated Speech Recognition, Speaker Diarization, LLM Quality Scoring & Reviewer Performance Tracking.
          </p>
        </div>

        <div class="col-lg-6">
          <div class="d-flex align-items-center gap-2 flex-wrap justify-content-lg-end">
            <!-- Total Calls -->
            <div class="nl-stat-card">
              <span class="label">Total Calls</span>
              <div class="value"><?= number_format($totalRecordings) ?></div>
            </div>
            <!-- Verification Calls -->
            <div class="nl-stat-card">
              <span class="label">Verification</span>
              <div class="value" style="color: #93c5fd;"><?= number_format($verificationCount) ?></div>
            </div>
            <!-- Registration Calls -->
            <div class="nl-stat-card">
              <span class="label">Registration</span>
              <div class="value" style="color: #a7f3d0;"><?= number_format($registrationCount) ?></div>
            </div>
            <!-- Avg QA Rating -->
            <div class="nl-stat-card">
              <span class="label">Avg QA Rating</span>
              <div class="value" style="color: #fde047; font-size: 22px;">
                <?= number_format($avgQa ?? $avgRating, 1) ?><small style="font-size: 12px; font-weight: 500; opacity: 0.8;">/10</small>
              </div>
            </div>
            <!-- Avg Risk -->
            <div class="nl-stat-card">
              <span class="label">Avg Risk Level</span>
              <div class="value" style="color: <?= $avgRisk > 3 ? '#fca5a5' : '#6ee7b7' ?>; font-size: 22px;">
                <?= number_format($avgRisk, 1) ?><small style="font-size: 12px; font-weight: 500; opacity: 0.8;">/10</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Segmented Tabs & Action Bar matching Bookings -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
      <div class="nl-segmented-tabs">
        <a href="<?= base_url('call-analysis?type=verification') ?>" class="nl-tab-pill <?= ($selectedType === 'verification' || empty($selectedType)) ? 'active' : '' ?>">
          <i class="bi bi-shield-check"></i>
          <span>Verification Calls</span>
          <span class="nl-tab-badge"><?= number_format($verificationCount) ?></span>
        </a>
        <a href="<?= base_url('call-analysis?type=registration') ?>" class="nl-tab-pill <?= $selectedType === 'registration' ? 'active' : '' ?>">
          <i class="bi bi-person-plus-fill"></i>
          <span>Registration Calls</span>
          <span class="nl-tab-badge"><?= number_format($registrationCount) ?></span>
        </a>
        <a href="<?= base_url('call-analysis?type=all') ?>" class="nl-tab-pill <?= $selectedType === 'all' ? 'active' : '' ?>">
          <i class="bi bi-grid-fill"></i>
          <span>All Executive Calls</span>
          <span class="nl-tab-badge"><?= number_format($totalRecordings) ?></span>
        </a>
        <a href="<?= base_url('client-call-analysis') ?>" class="nl-tab-pill">
          <i class="bi bi-headset"></i>
          <span>Client-SP Calls</span>
        </a>
      </div>

      <div class="d-flex align-items-center gap-2">
        <button type="button" class="nl-btn-action" data-bs-toggle="modal" data-bs-target="#uploadRecordingModal">
          <i class="bi bi-cloud-arrow-up-fill text-primary"></i>
          <span>Upload Call Recording</span>
        </button>
        <button type="button" class="nl-btn-action" onclick="refreshVerifierStats()">
          <i class="bi bi-arrow-repeat"></i>
          <span>Refresh Metrics</span>
        </button>
      </div>
    </div>

    <!-- Modern Search and Filter Toolbar matching Bookings -->
    <div class="nl-filter-card">
      <form method="GET" action="<?= base_url('call-analysis') ?>" id="callFilterForm">
        <?php if (!empty($selectedType)): ?>
          <input type="hidden" name="type" value="<?= esc($selectedType) ?>">
        <?php endif; ?>
        <div class="row g-3 align-items-center">
          <div class="col-lg-4 col-md-5">
            <div class="nl-search-wrap">
              <i class="bi bi-search search-icon"></i>
              <input type="text" class="nl-search-input" name="search" id="searchInput"
                placeholder="Search provider, phone, verifier, call ID..." value="<?= esc($search ?? '') ?>">
            </div>
          </div>
          <div class="col-lg-3 col-md-3">
            <select class="nl-select w-100" name="verifier_id" id="verifierFilter" onchange="this.form.submit()">
              <option value="">All Verifiers / Executives</option>
              <?php foreach ($verifiers as $v): ?>
                <option value="<?= $v['id'] ?>" <?= ((string)$selectedVerifier === (string)$v['id']) ? 'selected' : '' ?>>
                  <?= esc($v['name']) ?> (<?= esc($v['role_name'] ?? 'Staff') ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-lg-2 col-md-2">
            <select class="nl-select w-100" name="status" id="statusFilter" onchange="this.form.submit()">
              <option value="">All Statuses</option>
              <option value="approved" <?= ($selectedStatus === 'approved') ? 'selected' : '' ?>>Approved</option>
              <option value="re_verification" <?= ($selectedStatus === 're_verification') ? 'selected' : '' ?>>Re-verification</option>
              <option value="escalated" <?= ($selectedStatus === 'escalated') ? 'selected' : '' ?>>Escalated</option>
              <option value="rejected" <?= ($selectedStatus === 'rejected') ? 'selected' : '' ?>>Rejected</option>
              <option value="pending" <?= ($selectedStatus === 'pending') ? 'selected' : '' ?>>Pending</option>
            </select>
          </div>
          <div class="col-lg-3 col-md-2">
            <div class="d-flex align-items-center justify-content-md-end gap-2">
              <label class="text-muted small mb-0 fw-semibold">Show</label>
              <select class="nl-select" style="width: 80px;" name="per_page" id="entriesPerPage" onchange="changePerPage(this.value)">
                <option value="15" <?= ($perPage == 15) ? 'selected' : '' ?>>15</option>
                <option value="25" <?= ($perPage == 25) ? 'selected' : '' ?>>25</option>
                <option value="50" <?= ($perPage == 50) ? 'selected' : '' ?>>50</option>
                <option value="100" <?= ($perPage == 100) ? 'selected' : '' ?>>100</option>
              </select>
              <label class="text-muted small mb-0 fw-semibold">entries</label>
              <?php if (!empty($search) || !empty($selectedVerifier) || !empty($selectedStatus)): ?>
                <a href="<?= base_url('call-analysis?type=' . esc($selectedType)) ?>" class="btn btn-sm btn-outline-danger ms-1" title="Clear Filters">
                  <i class="bi bi-x-circle"></i>
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </form>
    </div>

    <!-- Call Recordings Data Table Card -->
    <div class="nl-table-card">
      <div class="table-responsive">
        <table class="table nl-table mb-0" id="callRecordingsTable">
          <thead>
            <tr>
              <th class="text-center" style="width: 70px;">Call ID</th>
              <th>Service Provider</th>
              <th>Assigned Executive</th>
              <th class="text-center">Call Type</th>
              <th class="text-center">Duration</th>
              <th class="text-center">AI Rating</th>
              <th class="text-center">Status</th>
              <th class="text-center">QA Rating</th>
              <th class="text-center">Escalated</th>
              <th class="text-center" style="width: 80px;">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($calls)) : ?>
              <?php foreach ($calls as $call) :
                $isReg = !empty($call['is_register']);
                $providerDisplay = !empty($call['provider_name']) ? $call['provider_name'] : (!empty($call['provider_id']) ? 'Provider #' . $call['provider_id'] : 'Unknown Provider');
                $pAvatar = getInitialsAvatar($providerDisplay, $call['provider_id'] ?? $call['id']);
                
                $verifierDisplay = !empty($call['verifier_name']) ? $call['verifier_name'] : 'Unassigned';
                $vAvatar = getInitialsAvatar($verifierDisplay, $call['verifier_id'] ?? 0);

                $cDate = !empty($call['created_at']) && $call['created_at'] !== '0000-00-00 00:00:00' ? $call['created_at'] : ($call['updated_at'] ?? '');
                $cTs = !empty($cDate) ? strtotime($cDate) : 0;
                $dateFormatted = ($cTs && $cTs > 946684800) ? date('M d, Y', $cTs) : 'Recently';
                $timeFormatted = ($cTs && $cTs > 946684800) ? date('h:i A', $cTs) : '';

                $verSt = strtolower($call['verification_status'] ?? 'pending');
                $statusClass = in_array($verSt, ['approved', 'completed', 're_verification', 'escalated', 'rejected', 'pending']) ? $verSt : 'pending';
              ?>
                <tr onclick="openCallDetails(<?= (int)$call['id'] ?>)">
                  <!-- Call ID & Date -->
                  <td class="text-center align-middle">
                    <span class="fw-bold" style="color: #4f46e5; font-size: 13px;">#<?= $call['id'] ?></span>
                    <div class="text-muted small mt-0.5" style="font-size: 11px;">
                      <?= $dateFormatted ?>
                    </div>
                  </td>

                  <!-- Service Provider Column with Pastel Initials Avatar -->
                  <td class="align-middle">
                    <div class="d-flex align-items-center gap-3">
                      <div class="nl-avatar" style="background: <?= $pAvatar['bg'] ?>; color: <?= $pAvatar['color'] ?>;">
                        <?= $pAvatar['text'] ?>
                      </div>
                      <div>
                        <div class="fw-bold text-dark" style="font-size: 13.5px; line-height: 1.3;">
                          <?= htmlspecialchars($providerDisplay) ?>
                        </div>
                        <?php if (!empty($call['provider_phone'])) : ?>
                          <div class="d-flex align-items-center text-muted small mt-1 gap-1" style="font-size: 11.5px;">
                            <i class="bi bi-telephone text-secondary"></i>
                            <a href="tel:<?= $call['provider_phone'] ?>" class="text-decoration-none text-muted" onclick="event.stopPropagation();">
                              <?= $call['provider_phone'] ?>
                            </a>
                          </div>
                        <?php elseif (!empty($call['provider_id'])) : ?>
                          <div class="text-muted small mt-0.5" style="font-size: 11px;">
                            ID: #<?= esc($call['provider_id']) ?>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </td>

                  <!-- Assigned Executive Column -->
                  <td class="align-middle">
                    <div class="d-flex align-items-center gap-2">
                      <div class="nl-avatar" style="width: 32px; height: 32px; font-size: 11px; background: <?= $vAvatar['bg'] ?>; color: <?= $vAvatar['color'] ?>;">
                        <?= $vAvatar['text'] ?>
                      </div>
                      <div>
                        <div class="fw-bold text-dark" style="font-size: 13px;">
                          <?= esc($verifierDisplay) ?>
                        </div>
                        <span class="badge bg-soft-secondary text-dark px-2" style="font-size: 10px; font-weight: 600;">
                          <?= esc($call['role_badge'] ?? 'Executive') ?>
                        </span>
                      </div>
                    </div>
                  </td>

                  <!-- Call Type -->
                  <td class="align-middle text-center">
                    <?php if ($isReg) : ?>
                      <span class="badge-service registration">
                        <i class="bi bi-person-plus"></i>
                        Registration
                      </span>
                    <?php else : ?>
                      <span class="badge-service verification">
                        <i class="bi bi-shield-check"></i>
                        Verification
                      </span>
                    <?php endif; ?>
                  </td>

                  <!-- Duration Column -->
                  <td class="align-middle text-center">
                    <?php if (!empty($call['formatted_duration']) && $call['formatted_duration'] !== '-') : ?>
                      <span class="badge-urgency upcoming">
                        <i class="bi bi-clock me-0.5"></i>
                        <?= esc($call['formatted_duration']) ?>
                      </span>
                    <?php elseif (!empty($call['duration']) && (int)$call['duration'] > 0) : ?>
                      <span class="badge-urgency upcoming">
                        <i class="bi bi-clock me-0.5"></i>
                        <?= esc($call['duration']) ?>s
                      </span>
                    <?php else : ?>
                      <span class="text-muted small">-</span>
                    <?php endif; ?>
                  </td>

                  <!-- Overall AI Score -->
                  <td class="align-middle text-center">
                    <?php if (isset($call['overall_rating']) && $call['overall_rating'] > 0) : ?>
                      <span class="badge bg-soft-primary text-primary fw-bold" style="font-size: 12.5px; padding: 4px 10px; border-radius: 50px;">
                        ⭐ <?= number_format($call['overall_rating'], 1) ?>
                      </span>
                    <?php else : ?>
                      <span class="text-muted small">N/A</span>
                    <?php endif; ?>
                  </td>

                  <!-- Status Column with Dot matching Bookings -->
                  <td class="align-middle text-center">
                    <span class="status-pill status-<?= $statusClass ?>">
                      <span class="status-dot"></span>
                      <span><?= ucfirst(str_replace('_', ' ', $verSt)) ?></span>
                    </span>
                  </td>

                  <!-- QA Score -->
                  <td class="align-middle text-center">
                    <span class="badge bg-soft-info text-info fw-bold" style="font-size: 12px; padding: 4px 9px; border-radius: 50px;">
                      <?= number_format($call['qa_score'] ?? $call['overall_rating'] ?? 0, 1) ?> <small>/10</small>
                    </span>
                  </td>

                  <!-- Escalated Column -->
                  <td class="align-middle text-center">
                    <?php if (!empty($call['is_escalated'])) : ?>
                      <span class="badge bg-danger text-white rounded-pill px-2.5 py-1" style="font-size: 11px;">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Escalated
                      </span>
                    <?php else : ?>
                      <span class="badge bg-soft-success text-success rounded-pill px-2.5 py-1" style="font-size: 11px;">
                        <i class="bi bi-check-circle-fill me-1"></i> Normal
                      </span>
                    <?php endif; ?>
                  </td>

                  <!-- Action Column -->
                  <td class="align-middle text-center" onclick="event.stopPropagation();">
                    <button type="button" class="nl-action-btn view" onclick="openCallDetails(<?= (int)$call['id'] ?>)" title="View AI Call Evaluation">
                      <i class="bi bi-eye"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else : ?>
              <tr>
                <td colspan="10" class="text-center py-5 text-muted">
                  <div class="py-4">
                    <i class="bi bi-headset fs-1 text-secondary opacity-50 d-block mb-2"></i>
                    <h6 class="text-dark fw-bold mb-1">No Call Recordings Found</h6>
                    <p class="text-muted small mb-3">No executive call recordings match your current filters. Upload a call audio recording to start AI analysis.</p>
                    <button type="button" class="nl-btn-action" data-bs-toggle="modal" data-bs-target="#uploadRecordingModal">
                      <i class="bi bi-cloud-arrow-up-fill text-primary"></i>
                      <span>Upload New Call Recording</span>
                    </button>
                  </div>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Modern Table Footer & Pagination matching Bookings -->
      <div class="d-flex flex-wrap justify-content-between align-items-center px-4 py-3 border-top" style="background: #fafafa;">
        <div class="text-muted small fw-medium" id="entriesInfo">
          <?php if (!empty($totalFiltered) && $totalFiltered > 0) : ?>
            Showing <strong><?= number_format(($page - 1) * $perPage + 1) ?></strong> to <strong><?= number_format(min($totalFiltered, $page * $perPage)) ?></strong> of <strong><?= number_format($totalFiltered) ?></strong> call recordings
          <?php else : ?>
            Showing 0 call recordings
          <?php endif; ?>
        </div>

        <div class="d-flex align-items-center gap-1" id="paginationControls">
          <?php if ($totalPages > 1) : ?>
            <!-- Prev Button -->
            <a href="<?= $page > 1 ? $buildPageUrl($page - 1) : '#' ?>" class="nl-page-btn nav-btn <?= $page <= 1 ? 'disabled' : '' ?>" title="Previous Page">
              <i class="bi bi-chevron-left"></i>
            </a>

            <!-- Page Numbers -->
            <?php
            $startP = max(1, $page - 2);
            $endP   = min($totalPages, $page + 2);
            if ($startP > 1) {
                echo '<a href="' . $buildPageUrl(1) . '" class="nl-page-btn">1</a>';
                if ($startP > 2) echo '<span class="nl-page-btn disabled">...</span>';
            }
            for ($p = $startP; $p <= $endP; $p++) {
                $activeClass = ($p == $page) ? 'active' : '';
                echo '<a href="' . $buildPageUrl($p) . '" class="nl-page-btn ' . $activeClass . '">' . $p . '</a>';
            }
            if ($endP < $totalPages) {
                if ($endP < $totalPages - 1) echo '<span class="nl-page-btn disabled">...</span>';
                echo '<a href="' . $buildPageUrl($totalPages) . '" class="nl-page-btn">' . $totalPages . '</a>';
            }
            ?>

            <!-- Next Button -->
            <a href="<?= $page < $totalPages ? $buildPageUrl($page + 1) : '#' ?>" class="nl-page-btn nav-btn <?= $page >= $totalPages ? 'disabled' : '' ?>" title="Next Page">
              <i class="bi bi-chevron-right"></i>
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Reviewer Performance Tracking & Quality Section -->
    <div class="nl-table-card">
      <div class="p-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2" style="background: #ffffff;">
        <div>
          <h5 class="fw-bold mb-1 text-dark" style="font-size: 17px; letter-spacing: -0.01em;">
            <i class="bi bi-award text-primary me-2"></i> Reviewer Performance & Verifier Quality Audit
          </h5>
          <p class="text-muted small mb-0">Track verification calls handled, average call duration, QA rating, approval & escalation metrics per verifier.</p>
        </div>
        <button class="nl-btn-action" onclick="refreshVerifierStats()">
          <i class="bi bi-arrow-repeat text-primary"></i>
          <span>Refresh Metrics</span>
        </button>
      </div>

      <div class="table-responsive">
        <table class="table nl-table mb-0" id="verifierPerformanceTable">
          <thead>
            <tr>
              <th>Verifier Name & Role</th>
              <th class="text-center">Calls Completed</th>
              <th class="text-center">Avg Duration</th>
              <th class="text-center">Avg Verif. Score</th>
              <th class="text-center">Approval Rate</th>
              <th class="text-center">Re-verif. Rate</th>
              <th class="text-center">QA Rating</th>
              <th class="text-center">Escalation Rate</th>
              <th class="text-center">Complaint Rate</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($verifierStats)) : ?>
              <?php foreach ($verifierStats as $v) :
                $vInit = getInitialsAvatar($v['verifier_name'] ?? 'Verifier', $v['verifier_id'] ?? 0);
              ?>
                <tr>
                  <td class="align-middle">
                    <div class="d-flex align-items-center gap-3">
                      <div class="nl-avatar" style="background: <?= $vInit['bg'] ?>; color: <?= $vInit['color'] ?>;">
                        <?= $vInit['text'] ?>
                      </div>
                      <div>
                        <div class="fw-bold text-dark" style="font-size: 13.5px;">
                          <?= esc($v['verifier_name']) ?>
                        </div>
                        <div class="d-flex align-items-center gap-2 mt-0.5">
                          <span class="badge bg-soft-primary text-primary px-2 py-0.5" style="font-size: 10.5px;">
                            Verification Executive
                          </span>
                          <?php if (!empty($v['verifier_email'])) : ?>
                            <span class="text-muted small" style="font-size: 11px;"><?= esc($v['verifier_email']) ?></span>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="text-center align-middle">
                    <span class="badge bg-soft-primary text-primary px-3 py-1.5 rounded-pill fw-bold" style="font-size: 12px;">
                      <?= number_format($v['calls_completed']) ?> calls
                    </span>
                  </td>
                  <td class="text-center align-middle fw-semibold" style="font-size: 12.5px;">
                    <i class="bi bi-clock text-muted me-1"></i> <?= esc($v['formatted_duration']) ?>
                  </td>
                  <td class="text-center align-middle">
                    <span class="badge bg-soft-success text-success fw-bold px-2.5 py-1" style="font-size: 12px; border-radius: 50px;">
                      ⭐ <?= number_format($v['avg_verification_score'], 1) ?> / 10
                    </span>
                  </td>
                  <td class="text-center align-middle">
                    <span class="badge bg-success text-white px-3 py-1.5 rounded-pill fw-bold" style="font-size: 11.5px;">
                      <?= number_format($v['approval_rate'], 1) ?>%
                    </span>
                  </td>
                  <td class="text-center align-middle">
                    <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold" style="font-size: 11.5px;">
                      <?= number_format($v['re_verification_rate'], 1) ?>%
                    </span>
                  </td>
                  <td class="text-center align-middle">
                    <span class="badge bg-info text-white px-3 py-1.5 rounded-pill fw-bold" style="font-size: 11.5px;">
                      <?= number_format($v['avg_qa_rating'], 1) ?> / 10
                    </span>
                  </td>
                  <td class="text-center align-middle">
                    <?php if ($v['escalation_rate'] > 15) : ?>
                      <span class="badge bg-danger text-white px-3 py-1.5 rounded-pill fw-bold" style="font-size: 11.5px;">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> <?= number_format($v['escalation_rate'], 1) ?>%
                      </span>
                    <?php else : ?>
                      <span class="badge bg-soft-secondary text-dark px-3 py-1.5 rounded-pill" style="font-size: 11.5px;">
                        <?= number_format($v['escalation_rate'], 1) ?>%
                      </span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center align-middle">
                    <?php if ($v['customer_complaint_rate'] > 5) : ?>
                      <span class="badge bg-danger text-white px-3 py-1.5 rounded-pill fw-bold" style="font-size: 11.5px;">
                        <i class="bi bi-megaphone-fill me-1"></i> <?= number_format($v['customer_complaint_rate'], 1) ?>%
                      </span>
                    <?php else : ?>
                      <span class="badge bg-soft-success text-success px-3 py-1.5 rounded-pill" style="font-size: 11.5px;">
                        <?= number_format($v['customer_complaint_rate'], 1) ?>%
                      </span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else : ?>
              <tr>
                <td colspan="9" class="text-center py-5 text-muted">
                  <i class="bi bi-info-circle fs-3 text-secondary d-block mb-1"></i>
                  No executive call verification metrics logged yet. Upload or assign verifiers to call recordings to view reviewer quality scores.
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Analytics Charts Row matching Bookings Cards -->
    <div class="row g-3 mb-4">
      <div class="col-12 col-lg-8">
        <div class="nl-table-card h-100 p-4" style="margin-bottom: 0;">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <h5 class="fw-bold mb-0 text-dark" style="font-size: 16px;">
                <i class="bi bi-graph-up text-primary me-2"></i> Daily Average Rating Trend
              </h5>
              <small class="text-muted">Last 14 Days (Executive Calls)</small>
            </div>
            <span class="badge bg-soft-primary text-primary px-2.5 py-1 rounded-pill" style="font-size: 11px;">Performance Trend</span>
          </div>
          <div>
            <canvas id="dailyRatingChart" style="max-height: 270px;"></canvas>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-4">
        <div class="nl-table-card h-100 p-4" style="margin-bottom: 0;">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <h5 class="fw-bold mb-0 text-dark" style="font-size: 16px;">
                <i class="bi bi-pie-chart text-primary me-2"></i> Customer Sentiment
              </h5>
              <small class="text-muted">Voice Mood Breakdown</small>
            </div>
          </div>
          <div class="d-flex align-items-center justify-content-center pt-2">
            <canvas id="sentimentChart" style="max-height: 250px;"></canvas>
          </div>
        </div>
      </div>
    </div>

  </div>
</main>

<!-- Upload Recording Modal matching Modern Dashboard -->
<div class="modal fade" id="uploadRecordingModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
      <div class="modal-header px-4 py-3 border-0 text-white" style="background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 25%, #3730a3 60%, #4f46e5 100%) !important;">
        <div class="d-flex align-items-center gap-2.5">
          <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: rgba(255, 255, 255, 0.15);">
            <i class="bi bi-cloud-arrow-up text-warning fs-5"></i>
          </div>
          <div>
            <h5 class="modal-title text-white fw-bold mb-0" style="font-size: 17px;">Upload Call Recording</h5>
            <small class="text-white text-opacity-75" style="font-size: 12px;">Transcribe, diarize speakers, and score executive quality</small>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="uploadRecordingForm" enctype="multipart/form-data">
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label fw-bold small text-dark">Select Audio File (.mp3, .wav, .m4a, .ogg)</label>
            <input type="file" name="recording" class="form-control nl-search-input" accept="audio/*" required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold text-dark">Assigned Executive / Verifier</label>
            <select name="verifier_id" class="nl-select w-100" id="modalVerifierSelect">
              <option value="">-- Select Executive / Verifier --</option>
              <?php
              $regExecs  = array_filter($verifiers ?? [], fn($v) => ($v['role_id'] ?? 0) == 13 || str_contains(strtolower($v['role_name'] ?? ''), 'regist'));
              $verifExecs = array_filter($verifiers ?? [], fn($v) => ($v['role_id'] ?? 0) == 10 || str_contains(strtolower($v['role_name'] ?? ''), 'verif'));
              $otherExecs = array_filter($verifiers ?? [], fn($v) => ($v['role_id'] ?? 0) != 13 && ($v['role_id'] ?? 0) != 10 && !str_contains(strtolower($v['role_name'] ?? ''), 'regist') && !str_contains(strtolower($v['role_name'] ?? ''), 'verif'));
              ?>
              <?php if (!empty($regExecs)) : ?>
                <optgroup label="📋 Registration Executives (Role 13)">
                  <?php foreach ($regExecs as $v) : ?>
                    <option value="<?= $v['id'] ?>" data-role="13"><?= esc($v['name']) ?> (<?= esc($v['email']) ?>)</option>
                  <?php endforeach; ?>
                </optgroup>
              <?php endif; ?>
              <?php if (!empty($verifExecs)) : ?>
                <optgroup label="✅ Verification Executives (Role 10)">
                  <?php foreach ($verifExecs as $v) : ?>
                    <option value="<?= $v['id'] ?>" data-role="10"><?= esc($v['name']) ?> (<?= esc($v['email']) ?>)</option>
                  <?php endforeach; ?>
                </optgroup>
              <?php endif; ?>
              <?php if (!empty($otherExecs)) : ?>
                <optgroup label="👤 Other Staff & Admins">
                  <?php foreach ($otherExecs as $v) : ?>
                    <option value="<?= $v['id'] ?>" data-role="<?= $v['role_id'] ?? 0 ?>"><?= esc($v['name']) ?> (<?= esc($v['role_name'] ?? 'Staff') ?>)</option>
                  <?php endforeach; ?>
                </optgroup>
              <?php endif; ?>
            </select>
          </div>
          <div class="row g-3">
            <div class="col-6">
              <label class="form-label small fw-semibold text-dark">Service Provider ID (Optional)</label>
              <input type="number" name="provider_id" class="form-control nl-search-input" placeholder="e.g. 101">
            </div>
            <div class="col-6">
              <label class="form-label small fw-semibold text-dark">Booking / Service ID (Optional)</label>
              <input type="number" name="booking_id" class="form-control nl-search-input" placeholder="e.g. 450">
            </div>
          </div>
          <div class="mt-3">
            <label class="form-label small fw-semibold text-dark">Call Classification Type</label>
            <select name="call_type" class="nl-select w-100" id="modalCallTypeSelect">
              <option value="verification">Provider Verification Call (Verification Executive)</option>
              <option value="registration">Provider Registration Call (Registration Executive)</option>
              <option value="inbound">Inbound Provider Support Call</option>
              <option value="outbound">Outbound Provider Follow-up</option>
              <option value="support">Support Dispute Call</option>
            </select>
          </div>
          <div id="uploadAlert" class="mt-3" style="display:none;"></div>
        </div>
        <div class="modal-footer bg-light px-4 py-3 border-top">
          <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-sm btn-primary px-4 fw-semibold" id="uploadSubmitBtn" style="background: #4f46e5; border-color: #4f46e5;">
            <i class="bi bi-cloud-arrow-up me-1"></i> Start Upload & Queue AI
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Details & QA Audit Offcanvas Drawer matching Modern Dashboard -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="callDetailsOffcanvas" style="width: 700px;">
  <div class="offcanvas-header px-4 py-3 text-white" style="background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 25%, #3730a3 60%, #4f46e5 100%) !important;">
    <div class="d-flex align-items-center gap-2.5">
      <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(255, 255, 255, 0.15);">
        <i class="bi bi-robot text-warning fs-5"></i>
      </div>
      <div>
        <h5 class="offcanvas-title text-white fw-bold mb-0" id="drawerTitle" style="font-size: 16px;">Call Evaluation & Verifier Quality Audit</h5>
        <small class="text-white text-opacity-75" style="font-size: 11.5px;">Detailed speech diagnostics, rubrics, and supervisor audit</small>
      </div>
    </div>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>

  <div class="offcanvas-body p-4">
    <div id="drawerLoading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status"></div>
      <p class="text-muted mt-2 small fw-semibold">Loading AI Analysis & Reviewer Payload...</p>
    </div>

    <div id="drawerContent" style="display:none;">
      <!-- Audio Player Box -->
      <div class="p-3 border rounded-3 bg-light mb-4" id="audioPlayerContainer">
        <label class="fw-bold mb-2 small text-uppercase text-muted d-flex align-items-center gap-1">
          <i class="bi bi-play-circle-fill text-primary"></i> Audio Playback
        </label>
        <audio id="drawerAudio" controls class="w-100"></audio>
      </div>

      <!-- Executive & Provider Summary Banner -->
      <div class="p-3 border rounded-3 bg-white shadow-sm mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
          <span class="text-muted small d-block">Service Provider</span>
          <div class="fw-bold text-dark fs-6" id="drawerProviderName">-</div>
        </div>
        <div>
          <span class="text-muted small d-block">Executive Role</span>
          <div><span class="badge bg-soft-primary text-primary fw-bold px-2 py-1" id="drawerExecutiveRole">-</span></div>
        </div>
        <div>
          <span class="text-muted small d-block">Classification</span>
          <div><span class="badge bg-soft-info text-info fw-bold px-2 py-1" id="drawerCallType">-</span></div>
        </div>
      </div>

      <!-- Supervisor Quality Audit Form Box -->
      <div class="card border mb-4 shadow-sm rounded-3 overflow-hidden" style="border-color: #e2e8f0 !important;">
        <div class="card-header bg-soft-primary fw-bold text-primary py-2.5 px-3 small d-flex justify-content-between align-items-center">
          <span><i class="bi bi-shield-check me-1"></i> QA Audit & Verifier Decision</span>
          <span id="verifierNameBadge" class="badge bg-primary">Verifier: Unassigned</span>
        </div>
        <div class="card-body p-3">
          <form id="qaAuditForm">
            <input type="hidden" id="auditRecordingId" name="recording_id">
            <div class="row g-2 align-items-center">
              <div class="col-md-6">
                <label class="form-label small fw-bold mb-1">Verification Status</label>
                <select id="auditVerificationStatus" name="verification_status" class="nl-select w-100">
                  <option value="approved">Approved</option>
                  <option value="re_verification">Re-verification Required</option>
                  <option value="escalated">Escalated to Supervisor</option>
                  <option value="rejected">Rejected</option>
                  <option value="pending">Pending</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-bold mb-1">QA Score (0.0 to 10.0)</label>
                <input type="number" step="0.1" max="10" min="0" id="auditQaScore" name="qa_score" class="form-control nl-search-input" placeholder="e.g. 9.5">
              </div>
              <div class="col-md-6 mt-2">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="auditIsEscalated" name="is_escalated" value="1">
                  <label class="form-check-label small fw-semibold" for="auditIsEscalated">Escalated Call</label>
                </div>
              </div>
              <div class="col-md-6 mt-2">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="auditHasComplaint" name="has_complaint" value="1">
                  <label class="form-check-label small fw-semibold" for="auditHasComplaint">Customer Complaint Logged</label>
                </div>
              </div>
              <div class="col-12 mt-3 text-end">
                <button type="submit" class="btn btn-sm btn-primary px-3" id="saveAuditBtn" style="background: #4f46e5; border-color: #4f46e5;">
                  <i class="bi bi-save me-1"></i> Update Audit Score
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Abuse / Scolding Alert -->
      <div id="abuseAlertContainer" style="display:none;">
        <div class="alert border-0 rounded-3 mb-4 shadow-sm" style="background: rgba(220, 38, 38, 0.08); border-left: 4px solid #dc2626 !important;">
          <h6 class="fw-bold mb-2 text-danger"><i class="bi bi-exclamation-octagon-fill me-2"></i> ⚠️ Abuse / Scolding / Negative Speech Detected</h6>
          <div id="abuseInstancesList" class="mb-2"></div>
          <div id="negativeFlagsList"></div>
        </div>
      </div>

      <!-- Overall Score Badge with Abuse Penalty -->
      <div class="text-center mb-4">
        <div class="d-inline-block p-3 rounded-3 shadow-sm" id="overallScoreBox" style="min-width: 180px;">
          <div class="text-muted small fw-semibold text-uppercase mb-1">Overall Score</div>
          <div class="fw-extrabold" id="detOverallScore" style="font-size: 2.2rem; line-height: 1;">-</div>
          <div class="text-muted small">/ 10</div>
          <div id="abusePenaltyBadge" style="display:none;" class="mt-1">
            <span class="badge bg-danger text-white small"><i class="bi bi-slash-circle me-1"></i>Abuse Penalty Applied</span>
          </div>
        </div>
      </div>

      <!-- Criteria Scores Grid -->
      <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-bar-chart-line-fill me-2 text-primary"></i> Evaluation Breakdown</h6>
      <div class="row g-2 mb-4">
        <div class="col-3">
          <div class="score-card-mini" id="commCard">
            <div class="val" id="detComm">-</div>
            <div class="lbl">Comm.</div>
          </div>
        </div>
        <div class="col-3">
          <div class="score-card-mini" id="profCard">
            <div class="val" id="detProf">-</div>
            <div class="lbl">Prof.</div>
          </div>
        </div>
        <div class="col-3">
          <div class="score-card-mini" id="empCard">
            <div class="val" id="detEmp">-</div>
            <div class="lbl">Empathy</div>
          </div>
        </div>
        <div class="col-3">
          <div class="score-card-mini" id="compCard">
            <div class="val" id="detComp">-</div>
            <div class="lbl">Compl.</div>
          </div>
        </div>
      </div>

      <!-- Executive Summary Box -->
      <div class="alert alert-primary border-0 rounded-3 mb-4" style="background: rgba(79, 70, 229, 0.06);">
        <h6 class="fw-bold mb-2 text-primary"><i class="bi bi-file-text me-2"></i> Executive Summary</h6>
        <p class="mb-0 text-dark small" id="detSummary">-</p>
      </div>

      <!-- Verification Question & Answer Audit Checklist -->
      <div class="card border-0 shadow-sm rounded-3 mb-4" id="qaChecklistCard">
        <div class="card-header bg-soft-primary border-0 py-3 px-3 d-flex justify-content-between align-items-center">
          <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-check2-square me-2"></i> Verification Question & Answer Audit</h6>
          <div class="d-flex gap-2 align-items-center">
            <span id="mandatoryStatusBadge" class="badge rounded-pill px-3 py-1" style="display:none;"></span>
            <span id="qaCoverageBadge" class="badge bg-primary rounded-pill">Coverage: 0%</span>
          </div>
        </div>
        <div class="card-body p-3">
          <!-- Out of Box Alert -->
          <div id="outOfBoxAlertContainer" class="alert alert-warning border-0 small mb-3" style="display:none;">
            <strong class="text-dark"><i class="bi bi-exclamation-triangle me-1 text-warning"></i> Out-of-Box Segments Flagged:</strong>
            <div id="outOfBoxList" class="mt-1"></div>
          </div>

          <!-- Mandatory Questions Missing Alert -->
          <div id="mandatoryQuestionsAlert" class="alert border-0 small mb-3" style="display:none;">
            <div id="mandatoryQuestionsContent"></div>
          </div>

          <!-- Question Table -->
          <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.85rem;">
              <thead class="bg-light">
                <tr>
                  <th>Question & Category</th>
                  <th class="text-center">Status</th>
                  <th>Answer / Audit Note</th>
                </tr>
              </thead>
              <tbody id="qaChecklistBody">
                <tr>
                  <td colspan="3" class="text-center text-muted py-3">No question audit data available</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- AI Strengths & Weaknesses -->
      <div class="row g-3 mb-4">
        <div class="col-6">
          <div class="p-3 border rounded-3 bg-soft-success h-100">
            <h6 class="fw-bold text-success mb-2 small"><i class="bi bi-check-circle me-1"></i> Key Strengths</h6>
            <ul class="mb-0 ps-3 small text-dark" id="detStrengths"></ul>
          </div>
        </div>
        <div class="col-6">
          <div class="p-3 border rounded-3 bg-soft-warning h-100">
            <h6 class="fw-bold text-warning mb-2 small"><i class="bi bi-exclamation-circle me-1"></i> Areas for Coaching</h6>
            <ul class="mb-0 ps-3 small text-dark" id="detWeaknesses"></ul>
          </div>
        </div>
      </div>

      <!-- Recommendations -->
      <div class="mb-4">
        <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-lightbulb me-2 text-primary"></i> Actionable AI Recommendations</h6>
        <ul class="list-group list-group-flush border rounded-3" id="detRecommendations"></ul>
      </div>

      <!-- Extracted Keywords -->
      <div class="mb-4">
        <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-tags me-2 text-primary"></i> Extracted Keywords</h6>
        <div id="detKeywords" class="d-flex flex-wrap gap-1"></div>
      </div>

      <!-- Full Transcript -->
      <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-chat-left-quote me-2 text-primary"></i> Transcript</h6>
      <div class="transcript-container" id="detTranscript">No transcript generated.</div>
    </div>
  </div>
</div>

<script>
  let dailyChart, sentimentChart;

  $(document.ready ? document : window).ready(function() {
    loadAnalyticsCharts();

    $('#uploadRecordingForm').on('submit', function(e) {
      e.preventDefault();
      let formData = new FormData(this);
      let btn = $('#uploadSubmitBtn');
      btn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i> Uploading...');

      $.ajax({
        url: '<?= base_url('call-analysis/upload') ?>',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(resp) {
          btn.prop('disabled', false).html('<i class="bi bi-cloud-arrow-up me-1"></i> Start Upload & Queue AI');
          if (resp.status) {
            $('#uploadAlert').removeClass().addClass('alert alert-success').text(resp.message).show();
            setTimeout(function() {
              location.reload();
            }, 1500);
          } else {
            $('#uploadAlert').removeClass().addClass('alert alert-danger').text(resp.message).show();
          }
        },
        error: function() {
          btn.prop('disabled', false).html('<i class="bi bi-cloud-arrow-up me-1"></i> Start Upload & Queue AI');
          $('#uploadAlert').removeClass().addClass('alert alert-danger').text('Server error occurred during file upload.').show();
        }
      });
    });

    $('#qaAuditForm').on('submit', function(e) {
      e.preventDefault();
      let btn = $('#saveAuditBtn');
      btn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i> Saving...');

      $.ajax({
        url: '<?= base_url('call-analysis/save-verifier-audit') ?>',
        type: 'POST',
        data: $(this).serialize(),
        success: function(resp) {
          btn.prop('disabled', false).html('<i class="bi bi-save me-1"></i> Update Audit Score');
          if (resp.status) {
            alert('Verifier audit details updated successfully.');
            location.reload();
          } else {
            alert('Error: ' + resp.message);
          }
        },
        error: function() {
          btn.prop('disabled', false).html('<i class="bi bi-save me-1"></i> Update Audit Score');
          alert('Server error saving QA audit.');
        }
      });
    });

    $('#modalVerifierSelect').on('change', function() {
      let selectedOpt = $(this).find(':selected');
      let role = selectedOpt.data('role');
      if (parseInt(role) === 13) {
        $('#modalCallTypeSelect').val('registration');
      } else if (parseInt(role) === 10) {
        $('#modalCallTypeSelect').val('verification');
      }
    });

    // Fast client-side row filtering when typing in search input
    $('#searchInput').on('keyup', function(e) {
      if (e.key === 'Enter') {
        $('#callFilterForm').submit();
        return;
      }
      let value = $(this).val().toLowerCase();
      $('#callRecordingsTable tbody tr').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
      });
    });
  });

  function changePerPage(val) {
    let url = new URL(window.location.href);
    url.searchParams.set('per_page', val);
    url.searchParams.set('page', '1');
    window.location.href = url.toString();
  }

  function refreshVerifierStats() {
    $.getJSON('<?= base_url('call-analysis/verifiers') ?>', function(resp) {
      if (resp.status) {
        location.reload();
      }
    });
  }

  function loadAnalyticsCharts() {
    $.getJSON('<?= base_url('call-analysis/analytics') ?>', function(resp) {
      if (!resp.status) return;

      // 1. Daily Rating Line Chart
      const dailyLabels = resp.dailyRatings.map(d => d.call_date);
      const dailyData = resp.dailyRatings.map(d => parseFloat(d.avg_rating));

      const ctxDaily = document.getElementById('dailyRatingChart').getContext('2d');
      dailyChart = new Chart(ctxDaily, {
        type: 'line',
        data: {
          labels: dailyLabels.length ? dailyLabels : ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5'],
          datasets: [{
            label: 'Average Score',
            data: dailyData.length ? dailyData : [8.5, 8.8, 9.0, 8.9, 9.2],
            borderColor: '#4f46e5',
            backgroundColor: 'rgba(79, 70, 229, 0.1)',
            fill: true,
            tension: 0.3
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              min: 0,
              max: 10
            }
          }
        }
      });

      // 2. Sentiment Doughnut Chart
      let sentLabels = resp.sentimentStats.map(s => s.customer_sentiment);
      let sentCounts = resp.sentimentStats.map(s => parseInt(s.total));

      const ctxSent = document.getElementById('sentimentChart').getContext('2d');
      sentimentChart = new Chart(ctxSent, {
        type: 'doughnut',
        data: {
          labels: sentLabels.length ? sentLabels : ['Positive', 'Neutral', 'Negative'],
          datasets: [{
            data: sentCounts.length ? sentCounts : [75, 20, 5],
            backgroundColor: ['#10b981', '#f59e0b', '#ef4444']
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom'
            }
          }
        }
      });
    });
  }

  function openCallDetails(id) {
    let offcanvasEl = document.getElementById('callDetailsOffcanvas');
    let offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl) || new bootstrap.Offcanvas(offcanvasEl);
    $('#drawerTitle').text('Call Evaluation #' + id);
    $('#drawerLoading').show();
    $('#drawerContent').hide();
    offcanvas.show();

    $.getJSON('<?= base_url('call-analysis/details/') ?>' + id, function(resp) {
      $('#drawerLoading').hide();
      if (!resp.status) return;

      let d = resp.data;
      $('#auditRecordingId').val(d.recording_id || d.id);
      $('#verifierNameBadge').text('Verifier: ' + (d.verifier_name || 'Unassigned'));

      // Populate Provider & Role metadata in drawer
      let pName = d.provider_name ? (d.provider_name + (d.provider_phone ? ' (' + d.provider_phone + ')' : '')) : (d.provider_id ? 'Provider #' + d.provider_id : 'N/A');
      $('#drawerProviderName').text(pName);
      $('#drawerExecutiveRole').text(d.role_badge || 'Executive');
      $('#drawerCallType').text(d.display_type || d.call_type || 'Verification');

      // Fill Audit Form
      $('#auditVerificationStatus').val(d.verification_status || 'approved');
      $('#auditQaScore').val(d.qa_score !== null && d.qa_score !== undefined ? d.qa_score : (d.overall_rating || 5.0));
      $('#auditIsEscalated').prop('checked', parseInt(d.is_escalated) === 1);
      $('#auditHasComplaint').prop('checked', parseInt(d.has_complaint) === 1);

      // Abuse / Negative Speech Alert
      let hasAbuse = parseInt(d.has_abuse) === 1;
      let abuseInstances = d.abuse_instances || [];
      let negativeFlags = d.negative_speech_flags || [];

      if (hasAbuse || abuseInstances.length > 0 || negativeFlags.length > 0) {
        let abuseHtml = '';
        if (abuseInstances.length > 0) {
          abuseHtml += '<div class="mb-1"><strong class="text-danger small"><i class="bi bi-exclamation-triangle-fill me-1"></i> Abusive / Scolding Phrases:</strong></div>';
          abuseInstances.forEach(a => {
            abuseHtml += `<span class="badge bg-danger text-white me-1 mb-1 px-2 py-1"><i class="bi bi-slash-circle me-1"></i>${a}</span>`;
          });
        }
        $('#abuseInstancesList').html(abuseHtml);

        let negHtml = '';
        if (negativeFlags.length > 0) {
          negHtml += '<div class="mb-1 mt-2"><strong class="text-warning small"><i class="bi bi-volume-up-fill me-1"></i> Negative Speech Indicators:</strong></div>';
          negativeFlags.forEach(n => {
            negHtml += `<span class="badge bg-warning text-dark me-1 mb-1 px-2 py-1">${n}</span>`;
          });
        }
        $('#negativeFlagsList').html(negHtml);
        $('#abuseAlertContainer').show();
      } else {
        $('#abuseAlertContainer').hide();
      }

      // Overall Score with Color & Abuse Penalty
      let overallScore = parseFloat(d.overall_rating) || 0;
      $('#detOverallScore').text(overallScore.toFixed(1));

      let scoreBoxBg, scoreColor;
      if (overallScore <= 4) {
        scoreBoxBg = 'rgba(220, 38, 38, 0.08)';
        scoreColor = '#dc2626';
      } else if (overallScore <= 6) {
        scoreBoxBg = 'rgba(245, 158, 11, 0.08)';
        scoreColor = '#d97706';
      } else {
        scoreBoxBg = 'rgba(16, 185, 129, 0.08)';
        scoreColor = '#059669';
      }
      $('#overallScoreBox').css({
        background: scoreBoxBg,
        border: `2px solid ${scoreColor}`
      });
      $('#detOverallScore').css('color', scoreColor);

      if (hasAbuse) {
        $('#abusePenaltyBadge').show();
      } else {
        $('#abusePenaltyBadge').hide();
      }

      // Strict Color-Coded Score Cards
      function applyScoreColor(cardId, valId, score) {
        let el = $(`#${valId}`);
        let card = $(`#${cardId}`);
        el.text(score || '-');
        let s = parseInt(score) || 0;
        if (s <= 4) {
          el.css('color', '#dc2626');
          card.css({
            'border-color': '#fca5a5',
            'background': 'rgba(220, 38, 38, 0.04)'
          });
        } else if (s <= 6) {
          el.css('color', '#d97706');
          card.css({
            'border-color': '#fcd34d',
            'background': 'rgba(245, 158, 11, 0.04)'
          });
        } else {
          el.css('color', '#059669');
          card.css({
            'border-color': '#6ee7b7',
            'background': '#f8fafc'
          });
        }
      }

      applyScoreColor('commCard', 'detComm', d.communication);
      applyScoreColor('profCard', 'detProf', d.professionalism);
      applyScoreColor('empCard', 'detEmp', d.empathy);
      applyScoreColor('compCard', 'detComp', d.compliance);

      // Summary
      $('#detSummary').text(d.summary || 'Summary pending processing.');

      // Audio
      if (d.recording_url) {
        let audioSrc = d.recording_url;
        if (!audioSrc.startsWith('http://') && !audioSrc.startsWith('https://')) {
          audioSrc = '<?= base_url() ?>' + audioSrc.replace(/^\//, '');
        }
        $('#drawerAudio').attr('src', audioSrc);
        $('#audioPlayerContainer').show();
      } else {
        $('#audioPlayerContainer').hide();
      }

      // Verification QA Checklist
      let qaData = d.verification_qa || null;
      if (qaData && qaData.qa_checklist && qaData.qa_checklist.length) {
        $('#qaCoverageBadge').text('Coverage: ' + (qaData.question_coverage_pct || 0) + '%');

        let mandatoryBadge = $('#mandatoryStatusBadge');
        if (qaData.all_mandatory_asked) {
          mandatoryBadge.text('✅ All Required Questions Asked')
            .removeClass('bg-danger bg-warning')
            .addClass('bg-success text-white')
            .show();
        } else {
          mandatoryBadge.text('⚠️ Required Questions Missing')
            .removeClass('bg-success bg-warning')
            .addClass('bg-danger text-white')
            .show();
        }

        let missedQuestions = qaData.missed_mandatory_questions || [];
        if (missedQuestions.length > 0) {
          let mqHtml = '<strong class="text-danger"><i class="bi bi-exclamation-circle me-1"></i> Required Questions Missing (' + missedQuestions.length + '):</strong>';
          mqHtml += '<ul class="mb-0 mt-1 ps-3">';
          missedQuestions.forEach(mq => {
            mqHtml += '<li class="text-danger fw-semibold">' + mq.question + ' <span class="text-muted fw-normal">(' + mq.category + ')</span></li>';
          });
          mqHtml += '</ul>';
          $('#mandatoryQuestionsContent').html(mqHtml);
          $('#mandatoryQuestionsAlert')
            .removeClass('alert-success')
            .addClass('alert-danger')
            .css('background', 'rgba(220, 38, 38, 0.06)')
            .show();
        } else {
          let allOkHtml = '<strong class="text-success"><i class="bi bi-check-circle me-1"></i> All Required Questions Asked</strong>';
          allOkHtml += '<div class="text-muted mt-1">The executive covered all mandatory verification questions during this call.</div>';
          $('#mandatoryQuestionsContent').html(allOkHtml);
          $('#mandatoryQuestionsAlert')
            .removeClass('alert-danger')
            .addClass('alert-success')
            .css('background', 'rgba(5, 150, 105, 0.06)')
            .show();
        }

        let qaRows = '';
        qaData.qa_checklist.forEach(q => {
          let badgeClass = q.status_color === 'success' ? 'bg-success' : (q.status_color === 'danger' ? 'bg-danger' : 'bg-warning text-dark');
          let rowBg = '';
          if (q.status_color === 'danger') {
            rowBg = 'style="background-color: rgba(220, 38, 38, 0.05);"';
          } else if (q.critical && q.status === 'Not Asked') {
            rowBg = 'style="background-color: rgba(220, 38, 38, 0.08); border-left: 3px solid #dc2626;"';
            badgeClass = 'bg-danger';
          }

          qaRows += `<tr ${rowBg}>
            <td>
              <div class="fw-bold text-dark">${q.question}</div>
              <small class="text-muted"><i class="bi bi-folder me-1"></i>${q.category} ${q.critical ? '<span class="badge bg-soft-danger text-danger ms-1">Required</span>' : ''}</small>
            </td>
            <td class="text-center">
              <span class="badge ${badgeClass} px-2 py-1">${q.status}</span>
            </td>
            <td>
              ${q.detected_a ? `<div class="small fw-semibold text-dark">Ans: "${q.detected_a}"</div>` : ''}
              <div class="small ${q.status_color === 'danger' ? 'text-danger fw-bold' : (q.critical && q.status === 'Not Asked' ? 'text-danger fw-bold' : 'text-muted')}">${q.critical && q.status === 'Not Asked' ? '⚠️ ' + q.note : q.note}</div>
            </td>
          </tr>`;
        });
        $('#qaChecklistBody').html(qaRows);

        if (qaData.out_of_box_segments && qaData.out_of_box_segments.length) {
          let oobHtml = '<ul class="mb-0 ps-3">';
          qaData.out_of_box_segments.forEach(o => {
            oobHtml += `<li class="text-danger fw-bold">${o.timestamp} - ${o.note}</li>`;
          });
          oobHtml += '</ul>';
          $('#outOfBoxList').html(oobHtml);
          $('#outOfBoxAlertContainer').show();
        } else {
          $('#outOfBoxAlertContainer').hide();
        }
        $('#qaChecklistCard').show();
      } else {
        $('#qaChecklistCard').hide();
        $('#mandatoryStatusBadge').hide();
        $('#mandatoryQuestionsAlert').hide();
      }

      // Strengths
      let strHtml = '';
      if (d.strengths && d.strengths.length) {
        d.strengths.forEach(s => strHtml += `<li>${s}</li>`);
      } else {
        strHtml = '<li class="text-muted">None recorded</li>';
      }
      $('#detStrengths').html(strHtml);

      // Weaknesses
      let wkHtml = '';
      if (d.weaknesses && d.weaknesses.length) {
        d.weaknesses.forEach(w => {
          if (w.includes('⚠️') || w.includes('CRITICAL') || w.includes('Abusive') || w.includes('Scolding') || w.includes('Threatening') || w.includes('Mismatched')) {
            wkHtml += `<li class="text-danger fw-bold">${w}</li>`;
          } else {
            wkHtml += `<li>${w}</li>`;
          }
        });
      } else {
        wkHtml = '<li class="text-muted">None recorded</li>';
      }
      $('#detWeaknesses').html(wkHtml);

      // Recommendations
      let recHtml = '';
      if (d.recommendations && d.recommendations.length) {
        d.recommendations.forEach(r => {
          let iconClass = 'text-primary';
          let textClass = 'text-dark';
          if (r.includes('⚠️') || r.includes('MANDATORY') || r.includes('disciplinary') || r.includes('Re-verify')) {
            iconClass = 'text-danger';
            textClass = 'text-danger fw-bold';
          }
          recHtml += `<li class="list-group-item bg-transparent ${textClass} small"><i class="bi bi-arrow-right ${iconClass} me-2"></i>${r}</li>`;
        });
      } else {
        recHtml = '<li class="list-group-item bg-transparent text-muted small">No action items needed</li>';
      }
      $('#detRecommendations').html(recHtml);

      // Keywords
      let kwHtml = '';
      if (d.keywords && d.keywords.length) {
        d.keywords.forEach(k => {
          kwHtml += `<span class="badge bg-soft-primary text-primary">${k.keyword} (${k.frequency})</span>`;
        });
      }
      $('#detKeywords').html(kwHtml || '<span class="text-muted small">N/A</span>');

      // Transcript
      $('#detTranscript').text(d.transcript || 'Transcript generation in progress.');

      $('#drawerContent').show();
    });
  }
</script>

<?php echo $this->include('admin/footer') ?>
<?php echo $this->endSection() ?>