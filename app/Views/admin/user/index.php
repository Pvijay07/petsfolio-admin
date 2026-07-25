<?php echo $this->extend('admin/includes/header'); ?>
<!--start content-->
<?php echo $this->section('content'); ?>
<style>
    /* Edit Modal Styling */
    #form-offcanvas {
        width: 450px !important;
    }

    .client-avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        /* background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); */
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        border: 4px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    #form-offcanvas .card {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    #form-offcanvas .card:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    #form-offcanvas .card-title {
        font-size: 0.95rem;
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 0.75rem;
    }

    #form-offcanvas .form-label {
        font-weight: 500;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        color: #495057;
    }

    #form-offcanvas .input-group-text {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #6c757d;
    }

    #form-offcanvas .form-control {
        border-color: #dee2e6;
        font-size: 0.9rem;
        padding: 0.5rem 0.75rem;
    }

    #form-offcanvas .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
    }

    #form-offcanvas .form-check-input {
        width: 2.5em;
        height: 1.25em;
    }

    #form-offcanvas .form-check-input:checked {
        background-color: #6366f1;
        border-color: #6366f1;
    }

    #form-offcanvas .form-check-label {
        font-size: 0.9rem;
        color: #495057;
    }

    #form-offcanvas .btn-primary {
        background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
        border: none;
        padding: 0.75rem;
        font-weight: 500;
    }

    #form-offcanvas .btn-primary:hover {
        background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    #form-offcanvas .btn-light {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 0.75rem;
        font-weight: 500;
    }

    #form-offcanvas .btn-light:hover {
        background-color: #e9ecef;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        #form-offcanvas {
            width: 100% !important;
        }

        .client-avatar-large {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
    }

    #send_push_notification {
        width: 500px !important;
    }

    #send_push_notification .offcanvas-header {
        background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%) !important;
        padding: 1.25rem 1.5rem;
    }

    #send_push_notification .preview-card {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        background: #f8f9fa;
    }

    #send_push_notification .preview-card .rounded-circle {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
    }

    #send_push_notification .card {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    #send_push_notification .card:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    #send_push_notification .card-title {
        font-size: 0.95rem;
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 0.75rem;
    }

    #send_push_notification .form-label {
        font-weight: 500;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        color: #495057;
    }

    #send_push_notification .input-group-text {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #6c757d;
    }

    #send_push_notification .form-control {
        border-color: #dee2e6;
        font-size: 0.9rem;
        padding: 0.5rem 0.75rem;
    }

    #send_push_notification textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    #send_push_notification .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
    }

    #send_push_notification .form-check-input {
        width: 2.5em;
        height: 1.25em;
    }

    #send_push_notification .form-check-input:checked {
        background-color: #6366f1;
        border-color: #6366f1;
    }

    #send_push_notification .form-check-label {
        font-size: 0.9rem;
        color: #495057;
    }

    #send_push_notification .btn-primary {
        background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
        border: none;
        padding: 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    #send_push_notification .btn-primary:hover {
        background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    #send_push_notification .btn-light {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 0.75rem;
        font-weight: 500;
    }

    #send_push_notification .btn-light:hover {
        background-color: #e9ecef;
    }

    #send_push_notification .alert-info {
        background-color: rgba(99, 102, 241, 0.1);
        border: 1px solid rgba(99, 102, 241, 0.2);
        color: #6366f1;
    }

    /* Character count styling */
    #charCount {
        font-size: 0.8rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        #send_push_notification {
            width: 100% !important;
        }

        #send_push_notification .offcanvas-body {
            padding: 1rem;
        }
    }

    /* Pagination Styling */
    .pagination-wrapper {
        padding: 1rem;
        background: #f8f9fa;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .pagination-info {
        color: #6c757d;
        font-size: 0.875rem;
    }

    .pagination {
        margin: 0;
        gap: 0.25rem;
    }

    .page-link {
        border: 1px solid #dee2e6;
        padding: 0.5rem 0.75rem;
        color: #6366f1;
        border-radius: 0.375rem;
        transition: all 0.2s;
        cursor: pointer;
    }

    .page-link:hover {
        background-color: #6366f1;
        border-color: #6366f1;
        color: white;
    }

    .page-item.active .page-link {
        background-color: #6366f1;
        border-color: #6366f1;
        color: white;
    }

    .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #fff;
        border-color: #dee2e6;
        cursor: not-allowed;
    }

    .items-per-page {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .items-per-page select {
        padding: 0.375rem 2rem 0.375rem 0.75rem;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        background-color: white;
        color: #495057;
        font-size: 0.875rem;
        cursor: pointer;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.5rem center;
        background-size: 16px 12px;
        appearance: none;
    }

    .items-per-page select:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
    }

    @media (max-width: 576px) {
        .pagination-wrapper {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .pagination {
            width: 100%;
            justify-content: center;
        }
    }

    /* Hide rows when paginated */
    .pagination-row {
        display: table-row;
    }
    
    .pagination-row.hidden {
        display: none;
    }
</style>

<main class="main-content">
    <div class="position-relative iq-banner">
        <!--Nav Start-->
        <?php echo $this->include('admin/nav_bar') ?>
    </div>

    <div class="conatiner-fluid content-inner px-4" id="bk-pgrs">
        <!-- Modern Header Section -->
        <div class="container-fluid bg-gradient-primary rounded-3  p-0 mb-4">
            <div class=" text-white p-5 rounded-3 shadow position-relative overflow-hidden">
                <div class="position-relative z-1">
                    <h1 class="h2 fw-bold mb-2 text-white">Clients</h1>
                    <p class="text-white-75 mb-0">Manage and monitor all clients in the system</p>
                </div>
                <i class="bi bi-people position-absolute end-0 bottom-0 opacity-10"
                    style="font-size: 14rem; transform: rotate(12deg); margin-right: -3rem; margin-bottom: -3rem;"></i>
            </div>
            <div class="p-3">
                <div class="card border-0 shadow-sm mb-0 rounded-3">
                    <div class="card-body p-3">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-auto d-flex align-items-center gap-3">
                                <!-- Search Box -->
                                <div class="input-group" style="width: 300px;">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0" id="searchClients"
                                        placeholder="Search clients by name, email, phone..."
                                        style="padding: 0.5rem 0.75rem;">
                                </div>

                                <!-- <button type="button" class="btn btn-primary d-flex align-items-center gap-1 add"
                                    data-crud-id="0" title="Create Duration" style="background: #0062ff !important;">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    New
                                </button> -->
                            </div>

                            <div class="col-auto">
                                <a href="#" onclick="downloadProviders()" class="btn btn-primary"
                                    style="background: #0062ff !important;">
                                    <i class="fa-solid fa-download"></i>
                                    Download
                                </a>
                                <button type="button" class="btn btn-warning w-80" id="send-bulk-notification"
                                    data-bs-toggle="offcanvas" data-bs-target="#send_push_notification"
                                    disabled="disabled" style="background: #000000 !important;">
                                    <i class="fa-regular fa-paper-plane"></i> Send Bulk Notification
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Main Table Card -->
        <div class="card border-0 shadow-sm overflow-hidden rounded-3">
            <div class="table-responsive">
                <table class="table table-hover" id="clientsTable">
                    <thead>
                        <tr style="background: #c6d2ff !important;">
                            <th style="width: 50px; background: #c6d2ff !important;">
                                <input type="checkbox" class="form-check-input" name="select_all_table"
                                    id="select-all-table" onclick="selectAllTable(this)">
                            </th>
                            <!-- <th class="text-dark text-center fs-6" style="background: #c6d2ff !important;">ID</th> -->
                            <th class="text-dark text-center fs-6" style="background: #c6d2ff !important;">Client</th>
                            <th class="text-dark text-center fs-6" style="background: #c6d2ff !important;">Pets</th>
                            <th class="text-dark text-center fs-6" style="background: #c6d2ff !important;">Location</th>
                            <th class="text-dark text-center fs-6" style="background: #c6d2ff !important;">Wallet</th>
                            <th class="text-dark text-center fs-6" style="background: #c6d2ff !important;">Status</th>
                            <th class="text-dark text-center fs-6" style="background: #c6d2ff !important;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php
                        $i = 1;
                        foreach ($clients as $client) {
                            ?>
                            <tr class="pagination-row" data-client-data='<?= htmlspecialchars(json_encode($client), ENT_QUOTES, 'UTF-8') ?>'>
                                <td>
                                    <input type="checkbox" class="form-check-input select-table-row"
                                        id="datatable-row-<?= $client->id ?>" name="datatable_ids[]"
                                        value="<?= $client->id ?>" data-module="users">
                                </td>
                                <!-- <td><?= $client->id ?></td> -->

                                <!-- Client Column with Email and Mobile -->
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php
                                        $name = $client->name ?? '';
                                        $initial = $name ? strtoupper(substr(trim($name), 0, 1)) : '?';
                                        ?>
                                        <!-- Avatar -->
                                        <div class="rounded-circle bg-primary bg-opacity-10 fw-bold d-flex align-items-center justify-content-center flex-shrink-0"
                                            style="width:32px; height:32px; border: 2px solid #e5dbff; color: #ffffff;">
                                            <?= $initial ?>
                                        </div>

                                        <!-- Details -->
                                        <div>
                                            <div class="fw-bold text-dark">
                                                <?= $client->name ?? '--' ?>
                                            </div>
                                            <div class="text-muted small">
                                                <i class="bi bi-envelope me-1"></i> <?= $client->email ?? '--' ?>
                                            </div>
                                            <div class="text-muted small">
                                                <i class="bi bi-telephone me-1"></i> <?= $client->phone ?? '--' ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Pets Column -->
                                <td>
                                    <?php if (!empty($client->pets)): ?>
                                        <div class="pet-pills">
                                            <?php foreach ($client->pets as $index => $pet): ?>
                                                <?php if ($index < 3): ?>
                                                    <span class="badge bg-info-subtle text-info border border-info mb-1 me-1" style="background-color: rgba(59, 130, 246, 0.1) !important;
        color: #2563eb !important;
        border: 1px solid rgba(59, 130, 246, 0.2) !important;">
                                                        <?= $pet->name ?>
                                                    </span>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                            <?php if (count($client->pets) > 3): ?>
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary">
                                                    +<?= count($client->pets) - 3 ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small">--</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Location Column -->
                                <td>
                                    <?php if (count($client->addresses) > 0): ?>
                                        <div class="d-flex align-items-center gap-1">
                                            <i class="bi bi-geo-alt text-primary small"></i>
                                            <span class="text-muted small text-truncate" style="max-width: 120px;">
                                                <?= $client->addresses[0]->state ?? 'N/A' ?>
                                            </span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small">--</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Wallet Amount -->
                                <td>
                                    <span class="badge bg-success-subtle text-success border border-success px-2 py-1"
                                        style="background: rgba(34, 197, 94, 0.1) !important; color: #16a34a !important;
        border: 1px solid rgba(34, 197, 94, 0.2) !important; font-weight: 600;">
                                        ₹<?= $client->wallet_amount ?>
                                    </span>
                                </td>

                                <!-- Status -->
                                <td>
                                    <div class="form-check form-switch d-inline-block">
                                        <input type="checkbox" class="form-check-input status"
                                            data-url="<?= base_url() ?>updateStatus" data-module="users"
                                            data-id="<?= $client->id ?>" value="<?= $client->status ?>"
                                            <?= $client->status == 'active' ? 'checked' : '' ?>>
                                    </div>
                                </td>

                                <!-- Action Column -->
                                <td>
                                    <div class="d-flex gap-1 align-items-center">
                                        <button type="button" class="btn btn-sm btn-outline-primary border-0 view-client-btn "
                                            style="padding:10px !important" data-client-id="<?= $client->id ?>"
                                            title="View Details">
                                            <i class="bi bi-eye fs-6"></i>
                                        </button>

                                        <button type="button" class="btn btn-sm btn-outline-info border-0 send-tc-btn"
                                            style="padding:10px !important" data-id="<?= $client->id ?>" title="Send T&C">
                                            <i class="fa-regular fa-envelope fs-6"></i>
                                        </button>

                                        <button type="button" data-assign-module="<?= $client->id ?>"
                                            style="padding:10px !important" data-assign-target="#send_push_notification"
                                            data-assign-event="employee_assign"
                                            class="btn btn-sm btn-outline-warning border-0" data-bs-toggle="offcanvas"
                                            data-bs-target="#send_push_notification" aria-label="Send Push Notification"
                                            data-id="<?= $client->id ?>" data-module="users" title="Send Notification">
                                            <i class="fa-regular fa-paper-plane fs-6"></i>
                                        </button>

                                        <button type="button" class="btn btn-sm btn-outline-primary border-0"
                                            style="padding:10px !important" data-bs-toggle="offcanvas"
                                            data-bs-target="#form-offcanvas" aria-label="Edit" data-bs-original-title="Edit"
                                            data-edit="<?= $client->id ?>" data-module="users"
                                            data-url="<?= base_url() ?>getUser" data-id="<?= $client->id ?>" title="Edit">
                                            <i class="fas fa-pencil-alt fs-6"></i>
                                        </button>

                                        <a href="<?= base_url() ?>deleteUser" style="padding:10px !important"
                                            id="delete-employees-<?= $client->id ?>"
                                            class="btn btn-sm btn-outline-danger border-0" data-type="ajax"
                                            data-method="DELETE" data-token="<?= csrf_hash() ?>" data-bs-toggle="tooltip"
                                            data-id="<?= $client->id ?>"
                                            data-confirm="Are you certain you want to delete it?" aria-label="Delete"
                                            title="Delete">
                                            <i class="fa fa-trash fs-6" aria-hidden="true"></i>
                                        </a>
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
            
            <!-- Pagination -->
            <div class="pagination-wrapper">
                <div class="pagination-info" id="paginationInfo">
                    Showing 0 to 0 of 0 entries
                </div>
                
                <div class="d-flex align-items-center gap-3">
                    <div class="items-per-page">
                        <label for="perPage" class="form-label mb-0">Show:</label>
                        <select id="perPage" class="form-select form-select-sm">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    
                    <nav aria-label="Page navigation">
                        <ul class="pagination" id="pagination">
                            <!-- Pagination will be generated here -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Send Push Notification Modal -->
    <form action="<?= base_url('sendNotification') ?>" method="POST" id="sendNotification1" class="sendNotification">
        <input type="hidden" id="selectedProviderIds" name="selectedProviderIds">
        <input type="hidden" name="module" value="users">
        <div class="offcanvas offcanvas-end" tabindex="-1" id="send_push_notification"
            aria-labelledby="form-offcanvasLabel">
            <div class="offcanvas-header border-bottom bg-primary text-white">
                <h5 class="offcanvas-title mb-1 text-white">
                    <i class="fa-regular fa-paper-plane me-2"></i>Send Push Notification
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body">
                                    <h6 class="card-title text-primary mb-3 fw-bold">
                                        <i class="fas fa-bell me-2"></i>Notification Content
                                    </h6>
                                    <div class="mb-4">
                                        <label for="Title" class="form-label fw-semibold">
                                            Notification Title
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-heading text-muted"></i>
                                            </span>
                                            <input type="text" class="form-control border-start-0" id="Title"
                                                name="title" required placeholder="Enter notification title">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label fw-semibold">Message
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light align-items-start pt-2 border-end-0">
                                                <i class="fas fa-comment text-muted"></i>
                                            </span>
                                            <textarea class="form-control border-start-0" id="description"
                                                name="message" required placeholder="Enter your notification message"
                                                rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="offcanvas-footer border-top p-3 bg-light">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light flex-fill" data-bs-dismiss="offcanvas">
                        <i class="fas fa-times me-2"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary flex-fill" id="sendNotificationBtn">
                        <i class="fa-regular fa-paper-plane me-2"></i> Send Notification
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Edit Client Modal -->
    <form action="<?= base_url() ?>update" id="updateUser">
        <div class="offcanvas offcanvas-end" tabindex="-1" id="form-offcanvas" aria-labelledby="form-offcanvasLabel"
            aria-modal="true" role="dialog">
            <div class="offcanvas-header border-bottom bg-primary text-white">
                <h5 class="offcanvas-title text-white" id="form-offcanvasLabel">Edit Client</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body " style="scrollbar-width:none">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <!-- Header with Avatar -->
                            <div class="text-center mb-4">
                                <div class="client-avatar-large bg-primary mx-auto mb-3">
                                    <i class="fas fa-user"></i>
                                </div>
                                <h6 class="text-black">Edit Client Information</h6>
                            </div>

                            <!-- Hidden Fields -->
                            <input type="hidden" name="id" value="" id="id">
                            <input type="hidden" name="module" value="users" id="module">

                            <!-- Client Information Form -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body">
                                    <h6 class="card-title text-primary mb-3 fw-bold">
                                        <i class="fas fa-user-circle me-2"></i>Personal Information
                                    </h6>

                                    <div class="row g-3">
                                        <!-- Name -->
                                        <div class="col-md-12">
                                            <label for="name" class="form-label">
                                                <i class="fas fa-user me-1"></i>Full Name
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-user text-muted"></i>
                                                </span>
                                                <input type="text" class="form-control border-start-0" name="name"
                                                    id="name" placeholder="Enter client name">
                                            </div>
                                        </div>

                                        <!-- Email -->
                                        <div class="col-md-12">
                                            <label for="email" class="form-label">
                                                <i class="fas fa-envelope me-1"></i>Email Address
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-envelope text-muted"></i>
                                                </span>
                                                <input type="email" class="form-control border-start-0" name="email"
                                                    id="email" placeholder="Enter email address">
                                            </div>
                                        </div>

                                        <!-- Phone Number -->
                                        <div class="col-md-12">
                                            <label for="phone" class="form-label">
                                                <i class="fas fa-phone me-1"></i>Phone Number
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-phone text-muted"></i>
                                                </span>
                                                <input type="tel" class="form-control border-start-0" name="phone"
                                                    id="phone" placeholder="Enter phone number">
                                            </div>
                                        </div>

                                        <!-- Gender -->
                                        <div class="col-md-12">
                                            <label class="form-label">
                                                <i class="fas fa-venus-mars me-1"></i>Gender
                                            </label>
                                            <div class="d-flex gap-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <input class="" type="radio" name="gender" id="male" value="Male">
                                                    <label class="form-check-label d-flex align-items-center"
                                                        for="male">
                                                        Male
                                                    </label>
                                                </div>
                                                <div class="d-flex align-items-center gap-3">
                                                    <input class="" type="radio" name="gender" id="female"
                                                        value="Female">
                                                    <label class="form-check-label d-flex align-items-center"
                                                        for="female">
                                                        Female
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Card -->
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title text-primary mb-3 fw-bold">
                                        <i class="fas fa-toggle-on me-2"></i>Account Status
                                    </h6>

                                    <div class="row align-items-center">
                                        <div class="col-md-12">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <label for="category-status" class="form-label mb-0">
                                                        Account Status
                                                    </label>
                                                    <p class="text-muted small mb-0">
                                                        Toggle to activate or deactivate client account
                                                    </p>
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" name="status" type="checkbox"
                                                        id="category-status" role="switch">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="offcanvas-footer border-top p-3 bg-light">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light flex-fill" data-bs-dismiss="offcanvas">
                        <i class="fas fa-times me-2"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="fas fa-save me-2"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </form>
    
    <!-- Client Details Drawer -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="clientDetailsDrawer"
        aria-labelledby="clientDetailsDrawerLabel" style="width: 500px;">
        <div class="offcanvas-header border-bottom bg-primary text-white">
            <h5 class="offcanvas-title text-white" id="clientDetailsDrawerLabel">Client Details</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0" style="scrollbar-width:none;">
            <div id="clientDetailsContent">
                <!-- Content will be loaded here via JavaScript -->
            </div>
        </div>
    </div>

    <?= $this->include('admin/footer') ?>
</main>

<style>
    /* Modern Table Styling */
    .table-hover tbody tr:hover {
        background-color: rgba(99, 102, 241, 0.03) !important;
    }

    .table thead th {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 1rem 0.75rem;
        border: none !important;
        font-weight: 600;
    }

    .table td {
        border: none !important;
        border-bottom: 1px solid #e5e7eb !important;
        padding: 0.75rem;
        vertical-align: middle;
    }

    .table tbody tr:last-child td {
        border-bottom: none !important;
    }

    /* Fixed gradient header */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
    }

    /* Button Styling */
    .btn-outline-primary {
        border-color: #dee2e6;
        color: #6c757d;
    }

    .btn-outline-primary:hover {
        border-color: #6366f1;
        color: #6366f1;
        background-color: transparent;
    }

    /* Status switch styling */
    .form-check-input:checked {
        background-color: #6366f1;
        border-color: #6366f1;
    }

    /* Badge styling */
    .badge {
        font-weight: 500;
        letter-spacing: 0.02em;
        font-size: 0.75rem;
        border-radius: 50px
    }

    /* Pet pills styling */
    .pet-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
    }

    /* Client details drawer styling */
    #clientDetailsDrawer .offcanvas-body {
        padding: 0;
    }

    .client-detail-section {
        padding: 1.5rem;
        border-bottom: 1px solid #e9ecef;
    }

    .client-detail-section:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .detail-value {
        font-size: 1rem;
        color: #212529;
        font-weight: 500;
    }

    .detail-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
    }

    .detail-card:last-child {
        margin-bottom: 0;
    }

    /* Avatar in drawer */
    .client-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        /* background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); */
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: bold;
        margin-right: 1rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {

        .table td,
        .table th {
            padding: 0.5rem;
            font-size: 0.875rem;
        }

        .badge {
            font-size: 0.7rem;
        }

        #clientDetailsDrawer {
            width: 100% !important;
        }
    }

    /* Small text styling */
    .small-text {
        font-size: 0.8rem;
    }
</style>

<!-- JavaScript for All Functionality -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // ============================
        // 1. PAGINATION FUNCTIONALITY
        // ============================
        let currentPage = 1;
        let rowsPerPage = 10;
        let totalRows = 0;
        let totalPages = 0;
        let allRows = [];

        // Initialize pagination
        function initPagination() {
            allRows = Array.from(document.querySelectorAll('#tableBody .pagination-row'));
            totalRows = allRows.length;
            updatePagination();
        }

        // Update pagination based on current page and rows per page
        function updatePagination() {
            totalPages = Math.ceil(totalRows / rowsPerPage);
            
            // Ensure current page is valid
            if (currentPage < 1) currentPage = 1;
            if (currentPage > totalPages) currentPage = totalPages;
            if (totalPages === 0) currentPage = 0;

            // Show/hide rows based on current page
            allRows.forEach((row, index) => {
                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;
                if (index >= start && index < end) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });

            // Update pagination info
            updatePaginationInfo();
            
            // Generate pagination buttons
            generatePaginationButtons();
        }

        // Update pagination info text
        function updatePaginationInfo() {
            const paginationInfo = document.getElementById('paginationInfo');
            if (totalRows === 0) {
                paginationInfo.textContent = 'Showing 0 to 0 of 0 entries';
                return;
            }
            
            const start = (currentPage - 1) * rowsPerPage + 1;
            const end = Math.min(currentPage * rowsPerPage, totalRows);
            paginationInfo.textContent = `Showing ${start} to ${end} of ${totalRows} entries`;
        }

        // Generate pagination buttons
        function generatePaginationButtons() {
            const pagination = document.getElementById('pagination');
            if (!pagination) return;
            
            let html = '';
            
            // Previous button
            if (currentPage > 1) {
                html += `<li class="page-item"><span class="page-link" onclick="changePage(${currentPage - 1})">&laquo;</span></li>`;
            } else {
                html += `<li class="page-item disabled"><span class="page-link">&laquo;</span></li>`;
            }
            
            // Page numbers
            const maxVisiblePages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
            
            if (endPage - startPage + 1 < maxVisiblePages) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }
            
            for (let i = startPage; i <= endPage; i++) {
                if (i === currentPage) {
                    html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
                } else {
                    html += `<li class="page-item"><span class="page-link" onclick="changePage(${i})">${i}</span></li>`;
                }
            }
            
            // Next button
            if (currentPage < totalPages) {
                html += `<li class="page-item"><span class="page-link" onclick="changePage(${currentPage + 1})">&raquo;</span></li>`;
            } else {
                html += `<li class="page-item disabled"><span class="page-link">&raquo;</span></li>`;
            }
            
            pagination.innerHTML = html;
        }

        // Change page
        window.changePage = function(page) {
            currentPage = page;
            updatePagination();
            // Update select all checkbox state
            updateSelectAllState();
        };

        // Handle rows per page change
        const perPageSelect = document.getElementById('perPage');
        if (perPageSelect) {
            perPageSelect.addEventListener('change', function() {
                rowsPerPage = parseInt(this.value);
                currentPage = 1; // Reset to first page
                updatePagination();
                // Update select all checkbox state
                updateSelectAllState();
            });
        }

        // ============================
        // 2. BULK NOTIFICATION FUNCTIONALITY
        // ============================
        let selectAllCheckbox = document.getElementById("select-all-table");
        let sendBulkNotificationBtn = document.getElementById("send-bulk-notification");
        let selectedProviderIds = document.getElementById("selectedProviderIds");

        function getSelectedIds() {
            return Array.from(document.querySelectorAll(".select-table-row:checked"))
                .map(checkbox => checkbox.value);
        }

        function updateButtonState() {
            let selectedIds = getSelectedIds();
            if (sendBulkNotificationBtn) {
                sendBulkNotificationBtn.disabled = selectedIds.length === 0;
            }
            if (selectedProviderIds) {
                selectedProviderIds.value = JSON.stringify(selectedIds);
            }
        }

        function updateSelectAllState() {
            if (!selectAllCheckbox) return;
            
            const visibleCheckboxes = Array.from(document.querySelectorAll('#tableBody .pagination-row:not(.hidden) .select-table-row'));
            const checkedVisibleCheckboxes = visibleCheckboxes.filter(cb => cb.checked);
            
            if (visibleCheckboxes.length === 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            } else if (checkedVisibleCheckboxes.length === visibleCheckboxes.length) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else if (checkedVisibleCheckboxes.length > 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = true;
            } else {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener("change", function() {
                const visibleCheckboxes = document.querySelectorAll('#tableBody .pagination-row:not(.hidden) .select-table-row');
                visibleCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
                updateButtonState();
            });
        }

        // Listen for checkbox changes
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('select-table-row')) {
                updateButtonState();
                updateSelectAllState();
            }
        });

        if (sendBulkNotificationBtn) {
            sendBulkNotificationBtn.addEventListener("click", function () {
                let selectedIds = getSelectedIds();
                if (selectedIds.length > 0 && selectedProviderIds) {
                    selectedProviderIds.value = JSON.stringify(selectedIds);
                }
            });
        }

        // Send T&C
        $(document).on('click', '.send-tc-btn', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Send T&C?',
                text: "Do you want to send the User Agreement to this client?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, send it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url() ?>send-user-agreement',
                        method: 'POST',
                        data: {
                            id: id,
                            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                        },
                        success: function(response) {
                            if (response.status) {
                                Swal.fire('Sent!', response.message, 'success');
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        }
                    });
                }
            });
        });

        // Send notification form submission
        $(document).on('click', '[data-assign-module]', function () {
            const dataId = $(this).data('id');
            const moduleId = $(this).data('module');
            const targetForm = $('#sendNotification1');
            targetForm.data('id', dataId);
            targetForm.data('module', moduleId);
        });

        $('#sendNotification1').on('submit', function (e) {
            e.preventDefault();

            const url = $(this).attr('action');
            let id = $(this).data('id') || $('#selectedProviderIds').val();
            if (!Array.isArray(id)) {
                id = id;
            }
            const title = $('#Title').val();
            const message = $('#description').val();
            let module = $(this).data('module') || $('#module').val();

            $.ajax({
                url: url,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'id': id,
                    title: title,
                    message: message,
                    module: module
                },
                traditional: true,
                success: function (response) {
                    if (response.status) {
                        Swal.fire('Success!', 'Notification sent successfully.', 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error sending notification:', error);
                    Swal.fire('Error!', 'An error occurred. Please try again later.', 'error');
                }
            });
        });

        // ============================
        // 3. CLIENT DETAILS DRAWER FUNCTIONALITY
        // ============================
        function formatClientDetails(clientData) {
            const client = typeof clientData === 'string' ? JSON.parse(clientData) : clientData;

            function formatDate(dateString) {
                if (!dateString) return "--";
                const options = {
                    year: "numeric",
                    month: "short",
                    day: "2-digit"
                };
                return new Date(dateString).toLocaleString("en-GB", options);
            }

            const initial = client.name ? client.name.charAt(0).toUpperCase() : '?';

            let petDetailsHtml = '';
            if (client.pets && client.pets.length > 0) {
                client.pets.forEach((pet) => {
                    petDetailsHtml += `
                    <div class="detail-card">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="detail-label">Name</div>
                                <div class="detail-value">${pet.name || '--'}</div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="detail-label">Breed</div>
                                <div class="detail-value">${pet.breed || '--'}</div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="detail-label">Gender</div>
                                <div class="detail-value">${pet.gender || '--'}</div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="detail-label">Age</div>
                                <div class="detail-value">${pet.age || '--'} years</div>
                            </div>
                        </div>
                    </div>
                `;
                });
            } else {
                petDetailsHtml = '<div class="text-center text-muted py-3">No pets registered</div>';
            }

            let addressDetailsHtml = '';
            if (client.addresses && client.addresses.length > 0) {
                client.addresses.forEach((address) => {
                    addressDetailsHtml += `
                    <div class="detail-card">
                        <div class="d-flex align-items-start gap-2 mb-2">
                            <div class="detail-value small-text">${address.address || '--'}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="detail-label">State</div>
                                <div class="detail-value">${address.state || '--'}</div>
                            </div>
                        </div>
                    </div>
                `;
                });
            } else {
                addressDetailsHtml = '<div class="text-center text-muted py-3">No addresses registered</div>';
            }

            return `
            <div class="client-detail-section bg-primary-subtle">
                <div class="d-flex align-items-center">
                    <div class="client-avatar bg-primary">${initial}</div>
                    <div>
                        <h4 class="mb-1 text-primary">${client.name || '--'}</h4>
                        <p class="text-muted mb-1"><i class="bi bi-envelope me-1"></i> ${client.email || '--'}</p>
                        <p class="text-muted mb-0"><i class="bi bi-telephone me-1"></i> ${client.phone || '--'}</p>
                    </div>
                </div>
            </div>
            <div class="client-detail-section">
                <h6 class="fw-bold mb-3 text-primary">Basic Information</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="detail-label">Client ID</div>
                        <div class="detail-value">${client.id || '--'}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="detail-label">Gender</div>
                        <div class="detail-value">${client.gender || 'Not specified'}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">
                            <span class="badge ${client.status === 'active' ? 'bg-success' : 'bg-danger'}">
                                ${client.status === 'active' ? 'Active' : 'Inactive'}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="detail-label">Joined Date</div>
                        <div class="detail-value">${formatDate(client.created_at)}</div>
                    </div>
                </div>
            </div>
            <div class="client-detail-section">
                <h6 class="fw-bold mb-3 text-primary">Wallet Information</h6>
                <div class="detail-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="detail-label">Current Balance</div>
                            <div class="detail-value h4 text-success">₹${client.wallet_amount || 0}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="client-detail-section">
                <h6 class="fw-bold mb-3 text-primary">Pets (${client.pets ? client.pets.length : 0})</h6>
                ${petDetailsHtml}
            </div>
            <div class="client-detail-section">
                <h6 class="fw-bold mb-3 text-primary">Addresses (${client.addresses ? client.addresses.length : 0})</h6>
                ${addressDetailsHtml}
            </div>
        `;
        }

        document.addEventListener('click', function (e) {
            if (e.target.closest('.view-client-btn')) {
                const button = e.target.closest('.view-client-btn');
                const row = button.closest('tr');
                const clientData = row.getAttribute('data-client-data');

                if (clientData) {
                    try {
                        const drawerContent = document.getElementById('clientDetailsContent');
                        drawerContent.innerHTML = formatClientDetails(clientData);
                        const drawer = new bootstrap.Offcanvas(document.getElementById('clientDetailsDrawer'));
                        drawer.show();
                    } catch (error) {
                        console.error('Error parsing client details:', error);
                        alert('Error loading client details. Please try again.');
                    }
                }
            }
        });

        // ============================
        // 4. STATUS UPDATE FUNCTIONALITY
        // ============================
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('status')) {
                const statusToggle = e.target;
                const url = statusToggle.getAttribute('data-url');
                const id = statusToggle.getAttribute('data-id');
                const module = statusToggle.getAttribute('data-module');
                const isChecked = statusToggle.checked;

                $.ajax({
                    url: url,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id,
                        'status': isChecked ? 'active' : 'inactive',
                        'module': module
                    },
                    success: function(response) {
                        if (response.status) {
                            Swal.fire('Success!', 'Status updated successfully.', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            statusToggle.checked = !isChecked;
                            Swal.fire('Error!', response.message || 'Failed to update status.', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating status:', error);
                        statusToggle.checked = !isChecked;
                        Swal.fire('Error!', 'An error occurred. Please try again later.', 'error');
                    }
                });
            }
        });

        // ============================
        // 5. SEARCH FUNCTIONALITY
        // ============================
        const searchInput = document.getElementById('searchClients');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const searchTerm = this.value.toLowerCase().trim();
                
                allRows.forEach(row => {
                    const rowText = Array.from(row.cells).slice(2, -1)
                        .map(cell => {
                            if (cell.querySelector('.text-muted')) {
                                return cell.textContent.toLowerCase();
                            }
                            return cell.textContent.toLowerCase();
                        })
                        .join(' ');

                    const isVisible = searchTerm === '' || rowText.includes(searchTerm);
                    if (isVisible) {
                        row.classList.remove('search-hidden');
                    } else {
                        row.classList.add('search-hidden');
                    }
                });

                // Update total visible rows for pagination
                const visibleRows = allRows.filter(row => !row.classList.contains('search-hidden'));
                totalRows = visibleRows.length;
                
                // Reset to first page when searching
                currentPage = 1;
                
                // Show/hide rows based on both search and pagination
                visibleRows.forEach((row, index) => {
                    const start = (currentPage - 1) * rowsPerPage;
                    const end = start + rowsPerPage;
                    if (index >= start && index < end) {
                        row.classList.remove('hidden');
                    } else {
                        row.classList.add('hidden');
                    }
                });

                // Hide non-visible rows
                allRows.forEach(row => {
                    if (row.classList.contains('search-hidden')) {
                        row.classList.add('hidden');
                    }
                });

                updatePaginationInfo();
                generatePaginationButtons();
                updateSelectAllState();
            });
        }

        // ============================
        // 6. INITIALIZE
        // ============================
        initPagination();
        updateButtonState();
    });

    // Global function for select all
    function selectAllTable(checkbox) {
        const visibleCheckboxes = document.querySelectorAll('#tableBody .pagination-row:not(.hidden) .select-table-row');
        visibleCheckboxes.forEach(cb => {
            cb.checked = checkbox.checked;
        });

        // Trigger change events
        document.dispatchEvent(new Event('change'));
    }

    // Download functionality
    function downloadProviders() {
        const selectedIds = Array.from(document.querySelectorAll('.select-table-row:checked'))
            .map(checkbox => checkbox.value);

        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');

        if (selectedIds.length === 0) {
            if (confirm('No clients selected. Download all clients?')) {
                let downloadUrl = "<?= base_url() ?>download";
                if (status) {
                    downloadUrl += `?status=${encodeURIComponent(status)}`;
                }
                window.location.href = downloadUrl;
            }
        } else {
            let downloadUrl = "<?= base_url() ?>download?ids=" + encodeURIComponent(JSON.stringify(selectedIds));
            if (status) {
                downloadUrl += `&status=${encodeURIComponent(status)}`;
            }
            window.location.href = downloadUrl;
        }
    }
</script>

<?php echo $this->endSection() ?>