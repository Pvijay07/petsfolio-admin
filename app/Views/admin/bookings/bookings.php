<?php echo $this->extend('admin/includes/header'); ?>
<?php echo $this->section('content'); ?>
<?php
<<<<<<< HEAD
=======
$status = $status ?? 'in_progress';
$bookings = $bookings ?? [];

>>>>>>> origin/main
// PHP helpers for status and UI elements
if (!function_exists('getStatusClass')) {
    function getStatusClass($status)
    {
        $statusLower = strtolower($status ?? '');
        if (strpos($statusLower, 'pending') !== false)
            return 'status-pending';
        if (strpos($statusLower, 'active') !== false)
            return 'status-active';
        if (strpos($statusLower, 'completed') !== false)
            return 'status-completed';
        if (strpos($statusLower, 'cancelled') !== false)
            return 'status-cancelled';
        if (strpos($statusLower, 'deleted') !== false)
            return 'status-deleted';
        if (strpos($statusLower, 'halted') !== false)
            return 'status-halted';
        if (strpos($statusLower, 'confirmed') !== false)
<<<<<<< HEAD
            return 'status-confirmed';
        if (strpos($statusLower, 'progress') !== false)
            return 'status-inprogress';
=======
            return 'status-active';
        if (strpos($statusLower, 'progress') !== false)
            return 'status-active';
>>>>>>> origin/main
        return 'status-pending';
    }
}

if (!function_exists('buildCommentSelect')) {
    function buildCommentSelect($data, $bookingId, $serviceId)
    {
        if (!$data) {
            $options = ['Not interested', 'Will book later', 'Followup', 'Bad service'];
            $html = '<div class="text-center">
                <select class="form-control form-control-sm booking-comment" data-booking-id="' . $bookingId . '" data-service-id="' . $serviceId . '">
                    <option value="">Select comment</option>';
            foreach ($options as $opt) {
                $html .= '<option value="' . $opt . '">' . $opt . '</option>';
            }
            $html .= '</select>
                <div class="comment-status small mt-1 text-center"></div>
            </div>';
            return $html;
        }
        return '<div class="text-center">' . esc($data) . '</div>';
    }
}
<<<<<<< HEAD
?>

=======

// Helper for avatar initials and pastel colors matching modern SaaS UI
if (!function_exists('getInitialsAvatar')) {
    function getInitialsAvatar($name, $id = 0)
    {
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
$totalBookingsInView = count($bookings ?? []);
$inProgressCount = 0;
$totalCumulativeValue = 0;
$dueTodayCount = 0;
$todayDateStr = date('Y-m-d');

if (!empty($bookings)) {
    foreach ($bookings as $b) {
        $st = strtolower($b->status ?? '');
        if (in_array($st, ['confirmed', 'in progress', 'in_progress', 'active'])) {
            $inProgressCount++;
        }
        $price = (float)($b->price ?? $b->bid_amount ?? $b->total_price ?? 0);
        $totalCumulativeValue += $price;

        if (!empty($b->service_start_date) && substr($b->service_start_date, 0, 10) === $todayDateStr) {
            $dueTodayCount++;
        }
    }
}

$statusClean = strtolower($status ?? 'in_progress');
$isInProgress = in_array($statusClean, ['in_progress', 'confirmed', 'active']);
$isHalted = $statusClean === 'halted';
$isCompleted = $statusClean === 'completed';
$isCancelled = $statusClean === 'cancelled';
$isDeleted = $statusClean === 'deleted';
?>

<style>
    /* Modern Dashboard UI Styles for Bookings */
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
        background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0) 70%);
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
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
        }

        70% {
            transform: scale(1);
            box-shadow: 0 0 0 6px rgba(34, 197, 94, 0);
        }

        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
        }
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
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
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
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .nl-btn-action:hover {
        background: #f8fafc;
        color: #0f172a;
        border-color: #94a3b8;
    }

    /* Filter Card */
    .nl-filter-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px 20px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
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
        box-shadow: 0 2px 14px rgba(0, 0, 0, 0.025);
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

    /* Badges */
    .badge-service {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge-service.walking {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .badge-service.grooming {
        background: #f5f3ff;
        color: #6d28d9;
        border: 1px solid #ddd6fe;
    }

    .badge-service.boarding {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    .badge-service.sitting {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .badge-service.training {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .badge-service.default {
        background: #f8fafc;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 11px;
        border-radius: 50px;
        font-size: 11.5px;
        font-weight: 600;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .status-active,
    .status-inprogress,
    .status-confirmed {
        background: #dcfce7;
        color: #15803d;
    }

    .status-active .status-dot,
    .status-inprogress .status-dot,
    .status-confirmed .status-dot {
        background: #16a34a;
    }

    .status-pending {
        background: #fef3c7;
        color: #b45309;
    }

    .status-pending .status-dot {
        background: #d97706;
    }

    .status-completed {
        background: #e0f2fe;
        color: #0369a1;
    }

    .status-completed .status-dot {
        background: #0284c7;
    }

    .status-cancelled {
        background: #fee2e2;
        color: #b91c1c;
    }

    .status-cancelled .status-dot {
        background: #dc2626;
    }

    .status-deleted {
        background: #f1f5f9;
        color: #475569;
    }

    .status-deleted .status-dot {
        background: #64748b;
    }

    .status-halted {
        background: #ffedd5;
        color: #c2410c;
    }

    .status-halted .status-dot {
        background: #ea580c;
    }

    .nl-btn-nearby {
        background: #ffffff;
        color: #4f46e5;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 11.5px;
        font-weight: 600;
        padding: 5px 12px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none;
    }

    .nl-btn-nearby:hover {
        background: #4f46e5;
        color: #ffffff;
        border-color: #4f46e5;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(79, 70, 229, 0.25);
    }

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
    }

    .nl-action-btn.view {
        color: #4f46e5;
    }

    .nl-action-btn.view:hover {
        background: #4f46e5;
        color: #ffffff;
        border-color: #4f46e5;
    }

    .nl-action-btn.delete {
        color: #ef4444;
    }

    .nl-action-btn.delete:hover {
        background: #ef4444;
        color: #ffffff;
        border-color: #ef4444;
    }

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

    .nl-page-btn:disabled,
    .nl-page-btn.disabled {
        color: #cbd5e1;
        cursor: not-allowed;
        pointer-events: none;
    }

    .nl-page-btn.nav-btn {
        border: 1px solid #e2e8f0;
        background: #ffffff;
    }
</style>

>>>>>>> origin/main
<main class="main-content">
    <div class="position-relative iq-banner">
        <?php echo $this->include('admin/nav_bar') ?>
    </div>

<<<<<<< HEAD
    <div class="conatiner-fluid content-inner  px-4" id="bk-pgrs">
        <!-- Modern Header Section -->
        <div class="container-fluid p-0 mb-4">
            <div class="bg-gradient-primary text-white p-5 rounded-3 shadow position-relative overflow-hidden">
                <div class="position-relative z-1">
                    <h1 class="h2 fw-bold mb-2 text-white">
                        <?php echo ucfirst($status == 'Confirmed' ? 'In Progress' : $status); ?> Bookings
                    </h1>
                    <p class="text-white-75 mb-0">Track and manage service requests, assign providers, and monitor
                        status updates in real-time.</p>
                </div>
                <i class="bi bi-calendar-check position-absolute end-0 bottom-0 opacity-10"
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
                            <input type="text" class="form-control border-start-0" placeholder="Search bookings..."
                                id="searchInput">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2 mb-md-0">
                            <label class="text-muted small me-2">Show</label>
                            <select class="form-select form-select-sm" style="width: auto;" id="entriesPerPage">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="all">All</option>
                            </select>
                            <label class="text-muted small ms-2">entries</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-black bg-black p-2 text-white w-100" onclick="applyFilters()">
                            <i class="bi bi-funnel me-1"></i>Filter
                        </button>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-black bg-black p-2 text-white w-100" onclick="clearFilters()">
                            <i class="bi bi-x-circle me-1"></i>Clear
                        </button>
                    </div>

=======
    <div class="container-fluid px-4 content-inner pb-5" id="bk-pgrs">

        <!-- Hero Banner with Stats matching Modern Dashboard -->
        <div class="nl-hero-banner">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <div class="nl-live-pill">
                        <span class="nl-live-dot"></span>
                        <span>Live Bookings Status</span>
                    </div>
                    <h2 class="fw-bold text-white mb-2" style="font-size: 30px; letter-spacing: -0.02em;">
                        <?= ucfirst($status == 'Confirmed' || $status == 'in_progress' ? 'In Progress' : $status) ?> Bookings
                    </h2>
                    <p class="mb-0 text-white text-opacity-75" style="font-size: 13.5px; max-width: 560px; line-height: 1.55;">
                        Track and manage service requests, assign providers, and monitor status updates in real-time.
                    </p>
                </div>

                <div class="col-lg-5">
                    <div class="d-flex align-items-center gap-2 flex-wrap justify-content-lg-end">
                        <!-- Total in View -->
                        <div class="nl-stat-card">
                            <span class="label">Total in View</span>
                            <div class="value"><?= number_format($totalBookingsInView) ?></div>
                        </div>
                        <!-- In Progress -->
                        <div class="nl-stat-card">
                            <span class="label">In Progress</span>
                            <div class="value" style="color: #6ee7b7;"><?= number_format($inProgressCount) ?></div>
                        </div>
                        <!-- Total Value -->
                        <div class="nl-stat-card">
                            <span class="label">Total Value</span>
                            <div class="value" style="color: #93c5fd; font-size: 20px;">₹<?= number_format($totalCumulativeValue) ?></div>
                        </div>
                        <!-- Due Today -->
                        <div class="nl-stat-card">
                            <span class="label">Due Today</span>
                            <div class="value" style="color: #fde047;"><?= number_format($dueTodayCount) ?></div>
                        </div>
                    </div>
>>>>>>> origin/main
                </div>
            </div>
        </div>

<<<<<<< HEAD
        <!-- Bookings Table Section -->
        <div class="card border-0 shadow-sm overflow-hidden rounded-3">
            <div class="table-responsive">
                <table class="table table-hover mb-0 mt-0" id="bookingsTable"
                    style="width:100% !important; marginTop: 0 !important;">
                    <thead class="" style="background: #c6d2ff !important;">
                        <tr>
                            <th data-orderable="false"
                                class=" py-3 text-black fw-semibold border-bottom text-center fs-6"
                                style="background: #c6d2ff !important;">Customer Details</th>
                            <th data-orderable="false"
                                class="py-3 text-black fw-semibold border-bottom d-none d-lg-table-cell text-center fs-6"
                                style="background: #c6d2ff !important;">Pet Details</th>
                            <th data-orderable="false"
                                class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                                style="background: #c6d2ff !important;">Service</th>
                            <th data-orderable="false"
                                class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                                style="background: #c6d2ff !important;">Price</th>

                            <?php if ($status === 'deleted') { ?>
                                <th data-orderable="false"
                                    class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                                    style="background: #c6d2ff !important;">Deleted At</th>
                                <th data-orderable="false"
                                    class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                                    style="background: #c6d2ff !important;">Comment</th>
                            <?php } elseif ($status === 'cancelled') { ?>
                                <th data-orderable="false"
                                    class="py-3 text-black fw-semibold border-bottom d-none d-sm-table-cell text-center fs-6"
                                    style="background: #c6d2ff !important;">Provider</th>
                                <th data-orderable="false"
                                    class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                                    style="background: #c6d2ff !important;">Reason</th>
                                <th data-orderable="false"
                                    class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                                    style="background: #c6d2ff !important;">Comment</th>
                            <?php } elseif ($status !== 'halted') { ?>
                                <th data-orderable="false"
                                    class="py-3 text-black fw-semibold border-bottom d-none d-sm-table-cell text-center fs-6"
                                    style="background: #c6d2ff !important;">Provider</th>
                                <th data-orderable="false"
                                    class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                                    style="background: #c6d2ff !important;">Status</th>
                            <?php } else { ?>
                                <th data-orderable="false"
                                    class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                                    style="background: #c6d2ff !important;">Quotes</th>
                                <th data-orderable="false"
                                    class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                                    style="background: #c6d2ff !important;">Service Date</th>
                                <th data-orderable="false"
                                    class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                                    style="background: #c6d2ff !important;">Repost</th>
                            <?php } ?>

                            <th data-orderable="false"
                                class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                                style="background: #c6d2ff !important; width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0" id="tableBody">
                        <?php if (!empty($bookings)): ?>
                            <?php foreach ($bookings as $booking): ?>
                                <tr id="row_<?= $booking->id ?>" class="booking-row">
                                    <td class="text-center align-middle">
                                        <div class="d-flex align-items-center justify-content-center gap-3">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary"
                                                style="width: 2.5rem; height: 2.5rem;  color: #fff; font-weight: bold; font-size: 0.875rem;">
                                                <?= !empty($booking->user_name) ? strtoupper(substr($booking->user_name, 0, 1)) : '?' ?>
                                            </div>
                                            <div class="" style="min-width: 0;">
                                                <div class="fw-bold text-truncate text-center"
                                                    title="<?= esc($booking->user_name ?? '--') ?>">
                                                    <?= esc($booking->user_name ?? '--') ?>
                                                </div>
                                                <div
                                                    class="d-flex align-items-center text-muted small mt-1 justify-content-center">
                                                    <i class="bi bi-telephone me-1"></i>
                                                    <span class="text-truncate">
                                                        <?= esc($booking->user_phone ?? '--') ?>
                                                    </span>
                                                </div>
                                                <div
                                                    class="d-flex align-items-center text-muted small mt-1 justify-content-center">
                                                    <i class="bi bi-geo-alt me-1"></i>
                                                    <span class="text-truncate cursor-pointer address-tooltip"
                                                        title="<?= esc($booking->formatted_address ?? $booking->address ?? '') ?>">
                                                        <?= esc($booking->state ?? '--') ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle d-none d-lg-table-cell">
                                        <div style="min-width: 0;">
                                            <div class="fw-bold text-truncate text-center mb-2"
                                                title="<?= esc($booking->pet_name ?? '--') ?>">
                                                <?= esc($booking->pet_name ?? '--') ?>
                                            </div>
                                            <div class="d-flex flex-wrap justify-content-center gap-1 ">
                                                <?php if (!empty($booking->breed) && $booking->breed !== '--'): ?>
                                                    <span class="breed-badge"
                                                        style="background-color: rgba(251, 191, 36, 0.1) !important; color: #d97706 !important; border: 1px solid rgba(251, 191, 36, 0.2) !important; font-size: 0.75rem;">
                                                        <?= esc($booking->breed) ?>
                                                    </span>
=======
        <!-- Segmented Status Tabs & Action Bar -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div class="nl-segmented-tabs">
                <a href="<?= base_url('bookings/in_progress') ?>" class="nl-tab-pill <?= $isInProgress ? 'active' : '' ?>">
                    <i class="bi bi-play-circle"></i>
                    <span>In Progress</span>
                </a>
                <a href="<?= base_url('bookings/halted') ?>" class="nl-tab-pill <?= $isHalted ? 'active' : '' ?>">
                    <i class="bi bi-pause-circle"></i>
                    <span>Halted</span>
                </a>
                <a href="<?= base_url('bookings/completed') ?>" class="nl-tab-pill <?= $isCompleted ? 'active' : '' ?>">
                    <i class="bi bi-check-circle"></i>
                    <span>Completed</span>
                </a>
                <a href="<?= base_url('bookings/cancelled') ?>" class="nl-tab-pill <?= $isCancelled ? 'active' : '' ?>">
                    <i class="bi bi-x-circle"></i>
                    <span>Cancelled</span>
                </a>
                <a href="<?= base_url('bookings/deleted') ?>" class="nl-tab-pill <?= $isDeleted ? 'active' : '' ?>">
                    <i class="bi bi-trash"></i>
                    <span>Deleted</span>
                </a>
            </div>

            <div class="d-flex align-items-center gap-2">
                <?php if ($isHalted): ?>
                    <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2 px-3 py-2 rounded-3 fw-bold shadow-sm" style="font-size: 13px;" onclick="openBulkNotificationModal()">
                        <i class="bi bi-bell-fill"></i>
                        <span>Send Bulk Notifications</span>
                        <span class="badge bg-white text-primary rounded-pill px-2 py-0.5 ms-1" id="haltedSelectedCountBadge" style="display: none;">0</span>
                    </button>
                <?php endif; ?>
                <button type="button" class="nl-btn-action" onclick="exportBookingsToCSV()">
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
                        <input type="text" class="nl-search-input" id="searchInput"
                            placeholder="Search active bookings by customer, phone, provider, pet...">
                    </div>
                </div>
                <div class="col-lg-3 col-md-4">
                    <select class="nl-select w-100" id="serviceFilter">
                        <option value="all">All Services</option>
                        <option value="walking">Dog Walking</option>
                        <option value="grooming">Pet Grooming</option>
                        <option value="sitting">Pet Sitting</option>
                        <option value="training">Pet Training</option>
                        <option value="boarding">Pet Boarding</option>
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

        <!-- Bookings Data Table Card -->
        <div class="nl-table-card">
            <div class="table-responsive">
                <table class="table nl-table mb-0" id="bookingsTable">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: <?= $isHalted ? '70px' : '60px' ?>;">
                                <?php if ($isHalted): ?>
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <input type="checkbox" id="selectAllHaltedBookings" class="form-check-input m-0" style="cursor: pointer;" title="Select All Halted Bookings" onchange="toggleSelectAllHalted(this)">
                                    </div>
                                <?php else: ?>
                                    S.No.
                                <?php endif; ?>
                            </th>
                            <th>Customer Details</th>
                            <th>Pet Details</th>
                            <th class="text-center">Service</th>
                            <th class="text-end">Price</th>
                            <?php if ($status === 'deleted'): ?>
                                <th class="text-center">Status</th>
                                <th class="text-center">Deleted At</th>
                                <th class="text-center">Comment</th>
                                <th class="text-center">Repost</th>
                            <?php elseif ($status === 'cancelled'): ?>
                                <th>Provider</th>
                                <th class="text-center">Status</th>
                                <th>Reason</th>
                                <th class="text-center">Comment</th>
                                <th class="text-center">Repost</th>
                            <?php elseif ($status !== 'halted'): ?>
                                <th class="text-center">Service Date</th>
                                <th class="text-center">Created Date</th>
                                <?php if (in_array(strtolower($status ?? ''), ['in_progress', 'confirmed', 'completed'])): ?>
                                    <th class="text-center">Confirmed Date</th>
                                <?php endif; ?>
                                <th>Provider</th>
                                <th class="text-center">Status</th>
                            <?php else: ?>
                                <th class="text-center">Status</th>
                                <th class="text-center">Quotes</th>
                                <th class="text-center">Service Date</th>
                                <th class="text-center">Repost</th>
                            <?php endif; ?>
                            <th class="text-center" style="width: 130px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php if (!empty($bookings)): ?>
                            <?php
                            $i = 1;
                            foreach ($bookings as $booking):
                                $avatar = getInitialsAvatar($booking->user_name ?? '', $booking->id ?? $i);

                                // Service class
                                $serviceRaw = strtolower($booking->service_type ?? $booking->service_name ?? 'default');
                                $serviceClass = 'default';
                                if (strpos($serviceRaw, 'walk') !== false) $serviceClass = 'walking';
                                elseif (strpos($serviceRaw, 'groom') !== false) $serviceClass = 'grooming';
                                elseif (strpos($serviceRaw, 'board') !== false) $serviceClass = 'boarding';
                                elseif (strpos($serviceRaw, 'sit') !== false) $serviceClass = 'sitting';
                                elseif (strpos($serviceRaw, 'train') !== false) $serviceClass = 'training';
                            ?>
                                <tr id="row_<?= $booking->id ?>" class="booking-row" data-service="<?= $serviceClass ?>">
                                    <!-- S.No. / Selection -->
                                    <td class="text-center align-middle">
                                        <?php if ($isHalted): ?>
                                            <div class="d-flex align-items-center justify-content-center gap-1.5">
                                                <input type="checkbox" class="form-check-input halted-booking-checkbox m-0" value="<?= esc($booking->id) ?>" style="cursor: pointer;" onchange="updateHaltedSelectionCount()">
                                                <span class="fw-bold text-muted" style="font-size: 11px;"><?= sprintf('%02d', $i) ?></span>
                                            </div>
                                        <?php else: ?>
                                            <span class="fw-bold" style="color: #64748b; font-size: 13px;"><?= sprintf('%02d', $i) ?></span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Customer Column -->
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="nl-avatar" style="background: <?= $avatar['bg'] ?>; color: <?= $avatar['color'] ?>;">
                                                <?= $avatar['text'] ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark" style="font-size: 13.5px; line-height: 1.3;">
                                                    <?= esc($booking->user_name ?? '--') ?>
                                                </div>
                                                <div class="d-flex align-items-center text-muted small mt-1 gap-1" style="font-size: 11.5px;">
                                                    <i class="bi bi-telephone text-secondary"></i>
                                                    <a href="tel:<?= esc($booking->user_phone ?? '') ?>" class="text-decoration-none text-muted"><?= esc($booking->user_phone ?? '--') ?></a>
                                                </div>
                                                <?php if (!empty($booking->state) || !empty($booking->formatted_address) || !empty($booking->address)): ?>
                                                    <div class="text-muted small d-flex align-items-center gap-1 mt-0.5" style="font-size: 11px;">
                                                        <i class="bi bi-geo-alt text-secondary"></i>
                                                        <span class="text-truncate address-tooltip" style="max-width: 170px;" title="<?= esc($booking->formatted_address ?? $booking->address ?? '') ?>">
                                                            <?= esc($booking->state ?? $booking->formatted_address ?? '--') ?>
                                                        </span>
                                                    </div>
>>>>>>> origin/main
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
<<<<<<< HEAD
                                    <td class="text-center align-middle">
                                        <div style="min-width: 0;">
                                            <div class="mb-2 text-center">
                                                <a href="javascript:void(0);" class="show-booking-details text-decoration-none"
                                                    data-booking-id="<?= $booking->id ?>">
                                                    <span class="service-badge p-2"
                                                        style="background-color: rgba(59, 130, 246, 0.1) !important; color: #2563eb !important; border: 1px solid rgba(59, 130, 246, 0.2) !important;">
                                                        <?= esc($booking->service_name ?? '--') ?>
                                                    </span>
                                                </a>
                                                
                                            </div>
                                           <?php if (!empty($booking->type)): ?>
                                                <span class="badge rounded-pill p-1 px-2 border-0" style="background-color: <?= strtolower($booking->type) === 'extend' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(107, 114, 128, 0.1)' ?> !important;
                                                            color: <?= strtolower($booking->type) === 'extend' ? '#10b981' : '#6b7280' ?> !important;
                                                            border: 1px solid <?= strtolower($booking->type) === 'extend' ? 'rgba(16, 185, 129, 0.2)' : 'rgba(107, 114, 128, 0.2)' ?> !important;
                                                            font-size: 0.7rem; text-transform: uppercase;">
                                                    <i
                                                        class="bi <?= strtolower($booking->type) === 'extend' ? 'bi-arrow-repeat' : 'bi-plus-circle' ?> me-1"></i>
                                                    <?= htmlspecialchars($booking->type) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div style="min-width: 0;">
                                            <div class="price-text text-success text-center">
                                                ₹<?= esc($booking->price ?? $booking->bid_amount ?? $booking->total_price ?? '0') ?>
                                            </div>
=======

                                    <!-- Pet Details Column -->
                                    <td class="align-middle">
                                        <div class="fw-bold text-dark mb-1" style="font-size: 13px;" title="<?= esc($booking->pet_name ?? '--') ?>">
                                            <?= esc($booking->pet_name ?? '--') ?>
                                        </div>
                                        <?php if (!empty($booking->breed) && $booking->breed !== '--'): ?>
                                            <span class="badge rounded-pill" style="background: rgba(245, 158, 11, 0.1); color: #b45309; border: 1px solid rgba(245, 158, 11, 0.25); font-size: 11px; font-weight: 600;">
                                                <?= esc($booking->breed) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Service Column -->
                                    <td class="align-middle text-center">
                                        <a href="javascript:void(0);" class="show-booking-details booking-details-btn text-decoration-none"
                                            data-booking-id="<?= $booking->id ?>"
                                            data-booking="<?= htmlspecialchars(json_encode($booking), ENT_QUOTES, 'UTF-8') ?>"
                                            onclick="event.stopPropagation(); showBookingDetailsFromElem(this);"
                                            title="View Booking Details">
                                            <span class="badge-service <?= $serviceClass ?>">
                                                <?= esc(ucfirst($booking->service_type ?? $booking->service_name ?? '--')) ?>
                                                <?php if (!empty($booking->booking_type) && strtolower($booking->booking_type) === 'premium'): ?>
                                                    <i class="bi bi-star-fill text-warning ms-1" style="font-size: 10px;"></i>
                                                <?php endif; ?>
                                            </span>
                                        </a>
                                    </td>

                                    <!-- Price Column -->
                                    <td class="align-middle text-end">
                                        <div class="fw-bold" style="color: #4338ca; font-size: 14.5px;">
                                            ₹<?= esc(number_format((float)($booking->bid_amount ?? $booking->price ?? $booking->total_price ?? 0))) ?>
>>>>>>> origin/main
                                        </div>
                                    </td>

                                    <?php if ($status === 'deleted'): ?>
<<<<<<< HEAD
                                        <td class="text-center align-middle"><?= esc($booking->deleted_at ?? '--') ?></td>
                                        <td class="text-center align-middle">
                                            <?= buildCommentSelect($booking->comment ?? '', $booking->id, $booking->service_id) ?>
                                        </td>
                                    <?php elseif ($status === 'cancelled'): ?>
                                        <td class="text-center align-middle d-none d-sm-table-cell">
                                            <?= esc($booking->provider_name ?? '--') ?>
                                        </td>
                                        <td class="text-center align-middle"><?= esc($booking->cancel_reason ?? '--') ?></td>
                                        <td class="text-center align-middle">
                                            <?= buildCommentSelect($booking->comment ?? '', $booking->id, $booking->service_id) ?>
                                        </td>
                                    <?php elseif ($status !== 'halted'): ?>
                                        <td class="text-center align-middle d-none d-sm-table-cell">
                                            <?php if (($booking->totalQuotes ?? 0) > 0): ?>
                                                <div class="fw-medium text-center">
                                                    <a href="<?= base_url('bookings/walkingQuotes/' . $booking->id) ?>"
                                                        class="text-decoration-none"><?= esc($booking->provider_name ?? '--') ?></a>
                                                </div>
                                            <?php else: ?>
                                                <div class="text-center"><?= esc($booking->provider_name ?? '--') ?></div>
                                            <?php endif; ?>
                                        </td>
                                       <td class="text-center align-middle">
    <?php
    $bookingStatusText = $booking->status ?? '';
    if ($bookingStatusText === 'Confirmed')
        $bookingStatusText = 'In Progress';
    $statusClass = getStatusClass($bookingStatusText);
    ?>
    <div class="text-center">
        <?php if ($bookingStatusText === 'In Progress' || $bookingStatusText === 'Completed'): ?>
            <?php
            // For both In Progress and Completed, make status clickable if there's tracking data
            if ($bookingStatusText === 'In Progress' || ($bookingStatusText === 'Completed' && ($booking->provider_id ?? false))): 
                $trackingUrl = base_url('trackings?booking_id=' . $booking->id . '&service_id=' . $booking->service_id . '&provider_id=' . ($booking->provider_id ?? ''));
            ?>
                <a href="<?= $trackingUrl ?>" class="text-decoration-none">
                    <span class="status-badge <?= $statusClass ?>"><?= esc($bookingStatusText) ?></span>
                </a>
            <?php else: ?>
                <span class="status-badge <?= $statusClass ?>"><?= esc($bookingStatusText) ?></span>
            <?php endif; ?>
        <?php else: ?>
            <span class="status-badge <?= $statusClass ?>"><?= esc($bookingStatusText) ?></span>
        <?php endif; ?>
    </div>
</td>
                                    <?php else: ?>
                                        <td class="text-center align-middle">
                                            <?php if (($booking->totalQuotes ?? 0) > 0): ?>
                                                <div class="text-center"><a
                                                        href="<?= base_url('bookings/walkingQuotes/' . $booking->id) ?>"
                                                        class="fw-bold"><?= $booking->totalQuotes ?></a></div>
                                            <?php else: ?>
                                                <div class="text-center">0</div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center align-middle"><?= esc($booking->service_start_date ?? '--') ?></td>
                                        <td class="text-center align-middle">
                                            <div class="text-center"><input type="text" class="form-control datepicker text-center"
                                                    data-booking-id="<?= $booking->id ?>"
                                                    data-service-id="<?= $booking->service_id ?>" placeholder="Select date"></div>
                                        </td>
                                    <?php endif; ?>

                                    <td class="text-center align-middle">
                                        <div class="d-flex justify-content-center gap-1">
                                            <?php if ($status !== 'deleted' && $status !== 'completed'): ?>
                                                <button class="btn btn-sm btn-outline-primary nearby-providers-btn"
                                                    data-booking-id="<?= $booking->id ?>"
                                                    data-customer-name="<?= esc($booking->user_name ?? 'Customer') ?>"
                                                    data-target-price="₹<?= esc($booking->price ?? $booking->bid_amount ?? $booking->total_price ?? '0') ?>">Nearby</button>
                                            <?php endif; ?>

                                            <?php if ($status === 'Confirmed' && ($booking->totalQuotes ?? 0) > 0): ?>
                                                <a href="<?= base_url('bookings/walkingQuotes/' . $booking->id) ?>"
                                                    class="btn btn-sm btn-info">Quotes</a>
                                            <?php endif; ?>

                                            <?php if ($status === 'completed'): ?>
                                                <?php
                                                // For completed services, add Walk History button with tracking link
                                                $trackingUrl = base_url('trackings?booking_id=' . $booking->id . '&service_id=' . $booking->service_id . '&provider_id=' . ($booking->provider_id ?? ''));
                                                ?>
                                                <a href="<?= $trackingUrl ?>" class="btn btn-sm btn-success"
                                                    title="View walk history for this completed service">
                                                    <i class="bi bi-clock-history"></i> Walk History
=======
                                        <td class="align-middle text-center">
                                            <span class="status-pill status-deleted">
                                                <span class="status-dot"></span>
                                                Deleted
                                            </span>
                                        </td>
                                        <td class="align-middle text-center small text-muted"><?= esc($booking->deleted_at ?? '--') ?></td>
                                        <td class="align-middle text-center">
                                            <?= buildCommentSelect($booking->comment ?? '', $booking->id, $booking->service_id) ?>
                                        </td>
                                        <td class="align-middle text-center">
                                            <input type="text" class="form-control form-control-sm datepicker text-center"
                                                data-booking-id="<?= $booking->id ?>"
                                                data-service-id="<?= $booking->service_id ?>" placeholder="Select date" style="max-width: 120px; margin: 0 auto;">
                                        </td>
                                    <?php elseif ($status === 'cancelled'): ?>
                                        <td class="align-middle">
                                            <div class="fw-medium text-dark" style="font-size: 13px;"><?= esc($booking->provider_name ?? '--') ?></div>
                                            <?php if (!empty($booking->provider_phone)): ?>
                                                <div class="text-muted small mt-0.5" style="font-size: 11px;"><?= esc($booking->provider_phone) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="status-pill status-cancelled">
                                                <span class="status-dot"></span>
                                                Cancelled
                                            </span>
                                        </td>
                                        <td class="align-middle small text-muted"><?= esc($booking->cancel_reason ?? '--') ?></td>
                                        <td class="align-middle text-center">
                                            <?= buildCommentSelect($booking->comment ?? '', $booking->id, $booking->service_id) ?>
                                        </td>
                                        <td class="align-middle text-center">
                                            <input type="text" class="form-control form-control-sm datepicker text-center"
                                                data-booking-id="<?= $booking->id ?>"
                                                data-service-id="<?= $booking->service_id ?>" placeholder="Select date" style="max-width: 120px; margin: 0 auto;">
                                        </td>
                                    <?php elseif ($status !== 'halted'): ?>
                                        <!-- Service Date Column -->
                                        <td class="align-middle text-center">
                                            <div class="d-flex flex-column align-items-center gap-0.5">
                                                <div class="text-dark fw-semibold" style="font-size: 12.5px;">
                                                    <i class="bi bi-calendar3 me-1 text-primary" style="font-size: 11px;"></i>
                                                    <?= (!empty($booking->service_start_date) && $booking->service_start_date !== '0000-00-00') ? date('d M Y', strtotime($booking->service_start_date)) : '--' ?>
                                                </div>
                                                <?php if (!empty($booking->service_end_date) && $booking->service_end_date !== '0000-00-00' && $booking->service_end_date !== $booking->service_start_date): ?>
                                                    <div class="text-muted" style="font-size: 11px;">
                                                        to <?= date('d M Y', strtotime($booking->service_end_date)) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>

                                        <!-- Created Date Column -->
                                        <td class="align-middle text-center">
                                            <?php
                                            $hasCreatedAt = !empty($booking->created_at) && $booking->created_at !== '0000-00-00 00:00:00';
                                            $isAlreadyIst = isset($booking->created_at_utc);
                                            $displayDate = '--';
                                            $displayTime = '';
                                            if ($hasCreatedAt) {
                                                $displayDate = $isAlreadyIst ? date('d M Y', strtotime($booking->created_at)) : utcToIst($booking->created_at, 'd M Y');
                                                $displayTime = $isAlreadyIst ? date('h:i A', strtotime($booking->created_at)) : utcToIst($booking->created_at, 'h:i A');
                                            }
                                            ?>
                                            <div class="fw-medium text-dark" style="font-size: 12.5px;">
                                                <i class="bi bi-clock-history me-1 text-muted" style="font-size: 11px;"></i>
                                                <?= $displayDate ?>
                                            </div>
                                            <?php if (!empty($displayTime)): ?>
                                                <div class="text-muted" style="font-size: 11px;">
                                                    <?= $displayTime ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>

                                        <?php if (in_array(strtolower($status ?? ''), ['in_progress', 'confirmed', 'completed'])): ?>
                                            <!-- Confirmed Date Column -->
                                            <td class="align-middle text-center">
                                                <?php
                                                $hasConfirmedAt = !empty($booking->confirmed_at) && $booking->confirmed_at !== '0000-00-00 00:00:00';
                                                $displayConfirmedDate = '--';
                                                $displayConfirmedTime = '';
                                                if ($hasConfirmedAt) {
                                                    $displayConfirmedDate = date('d M Y', strtotime($booking->confirmed_at));
                                                    $displayConfirmedTime = date('h:i A', strtotime($booking->confirmed_at));
                                                }
                                                ?>
                                                <div class="fw-medium text-dark" style="font-size: 12.5px;">
                                                    <i class="bi bi-check-circle-fill me-1 text-success" style="font-size: 11px;"></i>
                                                    <?= $displayConfirmedDate ?>
                                                </div>
                                                <?php if (!empty($displayConfirmedTime)): ?>
                                                    <div class="text-muted" style="font-size: 11px;">
                                                        <?= $displayConfirmedTime ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>

                                        <!-- Provider Column -->
                                        <td class="align-middle">
                                            <div class="fw-medium text-dark" style="font-size: 13px;">
                                                <?php if (($booking->totalQuotes ?? 0) > 0): ?>
                                                    <a href="<?= base_url('bookings/walkingQuotes/' . $booking->id) ?>" class="text-decoration-none fw-bold" style="color: #4338ca;">
                                                        <?= esc($booking->provider_name ?? '--') ?>
                                                    </a>
                                                <?php else: ?>
                                                    <?= esc($booking->provider_name ?? '--') ?>
                                                <?php endif; ?>
                                            </div>
                                            <?php if (!empty($booking->provider_phone)): ?>
                                                <div class="d-flex align-items-center text-muted small mt-1 gap-1" style="font-size: 11.5px;">
                                                    <i class="bi bi-telephone text-secondary"></i>
                                                    <a href="tel:<?= esc($booking->provider_phone) ?>" class="text-decoration-none text-muted"><?= esc($booking->provider_phone) ?></a>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="align-middle text-center">
                                            <?php
                                            $bookingStatusText = $booking->status ?? '';
                                            if (ucfirst(strtolower($bookingStatusText)) === 'Confirmed') $bookingStatusText = 'In Progress';
                                            $stClass = strtolower(str_replace(' ', '', $bookingStatusText));
                                            $trackingUrl = base_url('trackings?booking_id=' . $booking->id . '&service_id=' . $booking->service_id . '&provider_id=' . ($booking->provider_id ?? ''));
                                            ?>
                                            <?php if ($bookingStatusText === 'In Progress' || ($bookingStatusText === 'Completed' && ($booking->provider_id ?? false))): ?>
                                                <a href="<?= $trackingUrl ?>" class="text-decoration-none" title="Live Tracking">
                                                    <span class="status-pill status-active">
                                                        <span class="status-dot"></span>
                                                        <?= esc($bookingStatusText) ?>
                                                    </span>
                                                </a>
                                            <?php else: ?>
                                                <span class="status-pill status-<?= $stClass ?>">
                                                    <span class="status-dot"></span>
                                                    <?= esc($bookingStatusText) ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    <?php else: ?>
                                        <td class="align-middle text-center">
                                            <span class="status-pill status-halted">
                                                <span class="status-dot"></span>
                                                Halted
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <?php if (($booking->totalQuotes ?? 0) > 0): ?>
                                                <a href="<?= base_url('bookings/walkingQuotes/' . $booking->id) ?>" class="fw-bold" style="color: #4338ca;">
                                                    <?= $booking->totalQuotes ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">0</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="align-middle text-center small text-muted"><?= esc($booking->service_start_date ?? '--') ?></td>
                                        <td class="align-middle text-center">
                                            <input type="text" class="form-control form-control-sm datepicker text-center"
                                                data-booking-id="<?= $booking->id ?>"
                                                data-service-id="<?= $booking->service_id ?>" placeholder="Select date" style="max-width: 120px; margin: 0 auto;">
                                        </td>
                                    <?php endif; ?>

                                    <!-- Actions Column -->
                                    <td class="align-middle text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-1">
                                            <button type="button" class="nl-action-btn view booking-details-btn show-booking-details"
                                                title="View Details"
                                                data-booking-id="<?= $booking->id ?>"
                                                data-booking="<?= htmlspecialchars(json_encode($booking), ENT_QUOTES, 'UTF-8') ?>"
                                                onclick="event.stopPropagation(); showBookingDetailsFromElem(this);">
                                                <i class="bi bi-eye"></i>
                                            </button>

                                            <?php if ($status !== 'deleted' && $status !== 'completed'): ?>
                                                <button type="button" class="nl-btn-nearby nearby-providers-btn view-providers-btn"
                                                    data-booking-id="<?= $booking->id ?>"
                                                    data-customer-name="<?= esc($booking->user_name ?? 'Customer') ?>"
                                                    data-target-price="₹<?= esc(number_format((float)($booking->price ?? $booking->bid_amount ?? $booking->total_price ?? 0))) ?>"
                                                    onclick="event.stopPropagation(); showNearbyProviders('<?= $booking->id ?>', '<?= addslashes(esc($booking->user_name ?? 'Customer')) ?>', '₹<?= esc(number_format((float)($booking->price ?? $booking->bid_amount ?? $booking->total_price ?? 0))) ?>')"
                                                    title="Nearby Providers">
                                                    <i class="bi bi-geo-alt-fill me-1"></i>Nearby
                                                </button>
                                            <?php endif; ?>

                                            <?php if ($status === 'Confirmed' && ($booking->totalQuotes ?? 0) > 0): ?>
                                                <a href="<?= base_url('bookings/walkingQuotes/' . $booking->id) ?>" class="nl-action-btn view" title="View Quotes">
                                                    <i class="bi bi-chat-quote"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if ($status === 'completed' || $status === 'cancelled'): ?>
                                                <?php
                                                $trackingUrl = base_url('trackings?booking_id=' . $booking->id . '&service_id=' . $booking->service_id . '&provider_id=' . ($booking->provider_id ?? ''));
                                                ?>
                                                <a href="<?= $trackingUrl ?>" class="nl-action-btn view" style="color: #16a34a;" title="Walk History">
                                                    <i class="bi bi-clock-history"></i>
>>>>>>> origin/main
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
<<<<<<< HEAD
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center py-5 text-muted">No bookings found</td>
=======
                            <?php
                                $i++;
                            endforeach;
                            ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?= in_array(strtolower($status ?? ''), ['in_progress', 'confirmed', 'completed', 'cancelled']) ? '11' : '10' ?>" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                                    No bookings found
                                </td>
>>>>>>> origin/main
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

<<<<<<< HEAD
            <!-- Updated Pagination Section with Entries Per Page -->
            <div class="card-footer bg-white border-top-0 d-flex  justify-content-end align-items-center p-3">

                <div class="btn-group mt-0 pt-0 border-0" id="paginationControls">
                    <button class="btn bg-primary text-white btn-sm" id="prevPage" disabled>
                        <i class="bi bi-chevron-left"></i> Previous
                    </button>
                    <div class="" id="pageNumbers" style="gap: 2px;">
                    </div>
                    <button class="btn bg-primary text-white btn-sm" id="nextPage">
                        Next <i class="bi bi-chevron-right"></i>
=======
            <!-- Modern Table Footer & Pagination -->
            <div class="d-flex flex-wrap justify-content-between align-items-center px-4 py-3 border-top" style="background: #fafafa;">
                <div class="text-muted small fw-medium" id="entriesInfo">
                    Showing 1 to <?= min(10, $totalBookingsInView) ?> of <?= $totalBookingsInView ?> entries
                </div>
                <div class="d-flex align-items-center gap-1" id="paginationControls">
                    <button class="nl-page-btn nav-btn" id="prevPage" disabled>
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <div class="d-flex align-items-center gap-1" id="pageNumbers"></div>
                    <button class="nl-page-btn nav-btn" id="nextPage">
                        <i class="bi bi-chevron-right"></i>
>>>>>>> origin/main
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Nearby Providers Modal -->
    <div class="modal fade overflow-hidden" id="nearbyProvidersModal" tabindex="-1"
        aria-labelledby="nearbyProvidersModalLabel" aria-hidden="true">
<<<<<<< HEAD
        <div class="modal-dialog modal-lg">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="nearbyProvidersModalLabel">Nearby Service Providers</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="nearbyProvidersBody">
                    <!-- Dynamic providers content will be injected here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
=======
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden p-0">
                <div class="modal-header px-4 py-3 border-0 text-white" style="background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 25%, #3730a3 60%, #4f46e5 100%) !important;">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(255, 255, 255, 0.15);">
                            <i class="bi bi-radar text-warning fs-5"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-white mb-0" id="nearbyProvidersModalLabel">Nearby Service Providers</h5>
                            <span class="text-white-50 tiny">Smart dispatch & instant push notifications</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white opacity-75 hover-opacity-100" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" id="nearbyProvidersBody">
                    <!-- Dynamic providers content will be injected here -->
                </div>
                <div class="modal-footer border-top bg-light px-4 py-2.5">
                    <button type="button" class="btn btn-sm btn-dark rounded-pill px-4 fw-semibold" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Notifications Modal (Halted Bookings) -->
    <div class="modal fade overflow-hidden" id="bulkNotificationModal" tabindex="-1" aria-labelledby="bulkNotificationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
                <div class="modal-header px-4 py-3 border-0 text-white" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4338ca 100%) !important;">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: rgba(255, 255, 255, 0.15);">
                            <i class="bi bi-broadcast text-warning fs-5"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-white mb-0" id="bulkNotificationModalLabel">Broadcast Notification to Halted Bookings</h5>
                            <span class="text-white-50 tiny">Send push notifications to clients / pet parents to reactivate their bookings</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white opacity-75 hover-opacity-100" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4 bg-light">
                    <!-- Selected Bookings Summary Card -->
                    <div class="p-3 bg-white rounded-3 border mb-3 shadow-xs d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge rounded-pill px-3 py-2 fw-bold fs-6" style="background: #4338ca; color: #ffffff;">
                                <i class="bi bi-check2-square me-1"></i><span id="bulkModalSelectedCount">0</span> Bookings Selected
                            </span>
                            <span class="text-muted small fw-semibold">from Halted list</span>
                        </div>
                        <div class="small text-muted font-monospace" id="bulkModalBookingsSummary">
                            No bookings selected
                        </div>
                    </div>

                    <form id="bulkNotificationForm">
                        <!-- Target Recipient: Defaulted to Clients / Pet Parents -->
                        <input type="hidden" name="targetAudience" id="targetAudience" value="clients">
                        <div class="p-2.5 px-3 bg-white rounded-3 border mb-3 shadow-xs d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 small fw-bold">
                                    <i class="bi bi-person-heart me-1"></i>Clients / Pet Parents
                                </span>
                                <span class="text-muted tiny">Notification will be sent directly to registered pet parent devices.</span>
                            </div>
                        </div>

                        <!-- Notification Title -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small mb-1" for="bulkNotifTitle">Notification Title</label>
                            <input type="text" class="form-control rounded-3" id="bulkNotifTitle" value="Reactivate Booking - Petsfolio" placeholder="Enter notification title..." maxlength="100">
                            <div class="tiny text-muted mt-1">Displayed as the alert heading on device screens.</div>
                        </div>

                        <!-- Notification Message / Body -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small mb-1" for="bulkNotifMessage">Notification Body</label>
                            <textarea class="form-control rounded-3" id="bulkNotifMessage" rows="3" placeholder="Enter message body here..." maxlength="300">A service request in your area is currently awaiting confirmation. Open the Petsfolio app to reactivate your booking and take action now.</textarea>
                            <div class="tiny text-muted mt-1">Main notification text message delivered to selected users.</div>
                        </div>

                        <!-- Quick Templates -->
                        <div class="mb-2">
                            <label class="form-label fw-bold text-dark tiny mb-1">Quick Message Templates:</label>
                            <div class="d-flex flex-wrap gap-1.5">
                                <button type="button" class="btn btn-sm btn-primary rounded-pill py-0.5 px-2.5 tiny fw-bold" onclick="applyBulkTemplate('reactivate')">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Reactivate Booking
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-success rounded-pill py-0.5 px-2.5 tiny" onclick="applyBulkTemplate('client_update')">
                                    <i class="bi bi-person-check me-1"></i>Customer Update
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-0.5 px-2.5 tiny" onclick="applyBulkTemplate('awaiting')">
                                    <i class="bi bi-bell me-1"></i>Awaiting Confirmation
                                </button>
                                <button type="button" class="btn btn-sm btn-link text-muted py-0 px-2 tiny text-decoration-none" onclick="applyBulkTemplate('clear')">
                                    Clear
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer border-top bg-white px-4 py-3 d-flex justify-content-between">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-semibold text-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm d-inline-flex align-items-center gap-2" id="btnSendBulkNotificationsSubmit" onclick="submitBulkNotifications()">
                        <i class="bi bi-send-fill"></i>
                        <span>Send Notifications Now</span>
                    </button>
>>>>>>> origin/main
                </div>
            </div>
        </div>
    </div>

    <?php echo $this->include('admin/footer') ?>
</main>

<style>
<<<<<<< HEAD
    .table thead th {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding-top: 1rem;
        padding-bottom: 1rem;
        border: none !important;
=======
    .swal2-container {
        z-index: 99999 !important;
    }

    #nearbyProvidersModal .modal-dialog {
        max-height: 90vh;
    }

    #nearbyProvidersModal .modal-content {
        max-height: 90vh;
        display: flex;
        flex-direction: column;
    }

    #nearbyProvidersModal .modal-body {
        overflow-y: auto !important;
        max-height: calc(90vh - 130px);
    }

    #nearbyProvidersModal .modal-body::-webkit-scrollbar {
        width: 6px;
    }

    #nearbyProvidersModal .modal-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    #nearbyProvidersModal #providerCardsContainer {
        overflow-y: visible !important;
    }

    /* Bulk Notification Modal Audience Cards */
    .audience-card {
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 12px !important;
        transition: all 0.2s ease-in-out;
        position: relative;
        background: #ffffff !important;
    }

    .audience-card:hover {
        border-color: #6366f1 !important;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.08);
        transform: translateY(-1px);
    }

    .audience-card:has(input[type="radio"]:checked) {
        border-color: #4f46e5 !important;
        background: #f5f3ff !important;
        box-shadow: 0 0 0 1px #4f46e5, 0 4px 12px rgba(79, 70, 229, 0.1) !important;
    }

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
    }

    .table thead th {
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
        padding-top: 0.6rem;
        padding-bottom: 0.6rem;
        border: none !important;
        white-space: nowrap;
>>>>>>> origin/main
    }

    .table.dataTable {
        margin-top: 0px !important;
        margin-bottom: 0px !important;
    }

    .table td {
        border: none !important;
        border-bottom: 1px solid #e5e7eb !important;
<<<<<<< HEAD
        padding: 0.75rem !important;
=======
        padding: 0.5rem !important;
        font-size: 0.85rem;
>>>>>>> origin/main
    }

    .table tbody tr:last-child td {
        border-bottom: none !important;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
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

    .service-badge {
        display: inline-block;
        border-radius: 50rem;
        font-weight: 600;
        font-size: 0.75rem;
        text-align: center;
        transition: all 0.3s ease;
        border: none !important;
    }


    /* Pet Breed Badge Styles */
    .breed-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.75rem;
        margin: 0.1rem;
        border: 1px solid transparent;
        transition: all 0.2s ease;
    }

    .breed-badge:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .breed-golden {
        background: linear-gradient(135deg, #fbbf24, #f59e0b) !important;
        color: #92400e !important;
        border-color: rgba(245, 158, 11, 0.3) !important;
    }

    .breed-labrador {
        background: linear-gradient(135deg, #fcd34d, #fbbf24) !important;
        color: #92400e !important;
        border-color: rgba(251, 191, 36, 0.3) !important;
    }

    .breed-german {
        background: linear-gradient(135deg, #6b7280, #4b5563) !important;
        color: white !important;
        border-color: rgba(75, 85, 99, 0.3) !important;
    }

    .breed-poodle {
        background: linear-gradient(135deg, #d1d5db, #9ca3af) !important;
        color: #1f2937 !important;
        border-color: rgba(156, 163, 175, 0.3) !important;
    }

    .breed-beagle {
        background: linear-gradient(135deg, #fca5a5, #f87171) !important;
        color: #991b1b !important;
        border-color: rgba(248, 113, 113, 0.3) !important;
    }

    .breed-bulldog {
        background: linear-gradient(135deg, #94a3b8, #64748b) !important;
        color: white !important;
        border-color: rgba(100, 116, 139, 0.3) !important;
    }

    .breed-siberian {
        background: linear-gradient(135deg, #c7d2fe, #a5b4fc) !important;
        color: #3730a3 !important;
        border-color: rgba(165, 180, 252, 0.3) !important;
    }

    .breed-pug {
        background: linear-gradient(135deg, #f9a8d4, #f472b6) !important;
        color: #9d174d !important;
        border-color: rgba(244, 114, 182, 0.3) !important;
    }

    .breed-default {
        background-color: rgba(59, 130, 246, 0.1) !important;
        color: #1d4ed8 !important;
        border: 1px solid rgba(59, 130, 246, 0.2) !important;
    }

    /* Price Styling */
    .price-text {
        font-weight: 700;
        letter-spacing: -0.5px;
        font-size: 1rem;
    }

    /* Address tooltip styling */
    .address-tooltip {
        cursor: pointer;
        text-decoration: underline dotted;
        color: #6b7280;
    }

    .address-tooltip:hover {
        color: #374151;
    }

    /* Provider card custom styles */
    .provider-card {
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }

    .provider-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
        border-color: #0d6efd !important;
    }

    /* Responsive table adjustments */
    @media (max-width: 768px) {

        /* Hide pet column on mobile */
        #bookingsTable th:nth-child(2),
        #bookingsTable td:nth-child(2) {
            display: none !important;
        }

        /* Adjust action buttons on mobile */
        .nearby-providers-btn {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        /* Smaller service badges on mobile */
        .service-badge {
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
        }

        /* Smaller breed badges on mobile */
        .breed-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
        }
    }

    /* DataTables custom styling */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        display: none !important;
    }

    .dataTables_wrapper .dataTables_info {
        padding: 1rem;
        color: #6c757d;
        font-size: 0.875rem;
    }

    .dataTables_wrapper .dataTables_paginate {
        padding: 1rem;
    }

    /* Status badge styling */
    .status-badge {
        font-weight: 500;
        font-size: 0.7rem;
        padding: 0.25rem 0.75rem;
        border-radius: 50rem;
    }

    .status-pending {
        background-color: rgba(245, 158, 11, 0.1) !important;
        color: #d97706 !important;
        border: 1px solid rgba(245, 158, 11, 0.2) !important;
    }

    .status-active {
        background-color: rgba(16, 185, 129, 0.1) !important;
        color: #059669 !important;
        border: 1px solid rgba(16, 185, 129, 0.2) !important;
    }

    .status-completed {
        background-color: rgba(59, 130, 246, 0.1) !important;
        color: #1d4ed8 !important;
        border: 1px solid rgba(59, 130, 246, 0.2) !important;
    }

    .status-cancelled {
        background-color: rgba(239, 68, 68, 0.1) !important;
        color: #dc2626 !important;
        border: 1px solid rgba(239, 68, 68, 0.2) !important;
    }

    .status-deleted {
        background-color: rgba(107, 114, 128, 0.1) !important;
        color: #4b5563 !important;
        border: 1px solid rgba(107, 114, 128, 0.2) !important;
    }

    .status-halted {
        background-color: rgba(245, 158, 11, 0.1) !important;
        color: #d97706 !important;
        border: 1px solid rgba(245, 158, 11, 0.2) !important;
    }

    .status-confirmed {
        background-color: rgba(16, 185, 129, 0.1) !important;
        color: #059669 !important;
        border: 1px solid rgba(16, 185, 129, 0.2) !important;
    }

    .status-inprogress {
        background-color: rgba(59, 130, 246, 0.1) !important;
        color: #1d4ed8 !important;
        border: 1px solid rgba(59, 130, 246, 0.2) !important;
    }
</style>

<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>

<script>
    let modalStack = [];

    function closeCurrentModal() {
        if (modalStack.length === 0) return;
        const modalId = modalStack.pop();
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.removeEventListener('keydown', handleModalKeyDown);
            modal.removeEventListener('click', handleModalClick);
            modal.remove();
        }
    }

    function closeAllModals() {
        while (modalStack.length > 0) {
            closeCurrentModal();
        }
    }

    function handleModalKeyDown(e) {
        if (e.key === 'Escape') {
            closeCurrentModal();
            return;
        }

        if (e.key === 'Tab') {
            const modal = e.currentTarget;
            const modalContent = modal.querySelector('.modal-content');
            const focusable = modalContent.querySelectorAll(
                'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );

            if (focusable.length === 0) return;

            const first = focusable[0];
            const last = focusable[focusable.length - 1];

            if (e.shiftKey && document.activeElement === first) {
                last.focus();
                e.preventDefault();
            } else if (!e.shiftKey && document.activeElement === last) {
                first.focus();
                e.preventDefault();
            }
        }
    }

    function handleModalClick(e) {
        const modal = e.currentTarget;
        if (e.target.classList.contains('close-button') ||
            e.target.classList.contains('btn-secondary') ||
            e.target === modal) {
            closeCurrentModal();
        }
    }

    function escapeHtml(unsafe) {
        if (unsafe === null || unsafe === undefined) return '--';
        return String(unsafe)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Helper function to get service class
    function getServiceClass(serviceName) {
        const serviceNameLower = (serviceName || '').toLowerCase();

        if (serviceNameLower.includes('boarding')) return 'service-boarding';
        if (serviceNameLower.includes('walking')) return 'service-walking';
        if (serviceNameLower.includes('grooming')) return 'service-grooming';
        if (serviceNameLower.includes('sitting')) return 'service-sitting';
        if (serviceNameLower.includes('training')) return 'service-training';
        if (serviceNameLower.includes('vet')) return 'service-vet';
        if (serviceNameLower.includes('daycare')) return 'service-daycare';
        if (serviceNameLower.includes('taxi')) return 'service-taxi';
        if (serviceNameLower.includes('spa')) return 'service-spa';

        return 'service-default';
    }

    // Helper function to get breed class
    function getBreedClass(breedName) {
        const breedNameLower = (breedName || '').toLowerCase().replace(/\s+/g, '');

        if (breedNameLower.includes('golden')) return 'breed-golden';
        if (breedNameLower.includes('labrador')) return 'breed-labrador';
        if (breedNameLower.includes('german')) return 'breed-german';
        if (breedNameLower.includes('poodle')) return 'breed-poodle';
        if (breedNameLower.includes('beagle')) return 'breed-beagle';
        if (breedNameLower.includes('bulldog')) return 'breed-bulldog';
        if (breedNameLower.includes('siberian')) return 'breed-siberian';
        if (breedNameLower.includes('pug')) return 'breed-pug';

        return 'breed-default';
    }

    // Helper function to get status class
    function getStatusClass(status) {
        const statusLower = (status || '').toLowerCase();

        if (statusLower.includes('pending')) return 'status-pending';
        if (statusLower.includes('active')) return 'status-active';
        if (statusLower.includes('completed')) return 'status-completed';
        if (statusLower.includes('cancelled')) return 'status-cancelled';
        if (statusLower.includes('deleted')) return 'status-deleted';
        if (statusLower.includes('halted')) return 'status-halted';
        if (statusLower.includes('confirmed')) return 'status-confirmed';
        if (statusLower.includes('progress')) return 'status-inprogress';

        return 'status-pending';
    }

    function getStatusColor(status) {
        const colors = {
            'Cancelled': '#ef4444',
<<<<<<< HEAD
            'Completed': '#10b981',
            'In Progress': '#f59e0b',
            'Confirmed': '#f59e0b',
            'Pending': '#6b7280',
=======
            'Completed': '#016544ff',
            'In Progress': '#f59e0b',
            'Confirmed': '#f59e0b',
            'Pending': '#6b7280',
            'New': '#000000',
>>>>>>> origin/main
            'default': '#6b7280'
        };
        return colors[status] || colors.default;
    }

    function formatServiceDays(days) {
        if (!days || days === '--') return '--';
        if (days === 'all days') return 'Everyday';
        if (typeof days === 'string') {
            return days.split(',').map(day =>
                day.trim().charAt(0).toUpperCase() + day.trim().slice(1)
            ).join(', ');
        }
        return days;
    }

    function formatPreferableTime(time) {
        if (!time || time === '--') return '--';
        if (typeof time === 'string') {
            return time.split(',').map(t => t.trim()).join(', ');
        }
        return time;
    }

    // Pagination Manager Class
    class PaginationManager {
        constructor() {
            this.currentPage = 1;
            this.entriesPerPage = 10;
            this.totalEntries = <?= count($bookings ?? []) ?>;
            this.tableBody = document.getElementById('tableBody');
            this.allRows = Array.from(document.querySelectorAll('.booking-row'));
            this.filteredRows = this.allRows;
            this.init();
        }

        init() {
            this.setupEventListeners();
            this.updateDisplay();
        }

        setupEventListeners() {
            // Entries per page change
            document.getElementById('entriesPerPage').addEventListener('change', (e) => {
                const value = e.target.value;
                this.entriesPerPage = value === 'all' ? this.totalEntries : parseInt(value);
                this.currentPage = 1;
                this.updateDisplay();
            });

            // Previous page button
            document.getElementById('prevPage').addEventListener('click', () => {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.updateDisplay();
                }
            });

            // Next page button
            document.getElementById('nextPage').addEventListener('click', () => {
                const totalPages = this.getTotalPages();
                if (this.currentPage < totalPages) {
                    this.currentPage++;
                    this.updateDisplay();
                }
            });

            // Search input
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
<<<<<<< HEAD
                    this.searchBookings(e.target.value);
=======
                    this.applyCombinedFilters();
                });
            }

            // Service filter
            const serviceFilter = document.getElementById('serviceFilter');
            if (serviceFilter) {
                serviceFilter.addEventListener('change', () => {
                    this.applyCombinedFilters();
>>>>>>> origin/main
                });
            }
        }

        getTotalPages() {
            return Math.ceil(this.filteredRows.length / this.entriesPerPage);
        }

        updateDisplay() {
            const start = (this.currentPage - 1) * this.entriesPerPage;
            const end = Math.min(start + this.entriesPerPage, this.filteredRows.length);

            // Hide all rows first
            this.allRows.forEach(row => row.style.display = 'none');

            // Show only current page rows
            for (let i = start; i < end; i++) {
                if (this.filteredRows[i]) {
                    this.filteredRows[i].style.display = '';
                }
            }

            this.updatePaginationControls();
            this.updateEntriesInfo(start, end);
<<<<<<< HEAD
=======
            <?php if ($isHalted): ?>
                if (typeof updateHaltedSelectionCount === 'function') {
                    updateHaltedSelectionCount();
                }
            <?php endif; ?>
>>>>>>> origin/main
        }

        updatePaginationControls() {
            const totalPages = this.getTotalPages();
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');
            const pageNumbersContainer = document.getElementById('pageNumbers');

            // Update prev/next buttons
            prevBtn.disabled = this.currentPage === 1;
            nextBtn.disabled = this.currentPage === totalPages || totalPages === 0;

            // Generate page numbers
            let pageNumbersHtml = '';
            const maxVisiblePages = 5;

            if (totalPages <= maxVisiblePages) {
                // Show all pages
                for (let i = 1; i <= totalPages; i++) {
                    pageNumbersHtml += this.getPageButtonHtml(i);
                }
            } else {
                // Show limited pages with ellipsis
                if (this.currentPage <= 3) {
                    for (let i = 1; i <= 4; i++) {
                        pageNumbersHtml += this.getPageButtonHtml(i);
                    }
<<<<<<< HEAD
                    pageNumbersHtml += `<span class="btn btn-sm btn-outline-secondary border-0" disabled>...</span>`;
                    pageNumbersHtml += this.getPageButtonHtml(totalPages);
                } else if (this.currentPage >= totalPages - 2) {
                    pageNumbersHtml += this.getPageButtonHtml(1);
                    pageNumbersHtml += `<span class="btn btn-sm btn-outline-secondary border-0" disabled>...</span>`;
=======
                    pageNumbersHtml += `<span class="nl-page-btn disabled" disabled>...</span>`;
                    pageNumbersHtml += this.getPageButtonHtml(totalPages);
                } else if (this.currentPage >= totalPages - 2) {
                    pageNumbersHtml += this.getPageButtonHtml(1);
                    pageNumbersHtml += `<span class="nl-page-btn disabled" disabled>...</span>`;
>>>>>>> origin/main
                    for (let i = totalPages - 3; i <= totalPages; i++) {
                        pageNumbersHtml += this.getPageButtonHtml(i);
                    }
                } else {
                    pageNumbersHtml += this.getPageButtonHtml(1);
<<<<<<< HEAD
                    pageNumbersHtml += `<span class="btn btn-sm btn-outline-secondary border-0" disabled>...</span>`;
                    for (let i = this.currentPage - 1; i <= this.currentPage + 1; i++) {
                        pageNumbersHtml += this.getPageButtonHtml(i);
                    }
                    pageNumbersHtml += `<span class="btn btn-sm btn-outline-secondary border-0" disabled>...</span>`;
=======
                    pageNumbersHtml += `<span class="nl-page-btn disabled" disabled>...</span>`;
                    for (let i = this.currentPage - 1; i <= this.currentPage + 1; i++) {
                        pageNumbersHtml += this.getPageButtonHtml(i);
                    }
                    pageNumbersHtml += `<span class="nl-page-btn disabled" disabled>...</span>`;
>>>>>>> origin/main
                    pageNumbersHtml += this.getPageButtonHtml(totalPages);
                }
            }

            pageNumbersContainer.innerHTML = pageNumbersHtml;

            // Add click events to page buttons
            document.querySelectorAll('.page-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    this.currentPage = parseInt(e.target.dataset.page);
                    this.updateDisplay();
                });
            });
        }

        getPageButtonHtml(pageNum) {
<<<<<<< HEAD
            const activeClass = pageNum === this.currentPage ? 'btn-primary' : 'btn-outline-primary';
            return `<button class="btn btn-sm ${activeClass} page-btn" data-page="${pageNum}">${pageNum}</button>`;
        }

        updateEntriesInfo(start, end) {
            // const entriesInfo = document.getElementById('entriesInfo');
            // if (this.filteredRows.length === 0) {
            //     entriesInfo.textContent = 'Showing 0 entries';
            // } else {
            //     entriesInfo.textContent = `Showing ${start + 1} to ${end} of ${this.filteredRows.length} entries`;
            // }
        }

        searchBookings(query) {
            query = query.toLowerCase();
            this.filteredRows = this.allRows.filter(row => {
                return row.textContent.toLowerCase().includes(query);
=======
            const activeClass = pageNum === this.currentPage ? 'active' : '';
            return `<button class="nl-page-btn ${activeClass} page-btn" data-page="${pageNum}">${pageNum}</button>`;
        }

        updateEntriesInfo(start, end) {
            const entriesInfo = document.getElementById('entriesInfo');
            if (entriesInfo) {
                if (this.filteredRows.length === 0) {
                    entriesInfo.textContent = 'Showing 0 entries';
                } else {
                    entriesInfo.textContent = `Showing ${start + 1} to ${end} of ${this.filteredRows.length} entries`;
                }
            }
        }

        applyCombinedFilters() {
            const query = (document.getElementById('searchInput')?.value || '').toLowerCase().trim();
            const service = (document.getElementById('serviceFilter')?.value || 'all').toLowerCase().trim();

            this.filteredRows = this.allRows.filter(row => {
                const text = row.textContent.toLowerCase();
                const matchesQuery = !query || text.includes(query);
                const rowService = (row.dataset.service || '').toLowerCase();
                const matchesService = service === 'all' || rowService === service || rowService.includes(service);
                return matchesQuery && matchesService;
>>>>>>> origin/main
            });
            this.currentPage = 1;
            this.updateDisplay();
        }

<<<<<<< HEAD
=======
        searchBookings(query) {
            this.applyCombinedFilters();
        }

>>>>>>> origin/main
        refreshRows() {
            this.allRows = Array.from(document.querySelectorAll('.booking-row'));
            this.filteredRows = this.allRows;
            this.totalEntries = this.allRows.length;
            this.currentPage = 1;
            this.updateDisplay();
        }
    }

    // Function to show nearby providers with dynamic API data
<<<<<<< HEAD
    function showNearbyProviders(bookingId, customerName = 'Customer', targetPrice = '₹0') {
=======
    let _openingNearbyModalTimer = null;

    function showNearbyProviders(bookingId, customerName = 'Customer', targetPrice = '₹0') {
        if (_openingNearbyModalTimer) return;
        _openingNearbyModalTimer = setTimeout(() => {
            _openingNearbyModalTimer = null;
        }, 500);

>>>>>>> origin/main
        // Show loading state
        const modalBody = document.getElementById('nearbyProvidersBody');
        modalBody.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-dark">Loading providers...</p>
            </div>
        `;

        // Show modal first
        const modalEl = document.getElementById('nearbyProvidersModal');
        const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
        modalInstance.show();

        // Fetch providers data via API
        fetch(`<?= base_url('getNearbyProviders/') ?>${bookingId}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.providers) {
                    // Build modal content with API data
                    let providersHtml = `
                    <div class="p-4 d-flex flex-column h-100 overflow-hidden">
                        <div class="d-flex flex-column h-100">
<<<<<<< HEAD
                            <!-- Header -->
                            <div style="background: #c6d2ff !important;" class="p-3 rounded border d-flex flex-column flex-sm-row justify-content-between align-items-center mb-2 gap-2">
                                <div class="text-center text-sm-start">
                                    <span class="text-muted">Customer:</span>
                                    <span class="fw-semibold text-black ms-1">${customerName}</span>
                                </div>
                                <div class="text-center text-sm-end">
                                    <span class="text-muted">Target:</span>
                                    <span class="fw-bold text-black ms-1">${targetPrice}</span>
=======
                            <!-- Header / Target Summary -->
                            <div class="p-3 rounded-3 border d-flex flex-column flex-sm-row justify-content-between align-items-center mb-3 gap-2 shadow-xs" style="background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%); border-color: #e2e8f0 !important;">
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-size: 0.95rem;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div>
                                        <span class="tiny text-muted fw-bold text-uppercase tracking-wider d-block">Target Client</span>
                                        <span class="fw-bold text-dark fs-6">${customerName}</span>
                                    </div>
                                </div>
                                <div class="text-center text-sm-end">
                                    <span class="badge bg-white text-dark border px-3 py-1.5 rounded-pill shadow-xs">
                                        <span class="text-muted me-1">Target Price:</span>
                                        <strong class="text-success fs-6">${targetPrice}</strong>
                                    </span>
                                </div>
                            </div>

                            <!-- Notification & Selection Toolbar -->
                            <div class="d-flex flex-wrap justify-content-between align-items-center bg-light p-2.5 px-3 rounded-3 border mb-3 gap-2">
                                <div class="form-check d-flex align-items-center mb-0">
                                    <input class="form-check-input me-2" type="checkbox" id="selectAllNearbyProvidersBookings" onchange="toggleSelectAllNearbyBookings(this)" style="cursor: pointer; transform: scale(1.15);">
                                    <label class="form-check-label fw-bold text-dark small mb-0" for="selectAllNearbyProvidersBookings" style="cursor: pointer;">
                                        Select All (<span id="nearbySelectedCountBookings">0</span>/<span id="nearbyTotalCountBookings">${data.providers.length}</span>)
                                    </label>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-1.5 shadow-sm" id="btnSendNearbyNotificationsBookings" onclick="sendNearbyNotificationsBookings('${bookingId}')">
                                        <i class="fa-regular fa-paper-plane me-1"></i> Send Notifications (<span id="btnSelectedCountBookings">0</span>)
                                    </button>
>>>>>>> origin/main
                                </div>
                            </div>

                            <!-- Search -->
<<<<<<< HEAD
                            <div class="position-relative mb-4">
                                <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </span>
                                <input type="text" class="form-control ps-5" id="providerSearch" 
                                    placeholder="Search quotes by provider or notes..." 
                                    onkeyup="filterProviderCards()" />
                            </div>

                            <!-- Scroll Area -->
                            <div class="overflow-auto pe-2" style="max-height: 500px; scrollbar-width:none;" id="providerCardsContainer">
=======
                            <div class="position-relative mb-3 w-100">
                                <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </span>
                                <input type="text" class="form-control rounded-pill ps-5 py-2 w-100" id="providerSearch" 
                                    placeholder="Search providers by name, area, or notes..." 
                                    onkeyup="filterProviderCards()" style="font-size: 0.875rem;" />
                            </div>

                            <!-- Scroll Area -->
                            <div class="pe-1" id="providerCardsContainer">
>>>>>>> origin/main
                    `;

                    // Check if providers exist
                    if (data.providers.length > 0) {
                        // Add provider cards from API data
                        data.providers.forEach((provider, index) => {
                            // Get initials for avatar
                            const initials = provider.provider_name ?
                                provider.provider_name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2) :
                                'PR';

                            // Format rating
                            const rating = provider.rating || 0;
                            const ratingDisplay = rating > 0 ? rating.toFixed(1) : 'N/A';

                            // Format distance
                            const distance = provider.distance || 0;
<<<<<<< HEAD
                            const distanceDisplay = distance > 0 ? `${distance} km` : 'Nearby';

                            // Format verification status
                            const verificationStatus = provider.completionPercentage || 0;

                            // Area info
                            const area = provider.area || 'Unknown area';
=======
                            const distanceDisplay = distance > 0 ? `${parseFloat(distance).toFixed(1)} km` : 'Nearby';

                            // Format verification status
                            const verificationStatus = provider.completionPercentage || 0;
                            const verificationText = verificationStatus >= 80 ? 'Verified' :
                                verificationStatus >= 50 ? 'Partially Verified' : 'Not Verified';
                            const verificationStyle = verificationStatus >= 80 ? 'background: #ecfdf5 !important; color: #059669 !important; border: 1px solid #a7f3d0 !important;' :
                                verificationStatus >= 50 ? 'background: #fffbeb !important; color: #b45309 !important; border: 1px solid #fde68a !important;' :
                                'background: #f1f5f9 !important; color: #64748b !important; border: 1px solid #e2e8f0 !important;';

                            // Area info
                            const area = provider.area || 'Unknown area';
                            const hasToken = provider.device_token && provider.device_token !== 'default_token';
>>>>>>> origin/main

                            // Generate service description
                            let serviceDescription = `Service available in ${area}`;
                            if (distance > 0) {
                                serviceDescription += `, ${distanceDisplay} away`;
                            }
                            if (verificationStatus > 0) {
                                serviceDescription += `, ${verificationStatus.toFixed(0)}% profile complete`;
                            }

                            providersHtml += `
                                <!-- Provider Card ${index + 1} -->
<<<<<<< HEAD
                                <div class="card mb-3 shadow-sm provider-card border rounded p-1" style="border-color: #c6d2ff !important;"
                                    data-provider-name="${provider.provider_name || ''}" 
                                    data-provider-notes="${serviceDescription.toLowerCase()}">
                                    <div class="card-body p-2">
                                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2">
                                            <div class="d-flex gap-3">
                                                <div class="rounded-circle bg-primary bg-opacity-100 text-white fw-bold d-flex align-items-center justify-content-center"
                                                    style="width:40px;height:40px;">
                                                    ${initials}
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-1 text-dark">
                                                        ${provider.provider_name || 'Unknown Provider'}
                                                        <span class="badge bg-primary text-white border rounded-pill ms-1">⭐ ${ratingDisplay}</span>
                                                    </h6>
                                                    <small class="text-muted"><i class="fa-solid fa-phone"></i> ${provider.provider_phone || 'N/A'}</small>
                                                    <div class="mt-2">
                                                        <span class="badge bg-light text-dark border">${distanceDisplay}</span>
                                                        <span class="badge bg-primary text-white ms-1">${area}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background: #c6d2ff !important;" class="mt-3 p-2 border rounded fst-italic small text-muted">
                                            "${serviceDescription}"
=======
                                <div class="card mb-3 shadow-xs provider-card border rounded-3 p-3 position-relative" style="border-color: #e2e8f0 !important; cursor: pointer; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); background: #ffffff;"
                                    id="providerCard_b_${provider.provider_id}"
                                    onclick="handleNearbyCardClickBookings(event, '${provider.provider_id}')"
                                    data-provider-name="${provider.provider_name || ''}" 
                                    data-provider-notes="${serviceDescription.toLowerCase()}">
                                    <div class="card-body p-0">
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="pt-1" onclick="event.stopPropagation()">
                                                <input type="checkbox" class="form-check-input nearby-provider-checkbox-bookings" 
                                                    value="${provider.provider_id}" 
                                                    id="checkbox_provider_b_${provider.provider_id}"
                                                    data-token="${provider.device_token || ''}"
                                                    onchange="updateNearbySelectedCountBookings()" 
                                                    style="cursor: pointer; transform: scale(1.2);">
                                            </div>
                                            <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center flex-shrink-0 shadow-xs"
                                                style="width:44px;height:44px; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); font-size: 0.95rem;">
                                                ${initials}
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-1 mb-1">
                                                    <h6 class="fw-bold mb-0 text-dark fs-6 d-flex align-items-center gap-1.5">
                                                        ${provider.provider_name || 'Unknown Provider'}
                                                        <span class="badge rounded-pill" style="background:#fffbeb; color:#b45309; border: 1px solid #fde68a; font-size:0.75rem;">⭐ ${ratingDisplay}</span>
                                                    </h6>
                                                    <div>
                                                        ${hasToken ? '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2 py-1 tiny fw-semibold"><i class="bi bi-bell-fill me-1"></i>Push Ready</span>' : '<span class="badge bg-secondary bg-opacity-10 text-secondary border rounded-pill px-2 py-1 tiny"><i class="bi bi-bell-slash me-1"></i>No Token</span>'}
                                                    </div>
                                                </div>
                                                <div class="text-muted small"><i class="fa-solid fa-phone me-1 text-secondary"></i>${provider.provider_phone || 'N/A'}</div>
                                                <div class="mt-2 d-flex flex-wrap gap-1.5 align-items-center">
                                                    <span class="badge rounded-pill px-2.5 py-1 tiny fw-semibold" style="background: #f8fafc; color: #475569; border: 1px solid #e2e8f0;"><i class="bi bi-pin-map me-1 text-primary"></i>${distanceDisplay}</span>
                                                    <span class="badge rounded-pill px-2.5 py-1 tiny fw-semibold" style="background: #eef2ff !important; color: #4338ca !important; border: 1px solid #c7d2fe !important;"><i class="bi bi-geo-alt me-1"></i>${area}</span>
                                                    <span class="badge rounded-pill px-2.5 py-1 tiny fw-semibold" style="${verificationStyle}">${verificationText}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2.5 p-2 rounded-2 small text-muted border d-flex align-items-center gap-2" style="background: #f8fafc; border-color: #f1f5f9 !important; font-size: 0.8rem;">
                                            <i class="bi bi-info-circle text-primary"></i>
                                            <span>${serviceDescription}</span>
>>>>>>> origin/main
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        providersHtml += `
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="bi bi-emoji-frown display-1 text-muted"></i>
                                </div>
                                <h5 class="mb-3 text-dark">No nearby providers found</h5>
                                <p class="text-muted">No service providers are available near this location.</p>
                            </div>
                        `;
                    }

                    providersHtml += `
                            </div> <!-- End Scroll Area -->
                        </div>
                    </div>
                    `;

                    // Update modal content
                    modalBody.innerHTML = providersHtml;

                } else {
                    // Error or no data
                    modalBody.innerHTML = `
                    <div class="alert alert-danger m-3">
                        <i class="bi bi-exclamation-triangle"></i>
                        ${data.message || 'Failed to load providers data'}
                    </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error fetching providers:', error);
                modalBody.innerHTML = `
                <div class="alert alert-danger m-3">
                    <i class="bi bi-exclamation-triangle"></i>
                    Failed to load providers. Please try again.
                </div>
                `;
            });
    }

<<<<<<< HEAD
=======
    function handleNearbyCardClickBookings(event, providerId) {
        if (event.target.tagName === 'INPUT' || event.target.tagName === 'A' || event.target.tagName === 'BUTTON' || event.target.closest('.pt-1')) {
            return;
        }
        const checkbox = document.getElementById(`checkbox_provider_b_${providerId}`);
        if (checkbox) {
            checkbox.checked = !checkbox.checked;
            updateNearbySelectedCountBookings();
        }
    }

    function toggleSelectAllNearbyBookings(master) {
        const checkboxes = document.querySelectorAll('.nearby-provider-checkbox-bookings');
        checkboxes.forEach(cb => {
            const card = cb.closest('.provider-card');
            if (card && card.style.display !== 'none') {
                cb.checked = master.checked;
            }
        });
        updateNearbySelectedCountBookings();
    }

    function updateNearbySelectedCountBookings() {
        const checkboxes = document.querySelectorAll('.nearby-provider-checkbox-bookings');
        const checked = document.querySelectorAll('.nearby-provider-checkbox-bookings:checked');
        const count = checked.length;

        const countEl = document.getElementById('nearbySelectedCountBookings');
        if (countEl) countEl.innerText = count;

        const btnCountEl = document.getElementById('btnSelectedCountBookings');
        if (btnCountEl) btnCountEl.innerText = count;

        const master = document.getElementById('selectAllNearbyProvidersBookings');
        if (master) {
            master.indeterminate = false;
            master.checked = (checkboxes.length > 0 && count === checkboxes.length);
        }

        // Highlight selected cards
        checkboxes.forEach(cb => {
            const card = cb.closest('.provider-card');
            if (card) {
                if (cb.checked) {
                    card.classList.add('border-primary', 'shadow-sm');
                    card.style.backgroundColor = '#f4f6ff';
                } else {
                    card.classList.remove('border-primary', 'shadow-sm');
                    card.style.backgroundColor = '';
                }
            }
        });
    }

    function sendNearbyNotificationsBookings(bookingId) {
        const checked = document.querySelectorAll('.nearby-provider-checkbox-bookings:checked');
        if (checked.length === 0) {
            Swal.fire('No Providers Selected', 'Please select at least one provider to send notifications.', 'warning');
            return;
        }

        const providerIds = Array.from(checked).map(cb => cb.value);
        const queueOnlyCheckbox = document.getElementById('nearbyQueueOnlyBookings');
        const isQueue = queueOnlyCheckbox && queueOnlyCheckbox.checked ? 1 : 0;

        const btn = document.getElementById('btnSendNearbyNotificationsBookings');
        const originalBtnHtml = btn ? btn.innerHTML : '';
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> ${isQueue ? 'Queueing...' : 'Sending...'}`;
        }

        $.ajax({
            url: '<?= base_url("bookings/notifyNearbyProviders") ?>',
            method: 'POST',
            data: {
                booking_id: bookingId,
                provider_ids: providerIds,
                queue_only: isQueue
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(response) {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = originalBtnHtml;
                }
                if (response && response.status) {
                    Swal.fire({
                        icon: 'success',
                        title: response.is_queue ? 'Notifications Queued!' : 'Notifications Sent!',
                        text: response.message || 'Notifications processed successfully.',
                        confirmButtonColor: '#3085d6'
                    }).then(() => {
                        const modalEl = document.getElementById('nearbyProvidersModal');
                        if (modalEl) {
                            const modalInstance = bootstrap.Modal.getInstance(modalEl);
                            if (modalInstance) modalInstance.hide();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: (response && response.message) ? response.message : 'Failed to send notifications. Please try again.',
                        confirmButtonColor: '#d33'
                    });
                }
            },
            error: function(xhr, status, error) {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = originalBtnHtml;
                }
                console.error('Notification error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while sending notifications.',
                    confirmButtonColor: '#d33'
                });
            }
        });
    }

>>>>>>> origin/main
    // Function to filter provider cards based on search
    function filterProviderCards() {
        const searchTerm = document.getElementById('providerSearch').value.toLowerCase();
        const cards = document.querySelectorAll('.provider-card');

        cards.forEach(card => {
            const providerName = card.getAttribute('data-provider-name').toLowerCase();
            const providerNotes = card.getAttribute('data-provider-notes');

            if (providerName.includes(searchTerm) || providerNotes.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function getDaysLeftStyle(daysLeft, serviceType) {
        const serviceTypeLower = serviceType.toLowerCase();

        if (daysLeft <= 0) {
            // Expired - Red theme for all services
            return {
                bg: 'linear-gradient(135deg, #ef4444, #dc2626)',
                color: 'white',
                icon: 'bi-exclamation-triangle'
            };
        } else if (daysLeft <= 3) {
            // Urgent - Warning theme
            return {
                bg: 'linear-gradient(135deg, #fbbf24, #f59e0b)',
                color: '#92400e',
                icon: 'bi-clock-fill'
            };
        } else {
            // Normal - Service-specific themes
            if (serviceTypeLower.includes('boarding')) {
                return {
                    bg: 'linear-gradient(135deg, #dbeafe, #bfdbfe)',
                    color: '#1e40af',
                    icon: 'bi-house-door'
                };
            } else if (serviceTypeLower.includes('walking')) {
                return {
                    bg: 'linear-gradient(135deg, #d1fae5, #a7f3d0)',
                    color: '#065f46',
                    icon: 'bi-shuffle'
                };
            } else if (serviceTypeLower.includes('grooming')) {
                return {
                    bg: 'linear-gradient(135deg, #ede9fe, #ddd6fe)',
                    color: '#5b21b6',
                    icon: 'bi-scissors'
                };
            } else if (serviceTypeLower.includes('sitting')) {
                return {
                    bg: 'linear-gradient(135deg, #fef3c7, #fde68a)',
                    color: '#92400e',
                    icon: 'bi-person-badge'
                };
            } else if (serviceTypeLower.includes('training')) {
                return {
                    bg: 'linear-gradient(135deg, #fee2e2, #fecaca)',
                    color: '#991b1b',
                    icon: 'bi-award'
                };
            } else if (serviceTypeLower.includes('vet')) {
                return {
                    bg: 'linear-gradient(135deg, #fce7f3, #fbcfe8)',
                    color: '#9d174d',
                    icon: 'bi-heart-pulse'
                };
            } else if (serviceTypeLower.includes('daycare')) {
                return {
                    bg: 'linear-gradient(135deg, #cffafe, #a5f3fc)',
                    color: '#155e75',
                    icon: 'bi-sun'
                };
            } else if (serviceTypeLower.includes('taxi')) {
                return {
                    bg: 'linear-gradient(135deg, #f1f5f9, #e2e8f0)',
                    color: '#334155',
                    icon: 'bi-car-front'
                };
            } else if (serviceTypeLower.includes('spa')) {
                return {
                    bg: 'linear-gradient(135deg, #ffedd5, #fed7aa)',
                    color: '#9a3412',
                    icon: 'bi-flower1'
                };
            } else {
                return {
                    bg: 'linear-gradient(135deg, #e0e7ff, #c7d2fe)',
                    color: '#3730a3',
                    icon: 'bi-calendar-check'
                };
            }
        }
    }

<<<<<<< HEAD
    function showBookingDetails(booking) {

        console.log("booking", booking)
        // Format dates
        const startDate = booking.service_start_date ? new Date(booking.service_start_date).toLocaleDateString('en-IN', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        }) : '--';

        const endDate = booking.service_end_date ? new Date(booking.service_end_date).toLocaleDateString('en-IN', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        }) : null;

        // Format addons
        let addonsHtml = '';
        if (booking.addons && Array.isArray(booking.addons) && booking.addons.length > 0) {
            addonsHtml = `
                <div class="mb-3">
                    <h6 class="fw-bold mb-2 text-dark">Addons:</h6>
                    <div class="d-flex flex-wrap gap-2">
                        ${booking.addons.map(addon => `
                            <span class="badge bg-light text-dark border">${addon.addon}</span>
                        `).join('')}
                    </div>
                </div>
            `;
        }

        // Format packages
        let packagesHtml = '';
        if (booking.package_name) {
            packagesHtml = `
                <div class="">
                    <h6 class="fw-bold text-dark">Package:</h6>
                    <div class="">
                        <div class="card-body p-1">
                            <h6 class="fw-bold text-dark">${booking.package_name}</h6>
                            ${booking.total_price ? `<p class="mb-1 text-dark"><strong>Price:</strong> ₹${booking.total_price}</p>` : ''}
                        </div>
                    </div>
                </div>
            `;
        }

        // Format pet breeds
        let petBreedsHtml = '';
        if (booking.pet_breeds) {
            const breeds = Array.isArray(booking.pet_breeds) ? booking.pet_breeds : booking.pet_breeds.split(',');
            petBreedsHtml = `
                <div class="mb-3">
                    <h6 class="fw-bold mb-2 text-dark">Pet Breeds:</h6>
                    <div class="d-flex flex-wrap gap-2">
                        ${breeds.map(breed => `
                            <span class="badge text-primary border p-1 px-2 rounded" style="background: #c6d2ff !important; border: 1px solid #486ef8 !important;">${breed.trim()}</span>
                        `).join('')}
                    </div>
                </div>
            `;
        }

        // Format status badge
        const statusColor = getStatusColor(booking.status);
        const statusBadge = `<span class="badge bg-${statusColor}">${booking.status ? booking.status.charAt(0).toUpperCase() + booking.status.slice(1) : 'Unknown'}</span>`;

        // Format days left badge
        const daysLeft = booking.total_days || 0;
        const daysStyle = getDaysLeftStyle(daysLeft, booking.service_name || '');
        const daysBadge = `<span class="badge rounded-pill p-2 px-3 text-primary " style="font-size: 0.8rem; background: #c6d2ff !important; border: 1px solid #486ef8 !important; ">
            <i class="bi ${daysStyle.icon} me-1"></i>${daysLeft} days left
        </span>`;

        const modalId = 'booking-modal-' + Date.now();

        // Build modal content
        const modalContent = `
            <div class="modal srv_dtls" id="${modalId}" role="dialog" aria-modal="true" aria-labelledby="${modalId}-title" style="justify-content:center;">
                <div class="modal-content p-0" style="max-width: 800px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); margin: 20px; max-height: 90vh; overflow-y: auto;">
                    <!-- Header -->
                    <div class="offcanvas-header" style="background: #c6d2ff!important; border-radius: 12px 12px 0 0; padding: 20px 25px;">
                        <div class="d-flex align-items-center justify-content-between w-100">
                            <div>
                                <h5 class="offcanvas-title text-black fw-bold" id="${modalId}-title">
                                    <i class="bi bi-journal-text me-2"></i>Booking Details
                                </h5>
                                <p class="mb-0 small opacity-75">Complete booking information</p>
                            </div>
                            <button type="button" class="btn-close" aria-label="Close modal" 
                                    style="background: rgba(0,0,0,0.2); border: none; color: black; font-size: 1.2rem; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;">
                                ×
                            </button>
                        </div>
                    </div>
                    
                    <!-- Body -->
                    <div class="offcanvas-body p-4" style="background: #f8fafc; scrollbar-width:none;">
                        <div class="container-fluid">
                            <div class="row">
                                <!-- Left Column -->
                                <div class="col-md-6">
                                    <!-- Customer Info -->
                                    <div class="card border mb-3">
                                        <div class="card-header p-2" style="background: #c6d2ff !important;">
                                            <h6 class="mb-0 fw-semibold text-black"><i class="bi bi-person me-2"></i>Customer Information</h6>
                                        </div>
                                        <div class="card-body p-2">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3 bg-primary"
                                                    style="width: 50px; height: 50px; color: #ffffff; font-weight: bold; font-size: 1.2rem;">
                                                    ${booking.user_name ? booking.user_name.charAt(0).toUpperCase() : '?'}
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-1 text-black">${booking.user_name || '--'}</h6>
                                                    <p class="text-muted mb-1" style="font-size: 0.9rem;"><i class="bi bi-telephone me-1"></i> ${booking.user_phone || '--'}</p>
                                                    <p class="text-muted mb-0" style="font-size: 0.9rem;"><i class="bi bi-geo-alt me-1"></i> ${booking.state || '--'}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Service Details -->
                                    <div class="card service-card mb-3">
                                        <div class="card-header p-2" style="background: #c6d2ff !important;">
                                            <h6 class="mb-0 fw-semibold text-black">
                                                <i class="bi bi-briefcase me-2"></i>Service Details
                                            </h6>
                                        </div>
                                        <div class="card-body service-card-body p-2">
                                            <div class="mb-3">
                                                <span class="badge text-white fw-bold rounded-pill p-2 px-3 text-primary" style="background: #c6d2ff !important; border: 1px solid #486ef8 !important;">
                                                    ${booking.service_name || '--'}
                                                </span>
                                            </div>
                                            <div class="service-info">
                                                <p class="mb-1 text-dark" style="font-size: 0.9rem;"><i class="bi bi-calendar m-1"></i><span class="fw-bold text-black">Start Date:</span> ${startDate}</p>
                       ${endDate ? `
  <p class="mb-1 text-dark" style="font-size: 0.9rem;">
    <i class="bi bi-calendar m-1"></i>
    <span class="fw-bold text-black">End Date:</span> ${endDate}
  </p>
` : ''}

                                                ${booking.service_frequency ? `
                                                    <p class="mb-1 text-dark" style="font-size: 0.9rem;"><i class="bi bi-arrow-repeat m-1"></i><span class="fw-bold text-black">Frequency:</span> ${booking.service_frequency}</p>` : ''}
                                                ${booking.service_days ? `
                                                    <p class="mb-1 text-dark" style="font-size: 0.9rem;"><i class="bi bi-calendar-week m-1"></i><span class="fw-bold text-black">Service Days:</span> ${booking.service_days}</p>` : ''}

                                                ${booking.preferable_time ? `
                                                    <p class="mb-1 text-dark" style="font-size: 0.9rem;"><i class="bi bi-clock m-1"></i><span class="fw-bold text-black">Preferable Time:</span> ${booking.preferable_time}</p>` : ''}

                                                ${booking.walk_duration ? `
                                                    <p class="mb-1 text-dark" style="font-size: 0.9rem;"><i class="bi bi-watch m-1"></i><span class="fw-bold text-black">Walk Duration:</span> ${booking.walk_duration}</p>` : ''}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6">
                                    <!-- Booking Status -->
                                    <div class="card border mb-3">
                                        <div class="card-header p-2" style="background: #c6d2ff !important;">
                                            <h6 class="mb-0 fw-semibold text-black"><i class="fa-solid fa-file-code me-2"></i></i>Booking Status</h6>
                                        </div>
                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div class="d-flex gap-2 align-items-center">
                                                    <p class="fw-bold mb-1 text-dark" style="font-size: 0.9rem;">Status</p>
                                                    ${statusBadge}
                                                </div>
                                                <div class="d-flex gap-2 align-items-center">
                                                    ${daysBadge}
                                                </div>
                                            </div>
                                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                                <p class="fw-bold text-dark" style="font-size: 0.9rem;">Total Price</p>
                                                <h6 class="text-primary fw-bold">₹${booking.total_price || '0'}</h6>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pet Information -->
                                    <div class="card border mb-3">
                                        <div class="card-header p-2" style="background: #c6d2ff !important;">
                                            <h6 class="mb-0 fw-semibold text-black"><i class="bi bi-brightness-alt-high me-2"></i>Pet Information</h6>
                                        </div>
                                        <div class="card-body p-2">
                                            ${petBreedsHtml}
                                            ${booking.pet_names ? `
                                                <p class="mb-2 text-dark"><strong>Pet Names:</strong> ${booking.pet_names}</p>
                                            ` : ''}
                                        </div>
                                    </div>
                                    
                                    ${packagesHtml ? `
                                    <!-- Package Information -->
                                    <div class="card border mb-3">
                                        <div class="card-header p-2" style="background: #c6d2ff !important;">
                                            <h6 class="mb-0 fw-semibold text-black"><i class="bi bi-box me-2"></i>Package Information</h6>
                                        </div>
                                        <div class="card-body p-2">
                                            ${packagesHtml}
                                        </div>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>

                            <!-- Full Width Sections -->
                            <div class="row">
                                <div class="col-12">
                                    <!-- Addons Section -->
                                    ${addonsHtml ? `
                                    <div class="card border mb-3">
                                        <div class="card-header p-2" style="background: #c6d2ff !important;">
                                            <h6 class="mb-0 fw-semibold text-black"><i class="bi bi-plus-circle me-2"></i>Addons</h6>
                                        </div>
                                        <div class="card-body p-2">
                                            ${addonsHtml}
                                        </div>
                                    </div>
                                    ` : ''}

                                    <!-- Location Details -->
                                    ${booking.address ? `
                                    <div class="card border mt-3">
                                        <div class="card-header p-2" style="background: #c6d2ff !important;">
                                            <h6 class="mb-0 fw-semibold text-black"><i class="bi bi-geo me-2"></i>Location Details</h6>
                                        </div>
                                        <div class="card-body p-2">
                                            <p class="mb-1 text-dark"><strong>Address:</strong> ${booking.address}</p>
                                            ${booking.houseno_floor ? `<p class="mb-1 text-dark"><strong>House/Floor:</strong> ${booking.houseno_floor}</p>` : ''}
                                            ${booking.building_blockno ? `<p class="mb-1 text-dark"><strong>Building/Block:</strong> ${booking.building_blockno}</p>` : ''}
                                            ${booking.landmark_areaname ? `<p class="mb-1 text-dark"><strong>Landmark:</strong> ${booking.landmark_areaname}</p>` : ''}
                                            <p class="mb-0 text-dark"><strong>City:</strong> ${booking.city || '--'}</p>
                                        </div>
                                    </div>
                                    ` : ''}

                                    <!-- Service Provider (if exists) -->
                                    ${booking.provider_name ? `
                                    <div class="card border mt-3">
                                        <div class="card-header p-2" style="background: #c6d2ff !important;">
                                            <h6 class="mb-0 fw-semibold text-black"><i class="bi bi-person-badge me-2"></i>Service Provider</h6>
                                        </div>
                                        <div class="card-body p-2">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="mb-1 text-dark"><strong>Provider Name:</strong> ${booking.provider_name}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="mb-1 text-dark"><strong>Provider Phone:</strong> ${booking.provider_phone || '--'}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    ` : ''}

                                    <!-- Additional Info -->
                                    ${booking.comment || booking.cancel_reason ? `
                                    <div class="card border mt-3">
                                        <div class="card-header p-2" style="background: #c6d2ff !important;">
                                            <h6 class="mb-0 fw-semibold text-black"><i class="bi bi-info-circle me-2"></i>Additional Information</h6>
                                        </div>
                                        <div class="card-body p-2">
                                            ${booking.cancel_reason ? `
                                                <div class="mb-3">
                                                    <p class="mb-1 text-dark"><strong>Cancellation Reason:</strong></p>
                                                    <div class="p-2 bg-light border-start border-3 border-danger">
                                                        ${booking.cancel_reason}
                                                    </div>
                                                </div>
                                            ` : ''}
                                            ${booking.comment ? `
                                                <div>
                                                    <p class="mb-1 text-dark"><strong>Admin Comment:</strong></p>
                                                    <div class="p-2 bg-light border-start border-3 border-info">
                                                        ${booking.comment}
                                                    </div>
                                                </div>
                                            ` : ''}
                                        </div>
                                    </div>
                                    ` : ''}
                                </div>
=======
    // Function to show booking details
    function showBookingDetails(booking) {
        console.log("booking", booking);
        window._currentModalBooking = booking;

        // Helper to format dates
        const formatDate = (dateStr) => {
            if (!dateStr || dateStr === '0000-00-00' || dateStr === '--') return '--';
            try {
                const safeStr = (typeof dateStr === 'string' && dateStr.includes(' ') && !dateStr.includes('T')) ?
                    dateStr.replace(' ', 'T') :
                    dateStr;
                return new Date(safeStr).toLocaleDateString('en-IN', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            } catch (e) {
                return '--';
            }
        };

        const formatPrice = (val) => {
            if (!val || isNaN(val)) return '0';
            return Number(val).toLocaleString('en-IN', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });
        };

        const cleanPhone = (phone) => {
            if (!phone) return '';
            return String(phone).replace(/\D/g, '').slice(-10);
        };

        const startDate = formatDate(booking.service_start_date);
        let endDate = formatDate(booking.service_end_date);
        if (Number(booking.service_id) === 6 && booking.service_start_date && booking.days) {
            const start = new Date(booking.service_start_date);
            const days = parseInt(booking.days);
            if (!isNaN(days)) {
                start.setDate(start.getDate() + days);
                const year = start.getFullYear();
                const month = String(start.getMonth() + 1).padStart(2, '0');
                const day = String(start.getDate()).padStart(2, '0');
                endDate = formatDate(`${year}-${month}-${day}`);
            }
        }

        let modalTotalDays = null;
        if (booking.service_start_date && booking.service_end_date && booking.service_start_date !== '0000-00-00' && booking.service_end_date !== '0000-00-00') {
            const isWeekdays = String(booking.service_days || '').toLowerCase().trim() === 'weekdays';
            const d1 = new Date(booking.service_start_date);
            const d2 = new Date(booking.service_end_date);
            if (!isNaN(d1.getTime()) && !isNaN(d2.getTime())) {
                if (isWeekdays) {
                    let count = 0;
                    const cur = new Date(d1.getTime());
                    while (cur <= d2) {
                        if (cur.getDay() !== 0) count++;
                        cur.setDate(cur.getDate() + 1);
                    }
                    modalTotalDays = count;
                } else {
                    const diffTime = d2.getTime() - d1.getTime();
                    if (diffTime >= 0) {
                        modalTotalDays = Math.round(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    }
                }
            }
        }
        if (!modalTotalDays && booking.days && !isNaN(parseInt(booking.days)) && parseInt(booking.days) > 0) {
            modalTotalDays = parseInt(booking.days);
        }

        // Service Theme & Icon Configuration
        const sNameLower = (booking.service_name || booking.service_type || '').toLowerCase();
        let svcConfig = {
            name: booking.service_name || booking.service_type || 'Pet Service',
            icon: 'bi-star-fill',
            themeBg: 'linear-gradient(135deg, #1e1b4b 0%, #312e81 45%, #4338ca 100%)',
            tagClass: 'svc-tag-training',
            iconBg: 'rgba(245, 158, 11, 0.15)',
            iconColor: '#f59e0b'
        };

        if (sNameLower.includes('walk')) {
            svcConfig = {
                name: booking.service_name || booking.service_type || 'Dog Walking',
                icon: 'bi-compass-fill',
                themeBg: 'linear-gradient(135deg, #042f2e 0%, #115e59 45%, #0d9488 100%)',
                tagClass: 'svc-tag-walking',
                iconBg: 'rgba(20, 184, 166, 0.15)',
                iconColor: '#14b8a6'
            };
        } else if (sNameLower.includes('groom')) {
            svcConfig = {
                name: booking.service_name || booking.service_type || 'Pet Grooming',
                icon: 'bi-scissors',
                themeBg: 'linear-gradient(135deg, #2e1065 0%, #5b21b6 45%, #7c3aed 100%)',
                tagClass: 'svc-tag-grooming',
                iconBg: 'rgba(168, 85, 247, 0.15)',
                iconColor: '#c084fc'
            };
        } else if (sNameLower.includes('train')) {
            svcConfig = {
                name: booking.service_name || booking.service_type || 'Pet Training',
                icon: 'bi-award-fill',
                themeBg: 'linear-gradient(135deg, #451a03 0%, #78350f 45%, #b45309 100%)',
                tagClass: 'svc-tag-training',
                iconBg: 'rgba(245, 158, 11, 0.15)',
                iconColor: '#fbbf24'
            };
        } else if (sNameLower.includes('sit') || sNameLower.includes('board')) {
            svcConfig = {
                name: booking.service_name || booking.service_type || 'Pet Sitting',
                icon: 'bi-house-heart-fill',
                themeBg: 'linear-gradient(135deg, #0c4a6e 0%, #0369a1 45%, #0284c7 100%)',
                tagClass: 'svc-tag-sitting',
                iconBg: 'rgba(56, 189, 248, 0.15)',
                iconColor: '#38bdf8'
            };
        }

        // Status Badge Logic
        const currentStatus = booking.status || booking.source || 'Pending';
        const stLower = currentStatus.toLowerCase();
        let statusBadgeInfo = {
            label: currentStatus,
            badgeClass: 'status-pill-amber',
            dotClass: 'dot-amber'
        };

        if (stLower.includes('active') || stLower.includes('confirm') || stLower.includes('accept') || stLower.includes('progress') || stLower.includes('assigned')) {
            statusBadgeInfo = {
                label: currentStatus,
                badgeClass: 'status-pill-emerald',
                dotClass: 'dot-emerald'
            };
        } else if (stLower.includes('complete')) {
            statusBadgeInfo = {
                label: currentStatus,
                badgeClass: 'status-pill-blue',
                dotClass: 'dot-blue'
            };
        } else if (stLower.includes('halt') || stLower.includes('hold') || stLower.includes('pause')) {
            statusBadgeInfo = {
                label: currentStatus,
                badgeClass: 'status-pill-purple',
                dotClass: 'dot-purple'
            };
        } else if (stLower.includes('cancel') || stLower.includes('delete') || stLower.includes('reject')) {
            statusBadgeInfo = {
                label: currentStatus,
                badgeClass: 'status-pill-rose',
                dotClass: 'dot-rose'
            };
        }

        // Address deduplication
        const cleanAddressText = (() => {
            const rawParts = [
                booking.houseno_floor,
                booking.building_blockno,
                booking.landmark_areaname,
                booking.address
            ].filter(val => val && val !== '--' && val !== '0' && val !== 'null' && String(val).trim() !== '');

            let combined = rawParts.join(', ');
            const tokens = combined.split(',').map(t => t.trim()).filter(t => t.length > 0);
            const seen = new Set();
            const uniqueTokens = [];
            for (const t of tokens) {
                const lower = t.toLowerCase();
                if (!seen.has(lower)) {
                    seen.add(lower);
                    uniqueTokens.push(t);
                }
            }
            return uniqueTokens.join(', ') || booking.address || 'Address not provided';
        })();

        const mapsQuery = encodeURIComponent(`${cleanAddressText}, ${booking.city || ''}, ${booking.state || ''}`);
        const mapsUrl = `https://www.google.com/maps/search/?api=1&query=${mapsQuery}`;
        const locationBadgeText = [booking.city, booking.state].filter(val => val && val !== '--' && val !== 'null' && String(val).trim() !== '').join(', ');

        // Modal ID
        const modalId = 'bookingDetailsModal';

        // Build Pet Profiles HTML
        let petsHtml = '';
        if (booking.pets && booking.pets.length > 0) {
            petsHtml = booking.pets.map(pet => {
                const petDobFormatted = formatDate(pet.dob);
                const isAggressive = (pet.aggressiveness_level || '').toLowerCase();
                let aggBadge = '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1 rounded-pill"><i class="bi bi-shield-check me-1"></i>Normal</span>';
                if (isAggressive.includes('high') || isAggressive.includes('severe') || isAggressive.includes('heavy')) {
                    aggBadge = '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1 rounded-pill"><i class="bi bi-exclamation-triangle-fill me-1"></i>High Aggression</span>';
                } else if (isAggressive.includes('slight') || isAggressive.includes('medium') || isAggressive.includes('moderate')) {
                    aggBadge = '<span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2.5 py-1 rounded-pill"><i class="bi bi-exclamation-circle-fill me-1"></i>Slightly Aggressive</span>';
                }

                const isInsured = (pet.insured || '').toLowerCase() === 'yes' || pet.insured === 1 || pet.insured === '1';
                const isVaccinated = (pet.vaccinated || '').toLowerCase() === 'yes' || pet.vaccinated === 1 || pet.vaccinated === '1';
                const isRabies = (pet.vaccinated_rabies || '').toLowerCase() === 'yes' || pet.vaccinated_rabies === 1 || pet.vaccinated_rabies === '1';
                const isLicensed = (pet.licensed || '').toLowerCase() === 'yes' || pet.licensed === 1 || pet.licensed === '1';

                const hasValidLastVaccine = pet.last_vaccination && pet.last_vaccination !== '--' && pet.last_vaccination !== '0000-00-00' && pet.last_vaccination !== 'null';
                const hasValidDewormed = pet.dewormed_on && pet.dewormed_on !== '--' && pet.dewormed_on !== '0000-00-00' && pet.dewormed_on !== 'null';

                return `
                <div class="pet-card-item p-3 mb-3 bg-white rounded-3 border shadow-xs">
                    <!-- Pet Header -->
                    <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom">
                        <div class="d-flex align-items-center gap-3">
                            <div class="pet-avatar-box">
                                ${pet.image ?
                                    `<img src="https://Petsfolio.in/public/uploads/pets/${pet.image}" class="pet-avatar-img" alt="${pet.name}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                     <div class="pet-avatar-fallback" style="display:none;"><i class="bi bi-paw-fill"></i></div>` :
                                    `<div class="pet-avatar-fallback"><i class="bi bi-paw-fill"></i></div>`
                                }
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold text-dark text-capitalize fs-6">${pet.name || 'Unnamed Pet'}</h6>
                                <span class="badge bg-light text-secondary border px-2 py-1 rounded-2 tiny">
                                    <i class="bi bi-tag-fill me-1 text-primary"></i>${pet.type || 'Pet'} • ${pet.breed || 'Unknown Breed'}
                                </span>
                            </div>
                        </div>
                        <div>
                            ${aggBadge}
                        </div>
                    </div>
                    
                    <!-- Key Specs 4-col Grid -->
                    <div class="row g-2 text-center mb-3">
                        <div class="col-3">
                            <div class="spec-tile-clean">
                                <span class="spec-tile-head">GENDER</span>
                                <span class="spec-tile-data text-capitalize">${pet.gender ? (pet.gender.toLowerCase().includes('male') && !pet.gender.toLowerCase().includes('female') ? '♂ Male' : (pet.gender.toLowerCase().includes('female') ? '♀ Female' : pet.gender)) : '--'}</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="spec-tile-clean">
                                <span class="spec-tile-head">DOB / AGE</span>
                                <span class="spec-tile-data">${petDobFormatted}</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="spec-tile-clean">
                                <span class="spec-tile-head">INSURED</span>
                                <span class="spec-tile-data ${isInsured ? 'text-success' : 'text-muted'}">
                                    <i class="bi ${isInsured ? 'bi-shield-fill-check text-success' : 'bi-shield-x text-muted'} me-1"></i>${isInsured ? 'Yes' : 'No'}
                                </span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="spec-tile-clean">
                                <span class="spec-tile-head">LICENSED</span>
                                <span class="spec-tile-data ${isLicensed ? 'text-success' : 'text-muted'}">
                                    <i class="bi ${isLicensed ? 'bi-patch-check-fill text-success' : 'bi-x-circle text-muted'} me-1"></i>${isLicensed ? 'Yes' : 'No'}
                                </span>
>>>>>>> origin/main
                            </div>
                        </div>
                    </div>

<<<<<<< HEAD
                    <!-- Footer -->
                    <div class="offcanvas-footer border-top p-4">
                        <div class="d-flex gap-3 justify-content-end">
                            <button class="btn btn-outline-secondary d-flex align-items-center gap-2" type="button">
                                <i class="bi bi-x-circle"></i> Close
                            </button>
                        </div>
                    </div>
=======
                    <!-- Medical & Vaccines Micro Bar (Single Neat Row) -->
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="badge ${isVaccinated ? 'bg-success bg-opacity-10 text-success border border-success border-opacity-25' : 'bg-light text-secondary border'} px-2.5 py-1.5 rounded-2 tiny">
                            <i class="bi ${isVaccinated ? 'bi-check-circle-fill' : 'bi-dash-circle'} me-1"></i>Vaccinated: <strong>${isVaccinated ? 'Yes' : 'No'}</strong>
                        </span>
                        <span class="badge ${isRabies ? 'bg-success bg-opacity-10 text-success border border-success border-opacity-25' : 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25'} px-2.5 py-1.5 rounded-2 tiny">
                            <i class="bi ${isRabies ? 'bi-shield-fill-check' : 'bi-exclamation-octagon-fill'} me-1"></i>Rabies: <strong>${isRabies ? 'Active' : 'Missing'}</strong>
                        </span>
                        ${hasValidLastVaccine ? `
                        <span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-2 tiny">
                            <i class="bi bi-calendar-event me-1 text-primary"></i>Last Vaccine: <strong>${formatDate(pet.last_vaccination)}</strong>
                        </span>` : ''}
                        ${hasValidDewormed ? `
                        <span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-2 tiny">
                            <i class="bi bi-capsule me-1 text-primary"></i>Dewormed: <strong>${formatDate(pet.dewormed_on)}</strong>
                        </span>` : ''}
                    </div>

                    <!-- Vet Information (if provided) -->
                    ${(pet.vet_name || pet.vet_phone || pet.vet_address) ? `
                    <div class="p-2.5 rounded-3 bg-light border d-flex align-items-center justify-content-between mt-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-hospital-fill text-primary fs-5"></i>
                            <div>
                                <div class="fw-bold text-dark small">${pet.vet_name || 'Veterinary Clinic'}</div>
                                <div class="tiny text-muted">${pet.vet_address || 'Clinic registered with Pet'}</div>
                            </div>
                        </div>
                        ${pet.vet_phone ? `
                        <a href="tel:${pet.vet_phone}" class="btn btn-sm btn-outline-primary py-1 px-2.5 rounded-pill tiny fw-bold">
                            <i class="bi bi-telephone-fill me-1"></i>${pet.vet_phone}
                        </a>` : ''}
                    </div>` : ''}
                </div>`;
            }).join('');
        } else {
            petsHtml = `
                <div class="text-center py-4 px-3 bg-light rounded-3 border border-dashed text-muted">
                    <i class="bi bi-paw fs-2 opacity-50 d-block mb-1"></i>
                    <span class="small fw-medium">No detailed pet profile attached to this booking</span>
                </div>
            `;
        }

        // Build Packages & Addons
        let extrasHtml = '';
        let hasExtras = false;

        // Packages (Grooming / Training)
        if (booking.packages && booking.packages.length > 0) {
            hasExtras = true;
            extrasHtml += booking.packages.map(pkg => {
                let pkgPrice = pkg.package_price || pkg.price || '0';
                let pkgHtml = `
                    <div class="p-3 rounded-3 bg-light border mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-dark fw-bold d-flex align-items-center gap-2">
                                <i class="bi bi-star-fill text-warning"></i>
                                ${pkg.package_name || 'Standard Package'}
                            </span>
                            <span class="fw-bold text-primary fs-6">₹${formatPrice(pkgPrice)}</span>
                        </div>
                `;

                const isWalking = (booking.service_id == 4 || (booking.service_name && booking.service_name.toLowerCase().includes('walking')));
                if (!isWalking && pkg.included_addons && Array.isArray(pkg.included_addons) && pkg.included_addons.length > 0) {
                    pkgHtml += `
                        <div class="mt-2 pt-2 border-top">
                            <span class="tiny fw-bold text-muted text-uppercase d-block mb-1.5">Included in Package:</span>
                            <div class="d-flex flex-wrap gap-1.5">
                    `;
                    pkgHtml += pkg.included_addons.map(incl => `
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill tiny fw-medium px-2 py-1">
                            <i class="bi bi-check2 me-1"></i>${typeof incl === 'object' ? (incl.addon_name || incl.name) : incl}
                        </span>
                    `).join('');
                    pkgHtml += `</div></div>`;
                }
                pkgHtml += `</div>`;
                return pkgHtml;
            }).join('');
        } else if (booking.package_name) {
            hasExtras = true;
            extrasHtml += `
                <div class="p-3 rounded-3 bg-light border mb-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <span class="text-dark fw-bold d-flex align-items-center gap-2">
                            <i class="bi bi-star-fill text-warning"></i>
                            ${booking.package_name}
                            ${(booking.duration_days && (sNameLower.includes('sit') || sNameLower.includes('board') || sTypeLower.includes('sit'))) ? `
                            <span class="badge bg-info bg-opacity-10 text-white border border-info border-opacity-25 rounded-pill tiny fw-bold px-2 py-0.5">
                                <i class="bi bi-hourglass-split me-1"></i>${booking.duration_days} Hours
                            </span>` : ''}
                        </span>
                        <span class="fw-bold text-primary fs-6">₹${formatPrice(booking.package_price || booking.total_price)}</span>
                    </div>
            `;

            const isWalking = (booking.service_id == 4 || (booking.service_name && booking.service_name.toLowerCase().includes('walking')));
            if (!isWalking && booking.includedAddons && Array.isArray(booking.includedAddons) && booking.includedAddons.length > 0) {
                extrasHtml += `
                    <div class="mt-2 pt-2 border-top">
                        <span class="tiny fw-bold text-muted text-uppercase d-block mb-1.5">Included in Package:</span>
                        <div class="d-flex flex-wrap gap-1.5">
                `;
                extrasHtml += booking.includedAddons.map(incl => `
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill tiny fw-medium px-2 py-1">
                        <i class="bi bi-check2 me-1"></i>${typeof incl === 'object' ? (incl.addon_name || incl.name) : incl}
                    </span>
                `).join('');
                extrasHtml += `</div></div>`;
            }
            extrasHtml += `</div>`;
        }

        // Addons List
        if (booking.addons && Array.isArray(booking.addons) && booking.addons.length > 0) {
            hasExtras = true;
            extrasHtml += `
                <div class="p-3 bg-white rounded-3 border">
                    <span class="tiny fw-bold text-muted text-uppercase d-block mb-2">Selected Add-on Services:</span>
            `;
            extrasHtml += booking.addons.map(addon => {
                const price = addon.addon_price || addon.price || addon.addon_table_price || '0';
                return `
                    <div class="d-flex justify-content-between align-items-center p-2 mb-1.5 rounded-2 bg-light border">
                        <span class="text-dark small fw-semibold d-flex align-items-center gap-2">
                            <i class="bi bi-plus-circle-fill text-primary"></i>
                            ${addon.addon || addon.name || 'Addon'}
                        </span>
                        <span class="fw-bold text-dark small">₹${formatPrice(price)}</span>
                    </div>
                `;
            }).join('');
            extrasHtml += `</div>`;
        }

        if (!hasExtras) {
            extrasHtml = `
                <div class="p-3 bg-light rounded-3 border text-center text-muted small">
                    <i class="bi bi-box-seam me-1"></i>Standard Service Booking (No extra add-ons)
                </div>
            `;
        }

        // Assigned Specialist HTML
        let specialistHtml = '';
        if (booking.provider_name && booking.provider_name !== '--' && !booking.provider_name.toLowerCase().includes('search')) {
            specialistHtml = `
                <div class="d-flex align-items-center justify-content-between p-1">
                    <div class="d-flex align-items-center gap-3">
                        <div class="specialist-avatar-circle shadow-xs">
                            <i class="bi bi-person-fill fs-4 text-success"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark text-capitalize fs-6">${booking.provider_name}</h6>
                            <div class="text-muted small d-flex align-items-center gap-2 mt-0.5">
                                <span><i class="bi bi-telephone me-1 text-secondary"></i>${booking.provider_phone || '--'}</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        ${booking.provider_phone ? `
                        <a href="tel:${booking.provider_phone}" class="btn btn-sm btn-outline-success rounded-pill px-2.5 py-1 tiny fw-bold" title="Call Provider">
                            <i class="bi bi-telephone-fill me-1"></i>Call
                        </a>
                        <a href="https://wa.me/91${cleanPhone(booking.provider_phone)}" target="_blank" class="btn btn-sm btn-success rounded-pill px-2.5 py-1 tiny fw-bold" title="WhatsApp Provider">
                            <i class="bi bi-whatsapp me-1"></i>WhatsApp
                        </a>` : ''}
                    </div>
                </div>
            `;
        } else {
            specialistHtml = `
                <div class="text-center p-3 rounded-3 bg-warning bg-opacity-10 border border-warning border-opacity-25">
                    <div class="mb-2">
                        <i class="bi bi-radar text-warning fs-3"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1 small">Awaiting Specialist Assignment</h6>
                    <p class="tiny text-muted mb-3">No service provider has accepted or been assigned yet.</p>
                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1.5 fw-bold shadow-sm" onclick="closeBookingDetails(); showNearbyProviders('${booking.id}', '${(booking.user_name || 'Customer').replace(/'/g, "\\'")}', '₹${booking.total_price || '0'}');">
                        <i class="bi bi-people-fill me-1"></i>Find Nearby Providers
                    </button>
                </div>
            `;
        }

        const modalContent = `
        <div class="modal fade" id="${modalId}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable booking-details-dialog">
                <div class="modal-content custom-booking-modal border-0 shadow-2xl rounded-4 overflow-hidden">
                    
                    <!-- 1. Header (Deep Indigo SaaS Theme) -->
                    <div class="modal-header border-0 px-4 py-3 text-white position-relative" style="background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 25%, #3730a3 60%, #4f46e5 100%);">
                        <div class="d-flex align-items-center justify-content-between w-100 pe-1">
                            <div class="d-flex align-items-center gap-3">
                                <div class="header-icon-box rounded-3 d-flex align-items-center justify-content-center" style="background: ${svcConfig.iconBg}; color: ${svcConfig.iconColor};">
                                    <i class="bi ${svcConfig.icon} fs-4"></i>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                                        <h5 class="modal-title fw-bold mb-0 text-white tracking-tight">Booking Details</h5>
                                        <span class="badge ${svcConfig.tagClass} rounded-pill px-2.5 py-1 tiny fw-bold text-uppercase">
                                            <i class="bi ${svcConfig.icon} me-1"></i>${svcConfig.name}
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center flex-wrap gap-2 text-slate-300 small">
                                        <span>Reference: <strong class="text-white font-monospace">#${booking.id}</strong></span>
                                        <button type="button" class="btn btn-sm btn-outline-light py-0 px-2 rounded-pill copy-ref-btn tiny text-decoration-none" onclick="navigator.clipboard.writeText('${booking.id}'); $(this).html('<i class=\\'bi bi-check2 text-success\\'></i> Copied'); setTimeout(() => $(this).html('<i class=\\'bi bi-clipboard\\'></i> Copy'), 2000);" title="Copy Reference ID">
                                            <i class="bi bi-clipboard"></i> Copy
                                        </button>
                                        ${booking.created_at ? `<span class="opacity-40">•</span> <span class="tiny text-slate-300 opacity-80"><i class="bi bi-clock me-1"></i>Booked on ${formatDate(booking.created_at)}</span>` : ''}
                                        ${booking.confirmed_at ? `<span class="opacity-40">•</span> <span class="tiny text-emerald-300 opacity-90"><i class="bi bi-check-circle me-1"></i>Confirmed on ${formatDate(booking.confirmed_at)}</span>` : ''}
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn-close btn-close-white opacity-75 hover-opacity-100" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>

                    <!-- 2. Sticky KPI / Status Bar -->
                    <div class="modal-subbar px-4 py-2.5 bg-white border-bottom d-flex flex-wrap justify-content-between align-items-center gap-3 sticky-top shadow-xs" style="z-index: 10;">
                        <div class="d-flex flex-wrap align-items-center gap-4">
                            <!-- Status -->
                            <div class="kpi-block">
                                <span class="kpi-label">STATUS</span>
                                <div class="mt-0.5">
                                    <span class="status-pill-modern ${statusBadgeInfo.badgeClass}">
                                        <span class="pulse-dot ${statusBadgeInfo.dotClass}"></span>
                                        ${statusBadgeInfo.label.toUpperCase()}
                                    </span>
                                </div>
                            </div>

                            <div class="vr bg-slate-200 d-none d-sm-block" style="height: 28px;"></div>

                            <!-- Service Category -->
                            <div class="kpi-block">
                                <span class="kpi-label">SERVICE CATEGORY</span>
                                <div class="mt-0.5 fw-bold text-dark d-flex align-items-center gap-1.5 fs-7">
                                    <i class="bi ${svcConfig.icon} text-primary"></i>${svcConfig.name}
                                </div>
                            </div>

                            ${booking.service_mode ? `
                            <div class="vr bg-slate-200 d-none d-md-block" style="height: 28px;"></div>
                            <div class="kpi-block d-none d-md-block">
                                <span class="kpi-label">SERVICE MODE</span>
                                <div class="mt-0.5 fw-bold text-dark text-capitalize fs-7">
                                    <i class="bi bi-geo-alt-fill text-primary me-1"></i>${booking.service_mode}
                                </div>
                            </div>` : ''}
                        </div>

                        <!-- Total Amount -->
                        <div class="text-end">
                            <span class="kpi-label text-end">TOTAL AMOUNT</span>
                            <div class="d-flex align-items-baseline justify-content-end gap-1 mt-0.5">
                                <span class="price-currency text-primary">₹</span>
                                <span class="price-value text-dark">${formatPrice(booking.total_price)}</span>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Modal Body (Clean Two-Column Grid) -->
                    <div class="modal-body p-4" style="background: #f8fafc;">
                        <div class="row g-4">
                            
                            <!-- Left Column (6 cols): Customer Profile & Pet Profiles -->
                            <div class="col-lg-6">
                                
                                <!-- Customer Profile Card -->
                                <div class="card border shadow-xs rounded-3 mb-4 bg-white">
                                    <div class="card-header bg-white border-bottom py-3 px-3.5 d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-person-circle text-primary fs-5"></i>
                                            <h6 class="mb-0 fw-bold text-dark">Customer Profile</h6>
                                        </div>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2.5 py-1 tiny fw-semibold">
                                            <i class="bi bi-shield-check me-1"></i>Verified Client
                                        </span>
                                    </div>
                                    <div class="card-body p-3.5">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="user-avatar-gradient">
                                                    ${booking.user_name ? booking.user_name.charAt(0).toUpperCase() : 'U'}
                                                </div>
                                                <div>
                                                    <h5 class="mb-0 fw-bold text-dark fs-6">${booking.user_name || 'Unnamed Client'}</h5>
                                                    <div class="text-muted small mt-0.5 d-flex align-items-center gap-2">
                                                        <span><i class="bi bi-telephone me-1 text-secondary"></i>${booking.user_phone || '--'}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Quick Contact Actions -->
                                            <div class="d-flex gap-2">
                                                ${booking.user_phone ? `
                                                <a href="tel:${booking.user_phone}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 tiny fw-bold" title="Call Customer">
                                                    <i class="bi bi-telephone-fill me-1"></i>Call
                                                </a>
                                                <a href="https://wa.me/91${cleanPhone(booking.user_phone)}" target="_blank" class="btn btn-sm btn-success rounded-pill px-3 py-1 tiny fw-bold" title="WhatsApp Customer">
                                                    <i class="bi bi-whatsapp me-1"></i>WhatsApp
                                                </a>` : ''}
                                            </div>
                                        </div>

                                        <!-- Clean Deduplicated Address Box -->
                                        <div class="p-3 bg-light rounded-3 border">
                                            <div class="d-flex align-items-start gap-2.5">
                                                <i class="bi bi-geo-alt-fill text-primary fs-5 mt-0.5"></i>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                                        <span class="address-subhead">SERVICE LOCATION</span>
                                                        <a href="${mapsUrl}" target="_blank" class="tiny text-primary text-decoration-none fw-semibold"><i class="bi bi-box-arrow-up-right me-1"></i>Open Maps</a>
                                                    </div>
                                                    <div class="address-text">${cleanAddressText}</div>
                                                    ${locationBadgeText ? `
                                                    <div class="mt-2">
                                                        <span class="badge bg-white text-dark border px-2.5 py-1 rounded-2 tiny shadow-xs">
                                                            <i class="bi bi-pin-map-fill me-1 text-danger"></i>${locationBadgeText}
                                                        </span>
                                                    </div>` : ''}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pet Profiles Card -->
                                <div class="card border shadow-xs rounded-3 bg-white">
                                    <div class="card-header bg-white border-bottom py-3 px-3.5 d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-paw-fill text-warning fs-5"></i>
                                            <h6 class="mb-0 fw-bold text-dark">Pet Profiles</h6>
                                        </div>
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-2.5 py-1 tiny fw-bold">
                                            ${(booking.pets && booking.pets.length) ? booking.pets.length + ' Pet' + (booking.pets.length > 1 ? 's' : '') : '1 Pet'}
                                        </span>
                                    </div>
                                    <div class="card-body p-3.5">
                                        ${petsHtml}
                                    </div>
                                </div>

                            </div>

                            <!-- Right Column (6 cols): Schedule, Packages & Specialist -->
                            <div class="col-lg-6">
                                
                                <!-- Service Schedule Card -->
                                <div class="card border shadow-xs rounded-3 mb-4 bg-white">
                                    <div class="card-header bg-white border-bottom py-3 px-3.5 d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-calendar-check-fill text-success fs-5"></i>
                                            <h6 class="mb-0 fw-bold text-dark">Service Schedule</h6>
                                        </div>
                                    </div>
                                    <div class="card-body p-3.5">
                                        <!-- Date Range Pill Boxes -->
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <div class="p-2.5 rounded-3 text-center bg-primary bg-opacity-10 border border-primary border-opacity-25">
                                                    <span class="schedule-date-label text-white">START DATE</span>
                                                    <div class="schedule-date-value text-white">${startDate}</div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-2.5 rounded-3 text-center bg-light border">
                                                    <span class="schedule-date-label text-muted">END DATE</span>
                                                    <div class="schedule-date-value text-white">${endDate || '--'}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Schedule Details Grid -->
                                        <div class="schedule-specs-list">
                                            <div class="spec-row">
                                                <span class="spec-label"><i class="bi bi-clock me-2 text-secondary"></i>Preferred Time</span>
                                                <span class="spec-val fw-bold text-primary">${booking.preferable_time || '--'}</span>
                                            </div>
                                            ${booking.confirmed_at ? `
                                            <div class="spec-row">
                                                <span class="spec-label"><i class="bi bi-check-circle-fill me-2 text-success"></i>Confirmed Date</span>
                                                <span class="spec-val fw-bold text-success">${formatDate(booking.confirmed_at)}</span>
                                            </div>` : ''}
                                            ${(booking.duration_days && (sNameLower.includes('sit') || sNameLower.includes('board') || sTypeLower.includes('sit'))) ? `
                                            <div class="spec-row">
                                                <span class="spec-label"><i class="bi bi-hourglass-split me-2 text-secondary"></i>Duration</span>
                                                <span class="spec-val fw-bold text-dark">${booking.duration_days} Hours</span>
                                            </div>` : ''}
                                            ${modalTotalDays ? `
                                            <div class="spec-row">
                                                <span class="spec-label"><i class="bi bi-calendar-range me-2 text-secondary"></i>Total Days</span>
                                                <span class="spec-val fw-bold text-dark">${modalTotalDays} Days</span>
                                            </div>` : ''}
                                            ${booking.service_frequency ? `
                                            <div class="spec-row">
                                                <span class="spec-label"><i class="bi bi-arrow-repeat me-2 text-secondary"></i>Frequency</span>
                                                <span class="spec-val fw-bold text-dark">${booking.service_frequency}</span>
                                            </div>` : ''}
                                            ${booking.walk_duration ? `
                                            <div class="spec-row">
                                                <span class="spec-label"><i class="bi bi-stopwatch me-2 text-secondary"></i>Walk Duration</span>
                                                <span class="spec-val fw-bold text-dark">${booking.walk_duration}</span>
                                            </div>` : ''}
                                            ${booking.service_days ? `
                                            <div class="spec-row">
                                                <span class="spec-label"><i class="bi bi-calendar3 me-2 text-secondary"></i>Service Days</span>
                                                <span class="spec-val fw-semibold text-dark text-end" style="max-width: 180px;">${booking.service_days}</span>
                                            </div>` : ''}
                                            ${booking.extra_sessions ? `
                                            <div class="spec-row">
                                                <span class="spec-label"><i class="bi bi-plus-circle me-2 text-secondary"></i>Extra Sessions</span>
                                                <span class="spec-val fw-bold text-primary">${booking.extra_sessions}</span>
                                            </div>` : ''}
                                        </div>
                                    </div>
                                </div>

                                <!-- Packages & Addons Card -->
                                <div class="card border shadow-xs rounded-3 mb-4 bg-white">
                                    <div class="card-header bg-white border-bottom py-3 px-3.5 d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-box-seam-fill text-purple fs-5"></i>
                                            <h6 class="mb-0 fw-bold text-dark">Package & Add-ons</h6>
                                        </div>
                                        <span class="badge bg-purple bg-opacity-10 text-purple border border-purple border-opacity-25 rounded-pill px-2.5 py-1 tiny fw-semibold">
                                            Itemized
                                        </span>
                                    </div>
                                    <div class="card-body p-3.5">
                                        ${extrasHtml}
                                    </div>
                                </div>

                                <!-- Assigned Specialist Card -->
                                <div class="card border shadow-xs rounded-3 bg-white">
                                    <div class="card-header bg-white border-bottom py-3 px-3.5 d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-person-badge-fill text-info fs-5"></i>
                                            <h6 class="mb-0 fw-bold text-dark">Assigned Specialist</h6>
                                        </div>
                                        ${booking.provider_name && booking.provider_name !== '--' && !booking.provider_name.toLowerCase().includes('search') ? `<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2.5 py-1 tiny fw-semibold"><i class="bi bi-check-circle-fill me-1"></i>Assigned</span>` : `<span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-2.5 py-1 tiny fw-semibold"><i class="bi bi-hourglass-split me-1"></i>Pending</span>`}
                                    </div>
                                    <div class="card-body p-3.5">
                                        ${specialistHtml}
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Notes / Cancellation Alerts (if any) -->
                        ${(booking.comment || booking.cancel_reason) ? `
                        <div class="row g-3 mt-1">
                            ${booking.cancel_reason ? `
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 bg-danger bg-opacity-10 border border-danger border-opacity-25">
                                    <div class="d-flex align-items-center gap-2 mb-1 text-danger fw-bold small">
                                        <i class="bi bi-x-octagon-fill fs-6"></i> Cancellation Reason
                                    </div>
                                    <div class="text-dark small ps-4">${booking.cancel_reason}</div>
                                </div>
                            </div>` : ''}
                            ${booking.comment ? `
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 bg-primary bg-opacity-10 border border-primary border-opacity-25">
                                    <div class="d-flex align-items-center gap-2 mb-1 text-primary fw-bold small">
                                        <i class="bi bi-info-circle-fill fs-6"></i> Admin Comments
                                    </div>
                                    <div class="text-dark small ps-4">${booking.comment}</div>
                                </div>
                            </div>` : ''}
                        </div>` : ''}
                    </div>

                    <!-- 4. Modal Footer -->
                    <div class="modal-footer px-4 py-3 bg-white border-top d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 fw-medium text-dark" onclick="window.print();">
                                <i class="bi bi-printer me-1"></i>Print Summary
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold shadow-xs copy-details-btn" onclick="copyCurrentModalBooking(this)">
                                <i class="bi bi-clipboard-check me-1"></i>Copy Booking Details
                            </button>
                            ${(!booking.provider_name || booking.provider_name === '--' || booking.provider_name.toLowerCase().includes('search')) ? `
                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold shadow-sm" onclick="closeBookingDetails(); showNearbyProviders('${booking.id}', '${(booking.user_name || 'Customer').replace(/'/g, "\\'")}', '₹${booking.total_price || '0'}');">
                                <i class="bi bi-people-fill me-1"></i>Find Nearby Providers
                            </button>` : ''}
                        </div>
                        <button type="button" class="btn btn-sm btn-dark rounded-pill px-4 fw-semibold" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>

>>>>>>> origin/main
                </div>
            </div>

            <style>
<<<<<<< HEAD
                .modal {
                    z-index: 10000;
                    background-color: rgba(0, 0, 0, 0.7);
                }
                .modal-content {
                    background-color: white;
                    animation: modalSlideIn 0.3s ease-out;
                }
                
                @keyframes modalSlideIn {
                    from {
                        opacity: 0;
                        transform: translateY(-50px) scale(0.9);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0) scale(1);
                    }
                }
                
                .btn-close:hover {
                    background: rgba(0,0,0,0.3) !important;
                    transform: scale(1.1);
                }
            </style>
        `;

        document.body.insertAdjacentHTML('beforeend', modalContent);
        const modal = document.getElementById(modalId);

        modal.setAttribute('role', 'dialog');
        modal.setAttribute('aria-modal', 'true');
        modal.setAttribute('aria-labelledby', `${modalId}-title`);
        modal.style.display = 'flex';

        modalStack.push(modalId);

        modal.addEventListener('keydown', handleModalKeyDown);
        modal.addEventListener('click', handleModalClick);

        const modalContentEl = modal.querySelector('.modal-content');
        if (modalContentEl) {
            modalContentEl.setAttribute('tabindex', '-1');
            modalContentEl.focus();
        }

        modal._previousActiveElement = document.activeElement;

        // Add event listeners for close buttons
        const closeButton = modal.querySelector('.btn-close');
        const closeBtn = modal.querySelector('.btn-outline-secondary');

        if (closeButton) {
            closeButton.addEventListener('click', () => {
                closeModal(modalId);
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                closeModal(modalId);
            });
        }
=======
                .booking-details-dialog {
                    max-width: 1080px !important;
                    width: 95% !important;
                    margin: 1.5rem auto !important;
                }
                .custom-booking-modal {
                    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                    border-radius: 20px !important;
                    background: #f8fafc;
                }
                .shadow-2xl {
                    box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);
                }
                .shadow-xs {
                    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
                }
                .tiny { font-size: 0.72rem; }
                .fs-7 { font-size: 0.85rem; }
                .tracking-tight { letter-spacing: -0.02em; }
                .text-purple { color: #7c3aed !important; }
                .bg-purple { background-color: #7c3aed !important; }
                .border-purple { border-color: #7c3aed !important; }
                
                /* Header elements */
                .header-icon-box {
                    width: 44px; height: 44px;
                    border: 1px solid rgba(255, 255, 255, 0.15);
                }
                .copy-ref-btn {
                    font-size: 0.72rem;
                    transition: all 0.2s ease;
                }
                .copy-ref-btn:hover { background: rgba(255, 255, 255, 0.2); }

                /* KPI Status Bar */
                .kpi-label {
                    font-size: 0.65rem;
                    font-weight: 800;
                    color: #94a3b8;
                    letter-spacing: 0.05em;
                    display: block;
                }
                .status-pill-modern {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    padding: 3px 10px;
                    border-radius: 9999px;
                    font-size: 0.75rem;
                    font-weight: 700;
                    letter-spacing: 0.03em;
                }
                .status-pill-emerald { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
                .status-pill-amber { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
                .status-pill-blue { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
                .status-pill-purple { background: #faf5ff; color: #7e22ce; border: 1px solid #e9d5ff; }
                .status-pill-rose { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }

                .pulse-dot {
                    width: 7px; height: 7px; border-radius: 50%;
                    display: inline-block;
                    animation: pulseDot 1.8s infinite ease-in-out;
                }
                .dot-emerald { background: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25); }
                .dot-amber { background: #f59e0b; box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.25); }
                .dot-blue { background: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25); }
                .dot-purple { background: #a855f7; box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.25); }
                .dot-rose { background: #f43f5e; box-shadow: 0 0 0 3px rgba(244, 63, 94, 0.25); }

                @keyframes pulseDot {
                    0%, 100% { transform: scale(1); opacity: 1; }
                    50% { transform: scale(1.3); opacity: 0.6; }
                }

                .svc-tag-training { background: #fef3c7; color: #92400e; }
                .svc-tag-walking { background: #ccfbf1; color: #0f766e; }
                .svc-tag-grooming { background: #ede9fe; color: #5b21b6; }
                .svc-tag-sitting { background: #e0f2fe; color: #0369a1; }

                .price-currency { font-size: 1.1rem; font-weight: 800; }
                .price-value { font-size: 1.45rem; font-weight: 900; letter-spacing: -0.02em; }

                /* User Profile */
                .user-avatar-gradient {
                    width: 44px; height: 44px; border-radius: 12px;
                    background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%);
                    color: white; font-weight: 800; font-size: 1.2rem;
                    display: flex; align-items: center; justify-content: center;
                    box-shadow: 0 3px 8px rgba(67, 56, 202, 0.2);
                }

                /* Address Box */
                .address-subhead { font-size: 0.65rem; font-weight: 800; color: #64748b; letter-spacing: 0.05em; }
                .address-text { font-size: 0.83rem; font-weight: 600; color: #1e293b; line-height: 1.45; }

                /* Pet Card */
                .pet-avatar-box {
                    width: 46px; height: 46px; border-radius: 12px;
                    overflow: hidden; border: 2px solid #e2e8f0; background: #f1f5f9;
                    display: flex; align-items: center; justify-content: center;
                }
                .pet-avatar-img { width: 100%; height: 100%; object-fit: cover; }
                .pet-avatar-fallback {
                    width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
                    color: #94a3b8; font-size: 1.3rem;
                }
                .spec-tile-clean {
                    background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 6px 3px;
                }
                .spec-tile-head { font-size: 0.58rem; font-weight: 800; color: #64748b; display: block; margin-bottom: 2px; }
                .spec-tile-data { font-size: 0.78rem; font-weight: 700; color: #1e293b; display: block; }

                /* Schedule Card */
                .schedule-date-label { font-size: 0.6rem; font-weight: 800; display: block; margin-bottom: 2px; letter-spacing: 0.03em; }
                .schedule-date-value { font-size: 0.92rem; font-weight: 800; }

                .schedule-specs-list .spec-row {
                    display: flex; justify-content: space-between; align-items: center;
                    padding: 7px 0; border-bottom: 1px solid #f1f5f9; font-size: 0.82rem;
                }
                .schedule-specs-list .spec-row:last-child { border-bottom: none; }
                .spec-label { color: #64748b; }
                .spec-val { color: #1e293b; }

                /* Specialist Box */
                .specialist-avatar-circle {
                    width: 42px; height: 42px; border-radius: 12px; background: #ecfdf5;
                    display: flex; align-items: center; justify-content: center;
                    border: 1px solid #a7f3d0;
                }
            </style>
        </div>`;

        // Cleanup any existing booking modals and backdrops
        const oldModals = document.querySelectorAll('#bookingDetailsModal, .modal[id^="booking-modal-"]');
        oldModals.forEach(m => {
            const inst = bootstrap.Modal.getInstance(m);
            if (inst) inst.dispose();
            m.remove();
        });
        document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());

        $('body').append(modalContent);
        const modalEl = document.getElementById(modalId);
        const myModal = new bootstrap.Modal(modalEl);
        myModal.show();

        // Remove from DOM on close and clean up backdrops
        modalEl.addEventListener('hidden.bs.modal', function() {
            const inst = bootstrap.Modal.getInstance(this);
            if (inst) inst.dispose();
            this.remove();
            document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('padding-right');
            document.body.style.removeProperty('overflow');
        });
    }

    function closeBookingDetails() {
        const modal = document.getElementById('bookingDetailsModal') || document.querySelector(`.modal[id^="booking-modal-"]`);
        if (modal) {
            const myModal = bootstrap.Modal.getInstance(modal);
            if (myModal) myModal.hide();
            else modal.remove();
        }
        document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('padding-right');
        document.body.style.removeProperty('overflow');
>>>>>>> origin/main
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.remove();
            const index = modalStack.indexOf(modalId);
            if (index !== -1) modalStack.splice(index, 1);
        }
    }

<<<<<<< HEAD
    // Map to store booking data by ID
    const bookingMap = new Map();
    <?php foreach ($bookings as $booking): ?>
        bookingMap.set(<?= json_encode($booking->id) ?>, <?= json_encode($booking) ?>);
    <?php endforeach; ?>

        $(document).ready(function () {
            // Initialize pagination manager
            window.paginationManager = new PaginationManager();

            // Register events
            registerEventHandlers();
            initializeDatepickers();
            initializeAddressTooltips();

            // Search inputs (simplified reload)
            $('#searchInput, #dateFilter').on('keypress', function (e) {
                if (e.which == 13) applyFilters();
            });
        });
=======
    // Function to generate customized text for copying booking details based on service type
    function getFormattedBookingCopyText(booking) {
        if (!booking) return '';

        const formatCopyDate = (dateStr) => {
            if (!dateStr || dateStr === '0000-00-00' || dateStr === '--') return '';
            try {
                if (typeof dateStr === 'string') {
                    const match = dateStr.match(/^(\d{4})-(\d{2})-(\d{2})/);
                    if (match) {
                        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
                        const day = match[3];
                        const month = months[parseInt(match[2], 10) - 1];
                        const year = match[1];
                        return `${day} ${month} ${year}`;
                    }
                }
                const d = new Date(dateStr);
                if (isNaN(d.getTime())) return dateStr;
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
                const day = String(d.getDate()).padStart(2, '0');
                const month = months[d.getMonth()];
                const year = d.getFullYear();
                return `${day} ${month} ${year}`;
            } catch (e) {
                return dateStr;
            }
        };

        const formatPriceNumber = (val) => {
            if (!val || isNaN(val)) return '0';
            return Number(val).toLocaleString('en-IN', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });
        };

        // 1. Pet Breeds
        let breedsList = [];
        if (booking.pets && Array.isArray(booking.pets) && booking.pets.length > 0) {
            breedsList = booking.pets.map(p => p.breed || p.pet_breed).filter(b => b && b !== '--' && b !== 'null');
        }
        if (breedsList.length === 0 && (booking.breed || booking.pet_breeds)) {
            const raw = booking.pet_breeds || booking.breed;
            breedsList = (Array.isArray(raw) ? raw : String(raw).split(',')).map(b => b.trim()).filter(b => b && b !== '--' && b !== 'null');
        }
        const breedsText = breedsList.length > 0 ? breedsList.join(', ') : (booking.type || 'Indie');

        // 2. Pet Count
        let petCount = 1;
        if (booking.pets && Array.isArray(booking.pets) && booking.pets.length > 0) {
            petCount = booking.pets.length;
        } else if (booking.pet_names) {
            const pNames = String(booking.pet_names).split(',').filter(n => n.trim().length > 0);
            if (pNames.length > 0) petCount = pNames.length;
        }

        // 3. Dates & Days
        const startDateStr = formatCopyDate(booking.service_start_date || booking.booking_date || booking.created_at);
        let endDateStr = formatCopyDate(booking.service_end_date);

        const parseDateOnly = (dateStr) => {
            if (!dateStr || dateStr === '0000-00-00' || dateStr === '--') return null;
            if (typeof dateStr === 'string') {
                const match = dateStr.match(/^(\d{4})-(\d{2})-(\d{2})/);
                if (match) {
                    return new Date(Number(match[1]), Number(match[2]) - 1, Number(match[3]));
                }
            }
            const d = new Date(dateStr);
            return isNaN(d.getTime()) ? null : new Date(d.getFullYear(), d.getMonth(), d.getDate());
        };

        let days = null;
        const startObj = parseDateOnly(booking.service_start_date);
        const endObj = parseDateOnly(booking.service_end_date);

        // 1st priority: Calculate exact duration from service_start_date and service_end_date if both exist
        if (startObj && endObj) {
            const isWeekdays = String(booking.service_days || '').toLowerCase().trim() === 'weekdays';
            if (isWeekdays) {
                let count = 0;
                const cur = new Date(startObj.getTime());
                while (cur <= endObj) {
                    if (cur.getDay() !== 0) count++;
                    cur.setDate(cur.getDate() + 1);
                }
                days = count;
            } else {
                const diffTime = endObj.getTime() - startObj.getTime();
                if (diffTime >= 0) {
                    days = Math.round(diffTime / (1000 * 60 * 60 * 24)) + 1;
                }
            }
        }

        // 2nd priority: If days not derived from start/end dates, use explicit booking.days (e.g. Sitting / Boarding duration)
        if (!days && booking.days && !isNaN(parseInt(booking.days)) && parseInt(booking.days) > 0) {
            days = parseInt(booking.days);
        }

        // If end date is missing but we have start date and days, compute endDateStr
        if (!endDateStr && startObj && days) {
            const isWeekdays = String(booking.service_days || '').toLowerCase().trim() === 'weekdays';
            const computedEnd = new Date(startObj.getTime());
            if (isWeekdays) {
                let daysAdded = 1;
                while (daysAdded < days) {
                    computedEnd.setDate(computedEnd.getDate() + 1);
                    if (computedEnd.getDay() !== 0) daysAdded++;
                }
            } else {
                computedEnd.setDate(computedEnd.getDate() + days - 1);
            }
            const year = computedEnd.getFullYear();
            const month = String(computedEnd.getMonth() + 1).padStart(2, '0');
            const day = String(computedEnd.getDate()).padStart(2, '0');
            endDateStr = formatCopyDate(`${year}-${month}-${day}`);
        }

        let dateRangeText = startDateStr;
        if (endDateStr && endDateStr !== startDateStr) {
            dateRangeText = `${startDateStr} - ${endDateStr}`;
        }
        if (days) {
            dateRangeText += ` (${days}Days)`;
        }

        // 4. Preferred Time
        const prefTime = booking.preferable_time || booking.sp_timings || booking.booking_time || '--';

        // 5. Location String
        const rawTokens = [];
        if (booking.landmark_areaname && booking.landmark_areaname !== '--' && booking.landmark_areaname !== 'null') {
            rawTokens.push(...booking.landmark_areaname.split(','));
        } else if (booking.area && booking.area !== '--' && booking.area !== 'null') {
            rawTokens.push(...booking.area.split(','));
        } else if (booking.houseno_floor || booking.building_blockno || booking.address) {
            const fullAddr = [booking.houseno_floor, booking.building_blockno, booking.address].filter(Boolean).join(', ');
            rawTokens.push(...fullAddr.split(','));
        }

        if (booking.city && booking.city !== '--' && booking.city !== 'null') {
            rawTokens.push(booking.city);
        }
        if (booking.state && booking.state !== '--' && booking.state !== 'null' && (!booking.city || booking.city.toLowerCase() !== booking.state.toLowerCase())) {
            rawTokens.push(booking.state);
        }
        const pin = booking.pincode || booking.zip_code;
        if (pin && pin !== '--' && pin !== 'null') {
            rawTokens.push(pin);
        }

        const seenLoc = new Set();
        const uniqueLoc = [];
        for (let t of rawTokens) {
            t = String(t).trim();
            if (!t || t === '--' || t === '0' || t === 'null') continue;
            const lower = t.toLowerCase();
            if (!seenLoc.has(lower)) {
                seenLoc.add(lower);
                uniqueLoc.push(t);
            }
        }
        const locationText = uniqueLoc.join(', ') || booking.city || 'Location not specified';

        // 6. Community & Footer links
        const footerText = `For more information whatsapp - 8464825652\n\n📲 Apply here:\n👉 Android - https://play.google.com/store/apps/details?id=com.Petsfolio.employee\n\n | iOS - https://apps.apple.com/in/app/Petsfolio-master/id6747756338\n\nJoin community for latest update\n\nhttps://chat.whatsapp.com/CMS6gk7w3UU4wS5NJ5nx9h?mode`;

        // 7. Service Type Matching
        const sTypeLower = (booking.service_type || '').toLowerCase();
        const sNameLower = (booking.service_name || '').toLowerCase();
        const sId = Number(booking.service_id);

        let output = '';

        if (sTypeLower.includes('walk') || sNameLower.includes('walk') || sId === 4) {
            // Walking Service
            const frequency = booking.service_frequency || 'Twice a day';
            const walkDuration = booking.walk_duration || '30 min walk';
            const price = formatPriceNumber(booking.total_price || booking.final_payable || '0');
            const paymentDays = days || (booking.days ? parseInt(booking.days) : 30);

            let serviceDaysFormatted = '';
            if (booking.service_days && booking.service_days !== '--' && booking.service_days !== 'null') {
                const sDaysLower = String(booking.service_days).toLowerCase().trim();
                if (sDaysLower === 'all days') {
                    serviceDaysFormatted = 'Everyday';
                } else if (sDaysLower === 'weekdays') {
                    serviceDaysFormatted = 'Weekdays';
                } else {
                    serviceDaysFormatted = String(booking.service_days).split(',').map(d => {
                        const tr = d.trim();
                        return tr.charAt(0).toUpperCase() + tr.slice(1);
                    }).join(', ');
                }
            }
            const serviceDaysLine = serviceDaysFormatted ? `Service days : ${serviceDaysFormatted}\n` : '';

            output = `Pet breeds : ${breedsText}\n` +
                `Service name : Walking\n` +
                `No of Dogs :${petCount}\n` +
                `Booking date : ${dateRangeText}\n` +
                serviceDaysLine +
                `Service frequency : ${frequency}\n` +
                `Walk duration : ${walkDuration}\n` +
                `Preferred Time : ${prefTime}\n` +
                `Payment : *₹${price} for ${paymentDays}days*\n` +
                `Location : ${locationText}\n\n` +
                footerText;

        } else if (sTypeLower.includes('sit') || sNameLower.includes('sit') || sId === 6 || sTypeLower.includes('board') || sNameLower.includes('board') || (sId === 1 && sNameLower.includes('board'))) {
            // Sitting / Boarding Service
            let sittingFrequency = booking.service_frequency;
            if (!sittingFrequency || sittingFrequency === '--' || sittingFrequency === 'null') {
                if (booking.duration_days) {
                    sittingFrequency = `${booking.duration_days}hrs${booking.package_name ? ` (${booking.package_name})` : ''}`;
                } else {
                    sittingFrequency = booking.package_name || '24hrs (Over night )';
                }
            }

            // Show package & add-ons with hours
            let sittingPackageAddons = [];
            if (booking.package_name && booking.package_name !== sittingFrequency) {
                const pkgHours = booking.duration_days ? ` (${booking.duration_days} hrs)` : '';
                sittingPackageAddons.push(`${booking.package_name}${pkgHours}`);
            }
            if (booking.addons && Array.isArray(booking.addons) && booking.addons.length > 0) {
                booking.addons.forEach(a => {
                    const name = a.addon || a.name || 'Add-on';
                    const hours = a.hours || a.duration || '';
                    sittingPackageAddons.push(hours ? `${name} (${hours} hrs)` : name);
                });
            }

            let packageAddonsLine = '';
            if (sittingPackageAddons.length > 0) {
                packageAddonsLine = `Package & Add-ons : ${sittingPackageAddons.join(', ')}\n`;
            }

            const price = formatPriceNumber(booking.total_price || booking.final_payable || '0');
            const paymentDays = days || (booking.days ? parseInt(booking.days) : 10);

            output = `Pet breeds : ${breedsText}\n` +
                `Service name : Pet sitting \n` +
                `No of Dogs :${petCount}\n` +
                `Booking date : ${dateRangeText}\n` +
                `Service frequency : ${sittingFrequency}\n` +
                packageAddonsLine +
                `Preferred Time : ${prefTime}\n` +
                `Payment : *₹${price} for ${paymentDays}days*\n` +
                `Location : ${locationText}\n\n` +
                footerText;

        } else if (sTypeLower.includes('train') || sNameLower.includes('train') || sId === 1 || (sId === 3 && sNameLower.includes('train'))) {
            // Training Service
            let packageName = booking.package_name || 'Behaviour  Training';
            if (booking.packages && Array.isArray(booking.packages) && booking.packages.length > 0 && (!booking.package_name || booking.package_name === '--')) {
                packageName = booking.packages[0].package_name || packageName;
            }

            output = `Pet Breeds: ${breedsText}\n` +
                `Service Name: Training\n` +
                `Booking Date: ${startDateStr}\n` +
                `Package Name: ${packageName}\n` +
                `Preferable Time: ${prefTime}\n` +
                `Location : ${locationText}\n\n` +
                footerText;

        } else if (sTypeLower.includes('groom') || sNameLower.includes('groom') || sId === 2 || (sId === 3 && sNameLower.includes('groom'))) {
            // Grooming Service
            output = `Pet breeds : ${breedsText}\n` +
                `Service name : Grooming\n` +
                `No of Dogs :${petCount}\n` +
                `Booking date : ${startDateStr}\n` +
                `Preferred Time : ${prefTime}\n` +
                `Location : ${locationText}\n\n` +
                footerText;

        } else {
            // Fallback
            const serviceName = booking.service_name || 'Pet Service';
            const price = formatPriceNumber(booking.total_price || booking.final_payable || '0');
            const paymentDays = days || (booking.days ? parseInt(booking.days) : 1);

            output = `Pet breeds : ${breedsText}\n` +
                `Service name : ${serviceName}\n` +
                `No of Dogs :${petCount}\n` +
                `Booking date : ${dateRangeText}\n` +
                `Preferred Time : ${prefTime}\n` +
                `Payment : *₹${price} for ${paymentDays}days*\n` +
                `Location : ${locationText}\n\n` +
                footerText;
        }

        return output;
    }

    function copyCurrentModalBooking(btn) {
        if (!window._currentModalBooking) {
            console.error('No current modal booking available');
            return;
        }
        copyBookingDetailsFormatted(btn, window._currentModalBooking);
    }

    function copyBookingDetailsFormatted(btn, booking) {
        if (!booking) return;
        const textToCopy = getFormattedBookingCopyText(booking);

        const copyToClipboard = (str) => {
            if (navigator.clipboard && window.isSecureContext) {
                return navigator.clipboard.writeText(str);
            } else {
                const textArea = document.createElement("textarea");
                textArea.value = str;
                textArea.style.position = "fixed";
                textArea.style.left = "-999999px";
                textArea.style.top = "-999999px";
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                return new Promise((res, rej) => {
                    document.execCommand('copy') ? res() : rej(new Error('Copy command failed'));
                    textArea.remove();
                });
            }
        };

        copyToClipboard(textToCopy).then(() => {
            const $btn = $(btn);
            const origHtml = $btn.html();
            $btn.removeClass('btn-outline-primary btn-outline-dark btn-light').addClass('btn-success text-white');
            $btn.html('<i class="bi bi-check2-circle me-1"></i>Copied Details!');
            setTimeout(() => {
                $btn.html(origHtml);
                $btn.removeClass('btn-success text-white').addClass('btn-outline-primary');
            }, 2500);
        }).catch(err => {
            console.error('Failed to copy: ', err);
            alert('Failed to copy booking details to clipboard.');
        });
    }

    // Map to store booking data by ID
    const bookingMap = new Map();
    <?php if (!empty($bookings)): ?>
        <?php foreach ($bookings as $booking): ?>
            bookingMap.set(String(<?= json_encode($booking->id) ?>), <?= json_encode($booking) ?>);
            bookingMap.set(Number(<?= json_encode($booking->id) ?>), <?= json_encode($booking) ?>);
        <?php endforeach; ?>
    <?php endif; ?>

    // Global helper to show booking details from any trigger element
    let _openingBookingModalTimer = null;

    function showBookingDetailsFromElem(elem) {
        if (!elem) return;
        if (_openingBookingModalTimer) return;
        _openingBookingModalTimer = setTimeout(() => {
            _openingBookingModalTimer = null;
        }, 500);

        let bookingData = null;
        if (elem.dataset && elem.dataset.booking) {
            try {
                bookingData = JSON.parse(elem.dataset.booking);
            } catch (e) {
                console.error("Error parsing booking dataset JSON", e);
            }
        }
        if (!bookingData) {
            const id = elem.getAttribute('data-booking-id') || $(elem).data('booking-id');
            if (id !== undefined && id !== null) {
                bookingData = bookingMap.get(id) || bookingMap.get(String(id)) || bookingMap.get(Number(id));
            }
        }
        if (bookingData) {
            showBookingDetails(bookingData);
        } else {
            console.warn("Booking data not found for element:", elem);
        }
    }

    $(document).ready(function() {
        // Clear search input when page loads
        const searchInputEl = document.getElementById('searchInput');
        if (searchInputEl) searchInputEl.value = '';

        // Initialize pagination manager
        if (typeof PaginationManager !== 'undefined') {
            window.paginationManager = new PaginationManager();
        }

        // Register events
        registerEventHandlers();
        initializeDatepickers();
        initializeAddressTooltips();

        // Search inputs (simplified reload)
        $('#searchInput, #dateFilter').on('keypress', function(e) {
            if (e.which == 13) applyFilters();
        });
    });
>>>>>>> origin/main

    function applyFilters() {
        const search = $('#searchInput').val();
        const date = $('#dateFilter').val();
        let url = new URL(window.location.href);
<<<<<<< HEAD
        if (search) url.searchParams.set('search', search); else url.searchParams.delete('search');
        if (date) url.searchParams.set('date', date); else url.searchParams.delete('date');
=======
        if (search) url.searchParams.set('search', search);
        else url.searchParams.delete('search');
        if (date) url.searchParams.set('date', date);
        else url.searchParams.delete('date');
>>>>>>> origin/main
        url.searchParams.set('page', 1); // Reset to first page
        window.location.href = url.toString();
    }

    function clearFilters() {
        let url = new URL(window.location.href);
        url.searchParams.delete('search');
        url.searchParams.delete('date');
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }

    function buildCommentSelect(data, bookingId, serviceId) {
        if (!data) {
            return `<div class="text-center">
<<<<<<< HEAD
                <select class="form-control form-control-sm booking-comment" data-booking-id="${bookingId}" data-service-id="${serviceId}">
=======
                <select class="form-control form-control-sm booking-comment" 
                        data-booking-id="${bookingId}" 
                        data-service-id="${serviceId}">
>>>>>>> origin/main
                    <option value="">Select comment</option>
                    <option value="Not interested">Not interested</option>
                    <option value="Will book later">Will book later</option>
                    <option value="Followup">Followup</option>
                    <option value="Bad service">Bad service</option>
                </select>
                <div class="comment-status small mt-1 text-center"></div>
            </div>`;
        }
        return `<div class="text-center">${data}</div>`;
    }

    function initializeDatepickers() {
<<<<<<< HEAD
        $(".datepicker:not([data-dp-initialized])").each(function () {
            $(this).attr('data-dp-initialized', '1').datepicker({
                dateFormat: "yy-mm-dd",
                minDate: 1
            });
        });
=======
        if (typeof $.fn.datepicker === 'undefined') {
            $('head').append('<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">');
            $('<style>#ui-datepicker-div { z-index: 99999 !important; }</style>').appendTo('head');
            $.getScript("https://code.jquery.com/ui/1.13.2/jquery-ui.min.js", function() {
                initDP();
            });
        } else {
            if (!$('#datepicker-zindex-fix').length) {
                $('<style id="datepicker-zindex-fix">#ui-datepicker-div { z-index: 99999 !important; }</style>').appendTo('head');
            }
            initDP();
        }

        function initDP() {
            $(".datepicker:not([data-dp-initialized])").each(function() {
                $(this).attr('data-dp-initialized', '1').datepicker({
                    dateFormat: "yy-mm-dd",
                    minDate: 1
                });
            });
        }
>>>>>>> origin/main
    }

    // All delegated handlers registered ONCE
    function registerEventHandlers() {
        // Datepicker change
<<<<<<< HEAD
        $(document).on('change', '.datepicker', function () {
=======
        $(document).on('change', '.datepicker', function() {
>>>>>>> origin/main
            var date = $(this).val();
            var bookingId = $(this).data('booking-id');
            var serviceId = $(this).data('service-id');

            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to update the booking date?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, update it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('repostBooking') ?>",
                        data: {
                            'start_date': date,
                            'booking_id': bookingId,
                            'service_id': serviceId
                        },
<<<<<<< HEAD
                        success: function (data) {
=======
                        success: function(data) {
>>>>>>> origin/main
                            Swal.fire({
                                title: "Success!",
                                text: "Booking date updated successfully.",
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });
                            setTimeout(() => location.reload(), 2000);
                        },
<<<<<<< HEAD
                        error: function () {
=======
                        error: function() {
>>>>>>> origin/main
                            Swal.fire({
                                title: "Error!",
                                text: "Something went wrong while updating.",
                                icon: "error",
                                confirmButtonText: "Okay"
                            });
                        }
                    });
                }
            });
        });

        // Nearby providers
<<<<<<< HEAD
        $(document).on('click', '.nearby-providers-btn', function () {
            const bookingId = $(this).data('booking-id');
            const customerName = $(this).data('customer-name') || 'Customer';
            const targetPrice = $(this).data('target-price') || '₹0';
=======
        $(document).on('click', '.nearby-providers-btn, .view-providers-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const bookingId = $(this).data('booking-id') || $(this).attr('data-booking-id');
            const customerName = $(this).data('customer-name') || $(this).attr('data-customer-name') || 'Customer';
            const targetPrice = $(this).data('target-price') || $(this).attr('data-target-price') || '₹0';
>>>>>>> origin/main
            showNearbyProviders(bookingId, customerName, targetPrice);
        });

        // Comment change
<<<<<<< HEAD
        $(document).on('change', '.booking-comment', function () {
=======
        $(document).on('change', '.booking-comment', function() {
>>>>>>> origin/main
            var $select = $(this);
            var bookingId = $select.data('booking-id');
            var comment = $select.val();
            var $statusElement = $select.next('.comment-status');
            var serviceId = $select.data('service-id');

            if (!comment) {
                $statusElement.text('Please select a comment').show().addClass('text-danger');
                $select.addClass('is-invalid');
                return;
            }

            $statusElement.text('Saving...').show().addClass('text-info');

            $.ajax({
                url: "<?= base_url('/updateComment') ?>",
                method: "POST",
                data: {
                    booking_id: bookingId,
                    comment: comment,
                    service_id: serviceId
                },
<<<<<<< HEAD
                success: function (response) {
                    if (response.success) {
                        $statusElement.text('Saved!').removeClass('text-info').addClass('text-success');
                        $select.removeClass('is-invalid');
                        setTimeout(function () {
=======
                success: function(response) {
                    if (response.success) {
                        $statusElement.text('Saved!').removeClass('text-info').addClass('text-success');
                        $select.removeClass('is-invalid');
                        setTimeout(function() {
>>>>>>> origin/main
                            $statusElement.fadeOut();
                        }, 2000);
                    } else {
                        $statusElement.text(response.message).removeClass('text-info').addClass('text-danger');
                    }
                },
<<<<<<< HEAD
                error: function () {
=======
                error: function() {
>>>>>>> origin/main
                    $statusElement.text('Request failed').removeClass('text-info').addClass('text-danger');
                }
            });
        });

        // Booking details modal
<<<<<<< HEAD
        $(document).on('click', '.show-booking-details', function () {
            var bookingId = $(this).data('booking-id');
            var bookingData = bookingMap.get(bookingId);
            if (bookingData) showBookingDetails(bookingData);
=======
        $(document).on('click', '.show-booking-details, .booking-details-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            showBookingDetailsFromElem(this);
>>>>>>> origin/main
        });
    }

    function initializeAddressTooltips() {
<<<<<<< HEAD
        requestAnimationFrame(function () {
=======
        if (typeof tippy === 'undefined') return;
        requestAnimationFrame(function() {
>>>>>>> origin/main
            const addressCells = document.querySelectorAll('.address-tooltip:not([data-tippy-initialized])');
            addressCells.forEach(cell => {
                cell.setAttribute('data-tippy-initialized', '1');
                tippy(cell, {
                    content: cell.getAttribute('title'),
                    placement: 'top',
                    animation: 'shift-away',
                    theme: 'light'
                });
            });
        });
    }
<<<<<<< HEAD
=======

    function exportBookingsToCSV() {
        const table = document.getElementById('bookingsTable');
        if (!table) return;
        const rows = Array.from(table.querySelectorAll('tbody tr.booking-row'));
        if (!rows.length) {
            Swal.fire('Notice', 'No booking records to export.', 'info');
            return;
        }

        const isHalted = <?= $isHalted ? 'true' : 'false' ?>;
        const isDeleted = <?= $isDeleted ? 'true' : 'false' ?>;
        const isCancelled = <?= $isCancelled ? 'true' : 'false' ?>;

        let headers = [];
        if (isDeleted) {
            headers = ['S.No.', 'Customer Name', 'Phone', 'Location', 'Pet Details', 'Service', 'Price', 'Status', 'Deleted At', 'Comment', 'Repost'];
        } else if (isCancelled) {
            headers = ['S.No.', 'Customer Name', 'Phone', 'Location', 'Pet Details', 'Service', 'Price', 'Provider', 'Status', 'Reason', 'Comment', 'Repost'];
        } else if (isHalted) {
            headers = ['S.No.', 'Customer Name', 'Phone', 'Location', 'Pet Details', 'Service', 'Price', 'Status', 'Quotes', 'Service Date'];
        } else {
            // Active / Completed
            headers = ['S.No.', 'Customer Name', 'Phone', 'Location', 'Pet Details', 'Service', 'Price', 'Service Date', 'Created Date', 'Confirmed Date', 'Provider', 'Status'];
        }

        const csvRows = [headers.join(',')];

        let exportIndex = 1;
        rows.forEach((row) => {
            if (row.style.display === 'none') return;
            const cells = row.querySelectorAll('td');
            if (cells.length < 5) return;

            const sNo = exportIndex++;
            const customerName = (cells[1].querySelector('.fw-bold')?.innerText || '').replace(/"/g, '""').trim();
            const phone = (cells[1].querySelector('a[href^="tel:"]')?.innerText || '').replace(/"/g, '""').trim();
            const location = (cells[1].querySelector('.text-truncate')?.innerText || '').replace(/"/g, '""').trim();
            const pet = (cells[2].querySelector('.fw-bold')?.innerText || '').replace(/"/g, '""').trim();
            const service = (cells[3].querySelector('.badge-service')?.innerText || cells[3]?.innerText || '').replace(/"/g, '""').trim();
            const price = (cells[4].innerText || '').replace(/[₹,]/g, '').trim();

            if (isDeleted) {
                const status = (cells[5]?.innerText || '').replace(/"/g, '""').trim();
                const deletedAt = (cells[6]?.innerText || '').replace(/"/g, '""').trim();
                const comment = (cells[7]?.querySelector('select')?.value || cells[7]?.innerText || '').replace(/"/g, '""').trim();
                const repost = (cells[8]?.querySelector('input')?.value || '').replace(/"/g, '""').trim();
                csvRows.push(`"${sNo}","${customerName}","${phone}","${location}","${pet}","${service}","${price}","${status}","${deletedAt}","${comment}","${repost}"`);
            } else if (isCancelled) {
                const provider = (cells[5]?.innerText || '').replace(/\n/g, ' ').replace(/"/g, '""').trim();
                const status = (cells[6]?.innerText || '').replace(/"/g, '""').trim();
                const reason = (cells[7]?.innerText || '').replace(/"/g, '""').trim();
                const comment = (cells[8]?.querySelector('select')?.value || cells[8]?.innerText || '').replace(/"/g, '""').trim();
                const repost = (cells[9]?.querySelector('input')?.value || '').replace(/"/g, '""').trim();
                csvRows.push(`"${sNo}","${customerName}","${phone}","${location}","${pet}","${service}","${price}","${provider}","${status}","${reason}","${comment}","${repost}"`);
            } else if (isHalted) {
                const status = (cells[5]?.innerText || '').replace(/"/g, '""').trim();
                const quotes = (cells[6]?.innerText || '').replace(/"/g, '""').trim();
                const serviceDate = (cells[7]?.innerText || '').replace(/"/g, '""').trim();
                csvRows.push(`"${sNo}","${customerName}","${phone}","${location}","${pet}","${service}","${price}","${status}","${quotes}","${serviceDate}"`);
            } else {
                // Active / Completed
                const serviceDate = (cells[5]?.innerText || '').replace(/\n/g, ' ').replace(/"/g, '""').trim();
                const createdDate = (cells[6]?.innerText || '').replace(/\n/g, ' ').replace(/"/g, '""').trim();
                const confirmedDate = (cells[7]?.innerText || '').replace(/\n/g, ' ').replace(/"/g, '""').trim();
                const provider = (cells[8]?.innerText || '').replace(/\n/g, ' ').replace(/"/g, '""').trim();
                const status = (cells[9]?.querySelector('.status-pill')?.innerText || cells[9]?.innerText || '').trim();
                csvRows.push(`"${sNo}","${customerName}","${phone}","${location}","${pet}","${service}","${price}","${serviceDate}","${createdDate}","${confirmedDate}","${provider}","${status}"`);
            }
        });

        if (csvRows.length === 1) {
            Swal.fire('Notice', 'No visible booking records matching current filters.', 'info');
            return;
        }

        const blob = new Blob([csvRows.join('\n')], {
            type: 'text/csv;charset=utf-8;'
        });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.setAttribute('href', url);
        link.setAttribute('download', `bookings_${new Date().toISOString().slice(0, 10)}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // ==========================================
    // Bulk Notifications for Halted Bookings JS
    // ==========================================
    function toggleSelectAllHalted(masterCheckbox) {
        const checkboxes = document.querySelectorAll('.halted-booking-checkbox');
        checkboxes.forEach(cb => {
            const row = cb.closest('.booking-row');
            // Check if visible
            if (row && row.style.display !== 'none') {
                cb.checked = masterCheckbox.checked;
            }
        });
        updateHaltedSelectionCount();
    }

    function updateHaltedSelectionCount() {
        const visibleCheckboxes = Array.from(document.querySelectorAll('.halted-booking-checkbox')).filter(cb => {
            const row = cb.closest('.booking-row');
            return row && row.style.display !== 'none';
        });
        const checkedBoxes = Array.from(document.querySelectorAll('.halted-booking-checkbox:checked')).filter(cb => {
            const row = cb.closest('.booking-row');
            return row && row.style.display !== 'none';
        });

        const count = checkedBoxes.length;
        const total = visibleCheckboxes.length;

        // Update badge on the header action button
        const badge = document.getElementById('haltedSelectedCountBadge');
        if (badge) {
            badge.innerText = count;
            badge.style.display = count > 0 ? 'inline-block' : 'none';
        }

        // Master checkbox state
        const master = document.getElementById('selectAllHaltedBookings');
        if (master) {
            master.indeterminate = (count > 0 && count < total);
            master.checked = (total > 0 && count === total);
        }

        // Highlight selected rows
        visibleCheckboxes.forEach(cb => {
            const row = cb.closest('.booking-row');
            if (row) {
                if (cb.checked) {
                    row.classList.add('table-primary');
                } else {
                    row.classList.remove('table-primary');
                }
            }
        });
    }

    function getSelectedHaltedBookingIds() {
        const checked = Array.from(document.querySelectorAll('.halted-booking-checkbox:checked')).filter(cb => {
            const row = cb.closest('.booking-row');
            return row && row.style.display !== 'none';
        });
        return checked.map(cb => cb.value);
    }

    function openBulkNotificationModal() {
        let selectedIds = getSelectedHaltedBookingIds();

        // If none explicitly checked, prompt to select all visible halted bookings or ask user
        if (selectedIds.length === 0) {
            const allVisibleHalted = Array.from(document.querySelectorAll('.halted-booking-checkbox')).filter(cb => {
                const row = cb.closest('.booking-row');
                return row && row.style.display !== 'none';
            });

            if (allVisibleHalted.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Halted Bookings',
                    text: 'There are no halted bookings available to notify.',
                    confirmButtonColor: '#4338ca'
                });
                return;
            }

            Swal.fire({
                title: 'No Bookings Checked',
                text: `Would you like to select all ${allVisibleHalted.length} visible halted booking(s) for notification broadcast?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4338ca',
                cancelButtonColor: '#64748b',
                confirmButtonText: `Yes, select all (${allVisibleHalted.length})`,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    allVisibleHalted.forEach(cb => cb.checked = true);
                    updateHaltedSelectionCount();
                    showBulkNotificationModalInstance();
                }
            });
            return;
        }

        showBulkNotificationModalInstance();
    }

    function showBulkNotificationModalInstance() {
        const selectedIds = getSelectedHaltedBookingIds();
        $('#bulkModalSelectedCount').text(selectedIds.length);

        let summaryText = selectedIds.length <= 5 ?
            'IDs: #' + selectedIds.join(', #') :
            'IDs: #' + selectedIds.slice(0, 5).join(', #') + ' and ' + (selectedIds.length - 5) + ' more';
        $('#bulkModalBookingsSummary').text(summaryText);

        const modalEl = document.getElementById('bulkNotificationModal');
        if (modalEl) {
            const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
            modalInstance.show();
        }
    }

    function applyBulkTemplate(type) {
        const titleInput = document.getElementById('bulkNotifTitle');
        const bodyInput = document.getElementById('bulkNotifMessage');
        if (!titleInput || !bodyInput) return;

        if (type === 'reactivate') {
            titleInput.value = 'Reactivate Booking - Petsfolio';
            bodyInput.value = 'A service request in your area is currently awaiting confirmation. Open the Petsfolio app to reactivate your booking and take action now.';
        } else if (type === 'client_update') {
            titleInput.value = 'Reactivate Booking - Petsfolio';
            bodyInput.value = 'Your service request is ready to be reactivated! Open the Petsfolio app to reactivate your booking and resume service.';
        } else if (type === 'awaiting') {
            titleInput.value = 'Reactivate Booking - Petsfolio';
            bodyInput.value = 'A service request in your area is currently awaiting confirmation. Open the Petsfolio app to reactivate your booking and take action now.';
        } else if (type === 'clear') {
            titleInput.value = '';
            bodyInput.value = '';
        }
    }

    function submitBulkNotifications() {
        const selectedIds = getSelectedHaltedBookingIds();
        if (selectedIds.length === 0) {
            Swal.fire('No Bookings', 'Please select at least one halted booking.', 'warning');
            return;
        }

        const targetAudience = $('#targetAudience').val() || 'clients';
        const customTitle = $('#bulkNotifTitle').val().trim();
        const customMessage = $('#bulkNotifMessage').val().trim();
        const radiusKm = 25;

        const btn = document.getElementById('btnSendBulkNotificationsSubmit');
        const originalHtml = btn ? btn.innerHTML : '';
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Sending Notifications...`;
        }

        $.ajax({
            url: '<?= base_url("bookings/sendBulkNotifications") ?>',
            method: 'POST',
            data: {
                booking_ids: selectedIds,
                target_audience: targetAudience,
                title: customTitle,
                message: customMessage,
                radius_km: radiusKm
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(response) {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }
                if (response && response.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Broadcast Complete!',
                        text: response.message || 'Bulk notifications sent successfully.',
                        confirmButtonColor: '#4338ca'
                    }).then(() => {
                        const modalEl = document.getElementById('bulkNotificationModal');
                        if (modalEl) {
                            const modalInstance = bootstrap.Modal.getInstance(modalEl);
                            if (modalInstance) modalInstance.hide();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Notice',
                        text: (response && response.message) ? response.message : 'No notifications were delivered. Verify token availability.',
                        confirmButtonColor: '#4338ca'
                    });
                }
            },
            error: function(xhr, status, error) {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }
                console.error('Bulk notification error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while sending bulk notifications. Please try again.',
                    confirmButtonColor: '#d33'
                });
            }
        });
    }
>>>>>>> origin/main
</script>

<?php echo $this->endSection() ?>