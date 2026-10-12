<?php echo $this->extend('admin/includes/header'); ?>

<?php echo $this->section('content'); ?>

<?php
/**
 * Variable fallbacks for view templates
 */
$active_version = $active_version ?? null;
$versions       = $versions ?? [];
$live_version   = $live_version ?? null;
$categories     = $categories ?? [];
$employees      = $employees ?? [];
$active_tab     = $active_tab ?? 'details';
?>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?php echo $this->include('admin/nav_bar') ?>
  </div>

  <div class="container-fluid px-4 content-inner pb-5" id="page_layout">

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-check-circle-fill fs-5 me-2"></i>
        <div><?= session()->getFlashdata('success') ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
        <div><?= session()->getFlashdata('error') ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('warning')): ?>
      <div class="alert alert-warning alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-exclamation-circle-fill fs-5 me-2"></i>
        <div><?= session()->getFlashdata('warning') ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <!-- Main Header Banner -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="rounded-4 shadow-sm text-white position-relative overflow-hidden p-4"
          style="background: linear-gradient(135deg, #0d424d 0%, #165b69 100%);">
          <div class="row align-items-center position-relative z-1">
            <div class="col-lg-7 mb-3 mb-lg-0">
              <div class="d-flex align-items-center gap-3 mb-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                  style="width: 54px; height: 54px; background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.25);">
                  <i class="bi bi-rocket-takeoff-fill fs-2 text-warning"></i>
                </div>
                <div>
                  <h3 class="fw-bold mb-1 text-white">Version Release Management</h3>
                  <p class="text-white-75 mb-0 small">
                    End-to-end release lifecycle, developer & QA checklists, backup verification, and rollback control
                  </p>
                </div>
              </div>

              <!-- Quick Stats Pills -->
              <div class="d-flex flex-wrap gap-2">
                <div class="d-flex align-items-center rounded-pill px-3 py-1"
                  style="background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255, 255, 255, 0.2); font-size: 0.85rem;">
                  <i class="bi bi-broadcast text-success me-2"></i>
                  <span>Live Production: <strong class="text-white">v<?= esc($live_version['version_number'] ?? '2.4.0') ?></strong></span>
                </div>

                <?php if ($active_version): ?>
                  <div class="d-flex align-items-center rounded-pill px-3 py-1"
                    style="background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255, 255, 255, 0.2); font-size: 0.85rem;">
                    <i class="bi bi-tag-fill text-warning me-2"></i>
                    <span>Selected: <strong class="text-warning">v<?= esc($active_version['version_number']) ?></strong> (Build <?= esc($active_version['build_number'] ?? 'N/A') ?>)</span>
                  </div>

                  <div class="d-flex align-items-center rounded-pill px-3 py-1"
                    style="background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255, 255, 255, 0.2); font-size: 0.85rem;">
                    <i class="bi bi-activity text-info me-2"></i>
                    <span>Status: <strong class="text-uppercase text-white"><?= str_replace('_', ' ', esc($active_version['status'])) ?></strong></span>
                  </div>
                <?php endif; ?>

                <div class="d-flex align-items-center rounded-pill px-3 py-1"
                  style="background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255, 255, 255, 0.2); font-size: 0.85rem;">
                  <i class="bi bi-collection-fill text-info me-2"></i>
                  <span>Total Releases: <strong class="text-white"><?= count($versions) ?></strong></span>
                </div>
              </div>
            </div>

            <!-- Header Action Buttons -->
            <div class="col-lg-5 text-lg-end">
              <div class="d-flex flex-wrap justify-content-lg-end gap-2">
                <!-- Switch Version Dropdown -->
                <?php if (!empty($versions)): ?>
                  <div class="dropdown">
                    <button class="btn btn-outline-light rounded-pill px-3 py-2 dropdown-toggle d-flex align-items-center gap-2"
                      type="button" id="versionSelectorDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                      style="border-color: rgba(255,255,255,0.4);">
                      <i class="bi bi-layers-fill"></i>
                      <span>Switch Version</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 p-2" aria-labelledby="versionSelectorDropdown" style="min-width: 260px;">
                      <li class="dropdown-header text-uppercase small text-muted fw-bold">Select Release Version</li>
                      <?php foreach ($versions as $v): ?>
                        <li>
                          <a class="dropdown-item rounded-2 py-2 d-flex justify-content-between align-items-center <?= ($active_version && $active_version['id'] == $v['id']) ? 'active' : '' ?>"
                            href="<?= base_url('version-releases?version_id=' . $v['id']) ?>">
                            <div>
                              <strong>v<?= esc($v['version_number']) ?></strong>
                              <span class="small text-muted d-block">Build <?= esc($v['build_number'] ?? 'N/A') ?> &bull; <?= strtoupper(esc($v['platform'])) ?></span>
                            </div>
                            <span class="badge rounded-pill <?= $v['status'] === 'live' ? 'bg-success' : ($v['status'] === 'rolled_back' ? 'bg-danger' : 'bg-secondary') ?>">
                              <?= esc($v['status']) ?>
                            </span>
                          </a>
                        </li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                <?php endif; ?>

                <!-- Create Version Button -->
                <button type="button" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2 shadow-sm d-flex align-items-center gap-2"
                  data-bs-toggle="modal" data-bs-target="#newVersionModal">
                  <i class="bi bi-plus-lg fs-6"></i>
                  <span>New Version Release</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php if (!$active_version): ?>
      <!-- Empty State -->
      <div class="card border-0 shadow-sm rounded-4 p-5 text-center my-4">
        <div class="py-5">
          <i class="bi bi-box-seam text-muted" style="font-size: 5rem;"></i>
          <h4 class="fw-bold mt-3">No Release Versions Found</h4>
          <p class="text-muted">Get started by creating your first version release for the Petsfolio platform.</p>
          <button type="button" class="btn btn-primary rounded-pill px-4 py-2 mt-2"
            data-bs-toggle="modal" data-bs-target="#newVersionModal">
            <i class="bi bi-plus-circle me-2"></i> Create Version 1.0.0
          </button>
        </div>
      </div>
    <?php else: ?>

      <!-- Release Workflow Stepper / Pipeline Ribbon -->
      <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="fw-bold text-dark small text-uppercase letter-spacing-1">
              <i class="bi bi-diagram-3-fill text-primary me-2"></i>Release Lifecycle Pipeline &bull; Current Stage:
              <span class="badge bg-primary text-uppercase px-2 py-1"><?= str_replace('_', ' ', esc($active_version['status'])) ?></span>
            </span>
            <div class="d-flex align-items-center gap-2">
              <span class="small text-muted">Overall Readiness:</span>
              <div class="progress" style="width: 140px; height: 8px;">
                <?php
                $totalAll = 0;
                $passedAll = 0;
                foreach ($active_version['checklists'] as $c) {
                  $totalAll += $c['stats']['total'];
                  $passedAll += $c['stats']['passed'];
                }
                $readiness = $totalAll > 0 ? round(($passedAll / $totalAll) * 100) : 0;
                ?>
                <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                  id="overallProgressBar"
                  role="progressbar"
                  style="width: <?= $readiness ?>%;"
                  aria-valuenow="<?= $readiness ?>" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <span class="small fw-bold text-dark" id="overallProgressText"><?= $readiness ?>%</span>
            </div>
          </div>

          <!-- Visual Stepper -->
          <?php
          $stages = [
            'draft'               => ['label' => '1. Draft', 'icon' => 'bi-pencil-square'],
            'testing'             => ['label' => '2. Testing', 'icon' => 'bi-bug'],
            'qa_approved'         => ['label' => '3. QA Approved', 'icon' => 'bi-patch-check'],
            'backup_completed'    => ['label' => '4. Backup Verified', 'icon' => 'bi-shield-check'],
            'management_approved' => ['label' => '5. Management OK', 'icon' => 'bi-person-check'],
            'live'                => ['label' => '6. Live Production', 'icon' => 'bi-broadcast'],
            'monitoring'          => ['label' => '7. Monitoring', 'icon' => 'bi-clock-history'],
            'successful'          => ['label' => '8. Successful', 'icon' => 'bi-award']
          ];
          $stageKeys = array_keys($stages);
          $currentStageIndex = array_search($active_version['status'], $stageKeys);
          if ($currentStageIndex === false) $currentStageIndex = 0;
          ?>
          <div class="position-relative mt-3 px-2">
            <div class="row text-center g-2">
              <?php $idx = 0;
              foreach ($stages as $key => $stage): ?>
                <?php
                $isDone = $idx < $currentStageIndex || $active_version['status'] === 'successful';
                $isCurrent = $active_version['status'] === $key;
                ?>
                <div class="col">
                  <div class="p-2 rounded-3 text-center border <?= $isCurrent ? 'bg-primary text-white border-primary shadow-sm' : ($isDone ? 'bg-light text-success border-success' : 'bg-light text-muted border-light') ?>">
                    <i class="bi <?= $stage['icon'] ?> fs-5 d-block mb-1 <?= $isCurrent ? 'text-white' : ($isDone ? 'text-success' : 'text-muted') ?>"></i>
                    <div class="fw-bold" style="font-size: 0.75rem; white-space: nowrap;"><?= $stage['label'] ?></div>
                  </div>
                </div>
              <?php $idx++;
              endforeach; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Navigation Tabs for the 10 Core Sections -->
      <ul class="nav nav-pills custom-pills mb-4 bg-white p-2 rounded-4 shadow-sm border" id="releaseTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link <?= ($active_tab === 'details') ? 'active' : '' ?> rounded-pill px-3 py-2"
            id="details-tab" data-bs-toggle="pill" data-bs-target="#tab-details" type="button" role="tab">
            <i class="bi bi-info-circle me-1"></i> Version Details
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link <?= ($active_tab === 'updates') ? 'active' : '' ?> rounded-pill px-3 py-2 position-relative"
            id="updates-tab" data-bs-toggle="pill" data-bs-target="#tab-updates" type="button" role="tab">
            <i class="bi bi-list-task me-1"></i> Updates (<?= count($active_version['updates']) ?>)
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link <?= ($active_tab === 'checklists') ? 'active' : '' ?> rounded-pill px-3 py-2 fw-bold"
            id="checklists-tab" data-bs-toggle="pill" data-bs-target="#tab-checklists" type="button" role="tab"
            style="color: #0d424d;">
            <i class="bi bi-check2-all text-success me-1"></i> Developer & QA Checklists
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link <?= ($active_tab === 'threads') ? 'active' : '' ?> rounded-pill px-3 py-2"
            id="threads-tab" data-bs-toggle="pill" data-bs-target="#tab-threads" type="button" role="tab">
            <i class="bi bi-diagram-2 me-1"></i> Version Threads & Diff
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link <?= ($active_tab === 'backup') ? 'active' : '' ?> rounded-pill px-3 py-2"
            id="backup-tab" data-bs-toggle="pill" data-bs-target="#tab-backup" type="button" role="tab">
            <i class="bi bi-shield-lock me-1"></i> Production Backup
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link <?= ($active_tab === 'approvals') ? 'active' : '' ?> rounded-pill px-3 py-2"
            id="approvals-tab" data-bs-toggle="pill" data-bs-target="#tab-approvals" type="button" role="tab">
            <i class="bi bi-patch-check me-1"></i> Approvals
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link <?= ($active_tab === 'email') ? 'active' : '' ?> rounded-pill px-3 py-2"
            id="email-tab" data-bs-toggle="pill" data-bs-target="#tab-email" type="button" role="tab">
            <i class="bi bi-envelope-check me-1"></i> Approval Mail
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link <?= ($active_tab === 'versions') ? 'active' : '' ?> rounded-pill px-3 py-2"
            id="versions-tab" data-bs-toggle="pill" data-bs-target="#tab-versions" type="button" role="tab">
            <i class="bi bi-collection me-1"></i> Version List
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link <?= ($active_tab === 'rollback') ? 'active' : '' ?> rounded-pill px-3 py-2 text-danger"
            id="rollback-tab" data-bs-toggle="pill" data-bs-target="#tab-rollback" type="button" role="tab">
            <i class="bi bi-arrow-counterclockwise me-1"></i> Rollback
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link <?= ($active_tab === 'audit') ? 'active' : '' ?> rounded-pill px-3 py-2"
            id="audit-tab" data-bs-toggle="pill" data-bs-target="#tab-audit" type="button" role="tab">
            <i class="bi bi-journal-text me-1"></i> Audit Trail
          </button>
        </li>
      </ul>

      <!-- Tab Content Area -->
      <div class="tab-content" id="releaseTabsContent">

        <!-- ================= SECTION 1: VERSION DETAILS ================= -->
        <div class="tab-pane fade <?= ($active_tab === 'details') ? 'show active' : '' ?>" id="tab-details" role="tabpanel">
          <div class="row g-4">
            <div class="col-lg-8">
              <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center p-3">
                  <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-file-earmark-code text-primary me-2"></i>Version Specifications
                  </h5>
                  <button class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#editVersionModal">
                    <i class="bi bi-pencil me-1"></i> Edit Details
                  </button>
                </div>
                <div class="card-body p-4">
                  <div class="row g-3">
                    <div class="col-md-4">
                      <label class="text-muted small text-uppercase fw-semibold">Version Number</label>
                      <h4 class="fw-bold text-dark mb-0">v<?= esc($active_version['version_number']) ?></h4>
                    </div>
                    <div class="col-md-4">
                      <label class="text-muted small text-uppercase fw-semibold">Build Number / Code</label>
                      <h5 class="fw-semibold text-dark mb-0">#<?= esc($active_version['build_number'] ?? 'None specified') ?></h5>
                    </div>
                    <div class="col-md-4">
                      <label class="text-muted small text-uppercase fw-semibold">Platform</label>
                      <div>
                        <?php if ($active_version['platform'] === 'android'): ?>
                          <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-6"><i class="bi bi-android2 me-1"></i> Android</span>
                        <?php elseif ($active_version['platform'] === 'ios'): ?>
                          <span class="badge bg-dark-subtle text-dark border border-dark-subtle px-2 py-1 fs-6"><i class="bi bi-apple me-1"></i> iOS</span>
                        <?php else: ?>
                          <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-6"><i class="bi bi-phone me-1"></i> Android & iOS (Both)</span>
                        <?php endif; ?>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <label class="text-muted small text-uppercase fw-semibold">Release Type</label>
                      <div>
                        <span class="badge bg-info text-white text-uppercase px-2 py-1">
                          <?= esc($active_version['release_type']) ?>
                        </span>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <label class="text-muted small text-uppercase fw-semibold">Release Status</label>
                      <div>
                        <span class="badge bg-primary text-uppercase px-2 py-1 fs-6">
                          <?= str_replace('_', ' ', esc($active_version['status'])) ?>
                        </span>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <label class="text-muted small text-uppercase fw-semibold">Monitoring Window</label>
                      <div class="fw-semibold text-dark">
                        <i class="bi bi-clock me-1 text-muted"></i> <?= esc($active_version['monitoring_period']) ?>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <label class="text-muted small text-uppercase fw-semibold">Planned Release Date</label>
                      <div class="fw-semibold text-dark">
                        <?= !empty($active_version['planned_release_at']) ? date('M d, Y - h:i A', strtotime($active_version['planned_release_at'])) : 'Not scheduled' ?>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label class="text-muted small text-uppercase fw-semibold">Actual Released Date</label>
                      <div class="fw-semibold text-dark">
                        <?= !empty($active_version['released_at']) ? date('M d, Y - h:i A', strtotime($active_version['released_at'])) : 'Not released yet' ?>
                      </div>
                    </div>

                    <div class="col-12 mt-4">
                      <label class="text-muted small text-uppercase fw-semibold">Release Notes / Changelog Summary</label>
                      <div class="p-3 rounded-3 bg-light border" style="white-space: pre-line; min-height: 100px; font-family: inherit;">
                        <?= !empty($active_version['release_notes']) ? esc($active_version['release_notes']) : 'No release notes entered yet.' ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Lifecycle Quick Actions Sidebar -->
            <div class="col-lg-4">
              <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-transparent border-bottom p-3">
                  <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-lightning-charge-fill text-warning me-2"></i>Stage Controls
                  </h6>
                </div>
                <div class="card-body p-3">
                  <form action="<?= base_url('version-releases/status-update/' . $active_version['id']) ?>" method="POST">
                    <?= csrf_field() ?>
                    <label class="form-label small text-muted text-uppercase fw-bold">Advance Release Stage</label>
                    <div class="mb-3">
                      <select name="status" class="form-select">
                        <option value="draft" <?= $active_version['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="testing" <?= $active_version['status'] === 'testing' ? 'selected' : '' ?>>Testing</option>
                        <option value="qa_approved" <?= $active_version['status'] === 'qa_approved' ? 'selected' : '' ?>>QA Approved</option>
                        <option value="backup_completed" <?= $active_version['status'] === 'backup_completed' ? 'selected' : '' ?>>Backup Completed</option>
                        <option value="management_approved" <?= $active_version['status'] === 'management_approved' ? 'selected' : '' ?>>Management Approved</option>
                        <option value="scheduled" <?= $active_version['status'] === 'scheduled' ? 'selected' : '' ?>>Scheduled</option>
                        <option value="releasing" <?= $active_version['status'] === 'releasing' ? 'selected' : '' ?>>Releasing (Deployment in Progress)</option>
                        <option value="live" <?= $active_version['status'] === 'live' ? 'selected' : '' ?>>Live (Released to Production)</option>
                        <option value="monitoring" <?= $active_version['status'] === 'monitoring' ? 'selected' : '' ?>>Monitoring (Post Release)</option>
                        <option value="successful" <?= $active_version['status'] === 'successful' ? 'selected' : '' ?>>Successful (Closed)</option>
                        <option value="rollback_requested" <?= $active_version['status'] === 'rollback_requested' ? 'selected' : '' ?>>Rollback Requested</option>
                        <option value="rolling_back" <?= $active_version['status'] === 'rolling_back' ? 'selected' : '' ?>>Rolling Back</option>
                        <option value="rolled_back" <?= $active_version['status'] === 'rolled_back' ? 'selected' : '' ?>>Rolled Back</option>
                      </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2">
                      <i class="bi bi-arrow-right-circle me-1"></i> Update Stage
                    </button>
                  </form>

                  <hr class="my-4">

                  <div class="d-flex flex-column gap-2">
                    <a href="<?= base_url('version-releases/email/preview/' . $active_version['id']) ?>" target="_blank"
                      class="btn btn-outline-dark rounded-pill py-2 text-start d-flex align-items-center justify-content-between">
                      <span><i class="bi bi-file-earmark-text text-primary me-2"></i>Preview Approval Email</span>
                      <i class="bi bi-box-arrow-up-right small"></i>
                    </a>

                    <?php if ($active_version['status'] === 'management_approved'): ?>
                      <form action="<?= base_url('version-releases/status-update/' . $active_version['id']) ?>" method="POST"
                        onsubmit="return confirm('Ready to make version <?= esc($active_version['version_number']) ?> LIVE in production?');">
                        <?= csrf_field() ?>
                        <input type="hidden" name="status" value="live">
                        <button type="submit" class="btn btn-success w-100 rounded-pill py-2 fw-bold shadow-sm">
                          <i class="bi bi-rocket-takeoff me-1"></i> Deploy & Go Live Now
                        </button>
                      </form>
                    <?php endif; ?>

                    <?php if (in_array($active_version['status'], ['live', 'monitoring'])): ?>
                      <button type="button" class="btn btn-outline-danger rounded-pill py-2 text-start d-flex align-items-center justify-content-between"
                        data-bs-toggle="modal" data-bs-target="#rollbackModal">
                        <span><i class="bi bi-exclamation-octagon-fill text-danger me-2"></i>Initiate Emergency Rollback</span>
                        <i class="bi bi-arrow-counterclockwise"></i>
                      </button>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ================= SECTION 2: UPDATES / CHANGELOG ================= -->
        <div class="tab-pane fade <?= ($active_tab === 'updates') ? 'show active' : '' ?>" id="tab-updates" role="tabpanel">
          <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center p-3">
              <div>
                <h5 class="fw-bold mb-0 text-dark">
                  <i class="bi bi-card-checklist text-primary me-2"></i>Updates in Version <?= esc($active_version['version_number']) ?>
                </h5>
                <small class="text-muted">Categorized breakdown of features, bug fixes, UI/UX, APIs, database & security</small>
              </div>
              <button class="btn btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#newUpdateModal">
                <i class="bi bi-plus-lg me-1"></i> Add Update Item
              </button>
            </div>
            <div class="card-body p-4">
              <!-- Category Summary Chips -->
              <div class="row g-3 mb-4">
                <?php foreach ($categories as $catKey => $catMeta): ?>
                  <?php $cnt = count($active_version['grouped_updates'][$catKey] ?? []); ?>
                  <div class="col-xl-3 col-md-4 col-sm-6">
                    <div class="p-3 rounded-3 border bg-light d-flex align-items-center justify-content-between">
                      <div class="d-flex align-items-center gap-2">
                        <i class="bi <?= $catMeta['icon'] ?> fs-4 <?= str_replace('bg-', 'text-', explode(' ', $catMeta['badge'])[0]) ?>"></i>
                        <span class="small fw-semibold text-dark"><?= $catMeta['label'] ?></span>
                      </div>
                      <span class="badge rounded-pill <?= $cnt > 0 ? $catMeta['badge'] : 'bg-secondary' ?> px-2 py-1">
                        <?= $cnt ?>
                      </span>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>

              <!-- List of Updates -->
              <?php if (empty($active_version['updates'])): ?>
                <div class="text-center py-5 text-muted">
                  <i class="bi bi-list-nested fs-1 d-block mb-2 text-secondary"></i>
                  No update items added to this version yet. Click <strong>"Add Update Item"</strong> to record new features and bug fixes.
                </div>
              <?php else: ?>
                <div class="table-responsive">
                  <table class="table table-hover align-middle border rounded-3 overflow-hidden">
                    <thead class="table-light">
                      <tr>
                        <th style="width: 18%;">Category</th>
                        <th style="width: 40%;">Title & Description</th>
                        <th>Ticket / Jira</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($active_version['updates'] as $upd): ?>
                        <?php $cMeta = $categories[$upd['category']] ?? ['label' => $upd['category'], 'badge' => 'bg-secondary']; ?>
                        <tr>
                          <td>
                            <span class="badge rounded-pill <?= $cMeta['badge'] ?> px-2 py-1">
                              <?= $cMeta['label'] ?>
                            </span>
                          </td>
                          <td>
                            <div class="fw-bold text-dark"><?= esc($upd['title']) ?></div>
                            <?php if (!empty($upd['description'])): ?>
                              <small class="text-muted d-block"><?= esc($upd['description']) ?></small>
                            <?php endif; ?>
                            <?php if (!empty($upd['comments'])): ?>
                              <small class="text-info d-block mt-1"><i class="bi bi-chat-left-dots me-1"></i><?= esc($upd['comments']) ?></small>
                            <?php endif; ?>
                          </td>
                          <td>
                            <?php if (!empty($upd['ticket_id'])): ?>
                              <span class="badge bg-light text-dark border px-2 py-1 font-monospace">
                                <?= esc($upd['ticket_id']) ?>
                              </span>
                            <?php else: ?>
                              <span class="text-muted small">-</span>
                            <?php endif; ?>
                          </td>
                          <td>
                            <?php if ($upd['status'] === 'completed' || $upd['status'] === 'verified'): ?>
                              <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Completed</span>
                            <?php elseif ($upd['status'] === 'in_progress'): ?>
                              <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">In Progress</span>
                            <?php else: ?>
                              <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">Pending</span>
                            <?php endif; ?>
                          </td>
                          <td class="text-end">
                            <form action="<?= base_url('version-releases/updates/delete/' . $upd['id']) ?>" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this update item?');">
                              <?= csrf_field() ?>
                              <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-1" style="width: 32px; height: 32px;" title="Delete">
                                <i class="bi bi-trash"></i>
                              </button>
                            </form>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- ================= SECTION 3: DEVELOPER & QA CHECKLISTS (PRIMARY) ================= -->
        <div class="tab-pane fade <?= ($active_tab === 'checklists') ? 'show active' : '' ?>" id="tab-checklists" role="tabpanel">
          <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-transparent border-bottom d-flex flex-wrap justify-content-between align-items-center p-3 gap-2">
              <div>
                <h5 class="fw-bold mb-0 text-dark">
                  <i class="bi bi-check2-circle text-success me-2"></i>Go-Live Developer & QA Checklists
                </h5>
                <small class="text-muted">
                  Interactive sign-offs for Frontend Developer, Backend Developer, and QA / Tester with auto-captured auditor & timestamp
                </small>
              </div>
              <div class="d-flex align-items-center gap-2">
                <form action="<?= base_url('version-releases/checklist/populate-defaults/' . $active_version['id']) ?>" method="POST">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-arrow-repeat me-1"></i> Reset / Re-seed Checklists
                  </button>
                </form>
              </div>
            </div>
            <div class="card-body p-4">

              <!-- Checklists Render -->
              <?php if (empty($active_version['checklists'])): ?>
                <div class="text-center py-5">
                  <p class="text-muted">Checklists have not been populated for this version yet.</p>
                  <form action="<?= base_url('version-releases/checklist/populate-defaults/' . $active_version['id']) ?>" method="POST">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                      Initialize Developer & QA Checklists
                    </button>
                  </form>
                </div>
              <?php else: ?>

                <div class="row g-4">
                  <?php foreach ($active_version['checklists'] as $chk): ?>
                    <div class="col-12">
                      <div class="card border rounded-4 shadow-none">
                        <div class="card-header bg-light border-bottom p-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                          <div class="d-flex align-items-center gap-2">
                            <?php
                            $badgeClass = 'bg-primary';
                            $icon = 'bi-code-slash';
                            if ($chk['section'] === 'application') {
                              $badgeClass = 'bg-info text-dark';
                              $icon = 'bi-phone';
                            } elseif ($chk['section'] === 'technical') {
                              $badgeClass = 'bg-dark text-white';
                              $icon = 'bi-server';
                            } elseif ($chk['section'] === 'qa') {
                              $badgeClass = 'bg-success text-white';
                              $icon = 'bi-shield-check';
                            }
                            ?>
                            <span class="badge <?= $badgeClass ?> rounded-pill px-3 py-1">
                              <i class="bi <?= $icon ?> me-1"></i> <?= esc($chk['section_label']) ?>
                            </span>
                          </div>

                          <div class="d-flex align-items-center gap-3">
                            <span class="small text-muted">Progress:</span>
                            <div class="progress" style="width: 120px; height: 6px;">
                              <div class="progress-bar bg-success" id="chkProgress-<?= $chk['id'] ?>"
                                style="width: <?= $chk['stats']['percentage'] ?>%;"></div>
                            </div>
                            <span class="small fw-bold text-dark" id="chkProgressText-<?= $chk['id'] ?>">
                              <?= $chk['stats']['passed'] ?>/<?= $chk['stats']['total'] ?> (<?= $chk['stats']['percentage'] ?>%)
                            </span>
                          </div>
                        </div>

                        <div class="card-body p-0">
                          <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                              <thead class="table-light small text-muted text-uppercase">
                                <tr>
                                  <th style="width: 45%;">Checklist Item</th>
                                  <th style="width: 25%;">Status Toggle</th>
                                  <th style="width: 30%;">Checked By & Timestamp</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php foreach ($chk['items'] as $item): ?>
                                  <tr id="item-row-<?= $item['id'] ?>" class="<?= $item['status'] === 'passed' ? 'table-success-subtle' : ($item['status'] === 'failed' ? 'table-danger-subtle' : '') ?>">
                                    <td>
                                      <div class="d-flex align-items-start gap-2">
                                        <i class="bi <?= $item['status'] === 'passed' ? 'bi-check-circle-fill text-success' : ($item['status'] === 'failed' ? 'bi-x-circle-fill text-danger' : 'bi-circle text-muted') ?> fs-5 mt-1" id="icon-<?= $item['id'] ?>"></i>
                                        <div>
                                          <div class="fw-semibold text-dark"><?= esc($item['item_text']) ?></div>
                                          <!-- Optional Comment -->
                                          <div class="small text-muted mt-1" id="comment-display-<?= $item['id'] ?>">
                                            <?php if (!empty($item['comment'])): ?>
                                              <i class="bi bi-chat-left-quote text-primary me-1"></i><?= esc($item['comment']) ?>
                                            <?php endif; ?>
                                          </div>
                                        </div>
                                      </div>
                                    </td>
                                    <td>
                                      <!-- Status Buttons: Pending / Completed / Failed -->
                                      <div class="btn-group btn-group-sm" role="group" aria-label="Item Status">
                                        <button type="button"
                                          class="btn <?= $item['status'] === 'pending' ? 'btn-secondary text-white' : 'btn-outline-secondary' ?>"
                                          onclick="updateItemStatus(<?= $item['id'] ?>, 'pending')">
                                          Pending
                                        </button>
                                        <button type="button"
                                          class="btn <?= $item['status'] === 'passed' ? 'btn-success text-white' : 'btn-outline-success' ?>"
                                          onclick="updateItemStatus(<?= $item['id'] ?>, 'completed')">
                                          Completed
                                        </button>
                                        <button type="button"
                                          class="btn <?= $item['status'] === 'failed' ? 'btn-danger text-white' : 'btn-outline-danger' ?>"
                                          onclick="updateItemStatus(<?= $item['id'] ?>, 'failed')">
                                          Failed
                                        </button>
                                      </div>
                                      <button type="button" class="btn btn-sm btn-link text-muted p-0 ms-2"
                                        title="Add comment" onclick="promptComment(<?= $item['id'] ?>)">
                                        <i class="bi bi-chat-text"></i>
                                      </button>
                                    </td>
                                    <td>
                                      <div id="meta-<?= $item['id'] ?>">
                                        <?php if ($item['status'] !== 'pending' && !empty($item['checker_name'])): ?>
                                          <span class="badge bg-light text-dark border px-2 py-1">
                                            <i class="bi bi-person-fill text-primary me-1"></i><?= esc($item['checker_name']) ?>
                                          </span>
                                          <small class="text-muted d-block mt-1">
                                            <i class="bi bi-clock me-1"></i><?= date('M d, Y h:i A', strtotime($item['checked_at'])) ?>
                                          </small>
                                        <?php else: ?>
                                          <span class="text-muted small italic">Not verified yet</span>
                                        <?php endif; ?>
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
                  <?php endforeach; ?>
                </div>

              <?php endif; ?>

            </div>
          </div>
        </div>

        <!-- ================= SECTION 4: VERSION THREADS & DIFF ================= -->
        <div class="tab-pane fade <?= ($active_tab === 'threads') ? 'show active' : '' ?>" id="tab-threads" role="tabpanel">
          <div class="row g-4">
            <div class="col-lg-7">
              <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-transparent border-bottom p-3">
                  <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-arrow-left-right text-primary me-2"></i>Version Comparison & Relationship
                  </h5>
                  <small class="text-muted">Previous Version &rarr; New Version Changelog Analysis</small>
                </div>
                <div class="card-body p-4">
                  <div class="p-3 rounded-3 bg-light border mb-4">
                    <div class="row align-items-center text-center">
                      <div class="col-5">
                        <small class="text-muted text-uppercase fw-bold">Previous Baseline</small>
                        <h4 class="fw-bold text-secondary mb-0">v<?= esc($active_version['previous_version']['version_number'] ?? '2.4.0') ?></h4>
                        <span class="badge bg-secondary-subtle text-secondary small">Stable Build</span>
                      </div>
                      <div class="col-2">
                        <i class="bi bi-arrow-right-circle-fill fs-2 text-primary"></i>
                      </div>
                      <div class="col-5">
                        <small class="text-muted text-uppercase fw-bold">Target Release</small>
                        <h4 class="fw-bold text-primary mb-0">v<?= esc($active_version['version_number']) ?></h4>
                        <span class="badge bg-primary-subtle text-primary small">Current Target</span>
                      </div>
                    </div>
                  </div>

                  <!-- What Changed Breakdown -->
                  <h6 class="fw-bold text-dark mb-3">Summary of Changes:</h6>
                  <div class="list-group list-group-flush mb-4">
                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                      <span><i class="bi bi-plus-circle text-success me-2"></i>New Features Added:</span>
                      <span class="badge bg-success rounded-pill"><?= count($active_version['grouped_updates']['new_features'] ?? []) ?></span>
                    </div>
                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                      <span><i class="bi bi-arrow-up-right-circle text-info me-2"></i>Enhancements:</span>
                      <span class="badge bg-info rounded-pill"><?= count($active_version['grouped_updates']['enhancements'] ?? []) ?></span>
                    </div>
                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                      <span><i class="bi bi-bug text-danger me-2"></i>Bugs Fixed:</span>
                      <span class="badge bg-danger rounded-pill"><?= count($active_version['grouped_updates']['bug_fixes'] ?? []) ?></span>
                    </div>
                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                      <span><i class="bi bi-shield-lock text-dark me-2"></i>Security & DB Changes:</span>
                      <span class="badge bg-dark rounded-pill"><?= count($active_version['grouped_updates']['security'] ?? []) + count($active_version['grouped_updates']['database'] ?? []) ?></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Threaded Comments / Discussions -->
            <div class="col-lg-5">
              <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-transparent border-bottom p-3">
                  <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-chat-square-text text-primary me-2"></i>Developer & QA Release Thread
                  </h6>
                </div>
                <div class="card-body p-3">
                  <!-- Comments Stream -->
                  <div class="comment-stream mb-3" style="max-height: 380px; overflow-y: auto;">
                    <?php if (empty($active_version['comments'])): ?>
                      <p class="text-muted text-center py-4 small">No comments in this version thread yet.</p>
                    <?php else: ?>
                      <?php foreach ($active_version['comments'] as $comm): ?>
                        <div class="p-2 mb-2 rounded-3 border bg-light">
                          <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge <?= $comm['comment_type'] === 'qa' ? 'bg-success' : ($comm['comment_type'] === 'developer' ? 'bg-primary' : 'bg-secondary') ?> rounded-pill" style="font-size: 0.65rem;">
                              <?= strtoupper(esc($comm['comment_type'])) ?>
                            </span>
                            <small class="text-muted" style="font-size: 0.7rem;">
                              <?= date('M d, h:i A', strtotime($comm['created_at'])) ?>
                            </small>
                          </div>
                          <div class="small text-dark fw-semibold"><?= esc($comm['author_name'] ?? 'Admin') ?></div>
                          <p class="small mb-0 text-secondary"><?= esc($comm['comment']) ?></p>
                        </div>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </div>

                  <!-- Post Comment Form -->
                  <form action="<?= base_url('version-releases/comments/add') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="version_id" value="<?= $active_version['id'] ?>">
                    <div class="mb-2">
                      <select name="comment_type" class="form-select form-select-sm">
                        <option value="developer">Developer Comment</option>
                        <option value="qa">QA / Tester Comment</option>
                        <option value="approval">Approval Comment</option>
                        <option value="general">General Note</option>
                      </select>
                    </div>
                    <div class="input-group">
                      <input type="text" name="comment" class="form-control form-control-sm" placeholder="Add comment to thread..." required>
                      <button class="btn btn-sm btn-primary" type="submit">Post</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ================= SECTION 5: PRODUCTION BACKUP ================= -->
        <div class="tab-pane fade <?= ($active_tab === 'backup') ? 'show active' : '' ?>" id="tab-backup" role="tabpanel">
          <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-transparent border-bottom p-3">
              <h5 class="fw-bold mb-0 text-dark">
                <i class="bi bi-shield-lock-fill text-warning me-2"></i>Production Backup & Recovery Information
              </h5>
              <small class="text-muted">Must be verified and recorded before advancing to Management Approval and Go-Live</small>
            </div>
            <div class="card-body p-4">
              <form action="<?= base_url('version-releases/backup/save') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="version_id" value="<?= $active_version['id'] ?>">

                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">Current Production Version <span class="text-danger">*</span></label>
                    <input type="text" name="current_prod_version" class="form-control"
                      value="<?= esc($active_version['backup']['current_prod_version'] ?? ($live_version['version_number'] ?? '2.4.0')) ?>" required>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">Current Production Build Number</label>
                    <input type="text" name="current_build_number" class="form-control"
                      value="<?= esc($active_version['backup']['current_build_number'] ?? ($live_version['build_number'] ?? '104')) ?>">
                  </div>

                  <div class="col-md-6">
                    <label class="form-label fw-semibold">Database Backup Filename / S3 Reference <span class="text-danger">*</span></label>
                    <input type="text" name="database_backup" class="form-control" placeholder="e.g. petsfolio_prod_2026_09_09_0200.sql.gz"
                      value="<?= esc($active_version['backup']['database_backup'] ?? '') ?>" required>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">API / Backend Git Commit / Tag</label>
                    <input type="text" name="api_backup" class="form-control" placeholder="e.g. tag: release-v2.4.0 or commit: 8e4f1a2"
                      value="<?= esc($active_version['backup']['api_backup'] ?? '') ?>">
                  </div>

                  <div class="col-md-6">
                    <label class="form-label fw-semibold">Config / Environment Backup</label>
                    <input type="text" name="config_backup" class="form-control" placeholder="e.g. s3://petsfolio-vault/config-prod-2.4.0.env"
                      value="<?= esc($active_version['backup']['config_backup'] ?? '') ?>">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">Previous APK / AAB Storage Reference</label>
                    <input type="text" name="apk_aab_reference" class="form-control" placeholder="e.g. builds/android/petsfolio-2.4.0.aab"
                      value="<?= esc($active_version['backup']['apk_aab_reference'] ?? '') ?>">
                  </div>

                  <div class="col-md-6">
                    <label class="form-label fw-semibold">Previous iOS IPA Storage Reference</label>
                    <input type="text" name="ipa_reference" class="form-control" placeholder="e.g. builds/ios/petsfolio-2.4.0.ipa"
                      value="<?= esc($active_version['backup']['ipa_reference'] ?? '') ?>">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">Rollback Target Version Number <span class="text-danger">*</span></label>
                    <input type="text" name="rollback_version" class="form-control"
                      value="<?= esc($active_version['backup']['rollback_version'] ?? ($live_version['version_number'] ?? '2.4.0')) ?>" required>
                  </div>

                  <div class="col-12">
                    <label class="form-label fw-semibold">Backup Notes & Verification Comments</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Confirmation notes regarding database restore test, APK availability, and rollback sanity..."><?= esc($active_version['backup']['notes'] ?? '') ?></textarea>
                  </div>

                  <div class="col-12 mt-4 text-end">
                    <button type="submit" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2">
                      <i class="bi bi-shield-check me-1"></i> Save & Verify Production Backup
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- ================= SECTION 6: APPROVALS ================= -->
        <div class="tab-pane fade <?= ($active_tab === 'approvals') ? 'show active' : '' ?>" id="tab-approvals" role="tabpanel">
          <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-transparent border-bottom p-3">
              <h5 class="fw-bold mb-0 text-dark">
                <i class="bi bi-patch-check-fill text-primary me-2"></i>Sign-off Approvals Matrix
              </h5>
              <small class="text-muted">Multi-stakeholder approval pipeline before final production release</small>
            </div>
            <div class="card-body p-4">
              <div class="row g-4">
                <?php
                $approvalRoles = [
                  'qa'              => ['title' => 'QA / Testing Lead', 'desc' => 'Confirms test execution, regression testing & blocker closure', 'icon' => 'bi-shield-check'],
                  'developer'       => ['title' => 'Technical Lead (Backend)', 'desc' => 'Verifies APIs, DB migrations & production configuration', 'icon' => 'bi-code-slash'],
                  'product'         => ['title' => 'Product Manager', 'desc' => 'Validates business requirements & user experience scope', 'icon' => 'bi-briefcase'],
                  'management'      => ['title' => 'Executive / Management', 'desc' => 'Final go-live sign-off for deployment release', 'icon' => 'bi-person-check-fill'],
                  'release_manager' => ['title' => 'Release Operations', 'desc' => 'Coordinates deployment schedule & monitoring team', 'icon' => 'bi-gear-wide-connected']
                ];
                ?>

                <?php foreach ($approvalRoles as $typeKey => $roleInfo): ?>
                  <?php
                  $rec = $active_version['approvals'][$typeKey] ?? null;
                  $status = $rec['status'] ?? 'pending';
                  ?>
                  <div class="col-md-6 col-lg-4">
                    <div class="card border rounded-4 shadow-none h-100 <?= $status === 'approved' ? 'border-success' : ($status === 'rejected' ? 'border-danger' : '') ?>">
                      <div class="card-body p-3 d-flex flex-column justify-content-between">
                        <div>
                          <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge rounded-pill <?= $status === 'approved' ? 'bg-success' : ($status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark') ?> text-uppercase">
                              <?= $status ?>
                            </span>
                            <i class="bi <?= $roleInfo['icon'] ?> fs-4 text-secondary"></i>
                          </div>
                          <h6 class="fw-bold text-dark mb-1"><?= $roleInfo['title'] ?></h6>
                          <p class="small text-muted mb-3"><?= $roleInfo['desc'] ?></p>

                          <?php if ($rec && !empty($rec['approver_name'])): ?>
                            <div class="small p-2 bg-light rounded-3 mb-3">
                              <div><strong>Sign-off by:</strong> <?= esc($rec['approver_name']) ?></div>
                              <div class="text-muted" style="font-size: 0.75rem;">
                                <?= date('M d, Y - h:i A', strtotime($rec['approved_at'])) ?>
                              </div>
                              <?php if (!empty($rec['comments'])): ?>
                                <div class="mt-1 text-secondary italic">"<?= esc($rec['comments']) ?>"</div>
                              <?php endif; ?>
                            </div>
                          <?php endif; ?>
                        </div>

                        <!-- Action Buttons -->
                        <div>
                          <form action="<?= base_url('version-releases/approvals/submit') ?>" method="POST">
                            <?= csrf_field() ?>
                            <input type="hidden" name="version_id" value="<?= $active_version['id'] ?>">
                            <input type="hidden" name="approval_type" value="<?= $typeKey ?>">

                            <div class="mb-2">
                              <input type="text" name="comments" class="form-control form-control-sm" placeholder="Sign-off comments (optional)">
                            </div>
                            <div class="d-flex gap-2">
                              <button type="submit" name="status" value="approved" class="btn btn-sm btn-success rounded-pill w-100">
                                <i class="bi bi-check-lg"></i> Approve
                              </button>
                              <button type="submit" name="status" value="rejected" class="btn btn-sm btn-outline-danger rounded-pill w-100">
                                <i class="bi bi-x-lg"></i> Reject
                              </button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>

        <!-- ================= SECTION 7: APPROVAL MAIL ================= -->
        <div class="tab-pane fade <?= ($active_tab === 'email') ? 'show active' : '' ?>" id="tab-email" role="tabpanel">
          <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center p-3">
              <div>
                <h5 class="fw-bold mb-0 text-dark">
                  <i class="bi bi-envelope-paper-fill text-primary me-2"></i>Release Approval Mail
                </h5>
                <small class="text-muted">Automated HTML email generation with testing summary, changelog, and backup status</small>
              </div>
              <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-primary rounded-pill px-3" onclick="copyEmailHtml()">
                  <i class="bi bi-clipboard me-1"></i> Copy HTML
                </button>
                <button type="button" class="btn btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#sendEmailModal">
                  <i class="bi bi-send me-1"></i> Send Mail to Stakeholders
                </button>
              </div>
            </div>
            <div class="card-body p-4">
              <!-- Live Embedded Preview Frame -->
              <div class="border rounded-3 p-3 bg-light" style="max-height: 700px; overflow-y: auto;">
                <iframe src="<?= base_url('version-releases/email/preview/' . $active_version['id']) ?>"
                  id="emailPreviewFrame"
                  style="width: 100%; height: 650px; border: none; background: #fff; border-radius: 8px;"></iframe>
              </div>
            </div>
          </div>
        </div>

        <!-- ================= SECTION 8: VERSION LIST ================= -->
        <div class="tab-pane fade <?= ($active_tab === 'versions') ? 'show active' : '' ?>" id="tab-versions" role="tabpanel">
          <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center p-3">
              <h5 class="fw-bold mb-0 text-dark">
                <i class="bi bi-collection-fill text-primary me-2"></i>All Releases History
              </h5>
              <button class="btn btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#newVersionModal">
                <i class="bi bi-plus-lg me-1"></i> New Version
              </button>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                  <thead class="table-light small text-muted text-uppercase">
                    <tr>
                      <th>Version & Build</th>
                      <th>Platform</th>
                      <th>Type</th>
                      <th>Status</th>
                      <th>Checklist Progress</th>
                      <th>Created By</th>
                      <th>Planned / Released Date</th>
                      <th class="text-end">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($versions as $ver): ?>
                      <tr class="<?= ($active_version && $active_version['id'] == $ver['id']) ? 'table-primary-subtle' : '' ?>">
                        <td>
                          <a href="<?= base_url('version-releases?version_id=' . $ver['id']) ?>" class="fw-bold text-primary text-decoration-none">
                            v<?= esc($ver['version_number']) ?>
                          </a>
                          <span class="small text-muted d-block">Build <?= esc($ver['build_number'] ?? 'N/A') ?></span>
                        </td>
                        <td>
                          <?php if ($ver['platform'] === 'android'): ?>
                            <span class="badge bg-success-subtle text-success"><i class="bi bi-android2"></i> Android</span>
                          <?php elseif ($ver['platform'] === 'ios'): ?>
                            <span class="badge bg-dark-subtle text-dark"><i class="bi bi-apple"></i> iOS</span>
                          <?php else: ?>
                            <span class="badge bg-primary-subtle text-primary"><i class="bi bi-phone"></i> Both</span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <span class="badge bg-secondary text-uppercase"><?= esc($ver['release_type']) ?></span>
                        </td>
                        <td>
                          <span class="badge rounded-pill <?= $ver['status'] === 'live' ? 'bg-success' : ($ver['status'] === 'rolled_back' ? 'bg-danger' : 'bg-primary') ?> text-uppercase">
                            <?= str_replace('_', ' ', esc($ver['status'])) ?>
                          </span>
                        </td>
                        <td>
                          <div class="d-flex align-items-center gap-2">
                            <div class="progress" style="width: 80px; height: 6px;">
                              <div class="progress-bar bg-success" style="width: <?= $ver['checklist_stats']['percentage'] ?>%;"></div>
                            </div>
                            <span class="small fw-semibold"><?= $ver['checklist_stats']['percentage'] ?>%</span>
                          </div>
                        </td>
                        <td>
                          <small class="text-dark fw-semibold"><?= esc($ver['creator_name']) ?></small>
                        </td>
                        <td>
                          <small class="text-muted">
                            <?= !empty($ver['released_at']) ? date('M d, Y', strtotime($ver['released_at'])) : (!empty($ver['planned_release_at']) ? date('M d, Y', strtotime($ver['planned_release_at'])) : date('M d, Y', strtotime($ver['created_at']))) ?>
                          </small>
                        </td>
                        <td class="text-end">
                          <a href="<?= base_url('version-releases?version_id=' . $ver['id']) ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            Manage
                          </a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- ================= SECTION 9: ROLLBACK CONSOLE ================= -->
        <div class="tab-pane fade <?= ($active_tab === 'rollback') ? 'show active' : '' ?>" id="tab-rollback" role="tabpanel">
          <div class="card border-0 shadow-sm rounded-4 mb-4 border-start border-danger border-4">
            <div class="card-header bg-transparent border-bottom p-3 d-flex justify-content-between align-items-center">
              <div>
                <h5 class="fw-bold mb-0 text-danger">
                  <i class="bi bi-arrow-counterclockwise text-danger me-2"></i>Emergency Rollback Console
                </h5>
                <small class="text-muted">Live Version &rarr; Critical Issue Detected &rarr; Rollback &rarr; Restore Previous Stable Version</small>
              </div>
              <button class="btn btn-danger rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#rollbackModal">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> Trigger Rollback
              </button>
            </div>
            <div class="card-body p-4">
              <!-- Rollback Workflow Overview -->
              <div class="p-3 bg-danger-subtle rounded-3 text-danger border border-danger-subtle mb-4">
                <div class="d-flex align-items-center gap-2 fw-bold">
                  <i class="bi bi-info-circle-fill fs-5"></i>
                  Emergency Recovery Protocol
                </div>
                <p class="small mb-0 mt-1">
                  Triggering a rollback immediately changes the version status to <code>rolling_back</code>, records audit logs with the blocker justification, and restores the targeted previous stable build for client traffic.
                </p>
              </div>

              <!-- History of Rollbacks -->
              <h6 class="fw-bold text-dark mb-3">Rollback Records for Version <?= esc($active_version['version_number']) ?></h6>
              <?php if (empty($active_version['rollbacks'])): ?>
                <div class="text-center py-4 text-muted small">
                  No rollback events have occurred for this version. System running normally.
                </div>
              <?php else: ?>
                <div class="table-responsive">
                  <table class="table table-bordered align-middle">
                    <thead class="table-light">
                      <tr>
                        <th>Status</th>
                        <th>Target Version</th>
                        <th>Reason / Critical Issue</th>
                        <th>Initiated By & Time</th>
                        <th>Completed Time</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($active_version['rollbacks'] as $rb): ?>
                        <tr>
                          <td>
                            <span class="badge rounded-pill <?= $rb['status'] === 'completed' ? 'bg-success' : 'bg-warning text-dark' ?>">
                              <?= strtoupper(esc($rb['status'])) ?>
                            </span>
                          </td>
                          <td class="fw-bold">v<?= esc($rb['rollback_to_version']) ?></td>
                          <td>
                            <div class="fw-bold text-danger"><?= esc($rb['rollback_reason']) ?></div>
                            <?php if (!empty($rb['critical_issue'])): ?>
                              <small class="text-muted"><?= esc($rb['critical_issue']) ?></small>
                            <?php endif; ?>
                          </td>
                          <td>
                            <div class="small fw-semibold"><?= esc($rb['approver_name'] ?? 'Admin') ?></div>
                            <small class="text-muted"><?= date('M d, Y h:i A', strtotime($rb['created_at'])) ?></small>
                          </td>
                          <td>
                            <?= !empty($rb['completed_at']) ? date('M d, Y h:i A', strtotime($rb['completed_at'])) : '-' ?>
                          </td>
                          <td>
                            <?php if ($rb['status'] === 'in_progress'): ?>
                              <form action="<?= base_url('version-releases/rollback/complete/' . $rb['id']) ?>" method="POST">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-success rounded-pill px-3">
                                  Mark Completed
                                </button>
                              </form>
                            <?php else: ?>
                              <span class="text-success small fw-bold"><i class="bi bi-check2"></i> Restored</span>
                            <?php endif; ?>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- ================= SECTION 10: AUDIT TRAIL ================= -->
        <div class="tab-pane fade <?= ($active_tab === 'audit') ? 'show active' : '' ?>" id="tab-audit" role="tabpanel">
          <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-transparent border-bottom p-3">
              <h5 class="fw-bold mb-0 text-dark">
                <i class="bi bi-journal-check text-primary me-2"></i>Release Audit Trail & History
              </h5>
              <small class="text-muted">Chronological record of state transitions, verifications, and approvals</small>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                  <thead class="table-light small text-muted text-uppercase">
                    <tr>
                      <th style="width: 20%;">Date & Time</th>
                      <th style="width: 20%;">Action</th>
                      <th style="width: 40%;">Description</th>
                      <th style="width: 20%;">User & IP</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($active_version['audit_logs'])): ?>
                      <tr>
                        <td colspan="4" class="text-center py-4 text-muted">No audit records found.</td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($active_version['audit_logs'] as $log): ?>
                        <tr>
                          <td>
                            <small class="text-muted">
                              <i class="bi bi-clock me-1"></i><?= date('M d, Y h:i A', strtotime($log['created_at'])) ?>
                            </small>
                          </td>
                          <td>
                            <span class="badge bg-secondary text-uppercase font-monospace" style="font-size: 0.75rem;">
                              <?= esc($log['action']) ?>
                            </span>
                          </td>
                          <td>
                            <div class="text-dark small"><?= esc($log['description'] ?? 'Action performed') ?></div>
                          </td>
                          <td>
                            <div class="small fw-semibold"><?= esc($log['user_name'] ?? 'Admin') ?></div>
                            <small class="text-muted" style="font-size: 0.7rem;"><?= esc($log['ip_address']) ?></small>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

      </div>

    <?php endif; ?>

  </div>
</main>

<!-- ================= MODALS ================= -->

<!-- 1. New Version Modal -->
<div class="modal fade" id="newVersionModal" tabindex="-1" aria-labelledby="newVersionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 rounded-4 shadow">
      <form action="<?= base_url('version-releases/store') ?>" method="POST">
        <?= csrf_field() ?>
        <div class="modal-header border-bottom p-4">
          <h5 class="modal-title fw-bold text-dark" id="newVersionModalLabel">
            <i class="bi bi-rocket-takeoff-fill text-warning me-2"></i>Create New Version Release
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Version Number <span class="text-danger">*</span></label>
              <input type="text" name="version_number" class="form-control" placeholder="e.g. 2.4.1" required>
              <small class="text-muted">Semantic version (Major.Minor.Patch)</small>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Build Number / Code</label>
              <input type="text" name="build_number" class="form-control" placeholder="e.g. 105">
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">Platform <span class="text-danger">*</span></label>
              <select name="platform" class="form-select">
                <option value="both" selected>Both (Android & iOS)</option>
                <option value="android">Android Only</option>
                <option value="ios">iOS Only</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Release Type <span class="text-danger">*</span></label>
              <select name="release_type" class="form-select">
                <option value="minor" selected>Minor Release</option>
                <option value="major">Major Release</option>
                <option value="bugfix">Bug Fix Release</option>
                <option value="hotfix">Hotfix Release</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Monitoring Window</label>
              <select name="monitoring_period" class="form-select">
                <option value="30min">30 Minutes</option>
                <option value="1hr" selected>1 Hour</option>
                <option value="4hr">4 Hours</option>
                <option value="24hr">24 Hours</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Planned Release Date & Time</label>
              <input type="datetime-local" name="planned_release_at" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Previous Version Baseline</label>
              <select name="previous_version_id" class="form-select">
                <option value="">Auto-select current live version (v<?= esc($live_version['version_number'] ?? '2.4.0') ?>)</option>
                <?php foreach ($versions as $v): ?>
                  <option value="<?= $v['id'] ?>">v<?= esc($v['version_number']) ?> (Build <?= esc($v['build_number'] ?? 'N/A') ?>)</option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold">Release Notes / Objectives</label>
              <textarea name="release_notes" class="form-control" rows="3" placeholder="Summary of what is shipping in this build..."></textarea>
            </div>

            <div class="col-12">
              <div class="p-3 bg-light rounded-3 border">
                <i class="bi bi-info-circle text-primary me-1"></i>
                <small class="text-muted">
                  Creating a version will automatically initialize the <strong>Frontend Developer</strong>, <strong>Backend Developer</strong>, and <strong>QA / Tester</strong> checklists.
                </small>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer border-top p-3">
          <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary rounded-pill px-4">Create Release</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- 2. Edit Version Modal -->
<?php if ($active_version): ?>
  <div class="modal fade" id="editVersionModal" tabindex="-1" aria-labelledby="editVersionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content border-0 rounded-4 shadow">
        <form action="<?= base_url('version-releases/update/' . $active_version['id']) ?>" method="POST">
          <?= csrf_field() ?>
          <div class="modal-header border-bottom p-4">
            <h5 class="modal-title fw-bold text-dark" id="editVersionModalLabel">
              <i class="bi bi-pencil-square text-primary me-2"></i>Edit Version Specifications
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Version Number</label>
                <input type="text" name="version_number" class="form-control" value="<?= esc($active_version['version_number']) ?>" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Build Number</label>
                <input type="text" name="build_number" class="form-control" value="<?= esc($active_version['build_number'] ?? '') ?>">
              </div>

              <div class="col-md-4">
                <label class="form-label fw-semibold">Platform</label>
                <select name="platform" class="form-select">
                  <option value="both" <?= $active_version['platform'] === 'both' ? 'selected' : '' ?>>Both (Android & iOS)</option>
                  <option value="android" <?= $active_version['platform'] === 'android' ? 'selected' : '' ?>>Android Only</option>
                  <option value="ios" <?= $active_version['platform'] === 'ios' ? 'selected' : '' ?>>iOS Only</option>
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label fw-semibold">Release Type</label>
                <select name="release_type" class="form-select">
                  <option value="minor" <?= $active_version['release_type'] === 'minor' ? 'selected' : '' ?>>Minor Release</option>
                  <option value="major" <?= $active_version['release_type'] === 'major' ? 'selected' : '' ?>>Major Release</option>
                  <option value="bugfix" <?= $active_version['release_type'] === 'bugfix' ? 'selected' : '' ?>>Bug Fix Release</option>
                  <option value="hotfix" <?= $active_version['release_type'] === 'hotfix' ? 'selected' : '' ?>>Hotfix Release</option>
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label fw-semibold">Monitoring Window</label>
                <select name="monitoring_period" class="form-select">
                  <option value="30min" <?= $active_version['monitoring_period'] === '30min' ? 'selected' : '' ?>>30 Minutes</option>
                  <option value="1hr" <?= $active_version['monitoring_period'] === '1hr' ? 'selected' : '' ?>>1 Hour</option>
                  <option value="4hr" <?= $active_version['monitoring_period'] === '4hr' ? 'selected' : '' ?>>4 Hours</option>
                  <option value="24hr" <?= $active_version['monitoring_period'] === '24hr' ? 'selected' : '' ?>>24 Hours</option>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Planned Release Date</label>
                <input type="datetime-local" name="planned_release_at" class="form-control"
                  value="<?= !empty($active_version['planned_release_at']) ? date('Y-m-d\TH:i', strtotime($active_version['planned_release_at'])) : '' ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Previous Version Baseline</label>
                <select name="previous_version_id" class="form-select">
                  <option value="">None</option>
                  <?php foreach ($versions as $v): ?>
                    <?php if ($v['id'] != $active_version['id']): ?>
                      <option value="<?= $v['id'] ?>" <?= $active_version['previous_version_id'] == $v['id'] ? 'selected' : '' ?>>
                        v<?= esc($v['version_number']) ?> (Build <?= esc($v['build_number'] ?? 'N/A') ?>)
                      </option>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-12">
                <label class="form-label fw-semibold">Release Notes</label>
                <textarea name="release_notes" class="form-control" rows="4"><?= esc($active_version['release_notes'] ?? '') ?></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer border-top p-3">
            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary rounded-pill px-4">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php endif; ?>

<!-- 3. Add Update Modal -->
<?php if ($active_version): ?>
  <div class="modal fade" id="newUpdateModal" tabindex="-1" aria-labelledby="newUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 rounded-4 shadow">
        <form action="<?= base_url('version-releases/updates/save') ?>" method="POST">
          <?= csrf_field() ?>
          <input type="hidden" name="version_id" value="<?= $active_version['id'] ?>">

          <div class="modal-header border-bottom p-4">
            <h5 class="modal-title fw-bold text-dark" id="newUpdateModalLabel">
              <i class="bi bi-plus-circle text-primary me-2"></i>Add Update to v<?= esc($active_version['version_number']) ?>
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">
            <div class="mb-3">
              <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
              <select name="category" class="form-select" required>
                <?php foreach ($categories as $cKey => $cMeta): ?>
                  <option value="<?= $cKey ?>"><?= $cMeta['label'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Title / Requirement <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control" placeholder="e.g. OTP auto-verification on Android" required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Jira / Ticket ID</label>
              <input type="text" name="ticket_id" class="form-control" placeholder="e.g. PF-1024">
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Description</label>
              <textarea name="description" class="form-control" rows="2" placeholder="Brief technical or functional description..."></textarea>
            </div>

            <div class="row g-2">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Assigned Developer</label>
                <select name="developer_id" class="form-select">
                  <option value="">Unassigned</option>
                  <?php foreach ($employees as $emp): ?>
                    <option value="<?= $emp['id'] ?>"><?= esc($emp['name']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                  <option value="completed" selected>Completed</option>
                  <option value="verified">Verified</option>
                  <option value="in_progress">In Progress</option>
                  <option value="pending">Pending</option>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer border-top p-3">
            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary rounded-pill px-4">Save Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php endif; ?>

<!-- 4. Emergency Rollback Modal -->
<?php if ($active_version): ?>
  <div class="modal fade" id="rollbackModal" tabindex="-1" aria-labelledby="rollbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 rounded-4 shadow border-top border-danger border-5">
        <form action="<?= base_url('version-releases/rollback/initiate') ?>" method="POST"
          onsubmit="return confirm('ARE YOU SURE? This will initiate an emergency rollback of the current production version!');">
          <?= csrf_field() ?>
          <input type="hidden" name="version_id" value="<?= $active_version['id'] ?>">

          <div class="modal-header border-bottom p-4">
            <h5 class="modal-title fw-bold text-danger" id="rollbackModalLabel">
              <i class="bi bi-exclamation-octagon-fill text-danger me-2"></i>Initiate Emergency Rollback
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">
            <div class="alert alert-danger small mb-3">
              <strong>Warning:</strong> You are about to rollback live production version <strong>v<?= esc($active_version['version_number']) ?></strong>.
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Current Live Version</label>
              <input type="text" name="current_live_version" class="form-control" value="<?= esc($active_version['version_number']) ?>" readonly>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Rollback To (Target Stable Version) <span class="text-danger">*</span></label>
              <input type="text" name="rollback_to_version" class="form-control"
                value="<?= esc($active_version['rollback_version']['version_number'] ?? $active_version['backup']['rollback_version'] ?? '2.4.0') ?>" required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Rollback Reason <span class="text-danger">*</span></label>
              <input type="text" name="rollback_reason" class="form-control" placeholder="e.g. Critical crash on OTP verification" required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Critical Issue Details & Logs</label>
              <textarea name="critical_issue" class="form-control" rows="3" placeholder="Describe stack trace, impact percentage, affected endpoints..."></textarea>
            </div>
          </div>
          <div class="modal-footer border-top p-3">
            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Execute Rollback</button>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php endif; ?>

<!-- 5. Send Approval Email Modal -->
<?php if ($active_version): ?>
  <div class="modal fade" id="sendEmailModal" tabindex="-1" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 rounded-4 shadow">
        <form action="<?= base_url('version-releases/email/send/' . $active_version['id']) ?>" method="POST">
          <?= csrf_field() ?>
          <div class="modal-header border-bottom p-4">
            <h5 class="modal-title fw-bold text-dark" id="sendEmailModalLabel">
              <i class="bi bi-send-fill text-primary me-2"></i>Send Release Approval Email
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">
            <div class="mb-3">
              <label class="form-label fw-semibold">Recipients (comma separated)</label>
              <input type="text" name="recipients" class="form-control"
                value="management@petsfolio.com, qa@petsfolio.com, techlead@petsfolio.com" required>
              <small class="text-muted">Enter email addresses of management and QA sign-off authorities</small>
            </div>
            <div class="p-3 bg-light rounded-3 small">
              The email will automatically include the release notes, updates catalog, developer & QA checklist completion status, production backup verification, and direct sign-off link.
            </div>
          </div>
          <div class="modal-footer border-top p-3">
            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary rounded-pill px-4">Send Email</button>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php endif; ?>

<!-- JavaScript for Interactive Checklist Status Updates and Actions -->
<script>
  function updateItemStatus(itemId, status) {
    const row = document.getElementById('item-row-' + itemId);
    const icon = document.getElementById('icon-' + itemId);
    const meta = document.getElementById('meta-' + itemId);

    // Optimistic UI pulse
    if (row) row.style.opacity = '0.6';

    const formData = new FormData();
    formData.append('item_id', itemId);
    formData.append('status', status);

    fetch('<?= base_url('version-releases/checklist/item-update') ?>', {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (row) row.style.opacity = '1';
        if (data.success) {
          // Update row class
          row.className = data.status === 'passed' ? 'table-success-subtle' : (data.status === 'failed' ? 'table-danger-subtle' : '');

          // Update icon
          if (icon) {
            if (data.status === 'passed') {
              icon.className = 'bi bi-check-circle-fill text-success fs-5 mt-1';
            } else if (data.status === 'failed') {
              icon.className = 'bi bi-x-circle-fill text-danger fs-5 mt-1';
            } else {
              icon.className = 'bi bi-circle text-muted fs-5 mt-1';
            }
          }

          // Update buttons
          const btnGroup = row.querySelector('.btn-group');
          if (btnGroup) {
            const btns = btnGroup.querySelectorAll('button');
            // Pending button
            btns[0].className = data.status === 'pending' ? 'btn btn-secondary text-white' : 'btn btn-outline-secondary';
            // Completed (passed) button
            btns[1].className = data.status === 'passed' ? 'btn btn-success text-white' : 'btn btn-outline-success';
            // Failed button
            btns[2].className = data.status === 'failed' ? 'btn btn-danger text-white' : 'btn btn-outline-danger';
          }

          // Update meta (checked by & timestamp)
          if (meta) {
            if (data.status !== 'pending' && data.checked_by) {
              meta.innerHTML = `
            <span class="badge bg-light text-dark border px-2 py-1">
              <i class="bi bi-person-fill text-primary me-1"></i>${data.checked_by}
            </span>
            <small class="text-muted d-block mt-1">
              <i class="bi bi-clock me-1"></i>${data.checked_at}
            </small>
          `;
            } else {
              meta.innerHTML = '<span class="text-muted small italic">Not verified yet</span>';
            }
          }

          // Update version overall progress
          if (data.version_stats) {
            const overallBar = document.getElementById('overallProgressBar');
            const overallText = document.getElementById('overallProgressText');
            if (overallBar) overallBar.style.width = data.version_stats.percentage + '%';
            if (overallText) overallText.textContent = data.version_stats.percentage + '%';
          }
        } else {
          alert(data.message || 'Error updating item status');
        }
      })
      .catch(err => {
        if (row) row.style.opacity = '1';
        console.error('Checklist update error:', err);
      });
  }

  function promptComment(itemId) {
    const currentComment = document.getElementById('comment-display-' + itemId)?.textContent.trim() || '';
    const comment = prompt('Enter comment / verification note for this checklist item:', currentComment);
    if (comment !== null) {
      const formData = new FormData();
      formData.append('item_id', itemId);
      // Keep current status
      formData.append('status', 'in_progress');
      formData.append('comment', comment);

      fetch('<?= base_url('version-releases/checklist/item-update') ?>', {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            const cd = document.getElementById('comment-display-' + itemId);
            if (cd) {
              cd.innerHTML = comment ? `<i class="bi bi-chat-left-quote text-primary me-1"></i>${comment}` : '';
            }
          }
        });
    }
  }

  function copyEmailHtml() {
    const frame = document.getElementById('emailPreviewFrame');
    if (frame && frame.contentWindow) {
      try {
        const html = frame.contentWindow.document.documentElement.outerHTML;
        navigator.clipboard.writeText(html).then(() => {
          alert('Release Approval Email HTML copied to clipboard!');
        }).catch(() => {
          alert('Could not auto-copy. Please open preview in new tab and copy.');
        });
      } catch (e) {
        alert('Email preview opened. You can view or send it directly.');
      }
    }
  }
</script>

<style>
  .custom-pills .nav-link {
    color: #475569;
    font-weight: 500;
    font-size: 0.85rem;
    transition: all 0.2s ease;
  }

  .custom-pills .nav-link:hover {
    background-color: #f1f5f9;
    color: #0f172a;
  }

  .custom-pills .nav-link.active {
    background: #0d424d !important;
    color: #ffffff !important;
    box-shadow: 0 4px 6px -1px rgba(13, 66, 77, 0.2);
  }

  .letter-spacing-1 {
    letter-spacing: 0.5px;
  }

  .bg-purple {
    background-color: #8b5cf6 !important;
    color: #ffffff !important;
  }

  .bg-teal {
    background-color: #0d9488 !important;
    color: #ffffff !important;
  }
</style>

<?php echo $this->endSection(); ?>