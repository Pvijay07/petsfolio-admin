<?= $this->extend('admin/includes/header'); ?>
<!--start content-->
<?= $this->section('content'); ?>

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
    <?= $this->include('admin/nav_bar') ?>
  </div>

  <div class="container-fluid content-inner px-4" id="page_layout">

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
            </div>
          </div>
        </div>
      </div>
    </div>

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
        </div>
      </div>
    </div>

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
                      </div>
                    </div>
                  </div>
                </td>
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
                    </a>
                  </div>
                </td>
              </tr>
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
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
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
            </div>
          </div>
        </div>
      </div>
      <div class="offcanvas-footer border-top p-3 bg-white">
        <div class="d-flex gap-2">
          <button class="btn btn-light flex-fill rounded-3 py-2 fw-semibold" type="button" data-bs-dismiss="offcanvas">
            <i class="bi bi-x-circle me-1"></i> Cancel
          </button>
          <button class="btn btn-primary flex-fill rounded-3 py-2 fw-semibold" type="submit" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); border: none;">
            <i class="bi bi-check-circle me-1"></i> Save Employee
          </button>
        </div>
      </div>
    </div>
  </form>

  <!-- View Permissions Modal -->
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
              <!-- Permissions will be loaded here dynamically -->
            </ul>
          </div>
        </div>
        <div class="modal-footer border-top bg-white p-3">
          <button type="button" class="btn btn-secondary px-4 py-2 rounded-3" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Close
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Permissions Modal -->
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
          <div class="p-4">
            <form id="permissionsForm">
              <input type="hidden" name="user_id" id="permission_user_id">
              <input type="hidden" name="role_id" id="permission_role_id">

              <div class="permissions-container">
                <div class="select-all-section mb-3">
                  <div class="form-check d-flex align-items-center gap-2 m-0">
                    <input class="form-check-input select-all-checkbox" type="checkbox" id="selectAllPermissions" style="width: 1.2em; height: 1.2em; cursor: pointer;">
                    <label class="form-check-label fw-bold text-dark" for="selectAllPermissions" style="cursor: pointer;">
                      Select All Permissions Across Modules
                    </label>
                  </div>
                </div>
                <div id="permissionsContainer">
                  <!-- Permissions checkboxes dynamically generated -->
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="modal-footer border-top bg-white p-3">
          <button type="button" class="btn btn-light px-4 py-2 rounded-3 fw-semibold" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Cancel
          </button>
          <button type="button" class="btn btn-primary px-4 py-2 rounded-3 fw-semibold" id="savePermissions" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); border: none;">
            <i class="bi bi-check-circle me-1"></i> Save Changes
          </button>
        </div>
      </div>
    </div>
  </div>

  <?= $this->include('admin/footer') ?>
</main>

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
        success: function(response) {
          if (response.success || response.status === true) {
            Swal.fire({
              icon: 'success',
              title: 'Updated!',
              text: response.message || 'Status updated successfully',
              timer: 1200,
              showConfirmButton: false
            });
            checkbox.closest('tr').attr('data-status', status == 1 ? 'active' : 'inactive');
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: response.message || 'Failed to update status'
            });
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

      $.ajax({
        url: "<?= base_url('permission/getPermissions') ?>",
        type: "POST",
        data: {
          user_id: userId
        },
        dataType: "json",
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
                '</li>'
              );
            });
          } else {
            permissionsList.append(
              '<li class="list-group-item text-center py-5 text-muted border-0">' +
              '<i class="bi bi-shield-slash fs-1 d-block mb-2 opacity-25"></i>' +
              '<div class="fw-semibold">No permissions assigned to this user</div>' +
              '</li>'
            );
          }

          $("#permissionsModal").modal("show");
        },
        error: function() {
          Swal.fire('Error', 'Error fetching employee permissions.', 'error');
        }
      });
    });

    // Edit Permissions
    $(".edit-permissions").click(function(e) {
      e.preventDefault();
      var userId = $(this).data("id");
      var userName = $(this).data("name");
      var roleId = $(this).data("role-id");

      $("#editPermissionsModalLabel").html('<i class="bi bi-shield-lock text-warning me-2"></i> Edit Permissions: ' + userName);
      $("#permission_user_id").val(userId);
      $("#permission_role_id").val(roleId);

      // Load available permissions
      $.ajax({
        url: "<?= base_url('permission/getAllPermissions') ?>",
        type: "GET",
        dataType: "json",
        success: function(allPermissions) {
          // Load user's current permissions
          $.ajax({
            url: "<?= base_url('permission/getPermissions') ?>",
            type: "POST",
            data: {
              user_id: userId
            },
            dataType: "json",
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
        }
      });
    });

    // Render permissions form with checkboxes
    function renderPermissionsForm(allPermissions, userPermissions) {
      var container = $("#permissionsContainer");
      container.empty();

      // Group permissions by module
      var groupedPermissions = {};
      allPermissions.forEach(function(permission) {
        if (!groupedPermissions[permission.module]) {
          groupedPermissions[permission.module] = [];
        }
        groupedPermissions[permission.module].push(permission);
      });

      // Create checkboxes for each permission
      for (var module in groupedPermissions) {
        var moduleSection = $('<div class="module-section"></div>');
        var moduleHeader = $('<h6 class="module-header"><i class="bi bi-folder-fill text-warning me-2"></i>' + module + '</h6>');
        var permissionsGrid = $('<div class="permissions-grid"></div>');

        groupedPermissions[module].forEach(function(permission) {
          var isChecked = userPermissions.some(function(userPerm) {
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
      $("#selectAllPermissions").off('change').on('change', function() {
        var isChecked = $(this).prop('checked');
        $(".permission-checkbox").prop('checked', isChecked);
      });

      // Individual checkbox change
      $(".permission-checkbox").off('change').on('change', function() {
        var allChecked = $(".permission-checkbox").length === $(".permission-checkbox:checked").length;
        $("#selectAllPermissions").prop('checked', allChecked);
      });

      // Initialize select all based on current selections
      var allChecked = $(".permission-checkbox").length === $(".permission-checkbox:checked").length;
      $("#selectAllPermissions").prop('checked', allChecked);
    }

    // Save Permissions
    $("#savePermissions").click(function() {
      var btn = $(this);
      var origText = btn.html();
      var userId = $("#permission_user_id").val();
      var roleId = $("#permission_role_id").val();

      var selectedPermissions = [];
      $(".permission-checkbox:checked").each(function() {
        selectedPermissions.push($(this).val());
      });

      btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');

      $.ajax({
        url: "<?= base_url('permission/updatePermissions') ?>",
        type: "POST",
        data: {
          user_id: userId,
          role_id: roleId,
          permissions: selectedPermissions
        },
        dataType: "json",
        success: function(response) {
          btn.prop('disabled', false).html(origText);
          if (response.success) {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: 'Permissions updated successfully!',
              timer: 1500,
              showConfirmButton: false
            });
            $("#editPermissionsModal").modal("hide");
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: response.message || 'Error updating permissions.'
            });
          }
        },
        error: function() {
          btn.prop('disabled', false).html(origText);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error updating permissions.'
          });
        }
      });
    });

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
      e.preventDefault();

      var id = $(this).data("id");
      var name = $(this).data("name");
      var email = $(this).data("email");
      var roleId = $(this).data("role-id");
      var status = $(this).data("status");

      $("#id").val(id);
      $("#name").val(name);
      $("#email").val(email);
      $("#user_type").val(roleId);

      if (status == '1') {
        $("#category-status").prop('checked', true);
      } else {
        $("#category-status").prop('checked', false);
      }

      $("#form-offcanvasLabel").html('<i class="bi bi-pencil-square text-info"></i> Edit Employee');
      $("#updateUser").attr("action", "<?= base_url() ?>updateEmployee");

      var offcanvas = new bootstrap.Offcanvas(document.getElementById('form-offcanvas'));
      offcanvas.show();
    });

    // Add New Employee
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

<?= $this->endSection() ?>