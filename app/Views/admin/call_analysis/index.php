<?php echo $this->extend('admin/includes/header'); ?>
<?php echo $this->section('content'); ?>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<style>
  .bg-gradient-call {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important;
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
  .score-badge {
    font-size: 1.1rem;
    font-weight: 700;
    padding: 6px 12px;
    border-radius: 8px;
  }
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
  .table-hover tbody tr {
    cursor: pointer;
    transition: background 0.15s ease;
  }
  .table-hover tbody tr:hover {
    background-color: rgba(99, 102, 241, 0.04) !important;
  }
  .nav-pills .nav-link.active {
    background-color: #4f46e5 !important;
  }
</style>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?php echo $this->include('admin/nav_bar') ?>
  </div>

  <div class="container-fluid content-inner mt-n5 py-0 px-4" id="page_layout">
    
    <!-- Hero Banner -->
    <div class="container-fluid p-0 mb-4 bg-gradient-call rounded-3 shadow">
      <div class="text-white p-4 p-md-5 rounded-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <h1 class="h2 fw-bold mb-2 text-white"><i class="fas fa-headset me-2"></i> AI Call Intelligence & Verifier Quality Dashboard</h1>
          <p class="text-white-75 mb-0">Automated Speech Recognition Speaker Diarization, LLM Quality Scoring & Reviewer Performance Tracking.</p>
        </div>
        <button class="btn btn-light text-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadRecordingModal">
          <i class="fas fa-cloud-upload-alt me-2"></i> Upload New Call Recording
        </button>
      </div>
    </div>

    <!-- Executive KPI Row -->
    <div class="row g-3 mb-4">
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card-modern p-3">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <span class="text-muted small fw-semibold uppercase">Total Recordings</span>
              <h3 class="fw-extrabold mb-0 mt-1"><?= number_format($totalRecordings) ?></h3>
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
              <span class="text-muted small fw-semibold uppercase">Avg Verification Score</span>
              <h3 class="fw-extrabold mb-0 mt-1 text-primary"><?= number_format($avgRating, 1) ?> <small class="fs-6 text-muted">/ 10</small></h3>
            </div>
            <div class="avatar bg-soft-success text-success rounded-circle p-3">
              <i class="fas fa-star fa-2x"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card-modern p-3">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <span class="text-muted small fw-semibold uppercase">Average QA Rating</span>
              <h3 class="fw-extrabold mb-0 mt-1 text-info"><?= number_format($avgQa ?? $avgRating, 1) ?> <small class="fs-6 text-muted">/ 10</small></h3>
            </div>
            <div class="avatar bg-soft-info text-info rounded-circle p-3">
              <i class="fas fa-user-check fa-2x"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card-modern p-3">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <span class="text-muted small fw-semibold uppercase">Avg Risk Level</span>
              <h3 class="fw-extrabold mb-0 mt-1 <?= $avgRisk > 3 ? 'text-danger' : 'text-success' ?>"><?= $avgRisk ?> <small class="fs-6 text-muted">/ 10</small></h3>
            </div>
            <div class="avatar bg-soft-warning text-warning rounded-circle p-3">
              <i class="fas fa-shield-alt fa-2x"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Reviewer Performance Tracking & Quality Section -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
      <div class="card-header bg-transparent border-bottom p-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
          <h5 class="fw-bold mb-1 text-dark"><i class="fas fa-user-shield me-2 text-primary"></i> Reviewer Performance & Verifier Quality Audit</h5>
          <p class="text-muted small mb-0">Track verification calls handled, average call duration, QA rating, approval & escalation metrics per verifier.</p>
        </div>
        <div class="d-flex gap-2">
          <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="refreshVerifierStats()">
            <i class="fas fa-sync-alt me-1"></i> Refresh Metrics
          </button>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" id="verifierPerformanceTable">
            <thead class="bg-light text-uppercase small text-muted">
              <tr>
                <th class="py-3 px-4">Verifier Name</th>
                <th class="py-3 text-center">Calls Completed</th>
                <th class="py-3 text-center">Avg Call Duration</th>
                <th class="py-3 text-center">Avg Verif. Score</th>
                <th class="py-3 text-center">Approval Rate</th>
                <th class="py-3 text-center">Re-verif. Rate</th>
                <th class="py-3 text-center">QA Rating</th>
                <th class="py-3 text-center">Escalation Rate</th>
                <th class="py-3 text-center">Complaint Rate</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($verifierStats)) : ?>
                <?php foreach ($verifierStats as $v) : ?>
                  <tr>
                    <td class="px-4 fw-bold">
                      <i class="fas fa-user-circle me-2 text-primary"></i> <?= esc($v['verifier_name']) ?>
                      <?php if (!empty($v['verifier_email'])) : ?>
                        <div class="text-muted small fw-normal"><?= esc($v['verifier_email']) ?></div>
                      <?php endif; ?>
                    </td>
                    <td class="text-center fw-bold fs-6">
                      <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill"><?= number_format($v['calls_completed']) ?> calls</span>
                    </td>
                    <td class="text-center fw-semibold">
                      <i class="far fa-clock me-1 text-muted"></i> <?= esc($v['formatted_duration']) ?>
                    </td>
                    <td class="text-center">
                      <span class="badge bg-soft-success text-success fw-bold px-3 py-2">
                        ⭐ <?= number_format($v['avg_verification_score'], 1) ?> / 10
                      </span>
                    </td>
                    <td class="text-center">
                      <span class="badge bg-success text-white px-3 py-2 rounded-pill fw-bold">
                        <?= number_format($v['approval_rate'], 1) ?>%
                      </span>
                    </td>
                    <td class="text-center">
                      <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold">
                        <?= number_format($v['re_verification_rate'], 1) ?>%
                      </span>
                    </td>
                    <td class="text-center">
                      <span class="badge bg-info text-white px-3 py-2 rounded-pill fw-bold">
                        <?= number_format($v['avg_qa_rating'], 1) ?> / 10
                      </span>
                    </td>
                    <td class="text-center">
                      <?php if ($v['escalation_rate'] > 15) : ?>
                        <span class="badge bg-danger text-white px-3 py-2 rounded-pill fw-bold">
                          <i class="fas fa-exclamation-circle me-1"></i> <?= number_format($v['escalation_rate'], 1) ?>%
                        </span>
                      <?php else : ?>
                        <span class="badge bg-soft-secondary text-dark px-3 py-2 rounded-pill">
                          <?= number_format($v['escalation_rate'], 1) ?>%
                        </span>
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <?php if ($v['customer_complaint_rate'] > 5) : ?>
                        <span class="badge bg-danger text-white px-3 py-2 rounded-pill fw-bold">
                          <i class="fas fa-bullhorn me-1"></i> <?= number_format($v['customer_complaint_rate'], 1) ?>%
                        </span>
                      <?php else : ?>
                        <span class="badge bg-soft-success text-success px-3 py-2 rounded-pill">
                          <?= number_format($v['customer_complaint_rate'], 1) ?>%
                        </span>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else : ?>
                <tr>
                  <td colspan="9" class="text-center py-5 text-muted">
                    <i class="fas fa-info-circle me-2"></i> No call verifier metrics logged yet. Upload or assign verifiers to call recordings to view reviewer quality scores!
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Analytics Charts Row -->
    <div class="row g-3 mb-4">
      <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm rounded-3 h-100">
          <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0"><i class="fas fa-chart-line me-2 text-primary"></i> Daily Average Rating Trend</h5>
            <small class="text-muted">Last 14 Days</small>
          </div>
          <div class="card-body px-4 pb-4">
            <canvas id="dailyRatingChart" style="max-height: 280px;"></canvas>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm rounded-3 h-100">
          <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
            <h5 class="fw-bold mb-0"><i class="fas fa-pie-chart me-2 text-primary"></i> Customer Sentiment</h5>
          </div>
          <div class="card-body px-4 pb-4 d-flex align-items-center justify-content-center">
            <canvas id="sentimentChart" style="max-height: 250px;"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Call Recordings Table Card -->
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-header bg-transparent border-bottom p-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="fw-bold mb-0"><i class="fas fa-list me-2 text-primary"></i> Call Recordings & AI Evaluation</h5>
        <div class="d-flex gap-2">
          <input type="text" id="searchInput" class="form-control form-control-sm rounded-2" placeholder="Search provider, verifier or ID..." style="width: 240px;">
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" id="callRecordingsTable">
            <thead class="bg-light">
              <tr>
                <th class="py-3 px-4">Call ID / Date</th>
                <th class="py-3">Type</th>
                <th class="py-3">Verifier</th>
                <th class="py-3">Duration</th>
                <th class="text-center py-3">Score</th>
                <th class="text-center py-3">Status</th>
                <th class="text-center py-3">QA Score</th>
                <th class="text-center py-3">Escalated</th>
                <th class="text-center py-3">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($calls)) : ?>
                <?php foreach ($calls as $call) : ?>
                  <tr onclick="openCallDetails(<?= (int)$call['id'] ?>)">
                    <td class="px-4 fw-semibold">
                      #<?= $call['id'] ?>
                      <div class="text-muted small fw-normal"><?= date('d M Y, H:i', strtotime($call['created_at'])) ?></div>
                    </td>
                    <td>
                      <span class="badge bg-soft-info text-info text-capitalize"><?= esc($call['call_type'] ?? 'Verification') ?></span>
                    </td>
                    <td>
                      <span class="fw-bold text-dark"><i class="fas fa-user-tie me-1 text-primary"></i> <?= esc($call['verifier_name'] ?: 'Unassigned') ?></span>
                    </td>
                    <td><?= esc($call['duration']) ?>s</td>
                    <td class="text-center">
                      <?php if (isset($call['overall_rating']) && $call['overall_rating'] > 0) : ?>
                        <span class="badge bg-soft-primary text-primary fw-bold fs-6">⭐ <?= number_format($call['overall_rating'], 1) ?></span>
                      <?php else : ?>
                        <span class="text-muted small">N/A</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <?php 
                        $verSt = strtolower($call['verification_status'] ?? 'pending');
                        $badgeBg = $verSt === 'approved' ? 'bg-success' : ($verSt === 're_verification' ? 'bg-warning text-dark' : ($verSt === 'escalated' ? 'bg-danger' : 'bg-secondary'));
                      ?>
                      <span class="badge <?= $badgeBg ?> text-white rounded-pill px-3"><?= ucfirst(str_replace('_', ' ', $verSt)) ?></span>
                    </td>
                    <td class="text-center">
                      <span class="badge bg-soft-info text-info fw-bold fs-6">
                        <?= number_format($call['qa_score'] ?? $call['overall_rating'] ?? 0, 1) ?> / 10
                      </span>
                    </td>
                    <td class="text-center">
                      <?php if (!empty($call['is_escalated'])) : ?>
                        <span class="badge bg-danger shadow-sm"><i class="fas fa-exclamation-triangle"></i> Escalated</span>
                      <?php else : ?>
                        <span class="badge bg-soft-success text-success"><i class="fas fa-check-circle"></i> Normal</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <button class="btn btn-sm btn-icon btn-primary rounded-circle" title="View AI Details">
                        <i class="fas fa-eye"></i>
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else : ?>
                <tr>
                  <td colspan="9" class="text-center py-5 text-muted">No call recordings found in database. Click "Upload New Call Recording" to get started!</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</main>

<!-- Upload Recording Modal -->
<div class="modal fade" id="uploadRecordingModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-gradient-call text-white p-4">
        <h5 class="modal-title text-white fw-bold"><i class="fas fa-cloud-upload-alt me-2"></i> Upload Audio File for AI Analysis</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="uploadRecordingForm" enctype="multipart/form-data">
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label fw-bold">Select Audio File (.mp3, .wav, .m4a, .ogg)</label>
            <input type="file" name="recording" class="form-control" accept="audio/*" required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Assigned Verifier / Reviewer</label>
            <select name="verifier_id" class="form-select form-select-sm">
              <option value="">-- Unassigned / Current User --</option>
              <?php if (!empty($verifiers)) : ?>
                <?php foreach ($verifiers as $v) : ?>
                  <option value="<?= $v['id'] ?>"><?= esc($v['name']) ?> (<?= esc($v['email']) ?>)</option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>
          <div class="row g-3">
            <div class="col-6">
              <label class="form-label small fw-semibold">Provider ID (Optional)</label>
              <input type="number" name="provider_id" class="form-control form-control-sm" placeholder="e.g. 101">
            </div>
            <div class="col-6">
              <label class="form-label small fw-semibold">Booking ID (Optional)</label>
              <input type="number" name="booking_id" class="form-control form-control-sm" placeholder="e.g. 450">
            </div>
          </div>
          <div class="mt-3">
            <label class="form-label small fw-semibold">Call Type</label>
            <select name="call_type" class="form-select form-select-sm">
              <option value="verification">Provider Verification Call</option>
              <option value="inbound">Inbound Customer Call</option>
              <option value="outbound">Outbound Provider Follow-up</option>
              <option value="support">Support Dispute Call</option>
            </select>
          </div>
          <div id="uploadAlert" class="mt-3" style="display:none;"></div>
        </div>
        <div class="modal-footer bg-light p-3">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary btn-sm px-4" id="uploadSubmitBtn">
            <i class="fas fa-paper-plane me-1"></i> Start Upload & Queue AI
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Details & QA Audit Offcanvas Drawer -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="callDetailsOffcanvas" style="width: 680px;">
  <div class="offcanvas-header bg-gradient-call text-white p-4">
    <h5 class="offcanvas-title text-white fw-bold" id="drawerTitle"><i class="fas fa-robot me-2"></i> Call Evaluation & Verifier Quality Audit</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body p-4">
    <div id="drawerLoading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status"></div>
      <p class="text-muted mt-2">Loading AI Analysis & Reviewer Payload...</p>
    </div>

    <div id="drawerContent" style="display:none;">
      <!-- Audio Player -->
      <div class="p-3 border rounded-3 bg-light mb-4" id="audioPlayerContainer">
        <label class="fw-bold mb-2 small text-uppercase text-muted"><i class="fas fa-play-circle me-1 text-primary"></i> Audio Playback</label>
        <audio id="drawerAudio" controls class="w-100"></audio>
      </div>

      <!-- Supervisor Quality Audit Form Box -->
      <div class="card border-primary mb-4 shadow-sm">
        <div class="card-header bg-soft-primary fw-bold text-primary py-2 px-3 small d-flex justify-content-between align-items-center">
          <span><i class="fas fa-user-check me-1"></i> QA Audit & Verifier Decision</span>
          <span id="verifierNameBadge" class="badge bg-primary">Verifier: Unassigned</span>
        </div>
        <div class="card-body p-3">
          <form id="qaAuditForm">
            <input type="hidden" id="auditRecordingId" name="recording_id">
            <div class="row g-2 align-items-center">
              <div class="col-md-6">
                <label class="form-label small fw-bold mb-1">Verification Status</label>
                <select id="auditVerificationStatus" name="verification_status" class="form-select form-select-sm">
                  <option value="approved">Approved</option>
                  <option value="re_verification">Re-verification Required</option>
                  <option value="escalated">Escalated to Supervisor</option>
                  <option value="rejected">Rejected</option>
                  <option value="pending">Pending</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-bold mb-1">QA Score (0.0 to 10.0)</label>
                <input type="number" step="0.1" max="10" min="0" id="auditQaScore" name="qa_score" class="form-control form-control-sm" placeholder="e.g. 9.5">
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
                <button type="submit" class="btn btn-sm btn-primary px-3" id="saveAuditBtn">
                  <i class="fas fa-save me-1"></i> Update Audit Score
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- ⚠️ Abuse / Scolding / Negative Speech Alert (shown only when flagged) -->
      <div id="abuseAlertContainer" style="display:none;">
        <div class="alert border-0 rounded-3 mb-4 shadow-sm" style="background: rgba(220, 38, 38, 0.08); border-left: 4px solid #dc2626 !important;">
          <h6 class="fw-bold mb-2 text-danger"><i class="fas fa-skull-crossbones me-2"></i> ⚠️ Abuse / Scolding / Negative Speech Detected</h6>
          <div id="abuseInstancesList" class="mb-2"></div>
          <div id="negativeFlagsList"></div>
        </div>
      </div>

      <!-- Overall Score Badge with Abuse Penalty Indicator -->
      <div class="text-center mb-4">
        <div class="d-inline-block p-3 rounded-3 shadow-sm" id="overallScoreBox" style="min-width: 180px;">
          <div class="text-muted small fw-semibold text-uppercase mb-1">Overall Score</div>
          <div class="fw-extrabold" id="detOverallScore" style="font-size: 2.2rem; line-height: 1;">-</div>
          <div class="text-muted small">/ 10</div>
          <div id="abusePenaltyBadge" style="display:none;" class="mt-1">
            <span class="badge bg-danger text-white small"><i class="fas fa-ban me-1"></i>Abuse Penalty Applied</span>
          </div>
        </div>
      </div>

      <!-- Criteria Scores Grid — Strict Color-Coded -->
      <h6 class="fw-bold mb-3"><i class="fas fa-chart-bar me-2 text-primary"></i> Evaluation Breakdown (Strict Rubric)</h6>
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
        <h6 class="fw-bold mb-2 text-primary"><i class="fas fa-file-alt me-2"></i> Executive Summary</h6>
        <p class="mb-0 text-dark small" id="detSummary">-</p>
      </div>

      <!-- 📋 Verification Question-Answer Audit Checklist -->
      <div class="card border-0 shadow-sm rounded-3 mb-4" id="qaChecklistCard">
        <div class="card-header bg-soft-primary border-0 py-3 px-3 d-flex justify-content-between align-items-center">
          <h6 class="fw-bold mb-0 text-primary"><i class="fas fa-tasks me-2"></i> Verification Question & Answer Audit</h6>
          <span id="qaCoverageBadge" class="badge bg-primary rounded-pill">Coverage: 0%</span>
        </div>
        <div class="card-body p-3">
          <!-- Out of Box Alert -->
          <div id="outOfBoxAlertContainer" class="alert alert-warning border-0 small mb-3" style="display:none;">
            <strong class="text-dark"><i class="fas fa-exclamation-triangle me-1 text-warning"></i> Out-of-Box Segments Flagged:</strong>
            <div id="outOfBoxList" class="mt-1"></div>
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
                <tr><td colspan="3" class="text-center text-muted py-3">No question audit data available</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- AI Strengths & Weaknesses -->
      <div class="row g-3 mb-4">
        <div class="col-6">
          <div class="p-3 border rounded-3 bg-soft-success h-100">
            <h6 class="fw-bold text-success mb-2 small"><i class="fas fa-check-circle me-1"></i> Key Strengths</h6>
            <ul class="mb-0 ps-3 small text-dark" id="detStrengths"></ul>
          </div>
        </div>
        <div class="col-6">
          <div class="p-3 border rounded-3 bg-soft-warning h-100">
            <h6 class="fw-bold text-warning mb-2 small"><i class="fas fa-exclamation-circle me-1"></i> Areas for Coaching</h6>
            <ul class="mb-0 ps-3 small text-dark" id="detWeaknesses"></ul>
          </div>
        </div>
      </div>

      <!-- Recommendations -->
      <div class="mb-4">
        <h6 class="fw-bold mb-2"><i class="fas fa-lightbulb me-2 text-primary"></i> Actionable AI Recommendations</h6>
        <ul class="list-group list-group-flush border rounded-3" id="detRecommendations"></ul>
      </div>

      <!-- Extracted Keywords -->
      <div class="mb-4">
        <h6 class="fw-bold mb-2"><i class="fas fa-tags me-2 text-primary"></i> Extracted Keywords</h6>
        <div id="detKeywords" class="d-flex flex-wrap gap-1"></div>
      </div>

      <!-- Full Transcript -->
      <h6 class="fw-bold mb-2"><i class="fas fa-quote-left me-2 text-primary"></i> Transcript</h6>
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
      btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Uploading...');

      $.ajax({
        url: '<?= base_url('call-analysis/upload') ?>',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(resp) {
          btn.prop('disabled', false).html('<i class="fas fa-paper-plane me-1"></i> Start Upload & Queue AI');
          if (resp.status) {
            $('#uploadAlert').removeClass().addClass('alert alert-success').text(resp.message).show();
            setTimeout(function() { location.reload(); }, 1500);
          } else {
            $('#uploadAlert').removeClass().addClass('alert alert-danger').text(resp.message).show();
          }
        },
        error: function() {
          btn.prop('disabled', false).html('<i class="fas fa-paper-plane me-1"></i> Start Upload & Queue AI');
          $('#uploadAlert').removeClass().addClass('alert alert-danger').text('Server error occurred during file upload.').show();
        }
      });
    });

    $('#qaAuditForm').on('submit', function(e) {
      e.preventDefault();
      let btn = $('#saveAuditBtn');
      btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');

      $.ajax({
        url: '<?= base_url('call-analysis/save-verifier-audit') ?>',
        type: 'POST',
        data: $(this).serialize(),
        success: function(resp) {
          btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Update Audit Score');
          if (resp.status) {
            alert('Verifier audit details updated successfully.');
            location.reload();
          } else {
            alert('Error: ' + resp.message);
          }
        },
        error: function() {
          btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Update Audit Score');
          alert('Server error saving QA audit.');
        }
      });
    });

    $('#searchInput').on('keyup', function() {
      let value = $(this).val().toLowerCase();
      $('#callRecordingsTable tbody tr').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
  });

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
          scales: { y: { min: 0, max: 10 } }
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
          plugins: { legend: { position: 'bottom' } }
        }
      });
    });
  }

  function openCallDetails(id) {
    let offcanvas = new bootstrap.Offcanvas(document.getElementById('callDetailsOffcanvas'));
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

      // Fill Audit Form
      $('#auditVerificationStatus').val(d.verification_status || 'approved');
      $('#auditQaScore').val(d.qa_score !== null && d.qa_score !== undefined ? d.qa_score : (d.overall_rating || 5.0));
      $('#auditIsEscalated').prop('checked', parseInt(d.is_escalated) === 1);
      $('#auditHasComplaint').prop('checked', parseInt(d.has_complaint) === 1);

      // ─── Abuse / Scolding / Negative Speech Alert ──────────────
      let hasAbuse = parseInt(d.has_abuse) === 1;
      let abuseInstances = d.abuse_instances || [];
      let negativeFlags = d.negative_speech_flags || [];

      if (hasAbuse || abuseInstances.length > 0 || negativeFlags.length > 0) {
        let abuseHtml = '';
        if (abuseInstances.length > 0) {
          abuseHtml += '<div class="mb-1"><strong class="text-danger small"><i class="fas fa-exclamation-triangle me-1"></i> Abusive / Scolding Phrases:</strong></div>';
          abuseInstances.forEach(a => {
            abuseHtml += `<span class="badge bg-danger text-white me-1 mb-1 px-2 py-1"><i class="fas fa-ban me-1"></i>${a}</span>`;
          });
        }
        $('#abuseInstancesList').html(abuseHtml);

        let negHtml = '';
        if (negativeFlags.length > 0) {
          negHtml += '<div class="mb-1 mt-2"><strong class="text-warning small"><i class="fas fa-volume-up me-1"></i> Negative Speech Indicators:</strong></div>';
          negativeFlags.forEach(n => {
            negHtml += `<span class="badge bg-warning text-dark me-1 mb-1 px-2 py-1">${n}</span>`;
          });
        }
        $('#negativeFlagsList').html(negHtml);
        $('#abuseAlertContainer').show();
      } else {
        $('#abuseAlertContainer').hide();
      }

      // ─── Overall Score with Color & Abuse Penalty ──────────────
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
      $('#overallScoreBox').css({ background: scoreBoxBg, border: `2px solid ${scoreColor}` });
      $('#detOverallScore').css('color', scoreColor);

      if (hasAbuse) {
        $('#abusePenaltyBadge').show();
      } else {
        $('#abusePenaltyBadge').hide();
      }

      // ─── Strict Color-Coded Score Cards ────────────────────────
      function applyScoreColor(cardId, valId, score) {
        let el = $(`#${valId}`);
        let card = $(`#${cardId}`);
        el.text(score || '-');
        let s = parseInt(score) || 0;
        if (s <= 4) {
          el.css('color', '#dc2626');
          card.css({ 'border-color': '#fca5a5', 'background': 'rgba(220, 38, 38, 0.04)' });
        } else if (s <= 6) {
          el.css('color', '#d97706');
          card.css({ 'border-color': '#fcd34d', 'background': 'rgba(245, 158, 11, 0.04)' });
        } else {
          el.css('color', '#059669');
          card.css({ 'border-color': '#6ee7b7', 'background': '#f8fafc' });
        }
      }

      applyScoreColor('commCard', 'detComm', d.communication);
      applyScoreColor('profCard', 'detProf', d.professionalism);
      applyScoreColor('empCard', 'detEmp', d.empathy);
      applyScoreColor('compCard', 'detComp', d.compliance);

      // ─── Summary ──────────────────────────────────────────────
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

      // ─── Verification Question & Answer Audit Checklist ─────────
      let qaData = d.verification_qa || null;
      if (qaData && qaData.qa_checklist && qaData.qa_checklist.length) {
        $('#qaCoverageBadge').text('Coverage: ' + (qaData.question_coverage_pct || 0) + '%');
        let qaRows = '';
        qaData.qa_checklist.forEach(q => {
          let badgeClass = q.status_color === 'success' ? 'bg-success' : (q.status_color === 'danger' ? 'bg-danger' : 'bg-warning text-dark');
          let rowBg = q.status_color === 'danger' ? 'style="background-color: rgba(220, 38, 38, 0.05);"' : '';
          
          qaRows += `<tr ${rowBg}>
            <td>
              <div class="fw-bold text-dark">${q.question}</div>
              <small class="text-muted"><i class="fas fa-folder me-1"></i>${q.category} ${q.critical ? '<span class="badge bg-soft-danger text-danger ms-1">Required</span>' : ''}</small>
            </td>
            <td class="text-center">
              <span class="badge ${badgeClass} px-2 py-1">${q.status}</span>
            </td>
            <td>
              ${q.detected_a ? `<div class="small fw-semibold text-dark">Ans: "${q.detected_a}"</div>` : ''}
              <div class="small ${q.status_color === 'danger' ? 'text-danger fw-bold' : 'text-muted'}">${q.note}</div>
            </td>
          </tr>`;
        });
        $('#qaChecklistBody').html(qaRows);

        // Render Out of Box Alert if present
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
      }

      // Strengths
      let strHtml = '';
      if (d.strengths && d.strengths.length) {
        d.strengths.forEach(s => strHtml += `<li>${s}</li>`);
      } else {
        strHtml = '<li class="text-muted">None recorded</li>';
      }
      $('#detStrengths').html(strHtml);

      // Weaknesses — highlight abuse-related items in red
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

      // Recommendations — highlight mandatory actions in red
      let recHtml = '';
      if (d.recommendations && d.recommendations.length) {
        d.recommendations.forEach(r => {
          let iconClass = 'text-primary';
          let textClass = 'text-dark';
          if (r.includes('⚠️') || r.includes('MANDATORY') || r.includes('disciplinary') || r.includes('Re-verify')) {
            iconClass = 'text-danger';
            textClass = 'text-danger fw-bold';
          }
          recHtml += `<li class="list-group-item bg-transparent ${textClass} small"><i class="fas fa-arrow-right ${iconClass} me-2"></i>${r}</li>`;
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
