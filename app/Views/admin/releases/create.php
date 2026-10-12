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
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-1">
          <li class="breadcrumb-item"><a href="<?= base_url('releases') ?>">Release Management</a></li>
          <li class="breadcrumb-item active">New Version</li>
        </ol>
      </nav>
      <h4 class="fw-bold mb-0"><i class="bi bi-plus-circle me-2"></i>Create New Version</h4>
    </div>
  </div>

  <!-- Flash Messages -->
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="bi bi-exclamation-triangle me-1"></i> <?= session()->getFlashdata('error') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <div class="row">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <form method="post" action="<?= base_url('releases/store') ?>" id="createVersionForm">
            <?= csrf_field() ?>

            <!-- Version Number & Build -->
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Version Number <span class="text-danger">*</span></label>
                <input type="text" name="version_number" class="form-control" placeholder="e.g. 2.4.0" value="<?= old('version_number') ?>" required>
                <div class="form-text">Semantic versioning: major.minor.patch</div>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Build Number</label>
                <input type="text" name="build_number" class="form-control" placeholder="e.g. 240012" value="<?= old('build_number') ?>">
                <div class="form-text">Android/iOS build identifier</div>
              </div>
            </div>

            <!-- Platform & Type -->
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Platform <span class="text-danger">*</span></label>
                <select name="platform" class="form-select" required>
                  <option value="both" <?= old('platform') === 'both' ? 'selected' : '' ?>>📱 Both (Android + iOS)</option>
                  <option value="android" <?= old('platform') === 'android' ? 'selected' : '' ?>>🤖 Android</option>
                  <option value="ios" <?= old('platform') === 'ios' ? 'selected' : '' ?>>🍎 iOS</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Release Type <span class="text-danger">*</span></label>
                <select name="release_type" class="form-select" required>
                  <option value="major" <?= old('release_type') === 'major' ? 'selected' : '' ?>>🔴 Major</option>
                  <option value="minor" <?= old('release_type') === 'minor' || !old('release_type') ? 'selected' : '' ?>>🟡 Minor</option>
                  <option value="bugfix" <?= old('release_type') === 'bugfix' ? 'selected' : '' ?>>🟢 Bug Fix</option>
                  <option value="hotfix" <?= old('release_type') === 'hotfix' ? 'selected' : '' ?>>🔥 Hotfix</option>
                </select>
              </div>
            </div>

            <!-- Previous Version & Planned Date -->
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Previous Version</label>
                <select name="previous_version_id" class="form-select">
                  <option value="">— None —</option>
                  <?php foreach ($previousVersions as $pv): ?>
                    <option value="<?= $pv['id'] ?>" <?= old('previous_version_id') == $pv['id'] ? 'selected' : '' ?>>
                      <?= esc($pv['version_number']) ?> (<?= ucfirst($pv['status']) ?>)
                    </option>
                  <?php endforeach; ?>
                </select>
                <div class="form-text">Select the current production version</div>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Target Release Date</label>
                <input type="datetime-local" name="planned_release_at" class="form-control" value="<?= old('planned_release_at') ?>">
              </div>
            </div>

            <!-- Monitoring Period -->
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Post-Go-Live Monitoring Period</label>
                <select name="monitoring_period" class="form-select">
                  <option value="30min">30 Minutes</option>
                  <option value="1hr" selected>1 Hour</option>
                  <option value="4hr">4 Hours</option>
                  <option value="24hr">24 Hours</option>
                </select>
              </div>
            </div>

            <!-- Release Notes -->
            <div class="mb-4">
              <label class="form-label fw-semibold">Release Notes</label>
              <textarea name="release_notes" class="form-control" rows="5" placeholder="Summary of this release visible to Admin/QA..."><?= old('release_notes') ?></textarea>
            </div>

            <!-- Submit -->
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-plus-circle me-1"></i> Create Version
              </button>
              <a href="<?= base_url('releases') ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Right Panel: Info -->
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
          <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-1"></i> What happens next?</h6>
          <div class="d-flex flex-column gap-2">
            <div class="d-flex gap-2 align-items-start">
              <span class="badge bg-secondary rounded-circle" style="width:24px;height:24px;display:flex;align-items:center;justify-content:center">1</span>
              <small>Version is created in <strong>Draft</strong> status</small>
            </div>
            <div class="d-flex gap-2 align-items-start">
              <span class="badge bg-info rounded-circle" style="width:24px;height:24px;display:flex;align-items:center;justify-content:center">2</span>
              <small>Add updates, tickets, and changes</small>
            </div>
            <div class="d-flex gap-2 align-items-start">
              <span class="badge bg-primary rounded-circle" style="width:24px;height:24px;display:flex;align-items:center;justify-content:center">3</span>
              <small>Move to <strong>Testing</strong> for QA</small>
            </div>
            <div class="d-flex gap-2 align-items-start">
              <span class="badge bg-success rounded-circle" style="width:24px;height:24px;display:flex;align-items:center;justify-content:center">4</span>
              <small>Get approvals → backup → go-live</small>
            </div>
          </div>
        </div>
      </div>

      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h6 class="fw-bold mb-3"><i class="bi bi-diagram-3 me-1"></i> Release Workflow</h6>
          <div class="d-flex flex-column gap-1">
            <?php
              $workflow = ['Draft', 'Testing', 'QA Approved', 'Backup', 'Management Approved', 'Scheduled', 'Live', 'Monitoring', 'Successful'];
              foreach ($workflow as $i => $step):
            ?>
              <div class="d-flex align-items-center gap-2">
                <i class="bi bi-arrow-right-circle text-muted" style="font-size:0.7rem"></i>
                <small class="<?= $i === 0 ? 'fw-semibold' : 'text-muted' ?>"><?= $step ?></small>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</main>
<?php echo $this->include('admin/footer') ?>
<?php echo $this->endSection() ?>
