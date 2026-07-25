<?= $this->extend('admin/includes/header'); ?>
<!--start content-->
<?= $this->section('content'); ?>

<main class="main-content">
  <div class="position-relative iq-banner">
    <!--Nav Start-->
    <?= $this->include('admin/nav_bar') ?>
  </div>

  <div class="container-fluid content-inner px-4" id="page_layout">

    <!-- Modern Header Section -->
    <div class="container-fluid p-0 bg-gradient-primary rounded-3 mb-4">
      <div class=" text-white p-3  shadow position-relative overflow-hidden">
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
            </div>
          </div>
        </div>
      </div>
    </div>

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
        </div>
      </div>
    </div>

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
                      </div>
                    </div>
                  </div>
                </td>
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
                    </a>
                  </div>
                </td>
              </tr>
              <?php
              $i++;
            endforeach;
            if (empty($users)): ?>
              <tr>
                <td colspan="6" class="text-center py-5 text-muted">
                  <i class="bi bi-people fs-2 d-block mb-3 opacity-25"></i>
                  No employees found
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

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
            </div>
          </div>
        </div>
      </div>
      <div class="offcanvas-footer border-top p-4">
        <div class="d-flex gap-3">
          <button class="btn bg-primary d-flex align-items-center gap-2 flex-grow-1 justify-content-center text-white"
            type="submit">
            <i class="bi bi-check-circle"></i> Save Employee
          </button>
          <button class="btn btn-outline-secondary d-flex align-items-center gap-2" type="button"
            data-bs-dismiss="offcanvas">
            <i class="bi bi-x-circle"></i> Cancel
          </button>
        </div>
      </div>
    </div>
  </form>

  <!-- View Permissions Modal -->
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
              <!-- Permissions will be loaded here dynamically -->
            </ul>
          </div>
        </div>
        <div class="modal-footer border-top">
          <button type="button" class="btn btn-secondary px-4 py-2 fs-6" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i>Close
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Permissions Modal -->
  <div class="modal fade" id="editPermissionsModal" tabindex="-1" aria-labelledby="editPermissionsModalLabel"
    aria-hidden="true" style="overflow: hidden;">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
      <div class="modal-content border-0 shadow p-0" style="height: 90vh;">
        <div class="modal-header" style="background: #c6d2ff!important; border-bottom: 2px solid #a3b4ff;">
          <h5 class="modal-title mb-0 text-black fw-bold fs-4" id="editPermissionsModalLabel">Edit User Permissions</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0" style="overflow-y: auto; flex: 1;">
          <div class="p-4">
            <form id="permissionsForm">
              <input type="hidden" name="user_id" id="permission_user_id">
              <input type="hidden" name="role_id" id="permission_role_id">

              <div class="permissions-container">
                <div class="select-all-section mb-3 p-2 rounded shadow-sm">
                  <div class="form-check">
                    <input class="form-check-input select-all-checkbox" type="checkbox" id="selectAllPermissions"
                      style="width: 1.2em; height: 1.2em;">
                    <label class="form-check-label fw-semibold fs-6" for="selectAllPermissions">
                      Select All Permissions
                    </label>
                  </div>
                </div>
                <div id="permissionsContainer">
                  <!-- Permissions checkboxes will be loaded here dynamically -->
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="modal-footer border-top">
          <button type="button" class="btn btn-secondary px-4 py-2 fs-6" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i>Cancel
          </button>
          <button type="button" class="btn text-white bg-primary px-4 py-2 fs-6" id="savePermissions">
            <i class="bi bi-check-circle me-1"></i>Save Changes
          </button>
        </div>
      </div>
    </div>
  </div>

  <?= $this->include('admin/footer') ?>
</main>

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
        success: function (response) {

          if (response.success || response.status === true) {

            Swal.fire({
              icon: 'success',
              title: 'Updated!',
              text: response.message || 'Status updated successfully',
              timer: 1500,
              showConfirmButton: false
            })
            setTimeout(() => location.reload(), 1000)

          } else {

            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: response.message || 'Failed to update status'
            });

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

      $.ajax({
        url: "<?= base_url('permission/getPermissions') ?>",
        type: "POST",
        data: {
          user_id: userId
        },
        dataType: "json",
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
                '</li>'
              );
            });
          } else {
            permissionsList.append(
              '<li class="list-group-item text-center py-5 text-muted border-0">' +
              '<i class="bi bi-shield-slash fs-1 d-block mb-3 opacity-25"></i>' +
              '<div class="fs-5">No permissions assigned</div>' +
              '</li>'
            );
          }

          $("#permissionsModal").modal("show");
        },
        error: function () {
          alert("Error fetching permissions.");
        }
      });
    });

    // Edit Permissions
    $(".edit-permissions").click(function (e) {
      e.preventDefault();
      var userId = $(this).data("id");
      var userName = $(this).data("name");
      var roleId = $(this).data("role-id");

      $("#editPermissionsModalLabel").text("Edit Permissions - " + userName);
      $("#permission_user_id").val(userId);
      $("#permission_role_id").val(roleId);

      // Load available permissions
      $.ajax({
        url: "<?= base_url('permission/getAllPermissions') ?>",
        type: "GET",
        dataType: "json",
        success: function (allPermissions) {
          // Load user's current permissions
          $.ajax({
            url: "<?= base_url('permission/getPermissions') ?>",
            type: "POST",
            data: {
              user_id: userId
            },
            dataType: "json",
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
        }
      });
    });

    // Render permissions form with checkboxes
    function renderPermissionsForm(allPermissions, userPermissions) {
      var container = $("#permissionsContainer");
      container.empty();

      // Group permissions by module
      var groupedPermissions = {};
      allPermissions.forEach(function (permission) {
        if (!groupedPermissions[permission.module]) {
          groupedPermissions[permission.module] = [];
        }
        groupedPermissions[permission.module].push(permission);
      });

      // Create checkboxes for each permission
      for (var module in groupedPermissions) {
        var moduleSection = $('<div class="module-section"></div>');
        var moduleHeader = $('<h6 class="module-header"><i class="bi bi-folder me-2"></i>' + module + '</h6>');
        var permissionsGrid = $('<div class="permissions-grid"></div>');

        groupedPermissions[module].forEach(function (permission) {
          var isChecked = userPermissions.some(function (userPerm) {
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
      $("#selectAllPermissions").change(function () {
        var isChecked = $(this).prop('checked');
        $(".permission-checkbox").prop('checked', isChecked);
      });

      // Individual checkbox change
      $(".permission-checkbox").change(function () {
        var allChecked = $(".permission-checkbox").length === $(".permission-checkbox:checked").length;
        $("#selectAllPermissions").prop('checked', allChecked);
      });

      // Initialize select all based on current selections
      var allChecked = $(".permission-checkbox").length === $(".permission-checkbox:checked").length;
      $("#selectAllPermissions").prop('checked', allChecked);
    }

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
      var userId = $("#permission_user_id").val();
      var roleId = $("#permission_role_id").val();

      var selectedPermissions = [];

      $(".permission-checkbox:checked").each(function () {
        selectedPermissions.push($(this).val());
      });

      $.ajax({
        url: "<?= base_url('permission/updatePermissions') ?>",
        type: "POST",
        data: {
          user_id: userId,
          role_id: roleId,
          permissions: selectedPermissions
        },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: 'Permissions updated successfully!',
              timer: 2000,
              showConfirmButton: false
            });

            $("#editPermissionsModal").modal("hide");
            setTimeout(() => location.reload(), 1000)
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Error updating permissions: ' + response.message
            });
          }
        },
        error: function () {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error updating permissions.'
          });
        }
      });
    });

    // Edit Employee
    $(".edit-employee").click(function (e) {
      e.preventDefault();

      var id = $(this).data("id");
      var name = $(this).data("name");
      var email = $(this).data("email");
      var roleId = $(this).data("role-id");
      var status = $(this).data("status");

      // Fill the form with employee data
      $("#id").val(id);
      $("#name").val(name);
      $("#email").val(email);
      $("#user_type").val(roleId);

      // Set status checkbox
      if (status == '1') {
        $("#category-status").prop('checked', true);
      } else {
        $("#category-status").prop('checked', false);
      }

      // Update form title and action
      $("#form-offcanvasLabel").text("Edit Employee");
      $("#updateUser").attr("action", "<?= base_url() ?>updateEmployee");

      // Show the offcanvas
      var offcanvas = new bootstrap.Offcanvas(document.getElementById('form-offcanvas'));
      offcanvas.show();
    });

    // Add New Employee
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
<?= $this->endSection() ?>