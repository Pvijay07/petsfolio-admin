<?= $this->extend('admin/includes/header'); ?>
<?= $this->section('content'); ?>
<main class="main-content">
  <div class="position-relative iq-banner">
    <?= $this->include('admin/nav_bar') ?>
  </div>

<?php
  $activeTab = $_GET['tab'] ?? 'overview';
  $platformIcons = ['android' => '🤖 Android', 'ios' => '🍎 iOS', 'both' => '📱 Android + iOS'];
  $platformLabel = $platformIcons[$version['platform']] ?? ucfirst($version['platform']);
  $typeLabel = ucfirst($version['release_type']);
?>

  <div class="container-fluid content-inner pb-0 px-4" id="page_layout">
  <!-- Breadcrumb + Header -->
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-2">
      <li class="breadcrumb-item"><a href="<?= base_url('releases') ?>">Release Management</a></li>
      <li class="breadcrumb-item active">v<?= esc($version['version_number']) ?></li>
    </ol>
  </nav>

  <!-- Version Header Card -->
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3 px-4">
      <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
          <h3 class="fw-bold mb-1">
            v<?= esc($version['version_number']) ?>
            <span class="badge bg-<?= $statusColor ?> ms-2" style="font-size:0.65em;vertical-align:middle"><?= $statusLabel ?></span>
          </h3>
          <div class="d-flex flex-wrap gap-2 text-muted small">
            <span><i class="bi bi-tag me-1"></i><?= $typeLabel ?> Release</span>
            <span>·</span>
            <span><?= $platformLabel ?></span>
            <?php if ($version['build_number']): ?>
              <span>·</span>
              <span>Build: <?= esc($version['build_number']) ?></span>
            <?php endif; ?>
            <?php if ($version['previous_version_number']): ?>
              <span>·</span>
              <span>Previous: <strong><?= esc($version['previous_version_number']) ?></strong></span>
            <?php endif; ?>
            <span>·</span>
            <span>Created by <?= esc($version['creator_name'] ?? 'Unknown') ?></span>
          </div>
        </div>
        <div class="d-flex gap-2 align-items-center">
          <!-- Status Transition Dropdown -->
          <?php if (!empty($allowedTransitions)): ?>
            <div class="dropdown">
              <button class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-arrow-right-circle me-1"></i>Change Status
              </button>
              <ul class="dropdown-menu">
                <?php foreach ($allowedTransitions as $t): ?>
                  <li>
                    <form method="post" action="<?= base_url("releases/transition-status/{$version['id']}") ?>" class="d-inline">
                      <?= csrf_field() ?>
                      <input type="hidden" name="new_status" value="<?= $t ?>">
                      <button type="submit" class="dropdown-item" onclick="return confirm('Move to <?= $versionModel->getStatusLabel($t) ?>?')">
                        <span class="badge bg-<?= $versionModel->getStatusColor($t) ?> me-1">&nbsp;</span>
                        <?= $versionModel->getStatusLabel($t) ?>
                      </button>
                    </form>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>
          <!-- Edit Button -->
          <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editVersionModal">
            <i class="bi bi-pencil"></i>
          </button>
        </div>
      </div>

      <!-- State Progress Bar -->
      <div class="mt-3">
        <?php
          $allStates = ['draft','testing','qa_approved','backup_completed','management_approved','scheduled','releasing','live','monitoring','successful'];
          $currentIndex = array_search($version['status'], $allStates);
          if ($currentIndex === false) $currentIndex = -1;
        ?>
        <div class="d-flex align-items-center gap-0 overflow-auto pb-1" style="min-width:0">
          <?php foreach ($allStates as $i => $state): ?>
            <?php
              $isDone = $i <= $currentIndex;
              $isCurrent = $i === $currentIndex;
              $color = $isDone ? ($isCurrent ? $versionModel->getStatusColor($state) : 'success') : 'secondary';
            ?>
            <div class="text-center flex-shrink-0" style="min-width:70px">
              <div class="rounded-circle mx-auto mb-1 d-flex align-items-center justify-content-center
                   <?= $isDone ? "bg-{$color} text-white" : 'bg-light text-muted border' ?>"
                   style="width:24px;height:24px;font-size:0.6rem;<?= $isCurrent ? 'box-shadow:0 0 0 3px rgba(var(--bs-primary-rgb),0.3)' : '' ?>">
                <?= $isDone ? '<i class="bi bi-check"></i>' : ($i + 1) ?>
              </div>
              <small class="d-block <?= $isCurrent ? 'fw-bold text-primary' : 'text-muted' ?>" style="font-size:0.6rem;line-height:1.2">
                <?= $versionModel->getStatusLabel($state) ?>
              </small>
            </div>
            <?php if ($i < count($allStates) - 1): ?>
              <div class="flex-shrink-0" style="height:2px;width:20px;background:<?= $isDone ? 'var(--bs-success)' : '#dee2e6' ?>;margin-top:-12px"></div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
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

  <!-- Tab Navigation -->
  <ul class="nav nav-tabs mb-0" id="versionTabs" role="tablist">
    <?php
      $tabs = [
        'overview'  => ['label' => 'Overview',       'icon' => 'bi-grid'],
        'updates'   => ['label' => 'Updates',        'icon' => 'bi-list-check'],
        'thread'    => ['label' => 'Version Thread',  'icon' => 'bi-diagram-3'],
        'checklist' => ['label' => 'QA Checklist',    'icon' => 'bi-clipboard-check'],
        'backup'    => ['label' => 'Backup',          'icon' => 'bi-cloud-arrow-up'],
        'approvals' => ['label' => 'Approvals',       'icon' => 'bi-person-check'],
        'email'     => ['label' => 'Approval Mail',   'icon' => 'bi-envelope'],
        'golive'    => ['label' => 'Go-Live',         'icon' => 'bi-rocket-takeoff'],
        'rollback'  => ['label' => 'Rollback',        'icon' => 'bi-arrow-counterclockwise'],
        'audit'     => ['label' => 'Audit Log',       'icon' => 'bi-clock-history'],
      ];
      foreach ($tabs as $key => $tab):
    ?>
      <li class="nav-item" role="presentation">
        <button class="nav-link <?= $activeTab === $key ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#tab-<?= $key ?>" type="button" role="tab">
          <i class="<?= $tab['icon'] ?> me-1"></i><span class="d-none d-md-inline"><?= $tab['label'] ?></span>
        </button>
      </li>
    <?php endforeach; ?>
  </ul>

  <!-- Tab Content -->
  <div class="tab-content" id="versionTabContent">

    <!-- ============================================================ -->
    <!-- TAB: Overview -->
    <!-- ============================================================ -->
    <div class="tab-pane fade <?= $activeTab === 'overview' ? 'show active' : '' ?>" id="tab-overview" role="tabpanel">
      <div class="card border-0 shadow-sm border-top-0 rounded-top-0">
        <div class="card-body p-4">
          <div class="row g-4">
            <!-- Quick Stats -->
            <div class="col-md-8">
              <h6 class="fw-bold mb-3">Quick Stats</h6>
              <div class="row g-3">
                <div class="col-6 col-md-3">
                  <div class="border rounded-3 p-3 text-center">
                    <h4 class="fw-bold mb-0"><?= $qaProgress ?>%</h4>
                    <small class="text-muted">QA Progress</small>
                  </div>
                </div>
                <div class="col-6 col-md-3">
                  <div class="border rounded-3 p-3 text-center">
                    <h4 class="fw-bold mb-0"><?= array_sum($updateStats) ?></h4>
                    <small class="text-muted">Updates</small>
                  </div>
                </div>
                <div class="col-6 col-md-3">
                  <div class="border rounded-3 p-3 text-center">
                    <h4 class="fw-bold mb-0"><?= $ticketStats['total'] ?? 0 ?></h4>
                    <small class="text-muted">Tickets</small>
                  </div>
                </div>
                <div class="col-6 col-md-3">
                  <div class="border rounded-3 p-3 text-center">
                    <h4 class="fw-bold mb-0"><?= ($approvalSummary['approved'] ?? 0) ?>/<?= ($approvalSummary['total'] ?? 0) ?></h4>
                    <small class="text-muted">Approvals</small>
                  </div>
                </div>
              </div>

              <!-- Release Notes -->
              <?php if ($version['release_notes']): ?>
                <h6 class="fw-bold mt-4 mb-2">Release Notes</h6>
                <div class="border rounded-3 p-3 bg-light">
                  <?= nl2br(esc($version['release_notes'])) ?>
                </div>
              <?php endif; ?>

              <!-- Update Summary by Category -->
              <?php if (!empty($updateStats)): ?>
                <h6 class="fw-bold mt-4 mb-2">Changes by Category</h6>
                <div class="d-flex flex-wrap gap-2">
                  <?php foreach ($updateStats as $cat => $count): ?>
                    <span class="badge bg-light text-dark border px-3 py-2">
                      <i class="<?= $updateModel->getCategoryIcon($cat) ?> me-1"></i>
                      <?= $updateModel->getCategoryLabel($cat) ?>: <?= $count ?>
                    </span>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>

            <!-- Version Info Sidebar -->
            <div class="col-md-4">
              <h6 class="fw-bold mb-3">Version Details</h6>
              <div class="list-group list-group-flush">
                <div class="list-group-item d-flex justify-content-between px-0">
                  <small class="text-muted">Version</small>
                  <strong><?= esc($version['version_number']) ?></strong>
                </div>
                <div class="list-group-item d-flex justify-content-between px-0">
                  <small class="text-muted">Build</small>
                  <span><?= esc($version['build_number'] ?: '—') ?></span>
                </div>
                <div class="list-group-item d-flex justify-content-between px-0">
                  <small class="text-muted">Platform</small>
                  <span><?= ucfirst($version['platform']) ?></span>
                </div>
                <div class="list-group-item d-flex justify-content-between px-0">
                  <small class="text-muted">Type</small>
                  <span class="badge bg-light text-dark border"><?= $typeLabel ?></span>
                </div>
                <div class="list-group-item d-flex justify-content-between px-0">
                  <small class="text-muted">Target Date</small>
                  <span><?= $version['planned_release_at'] ? date('d-M-Y H:i', strtotime($version['planned_release_at'])) : '—' ?></span>
                </div>
                <div class="list-group-item d-flex justify-content-between px-0">
                  <small class="text-muted">Released</small>
                  <span><?= $version['released_at'] ? date('d-M-Y H:i', strtotime($version['released_at'])) : '—' ?></span>
                </div>
                <div class="list-group-item d-flex justify-content-between px-0">
                  <small class="text-muted">Previous</small>
                  <span><?= esc($version['previous_version_number'] ?: '—') ?></span>
                </div>
                <div class="list-group-item d-flex justify-content-between px-0">
                  <small class="text-muted">Created</small>
                  <span><?= date('d-M-Y H:i', strtotime($version['created_at'])) ?></span>
                </div>
              </div>

              <!-- Comments Section -->
              <h6 class="fw-bold mt-4 mb-3">Comments</h6>
              <form method="post" action="<?= base_url('releases/add-comment') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="version_id" value="<?= $version['id'] ?>">
                <select name="comment_type" class="form-select form-select-sm mb-2">
                  <option value="developer">Developer</option>
                  <option value="qa">QA</option>
                  <option value="approval">Approval</option>
                  <option value="general" selected>General</option>
                </select>
                <textarea name="comment" class="form-control form-control-sm mb-2" rows="2" placeholder="Add a comment..." required></textarea>
                <button type="submit" class="btn btn-sm btn-primary w-100">Post Comment</button>
              </form>

              <?php if (!empty($comments)): ?>
                <div class="mt-3" style="max-height:300px;overflow-y:auto">
                  <?php foreach (array_slice($comments, 0, 10) as $c): ?>
                    <div class="border-start border-3 border-<?= $commentModel->getTypeColor($c['comment_type']) ?> ps-2 mb-2">
                      <small class="fw-semibold"><?= esc($c['author_name'] ?? 'Unknown') ?></small>
                      <span class="badge bg-<?= $commentModel->getTypeColor($c['comment_type']) ?> ms-1" style="font-size:0.6rem"><?= $commentModel->getTypeLabel($c['comment_type']) ?></span>
                      <br><small class="text-muted"><?= esc($c['comment']) ?></small>
                      <br><small class="text-muted" style="font-size:0.65rem"><?= date('d-M H:i', strtotime($c['created_at'])) ?></small>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ============================================================ -->
    <!-- TAB: Updates -->
    <!-- ============================================================ -->
    <div class="tab-pane fade <?= $activeTab === 'updates' ? 'show active' : '' ?>" id="tab-updates" role="tabpanel">
      <div class="card border-0 shadow-sm border-top-0 rounded-top-0">
        <div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Version Updates</h6>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUpdateModal">
              <i class="bi bi-plus me-1"></i>Add Update
            </button>
          </div>

          <?php foreach ($updates as $catKey => $catData): ?>
            <?php if (!empty($catData['items'])): ?>
              <div class="mb-4">
                <h6 class="fw-semibold text-muted mb-2">
                  <i class="<?= $catData['icon'] ?> me-1"></i><?= $catData['label'] ?>
                  <span class="badge bg-primary rounded-pill ms-1"><?= count($catData['items']) ?></span>
                </h6>
                <div class="table-responsive">
                  <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                      <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Ticket</th>
                        <th>Developer</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($catData['items'] as $item): ?>
                        <tr>
                          <td class="fw-semibold"><?= esc($item['title']) ?></td>
                          <td><small class="text-muted"><?= esc(mb_substr($item['description'] ?? '', 0, 80)) ?></small></td>
                          <td><?= $item['ticket_id'] ? '<code>' . esc($item['ticket_id']) . '</code>' : '—' ?></td>
                          <td><small><?= esc($item['developer_name'] ?? '—') ?></small></td>
                          <td>
                            <?php
                              $statusBg = ['pending' => 'secondary', 'in_progress' => 'warning', 'completed' => 'success', 'verified' => 'primary'];
                            ?>
                            <span class="badge bg-<?= $statusBg[$item['status']] ?? 'secondary' ?>"><?= ucfirst(str_replace('_',' ',$item['status'])) ?></span>
                          </td>
                          <td class="text-end">
                            <form method="post" action="<?= base_url("releases/delete-update/{$item['id']}") ?>" class="d-inline" onsubmit="return confirm('Delete this update?')">
                              <?= csrf_field() ?>
                              <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>

          <?php if (array_sum(array_map(function($c){ return count($c['items']); }, $updates)) === 0): ?>
            <div class="text-center py-5 text-muted">
              <i class="bi bi-list-check fs-1 d-block mb-2"></i>
              No updates added yet. Click "Add Update" to get started.
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- ============================================================ -->
    <!-- TAB: Version Thread -->
    <!-- ============================================================ -->
    <div class="tab-pane fade <?= $activeTab === 'thread' ? 'show active' : '' ?>" id="tab-thread" role="tabpanel">
      <div class="card border-0 shadow-sm border-top-0 rounded-top-0">
        <div class="card-body p-4">
          <h6 class="fw-bold mb-3"><i class="bi bi-diagram-3 me-1"></i>Version Timeline</h6>
          <?php if (!empty($versionThread)): ?>
            <div class="d-flex flex-column align-items-start">
              <?php foreach ($versionThread as $i => $tv): ?>
                <div class="d-flex align-items-start gap-3 mb-0 position-relative">
                  <!-- Connector line -->
                  <?php if ($i < count($versionThread) - 1): ?>
                    <div style="position:absolute;left:15px;top:32px;bottom:-8px;width:2px;background:<?= $tv['id'] == $version['id'] ? 'var(--bs-primary)' : '#dee2e6' ?>"></div>
                  <?php endif; ?>
                  <!-- Node -->
                  <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 <?= $tv['id'] == $version['id'] ? 'bg-primary text-white' : 'bg-light border' ?>"
                       style="width:32px;height:32px;z-index:1">
                    <i class="bi <?= $tv['id'] == $version['id'] ? 'bi-circle-fill' : 'bi-circle' ?>" style="font-size:0.5rem"></i>
                  </div>
                  <!-- Content -->
                  <div class="pb-4">
                    <a href="<?= base_url("releases/detail/{$tv['id']}") ?>" class="fw-semibold text-decoration-none <?= $tv['id'] == $version['id'] ? 'text-primary' : '' ?>">
                      Version <?= esc($tv['version_number']) ?>
                    </a>
                    <span class="badge bg-<?= $versionModel->getStatusColor($tv['status']) ?> ms-1" style="font-size:0.6rem">
                      <?= $versionModel->getStatusLabel($tv['status']) ?>
                    </span>
                    <br>
                    <small class="text-muted">
                      <?= ucfirst($tv['release_type']) ?> · <?= ucfirst($tv['platform']) ?>
                      · <?= date('d-M-Y', strtotime($tv['created_at'])) ?>
                    </small>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="text-center py-4 text-muted">No version history available.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- ============================================================ -->
    <!-- TAB: QA Checklist -->
    <!-- ============================================================ -->
    <div class="tab-pane fade <?= $activeTab === 'checklist' ? 'show active' : '' ?>" id="tab-checklist" role="tabpanel">
      <div class="card border-0 shadow-sm border-top-0 rounded-top-0">
        <div class="card-body p-4">
          <!-- Checklist Header -->
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <h6 class="fw-bold mb-1">Go-Live Checklist</h6>
              <div class="d-flex align-items-center gap-2">
                <div class="progress" style="width:200px;height:8px">
                  <div class="progress-bar bg-<?= $qaProgress >= 100 ? 'success' : ($qaProgress >= 50 ? 'warning' : 'danger') ?>" style="width:<?= $qaProgress ?>%"></div>
                </div>
                <small class="fw-semibold"><?= $qaProgress ?>%</small>
              </div>
            </div>
            <div class="d-flex gap-2">
              <?php foreach (['passed' => 'success', 'failed' => 'danger', 'pending' => 'secondary', 'in_progress' => 'warning'] as $s => $c): ?>
                <span class="badge bg-<?= $c ?>"><?= $checklistCounts[$s] ?? 0 ?> <?= ucfirst(str_replace('_',' ',$s)) ?></span>
              <?php endforeach; ?>
            </div>
          </div>

          <?php if (empty($checklist)): ?>
            <div class="text-center py-5 text-muted">
              <i class="bi bi-clipboard-check fs-1 d-block mb-2"></i>
              <p>Checklist not initialized yet.</p>
              <form method="post" action="<?= base_url("releases/init-checklist/{$version['id']}") ?>" class="d-inline">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-primary"><i class="bi bi-plus me-1"></i>Initialize Checklist</button>
              </form>
            </div>
          <?php else: ?>
            <?php foreach ($checklist as $section): ?>
              <div class="mb-4">
                <h6 class="fw-semibold text-primary mb-2">
                  <i class="bi bi-folder me-1"></i><?= esc($section['section_label']) ?>
                </h6>
                <div class="list-group">
                  <?php foreach ($section['items'] as $item): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                      <div class="d-flex align-items-center gap-2">
                        <?php
                          $itemIcons = ['pending' => 'bi-circle text-secondary', 'in_progress' => 'bi-arrow-repeat text-warning', 'passed' => 'bi-check-circle-fill text-success', 'failed' => 'bi-x-circle-fill text-danger', 'na' => 'bi-dash-circle text-muted'];
                        ?>
                        <i class="bi <?= $itemIcons[$item['status']] ?? 'bi-circle' ?>"></i>
                        <span class="<?= $item['status'] === 'passed' ? 'text-decoration-line-through text-muted' : '' ?>"><?= esc($item['item_text']) ?></span>
                        <?php if ($item['checker_name']): ?>
                          <small class="text-muted ms-2">— <?= esc($item['checker_name']) ?></small>
                        <?php endif; ?>
                      </div>
                      <div class="d-flex gap-1">
                        <?php foreach (['passed' => '✅', 'failed' => '❌', 'in_progress' => '🔄', 'na' => '➖'] as $st => $emoji): ?>
                          <form method="post" action="<?= base_url('releases/update-checklist-item') ?>" class="d-inline">
                            <?= csrf_field() ?>
                            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                            <input type="hidden" name="status" value="<?= $st ?>">
                            <input type="hidden" name="version_id" value="<?= $version['id'] ?>">
                            <button type="submit" class="btn btn-sm <?= $item['status'] === $st ? 'btn-primary' : 'btn-outline-secondary' ?>" style="font-size:0.7rem;padding:2px 6px" title="<?= ucfirst(str_replace('_',' ',$st)) ?>">
                              <?= $emoji ?>
                            </button>
                          </form>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- ============================================================ -->
    <!-- TAB: Backup -->
    <!-- ============================================================ -->
    <div class="tab-pane fade <?= $activeTab === 'backup' ? 'show active' : '' ?>" id="tab-backup" role="tabpanel">
      <div class="card border-0 shadow-sm border-top-0 rounded-top-0">
        <div class="card-body p-4">
          <h6 class="fw-bold mb-3"><i class="bi bi-cloud-arrow-up me-1"></i>Production Backup</h6>

          <form method="post" action="<?= base_url('releases/save-backup') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="version_id" value="<?= $version['id'] ?>">
            <?php if ($backup): ?>
              <input type="hidden" name="backup_id" value="<?= $backup['id'] ?>">
            <?php endif; ?>

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Current Production Version <span class="text-danger">*</span></label>
                <input type="text" name="current_prod_version" class="form-control" value="<?= esc($backup['current_prod_version'] ?? $version['previous_version_number'] ?? '') ?>" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Current Build Number</label>
                <input type="text" name="current_build_number" class="form-control" value="<?= esc($backup['current_build_number'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">APK/AAB Reference</label>
                <input type="text" name="apk_aab_reference" class="form-control" placeholder="Backup file path or URL" value="<?= esc($backup['apk_aab_reference'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">IPA Reference</label>
                <input type="text" name="ipa_reference" class="form-control" placeholder="Backup file path or URL" value="<?= esc($backup['ipa_reference'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Database Backup <span class="text-danger">*</span></label>
                <input type="text" name="database_backup" class="form-control" placeholder="e.g. db_backup_2026_09_05" value="<?= esc($backup['database_backup'] ?? '') ?>" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">API Backup (Git Commit/Tag)</label>
                <input type="text" name="api_backup" class="form-control" placeholder="e.g. v2.3.1 or commit hash" value="<?= esc($backup['api_backup'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Config Backup</label>
                <input type="text" name="config_backup" class="form-control" placeholder="Configuration version/reference" value="<?= esc($backup['config_backup'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Backup Date/Time</label>
                <input type="datetime-local" name="backup_date" class="form-control" value="<?= $backup ? date('Y-m-d\TH:i', strtotime($backup['backup_date'])) : date('Y-m-d\TH:i') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Rollback Version</label>
                <input type="text" name="rollback_version" class="form-control" value="<?= esc($backup['rollback_version'] ?? $version['previous_version_number'] ?? '') ?>">
              </div>
              <div class="col-12">
                <label class="form-label fw-semibold">Notes</label>
                <textarea name="notes" class="form-control" rows="2"><?= esc($backup['notes'] ?? '') ?></textarea>
              </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
              <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save Backup</button>
              <?php if ($backup): ?>
                <div>
                  <?php if ($backup['verified_by']): ?>
                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Verified by <?= esc($backup['verifier_name'] ?? 'Admin') ?> on <?= date('d-M H:i', strtotime($backup['verified_at'])) ?></span>
                  <?php else: ?>
                    <button type="button" class="btn btn-outline-success btn-sm" onclick="verifyBackup(<?= $backup['id'] ?>)">
                      <i class="bi bi-shield-check me-1"></i>Verify Backup
                    </button>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- ============================================================ -->
    <!-- TAB: Approvals -->
    <!-- ============================================================ -->
    <div class="tab-pane fade <?= $activeTab === 'approvals' ? 'show active' : '' ?>" id="tab-approvals" role="tabpanel">
      <div class="card border-0 shadow-sm border-top-0 rounded-top-0">
        <div class="card-body p-4">
          <h6 class="fw-bold mb-3"><i class="bi bi-person-check me-1"></i>Approval Workflow</h6>

          <div class="row g-3">
            <?php foreach ($approvals as $approval): ?>
              <div class="col-md-6 col-lg-4">
                <div class="card border h-100 <?= $approval['status'] === 'approved' ? 'border-success' : ($approval['status'] === 'rejected' ? 'border-danger' : '') ?>">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                      <div>
                        <i class="<?= $approvalModel->getTypeIcon($approval['approval_type']) ?> me-1"></i>
                        <strong><?= $approvalModel->getTypeLabel($approval['approval_type']) ?></strong>
                      </div>
                      <?php
                        $aBg = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'];
                      ?>
                      <span class="badge bg-<?= $aBg[$approval['status']] ?? 'secondary' ?>">
                        <?= $approval['status'] === 'approved' ? '✅' : ($approval['status'] === 'rejected' ? '❌' : '⏳') ?>
                        <?= ucfirst($approval['status']) ?>
                      </span>
                    </div>

                    <?php if ($approval['approved_by']): ?>
                      <small class="text-muted d-block">By: <?= esc($approval['approver_name'] ?? 'Unknown') ?></small>
                      <small class="text-muted d-block">At: <?= date('d-M-Y H:i', strtotime($approval['approved_at'])) ?></small>
                    <?php endif; ?>
                    <?php if ($approval['comments']): ?>
                      <small class="text-muted d-block mt-1">"<?= esc($approval['comments']) ?>"</small>
                    <?php endif; ?>

                    <?php if ($approval['status'] === 'pending'): ?>
                      <hr class="my-2">
                      <form method="post" action="<?= base_url('releases/submit-approval') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="version_id" value="<?= $version['id'] ?>">
                        <input type="hidden" name="approval_type" value="<?= $approval['approval_type'] ?>">
                        <textarea name="comments" class="form-control form-control-sm mb-2" rows="1" placeholder="Comments (optional)"></textarea>
                        <div class="d-flex gap-2">
                          <button type="submit" name="status" value="approved" class="btn btn-success btn-sm flex-fill">
                            <i class="bi bi-check me-1"></i>Approve
                          </button>
                          <button type="submit" name="status" value="rejected" class="btn btn-outline-danger btn-sm flex-fill">
                            <i class="bi bi-x me-1"></i>Reject
                          </button>
                        </div>
                      </form>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- ============================================================ -->
    <!-- TAB: Approval Mail -->
    <!-- ============================================================ -->
    <div class="tab-pane fade <?= $activeTab === 'email' ? 'show active' : '' ?>" id="tab-email" role="tabpanel">
      <div class="card border-0 shadow-sm border-top-0 rounded-top-0">
        <div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0"><i class="bi bi-envelope me-1"></i>Approval Email</h6>
            <a href="<?= base_url("releases/generate-email/{$version['id']}") ?>" class="btn btn-primary btn-sm">
              <i class="bi bi-magic me-1"></i>Generate Email
            </a>
          </div>

          <?php if ($latestEmail): ?>
            <div class="border rounded-3 p-3 mb-3">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <strong><?= esc($latestEmail['subject']) ?></strong>
                <span class="badge bg-<?= $latestEmail['status'] === 'sent' ? 'success' : ($latestEmail['status'] === 'failed' ? 'danger' : 'warning') ?>">
                  <?= ucfirst($latestEmail['status']) ?>
                </span>
              </div>
              <?php if ($latestEmail['sent_at']): ?>
                <small class="text-muted">Sent by <?= esc($latestEmail['sender_name'] ?? 'Unknown') ?> on <?= date('d-M-Y H:i', strtotime($latestEmail['sent_at'])) ?></small>
              <?php endif; ?>
            </div>

            <!-- Email Preview -->
            <div class="border rounded-3 p-3 bg-light mb-3" style="max-height:500px;overflow-y:auto">
              <?= $latestEmail['body_html'] ?>
            </div>

            <?php if ($latestEmail['status'] !== 'sent'): ?>
              <form method="post" action="<?= base_url("releases/send-email/{$latestEmail['id']}") ?>">
                <?= csrf_field() ?>
                <div class="mb-3">
                  <label class="form-label fw-semibold">Recipients (comma-separated emails)</label>
                  <input type="text" name="recipients" class="form-control" placeholder="admin@petsfolio.in, qa@petsfolio.in"
                         value="<?= esc(implode(', ', json_decode($latestEmail['recipients'] ?? '[]', true) ?: [])) ?>">
                </div>
                <button type="submit" class="btn btn-success">
                  <i class="bi bi-send me-1"></i>Send Email
                </button>
              </form>
            <?php endif; ?>
          <?php else: ?>
            <div class="text-center py-5 text-muted">
              <i class="bi bi-envelope fs-1 d-block mb-2"></i>
              <p>No email generated yet. Click "Generate Email" to create the approval email from release data.</p>
            </div>
          <?php endif; ?>

          <!-- Email History -->
          <?php if (!empty($emails) && count($emails) > 1): ?>
            <h6 class="fw-bold mt-4 mb-2">Email History</h6>
            <div class="list-group list-group-flush">
              <?php foreach (array_slice($emails, 1) as $em): ?>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                  <div>
                    <small class="fw-semibold"><?= esc($em['subject']) ?></small>
                    <br><small class="text-muted"><?= ucfirst($em['status']) ?> · <?= date('d-M-Y H:i', strtotime($em['created_at'])) ?></small>
                  </div>
                  <span class="badge bg-<?= $em['status'] === 'sent' ? 'success' : 'secondary' ?>"><?= ucfirst($em['status']) ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- ============================================================ -->
    <!-- TAB: Go-Live -->
    <!-- ============================================================ -->
    <div class="tab-pane fade <?= $activeTab === 'golive' ? 'show active' : '' ?>" id="tab-golive" role="tabpanel">
      <div class="card border-0 shadow-sm border-top-0 rounded-top-0">
        <div class="card-body p-4">
          <h6 class="fw-bold mb-3"><i class="bi bi-rocket-takeoff me-1"></i>Go-Live Control</h6>

          <!-- Pre-flight Checks -->
          <div class="row g-3 mb-4">
            <?php
              $checks = [
                ['label' => 'QA Approved',          'ok' => $approvalModel->isApproved($version['id'], 'qa')],
                ['label' => 'Backup Completed',     'ok' => $backup && $backup['verified_by']],
                ['label' => 'Management Approved',   'ok' => $approvalModel->isApproved($version['id'], 'management')],
                ['label' => 'Release Scheduled',     'ok' => in_array($version['status'], ['scheduled','releasing','live','monitoring','successful'])],
              ];
            ?>
            <?php foreach ($checks as $check): ?>
              <div class="col-md-3">
                <div class="border rounded-3 p-3 text-center <?= $check['ok'] ? 'border-success bg-success bg-opacity-10' : 'border-secondary' ?>">
                  <div class="fs-4 mb-1"><?= $check['ok'] ? '✅' : '⬜' ?></div>
                  <small class="fw-semibold"><?= $check['label'] ?></small>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <?php
            $allChecksPass = array_reduce($checks, function($carry, $c) { return $carry && $c['ok']; }, true);
          ?>

          <?php if (!$deployment): ?>
            <!-- GO LIVE button -->
            <div class="text-center py-4">
              <?php if ($allChecksPass): ?>
                <form method="post" action="<?= base_url("releases/go-live/{$version['id']}") ?>">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-success btn-lg px-5" onclick="return confirm('Start Go-Live for v<?= esc($version['version_number']) ?>?')">
                    <i class="bi bi-rocket-takeoff me-2"></i>GO LIVE
                  </button>
                </form>
              <?php else: ?>
                <button class="btn btn-secondary btn-lg px-5" disabled>
                  <i class="bi bi-lock me-2"></i>Complete All Checks First
                </button>
                <p class="text-muted mt-2 small">All pre-flight checks must pass before going live.</p>
              <?php endif; ?>
            </div>
          <?php else: ?>
            <!-- Deployment Progress Tracker -->
            <h6 class="fw-semibold mt-4 mb-3">Deployment Progress</h6>
            <div class="d-flex flex-column gap-2">
              <?php foreach ($deploymentProgress as $step): ?>
                <div class="d-flex align-items-center gap-3 p-2 rounded-3 <?= $step['current'] ? 'bg-primary bg-opacity-10 border border-primary' : '' ?>">
                  <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0
                       <?= $step['completed'] ? 'bg-success text-white' : ($step['current'] ? 'bg-primary text-white' : 'bg-light border') ?>"
                       style="width:36px;height:36px">
                    <i class="bi <?= $step['completed'] && !$step['current'] ? 'bi-check' : $step['icon'] ?>"></i>
                  </div>
                  <div class="flex-grow-1">
                    <span class="fw-semibold <?= $step['current'] ? 'text-primary' : ($step['completed'] ? '' : 'text-muted') ?>">
                      <?= $step['label'] ?>
                    </span>
                  </div>
                  <?php if ($step['current'] && $step['step'] !== 'successful'): ?>
                    <?php
                      $nextSteps = ['started' => 'deploying', 'deploying' => 'deployed', 'deployed' => 'health_check', 'health_check' => 'monitoring', 'monitoring' => 'successful'];
                      $nextStep = $nextSteps[$step['step']] ?? null;
                    ?>
                    <?php if ($nextStep): ?>
                      <form method="post" action="<?= base_url('releases/update-deployment') ?>" class="d-inline">
                        <?= csrf_field() ?>
                        <input type="hidden" name="deployment_id" value="<?= $deployment['id'] ?>">
                        <input type="hidden" name="version_id" value="<?= $version['id'] ?>">
                        <input type="hidden" name="step" value="<?= $nextStep ?>">
                        <button type="submit" class="btn btn-primary btn-sm">
                          <i class="bi bi-arrow-right me-1"></i><?= $deploymentModel->getStepLabel($nextStep) ?>
                        </button>
                      </form>
                    <?php endif; ?>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            </div>

            <?php if ($deployment['notes']): ?>
              <div class="mt-3 border rounded-3 p-3 bg-light">
                <small class="fw-semibold">Deployment Notes:</small><br>
                <small><?= nl2br(esc($deployment['notes'])) ?></small>
              </div>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- ============================================================ -->
    <!-- TAB: Rollback -->
    <!-- ============================================================ -->
    <div class="tab-pane fade <?= $activeTab === 'rollback' ? 'show active' : '' ?>" id="tab-rollback" role="tabpanel">
      <div class="card border-0 shadow-sm border-top-0 rounded-top-0">
        <div class="card-body p-4">
          <h6 class="fw-bold mb-3"><i class="bi bi-arrow-counterclockwise me-1"></i>Rollback Management</h6>

          <?php if (!$rollback): ?>
            <?php if (in_array($version['status'], ['live', 'monitoring'])): ?>
              <form method="post" action="<?= base_url('releases/initiate-rollback') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="version_id" value="<?= $version['id'] ?>">

                <div class="row g-3 mb-3">
                  <div class="col-md-6">
                    <div class="border rounded-3 p-3 text-center">
                      <small class="text-muted d-block">Current Live Version</small>
                      <h4 class="fw-bold text-danger mb-0"><?= esc($version['version_number']) ?></h4>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="border rounded-3 p-3 text-center">
                      <small class="text-muted d-block">Previous Stable Version</small>
                      <h4 class="fw-bold text-success mb-0"><?= esc($version['previous_version_number'] ?: '—') ?></h4>
                    </div>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-semibold">Rollback To Version <span class="text-danger">*</span></label>
                  <input type="text" name="rollback_to_version" class="form-control" value="<?= esc($version['previous_version_number'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-semibold">Rollback Reason <span class="text-danger">*</span></label>
                  <textarea name="rollback_reason" class="form-control" rows="3" required placeholder="Why is rollback needed?"></textarea>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-semibold">Critical Issue</label>
                  <textarea name="critical_issue" class="form-control" rows="2" placeholder="Describe the critical issue..."></textarea>
                </div>

                <button type="submit" class="btn btn-danger" onclick="return confirm('⚠️ Initiate rollback for v<?= esc($version['version_number']) ?>?')">
                  <i class="bi bi-exclamation-triangle me-1"></i>Initiate Rollback
                </button>
              </form>
            <?php else: ?>
              <div class="text-center py-5 text-muted">
                <i class="bi bi-shield-check fs-1 d-block mb-2"></i>
                <p>Rollback is only available for <strong>Live</strong> or <strong>Monitoring</strong> versions.</p>
              </div>
            <?php endif; ?>
          <?php else: ?>
            <!-- Rollback in progress / completed -->
            <div class="alert alert-<?= $rollback['status'] === 'completed' ? 'success' : 'danger' ?>">
              <strong><?= $rollback['status'] === 'completed' ? '✅ Rollback Completed' : '⚠️ Rollback In Progress' ?></strong>
              <br><small><?= esc($rollback['current_live_version']) ?> → <?= esc($rollback['rollback_to_version']) ?></small>
            </div>

            <!-- Rollback Timeline -->
            <?php if (!empty($rollbackTimeline)): ?>
              <h6 class="fw-semibold mt-3 mb-2">Rollback Timeline</h6>
              <div class="d-flex flex-column gap-2">
                <?php foreach ($rollbackTimeline as $event): ?>
                  <div class="d-flex align-items-start gap-3">
                    <div class="rounded-circle bg-<?= $event['color'] ?> text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width:28px;height:28px">
                      <i class="bi <?= $event['icon'] ?>" style="font-size:0.7rem"></i>
                    </div>
                    <div>
                      <span class="fw-semibold"><?= esc($event['event']) ?></span>
                      <br><small class="text-muted"><?= date('d-M-Y H:i', strtotime($event['time'])) ?></small>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

            <?php if ($rollback['status'] !== 'completed'): ?>
              <form method="post" action="<?= base_url("releases/complete-rollback/{$rollback['id']}") ?>" class="mt-3">
                <?= csrf_field() ?>
                <div class="mb-3">
                  <label class="form-label fw-semibold">Post-Rollback Notes</label>
                  <textarea name="post_rollback_notes" class="form-control" rows="2" placeholder="What happened after the rollback?"></textarea>
                </div>
                <button type="submit" class="btn btn-success" onclick="return confirm('Mark rollback as completed?')">
                  <i class="bi bi-check-circle me-1"></i>Complete Rollback
                </button>
              </form>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- ============================================================ -->
    <!-- TAB: Audit Log -->
    <!-- ============================================================ -->
    <div class="tab-pane fade <?= $activeTab === 'audit' ? 'show active' : '' ?>" id="tab-audit" role="tabpanel">
      <div class="card border-0 shadow-sm border-top-0 rounded-top-0">
        <div class="card-body p-4">
          <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-1"></i>Audit Trail</h6>

          <?php if (!empty($auditLogs)): ?>
            <div class="d-flex flex-column gap-0">
              <?php foreach ($auditLogs as $i => $log): ?>
                <div class="d-flex gap-3 pb-3 position-relative">
                  <?php if ($i < count($auditLogs) - 1): ?>
                    <div style="position:absolute;left:13px;top:28px;bottom:0;width:2px;background:#dee2e6"></div>
                  <?php endif; ?>
                  <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width:28px;height:28px;z-index:1">
                    <i class="bi bi-circle-fill" style="font-size:0.3rem"></i>
                  </div>
                  <div>
                    <div>
                      <small class="fw-semibold"><?= date('d-M H:i', strtotime($log['created_at'])) ?></small>
                      <span class="mx-1">·</span>
                      <small class="text-muted"><?= esc($log['user_name'] ?? 'System') ?></small>
                    </div>
                    <small><?= esc($log['description'] ?? $log['action']) ?></small>
                    <?php if ($log['old_value'] || $log['new_value']): ?>
                      <br><small class="text-muted">
                        <?php if ($log['old_value']): ?><del><?= esc(mb_substr($log['old_value'], 0, 50)) ?></del> → <?php endif; ?>
                        <?php if ($log['new_value']): ?><strong><?= esc(mb_substr($log['new_value'], 0, 50)) ?></strong><?php endif; ?>
                      </small>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="text-center py-4 text-muted">
              <i class="bi bi-clock-history fs-1 d-block mb-2"></i>
              No audit events recorded yet.
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div><!-- /tab-content -->
</div><!-- /container -->

<!-- ============================================================ -->
<!-- MODALS -->
<!-- ============================================================ -->

<!-- Edit Version Modal -->
<div class="modal fade" id="editVersionModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="<?= base_url("releases/update/{$version['id']}") ?>">
        <?= csrf_field() ?>
        <div class="modal-header">
          <h6 class="modal-title fw-bold">Edit Version <?= esc($version['version_number']) ?></h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold">Version Number</label>
            <input type="text" name="version_number" class="form-control" value="<?= esc($version['version_number']) ?>">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Build Number</label>
            <input type="text" name="build_number" class="form-control" value="<?= esc($version['build_number'] ?? '') ?>">
          </div>
          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label fw-semibold">Platform</label>
              <select name="platform" class="form-select">
                <option value="both" <?= $version['platform'] === 'both' ? 'selected' : '' ?>>Both</option>
                <option value="android" <?= $version['platform'] === 'android' ? 'selected' : '' ?>>Android</option>
                <option value="ios" <?= $version['platform'] === 'ios' ? 'selected' : '' ?>>iOS</option>
              </select>
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold">Release Type</label>
              <select name="release_type" class="form-select">
                <option value="major" <?= $version['release_type'] === 'major' ? 'selected' : '' ?>>Major</option>
                <option value="minor" <?= $version['release_type'] === 'minor' ? 'selected' : '' ?>>Minor</option>
                <option value="bugfix" <?= $version['release_type'] === 'bugfix' ? 'selected' : '' ?>>Bug Fix</option>
                <option value="hotfix" <?= $version['release_type'] === 'hotfix' ? 'selected' : '' ?>>Hotfix</option>
              </select>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Target Release Date</label>
            <input type="datetime-local" name="planned_release_at" class="form-control" value="<?= $version['planned_release_at'] ? date('Y-m-d\TH:i', strtotime($version['planned_release_at'])) : '' ?>">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Release Notes</label>
            <textarea name="release_notes" class="form-control" rows="4"><?= esc($version['release_notes'] ?? '') ?></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Monitoring Period</label>
            <select name="monitoring_period" class="form-select">
              <option value="30min" <?= ($version['monitoring_period'] ?? '') === '30min' ? 'selected' : '' ?>>30 Minutes</option>
              <option value="1hr" <?= ($version['monitoring_period'] ?? '1hr') === '1hr' ? 'selected' : '' ?>>1 Hour</option>
              <option value="4hr" <?= ($version['monitoring_period'] ?? '') === '4hr' ? 'selected' : '' ?>>4 Hours</option>
              <option value="24hr" <?= ($version['monitoring_period'] ?? '') === '24hr' ? 'selected' : '' ?>>24 Hours</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Add Update Modal -->
<div class="modal fade" id="addUpdateModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="post" action="<?= base_url('releases/save-update') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="version_id" value="<?= $version['id'] ?>">
        <div class="modal-header">
          <h6 class="modal-title fw-bold">Add Version Update</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
              <select name="category" class="form-select" required>
                <?php foreach ($categories as $key => $label): ?>
                  <option value="<?= $key ?>"><?= $label ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Description</label>
              <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Ticket ID</label>
              <input type="text" name="ticket_id" class="form-control" placeholder="e.g. PF-1024">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Developer</label>
              <select name="developer_id" class="form-select">
                <option value="">— Select —</option>
                <?php foreach ($employees as $emp): ?>
                  <option value="<?= $emp['id'] ?>"><?= esc($emp['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Status</label>
              <select name="status" class="form-select">
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
                <option value="verified">Verified</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Comments</label>
              <textarea name="comments" class="form-control" rows="2"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-plus me-1"></i>Add Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function verifyBackup(backupId) {
  if (!confirm('Verify this backup record?')) return;
  fetch(`<?= base_url('releases/verify-backup/') ?>${backupId}`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    body: JSON.stringify({})
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      location.reload();
    } else {
      alert(data.message || 'Failed to verify backup.');
    }
  });
}

// Activate tab from URL parameter
document.addEventListener('DOMContentLoaded', function() {
  const params = new URLSearchParams(window.location.search);
  const tab = params.get('tab');
  if (tab) {
    const tabEl = document.querySelector(`[data-bs-target="#tab-${tab}"]`);
    if (tabEl) {
      new bootstrap.Tab(tabEl).show();
    }
  }
});
</script>

</main>
<?php echo $this->include('admin/footer') ?>
<?php echo $this->endSection() ?>
