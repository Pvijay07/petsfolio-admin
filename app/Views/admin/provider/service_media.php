<?= $this->extend('admin/includes/header'); ?>

<?= $this->section('content'); ?>

<style>
  .media-card {
    border-radius: 16px;
    transition: all 0.25s ease-in-out;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
  }

  .media-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.15);
  }

  .media-preview-container {
    position: relative;
    width: 100%;
    padding-top: 66.66%; /* 3:2 ratio */
    background-color: #0f172a;
    overflow: hidden;
  }

  .media-preview-content {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .media-type-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: rgba(15, 23, 42, 0.75);
    backdrop-filter: blur(4px);
    color: #fff;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 20px;
    z-index: 2;
  }

  .status-badge {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 5px 12px;
    border-radius: 20px;
    display: inline-block;
  }

  .status-pending {
    background-color: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
  }

  .status-approved {
    background-color: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
  }

  .status-rejected {
    background-color: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
  }

  .stat-widget {
    background: #ffffff;
    border-radius: 16px;
    padding: 20px;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
  }

  .stat-widget:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  }

  .select-checkbox {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 3;
    width: 22px;
    height: 22px;
    cursor: pointer;
  }

  .filter-tab {
    padding: 8px 18px;
    border-radius: 30px;
    font-weight: 600;
    font-size: 0.875rem;
    text-decoration: none;
    color: #64748b;
    background: #f1f5f9;
    transition: all 0.2s ease;
    margin-right: 6px;
    display: inline-block;
  }

  .filter-tab.active, .filter-tab:hover {
    background: #3b82f6;
    color: #ffffff;
  }

  /* Premium Pagination Styling */
  .premium-pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0.5rem 0;
  }

  .premium-pagination ul.pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 6px;
    flex-wrap: wrap;
  }

  .premium-pagination ul.pagination li {
    display: inline-block;
    margin: 0;
    padding: 0;
  }

  .premium-pagination ul.pagination li a,
  .premium-pagination ul.pagination li span {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 12px;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    color: #3b82f6;
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
  }

  .premium-pagination ul.pagination li a:hover {
    background-color: #3b82f6;
    color: #ffffff;
    border-color: #3b82f6;
    box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
  }

  .premium-pagination ul.pagination li.active a,
  .premium-pagination ul.pagination li.active span {
    background: #3b82f6 !important;
    color: #ffffff !important;
    border-color: #3b82f6 !important;
    box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3) !important;
    pointer-events: none;
  }

  .premium-pagination ul.pagination li.disabled a,
  .premium-pagination ul.pagination li.disabled span,
  .premium-pagination ul.pagination li:not([class]) span {
    color: #94a3b8;
    background-color: #f8fafc;
    border-color: #e2e8f0;
    cursor: not-allowed;
    pointer-events: none;
  }
</style>

<div class="container-fluid py-4">

  <!-- Header & Title -->
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
      <h2 class="h3 font-weight-bold text-dark mb-1">Service Media Approval</h2>
      <p class="text-muted mb-0">Review, approve, or reject Service Provider photos & recording videos sent for client display.</p>
    </div>
  </div>

  <!-- Summary Cards -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="stat-widget d-flex align-items-center">
        <div class="rounded-circle p-3 mr-3" style="background: #fef3c7; color: #d97706;">
          <i class="fas fa-clock fa-2x"></i>
        </div>
        <div>
          <div class="text-muted small font-weight-bold">Pending Approval</div>
          <div class="h3 font-weight-bold text-dark mb-0"><?= number_format($counts['pending'] ?? 0); ?></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="stat-widget d-flex align-items-center">
        <div class="rounded-circle p-3 mr-3" style="background: #d1fae5; color: #059669;">
          <i class="fas fa-check-circle fa-2x"></i>
        </div>
        <div>
          <div class="text-muted small font-weight-bold">Approved</div>
          <div class="h3 font-weight-bold text-dark mb-0"><?= number_format($counts['approved'] ?? 0); ?></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="stat-widget d-flex align-items-center">
        <div class="rounded-circle p-3 mr-3" style="background: #fee2e2; color: #dc2626;">
          <i class="fas fa-times-circle fa-2x"></i>
        </div>
        <div>
          <div class="text-muted small font-weight-bold">Rejected</div>
          <div class="h3 font-weight-bold text-dark mb-0"><?= number_format($counts['rejected'] ?? 0); ?></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="stat-widget d-flex align-items-center">
        <div class="rounded-circle p-3 mr-3" style="background: #e0f2fe; color: #0284c7;">
          <i class="fas fa-photo-video fa-2x"></i>
        </div>
        <div>
          <div class="text-muted small font-weight-bold">Total Media</div>
          <div class="h3 font-weight-bold text-dark mb-0"><?= number_format($counts['all'] ?? 0); ?></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Filters and Actions Toolbar -->
  <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-3">
      <form method="GET" action="<?= base_url('service-media-approval'); ?>" id="filterForm">
        <div class="row align-items-center g-3">
          
          <!-- Status Filter Tabs -->
          <div class="col-12 col-lg-6">
            <div class="d-flex flex-wrap">
              <a href="<?= base_url('service-media-approval?status=pending&media_type=' . esc($media_type) . '&search=' . esc($search) . '&limit=' . esc($per_page)); ?>" class="filter-tab <?= ($status === 'pending' || empty($status)) ? 'active' : ''; ?>">
                Pending (<?= $counts['pending'] ?? 0; ?>)
              </a>
              <a href="<?= base_url('service-media-approval?status=approved&media_type=' . esc($media_type) . '&search=' . esc($search) . '&limit=' . esc($per_page)); ?>" class="filter-tab <?= ($status === 'approved') ? 'active' : ''; ?>">
                Approved (<?= $counts['approved'] ?? 0; ?>)
              </a>
              <a href="<?= base_url('service-media-approval?status=rejected&media_type=' . esc($media_type) . '&search=' . esc($search) . '&limit=' . esc($per_page)); ?>" class="filter-tab <?= ($status === 'rejected') ? 'active' : ''; ?>">
                Rejected (<?= $counts['rejected'] ?? 0; ?>)
              </a>
              <a href="<?= base_url('service-media-approval?status=all&media_type=' . esc($media_type) . '&search=' . esc($search) . '&limit=' . esc($per_page)); ?>" class="filter-tab <?= ($status === 'all') ? 'active' : ''; ?>">
                All (<?= $counts['all'] ?? 0; ?>)
              </a>
            </div>
            <input type="hidden" name="status" value="<?= esc($status); ?>">
          </div>

          <!-- Type & Search Controls -->
          <div class="col-12 col-lg-6 d-flex flex-wrap justify-content-end gap-2">
            <select name="media_type" class="form-control w-auto rounded-pill" onchange="document.getElementById('filterForm').submit();">
              <option value="all" <?= ($media_type === 'all') ? 'selected' : ''; ?>>All Media Types</option>
              <option value="photo" <?= ($media_type === 'photo') ? 'selected' : ''; ?>>Photos Only</option>
              <option value="video" <?= ($media_type === 'video') ? 'selected' : ''; ?>>Recording Videos Only</option>
            </select>

            <div class="input-group w-auto">
              <input type="text" name="search" class="form-control rounded-pill-left" placeholder="Search provider or file..." value="<?= esc($search); ?>">
              <div class="input-group-append">
                <button class="btn btn-primary rounded-pill-right" type="submit">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
          </div>

        </div>
      </form>
    </div>
  </div>

  <!-- Bulk Action Bar -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <div class="custom-control custom-checkbox d-inline-block mr-3">
        <input type="checkbox" class="custom-control-input" id="selectAllCheckbox" onchange="toggleSelectAll(this)">
        <label class="custom-control-label font-weight-bold" for="selectAllCheckbox">Select All Items</label>
      </div>
      <span class="text-muted small" id="selectedCountText">(0 selected)</span>
    </div>

    <div>
      <button type="button" class="btn btn-success btn-sm rounded-pill px-3 mr-2" onclick="batchUpdateStatus('approved')">
        <i class="fas fa-check mr-1"></i> Approve Selected
      </button>
      <button type="button" class="btn btn-danger btn-sm rounded-pill px-3" onclick="batchUpdateStatus('rejected')">
        <i class="fas fa-times mr-1"></i> Reject Selected
      </button>
    </div>
  </div>

  <!-- Media Grid -->
  <?php if (!empty($media)): ?>
    <div class="row g-4">
      <?php foreach ($media as $item): ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4" id="media-card-<?= $item->id; ?>">
          <div class="media-card shadow-sm">

            <input type="checkbox" class="media-checkbox select-checkbox" value="<?= $item->id; ?>" onchange="updateSelectedCount()">

            <div class="media-preview-container">
              <span class="media-type-badge">
                <i class="fas <?= $item->is_video ? 'fa-video text-warning' : 'fa-image text-info'; ?> mr-1"></i>
                <?= $item->is_video ? 'Recording Video' : 'Service Photo'; ?>
              </span>

              <?php if ($item->is_video): ?>
                <video class="media-preview-content" controls preload="metadata">
                  <source src="<?= esc($item->file_url); ?>" type="video/mp4">
                  Your browser does not support video playback.
                </video>
              <?php else: ?>
                <a href="<?= esc($item->file_url); ?>" target="_blank" title="Click to view full photo">
                  <img src="<?= esc($item->file_url); ?>" class="media-preview-content" alt="Service Photo" loading="lazy">
                </a>
              <?php endif; ?>
            </div>

            <div class="card-body p-3 d-flex flex-column justify-content-between">
              <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="status-badge status-<?= esc($item->status); ?>">
                    <i class="fas <?= $item->status === 'approved' ? 'fa-check-circle' : ($item->status === 'rejected' ? 'fa-times-circle' : 'fa-clock'); ?> mr-1"></i>
                    <?= ucfirst(esc($item->status)); ?>
                  </span>
                  <small class="text-muted"><?= date('M d, Y', strtotime($item->created_at)); ?></small>
                </div>

                <h6 class="font-weight-bold text-dark mb-1 text-truncate" title="<?= esc($item->provider_name ?? 'Unknown Provider'); ?>">
                  <i class="fas fa-user-circle mr-1 text-primary"></i>
                  <?= esc($item->provider_name ?? 'Provider #' . $item->provider_id); ?>
                </h6>

                <?php if (!empty($item->provider_phone)): ?>
                  <p class="text-muted small mb-1">
                    <i class="fas fa-phone mr-1"></i> <?= esc($item->provider_phone); ?>
                  </p>
                <?php endif; ?>

                <?php if (!empty($item->service_name)): ?>
                  <span class="badge badge-light border text-dark font-weight-normal mb-2">
                    <i class="fas fa-paw mr-1"></i> <?= esc($item->service_name); ?>
                  </span>
                <?php endif; ?>
              </div>

              <div class="pt-3 border-top d-flex gap-2 justify-content-between">
                <button type="button" class="btn btn-outline-success btn-sm flex-fill rounded-pill" onclick="singleUpdateStatus(<?= $item->id; ?>, 'approved')">
                  <i class="fas fa-check mr-1"></i> Approve
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm flex-fill rounded-pill" onclick="singleUpdateStatus(<?= $item->id; ?>, 'rejected')">
                  <i class="fas fa-times mr-1"></i> Reject
                </button>
              </div>

            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination Footer -->
    <?php
    $start = ($total_items > 0) ? ($current_page - 1) * $per_page + 1 : 0;
    $end   = min($current_page * $per_page, $total_items);
    ?>
    <div class="px-4 py-3 border-top bg-light rounded-lg d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4">
      <div class="d-flex align-items-center gap-3">
        <span class="text-muted small">
          Showing <strong class="text-dark"><?= $start ?></strong> to <strong class="text-dark"><?= $end ?></strong> of <strong class="text-dark"><?= $total_items ?></strong> entries
        </span>
        <div class="d-flex align-items-center gap-2 ml-3">
          <label for="perPageSelect" class="text-muted small mb-0 text-nowrap">Show:</label>
          <select id="perPageSelect" class="form-control form-control-sm border bg-white rounded-pill" style="width: auto; padding-right: 1.5rem;" onchange="changeLimit(this.value)">
            <option value="12" <?= $per_page == 12 ? 'selected' : '' ?>>12</option>
            <option value="24" <?= $per_page == 24 ? 'selected' : '' ?>>24</option>
            <option value="48" <?= $per_page == 48 ? 'selected' : '' ?>>48</option>
            <option value="96" <?= $per_page == 96 ? 'selected' : '' ?>>96</option>
            <option value="500" <?= $per_page == 500 ? 'selected' : '' ?>>All</option>
          </select>
          <span class="text-muted small text-nowrap">rows</span>
        </div>
      </div>
      <?php if (isset($pager_links) && !empty($pager_links)): ?>
        <div class="premium-pagination mb-0">
          <?= $pager_links ?>
        </div>
      <?php endif; ?>
    </div>

  <?php else: ?>
    <div class="card border-0 shadow-sm p-5 text-center" style="border-radius: 16px;">
      <div class="my-4">
        <i class="fas fa-photo-video fa-4x text-muted mb-3 opacity-50"></i>
        <h4 class="text-dark font-weight-bold">No Service Media Found</h4>
        <p class="text-muted">No uploaded service photos or recording videos match the selected filters.</p>
      </div>
    </div>
  <?php endif; ?>

</div>

<script>
  function toggleSelectAll(master) {
    const checkboxes = document.querySelectorAll('.media-checkbox');
    checkboxes.forEach(cb => cb.checked = master.checked);
    updateSelectedCount();
  }

  function updateSelectedCount() {
    const selected = document.querySelectorAll('.media-checkbox:checked').length;
    document.getElementById('selectedCountText').innerText = `(${selected} selected)`;
  }

  function changeLimit(limit) {
    const url = new URL(window.location.href);
    url.searchParams.set('limit', limit);
    url.searchParams.set('page', 1);
    window.location.href = url.toString();
  }

  function singleUpdateStatus(id, status) {
    batchUpdateStatus(status, [id]);
  }

  function batchUpdateStatus(status, ids = null) {
    if (!ids) {
      const selected = Array.from(document.querySelectorAll('.media-checkbox:checked')).map(cb => cb.value);
      ids = selected;
    }

    if (ids.length === 0) {
      alert('Please select at least one media item.');
      return;
    }

    const actionText = status === 'approved' ? 'approve' : (status === 'rejected' ? 'reject' : 'update');
    if (!confirm(`Are you sure you want to ${actionText} ${ids.length} item(s)?`)) {
      return;
    }

    const formData = new FormData();
    ids.forEach(id => formData.append('media_ids[]', id));
    formData.append('status', status);

    fetch('<?= base_url('update-service-media-status'); ?>', {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.status) {
        window.location.reload();
      } else {
        alert(data.message || 'Error updating status.');
      }
    })
    .catch(err => {
      console.error(err);
      alert('An error occurred while communicating with the server.');
    });
  }
</script>

<?= $this->endSection(); ?>
