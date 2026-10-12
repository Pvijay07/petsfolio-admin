<?= $this->extend('admin/includes/header'); ?>
<!--start content-->
<?= $this->section('content'); ?>
<style>
    /* Edit Modal Styling */
    #form-offcanvas {
        width: 450px !important;
    }

    .provider-avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
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


<<<<<<< HEAD
    /* Fixed gradient header */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
=======
    /* Modern Dashboard UI Styles matching Bookings */
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
    .nl-btn-action:hover:not(:disabled) {
        background: #f8fafc;
        color: #0f172a;
        border-color: #94a3b8;
    }
    .nl-btn-action:disabled {
        opacity: 0.6;
        cursor: not-allowed;
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
        padding: 9px 12px;
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
        background: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0 !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        letter-spacing: 0.06em !important;
        text-transform: uppercase !important;
        color: #64748b !important;
        padding: 14px 16px !important;
        vertical-align: middle !important;
    }
    .nl-table tbody td {
        padding: 14px 16px;
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

    /* Fixed gradient header matching bookings SaaS look */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 25%, #3730a3 60%, #4f46e5 100%) !important;
>>>>>>> origin/main
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

<<<<<<< HEAD
    /* Nav Tabs Styling */
    .nav-tabs {
        border-bottom: none;
    }

    .nav-tabs .nav-link {
        border: none;
        border-radius: 50px;
        padding: 0.75rem 1.5rem;
        color: #6c757d;
        background: transparent;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link:hover {
        color: #6366f1;
        background-color: rgba(99, 102, 241, 0.05);
    }

    .nav-tabs .nav-link.active {
        background: #3f30e3;
        color: #ffffff;
        border-bottom: 1px solid #6366f1;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
    }

=======
>>>>>>> origin/main
    /* Details icon styling */
    .details-control {
        cursor: pointer;
    }

    .view-details {
        transition: all 0.3s ease;
        font-size: 1.1rem;
    }

    .view-details:hover {
        transform: scale(1.3);
        color: #4f46e5 !important;
    }

    /* Avatar styling */
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: 2px solid #e5dbff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }


    /* Badge Enhancements */
    .badge-soft-primary {
        background-color: rgba(99, 102, 241, 0.1) !important;
        color: #6366f1 !important;
    }

    .badge-soft-success {
        background-color: rgba(34, 197, 94, 0.1) !important;
        color: #22c55e !important;
    }

    .badge-soft-warning {
        background-color: rgba(245, 158, 11, 0.1) !important;
        color: #f59e0b !important;
    }

    .badge-soft-info {
        background-color: rgba(14, 165, 233, 0.1) !important;
        color: #0ea5e9 !important;
    }

    .badge-soft-danger {
        background-color: rgba(239, 68, 68, 0.1) !important;
        color: #ef4444 !important;
    }

    /* Provider Info Layout */
    .provider-info-card {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .provider-initial {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
        box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.2);
    }

    /* Action Buttons */
    .action-btn {
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all 0.2s ease;
        border: 1px solid #e2e8f0;
        background: white;
        color: #64748b;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .action-btn-edit:hover {
        color: #6366f1;
        border-color: #6366f1;
        background-color: rgba(99, 102, 241, 0.05);
    }

    .action-btn-delete:hover {
        color: #ef4444;
        border-color: #ef4444;
        background-color: rgba(239, 68, 68, 0.05);
    }

    .action-btn-notify:hover {
        color: #0ea5e9;
        border-color: #0ea5e9;
        background-color: rgba(14, 165, 233, 0.05);
    }

    /* Form Control Styling */
    .form-control,
    .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

<<<<<<< HEAD
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);
=======
    /* Checkbox Styling */
    .form-check-input {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        background-image: none !important;
    }
    .form-check-input:not(:checked) {
        background-color: #ffffff !important;
        background-image: none !important;
        border-color: #cbd5e1 !important;
    }
    .form-check-input:checked {
        background-color: #3a57e8 !important;
        border-color: #3a57e8 !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='m6 10 3 3 6-6'/%3e%3c/svg%3e") !important;
    }
    .form-check-input:indeterminate {
        background-color: #ffffff !important;
        background-image: none !important;
        border-color: #cbd5e1 !important;
    }
    .form-switch .form-check-input {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba%280, 0, 0, 0.25%29'/%3e%3c/svg%3e") !important;
        width: 2em !important;
        border-radius: 2em !important;
    }
    .form-switch .form-check-input:checked {
        background-color: #3a57e8 !important;
        border-color: #3a57e8 !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23ffffff'/%3e%3c/svg%3e") !important;
>>>>>>> origin/main
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        #form-offcanvas {
            width: 100% !important;
        }

        .provider-avatar-large {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }

        .d-flex.gap-4.flex-wrap {
            gap: 1rem !important;
        }

        .nav-tabs .nav-link {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .avatar-circle {
            width: 32px;
            height: 32px;
            font-size: 0.875rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .offcanvas {
            width: 100% !important;
        }
    }

    /* Table cursor */
    .table-premium tbody tr {
        cursor: pointer;
    }

    /* Pagination Styling */
    .premium-pagination {
        display: flex;
        justify-content: center;
        width: 100%;
        margin: 0.5rem 0;
    }

    .premium-pagination ul.pagination {
        display: flex;
        align-items: center;
        justify-content: center;
        list-style: none;
        padding: 0;
        margin: 0;
        gap: 8px;
        flex-wrap: wrap;
    }

    .premium-pagination ul.pagination li {
        display: inline-block;
        margin: 0;
        padding: 0;
    }

    .premium-pagination ul.pagination li a,
    .premium-pagination ul.pagination li span {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        height: 38px;
        padding: 0 14px;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 600;
        color: #3f30e3;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        cursor: pointer;
    }

    .premium-pagination ul.pagination li a:hover {
        background-color: rgba(63, 48, 227, 0.08);
        color: #3f30e3;
        border-color: #3f30e3;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(63, 48, 227, 0.1);
    }

    /* Active State Styling */
    .premium-pagination ul.pagination li.active a,
    .premium-pagination ul.pagination li.active span {
        background: linear-gradient(135deg, #5555fa 0%, #3f30e3 100%) !important;
        color: #ffffff !important;
        border-color: transparent !important;
        box-shadow: 0 4px 12px rgba(63, 48, 227, 0.3) !important;
        pointer-events: none;
    }

    /* Disabled/Inactive State Styling */
    .premium-pagination ul.pagination li.disabled a,
    .premium-pagination ul.pagination li.disabled span,
    .premium-pagination ul.pagination li:not([class]) span {
        color: #94a3b8;
        background-color: #f8fafc;
        border-color: #e2e8f0;
        cursor: not-allowed;
        pointer-events: none;
        box-shadow: none;
    }

    /* Support standard bootstrap classes as well */
    .premium-pagination .page-item {
        display: inline-block;
    }

    .premium-pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        height: 38px;
        padding: 0 14px;
        border-radius: 10px !important;
        font-size: 0.875rem;
        font-weight: 600;
        color: #3f30e3;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .premium-pagination .page-link:hover {
        background-color: rgba(63, 48, 227, 0.08);
        color: #3f30e3;
        border-color: #3f30e3;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(63, 48, 227, 0.1);
    }

    .premium-pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #5555fa 0%, #3f30e3 100%) !important;
        color: #ffffff !important;
        border-color: transparent !important;
        box-shadow: 0 4px 12px rgba(63, 48, 227, 0.3) !important;
        pointer-events: none;
    }

    .premium-pagination .page-item.disabled .page-link {
        color: #94a3b8;
        background-color: #f8fafc;
        border-color: #e2e8f0;
        cursor: not-allowed;
        pointer-events: none;
        box-shadow: none;
    }
</style>

<main class="main-content">
    <div class="position-relative iq-banner">
        <?= $this->include('admin/nav_bar') ?>

    </div>

    <div class="conatiner-fluid content-inner mt-n5 py-0 px-4" id="bk-pgrs">
        <!-- Modern Header Section -->
        <?php
<<<<<<< HEAD
        $perServiceStatuses = ['pending_registration', 'pending_verification', 'approval', 'active', 'rejected', 'non_verified'];
=======
        $perServiceStatuses = ['pending_registration', 'pending_verification', 'approval', 'active', 'rejected', 'non_verified', 'pending_kyc'];
>>>>>>> origin/main
        $isServiceWise = in_array($type, $perServiceStatuses);

        $titles = [
            'active' => (isset($_GET['flow']) && $_GET['flow'] === 'old') ? 'Old SP - Verified' : 'Verified',
            'approval' => 'Manager Approval',
            'pending_registration' => 'Fee pending',
            'pending_verification' => (isset($_GET['overdue']) && $_GET['overdue'] === '1') ? 'waiting for call (>24h)' : 'waiting for call',
            'pending_kyc' => 'KYC pending',
            'rejected' => 'Rejected Services',
            'inactive' => 'Suspended',
            'non_verified' => 'Old SP-Non Verified'
        ];
        $displayTitle = $titles[$type] ?? 'Service Providers';
        ?>
<<<<<<< HEAD
        <div class="container-fluid p-0 mb-4 bg-gradient-primary rounded-3 overflow-hidden shadow-sm">
            <div class="text-white p-5 position-relative">
                <div class="position-relative z-1">
                    <h1 class="h2 fw-bold mb-2 text-white"><?= $displayTitle ?></h1>
                    <p class="text-white-75 mb-0">Manage and monitor your service provider network efficiently</p>
                </div>
                <i class="bi bi-shield-check position-absolute end-0 bottom-0 opacity-10"
                    style="font-size: 12rem; transform: rotate(12deg); margin-right: -2rem; margin-bottom: -2rem;"></i>
            </div>
        </div>

        <!-- Premium Filters Section -->
        <div class="card border-0 shadow-sm mb-4 rounded-3">
            <div class="card-body p-4">
                <div class="row g-4 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-muted small text-uppercase mb-2">Filter by Service</label>
                        <select id="serviceFilter" class="form-select border-0 bg-light">
                            <option value="">All Services</option>
                            <?php foreach ($all_services as $s): ?>
                                <option value="<?= $s->id ?>" <?= ($filter_service_id == $s->id) ? 'selected' : '' ?>><?= $s->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-muted small text-uppercase mb-2">Filter by City</label>
                        <select id="cityFilter" class="form-select border-0 bg-light">
                            <option value="">All Cities</option>
                            <?php foreach ($all_cities as $c): ?>
                                <option value="<?= $c->city ?>" <?= ($city == $c->city) ? 'selected' : '' ?>><?= $c->city ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-muted small text-uppercase mb-2">Filter by Area</label>
                        <select id="areaFilter" class="form-select border-0 bg-light">
                            <option value="">All Areas</option>
                            <?php foreach ($all_areas as $a): ?>
                                <option value="<?= $a->area ?>" <?= ($area == $a->area) ? 'selected' : '' ?>><?= $a->area ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button onclick="applyFilters()" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                                <i class="fas fa-filter me-2"></i>Apply Filters
                            </button>
                            <button onclick="resetFilters()" class="btn btn-light border py-2 px-3 shadow-sm" title="Reset Filters">
                                <i class="fas fa-undo"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons Card -->
        <div class="card border-0 shadow-sm mb-4 rounded-3">
            <div class="card-body p-3">
                <div class="row g-3 justify-content-between align-items-center">
                    <div class="col-md-7">
                        <div class="d-flex gap-3 align-items-center">
                            <div class="input-group" style="max-width: 400px;">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 ps-0" id="searchProviders"
                                    placeholder="Search by name, phone, or area...">
                            </div>

                            <?php if ($type === 'active'): ?>
                                <div style="min-width: 180px;">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 small text-muted">Exp:</span>
                                        <select id="experienceFilter" class="form-select border-start-0 ps-0"
                                            onchange="applyExperienceFilter(this.value)">
                                            <option value="">All Experience</option>
                                            <option value="0-1" <?= ($experience ?? '') === '0-1' ? 'selected' : '' ?>>0-1 Year</option>
                                            <option value="1-3" <?= ($experience ?? '') === '1-3' ? 'selected' : '' ?>>1-3 Years</option>
                                            <option value="3-5" <?= ($experience ?? '') === '3-5' ? 'selected' : '' ?>>3-5 Years</option>
                                            <option value="5+" <?= ($experience ?? '') === '5+' ? 'selected' : '' ?>>5+ Years</option>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="d-flex justify-content-md-end gap-2">
                            <button onclick="downloadProviders()" class="btn btn-light border fw-600 px-3">
                                <i class="fa-solid fa-download me-1 text-primary"></i> Download
                            </button>
                            <button type="button" class="btn btn-dark fw-600 px-3" id="send-bulk-notification"
                                data-bs-toggle="offcanvas" data-bs-target="#send_push_notification" disabled>
                                <i class="fa-regular fa-paper-plane me-1"></i> Bulk Notification
                            </button>
                        </div>
=======
        <!-- Hero Banner matching Bookings Page -->
        <div class="nl-hero-banner">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <div class="nl-live-pill">
                        <span class="nl-live-dot"></span>
                        <span>Live Provider Network</span>
                    </div>
                    <h2 class="fw-bold text-white mb-2" style="font-size: 28px; letter-spacing: -0.02em;">
                        <?= esc($displayTitle) ?>
                    </h2>
                    <p class="mb-0 text-white text-opacity-75" style="font-size: 13.5px; max-width: 540px; line-height: 1.55;">
                        Manage, verify, and monitor your service provider fleet, credentials, and real-time onboarding status.
                    </p>
                </div>

                <div class="col-lg-6">
                    <div class="d-flex align-items-center gap-2 flex-wrap justify-content-lg-end">
                        <div class="nl-stat-card">
                            <span class="label">Total in View</span>
                            <div class="value"><?= number_format($total_items ?? count($providers ?? [])) ?></div>
                        </div>
                        <?php if (isset($call_verification_count) && $call_verification_count > 0): ?>
                            <div class="nl-stat-card">
                                <span class="label">Call Verified</span>
                                <div class="value" style="color: #6ee7b7;"><?= number_format($call_verification_count) ?></div>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($manager_approval_count) && $manager_approval_count > 0): ?>
                            <div class="nl-stat-card">
                                <span class="label">Approvals Done</span>
                                <div class="value" style="color: #93c5fd;"><?= number_format($manager_approval_count) ?></div>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($total_sp_count) && $total_sp_count > 0): ?>
                            <div class="nl-stat-card">
                                <span class="label">Total Network</span>
                                <div class="value" style="color: #fde047;"><?= number_format($total_sp_count) ?></div>
                            </div>
                        <?php endif; ?>
>>>>>>> origin/main
                    </div>
                </div>
            </div>
        </div>
<<<<<<< HEAD
        <div class="card border-0 shadow-sm overflow-hidden rounded-3">
=======

        <!-- Segmented Status Tabs & Action Bar -->
        <?php
        $currStatus = $type ?? 'active';
        $currOverdue = isset($_GET['overdue']) && $_GET['overdue'] === '1';
        ?>
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div class="nl-segmented-tabs">
                <?php if (hasPermission('providers.view') || hasPermission('fee_pending.view')) : ?>
                    <a href="<?= base_url('providers?status=pending_registration') ?>"
                       class="nl-tab-pill <?= ($currStatus === 'pending_registration') ? 'active' : '' ?>">
                        <i class="fas fa-receipt"></i>
                        <span>Fee Pending</span>
                    </a>
                <?php endif; ?>

                <?php if (hasPermission('providers.view') || hasPermission('wait_for_call.view')) : ?>
                    <a href="<?= base_url('providers?status=pending_verification') ?>"
                       class="nl-tab-pill <?= ($currStatus === 'pending_verification' && !$currOverdue) ? 'active' : '' ?>">
                        <i class="fas fa-phone"></i>
                        <span>Waiting for Call</span>
                    </a>
                <?php endif; ?>

                <?php if (hasPermission('providers.view') || hasPermission('wait_for_call_24.view') || hasPermission('wait_for_call.view')) : ?>
                    <a href="<?= base_url('providers?status=pending_verification&overdue=1') ?>"
                       class="nl-tab-pill <?= ($currStatus === 'pending_verification' && $currOverdue) ? 'active' : '' ?>">
                        <i class="fas fa-clock text-danger"></i>
                        <span>Waiting (>24h)</span>
                    </a>
                <?php endif; ?>

                <?php if (hasPermission('providers.view') || hasPermission('manager_approval.view')) : ?>
                    <a href="<?= base_url('providers?status=approval') ?>"
                       class="nl-tab-pill <?= ($currStatus === 'approval') ? 'active' : '' ?>">
                        <i class="fas fa-user-check"></i>
                        <span>Manager Approval</span>
                    </a>
                <?php endif; ?>

                <?php if (hasPermission('providers.view') || hasPermission('kyc_pending.view')) : ?>
                    <a href="<?= base_url('providers?status=pending_kyc') ?>"
                       class="nl-tab-pill <?= ($currStatus === 'pending_kyc') ? 'active' : '' ?>">
                        <i class="fas fa-id-card"></i>
                        <span>KYC Pending</span>
                    </a>
                <?php endif; ?>

                <?php if (hasPermission('providers.view') || hasPermission('verified.view') || hasPermission('old_verified.view')) : ?>
                    <a href="<?= base_url('providers?status=active') ?>"
                       class="nl-tab-pill <?= ($currStatus === 'active') ? 'active' : '' ?>">
                        <i class="fas fa-check-circle"></i>
                        <span>Verified</span>
                    </a>
                <?php endif; ?>

                <?php if (hasPermission('providers.view') || hasPermission('old_non_verified.view')) : ?>
                    <a href="<?= base_url('providers?status=non_verified') ?>"
                       class="nl-tab-pill <?= ($currStatus === 'non_verified') ? 'active' : '' ?>">
                        <i class="fas fa-user-clock"></i>
                        <span>Old SP - Non Verified</span>
                    </a>
                <?php endif; ?>

                <?php if (hasPermission('providers.view') || hasPermission('suspended.view')) : ?>
                    <a href="<?= base_url('providers?status=inactive') ?>"
                       class="nl-tab-pill <?= ($currStatus === 'inactive') ? 'active' : '' ?>">
                        <i class="fas fa-ban"></i>
                        <span>Suspended</span>
                    </a>
                <?php endif; ?>

                <?php if (hasPermission('providers.view')) : ?>
                    <a href="<?= base_url('providers?status=rejected') ?>"
                       class="nl-tab-pill <?= ($currStatus === 'rejected') ? 'active' : '' ?>">
                        <i class="fas fa-times-circle"></i>
                        <span>Rejected</span>
                    </a>
                <?php endif; ?>
            </div>

            <div class="d-flex align-items-center gap-2">
                <button type="button" onclick="downloadProviders()" class="nl-btn-action" title="Export / Download Providers">
                    <i class="fa-solid fa-download text-primary"></i>
                    <span>Download</span>
                </button>
                <button type="button" class="nl-btn-action" id="send-bulk-notification"
                        data-bs-toggle="offcanvas" data-bs-target="#send_push_notification" disabled>
                    <i class="fa-regular fa-paper-plane text-indigo"></i>
                    <span>Bulk Notification</span>
                </button>
            </div>
        </div>

        <!-- Modern Filter Toolbar matching Bookings Page -->
        <div class="nl-filter-card">
            <div class="row g-3 align-items-center">
                <div class="col-lg-3 col-md-6">
                    <div class="nl-search-wrap">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="nl-search-input" id="searchProviders"
                               placeholder="Search name, phone, area..."
                               value="<?= esc($search ?? '') ?>"
                               onkeypress="if(event.key === 'Enter') applySearch()">
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <select id="serviceFilter" class="form-select nl-select w-100" onchange="applyFilters()">
                        <option value="">All Services</option>
                        <?php foreach ($all_services as $s): ?>
                            <option value="<?= $s->id ?>" <?= ($filter_service_id == $s->id) ? 'selected' : '' ?>><?= esc($s->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <select id="cityFilter" class="form-select nl-select w-100">
                        <option value="">All Cities</option>
                        <?php foreach ($all_cities as $c): ?>
                            <option value="<?= esc($c->city) ?>" <?= ($city == $c->city) ? 'selected' : '' ?>><?= esc($c->city) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <select id="areaFilter" class="form-select nl-select w-100" onchange="applyFilters()">
                        <option value="">All Areas</option>
                        <?php foreach ($all_areas as $a): ?>
                            <option value="<?= esc($a->area) ?>" <?= ($area == $a->area) ? 'selected' : '' ?>><?= esc($a->area) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if ($currStatus === 'active'): ?>
                    <div class="col-lg-2 col-md-6">
                        <select id="experienceFilter" class="form-select nl-select w-100" onchange="applyExperienceFilter(this.value)">
                            <option value="">All Experience</option>
                            <option value="0-1" <?= ($experience ?? '') === '0-1' ? 'selected' : '' ?>>0-1 Year</option>
                            <option value="1-3" <?= ($experience ?? '') === '1-3' ? 'selected' : '' ?>>1-3 Years</option>
                            <option value="3-5" <?= ($experience ?? '') === '3-5' ? 'selected' : '' ?>>3-5 Years</option>
                            <option value="5+" <?= ($experience ?? '') === '5+' ? 'selected' : '' ?>>5+ Years</option>
                        </select>
                    </div>
                <?php endif; ?>
                <div class="col-auto ms-auto d-flex align-items-center gap-2">
                    <button onclick="applyFilters()" class="btn btn-primary px-3 py-2 rounded-3 fw-bold small d-inline-flex align-items-center gap-1 shadow-sm">
                        <i class="fas fa-filter"></i> Apply
                    </button>
                    <button onclick="resetFilters()" class="btn btn-light border px-3 py-2 rounded-3 fw-bold small text-muted" title="Reset Filters">
                        <i class="fas fa-undo"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="nl-table-card">
>>>>>>> origin/main
            <div class="card-body p-0">
                <div id="singleProviderView">
                    <!-- Dynamic Table Content Based on Type -->
                    <?php if ($type === 'active' || empty($type)) : ?>
                        <!-- Verified Providers Table -->
                        <div id="verified">
                            <div class="table-responsive">
<<<<<<< HEAD
                                <table class="table table-hover table-premium" id="verifiedProvidersTable">
                                    <thead style="background: #c6d2ff !important;">
                                        <tr>
                                            <th style="width: 50px; background: #c6d2ff !important;" class="fs-6">
                                                <input type="checkbox" class="form-check-input" name="select_all_table"
                                                    id="select-all-verified" onclick="selectAllTable(this)">
                                            </th>
                                            <th class="text-center fs-6" style="width: 60px; background: #c6d2ff !important;">Select</th>
                                            <th style="background: #c6d2ff !important;" class="fs-6">Provider</th>
                                            <th class="text-center fs-6" style="background: #c6d2ff !important;">Service</th>
                                            <th class="text-center fs-6" style="background: #c6d2ff !important;">Rating</th>
                                            <th class="text-center fs-6" style="background: #c6d2ff !important;">Joined At</th>
                                            <th class="text-center fs-6" style="background: #c6d2ff !important;">Verified At</th>
                                            <th class="text-center fs-6" style="background: #c6d2ff !important;">Device & App</th>
                                            <th class="text-center fs-6" style="background: #c6d2ff !important;">Status</th>
                                            <th class="text-center fs-6" style="background: #c6d2ff !important;">Action</th>
=======
                                <table class="table table-hover table-premium nl-table" id="verifiedProvidersTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">
                                                <input type="checkbox" class="form-check-input" name="select_all_table"
                                                    id="select-all-verified" onclick="selectAllTable(this)">
                                            </th>
                                            <th class="text-center" style="width: 60px;">Select</th>
                                            <th>Provider</th>
                                            <th class="text-center">Service</th>
                                            <th class="text-center">Rating</th>
                                            <th class="text-center">Joined At</th>
                                            <th class="text-center">Call Verified At</th>
                                            <th class="text-center">Approved At</th>
                                            <th class="text-center">Verified By</th>
                                            <th class="text-center">Device & App</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
>>>>>>> origin/main
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($type === 'active' || empty($type)) :
                                            foreach ($providers as $provider) :
                                        ?>
                                                <tr data-details='<?= json_encode($provider) ?>'>
                                                    <td>
                                                        <input type="checkbox" class="form-check-input select-table-row"
                                                            value="<?= $provider->id; ?>">
                                                    </td>
                                                    <td class="details-control text-center">
                                                        <i class="fa fa-plus-circle text-primary view-details"
                                                            data-details='<?= htmlspecialchars(json_encode($provider), ENT_QUOTES, 'UTF-8') ?>'
                                                            style="cursor: pointer;"></i>
                                                    </td>

                                                    <td>
                                                        <div class="provider-info-card">
                                                            <?php
                                                            $name    = $provider->name ?? '';
                                                            $initial = $name ? strtoupper(substr(trim($name), 0, 1)) : '?';
                                                            ?>
                                                            <div class="provider-initial">
                                                                <?= $initial ?>
                                                            </div>

                                                            <div>
                                                                <div class="fw-bold text-dark">
                                                                    <a href="<?= base_url() ?>reviews/<?= $provider->id; ?>"
                                                                        target="_blank"
                                                                        class="text-decoration-none text-primary"><?= $provider->name ?? '--'; ?>
                                                                    </a>
                                                                </div>

                                                                <div class="d-flex align-items-center small text-muted mt-1">
                                                                    <i class="bi bi-telephone me-1"></i>
                                                                    <?= $provider->phone; ?>
                                                                </div>
                                                                <div class="text-muted small">
                                                                    <i class="bi bi-geo-alt me-1"></i>
                                                                    <?= $provider->city; ?> / <?= $provider->area; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-soft-primary px-3">
                                                            <?= $provider->service_name ?? 'General' ?>
                                                        </span>
                                                    </td>
                                                    <!-- <td class="text-center">
                                                        <span class="fw-bold text-dark">
                                                            <?= ($provider->experience ?? '0') . ' Years' ?>
                                                        </span>
                                                    </td> -->

                                                    <td class="text-center">
                                                        <span class="badge badge-soft-warning px-2">
                                                            <i class="fas fa-star me-1"></i><?= number_format($provider->rating, 1); ?>
                                                        </span>
                                                    </td>

                                                    <td class="text-center text-muted small"><?= date('d M Y', strtotime($provider->created_at)) ?></td>
<<<<<<< HEAD
                                                    <td class="text-center text-muted small"><?= !empty($provider->verifiedAt) ? date('d M Y', strtotime($provider->verifiedAt)) : '--' ?></td>
                                                    <td class="text-center">
                                                         <?php if (!empty($provider->latest_device)): ?>
                                                             <?php $dev = $provider->latest_device; ?>
                                                             <div class="d-flex flex-column align-items-center text-center">
                                                                 <span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1 mb-1" style="font-weight: 600;">
                                                                     v<?= htmlspecialchars($dev->app_version ?? 'N/A') ?> <?= !empty($dev->build_number) ? '('.$dev->build_number.')' : '' ?>
                                                                 </span>
                                                                 <span class="text-dark small fw-bold">
                                                                     <?= ucfirst(htmlspecialchars($dev->os ?? '')) ?> <?= htmlspecialchars($dev->os_version ?? '') ?>
                                                                 </span>
                                                                 <span class="text-muted small" style="font-size: 0.75rem;">
                                                                     <?= htmlspecialchars(trim(($dev->manufacturer ?? '').' '.($dev->model ?? $dev->device_name ?? ''))) ?>
                                                                 </span>
                                                                 <span class="badge <?= $dev->is_active ? 'bg-success' : 'bg-secondary' ?> mt-1" style="font-size: 0.65rem;">
                                                                     <?= $dev->is_active ? 'Active' : 'Inactive' ?>
                                                                 </span>
                                                             </div>
                                                         <?php else: ?>
                                                             <span class="text-muted small">No Device</span>
                                                         <?php endif; ?>
                                                     </td>
=======
                                                    <td class="text-center text-muted small"><?= !empty($provider->call_verified_at) ? date('d M Y h:i A', strtotime($provider->call_verified_at)) : '--' ?></td>
                                                    <td class="text-center text-muted small"><?= !empty($provider->approved_at) ? date('d M Y h:i A', strtotime($provider->approved_at)) : '--' ?></td>
                                                    <td class="text-center">
                                                        <span class="badge bg-light text-dark border px-3">
                                                            <i class="fas fa-user-shield me-1 text-primary"></i> <?= htmlspecialchars($provider->verifier_name ?? 'N/A') ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if (!empty($provider->latest_device)): ?>
                                                            <?php $dev = $provider->latest_device; ?>
                                                            <div class="d-flex flex-column align-items-center text-center">
                                                                <span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1 mb-1" style="font-weight: 600;">
                                                                    v<?= htmlspecialchars($dev->app_version ?? 'N/A') ?> <?= !empty($dev->build_number) ? '('.$dev->build_number.')' : '' ?>
                                                                </span>
                                                                <span class="text-dark small fw-bold">
                                                                    <?= ucfirst(htmlspecialchars($dev->os ?? '')) ?> <?= htmlspecialchars($dev->os_version ?? '') ?>
                                                                </span>
                                                                <span class="text-muted small" style="font-size: 0.75rem;">
                                                                    <?= htmlspecialchars(trim(($dev->manufacturer ?? '').' '.($dev->model ?? $dev->device_name ?? ''))) ?>
                                                                </span>
                                                                <span class="badge <?= $dev->is_active ? 'bg-success' : 'bg-secondary' ?> mt-1" style="font-size: 0.65rem;">
                                                                    <?= $dev->is_active ? 'Active' : 'Inactive' ?>
                                                                </span>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="text-center"><span class="text-muted small">No Device</span></div>
                                                        <?php endif; ?>
                                                    </td>
>>>>>>> origin/main

                                                    <td class="text-center">
                                                        <div class="form-check form-switch d-flex justify-content-center">
                                                            <input type="checkbox" class="form-check-input status"
                                                                data-module="service_providers" data-id="<?= $provider->id ?>"
                                                                data-url="<?php echo base_url() ?>updateStatus"
                                                                value="<?php echo $provider->status; ?>" <?php echo $provider->status == 'active' ? 'checked' : ''; ?>>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <div class="d-flex gap-2 justify-content-center">
                                                            <button type="button" data-assign-module="<?= $provider->id; ?>"
                                                                data-assign-target="#send_push_notification"
                                                                data-assign-event="employee_assign"
                                                                class="action-btn action-btn-notify"
                                                                data-bs-toggle="offcanvas" data-bs-target="#send_push_notification"
                                                                title="Send Push Notification" data-id="<?= $provider->id; ?>"
                                                                data-module="service_providers">
                                                                <i class="fa-regular fa-paper-plane"></i>
                                                            </button>

                                                            <button type="button" class="action-btn action-btn-edit"
                                                                data-bs-toggle="offcanvas" data-bs-target="#form-offcanvas"
                                                                title="Edit"
                                                                data-edit="<?= $provider->id; ?>" data-module="service_providers"
                                                                data-url="<?= base_url() ?>getUser" data-id="<?= $provider->id; ?>">
                                                                <i class="fas fa-pencil-alt"></i>
                                                            </button>
                                                            <a href="<?= base_url() ?>deleteUser"
                                                                id="delete-employees-<?= $provider->id; ?>"
                                                                class="action-btn action-btn-delete" data-type="ajax"
                                                                data-method="DELETE" data-token="<?= csrf_hash(); ?>"
                                                                data-id="<?= $provider->id; ?>"
                                                                data-confirm="Are you certain you want to delete it?"
                                                                title="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                        <?php endforeach;
                                        endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    <?php elseif ($type === 'approval') : ?>
                        <!-- Manager Approval Table -->
                        <div id="approval">
                            <div class="table-responsive">
<<<<<<< HEAD
                                <table class="table table-hover table-premium" id="nonverifiedProvidersTable">
                                    <thead style="background: #c6d2ff !important;">
                                        <tr>
                                            <th style="width: 50px; background: #c6d2ff !important;">
                                                <input type="checkbox" class="form-check-input" name="select_all_table"
                                                    id="select-all-nonverified" onclick="selectAllTable(this)">
                                            </th>
                                            <th class="text-center" style="width: 60px; background: #c6d2ff !important;">Details</th>
                                            <th style="background: #c6d2ff !important;">Provider Info</th>
                                            <th class="text-center" style="background: #c6d2ff !important;">Service</th>
                                            <th class="text-center" style="background: #c6d2ff !important;">Status</th>
                                            <th class="text-center" style="background: #c6d2ff !important;">Approval Action</th>
                                            <th class="text-center" style="background: #c6d2ff !important;">Joined At</th>
                                            <th class="text-center" style="background: #c6d2ff !important;">Action</th>
=======
                                <table class="table table-hover table-premium nl-table" id="nonverifiedProvidersTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">
                                                <input type="checkbox" class="form-check-input" name="select_all_table"
                                                    id="select-all-nonverified" onclick="selectAllTable(this)">
                                            </th>
                                            <th class="text-center" style="width: 60px;">Details</th>
                                            <th>Provider Info</th>
                                            <th class="text-center">Service</th>
                                            <th class="text-center">Rating</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Call Verified At</th>
                                            <th class="text-center">Approved At</th>
                                            <th class="text-center">Verified By</th>
                                            <th class="text-center">Approval Action</th>
                                            <th class="text-center">Joined At</th>
                                            <th class="text-center">Device & App</th>
                                            <th class="text-center">Action</th>
>>>>>>> origin/main
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($type === 'approval') :
                                            foreach ($providers as $provider) :
                                        ?>
                                                <tr data-details='<?= htmlspecialchars(json_encode($provider, JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8') ?>'>
                                                    <td>
                                                        <input type="checkbox" class="form-check-input select-table-row"
                                                            value="<?= $provider->id; ?>">
                                                    </td>
                                                    <td class="details-control text-center">
                                                        <i class="fa fa-plus-circle text-primary view-details"
                                                            data-details='<?= htmlspecialchars(json_encode($provider, JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8') ?>'
                                                            style="cursor: pointer;"></i>
                                                    </td>

                                                    <td>
                                                        <div class="provider-info-card">
                                                            <?php
                                                            $name    = $provider->name ?? '';
                                                            $initial = $name ? strtoupper(substr(trim($name), 0, 1)) : '?';
                                                            ?>
                                                            <div class="provider-initial">
                                                                <?= $initial ?>
                                                            </div>

                                                            <div>
                                                                <div class="fw-bold text-dark">
                                                                    <a href="<?= base_url() ?>reviews/<?= $provider->id; ?>"
                                                                        target="_blank"
                                                                        class="text-decoration-none text-primary"><?= $provider->name ?? '--'; ?></a>
                                                                    <?php if ($provider->total_services == 1) : ?>
                                                                        <span class="badge badge-soft-danger ms-1" style="font-size: 0.7rem;">New</span>
                                                                    <?php endif; ?>
                                                                </div>

                                                                <div class="d-flex align-items-center small text-muted mt-1">
                                                                    <i class="bi bi-telephone me-1"></i>
                                                                    <?= $provider->phone; ?>
                                                                </div>
                                                                <div class="text-muted small">
                                                                    <i class="bi bi-geo-alt me-1"></i>
                                                                    <?= $provider->city; ?> / <?= $provider->area; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="text-center">
                                                        <span class="badge badge-soft-info px-3">
                                                            <?= $provider->service_name ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
<<<<<<< HEAD
=======
                                                        <span class="badge badge-soft-warning px-2">
                                                            <i class="fas fa-star me-1"></i><?= number_format($provider->rating, 1); ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
>>>>>>> origin/main
                                                        <?php if ($provider->service_status === 'rejected') : ?>
                                                            <span class="badge badge-soft-danger px-3">Rejected</span>
                                                        <?php else : ?>
                                                            <span class="badge badge-soft-success px-3">Approved</span>
                                                        <?php endif; ?>
                                                    </td>
<<<<<<< HEAD
=======
                                                    <td class="text-center text-muted small"><?= !empty($provider->call_verified_at) ? date('d M Y h:i A', strtotime($provider->call_verified_at)) : '--' ?></td>
                                                    <td class="text-center text-muted small"><?= !empty($provider->approved_at) ? date('d M Y h:i A', strtotime($provider->approved_at)) : '--' ?></td>
                                                    <td class="text-center">
                                                        <span class="badge bg-light text-dark border px-3">
                                                            <i class="fas fa-user-shield me-1 text-primary"></i> <?= htmlspecialchars($provider->verifier_name ?? 'N/A') ?>
                                                        </span>
                                                    </td>
>>>>>>> origin/main

                                                    <td class="text-center">
                                                        <a href="<?= base_url('provider/verify/' . $provider->id . '/' . $provider->service_id) ?>"
                                                            class="btn btn-sm btn-primary rounded-pill px-3 fw-600" id="verifyProvider"
                                                            data-payment-paid="<?= $provider->payment_paid ?>"
                                                            data-call-verified="<?= $provider->call_verified ?>"
                                                            data-kyc-completed="<?= ($provider->verifications->identity_proof_verified ?? 0) ? 'true' : 'false' ?>">
                                                            <i class="fas fa-user-check me-1"></i> Update Status
                                                        </a>
                                                    </td>
                                                    <td class="text-center text-muted small"><?= date('d M Y', strtotime($provider->created_at)) ?></td>
<<<<<<< HEAD
=======
                                                    <td class="text-center">
                                                        <?php if (!empty($provider->latest_device)): ?>
                                                            <?php $dev = $provider->latest_device; ?>
                                                            <div class="d-flex flex-column align-items-center text-center">
                                                                <span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1 mb-1" style="font-weight: 600;">
                                                                    v<?= htmlspecialchars($dev->app_version ?? 'N/A') ?> <?= !empty($dev->build_number) ? '('.$dev->build_number.')' : '' ?>
                                                                </span>
                                                                <span class="text-dark small fw-bold">
                                                                    <?= ucfirst(htmlspecialchars($dev->os ?? '')) ?> <?= htmlspecialchars($dev->os_version ?? '') ?>
                                                                </span>
                                                                <span class="text-muted small" style="font-size: 0.75rem;">
                                                                    <?= htmlspecialchars(trim(($dev->manufacturer ?? '').' '.($dev->model ?? $dev->device_name ?? ''))) ?>
                                                                </span>
                                                                <span class="badge <?= $dev->is_active ? 'bg-success' : 'bg-secondary' ?> mt-1" style="font-size: 0.65rem;">
                                                                    <?= $dev->is_active ? 'Active' : 'Inactive' ?>
                                                                </span>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="text-center"><span class="text-muted small">No Device</span></div>
                                                        <?php endif; ?>
                                                    </td>
>>>>>>> origin/main
                                                    <td>
                                                        <div class="d-flex gap-2 justify-content-center">
                                                            <button type="button" data-assign-module="<?= $provider->id; ?>"
                                                                data-assign-target="#send_push_notification"
                                                                data-assign-event="employee_assign"
                                                                class="action-btn action-btn-notify"
                                                                data-bs-toggle="offcanvas" data-bs-target="#send_push_notification"
                                                                title="Send Push Notification" data-id="<?= $provider->id; ?>"
                                                                data-module="service_providers">
                                                                <i class="fa-regular fa-paper-plane"></i>
                                                            </button>

                                                            <button type="button" class="action-btn action-btn-edit"
                                                                data-bs-toggle="offcanvas" data-bs-target="#form-offcanvas"
                                                                title="Edit"
                                                                data-edit="<?= $provider->id; ?>" data-module="service_providers"
                                                                data-url="<?= base_url() ?>getUser" data-id="<?= $provider->id; ?>">
                                                                <i class="fas fa-pencil-alt"></i>
                                                            </button>
                                                            <a href="<?= base_url() ?>deleteUser"
                                                                id="delete-employees-<?= $provider->id; ?>"
                                                                class="action-btn action-btn-delete" data-type="ajax"
                                                                data-method="DELETE" data-token="<?= csrf_hash(); ?>"
                                                                data-id="<?= $provider->id; ?>"
                                                                data-confirm="Are you certain you want to delete it?"
                                                                title="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                        <?php endforeach;
                                        endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php elseif ($type === 'rejected') : ?>
                        <!-- Rejected Table -->
                        <div id="rejected">
                            <div class="table-responsive">
<<<<<<< HEAD
                                <table class="table table-hover table-premium" id="rejectedProvidersTable">
                                    <thead style="background: #c6d2ff !important;">
                                        <tr>
                                            <th style="width: 50px; background: #c6d2ff !important;">
                                                <input type="checkbox" class="form-check-input" name="select_all_table"
                                                    id="select-all-rejected" onclick="selectAllTable(this)">
                                            </th>
                                            <th style="width: 60px; background: #c6d2ff !important;" class="text-center">Details</th>
                                            <th style="background: #c6d2ff !important;">Provider Info</th>
                                            <th class="text-center" style="background: #c6d2ff !important;">Service</th>
                                            <th class="text-center" style="background: #c6d2ff !important;">Status</th>
                                            <th class="text-center" style="background: #c6d2ff !important;">Approval Action</th>
                                            <th class="text-center" style="background: #c6d2ff !important;">Joined At</th>
                                            <th class="text-center" style="background: #c6d2ff !important;">Action</th>
=======
                                <table class="table table-hover table-premium nl-table" id="rejectedProvidersTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">
                                                <input type="checkbox" class="form-check-input" name="select_all_table"
                                                    id="select-all-rejected" onclick="selectAllTable(this)">
                                            </th>
                                            <th style="width: 60px;" class="text-center">Details</th>
                                            <th>Provider Info</th>
                                            <th class="text-center">Service</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Approval Action</th>
                                            <th class="text-center">Joined At</th>
                                            <th class="text-center">Device & App</th>
                                            <th class="text-center">Action</th>
>>>>>>> origin/main
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($type === 'rejected') :
                                            foreach ($providers as $provider) :
                                        ?>
                                                <tr data-details='<?= htmlspecialchars(json_encode($provider, JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8') ?>'>
                                                    <td>
                                                        <input type="checkbox" class="form-check-input select-table-row"
                                                            value="<?= $provider->id; ?>">
                                                    </td>
                                                    <td class="details-control text-center">
                                                        <i class="fa fa-plus-circle text-primary view-details"
                                                            data-details='<?= htmlspecialchars(json_encode($provider, JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8') ?>'
                                                            style="cursor: pointer;"></i>
                                                    </td>
                                                    <td>
                                                        <div class="provider-info-card">
                                                            <?php
                                                            $name    = $provider->name ?? '';
                                                            $initial = $name ? strtoupper(substr(trim($name), 0, 1)) : '?';
                                                            ?>
                                                            <div class="provider-initial">
                                                                <?= $initial ?>
                                                            </div>
                                                            <div>
                                                                <div class="fw-bold text-dark">
                                                                    <a href="<?= base_url() ?>reviews/<?= $provider->id; ?>"
                                                                        target="_blank"
                                                                        class="text-decoration-none text-primary"><?= $provider->name ?? '--'; ?></a>
                                                                </div>
                                                                <div class="d-flex align-items-center small text-muted mt-1">
                                                                    <i class="bi bi-telephone me-1"></i><?= $provider->phone; ?>
                                                                </div>
                                                                <div class="text-muted small">
                                                                    <i class="bi bi-geo-alt me-1"></i><?= $provider->city; ?> / <?= $provider->area; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-soft-info px-3">
                                                            <?= $provider->service_name ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-soft-danger px-3">Rejected</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="<?= base_url('provider/verify/' . $provider->id . '/' . $provider->service_id) ?>"
                                                            class="btn btn-sm btn-primary rounded-pill px-3 fw-600"
                                                            data-payment-paid="<?= $provider->payment_paid ?>"
                                                            data-call-verified="<?= $provider->call_verified ?>"
                                                            data-kyc-completed="<?= ($provider->verifications->identity_proof_verified ?? 0) ? 'true' : 'false' ?>">
                                                            <i class="fas fa-user-check me-1"></i> Update Status
                                                        </a>
                                                    </td>
                                                    <td class="text-center text-muted small"><?= date('d M Y', strtotime($provider->created_at)) ?></td>
<<<<<<< HEAD
=======
                                                    <td class="text-center">
                                                        <?php if (!empty($provider->latest_device)): ?>
                                                            <?php $dev = $provider->latest_device; ?>
                                                            <div class="d-flex flex-column align-items-center text-center">
                                                                <span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1 mb-1" style="font-weight: 600;">
                                                                    v<?= htmlspecialchars($dev->app_version ?? 'N/A') ?> <?= !empty($dev->build_number) ? '('.$dev->build_number.')' : '' ?>
                                                                </span>
                                                                <span class="text-dark small fw-bold">
                                                                    <?= ucfirst(htmlspecialchars($dev->os ?? '')) ?> <?= htmlspecialchars($dev->os_version ?? '') ?>
                                                                </span>
                                                                <span class="text-muted small" style="font-size: 0.75rem;">
                                                                    <?= htmlspecialchars(trim(($dev->manufacturer ?? '').' '.($dev->model ?? $dev->device_name ?? ''))) ?>
                                                                </span>
                                                                <span class="badge <?= $dev->is_active ? 'bg-success' : 'bg-secondary' ?> mt-1" style="font-size: 0.65rem;">
                                                                    <?= $dev->is_active ? 'Active' : 'Inactive' ?>
                                                                </span>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="text-center"><span class="text-muted small">No Device</span></div>
                                                        <?php endif; ?>
                                                    </td>
>>>>>>> origin/main
                                                    <td>
                                                        <div class="d-flex gap-2 justify-content-center">
                                                            <button type="button" data-assign-module="<?= $provider->id; ?>"
                                                                data-assign-target="#send_push_notification"
                                                                data-assign-event="employee_assign"
                                                                class="action-btn action-btn-notify"
                                                                data-bs-toggle="offcanvas" data-bs-target="#send_push_notification"
                                                                title="Send Push Notification" data-id="<?= $provider->id; ?>"
                                                                data-module="service_providers">
                                                                <i class="fa-regular fa-paper-plane"></i>
                                                            </button>
                                                            <button type="button" class="action-btn action-btn-edit"
                                                                data-bs-toggle="offcanvas" data-bs-target="#form-offcanvas"
                                                                title="Edit"
                                                                data-edit="<?= $provider->id; ?>" data-module="service_providers"
                                                                data-url="<?= base_url() ?>getUser" data-id="<?= $provider->id; ?>">
                                                                <i class="fas fa-pencil-alt"></i>
                                                            </button>
                                                            <a href="<?= base_url() ?>deleteUser"
                                                                id="delete-employees-<?= $provider->id; ?>"
                                                                class="action-btn action-btn-delete" data-type="ajax"
                                                                data-method="DELETE" data-token="<?= csrf_hash(); ?>"
                                                                data-id="<?= $provider->id; ?>"
                                                                data-confirm="Are you certain you want to delete it?"
                                                                title="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                        <?php endforeach;
                                        endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    <?php elseif ($type === 'pending_registration') : ?>
                        <!-- Pending Registration Fee Tab -->
                        <div id="pending-registration">
<<<<<<< HEAD
                            <?php
                            $approved_cities = ['hyderabad', 'bengaluru', 'bangalore'];
                            $approved_providers = [];
                            $non_approved_providers = [];
                            
                            foreach ($providers as $provider) {
                                if (in_array(strtolower(trim($provider->city)), $approved_cities)) {
                                    $approved_providers[] = $provider;
                                } else {
                                    $non_approved_providers[] = $provider;
                                }
                            }
                            ?>

                            <ul class="nav nav-tabs mb-3 px-4" id="pendingRegistrationTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active fw-bold" id="approved-states-tab" data-bs-toggle="tab" data-bs-target="#approved-states" type="button" role="tab" aria-controls="approved-states" aria-selected="true">
                                        Approved States (<?= count($approved_providers) ?>)
                                        <br><small class="fw-normal" style="font-size: 0.65rem;">(Hyd, Blr - Flow: Details > Fee > Verification > KYC)</small>
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link fw-bold" id="non-approved-states-tab" data-bs-toggle="tab" data-bs-target="#non-approved-states" type="button" role="tab" aria-controls="non-approved-states" aria-selected="false">
                                        Non-Approved States (<?= count($non_approved_providers) ?>)
                                        <br><small class="fw-normal" style="font-size: 0.65rem;">(Others - Flow: Home > Fee > Verification > KYC)</small>
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="pendingRegistrationTabsContent">
                                <!-- Approved States Tab -->
                                <div class="tab-pane fade show active" id="approved-states" role="tabpanel" aria-labelledby="approved-states-tab">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-premium" id="pending-registration-approvedProvidersTable">
                                            <thead style="background: #c6d2ff !important;">
                                                <tr>
                                                    <th style="width: 50px; background: #c6d2ff !important;">
                                                        <input type="checkbox" name="select_all_table" class="form-check-input" id="select-all-pending-registration-approved" onclick="selectAllTable(this)">
                                                    </th>
                                                    <th class="text-center" style="width: 60px; background: #c6d2ff !important;">Details</th>
                                                    <th style="background: #c6d2ff !important;">Provider Info</th>
                                                    <th class="text-center" style="background: #c6d2ff !important;">Service</th>
                                                    <th class="text-center" style="background: #c6d2ff !important;">Profile Completion</th>
                                                    <th class="text-center" style="background: #c6d2ff !important;">Joined At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($approved_providers as $provider) : ?>
                                                    <tr data-details='<?= htmlspecialchars(json_encode($provider, JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8') ?>'>
                                                        <td><input type="checkbox" class="form-check-input select-table-row" value="<?= $provider->id; ?>"></td>
                                                        <td class="details-control text-center">
                                                            <i class="fa fa-plus-circle text-primary view-details" data-details='<?= htmlspecialchars(json_encode($provider, JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8') ?>' style="cursor: pointer;"></i>
                                                        </td>
                                                        <td>
                                                            <div class="provider-info-card">
                                                                <div class="provider-initial"><?= $provider->name ? strtoupper(substr(trim($provider->name), 0, 1)) : '?' ?></div>
                                                                <div>
                                                                    <div class="fw-bold text-dark">
                                                                        <a href="<?= base_url() ?>reviews/<?= $provider->id; ?>" target="_blank" class="text-decoration-none text-primary"><?= $provider->name ?? '--'; ?></a>
                                                                    </div>
                                                                    <div class="d-flex align-items-center small text-muted mt-1"><i class="bi bi-telephone me-1"></i><?= $provider->phone; ?></div>
                                                                    <div class="text-muted small"><i class="bi bi-geo-alt me-1"></i><?= $provider->city; ?> / <?= $provider->area; ?></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center"><span class="badge badge-soft-info px-3"><?= $provider->service_name ?></span></td>
                                                        <td>
                                                            <div class="d-flex align-items-center gap-3 px-3">
                                                                <div class="progress flex-grow-1" style="height: 8px;">
                                                                    <div class="progress-bar bg-<?= $provider->completionPercentage == 100 ? 'success' : ($provider->completionPercentage > 50 ? 'warning' : 'danger') ?>" role="progressbar" style="width: <?= $provider->completionPercentage ?>%"></div>
                                                                </div>
                                                                <span class="badge badge-soft-dark border py-1 px-2"><?= $provider->completionPercentage ?>%</span>
                                                            </div>
                                                        </td>
                                                        <td class="text-center text-muted small"><?= date('d M Y', strtotime($provider->created_at)) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Non-Approved States Tab -->
                                <div class="tab-pane fade" id="non-approved-states" role="tabpanel" aria-labelledby="non-approved-states-tab">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-premium" id="pending-registration-non-approvedProvidersTable">
                                            <thead style="background: #c6d2ff !important;">
                                                <tr>
                                                    <th style="width: 50px; background: #c6d2ff !important;">
                                                        <input type="checkbox" name="select_all_table" class="form-check-input" id="select-all-pending-registration-non-approved" onclick="selectAllTable(this)">
                                                    </th>
                                                    <th class="text-center" style="width: 60px; background: #c6d2ff !important;">Details</th>
                                                    <th style="background: #c6d2ff !important;">Provider Info</th>
                                                    <th class="text-center" style="background: #c6d2ff !important;">Service</th>
                                                    <th class="text-center" style="background: #c6d2ff !important;">Profile Completion</th>
                                                    <th class="text-center" style="background: #c6d2ff !important;">Joined At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($non_approved_providers as $provider) : ?>
                                                    <tr data-details='<?= htmlspecialchars(json_encode($provider, JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8') ?>'>
                                                        <td><input type="checkbox" class="form-check-input select-table-row" value="<?= $provider->id; ?>"></td>
                                                        <td class="details-control text-center">
                                                            <i class="fa fa-plus-circle text-primary view-details" data-details='<?= htmlspecialchars(json_encode($provider, JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8') ?>' style="cursor: pointer;"></i>
                                                        </td>
                                                        <td>
                                                            <div class="provider-info-card">
                                                                <div class="provider-initial"><?= $provider->name ? strtoupper(substr(trim($provider->name), 0, 1)) : '?' ?></div>
                                                                <div>
                                                                    <div class="fw-bold text-dark">
                                                                        <a href="<?= base_url() ?>reviews/<?= $provider->id; ?>" target="_blank" class="text-decoration-none text-primary"><?= $provider->name ?? '--'; ?></a>
                                                                    </div>
                                                                    <div class="d-flex align-items-center small text-muted mt-1"><i class="bi bi-telephone me-1"></i><?= $provider->phone; ?></div>
                                                                    <div class="text-muted small"><i class="bi bi-geo-alt me-1"></i><?= $provider->city; ?> / <?= $provider->area; ?></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center"><span class="badge badge-soft-info px-3"><?= $provider->service_name ?></span></td>
                                                        <td>
                                                            <div class="d-flex align-items-center gap-3 px-3">
                                                                <div class="progress flex-grow-1" style="height: 8px;">
                                                                    <div class="progress-bar bg-<?= $provider->completionPercentage == 100 ? 'success' : ($provider->completionPercentage > 50 ? 'warning' : 'danger') ?>" role="progressbar" style="width: <?= $provider->completionPercentage ?>%"></div>
                                                                </div>
                                                                <span class="badge badge-soft-dark border py-1 px-2"><?= $provider->completionPercentage ?>%</span>
                                                            </div>
                                                        </td>
                                                        <td class="text-center text-muted small"><?= date('d M Y', strtotime($provider->created_at)) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
=======
                            <div class="table-responsive">
                                <table class="table table-hover table-premium nl-table" id="pending-registrationProvidersTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">
                                                <input type="checkbox" name="select_all_table" class="form-check-input" id="select-all-pending-registration" onclick="selectAllTable(this)">
                                            </th>
                                            <th class="text-center" style="width: 60px;">Details</th>
                                            <th>Provider Info</th>
                                            <th class="text-center">Service</th>
                                            <th class="text-center">Profile Completion</th>
                                            <th class="text-center">Joined At</th>
                                            <th class="text-center">Device & App</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($providers as $provider) : ?>
                                            <tr data-details='<?= htmlspecialchars(json_encode($provider, JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8') ?>'>
                                                <td><input type="checkbox" class="form-check-input select-table-row" value="<?= $provider->id; ?>"></td>
                                                <td class="details-control text-center">
                                                    <i class="fa fa-plus-circle text-primary view-details" data-details='<?= htmlspecialchars(json_encode($provider, JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8') ?>' style="cursor: pointer;"></i>
                                                </td>
                                                <td>
                                                    <div class="provider-info-card">
                                                        <div class="provider-initial"><?= $provider->name ? strtoupper(substr(trim($provider->name), 0, 1)) : '?' ?></div>
                                                        <div>
                                                            <div class="fw-bold text-dark">
                                                                <a href="<?= base_url() ?>reviews/<?= $provider->id; ?>" target="_blank" class="text-decoration-none text-primary"><?= $provider->name ?? '--'; ?></a>
                                                            </div>
                                                            <div class="d-flex align-items-center small text-muted mt-1"><i class="bi bi-telephone me-1"></i><?= $provider->phone; ?></div>
                                                            <div class="text-muted small"><i class="bi bi-geo-alt me-1"></i><?= $provider->city; ?> / <?= $provider->area; ?></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center"><span class="badge badge-soft-info px-3"><?= $provider->service_name ?></span></td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3 px-3">
                                                        <div class="progress flex-grow-1" style="height: 8px;">
                                                            <div class="progress-bar bg-<?= $provider->completionPercentage == 100 ? 'success' : ($provider->completionPercentage > 50 ? 'warning' : 'danger') ?>" role="progressbar" style="width: <?= $provider->completionPercentage ?>%"></div>
                                                        </div>
                                                        <span class="badge badge-soft-dark border py-1 px-2"><?= $provider->completionPercentage ?>%</span>
                                                    </div>
                                                </td>
                                                <td class="text-center text-muted small"><?= date('d M Y', strtotime($provider->created_at)) ?></td>
                                                <td class="text-center">
                                                    <?php if (!empty($provider->latest_device)): ?>
                                                        <?php $dev = $provider->latest_device; ?>
                                                        <div class="d-flex flex-column align-items-center text-center">
                                                            <span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1 mb-1" style="font-weight: 600;">
                                                                v<?= htmlspecialchars($dev->app_version ?? 'N/A') ?> <?= !empty($dev->build_number) ? '('.$dev->build_number.')' : '' ?>
                                                            </span>
                                                            <span class="text-dark small fw-bold">
                                                                <?= ucfirst(htmlspecialchars($dev->os ?? '')) ?> <?= htmlspecialchars($dev->os_version ?? '') ?>
                                                            </span>
                                                            <span class="text-muted small" style="font-size: 0.75rem;">
                                                                <?= htmlspecialchars(trim(($dev->manufacturer ?? '').' '.($dev->model ?? $dev->device_name ?? ''))) ?>
                                                            </span>
                                                            <span class="badge <?= $dev->is_active ? 'bg-success' : 'bg-secondary' ?> mt-1" style="font-size: 0.65rem;">
                                                                <?= $dev->is_active ? 'Active' : 'Inactive' ?>
                                                            </span>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="text-center"><span class="text-muted small">No Device</span></div>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
>>>>>>> origin/main
                            </div>
                        </div>


                    <?php elseif ($type === 'pending_verification') : ?>
                        <!-- Waiting for Call verification Tab -->
                        <div id="waiting-call-verification">
                            <div class="table-responsive">
<<<<<<< HEAD
                                <table class="table table-hover table-premium" id="waiting-call-verificationProvidersTable">
                                    <thead style="background: #c6d2ff !important;">
                                        <tr>
                                            <th style="width: 50px; background: #c6d2ff !important;">
=======
                                <table class="table table-hover table-premium nl-table" id="waiting-call-verificationProvidersTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">
>>>>>>> origin/main
                                                <input type="checkbox" class="form-check-input" name="select_all_table"
                                                    id="select-all-waiting-call-verification"
                                                    onclick="selectAllTable(this)">
                                            </th>
<<<<<<< HEAD
                                            <th style="width: 60px; background: #c6d2ff !important;" class="text-center">Details</th>
                                            <th style="background: #c6d2ff !important;">Provider Info</th>
                                            <th class="text-center" style="background: #c6d2ff !important;">Service</th>
                                            <th class="text-center" style="background: #c6d2ff !important;">Progress</th>
                                            <th class="text-center" style="background: #c6d2ff !important;">Call Action</th>
                                            <th class="text-center" style="background: #c6d2ff !important;">Joined At</th>
=======
                                            <th style="width: 60px;" class="text-center">Details</th>
                                            <th>Provider Info</th>
                                            <th class="text-center">Service</th>
                                            <th class="text-center">Progress</th>
                                            <th class="text-center">Call Attempts</th>
                                            <th class="text-center">Call Action</th>
                                            <th class="text-center">Joined At</th>
                                            <th class="text-center">Device & App</th>
>>>>>>> origin/main
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($type === 'pending_verification') :
                                            foreach ($providers as $provider) :
                                        ?>
                                                <tr data-details='<?= htmlspecialchars(json_encode($provider, JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8') ?>'>
                                                    <td>
                                                        <input type="checkbox" class="form-check-input select-table-row"
                                                            value="<?= $provider->id; ?>">
                                                    </td>
                                                    <td class="details-control text-center">
                                                        <i class="fa fa-plus-circle text-primary view-details"
                                                            data-details='<?= htmlspecialchars(json_encode($provider, JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8') ?>'
                                                            style="cursor: pointer;"></i>
                                                    </td>
                                                    <td>
                                                        <div class="provider-info-card">
                                                            <?php
                                                            $name    = $provider->name ?? '';
                                                            $initial = $name ? strtoupper(substr(trim($name), 0, 1)) : '?';
                                                            ?>
                                                            <div class="provider-initial">
                                                                <?= $initial ?>
                                                            </div>
                                                            <div>
                                                                <div class="fw-bold text-dark">
                                                                    <a href="<?= base_url() ?>reviews/<?= $provider->id; ?>"
                                                                        target="_blank"
                                                                        class="text-decoration-none text-primary"><?= $provider->name ?? '--'; ?></a>
                                                                    <?php if ($provider->total_services == 1) : ?>
                                                                        <span class="badge badge-soft-danger ms-1" style="font-size: 0.7rem;">New</span>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="d-flex align-items-center small text-muted mt-1">
                                                                    <i class="bi bi-telephone me-1"></i><?= $provider->phone; ?>
                                                                </div>
                                                                <div class="text-muted small">
                                                                    <i class="bi bi-geo-alt me-1"></i><?= $provider->city; ?> /
                                                                    <?= $provider->area; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-soft-info px-3">
                                                            <?= $provider->service_name ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-3 px-3">
                                                            <div class="progress flex-grow-1" style="height: 8px;">
                                                                <div class="progress-bar bg-<?= $provider->completionPercentage == 100 ? 'success' : ($provider->completionPercentage > 50 ? 'warning' : 'danger') ?>"
                                                                    role="progressbar"
                                                                    style="width: <?= $provider->completionPercentage ?>%"
                                                                    aria-valuenow="<?= $provider->completionPercentage ?>"
                                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                            <span class="badge badge-soft-dark border py-1 px-2 text-dark"><?= $provider->completionPercentage ?>%</span>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
<<<<<<< HEAD
=======
                                                        <?php
                                                        $attempts = (int) ($provider->call_attempts_count ?? 0);
                                                        $totalAtt = (int) ($provider->total_call_attempts ?? $attempts);
                                                        $displayAtt = max($attempts, $totalAtt);
                                                        $lastCall = $provider->last_call_attempt_at ?? null;
                                                        ?>
                                                        <?php if ($displayAtt > 0) : ?>
                                                            <span class="badge bg-danger rounded-pill px-3 py-1 fw-bold" style="font-size: 0.8rem;" title="Service attempts: <?= $attempts ?>, Total attempts: <?= $totalAtt ?>">
                                                                <i class="bi bi-telephone-outbound-fill me-1"></i> <?= $displayAtt ?> <?= $displayAtt === 1 ? 'Attempt' : 'Attempts' ?>
                                                            </span>
                                                            <?php if ($lastCall) : ?>
                                                                <div class="text-muted small mt-1" style="font-size: 0.72rem;">
                                                                    Last: <?= date('d M, h:i A', strtotime($lastCall)) ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        <?php else : ?>
                                                            <span class="badge bg-light text-muted border rounded-pill px-3 py-1" style="font-size: 0.78rem;">
                                                                0 Attempts
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
>>>>>>> origin/main
                                                        <a href="<?= base_url('provider/verify/' . $provider->id . '/' . $provider->service_id) ?>"
                                                            class="btn btn-sm btn-info text-white rounded-pill px-3 fw-600">
                                                            <i class="bi bi-telephone-fill me-1"></i> Verify Call
                                                        </a>
                                                    </td>
                                                    <td class="text-center text-muted small"><?= date('d M Y', strtotime($provider->created_at)) ?></td>
<<<<<<< HEAD
=======
                                                    <td class="text-center">
                                                        <?php if (!empty($provider->latest_device)): ?>
                                                            <?php $dev = $provider->latest_device; ?>
                                                            <div class="d-flex flex-column align-items-center text-center">
                                                                <span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1 mb-1" style="font-weight: 600;">
                                                                    v<?= htmlspecialchars($dev->app_version ?? 'N/A') ?> <?= !empty($dev->build_number) ? '('.$dev->build_number.')' : '' ?>
                                                                </span>
                                                                <span class="text-dark small fw-bold">
                                                                    <?= ucfirst(htmlspecialchars($dev->os ?? '')) ?> <?= htmlspecialchars($dev->os_version ?? '') ?>
                                                                </span>
                                                                <span class="text-muted small" style="font-size: 0.75rem;">
                                                                    <?= htmlspecialchars(trim(($dev->manufacturer ?? '').' '.($dev->model ?? $dev->device_name ?? ''))) ?>
                                                                </span>
                                                                <span class="badge <?= $dev->is_active ? 'bg-success' : 'bg-secondary' ?> mt-1" style="font-size: 0.65rem;">
                                                                    <?= $dev->is_active ? 'Active' : 'Inactive' ?>
                                                                </span>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="text-center"><span class="text-muted small">No Device</span></div>
                                                        <?php endif; ?>
                                                    </td>
>>>>>>> origin/main
                                                </tr>
                                        <?php endforeach;
                                        endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    <?php elseif ($type === 'pending_kyc') : ?>
                        <!-- Pending KYC Verification Tab -->
                        <div id="pending-kyc">
                            <div class="table-responsive">
<<<<<<< HEAD
                                <table class="table table-hover table-premium" id="pending-kycProvidersTable">
                                    <thead style="background: #c6d2ff !important;">
                                        <tr>
                                            <th style="width: 50px; background: #c6d2ff !important;">
                                                <input type="checkbox" class="form-check-input" name="select_all_table"
                                                    id="select-all-pending-kyc" onclick="selectAllTable(this)">
                                            </th>
                                            <th style="width: 60px; background: #c6d2ff !important;" class="text-center">Details</th>
                                            <th style="background: #c6d2ff !important;">Provider Info</th>
                                            <th class="text-center" style="background: #c6d2ff !important;">Service</th>
                                            <th class="text-center" style="background: #c6d2ff !important;">Progress</th>
                                            <th class="text-center" style="background: #c6d2ff !important;">Joined At</th>
=======
                                <table class="table table-hover table-premium nl-table" id="pending-kycProvidersTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">
                                                <input type="checkbox" class="form-check-input" name="select_all_table"
                                                    id="select-all-pending-kyc" onclick="selectAllTable(this)">
                                            </th>
                                            <th style="width: 60px;" class="text-center">Details</th>
                                            <th>Provider Info</th>
                                            <th class="text-center">Service</th>
                                            <th class="text-center">Progress</th>
                                            <th class="text-center">Joined At</th>
                                            <th class="text-center">Device & App</th>
>>>>>>> origin/main
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($type === 'pending_kyc') :
                                            foreach ($providers as $provider) :
                                        ?>
                                                <tr data-details='<?= htmlspecialchars(json_encode($provider, JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8') ?>'>
                                                    <td>
                                                        <input type="checkbox" class="form-check-input select-table-row"
                                                            value="<?= $provider->id; ?>">
                                                    </td>
                                                    <td class="details-control text-center">
                                                        <i class="fa fa-plus-circle text-primary view-details"
                                                            data-details='<?= htmlspecialchars(json_encode($provider, JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8') ?>'
                                                            style="cursor: pointer;"></i>
                                                    </td>
                                                    <td>
                                                        <div class="provider-info-card">
                                                            <?php
                                                            $name    = $provider->name ?? '';
                                                            $initial = $name ? strtoupper(substr(trim($name), 0, 1)) : '?';
                                                            ?>
                                                            <div class="provider-initial">
                                                                <?= $initial ?>
                                                            </div>
                                                            <div>
                                                                <div class="fw-bold text-dark">
                                                                    <a href="<?= base_url() ?>reviews/<?= $provider->id; ?>"
                                                                        target="_blank"
                                                                        class="text-decoration-none text-primary"><?= $provider->name ?? '--'; ?></a>
                                                                </div>
                                                                <div class="d-flex align-items-center small text-muted mt-1">
                                                                    <i class="bi bi-telephone me-1"></i><?= $provider->phone; ?>
                                                                </div>
                                                                <div class="text-muted small">
                                                                    <i class="bi bi-geo-alt me-1"></i><?= $provider->city; ?> /
                                                                    <?= $provider->area; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                        $tmpSrv = '--';
<<<<<<< HEAD
=======
                                                        // var_dump($provider->services);
>>>>>>> origin/main
                                                        if (!empty($provider->services) && is_array($provider->services)) {
                                                            $serviceNames = array_column($provider->services, 'service_name');
                                                            $tmpSrv       = implode(', ', $serviceNames);
                                                        } elseif (!empty($provider->service_name)) {
                                                            $tmpSrv = $provider->service_name;
                                                        }
                                                        ?>
                                                        <span class="badge badge-soft-primary px-3"><?= $tmpSrv ?></span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-3 px-3">
                                                            <div class="progress flex-grow-1" style="height: 8px;">
                                                                <div class="progress-bar bg-<?= $provider->completionPercentage == 100 ? 'success' : ($provider->completionPercentage > 50 ? 'warning' : 'danger') ?>"
                                                                    role="progressbar"
                                                                    style="width: <?= $provider->completionPercentage ?>%"
                                                                    aria-valuenow="<?= $provider->completionPercentage ?>"
                                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                            <span class="badge badge-soft-dark border py-1 px-2"><?= $provider->completionPercentage ?>%</span>
                                                        </div>
                                                    </td>
                                                    <td class="text-center text-muted small"><?= date('d M Y', strtotime($provider->created_at)) ?></td>
<<<<<<< HEAD
=======
                                                    <td class="text-center">
                                                        <?php if (!empty($provider->latest_device)): ?>
                                                            <?php $dev = $provider->latest_device; ?>
                                                            <div class="d-flex flex-column align-items-center text-center">
                                                                <span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1 mb-1" style="font-weight: 600;">
                                                                    v<?= htmlspecialchars($dev->app_version ?? 'N/A') ?> <?= !empty($dev->build_number) ? '('.$dev->build_number.')' : '' ?>
                                                                </span>
                                                                <span class="text-dark small fw-bold">
                                                                    <?= ucfirst(htmlspecialchars($dev->os ?? '')) ?> <?= htmlspecialchars($dev->os_version ?? '') ?>
                                                                </span>
                                                                <span class="text-muted small" style="font-size: 0.75rem;">
                                                                    <?= htmlspecialchars(trim(($dev->manufacturer ?? '').' '.($dev->model ?? $dev->device_name ?? ''))) ?>
                                                                </span>
                                                                <span class="badge <?= $dev->is_active ? 'bg-success' : 'bg-secondary' ?> mt-1" style="font-size: 0.65rem;">
                                                                    <?= $dev->is_active ? 'Active' : 'Inactive' ?>
                                                                </span>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="text-center"><span class="text-muted small">No Device</span></div>
                                                        <?php endif; ?>
                                                    </td>
>>>>>>> origin/main
                                                </tr>
                                        <?php endforeach;
                                        endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php elseif ($type === 'inactive') : ?>
                        <!-- Suspended / Inactive Providers Table -->
                        <div id="suspended">
                            <div class="table-responsive">
<<<<<<< HEAD
                                <table class="table table-hover table-premium" id="inactiveProvidersTable">
                                    <thead style="background: #c6d2ff !important;">
                                        <tr>
                                            <th style="width: 50px; background: #c6d2ff !important;">
                                                <input type="checkbox" class="form-check-input" name="select_all_table"
                                                    id="select-all-inactive" onclick="selectAllTable(this)">
                                            </th>
                                            <th style="width: 60px; background: #c6d2ff !important;" class="text-center">Details</th>
                                            <th style="background: #c6d2ff !important;">Provider Info</th>
                                            <th class="text-center" style="background: #c6d2ff !important;">Service</th>
                                            <th class="text-center" style="background: #c6d2ff !important;">Rating</th>
                                            <th class="text-center" style="background: #c6d2ff !important;">Joined At</th>
                                            <th class="text-center" style="background: #c6d2ff !important;">Status</th>
                                            <th class="text-center" style="background: #c6d2ff !important;">Action</th>
=======
                                <table class="table table-hover table-premium nl-table" id="inactiveProvidersTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">
                                                <input type="checkbox" class="form-check-input" name="select_all_table"
                                                    id="select-all-inactive" onclick="selectAllTable(this)">
                                            </th>
                                            <th style="width: 60px;" class="text-center">Details</th>
                                            <th>Provider Info</th>
                                            <th class="text-center">Service</th>
                                            <th class="text-center">Rating</th>
                                            <th class="text-center">Joined At</th>
                                            <th class="text-center">Device & App</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
>>>>>>> origin/main
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($providers as $provider) :
                                        ?>
                                            <tr data-details='<?= json_encode($provider) ?>'>
                                                <td>
                                                    <input type="checkbox" class="form-check-input select-table-row"
                                                        value="<?= $provider->id; ?>">
                                                </td>
                                                <td class="details-control text-center">
                                                    <i class="fa fa-plus-circle text-primary view-details"
                                                        data-details='<?= htmlspecialchars(json_encode($provider), ENT_QUOTES, 'UTF-8') ?>'
                                                        style="cursor: pointer;"></i>
                                                </td>
                                                <td>
                                                    <div class="provider-info-card">
                                                        <?php
                                                        $name    = $provider->name ?? '';
                                                        $initial = $name ? strtoupper(substr(trim($name), 0, 1)) : '?';
                                                        ?>
                                                        <div class="provider-initial">
                                                            <?= $initial ?>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold text-dark">
                                                                <a href="<?= base_url() ?>reviews/<?= $provider->id; ?>"
                                                                    target="_blank"
                                                                    class="text-decoration-none text-primary"><?= $provider->name ?? '--'; ?></a>
                                                            </div>
                                                            <div class="d-flex align-items-center small text-muted mt-1">
                                                                <i class="bi bi-telephone me-1"></i><?= $provider->phone; ?>
                                                            </div>
                                                            <div class="text-muted small">
                                                                <i class="bi bi-geo-alt me-1"></i><?= $provider->city; ?> /
                                                                <?= $provider->area; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    $tmpSrv = '--';
                                                    if (!empty($provider->services) && is_array($provider->services)) {
                                                        $serviceNames = array_column($provider->services, 'service_name');
                                                        $tmpSrv       = implode(', ', $serviceNames);
                                                    } elseif (!empty($provider->service_name)) {
                                                        $tmpSrv = $provider->service_name;
                                                    }
                                                    ?>
                                                    <span class="badge badge-soft-info px-3"><?= $tmpSrv ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-soft-warning px-2">
                                                        <i class="fas fa-star me-1"></i><?= number_format($provider->rating, 1); ?>
                                                    </span>
                                                </td>
                                                <td class="text-center text-muted small"><?= date('d M Y', strtotime($provider->created_at)) ?></td>
                                                <td class="text-center">
<<<<<<< HEAD
=======
                                                    <?php if (!empty($provider->latest_device)): ?>
                                                        <?php $dev = $provider->latest_device; ?>
                                                        <div class="d-flex flex-column align-items-center text-center">
                                                            <span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1 mb-1" style="font-weight: 600;">
                                                                v<?= htmlspecialchars($dev->app_version ?? 'N/A') ?> <?= !empty($dev->build_number) ? '('.$dev->build_number.')' : '' ?>
                                                            </span>
                                                            <span class="text-dark small fw-bold">
                                                                <?= ucfirst(htmlspecialchars($dev->os ?? '')) ?> <?= htmlspecialchars($dev->os_version ?? '') ?>
                                                            </span>
                                                            <span class="text-muted small" style="font-size: 0.75rem;">
                                                                <?= htmlspecialchars(trim(($dev->manufacturer ?? '').' '.($dev->model ?? $dev->device_name ?? ''))) ?>
                                                            </span>
                                                            <span class="badge <?= $dev->is_active ? 'bg-success' : 'bg-secondary' ?> mt-1" style="font-size: 0.65rem;">
                                                                <?= $dev->is_active ? 'Active' : 'Inactive' ?>
                                                            </span>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="text-center"><span class="text-muted small">No Device</span></div>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
>>>>>>> origin/main
                                                    <div class="form-check form-switch d-flex justify-content-center">
                                                        <input type="checkbox" class="form-check-input status"
                                                            data-module="service_providers" data-id="<?= $provider->id ?>"
                                                            data-url="<?php echo base_url() ?>updateStatus"
                                                            value="<?php echo $provider->status; ?>" <?php echo $provider->status == 'active' ? 'checked' : ''; ?>>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2 justify-content-center">
                                                        <button type="button" class="action-btn action-btn-edit"
                                                            data-bs-toggle="offcanvas" data-bs-target="#form-offcanvas"
                                                            title="Edit"
                                                            data-edit="<?= $provider->id; ?>" data-module="service_providers"
                                                            data-url="<?= base_url() ?>getUser" data-id="<?= $provider->id; ?>">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </button>
                                                        <a href="<?= base_url() ?>deleteUser"
                                                            id="delete-employees-<?= $provider->id; ?>"
                                                            class="action-btn action-btn-delete" data-type="ajax"
                                                            data-method="DELETE" data-token="<?= csrf_hash(); ?>"
                                                            data-id="<?= $provider->id; ?>"
                                                            data-confirm="Are you certain you want to delete it?"
                                                            title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php elseif ($type === 'non_verified') : ?>
                        <!-- Non Verified Providers Table -->
                        <div id="non-verified">
                            <div class="table-responsive">
<<<<<<< HEAD
                                <table class="table table-hover table-premium" id="non-verifiedProvidersTable">
                                    <thead style="background: #c6d2ff !important;">
                                        <tr>
                                            <th style="width: 50px; background: #c6d2ff !important;">
                                                <input type="checkbox" class="form-check-input" name="select_all_table"
                                                    id="select-all-non-verified" onclick="selectAllTable(this)">
                                            </th>
                                            <th style="width: 60px; background: #c6d2ff !important;" class="text-center">Details</th>
                                            <th style="background: #c6d2ff !important;">Provider Info</th>
                                            <th class="text-center" style="background: #c6d2ff !important;">Service</th>
                                            <th class="text-center" style="background: #c6d2ff !important;">Status</th>
                                            <th class="text-center" style="background: #c6d2ff !important;">Joined At</th>
                                            <th class="text-center" style="background: #c6d2ff !important;">Action</th>
=======
                                <table class="table table-hover table-premium nl-table" id="non-verifiedProvidersTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">
                                                <input type="checkbox" class="form-check-input" name="select_all_table"
                                                    id="select-all-non-verified" onclick="selectAllTable(this)">
                                            </th>
                                            <th style="width: 60px;" class="text-center">Details</th>
                                            <th>Provider Info</th>
                                            <th class="text-center">Service</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Joined At</th>
                                            <th class="text-center">Device & App</th>
                                            <th class="text-center">Action</th>
>>>>>>> origin/main
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($providers as $provider) :
                                        ?>
                                            <tr data-details='<?= htmlspecialchars(json_encode($provider, JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8') ?>'>
                                                <td>
                                                    <input type="checkbox" class="form-check-input select-table-row"
                                                        value="<?= $provider->id; ?>">
                                                </td>
                                                <td class="details-control text-center">
                                                    <i class="fa fa-plus-circle text-primary view-details"
                                                        data-details='<?= htmlspecialchars(json_encode($provider, JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8') ?>'
                                                        style="cursor: pointer;"></i>
                                                </td>
                                                <td>
                                                    <div class="provider-info-card">
                                                        <?php
                                                        $name    = $provider->name ?? '';
                                                        $initial = $name ? strtoupper(substr(trim($name), 0, 1)) : '?';
                                                        ?>
                                                        <div class="provider-initial">
                                                            <?= $initial ?>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold text-dark">
                                                                <a href="<?= base_url() ?>reviews/<?= $provider->id; ?>"
                                                                    target="_blank"
                                                                    class="text-decoration-none text-primary"><?= $provider->name ?? '--'; ?></a>
                                                            </div>
                                                            <div class="d-flex align-items-center small text-muted mt-1">
                                                                <i class="bi bi-telephone me-1"></i><?= $provider->phone; ?>
                                                            </div>
                                                            <div class="text-muted small">
                                                                <i class="bi bi-geo-alt me-1"></i><?= $provider->city; ?> /
                                                                <?= $provider->area; ?>
<<<<<<< HEAD
                                                     <div class="d-flex align-items-center">
                                                         <div class="provider-avatar me-3">
                                                             <i class="fas fa-user-tie text-primary"></i>
                                                         </div>
                                                         <div>
                                                             <div class="fw-bold text-dark">
                                                                 <a href="<?= base_url() ?>getUserDetails/<?= $provider->id ?>"
                                                                     target="_blank"
                                                                     class="text-decoration-none text-dark hover-primary"><?= $provider->name ?? '--'; ?></a>
                                                             </div>
                                                             <div class="d-flex align-items-center small text-muted mt-1">
                                                                 <i class="bi bi-telephone me-1 opacity-50"></i><?= $provider->phone; ?>
                                                             </div>
                                                             <div class="text-muted extra-small mt-1">
                                                                 <i class="bi bi-geo-alt me-1 opacity-50"></i><?= $provider->city; ?> / <?= $provider->area; ?>
                                                             </div>
                                                         </div>
                                                     </div>
                                                 </td>
                                                 <td class="text-center">
                                                      <a href="<?= base_url('provider/verify/' . $provider->id . '/' . ($provider->service_id ?? '')) ?>"
                                                         class="badge bg-primary-soft text-primary btn-verify-chip px-3 py-2 text-decoration-none">
                                                         <i class="fas fa-check-circle me-1 small"></i> <?= $provider->service_name ?? 'General' ?>
                                                      </a>
                                                 </td>
                                                 <td class="text-center">
                                                     <?php 
                                                        $statusClass = 'status-pending';
                                                        if($provider->status == 'approved') $statusClass = 'status-approved';
                                                        if($provider->status == 'rejected') $statusClass = 'status-rejected';
                                                        if($provider->status == 'call_pending') $statusClass = 'status-call';
                                                     ?>
                                                     <span class="status-pill <?= $statusClass ?>">
                                                         <?= str_replace('_', ' ', $provider->status) ?>
                                                     </span>
                                                 </td>
                                                 <td class="text-center text-muted small"><?= date('d M Y', strtotime($provider->created_at)) ?></td>
=======
                                                                <div class="d-flex align-items-center">
                                                                    <div class="provider-avatar me-3">
                                                                        <i class="fas fa-user-tie text-primary"></i>
                                                                    </div>
                                                                    <div>
                                                                        <div class="fw-bold text-dark">
                                                                            <a href="<?= base_url() ?>getUserDetails/<?= $provider->id ?>"
                                                                                target="_blank"
                                                                                class="text-decoration-none text-dark hover-primary"><?= $provider->name ?? '--'; ?></a>
                                                                        </div>
                                                                        <div class="d-flex align-items-center small text-muted mt-1">
                                                                            <i class="bi bi-telephone me-1 opacity-50"></i><?= $provider->phone; ?>
                                                                        </div>
                                                                        <div class="text-muted extra-small mt-1">
                                                                            <i class="bi bi-geo-alt me-1 opacity-50"></i><?= $provider->city; ?> / <?= $provider->area; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                </td>
                                                <td class="text-center">
                                                    <a href="<?= base_url('provider/verify/' . $provider->id . '/' . ($provider->service_id ?? '')) ?>"
                                                        class="badge bg-primary-soft text-primary btn-verify-chip px-3 py-2 text-decoration-none">
                                                        <i class="fas fa-check-circle me-1 small"></i> <?= $provider->service_name ?? 'General' ?>
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    $statusClass = 'status-pending';
                                                    if ($provider->status == 'approved') $statusClass = 'status-approved';
                                                    if ($provider->status == 'rejected') $statusClass = 'status-rejected';
                                                    if ($provider->status == 'call_pending') $statusClass = 'status-call';
                                                    ?>
                                                    <span class="status-pill <?= $statusClass ?>">
                                                        <?= str_replace('_', ' ', $provider->status) ?>
                                                    </span>
                                                </td>
                                                <td class="text-center text-muted small"><?= date('d M Y', strtotime($provider->created_at)) ?></td>
                                                <td class="text-center">
                                                    <?php if (!empty($provider->latest_device)): ?>
                                                        <?php $dev = $provider->latest_device; ?>
                                                        <div class="d-flex flex-column align-items-center text-center">
                                                            <span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1 mb-1" style="font-weight: 600;">
                                                                v<?= htmlspecialchars($dev->app_version ?? 'N/A') ?> <?= !empty($dev->build_number) ? '('.$dev->build_number.')' : '' ?>
                                                            </span>
                                                            <span class="text-dark small fw-bold">
                                                                <?= ucfirst(htmlspecialchars($dev->os ?? '')) ?> <?= htmlspecialchars($dev->os_version ?? '') ?>
                                                            </span>
                                                            <span class="text-muted small" style="font-size: 0.75rem;">
                                                                <?= htmlspecialchars(trim(($dev->manufacturer ?? '').' '.($dev->model ?? $dev->device_name ?? ''))) ?>
                                                            </span>
                                                            <span class="badge <?= $dev->is_active ? 'bg-success' : 'bg-secondary' ?> mt-1" style="font-size: 0.65rem;">
                                                                <?= $dev->is_active ? 'Active' : 'Inactive' ?>
                                                            </span>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="text-center"><span class="text-muted small">No Device</span></div>
                                                    <?php endif; ?>
                                                </td>
>>>>>>> origin/main
                                                <td>
                                                    <div class="d-flex gap-2 justify-content-center">
                                                        <button type="button" class="action-btn action-btn-edit"
                                                            data-bs-toggle="offcanvas" data-bs-target="#form-offcanvas"
                                                            title="Edit"
                                                            data-edit="<?= $provider->id; ?>" data-module="service_providers"
                                                            data-url="<?= base_url() ?>getUser" data-id="<?= $provider->id; ?>">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </button>
                                                        <a href="<?= base_url() ?>deleteUser"
                                                            id="delete-employees-<?= $provider->id; ?>"
                                                            class="action-btn action-btn-delete" data-type="ajax"
                                                            data-method="DELETE" data-token="<?= csrf_hash(); ?>"
                                                            data-id="<?= $provider->id; ?>"
                                                            data-confirm="Are you certain you want to delete it?"
                                                            title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>

<<<<<<< HEAD
                    <!-- Pagination Links -->
                    <?php if (isset($pager_links) && !empty($pager_links)): ?>
                        <div class="px-4 py-3 border-top bg-light d-flex justify-content-center align-items-center">
                            <div class="premium-pagination">
                                <?= $pager_links ?>
                            </div>
=======
                    <!-- Pagination & Info -->
                    <?php if (isset($total_items) && $total_items > 0): ?>
                        <?php
                        $start = ($total_items > 0) ? ($current_page - 1) * $per_page + 1 : 0;
                        $end = min($current_page * $per_page, $total_items);
                        ?>
                        <div class="px-4 py-3 border-top bg-light d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <span class="text-muted small">
                                    Showing <strong class="text-dark"><?= $start ?></strong> to <strong class="text-dark"><?= $end ?></strong> of <strong class="text-dark"><?= $total_items ?></strong> entries
                                </span>
                                <div class="d-flex align-items-center gap-2">
                                    <label for="perPageSelect" class="text-muted small mb-0 text-nowrap">Show:</label>
                                    <select id="perPageSelect" class="form-select form-select-sm border bg-white" style="width: auto; padding: 0.25rem 1.5rem 0.25rem 0.5rem;" onchange="changeLimit(this.value)">
                                        <option value="10" <?= $per_page == 10 ? 'selected' : '' ?>>10</option>
                                        <option value="25" <?= $per_page == 25 ? 'selected' : '' ?>>25</option>
                                        <option value="50" <?= $per_page == 50 ? 'selected' : '' ?>>50</option>
                                        <option value="100" <?= $per_page == 100 ? 'selected' : '' ?>>100</option>
                                        <option value="250" <?= $per_page == 250 ? 'selected' : '' ?>>250</option>
                                        <option value="500" <?= $per_page == 500 ? 'selected' : '' ?>>500</option>
                                        <option value="10000" <?= $per_page == 10000 ? 'selected' : '' ?>>All</option>
                                    </select>
                                    <span class="text-muted small text-nowrap">rows</span>
                                </div>
                            </div>
                            <?php if (isset($pager_links) && !empty($pager_links)): ?>
                                <div class="premium-pagination mb-0">
                                    <?= $pager_links ?>
                                </div>
                            <?php endif; ?>
>>>>>>> origin/main
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <!-- All Modals and Offcanvas -->
    <!-- Send Push Notification Offcanvas -->
    <form action="<?= base_url('sendNotification') ?>" method="POST" id="sendNotification1">
        <input type="hidden" id="selectedProviderIds" name="selectedProviderIds">
        <input type="hidden" name="module" value="service_providers">
        <div class="offcanvas offcanvas-end" id="send_push_notification" tabindex="-1"
            aria-labelledby="form-offcanvasLabel">
<<<<<<< HEAD
            <div class="offcanvas-header bg-gradient-primary text-white border-0">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div>
                        <h5 class="offcanvas-title text-white fw-bold" id="form-offcanvasLabel">
                            <i class="fa-regular fa-paper-plane me-2"></i>Send Push Notification
                        </h5>
                        <p class="mb-0 small opacity-75">Send notification to selected users</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
=======
            <div class="offcanvas-header text-white border-0" style="background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 25%, #3730a3 60%, #4f46e5 100%) !important; padding: 1.25rem 1.5rem;">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div>
                        <h5 class="offcanvas-title text-white fw-bold mb-1" id="form-offcanvasLabel">
                            <i class="fa-regular fa-paper-plane me-2 text-warning"></i>Send Push Notification
                        </h5>
                        <p class="mb-0 small text-white-50">Send push notification to providers</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
>>>>>>> origin/main
                </div>
            </div>
            <div class="offcanvas-body p-4">
                <div class="row">
                    <div class="col-12">
<<<<<<< HEAD
                        <div class="card border-0 mb-4">
                            <div class="card-body">
=======
                        <div id="notificationRecipientInfo" class="alert alert-primary d-flex align-items-center gap-2 mb-3 py-2 px-3 small border-0" style="background: rgba(99, 102, 241, 0.1); color: #4338ca; border-radius: 8px;">
                            <i class="fas fa-users"></i>
                            <span id="recipientCountText">Recipient: <strong>Selected Providers</strong></span>
                        </div>
                        <div class="card border-0 mb-4 shadow-none">
                            <div class="card-body p-0">
>>>>>>> origin/main
                                <h6 class="card-title text-primary mb-3 fw-bold">
                                    <i class="fas fa-bell me-2"></i>Notification Content
                                </h6>
                                <div class="mb-4">
                                    <label class="form-label fw-bold" for="Title">
                                        Notification Title
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-heading text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control border-start-0" id="Title" name="title"
                                            required placeholder="Enter notification title">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold" for="description">
                                        Message
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light align-items-start pt-2 border-end-0">
                                            <i class="fas fa-comment text-muted"></i>
                                        </span>
                                        <textarea class="form-control border-start-0" id="description" name="message"
                                            required placeholder="Enter your notification message" rows="5"></textarea>
                                    </div>
                                </div>
<<<<<<< HEAD
=======

                                <div class="mb-3 p-3 bg-light rounded border">
                                    <div class="form-check form-switch d-flex align-items-center gap-2 m-0">
                                        <input class="form-check-input" type="checkbox" id="sp_queue_only" name="queue_only" value="1" checked style="cursor: pointer; transform: scale(1.15);">
                                        <label class="form-check-label fw-bold text-dark mb-0 small" for="sp_queue_only" style="cursor: pointer;">
                                            <i class="bi bi-clock-history text-primary me-1"></i> Send notifications as Queue Only
                                        </label>
                                    </div>
                                    <div class="small text-muted mt-1 ps-4">Queues notifications in the background to dispatch reliably without UI delay.</div>
                                </div>
>>>>>>> origin/main
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="offcanvas-footer border-top p-4">
                <div class="d-flex gap-3">
                    <button class="btn btn-primary d-flex align-items-center gap-2 flex-grow-1 justify-content-center"
                        type="submit" id="sendNotificationBtn">
                        <i class="fa-regular fa-paper-plane"></i> Send Notification
                    </button>
                    <button class="btn btn-outline-secondary d-flex align-items-center gap-2" type="button"
                        data-bs-dismiss="offcanvas">
                        <i class="fas fa-times me-2"></i> Cancel
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Edit Provider Offcanvas -->
    <form action="<?= base_url() ?>update" id="updateUser">
        <div class="offcanvas offcanvas-end" tabindex="-1" id="form-offcanvas" aria-labelledby="form-offcanvasLabel"
            aria-modal="true" role="dialog">
<<<<<<< HEAD
            <div class="offcanvas-header border-bottom bg-primary text-white">
                <h5 class="offcanvas-title" id="form-offcanvasLabel">Edit Service Provider</h5>
=======
            <div class="offcanvas-header border-0 text-white" style="background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 25%, #3730a3 60%, #4f46e5 100%) !important; padding: 1.25rem 1.5rem;">
                <div>
                    <h5 class="offcanvas-title text-white fw-bold mb-1" id="form-offcanvasLabel">
                        <i class="fas fa-user-edit me-2 text-warning"></i>Edit Service Provider
                    </h5>
                    <p class="mb-0 small text-white-50">Update provider profile and settings</p>
                </div>
>>>>>>> origin/main
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <!-- Header with Avatar -->
                            <div class="text-center mb-4">
                                <div class="provider-avatar-large mx-auto mb-3">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <h6 class="text-muted">Edit Service Provider Information</h6>
                            </div>

                            <!-- Hidden Fields -->
                            <input type="hidden" name="id" value="" id="id">
                            <input type="hidden" name="module" value="service_providers" id="module">

                            <!-- Provider Information Form -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body">
                                    <h6 class="card-title text-primary mb-3 fw-bold">
                                        <i class="fas fa-user-circle me-2"></i>Personal Information
                                    </h6>

                                    <div class="row g-3">
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
                                                    id="name" placeholder="Enter provider name">
                                            </div>
                                        </div>

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

                                        <div class="col-md-12">
                                            <label class="form-label">
                                                <i class="fas fa-venus-mars me-1"></i>Gender
                                            </label>
                                            <div class="d-flex gap-4 flex-wrap">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="gender" id="male"
                                                        value="Male">
                                                    <label class="form-check-label d-flex align-items-center"
                                                        for="male">
                                                        <i class="fas fa-male me-2"></i> Male
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="gender"
                                                        id="female" value="Female">
                                                    <label class="form-check-label d-flex align-items-center"
                                                        for="female">
                                                        <i class="fas fa-female me-2"></i> Female
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="gender"
                                                        id="other" value="Other">
                                                    <label class="form-check-label d-flex align-items-center"
                                                        for="other">
                                                        <i class="fas fa-user me-2"></i> Other
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label for="dob" class="form-label">
                                                <i class="fas fa-birthday-cake me-1"></i>Date of Birth
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-calendar text-muted"></i>
                                                </span>
                                                <input type="date" class="form-control border-start-0" name="dob"
                                                    id="dob">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Card -->
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title text-primary mb-3 fw-bold">
                                        <i class="fas fa-toggle-on me-2"></i>Provider Status
                                    </h6>

                                    <div class="row align-items-center">
                                        <div class="col-md-12">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <label for="category-status" class="form-label mb-0">
                                                        Active Status
                                                    </label>
                                                    <p class="text-muted small mb-0">
                                                        Toggle to activate or deactivate service provider account
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

    <!-- Rating Modal -->
    <div class="modal fade" id="ratingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg p-0 m-1" style="border-radius: 16px; overflow: hidden;">
<<<<<<< HEAD
                <div class="modal-header bg-gradient-primary text-white border-0">
                    <div class="d-flex align-items-center justify-content-between w-100">
                        <div>
                            <h5 class="modal-title text-white fw-bold">Update Rating</h5>
                            <p class="mb-0 small opacity-75">Rate and comment on provider performance</p>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
=======
                <div class="modal-header text-white border-0" style="background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 25%, #3730a3 60%, #4f46e5 100%) !important; padding: 1.25rem 1.5rem;">
                    <div class="d-flex align-items-center justify-content-between w-100">
                        <div>
                            <h5 class="modal-title text-white fw-bold mb-1">
                                <i class="fas fa-star me-2 text-warning"></i>Update Rating
                            </h5>
                            <p class="mb-0 small text-white-50">Rate and comment on provider performance</p>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
>>>>>>> origin/main
                    </div>
                </div>
                <form id="ratingForm" class="ratingForm" method="POST" action="<?= base_url('updateRating') ?>">
                    <div class="modal-body p-4">
                        <input type="hidden" name="provider_id" id="ratingProviderId">
                        <input type="hidden" name="_token" value="<?= csrf_hash() ?>">

                        <div class="mb-4">
                            <label class="form-label fw-bold mb-3">Rating</label>
                            <div class="rating-stars d-flex justify-content-center gap-2 mb-3">
                                <?php for ($i = 1; $i <= 5; $i++) : ?>
                                    <div class="star-rating" data-rating="<?= $i ?>">
                                        <i class="far fa-star fa-2x text-muted"
                                            style="cursor: pointer; transition: all 0.2s ease;"></i>
                                    </div>
                                <?php endfor; ?>
                            </div>
                            <select name="rating" id="ratingValue" class="form-control d-none">
                                <?php for ($i = 1; $i <= 5; $i++) : ?>
                                    <option value="<?= $i ?>"><?= str_repeat('⭐', $i) ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Comment</label>
                            <textarea name="comment" id="ratingComment" class="form-control" rows="4"
                                placeholder="Add your comments about this provider..."
                                style="border-radius: 8px; border: 2px solid #e9ecef;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top p-4">
                        <div class="d-flex gap-3 w-100">
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary flex-grow-1">Save Rating</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Document Modal -->
    <div class="modal fade overflow-hidden" id="documentModal" tabindex="-1" aria-labelledby="documentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg p-0 m-0"
                style="border-radius: 16px; overflow: hidden; height: 80vh;">
<<<<<<< HEAD
                <div class="modal-header bg-gradient-primary text-white border-0">
                    <div class="d-flex align-items-center justify-content-between w-100">
                        <div>
                            <h5 class="modal-title text-white fw-bold" id="documentModalLabel">Verification Documents
                            </h5>
                            <p class="mb-0 small opacity-75">Review and verify provider documents</p>
=======
                <div class="modal-header text-white border-0" style="background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 25%, #3730a3 60%, #4f46e5 100%) !important; padding: 1.25rem 1.5rem;">
                    <div class="d-flex align-items-center justify-content-between w-100">
                        <div>
                            <h5 class="modal-title text-white fw-bold mb-1" id="documentModalLabel">
                                <i class="fas fa-id-card me-2 text-warning"></i>Verification Documents
                            </h5>
                            <p class="mb-0 small text-white-50">Review and verify provider documents</p>
>>>>>>> origin/main
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                </div>

                <div class="modal-body p-0">
                    <div class="table-responsive">
<<<<<<< HEAD
                        <table class="table table-hover align-middle" id="verificationDetails">
                            <thead style="background: #c6d2ff !important;">
                                <tr>
                                    <th class="fw-bold text-primary fs-6"
                                        style="min-width: 150px; background: #c6d2ff !important;">Document Type</th>
                                    <th class="fw-bold text-primary fs-6"
                                        style="min-width: 150px; background: #c6d2ff !important;">Image Preview</th>
                                    <th class="fw-bold text-primary fs-6"
                                        style="min-width: 120px; background: #c6d2ff !important;">Status</th>
=======
                        <table class="table table-hover align-middle mb-0" id="verificationDetails">
                            <thead>
                                <tr>
                                    <th style="min-width: 150px;">Document Type</th>
                                    <th style="min-width: 150px;">Image Preview</th>
                                    <th style="min-width: 120px;">Status</th>
>>>>>>> origin/main
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
<<<<<<< HEAD
=======
                            <tbody></tbody>
                        </table>
                    </div>
>>>>>>> origin/main

                    <div class="alert alert-info mt-4 d-flex align-items-center gap-3">
                        <i class="fas fa-info-circle fa-2x"></i>
                        <div>
                            <h6 class="alert-heading mb-1">Document Verification Guidelines</h6>
                            <p class="mb-0 small">Click on images to view in full size. Use Approve/Reject buttons to
                                update document status.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
<<<<<<< HEAD
        .bg-primary-soft { background-color: rgba(13, 110, 253, 0.1) !important; }
        .bg-success-soft { background-color: rgba(25, 135, 84, 0.1) !important; }
        .bg-info-soft { background-color: rgba(13, 202, 240, 0.1) !important; }
        .bg-danger-soft { background-color: rgba(220, 53, 69, 0.1) !important; }
        .bg-warning-soft { background-color: rgba(255, 193, 7, 0.1) !important; }
        .text-primary { color: #0d6efd !important; }
        
=======
        .bg-primary-soft {
            background-color: rgba(13, 110, 253, 0.1) !important;
        }

        .bg-success-soft {
            background-color: rgba(25, 135, 84, 0.1) !important;
        }

        .bg-info-soft {
            background-color: rgba(13, 202, 240, 0.1) !important;
        }

        .bg-danger-soft {
            background-color: rgba(220, 53, 69, 0.1) !important;
        }

        .bg-warning-soft {
            background-color: rgba(255, 193, 7, 0.1) !important;
        }

        .text-primary {
            color: #0d6efd !important;
        }

>>>>>>> origin/main
        .category-icon {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 0.85rem;
        }
<<<<<<< HEAD
        
        .custom-accordion .accordion-item {
            border: 1px solid rgba(0,0,0,0.05) !important;
            transition: all 0.3s ease;
        }
        
=======

        .custom-accordion .accordion-item {
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            transition: all 0.3s ease;
        }

>>>>>>> origin/main
        .custom-accordion .accordion-button:not(.collapsed) {
            background-color: #fff !important;
            color: #0d6efd !important;
            box-shadow: none !important;
        }
<<<<<<< HEAD
        
        .custom-accordion .accordion-button:focus {
            box-shadow: none !important;
        }
        
        .sticky-top { z-index: 1021; }
        .sticky-bottom { z-index: 1021; }
        
        .glass-footer {
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(0,0,0,0.05) !important;
        }
        
        .shadow-premium {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        }
        
=======

        .custom-accordion .accordion-button:focus {
            box-shadow: none !important;
        }

        .sticky-top {
            z-index: 1021;
        }

        .sticky-bottom {
            z-index: 1021;
        }

        .glass-footer {
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(0, 0, 0, 0.05) !important;
        }

        .shadow-premium {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        }

>>>>>>> origin/main
        .status-pill {
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
<<<<<<< HEAD
        
        .status-approved { background: #e6fcf5; color: #087f5b; }
        .status-pending { background: #fff9db; color: #f08c00; }
        .status-rejected { background: #fff5f5; color: #c92a2a; }
        .status-call { background: #e7f5ff; color: #1971c2; }
        
=======

        .status-approved {
            background: #e6fcf5;
            color: #087f5b;
        }

        .status-pending {
            background: #fff9db;
            color: #f08c00;
        }

        .status-rejected {
            background: #fff5f5;
            color: #c92a2a;
        }

        .status-call {
            background: #e7f5ff;
            color: #1971c2;
        }

>>>>>>> origin/main
        #sv_rating_stars_display i {
            margin-right: 2px;
            transition: transform 0.2s ease;
        }
<<<<<<< HEAD
        
=======

>>>>>>> origin/main
        .btn-verify-chip {
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }
<<<<<<< HEAD
        
=======

>>>>>>> origin/main
        .btn-verify-chip:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
            background-color: #0d6efd !important;
            color: white !important;
        }

        .category-summary-item {
<<<<<<< HEAD
            border-right: 1px solid rgba(0,0,0,0.05);
            padding: 0 15px;
        }
        .category-summary-item:last-child { border-right: none; }
        .extra-small { font-size: 0.7rem !important; }
        
=======
            border-right: 1px solid rgba(0, 0, 0, 0.05);
            padding: 0 15px;
        }

        .category-summary-item:last-child {
            border-right: none;
        }

        .extra-small {
            font-size: 0.7rem !important;
        }

>>>>>>> origin/main
        .set-satisfactory {
            font-size: 0.7rem;
            color: #adb5bd;
            transition: all 0.2s;
        }
<<<<<<< HEAD
=======

>>>>>>> origin/main
        .set-satisfactory:hover {
            color: #0d6efd !important;
        }
    </style>


    <!-- Details Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="detailsModal" aria-labelledby="detailsModalLabel"
        style="width: 750px;">
<<<<<<< HEAD
        <div class="offcanvas-header bg-primary text-white border-0">
            <div class="d-flex align-items-center justify-content-between w-100">
                <h5 class="offcanvas-title text-white fw-bold" id="detailsModalLabel">Provider Details</h5>
=======
        <div class="offcanvas-header text-white border-0" style="background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 25%, #3730a3 60%, #4f46e5 100%) !important; padding: 1.25rem 1.5rem;">
            <div class="d-flex align-items-center justify-content-between w-100">
                <div>
                    <h5 class="offcanvas-title text-white fw-bold mb-1" id="detailsModalLabel">
                        <i class="fas fa-id-badge me-2 text-warning"></i>Provider Details
                    </h5>
                    <p class="mb-0 small text-white-50">Complete provider profile and verification status</p>
                </div>
>>>>>>> origin/main
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
        </div>
        <div class="offcanvas-body p-0" style="scrollbar-width:none">
            <div class="h-100" id="modalDetailsContent"></div>
        </div>
    </div>

    <?= $this->include('admin/footer') ?>
</main>

<!-- Add SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- JavaScript for All Functionality -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const csrfToken = '<?= csrf_hash() ?>';
        const baseUrl = '<?= base_url() ?>';

<<<<<<< HEAD
        // Restore or manage active tab state for pending_registration
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('status') === 'pending_registration') {
            const savedTabId = sessionStorage.getItem("activePendingRegistrationTab");
            if (savedTabId) {
                const tabElement = document.getElementById(savedTabId);
                if (tabElement) {
                    const tab = new bootstrap.Tab(tabElement);
                    tab.show();
                }
            }

            const pendingTabs = document.getElementById("pendingRegistrationTabs");
            if (pendingTabs) {
                pendingTabs.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
                    tab.addEventListener('shown.bs.tab', function(e) {
                        sessionStorage.setItem("activePendingRegistrationTab", e.target.id);
                    });
                });
            }
        } else {
            sessionStorage.removeItem("activePendingRegistrationTab");
        }

        // ============================
        // 1. BULK NOTIFICATION FUNCTIONALITY
        // ============================
        let sendBulkNotificationBtn = document.getElementById("send-bulk-notification");
        let selectedProviderIds = document.getElementById("selectedProviderIds");

        function getActiveTableCheckboxes() {
            const activePane = document.querySelector('.tab-pane.active');
            if (activePane) {
                return activePane.querySelectorAll('.select-table-row');
            }
            return document.querySelectorAll('.select-table-row');
        }

        function updateBulkNotificationButtonState() {
            const checkboxes = getActiveTableCheckboxes();
            const selectedIds = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            sendBulkNotificationBtn.disabled = selectedIds.length === 0;
            if (selectedProviderIds) {
                selectedProviderIds.value = JSON.stringify(selectedIds);
            }
        }

        function initializeCheckboxes() {
            document.querySelectorAll('.select-table-row').forEach(checkbox => {
                checkbox.addEventListener('change', updateBulkNotificationButtonState);
            });

            document.querySelectorAll('[id^="select-all-"]').forEach(selectAll => {
                selectAll.addEventListener('change', function() {
                    const tableId = this.id.replace('select-all-', '') + 'ProvidersTable';
                    const table = document.getElementById(tableId);
                    if (table) {
                        const checkboxes = table.querySelectorAll('.select-table-row');
                        checkboxes.forEach(cb => {
                            cb.checked = this.checked;
                        });
                        updateBulkNotificationButtonState();
                    }
                });
            });
        }

        // Tab change handler
        document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function() {
                setTimeout(updateBulkNotificationButtonState, 100);
            });
        });

=======
        // Tab management is handled server-side via URL parameters

        // ============================
        // 1. BULK NOTIFICATION & CHECKBOXES
        // ============================
        const sendBulkNotificationBtn = document.getElementById("send-bulk-notification");
        const selectedProviderIds = document.getElementById("selectedProviderIds");

        function getVisibleRowCheckboxes() {
            const activePane = document.querySelector('.tab-pane.active');
            if (activePane) {
                return Array.from(activePane.querySelectorAll('.select-table-row'));
            }
            return Array.from(document.querySelectorAll('.select-table-row'));
        }

        window.updateBulkNotificationButtonState = function() {
            const checkboxes = getVisibleRowCheckboxes();
            const checkedBoxes = checkboxes.filter(cb => cb.checked);
            const selectedIds = checkedBoxes.map(cb => cb.value).filter(val => val !== "" && val !== undefined);

            if (sendBulkNotificationBtn) {
                const count = selectedIds.length;
                sendBulkNotificationBtn.disabled = count === 0;
                if (count > 0) {
                    sendBulkNotificationBtn.classList.remove('opacity-50');
                    sendBulkNotificationBtn.innerHTML = `<i class="fa-regular fa-paper-plane me-1"></i> Bulk Notification <span class="badge bg-dark ms-1" style="font-size: 0.72rem; padding: 0.2rem 0.45rem;">${count}</span>`;
                } else {
                    sendBulkNotificationBtn.classList.add('opacity-50');
                    sendBulkNotificationBtn.innerHTML = `<i class="fa-regular fa-paper-plane me-1"></i> Bulk Notification`;
                }
            }

            if (selectedProviderIds) {
                selectedProviderIds.value = JSON.stringify(selectedIds);
            }

            // Sync master checkbox state for each table on the page
            document.querySelectorAll('table.nl-table').forEach(table => {
                const master = table.querySelector('input[name="select_all_table"], [id^="select-all-"]');
                if (master) {
                    const tableRows = Array.from(table.querySelectorAll('.select-table-row'));
                    const tableChecked = tableRows.filter(cb => cb.checked);
                    if (tableRows.length > 0 && tableChecked.length === tableRows.length) {
                        master.checked = true;
                        master.indeterminate = false;
                    } else if (tableChecked.length > 0) {
                        master.checked = false;
                        master.indeterminate = true;
                    } else {
                        master.checked = false;
                        master.indeterminate = false;
                    }
                }
            });
        };

        window.selectAllTable = function(checkbox) {
            const table = checkbox.closest('table');
            if (table) {
                const checkboxes = table.querySelectorAll('.select-table-row');
                checkboxes.forEach(cb => {
                    cb.checked = checkbox.checked;
                });
                updateBulkNotificationButtonState();
                if (typeof updateApplyButtonState === 'function') {
                    updateApplyButtonState();
                }
            }
        };

        // Delegated checkbox handlers with stopPropagation to prevent row clicks
        $(document).on('click', '.select-table-row', function(e) {
            e.stopPropagation();
        });

        $(document).on('change', '.select-table-row', function(e) {
            e.stopPropagation();
            updateBulkNotificationButtonState();
            if (typeof updateApplyButtonState === 'function') {
                updateApplyButtonState();
            }
        });

        $(document).on('click', 'input[name="select_all_table"], [id^="select-all-"]', function(e) {
            e.stopPropagation();
            selectAllTable(this);
        });

        // Tab change listener to re-evaluate checkbox and bulk button state
        document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function() {
                setTimeout(() => {
                    updateBulkNotificationButtonState();
                    if (typeof updateApplyButtonState === 'function') {
                        updateApplyButtonState();
                    }
                }, 100);
            });
        });

        // Safe Row Click: click row to view details without triggering when clicking buttons/inputs/badges
        $(document).on('click', '.nl-table tbody tr', function(e) {
            if ($(e.target).closest('input, button, a, select, textarea, label, .action-btn, .form-check, .btn-verify-chip, .open-rating-modal, .viewDocumentsLink, .verifyBtn, .move-back-btn, .badge').length > 0) {
                return;
            }
            const viewDetailsBtn = $(this).find('.view-details');
            if (viewDetailsBtn.length > 0) {
                viewDetailsBtn.trigger('click');
            }
        });

        // Prevent button actions in tables from triggering row click
        $(document).on('click', '.action-btn, .btn-verify-chip, .open-rating-modal, .viewDocumentsLink, .verifyBtn, .move-back-btn', function(e) {
            e.stopPropagation();
        });

        // Bulk Notification trigger button click
        $(document).on('click', '#send-bulk-notification', function(e) {
            const checkboxes = getVisibleRowCheckboxes();
            const selectedIds = checkboxes.filter(cb => cb.checked).map(cb => cb.value).filter(val => val !== "");

            if (selectedIds.length === 0) {
                e.preventDefault();
                e.stopPropagation();
                Swal.fire({
                    icon: 'info',
                    title: 'No Providers Selected',
                    text: 'Please select one or more providers from the table using checkboxes to send a bulk notification.'
                });
                return false;
            }

            // Clear any single provider ID data
            const targetForm = $('#sendNotification1');
            targetForm.removeData('id');
            targetForm.removeData('module');
            $('#selectedProviderIds').val(JSON.stringify(selectedIds));

            $('#recipientCountText').html(`Target: <strong>${selectedIds.length} Selected Provider(s)</strong>`);
            $('#Title').val('');
            $('#description').val('');

            const offcanvasEl = document.getElementById('send_push_notification');
            if (offcanvasEl) {
                bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl).show();
            }
        });

>>>>>>> origin/main
        // ============================
        // 2. FOLLOW-UP FUNCTIONALITY
        // ============================
        const quickActionType = document.getElementById('quick-action-type');
        const followupCommentAction = document.getElementById('followup-comment-action');
        const followupNotesAction = document.getElementById('followup-notes-action');
        const followupComment = document.getElementById('followup-comment');
        const followupNotes = document.getElementById('followup-notes');
        const quickActionApply = document.getElementById('quick-action-apply');

        function updateApplyButtonState() {
            const checkboxes = document.querySelectorAll('.select-table-row:checked');
            const hasSelection = checkboxes.length > 0;
            const actionType = quickActionType ? quickActionType.value : '';

            if (!hasSelection) {
                if (quickActionApply) quickActionApply.disabled = true;
                return;
            }

            if (actionType === 'add-followup') {
                const commentSelected = followupComment ? followupComment.value !== '' : false;
                if (quickActionApply) quickActionApply.disabled = !commentSelected;
            } else {
                if (quickActionApply) quickActionApply.disabled = true;
            }
        }

        if (quickActionType) {
            quickActionType.addEventListener('change', function() {
                const actionType = this.value;

                if (followupCommentAction) followupCommentAction.classList.add('d-none');
                if (followupNotesAction) followupNotesAction.classList.add('d-none');

                if (followupComment) followupComment.value = '';
                if (followupNotes) followupNotes.value = '';

                if (actionType === 'add-followup') {
                    if (followupCommentAction) followupCommentAction.classList.remove('d-none');
                }

                updateApplyButtonState();
            });
        }

        if (followupComment) {
            followupComment.addEventListener('change', function() {
                const commentValue = this.value;

                if (commentValue === 'other') {
                    if (followupNotesAction) followupNotesAction.classList.remove('d-none');
                } else {
                    if (followupNotesAction) followupNotesAction.classList.add('d-none');
                    if (followupNotes) followupNotes.value = '';
                }

                updateApplyButtonState();
            });
        }

        if (quickActionApply) {
            quickActionApply.addEventListener('click', function(e) {
                e.preventDefault();

                const selectedCheckboxes = document.querySelectorAll('.select-table-row:checked');
                const actionType = quickActionType ? quickActionType.value : '';

                if (selectedCheckboxes.length === 0) {
                    showAlert('error', 'Please select at least one provider.');
                    return;
                }

                if (actionType === 'add-followup') {
                    const providerIds = Array.from(selectedCheckboxes).map(cb => cb.value);
                    const comment = followupComment ? followupComment.value : '';
                    const notes = followupNotes ? followupNotes.value : '';

                    if (!comment) {
                        showAlert('error', 'Please select a follow-up comment.');
                        return;
                    }

                    addFollowupComments(providerIds, comment, notes);
                }
            });
        }

        function addFollowupComments(providerIds, comment, notes) {
            const applyBtn = quickActionApply;
            const originalText = applyBtn ? applyBtn.innerHTML : 'Apply';
            if (applyBtn) {
                applyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                applyBtn.disabled = true;
            }
            console.log("providerIds", providerIds)

            // Make sure this URL matches your actual route
            fetch(baseUrl + 'add-followup-comment', { // Change this to your correct endpoint
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        ids: providerIds,
                        comment: comment,
                        notes: notes,
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log("data", data)
                    if (data.success || data.status === true) { // Handle both response formats
                        showAlert('success', data.message || 'Follow-up comments added successfully!');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showAlert('error', data.message || 'Failed to add follow-up comments.');
                        if (applyBtn) applyBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'An error occurred while adding follow-up comments.');
                    if (applyBtn) {
                        applyBtn.innerHTML = originalText;
                        applyBtn.disabled = false;
                    }
                })
                .finally(() => {
                    if (applyBtn) {
                        applyBtn.innerHTML = originalText;
                        updateApplyButtonState();
                    }
                });
        }

        // ============================
        // 3. STATUS TOGGLE FUNCTIONALITY (Fixed to prevent double calls)
        // ============================
        $(document).off('change', '.status').on('change', '.status', function(e) {
            e.stopPropagation();

            const $checkbox = $(this);
            const module = $checkbox.data('module');
            const id = $checkbox.data('id');
            const url = $checkbox.data('url');
            const status = $checkbox.is(':checked') ? 'active' : 'inactive';

            // Prevent double submission
            if ($checkbox.data('processing')) {
                return false;
            }

            $checkbox.data('processing', true);
            $checkbox.prop('disabled', true);

            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    module: module,
                    id: id,
                    status: status
                },
                success: function(response) {
                    if (response.status === true) {
                        // showAlert('success', 'Status updated successfully!');
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        $checkbox.prop('checked', !$checkbox.is(':checked'));
                        // showAlert('error', response.message || 'Failed to update status.');
                    }
                },
                error: function(xhr, status, error) {
                    $checkbox.prop('checked', !$checkbox.is(':checked'));
                    // showAlert('error', 'An error occurred while updating status.');
                },
                complete: function() {
                    $checkbox.data('processing', false);
                    $checkbox.prop('disabled', false);
                }
            });

            return false;
        });

        // Dropdown status update
        $(document).on('change', '.status-select', function() {
            const $select = $(this);
            const module = $select.data('module');
            const id = $select.data('id');
            const url = $select.data('url');
            const status = $select.val();

            $select.prop('disabled', true);

            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    module: module,
                    id: id,
                    status: status
                },
                success: function(response) {
                    if (response.status === true) {
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        showAlert('error', response.message || 'Failed to update status.');
                        $select.prop('disabled', false);
                    }
                },
                error: function() {
                    showAlert('error', 'An error occurred while updating status.');
                    $select.prop('disabled', false);
                }
            });
        });

        // Update verification toggle
        $(document).on('change', '.updateVerification', function() {
            const $checkbox = $(this);
            const id = $checkbox.data('id');
            const url = $checkbox.data('url');
            const verified = $checkbox.is(':checked') ? 1 : 0;

            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    id: id,
                    is_verified: verified
                },
                success: function(data) {
                    Swal.fire({
                        icon: data.status ? 'success' : 'error',
                        text: data.message
                    });

                },
                error: function() {
                    Swal.fire('Error updating verification!');
                }
            });
        });

        // ============================
        // 4. RATING MODAL FUNCTIONALITY
        // ============================
        // Star rating interaction
        document.querySelectorAll('.rating-stars .star-rating').forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.getAttribute('data-rating'));
                const stars = this.parentElement.querySelectorAll('.star-rating');

                stars.forEach((s, index) => {
                    const icon = s.querySelector('i');
                    if (index < rating) {
                        icon.classList.remove('far', 'text-muted');
                        icon.classList.add('fas', 'text-warning', 'active');
                    } else {
                        icon.classList.remove('fas', 'text-warning', 'active');
                        icon.classList.add('far', 'text-muted');
                    }
                });

                document.getElementById('ratingValue').value = rating;
            });
        });

        // Open rating modal
        $(document).on('click', '.open-rating-modal', function() {
            let providerId = $(this).data('id');
            $('#ratingProviderId').val(providerId);
            const rating = $(this).data("rating") || "";
            const comment = $(this).data("comment") || "";

            $("#ratingProviderId").val(providerId);
            $("#ratingValue").val(rating);
            $("#ratingComment").val(comment);

            // Update star display
            const stars = document.querySelectorAll('.rating-stars .star-rating');
            stars.forEach((star, index) => {
                const icon = star.querySelector('i');
                if (index < rating) {
                    icon.classList.remove('far', 'text-muted');
                    icon.classList.add('fas', 'text-warning', 'active');
                } else {
                    icon.classList.remove('fas', 'text-warning', 'active');
                    icon.classList.add('far', 'text-muted');
                }
            });
        });

        // Rating form submission
        $(".ratingForm").on("submit", function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "<?= base_url('updateProviderFeedback') ?>",
                data: $(this).serialize(),
                success: function(data) {
                    if (data.status) {
                        Swal.fire({
                            icon: 'success',
                            text: data.message
                        }).then(() => {
                            $("#ratingModal").modal("hide");
                            $("#ratingForm")[0].reset();
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            text: data.message
                        });
                    }
                },
                error: function() {
                    Swal.fire('Something went wrong while saving rating!');
                }
            });
        });

        // ============================
        // 5. DOCUMENT MODAL FUNCTIONALITY
        // ============================
        $(document).on('click', '.viewDocumentsLink', function(e) {
            e.preventDefault();

            const verifications = JSON.parse($(this).attr('data-verifications') || '{}');
            const providerId = $(this).data('id');
            const $tableBody = $('#verificationDetails tbody');
            $tableBody.empty();

            const verificationFields = [{
                    label: 'Identity Proof',
                    field: 'identity_proof_verified',
<<<<<<< HEAD
                    imageUrl: `https://petsfolio.in/public/uploads/aadhaar_photos/`,
=======
                    imageUrl: `https://api.petsfolio.in/public/uploads/aadhaar_photos/`,
>>>>>>> origin/main
                    type: 'identity_proof'
                },
                {
                    label: 'Police Verification',
                    field: 'police_verification_verified',
<<<<<<< HEAD
                    imageUrl: `https://petsfolio.in/public/uploads/providers/verifications/`,
=======
                    imageUrl: `https://api.petsfolio.in/public/uploads/providers/verifications/`,
>>>>>>> origin/main
                    type: 'police_verification'
                },
                {
                    label: 'Walking Certificate',
                    field: 'walking_verified',
<<<<<<< HEAD
                    imageUrl: `https://petsfolio.in/public/uploads/providers/verifications/`,
=======
                    imageUrl: `https://api.petsfolio.in/public/uploads/providers/verifications/`,
>>>>>>> origin/main
                    type: 'walking'
                },
                {
                    label: 'Grooming Certificate',
                    field: 'grooming_verified',
<<<<<<< HEAD
                    imageUrl: `https://petsfolio.in/public/uploads/providers/verifications/`,
=======
                    imageUrl: `https://api.petsfolio.in/public/uploads/providers/verifications/`,
>>>>>>> origin/main
                    type: 'grooming'
                },
                {
                    label: 'Training Certificate',
                    field: 'training_verified',
<<<<<<< HEAD
                    imageUrl: `https://petsfolio.in/public/uploads/providers/verifications/`,
=======
                    imageUrl: `https://api.petsfolio.in/public/uploads/providers/verifications/`,
>>>>>>> origin/main
                    type: 'training'
                }
            ];

            verificationFields.forEach((verification) => {
                const fileName = verifications[verification.type];
                const isUploaded = !!fileName;
                const imgHtml = isUploaded ?
                    `<a href="${verification.imageUrl + fileName}" target="_blank">
                        <img src="${verification.imageUrl + fileName}" alt="${verification.label}" width="50">
                    </a>` :
                    'No document uploaded';

                const statusHtml = verifications[verification.field] === '1' ?
                    `<span class="text-success fw-bold">Verified</span>` :
                    `<button class="btn btn-sm btn-success verifyBtn"
                        ${!isUploaded ? 'disabled' : ''}
                        data-id="${providerId}"
                        data-type="${verification.type}"
                        data-field="${verification.field}">
                        Verify
                    </button>`;

                $tableBody.append(`
                    <tr>
                        <td><strong>${verification.label}</strong></td>
                        <td>${imgHtml}</td>
                        <td>${statusHtml}</td>
                    </tr>
                `);
            });

            const documentModal = new bootstrap.Modal(document.getElementById('documentModal'));
            documentModal.show();
        });

        $(document).on('click', '.verifyBtn', function() {
            const button = $(this);
            const type = button.data('type');
            const field = button.data('field');
            const providerId = button.data('id');

            $.ajax({
                url: '<?= base_url() ?>update-provider-verifications',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    id: providerId,
                    type: type,
                    field: field
                },
                success: function(response) {
                    if (response.status === true) {
                        Swal.fire('Success!', 'Updated successfully.', 'success').then(() => {
                            const row = button.closest('tr');
                            button.replaceWith('<span class="verifiedStatus" style="display: inline-block; text-align: center;">Verified</span>');

                            row.find('.verifiedStatus').css({
                                'font-weight': 'bold',
                                'color': 'green',
                                'padding': '5px',
                                'text-align': 'center',
                                'display': 'inline-block'
                            });
                        });
                        location.reload();
                    } else {
                        Swal.fire('Error!', 'Failed to update.', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error!', 'An error occurred. Please try again later.', 'error');
                },
            });
        });

        // ============================
        // 6. MOVE BACK BUTTON FUNCTIONALITY
        // ============================
        $(document).on('click', '.move-back-btn', function() {
            const providerId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to move this provider back to Non-Verified?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes, move back!',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url() ?>remove-followup-status',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        data: {
                            id: providerId
                        },
                        success: function(response) {
                            if (response.status === true) {
                                Swal.fire('Success!', 'Provider moved back to Non-Verified.', 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error!', 'Failed to move provider.', 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error!', 'An error occurred. Please try again later.', 'error');
                        }
                    });
                }
            });
        });

        // ============================
        // 7. DETAILS OFF-CANVAS FUNCTIONALITY
        // ============================
        function formatForModal(details) {
            const data = typeof details === 'string' ? JSON.parse(details) : details;
            console.log(data.serviceDetails)

            function formatDate(dateString) {
                if (!dateString) return "--";
                const options = {
                    year: "numeric",
                    month: "short",
                    day: "2-digit"
                };
                return new Date(dateString).toLocaleString("en-GB", options);
            }

<<<<<<< HEAD
            let providerDeviceDetailsHtml = '';
            if (data.devices && data.devices.length > 0) {
                data.devices.forEach((dev) => {
                    providerDeviceDetailsHtml += `
                    <div class="detail-card mb-2 p-2 border rounded">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge ${dev.is_active == 1 ? 'bg-success' : 'bg-secondary'}">${dev.is_active == 1 ? 'Active' : 'Inactive'}</span>
                            <span class="badge bg-primary">App v${dev.app_version || 'N/A'} ${dev.build_number ? '('+dev.build_number+')' : ''}</span>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <div class="text-black small mb-1 fw-bold">OS / Version</div>
                                <div class="fw-medium text-dark">${(dev.os || '').toUpperCase()} ${dev.os_version || ''}</div>
                            </div>
                            <div class="col-md-6 mb-1">
                                <div class="text-black small mb-1 fw-bold">Device Model</div>
                                <div class="fw-medium text-dark">${dev.manufacturer || ''} ${dev.model || dev.device_name || '--'}</div>
                            </div>
                            <div class="col-md-6 mb-1">
                                <div class="text-black small mb-1 fw-bold">Last Login</div>
                                <div class="fw-medium text-dark small">${dev.last_login_at ? formatDate(dev.last_login_at) : '--'}</div>
                            </div>
                            <div class="col-md-6 mb-1">
                                <div class="text-black small mb-1 fw-bold">Last Active</div>
                                <div class="fw-medium text-dark small">${dev.last_active_at ? formatDate(dev.last_active_at) : '--'}</div>
                            </div>
                        </div>
                    </div>
                `;
                });
            } else {
                providerDeviceDetailsHtml = '<div class="text-center text-muted py-2">No registered devices</div>';
            }

=======
>>>>>>> origin/main
            let serviceDetailsHtml = '';
            if (data.serviceDetails && Object.keys(data.serviceDetails).length > 0) {
                Object.entries(data.serviceDetails).forEach(([serviceName, details]) => {
                    const sRatingValue = details.rating || 0;
                    const sRatingStars = '★'.repeat(Math.round(sRatingValue)) + '☆'.repeat(5 - Math.round(sRatingValue));

                    let interviewAnswersHtml = '';
                    if (details.interview_answers) {
                        interviewAnswersHtml = `
                            <div class="mt-3 pt-3 border-top">
                                <div class="text-black small mb-2 fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Interview Performance</div>
                                <div class="row g-2">
                                    ${Object.entries(details.interview_answers).map(([q, val]) => {
                                        let isNewFormat = typeof val === 'object' && val !== null && 'question' in val;
                                        let qLabel = isNewFormat ? val.question : q.replace('q_', '').replace(/_/g, ' ').replace(/^\w/, c => c.toUpperCase());
                                        let answerText = isNewFormat ? val.answer : (parseFloat(val) > 0 ? 'Pass' : 'Fail');
                                        let scoreVal = isNewFormat ? val.score : val;
                                        let isPass = isNewFormat ? (val.score > 0) : (parseFloat(val) > 0);
                                        let colClass = isNewFormat ? 'col-12' : 'col-md-6 col-lg-4';
                                        
                                        return `
                                            <div class="${colClass}">
                                                <div class="p-2 bg-light rounded-3 small h-100 border-start border-3 ${isPass ? 'border-success' : 'border-danger'}">
                                                    <div class="text-muted fw-bold mb-1" style="font-size: 0.7rem;">${qLabel}</div>
                                                    <div class="text-dark" style="font-size: 0.75rem;">
                                                        ${isNewFormat ? `<strong>Selected:</strong> ${answerText}` : `<span class="fw-bold ${isPass ? 'text-success' : 'text-danger'}">${answerText}</span> (${scoreVal}%)`}
                                                    </div>
                                                    ${isNewFormat ? `<div class="text-muted mt-1" style="font-size: 0.65rem;">Score: ${scoreVal}</div>` : ''}
                                                </div>
                                            </div>`;
                                    }).join('')}
                                </div>
                            </div>`;
                    }

                    serviceDetailsHtml += `
                        <div class="detail-section mb-4 border-bottom pb-3">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-concierge-bell me-2"></i>
                                ${serviceName.charAt(0).toUpperCase() + serviceName.slice(1)} Service
                            </h6>
                            <div class="row align-items-center">
                                <div class="col-md-4 mb-3 mb-md-0 text-center border-end">
                                    <div class="text-black small mb-2 fw-bold">Current Rating</div>
                                    <div class="h4 text-warning mb-1">${sRatingStars}</div>
                                    <div class="text-muted small">(${sRatingValue}/5)</div>
                                </div>
                                <div class="col-md-8 ps-md-4">
                                    <div class="mb-3">
                                        <div class="text-black small mb-1 fw-bold">Admin Review</div>
                                        <div class="fw-medium text-dark">${details.review || 'No review recorded'}</div>
                                    </div>
                                    <div class="row mb-0">
                                        <div class="col-md-6">
                                            <div class="text-black small mb-1 fw-bold">Experience</div>
                                            <div class="fw-medium text-dark">${details.experience || '--'}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-black small mb-1 fw-bold">Availability</div>
                                            <div class="fw-medium text-dark">${details.available_time || '--'}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            ${interviewAnswersHtml}
                        </div>
                    `;
                });
            } else {
                serviceDetailsHtml = '';
            }

            const profileImageHtml = data.profile ?
                `<img src="${baseUrl}public/uploads/providers/${data.profile}" 
                    class="rounded-circle border border-4 border-white shadow-lg"
                    style="width:100px;height:100px;object-fit:cover"
                    onerror="this.onerror=null; this.src='${baseUrl}public/assets/images/default-avatar.png';">` :
                `<div class="rounded-circle bg-gradient-primary d-flex align-items-center justify-content-center shadow-lg"
                    style="width:100px;height:100px;">
                    <i class="fas fa-user text-white fa-3x"></i>
                </div>`;

            const ratingValue = data.rating || 0;
            const ratingStars = '★'.repeat(ratingValue) + '☆'.repeat(5 - ratingValue);

            return `
                <div class="p-4">
                    <div class="d-flex align-items-start gap-4 mb-4 pb-4 border-bottom">
                        ${profileImageHtml}
                        <div class="flex-grow-1">
                            <h2 class="h4 fw-bold text-primary mb-2">${data.name || '--'}</h2>
                            <div class="text-muted mb-3">
                                <i class="fas fa-phone-alt me-2"></i> ${data.phone || '--'}
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-primary">
                                    <i class="fas fa-map-marker-alt me-1"></i> 
                                    ${data.city || '--'} / ${data.area || '--'}
                                </span>
                                <span class="badge ${data.status === 'active' ? 'bg-success' : 'bg-secondary'}">
                                    <i class="fas fa-circle me-1" style="font-size: 0.6em;"></i> 
                                    ${data.status || 'inactive'}
                                </span>
                                <span class="badge ${data.verified ? 'bg-info' : 'bg-warning'}">
                                    <i class="fas fa-shield-alt me-1"></i> 
                                    ${data.verified ? 'Verified' : 'Not Verified'}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section mb-4">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="fas fa-user-circle me-2"></i> Personal Information
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="text-black small mb-1 fw-bold">Email Address</div>
                                <div class="fw-medium text-dark">${data.email || '--'}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="text-black small mb-1 fw-bold">Gender</div>
                                <div class="fw-medium text-dark">${data.gender || '--'}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="text-black small mb-1 fw-bold">Age</div>
                                <div class="fw-medium text-dark">${data.age || '--'}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="text-black small mb-1 fw-bold">Wallet Balance</div>
                                <div class="fw-medium text-success">₹${data.wallet_amount || 0}</div>
                            </div>
<<<<<<< HEAD
=======
                            <div class="col-md-6 mb-3">
                                <div class="text-black small mb-1 fw-bold">Device & App</div>
                                <div class="fw-medium text-dark">
                                    ${data.latest_device ? `
                                        <span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1 mb-1">
                                            v${data.latest_device.app_version || 'N/A'} ${data.latest_device.build_number ? '(' + data.latest_device.build_number + ')' : ''}
                                        </span>
                                        <div><strong>${data.latest_device.os ? data.latest_device.os.charAt(0).toUpperCase() + data.latest_device.os.slice(1) : ''} ${data.latest_device.os_version || ''}</strong></div>
                                        <small class="text-muted">${(data.latest_device.manufacturer || '') + ' ' + (data.latest_device.model || data.latest_device.device_name || '')}</small>
                                    ` : '<span class="text-muted">No Device</span>'}
                                </div>
                            </div>
>>>>>>> origin/main
                        </div>
                    </div>

                    <div class="detail-section mb-4">
                        <h6 class="fw-bold text-primary mb-3">
<<<<<<< HEAD
                            <i class="fas fa-mobile-alt me-2"></i> Registered Devices (${data.devices ? data.devices.length : 0})
                        </h6>
                        ${providerDeviceDetailsHtml}
                    </div>

                    <div class="detail-section mb-4">
                        <h6 class="fw-bold text-primary mb-3">
=======
>>>>>>> origin/main
                            <i class="fas fa-map-marked-alt me-2"></i> Service Address
                        </h6>
                        <p class="mb-0 text-dark">${data.service_address || '--'}</p>
                    </div>

                    <div class="detail-section mb-4">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="fas fa-shield-check me-2"></i> Verification Status
                        </h6>
                        <div class="row align-items-center mb-4">
                            <div class="col-md-4">
                                <div class="text-black small fw-bold">Profile Completion</div>
                            </div>
                            <div class="col-md-8">
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar" 
                                         style="width: ${Math.round(data.completionPercentage) || 0}%">
                                        ${Math.round(data.completionPercentage) || 0}%
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="text-black small fw-bold">Call Verification</div>
                            </div>
                            <div class="col-md-8">
                                ${data.verifications?.call_verification == 1
                    ? '<span class="badge bg-success"><i class="fas fa-check me-1"></i> Verified</span>'
                    : '<button class="btn btn-sm btn-info" onclick="callProvider(' + data.id + ')"><i class="fas fa-phone me-1"></i> Verify Call</button>'}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-black small fw-bold">Documents</div>
                            </div>
                            <div class="col-md-8">
                                <button class="btn btn-info btn-sm viewDocumentsLink"
                                        data-id="${data.id}"
                                        data-verifications='${JSON.stringify(data.verifications || {})}'>
                                    <i class="fas fa-file-alt me-1"></i> View Documents
                                </button>
                            </div>
                        </div>
                    </div>

                    ${serviceDetailsHtml ? serviceDetailsHtml : `
                    <div class="detail-section mb-4">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="fas fa-star me-2"></i> Admin Rating
                        </h6>
                        <div class="row align-items-center">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="text-black small mb-2 fw-bold">Current Rating</div>
                                <div class="h3 text-warning mb-1">${ratingStars}</div>
                                <div class="text-muted small">(${ratingValue}/5)</div>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <div class="text-black small mb-1 fw-bold">Comment</div>
                                    <div class="fw-medium text-dark">${data.comment || 'No comment yet'}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <div class="text-black small mb-1 fw-bold">Experience</div>
                                        <div class="fw-medium text-dark">${data.experience ? data.experience + ' Years' : '--'}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-black small mb-1 fw-bold">Confidence</div>
                                        <div class="fw-medium text-dark text-capitalize">${data.confidence || '--'}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-black small mb-1 fw-bold">Availability</div>
                                        <div class="fw-medium text-dark">${data.available_time || '--'}</div>
                                    </div>
                                </div>
                                <button class="btn btn-info open-rating-modal" 
                                        data-id="${data.id}" 
                                        data-rating="${ratingValue}" 
                                        data-comment="${data.comment || ''}"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#ratingModal">
                                    <i class="fas fa-star me-1"></i> ${data.rating ? 'Edit Rating' : 'Add Rating'}
                                </button>
                            </div>
                        </div>
                    </div>
                    `}

                    <div class="detail-section">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="fas fa-clock me-2"></i> Timestamps
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="text-black small mb-1 fw-bold">Joined At</div>
                                <div class="fw-medium text-dark">${formatDate(data.created_at)}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="text-black small mb-1 fw-bold">Verified At</div>
                                <div class="fw-medium text-dark">${formatDate(data.verifiedAt)}</div>
                            </div>
                            ${data.followup_date ? `
                            <div class="col-md-6 mb-3">
                                <div class="text-black small mb-1 fw-bold">Follow-up Date</div>
                                <div class="fw-medium text-dark">${formatDate(data.followup_date)}</div>
                            </div>
                            ` : ''}
                            ${data.last_login ? `
                            <div class="col-md-6 mb-3">
                                <div class="text-black small mb-1 fw-bold">Last Login</div>
                                <div class="fw-medium text-dark">${formatDate(data.last_login)}</div>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                </div>`;
        }

        $(document).on('click', '.view-details', function(e) {
            e.stopPropagation();
            const icon = this;
            const details = this.getAttribute('data-details');
            const modalContent = document.getElementById('modalDetailsContent');

            try {
                const parsedDetails = JSON.parse(details);
                modalContent.innerHTML = formatForModal(parsedDetails);

                setTimeout(() => {
                    modalContent.querySelectorAll('.viewDocumentsLink').forEach(link => {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            const providerId = this.getAttribute('data-id');
                            const verifications = JSON.parse(this.getAttribute('data-verifications') || '{}');
                            // loadDocuments(providerId, verifications);
                        });
                    });

                    modalContent.querySelectorAll('.open-rating-modal').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const providerId = this.getAttribute('data-id');
                            const rating = this.getAttribute('data-rating');
                            const comment = this.getAttribute('data-comment');

                            document.getElementById('ratingProviderId').value = providerId;
                            document.getElementById('ratingValue').value = rating;
                            document.getElementById('ratingComment').value = comment || '';

                            const stars = document.querySelectorAll('.rating-stars .star-rating');
                            stars.forEach((star, index) => {
                                const starIcon = star.querySelector('i');
                                if (index < rating) {
                                    starIcon.classList.remove('far', 'text-muted');
                                    starIcon.classList.add('fas', 'text-warning', 'active');
                                } else {
                                    starIcon.classList.remove('fas', 'text-warning', 'active');
                                    starIcon.classList.add('far', 'text-muted');
                                }
                            });
                        });
                    });
                }, 100);
            } catch (error) {
                modalContent.innerHTML = '<div class="alert alert-danger m-4"><i class="fas fa-exclamation-triangle me-2"></i>Error loading details. Please try again.</div>';
                console.error('Error parsing details:', error);
            }

            const offcanvasElement = document.getElementById('detailsModal');
            const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasElement);
            offcanvas.show();

            icon.classList.remove('fa-plus-circle');
            icon.classList.add('fa-minus-circle');
            icon.style.color = '#dc3545';

            offcanvasElement.addEventListener('hidden.bs.offcanvas', function() {
                icon.classList.remove('fa-minus-circle');
                icon.classList.add('fa-plus-circle');
                icon.style.color = '#0d6efd';
            }, {
                once: true
            });
        });

        // ============================
        // 8. HELPER FUNCTIONS
        // ============================
        function showAlert(type, message) {
            document.querySelectorAll('.alert.position-fixed').forEach(alert => alert.remove());

            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type === 'error' ? 'danger' : 'success'} alert-dismissible fade show position-fixed shadow-lg`;
            alertDiv.style.cssText = `
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 300px;
                max-width: 400px;
                border-radius: 10px;
                border: none;
                animation: slideIn 0.3s ease-out;
            `;

            const icon = type === 'error' ? 'exclamation-triangle' : 'check-circle';
            const color = type === 'error' ? '#dc3545' : '#198754';

            alertDiv.innerHTML = `
                <div class="d-flex align-items-start gap-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-${icon} fa-lg" style="color: ${color};"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold mb-1">${type === 'error' ? 'Error' : 'Success'}</div>
                        <div class="small">${message}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size: 0.7rem;"></button>
                </div>
            `;

            document.body.appendChild(alertDiv);

            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.classList.add('fade');
                    setTimeout(() => {
                        if (alertDiv.parentNode) {
                            alertDiv.remove();
                        }
                    }, 300);
                }
            }, 5000);
        }

        window.callProvider = function(providerId) {
            if (confirm('Call this provider for verification?')) {
                fetch(baseUrl + 'update-provider-call-status', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            provider_id: providerId,
                            call_verification: 1
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log("data", data)
                        if (data.status) {
                            showAlert('success', 'Call verification updated successfully!');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showAlert('error', data.message || 'Failed to update call verification.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('error', 'An error occurred while updating call verification.');
                    });
            }
        };

        // ============================
        // 9. INITIALIZE EVERYTHING
        // ============================
<<<<<<< HEAD
        initializeCheckboxes();
        updateBulkNotificationButtonState();
        updateApplyButtonState();

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('select-table-row') ||
                e.target.name === 'select_all_table') {
                updateBulkNotificationButtonState();
                updateApplyButtonState();
            }
        });

        // Global function for select all
        window.selectAllTable = function(checkbox) {
            const tableId = checkbox.id.replace('select-all-', '') + 'ProvidersTable';
            const table = document.getElementById(tableId);
            console.log(tableId)
            if (table) {
                const checkboxes = table.querySelectorAll('.select-table-row');
                checkboxes.forEach(cb => {
                    cb.checked = checkbox.checked;
                });
                document.dispatchEvent(new Event('change'));
            }
        };

        // Search functionality
        const searchInput = document.getElementById('searchProviders');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();

                let activeTableId = '';
                const singleView = document.getElementById('singleProviderView');
                if (singleView) {
                    // Find the visible table (either inside an active tab or the direct table if no tabs)
                    const visibleTable = singleView.querySelector('.tab-pane.show.active table') || singleView.querySelector('table');
                    if (visibleTable) activeTableId = visibleTable.id;
                }

                const table = document.getElementById(activeTableId);
                if (!table) return;

                const rows = table.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    const rowText = row.textContent.toLowerCase();
                    const isVisible = searchTerm === '' || rowText.includes(searchTerm);
                    row.style.display = isVisible ? '' : 'none';
                });
            });

=======
        if (typeof updateBulkNotificationButtonState === 'function') {
            updateBulkNotificationButtonState();
        }
        if (typeof updateApplyButtonState === 'function') {
            updateApplyButtonState();
        }

        const searchInput = document.getElementById('searchProviders');

        // Restore focus to search input after page reloads from a search
        if (searchInput && searchInput.value) {
            searchInput.focus();
            // Move cursor to the end of the text
            const val = searchInput.value;
            searchInput.value = '';
            searchInput.value = val;
        }

        // Auto-search when user stops typing (debounce)
        let searchTimeout = null;
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    applySearch();
                }, 800); // Wait 800ms after user stops typing before reloading
            });
        }

        if (searchInput) {
>>>>>>> origin/main
            document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
                tab.addEventListener('shown.bs.tab', function() {
                    searchInput.value = '';
                    const tableId = this.getAttribute('aria-controls') + 'ProvidersTable';
                    const table = document.getElementById(tableId);
                    if (table) {
                        table.querySelectorAll('tbody tr').forEach(row => {
                            row.style.display = '';
                        });
                    }
                });
            });
        }

        // Download providers functionality
        window.downloadProviders = function() {
<<<<<<< HEAD
            const selectedIds = Array.from(document.querySelectorAll('.select-table-row:checked'))
                .map(checkbox => checkbox.value);

            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');

            if (selectedIds.length === 0) {
                if (confirm('No providers selected. Download all providers?')) {
                    let downloadUrl = "<?= base_url() ?>downloadProviders";
                    if (status) {
                        downloadUrl += `?status=${encodeURIComponent(status)}`;
                    }
                    window.location.href = downloadUrl;
                }
            } else {
                const form = document.createElement("form");
                form.method = "GET";
                form.action = "<?= base_url() ?>downloadProviders";
                form.style.display = "none";

=======
            // 1. Collect all checked provider IDs
            const selectedCheckboxes = document.querySelectorAll('.select-table-row:checked');
            const selectedIds = Array.from(selectedCheckboxes)
                .map(cb => cb.value)
                .filter(val => val !== "" && val !== undefined && val !== null);

            // 2. Collect current URL and DOM filter values
            const urlParams = new URLSearchParams(window.location.search);

            const serviceFilter = document.getElementById('serviceFilter');
            if (serviceFilter && serviceFilter.value) {
                urlParams.set('service_id', serviceFilter.value);
            }

            const cityFilter = document.getElementById('cityFilter');
            if (cityFilter && cityFilter.value) {
                urlParams.set('city', cityFilter.value);
            }

            const areaFilter = document.getElementById('areaFilter');
            if (areaFilter && areaFilter.value) {
                urlParams.set('area', areaFilter.value);
            }

            const experienceFilter = document.getElementById('experienceFilter');
            if (experienceFilter && experienceFilter.value) {
                urlParams.set('experience', experienceFilter.value);
            }

            const searchInput = document.getElementById('searchProviders');
            if (searchInput && searchInput.value.trim()) {
                urlParams.set('search', searchInput.value.trim());
            }

            // 3. Create a form to POST / GET download request
            const form = document.createElement("form");
            form.method = "POST";
            form.action = "<?= base_url('downloadProviders') ?>";
            form.style.display = "none";

            const csrfTokenName = '<?= csrf_token() ?>';
            const csrfHash = '<?= csrf_hash() ?>';
            if (csrfTokenName && csrfHash) {
                const inputCsrf = document.createElement("input");
                inputCsrf.type = "hidden";
                inputCsrf.name = csrfTokenName;
                inputCsrf.value = csrfHash;
                form.appendChild(inputCsrf);
            }

            if (selectedIds.length > 0) {
>>>>>>> origin/main
                const inputIds = document.createElement("input");
                inputIds.type = "hidden";
                inputIds.name = "ids";
                inputIds.value = JSON.stringify(selectedIds);
                form.appendChild(inputIds);
<<<<<<< HEAD

                if (status) {
                    const inputStatus = document.createElement("input");
                    inputStatus.type = "hidden";
                    inputStatus.name = "status";
                    inputStatus.value = status;
                    form.appendChild(inputStatus);
                }

                document.body.appendChild(form);
                form.submit();
            }
        };

        // Send notification functionality
        $(document).on('click', '[data-assign-module]', function() {
            const dataId = $(this).data('id');
            const moduleId = $(this).data('module');
=======
            }

            for (const [key, value] of urlParams.entries()) {
                if (key !== 'ids' && key !== 'page') {
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = key;
                    input.value = value;
                    form.appendChild(input);
                }
            }

            document.body.appendChild(form);
            form.submit();
            setTimeout(() => {
                if (document.body.contains(form)) {
                    document.body.removeChild(form);
                }
            }, 1000);
        };

        // Send notification functionality (Individual click)
        $(document).on('click', '[data-assign-module]', function(e) {
            e.stopPropagation();
            const dataId = $(this).data('id') || $(this).data('assign-module');
            const moduleId = $(this).data('module') || 'service_providers';
>>>>>>> origin/main

            const targetForm = $('#sendNotification1');
            targetForm.data('id', dataId);
            targetForm.data('module', moduleId);
<<<<<<< HEAD
        });

=======
            $('#selectedProviderIds').val('');

            $('#recipientCountText').html(`Target: <strong>Provider ID #${dataId}</strong>`);
            $('#Title').val('');
            $('#description').val('');

            const offcanvasEl = document.getElementById('send_push_notification');
            if (offcanvasEl) {
                bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl).show();
            }
        });

        // Send notification submit (handles both single provider and bulk selected providers)
>>>>>>> origin/main
        $('#sendNotification1').on('submit', function(e) {
            e.preventDefault();

            const url = $(this).attr('action');
<<<<<<< HEAD
            let id = $(this).data('id') || $('#selectedProviderIds').val();
            const title = $('#Title').val();
            const message = $('#description').val();
            let module = $(this).data('module') || 'service_providers';
=======
            let id = $(this).data('id');
            if (!id) {
                id = $('#selectedProviderIds').val();
            }

            const title = $('#Title').val().trim();
            const message = $('#description').val().trim();
            let module = $(this).data('module') || 'service_providers';
            const queueOnly = $('#sp_queue_only').length ? ($('#sp_queue_only').is(':checked') ? 1 : 0) : 1;

            if (!id || id === '[]' || id === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Recipient Selected',
                    text: 'Please select at least one provider to send notification.'
                });
                return;
            }

            if (!title || !message) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Information',
                    text: 'Please enter both Title and Message.'
                });
                return;
            }

            const submitBtn = $('#sendNotificationBtn');
            const originalBtnHtml = submitBtn.html();
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Sending...');
>>>>>>> origin/main

            $.ajax({
                url: url,
                method: 'POST',
                headers: {
<<<<<<< HEAD
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'id': id,
                    title: title,
                    message: message,
                    module: module
                },
                traditional: true,
                success: function(response) {
                    if (response.status) {
                        Swal.fire('Success!', 'Notification sent successfully.', 'success')
                        setTimeout(() => {
                            location.reload();
                        }, 1000)
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error sending notification:', error);
                    Swal.fire('Error!', 'An error occurred. Please try again later.', 'error');
=======
                    'X-CSRF-TOKEN': csrfToken || $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: id,
                    title: title,
                    message: message,
                    module: module,
                    queue_only: queueOnly
                },
                traditional: true,
                success: function(response) {
                    submitBtn.prop('disabled', false).html(originalBtnHtml);
                    if (response.status) {
                        const offcanvasEl = document.getElementById('send_push_notification');
                        if (offcanvasEl) {
                            const inst = bootstrap.Offcanvas.getInstance(offcanvasEl);
                            if (inst) inst.hide();
                        }
                        const successMsg = response.message || (response.is_queue ? 'Notification queued successfully.' : 'Notification sent successfully.');
                        Swal.fire('Success!', successMsg, 'success');

                        // Reset selection & inputs
                        $('#Title').val('');
                        $('#description').val('');
                        document.querySelectorAll('.select-table-row').forEach(cb => cb.checked = false);
                        document.querySelectorAll('input[name="select_all_table"], [id^="select-all-"]').forEach(cb => {
                            cb.checked = false;
                            cb.indeterminate = false;
                        });
                        if (typeof updateBulkNotificationButtonState === 'function') {
                            updateBulkNotificationButtonState();
                        }
                        if (typeof updateApplyButtonState === 'function') {
                            updateApplyButtonState();
                        }
                    } else {
                        Swal.fire('Error!', response.message || 'Failed to send notification.', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    submitBtn.prop('disabled', false).html(originalBtnHtml);
                    console.error('Error sending notification:', error);
                    Swal.fire('Error!', 'An error occurred while sending notification. Please try again.', 'error');
>>>>>>> origin/main
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $(document).on('change', '#cityFilter', function() {
            const city = $(this).val();
            const areaFilter = $('#areaFilter');

            // Clear current areas
            areaFilter.html('<option value="">All Areas</option>');

            if (city) {
                $.ajax({
                    url: '<?= base_url('getAreas') ?>',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        city: city
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response && response.length > 0) {
                            response.forEach(function(item) {
                                areaFilter.append($('<option></option>').val(item.area).text(item.area));
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Failed to fetch areas:', error);
                    }
                });
            }
        });
    });

    function applyFilters() {
<<<<<<< HEAD
        const service = document.getElementById('serviceFilter').value;
        const city = document.getElementById('cityFilter').value;
        const area = document.getElementById('areaFilter').value;

        let url = new URL(window.location.href);
        if (service) url.searchParams.set('service_id', service);
        else url.searchParams.delete('service_id');

        if (city) url.searchParams.set('city', city);
        else url.searchParams.delete('city');

        if (area) url.searchParams.set('area', area);
        else url.searchParams.delete('area');

=======
        const searchInput = document.getElementById('searchProviders');
        const serviceSelect = document.getElementById('serviceFilter');
        const citySelect = document.getElementById('cityFilter');
        const areaSelect = document.getElementById('areaFilter');
        const experienceSelect = document.getElementById('experienceFilter');

        let url = new URL(window.location.href);

        const searchVal = searchInput ? searchInput.value.trim() : '';
        if (searchVal) url.searchParams.set('search', searchVal);
        else url.searchParams.delete('search');

        const serviceVal = serviceSelect ? serviceSelect.value : '';
        if (serviceVal) url.searchParams.set('service_id', serviceVal);
        else url.searchParams.delete('service_id');

        const cityVal = citySelect ? citySelect.value : '';
        if (cityVal) url.searchParams.set('city', cityVal);
        else url.searchParams.delete('city');

        const areaVal = areaSelect ? areaSelect.value : '';
        if (areaVal) url.searchParams.set('area', areaVal);
        else url.searchParams.delete('area');

        if (experienceSelect) {
            const expVal = experienceSelect.value;
            if (expVal) url.searchParams.set('experience', expVal);
            else url.searchParams.delete('experience');
        }

>>>>>>> origin/main
        url.searchParams.delete('page');

        window.location.href = url.toString();
    }

    function resetFilters() {
        let url = new URL(window.location.href);
        url.searchParams.delete('service_id');
        url.searchParams.delete('city');
        url.searchParams.delete('area');
        url.searchParams.delete('experience');
<<<<<<< HEAD
=======
        url.searchParams.delete('search');
>>>>>>> origin/main
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }

<<<<<<< HEAD
    function applyExperienceFilter(val) {
        let url = new URL(window.location.href);
        if (val) url.searchParams.set('experience', val);
        else url.searchParams.delete('experience');
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }

    window.downloadProviders = function() {
        const baseUrl = '<?= base_url() ?>';
        const params = new URLSearchParams(window.location.search);
        window.location.href = baseUrl + 'downloadProviders?' + params.toString();
    }
</script>
</main>
=======
    function applySearch() {
        applyFilters();
    }

    function applyExperienceFilter(val) {
        applyFilters();
    }

    function changeLimit(limit) {
        let url = new URL(window.location.href);
        url.searchParams.set('limit', limit);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }
</script>
>>>>>>> origin/main
<?= $this->endSection(); ?>