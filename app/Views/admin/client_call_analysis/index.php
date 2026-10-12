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
