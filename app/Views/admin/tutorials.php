<?= $this->extend('admin/includes/header'); ?>
<!--start content-->


<?= $this->section('content'); ?>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?= $this->include('admin/nav_bar') ?>
  </div>

  <div class="conatiner-fluid content-inner mt-n5 py-0" id="page_layout">
    <!-- Modern Header Section -->
    <div class="container-fluid p-0 mb-4">
      <div class="bg-gradient-primary text-white p-5 rounded-4 shadow-lg position-relative overflow-hidden">
        <div class="position-relative z-1">
          <h1 class="display-6 fw-bold mb-3 text-white">Tutorials Management</h1>
          <p class="text-white-75 mb-0 fs-5 opacity-90">Manage video tutorials for pet owners and service providers</p>
        </div>
        <i class="bi bi-play-circle position-absolute end-0 bottom-0 opacity-10"
          style="font-size: 14rem; transform: rotate(12deg); margin-right: -3rem; margin-bottom: -3rem;"></i>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-12">
        <div class="card modern-card">
          <div class="card-body">
            <!-- Add Tutorial Button -->
            <div class="d-flex justify-content-end mb-4">
              <button class="btn-modern-primary" data-bs-toggle="modal" data-bs-target="#addTutorialModal">
                <i class="bi bi-plus-circle me-2"></i>Add Tutorial
              </button>
            </div>

            <!-- Main Tabs -->
            <ul class="nav nav-tabs-modern" id="mainTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="pet-owners-tab" data-bs-toggle="tab" href="#pet-owners" role="tab">
                  <i class="bi bi-people me-2"></i>Pet Owners
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="service-providers-tab" data-bs-toggle="tab" href="#service-providers"
                  role="tab">
                  <i class="bi bi-briefcase me-2"></i>Service Providers
                </a>
              </li>
            </ul>

            <div class="tab-content mt-4" id="mainTabContent">
              <!-- PET OWNERS -->
              <div class="tab-pane fade show active" id="pet-owners" role="tabpanel">
                <ul class="nav nav-pills-modern" id="petOwnerSubTabs" role="tablist">
                  <?php foreach (['walking', 'grooming', 'training', 'boarding'] as $cat): ?>
                    <li class="nav-item">
                      <a class="nav-link<?= $cat == 'walking' ? ' active' : '' ?>" id="po-<?= $cat ?>-tab"
                        data-bs-toggle="tab" href="#po-<?= $cat ?>" role="tab">
                        <i
                          class="bi <?= $cat == 'walking' ? 'bi-shuffle' : ($cat == 'grooming' ? 'bi-scissors' : ($cat == 'training' ? 'bi-award' : 'bi-house-door')) ?> me-2"></i>
                        <?= ucfirst($cat) ?>
                      </a>
                    </li>
                  <?php endforeach; ?>
                </ul>

                <div class="tab-content" id="petOwnerSubTabsContent">
                  <?php foreach (['walking', 'grooming', 'training', 'boarding'] as $cat): ?>
                    <div class="tab-pane fade<?= $cat == 'walking' ? ' show active' : '' ?>" id="po-<?= $cat ?>"
                      role="tabpanel">
                      <div class="table-responsive">
                        <table class="table table-modern table-hover" id="po-<?= $cat ?>-table">
                          <?php
                          $filteredTutorials = array_filter($tutorials, function ($t) use ($cat) {
                            return $t->user_type === 'pet_owner' && $t->service_type === $cat;
                          });
                          include '_tutorials_table.php';
                          ?>
                        </table>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>

              <!-- SERVICE PROVIDERS -->
              <div class="tab-pane fade" id="service-providers" role="tabpanel">
                <ul class="nav nav-pills-modern" id="providerSubTabs" role="tablist">
                  <?php foreach (['walking', 'grooming', 'training', 'boarding'] as $cat): ?>
                    <li class="nav-item">
                      <a class="nav-link<?= $cat == 'walking' ? ' active' : '' ?>" id="sp-<?= $cat ?>-tab"
                        data-bs-toggle="tab" href="#sp-<?= $cat ?>" role="tab">
                        <i
                          class="bi <?= $cat == 'walking' ? 'bi-shuffle' : ($cat == 'grooming' ? 'bi-scissors' : ($cat == 'training' ? 'bi-award' : 'bi-house-door')) ?> me-2"></i>
                        <?= ucfirst($cat) ?>
                      </a>
                    </li>
                  <?php endforeach; ?>
                </ul>

                <div class="tab-content" id="providerSubTabsContent">
                  <?php foreach (['walking', 'grooming', 'training', 'boarding'] as $cat): ?>
                    <div class="tab-pane fade<?= $cat == 'walking' ? ' show active' : '' ?>" id="sp-<?= $cat ?>"
                      role="tabpanel">
                      <div class="table-responsive">
                        <table class="table table-modern table-hover" id="sp-<?= $cat ?>-table">
                          <?php
                          $filteredTutorials = array_filter($tutorials, function ($t) use ($cat) {
                            return $t->user_type === 'service_provider' && $t->service_type === $cat;
                          });
                          include '_tutorials_table.php';
                          ?>
                        </table>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Add/Edit Tutorial Modal -->
  <!-- Add/Edit Tutorial Modal -->
  <div class="modal fade modal-modern" id="addTutorialModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <form action="<?= site_url('tutorials/saveTutorial') ?>" method="POST" enctype="multipart/form-data"
        class="modal-content p-0" style="max-height: 90vh;">
        <input type="hidden" name="id" id="tutorial_id">

        <div class="modal-header">
          <h5 class="modal-title" id="addTutorialModalLabel">
            <i class="bi bi-plus-circle me-2"></i>Add Tutorial
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body p-4" style="overflow-y: auto; max-height: calc(90vh - 130px);">
          <div class="mb-3">
            <label for="user_type" class="form-label-modern">User Type</label>
            <select class="form-control-modern" name="user_type" id="user_type" required>
              <option value="">-- Select User Type --</option>
              <option value="pet_owner">Pet Owner</option>
              <option value="service_provider">Service Provider</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="service_type" class="form-label-modern">Service Type</label>
            <select class="form-control-modern" name="service_type" id="service_type" required>
              <option value="">-- Select Service --</option>
              <option value="walking">Walking</option>
              <option value="grooming">Grooming</option>
              <option value="training">Training</option>
              <option value="boarding">Boarding</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="title" class="form-label-modern">Title</label>
            <input type="text" class="form-control-modern" name="title" id="title"
              placeholder="e.g., How to walk your dog" required>
          </div>

          <div class="mb-3">
            <label for="thumbnail" class="form-label-modern">Thumbnail Image</label>
            <input type="file" class="form-control-modern" name="thumbnail" id="thumbnail" accept="image/*">
            <small class="text-muted">Recommended size: 1280x720px</small>
          </div>

          <div class="mb-3">
            <label for="link" class="form-label-modern">Tutorial Link</label>
            <input type="url" class="form-control-modern" name="link" id="link"
              placeholder="https://youtube.com/watch?v=..." required>
          </div>

          <div class="mb-3">
            <label for="description" class="form-label-modern">Description</label>
            <textarea class="form-control-modern" name="description" id="description" rows="4"
              placeholder="Brief description of the tutorial..." required></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn-modern-outline" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-2"></i>Cancel
          </button>
          <button type="submit" class="btn-modern-primary">
            <i class="bi bi-check2-circle me-2"></i>Save Tutorial
          </button>
        </div>
      </form>
    </div>
  </div>

  <?= $this->include('admin/footer') ?>
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

  /* Additional Modal Styles for Perfect Centering */
  .modal.show {
    display: flex !important;
    align-items: center;
    justify-content: center;
  }

  .modal-dialog-centered {
    display: flex;
    align-items: center;
    min-height: calc(100% - 1rem);
    margin: 0 auto;
  }

  .modal-dialog-scrollable {
    max-height: calc(100% - 1rem);
    margin: 0 auto;
  }

  .modal-dialog-scrollable .modal-content {
    max-height: 90vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
  }

  .modal-dialog-scrollable .modal-body {
    overflow-y: auto;
    flex: 1 1 auto;
  }

  /* Ensure body doesn't scroll when modal is open */
  body.modal-open {
    overflow: hidden;
    padding-right: 0 !important;
  }

  /* Custom scrollbar for modal body */
  .modal-dialog-scrollable .modal-body::-webkit-scrollbar {
    width: 6px;
  }

  .modal-dialog-scrollable .modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
  }

  .modal-dialog-scrollable .modal-body::-webkit-scrollbar-thumb {
    background: var(--primary-light);
    border-radius: 10px;
  }

  .modal-dialog-scrollable .modal-body::-webkit-scrollbar-thumb:hover {
    background: var(--primary-main);
  }

  /* Animation for modal */
  .modal.fade .modal-dialog {
    transform: scale(0.95);
    transition: transform 0.2s ease-out;
  }

  .modal.show .modal-dialog {
    transform: scale(1);
  }

  /* Ensure modal backdrop covers everything */
  .modal-backdrop {
    background-color: rgba(0, 0, 0, 0.5) !important;
  }

  /* Responsive adjustments */
  @media (max-width: 576px) {
    .modal-dialog-centered {
      min-height: calc(100% - 0.5rem);
      margin: 0.25rem;
    }

    .modal-dialog-scrollable .modal-content {
      max-height: 95vh;
    }

    .modal-dialog-scrollable .modal-body {
      max-height: calc(95vh - 130px);
    }
  }

  /* Modern Card Styles */
  .modern-card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
    transition: all 0.3s ease;
  }

  .modern-card:hover {
    box-shadow: 0 8px 30px rgba(63, 48, 227, 0.08);
  }

  /* Table Styling */
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

  /* Tab Styling */
  .nav-tabs-modern {
    border-bottom: 2px solid var(--primary-light);
    margin-bottom: 1.5rem;
  }

  .nav-tabs-modern .nav-link {
    border: none;
    color: #6c757d;
    font-weight: 500;
    padding: 0.75rem 1.5rem;
    margin-right: 0.5rem;
    border-radius: 40px 40px 0 0;
    transition: all 0.3s ease;
  }

  .nav-tabs-modern .nav-link:hover {
    color: var(--primary-main);
    background: var(--secondary-light);
  }

  .nav-tabs-modern .nav-link.active {
    color: var(--primary-dark);
    background: var(--primary-light);
    font-weight: 600;
  }

  /* Pills Styling */
  .nav-pills-modern {
    gap: 0.5rem;
    margin-bottom: 1.5rem;
  }

  .nav-pills-modern .nav-link {
    border-radius: 40px;
    padding: 0.5rem 1.25rem;
    color: #6c757d;
    font-weight: 500;
    background: white;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
  }

  .nav-pills-modern .nav-link:hover {
    border-color: var(--primary-main);
    color: var(--primary-main);
  }

  .nav-pills-modern .nav-link.active {
    background: var(--primary-gradient);
    color: white;
    border-color: transparent;
  }

  /* Button Styles */
  .btn-modern-primary {
    background: var(--primary-gradient);
    color: white;
    border: none;
    padding: 0.6rem 1.5rem;
    border-radius: 40px;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  .btn-modern-primary:hover {
    background: linear-gradient(135deg, #2b1fb3 0%, #3f30e3 100%);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 6px 15px rgba(63, 48, 227, 0.3);
  }

  .btn-modern-outline {
    background: white;
    border: 1px solid #e9ecef;
    color: #495057;
    padding: 0.5rem 1.25rem;
    border-radius: 40px;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  .btn-modern-outline:hover {
    border-color: var(--primary-main);
    color: var(--primary-main);
    background: var(--secondary-light);
  }

  /* Action Buttons */
  .action-btn {
    width: 36px;
    height: 36px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    transition: all 0.2s ease;
    border: none;
    margin: 0 2px;
  }

  .action-btn-edit {
    background: var(--info-light);
    color: var(--info-text);
    border: 1px solid var(--info-border);
  }

  .action-btn-edit:hover {
    background: var(--info-text);
    color: white;
  }

  .action-btn-delete {
    background: var(--danger-light);
    color: var(--danger-text);
    border: 1px solid var(--danger-border);
  }

  .action-btn-delete:hover {
    background: var(--danger-text);
    color: white;
  }

  /* Thumbnail Image */
  .thumbnail-img {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    object-fit: cover;
    border: 2px solid var(--primary-light);
    transition: all 0.3s ease;
  }

  .thumbnail-img:hover {
    transform: scale(1.05);
    border-color: var(--primary-main);
  }

  /* Section Header */
  .section-header {
    background: var(--primary-light);
    padding: 0.75rem 1rem;
    border-radius: 12px;
    color: var(--primary-dark);
    font-weight: 600;
    font-size: 0.95rem;
    letter-spacing: 0.02em;
    margin-bottom: 1rem;
  }

  /* Modal Styling */
  .modal-modern .modal-content {
    border-radius: 24px;
    border: none;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  }

  .modal-modern .modal-header {
    background: var(--primary-light);
    border-bottom: 2px solid var(--primary-main);
    padding: 1.5rem;
    border-radius: 24px 24px 0 0;
  }

  .modal-modern .modal-title {
    color: var(--primary-dark);
    font-weight: 700;
    font-size: 1.35rem;
  }

  .modal-modern .modal-footer {
    border-top: 1px solid #e9ecef;
    padding: 1.25rem 1.5rem;
  }

  /* Form Controls */
  .form-control-modern {
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 0.6rem 1rem;
    transition: all 0.3s ease;
    width: 100%;
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
  }

  /* DataTables Customization */
  .dataTables_wrapper .dataTables_length,
  .dataTables_wrapper .dataTables_filter,
  .dataTables_wrapper .dataTables_info,
  .dataTables_wrapper .dataTables_paginate {
    padding: 0.5rem 1rem;
  }

  .dataTables_wrapper .dataTables_filter input {
    border: 1px solid #e9ecef;
    border-radius: 40px;
    padding: 0.4rem 1rem;
    margin-left: 0.5rem;
  }

  .dataTables_wrapper .dataTables_filter input:focus {
    border-color: var(--primary-main);
    box-shadow: 0 0 0 3px rgba(63, 48, 227, 0.1);
    outline: none;
  }

  .dataTables_wrapper .dataTables_length select {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 0.3rem 1.5rem 0.3rem 0.5rem;
  }

  .dataTables_paginate .paginate_button {
    border-radius: 8px !important;
    margin: 0 3px !important;
  }

  .dataTables_paginate .paginate_button.current {
    background: var(--primary-gradient) !important;
    color: white !important;
    border: none !important;
  }

  /* Responsive Design */
  @media (max-width: 768px) {
    .nav-tabs-modern .nav-link {
      padding: 0.5rem 1rem;
      font-size: 0.9rem;
    }

    .nav-pills-modern .nav-link {
      padding: 0.4rem 1rem;
      font-size: 0.85rem;
    }

    .table-modern thead th {
      font-size: 0.75rem;
      padding: 0.75rem !important;
    }

    .action-btn {
      width: 32px;
      height: 32px;
    }

    .thumbnail-img {
      width: 40px;
      height: 40px;
    }
  }

  @media (max-width: 576px) {
    .btn-modern-primary {
      padding: 0.4rem 1rem;
      font-size: 0.85rem;
    }

    .modal-modern .modal-content {
      margin: 1rem;
    }
  }

  /* Utility Classes */
  .bg-primary-light {
    background: var(--primary-light) !important;
  }

  .text-primary-dark {
    color: var(--primary-dark) !important;
  }

  .border-primary-light {
    border-color: var(--primary-light) !important;
  }

  .bg-gradient-primary {
    background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Initialize DataTables for all tables
    <?php foreach (['walking', 'grooming', 'training', 'boarding'] as $cat): ?>
      <?php foreach (['po', 'sp'] as $type): ?>
        if ($.fn.DataTable.isDataTable('#<?= $type ?>-<?= $cat ?>-table')) {
          $('#<?= $type ?>-<?= $cat ?>-table').DataTable().destroy();
        }
        $('#<?= $type ?>-<?= $cat ?>-table').DataTable({
          responsive: true,
          pageLength: 10,
          language: {
            search: "_INPUT_",
            searchPlaceholder: "Search tutorials...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)"
          },
          columnDefs: [
            { orderable: false, targets: [5] } // Disable ordering on actions column
          ]
        });
      <?php endforeach; ?>
    <?php endforeach; ?>

    // Edit tutorial functionality
    document.querySelectorAll('.edit-tutorial').forEach(btn => {
      btn.addEventListener('click', function (e) {
        e.preventDefault();

        // Populate form fields
        document.getElementById('tutorial_id').value = this.dataset.id;
        document.getElementById('user_type').value = this.dataset.user_type;
        document.getElementById('service_type').value = this.dataset.service_type;
        document.getElementById('title').value = this.dataset.title;
        document.getElementById('link').value = this.dataset.link;
        document.getElementById('description').value = this.dataset.description;

        // Update modal title
        document.getElementById('addTutorialModalLabel').innerHTML = '<i class="bi bi-pencil-square me-2"></i>Edit Tutorial';

        // Show modal
        new bootstrap.Modal(document.getElementById('addTutorialModal')).show();
      });
    });

    // Reset modal on close
    document.getElementById('addTutorialModal').addEventListener('hidden.bs.modal', function () {
      document.getElementById('tutorial_id').value = '';
      document.getElementById('addTutorialModalLabel').innerHTML = '<i class="bi bi-plus-circle me-2"></i>Add Tutorial';
    });

    // Delete confirmation
    document.querySelectorAll('.delete-tutorial').forEach(btn => {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete this tutorial?')) {
          window.location.href = this.href;
        }
      });
    });
  });
</script>

<?= $this->endSection() ?>