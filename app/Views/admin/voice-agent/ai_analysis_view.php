<?php echo $this->extend ( 'admin/includes/header' ); ?>
<!--start content-->
<?php echo $this->section ( 'content' ); ?>
<style>
  /* Modern Chat UI Styling */
  .chat-container {
    max-height: 500px;
    overflow-y: auto;
    padding: 20px;
    background: #f8fafc;
    border-radius: 12px;
  }

  .chat-bubble {
    max-width: 80%;
    margin-bottom: 15px;
    padding: 12px 16px;
    border-radius: 15px;
    font-size: 0.95rem;
    line-height: 1.5;
    position: relative;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
  }

  .chat-bubble.bot {
    background: #ffffff;
    color: #1e293b;
    border-bottom-left-radius: 2px;
    align-self: flex-start;
    border: 1px solid #e2e8f0;
  }

  .chat-bubble.user {
    background: #4f46e5;
    color: #ffffff;
    border-bottom-right-radius: 2px;
    align-self: flex-end;
    margin-left: auto;
  }

  .chat-time {
    font-size: 0.7rem;
    margin-top: 5px;
    opacity: 0.7;
    display: block;
  }

  /* Score Grid */
  .score-card {
    background: white;
    padding: 15px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    text-align: center;
  }

  .score-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #4f46e5;
  }

  .score-label {
    font-size: 0.8rem;
    color: #64748b;
    text-transform: uppercase;
    margin-top: 5px;
  }

  /* Modern Table Styling */
  .table-hover tbody tr:hover {
    background-color: rgba(99, 102, 241, 0.03) !important;
    cursor: pointer;
  }

  .bg-gradient-primary {
    background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
  }
</style>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?php echo $this->include ( 'admin/nav_bar' ) ?>
  </div>

  <div class="container-fluid content-inner mt-n5 py-0 px-4" id="page_layout">
    <div class="container-fluid p-0 mb-4 bg-gradient-primary rounded-3 shadow">
      <div class="text-white p-5 rounded-3 position-relative overflow-hidden">
        <h1 class="h2 fw-bold mb-2 text-white">AI Call Intelligence Dashboard</h1>
        <p class="text-white-75 mb-0">Monitor provider performance, interactions, and sentiment through AI-driven
          insights.</p>
      </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover" id="aiAnalysisTable">
            <thead>
              <tr class="bg-light">
                <th class="text-center py-3">Date</th>
                <th class="py-3">Provider / Phone</th>
                <th class="text-center py-3">Sentiment</th>
                <th class="text-center py-3">Duration</th>
                <th class="text-center py-3">AI Rating</th>
                <th class="text-center py-3">Risk</th>
                <th class="text-center py-3">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if ( !empty ( $calls ) ) : ?>
                <?php foreach ( $calls as $call ) : ?>
                  <tr onclick="showCallDetails(<?= htmlspecialchars ( json_encode ( $call ) ) ?>)">
                    <td class="text-center align-middle"><?= date ( 'd M, Y H:i', strtotime ( $call->call_date ) ) ?></td>
                    <td class="align-middle">
                      <div class="d-flex align-items-center gap-2">
                        <div class="avatar-circle">
                          <?= strtoupper ( substr ( $call->provider_name ?? 'U', 0, 1 ) ) ?>
                        </div>
                        <div>
                          <div class="fw-bold"><?= $call->provider_name ?: 'Unknown' ?></div>
                          <small class="text-muted"><?= $call->to_number ?></small>
                        </div>
                      </div>
                    </td>
                    <td class="text-center align-middle">
                      <span
                        class="badge rounded-pill bg-<?= strtolower ( $call->sentiment ) == 'positive' ? 'success' : ( str_contains ( strtolower ( $call->sentiment ), 'neg' ) ? 'danger' : 'warning' ) ?>">
                        <?= ucfirst ( $call->sentiment ?: 'Neutral' ) ?>
                      </span>
                    </td>
                    <td class="text-center align-middle"><?= $call->call_duration ?>s</td>
                    <td class="text-center align-middle">
                      <div class="fw-bold text-primary"><?= number_format ( $call->final_score ?? 0, 1 ) ?>/5</div>
                    </td>
                    <td class="text-center align-middle">
                      <?php if ( $call->risk_flag ) : ?>
                        <span class="badge bg-danger shadow-sm anim-pulse"><i class="fas fa-exclamation-triangle"></i> High
                          Risk</span>
                      <?php else : ?>
                        <span class="badge bg-light text-success"><i class="fas fa-check-circle"></i> Low Risk</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-center align-middle">
                      <button class="btn btn-sm btn-icon btn-primary rounded-circle">
                        <i class="fas fa-eye"></i>
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else : ?>
                <tr>
                  <td colspan="7" class="text-center py-5 text-muted">No interactions recorded yet.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Details Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="callDetailsOffcanvas" style="width: 600px;">
  <div class="offcanvas-header bg-gradient-primary text-white p-4">
    <h5 class="offcanvas-title text-white"><i class="fas fa-robot me-2"></i> Interaction Details</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body p-4">
    <!-- Quick Stats -->
    <div class="row g-3 mb-4">
      <div class="col-4">
        <div class="score-card">
          <div class="score-value" id="det-clarity">-</div>
          <div class="score-label">Clarity</div>
        </div>
      </div>
      <div class="col-4">
        <div class="score-card">
          <div class="score-value" id="det-conf">-</div>
          <div class="score-label">Confidence</div>
        </div>
      </div>
      <div class="col-4">
        <div class="score-card">
          <div class="score-value" id="det-prof">-</div>
          <div class="score-label">Prof.</div>
        </div>
      </div>
    </div>

    <!-- Summary Box -->
    <div class="alert alert-primary border-0 rounded-3 mb-4" style="background: rgba(79, 70, 229, 0.05);">
      <h6 class="fw-bold mb-2 text-primary"><i class="fas fa-file-alt me-2"></i> AI Summary</h6>
      <p class="mb-0 small text-dark" id="det-summary"></p>
    </div>

    <!-- AI Extracted Insights -->
    <div id="ai-insights-section" class="mb-4">
      <h6 class="fw-bold mb-3"><i class="fas fa-brain me-2 text-primary"></i> AI Extracted Insights</h6>
      <div class="row g-2" id="det-insights-grid">
        <!-- Insights will be injected here -->
      </div>
    </div>

    <!-- Chat History -->
    <h6 class="fw-bold mb-3"><i class="fas fa-comments me-2 text-primary"></i> Conversation Flow</h6>
    <div class="chat-container d-flex flex-column mb-4" id="chat-history">
      <!-- Chat bubbles injected here -->
    </div>

    <!-- Recording Player -->
    <div class="p-3 border rounded-3 bg-light mb-4" id="recording-player-box" style="display:none;">
      <h6 class="fw-bold mb-2 small">Call Recording</h6>
      <audio id="det-audio" controls class="w-100"></audio>
    </div>
  </div>
</div>

<script>
  function showCallDetails(call) {
    const offcanvas = new bootstrap.Offcanvas(document.getElementById('callDetailsOffcanvas'));
    console.log("Call Data:", call);

    // Populate metadata
    document.getElementById('det-summary').innerText = call.summary || 'Summary not available.';
    document.getElementById('det-clarity').innerHTML = call.clarity_score ? `<span>${call.clarity_score}</span>` : '<small class="text-muted">N/A</small>';
    document.getElementById('det-conf').innerHTML = call.confidence_score ? `<span>${call.confidence_score}</span>` : '<small class="text-muted">N/A</small>';
    document.getElementById('det-prof').innerHTML = call.professionalism_score ? `<span>${call.professionalism_score}</span>` : '<small class="text-muted">N/A</small>';

    // Parse Extracted Data
    const insightsGrid = document.getElementById('det-insights-grid');
    insightsGrid.innerHTML = '';
    
    let extracted = {};
    try {
        if (call.extracted_data) {
            extracted = typeof call.extracted_data === 'string' ? JSON.parse(call.extracted_data) : call.extracted_data;
        }
    } catch (e) {
        console.error("Error parsing extracted_data:", e);
    }

    const importantFields = {
        'service_type': { label: 'Service', icon: 'fas fa-concierge-bell' },
        'experience_years': { label: 'Experience', icon: 'fas fa-history' },
        'availability': { label: 'Availability', icon: 'fas fa-calendar-check' },
        'documents_ready': { label: 'Docs Ready', icon: 'fas fa-id-card' },
        'risk_level': { label: 'Risk Level', icon: 'fas fa-shield-alt' },
        'handles_aggressive_pets': { label: 'Aggressive Pets', icon: 'fas fa-paw' },
        'language_switching': { label: 'Multilingual', icon: 'fas fa-language' },
        'communication_quality': { label: 'Comm. Quality', icon: 'fas fa-comments' },
        'hesitation_detected': { label: 'Hesitation', icon: 'fas fa-clock' },
        'fraud_flags': { label: 'Fraud Flags', icon: 'fas fa-flag' },
        'call_outcome': { label: 'Outcome', icon: 'fas fa-phone-slash' },
        'approval_status': { label: 'Status', icon: 'fas fa-user-check' },
        'strengths': { label: 'Strengths', icon: 'fas fa-plus-circle' },
        'weaknesses': { label: 'Weaknesses', icon: 'fas fa-minus-circle' },
        'final_recommendation': { label: 'Recommendation', icon: 'fas fa-thumbs-up' }
    };

    const longTextFields = ['strengths', 'weaknesses', 'final_recommendation', 'fraud_flags'];

    Object.keys(importantFields).forEach(key => {
        const value = extracted[key];
        if (value !== undefined && value !== null && value !== '') {
            let displayValue = value;
            let badgeClass = 'bg-light text-dark';
            
            if (value === 'true' || value === true || value === 'Yes') {
                displayValue = 'Yes';
                badgeClass = 'bg-soft-success text-success';
            } else if (value === 'false' || value === false || value === 'No') {
                displayValue = 'No';
            }

            if (key === 'risk_level') {
                badgeClass = value.toLowerCase() === 'low' ? 'bg-soft-success text-success' : (value.toLowerCase() === 'high' ? 'bg-soft-danger text-danger' : 'bg-soft-warning text-warning');
            }

            const isLongText = longTextFields.includes(key);
            const gridClass = isLongText ? 'col-12' : 'col-6';
            const valueClass = isLongText ? 'small' : 'fw-bold text-truncate';

            insightsGrid.innerHTML += `
                <div class="${gridClass}">
                    <div class="d-flex align-items-start p-2 border rounded-3 bg-white shadow-xs h-100">
                        <div class="flex-shrink-0 me-2 text-primary mt-1">
                            <i class="${importantFields[key].icon} fa-fw"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="text-muted" style="font-size: 0.7rem; text-transform: uppercase;">${importantFields[key].label}</div>
                            <div class="${valueClass}" style="font-size: 0.85rem;">${displayValue}</div>
                        </div>
                    </div>
                </div>
            `;
        }
    });

    if (insightsGrid.innerHTML === '') {
        document.getElementById('ai-insights-section').style.display = 'none';
    } else {
        document.getElementById('ai-insights-section').style.display = 'block';
    }

    // Audio
    const audioBox = document.getElementById('recording-player-box');
    const audioElem = document.getElementById('det-audio');
    if (call.recording_url && call.recording_url !== '0') {
      audioElem.src = call.recording_url;
      audioBox.style.display = 'block';
    } else {
      audioBox.style.display = 'none';
    }

    // Populate Chat
    const chatBox = document.getElementById('chat-history');
    chatBox.innerHTML = '';

    if (call.interactions && call.interactions.length > 0) {
      call.interactions.forEach(turn => {
        // Bot turn (if exists)
        if (turn.bot_response) {
          chatBox.innerHTML += `
            <div class="chat-bubble bot">
              ${turn.bot_response}
              <span class="chat-time">${turn.interaction_time || ''}</span>
            </div>`;
        }
        // User turn (if exists)
        if (turn.user_query) {
          chatBox.innerHTML += `
            <div class="chat-bubble user">
              ${turn.user_query}
              <span class="chat-time">${turn.interaction_time || ''}</span>
            </div>`;
        }
      });
    } else if (call.full_conversation) {
      chatBox.innerHTML = `<div class="p-3 text-muted small border-start border-3" style="background: #f1f5f9; white-space: pre-wrap;">${call.full_conversation}</div>`;
    } else {
      chatBox.innerHTML = `<div class="text-center py-4 text-muted">No conversation data found.</div>`;
    }

    offcanvas.show();
  }
</script>

<?php echo $this->include ( 'admin/footer' ) ?>
<?php echo $this->endSection () ?>