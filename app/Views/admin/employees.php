<?= $this->extend('admin/includes/header'); ?>
<!--start content-->
<?= $this->section('content'); ?>

<<<<<<< HEAD
<main class="main-content">
  <div class="position-relative iq-banner">
    <!--Nav Start-->
=======
<style>
  /* Modern CSS Variables & SaaS Design System */
  :root {
    --nl-primary: #4f46e5;
    --nl-primary-hover: #4338ca;
    --nl-primary-light: rgba(79, 70, 229, 0.08);
    --nl-card-radius: 16px;
    --nl-pill-radius: 30px;
    --nl-border: #e2e8f0;
    --nl-bg-subtle: #f8fafc;
    --nl-text-main: #0f172a;
    --nl-text-muted: #64748b;
    --nl-shadow-sm: 0 1px 3px rgba(0,0,0,0.05), 0 1px 2px rgba(0,0,0,0.03);
    --nl-shadow-md: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -2px rgba(0,0,0,0.05);
    --nl-shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.08), 0 4px 6px -4px rgba(0,0,0,0.04);
  }

  /* Hero Banner */
  .nl-hero-banner {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 60%, #312e81 100%);
    border-radius: var(--nl-card-radius);
    padding: 2rem 2.25rem;
    position: relative;
    overflow: hidden;
    color: #ffffff;
    box-shadow: 0 12px 28px -6px rgba(15, 23, 42, 0.35);
    margin-bottom: 1.75rem;
  }
  .nl-hero-banner::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 480px;
    height: 480px;
    background: radial-gradient(circle, rgba(99, 102, 241, 0.18) 0%, rgba(255,255,255,0) 70%);
    pointer-events: none;
    border-radius: 50%;
  }
  .nl-live-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(8px);
    padding: 0.3rem 0.85rem;
    border-radius: var(--nl-pill-radius);
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: #e0e7ff;
    border: 1px solid rgba(255, 255, 255, 0.18);
    margin-bottom: 0.75rem;
  }
  .nl-live-pill .dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.35);
  }
  .nl-hero-title {
    font-size: 1.75rem;
    font-weight: 800;
    letter-spacing: -0.025em;
    color: #ffffff;
    margin-bottom: 0.35rem;
  }
  .nl-hero-desc {
    color: #cbd5e1;
    font-size: 0.9rem;
    margin-bottom: 0;
    max-width: 580px;
  }
  .nl-btn-create {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: #ffffff;
    color: #1e1b4b !important;
    font-weight: 700;
    font-size: 0.88rem;
    padding: 0.7rem 1.35rem;
    border-radius: 12px;
    border: none;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    text-decoration: none;
    cursor: pointer;
  }
  .nl-btn-create:hover {
    background: #f1f5f9;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    color: #0f172a !important;
  }

  /* Hero Stat Cards */
  .nl-stat-card {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 14px;
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.2s ease;
  }
  .nl-stat-card:hover {
    background: rgba(255, 255, 255, 0.13);
    transform: translateY(-2px);
  }
  .nl-stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
  }
  .nl-stat-value {
    font-size: 1.5rem;
    font-weight: 800;
    line-height: 1.1;
    color: #ffffff;
  }
  .nl-stat-label {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #94a3b8;
    margin-bottom: 0.2rem;
  }

  /* Filter Toolbar */
  .nl-filter-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 1rem 1.25rem;
    border: 1px solid var(--nl-border);
    box-shadow: var(--nl-shadow-sm);
    margin-bottom: 1.5rem;
  }
  .nl-search-wrap {
    position: relative;
    flex: 1;
    min-width: 240px;
  }
  .nl-search-wrap i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--nl-text-muted);
    font-size: 0.95rem;
    pointer-events: none;
  }
  .nl-search-input {
    width: 100%;
    padding: 0.65rem 1rem 0.65rem 2.45rem;
    background: var(--nl-bg-subtle);
    border: 1px solid var(--nl-border);
    border-radius: 10px;
    font-size: 0.88rem;
    color: var(--nl-text-main);
    transition: all 0.2s ease;
  }
  .nl-search-input:focus {
    background: #ffffff;
    border-color: var(--nl-primary);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
    outline: none;
  }
  .nl-select {
    padding: 0.65rem 2rem 0.65rem 1rem;
    background-color: var(--nl-bg-subtle);
    border: 1px solid var(--nl-border);
    border-radius: 10px;
    font-size: 0.88rem;
    color: var(--nl-text-main);
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  .nl-select:focus {
    background-color: #ffffff;
    border-color: var(--nl-primary);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
    outline: none;
  }
  .nl-btn-clear {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.65rem 1rem;
    background: var(--nl-bg-subtle);
    border: 1px solid var(--nl-border);
    border-radius: 10px;
    font-size: 0.84rem;
    font-weight: 600;
    color: var(--nl-text-muted);
    transition: all 0.2s ease;
    cursor: pointer;
    border: 1px solid var(--nl-border);
  }
  .nl-btn-clear:hover {
    background: #f1f5f9;
    color: #ef4444;
    border-color: #fca5a5;
  }

  /* Table Card & Modern Table */
  .nl-table-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid var(--nl-border);
    box-shadow: var(--nl-shadow-sm);
    overflow: hidden;
    margin-bottom: 1.5rem;
  }
  .nl-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-bottom: 0;
  }
  .nl-table thead th {
    background: #f8fafc;
    color: #475569;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    padding: 0.95rem 1rem;
    border-bottom: 1px solid var(--nl-border);
    border-top: none;
    white-space: nowrap;
  }
  .nl-table tbody tr {
    transition: background-color 0.15s ease;
  }
  .nl-table tbody tr:hover {
    background-color: #f8faff;
  }
  .nl-table tbody td {
    padding: 1rem 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.88rem;
    color: var(--nl-text-main);
  }
  .nl-table tbody tr:last-child td {
    border-bottom: none;
  }

  /* Employee Avatar */
  .nl-user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
    color: #ffffff;
    font-weight: 700;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 2px 6px rgba(79, 70, 229, 0.25);
  }

  /* Action Buttons */
  .action-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.82rem;
    border: 1px solid transparent;
    transition: all 0.15s ease;
    cursor: pointer;
    text-decoration: none;
  }
  .action-btn-view {
    background: #f0fdf4;
    color: #16a34a;
    border-color: #bbf7d0;
  }
  .action-btn-view:hover {
    background: #16a34a;
    color: #ffffff;
  }
  .action-btn-perm {
    background: #fefce8;
    color: #ca8a04;
    border-color: #fef08a;
  }
  .action-btn-perm:hover {
    background: #ca8a04;
    color: #ffffff;
  }
  .action-btn-edit {
    background: #eff6ff;
    color: #2563eb;
    border-color: #bfdbfe;
  }
  .action-btn-edit:hover {
    background: #2563eb;
    color: #ffffff;
  }
  .action-btn-delete {
    background: #fef2f2;
    color: #dc2626;
    border-color: #fecaca;
  }
  .action-btn-delete:hover {
    background: #dc2626;
    color: #ffffff;
  }

  /* Designation Pill Badge */
  .nl-role-pill {
    display: inline-flex;
    align-items: center;
    padding: 0.32rem 0.85rem;
    background: rgba(79, 70, 229, 0.08);
    color: #4338ca;
    border: 1px solid rgba(79, 70, 229, 0.2);
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.8rem;
  }

  /* Modal Backdrop & Layout */
  body.modal-open {
    overflow: hidden !important;
    padding-right: 0 !important;
  }
  .modal-backdrop {
    background-color: rgba(15, 23, 42, 0.75) !important;
  }
  .permissions-container {
    max-height: 100%;
  }
  .module-section {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    margin-bottom: 18px;
    background: #ffffff;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    overflow: hidden;
  }
  .module-header {
    background: #f8fafc;
    padding: 12px 18px;
    margin: 0;
    font-size: 14px;
    font-weight: 700;
    color: #334155;
    border-bottom: 1px solid #e2e8f0;
  }
  .permissions-grid {
    padding: 16px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 12px;
  }
  .permission-item {
    display: flex;
    align-items: center;
    padding: 10px 14px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    transition: all 0.2s ease;
  }
  .permission-item:hover {
    border-color: #cbd5e1;
    background: #ffffff;
  }
  .permission-checkbox {
    margin-right: 10px;
    width: 1.15em !important;
    height: 1.15em !important;
    cursor: pointer;
  }
  .permission-label {
    font-size: 13.5px;
    color: #475569;
    margin: 0;
    cursor: pointer;
    user-select: none;
    font-weight: 500;
  }
  .select-all-section {
    background: rgba(79, 70, 229, 0.06);
    border: 1px solid rgba(79, 70, 229, 0.2);
    border-radius: 12px;
    padding: 14px 18px;
    margin-bottom: 18px;
  }
  .modal-body::-webkit-scrollbar {
    width: 6px;
  }
  .modal-body::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
  }
</style>

<main class="main-content">
  <div class="position-relative iq-banner">
>>>>>>> origin/main
    <?= $this->include('admin/nav_bar') ?>
  </div>

  <div class="container-fluid content-inner px-4" id="page_layout">

<<<<<<< HEAD
    <!-- Modern Header Section -->
    <div class="container-fluid p-0 bg-gradient-primary rounded-3 mb-4">
      <div class=" text-white p-5  shadow position-relative overflow-hidden">
        <div class="position-relative z-1">
          <h1 class="h2 fw-bold mb-2 text-white">Employees Management</h1>
          <p class="text-white-75 mb-0">Manage your team members, roles, and permissions</p>
        </div>
        <i class="bi bi-people position-absolute end-0 bottom-0 opacity-10"
          style="font-size: 14rem; transform: rotate(12deg); margin-right: -3rem; margin-bottom: -3rem;"></i>
      </div>
      <div class="p-3">
        <div class="card border-0 mb-0 shadow-sm">
          <div class="card-body p-3">
            <div
              class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
              <div
                class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-3 w-100 w-md-auto">
                <div class="input-group flex-grow-1 flex-md-grow-0">
                  <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-search text-muted fs-5"></i>
                  </span>
                  <input type="text" class="form-control border-start-0 ps-0" placeholder="Search employees..."
                    id="searchEmployees">
                </div>
                <select class="form-select flex-grow-0" style="width: 180px;" id="roleFilter">
                  <option value="">All Roles</option>
                  <?php foreach ($role as $value) { ?>
                    <option value="<?= htmlspecialchars($value->name) ?>"><?= htmlspecialchars($value->name) ?></option>
                  <?php } ?>
                </select>
                <!-- <button type="button" onclick="refreshEmployees()"
                  class="btn btn-primary d-flex align-items-center justify-content-center gap-1"
                  style="background: #c6d2ff!important;">
                  <i class="bi bi-arrow-clockwise text-black fs-5"></i>
                  <span class="d-none d-md-inline text-black">Refresh</span>
                </button> -->
              </div>
              <button type="button" class="btn btn-black bg-black d-flex align-items-center gap-2 add text-white"
                data-bs-toggle="offcanvas" data-bs-target="#form-offcanvas" data-crud-id="0" title="Create Employee">
                <i class="bi bi-plus-lg fs-5"></i>
                <span>Employee</span>
              </button>
=======
    <?php
      $totalEmployees = count($users ?? []);
      $activeEmployees = count(array_filter($users ?? [], fn($u) => $u->status == '1'));
      $inactiveEmployees = count(array_filter($users ?? [], fn($u) => $u->status == '0'));
      $totalRoles = count($role ?? []);
    ?>

    <!-- SaaS Hero Banner Section -->
    <div class="nl-hero-banner">
      <div class="row align-items-center g-4">
        <div class="col-lg-7">
          <div class="nl-live-pill">
            <span class="dot"></span> TEAM DIRECTORY
          </div>
          <h1 class="nl-hero-title">Employee Management</h1>
          <p class="nl-hero-desc">
            Manage staff members, role designations, account access statuses, and module permission levels across the organization.
          </p>
        </div>
        <div class="col-lg-5 text-lg-end">
          <button type="button" class="nl-btn-create add"
            data-bs-toggle="offcanvas" data-bs-target="#form-offcanvas" data-crud-id="0" title="Create Employee">
            <i class="bi bi-person-plus-fill fs-5"></i>
            <span>+ Add Employee</span>
          </button>
        </div>
      </div>

      <!-- Live KPI Stat Cards Grid -->
      <div class="row g-3 mt-3 pt-2">
        <div class="col-6 col-md-3">
          <div class="nl-stat-card">
            <div class="nl-stat-icon" style="background: rgba(99, 102, 241, 0.2); color: #a5b4fc;">
              <i class="bi bi-people-fill"></i>
            </div>
            <div>
              <div class="nl-stat-label">Total Staff</div>
              <div class="nl-stat-value"><?= $totalEmployees ?></div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="nl-stat-card">
            <div class="nl-stat-icon" style="background: rgba(16, 185, 129, 0.2); color: #6ee7b7;">
              <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
              <div class="nl-stat-label">Active Staff</div>
              <div class="nl-stat-value"><?= $activeEmployees ?></div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="nl-stat-card">
            <div class="nl-stat-icon" style="background: rgba(245, 158, 11, 0.2); color: #fcd34d;">
              <i class="bi bi-person-dash-fill"></i>
            </div>
            <div>
              <div class="nl-stat-label">Inactive Staff</div>
              <div class="nl-stat-value"><?= $inactiveEmployees ?></div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="nl-stat-card">
            <div class="nl-stat-icon" style="background: rgba(14, 165, 233, 0.2); color: #7dd3fc;">
              <i class="bi bi-shield-check"></i>
            </div>
            <div>
              <div class="nl-stat-label">Total Roles</div>
              <div class="nl-stat-value"><?= $totalRoles ?></div>
>>>>>>> origin/main
            </div>
          </div>
        </div>
      </div>
    </div>

<<<<<<< HEAD
    <!-- Search and Actions -->


    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
      <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div
                class="rounded-circle bg-primary bg-opacity-10 text-white d-flex align-items-center justify-content-center"
                style="width: 56px; height: 56px;">
                <i class="bi bi-people fs-3"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Total Employees</p>
                <h4 class="mb-0"><?= count($users) ?></h4>
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
                style="width: 56px; height: 56px;">
                <i class="bi bi-check-circle fs-3"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Active</p>
                <h4 class="mb-0"><?= count(array_filter($users, fn($u) => $u->status == '1')) ?></h4>
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
                style="width: 56px; height: 56px;">
                <i class="bi bi-person-x fs-3"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Inactive</p>
                <h4 class="mb-0"><?= count(array_filter($users, fn($u) => $u->status == '0')) ?></h4>
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
                style="width: 56px; height: 56px;">
                <i class="bi bi-shield-check fs-3"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Roles</p>
                <h4 class="mb-0"><?= count($role) ?></h4>
              </div>
            </div>
          </div>
=======
    <!-- Filter Toolbar -->
    <div class="nl-filter-card">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3">
        <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-3 flex-grow-1">
          <!-- Search Input -->
          <div class="nl-search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" class="nl-search-input" placeholder="Search by employee name, email, or ID..." id="searchEmployees">
          </div>

          <!-- Role Filter -->
          <select class="nl-select" id="roleFilter" style="min-width: 170px;">
            <option value="">All Roles (<?= $totalRoles ?>)</option>
            <?php if (!empty($role)): ?>
              <?php foreach ($role as $value): ?>
                <option value="<?= htmlspecialchars(strtolower($value->name)) ?>">
                  <?= htmlspecialchars($value->name) ?>
                </option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>

          <!-- Status Filter -->
          <select class="nl-select" id="statusFilter" style="min-width: 150px;">
            <option value="">All Statuses</option>
            <option value="active">Active (<?= $activeEmployees ?>)</option>
            <option value="inactive">Inactive (<?= $inactiveEmployees ?>)</option>
          </select>

          <!-- Reset Filter -->
          <button type="button" class="nl-btn-clear" onclick="resetEmployeeFilters()" title="Reset Filters">
            <i class="bi bi-arrow-counterclockwise"></i> Reset
          </button>
        </div>

        <div class="d-flex align-items-center justify-content-end text-muted small fw-semibold" id="tableFilterSummary">
          Showing <span class="text-dark fw-bold px-1" id="visibleRowCount"><?= $totalEmployees ?></span> of <?= $totalEmployees ?> employees
>>>>>>> origin/main
        </div>
      </div>
    </div>

<<<<<<< HEAD
    <!-- Employees Table -->
    <div class="card border-0 shadow-sm overflow-hidden mb-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0" id="employeesTable">
          <thead class="" style="background-color: #c6d2ff!important;">
            <tr>
              <th width="60" class="pe-4 py-3 text-black fs-6 fw-semibold border-bottom text-center"
                style="background: #c6d2ff!important;">
                S.No.
              </th>
              <th class="pe-4 py-3 text-black fs-6 fw-semibold border-bottom" style="background: #c6d2ff!important;">
                Employee Details</th>
              <th width="150" class="py-3 text-black fs-6 fw-semibold border-bottom text-center"
                style="background: #c6d2ff!important;">Designation</th>
              <th width="180" class="py-3 text-black fs-6 fw-semibold border-bottom text-center d-none d-lg-table-cell"
                style="background: #c6d2ff!important;">Permissions</th>
              <th width="120" class="py-3 text-black fs-6 fw-semibold border-bottom text-center"
                style="background: #c6d2ff!important;">Status</th>
              <th width="120" class="py-3 text-black fs-6 fw-semibold border-bottom text-center"
                style="background: #c6d2ff!important;">Actions</th>
            </tr>
          </thead>
          <tbody class="border-top-0">
            <?php
            $i = 1;
            foreach ($users as $employee):
              $initial = strtoupper(substr($employee->name ?? '?', 0, 1));
              $statusClass = $employee->status == '1' ? 'success' : 'danger';
              $statusText = $employee->status == '1' ? 'Active' : 'Inactive';
              ?>
              <tr>
                <td class="ps-4 py-2 fw-medium text-center"><?= $i ?></td>
                <td class="py-2">
                  <div class="d-flex align-items-center gap-3">

                    <div class="min-w-0 flex-grow-1">
                      <div class="fw-bold text-dark text-truncate ">
                        <?= htmlspecialchars($employee->name ?? '--') ?>
                      </div>
                      <div class="d-flex align-items-center text-muted small mt-1">
                        <i class="bi bi-envelope me-1 flex-shrink-0 fs-6"></i>
                        <span class="text-truncate"><?= htmlspecialchars($employee->email ?? '--') ?></span>
                      </div>
                      <div class="text-muted small font-monospace mt-1">
                        <i class="bi bi-person-badge me-1"></i>
                        <span>ID: <?= htmlspecialchars($employee->id ?? '--') ?></span>
=======
    <!-- Employees Modern Table Card -->
    <div class="nl-table-card">
      <div class="table-responsive">
        <table class="nl-table" id="employeesTable">
          <thead>
            <tr>
              <th width="70" class="text-center">S.No.</th>
              <th>Employee Details</th>
              <th width="170" class="text-center">Designation</th>
              <th width="160" class="text-center">Permissions</th>
              <th width="130" class="text-center">Status</th>
              <th width="120" class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i = 1;
            if (!empty($users)):
              foreach ($users as $employee):
                $initial = strtoupper(substr($employee->name ?? '?', 0, 1));
                $isActive = ($employee->status == '1');
            ?>
              <tr data-status="<?= $isActive ? 'active' : 'inactive' ?>" data-role="<?= htmlspecialchars(strtolower($employee->role_name ?? '')) ?>">
                <td class="text-center fw-semibold text-muted"><?= $i ?></td>
                <td>
                  <div class="d-flex align-items-center gap-3">
                    <div class="nl-user-avatar">
                      <?= $initial ?>
                    </div>
                    <div class="min-w-0">
                      <div class="fw-bold text-dark text-truncate" style="font-size: 0.95rem;">
                        <?= htmlspecialchars($employee->name ?? '--') ?>
                      </div>
                      <div class="d-flex align-items-center text-muted small mt-1 gap-2">
                        <span class="text-truncate">
                          <i class="bi bi-envelope me-1 text-primary"></i><?= htmlspecialchars($employee->email ?? '--') ?>
                        </span>
                        <span class="badge bg-light text-secondary border px-2 py-0" style="font-size: 0.72rem;">
                          ID: <?= htmlspecialchars($employee->id ?? '--') ?>
                        </span>
>>>>>>> origin/main
                      </div>
                    </div>
                  </div>
                </td>
<<<<<<< HEAD
                <td class="py-2 text-center">
                  <span class="badge bg-info bg-opacity-10 text-white border border-info border-opacity-25 " style="background: rgba(34, 197, 94, 0.1) !important; color: #16a34a !important;
        border: 1px solid rgba(34, 197, 94, 0.2) !important; font-weight: 600; font-size: 14px; border-radius: 50px;">
                    <?= htmlspecialchars($employee->role_name ?? '--') ?>
                  </span>
                </td>
                <td class="py-2 text-center d-none d-lg-table-cell">
                  <div class="d-flex justify-content-center gap-2">
                    <a href="#" class="btn btn-sm btn-outline-primary view-permissions" data-id="<?= $employee->id; ?>"
                      data-bs-toggle="tooltip" title="View Permissions">
                      <i class="bi bi-eye fs-6"></i>
                    </a>
                    <a href="#" class="btn btn-sm btn-outline-warning edit-permissions" data-id="<?= $employee->id; ?>"
                      data-name="<?= htmlspecialchars($employee->name); ?>" data-role-id="<?= $employee->role_id; ?>"
                      data-bs-toggle="tooltip" title="Edit Permissions">
                      <i class="bi bi-pencil fs-6"></i>
                    </a>
                  </div>
                </td>
                <td class="py-2 text-center">
                  <div class="form-check form-switch d-inline-block">
                    <input type="checkbox" data-token="" data-url="<?= base_url() ?>updateStatus" data-module="employees"
                      class="switch-status-change form-check-input status" id="datatable-row-<?= $employee->id; ?>"
                      name="status" data-id="<?= $employee->id; ?>" value="<?= $employee->status; ?>"
                      <?= $employee->status == '1' ? 'checked' : ''; ?>>
                    <label class="form-check-label d-none" for="datatable-row-<?= $employee->id; ?>"></label>
                  </div>
                </td>
                <td class="py-2 text-center">
                  <div class="d-flex gap-2 align-items-center justify-content-center">
                    <button type="button" class="btn btn-sm btn-outline-primary border-0 edit-employee"
                      data-bs-toggle="tooltip" title="Edit Employee" data-id="<?= $employee->id; ?>"
                      data-name="<?= htmlspecialchars($employee->name); ?>"
                      data-email="<?= htmlspecialchars($employee->email); ?>" data-role-id="<?= $employee->role_id; ?>"
                      data-status="<?= $employee->status; ?>">
                      <i class="bi bi-pencil fs-6"></i>
                    </button>
                    <a href="<?= base_url() ?>deleteEmployee" id="delete-employees-<?= $employee->id; ?>"
                      class="fs-4 text-danger" data-type="ajax" data-method="DELETE"
                      data-token="WY9UR433BdtOHpyqGYdQZivZoEc7vdNGeFkSyjI5" data-bs-toggle="tooltip"
                      data-confirm="Are you certain you want to delete it?" aria-label="Delete"
                      data-bs-original-title="Delete" data-id="<?= $employee->id; ?>">
                      <i class="bi bi-trash fs-6"></i>
=======
                <td class="text-center">
                  <span class="nl-role-pill">
                    <i class="bi bi-shield-check me-1"></i><?= htmlspecialchars($employee->role_name ?? 'Staff') ?>
                  </span>
                </td>
                <td class="text-center">
                  <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="action-btn action-btn-view view-permissions" data-id="<?= $employee->id; ?>"
                      data-bs-toggle="tooltip" title="View Permissions">
                      <i class="bi bi-eye"></i>
                    </button>
                    <button type="button" class="action-btn action-btn-perm edit-permissions" data-id="<?= $employee->id; ?>"
                      data-name="<?= htmlspecialchars($employee->name ?? ''); ?>" data-role-id="<?= $employee->role_id ?? ''; ?>"
                      data-role-slug="<?= htmlspecialchars($employee->role_slug ?? ''); ?>"
                      data-bs-toggle="tooltip" title="Edit Permissions">
                      <i class="bi bi-pencil-square"></i>
                    </button>
                  </div>
                </td>
                <td class="text-center">
                  <div class="form-check form-switch d-inline-block m-0">
                    <input type="checkbox" data-url="<?= base_url() ?>updateStatus" data-module="employees"
                      class="switch-status-change form-check-input status" id="datatable-row-<?= $employee->id; ?>"
                      name="status" data-id="<?= $employee->id; ?>" value="<?= $employee->status; ?>"
                      <?= $isActive ? 'checked' : ''; ?>>
                    <label class="form-check-label d-none" for="datatable-row-<?= $employee->id; ?>"></label>
                  </div>
                </td>
                <td class="text-center">
                  <div class="d-flex gap-2 align-items-center justify-content-center">
                    <button type="button" class="action-btn action-btn-edit edit-employee"
                      data-bs-toggle="tooltip" title="Edit Employee" data-id="<?= $employee->id; ?>"
                      data-name="<?= htmlspecialchars($employee->name ?? ''); ?>"
                      data-email="<?= htmlspecialchars($employee->email ?? ''); ?>" data-role-id="<?= $employee->role_id ?? ''; ?>"
                      data-role-slug="<?= htmlspecialchars($employee->role_slug ?? ''); ?>"
                      data-status="<?= $employee->status; ?>">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <a href="<?= base_url() ?>deleteEmployee" id="delete-employees-<?= $employee->id; ?>"
                      class="action-btn action-btn-delete" data-type="ajax" data-method="DELETE"
                      data-token="<?= csrf_hash() ?>" data-bs-toggle="tooltip"
                      data-confirm="Are you certain you want to delete this employee?" aria-label="Delete"
                      data-bs-original-title="Delete" data-id="<?= $employee->id; ?>">
                      <i class="bi bi-trash"></i>
>>>>>>> origin/main
                    </a>
                  </div>
                </td>
              </tr>
<<<<<<< HEAD
              <?php
              $i++;
            endforeach;
            if (empty($users)): ?>
              <tr>
                <td colspan="6" class="text-center py-5 text-muted">
                  <i class="bi bi-people fs-2 d-block mb-3 opacity-25"></i>
                  No employees found
=======
            <?php
                $i++;
              endforeach;
            else:
            ?>
              <tr id="emptyEmployeesRow">
                <td colspan="6" class="text-center py-5 text-muted">
                  <i class="bi bi-people fs-1 d-block mb-3 opacity-25"></i>
                  <h6 class="text-muted fw-bold">No employees found</h6>
                  <p class="small text-muted mb-0">Get started by creating a new employee above.</p>
>>>>>>> origin/main
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
<<<<<<< HEAD

    <!-- Pagination -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center px-2 gap-3">
      <div class="text-muted small">
        Showing <?= count($users) ?> of <?= count($users) ?> entries
      </div>
      <div class="btn-group mt-0 pt-2">
        <button class="btn btn-outline-primary btn-sm">
          <i class="bi bi-chevron-left"></i> Previous
        </button>
        <button class="btn btn-primary btn-sm">1</button>
        <button class="btn btn-outline-primary btn-sm">
          Next <i class="bi bi-chevron-right"></i>
        </button>
      </div>
    </div>
  </div>

  <!-- Add/Edit Employee Form -->
  <form action="<?= base_url() ?>addEmployee" method="POST" id="updateUser">
    <input type="hidden" name="id" id="id">
    <input type="hidden" name="module" value="employees" id="module">
    <div class="offcanvas offcanvas-end" tabindex="-1" id="form-offcanvas" aria-labelledby="form-offcanvasLabel">
      <div class="offcanvas-header bg-gradient-primary text-white border-0">
        <div class="d-flex align-items-center justify-content-between w-100">
          <div>
            <h5 class="offcanvas-title text-white fw-bold" id="form-offcanvasLabel">
              Add New Employee
            </h5>
            <p class="mb-0 small opacity-75">Add a new employee to the system</p>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
      </div>
      <div class="offcanvas-body p-4">
        <div class="row g-4">
          <!-- Name -->
          <div class="col-md-12">
            <label for="name" class="form-label fw-bold">
              Name <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0">
                <i class="fas fa-user text-muted"></i>
              </span>
              <input type="text" class="form-control border-start-0" name="name" id="name" placeholder="Enter full name"
                required>
            </div>
          </div>

          <!-- Email -->
          <div class="col-md-12">
            <label for="email" class="form-label fw-bold">
              Email <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0">
                <i class="fas fa-envelope text-muted"></i>
              </span>
              <input type="email" class="form-control border-start-0" name="email" id="email"
                placeholder="Enter email address" required>
            </div>
          </div>

          <!-- Role -->
          <div class="col-md-12">
            <label for="user_type" class="form-label fw-bold">
              Select Role <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0">
                <i class="fas fa-user-tag text-muted"></i>
              </span>
              <select class="form-select border-start-0" name="role_id" id="user_type" required>
                <option value="" selected disabled>Select Role</option>
                <?php foreach ($role as $value) { ?>
                  <option value="<?= $value->id ?>"><?= htmlspecialchars($value->name) ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <!-- Status -->
          <div class="col-md-12">
            <label class="form-label fw-bold d-block mb-2">Status</label>
            <div class="form-check form-switch gap-3 align-items-center d-flex">
              <input class="form-check-input" name="status" type="checkbox" id="category-status" value="1" checked
                style="width: 3.5em; height: 1.8em;">
              <label class="form-check-label fw-medium" for="category-status">Active</label>
=======
  </div>

  <!-- Add/Edit Employee Offcanvas Modal -->
  <form action="<?= base_url() ?>addEmployee" method="POST" id="updateUser">
    <input type="hidden" name="id" id="id">
    <input type="hidden" name="module" value="employees" id="module">
    <div class="offcanvas offcanvas-end" tabindex="-1" id="form-offcanvas" aria-labelledby="form-offcanvasLabel" style="width: 480px; max-width: 92vw;">
      <div class="offcanvas-header" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.08);">
        <div>
          <h5 class="offcanvas-title text-white fw-bold d-flex align-items-center gap-2 mb-1" id="form-offcanvasLabel" style="font-size: 1.05rem;">
            <i class="bi bi-person-plus text-info"></i> Add New Employee
          </h5>
          <p class="text-white-50 mb-0 small" style="font-size: 0.78rem;">Create or update staff account and designation</p>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body p-4" style="background: #f8fafc;">
        <div class="card border-0 shadow-sm mb-0">
          <div class="card-body p-4">
            <div class="row g-3">
              <!-- Name -->
              <div class="col-md-12">
                <label for="name" class="form-label fw-semibold text-dark">
                  Full Name <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0">
                    <i class="fas fa-user text-muted"></i>
                  </span>
                  <input type="text" class="form-control border-start-0" name="name" id="name" placeholder="e.g. John Doe" required>
                </div>
              </div>

              <!-- Email -->
              <div class="col-md-12">
                <label for="email" class="form-label fw-semibold text-dark">
                  Email Address <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0">
                    <i class="fas fa-envelope text-muted"></i>
                  </span>
                  <input type="email" class="form-control border-start-0" name="email" id="email" placeholder="e.g. john@petsfolio.com" required>
                </div>
              </div>

              <!-- Role -->
              <div class="col-md-12">
                <label for="user_type" class="form-label fw-semibold text-dark">
                  Select Role / Designation <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0">
                    <i class="fas fa-user-tag text-muted"></i>
                  </span>
                  <select class="form-select border-start-0" name="role_id" id="user_type" required>
                    <option value="" selected disabled>Choose Role...</option>
                    <?php if (!empty($role)): ?>
                      <?php foreach ($role as $value): ?>
                        <option value="<?= $value->id ?>"><?= htmlspecialchars($value->name) ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>
              </div>

              <!-- Status -->
              <div class="col-md-12 pt-2">
                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3 border">
                  <div>
                    <label class="form-label fw-semibold text-dark mb-0">Account Active</label>
                    <p class="text-muted small mb-0" style="font-size: 0.78rem;">Allow employee login and portal operations</p>
                  </div>
                  <div class="form-check form-switch m-0">
                    <input class="form-check-input" name="status" type="checkbox" id="category-status" value="1" checked role="switch">
                  </div>
                </div>
              </div>
>>>>>>> origin/main
            </div>
          </div>
        </div>
      </div>
<<<<<<< HEAD
      <div class="offcanvas-footer border-top p-4">
        <div class="d-flex gap-3">
          <button class="btn bg-primary d-flex align-items-center gap-2 flex-grow-1 justify-content-center text-white"
            type="submit">
            <i class="bi bi-check-circle"></i> Save Employee
          </button>
          <button class="btn btn-outline-secondary d-flex align-items-center gap-2" type="button"
            data-bs-dismiss="offcanvas">
            <i class="bi bi-x-circle"></i> Cancel
=======
      <div class="offcanvas-footer border-top p-3 bg-white">
        <div class="d-flex gap-2">
          <button class="btn btn-light flex-fill rounded-3 py-2 fw-semibold" type="button" data-bs-dismiss="offcanvas">
            <i class="bi bi-x-circle me-1"></i> Cancel
          </button>
          <button class="btn btn-primary flex-fill rounded-3 py-2 fw-semibold" type="submit" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); border: none;">
            <i class="bi bi-check-circle me-1"></i> Save Employee
>>>>>>> origin/main
          </button>
        </div>
      </div>
    </div>
  </form>

  <!-- View Permissions Modal -->
<<<<<<< HEAD
  <div class="modal fade" id="permissionsModal" tabindex="-1" aria-labelledby="permissionsModalLabel" aria-hidden="true"
    style="overflow: hidden;">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content border-0 shadow p-0" style="height: 80vh;">
        <div class="modal-header" style="background: #c6d2ff!important; border-bottom: 2px solid #a3b4ff;">
          <h5 class="modal-title text-black fw-bold fs-4" id="permissionsModalLabel">User Permissions</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0" style="overflow-y: auto; flex: 1;">
          <div class="p-4">
            <ul id="permissionsList" class="list-group list-group-flush">
=======
  <div class="modal fade" id="permissionsModal" tabindex="-1" aria-labelledby="permissionsModalLabel" aria-hidden="true" style="overflow: hidden;">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content border-0 shadow" style="height: 80vh; border-radius: 16px; overflow: hidden;">
        <div class="modal-header" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.08);">
          <div>
            <h5 class="modal-title text-white fw-bold mb-1" id="permissionsModalLabel">
              <i class="bi bi-shield-check text-primary me-2"></i> User Permissions
            </h5>
            <p class="text-white-50 mb-0 small" style="font-size: 0.78rem;">Active access permissions granted to this user</p>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0" style="overflow-y: auto; flex: 1; background: #f8fafc;">
          <div class="p-4">
            <ul id="permissionsList" class="list-group list-group-flush shadow-sm rounded-3 overflow-hidden border">
>>>>>>> origin/main
              <!-- Permissions will be loaded here dynamically -->
            </ul>
          </div>
        </div>
<<<<<<< HEAD
        <div class="modal-footer border-top">
          <button type="button" class="btn btn-secondary px-4 py-2 fs-6" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i>Close
=======
        <div class="modal-footer border-top bg-white p-3">
          <button type="button" class="btn btn-secondary px-4 py-2 rounded-3" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Close
>>>>>>> origin/main
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Permissions Modal -->
<<<<<<< HEAD
  <div class="modal fade" id="editPermissionsModal" tabindex="-1" aria-labelledby="editPermissionsModalLabel"
    aria-hidden="true" style="overflow: hidden;">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
      <div class="modal-content border-0 shadow p-0" style="height: 90vh;">
        <div class="modal-header" style="background: #c6d2ff!important; border-bottom: 2px solid #a3b4ff;">
          <h5 class="modal-title mb-0 text-black fw-bold fs-4" id="editPermissionsModalLabel">Edit User Permissions</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0" style="overflow-y: auto; flex: 1;">
=======
  <div class="modal fade" id="editPermissionsModal" tabindex="-1" aria-labelledby="editPermissionsModalLabel" aria-hidden="true" style="overflow: hidden;">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
      <div class="modal-content border-0 shadow" style="height: 90vh; border-radius: 16px; overflow: hidden;">
        <div class="modal-header" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.08);">
          <div>
            <h5 class="modal-title text-white fw-bold mb-1" id="editPermissionsModalLabel">
              <i class="bi bi-shield-lock text-warning me-2"></i> Edit User Permissions
            </h5>
            <p class="text-white-50 mb-0 small" style="font-size: 0.78rem;">Configure granular capability access per module</p>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0" style="overflow-y: auto; flex: 1; background: #f8fafc;">
>>>>>>> origin/main
          <div class="p-4">
            <form id="permissionsForm">
              <input type="hidden" name="user_id" id="permission_user_id">
              <input type="hidden" name="role_id" id="permission_role_id">

              <div class="permissions-container">
<<<<<<< HEAD
                <div class="select-all-section mb-3 p-2 rounded shadow-sm">
                  <div class="form-check">
                    <input class="form-check-input select-all-checkbox" type="checkbox" id="selectAllPermissions"
                      style="width: 1.2em; height: 1.2em;">
                    <label class="form-check-label fw-semibold fs-6" for="selectAllPermissions">
                      Select All Permissions
=======
                <div class="select-all-section mb-3">
                  <div class="form-check d-flex align-items-center gap-2 m-0">
                    <input class="form-check-input select-all-checkbox" type="checkbox" id="selectAllPermissions" style="width: 1.2em; height: 1.2em; cursor: pointer;">
                    <label class="form-check-label fw-bold text-dark" for="selectAllPermissions" style="cursor: pointer;">
                      Select All Permissions Across Modules
>>>>>>> origin/main
                    </label>
                  </div>
                </div>
                <div id="permissionsContainer">
<<<<<<< HEAD
                  <!-- Permissions checkboxes will be loaded here dynamically -->
=======
                  <!-- Permissions checkboxes dynamically generated -->
>>>>>>> origin/main
                </div>
              </div>
            </form>
          </div>
        </div>
<<<<<<< HEAD
        <div class="modal-footer border-top">
          <button type="button" class="btn btn-secondary px-4 py-2 fs-6" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i>Cancel
          </button>
          <button type="button" class="btn text-white bg-primary px-4 py-2 fs-6" id="savePermissions">
            <i class="bi bi-check-circle me-1"></i>Save Changes
=======
        <div class="modal-footer border-top bg-white p-3">
          <button type="button" class="btn btn-light px-4 py-2 rounded-3 fw-semibold" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Cancel
          </button>
          <button type="button" class="btn btn-primary px-4 py-2 rounded-3 fw-semibold" id="savePermissions" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); border: none;">
            <i class="bi bi-check-circle me-1"></i> Save Changes
>>>>>>> origin/main
          </button>
        </div>
      </div>
    </div>
  </div>

  <?= $this->include('admin/footer') ?>
</main>

<<<<<<< HEAD
<style>
  /* Fixed body when modal is open */
  body.modal-open {
    overflow: hidden !important;
    padding-right: 0 !important;
  }

  .bg-gradient-primary {
    background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
  }

  /* Modal backdrop */
  .modal-backdrop {
    background-color: rgba(0, 0, 0, 0.7) !important;
  }

  .permissions-container {
    max-height: 100%;
  }

  .module-section {
    border: 1px solid #e9ecef;
    border-radius: 12px;
    margin-bottom: 20px;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    overflow: hidden;
  }

  .module-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 16px 20px;
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
  }

  .permissions-grid {
    padding: 20px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 15px;
  }

  .permission-item {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    transition: all 0.3s ease;
  }


  .permission-checkbox {
    margin-right: 12px;
    width: 1.2em !important;
    height: 1.2em !important;
    cursor: pointer;
  }

  .permission-label {
    font-size: 15px;
    color: #495057;
    margin: 0;
    cursor: pointer;
    user-select: none;
    font-weight: 500;
  }

  .select-all-section {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border: 2px solid #90caf9;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 20px;
  }

  .select-all-checkbox {
    margin-right: 12px;
  }

  /* Custom scrollbar for modal body */
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

  /* Status switch styling */
  .form-switch .form-check-input {
    width: 3.5em;
    height: 1.8em;
  }

  .form-switch .form-check-input:checked {
    background-color: #198754;
    border-color: #198754;
  }

  /* Badge styling */
  .badge {
    padding: 0.6em 1em;
    font-weight: 600;
    font-size: 0.9rem;
  }

  /* Tooltip styling */
  [data-bs-toggle="tooltip"] {
    cursor: pointer;
  }

  /* Button hover effects */
  .btn-outline-primary:hover,
  .btn-outline-danger:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
  }

  /* Modal animation */
  .modal.fade .modal-dialog {
    transform: scale(0.95);
    transition: transform 0.3s ease-out;
  }

  .modal.show .modal-dialog {
    transform: scale(1);
  }

  /* Table row hover */
  .table tbody tr:hover {
    background-color: rgba(99, 102, 241, 0.05) !important;
  }

  /* Icon sizing */
  .bi {
    vertical-align: middle;
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .modal-dialog {
      margin: 20px;
      width: calc(100% - 40px);
    }

    .permissions-grid {
      grid-template-columns: 1fr;
    }

    .module-section {
      margin-bottom: 15px;
    }

    .permission-item {
      padding: 10px 14px;
    }
  }

  @media (max-width: 576px) {
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
  }
</style>

<script>
  $(document).ready(function () {
    // Prevent body scroll when modal is open
    $(document).on('show.bs.modal', '.modal', function () {
      $('body').addClass('modal-open');
    });

    $(document).on('hide.bs.modal', '.modal', function () {
      $('body').removeClass('modal-open');
    });

    // Status Toggle Change
    $(document).on('change', '.switch-status-change', function () {

=======
<script>
  $(document).ready(function() {
    // Prevent body scroll when modal is open
    $(document).on('show.bs.modal', '.modal', function() {
      $('body').addClass('modal-open');
    });

    $(document).on('hide.bs.modal', '.modal', function() {
      $('body').removeClass('modal-open');
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Combined Filter Function
    function applyEmployeeFilters() {
      const searchTerm = ($('#searchEmployees').val() || '').toLowerCase().trim();
      const selectedRole = ($('#roleFilter').val() || '').toLowerCase().trim();
      const selectedStatus = ($('#statusFilter').val() || '').toLowerCase().trim();

      let visibleCount = 0;
      const rows = document.querySelectorAll('#employeesTable tbody tr:not(#emptyEmployeesRow)');

      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const rowRole = (row.getAttribute('data-role') || '').toLowerCase();
        const rowStatus = (row.getAttribute('data-status') || '').toLowerCase();

        const matchesSearch = !searchTerm || text.includes(searchTerm);
        const matchesRole = !selectedRole || rowRole === selectedRole;
        const matchesStatus = !selectedStatus || rowStatus === selectedStatus;

        if (matchesSearch && matchesRole && matchesStatus) {
          row.style.display = '';
          visibleCount++;
        } else {
          row.style.display = 'none';
        }
      });

      const countDisplay = document.getElementById('visibleRowCount');
      if (countDisplay) {
        countDisplay.textContent = visibleCount;
      }
    }

    // Filter event listeners
    $('#searchEmployees').on('input', applyEmployeeFilters);
    $('#roleFilter').on('change', applyEmployeeFilters);
    $('#statusFilter').on('change', applyEmployeeFilters);

    // Reset filters
    window.resetEmployeeFilters = function() {
      $('#searchEmployees').val('');
      $('#roleFilter').val('');
      $('#statusFilter').val('');
      applyEmployeeFilters();
    };

    // Status Toggle Change
    $(document).on('change', '.switch-status-change', function() {
>>>>>>> origin/main
      var checkbox = $(this);
      var userId = checkbox.data('id');
      var url = checkbox.data('url');
      var module = checkbox.data('module');
      var status = checkbox.is(':checked') ? 1 : 0;

      $.ajax({
        url: url,
        type: "POST",
        data: {
          id: userId,
          status: status,
          module: module
        },
        dataType: "json",
<<<<<<< HEAD
        success: function (response) {

          if (response.success || response.status === true) {

=======
        success: function(response) {
          if (response.success || response.status === true) {
>>>>>>> origin/main
            Swal.fire({
              icon: 'success',
              title: 'Updated!',
              text: response.message || 'Status updated successfully',
<<<<<<< HEAD
              timer: 1500,
              showConfirmButton: false
            })
            setTimeout(() => location.reload(), 1000)

          } else {

=======
              timer: 1200,
              showConfirmButton: false
            });
            checkbox.closest('tr').attr('data-status', status == 1 ? 'active' : 'inactive');
          } else {
>>>>>>> origin/main
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: response.message || 'Failed to update status'
            });
<<<<<<< HEAD

            checkbox.prop('checked', !checkbox.is(':checked'));
          }
        },
        error: function () {

          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Something went wrong'
          });

          checkbox.prop('checked', !checkbox.is(':checked'));
        }
      });

    });
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Search functionality
    document.getElementById('searchEmployees').addEventListener('input', function (e) {
      const searchTerm = e.target.value.toLowerCase();
      const rows = document.querySelectorAll('#employeesTable tbody tr');

      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
      });
    });

    // Role filter functionality
    document.getElementById('roleFilter').addEventListener('change', function (e) {
      const selectedRole = e.target.value.toLowerCase();
      const rows = document.querySelectorAll('#employeesTable tbody tr');

      rows.forEach(row => {
        const roleCell = row.querySelector('td:nth-child(3)'); // Role column
        if (roleCell) {
          const roleText = roleCell.textContent.toLowerCase();
          if (selectedRole === '' || roleText.includes(selectedRole)) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        }
      });
    });

    // Refresh Data
    function refreshEmployees() {
      console.log('Refreshing employees data...');
      location.reload();
    }

    // View Permissions
    $(".view-permissions").click(function (e) {
      e.preventDefault();
      var userId = $(this).data("id");
      var userName = $(this).closest('tr').find('td:nth-child(2) .fw-bold').text();
=======
            checkbox.prop('checked', !checkbox.is(':checked'));
          }
        },
        error: function() {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Something went wrong while updating status'
          });
          checkbox.prop('checked', !checkbox.is(':checked'));
        }
      });
    });

    // View Permissions
    $(".view-permissions").click(function(e) {
      e.preventDefault();
      var userId = $(this).data("id");
      var userName = $(this).closest('tr').find('td:nth-child(2) .fw-bold').text().trim();
>>>>>>> origin/main

      $.ajax({
        url: "<?= base_url('permission/getPermissions') ?>",
        type: "POST",
        data: {
          user_id: userId
        },
        dataType: "json",
<<<<<<< HEAD
        success: function (response) {
          var permissionsList = $("#permissionsList");
          permissionsList.empty();

          $("#permissionsModalLabel").text("Permissions - " + userName);

          if (response.length > 0) {
            response.forEach(function (permission) {
              permissionsList.append(
                '<li class="list-group-item d-flex justify-content-between align-items-center py-2 px-4 border-0">' +
                '<div class="d-flex align-items-center gap-3">' +
                '<i class="bi bi-shield-check text-primary fs-5"></i>' +
                '<div>' +
                '<div class="fw-semibold ">' + permission.module + '</div>' +
                '</div>' +
                '</div>' +
                '<span class="badge bg-primary bg-opacity-10 text-white ">' + permission.name + '</span>' +
=======
        success: function(response) {
          var permissionsList = $("#permissionsList");
          permissionsList.empty();

          $("#permissionsModalLabel").html('<i class="bi bi-shield-check text-primary me-2"></i> Permissions: ' + userName);

          if (response && response.length > 0) {
            response.forEach(function(permission) {
              permissionsList.append(
                '<li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3 border-bottom">' +
                '<div class="d-flex align-items-center gap-2">' +
                '<i class="bi bi-check-circle-fill text-success fs-6"></i>' +
                '<span class="fw-semibold text-dark">' + permission.module + '</span>' +
                '</div>' +
                '<span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1" style="font-weight: 600; font-size: 0.78rem;">' + permission.name + '</span>' +
>>>>>>> origin/main
                '</li>'
              );
            });
          } else {
            permissionsList.append(
              '<li class="list-group-item text-center py-5 text-muted border-0">' +
<<<<<<< HEAD
              '<i class="bi bi-shield-slash fs-1 d-block mb-3 opacity-25"></i>' +
              '<div class="fs-5">No permissions assigned</div>' +
=======
              '<i class="bi bi-shield-slash fs-1 d-block mb-2 opacity-25"></i>' +
              '<div class="fw-semibold">No permissions assigned to this user</div>' +
>>>>>>> origin/main
              '</li>'
            );
          }

          $("#permissionsModal").modal("show");
        },
<<<<<<< HEAD
        error: function () {
          alert("Error fetching permissions.");
=======
        error: function() {
          Swal.fire('Error', 'Error fetching employee permissions.', 'error');
>>>>>>> origin/main
        }
      });
    });

    // Edit Permissions
<<<<<<< HEAD
    $(".edit-permissions").click(function (e) {
=======
    $(".edit-permissions").click(function(e) {
>>>>>>> origin/main
      e.preventDefault();
      var userId = $(this).data("id");
      var userName = $(this).data("name");
      var roleId = $(this).data("role-id");

<<<<<<< HEAD
      $("#editPermissionsModalLabel").text("Edit Permissions - " + userName);
=======
      $("#editPermissionsModalLabel").html('<i class="bi bi-shield-lock text-warning me-2"></i> Edit Permissions: ' + userName);
>>>>>>> origin/main
      $("#permission_user_id").val(userId);
      $("#permission_role_id").val(roleId);

      // Load available permissions
      $.ajax({
        url: "<?= base_url('permission/getAllPermissions') ?>",
        type: "GET",
        dataType: "json",
<<<<<<< HEAD
        success: function (allPermissions) {
=======
        success: function(allPermissions) {
>>>>>>> origin/main
          // Load user's current permissions
          $.ajax({
            url: "<?= base_url('permission/getPermissions') ?>",
            type: "POST",
            data: {
              user_id: userId
            },
            dataType: "json",
<<<<<<< HEAD
            success: function (userPermissions) {
              renderPermissionsForm(allPermissions, userPermissions);
              $("#editPermissionsModal").modal("show");
            },
            error: function () {
              alert("Error loading user permissions.");
            }
          });
        },
        error: function () {
          alert("Error loading available permissions.");
=======
            success: function(userPermissions) {
              renderPermissionsForm(allPermissions, userPermissions);
              $("#editPermissionsModal").modal("show");
            },
            error: function() {
              Swal.fire('Error', 'Error loading user permissions.', 'error');
            }
          });
        },
        error: function() {
          Swal.fire('Error', 'Error loading available permissions.', 'error');
>>>>>>> origin/main
        }
      });
    });

    // Render permissions form with checkboxes
    function renderPermissionsForm(allPermissions, userPermissions) {
      var container = $("#permissionsContainer");
      container.empty();

      // Group permissions by module
      var groupedPermissions = {};
<<<<<<< HEAD
      allPermissions.forEach(function (permission) {
=======
      allPermissions.forEach(function(permission) {
>>>>>>> origin/main
        if (!groupedPermissions[permission.module]) {
          groupedPermissions[permission.module] = [];
        }
        groupedPermissions[permission.module].push(permission);
      });

      // Create checkboxes for each permission
      for (var module in groupedPermissions) {
        var moduleSection = $('<div class="module-section"></div>');
<<<<<<< HEAD
        var moduleHeader = $('<h6 class="module-header"><i class="bi bi-folder me-2"></i>' + module + '</h6>');
        var permissionsGrid = $('<div class="permissions-grid"></div>');

        groupedPermissions[module].forEach(function (permission) {
          var isChecked = userPermissions.some(function (userPerm) {
=======
        var moduleHeader = $('<h6 class="module-header"><i class="bi bi-folder-fill text-warning me-2"></i>' + module + '</h6>');
        var permissionsGrid = $('<div class="permissions-grid"></div>');

        groupedPermissions[module].forEach(function(permission) {
          var isChecked = userPermissions.some(function(userPerm) {
>>>>>>> origin/main
            return userPerm.id === permission.id;
          });

          var permissionItem = $('<div class="permission-item"></div>');
          var checkbox = $('<input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" value="' + permission.id + '" id="perm_' + permission.id + '">');
          var label = $('<label class="permission-label" for="perm_' + permission.id + '">' + permission.name + '</label>');

          if (isChecked) {
            checkbox.prop('checked', true);
          }

          permissionItem.append(checkbox);
          permissionItem.append(label);
          permissionsGrid.append(permissionItem);
        });

        moduleSection.append(moduleHeader);
        moduleSection.append(permissionsGrid);
        container.append(moduleSection);
      }

      // Select all functionality
<<<<<<< HEAD
      $("#selectAllPermissions").change(function () {
=======
      $("#selectAllPermissions").off('change').on('change', function() {
>>>>>>> origin/main
        var isChecked = $(this).prop('checked');
        $(".permission-checkbox").prop('checked', isChecked);
      });

      // Individual checkbox change
<<<<<<< HEAD
      $(".permission-checkbox").change(function () {
=======
      $(".permission-checkbox").off('change').on('change', function() {
>>>>>>> origin/main
        var allChecked = $(".permission-checkbox").length === $(".permission-checkbox:checked").length;
        $("#selectAllPermissions").prop('checked', allChecked);
      });

      // Initialize select all based on current selections
      var allChecked = $(".permission-checkbox").length === $(".permission-checkbox:checked").length;
      $("#selectAllPermissions").prop('checked', allChecked);
    }

<<<<<<< HEAD
    // Add / Update Employee Form Submit
    $("#updateUser").submit(function (e) {
      e.preventDefault(); // stop normal form submit

      var form = $(this);
      var url = form.attr("action");
      var formData = form.serialize();

      $.ajax({
        url: url,
        type: "POST",
        data: formData,
        dataType: "json",
        success: function (response) {

          if (response.success || response.status === true) {

            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: response.message || 'Employee saved successfully!',
              timer: 1500,
              showConfirmButton: false
            });

            // Close offcanvas
            var offcanvasElement = document.getElementById('form-offcanvas');
            var offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
            if (offcanvas) offcanvas.hide();

            // Reload page
            setTimeout(() => location.reload(), 1200);

          } else {

            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: response.message || 'Failed to save employee'
            });

          }
        },
        error: function () {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Something went wrong'
          });
        }
      });
    });

    // Save Permissions
    $("#savePermissions").click(function () {
=======
    // Save Permissions
    $("#savePermissions").click(function() {
      var btn = $(this);
      var origText = btn.html();
>>>>>>> origin/main
      var userId = $("#permission_user_id").val();
      var roleId = $("#permission_role_id").val();

      var selectedPermissions = [];
<<<<<<< HEAD

      $(".permission-checkbox:checked").each(function () {
        selectedPermissions.push($(this).val());
      });

=======
      $(".permission-checkbox:checked").each(function() {
        selectedPermissions.push($(this).val());
      });

      btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');

>>>>>>> origin/main
      $.ajax({
        url: "<?= base_url('permission/updatePermissions') ?>",
        type: "POST",
        data: {
          user_id: userId,
          role_id: roleId,
          permissions: selectedPermissions
        },
        dataType: "json",
<<<<<<< HEAD
        success: function (response) {
=======
        success: function(response) {
          btn.prop('disabled', false).html(origText);
>>>>>>> origin/main
          if (response.success) {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: 'Permissions updated successfully!',
<<<<<<< HEAD
              timer: 2000,
              showConfirmButton: false
            });

            $("#editPermissionsModal").modal("hide");
            setTimeout(() => location.reload(), 1000)
=======
              timer: 1500,
              showConfirmButton: false
            });
            $("#editPermissionsModal").modal("hide");
>>>>>>> origin/main
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
<<<<<<< HEAD
              text: 'Error updating permissions: ' + response.message
            });
          }
        },
        error: function () {
=======
              text: response.message || 'Error updating permissions.'
            });
          }
        },
        error: function() {
          btn.prop('disabled', false).html(origText);
>>>>>>> origin/main
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error updating permissions.'
          });
        }
      });
    });

<<<<<<< HEAD
    // Edit Employee
    $(".edit-employee").click(function (e) {
=======
    // Add / Update Employee Form Submit
    $("#updateUser").submit(function(e) {
      e.preventDefault();

      var form = $(this);
      var url = form.attr("action");
      var formData = form.serialize();
      var submitBtn = form.find('button[type="submit"]');
      var origBtnHtml = submitBtn.html();

      submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');

      $.ajax({
        url: url,
        type: "POST",
        data: formData,
        dataType: "json",
        success: function(response) {
          submitBtn.prop('disabled', false).html(origBtnHtml);
          if (response.success || response.status === true) {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: response.message || 'Employee saved successfully!',
              timer: 1500,
              showConfirmButton: false
            }).then(() => {
              location.reload();
            });

            var offcanvasElement = document.getElementById('form-offcanvas');
            var offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
            if (offcanvas) offcanvas.hide();
          } else {
            var msg = response.message;
            if (!msg && response.errors) {
              if (typeof response.errors === 'object') {
                msg = Object.values(response.errors).join("<br>");
              } else {
                msg = response.errors;
              }
            }
            Swal.fire({
              icon: 'error',
              title: 'Error',
              html: msg || 'Failed to save employee'
            });
          }
        },
        error: function(xhr) {
          submitBtn.prop('disabled', false).html(origBtnHtml);
          var msg = 'Something went wrong';
          if (xhr.responseJSON && xhr.responseJSON.message) {
            msg = xhr.responseJSON.message;
          }
          Swal.fire({
            icon: 'error',
            title: 'Error',
            html: msg
          });
        }
      });
    });

    // Edit Employee
    $(".edit-employee").click(function(e) {
>>>>>>> origin/main
      e.preventDefault();

      var id = $(this).data("id");
      var name = $(this).data("name");
      var email = $(this).data("email");
      var roleId = $(this).data("role-id");
      var status = $(this).data("status");

<<<<<<< HEAD
      // Fill the form with employee data
=======
>>>>>>> origin/main
      $("#id").val(id);
      $("#name").val(name);
      $("#email").val(email);
      $("#user_type").val(roleId);

<<<<<<< HEAD
      // Set status checkbox
=======
>>>>>>> origin/main
      if (status == '1') {
        $("#category-status").prop('checked', true);
      } else {
        $("#category-status").prop('checked', false);
      }

<<<<<<< HEAD
      // Update form title and action
      $("#form-offcanvasLabel").text("Edit Employee");
      $("#updateUser").attr("action", "<?= base_url() ?>updateEmployee");

      // Show the offcanvas
=======
      $("#form-offcanvasLabel").html('<i class="bi bi-pencil-square text-info"></i> Edit Employee');
      $("#updateUser").attr("action", "<?= base_url() ?>updateEmployee");

>>>>>>> origin/main
      var offcanvas = new bootstrap.Offcanvas(document.getElementById('form-offcanvas'));
      offcanvas.show();
    });

    // Add New Employee
<<<<<<< HEAD
    $(".add").click(function (e) {
      e.preventDefault();

      // Clear the form
      $("#updateUser")[0].reset();
      $("#id").val('');

      // Update form title and action
      $("#form-offcanvasLabel").text("Add New Employee");
      $("#updateUser").attr("action", "<?= base_url() ?>addEmployee");

      // Set default status to active
      $("#category-status").prop('checked', true);

      // Show the offcanvas
      var offcanvas = new bootstrap.Offcanvas(document.getElementById('form-offcanvas'));
      offcanvas.show();
    });

    // Delete confirmation
    // $(document).on('click', 'a[data-confirm]', function (e) {
    //   e.preventDefault();
    //   var link = $(this);
    //   var confirmMessage = link.data('confirm');

    //   Swal.fire({
    //     title: 'Are you sure?',
    //     text: confirmMessage,
    //     icon: 'warning',
    //     showCancelButton: true,
    //     confirmButtonColor: '#3085d6',
    //     cancelButtonColor: '#d33',
    //     confirmButtonText: 'Yes, delete it!',
    //     cancelButtonText: 'Cancel',
    //     width: '400px',
    //     padding: '20px'
    //   }).then((result) => {
    //     if (result.isConfirmed) {
    //       window.location.href = link.attr('href') + '/' + link.data('id');
    //     }
    //   });
    // });

    // Initialize on DOM ready
    console.log('Employees Management page loaded');
  });
</script>
=======
    $(".add").click(function(e) {
      e.preventDefault();

      $("#updateUser")[0].reset();
      $("#id").val('');

      $("#form-offcanvasLabel").html('<i class="bi bi-person-plus text-info"></i> Add New Employee');
      $("#updateUser").attr("action", "<?= base_url() ?>addEmployee");

      $("#category-status").prop('checked', true);

      var offcanvas = new bootstrap.Offcanvas(document.getElementById('form-offcanvas'));
      offcanvas.show();
    });
  });
</script>

>>>>>>> origin/main
<?= $this->endSection() ?>