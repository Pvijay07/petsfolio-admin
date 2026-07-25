<?= $this->extend('admin/includes/header'); ?>
<?= $this->section('content'); ?>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?= $this->include('admin/nav_bar') ?>
  </div>

  <div class="conatiner-fluid content-inner pb-0 px-4" id="page_layout">
    <!-- Modern Header Section -->
    <div class="container-fluid bg-gradient-primary rounded-3 p-0 mb-4">
      <div class=" text-white p-3  shadow position-relative overflow-hidden">
        <div class="position-relative z-1">
          <h1 class="h2 fw-bold mb-2 text-white">Client Segregation Report</h1>
          <p class="text-white-75 mb-0">Track and manage clients based on their app activity and job status
          </p>
        </div>
        <i class="bi bi-people-fill position-absolute end-0 bottom-0 opacity-10"
          style="font-size: 14rem; transform: rotate(12deg); margin-right: -3rem; margin-bottom: -3rem;"></i>
      </div>
      <div class="p-3 pt-0" style="max-width: max-content !important;">
        <div class="card mb-0 border-0 shadow-sm rounded-5">
          <ul class="nav nav-tabs-modern p-2" id="clientTabs" role="tablist">
            <li class="nav-item mb-0" role="presentation">
              <a class="nav-link-modern active" data-bs-toggle="tab" href="#downloaded" role="tab">
                <i class="bi bi-phone me-2"></i>Just App Downloaded
              </a>
            </li>
            <li class="nav-item mb-0" role="presentation">
              <a class="nav-link-modern" data-bs-toggle="tab" href="#posted" role="tab">
                <i class="bi bi-briefcase me-2"></i>Jobs Posted
              </a>
            </li>
            <li class="nav-item mb-0" role="presentation">
              <a class="nav-link-modern" data-bs-toggle="tab" href="#assigned" role="tab">
                <i class="bi bi-check2-circle me-2"></i>Jobs Assigned
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Date Range Filter Card -->
    <div class="card border-0 shadow-sm mb-4 rounded-3">
      <div class="card-body p-3">
        <form method="GET" action="">
          <div class="row  align-items-end">
            <div class="col-md-3">
              <label class="form-label-modern">Start Date</label>
              <input type="date" name="start_date" class="form-control-modern" value="<?= $start ?>">
            </div>
            <div class="col-md-3">
              <label class="form-label-modern">End Date</label>
              <input type="date" name="end_date" class="form-control-modern" value="<?= $end ?>">
            </div>
            <div class="col-md-3 ">
              <div class="input-group form-control-modern" style="padding: 0 !important;">
                <span class="input-group-text bg-black border-end-0 text-white">
                  <i class="bi bi-search"></i>
                </span>
                <input type="text" class="form-control border-start-0 table-search" placeholder="Search..."
                  data-table="downloaded">
              </div>
            </div>
            <div class="col-md-3 d-flex align-items-end">
              <button type="submit" class="btn-modern-primary w-100">
                <i class="bi bi-funnel me-2"></i>Apply Filter
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Client Segment Tabs -->
    <div class="card border-0 shadow-sm mb-4 rounded-3">
      <div class="card-body p-0">
        <div class="tab-content p-4">
          <div class="tab-pane fade show active" id="downloaded" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h5 class="fw-bold text-dark mb-0">
                <i class="bi bi-phone me-2 text-primary"></i>
                Clients Who Downloaded but NOT Posted a Job
              </h5>
              <span class="badge-status-info rounded-pill px-3 py-2">
                <i class="bi bi-people me-1"></i>
                <span id="downloadedTotalCount"><?= count($downloaded_clients) ?></span> Clients
              </span>
            </div>

            <div class="table-responsive">
              <table class="table table-modern rounded-sm table-hover" id="downloadedTable">
                <thead>
                  <tr>
                    <th class="text-center" data-sort="sno">S.No</th>
                    <th data-sort="name">Client Name</th>
                    <th data-sort="phone">Phone</th>
                    <th data-sort="date">Download Date</th>
                    <th>Comment (Sales)</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody id="downloadedTableBody">
                  <!-- Data will be populated by JavaScript -->
                </tbody>
              </table>
            </div>

            <!-- Pagination Controls -->
            <div class="d-flex justify-content-end align-items-center px-3 py-3 border-top" id="downloadedPagination">
              <div class="btn-group mt-0 pt-0 border-0">
                <button class="btn bg-primary btn-sm text-white downloaded-prev-page" disabled>
                  <i class="bi bi-chevron-left"></i> Previous
                </button>
                <div class="downloaded-page-numbers" style="display: flex; gap: 2px;"></div>
                <button class="btn bg-primary btn-sm text-white downloaded-next-page">
                  Next <i class="bi bi-chevron-right"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- ========================
                     2️⃣ JOBS POSTED
                ============================ -->
          <div class="tab-pane fade" id="posted" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h5 class="fw-bold text-dark mb-0">
                <i class="bi bi-briefcase me-2 text-primary"></i>
                Clients Who Posted a Job (But Job Not Assigned)
              </h5>
              <span class="badge-status-info rounded-pill px-3 py-2">
                <i class="bi bi-people me-1"></i>
                <span id="postedTotalCount"><?= count($posted_clients) ?></span> Clients
              </span>
            </div>

           

            <div class="table-responsive">
              <table class="table table-modern rounded-sm table-hover" id="postedTable">
                <thead>
                  <tr>
                    <th class="text-center" data-sort="sno">S.No</th>
                    <th data-sort="name">Client Name</th>
                    <th data-sort="phone">Phone</th>
                    <th class="text-center" data-sort="jobs">Total Jobs Posted</th>
                    <th data-sort="date">Last Job Date</th>
                    <th>Comment (Sales)</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody id="postedTableBody">
                  <!-- Data will be populated by JavaScript -->
                </tbody>
              </table>
            </div>

            <!-- Pagination Controls -->
            <div class="d-flex justify-content-end align-items-center px-3 py-3 border-top" id="postedPagination">
              <div class="btn-group mt-0 pt-0 border-0">
                <button class="btn bg-primary btn-sm text-white posted-prev-page" disabled>
                  <i class="bi bi-chevron-left"></i> Previous
                </button>
                <div class="posted-page-numbers" style="display: flex; gap: 2px;"></div>
                <button class="btn bg-primary btn-sm text-white posted-next-page">
                  Next <i class="bi bi-chevron-right"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- ========================
                     3️⃣ JOBS ASSIGNED
                ============================ -->
          <div class="tab-pane fade" id="assigned" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h5 class="fw-bold text-dark mb-0">
                <i class="bi bi-check2-circle me-2 text-primary"></i>
                Clients Who Have Jobs Assigned
              </h5>
              <span class="badge-status-info rounded-pill px-3 py-2">
                <i class="bi bi-people me-1"></i>
                <span id="assignedTotalCount"><?= count($assigned_clients) ?></span> Clients
              </span>
            </div>

            

            <div class="table-responsive">
              <table class="table table-modern rounded-sm table-hover" id="assignedTable">
                <thead>
                  <tr>
                    <th class="text-center" data-sort="sno">S.No</th>
                    <th data-sort="name">Client Name</th>
                    <th data-sort="phone">Phone</th>
                    <th class="text-center" data-sort="jobs">Total Jobs Assigned</th>
                    <th data-sort="date">Last Assigned Date</th>
                  </tr>
                </thead>
                <tbody id="assignedTableBody">
                  <!-- Data will be populated by JavaScript -->
                </tbody>
              </table>
            </div>

            <!-- Pagination Controls -->
            <div class="d-flex justify-content-end align-items-center px-3 py-3 border-top" id="assignedPagination">
              <div class="btn-group mt-0 pt-0 border-0">
                <button class="btn bg-primary btn-sm text-white assigned-prev-page" disabled>
                  <i class="bi bi-chevron-left"></i> Previous
                </button>
                <div class="assigned-page-numbers" style="display: flex; gap: 2px;"></div>
                <button class="btn bg-primary btn-sm text-white assigned-next-page">
                  Next <i class="bi bi-chevron-right"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?= $this->include('admin/footer'); ?>
</main>

<style>
  /* Modern Color Variables - Matching bookings management */
  :root {
    --primary-light: #c6d2ff;
    --primary-main: #3f30e3;
    --primary-dark: #2b1fb3;
    --primary-gradient: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%);
    --secondary-light: #f0f2ff;
    --success-light: rgba(34, 197, 94, 0.1);
    --success-border: rgba(34, 197, 94, 0.2);
    --success-text: #16a34a;
    --warning-light: rgba(245, 158, 11, 0.1);
    --warning-border: rgba(245, 158, 11, 0.2);
    --warning-text: #f59e0b;
    --danger-light: rgba(239, 68, 68, 0.1);
    --danger-border: rgba(239, 68, 68, 0.2);
    --danger-text: #ef4444;
    --info-light: rgba(59, 130, 246, 0.1);
    --info-border: rgba(59, 130, 246, 0.2);
    --info-text: #3b82f6;
  }

  .bg-gradient-primary {
    background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
  }

  /* Modern Card Styles */
  .card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
    transition: all 0.3s ease;
  }

  .card:hover {
    box-shadow: 0 8px 30px rgba(63, 48, 227, 0.08);
  }

  /* Modern Table Styles */
  .table-modern {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
  }

  .table-modern thead th {
    background: var(--primary-light) !important;
    color: var(--primary-dark) !important;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 1rem !important;
    border-bottom: 2px solid rgba(63, 48, 227, 0.1);
    white-space: nowrap;
    cursor: pointer;
  }

  .table-modern thead th i {
    font-size: 0.7rem;
    opacity: 0.5;
    margin-left: 5px;
  }

  .table-modern thead th.sort-asc i,
  .table-modern thead th.sort-desc i {
    opacity: 1;
  }

  .table-modern tbody tr {
    transition: all 0.2s ease;
  }

  .table-modern tbody tr:hover {
    background: var(--secondary-light) !important;
  }

  .table-modern td {
    padding: 1rem 0.75rem !important;
    vertical-align: middle;
  }

  /* Avatar Styles */
  .avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--primary-light);
    color: var(--primary-dark);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1rem;
    border: 2px solid white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    flex-shrink: 0;
  }

  /* Badge Styles */
  .badge-status-info {
    background: var(--info-light) !important;
    color: var(--info-text) !important;
    border: 1px solid var(--info-border) !important;
    font-weight: 500;
    font-size: 0.8rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
  }

  .badge-status-success {
    background: var(--success-light) !important;
    color: var(--success-text) !important;
    border: 1px solid var(--success-border) !important;
    font-weight: 500;
    font-size: 0.8rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
  }

  /* Modern Tabs */
  .nav-tabs-modern {
    display: flex;
    flex-wrap: wrap;
    margin: 0;
    list-style: none;
    background: transparent;
  }

  .nav-link-modern {
    display: flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    margin: 0 0.25rem;
    font-weight: 500;
    font-size: 0.95rem;
    color: #6c757d;
    border: none;
    border-radius: 40px;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
  }

  .nav-link-modern:hover {
    color: var(--primary-main);
    background: var(--primary-light);
  }

  .nav-link-modern.active {
    color: white;
    background: var(--primary-gradient);
    box-shadow: 0 4px 10px rgba(63, 48, 227, 0.3);
  }

  .nav-link-modern i {
    font-size: 1rem;
  }

  /* Modern Form Controls */
  .form-control-modern {
    border: 1px solid #e9ecef;
    border-radius: 40px !important;
    padding: 0.6rem 1rem !important;
    transition: all 0.3s ease;
    width: 100%;
    font-size: 0.9rem;
    resize: vertical;
  }

  .form-control-modern:focus {
    border-color: var(--primary-main);
    box-shadow: 0 0 0 3px rgba(63, 48, 227, 0.1);
    outline: none;
  }

  .form-label-modern {
    font-weight: 500;
    color: var(--primary-dark);
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    display: block;
  }

  /* Modern Buttons */
  .btn-modern-primary {
    background: var(--primary-gradient);
    color: white;
    border: none;
    padding: 0.6rem 1.5rem;
    border-radius: 40px;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
  }

  .btn-modern-primary:hover {
    background: linear-gradient(135deg, #2b1fb3 0%, #3f30e3 100%);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 6px 15px rgba(63, 48, 227, 0.3);
  }

  .btn-modern-primary.btn-sm {
    padding: 0.4rem 1rem;
    font-size: 0.85rem;
  }

  /* Pagination Styles */
  .page-btn {
    margin: 0 2px;
  }

  .page-btn.active {
    background: var(--primary-main) !important;
    color: white !important;
    border-color: var(--primary-main) !important;
  }

  /* Responsive Design */
  @media (max-width: 768px) {
    .nav-link-modern {
      padding: 0.5rem 1rem;
      font-size: 0.85rem;
    }

    .avatar-circle {
      width: 32px;
      height: 32px;
      font-size: 0.85rem;
    }

    .table-modern thead th {
      font-size: 0.75rem;
      padding: 0.75rem !important;
    }

    .table-modern td {
      padding: 0.75rem 0.5rem !important;
    }

    .btn-modern-primary.btn-sm {
      padding: 0.3rem 0.8rem;
      font-size: 0.8rem;
    }
  }

  @media (max-width: 576px) {
    .nav-link-modern {
      width: 100%;
      justify-content: center;
      margin: 0.25rem 0;
    }

    .nav-tabs-modern {
      flex-direction: column;
    }

    .form-control-modern {
      font-size: 0.85rem;
      padding: 0.5rem 0.8rem !important;
    }
  }

  /* Utility Classes */
  .bg-primary-light {
    background: var(--primary-light) !important;
  }

  .text-primary-dark {
    color: var(--primary-dark) !important;
  }
</style>

<script>
  // Client data from PHP
  const downloadedClients = <?= json_encode($downloaded_clients) ?>;
  const postedClients = <?= json_encode($posted_clients) ?>;
  const assignedClients = <?= json_encode($assigned_clients) ?>;

  // State management for each table
  const tableStates = {
    downloaded: {
      currentPage: 1,
      entriesPerPage: 10,
      searchTerm: '',
      sortColumn: 'sno',
      sortDirection: 'asc',
      filteredData: [...downloadedClients],
      originalData: [...downloadedClients]
    },
    posted: {
      currentPage: 1,
      entriesPerPage: 10,
      searchTerm: '',
      sortColumn: 'sno',
      sortDirection: 'asc',
      filteredData: [...postedClients],
      originalData: [...postedClients]
    },
    assigned: {
      currentPage: 1,
      entriesPerPage: 10,
      searchTerm: '',
      sortColumn: 'sno',
      sortDirection: 'asc',
      filteredData: [...assignedClients],
      originalData: [...assignedClients]
    }
  };

  document.addEventListener('DOMContentLoaded', function () {
    // Initialize all tables
    renderTable('downloaded');
    renderTable('posted');
    renderTable('assigned');

    setupEventListeners();
    setupSaveCommentHandlers();
  });

  function setupEventListeners() {
    // Search inputs
    document.querySelectorAll('.table-search').forEach(input => {
      input.addEventListener('input', function () {
        const tableId = this.dataset.table;
        tableStates[tableId].searchTerm = this.value.toLowerCase();
        tableStates[tableId].currentPage = 1;
        filterData(tableId);
      });
    });

    // Entries per page selects
    document.querySelectorAll('.entries-per-page').forEach(select => {
      select.addEventListener('change', function () {
        const tableId = this.dataset.table;
        tableStates[tableId].entriesPerPage = this.value === 'all' ?
          tableStates[tableId].filteredData.length : parseInt(this.value);
        tableStates[tableId].currentPage = 1;
        renderTable(tableId);
      });
    });

    // Sort on header click
    document.querySelectorAll('#downloadedTable thead th[data-sort], #postedTable thead th[data-sort], #assignedTable thead th[data-sort]').forEach(header => {
      header.addEventListener('click', function () {
        const tableId = this.closest('table').id.replace('Table', '');
        const column = this.dataset.sort;

        if (tableStates[tableId].sortColumn === column) {
          tableStates[tableId].sortDirection = tableStates[tableId].sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
          tableStates[tableId].sortColumn = column;
          tableStates[tableId].sortDirection = 'asc';
        }

        // Update sort icons
        this.closest('thead').querySelectorAll('th').forEach(th => {
          th.classList.remove('sort-asc', 'sort-desc');
          const icon = th.querySelector('i');
          if (icon) icon.className = 'bi bi-arrow-down-up ms-1';
        });

        this.classList.add(tableStates[tableId].sortDirection === 'asc' ? 'sort-asc' : 'sort-desc');
        const icon = this.querySelector('i');
        if (icon) {
          icon.className = tableStates[tableId].sortDirection === 'asc' ? 'bi bi-arrow-up ms-1' : 'bi bi-arrow-down ms-1';
        }

        filterData(tableId);
      });
    });
  }

  function filterData(tableId) {
    const state = tableStates[tableId];

    // Apply search filter
    state.filteredData = state.originalData.filter(item => {
      const searchableText = `
        ${item.name || ''} 
        ${item.phone || ''} 
        ${item.download_date || ''} 
        ${item.last_post_date || ''} 
        ${item.last_assigned_date || ''}
      `.toLowerCase();

      return searchableText.includes(state.searchTerm);
    });

    // Apply sorting
    state.filteredData.sort((a, b) => {
      let valA, valB;

      switch (state.sortColumn) {
        case 'name':
          valA = a.name || '';
          valB = b.name || '';
          break;
        case 'phone':
          valA = a.phone || '';
          valB = b.phone || '';
          break;
        case 'date':
          valA = a.download_date || a.last_post_date || a.last_assigned_date || '';
          valB = b.download_date || b.last_post_date || b.last_assigned_date || '';
          break;
        case 'jobs':
          valA = a.jobs_count || a.assigned_count || 0;
          valB = b.jobs_count || b.assigned_count || 0;
          break;
        default:
          return 0;
      }

      if (valA < valB) return state.sortDirection === 'asc' ? -1 : 1;
      if (valA > valB) return state.sortDirection === 'asc' ? 1 : -1;
      return 0;
    });

    // Update total count display
    const countElement = document.getElementById(`${tableId}TotalCount`);
    if (countElement) {
      countElement.textContent = state.filteredData.length;
    }

    renderTable(tableId);
  }

  function renderTable(tableId) {
    const state = tableStates[tableId];
    const tbody = document.getElementById(`${tableId}TableBody`);

    if (!tbody) return;

    const start = state.entriesPerPage === 'all' ? 0 : (state.currentPage - 1) * state.entriesPerPage;
    const end = state.entriesPerPage === 'all' ? state.filteredData.length : Math.min(start + state.entriesPerPage, state.filteredData.length);
    const pageData = state.filteredData.slice(start, end);

    let html = '';
    let serialNumber = start + 1;

    pageData.forEach(item => {
      if (tableId === 'downloaded') {
        html += renderDownloadedRow(item, serialNumber++);
      } else if (tableId === 'posted') {
        html += renderPostedRow(item, serialNumber++);
      } else if (tableId === 'assigned') {
        html += renderAssignedRow(item, serialNumber++);
      }
    });

    tbody.innerHTML = html;
    updatePaginationControls(tableId);
    setupSaveCommentHandlers(); // Re-attach save handlers after rendering
  }

  function renderDownloadedRow(item, index) {
    return `
      <tr>
        <td class="text-center align-middle">${index}</td>
        <td class="align-middle">
          <div class="d-flex align-items-center">
            <div class="avatar-circle me-3">
              ${(item.name || 'C').charAt(0).toUpperCase()}
            </div>
            <span class="fw-medium text-dark">${item.name || '--'}</span>
          </div>
        </td>
        <td class="align-middle">
          <div class="d-flex align-items-center">
            <i class="bi bi-telephone text-primary me-2"></i>
            <span class="text-dark">${item.phone || '--'}</span>
          </div>
        </td>
        <td class="align-middle">
          <div class="d-flex align-items-center">
            <i class="bi bi-calendar3 text-primary me-2"></i>
            <span class="text-dark">${item.download_date || '--'}</span>
          </div>
        </td>
        <td class="align-middle" style="min-width: 250px;">
          <textarea class="form-control-modern" name="comment_${item.id}" placeholder="Enter comment..." rows="2">${item.comment || ''}</textarea>
        </td>
        <td class="text-center align-middle">
          <button class="btn-modern-primary btn-sm save-comment px-4" data-id="${item.id}">
            <i class="bi bi-check2 me-2"></i>Save
          </button>
        </td>
      </tr>
    `;
  }

  function renderPostedRow(item, index) {
    return `
      <tr>
        <td class="text-center align-middle">${index}</td>
        <td class="align-middle">
          <div class="d-flex align-items-center">
            <div class="avatar-circle me-3">
              ${(item.name || 'C').charAt(0).toUpperCase()}
            </div>
            <span class="fw-medium text-dark">${item.name || '--'}</span>
          </div>
        </td>
        <td class="align-middle">
          <div class="d-flex align-items-center">
            <i class="bi bi-telephone text-primary me-2"></i>
            <span class="text-dark">${item.phone || '--'}</span>
          </div>
        </td>
        <td class="text-center align-middle">
          <span class="badge-status-info rounded-pill px-3 py-2">
            ${item.jobs_count || 0}
          </span>
        </td>
        <td class="align-middle">
          <div class="d-flex align-items-center">
            <i class="bi bi-calendar3 text-primary me-2"></i>
            <span class="text-dark">${item.last_post_date || '--'}</span>
          </div>
        </td>
        <td class="align-middle" style="min-width: 250px;">
          <textarea class="form-control-modern" name="comment_${item.id}" placeholder="Enter comment..." rows="2">${item.comment || ''}</textarea>
        </td>
        <td class="text-center align-middle">
          <button class="btn-modern-primary btn-sm save-comment px-4" data-id="${item.id}">
            <i class="bi bi-check2 me-2"></i>Save
          </button>
        </td>
      </tr>
    `;
  }

  function renderAssignedRow(item, index) {
    return `
      <tr>
        <td class="text-center align-middle">${index}</td>
        <td class="align-middle">
          <div class="d-flex align-items-center">
            <div class="avatar-circle me-3">
              ${(item.name || 'C').charAt(0).toUpperCase()}
            </div>
            <span class="fw-medium text-dark">${item.name || '--'}</span>
          </div>
        </td>
        <td class="align-middle">
          <div class="d-flex align-items-center">
            <i class="bi bi-telephone text-primary me-2"></i>
            <span class="text-dark">${item.phone || '--'}</span>
          </div>
        </td>
        <td class="text-center align-middle">
          <span class="badge-status-success rounded-pill px-3 py-2">
            ${item.assigned_count || 0}
          </span>
        </td>
        <td class="align-middle">
          <div class="d-flex align-items-center">
            <i class="bi bi-calendar3 text-primary me-2"></i>
            <span class="text-dark">${item.last_assigned_date || '--'}</span>
          </div>
        </td>
      </tr>
    `;
  }

  function updatePaginationControls(tableId) {
    const state = tableStates[tableId];
    const totalPages = Math.ceil(state.filteredData.length / (state.entriesPerPage === 'all' ? state.filteredData.length : state.entriesPerPage));

    // Update prev/next buttons
    const prevBtn = document.querySelector(`.${tableId}-prev-page`);
    const nextBtn = document.querySelector(`.${tableId}-next-page`);

    if (prevBtn) {
      prevBtn.disabled = state.currentPage === 1 || totalPages === 0;
      prevBtn.onclick = () => {
        if (state.currentPage > 1) {
          state.currentPage--;
          renderTable(tableId);
        }
      };
    }

    if (nextBtn) {
      nextBtn.disabled = state.currentPage === totalPages || totalPages === 0;
      nextBtn.onclick = () => {
        if (state.currentPage < totalPages) {
          state.currentPage++;
          renderTable(tableId);
        }
      };
    }

    // Generate page numbers
    const pageNumbersContainer = document.querySelector(`.${tableId}-page-numbers`);
    if (!pageNumbersContainer) return;

    let pageNumbersHtml = '';
    const maxVisiblePages = 5;

    if (totalPages <= maxVisiblePages) {
      for (let i = 1; i <= totalPages; i++) {
        pageNumbersHtml += getPageButtonHtml(i, state.currentPage, tableId);
      }
    } else {
      if (state.currentPage <= 3) {
        for (let i = 1; i <= 4; i++) {
          pageNumbersHtml += getPageButtonHtml(i, state.currentPage, tableId);
        }
        pageNumbersHtml += `<span class="btn btn-sm bg-secondary border-0" disabled>...</span>`;
        pageNumbersHtml += getPageButtonHtml(totalPages, state.currentPage, tableId);
      } else if (state.currentPage >= totalPages - 2) {
        pageNumbersHtml += getPageButtonHtml(1, state.currentPage, tableId);
        pageNumbersHtml += `<span class="btn btn-sm bg-secondary border-0" disabled>...</span>`;
        for (let i = totalPages - 3; i <= totalPages; i++) {
          pageNumbersHtml += getPageButtonHtml(i, state.currentPage, tableId);
        }
      } else {
        pageNumbersHtml += getPageButtonHtml(1, state.currentPage, tableId);
        pageNumbersHtml += `<span class="btn btn-sm bg-secondary border-0" disabled>...</span>`;
        for (let i = state.currentPage - 1; i <= state.currentPage + 1; i++) {
          pageNumbersHtml += getPageButtonHtml(i, state.currentPage, tableId);
        }
        pageNumbersHtml += `<span class="btn btn-sm bg-secondary border-0" disabled>...</span>`;
        pageNumbersHtml += getPageButtonHtml(totalPages, state.currentPage, tableId);
      }
    }

    pageNumbersContainer.innerHTML = pageNumbersHtml;

    // Add click events to page buttons
    pageNumbersContainer.querySelectorAll('.page-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        state.currentPage = parseInt(this.dataset.page);
        renderTable(tableId);
      });
    });
  }

  function getPageButtonHtml(pageNum, currentPage, tableId) {
    const activeClass = pageNum === currentPage ? 'btn-primary active' : 'btn-outline-primary';
    return `<button class="btn btn-sm ${activeClass} page-btn" data-page="${pageNum}">${pageNum}</button>`;
  }

  function setupSaveCommentHandlers() {
    document.querySelectorAll(".save-comment").forEach(btn => {
      // Remove existing listeners to prevent duplicates
      btn.removeEventListener('click', saveCommentHandler);
      btn.addEventListener('click', saveCommentHandler);
    });
  }

  function saveCommentHandler(e) {
    const btn = e.currentTarget;
    const id = btn.dataset.id;
    const comment = document.querySelector(`[name='comment_${id}']`).value;

    // Show loading state
    btn.innerHTML = '<i class="bi bi-hourglass me-2"></i>Saving...';
    btn.disabled = true;

    fetch("<?= base_url('save-client-comment') ?>", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({
        client_id: id,
        comment: comment
      })
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          btn.innerHTML = '<i class="bi bi-check2 me-2"></i>Saved!';
          setTimeout(() => {
            btn.innerHTML = '<i class="bi bi-check2 me-2"></i>Save';
            btn.disabled = false;
          }, 2000);
        } else {
          throw new Error('Save failed');
        }
      })
      .catch(error => {
        btn.innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i>Error';
        setTimeout(() => {
          btn.innerHTML = '<i class="bi bi-check2 me-2"></i>Save';
          btn.disabled = false;
        }, 2000);
      });
  }
</script>

<?= $this->endSection() ?>