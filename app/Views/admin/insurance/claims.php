<?php echo $this->extend('admin/includes/header'); ?>
<!--start content-->
<?php echo $this->section('content'); ?>
<main class="main-content">
    <div class="position-relative iq-banner">
        <!--Nav Start-->
        <?php echo $this->include('admin/nav_bar') ?>
    </div>

    <div class="conatiner-fluid content-inner pb-0 px-4" id="page_layout">
        <!-- Modern Header Section -->
        <div class="container-fluid p-0 mb-4 bg-gradient-primary  rounded-3">
            <div class=" text-white p-3 shadow position-relative overflow-hidden">
                <div class="position-relative z-1">
                    <h1 class="h2 fw-bold mb-2 text-white">Insurance Claims</h1>
                    <p class="text-white-75 mb-0">Process and manage insurance claims</p>
                </div>
                <i class="bi bi-clipboard-check position-absolute end-0 bottom-0 opacity-10"
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
                            <button class="btn btn-black bg-black text-white d-flex align-items-center gap-2"
                                onclick="openAddClaimModal()">
                                <i class="bi bi-plus fs-6"></i>
                                Add New Claim
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->


        <!-- Modern Table - Responsive -->
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="" style="background-color: #c6d2ff!important;">
                        <tr>
                            <th class="py-3 text-black fs-6 fw-semibold border-bottom"
                                style="background: #c6d2ff!important;">Claim Details</th>
                            <th class="py-3 text-black fs-6 fw-semibold border-bottom d-none d-md-table-cell"
                                style="background: #c6d2ff!important;">Policy Info</th>
                            <th class="py-3 text-black fs-6 fw-semibold border-bottom d-none d-lg-table-cell"
                                style="background: #c6d2ff!important;">Claim Type</th>
                            <th class="py-3 text-black fs-6 fw-semibold border-bottom"
                                style="background: #c6d2ff!important;">Amount</th>
                            <th class="py-3 text-black fs-6 fw-semibold border-bottom text-center"
                                style="background: #c6d2ff!important;">Status</th>
                            <th class="py-3 text-black fs-6 fw-semibold border-bottom d-none d-lg-table-cell"
                                style="background: #c6d2ff!important;">Settlement</th>
                            <th class="pe-4 py-3 text-black fw-semibold border-bottom text-end"
                                style="background: #c6d2ff!important;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php if (!empty($claims)): ?>
                            <?php foreach ($claims as $claim):
                                $initial = strtoupper(substr($claim['client_name'], 0, 1));
                                $status = strtolower($claim['status']);
                                $statusClass = '';
                                $statusIcon = '';

                                switch ($status) {
                                    case 'pending':
                                        $statusClass = 'bg-warning bg-opacity-10 text-warning border-warning border-opacity-25';
                                        $statusIcon = 'bi-clock';
                                        break;
                                    case 'in progress':
                                        $statusClass = 'bg-info bg-opacity-10 text-white border-info border-opacity-25';
                                        $statusIcon = 'bi-arrow-repeat';
                                        break;
                                    case 'settled':
                                        $statusClass = 'bg-success bg-opacity-10 text-white border-success border-opacity-25';
                                        $statusIcon = 'bi-check-circle';
                                        break;
                                    case 'rejected':
                                        $statusClass = 'bg-danger bg-opacity-10 text-danger border-danger border-opacity-25';
                                        $statusIcon = 'bi-x-circle';
                                        break;
                                    default:
                                        $statusClass = 'bg-light text-dark border';
                                        $statusIcon = 'bi-question-circle';
                                }
                                ?>
                                <tr data-claim-details='<?= json_encode([
                                    "claimId" => $claim["id"],
                                    "policyNumber" => $claim["policy_number"],
                                    "clientName" => $claim["client_name"],
                                    "contact" => $claim["contact"] ?? "",
                                    "claimType" => $claim["claim_type"],
                                    "claimAmount" => $claim["amount"] ?? "",
                                    "status" => $claim["status"],
                                    "settlementDate" => $claim["settlement_date"] ?? "-",
                                    "remarks" => $claim["remarks"] ?? ""
                                ]) ?>'>
                                    <td class="ps-4 py-2">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold"
                                                style="width: 40px; height: 40px; border: 1px solid #e5dbff;">
                                                <?= $initial ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark"><?= esc($claim['client_name']) ?></div>
                                                <div class="text-muted small font-monospace mt-1">
                                                    Claim ID: #<?= esc($claim['id']) ?>
                                                </div>
                                                <!-- Mobile: All hidden info in first column -->
                                                <div class="d-md-none mt-2">
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <span class="badge bg-light text-dark border small">
                                                            <i class="bi bi-file-text me-1"></i>
                                                            <?= esc($claim['policy_number']) ?>
                                                        </span>
                                                        <span class="badge bg-light text-dark border small">
                                                            <i class="bi bi-shield-exclamation me-1"></i>
                                                            <?= esc($claim['claim_type']) ?>
                                                        </span>
                                                        <?php if (!empty($claim['settlement_date']) && $claim['settlement_date'] != '-'): ?>
                                                            <span class="badge bg-light text-dark border small">
                                                                <i class="bi bi-calendar-check me-1"></i>
                                                                <?= esc($claim['settlement_date']) ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <?php if (!empty($claim['remarks'])): ?>
                                                    <div class="text-muted small mt-1">
                                                        <i class="bi bi-chat-left-text me-1"></i>
                                                        <?= esc(substr($claim['remarks'], 0, 30)) ?>...
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-2 d-none d-md-table-cell">
                                        <div class="d-inline-flex align-items-center gap-2 bg-light px-3 py-1  border" style="background-color: rgba(59, 130, 246, 0.1) !important;
        color: #2563eb !important;
        border: 1px solid rgba(59, 130, 246, 0.2) !important; border-radius:50px">
                                            <i class="bi bi-file-text "></i>
                                            <span class="fw-medium  small"><?= esc($claim['policy_number']) ?></span>
                                        </div>
                                    </td>
                                    <td class="py-2 d-none d-lg-table-cell">
                                        <div class="d-inline-flex align-items-center gap-2 bg-light px-3 py-1 border" style=" background-color: rgba(251, 191, 36, 0.1) !important;
        color: #d97706 !important;
        border: 1px solid rgba(251, 191, 36, 0.2) !important; font-size: 0.75rem; border-radius:50px">
                                            <i class="bi bi-shield-exclamation "></i>
                                            <span class="fw-medium  small"><?= esc($claim['claim_type']) ?></span>
                                        </div>
                                    </td>
                                    <td class="py-2">
                                        <div class="fw-bold text-dark fs-6">
                                            <?php if (isset($claim['amount']) && $claim['amount']): ?>
                                                ₹<?= number_format($claim['amount'], 2) ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="py-2 text-center">
                                        <span
                                            class="badge d-inline-flex align-items-center gap-1 px-3 py-2 rounded-pill fw-semibold <?= $statusClass ?>">
                                            <i class="<?= $statusIcon ?>"></i>
                                            <span class="d-none d-sm-inline"><?= esc(ucfirst($claim['status'])) ?></span>
                                            <span
                                                class="d-inline d-sm-none"><?= esc(substr(ucfirst($claim['status']), 0, 3)) ?></span>
                                        </span>
                                    </td>
                                    <td class="py-2 d-none d-lg-table-cell">
                                        <?php if (!empty($claim['settlement_date']) && $claim['settlement_date'] != '-'): ?>
                                            <div class="d-flex align-items-center gap-2 text-dark fw-medium">
                                                <i class="bi bi-calendar-check text-muted"></i>
                                                <span><?= esc($claim['settlement_date']) ?></span>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="pe-4 py-2 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button class="btn btn-outline-primary btn-sm" onclick="showClaimUpdateForm(this)">
                                                Update
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-4 mb-3"></i>
                                        <p class="h5">No claims found</p>
                                        <p class="small">There are no insurance claims to display</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4 px-2">
            <div class="text-muted small">
                Showing <?= !empty($claims) ? count($claims) : 0 ?> entries
            </div>
            <div class="btn-group mt-0 pt-0 border-0">
                <button class="btn btn-outline-primary btn-sm">Previous</button>
                <button class="btn btn-primary btn-sm">1</button>
                <button class="btn btn-outline-primary btn-sm">Next</button>
            </div>
        </div>
    </div>
</main>

<!-- Claim Update Modal - Bootstrap Modal -->
<div class="modal fade" id="claimUpdateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden p-0"
            style="max-height: 90vh; display: flex; flex-direction: column;">

            <!-- Header -->
            <div class="modal-header border-0 text-white"
                style="background: linear-gradient(135deg, #6366f1, #8b5cf6); flex-shrink: 0;">
                <div>
                    <h4 class="modal-title fw-bold mb-1 text-white">Update Claim Status</h4>
                    <p class="small mb-0 opacity-75">Modify claim details and update current status</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- Body -->
            <div class="modal-body p-4 bg-light" style="overflow-y: auto; flex: 1;">

                <!-- Claim Info Card -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small mb-1">Claim ID</label>
                                <input type="text" id="claimId" class="form-control bg-light rounded-3" readonly />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small mb-1">Client Name</label>
                                <input type="text" id="claimClientName" class="form-control bg-light rounded-3"
                                    readonly />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Details -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small mb-1">Claim Amount (₹)</label>
                                <input type="number" id="claimAmount" step="0.01" min="0"
                                    placeholder="Enter claim amount" class="form-control rounded-3" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small mb-1">Settlement Date</label>
                                <input type="date" id="settlementDate" class="form-control rounded-3" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Selection -->
                <div class="mb-4">
                    <label class="form-label text-muted small mb-2">Update Status</label>

                    <div class="status-options d-flex flex-wrap gap-3">
                        <div class="status-pill" data-status="Pending">
                            <i class="bi bi-clock me-2"></i> Pending
                        </div>
                        <div class="status-pill" data-status="In Progress">
                            <i class="bi bi-arrow-repeat me-2"></i> In Progress
                        </div>
                        <div class="status-pill" data-status="Settled">
                            <i class="bi bi-check-circle me-2"></i> Settled
                        </div>
                        <div class="status-pill" data-status="Rejected">
                            <i class="bi bi-x-circle me-2"></i> Rejected
                        </div>
                    </div>

                    <input type="hidden" id="selectedStatus" value="" />
                </div>

                <!-- Remarks -->
                <div>
                    <label class="form-label text-muted small mb-1">Remarks</label>
                    <textarea id="updateRemarks" class="form-control rounded-3" rows="3"
                        placeholder="Add remarks about the claim status update..."></textarea>
                </div>

            </div>

            <!-- Footer -->
            <div class="modal-footer bg-white border-top d-flex justify-content-between" style="flex-shrink: 0;">
                <button class="btn btn-light px-4 rounded-3" data-bs-dismiss="modal">
                    Cancel
                </button>

                <button class="btn btn-primary px-4 rounded-3 d-flex align-items-center gap-2"
                    onclick="updateClaimStatus()">
                    <i class="bi bi-check-circle"></i>
                    Update Status
                </button>
            </div>

        </div>
    </div>
</div>
<!-- Add Claim Modal -->
<div class="modal fade" id="addClaimModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden p-0">

            <!-- Header -->
            <div class="modal-header border-0 text-white"
                style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                <div>
                    <h4 class="modal-title fw-bold mb-1 text-white">Record New Claim</h4>
                    <p class="small mb-0 opacity-75">Search policy and submit a new claim</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- Body -->
            <div class="modal-body p-4 bg-light">

                <!-- STEP 1: SEARCH POLICY -->
                <div id="claimStep1">
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body">
                            <label class="form-label text-muted small mb-2">Find Policy</label>

                            <div class="input-group mb-3 rounded-5">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" id="policySearchInput" class="form-control"
                                    placeholder="Policy #, Client Name or Phone">
                                <button class="btn bg-primary text-white px-4 m-0" type="button" onclick="searchPolicies()">
                                    Search
                                </button>
                            </div>

                            <div id="policySearchResults" class="list-group rounded-3"
                                style="max-height: 220px; overflow-y: auto;">
                                <!-- Search results -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: CLAIM DETAILS -->
                <div id="claimStep2" style="display:none;">

                    <!-- Selected Policy Info -->
                    <div class="alert alert-info border-0 rounded-4 d-flex align-items-center gap-2 mb-4">
                        <i class="bi bi-info-circle"></i>
                        <span id="selectedPolicyInfo" class="small fw-semibold"></span>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-black small mb-1">Claim Type</label>
                                    <select id="newClaimType" class="form-select rounded-3">
                                        <option>Accident</option>
                                        <option>Illness</option>
                                        <option>Surgery</option>
                                        <option>OPD</option>
                                        <option>Other</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-black small mb-1">Claim Amount (₹)</label>
                                    <input type="number" id="newClaimAmount" class="form-control rounded-3"
                                        placeholder="0.00">
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label text-black small mb-1">Remarks / Description</label>
                                <textarea id="newClaimRemarks" class="form-control rounded-3" rows="3"
                                    placeholder="Provide details about the claim..."></textarea>
                            </div>
                        </div>
                    </div>

                    <button class="btn bg-secondary text-white w-100" onclick="showClaimStep(1)">
                        Back to Policy Search
                    </button>

                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer bg-white border-top d-flex justify-content-between">
                <button class="btn btn-light px-4" data-bs-dismiss="modal">
                    Cancel
                </button>

                <button id="submitClaimBtn" class="btn btn-primary px-4 d-none d-flex align-items-center gap-2"
                    onclick="submitNewClaim()">
                    <i class="bi bi-check-circle"></i>
                    Submit Claim
                </button>
            </div>

        </div>
    </div>
</div>

<?php echo $this->include('admin/footer') ?>

<style>
    .status-pill {
        padding: 10px 18px;
        border-radius: 50px;
        background: white;
        border: 1px solid #e5e7eb;
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    #claimStep1,
    #claimStep2 {
        transition: all 0.3s ease;
    }

    .status-pill:hover {
        background: #eef2ff;
        border-color: #6366f1;
    }

    .status-pill.active {
        background: #6366f1;
        color: white;
        border-color: #6366f1;
    }

    /* Fixed body when modal is open - ENHANCED */
    body.modal-open {
        overflow: hidden !important;
        padding-right: 0 !important;
        position: fixed !important;
        width: 100% !important;
        height: 100% !important;
    }

    /* Prevent body scrolling on mobile */
    @media (max-width: 768px) {
        body.modal-open {
            overflow: hidden !important;
            position: fixed !important;
            width: 100% !important;
            height: 100% !important;
            touch-action: none !important;
        }
    }

    /* Modal backdrop */
    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.7) !important;
    }

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

    .modal-content {
        border-radius: 16px !important;
        border: none;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .badge {
        padding: 0.5rem 1rem !important;
        font-weight: 600 !important;
    }

    .input-group-text {
        background-color: #000 !important;
        border-color: transparent !important;
        color: #fff !important;
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

    /* Status Options */
    .status-option {
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        background-color: #f9fafb;
        display: flex;
        align-items: center;
        flex: 1;
        min-width: 120px;
        justify-content: center;
    }

    .status-option:hover {
        border-color: #6366f1;
        background-color: #f8f9ff;
        transform: translateY(-2px);
    }

    .status-option.selected {
        border-color: #6366f1;
        background-color: #eef2ff;
        color: #6366f1;
        font-weight: 600;
        box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.1);
    }

    .status-option[data-status="Pending"]:hover,
    .status-option[data-status="Pending"].selected {
        border-color: #f59e0b;
        background-color: #fffbeb;
        color: #d97706;
    }

    .status-option[data-status="In Progress"]:hover,
    .status-option[data-status="In Progress"].selected {
        border-color: #06b6d4;
        background-color: #ecfeff;
        color: #0891b2;
    }

    .status-option[data-status="Settled"]:hover,
    .status-option[data-status="Settled"].selected {
        border-color: #10b981;
        background-color: #f0fdf4;
        color: #059669;
    }

    .status-option[data-status="Rejected"]:hover,
    .status-option[data-status="Rejected"].selected {
        border-color: #ef4444;
        background-color: #fef2f2;
        color: #dc2626;
    }

    /* Form styling */
    .form-label {
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
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

    /* Responsive table without horizontal scrolling */
    @media (max-width: 992px) {
        .table-responsive {
            overflow-x: hidden;
        }

        .table {
            width: 100%;
            margin-bottom: 0;
        }

        .table thead th {
            font-size: 0.7rem;
            padding: 0.75rem 0.5rem;
        }

        .table tbody td {
            padding: 0.75rem 0.5rem;
        }

        .badge {
            padding: 0.4rem 0.75rem !important;
            font-size: 0.75rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .fs-6 {
            font-size: 0.9rem !important;
        }
    }

    @media (max-width: 768px) {
        .table thead th:not(:first-child):not(:last-child) {
            display: none;
        }

        .table tbody td:not(:first-child):not(:last-child) {
            display: none;
        }

        .table tbody td:first-child {
            width: 60%;
        }

        .table tbody td:last-child {
            width: 40%;
            text-align: right;
        }

        .d-md-none .badge {
            margin-bottom: 0.25rem;
            font-size: 0.7rem;
        }

        /* Show only icons in status badge on very small screens */
        .badge span.d-inline {
            display: none !important;
        }

        .badge span.d-none.d-sm-inline {
            display: inline !important;
        }
    }

    @media (max-width: 576px) {
        .table tbody td:first-child {
            width: 70%;
        }

        .table tbody td:last-child {
            width: 30%;
        }

        .badge span.d-none.d-sm-inline {
            display: none !important;
        }

        .badge span.d-inline.d-sm-none {
            display: inline !important;
        }

        .modal-dialog {
            margin: 1rem;
            max-width: calc(100% - 2rem);
        }

        .card-body.p-3 {
            padding: 1rem !important;
        }

        .input-group.w-50 {
            width: 100% !important;
        }

        .status-options {
            flex-direction: column;
        }

        .status-option {
            width: 100%;
        }
    }

    @media (max-width: 400px) {
        .table tbody td:first-child {
            width: 65%;
        }

        .table tbody td:last-child {
            width: 35%;
        }

        .rounded-circle {
            width: 32px !important;
            height: 32px !important;
            font-size: 0.8rem;
        }

        .fw-bold.text-dark {
            font-size: 0.9rem;
        }

        .text-muted.small {
            font-size: 0.75rem;
        }
    }
</style>

<script>
    // Enhanced script to prevent body scrolling
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Bootstrap modal
        const claimModalElement = document.getElementById('claimUpdateModal');

        if (claimModalElement) {
            // Store the scroll position before modal opens
            let scrollPosition = 0;

            claimModalElement.addEventListener('show.bs.modal', function () {
                // Save current scroll position
                scrollPosition = window.pageYOffset || document.documentElement.scrollTop;

                // Force body to be fixed position
                document.body.style.top = `-${scrollPosition}px`;
                document.body.classList.add('modal-open');

                // Prevent touch scrolling on mobile
                document.addEventListener('touchmove', preventTouchScroll, {
                    passive: false
                });
            });

            claimModalElement.addEventListener('hide.bs.modal', function () {
                // Restore scroll position
                document.body.classList.remove('modal-open');
                document.body.style.top = '';
                window.scrollTo(0, scrollPosition);

                // Remove touch scroll prevention
                document.removeEventListener('touchmove', preventTouchScroll);
            });
        }

        // Function to prevent touch scrolling on body
        function preventTouchScroll(e) {
            if (!e.target.closest('.modal-content')) {
                e.preventDefault();
            }
        }

        // Add click event listeners to status options
        document.querySelectorAll('.status-option').forEach(option => {
            option.addEventListener('click', function () {
                selectStatus(this.getAttribute('data-status'));
            });
        });

        // Initialize search functionality
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function (e) {
                const searchTerm = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        }
    });

    function showClaimUpdateForm(button) {
        const row = button.closest('tr');
        const data = JSON.parse(row.getAttribute('data-claim-details'));

        document.getElementById('claimId').value = data.claimId;
        document.getElementById('claimClientName').value = data.clientName;
        document.getElementById('claimAmount').value = data.claimAmount || '';
        document.getElementById('selectedStatus').value = data.status;
        document.getElementById('settlementDate').value = data.settlementDate !== '-' ? data.settlementDate : '';
        document.getElementById('updateRemarks').value = data.remarks || '';

        // Highlight the current status option
        highlightSelectedStatus(data.status);

        // Show modal using Bootstrap
        const modalElement = document.getElementById('claimUpdateModal');
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    }

    function selectStatus(status) {
        document.getElementById('selectedStatus').value = status;
        highlightSelectedStatus(status);
    }

    function highlightSelectedStatus(status) {
        // Remove highlight from all options
        const options = document.querySelectorAll('.status-option');
        options.forEach(option => {
            option.classList.remove('selected');
        });

        // Add highlight to selected option
        const selectedOption = document.querySelector(`.status-option[data-status="${status}"]`);
        if (selectedOption) {
            selectedOption.classList.add('selected');
        }
    }

    function updateClaimStatus() {
        const claimId = document.getElementById('claimId').value;
        const claimAmount = document.getElementById('claimAmount').value;
        const status = document.getElementById('selectedStatus').value;
        const settlementDate = document.getElementById('settlementDate').value;
        const remarks = document.getElementById('updateRemarks').value;

        // Validate required fields
        if (!claimAmount || claimAmount <= 0) {
            alert('Please enter a valid claim amount');
            return;
        }

        if (!status) {
            alert('Please select a status');
            return;
        }

        console.log('Sending update request...', {
            claimId,
            claimAmount,
            status,
            settlementDate,
            remarks
        });

        // AJAX call to update claim - using FormData instead of JSON
        const formData = new FormData();
        formData.append('claimId', claimId);
        formData.append('claimAmount', claimAmount);
        formData.append('status', status);
        formData.append('settlementDate', settlementDate);
        formData.append('remarks', remarks);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        fetch('<?= base_url("insurance/updateClaim") ?>', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                console.log('Response data:', data);
                if (data.status) {
                    alert('Claim updated successfully');

                    // Close modal
                    const modalElement = document.getElementById('claimUpdateModal');
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    modal.hide();

                    // Reload page to show updated data
                    location.reload();
                } else {
                    alert('Update failed: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the claim: ' + error.message);
            });
    }

    let selectedPolicyId = null;

    function openAddClaimModal() {
        const modalElement = document.getElementById('addClaimModal');
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
        showClaimStep(1);
        document.getElementById('policySearchInput').value = '';
        document.getElementById('policySearchResults').innerHTML = '';
        selectedPolicyId = null;
    }

    function showClaimStep(step) {
        if (step === 2) {
            $('#claimStep1').hide();
            $('#claimStep2').show();
            $('#submitClaimBtn').removeClass('d-none');
        } else {
            $('#claimStep2').hide();
            $('#claimStep1').show();
            $('#submitClaimBtn').addClass('d-none');
        }
    }

    function searchPolicies() {
        const query = document.getElementById('policySearchInput').value;
        if (!query) return;

        fetch('<?= base_url("insurance/searchActivePolicies") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                query: query
            })
        })
            .then(res => res.json())
            .then(data => {
                const resultsDiv = document.getElementById('policySearchResults');
                resultsDiv.innerHTML = '';

                if (data.policies && data.policies.length > 0) {
                    data.policies.forEach(p => {
                        const btn = document.createElement('button');
                        btn.className = 'list-group-item list-group-item-action py-3';
                        btn.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold text-dark">${p.client_name}</div>
                                <div class="small text-muted">${p.pet_name} | ${p.policy_number}</div>
                            </div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </div>
                    `;
                        btn.onclick = () => selectPolicy(p);
                        resultsDiv.appendChild(btn);
                    });
                } else {
                    resultsDiv.innerHTML = '<div class="text-center py-3 text-muted">No active policies found</div>';
                }
            });
    }

    function selectPolicy(p) {
        selectedPolicyId = p.id;
        document.getElementById('selectedPolicyInfo').textContent = `Policy: ${p.policy_number} | Client: ${p.client_name}`;
        showClaimStep(2);
    }

    function submitNewClaim() {
        const amount = document.getElementById('newClaimAmount').value;
        const claimType = document.getElementById('newClaimType').value;
        const remarks = document.getElementById('newClaimRemarks').value;

        if (!amount || amount <= 0) {
            alert('Please enter a valid amount');
            return;
        }

        fetch('<?= base_url("insurance/addClaim") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                policy_id: selectedPolicyId,
                amount: amount,
                claim_type: claimType,
                remarks: remarks
            })
        })
            .then(res => res.json())
            .then(data => {
                if (data.status) {
                    alert('Claim recorded successfully');
                    location.reload();
                } else {
                    alert('Failed to record claim: ' + data.message);
                }
            });
    }
</script>
<?php echo $this->endSection() ?>