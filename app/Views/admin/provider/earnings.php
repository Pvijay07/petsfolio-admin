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
    <div class="container-fluid bg-gradient-primary p-0 rounded-3 mb-4">
      <div class=" text-white p-5  shadow position-relative overflow-hidden">
        <div class="position-relative z-1">
          <h1 class="h2 fw-bold mb-2 text-white">Service Providers Earnings</h1>
          <p class="text-white-75 mb-0">Track and manage service provider earnings and withdrawals</p>
        </div>
        <i class="bi bi-currency-dollar position-absolute end-0 bottom-0 opacity-10"
          style="font-size: 12rem; transform: rotate(12deg); margin-right: -2rem; margin-bottom: -2rem;"></i>
      </div>
      <div class="p-3">
        <div class="card border-0 shadow-sm mb-0">
          <div class="card-body p-3">
            <div
              class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
              <!-- Search and Export -->
              <div
                class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-3 w-100 w-md-auto">
                <div class="input-group flex-grow-1 flex-md-grow-0">
                  <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-search text-muted"></i>
                  </span>
                  <input type="text" class="form-control border-start-0 ps-0" placeholder="Search by name or phone..."
                    id="searchInput">
                </div>

                <!-- Refresh Button -->
                <button type="button" onclick="refreshData()"
                  class="btn bg-primary d-flex align-items-center justify-content-center gap-1">
                  <i class="bi bi-arrow-clockwise text-white"></i>
                  <span class="d-none d-md-inline text-white">Refresh</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Search Bar and Actions -->


    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
      <div class="col-md-4 ">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div
                class="rounded-circle bg-primary bg-opacity-10 text-white d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-people fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Total Providers</p>
                <h4 class="mb-0"><?= count($providers) ?></h4>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4 ">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div
                class="rounded-circle bg-success bg-opacity-10 text-white d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-cash-coin fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Total Earnings</p>
                <h4 class="mb-0">₹<?= number_format(array_sum(array_column($providers, 'total')) ?? 0, 2) ?></h4>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4 ">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div
                class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-wallet2 fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Total Withdrawals</p>
                <h4 class="mb-0">₹<?= number_format(array_sum(array_column($providers, 'receipts_total')) ?? 0, 2) ?>
                </h4>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- <div class="col-md-4 ">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <i class="bi bi-calendar-check fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Total Bookings</p>
                <h4 class="mb-0"><?= number_format(array_sum(array_column($providers, 'totalBookings')) ?? 0) ?></h4>
              </div>
            </div>
          </div>
        </div>
      </div> -->
    </div>

    <!-- Earnings Table -->
    <div class="card border-0 shadow-sm overflow-hidden">
      <div class="table-responsive">
        <table class="table table-hover mb-0" id="earningsTable">
          <thead class="" style="background-color: #c6d2ff!important;">
            <tr>
              <th width="60" class="pe-4 py-3 text-black fs-6 fw-semibold border-bottom text-end"
                style="background: #c6d2ff!important;">
                S.No.
              </th>
              <th class="pe-4 py-3 text-black fs-6 fw-semibold border-bottom " style="background: #c6d2ff!important;">
                Service Provider</th>
              <th width="120" class="py-3 text-black fs-6  fw-semibold border-bottom text-center d-none d-lg-table-cell"
                style="background: #c6d2ff!important;">Service</th>
              <th width="140" class="py-3 text-black fs-6  fw-semibold border-bottom text-center"
                style="background: #c6d2ff!important;">Total Bookings</th>
              <th width="150" class="py-3 text-black  fs-6 fw-semibold border-bottom text-center"
                style="background: #c6d2ff!important;">Total Withdrawal</th>
              <th width="150" class="py-3 text-black fs-6  fw-semibold border-bottom text-center"
                style="background: #c6d2ff!important;">Total Earnings</th>
            </tr>
          </thead>
          <tbody class="border-top-0">
            <?php
            $i = 1;
            foreach ($providers as $provider) {
              $initial = strtoupper(substr($provider->name ?? '?', 0, 1));
              $totalEarnings = $provider->total ?? 0;
              $totalWithdrawal = $provider->receipts_total ?? 0;
              $totalBookings = $provider->totalBookings ?? 0;
              ?>
              <tr>
                <td class="ps-4 py-3 fw-medium"><?= $i ?></td>
                <td class="py-3">
                  <div class="d-flex align-items-center gap-3">
                    <div
                      class="rounded-circle bg-primary bg-opacity-10 text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                      style="width: 36px; height: 36px; border: 1px solid #e5dbff;">
                      <?= $initial ?>
                    </div>
                    <div class="min-w-0 flex-grow-1">
                      <div class="fw-bold text-dark text-truncate">
                        <?= $provider->name ?? '--' ?>
                      </div>
                      <div class="d-flex align-items-center text-muted small mt-1">
                        <i class="bi bi-telephone me-1 flex-shrink-0"></i>
                        <span class="text-truncate"><?= $provider->phone ?? '--' ?></span>
                      </div>
                      <div class="text-muted small font-monospace mt-1">
                        <i class="bi bi-geo-alt me-1"></i><span
                          class="text-truncate"><?= $provider->area ?? '--' ?></span>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="py-3 text-center d-none d-lg-table-cell">
                  <span class="badge bg-primary bg-opacity-10 text-white border border-primary border-opacity-25" style="background-color: rgba(59, 130, 246, 0.1) !important;
        color: #2563eb !important;
        border: 1px solid rgba(59, 130, 246, 0.2) !important; border-radius: 50px !important;">
                    Service
                  </span>
                </td>
                <td class="py-3 text-center">
                  <div class="d-flex flex-column align-items-center">
                    <span class="fw-bold text-dark"><?= number_format($totalBookings) ?></span>
                    <small class="text-muted">Bookings</small>
                  </div>
                </td>
                <td class="py-3 text-center">
                  <div class="d-flex flex-column align-items-center">
                    <span class="fw-bold text-danger">₹<?= number_format($totalWithdrawal, 2) ?></span>
                    <small class="text-muted">Withdrawn</small>
                  </div>
                </td>
                <td class="py-3 text-center">
                  <div class="d-flex flex-column align-items-center">
                    <span class="fw-bold text-success">₹<?= number_format($totalEarnings, 2) ?></span>
                    <small class="text-muted">Earned</small>
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
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 px-2 gap-3">
      <div class="text-muted small">
        Showing <?= count($providers) ?> entries
      </div>
      <div class="btn-group">
        <button class="btn btn-outline-primary btn-sm">Previous</button>
        <button class="btn btn-primary btn-sm">1</button>
        <button class="btn btn-outline-primary btn-sm">Next</button>
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
    padding: 0.5rem 1rem !important;
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

    .row.g-4 .col-md-4 {
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

    .modal-dialog {
      margin: 1rem;
      width: calc(100% - 2rem);
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
  // Search functionality
  document.getElementById('searchInput').addEventListener('input', function (e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#earningsTable tbody tr');

    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
  });

  // Refresh Data
  function refreshData() {
    console.log('Refreshing data...');
    location.reload();
  }

  // Initialize on DOM ready
  document.addEventListener('DOMContentLoaded', function () {
    console.log('Service Providers Earnings page loaded');
  });
</script>

<?= $this->include('admin/footer') ?>
<?= $this->endSection() ?>