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
            <h4 class="fw-bold mb-1">Notification Logs</h4>
            <p class="text-muted mb-0">
              Track all sent notifications
              <?php if (!empty($filters['campaign_id'])): ?>
                — <span class="badge bg-primary rounded-pill">Campaign #<?= $filters['campaign_id'] ?></span>
              <?php endif; ?>
            </p>
          </div>
          <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
            <i class="bi bi-database me-1"></i> <?= number_format($totalLogs) ?> total records
          </span>
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
                <label class="form-label small fw-semibold">Channel</label>
                <select name="channel" class="form-select form-select-sm">
                  <option value="">All</option>
                  <?php foreach (['push','sms','email','whatsapp'] as $c): ?>
                    <option value="<?= $c ?>" <?= ($filters['channel'] ?? '') === $c ? 'selected' : '' ?>><?= ucfirst($c) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-2">
                <label class="form-label small fw-semibold">Status</label>
                <select name="status" class="form-select form-select-sm">
                  <option value="">All</option>
                  <?php foreach (['queued','sent','delivered','failed','opened','clicked'] as $s): ?>
                    <option value="<?= $s ?>" <?= ($filters['status'] ?? '') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-2">
                <label class="form-label small fw-semibold">User Type</label>
                <select name="user_type" class="form-select form-select-sm">
                  <option value="">All</option>
                  <option value="user" <?= ($filters['user_type'] ?? '') === 'user' ? 'selected' : '' ?>>Client</option>
                  <option value="provider" <?= ($filters['user_type'] ?? '') === 'provider' ? 'selected' : '' ?>>Provider</option>
                </select>
              </div>
              <div class="col-md-2">
                <label class="form-label small fw-semibold">From</label>
                <input type="date" name="date_from" class="form-control form-control-sm" value="<?= esc($filters['date_from'] ?? '') ?>" style="padding:0.375rem 0.75rem;font-family:inherit;background:white;">
              </div>
              <div class="col-md-2">
                <label class="form-label small fw-semibold">To</label>
                <input type="date" name="date_to" class="form-control form-control-sm" value="<?= esc($filters['date_to'] ?? '') ?>" style="padding:0.375rem 0.75rem;font-family:inherit;background:white;">
              </div>
              <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary px-3"><i class="bi bi-search me-1"></i>Filter</button>
                <a href="<?= base_url('notification-manager/logs') ?>" class="btn btn-sm btn-outline-secondary">Clear</a>
              </div>

              <?php if (!empty($filters['campaign_id'])): ?>
                <input type="hidden" name="campaign_id" value="<?= $filters['campaign_id'] ?>">
              <?php endif; ?>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Logs Table -->
    <div class="row">
      <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-body p-4">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                  <tr>
                    <th class="border-0 rounded-start py-3 ps-3">#</th>
                    <th class="border-0 py-3">Recipient</th>
                    <th class="border-0 py-3">Channel</th>
                    <th class="border-0 py-3">Title</th>
                    <th class="border-0 py-3">Body</th>
                    <th class="border-0 py-3">Status</th>
                    <th class="border-0 py-3">Campaign</th>
                    <th class="border-0 rounded-end py-3">Sent At</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($logs)): ?>
                    <?php
                    $offset = ($page - 1) * 50;
                    $i = $offset + 1;
                    foreach ($logs as $log):
                    ?>
                      <tr>
                        <td class="ps-3"><?= $i++ ?></td>
                        <td>
                          <div class="fw-semibold small"><?= esc($log->user_name ?: 'Unknown') ?></div>
                          <small class="text-muted">
                            <?= ucfirst($log->user_type) ?>
                            #<?= $log->user_type === 'user' ? ($log->user_id ?? '—') : ($log->provider_id ?? '—') ?>
                          </small>
                        </td>
                        <td>
                          <?php
                          $chColors = ['push' => 'primary', 'sms' => 'success', 'email' => 'info', 'whatsapp' => 'success'];
                          $chIcons = ['push' => 'bi-phone', 'sms' => 'bi-chat-dots', 'email' => 'bi-envelope', 'whatsapp' => 'bi-whatsapp'];
                          ?>
                          <span class="badge bg-<?= $chColors[$log->channel] ?? 'secondary' ?> bg-opacity-10 text-<?= $chColors[$log->channel] ?? 'secondary' ?> rounded-pill">
                            <i class="bi <?= $chIcons[$log->channel] ?? 'bi-bell' ?> me-1"></i><?= ucfirst($log->channel) ?>
                          </span>
                        </td>
                        <td>
                          <span class="small fw-semibold" title="<?= esc($log->title) ?>"><?= esc(mb_substr($log->title, 0, 30)) ?><?= mb_strlen($log->title) > 30 ? '...' : '' ?></span>
                        </td>
                        <td>
                          <span class="text-muted small" title="<?= esc($log->body) ?>"><?= esc(mb_substr($log->body ?? '', 0, 35)) ?><?= mb_strlen($log->body ?? '') > 35 ? '...' : '' ?></span>
                        </td>
                        <td>
                          <?php
                          $statusIcons = [
                            'queued' => ['bi-clock', 'secondary'],
                            'sent' => ['bi-check', 'primary'],
                            'delivered' => ['bi-check2-all', 'success'],
                            'failed' => ['bi-x-circle', 'danger'],
                            'opened' => ['bi-eye', 'info'],
                            'clicked' => ['bi-cursor', 'warning'],
                          ];
                          $si = $statusIcons[$log->status] ?? ['bi-question', 'secondary'];
                          ?>
                          <span class="badge bg-<?= $si[1] ?> rounded-pill">
                            <i class="bi <?= $si[0] ?> me-1"></i><?= ucfirst($log->status) ?>
                          </span>
                          <?php if ($log->error_message): ?>
                            <i class="bi bi-exclamation-triangle text-danger ms-1" title="<?= esc($log->error_message) ?>" style="cursor:help;"></i>
                          <?php endif; ?>
                        </td>
                        <td>
                          <?php if ($log->campaign_id): ?>
                            <a href="<?= base_url('notification-manager/logs?campaign_id=' . $log->campaign_id) ?>" class="badge bg-light text-dark border rounded-pill text-decoration-none">
                              #<?= $log->campaign_id ?>
                            </a>
                          <?php else: ?>
                            <span class="text-muted small">Direct</span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <small class="text-muted">
                            <?= $log->sent_at ? date('M d, h:i A', strtotime($log->sent_at)) : ($log->created_at ? date('M d, h:i A', strtotime($log->created_at)) : '—') ?>
                          </small>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="8" class="text-center py-5 text-muted">
                        <i class="bi bi-journal-text fs-1 d-block mb-2 opacity-25"></i>
                        No notification logs found.
                      </td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <?php if (($totalPages ?? 0) > 1): ?>
              <nav class="mt-4">
                <ul class="pagination justify-content-center mb-0">
                  <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?<?= http_build_query(array_merge($filters, ['page' => $page - 1])) ?>">
                      <i class="bi bi-chevron-left"></i>
                    </a>
                  </li>

                  <?php
                  $start = max(1, $page - 2);
                  $end = min($totalPages, $page + 2);
                  for ($p = $start; $p <= $end; $p++):
                  ?>
                    <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                      <a class="page-link" href="?<?= http_build_query(array_merge($filters, ['page' => $p])) ?>"><?= $p ?></a>
                    </li>
                  <?php endfor; ?>

                  <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?<?= http_build_query(array_merge($filters, ['page' => $page + 1])) ?>">
                      <i class="bi bi-chevron-right"></i>
                    </a>
                  </li>
                </ul>
              </nav>
            <?php endif; ?>

          </div>
        </div>
      </div>
    </div>

  </div>
  <?= $this->include('admin/footer') ?>
</main>

<?= $this->endSection() ?>
