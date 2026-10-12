<?php echo $this->extend('admin/includes/header'); ?>
<?php echo $this->section('content'); ?>

<style>
  :root {
    --al-primary: #4f46e5;
    --al-primary-dark: #3730a3;
    --al-slate-900: #0f172a;
    --al-slate-800: #1e293b;
    --al-slate-700: #334155;
  }

  .font-10 {
    font-size: 10px !important;
  }

  .font-11 {
    font-size: 11px !important;
  }

  .font-12 {
    font-size: 12px !important;
  }

  .font-13 {
    font-size: 13px !important;
  }

  .font-14 {
    font-size: 14px !important;
  }

  .font-15 {
    font-size: 15px !important;
  }

  .font-16 {
    font-size: 16px !important;
  }

  .tracking-wider {
    letter-spacing: 0.05em;
  }

  .cursor-pointer {
    cursor: pointer;
  }

  .transition-all {
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
  }

  /* Hero Section styling */
  .hero-audit-banner {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 45%, #312e81 100%);
    border: none;
  }

  .pulse-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 0 rgba(34, 197, 94, 0.4);
    animation: alPulse 2s infinite;
  }

  @keyframes alPulse {
    0% {
      box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
    }

    70% {
      box-shadow: 0 0 0 8px rgba(34, 197, 94, 0);
    }

    100% {
      box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
    }
  }

  .btn-hover-scale {
    transition: transform 0.15s ease, box-shadow 0.15s ease;
  }

  .btn-hover-scale:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
  }

  /* Stat Cards */
  .stat-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: 1px solid rgba(226, 232, 240, 0.9) !important;
  }

  .stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 24px -4px rgba(15, 23, 42, 0.08) !important;
  }

  .stat-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
  }

  /* Quick filter segmented pills */
  .segmented-filter-bar {
    background: #f1f5f9;
    padding: 5px;
    border-radius: 50rem;
    display: inline-flex;
    gap: 4px;
    flex-wrap: wrap;
  }

  .segmented-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 16px;
    border-radius: 50rem;
    font-size: 13px;
    font-weight: 500;
    color: #475569;
    border: none;
    background: transparent;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
  }

  .segmented-pill:hover {
    color: #0f172a;
    background: rgba(255, 255, 255, 0.6);
  }

  .segmented-pill.active {
    background: #ffffff;
    color: #4338ca;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  }

  .segmented-pill .pill-counter {
    border-radius: 50rem;
    padding: 1px 7px;
    font-size: 11px;
    font-weight: 700;
  }

  .segmented-pill.active .pill-counter {
    background: #e0e7ff;
    color: #4338ca;
  }

  .segmented-pill:not(.active) .pill-counter {
    background: #e2e8f0;
    color: #64748b;
  }

  /* Table styling matching Petsfolio High-end theme */
  #auditLogsTable thead th {
    background-color: #f8fafc !important;
    color: #475569 !important;
    font-weight: 700 !important;
    font-size: 11.5px !important;
    letter-spacing: 0.05em !important;
    text-transform: uppercase !important;
    border-bottom: 2px solid #e2e8f0 !important;
    padding: 14px 16px !important;
    white-space: nowrap;
  }

  #auditLogsTable tbody td {
    padding: 14px 16px !important;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    background: #ffffff;
  }

  #auditLogsTable tbody tr:hover td {
    background-color: #f8fafc !important;
  }

  /* User Avatar Initial circle */
  .user-avatar-circle {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 13px;
    flex-shrink: 0;
  }

  /* HTTP Method Pill */
  .method-badge {
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.05em;
    padding: 2px 7px;
    border-radius: 5px;
    display: inline-block;
  }

  .method-get {
    background: #e0f2fe;
    color: #0284c7;
  }

  .method-post {
    background: #dcfce7;
    color: #16a34a;
  }

  .method-put,
  .method-patch {
    background: #fef3c7;
    color: #d97706;
  }

  .method-delete {
    background: #fee2e2;
    color: #dc2626;
  }

  /* Action Pill Badges */
  .action-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 50rem;
    font-size: 11.5px;
    font-weight: 600;
    line-height: 1;
  }

  /* Modern Pagination Buttons */
  .pagination-pill-btn {
    min-width: 34px;
    height: 34px;
    padding: 0 10px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12.5px;
    font-weight: 600;
    color: #475569;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    cursor: pointer;
    transition: all 0.15s ease;
    text-decoration: none;
  }

  .pagination-pill-btn:hover:not(:disabled) {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #0f172a;
  }

  .pagination-pill-btn.active {
    background: #4f46e5;
    border-color: #4f46e5;
    color: #ffffff;
  }

  .pagination-pill-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
  }

  /* Modal code styling */
  .code-preview-box {
    background: #0f172a;
    color: #38bdf8;
    border-radius: 12px;
    padding: 16px;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    font-size: 12px;
    max-height: 280px;
    overflow-y: auto;
    border: 1px solid #1e293b;
    white-space: pre-wrap;
    word-break: break-word;
  }

  .detail-info-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px;
    transition: all 0.2s ease;
  }

  .detail-info-card:hover {
    background: #ffffff;
    border-color: #cbd5e1;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
  }

  /* Loading Spinner Overlay */
  #tableLoadingOverlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 10;
    pointer-events: none;
  }
  #tableLoadingOverlay.active {
    display: flex !important;
    pointer-events: auto;
  }
</style>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?php echo $this->include('admin/nav_bar') ?>
  </div>

  <div class="container-fluid content-inner pb-5 px-3 px-md-4" id="page_layout">

    <!-- Hero Header Banner Section -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden hero-audit-banner">
      <div class="card-body p-4 p-md-5 text-white position-relative">
        <div class="position-relative z-2">
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
              <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-10 border border-white border-opacity-15 mb-3" style="backdrop-filter: blur(8px);">
                <span class="pulse-indicator bg-success"></span>
                <span class="text-uppercase fw-bold font-11 tracking-wider text-light">Real-Time Security &amp; Activity Audit</span>
              </div>
              <h2 class="fw-bold text-white mb-2 d-flex align-items-center gap-3">
                <i class="bi bi-shield-check text-success fs-2"></i>
                Activity &amp; Audit Trail
              </h2>
              <p class="text-white-75 mb-0 font-14" style="max-width: 780px; line-height: 1.6;">
                Continuous compliance telemetry recording admin and employee activities: user logins, secure sessions, page access, record mutations, and destructive cancellations across the Petsfolio platform.
              </p>
            </div>

            <div class="d-flex flex-wrap gap-2 align-items-center">
              <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3 py-2 d-flex align-items-center gap-2 shadow-sm font-13 btn-hover-scale" data-bs-toggle="modal" data-bs-target="#auditInfoModal">
                <i class="bi bi-info-circle"></i>
                <span>How It Works</span>
              </button>
              <button type="button" onclick="exportAuditTableToCSV()" class="btn btn-light btn-sm rounded-pill px-3 py-2 d-flex align-items-center gap-2 shadow-sm text-dark fw-semibold font-13 btn-hover-scale">
                <i class="bi bi-download text-primary"></i>
                <span>Export CSV</span>
              </button>
              <button type="button" onclick="refreshAuditFeed(true)" id="btnRefreshFeed" class="btn btn-primary btn-sm rounded-pill px-3 py-2 d-flex align-items-center gap-2 shadow-sm font-13 btn-hover-scale">
                <i class="bi bi-arrow-clockwise"></i>
                <span>Refresh Feed</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Watermark Icon in background -->
        <i class="bi bi-fingerprint position-absolute end-0 bottom-0 opacity-10 text-white"
          style="font-size: 15rem; transform: translate(15%, 30%) rotate(-10deg); pointer-events: none;"></i>
      </div>
    </div>

    <!-- Summary KPI Cards (Clickable quick filters) -->
    <div class="row g-3 g-md-4 mb-4">

      <!-- Total Logged Activities -->
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card rounded-4 h-100 border-start border-4 border-primary cursor-pointer bg-white" onclick="setQuickTab('')">
          <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="text-muted fw-semibold small text-uppercase tracking-wider">Total Recorded</span>
              <div class="stat-icon bg-primary-subtle text-primary d-flex align-items-center justify-content-center">
                <i class="bi bi-activity fs-4"></i>
              </div>
            </div>
            <h3 class="fw-bold text-dark mb-1" id="metric-total"><?= number_format($metrics['total_logs'] ?? 0) ?></h3>
            <div class="d-flex align-items-center text-muted small">
              <span class="badge bg-primary bg-opacity-10 text-primary me-2 font-11 rounded-pill px-2">Live Stream</span>
              <span>All user actions</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Logins & Auth -->
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card rounded-4 h-100 border-start border-4 border-success cursor-pointer bg-white" onclick="setQuickTab('login')">
          <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="text-muted fw-semibold small text-uppercase tracking-wider">Logins Today</span>
              <div class="stat-icon bg-success-subtle text-success d-flex align-items-center justify-content-center">
                <i class="bi bi-box-arrow-in-right fs-4"></i>
              </div>
            </div>
            <h3 class="fw-bold text-dark mb-1" id="metric-logins"><?= number_format($metrics['logins_today'] ?? 0) ?></h3>
            <div class="d-flex align-items-center text-muted small">
              <span class="badge bg-success bg-opacity-10 text-success me-2 font-11 rounded-pill px-2">Authenticated</span>
              <span>User sessions started</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Updates & Edits -->
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card rounded-4 h-100 border-start border-4 border-warning cursor-pointer bg-white" onclick="setQuickTab('update')">
          <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="text-muted fw-semibold small text-uppercase tracking-wider">Updates &amp; Edits</span>
              <div class="stat-icon bg-warning-subtle text-warning d-flex align-items-center justify-content-center">
                <i class="bi bi-pencil-square fs-4"></i>
              </div>
            </div>
            <h3 class="fw-bold text-dark mb-1" id="metric-updates"><?= number_format($metrics['updates_count'] ?? 0) ?></h3>
            <div class="d-flex align-items-center text-muted small">
              <span class="badge bg-warning bg-opacity-10 text-warning me-2 font-11 rounded-pill px-2">Data Changes</span>
              <span>Form edits &amp; status updates</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Deletions & Purges -->
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card rounded-4 h-100 border-start border-4 border-danger cursor-pointer bg-white" onclick="setQuickTab('delete')">
          <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="text-muted fw-semibold small text-uppercase tracking-wider">Deletions &amp; Cancels</span>
              <div class="stat-icon bg-danger-subtle text-danger d-flex align-items-center justify-content-center">
                <i class="bi bi-trash3-fill fs-4"></i>
              </div>
            </div>
            <h3 class="fw-bold text-dark mb-1" id="metric-deletes"><?= number_format($metrics['deletes_count'] ?? 0) ?></h3>
            <div class="d-flex align-items-center text-muted small">
              <span class="badge bg-danger bg-opacity-10 text-danger me-2 font-11 rounded-pill px-2">High Severity</span>
              <span>Removed or purged records</span>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- Main Data Table & Filtering Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white overflow-hidden">

      <!-- Segmented Quick Pills Header -->
      <div class="card-header bg-white pt-4 pb-3 px-4 border-bottom">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
          <div class="segmented-filter-bar">
            <button type="button" class="segmented-pill active" data-action="" onclick="handleSegmentedTab(this, '')">
              <i class="bi bi-grid-fill"></i>
              <span>All Events</span>
              <span class="pill-counter" id="pill-all-count"><?= number_format($metrics['total_logs'] ?? 0) ?></span>
            </button>
            <button type="button" class="segmented-pill" data-action="login" onclick="handleSegmentedTab(this, 'login')">
              <i class="bi bi-box-arrow-in-right text-success"></i>
              <span>Logins</span>
            </button>
            <button type="button" class="segmented-pill" data-action="update" onclick="handleSegmentedTab(this, 'update')">
              <i class="bi bi-pencil-square text-warning"></i>
              <span>Updates</span>
            </button>
            <button type="button" class="segmented-pill" data-action="delete" onclick="handleSegmentedTab(this, 'delete')">
              <i class="bi bi-trash3 text-danger"></i>
              <span>Deletions</span>
            </button>
            <button type="button" class="segmented-pill" data-action="view" onclick="handleSegmentedTab(this, 'view')">
              <i class="bi bi-eye text-primary"></i>
              <span>Page Views</span>
            </button>
          </div>

          <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-2 px-3 d-flex align-items-center gap-1 font-12" onclick="resetAllFilters()">
              <i class="bi bi-arrow-counterclockwise"></i>
              <span>Reset Filters</span>
            </button>
          </div>
        </div>

        <!-- Filter Controls Row -->
        <div class="row g-2 align-items-center">

          <!-- Keyword Search -->
          <div class="col-12 col-md-4 col-xl-3">
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0 text-muted ps-3 rounded-start-pill">
                <i class="bi bi-search"></i>
              </span>
              <input type="text" id="filterSearch" class="form-control bg-light border-start-0 ps-1 rounded-end-pill font-13"
                placeholder="Search user, action, IP, URL..." oninput="debounceFetchLogs()">
            </div>
          </div>

          <!-- Actor / User Dropdown -->
          <div class="col-6 col-md-3 col-xl-2">
            <select id="filterUser" class="form-select form-select-sm rounded-pill py-2 px-3 border-secondary-subtle font-13" onchange="fetchLogs(1)">
              <option value="">All Users</option>
              <?php if (!empty($actors)): ?>
                <?php foreach ($actors as $actor): ?>
                  <?php
                  $aId = is_object($actor) ? ($actor->actor_id ?? '') : (is_array($actor) ? ($actor['actor_id'] ?? '') : '');
                  $aName = is_object($actor) ? ($actor->user_name ?? '') : (is_array($actor) ? ($actor['user_name'] ?? '') : '');
                  ?>
                  <?php if (!empty($aId)): ?>
                    <option value="<?= esc($aId) ?>"><?= esc($aName ?: 'User') ?> (ID #<?= esc($aId) ?>)</option>
                  <?php endif; ?>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>

          <!-- Action Dropdown -->
          <div class="col-6 col-md-2 col-xl-2">
            <select id="filterAction" class="form-select form-select-sm rounded-pill py-2 px-3 border-secondary-subtle font-13" onchange="syncActionSelect(this.value); fetchLogs(1)">
              <option value="">All Actions</option>
              <option value="login">Login</option>
              <option value="logout">Logout</option>
              <option value="view">Page View</option>
              <option value="create">Create</option>
              <option value="update">Update / Edit</option>
              <option value="delete">Delete / Cancel</option>
            </select>
          </div>

          <!-- Module / Entity Dropdown -->
          <div class="col-6 col-md-3 col-xl-2">
            <select id="filterModule" class="form-select form-select-sm rounded-pill py-2 px-3 border-secondary-subtle font-13" onchange="fetchLogs(1)">
              <option value="">All Modules</option>
              <?php if (!empty($entityTypes)): ?>
                <?php foreach ($entityTypes as $et): ?>
                  <?php $etName = is_object($et) ? ($et->entity_type ?? '') : (is_array($et) ? ($et['entity_type'] ?? '') : (string) $et); ?>
                  <?php if (!empty($etName)): ?>
                    <option value="<?= esc($etName) ?>"><?= esc(ucwords(str_replace(['_', '-'], ' ', $etName))) ?></option>
                  <?php endif; ?>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>

          <!-- Date Range Pickers -->
          <div class="col-6 col-md-12 col-xl-3">
            <div class="d-flex align-items-center gap-1">
              <input type="date" id="filterDateFrom" class="form-control form-control-sm rounded-pill py-2 px-2 border-secondary-subtle font-12" title="From Date" onchange="fetchLogs(1)">
              <span class="text-muted font-11">to</span>
              <input type="date" id="filterDateTo" class="form-control form-control-sm rounded-pill py-2 px-2 border-secondary-subtle font-12" title="To Date" onchange="fetchLogs(1)">
            </div>
          </div>

        </div>

        <!-- Table Count Indicator & Active Filter Pill -->
        <div class="d-flex align-items-center justify-content-between pt-3 mt-3 border-top font-13 text-muted">
          <div>
            Showing <span id="lblVisibleFrom" class="fw-bold text-dark">0</span> to <span id="lblVisibleTo" class="fw-bold text-dark">0</span> of <span id="lblTotalCount" class="fw-bold text-dark">0</span> recorded events
          </div>
          <div id="filterIndicator" class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1" style="display: none;">
            <i class="bi bi-funnel-fill me-1"></i>Filters active
          </div>
        </div>

      </div>

      <!-- Table Body -->
      <div class="card-body p-0 position-relative">

        <!-- Loading Spinner Overlay -->
        <div id="tableLoadingOverlay">
          <div class="spinner-border text-primary" role="status" style="width: 2.5rem; height: 2.5rem;">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table align-middle mb-0 table-hover" id="auditLogsTable">
            <thead>
              <tr>
                <th class="ps-4" style="min-width: 220px;">Actor / User</th>
                <th style="min-width: 140px;">Action &amp; Method</th>
                <th style="min-width: 260px;">Activity Description</th>
                <th style="min-width: 150px;">Module</th>
                <th style="min-width: 180px;">Network / Client</th>
                <th style="min-width: 170px;">Timestamp</th>
                <th class="pe-4 text-end" style="width: 100px;">Inspect</th>
              </tr>
            </thead>
            <tbody id="auditLogsTableBody" class="font-14">
              <!-- Rendered via AJAX JavaScript -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- Table Pagination Footer -->
      <div class="card-footer bg-white py-3 px-4 border-top d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2 text-muted font-13">
          <span>Rows per page:</span>
          <select id="filterPerPage" class="form-select form-select-sm rounded-pill py-1 px-2 border-secondary-subtle" style="width: 75px;" onchange="fetchLogs(1)">
            <option value="15">15</option>
            <option value="25" selected>25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
        </div>

        <div class="d-flex align-items-center gap-1" id="paginationControls">
          <!-- Rendered via JS -->
        </div>
      </div>

    </div>

  </div>
</main>

<!-- Detailed Inspection Modal -->
<div class="modal fade" id="auditDetailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

      <!-- Modal Header -->
      <div class="modal-header text-white p-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
        <div class="d-flex align-items-center gap-3">
          <div class="rounded-circle bg-white bg-opacity-15 text-white d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
            <i class="bi bi-file-earmark-text fs-4"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold text-white mb-1 font-16" id="detailModalTitle">Event Audit Inspection</h5>
            <div class="text-white-50 font-12" id="detailModalSubtitle">Log ID #--</div>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body p-4 font-14 text-dark" style="max-height: 75vh; overflow-y: auto;">

        <!-- Summary Info Grid -->
        <div class="row g-3 mb-4">
          <div class="col-12 col-md-6">
            <div class="detail-info-card h-100">
              <span class="text-muted font-11 fw-semibold text-uppercase tracking-wider d-block mb-1">Actor Identity</span>
              <div class="fw-bold text-dark font-15" id="detailUserName">--</div>
              <div class="text-muted font-12" id="detailUserEmail">--</div>
              <div class="mt-2">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill font-11 px-2 py-1" id="detailUserRole">--</span>
                <span class="badge bg-light text-muted border rounded-pill font-11 px-2 py-1" id="detailActorId">ID: --</span>
              </div>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="detail-info-card h-100">
              <span class="text-muted font-11 fw-semibold text-uppercase tracking-wider d-block mb-1">Network &amp; Client</span>
              <div class="fw-bold text-dark font-15 d-flex align-items-center gap-2">
                <i class="bi bi-hdd-network text-primary"></i>
                <span id="detailIpAddress">--</span>
              </div>
              <div class="text-muted font-12 text-break mt-1" id="detailUserAgent">--</div>
            </div>
          </div>
        </div>

        <!-- Request Details -->
        <div class="card border rounded-3 mb-4 bg-light bg-opacity-50">
          <div class="card-body p-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
              <div class="d-flex align-items-center gap-2">
                <span class="method-badge" id="detailMethodBadge">GET</span>
                <span class="fw-bold text-dark font-14" id="detailActionBadge">View</span>
              </div>
              <span class="text-muted font-12" id="detailTimestamp">--</span>
            </div>
            <div class="text-muted font-13 mb-1" id="detailDescription">--</div>
            <div class="p-2 bg-white rounded border text-muted font-12 text-break" style="font-family: monospace;" id="detailUrl">--</div>
          </div>
        </div>

        <!-- Old State vs New State (If updates occurred) -->
        <div id="diffSection" style="display: none;" class="mb-4">
          <h6 class="fw-bold text-dark mb-2 font-13 d-flex align-items-center gap-2">
            <i class="bi bi-arrow-left-right text-warning"></i> State Differences (Before vs After)
          </h6>
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <div class="p-3 rounded-3 bg-danger bg-opacity-10 border border-danger-subtle">
                <span class="fw-bold text-danger font-12 d-block mb-2"><i class="bi bi-dash-circle me-1"></i> Original State</span>
                <pre class="json-box text-dark mb-0 font-12" style="max-height: 180px; overflow-y: auto;" id="detailOldState">None</pre>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="p-3 rounded-3 bg-success bg-opacity-10 border border-success-subtle">
                <span class="fw-bold text-success font-12 d-block mb-2"><i class="bi bi-plus-circle me-1"></i> Modified State</span>
                <pre class="json-box text-dark mb-0 font-12" style="max-height: 180px; overflow-y: auto;" id="detailNewState">None</pre>
              </div>
            </div>
          </div>
        </div>

        <!-- Submitted Form Data (Shown only if actual post payload exists) -->
        <div id="payloadSection" class="mb-2" style="display: none;">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="fw-bold text-dark mb-0 font-13 d-flex align-items-center gap-2">
              <i class="bi bi-file-earmark-code text-primary"></i> Submitted Form Data
            </h6>
            <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-3 py-1 font-11" onclick="copyDetailPayload(this)">
              <i class="bi bi-clipboard me-1"></i> Copy JSON
            </button>
          </div>
          <div class="code-preview-box" id="detailPayloadBox"></div>
        </div>

      </div>

      <!-- Modal Footer -->
      <div class="modal-footer bg-light px-4 py-3 border-top d-flex align-items-center justify-content-between">
        <span class="text-muted font-12 d-flex align-items-center gap-1">
          <span class="pulse-indicator bg-success" style="width: 7px; height: 7px;"></span>
          <span>Sensitive fields (passwords, OTPs, tokens) automatically scrubbed</span>
        </span>
        <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<!-- How It Works Comprehensive Info Modal -->
<div class="modal fade" id="auditInfoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
      <div class="modal-header text-white p-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
        <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2 font-16">
          <i class="bi bi-shield-lock text-success"></i> How Activity &amp; Audit Logging Works
        </h5>
        <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 font-14 lh-base text-dark">
        <h6 class="fw-bold text-dark mb-2">1. Continuous User &amp; Employee Session Tracking</h6>
        <p class="text-muted mb-4">
          Every administrative user session is authenticated and tracked with their User ID, display name, registered email, and assigned permission role.
        </p>

        <h6 class="fw-bold text-dark mb-2">2. Comprehensive Lifecycle Events</h6>
        <p class="text-muted mb-4">
          The audit framework tracks <strong>Logins</strong> (and failed OTP attempts), <strong>Logouts</strong>, <strong>Page Views</strong> (GET requests), <strong>Form Submissions &amp; Updates</strong> (POST/PUT), and <strong>Destructive Deletions</strong> across all modules (Bookings, SPs, Customers, Employees, Services).
        </p>

        <h6 class="fw-bold text-dark mb-2">3. Zero-Leak Sensitive Field Masking</h6>
        <p class="text-muted mb-4">
          All passwords, OTP verification codes, authorization tokens, credit cards, and sensitive credentials are automatically redacted before being saved to the database.
        </p>

        <h6 class="fw-bold text-dark mb-2">4. Noise Filtering</h6>
        <p class="text-muted mb-0">
          Automated polling heartbeats (sidebar count updates, notification badges) and static assets are excluded so your audit log stays clean, actionable, and lightweight.
        </p>
      </div>
      <div class="modal-footer bg-light px-4 py-3 border-top">
        <button type="button" class="btn btn-primary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Understood</button>
      </div>
    </div>
  </div>
</div>

<script>
  let currentAuditPage = 1;
  let debounceAuditTimer = null;
  let cachedLogsData = [];

  // Helper colors for user initials
  const avatarColors = [{
      bg: '#e0e7ff',
      text: '#4338ca'
    }, // indigo
    {
      bg: '#dcfce7',
      text: '#15803d'
    }, // green
    {
      bg: '#fef3c7',
      text: '#b45309'
    }, // amber
    {
      bg: '#e0f2fe',
      text: '#0369a1'
    }, // sky
    {
      bg: '#fce7f3',
      text: '#be185d'
    }, // pink
    {
      bg: '#f3e8ff',
      text: '#7e22ce'
    }, // purple
  ];

  function getAvatarStyle(name) {
    let hash = 0;
    for (let i = 0; i < (name || '').length; i++) {
      hash = (name.charCodeAt(i) + ((hash << 5) - hash));
    }
    const idx = Math.abs(hash) % avatarColors.length;
    return avatarColors[idx];
  }

  function getInitials(name) {
    if (!name || name === 'Unknown') return 'U';
    const parts = name.trim().split(/\s+/);
    if (parts.length === 1) return parts[0].substring(0, 2).toUpperCase();
    return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
  }

  function escapeHtml(str) {
    if (!str) return '';
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
  }

  function debounceFetchLogs() {
    clearTimeout(debounceAuditTimer);
    debounceAuditTimer = setTimeout(() => {
      fetchLogs(1);
    }, 350);
  }

  function handleSegmentedTab(btn, action) {
    document.querySelectorAll('.segmented-pill').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('filterAction').value = action;
    fetchLogs(1);
  }

  function syncActionSelect(val) {
    document.querySelectorAll('.segmented-pill').forEach(b => {
      if (b.getAttribute('data-action') === val) {
        b.classList.add('active');
      } else {
        b.classList.remove('active');
      }
    });
  }

  function setQuickTab(action) {
    syncActionSelect(action);
    document.getElementById('filterAction').value = action;
    fetchLogs(1);
    const tableEl = document.getElementById('auditLogsTable');
    if (tableEl) {
      tableEl.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
      });
    }
  }

  function resetAllFilters() {
    document.getElementById('filterSearch').value = '';
    document.getElementById('filterUser').value = '';
    document.getElementById('filterAction').value = '';
    document.getElementById('filterModule').value = '';
    document.getElementById('filterDateFrom').value = '';
    document.getElementById('filterDateTo').value = '';
    syncActionSelect('');
    fetchLogs(1);
  }

  function refreshAuditFeed(animate = false) {
    const btn = document.getElementById('btnRefreshFeed');
    if (animate && btn) {
      const icon = btn.querySelector('i');
      if (icon) icon.classList.add('fa-spin');
      setTimeout(() => {
        if (icon) icon.classList.remove('fa-spin');
      }, 1000);
    }
    fetchLogs(currentAuditPage);
  }

  function showLoading(show) {
    const overlay = document.getElementById('tableLoadingOverlay');
    if (!overlay) return;
    if (show) {
      overlay.classList.add('active');
    } else {
      overlay.classList.remove('active');
    }
  }

  function fetchLogs(page = 1) {
    currentAuditPage = page;
    showLoading(true);

    const search = document.getElementById('filterSearch').value.trim();
    const userId = document.getElementById('filterUser').value;
    const action = document.getElementById('filterAction').value;
    const module = document.getElementById('filterModule').value;
    const dateFrom = document.getElementById('filterDateFrom').value;
    const dateTo = document.getElementById('filterDateTo').value;
    const perPage = document.getElementById('filterPerPage').value;

    // Check if any filter is active
    const hasFilter = search || userId || action || module || dateFrom || dateTo;
    const indicator = document.getElementById('filterIndicator');
    if (indicator) {
      indicator.style.display = hasFilter ? 'inline-flex' : 'none';
    }

    const params = new URLSearchParams({
      page: page,
      per_page: perPage,
      search: search,
      user_id: userId,
      action: action,
      entity_type: module,
      date_from: dateFrom,
      date_to: dateTo
    });

    fetch(`<?= base_url('audit-logs/data') ?>?${params.toString()}`, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(res => res.json())
      .then(json => {
        showLoading(false);
        if (!json.success) {
          console.error('Failed to load logs:', json);
          return;
        }

        cachedLogsData = json.data || [];
        renderLogsTable(cachedLogsData);
        renderPagination(json.pagination);
        updateKpis(json.metrics, json.pagination.total_records);
      })
      .catch(err => {
        showLoading(false);
        console.error('Audit log fetch error:', err);
      });
  }

  function updateKpis(metrics, totalRecords) {
    if (!metrics) return;
    const totalEl = document.getElementById('metric-total');
    if (totalEl) totalEl.innerText = Number(metrics.total_logs || 0).toLocaleString();

    const loginsEl = document.getElementById('metric-logins');
    if (loginsEl) loginsEl.innerText = Number(metrics.logins_today || 0).toLocaleString();

    const updatesEl = document.getElementById('metric-updates');
    if (updatesEl) updatesEl.innerText = Number(metrics.updates_count || 0).toLocaleString();

    const deletesEl = document.getElementById('metric-deletes');
    if (deletesEl) deletesEl.innerText = Number(metrics.deletes_count || 0).toLocaleString();

    const pillCount = document.getElementById('pill-all-count');
    if (pillCount) pillCount.innerText = Number(metrics.total_logs || 0).toLocaleString();
  }

  function getActionBadge(action) {
    const act = (action || '').toLowerCase();
    if (act.includes('login')) {
      return `<span class="action-badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-box-arrow-in-right"></i> Login</span>`;
    } else if (act.includes('logout')) {
      return `<span class="action-badge bg-secondary-subtle text-secondary border border-secondary-subtle"><i class="bi bi-box-arrow-left"></i> Logout</span>`;
    } else if (act.includes('delete') || act.includes('cancel') || act.includes('purge')) {
      return `<span class="action-badge bg-danger-subtle text-danger border border-danger-subtle"><i class="bi bi-trash3"></i> Delete</span>`;
    } else if (act.includes('update') || act.includes('edit') || act.includes('modify')) {
      return `<span class="action-badge bg-warning-subtle text-warning border border-warning-subtle"><i class="bi bi-pencil-square"></i> Update</span>`;
    } else if (act.includes('create') || act.includes('store') || act.includes('add')) {
      return `<span class="action-badge bg-info-subtle text-info border border-info-subtle"><i class="bi bi-plus-circle"></i> Create</span>`;
    } else {
      return `<span class="action-badge bg-light text-dark border"><i class="bi bi-eye"></i> View</span>`;
    }
  }

  function getMethodBadge(method) {
    const m = (method || 'GET').toUpperCase();
    let cls = 'method-get';
    if (m === 'POST') cls = 'method-post';
    else if (m === 'PUT' || m === 'PATCH') cls = 'method-put';
    else if (m === 'DELETE') cls = 'method-delete';
    return `<span class="method-badge ${cls}">${m}</span>`;
  }

  function renderLogsTable(logs) {
    const tbody = document.getElementById('auditLogsTableBody');
    if (!tbody) return;

    if (!logs || logs.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="7" class="text-center py-5">
            <div class="py-4">
              <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 68px; height: 68px;">
                <i class="bi bi-shield-check fs-1"></i>
              </div>
              <h5 class="fw-bold text-dark mb-1">No Activity Logs Found</h5>
              <p class="text-muted font-13 mb-0">No matching activities recorded for the selected search or filter criteria.</p>
            </div>
          </td>
        </tr>
      `;
      return;
    }

    let html = '';
    logs.forEach(log => {
      const style = getAvatarStyle(log.user_name);
      const initials = getInitials(log.user_name);
      const actionBadge = getActionBadge(log.action);
      const methodBadge = getMethodBadge(log.method);

      // Clean module label
      const moduleName = log.entity_type ?
        log.entity_type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) :
        'System';

      html += `
        <tr class="audit-row" data-id="${log.id}">
          <!-- Actor / User -->
          <td class="ps-4">
            <div class="d-flex align-items-center gap-3">
              <div class="user-avatar-circle" style="background-color: ${style.bg}; color: ${style.text};">
                ${initials}
              </div>
              <div>
                <div class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                  <span>${escapeHtml(log.user_name)}</span>
                  ${log.role_name ? `<span class="badge bg-light text-secondary border font-10 px-2 py-0 rounded-pill">${escapeHtml(log.role_name)}</span>` : ''}
                </div>
                <div class="text-muted font-12">${escapeHtml(log.user_email)}</div>
              </div>
            </div>
          </td>

          <!-- Action & Method -->
          <td>
            <div class="d-flex flex-column align-items-start gap-1">
              ${actionBadge}
              ${methodBadge}
            </div>
          </td>

          <!-- Activity Description -->
          <td>
            <div class="fw-semibold text-dark mb-1 font-13">${escapeHtml(log.description || '--')}</div>
            <div class="text-muted font-11 font-monospace text-truncate" style="max-width: 280px;" title="${escapeHtml(log.url)}">
              ${escapeHtml(log.url || '--')}
            </div>
          </td>

          <!-- Module -->
          <td>
            <span class="badge bg-light text-dark border rounded-pill px-3 py-1 font-12">
              <i class="bi bi-folder2-open me-1 text-muted"></i>
              ${escapeHtml(moduleName)}
            </span>
            ${log.entity_id ? `<div class="text-muted font-11 mt-1 ps-1">ID: #${log.entity_id}</div>` : ''}
          </td>

          <!-- Network & Client -->
          <td>
            <div class="fw-semibold text-dark font-13 d-flex align-items-center gap-1">
              <i class="bi bi-globe2 text-muted font-11"></i>
              <span>${escapeHtml(log.ip_address || '127.0.0.1')}</span>
            </div>
            <div class="text-muted font-11 text-truncate" style="max-width: 200px;" title="${escapeHtml(log.user_agent)}">
              ${escapeHtml(log.user_agent ? log.user_agent.split(' ')[0] : 'Browser')}
            </div>
          </td>

          <!-- Timestamp -->
          <td>
            <div class="fw-semibold text-dark font-13">${escapeHtml(log.time_ago)}</div>
            <div class="text-muted font-11">${escapeHtml(log.created_at)}</div>
          </td>

          <!-- Inspect Button -->
          <td class="pe-4 text-end">
            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 font-12 d-inline-flex align-items-center gap-1 btn-hover-scale"
              onclick="openAuditDetails(${log.id})" title="Inspect complete payload and state">
              <i class="bi bi-eye"></i>
              <span>Inspect</span>
            </button>
          </td>
        </tr>
      `;
    });

    tbody.innerHTML = html;
  }

  function renderPagination(pg) {
    const container = document.getElementById('paginationControls');
    const lblFrom = document.getElementById('lblVisibleFrom');
    const lblTo = document.getElementById('lblVisibleTo');
    const lblTotal = document.getElementById('lblTotalCount');

    if (!pg || !container) return;

    const total = pg.total_records || 0;
    const perPage = pg.per_page || 25;
    const current = pg.current_page || 1;
    const totalPages = pg.total_pages || 1;

    const from = total > 0 ? (current - 1) * perPage + 1 : 0;
    const to = Math.min(current * perPage, total);

    if (lblFrom) lblFrom.innerText = from.toLocaleString();
    if (lblTo) lblTo.innerText = to.toLocaleString();
    if (lblTotal) lblTotal.innerText = total.toLocaleString();

    if (totalPages <= 1) {
      container.innerHTML = '';
      return;
    }

    let btns = '';

    // Previous button
    btns += `
      <button type="button" class="pagination-pill-btn" ${current <= 1 ? 'disabled' : ''} onclick="fetchLogs(${current - 1})">
        <i class="bi bi-chevron-left font-11"></i>
      </button>
    `;

    // Numeric buttons (limit to 5 window)
    let start = Math.max(1, current - 2);
    let end = Math.min(totalPages, start + 4);
    if (end - start < 4) {
      start = Math.max(1, end - 4);
    }

    for (let p = start; p <= end; p++) {
      btns += `
        <button type="button" class="pagination-pill-btn ${p === current ? 'active' : ''}" onclick="fetchLogs(${p})">
          ${p}
        </button>
      `;
    }

    // Next button
    btns += `
      <button type="button" class="pagination-pill-btn" ${current >= totalPages ? 'disabled' : ''} onclick="fetchLogs(${current + 1})">
        <i class="bi bi-chevron-right font-11"></i>
      </button>
    `;

    container.innerHTML = btns;
  }

  // Open Log Details Modal
  function openAuditDetails(id) {
    fetch(`<?= base_url('audit-logs/details') ?>/${id}`, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(r => r.json())
      .then(res => {
        if (!res.success || !res.log) {
          alert(res.message || 'Log details could not be loaded.');
          return;
        }
        const log = res.log;

        document.getElementById('detailModalSubtitle').innerText = `Log ID #${log.id} • ${log.created_at}`;
        document.getElementById('detailUserName').innerText = log.user_name || 'System / Guest';
        document.getElementById('detailUserEmail').innerText = log.user_email || '--';
        document.getElementById('detailUserRole').innerText = log.role_name || 'Admin';
        document.getElementById('detailActorId').innerText = `Actor ID: #${log.actor_id || '0'}`;

        document.getElementById('detailIpAddress').innerText = log.ip_address || 'Unknown IP';
        document.getElementById('detailUserAgent').innerText = log.user_agent || 'Unknown User-Agent';

        document.getElementById('detailMethodBadge').innerText = (log.method || 'GET').toUpperCase();
        document.getElementById('detailMethodBadge').className = `method-badge method-${(log.method || 'GET').toLowerCase()}`;
        document.getElementById('detailActionBadge').innerText = (log.action || 'view').toUpperCase();
        document.getElementById('detailTimestamp').innerText = log.created_at;
        document.getElementById('detailDescription').innerText = log.description || '--';
        document.getElementById('detailUrl').innerText = log.url || '--';

        // Diff section (old vs new state)
        const diffSec = document.getElementById('diffSection');
        if (log.old_state || log.new_state) {
          diffSec.style.display = 'block';
          document.getElementById('detailOldState').innerText = log.old_state ? JSON.stringify(log.old_state, null, 2) : 'None';
          document.getElementById('detailNewState').innerText = log.new_state ? JSON.stringify(log.new_state, null, 2) : 'None';
        } else {
          diffSec.style.display = 'none';
        }

        // Submitted Form Data / Payload (only show if actual data exists)
        const payloadSec = document.getElementById('payloadSection');
        const payloadBox = document.getElementById('detailPayloadBox');
        const payloadData = log.new_state || (log.metadata && log.metadata.params && Object.keys(log.metadata.params).length > 0 ? log.metadata.params : null);

        if (payloadData && typeof payloadData === 'object' && Object.keys(payloadData).length > 0) {
          payloadSec.style.display = 'block';
          payloadBox.innerText = JSON.stringify(payloadData, null, 2);
        } else {
          payloadSec.style.display = 'none';
          payloadBox.innerText = '';
        }

        const modal = new bootstrap.Modal(document.getElementById('auditDetailModal'));
        modal.show();
      })
      .catch(err => {
        console.error(err);
        alert('Error fetching log details.');
      });
  }

  function copyDetailPayload(btn) {
    const text = document.getElementById('detailPayloadBox').innerText;
    if (navigator.clipboard) {
      navigator.clipboard.writeText(text);
    } else {
      const el = document.createElement('textarea');
      el.value = text;
      document.body.appendChild(el);
      el.select();
      document.execCommand('copy');
      document.body.removeChild(el);
    }
    const orig = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-check2 text-success me-1"></i> Copied';
    setTimeout(() => {
      btn.innerHTML = orig;
    }, 1500);
  }

  // Export current table logs to CSV
  function exportAuditTableToCSV() {
    if (!cachedLogsData || cachedLogsData.length === 0) {
      alert('No audit logs available to export.');
      return;
    }

    const headers = ['ID', 'User Name', 'User Email', 'Role', 'Action', 'Method', 'Description', 'URL', 'Module', 'Entity ID', 'IP Address', 'Created At'];
    let csv = [headers.map(h => `"${h}"`).join(',')];

    cachedLogsData.forEach(row => {
      const line = [
        row.id,
        row.user_name || '',
        row.user_email || '',
        row.role_name || '',
        row.action || '',
        row.method || '',
        row.description || '',
        row.url || '',
        row.entity_type || '',
        row.entity_id || '',
        row.ip_address || '',
        row.created_at || ''
      ].map(val => `"${(val || '').toString().replace(/"/g, '""')}"`).join(',');
      csv.push(line);
    });

    const csvBlob = new Blob([csv.join('\n')], {
      type: 'text/csv;charset=utf-8;'
    });
    const url = URL.createObjectURL(csvBlob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `petsfolio-audit-logs-${new Date().toISOString().split('T')[0]}.csv`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
  }

  // Initialize on document ready
  document.addEventListener('DOMContentLoaded', function() {
    fetchLogs(1);
  });
</script>

<?php echo $this->endSection(); ?>