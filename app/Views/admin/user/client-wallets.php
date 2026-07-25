<?php echo $this->extend('admin/includes/header'); ?>
<!--start content-->
<style>
  /* Prevent body scroll when modal is open */
  body.modal-open {
    overflow: hidden !important;
    padding-right: 0 !important;
  }

  /* Status filter button active state */
  .status-filter-btn.active .badge {
    background-color: rgba(220, 53, 69, 0.15) !important;
    color: #dc3545 !important;
    border: 1px solid rgba(220, 53, 69, 0.2) !important;
  }

  /* Search input styling */
  .search-input-wrapper {
    position: relative;
    margin-bottom: 20px;
  }

  .search-input-wrapper .search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    z-index: 2;
  }

  .search-input-wrapper input {
    padding-left: 40px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
  }

  .search-input-wrapper input:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
  }

  /* No results message */
  .no-results {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
    font-size: 16px;
    display: none;
  }

  .no-results i {
    font-size: 48px;
    margin-bottom: 10px;
    color: #adb5bd;
  }

  /* Pagination styles */
  .pagination-info {
    font-size: 14px;
    color: #6c757d;
  }

  /* Table row highlight on hover */
  .table-hover tbody tr:hover {
    background-color: rgba(99, 102, 241, 0.03) !important;
    transform: translateY(-1px);
    transition: all 0.2s ease;
  }
</style>
<?php echo $this->section('content'); ?>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?php echo $this->include('admin/nav_bar') ?>
  </div>

  <div class="conatiner-fluid content-inner pb-0 px-4" id="page_layout">
    <!-- Modern Header Section -->
    <div class="container-fluid p-0 mb-4">
      <div class="bg-gradient-primary text-white p-5 rounded-3 shadow position-relative overflow-hidden">
        <div class="position-relative z-1">
          <h1 class="h2 fw-bold mb-2 text-white">Refund Management</h1>
          <p class="text-white-75 mb-0">Manage client wallet balances, process refund requests, and track refund
            transactions</p>
        </div>
        <i class="bi bi-arrow-counterclockwise position-absolute end-0 bottom-0 opacity-10"
          style="font-size: 14rem; transform: rotate(12deg); margin-right: -3rem; margin-bottom: -3rem;"></i>
      </div>
    </div>
    <div class="card mb-3">
      <div class="card-body p-0">
        <ul class="nav nav-tabs gap-3 p-3 mb-0" id="paymentTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active fw-semibold mb-0" id="wallets-tab" data-bs-toggle="tab"
              data-bs-target="#wallets" type="button" role="tab">
              <i class="bi bi-wallet me-1"></i>Client Wallets
              <span class="badge ms-1"
                style="background: rgba(59, 130, 246, 0.15) !important; 
                                      color: #3b82f6 !important; border: 1px solid rgba(59, 130, 246, 0.2) !important;">
                <?= count($wallets) ?>
              </span>
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold mb-0" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending"
              type="button" role="tab">
              <i class="bi bi-clock me-1"></i>Pending Refunds
              <span class="badge ms-1"
                style="background: rgba(245, 158, 11, 0.15) !important; 
                                      color: #f59e0b !important; border: 1px solid rgba(245, 158, 11, 0.2) !important;">
                <?= count($pending_refunds) ?>
              </span>
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold mb-0" id="completed-tab" data-bs-toggle="tab"
              data-bs-target="#completed" type="button" role="tab">
              <i class="bi bi-check-circle me-1"></i>Successful Refunds
              <span class="badge ms-1" style="background: rgba(34, 197, 94, 0.15) !important; 
                                      color: #16a34a !important; border: 1px solid rgba(34, 197, 94, 0.2) !important;">
                <?= count($completed_refunds) ?>
              </span>
            </button>
          </li>
        </ul>
      </div>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden rounded-3">
      <div class="card-header p-3" style="background: #c6d2ff !important; border-bottom: 2px solid #a3b4ff;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
          <div class="mb-2 mb-md-0">
            <h5 class="card-title mb-0 text-black fw-bold">
              <i class="bi bi-arrow-counterclockwise me-2"></i>Refund Management System
            </h5>
          </div>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="tab-content" id="paymentTabsContent">
          <!-- Client Wallets Tab -->
          <div class="tab-pane fade show active" id="wallets" role="tabpanel">
            <!-- Search Bar -->
            <div class="p-3 border-bottom">
              <div class="search-input-wrapper">
                <input type="text" class="form-control" id="searchWallets"
                  placeholder="Search wallets by client name, mobile, or amount...">
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-hover mb-0" id="walletsTable">
                <thead class="" style="background: #c6d2ff !important;">
                  <tr>
                    <th class="py-3 text-black fw-semibold border-bottom text-center"
                      style="background: #c6d2ff !important;">S.No</th>
                    <th class="py-3 text-black fw-semibold border-bottom" style="background: #c6d2ff !important;">Client
                      Details</th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center"
                      style="background: #c6d2ff !important;">Wallet Balance</th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center"
                      style="background: #c6d2ff !important;">Refund Status</th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center"
                      style="background: #c6d2ff !important;">Requested Amount</th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center"
                      style="background: #c6d2ff !important; width: 150px;">Action</th>
                  </tr>
                </thead>
                <tbody class="border-top-0" id="walletsTableBody">
                  <?php $i = 1;
                  foreach ($wallets as $wallet):
                    $name = !empty($wallet->name) ? $wallet->name : 'N/A';
                    $phone = !empty($wallet->phone) ? $wallet->phone : 'N/A';
                    $totalAmount = !empty($wallet->total_amount) ? $wallet->total_amount : 0;
                    $refundStatus = !empty($wallet->refund_status) ? $wallet->refund_status : null;
                    $requestedAmount = !empty($wallet->requested_amount) ? $wallet->requested_amount : 0;
                  ?>
                    <tr>
                      <td class="text-center py-2">
                        <span class="text-dark fw-medium"><?= $i ?></span>
                      </td>
                      <td class="py-2">
                        <div>
                          <div class="fw-bold text-dark">
                            <a href="<?= base_url('admin/client/' . $wallet->user_id) ?>" class="text-decoration-none">
                              <div class="fw-bold text-dark"><?= htmlspecialchars($name) ?></div>
                            </a>
                          </div>
                          <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-telephone me-1"></i>
                            <span class="text-dark"><?= htmlspecialchars($phone) ?></span>
                          </div>
                        </div>
                      </td>

                      <td class="py-2 text-center">
                        <div class="fw-bold text-primary fs-6">
                          ₹<?= number_format($totalAmount, 2) ?>
                        </div>
                      </td>
                      <td class="py-2 text-center">
                        <?php if ($refundStatus == 'pending'): ?>
                          <span class="badge d-inline-flex align-items-center gap-1 rounded-pill fw-semibold px-3 py-2"
                            style="background: rgba(245, 158, 11, 0.1) !important; 
                                                           color: #f59e0b !important;
                                                           border: 1px solid rgba(245, 158, 11, 0.2) !important;">
                            <i class="bi bi-clock"></i>Waiting for Approval
                          </span>
                        <?php elseif ($refundStatus == 'completed'): ?>
                          <span class="badge d-inline-flex align-items-center gap-1 rounded-pill fw-semibold px-3 py-2"
                            style="background: rgba(34, 197, 94, 0.1) !important; 
                                                           color: #16a34a !important;
                                                           border: 1px solid rgba(34, 197, 94, 0.2) !important;">
                            <i class="bi bi-check-circle"></i>Refunded
                          </span>
                        <?php else: ?>
                          <span class="badge d-inline-flex align-items-center gap-1 rounded-pill fw-semibold px-3 py-2"
                            style="background: rgba(108, 117, 125, 0.1) !important; 
                                                           color: #6c757d !important;
                                                           border: 1px solid rgba(108, 117, 125, 0.2) !important;">
                            <i class="bi bi-dash-circle"></i>No Request
                          </span>
                        <?php endif; ?>
                      </td>
                      <td class="py-2 text-center">
                        <?php if ($requestedAmount > 0 && $refundStatus == 'pending'): ?>
                          <div class="fw-bold text-dark">
                            ₹<?= number_format($requestedAmount, 2) ?>
                          </div>
                        <?php else: ?>
                          <span class="text-muted">-</span>
                        <?php endif; ?>
                      </td>
                      <td class="py-2 text-center">
                        <?php if ($totalAmount > 0 && $refundStatus != 'pending'): ?>
                          <button class="btn btn-sm request-refund" data-userid="<?= $wallet->user_id ?>"
                            data-balance="<?= $totalAmount ?>" data-name="<?= htmlspecialchars($name) ?>" style="background: rgba(59, 130, 246, 0.1) !important; 
                                                               color: #3b82f6 !important;
                                                               border: 1px solid rgba(59, 130, 246, 0.2) !important;">
                            <i class="bi bi-send me-1"></i>Send for Approval
                          </button>
                        <?php elseif ($refundStatus == 'pending'): ?>
                          <span class="text-muted small">Request Pending</span>
                        <?php else: ?>
                          <span class="text-muted small">No Balance</span>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php $i++;
                  endforeach; ?>
                </tbody>
              </table>
              <!-- No results message -->
              <div class="no-results" id="walletsNoResults">
                <i class="bi bi-search"></i>
                <p>No matching wallets found</p>
              </div>
            </div>
            <!-- Pagination Info -->
            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-top">
              <div class="pagination-info">
                Showing <span id="walletsShowing"><?= count($wallets) ?></span> of <?= count($wallets) ?> wallets
              </div>
              <div class="btn-group">
                <button class="btn btn-outline-primary btn-sm" id="walletsPrevBtn" disabled>
                  <i class="bi bi-chevron-left"></i> Previous
                </button>
                <button class="btn btn-primary btn-sm" id="walletsPageInfo">Page 1</button>
                <button class="btn btn-outline-primary btn-sm" id="walletsNextBtn" disabled>
                  Next <i class="bi bi-chevron-right"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Pending Refunds Tab -->
          <div class="tab-pane fade" id="pending" role="tabpanel">
            <!-- Search Bar -->
            <div class="p-3 border-bottom">
              <div class="search-input-wrapper">
                <input type="text" class="form-control" id="searchPending"
                  placeholder="Search pending refunds by client name, ID, amount, or reason...">
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-hover mb-0" id="pendingTable">
                <thead class="" style="background: #c6d2ff !important;">
                  <tr>
                    <th style="display:none">Id</th>
                    <th class="py-3 text-black fw-semibold border-bottom" style="background: #c6d2ff !important;">Refund
                      ID</th>
                    <th class="py-3 text-black fw-semibold border-bottom" style="background: #c6d2ff !important;">Client
                      Details</th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center"
                      style="background: #c6d2ff !important;">Wallet Balance</th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center"
                      style="background: #c6d2ff !important;">Requested Amount</th>
                    <th class="py-3 text-black fw-semibold border-bottom" style="background: #c6d2ff !important;">Reason
                    </th>
                    <th class="py-3 text-black fw-semibold border-bottom" style="background: #c6d2ff !important;">
                      Requested By / Date</th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center"
                      style="background: #c6d2ff !important;">Attachment</th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center"
                      style="background: #c6d2ff !important;">Receipt</th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center"
                      style="background: #c6d2ff !important; width: 150px;">Action</th>
                  </tr>
                </thead>
                <tbody class="border-top-0" id="pendingTableBody">
                  <?php $j = 1;
                  foreach ($pending_refunds as $r):
                    $id = !empty($r->id) ? $r->id : 'N/A';
                    $name = !empty($r->name) ? $r->name : 'N/A';
                    $phone = !empty($r->phone) ? $r->phone : 'N/A';
                    $walletBalance = !empty($r->wallet_balance) ? $r->wallet_balance : 0;
                    $amount = !empty($r->amount) ? $r->amount : 0;
                    $reason = !empty($r->reason) ? $r->reason : 'N/A';
                    $requestedByName = !empty($r->requested_by_name) ? $r->requested_by_name : 'N/A';
                    $createdAt = !empty($r->created_at) ? date('M d, Y h:i A', strtotime($r->created_at)) : 'N/A';
                    $attachment = !empty($r->attachment) ? $r->attachment : null;
                    $receipt = !empty($r->receipt) ? $r->receipt : null;
                  ?>
                    <tr>
                      <td style="display:none"><?= $id ?></td>
                      <td class="py-2">
                        <code class="text-dark">#<?= $id ?></code>
                      </td>
                      <td class="py-2">
                        <div>
                          <div class="fw-bold text-dark">
                            <a href="<?= base_url('admin/client/' . $r->user_id) ?>" class="text-decoration-none">
                              <div class="fw-bold text-dark"><?= htmlspecialchars($name) ?></div>
                            </a>
                          </div>
                          <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-telephone me-1"></i>
                            <span class="text-dark"><?= htmlspecialchars($phone) ?></span>
                          </div>
                        </div>
                      </td>
                      <td class="py-2 text-center">
                        <div class="fw-bold text-dark">
                          ₹<?= number_format($walletBalance, 2) ?>
                        </div>
                      </td>
                      <td class="py-2 text-center">
                        <div class="fw-bold text-primary fs-6">
                          ₹<?= number_format($amount, 2) ?>
                        </div>
                      </td>
                      <td class="py-2">
                        <div class="d-flex flex-wrap gap-1">
                          <?php foreach (explode("\n", $reason) as $line): ?>
                            <?php if (trim($line) !== ''): ?>
                              <span class="badge bg-light text-dark border">
                                <?= htmlspecialchars($line) ?>
                              </span>
                            <?php endif; ?>
                          <?php endforeach; ?>
                        </div>
                      </td>
                      <td class="py-2">
                        <div class="small">
                          <div class="fw-medium text-dark"><?= htmlspecialchars($requestedByName) ?></div>
                          <div class="text-muted">
                            <i class="bi bi-calendar me-1"></i><?= $createdAt ?>
                          </div>
                        </div>
                      </td>
                      <td class="py-2 text-center">
                        <?php if ($attachment): ?>
                          <a href="<?= base_url('public/uploads/refund_attachments/' . $attachment) ?>" target="_blank"
                            class="btn btn-sm" style="background: rgba(59, 130, 246, 0.1) !important; 
                                                          color: #3b82f6 !important;
                                                          border: 1px solid rgba(59, 130, 246, 0.2) !important;">
                            <i class="bi bi-eye me-1"></i>View
                          </a>
                        <?php else: ?>
                          <span class="text-muted small">N/A</span>
                        <?php endif; ?>
                      </td>
                      <td class="py-2 text-center">
                        <?php if ($receipt): ?>
                          <a href="<?= base_url('public/uploads/refund_receipts/' . $receipt) ?>" target="_blank"
                            class="btn btn-sm" style="background: rgba(34, 197, 94, 0.1) !important; 
                                                          color: #16a34a !important;
                                                          border: 1px solid rgba(34, 197, 94, 0.2) !important;">
                            <i class="bi bi-download me-1"></i>Download
                          </a>
                        <?php else: ?>
                          <span class="text-muted small">N/A</span>
                        <?php endif; ?>
                      </td>
                      <td class="py-2 text-center">
                        <button class="btn btn-sm process-refund" data-id="<?= $id ?>" data-amount="<?= $amount ?>"
                          data-client="<?= htmlspecialchars($name) ?>" style="background: rgba(34, 197, 94, 0.1) !important; 
                                                           color: #16a34a !important;
                                                           border: 1px solid rgba(34, 197, 94, 0.2) !important;">
                          <i class="bi bi-check-circle me-1"></i>Process Refund
                        </button>
                      </td>
                    </tr>
                  <?php $j++;
                  endforeach; ?>
                </tbody>
              </table>
              <!-- No results message -->
              <div class="no-results" id="pendingNoResults">
                <i class="bi bi-search"></i>
                <p>No matching pending refunds found</p>
              </div>
            </div>
            <!-- Pagination Info -->
            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-top">
              <div class="pagination-info">
                Showing <span id="pendingShowing"><?= count($pending_refunds) ?></span> of
                <?= count($pending_refunds) ?> pending refunds
              </div>
              <div class="btn-group">
                <button class="btn btn-outline-primary btn-sm" id="pendingPrevBtn" disabled>
                  <i class="bi bi-chevron-left"></i> Previous
                </button>
                <button class="btn btn-primary btn-sm" id="pendingPageInfo">Page 1</button>
                <button class="btn btn-outline-primary btn-sm" id="pendingNextBtn" disabled>
                  Next <i class="bi bi-chevron-right"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Completed Refunds Tab -->
          <div class="tab-pane fade" id="completed" role="tabpanel">
            <!-- Search Bar -->
            <div class="p-3 border-bottom">
              <div class="search-input-wrapper">
                <input type="text" class="form-control" id="searchCompleted"
                  placeholder="Search completed refunds by client name, ID, amount, or transaction ID...">
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-hover mb-0" id="completedTable">
                <thead class="" style="background: #c6d2ff !important;">
                  <tr>
                    <th style="display:none">Id</th>
                    <th class="py-3 text-black fw-semibold border-bottom" style="background: #c6d2ff !important;">Refund
                      ID</th>
                    <th class="py-3 text-black fw-semibold border-bottom" style="background: #c6d2ff !important;">Client
                      Details</th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center"
                      style="background: #c6d2ff !important;">Refund Amount</th>
                    <th class="py-3 text-black fw-semibold border-bottom" style="background: #c6d2ff !important;">
                      Processed By</th>
                    <th class="py-3 text-black fw-semibold border-bottom" style="background: #c6d2ff !important;">
                      Transaction ID</th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center"
                      style="background: #c6d2ff !important;">Receipt</th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center"
                      style="background: #c6d2ff !important;">Status</th>
                    <th class="py-3 text-black fw-semibold border-bottom" style="background: #c6d2ff !important;">Date
                    </th>
                  </tr>
                </thead>
                <tbody class="border-top-0" id="completedTableBody">
                  <?php $k = 1;
                  foreach ($completed_refunds as $r):
                    $id = !empty($r->id) ? $r->id : 'N/A';
                    $name = !empty($r->name) ? $r->name : 'N/A';
                    $phone = !empty($r->phone) ? $r->phone : 'N/A';
                    $amount = !empty($r->amount) ? $r->amount : 0;
                    $processedByName = !empty($r->processed_by_name) ? $r->processed_by_name : 'N/A';
                    $transactionId = !empty($r->transaction_id) ? $r->transaction_id : null;
                    $receipt = !empty($r->receipt) ? $r->receipt : null;
                    $updatedAt = !empty($r->updated_at) ? date('M d, Y h:i A', strtotime($r->updated_at)) : 'N/A';
                  ?>
                    <tr>
                      <td style="display:none"><?= $id ?></td>
                      <td class="py-2">
                        <code class="text-dark">#<?= $id ?></code>
                      </td>
                      <td class="py-2">
                        <div>
                          <div class="fw-bold text-dark">
                            <div class="fw-bold text-dark"><?= htmlspecialchars($name) ?></div>
                          </div>
                          <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-telephone me-1"></i>
                            <span class="text-dark"><?= htmlspecialchars($phone) ?></span>
                          </div>
                        </div>
                      </td>
                      <td class="py-2 text-center">
                        <div class="fw-bold text-primary fs-6">
                          ₹<?= number_format($amount, 2) ?>
                        </div>
                      </td>
                      <td class="py-2">
                        <div class="fw-medium text-dark"><?= htmlspecialchars($processedByName) ?></div>
                      </td>
                      <td class="py-2">
                        <?php if ($transactionId): ?>
                          <code class="text-dark"><?= htmlspecialchars($transactionId) ?></code>
                        <?php else: ?>
                          <span class="text-muted small">N/A</span>
                        <?php endif; ?>
                      </td>
                      <td class="py-2 text-center">
                        <?php if ($receipt): ?>
                          <a href="<?= base_url('public/uploads/refund_receipts/' . $receipt) ?>" target="_blank"
                            class="btn btn-sm" style="background: rgba(34, 197, 94, 0.1) !important; 
                                                          color: #16a34a !important;
                                                          border: 1px solid rgba(34, 197, 94, 0.2) !important;">
                            <i class="bi bi-download me-1"></i>Download
                          </a>
                        <?php else: ?>
                          <span class="text-muted small">N/A</span>
                        <?php endif; ?>
                      </td>
                      <td class="py-2 text-center">
                        <span class="badge d-inline-flex align-items-center gap-1 rounded-pill fw-semibold px-3 py-2"
                          style="background: rgba(34, 197, 94, 0.1) !important; 
                                                       color: #16a34a !important;
                                                       border: 1px solid rgba(34, 197, 94, 0.2) !important;">
                          <i class="bi bi-check-circle"></i>Success
                        </span>
                      </td>
                      <td class="py-2">
                        <div class="text-muted small">
                          <i class="bi bi-calendar me-1"></i><?= $updatedAt ?>
                        </div>
                      </td>
                    </tr>
                  <?php $k++;
                  endforeach; ?>
                </tbody>
              </table>
              <!-- No results message -->
              <div class="no-results" id="completedNoResults">
                <i class="bi bi-search"></i>
                <p>No matching completed refunds found</p>
              </div>
            </div>
            <!-- Pagination Info -->
            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-top">
              <div class="pagination-info">
                Showing <span id="completedShowing"><?= count($completed_refunds) ?></span> of
                <?= count($completed_refunds) ?> completed refunds
              </div>
              <div class="btn-group">
                <button class="btn btn-outline-primary btn-sm" id="completedPrevBtn" disabled>
                  <i class="bi bi-chevron-left"></i> Previous
                </button>
                <button class="btn btn-primary btn-sm" id="completedPageInfo">Page 1</button>
                <button class="btn btn-outline-primary btn-sm" id="completedNextBtn" disabled>
                  Next <i class="bi bi-chevron-right"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Request Refund Modal -->
<div class="modal fade" id="requestRefundModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow p-0" style="border-radius: 12px;">
      <div class="modal-header p-3" style="background: #c6d2ff !important; border-bottom: 2px solid #a3b4ff;">
        <h5 class="modal-title text-black fw-bold mb-0">
          <i class="bi bi-send me-2"></i>Request Refund for <span id="clientName"></span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form id="requestRefundForm" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <input type="hidden" name="user_id" id="rf_user_id">

          <div class="mb-3">
            <label class="form-label fw-medium text-dark">
              <i class="bi bi-wallet me-1"></i>Wallet Balance
            </label>
            <input type="text" id="rf_balance" class="form-control" readonly style="background: rgba(59, 130, 246, 0.1) !important; 
                                      color: #3b82f6 !important;
                                      border: 1px solid rgba(59, 130, 246, 0.2) !important;">
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium text-dark">
              <i class="bi bi-cash-stack me-1"></i>Refund Amount <span class="text-danger">*</span>
            </label>
            <input type="number" name="amount" id="rf_amount" class="form-control" step="0.01" min="0.01" required
              placeholder="Enter refund amount">
            <small class="text-muted mt-1 d-block">
              Maximum amount: <span id="maxAmount" class="fw-semibold">0.00</span>
            </small>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium text-dark">
              <i class="bi bi-chat-text me-1"></i>Reason/Comments <span class="text-danger">*</span>
            </label>
            <textarea name="reason" class="form-control" required placeholder="Enter reason for refund"
              rows="3"></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium text-dark">
              <i class="bi bi-paperclip me-1"></i>Attachment (Optional)
            </label>
            <input type="file" name="attachment" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
            <small class="text-muted mt-1 d-block">
              Supported formats: JPG, PNG, PDF, DOC (Max: 5MB)
            </small>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top p-3">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="bi bi-x-circle me-1"></i>Cancel
        </button>
        <button id="confirmRequestRefund" class="btn bg-primary text-white">
          <i class="bi bi-send me-1"></i>Submit for Approval
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Process Refund Modal -->
<div class="modal fade" id="processRefundModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow p-0" style="border-radius: 12px;">
      <div class="modal-header p-3" style="background: #c6d2ff !important; border-bottom: 2px solid #a3b4ff;">
        <h5 class="modal-title text-black fw-bold mb-0">
          <i class="bi bi-check-circle me-2"></i>Process Refund for <span id="processClientName"></span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form id="processRefundForm" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <input type="hidden" name="refund_id" id="pr_refund_id">

          <div class="mb-3">
            <label class="form-label fw-medium text-dark">
              <i class="bi bi-cash-stack me-1"></i>Refund Amount
            </label>
            <input type="text" id="pr_amount" class="form-control" readonly style="background: rgba(34, 197, 94, 0.1) !important; 
                                      color: #16a34a !important;
                                      border: 1px solid rgba(34, 197, 94, 0.2) !important;">
          </div>
          <div class="mb-3">
            <label class="form-label fw-medium text-dark">
              <i class="bi bi-chat-left-text me-1"></i>Notes / Comments (Optional)
            </label>
            <textarea name="admin_comments" class="form-control" placeholder="Enter any additional notes for this refund"
              rows="3"></textarea>
          </div>
          <div class="alert alert-info py-2 small mb-0 border-0" style="background: rgba(99, 102, 241, 0.1) !important; color: #6366f1 !important;">
            <i class="bi bi-info-circle me-1"></i> This refund will be processed automatically via <strong>Cashfree Payouts</strong> using the banking details provided by the client.
          </div>
        </form>
      </div>
      <div class="modal-footer border-top p-3">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="bi bi-x-circle me-1"></i>Cancel
        </button>
        <button id="confirmProcessRefund" class="btn bg-success text-white">
          <i class="bi bi-lightning-charge-fill me-1"></i>Process via Cashfree
        </button>
      </div>
    </div>
  </div>
</div>

<!-- SweetAlert CSS/JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- Custom Styles -->
<style>
  /* Consistent with your theme */
  .bg-gradient-primary {
    background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
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

  /* Card hover effect */
  .card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 10px -5px rgba(0, 0, 0, 0.04) !important;
  }

  /* Modal Animation */
  .modal.show .modal-dialog {
    animation: modalSlideIn 0.3s ease-out;
  }

  @keyframes modalSlideIn {
    from {
      transform: translateY(-30px) scale(0.95);
      opacity: 0;
    }

    to {
      transform: translateY(0) scale(1);
      opacity: 1;
    }
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .card-header .d-flex {
      flex-direction: column;
      align-items: flex-start !important;
    }

    .table-responsive {
      font-size: 0.875rem;
    }

    .btn-sm {
      padding: 0.25rem 0.5rem;
      font-size: 0.75rem;
    }

    /* Hide some columns on mobile */
    #pendingTable th:nth-child(4),
    #pendingTable td:nth-child(4),
    #pendingTable th:nth-child(7),
    #pendingTable td:nth-child(7),
    #pendingTable th:nth-child(8),
    #pendingTable td:nth-child(8) {
      display: none !important;
    }

    #completedTable th:nth-child(5),
    #completedTable td:nth-child(5),
    #completedTable th:nth-child(7),
    #completedTable td:nth-child(7),
    #completedTable th:nth-child(8),
    #completedTable td:nth-child(8) {
      display: none !important;
    }
  }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
  integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
  crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- JavaScript (Custom Search Functionality) -->
<!-- JavaScript (Custom Search Functionality) -->
<script>
  $(document).ready(function() {
    // ============== CUSTOM TABLE FUNCTIONS ==============

    // Store original rows for each table
    const originalRows = {
      wallets: $('#walletsTableBody tr').clone(),
      pending: $('#pendingTableBody tr').clone(),
      completed: $('#completedTableBody tr').clone()
    };

    // Count total rows for each table
    const totalRows = {
      wallets: originalRows.wallets.length,
      pending: originalRows.pending.length,
      completed: originalRows.completed.length
    };

    // Initialize all table searches
    function initializeAllTables() {
      initializeTable('wallets', 'searchWallets', 'walletsTableBody', 'walletsNoResults', 'walletsShowing', 'walletsPageInfo', 'walletsPrevBtn', 'walletsNextBtn');
      initializeTable('pending', 'searchPending', 'pendingTableBody', 'pendingNoResults', 'pendingShowing', 'pendingPageInfo', 'pendingPrevBtn', 'pendingNextBtn');
      initializeTable('completed', 'searchCompleted', 'completedTableBody', 'completedNoResults', 'completedShowing', 'completedPageInfo', 'completedPrevBtn', 'completedNextBtn');
    }

    // Initialize table with search and pagination
    function initializeTable(tableType, searchInputId, tableBodyId, noResultsId, showingId, pageInfoId, prevBtnId, nextBtnId) {
      const searchInput = document.getElementById(searchInputId);
      const tableBody = document.getElementById(tableBodyId);
      const noResults = document.getElementById(noResultsId);
      const showingSpan = document.getElementById(showingId);
      const pageInfo = document.getElementById(pageInfoId);
      const prevBtn = document.getElementById(prevBtnId);
      const nextBtn = document.getElementById(nextBtnId);

      if (!tableBody) return;

      // Initialize state for this table
      const state = {
        currentPage: 1,
        pageSize: 10,
        filteredRows: [],
        searchTerm: '',
        totalPages: Math.ceil(totalRows[tableType] / 10)
      };

      // Initial display
      displayTableRows(tableType, state, tableBody, noResults, showingSpan, pageInfo, prevBtn, nextBtn);

      // Search functionality
      if (searchInput) {
        searchInput.addEventListener('keyup', function() {
          state.searchTerm = this.value.toLowerCase().trim();
          state.currentPage = 1;

          if (state.searchTerm === '') {
            // Show all rows
            state.filteredRows = originalRows[tableType].toArray();
            state.totalPages = Math.ceil(totalRows[tableType] / state.pageSize);
          } else {
            // Filter rows
            state.filteredRows = originalRows[tableType].filter(function() {
              const row = $(this);
              const rowText = row.text().toLowerCase();
              return rowText.includes(state.searchTerm);
            }).toArray();

            state.totalPages = Math.ceil(state.filteredRows.length / state.pageSize);
          }

          displayTableRows(tableType, state, tableBody, noResults, showingSpan, pageInfo, prevBtn, nextBtn);
        });
      }

      // Pagination buttons
      if (prevBtn) {
        prevBtn.addEventListener('click', function() {
          if (state.currentPage > 1) {
            state.currentPage--;
            displayTableRows(tableType, state, tableBody, noResults, showingSpan, pageInfo, prevBtn, nextBtn);
          }
        });
      }

      if (nextBtn) {
        nextBtn.addEventListener('click', function() {
          if (state.currentPage < state.totalPages) {
            state.currentPage++;
            displayTableRows(tableType, state, tableBody, noResults, showingSpan, pageInfo, prevBtn, nextBtn);
          }
        });
      }
    }

    // Display table rows with pagination
    function displayTableRows(tableType, state, tableBody, noResults, showingSpan, pageInfo, prevBtn, nextBtn) {
      // Get rows to display
      const rowsToDisplay = state.searchTerm === '' ?
        originalRows[tableType].toArray() :
        state.filteredRows;

      // Calculate pagination
      const startIndex = (state.currentPage - 1) * state.pageSize;
      const endIndex = Math.min(startIndex + state.pageSize, rowsToDisplay.length);
      const currentRows = rowsToDisplay.slice(startIndex, endIndex);

      // Clear table body
      tableBody.innerHTML = '';

      // Add rows to table
      currentRows.forEach(row => {
        tableBody.appendChild(row.cloneNode(true));
      });

      // Show/hide no results message
      if (noResults) {
        noResults.style.display = rowsToDisplay.length === 0 ? 'block' : 'none';
      }

      // Update showing count
      if (showingSpan) {
        showingSpan.textContent = rowsToDisplay.length > 0 ?
          `${startIndex + 1}-${endIndex}` :
          '0';
      }

      // Update page info
      if (pageInfo) {
        pageInfo.textContent = rowsToDisplay.length > 0 ?
          `Page ${state.currentPage} of ${state.totalPages}` :
          'Page 0 of 0';
      }

      // Update pagination buttons
      if (prevBtn) {
        prevBtn.disabled = state.currentPage <= 1 || rowsToDisplay.length === 0;
      }

      if (nextBtn) {
        nextBtn.disabled = state.currentPage >= state.totalPages || rowsToDisplay.length === 0;
      }
    }

    // Initialize all tables
    initializeAllTables();

    // Handle tab changes to reset search
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
      const target = $(e.target).attr("data-bs-target");
      const tableType = target.replace('#', '');

      // Clear search input for active tab
      const searchInputId = tableType === 'wallets' ? 'searchWallets' :
        tableType === 'pending' ? 'searchPending' : 'searchCompleted';
      const searchInput = document.getElementById(searchInputId);
      if (searchInput) {
        searchInput.value = '';
      }
    });

    // ============== EXISTING FUNCTIONALITY ==============

    // Open request refund modal
    $(document).on('click', '.request-refund', function() {
      let userId = $(this).data('userid');
      let balance = $(this).data('balance');
      let name = $(this).data('name');

      $('#rf_user_id').val(userId);
      $('#rf_balance').val('₹' + parseFloat(balance).toFixed(2));
      $('#rf_amount').val('');
      $('#rf_amount').attr('max', balance);
      $('#maxAmount').text('₹' + parseFloat(balance).toFixed(2));
      $('#clientName').text(name);
      $('#requestRefundModal').modal('show');
    });

    // Submit refund request
    $('#confirmRequestRefund').on('click', function() {
      let formData = new FormData($('#requestRefundForm')[0]);
      let amount = parseFloat($('#rf_amount').val());
      let balance = parseFloat($('#rf_balance').val().replace('₹', ''));

      if (!amount || amount <= 0) {
        Swal.fire({
          title: 'Error!',
          text: 'Please enter a valid amount',
          icon: 'error',
          showClass: {
            popup: 'animate__animated animate__fadeInDown'
          },
          hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
          }
        });
        return;
      }

      if (amount > balance) {
        Swal.fire({
          title: 'Error!',
          text: 'Amount exceeds wallet balance',
          icon: 'error',
          showClass: {
            popup: 'animate__animated animate__fadeInDown'
          },
          hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
          }
        });
        return;
      }

      if (!$('textarea[name="reason"]').val().trim()) {
        Swal.fire({
          title: 'Error!',
          text: 'Please enter reason for refund',
          icon: 'error',
          showClass: {
            popup: 'animate__animated animate__fadeInDown'
          },
          hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
          }
        });
        return;
      }

      Swal.fire({
        title: 'Submit Refund Request?',
        text: 'This will send the refund request for approval',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#6366f1',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-send me-1"></i> Yes, Submit',
        cancelButtonText: '<i class="bi bi-x-circle me-1"></i> Cancel',
        showClass: {
          popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
          popup: 'animate__animated animate__fadeOutUp'
        }
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: '<?= base_url("admin/request-refund") ?>',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(resp) {
              if (resp.status) {
                Swal.fire({
                  title: 'Success!',
                  text: resp.message,
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
                  text: resp.message || 'Something went wrong',
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
            error: function(xhr) {
              let msg = 'An error occurred while submitting the request';
              try {
                msg = JSON.parse(xhr.responseText).message || msg;
              } catch (e) {}
              Swal.fire({
                title: 'Error!',
                text: msg,
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

    // Open process refund modal
    $(document).on('click', '.process-refund', function() {
      let id = $(this).data('id');
      let amt = $(this).data('amount');
      let clientName = $(this).data('client');

      $('#pr_refund_id').val(id);
      $('#pr_amount').val('₹' + parseFloat(amt).toFixed(2));
      $('#processClientName').text(clientName);
      $('#processRefundModal').modal('show');
    });

    // Process refund
    $('#confirmProcessRefund').on('click', function() {
      let formData = new FormData($('#processRefundForm')[0]);
      let clientName = $('#processClientName').text();
      let amount = $('#pr_amount').val();

      Swal.fire({
        title: 'Process Automated Refund?',
        html: `This will initiate an automated transfer of <strong>${amount}</strong> to <strong>${clientName}</strong> via Cashfree Payouts.<br/><br/><span class="text-danger small"><i class="bi bi-exclamation-triangle me-1"></i> Please ensure you have sufficient balance in your Cashfree Payout account.</span>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#22c55e',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-lightning-charge-fill me-1"></i> Initiate Transfer',
        cancelButtonText: '<i class="bi bi-x-circle me-1"></i> Cancel',
        showClass: {
          popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
          popup: 'animate__animated animate__fadeOutUp'
        }
      }).then((result) => {
        if (result.isConfirmed) {
          // Disable button and show loading
          let btn = $(this);
          let btnOldHtml = btn.html();
          btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Processing...');

          $.ajax({
            url: '<?= base_url("admin/process-refund") ?>',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(resp) {
              if (resp.status) {
                Swal.fire({
                  title: 'Success!',
                  text: resp.message,
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
                  text: resp.message || 'Something went wrong',
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
            error: function(xhr) {
              let msg = 'An error occurred while processing refund';
              try {
                msg = JSON.parse(xhr.responseText).message || msg;
              } catch (e) {}
              Swal.fire({
                title: 'Error!',
                text: msg,
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

    // Real-time amount validation
    $('#rf_amount').on('input', function() {
      let amount = parseFloat($(this).val());
      let balance = parseFloat($('#rf_balance').val().replace('₹', ''));

      if (amount > balance) {
        $(this).addClass('is-invalid');
        $('#confirmRequestRefund').prop('disabled', true);
      } else {
        $(this).removeClass('is-invalid');
        $('#confirmRequestRefund').prop('disabled', false);
      }
    });
  });
</script>

<?php echo $this->include('admin/footer') ?>
<?php echo $this->endSection() ?>