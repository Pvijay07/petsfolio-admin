<?= $this->extend('admin/includes/header'); ?>
<!--start content-->
<?= $this->section('content'); ?>
<<<<<<< HEAD
=======
<?php
$totalLogs     = $totalLogs ?? 0;
$logs          = $logs ?? [];
$filters       = $filters ?? [];
$page          = $page ?? 1;
$limit         = $limit ?? 50;
$totalPages    = $totalPages ?? 1;
$summaryCounts = $summaryCounts ?? [];

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

$currentCategory     = $filters['type_category'] ?? '';
$currentUserType     = $filters['user_type'] ?? '';
$currentStatus       = $filters['status'] ?? '';
$currentService      = $filters['service'] ?? '';
$currentBookingId    = $filters['booking_id'] ?? '';
$currentBookingEvent = $filters['booking_event'] ?? '';
$isAll               = empty($currentCategory) && empty($currentUserType) && empty($currentStatus) && empty($filters['search']) && empty($currentService) && empty($currentBookingId);
$isBooking           = $currentCategory === 'booking' || !empty($currentService) || !empty($currentBookingId) || !empty($currentBookingEvent);
$isClients           = $currentUserType === 'user';
$isProviders         = $currentUserType === 'provider';
$isFailed            = $currentStatus === 'failed';
?>

<style>
  /* Modern Dashboard UI Styles */
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
  .nl-input-date {
    padding: 7px 10px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #f8fafc;
    font-size: 12.5px;
    color: #334155;
    font-weight: 500;
    transition: all 0.15s ease;
    height: 38px;
  }
  .nl-input-date:focus {
    border-color: #6366f1;
    background: #ffffff;
    outline: none;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
  }
  .nl-date-pill {
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 11.5px;
    font-weight: 600;
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
    cursor: pointer;
    transition: all 0.15s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
    user-select: none;
  }
  .nl-date-pill:hover, .nl-date-pill.active {
    background: #e0e7ff;
    color: #4338ca;
    border-color: #c7d2fe;
  }

  /* Main Table Card */
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

  /* Badges */
  .badge-channel {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 600;
  }
  .badge-channel.push { background: #eff6ff; color: #2563eb; border: 1px solid #dbeafe; }
  .badge-channel.sms { background: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7; }
  .badge-channel.whatsapp { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
  .badge-channel.email { background: #f8fafc; color: #0284c7; border: 1px solid #e0f2fe; }

  .status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 11px;
    border-radius: 50px;
    font-size: 11.5px;
    font-weight: 600;
  }
  .status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
  }
  .status-delivered { background: #dcfce7; color: #15803d; }
  .status-delivered .status-dot { background: #16a34a; }
  .status-sent { background: #ede9fe; color: #6d28d9; }
  .status-sent .status-dot { background: #7c3aed; }
  .status-failed { background: #fee2e2; color: #b91c1c; }
  .status-failed .status-dot { background: #dc2626; }
  .status-queued { background: #f1f5f9; color: #475569; }
  .status-queued .status-dot { background: #64748b; }
  .status-opened { background: #e0f2fe; color: #0369a1; }
  .status-opened .status-dot { background: #0284c7; }
  .status-clicked { background: #fef3c7; color: #b45309; }
  .status-clicked .status-dot { background: #d97706; }

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
  }
  .nl-page-btn:hover:not(.active):not(.disabled) {
    background: #f1f5f9;
    color: #0f172a;
  }
  .nl-page-btn.active {
    background: #4f46e5;
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(79, 70, 229, 0.3);
  }
  .nl-page-btn.disabled {
    color: #cbd5e1;
    pointer-events: none;
  }

  .nl-action-circle {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #4f46e5;
    box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    transition: all 0.15s ease;
  }
  .nl-action-circle:hover {
    background: #4f46e5;
    color: #ffffff;
    border-color: #4f46e5;
    transform: scale(1.05);
  }
</style>
>>>>>>> origin/main

<main class="main-content">
  <div class="position-relative iq-banner">
    <?= $this->include('admin/nav_bar') ?>
  </div>
<<<<<<< HEAD
  <div class="container-fluid px-4 content-inner pb-0" id="page_layout">

    <!-- Page Header -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
          <div>
            <h4 class="fw-bold mb-1">Notification Logs</h4>
            <p class="text-muted mb-0">
              Track all sent notifications
              <?php if (!empty($filters['campaign_id'])): ?>
                — <span class="badge bg-primary rounded-pill">Campaign #<?= $filters['campaign_id'] ?></span>
              <?php endif; ?>
            </p>
          </div>
          <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
            <i class="bi bi-database me-1"></i> <?= number_format($totalLogs) ?> total records
          </span>
        </div>
      </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
      <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-body p-3">
            <form method="get" class="row g-2 align-items-end">
              <div class="col-md-2">
                <label class="form-label small fw-semibold">Channel</label>
                <select name="channel" class="form-select form-select-sm">
                  <option value="">All</option>
                  <?php foreach (['push','sms','email','whatsapp'] as $c): ?>
                    <option value="<?= $c ?>" <?= ($filters['channel'] ?? '') === $c ? 'selected' : '' ?>><?= ucfirst($c) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-2">
                <label class="form-label small fw-semibold">Status</label>
                <select name="status" class="form-select form-select-sm">
                  <option value="">All</option>
                  <?php foreach (['queued','sent','delivered','failed','opened','clicked'] as $s): ?>
                    <option value="<?= $s ?>" <?= ($filters['status'] ?? '') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-2">
                <label class="form-label small fw-semibold">User Type</label>
                <select name="user_type" class="form-select form-select-sm">
                  <option value="">All</option>
                  <option value="user" <?= ($filters['user_type'] ?? '') === 'user' ? 'selected' : '' ?>>Client</option>
                  <option value="provider" <?= ($filters['user_type'] ?? '') === 'provider' ? 'selected' : '' ?>>Provider</option>
                </select>
              </div>
              <div class="col-md-2">
                <label class="form-label small fw-semibold">From</label>
                <input type="date" name="date_from" class="form-control form-control-sm" value="<?= esc($filters['date_from'] ?? '') ?>" style="padding:0.375rem 0.75rem;font-family:inherit;background:white;">
              </div>
              <div class="col-md-2">
                <label class="form-label small fw-semibold">To</label>
                <input type="date" name="date_to" class="form-control form-control-sm" value="<?= esc($filters['date_to'] ?? '') ?>" style="padding:0.375rem 0.75rem;font-family:inherit;background:white;">
              </div>
              <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary px-3"><i class="bi bi-search me-1"></i>Filter</button>
                <a href="<?= base_url('notification-manager/logs') ?>" class="btn btn-sm btn-outline-secondary">Clear</a>
              </div>

              <?php if (!empty($filters['campaign_id'])): ?>
                <input type="hidden" name="campaign_id" value="<?= $filters['campaign_id'] ?>">
              <?php endif; ?>
            </form>
=======

  <div class="container-fluid px-4 content-inner pb-5" id="page_layout">

    <!-- Hero Banner with Stats -->
    <div class="nl-hero-banner">
      <div class="row align-items-center g-4">
        <div class="col-lg-7">
          <div class="nl-live-pill">
            <span class="nl-live-dot"></span>
            <span>Live Dispatch Stream</span>
          </div>
          <h2 class="fw-bold text-white mb-2" style="font-size: 30px; letter-spacing: -0.02em;">Notification Logs</h2>
          <p class="mb-0 text-white text-opacity-75" style="font-size: 13.5px; max-width: 560px; line-height: 1.55;">
            Track and monitor real-time push, SMS, email, and WhatsApp dispatches, rapidly verify delivery status, and inspect notification payload details.
            <?php if (!empty($filters['campaign_id'])): ?>
              <span class="badge bg-white text-primary rounded-pill ms-2 px-2 py-1">Campaign #<?= esc($filters['campaign_id']) ?></span>
            <?php endif; ?>
          </p>
        </div>

        <div class="col-lg-5">
          <div class="d-flex align-items-center gap-2 flex-wrap justify-content-lg-end">
            <!-- Total Logs -->
            <div class="nl-stat-card">
              <span class="label">Total Logs</span>
              <div class="value"><?= number_format($summaryCounts['all'] ?? $totalLogs) ?></div>
            </div>
            <!-- Delivered -->
            <div class="nl-stat-card">
              <span class="label">Delivered</span>
              <div class="value" style="color: #6ee7b7;"><?= number_format($summaryCounts['delivered'] ?? 0) ?></div>
            </div>
            <!-- Dispatched Today -->
            <div class="nl-stat-card">
              <span class="label">Today</span>
              <div class="value" style="color: #93c5fd;"><?= number_format($summaryCounts['today'] ?? 0) ?></div>
            </div>
            <!-- Failed -->
            <div class="nl-stat-card">
              <span class="label">Failed</span>
              <div class="value" style="color: <?= ($summaryCounts['failed'] ?? 0) > 0 ? '#fca5a5' : '#ffffff' ?>;">
                <?= number_format($summaryCounts['failed'] ?? 0) ?>
              </div>
            </div>
>>>>>>> origin/main
          </div>
        </div>
      </div>
    </div>

<<<<<<< HEAD
    <!-- Logs Table -->
    <div class="row">
      <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-body p-4">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                  <tr>
                    <th class="border-0 rounded-start py-3 ps-3">#</th>
                    <th class="border-0 py-3">Recipient</th>
                    <th class="border-0 py-3">Channel</th>
                    <th class="border-0 py-3">Title</th>
                    <th class="border-0 py-3">Body</th>
                    <th class="border-0 py-3">Status</th>
                    <th class="border-0 py-3">Campaign</th>
                    <th class="border-0 rounded-end py-3">Sent At</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($logs)): ?>
                    <?php
                    $offset = ($page - 1) * 50;
                    $i = $offset + 1;
                    foreach ($logs as $log):
                    ?>
                      <tr>
                        <td class="ps-3"><?= $i++ ?></td>
                        <td>
                          <div class="fw-semibold small"><?= esc($log->user_name ?: 'Unknown') ?></div>
                          <small class="text-muted">
                            <?= ucfirst($log->user_type) ?>
                            #<?= $log->user_type === 'user' ? ($log->user_id ?? '—') : ($log->provider_id ?? '—') ?>
                          </small>
                        </td>
                        <td>
                          <?php
                          $chColors = ['push' => 'primary', 'sms' => 'success', 'email' => 'info', 'whatsapp' => 'success'];
                          $chIcons = ['push' => 'bi-phone', 'sms' => 'bi-chat-dots', 'email' => 'bi-envelope', 'whatsapp' => 'bi-whatsapp'];
                          ?>
                          <span class="badge bg-<?= $chColors[$log->channel] ?? 'secondary' ?> bg-opacity-10 text-<?= $chColors[$log->channel] ?? 'secondary' ?> rounded-pill">
                            <i class="bi <?= $chIcons[$log->channel] ?? 'bi-bell' ?> me-1"></i><?= ucfirst($log->channel) ?>
                          </span>
                        </td>
                        <td>
                          <span class="small fw-semibold" title="<?= esc($log->title) ?>"><?= esc(mb_substr($log->title, 0, 30)) ?><?= mb_strlen($log->title) > 30 ? '...' : '' ?></span>
                        </td>
                        <td>
                          <span class="text-muted small" title="<?= esc($log->body) ?>"><?= esc(mb_substr($log->body ?? '', 0, 35)) ?><?= mb_strlen($log->body ?? '') > 35 ? '...' : '' ?></span>
                        </td>
                        <td>
                          <?php
                          $statusIcons = [
                            'queued' => ['bi-clock', 'secondary'],
                            'sent' => ['bi-check', 'primary'],
                            'delivered' => ['bi-check2-all', 'success'],
                            'failed' => ['bi-x-circle', 'danger'],
                            'opened' => ['bi-eye', 'info'],
                            'clicked' => ['bi-cursor', 'warning'],
                          ];
                          $si = $statusIcons[$log->status] ?? ['bi-question', 'secondary'];
                          ?>
                          <span class="badge bg-<?= $si[1] ?> rounded-pill">
                            <i class="bi <?= $si[0] ?> me-1"></i><?= ucfirst($log->status) ?>
                          </span>
                          <?php if ($log->error_message): ?>
                            <i class="bi bi-exclamation-triangle text-danger ms-1" title="<?= esc($log->error_message) ?>" style="cursor:help;"></i>
                          <?php endif; ?>
                        </td>
                        <td>
                          <?php if ($log->campaign_id): ?>
                            <a href="<?= base_url('notification-manager/logs?campaign_id=' . $log->campaign_id) ?>" class="badge bg-light text-dark border rounded-pill text-decoration-none">
                              #<?= $log->campaign_id ?>
                            </a>
                          <?php else: ?>
                            <span class="text-muted small">Direct</span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <small class="text-muted">
                            <?= $log->sent_at ? date('M d, h:i A', strtotime($log->sent_at)) : ($log->created_at ? date('M d, h:i A', strtotime($log->created_at)) : '—') ?>
                          </small>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="8" class="text-center py-5 text-muted">
                        <i class="bi bi-journal-text fs-1 d-block mb-2 opacity-25"></i>
                        No notification logs found.
                      </td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <?php if (($totalPages ?? 0) > 1): ?>
              <nav class="mt-4">
                <ul class="pagination justify-content-center mb-0">
                  <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?<?= http_build_query(array_merge($filters, ['page' => $page - 1])) ?>">
                      <i class="bi bi-chevron-left"></i>
                    </a>
                  </li>

                  <?php
                  $start = max(1, $page - 2);
                  $end = min($totalPages, $page + 2);
                  for ($p = $start; $p <= $end; $p++):
                  ?>
                    <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                      <a class="page-link" href="?<?= http_build_query(array_merge($filters, ['page' => $p])) ?>"><?= $p ?></a>
                    </li>
                  <?php endfor; ?>

                  <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?<?= http_build_query(array_merge($filters, ['page' => $page + 1])) ?>">
                      <i class="bi bi-chevron-right"></i>
                    </a>
                  </li>
                </ul>
              </nav>
            <?php endif; ?>

=======
    <!-- Sub-Bar: Segmented Pills on Left & Action Buttons on Right -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
      <!-- Segmented Pill Tabs -->
      <div class="nl-segmented-tabs">
        <a href="<?= base_url('notification-manager/logs') ?>" 
           class="nl-tab-pill <?= $isAll ? 'active' : '' ?>">
          <i class="bi bi-list-ul"></i>
          <span>All Logs</span>
          <span class="nl-tab-badge"><?= number_format($summaryCounts['all'] ?? $totalLogs) ?></span>
        </a>
        <a href="<?= base_url('notification-manager/logs?type_category=booking') ?>" 
           class="nl-tab-pill <?= $isBooking ? 'active' : '' ?>">
          <i class="bi bi-calendar2-check"></i>
          <span>Booking & Quotes</span>
          <span class="nl-tab-badge"><?= number_format($summaryCounts['booking'] ?? 0) ?></span>
        </a>
        <a href="<?= base_url('notification-manager/logs?user_type=provider') ?>" 
           class="nl-tab-pill <?= $isProviders ? 'active' : '' ?>">
          <i class="bi bi-person-badge"></i>
          <span>Service Providers</span>
          <span class="nl-tab-badge"><?= number_format($summaryCounts['providers'] ?? 0) ?></span>
        </a>
        <a href="<?= base_url('notification-manager/logs?user_type=user') ?>" 
           class="nl-tab-pill <?= $isClients ? 'active' : '' ?>">
          <i class="bi bi-person"></i>
          <span>Clients</span>
          <span class="nl-tab-badge"><?= number_format($summaryCounts['clients'] ?? 0) ?></span>
        </a>
        <a href="<?= base_url('notification-manager/logs?status=failed') ?>" 
           class="nl-tab-pill <?= $isFailed ? 'active' : '' ?>">
          <i class="bi bi-exclamation-octagon"></i>
          <span>Failed Dispatches</span>
          <span class="nl-tab-badge"><?= number_format($summaryCounts['failed'] ?? 0) ?></span>
        </a>
      </div>

      <!-- Action Buttons -->
      <div class="d-flex align-items-center gap-2">
        <a href="<?= base_url('notification-manager/analytics') ?>" class="nl-btn-action text-primary border-primary-subtle" style="background: #eef2ff;" title="Job Notification Funnel & SP Health Report">
          <i class="bi bi-graph-up-arrow text-primary"></i>
          <span>Analytics & SP Health ⭐</span>
        </a>
        <button type="button" class="nl-btn-action" id="btnExportCSV" title="Export current logs as CSV">
          <i class="bi bi-box-arrow-up"></i>
          <span>Export CSV</span>
        </button>
        <a href="<?= base_url('notification-manager/logs') ?>" class="nl-btn-action" title="Reset all applied filters">
          <i class="bi bi-arrow-clockwise"></i>
          <span>Reset</span>
        </a>
      </div>
    </div>

    <!-- Toolbar & Filter Card -->
    <div class="nl-filter-card">
      <form method="get" action="<?= base_url('notification-manager/logs') ?>" id="formFilterLogs">
        <!-- Row 1: Search, Channel, Status, User Role, Quick Preset -->
        <div class="row g-2 align-items-center mb-2">
          <!-- Search Keyword -->
          <div class="col-lg-4 col-md-12">
            <div class="nl-search-wrap">
              <i class="bi bi-search search-icon"></i>
              <input type="text" name="search" class="nl-search-input" 
                     placeholder="Search recipient, phone, title, message, ID..." 
                     value="<?= esc($filters['search'] ?? '') ?>">
            </div>
          </div>

          <!-- Channel -->
          <div class="col-lg-2 col-md-3 col-sm-6">
            <select name="channel" class="form-select nl-select">
              <option value="">Channel: All</option>
              <?php foreach (['push' => 'Push', 'sms' => 'SMS', 'email' => 'Email', 'whatsapp' => 'WhatsApp'] as $cKey => $cVal): ?>
                <option value="<?= $cKey ?>" <?= ($filters['channel'] ?? '') === $cKey ? 'selected' : '' ?>><?= $cVal ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Status -->
          <div class="col-lg-2 col-md-3 col-sm-6">
            <select name="status" class="form-select nl-select">
              <option value="">Status: All</option>
              <?php foreach (['delivered' => 'Delivered', 'sent' => 'Sent', 'failed' => 'Failed', 'queued' => 'Queued', 'opened' => 'Opened', 'clicked' => 'Clicked'] as $sKey => $sVal): ?>
                <option value="<?= $sKey ?>" <?= ($filters['status'] ?? '') === $sKey ? 'selected' : '' ?>><?= $sVal ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- User Type / Role -->
          <div class="col-lg-2 col-md-3 col-sm-6">
            <select name="user_type" class="form-select nl-select">
              <option value="">Role: All Roles</option>
              <option value="provider" <?= ($filters['user_type'] ?? '') === 'provider' ? 'selected' : '' ?>>Service Provider</option>
              <option value="user" <?= ($filters['user_type'] ?? '') === 'user' ? 'selected' : '' ?>>Client / User</option>
            </select>
          </div>

          <!-- Quick Date Preset Dropdown -->
          <div class="col-lg-2 col-md-3 col-sm-6">
            <select name="date_preset" id="datePresetSelect" class="form-select nl-select">
              <option value="">Date: All Time</option>
              <option value="today" <?= ($filters['date_preset'] ?? '') === 'today' ? 'selected' : '' ?>>Today</option>
              <option value="yesterday" <?= ($filters['date_preset'] ?? '') === 'yesterday' ? 'selected' : '' ?>>Yesterday</option>
              <option value="last_7_days" <?= ($filters['date_preset'] ?? '') === 'last_7_days' ? 'selected' : '' ?>>Last 7 Days</option>
              <option value="last_30_days" <?= ($filters['date_preset'] ?? '') === 'last_30_days' ? 'selected' : '' ?>>Last 30 Days</option>
              <option value="this_month" <?= ($filters['date_preset'] ?? '') === 'this_month' ? 'selected' : '' ?>>This Month</option>
              <option value="last_month" <?= ($filters['date_preset'] ?? '') === 'last_month' ? 'selected' : '' ?>>Last Month</option>
              <option value="custom" <?= (!empty($filters['date_from']) || !empty($filters['date_to'])) && empty($filters['date_preset']) ? 'selected' : '' ?>>Custom Range...</option>
            </select>
          </div>
        </div>

        <!-- Row 2: Date From, Date To, Quick Preset Pills & Submit/Reset -->
        <div class="row g-2 align-items-center pt-2 border-top border-light-subtle">
          <!-- From Date -->
          <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="d-flex align-items-center gap-2">
              <span class="text-muted fw-semibold small" style="font-size: 12px; white-space: nowrap;"><i class="bi bi-calendar-event me-1 text-primary"></i>From:</span>
              <input type="date" name="date_from" id="filterDateFrom" class="form-control nl-input-date" 
                     value="<?= esc($filters['date_from'] ?? '') ?>" 
                     placeholder="YYYY-MM-DD">
            </div>
          </div>

          <!-- To Date -->
          <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="d-flex align-items-center gap-2">
              <span class="text-muted fw-semibold small" style="font-size: 12px; white-space: nowrap;"><i class="bi bi-calendar-check me-1 text-primary"></i>To:</span>
              <input type="date" name="date_to" id="filterDateTo" class="form-control nl-input-date" 
                     value="<?= esc($filters['date_to'] ?? '') ?>" 
                     placeholder="YYYY-MM-DD">
            </div>
          </div>

          <!-- Quick Date Shortcut Pills -->
          <div class="col-lg-4 col-md-8 col-sm-12 d-flex align-items-center gap-1 flex-wrap">
            <button type="button" class="nl-date-pill btn-quick-date <?= ($filters['date_preset'] ?? '') === 'today' ? 'active' : '' ?>" data-preset="today">Today</button>
            <button type="button" class="nl-date-pill btn-quick-date <?= ($filters['date_preset'] ?? '') === 'yesterday' ? 'active' : '' ?>" data-preset="yesterday">Yesterday</button>
            <button type="button" class="nl-date-pill btn-quick-date <?= ($filters['date_preset'] ?? '') === 'last_7_days' ? 'active' : '' ?>" data-preset="last_7_days">7 Days</button>
            <button type="button" class="nl-date-pill btn-quick-date <?= ($filters['date_preset'] ?? '') === 'last_30_days' ? 'active' : '' ?>" data-preset="last_30_days">30 Days</button>
            <button type="button" class="nl-date-pill btn-quick-date <?= ($filters['date_preset'] ?? '') === 'this_month' ? 'active' : '' ?>" data-preset="this_month">This Month</button>
            <button type="button" class="nl-date-pill btn-quick-date <?= ($filters['date_preset'] ?? '') === 'last_month' ? 'active' : '' ?>" data-preset="last_month">Last Month</button>
          </div>

          <!-- Action Buttons -->
          <div class="col-lg-2 col-md-4 col-sm-12 d-flex gap-2 ms-auto">
            <button type="submit" class="btn btn-primary flex-grow-1 rounded-3 py-1 px-3 fw-semibold shadow-xs" style="font-size: 13px; background: #4f46e5; border-color: #4f46e5; height: 38px;">
              <i class="bi bi-funnel me-1"></i>Apply
            </button>
            <a href="<?= base_url('notification-manager/logs') ?>" class="btn btn-outline-secondary rounded-3 py-1 px-2 d-flex align-items-center justify-content-center" style="font-size: 13px; height: 38px; width: 38px;" title="Reset filters">
              <i class="bi bi-arrow-clockwise"></i>
            </a>
          </div>
        </div>

        <?php if (!empty($filters['type_category'])): ?>
          <input type="hidden" name="type_category" value="<?= esc($filters['type_category']) ?>">
        <?php endif; ?>
        <?php if (!empty($filters['campaign_id'])): ?>
          <input type="hidden" name="campaign_id" value="<?= esc($filters['campaign_id']) ?>">
        <?php endif; ?>
        <?php if (!empty($filters['booking_id'])): ?>
          <input type="hidden" name="booking_id" value="<?= esc($filters['booking_id']) ?>">
        <?php endif; ?>
        <?php if (!empty($filters['service'])): ?>
          <input type="hidden" name="service" value="<?= esc($filters['service']) ?>">
        <?php endif; ?>
        <?php if (!empty($filters['booking_event'])): ?>
          <input type="hidden" name="booking_event" value="<?= esc($filters['booking_event']) ?>">
        <?php endif; ?>
      </form>

      <!-- Active Filters Chips -->
      <?php
      $activeFilterBadges = [];
      if (!empty($filters['booking_id']))    $activeFilterBadges['Booking #'] = '#' . ltrim($filters['booking_id'], '#');
      if (!empty($filters['service']))       $activeFilterBadges['Service']   = ucfirst($filters['service']);
      if (!empty($filters['booking_event'])) $activeFilterBadges['Event']     = ucfirst($filters['booking_event']);
      if (!empty($filters['search']))        $activeFilterBadges['Keyword']   = $filters['search'];
      if (!empty($filters['type_category']) && empty($filters['booking_id']) && empty($filters['service'])) {
          $activeFilterBadges['Category'] = ucfirst($filters['type_category']);
      }
      if (!empty($filters['channel']))       $activeFilterBadges['Channel']   = ucfirst($filters['channel']);
      if (!empty($filters['status']))        $activeFilterBadges['Status']    = ucfirst($filters['status']);
      if (!empty($filters['user_type']))     $activeFilterBadges['Role']      = ($filters['user_type'] === 'user' ? 'Client' : 'Provider');
      if (!empty($filters['date_from']) || !empty($filters['date_to'])) {
          if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
              $activeFilterBadges['Date Range'] = date('d M Y', strtotime($filters['date_from'])) . ' - ' . date('d M Y', strtotime($filters['date_to']));
          } elseif (!empty($filters['date_from'])) {
              $activeFilterBadges['From Date'] = date('d M Y', strtotime($filters['date_from']));
          } elseif (!empty($filters['date_to'])) {
              $activeFilterBadges['To Date'] = date('d M Y', strtotime($filters['date_to']));
          }
      } elseif (!empty($filters['date_preset'])) {
          $activeFilterBadges['Date'] = ucwords(str_replace('_', ' ', $filters['date_preset']));
      }
      ?>
      <?php if (!empty($activeFilterBadges)): ?>
        <div class="mt-3 pt-2 border-top d-flex align-items-center flex-wrap gap-2 small">
          <span class="text-muted fw-semibold" style="font-size: 12px;"><i class="bi bi-filter me-1"></i>Active filters:</span>
          <?php foreach ($activeFilterBadges as $k => $v): ?>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill py-1 px-2" style="font-size: 11.5px;">
              <?= $k ?>: <strong><?= esc($v) ?></strong>
            </span>
          <?php endforeach; ?>
          <a href="<?= base_url('notification-manager/logs') ?>" class="text-danger small text-decoration-none ms-2 fw-semibold" style="font-size: 12px;">
            <i class="bi bi-x-circle me-1"></i>Clear all
          </a>
        </div>
      <?php endif; ?>
    </div>

    <!-- Data Table Card -->
    <div class="nl-table-card">
      <div class="table-responsive">
        <table class="table nl-table align-middle" id="logsDataTable">
          <thead>
            <tr>
              <th style="width: 70px;">S.No.</th>
              <th style="min-width: 220px;">Customer / Recipient</th>
              <th style="width: 110px;">Channel</th>
              <th style="min-width: 240px;">Notification Details</th>
              <th style="width: 130px;">Source / Event</th>
              <th style="width: 120px;">Status</th>
              <th style="width: 140px;">Service Date</th>
              <th style="width: 70px;" class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($logs)): ?>
              <?php
              $isProviderType = function ($t) {
                return in_array(strtolower($t ?? ''), ['provider', 'sp', 'service_provider']);
              };

              $rowIdx = (($page - 1) * $limit);
              foreach ($logs as $log):
                $rowIdx++;
                $isProv = $isProviderType($log->user_type);
                $dispId = $isProv ? ($log->provider_id ?: $log->user_id) : $log->user_id;
                $dispName = $log->user_name ?: ($isProv ? 'Provider #' . ($dispId ?: '—') : 'Client #' . ($dispId ?: '—'));
                $dispPhone = $log->user_phone ?: ($log->provider_phone ?? '');
                $avatar = getInitialsAvatar($dispName, $dispId);

                // Detect Booking ID
                $detectedBId = null;
                if (isset($log->booking_id) && !empty($log->booking_id)) {
                    $detectedBId = $log->booking_id;
                } elseif (preg_match('/#(\d+)/', ($log->title ?? '') . ' ' . ($log->body ?? ''), $matches)) {
                    $detectedBId = $matches[1];
                }
              ?>
                <tr id="log_row_<?= $log->id ?>">
                  <!-- S.No. / ID -->
                  <td>
                    <div class="fw-bold text-muted" style="font-size: 13px;">
                      <?= sprintf('%02d', $rowIdx) ?>
                    </div>
                    <small class="text-muted" style="font-size: 10.5px;">#<?= $log->id ?></small>
                  </td>

                  <!-- Recipient -->
                  <td>
                    <div class="d-flex align-items-center gap-3">
                      <div class="nl-avatar" style="background: <?= $avatar['bg'] ?>; color: <?= $avatar['color'] ?>;">
                        <?= $avatar['text'] ?>
                      </div>
                      <div>
                        <div class="fw-bold text-dark text-truncate" style="max-width: 180px;" title="<?= esc($dispName) ?>">
                          <?= esc($dispName) ?>
                        </div>
                        <div class="d-flex align-items-center gap-1 mt-1 text-muted" style="font-size: 11.5px;">
                          <?php if (!empty($dispPhone)): ?>
                            <span><i class="bi bi-telephone text-muted me-1"></i><?= esc($dispPhone) ?></span>
                            <span class="mx-1">•</span>
                          <?php endif; ?>
                          <span class="badge <?= $isProv ? 'bg-info-subtle text-dark border border-info-subtle' : 'bg-secondary-subtle text-secondary border border-secondary-subtle' ?> rounded-pill" style="font-size: 10px; padding: 2px 7px;">
                            <?= $isProv ? 'Provider' : 'Client' ?> #<?= $dispId ?: '—' ?>
                          </span>
                        </div>
                      </div>
                    </div>
                  </td>

                  <!-- Channel -->
                  <td>
                    <?php
                    $cName = strtolower($log->channel ?? 'push');
                    $chIcons = ['push' => 'bi-phone', 'sms' => 'bi-chat-dots', 'email' => 'bi-envelope', 'whatsapp' => 'bi-whatsapp'];
                    ?>
                    <span class="badge-channel <?= $cName ?>">
                      <i class="bi <?= $chIcons[$cName] ?? 'bi-bell' ?>"></i>
                      <?= ucfirst($cName) ?>
                    </span>
                  </td>

                  <!-- Title & Body -->
                  <td>
                    <div class="fw-semibold text-dark text-truncate mb-1" style="max-width: 260px;" title="<?= esc($log->title) ?>">
                      <?= esc($log->title) ?>
                    </div>
                    <div class="text-muted text-truncate" style="max-width: 280px; font-size: 12px;" title="<?= esc($log->body ?? '') ?>">
                      <?= esc($log->body ?? '—') ?>
                    </div>
                  </td>

                  <!-- Source / Event -->
                  <td>
                    <?php if (!empty($log->campaign_id)): ?>
                      <a href="<?= base_url('notification-manager/logs?campaign_id=' . $log->campaign_id) ?>"
                         class="badge bg-light text-dark border rounded-pill text-decoration-none px-2 py-1">
                        <i class="bi bi-bullseye me-1 text-primary"></i>Campaign #<?= $log->campaign_id ?>
                      </a>
                    <?php elseif ($detectedBId): ?>
                      <a href="<?= base_url('notification-manager/logs?booking_id=' . $detectedBId) ?>" 
                         class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill text-decoration-none px-2 py-1" 
                         title="Filter by Booking #<?= $detectedBId ?>">
                        <i class="bi bi-calendar2-check me-1"></i>Booking #<?= $detectedBId ?>
                      </a>
                    <?php else: ?>
                      <span class="badge bg-light text-muted border rounded-pill px-2 py-1">
                        <i class="bi bi-broadcast me-1"></i>Direct Dispatch
                      </span>
                    <?php endif; ?>
                  </td>

                  <!-- Status -->
                  <td>
                    <?php
                    $st = strtolower($log->status ?? 'queued');
                    ?>
                    <span class="status-pill status-<?= in_array($st, ['delivered', 'sent', 'failed', 'queued', 'opened', 'clicked']) ? $st : 'queued' ?>">
                      <span class="status-dot"></span>
                      <?= ucfirst($st) ?>
                    </span>
                    <?php if (!empty($log->error_message)): ?>
                      <i class="bi bi-exclamation-triangle-fill text-danger ms-1"
                         title="<?= esc($log->error_message) ?>"
                         data-bs-toggle="tooltip"
                         style="cursor: help;"></i>
                    <?php endif; ?>
                  </td>

                  <!-- Service / Dispatch Date -->
                  <td>
                    <div class="fw-semibold text-dark d-flex align-items-center gap-1" style="font-size: 12.5px;">
                      <i class="bi bi-calendar-event text-muted"></i>
                      <?= !empty($log->sent_at) ? date('Y-m-d', strtotime($log->sent_at)) : (!empty($log->created_at) ? date('Y-m-d', strtotime($log->created_at)) : '—') ?>
                    </div>
                    <small class="text-muted" style="font-size: 11px; margin-left: 17px;">
                      <?= !empty($log->sent_at) ? date('h:i A', strtotime($log->sent_at)) : (!empty($log->created_at) ? date('h:i A', strtotime($log->created_at)) : '') ?>
                    </small>
                  </td>

                  <!-- Action -->
                  <td class="text-center">
                    <button type="button" class="nl-action-circle btn-view-details"
                            title="View Dispatch Details"
                            data-log='<?= htmlspecialchars(json_encode($log), ENT_QUOTES, 'UTF-8') ?>'>
                      <i class="bi bi-eye"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center py-5 text-muted">
                  <i class="bi bi-journal-x fs-1 d-block mb-2 opacity-25"></i>
                  <h6 class="fw-bold text-dark mb-1">No Notification Logs Found</h6>
                  <p class="text-muted small mb-3">No notifications match your current filter criteria.</p>
                  <a href="<?= base_url('notification-manager/logs') ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Clear Filters & View All
                  </a>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination Footer -->
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 p-3 px-4 border-top">
        <div class="text-muted" style="font-size: 13px; font-weight: 500;">
          <?php
          $offset = (($page ?? 1) - 1) * ($limit ?? 50);
          $shownCount = count($logs ?? []);
          $fromItem = $totalLogs > 0 ? $offset + 1 : 0;
          $toItem = min($offset + $shownCount, $totalLogs);
          ?>
          Showing <strong><?= $fromItem ?></strong> to <strong><?= $toItem ?></strong> of <strong><?= number_format($totalLogs) ?></strong> notification logs
        </div>

        <?php if (($totalPages ?? 0) > 1): ?>
          <div class="d-flex align-items-center gap-1">
            <!-- Prev -->
            <a class="nl-page-btn <?= $page <= 1 ? 'disabled' : '' ?>" 
               href="?<?= http_build_query(array_merge($filters, ['page' => $page - 1])) ?>"
               title="Previous Page">
              <i class="bi bi-chevron-left"></i>
            </a>

            <?php
            $start = max(1, $page - 2);
            $end   = min($totalPages, $page + 2);
            if ($start > 1) {
              echo '<a class="nl-page-btn" href="?' . http_build_query(array_merge($filters, ['page' => 1])) . '">1</a>';
              if ($start > 2) echo '<span class="px-1 text-muted">...</span>';
            }
            for ($p = $start; $p <= $end; $p++):
            ?>
              <a class="nl-page-btn <?= $p === $page ? 'active' : '' ?>" 
                 href="?<?= http_build_query(array_merge($filters, ['page' => $p])) ?>">
                <?= $p ?>
              </a>
            <?php endfor; ?>

            <?php
            if ($end < $totalPages) {
              if ($end < $totalPages - 1) echo '<span class="px-1 text-muted">...</span>';
              echo '<a class="nl-page-btn" href="?' . http_build_query(array_merge($filters, ['page' => $totalPages])) . '">' . $totalPages . '</a>';
            }
            ?>

            <!-- Next -->
            <a class="nl-page-btn <?= $page >= $totalPages ? 'disabled' : '' ?>" 
               href="?<?= http_build_query(array_merge($filters, ['page' => $page + 1])) ?>"
               title="Next Page">
              <i class="bi bi-chevron-right"></i>
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Notification Details Modal -->
    <div class="modal fade" id="logDetailsModal" tabindex="-1" aria-labelledby="logDetailsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
          <div class="modal-header border-bottom py-3 px-4" style="background: #f8fafc;">
            <div class="d-flex align-items-center gap-3">
              <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: #e0e7ff; color: #4338ca;">
                <i class="bi bi-bell-fill fs-5"></i>
              </div>
              <div>
                <h5 class="modal-title fw-bold text-dark mb-0" id="logDetailsModalLabel">Dispatch Details</h5>
                <small class="text-muted" id="modalLogIdHeader">Log #</small>
              </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body p-4">
            <!-- Error Banner (if failed) -->
            <div id="modalErrorContainer" class="d-none alert alert-danger rounded-3 d-flex align-items-start gap-2 mb-3">
              <i class="bi bi-exclamation-triangle-fill fs-5 mt-1 flex-shrink-0"></i>
              <div>
                <strong class="d-block">Dispatch Failed</strong>
                <span id="modalErrorMessage" class="small"></span>
              </div>
            </div>

            <div class="row g-3">
              <!-- Recipient info -->
              <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 h-100 border border-light-subtle">
                  <label class="text-muted small fw-bold text-uppercase mb-2 d-block" style="font-size: 10px; letter-spacing: 0.05em;">Recipient Information</label>
                  <h6 class="fw-bold text-dark mb-1" id="modalRecipientName">—</h6>
                  <div class="small text-muted mb-2" id="modalRecipientRole">—</div>
                  <div class="small text-muted"><i class="bi bi-telephone me-1 text-primary"></i><span id="modalRecipientPhone">—</span></div>
                </div>
              </div>

              <!-- Status and Metadata -->
              <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 h-100 border border-light-subtle">
                  <label class="text-muted small fw-bold text-uppercase mb-2 d-block" style="font-size: 10px; letter-spacing: 0.05em;">Delivery Status</label>
                  <div class="d-flex align-items-center gap-2 mb-2">
                    <span id="modalStatusBadge" class="status-pill status-queued"></span>
                    <span id="modalChannelBadge" class="badge-channel push"></span>
                    <span id="modalCampaignBadge" class="badge bg-light text-dark border rounded-pill py-1 px-2"></span>
                  </div>
                  <div class="small text-muted mb-1"><i class="bi bi-calendar-event me-1"></i>Sent At: <span id="modalSentAt" class="fw-semibold text-dark">—</span></div>
                  <div class="small text-muted"><i class="bi bi-clock me-1"></i>Created At: <span id="modalCreatedAt" class="text-dark">—</span></div>
                </div>
              </div>

              <!-- Notification Content -->
              <div class="col-12">
                <div class="border rounded-3 p-3 bg-white shadow-xs">
                  <label class="text-muted small fw-bold text-uppercase mb-2 d-block" style="font-size: 10px; letter-spacing: 0.05em;">Message Content</label>
                  <div class="fw-bold text-dark mb-2 fs-6" id="modalNotificationTitle">—</div>
                  <div class="text-secondary small p-3 bg-light rounded-3" style="white-space: pre-wrap; font-family: inherit; line-height: 1.6;" id="modalNotificationBody">—</div>
                </div>
              </div>

              <!-- Device Token -->
              <div class="col-12" id="modalTokenSection">
                <label class="text-muted small fw-bold text-uppercase mb-1 d-block" style="font-size: 10px; letter-spacing: 0.05em;">Device Push Token</label>
                <div class="input-group input-group-sm">
                  <input type="text" id="modalDeviceToken" class="form-control font-monospace small bg-light" readonly>
                  <button class="btn btn-outline-secondary" type="button" id="btnCopyToken" title="Copy Token">
                    <i class="bi bi-clipboard me-1"></i>Copy
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer border-top bg-light py-3 px-4">
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
>>>>>>> origin/main
          </div>
        </div>
      </div>
    </div>

  </div>
  <?= $this->include('admin/footer') ?>
</main>

<<<<<<< HEAD
<?= $this->endSection() ?>
=======
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Details Modal
    const detailsModalEl = document.getElementById('logDetailsModal');
    const detailsModal = new bootstrap.Modal(detailsModalEl);

    document.querySelectorAll('.btn-view-details').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.stopPropagation();
        try {
          const data = JSON.parse(this.getAttribute('data-log'));
          populateModal(data);
          detailsModal.show();
        } catch (err) {
          console.error('Error parsing log data:', err);
        }
      });
    });

    function populateModal(log) {
      document.getElementById('modalLogIdHeader').innerText = 'Log #' + (log.id || '—');

      const isProv = ['provider', 'sp', 'service_provider'].includes((log.user_type || '').toLowerCase());
      const dispId = isProv ? (log.provider_id || log.user_id || '—') : (log.user_id || '—');
      const dispName = log.user_name || (isProv ? 'Provider #' + dispId : 'Client #' + dispId);

      document.getElementById('modalRecipientName').innerText = dispName;
      document.getElementById('modalRecipientRole').innerText = (isProv ? 'Service Provider' : 'Client') + ' (ID #' + dispId + ')';
      document.getElementById('modalRecipientPhone').innerText = log.user_phone || log.provider_phone || 'No phone recorded';

      // Status
      const statusEl = document.getElementById('modalStatusBadge');
      const st = (log.status || 'queued').toLowerCase();
      statusEl.innerHTML = '<span class="status-dot"></span> ' + (st.charAt(0).toUpperCase() + st.slice(1));
      statusEl.className = 'status-pill status-' + (['delivered', 'sent', 'failed', 'queued', 'opened', 'clicked'].includes(st) ? st : 'queued');

      // Channel
      const chEl = document.getElementById('modalChannelBadge');
      const ch = (log.channel || 'push').toLowerCase();
      chEl.innerText = ch.toUpperCase();
      chEl.className = 'badge-channel ' + ch;

      // Source
      const campEl = document.getElementById('modalCampaignBadge');
      if (log.campaign_id) {
        campEl.innerText = 'Campaign #' + log.campaign_id;
        campEl.classList.remove('d-none');
      } else {
        campEl.innerText = 'Direct / Event';
      }

      // Timestamps
      document.getElementById('modalSentAt').innerText = log.sent_at || 'Not sent yet';
      document.getElementById('modalCreatedAt').innerText = log.created_at || '—';

      // Content
      document.getElementById('modalNotificationTitle').innerText = log.title || 'Untitled';
      document.getElementById('modalNotificationBody').innerText = log.body || '(No body text)';

      // Token
      const tokenInput = document.getElementById('modalDeviceToken');
      if (log.device_token) {
        tokenInput.value = log.device_token;
        document.getElementById('modalTokenSection').classList.remove('d-none');
      } else {
        tokenInput.value = 'No device token registered';
      }

      // Error
      const errContainer = document.getElementById('modalErrorContainer');
      if (log.status === 'failed' || log.error_message) {
        document.getElementById('modalErrorMessage').innerText = log.error_message || 'Unknown error occurred during dispatch';
        errContainer.classList.remove('d-none');
      } else {
        errContainer.classList.add('d-none');
      }
    }

    // Copy Token button
    const copyBtn = document.getElementById('btnCopyToken');
    if (copyBtn) {
      copyBtn.addEventListener('click', function() {
        const tokenVal = document.getElementById('modalDeviceToken').value;
        if (tokenVal && tokenVal !== 'No device token registered') {
          navigator.clipboard.writeText(tokenVal).then(() => {
            this.innerHTML = '<i class="bi bi-check2 text-success me-1"></i>Copied!';
            setTimeout(() => {
              this.innerHTML = '<i class="bi bi-clipboard me-1"></i>Copy';
            }, 2000);
          });
        }
      });
    }

    // Quick Date Preset helper
    function formatDateYMD(d) {
      const year = d.getFullYear();
      const month = String(d.getMonth() + 1).padStart(2, '0');
      const day = String(d.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`;
    }

    function applyDatePreset(preset) {
      const now = new Date();
      let fromDate = '';
      let toDate = '';

      switch (preset) {
        case 'today':
          fromDate = formatDateYMD(now);
          toDate = formatDateYMD(now);
          break;
        case 'yesterday':
          const yest = new Date(now);
          yest.setDate(yest.getDate() - 1);
          fromDate = formatDateYMD(yest);
          toDate = formatDateYMD(yest);
          break;
        case 'last_7_days':
          const d7 = new Date(now);
          d7.setDate(d7.getDate() - 6);
          fromDate = formatDateYMD(d7);
          toDate = formatDateYMD(now);
          break;
        case 'last_30_days':
          const d30 = new Date(now);
          d30.setDate(d30.getDate() - 29);
          fromDate = formatDateYMD(d30);
          toDate = formatDateYMD(now);
          break;
        case 'this_month':
          fromDate = formatDateYMD(new Date(now.getFullYear(), now.getMonth(), 1));
          toDate = formatDateYMD(new Date(now.getFullYear(), now.getMonth() + 1, 0));
          break;
        case 'last_month':
          fromDate = formatDateYMD(new Date(now.getFullYear(), now.getMonth() - 1, 1));
          toDate = formatDateYMD(new Date(now.getFullYear(), now.getMonth(), 0));
          break;
      }

      const inputFrom = document.getElementById('filterDateFrom');
      const inputTo = document.getElementById('filterDateTo');
      if (inputFrom) inputFrom.value = fromDate;
      if (inputTo) inputTo.value = toDate;

      const presetSelect = document.getElementById('datePresetSelect');
      if (presetSelect) presetSelect.value = preset;

      document.querySelectorAll('.btn-quick-date').forEach(btn => {
        if (btn.getAttribute('data-preset') === preset) {
          btn.classList.add('active');
        } else {
          btn.classList.remove('active');
        }
      });
    }

    document.querySelectorAll('.btn-quick-date').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const preset = this.getAttribute('data-preset');
        applyDatePreset(preset);
        const form = document.getElementById('formFilterLogs');
        if (form) form.submit();
      });
    });

    const presetSelect = document.getElementById('datePresetSelect');
    if (presetSelect) {
      presetSelect.addEventListener('change', function() {
        if (this.value && this.value !== 'custom') {
          applyDatePreset(this.value);
          const form = document.getElementById('formFilterLogs');
          if (form) form.submit();
        } else if (this.value === '') {
          const inputFrom = document.getElementById('filterDateFrom');
          const inputTo = document.getElementById('filterDateTo');
          if (inputFrom) inputFrom.value = '';
          if (inputTo) inputTo.value = '';
          const form = document.getElementById('formFilterLogs');
          if (form) form.submit();
        }
      });
    }

    // Client-side CSV Export
    const exportBtn = document.getElementById('btnExportCSV');
    if (exportBtn) {
      exportBtn.addEventListener('click', function() {
        const rows = document.querySelectorAll('#logsDataTable tbody tr');
        if (!rows.length) return;

        let csv = [];
        // Headers
        csv.push(['ID', 'Recipient Name', 'Role', 'Phone', 'Channel', 'Title', 'Body', 'Status', 'Date'].map(h => '"' + h + '"').join(','));

        rows.forEach(tr => {
          const btn = tr.querySelector('.btn-view-details');
          if (btn) {
            try {
              const data = JSON.parse(btn.getAttribute('data-log'));
              const isProv = ['provider', 'sp', 'service_provider'].includes((data.user_type || '').toLowerCase());
              const dispName = data.user_name || (isProv ? 'Provider #' + (data.provider_id || data.user_id) : 'Client #' + data.user_id);
              const dispPhone = data.user_phone || data.provider_phone || '';
              const row = [
                data.id || '',
                dispName.replace(/"/g, '""'),
                isProv ? 'Provider' : 'Client',
                dispPhone.replace(/"/g, '""'),
                data.channel || '',
                (data.title || '').replace(/"/g, '""'),
                (data.body || '').replace(/"/g, '""'),
                data.status || '',
                data.sent_at || data.created_at || ''
              ];
              csv.push(row.map(f => '"' + f + '"').join(','));
            } catch (e) {}
          }
        });

        const csvString = csv.join('\n');
        const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.setAttribute('download', 'notification_logs_' + new Date().toISOString().slice(0,10) + '.csv');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      });
    }
  });
</script>

<?= $this->endSection() ?>
>>>>>>> origin/main
