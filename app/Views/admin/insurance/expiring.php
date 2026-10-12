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
                    <h1 class="h2 fw-bold mb-2 text-white">Policies Expiring Soon</h1>
                    <p class="text-white-75 mb-0">Track policies that need renewal</p>
                </div>
                <i class="bi bi-calendar position-absolute end-0 bottom-0 opacity-10"
                    style="font-size: 12rem; transform: rotate(12deg); margin-right: -2rem; margin-bottom: -2rem;"></i>
            </div>
            <!-- <div class="p-3">
                <div class="card border-0 shadow-sm mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-end">
                            <div class="input-group w-50 w-md-auto">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 ps-0" placeholder="Search..."
                                    id="searchInput">
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>

        <!-- Search Bar -->


        <!-- Modern Table - Responsive -->
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="" style="background-color: #c6d2ff!important;">
                        <tr>
                            <th class="ps-4 py-3 text-black fs-6 fw-semibold border-bottom"
                                style="background: #c6d2ff!important;">Client Details</th>
                            <th class="py-3 text-black fs-6 fw-semibold border-bottom d-none d-md-table-cell"
                                style="background: #c6d2ff!important;">Policy Info</th>
                            <th class="py-3 text-black fs-6 fw-semibold border-bottom"
                                style="background: #c6d2ff!important;">Insurance Type</th>
                            <th class="py-3 text-black fs-6 fw-semibold border-bottom"
                                style="background: #c6d2ff!important;">Dates</th>
                            <th class="py-3 text-black fs-6 fw-semibold border-bottom text-center"
                                style="background: #c6d2ff!important;">Status</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php
                        $i = 1;
                        foreach ($insurances as $ins) {
                            $initial = strtoupper(substr($ins->full_name, 0, 1));
                            $startDate = date('d-m-Y', strtotime($ins->start_date));
                            $endDate = date('d-m-Y', strtotime($ins->end_date));
                            // Calculate days until expiry
                            $today = new DateTime();
                            $expiryDate = new DateTime($ins->end_date);
                            $daysUntilExpiry = $today->diff($expiryDate)->days;

                            // Determine status based on days until expiry
                            if ($daysUntilExpiry <= 7) {
                                $statusClass = 'bg-danger bg-opacity-10 text-danger border-danger border-opacity-25';
                                $statusIcon = 'bi-exclamation-triangle';
                                $statusText = 'Expiring Soon';
                            } elseif ($daysUntilExpiry <= 30) {
                                $statusClass = 'bg-warning bg-opacity-10 text-warning border-warning border-opacity-25';
                                $statusIcon = 'bi-clock';
                                $statusText = 'Expiring Soon';
                            } else {
                                $statusClass = 'bg-success bg-opacity-10 text-success border-success border-opacity-25';
                                $statusIcon = 'bi-check-circle';
                                $statusText = 'Active';
                            }
                            ?>
                            <tr data-policy-details='{
                                "policyNumber":"<?= $ins->policy_number ?>",
                                "clientName":"<?= $ins->full_name ?>",
                                "contact":"<?= $ins->phone ?>",
                                "email":"<?= $ins->email ?>",
                                "insuranceType":"Dog Insurance",
                                "startDate":"<?= $startDate ?>",
                                "expiryDate":"<?= $endDate ?>",
                                "premium":"₹<?= $ins->amount ?>",
                                "renewalDate":"<?= $endDate ?>",
                                "status":"<?= $statusText ?>",
                                "daysUntilExpiry":"<?= $daysUntilExpiry ?>",
                                "petDetails":{
                                    "breed":"<?= $ins->breed ?>",
                                    "age":"<?= $ins->dob ?>",
                                    "gender":"<?= $ins->gender ?>",
                                    "vaccination":"<?= $ins->last_vaccination ?>"
                                }
                            }'>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 40px; height: 40px; border: 1px solid #e5dbff;">
                                            <?= $initial ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?= $ins->full_name ?></div>
                                            <div class="d-flex align-items-center text-muted small mt-1">
                                                <i class="bi bi-telephone me-1"></i>
                                                <?= $ins->phone ?>
                                            </div>
                                            <!-- Mobile: Policy info -->
                                            <div class="d-md-none mt-2">
                                                <span class="badge bg-light text-dark border small">
                                                    <i class="bi bi-file-text me-1"></i>
                                                    <?= $ins->policy_number ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 d-none d-md-table-cell">
                                    <div class="d-flex flex-column gap-1">
                                        <div class="fw-bold text-dark font-monospace">
                                            #<?= $ins->policy_number ?>
                                        </div>
                                        <div class="text-muted small">
                                            <i class="bi bi-calendar me-1"></i>
                                            Start: <?= $startDate ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="d-inline-flex align-items-center gap-2 bg-light px-3 py-1 rounded-2 border">
                                        <i class="bi bi-shield-check text-primary"></i>
                                        <span class="fw-medium text-dark small">Dog Insurance</span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex flex-column">
                                        <div class="text-dark fw-medium">
                                            <i class="bi bi-calendar-check text-muted me-2"></i>
                                            <?= $endDate ?>
                                        </div>
                                        <div class="text-muted small mt-1">
                                            <i class="bi bi-hourglass-split me-1"></i>
                                            <?= $daysUntilExpiry ?> days left
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 text-center">
                                    <span
                                        class="badge d-inline-flex align-items-center gap-1 px-3 py-2 rounded-pill fw-semibold <?= $statusClass ?>">
                                        <i class="<?= $statusIcon ?>"></i>
                                        <?= $statusText ?>
                                    </span>
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
            <div class="btn-group">
                <button class="btn btn-outline-primary btn-sm">Previous</button>
                <button class="btn btn-primary btn-sm">1</button>
                <button class="btn btn-outline-primary btn-sm">Next</button>
            </div>
        </div>
    </div>
</main>

<?php echo $this->include('admin/footer') ?>

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

    .input-group-text {
        background-color: #f9fafb !important;
        border-color: #e5e7eb !important;
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

        .fs-6 {
            font-size: 0.9rem !important;
        }
    }

    @media (max-width: 768px) {
        .table thead th:nth-child(2) {
            display: none;
        }

        .table tbody td:nth-child(2) {
            display: none;
        }

        .table tbody td:first-child {
            width: 40%;
        }

        .table tbody td:nth-child(3) {
            width: 25%;
        }

        .table tbody td:nth-child(4) {
            width: 25%;
        }

        .table tbody td:last-child {
            width: 30%;
            text-align: center;
        }

        .d-md-none .badge {
            margin-bottom: 0.25rem;
            font-size: 0.7rem;
        }
    }

    @media (max-width: 576px) {
        .table thead th:nth-child(3) {
            display: none;
        }

        .table tbody td:nth-child(3) {
            display: none;
        }

        .table tbody td:first-child {
            width: 45%;
        }

        .table tbody td:nth-child(4) {
            width: 35%;
        }

        .table tbody td:last-child {
            width: 40%;
        }

        .card-body.p-3 {
            padding: 1rem !important;
        }

        .input-group.w-50 {
            width: 100% !important;
        }

        /* Adjust badge for mobile */
        .badge span {
            display: inline;
        }

        .badge i {
            margin-right: 0.25rem;
        }
    }

    @media (max-width: 400px) {
        .table tbody td:first-child {
            width: 50%;
        }

        .table tbody td:nth-child(4) {
            width: 30%;
        }

        .table tbody td:last-child {
            width: 40%;
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

        .badge {
            padding: 0.3rem 0.5rem !important;
            font-size: 0.7rem;
        }
    }
</style>

<script>
    // Initialize search functionality
    document.addEventListener('DOMContentLoaded', function () {
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

        // Optional: Add click handler to show policy details
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            row.addEventListener('click', function (e) {
                if (!e.target.closest('button')) { // Don't trigger if clicking a button
                    const policyDetails = JSON.parse(this.getAttribute('data-policy-details'));
                    alert(`Policy Details:\nClient: ${policyDetails.clientName}\nPolicy: ${policyDetails.policyNumber}\nExpires: ${policyDetails.expiryDate}\nStatus: ${policyDetails.status}\nDays Left: ${policyDetails.daysUntilExpiry}`);
                }
            });
        });
    });
</script>

<?php echo $this->endSection() ?>