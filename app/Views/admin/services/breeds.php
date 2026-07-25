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
    <div class="container-fluid p-0 mb-4 bg-gradient-primary rounded-3">
      <div class="text-white p-5 shadow position-relative overflow-hidden">
        <div class="position-relative z-1">
          <h1 class="h2 fw-bold mb-2 text-white">Breeds Management</h1>
          <p class="text-white-75 mb-0">Manage pet breeds across different pet types</p>
        </div>
        <i class="bi bi-tags position-absolute end-0 bottom-0 opacity-10"
          style="font-size: 12rem; transform: rotate(12deg); margin-right: -2rem; margin-bottom: -2rem;"></i>
      </div>
      <div class="p-3">
        <div class="card border-0 shadow-sm mb-0">
          <div class="card-body p-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
              <!-- Search -->
              <div class="input-group flex-grow-1 flex-md-grow-0">
                <span class="input-group-text bg-light border-end-0">
                  <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" class="form-control border-start-0 ps-0" 
                       placeholder="Search breeds..." 
                       id="searchInput">
              </div>

              <!-- Status Filter and Add Button -->
              <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                  <label class="form-label text-muted mb-0 small">Pet Type:</label>
                  <select class="form-select form-select-sm border-0 bg-light" 
                          id="petTypeFilter" 
                          style="width: 140px;">
                    <option value="">All Types</option>
                    <?php foreach ($petTypes as $petType) { ?>
                      <option value="<?= htmlspecialchars($petType->name) ?>"><?= htmlspecialchars($petType->name) ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <label class="form-label text-muted mb-0 small">Status:</label>
                  <select class="form-select form-select-sm border-0 bg-light" 
                          id="statusFilter" 
                          style="width: 120px;">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                  </select>
                </div>
                <button class="btn bg-primary text-white d-flex align-items-center justify-content-center gap-2 add"
                        data-crud-id="0" 
                        title="Create Breed">
                  <i class="bi bi-plus-lg"></i>
                  <span class="d-none d-md-inline">New</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions Card -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-body">
        <h6 class="card-title mb-3 text-muted fw-semibold">QUICK ACTIONS</h6>
        <form id="quick-action-form" class="row g-3 align-items-end">
          <input type="hidden" name="_token" value="WY9UR433BdtOHpyqGYdQZivZoEc7vdNGeFkSyjI5">
          
          <div class="col-md-3">
            <label for="quick-action-type" class="form-label small text-muted">Action</label>
            <select name="action_type" class="form-select" id="quick-action-type">
              <option value="">No Action</option>
              <option value="change-status">Change Status</option>
              <option value="delete">Delete</option>
            </select>
          </div>

          <div class="col-md-3 d-none" id="change-status-action">
            <label for="status" class="form-label small text-muted">Status</label>
            <select name="status" class="form-select" id="status">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary w-100" id="quick-action-apply" disabled="disabled">
              <i class="bi bi-check-lg me-1"></i>
              Apply
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
      <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                   style="width: 48px; height: 48px;">
                <i class="bi bi-list-nested fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Total Breeds</p>
                <h3 class="mb-0"><?= count($breeds) ?></h3>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center"
                   style="width: 48px; height: 48px;">
                <i class="bi bi-check-circle fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Active</p>
                <h3 class="mb-0">
                  <?= count(array_filter($breeds, function($breed) { return $breed->status == '1'; })) ?>
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center"
                   style="width: 48px; height: 48px;">
                <i class="bi bi-pause-circle fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Inactive</p>
                <h3 class="mb-0">
                  <?= count(array_filter($breeds, function($breed) { return $breed->status == '0'; })) ?>
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center"
                   style="width: 48px; height: 48px;">
                <i class="bi bi-grid-3x3-gap fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Pet Types</p>
                <h3 class="mb-0"><?= count($petTypes) ?></h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Breeds Table -->
    <div class="card border-0 shadow-sm overflow-hidden">
      <div class="table-responsive">
        <table class="table table-hover mb-0" id="breedsTable">
          <thead class="" style="background-color: #c6d2ff!important;">
            <tr>
              <th width="80" class="ps-4 py-3 text-black fs-6 fw-semibold border-bottom" style="background: #c6d2ff!important;">
                ID
              </th>
              <th width="60" class="py-3 text-black fs-6 fw-semibold border-bottom text-center" style="background: #c6d2ff!important;">
                <input type="checkbox" class="form-check-input" 
                       name="select_all_table" id="select-all-table"
                       onclick="selectAllTable(this)">
              </th>
              <th width="80" class="py-3 text-black fs-6 fw-semibold border-bottom" style="background: #c6d2ff!important;">
                S.No.
              </th>
              <th class="py-3 text-black fs-6 fw-semibold border-bottom" style="background: #c6d2ff!important;">
                Pet Type
              </th>
              <th class="py-3 text-black fs-6 fw-semibold border-bottom" style="background: #c6d2ff!important;">
                Breed
              </th>
              <th width="120" class="py-3 text-black fs-6 fw-semibold border-bottom text-center" style="background: #c6d2ff!important;">
                Status
              </th>
              <th width="150" class="pe-4 py-3 text-black fs-6 fw-semibold border-bottom text-center" style="background: #c6d2ff!important;">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="border-top-0">
            <?php $i = 1; foreach ($breeds as $breed) { ?>
              <tr class="breed-row" 
                  data-status="<?= $breed->status == '1' ? 'active' : 'inactive' ?>"
                  data-pet-type="<?= htmlspecialchars($breed->name ?? '') ?>">
                <td class="ps-4 py-3 fw-medium"><?= htmlspecialchars($breed->id) ?></td>
                <td class="py-3 text-center">
                  <input type="checkbox" class="form-check-input select-table-row"
                         id="datatable-row-<?= $breed->id ?>" 
                         name="datatable_ids[]" 
                         value="<?= $breed->id ?>"
                         onclick="dataTableRowCheck(<?= $breed->id ?>)">
                </td>
                <td class="py-3 fw-medium"><?= $i ?></td>
                <td class="py-3">
                  <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                         style="width: 32px; height: 32px; border: 1px solid #e5dbff;">
                      <?= strtoupper(substr($breed->name ?? 'P', 0, 1)) ?>
                    </div>
                    <span class="fw-medium"><?= htmlspecialchars($breed->name ?? '--') ?></span>
                  </div>
                </td>
                <td class="py-3">
                  <div class="fw-bold text-dark"><?= htmlspecialchars($breed->label) ?></div>
                </td>
                <td class="py-3 text-center">
                  <div class="form-check form-switch d-inline-block">
                    <input type="checkbox"
                           data-token=""
                           data-url="<?= base_url() ?>updateStatus"
                           data-module="dog_breeds"
                           class="switch-status-change form-check-input status"
                           id="datatable-row-<?= $breed->id; ?>" 
                           name="status"
                           data-id="<?= $breed->id; ?>"
                           value="<?= $breed->status; ?>" 
                           <?= $breed->status == '1' ? 'checked' : ''; ?>>
                  </div>
                </td>
                <td class="pe-4 py-3 text-center">
                  <div class="d-flex justify-content-center gap-2">
                    <button type="button" 
                            class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1"
                            data-bs-toggle="offcanvas" 
                            data-bs-target="#form-offcanvas"
                            aria-label="Edit"
                            data-edit="<?= $breed->id; ?>"
                            data-module="dog_breeds"
                            data-url="<?= base_url() ?>getUser"
                            data-id="<?= $breed->id; ?>">
                      <i class="bi bi-pencil"></i>
                      <span class="d-none d-md-inline">Edit</span>
                    </button>
                    <a href="<?= base_url() ?>deleteUser" 
                       id="delete-employees-<?= $breed->id; ?>" 
                       class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1"
                       data-type="ajax" 
                       data-method="DELETE"
                       data-token="<?= csrf_hash(); ?>"
                       data-id="<?= $breed->id; ?>"
                       data-confirm="Are you certain you want to delete it?"
                       aria-label="Delete">
                      <i class="bi bi-trash"></i>
                      <span class="d-none d-md-inline">Delete</span>
                    </a>
                  </div>
                </td>
              </tr>
            <?php $i++; } ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 px-2 gap-3">
      <div class="text-muted small" id="pageInfo">
        Showing <?= count($breeds) ?> entries
      </div>
      <div class="btn-group">
        <button class="btn btn-outline-primary btn-sm">Previous</button>
        <button class="btn btn-primary btn-sm">1</button>
        <button class="btn btn-outline-primary btn-sm">Next</button>
      </div>
    </div>
  </div>

  <!-- Add Breed Popup -->
  <div class="popup" id="addpopup">
    <div class="popup-content border-0 shadow rounded-3">
      <div class="popup-header bg-primary text-white p-4 rounded-top-3">
        <h5 class="mb-0">Add New Breed</h5>
        <img src="<?= base_url() ?>public/assets/images/close.png" alt="Close" class="close-icon" />
      </div>
      <div class="popup-body p-4">
        <form id="addonForm" action="<?= base_url('addBreed') ?>">
          <div class="mb-4">
            <label class="form-label fw-medium text-muted mb-2" for="pet_type_id">Pet Type</label>
            <select class="form-select" id="pet_type_id" name="pet_type_id" required>
              <option value="">Select Pet Type</option>
              <?php foreach ($petTypes as $petType) { ?>
                <option value="<?= $petType->id ?>"><?= htmlspecialchars($petType->name) ?></option>
              <?php } ?>
            </select>
          </div>

          <div class="mb-4">
            <label class="form-label fw-medium text-muted mb-2" for="breed">Breed Name</label>
            <input type="text" class="form-control" id="breed" name="breed" placeholder="Enter breed name" required>
          </div>

          <div class="mb-4">
            <label class="form-label fw-medium text-muted mb-2" for="status">Status</label>
            <select class="form-select" id="status" name="status" required>
              <option value="">Select Status</option>
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center gap-2">
              <i class="bi bi-check-lg"></i>
              Save Breed
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Form Offcanvas -->
  <form action="<?= base_url() ?>update" id="updateUser">
    <div class="offcanvas offcanvas-end border-0 shadow" tabindex="-1" id="form-offcanvas"
         aria-labelledby="form-offcanvasLabel" aria-modal="true" role="dialog" style="width: 50%;">
      <div class="offcanvas-header bg-primary text-white">
        <h5 class="offcanvas-title" id="form-offcanvasLabel">Edit Breed</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body p-4">
        <div class="row g-4">
          <div class="col-md-6">
            <label for="name" class="form-label fw-medium text-muted mb-2">Name <span class="text-danger">*</span></label>
            <input type="hidden" name="id" value="" id="id">
            <input type="hidden" name="module" value="service_providers" id="module">
            <input type="text" class="form-control" name="name" id="name" placeholder="Enter name">
          </div>

          <div class="col-md-6">
            <label for="email" class="form-label fw-medium text-muted mb-2">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Enter email">
          </div>

          <div class="col-md-6">
            <label for="phone" class="form-label fw-medium text-muted mb-2">Phone Number <span class="text-danger">*</span></label>
            <input type="tel" class="form-control" name="phone" id="phone" placeholder="Enter phone number">
          </div>

          <div class="col-md-6">
            <label for="dob" class="form-label fw-medium text-muted mb-2">DOB<span class="text-danger">*</span></label>
            <input type="date" class="form-control" name="dob" id="dob" placeholder="Enter date of birth">
          </div>

          <div class="col-12">
            <label class="form-label fw-medium text-muted mb-2">Gender</label>
            <div class="d-flex gap-3">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" id="male" value="Male">
                <label class="form-check-label" for="male">Male</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" id="female" value="Female">
                <label class="form-check-label" for="female">Female</label>
              </div>
            </div>
          </div>

          <div class="col-12">
            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded">
              <label for="category-status" class="form-label fw-medium text-muted mb-0">Status</label>
              <div class="form-check form-switch">
                <input class="form-check-input" name="status" type="checkbox" id="category-status">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="offcanvas-footer border-top p-4">
        <div class="d-grid d-md-flex gap-3">
          <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-check-lg"></i>
            Save Changes
          </button>
          <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-2" data-bs-dismiss="offcanvas">
            <i class="bi bi-x-lg"></i>
            Cancel
          </button>
        </div>
      </div>
    </div>
  </form>

  <?= $this->include('admin/footer') ?>
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

  .form-check-input:checked {
    background-color: #198754 !important;
    border-color: #198754 !important;
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

  /* Popup Styles */
  .popup {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
    z-index: 1050;
  }

  .popup-content {
    background: white;
    width: 90%;
    max-width: 500px;
    position: relative;
  }

  .popup-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .close-icon {
    width: 24px;
    height: 24px;
    cursor: pointer;
  }

  /* Offcanvas Styles */
  .offcanvas {
    border-radius: 16px 0 0 16px !important;
  }

  .offcanvas-header {
    border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
  }

  .offcanvas-footer {
    border-top: 1px solid #dee2e6 !important;
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
      min-width: 700px;
    }

    .table td,
    .table th {
      padding: 0.75rem;
    }

    .row.g-4 .col-md-3 {
      margin-bottom: 1rem;
    }

    .btn-group .btn {
      padding: 0.5rem 0.75rem;
      font-size: 0.875rem;
    }
    
    .offcanvas {
      width: 100% !important;
    }
  }

  @media (max-width: 576px) {
    .table {
      min-width: 600px;
    }

    .btn {
      padding: 0.375rem 0.75rem;
      font-size: 0.875rem;
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

  /* Summary card hover effects */
  .card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease;
  }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    // Add button click - open popup
    $('.add').on('click', function() {
      $('#addpopup').css('display', 'flex');
    });

    // Close popup on clicking the close icon
    $('.close-icon').on('click', function() {
      $('#addpopup').hide();
    });

    // Close popup on clicking outside of popup content
    $(document).on('click', '#addpopup', function(e) {
      if (!$(e.target).closest('.popup-content').length) {
        $('#addpopup').hide();
      }
    });

    // Search functionality
    $('#searchInput').on('input', function() {
      const searchTerm = $(this).val().toLowerCase();
      $('.breed-row').each(function() {
        const text = $(this).text().toLowerCase();
        $(this).toggle(text.includes(searchTerm));
      });
    });

    // Pet Type filter
    $('#petTypeFilter').on('change', function() {
      filterTable();
    });

    // Status filter
    $('#statusFilter').on('change', function() {
      filterTable();
    });

    function filterTable() {
      const petType = $('#petTypeFilter').val();
      const status = $('#statusFilter').val();
      
      $('.breed-row').each(function() {
        const rowPetType = $(this).data('pet-type');
        const rowStatus = $(this).data('status');
        
        let petTypeMatch = !petType || rowPetType === petType;
        let statusMatch = !status || rowStatus === status;
        
        $(this).toggle(petTypeMatch && statusMatch);
      });
    }
  });

  // Original bulk action functionality preserved
  function selectAllTable(checkbox) {
    const rowCheckboxes = document.querySelectorAll('.select-table-row');
    rowCheckboxes.forEach(cb => cb.checked = checkbox.checked);
    toggleApplyButton();
  }

  function dataTableRowCheck(id) {
    toggleApplyButton();
  }

  function toggleApplyButton() {
    const selectedRows = document.querySelectorAll('.select-table-row:checked');
    const applyButton = document.getElementById('quick-action-apply');
    applyButton.disabled = selectedRows.length === 0;
  }

  document.getElementById('quick-action-apply').addEventListener('click', function() {
    const selectedAction = document.getElementById('quick-action-type').value;
    const selectedStatus = document.getElementById('status').value;
    const selectedRows = document.querySelectorAll('.select-table-row:checked');

    if (selectedAction === 'delete') {
      selectedRows.forEach(row => {
        const rowId = row.value;
        const deleteLink = document.getElementById(`delete-employees-${rowId}`);
        if (deleteLink) {
          if (confirm('Are you certain you want to delete it?')) {
            deleteLink.click();
          }
        }
      });
    } else if (selectedAction === 'change-status') {
      selectedRows.forEach(row => {
        const statusCell = row.closest('tr').querySelector('.switch-status-change');
        if (statusCell) {
          statusCell.checked = selectedStatus == '1';
          // Trigger change event to update via AJAX
          $(statusCell).trigger('change');
        }
      });
    }
  });

  document.querySelectorAll('.select-table-row').forEach(checkbox => {
    checkbox.addEventListener('change', toggleApplyButton);
  });

  document.getElementById('quick-action-type').addEventListener('change', function() {
    const statusActionDiv = document.getElementById('change-status-action');
    if (this.value === 'change-status') {
      statusActionDiv.classList.remove('d-none');
    } else {
      statusActionDiv.classList.add('d-none');
    }
    toggleApplyButton();
  });

  // Status switch change (assuming this functionality exists elsewhere)
  $(document).on('change', '.switch-status-change', function() {
    const url = $(this).data('url');
    const module = $(this).data('module');
    const id = $(this).data('id');
    const status = this.checked ? '1' : '0';
    
    // AJAX call to update status
    $.ajax({
      url: url,
      method: 'POST',
      data: {
        id: id,
        status: status,
        module: module
      },
      success: function(response) {
        if (response.status) {
          console.log('Status updated successfully');
        }
      }
    });
  });

  // Edit button functionality (assuming this exists elsewhere)
  $(document).on('click', 'button[data-edit]', function() {
    const id = $(this).data('id');
    const module = $(this).data('module');
    const url = $(this).data('url');
    
    // AJAX call to get data and populate form
    $.ajax({
      url: url,
      method: 'POST',
      data: {
        id: id,
        module: module
      },
      success: function(response) {
        if (response.status) {
          // Populate form fields
          $('#id').val(response.data.id);
          $('#name').val(response.data.name);
          // ... populate other fields
          
          // Show offcanvas
          const offcanvas = new bootstrap.Offcanvas(document.getElementById('form-offcanvas'));
          offcanvas.show();
        }
      }
    });
  });
</script>

<?= $this->endSection() ?>