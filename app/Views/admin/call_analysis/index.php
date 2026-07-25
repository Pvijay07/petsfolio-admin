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
          <h1 class="h2 fw-bold mb-2 text-white"><i class="fas fa-phone-volume me-2"></i> AI Call Intelligence & Quality Dashboard</h1>
          <p class="text-white-75 mb-0">Automated Speech Recognition (Faster-Whisper), Speaker Diarization, and Ollama LLM QA scoring for Petsfolio calls.</p>
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
              <span class="text-muted small fw-semibold uppercase">Average Call Score</span>
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
              <span class="text-muted small fw-semibold uppercase">Positive Sentiment</span>
              <h3 class="fw-extrabold mb-0 mt-1 text-success"><?= $positivePercentage ?>%</h3>
            </div>
            <div class="avatar bg-soft-info text-info rounded-circle p-3">
              <i class="fas fa-smile fa-2x"></i>
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
          <input type="text" id="searchInput" class="form-control form-control-sm rounded-2" placeholder="Search provider or ID..." style="width: 220px;">
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" id="callRecordingsTable">
            <thead class="bg-light">
              <tr>
                <th class="py-3 px-4">Call ID / Date</th>
                <th class="py-3">Type</th>
                <th class="py-3">Provider ID</th>
                <th class="py-3">Duration</th>
                <th class="text-center py-3">Rating</th>
                <th class="text-center py-3">Sentiment</th>
                <th class="text-center py-3">Risk</th>
                <th class="text-center py-3">Status</th>
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
                      <span class="badge bg-soft-info text-info text-capitalize"><?= esc($call['call_type'] ?? 'Inbound') ?></span>
                    </td>
                    <td>
                      <span class="fw-bold text-dark"><?= esc($call['provider_id'] ? 'SP #' . $call['provider_id'] : 'Unassigned') ?></span>
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
                        $sent = strtolower($call['customer_sentiment'] ?? '');
                        $badgeBg = $sent === 'positive' ? 'bg-success' : ($sent === 'negative' ? 'bg-danger' : 'bg-warning');
                      ?>
                      <span class="badge <?= $badgeBg ?> text-white rounded-pill px-3"><?= ucfirst($call['customer_sentiment'] ?: 'Pending') ?></span>
                    </td>
                    <td class="text-center">
                      <?php if (isset($call['risk_score']) && $call['risk_score'] > 3) : ?>
                        <span class="badge bg-danger shadow-sm"><i class="fas fa-exclamation-triangle"></i> High Risk (<?= $call['risk_score'] ?>)</span>
                      <?php else : ?>
                        <span class="badge bg-soft-success text-success"><i class="fas fa-check-circle"></i> Low Risk</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <?php 
                        $st = strtolower($call['status'] ?? 'uploaded');
                        $stClass = $st === 'completed' ? 'bg-soft-success text-success' : ($st === 'processing' ? 'bg-soft-warning text-warning' : ($st === 'failed' ? 'bg-soft-danger text-danger' : 'bg-soft-secondary text-secondary'));
                      ?>
                      <span class="badge <?= $stClass ?> text-uppercase"><?= esc($call['status']) ?></span>
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
              <option value="inbound">Inbound Customer Call</option>
              <option value="outbound">Outbound Provider Follow-up</option>
              <option value="verification">Provider Verification Call</option>
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

<!-- Details Offcanvas Drawer -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="callDetailsOffcanvas" style="width: 650px;">
  <div class="offcanvas-header bg-gradient-call text-white p-4">
    <h5 class="offcanvas-title text-white fw-bold" id="drawerTitle"><i class="fas fa-robot me-2"></i> Call Evaluation Details</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body p-4">
    <div id="drawerLoading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status"></div>
      <p class="text-muted mt-2">Loading AI Analysis Payload...</p>
    </div>

    <div id="drawerContent" style="display:none;">
      <!-- Audio Player -->
      <div class="p-3 border rounded-3 bg-light mb-4" id="audioPlayerContainer">
        <label class="fw-bold mb-2 small text-uppercase text-muted"><i class="fas fa-play-circle me-1 text-primary"></i> Audio Playback</label>
        <audio id="drawerAudio" controls class="w-100"></audio>
      </div>

      <!-- Criteria Scores Grid -->
      <h6 class="fw-bold mb-3"><i class="fas fa-chart-bar me-2 text-primary"></i> Evaluation Breakdown</h6>
      <div class="row g-2 mb-4">
        <div class="col-3">
          <div class="score-card-mini">
            <div class="val" id="detComm">-</div>
            <div class="lbl">Comm.</div>
          </div>
        </div>
        <div class="col-3">
          <div class="score-card-mini">
            <div class="val" id="detProf">-</div>
            <div class="lbl">Prof.</div>
          </div>
        </div>
        <div class="col-3">
          <div class="score-card-mini">
            <div class="val" id="detEmp">-</div>
            <div class="lbl">Empathy</div>
          </div>
        </div>
        <div class="col-3">
          <div class="score-card-mini">
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

    $('#searchInput').on('keyup', function() {
      let value = $(this).val().toLowerCase();
      $('#callRecordingsTable tbody tr').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
  });

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
      $('#detSummary').text(d.summary || 'Summary pending processing.');
      $('#detComm').text(d.communication || '-');
      $('#detProf').text(d.professionalism || '-');
      $('#detEmp').text(d.empathy || '-');
      $('#detComp').text(d.compliance || '-');

      // Audio
      if (d.recording_url) {
        $('#drawerAudio').attr('src', '<?= base_url() ?>' + d.recording_url);
        $('#audioPlayerContainer').show();
      } else {
        $('#audioPlayerContainer').hide();
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
        d.weaknesses.forEach(w => wkHtml += `<li>${w}</li>`);
      } else {
        wkHtml = '<li class="text-muted">None recorded</li>';
      }
      $('#detWeaknesses').html(wkHtml);

      // Recommendations
      let recHtml = '';
      if (d.recommendations && d.recommendations.length) {
        d.recommendations.forEach(r => recHtml += `<li class="list-group-item bg-transparent text-dark small"><i class="fas fa-arrow-right text-primary me-2"></i>${r}</li>`);
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
