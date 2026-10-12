<<<<<<< HEAD
<?php echo $this->extend('admin/includes/header'); ?>
<?php echo $this->section('content'); ?>

<?php
  // Safe variable initializations
  $totalRecordings   = $totalRecordings ?? 0;
  $completedCount    = $completedCount ?? 0;
  $queuedCount       = $queuedCount ?? 0;
  $failedCount       = $failedCount ?? 0;
  $avgRelevance      = $avgRelevance ?? 9.0;
  $avgRating         = $avgRating ?? 9.0;
  $highRelevancePct  = $highRelevancePct ?? 100.0;
  $offPlatformCount  = $offPlatformCount ?? 0;
  $contactShareCount = $contactShareCount ?? 0;
  $complaintCount    = $complaintCount ?? 0;
  $outsideSvcCount   = $outsideSvcCount ?? 0;
  $criticalCount     = $criticalCount ?? 0;
  $reviewCount       = $reviewCount ?? 0;
  $selectedService   = $selectedService ?? '';
  $selectedRating    = $selectedRating ?? '';
  $selectedFlag      = $selectedFlag ?? '';
  $calls             = $calls ?? [];
?>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<style>
  .bg-gradient-client-call {
    background: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 50%, #6366f1 100%) !important;
  }
  .stat-card-modern {
    border: none;
    border-radius: 16px;
    background: #ffffff;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .stat-card-modern:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
  }
  .service-pill {
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 0.82rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .service-pill.active {
    background: #0284c7 !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35);
  }
  .service-pill:hover:not(.active) {
    background: #e0f2fe;
    color: #0369a1;
  }
  .highlight-badge-service {
    background: #dcfce7;
    color: #15803d;
    border-left: 4px solid #22c55e;
  }
  .highlight-badge-unrelated {
    background: #fef9c3;
    color: #854d0e;
    border-left: 4px solid #eab308;
  }
  .highlight-badge-policy {
    background: #fee2e2;
    color: #991b1b;
    border-left: 4px solid #ef4444;
  }
  .highlight-badge-complaint {
    background: #ffedd5;
    color: #9a3412;
    border-left: 4px solid #f97316;
  }
  .highlight-badge-info {
    background: #e0f2fe;
    color: #075985;
    border-left: 4px solid #0ea5e9;
  }
  .timeline-segment-item {
    position: relative;
    padding-left: 24px;
    margin-bottom: 14px;
  }
  .timeline-segment-item::before {
    content: '';
    position: absolute;
    left: 4px;
    top: 6px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #3b82f6;
  }
  .timeline-segment-item::after {
    content: '';
    position: absolute;
    left: 8px;
    top: 18px;
    bottom: -10px;
    width: 2px;
    background: #e2e8f0;
  }
  .timeline-segment-item:last-child::after {
    display: none;
  }
  .transcript-container {
    max-height: 380px;
    overflow-y: auto;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px;
    font-family: inherit;
    white-space: pre-wrap;
    font-size: 0.9rem;
    line-height: 1.6;
  }
  .table-hover tbody tr {
    cursor: pointer;
    transition: background 0.15s ease;
  }
  .table-hover tbody tr:hover {
    background-color: rgba(14, 165, 233, 0.04) !important;
  }
  .badge-tag-pill {
    font-size: 0.72rem;
    padding: 4px 8px;
    border-radius: 6px;
    font-weight: 600;
  }
</style>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?php echo $this->include('admin/nav_bar') ?>
  </div>

  <div class="container-fluid content-inner mt-n5 py-0 px-4" id="page_layout">
    
    <!-- Hero Banner -->
    <div class="container-fluid p-0 mb-4 bg-gradient-client-call rounded-3 shadow">
      <div class="text-white p-4 p-md-5 rounded-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <span class="badge bg-white text-primary fw-bold text-uppercase px-3 py-1 mb-2">Service Conversation Intelligence</span>
          <h1 class="h2 fw-bold mb-2 text-white"><i class="fas fa-comments me-2"></i> Client & Provider Call Analysis</h1>
          <p class="text-white-75 mb-0" style="max-width: 750px;">
            AI analysis of mobile-to-mobile recorded conversations across Walking, Grooming, Training, and Sitting.
            Detects service relevance, highlights unrelated topics, and flags off-platform payments, contact sharing, and complaints.
          </p>
        </div>
        <div class="d-flex gap-2">
          <a href="<?= base_url('call-analysis') ?>" class="btn btn-outline-light fw-semibold shadow-sm">
            <i class="fas fa-user-shield me-1"></i> SP Verification Calls
          </a>
          <button class="btn btn-light text-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadClientRecordingModal">
            <i class="fas fa-cloud-upload-alt me-1"></i> Upload Client-SP Call
          </button>
        </div>
      </div>
    </div>

    <!-- Executive KPI Row -->
    <div class="row g-3 mb-4">
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card-modern p-3">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <span class="text-muted small fw-semibold text-uppercase">Total Client Calls</span>
              <h3 class="fw-extrabold mb-0 mt-1"><?= number_format($totalRecordings) ?></h3>
              <small class="text-muted"><?= $completedCount ?> analyzed</small>
            </div>
            <div class="avatar bg-soft-primary text-primary rounded-circle p-3">
              <i class="fas fa-headset fa-2x"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card-modern p-3">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <span class="text-muted small fw-semibold text-uppercase">Avg Service Relevance</span>
              <h3 class="fw-extrabold mb-0 mt-1 text-primary"><?= number_format($avgRelevance, 1) ?> <small class="fs-6 text-muted">/ 10</small></h3>
              <small class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i><?= $highRelevancePct ?>% Highly Relevant</small>
            </div>
            <div class="avatar bg-soft-success text-success rounded-circle p-3">
              <i class="fas fa-bullseye fa-2x"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card-modern p-3">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <span class="text-muted small fw-semibold text-uppercase">Policy Violations Flagged</span>
              <h3 class="fw-extrabold mb-0 mt-1 text-danger"><?= number_format($offPlatformCount + $contactShareCount) ?></h3>
              <small class="text-danger fw-semibold"><?= $offPlatformCount ?> Direct Pay | <?= $contactShareCount ?> Contact Share</small>
            </div>
            <div class="avatar bg-soft-danger text-danger rounded-circle p-3">
              <i class="fas fa-exclamation-triangle fa-2x"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card-modern p-3">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <span class="text-muted small fw-semibold text-uppercase">Complaints & Disputes</span>
              <h3 class="fw-extrabold mb-0 mt-1 text-warning"><?= number_format($complaintCount) ?></h3>
              <small class="text-muted"><?= $criticalCount ?> Critical Attention</small>
            </div>
            <div class="avatar bg-soft-warning text-warning rounded-circle p-3">
              <i class="fas fa-shield-alt fa-2x"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Service Category & Alert Filters Bar -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
      <div class="card-body p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <!-- Service Filter Pills -->
        <div class="d-flex flex-wrap gap-2 align-items-center">
          <span class="fw-bold small text-muted text-uppercase me-1"><i class="fas fa-filter me-1 text-primary"></i> Service:</span>
          <a href="<?= base_url('client-call-analysis') ?>" class="service-pill bg-light text-dark <?= empty($selectedService) ? 'active' : '' ?>">
            All Services (<?= $totalRecordings ?>)
          </a>
          <a href="<?= base_url('client-call-analysis?service=walking') ?>" class="service-pill bg-light text-dark <?= $selectedService === 'walking' ? 'active' : '' ?>">
            🐕 Walking
          </a>
          <a href="<?= base_url('client-call-analysis?service=grooming') ?>" class="service-pill bg-light text-dark <?= $selectedService === 'grooming' ? 'active' : '' ?>">
            ✂️ Grooming
          </a>
          <a href="<?= base_url('client-call-analysis?service=training') ?>" class="service-pill bg-light text-dark <?= $selectedService === 'training' ? 'active' : '' ?>">
            🎾 Training
          </a>
          <a href="<?= base_url('client-call-analysis?service=sitting') ?>" class="service-pill bg-light text-dark <?= $selectedService === 'sitting' ? 'active' : '' ?>">
            🏡 Sitting
          </a>
        </div>

        <!-- Quick Flag Badges -->
        <div class="d-flex flex-wrap gap-2 align-items-center">
          <span class="fw-bold small text-muted text-uppercase me-1">Policy Filter:</span>
          <a href="<?= base_url('client-call-analysis?flag=off_platform') ?>" class="badge <?= $selectedFlag === 'off_platform' ? 'bg-danger text-white' : 'bg-soft-danger text-danger' ?> p-2 text-decoration-none">
            🚨 Off-Platform Pay (<?= $offPlatformCount ?>)
          </a>
          <a href="<?= base_url('client-call-analysis?flag=contact_sharing') ?>" class="badge <?= $selectedFlag === 'contact_sharing' ? 'bg-warning text-dark' : 'bg-soft-warning text-dark' ?> p-2 text-decoration-none">
            ⚠️ Contact Sharing (<?= $contactShareCount ?>)
          </a>
          <a href="<?= base_url('client-call-analysis?flag=complaint') ?>" class="badge <?= $selectedFlag === 'complaint' ? 'bg-danger text-white' : 'bg-soft-danger text-danger' ?> p-2 text-decoration-none">
            🟠 Complaints (<?= $complaintCount ?>)
          </a>
        </div>
      </div>
    </div>

    <!-- Analytics Charts Row -->
    <div class="row g-3 mb-4">
      <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm rounded-3 h-100">
          <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0"><i class="fas fa-chart-bar me-2 text-primary"></i> Service Relevance & Call Volume by Service Type</h5>
            <small class="text-muted">Walking, Grooming, Training, Sitting</small>
          </div>
          <div class="card-body px-4 pb-4">
            <canvas id="serviceBreakdownChart" style="max-height: 270px;"></canvas>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm rounded-3 h-100">
          <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
            <h5 class="fw-bold mb-0"><i class="fas fa-pie-chart me-2 text-primary"></i> AI Call Rating Distribution</h5>
          </div>
          <div class="card-body px-4 pb-4 d-flex align-items-center justify-content-center">
            <canvas id="ratingDistributionChart" style="max-height: 250px;"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Call Recordings Table Card -->
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-header bg-transparent border-bottom p-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
          <h5 class="fw-bold mb-0"><i class="fas fa-list-alt me-2 text-primary"></i> Recorded Client & Service Provider Conversations</h5>
          <p class="text-muted small mb-0">Click on any call row to inspect full AI analysis, 3-question summary, highlighted statements & audio playback.</p>
        </div>
        <div class="d-flex gap-2">
          <input type="text" id="searchInput" class="form-control form-control-sm rounded-2" placeholder="Search Client, SP, or Booking ID..." style="width: 250px;">
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" id="clientCallsTable">
            <thead class="bg-light text-uppercase small text-muted">
              <tr>
                <th class="py-3 px-4">Call ID / Date</th>
                <th class="py-3">Service Type</th>
                <th class="py-3">Client (Pet Parent)</th>
                <th class="py-3">Service Provider</th>
                <th class="py-3 text-center">Duration</th>
                <th class="py-3 text-center">Service Relevance</th>
                <th class="py-3 text-center">AI Rating</th>
                <th class="py-3">Policy Alerts</th>
                <th class="py-3 text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($calls)) : ?>
                <?php foreach ($calls as $call) : ?>
                  <?php 
                    $svc = strtolower($call['analyzed_service'] ?: ($call['service_type'] ?: 'walking'));
                    $svcIcon = $svc === 'walking' ? '🐕 Walking' : ($svc === 'grooming' ? '✂️ Grooming' : ($svc === 'training' ? '🎾 Training' : '🏡 Sitting'));
                    $ratingCat = strtolower($call['ai_rating_category'] ?? 'good');
                    $ratingBadge = $ratingCat === 'good' ? 'bg-success' : ($ratingCat === 'review_recommended' ? 'bg-warning text-dark' : 'bg-danger text-white');
                    $relPct = (float)($call['service_related_pct'] ?? 90.0);
                  ?>
                  <tr onclick="openClientCallDetails(<?= (int)$call['id'] ?>)">
                    <td class="px-4 fw-semibold">
                      #<?= $call['id'] ?>
                      <?php if (!empty($call['booking_id'])) : ?>
                        <span class="badge bg-soft-secondary text-dark ms-1">Bk #<?= $call['booking_id'] ?></span>
                      <?php endif; ?>
                      <div class="text-muted small fw-normal"><?= date('d M Y, H:i', strtotime($call['created_at'])) ?></div>
                    </td>
                    <td>
                      <span class="badge bg-soft-primary text-primary px-2 py-1 fw-bold"><?= $svcIcon ?></span>
                    </td>
                    <td>
                      <div class="fw-bold text-dark"><i class="fas fa-user text-muted me-1"></i> <?= esc($call['client_name'] ?: 'Client') ?></div>
                      <?php if (!empty($call['client_phone'])) : ?>
                        <div class="text-muted small"><?= esc($call['client_phone']) ?></div>
                      <?php endif; ?>
                    </td>
                    <td>
                      <div class="fw-bold text-dark"><i class="fas fa-id-badge text-primary me-1"></i> <?= esc($call['provider_name'] ?: 'Service Provider') ?></div>
                      <?php if (!empty($call['provider_phone'])) : ?>
                        <div class="text-muted small"><?= esc($call['provider_phone']) ?></div>
                      <?php endif; ?>
                    </td>
                    <td class="text-center fw-semibold">
                      <?= esc($call['duration']) ?>s
                    </td>
                    <td class="text-center">
                      <div class="d-flex flex-column align-items-center">
                        <span class="badge <?= $relPct >= 80 ? 'bg-soft-success text-success' : 'bg-soft-warning text-warning' ?> fw-bold px-2 py-1">
                          <?= number_format($relPct, 0) ?>% Relevant
                        </span>
                        <?php if ((float)$call['unrelated_pct'] > 10) : ?>
                          <small class="text-muted" style="font-size:0.7rem;"><?= number_format((float)$call['unrelated_pct'], 0) ?>% Unrelated</small>
                        <?php endif; ?>
                      </div>
                    </td>
                    <td class="text-center">
                      <span class="badge <?= $ratingBadge ?> rounded-pill px-3 py-1 fw-bold text-capitalize">
                        <?= $ratingCat === 'good' ? '🟢 Good' : ($ratingCat === 'review_recommended' ? '🟡 Review' : '🔴 Critical') ?>
                      </span>
                    </td>
                    <td>
                      <div class="d-flex flex-wrap gap-1">
                        <?php if (!empty($call['off_platform_payment'])) : ?>
                          <span class="badge bg-danger text-white badge-tag-pill"><i class="fas fa-ban me-1"></i>Off-App Pay</span>
                        <?php endif; ?>
                        <?php if (!empty($call['contact_sharing'])) : ?>
                          <span class="badge bg-warning text-dark badge-tag-pill"><i class="fas fa-phone-slash me-1"></i>Contact Share</span>
                        <?php endif; ?>
                        <?php if (!empty($call['refund_requested'])) : ?>
                          <span class="badge bg-danger text-white badge-tag-pill"><i class="fas fa-undo me-1"></i>Refund</span>
                        <?php endif; ?>
                        <?php if (!empty($call['has_complaint'])) : ?>
                          <span class="badge bg-orange text-dark badge-tag-pill" style="background:#fed7aa;"><i class="fas fa-exclamation-circle me-1"></i>Complaint</span>
                        <?php endif; ?>
                        <?php if (!empty($call['outside_service_request'])) : ?>
                          <span class="badge bg-soft-info text-info badge-tag-pill">Add-on Req</span>
                        <?php endif; ?>
                        <?php if (empty($call['off_platform_payment']) && empty($call['contact_sharing']) && empty($call['refund_requested']) && empty($call['has_complaint'])) : ?>
                          <span class="badge bg-soft-success text-success badge-tag-pill"><i class="fas fa-check me-1"></i>Clean</span>
                        <?php endif; ?>
                      </div>
                    </td>
                    <td class="text-center">
                      <button class="btn btn-sm btn-icon btn-primary rounded-circle" title="View Full AI Analysis">
                        <i class="fas fa-eye"></i>
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else : ?>
                <tr>
                  <td colspan="9" class="text-center py-5 text-muted">
                    <i class="fas fa-info-circle me-2"></i> No Client-SP call recordings found matching criteria. Click "Upload Client-SP Call" to analyze a recorded conversation!
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</main>

<!-- Upload Client Recording Modal -->
<div class="modal fade" id="uploadClientRecordingModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-gradient-client-call text-white p-4">
        <h5 class="modal-title text-white fw-bold"><i class="fas fa-cloud-upload-alt me-2"></i> Upload Client-Provider Call Recording</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="uploadClientRecordingForm" enctype="multipart/form-data">
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label fw-bold">Select Audio File (.mp3, .wav, .m4a, .ogg)</label>
            <input type="file" name="recording" class="form-control" accept="audio/*" required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Booked Service Category</label>
            <select name="service_type" class="form-select form-select-sm" required>
              <option value="walking">🐕 Walking Service</option>
              <option value="grooming">✂️ Grooming Service</option>
              <option value="training">🎾 Training Service</option>
              <option value="sitting">🏡 Sitting Service</option>
            </select>
          </div>
          <div class="row g-3">
            <div class="col-6">
              <label class="form-label small fw-semibold">Booking ID (Optional)</label>
              <input type="number" name="booking_id" class="form-control form-control-sm" placeholder="e.g. 1024">
            </div>
            <div class="col-6">
              <label class="form-label small fw-semibold">Client ID (Optional)</label>
              <input type="number" name="customer_id" class="form-control form-control-sm" placeholder="e.g. 50">
            </div>
            <div class="col-12">
              <label class="form-label small fw-semibold">Provider ID (Optional)</label>
              <input type="number" name="provider_id" class="form-control form-control-sm" placeholder="e.g. 120">
            </div>
          </div>
          <div id="uploadAlert" class="mt-3" style="display:none;"></div>
        </div>
        <div class="modal-footer bg-light p-3">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary btn-sm px-4" id="uploadSubmitBtn">
            <i class="fas fa-paper-plane me-1"></i> Start Upload & Analyze
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Details Offcanvas Drawer -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="clientCallDetailsOffcanvas" style="width: 720px;">
  <div class="offcanvas-header bg-gradient-client-call text-white p-4">
    <div>
      <h5 class="offcanvas-title text-white fw-bold" id="drawerTitle"><i class="fas fa-robot me-2"></i> Client & SP Conversation Analysis</h5>
      <span class="badge bg-white text-primary mt-1" id="drawerServiceBadge">Walking Service</span>
    </div>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body p-4">
    <div id="drawerLoading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status"></div>
      <p class="text-muted mt-2">Loading Conversation Intelligence...</p>
    </div>

    <div id="drawerContent" style="display:none;">
      
      <!-- Audio Player -->
      <div class="p-3 border rounded-3 bg-light mb-4" id="audioPlayerContainer">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <label class="fw-bold mb-0 small text-uppercase text-muted"><i class="fas fa-play-circle me-1 text-primary"></i> Call Recording Playback</label>
          <span id="drawerDurationBadge" class="badge bg-soft-primary text-primary">Duration: 00:00</span>
        </div>
        <audio id="drawerAudio" controls class="w-100"></audio>
      </div>

      <!-- Core 3-Question AI Verdict Banner -->
      <div class="card border-0 shadow-sm rounded-3 mb-4" style="background: #f0fdf4; border-left: 5px solid #22c55e !important;">
        <div class="card-body p-3">
          <h6 class="fw-bold text-success mb-2"><i class="fas fa-clipboard-check me-2"></i> Three-Question AI Summary Verdict</h6>
          
          <div class="mb-2">
            <span class="badge bg-success text-white small me-1">1. What was discussed?</span>
            <p class="small text-dark mb-1 mt-1" id="verdictSummary">-</p>
          </div>
          
          <div class="mb-2">
            <span class="badge bg-info text-white small me-1">2. Was it related to booked service?</span>
            <p class="small text-dark mb-1 mt-1" id="verdictRelevance">-</p>
          </div>

          <div>
            <span class="badge bg-warning text-dark small me-1">3. Admin Attention Required?</span>
            <p class="small text-dark mb-0 mt-1" id="verdictAdminAttention">-</p>
          </div>
        </div>
      </div>

      <!-- Service Relevance & Unrelated Split Progress -->
      <h6 class="fw-bold mb-2"><i class="fas fa-percentage me-2 text-primary"></i> Conversation Composition</h6>
      <div class="p-3 border rounded-3 bg-light mb-4">
        <div class="d-flex justify-content-between small fw-bold mb-1">
          <span class="text-success"><i class="fas fa-check-circle me-1"></i> Service Related: <span id="pctServiceRelated">0%</span></span>
          <span class="text-warning"><i class="fas fa-exclamation-circle me-1"></i> Unrelated: <span id="pctUnrelated">0%</span></span>
        </div>
        <div class="progress" style="height: 12px;">
          <div id="progServiceRelated" class="progress-bar bg-success" role="progressbar" style="width: 85%"></div>
          <div id="progUnrelated" class="progress-bar bg-warning" role="progressbar" style="width: 15%"></div>
        </div>
      </div>

      <!-- AI Alerts Section -->
      <div class="mb-4" id="alertsContainer">
        <h6 class="fw-bold mb-2"><i class="fas fa-bell me-2 text-danger"></i> AI Alerts & Policy Flags</h6>
        <div id="alertsList" class="d-flex flex-column gap-2"></div>
      </div>

      <!-- Highlighted Statements Categorized (Section 24) -->
      <div class="mb-4">
        <h6 class="fw-bold mb-2"><i class="fas fa-highlighter me-2 text-primary"></i> Categorized Statement Highlighting</h6>
        <div id="highlightedStatementsList" class="d-flex flex-column gap-2"></div>
      </div>

      <!-- Chronological Topic Segmentation Timeline (Section 28) -->
      <div class="mb-4">
        <h6 class="fw-bold mb-2"><i class="fas fa-stream me-2 text-primary"></i> Conversation Flow & Topic Segments</h6>
        <div class="p-3 border rounded-3 bg-light">
          <div id="conversationSegmentsList"></div>
        </div>
      </div>

      <!-- Participant Sentiment & Professionalism -->
      <h6 class="fw-bold mb-2"><i class="fas fa-smile me-2 text-primary"></i> Sentiment & Communication Quality</h6>
      <div class="row g-2 mb-4">
        <div class="col-6">
          <div class="p-3 border rounded-3 bg-light text-center">
            <span class="text-muted small text-uppercase">Client Sentiment</span>
            <h6 class="fw-bold mb-0 mt-1" id="detClientSentiment">-</h6>
          </div>
        </div>
        <div class="col-6">
          <div class="p-3 border rounded-3 bg-light text-center">
            <span class="text-muted small text-uppercase">Provider Conduct</span>
            <h6 class="fw-bold mb-0 mt-1" id="detProviderSentiment">-</h6>
          </div>
        </div>
      </div>

      <!-- Admin Review & Remarks Box (Section 15) -->
      <div class="card border-primary mb-4 shadow-sm">
        <div class="card-header bg-soft-primary fw-bold text-primary py-2 px-3 small d-flex justify-content-between align-items-center">
          <span><i class="fas fa-user-shield me-1"></i> Admin Action & Review Remarks</span>
          <span id="reviewStatusBadge" class="badge bg-secondary">Pending Review</span>
        </div>
        <div class="card-body p-3">
          <form id="adminReviewForm">
            <input type="hidden" id="reviewRecordingId" name="recording_id">
            <div class="mb-2">
              <label class="form-label small fw-bold mb-1">Review Status</label>
              <select id="reviewStatusSelect" name="admin_review_status" class="form-select form-select-sm">
                <option value="pending">Pending Review</option>
                <option value="reviewed">Reviewed & Verified</option>
                <option value="resolved">Follow-up Resolved</option>
              </select>
            </div>
            <div class="mb-2">
              <label class="form-label small fw-bold mb-1">Admin Remarks / Notes</label>
              <textarea id="adminRemarksText" name="admin_remarks" class="form-control form-control-sm" rows="2" placeholder="Add follow-up notes, disciplinary action or customer callback log..."></textarea>
            </div>
            <div class="text-end">
              <button type="submit" class="btn btn-sm btn-primary px-3" id="saveReviewBtn">
                <i class="fas fa-save me-1"></i> Save Admin Review
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Actionable AI Recommendations -->
      <div class="mb-4">
        <h6 class="fw-bold mb-2"><i class="fas fa-lightbulb me-2 text-primary"></i> Actionable AI Recommendations</h6>
        <ul class="list-group list-group-flush border rounded-3" id="detRecommendations"></ul>
      </div>

      <!-- Full Interactive Transcript -->
      <h6 class="fw-bold mb-2"><i class="fas fa-quote-left me-2 text-primary"></i> Full Transcript (Translated to English)</h6>
      <div class="transcript-container" id="detTranscript">No transcript available.</div>

    </div>
  </div>
</div>

<script>
  let serviceChart, ratingChart;

  $(document.ready ? document : window).ready(function() {
    loadAnalytics();

    $('#uploadClientRecordingForm').on('submit', function(e) {
      e.preventDefault();
      let formData = new FormData(this);
      let btn = $('#uploadSubmitBtn');
      btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Uploading & Analyzing...');

      $.ajax({
        url: '<?= base_url('client-call-analysis/upload') ?>',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(resp) {
          btn.prop('disabled', false).html('<i class="fas fa-paper-plane me-1"></i> Start Upload & Analyze');
          if (resp.status) {
            $('#uploadAlert').removeClass().addClass('alert alert-success').text(resp.message).show();
            setTimeout(function() { location.reload(); }, 1500);
          } else {
            $('#uploadAlert').removeClass().addClass('alert alert-danger').text(resp.message).show();
          }
        },
        error: function() {
          btn.prop('disabled', false).html('<i class="fas fa-paper-plane me-1"></i> Start Upload & Analyze');
          $('#uploadAlert').removeClass().addClass('alert alert-danger').text('Server error during upload.').show();
        }
      });
    });

    $('#adminReviewForm').on('submit', function(e) {
      e.preventDefault();
      let btn = $('#saveReviewBtn');
      btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');

      $.ajax({
        url: '<?= base_url('client-call-analysis/save-admin-review') ?>',
        type: 'POST',
        data: $(this).serialize(),
        success: function(resp) {
          btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save Admin Review');
          if (resp.status) {
            alert('Admin review successfully saved.');
            location.reload();
          } else {
            alert('Error: ' + resp.message);
          }
        },
        error: function() {
          btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save Admin Review');
          alert('Server error saving review.');
        }
      });
    });

    $('#searchInput').on('keyup', function() {
      let value = $(this).val().toLowerCase();
      $('#clientCallsTable tbody tr').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
  });

  function loadAnalytics() {
    $.getJSON('<?= base_url('client-call-analysis/analytics') ?>', function(resp) {
      if (!resp.status) return;

      // 1. Service Breakdown Bar Chart
      let svcNames = resp.serviceBreakdown.map(s => s.service_name ? s.service_name.charAt(0).toUpperCase() + s.service_name.slice(1) : 'Walking');
      let svcCalls = resp.serviceBreakdown.map(s => parseInt(s.total_calls));
      let svcRel = resp.serviceBreakdown.map(s => parseFloat(s.avg_relevance));

      const ctxSvc = document.getElementById('serviceBreakdownChart').getContext('2d');
      serviceChart = new Chart(ctxSvc, {
        type: 'bar',
        data: {
          labels: svcNames.length ? svcNames : ['Walking', 'Grooming', 'Training', 'Sitting'],
          datasets: [
            {
              label: 'Total Calls',
              data: svcCalls.length ? svcCalls : [15, 8, 6, 4],
              backgroundColor: '#0ea5e9',
              borderRadius: 6
            },
            {
              label: 'Avg Relevance Score (/10)',
              data: svcRel.length ? svcRel : [9.2, 8.8, 9.5, 9.0],
              backgroundColor: '#10b981',
              borderRadius: 6
            }
          ]
        },
        options: {
          responsive: true,
          scales: { y: { beginAtZero: true } }
        }
      });

      // 2. Rating Distribution Doughnut Chart
      let ratLabels = resp.ratingDistribution.map(r => r.category ? r.category.charAt(0).toUpperCase() + r.category.slice(1).replace('_', ' ') : 'Good');
      let ratCounts = resp.ratingDistribution.map(r => parseInt(r.count));

      const ctxRat = document.getElementById('ratingDistributionChart').getContext('2d');
      ratingChart = new Chart(ctxRat, {
        type: 'doughnut',
        data: {
          labels: ratLabels.length ? ratLabels : ['Good', 'Review Recommended', 'Critical'],
          datasets: [{
            data: ratCounts.length ? ratCounts : [22, 6, 2],
            backgroundColor: ['#10b981', '#f59e0b', '#ef4444']
          }]
        },
        options: {
          responsive: true,
          plugins: { legend: { position: 'bottom' } }
        }
      });
    });
  }

  function openClientCallDetails(id) {
    let offcanvas = new bootstrap.Offcanvas(document.getElementById('clientCallDetailsOffcanvas'));
    $('#drawerTitle').text('Client & SP Call #' + id);
    $('#drawerLoading').show();
    $('#drawerContent').hide();
    offcanvas.show();

    $.getJSON('<?= base_url('client-call-analysis/details/') ?>' + id, function(resp) {
      $('#drawerLoading').hide();
      if (!resp.status) return;

      let d = resp.data;
      $('#reviewRecordingId').val(d.recording_id || d.id);
      
      let svc = d.analyzed_service || d.service_type || 'walking';
      $('#drawerServiceBadge').text(svc.toUpperCase() + ' SERVICE CALL');

      // Audio
      if (d.recording_url) {
        let audioSrc = d.recording_url;
        if (!audioSrc.startsWith('http://') && !audioSrc.startsWith('https://')) {
          audioSrc = '<?= base_url() ?>' + audioSrc.replace(/^\//, '');
        }
        $('#drawerAudio').attr('src', audioSrc);
        $('#drawerDurationBadge').text('Duration: ' + (d.duration || 0) + 's');
        $('#audioPlayerContainer').show();
      } else {
        $('#audioPlayerContainer').hide();
      }

      // Three-Question Summary Split
      $('#verdictSummary').text(d.summary || 'Summary generated.');
      
      let relPct = parseFloat(d.service_related_pct || 90.0);
      let unrelPct = parseFloat(d.unrelated_pct || (100.0 - relPct));
      $('#verdictRelevance').text(relPct + '% conversation was related to booked ' + svc + ' service (' + unrelPct + '% unrelated topics).');
      
      let alertsText = [];
      if (parseInt(d.off_platform_payment) === 1) alertsText.push('🚨 Off-platform direct payment detected');
      if (parseInt(d.contact_sharing) === 1) alertsText.push('⚠️ Personal contact sharing attempt detected');
      if (parseInt(d.refund_requested) === 1) alertsText.push('🚨 Client requested a refund');
      if (parseInt(d.has_complaint) === 1) alertsText.push('🟠 Service complaint voiced');
      if (parseInt(d.has_abuse) === 1) alertsText.push('🚨 Inappropriate / abusive language detected');

      $('#verdictAdminAttention').text(alertsText.length ? alertsText.join('; ') + '.' : 'No critical compliance concerns or disputes flagged.');

      // Progress bars
      $('#pctServiceRelated').text(relPct.toFixed(1) + '%');
      $('#pctUnrelated').text(unrelPct.toFixed(1) + '%');
      $('#progServiceRelated').css('width', relPct + '%');
      $('#progUnrelated').css('width', unrelPct + '%');

      // Alerts List
      let alertsHtml = '';
      if (d.alerts && d.alerts.length) {
        d.alerts.forEach(a => {
          let alertClass = a.type === 'Critical Alert' ? 'alert-danger' : (a.type === 'Warning' ? 'alert-warning' : 'alert-success');
          alertsHtml += `
            <div class="alert ${alertClass} py-2 px-3 mb-0 small rounded-2 d-flex align-items-center gap-2">
              <span class="fs-5">${a.icon || '⚠️'}</span>
              <div>
                <strong>${a.category || a.type}:</strong> ${a.message}
              </div>
            </div>
          `;
        });
      } else {
        alertsHtml = '<div class="text-muted small">No policy alerts logged.</div>';
      }
      $('#alertsList').html(alertsHtml);

      // Highlighted Statements (Section 24)
      let hlHtml = '';
      if (d.highlighted_conversations && d.highlighted_conversations.length) {
        d.highlighted_conversations.forEach(h => {
          let cat = h.category || '';
          let badgeClass = 'highlight-badge-info';
          if (cat.includes('Policy') || cat.includes('Serious')) badgeClass = 'highlight-badge-policy';
          else if (cat.includes('Unrelated')) badgeClass = 'highlight-badge-unrelated';
          else if (cat.includes('Complaint')) badgeClass = 'highlight-badge-complaint';
          else if (cat.includes('Service')) badgeClass = 'highlight-badge-service';

          hlHtml += `
            <div class="p-2 rounded-2 ${badgeClass} small">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <strong>${h.category_icon || '🏷️'} ${h.category}</strong>
                <span class="badge bg-white text-dark">${h.timestamp || '00:00'}</span>
              </div>
              <div class="fst-italic text-dark">"${h.sentence}"</div>
              ${h.note ? `<div class="text-muted mt-1" style="font-size:0.75rem;">ℹ️ ${h.note}</div>` : ''}
            </div>
          `;
        });
      } else {
        hlHtml = '<div class="text-muted small">No specific highlighted statements flagged.</div>';
      }
      $('#highlightedStatementsList').html(hlHtml);

      // Conversation Timeline Segments (Section 28)
      let segHtml = '';
      if (d.conversation_segments && d.conversation_segments.length) {
        d.conversation_segments.forEach(seg => {
          let badgeColor = seg.color === 'danger' ? 'bg-danger' : (seg.color === 'warning' ? 'bg-warning text-dark' : (seg.color === 'success' ? 'bg-success' : 'bg-secondary'));
          segHtml += `
            <div class="timeline-segment-item small">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="badge ${badgeColor}">${seg.type || 'Service Segment'}</span>
                <span class="text-muted small fw-bold">${seg.timestamp || ''}</span>
              </div>
              <div class="text-dark">${seg.text}</div>
            </div>
          `;
        });
      } else {
        segHtml = '<div class="text-muted small">Timeline segmentation pending.</div>';
      }
      $('#conversationSegmentsList').html(segHtml);

      // Sentiments
      $('#detClientSentiment').text(d.customer_sentiment || 'Positive');
      $('#detProviderSentiment').text(d.provider_sentiment || 'Professional');

      // Admin Review
      let revStatus = d.admin_review_status || 'pending';
      $('#reviewStatusSelect').val(revStatus);
      $('#adminRemarksText').val(d.admin_remarks || '');
      $('#reviewStatusBadge').text(revStatus.toUpperCase()).removeClass().addClass('badge ' + (revStatus === 'resolved' ? 'bg-success' : (revStatus === 'reviewed' ? 'bg-primary' : 'bg-secondary')));

      // Recommendations
      let recHtml = '';
      if (d.recommendations && d.recommendations.length) {
        d.recommendations.forEach(r => {
          recHtml += `<li class="list-group-item bg-transparent text-dark small"><i class="fas fa-arrow-right text-primary me-2"></i>${r}</li>`;
        });
      } else {
        recHtml = '<li class="list-group-item bg-transparent text-muted small">No action items required.</li>';
      }
      $('#detRecommendations').html(recHtml);

      // Transcript
      $('#detTranscript').text(d.transcript || 'Transcript generation in progress.');

      $('#drawerContent').show();
    });
  }
</script>

<?php echo $this->include('admin/footer') ?>
<?php echo $this->endSection() ?>
=======
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
$totalRecordings   = $totalRecordings ?? 0;
$completedCount    = $completedCount ?? 0;
$queuedCount       = $queuedCount ?? 0;
$failedCount       = $failedCount ?? 0;
$avgRelevance      = $avgRelevance ?? 9.0;
$avgRating         = $avgRating ?? 9.0;
$highRelevancePct  = $highRelevancePct ?? 100.0;
$offPlatformCount  = $offPlatformCount ?? 0;
$contactShareCount = $contactShareCount ?? 0;
$complaintCount    = $complaintCount ?? 0;
$outsideSvcCount   = $outsideSvcCount ?? 0;
$criticalCount     = $criticalCount ?? 0;
$reviewCount       = $reviewCount ?? 0;
$selectedService   = $selectedService ?? '';
$selectedRating    = $selectedRating ?? '';
$selectedFlag      = $selectedFlag ?? '';
$page              = $page ?? 1;
$perPage           = $perPage ?? 25;
$totalFiltered     = $totalFiltered ?? 0;
$totalPages        = $totalPages ?? 1;
$search            = $search ?? '';
$calls             = $calls ?? [];

// Helper closure for building page URLs with query parameters
$buildPageUrl = function($pageNum) use ($selectedService, $selectedRating, $selectedFlag, $search, $perPage) {
    $params = [
        'page'     => $pageNum,
        'per_page' => $perPage,
    ];
    if (!empty($selectedService)) $params['service'] = $selectedService;
    if (!empty($selectedRating)) $params['rating'] = $selectedRating;
    if (!empty($selectedFlag)) $params['flag'] = $selectedFlag;
    if (!empty($search)) $params['search'] = $search;
    return base_url('client-call-analysis?' . http_build_query($params));
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
  .badge-service.walking { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
  .badge-service.grooming { background: #f5f3ff; color: #6d28d9; border: 1px solid #ddd6fe; }
  .badge-service.boarding { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
  .badge-service.sitting { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
  .badge-service.training { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

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
  .status-good { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
  .status-good .status-dot { background: #10b981; }
  .status-review { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
  .status-review .status-dot { background: #f59e0b; }
  .status-critical { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
  .status-critical .status-dot { background: #ef4444; }

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

  /* Policy Badges */
  .badge-tag-pill {
    font-size: 0.72rem;
    padding: 3px 8px;
    border-radius: 6px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 3px;
  }

  /* Timeline and Drawer Specifics */
  .timeline-segment-item {
    position: relative;
    padding-left: 24px;
    margin-bottom: 14px;
  }
  .timeline-segment-item::before {
    content: '';
    position: absolute;
    left: 4px;
    top: 6px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #4f46e5;
  }
  .timeline-segment-item::after {
    content: '';
    position: absolute;
    left: 8px;
    top: 18px;
    bottom: -10px;
    width: 2px;
    background: #e2e8f0;
  }
  .timeline-segment-item:last-child::after {
    display: none;
  }
  .transcript-container {
    max-height: 380px;
    overflow-y: auto;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px;
    font-family: inherit;
    white-space: pre-wrap;
    font-size: 0.88rem;
    line-height: 1.6;
  }
  .highlight-badge-service {
    background: #dcfce7;
    color: #15803d;
    border-left: 4px solid #22c55e;
  }
  .highlight-badge-unrelated {
    background: #fef9c3;
    color: #854d0e;
    border-left: 4px solid #eab308;
  }
  .highlight-badge-policy {
    background: #fee2e2;
    color: #991b1b;
    border-left: 4px solid #ef4444;
  }
  .highlight-badge-complaint {
    background: #ffedd5;
    color: #9a3412;
    border-left: 4px solid #f97316;
  }
  .highlight-badge-info {
    background: #e0f2fe;
    color: #075985;
    border-left: 4px solid #0ea5e9;
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
            <span>Client-Provider Call Intelligence</span>
          </div>
          <h2 class="fw-bold text-white mb-2" style="font-size: 30px; letter-spacing: -0.02em;">Client & Provider Calls</h2>
          <p class="mb-0 text-white text-opacity-75" style="font-size: 13.5px; max-width: 560px; line-height: 1.55;">
            Automated speech diagnostics of client-to-provider recorded calls across Walking, Grooming, Training, and Sitting.
            Highlights service relevance and flags off-platform arrangements, contact sharing, or disputes.
          </p>
        </div>

        <div class="col-lg-6">
          <div class="d-flex align-items-center gap-2 flex-wrap justify-content-lg-end">
            <!-- Total Calls -->
            <div class="nl-stat-card">
              <span class="label">Total Calls</span>
              <div class="value"><?= number_format($totalRecordings) ?></div>
            </div>
            <!-- Avg Relevance -->
            <div class="nl-stat-card">
              <span class="label">Avg Relevance</span>
              <div class="value" style="color: #93c5fd; font-size: 22px;">
                <?= number_format($avgRelevance, 1) ?><small style="font-size: 12px; font-weight: 500; opacity: 0.8;">/10</small>
              </div>
            </div>
            <!-- High Relevance Pct -->
            <div class="nl-stat-card">
              <span class="label">Relevant Calls</span>
              <div class="value" style="color: #a7f3d0;"><?= number_format($highRelevancePct, 0) ?>%</div>
            </div>
            <!-- Policy Flags -->
            <div class="nl-stat-card">
              <span class="label">Policy Flags</span>
              <div class="value" style="color: <?= ($offPlatformCount + $contactShareCount) > 0 ? '#fca5a5' : '#6ee7b7' ?>;">
                <?= number_format($offPlatformCount + $contactShareCount) ?>
              </div>
            </div>
            <!-- Complaints -->
            <div class="nl-stat-card">
              <span class="label">Complaints</span>
              <div class="value" style="color: <?= $complaintCount > 0 ? '#fde047' : '#ffffff' ?>;">
                <?= number_format($complaintCount) ?>
              </div>
            </div>
            <!-- SP Holds Active -->
            <div class="nl-stat-card" style="<?= !empty($openCasesCount) ? 'background: rgba(239, 68, 68, 0.25); border-color: rgba(239, 68, 68, 0.5);' : '' ?>">
              <span class="label">SP Holds Active</span>
              <div class="value" style="color: <?= !empty($openCasesCount) ? '#fca5a5' : '#a7f3d0' ?>;">
                <?= number_format($openCasesCount ?? 0) ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Segmented Tabs & Action Bar matching Bookings -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
      <div class="nl-segmented-tabs">
        <a href="<?= base_url('call-analysis?type=verification') ?>" class="nl-tab-pill">
          <i class="bi bi-shield-check"></i>
          <span>SP Verification Calls</span>
        </a>
        <a href="<?= base_url('call-analysis?type=registration') ?>" class="nl-tab-pill">
          <i class="bi bi-person-plus-fill"></i>
          <span>Registration Calls</span>
        </a>
        <a href="<?= base_url('client-call-analysis') ?>" class="nl-tab-pill active">
          <i class="bi bi-headset"></i>
          <span>Client-SP Calls</span>
          <span class="nl-tab-badge"><?= number_format($totalRecordings) ?></span>
        </a>
      </div>

      <div class="d-flex align-items-center gap-2">
        <button type="button" class="nl-btn-action" data-bs-toggle="modal" data-bs-target="#uploadClientRecordingModal">
          <i class="bi bi-cloud-arrow-up-fill text-primary"></i>
          <span>Upload Client-SP Call</span>
        </button>
        <?php if ($totalRecordings > 0): ?>
          <button type="button" class="nl-btn-action" style="color: #ef4444; border-color: rgba(239, 68, 68, 0.3); background: rgba(239, 68, 68, 0.05);" onclick="confirmClearAllCalls(this)">
            <i class="bi bi-trash3-fill text-danger"></i>
            <span class="text-danger fw-semibold">Clear All Calls</span>
          </button>
        <?php endif; ?>
      </div>
    </div>

    <!-- Modern Search and Filter Toolbar matching Bookings -->
    <div class="nl-filter-card">
      <form method="GET" action="<?= base_url('client-call-analysis') ?>" id="clientCallFilterForm">
        <div class="row g-3 align-items-center">
          <div class="col-lg-3 col-md-4">
            <div class="nl-search-wrap">
              <i class="bi bi-search search-icon"></i>
              <input type="text" class="nl-search-input" name="search" id="searchInput"
                placeholder="Search Client, SP, or Call ID..." value="<?= esc($search ?? '') ?>">
            </div>
          </div>
          <div class="col-lg-3 col-md-3">
            <select class="nl-select w-100" name="service" id="serviceFilter" onchange="this.form.submit()">
              <option value="">All Services</option>
              <option value="walking" <?= ($selectedService === 'walking') ? 'selected' : '' ?>>🐕 Dog Walking</option>
              <option value="grooming" <?= ($selectedService === 'grooming') ? 'selected' : '' ?>>✂️ Pet Grooming</option>
              <option value="training" <?= ($selectedService === 'training') ? 'selected' : '' ?>>🎾 Pet Training</option>
              <option value="sitting" <?= ($selectedService === 'sitting') ? 'selected' : '' ?>>🏡 Pet Sitting</option>
            </select>
          </div>
          <div class="col-lg-2 col-md-2">
            <select class="nl-select w-100" name="flag" id="flagFilter" onchange="this.form.submit()">
              <option value="">All Policy Flags</option>
              <option value="off_platform" <?= ($selectedFlag === 'off_platform') ? 'selected' : '' ?>>🚨 Off-Platform Pay (<?= $offPlatformCount ?>)</option>
              <option value="contact_sharing" <?= ($selectedFlag === 'contact_sharing') ? 'selected' : '' ?>>⚠️ Contact Sharing (<?= $contactShareCount ?>)</option>
              <option value="complaint" <?= ($selectedFlag === 'complaint') ? 'selected' : '' ?>>🟠 Complaints (<?= $complaintCount ?>)</option>
              <option value="refund" <?= ($selectedFlag === 'refund') ? 'selected' : '' ?>>💸 Refund Request</option>
              <option value="outside_service" <?= ($selectedFlag === 'outside_service') ? 'selected' : '' ?>>➕ Add-on / Outside Svc</option>
            </select>
          </div>
          <div class="col-lg-2 col-md-2">
            <select class="nl-select w-100" name="rating" id="ratingFilter" onchange="this.form.submit()">
              <option value="">All AI Ratings</option>
              <option value="good" <?= ($selectedRating === 'good') ? 'selected' : '' ?>>🟢 Good Conduct</option>
              <option value="review_recommended" <?= ($selectedRating === 'review_recommended') ? 'selected' : '' ?>>🟡 Review Advised</option>
              <option value="critical" <?= ($selectedRating === 'critical') ? 'selected' : '' ?>>🔴 Critical Issue</option>
            </select>
          </div>
          <div class="col-lg-2 col-md-1">
            <div class="d-flex align-items-center justify-content-md-end gap-2">
              <label class="text-muted small mb-0 fw-semibold">Show</label>
              <select class="nl-select" style="width: 75px;" name="per_page" id="entriesPerPage" onchange="changePerPage(this.value)">
                <option value="15" <?= ($perPage == 15) ? 'selected' : '' ?>>15</option>
                <option value="25" <?= ($perPage == 25) ? 'selected' : '' ?>>25</option>
                <option value="50" <?= ($perPage == 50) ? 'selected' : '' ?>>50</option>
                <option value="100" <?= ($perPage == 100) ? 'selected' : '' ?>>100</option>
              </select>
              <?php if (!empty($search) || !empty($selectedService) || !empty($selectedFlag) || !empty($selectedRating)): ?>
                <a href="<?= base_url('client-call-analysis') ?>" class="btn btn-sm btn-outline-danger ms-1" title="Clear Filters">
                  <i class="bi bi-x-circle"></i>
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </form>
    </div>

    <!-- Client-SP Call Recordings Data Table Card -->
    <div class="nl-table-card">
      <div class="table-responsive">
        <table class="table nl-table mb-0" id="clientCallsTable">
          <thead>
            <tr>
              <th class="text-center" style="width: 75px;">Call ID</th>
              <th>Service</th>
              <th>Client (Pet Parent)</th>
              <th>Service Provider</th>
              <th class="text-center">Duration & Timing</th>
              <th class="text-center">Service Relevance</th>
              <th class="text-center">AI Conduct</th>
              <th>Policy Alerts</th>
              <th class="text-center" style="width: 80px;">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($calls)) : ?>
              <?php foreach ($calls as $call) :
                $isAnalyzed = !empty($call['analysis_id']);
                $isQueuedOrProcessing = in_array(strtolower($call['status'] ?? ''), ['queued', 'processing', 'uploaded']) || !$isAnalyzed;

                $rawSvc = strtolower($call['analyzed_service'] ?: ($call['service_type'] ?: ''));
                if (empty($rawSvc) && !empty($call['booking_id'])) {
                    $bkCode = strtoupper(trim((string)$call['booking_id']));
                    if (preg_match('/(STT|STTE|STTP|STTT)$/', $bkCode)) $rawSvc = 'sitting';
                    elseif (preg_match('/(WLK|WLKE|WLKP|WLKT)$/', $bkCode)) $rawSvc = 'walking';
                    elseif (preg_match('/(GRM|GRME|GRMP|GRMT)$/', $bkCode)) $rawSvc = 'grooming';
                    elseif (preg_match('/(TRN|TRNE|TRNP|TRNT)$/', $bkCode)) $rawSvc = 'training';
                    elseif (preg_match('/(BRD)$/', $bkCode)) $rawSvc = 'boarding';
                }
                $svcClass = in_array($rawSvc, ['walking', 'grooming', 'training', 'sitting', 'boarding']) ? $rawSvc : 'sitting';
                $svcLabel = $rawSvc === 'walking' ? 'Walking' : ($rawSvc === 'grooming' ? 'Grooming' : ($rawSvc === 'training' ? 'Training' : ($rawSvc === 'sitting' ? 'Sitting' : ($rawSvc === 'boarding' ? 'Boarding' : ($isQueuedOrProcessing ? 'Queued' : 'General')))));

                $clientDisplay = !empty($call['client_name']) ? $call['client_name'] : (!empty($call['customer_id']) ? 'Client #' . $call['customer_id'] : 'Client');
                $cAvatar = getInitialsAvatar($clientDisplay, $call['customer_id'] ?? $call['id']);

                $spDisplay = !empty($call['provider_name']) ? $call['provider_name'] : (!empty($call['provider_id']) ? 'Provider #' . $call['provider_id'] : 'Provider');
                $pAvatar = getInitialsAvatar($spDisplay, $call['provider_id'] ?? $call['id']);

                $cDate = !empty($call['call_started_at']) && $call['call_started_at'] !== '0000-00-00 00:00:00'
                    ? $call['call_started_at']
                    : (!empty($call['call_initiated_at']) && $call['call_initiated_at'] !== '0000-00-00 00:00:00'
                        ? $call['call_initiated_at']
                        : (!empty($call['created_at']) && $call['created_at'] !== '0000-00-00 00:00:00' ? $call['created_at'] : ($call['updated_at'] ?? '')));
                $cTs = !empty($cDate) ? strtotime($cDate) : 0;
                $dateFormatted = ($cTs && $cTs > 946684800) ? date('M d, Y', $cTs) : 'Recently';

                $ratingCat = strtolower($call['ai_rating_category'] ?? '');
                $statusPillClass = $ratingCat === 'good' ? 'status-good' : ($ratingCat === 'review_recommended' ? 'status-review' : ($ratingCat === 'critical' ? 'status-critical' : 'status-review'));
                $statusLabel = $ratingCat === 'good' ? 'Good' : ($ratingCat === 'review_recommended' ? 'Review' : ($ratingCat === 'critical' ? 'Critical' : 'Pending'));

                $relPct = isset($call['service_related_pct']) && $call['service_related_pct'] !== null && $call['service_related_pct'] !== '' ? (float)$call['service_related_pct'] : null;
              ?>
                <tr onclick="openClientCallDetails(<?= (int)$call['id'] ?>)">
                  <!-- Call ID & Date -->
                  <td class="text-center align-middle">
                    <span class="fw-bold" style="color: #4f46e5; font-size: 13px;">#<?= $call['id'] ?></span>
                    <?php if (!empty($call['booking_id'])) : ?>
                      <div><span class="badge bg-soft-secondary text-dark" style="font-size: 10px;">Bk #<?= $call['booking_id'] ?></span></div>
                    <?php endif; ?>
                    <div class="text-muted small mt-0.5" style="font-size: 11px;"><?= $dateFormatted ?></div>
                  </td>

                  <!-- Service Type Badge -->
                  <td class="align-middle">
                    <span class="badge-service <?= $svcClass ?>">
                      <i class="bi <?= $svcClass === 'walking' ? 'bi-compass' : ($svcClass === 'grooming' ? 'bi-scissors' : 'bi-award') ?>"></i>
                      <?= $svcLabel ?>
                    </span>
                  </td>

                  <!-- Client (Pet Parent) with Pastel Initials Avatar -->
                  <td class="align-middle">
                    <div class="d-flex align-items-center gap-3">
                      <div class="nl-avatar" style="background: <?= $cAvatar['bg'] ?>; color: <?= $cAvatar['color'] ?>;">
                        <?= $cAvatar['text'] ?>
                      </div>
                      <div>
                        <div class="fw-bold text-dark" style="font-size: 13.5px; line-height: 1.3;">
                          <?= htmlspecialchars($clientDisplay) ?>
                        </div>
                        <?php if (!empty($call['client_phone'])) : ?>
                          <div class="d-flex align-items-center text-muted small mt-1 gap-1" style="font-size: 11.5px;">
                            <i class="bi bi-telephone text-secondary"></i>
                            <a href="tel:<?= $call['client_phone'] ?>" class="text-decoration-none text-muted" onclick="event.stopPropagation();">
                              <?= $call['client_phone'] ?>
                            </a>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </td>

                  <!-- Service Provider with Pastel Initials Avatar -->
                  <td class="align-middle">
                    <div class="d-flex align-items-center gap-3">
                      <div class="nl-avatar" style="background: <?= $pAvatar['bg'] ?>; color: <?= $pAvatar['color'] ?>;">
                        <?= $pAvatar['text'] ?>
                      </div>
                      <div>
                        <div class="fw-bold text-dark" style="font-size: 13.5px; line-height: 1.3;">
                          <?= htmlspecialchars($spDisplay) ?>
                        </div>
                        <?php if (!empty($call['provider_phone'])) : ?>
                          <div class="d-flex align-items-center text-muted small mt-1 gap-1" style="font-size: 11.5px;">
                            <i class="bi bi-telephone text-secondary"></i>
                            <a href="tel:<?= $call['provider_phone'] ?>" class="text-decoration-none text-muted" onclick="event.stopPropagation();">
                              <?= $call['provider_phone'] ?>
                            </a>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </td>

                  <!-- Duration & Call Timing Column -->
                  <td class="align-middle text-center">
                    <div class="d-inline-flex flex-column align-items-center">
                      <?php if (!empty($call['formatted_duration']) && $call['formatted_duration'] !== '-') : ?>
                        <span class="badge-urgency upcoming mb-1">
                          <i class="bi bi-clock me-0.5"></i>
                          <?= esc($call['formatted_duration']) ?>
                        </span>
                      <?php elseif (!empty($call['duration']) && (int)$call['duration'] > 0) : ?>
                        <span class="badge-urgency upcoming mb-1">
                          <i class="bi bi-clock me-0.5"></i>
                          <?= esc($call['duration']) ?>s
                        </span>
                      <?php else : ?>
                        <span class="text-muted small mb-1">-</span>
                      <?php endif; ?>

                      <?php if (!empty($call['start_time_formatted']) && $call['start_time_formatted'] !== '-') : ?>
                        <div class="text-nowrap" style="font-size: 11px; line-height: 1.35;">
                          <div class="text-muted" title="Call Start Time"><span class="text-success fw-bold">Start:</span> <?= esc($call['start_time_formatted']) ?></div>
                          <div class="text-muted" title="Call End Time"><span class="text-danger fw-bold">End:</span> <?= esc($call['end_time_formatted']) ?></div>
                        </div>
                      <?php endif; ?>
                    </div>
                  </td>

                  <!-- Service Relevance -->
                  <td class="align-middle text-center">
                    <?php if ($isAnalyzed && $relPct !== null) : ?>
                      <div class="d-flex flex-column align-items-center">
                        <span class="badge <?= $relPct >= 80 ? 'bg-soft-success text-success' : 'bg-soft-warning text-warning' ?> fw-bold px-2.5 py-1" style="font-size: 11.5px; border-radius: 50px;">
                          <?= number_format($relPct, 0) ?>% Relevant
                        </span>
                        <?php if ((float)($call['unrelated_pct'] ?? 0) > 10) : ?>
                          <small class="text-muted mt-0.5" style="font-size: 10.5px;"><?= number_format((float)$call['unrelated_pct'], 0) ?>% Unrelated</small>
                        <?php endif; ?>
                      </div>
                    <?php else : ?>
                      <span class="badge bg-soft-secondary text-muted px-2.5 py-1" style="font-size: 11px; border-radius: 50px;">
                        <i class="bi bi-hourglass-split me-1"></i>Pending
                      </span>
                    <?php endif; ?>
                  </td>

                  <!-- AI Rating Status Pill -->
                  <td class="align-middle text-center">
                    <?php if ($isAnalyzed && !empty($call['ai_rating_category'])) : ?>
                      <span class="status-pill <?= $statusPillClass ?>">
                        <span class="status-dot"></span>
                        <span><?= $statusLabel ?></span>
                      </span>
                    <?php else : ?>
                      <span class="status-pill" style="background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0;">
                        <span class="status-dot" style="background: #94a3b8;"></span>
                        <span><?= ($call['status'] === 'processing') ? 'Analyzing...' : 'Queued' ?></span>
                      </span>
                    <?php endif; ?>
                  </td>

                  <!-- Policy Alerts -->
                  <td class="align-middle">
                    <?php if ($isAnalyzed) : ?>
                      <div class="d-flex flex-wrap gap-1">
                        <?php if (!empty($call['off_platform_payment'])) : ?>
                          <span class="badge bg-danger text-white badge-tag-pill"><i class="bi bi-slash-circle me-1"></i>Off-App Pay</span>
                        <?php endif; ?>
                        <?php if (!empty($call['contact_sharing'])) : ?>
                          <span class="badge bg-warning text-dark badge-tag-pill"><i class="bi bi-telephone-x me-1"></i>Contact Share</span>
                        <?php endif; ?>
                        <?php if (!empty($call['refund_requested'])) : ?>
                          <span class="badge bg-danger text-white badge-tag-pill"><i class="bi bi-arrow-counterclockwise me-1"></i>Refund</span>
                        <?php endif; ?>
                        <?php if (!empty($call['has_complaint'])) : ?>
                          <span class="badge text-dark badge-tag-pill" style="background:#fed7aa;"><i class="bi bi-exclamation-triangle me-1"></i>Complaint</span>
                        <?php endif; ?>
                        <?php if (!empty($call['outside_service_request'])) : ?>
                          <span class="badge bg-soft-info text-info badge-tag-pill">Add-on Req</span>
                        <?php endif; ?>
                        <?php if (empty($call['off_platform_payment']) && empty($call['contact_sharing']) && empty($call['refund_requested']) && empty($call['has_complaint'])) : ?>
                          <span class="badge bg-soft-success text-success badge-tag-pill"><i class="bi bi-check-circle me-1"></i>Clean</span>
                        <?php endif; ?>
                      </div>
                      <?php if (!empty($call['violation_case_id'])) : ?>
                        <div class="mt-1 d-flex flex-wrap align-items-center gap-1">
                          <?php if (($call['violation_case_status'] ?? '') === 'open') : ?>
                            <button type="button" class="btn btn-sm btn-danger py-0 px-2 fw-bold d-inline-flex align-items-center gap-1 shadow-sm" style="font-size: 10px; border-radius: 4px;" onclick="event.stopPropagation(); openViolationCaseModal(<?= (int)$call['violation_case_id'] ?>);">
                              <i class="bi bi-shield-lock-fill"></i> Case: <?= esc($call['violation_case_number']) ?> (Hold Active)
                            </button>
                          <?php elseif (($call['violation_case_status'] ?? '') === 'confirmed') : ?>
                            <button type="button" class="btn btn-sm btn-dark py-0 px-2 fw-bold d-inline-flex align-items-center gap-1" style="font-size: 10px; border-radius: 4px;" onclick="event.stopPropagation(); openViolationCaseModal(<?= (int)$call['violation_case_id'] ?>);">
                              <i class="bi bi-shield-x"></i> Strike Confirmed
                            </button>
                          <?php elseif (($call['violation_case_status'] ?? '') === 'dismissed') : ?>
                            <button type="button" class="btn btn-sm btn-success py-0 px-2 fw-bold d-inline-flex align-items-center gap-1" style="font-size: 10px; border-radius: 4px;" onclick="event.stopPropagation(); openViolationCaseModal(<?= (int)$call['violation_case_id'] ?>);">
                              <i class="bi bi-shield-check"></i> Hold Released
                            </button>
                          <?php endif; ?>
                          <?php if (!empty($call['affected_bookings_count']) && (int)$call['affected_bookings_count'] > 0) : ?>
                            <span class="badge bg-warning text-dark py-0.5 px-1.5" style="font-size: 9.5px;" title="Ongoing Active Bookings">
                              <i class="bi bi-calendar-check me-0.5"></i><?= (int)$call['affected_bookings_count'] ?> Active
                            </span>
                          <?php endif; ?>
                        </div>
                      <?php endif; ?>
                    <?php else : ?>
                      <span class="badge bg-soft-secondary text-muted badge-tag-pill"><i class="bi bi-clock me-1"></i>Awaiting AI</span>
                    <?php endif; ?>
                  </td>

                  <!-- Actions -->
                  <td class="align-middle text-center" onclick="event.stopPropagation();">
                    <div class="d-inline-flex align-items-center gap-1">
                      <button type="button" class="nl-action-btn view" onclick="openClientCallDetails(<?= (int)$call['id'] ?>)" title="View Full AI Analysis">
                        <i class="bi bi-eye"></i>
                      </button>
                      <?php if (!empty($call['violation_case_id'])) : ?>
                        <button type="button" class="nl-action-btn" style="color: #ef4444; border-color: rgba(239, 68, 68, 0.3); background: rgba(239, 68, 68, 0.08);" onclick="openViolationCaseModal(<?= (int)$call['violation_case_id'] ?>)" title="Review Critical Violation Case & Hold">
                          <i class="bi bi-shield-exclamation"></i>
                        </button>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else : ?>
              <tr>
                <td colspan="9" class="text-center py-5 text-muted">
                  <div class="py-4">
                    <i class="bi bi-headset fs-1 text-secondary opacity-50 d-block mb-2"></i>
                    <h6 class="text-dark fw-bold mb-1">No Client-SP Calls Found</h6>
                    <p class="text-muted small mb-3">No client-provider calls match your current filter selections. Upload a recording to run AI analysis.</p>
                    <button type="button" class="nl-btn-action" data-bs-toggle="modal" data-bs-target="#uploadClientRecordingModal">
                      <i class="bi bi-cloud-arrow-up-fill text-primary"></i>
                      <span>Upload Client-SP Call</span>
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

    <!-- Analytics Charts Row matching Bookings Cards -->
    <div class="row g-3 mb-4">
      <div class="col-12 col-lg-8">
        <div class="nl-table-card h-100 p-4" style="margin-bottom: 0;">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <h5 class="fw-bold mb-0 text-dark" style="font-size: 16px;">
                <i class="bi bi-bar-chart text-primary me-2"></i> Service Relevance & Volume Breakdown
              </h5>
              <small class="text-muted">Walking, Grooming, Training, Sitting</small>
            </div>
          </div>
          <div>
            <canvas id="serviceBreakdownChart" style="max-height: 270px;"></canvas>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-4">
        <div class="nl-table-card h-100 p-4" style="margin-bottom: 0;">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <h5 class="fw-bold mb-0 text-dark" style="font-size: 16px;">
                <i class="bi bi-pie-chart text-primary me-2"></i> AI Call Conduct Distribution
              </h5>
              <small class="text-muted">Good vs Review vs Critical</small>
            </div>
          </div>
          <div class="d-flex align-items-center justify-content-center pt-2">
            <canvas id="ratingDistributionChart" style="max-height: 250px;"></canvas>
          </div>
        </div>
      </div>
    </div>

  </div>
</main>

<!-- Upload Client Recording Modal matching Modern Dashboard -->
<div class="modal fade" id="uploadClientRecordingModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
      <div class="modal-header px-4 py-3 border-0 text-white" style="background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 25%, #3730a3 60%, #4f46e5 100%) !important;">
        <div class="d-flex align-items-center gap-2.5">
          <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: rgba(255, 255, 255, 0.15);">
            <i class="bi bi-cloud-arrow-up text-warning fs-5"></i>
          </div>
          <div>
            <h5 class="modal-title text-white fw-bold mb-0" style="font-size: 17px;">Upload Client-Provider Call</h5>
            <small class="text-white text-opacity-75" style="font-size: 12px;">Transcribe, detect policy breaches, and score conduct</small>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="uploadClientRecordingForm" enctype="multipart/form-data">
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label fw-bold small text-dark">Select Audio File (.mp3, .wav, .m4a, .ogg)</label>
            <input type="file" name="recording" class="form-control nl-search-input" accept="audio/*" required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold text-dark">Booked Service Category</label>
            <select name="service_type" class="nl-select w-100" required>
              <option value="walking">🐕 Dog Walking Service</option>
              <option value="grooming">✂️ Pet Grooming Service</option>
              <option value="training">🎾 Pet Training Service</option>
              <option value="sitting">🏡 Pet Sitting Service</option>
            </select>
          </div>
          <div class="row g-3">
            <div class="col-6">
              <label class="form-label small fw-semibold text-dark">Booking ID (Optional)</label>
              <input type="number" name="booking_id" class="form-control nl-search-input" placeholder="e.g. 1024">
            </div>
            <div class="col-6">
              <label class="form-label small fw-semibold text-dark">Client ID (Optional)</label>
              <input type="number" name="customer_id" class="form-control nl-search-input" placeholder="e.g. 50">
            </div>
            <div class="col-12">
              <label class="form-label small fw-semibold text-dark">Service Provider ID (Optional)</label>
              <input type="number" name="provider_id" class="form-control nl-search-input" placeholder="e.g. 120">
            </div>
          </div>
          <div id="uploadAlert" class="mt-3" style="display:none;"></div>
        </div>
        <div class="modal-footer bg-light px-4 py-3 border-top">
          <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-sm btn-primary px-4 fw-semibold" id="uploadSubmitBtn" style="background: #4f46e5; border-color: #4f46e5;">
            <i class="bi bi-cloud-arrow-up me-1"></i> Start Upload & Analyze
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Critical Violation Case Adjudication Modal -->
<div class="modal fade" id="violationCaseModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
      <!-- Modal Header -->
      <div class="modal-header px-4 py-3 border-0 text-white" style="background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 35%, #b91c1c 70%, #dc2626 100%) !important;">
        <div class="d-flex align-items-center gap-3">
          <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: rgba(255, 255, 255, 0.2);">
            <i class="bi bi-shield-lock-fill text-warning fs-4"></i>
          </div>
          <div>
            <div class="d-flex align-items-center gap-2">
              <h5 class="modal-title text-white fw-bold mb-0" id="vcModalTitle">Critical Violation Adjudication</h5>
              <span class="badge bg-warning text-dark fw-bold px-2 py-0.5 rounded-pill" id="vcModalSeverityBadge" style="font-size: 11px;">CRITICAL</span>
              <span class="badge bg-white text-danger fw-bold px-2 py-0.5 rounded-pill" id="vcModalStatusBadge" style="font-size: 11px;">OPEN</span>
            </div>
            <small class="text-white text-opacity-80" id="vcModalSubtitle">Contact Sharing + Off-App Payment Detection Workflow</small>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body p-4" style="background: #f8fafc;">
        <div id="vcModalLoading" class="text-center py-5">
          <div class="spinner-border text-danger" role="status"></div>
          <p class="text-muted mt-2 small fw-semibold">Loading Violation Case Details & Evidence...</p>
        </div>

        <div id="vcModalContent" style="display:none;">
          <!-- Top Overview Grid -->
          <div class="row g-3 mb-4">
            <!-- Case & Recording Info -->
            <div class="col-lg-4 col-md-6">
              <div class="card h-100 border-0 shadow-sm rounded-3 p-3 bg-white">
                <span class="text-muted small text-uppercase fw-bold" style="font-size: 11px;">Incident Details</span>
                <div class="mt-2">
                  <div class="fw-bold text-dark fs-6" id="vcCaseNumberText">-</div>
                  <div class="small text-muted mt-1"><i class="bi bi-headset me-1 text-primary"></i>Recording ID: <strong id="vcRecordingIdText">-</strong></div>
                  <div class="small text-muted"><i class="bi bi-calendar3 me-1 text-primary"></i>Detected: <span id="vcCreatedAtText">-</span></div>
                  <div class="small text-muted"><i class="bi bi-person me-1 text-primary"></i>Client: <strong id="vcClientNameText">-</strong> (<span id="vcClientPhoneText">-</span>)</div>
                </div>
              </div>
            </div>

            <!-- Provider & Account Hold Status -->
            <div class="col-lg-4 col-md-6">
              <div class="card h-100 border-0 shadow-sm rounded-3 p-3 bg-white">
                <span class="text-muted small text-uppercase fw-bold" style="font-size: 11px;">Partner & Hold Status</span>
                <div class="mt-2">
                  <div class="fw-bold text-dark fs-6" id="vcProviderNameText">-</div>
                  <div class="small text-muted mt-1"><i class="bi bi-telephone me-1 text-secondary"></i>Phone: <span id="vcProviderPhoneText">-</span></div>
                  <div class="mt-2 p-2 rounded-2" style="background: #fee2e2; border: 1px solid #fecaca;">
                    <div class="d-flex align-items-center gap-1.5 text-danger fw-bold small">
                      <i class="bi bi-exclamation-octagon-fill"></i>
                      <span>Account Status: ON HOLD</span>
                    </div>
                    <div class="text-muted small mt-0.5" style="font-size: 11px;" id="vcHoldExpiryText">
                      Expires: -
                    </div>
                  </div>
                  <div class="text-muted small mt-1.5" style="font-size: 10.5px;">
                    <i class="bi bi-info-circle text-primary me-1"></i>New leads & matching paused. <strong>Wallet unaffected (no wallet hold).</strong>
                  </div>
                </div>
              </div>
            </div>

            <!-- Active Services Status -->
            <div class="col-lg-4 col-md-12">
              <div class="card h-100 border-0 shadow-sm rounded-3 p-3 bg-white">
                <span class="text-muted small text-uppercase fw-bold" style="font-size: 11px;">Active Service Commitments</span>
                <div class="mt-2">
                  <div id="vcActiveServicesIndicator" class="d-flex align-items-center gap-2">
                    <!-- Populated dynamically -->
                  </div>
                  <p class="small text-muted mt-2 mb-0" id="vcActiveServicesNote" style="font-size: 11.5px;">
                    <!-- Populated dynamically -->
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Evidence Audio & Transcript Section -->
          <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white overflow-hidden">
            <div class="card-header bg-soft-danger py-2.5 px-3 d-flex align-items-center justify-content-between border-0">
              <span class="fw-bold text-danger small">
                <i class="bi bi-chat-quote-fill me-1"></i> AI Detected Violation Evidence
              </span>
              <span class="badge bg-danger text-white" id="vcEvidenceTimestampBadge">00:00</span>
            </div>
            <div class="card-body p-3">
              <div class="p-3 rounded-3 mb-3" style="background: #fff5f5; border-left: 4px solid #ef4444;">
                <div class="text-dark fw-semibold" id="vcEvidenceSnippetText" style="font-size: 13.5px; line-height: 1.5;">
                  "Can you give me your personal mobile number? I will pay you directly outside Petsfolio."
                </div>
              </div>
              
              <!-- Evidence Audio Player -->
              <div class="p-2.5 rounded-3 bg-light d-flex flex-wrap align-items-center justify-content-between gap-2 border">
                <div class="d-flex align-items-center gap-2">
                  <audio id="vcEvidenceAudio" controls style="height: 36px; max-width: 320px;"></audio>
                  <button type="button" class="btn btn-sm btn-outline-danger" id="vcJumpAudioBtn" onclick="jumpToEvidenceTime()">
                    <i class="bi bi-skip-forward-fill me-1"></i> Jump to <span id="vcJumpTimeText">00:00</span>
                  </button>
                </div>
                <div class="text-muted small" style="font-size: 11.5px;">
                  <i class="bi bi-clock-history me-1"></i>Call Duration: <span id="vcAudioDurationText">-</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Affected Active Bookings Table (Conditional) -->
          <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white" id="vcAffectedBookingsCard" style="display:none;">
            <div class="card-header bg-soft-warning py-2.5 px-3 d-flex align-items-center justify-content-between border-0">
              <span class="fw-bold text-dark small">
                <i class="bi bi-calendar-check-fill text-warning me-1"></i> Review Affected Active Bookings
              </span>
              <span class="badge bg-warning text-dark fw-bold" id="vcAffectedCountBadge">0 Bookings</span>
            </div>
            <div class="card-body p-0 table-responsive">
              <table class="table table-sm table-hover align-middle mb-0" style="font-size: 12px;">
                <thead class="table-light">
                  <tr>
                    <th class="ps-3">Booking ID</th>
                    <th>Service</th>
                    <th>Client</th>
                    <th>Service Window</th>
                    <th>Status</th>
                    <th class="pe-3">Action</th>
                  </tr>
                </thead>
                <tbody id="vcAffectedBookingsTableBody">
                  <!-- Populated dynamically -->
                </tbody>
              </table>
            </div>
          </div>

          <!-- Adjudication Decision Card -->
          <div class="card border-0 shadow-sm rounded-3 bg-white overflow-hidden">
            <div class="card-header bg-dark text-white py-2.5 px-3 d-flex align-items-center justify-content-between border-0">
              <span class="fw-bold small"><i class="bi bi-hammer me-1 text-warning"></i> Admin Adjudication Decision</span>
              <span class="text-white text-opacity-75 small" style="font-size: 11px;">Resolution Action Required</span>
            </div>
            <div class="card-body p-4">
              <div class="row g-4">
                <!-- Option A: Confirm Violation -->
                <div class="col-lg-6">
                  <div class="p-3 rounded-3 border h-100" style="border-color: #fecaca !important; background: #fffbfb;">
                    <div class="d-flex align-items-center gap-2 mb-2">
                      <span class="badge bg-danger rounded-pill px-2.5 py-1 fw-bold">YES</span>
                      <h6 class="fw-bold text-danger mb-0">Violation Confirmed</h6>
                    </div>
                    <p class="text-muted small mb-3" style="font-size: 11.5px;">
                      Applies first-time policy strike to provider record. Provider remains on temporary hold until expiry to enforce compliance.
                    </p>
                    <form id="vcConfirmForm" onsubmit="submitConfirmViolation(event)">
                      <input type="hidden" id="vcConfirmCaseId" name="case_id">
                      <div class="mb-2">
                        <label class="form-label small fw-bold mb-1">Hold Duration (Hours)</label>
                        <select name="hold_hours" id="vcConfirmHoldHours" class="nl-select w-100">
                          <option value="24" selected>24 Hours (Standard Investigation Hold)</option>
                          <option value="48">48 Hours (Extended Hold)</option>
                          <option value="72">72 Hours (3 Days Hold)</option>
                          <option value="168">7 Days (1 Week Probation)</option>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label class="form-label small fw-bold mb-1">Admin Confirmation Notes</label>
                        <textarea name="admin_remarks" id="vcConfirmRemarks" class="form-control nl-search-input" rows="2" placeholder="Document verified policy violation details for compliance audit..." required></textarea>
                      </div>
                      <button type="submit" class="btn btn-danger w-100 fw-bold btn-sm py-2" id="vcConfirmSubmitBtn">
                        <i class="bi bi-shield-x me-1"></i> Apply First-Time Penalty & Maintain Hold
                      </button>
                    </form>
                  </div>
                </div>

                <!-- Option B: Dismiss Violation (False Positive) -->
                <div class="col-lg-6">
                  <div class="p-3 rounded-3 border h-100" style="border-color: #bbf7d0 !important; background: #fbfdfb;">
                    <div class="d-flex align-items-center gap-2 mb-2">
                      <span class="badge bg-success rounded-pill px-2.5 py-1 fw-bold">NO</span>
                      <h6 class="fw-bold text-success mb-0">False Alarm / No Violation</h6>
                    </div>
                    <p class="text-muted small mb-3" style="font-size: 11.5px;">
                      Immediately releases the account hold, clears the incorrect flag from AI records, and restores eligible provider matching status.
                    </p>
                    <form id="vcDismissForm" onsubmit="submitDismissViolation(event)">
                      <input type="hidden" id="vcDismissCaseId" name="case_id">
                      <div class="mb-3">
                        <label class="form-label small fw-bold mb-1">Clearance Reason / Findings</label>
                        <textarea name="admin_remarks" id="vcDismissRemarks" class="form-control nl-search-input" rows="4" placeholder="Explain why this conversation was determined to be compliant (e.g., standard support inquiry, customer misunderstanding, false trigger)..." required></textarea>
                      </div>
                      <button type="submit" class="btn btn-success w-100 fw-bold btn-sm py-2" id="vcDismissSubmitBtn">
                        <i class="bi bi-shield-check me-1"></i> Release Hold & Clear Incorrect Flag
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Modal Footer -->
      <div class="modal-footer bg-light px-4 py-2.5 border-top">
        <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Details Offcanvas Drawer matching Modern Dashboard -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="clientCallDetailsOffcanvas" style="width: 720px;">
  <div class="offcanvas-header px-4 py-3 text-white" style="background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 25%, #3730a3 60%, #4f46e5 100%) !important;">
    <div class="d-flex align-items-center gap-2.5">
      <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(255, 255, 255, 0.15);">
        <i class="bi bi-robot text-warning fs-5"></i>
      </div>
      <div>
        <h5 class="offcanvas-title text-white fw-bold mb-0" id="drawerTitle" style="font-size: 16px;">Client & SP Conversation Analysis</h5>
        <span class="badge bg-white text-primary mt-1" id="drawerServiceBadge" style="font-size: 11px;">Walking Service</span>
      </div>
    </div>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>

  <div class="offcanvas-body p-4">
    <div id="drawerLoading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status"></div>
      <p class="text-muted mt-2 small fw-semibold">Loading Conversation Intelligence...</p>
    </div>

    <div id="drawerContent" style="display:none;">
      <!-- Audio Player Box -->
      <div class="p-3 border rounded-3 bg-light mb-4" id="audioPlayerContainer">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <label class="fw-bold mb-0 small text-uppercase text-muted d-flex align-items-center gap-1">
            <i class="bi bi-play-circle-fill text-primary"></i> Call Recording Playback
          </label>
          <span id="drawerDurationBadge" class="badge bg-soft-primary text-primary">Duration: 00:00</span>
        </div>
        <audio id="drawerAudio" controls class="w-100"></audio>
        <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top small" id="drawerCallTimingBox">
          <span class="text-muted"><i class="bi bi-telephone-inbound text-success me-1"></i><strong>Call Start:</strong> <span id="drawerStartTime" class="text-dark fw-bold">-</span></span>
          <span class="text-muted"><i class="bi bi-telephone-x text-danger me-1"></i><strong>Call End:</strong> <span id="drawerEndTime" class="text-dark fw-bold">-</span></span>
        </div>
      </div>

      <!-- Core 3-Question AI Verdict Banner -->
      <div class="card border-0 shadow-sm rounded-3 mb-4" style="background: #f0fdf4; border-left: 5px solid #22c55e !important;">
        <div class="card-body p-3">
          <h6 class="fw-bold text-success mb-2"><i class="bi bi-check2-circle me-2"></i> Three-Question AI Summary Verdict</h6>
          
          <div class="mb-2">
            <span class="badge bg-success text-white small me-1">1. What was discussed?</span>
            <p class="small text-dark mb-1 mt-1" id="verdictSummary">-</p>
          </div>
          
          <div class="mb-2">
            <span class="badge bg-info text-white small me-1">2. Was it related to booked service?</span>
            <p class="small text-dark mb-1 mt-1" id="verdictRelevance">-</p>
          </div>

          <div>
            <span class="badge bg-warning text-dark small me-1">3. Admin Attention Required?</span>
            <p class="small text-dark mb-0 mt-1" id="verdictAdminAttention">-</p>
          </div>
        </div>
      </div>

      <!-- Service Relevance & Unrelated Split Progress -->
      <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-pie-chart-fill me-2 text-primary"></i> Conversation Composition</h6>
      <div class="p-3 border rounded-3 bg-light mb-4">
        <div class="d-flex justify-content-between small fw-bold mb-1">
          <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i> Service Related: <span id="pctServiceRelated">0%</span></span>
          <span class="text-warning"><i class="bi bi-exclamation-circle-fill me-1"></i> Unrelated: <span id="pctUnrelated">0%</span></span>
        </div>
        <div class="progress" style="height: 10px; border-radius: 50px;">
          <div id="progServiceRelated" class="progress-bar bg-success" role="progressbar" style="width: 85%"></div>
          <div id="progUnrelated" class="progress-bar bg-warning" role="progressbar" style="width: 15%"></div>
        </div>
      </div>

      <!-- Critical Violation Workflow & Hold Status Box -->
      <div id="drawerViolationBox" class="mb-4" style="display:none;">
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden" style="background: #fff5f5; border: 1px solid #fecaca !important;">
          <div class="card-header bg-danger text-white py-2 px-3 d-flex align-items-center justify-content-between">
            <span class="small fw-bold"><i class="bi bi-shield-exclamation me-1"></i> Critical Violation Case & Temporary Hold</span>
            <span class="badge bg-white text-danger fw-bold" id="drawerVcStatusBadge">OPEN</span>
          </div>
          <div class="card-body p-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <div>
                <span class="small text-muted">Case:</span>
                <span class="fw-bold text-dark ms-1" id="drawerVcNumber">-</span>
              </div>
              <div id="drawerVcActiveBookingsBadge"></div>
            </div>
            <div class="p-2 rounded-2 bg-white border border-danger-subtle small text-danger fw-semibold mb-3" id="drawerVcEvidenceText">
              -
            </div>
            <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
              <span class="text-muted small" style="font-size: 11px;">
                <i class="bi bi-info-circle me-1"></i>SP Account placed on temporary hold. <strong>Wallet unaffected.</strong>
              </span>
              <button type="button" class="btn btn-sm btn-danger px-3 fw-bold" id="drawerOpenVcModalBtn" onclick="openViolationCaseFromDrawer()">
                <i class="bi bi-hammer me-1"></i> Review & Adjudicate
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Manual Trigger Workflow Button (if critical flags exist but case not yet created) -->
      <div id="drawerManualTriggerBox" class="mb-4" style="display:none;">
        <div class="p-3 rounded-3 bg-light border d-flex align-items-center justify-content-between">
          <div>
            <div class="fw-bold text-danger small"><i class="bi bi-exclamation-triangle-fill me-1"></i> Contact Sharing & Direct Payment Flags Detected</div>
            <div class="text-muted small" style="font-size: 11px;">You can trigger the formal violation workflow to place temporary hold and check active services.</div>
          </div>
          <button type="button" class="btn btn-sm btn-outline-danger fw-bold" onclick="triggerWorkflowForCurrentRecording()">
            <i class="bi bi-play-circle me-1"></i> Start Workflow
          </button>
        </div>
      </div>

      <!-- AI Alerts Section -->
      <div class="mb-4" id="alertsContainer">
        <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-bell-fill me-2 text-danger"></i> AI Alerts & Policy Flags</h6>
        <div id="alertsList" class="d-flex flex-column gap-2"></div>
      </div>

      <!-- Highlighted Statements Categorized -->
      <div class="mb-4">
        <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-highlighter me-2 text-primary"></i> Categorized Statement Highlighting</h6>
        <div id="highlightedStatementsList" class="d-flex flex-column gap-2"></div>
      </div>

      <!-- Chronological Topic Segmentation Timeline -->
      <div class="mb-4">
        <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-diagram-3-fill me-2 text-primary"></i> Conversation Flow & Topic Segments</h6>
        <div class="p-3 border rounded-3 bg-light">
          <div id="conversationSegmentsList"></div>
        </div>
      </div>

      <!-- Participant Sentiment & Professionalism -->
      <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-emoji-smile-fill me-2 text-primary"></i> Sentiment & Communication Quality</h6>
      <div class="row g-2 mb-4">
        <div class="col-6">
          <div class="p-3 border rounded-3 bg-light text-center">
            <span class="text-muted small text-uppercase fw-semibold">Client Sentiment</span>
            <h6 class="fw-bold mb-0 mt-1" id="detClientSentiment">-</h6>
          </div>
        </div>
        <div class="col-6">
          <div class="p-3 border rounded-3 bg-light text-center">
            <span class="text-muted small text-uppercase fw-semibold">Provider Conduct</span>
            <h6 class="fw-bold mb-0 mt-1" id="detProviderSentiment">-</h6>
          </div>
        </div>
      </div>

      <!-- Admin Review & Remarks Box -->
      <div class="card border mb-4 shadow-sm rounded-3 overflow-hidden" style="border-color: #e2e8f0 !important;">
        <div class="card-header bg-soft-primary fw-bold text-primary py-2.5 px-3 small d-flex justify-content-between align-items-center">
          <span><i class="bi bi-shield-check me-1"></i> Admin Action & Review Remarks</span>
          <span id="reviewStatusBadge" class="badge bg-secondary">Pending Review</span>
        </div>
        <div class="card-body p-3">
          <form id="adminReviewForm">
            <input type="hidden" id="reviewRecordingId" name="recording_id">
            <div class="mb-2">
              <label class="form-label small fw-bold mb-1">Review Status</label>
              <select id="reviewStatusSelect" name="admin_review_status" class="nl-select w-100">
                <option value="pending">Pending Review</option>
                <option value="reviewed">Reviewed & Verified</option>
                <option value="resolved">Follow-up Resolved</option>
              </select>
            </div>
            <div class="mb-2">
              <label class="form-label small fw-bold mb-1">Admin Remarks / Notes</label>
              <textarea id="adminRemarksText" name="admin_remarks" class="form-control nl-search-input" rows="2" placeholder="Add follow-up notes, disciplinary action or customer callback log..."></textarea>
            </div>
            <div class="text-end">
              <button type="submit" class="btn btn-sm btn-primary px-3" id="saveReviewBtn" style="background: #4f46e5; border-color: #4f46e5;">
                <i class="bi bi-save me-1"></i> Save Admin Review
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Actionable AI Recommendations -->
      <div class="mb-4">
        <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-lightbulb me-2 text-primary"></i> Actionable AI Recommendations</h6>
        <ul class="list-group list-group-flush border rounded-3" id="detRecommendations"></ul>
      </div>

      <!-- Full Interactive Transcript -->
      <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-chat-left-quote me-2 text-primary"></i> Full Transcript (Translated to English)</h6>
      <div class="transcript-container" id="detTranscript">No transcript available.</div>

    </div>
  </div>
</div>

<script>
  let serviceChart, ratingChart;

  $(document.ready ? document : window).ready(function() {
    loadAnalytics();

    $('#uploadClientRecordingForm').on('submit', function(e) {
      e.preventDefault();
      let formData = new FormData(this);
      let btn = $('#uploadSubmitBtn');
      btn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i> Uploading & Analyzing...');

      $.ajax({
        url: '<?= base_url('client-call-analysis/upload') ?>',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(resp) {
          btn.prop('disabled', false).html('<i class="bi bi-cloud-arrow-up me-1"></i> Start Upload & Analyze');
          if (resp.status) {
            $('#uploadAlert').removeClass().addClass('alert alert-success').text(resp.message).show();
            setTimeout(function() { location.reload(); }, 1500);
          } else {
            $('#uploadAlert').removeClass().addClass('alert alert-danger').text(resp.message).show();
          }
        },
        error: function() {
          btn.prop('disabled', false).html('<i class="bi bi-cloud-arrow-up me-1"></i> Start Upload & Analyze');
          $('#uploadAlert').removeClass().addClass('alert alert-danger').text('Server error during upload.').show();
        }
      });
    });

    $('#adminReviewForm').on('submit', function(e) {
      e.preventDefault();
      let btn = $('#saveReviewBtn');
      btn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i> Saving...');

      $.ajax({
        url: '<?= base_url('client-call-analysis/save-admin-review') ?>',
        type: 'POST',
        data: $(this).serialize(),
        success: function(resp) {
          btn.prop('disabled', false).html('<i class="bi bi-save me-1"></i> Save Admin Review');
          if (resp.status) {
            alert('Admin review successfully saved.');
            location.reload();
          } else {
            alert('Error: ' + resp.message);
          }
        },
        error: function() {
          btn.prop('disabled', false).html('<i class="bi bi-save me-1"></i> Save Admin Review');
          alert('Server error saving review.');
        }
      });
    });

    // Fast client-side row filtering when typing in search input
    $('#searchInput').on('keyup', function(e) {
      if (e.key === 'Enter') {
        $('#clientCallFilterForm').submit();
        return;
      }
      let value = $(this).val().toLowerCase();
      $('#clientCallsTable tbody tr').filter(function() {
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

  function loadAnalytics() {
    $.getJSON('<?= base_url('client-call-analysis/analytics') ?>', function(resp) {
      if (!resp.status) return;

      // 1. Service Breakdown Bar Chart
      let svcNames = resp.serviceBreakdown.map(s => s.service_name ? s.service_name.charAt(0).toUpperCase() + s.service_name.slice(1) : 'Walking');
      let svcCalls = resp.serviceBreakdown.map(s => parseInt(s.total_calls));
      let svcRel = resp.serviceBreakdown.map(s => parseFloat(s.avg_relevance));

      const ctxSvc = document.getElementById('serviceBreakdownChart').getContext('2d');
      serviceChart = new Chart(ctxSvc, {
        type: 'bar',
        data: {
          labels: svcNames.length ? svcNames : ['Walking', 'Grooming', 'Training', 'Sitting'],
          datasets: [
            {
              label: 'Total Calls',
              data: svcCalls.length ? svcCalls : [15, 8, 6, 4],
              backgroundColor: '#4f46e5',
              borderRadius: 6
            },
            {
              label: 'Avg Relevance Score (/10)',
              data: svcRel.length ? svcRel : [9.2, 8.8, 9.5, 9.0],
              backgroundColor: '#10b981',
              borderRadius: 6
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: { y: { beginAtZero: true } }
        }
      });

      // 2. Rating Distribution Doughnut Chart
      let ratLabels = resp.ratingDistribution.map(r => r.category ? r.category.charAt(0).toUpperCase() + r.category.slice(1).replace('_', ' ') : 'Good');
      let ratCounts = resp.ratingDistribution.map(r => parseInt(r.count));

      const ctxRat = document.getElementById('ratingDistributionChart').getContext('2d');
      ratingChart = new Chart(ctxRat, {
        type: 'doughnut',
        data: {
          labels: ratLabels.length ? ratLabels : ['Good', 'Review Recommended', 'Critical'],
          datasets: [{
            data: ratCounts.length ? ratCounts : [22, 6, 2],
            backgroundColor: ['#10b981', '#f59e0b', '#ef4444']
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { position: 'bottom' } }
        }
      });
    });
  }

  function openClientCallDetails(id) {
    let offcanvasEl = document.getElementById('clientCallDetailsOffcanvas');
    let offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl) || new bootstrap.Offcanvas(offcanvasEl);
    $('#drawerTitle').text('Client & SP Call #' + id);
    $('#drawerLoading').show();
    $('#drawerContent').hide();
    offcanvas.show();

    $.getJSON('<?= base_url('client-call-analysis/details/') ?>' + id, function(resp) {
      $('#drawerLoading').hide();
      if (!resp.status) return;

      let d = resp.data;
      $('#reviewRecordingId').val(d.recording_id || d.id);
      
      let svc = d.analyzed_service || d.service_type || 'walking';
      $('#drawerServiceBadge').text(svc.toUpperCase() + ' SERVICE CALL');

      // Audio
      if (d.recording_url) {
        let audioSrc = d.recording_url;
        if (!audioSrc.startsWith('http://') && !audioSrc.startsWith('https://')) {
          audioSrc = '<?= base_url() ?>' + audioSrc.replace(/^\//, '');
        }
        $('#drawerAudio').attr('src', audioSrc);
        $('#drawerDurationBadge').text('Duration: ' + (d.duration || 0) + 's');
        $('#drawerStartTime').text(d.start_datetime_formatted || d.start_time_formatted || '-');
        $('#drawerEndTime').text(d.end_datetime_formatted || d.end_time_formatted || '-');
        $('#audioPlayerContainer').show();
      } else {
        $('#audioPlayerContainer').hide();
      }

      // Three-Question Summary Split
      let q1Text = d.summary_q1 || '';
      let q2Text = d.summary_q2 || '';
      let q3Text = d.summary_q3 || '';

      if (!q1Text && d.summary) {
        let parts = d.summary.split(/(?=The conversation was|The conversation began|The interaction was|Admin Attention Required:)/i);
        q1Text = parts[0] ? parts[0].trim() : d.summary;
        if (parts.length > 1) {
          for (let pi = 1; pi < parts.length; pi++) {
            if (parts[pi].toLowerCase().includes('admin attention required:')) {
              q3Text = parts[pi].trim();
            } else if (!q2Text) {
              q2Text = parts[pi].trim();
            }
          }
        }
      }

      $('#verdictSummary').text(q1Text || d.summary || 'Summary generated.');
      
      let relPct = parseFloat(d.service_related_pct || 0.0);
      let unrelPct = parseFloat(d.unrelated_pct || (100.0 - relPct));
      $('#verdictRelevance').text(q2Text || (relPct + '% conversation was related to booked ' + svc + ' service (' + unrelPct + '% unrelated topics).'));
      
      let alertsText = [];
      if (parseInt(d.off_platform_payment) === 1) alertsText.push('🚨 Platform bypass / off-platform arrangement detected');
      if (parseInt(d.contact_sharing) === 1) alertsText.push('🚨 Personal contact sharing attempt detected');
      if (parseInt(d.refund_requested) === 1) alertsText.push('🚨 Client requested a refund');
      if (parseInt(d.has_complaint) === 1) alertsText.push('🟠 Service complaint voiced');
      if (parseInt(d.has_abuse) === 1) alertsText.push('🚨 Inappropriate / abusive language detected');

      let fallbackAdmin = alertsText.length ? ('Admin Attention Required: ' + alertsText.join('; ') + '.') : 'No critical compliance concerns or disputes flagged.';
      $('#verdictAdminAttention').text(q3Text || fallbackAdmin);

      // Progress bars
      $('#pctServiceRelated').text(relPct.toFixed(1) + '%');
      $('#pctUnrelated').text(unrelPct.toFixed(1) + '%');
      $('#progServiceRelated').css('width', relPct + '%');
      $('#progUnrelated').css('width', unrelPct + '%');

      // Alerts List
      let alertsHtml = '';
      if (d.alerts && d.alerts.length) {
        d.alerts.forEach(a => {
          let alertClass = a.type === 'Critical Alert' ? 'alert-danger' : (a.type === 'Warning' ? 'alert-warning' : 'alert-success');
          alertsHtml += `
            <div class="alert ${alertClass} py-2 px-3 mb-0 small rounded-2 d-flex align-items-center gap-2">
              <span class="fs-5">${a.icon || '⚠️'}</span>
              <div>
                <strong>${a.category || a.type}:</strong> ${a.message}
              </div>
            </div>
          `;
        });
      } else {
        alertsHtml = '<div class="text-muted small">No policy alerts logged.</div>';
      }
      $('#alertsList').html(alertsHtml);

      // Highlighted Statements
      let hlHtml = '';
      if (d.highlighted_conversations && d.highlighted_conversations.length) {
        d.highlighted_conversations.forEach(h => {
          let cat = h.category || '';
          let badgeClass = 'highlight-badge-info';
          if (cat.includes('Policy') || cat.includes('Serious')) badgeClass = 'highlight-badge-policy';
          else if (cat.includes('Unrelated')) badgeClass = 'highlight-badge-unrelated';
          else if (cat.includes('Complaint')) badgeClass = 'highlight-badge-complaint';
          else if (cat.includes('Service')) badgeClass = 'highlight-badge-service';

          hlHtml += `
            <div class="p-2.5 rounded-2 ${badgeClass} small">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <strong>${h.category_icon || '🏷️'} ${h.category}</strong>
                <span class="badge bg-white text-dark">${h.timestamp || '00:00'}</span>
              </div>
              <div class="fst-italic text-dark">"${h.sentence}"</div>
              ${h.note ? `<div class="text-muted mt-1" style="font-size:0.75rem;">ℹ️ ${h.note}</div>` : ''}
            </div>
          `;
        });
      } else {
        hlHtml = '<div class="text-muted small">No specific highlighted statements flagged.</div>';
      }
      $('#highlightedStatementsList').html(hlHtml);

      // Conversation Timeline Segments
      let segHtml = '';
      if (d.conversation_segments && d.conversation_segments.length) {
        d.conversation_segments.forEach(seg => {
          let badgeColor = seg.color === 'danger' ? 'bg-danger' : (seg.color === 'warning' ? 'bg-warning text-dark' : (seg.color === 'success' ? 'bg-success' : 'bg-secondary'));
          segHtml += `
            <div class="timeline-segment-item small">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="badge ${badgeColor}">${seg.type || 'Service Segment'}</span>
                <span class="text-muted small fw-bold">${seg.timestamp || ''}</span>
              </div>
              <div class="text-dark">${seg.text}</div>
            </div>
          `;
        });
      } else {
        segHtml = '<div class="text-muted small">Timeline segmentation pending.</div>';
      }
      $('#conversationSegmentsList').html(segHtml);

      // Sentiments with color coding
      let custSent = d.customer_sentiment || 'Positive';
      let provSent = d.provider_sentiment || 'Positive';
      let custClass = (custSent.toLowerCase() === 'negative') ? 'text-danger' : ((custSent.toLowerCase() === 'neutral') ? 'text-warning' : 'text-success');
      let provClass = (provSent.toLowerCase().includes('non-compliant') || provSent.toLowerCase() === 'negative') ? 'text-danger' : ((provSent.toLowerCase() === 'neutral') ? 'text-warning' : 'text-success');

      $('#detClientSentiment').html(`<span class="${custClass}">${custSent}</span>`);
      $('#detProviderSentiment').html(`<span class="${provClass}">${provSent}</span>`);

      // Admin Review
      let revStatus = d.admin_review_status || 'pending';
      $('#reviewStatusSelect').val(revStatus);
      $('#adminRemarksText').val(d.admin_remarks || '');
      $('#reviewStatusBadge').text(revStatus.toUpperCase()).removeClass().addClass('badge ' + (revStatus === 'resolved' ? 'bg-success' : (revStatus === 'reviewed' ? 'bg-primary' : 'bg-secondary')));

      // Recommendations
      let recHtml = '';
      if (d.recommendations && d.recommendations.length) {
        d.recommendations.forEach(r => {
          recHtml += `<li class="list-group-item bg-transparent text-dark small"><i class="bi bi-arrow-right text-primary me-2"></i>${r}</li>`;
        });
      } else {
        recHtml = '<li class="list-group-item bg-transparent text-muted small">No action items required.</li>';
      }
      $('#detRecommendations').html(recHtml);

      // Transcript
      $('#detTranscript').text(d.transcript || 'Transcript generation in progress.');

      // Critical Violation Case integration in Drawer
      window.currentLoadedCall = d;
      if (d.violation_case_id) {
        $('#drawerViolationBox').show();
        $('#drawerManualTriggerBox').hide();
        $('#drawerVcNumber').text(d.violation_case_number || ('Case #' + d.violation_case_id));
        let vcStat = (d.violation_case_status || 'open').toUpperCase();
        let statBadgeClass = vcStat === 'OPEN' ? 'bg-danger text-white' : (vcStat === 'CONFIRMED' ? 'bg-dark text-white' : 'bg-success text-white');
        $('#drawerVcStatusBadge').text(vcStat).removeClass().addClass('badge fw-bold ' + statBadgeClass);
        $('#drawerVcEvidenceText').text(d.violation_evidence_snippet || 'Critical contact sharing & off-platform payment detected.');
        
        let actCount = parseInt(d.violation_affected_bookings_count || 0);
        if (actCount > 0) {
          $('#drawerVcActiveBookingsBadge').html(`<span class="badge bg-warning text-dark"><i class="bi bi-calendar2-check me-1"></i>${actCount} Active Bookings</span>`);
        } else {
          $('#drawerVcActiveBookingsBadge').html(`<span class="badge bg-secondary text-white">0 Active Bookings</span>`);
        }
      } else if (parseInt(d.off_platform_payment) === 1 && parseInt(d.contact_sharing) === 1) {
        $('#drawerViolationBox').hide();
        $('#drawerManualTriggerBox').show();
      } else {
        $('#drawerViolationBox').hide();
        $('#drawerManualTriggerBox').hide();
      }

      $('#drawerContent').show();
    });
  }

  function openViolationCaseFromDrawer() {
    if (window.currentLoadedCall && window.currentLoadedCall.violation_case_id) {
      openViolationCaseModal(window.currentLoadedCall.violation_case_id);
    }
  }

  function triggerWorkflowForCurrentRecording() {
    if (!window.currentLoadedCall || !window.currentLoadedCall.id) return;
    if (!confirm('Place temporary account hold on provider and initiate critical violation case workflow?')) return;

    let recId = window.currentLoadedCall.id;
    $.ajax({
      url: '<?= base_url('client-call-analysis/trigger-violation') ?>',
      type: 'POST',
      data: { recording_id: recId },
      dataType: 'json',
      success: function(res) {
        if (res.status) {
          alert('Workflow initiated: Case ' + res.case_number + ' created. Temporary account hold placed.');
          openClientCallDetails(recId);
          if (res.case_id) {
            openViolationCaseModal(res.case_id);
          }
        } else {
          alert('Failed to trigger workflow: ' + (res.message || 'Unknown error'));
        }
      },
      error: function(xhr) {
        alert('Server error triggering workflow: ' + (xhr.responseJSON?.message || xhr.statusText));
      }
    });
  }

  let currentLoadedCase = null;

  function openViolationCaseModal(caseId) {
    $('#vcModalLoading').show();
    $('#vcModalContent').hide();
    
    let modalEl = document.getElementById('violationCaseModal');
    let modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
    modalInstance.show();

    $.getJSON('<?= base_url('client-call-analysis/case-details') ?>/' + caseId, function(resp) {
      $('#vcModalLoading').hide();
      if (!resp.status || !resp.data) {
        alert('Failed to load violation case details.');
        modalInstance.hide();
        return;
      }

      let c = resp.data;
      currentLoadedCase = c;

      $('#vcModalTitle').text('Critical Case: ' + c.case_number);
      let stat = (c.status || 'open').toUpperCase();
      let statClass = stat === 'OPEN' ? 'bg-danger text-white' : (stat === 'CONFIRMED' ? 'bg-dark text-white' : 'bg-success text-white');
      $('#vcModalStatusBadge').text(stat).removeClass().addClass('badge fw-bold px-2.5 py-1 rounded-pill ' + statClass);

      $('#vcCaseNumberText').text(c.case_number);
      $('#vcRecordingIdText').text('#' + c.recording_id + (c.call_id ? ' (Call #' + c.call_id + ')' : ''));
      $('#vcCreatedAtText').text(c.created_at || '-');
      $('#vcClientNameText').text(c.client_name || 'Client #' + (c.customer_id || '-'));
      $('#vcClientPhoneText').text(c.client_phone || '-');
      $('#vcProviderNameText').text(c.provider_name || 'Provider #' + (c.provider_id || '-'));
      $('#vcProviderPhoneText').text(c.provider_phone || '-');

      // Hold status
      let holdExp = c.hold_expires_at || c.provider_hold_expires_at;
      if (holdExp) {
        $('#vcHoldExpiryText').html('Expires: <strong>' + holdExp + '</strong>');
      } else {
        $('#vcHoldExpiryText').text('Hold duration completed or not active.');
      }

      // Evidence & Audio
      $('#vcEvidenceSnippetText').text('"' + (c.evidence_snippet || 'Contact sharing and off-platform arrangement detected.') + '"');
      let ts = c.evidence_timestamp || '00:00';
      $('#vcEvidenceTimestampBadge').text(ts);
      $('#vcJumpTimeText').text(ts);

      if (c.recording_url) {
        $('#vcEvidenceAudio').attr('src', c.recording_url);
      }
      let durSec = parseInt(c.recording_duration || 0);
      let durFmt = durSec > 0 ? (Math.floor(durSec / 60) + 'm ' + (durSec % 60) + 's') : '-';
      $('#vcAudioDurationText').text(durFmt);

      // Active Services Check
      let hasActive = parseInt(c.has_active_services || 0) === 1 || (c.affected_bookings && c.affected_bookings.length > 0);
      let actCount = c.affected_bookings ? c.affected_bookings.length : parseInt(c.affected_bookings_count || 0);

      if (hasActive && actCount > 0) {
        $('#vcActiveServicesIndicator').html(`
          <span class="badge bg-warning text-dark px-2.5 py-1.5 fw-bold fs-6">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> YES (${actCount} Bookings)
          </span>
        `);
        $('#vcActiveServicesNote').html(`
          <strong class="text-danger">Provider Alerted:</strong> Scheduled commitments must still be serviced on time. Do not abandon active bookings.
        `);
        $('#vcAffectedCountBadge').text(actCount + ' Active Bookings');
        $('#vcAffectedBookingsCard').show();

        // Populate table
        let bHtml = '';
        if (c.affected_bookings && c.affected_bookings.length) {
          c.affected_bookings.forEach(b => {
            bHtml += `
              <tr>
                <td class="ps-3 fw-bold text-primary">#${b.booking_id}</td>
                <td><span class="badge bg-soft-primary text-primary text-uppercase">${b.service_name || 'Service'}</span></td>
                <td>${b.client_name || 'Client'} <small class="text-muted">(${b.client_phone || '-'})</small></td>
                <td>${b.service_date || '-'} to ${b.service_end_date || '-'}</td>
                <td><span class="badge bg-success">${b.booking_status || 'Confirmed'}</span></td>
                <td class="pe-3">
                  <a href="<?= base_url('bookings/view/') ?>/${b.booking_id}" target="_blank" class="btn btn-xs btn-outline-secondary" style="font-size:10.5px;">
                    <i class="bi bi-box-arrow-up-right me-0.5"></i> View
                  </a>
                </td>
              </tr>
            `;
          });
        }
        $('#vcAffectedBookingsTableBody').html(bHtml);
      } else {
        $('#vcActiveServicesIndicator').html(`
          <span class="badge bg-success text-white px-2.5 py-1.5 fw-bold fs-6">
            <i class="bi bi-check-circle-fill me-1"></i> NO Active Bookings
          </span>
        `);
        $('#vcActiveServicesNote').html(`
          Provider has no ongoing bookings. Provider was alerted that account access is temporarily paused for review.
        `);
        $('#vcAffectedBookingsCard').hide();
      }

      // Adjudication forms setup
      $('#vcConfirmCaseId').val(c.id);
      $('#vcDismissCaseId').val(c.id);
      $('#vcConfirmRemarks').val(c.admin_remarks || '');
      $('#vcDismissRemarks').val(c.admin_remarks || '');

      // If already decided, adjust button text
      if (c.status === 'confirmed') {
        $('#vcConfirmSubmitBtn').html('<i class="bi bi-check2-all me-1"></i> Update Strike / Hold Remarks');
        $('#vcDismissSubmitBtn').html('<i class="bi bi-arrow-counterclockwise me-1"></i> Revert & Release Hold');
      } else if (c.status === 'dismissed') {
        $('#vcDismissSubmitBtn').html('<i class="bi bi-check2-all me-1"></i> Already Cleared');
      }

      $('#vcModalContent').show();
    });
  }

  function jumpToEvidenceTime() {
    let audio = document.getElementById('vcEvidenceAudio');
    if (!audio) return;
    let ts = $('#vcEvidenceTimestampBadge').text().trim();
    let parts = ts.split(':');
    let secs = 0;
    if (parts.length === 2) {
      secs = parseInt(parts[0]) * 60 + parseInt(parts[1]);
    } else if (parts.length === 3) {
      secs = parseInt(parts[0]) * 3600 + parseInt(parts[1]) * 60 + parseInt(parts[2]);
    }
    audio.currentTime = secs;
    audio.play();
  }

  function submitConfirmViolation(e) {
    e.preventDefault();
    if (!confirm('Confirm policy violation? This will record a strike on the provider and maintain the temporary account hold.')) {
      return;
    }

    let btn = $('#vcConfirmSubmitBtn');
    let origText = btn.html();
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Applying Penalty...');

    $.ajax({
      url: '<?= base_url('client-call-analysis/confirm-violation') ?>',
      type: 'POST',
      data: $('#vcConfirmForm').serialize(),
      dataType: 'json',
      success: function(res) {
        btn.prop('disabled', false).html(origText);
        if (res.status) {
          alert('Violation Confirmed: ' + res.message);
          bootstrap.Modal.getInstance(document.getElementById('violationCaseModal')).hide();
          location.reload();
        } else {
          alert('Error: ' + (res.message || 'Failed to confirm violation.'));
        }
      },
      error: function(xhr) {
        btn.prop('disabled', false).html(origText);
        alert('Server error: ' + (xhr.responseJSON?.message || xhr.statusText));
      }
    });
  }

  function submitDismissViolation(e) {
    e.preventDefault();
    if (!confirm('Dismiss violation as false positive / compliant? This will immediately release the provider hold and restore full account access.')) {
      return;
    }

    let btn = $('#vcDismissSubmitBtn');
    let origText = btn.html();
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Releasing Hold...');

    $.ajax({
      url: '<?= base_url('client-call-analysis/dismiss-violation') ?>',
      type: 'POST',
      data: $('#vcDismissForm').serialize(),
      dataType: 'json',
      success: function(res) {
        btn.prop('disabled', false).html(origText);
        if (res.status) {
          alert('Hold Released: ' + res.message);
          bootstrap.Modal.getInstance(document.getElementById('violationCaseModal')).hide();
          location.reload();
        } else {
          alert('Error: ' + (res.message || 'Failed to dismiss violation.'));
        }
      },
      error: function(xhr) {
        btn.prop('disabled', false).html(origText);
        alert('Server error: ' + (xhr.responseJSON?.message || xhr.statusText));
      }
    });
  }

  function confirmClearAllCalls(btn) {
    if (!confirm('Are you sure you want to permanently clear all Client & Provider call recordings and AI analysis data? This cannot be undone.')) {
      return;
    }
    const origHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Clearing...';

    $.ajax({
      url: '<?= base_url('client-call-analysis/clear-all') ?>',
      type: 'POST',
      dataType: 'json',
      success: function(res) {
        if (res.status) {
          alert(res.message);
          window.location.href = '<?= base_url('client-call-analysis') ?>';
        } else {
          alert('Error: ' + (res.message || 'Failed to clear calls.'));
          btn.disabled = false;
          btn.innerHTML = origHtml;
        }
      },
      error: function(xhr) {
        alert('Failed to clear calls: ' + (xhr.responseJSON?.message || xhr.statusText));
        btn.disabled = false;
        btn.innerHTML = origHtml;
      }
    });
  }
</script>

<?php echo $this->include('admin/footer') ?>
<?php echo $this->endSection() ?>
>>>>>>> origin/main
