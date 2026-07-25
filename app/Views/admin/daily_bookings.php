<?= $this->extend('admin/includes/header'); ?>
<!--start content-->
<?= $this->section('content'); ?>
<?php
// Helper function for service colors in PHP
function getServiceColor($serviceName)
{
  $serviceLower = strtolower($serviceName ?? '');
  $colors = [
    'boarding' => '#3b82f6',
    'walking' => '#10b981',
    'grooming' => '#8b5cf6',
    'sitting' => '#f59e0b',
    'training' => '#ef4444',
    'vet' => '#ec4899',
    'daycare' => '#06b6d4',
    'taxi' => '#64748b',
    'spa' => '#f97316',
    'default' => '#6366f1'
  ];

  foreach ($colors as $key => $color) {
    if (strpos($serviceLower, $key) !== false) {
      return $color;
    }
  }
  return $colors['default'];
}
?>

<main class="main-content">
  <div class="position-relative iq-banner">
    <!--Nav Start-->
    <?= $this->include('admin/nav_bar') ?>
  </div>

  <div class="container-fluid content-inner mt-n5 py-0" id="page_layout">
    <div class="row">
      <div class="col-xl-12 col-lg-8">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="header-title">
              <h4 class="card-title mb-0">Daily Bookings</h4>
            </div>
            <div class="d-flex gap-3 align-items-center flex-wrap">
              <!-- Entries Per Page -->
              <div class="d-flex align-items-center gap-2">
                <label class="text-muted small">Show</label>
                <select class="form-select form-select-sm" id="entriesPerPage" style="width: 70px;">
                  <option value="5">5</option>
                  <option value="10" selected>10</option>
                  <option value="25">25</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                </select>
                <label class="text-muted small">entries</label>
              </div>
              <!-- Search Box -->
              <div class="position-relative">
                <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">
                  <i class="bi bi-search"></i>
                </span>
                <input type="text" class="form-control form-control-sm ps-5" id="searchInput"
                  placeholder="Search bookings..." style="width: 250px;">
              </div>
            </div>
          </div>

          <div class="card-body">
            <!-- Table with responsive wrapper -->
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0" id="bookingsTable">
                <thead class="bg-light">
                  <tr>
                    <th class="py-3" style="background: #c6d2ff !important;">S.No.</th>
                    <th class="py-3" style="background: #c6d2ff !important;">Booking Date</th>
                    <th class="py-3" style="background: #c6d2ff !important;">No. Bookings</th>
                    <th class="py-3" style="background: #c6d2ff !important;">Service</th>
                    <th class="py-3" style="background: #c6d2ff !important;">Total Amount</th>
                    <th class="py-3" style="background: #c6d2ff !important;">Platform Charges</th>
                    <th class="py-3" style="background: #c6d2ff !important;">Final Amount</th>
                  </tr>
                </thead>
                <tbody id="tableBody">
                  <?php $i = 1;
                  foreach ($bookings as $booking) { ?>
                    <tr class="booking-row">
                      <td class="py-2"><?= $i ?></td>
                      <td class="py-2"><?= date('d-m-Y', strtotime($booking->booking_date)) ?></td>
                      <td class="py-2">
                        <span class="badge bg-primary bg-opacity-10 text-white px-3 py-2 rounded-pill">
                          <?= $booking->total_bookings ?>
                        </span>
                      </td>
                      <td class="py-2">
                        <span class="badge px-3 py-2 rounded-pill"
                          style="background: <?= getServiceColor($booking->service_name) ?>; color: white;">
                          <?= $booking->service_name ?>
                        </span>
                      </td>
                      <td class="py-2 fw-semibold">₹<?= number_format($booking->total_price, 2) ?></td>
                      <td class="py-2 text-secondary">₹<?= number_format($booking->platform_charges, 2) ?></td>
                      <td class="py-2 fw-bold text-success">₹<?= number_format($booking->actual_amount, 2) ?></td>
                    </tr>
                    <?php $i++;
                  } ?>
                </tbody>
              </table>
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="text-center py-5 d-none">
              <div class="mb-3">
                <i class="bi bi-inbox display-1 text-muted"></i>
              </div>
              <h5 class="text-muted mb-2">No bookings found</h5>
              <p class="text-muted small">Try adjusting your search or filter criteria</p>
            </div>

            <!-- Pagination Controls -->
            <div class="d-flex justify-content-between align-items-center mt-4 pt-2 border-top">
              <div class="text-muted small" id="tableInfo">
                Showing 0 to 0 of 0 entries
              </div>
              <div class="d-flex gap-2" id="paginationControls">
                <button class="btn btn-outline-primary btn-sm" id="prevPage" disabled>
                  <i class="bi bi-chevron-left me-1"></i>Previous
                </button>
                <div class="d-flex gap-1" id="pageNumbers"></div>
                <button class="btn btn-outline-primary btn-sm" id="nextPage">
                  Next<i class="bi bi-chevron-right ms-1"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?= $this->include('admin/footer') ?>
</main>

<style>
  /* Custom styles */
  .card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
  }

  .card-header {
    background-color: transparent;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    padding: 1.5rem;
  }

  .table thead th {
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
  }

  .table tbody tr {
    transition: all 0.2s ease;
  }

  .table tbody tr:hover {
    background-color: rgba(99, 102, 241, 0.02) !important;
  }

  .badge {
    font-weight: 500;
    font-size: 0.8rem;
  }

  .form-control-sm,
  .form-select-sm {
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    background-color: white;
    font-size: 0.875rem;
  }

  .form-control-sm:focus,
  .form-select-sm:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
  }

  .btn-outline-primary {
    border-color: #e2e8f0;
    color: #64748b;
    border-radius: 10px;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
  }

  .btn-outline-primary:hover {
    background-color: #6366f1;
    border-color: #6366f1;
    color: white;
  }

  .btn-outline-primary:disabled {
    background-color: #f1f5f9;
    border-color: #e2e8f0;
    color: #94a3b8;
    opacity: 0.7;
  }

  .btn-outline-primary.active {
    background-color: #6366f1 !important;
    border-color: #6366f1 !important;
    color: white !important;
  }

  #pageNumbers .btn {
    min-width: 38px;
    border-radius: 10px;
  }

  @media (max-width: 768px) {
    .card-header {
      flex-direction: column;
      align-items: flex-start !important;
    }

    #searchInput {
      width: 100% !important;
    }

    #paginationControls {
      flex-wrap: wrap;
    }
  }
</style>

<script>
  class BookingTableManager {
    constructor() {
      this.currentPage = 1;
      this.entriesPerPage = 10;
      this.searchTerm = '';

      // Get all rows from PHP data
      this.allRows = Array.from(document.querySelectorAll('.booking-row'));
      this.filteredRows = [...this.allRows];
      this.totalEntries = this.allRows.length;

      this.init();
    }

    init() {
      // Setup event listeners
      document.getElementById('entriesPerPage').addEventListener('change', (e) => {
        this.entriesPerPage = parseInt(e.target.value);
        this.currentPage = 1;
        this.updateDisplay();
      });

      document.getElementById('searchInput').addEventListener('input', (e) => {
        this.searchTerm = e.target.value.toLowerCase();
        this.currentPage = 1;
        this.filterData();
      });

      document.getElementById('prevPage').addEventListener('click', () => {
        if (this.currentPage > 1) {
          this.currentPage--;
          this.updateDisplay();
        }
      });

      document.getElementById('nextPage').addEventListener('click', () => {
        const totalPages = this.getTotalPages();
        if (this.currentPage < totalPages) {
          this.currentPage++;
          this.updateDisplay();
        }
      });

      // Initial display
      this.updateDisplay();
    }

    filterData() {
      if (this.searchTerm === '') {
        this.filteredRows = [...this.allRows];
      } else {
        this.filteredRows = this.allRows.filter(row => {
          const rowText = Array.from(row.cells).slice(1).map(cell =>
            cell.textContent.toLowerCase()
          ).join(' ');
          return rowText.includes(this.searchTerm);
        });
      }

      this.updateDisplay();
    }

    getTotalPages() {
      return Math.ceil(this.filteredRows.length / this.entriesPerPage);
    }

    updateDisplay() {
      const start = (this.currentPage - 1) * this.entriesPerPage;
      const end = Math.min(start + this.entriesPerPage, this.filteredRows.length);

      // Hide all rows first
      this.allRows.forEach(row => row.style.display = 'none');

      // Show filtered rows for current page
      for (let i = start; i < end; i++) {
        if (this.filteredRows[i]) {
          this.filteredRows[i].style.display = '';
        }
      }

      // Show/hide empty state
      const emptyState = document.getElementById('emptyState');
      if (this.filteredRows.length === 0) {
        emptyState.classList.remove('d-none');
      } else {
        emptyState.classList.add('d-none');
      }

      this.updatePaginationControls(start, end);
    }

    updatePaginationControls(start, end) {
      const totalPages = this.getTotalPages();
      const totalEntries = this.filteredRows.length;

      // Update table info
      document.getElementById('tableInfo').textContent =
        `Showing ${totalEntries > 0 ? start + 1 : 0} to ${Math.min(end, totalEntries)} of ${totalEntries} entries`;

      // Update prev/next buttons
      document.getElementById('prevPage').disabled = this.currentPage === 1 || totalEntries === 0;
      document.getElementById('nextPage').disabled = this.currentPage === totalPages || totalPages === 0;

      // Generate page numbers
      this.generatePageNumbers(totalPages);
    }

    generatePageNumbers(totalPages) {
      const pageNumbersContainer = document.getElementById('pageNumbers');
      let html = '';

      if (totalPages <= 7) {
        // Show all pages
        for (let i = 1; i <= totalPages; i++) {
          html += this.createPageButton(i);
        }
      } else {
        // Show limited pages with ellipsis
        if (this.currentPage <= 4) {
          for (let i = 1; i <= 5; i++) {
            html += this.createPageButton(i);
          }
          html += `<span class="btn btn-outline-primary btn-sm" disabled>...</span>`;
          html += this.createPageButton(totalPages);
        } else if (this.currentPage >= totalPages - 3) {
          html += this.createPageButton(1);
          html += `<span class="btn btn-outline-primary btn-sm" disabled>...</span>`;
          for (let i = totalPages - 4; i <= totalPages; i++) {
            html += this.createPageButton(i);
          }
        } else {
          html += this.createPageButton(1);
          html += `<span class="btn btn-outline-primary btn-sm" disabled>...</span>`;
          for (let i = this.currentPage - 2; i <= this.currentPage + 2; i++) {
            html += this.createPageButton(i);
          }
          html += `<span class="btn btn-outline-primary btn-sm" disabled>...</span>`;
          html += this.createPageButton(totalPages);
        }
      }

      pageNumbersContainer.innerHTML = html;

      // Add click events to page buttons
      document.querySelectorAll('.page-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
          this.currentPage = parseInt(e.target.dataset.page);
          this.updateDisplay();
        });
      });
    }

    createPageButton(pageNum) {
      const activeClass = pageNum === this.currentPage ? 'active' : '';
      return `<button class="btn btn-outline-primary btn-sm page-btn ${activeClass}" data-page="${pageNum}">${pageNum}</button>`;
    }
  }

  // Helper function for service colors (replicating PHP function in JS)


  // Initialize the table manager when DOM is loaded
  document.addEventListener('DOMContentLoaded', function () {
    window.tableManager = new BookingTableManager();
  });
</script>

<?= $this->endSection() ?>