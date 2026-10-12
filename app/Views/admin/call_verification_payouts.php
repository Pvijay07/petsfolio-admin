<?= $this->extend('admin/includes/header'); ?>
<?= $this->section('content'); ?>

<style>
  .badge-role-register {
    background-color: #dcfce7 !important;
    color: #15803d !important;
    border: 1.5px solid #86efac !important;
    font-weight: 700 !important;
    font-size: 0.8rem !important;
    padding: 6px 14px !important;
    border-radius: 50rem !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    white-space: nowrap !important;
  }

  .badge-role-verification {
    background-color: #dbeafe !important;
    color: #1d4ed8 !important;
    border: 1.5px solid #93c5fd !important;
    font-weight: 700 !important;
    font-size: 0.8rem !important;
    padding: 6px 14px !important;
    border-radius: 50rem !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    white-space: nowrap !important;
  }

  .badge-tx-count {
    background-color: #f1f5f9 !important;
    color: #334155 !important;
    border: 1px solid #cbd5e1 !important;
    font-weight: 700 !important;
    font-size: 0.8rem !important;
    padding: 6px 12px !important;
    border-radius: 50rem !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 4px !important;
  }

  .badge-status-pending {
    background-color: #fef3c7 !important;
    color: #b45309 !important;
    border: 1px solid #fcd34d !important;
    font-weight: 700 !important;
    padding: 6px 12px !important;
    border-radius: 50rem !important;
  }

  .badge-status-paid {
    background-color: #dcfce7 !important;
    color: #15803d !important;
    border: 1px solid #86efac !important;
    font-weight: 700 !important;
    padding: 6px 12px !important;
    border-radius: 50rem !important;
  }
</style>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?= $this->include('admin/nav_bar') ?>
  </div>

  <div class="container-fluid content-inner pb-0 px-4" id="page_layout">
    <!-- Modern Header Section -->
    <div class="container-fluid p-0 mb-4 bg-gradient-primary rounded-3 shadow">
      <div class="text-white p-4 p-md-5 position-relative overflow-hidden rounded-3" style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #10b981 100%);">
        <div class="position-relative z-1">
          <!-- <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-white bg-opacity-20 text-white px-3 py-1 rounded-pill fs-7 fw-semibold">
              <i class="bi bi-diagram-3-fill me-1"></i> Executive Payouts & Wallets
            </span>
          </div> -->
          <h1 class="h2 fw-bold mb-2 text-white">Executive Payouts & Wallets</h1>
          <p class="text-white-75 mb-0 fs-6" style="max-width: 850px;">
            Manage and release payouts for
            <strong>Registration Executives</strong> (Paid Provider Conversions: ₹15 / ₹20) and
            <strong>Verification Executives</strong> (Service Verification Calls: ₹10 / ₹15).
          </p>
        </div>
        <i class="bi bi-wallet2 position-absolute end-0 bottom-0 opacity-10 text-white"
          style="font-size: 12rem; transform: rotate(12deg); margin-right: -2rem; margin-bottom: -2.5rem;"></i>
      </div>
    </div>

    <!-- Dual Rate Tier Rules Callout Cards -->
    <div class="row g-3 mb-4">
      <!-- Registration Executives Tier Card -->
      <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3 h-100 border-start border-4 border-success bg-white">
          <div class="card-body p-3 p-md-4">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success bg-opacity-10 text-success p-2 rounded-circle">
                  <i class="bi bi-person-check-fill fs-5"></i>
                </span>
                <div>
                  <h6 class="fw-bold mb-0 text-dark">1. Registration Executives Rate Tiers</h6>
                  <small class="text-muted">Paid Provider Conversions (<code>sp_services.payment_paid = 1</code>)</small>
                </div>
              </div>
              <span class="badge bg-success text-white px-3 py-1 rounded-pill fs-8">Role 13 • Paid Conversions</span>
            </div>
            <div class="d-flex flex-wrap gap-3 mt-3 pt-2 border-top">
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-dark border px-2 py-1 fs-7 fw-bold">&lt; 15 paid / day</span>
                <span class="text-success fw-bold fs-6">₹15 <small class="text-muted fw-normal">/ paid provider</small></span>
              </div>
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1 fs-7 fw-bold">≥ 15 paid / day</span>
                <span class="text-success fw-bold fs-6">₹20 <small class="text-muted fw-normal">/ paid provider</small></span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Verification Executives Tier Card -->
      <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3 h-100 border-start border-4 border-primary bg-white">
          <div class="card-body p-3 p-md-4">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary bg-opacity-10 text-primary p-2 rounded-circle">
                  <i class="bi bi-telephone-check-fill fs-5"></i>
                </span>
                <div>
                  <h6 class="fw-bold mb-0 text-dark">2. Verification Executives Rate Tiers</h6>
                  <small class="text-muted">Service Verification Calls (<code>sp_reviews.reviewed_by = 'admin'</code>)</small>
                </div>
              </div>
              <span class="badge bg-primary text-white px-3 py-1 rounded-pill fs-8">Role 10 • Verification Calls</span>
            </div>
            <div class="d-flex flex-wrap gap-3 mt-3 pt-2 border-top">
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-dark border px-2 py-1 fs-7 fw-bold">&lt; 15 calls / day</span>
                <span class="text-primary fw-bold fs-6">₹10 <small class="text-muted fw-normal">/ call</small></span>
              </div>
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-2 py-1 fs-7 fw-bold">≥ 15 calls / day</span>
                <span class="text-primary fw-bold fs-6">₹15 <small class="text-muted fw-normal">/ call</small></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- KPI Summary Cards -->
    <div class="row g-4 mb-4">
      <!-- Total Pending Payout -->
      <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
          <div class="card-body p-4">
            <div class="d-flex align-items-center">
              <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center p-3" style="width: 54px; height: 54px;">
                <i class="bi bi-hourglass-split fs-3"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small fw-semibold mb-1 text-uppercase">Requested Pending Payouts</p>
                <h3 class="fw-bold mb-0 text-dark">₹<?= number_format($totalPendingAmount ?? 0, 2) ?></h3>
                <small class="text-muted">
                  <?= count($pendingPayouts ?? []) ?> requested withdrawal(s)
                </small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Registration Executives Pending -->
      <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
          <div class="card-body p-4">
            <div class="d-flex align-items-center">
              <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center p-3" style="width: 54px; height: 54px;">
                <i class="bi bi-person-check fs-3"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small fw-semibold mb-1 text-uppercase">Register Conversions</p>
                <h3 class="fw-bold mb-0 text-dark"><?= number_format($registerPendingCount ?? 0) ?></h3>
                <small class="text-success fw-semibold">₹<?= number_format($registerPendingAmount ?? 0, 2) ?> pending (₹15/₹20)</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Verification Executives Pending -->
      <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
          <div class="card-body p-4">
            <div class="d-flex align-items-center">
              <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center p-3" style="width: 54px; height: 54px;">
                <i class="bi bi-telephone-check fs-3"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small fw-semibold mb-1 text-uppercase">Verification Calls</p>
                <h3 class="fw-bold mb-0 text-dark"><?= number_format($verificationPendingCount ?? 0) ?></h3>
                <small class="text-primary fw-semibold">₹<?= number_format($verificationPendingAmount ?? 0, 2) ?> pending (₹10/₹15)</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Total Released Payouts -->
      <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
          <div class="card-body p-4">
            <div class="d-flex align-items-center">
              <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center p-3" style="width: 54px; height: 54px;">
                <i class="bi bi-check2-circle fs-3"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small fw-semibold mb-1 text-uppercase">Total Released Payouts</p>
                <h3 class="fw-bold mb-0 text-dark">₹<?= number_format($totalPaidAmount ?? 0, 2) ?></h3>
                <small class="text-muted">Total <?= count($payoutHistory ?? []) ?> releases completed</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
      <ul class="nav nav-pills gap-2" id="payoutTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active px-4 py-2 rounded-pill fw-semibold border shadow-sm" id="pending-tab" data-bs-toggle="pill" data-bs-target="#pending-content" type="button" role="tab">
            <i class="bi bi-hourglass-split me-1"></i> Pending Payouts (<?= count($pendingPayouts ?? []) ?>)
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link px-4 py-2 rounded-pill fw-semibold border shadow-sm" id="history-tab" data-bs-toggle="pill" data-bs-target="#history-content" type="button" role="tab">
            <i class="bi bi-clock-history me-1"></i> Payout History (<?= count($payoutHistory ?? []) ?>)
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link px-4 py-2 rounded-pill fw-semibold border shadow-sm" id="wallets-tab" data-bs-toggle="pill" data-bs-target="#wallets-content" type="button" role="tab">
            <i class="bi bi-wallet2 me-1"></i> Executive Wallets (<?= count($executiveWallets ?? []) ?>)
          </button>
        </li>
      </ul>

      <div>
        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill small">
          <i class="bi bi-calendar2-week text-primary me-1"></i> Weekly Release: <strong>Tuesday</strong>
        </span>
      </div>
    </div>

    <div class="tab-content" id="payoutTabsContent">
      <!-- TAB 1: PENDING PAYOUTS -->
      <div class="tab-pane fade show active" id="pending-content" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
          <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
            <div>
              <h5 class="fw-bold mb-0 text-dark">Executive Pending Payout Requests</h5>
              <small class="text-muted">Withdrawal requests submitted by Registration and Verification executives</small>
            </div>
            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3 py-1 rounded-pill">
              <?= count($pendingPayouts ?? []) ?> Requests Pending Release
            </span>
          </div>
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="pendingPayoutsTable">
              <thead style="background-color: #f8fafc;">
                <tr>
                  <th class="ps-4 py-3 text-secondary text-uppercase fs-7 fw-bold">Executive Name</th>
                  <th class="py-3 text-secondary text-uppercase fs-7 fw-bold text-center">Role / Department</th>
                  <th class="py-3 text-secondary text-uppercase fs-7 fw-bold text-center">Requested Volume</th>
                  <!-- <th class="py-3 text-secondary text-uppercase fs-7 fw-bold">Daily Threshold Breakdown</th> -->
                  <th class="py-3 text-secondary text-uppercase fs-7 fw-bold text-end">Requested Amount</th>
                  <th class="py-3 text-secondary text-uppercase fs-7 fw-bold text-center">Status</th>
                  <th class="pe-4 py-3 text-secondary text-uppercase fs-7 fw-bold text-end">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($pendingPayouts)) : ?>
                  <tr class="empty-pending-row">
                    <td colspan="7" class="text-center py-5 text-muted">
                      <i class="bi bi-check2-all display-6 d-block mb-2 text-success"></i>
                      All completed executive payouts have been released! No pending withdrawal requests found.
                    </td>
                  </tr>
                <?php else : ?>
                  <?php foreach ($pendingPayouts as $p) : ?>
                    <?php
                    $isRegister = (($p['executive_type'] ?? '') === 'register');
                    $typeClass  = $isRegister ? 'register' : 'verification';
                    ?>
                    <tr class="pending-row" data-type="<?= $typeClass ?>">
                      <td class="ps-4 fw-semibold text-dark">
                        <div class="d-flex align-items-center gap-2">
                          <div class="avatar-sm rounded-circle <?= $isRegister ? 'bg-success text-success' : 'bg-primary text-primary' ?> bg-opacity-10 d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px;">
                            <?= strtoupper(substr($p['executive_name'] ?? 'E', 0, 1)) ?>
                          </div>
                          <div>
                            <div class="fw-bold"><?= esc($p['executive_name'] ?? 'N/A') ?></div>
                            <small class="text-muted">ID: #<?= $p['executive_id'] ?? 0 ?></small>
                          </div>
                        </div>
                      </td>
                      <td class="text-center">
                        <?php if ($isRegister) : ?>
                          <span class="badge-role-register">
                            <i class="bi bi-person-check-fill"></i> Registration Executive
                          </span>
                        <?php else : ?>
                          <span class="badge-role-verification">
                            <i class="bi bi-telephone-fill"></i> Verification Executive
                          </span>
                        <?php endif; ?>
                      </td>
                      <td class="text-center fw-bold fs-6">
                        <span class="badge bg-light text-dark px-3 py-2 border rounded-pill fs-6">
                          <?= $p['total_pending_calls'] ?? 0 ?> <?= $isRegister ? 'Paid Providers' : 'Calls' ?>
                        </span>
                      </td>
                      <!-- <td>
                        <div class="d-flex flex-wrap gap-1">
                          <?php foreach (($p['daily_breakdown'] ?? []) as $day) : ?>
                            <?php
                            $rateVal = (float)($day['rate'] ?? 0);
                            $isHighTier = $isRegister ? ($rateVal >= 20) : ($rateVal >= 15);
                            ?>
                            <span class="badge <?= $isHighTier ? 'bg-success' : 'bg-secondary' ?> bg-opacity-10 text-dark border px-2 py-1 small" title="Rate: ₹<?= $rateVal ?>/unit">
                              <?= !empty($day['call_date']) ? date('M d', strtotime($day['call_date'])) : '' ?>: <strong><?= $day['daily_calls'] ?? $day['daily_count'] ?? 0 ?></strong> @ ₹<?= (int)$rateVal ?>
                            </span>
                          <?php endforeach; ?>
                        </div>
                      </td> -->
                      <td class="text-end fw-bold text-success fs-5">
                        ₹<?= number_format($p['total_pending_amount'] ?? 0, 2) ?>
                      </td>
                      <td class="text-center">
                        <span class="badge-status-pending">
                          <i class="bi bi-clock me-1"></i> Pending
                        </span>
                      </td>
                      <td class="pe-4 text-end">
                        <!-- <button type="button" class="btn btn-sm btn-outline-info me-1" onclick="viewPendingDetails(<?= $p['executive_id'] ?? 0 ?>, '<?= $p['executive_type'] ?? 'verification' ?>')">
                          <i class="bi bi-eye"></i> Details
                        </button> -->
                        <button type="button" class="btn btn-sm <?= !empty($p['bank_details']) ? 'btn-outline-success' : 'btn-outline-warning' ?> me-1" onclick="openExecutiveBankModal(<?= $p['executive_id'] ?? 0 ?>, '<?= esc($p['executive_name'] ?? '') ?>')">
                          <i class="bi bi-bank me-1"></i> <?= !empty($p['bank_details']) ? 'Bank Details' : 'Add Bank' ?>
                        </button>
                        <button type="button" class="btn btn-sm btn-success px-3" onclick='openReleaseModal(<?= (int)($p['executive_id'] ?? 0) ?>, "<?= esc($p['executive_name'] ?? '', 'js') ?>", <?= (int)($p['total_pending_calls'] ?? 0) ?>, <?= (float)($p['total_pending_amount'] ?? 0) ?>, <?= (int)($p['payout_id'] ?? 0) ?>, <?= json_encode($p['bank_details'] ?? null) ?>, "<?= $p['executive_type'] ?? 'verification' ?>")'>
                          <i class="bi bi-send-check me-1"></i> Release Payout
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- TAB 2: PAYOUT HISTORY -->
      <div class="tab-pane fade" id="history-content" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
          <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
            <div>
              <h5 class="fw-bold mb-0 text-dark">Released Payout History</h5>
              <small class="text-muted">Historical records of released executive payouts</small>
            </div>
            <span class="badge bg-light text-dark border px-3 py-1 rounded-pill small">
              Total <?= count($payoutHistory ?? []) ?> Paid Releases
            </span>
          </div>
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="historyPayoutsTable">
              <thead style="background-color: #f8fafc;">
                <tr>
                  <th class="ps-4 py-3 text-secondary text-uppercase fs-7 fw-bold">Payout Ref / ID</th>
                  <th class="py-3 text-secondary text-uppercase fs-7 fw-bold">Executive Name</th>
                  <th class="py-3 text-secondary text-uppercase fs-7 fw-bold text-center">Role / Department</th>
                  <th class="py-3 text-secondary text-uppercase fs-7 fw-bold text-center">Volume Paid</th>
                  <th class="py-3 text-secondary text-uppercase fs-7 fw-bold text-end">Total Amount</th>
                  <th class="py-3 text-secondary text-uppercase fs-7 fw-bold text-center">Status</th>
                  <th class="py-3 text-secondary text-uppercase fs-7 fw-bold">Release Date</th>
                  <th class="pe-4 py-3 text-secondary text-uppercase fs-7 fw-bold text-end">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($payoutHistory)) : ?>
                  <tr class="empty-history-row">
                    <td colspan="8" class="text-center py-5 text-muted">
                      <i class="bi bi-journal-x display-6 d-block mb-2"></i>
                      No payout history records found. Released payouts will appear here.
                    </td>
                  </tr>
                <?php else : ?>
                  <?php foreach ($payoutHistory as $ph) : ?>
                    <?php
                    $isRegister = (($ph['executive_type'] ?? '') === 'register');
                    $typeClass  = $isRegister ? 'register' : 'verification';
                    ?>
                    <tr class="history-row" data-type="<?= $typeClass ?>">
                      <td class="ps-4 fw-bold text-primary">
                        <?= esc(($ph['transaction_ref'] ?? '') ?: ('#PAY-' . ($ph['id'] ?? ''))) ?>
                      </td>
                      <td class="fw-semibold text-dark">
                        <?= esc($ph['executive_name'] ?? 'N/A') ?>
                      </td>
                      <td class="text-center">
                        <?php if ($isRegister) : ?>
                          <span class="badge-role-register">
                            <i class="bi bi-person-check-fill"></i> Registration Executive
                          </span>
                        <?php else : ?>
                          <span class="badge-role-verification">
                            <i class="bi bi-telephone-fill"></i> Verification Executive
                          </span>
                        <?php endif; ?>
                      </td>
                      <td class="text-center">
                        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-bold">
                          <?= $ph['total_calls'] ?? 0 ?> <?= $isRegister ? 'Paid Providers' : 'Calls' ?>
                        </span>
                      </td>
                      <td class="text-end fw-bold text-dark fs-6">
                        ₹<?= number_format($ph['total_amount'] ?? 0, 2) ?>
                      </td>
                      <td class="text-center">
                        <span class="badge-status-paid">
                          <i class="bi bi-check-all me-1"></i> Paid
                        </span>
                      </td>
                      <td class="text-muted small">
                        <?= !empty($ph['payout_date']) ? date('M d, Y h:i A', strtotime($ph['payout_date'])) : '--' ?>
                      </td>
                      <td class="pe-4 text-end">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewHistoryDetails(<?= $ph['id'] ?? 0 ?>)">
                          <i class="bi bi-card-checklist me-1"></i> View Receipt
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- TAB 3: EXECUTIVE WALLETS -->
      <div class="tab-pane fade" id="wallets-content" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
          <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
            <div>
              <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-wallet2 text-primary me-2"></i>Executive Wallets Pool</h5>
              <small class="text-muted">Live wallet balances for Registration Executives (Role 13) and Verification Executives (Role 10)</small>
            </div>
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold">
              Total Wallet Pool: ₹<?= number_format($totalWalletBalance ?? 0, 2) ?>
            </span>
          </div>
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="walletsTable">
              <thead style="background-color: #f8fafc;">
                <tr>
                  <th class="ps-4 py-3 text-secondary text-uppercase fs-7 fw-bold">Executive Name</th>
                  <th class="py-3 text-secondary text-uppercase fs-7 fw-bold text-center">Role / Department</th>
                  <th class="py-3 text-secondary text-uppercase fs-7 fw-bold text-end">Current Wallet Balance</th>
                  <th class="py-3 text-secondary text-uppercase fs-7 fw-bold text-end">Total Lifetime Earned</th>
                  <th class="py-3 text-secondary text-uppercase fs-7 fw-bold text-end">Total Withdrawn</th>
                  <th class="py-3 text-secondary text-uppercase fs-7 fw-bold text-center">Transactions</th>
                  <th class="py-3 text-secondary text-uppercase fs-7 fw-bold">Last Updated</th>
                  <th class="pe-4 py-3 text-secondary text-uppercase fs-7 fw-bold text-end">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($executiveWallets)) : ?>
                  <tr class="empty-wallet-row">
                    <td colspan="8" class="text-center py-5 text-muted">
                      <i class="bi bi-wallet display-6 d-block mb-2 text-secondary"></i>
                      No executive wallet records found.
                    </td>
                  </tr>
                <?php else : ?>
                  <?php foreach ($executiveWallets as $w) : ?>
                    <?php
                    $isRegister = (($w['executive_type'] ?? '') === 'register' || ($w['role_id'] ?? 0) == 13);
                    ?>
                    <tr class="wallet-row">
                      <td class="ps-4 fw-semibold text-dark">
                        <div class="d-flex align-items-center gap-2">
                          <div class="avatar-sm rounded-circle <?= $isRegister ? 'bg-success text-success' : 'bg-primary text-primary' ?> bg-opacity-10 d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px;">
                            <?= strtoupper(substr($w['executive_name'] ?? 'E', 0, 1)) ?>
                          </div>
                          <div>
                            <div class="fw-bold text-dark"><?= esc($w['executive_name'] ?? 'N/A') ?></div>
                            <small class="text-muted">ID: #<?= $w['executive_id'] ?? 0 ?></small>
                          </div>
                        </div>
                      </td>
                      <td class="text-center">
                        <?php if ($isRegister) : ?>
                          <span class="badge-role-register">
                            <i class="bi bi-person-check-fill"></i> Registration Executive
                          </span>
                        <?php else : ?>
                          <span class="badge-role-verification">
                            <i class="bi bi-telephone-fill"></i> Verification Executive
                          </span>
                        <?php endif; ?>
                      </td>
                      <td class="text-end fw-bold text-success fs-5">
                        ₹<?= number_format($w['balance'] ?? 0, 2) ?>
                      </td>
                      <td class="text-end fw-semibold text-dark fs-6">
                        ₹<?= number_format($w['total_earned'] ?? 0, 2) ?>
                      </td>
                      <td class="text-end fw-semibold text-muted fs-6">
                        ₹<?= number_format($w['total_withdrawn'] ?? 0, 2) ?>
                      </td>
                      <td class="text-center">
                        <span class="badge-tx-count">
                          <i class="bi bi-receipt me-1 text-primary"></i> <?= (int)($w['tx_count'] ?? 0) ?> Txns
                        </span>
                      </td>
                      <td class="text-muted small">
                        <?= !empty($w['updated_at']) ? date('M d, Y h:i A', strtotime($w['updated_at'])) : '--' ?>
                      </td>
                      <td class="pe-4 text-end">
                        <button type="button" class="btn btn-sm btn-outline-primary px-3 rounded-pill me-1" onclick="viewWalletLedger(<?= $w['executive_id'] ?? 0 ?>)">
                          <i class="bi bi-journal-text me-1"></i> Ledger
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary px-2 rounded-pill" onclick="openExecutiveBankModal(<?= $w['executive_id'] ?? 0 ?>, '<?= esc($w['executive_name'] ?? '') ?>')">
                          <i class="bi bi-bank"></i>
                        </button>
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
</main>

<!-- MODAL 1: RELEASE PAYOUT MODAL -->
<div class="modal fade" id="releasePayoutModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-gradient-primary text-white p-4" id="releaseModalHeader" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
        <div>
          <h5 class="modal-title fw-bold text-white mb-0" id="releaseModalTitle"><i class="bi bi-send-check me-2"></i> Process Executive Payout Transfer</h5>
          <small class="text-white-75" id="releaseModalSubtitle">Initiate real money transfer directly to executive's bank account</small>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="releasePayoutForm" onsubmit="submitReleasePayout(event)">
        <div class="modal-body p-4">
          <input type="hidden" id="release_executive_id" name="executive_id">
          <input type="hidden" id="release_payout_id" name="payout_id">
          <input type="hidden" id="release_amount" name="amount">
          <input type="hidden" id="release_executive_type" name="executive_type" value="verification">

          <!-- Summary Card -->
          <div class="bg-light p-4 rounded-3 mb-4 border">
            <div class="row g-3 align-items-center">
              <div class="col-md-7">
                <div class="d-flex align-items-center gap-3">
                  <div class="avatar-md bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 52px; height: 52px;" id="modal_avatar_icon">
                    <i class="bi bi-person-check"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-0" id="modal_exec_name">---</h5>
                    <div class="d-flex gap-2 align-items-center mt-1">
                      <span class="badge bg-primary bg-opacity-10 text-white border border-primary px-2 py-1" id="modal_total_calls">0 Units</span>
                      <span class="badge bg-secondary bg-opacity-10 text-white border px-2 py-1" id="modal_type_badge">Verification Executive</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-5 text-md-end">
                <small class="text-muted text-uppercase fw-bold d-block mb-1">Requested Payout Amount</small>
                <h2 class="fw-bold text-success mb-0" id="modal_total_amount">₹0.00</h2>
              </div>
            </div>
          </div>

          <!-- Target Verified Bank Account Details Card -->
          <div class="card border rounded-3 mb-4 shadow-sm">
            <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
              <span class="fw-bold text-dark small text-uppercase"><i class="bi bi-bank2 text-primary me-2"></i>Target Bank Account</span>
              <span class="badge bg-success px-3 py-1 rounded-pill" id="modal_bank_status_badge"><i class="bi bi-patch-check-fill me-1"></i> Verified Account</span>
            </div>
            <div class="card-body p-3 bg-light bg-opacity-50" id="modal_bank_details_body">
              <div class="row g-2 small">
                <div class="col-md-6"><strong>Bank Name:</strong> <span id="modal_bank_name" class="text-dark">Loading...</span></div>
                <div class="col-md-6"><strong>Account Holder:</strong> <span id="modal_acc_holder" class="text-dark">Loading...</span></div>
                <div class="col-md-6"><strong>Account Number:</strong> <span id="modal_acc_number" class="font-monospace fw-bold text-dark">Loading...</span></div>
                <div class="col-md-6"><strong>IFSC Code:</strong> <span id="modal_ifsc" class="font-monospace fw-bold text-primary">Loading...</span></div>
                <div class="col-md-6"><strong>UPI ID:</strong> <span id="modal_upi" class="text-muted">--</span></div>
              </div>
            </div>
            <div id="modal_bank_missing_alert" class="alert alert-warning border-0 rounded-0 m-0 p-3 small" style="display:none;">
              <i class="bi bi-exclamation-triangle-fill me-1"></i> <strong>No verified bank account found for this executive.</strong> Please add and verify bank details before initiating Cashfree transfer.
            </div>
          </div>

          <!-- Cashfree Direct Transfer Notice -->
          <!-- <div class="alert alert-success border-success border-opacity-25 bg-success bg-opacity-10 d-flex align-items-center gap-3 p-3 rounded-3 mb-3">
            <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
              <i class="bi bi-lightning-charge-fill fs-5"></i>
            </div>
            <div>
              <strong class="text-success d-block">Direct Real-Time Cashfree Payout</strong>
              <small class="text-secondary">Funds will be transferred directly and immediately from your Cashfree account to the executive's bank account.</small>
            </div>
          </div> -->

          <!-- Additional Remarks -->
          <div class="mb-2">
            <label class="form-label small fw-bold text-uppercase text-secondary">Transfer Remarks (Optional)</label>
            <input type="text" class="form-control" name="remarks" id="modal_remarks" placeholder="e.g. Executive Performance Payout">
          </div>

        </div>
        <div class="modal-footer bg-light p-3 border-top">
          <button type="button" class="btn btn-light px-4 border" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success px-4 fw-bold" id="confirmReleaseBtn">
            <i class="bi bi-send-check me-1"></i> Proceed & Transfer via Cashfree
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL 2: VIEW DETAILS MODAL -->
<div class="modal fade" id="payoutDetailsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg rounded-3">
      <div class="modal-header bg-dark text-white p-4" id="detailsModalHeader">
        <h5 class="modal-title fw-bold text-white"><i class="bi bi-card-heading me-2"></i> Payout Line-Item Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4" id="payoutDetailsModalBody">
        <div class="text-center py-5">
          <div class="spinner-border text-primary" role="status"></div>
          <p class="mt-2 text-muted">Loading records...</p>
        </div>
      </div>
      <div class="modal-footer bg-light p-3 border-top">
        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL 4: EXECUTIVE WALLET LEDGER MODAL -->
<div class="modal fade" id="walletLedgerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-3">
      <div class="modal-header bg-gradient-primary text-white p-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
        <div>
          <h5 class="modal-title fw-bold text-white mb-0" id="walletLedgerModalLabel"><i class="bi bi-wallet2 me-2"></i> Executive Wallet Ledger & Transactions</h5>
          <small class="text-white-50" id="ledger_exec_subtitle">Executive payout history & wallet balance ledger</small>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4" style="background: #f8fafc;">
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <div class="p-3 bg-white border rounded-3 shadow-sm d-flex justify-content-between align-items-center">
              <div>
                <small class="text-muted fw-bold text-uppercase d-block mb-1">Executive Name</small>
                <h5 class="fw-bold text-dark mb-0" id="ledger_exec_name">---</h5>
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-2 py-1 mt-1 small" id="ledger_type_badge">Verification Executive</span>
              </div>
              <div class="avatar-sm rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 44px; height: 44px;">
                <i class="bi bi-person-badge"></i>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="p-3 bg-white border rounded-3 shadow-sm">
              <small class="text-muted fw-bold text-uppercase d-block mb-1">Current Balance</small>
              <h4 class="fw-bold text-success mb-0" id="ledger_balance">₹0.00</h4>
            </div>
          </div>
          <div class="col-md-3">
            <div class="p-3 bg-white border rounded-3 shadow-sm">
              <small class="text-muted fw-bold text-uppercase d-block mb-1">Lifetime Earned</small>
              <h4 class="fw-bold text-dark mb-0" id="ledger_earned">₹0.00</h4>
            </div>
          </div>
        </div>

        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-list-columns-reverse text-primary me-2"></i>Wallet Transaction Ledger</h6>

        <div class="table-responsive bg-white rounded-3 border shadow-sm">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th class="ps-3 py-2">Date & Time</th>
                <th class="py-2">Type</th>
                <th class="py-2 text-end">Amount</th>
                <th class="py-2 text-end">Balance After</th>
                <th class="pe-3 py-2">Description / Reference</th>
              </tr>
            </thead>
            <tbody id="ledger_transactions_tbody">
              <tr>
                <td colspan="5" class="text-center py-4 text-muted">Loading transactions...</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="modal-footer bg-light p-3 border-top-0">
        <button type="button" class="btn btn-secondary btn-sm px-4 rounded-pill fw-bold" data-bs-dismiss="modal">Close Ledger</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL 5: EXECUTIVE BANK DETAILS & OTP VERIFICATION MODAL -->
<div class="modal fade" id="executiveBankModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-gradient-primary text-white border-0 py-3 px-4" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
        <div class="d-flex align-items-center gap-2">
          <div class="rounded-circle p-2 bg-white bg-opacity-10">
            <i class="bi bi-bank fs-4 text-warning"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold text-white mb-0" id="bankModalExecName">Executive Bank Payout Account</h5>
            <small class="text-white-50" id="bankModalExecSub">Add or Update Bank Account with Mobile OTP Verification</small>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4">
        <input type="hidden" id="bankModalExecId" value="">

        <!-- Status Card for Current Bank Details -->
        <div id="bankDetailsStatusCard" class="alert alert-light border rounded-3 p-3 mb-4" style="display:none;">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <!-- <span class="badge bg-success px-3 py-1 rounded-pill"><i class="bi bi-patch-check-fill me-1"></i> OTP Verified & Active Bank Account</span> -->
            <small class="text-muted" id="bankLastUpdated"></small>
          </div>
          <div class="row g-2 text-dark small">
            <div class="col-md-6"><strong>Bank Name:</strong> <span id="viewBankName">--</span></div>
            <div class="col-md-6"><strong>Account Holder:</strong> <span id="viewAccountHolder">--</span></div>
            <div class="col-md-6"><strong>Account Number:</strong> <span id="viewAccountNumber" class="font-monospace">--</span></div>
            <div class="col-md-6"><strong>IFSC Code:</strong> <span id="viewIfscCode" class="font-monospace fw-bold text-primary">--</span></div>
            <!-- <div class="col-md-6"><strong>UPI ID:</strong> <span id="viewUpiId" class="text-muted">--</span></div> -->
          </div>
        </div>

        <!-- STEP 1: Enter Bank Details Form -->
        <div id="bankFormStep">
          <!-- <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-pencil-square text-primary me-2"></i>Enter Payout Bank Account Details</h6> -->
          <form id="formBankDetails" onsubmit="event.preventDefault(); triggerSendBankOtp();">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label small fw-bold text-uppercase text-secondary">Bank Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="inputBankName" placeholder="e.g. HDFC Bank, ICICI Bank, SBI" required disabled>
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-bold text-uppercase text-secondary">Account Holder Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="inputAccountHolder" placeholder="e.g. Rahul Sharma" required disabled>
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-bold text-uppercase text-secondary">Bank Account Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control font-monospace" id="inputAccountNumber" placeholder="Enter bank account number" required disabled>
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-bold text-uppercase text-secondary">Confirm Account Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control font-monospace" id="inputConfirmAccountNumber" placeholder="Re-enter bank account number" required disabled>
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-bold text-uppercase text-secondary">IFSC Code <span class="text-danger">*</span></label>
                <input type="text" class="form-control font-monospace text-uppercase" id="inputIfscCode" placeholder="e.g. HDFC0001234" maxlength="11" required disabled>
              </div>
              <!-- 
              <div class="col-md-6">
                <label class="form-label small fw-bold text-uppercase text-secondary">UPI ID (Optional)</label>
                <input type="text" class="form-control" id="inputUpiId" placeholder="e.g. executive@upi">
              </div> -->
            </div>

            <!-- <div class="mt-4 pt-2 border-top text-end">
              <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold" id="btnSendBankOtp">
                <i class="bi bi-shield-lock me-1"></i> Send OTP Verification Code
              </button>
            </div> -->
          </form>
        </div>

        <!-- STEP 2: OTP Verification Card (Hidden initially) -->
        <div id="otpFormStep" class="border rounded-3 p-4 bg-light text-center" style="display:none;">
          <div class="avatar-md bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:56px; height:56px;">
            <i class="bi bi-shield-check fs-2"></i>
          </div>
          <h6 class="fw-bold text-dark mb-1">Enter 6-Digit Verification OTP</h6>
          <p class="text-muted small mb-3">An OTP verification code has been sent to the registered mobile number for bank account confirmation.</p>

          <div class="d-flex justify-content-center gap-2 mb-3">
            <input type="text" class="form-control text-center font-monospace fw-bold fs-4 rounded-3" id="inputBankOtpCode" placeholder="0 0 0 0 0 0" style="max-width: 220px; letter-spacing: 6px;" maxlength="6">
          </div>

          <div class="mb-3 text-info small fw-bold" id="otpDemoDisplay"></div>

          <div class="d-flex justify-content-center gap-2">
            <button type="button" class="btn btn-outline-secondary rounded-pill px-3 btn-sm" onclick="cancelOtpStep()">
              <i class="bi bi-arrow-left me-1"></i> Edit Bank Info
            </button>
            <button type="button" class="btn btn-success rounded-pill px-4 fw-bold" id="btnVerifyBankOtp" onclick="triggerVerifyBankOtp()">
              <i class="bi bi-check-circle me-1"></i> Verify OTP & Save Bank Details
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const baseUrl = '<?= base_url() ?>/';

  // -------------------------------------------------------------
  // Modal Handlers
  // -------------------------------------------------------------
  function openReleaseModal(execId, execName, totalCalls, totalAmount, payoutId, bankDetails, execType) {
    execType = execType || 'verification';
    const isRegister = (execType === 'register');

    const execIdElem = document.getElementById('release_executive_id');
    if (execIdElem) execIdElem.value = execId;

    const payoutIdElem = document.getElementById('release_payout_id');
    if (payoutIdElem) payoutIdElem.value = payoutId || '';

    const amountElem = document.getElementById('release_amount');
    if (amountElem) amountElem.value = parseFloat(totalAmount || 0).toFixed(2);

    const execTypeElem = document.getElementById('release_executive_type');
    if (execTypeElem) execTypeElem.value = execType;

    const execNameElem = document.getElementById('modal_exec_name');
    if (execNameElem) execNameElem.textContent = execName;

    const unitLabel = isRegister ? 'Paid Providers' : 'Verification Calls';
    const totalCallsElem = document.getElementById('modal_total_calls');
    if (totalCallsElem) totalCallsElem.textContent = (totalCalls || 0) + ' ' + unitLabel;

    const typeBadge = document.getElementById('modal_type_badge');
    if (typeBadge) {
      typeBadge.className = isRegister ? 'badge-role-register' : 'badge-role-verification';
      typeBadge.textContent = isRegister ? 'Registration Executive (₹15/₹20)' : 'Verification Executive (₹10/₹15)';
    }

    const totalAmountElem = document.getElementById('modal_total_amount');
    if (totalAmountElem) totalAmountElem.textContent = '₹' + parseFloat(totalAmount || 0).toFixed(2);

    const modalTitle = document.getElementById('releaseModalTitle');
    if (modalTitle) {
      modalTitle.innerHTML = '<i class="bi bi-send-check me-2"></i> Process ' + (isRegister ? 'Registration Executive' : 'Verification Executive') + ' Payout Transfer';
    }

    // Populate Bank Details
    const bankBody = document.getElementById('modal_bank_details_body');
    const missingAlert = document.getElementById('modal_bank_missing_alert');
    const statusBadge = document.getElementById('modal_bank_status_badge');

    if (bankDetails && bankDetails.account_number && bankDetails.ifsc_code) {
      document.getElementById('modal_bank_name').textContent = bankDetails.bank_name || 'N/A';
      document.getElementById('modal_acc_holder').textContent = bankDetails.account_holder_name || execName;
      document.getElementById('modal_acc_number').textContent = bankDetails.account_number;
      document.getElementById('modal_ifsc').textContent = bankDetails.ifsc_code;
      document.getElementById('modal_upi').textContent = bankDetails.upi_id || '--';
      if (bankBody) bankBody.style.display = 'block';
      if (missingAlert) missingAlert.style.display = 'none';
      if (statusBadge) statusBadge.style.display = 'inline-block';
    } else {
      fetch('<?= base_url('call-verification-payouts/bank-details/') ?>/' + execId)
        .then(res => res.json())
        .then(res => {
          if (res && res.status && res.bank_details && res.bank_details.account_number) {
            const b = res.bank_details;
            document.getElementById('modal_bank_name').textContent = b.bank_name || 'N/A';
            document.getElementById('modal_acc_holder').textContent = b.account_holder_name || execName;
            document.getElementById('modal_acc_number').textContent = b.account_number;
            document.getElementById('modal_ifsc').textContent = b.ifsc_code;
            document.getElementById('modal_upi').textContent = b.upi_id || '--';
            if (bankBody) bankBody.style.display = 'block';
            if (missingAlert) missingAlert.style.display = 'none';
            if (statusBadge) statusBadge.style.display = 'inline-block';
          } else {
            if (bankBody) bankBody.style.display = 'none';
            if (missingAlert) missingAlert.style.display = 'block';
            if (statusBadge) statusBadge.style.display = 'none';
          }
        })
        .catch(() => {
          if (bankBody) bankBody.style.display = 'none';
          if (missingAlert) missingAlert.style.display = 'block';
          if (statusBadge) statusBadge.style.display = 'none';
        });
    }

    const modalElem = document.getElementById('releasePayoutModal');
    if (modalElem) {
      const modal = bootstrap.Modal.getInstance(modalElem) || new bootstrap.Modal(modalElem);
      modal.show();
    }
  }

  function submitReleasePayout(e) {
    e.preventDefault();
    const btn = document.getElementById('confirmReleaseBtn');

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Transferring Funds via Cashfree...';

    const formData = new FormData(document.getElementById('releasePayoutForm'));

    fetch('<?= base_url('call-verification-payouts/release') ?>', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-send-check me-1"></i> Proceed & Transfer via Cashfree';

        if (data.status) {
          alert(data.message);
          window.location.reload();
        } else {
          alert('Payout Error: ' + data.message);
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-send-check me-1"></i> Proceed & Transfer via Cashfree';
        console.error(err);
        alert('An unexpected error occurred while releasing payout.');
      });
  }

  function viewPendingDetails(execId, execType) {
    execType = execType || 'verification';
    const isRegister = (execType === 'register');
    const modal = new bootstrap.Modal(document.getElementById('payoutDetailsModal'));
    const modalBody = document.getElementById('payoutDetailsModalBody');
    modalBody.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Loading pending records...</p></div>';
    modal.show();

    fetch('<?= base_url('call-verification-payouts/executive-details/') ?>' + execId + '?executive_type=' + execType)
      .then(res => res.json())
      .then(data => {
        if (!data.status) {
          modalBody.innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
          return;
        }

        const isReg = (data.executive_type === 'register');
        const unitName = isReg ? 'Paid Providers' : 'Calls';
        const rateNote = isReg ? '(<15: ₹15 | ≥15: ₹20)' : '(<15: ₹10 | ≥15: ₹15)';

        let breakdownHtml = '';
        if (data.daily_breakdown && data.daily_breakdown.length > 0) {
          breakdownHtml = '<h6 class="fw-bold mb-2">Daily Threshold Calculation ' + rateNote + ':</h6><div class="row g-2 mb-4">';
          data.daily_breakdown.forEach(d => {
            const count = d.daily_calls || d.daily_count || 0;
            const isHigh = isReg ? (count >= 15) : (count >= 15);
            breakdownHtml += `
              <div class="col-md-4">
                <div class="p-3 border rounded-3 bg-light">
                  <div class="small text-muted fw-bold">${d.call_date}</div>
                  <div class="fs-5 fw-bold text-dark">${count} ${unitName}</div>
                  <div class="small text-primary fw-semibold">Rate: ₹${d.rate}/unit (${isHigh ? '≥15 Tier' : '<15 Tier'})</div>
                  <div class="fw-bold text-success mt-1">₹${parseFloat(d.amount).toFixed(2)}</div>
                </div>
              </div>`;
          });
          breakdownHtml += '</div>';
        }

        let callsTableHtml = `
          <h6 class="fw-bold mb-2">${isReg ? 'Paid Provider Conversions' : 'Verified Provider Call Records'} (${data.calls.length}):</h6>
          <div class="table-responsive">
            <table class="table table-sm table-bordered align-middle">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Date</th>
                  <th>Service Provider</th>
                  <th>Service</th>
                  <th>Source</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>`;

        if (data.calls.length === 0) {
          callsTableHtml += '<tr><td colspan="6" class="text-center text-muted">No records found.</td></tr>';
        } else {
          data.calls.forEach((c, idx) => {
            callsTableHtml += `
              <tr>
                <td>${idx + 1}</td>
                <td>${c.call_date}</td>
                <td><strong>${c.provider_name || 'SP #' + c.provider_id}</strong> <br><small class="text-muted">${c.provider_phone || ''}</small></td>
                <td><span class="badge bg-info bg-opacity-10 text-info border">${c.service_name}</span></td>
                <td><code>${c.source_table}</code></td>
                <td>${c.payout_status === 'paid' ? '<span class="badge-status-paid">Paid</span>' : '<span class="badge-status-pending">Pending Release</span>'}</td>
              </tr>`;
          });
        }

        callsTableHtml += '</tbody></table></div>';

        modalBody.innerHTML = `
          <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded-3 border">
            <div>
              <h5 class="fw-bold mb-0 text-dark">${data.executive_name}</h5>
              <div class="d-flex gap-2 align-items-center mt-1">
                <span class="${isReg ? 'badge-role-register' : 'badge-role-verification'}">${data.executive_type_label || (isReg ? 'Registration Executive' : 'Verification Executive')}</span>
                <small class="text-muted">ID: #${data.executive_id}</small>
              </div>
            </div>
            <div class="text-end">
              <div class="fs-4 fw-bold text-success">₹${parseFloat(data.total_amount).toFixed(2)}</div>
              <small class="text-muted">${data.total_calls} ${unitName} Requested</small>
            </div>
          </div>
          ${breakdownHtml}
          ${callsTableHtml}
        `;
      })
      .catch(err => {
        console.error(err);
        modalBody.innerHTML = '<div class="alert alert-danger">Failed to load details.</div>';
      });
  }

  function viewHistoryDetails(payoutId) {
    const modal = new bootstrap.Modal(document.getElementById('payoutDetailsModal'));
    const modalBody = document.getElementById('payoutDetailsModalBody');
    modalBody.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Loading released payout details...</p></div>';
    modal.show();

    fetch('<?= base_url('call-verification-payouts/details/') ?>' + payoutId)
      .then(res => res.json())
      .then(data => {
        if (!data.status) {
          modalBody.innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
          return;
        }

        const p = data.payout;
        const isReg = (p.executive_type === 'register');
        const unitName = isReg ? 'Paid Providers' : 'Calls';
        const rateNote = isReg ? '(<15: ₹15 | ≥15: ₹20)' : '(<15: ₹10 | ≥15: ₹15)';

        let breakdownHtml = '';
        if (p.daily_breakdown && p.daily_breakdown.length > 0) {
          breakdownHtml = '<h6 class="fw-bold mb-2">Daily Threshold Calculation ' + rateNote + ':</h6><div class="row g-2 mb-4">';
          p.daily_breakdown.forEach(d => {
            const count = d.daily_calls || d.daily_count || 0;
            const isHigh = isReg ? (count >= 15) : (count >= 15);
            breakdownHtml += `
              <div class="col-md-4">
                <div class="p-3 border rounded-3 bg-light">
                  <div class="small text-muted fw-bold">${d.call_date}</div>
                  <div class="fs-5 fw-bold text-dark">${count} ${unitName}</div>
                  <div class="small text-primary fw-semibold">Rate: ₹${d.rate}/unit (${isHigh ? '≥15 Tier' : '<15 Tier'})</div>
                  <div class="fw-bold text-success mt-1">₹${parseFloat(d.amount).toFixed(2)}</div>
                </div>
              </div>`;
          });
          breakdownHtml += '</div>';
        }

        let callsTableHtml = `
          <h6 class="fw-bold mb-2">Itemized Release Proof Records (${data.calls.length}):</h6>
          <div class="table-responsive">
            <table class="table table-sm table-bordered align-middle">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Date</th>
                  <th>Service Provider</th>
                  <th>Service</th>
                  <th>Rate Applied</th>
                  <th>Source</th>
                </tr>
              </thead>
              <tbody>`;

        if (data.calls.length === 0) {
          callsTableHtml += '<tr><td colspan="6" class="text-center text-muted">No line-item records found.</td></tr>';
        } else {
          data.calls.forEach((c, idx) => {
            callsTableHtml += `
              <tr>
                <td>${idx + 1}</td>
                <td>${c.call_date}</td>
                <td><strong>${c.provider_name || 'SP #' + c.provider_id}</strong> <br><small class="text-muted">${c.provider_phone || ''}</small></td>
                <td><span class="badge bg-info bg-opacity-10 text-info border">${c.service_name}</span></td>
                <td><strong class="text-success">₹${c.rate}</strong></td>
                <td><code>${c.source_table}</code></td>
              </tr>`;
          });
        }

        callsTableHtml += '</tbody></table></div>';

        modalBody.innerHTML = `
          <div class="p-3 bg-light rounded-3 border mb-3">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h5 class="fw-bold mb-1 text-dark">${p.executive_name}</h5>
                <div class="d-flex gap-2 align-items-center">
                  <span class="${isReg ? 'badge-role-register' : 'badge-role-verification'}">${p.executive_type_label || (isReg ? 'Registration Executive' : 'Verification Executive')}</span>
                  <span class="badge-status-paid">Paid Status</span>
                </div>
              </div>
              <div class="text-end">
                <div class="fs-4 fw-bold text-success">₹${parseFloat(p.total_amount).toFixed(2)}</div>
                <small class="text-muted">${p.total_calls} Total ${unitName} Released</small>
              </div>
            </div>
            <hr class="my-2">
            <div class="row small text-muted">
              <div class="col-md-6"><strong>Txn Reference:</strong> ${p.transaction_ref || 'N/A'}</div>
              <div class="col-md-6 text-end"><strong>Release Date:</strong> ${p.payout_date}</div>
            </div>
            ${p.remarks ? `<div class="small text-muted mt-1"><strong>Remarks:</strong> ${p.remarks}</div>` : ''}
          </div>
          ${breakdownHtml}
        `;
      })
      .catch(err => {
        console.error(err);
        modalBody.innerHTML = '<div class="alert alert-danger">Failed to load payout receipt.</div>';
      });
  }

  function viewWalletLedger(execId) {
    const modal = new bootstrap.Modal(document.getElementById('walletLedgerModal'));
    $('#ledger_transactions_tbody').html('<tr><td colspan="5" class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm text-primary me-2"></div> Loading wallet ledger...</td></tr>');
    modal.show();

    fetch('<?= base_url('call-verification-payouts/wallet-ledger/') ?>/' + execId)
      .then(res => res.json())
      .then(data => {
        if (!data.status) {
          $('#ledger_transactions_tbody').html('<tr><td colspan="5" class="text-center text-danger py-4">Failed to load wallet ledger.</td></tr>');
          return;
        }

        const isReg = (data.executive_type === 'register');
        $('#ledger_exec_name').text(data.executive_name);
        $('#ledger_exec_subtitle').text('Executive ID #' + data.executive_id + ' • ' + (data.executive_type_label || (isReg ? 'Registration Executive Wallet' : 'Verification Executive Wallet')));
        $('#ledger_type_badge').text(isReg ? 'Registration Executive' : 'Verification Executive')
          .removeClass('badge-role-register badge-role-verification')
          .addClass(isReg ? 'badge-role-register' : 'badge-role-verification');

        $('#ledger_balance').text('₹' + parseFloat(data.wallet.balance).toFixed(2));
        $('#ledger_earned').text('₹' + parseFloat(data.wallet.total_earned).toFixed(2));

        let html = '';
        if (data.transactions.length === 0) {
          html = '<tr><td colspan="5" class="text-center py-4 text-muted"><i class="bi bi-wallet2 d-block mb-1 fs-4 text-secondary"></i>No wallet transactions found for this executive.</td></tr>';
        } else {
          data.transactions.forEach(t => {
            const isCredit = t.type === 'credit';
            const badgeClass = isCredit ? 'bg-success' : 'bg-danger';
            const amountPrefix = isCredit ? '+₹' : '-₹';
            const amountColor = isCredit ? 'text-success' : 'text-danger';

            html += `
              <tr>
                <td class="ps-3 small text-muted">${t.created_at}</td>
                <td><span class="badge ${badgeClass} text-white px-2 py-1 small">${t.type.toUpperCase()}</span></td>
                <td class="text-end fw-bold ${amountColor}">${amountPrefix}${parseFloat(t.amount).toFixed(2)}</td>
                <td class="text-end fw-bold text-dark">₹${parseFloat(t.balance_after).toFixed(2)}</td>
                <td class="pe-3">
                  <div class="fw-semibold text-dark small">${t.description}</div>
                  <small class="text-muted" style="font-size:0.75rem;">Ref: <code>${t.reference_no || 'N/A'}</code></small>
                </td>
              </tr>
            `;
          });
        }
        $('#ledger_transactions_tbody').html(html);
      })
      .catch(err => {
        console.error(err);
        $('#ledger_transactions_tbody').html('<tr><td colspan="5" class="text-center text-danger py-4">Error fetching wallet ledger data.</td></tr>');
      });
  }

  // -------------------------------------------------------------
  // Bank Account & OTP Handlers
  // -------------------------------------------------------------
  window.openExecutiveBankModal = function(executiveId, executiveName) {
    const apiBase = (typeof baseUrl !== 'undefined' ? baseUrl : '<?= base_url() ?>/');
    $('#bankModalExecId').val(executiveId);
    $('#bankModalExecName').text(executiveName + ' - Payout Bank Account');
    $('#bankFormStep').show();
    $('#otpFormStep').hide();
    $('#inputBankOtpCode').val('');

    // Reset form
    $('#inputBankName').val('');
    $('#inputAccountHolder').val('');
    $('#inputAccountNumber').val('');
    $('#inputConfirmAccountNumber').val('');
    $('#inputIfscCode').val('');
    $('#inputUpiId').val('');

    const modalElem = document.getElementById('executiveBankModal');
    if (modalElem) {
      if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        const modal = bootstrap.Modal.getInstance(modalElem) || new bootstrap.Modal(modalElem);
        modal.show();
      } else if (typeof $ !== 'undefined' && $.fn.modal) {
        $('#executiveBankModal').modal('show');
      }
    }

    // Fetch current saved details
    $.getJSON(apiBase + 'call-verification-payouts/bank-details/' + executiveId, function(res) {
      if (res && res.status && res.bank_details) {
        const b = res.bank_details;
        $('#bankDetailsStatusCard').show();
        $('#viewBankName').text(b.bank_name || '--');
        $('#viewAccountHolder').text(b.account_holder_name || '--');
        $('#viewAccountNumber').text(b.account_number || '--');
        $('#viewIfscCode').text(b.ifsc_code || '--');
        $('#viewUpiId').text(b.upi_id || '--');
        $('#bankLastUpdated').text('Updated: ' + (b.updated_at || b.created_at || ''));

        // Pre-fill form
        $('#inputBankName').val(b.bank_name || '');
        $('#inputAccountHolder').val(b.account_holder_name || '');
        $('#inputAccountNumber').val(b.account_number || '');
        $('#inputConfirmAccountNumber').val(b.account_number || '');
        $('#inputIfscCode').val(b.ifsc_code || '');
        $('#inputUpiId').val(b.upi_id || '');
      } else {
        $('#bankDetailsStatusCard').hide();
      }
    }).fail(function(err) {
      console.error('Failed to fetch bank details:', err);
      $('#bankDetailsStatusCard').hide();
    });
  };

  window.triggerSendBankOtp = function() {
    const apiBase = (typeof baseUrl !== 'undefined' ? baseUrl : '<?= base_url() ?>/');
    const acc = $('#inputAccountNumber').val().trim();
    const accConfirm = $('#inputConfirmAccountNumber').val().trim();

    if (acc !== accConfirm) {
      alert('Bank Account Number and Confirm Account Number do not match!');
      return;
    }

    const payload = {
      executive_id: $('#bankModalExecId').val(),
      bank_name: $('#inputBankName').val().trim(),
      account_number: acc,
      ifsc_code: $('#inputIfscCode').val().trim(),
      account_holder_name: $('#inputAccountHolder').val().trim(),
      upi_id: $('#inputUpiId').val().trim(),
    };

    $('#btnSendBankOtp').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Sending OTP...');

    $.post(apiBase + 'call-verification-payouts/send-bank-otp', payload, function(res) {
      $('#btnSendBankOtp').prop('disabled', false).html('<i class="bi bi-shield-lock me-1"></i> Send OTP Verification Code');

      if (res.status) {
        $('#bankFormStep').hide();
        $('#otpFormStep').show();
        if (res.demo_otp) {
          $('#otpDemoDisplay').text('Verification Code: ' + res.demo_otp);
          $('#inputBankOtpCode').val(res.demo_otp);
        }
        alert(res.message);
      } else {
        alert(res.message || 'Failed to send OTP code.');
      }
    }).fail(function() {
      $('#btnSendBankOtp').prop('disabled', false).html('<i class="bi bi-shield-lock me-1"></i> Send OTP Verification Code');
      alert('Server error sending OTP code.');
    });
  };

  window.cancelOtpStep = function() {
    $('#otpFormStep').hide();
    $('#bankFormStep').show();
  };

  window.triggerVerifyBankOtp = function() {
    const apiBase = (typeof baseUrl !== 'undefined' ? baseUrl : '<?= base_url() ?>/');
    const otp = $('#inputBankOtpCode').val().trim();
    if (!otp) {
      alert('Please enter 6-digit OTP code.');
      return;
    }

    const payload = {
      executive_id: $('#bankModalExecId').val(),
      otp_code: otp,
    };

    $('#btnVerifyBankOtp').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Verifying...');

    $.post(apiBase + 'call-verification-payouts/verify-bank-otp', payload, function(res) {
      $('#btnVerifyBankOtp').prop('disabled', false).html('<i class="bi bi-check-circle me-1"></i> Verify OTP & Save Bank Details');

      if (res.status) {
        alert(res.message);
        const modalElem = document.getElementById('executiveBankModal');
        if (modalElem && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
          const modal = bootstrap.Modal.getInstance(modalElem);
          if (modal) modal.hide();
        } else if (typeof $ !== 'undefined' && $.fn.modal) {
          $('#executiveBankModal').modal('hide');
        }
        location.reload();
      } else {
        alert(res.message || 'OTP verification failed.');
      }
    }).fail(function() {
      $('#btnVerifyBankOtp').prop('disabled', false).html('<i class="bi bi-check-circle me-1"></i> Verify OTP & Save Bank Details');
      alert('Server error verifying OTP code.');
    });
  };
</script>
<?= $this->endSection(); ?>