<?php echo $this->extend('admin/includes/header'); ?>

<?php echo $this->section('content'); ?>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?php echo $this->include('admin/nav_bar') ?>
  </div>

  <div class="conatiner-fluid content-inner pb-0" id="page_layout">
    <!-- Modern Header Section -->
    <div class="container-fluid p-0 mb-4">
      <div class="bg-gradient-primary text-white p-5 rounded-3 shadow position-relative overflow-hidden">
        <div class="position-relative z-1">
          <h1 class="h2 fw-bold mb-2 text-white">Dispute Management</h1>
          <p class="text-white-75 mb-0">Review and resolve customer disputes, manage addons and packages</p>
        </div>
        <i class="bi bi-exclamation-triangle position-absolute end-0 bottom-0 opacity-10"
          style="font-size: 14rem; transform: rotate(12deg); margin-right: -3rem; margin-bottom: -3rem;"></i>
      </div>
    </div>

    <!-- Search and Filter Card -->
    <div class="card border-0 shadow-sm mb-4 rounded-3">
      <div class="card-body p-3">
        <div class="row g-3 align-items-center">
          <div class="col-md-5">
            <div class="input-group">
              <span class="input-group-text bg-black border-end-0 text-white">
                <i class="bi bi-search"></i>
              </span>
              <input type="text" class="form-control border-start-0"
                placeholder="Search disputes by customer, pet or provider..." id="searchDisputes">
            </div>
          </div>
          <div class="col-md-2">
            <select class="form-select" id="statusFilter">
              <option value="">All Status</option>
              <option value="pending">Pending</option>
              <option value="resolved">Resolved</option>
              <option value="escalated">Escalated</option>
            </select>
          </div>
          <div class="col-md-2">
            <select class="form-select" id="paymentFilter">
              <option value="">Payment Status</option>
              <option value="paid">Paid</option>
              <option value="pending">Pending</option>
              <option value="failed">Failed</option>
            </select>
          </div>
          <div class="col-md-3">
            <button class="btn btn-black bg-black text-white me-2" onclick="applyFilters()">
              <i class="bi bi-funnel me-1"></i>Apply Filters
            </button>
            <button class="btn btn-outline-secondary" onclick="clearFilters()">
              <i class="bi bi-x-circle"></i>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Card -->
    <div class="card border-0 shadow-sm overflow-hidden rounded-3">
      <div class="table-responsive">
        <table class="table table-hover mb-0" id="datatable">
          <thead>
            <tr>
              <th>Booking ID</th>
              <th>Customer</th>
              <th>Date</th>
              <th>Pet</th>
              <th>Provider</th>
              <th>Status</th>
              <th>Payment</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i = 1;
            foreach ($disputes as $dispute) { ?>
              <tr class="dispute-row">
                <td>
                  <span class="fw-semibold text-primary">#<?php echo $dispute->booking_id ?></span>
                </td>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="customer-avatar">
                      <?php echo strtoupper(substr($dispute->client_name ?? '?', 0, 1)) ?>
                    </div>
                    <div>
                      <div class="fw-semibold"><?php echo $dispute->client_name; ?></div>
                      <small class="text-muted">ID: <?php echo $dispute->user_id ?? '--' ?></small>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="d-flex align-items-center">
                    <i class="bi bi-calendar3 me-2 text-primary"></i>
                    <?php echo date('d M Y', strtotime($dispute->service_start_date)); ?>
                  </div>
                </td>
                <td>
                  <div class="d-flex align-items-center">
                    <i class="bi bi-github me-2 text-primary"></i>
                    <span class="badge bg-light text-dark border">
                      <?php echo $dispute->pet_name; ?>
                    </span>
                  </div>
                </td>
                <td>
                  <div class="d-flex align-items-center">
                    <i class="bi bi-person-badge me-2 text-primary"></i>
                    <?php echo $dispute->provider_name; ?>
                  </div>
                </td>
                <td>
                  <?php
                  $statusClass = 'pending';
                  if (isset($dispute->status)) {
                    if ($dispute->status == 'resolved')
                      $statusClass = 'resolved';
                    if ($dispute->status == 'escalated')
                      $statusClass = 'escalated';
                  }
                  ?>
                  <span class="status-badge <?php echo $statusClass; ?>">
                    <i class="bi 
                      <?php
                      if ($statusClass == 'pending')
                        echo 'bi-clock';
                      elseif ($statusClass == 'resolved')
                        echo 'bi-check-circle';
                      elseif ($statusClass == 'escalated')
                        echo 'bi-exclamation-triangle';
                      ?> 
                      me-1">
                    </i>
                    <?php echo ucfirst($dispute->status ?? 'pending'); ?>
                  </span>
                </td>
                <td>
                  <?php
                  $paymentStatus = 'pending'; // Default
                  $paymentClass = 'pending';
                  ?>
                  <span class="payment-badge <?php echo $paymentClass; ?>">
                    <i class="bi 
                      <?php
                      if ($paymentClass == 'pending')
                        echo 'bi-hourglass';
                      elseif ($paymentClass == 'paid')
                        echo 'bi-check-circle';
                      elseif ($paymentClass == 'failed')
                        echo 'bi-x-circle';
                      ?> 
                      me-1">
                    </i>
                    <?php echo ucfirst($paymentStatus); ?>
                  </span>
                </td>
                <td>
                  <button class="action-btn openModal d-flex align-items-center gap-2"
                    data-booking="<?php echo $dispute->booking_id ?>" 
                    data-provider="<?php echo $dispute->provider_id ?>"
                    data-pet="<?php echo $dispute->pet_id ?>" 
                    data-user="<?php echo $dispute->user_id ?>">
                    <i class="bi bi-plus-circle"></i>
                    <span>Manage</span>
                  </button>
                </td>
              </tr>
              <?php
              $i++;
            } ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <?php if (count($disputes) > 0): ?>
        <div class="d-flex justify-content-between align-items-center p-3 border-top">
          <div class="text-muted small">
            Showing <?php echo count($disputes); ?> entries
          </div>
          <div class="btn-group">
            <button class="btn btn-outline-primary btn-sm">
              <i class="bi bi-chevron-left"></i>
            </button>
            <button class="btn btn-primary btn-sm">1</button>
            <button class="btn btn-outline-primary btn-sm">
              <i class="bi bi-chevron-right"></i>
            </button>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Modal Popup for Addons & Packages -->
  <div class="modal" id="addpopup" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content p-0" style="height: 600px; display: flex; flex-direction: column;">
        <div class="modal-header flex-shrink-0">
          <h3 class="fw-semibold">
            <i class="bi bi-puzzle me-2"></i>Manage Addons & Packages
          </h3>
          <img src="https://beta.petsfolio.in/admin/public/assets/images/close.png" alt="Close" class="close-icon"
            id="close-icon">
        </div>
        
        <!-- Loader -->
        <div id="modalLoader" style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.9); z-index: 10; display: flex; align-items: center; justify-content: center; border-radius: 16px;">
          <div class="text-center">
            <div class="spinner-border text-primary mb-2" role="status" style="width: 3rem; height: 3rem;">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-muted">Loading data...</p>
          </div>
        </div>
        
        <form id="addonForm" style="display: flex; flex-direction: column; height: 100%; overflow: hidden;">
          <div class="modal-body" style="flex: 1 1 auto; overflow-y: auto; padding: 1.5rem;">
            <div class="section-title">
              <i class="bi bi-box-seam me-2"></i>Packages
            </div>
            <div id="packageList" class="mb-3"></div>

            <div class="section-title">
              <i class="bi bi-puzzle me-2"></i>Addons
            </div>
            <div id="addonList" class="mb-3"></div>
          </div>
          <div class="modal-footer flex-shrink-0">
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('close-icon').click()">
              <i class="bi bi-x-circle me-1"></i>Cancel
            </button>
            <button type="submit" class="btn bg-primary text-white">
              <i class="bi bi-check-circle me-1"></i>Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

<?php echo $this->include('admin/footer') ?>

<style>
  /* Custom styles for dispute management */
  .status-badge {
    padding: 0.5rem 1rem;
    font-weight: 500;
    letter-spacing: 0.02em;
    border-radius: 50px;
    display: inline-block;
  }

  .status-badge.pending {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.2);
  }

  .status-badge.resolved {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
    border: 1px solid rgba(40, 167, 69, 0.2);
  }

  .bg-gradient-primary {
    background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
  }

  .status-badge.escalated {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
    border: 1px solid rgba(220, 53, 69, 0.2);
  }

  .payment-badge {
    padding: 0.4rem 0.8rem;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 500;
  }

  .payment-badge.paid {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
    border: 1px solid rgba(40, 167, 69, 0.2);
  }

  .payment-badge.pending {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.2);
  }

  .payment-badge.failed {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
    border: 1px solid rgba(220, 53, 69, 0.2);
  }

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
    max-width: 600px;
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

  .modal-content {
    background-color: white;
    border-radius: 16px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    border: none;
    overflow: hidden;
    position: relative;
    display: flex;
    flex-direction: column;
    height: 600px;
  }

  .modal-header {
    background: #c6d2ff !important;
    padding: 1.25rem 1.5rem;
    border-bottom: 2px solid #a3b4ff;
    flex-shrink: 0;
  }

  .modal-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: #000;
  }

  .modal-body {
    flex: 1 1 auto;
    overflow-y: auto;
    padding: 1.5rem;
    scrollbar-width: thin;
    scrollbar-color: #c6d2ff #f1f1f1;
  }

  .modal-body::-webkit-scrollbar {
    width: 8px;
  }

  .modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
  }

  .modal-body::-webkit-scrollbar-thumb {
    background: #c6d2ff;
    border-radius: 4px;
  }

  .modal-body::-webkit-scrollbar-thumb:hover {
    background: #a3b4ff;
  }

  .modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
    flex-shrink: 0;
  }

  .close-icon {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 24px;
    height: 24px;
    cursor: pointer;
    opacity: 0.7;
    transition: opacity 0.2s;
    z-index: 20;
  }

  .close-icon:hover {
    opacity: 1;
  }

  /* Loader styling */
  #modalLoader {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.95);
    z-index: 1000;
    display: none;
    align-items: center;
    justify-content: center;
    border-radius: 16px;
    backdrop-filter: blur(3px);
  }

  #modalLoader .spinner-border {
    width: 3rem;
    height: 3rem;
    color: #6366f1;
  }

  /* Custom checkbox styling */
  .custom-checkbox {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    margin-bottom: 0.5rem;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    transition: all 0.2s;
  }

  .custom-checkbox:hover {
    background: #e9ecef;
    border-color: #c6d2ff;
  }

  .custom-checkbox span {
    font-weight: 500;
    color: #2d3436;
  }

  .custom-checkbox input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: #6366f1;
  }

  .section-title {
    font-size: 1rem;
    font-weight: 600;
    color: #2d3436;
    margin: 0 0 1rem 0;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #c6d2ff;
  }

  /* Action button styling */
  .action-btn {
    background: #c6d2ff;
    color: #000;
    border: none;
    padding: 0.4rem 1rem;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.2s;
  }

  .action-btn:hover {
    background: #a3b4ff;
    transform: translateY(-1px);
  }

  /* Table improvements */
  .table th {
    background: #c6d2ff !important;
    color: #000 !important;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 1rem !important;
    border-bottom: 2px solid #a3b4ff !important;
  }

  .table td {
    padding: 1rem !important;
    vertical-align: middle;
    color: #2d3436;
  }

  .table tbody tr:hover {
    background-color: rgba(198, 210, 255, 0.1) !important;
  }

  /* Avatar styles */
  .customer-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #c6d2ff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: #000;
    margin-right: 0.75rem;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .modal-dialog {
      width: 95%;
      margin: 1rem auto;
    }

    .modal-content {
      height: 80vh !important;
    }

    .table th,
    .table td {
      padding: 0.75rem !important;
    }

    .action-btn {
      padding: 0.3rem 0.8rem;
      font-size: 0.8rem;
    }
  }
</style>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("addpopup");
    const closeModal = document.getElementById("close-icon");
    const packageList = document.getElementById("packageList");
    const addonList = document.getElementById("addonList");
    const addonForm = document.getElementById("addonForm");
    const modalLoader = document.getElementById("modalLoader");

    let bookingId, providerId, petId, userId;

    // Open modal for each button
    document.querySelectorAll(".openModal").forEach((button) => {
      button.addEventListener("click", function () {
        bookingId = this.getAttribute("data-booking");
        providerId = this.getAttribute("data-provider");
        petId = this.getAttribute("data-pet");
        userId = this.getAttribute("data-user");

        // Show modal and loader
        modal.style.display = "flex";
        modalLoader.style.display = "flex";
        document.body.style.overflow = "hidden";

        // Clear previous content
        packageList.innerHTML = "";
        addonList.innerHTML = "";

        const url = `<?php echo base_url() ?>admin/getGroomingData`;

        fetch(`${url}/${bookingId}/${providerId}/${petId}`)
          .then((response) => response.json())
          .then((data) => {
            // Hide loader
            modalLoader.style.display = "none";

            if (data.packages && data.packages.length > 0) {
              data.packages.forEach((pkg) => {
                const checked = pkg.status === "completed" ? "checked" : "";
                packageList.innerHTML += `
                  <label class="custom-checkbox">
                    <span>
                      <i class="bi bi-box me-2 text-primary"></i>
                      ${pkg.package_name}
                    </span>
                    <input type="checkbox" class="package-checkbox" value="${pkg.package_id}" ${checked}>
                  </label>`;
              });
            } else {
              packageList.innerHTML = "<p class='text-muted text-center py-3'>No packages found</p>";
            }

            if (data.addons && data.addons.length > 0) {
              data.addons.forEach((addon) => {
                const checked = addon.status === "completed" ? "checked" : "";
                addonList.innerHTML += `
                  <label class="custom-checkbox">
                    <span>
                      <i class="bi bi-puzzle me-2 text-primary"></i>
                      ${addon.addon}
                    </span>
                    <input type="checkbox" class="addon-checkbox" value="${addon.addon}" ${checked}>
                  </label>`;
              });
            } else {
              addonList.innerHTML = "<p class='text-muted text-center py-3'>No addons found</p>";
            }
          })
          .catch((error) => {
            // Hide loader on error
            modalLoader.style.display = "none";
            console.error("Error fetching data:", error);
            alert("Failed to load data.");
          });
      });
    });

    closeModal.addEventListener("click", function () {
      modal.style.display = "none";
      document.body.style.overflow = "";
      modalLoader.style.display = "none"; // Ensure loader is hidden
    });

    // Close modal when clicking outside
    modal.addEventListener("click", function (e) {
      if (e.target === modal) {
        modal.style.display = "none";
        document.body.style.overflow = "";
        modalLoader.style.display = "none"; // Ensure loader is hidden
      }
    });

    addonForm.addEventListener("submit", function (event) {
      event.preventDefault();

      // Show loader while submitting
      modalLoader.style.display = "flex";

      let action = "approve";
      let formData = new FormData();
      formData.append("booking_id", bookingId);
      formData.append("provider_id", providerId);
      formData.append("pet_id", petId);
      formData.append("user_id", userId);

      let packagesCount = 0;
      document.querySelectorAll(".package-checkbox:checked").forEach((checkbox, index) => {
        formData.append(`packages[${index}][package_id]`, checkbox.value);
        formData.append(`packages[${index}][status]`, "completed");
        packagesCount++;
      });

      let addonsCount = 0;
      document.querySelectorAll(".addon-checkbox:checked").forEach((checkbox, index) => {
        formData.append(`addons[${index}][name]`, checkbox.value);
        formData.append(`addons[${index}][status]`, "completed");
        addonsCount++;
      });

      if (packagesCount === 0 && addonsCount === 0) {
        action = "reject";
      }
      formData.append("action", action);

      const submitUrl = `<?php echo base_url() ?>admin/approveOrRejectGroomingTracking`;

      fetch(submitUrl, {
        method: "POST",
        body: formData
      })
        .then((response) => response.json())
        .then((data) => {
          modalLoader.style.display = "none";
          
          if (data.status) {
            alert(data.message);
            modal.style.display = "none";
            document.body.style.overflow = "";
            location.reload();
          } else {
            alert("Error: " + (data.errors ? JSON.stringify(data.errors) : data.message));
          }
        })
        .catch((error) => {
          modalLoader.style.display = "none";
          console.error("Error submitting data:", error);
          alert("Submission failed.");
        });
    });

    // Search functionality
    const searchInput = document.getElementById('searchDisputes');
    if (searchInput) {
      searchInput.addEventListener('keyup', function () {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('.dispute-row');

        rows.forEach(row => {
          const text = row.textContent.toLowerCase();
          if (text.includes(searchTerm)) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        });
      });
    }
  });

  // Filter functions
  function applyFilters() {
    const statusFilter = document.getElementById('statusFilter').value;
    const paymentFilter = document.getElementById('paymentFilter').value;
    const rows = document.querySelectorAll('.dispute-row');

    rows.forEach(row => {
      let showRow = true;

      if (statusFilter) {
        const statusCell = row.querySelector('td:nth-child(6) .status-badge');
        if (statusCell && !statusCell.textContent.toLowerCase().includes(statusFilter.toLowerCase())) {
          showRow = false;
        }
      }

      if (paymentFilter && showRow) {
        const paymentCell = row.querySelector('td:nth-child(7) .payment-badge');
        if (paymentCell && !paymentCell.textContent.toLowerCase().includes(paymentFilter.toLowerCase())) {
          showRow = false;
        }
      }

      row.style.display = showRow ? '' : 'none';
    });
  }

  function clearFilters() {
    document.getElementById('searchDisputes').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('paymentFilter').value = '';

    const rows = document.querySelectorAll('.dispute-row');
    rows.forEach(row => {
      row.style.display = '';
    });
  }

  // Close modal on Escape key
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      const modal = document.getElementById('addpopup');
      if (modal.style.display === 'flex') {
        modal.style.display = 'none';
        document.body.style.overflow = '';
        const loader = document.getElementById('modalLoader');
        if (loader) loader.style.display = 'none';
      }
    }
  });
</script>

<?php echo $this->endSection() ?>