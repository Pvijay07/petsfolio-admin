<?php echo $this->extend('admin/includes/header'); ?>
<?php echo $this->section('content'); ?>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?php echo $this->include('admin/nav_bar') ?>
  </div>

  <div class="conatiner-fluid content-inner pb-0 px-4" id="page_layout">
    <!-- Modern Header Section -->
    <div class="container-fluid p-0 mb-4 bg-gradient-primary rounded-3">
      <div class="text-white p-5 shadow position-relative overflow-hidden">
        <div class="position-relative z-1">
          <h1 class="h2 fw-bold mb-2 text-white">Permissions Management</h1>
          <p class="text-white-75 mb-0">Manage and assign permissions to user roles</p>
        </div>
        <i class="bi bi-shield-lock position-absolute end-0 bottom-0 opacity-10"
          style="font-size: 12rem; transform: rotate(12deg); margin-right: -2rem; margin-bottom: -2rem;"></i>
      </div>
    
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
      <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div
                class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-eye fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">View Permissions</p>
                <h3 class="mb-0">
                  <?= count(array_filter($permissions, function($perm) {
                    return $perm['name'] == 'view';
                  })) ?>
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div
                class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-plus-circle fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Add Permissions</p>
                <h3 class="mb-0">
                  <?= count(array_filter($permissions, function($perm) {
                    return $perm['name'] == 'add';
                  })) ?>
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div
                class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-pencil-square fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Edit Permissions</p>
                <h3 class="mb-0">
                  <?= count(array_filter($permissions, function($perm) {
                    return $perm['name'] == 'edit';
                  })) ?>
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div
                class="rounded-circle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-trash fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Delete Permissions</p>
                <h3 class="mb-0">
                  <?= count(array_filter($permissions, function($perm) {
                    return $perm['name'] == 'delete';
                  })) ?>
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-8">
        <!-- Add New Permission Card -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-transparent border-0 p-4">
            <h4 class="card-title mb-0 d-flex align-items-center gap-2">
              <i class="bi bi-plus-circle text-primary"></i>
              Add New Permission
            </h4>
            <p class="text-muted small mb-0">Create new permission for specific module</p>
          </div>
          <div class="card-body p-4 pt-0">
            <form method="post" action="<?= base_url('admin/add-permission') ?>" class="needs-validation" novalidate>
              <div class="row g-3">
                <div class="col-md-5">
                  <label class="form-label fw-medium text-muted mb-2">Module Name</label>
                  <div class="input-group">
                    <span class="input-group-text bg-light">
                      <i class="bi bi-folder text-muted"></i>
                    </span>
                    <input type="text" name="module" class="form-control" placeholder="Enter module name" required>
                    <div class="invalid-feedback">
                      Please enter module name.
                    </div>
                  </div>
                </div>
                <div class="col-md-5">
                  <label class="form-label fw-medium text-muted mb-2">Permission Type</label>
                  <div class="input-group">
                    <span class="input-group-text bg-light">
                      <i class="bi bi-shield text-muted"></i>
                    </span>
                    <select name="name" class="form-select" required>
                      <option value="">Select permission</option>
                      <option value="view">View</option>
                      <option value="add">Add</option>
                      <option value="edit">Edit</option>
                      <option value="delete">Delete</option>
                    </select>
                    <div class="invalid-feedback">
                      Please select permission type.
                    </div>
                  </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                  <button type="submit" class="btn bg-primary text-white w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-plus-lg"></i>
                    <span class="d-none d-md-inline">Add</span>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <!-- Role Selection Card -->
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-transparent border-0 p-3">
            <h4 class="card-title mb-0 d-flex align-items-center gap-2">
              <i class="bi bi-person-badge text-success"></i>
             Role for Assignment
            </h4>
            <p class="text-muted small mb-0">Choose a role to assign permissions</p>
          </div>
          <div class="card-body p-3 pt-0">
            <form method="post" action="<?= base_url('admin/assign-permissions') ?>" id="assignPermissionsForm">
              <div class="mb-4">
                <label class="form-label fw-medium text-muted mb-2">Select Role</label>
                <div class="input-group">
                  <span class="input-group-text bg-light">
                    <i class="fa-solid fa-user-group text-black"></i>
                  </span>
                  <select name="role_id" class="form-select" id="roleSelect" required>
                    <option value="">Select a role</option>
                    <?php foreach ($roles as $role): ?>
                      <option value="<?= $role->id ?>"><?= esc($role->name) ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback">
                    Please select a role.
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Permissions Table Card -->
    <div class="card border-0 shadow-sm overflow-hidden">
      <div class="card-header bg-transparent border-0 p-4">
        <h4 class="card-title mb-0 d-flex align-items-center gap-2">
          <i class="bi bi-list-check text-warning"></i>
          Permissions Assignment Matrix
        </h4>
        <p class="text-muted small mb-0">Select permissions to assign to the selected role</p>
      </div>
      <div class="card-body p-0 pt-0">
        <div class="table-responsive">
          <table class="table table-hover table-bordered mb-0" style="table-layout: fixed; width: 100%;">
            <thead style="background-color: #c6d2ff!important;">
              <tr>
                <th width="250" class="py-3 text-black fs-6 fw-semibold border-bottom" style="background: #c6d2ff!important;">
                  Module
                </th>
                <th width="150" class="py-3 text-black fs-6 fw-semibold border-bottom text-center" style="background: #c6d2ff!important;">
                  <div class="d-flex align-items-center justify-content-center gap-2">
                    <input type="checkbox" class="form-check-input" id="select-all-view">
                    <label class="form-check-label mb-0">View</label>
                  </div>
                </th>
                <th width="150" class="py-3 text-black fs-6 fw-semibold border-bottom text-center" style="background: #c6d2ff!important;">
                  <div class="d-flex align-items-center justify-content-center gap-2">
                    <input type="checkbox" class="form-check-input" id="select-all-add">
                    <label class="form-check-label mb-0">Add</label>
                  </div>
                </th>
                <th width="150" class="py-3 text-black fs-6 fw-semibold border-bottom text-center" style="background: #c6d2ff!important;">
                  <div class="d-flex align-items-center justify-content-center gap-2">
                    <input type="checkbox" class="form-check-input" id="select-all-edit">
                    <label class="form-check-label mb-0">Edit</label>
                  </div>
                </th>
                <th width="150" class="py-3 text-black fs-6 fw-semibold border-bottom text-center" style="background: #c6d2ff!important;">
                  <div class="d-flex align-items-center justify-content-center gap-2">
                    <input type="checkbox" class="form-check-input" id="select-all-delete">
                    <label class="form-check-label mb-0">Delete</label>
                  </div>
                </th>
              </tr>
            </thead>
            <tbody>
              <?php
              $modules = array_unique(array_column($permissions, 'module'));
              foreach ($modules as $module): ?>
                <tr>
                  <td class="py-3">
                    <div class="d-flex align-items-center gap-2">
                      <div class="bg-primary bg-opacity-10 rounded p-2">
                        <i class="bi bi-folder text-white"></i>
                      </div>
                      <span class="fw-medium text-dark"><?= esc($module) ?></span>
                    </div>
                  </td>
                  <?php foreach (['view', 'add', 'edit', 'delete'] as $perm):
                    $permExists = array_filter($permissions, function ($p) use ($module, $perm) {
                      return $p['module'] == $module && $p['name'] == $perm;
                    });
                    $permissionId = !empty($permExists) ? reset($permExists)['id'] : '';
                  ?>
                    <td class="py-3 text-center">
                      <div class="form-check d-inline-block">
                        <input type="checkbox" 
                               class="form-check-input perm-checkbox perm-<?= $perm ?>" 
                               name="permissions[]" 
                               value="<?= $permissionId ?>" 
                               id="perm_<?= $module ?>_<?= $perm ?>"
                               <?= $permissionId ? 'checked' : '' ?>
                               data-perm-type="<?= $perm ?>"
                               data-module="<?= $module ?>">
                        <label class="form-check-label" for="perm_<?= $module ?>_<?= $perm ?>"></label>
                      </div>
                    </td>
                  <?php endforeach; ?>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div class="mt-4 text-center">
          <button type="submit" form="assignPermissionsForm" class="btn bg-success text-white d-flex align-items-center justify-content-center gap-2 mx-auto px-5">
            <i class="bi bi-check-circle"></i>
            Assign Permissions to Selected Role
          </button>
        </div>
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
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .table tbody td {
    vertical-align: middle;
    padding-top: 1rem;
    padding-bottom: 1rem;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .table tbody tr {
    transition: all 0.2s ease;
  }

  .table tbody tr:hover {
    background-color: rgba(99, 102, 241, 0.03) !important;
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
  .form-select:focus,
  .form-check-input:focus {
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25) !important;
  }

  .form-check-input:checked {
    background-color: #6366f1 !important;
    border-color: #6366f1 !important;
  }

  /* Custom checkbox styling */
  .form-check {
    margin-bottom: 0;
  }

  .form-check-input {
    width: 1.2em;
    height: 1.2em;
    cursor: pointer;
  }

  .form-check-label {
    cursor: pointer;
  }

  /* Fixed table layout */
  .table {
    table-layout: fixed !important;
    width: 100% !important;
  }

  /* Responsive adjustments */
  @media (max-width: 992px) {
    .table-responsive {
      overflow-x: auto;
    }
    
    .table {
      min-width: 700px;
    }
    
    .row.g-4 .col-md-3 {
      margin-bottom: 1rem;
    }

    .col-lg-6 {
      margin-bottom: 1.5rem;
    }
  }

  @media (max-width: 768px) {
    .d-flex.justify-content-between.align-items-center {
      flex-direction: column;
      gap: 1rem;
    }

    .table td,
    .table th {
      padding: 0.75rem 0.5rem;
    }

    .btn span.d-none.d-md-inline {
      display: none !important;
    }

    .input-group {
      flex-direction: column;
    }

    .input-group-text {
      width: 100%;
      justify-content: center;
      border-radius: 0.375rem 0.375rem 0 0 !important;
    }

    .form-control,
    .form-select {
      border-radius: 0 0 0.375rem 0.375rem !important;
    }
  }

  @media (max-width: 576px) {
    .table {
      min-width: 600px;
    }

    .btn {
      padding: 0.5rem 1rem;
      font-size: 0.875rem;
    }

    .col-md-1 {
      margin-top: 1rem;
    }
  }

  /* Summary card hover effects */
  .card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease;
  }

  /* Validation styles */
  .was-validated .form-control:invalid,
  .was-validated .form-select:invalid {
    border-color: #dc3545 !important;
  }

  .was-validated .form-control:invalid:focus,
  .was-validated .form-select:invalid:focus {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;
  }
</style>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
      form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });

    // Select all checkboxes for each permission type
    function setupSelectAll(mainCheckboxId, checkboxesClass) {
      const mainCheckbox = document.getElementById(mainCheckboxId);
      if (mainCheckbox) {
        mainCheckbox.addEventListener("change", function() {
          let checkboxes = document.querySelectorAll("." + checkboxesClass);
          checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
          });
        });
      }
    }

    setupSelectAll("select-all-view", "perm-view"); // Select all View permissions
    setupSelectAll("select-all-add", "perm-add"); // Select all Add permissions
    setupSelectAll("select-all-edit", "perm-edit"); // Select all Edit permissions
    setupSelectAll("select-all-delete", "perm-delete"); // Select all Delete permissions

    // Role selection validation for the assign form
    const roleSelect = document.getElementById('roleSelect');
    const assignForm = document.getElementById('assignPermissionsForm');
    
    if (assignForm) {
      assignForm.addEventListener('submit', function(event) {
        if (!roleSelect.value) {
          event.preventDefault();
          event.stopPropagation();
          roleSelect.classList.add('is-invalid');
          
          // Show toast or alert
          showNotification('Please select a role before assigning permissions.', 'warning');
        } else {
          roleSelect.classList.remove('is-invalid');
        }
      });
    }

    // Show notification function
    function showNotification(message, type = 'info') {
      const types = {
        'success': { bg: 'bg-success', icon: 'bi-check-circle' },
        'warning': { bg: 'bg-warning', icon: 'bi-exclamation-triangle' },
        'danger': { bg: 'bg-danger', icon: 'bi-x-circle' },
        'info': { bg: 'bg-info', icon: 'bi-info-circle' }
      };

      const toastId = 'toast-' + Date.now();
      const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-white ${types[type].bg} border-0 position-fixed bottom-0 end-0 m-3" role="alert">
          <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2">
              <i class="bi ${types[type].icon} fs-5"></i>
              ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
          </div>
        </div>
      `;

      document.body.insertAdjacentHTML('beforeend', toastHtml);
      const toastElement = document.getElementById(toastId);
      const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
      toast.show();

      // Remove toast after it's hidden
      toastElement.addEventListener('hidden.bs.toast', function() {
        this.remove();
      });
    }

    // Initialize tooltips if using Bootstrap 5
    if (typeof bootstrap !== 'undefined') {
      const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
    }
  });
</script>

<?php echo $this->include('admin/footer') ?>
<?php echo $this->endSection(); ?>