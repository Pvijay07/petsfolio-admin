<?php echo $this->extend('admin/includes/header'); ?>
<!--start content-->
<<<<<<< HEAD
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
=======

<?php echo $this->section('content'); ?>

<?php
// Helper for avatar initials and pastel colors matching modern SaaS UI
if (!function_exists('getInitialsAvatar')) {
    function getInitialsAvatar($name, $id = 0) {
        $trimmed = trim($name ?? '');
        if (empty($trimmed) || $trimmed === 'Unknown') {
            return ['text' => '??', 'bg' => '#f1f5f9', 'color' => '#64748b'];
        }
        $words = preg_split('/\s+/', $trimmed);
        if (count($words) >= 2) {
            $initials = mb_strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1));
        } else {
            $initials = mb_strtoupper(mb_substr($trimmed, 0, 2));
        }

        $palettes = [
            ['bg' => '#ede9fe', 'color' => '#6366f1'], // Indigo
            ['bg' => '#dbeafe', 'color' => '#2563eb'], // Blue
            ['bg' => '#dcfce7', 'color' => '#16a34a'], // Green
            ['bg' => '#fce7f3', 'color' => '#db2777'], // Pink
            ['bg' => '#fef3c7', 'color' => '#d97706'], // Amber
            ['bg' => '#ccfbf1', 'color' => '#0d9488'], // Teal
            ['bg' => '#e0e7ff', 'color' => '#4338ca'], // Purple
            ['bg' => '#ffedd5', 'color' => '#ea580c'], // Orange
        ];
        $idx = abs(crc32($trimmed . $id)) % count($palettes);
        return [
            'text'  => $initials,
            'bg'    => $palettes[$idx]['bg'],
            'color' => $palettes[$idx]['color'],
        ];
    }
}

// Compute summary stats for hero banner cards
$totalActive = count($insurances ?? []);
$totalPremiumSum = 0;
$comprehensiveCount = 0;

if (!empty($insurances)) {
    foreach ($insurances as $ins) {
        $totalPremiumSum += (float)($ins->amount ?? 0);
        if (stripos($ins->coverage_type ?? '', 'comp') !== false) {
            $comprehensiveCount++;
        }
    }
}
?>

<style>
  /* Modern Dashboard UI Styles matching Booking Requests */
  .nl-hero-banner {
    background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 25%, #3730a3 60%, #4f46e5 100%);
    border-radius: 20px;
    padding: 28px 32px;
    color: #ffffff;
    box-shadow: 0 10px 30px -5px rgba(79, 70, 229, 0.25);
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
  }
  .nl-hero-banner::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, rgba(255,255,255,0) 70%);
    border-radius: 50%;
    pointer-events: none;
  }
  .nl-live-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 5px 14px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 600;
    color: #ffffff;
    letter-spacing: 0.03em;
    margin-bottom: 12px;
  }
  .nl-live-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #22c55e;
    box-shadow: 0 0 10px #22c55e;
    animation: nl-pulse 2s infinite;
  }
  @keyframes nl-pulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(34, 197, 94, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
  }
  .nl-stat-card {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.16);
    border-radius: 14px;
    padding: 14px 18px;
    min-width: 115px;
    flex: 1;
    transition: transform 0.15s ease, background 0.15s ease;
  }
  .nl-stat-card:hover {
    transform: translateY(-2px);
    background: rgba(255, 255, 255, 0.12);
  }
  .nl-stat-card .label {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.72);
    margin-bottom: 4px;
    display: block;
  }
  .nl-stat-card .value {
    font-size: 24px;
    font-weight: 800;
    line-height: 1.15;
    color: #ffffff;
  }

  /* Segmented Pill Tabs */
  .nl-segmented-tabs {
    background: #eef2f6;
    padding: 4px;
    border-radius: 50px;
    display: inline-flex;
    align-items: center;
    gap: 3px;
    flex-wrap: wrap;
  }
  .nl-tab-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 18px;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 500;
    color: #64748b;
    text-decoration: none;
    transition: all 0.15s ease;
  }
  .nl-tab-pill:hover {
    color: #1e293b;
  }
  .nl-tab-pill.active {
    background: #ffffff;
    color: #4338ca;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  }
  .nl-tab-badge {
    border-radius: 50px;
    padding: 2px 8px;
    font-size: 11px;
    font-weight: 700;
  }
  .nl-tab-pill.active .nl-tab-badge {
    background: #e0e7ff;
    color: #4338ca;
  }
  .nl-tab-pill:not(.active) .nl-tab-badge {
    background: rgba(0, 0, 0, 0.06);
    color: #64748b;
  }

  /* Action Buttons */
  .nl-btn-action {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 18px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #334155;
    box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    text-decoration: none;
    cursor: pointer;
    transition: all 0.15s ease;
  }
  .nl-btn-action:hover {
    background: #f8fafc;
    color: #0f172a;
    border-color: #94a3b8;
  }
  .nl-btn-primary {
    background: #4f46e5;
    color: #ffffff;
    border-color: #4f46e5;
  }
  .nl-btn-primary:hover {
    background: #4338ca;
    color: #ffffff;
    border-color: #4338ca;
  }

  /* Filter Card */
  .nl-filter-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 16px 20px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.02);
    margin-bottom: 20px;
  }
  .nl-search-wrap {
    position: relative;
    width: 100%;
  }
  .nl-search-wrap .search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    pointer-events: none;
    font-size: 14px;
  }
  .nl-search-input {
    width: 100%;
    padding: 9px 14px 9px 38px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #f8fafc;
    font-size: 13px;
    color: #0f172a;
    transition: all 0.15s ease;
  }
  .nl-search-input:focus {
    background: #ffffff;
    border-color: #6366f1;
    outline: none;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
  }
  .nl-select {
    padding: 8px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #f8fafc;
    font-size: 13px;
    color: #334155;
    font-weight: 500;
    transition: all 0.15s ease;
  }
  .nl-select:focus {
    border-color: #6366f1;
    background: #ffffff;
    outline: none;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
  }

  /* Table Card */
  .nl-table-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    box-shadow: 0 2px 14px rgba(0,0,0,0.025);
    overflow: hidden;
  }
  .nl-table {
    width: 100%;
    margin-bottom: 0;
  }
  .nl-table thead th {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: #64748b;
    padding: 14px 18px;
    vertical-align: middle;
  }
  .nl-table tbody td {
    padding: 16px 18px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
    font-size: 13px;
  }
  .nl-table tbody tr {
    transition: background 0.12s ease;
  }
  .nl-table tbody tr:hover {
    background: rgba(99, 102, 241, 0.025);
  }

  /* Recipient Avatar Initials */
  .nl-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 12.5px;
    flex-shrink: 0;
  }

  .badge-service {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 11px;
    border-radius: 50px;
    font-size: 11.5px;
    font-weight: 600;
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
  }
  .status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 50px;
    font-size: 11.5px;
    font-weight: 600;
  }
  .status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
  }
  .status-active { background: #dcfce7; color: #15803d; }
  .status-active .status-dot { background: #16a34a; }

  .nl-action-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.15s ease;
    background: #ffffff;
    text-decoration: none;
  }
  .nl-action-btn.view { color: #4f46e5; }
  .nl-action-btn.view:hover { background: #4f46e5; color: #ffffff; border-color: #4f46e5; }

  /* Pagination */
  .nl-page-btn {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    text-decoration: none;
    border: 1px solid transparent;
    transition: all 0.12s ease;
    background: transparent;
    cursor: pointer;
  }
  .nl-page-btn:hover:not(.active):not(:disabled) {
    background: #f1f5f9;
    color: #0f172a;
  }
  .nl-page-btn.active {
    background: #4f46e5;
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(79, 70, 229, 0.3);
    border-color: #4f46e5;
  }
  .nl-page-btn:disabled, .nl-page-btn.disabled {
    color: #cbd5e1;
    cursor: not-allowed;
    pointer-events: none;
  }
  .nl-page-btn.nav-btn {
    border: 1px solid #e2e8f0;
    background: #ffffff;
  }

  /* Modal Styling */
  .modal-content {
    border-radius: 20px !important;
    overflow: hidden;
  }
  .modal.show .modal-dialog {
    animation: modalSlideIn 0.25s ease-out;
  }
  @keyframes modalSlideIn {
    0% { transform: translateY(20px) scale(0.98); opacity: 0; }
    100% { transform: translateY(0) scale(1); opacity: 1; }
  }
  .modal-header-gradient {
    background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 25%, #3730a3 60%, #4f46e5 100%) !important;
    padding: 18px 24px;
    border: 0;
    color: #ffffff;
  }
  .modal-icon-badge {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.15);
    color: #ffffff;
    font-size: 18px;
    flex-shrink: 0;
  }
  .modal-info-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px 16px;
  }
</style>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?php echo $this->include('admin/nav_bar') ?>
  </div>

  <div class="container-fluid px-4 content-inner pb-5" id="page_layout">

    <!-- Hero Banner with Stats matching Modern Dashboard -->
    <div class="nl-hero-banner">
      <div class="row align-items-center g-4">
        <div class="col-lg-7">
          <div class="nl-live-pill">
            <span class="nl-live-dot"></span>
            <span>Active Policy Ledger</span>
          </div>
          <h2 class="fw-bold text-white mb-2" style="font-size: 30px; letter-spacing: -0.02em;">Active Insurance Policies</h2>
          <p class="mb-0 text-white text-opacity-75" style="font-size: 13.5px; max-width: 560px; line-height: 1.55;">
            Manage and audit active pet health and life coverage, track policy durations, verify premium receipts, and inspect insured pet profiles.
          </p>
        </div>

        <div class="col-lg-5">
          <div class="d-flex align-items-center gap-2 flex-wrap justify-content-lg-end">
            <div class="nl-stat-card">
              <span class="label">Active Policies</span>
              <div class="value"><?= number_format($totalActive) ?></div>
            </div>
            <div class="nl-stat-card">
              <span class="label">Annual Premium</span>
              <div class="value" style="color: #6ee7b7; font-size: 20px;">₹<?= number_format($totalPremiumSum) ?></div>
            </div>
            <div class="nl-stat-card">
              <span class="label">Comprehensive</span>
              <div class="value" style="color: #93c5fd;"><?= number_format($comprehensiveCount) ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Segmented Tabs & Action Bar -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
      <div class="nl-segmented-tabs">
        <a href="<?= base_url('enquiries') ?>" class="nl-tab-pill">
          <i class="bi bi-inbox-fill"></i>
          <span>New Enquiries</span>
          <span class="nl-tab-badge"><?= $tabCounts['enquiries'] ?? 0 ?></span>
        </a>
        <a href="<?= base_url('quotations') ?>" class="nl-tab-pill">
          <i class="bi bi-file-earmark-text"></i>
          <span>Quotations</span>
          <span class="nl-tab-badge"><?= $tabCounts['quotations'] ?? 0 ?></span>
        </a>
        <a href="<?= base_url('active') ?>" class="nl-tab-pill active">
          <i class="bi bi-shield-check"></i>
          <span>Active Policies</span>
          <span class="nl-tab-badge"><?= $tabCounts['active'] ?? $totalActive ?></span>
        </a>
        <a href="<?= base_url('expiring') ?>" class="nl-tab-pill">
          <i class="bi bi-clock-history"></i>
          <span>Expiring Soon</span>
          <span class="nl-tab-badge"><?= $tabCounts['expiring'] ?? 0 ?></span>
        </a>
        <a href="<?= base_url('claims') ?>" class="nl-tab-pill">
          <i class="bi bi-clipboard2-pulse"></i>
          <span>Insurance Claims</span>
          <span class="nl-tab-badge"><?= $tabCounts['claims'] ?? 0 ?></span>
        </a>
      </div>

      <div class="d-flex align-items-center gap-2">
        <button type="button" class="nl-btn-action nl-btn-primary" onclick="openAddInsuranceModal()">
          <i class="bi bi-plus-lg"></i>
          <span>Add New Policy</span>
        </button>
        <button type="button" class="nl-btn-action" onclick="exportActivePoliciesToCSV()">
          <i class="bi bi-download"></i>
          <span>Export CSV</span>
        </button>
      </div>
    </div>

    <!-- Modern Search and Filter Toolbar -->
    <div class="nl-filter-card">
      <div class="row g-3 align-items-center">
        <div class="col-lg-6 col-md-5">
          <div class="nl-search-wrap">
            <i class="bi bi-search search-icon"></i>
            <input type="text" class="nl-search-input" id="searchActivePolicies"
              placeholder="Search by client, phone, policy #, pet name, breed...">
          </div>
        </div>
        <div class="col-lg-3 col-md-4">
          <select class="nl-select w-100" id="coverageFilter">
            <option value="all">All Coverage Types</option>
            <option value="comprehensive">Comprehensive</option>
            <option value="standard">Standard</option>
            <option value="basic">Basic</option>
          </select>
        </div>
        <div class="col-lg-3 col-md-3">
          <div class="d-flex align-items-center justify-content-md-end gap-2">
            <label class="text-muted small mb-0 fw-semibold">Show</label>
            <select class="nl-select" style="width: 80px;" id="entriesPerPage">
              <option value="10">10</option>
              <option value="25">25</option>
              <option value="50">50</option>
              <option value="100">100</option>
              <option value="all">All</option>
            </select>
            <label class="text-muted small mb-0 fw-semibold">entries</label>
          </div>
        </div>
      </div>
    </div>

    <!-- Active Policies Data Table Card -->
    <div class="nl-table-card">
      <div class="table-responsive">
        <table class="table nl-table mb-0" id="activeTable">
          <thead>
            <tr>
              <th class="text-center" style="width: 60px;">S.No.</th>
              <th>Client Details</th>
              <th>Pet Profile</th>
              <th>Policy Info</th>
              <th>Policy Period</th>
              <th class="text-end">Premium</th>
              <th class="text-center">Status</th>
              <th class="text-center" style="width: 90px;">Actions</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            <?php if (!empty($insurances)): ?>
              <?php
              $sno = 1;
              foreach ($insurances as $ins):
                $avatar = getInitialsAvatar($ins->full_name ?? 'Client', $sno);
                $startDate = !empty($ins->start_date) ? date('d M Y', strtotime($ins->start_date)) : 'N/A';
                $endDate = !empty($ins->end_date) ? date('d M Y', strtotime($ins->end_date)) : 'N/A';
                $formattedAmount = number_format((float)($ins->amount ?? 0), 2);
                $coverageLower = strtolower($ins->coverage_type ?? 'standard');

                $policyJson = json_encode([
                    "policyNumber"   => $ins->policy_number ?? 'N/A',
                    "clientName"     => $ins->full_name ?? 'N/A',
                    "contact"        => $ins->phone ?? 'N/A',
                    "email"          => $ins->email ?? 'N/A',
                    "coverageType"   => $ins->coverage_type ?? 'Standard',
                    "startDate"      => $startDate,
                    "expiryDate"     => $endDate,
                    "premium"        => '₹' . $formattedAmount,
                    "status"         => 'Active',
                    "petName"        => $ins->name ?? 'N/A',
                    "breed"          => $ins->breed ?? 'N/A',
                    "age"            => $ins->dob ?? 'N/A',
                    "gender"         => $ins->gender ?? 'N/A',
                    "vaccination"    => $ins->last_vaccination ?? 'N/A',
                    "remarks"        => $ins->remarks ?? ''
                ]);
              ?>
                <tr class="policy-row"
                    data-coverage="<?= $coverageLower ?>"
                    data-policy="<?= htmlspecialchars($policyJson, ENT_QUOTES, 'UTF-8') ?>">
                  
                  <td class="text-center fw-bold text-muted" style="font-size: 12px;"><?= $sno ?></td>
                  
                  <td>
                    <div class="d-flex align-items-center gap-3">
                      <div class="nl-avatar" style="background: <?= $avatar['bg'] ?>; color: <?= $avatar['color'] ?>;">
                        <?= $avatar['text'] ?>
                      </div>
                      <div>
                        <div class="fw-bold text-dark mb-0"><?= esc($ins->full_name ?? 'Unknown') ?></div>
                        <div class="d-flex align-items-center text-muted small mt-0.5">
                          <i class="bi bi-telephone me-1" style="font-size: 11px;"></i>
                          <span><?= esc($ins->phone ?? 'N/A') ?></span>
                        </div>
                        <?php if (!empty($ins->remarks)): ?>
                          <div class="text-muted small mt-1" style="font-size: 11px; color: #6366f1 !important;">
                            <i class="bi bi-chat-left-text me-1"></i>
                            <?= htmlspecialchars(mb_substr($ins->remarks, 0, 45)) . (mb_strlen($ins->remarks) > 45 ? '...' : '') ?>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </td>

                  <td>
                    <div>
                      <div class="fw-semibold text-dark"><?= esc($ins->name ?? 'Insured Pet') ?></div>
                      <div class="text-muted small">
                        <span><?= esc($ins->breed ?? 'Breed N/A') ?></span>
                        <?php if (!empty($ins->gender)): ?>
                          <span class="mx-1">•</span><span><?= esc(ucfirst($ins->gender)) ?></span>
                        <?php endif; ?>
                      </div>
                    </div>
                  </td>

                  <td>
                    <div class="d-flex flex-column gap-1">
                      <span class="font-monospace fw-bold text-dark" style="font-size: 12px;">#<?= esc($ins->policy_number ?? 'N/A') ?></span>
                      <div class="badge-service" style="padding: 2px 8px; font-size: 10.5px;">
                        <i class="bi bi-shield-check"></i>
                        <span><?= esc($ins->coverage_type ?? 'Standard') ?></span>
                      </div>
                    </div>
                  </td>

                  <td>
                    <div class="d-flex flex-column" style="font-size: 12px;">
                      <div class="text-dark fw-medium">
                        <i class="bi bi-calendar-check text-success me-1"></i>
                        <span><?= $startDate ?></span>
                      </div>
                      <div class="text-muted mt-0.5">
                        <i class="bi bi-calendar-x text-muted me-1"></i>
                        <span>to <?= $endDate ?></span>
                      </div>
                    </div>
                  </td>

                  <td class="text-end">
                    <div class="fw-bold text-dark fs-6" style="color: #0f172a !important;">
                      ₹<?= $formattedAmount ?>
                    </div>
                  </td>

                  <td class="text-center">
                    <span class="status-pill status-active">
                      <span class="status-dot"></span>
                      <span>Active</span>
                    </span>
                  </td>

                  <td class="text-center">
                    <button type="button" class="nl-action-btn view" title="View Policy Details"
                      onclick="showPolicyDetails(<?= htmlspecialchars($policyJson, ENT_QUOTES, 'UTF-8') ?>)">
                      <i class="bi bi-eye"></i>
                    </button>
                  </td>
                </tr>
              <?php
                $sno++;
              endforeach;
              ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center py-5 text-muted">
                  <i class="bi bi-shield-slash fs-2 d-block mb-2 text-secondary"></i>
                  No active insurance policies found
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Modern Table Footer & Pagination -->
      <div class="d-flex flex-wrap justify-content-between align-items-center px-4 py-3 border-top" style="background: #fafafa;">
        <div class="text-muted small fw-medium" id="entriesInfo">
          Showing 1 to <?= min(10, $totalActive) ?> of <?= $totalActive ?> entries
        </div>
        <div class="d-flex align-items-center gap-1" id="paginationControls">
          <button class="nl-page-btn nav-btn" id="prevPage" disabled>
            <i class="bi bi-chevron-left"></i>
          </button>
          <div class="d-flex align-items-center gap-1" id="pageNumbers"></div>
          <button class="nl-page-btn nav-btn" id="nextPage">
            <i class="bi bi-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- ==========================================
     MODALS (Matching Booking Requests UI)
========================================== -->

<!-- 1. Policy Details Modal -->
<div class="modal fade" id="policyDetailsModal" tabindex="-1" aria-labelledby="policyDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden" style="max-height: 85vh;">
      <div class="modal-header modal-header-gradient">
        <div class="d-flex align-items-center gap-2.5">
          <div class="modal-icon-badge">
            <i class="bi bi-shield-check text-warning"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold text-white mb-0" id="policyDetailsModalLabel">Active Policy Certificate</h5>
            <span class="text-white-50 small" id="policyHeaderSub">Policy #PF-XXXXXX</span>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white opacity-75 hover-opacity-100" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4" style="overflow-y: auto; flex: 1;">
        <div class="row g-3">
          <div class="col-md-6">
            <div class="modal-info-card h-100">
              <h6 class="fw-bold text-dark mb-3"><i class="bi bi-person me-1.5 text-primary"></i>Policyholder Profile</h6>
              <div class="d-flex flex-column gap-2 small">
                <div class="d-flex justify-content-between"><span class="text-muted">Client Name:</span><span class="fw-semibold text-dark" id="modalPolicyClient"></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Phone Number:</span><span class="fw-semibold text-dark" id="modalPolicyPhone"></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Email Address:</span><span class="fw-semibold text-dark" id="modalPolicyEmail"></span></div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="modal-info-card h-100">
              <h6 class="fw-bold text-dark mb-3"><i class="bi bi-heart-pulse me-1.5 text-danger"></i>Insured Pet Profile</h6>
              <div class="d-flex flex-column gap-2 small">
                <div class="d-flex justify-content-between"><span class="text-muted">Pet Name:</span><span class="fw-semibold text-dark" id="modalPetName"></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Breed:</span><span class="fw-semibold text-dark" id="modalPetBreed"></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">DOB / Age:</span><span class="fw-semibold text-dark" id="modalPetAge"></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Gender:</span><span class="fw-semibold text-dark" id="modalPetGender"></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Vaccination:</span><span class="fw-semibold text-dark" id="modalPetVacc"></span></div>
              </div>
            </div>
          </div>

          <div class="col-12">
            <div class="modal-info-card">
              <h6 class="fw-bold text-dark mb-3"><i class="bi bi-shield-lock me-1.5 text-success"></i>Policy Term & Coverage Benefits</h6>
              <div class="row g-3 small">
                <div class="col-sm-3"><span class="text-muted d-block">Coverage Plan:</span><span class="fw-semibold text-dark" id="modalCoverageType"></span></div>
                <div class="col-sm-3"><span class="text-muted d-block">Start Date:</span><span class="fw-semibold text-dark" id="modalStartDate"></span></div>
                <div class="col-sm-3"><span class="text-muted d-block">Expiry Date:</span><span class="fw-semibold text-dark" id="modalExpiryDate"></span></div>
                <div class="col-sm-3"><span class="text-muted d-block">Annual Premium:</span><span class="fw-bold text-dark fs-6" id="modalPremium" style="color: #4f46e5 !important;"></span></div>
                <div class="col-12 pt-2 border-top" id="modalPolicyRemarksWrap" style="display: none;">
                  <span class="text-muted d-block">Policy Notes / Remarks:</span>
                  <span class="text-dark" id="modalPolicyRemarks"></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer border-top bg-light px-4 py-2.5 d-flex justify-content-between">
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
          Close
        </button>
        <button type="button" class="btn btn-sm btn-primary rounded-pill px-4 fw-semibold" onclick="window.print()">
          <i class="bi bi-printer me-1.5"></i>Print Certificate
        </button>
      </div>
    </div>
  </div>
</div>

<!-- 2. Add New Policy Wizard Modal -->
<div class="modal fade" id="addInsuranceModal" tabindex="-1" aria-labelledby="addInsuranceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden" style="max-height: 88vh;">
      <div class="modal-header modal-header-gradient">
        <div class="d-flex align-items-center gap-2.5">
          <div class="modal-icon-badge">
            <i class="bi bi-shield-plus text-warning"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold text-white mb-0" id="addInsuranceModalLabel">Issue Active Insurance Policy</h5>
            <span class="text-white-50 small">Direct policy issuance and quotation activation</span>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white opacity-75 hover-opacity-100" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4" style="overflow-y: auto; flex: 1;">
        <!-- Step 1: Search Client -->
        <div id="policySearchStep">
          <div class="modal-info-card mb-4">
            <label class="form-label fw-bold text-dark small text-uppercase mb-2">Search Registered Client by Phone</label>
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-telephone"></i></span>
              <input type="text" class="form-control border-start-0" id="policySearchPhone" placeholder="Enter 10-digit mobile number">
              <button class="btn btn-primary fw-semibold px-4" type="button" onclick="searchPolicyClient()">
                <i class="bi bi-search me-1"></i>Search
              </button>
            </div>
            <div id="policySearchFeedback" class="mt-2 small"></div>
          </div>
        </div>

        <!-- Step 1b: New Client Form -->
        <div id="policyNewClientSection" style="display: none;">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0 text-indigo"><i class="bi bi-person-plus me-1.5"></i>New Policyholder</h6>
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" onclick="backToPolicySearch()">
              <i class="bi bi-arrow-left me-1"></i>Back to Search
            </button>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">FULL NAME</label>
              <input type="text" class="form-control rounded-3" id="pol_new_name" placeholder="Client Name">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">EMAIL ADDRESS</label>
              <input type="email" class="form-control rounded-3" id="pol_new_email" placeholder="client@example.com">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">PHONE NUMBER</label>
              <input type="text" class="form-control rounded-3" id="pol_new_phone" placeholder="10-digit phone">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">ADDRESS</label>
              <textarea class="form-control rounded-3" id="pol_new_address" rows="1" placeholder="Address & City"></textarea>
            </div>
          </div>

          <h6 class="fw-bold mb-3 text-indigo mt-4"><i class="bi bi-heart-pulse me-1.5"></i>Pet Profile Details</h6>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">PET NAME</label>
              <input type="text" class="form-control rounded-3" id="pol_new_pet_name" placeholder="Pet Name">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">BREED</label>
              <select class="form-select rounded-3" id="pol_new_pet_breed"><option value="">Select Breed</option></select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">AGGRESSIVE</label>
              <select class="form-select rounded-3" id="pol_new_pet_aggressive"><option value="No">No</option><option value="Yes">Yes</option></select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">VACCINATED</label>
              <select class="form-select rounded-3" id="pol_new_pet_vaccinated"><option value="Yes">Yes</option><option value="No">No</option></select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold small text-muted">DOB</label>
              <input type="date" class="form-control rounded-3" id="pol_new_pet_dob">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold small text-muted">GENDER</label>
              <select class="form-select rounded-3" id="pol_new_pet_gender"><option value="Male">Male</option><option value="Female">Female</option></select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold small text-muted">LAST VACCINATION</label>
              <input type="date" class="form-control rounded-3" id="pol_new_pet_vacc">
            </div>
          </div>
          <hr class="my-4">
        </div>

        <!-- Step 2: Policy & Premium Form -->
        <form id="policyForm" style="display: none;">
          <input type="hidden" id="pol_foundClientId">
          <input type="hidden" id="pol_is_new_client" value="0">

          <div id="pol_existingClientDisplay">
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label fw-bold small text-muted">CLIENT NAME</label>
                <input type="text" class="form-control bg-light rounded-3" id="pol_displayClientName" readonly>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-bold small text-muted">CLIENT EMAIL</label>
                <input type="email" class="form-control bg-light rounded-3" id="pol_displayClientEmail" readonly>
                <input type="hidden" id="pol_displayClientPhone">
              </div>
            </div>

            <div class="row g-3 mb-3">
              <div class="col-md-12">
                <label class="form-label fw-bold small text-muted">SELECT PET</label>
                <select class="form-select rounded-3" id="pol_petSelect" required>
                  <option value="">Select a pet</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">COVERAGE TYPE</label>
              <select class="form-select rounded-3" id="pol_coverage_type" required>
                <option value="Comprehensive">Comprehensive Cover</option>
                <option value="Standard">Standard Cover</option>
                <option value="Basic">Basic Accidental Cover</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">ANNUAL PREMIUM (₹)</label>
              <div class="input-group">
                <span class="input-group-text bg-white">₹</span>
                <input type="number" class="form-control rounded-end-3" id="pol_premium" placeholder="e.g. 5999" required>
              </div>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <label class="form-label fw-bold small text-muted">DURATION (YEARS)</label>
              <select class="form-select rounded-3" id="pol_duration_years" onchange="calculatePolicyEndDate()">
                <option value="1">1 Year</option>
                <option value="2">2 Years</option>
                <option value="3">3 Years</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold small text-muted">START DATE</label>
              <input type="date" class="form-control rounded-3" id="pol_start_date" required onchange="calculatePolicyEndDate()">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold small text-muted">END DATE</label>
              <input type="date" class="form-control bg-light rounded-3" id="pol_end_date" readonly required>
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer border-top bg-light px-4 py-2.5 d-flex justify-content-between">
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
          Cancel
        </button>
        <button type="button" class="btn btn-sm btn-primary rounded-pill px-4 fw-semibold" id="submitPolicyBtn" style="display: none;" onclick="submitActivePolicy()">
          <i class="bi bi-shield-check me-1.5"></i>Issue Active Policy
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ==========================================
     JAVASCRIPT: Pagination & Actions
========================================== -->
<script>
class ActivePoliciesPaginationManager {
    constructor() {
        this.currentPage = 1;
        this.entriesPerPage = 10;
        this.allRows = Array.from(document.querySelectorAll('.policy-row'));
        this.filteredRows = this.allRows;
        this.totalEntries = this.allRows.length;
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.updateDisplay();
    }

    setupEventListeners() {
        // Entries per page
        const entriesSelect = document.getElementById('entriesPerPage');
        if (entriesSelect) {
            entriesSelect.addEventListener('change', (e) => {
                const val = e.target.value;
                this.entriesPerPage = val === 'all' ? this.totalEntries : parseInt(val);
                this.currentPage = 1;
                this.updateDisplay();
            });
        }

        // Prev page
        const prevBtn = document.getElementById('prevPage');
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.updateDisplay();
                }
            });
        }

        // Next page
        const nextBtn = document.getElementById('nextPage');
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                const totalPages = this.getTotalPages();
                if (this.currentPage < totalPages) {
                    this.currentPage++;
                    this.updateDisplay();
                }
            });
        }

        // Search input
        const searchInput = document.getElementById('searchActivePolicies');
        if (searchInput) {
            searchInput.addEventListener('input', () => this.applyCombinedFilters());
        }

        // Coverage filter
        const coverageFilter = document.getElementById('coverageFilter');
        if (coverageFilter) {
            coverageFilter.addEventListener('change', () => this.applyCombinedFilters());
        }
    }

    getTotalPages() {
        return Math.ceil(this.filteredRows.length / this.entriesPerPage) || 1;
    }

    applyCombinedFilters() {
        const query = (document.getElementById('searchActivePolicies')?.value || '').toLowerCase().trim();
        const coverage = (document.getElementById('coverageFilter')?.value || 'all').toLowerCase().trim();

        this.filteredRows = this.allRows.filter(row => {
            const text = row.textContent.toLowerCase();
            const matchesQuery = !query || text.includes(query);
            const rowCoverage = (row.dataset.coverage || '').toLowerCase();
            const matchesCoverage = coverage === 'all' || rowCoverage === coverage || rowCoverage.includes(coverage);
            return matchesQuery && matchesCoverage;
        });

        this.currentPage = 1;
        this.updateDisplay();
    }

    updateDisplay() {
        const start = (this.currentPage - 1) * this.entriesPerPage;
        const end = Math.min(start + this.entriesPerPage, this.filteredRows.length);

        this.allRows.forEach(row => row.style.display = 'none');

        for (let i = start; i < end; i++) {
            if (this.filteredRows[i]) {
                this.filteredRows[i].style.display = '';
            }
        }

        // Update info counter
        const entriesInfo = document.getElementById('entriesInfo');
        if (entriesInfo) {
            if (this.filteredRows.length === 0) {
                entriesInfo.textContent = 'Showing 0 entries';
            } else {
                entriesInfo.textContent = `Showing ${start + 1} to ${end} of ${this.filteredRows.length} entries`;
            }
        }

        this.updatePaginationControls();
    }

    updatePaginationControls() {
        const totalPages = this.getTotalPages();
        const prevBtn = document.getElementById('prevPage');
        const nextBtn = document.getElementById('nextPage');
        const pageNumbersContainer = document.getElementById('pageNumbers');

        if (prevBtn) prevBtn.disabled = this.currentPage === 1;
        if (nextBtn) nextBtn.disabled = this.currentPage === totalPages || this.filteredRows.length === 0;

        if (!pageNumbersContainer) return;

        let pageHtml = '';
        const maxPages = 5;

        if (totalPages <= maxPages) {
            for (let i = 1; i <= totalPages; i++) {
                pageHtml += this.getPageButtonHtml(i);
            }
        } else {
            if (this.currentPage <= 3) {
                for (let i = 1; i <= 4; i++) pageHtml += this.getPageButtonHtml(i);
                pageHtml += `<span class="nl-page-btn disabled" disabled>...</span>`;
                pageHtml += this.getPageButtonHtml(totalPages);
            } else if (this.currentPage >= totalPages - 2) {
                pageHtml += this.getPageButtonHtml(1);
                pageHtml += `<span class="nl-page-btn disabled" disabled>...</span>`;
                for (let i = totalPages - 3; i <= totalPages; i++) pageHtml += this.getPageButtonHtml(i);
            } else {
                pageHtml += this.getPageButtonHtml(1);
                pageHtml += `<span class="nl-page-btn disabled" disabled>...</span>`;
                for (let i = this.currentPage - 1; i <= this.currentPage + 1; i++) pageHtml += this.getPageButtonHtml(i);
                pageHtml += `<span class="nl-page-btn disabled" disabled>...</span>`;
                pageHtml += this.getPageButtonHtml(totalPages);
            }
        }

        pageNumbersContainer.innerHTML = pageHtml;

        pageNumbersContainer.querySelectorAll('.page-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.currentPage = parseInt(e.target.dataset.page);
                this.updateDisplay();
            });
        });
    }

    getPageButtonHtml(num) {
        const activeClass = num === this.currentPage ? 'active' : '';
        return `<button class="nl-page-btn ${activeClass} page-btn" data-page="${num}">${num}</button>`;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    window.activePoliciesPagination = new ActivePoliciesPaginationManager();
});

// View Policy Details
function showPolicyDetails(policy) {
    if (typeof policy === 'string') {
        try { policy = JSON.parse(policy); } catch(e){}
    }

    document.getElementById('policyHeaderSub').textContent = `Policy #${policy.policyNumber || 'N/A'}`;
    document.getElementById('modalPolicyClient').textContent = policy.clientName || 'N/A';
    document.getElementById('modalPolicyPhone').textContent = policy.contact || 'N/A';
    document.getElementById('modalPolicyEmail').textContent = policy.email || 'N/A';

    document.getElementById('modalPetName').textContent = policy.petName || 'N/A';
    document.getElementById('modalPetBreed').textContent = policy.breed || 'N/A';
    document.getElementById('modalPetAge').textContent = policy.age || 'N/A';
    document.getElementById('modalPetGender').textContent = policy.gender || 'N/A';
    document.getElementById('modalPetVacc').textContent = policy.vaccination || 'N/A';

    document.getElementById('modalCoverageType').textContent = policy.coverageType || 'Comprehensive';
    document.getElementById('modalStartDate').textContent = policy.startDate || 'N/A';
    document.getElementById('modalExpiryDate').textContent = policy.expiryDate || 'N/A';
    document.getElementById('modalPremium').textContent = policy.premium || '₹0.00';

    if (policy.remarks) {
        document.getElementById('modalPolicyRemarks').textContent = policy.remarks;
        document.getElementById('modalPolicyRemarksWrap').style.display = 'block';
    } else {
        document.getElementById('modalPolicyRemarksWrap').style.display = 'none';
    }

    const modalEl = document.getElementById('policyDetailsModal');
    let bsModal = bootstrap.Modal.getInstance(modalEl);
    if (!bsModal) bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
}

// Add Policy Wizard Logic
function openAddInsuranceModal() {
    const modalEl = document.getElementById('addInsuranceModal');
    let bsModal = bootstrap.Modal.getInstance(modalEl);
    if (!bsModal) bsModal = new bootstrap.Modal(modalEl);

    const today = new Date().toISOString().split('T')[0];
    const startDateInput = document.getElementById('pol_start_date');
    if (startDateInput) {
        startDateInput.value = today;
        calculatePolicyEndDate();
    }

    bsModal.show();
}

function searchPolicyClient() {
    const phone = document.getElementById('policySearchPhone').value.trim();
    const feedback = document.getElementById('policySearchFeedback');

    if (!phone || phone.length < 10) {
        feedback.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-circle me-1"></i>Please enter a valid 10-digit phone number.</span>';
        return;
    }

    feedback.innerHTML = '<span class="text-muted"><i class="bi bi-arrow-repeat spin me-1"></i>Searching client database...</span>';

    fetch('<?= base_url('insurance/searchClient') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ phone: phone })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && data.client) {
            feedback.innerHTML = `<span class="text-success"><i class="bi bi-check-circle me-1"></i>Client found: <strong>${data.client.full_name}</strong></span>`;
            document.getElementById('pol_foundClientId').value = data.client.id;
            document.getElementById('pol_displayClientName').value = data.client.full_name;
            document.getElementById('pol_displayClientEmail').value = data.client.email || '';
            document.getElementById('pol_displayClientPhone').value = data.client.phone || '';
            document.getElementById('pol_is_new_client').value = '0';

            loadPolicyClientPets(data.client.id);
            document.getElementById('policyForm').style.display = 'block';
            document.getElementById('policyNewClientSection').style.display = 'none';
            document.getElementById('submitPolicyBtn').style.display = 'inline-flex';
        } else {
            feedback.innerHTML = `
                <div class="alert alert-warning py-2 px-3 small mt-2 mb-0 d-flex justify-content-between align-items-center">
                  <span>Client not registered with this phone.</span>
                  <button type="button" class="btn btn-sm btn-dark rounded-pill" onclick="showPolicyNewClientForm('${phone}')">
                    <i class="bi bi-plus me-1"></i>Create New Client
                  </button>
                </div>`;
        }
    });
}

function showPolicyNewClientForm(phone = '') {
    document.getElementById('policyNewClientSection').style.display = 'block';
    document.getElementById('policySearchStep').style.display = 'none';
    document.getElementById('policyForm').style.display = 'block';
    document.getElementById('pol_existingClientDisplay').style.display = 'none';
    document.getElementById('pol_is_new_client').value = '1';
    document.getElementById('pol_new_phone').value = phone;
    document.getElementById('submitPolicyBtn').style.display = 'inline-flex';
    loadPolicyDogBreeds();
}

function backToPolicySearch() {
    document.getElementById('policyNewClientSection').style.display = 'none';
    document.getElementById('policySearchStep').style.display = 'block';
    document.getElementById('policyForm').style.display = 'none';
    document.getElementById('pol_existingClientDisplay').style.display = 'block';
    document.getElementById('pol_is_new_client').value = '0';
    document.getElementById('submitPolicyBtn').style.display = 'none';
}

function loadPolicyDogBreeds() {
    const breedSelect = document.getElementById('pol_new_pet_breed');
    if (breedSelect.options.length > 1) return;

    fetch('<?= base_url('insurance/getDogBreeds') ?>')
    .then(r => r.json())
    .then(data => {
        if (data.success && data.breeds) {
            data.breeds.forEach(b => {
                const opt = document.createElement('option');
                opt.value = b.value;
                opt.textContent = b.value;
                breedSelect.appendChild(opt);
            });
        }
    });
}

function loadPolicyClientPets(clientId) {
    const petSelect = document.getElementById('pol_petSelect');
    petSelect.innerHTML = '<option value="">Loading pets...</option>';

    fetch('<?= base_url('insurance/getClientPets') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ client_id: clientId })
    })
    .then(r => r.json())
    .then(data => {
        petSelect.innerHTML = '<option value="">Select a pet</option>';
        if (data.success && data.pets && data.pets.length > 0) {
            data.pets.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = `${p.name} (${p.breed || 'Dog'})`;
                petSelect.appendChild(opt);
            });
        } else {
            petSelect.innerHTML = '<option value="">No registered pets found</option>';
        }
    });
}

function calculatePolicyEndDate() {
    const startDate = document.getElementById('pol_start_date').value;
    const duration = parseInt(document.getElementById('pol_duration_years').value) || 1;
    if (startDate) {
        const start = new Date(startDate);
        const end = new Date(start.setFullYear(start.getFullYear() + duration));
        document.getElementById('pol_end_date').value = end.toISOString().split('T')[0];
    }
}

function submitActivePolicy() {
    const isNew = document.getElementById('pol_is_new_client').value === '1';
    const premium = document.getElementById('pol_premium').value;

    if (!premium || parseFloat(premium) <= 0) {
        alert('Please enter a valid premium amount.');
        return;
    }

    const payload = {
        is_new_client: isNew ? 1 : 0,
        coverage_type: document.getElementById('pol_coverage_type').value,
        duration_years: document.getElementById('pol_duration_years').value,
        start_date: document.getElementById('pol_start_date').value,
        end_date: document.getElementById('pol_end_date').value,
        premium: premium
    };

    if (isNew) {
        payload.new_client_name = document.getElementById('pol_new_name').value.trim();
        payload.new_client_email = document.getElementById('pol_new_email').value.trim();
        payload.new_client_phone = document.getElementById('pol_new_phone').value.trim();
        payload.new_client_address = document.getElementById('pol_new_address').value.trim();
        payload.new_pet_name = document.getElementById('pol_new_pet_name').value.trim();
        payload.new_pet_breed = document.getElementById('pol_new_pet_breed').value;
        payload.new_pet_aggressive = document.getElementById('pol_new_pet_aggressive').value;
        payload.new_pet_vaccinated = document.getElementById('pol_new_pet_vaccinated').value;
        payload.new_pet_dob = document.getElementById('pol_new_pet_dob').value;
        payload.new_pet_gender = document.getElementById('pol_new_pet_gender').value;
        payload.new_pet_vaccination = document.getElementById('pol_new_pet_vacc').value;

        if (!payload.new_client_name || !payload.new_client_phone || !payload.new_pet_name) {
            alert('Please fill all required client and pet details.');
            return;
        }
    } else {
        payload.user_id = document.getElementById('pol_foundClientId').value;
        payload.full_name = document.getElementById('pol_displayClientName').value;
        payload.email = document.getElementById('pol_displayClientEmail').value;
        payload.phone = document.getElementById('pol_displayClientPhone').value;
        payload.pet_id = document.getElementById('pol_petSelect').value;

        if (!payload.pet_id) {
            alert('Please select a pet.');
            return;
        }
    }

    fetch('<?= base_url('insurance/addPolicy') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {
        if (data.status) {
            alert('Insurance policy issued successfully!');
            location.reload();
        } else {
            alert(data.message || 'Error issuing policy.');
        }
    });
}

function exportActivePoliciesToCSV() {
    const rows = Array.from(document.querySelectorAll('#activeTable tbody tr.policy-row'));
    if (!rows.length) {
        alert('No active policies to export.');
        return;
    }

    let csv = 'S.No,Policy Number,Client Name,Phone,Email,Pet Name,Breed,Coverage,Start Date,Expiry Date,Premium,Status\n';
    rows.forEach((r, idx) => {
        const d = JSON.parse(r.dataset.policy || '{}');
        csv += `"${idx + 1}","${d.policyNumber || ''}","${d.clientName || ''}","${d.contact || ''}","${d.email || ''}","${d.petName || ''}","${d.breed || ''}","${d.coverageType || ''}","${d.startDate || ''}","${d.expiryDate || ''}","${d.premium || ''}","${d.status || ''}"\n`;
    });

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `active_insurance_policies_${new Date().toISOString().split('T')[0]}.csv`;
    link.click();
}
</script>

<?php echo $this->endSection(); ?>
>>>>>>> origin/main
