<?php echo $this->extend('admin/includes/header'); ?>
<!--start content-->
<?php echo $this->section('content'); ?>
<main class="main-content">
  <div class="position-relative iq-banner">
    <?php echo $this->include('admin/nav_bar') ?>
  </div>

  <div class="conatiner-fluid content-inner pb-0 px-4" id="page_layout">
    <!-- Modern Header Section -->
    <div class="container-fluid p-0 mb-4 bg-gradient-primary rounded-3">
      <div class="text-white p-3 shadow position-relative overflow-hidden">
        <div class="position-relative z-1">
          <h1 class="h2 fw-bold mb-2 text-white">Pets Management</h1>
          <p class="text-white-75 mb-0">Manage pet types and their breeds</p>
        </div>
        <i class="bi bi-paw position-absolute end-0 bottom-0 opacity-10"
          style="font-size: 12rem; transform: rotate(12deg); margin-right: -2rem; margin-bottom: -2rem;"></i>
      </div>
      <div class="p-3">
        <div class="card border-0 shadow-sm mb-0">
          <div class="card-body p-3">
            <div
              class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
              <!-- Search -->
              <div class="input-group flex-grow-1 flex-md-grow-0" style="max-width: 300px;">
                <span class="input-group-text bg-light border-end-0">
                  <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" class="form-control border-start-0 ps-0" placeholder="Search pets..."
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
                  data-bs-toggle="modal" data-bs-target="#addPetModal">
                  <i class="bi bi-plus-lg"></i>
                  <span class="d-none d-md-inline">New Pet</span>
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
                <i class="bi bi-paw fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Total Pets</p>
                <h3 class="mb-0"><?= count($pets) ?></h3>
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
                <p class="text-muted small mb-1">Active</p>
                <h3 class="mb-0">
                  <?= count(array_filter($pets, function ($pet) {
                    return $pet->status == '1';
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
                <p class="text-muted small mb-1">Inactive</p>
                <h3 class="mb-0">
                  <?= count(array_filter($pets, function ($pet) {
                    return $pet->status == '0';
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
                <i class="bi bi-list-nested fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Total Breeds</p>
                <h3 class="mb-0">
                  <?= array_sum(array_map(function ($pet) {
                    return $pet->totalBreeds ?? 0;
                  }, $pets)) ?>
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pets Table -->
    <div class="card border-0 shadow-sm overflow-hidden">
      <div class="table-responsive">
        <table class="table table-hover mb-0" id="petsTable">
          <thead class="" style="background-color: #c6d2ff!important;">
            <tr>
              <th width="80" class="ps-4 py-3 text-black fs-6 fw-semibold border-bottom"
                style="background: #c6d2ff!important;">
                ID
              </th>
              <th class="py-3 text-black fs-6 fw-semibold border-bottom" style="background: #c6d2ff!important;">
                Pet Type
              </th>
              <th width="150" class="py-3 text-black fs-6 fw-semibold border-bottom text-center"
                style="background: #c6d2ff!important;">
                No Of Breeds
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
            <?php foreach ($pets as $pet) { ?>
              <tr class="pet-row" data-status="<?= $pet->status == '1' ? 'active' : 'inactive' ?>">
                <td class="ps-4 py-3 fw-medium"><?= htmlspecialchars($pet->id) ?></td>
                <td class="py-3">
                  <div class="d-flex align-items-center gap-3">
                    <div
                      class="rounded-circle bg-primary bg-opacity-10 text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                      style="width: 36px; height: 36px; border: 1px solid #e5dbff;">
                      <?= strtoupper(substr($pet->name, 0, 1)) ?>
                    </div>
                    <div class="fw-bold text-dark">
                      <?= htmlspecialchars($pet->name) ?>
                    </div>
                  </div>
                </td>
                <td class="py-3 text-center">
                  <a href="<?= base_url() ?>breeds/<?= $pet->id ?>"
                    class="badge bg-primary bg-opacity-10 text-white border border-primary border-opacity-25 px-3 py-2 fw-medium text-decoration-none"
                    style="background-color: rgba(59, 130, 246, 0.1) !important;
        color: #2563eb !important;
        border: 1px solid rgba(59, 130, 246, 0.2) !important; border-radius:50px">
                    <?= $pet->totalBreeds ?? 0 ?>
                  </a>
                </td>
                <td class="py-3 text-center">
                  <div class="form-check form-switch d-inline-block">
                    <input type="checkbox" data-url="<?= base_url() ?>updateStatus" data-module="pet_types"
                      data-token="<?= csrf_hash(); ?>" class="switch-status-change form-check-input pet_status"
                      id="datatable-row-<?= $pet->id; ?>" name="status" data-id="<?= $pet->id; ?>"
                      value="<?= $pet->status; ?>" <?= $pet->status == '1' ? 'checked' : ''; ?>>
                  </div>
                </td>
                <td class="pe-4 py-3 text-center">
                  <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1 edit-pet"
                      data-bs-toggle="modal" data-bs-target="#editPetModal" aria-label="Edit" data-module="pet_types"
                      data-url="<?= base_url() ?>Pet" data-edit-pet="<?= $pet->id; ?>" data-id="<?= $pet->id; ?>">
                      <i class="bi bi-pencil"></i>
                      <span class="d-none d-md-inline">Edit</span>
                    </button>
                    <a href="<?= base_url() ?>deletePetType" id="delete-employees-<?= $pet->id; ?>"
                      class="btn btn-sm text-danger d-flex align-items-center gap-1" data-type="ajax" data-method="DELETE"
                      data-token="<?= csrf_hash(); ?>" data-bs-toggle="tooltip" data-id="<?= $pet->id; ?>"
                      data-confirm="Are you certain you want to delete it?" aria-label="Delete">
                      <i class="bi bi-trash"></i>
                      <span class="d-none d-md-inline">Delete</span>
                    </a>
                  </div>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 px-2 gap-3">
      <div class="text-muted small" id="pageInfo">
        Showing <?= count($pets) ?> entries
      </div>
      <div class="btn-group">
        <button class="btn btn-outline-primary btn-sm">Previous</button>
        <button class="btn btn-primary btn-sm">1</button>
        <button class="btn btn-outline-primary btn-sm">Next</button>
      </div>
    </div>
  </div>

  <!-- Add Pet Modal -->
  <div class="modal fade" id="addPetModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow p-0">
        <div class="modal-header bg-primary text-white border-0">
          <h5 class="modal-title text-white">Add Pet Type</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="<?= base_url() ?>addPetType" method="post" id="addPetForm">
          <div class="modal-body p-4">
            <div class="row g-3">
              <div class="col-12">
                <div class="form-group">
                  <label class="form-label fw-medium text-muted mb-2" for="add_name">Pet Type <span
                      class="text-danger">*</span></label>
                  <div class="input-group">

                    <input type="text" class="form-control" name="name" id="add_name" placeholder="Enter pet type"
                      required>
                  </div>
                  <span class="text-danger"></span>
                </div>

                <div class="form-group mt-3">
                  <label class="form-label fw-medium text-muted mb-2" for="add_status">Status <span
                      class="text-danger">*</span></label>
                  <select name="status" id="add_status" class="form-select" required>
                    <option value="">Select Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer border-top p-4">
            <div class="d-flex gap-3 w-100">
              <button type="submit"
                class="text-center btn bg-primary text-white d-flex align-items-center gap-2 flex-grow-1">
                <i class="bi bi-check-lg"></i>
                Save
              </button>
              <button type="button"
                class=" text-center btn bg-info text-white d-flex align-items-center gap-2 flex-grow-1"
                data-bs-dismiss="modal">
                <i class="bi bi-x-lg"></i>
                Cancel
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Pet Modal -->
  <div class="modal fade" id="editPetModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow p-0">
        <div class="modal-header bg-primary text-white border-0">
          <h5 class="modal-title text-white">Edit Pet Type</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="<?= base_url() ?>updatePetType" method="post" id="editPetForm">
          <div class="modal-body p-4">
            <div class="row g-4">
              <div class="col-12">
                <input type="hidden" name="id" value="" id="edit_id">
                <input type="hidden" name="module" value="pet_types" id="edit_module">

                <label class="form-label fw-medium text-muted mb-2" for="edit_pet_type">Pet Type <span
                    class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text bg-light">
                    <i class="bi bi-paw text-muted"></i>
                  </span>
                  <input type="text" class="form-control" name="name" id="edit_pet_type" placeholder="Enter pet type"
                    required>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer border-top p-4">
            <div class="d-flex gap-3 w-100">
              <button type="submit"
                class="btn bg-primary text-center text-white d-flex align-items-center gap-2 flex-grow-1">
                <i class="bi bi-check-lg"></i>
                Save Changes
              </button>
              <button type="button"
                class="text-center btn bg-info text-white d-flex align-items-center gap-2 flex-grow-1"
                data-bs-dismiss="modal">
                <i class="bi bi-x-lg"></i>
                Cancel
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

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

  /* Modal Styles */
  .modal-content {
    border-radius: 16px !important;
    border: none !important;
  }

  .modal-header {
    border-radius: 16px 16px 0 0 !important;
  }

  .modal-footer {
    border-radius: 0 0 16px 16px !important;
  }

  /* Prevent body scroll when modal is open */
  body.modal-open {
    overflow: hidden;
    padding-right: 0 !important;
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
      min-width: 600px;
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

    .modal-dialog {
      margin: 0.5rem;
    }
  }

  @media (max-width: 576px) {
    .table {
      min-width: 500px;
    }

    .btn {
      padding: 0.375rem 0.75rem;
      font-size: 0.875rem;
    }

    .modal-footer .d-flex {
      flex-direction: column;
    }

    .modal-footer .btn {
      width: 100%;
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  $(document).ready(function () {
    // Status toggle functionality
    $('.pet_status').on('change', function () {
      var status = $(this).val()
      const id = $(this).data('id')
      const url = $(this).data('url')
      const module = $(this).data('module')

      if (status == 1 || status == '1') {
        status = '0'
      } else if (status == 0 || status == '0') {
        status = '1'
      }
      $.ajax({
        type: 'POST',
        url: url,
        data: {
          id: id,
          status: status,
          module: module
        },
        success: function (data) {
          if (data.status == true) {
            Swal.fire('Success!', data.message, 'success')
            setTimeout(() => location.reload(), 1200)
          } else {
            Swal.fire('Error!', data.message, 'error')
          }
        }
      })
    })

    // Add pet form submission
    $('#addPetForm').on('submit', function (e) {
      e.preventDefault()
      const url = $(this).attr('action')
      var form = $(this)
      $.ajax({
        url: url,
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: form.serialize(),
        success: function (response) {
          if (response.status === true) {
            Swal.fire('Success!', response.message, 'success').then(() => {
              $('#addPetModal').modal('hide')
              form[0].reset()
              location.reload()
            })
          } else {
            Swal.fire('Error!', response.message, 'error')
          }
        },
        error: function (xhr, status, error) {
          console.error('Error:', error)
          Swal.fire('Error!', 'An error occurred. Please try again later.', 'error')
        }
      })
    })

    // Edit pet form submission
    $('#editPetForm').on('submit', function (e) {
      e.preventDefault()
      const url = $(this).attr('action')
      var form = $(this)
      $.ajax({
        url: url,
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: form.serialize(),
        success: function (response) {
          if (response.status === true) {
            Swal.fire('Success!', 'Updated successfully.', 'success').then(() => {
              $('#editPetModal').modal('hide')
              location.reload()
            })
          } else {
            Swal.fire('Error!', response.message, 'error')
          }
        },
        error: function (xhr, status, error) {
          console.error('Error:', error)
          Swal.fire('Error!', 'An error occurred. Please try again later.', 'error')
        }
      })
    })

    // Edit pet button click
    $(document).on('click', '.edit-pet', function () {
      const dataId = $(this).data('id')
      const moduleId = $(this).data('module')
      const url = $(this).data('url')

      $.ajax({
        url: url,
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
          id: dataId,
          module: moduleId
        },
        success: function (response) {
          if (response.status) {
            let data = response.data
            $('#edit_id').val(data.id)
            $('#edit_pet_type').val(data.name)

            $('#editPetModal').modal('show')
          } else {
            Swal.fire('Error!', 'Failed to get data.', 'error')
          }
        },
        error: function () {
          Swal.fire('Error!', 'An error occurred. Please try again later.', 'error')
        }
      })
    })

    // Delete confirmation
    $(document).on('click', 'a[data-confirm]', function (e) {
      e.preventDefault()
      const confirmMessage = $(this).data('confirm')
      const url = $(this).attr('href')
      const method = $(this).data('method')
      const token = $(this).data('token')
      const id = $(this).data('id')

      Swal.fire({
        title: 'Are you sure?',
        text: confirmMessage,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: url,
            method: method,
            headers: {
              'X-CSRF-TOKEN': token
            },
            data: { id: id },
            success: function (response) {
              if (response.status === true) {
                Swal.fire('Deleted!', response.message, 'success').then(() => {
                  location.reload()
                })
              } else {
                Swal.fire('Error!', response.message, 'error')
              }
            },
            error: function () {
              Swal.fire('Error!', 'An error occurred. Please try again later.', 'error')
            }
          })
        }
      })
    })

    // Search functionality
    $('#searchInput').on('input', function () {
      const searchTerm = $(this).val().toLowerCase()
      $('.pet-row').each(function () {
        const text = $(this).text().toLowerCase()
        $(this).toggle(text.includes(searchTerm))
      })
    })

    // Status filter functionality
    $('#statusFilter').on('change', function () {
      const status = $(this).val()
      $('.pet-row').each(function () {
        if (!status) {
          $(this).show()
          return
        }

        const rowStatus = $(this).data('status')
        $(this).toggle(rowStatus === status)
      })
    })

    // Reset add form when modal is closed
    $('#addPetModal').on('hidden.bs.modal', function () {
      $('#addPetForm')[0].reset()
    })

    // Reset edit form when modal is closed
    $('#editPetModal').on('hidden.bs.modal', function () {
      $('#editPetForm')[0].reset()
    })

    // Prevent body scroll when modal is open
    $('#addPetModal, #editPetModal').on('show.bs.modal', function () {
      $('body').addClass('modal-open')
    })

    $('#addPetModal, #editPetModal').on('hidden.bs.modal', function () {
      $('body').removeClass('modal-open')
    })
  })
</script>

<?php echo $this->endSection() ?>