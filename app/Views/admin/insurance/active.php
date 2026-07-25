<?php echo $this->extend('admin/includes/header'); ?>
<!--start content-->
<?php echo $this->section('content'); ?>
<main class="main-content">
    <div class="position-relative iq-banner">
        <!--Nav Start-->
        <?php echo $this->include('admin/nav_bar') ?>
    </div>

    <div class="container-fluid content-inner pb-0 px-4" id="page_layout">
        <!-- Modern Header Section -->
        <div class="container-fluid p-0 mb-4 bg-gradient-primary  rounded-3">
            <div class=" text-white px-5 p-3  shadow position-relative overflow-hidden">
                <div class="position-relative z-1">
                    <h1 class="h2 fw-bold mb-2 text-white">Active Insurance Policies</h1>
                    <p class="text-white-75 mb-0">Manage active insurance policies</p>
                </div>
                <i class="bi bi-shield-check position-absolute end-0 bottom-0 opacity-10"
                    style="font-size: 12rem; transform: rotate(12deg); margin-right: -2rem; margin-bottom: -2rem;"></i>
            </div>
            <div class="p-3">
                <div class="card border-0 shadow-sm mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center gap-3">
                            <div class="input-group w-50 w-md-auto">
                                <span class="input-group-text bg-black border-end-0">
                                    <i class="bi bi-search text-white"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 ps-0" placeholder="Search..."
                                    id="searchInput">
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary d-flex align-items-center gap-2"
                                    onclick="showAddEnquiryModal()">
                                    <i class="bi bi-plus-circle fs-6"></i>
                                    Add Enquiry
                                </button>
                                <button class="btn btn-black bg-black text-white d-flex align-items-center gap-2"
                                    onclick="openAddInsuranceModal()">
                                    <i class="bi bi-plus fs-6"></i>
                                    Add New Policy
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->


        <!-- Modern Table -->
        <div class="card border-0 shadow-sm overflow-hidden mb-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="" style="background-color: #c6d2ff!important;">
                        <tr>
                            <th class="py-3 text-black fs-6 fw-semibold border-bottom"
                                style="background: #c6d2ff!important;">Client Details</th>
                            <th class="py-3 text-black fs-6 fw-semibold border-bottom"
                                style="background: #c6d2ff!important;">Insurance Type</th>
                            <th class="py-3 text-black fs-6 fw-semibold border-bottom"
                                style="background: #c6d2ff!important;">Policy Period</th>
                            <th class="py-3 text-black fs-6 fw-semibold border-bottom"
                                style="background: #c6d2ff!important;">Premium</th>
                            <th class="py-3 text-black fs-6 fw-semibold border-bottom text-center"
                                style="background: #c6d2ff!important;">Status</th>
                            <th class="pe-4 py-3 text-black fs-6 fw-semibold border-bottom text-end"
                                style="background: #c6d2ff!important;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php
                        $i = 1;
                        foreach ($insurances as $ins) {
                            $initial = strtoupper(substr($ins->full_name, 0, 1));
                            $startDate = date('d-m-Y', strtotime($ins->start_date));
                            $endDate = date('d-m-Y', strtotime($ins->end_date));
                        ?>
                            <tr data-policy-details="<?= htmlspecialchars(json_encode([
                                                            "policyNumber" => $ins->policy_number,
                                                            "clientName" => $ins->full_name,
                                                            "contact" => $ins->phone,
                                                            "email" => $ins->email,
                                                            "insuranceType" => "Dog Insurance",
                                                            "coverageType" => $ins->coverage_type ?? "Comprehensive",
                                                            "startDate" => $startDate,
                                                            "expiryDate" => $endDate,
                                                            "premium" => "₹" . number_format($ins->amount, 2),
                                                            "renewalDate" => $endDate,
                                                            "status" => "Active",
                                                            "petDetails" => [
                                                                "name" => $ins->name ?? "N/A",
                                                                "breed" => $ins->breed,
                                                                "age" => $ins->dob,
                                                                "gender" => $ins->gender,
                                                                "vaccination" => $ins->last_vaccination,
                                                                "remarks" => $ins->remarks ?? ""
                                                            ]
                                                        ]), ENT_QUOTES, 'UTF-8') ?>">
                                <td class="ps-4 py-2">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-white d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 40px; height: 40px; border: 1px solid #e5dbff;">
                                            <?= $initial ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?= $ins->full_name ?></div>
                                            <div class="d-flex align-items-center text-muted small mt-1">
                                                <i class="bi bi-telephone me-1"></i>
                                                <?= $ins->phone ?>
                                            </div>
                                            <!-- <div class="text-muted small font-monospace mt-1">
                                                ID: #<?= $ins->policy_number ?>
                                            </div> -->
                                            <?php if (!empty($ins->remarks)): ?>
                                                <div class="text-muted small mt-1 italic" style="font-size: 11px; color: #6366f1 !important;">
                                                    <i class="bi bi-chat-left-text me-1"></i>
                                                    <?= htmlspecialchars(substr($ins->remarks, 0, 50)) . (strlen($ins->remarks) > 50 ? '...' : '') ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2">
                                    <div class="d-inline-flex align-items-center gap-2 bg-light px-3 py-1 border" style="background-color: rgba(59, 130, 246, 0.1) !important;
        color: #2563eb !important;
        border: 1px solid rgba(59, 130, 246, 0.2) !important; border-radius:50px">
                                        <i class="bi bi-shield-check "></i>
                                        <span class="fw-medium  small">Dog Insurance</span>
                                    </div>
                                </td>
                                <td class="py-2">
                                    <div class="text-dark">
                                        <div class="d-flex align-items-center gap-2 fw-medium">
                                            <i class="bi bi-calendar-check text-muted"></i>
                                            <span><?= $startDate ?></span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 text-muted small mt-1">
                                            <i class="bi bi-calendar-x text-muted"></i>
                                            <span>to <?= $endDate ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2">
                                    <div class="fw-bold text-dark fs-6">
                                        ₹<?= number_format($ins->amount, 2) ?>
                                    </div>
                                </td>
                                <td class="py-2 text-center">
                                    <span
                                        class="badge d-inline-flex align-items-center gap-1 px-3 py-2 rounded-pill fw-semibold bg-success bg-opacity-10 text-white border-success border-opacity-25">
                                        <i class="bi bi-check-circle"></i>
                                        Active
                                    </span>
                                </td>
                                <td class="pe-4 py-2 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-outline-primary btn-sm" onclick="showPolicyDetails(this)">
                                            View
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php
                            $i++;
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4 px-2">
            <div class="text-muted small">
                Showing <?= count($insurances) ?> entries
            </div>
            <div class="btn-group mt-0 pt-0 border-0">
                <button class="btn btn-outline-primary btn-sm">Previous</button>
                <button class="btn btn-primary btn-sm">1</button>
                <button class="btn btn-outline-primary btn-sm">Next</button>
            </div>
        </div>
    </div>
</main>

<!-- Policy Details Modal - Bootstrap Modal -->
<div class="modal fade modal-dialog-scrollable" id="policyDetailsModal" tabindex="-1"
    aria-labelledby="policyDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow my-0 p-0" style="height: 85vh;">
            <div class="modal-header"
                style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); border-bottom: 2px solid #a3b4ff;">
                <h2 class="modal-title text-white fw-bold mb-0">Policy Details</h2>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="overflow-y: auto; flex: 1;">
                <div class="p-4">
                    <!-- Policy Header Section -->
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-white bg-opacity-20 text-white d-flex align-items-center justify-content-center fw-bold"
                                style="width: 50px; height: 50px; border: 2px solid rgba(255,255,255,0.3);">
                                <i class="bi bi-shield-check fs-5"></i>
                            </div>
                            <div>
                                <h3 class="h4 fw-bold text-dark mb-1" id="detail-client-name-header">-</h3>
                                <div class="d-flex align-items-center text-muted">
                                    <i class="bi bi-file-text me-2"></i>
                                    <span id="detail-policy-number-header">-</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <div
                                class="badge d-inline-flex align-items-center gap-1 px-3 py-2 rounded-pill fw-semibold bg-success bg-opacity-10 text-success border-success border-opacity-25">
                                <i class="bi bi-check-circle"></i>
                                Active
                            </div>
                            <div class="text-muted small mt-1">Dog Insurance</div>
                        </div>
                    </div>

                    <!-- Grid Layout for Details -->
                    <div class="grid-container">
                        <!-- Client Information Card -->
                        <div class="grid-card">
                            <div class="grid-card-header">
                                <i class="bi bi-person-circle grid-card-icon"></i>
                                <h6 class="grid-card-title">Client Information</h6>
                            </div>
                            <div class="grid-card-body">
                                <div class="detail-item">
                                    <span class="detail-label">Full Name</span>
                                    <span class="detail-value" id="detail-client-name">-</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Email</span>
                                    <span class="detail-value" id="detail-email">-</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Phone</span>
                                    <span class="detail-value" id="detail-phone">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- Policy Information Card -->
                        <div class="grid-card">
                            <div class="grid-card-header">
                                <i class="bi bi-file-earmark-text grid-card-icon"></i>
                                <h6 class="grid-card-title">Policy Information</h6>
                            </div>
                            <div class="grid-card-body">
                                <div class="detail-item">
                                    <span class="detail-label">Policy Number</span>
                                    <span class="detail-value font-monospace" id="detail-policy-number">-</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Insurance Type</span>
                                    <span class="detail-value" id="detail-insurance-type">Dog Insurance</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Coverage Type</span>
                                    <span class="detail-value" id="detail-coverage-type">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- Dates Card -->
                        <div class="grid-card">
                            <div class="grid-card-header">
                                <i class="bi bi-calendar-week grid-card-icon"></i>
                                <h6 class="grid-card-title">Policy Dates</h6>
                            </div>
                            <div class="grid-card-body">
                                <div class="detail-item">
                                    <span class="detail-label">Start Date</span>
                                    <span class="detail-value" id="detail-start-date">-</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Expiry Date</span>
                                    <span class="detail-value" id="detail-expiry-date">-</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Renewal Date</span>
                                    <span class="detail-value" id="detail-renewal-date">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- Financial Card -->
                        <div class="grid-card">
                            <div class="grid-card-header">
                                <i class="bi bi-cash-stack grid-card-icon"></i>
                                <h6 class="grid-card-title">Financial Details</h6>
                            </div>
                            <div class="grid-card-body">
                                <div class="detail-item">
                                    <span class="detail-label">Premium Amount</span>
                                    <span class="detail-value fw-bold" id="detail-premium">-</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Payment Status</span>
                                    <span class="detail-value">
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success border-success border-opacity-25 px-2 py-1">
                                            Paid
                                        </span>
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Payment Frequency</span>
                                    <span class="detail-value">Annual</span>
                                </div>
                            </div>
                        </div>

                        <!-- Pet Details Card -->
                        <div class="grid-card wide-card">
                            <div class="grid-card-header">
                                <i class="bi bi-heart-pulse grid-card-icon"></i>
                                <h6 class="grid-card-title">Pet Details</h6>
                            </div>
                            <div class="grid-card-body">
                                <div class="row g-3">
                                    <div class="col-md-5th col-md-2">
                                        <div class="pet-detail-box">
                                            <div class="pet-detail-label">Pet Name</div>
                                            <div class="pet-detail-value" id="pet-name">-</div>
                                        </div>
                                    </div>
                                    <div class="col-md-5th col-md-3">
                                        <div class="pet-detail-box">
                                            <div class="pet-detail-label">Breed</div>
                                            <div class="pet-detail-value" id="pet-breed">-</div>
                                        </div>
                                    </div>
                                    <div class="col-md-5th col-md-2">
                                        <div class="pet-detail-box">
                                            <div class="pet-detail-label">Age</div>
                                            <div class="pet-detail-value" id="pet-age">-</div>
                                        </div>
                                    </div>
                                    <div class="col-md-5th col-md-2">
                                        <div class="pet-detail-box">
                                            <div class="pet-detail-label">Gender</div>
                                            <div class="pet-detail-value" id="pet-gender">-</div>
                                        </div>
                                    </div>
                                    <div class="col-md-5th col-md-3">
                                        <div class="pet-detail-box">
                                            <div class="pet-detail-label">Last Vaccination</div>
                                            <div class="pet-detail-value" id="pet-vaccination">-</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Policy Benefits Card -->
                        <div class="grid-card wide-card">
                            <div class="grid-card-header">
                                <i class="bi bi-award grid-card-icon"></i>
                                <h6 class="grid-card-title">Policy Benefits</h6>
                            </div>
                            <div class="grid-card-body">
                                <div class="benefits-list">
                                    <div class="benefit-item">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <span>Accident Coverage up to ₹5,00,000</span>
                                    </div>
                                    <div class="benefit-item">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <span>Illness Coverage up to ₹3,00,000</span>
                                    </div>
                                    <div class="benefit-item">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <span>Third Party Liability up to ₹10,00,000</span>
                                    </div>
                                    <div class="benefit-item">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <span>Vaccination Coverage up to ₹5,000 annually</span>
                                    </div>
                                    <div class="benefit-item">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <span>Emergency Care Coverage</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Admin Remarks Card -->
                        <div class="grid-card wide-card">
                            <div class="grid-card-header">
                                <i class="bi bi-chat-left-text grid-card-icon"></i>
                                <h6 class="grid-card-title">Admin Remarks</h6>
                            </div>
                            <div class="grid-card-body">
                                <p class="text-muted mb-0 small" id="detail-remarks">No remarks provided.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-primary">
                    <i class="bi bi-download me-2"></i> Download Policy
                </button>
            </div>
        </div>
    </div>
</div>

<!-- New Add Insurance Modal with Multi-step Form - Bootstrap Modal -->
<div class="modal fade modal-dialog-scrollable" id="addInsuranceModal" tabindex="-1"
    aria-labelledby="addInsuranceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow my-0 p-0" style="height: 70vh;">
            <div class="modal-header"
                style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); border-bottom: 2px solid #a3b4ff;">
                <h2 class="modal-title text-white fw-bold mb-0" id="addInsuranceModalLabel">Add New Insurance Policy
                </h2>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="overflow-y: auto; flex: 1;">
                <div class="p-4">
                    <!-- Progress Steps -->
                    <div class="progress-steps mb-4">
                        <div class="step active" data-step="1">
                            <div class="step-circle">1</div>
                            <div class="step-label">Find Client</div>
                        </div>
                        <div class="step" data-step="2">
                            <div class="step-circle">2</div>
                            <div class="step-label">Select Pet</div>
                        </div>
                        <div class="step" data-step="3">
                            <div class="step-circle">3</div>
                            <div class="step-label">Policy Details</div>
                        </div>
                    </div>

                    <!-- Step 1: Find Client -->
                    <div id="step1" class="step-content active">
                        <h4 style="color: #667eea; margin-bottom: 12px">Find Client</h4>

                        <!-- Search Section -->
                        <div id="clientSearchSection">
                            <div class="mb-4">
                                <label for="clientSearch" class="form-label text-black small mb-1">Enter Client Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-telephone"></i>
                                    </span>
                                    <input type="text" id="clientSearch" placeholder="Enter phone number"
                                        class="form-control border-start-0" />
                                    <button type="button" class="btn btn-primary m-0" onclick="searchClient()">
                                        Search Client
                                    </button>
                                </div>
                                <div id="clientSearchError" class="mt-2" style="display: none;"></div>
                            </div>
                        </div>

                        <!-- New Client Section -->
                        <div id="newClientFormSection" style="display: none;">
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
                        </div>

                        <div class="client-info" id="clientInfo" style="display: none;">
                            <div class="bg-light-primary p-3 rounded-3 mb-4">
                                <h5 class="mb-3">Client Information</h5>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div>
                                            <h6 class="text-muted mb-1">Name</h6>
                                            <p class="fw-bold mb-0" id="clientName">-</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div>
                                            <h6 class="text-muted mb-1">Email</h6>
                                            <p class="fw-bold mb-0" id="clientEmail">-</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div>
                                            <h6 class="text-muted mb-1">Phone</h6>
                                            <p class="fw-bold mb-0" id="clientPhone">-</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <input type="hidden" id="is_new_client" value="0">
                            <input type="hidden" id="is_new_pet" value="0">
                            <button type="button" class="btn btn-primary" onclick="nextStep(2)" id="step1Next"
                                style="display: none;">
                                Next <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Select Pet -->
                    <div id="step2" class="step-content">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 style="color: #667eea; margin-bottom: 0">Select Pet</h4>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="addNewPetBtn" onclick="showNewPetFormInStep2()">
                                <i class="bi bi-plus-circle me-1"></i> Add New Pet
                            </button>
                        </div>
                        <div id="petsList" class="pets-list mb-4 row g-3">
                            <!-- Pets will be loaded here dynamically -->
                        </div>

                        <!-- New Pet Form Section (Inside Step 2) -->
                        <div id="newPetFormStep2" style="display: none;" class="mt-3 p-3 border rounded bg-light mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-paw me-2"></i>New Pet Details</h5>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="hideNewPetFormInStep2()">Back to Selection</button>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-black small">PET NAME</label>
                                    <input type="text" class="form-control" id="step2_pet_name" placeholder="Pet name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-black small">BREED</label>
                                    <select class="form-select step2-pet-breed" id="step2_pet_breed">
                                        <option value="">Select Breed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-black small">AGGRESSIVE</label>
                                    <select class="form-select" id="step2_pet_aggressive">
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-black small">VACCINATED</label>
                                    <select class="form-select" id="step2_pet_vaccinated">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-black small">DOB</label>
                                    <input type="date" class="form-control" id="step2_pet_dob">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-black small">GENDER</label>
                                    <select class="form-select" id="step2_pet_gender">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-black small">LAST VACCINATION</label>
                                    <input type="date" class="form-control" id="step2_pet_vaccination">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary" onclick="prevStep(1)">
                                <i class="bi bi-arrow-left"></i> Previous
                            </button>
                            <button type="button" class="btn btn-primary" onclick="nextStep(3)" id="step2Next" disabled>
                                Next <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Policy Details -->
                    <div id="step3" class="step-content">
                        <div class="policy-card p-4">

                            <h4 class="step-title mb-4">
                                <i class="bi bi-shield-check me-2"></i> Policy Details
                            </h4>

                            <form id="addInsuranceForm">

                                <!-- Coverage Type -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold mb-3">Choose Coverage Type</label>
                                    <div class="row g-3">

                                        <div class="col-md-3">
                                            <div class="coverage-option active" onclick="selectCoverage('Basic', this)">
                                                <h6>Basic</h6>
                                                <small>Protection</small>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="coverage-option" onclick="selectCoverage('Standard', this)">
                                                <h6>Standard</h6>
                                                <small>Balanced Plan</small>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="coverage-option" onclick="selectCoverage('Premium', this)">
                                                <h6>Premium</h6>
                                                <small>Advanced Care</small>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="coverage-option"
                                                onclick="selectCoverage('Comprehensive', this)">
                                                <h6>Comprehensive</h6>
                                                <small>Full Coverage</small>
                                            </div>
                                        </div>

                                    </div>
                                    <input type="hidden" id="coverageType" name="coverage_type" value="Basic">
                                </div>

                                <!-- Premium & Duration -->
                                <div class="row g-4 mb-4">

                                    <div class="col-md-6">
                                        <label class="form-label text-muted small">Premium Amount (₹)</label>
                                        <div class="modern-input">
                                            <i class="bi bi-currency-rupee"></i>
                                            <input type="number" id="premium" name="premium"
                                                placeholder="Enter premium amount" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label text-muted small">Duration (Years)</label>
                                        <div class="modern-input">
                                            <i class="bi bi-clock"></i>
                                            <input type="number" id="durationYears" name="duration_years" value="1"
                                                min="1" max="10" required>
                                        </div>
                                    </div>

                                </div>

                                <!-- Dates -->
                                <div class="row g-4 mb-4">

                                    <div class="col-md-6">
                                        <label class="form-label text-muted small">Start Date</label>
                                        <div class="modern-input">
                                            <i class="bi bi-calendar-plus"></i>
                                            <input type="date" id="startDate" name="start_date" required
                                                onchange="updateEndDate()">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label text-muted small">End Date</label>
                                        <div class="modern-input">
                                            <i class="bi bi-calendar-check"></i>
                                            <input type="date" id="endDate" name="end_date" required
                                                onchange="updateDuration()">
                                        </div>
                                    </div>

                                </div>

                                <!-- Buttons -->
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-light px-4" onclick="prevStep(2)">
                                        <i class="bi bi-arrow-left me-1"></i> Previous
                                    </button>

                                    <button type="submit" class="btn btn-gradient px-4">
                                        <i class="bi bi-check-circle me-1"></i> Add Policy
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .policy-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
    }

    .step-title {
        color: #667eea;
        font-weight: 600;
    }

    .coverage-option {
        padding: 18px;
        border-radius: 12px;
        border: 1px solid #eaeaea;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f9fafc;
    }

    .coverage-option:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    }

    .coverage-option.active {
        border: 2px solid #667eea;
        background: #eef2ff;
    }

    .modern-input {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f8f9fb;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid #eaeaea;
    }

    .modern-input input {
        border: none;
        background: transparent;
        width: 100%;
        outline: none;
        padding: 0 !important;
    }

    .btn-gradient {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
    }

    .btn-gradient:hover {
        opacity: 0.9;
    }

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

    .modal-content {
        border-radius: 16px !important;
        border: none;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        display: flex;
        flex-direction: column;
    }

    .badge {
        padding: 0.5rem 1rem !important;
        font-weight: 600 !important;
    }

    .input-group-text {
        /* background-color: #f9fafb !important;
        border-color: #e5e7eb !important; */
    }

    .form-control:focus {
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

    /* Progress Steps */
    .progress-steps {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        position: relative;
    }

    .progress-steps::before {
        content: '';
        position: absolute;
        top: 15px;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #e5e7eb;
        z-index: 1;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
        flex: 1;
    }

    .step-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #e5e7eb;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-bottom: 8px;
        border: 2px solid #e5e7eb;
    }

    .step.active .step-circle {
        background-color: #6366f1;
        color: white;
        border-color: #6366f1;
    }

    .step-label {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 500;
    }

    .step.active .step-label {
        color: #6366f1;
        font-weight: 600;
    }

    .step-content {
        display: none;
    }

    .step-content.active {
        display: block;
    }

    /* Grid Layout for Policy Details */
    .grid-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .grid-card {
        background: #f9fafb;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .grid-card:hover {
        border-color: #6366f1;
        box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.1);
    }

    .grid-card.wide-card {
        grid-column: span 2;
    }

    .grid-card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .grid-card-icon {
        color: #6366f1;
        font-size: 1.25rem;
    }

    .grid-card-title {
        margin: 0;
        font-size: 0.9rem;
        font-weight: 600;
        color: #374151;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .grid-card-body {
        padding: 1.25rem;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-size: 0.8rem;
        color: #6b7280;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .detail-value {
        font-size: 0.95rem;
        color: #111827;
        font-weight: 600;
        text-align: right;
        max-width: 60%;
        word-break: break-word;
    }

    /* Pet Details Box */
    .pet-detail-box {
        text-align: center;
        padding: 1rem;
        background: white;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .pet-detail-box:hover {
        border-color: #6366f1;
        background: #f8fafc;
    }

    .pet-detail-label {
        font-size: 0.75rem;
        color: #6b7280;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }

    .pet-detail-value {
        font-size: 1rem;
        color: #111827;
        font-weight: 600;
    }

    /* Benefits List */
    .benefits-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .benefit-item {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        background: white;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .benefit-item:hover {
        border-color: #10b981;
        background: #f0fdf4;
    }

    .benefit-item i {
        font-size: 1rem;
    }

    .benefit-item span {
        font-size: 0.9rem;
        color: #374151;
        font-weight: 500;
    }

    .pet-item {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        background-color: #f9fafb;
    }

    .pet-item:hover {
        border-color: #6366f1;
        background-color: #f8f9ff;
        transform: translateY(-2px);
    }

    .pet-item.selected {
        border-color: #6366f1;
        background-color: #eef2ff;
        box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.1);
    }

    .error-message {
        color: #dc3545;
        margin-top: 5px;
        font-size: 14px;
    }

    .success-message {
        color: #28a745;
        margin-top: 5px;
        font-size: 14px;
    }

    /* Form styling */
    .form-label {
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .input-group-text {
        /* background-color: #f9fafb !important; */
        border-color: #e5e7eb !important;
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

    /* Modal animation */
    .modal.fade .modal-dialog {
        transform: scale(0.95);
        transition: transform 0.3s ease-out;
    }

    .modal.show .modal-dialog {
        transform: scale(1);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .grid-container {
            grid-template-columns: 1fr;
        }

        .grid-card.wide-card {
            grid-column: span 1;
        }

        .detail-item {
            flex-direction: column;
            gap: 0.25rem;
        }

        .detail-value {
            text-align: left;
            max-width: 100%;
        }

        .modal-dialog {
            margin: 20px;
            width: calc(100% - 40px);
        }

        .modal-content {
            height: 80vh !important;
        }
    }

    @media (max-width: 576px) {
        .modal-content {
            height: 90vh !important;
        }

        .modal-header {
            padding: 15px;
        }

        .modal-body .p-4 {
            padding: 15px !important;
        }

        .modal-footer {
            padding: 15px;
        }

        .row.g-4 {
            gap: 10px !important;
        }

        .bg-light-primary {
            padding: 15px !important;
        }

        .progress-steps {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .progress-steps::before {
            display: none;
        }

        .step {
            flex-direction: row;
            gap: 10px;
            width: 100%;
        }

        .step-circle {
            margin-bottom: 0;
        }
    }

    /* 5-column grid for pet details */
    @media (min-width: 768px) {
        .col-md-5th {
            width: 20%;
            flex: 0 0 20%;
        }
    }

    .pet-card {
        transition: all 0.3s ease;
        border-radius: 12px;
    }

    .pet-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    }

    .pet-card.selected {
        border: 2px solid #0d6efd !important;
        background-color: #f0f7ff;
    }

    .bg-pink {
        background-color: #e83e8c;
    }
</style>

<script>
    function selectCoverage(type, element) {
        document.querySelectorAll('.coverage-option')
            .forEach(el => el.classList.remove('active'));

        element.classList.add('active');
        document.getElementById('coverageType').value = type;
    }
    // Function to show policy details
    function showPolicyDetails(button) {
        try {
            const row = button.closest('tr');
            const dataAttr = row.getAttribute('data-policy-details');
            if (!dataAttr) throw new Error('No policy data found on row');

            const policyDetails = JSON.parse(dataAttr);
            console.log('Policy Details:', policyDetails); // Debug log

            // Update modal header elements
            const policyClientNameHeader = document.getElementById('detail-client-name-header');
            const policyNumberHeader = document.getElementById('detail-policy-number-header');

            if (policyClientNameHeader) policyClientNameHeader.textContent = policyDetails.clientName;
            if (policyNumberHeader) policyNumberHeader.textContent = `Policy #${policyDetails.policyNumber}`;

            // Update grid cards
            const detailClientName = document.getElementById('detail-client-name');
            const detailEmail = document.getElementById('detail-email');
            const detailPhone = document.getElementById('detail-phone');
            const detailPolicyNumber = document.getElementById('detail-policy-number');
            const detailInsuranceType = document.getElementById('detail-insurance-type');
            const detailCoverageType = document.getElementById('detail-coverage-type');
            const detailStartDate = document.getElementById('detail-start-date');
            const detailExpiryDate = document.getElementById('detail-expiry-date');
            const detailRenewalDate = document.getElementById('detail-renewal-date');
            const detailPremium = document.getElementById('detail-premium');

            if (detailClientName) detailClientName.textContent = policyDetails.clientName;
            if (detailEmail) detailEmail.textContent = policyDetails.email;
            if (detailPhone) detailPhone.textContent = policyDetails.contact;
            if (detailPolicyNumber) detailPolicyNumber.textContent = policyDetails.policyNumber;
            if (detailInsuranceType) detailInsuranceType.textContent = policyDetails.insuranceType;
            if (detailCoverageType) detailCoverageType.textContent = policyDetails.coverageType || 'Comprehensive';
            if (detailStartDate) detailStartDate.textContent = policyDetails.startDate;
            if (detailExpiryDate) detailExpiryDate.textContent = policyDetails.expiryDate;
            if (detailRenewalDate) detailRenewalDate.textContent = policyDetails.renewalDate;
            if (detailPremium) detailPremium.textContent = policyDetails.premium;

            // Update pet details
            const petName = document.getElementById('pet-name');
            const petBreed = document.getElementById('pet-breed');
            const petAge = document.getElementById('pet-age');
            const petGender = document.getElementById('pet-gender');
            const petVaccination = document.getElementById('pet-vaccination');

            if (petName) petName.textContent = policyDetails.petDetails.name || 'N/A';
            if (petBreed) petBreed.textContent = policyDetails.petDetails.breed || 'Not specified';
            if (petAge) petAge.textContent = policyDetails.petDetails.age || 'Not specified';
            if (petGender) petGender.textContent = policyDetails.petDetails.gender || 'Not specified';
            if (petVaccination) petVaccination.textContent = policyDetails.petDetails.vaccination || 'Not specified';

            const detailRemarks = document.getElementById('detail-remarks');
            if (detailRemarks) detailRemarks.textContent = policyDetails.petDetails.remarks || 'No remarks provided.';


            // Show modal using Bootstrap
            const modalElement = document.getElementById('policyDetailsModal');
            let modal = window.bootstrap.Modal.getInstance(modalElement);
            if (!modal) {
                modal = new window.bootstrap.Modal(modalElement);
            }
            modal.show();

        } catch (error) {
            console.error('Error showing policy details:', error);
            alert('Error loading policy details. Please try again.');
        }
    }

    let currentStep = 1;
    let selectedClient = null;
    let selectedPet = null;
    let isEnquiryMode = false;

    function openAddInsuranceModal() {
        isEnquiryMode = false;
        document.getElementById('addInsuranceModalLabel').textContent = 'Add New Insurance Policy';
        const modalElement = document.getElementById('addInsuranceModal');
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
        resetForm();
    }

    function showAddEnquiryModal() {
        isEnquiryMode = true;
        document.getElementById('addInsuranceModalLabel').textContent = 'Add New Enquiry';
        const modalElement = document.getElementById('addInsuranceModal');
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
        resetForm();
    }

    function resetForm() {
        currentStep = 1;
        selectedClient = null;
        selectedPet = null;
        updateSteps();
        document.getElementById('clientSearch').value = '';
        document.getElementById('clientInfo').style.display = 'none';
        document.getElementById('step1Next').style.display = 'none';
        document.getElementById('step2Next').disabled = true;
        document.getElementById('petsList').innerHTML = '';

        // Reset New Client Form fields
        document.getElementById('newClientFormSection').style.display = 'none';
        document.getElementById('clientSearchSection').style.display = 'block';
        document.getElementById('is_new_client').value = '0';
        document.getElementById('new_client_name').value = '';
        document.getElementById('new_client_email').value = '';
        document.getElementById('new_client_phone').value = '';
        document.getElementById('new_client_address').value = '';
        document.getElementById('new_pet_name').value = '';
        document.getElementById('new_pet_breed').value = '';
        document.getElementById('new_pet_dob').value = '';
        document.getElementById('new_pet_gender').value = 'Male';
        document.getElementById('new_pet_vaccination').value = '';

        // Reset Step 2 New Pet fields
        document.getElementById('is_new_pet').value = '0';
        document.getElementById('newPetFormStep2').style.display = 'none';
        document.getElementById('petsList').style.display = 'flex';
        if (document.getElementById('addNewPetBtn')) document.getElementById('addNewPetBtn').style.display = 'inline-block';
        document.getElementById('step2_pet_name').value = '';
        document.getElementById('step2_pet_breed').value = '';
        document.getElementById('step2_pet_dob').value = '';
        document.getElementById('step2_pet_gender').value = 'Male';
        document.getElementById('step2_pet_vaccination').value = '';

        // Reset dates
        document.getElementById('startDate').value = '';
        document.getElementById('endDate').value = '';
        document.getElementById('durationYears').value = 1;
    }

    function updateSteps() {
        // Update step indicators
        document.querySelectorAll('.step').forEach((step, index) => {
            if (index + 1 === currentStep) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
        });

        // Update step content
        document.querySelectorAll('.step-content').forEach((content, index) => {
            if (index + 1 === currentStep) {
                content.classList.add('active');
            } else {
                content.classList.remove('active');
            }
        });
    }

    function nextStep(step) {
        // If coming from step 1 with new client, skip step 2 (Select Pet)
        if (currentStep === 1 && document.getElementById('is_new_client').value === '1') {
            currentStep = 3;
        } else {
            currentStep = step;
        }
        updateSteps();
    }

    function prevStep(step) {
        // If going back from step 3 with new client, skip back to step 1
        if (currentStep === 3 && document.getElementById('is_new_client').value === '1') {
            currentStep = 1;
        } else {
            currentStep = step;
        }
        updateSteps();
    }

    function searchClient() {
        const phone = document.getElementById('clientSearch').value.trim();
        const errorDiv = document.getElementById('clientSearchError');

        if (!phone) {
            errorDiv.innerHTML = '<span class="text-danger small">Please enter a phone number</span>';
            errorDiv.style.display = 'block';
            return;
        }

        errorDiv.style.display = 'none';

        // AJAX call to search client
        fetch("<?= base_url('insurance/searchClient') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    phone: phone
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    selectedClient = data.client;
                    displayClientInfo(data.client);
                    loadClientPets(data.client.id);
                    document.getElementById('step1Next').style.display = 'inline-block';
                    errorDiv.style.display = 'none';
                } else {
                    errorDiv.innerHTML = `
                        <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center justify-content-between p-2 mb-0 mt-2">
                          <span class="small fw-medium"><i class="bi bi-exclamation-triangle me-2"></i>${data.message || 'Client not found'}</span>
                          <button type="button" class="btn btn-sm btn-dark px-3" onclick="showNewClientForm()">Add New Client</button>
                        </div>
                    `;
                    errorDiv.style.display = 'block';
                    document.getElementById('step1Next').style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorDiv.textContent = 'An error occurred while searching for client';
                errorDiv.style.display = 'block';
            });
    }

    function showNewClientForm() {
        document.getElementById('clientSearchSection').style.display = 'none';
        document.getElementById('newClientFormSection').style.display = 'block';
        document.getElementById('is_new_client').value = '1';
        document.getElementById('step1Next').style.display = 'inline-block';
        document.getElementById('clientInfo').style.display = 'none';

        // Auto-fill phone
        if (document.getElementById('clientSearch').value) {
            document.getElementById('new_client_phone').value = document.getElementById('clientSearch').value;
        }
    }

    function backToSearch() {
        document.getElementById('newClientFormSection').style.display = 'none';
        document.getElementById('clientSearchSection').style.display = 'block';
        document.getElementById('is_new_client').value = '0';
        document.getElementById('step1Next').style.display = 'none';
        document.getElementById('clientSearchError').style.display = 'none';
    }

    function displayClientInfo(client) {
        document.getElementById('clientName').textContent = client.full_name;
        document.getElementById('clientEmail').textContent = client.email;
        document.getElementById('clientPhone').textContent = client.phone;
        document.getElementById('clientInfo').style.display = 'block';
    }

    function loadClientPets(clientId) {
        fetch('/admin/insurance/getClientPets', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    client_id: clientId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayPets(data.pets);
                    document.getElementById('addNewPetBtn').style.display = 'inline-block';
                } else {
                    document.getElementById('petsList').innerHTML = `
                        <div class="col-12">
                            <div class="text-center p-4 border rounded bg-light">
                                <p class="text-muted mb-3">No pets found for this client.</p>
                                <button type="button" class="btn btn-primary btn-sm" onclick="showNewPetFormInStep2()">
                                    <i class="bi bi-plus-circle me-1"></i> Add First Pet
                                </button>
                            </div>
                        </div>
                    `;
                    document.getElementById('addNewPetBtn').style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('petsList').innerHTML = '<p class="text-danger">Error loading pets.</p>';
            });
    }

    function showNewPetFormInStep2() {
        document.getElementById('petsList').style.display = 'none';
        document.getElementById('newPetFormStep2').style.display = 'block';
        document.getElementById('addNewPetBtn').style.display = 'none';
        document.getElementById('is_new_pet').value = '1';
        document.getElementById('step2Next').disabled = false;
        selectedPet = null;
    }

    function hideNewPetFormInStep2() {
        document.getElementById('petsList').style.display = 'flex';
        document.getElementById('newPetFormStep2').style.display = 'none';
        document.getElementById('addNewPetBtn').style.display = 'inline-block';
        document.getElementById('is_new_pet').value = '0';
        if (!selectedPet) {
            document.getElementById('step2Next').disabled = true;
        }
    }

    function calculateAge(dob) {
        const birthDate = new Date(dob);
        const diff = Date.now() - birthDate.getTime();
        const ageDate = new Date(diff);
        return Math.abs(ageDate.getUTCFullYear() - 1970) + " yrs";
    }

    function displayPets(pets) {
        const petsList = document.getElementById('petsList');

        if (pets.length === 0) {
            petsList.innerHTML = `
            <div class="col-12">
                <div class="text-center p-4 border rounded bg-light">
                    <p class="text-muted mb-0">No pets found for this client.</p>
                </div>
            </div>
        `;
            return;
        }

        petsList.innerHTML = pets.map(pet => `
        <div class="col-md-4 col-sm-6 border-1 border-light ">
            <div class="card pet-card h-100 shadow-sm border-0"
                 onclick="selectPet(${pet.id}, this)"
                 data-pet-id="${pet.id}"
                 style="cursor:pointer;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title mb-0 fw-bold">${pet.name}</h5>
                        <span class="badge ${pet.gender === 'Male' ? 'bg-primary' : 'bg-pink'}">
                            ${pet.gender}
                        </span>
                    </div>
                    <p class="mb-1"><strong>Breed:</strong> ${pet.breed}</p>
                </div>
            </div>
        </div>
    `).join('');
    }

    function selectPet(petId, element) {
        // Remove selected class from all pets
        document.querySelectorAll('.pet-item').forEach(item => {
            item.classList.remove('selected');
        });

        // Add selected class to clicked pet
        element.classList.add('selected');

        selectedPet = petId;
        document.getElementById('step2Next').disabled = false;
    }

    // Auto-update end date based on duration
    function updateEndDate() {
        const startDate = document.getElementById('startDate').value;
        const durationYears = document.getElementById('durationYears').value;

        if (startDate && durationYears) {
            const start = new Date(startDate);
            const end = new Date(start);
            end.setFullYear(start.getFullYear() + parseInt(durationYears));

            document.getElementById('endDate').value = end.toISOString().split('T')[0];
        }
    }

    // Auto-update duration based on dates
    function updateDuration() {
        const startDateVal = document.getElementById('startDate').value;
        const endDateVal = document.getElementById('endDate').value;

        if (startDateVal && endDateVal) {
            const startDate = new Date(startDateVal);
            const endDate = new Date(endDateVal);
            if (!isNaN(startDate.getTime()) && !isNaN(endDate.getTime())) {
                const diffTime = Math.abs(endDate - startDate);
                const diffYears = Math.ceil(diffTime / (1000 * 60 * 60 * 24 * 365));
                document.getElementById('durationYears').value = diffYears || 1;
            }
        }
    }



    // Handle form submission
    document.getElementById('addInsuranceForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const isNewClient = document.getElementById('is_new_client').value === '1';
        const isNewPet = document.getElementById('is_new_pet').value === '1';

        if (!isNewClient && !isNewPet && (!selectedClient || !selectedPet)) {
            alert('Please complete all steps before submitting');
            return;
        }

        // Collect form data
        const formData = {
            is_new_client: document.getElementById('is_new_client').value,
            is_new_pet: document.getElementById('is_new_pet').value,
            coverage_type: document.getElementById('coverageType').value,
            duration_years: calculateDurationYears(),
            premium: document.getElementById('premium').value,
            start_date: document.getElementById('startDate').value,
            end_date: document.getElementById('endDate').value,
        };

        if (formData.is_new_client === '1') {
            formData.new_client_name = document.getElementById('new_client_name').value;
            formData.new_client_email = document.getElementById('new_client_email').value;
            formData.new_client_phone = document.getElementById('new_client_phone').value;
            formData.new_client_address = document.getElementById('new_client_address').value;

            formData.new_pet_name = document.getElementById('new_pet_name').value;
            formData.new_pet_breed = document.getElementById('new_pet_breed').value;
            formData.new_pet_dob = document.getElementById('new_pet_dob').value;
            formData.new_pet_gender = document.getElementById('new_pet_gender').value;
            formData.new_pet_vaccination = document.getElementById('new_pet_vaccination').value;
            formData.new_pet_aggressive = document.getElementById('new_pet_aggressive').value;
            formData.new_pet_vaccinated = document.getElementById('new_pet_vaccinated').value;

            if (!formData.new_client_name || !formData.new_client_phone || !formData.new_pet_name) {
                alert('Please fill in client name, phone and pet name.');
                return;
            }
        } else if (formData.is_new_pet === '1') {
            formData.user_id = selectedClient.id;
            formData.full_name = selectedClient.full_name;
            formData.email = selectedClient.email;
            formData.phone = selectedClient.phone;

            formData.new_pet_name = document.getElementById('step2_pet_name').value;
            formData.new_pet_breed = document.getElementById('step2_pet_breed').value;
            formData.new_pet_dob = document.getElementById('step2_pet_dob').value;
            formData.new_pet_gender = document.getElementById('step2_pet_gender').value;
            formData.new_pet_vaccination = document.getElementById('step2_pet_vaccination').value;
            formData.new_pet_aggressive = document.getElementById('step2_pet_aggressive').value;
            formData.new_pet_vaccinated = document.getElementById('step2_pet_vaccinated').value;

            if (!formData.new_pet_name) {
                alert('Please fill in the pet name.');
                return;
            }
        } else {
            formData.user_id = selectedClient.id;
            formData.full_name = selectedClient.full_name;
            formData.email = selectedClient.email;
            formData.phone = selectedClient.phone;
            formData.pet_id = selectedPet;
        }

        // Send AJAX request
        const endpoint = isEnquiryMode ? "<?= base_url('insurance/addEnquiry') ?>" : "<?= base_url('insurance/addPolicy') ?>";

        fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    alert(isEnquiryMode ? 'Enquiry added successfully!' : 'Insurance policy added successfully!');
                    const modalElement = document.getElementById('addInsuranceModal');
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    modal.hide();
                    location.reload(); // Reload to show the new policy
                } else {
                    alert('Error: ' + (data.message || 'Failed to add policy'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the policy');
            });
    });

    // Helper function to calculate duration in years
    function calculateDurationYears() {
        const startDateVal = document.getElementById('startDate').value;
        const endDateVal = document.getElementById('endDate').value;

        if (!startDateVal || !endDateVal) return 1;

        const startDate = new Date(startDateVal);
        const endDate = new Date(endDateVal);

        if (isNaN(startDate.getTime()) || isNaN(endDate.getTime())) return 1;

        const diffTime = Math.abs(endDate - startDate);
        const diffYears = Math.ceil(diffTime / (1000 * 60 * 60 * 24 * 365));

        return diffYears || 1;
    }

    // Search functionality for main table
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

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
                    $('.step2-pet-breed').html(options);
                }
            }
        });
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadBreeds();
    });
</script>

<?php echo $this->include('admin/footer') ?>
<?php echo $this->endSection() ?>