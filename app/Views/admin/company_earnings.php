<?= $this->extend('admin/includes/header'); ?>
<!--start content-->
<?= $this->section('content'); ?>

<main class="main-content">
  <div class="position-relative iq-banner">
    <!--Nav Start-->
    <?= $this->include('admin/nav_bar') ?>
  </div>

  <div class="container-fluid content-inner pb-0 px-4" id="page_layout">
    <div class="container-fluid bg-gradient-primary rounded-3 p-0 mb-4">
      <div class="bg-gradient-primary text-white p-5  shadow position-relative overflow-hidden">
        <div class="position-relative z-1">
          <h1 class="h2 fw-bold mb-2 text-white">Company Earnings</h1>
          <p class="text-white-75 mb-0">Track and analyze company revenue from all services</p>
        </div>
        <i class="bi bi-bar-chart-line position-absolute end-0 bottom-0 opacity-10"
          style="font-size: 12rem; transform: rotate(12deg); margin-right: -2rem; margin-bottom: -2rem;"></i>
      </div>
<<<<<<< HEAD
      <div class="p-3">
        <div class="card border-0 shadow-sm mb-0">
          <div class="card-body p-3">
            <div
              class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
              <!-- Search and Actions -->
              <div
                class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-3 w-100 w-md-auto">
                <div class="input-group flex-grow-1 flex-md-grow-0">
                  <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-search text-muted"></i>
                  </span>
                  <input type="text" class="form-control border-start-0 ps-0"
                    placeholder="Search booking ID or customer..." id="searchEarnings">
                </div>

                <!-- Filter by Month -->
                <select class="form-select flex-grow-0" style="width: 180px;" id="monthFilter">
                  <option value="">All Months</option>
                  <?php
                  $months = [];
                  foreach ($earnings as $earning) {
                    if (!in_array($earning->month, $months)) {
                      $months[] = $earning->month;
                    }
                  }
                  sort($months);
                  foreach ($months as $month) {
                    echo '<option value="' . htmlspecialchars($month) . '">' . htmlspecialchars($month) . '</option>';
                  }
                  ?>
                </select>

                <!-- Refresh Button -->
                <button type="button" onclick="refreshEarnings()"
                  class="btn bg-primary d-flex align-items-center justify-content-center gap-1">
                  <i class="bi bi-arrow-clockwise text-white"></i>
                  <span class="d-none d-md-inline text-white">Refresh</span>
                </button>
              </div>

              <!-- Export Button -->
              <button class="btn btn-outline-primary d-flex align-items-center gap-1">
                <i class="bi bi-download"></i>
                <span>Export</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Search and Filters -->


=======
    </div>

>>>>>>> origin/main
    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
      <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div
                class="rounded-circle bg-primary bg-opacity-10 text-white d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-receipt fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Total Bookings</p>
                <h4 class="mb-0"><?= count($earnings) ?></h4>
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
                class="rounded-circle bg-success bg-opacity-10 text-white d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-currency-rupee fs-4"></i>
              </div>
              <div class="ms-3">
<<<<<<< HEAD
                <p class="text-muted small mb-1">Total Revenue</p>
=======
                <p class="text-muted small mb-1">Total Booking Amount</p>
>>>>>>> origin/main
                <h4 class="mb-0">₹<?= number_format(array_sum(array_column($earnings, 'bid_amount')) ?? 0, 2) ?></h4>
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
                class="rounded-circle bg-info bg-opacity-10 text-white d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-percent fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Platform Charges</p>
                <h4 class="mb-0">₹<?= number_format(array_sum(array_column($earnings, 'platform_charges')) ?? 0, 2) ?>
                </h4>
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
                <i class="bi bi-pie-chart fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Average Per Booking</p>
                <h4 class="mb-0">
                  ₹<?= count($earnings) > 0 ? number_format(array_sum(array_column($earnings, 'bid_amount')) / count($earnings), 2) : '0.00' ?>
                </h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

<<<<<<< HEAD
    <!-- Earnings Table -->
=======
    <div class="card border-0 shadow-sm ">
      <div class="card-body p-3">
        <div
          class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
          <!-- Search and Actions -->
          <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-3 w-100 w-md-auto">
            <div class="input-group flex-grow-1 flex-md-grow-0">
              <span class="input-group-text bg-light border-end-0">
                <i class="bi bi-search text-muted"></i>
              </span>
              <input type="text" class="form-control border-start-0 ps-0" placeholder="Search booking ID or customer..."
                id="searchEarnings">
            </div>

            <!-- Filter by Month -->
            <select class="form-select flex-grow-0" style="width: 180px;" id="monthFilter">
              <option value="">All Months</option>
              <?php
              $months = [];
              foreach ($earnings as $earning) {
                if (!in_array($earning->month, $months)) {
                  $months[] = $earning->month;
                }
              }
              sort($months);
              foreach ($months as $month) {
                echo '<option value="' . htmlspecialchars($month) . '">' . htmlspecialchars($month) . '</option>';
              }
              ?>
            </select>

            <!-- Refresh Button -->
            <button type="button" onclick="refreshEarnings()"
              class="btn bg-primary d-flex align-items-center justify-content-center gap-1">
              <i class="bi bi-arrow-clockwise text-white"></i>
              <span class="d-none d-md-inline text-white">Refresh</span>
            </button>
          </div>

          <!-- Export Button -->
          <button class="btn btn-outline-primary d-flex align-items-center gap-1">
            <i class="bi bi-download"></i>
            <span>Export</span>
          </button>
        </div>
      </div>
    </div>

>>>>>>> origin/main
    <div class="card border-0 shadow-sm overflow-hidden">
      <div class="table-responsive">
        <table class="table table-hover mb-0" id="earningsTable">
          <thead class="" style="background-color: #c6d2ff!important;">
            <tr>
              <th width="60" class="pe-4 py-3 text-black fs-6 fw-semibold border-bottom text-center"
                style="background: #c6d2ff!important;">
                S.No.
              </th>
              <th class="pe-4 py-3 text-black fs-6 fw-semibold border-bottom" style="background: #c6d2ff!important;">
                Booking Details</th>
              <th width="150" class="py-3 text-black fs-6 fw-semibold border-bottom text-center"
                style="background: #c6d2ff!important;">Booking Amount</th>
              <th width="150" class="py-3 text-black fs-6 fw-semibold border-bottom text-center"
                style="background: #c6d2ff!important;">Platform Charges</th>
              <th width="120" class="py-3 text-black fs-6 fw-semibold border-bottom text-center d-none d-lg-table-cell"
                style="background: #c6d2ff!important;">Service</th>
              <th width="120" class="py-3 text-black fs-6 fw-semibold border-bottom text-center"
                style="background: #c6d2ff!important;">Month</th>
            </tr>
          </thead>
          <tbody class="border-top-0">
            <?php
            $i = 1;
            foreach ($earnings as $earning) {
              $initial = strtoupper(substr($earning->user_name ?? '?', 0, 1));
              ?>
              <tr>
                <td class="ps-4 py-2 fw-medium text-center"><?= $i ?></td>
                <td class="py-2">
                  <div class="d-flex align-items-center gap-3">
                    <div
                      class="rounded-circle bg-primary bg-opacity-10 text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                      style="width: 36px; height: 36px; border: 1px solid #e5dbff;">
                      <?= $initial ?>
                    </div>
                    <div class="min-w-0 flex-grow-1">
                      <div class="d-flex align-items-center gap-2 mb-1">
                        <div class="fw-bold text-dark">
                          <?= htmlspecialchars($earning->user_name ?? '--') ?>
                        </div>
                        <span class="badge bg-light text-dark border small font-monospace" style="background-color: rgba(59, 130, 246, 0.1) !important;
        color: #2563eb !important;
        border: 1px solid rgba(59, 130, 246, 0.2) !important;">
                          #<?= htmlspecialchars($earning->booking_id ?? '--') ?>
                        </span>
                      </div>
                      <div class="text-muted small">
                        <i class="bi bi-calendar3 me-1"></i>
                        <?= date('M d, Y', strtotime($earning->created_at ?? date('Y-m-d'))) ?>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="py-2 text-center">
                  <div class="d-flex flex-column align-items-center">
                    <span class="fw-bold text-success">₹<?= number_format($earning->bid_amount ?? 0, 2) ?></span>
                    <small class="text-muted">Amount</small>
                  </div>
                </td>
                <td class="py-2 text-center">
                  <div class="d-flex flex-column align-items-center">
                    <span class="fw-bold text-danger">₹<?= number_format($earning->platform_charges ?? 0, 2) ?></span>
                    <small class="text-muted">Charges</small>
                  </div>
                </td>
                <td class="py-2 text-center d-none d-lg-table-cell">
                  <span class="badge text-white px-3 py-1" style="background: rgba(34, 197, 94, 0.1) !important; color: #16a34a !important;
        border: 1px solid rgba(34, 197, 94, 0.2) !important; font-weight: 600;">
                    <?= htmlspecialchars($earning->service_type ?? '--') ?>
                  </span>
                </td>
                <td class="py-2 text-center">
                  <span class="badge " style=" background-color: rgba(251, 191, 36, 0.1) !important;
        color: #d97706 !important;
        border: 1px solid rgba(251, 191, 36, 0.2) !important; font-size: 0.75rem;">
                    <?= htmlspecialchars($earning->month ?? '--') ?>
                  </span>
                </td>
              </tr>
              <?php
              $i++;
            }
            if (empty($earnings)): ?>
              <tr>
                <td colspan="6" class="text-center py-5 text-muted">
                  <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                  No earnings data found
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
          <?php if (!empty($earnings)): ?>
            <tfoot style="background-color: #f8f9fa;">
              <tr>
                <td colspan="2" class="py-2 ps-4 fw-bold text-end">Total:</td>
                <td class="py-2 text-center fw-bold text-success">
                  ₹<?= number_format(array_sum(array_column($earnings, 'bid_amount')) ?? 0, 2) ?>
                </td>
                <td class="py-2 text-center fw-bold text-danger">
                  ₹<?= number_format(array_sum(array_column($earnings, 'platform_charges')) ?? 0, 2) ?>
                </td>
                <td colspan="2"></td>
              </tr>
            </tfoot>
          <?php endif; ?>
        </table>
      </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 px-2 gap-3">
<<<<<<< HEAD
      <div class="text-muted small">
        Showing <?= count($earnings) ?> of <?= count($earnings) ?> entries
      </div>
      <div class="btn-group">
        <button class="btn btn-outline-primary btn-sm">Previous</button>
        <button class="btn btn-primary btn-sm">1</button>
        <button class="btn btn-outline-primary btn-sm">Next</button>
=======
      <div class="text-muted small showing-text">
        Showing <?= count($earnings) ?> of <?= count($earnings) ?> entries
      </div>
      <div class="btn-group pagination-container">
>>>>>>> origin/main
      </div>
    </div>
  </div>

  <?= $this->include('admin/footer') ?>
</main>

<style>
  /* Custom styles matching the service providers page */
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
    padding: 0.5rem 1rem !important;
    font-weight: 600 !important;
    border-radius: 50px
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

  .form-control:focus,
  .form-select:focus {
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

  /* Utility classes */
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

  /* Card hover effects */
  .card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease;
  }
</style>

<script>
<<<<<<< HEAD
  // Search functionality
  document.getElementById('searchEarnings').addEventListener('input', function (e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#earningsTable tbody tr');

    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
  });

  // Month filter functionality
  document.getElementById('monthFilter').addEventListener('change', function (e) {
    const selectedMonth = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#earningsTable tbody tr');

    rows.forEach(row => {
      const monthCell = row.querySelector('td:nth-child(6)'); // Month column
      if (monthCell) {
        const monthText = monthCell.textContent.toLowerCase();
        if (selectedMonth === '' || monthText.includes(selectedMonth)) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      }
    });
  });

  // Refresh Data
  function refreshEarnings() {
    console.log('Refreshing earnings data...');
    location.reload();
  }

  // Initialize on DOM ready
  document.addEventListener('DOMContentLoaded', function () {
    console.log('Company Earnings page loaded');
    // Add any initialization code here
  });
=======
  document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('earningsTable');
    if (!table) return;
    
    const tbody = table.querySelector('tbody');
    const allRows = Array.from(tbody.querySelectorAll('tr'));
    
    // Ignore footer or empty rows if they exist inside tbody
    const dataRows = allRows.filter(row => row.children.length === 6 && !row.querySelector('.bi-inbox'));
    
    const rowsPerPage = 10;
    let currentPage = 1;
    let filteredRows = [...dataRows]; 

    const searchInput = document.getElementById('searchEarnings');
    const monthFilter = document.getElementById('monthFilter');
    const paginationContainer = document.querySelector('.pagination-container');
    const showingText = document.querySelector('.showing-text');
    
    const emptyRow = allRows.find(row => row.querySelector('.bi-inbox'));

    function applyFilters() {
      const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
      const selectedMonth = monthFilter ? monthFilter.value.toLowerCase() : '';

      filteredRows = dataRows.filter(row => {
        const text = row.textContent.toLowerCase();
        const matchesSearch = text.includes(searchTerm);
        
        let matchesMonth = true;
        if (selectedMonth !== '') {
          const monthCell = row.querySelector('td:nth-child(6)');
          if (monthCell) {
            matchesMonth = monthCell.textContent.toLowerCase().includes(selectedMonth);
          }
        }
        
        return matchesSearch && matchesMonth;
      });

      currentPage = 1;
      renderTable();
    }

    function renderTable() {
      // Hide all rows cleanly
      dataRows.forEach(row => {
        row.style.display = 'none';
        row.style.setProperty('display', 'none', 'important');
      });

      if (filteredRows.length === 0) {
        if (emptyRow) {
          emptyRow.style.display = '';
          emptyRow.style.removeProperty('display');
        }
      } else {
        if (emptyRow) {
          emptyRow.style.display = 'none';
          emptyRow.style.setProperty('display', 'none', 'important');
        }
        
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const currentRows = filteredRows.slice(start, end);
        
        currentRows.forEach(row => {
          row.style.display = '';
          row.style.removeProperty('display');
        });
      }

      updatePagination();
      updateFooterTotals();
    }

    function updateFooterTotals() {
      const tfoot = table.querySelector('tfoot');
      if (!tfoot) return;
      
      let sumAmount = 0;
      let sumCharges = 0;
      
      filteredRows.forEach(row => {
        const amountText = row.querySelector('td:nth-child(3)')?.textContent.replace(/[^0-9.-]+/g, '') || '0';
        const chargesText = row.querySelector('td:nth-child(4)')?.textContent.replace(/[^0-9.-]+/g, '') || '0';
        sumAmount += parseFloat(amountText);
        sumCharges += parseFloat(chargesText);
      });
      
      const amtTd = tfoot.querySelector('.text-success');
      const chgTd = tfoot.querySelector('.text-danger');
      if (amtTd) amtTd.textContent = '₹' + sumAmount.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
      if (chgTd) chgTd.textContent = '₹' + sumCharges.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    function updatePagination() {
      const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
      
      const start = filteredRows.length === 0 ? 0 : ((currentPage - 1) * rowsPerPage) + 1;
      const end = Math.min(currentPage * rowsPerPage, filteredRows.length);
      if(showingText) showingText.innerHTML = `Showing ${start} to ${end} of ${filteredRows.length} entries`;

      if(!paginationContainer) return;
      
      let html = `<button class="btn btn-outline-primary btn-sm" id="btn-prev" ${currentPage === 1 || totalPages === 0 ? 'disabled' : ''}>Previous</button>`;
      
      let startPage = Math.max(1, currentPage - 2);
      let endPage = Math.min(totalPages, currentPage + 2);
      
      for (let i = startPage; i <= endPage; i++) {
        html += `<button class="btn ${i === currentPage ? 'btn-primary' : 'btn-outline-primary'} btn-sm page-btn" data-page="${i}">${i}</button>`;
      }
      
      html += `<button class="btn btn-outline-primary btn-sm" id="btn-next" ${currentPage === totalPages || totalPages === 0 ? 'disabled' : ''}>Next</button>`;
      
      paginationContainer.innerHTML = html;

      document.getElementById('btn-prev')?.addEventListener('click', () => {
        if (currentPage > 1) { currentPage--; renderTable(); }
      });
      document.getElementById('btn-next')?.addEventListener('click', () => {
        if (currentPage < totalPages) { currentPage++; renderTable(); }
      });
      document.querySelectorAll('.page-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
          currentPage = parseInt(e.target.getAttribute('data-page'));
          renderTable();
        });
      });
    }

    if(searchInput) searchInput.addEventListener('input', applyFilters);
    if(monthFilter) monthFilter.addEventListener('change', applyFilters);

    renderTable();
  });

  function refreshEarnings() {
    location.reload();
  }
>>>>>>> origin/main
</script>

<?= $this->endSection() ?>