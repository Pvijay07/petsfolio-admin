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
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h4 class="fw-bold mb-1">Notification Templates</h4>
            <p class="text-muted mb-0">Manage message templates for all notification events</p>
          </div>
          <button type="button" class="btn btn-primary rounded-pill px-4" id="btnNewTemplate">
            <i class="bi bi-plus-circle me-1"></i> New Template
          </button>
        </div>
      </div>
    </div>

    <!-- Templates Table -->
    <div class="row">
      <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-body p-4">
            <div class="table-responsive">
              <table id="templatesTable" class="table table-hover align-middle mb-0" data-toggle="data-table">
                <thead class="bg-light">
                  <tr>
                    <th class="border-0 rounded-start py-3 ps-3">#</th>
                    <th class="border-0 py-3">Name</th>
                    <th class="border-0 py-3">Event Key</th>
                    <th class="border-0 py-3">Title</th>
                    <th class="border-0 py-3">Channel</th>
                    <th class="border-0 py-3">Type</th>
                    <th class="border-0 py-3">Status</th>
                    <th class="border-0 rounded-end py-3">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 1; foreach (($templates ?? []) as $template): ?>
                    <tr>
                      <td class="ps-3"><?= $i++ ?></td>
                      <td>
                        <div class="fw-semibold"><?= esc($template->name ?: $template->title) ?></div>
                      </td>
                      <td>
                        <?php if ($template->event_key): ?>
                          <code class="small bg-light px-2 py-1 rounded"><?= esc($template->event_key) ?></code>
                        <?php else: ?>
                          <span class="text-muted">—</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <span title="<?= esc($template->title) ?>"><?= esc(mb_substr($template->title, 0, 35)) ?><?= mb_strlen($template->title) > 35 ? '...' : '' ?></span>
                      </td>
                      <td>
                        <?php
                        $channelBadges = [
                          'push' => ['bi-phone', 'primary'],
                          'sms' => ['bi-chat-dots', 'success'],
                          'email' => ['bi-envelope', 'info'],
                          'whatsapp' => ['bi-whatsapp', 'success'],
                          'all' => ['bi-broadcast', 'dark'],
                        ];
                        $ch = $channelBadges[$template->channel ?? 'push'] ?? ['bi-bell', 'secondary'];
                        ?>
                        <span class="badge bg-<?= $ch[1] ?> bg-opacity-10 text-<?= $ch[1] ?> rounded-pill">
                          <i class="bi <?= $ch[0] ?> me-1"></i><?= ucfirst($template->channel ?? 'push') ?>
                        </span>
                      </td>
                      <td><span class="text-muted small"><?= ucfirst($template->type ?? '—') ?></span></td>
                      <td>
                        <div class="form-check form-switch">
                          <input type="checkbox" class="form-check-input toggle-template-status"
                            data-id="<?= $template->id ?>" <?= $template->status == 1 ? 'checked' : '' ?>>
                        </div>
                      </td>
                      <td>
                        <div class="d-flex gap-2">
                          <button class="btn btn-sm btn-outline-primary rounded-pill edit-template"
                            data-id="<?= $template->id ?>"
                            data-name="<?= esc($template->name ?? '') ?>"
                            data-event_key="<?= esc($template->event_key ?? '') ?>"
                            data-title="<?= esc($template->title) ?>"
                            data-body="<?= esc($template->body ?? $template->content ?? '') ?>"
                            data-type="<?= esc($template->type ?? '') ?>"
                            data-channel="<?= esc($template->channel ?? 'push') ?>"
                            data-variables="<?= esc($template->variables ?? '') ?>"
                            data-status="<?= $template->status ?>">
                            <i class="bi bi-pencil"></i>
                          </button>
                          <button class="btn btn-sm btn-outline-danger rounded-pill delete-template" data-id="<?= $template->id ?>">
                            <i class="bi bi-trash"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Template Form Offcanvas -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="templateOffcanvas" style="width:580px;">
    <div class="offcanvas-header border-bottom px-4 py-3">
      <h5 class="offcanvas-title fw-bold" id="templateOffcanvasTitle">New Template</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body px-4 py-3">
      <form id="templateForm">
        <input type="hidden" name="id" id="tpl_id">

        <div class="mb-3">
          <label class="form-label fw-semibold">Template Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="name" id="tpl_name" placeholder="e.g. Booking Confirmed" required>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold">Event Key</label>
            <select class="form-select" name="event_key" id="tpl_event_key">
              <option value="">— Select Event —</option>
              <?php foreach (($eventKeys ?? []) as $key => $label): ?>
                <option value="<?= $key ?>"><?= esc($label) ?> (<?= $key ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Channel</label>
            <select class="form-select" name="channel" id="tpl_channel">
              <option value="push">Push</option>
              <option value="sms">SMS</option>
              <option value="email">Email</option>
              <option value="whatsapp">WhatsApp</option>
              <option value="all">All Channels</option>
            </select>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Type</label>
          <input type="text" class="form-control" name="type" id="tpl_type" placeholder="e.g. booking, promo">
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="title" id="tpl_title" placeholder="Notification title — use {{variable}} for dynamic values" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Body <span class="text-danger">*</span></label>
          <textarea class="form-control" name="body" id="tpl_body" rows="4" placeholder="Notification body — use {{variable}} for dynamic values" required></textarea>
        </div>

        <!-- Available Variables -->
        <div class="mb-3" id="variablesHint" style="display:none;">
          <label class="form-label fw-semibold text-primary small">Available Variables</label>
          <div id="variablesList" class="d-flex flex-wrap gap-1"></div>
        </div>

        <!-- Preview -->
        <div class="mb-3 p-3 rounded-3" style="background:#f8f9ff; border: 1px dashed #c7d2fe;">
          <label class="form-label fw-semibold text-primary small mb-2"><i class="bi bi-eye me-1"></i> Live Preview</label>
          <div class="fw-semibold" id="previewTitle" style="color:#1e1b4b;">—</div>
          <div class="text-muted small mt-1" id="previewBody">—</div>
        </div>

        <div class="mb-3 d-flex align-items-center">
          <label class="form-label fw-semibold me-3 mb-0">Status</label>
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="status" id="tpl_status" checked>
            <label class="form-check-label" for="tpl_status">Active</label>
          </div>
        </div>

        <input type="hidden" name="variables" id="tpl_variables">
        <input type="hidden" name="content" id="tpl_content">
      </form>
    </div>
    <div class="offcanvas-footer border-top p-3 px-4">
      <button type="button" class="btn btn-primary px-4" id="btnSaveTemplate">
        <i class="bi bi-check-lg me-1"></i> Save Template
      </button>
      <button type="button" class="btn btn-light" data-bs-dismiss="offcanvas">Cancel</button>
    </div>
  </div>

  <?= $this->include('admin/footer') ?>
</main>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
  const baseUrl = '<?= base_url() ?>';

  // Event key variable mapping
  const eventVariables = <?= json_encode((new \App\Models\NotificationTemplateModel())->getEventKeys()) ?>;

  // New template
  $('#btnNewTemplate').click(function() {
    resetForm();
    $('#templateOffcanvasTitle').text('New Template');
    new bootstrap.Offcanvas(document.getElementById('templateOffcanvas')).show();
  });

  // Edit template
  $(document).on('click', '.edit-template', function() {
    resetForm();
    const data = $(this).data();
    $('#tpl_id').val(data.id);
    $('#tpl_name').val(data.name);
    $('#tpl_event_key').val(data.event_key);
    $('#tpl_title').val(data.title);
    $('#tpl_body').val(data.body);
    $('#tpl_type').val(data.type);
    $('#tpl_channel').val(data.channel);
    $('#tpl_variables').val(data.variables);
    $('#tpl_status').prop('checked', data.status == 1);
    $('#templateOffcanvasTitle').text('Edit Template');
    updatePreview();
    updateVariablesHint(data.event_key);
    new bootstrap.Offcanvas(document.getElementById('templateOffcanvas')).show();
  });

  // Save template
  $('#btnSaveTemplate').click(function() {
    const $btn = $(this);
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');

    // Copy body to content
    $('#tpl_content').val($('#tpl_body').val());

    $.ajax({
      url: baseUrl + 'notification-manager/templates/save',
      method: 'POST',
      data: $('#templateForm').serialize(),
      success: function(res) {
        if (res.status) {
          Swal.fire({ icon: 'success', title: 'Saved!', text: res.message, timer: 1500, showConfirmButton: false });
          setTimeout(() => location.reload(), 1500);
        } else {
          Swal.fire('Error', res.message || 'Failed to save', 'error');
        }
      },
      error: function() { Swal.fire('Error', 'Server error', 'error'); },
      complete: function() { $btn.prop('disabled', false).html('<i class="bi bi-check-lg me-1"></i> Save Template'); }
    });
  });

  // Delete template
  $(document).on('click', '.delete-template', function() {
    const id = $(this).data('id');
    Swal.fire({
      title: 'Delete Template?',
      text: 'This action cannot be undone.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#ef4444',
      confirmButtonText: 'Delete',
    }).then((result) => {
      if (result.isConfirmed) {
        $.post(baseUrl + 'notification-manager/templates/delete', { id: id }, function(res) {
          if (res.status) { location.reload(); }
          else { Swal.fire('Error', res.message, 'error'); }
        });
      }
    });
  });

  // Toggle status
  $(document).on('change', '.toggle-template-status', function() {
    const id = $(this).data('id');
    const status = $(this).is(':checked') ? 1 : 0;
    $.post(baseUrl + 'notification-manager/templates/save', { id: id, status: status });
  });

  // Event key change — show available variables
  $('#tpl_event_key').change(function() {
    updateVariablesHint($(this).val());
  });

  // Live preview
  $('#tpl_title, #tpl_body').on('input', function() { updatePreview(); });

  function updatePreview() {
    const title = $('#tpl_title').val() || '—';
    const body = $('#tpl_body').val() || '—';

    // Replace variables with sample values
    const samples = {
      user_name: 'John Doe', provider_name: 'Rahul Kumar', pet_name: 'Buddy',
      service_name: 'Dog Walking', amount: '₹499', booking_id: 'BK-12345',
      app_name: 'Petsfolio', service_date: '<?= date('d M Y') ?>', service_time: '10:00 AM',
      coupon_code: 'PETS20', discount_percent: '20%', points: '50',
    };

    let previewTitle = title, previewBody = body;
    for (const [k, v] of Object.entries(samples)) {
      previewTitle = previewTitle.replace(new RegExp('\\{\\{' + k + '\\}\\}', 'g'), '<strong style="color:#6366f1">' + v + '</strong>');
      previewBody = previewBody.replace(new RegExp('\\{\\{' + k + '\\}\\}', 'g'), '<strong style="color:#6366f1">' + v + '</strong>');
    }

    $('#previewTitle').html(previewTitle);
    $('#previewBody').html(previewBody);
  }

  function updateVariablesHint(eventKey) {
    if (!eventKey) { $('#variablesHint').hide(); return; }

    $.get(baseUrl + 'notification-manager/templates/variables?event_key=' + eventKey, function(res) {
      if (res.status && res.variables) {
        let html = '';
        res.variables.forEach(v => {
          html += '<span class="badge bg-light text-dark border cursor-pointer variable-badge" style="cursor:pointer" data-var="' + v + '">{{' + v + '}}</span>';
        });
        $('#variablesList').html(html);
        $('#variablesHint').show();
      }
    });
  }

  // Click variable to insert into focused field
  $(document).on('click', '.variable-badge', function() {
    const varText = '{{' + $(this).data('var') + '}}';
    const $focused = $('#tpl_body:focus').length ? $('#tpl_body') : $('#tpl_title');
    const pos = $focused[0].selectionStart || $focused.val().length;
    const val = $focused.val();
    $focused.val(val.slice(0, pos) + varText + val.slice(pos));
    $focused.trigger('input');
  });

  function resetForm() {
    $('#templateForm')[0].reset();
    $('#tpl_id').val('');
    $('#tpl_status').prop('checked', true);
    $('#variablesHint').hide();
    $('#previewTitle').text('—');
    $('#previewBody').text('—');
  }
});
</script>

<?= $this->endSection() ?>
