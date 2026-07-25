<?php echo $this->extend('admin/includes/header'); ?>
<!--start content-->
<?php echo $this->section('content'); ?>
<main class="main-content">
  <div class="position-relative iq-banner ">
    <!--Nav Start-->
    <?php echo $this->include('admin/nav_bar') ?>
  </div>

  <div class="container-fluid content-inner pb-0 px-4" id="page_layout">
    <!-- Modern Header Section -->
    <div class="container-fluid p-0 bg-gradient-primary rounded-3 mb-4">
      <div class=" text-white p-5  shadow position-relative overflow-hidden">
        <div class="position-relative z-1 d-flex justify-content-between align-items-center">
          <div>
            <h1 class="h2 fw-bold mb-2 text-white">New Enquiries</h1>
            <p class="text-white-75 mb-0">Manage and process new insurance enquiries</p>
          </div>

        </div>
        <i class="bi bi-shield-check position-absolute end-0 bottom-0 opacity-10"
          style="font-size: 14rem; transform: rotate(12deg); margin-right: -3rem; margin-bottom: -3rem;"></i>
      </div>
    </div>

    <!-- Search Bar -->

    <div class="card border-0 shadow-sm overflow-hidden mb-4">
      <div class="card-body p-3">
        <div class="d-flex justify-content-between align-items-center gap-3">
          <div class="input-group w-50 w-md-auto">
            <span class="input-group-text bg-black border-end-0">
              <i class="bi bi-search text-white"></i>
            </span>
            <input type="text" class="form-control border-start-0 ps-0" placeholder="Search..." id="searchInput">
          </div>
          <button class="btn btn-black bg-black text-white fw-bold px-4 py-2 shadow-sm d-flex align-items-center gap-2"
            onclick="showAddEnquiryModal()">
            <i class="bi bi-plus-circle"></i>Add Enquiry
          </button>
        </div>
      </div>
    </div>

    <!-- Modern Table -->
    <div class="card border-0 shadow-sm overflow-hidden">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="" style="background-color: #c6d2ff!important;">
            <tr>
              <th class=" py-3 text-black fs-6 fw-semibold border-bottom " style="background: #c6d2ff!important;">Client
                Details</th>
              <th class="py-3 text-black fs-6 fw-semibold border-bottom" style="background: #c6d2ff!important;">
                Insurance Type</th>
              <th class="py-3 text-black fs-6 fw-semibold border-bottom" style="background: #c6d2ff!important;">Enquiry
                Date</th>
              <th class="py-3 text-black fs-6 fw-semibold border-bottom text-center"
                style="background: #c6d2ff!important;">Status</th>
              <th class=" py-3 text-black fs-6 fw-semibold border-bottom text-end"
                style="background: #c6d2ff!important;">Actions</th>
            </tr>
          </thead>
          <tbody class="border-top-0">
            <?php
            $i = 1;
            foreach ($enquiries as $enquiry) {
              $initial = strtoupper(substr($enquiry->full_name, 0, 1));
              $formattedDate = date('d-m-Y', strtotime($enquiry->created_at));
            ?>
              <tr data-pet-details="<?= htmlspecialchars(json_encode([
                                      "breed" => $enquiry->breed,
                                      "age" => $enquiry->dob,
                                      "gender" => $enquiry->gender,
                                      "vaccination" => $enquiry->last_vaccination
                                    ]), ENT_QUOTES, 'UTF-8') ?>">
                <td class="ps-4 py-2">
                  <div class="d-flex align-items-center gap-3">
                    <div
                      class="rounded-circle bg-primary bg-opacity-10 text-white d-flex align-items-center justify-content-center fw-bold"
                      style="width: 44px; height: 44px; border: 2px solid #e5dbff; font-size: 1.2rem;">
                      <?= $initial ?>
                    </div>
                    <div>
                      <div class="fw-bold text-dark "><?= $enquiry->full_name ?></div>
                      <div class="d-flex align-items-center text-muted small mt-1" style="font-size: 12px;">
                        <i class="bi bi-telephone me-1"></i>
                        <?= $enquiry->phone ?>
                      </div>
                      <!-- <div class="text-muted small font-monospace mt-1">ID: #<?= $enquiry->policy_number ?></div> -->
                      <?php if (!empty($enquiry->remarks)): ?>
                        <div class="text-muted small mt-1 italic" style="font-size: 11px; color: #6366f1 !important;">
                          <i class="bi bi-chat-left-text me-1"></i>
                          <?= htmlspecialchars(substr($enquiry->remarks, 0, 50)) . (strlen($enquiry->remarks) > 50 ? '...' : '') ?>
                        </div>
                      <?php endif; ?>
                    </div>
                  </div>
                </td>
                <td class="py-2">
                  <div class="d-inline-flex align-items-center gap-2 bg-light px-3 py-2  border" style="background-color: rgba(59, 130, 246, 0.1) !important;
        color: #2563eb !important;
        border: 1px solid rgba(59, 130, 246, 0.2) !important; border-radius:50px ">
                    <i class="bi bi-shield-check"></i>
                    <span class="fw-medium  small">Dog Insurance</span>
                  </div>
                </td>
                <td class="py-2">
                  <div class="d-flex align-items-center gap-2 text-dark fw-medium">
                    <i class="bi bi-calendar text-muted "></i>
                    <span><?= $formattedDate ?></span>
                  </div>
                </td>
                <td class="py-2 text-center">
                  <?php
                  $statusClass = 'bg-light text-dark border';
                  $icon = 'bi-clock';
                  if ($enquiry->status === 'pending') {
                    $statusClass = 'bg-warning bg-opacity-10 text-warning border-warning border-opacity-25';
                    $icon = 'bi-clock';
                  } elseif ($enquiry->status === 'approved') {
                    $statusClass = 'bg-success bg-opacity-10 text-success border-success border-opacity-25';
                    $icon = 'bi-check-circle';
                  } elseif ($enquiry->status === 'rejected') {
                    $statusClass = 'bg-danger bg-opacity-10 text-danger border-danger border-opacity-25';
                    $icon = 'bi-x-circle';
                  }
                  ?>
                  <span class="badge d-inline-flex align-items-center gap-2  rounded-pill fw-semibold <?= $statusClass ?>"
                    style=" background-color: rgba(251, 191, 36, 0.1) !important;
        color: #d97706 !important;
        border: 1px solid rgba(251, 191, 36, 0.2) !important; font-size: 0.75rem; border-radius:50px">
                    <i class="<?= $icon ?> "></i>
                    <?= ucfirst($enquiry->status) ?>
                  </span>
                </td>
                <td class="pe-4 py-2 text-end">
                  <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-outline-primary btn-sm "
                      onclick="showAllDetails(<?= htmlspecialchars(json_encode($enquiry)) ?>)">
                      <i class="bi bi-eye me-1"></i>Details
                    </button>
                    <button class="btn btn-outline-dark btn-sm"
                      onclick="openUpdateStatusModal(<?= $enquiry->id ?>, '<?= $enquiry->status ?>', '<?= addslashes($enquiry->remarks ?? '') ?>')">
                      <i class="bi bi-pencil me-1"></i>Update
                    </button>
                  </div>
                </td>
              </tr>
            <?php
              $i++;
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
  </div>
  </div>
</main>

<!-- Pet Details Modal -->
<div id="petDetailsModal" class="modal" tabindex="-1" aria-labelledby="petDetailsModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow p-0" style="max-height: 90vh;">
      <div class="modal-header" style="background: #c6d2ff!important; border-bottom: 2px solid #a3b4ff;">
        <h2 class="modal-title text-black fw-bold fs-4 mb-0">
          <i class="bi bi-info-circle me-2"></i>Enquiry Details
        </h2>
        <button type="button" class="btn-close" aria-label="Close" id="closePetDetailsBtn"></button>
      </div>
      <div class="modal-body p-0" style="overflow-y: auto;">
        <div class="p-4">
          <div id="petDetailsContent"></div>
        </div>
      </div>
      <div class="modal-footer border-top bg-light">
        <button type="button" class="btn btn-secondary px-4" id="closePetDetailsFooter"> <i
            class="bi bi-x-circle me-1"></i>Close
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Add Enquiry Modal -->
<div id="addEnquiryModal" class="modal" tabindex="-1" aria-labelledby="addEnquiryModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow p-0">
      <div class="modal-header" style="background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%);">
        <h2 class="modal-title text-white fw-bold fs-4 mb-0">
          <i class="bi bi-plus-circle me-2"></i>Add New Enquiry
        </h2>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"
          onclick="closeAddEnquiryModal()"></button>
      </div>
      <div class="modal-body p-4">
        <!-- Step 1: Search Client -->
        <div id="searchClientStep">
          <div class="mb-4">
            <label class="form-label fw-bold text-muted small">SEARCH CLIENT BY PHONE</label>
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0">
                <i class="bi bi-telephone"></i>
              </span>
              <input type="text" class="form-control border-start-0" id="searchPhone" placeholder="Enter phone number">
              <button class="btn bg-primary text-white m-0" type="button" onclick="searchClient()">
                <i class="bi bi-search me-1"></i>Search
              </button>
            </div>
            <div id="searchFeedback" class="mt-2 small"></div>
          </div>
        </div>

        <!-- Step 1b: New Client Section (Hidden initially) -->
        <div id="newClientSection" style="display: none;">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-person-plus me-2"></i>New Client Details</h5>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="backToSearch()">Back to Search</button>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold text-black small">FULL NAME</label>
              <input type="text" class="form-control" id="new_client_name" placeholder="Enter name">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold text-black small">EMAIL</label>
              <input type="email" class="form-control" id="new_client_email" placeholder="Enter email">
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold text-black small">PHONE</label>
              <input type="text" class="form-control" id="new_client_phone" placeholder="Enter phone">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold text-black small">ADDRESS</label>
              <textarea class="form-control" id="new_client_address" placeholder="Enter address"></textarea>
            </div>
          </div>

          <h5 class="fw-bold mb-3 text-primary mt-4"><i class="bi bi-paw me-2"></i>Pet Details</h5>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold text-black small">PET NAME</label>
              <input type="text" class="form-control" id="new_pet_name" placeholder="Pet name">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold text-black small">BREED</label>
              <select class="form-select" id="new_pet_breed">
                <option value="">Select Breed</option>
              </select>
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold text-black small">AGGRESSIVE</label>
              <select class="form-select" id="new_pet_aggressive">
                <option value="No">No</option>
                <option value="Yes">Yes</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold text-black small">VACCINATED</label>
              <select class="form-select" id="new_pet_vaccinated">
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <label class="form-label fw-bold text-black small">DOB</label>
              <input type="date" class="form-control" id="new_pet_dob">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold text-black small">GENDER</label>
              <select class="form-select" id="new_pet_gender">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold text-black small">LAST VACCINATION</label>
              <input type="date" class="form-control" id="new_pet_vaccination">
            </div>
          </div>
          <hr>
        </div>

        <!-- Step 2: Enquiry Form (Hidden initially) -->
        <form id="enquiryForm" style="display: none;">
          <input type="hidden" id="foundClientId" name="user_id">
          <input type="hidden" id="is_new_client" name="is_new_client" value="0">

          <div id="existingClientDisplay">
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label fw-bold text-black small">CLIENT NAME</label>
                <input type="text" class="form-control bg-light" id="displayClientName" readonly name="full_name">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-bold text-black small">CLIENT EMAIL</label>
                <input type="email" class="form-control bg-light" id="displayClientEmail" readonly name="email">
                <input type="hidden" id="displayClientPhone" name="phone">
              </div>
            </div>

            <div class="row g-3 mb-3" id="petSelectRow">
              <div class="col-md-12">
                <label class="form-label fw-bold text-black small" id="petSelectLabel">SELECT PET</label>
                <select class="form-select" id="petSelect" name="pet_id" required>
                  <option value="">Select a pet</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold text-black small">COVERAGE TYPE</label>
              <select class="form-select" name="coverage_type" required>
                <option value="Standard">Standard</option>
                <option value="Premium">Premium</option>
                <option value="Basic">Basic</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold text-black small">DURATION (YEARS)</label>
              <select class="form-select" name="duration_years" required onchange="updateEndDate(this)">
                <option value="1">1 Year</option>
                <option value="2">2 Years</option>
                <option value="3">3 Years</option>
              </select>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold text-black small">START DATE</label>
              <input type="date" class="form-control" style="padding:0.5rem 3rem 0.5rem 1rem !important; border: 1px solid #eee !important" name="start_date" id="startDate"
                required onchange="calculateEndDate()">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold text-black small">END DATE</label>
              <input type="date" class="form-control bg-light" style="padding:0.5rem 3rem 0.5rem 1rem !important; border: 1px solid #eee !important;" name="end_date"
                id="endDate" readonly required>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer bg-light p-3">
        <button type="button" class="btn btn-outline-secondary px-4" onclick="closeAddEnquiryModal()">Cancel</button>
        <button type="button" class="btn bg-primary text-white px-4" id="submitEnquiryBtn" style="display: none;"
          onclick="submitEnquiry()">
          <i class="bi bi-save me-1"></i>Save Enquiry
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Email Modal -->
<div id="emailModal" class="modal" tabindex="-1" aria-labelledby="emailModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow p-0" style="height: 90vh;">
      <div class="modal-header"
        style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); border-bottom: 2px solid #a3b4ff;">
        <h2 class="modal-title text-white fw-bold fs-4 mb-0">
          <i class="bi bi-envelope-plus me-2"></i>Send Quotation
        </h2>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"
          onclick="closeEmailModal()"></button>
      </div>
      <div class="modal-body p-0" style="overflow-y: auto; flex: 1;">
        <div class="row g-0 h-100">
          <!-- Left Column - Form Fields -->
          <div class="col-md-5 p-4 border-end bg-white">
            <div class="mb-4">
              <label class="form-label text-muted small mb-2 fw-semibold">ENQUIRY ID</label>
              <div class="input-group">
                <span class="input-group-text bg-black border-end-0 text-white">
                  <i class="bi bi-file-text "></i>
                </span>
                <input type="text" class="form-control border-start-0 bg-white" id="enquiryId" readonly
                  name="policy_id">
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label text-muted small mb-2 fw-semibold">QUOTE AMOUNT</label>
              <div class="input-group">
                <span class="input-group-text bg-black border-end-0 text-white">
                  <i class="fa-solid fa-rupee-sign "></i>
                </span>
                <input type="number" class="form-control border-start-0" id="price" name="amount" placeholder="0.00">
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label text-muted small mb-2 fw-semibold">CLIENT EMAIL</label>
              <div class="input-group">
                <span class="input-group-text bg-black border-end-0 text-white">
                  <i class="bi bi-envelope "></i>
                </span>
                <input type="email" class="form-control border-start-0 bg-white" id="toEmail" readonly name="email">
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label text-muted small mb-2 fw-semibold">SUBJECT</label>
              <div class="input-group">
                <span class="input-group-text bg-black border-end-0 text-white">
                  <i class="bi bi-chat-text "></i>
                </span>
                <input type="text" class="form-control border-start-0" id="emailSubject" value="Pet Insurance Quotation"
                  name="subject">
              </div>
            </div>
          </div>

          <!-- Right Column - Message -->
          <div class="col-md-7 p-4">
            <label class="form-label text-muted small mb-2 fw-semibold">MESSAGE</label>
            <div class="border rounded bg-white" style="height: 300px;">
              <textarea class="form-control border-0 p-3" id="emailBody" name="message"
                style="height: 100%; resize: none; font-size: 0.95rem; line-height: 1.6;">Dear Customer,

Thank you for your enquiry about pet insurance. We are pleased to provide you with a quotation for your pet insurance.

Please find the details of our quotation below:

- Premium Amount: 
- Coverage Period: 
- Policy Benefits: 
- Terms and Conditions: 

If you have any questions or would like to proceed with this insurance, please don't hesitate to contact us.

Best regards,
Insurance Team</textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer border-top-0 p-4 bg-light">
        <button type="button" class="btn btn-outline-secondary px-4 py-2 " onclick="closeEmailModal()">
          <i class="bi bi-x-circle me-1"></i>Cancel
        </button>
        <button type="button" class="btn bg-primary text-white px-4 py-2 " onclick="sendQuotation(event)">
          <i class="bi bi-send me-1"></i>Send Quotation
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Update Status Modal -->
<div id="updateStatusModal" class="modal fade" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md " style="max-width: 450px !important;">
    <div class="modal-content border-0 shadow-lg rounded-4 p-0">

      <!-- Header -->
      <div class="modal-header border-0 pb-0">
        <div>
          <h5 class="modal-title fw-bold mb-1">Update Status</h5>
          <p class="text-muted small mb-0">Change enquiry status below</p>
        </div>
        <button type="button" class="btn-close bg-primary text-white" aria-label="Close"
          onclick="closeUpdateStatusModal()"></button>
      </div>

      <!-- Body -->
      <div class="modal-body p-3 pb-4">
        <input type="hidden" id="updateEnquiryId">

        <label class="form-label fw-semibold small text-uppercase text-muted mb-2">
          Select Status
        </label>

        <select class="form-select text-black form-select-lg shadow-sm rounded-3 mb-3" id="updateEnquiryStatus">
          <option value="pending" selected>🕒 Pending</option>
          <option value="approved">✅ Approved</option>
          <option value="rejected">❌ Rejected</option>
        </select>

        <label class="form-label fw-semibold small text-uppercase text-muted mb-2">
          Remarks / Comments
        </label>
        <textarea class="form-control rounded-3" id="updateEnquiryRemarks" rows="3" placeholder="Add any internal comments here..."></textarea>
      </div>

      <!-- Footer -->
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-light px-4 rounded-3" onclick="closeUpdateStatusModal()">
          Cancel
        </button>

        <button type="button" class="btn btn-dark px-4 rounded-3" onclick="submitStatusUpdate()">
          Update Status
        </button>
      </div>

    </div>
  </div>
</div>

<style>
  /* Fixed body when modal is open */
  body.modal-open {
    overflow: hidden !important;
    padding-right: 0 !important;
  }

  /* Modal backdrop */
  .modal-backdrop {
    background-color: rgba(0, 0, 0, 0.7) !important;
  }

  /* Custom styles to match the exact design */
  .bg-gradient-primary {
    background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
  }

  .bg-light-primary {
    background-color: rgba(99, 102, 241, 0.08) !important;
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

  .modal {
    display: none;
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    overflow-y: auto !important;
    align-items: center;
    justify-content: center;
  }

  .modal.show {
    display: flex;
  }

  .modal-dialog {
    margin: auto;
    width: 90%;
    max-width: 900px;
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

  .modal-dialog-scrollable .modal-body {
    overflow-y: auto;
  }

  .modal-content {
    background-color: white;
    border-radius: 16px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    border: none;
    overflow: hidden;
    display: flex;
    flex-direction: column;
  }

  .modal-header {
    padding: 1.5rem;
    border-bottom: none;
    flex-shrink: 0;
  }

  .modal-header h2 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
  }

  .modal-body {
    padding: 0;
    flex: 1 1 auto;
    overflow-y: auto;
  }

  .modal-footer {
    border-top: 1px solid #e5e7eb;
    flex-shrink: 0;
  }

  .badge {
    padding: 0.6rem 1.2rem !important;
    font-weight: 600 !important;
  }

  .form-label {
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .input-group-text {
    /* background-color: #f9fafb !important; */
    /* border-color: #e5e7eb !important; */
  }

  .form-control:focus {
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25) !important;
  }

  .form-control.bg-light {
    background-color: #f9fafb !important;
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

  /* Pet details content styling */
  .pet-detail-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px solid #dee2e6;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
  }

  .pet-detail-card:hover {
    border-color: #6366f1;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(99, 102, 241, 0.1);
  }

  .pet-detail-label {
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #6c757d;
  }

  .pet-detail-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: #495057;
  }

  /* Modal animation */
  .modal.show .modal-dialog {
    animation: modalSlideIn 0.3s ease-out;
  }

  @keyframes modalSlideIn {
    from {
      transform: translateY(-30px) scale(0.95);
      opacity: 0;
    }

    to {
      transform: translateY(0) scale(1);
      opacity: 1;
    }
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .modal-dialog {
      width: 95%;
      margin: 20px auto;
    }

    .modal-content {
      height: 85vh !important;
    }

    #emailModal .row {
      flex-direction: column;
    }

    #emailModal .border-end {
      border-right: none !important;
      border-bottom: 1px solid #e5e7eb !important;
    }

    #emailModal .col-md-5,
    #emailModal .col-md-7 {
      width: 100%;
    }
  }

  @media (max-width: 576px) {
    .modal-content {
      height: 90vh !important;
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

    .pet-detail-card {
      padding: 15px;
    }

    .pet-detail-value {
      font-size: 1.1rem;
    }
  }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
  integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
  crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
  // Prevent body scroll when modal is open
  function preventBodyScroll() {
    // document.body.classList.add('modal-open');

    const mainContent = document.querySelector('.main-content');
    if (mainContent) {
      // mainContent.setAttribute('inert', '');
    }
  }

  function allowBodyScroll() {
    document.body.classList.remove('modal-open');

    const mainContent = document.querySelector('.main-content');
    if (mainContent) {
      // mainContent.removeAttribute('inert');
    }
  }

  function focusModal(modalId) {
    setTimeout(() => {
      const modal = document.getElementById(modalId);
      if (modal) {
        const closeBtn = modal.querySelector('.btn-close');
        if (closeBtn) closeBtn.focus();
      }
    }, 100);
  }

  // Pet Details Modal Functions
  function showAllDetails(enquiry) {
    preventBodyScroll();

    const formattedDate = new Date(enquiry.created_at).toLocaleDateString('en-GB');

    const content = `
      <div class="row g-4">
        <!-- Client Information -->
        <div class="col-12">
          <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-person-circle fs-5 text-primary"></i>
            <h5 class="mb-0 fw-bold">Client Information</h5>
          </div>
          <div class="row g-3">
            <div class="col-md-4">
              <div class="pet-detail-card h-100">
                <div class="pet-detail-label">Full Name</div>
                <div class="pet-detail-value mt-1" style="font-size: 1.1rem;">${enquiry.full_name}</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="pet-detail-card h-100">
                <div class="pet-detail-label">Email</div>
                <div class="pet-detail-value mt-1" style="font-size: 1.1rem;">${enquiry.email}</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="pet-detail-card h-100">
                <div class="pet-detail-label">Phone</div>
                <div class="pet-detail-value mt-1" style="font-size: 1.1rem;">${enquiry.phone}</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Pet Information -->
        <div class="col-12 mt-4">
          <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-paw fs-5 text-primary"></i>
            <h5 class="mb-0 fw-bold">Pet Information</h5>
          </div>
          <div class="row g-3">
            <div class="col-md-3">
              <div class="pet-detail-card h-100">
                <div class="pet-detail-label">Name</div>
                <div class="pet-detail-value mt-1" style="font-size: 1.1rem;">${enquiry.name || 'N/A'}</div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="pet-detail-card h-100">
                <div class="pet-detail-label">Breed</div>
                <div class="pet-detail-value mt-1" style="font-size: 1.1rem;">${enquiry.breed || 'N/A'}</div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="pet-detail-card h-100">
                <div class="pet-detail-label">Age/DOB</div>
                <div class="pet-detail-value mt-1" style="font-size: 1.1rem;">${enquiry.dob || 'N/A'}</div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="pet-detail-card h-100">
                <div class="pet-detail-label">Gender</div>
                <div class="pet-detail-value mt-1" style="font-size: 1.1rem;">${enquiry.gender || 'N/A'}</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Policy Information -->
        <div class="col-12 mt-4">
          <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-shield-check fs-5 text-primary"></i>
            <h5 class="mb-0 fw-bold">Policy Request Details</h5>
          </div>
          <div class="row g-3">
            <div class="col-md-4">
              <div class="pet-detail-card h-100 border-primary border-opacity-25" style="background: rgba(99, 102, 241, 0.02);">
                <div class="pet-detail-label">Coverage Type</div>
                <div class="pet-detail-value mt-1 text-primary" style="font-size: 1.1rem;">${enquiry.coverage_type || 'Dog Insurance'}</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="pet-detail-card h-100">
                <div class="pet-detail-label">Duration</div>
                <div class="pet-detail-value mt-1" style="font-size: 1.1rem;">${enquiry.duration_years} Years</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="pet-detail-card h-100">
                <div class="pet-detail-label">Request Date</div>
                <div class="pet-detail-value mt-1" style="font-size: 1.1rem;">${formattedDate}</div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 mt-4">
          <div class="alert alert-info border-0 shadow-sm d-flex align-items-center gap-3 mb-3">
             <i class="bi bi-info-circle-fill fs-4"></i>
             <div>
                <div class="fw-bold">Vaccination Status</div>
                <div>${enquiry.last_vaccination || 'No vaccination records provided for this pet.'}</div>
             </div>
          </div>

          <div class="card border-0 shadow-sm" style="background: #f8f9fa;">
            <div class="card-body">
              <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-chat-left-text text-primary"></i>
                <h6 class="mb-0 fw-bold">Admin Remarks</h6>
              </div>
              <p class="text-muted mb-0 small">${enquiry.remarks || 'No remarks added yet.'}</p>
            </div>
          </div>
        </div>
      </div>
    `;

    $('#petDetailsContent').html(content);
    $('#petDetailsModal').attr('aria-hidden', 'false').addClass('show').css('display', 'flex');
    focusModal('petDetailsModal');
  }

  function showPetDetails(button) {
    // Legacy support if needed, but we use showAllDetails now
    const row = button.closest('tr');
    // ...
  }

  function closePetDetails() {
    const modal = $('#petDetailsModal');

    modal.removeClass('show');
    modal.css('display', 'none');
    modal.attr('aria-hidden', 'true');

    allowBodyScroll();


    // Clear content (optional but recommended)
    $('#petDetailsContent').html('');
  }

  // Email Modal Functions
  function showEmailForm(email, enquiryId, price = '') {
    preventBodyScroll();
    $('#toEmail').val(email);
    $('#enquiryId').val(enquiryId);
    $('#price').val(price || '');
    $('#emailModal').attr('aria-hidden', 'false').addClass('show').css('display', 'flex');
    focusModal('emailModal');
  }

  function closeEmailModal() {
    allowBodyScroll();
    $('#emailModal').attr('aria-hidden', 'true').removeClass('show').css('display', 'none');
  }

  function sendQuotation(event) {
    event.preventDefault();

    let formData = {
      email: $('#toEmail').val(),
      policy_id: $('#enquiryId').val(),
      amount: $('#price').val(),
      subject: $('#emailSubject').val(),
      message: $('#emailBody').val(),
    };

    console.log(formData);

    if (!formData.email || !formData.policy_id) {
      alert('Missing required data.');
      return;
    }

    $.ajax({
      url: "<?= base_url('insurance/sendQuotation') ?>",
      method: 'POST',
      data: formData,
      dataType: "json",
      beforeSend: function() {
        $('.btn-primary').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Sending...');
      },
      success: function(response) {
        if (response.status === true) {
          Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Quotation sent successfully!',
            timer: 2000,
            showConfirmButton: false
          });
          closeEmailModal();
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: response.message || 'Failed to send quotation.'
          });
        }
      },
      error: function(xhr) {
        console.error(xhr.responseText);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'An error occurred while sending the quotation.'
        });
      },
      complete: function() {
        $('.btn-primary').prop('disabled', false).html('<i class="bi bi-send me-1"></i>Send Quotation');
      }
    });
  }

  function showNewClientForm() {
    $('#searchClientStep').hide();
    $('#newClientSection').fadeIn();
    $('#enquiryForm').fadeIn();
    $('#existingClientDisplay').hide();
    $('#is_new_client').val('1');
    $('#submitEnquiryBtn').show();

    // Disable required attribute for petSelect when adding new client
    $('#petSelect').prop('required', false);

    // Auto-fill phone if entered in search
    if ($('#searchPhone').val()) {
      $('#new_client_phone').val($('#searchPhone').val());
    }
  }

  function backToSearch() {
    $('#newClientSection').hide();
    $('#enquiryForm').hide();
    $('#existingClientDisplay').show();
    $('#searchClientStep').show();
    $('#is_new_client').val('0');
    $('#submitEnquiryBtn').hide();
    $('#searchFeedback').html('');

    // Re-enable required attribute for petSelect
    $('#petSelect').prop('required', true);
  }

  // Add Enquiry Modal Functions
  function showAddEnquiryModal() {
    preventBodyScroll();
    $('#addEnquiryModal').attr('aria-hidden', 'false').addClass('show').css('display', 'flex');
    focusModal('addEnquiryModal');
    $('#searchClientStep').show();
    $('#enquiryForm').hide();
    $('#newClientSection').hide();
    $('#existingClientDisplay').show();
    $('#is_new_client').val('0');
    $('#submitEnquiryBtn').hide();
    $('#searchPhone').val('');
    $('#searchFeedback').html('');

    // Ensure pet_id is required initially
    $('#petSelect').prop('required', true);

    calculateEndDate();
  }

  function closeAddEnquiryModal() {
    allowBodyScroll();
    $('#addEnquiryModal').attr('aria-hidden', 'true').removeClass('show').css('display', 'none');
  }

  function searchClient() {
    const phone = $('#searchPhone').val();
    if (!phone) {
      $('#searchFeedback').html('<span class="text-danger">Please enter a phone number</span>');
      return;
    }

    const btn = $('#searchClientStep button');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Searching...');

    $.ajax({
      url: "<?= base_url('insurance/searchClient') ?>",
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({
        phone: phone
      }),
      success: function(response) {
        if (response.success) {
          $('#foundClientId').val(response.client.id);
          $('#displayClientName').val(response.client.full_name);
          $('#displayClientEmail').val(response.client.email);
          $('#displayClientPhone').val(response.client.phone);

          // Load pets
          loadClientPets(response.client.id);

          $('#searchClientStep').hide();
          $('#enquiryForm').fadeIn();
          $('#submitEnquiryBtn').show();
        } else {
          $('#searchFeedback').html(`
            <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center justify-content-between p-2 mb-0">
              <span class="small fw-medium"><i class="bi bi-exclamation-triangle me-2"></i>${response.message}</span>
              <button type="button" class="btn btn-sm btn-dark px-3" onclick="showNewClientForm()">Add New Client</button>
            </div>
          `);
        }
      },
      error: function() {
        $('#searchFeedback').html('<span class="text-danger">Connection error. Please try again.</span>');
      },
      complete: function() {
        btn.prop('disabled', false).html('<i class="bi bi-search me-1"></i>Search');
      }
    });
  }

  function loadClientPets(clientId) {
    $.ajax({
      url: "<?= base_url('insurance/getClientPets') ?>",
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({
        client_id: clientId
      }),
      success: function(response) {
        const select = $('#petSelect');
        select.empty().append('<option value="">Select a pet</option>');

        if (response.success && response.pets) {
          response.pets.forEach(pet => {
            select.append(`<option value="${pet.id}">${pet.name} (${pet.breed || 'N/A'})</option>`);
          });
        } else {
          select.append('<option value="" disabled>No pets found</option>');
        }
      }
    });
  }

  function calculateEndDate() {
    const startDate = $('#startDate').val();
    const years = parseInt($('[name="duration_years"]').val()) || 1;

    if (startDate) {
      const date = new Date(startDate);
      date.setFullYear(date.getFullYear() + years);
      $('#endDate').val(date.toISOString().split('T')[0]);
    }
  }

  function updateEndDate() {
    calculateEndDate();
  }

  function submitEnquiry() {
    const form = document.getElementById('enquiryForm');
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    const formData = {};
    const inputs = $(form).serializeArray();
    inputs.forEach(input => {
      formData[input.name] = input.value;
    });

    // Add new client/pet data if applicable
    if ($('#is_new_client').val() === '1') {
      formData.new_client_name = $('#new_client_name').val();
      formData.new_client_email = $('#new_client_email').val();
      formData.new_client_phone = $('#new_client_phone').val();
      formData.new_client_address = $('#new_client_address').val();

      formData.new_pet_name = $('#new_pet_name').val();
      formData.new_pet_breed = $('#new_pet_breed').val();
      formData.new_pet_dob = $('#new_pet_dob').val();
      formData.new_pet_gender = $('#new_pet_gender').val();
      formData.new_pet_vaccination = $('#new_pet_vaccination').val();
      formData.new_pet_aggressive = $('#new_pet_aggressive').val();
      formData.new_pet_vaccinated = $('#new_pet_vaccinated').val();

      // Basic validation for new client
      if (!formData.new_client_name || !formData.new_client_phone || !formData.new_pet_name) {
        Swal.fire('Required Fields', 'Please fill in client name, phone and pet name.', 'warning');
        return;
      }
    }

    const btn = $('#submitEnquiryBtn');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Saving...');

    $.ajax({
      url: "<?= base_url('insurance/addEnquiry') ?>",
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify(formData),
      success: function(response) {
        if (response.status === true) {
          Swal.fire({
            icon: 'success',
            title: 'Enquiry Added',
            text: 'The enquiry has been successfully created.',
            timer: 2000,
            showConfirmButton: false
          }).then(() => {
            location.reload();
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Failed',
            text: response.message || 'Could not add enquiry.'
          });
        }
      },
      error: function() {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'An error occurred while saving.'
        });
      },
      complete: function() {
        btn.prop('disabled', false).html('<i class="bi bi-save me-1"></i>Save Enquiry');
      }
    });
  }

  // Close modals when clicking outside
  $(document).on('click', function(e) {
    if (e.target.id === 'petDetailsModal') {
      closePetDetails();
    }
    if ($(e.target).is('#emailModal')) {
      closeEmailModal();
    }
    if ($(e.target).is('#addEnquiryModal')) {
      closeAddEnquiryModal();
    }
  });

  // Close modals on Escape key
  $(document).on('keydown', function(e) {
    if (e.key === 'Escape') {
      closePetDetails();
      closeEmailModal();
      closeAddEnquiryModal();
    }
  });

  // Search functionality
  // document.getElementById('searchInput').addEventListener('input', function(e) {
  //   const searchTerm = e.target.value.toLowerCase();
  //   const rows = document.querySelectorAll('tbody tr');

  //   rows.forEach(row => {
  //     const text = row.textContent.toLowerCase();
  //     row.style.display = text.includes(searchTerm) ? '' : 'none';
  //   });
  // });

  function loadBreeds() {
    $.ajax({
      url: "<?= base_url('insurance/getDogBreeds') ?>",
      method: "GET",
      success: function(response) {
        if (response.success) {
          let options = '<option value="">Select Breed</option>';
          response.breeds.forEach(function(breed) {
            options += `<option value="${breed.value}">${breed.label}</option>`;
          });
          $('#new_pet_breed').html(options);
        }
      }
    });
  }

  // Initialize on page load
  $(document).ready(function() {
    console.log('Enquiries page loaded');
    loadBreeds();
    calculateEndDate();
    $('#closePetDetailsBtn, #closePetDetailsFooter').on('click', function(e) {
      e.stopPropagation();
      closePetDetails();
    });

    // Initialize tooltips if needed
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    });
  });

  function openUpdateStatusModal(id, currentStatus, remarks = '') {
    console.log('Opening update status modal for ID:', id, 'Current Status:', currentStatus);
    preventBodyScroll();
    $('#updateEnquiryId').val(id);
    $('#updateEnquiryStatus').val(currentStatus.toLowerCase());
    $('#updateEnquiryRemarks').val(remarks);
    $('#updateStatusModal').attr('aria-hidden', 'false').addClass('show').css('display', 'flex');
    focusModal('updateStatusModal');
  }

  function closeUpdateStatusModal() {
    allowBodyScroll();
    $('#updateStatusModal').attr('aria-hidden', 'true').removeClass('show').css('display', 'none');
  }

  function submitStatusUpdate() {
    const id = $('#updateEnquiryId').val();
    const status = $('#updateEnquiryStatus').val();
    const remarks = $('#updateEnquiryRemarks').val();

    $.ajax({
      url: "<?= base_url('insurance/updateEnquiryStatus') ?>",
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({
        id: id,
        status: status,
        remarks: remarks
      }),
      success: function(response) {
        if (response.status) {
          Swal.fire({
            icon: 'success',
            title: 'Updated!',
            text: 'Status updated successfully.',
            timer: 1500,
            showConfirmButton: false
          }).then(() => {
            location.reload();
          });
        } else {
          alert(response.message || 'Failed to update status.');
        }
      },
      error: function() {
        alert('An error occurred while updating status.');
      }
    });
  }
</script>

<?php echo $this->include('admin/footer') ?>
<?php echo $this->endSection() ?>