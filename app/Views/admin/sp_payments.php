<?= $this->extend('admin/includes/header'); ?>
<!--start content-->
<?= $this->section('content'); ?>
<main class="main-content">
  <div class="position-relative iq-banner">
    <!--Nav Start-->
    <?= $this->include('admin/nav_bar') ?>
  </div>

  <div class="conatiner-fluid content-inner pb-0 px-4" id="page_layout">
    <!-- Modern Header Section -->
    <div class="container-fluid p-0 mb-4 bg-gradient-primary rounded-3">
      <div class=" text-white p-5  shadow position-relative overflow-hidden">
        <div class="position-relative z-1">
          <h1 class="h2 fw-bold mb-2 text-white">Payment Transactions</h1>
          <p class="text-white-75 mb-0">View and manage all payment transactions</p>
        </div>
        <i class="bi bi-credit-card position-absolute end-0 bottom-0 opacity-10"
          style="font-size: 12rem; transform: rotate(12deg); margin-right: -2rem; margin-bottom: -2rem;"></i>
      </div>
      <div class="p-3">
        <div class="card border-0 shadow-sm mb-0">
          <div class="card-body p-3">
            <div
              class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
              <!-- Search and Filters -->
              <div
                class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-3 w-100 w-md-auto">
                <div class="input-group flex-grow-1 flex-md-grow-0">
                  <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-search text-muted"></i>
                  </span>
                  <input type="text" class="form-control border-start-0 ps-0"
                    placeholder="Search by TXN ID, name or phone..." id="searchInput">
                </div>

                <!-- Service Type Filter -->
                <div class="d-flex align-items-center gap-2">
                  <label class="form-label text-muted mb-0 small">Service Type:</label>
                  <select class="form-select form-select-sm border-0 bg-light" id="serviceTypeFilter" style="width: 150px;">
                    <option value="">All Services</option>
                    <?php
                    $serviceTypes = [];
                    foreach ($payments as $p) {
                      if (!empty($p->transaction_type)) {
                        $serviceTypes[] = trim($p->transaction_type);
                      }
                    }
                    $serviceTypes = array_unique($serviceTypes);
                    foreach ($serviceTypes as $sType) {
                      $label = str_replace('_', ' ', $sType);
                      echo '<option value="' . htmlspecialchars(strtolower($sType)) . '">' . htmlspecialchars(ucwords(strtolower($label))) . '</option>';
                    }
                    ?>
                  </select>
                </div>

                <!-- Payment Mode Filter -->
                <div class="d-flex align-items-center gap-2">
                  <label class="form-label text-muted mb-0 small">Payment Mode:</label>
                  <select class="form-select form-select-sm border-0 bg-light" id="paymentModeFilter" style="width: 140px;">
                    <option value="">All Modes</option>
                    <?php
                    $modes = [];
                    foreach ($payments as $p) {
                      if (!empty($p->payment_mode)) {
                        $modes[] = trim($p->payment_mode);
                      }
                    }
                    $modes = array_unique($modes);
                    foreach ($modes as $mode) {
                      echo '<option value="' . htmlspecialchars(strtolower($mode)) . '">' . htmlspecialchars(ucwords($mode)) . '</option>';
                    }
                    ?>
                  </select>
                </div>

                <!-- Refresh Button -->
                <button type="button" onclick="refreshData()"
                  class="btn bg-primary text-white d-flex align-items-center justify-content-center gap-1">
                  <i class="bi bi-arrow-clockwise"></i>
                  <span class="d-none d-md-inline">Refresh</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Status Tabs -->
    <ul class="nav nav-tabs mb-4 border-bottom" id="paymentTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active fw-medium px-4 py-2 border-0 border-bottom border-primary border-2 text-primary bg-transparent" id="all-tab" data-status="" type="button" role="tab">All Payments</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link fw-medium px-4 py-2 border-0 text-muted bg-transparent" id="success-tab" data-status="success" type="button" role="tab">Success</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link fw-medium px-4 py-2 border-0 text-muted bg-transparent" id="pending-tab" data-status="pending" type="button" role="tab">Pending</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link fw-medium px-4 py-2 border-0 text-muted bg-transparent" id="failed-tab" data-status="failed" type="button" role="tab">Failed</button>
      </li>
    </ul>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
      <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div
                class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-credit-card fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Total Transactions</p>
                <h3 class="mb-0" id="totalTransactionsCount"><?= count($payments) ?></h3>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div
                class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-cash-stack fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Total Amount</p>
                <h3 class="mb-0" id="totalAmountValue">₹<?= number_format(array_sum(array_column($payments, 'amount')) ?? 0, 2) ?></h3>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div
                class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-people fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Total Customers</p>
                <h3 class="mb-0" id="totalCustomersCount"><?= count(array_unique(array_column($payments, 'user_name'))) ?></h3>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div
                class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-graph-up fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Avg. Transaction</p>
                <h3 class="mb-0" id="avgTransactionValue">
                  ₹<?= count($payments) > 0 ? number_format(array_sum(array_column($payments, 'amount')) / count($payments), 2) : '0.00' ?>
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Payments Table -->
    <div class="card border-0 shadow-sm overflow-hidden">
      <div class="table-responsive">
        <table class="table table-hover mb-0" id="paymentsTable">
          <thead class="" style="background-color: #c6d2ff!important;">
            <tr>
              <th width="60" class="ps-4 py-3 text-black fs-6 fw-semibold border-bottom "
                style="background: #c6d2ff!important;">S.No.</th>
              <th class="py-3 text-black fs-6 fw-semibold border-bottom " style="background: #c6d2ff!important;">
                Transaction</th>
              <th width="140" class="py-3 text-black fs-6 fw-semibold border-bottom d-none d-lg-table-cell"
                style="background: #c6d2ff!important;">Customer</th>

              <th width="110" class="py-3 text-black fs-6 fw-semibold border-bottom text-center"
                style="background: #c6d2ff!important;">Paid Amount</th>
              <th width="110" class="py-3 text-black fs-6 fw-semibold border-bottom text-center"
                style="background: #c6d2ff!important;">GST</th>
              <th width="120" class="py-3 text-black fs-6 fw-semibold border-bottom text-center d-none d-md-table-cell"
                style="background: #c6d2ff!important;">Status</th>
              <th width="140" class="py-3 text-black fs-6 fw-semibold border-bottom d-none d-md-table-cell"
                style="background: #c6d2ff!important;">Service Type</th>
              <th width="120" class="py-3 text-black fs-6 fw-semibold border-bottom d-none d-xl-table-cell"
                style="background: #c6d2ff!important;">Payment Mode</th>
              <th width="150" class="pe-4 py-3 text-black fs-6 fw-semibold border-bottom "
                style="background: #c6d2ff!important;">Date</th>
            </tr>
          </thead>
          <tbody class="border-top-0" id="paymentsTableBody">
            <?php
            $i = 1;
            foreach ($payments as $payment) {
              $initial = strtoupper(substr($payment->user_name ?? '?', 0, 1));
              $statusRaw = strtoupper($payment->status ?? '');
              $mappedStatus = match (true) {
                in_array($statusRaw, ['COMPLETED', 'PAID', 'SUCCESS']) => 'success',
                in_array($statusRaw, ['PENDING', 'ACTIVE']) => 'pending',
                in_array($statusRaw, ['FAILED', 'CANCELLED', 'USER_DROPPED', 'FLAGGED', 'CREATED']) => 'failed',
                default => strtolower($statusRaw)
              };

              $statusClass = match ($mappedStatus) {
                'success' => 'bg-success bg-opacity-10 text-white',
                'pending' => 'bg-warning bg-opacity-10 text-white',
                'failed'  => 'bg-danger bg-opacity-10 text-white',
                default   => 'bg-secondary bg-opacity-10 text-white'
              };

              $failureReason = '';
              if ($mappedStatus === 'failed') {
                $reason = $payment->failure_reason ?? $payment->response_message ?? '';
                if ($statusRaw === 'CREATED') {
                  $reason = 'Payment abandoned / Incomplete';
                } elseif (empty(trim($reason))) {
                  $reason = 'Payment Failed or Dropped';
                }

                $failureCode = $payment->failure_code ?? $payment->response_code ?? '';
                if (!empty($failureCode)) {
                  $reason .= ' [' . $failureCode . ']';
                }
                $failureReason = '<div class="text-danger mt-1 text-center" style="font-size: 0.75rem; max-width: 15rem; white-space: normal; line-height: 1.2;">' . htmlspecialchars($reason) . '</div>';
              }
            ?>
              <tr class="payment-row" data-status="<?= $mappedStatus ?>" data-mode="<?= strtolower($payment->payment_mode ?? '') ?>" data-service-type="<?= strtolower($payment->transaction_type ?? '') ?>">
                <td class="ps-4 py-3 fw-medium"><?= $i ?></td>
                <td class="py-3">
                  <div class="d-flex flex-column">
                    <div class="fw-bold text-dark text-truncate" style="max-width: 200px;">
                      <?= $payment->transaction_id ?>
                    </div>
                    <div class="text-muted small text-truncate mt-1" style="max-width: 200px;">
                      <i class="bi bi-briefcase me-1"></i>
                      <?= $payment->provider_name ?? '--' ?>
                    </div>
                  </div>
                </td>
                <td class="py-3 d-none d-lg-table-cell">
                  <div class="d-flex align-items-center gap-2">
                    <div
                      class="rounded-circle bg-primary bg-opacity-10 text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                      style="width: 32px; height: 32px; border: 1px solid #e5dbff;">
                      <?= $initial ?>
                    </div>
                    <div class="min-w-0">
                      <div class="fw-medium text-dark text-truncate">
                        <?= $payment->provider_name ?>
                      </div>
                      <div class="text-muted small text-truncate">
                        <?= $payment->provider_phone ?>
                      </div>
                    </div>
                  </div>
                </td>

                <td class="py-3 text-center">
                  <span class="fw-bold text-success">₹<?= number_format($payment->paid_amount ?? $payment->amount, 2) ?></span>
                </td>
                <td class="py-3 text-center">
                  <span class="fw-medium text-secondary">₹<?= (49 / 100 * 18) ?></span>
                </td>

                <td class="py-3 text-center d-none d-md-table-cell">
                  <div class="d-flex flex-column align-items-center justify-content-center">
                    <span class="badge <?= $statusClass ?> border border-opacity-25 px-3 py-1 fw-medium" style="border-radius: 50px !important;">
                      <?= ucwords(strtolower($statusRaw)) ?>
                    </span>
                    <?= $failureReason ?>
                  </div>
                </td>
                <td class="py-3 d-none d-md-table-cell">
                  <span class="badge bg-primary bg-opacity-10 text-white border border-primary border-opacity-25 px-3 py-1 fw-medium" style="border-radius: 50px !important;">
                    <?= !empty(trim($payment->transaction_type ?? '')) ? ucwords(str_replace('_', ' ', strtolower($payment->transaction_type))) : '--' ?>
                  </span>
                </td>
                <td class="py-3 d-none d-xl-table-cell">
                  <span class="badge bg-light text-dark border px-3 py-1 fw-medium" style="background: rgba(34, 197, 94, 0.1) !important;
         color: #16a34a !important;
         border: 1px solid rgba(34, 197, 94, 0.2) !important;
         font-weight: 600;
         border-radius: 50px !important;">
                    <?= !empty(trim($payment->payment_mode ?? '')) ? $payment->payment_mode : '--' ?>
                  </span>

                </td>
                <td class="pe-4 py-3">
                  <div class="d-flex flex-column">
                    <span class="text-dark"><?= date('d M Y', strtotime($payment->created_at)) ?></span>
                    <small class="text-muted"><?= date('h:i A', strtotime($payment->created_at)) ?></small>
                  </div>
                </td>
              </tr>
            <?php
              $i++;
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center px-2 gap-3">
      <div class="text-muted small" id="pageInfo">
        Showing <span id="startEntry">1</span> to <span id="endEntry">10</span> of <span
          id="totalEntries"><?= count($payments) ?></span> entries
      </div>
      <div class="btn-group mt-0 pt-0" id="paginationControls">
        <!-- Pagination buttons will be generated by JavaScript -->
      </div>
    </div>
  </div>
</main>

<style>
  /* Custom styles to match the exact design */
  .bg-gradient-primary {
    background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
  }

  .card {
    border-radius: 16px !important;
  }

  .table thead th {
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    padding-top: 1rem;
    padding-bottom: 1rem;
  }

  .table tbody tr {
    transition: all 0.2s ease;
  }

  .table tbody tr:hover {
    background-color: rgba(99, 102, 241, 0.03) !important;
  }

  .badge {
    padding: 0.5rem 0.75rem !important;
    font-weight: 600 !important;
  }

  .form-label {
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .input-group-text {
    background-color: #f9fafb !important;
    border-color: #e5e7eb !important;
  }

  .form-control:focus {
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25) !important;
  }

  .btn-outline-primary {
    border-color: #dee2e6;
    color: #6c757d;
  }

  .btn-outline-primary:hover {
    border-color: #6366f1;
    color: #6366f1;
    background-color: transparent;
  }

  /* Pagination Styles */
  .page-link {
    color: #6366f1;
    border-color: #dee2e6;
  }

  .page-link:hover {
    color: #4f46e5;
    background-color: #f8f9fa;
    border-color: #dee2e6;
  }

  .page-item.active .page-link {
    background-color: #6366f1;
    border-color: #6366f1;
    color: white;
  }

  /* Status colors */
  .status-success {
    background-color: rgba(25, 135, 84, 0.1) !important;
    color: #198754 !important;
    border-color: rgba(25, 135, 84, 0.2) !important;
  }

  .status-pending {
    background-color: rgba(255, 193, 7, 0.1) !important;
    color: #ffc107 !important;
    border-color: rgba(255, 193, 7, 0.2) !important;
  }

  .status-failed {
    background-color: rgba(220, 53, 69, 0.1) !important;
    color: #dc3545 !important;
    border-color: rgba(220, 53, 69, 0.2) !important;
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .d-flex.justify-content-between.align-items-center {
      flex-direction: column;
      gap: 1rem;
    }

    .d-flex.justify-content-between.align-items-center>div {
      width: 100%;
    }

    .input-group {
      width: 100% !important;
    }

    .table-responsive {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
    }

    .table {
      min-width: 600px;
    }

    .table td,
    .table th {
      padding: 0.5rem 0.75rem;
    }

    .row.g-4 .col-md-3 {
      margin-bottom: 1rem;
    }
  }

  @media (max-width: 576px) {
    .table {
      min-width: 500px;
    }

    .btn-group .btn {
      padding: 0.25rem 0.5rem;
      font-size: 0.75rem;
    }
  }

  /* Utility classes for responsive design */
  .min-w-0 {
    min-width: 0;
  }

  .flex-shrink-0 {
    flex-shrink: 0;
  }

  .flex-grow-1 {
    flex-grow: 1;
  }

  .text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  /* Summary card hover effects */
  .card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease;
  }
</style>

<script>
  // Pagination variables
  let currentPage = 1;
  const itemsPerPage = 10;
  let allPayments = [];
  let filteredPayments = [];

  // Initialize on DOM ready
  document.addEventListener('DOMContentLoaded', function() {
    // Store all payment rows in array
    const rows = document.querySelectorAll('#paymentsTableBody .payment-row');
    allPayments = Array.from(rows);
    filteredPayments = [...allPayments];

    // Initialize pagination
    initPagination();

    // Set up event listeners
    setupEventListeners();

    console.log('Payments page loaded with manual pagination');
  });

  let activeStatusTab = '';

  function applyFilters() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
    const status = activeStatusTab.toLowerCase();
    const paymentMode = document.getElementById('paymentModeFilter').value.toLowerCase();
    const serviceType = document.getElementById('serviceTypeFilter').value.toLowerCase();

    filteredPayments = allPayments.filter(row => {
      // Status filter
      const rowStatus = (row.getAttribute('data-status') || '').toLowerCase();
      const statusMatch = !status || rowStatus === status;

      // Payment Mode filter
      const rowMode = (row.getAttribute('data-mode') || '').toLowerCase();
      const modeMatch = !paymentMode || rowMode === paymentMode;

      // Service Type filter
      const rowServiceType = (row.getAttribute('data-service-type') || '').toLowerCase();
      const serviceTypeMatch = !serviceType || rowServiceType === serviceType;

      // Search filter (ID, Name, Phone, etc)
      const text = row.textContent.toLowerCase();
      const searchMatch = !searchTerm || text.includes(searchTerm);

      return statusMatch && modeMatch && serviceTypeMatch && searchMatch;
    });

    // Update summary cards
    updateSummaryCards();

    currentPage = 1;
    renderTable();
    updatePaginationControls();
  }

  function updateSummaryCards() {
    const totalTransactionsEl = document.getElementById('totalTransactionsCount');
    const totalAmountEl = document.getElementById('totalAmountValue');
    const totalCustomersEl = document.getElementById('totalCustomersCount');
    const avgTransactionEl = document.getElementById('avgTransactionValue');

    const totalCount = filteredPayments.length;
    let totalAmount = 0;
    const customerNames = new Set();

    filteredPayments.forEach(row => {
      // Amount (4th column)
      const amountSpan = row.querySelector('td:nth-child(4) span');
      if (amountSpan) {
        const val = parseFloat(amountSpan.textContent.replace(/[₹,]/g, '')) || 0;
        totalAmount += val;
      }
      // Customer name (3rd column)
      const nameDiv = row.querySelector('td:nth-child(3) .fw-medium');
      if (nameDiv) {
        customerNames.add(nameDiv.textContent.trim());
      }
    });

    if (totalTransactionsEl) totalTransactionsEl.textContent = totalCount;
    if (totalAmountEl) totalAmountEl.textContent = '₹' + totalAmount.toLocaleString('en-IN', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });
    if (totalCustomersEl) totalCustomersEl.textContent = customerNames.size;
    if (avgTransactionEl) {
      const avg = totalCount > 0 ? totalAmount / totalCount : 0;
      avgTransactionEl.textContent = '₹' + avg.toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
    }
  }

  function setupEventListeners() {
    document.getElementById('searchInput').addEventListener('input', applyFilters);
    document.getElementById('paymentModeFilter').addEventListener('change', applyFilters);
    document.getElementById('serviceTypeFilter').addEventListener('change', applyFilters);

    // Tab Listeners
    const tabs = document.querySelectorAll('#paymentTabs .nav-link');
    tabs.forEach(tab => {
      tab.addEventListener('click', function(e) {
        e.preventDefault();

        // Reset styles for all tabs
        tabs.forEach(t => {
          t.classList.remove('active', 'border-bottom', 'border-primary', 'border-2', 'text-primary');
          t.classList.add('text-muted');
        });

        // Apply styles to active tab
        this.classList.remove('text-muted');
        this.classList.add('active', 'border-bottom', 'border-primary', 'border-2', 'text-primary');

        activeStatusTab = this.getAttribute('data-status');
        applyFilters();
      });
    });
  }

  function initPagination() {
    // Hide all rows initially
    allPayments.forEach(row => row.style.display = 'none');

    // Show first page
    renderTable();
    updatePaginationControls();
  }

  function renderTable() {
    // Hide ALL rows first to clear previous state
    allPayments.forEach(row => row.style.display = 'none');

    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const pagePayments = filteredPayments.slice(startIndex, endIndex);

    // Show and update S.No for only rows for current page
    pagePayments.forEach((row, index) => {
      row.style.display = '';
      // Optional: Update serial number to be sequential in filtered view
      const snoCell = row.querySelector('td:first-child');
      if (snoCell) snoCell.textContent = startIndex + index + 1;
    });

    // Update page info
    const totalEntries = filteredPayments.length;
    const startEntry = totalEntries > 0 ? startIndex + 1 : 0;
    const endEntry = Math.min(endIndex, totalEntries);

    document.getElementById('startEntry').textContent = startEntry;
    document.getElementById('endEntry').textContent = endEntry;
    document.getElementById('totalEntries').textContent = totalEntries;
  }

  function updatePaginationControls() {
    const totalPages = Math.ceil(filteredPayments.length / itemsPerPage);
    const paginationContainer = document.getElementById('paginationControls');

    // Clear existing buttons
    paginationContainer.innerHTML = '';

    // Don't show pagination if only one page
    if (totalPages <= 1) {
      paginationContainer.innerHTML = '<div class="text-muted small">Page 1 of 1</div>';
      return;
    }

    // Previous button
    const prevButton = document.createElement('button');
    prevButton.className = 'btn btn-outline-primary btn-sm';
    prevButton.innerHTML = '<i class="bi bi-chevron-left"></i>';
    prevButton.disabled = currentPage === 1;
    prevButton.onclick = () => changePage(currentPage - 1);

    // Next button
    const nextButton = document.createElement('button');
    nextButton.className = 'btn btn-outline-primary btn-sm';
    nextButton.innerHTML = '<i class="bi bi-chevron-right"></i>';
    nextButton.disabled = currentPage === totalPages;
    nextButton.onclick = () => changePage(currentPage + 1);

    // Page number buttons
    const pageNumbers = [];
    const maxVisiblePages = 5;

    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

    if (endPage - startPage + 1 < maxVisiblePages) {
      startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }

    // First page button
    if (startPage > 1) {
      const firstButton = document.createElement('button');
      firstButton.className = 'btn btn-outline-primary btn-sm';
      firstButton.textContent = '1';
      firstButton.onclick = () => changePage(1);
      pageNumbers.push(firstButton);

      if (startPage > 2) {
        const ellipsis = document.createElement('button');
        ellipsis.className = 'btn btn-outline-primary btn-sm disabled';
        ellipsis.textContent = '...';
        ellipsis.disabled = true;
        pageNumbers.push(ellipsis);
      }
    }

    // Numbered pages
    for (let i = startPage; i <= endPage; i++) {
      const pageButton = document.createElement('button');
      pageButton.className = `btn ${i === currentPage ? 'btn-primary' : 'btn-outline-primary'} btn-sm`;
      pageButton.textContent = i;
      pageButton.onclick = () => changePage(i);
      pageNumbers.push(pageButton);
    }

    // Last page button
    if (endPage < totalPages) {
      if (endPage < totalPages - 1) {
        const ellipsis = document.createElement('button');
        ellipsis.className = 'btn btn-outline-primary btn-sm disabled';
        ellipsis.textContent = '...';
        ellipsis.disabled = true;
        pageNumbers.push(ellipsis);
      }

      const lastButton = document.createElement('button');
      lastButton.className = 'btn btn-outline-primary btn-sm';
      lastButton.textContent = totalPages;
      lastButton.onclick = () => changePage(totalPages);
      pageNumbers.push(lastButton);
    }

    // Add all buttons to container
    paginationContainer.appendChild(prevButton);
    pageNumbers.forEach(button => paginationContainer.appendChild(button));
    paginationContainer.appendChild(nextButton);
  }

  function changePage(page) {
    currentPage = page;
    renderTable();
    updatePaginationControls();

    // Scroll to top of table
    document.getElementById('paymentsTable').scrollIntoView({
      behavior: 'smooth'
    });
  }

  // Refresh Data
  function refreshData() {
    console.log('Refreshing payment data...');
    // Reset filters and pagination
    document.getElementById('searchInput').value = '';
    document.getElementById('paymentModeFilter').value = '';
    document.getElementById('serviceTypeFilter').value = '';

    // Reset tabs
    activeStatusTab = '';
    const tabs = document.querySelectorAll('#paymentTabs .nav-link');
    tabs.forEach(t => {
      t.classList.remove('active', 'border-bottom', 'border-primary', 'border-2', 'text-primary');
      t.classList.add('text-muted');
    });
    const allTab = document.getElementById('all-tab');
    if (allTab) {
      allTab.classList.remove('text-muted');
      allTab.classList.add('active', 'border-bottom', 'border-primary', 'border-2', 'text-primary');
    }

    applyFilters();
  }
</script>

<?= $this->include('admin/footer') ?>
<?= $this->endSection() ?>