<?= $this->extend('admin/includes/header'); ?>
<!--start content-->
<?= $this->section('content'); ?>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?= $this->include('admin/nav_bar') ?>
  </div>
  <div class="container-fluid px-4 content-inner pb-0" id="page_layout">

    <!-- Page Header -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
          <div>
            <h4 class="fw-bold mb-1">Campaigns</h4>
            <p class="text-muted mb-0">Create and manage notification campaigns</p>
          </div>
          <button type="button" class="btn btn-primary rounded-pill px-4" id="btnNewCampaign">
            <i class="bi bi-plus-circle me-1"></i> New Campaign
          </button>
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
                <label class="form-label small fw-semibold">Status</label>
                <select name="status" class="form-select form-select-sm">
                  <option value="">All Statuses</option>
                  <?php foreach (['draft','scheduled','sending','sent','cancelled','failed'] as $s): ?>
                    <option value="<?= $s ?>" <?= ($filters['status'] ?? '') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-2">
                <label class="form-label small fw-semibold">Type</label>
                <select name="type" class="form-select form-select-sm">
                  <option value="">All Types</option>
                  <?php foreach (['promotional','reminder','engagement','transactional'] as $t): ?>
                    <option value="<?= $t ?>" <?= ($filters['type'] ?? '') === $t ? 'selected' : '' ?>><?= ucfirst($t) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-2">
                <label class="form-label small fw-semibold">Channel</label>
                <select name="channel" class="form-select form-select-sm">
                  <option value="">All Channels</option>
                  <?php foreach (['push','sms','email','whatsapp'] as $c): ?>
                    <option value="<?= $c ?>" <?= ($filters['channel'] ?? '') === $c ? 'selected' : '' ?>><?= ucfirst($c) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label small fw-semibold">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Campaign name or title..." value="<?= esc($filters['search'] ?? '') ?>">
              </div>
              <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary px-3"><i class="bi bi-search me-1"></i>Filter</button>
                <a href="<?= base_url('notification-manager/campaigns') ?>" class="btn btn-sm btn-outline-secondary">Clear</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Campaigns List -->
    <div class="row">
      <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-body p-4">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                  <tr>
                    <th class="border-0 rounded-start py-3 ps-3">#</th>
                    <th class="border-0 py-3">Campaign</th>
                    <th class="border-0 py-3">Type</th>
                    <th class="border-0 py-3">Target</th>
                    <th class="border-0 py-3">Channel</th>
                    <th class="border-0 py-3">Schedule</th>
                    <th class="border-0 py-3">Delivery</th>
                    <th class="border-0 py-3">Status</th>
                    <th class="border-0 rounded-end py-3">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($campaigns)): ?>
                    <?php $i = 1; foreach ($campaigns as $campaign): ?>
                      <tr>
                        <td class="ps-3"><?= $i++ ?></td>
                        <td>
                          <div class="fw-semibold"><?= esc($campaign->name) ?></div>
                          <small class="text-muted"><?= esc(mb_substr($campaign->title, 0, 40)) ?><?= mb_strlen($campaign->title) > 40 ? '...' : '' ?></small>
                        </td>
                        <td>
                          <?php
<<<<<<< HEAD
                          $typeColors = ['promotional' => 'primary', 'reminder' => 'info', 'engagement' => 'warning', 'transactional' => 'dark'];
                          ?>
                          <span class="badge bg-<?= $typeColors[$campaign->type] ?? 'secondary' ?> bg-opacity-10 text-<?= $typeColors[$campaign->type] ?? 'secondary' ?> rounded-pill">
                            <?= ucfirst($campaign->type) ?>
=======
                          $campType   = !empty($campaign->type) ? strtolower($campaign->type) : 'promotional';
                          $typeBadges = [
                            'promotional'   => 'bg-primary text-white',
                            'reminder'      => 'bg-info text-dark',
                            'engagement'    => 'bg-warning text-dark',
                            'transactional' => 'bg-dark text-white',
                          ];
                          $badgeStyle = $typeBadges[$campType] ?? 'bg-secondary text-white';
                          ?>
                          <span class="badge <?= $badgeStyle ?> rounded-pill px-2 py-1">
                            <?= ucfirst($campType) ?>
>>>>>>> origin/main
                          </span>
                        </td>
                        <td>
                          <span class="text-capitalize"><?= esc($campaign->target) ?></span>
                          <div class="small text-muted"><?= number_format($campaign->total_audience ?? 0) ?> users</div>
                        </td>
                        <td>
                          <?php
                          $chIcons = ['push' => 'bi-phone', 'sms' => 'bi-chat-dots', 'email' => 'bi-envelope', 'whatsapp' => 'bi-whatsapp', 'all' => 'bi-broadcast'];
                          ?>
                          <i class="bi <?= $chIcons[$campaign->channel] ?? 'bi-bell' ?> me-1"></i><?= ucfirst($campaign->channel) ?>
                        </td>
                        <td>
                          <?php if ($campaign->schedule_type === 'scheduled' && $campaign->scheduled_at): ?>
                            <small><i class="bi bi-clock me-1"></i><?= date('M d, h:i A', strtotime($campaign->scheduled_at)) ?></small>
                          <?php elseif ($campaign->sent_at): ?>
                            <small class="text-success"><i class="bi bi-check-circle me-1"></i><?= date('M d, h:i A', strtotime($campaign->sent_at)) ?></small>
                          <?php else: ?>
                            <small class="text-muted">Immediate</small>
                          <?php endif; ?>
                        </td>
                        <td>
                          <?php if ($campaign->sent_count > 0 || $campaign->failed_count > 0): ?>
                            <span class="text-success fw-semibold"><?= number_format($campaign->sent_count) ?></span>
                            <?php if ($campaign->failed_count > 0): ?>
                              / <span class="text-danger"><?= number_format($campaign->failed_count) ?></span>
                            <?php endif; ?>
                          <?php else: ?>
                            <span class="text-muted">—</span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <?php
                          $statusColors = [
                            'draft' => 'secondary', 'scheduled' => 'warning', 'sending' => 'info',
                            'sent' => 'success', 'cancelled' => 'dark', 'failed' => 'danger',
                          ];
                          ?>
<<<<<<< HEAD
                          <span class="badge bg-<?= $statusColors[$campaign->status] ?? 'secondary' ?> rounded-pill"><?= ucfirst($campaign->status) ?></span>
                        </td>
                        <td>
                          <div class="d-flex gap-1">
                            <?php if (in_array($campaign->status, ['draft', 'scheduled'])): ?>
                              <button class="btn btn-sm btn-success rounded-pill send-campaign" data-id="<?= $campaign->id ?>" data-name="<?= esc($campaign->name) ?>" title="Send Now">
                                <i class="bi bi-send"></i>
                              </button>
                              <button class="btn btn-sm btn-outline-danger rounded-pill cancel-campaign" data-id="<?= $campaign->id ?>" title="Cancel">
                                <i class="bi bi-x-circle"></i>
                              </button>
                            <?php endif; ?>
                            <?php if ($campaign->status === 'sent'): ?>
                              <a href="<?= base_url('notification-manager/logs?campaign_id=' . $campaign->id) ?>" class="btn btn-sm btn-outline-primary rounded-pill" title="View Logs">
                                <i class="bi bi-list-ul"></i>
=======
                          <span class="badge bg-<?= $statusColors[$campaign->status] ?? 'secondary' ?> rounded-pill px-2 py-1"><?= ucfirst($campaign->status) ?></span>
                        </td>
                        <td>
                          <div class="d-flex gap-1 align-items-center">
                            <?php if (in_array($campaign->status, ['draft', 'scheduled', 'failed'])): ?>
                              <button class="btn btn-sm btn-success rounded-pill send-campaign" data-id="<?= $campaign->id ?>" data-name="<?= esc($campaign->name) ?>" title="<?= $campaign->status === 'failed' ? 'Retry Send' : 'Send Now' ?>">
                                <i class="bi bi-send me-1"></i><?= $campaign->status === 'failed' ? 'Retry' : 'Send' ?>
                              </button>
                              <?php if ($campaign->status !== 'failed'): ?>
                                <button class="btn btn-sm btn-outline-danger rounded-pill cancel-campaign" data-id="<?= $campaign->id ?>" title="Cancel">
                                  <i class="bi bi-x-circle"></i>
                                </button>
                              <?php endif; ?>
                            <?php endif; ?>
                            <?php if (in_array($campaign->status, ['sent', 'failed', 'sending'])): ?>
                              <a href="<?= base_url('notification-manager/logs?campaign_id=' . $campaign->id) ?>" class="btn btn-sm btn-outline-primary rounded-pill px-2" title="View Logs">
                                <i class="bi bi-list-ul me-1"></i>Logs
>>>>>>> origin/main
                              </a>
                            <?php endif; ?>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="9" class="text-center py-5 text-muted">
                        <i class="bi bi-megaphone fs-1 d-block mb-2 opacity-25"></i>
                        No campaigns found. Create your first campaign!
                      </td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Create/Edit Campaign Offcanvas -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="campaignOffcanvas" style="width:620px;">
    <div class="offcanvas-header border-bottom px-4 py-3">
      <h5 class="offcanvas-title fw-bold">New Campaign</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body px-4 py-3" style="overflow-y:auto;">
      <form id="campaignForm">
        <input type="hidden" name="id" id="camp_id">

        <!-- Campaign Info -->
        <div class="mb-3">
          <label class="form-label fw-semibold">Campaign Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="name" id="camp_name" placeholder="e.g. Weekend Grooming Sale" required>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold">Type</label>
            <select class="form-select" name="type" id="camp_type">
              <option value="promotional">Promotional</option>
              <option value="reminder">Reminder</option>
              <option value="engagement">Engagement</option>
              <option value="transactional">Transactional</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Channel</label>
            <select class="form-select" name="channel" id="camp_channel">
              <option value="push">Push Notification</option>
              <option value="sms">SMS</option>
              <option value="email">Email</option>
              <option value="whatsapp">WhatsApp</option>
              <option value="all">All Channels</option>
            </select>
          </div>
        </div>

        <hr class="my-3">

        <!-- Target Audience -->
        <h6 class="fw-bold mb-3"><i class="bi bi-people me-1"></i> Target Audience</h6>

        <div class="mb-3">
          <select class="form-select" name="target" id="camp_target">
            <option value="all">All Users (Clients + Providers)</option>
            <option value="clients">Clients Only</option>
            <option value="providers">Providers Only</option>
            <option value="segment">Custom Segment</option>
          </select>
        </div>

        <!-- Segment Rules (shown when target=segment) -->
        <div id="segmentRules" style="display:none;" class="p-3 rounded-3 mb-3" style="background:#f8f9ff;">
          <div class="row g-2">
            <div class="col-md-6">
              <label class="form-label small">City</label>
              <input type="text" class="form-control form-control-sm" name="segment_city" id="seg_city" placeholder="e.g. Hyderabad">
            </div>
            <div class="col-md-6">
              <label class="form-label small">Pet Type</label>
              <select class="form-select form-select-sm" name="segment_pet_type" id="seg_pet_type">
                <option value="">Any</option>
                <option value="Dog">Dog</option>
                <option value="Cat">Cat</option>
                <option value="Bird">Bird</option>
                <option value="Fish">Fish</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label small">Service Type</label>
              <select class="form-select form-select-sm" name="segment_service_type" id="seg_service_type">
                <option value="">Any</option>
                <option value="Walking">Walking</option>
                <option value="Grooming">Grooming</option>
                <option value="Training">Training</option>
                <option value="Boarding">Boarding</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label small">App Source</label>
              <select class="form-select form-select-sm" name="segment_source" id="seg_source">
                <option value="">Any</option>
                <option value="app">App</option>
                <option value="browser">Browser</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label small">Registered After</label>
              <input type="date" class="form-control form-control-sm" name="segment_registered_after" id="seg_reg_after" style="padding: 0.375rem 0.75rem; font-family: inherit; background: white;">
            </div>
            <div class="col-md-6">
              <label class="form-label small">Registered Before</label>
              <input type="date" class="form-control form-control-sm" name="segment_registered_before" id="seg_reg_before" style="padding: 0.375rem 0.75rem; font-family: inherit; background: white;">
            </div>
          </div>
        </div>

        <!-- Audience Preview -->
        <div class="mb-3 p-3 rounded-3 d-flex justify-content-between align-items-center" style="background:#ecfdf5; border:1px solid #a7f3d0;">
          <div>
            <i class="bi bi-people-fill me-1 text-success"></i>
            <span class="fw-semibold">Estimated Audience:</span>
            <strong id="audienceCount" class="text-success ms-1">—</strong>
          </div>
          <button type="button" class="btn btn-sm btn-outline-success rounded-pill" id="btnPreviewAudience">
            <i class="bi bi-arrow-clockwise me-1"></i>Refresh
          </button>
        </div>

        <hr class="my-3">

        <!-- Message -->
        <h6 class="fw-bold mb-3"><i class="bi bi-chat-left-text me-1"></i> Message</h6>

        <div class="mb-3">
          <label class="form-label fw-semibold">Use Template (Optional)</label>
          <select class="form-select" name="template_id" id="camp_template">
            <option value="">— Custom Message —</option>
            <?php foreach (($templates ?? []) as $tpl): ?>
              <option value="<?= $tpl->id ?>" data-title="<?= esc($tpl->title) ?>" data-body="<?= esc($tpl->body ?? $tpl->content ?? '') ?>">
                <?= esc($tpl->name ?: $tpl->title) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="title" id="camp_title" placeholder="Notification title" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Body <span class="text-danger">*</span></label>
          <textarea class="form-control" name="body" id="camp_body" rows="4" placeholder="Notification body" required></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Deep Link Route (Optional)</label>
          <input type="text" class="form-control" name="route_name" id="camp_route" placeholder="e.g. BookingDetails, Offers">
        </div>

        <hr class="my-3">

        <!-- Schedule -->
        <h6 class="fw-bold mb-3"><i class="bi bi-clock me-1"></i> Schedule</h6>

        <div class="mb-3">
          <select class="form-select" name="schedule_type" id="camp_schedule_type">
            <option value="immediate">Send Immediately</option>
            <option value="scheduled">Schedule for Later</option>
          </select>
        </div>

        <div id="scheduleDateTime" style="display:none;" class="mb-3">
          <label class="form-label small">Send At</label>
          <input type="datetime-local" class="form-control" name="scheduled_at" id="camp_scheduled_at" style="padding: 0.375rem 0.75rem; font-family: inherit; background: white;">
        </div>

      </form>
    </div>
    <div class="offcanvas-footer border-top p-3 px-4 d-flex gap-2">
      <button type="button" class="btn btn-outline-primary px-3" id="btnSaveDraft">
        <i class="bi bi-file-earmark me-1"></i> Save Draft
      </button>
      <button type="button" class="btn btn-primary px-4" id="btnSaveAndSend">
        <i class="bi bi-send me-1"></i> Save & Send
      </button>
      <button type="button" class="btn btn-light" data-bs-dismiss="offcanvas">Cancel</button>
    </div>
  </div>

  <?= $this->include('admin/footer') ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
  const baseUrl = '<?= base_url() ?>';

  // Show/hide segment rules
  $('#camp_target').change(function() {
    $('#segmentRules').toggle($(this).val() === 'segment');
    refreshAudienceCount();
  });

  // Show/hide schedule datetime
  $('#camp_schedule_type').change(function() {
    $('#scheduleDateTime').toggle($(this).val() === 'scheduled');
  });

  // Template selection fills title/body
  $('#camp_template').change(function() {
    const $opt = $(this).find(':selected');
    if ($opt.val()) {
      $('#camp_title').val($opt.data('title'));
      $('#camp_body').val($opt.data('body'));
    }
  });

  // New campaign
  $('#btnNewCampaign').click(function() {
    $('#campaignForm')[0].reset();
    $('#camp_id').val('');
    $('#segmentRules').hide();
    $('#scheduleDateTime').hide();
    $('#audienceCount').text('—');
    new bootstrap.Offcanvas(document.getElementById('campaignOffcanvas')).show();
    setTimeout(refreshAudienceCount, 500);
  });

  // Audience preview
  $('#btnPreviewAudience').click(refreshAudienceCount);
  $('#camp_target, #seg_city, #seg_pet_type, #seg_service_type, #seg_source').on('change', function() {
    refreshAudienceCount();
  });

  function refreshAudienceCount() {
    const $count = $('#audienceCount');
    $count.html('<span class="spinner-border spinner-border-sm"></span>');

    $.ajax({
      url: baseUrl + 'notification-manager/campaigns/preview-audience',
      method: 'POST',
      data: {
        target: $('#camp_target').val(),
        segment_city: $('#seg_city').val(),
        segment_pet_type: $('#seg_pet_type').val(),
        segment_service_type: $('#seg_service_type').val(),
        segment_source: $('#seg_source').val(),
        segment_registered_after: $('#seg_reg_after').val(),
        segment_registered_before: $('#seg_reg_before').val(),
      },
      success: function(res) {
        $count.text(res.count !== undefined ? res.count.toLocaleString() + ' users' : '—');
      },
      error: function() { $count.text('Error'); }
    });
  }

  // Save Draft
  $('#btnSaveDraft').click(function() { saveCampaign(false); });

  // Save & Send
  $('#btnSaveAndSend').click(function() {
    Swal.fire({
      title: 'Send Campaign?',
      text: 'This will send notifications to all matching users immediately.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#6366f1',
      confirmButtonText: 'Send Now',
    }).then((result) => {
      if (result.isConfirmed) { saveCampaign(true); }
    });
  });

  function saveCampaign(sendImmediately) {
    const $btn = sendImmediately ? $('#btnSaveAndSend') : $('#btnSaveDraft');
    const origHtml = $btn.html();
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Processing...');

    $.ajax({
      url: baseUrl + 'notification-manager/campaigns/save',
      method: 'POST',
      data: $('#campaignForm').serialize(),
      success: function(res) {
        if (res.status) {
          if (sendImmediately && res.id) {
            // Now send
            $.post(baseUrl + 'notification-manager/campaigns/send', { id: res.id }, function(sendRes) {
              Swal.fire({
                icon: sendRes.status ? 'success' : 'error',
<<<<<<< HEAD
                title: sendRes.status ? 'Campaign Sent!' : 'Send Failed',
                text: sendRes.message,
                timer: 3000,
              });
              setTimeout(() => location.reload(), 2000);
=======
                title: sendRes.status ? 'Campaign Queued!' : 'Queue Failed',
                text: sendRes.message,
                timer: 2500,
              });
              setTimeout(() => location.reload(), 1800);
>>>>>>> origin/main
            });
          } else {
            Swal.fire({ icon: 'success', title: 'Saved!', text: res.message, timer: 1500, showConfirmButton: false });
            setTimeout(() => location.reload(), 1500);
          }
        } else {
          Swal.fire('Error', res.message || 'Failed to save', 'error');
        }
      },
      error: function() { Swal.fire('Error', 'Server error', 'error'); },
      complete: function() { $btn.prop('disabled', false).html(origHtml); }
    });
  }

<<<<<<< HEAD
  // Send campaign
=======
  // Send campaign (Queue background execution)
>>>>>>> origin/main
  $(document).on('click', '.send-campaign', function() {
    const id = $(this).data('id');
    const name = $(this).data('name');
    Swal.fire({
<<<<<<< HEAD
      title: 'Send Campaign?',
      html: 'Send <strong>' + name + '</strong> to all matching users now?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#10b981',
      confirmButtonText: 'Send Now',
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({ title: 'Sending...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        $.post(baseUrl + 'notification-manager/campaigns/send', { id: id }, function(res) {
          Swal.fire({ icon: res.status ? 'success' : 'error', title: res.status ? 'Sent!' : 'Failed', text: res.message });
          if (res.status) setTimeout(() => location.reload(), 2000);
=======
      title: 'Queue Campaign?',
      html: 'Queue <strong>' + name + '</strong> for immediate background dispatch to all matching recipients?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#10b981',
      confirmButtonText: 'Queue & Send Now',
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({ title: 'Queueing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        $.post(baseUrl + 'notification-manager/campaigns/send', { id: id }, function(res) {
          Swal.fire({ icon: res.status ? 'success' : 'error', title: res.status ? 'Queued for Dispatch!' : 'Failed', text: res.message });
          if (res.status) setTimeout(() => location.reload(), 1800);
>>>>>>> origin/main
        });
      }
    });
  });

  // Cancel campaign
  $(document).on('click', '.cancel-campaign', function() {
    const id = $(this).data('id');
    Swal.fire({
      title: 'Cancel Campaign?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#ef4444',
      confirmButtonText: 'Yes, Cancel',
    }).then((result) => {
      if (result.isConfirmed) {
        $.post(baseUrl + 'notification-manager/campaigns/cancel', { id: id }, function(res) {
          if (res.status) location.reload();
          else Swal.fire('Error', res.message, 'error');
        });
      }
    });
  });

  // Auto-open if action=create in URL
  if (new URLSearchParams(window.location.search).get('action') === 'create') {
    $('#btnNewCampaign').click();
  }
});
</script>

<?= $this->endSection() ?>
