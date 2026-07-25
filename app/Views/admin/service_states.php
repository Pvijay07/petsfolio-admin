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
          <h1 class="h2 fw-bold mb-2 text-white">Service Available States</h1>
          <p class="text-white-75 mb-0">Manage states where your services are available</p>
        </div>
        <i class="bi bi-globe position-absolute end-0 bottom-0 opacity-10"
          style="font-size: 12rem; transform: rotate(12deg); margin-right: -2rem; margin-bottom: -2rem;"></i>
      </div>
      <div class="p-3">
        <div class="card border-0 shadow-sm mb-0">
          <div class="card-body p-3">
            <div
              class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
              <div class="input-group flex-grow-1 flex-md-grow-0" >
                <span class="input-group-text bg-light border-end-0">
                  <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" class="form-control border-start-0 ps-0" placeholder="Search states..."
                  id="searchInput">
              </div>

              <!-- Status Filter and Add Button -->
              <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                  <label class="form-label text-muted mb-0 small">Status:</label>
                  <select class="form-select form-select-sm border-0 bg-light" id="statusFilter" style="width: 120px;">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                  </select>
                </div>
                <button class="btn bg-primary text-white d-flex align-items-center justify-content-center gap-2"
                  data-bs-toggle="modal" data-bs-target="#stateModal">
                  <i class="bi bi-plus-lg"></i>
                  <span class="d-none d-md-inline">Add</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
      <div class="col-md-4 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div
                class="rounded-circle bg-primary bg-opacity-10 text-white d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-globe fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Total States</p>
                <h3 class="mb-0"><?= count($states) ?></h3>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div
                class="rounded-circle bg-success bg-opacity-10 text-white d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-check-circle fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Active States</p>
                <h3 class="mb-0">
                  <?= count(array_filter($states, function ($state) {
                    return $state['status'] == 'active';
                  })) ?>
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div
                class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-pause-circle fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Inactive States</p>
                <h3 class="mb-0">
                  <?= count(array_filter($states, function ($state) {
                    return $state['status'] == 'inactive';
                  })) ?>
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div
                class="rounded-circle bg-info bg-opacity-10 text-white d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-percent fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Active Rate</p>
                <h3 class="mb-0">
                  <?= count($states) > 0 ? round((count(array_filter($states, function ($state) {
                    return $state['status'] == 'active'; })) / count($states)) * 100) : 0 ?>%
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- States Table -->
    <div class="card border-0 shadow-sm overflow-hidden">
      <div class="table-responsive">
        <table class="table table-hover mb-0" id="statesTable">
          <thead class="" style="background-color: #c6d2ff!important;">
            <tr>
              <th width="80" class="ps-4 py-3 text-black fs-6 fw-semibold border-bottom"
                style="background: #c6d2ff!important;">
                S.No.
              </th>
              <th class="py-3 text-black fs-6 fw-semibold border-bottom" style="background: #c6d2ff!important;">
                State Name
              </th>
              <th width="120" class="py-3 text-black fs-6 fw-semibold border-bottom text-center"
                style="background: #c6d2ff!important;">
                Status
              </th>
              <th width="150" class="pe-4 py-3 text-black fs-6 fw-semibold border-bottom text-center"
                style="background: #c6d2ff!important;">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="border-top-0">
            <?php $i = 1;
            foreach ($states as $state): ?>
              <tr class="state-row" data-status="<?= $state['status'] ?>">
                <td class="ps-4 py-2 fw-medium"><?= $i++ ?></td>
                <td class="py-2">
                  <div class="d-flex align-items-center gap-3">
                    <div
                      class="rounded-circle bg-primary bg-opacity-10 text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                      style="width: 36px; height: 36px; border: 1px solid #e5dbff;">
                      <?= strtoupper(substr($state['state_name'], 0, 1)) ?>
                    </div>
                    <div class="fw-bold text-dark">
                      <?= esc($state['state_name']) ?>
                    </div>
                  </div>
                </td>
                <td class="py-2 text-center">
                  <span class="badge border px-3 py-1" style="<?= $state['status'] == 'active'
                    ? 'background: rgba(34, 197, 94, 0.1) !important;
       color: #16a34a !important;
       border: 1px solid rgba(34, 197, 94, 0.2) !important ;width: 80px ; border-radius:50px;
       font-weight: 600;'
                    : 'background-color: rgba(251, 191, 36, 0.1) !important;
       color: #d97706 !important;
       border: 1px solid rgba(251, 191, 36, 0.2) !important ;width: 80px ; border-radius:50px;
       font-size: 0.75rem;'
                    ?>">
                    <?= ucfirst($state['status']) ?>
                  </span>

                </td>
                <td class="pe-4 py-2 text-center">
                  <div class="d-flex justify-content-center gap-2">
                    <button class="btn btn-sm btn-outline-primary editBtn d-flex align-items-center gap-1"
                      data-id="<?= $state['id'] ?>" data-name="<?= esc($state['state_name']) ?>"
                      data-status="<?= $state['status'] ?>" data-bs-toggle="modal" data-bs-target="#stateModal">
                      <i class="bi bi-pencil"></i>
                      <span class="d-none d-md-inline">Edit</span>
                    </button>
                    <a href="<?= base_url('admin/servicestates/delete/' . $state['id']) ?>"
                      class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1 text-danger"
                      onclick="return confirm('Are you sure you want to delete this state?')">
                      <i class="bi bi-trash"></i>
                      <span class="d-none d-md-inline">Delete</span>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 px-2 gap-3">
      <div class="text-muted small" id="pageInfo">
        Showing <?= count($states) ?> entries
      </div>
      <div class="btn-group">
        <button class="btn btn-outline-primary btn-sm">Previous</button>
        <button class="btn btn-primary btn-sm">1</button>
        <button class="btn btn-outline-primary btn-sm">Next</button>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="stateModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <form action="<?= base_url('servicestates/save') ?>" method="post" class="needs-validation" novalidate>
          <div class="modal-content border-0 shadow p-0">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title text-white">Add/Edit State</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
              <input type="hidden" name="id" id="state_id">

              <div class="mb-4">
                <label class="form-label fw-medium text-muted mb-2">State Name</label>
                <div class="input-group">
                  <span class="input-group-text bg-light">
                    <i class="bi bi-globe text-muted"></i>
                  </span>
                  <input type="text" name="state_name" id="state_name" class="form-control" required
                    placeholder="Enter state name">
                  <div class="invalid-feedback">
                    Please enter a state name.
                  </div>
                </div>
              </div>

              <div class="mb-4">
                <label class="form-label fw-medium text-muted mb-2">Status</label>
                <div class="input-group">
                  <span class="input-group-text bg-light">
                    <i class="bi bi-toggle-on text-muted"></i>
                  </span>
                  <select name="status" id="state_status" class="form-select" required>
                    <option value="">Select Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                  </select>
                  <div class="invalid-feedback">
                    Please select a status.
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer border-top-0 p-4">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn bg-primary text-white d-flex align-items-center gap-1">
                <i class="bi bi-check-lg me-1"></i>
                Save State
              </button>
            </div>
          </div>
        </form>
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

  .btn-outline-danger {
    border-color: #dee2e6;
    color: #6c757d;
  }

  .btn-outline-danger:hover {
    border-color: #dc3545;
    color: #dc3545;
    background-color: transparent;
  }

  /* Modal Styles */
  .modal-content {
    border-radius: 16px !important;
    overflow: hidden;
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .d-flex.justify-content-between.align-items-center {
      flex-direction: column;
      gap: 1rem;
    }

    .input-group {
      width: 100% !important;
    }

    .table-responsive {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
    }

    .table {
      min-width: 500px;
    }

    .table td,
    .table th {
      padding: 0.75rem;
    }

    .row.g-4 .col-md-4 {
      margin-bottom: 1rem;
    }

    .btn-group .btn {
      padding: 0.5rem 0.75rem;
      font-size: 0.875rem;
    }
  }

  @media (max-width: 576px) {
    .table {
      min-width: 400px;
    }

    .btn {
      padding: 0.375rem 0.75rem;
      font-size: 0.875rem;
    }
  }

  /* Utility classes */
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
  document.addEventListener('DOMContentLoaded', function () {
    // Edit button functionality
    document.querySelectorAll('.editBtn').forEach(btn => {
      btn.addEventListener('click', function () {
        document.getElementById('state_id').value = this.dataset.id;
        document.getElementById('state_name').value = this.dataset.name;
        document.getElementById('state_status').value = this.dataset.status;

        // Update modal title
        document.querySelector('#stateModal .modal-title').textContent = 'Edit State';
      });
    });

    // Reset modal for new state
    document.getElementById('stateModal').addEventListener('hidden.bs.modal', function () {
      document.getElementById('state_id').value = '';
      document.getElementById('state_name').value = '';
      document.getElementById('state_status').value = '';
      document.querySelector('#stateModal .modal-title').textContent = 'Add State';

      // Reset validation
      const forms = document.querySelectorAll('.needs-validation');
      Array.from(forms).forEach(form => {
        form.classList.remove('was-validated');
      });
    });

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function (e) {
      const searchTerm = e.target.value.toLowerCase();
      const rows = document.querySelectorAll('#statesTable tbody tr');

      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
      });
    });

    // Status filter functionality
    document.getElementById('statusFilter').addEventListener('change', function (e) {
      const status = e.target.value;
      const rows = document.querySelectorAll('#statesTable tbody tr');

      rows.forEach(row => {
        if (!status) {
          row.style.display = '';
          return;
        }

        const rowStatus = row.getAttribute('data-status');
        row.style.display = rowStatus === status ? '' : 'none';
      });
    });

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
  });
</script>

<?php echo $this->include('admin/footer') ?>
<?php echo $this->endSection(); ?>