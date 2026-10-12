<?= $this->extend('admin/includes/header'); ?>

<?= $this->section('content'); ?>
<?php
  $filters    = $filters ?? [];
  $logs       = $logs ?? [];
  $kpis       = $kpis ?? [];
  $pagination = $pagination ?? ['total_records' => 0, 'total_pages' => 0, 'current_page' => 1, 'per_page' => 30];
?>
<style>
  .kpi-card {
    border-radius: 14px;
    border: none;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    overflow: hidden;
    position: relative;
  }
  .kpi-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08);
  }
  .kpi-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
  }
  .badge-status {
    padding: 6px 12px;
    font-weight: 600;
    border-radius: 8px;
    font-size: 0.78rem;
    letter-spacing: 0.3px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
  }
  .badge-success-soft {
    background: #e6f9ed;
    color: #167a3a;
    border: 1px solid #b7ecc8;
  }
  .badge-danger-soft {
    background: #fdeeee;
    color: #b91c1c;
    border: 1px solid #fecaca;
  }
  .badge-warning-soft {
    background: #fff8eb;
    color: #b45309;
    border: 1px solid #fde68a;
  }
  .badge-info-soft {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
  }
  .badge-purple-soft {
    background: #f5f3ff;
    color: #6d28d9;
    border: 1px solid #ddd6fe;
  }
  .table-custom {
    vertical-align: middle;
  }
  .table-custom th {
    background: #f8fafc;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.73rem;
    letter-spacing: 0.5px;
    color: #64748b;
    border-bottom: 1px solid #e2e8f0;
    padding: 12px 16px;
  }
  .table-custom td {
    padding: 14px 16px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.86rem;
  }
  .provider-avatar {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.95rem;
    flex-shrink: 0;
  }
  .copy-btn {
    cursor: pointer;
    opacity: 0.6;
    transition: opacity 0.15s ease, transform 0.15s ease;
  }
  .copy-btn:hover {
    opacity: 1;
    transform: scale(1.15);
  }
  .search-box {
    border-radius: 10px;
    border: 1px solid #cbd5e1;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
  }
  .search-box:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
  }
  .filter-pill {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #475569;
    text-decoration: none;
    transition: all 0.2s ease;
  }
  .filter-pill:hover, .filter-pill.active {
    background: #4f46e5;
    color: #ffffff;
    border-color: #4f46e5;
    box-shadow: 0 4px 10px rgba(79, 70, 229, 0.25);
  }
  .modal-custom .modal-content {
    border-radius: 16px;
    border: none;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
  }
  .detail-label {
    font-size: 0.72rem;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
    color: #64748b;
    margin-bottom: 2px;
  }
  .detail-value {
    font-size: 0.92rem;
    font-weight: 500;
    color: #1e293b;
    word-break: break-word;
  }
  .timeline-item {
    position: relative;
    padding-left: 24px;
    padding-bottom: 16px;
  }
  .timeline-item::before {
    content: '';
    position: absolute;
    left: 6px;
    top: 6px;
    bottom: 0;
    width: 2px;
    background: #e2e8f0;
  }
  .timeline-item:last-child::before {
    display: none;
  }
  .timeline-dot {
    position: absolute;
    left: 0;
    top: 4px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #6366f1;
    border: 3px solid #fff;
    box-shadow: 0 0 0 1px #cbd5e1;
  }
</style>

<div class="content-inner pb-5 px-3 px-md-4" id="page_layout">

  <!-- Header Banner -->
  <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);">
    <div class="card-body p-4 p-lg-5 text-white position-relative">
      <div class="row align-items-center position-relative z-1">
        <div class="col-lg-8">
          <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-white bg-opacity-20 text-dark rounded-pill px-3 py-1 fw-semibold" style="backdrop-filter: blur(4px);">
              <i class="bi bi-shield-check me-1"></i> DigiLocker KYC Audit
            </span>
            <span class="badge bg-success bg-opacity-75 text-white rounded-pill px-3 py-1 fw-semibold">
              Live Database
            </span>
          </div>
          <h2 class="fw-bold text-white mb-2">Aadhaar Identity Verification Logs</h2>
          <p class="text-indigo-100 mb-0 opacity-90" style="max-width: 650px;">
            Comprehensive tracking for DigiLocker Aadhaar verification sessions, provider consents, mismatches, and Cashfree API attempts.
          </p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
          <a href="<?= base_url('aadhaar-verification-logs/export?' . http_build_query(array_filter($filters))) ?>" class="btn btn-light text-primary fw-semibold px-3 py-2 shadow-sm rounded-3">
            <i class="bi bi-download me-1"></i> Export Logs (CSV)
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- KPI Stat Cards -->
  <?php
    $totalAttempts = (int) ($kpis['total_attempts'] ?? 0);
    $totalSuccess  = (int) ($kpis['total_success'] ?? 0);
    $totalFailed   = (int) ($kpis['total_failed'] ?? 0);
    $totalMismatch = (int) ($kpis['total_mismatch'] ?? 0);
    $totalPending  = (int) ($kpis['total_pending'] ?? 0);
    $successRate   = $totalAttempts > 0 ? round(($totalSuccess / $totalAttempts) * 100, 1) : 0;
  ?>
  <div class="row g-3 mb-4">
    <!-- Total Attempts -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card kpi-card shadow-sm h-100 p-3 bg-white">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <span class="text-muted fw-semibold small text-uppercase">Total Attempts</span>
            <h3 class="fw-bold text-dark mt-1 mb-0"><?= number_format($totalAttempts) ?></h3>
            <span class="text-muted small mt-1 d-block">
              Today: <strong class="text-dark"><?= number_format((int) ($kpis['today_attempts'] ?? 0)) ?></strong>
            </span>
          </div>
          <div class="kpi-icon bg-primary bg-opacity-10 text-primary">
            <i class="bi bi-journal-text"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Successful Verifications -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card kpi-card shadow-sm h-100 p-3 bg-white">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <span class="text-muted fw-semibold small text-uppercase">Verified Success</span>
            <h3 class="fw-bold text-success mt-1 mb-0"><?= number_format($totalSuccess) ?></h3>
            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 mt-1 small">
              <i class="bi bi-arrow-up-right me-1"></i><?= $successRate ?>% Success Rate
            </span>
          </div>
          <div class="kpi-icon bg-success bg-opacity-10 text-success">
            <i class="bi bi-check-circle-fill"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Mismatches -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card kpi-card shadow-sm h-100 p-3 bg-white">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <span class="text-muted fw-semibold small text-uppercase">Aadhaar Mismatches</span>
            <h3 class="fw-bold text-warning mt-1 mb-0"><?= number_format($totalMismatch) ?></h3>
            <span class="text-danger small mt-1 d-block fw-semibold">
              <i class="bi bi-exclamation-triangle me-1"></i>Number Discrepancies
            </span>
          </div>
          <div class="kpi-icon bg-warning bg-opacity-10 text-warning">
            <i class="bi bi-shield-slash-fill"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Failed / Denied -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card kpi-card shadow-sm h-100 p-3 bg-white">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <span class="text-muted fw-semibold small text-uppercase">Failed / Denied</span>
            <h3 class="fw-bold text-danger mt-1 mb-0"><?= number_format($totalFailed + (int)($kpis['total_consent_denied'] ?? 0)) ?></h3>
            <span class="text-muted small mt-1 d-block">
              Consent missing or aborted
            </span>
          </div>
          <div class="kpi-icon bg-danger bg-opacity-10 text-danger">
            <i class="bi bi-x-circle-fill"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Filter & Search Toolbar -->
  <div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3 p-md-4">
      <form method="GET" action="<?= base_url('aadhaar-verification-logs') ?>" id="filterForm">
        
        <!-- Status Quick Tabs -->
        <div class="d-flex flex-wrap gap-2 mb-3 pb-3 border-bottom">
          <a href="<?= base_url('aadhaar-verification-logs?' . http_build_query(array_merge($filters, ['status' => 'all', 'page' => 1]))) ?>" 
             class="filter-pill <?= empty($filters['status']) || $filters['status'] === 'all' ? 'active' : '' ?>">
            All Logs (<?= number_format($totalAttempts) ?>)
          </a>
          <a href="<?= base_url('aadhaar-verification-logs?' . http_build_query(array_merge($filters, ['status' => 'SUCCESS', 'page' => 1]))) ?>" 
             class="filter-pill <?= ($filters['status'] ?? '') === 'SUCCESS' ? 'active' : '' ?>">
            <span class="badge rounded-circle p-1 bg-success me-1"></span> Success (<?= number_format($totalSuccess) ?>)
          </a>
          <a href="<?= base_url('aadhaar-verification-logs?' . http_build_query(array_merge($filters, ['status' => 'FAILED', 'page' => 1]))) ?>" 
             class="filter-pill <?= ($filters['status'] ?? '') === 'FAILED' ? 'active' : '' ?>">
            <span class="badge rounded-circle p-1 bg-danger me-1"></span> Failed (<?= number_format($totalFailed) ?>)
          </a>
          <a href="<?= base_url('aadhaar-verification-logs?' . http_build_query(array_merge($filters, ['status' => 'MISMATCH', 'page' => 1]))) ?>" 
             class="filter-pill <?= ($filters['status'] ?? '') === 'MISMATCH' ? 'active' : '' ?>">
            <span class="badge rounded-circle p-1 bg-warning me-1"></span> Mismatch (<?= number_format($totalMismatch) ?>)
          </a>
          <a href="<?= base_url('aadhaar-verification-logs?' . http_build_query(array_merge($filters, ['status' => 'CONSENT_DENIED', 'page' => 1]))) ?>" 
             class="filter-pill <?= ($filters['status'] ?? '') === 'CONSENT_DENIED' ? 'active' : '' ?>">
            <span class="badge rounded-circle p-1 bg-secondary me-1"></span> Consent Denied (<?= number_format((int)($kpis['total_consent_denied'] ?? 0)) ?>)
          </a>
          <a href="<?= base_url('aadhaar-verification-logs?' . http_build_query(array_merge($filters, ['status' => 'PENDING', 'page' => 1]))) ?>" 
             class="filter-pill <?= ($filters['status'] ?? '') === 'PENDING' ? 'active' : '' ?>">
            <span class="badge rounded-circle p-1 bg-info me-1"></span> Pending (<?= number_format($totalPending) ?>)
          </a>
        </div>

        <!-- Filter Inputs -->
        <div class="row g-2 align-items-center">
          <input type="hidden" name="status" value="<?= esc($filters['status'] ?? 'all') ?>">
          
          <div class="col-12 col-md-5 col-lg-6">
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                <i class="bi bi-search"></i>
              </span>
              <input type="text" 
                     name="search" 
                     class="form-control border-start-0 search-box ps-1" 
                     placeholder="Search by Provider Name, Phone, Provider ID, Verification ID, or Ref ID..."
                     value="<?= esc($filters['search'] ?? '') ?>">
            </div>
          </div>

          <div class="col-6 col-md-3 col-lg-3">
            <select name="date_range" class="form-select search-box" onchange="this.form.submit()">
              <option value="all" <?= ($filters['date_range'] ?? '') === 'all' ? 'selected' : '' ?>>All Time</option>
              <option value="today" <?= ($filters['date_range'] ?? '') === 'today' ? 'selected' : '' ?>>Today</option>
              <option value="last_7_days" <?= ($filters['date_range'] ?? '') === 'last_7_days' ? 'selected' : '' ?>>Last 7 Days</option>
              <option value="last_30_days" <?= ($filters['date_range'] ?? '') === 'last_30_days' ? 'selected' : '' ?>>Last 30 Days</option>
            </select>
          </div>

          <div class="col-6 col-md-4 col-lg-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary fw-semibold flex-grow-1 rounded-3">
              <i class="bi bi-funnel-fill me-1"></i> Apply
            </button>
            <?php if (!empty($filters['search']) || (!empty($filters['status']) && $filters['status'] !== 'all') || (($filters['date_range'] ?? 'all') !== 'all')) : ?>
              <a href="<?= base_url('aadhaar-verification-logs') ?>" class="btn btn-outline-secondary rounded-3" title="Clear Filters">
                <i class="bi bi-arrow-counterclockwise"></i>
              </a>
            <?php endif; ?>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Logs Table Card -->
  <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-2">
        <h5 class="mb-0 fw-bold text-dark">Verification Attempts</h5>
        <span class="badge bg-light text-muted border px-2 py-1 small">
          Showing <?= count($logs) ?> of <?= number_format($pagination['total_records']) ?> entries
        </span>
      </div>
      <div>
        <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="window.location.reload()">
          <i class="bi bi-arrow-clockwise me-1"></i> Refresh
        </button>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-custom table-hover mb-0">
        <thead>
          <tr>
            <th style="width: 70px;">Attempt</th>
            <th>Service Provider</th>
            <th>Verification Session</th>
            <th>Status</th>
            <th>Details / Failure Reason</th>
            <th>Timestamp</th>
            <th class="text-end" style="width: 110px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($logs)) : ?>
            <tr>
              <td colspan="7" class="text-center py-5">
                <div class="py-4">
                  <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                  <h6 class="fw-bold text-dark mt-3">No Verification Logs Found</h6>
                  <p class="text-muted small mb-0">No records match your active search or filter criteria.</p>
                </div>
              </td>
            </tr>
          <?php else : ?>
            <?php foreach ($logs as $row) : ?>
              <tr>
                <!-- Attempt ID -->
                <td>
                  <span class="fw-bold text-dark">#<?= esc($row['id']) ?></span>
                </td>

                <!-- Provider Info -->
                <td>
                  <div class="d-flex align-items-center gap-3">
                    <div class="provider-avatar">
                      <?= strtoupper(substr($row['provider_name'] ?? 'P', 0, 1)) ?>
                    </div>
                    <div>
                      <div class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <?= esc($row['provider_name'] ?? 'Unknown Provider') ?>
                        <span class="badge bg-light text-dark border small" style="font-size: 0.68rem;">ID: <?= esc($row['provider_id']) ?></span>
                      </div>
                      <div class="small text-muted d-flex align-items-center gap-2 mt-1">
                        <?php if (!empty($row['provider_phone'])) : ?>
                          <span><i class="bi bi-telephone me-1"></i><?= esc($row['provider_phone']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($row['provider_email'])) : ?>
                          <span>&bull; <?= esc($row['provider_email']) ?></span>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </td>

                <!-- Verification Session -->
                <td>
                  <div class="d-flex flex-column gap-1">
                    <div class="d-flex align-items-center gap-2">
                      <span class="font-monospace small text-dark fw-semibold" title="<?= esc($row['verification_id']) ?>">
                        <?= esc(strlen($row['verification_id']) > 24 ? substr($row['verification_id'], 0, 24) . '...' : $row['verification_id']) ?>
                      </span>
                      <i class="bi bi-copy text-primary copy-btn small" 
                         onclick="copyToClipboard('<?= esc($row['verification_id']) ?>', this)" 
                         title="Copy Verification ID"></i>
                    </div>
                    <?php if (!empty($row['cashfree_reference_id'])) : ?>
                      <div class="small text-muted">
                        Ref: <span class="font-monospace fw-semibold text-secondary"><?= esc($row['cashfree_reference_id']) ?></span>
                      </div>
                    <?php endif; ?>
                  </div>
                </td>

                <!-- Status Badge -->
                <td>
                  <?php
                    $status = strtoupper($row['status'] ?? 'PENDING');
                    if ($status === 'SUCCESS') {
                        echo '<span class="badge-status badge-success-soft"><i class="bi bi-check-circle-fill"></i> SUCCESS</span>';
                    } elseif ($status === 'FAILED') {
                        echo '<span class="badge-status badge-danger-soft"><i class="bi bi-x-circle-fill"></i> FAILED</span>';
                    } elseif ($status === 'MISMATCH') {
                        echo '<span class="badge-status badge-warning-soft"><i class="bi bi-exclamation-triangle-fill"></i> MISMATCH</span>';
                    } elseif ($status === 'CONSENT_DENIED') {
                        echo '<span class="badge-status badge-purple-soft"><i class="bi bi-slash-circle-fill"></i> CONSENT DENIED</span>';
                    } else {
                        echo '<span class="badge-status badge-info-soft"><i class="bi bi-hourglass-split"></i> PENDING</span>';
                    }
                  ?>
                </td>

                <!-- Failure / Mismatch Info or Verified Aadhaar preview -->
                <td>
                  <?php if ($status === 'SUCCESS') : ?>
                    <div class="d-flex flex-column gap-1">
                      <div class="text-success small fw-semibold">
                        <i class="bi bi-shield-check me-1"></i>
                        Aadhaar: <span class="font-monospace">XXXX-XXXX-<?= esc($row['aadhaar_last4'] ?? '****') ?></span>
                      </div>
                      <?php if (!empty($row['verified_name'])) : ?>
                        <div class="text-muted small">
                          Name: <strong class="text-dark"><?= esc($row['verified_name']) ?></strong>
                          <?php if (!empty($row['verified_gender'])) : ?>
                            (<?= esc($row['verified_gender']) ?>)
                          <?php endif; ?>
                        </div>
                      <?php endif; ?>
                    </div>
                  <?php elseif ($status === 'MISMATCH') : ?>
                    <div class="alert alert-warning py-1 px-2 mb-0 small border-0 d-inline-block rounded-2">
                      <i class="bi bi-exclamation-circle me-1"></i>
                      <?= esc($row['failure_reason'] ?? 'Aadhaar details mismatch') ?>
                    </div>
                  <?php elseif ($status === 'FAILED') : ?>
                    <div class="text-danger small">
                      <i class="bi bi-info-circle me-1"></i>
                      <?= esc($row['failure_reason'] ?? 'Verification attempt failed') ?>
                    </div>
                  <?php elseif ($status === 'CONSENT_DENIED') : ?>
                    <div class="text-secondary small">
                      <i class="bi bi-person-x me-1"></i>
                      <?= esc($row['failure_reason'] ?? 'Consent rejected by user') ?>
                    </div>
                  <?php else : ?>
                    <span class="text-muted small">Session initiated; awaiting callback</span>
                  <?php endif; ?>
                </td>

                <!-- Timestamps -->
                <td>
                  <div class="small text-dark fw-semibold">
                    <?= !empty($row['started_at']) ? date('d M Y, h:i A', strtotime($row['started_at'])) : '-' ?>
                  </div>
                  <?php if (!empty($row['completed_at'])) : ?>
                    <?php
                      $durationSec = strtotime($row['completed_at']) - strtotime($row['started_at']);
                      $durText = $durationSec > 60 ? floor($durationSec / 60) . 'm ' . ($durationSec % 60) . 's' : $durationSec . 's';
                    ?>
                    <div class="text-muted small" style="font-size: 0.76rem;">
                      Duration: <?= $durText ?>
                    </div>
                  <?php endif; ?>
                </td>

                <!-- Actions -->
                <td class="text-end">
                  <button class="btn btn-sm btn-light border fw-semibold rounded-3 px-2 py-1 text-primary view-details-btn" 
                          data-id="<?= esc($row['id']) ?>" 
                          onclick="viewAttemptDetails(<?= (int)$row['id'] ?>)">
                    <i class="bi bi-eye me-1"></i> Details
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Pagination Footer -->
    <?php if ($pagination['total_pages'] > 1) : ?>
      <div class="card-footer bg-white border-top py-3 px-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
        <div class="small text-muted">
          Showing page <strong><?= $pagination['current_page'] ?></strong> of <strong><?= $pagination['total_pages'] ?></strong> (<?= number_format($pagination['total_records']) ?> total records)
        </div>
        <nav aria-label="Page navigation">
          <ul class="pagination pagination-sm mb-0">
            <!-- Previous Button -->
            <li class="page-item <?= $pagination['current_page'] <= 1 ? 'disabled' : '' ?>">
              <a class="page-link" href="<?= base_url('aadhaar-verification-logs?' . http_build_query(array_merge($filters, ['page' => $pagination['current_page'] - 1]))) ?>">
                <i class="bi bi-chevron-left"></i>
              </a>
            </li>

            <!-- Page Number Links -->
            <?php
              $startPage = max(1, $pagination['current_page'] - 2);
              $endPage   = min($pagination['total_pages'], $pagination['current_page'] + 2);
              for ($p = $startPage; $p <= $endPage; $p++) :
            ?>
              <li class="page-item <?= $p == $pagination['current_page'] ? 'active' : '' ?>">
                <a class="page-link" href="<?= base_url('aadhaar-verification-logs?' . http_build_query(array_merge($filters, ['page' => $p]))) ?>">
                  <?= $p ?>
                </a>
              </li>
            <?php endfor; ?>

            <!-- Next Button -->
            <li class="page-item <?= $pagination['current_page'] >= $pagination['total_pages'] ? 'disabled' : '' ?>">
              <a class="page-link" href="<?= base_url('aadhaar-verification-logs?' . http_build_query(array_merge($filters, ['page' => $pagination['current_page'] + 1]))) ?>">
                <i class="bi bi-chevron-right"></i>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    <?php endif; ?>
  </div>

</div>

<!-- Modal: Attempt Details -->
<div class="modal fade modal-custom" id="attemptDetailsModal" tabindex="-1" aria-labelledby="attemptDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header border-bottom py-3 px-4">
        <div>
          <h5 class="modal-title fw-bold text-dark" id="attemptDetailsModalLabel">
            <i class="bi bi-shield-lock text-primary me-2"></i> Verification Attempt Details
          </h5>
          <span class="text-muted small" id="modalSubTitle">Attempt #</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4" id="modalBodyContent">
        <!-- Spinner -->
        <div class="text-center py-5" id="modalSpinner">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <p class="text-muted small mt-2">Fetching verification record...</p>
        </div>

        <!-- Populated Content -->
        <div id="modalDataContent" style="display: none;">
          
          <!-- Top summary row -->
          <div class="card bg-light border-0 rounded-3 p-3 mb-4">
            <div class="row g-3">
              <div class="col-sm-6 col-md-3">
                <div class="detail-label">Status</div>
                <div id="modalStatusBadge" class="mt-1"></div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="detail-label">Provider ID</div>
                <div class="detail-value" id="modalProviderId">-</div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="detail-label">Started At</div>
                <div class="detail-value" id="modalStartedAt">-</div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="detail-label">Completed At</div>
                <div class="detail-value" id="modalCompletedAt">-</div>
              </div>
            </div>
          </div>

          <!-- Tabs for Structured View -->
          <ul class="nav nav-pills mb-3" id="modalTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active rounded-pill px-3 py-1 small fw-semibold" id="tab-overview-btn" data-bs-toggle="pill" data-bs-target="#tab-overview" type="button" role="tab">
                <i class="bi bi-info-circle me-1"></i> Overview
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link rounded-pill px-3 py-1 small fw-semibold" id="tab-doc-btn" data-bs-toggle="pill" data-bs-target="#tab-doc" type="button" role="tab">
                <i class="bi bi-file-earmark-person me-1"></i> DigiLocker Aadhaar Profile
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link rounded-pill px-3 py-1 small fw-semibold" id="tab-history-btn" data-bs-toggle="pill" data-bs-target="#tab-history" type="button" role="tab">
                <i class="bi bi-clock-history me-1"></i> Provider History
              </button>
            </li>
          </ul>

          <div class="tab-content" id="modalTabsContent">
            
            <!-- Tab 1: Overview -->
            <div class="tab-pane fade show active" id="tab-overview" role="tabpanel">
              <div class="row g-3">
                <div class="col-md-6">
                  <div class="p-3 border rounded-3 h-100">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-person me-1 text-primary"></i> Service Provider</h6>
                    <div class="mb-2">
                      <div class="detail-label">Full Name</div>
                      <div class="detail-value fw-bold" id="modalProviderName">-</div>
                    </div>
                    <div class="mb-2">
                      <div class="detail-label">Phone Number</div>
                      <div class="detail-value" id="modalProviderPhone">-</div>
                    </div>
                    <div class="mb-2">
                      <div class="detail-label">Email</div>
                      <div class="detail-value" id="modalProviderEmail">-</div>
                    </div>
                    <div>
                      <div class="detail-label">Registered Address</div>
                      <div class="detail-value" id="modalProviderAddress">-</div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="p-3 border rounded-3 h-100">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-link-45deg me-1 text-primary"></i> Session Identifiers</h6>
                    <div class="mb-2">
                      <div class="detail-label">Verification ID</div>
                      <div class="detail-value font-monospace small text-primary" id="modalVerificationId">-</div>
                    </div>
                    <div class="mb-2">
                      <div class="detail-label">Cashfree Reference ID</div>
                      <div class="detail-value font-monospace small text-dark" id="modalRefId">-</div>
                    </div>
                    <div class="mb-2">
                      <div class="detail-label">Verification Type</div>
                      <div class="detail-value" id="modalVerificationType">DIGILOCKER_AADHAAR</div>
                    </div>
                    <div id="modalFailureBox" style="display: none;" class="mt-3">
                      <div class="detail-label text-danger">Failure / Error Reason</div>
                      <div class="alert alert-danger py-2 px-3 small rounded-2 mb-0 mt-1" id="modalFailureReason"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Tab 2: DigiLocker Document -->
            <div class="tab-pane fade" id="tab-doc" role="tabpanel">
              <div id="modalNoDocMessage" class="text-center py-4 text-muted" style="display: none;">
                <i class="bi bi-file-earmark-x" style="font-size: 2.5rem;"></i>
                <p class="mt-2 small">No verified Aadhaar document has been fetched for this attempt.</p>
              </div>

              <div id="modalDocDetails">
                <div class="row g-3">
                  <div class="col-md-7">
                    <div class="table-responsive border rounded-3">
                      <table class="table table-sm mb-0">
                        <tbody>
                          <tr>
                            <td class="text-muted small fw-semibold bg-light" style="width: 40%;">Aadhaar Number</td>
                            <td class="font-monospace fw-bold text-dark" id="docAadhaarMasked">-</td>
                          </tr>
                          <tr>
                            <td class="text-muted small fw-semibold bg-light">Full Name</td>
                            <td class="fw-bold text-dark" id="docFullName">-</td>
                          </tr>
                          <tr>
                            <td class="text-muted small fw-semibold bg-light">Date of Birth</td>
                            <td id="docDob">-</td>
                          </tr>
                          <tr>
                            <td class="text-muted small fw-semibold bg-light">Gender</td>
                            <td id="docGender">-</td>
                          </tr>
                          <tr>
                            <td class="text-muted small fw-semibold bg-light">Address (from DigiLocker)</td>
                            <td class="small" id="docAddress">-</td>
                          </tr>
                          <tr>
                            <td class="text-muted small fw-semibold bg-light">Document Fetched At</td>
                            <td class="small text-muted" id="docFetchedAt">-</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>

                  <div class="col-md-5 text-center">
                    <div class="p-3 border rounded-3 bg-light h-100 d-flex flex-column align-items-center justify-content-center">
                      <div class="detail-label mb-2">Aadhaar Photo</div>
                      <div id="docPhotoContainer" class="mb-2">
                        <img id="docPhotoImg" src="" alt="Aadhaar Photo" class="img-thumbnail shadow-sm rounded-3" style="max-height: 160px; display: none;">
                        <div id="docNoPhoto" class="text-muted small py-4">
                          <i class="bi bi-person-bounding-box" style="font-size: 3rem;"></i>
                          <div>No photo available</div>
                        </div>
                      </div>
                      <a id="docPhotoLink" href="#" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3" style="display: none;">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Open Photo
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Tab 3: History -->
            <div class="tab-pane fade" id="tab-history" role="tabpanel">
              <div class="p-3 border rounded-3">
                <h6 class="fw-bold text-dark mb-3">Recent Verification Attempts for this Provider</h6>
                <div id="modalHistoryContainer" class="timeline"></div>
              </div>
            </div>

          </div>

        </div>
      </div>
      <div class="modal-footer border-top py-2 px-4">
        <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  function copyToClipboard(text, el) {
    if (!navigator.clipboard) {
      const textarea = document.createElement('textarea');
      textarea.value = text;
      document.body.appendChild(textarea);
      textarea.select();
      document.execCommand('copy');
      document.body.removeChild(textarea);
      showCopiedFeedback(el);
      return;
    }
    navigator.clipboard.writeText(text).then(function() {
      showCopiedFeedback(el);
    });
  }

  function showCopiedFeedback(el) {
    const originalClass = el.className;
    el.className = 'bi bi-check2 text-success small';
    setTimeout(function() {
      el.className = originalClass;
    }, 1500);
  }

  function viewAttemptDetails(id) {
    const modalEl = document.getElementById('attemptDetailsModal');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();

    // Reset UI
    document.getElementById('modalSpinner').style.display = 'block';
    document.getElementById('modalDataContent').style.display = 'none';
    document.getElementById('modalSubTitle').innerText = 'Attempt #' + id;

    // Fetch details
    fetch('<?= base_url('aadhaar-verification-logs/details') ?>/' + id)
      .then(res => res.json())
      .then(res => {
        document.getElementById('modalSpinner').style.display = 'none';
        if (!res.success || !res.data) {
          alert(res.message || 'Unable to fetch attempt details.');
          modal.hide();
          return;
        }

        const data = res.data;
        document.getElementById('modalDataContent').style.display = 'block';

        // Overview details
        document.getElementById('modalProviderId').innerText = data.provider_id || '-';
        document.getElementById('modalProviderName').innerText = data.provider_name || 'Unknown';
        document.getElementById('modalProviderPhone').innerText = data.provider_phone || '-';
        document.getElementById('modalProviderEmail').innerText = data.provider_email || '-';
        document.getElementById('modalProviderAddress').innerText = data.provider_registered_address || '-';
        document.getElementById('modalVerificationId').innerText = data.verification_id || '-';
        document.getElementById('modalRefId').innerText = data.cashfree_reference_id || '-';
        document.getElementById('modalVerificationType').innerText = data.verification_type || 'DIGILOCKER_AADHAAR';
        document.getElementById('modalStartedAt').innerText = data.started_at ? new Date(data.started_at).toLocaleString() : '-';
        document.getElementById('modalCompletedAt').innerText = data.completed_at ? new Date(data.completed_at).toLocaleString() : '-';

        // Status Badge
        const status = (data.status || 'PENDING').toUpperCase();
        let badgeHtml = '';
        if (status === 'SUCCESS') {
          badgeHtml = '<span class="badge-status badge-success-soft"><i class="bi bi-check-circle-fill"></i> SUCCESS</span>';
        } else if (status === 'FAILED') {
          badgeHtml = '<span class="badge-status badge-danger-soft"><i class="bi bi-x-circle-fill"></i> FAILED</span>';
        } else if (status === 'MISMATCH') {
          badgeHtml = '<span class="badge-status badge-warning-soft"><i class="bi bi-exclamation-triangle-fill"></i> MISMATCH</span>';
        } else if (status === 'CONSENT_DENIED') {
          badgeHtml = '<span class="badge-status badge-purple-soft"><i class="bi bi-slash-circle-fill"></i> CONSENT DENIED</span>';
        } else {
          badgeHtml = '<span class="badge-status badge-info-soft"><i class="bi bi-hourglass-split"></i> PENDING</span>';
        }
        document.getElementById('modalStatusBadge').innerHTML = badgeHtml;

        // Failure Reason Box
        if (data.failure_reason) {
          document.getElementById('modalFailureBox').style.display = 'block';
          document.getElementById('modalFailureReason').innerText = data.failure_reason;
        } else {
          document.getElementById('modalFailureBox').style.display = 'none';
        }

        // Document Tab
        const doc = data.document_data_parsed;
        if (doc || data.aadhaar_last4) {
          document.getElementById('modalNoDocMessage').style.display = 'none';
          document.getElementById('modalDocDetails').style.display = 'block';

          document.getElementById('docAadhaarMasked').innerText = doc?.aadhaar_number_masked || (data.aadhaar_last4 ? 'XXXX-XXXX-' + data.aadhaar_last4 : '-');
          document.getElementById('docFullName').innerText = doc?.aadhaar_full_name || data.verified_name || '-';
          document.getElementById('docDob').innerText = doc?.aadhaar_dob || data.verified_dob || '-';
          document.getElementById('docGender').innerText = doc?.aadhaar_gender || data.verified_gender || '-';
          document.getElementById('docAddress').innerText = doc?.aadhaar_address || '-';
          document.getElementById('docFetchedAt').innerText = data.document_fetched_at ? new Date(data.document_fetched_at).toLocaleString() : (data.verified_at ? new Date(data.verified_at).toLocaleString() : '-');

          if (doc?.aadhaar_photo) {
            const photoUrl = 'https://api.petsfolio.in/public/uploads/aadhaar_photos/' + doc.aadhaar_photo;
            document.getElementById('docPhotoImg').src = photoUrl;
            document.getElementById('docPhotoImg').style.display = 'block';
            document.getElementById('docNoPhoto').style.display = 'none';
            document.getElementById('docPhotoLink').href = photoUrl;
            document.getElementById('docPhotoLink').style.display = 'inline-block';
          } else {
            document.getElementById('docPhotoImg').style.display = 'none';
            document.getElementById('docNoPhoto').style.display = 'block';
            document.getElementById('docPhotoLink').style.display = 'none';
          }
        } else {
          document.getElementById('modalNoDocMessage').style.display = 'block';
          document.getElementById('modalDocDetails').style.display = 'none';
        }

        // History Timeline
        const history = data.provider_history || [];
        const histContainer = document.getElementById('modalHistoryContainer');
        histContainer.innerHTML = '';
        if (history.length === 0) {
          histContainer.innerHTML = '<div class="text-muted small">No other attempts found.</div>';
        } else {
          history.forEach(h => {
            const isCurrent = parseInt(h.id) === parseInt(data.id);
            const hStatus = (h.status || 'PENDING').toUpperCase();
            let hBadgeClass = 'badge-info-soft';
            if (hStatus === 'SUCCESS') hBadgeClass = 'badge-success-soft';
            else if (hStatus === 'FAILED') hBadgeClass = 'badge-danger-soft';
            else if (hStatus === 'MISMATCH') hBadgeClass = 'badge-warning-soft';

            const item = document.createElement('div');
            item.className = 'timeline-item';
            item.innerHTML = `
              <div class="timeline-dot"></div>
              <div class="d-flex justify-content-between align-items-center mb-1">
                <div>
                  <strong class="text-dark">Attempt #${h.id}</strong>
                  ${isCurrent ? '<span class="badge bg-primary ms-1 small">Current</span>' : ''}
                </div>
                <span class="badge-status ${hBadgeClass} py-0 px-2 small">${hStatus}</span>
              </div>
              <div class="small text-muted font-monospace">${h.verification_id || '-'}</div>
              ${h.failure_reason ? `<div class="small text-danger mt-1">${h.failure_reason}</div>` : ''}
              <div class="small text-muted mt-1">${h.started_at ? new Date(h.started_at).toLocaleString() : ''}</div>
            `;
            histContainer.appendChild(item);
          });
        }
      })
      .catch(err => {
        document.getElementById('modalSpinner').style.display = 'none';
        alert('An error occurred while loading attempt details.');
        modal.hide();
      });
  }
</script>

<?= $this->endSection(); ?>
