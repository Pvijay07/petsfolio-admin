<?= $this->extend('admin/includes/header'); ?>
<?= $this->section('content'); ?>
<main class="main-content">
  <div class="position-relative iq-banner">
    <?= $this->include('admin/nav_bar') ?>
  </div>

  <div class="container-fluid content-inner pb-0 px-4" id="page_layout">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1"><i class="bi bi-rocket-takeoff me-2"></i>Release Management</h4>
      <p class="text-muted mb-0">Manage version releases, approvals, and deployments</p>
    </div>
    <a href="<?= base_url('releases/create') ?>" class="btn btn-primary">
      <i class="bi bi-plus-circle me-1"></i> New Version
    </a>
  </div>

  <!-- Flash Messages -->
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle me-1"></i> <?= session()->getFlashdata('success') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="bi bi-exclamation-triangle me-1"></i> <?= session()->getFlashdata('error') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <!-- Stats Cards -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3 col-xl">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body text-center py-3">
          <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" style="width:48px;height:48px;background:rgba(108,117,125,0.1)">
            <i class="bi bi-file-earmark-text fs-4 text-secondary"></i>
          </div>
          <h3 class="fw-bold mb-0"><?= $stats['draft'] ?? 0 ?></h3>
          <small class="text-muted">Draft</small>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3 col-xl">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body text-center py-3">
          <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" style="width:48px;height:48px;background:rgba(13,202,240,0.1)">
            <i class="bi bi-clipboard-check fs-4 text-info"></i>
          </div>
          <h3 class="fw-bold mb-0"><?= $stats['testing'] ?? 0 ?></h3>
          <small class="text-muted">Testing</small>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3 col-xl">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body text-center py-3">
          <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" style="width:48px;height:48px;background:rgba(25,135,84,0.1)">
            <i class="bi bi-check-circle fs-4 text-success"></i>
          </div>
          <h3 class="fw-bold mb-0"><?= $stats['live'] ?? 0 ?></h3>
          <small class="text-muted">Live</small>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3 col-xl">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body text-center py-3">
          <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" style="width:48px;height:48px;background:rgba(25,135,84,0.15)">
            <i class="bi bi-trophy fs-4 text-success"></i>
          </div>
          <h3 class="fw-bold mb-0"><?= $stats['successful'] ?? 0 ?></h3>
          <small class="text-muted">Successful</small>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3 col-xl">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body text-center py-3">
          <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" style="width:48px;height:48px;background:rgba(220,53,69,0.1)">
            <i class="bi bi-arrow-counterclockwise fs-4 text-danger"></i>
          </div>
          <h3 class="fw-bold mb-0"><?= $stats['rolled_back'] ?? 0 ?></h3>
          <small class="text-muted">Rolled Back</small>
        </div>
      </div>
    </div>
  </div>

  <!-- Filters -->
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
      <form method="get" action="<?= base_url('releases') ?>" class="row g-2 align-items-end">
        <div class="col-md-3">
          <label class="form-label small text-muted">Search</label>
          <input type="text" name="search" class="form-control form-control-sm" placeholder="Version number or notes..." value="<?= esc($filters['search'] ?? '') ?>">
        </div>
        <div class="col-md-2">
          <label class="form-label small text-muted">Status</label>
          <select name="status" class="form-select form-select-sm">
            <option value="">All Status</option>
            <?php foreach ($statusLabels as $key => $label): ?>
              <option value="<?= $key ?>" <?= ($filters['status'] ?? '') === $key ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label small text-muted">Platform</label>
          <select name="platform" class="form-select form-select-sm">
            <option value="">All Platforms</option>
            <option value="android" <?= ($filters['platform'] ?? '') === 'android' ? 'selected' : '' ?>>Android</option>
            <option value="ios" <?= ($filters['platform'] ?? '') === 'ios' ? 'selected' : '' ?>>iOS</option>
            <option value="both" <?= ($filters['platform'] ?? '') === 'both' ? 'selected' : '' ?>>Both</option>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label small text-muted">Type</label>
          <select name="release_type" class="form-select form-select-sm">
            <option value="">All Types</option>
            <option value="major" <?= ($filters['release_type'] ?? '') === 'major' ? 'selected' : '' ?>>Major</option>
            <option value="minor" <?= ($filters['release_type'] ?? '') === 'minor' ? 'selected' : '' ?>>Minor</option>
            <option value="bugfix" <?= ($filters['release_type'] ?? '') === 'bugfix' ? 'selected' : '' ?>>Bug Fix</option>
            <option value="hotfix" <?= ($filters['release_type'] ?? '') === 'hotfix' ? 'selected' : '' ?>>Hotfix</option>
          </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
          <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-funnel me-1"></i>Filter</button>
          <a href="<?= base_url('releases') ?>" class="btn btn-outline-secondary btn-sm">Clear</a>
        </div>
      </form>
    </div>
  </div>

  <!-- Version List Table -->
  <div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-3">
      <h6 class="fw-bold mb-0">All Versions</h6>
      <span class="badge bg-primary rounded-pill"><?= count($versions) ?></span>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
          <thead class="table-light">
            <tr>
              <th class="px-3 py-2">Version</th>
              <th class="py-2">Platform</th>
              <th class="py-2">Type</th>
              <th class="py-2">Status</th>
              <th class="py-2">QA</th>
              <th class="py-2">Approval</th>
              <th class="py-2">Go-Live</th>
              <th class="py-2">Rollback</th>
              <th class="py-2">Created</th>
              <th class="py-2 text-end pe-3">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($versions)): ?>
              <tr>
                <td colspan="10" class="text-center py-5 text-muted">
                  <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                  No versions found. Create your first release version.
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($versions as $v): ?>
                <tr class="cursor-pointer" onclick="window.location='<?= base_url("releases/detail/{$v['id']}") ?>'">
                  <td class="px-3 fw-semibold">
                    <span class="text-primary"><?= esc($v['version_number']) ?></span>
                    <?php if ($v['build_number']): ?>
                      <br><small class="text-muted">Build: <?= esc($v['build_number']) ?></small>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php
                      $platformIcons = ['android' => '🤖', 'ios' => '🍎', 'both' => '📱'];
                      echo ($platformIcons[$v['platform']] ?? '📱') . ' ' . ucfirst($v['platform']);
                    ?>
                  </td>
                  <td><span class="badge bg-light text-dark border"><?= ucfirst($v['release_type']) ?></span></td>
                  <td><span class="badge bg-<?= $v['status_color'] ?>"><?= $v['status_label'] ?></span></td>
                  <td>
                    <div class="d-flex align-items-center gap-1">
                      <div class="progress" style="width:60px;height:6px;">
                        <div class="progress-bar bg-<?= $v['qa_progress'] >= 100 ? 'success' : ($v['qa_progress'] >= 50 ? 'warning' : 'danger') ?>" style="width:<?= $v['qa_progress'] ?>%"></div>
                      </div>
                      <small class="text-muted"><?= $v['qa_progress'] ?>%</small>
                    </div>
                  </td>
                  <td>
                    <?php
                      $as = $v['approval_summary'];
                      if ($as['total'] > 0) {
                          $pct = round(($as['approved'] / $as['total']) * 100);
                          echo $as['rejected'] > 0
                              ? '<span class="badge bg-danger">Rejected</span>'
                              : ($pct >= 100
                                  ? '<span class="badge bg-success">✅ Approved</span>'
                                  : "<span class='text-muted'>{$as['approved']}/{$as['total']}</span>");
                      } else {
                          echo '<span class="text-muted">—</span>';
                      }
                    ?>
                  </td>
                  <td>
                    <?php if ($v['planned_release_at']): ?>
                      <small><?= date('d-M', strtotime($v['planned_release_at'])) ?></small>
                    <?php else: ?>
                      <small class="text-muted">—</small>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if ($v['previous_version_number']): ?>
                      <small class="text-muted"><?= esc($v['previous_version_number']) ?></small>
                    <?php else: ?>
                      <small class="text-muted">—</small>
                    <?php endif; ?>
                  </td>
                  <td><small class="text-muted"><?= date('d-M-Y', strtotime($v['created_at'])) ?></small></td>
                  <td class="text-end pe-3">
                    <a href="<?= base_url("releases/detail/{$v['id']}") ?>" class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation()">
                      <i class="bi bi-eye"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Recent Activity -->
  <?php if (!empty($recentActivity)): ?>
  <div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-transparent border-bottom py-3">
      <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-1"></i> Recent Activity</h6>
    </div>
    <div class="card-body p-0">
      <div class="list-group list-group-flush">
        <?php foreach (array_slice($recentActivity, 0, 10) as $log): ?>
          <div class="list-group-item d-flex justify-content-between align-items-start py-2 px-3">
            <div>
              <small class="fw-semibold text-primary">v<?= esc($log['version_number'] ?? '') ?></small>
              <span class="mx-1">·</span>
              <small><?= esc($log['description'] ?? $log['action']) ?></small>
              <span class="mx-1">·</span>
              <small class="text-muted"><?= esc($log['user_name'] ?? 'System') ?></small>
            </div>
            <small class="text-muted text-nowrap"><?= date('d-M H:i', strtotime($log['created_at'])) ?></small>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>
</main>
<style>
  .cursor-pointer { cursor: pointer; }
  .cursor-pointer:hover { background-color: rgba(0,0,0,0.02); }
</style>

<?php echo $this->include('admin/footer') ?>
<?php echo $this->endSection() ?>