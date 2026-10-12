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
            <div class=" text-white p-5 shadow position-relative overflow-hidden">
                <div class="position-relative z-1">
                    <h1 class="h2 fw-bold mb-2 text-white">Quotations</h1>
                    <p class="text-white-75 mb-0">View and manage insurance quotations</p>
                </div>
                <i class="bi bi-file-text position-absolute end-0 bottom-0 opacity-10" style="font-size: 12rem; transform: rotate(12deg); margin-right: -2rem; margin-bottom: -2rem;"></i>
            </div>
            <!-- <div class="p-3">
             <div class="card border-0 shadow-sm mb-0">
            <div class="card-body p-3">
                <div class="d-flex justify-content-end">
                    <div class="input-group w-50 w-md-auto">
                        <span class="input-group-text bg-black text-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 ps-0" placeholder="Search..." id="searchInput">
                    </div>
                </div>
            </div></div>
        </div> -->
        </div>

        <!-- Search Bar -->
       

        <!-- Modern Table -->
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="" style="background-color: #c6d2ff!important;">
                        <tr>
                            <th class=" py-3 text-black fs-6 fw-semibold border-bottom" style="background: #c6d2ff!important;">Client Details</th>
                            <th class="py-3 text-black fs-6 fw-semibold border-bottom" style="background: #c6d2ff!important;">Insurance Type</th>
                            <th class="py-3 text-black fs-6 fw-semibold border-bottom" style="background: #c6d2ff!important;">Amount</th>
                            <th class="py-3 text-black fs-6 fw-semibold border-bottom text-center" style="background: #c6d2ff!important;">Status</th>
                            <th class="pe-4 py-3 text-black fs-6 fw-semibold border-bottom text-end" style="background: #c6d2ff!important;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php
                        $i = 1;
                        foreach ($quotations as $quote) {
                            $initial = strtoupper(substr($quote->full_name, 0, 1));
                            $amount = number_format($quote->amount, 2);
                            $parts = explode('.', $amount);
                            $formattedAmount = '$' . $parts[0] . '.' . $parts[1];
                            $expiryDate = '';
                            if (isset($quote->expiry_date)) {
                                $expiryDate = date('d-m-Y', strtotime($quote->expiry_date));
                            }
                        ?>
                            <tr>
                                <td class="ps-4 py-2">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; border: 1px solid #e5dbff;">
                                            <?= $initial ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?= $quote->full_name ?></div>
                                            <div class="d-flex align-items-center text-muted small mt-1">
                                                <i class="bi bi-telephone me-1"></i>
                                                <?= $quote->phone ?>
                                            </div>
                                            <?php if ($expiryDate): ?>
                                                <div class="d-flex align-items-center text-muted small mt-1">
                                                    <i class="bi bi-calendar me-1"></i>
                                                    Exp: <?= $expiryDate ?>
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
                                    <div class="fw-bold text-dark fs-6">
                                        <?= $formattedAmount ?>
                                    </div>
                                </td>
                                <td class="py-2 text-center">
                                    <span class="badge d-inline-flex align-items-center gap-1 px-3 py-2 rounded-pill fw-semibold bg-success bg-opacity-10 text-white border-success border-opacity-25">
                                        <i class="bi bi-check-circle"></i>
                                        Sent
                                    </span>
                                </td>
                                <td class="pe-4 py-2 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-outline-primary btn-sm"
                                            onclick="showQuotationDetails(this)"
                                            data-client-name="<?= $quote->full_name ?>"
                                            data-phone="<?= $quote->phone ?>"
                                            data-email="<?= $quote->email ?>"
                                            data-amount="<?= $quote->amount ?>"
                                            data-expiry="<?= $expiryDate ?>"
                                            data-policy-id="<?= $quote->policy_id ?>"
                                            data-bs-toggle="modal"
                                            data-bs-target="#quotationDetailsModal">
                                            View
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

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4 px-2">
            <div class="text-muted small">
                Showing <?= count($quotations) ?> entries
            </div>
            <div class="btn-group">
                <button class="btn btn-outline-primary btn-sm">Previous</button>
                <button class="btn btn-primary btn-sm">1</button>
                <button class="btn btn-outline-primary btn-sm">Next</button>
            </div>
        </div>
    </div>
</main>

<!-- Quotation Details Modal (Bootstrap) -->
<div class="modal fade" id="quotationDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow p-0" style="height: 85vh;">
            <div class="modal-header" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); border-bottom: 2px solid #a3b4ff;">
                <h5 class="modal-title text-white fw-bold">Quotation Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="overflow-y: auto; flex: 1;">
                <div class="p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="bg-light-primary p-3 rounded-3">
                                <h6 class="text-muted mb-1">Client Name</h6>
                                <p class="h5 fw-bold mb-0" id="detail-client-name"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light-primary p-3 rounded-3">
                                <h6 class="text-muted mb-1">Email</h6>
                                <p class="h5 fw-bold mb-0" id="detail-email"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light-primary p-3 rounded-3">
                                <h6 class="text-muted mb-1">Contact</h6>
                                <p class="h5 fw-bold mb-0" id="detail-phone"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light-primary p-3 rounded-3">
                                <h6 class="text-muted mb-1">Policy ID</h6>
                                <p class="h5 fw-bold mb-0" id="detail-policy-id"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light-primary p-3 rounded-3">
                                <h6 class="text-muted mb-1">Insurance Type</h6>
                                <p class="h5 fw-bold mb-0">Dog Insurance</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light-primary p-3 rounded-3">
                                <h6 class="text-muted mb-1">Coverage</h6>
                                <p class="h5 fw-bold mb-0">Comprehensive</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light-primary p-3 rounded-3">
                                <h6 class="text-muted mb-1">Premium Amount</h6>
                                <p class="h5 fw-bold mb-0" id="detail-amount"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light-primary p-3 rounded-3">
                                <h6 class="text-muted mb-1">Valid Till</h6>
                                <p class="h5 fw-bold mb-0" id="detail-expiry"></p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bg-light-primary p-3 rounded-3">
                                <h6 class="text-muted mb-1">Policy Benefits</h6>
                                <p class="h6 fw-bold mb-0">Accident Coverage, Illness Coverage, Third Party Liability</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bg-light-primary p-3 rounded-3">
                                <h6 class="text-muted mb-1">Terms & Conditions</h6>
                                <p class="text-muted mb-0">Policy subject to terms and conditions</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bg-light-primary p-3 rounded-3">
                                <h6 class="text-muted mb-1">Status</h6>
                                <span class="badge d-inline-flex align-items-center gap-1 px-3 py-2 rounded-pill fw-semibold bg-success bg-opacity-10 text-white border-success border-opacity-25">
                                    <i class="bi bi-check-circle"></i>
                                    Sent
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
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

    /* Modal header styling to match design */
    #quotationDetailsModal .modal-header {
        border-bottom: none;
        padding: 1.5rem;
        flex-shrink: 0;
    }

    #quotationDetailsModal .modal-title {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 600;
    }

    #quotationDetailsModal .btn-close-white {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    #quotationDetailsModal .modal-body {
        padding: 0;
        flex: 1 1 auto;
    }

    #quotationDetailsModal .modal-footer {
        border-top: 1px solid #e5e7eb;
        padding: 1rem 1.5rem;
        flex-shrink: 0;
    }

    /* Modal dialog scrollable */
    .modal-dialog-scrollable .modal-content {
        max-height: 100%;
        overflow: hidden;
    }

    .modal-dialog-scrollable .modal-body {
        overflow-y: auto;
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
    }
</style>

<script>
    function showQuotationDetails(button) {
        const clientName = button.getAttribute('data-client-name');
        const email = button.getAttribute('data-email');
        const phone = button.getAttribute('data-phone');
        const amount = button.getAttribute('data-amount');
        const expiry = button.getAttribute('data-expiry');
        const policyId = button.getAttribute('data-policy-id');

        const formattedAmount = '$' + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');

        document.getElementById('detail-client-name').textContent = clientName;
        document.getElementById('detail-email').textContent = email;
        document.getElementById('detail-phone').textContent = phone;
        document.getElementById('detail-amount').textContent = formattedAmount;
        document.getElementById('detail-expiry').textContent = expiry;
        document.getElementById('detail-policy-id').textContent = policyId;
    }

    // Initialize Bootstrap components if needed
    document.addEventListener('DOMContentLoaded', function() {
        // Prevent body scroll when modal is shown
        const quotationModal = document.getElementById('quotationDetailsModal');

        if (quotationModal) {
            quotationModal.addEventListener('show.bs.modal', function() {
                document.body.classList.add('modal-open');
            });

            quotationModal.addEventListener('hide.bs.modal', function() {
                document.body.classList.remove('modal-open');
            });
        }
    });

    // Search functionality
    // document.getElementById('searchInput').addEventListener('input', function(e) {
    //     const searchTerm = e.target.value.toLowerCase();
    //     const rows = document.querySelectorAll('tbody tr');

    //     rows.forEach(row => {
    //         const text = row.textContent.toLowerCase();
    //         row.style.display = text.includes(searchTerm) ? '' : 'none';
    //     });
    // });
</script>

<?php echo $this->include('admin/footer') ?>
<?php echo $this->endSection() ?>