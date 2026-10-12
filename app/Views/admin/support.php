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
</style>
<?php echo $this->section('content'); ?>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?php echo $this->include('admin/nav_bar') ?>
  </div>

  <div class="conatiner-fluid content-inner pb-0" id="page_layout">
    <div class="container-fluid p-0 mb-4 bg-gradient-primary rounded-3 shadow-sm">
      <div class=" text-white p-3 rounded-3 shadow position-relative overflow-hidden">
        <div class="position-relative z-1">
          <h1 class="h2 fw-bold mb-2 text-white">Support Messages</h1>
          <p class="text-white-75 mb-0">Manage and resolve customer support tickets and queries</p>
        </div>
        <i class="bi bi-headset position-absolute end-0 bottom-0 opacity-10"
          style="font-size: 14rem; transform: rotate(12deg); margin-right: -3rem; margin-bottom: -3rem;"></i>
      </div>
      <div class="p-3">
        <ul class="nav nav-tabs gap-2 mb-0" id="ticketTabs" role="tablist"
          style="background: #fff !important; padding: 0.5rem; border-radius: 50px; width: max-content;">
          <li class="nav-item mb-0" role="presentation">
            <button class="nav-link active px-4 py-2" id="open-tab" data-bs-toggle="tab" data-bs-target="#openTickets"
              type="button" role="tab">
              <i class="bi bi-envelope-open me-2"></i>Open Tickets
              <?php $openCount = array_reduce($supports, function ($carry, $item) {
                return $carry + ($item->status != 'resolved' ? 1 : 0);
              }, 0); ?>
              <span class="badge bg-danger ms-2"><?= $openCount ?></span>
            </button>
          </li>
          <li class="nav-item mb-0" role="presentation">
            <button class="nav-link px-4 py-2" id="closed-tab" data-bs-toggle="tab" data-bs-target="#closedTickets"
              type="button" role="tab">
              <i class="bi bi-envelope-check me-2"></i>Closed Tickets
              <?php $closedCount = array_reduce($supports, function ($carry, $item) {
                return $carry + ($item->status == 'resolved' ? 1 : 0);
              }, 0); ?>
              <span class="badge bg-secondary ms-2"><?= $closedCount ?></span>
            </button>
          </li>
        </ul>
      </div>
    </div>

    <!-- Search and Filter Card -->
    <div class="card border-0 shadow-sm mb-4 rounded-3">
      <div class="card-body p-3">
        <div class="row g-3 align-items-center">
<<<<<<< HEAD
          <div class="col-md-5">
=======
          <div class="col-md-9">
>>>>>>> origin/main
            <div class="input-group">
              <span class="input-group-text bg-black border-end-0 text-white">
                <i class="bi bi-search"></i>
              </span>
              <input type="text" class="form-control border-start-0" placeholder="Search tickets..." id="searchTickets">
            </div>
          </div>
          <div class="col-md-3">
            <div class="d-flex align-items-center">
              <label class="text-muted small me-2">Show</label>
              <select class="form-select form-select-sm" style="width: auto;" id="entriesPerPage">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="all">All</option>
              </select>
              <label class="text-muted small ms-2">entries</label>
            </div>
          </div>
<<<<<<< HEAD
          <div class="col-md-2">
            <select class="form-select" id="categoryFilter">
              <option value="">All Categories</option>
              <option value="General">General</option>
              <option value="Billing">Billing</option>
              <option value="Technical">Technical</option>
              <option value="Booking">Booking</option>
              <option value="Provider">Provider</option>
            </select>
          </div>
          <div class="col-md-2">
            <button class="btn btn-black bg-black p-2 text-white w-100" onclick="clearFilters()">
              <i class="bi bi-x-circle me-1"></i>Clear
            </button>
          </div>
=======

>>>>>>> origin/main
        </div>
      </div>
    </div>

    <!-- Tabs Card -->
    <div class="card border-0 shadow-sm overflow-hidden rounded-3">
      <div class="card-body p-0">
        <div class="tab-content" id="ticketTabsContent">
          <!-- Open Tickets -->
          <div class="tab-pane fade show active" id="openTickets" role="tabpanel" aria-labelledby="open-tab">
            <div class="table-responsive">
              <table class="table table-hover mb-0" id="openTicketsTable">
                <thead class="" style="background: #c6d2ff !important;">
                  <tr>
                    <th class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                      style="background: #c6d2ff !important; width: 80px;">S.No</th>
                    <th class="py-3 text-black fw-semibold border-bottom fs-6" style="background: #c6d2ff !important;">
                      Ticket ID</th>
                    <th class="py-3 text-black fw-semibold border-bottom fs-6" style="background: #c6d2ff !important;">
                      User Details</th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                      style="background: #c6d2ff !important;">Category</th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                      style="background: #c6d2ff !important;">Date & Time</th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                      style="background: #c6d2ff !important; width: 120px;">Actions</th>
                  </tr>
                </thead>
                <tbody class="border-top-0" id="openTicketsBody">
                  <?php
                  $i = 1;
                  $openTickets = array_filter($supports, function ($item) {
                    return $item->status != 'resolved';
                  });
                  foreach ($openTickets as $support):
<<<<<<< HEAD
                    ?>
=======
                  ?>
>>>>>>> origin/main
                    <tr class="support-row open-ticket" data-status="open" data-category="<?= esc($support->category) ?>">
                      <td class="text-center py-2">
                        <div class="fw-bold text-dark"><?= $i ?></div>
                      </td>
                      <td class="py-2">
                        <div class="fw-bold text-dark">#<?= esc($support->ticket_number) ?></div>
                      </td>
                      <td class="py-2">
                        <div class="d-flex align-items-center gap-3">
                          <div
                            class="rounded-circle bg-primary bg-opacity-10 text-black d-flex align-items-center justify-content-center fw-bold"
                            style="width: 44px; height: 44px; border: 2px solid #e5dbff; font-size: 1.2rem; color: #fff !important;">
                            <?= strtoupper(substr($support->name ?? '?', 0, 1)) ?>
                          </div>
                          <div>
                            <div class="fw-bold text-dark d-flex align-items-center gap-2">
                              <?= esc($support->name) ?>
                              <?php if ($support->unread_count > 0): ?>
                                <span class="badge rounded-pill px-3 py-1 fw-bold" style="
                                    background: rgba(239, 68, 68, 0.12);
                                    color: #dc2626;
                                    border: 1px solid rgba(239, 68, 68, 0.25);
                                  ">
                                  <?= $support->unread_count ?> unread
                                </span>
                              <?php endif; ?>
                            </div>
                            <div class="d-flex align-items-center text-muted small mt-1">
                              <i class="bi bi-envelope me-1"></i>
                              <span class="text-dark"><?= esc($support->email) ?></span>
                            </div>
                            <div class="text-muted small">
                              <i class="bi bi-telephone me-1"></i>
                              <span class="text-dark"><?= esc($support->phone) ?></span>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td class="py-2 text-center">
                        <span class="badge text-black rounded-pill p-2 border-0"
                          style="background-color: rgba(59, 130, 246, 0.1) !important; color: #2563eb !important; border: 1px solid rgba(59, 130, 246, 0.2) !important;">
                          <?= esc($support->category) ?>
                        </span>
                      </td>
                      <td class="py-2 text-center">
                        <div class="d-flex flex-column align-items-center gap-1">
                          <div class="d-flex align-items-center text-dark small">
                            <i class="bi bi-calendar me-1"></i>
                            <?= date('M d, Y', strtotime($support->created_at)) ?>
                          </div>
                          <div class="text-muted small">
                            <i class="bi bi-clock me-1"></i>
                            <?= date('H:i', strtotime($support->created_at)) ?>
                          </div>
                        </div>
                      </td>
                      <td class="py-2 text-center">
<<<<<<< HEAD
                        <div class="d-flex justify-content-center gap-2">
                          <a href="<?= base_url('supportticket/' . $support->id) ?>"
                            class="btn btn-outline-primary btn-sm px-3 py-2"
                            style="background: #c6d2ff !important; border-color: #a3b4ff !important; color: #1a1a1a !important;">
                            <i class="bi bi-chat-left-text me-1"></i>Chat
                          </a>
                        </div>
                      </td>
                    </tr>
                    <?php
=======
                        <div class="d-flex justify-content-center align-items-center gap-2">
                          <a href="<?= base_url('supportticket/' . $support->id) ?>"
                            class="btn btn-outline-primary btn-sm px-3 py-2 d-inline-flex align-items-center gap-1 rounded-pill"
                            style="background: #c6d2ff !important; border-color: #a3b4ff !important; color: #1a1a1a !important;">
                            <i class="bi bi-chat-left-text"></i> Chat
                          </a>
                          <!-- <span class="badge rounded-pill px-2 py-1" style="background: rgba(79, 70, 229, 0.1); color: #4f46e5; border: 1px solid rgba(79, 70, 229, 0.2); font-size: 0.72rem;" title="AI Mode & Human Mode Active">
                            <i class="bi bi-robot text-warning"></i> AI
                          </span> -->
                        </div>
                      </td>
                    </tr>
                  <?php
>>>>>>> origin/main
                    $i++;
                  endforeach;
                  ?>
                </tbody>
              </table>
              <?php if ($openCount == 0): ?>
                <div class="text-center py-5">
                  <i class="bi bi-envelope-open display-1 text-muted mb-3"></i>
                  <h5 class="text-dark mb-2">No Open Tickets</h5>
                  <p class="text-muted">All support tickets are resolved</p>
                </div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Closed Tickets -->
          <div class="tab-pane fade" id="closedTickets" role="tabpanel" aria-labelledby="closed-tab">
            <div class="table-responsive">
              <table class="table table-hover mb-0" id="closedTicketsTable">
                <thead class="" style="background: #c6d2ff !important;">
                  <tr>
                    <th class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                      style="background: #c6d2ff !important; width: 80px;">S.No</th>
                    <th class="py-3 text-black fw-semibold border-bottom fs-6" style="background: #c6d2ff !important;">
                      Ticket ID</th>
                    <th class="py-3 text-black fw-semibold border-bottom fs-6" style="background: #c6d2ff !important;">
                      User Details</th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                      style="background: #c6d2ff !important;">Category</th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                      style="background: #c6d2ff !important;">Date & Time</th>
                    <th class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                      style="background: #c6d2ff !important; width: 120px;">Actions</th>
                  </tr>
                </thead>
                <tbody class="border-top-0" id="closedTicketsBody">
                  <?php
                  $j = 1;
                  $closedTickets = array_filter($supports, function ($item) {
                    return $item->status == 'resolved';
                  });
                  foreach ($closedTickets as $support):
<<<<<<< HEAD
                    ?>
=======
                  ?>
>>>>>>> origin/main
                    <tr class="support-row closed-ticket" data-status="closed"
                      data-category="<?= esc($support->category) ?>">
                      <td class="text-center py-2">
                        <div class="fw-bold text-dark"><?= $j ?></div>
                      </td>
                      <td class="py-2">
                        <div class="fw-bold text-dark"><?= esc($support->ticket_number) ?></div>
                      </td>
                      <td class="py-2">
                        <div class="d-flex align-items-center gap-3">
                          <div
                            class="rounded-circle bg-secondary bg-opacity-10 text-dark d-flex align-items-center justify-content-center fw-bold"
                            style="width: 44px; height: 44px; border: 2px solid #dee2e6; font-size: 1.2rem;">
                            <?= strtoupper(substr($support->name ?? '?', 0, 1)) ?>
                          </div>
                          <div>
                            <div class="fw-bold text-dark">
                              <?= esc($support->name) ?>
                            </div>
                            <div class="d-flex align-items-center text-muted small mt-1">
                              <i class="bi bi-envelope me-1"></i>
                              <span class="text-dark"><?= esc($support->email) ?></span>
                            </div>
                            <div class="text-muted small">
                              <i class="bi bi-telephone me-1"></i>
                              <span class="text-dark"><?= esc($support->phone) ?></span>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td class="py-2 text-center">
                        <span class="badge rounded-pill p-2 border-0"
                          style="background-color: rgba(108, 117, 125, 0.1) !important; color: #6c757d !important; border: 1px solid rgba(108, 117, 125, 0.2) !important;">
                          <?= esc($support->category) ?>
                        </span>
                      </td>
                      <td class="py-2 text-center">
                        <div class="d-flex flex-column align-items-center gap-1">
                          <div class="d-flex align-items-center text-dark small">
                            <i class="bi bi-calendar me-1"></i>
                            <?= date('M d, Y', strtotime($support->created_at)) ?>
                          </div>
                          <div class="text-muted small">
                            <i class="bi bi-clock me-1"></i>
                            <?= date('H:i', strtotime($support->created_at)) ?>
                          </div>
                        </div>
                      </td>
                      <td class="py-2 text-center">
                        <div class="d-flex justify-content-center gap-2">
                          <a href="<?= base_url('supportticket/' . $support->id) ?>"
                            class="btn btn-outline-primary btn-sm px-3 py-2 border"
                            style="color: #6c757d !important; border-color: #dee2e6 !important;">
                            <i class="bi bi-chat-left me-1"></i>View
                          </a>
                        </div>
                      </td>
                    </tr>
<<<<<<< HEAD
                    <?php
=======
                  <?php
>>>>>>> origin/main
                    $j++;
                  endforeach;
                  ?>
                </tbody>
              </table>
              <?php if ($closedCount == 0): ?>
                <div class="text-center py-5">
                  <i class="bi bi-envelope-check display-1 text-muted mb-3"></i>
                  <h5 class="text-dark mb-2">No Closed Tickets</h5>
                  <p class="text-muted">No support tickets have been resolved yet</p>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Updated Pagination Section with Entries Info -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center px-3 py-3 border-top">
          <div class="text-muted small mb-2 mb-md-0" id="entriesInfo">
            Showing 1 to <?= min(10, $openCount) ?> of <?= $openCount ?> entries
          </div>

          <div class="btn-group mt-0 pt-0 border-0" id="paginationControls">
            <button class="btn bg-primary text-white btn-sm" id="prevPage" disabled>
              <i class="bi bi-chevron-left"></i> Previous
            </button>
            <div class="" id="pageNumbers" style="gap: 2px;">
              <!-- Page numbers will be dynamically inserted here -->
            </div>
            <button class="btn bg-primary text-white btn-sm" id="nextPage">
              Next <i class="bi bi-chevron-right"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<style>
  /* Modal Styling */
  .modal {
    display: none;
    position: fixed;
    z-index: 1999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    overflow: hidden;
    align-items: center;
    justify-content: center;
  }

  .modal.show {
    display: flex !important;
  }

  .modal-dialog {
    margin: auto;
    width: 90%;
    max-width: 700px;
  }

  .modal-dialog-centered {
    display: flex;
    align-items: center;
    min-height: calc(100% - 3rem);
  }

  .modal-dialog-scrollable .modal-content {
    max-height: 100%;
    overflow: hidden;
  }

  .modal-dialog-scrollable .modal-body {
    overflow-y: auto;
  }

  .modal-content {
    background-color: white;
    border-radius: 16px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    border: none;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    padding: 0 !important;
  }

  .modal-header {
    padding: 1.5rem;
    border-bottom: none;
    flex-shrink: 0;
  }

  .modal-header h2 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
  }

  .modal-body {
    padding: 0;
    flex: 1 1 auto;
  }

  .modal-footer {
    border-top: 1px solid #e5e7eb;
    flex-shrink: 0;
  }

  /* Table Styling */
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

  /* Tab Styling */
  .nav-tabs .nav-link {
    border: none;
    border-radius: 50px;
    padding: 0.75rem 1.5rem;
    color: #6c757d;
    background: transparent;
    transition: all 0.3s ease;
  }

  .nav-tabs .nav-link:hover {
    color: #6366f1;
    background-color: rgba(99, 102, 241, 0.05);
  }

  .nav-tabs .nav-link.active {
    background: #3f30e3;
    color: #ffffff;
    border-bottom: 1px solid #6366f1;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
  }

  /* Badge Improvements */
  .badge {
    font-weight: 500;
    letter-spacing: 0.02em;
  }

  /* Button Styling */
  .btn-outline-primary {
    border-color: #dee2e6;
    color: #6c757d;
  }

  .btn-outline-primary:hover {
    border-color: #6366f1;
    color: #6366f1;
    background-color: transparent;
  }

  /* Primary Button Styling */
  .btn-primary {
    background: #c6d2ff !important;
    border-color: #a3b4ff !important;
    color: #1a1a1a !important;
    transition: all 0.3s ease;
  }

  .btn-primary:hover {
    background: #a3b4ff !important;
    border-color: #8499ff !important;
    color: #1a1a1a !important;
  }

  /* Fixed gradient header */
  .bg-gradient-primary {
    background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
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

  /* Text Color Fixes */
  .text-dark {
    color: #495057 !important;
  }

  .text-muted {
    color: #6c757d !important;
  }

  .text-primary {
    color: #6366f1 !important;
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .modal-dialog {
      width: 95%;
      margin: 20px auto;
    }

    .modal-content {
      height: 85vh !important;
    }

    .modal-header {
      padding: 15px;
    }

    .modal-body {
      padding: 15px !important;
    }

    .modal-footer {
      padding: 15px;
    }

    /* Hide some columns on mobile */
    #openTicketsTable th:nth-child(3),
    #openTicketsTable td:nth-child(3) {
      display: none !important;
    }

    #closedTicketsTable th:nth-child(3),
    #closedTicketsTable td:nth-child(3) {
      display: none !important;
    }

    .nav-tabs .nav-link {
      padding: 0.5rem 1rem;
      font-size: 0.875rem;
    }
  }

  @media (max-width: 576px) {
    .modal-content {
      height: 90vh !important;
    }

    .modal-header h2 {
      font-size: 1.25rem;
    }

    .nav-tabs .nav-link {
      padding: 0.5rem;
      font-size: 0.75rem;
    }

    .nav-tabs .nav-link span {
      font-size: 0.7rem;
    }
  }
</style>

<script>
  // Pagination Manager Class
  class PaginationManager {
    constructor() {
      this.currentPage = 1;
      this.entriesPerPage = 10;
      this.currentTab = 'open'; // 'open' or 'closed'
      this.openRows = Array.from(document.querySelectorAll('.open-ticket'));
      this.closedRows = Array.from(document.querySelectorAll('.closed-ticket'));
      this.filteredRows = this.openRows; // Start with open tickets
      this.init();
    }

    init() {
      this.setupEventListeners();
      this.updateDisplay();
    }

    setupEventListeners() {
      // Entries per page change
      document.getElementById('entriesPerPage').addEventListener('change', (e) => {
        const value = e.target.value;
        this.entriesPerPage = value === 'all' ? this.getCurrentTotal() : parseInt(value);
        this.currentPage = 1;
        this.updateDisplay();
      });

      // Previous page button
      document.getElementById('prevPage').addEventListener('click', () => {
        if (this.currentPage > 1) {
          this.currentPage--;
          this.updateDisplay();
        }
      });

      // Next page button
      document.getElementById('nextPage').addEventListener('click', () => {
        const totalPages = this.getTotalPages();
        if (this.currentPage < totalPages) {
          this.currentPage++;
          this.updateDisplay();
        }
      });

      // Search input
      const searchInput = document.getElementById('searchTickets');
      if (searchInput) {
        searchInput.addEventListener('input', (e) => {
          this.searchTickets(e.target.value);
        });
      }

      // Category filter
      const categoryFilter = document.getElementById('categoryFilter');
      if (categoryFilter) {
        categoryFilter.addEventListener('change', (e) => {
          this.filterByCategory(e.target.value);
        });
      }

      // Tab change
      document.querySelectorAll('.nav-tabs .nav-link').forEach(tab => {
        tab.addEventListener('click', (e) => {
          const target = e.target.getAttribute('id');
          this.currentTab = target === 'open-tab' ? 'open' : 'closed';
          this.filteredRows = this.currentTab === 'open' ? this.openRows : this.closedRows;
          this.currentPage = 1;
          this.updateDisplay();

          // Update entries info text
          this.updateEntriesInfo();
        });
      });
    }

    getCurrentTotal() {
      return this.filteredRows.length;
    }

    getTotalPages() {
      return Math.ceil(this.filteredRows.length / this.entriesPerPage);
    }

    updateDisplay() {
      const start = (this.currentPage - 1) * this.entriesPerPage;
      const end = Math.min(start + this.entriesPerPage, this.filteredRows.length);

      // Hide all rows in current tab
      if (this.currentTab === 'open') {
        this.openRows.forEach(row => row.style.display = 'none');
      } else {
        this.closedRows.forEach(row => row.style.display = 'none');
      }

      // Show only current page rows
      for (let i = start; i < end; i++) {
        if (this.filteredRows[i]) {
          this.filteredRows[i].style.display = '';
        }
      }

      this.updatePaginationControls();
      this.updateEntriesInfo(start, end);
    }

    updatePaginationControls() {
      const totalPages = this.getTotalPages();
      const prevBtn = document.getElementById('prevPage');
      const nextBtn = document.getElementById('nextPage');
      const pageNumbersContainer = document.getElementById('pageNumbers');

      // Update prev/next buttons
      prevBtn.disabled = this.currentPage === 1;
      nextBtn.disabled = this.currentPage === totalPages || totalPages === 0;

      // Generate page numbers
      let pageNumbersHtml = '';
      const maxVisiblePages = 5;

      if (totalPages <= maxVisiblePages) {
        // Show all pages
        for (let i = 1; i <= totalPages; i++) {
          pageNumbersHtml += this.getPageButtonHtml(i);
        }
      } else {
        // Show limited pages with ellipsis
        if (this.currentPage <= 3) {
          for (let i = 1; i <= 4; i++) {
            pageNumbersHtml += this.getPageButtonHtml(i);
          }
          pageNumbersHtml += `<span class="btn btn-sm btn-outline-secondary border-0" disabled>...</span>`;
          pageNumbersHtml += this.getPageButtonHtml(totalPages);
        } else if (this.currentPage >= totalPages - 2) {
          pageNumbersHtml += this.getPageButtonHtml(1);
          pageNumbersHtml += `<span class="btn btn-sm btn-outline-secondary border-0" disabled>...</span>`;
          for (let i = totalPages - 3; i <= totalPages; i++) {
            pageNumbersHtml += this.getPageButtonHtml(i);
          }
        } else {
          pageNumbersHtml += this.getPageButtonHtml(1);
          pageNumbersHtml += `<span class="btn btn-sm btn-outline-secondary border-0" disabled>...</span>`;
          for (let i = this.currentPage - 1; i <= this.currentPage + 1; i++) {
            pageNumbersHtml += this.getPageButtonHtml(i);
          }
          pageNumbersHtml += `<span class="btn btn-sm btn-outline-secondary border-0" disabled>...</span>`;
          pageNumbersHtml += this.getPageButtonHtml(totalPages);
        }
      }

      pageNumbersContainer.innerHTML = pageNumbersHtml;

      // Add click events to page buttons
      document.querySelectorAll('.page-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
          this.currentPage = parseInt(e.target.dataset.page);
          this.updateDisplay();
        });
      });
    }

    getPageButtonHtml(pageNum) {
      const activeClass = pageNum === this.currentPage ? 'btn-primary' : 'btn-outline-primary';
      return `<button class="btn btn-sm ${activeClass} page-btn" data-page="${pageNum}">${pageNum}</button>`;
    }

    updateEntriesInfo(start, end) {
      const entriesInfo = document.getElementById('entriesInfo');
      if (this.filteredRows.length === 0) {
        entriesInfo.textContent = 'Showing 0 entries';
      } else {
        entriesInfo.textContent = `Showing ${start + 1} to ${end} of ${this.filteredRows.length} entries`;
      }
    }

    searchTickets(query) {
      query = query.toLowerCase();
      const rowsToSearch = this.currentTab === 'open' ? this.openRows : this.closedRows;

      this.filteredRows = rowsToSearch.filter(row => {
        const rowText = row.textContent.toLowerCase();
        return rowText.includes(query);
      });

      this.currentPage = 1;
      this.updateDisplay();
    }

    filterByCategory(category) {
      const rowsToFilter = this.currentTab === 'open' ? this.openRows : this.closedRows;

      if (!category) {
        this.filteredRows = rowsToFilter;
      } else {
        this.filteredRows = rowsToFilter.filter(row => {
          return row.getAttribute('data-category') === category;
        });
      }

      this.currentPage = 1;
      this.updateDisplay();
    }
  }

  // Filter functions
  function applyFilters() {
    // Implement if needed
  }

  function clearFilters() {
    document.getElementById('searchTickets').value = '';
    document.getElementById('categoryFilter').value = '';

    if (window.paginationManager) {
      const rowsToFilter = window.paginationManager.currentTab === 'open' ?
        window.paginationManager.openRows : window.paginationManager.closedRows;
      window.paginationManager.filteredRows = rowsToFilter;
      window.paginationManager.currentPage = 1;
      window.paginationManager.updateDisplay();
    }
  }

  // Tab change handler
  document.querySelectorAll('.nav-tabs .nav-link').forEach(tab => {
<<<<<<< HEAD
    tab.addEventListener('click', function () {
=======
    tab.addEventListener('click', function() {
>>>>>>> origin/main
      // Clear filters when switching tabs
      clearFilters();
    });
  });

  // Row click handler
<<<<<<< HEAD
  document.querySelectorAll(".support-row").forEach(function (row) {
    row.addEventListener("click", function (e) {
=======
  document.querySelectorAll(".support-row").forEach(function(row) {
    row.addEventListener("click", function(e) {
>>>>>>> origin/main
      // Only navigate if clicked outside the action button
      if (!e.target.closest('.btn')) {
        const link = this.querySelector('a.btn');
        if (link) {
          window.location.href = link.href;
        }
      }
    });

    // Hover effect
<<<<<<< HEAD
    row.addEventListener("mouseenter", function () {
=======
    row.addEventListener("mouseenter", function() {
>>>>>>> origin/main
      this.style.backgroundColor = "rgba(99, 102, 241, 0.03)";
      this.style.cursor = "pointer";
    });

<<<<<<< HEAD
    row.addEventListener("mouseleave", function () {
=======
    row.addEventListener("mouseleave", function() {
>>>>>>> origin/main
      this.style.backgroundColor = "";
    });
  });

  // DOM Ready
<<<<<<< HEAD
  document.addEventListener('DOMContentLoaded', function () {
=======
  document.addEventListener('DOMContentLoaded', function() {
>>>>>>> origin/main
    // Initialize pagination manager
    window.paginationManager = new PaginationManager();

    // Hide the old static pagination
    const oldPagination = document.querySelector('.d-flex.justify-content-between.align-items-center.px-2.mt-3');
    if (oldPagination) {
      oldPagination.style.display = 'none';
    }
  });
</script>

<?php echo $this->include('admin/footer') ?>
<?php echo $this->endSection() ?>