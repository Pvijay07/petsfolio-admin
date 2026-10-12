<?php echo $this->extend('admin/includes/header'); ?>
<?php echo $this->section('content'); ?>
<?php
$activeFunnels = $activeFunnels ?? [];
$initialFunnelKey = $initialFunnelKey ?? 'customer_booking';
$filterOptions = $filterOptions ?? ['services' => [], 'cities' => [], 'platforms' => [], 'versions' => []];
?>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<style>
  :root {
    --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
    --danger-gradient: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    --info-gradient: linear-gradient(135deg, #06b6d4 0%, #0284c7 100%);
  }

  .analytics-header {
    background: var(--primary-gradient);
    border-radius: 20px;
    padding: 28px 32px;
    color: #ffffff;
    box-shadow: 0 12px 30px rgba(79, 70, 229, 0.18);
    position: relative;
    overflow: hidden;
    margin-bottom: 25px;
  }

  .analytics-header::before {
    content: '';
    position: absolute;
    top: -50px;
    right: -50px;
    width: 220px;
    height: 220px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
    border-radius: 50%;
  }

  .kpi-card {
    border: none;
    border-radius: 16px;
    background: #ffffff;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    height: 100%;
  }

  .kpi-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 14px 28px rgba(0, 0, 0, 0.08);
  }

  .kpi-card .kpi-icon-box {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
  }

  .kpi-val {
    font-size: 1.85rem;
    font-weight: 800;
    color: #1e293b;
    line-height: 1.2;
    margin-top: 6px;
  }

  .kpi-lbl {
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
  }

  .filter-panel {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.03);
    padding: 20px 24px;
    margin-bottom: 25px;
    border: 1px solid #f1f5f9;
  }

  .filter-label {
    font-size: 0.75rem;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
    display: block;
  }

  .funnel-container {
    background: #ffffff;
    border-radius: 18px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.04);
    padding: 26px;
    margin-bottom: 25px;
    border: 1px solid #f1f5f9;
  }

  /* Funnel Visual Horizontal Track */
  .funnel-track {
    display: flex;
    align-items: stretch;
    gap: 12px;
    overflow-x: auto;
    padding: 10px 4px 20px;
    scrollbar-width: thin;
  }

  .funnel-step-node {
    flex: 0 0 200px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 16px;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.2s ease;
  }

  .funnel-step-node:hover {
    border-color: #6366f1;
    box-shadow: 0 8px 18px rgba(99, 102, 241, 0.12);
    transform: translateY(-2px);
  }

  .funnel-step-node.first-node {
    background: linear-gradient(180deg, #eef2ff 0%, #ffffff 100%);
    border-color: #c7d2fe;
  }

  .funnel-step-node.last-node {
    background: linear-gradient(180deg, #ecfdf5 0%, #ffffff 100%);
    border-color: #a7f3d0;
  }

  .node-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
  }

  .node-num {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #4f46e5;
    color: #ffffff;
    font-size: 0.72rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .node-title {
    font-size: 0.88rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .node-event {
    font-size: 0.72rem;
    color: #64748b;
    font-family: monospace;
  }

  .node-val {
    font-size: 1.45rem;
    font-weight: 800;
    color: #0f172a;
    margin: 8px 0 4px;
  }

  .node-progress-wrapper {
    background: #e2e8f0;
    height: 8px;
    border-radius: 4px;
    overflow: hidden;
    margin: 8px 0;
  }

  .node-progress-bar {
    height: 100%;
    border-radius: 4px;
    background: var(--primary-gradient);
    transition: width 0.6s ease;
  }

  .node-metrics {
    display: flex;
    justify-content: space-between;
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
  }

  .funnel-connector {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-width: 44px;
    text-align: center;
  }

  .connector-arrow {
    color: #94a3b8;
    font-size: 1.1rem;
    margin: 4px 0;
  }

  .connector-time {
    font-size: 0.7rem;
    font-weight: 700;
    color: #475569;
    background: #f1f5f9;
    padding: 2px 6px;
    border-radius: 6px;
    white-space: nowrap;
  }

  .connector-dropoff {
    font-size: 0.72rem;
    font-weight: 700;
    color: #ef4444;
    background: #fee2e2;
    padding: 2px 6px;
    border-radius: 6px;
    white-space: nowrap;
    margin-top: 4px;
  }

  /* Table styling */
  .table-funnel {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
  }

  .table-funnel thead th {
    background: #f8fafc;
    color: #475569;
    font-size: 0.78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 14px 16px;
    border-bottom: 2px solid #e2e8f0;
  }

  .table-funnel tbody td {
    padding: 14px 16px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.88rem;
  }

  .table-funnel tbody tr:hover td {
    background: #f8fafc;
  }

  .badge-dropoff {
    background: #fee2e2;
    color: #dc2626;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 0.78rem;
  }

  .badge-conversion {
    background: #dcfce7;
    color: #15803d;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 0.78rem;
  }

  .badge-time {
    background: #f1f5f9;
    color: #475569;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.75rem;
  }

  .signal-card {
    background: #ffffff;
    border-radius: 14px;
    border: 1px solid #f1f5f9;
    padding: 16px 20px;
    margin-bottom: 14px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
    transition: all 0.2s ease;
  }

  .signal-card:hover {
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    border-color: #cbd5e1;
  }

  .btn-filter-preset {
    border-radius: 8px;
    font-size: 0.78rem;
    font-weight: 600;
    padding: 6px 12px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #475569;
    transition: all 0.15s ease;
  }

  .btn-filter-preset.active, .btn-filter-preset:hover {
    background: #4f46e5;
    color: #ffffff;
    border-color: #4f46e5;
  }

  .nav-tabs-custom {
    border-bottom: 2px solid #e2e8f0;
    margin-bottom: 20px;
  }

  .nav-tabs-custom .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
    color: #64748b;
    font-weight: 600;
    padding: 10px 18px;
    margin-bottom: -2px;
    transition: all 0.2s ease;
  }

  .nav-tabs-custom .nav-link.active {
    color: #4f46e5;
    border-bottom-color: #4f46e5;
    background: transparent;
  }
</style>

<div class="content-wrapper p-3">
  <!-- Top Banner -->
  <div class="analytics-header">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <div class="d-flex align-items-center mb-2">
          <span class="badge bg-white text-primary px-3 py-1 font-weight-bold rounded-pill mr-2">
            <i class="fas fa-funnel-dollar mr-1"></i> Funnel & Event Intelligence
          </span>
          <span class="text-white-50 small">Session-Aware Real-time Tracking</span>
        </div>
        <h2 class="font-weight-bold mb-1 text-white">Customer Journey & Drop-off Analytics</h2>
        <p class="text-white-50 mb-0">
          Track end-to-end conversion funnels, pinpoint exact user drop-off stages, and detect rage & error signals across platforms.
        </p>
      </div>
      <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
        <div class="btn-group">
          <button class="btn btn-light font-weight-bold shadow-sm" onclick="openFunnelModal()">
            <i class="fas fa-plus-circle text-primary mr-1"></i> New Funnel
          </button>
          <button class="btn btn-outline-light font-weight-bold ml-2 shadow-sm" onclick="openEventsStreamModal()">
            <i class="fas fa-stream mr-1"></i> Live Events
          </button>
          <button class="btn btn-outline-light font-weight-bold ml-2 shadow-sm" onclick="generateDemoDataPrompt()" title="Seed Demo Telemetry">
            <i class="fas fa-magic mr-1"></i> Demo Data
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Primary Filter & Segmentation Panel -->
  <div class="filter-panel">
    <div class="row align-items-center mb-3 pb-2 border-bottom">
      <div class="col-md-5">
        <label class="filter-label"><i class="fas fa-route mr-1 text-primary"></i> Active Funnel</label>
        <select id="funnelSelect" class="form-control font-weight-bold border-primary" style="font-size: 0.95rem;" onchange="changeFunnel(this.value)">
          <?php foreach ($activeFunnels as $f) : ?>
            <option value="<?= esc($f['funnel_key']) ?>" <?= ($f['funnel_key'] === $initialFunnelKey) ? 'selected' : '' ?>>
              <?= esc($f['name']) ?> (<?= ucfirst(esc($f['category'])) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-7 text-md-right mt-2 mt-md-0">
        <label class="filter-label text-md-right">Quick Date Presets</label>
        <div class="btn-group">
          <button class="btn btn-filter-preset" onclick="setDatePreset('today', this)">Today</button>
          <button class="btn btn-filter-preset" onclick="setDatePreset('yesterday', this)">Yesterday</button>
          <button class="btn btn-filter-preset active" onclick="setDatePreset('7days', this)">Last 7 Days</button>
          <button class="btn btn-filter-preset" onclick="setDatePreset('30days', this)">Last 30 Days</button>
          <button class="btn btn-filter-preset" onclick="setDatePreset('all', this)">All Time</button>
        </div>
      </div>
    </div>

    <!-- Segmentation Controls -->
    <div class="row">
      <div class="col-lg-2 col-md-4 col-6 mb-2">
        <label class="filter-label">Start Date</label>
        <input type="date" id="filterStartDate" class="form-control form-control-sm" onchange="refreshFunnelData()">
      </div>
      <div class="col-lg-2 col-md-4 col-6 mb-2">
        <label class="filter-label">End Date</label>
        <input type="date" id="filterEndDate" class="form-control form-control-sm" onchange="refreshFunnelData()">
      </div>
      <div class="col-lg-2 col-md-4 col-6 mb-2">
        <label class="filter-label">Platform</label>
        <select id="filterPlatform" class="form-control form-control-sm" onchange="refreshFunnelData()">
          <option value="all">All Platforms</option>
          <option value="android">Android</option>
          <option value="ios">iOS</option>
          <option value="web">Web</option>
        </select>
      </div>
      <div class="col-lg-2 col-md-4 col-6 mb-2">
        <label class="filter-label">Customer Type</label>
        <select id="filterCustomerType" class="form-control form-control-sm" onchange="refreshFunnelData()">
          <option value="all">All Customers</option>
          <option value="new">New Customers</option>
          <option value="returning">Returning</option>
        </select>
      </div>
      <div class="col-lg-2 col-md-4 col-6 mb-2">
        <label class="filter-label">Service</label>
        <select id="filterService" class="form-control form-control-sm" onchange="refreshFunnelData()">
          <option value="all">All Services</option>
          <?php foreach ($filterOptions['services'] as $s) : ?>
            <option value="<?= esc($s['id']) ?>"><?= esc($s['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-lg-2 col-md-4 col-6 mb-2">
        <label class="filter-label">City</label>
        <select id="filterCity" class="form-control form-control-sm" onchange="refreshFunnelData()">
          <option value="all">All Cities</option>
          <?php foreach ($filterOptions['cities'] as $c) : ?>
            <option value="<?= esc($c) ?>"><?= esc($c) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </div>

  <!-- KPI Cards Row -->
  <div class="row mb-4">
    <div class="col-lg-3 col-sm-6 mb-3">
      <div class="kpi-card p-3">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <div class="kpi-lbl">Top of Funnel (Step 1)</div>
            <div class="kpi-val" id="kpiEntrants">—</div>
            <span class="text-muted small">Unique entrants in period</span>
          </div>
          <div class="kpi-icon-box bg-soft-primary text-primary">
            <i class="fas fa-users"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-sm-6 mb-3">
      <div class="kpi-card p-3">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <div class="kpi-lbl">Goal Completed</div>
            <div class="kpi-val text-success" id="kpiCompleted">—</div>
            <span class="text-muted small">Reached final confirmation</span>
          </div>
          <div class="kpi-icon-box bg-soft-success text-success">
            <i class="fas fa-check-double"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-sm-6 mb-3">
      <div class="kpi-card p-3">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <div class="kpi-lbl">Overall Conversion</div>
            <div class="kpi-val text-indigo" id="kpiConversion">—%</div>
            <span class="text-muted small">Step 1 to Final Step</span>
          </div>
          <div class="kpi-icon-box bg-soft-info text-info">
            <i class="fas fa-percentage"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-sm-6 mb-3">
      <div class="kpi-card p-3">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <div class="kpi-lbl">Biggest Bottleneck</div>
            <div class="kpi-val text-danger" style="font-size: 1.25rem;" id="kpiBottleneck">—</div>
            <span class="text-danger small font-weight-bold" id="kpiBottleneckRate">—</span>
          </div>
          <div class="kpi-icon-box bg-soft-danger text-danger">
            <i class="fas fa-exclamation-triangle"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Visual Horizontal Funnel Flow -->
  <div class="funnel-container">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <div>
        <h5 class="font-weight-bold mb-0 text-dark">
          <i class="fas fa-stream text-primary mr-1"></i> Interactive Customer Journey Flow
        </h5>
        <span class="text-muted small">Step-by-step user progression, drop-off % and average dwell times</span>
      </div>
      <div>
        <span class="badge badge-light border px-2 py-1 font-weight-bold mr-2" id="funnelCategoryBadge">Customer Funnel</span>
        <button class="btn btn-sm btn-outline-secondary" onclick="refreshFunnelData()">
          <i class="fas fa-sync-alt mr-1"></i> Refresh
        </button>
      </div>
    </div>

    <!-- Scrollable Funnel Nodes -->
    <div class="funnel-track" id="funnelTrackContainer">
      <div class="text-center w-100 py-5">
        <div class="spinner-border text-primary" role="status"></div>
        <div class="text-muted mt-2">Computing funnel flow metrics...</div>
      </div>
    </div>
  </div>

  <!-- Tabs for Details: Funnel Table, Problem Signals, and Drop-off Analytics -->
  <div class="card border-0 shadow-sm rounded-16 p-4">
    <ul class="nav nav-tabs nav-tabs-custom" id="funnelTabs" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="steps-tab" data-bs-toggle="tab" data-bs-target="#tab-steps" href="#tab-steps" role="tab" onclick="switchFunnelTab('tab-steps', this)">
          <i class="fas fa-table mr-1"></i> Funnel Steps Breakdown
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="signals-tab" data-bs-toggle="tab" data-bs-target="#tab-signals" href="#tab-signals" role="tab" onclick="switchFunnelTab('tab-signals', this); loadProblemSignals();">
          <i class="fas fa-fire-alt text-danger mr-1"></i> Rage & Problem Signals
          <span class="badge badge-danger ml-1" id="problemSignalsCount">0</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="chart-tab" data-bs-toggle="tab" data-bs-target="#tab-chart" href="#tab-chart" role="tab" onclick="switchFunnelTab('tab-chart', this)">
          <i class="fas fa-chart-bar mr-1"></i> Visual Conversion Charts
        </a>
      </li>
    </ul>

    <div class="tab-content" id="funnelTabsContent">
      <!-- TAB 1: Steps Breakdown Table -->
      <div class="tab-pane fade show active" id="tab-steps" role="tabpanel">
        <div class="table-responsive">
          <table class="table table-funnel" id="funnelTable">
            <thead>
              <tr>
                <th style="width: 50px;">#</th>
                <th>Step Name & Event</th>
                <th>Screen</th>
                <th class="text-right">Users Reached</th>
                <th class="text-right">Completed</th>
                <th class="text-right">Dropped</th>
                <th class="text-center">Drop-off %</th>
                <th class="text-center">Next Conversion %</th>
                <th class="text-center">Avg. Time</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody id="funnelTableBody">
              <tr>
                <td colspan="10" class="text-center py-4 text-muted">Loading funnel step data...</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- TAB 2: Problem & Rage Signals -->
      <div class="tab-pane fade" id="tab-signals" role="tabpanel">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div>
            <h6 class="font-weight-bold mb-0 text-dark">Detected Behavioral Friction & Errors</h6>
            <span class="text-muted small">Identifies errors, rage clicks, and specific forms where users give up</span>
          </div>
          <div>
            <button class="btn btn-sm btn-outline-danger" onclick="loadProblemSignals()">
              <i class="fas fa-redo-alt mr-1"></i> Re-scan Signals
            </button>
          </div>
        </div>

        <div id="signalsContainer">
          <div class="text-center py-4">
            <div class="spinner-border text-danger" role="status"></div>
            <div class="text-muted mt-2">Analyzing problem signals...</div>
          </div>
        </div>
      </div>

      <!-- TAB 3: Visual Conversion Chart -->
      <div class="tab-pane fade" id="tab-chart" role="tabpanel">
        <div class="row">
          <div class="col-lg-8 mb-4">
            <h6 class="font-weight-bold text-dark mb-3">Users Progression Across Stages</h6>
            <div style="height: 340px;">
              <canvas id="funnelChart"></canvas>
            </div>
          </div>
          <div class="col-lg-4 mb-4">
            <h6 class="font-weight-bold text-dark mb-3">Step Conversion vs Drop-off %</h6>
            <div style="height: 340px;">
              <canvas id="conversionChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL: Dropped Users Drilldown -->
<div class="modal fade" id="droppedUsersModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-light">
        <div>
          <h5 class="modal-title font-weight-bold text-danger">
            <i class="fas fa-user-times mr-1"></i> Dropped Users Drilldown
          </h5>
          <span class="text-muted small" id="dropoffModalSubtitle">Users who reached this step but did not proceed</span>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="thead-light">
              <tr>
                <th>User / Customer</th>
                <th>Phone</th>
                <th>Platform & Device</th>
                <th>City</th>
                <th>Last Active</th>
              </tr>
            </thead>
            <tbody id="droppedUsersTableBody">
              <tr>
                <td colspan="5" class="text-center py-4 text-muted">Loading dropped users list...</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer bg-light py-2">
        <span class="text-muted small mr-auto" id="droppedUsersCountText">Showing recent dropped sessions</span>
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL: Funnel Configuration Builder -->
<div class="modal fade" id="funnelConfigModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title font-weight-bold">
          <i class="fas fa-sliders-h mr-1"></i> Funnel Configuration Builder
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="funnelConfigForm">
          <input type="hidden" id="cfgFunnelId" name="id">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="font-weight-bold">Funnel Name <span class="text-danger">*</span></label>
              <input type="text" id="cfgName" name="name" class="form-control" placeholder="e.g. Grooming Booking Flow" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="font-weight-bold">Funnel Key <span class="text-danger">*</span></label>
              <input type="text" id="cfgFunnelKey" name="funnel_key" class="form-control" placeholder="e.g. grooming_booking_funnel" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="font-weight-bold">Category</label>
              <select id="cfgCategory" name="category" class="form-control">
                <option value="customer">Customer Journey</option>
                <option value="provider">Service Provider</option>
                <option value="campaign">Marketing Campaign</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="font-weight-bold">Inactivity Drop-off Window (Minutes)</label>
              <input type="number" id="cfgInactivity" name="inactivity_window_minutes" class="form-control" value="30">
            </div>
          </div>

          <div class="mb-2">
            <label class="font-weight-bold d-flex justify-content-between">
              <span>Funnel Steps Sequence <span class="text-danger">*</span></span>
              <button type="button" class="btn btn-xs btn-outline-primary" onclick="addConfigStepRow()">
                <i class="fas fa-plus"></i> Add Step
              </button>
            </label>
            <div id="cfgStepsContainer" style="max-height: 280px; overflow-y: auto;">
              <!-- Dynamic Step Rows -->
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary font-weight-bold" onclick="saveFunnelConfiguration()">
          <i class="fas fa-save mr-1"></i> Save Funnel
        </button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL: Live Events Stream -->
<div class="modal fade" id="eventsStreamModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-dark text-white">
        <div>
          <h5 class="modal-title font-weight-bold text-white">
            <i class="fas fa-stream text-info mr-1"></i> Real-time Analytics Events Stream
          </h5>
          <span class="text-white-50 small">Raw telemetry payload inspection</span>
        </div>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-3">
        <div class="input-group mb-3">
          <input type="text" id="streamSearchInput" class="form-control" placeholder="Search by event name, session ID, user ID..." onkeyup="if(event.key==='Enter') loadEventsStream()">
          <div class="input-group-append">
            <button class="btn btn-primary" onclick="loadEventsStream()"><i class="fas fa-search"></i> Search</button>
          </div>
        </div>
        <div class="table-responsive" style="max-height: 500px;">
          <table class="table table-sm table-hover font-size-13">
            <thead class="thead-dark">
              <tr>
                <th>Time</th>
                <th>Event Name</th>
                <th>Screen</th>
                <th>Platform</th>
                <th>Session ID</th>
                <th>User ID</th>
                <th>Metadata</th>
              </tr>
            </thead>
            <tbody id="eventsStreamTableBody">
              <tr>
                <td colspan="7" class="text-center py-4 text-muted">Loading events stream...</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer bg-light py-2">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript Logic -->
<script>
  let currentFunnelKey = '<?= esc($initialFunnelKey) ?>';
  let chartMain = null;
  let chartConversion = null;

  document.addEventListener('DOMContentLoaded', function() {
    setDatePreset('7days');

    // Handle initial URL hash (e.g. #tab-signals or #tab-chart)
    const initialHash = window.location.hash ? window.location.hash.substring(1) : '';
    if (initialHash && document.getElementById(initialHash)) {
      switchFunnelTab(initialHash);
      if (initialHash === 'tab-signals') loadProblemSignals();
    }
  });

  window.addEventListener('hashchange', function() {
    const hash = window.location.hash ? window.location.hash.substring(1) : '';
    if (hash && document.getElementById(hash)) {
      switchFunnelTab(hash);
      if (hash === 'tab-signals') loadProblemSignals();
    }
  });

  function switchFunnelTab(tabPaneId, element) {
    // 1. Update tab navigation link classes
    document.querySelectorAll('#funnelTabs .nav-link').forEach(link => {
      link.classList.remove('active');
    });

    if (element) {
      element.classList.add('active');
    } else {
      const matchLink = document.querySelector(`#funnelTabs [href="#${tabPaneId}"], #funnelTabs [data-bs-target="#${tabPaneId}"]`);
      if (matchLink) matchLink.classList.add('active');
    }

    // 2. Switch active tab pane
    document.querySelectorAll('#funnelTabsContent .tab-pane').forEach(pane => {
      pane.classList.remove('show', 'active');
    });
    const targetPane = document.getElementById(tabPaneId);
    if (targetPane) {
      targetPane.classList.add('show', 'active');
    }

    // 3. Trigger chart resize if charts tab is viewed
    if (tabPaneId === 'tab-chart') {
      setTimeout(() => {
        if (chartMain) chartMain.resize();
        if (chartConversion) chartConversion.resize();
      }, 60);
    }
  }

  function setDatePreset(preset, btnElement) {
    if (btnElement) {
      document.querySelectorAll('.btn-filter-preset').forEach(b => b.classList.remove('active'));
      btnElement.classList.add('active');
    }

    const today = new Date();
    const endStr = today.toISOString().split('T')[0];
    let startStr = endStr;

    if (preset === 'today') {
      startStr = endStr;
    } else if (preset === 'yesterday') {
      const y = new Date(today);
      y.setDate(y.getDate() - 1);
      startStr = y.toISOString().split('T')[0];
    } else if (preset === '7days') {
      const d = new Date(today);
      d.setDate(d.getDate() - 7);
      startStr = d.toISOString().split('T')[0];
    } else if (preset === '30days') {
      const d = new Date(today);
      d.setDate(d.getDate() - 30);
      startStr = d.toISOString().split('T')[0];
    } else if (preset === 'all') {
      startStr = '';
    }

    document.getElementById('filterStartDate').value = startStr;
    document.getElementById('filterEndDate').value = endStr;

    refreshFunnelData();
  }

  function changeFunnel(funnelKey) {
    currentFunnelKey = funnelKey;
    refreshFunnelData();
  }

  function getActiveFilterParams() {
    return {
      funnel_key: currentFunnelKey,
      start_date: document.getElementById('filterStartDate').value,
      end_date: document.getElementById('filterEndDate').value,
      platform: document.getElementById('filterPlatform').value,
      customer_type: document.getElementById('filterCustomerType').value,
      service_id: document.getElementById('filterService').value,
      city: document.getElementById('filterCity').value,
    };
  }

  function refreshFunnelData() {
    const params = getActiveFilterParams();
    const queryStr = new URLSearchParams(params).toString();

    // Show loading state in funnel track
    document.getElementById('funnelTrackContainer').innerHTML = `
      <div class="text-center w-100 py-5">
        <div class="spinner-border text-primary" role="status"></div>
        <div class="text-muted mt-2">Computing funnel flow metrics...</div>
      </div>
    `;

    fetch(`<?= base_url('analytics/funnel-data') ?>?${queryStr}`)
      .then(r => r.json())
      .then(res => {
        if (res.status !== 'success') {
          alert(res.message || 'Error calculating funnel data.');
          return;
        }

        renderKPIs(res.kpis);
        renderFunnelTrack(res.steps, res.kpis);
        renderFunnelTable(res.steps);
        renderCharts(res.chart);
      })
      .catch(err => {
        console.error(err);
        document.getElementById('funnelTrackContainer').innerHTML = `
          <div class="alert alert-danger w-100 mb-0">Failed to load funnel data. Please check network/console.</div>
        `;
      });
  }

  function renderKPIs(kpis) {
    document.getElementById('kpiEntrants').innerText = (kpis.total_entrants || 0).toLocaleString();
    document.getElementById('kpiCompleted').innerText = (kpis.total_completed || 0).toLocaleString();
    document.getElementById('kpiConversion').innerText = (kpis.overall_conversion_rate || 0) + '%';

    if (kpis.biggest_dropoff_step) {
      document.getElementById('kpiBottleneck').innerText = kpis.biggest_dropoff_step.name;
      document.getElementById('kpiBottleneckRate').innerText = `${kpis.biggest_dropoff_step.dropoff_pct}% Drop-off (${kpis.biggest_dropoff_step.dropped.toLocaleString()} users)`;
    } else {
      document.getElementById('kpiBottleneck').innerText = 'None';
      document.getElementById('kpiBottleneckRate').innerText = 'Healthy Flow';
    }
  }

  function renderFunnelTrack(steps, kpis) {
    if (!steps || steps.length === 0) {
      document.getElementById('funnelTrackContainer').innerHTML = `
        <div class="text-center w-100 py-4 text-muted">No telemetry events found for this filter criteria.</div>
      `;
      return;
    }

    let html = '';
    const maxUsers = steps[0].users_reached || 1;

    steps.forEach((s, idx) => {
      const isFirst = (idx === 0);
      const isLast = (idx === steps.length - 1);
      const pctOfMax = Math.round((s.users_reached / maxUsers) * 100);

      html += `
        <div class="funnel-step-node ${isFirst ? 'first-node' : ''} ${isLast ? 'last-node' : ''}">
          <div>
            <div class="node-header">
              <div class="node-num">${idx + 1}</div>
              <span class="badge ${isLast ? 'badge-success' : 'badge-light border'} font-weight-bold">
                ${s.overall_conversion_pct}% Overall
              </span>
            </div>
            <div class="node-title" title="${s.name}">
              <i class="${s.icon || 'fas fa-circle'} text-primary mr-1"></i> ${s.name}
            </div>
            <div class="node-event" title="${s.event_name}">${s.event_name}</div>
            <div class="node-val">${s.users_reached.toLocaleString()}</div>
          </div>

          <div>
            <div class="node-progress-wrapper">
              <div class="node-progress-bar" style="width: ${pctOfMax}%;"></div>
            </div>
            <div class="node-metrics">
              <span>Next: <strong class="text-success">${s.next_conversion_pct}%</strong></span>
              ${!isLast ? `<span>Drop: <strong class="text-danger">${s.dropoff_pct}%</strong></span>` : '<span class="text-success font-weight-bold">Goal!</span>'}
            </div>
          </div>
        </div>
      `;

      // Add connector arrow between steps
      if (!isLast) {
        html += `
          <div class="funnel-connector">
            <span class="connector-time" title="Average transition time">${s.avg_time}</span>
            <div class="connector-arrow"><i class="fas fa-arrow-right"></i></div>
            <span class="connector-dropoff" title="Users dropped at this step">-${s.dropped_users}</span>
          </div>
        `;
      }
    });

    document.getElementById('funnelTrackContainer').innerHTML = html;
  }

  function renderFunnelTable(steps) {
    if (!steps || steps.length === 0) {
      document.getElementById('funnelTableBody').innerHTML = `
        <tr><td colspan="10" class="text-center py-4 text-muted">No step data available.</td></tr>
      `;
      return;
    }

    let html = '';
    steps.forEach((s, idx) => {
      const isLast = (idx === steps.length - 1);
      html += `
        <tr>
          <td><span class="font-weight-bold text-muted">${idx + 1}</span></td>
          <td>
            <div class="font-weight-bold text-dark"><i class="${s.icon} text-primary mr-1"></i> ${s.name}</div>
            <span class="text-muted font-monospace small">${s.event_name}</span>
          </td>
          <td><span class="badge badge-light border">${s.screen || 'App'}</span></td>
          <td class="text-right font-weight-bold">${s.users_reached.toLocaleString()}</td>
          <td class="text-right text-success font-weight-bold">${s.users_completed.toLocaleString()}</td>
          <td class="text-right text-danger font-weight-bold">${s.dropped_users.toLocaleString()}</td>
          <td class="text-center">
            ${!isLast ? `<span class="badge-dropoff">${s.dropoff_pct}%</span>` : '<span class="text-muted">—</span>'}
          </td>
          <td class="text-center">
            <span class="badge-conversion">${s.next_conversion_pct}%</span>
          </td>
          <td class="text-center">
            <span class="badge-time">${s.avg_time}</span>
          </td>
          <td class="text-center">
            ${!isLast && s.dropped_users > 0 ? `
              <button class="btn btn-xs btn-outline-danger font-weight-bold" onclick="inspectDroppedUsers('${s.step_key}', '${s.name}')">
                <i class="fas fa-search-minus mr-1"></i> Inspect (${s.dropped_users})
              </button>
            ` : '<span class="text-muted small">—</span>'}
          </td>
        </tr>
      `;
    });

    document.getElementById('funnelTableBody').innerHTML = html;
  }

  function renderCharts(chartData) {
    if (!chartData || !chartData.labels || chartData.labels.length === 0) return;

    // 1. Funnel Main Bar Chart
    const ctxMain = document.getElementById('funnelChart').getContext('2d');
    if (chartMain) chartMain.destroy();

    chartMain = new Chart(ctxMain, {
      type: 'bar',
      data: {
        labels: chartData.labels,
        datasets: [{
          label: 'Users Reached',
          data: chartData.data,
          backgroundColor: '#6366f1',
          borderRadius: 8,
          hoverBackgroundColor: '#4f46e5',
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: (ctx) => ` Users: ${ctx.parsed.y.toLocaleString()}`
            }
          }
        },
        scales: {
          x: {
            grid: { display: false },
            ticks: { font: { size: 10 } }
          },
          y: {
            beginAtZero: true,
            grid: { color: '#f1f5f9' }
          }
        }
      }
    });

    // 2. Conversion vs Dropoff Chart
    const ctxConv = document.getElementById('conversionChart').getContext('2d');
    if (chartConversion) chartConversion.destroy();

    chartConversion = new Chart(ctxConv, {
      type: 'line',
      data: {
        labels: chartData.labels,
        datasets: [
          {
            label: 'Conversion %',
            data: chartData.conversion,
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            tension: 0.3,
            fill: true,
          },
          {
            label: 'Drop-off %',
            data: chartData.dropoff,
            borderColor: '#ef4444',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            tension: 0.3,
            fill: true,
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: 'top' },
        },
        scales: {
          y: {
            beginAtZero: true,
            max: 100,
            ticks: { callback: (v) => v + '%' }
          }
        }
      }
    });
  }

  function openModal(id) {
    const el = document.getElementById(id);
    if (!el) return;
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
      const m = bootstrap.Modal.getOrCreateInstance(el);
      m.show();
    } else if (typeof $ !== 'undefined' && typeof $.fn.modal !== 'undefined') {
      $(el).modal('show');
    }
  }

  function closeModal(id) {
    const el = document.getElementById(id);
    if (!el) return;
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
      const m = bootstrap.Modal.getInstance(el);
      if (m) m.hide();
    } else if (typeof $ !== 'undefined' && typeof $.fn.modal !== 'undefined') {
      $(el).modal('hide');
    }
  }

  function inspectDroppedUsers(stepKey, stepName) {
    document.getElementById('dropoffModalSubtitle').innerText = `Drop-off at "${stepName}"`;
    document.getElementById('droppedUsersTableBody').innerHTML = `
      <tr><td colspan="5" class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm text-danger mr-2"></div> Loading users...</td></tr>
    `;
    openModal('droppedUsersModal');

    const params = getActiveFilterParams();
    params.step_key = stepKey;
    const queryStr = new URLSearchParams(params).toString();

    fetch(`<?= base_url('analytics/dropoff-users') ?>?${queryStr}`)
      .then(r => r.json())
      .then(res => {
        if (res.status !== 'success' || !res.users || res.users.length === 0) {
          document.getElementById('droppedUsersTableBody').innerHTML = `
            <tr><td colspan="5" class="text-center py-4 text-muted">No specific user records found for this drop-off.</td></tr>
          `;
          return;
        }

        let html = '';
        res.users.forEach(u => {
          html += `
            <tr>
              <td>
                <div class="font-weight-bold text-dark">${u.user_name}</div>
                <span class="text-muted small">${u.user_email || 'No email'}</span>
              </td>
              <td>
                <span class="badge badge-light border text-dark font-weight-bold">${u.user_phone || '—'}</span>
              </td>
              <td>
                <span class="badge badge-primary mr-1">${u.platform}</span>
                <span class="text-muted small">${u.device} (${u.app_version})</span>
              </td>
              <td>${u.city || '—'}</td>
              <td class="text-muted small">${u.last_active_at}</td>
            </tr>
          `;
        });

        document.getElementById('droppedUsersTableBody').innerHTML = html;
        document.getElementById('droppedUsersCountText').innerText = `Showing ${res.count} dropped users / sessions`;
      })
      .catch(err => {
        console.error(err);
        document.getElementById('droppedUsersTableBody').innerHTML = `
          <tr><td colspan="5" class="text-center py-4 text-danger">Error loading dropped users.</td></tr>
        `;
      });
  }

  function loadProblemSignals() {
    const params = getActiveFilterParams();
    const queryStr = new URLSearchParams(params).toString();

    document.getElementById('signalsContainer').innerHTML = `
      <div class="text-center py-4">
        <div class="spinner-border text-danger" role="status"></div>
        <div class="text-muted mt-2">Analyzing problem & rage signals...</div>
      </div>
    `;

    fetch(`<?= base_url('analytics/problem-signals') ?>?${queryStr}`)
      .then(r => r.json())
      .then(res => {
        if (res.status !== 'success' || !res.data) {
          document.getElementById('signalsContainer').innerHTML = '<div class="alert alert-warning">No signals found.</div>';
          return;
        }

        const data = res.data;
        document.getElementById('problemSignalsCount').innerText = data.total_signals || 0;

        if (data.signals.length === 0) {
          document.getElementById('signalsContainer').innerHTML = `
            <div class="text-center py-5 text-success">
              <i class="fas fa-check-circle fa-2x mb-2"></i>
              <div class="font-weight-bold">Zero Critical Friction Detected</div>
              <span class="text-muted small">No rage clicks, validation drops, or payment errors encountered in this window.</span>
            </div>
          `;
          return;
        }

        let html = `
          <div class="alert alert-soft-danger d-flex align-items-center mb-4">
            <i class="fas fa-exclamation-circle fa-2x text-danger mr-3"></i>
            <div>
              <strong class="text-danger">High Friction Bottlenecks Identified!</strong><br>
              <span class="text-dark small">Sessions encountering the errors below show high correlated exit rates before completing booking.</span>
            </div>
          </div>
        `;

        data.signals.forEach(sig => {
          const badgeClass = sig.severity === 'danger' ? 'badge-danger' : (sig.severity === 'warning' ? 'badge-warning' : 'badge-info');
          html += `
            <div class="signal-card">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <div>
                  <span class="badge ${badgeClass} text-uppercase font-weight-bold mr-2">${sig.event_name.replace('_', ' ')}</span>
                  <span class="font-weight-bold text-dark">${sig.screen_name}</span>
                </div>
                <div>
                  <span class="badge badge-danger font-weight-bold px-2 py-1" style="font-size: 0.85rem;">
                    ${sig.exit_rate_pct}% Exit Rate
                  </span>
                </div>
              </div>
              <div class="text-muted mb-2 font-monospace" style="font-size: 0.84rem;">
                <i class="fas fa-info-circle text-muted mr-1"></i> Error: "${sig.error_message}"
              </div>
              <div class="d-flex align-items-center justify-content-between small text-muted border-top pt-2 mt-2">
                <span>Impact: <strong>${sig.count}</strong> occurrences across <strong>${sig.unique_sessions}</strong> unique sessions</span>
                <span>${sig.dropped_count} users dropped immediately after this error</span>
              </div>
            </div>
          `;
        });

        document.getElementById('signalsContainer').innerHTML = html;
      })
      .catch(err => {
        console.error(err);
        document.getElementById('signalsContainer').innerHTML = '<div class="alert alert-danger">Error loading problem signals.</div>';
      });
  }

  function openEventsStreamModal() {
    openModal('eventsStreamModal');
    loadEventsStream();
  }

  function loadEventsStream() {
    const search = document.getElementById('streamSearchInput').value;
    const params = getActiveFilterParams();
    params.search = search;
    const queryStr = new URLSearchParams(params).toString();

    document.getElementById('eventsStreamTableBody').innerHTML = `
      <tr><td colspan="7" class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm text-info mr-2"></div> Loading live event stream...</td></tr>
    `;

    fetch(`<?= base_url('analytics/events-stream') ?>?${queryStr}`)
      .then(r => r.json())
      .then(res => {
        if (res.status !== 'success' || !res.events || res.events.length === 0) {
          document.getElementById('eventsStreamTableBody').innerHTML = `
            <tr><td colspan="7" class="text-center py-4 text-muted">No events found.</td></tr>
          `;
          return;
        }

        let html = '';
        res.events.forEach(ev => {
          let metaStr = '—';
          if (ev.metadata_decoded) {
            metaStr = `<pre class="mb-0 font-size-11" style="max-height: 45px; overflow: hidden;">${JSON.stringify(ev.metadata_decoded)}</pre>`;
          }
          html += `
            <tr>
              <td class="text-muted small">${ev.created_at}</td>
              <td><span class="badge badge-soft-primary font-weight-bold text-primary">${ev.event_name}</span></td>
              <td>${ev.screen_name || '—'}</td>
              <td><span class="badge badge-light border">${ev.platform}</span></td>
              <td class="font-monospace small text-truncate" style="max-width: 120px;" title="${ev.session_id}">${ev.session_id}</td>
              <td>${ev.user_id ? `<span class="badge badge-info">#${ev.user_id}</span>` : '<span class="text-muted">guest</span>'}</td>
              <td>${metaStr}</td>
            </tr>
          `;
        });

        document.getElementById('eventsStreamTableBody').innerHTML = html;
      })
      .catch(err => {
        console.error(err);
        document.getElementById('eventsStreamTableBody').innerHTML = `
          <tr><td colspan="7" class="text-center py-4 text-danger">Failed to load event stream.</td></tr>
        `;
      });
  }

  function openFunnelModal() {
    document.getElementById('funnelConfigForm').reset();
    document.getElementById('cfgFunnelId').value = '';
    document.getElementById('cfgStepsContainer').innerHTML = '';

    // Add 4 default empty rows
    addConfigStepRow('Step 1', 'registration_started', 'RegisterScreen', 'fas fa-user-plus');
    addConfigStepRow('Step 2', 'service_selected', 'ServiceScreen', 'fas fa-check-circle');
    addConfigStepRow('Step 3', 'checkout_viewed', 'CheckoutScreen', 'fas fa-shopping-cart');
    addConfigStepRow('Step 4', 'payment_success', 'SuccessScreen', 'fas fa-check-double');

    openModal('funnelConfigModal');
  }

  function addConfigStepRow(name = '', eventName = '', screen = '', icon = 'fas fa-arrow-right') {
    const container = document.getElementById('cfgStepsContainer');
    const idx = container.children.length + 1;
    const row = document.createElement('div');
    row.className = 'card card-body p-2 mb-2 bg-light border step-row';
    row.innerHTML = `
      <div class="row align-items-center">
        <div class="col-1 text-center font-weight-bold text-muted">${idx}</div>
        <div class="col-4">
          <input type="text" class="form-control form-control-sm cfg-step-name" placeholder="Step Name" value="${name}" required>
        </div>
        <div class="col-4">
          <input type="text" class="form-control form-control-sm cfg-step-event" placeholder="Event Name (e.g. otp_verified)" value="${eventName}" required>
        </div>
        <div class="col-2">
          <input type="text" class="form-control form-control-sm cfg-step-screen" placeholder="Screen" value="${screen}">
        </div>
        <div class="col-1 text-center">
          <button type="button" class="btn btn-xs btn-link text-danger" onclick="this.closest('.step-row').remove()"><i class="fas fa-trash"></i></button>
        </div>
      </div>
    `;
    container.appendChild(row);
  }

  function saveFunnelConfiguration() {
    const name = document.getElementById('cfgName').value.trim();
    const key = document.getElementById('cfgFunnelKey').value.trim();
    const category = document.getElementById('cfgCategory').value;
    const inactivity = document.getElementById('cfgInactivity').value;

    const rows = document.querySelectorAll('#cfgStepsContainer .step-row');
    const steps = [];
    rows.forEach((r, idx) => {
      const stepName = r.querySelector('.cfg-step-name').value.trim();
      const stepEvent = r.querySelector('.cfg-step-event').value.trim();
      const stepScreen = r.querySelector('.cfg-step-screen').value.trim();
      if (stepName && stepEvent) {
        steps.push({
          step_key: 'step_' + (idx + 1),
          name: stepName,
          event_name: stepEvent,
          screen: stepScreen,
          icon: 'fas fa-chevron-circle-right'
        });
      }
    });

    if (!name || !key || steps.length < 2) {
      alert('Please enter a funnel name, key, and at least 2 valid steps.');
      return;
    }

    const formData = new FormData();
    formData.append('name', name);
    formData.append('funnel_key', key);
    formData.append('category', category);
    formData.append('inactivity_window_minutes', inactivity);
    formData.append('steps', JSON.stringify(steps));

    fetch('<?= base_url('analytics/funnels/save') ?>', {
      method: 'POST',
      body: formData
    })
      .then(r => r.json())
      .then(res => {
        if (res.status === 'success') {
          alert(res.message);
          closeModal('funnelConfigModal');
          window.location.href = `<?= base_url('customer-journey') ?>?funnel=${res.funnel_key}`;
        } else {
          alert(res.message || 'Failed to save funnel.');
        }
      })
      .catch(err => {
        console.error(err);
        alert('Server error saving funnel.');
      });
  }

  function generateDemoDataPrompt() {
    if (confirm('Generate realistic test customer journeys across Android, iOS, and Web with simulated drop-offs and error signals?')) {
      const formData = new FormData();
      formData.append('days', 14);
      formData.append('journeys', 180);

      fetch('<?= base_url('analytics/generate-sample-data') ?>', {
        method: 'POST',
        body: formData
      })
        .then(r => r.json())
        .then(res => {
          if (res.status === 'success') {
            alert(`Generated ${res.total_events} events across ${res.total_journeys} user journeys over ${res.days_covered} days!`);
            refreshFunnelData();
          } else {
            alert(res.message || 'Failed to generate demo data.');
          }
        })
        .catch(err => {
          console.error(err);
          alert('Error generating demo data.');
        });
    }
  }
</script>

<?php echo $this->endSection() ?>
