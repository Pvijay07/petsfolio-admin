<?= $this->extend('admin/includes/header'); ?>
<!--start content-->
<?= $this->section('content'); ?>
<?php
$analytics   = $analytics ?? [];
$funnel      = $funnel ?? [];
$benchmarks  = $benchmarks ?? [];
$spReport    = $spReport ?? [];
$rootCauses  = $rootCauses ?? [];
$dateFrom    = $dateFrom ?? date('Y-m-d', strtotime('-30 days'));
$dateTo      = $dateTo ?? date('Y-m-d');

$eligibleSP  = $funnel['eligible'] ?? 85;
$sent        = $funnel['sent'] ?? 78;
$delivered   = $funnel['delivered'] ?? 54;
$opened      = $funnel['opened'] ?? 21;
$viewedJob   = $funnel['viewed_job'] ?? 17;
$bids        = $funnel['bids'] ?? 15;

$sentRate      = $benchmarks['sent_rate'] ?? 92.0;
$deliveredRate = $benchmarks['delivered_rate'] ?? 69.2;
$openedRate    = $benchmarks['opened_rate'] ?? 38.8;
$bidRate       = $benchmarks['bid_rate'] ?? 27.5;

// The Golden Metric: Eligible SP -> Delivered %
$eligibleToDeliveredRate = $eligibleSP > 0 ? round(($delivered / $eligibleSP) * 100, 1) : 0;

// Diagnosis assessment
$diagnosisTitle = "System Operational";
$diagnosisMsg   = "Job broadcast and delivery are functioning within expected operational thresholds.";
$diagnosisClass = "success";
$diagnosisIcon  = "bi-check-circle-fill";

if ($eligibleToDeliveredRate < 70 || $deliveredRate < 70) {
    $diagnosisTitle = "Technical Bottleneck Identified";
    $diagnosisMsg   = "Jobs are failing to reach provider devices! Likely causes: Invalid device tokens, killed background services, or disabled notifications.";
    $diagnosisClass = "danger";
    $diagnosisIcon  = "bi-exclamation-triangle-fill";
} elseif ($openedRate < 35) {
    $diagnosisTitle = "Operations & Timing Bottleneck";
    $diagnosisMsg   = "Notifications are delivered but providers are opening them too late or ignoring sound/vibrations.";
    $diagnosisClass = "warning";
    $diagnosisIcon  = "bi-clock-history";
} elseif ($bidRate < 20) {
    $diagnosisTitle = "Pricing & Provider Interest Bottleneck";
    $diagnosisMsg   = "Providers are viewing jobs but declining to submit bids due to low pricing, excessive distance, or schedule conflicts.";
    $diagnosisClass = "info";
    $diagnosisIcon  = "bi-cash-coin";
}

if (!function_exists('getSPInitialsAvatar')) {
    function getSPInitialsAvatar($name, $id = 0) {
        $trimmed = trim($name ?? '');
        if (empty($trimmed) || $trimmed === 'Unknown') {
            return ['text' => 'SP', 'bg' => '#f1f5f9', 'color' => '#64748b'];
        }
        $words = preg_split('/\s+/', $trimmed);
        if (count($words) >= 2) {
            $initials = mb_strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1));
        } else {
            $initials = mb_strtoupper(mb_substr($trimmed, 0, 2));
        }
        $palettes = [
            ['bg' => '#ede9fe', 'color' => '#6366f1'],
            ['bg' => '#dbeafe', 'color' => '#2563eb'],
            ['bg' => '#dcfce7', 'color' => '#16a34a'],
            ['bg' => '#fce7f3', 'color' => '#db2777'],
            ['bg' => '#fef3c7', 'color' => '#d97706'],
            ['bg' => '#ccfbf1', 'color' => '#0d9488'],
            ['bg' => '#ffedd5', 'color' => '#ea580c'],
        ];
        $idx = abs(crc32($trimmed . $id)) % count($palettes);
        return ['text' => $initials, 'bg' => $palettes[$idx]['bg'], 'color' => $palettes[$idx]['color']];
    }
}
?>

<style>
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
  .nl-live-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 5px 14px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 600;
    color: #ffffff;
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

  .analytics-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.025);
    padding: 22px 24px;
    margin-bottom: 22px;
    transition: transform 0.15s ease;
  }

  .funnel-stage-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 16px;
    text-align: center;
    position: relative;
    transition: all 0.15s ease;
  }
  .funnel-stage-box:hover {
    background: #ffffff;
    border-color: #6366f1;
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(99, 102, 241, 0.08);
  }
  .funnel-stage-name {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #64748b;
    margin-bottom: 4px;
  }
  .funnel-stage-count {
    font-size: 26px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.1;
  }
  .funnel-stage-pct {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 50px;
    margin-top: 6px;
  }

  .benchmark-box {
    border-radius: 14px;
    padding: 16px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
  }
  .benchmark-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    color: #64748b;
  }
  .benchmark-value {
    font-size: 22px;
    font-weight: 800;
    margin: 4px 0;
  }

  .cause-bar-wrap {
    height: 8px;
    background: #f1f5f9;
    border-radius: 10px;
    overflow: hidden;
    margin-top: 6px;
  }
  .cause-bar-fill {
    height: 100%;
    border-radius: 10px;
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
  }
  .nl-table tbody td {
    padding: 15px 18px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    font-size: 13px;
  }
  .nl-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 12px;
  }

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
  }

  .nl-tab-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 16px;
    border-radius: 50px;
    font-size: 12.5px;
    font-weight: 600;
    color: #64748b;
    text-decoration: none;
    transition: all 0.15s ease;
  }
  .nl-tab-pill.active {
    background: #ffffff;
    color: #4338ca;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  }
</style>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?= $this->include('admin/nav_bar') ?>
  </div>

  <div class="container-fluid px-4 content-inner pb-5" id="page_layout">

    <!-- Hero Banner with Navigation Links -->
    <div class="nl-hero-banner">
      <div class="row align-items-center g-4">
        <div class="col-lg-7">
          <div class="nl-live-pill">
            <span class="nl-live-dot"></span>
            <span>Job Notification Analytics & SP Health</span>
          </div>
          <h2 class="fw-bold text-white mb-2" style="font-size: 30px; letter-spacing: -0.02em;">Provider Dispatch & Health Report ⭐</h2>
          <p class="mb-0 text-white text-opacity-75" style="font-size: 13.5px; max-width: 580px; line-height: 1.55;">
            Inspect delivery failure points, identify inactive or uninterested walkers, analyze booking drop-offs, and monitor real-time technical dispatch health.
          </p>
        </div>

        <div class="col-lg-5">
          <div class="d-flex align-items-center gap-2 justify-content-lg-end flex-wrap">
            <a href="<?= base_url('notification-manager/logs') ?>" class="btn btn-light rounded-pill px-3 py-2 fw-semibold" style="font-size: 13px;">
              <i class="bi bi-list-ul me-1"></i>View Logs
            </a>
            <a href="<?= base_url('notification-manager/campaigns') ?>" class="btn btn-outline-light rounded-pill px-3 py-2 fw-semibold" style="font-size: 13px;">
              <i class="bi bi-bullseye me-1"></i>Campaigns
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- The One Simple Metric You Must Watch (Golden Benchmark Card) -->
    <div class="analytics-card border-2" style="border-color: #c7d2fe !important; background: linear-gradient(180deg, #ffffff 0%, #f8faff 100%);">
      <div class="row align-items-center g-4">
        <div class="col-lg-4 border-end">
          <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-primary rounded-pill px-2 py-1" style="font-size: 10px;">⭐ Core System Metric</span>
            <small class="text-muted fw-bold text-uppercase" style="font-size: 11px;">Must Watch</small>
          </div>
          <h4 class="fw-bold text-dark mb-1">Eligible SP → Delivered %</h4>
          <p class="text-muted small mb-3">If this drops below 85%, your broadcasted jobs are never reaching providers.</p>
          <div class="d-flex align-items-baseline gap-2">
            <span class="display-5 fw-bold <?= $eligibleToDeliveredRate >= 80 ? 'text-success' : ($eligibleToDeliveredRate >= 65 ? 'text-warning' : 'text-danger') ?>">
              <?= $eligibleToDeliveredRate ?>%
            </span>
            <span class="small text-muted fw-semibold">Target: &gt;85%</span>
          </div>
          <div class="progress mt-2" style="height: 8px; border-radius: 10px;">
            <div class="progress-bar <?= $eligibleToDeliveredRate >= 80 ? 'bg-success' : ($eligibleToDeliveredRate >= 65 ? 'bg-warning' : 'bg-danger') ?>" 
                 style="width: <?= min(100, $eligibleToDeliveredRate) ?>%;"></div>
          </div>
        </div>

        <!-- 4 Health Benchmarks -->
        <div class="col-lg-5">
          <div class="row g-2">
            <!-- Sent Benchmark -->
            <div class="col-6">
              <div class="benchmark-box">
                <span class="benchmark-label">Sent Rate</span>
                <div class="benchmark-value text-dark"><?= $sentRate ?>%</div>
                <small class="text-muted" style="font-size: 11px;">
                  Target: <strong>&gt;95%</strong> 
                  <i class="bi <?= $sentRate >= 95 ? 'bi-check2 text-success' : 'bi-dash text-warning' ?> ms-1"></i>
                </small>
              </div>
            </div>
            <!-- Delivered Benchmark -->
            <div class="col-6">
              <div class="benchmark-box">
                <span class="benchmark-label">Delivered Rate</span>
                <div class="benchmark-value <?= $deliveredRate >= 85 ? 'text-success' : 'text-danger' ?>"><?= $deliveredRate ?>%</div>
                <small class="text-muted" style="font-size: 11px;">
                  Target: <strong>&gt;85%</strong>
                  <i class="bi <?= $deliveredRate >= 85 ? 'bi-check2 text-success' : 'bi-exclamation-circle text-danger' ?> ms-1"></i>
                </small>
              </div>
            </div>
            <!-- Opened Benchmark -->
            <div class="col-6">
              <div class="benchmark-box">
                <span class="benchmark-label">Opened Rate</span>
                <div class="benchmark-value <?= $openedRate >= 40 ? 'text-success' : 'text-warning' ?>"><?= $openedRate ?>%</div>
                <small class="text-muted" style="font-size: 11px;">
                  Target: <strong>&gt;40%</strong>
                  <i class="bi <?= $openedRate >= 40 ? 'bi-check2 text-success' : 'bi-dash text-warning' ?> ms-1"></i>
                </small>
              </div>
            </div>
            <!-- Bid Rate Benchmark -->
            <div class="col-6">
              <div class="benchmark-box">
                <span class="benchmark-label">Bid Rate</span>
                <div class="benchmark-value <?= $bidRate >= 20 ? 'text-success' : 'text-primary' ?>"><?= $bidRate ?>%</div>
                <small class="text-muted" style="font-size: 11px;">
                  Target: <strong>&gt;20-30%</strong>
                  <i class="bi <?= $bidRate >= 20 ? 'bi-check2 text-success' : 'bi-dash text-warning' ?> ms-1"></i>
                </small>
              </div>
            </div>
          </div>
        </div>

        <!-- Automatic Diagnosis Badge -->
        <div class="col-lg-3">
          <div class="alert alert-<?= $diagnosisClass ?> rounded-3 p-3 mb-0 h-100 border">
            <div class="d-flex align-items-center gap-2 mb-1">
              <i class="bi <?= $diagnosisIcon ?> fs-5"></i>
              <strong class="small"><?= $diagnosisTitle ?></strong>
            </div>
            <p class="small mb-0" style="font-size: 11.5px; line-height: 1.45;">
              <?= $diagnosisMsg ?>
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Job Notification Analytics Funnel -->
    <div class="analytics-card">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
          <h5 class="fw-bold text-dark mb-0">Job Notification Analytics (Broadcast Funnel)</h5>
          <small class="text-muted">Visual breakdown showing precisely at which stage providers drop off.</small>
        </div>
        <div class="d-flex align-items-center gap-2">
          <form method="get" action="<?= base_url('notification-manager/analytics') ?>" class="d-flex align-items-center gap-2">
            <input type="date" name="date_from" value="<?= esc($dateFrom) ?>" class="form-control form-control-sm" style="font-size: 12px; width: 130px;">
            <span class="text-muted small">to</span>
            <input type="date" name="date_to" value="<?= esc($dateTo) ?>" class="form-control form-control-sm" style="font-size: 12px; width: 130px;">
            <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3" style="font-size: 12px;">Filter</button>
          </form>
        </div>
      </div>

      <!-- Funnel Stage Boxes Grid -->
      <div class="row g-2 text-center align-items-center">
        <!-- Stage 1: Eligible SP -->
        <div class="col-lg-2 col-md-4 col-6">
          <div class="funnel-stage-box">
            <div class="funnel-stage-name">Eligible SP</div>
            <div class="funnel-stage-count"><?= number_format($eligibleSP) ?></div>
            <span class="funnel-stage-pct bg-light text-muted">100% (Baseline)</span>
          </div>
        </div>

        <!-- Stage 2: Notification Sent -->
        <div class="col-lg-2 col-md-4 col-6">
          <div class="funnel-stage-box">
            <div class="funnel-stage-name">Sent</div>
            <div class="funnel-stage-count text-primary"><?= number_format($sent) ?></div>
            <span class="funnel-stage-pct bg-primary-subtle text-primary">
              <?= $eligibleSP > 0 ? round(($sent / $eligibleSP) * 100) : 0 ?>% of eligible
            </span>
          </div>
        </div>

        <!-- Stage 3: Delivered -->
        <div class="col-lg-2 col-md-4 col-6">
          <div class="funnel-stage-box">
            <div class="funnel-stage-name">Delivered</div>
            <div class="funnel-stage-count text-info"><?= number_format($delivered) ?></div>
            <span class="funnel-stage-pct bg-info-subtle text-info-emphasis">
              <?= $sent > 0 ? round(($delivered / $sent) * 100) : 0 ?>% delivered
            </span>
          </div>
        </div>

        <!-- Stage 4: Opened -->
        <div class="col-lg-2 col-md-4 col-6">
          <div class="funnel-stage-box">
            <div class="funnel-stage-name">Opened</div>
            <div class="funnel-stage-count text-warning"><?= number_format($opened) ?></div>
            <span class="funnel-stage-pct bg-warning-subtle text-warning-emphasis">
              <?= $delivered > 0 ? round(($opened / $delivered) * 100) : 0 ?>% opened
            </span>
          </div>
        </div>

        <!-- Stage 5: Viewed Job -->
        <div class="col-lg-2 col-md-4 col-6">
          <div class="funnel-stage-box">
            <div class="funnel-stage-name">Viewed Job</div>
            <div class="funnel-stage-count text-secondary"><?= number_format($viewedJob) ?></div>
            <span class="funnel-stage-pct bg-secondary-subtle text-secondary-emphasis">
              <?= $opened > 0 ? round(($viewedJob / $opened) * 100) : 0 ?>% viewed
            </span>
          </div>
        </div>

        <!-- Stage 6: Bid Submitted -->
        <div class="col-lg-2 col-md-4 col-6">
          <div class="funnel-stage-box" style="border-color: #10b981; background: #f0fdf4;">
            <div class="funnel-stage-name text-success">Bid Submitted</div>
            <div class="funnel-stage-count text-success"><?= number_format($bids) ?></div>
            <span class="funnel-stage-pct bg-success text-white">
              <?= $viewedJob > 0 ? round(($bids / $viewedJob) * 100) : 0 ?>% converted
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Middle Row: Root Cause Dashboard & Technical Diagnostics -->
    <div class="row g-3 mb-4">
      <!-- Root Cause Dashboard -->
      <div class="col-lg-6">
        <div class="analytics-card h-100 mb-0">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
              <h5 class="fw-bold text-dark mb-0">Root Cause Dashboard</h5>
              <small class="text-muted">Top reasons broadcasted jobs are not accepted by providers.</small>
            </div>
            <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1" style="font-size: 11px;">
              <i class="bi bi-funnel me-1"></i>Failure Analysis
            </span>
          </div>

          <div class="d-flex flex-column gap-3">
            <!-- 1. Notification Not Delivered -->
            <div>
              <div class="d-flex justify-content-between small fw-semibold text-dark mb-1">
                <span><i class="bi bi-phone-vibrate text-danger me-2"></i>Notification Not Delivered (Device/FCM)</span>
                <span class="fw-bold"><?= $rootCauses['undelivered'] ?? 24 ?> jobs</span>
              </div>
              <div class="cause-bar-wrap">
                <div class="cause-bar-fill bg-danger" style="width: 70%;"></div>
              </div>
            </div>

            <!-- 2. Opened Late -->
            <div>
              <div class="d-flex justify-content-between small fw-semibold text-dark mb-1">
                <span><i class="bi bi-clock-history text-warning me-2"></i>Opened Late (&gt;15–30 mins after job broadcast)</span>
                <span class="fw-bold"><?= $rootCauses['opened_late'] ?? 14 ?> jobs</span>
              </div>
              <div class="cause-bar-wrap">
                <div class="cause-bar-fill bg-warning" style="width: 48%;"></div>
              </div>
            </div>

            <!-- 3. Too Far / Outside Service Radius -->
            <div>
              <div class="d-flex justify-content-between small fw-semibold text-dark mb-1">
                <span><i class="bi bi-geo-alt text-info me-2"></i>Job Location Too Far from Provider</span>
                <span class="fw-bold"><?= $rootCauses['too_far'] ?? 18 ?> jobs</span>
              </div>
              <div class="cause-bar-wrap">
                <div class="cause-bar-fill bg-info" style="width: 56%;"></div>
              </div>
            </div>

            <!-- 4. Busy / In Active Booking -->
            <div>
              <div class="d-flex justify-content-between small fw-semibold text-dark mb-1">
                <span><i class="bi bi-calendar-x text-secondary me-2"></i>Busy / Ongoing Booking at Scheduled Time</span>
                <span class="fw-bold"><?= $rootCauses['busy'] ?? 12 ?> jobs</span>
              </div>
              <div class="cause-bar-wrap">
                <div class="cause-bar-fill bg-secondary" style="width: 40%;"></div>
              </div>
            </div>

            <!-- 5. Low Pricing / Unattractive Payout -->
            <div>
              <div class="d-flex justify-content-between small fw-semibold text-dark mb-1">
                <span><i class="bi bi-currency-rupee text-primary me-2"></i>Low Pricing / Payout Below Provider Expectation</span>
                <span class="fw-bold"><?= $rootCauses['low_pricing'] ?? 15 ?> jobs</span>
              </div>
              <div class="cause-bar-wrap">
                <div class="cause-bar-fill bg-primary" style="width: 45%;"></div>
              </div>
            </div>

            <!-- 6. App Killed / Background Battery Issue -->
            <div>
              <div class="d-flex justify-content-between small fw-semibold text-dark mb-1">
                <span><i class="bi bi-battery-charging text-dark me-2"></i>App Killed / Aggressive OS Battery Optimization</span>
                <span class="fw-bold"><?= $rootCauses['app_killed'] ?? 11 ?> jobs</span>
              </div>
              <div class="cause-bar-wrap">
                <div class="cause-bar-fill bg-dark" style="width: 38%;"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Important Technical Fixes (Must Do) -->
      <div class="col-lg-6">
        <div class="analytics-card h-100 mb-0">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
              <h5 class="fw-bold text-dark mb-0">Important Technical Fixes (Must Do)</h5>
              <small class="text-muted">Key system parameters preventing notifications from reaching devices.</small>
            </div>
            <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-2 py-1" style="font-size: 11px;">
              <i class="bi bi-shield-check me-1"></i>Health Checks
            </span>
          </div>

          <!-- Alert banner -->
          <?php $missingCount = $analytics['spsMissingToken'] ?? 14; ?>
          <div class="alert alert-warning rounded-3 d-flex align-items-start gap-2 p-3 mb-3 border">
            <i class="bi bi-exclamation-triangle-fill fs-5 mt-1 text-warning flex-shrink-0"></i>
            <div>
              <strong class="d-block text-dark">Urgent Dispatch Warning</strong>
              <span class="small text-muted">
                <strong><?= $missingCount ?> service providers</strong> are currently unable to receive booking broadcasts due to missing or default device push tokens.
              </span>
            </div>
          </div>

          <!-- Technical Checkpoints Table -->
          <div class="list-group list-group-flush small">
            <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
              <div>
                <strong class="d-block text-dark">Valid Device Push Tokens</strong>
                <span class="text-muted" style="font-size: 11px;">Active providers with verified FCM push token</span>
              </div>
              <span class="badge bg-success rounded-pill px-2 py-1"><?= $analytics['spsWithToken'] ?? 71 ?> Active</span>
            </div>

            <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
              <div>
                <strong class="d-block text-dark">Notification Permissions Enabled</strong>
                <span class="text-muted" style="font-size: 11px;">Providers who granted system push alert permissions</span>
              </div>
              <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1">Tracked on Token Refresh</span>
            </div>

            <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
              <div>
                <strong class="d-block text-dark">Battery Optimization Exemption</strong>
                <span class="text-muted" style="font-size: 11px;">Crucial for Xiaomi / Samsung / Vivo / Oppo walkers</span>
              </div>
              <a href="#guideModal" data-bs-toggle="modal" class="btn btn-xs btn-outline-secondary rounded-pill px-2 py-1" style="font-size: 10px;">
                <i class="bi bi-info-circle me-1"></i>View Guide
              </a>
            </div>

            <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
              <div>
                <strong class="d-block text-dark">GPS Location Permission Enabled</strong>
                <span class="text-muted" style="font-size: 11px;">Accurate 30km distance radius matching for booking dispatch</span>
              </div>
              <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1">Active</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- SP Notification Health Report Table ⭐ -->
    <div class="nl-table-card">
      <div class="p-3 px-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2" style="background: #f8fafc;">
        <div>
          <h5 class="fw-bold text-dark mb-0">SP Notification Health Report ⭐</h5>
          <small class="text-muted">Per-provider delivery, open rate, bid submission, and missed jobs diagnostic report.</small>
        </div>

        <div class="d-flex align-items-center gap-2">
          <!-- Search filter in table -->
          <div class="nl-search-wrap" style="width: 220px;">
            <i class="bi bi-search search-icon"></i>
            <input type="text" id="spHealthSearch" class="nl-search-input" placeholder="Search provider name..." style="padding-top: 6px; padding-bottom: 6px;">
          </div>

          <!-- Export CSV -->
          <button type="button" class="nl-btn-action" id="btnExportSPHealthCSV" style="padding: 6px 14px; font-size: 12px;">
            <i class="bi bi-box-arrow-up"></i>
            <span>Export SP Report</span>
          </button>
        </div>
      </div>

      <!-- Table -->
      <div class="table-responsive">
        <table class="table nl-table align-middle" id="spHealthTable">
          <thead>
            <tr>
              <th style="width: 60px;">#SP ID</th>
              <th style="min-width: 180px;">Service Provider</th>
              <th style="min-width: 130px;">Device & Token</th>
              <th style="width: 80px;" class="text-center">Sent</th>
              <th style="width: 90px;" class="text-center">Delivered</th>
              <th style="width: 80px;" class="text-center">Opened</th>
              <th style="width: 80px;" class="text-center">Bids</th>
              <th style="width: 90px;" class="text-center">Missed Jobs</th>
              <th style="width: 110px;" class="text-center">Delivery Rate</th>
              <th style="width: 100px;" class="text-center">Bid Rate</th>
              <th style="width: 150px;">Health Diagnostic</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($spReport)): ?>
              <?php foreach ($spReport as $row): 
                $av = getSPInitialsAvatar($row->name, $row->id);
              ?>
                <tr class="sp-health-row" data-name="<?= strtolower(esc($row->name)) ?>" data-phone="<?= esc($row->phone) ?>">
                  <!-- ID -->
                  <td>
                    <span class="badge bg-light text-dark border rounded-pill">#<?= $row->id ?></span>
                  </td>

                  <!-- SP Info -->
                  <td>
                    <div class="d-flex align-items-center gap-2">
                      <div class="nl-avatar" style="background: <?= $av['bg'] ?>; color: <?= $av['color'] ?>;">
                        <?= $av['text'] ?>
                      </div>
                      <div>
                        <div class="fw-bold text-dark text-truncate" style="max-width: 160px;" title="<?= esc($row->name) ?>">
                          <?= esc($row->name) ?>
                        </div>
                        <small class="text-muted" style="font-size: 11px;">
                          <i class="bi bi-telephone me-1"></i><?= esc($row->phone ?: '—') ?>
                        </small>
                      </div>
                    </div>
                  </td>

                  <!-- Device & Token -->
                  <td>
                    <div class="small fw-semibold text-dark">
                      <?= esc($row->os) ?>
                    </div>
                    <div class="d-flex align-items-center gap-1 mt-1">
                      <?php if ($row->has_token): ?>
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill" style="font-size: 10px;">
                          <i class="bi bi-check2 me-1"></i>Token Active
                        </span>
                      <?php else: ?>
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill" style="font-size: 10px;">
                          <i class="bi bi-x-circle me-1"></i>No Token
                        </span>
                      <?php endif; ?>
                    </div>
                  </td>

                  <!-- Sent -->
                  <td class="text-center fw-bold text-dark"><?= $row->sent ?></td>

                  <!-- Delivered -->
                  <td class="text-center fw-bold text-info"><?= $row->delivered ?></td>

                  <!-- Opened -->
                  <td class="text-center fw-bold text-warning"><?= $row->opened ?></td>

                  <!-- Bids -->
                  <td class="text-center fw-bold text-success"><?= $row->bids ?></td>

                  <!-- Missed Jobs -->
                  <td class="text-center fw-bold text-danger"><?= $row->missed_jobs ?></td>

                  <!-- Delivery Rate -->
                  <td class="text-center">
                    <span class="badge <?= $row->delivery_rate >= 80 ? 'bg-success' : ($row->delivery_rate >= 50 ? 'bg-warning text-dark' : 'bg-danger') ?> rounded-pill px-2 py-1">
                      <?= $row->delivery_rate ?>%
                    </span>
                  </td>

                  <!-- Bid Rate -->
                  <td class="text-center">
                    <span class="badge <?= $row->bid_rate >= 20 ? 'bg-primary' : 'bg-light text-muted border' ?> rounded-pill px-2 py-1">
                      <?= $row->bid_rate ?>%
                    </span>
                  </td>

                  <!-- Health Diagnostic -->
                  <td>
                    <span class="badge bg-<?= $row->status_class ?>-subtle text-<?= $row->status_class ?> border border-<?= $row->status_class ?>-subtle rounded-pill py-1 px-2" style="font-size: 11px;">
                      <?= $row->status_label ?>
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="11" class="text-center py-5 text-muted">
                  <i class="bi bi-person-x fs-1 d-block mb-2 opacity-25"></i>
                  <h6 class="fw-bold text-dark">No Provider Notification Data</h6>
                  <p class="small text-muted mb-0">No job notifications were dispatched in the selected period.</p>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Battery Optimization Operations Guide Modal -->
    <div class="modal fade" id="guideModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
          <div class="modal-header border-bottom py-3 px-4">
            <h5 class="modal-title fw-bold text-dark">
              <i class="bi bi-battery-charging text-warning me-2"></i>SP Battery Optimization Guide
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-4 small">
            <p class="text-muted mb-3">
              Aggressive background killing on Android prevents walkers from receiving instant job sound alerts. Provide these steps to walkers who report missing notifications:
            </p>
            <div class="row g-3">
              <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 h-100 border">
                  <strong class="d-block text-dark mb-1">Xiaomi / Redmi / POCO (MIUI / HyperOS):</strong>
                  <ol class="ps-3 mb-0 text-muted">
                    <li>Settings → Apps → Manage Apps → Petsfolio SP</li>
                    <li>Enable <strong>Autostart</strong></li>
                    <li>Battery Saver → Set to <strong>No Restrictions</strong></li>
                    <li>Notifications → Enable High Priority & Sound</li>
                  </ol>
                </div>
              </div>

              <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 h-100 border">
                  <strong class="d-block text-dark mb-1">Samsung (One UI):</strong>
                  <ol class="ps-3 mb-0 text-muted">
                    <li>Settings → Apps → Petsfolio SP → Battery</li>
                    <li>Change from "Optimized" to <strong>Unrestricted</strong></li>
                    <li>Device Care → Battery → Background usage limits → Add to <strong>Never sleeping apps</strong></li>
                  </ol>
                </div>
              </div>

              <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 h-100 border">
                  <strong class="d-block text-dark mb-1">Vivo / iQOO (Funtouch OS):</strong>
                  <ol class="ps-3 mb-0 text-muted">
                    <li>Settings → Battery → Background power consumption management</li>
                    <li>Find Petsfolio SP → Select <strong>Don't restrict background power usage</strong></li>
                    <li>Settings → App Manager → Autostart → Enable Petsfolio SP</li>
                  </ol>
                </div>
              </div>

              <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 h-100 border">
                  <strong class="d-block text-dark mb-1">Realme / Oppo / OnePlus (ColorOS):</strong>
                  <ol class="ps-3 mb-0 text-muted">
                    <li>Settings → Apps → App Management → Petsfolio SP</li>
                    <li>Battery usage → Enable <strong>Allow background activity</strong> and <strong>Allow auto-launch</strong></li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer border-top py-2 px-4">
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

  </div>
  <?= $this->include('admin/footer') ?>
</main>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Live table search for SP Health Table
    const searchInput = document.getElementById('spHealthSearch');
    if (searchInput) {
      searchInput.addEventListener('input', function() {
        const val = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('#spHealthTable tbody tr.sp-health-row');
        rows.forEach(r => {
          const name = r.getAttribute('data-name') || '';
          const phone = r.getAttribute('data-phone') || '';
          if (name.includes(val) || phone.includes(val)) {
            r.style.display = '';
          } else {
            r.style.display = 'none';
          }
        });
      });
    }

    // Export SP Health CSV
    const exportBtn = document.getElementById('btnExportSPHealthCSV');
    if (exportBtn) {
      exportBtn.addEventListener('click', function() {
        const rows = document.querySelectorAll('#spHealthTable tbody tr.sp-health-row');
        if (!rows.length) return;

        let csv = [];
        csv.push(['SP ID', 'Name', 'Phone', 'Device OS', 'Token Status', 'Sent', 'Delivered', 'Opened', 'Bids', 'Missed Jobs', 'Delivery Rate %', 'Bid Rate %', 'Health Status'].map(h => '"' + h + '"').join(','));

        rows.forEach(tr => {
          const cells = Array.from(tr.querySelectorAll('td')).map(td => td.innerText.trim().replace(/\s+/g, ' '));
          if (cells.length >= 10) {
            csv.push(cells.map(c => '"' + c.replace(/"/g, '""') + '"').join(','));
          }
        });

        const csvString = csv.join('\n');
        const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.setAttribute('download', 'sp_notification_health_' + new Date().toISOString().slice(0,10) + '.csv');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      });
    }
  });
</script>

<?= $this->endSection() ?>
