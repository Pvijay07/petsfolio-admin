<?php echo $this->extend('admin/includes/header'); ?>
<!--start content-->
<style>
  /* Prevent body scroll when modal is open */
  body.modal-open {
    overflow: hidden !important;
    padding-right: 0 !important;
  }

  /* Custom scrollbar */
  .modal-body::-webkit-scrollbar {
    width: 8px;
  }

  .modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
  }

  .modal-body::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
  }

  .modal-body::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
  }

  /* Status filter button active state */
  .status-filter-btn.active .badge {
    background-color: rgba(220, 53, 69, 0.15) !important;
    color: #dc3545 !important;
    border: 1px solid rgba(220, 53, 69, 0.2) !important;
  }
<<<<<<< HEAD
</style>
<?php echo $this->section('content'); ?>
=======

  /* Compact table styling */
  .table thead th {
    padding-top: 0.6rem !important;
    padding-bottom: 0.6rem !important;
    font-size: 0.75rem !important;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    white-space: nowrap;
  }

  .table td {
    padding: 0.5rem 0.5rem !important;
    font-size: 0.85rem !important;
    vertical-align: middle;
  }

  .badge {
    padding: 0.4rem 0.6rem !important;
    font-size: 0.7rem !important;
  }

  .btn-sm {
    padding: 0.25rem 0.6rem !important;
    font-size: 0.75rem !important;
  }

  /* Loader Overlay Styling */
  #loader-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(4px);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 99999;
    transition: all 0.3s ease;
  }

  .loader-content {
    text-align: center;
    background: white;
    padding: 2.5rem;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.5rem;
  }

  .loader-spinner {
    width: 60px;
    height: 60px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #4f46e5;
    border-radius: 50%;
    animation: spin 1s cubic-bezier(0.68, -0.55, 0.27, 1.55) infinite;
  }

  .loader-text {
    font-weight: 600;
    color: #4b5563;
    font-size: 1.1rem;
    letter-spacing: -0.01em;
  }

  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }
</style>
<?php echo $this->section('content'); ?>
<!-- Loader Overlay -->
<div id="loader-overlay">
  <div class="loader-content">
    <div class="loader-spinner"></div>
    <div class="loader-text">Processing Request...</div>
  </div>
</div>
>>>>>>> origin/main

<main class="main-content">
  <div class="position-relative iq-banner">
    <?php echo $this->include('admin/nav_bar') ?>
  </div>

  <div class="conatiner-fluid content-inner pb-0 px-4" id="page_layout">
    <!-- Modern Header Section -->
    <div class="container-fluid p-0 mb-4">
      <div class="bg-gradient-primary text-white p-5 rounded-3 shadow position-relative overflow-hidden">
        <div class="position-relative z-1">
          <h1 class="h2 fw-bold mb-2 text-white">Payment Management</h1>
          <p class="text-white-75 mb-0">Track and manage payout requests, process payments, and monitor transactions</p>
        </div>
        <i class="bi bi-cash-stack position-absolute end-0 bottom-0 opacity-10"
          style="font-size: 14rem; transform: rotate(12deg); margin-right: -3rem; margin-bottom: -3rem;"></i>
      </div>
    </div>

    <!-- Statistics Cards - Updated to match theme -->
    <div class="row mb-4">
      <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 mb-0">
          <div class="card-body p-4">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0 me-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                  style="width: 50px; height: 50px; background: rgba(245, 158, 11, 0.1);">
                  <i class="fas fa-clock fa-lg" style="color: #f59e0b;"></i>
                </div>
              </div>
              <div class="flex-grow-1">
                <h6 class="text-muted mb-1">Pending Requests</h6>
                <h3 class="fw-bold mb-0" id="stat_pending" style="color: #f59e0b;">0</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 mb-0">
          <div class="card-body p-4">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0 me-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                  style="width: 50px; height: 50px; background: rgba(59, 130, 246, 0.1);">
                  <i class="fas fa-spinner fa-lg" style="color: #3b82f6;"></i>
                </div>
              </div>
              <div class="flex-grow-1">
                <h6 class="text-muted mb-1">In Progress</h6>
                <h3 class="fw-bold mb-0" id="stat_progress" style="color: #3b82f6;">0</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 mb-0">
          <div class="card-body p-4">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0 me-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                  style="width: 50px; height: 50px; background: rgba(34, 197, 94, 0.1);">
                  <i class="fas fa-check-circle fa-lg" style="color: #22c55e;"></i>
                </div>
              </div>
              <div class="flex-grow-1">
                <h6 class="text-muted mb-1">Completed</h6>
                <h3 class="fw-bold mb-0" id="stat_completed" style="color: #22c55e;">₹0</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 mb-0">
          <div class="card-body p-4">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0 me-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                  style="width: 50px; height: 50px; background: rgba(239, 68, 68, 0.1);">
                  <i class="fas fa-money-bill-wave fa-lg" style="color: #ef4444;"></i>
                </div>
              </div>
              <div class="flex-grow-1">
                <h6 class="text-muted mb-1">Pending Amount</h6>
                <h3 class="fw-bold mb-0" id="stat_pending_amt" style="color: #ef4444;">₹0</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Search and Filter Card -->
    <div class="card border-0 shadow-sm mb-4 rounded-3">
      <div class="card-body p-3">
        <div class="row g-3 align-items-center">
          <div class="col-md-8">
            <div class="input-group">
              <span class="input-group-text bg-black border-end-0 text-white">
                <i class="bi bi-search"></i>
              </span>
              <input type="text" class="form-control border-start-0" placeholder="Search payments..."
                id="searchPayments">
            </div>
          </div>
          <div class="col-md-2">
            <button class="btn btn-black bg-black p-2 text-white w-100" onclick="applyPaymentFilters()">
              <i class="bi bi-funnel me-1"></i>Filter
            </button>
          </div>
          <div class="col-md-2">
            <button class="btn btn-black bg-black p-2 text-white w-100" onclick="clearPaymentFilters()">
              <i class="bi bi-x-circle me-1"></i>Clear
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="card mb-2">
      <ul class="nav nav-tabs mb-0 p-2  gap-2 rounded-5" id="paymentTabs" role="tablist">
        <li class="nav-item mb-0" role="presentation">
          <button class="nav-link active text-indigo-600 fw-bold mb-0" id="requests-tab" data-bs-toggle="tab"
            data-bs-target="#requests" type="button" role="tab">
            <i class="bi bi-clock me-1"></i>Payment Requests
            <!-- <span class="badge ms-1 text-white" id="pending_count">0</span> -->
          </button>
        </li>
        <li class="nav-item mb-0" role="presentation">
          <button class="nav-link text-indigo-600 fw-bold mb-0" id="progress-tab" data-bs-toggle="tab"
            data-bs-target="#progress" type="button" role="tab">
            <i class="bi bi-arrow-repeat me-1"></i>In Progress
            <!-- <span class="badge ms-1 text-white" id="progress_count">0</span> -->
          </button>
        </li>
        <li class="nav-item mb-0" role="presentation">
          <button class="nav-link text-indigo-600 fw-bold mb-0" id="completed-tab" data-bs-toggle="tab"
            data-bs-target="#completed" type="button" role="tab">
            <i class="bi bi-check-circle me-1"></i>Completed
          </button>
        </li>
<<<<<<< HEAD
=======
        <li class="nav-item mb-0" role="presentation">
          <button class="nav-link text-indigo-600 fw-bold mb-0" id="rejected-tab" data-bs-toggle="tab"
            data-bs-target="#rejected" type="button" role="tab">
            <i class="bi bi-x-circle me-1"></i>Rejected
          </button>
        </li>
>>>>>>> origin/main
      </ul>
    </div>

    <!-- Main Card -->
    <div class="card border-0 shadow-sm overflow-hidden rounded-3">
      <div class="card-header p-3" style="background: #c6d2ff !important; border-bottom: 2px solid #a3b4ff;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
          <div class="mb-2 mb-md-0">
            <h5 class="card-title mb-0 text-black fw-bold">
              <i class="bi bi-cash-coin me-2"></i>Payment Requests Management
            </h5>
          </div>
          <div class="d-flex gap-2">
            <button class="btn btn-black bg-black p-2 text-white" id="bulkProcessBtn" disabled>
              <i class="fas fa-money-check me-1"></i> Process Selected
            </button>
            <button class="btn btn-black bg-black p-2 text-white" id="refreshStatsBtn">
              <i class="fas fa-sync me-1"></i> Refresh
            </button>
          </div>
        </div>
      </div>


      <div class="card-body p-0">
        <div class="tab-content" id="paymentTabsContent">
          <div class="tab-pane fade show active" id="requests" role="tabpanel">
            <div class="table-responsive">
              <table class="table table-hover mb-0" id="requestsTable">
                <thead class="" style="background: #c6d2ff !important;">
                  <tr>
<<<<<<< HEAD
                    <th class="py-3 text-black fw-semibold border-bottom text-center"
                      style="background: #c6d2ff !important; width: 50px;">
                      <input type="checkbox" id="selectAll" class="form-check-input m-0">
                    </th>
=======

>>>>>>> origin/main
                    <th class="py-3 text-black fw-semibold border-bottom text-center"
                      style="background: #c6d2ff !important;">S.No.</th>
                    <th class="py-3 text-black fw-semibold border-bottom" style="background: #c6d2ff !important;">Name
                    </th>
                    <th class="py-3 text-black fw-semibold border-bottom" style="background: #c6d2ff !important;">Mobile
                    </th>
                    <th class="py-3 text-black fw-semibold border-bottom" style="background: #c6d2ff !important;">
                      Request Date</th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center"
                      style="background: #c6d2ff !important;">Amount</th>
                    <th class="py-3 text-black fw-semibold border-bottom" style="background: #c6d2ff !important;">Bank
                      Details</th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center"
                      style="background: #c6d2ff !important; width: 180px;">Actions</th>
                  </tr>
                </thead>
                <tbody class="border-top-0">
                  <?php
                  $i = 1;
                  foreach ($payments as $payment) {
<<<<<<< HEAD
                    if ($payment->status !== 'completed' && $payment->status !== 'in_progress') {
=======
                    if ($payment->status !== 'completed' && $payment->status !== 'in_progress' && $payment->status !== 'rejected') {
>>>>>>> origin/main
                      $receipt = !empty($payment->receipt) ?
                        base_url('public/uploads/receipts/' . $payment->receipt) : '';

                      // Get account holder name
                      $accountHolderName = !empty($payment->account_holder_name) ?
                        $payment->account_holder_name : (!empty($payment->aadhar_name) ? $payment->aadhar_name : 'N/A');

                      // Get bank details safely
                      $accountNumber = !empty($payment->account_number) ? $payment->account_number : 'N/A';
                      $ifscCode = !empty($payment->ifsc_code) ? $payment->ifsc_code : 'N/A';
                      $phone = !empty($payment->phone) ? $payment->phone : 'N/A';
                      $name = !empty($payment->name) ? $payment->name : 'N/A';
                      $amount = !empty($payment->amount) ? $payment->amount : 0;
                      $requestedDate = !empty($payment->requested_at) ?
                        date('d M Y', strtotime($payment->requested_at)) : 'N/A';
                  ?>
                      <tr data-id="<?= $payment->id ?>">
<<<<<<< HEAD
                        <td class="text-center py-2">
                          <input type="checkbox" class="payment-checkbox form-check-input m-0" value="<?= $payment->id ?>"
                            <?= !empty($receipt) && $receipt != base_url('public/uploads/receipts/') ? '' : 'disabled' ?>>
                        </td>
=======

>>>>>>> origin/main
                        <td class="text-center py-2">
                          <span class="text-dark fw-medium"><?= $i ?></span>
                        </td>
                        <td class="py-2">
                          <div class="fw-bold text-dark"><?= htmlspecialchars($name) ?></div>
                        </td>
                        <td class="py-2">
                          <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-telephone me-1"></i>
                            <span class="text-dark"><?= htmlspecialchars($phone) ?></span>
                          </div>
                        </td>
<<<<<<< HEAD
                        <td class="py-2">
=======
                        <td class="py-2" data-order="<?= strtotime($payment->requested_at) ?>">
>>>>>>> origin/main
                          <span
                            class="badge rounded-pill p-2 border-0 d-flex align-items-center gap-1 justify-content-center"
                            style="background: rgba(99, 102, 241, 0.1) !important; color: #6366f1 !important;
                                   border: 1px solid rgba(99, 102, 241, 0.2) !important; font-weight: 600;">
                            <i class="bi bi-calendar"></i>
                            <?= $requestedDate ?>
                          </span>
                        </td>
                        <td class="py-2 text-center">
                          <div class="fw-bold text-primary fs-6">
                            ₹<?= number_format($amount, 2) ?>
                          </div>
                        </td>
                        <td class="py-2">
                          <div class="small">
                            <div class="mb-1">
                              <span class="fw-medium text-dark">Name:</span>
                              <span class="text-muted"><?= htmlspecialchars($accountHolderName) ?></span>
                            </div>
                            <div class="mb-1">
                              <span class="fw-medium text-dark">Account:</span>
                              <span class="text-muted"><?= htmlspecialchars($accountNumber) ?></span>
                            </div>
                            <div>
                              <span class="fw-medium text-dark">IFSC:</span>
                              <span class="text-muted"><?= htmlspecialchars($ifscCode) ?></span>
                            </div>
                          </div>
                        </td>
                        <td class="py-2 text-center">
                          <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-sm process-payout" data-id="<?= $payment->id ?>"
                              data-amount="<?= $amount ?>" data-provider="<?= $payment->provider_id ?? '' ?>" style="background: rgba(34, 197, 94, 0.1) !important; color: #16a34a !important;
                                           border: 1px solid rgba(34, 197, 94, 0.2) !important;">
                              <i class="fas fa-check me-1"></i>Approve
                            </button>
                            <button class="btn btn-sm reject-payment" data-id="<?= $payment->id ?>"
                              data-amount="<?= $amount ?>" data-provider="<?= $payment->provider_id ?? '' ?>" style="background: rgba(239, 68, 68, 0.1) !important; color: #dc3545 !important;
                                           border: 1px solid rgba(239, 68, 68, 0.2) !important;">
                              <i class="fas fa-times me-1"></i>Reject
                            </button>
                          </div>
                        </td>
                      </tr>
                  <?php
                      $i++;
                    }
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- In Progress Tab - Updated with proper UTR handling -->
          <div class="tab-pane fade" id="progress" role="tabpanel">
            <div class="table-responsive">
              <table class="table table-hover mb-0" id="progressTable">
                <thead class="" style="background: #c6d2ff !important;">
                  <tr>
                    <th class="py-3 text-black fw-semibold border-bottom text-center"
                      style="background: #c6d2ff !important;">S.No.</th>
                    <th class="py-3 text-black fw-semibold border-bottom" style="background: #c6d2ff !important;">Name
                    </th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center"
                      style="background: #c6d2ff !important;">Amount</th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center" style="background: #c6d2ff !important;">
                      Initiated Date</th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center" style="background: #c6d2ff !important;">
                      Transfer ID</th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center" style="background: #c6d2ff !important;">UTR
                    </th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center"
                      style="background: #c6d2ff !important; width: 150px;">Actions</th>
                  </tr>
                </thead>
                <tbody class="border-top-0">
                  <?php
                  $j = 1;
                  foreach ($payments as $payment) {
                    if ($payment->status === 'in_progress') {
                      // Safely get all values
                      $name = !empty($payment->name) ? $payment->name : 'N/A';
                      $amount = !empty($payment->amount) ? $payment->amount : 0;

                      // Get transfer ID
                      $transferId = !empty($payment->cf_transfer_id) ? $payment->cf_transfer_id : (!empty($payment->cf_reference_id) ? $payment->cf_reference_id : 'N/A');

                      // Get UTR safely
                      $utr = !empty($payment->utr) ? $payment->utr : null;

                      // Get initiated date
                      $initiatedDate = !empty($payment->payout_initiated_at) ?
                        date('d M Y H:i', strtotime($payment->payout_initiated_at)) : (!empty($payment->requested_at) ?
                          date('d M Y H:i', strtotime($payment->requested_at)) : 'N/A');
                  ?>
                      <tr>
                        <td class="text-center py-2">
                          <span class="text-dark fw-medium"><?= $j ?></span>
                        </td>
                        <td class="py-2">
                          <div class="fw-bold text-dark"><?= htmlspecialchars($name) ?></div>
                        </td>
                        <td class="py-2 text-center">
                          <div class="fw-bold text-primary fs-6">
                            ₹<?= number_format($amount, 2) ?>
                          </div>
                        </td>
<<<<<<< HEAD
                        <td class="py-2">
=======
                        <td class="py-2" data-order="<?= strtotime($payment->payout_initiated_at ?? $payment->requested_at) ?>">
>>>>>>> origin/main
                          <span class="badge rounded-pill p-2 border-0 d-flex align-items-center gap-1" style="background: rgba(99, 102, 241, 0.1) !important; color: #6366f1 !important;
                                   border: 1px solid rgba(99, 102, 241, 0.2) !important; font-weight: 600; width: max-content; margin: 0 auto;">
                            <i class="bi bi-calendar"></i>
                            <?= $initiatedDate ?>
                          </span>
                        </td>
                        <td class="py-2">
                          <code class="text-dark"><?= htmlspecialchars($transferId) ?></code>
                        </td>
                        <td class="py-2">
                          <?php if (!empty($utr)): ?>
                            <span class="badge rounded-pill p-2 border-0" style="background: rgba(34, 197, 94, 0.1) !important; color: #16a34a !important;
                                       border: 1px solid rgba(34, 197, 94, 0.2) !important; font-weight: 600; width: max-content; margin: 0 auto;">
                              <?= htmlspecialchars($utr) ?>
                            </span>
                          <?php else: ?>
                            <span class="badge rounded-pill p-2 border-0" style="background: rgba(245, 158, 11, 0.1) !important; color: #f59e0b !important;
                                       border: 1px solid rgba(245, 158, 11, 0.2) !important; font-weight: 600; width: max-content; margin: 0 auto;">
                              Pending
                            </span>
                          <?php endif; ?>
                        </td>
                        <td class="py-2 text-center">
<<<<<<< HEAD
                          <button class="btn btn-sm update-payout-status"
                            data-id="<?= $payment->id ?>"
                            data-current-status="<?= $payment->status ?>"
                            data-amount="<?= $payment->amount ?>"
                            data-provider="<?= $payment->user_id ?>"
                            style="background: rgba(59, 130, 246, 0.1) !important; color: #3b82f6 !important;
                                        border: 1px solid rgba(59, 130, 246, 0.2) !important;">
                            <i class="fas fa-edit me-1"></i>Update Status
=======
                          <button class="btn btn-sm check-status" data-id="<?= $payment->id ?>" style="background: rgba(59, 130, 246, 0.1) !important; color: #3b82f6 !important;
                                       border: 1px solid rgba(59, 130, 246, 0.2) !important;">
                            <i class="fas fa-sync me-1"></i>Check Status
>>>>>>> origin/main
                          </button>
                        </td>
                      </tr>
                  <?php
                      $j++;
                    }
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Completed Tab - Updated with proper UTR handling -->
          <div class="tab-pane fade" id="completed" role="tabpanel">
            <div class="table-responsive">
              <table class="table table-hover mb-0" id="completedTable">
                <thead class="" style="background: #c6d2ff !important;">
                  <tr>
                    <th class="py-3 text-black fw-semibold border-bottom text-center"
                      style="background: #c6d2ff !important;">S.No.</th>
                    <th class="py-3 text-black fw-semibold border-bottom" style="background: #c6d2ff !important;">Name
                    </th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center"
                      style="background: #c6d2ff !important;">Amount</th>
                    <th class="py-3 text-black fw-semibold border-bottom" style="background: #c6d2ff !important;">
                      Request Date</th>
                    <th class="py-3 text-black fw-semibold border-bottom" style="background: #c6d2ff !important;">
                      Completed Date</th>
                    <th class="py-3 text-black fw-semibold border-bottom" style="background: #c6d2ff !important;">UTR
                    </th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center"
                      style="background: #c6d2ff !important; width: 100px;">Receipt</th>
                  </tr>
                </thead>
                <tbody class="border-top-0">
                  <?php
                  $k = 1;
                  foreach ($payments as $payment) {
                    if ($payment->status === 'completed') {
                      // Safely get all values
                      $name = !empty($payment->name) ? $payment->name : 'N/A';
                      $amount = !empty($payment->amount) ? $payment->amount : 0;

                      // Get UTR safely
                      $utr = !empty($payment->utr) ? $payment->utr : 'N/A';

                      // Get dates safely
                      $requestedDate = !empty($payment->requested_at) ?
                        date('d M Y', strtotime($payment->requested_at)) : 'N/A';

                      $completedDate = !empty($payment->processed_at) ?
                        date('d M Y H:i', strtotime($payment->processed_at)) : 'N/A';

                      // Get receipt
                      $receipt = !empty($payment->receipt) && file_exists(FCPATH . 'public/uploads/receipts/' . $payment->receipt) ?
                        base_url('public/uploads/receipts/' . $payment->receipt) : '';
                  ?>
                      <tr>
                        <td class="text-center py-2">
                          <span class="text-dark fw-medium"><?= $k ?></span>
                        </td>
                        <td class="py-2">
                          <div class="fw-bold text-dark"><?= htmlspecialchars($name) ?></div>
                        </td>
                        <td class="py-2 text-center">
                          <div class="fw-bold text-primary fs-6">
                            ₹<?= number_format($amount, 2) ?>
                          </div>
                        </td>
<<<<<<< HEAD
                        <td class="py-2">
=======
                        <td class="py-2" data-order="<?= strtotime($payment->requested_at) ?>">
>>>>>>> origin/main
                          <span class="badge rounded-pill p-2 border-0 d-flex align-items-center gap-1" style="background: rgba(99, 102, 241, 0.1) !important; color: #6366f1 !important;
                                   border: 1px solid rgba(99, 102, 241, 0.2) !important; font-weight: 600;">
                            <i class="bi bi-calendar"></i>
                            <?= $requestedDate ?>
                          </span>
                        </td>
<<<<<<< HEAD
                        <td class="py-2">
=======
                        <td class="py-2" data-order="<?= strtotime($payment->processed_at) ?>">
>>>>>>> origin/main
                          <span class="badge rounded-pill p-2 border-0 d-flex align-items-center gap-1" style="background: rgba(34, 197, 94, 0.1) !important; color: #16a34a !important;
                                   border: 1px solid rgba(34, 197, 94, 0.2) !important; font-weight: 600;">
                            <i class="bi bi-check-circle"></i>
                            <?= $completedDate ?>
                          </span>
                        </td>
                        <td class="py-2">
                          <?php if ($utr !== 'N/A' && !empty($utr)): ?>
                            <code class="text-dark"><?= htmlspecialchars($utr) ?></code>
                          <?php else: ?>
                            <span class="text-muted">N/A</span>
                          <?php endif; ?>
                        </td>
                        <td class="py-2 text-center">
                          <?php if (!empty($receipt)): ?>
                            <a href="<?= htmlspecialchars($receipt) ?>" target="_blank" class="btn btn-sm" style="background: rgba(59, 130, 246, 0.1) !important; color: #3b82f6 !important;
                                      border: 1px solid rgba(59, 130, 246, 0.2) !important;">
                              <i class="fas fa-file me-1"></i>View
                            </a>
                          <?php else: ?>
                            <span class="text-muted">N/A</span>
                          <?php endif; ?>
                        </td>
                      </tr>
                  <?php
                      $k++;
                    }
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
<<<<<<< HEAD
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center px-2 py-3 border-top">
          <div class="text-muted small">
            Showing entries
          </div>
          <div class="btn-group mt-0 pt-0 border-0">
            <button class="btn btn-outline-primary btn-sm">
              <i class="bi bi-chevron-left"></i> Previous
            </button>
            <button class="btn btn-primary btn-sm">1</button>
            <button class="btn btn-outline-primary btn-sm">
              Next <i class="bi bi-chevron-right"></i>
            </button>
          </div>
        </div>
=======

          <!-- Rejected Tab -->
          <div class="tab-pane fade" id="rejected" role="tabpanel">
            <div class="table-responsive">
              <table class="table table-hover mb-0" id="rejectedTable">
                <thead class="" style="background: #c6d2ff !important;">
                  <tr>
                    <th class="py-3 text-black fw-semibold border-bottom text-center"
                      style="background: #c6d2ff !important;">S.No.</th>
                    <th class="py-3 text-black fw-semibold border-bottom" style="background: #c6d2ff !important;">Name
                    </th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center"
                      style="background: #c6d2ff !important;">Amount</th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center" style="background: #c6d2ff !important;">
                      Request Date</th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center" style="background: #c6d2ff !important;">
                      Rejection Date</th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center"
                      style="background: #c6d2ff !important;">Status</th>
                  </tr>
                </thead>
                <tbody class="border-top-0">
                  <?php
                  $l = 1;
                  foreach ($payments as $payment) {
                    if ($payment->status === 'rejected') {
                      $name = !empty($payment->name) ? $payment->name : 'N/A';
                      $amount = !empty($payment->amount) ? $payment->amount : 0;
                      $requestedDate = !empty($payment->requested_at) ?
                        date('d M Y', strtotime($payment->requested_at)) : 'N/A';
                      $rejectionDate = !empty($payment->updated_at) ?
                        date('d M Y H:i', strtotime($payment->updated_at)) : 'N/A';
                  ?>
                      <tr>
                        <td class="text-center py-2">
                          <span class="text-dark fw-medium"><?= $l ?></span>
                        </td>
                        <td class="py-2">
                          <div class="fw-bold text-dark"><?= htmlspecialchars($name) ?></div>
                        </td>
                        <td class="py-2 text-center">
                          <div class="fw-bold text-danger fs-6">
                            ₹<?= number_format($amount, 2) ?>
                          </div>
                        </td>
                        <td class="py-2 text-center" data-order="<?= strtotime($payment->requested_at) ?>">
                          <span class="badge rounded-pill p-2 border-0 d-flex align-items-center gap-1 justify-content-center" style="background: rgba(99, 102, 241, 0.1) !important; color: #6366f1 !important;
                                   border: 1px solid rgba(99, 102, 241, 0.2) !important; font-weight: 600;">
                            <i class="bi bi-calendar"></i>
                            <?= $requestedDate ?>
                          </span>
                        </td>
                        <td class="py-2 text-center" data-order="<?= strtotime($payment->updated_at) ?>">
                          <span class="badge rounded-pill p-2 border-0 d-flex align-items-center gap-1 justify-content-center" style="background: rgba(239, 68, 68, 0.1) !important; color: #ef4444 !important;
                                   border: 1px solid rgba(239, 68, 68, 0.2) !important; font-weight: 600;">
                            <i class="bi bi-calendar-x"></i>
                            <?= $rejectionDate ?>
                          </span>
                        </td>
                        <td class="py-2 text-center">
                          <span class="badge bg-danger">Rejected</span>
                        </td>
                      </tr>
                  <?php
                      $l++;
                    }
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

>>>>>>> origin/main
      </div>
    </div>
  </div>
</main>
<<<<<<< HEAD
=======
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- DataTables CSS/JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
>>>>>>> origin/main

<!-- SweetAlert CSS/JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- Custom Styles -->
<style>
  /* Consistent with your theme */
  .bg-gradient-primary {
    background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
  }

  .table-hover tbody tr:hover {
    background-color: rgba(99, 102, 241, 0.03) !important;
  }

  .table thead th {
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    padding-top: 1rem;
    padding-bottom: 1rem;
  }

  .nav-tabs .nav-link {
    border: none;
    border-radius: 50px;
    padding: 0.75rem 1.5rem;
    color: #6c757d;
    background: transparent;
    transition: all 0.3s ease;
  }



  .nav-tabs .nav-link.active {
    background: #3f30e3;
    color: #ffffff;
    border-bottom: 1px solid #6366f1;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
  }


  .btn {
    font-weight: 500;
    letter-spacing: 0.02em;
  }

  .btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
  }

  .badge {
    font-weight: 500;
    letter-spacing: 0.02em;
  }

  .form-check-input:checked {
    background-color: #6366f1;
    border-color: #6366f1;
  }

  /* Card hover effect */
  .card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 10px -5px rgba(0, 0, 0, 0.04) !important;
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .card-header .d-flex {
      flex-direction: column;
      align-items: flex-start !important;
    }

    .card-header .btn {
      margin-top: 0.5rem;
      width: 100%;
    }

    .table-responsive {
      font-size: 0.875rem;
    }

    .btn-sm {
      padding: 0.25rem 0.5rem;
      font-size: 0.75rem;
    }

    /* Hide some columns on mobile */
    #requestsTable th:nth-child(4),
    #requestsTable td:nth-child(4),
    #requestsTable th:nth-child(6),
    #requestsTable td:nth-child(6) {
      display: none !important;
    }

    #progressTable th:nth-child(4),
    #progressTable td:nth-child(4),
    #progressTable th:nth-child(5),
    #progressTable td:nth-child(5) {
      display: none !important;
    }

    #completedTable th:nth-child(4),
    #completedTable td:nth-child(4),
    #completedTable th:nth-child(5),
    #completedTable td:nth-child(5) {
      display: none !important;
    }
  }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
  integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
  crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
  function preventBodyScroll() {
    document.body.classList.add('modal-open');
  }

  function allowBodyScroll() {
    document.body.classList.remove('modal-open');
  }

<<<<<<< HEAD
  $(document).ready(function() {
    // Initialize DataTables with your theme styling
    // const requestsTable = $("#requestsTable").DataTable({
    //   order: [[1, "desc"]],
    //   columnDefs: [{
    //     targets: [0, 1],
    //     orderable: false
    //   }],
    //   pageLength: 20,
    //   language: {
    //     search: "_INPUT_",
    //     searchPlaceholder: "Search requests..."
    //   },
    //   dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex"l><"d-flex"f>>rt<"d-flex justify-content-between align-items-center"<"d-flex"i><"d-flex"p>>'
    // });

    // const progressTable = $("#progressTable").DataTable({
    //   order: [[0, "desc"]],
    //   pageLength: 20,
    //   language: {
    //     search: "_INPUT_",
    //     searchPlaceholder: "Search in progress..."
    //   },
    //   dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex"l><"d-flex"f>>rt<"d-flex justify-content-between align-items-center"<"d-flex"i><"d-flex"p>>'
    // });

    // const completedTable = $("#completedTable").DataTable({
    //   order: [[0, "desc"]],
    //   pageLength: 20,
    //   language: {
    //     search: "_INPUT_",
    //     searchPlaceholder: "Search completed..."
    //   },
    //   dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex"l><"d-flex"f>>rt<"d-flex justify-content-between align-items-center"<"d-flex"i><"d-flex"p>>'
    // });
=======
  // Loader functionality
  function showLoader(text = 'Processing Request...') {
    const loader = document.getElementById('loader-overlay');
    const loaderText = loader.querySelector('.loader-text');
    if (loaderText) loaderText.textContent = text;
    if (loader) loader.style.display = 'flex';
  }

  function hideLoader() {
    const loader = document.getElementById('loader-overlay');
    if (loader) loader.style.display = 'none';
  }

  $(document).ready(function() {
    // Initialize DataTables with your theme styling
    const commonConfig = {
      paging: true,
      pageLength: 20,
      language: {
        search: "_INPUT_",
        searchPlaceholder: "Search..."
      },
      dom: 'lrtip' // Hide default search box to use custom search
    };

    const requestsTable = $("#requestsTable").DataTable({
      ...commonConfig,
      order: [
        [3, "desc"]
      ], // Order by Request Date
      columnDefs: [{
        targets: [0, 5, 6], // S.No, Bank Details, Actions
        orderable: false
      }]
    });

    const progressTable = $("#progressTable").DataTable({
      ...commonConfig,
      order: [
        [3, "desc"]
      ], // Order by Initiated Date
      columnDefs: [{
        targets: [0, 4, 5, 6], // S.No, Transfer ID, UTR, Actions
        orderable: false
      }]
    });

    const completedTable = $("#completedTable").DataTable({
      ...commonConfig,
      order: [
        [4, "desc"]
      ], // Order by Completed Date
      columnDefs: [{
        targets: [0, 5, 6], // S.No, UTR, Receipt
        orderable: false
      }]
    });

    const rejectedTable = $("#rejectedTable").DataTable({
      ...commonConfig,
      order: [
        [4, "desc"]
      ], // Order by Rejection Date
      columnDefs: [{
        targets: [0, 5], // S.No, Status
        orderable: false
      }]
    });

    // Dynamic S.No for all tables
    [requestsTable, progressTable, completedTable, rejectedTable].forEach(table => {
      table.on('order.dt search.dt', function() {
        table.column(0, {
          search: 'applied',
          order: 'applied'
        }).nodes().each(function(cell, i) {
          cell.innerHTML = i + 1;
        });
      }).draw();
    });

    // Custom search input integration
    $('#searchPayments').on('keyup', function() {
      const val = this.value;
      requestsTable.search(val).draw();
      progressTable.search(val).draw();
      completedTable.search(val).draw();
      rejectedTable.search(val).draw();
    });
>>>>>>> origin/main

    // Load statistics
    loadStatistics();

<<<<<<< HEAD
=======
    $('#refreshStatsBtn').on('click', function() {
      showLoader('Refreshing statistics...');
      loadStatistics();
      setTimeout(hideLoader, 1000);
    });

>>>>>>> origin/main
    // Select all checkbox
    $('#selectAll').on('change', function() {
      $('.payment-checkbox:not(:disabled)').prop('checked', $(this).is(':checked'));
      updateBulkProcessButton();
    });

    // Individual checkbox
    $(document).on('change', '.payment-checkbox', function() {
      updateBulkProcessButton();
    });

    // Update bulk process button state
    function updateBulkProcessButton() {
      const checkedCount = $('.payment-checkbox:checked').length;
      const btn = $('#bulkProcessBtn');
      btn.prop('disabled', checkedCount === 0);

      if (checkedCount > 0) {
        btn.html(`<i class="fas fa-money-check me-1"></i> Process Selected (${checkedCount})`);
      } else {
        btn.html('<i class="fas fa-money-check me-1"></i> Process Selected');
      }
    }

    // Bulk process
    $('#bulkProcessBtn').on('click', function() {
      const selectedIds = $('.payment-checkbox:checked').map(function() {
        return $(this).val();
      }).get();

      if (selectedIds.length === 0) return;

      Swal.fire({
        title: 'Confirm Bulk Process',
        html: `You are about to process <strong>${selectedIds.length}</strong> payouts.<br><br>
                       <div class="text-start">
                         <label class="form-label">Enter total amount to process:</label>
                         <input type="number" id="bulkAmount" class="form-control" 
                                placeholder="Enter amount in INR" step="0.01" min="1">
                       </div>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#6366f1',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-check me-1"></i> Process All',
        cancelButtonText: '<i class="fas fa-times me-1"></i> Cancel',
        showClass: {
          popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
          popup: 'animate__animated animate__fadeOutUp'
        },
        preConfirm: () => {
          const amount = $('#bulkAmount').val();
          if (!amount || amount <= 0) {
            Swal.showValidationMessage('Please enter a valid amount');
            return false;
          }
          return {
            amount: amount
          };
        }
      }).then((result) => {
        if (result.isConfirmed) {
<<<<<<< HEAD
=======
          showLoader('Processing bulk payouts...');
>>>>>>> origin/main
          const btn = $(this);
          btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Processing...');

          $.ajax({
            type: 'POST',
            url: '<?= base_url('admin/bulk_process') ?>',
            data: {
              withdrawal_ids: selectedIds,
              amount: result.value.amount
            },
            success: function(response) {
<<<<<<< HEAD
=======
              hideLoader();
>>>>>>> origin/main
              if (response.status === 'success') {
                Swal.fire({
                  title: 'Success!',
                  text: response.message,
                  icon: 'success',
                  timer: 2000,
                  showConfirmButton: false,
                  showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                  },
                  hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                  }
                }).then(() => {
                  location.reload();
                });
              } else {
                Swal.fire({
                  title: 'Error!',
                  text: response.message,
                  icon: 'error',
                  showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                  },
                  hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                  }
                });
                btn.prop('disabled', false).html('<i class="fas fa-money-check me-1"></i> Process Selected');
              }
            },
            error: function() {
<<<<<<< HEAD
=======
              hideLoader();
>>>>>>> origin/main
              Swal.fire({
                title: 'Error!',
                text: 'Failed to process payouts',
                icon: 'error',
                showClass: {
                  popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                  popup: 'animate__animated animate__fadeOutUp'
                }
              });
              btn.prop('disabled', false).html('<i class="fas fa-money-check me-1"></i> Process Selected');
            }
          });
        }
      });
    });

    // Process single payout with amount confirmation
    $(document).on('click', '.process-payout', function() {
      const id = $(this).data('id');
      const requestedAmount = parseFloat($(this).data('amount'));
      const providerId = $(this).data('provider');

      Swal.fire({
        title: 'Confirm Payout',
        html: `
                    <div class="text-start">
                        <div class="mb-3">
                            <label class="form-label">Requested Amount:</label>
                            <input type="text" class="form-control" value="₹${requestedAmount.toFixed(2)}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Enter Amount to Pay:</label>
                            <input type="number" id="payAmount" class="form-control" 
                                   value="${requestedAmount}" 
                                   min="${requestedAmount}" 
                                   step="0.01" 
                                   placeholder="Enter amount in INR">
                            <small class="text-muted">Amount must be greater than or equal to ₹${requestedAmount.toFixed(2)}</small>
                        </div>
                    </div>
                `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#22c55e',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-check me-1"></i> Approve & Pay',
        cancelButtonText: '<i class="fas fa-times me-1"></i> Cancel',
        showClass: {
          popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
          popup: 'animate__animated animate__fadeOutUp'
        },
        preConfirm: () => {
          const amount = parseFloat($('#payAmount').val());
          if (!amount || isNaN(amount)) {
            Swal.showValidationMessage('Please enter a valid amount');
            return false;
          }
          if (amount < requestedAmount) {
            Swal.showValidationMessage(`Amount must be at least ₹${requestedAmount.toFixed(2)}`);
            return false;
          }
          return {
            amount: amount
          };
        }
      }).then((result) => {
        if (result.isConfirmed) {
<<<<<<< HEAD
=======
          showLoader('Initiating payout...');
>>>>>>> origin/main
          const btn = $(this);
          btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Processing...');

          $.ajax({
            type: 'POST',
            url: '<?= base_url('process_payout') ?>',
            data: {
              id: id,
              amount: result.value.amount,
              provider_id: providerId
            },
            success: function(response) {
<<<<<<< HEAD
=======
              hideLoader();
>>>>>>> origin/main
              if (response.status === 'success') {
                Swal.fire({
                  title: 'Success!',
                  text: response.message || 'Payout initiated successfully',
                  icon: 'success',
                  timer: 2000,
                  showConfirmButton: false,
                  showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                  },
                  hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                  }
                }).then(() => {
                  location.reload();
                });
              } else {
                Swal.fire({
                  title: 'Error!',
                  text: response.message || 'Failed to process payout',
                  icon: 'error',
                  showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                  },
                  hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                  }
                });
                btn.prop('disabled', false).html('<i class="fas fa-check me-1"></i> Approve & Pay');
              }
            },
            error: function(xhr, status, error) {
<<<<<<< HEAD
=======
              hideLoader();
>>>>>>> origin/main
              Swal.fire({
                title: 'Error!',
                text: 'Failed to process payout: ' + error,
                icon: 'error',
                showClass: {
                  popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                  popup: 'animate__animated animate__fadeOutUp'
                }
              });
              btn.prop('disabled', false).html('<i class="fas fa-check me-1"></i> Approve & Pay');
            }
          });
        }
      });
    });

    // Reject payment
    $(document).on('click', '.reject-payment', function() {
      const id = $(this).data('id');
      const amount = $(this).data('amount');
      const providerId = $(this).data('provider');

      Swal.fire({
        title: 'Reject Payment?',
        html: `Amount <strong>₹${parseFloat(amount).toFixed(2)}</strong> will be returned to wallet.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-times me-1"></i> Reject',
        cancelButtonText: '<i class="fas fa-times me-1"></i> Cancel',
        showClass: {
          popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
          popup: 'animate__animated animate__fadeOutUp'
        }
      }).then((result) => {
        if (result.isConfirmed) {
<<<<<<< HEAD
=======
          showLoader('Rejecting payment...');
>>>>>>> origin/main
          $.ajax({
            type: 'POST',
            url: '<?= base_url('admin/update_status') ?>',
            data: {
              id: id,
              status: 'rejected',
              provider_id: providerId,
              amount: amount
            },
            success: function(response) {
<<<<<<< HEAD
=======
              hideLoader();
>>>>>>> origin/main
              if (response.status === 'success') {
                Swal.fire({
                  title: 'Rejected!',
                  text: 'Payment rejected and amount returned to wallet',
                  icon: 'success',
                  timer: 2000,
                  showConfirmButton: false,
                  showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                  },
                  hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                  }
                }).then(() => {
                  location.reload();
                });
              } else {
                Swal.fire({
                  title: 'Error!',
                  text: response.message || 'Failed to reject payment',
                  icon: 'error',
                  showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                  },
                  hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                  }
                });
              }
            },
            error: function() {
<<<<<<< HEAD
=======
              hideLoader();
>>>>>>> origin/main
              Swal.fire({
                title: 'Error!',
                text: 'Failed to reject payment',
                icon: 'error',
                showClass: {
                  popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                  popup: 'animate__animated animate__fadeOutUp'
                }
              });
            }
          });
        }
      });
    });

<<<<<<< HEAD
    // Update status manually
    $(document).on('click', '.update-payout-status', function() {
      const id = $(this).data('id');
      const currentStatus = $(this).data('current-status');
      const amount = $(this).data('amount');
      const providerId = $(this).data('provider');

      Swal.fire({
        title: 'Update Payout Status',
        html: `
                    <div class="text-start">
                        <div class="mb-3">
                            <label class="form-label">Select New Status:</label>
                            <select id="newStatus" class="form-select">
                                <option value="in_progress" ${currentStatus === 'in_progress' ? 'selected' : ''}>In Progress (Initiated)</option>
                                <option value="completed" ${currentStatus === 'completed' ? 'selected' : ''}>Completed</option>
                                <option value="failed" ${currentStatus === 'failed' ? 'selected' : ''}>Failed</option>
                                <option value="rejected" ${currentStatus === 'rejected' ? 'selected' : ''}>Rejected</option>
                            </select>
                        </div>
                        <div class="mb-3" id="utrField">
                            <label class="form-label">UTR Number (optional):</label>
                            <input type="text" id="utrNumber" class="form-control" placeholder="Enter UTR if completed">
                        </div>
                        <div class="mb-3 d-none" id="reasonField">
                            <label class="form-label">Reason:</label>
                            <textarea id="statusReason" class="form-control" placeholder="Enter reason for rejection/failure"></textarea>
                        </div>
                    </div>
                `,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-save me-1"></i> Update Status',
        cancelButtonText: '<i class="fas fa-times me-1"></i> Cancel',
        didOpen: () => {
          $('#newStatus').on('change', function() {
            const val = $(this).val();
            if (val === 'rejected' || val === 'failed') {
              $('#reasonField').removeClass('d-none');
            } else {
              $('#reasonField').addClass('d-none');
            }
          });
        },
        preConfirm: () => {
          return {
            id: id,
            status: $('#newStatus').val(),
            utr: $('#utrNumber').val(),
            reason: $('#statusReason').val(),
            amount: amount,
            provider_id: providerId
          };
        }
      }).then((result) => {
        if (result.isConfirmed) {
          const btn = $(this);
          btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Updating...');

          $.ajax({
            type: 'POST',
            url: '<?= base_url('admin/update_status') ?>',
            data: result.value,
            success: function(response) {
              if (response.status === 'success') {
                Swal.fire({
                  title: 'Updated!',
                  text: response.message || 'Status updated successfully',
                  icon: 'success',
                  timer: 2000,
                  showConfirmButton: false,
                  showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                  },
                  hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                  }
                }).then(() => {
                  location.reload();
                });
              } else {
                Swal.fire({
                  title: 'Error!',
                  text: response.message || 'Failed to update status',
                  icon: 'error',
                  showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                  },
                  hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                  }
                });
                btn.prop('disabled', false).html('<i class="fas fa-edit me-1"></i> Update Status');
              }
            },
            error: function(xhr, status, error) {
              Swal.fire({
                title: 'Error!',
                text: 'Failed to update status: ' + error,
                icon: 'error',
                showClass: {
                  popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                  popup: 'animate__animated animate__fadeOutUp'
                }
              });
              btn.prop('disabled', false).html('<i class="fas fa-edit me-1"></i> Update Status');
=======
    // Check status
    // Check status - Updated with better UTR handling
    $(document).on('click', '.check-status', function() {
      const id = $(this).data('id');
      const btn = $(this);

      showLoader('Checking transaction status...');
      btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Checking...');

      $.ajax({
        type: 'POST',
        url: '<?= base_url('check_status') ?>',
        data: {
          id: id
        },
        success: function(response) {
          hideLoader();
          btn.prop('disabled', false).html('<i class="fas fa-sync me-1"></i> Check Status');

          if (response.status === 'success') {
            // Format the UTR properly
            let utrDisplay = '';
            if (response.utr && response.utr !== 'undefined' && response.utr !== 'null' && response.utr.trim() !== '') {
              utrDisplay = `<br>UTR: <code>${response.utr}</code>`;
            }

            const message = `Status: <strong>${response.payout_status || 'Unknown'}</strong>${utrDisplay}`;

            Swal.fire({
              title: 'Status Update',
              html: message,
              icon: 'info',
              showClass: {
                popup: 'animate__animated animate__fadeInDown'
              },
              hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
              }
            }).then(() => {
              // Only reload if status changed
              if (response.status_updated) {
                location.reload();
              }
            });
          } else {
            Swal.fire({
              title: 'Error!',
              text: response.message || 'Failed to get status',
              icon: 'error',
              showClass: {
                popup: 'animate__animated animate__fadeInDown'
              },
              hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
              }
            });
          }
        },
        error: function(xhr, status, error) {
          hideLoader();
          btn.prop('disabled', false).html('<i class="fas fa-sync me-1"></i> Check Status');
          Swal.fire({
            title: 'Error!',
            text: 'Failed to check status: ' + error,
            icon: 'error',
            showClass: {
              popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
              popup: 'animate__animated animate__fadeOutUp'
>>>>>>> origin/main
            }
          });
        }
      });
    });

    // Load statistics with better error handling
    function loadStatistics() {
      $.ajax({
        url: '<?= base_url('get_stats') ?>',
        success: function(response) {
          if (response.status === 'success' && response.data) {
            const data = response.data;
            $('#stat_pending').text(data.total_pending || 0);
            $('#stat_progress').text(data.total_in_progress || 0);

            // Format currency properly
            const completedAmount = parseFloat(data.total_paid || 0);
            const pendingAmount = parseFloat(data.total_pending_amount || 0);

            $('#stat_completed').html('₹' + completedAmount.toLocaleString('en-IN', {
              minimumFractionDigits: 2,
              maximumFractionDigits: 2
            }));

            $('#stat_pending_amt').html('₹' + pendingAmount.toLocaleString('en-IN', {
              minimumFractionDigits: 2,
              maximumFractionDigits: 2
            }));

            $('#pending_count').text(data.total_pending || 0);
            $('#progress_count').text(data.total_in_progress || 0);
          } else {
            console.error('Invalid response format:', response);
          }
        },
        error: function(xhr, status, error) {
          console.error('Failed to load statistics:', error);
          // Set default values
          $('#stat_pending').text('0');
          $('#stat_progress').text('0');
          $('#stat_completed').text('₹0');
          $('#stat_pending_amt').text('₹0');
          $('#pending_count').text('0');
          $('#progress_count').text('0');
        }
      });
    }

    // Refresh statistics button
    $('#refreshStatsBtn').on('click', function() {
      const btn = $(this);
      btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Refreshing...');

      loadStatistics();

      setTimeout(() => {
        btn.prop('disabled', false).html('<i class="fas fa-sync me-1"></i> Refresh');
        Swal.fire({
          title: 'Refreshed!',
          text: 'Statistics updated successfully',
          icon: 'success',
          timer: 1500,
          showConfirmButton: false,
          showClass: {
            popup: 'animate__animated animate__fadeInDown'
          },
          hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
          }
        });
      }, 500);
    });

    // Tab change event to refresh DataTables
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
      const target = $(e.target).attr("data-bs-target");
      if (target === '#requests') {
        requestsTable.columns.adjust().draw();
      } else if (target === '#progress') {
        progressTable.columns.adjust().draw();
      } else if (target === '#completed') {
        completedTable.columns.adjust().draw();
<<<<<<< HEAD
=======
      } else if (target === '#rejected') {
        rejectedTable.columns.adjust().draw();
>>>>>>> origin/main
      }
    });

    // Search functionality
    $('#searchPayments').on('input', function() {
      const searchTerm = $(this).val().toLowerCase();

      // Search in all tables based on active tab
      const activeTab = $('.nav-tabs .nav-link.active').attr('id');

      if (activeTab === 'requests-tab') {
        requestsTable.search(searchTerm).draw();
      } else if (activeTab === 'progress-tab') {
        progressTable.search(searchTerm).draw();
      } else if (activeTab === 'completed-tab') {
        completedTable.search(searchTerm).draw();
<<<<<<< HEAD
=======
      } else if (activeTab === 'rejected-tab') {
        rejectedTable.search(searchTerm).draw();
>>>>>>> origin/main
      }
    });

    // Filter functions
    window.applyPaymentFilters = function() {
      const searchTerm = $('#searchPayments').val().toLowerCase();
      const activeTab = $('.nav-tabs .nav-link.active').attr('id');

      if (activeTab === 'requests-tab') {
        requestsTable.search(searchTerm).draw();
      } else if (activeTab === 'progress-tab') {
        progressTable.search(searchTerm).draw();
      } else if (activeTab === 'completed-tab') {
        completedTable.search(searchTerm).draw();
<<<<<<< HEAD
=======
      } else if (activeTab === 'rejected-tab') {
        rejectedTable.search(searchTerm).draw();
>>>>>>> origin/main
      }
    };

    window.clearPaymentFilters = function() {
      $('#searchPayments').val('');

      // Clear search in all tables
      requestsTable.search('').draw();
      progressTable.search('').draw();
      completedTable.search('').draw();
<<<<<<< HEAD
=======
      rejectedTable.search('').draw();
>>>>>>> origin/main
    };
  });

  // Close modals on Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      allowBodyScroll();
    }
  });
</script>

<?php echo $this->include('admin/footer') ?>
<?php echo $this->endSection() ?>