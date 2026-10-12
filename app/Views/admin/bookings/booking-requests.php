<?php echo $this->extend('admin/includes/header'); ?>
<!--start content-->

<?php echo $this->section('content'); ?>

<<<<<<< HEAD
<main class="main-content">
    <div class="position-relative iq-banner">
        <?php echo $this->include('admin/nav_bar') ?>
    </div>

    <div class="conatiner-fluid content-inner pb-0" id="page_layout">
        <!-- Modern Header Section -->
        <div class="container-fluid p-0 mb-4">
            <div class="bg-gradient-primary text-white p-5 rounded-3 shadow position-relative overflow-hidden">
                <div class="position-relative z-1">
                    <h1 class="h2 fw-bold mb-2 text-white">Bookings Management</h1>
                    <p class="text-white-75 mb-0">Track and manage service requests, assign providers, and monitor
                        status updates</p>
                </div>
                <i class="bi bi-calendar-check position-absolute end-0 bottom-0 opacity-10"
                    style="font-size: 14rem; transform: rotate(12deg); margin-right: -3rem; margin-bottom: -3rem;"></i>
            </div>
        </div>

        <?php
        $bookingsList = $bookings ?? [];
        $allRequestsCount = count($bookingsList);
        $extendRequestsCount = 0;
        $normalRequestsCount = 0;
        foreach ($bookingsList as $b) {
            if (strtolower($b->type ?? '') === 'extend') {
                $extendRequestsCount++;
            } else {
                $normalRequestsCount++;
            }
        }
        ?>

        <!-- Tab Navigation -->
        <div class="card border-0 shadow-sm mb-4 rounded-3">
            <div class="card-body p-2">
                <ul class="nav nav-pills nav-fill" id="requestTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active rounded-pill py-2 fw-bold" href="<?= base_url('bookingRequests') ?>">
                            <i class="bi bi-list-task me-2"></i>Booking Requests (<?= $allRequestsCount ?>)
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link rounded-pill py-2 fw-bold text-dark" href="<?= base_url('premiumRequests') ?>">
                            <i class="bi bi-star-fill me-2"></i>Premium Requests
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Search and Filter Card -->
        <div class="card border-0 shadow-sm mb-2 rounded-3">
            <div class="card-body p-3">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-black border-end-0 text-white">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" placeholder="Search bookings..."
                                id="searchBookings">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2 mb-md-0">
                            <label class="text-muted small me-2 text-nowrap">Type</label>
                            <select class="form-select form-select-sm" id="typeFilter">
                                <option value="all">All Requests (<?= $allRequestsCount ?>)</option>
                                <option value="normal">Normal Requests (<?= $normalRequestsCount ?>)</option>
                                <option value="extend">Extend Requests (<?= $extendRequestsCount ?>)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
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
                    <div class="col-md-3 d-flex gap-2">
                        <button class="btn btn-black bg-black p-2 text-white w-50" onclick="applyFilters()">
                            <i class="bi bi-funnel me-1"></i>Filter
                        </button>
                        <button class="btn btn-black bg-black p-2 text-white w-50" onclick="clearFilters()">
                            <i class="bi bi-x-circle me-1"></i>Clear
                        </button>
                    </div>
                </div>

                <!-- Quick Filter Pills -->
                <div class="d-flex flex-wrap align-items-center gap-2 mt-3 pt-2 border-top">
                    <span class="text-muted small me-1">Quick Filter:</span>
                    <button type="button" class="btn btn-sm type-filter-btn btn-dark rounded-pill px-3 py-1 fw-semibold active"
                        data-type="all" style="font-size: 0.8rem;">
                        All Requests <span class="badge bg-secondary ms-1"><?= $allRequestsCount ?></span>
                    </button>
                    <button type="button" class="btn btn-sm type-filter-btn btn-outline-primary rounded-pill px-3 py-1 fw-semibold"
                        data-type="normal" style="font-size: 0.8rem;">
                        Normal Requests <span class="badge bg-primary ms-1"><?= $normalRequestsCount ?></span>
                    </button>
                    <button type="button" class="btn btn-sm type-filter-btn btn-outline-success rounded-pill px-3 py-1 fw-semibold"
                        data-type="extend" style="font-size: 0.8rem;">
                        <i class="bi bi-arrow-repeat me-1 text-success"></i>Extend Requests <span class="badge bg-success ms-1"><?= $extendRequestsCount ?></span>
                    </button>
                </div>
            </div>
        </div>
        <div class="card border-0 shadow-sm overflow-hidden rounded-3">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="bookingsTable">
                    <thead class="" style="background: #c6d2ff !important;">
                        <tr>
                            <th class=" py-3 text-black  fw-semibold border-bottom text-center fs-6"
                                style="background: #c6d2ff !important;">Customer</th>
                            <th class="py-3 text-black  fw-semibold border-bottom d-none d-lg-table-cell text-center  fs-6"
                                style="background: #c6d2ff !important;">Pet Details</th>
                            <th class="py-3 text-black  fw-semibold border-bottom text-center fs-6"
                                style="background: #c6d2ff !important;">Service Date</th>
                            <th class="py-3 text-black  fw-semibold border-bottom text-center fs-6"
                                style="background: #c6d2ff !important;">No. of Quotes</th>
                            <th class="py-3 text-black  fw-semibold border-bottom text-center fs-6"
                                style="background: #c6d2ff !important;">Price</th>
                            <th class="py-3 text-black  fw-semibold border-bottom d-none d-sm-table-cell text-center fs-6"
                                style="background: #c6d2ff !important;">Provider</th>
                            <th class="py-3 text-black  fw-semibold border-bottom text-center fs-6"
                                style="background: #c6d2ff !important;">Source</th>
                            <th class="py-3 text-black  fw-semibold border-bottom text-center fs-6"
                                style="background: #c6d2ff !important; width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0" id="tableBody">

                        <?php
                        // Helper function to get service color
                        function getServiceColor($serviceName)
                        {
                            $serviceName = strtolower($serviceName);
                            $colors = [
                                'boarding' => 'linear-gradient(135deg, #d4e2ff, #d4e2ff)',
                                'walking' => 'linear-gradient(135deg, #d4e2ff, #d4e2ff)',
                                'grooming' => 'linear-gradient(135deg, #d4e2ff, #d4e2ff)',
                                'sitting' => 'linear-gradient(135deg, #d4e2ff, #d4e2ff)',
                                'training' => 'linear-gradient(135deg, #d4e2ff, #d4e2ff)',
                                'default' => 'linear-gradient(135deg, #d4e2ff, #d4e2ff)'
                            ];

                            foreach ($colors as $key => $color) {
                                if (strpos($serviceName, $key) !== false) {
                                    return $color;
                                }
                            }
                            return $colors['default'];
                        }

                        // Helper function to get days left style
                        function getDaysLeftStyle($daysLeft, $serviceType)
                        {
                            $serviceType = strtolower($serviceType);

                            if ($daysLeft <= 0) {
                                // Expired - Red theme for all services
                                return [
                                    'bg' => 'linear-gradient(135deg, #ffb1b1, #ffb1b1)',
                                    'color' => 'red',
                                    'icon' => 'bi-exclamation-triangle'
                                ];
                            } elseif ($daysLeft <= 3) {
                                // Urgent - Warning theme
                                if (strpos($serviceType, 'boarding') !== false) {
                                    return [
                                        'bg' => 'linear-gradient(135deg, #ffedbe, #ffedbe)',
                                        'color' => '#ffb700',
                                        'icon' => 'bi-clock-fill'
                                    ];
                                } elseif (strpos($serviceType, 'walking') !== false) {
                                    return [
                                        'bg' => 'linear-gradient(135deg, #ffedbe, #ffedbe)',
                                        'color' => '#ffb700',
                                        'icon' => 'bi-clock-fill'
                                    ];
                                } elseif (strpos($serviceType, 'grooming') !== false) {
                                    return [
                                        'bg' => 'linear-gradient(135deg, #ffedbe, #ffedbe)',
                                        'color' => '#ffb700',
                                        'icon' => 'bi-clock-fill'
                                    ];
                                } else {
                                    return [
                                        'bg' => 'linear-gradient(135deg, #ffedbe, #ffedbe)',
                                        'color' => '#ffb700',
                                        'icon' => 'bi-clock-fill'
                                    ];
                                }
                            } else {
                                // Normal - Service-specific themes
                                if (strpos($serviceType, 'boarding') !== false) {
                                    return [
                                        'bg' => 'linear-gradient(135deg, #dbeafe, #bfdbfe)',
                                        'color' => '#1e40af',
                                        'icon' => 'bi-house-door'
                                    ];
                                } elseif (strpos($serviceType, 'walking') !== false) {
                                    return [
                                        'bg' => 'linear-gradient(135deg, #d1fae5, #a7f3d0)',
                                        'color' => '#065f46',
                                        'icon' => 'bi-shuffle'
                                    ];
                                } elseif (strpos($serviceType, 'grooming') !== false) {
                                    return [
                                        'bg' => 'linear-gradient(135deg, #ede9fe, #ddd6fe)',
                                        'color' => '#5b21b6',
                                        'icon' => 'bi-scissors'
                                    ];
                                } elseif (strpos($serviceType, 'sitting') !== false) {
                                    return [
                                        'bg' => 'linear-gradient(135deg, #fef3c7, #fde68a)',
                                        'color' => '#92400e',
                                        'icon' => 'bi-person-badge'
                                    ];
                                } elseif (strpos($serviceType, 'training') !== false) {
                                    return [
                                        'bg' => 'linear-gradient(135deg, #fee2e2, #fecaca)',
                                        'color' => '#991b1b',
                                        'icon' => 'bi-award'
                                    ];
                                } elseif (strpos($serviceType, 'vet') !== false) {
                                    return [
                                        'bg' => 'linear-gradient(135deg, #fce7f3, #fbcfe8)',
                                        'color' => '#9d174d',
                                        'icon' => 'bi-heart-pulse'
                                    ];
                                } elseif (strpos($serviceType, 'daycare') !== false) {
                                    return [
                                        'bg' => 'linear-gradient(135deg, #cffafe, #a5f3fc)',
                                        'color' => '#155e75',
                                        'icon' => 'bi-sun'
                                    ];
                                } elseif (strpos($serviceType, 'taxi') !== false) {
                                    return [
                                        'bg' => 'linear-gradient(135deg, #f1f5f9, #e2e8f0)',
                                        'color' => '#334155',
                                        'icon' => 'bi-car-front'
                                    ];
                                } elseif (strpos($serviceType, 'spa') !== false) {
                                    return [
                                        'bg' => 'linear-gradient(135deg, #ffedd5, #fed7aa)',
                                        'color' => '#9a3412',
                                        'icon' => 'bi-flower1'
                                    ];
                                } else {
                                    return [
                                        'bg' => 'linear-gradient(135deg, #e0e7ff, #c7d2fe)',
                                        'color' => '#3730a3',
                                        'icon' => 'bi-calendar-check'
                                    ];
                                }
                            }
                        }



                        $i = 1;
                        foreach ($bookingsList as $booking):
                            // Determine status badge color
                            $statusColors = [
                                'pending' => ['class' => 'bg-warning bg-opacity-10 text-warning border-warning border-opacity-25', 'icon' => 'bi-clock'],
                                'active' => ['class' => 'bg-success bg-opacity-10 text-success border-success border-opacity-25', 'icon' => 'bi-play-circle'],
                                'completed' => ['class' => 'bg-info bg-opacity-10 text-info border-info border-opacity-25', 'icon' => 'bi-check-circle'],
                                'cancelled' => ['class' => 'bg-danger bg-opacity-10 text-danger border-danger border-opacity-25', 'icon' => 'bi-x-circle'],
                                'deleted' => ['class' => 'bg-secondary bg-opacity-10 text-secondary border-secondary border-opacity-25', 'icon' => 'bi-trash'],
                                'halted' => ['class' => 'bg-warning bg-opacity-10 text-warning border-warning border-opacity-25', 'icon' => 'bi-pause-circle']
                            ];

                            $status = $booking->source ?? 'pending';
                            $statusClass = $statusColors[$status] ?? $statusColors['pending'];

                            // Get first letter of customer name for avatar
                            $avatarLetter = strtoupper(substr($booking->user_name ?? '?', 0, 1));

                            // Get service color and days left style
                            $serviceColor = getServiceColor($booking->service_name ?? 'default');
                            $daysLeft = $booking->total_days ?? 0;
                            $daysStyle = getDaysLeftStyle($daysLeft, $booking->service_name ?? '');
                            ?>
                            <tr class="booking-row" data-status="<?= $status ?>" data-type="<?= esc(strtolower($booking->type ?? 'normal')) ?>">
                                <!-- Customer Column -->
                                <td class="ps-4 py-2">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-black d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 44px; height: 44px; border: 2px solid #e5dbff; font-size: 1.2rem; color: #fff !important;">
                                            <?= $avatarLetter ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">
                                                <?= htmlspecialchars($booking->user_name ?? '--') ?>
                                            </div>
                                            <div class="d-flex align-items-center text-muted small mt-1">
                                                <i class="bi bi-telephone me-1"></i>
                                                <span class="text-dark"><?= $booking->user_phone ?? '--' ?></span>
                                            </div>
                                            <div class="text-muted small">
                                                <i class="bi bi-geo-alt me-1"></i>
                                                <span
                                                    class="text-dark"><?= htmlspecialchars($booking->state ?? '--') ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Pet Column -->
                                <td class="py-2 d-none d-lg-table-cell">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <div class="fw-bold mb-2 text-truncate w-100 text-center text-dark"
                                            title="<?= htmlspecialchars($booking->pet_names ?? '--') ?>">
                                            <?= htmlspecialchars(explode(',', $booking->pet_names ?? '--')[0] ?? '--') ?>
                                        </div>
                                        <div class="d-flex flex-column align-items-center gap-1">
                                            <span class="badge text-black rounded-pill p-2 border-0 service-name-badge "
                                                style="background-color: rgba(59, 130, 246, 0.1) !important;
                                                        color: #2563eb !important;
                                                        border: 1px solid rgba(59, 130, 246, 0.2) !important;">
                                                <?= htmlspecialchars($booking->service_name ?? '--') ?>
                                            </span>
                                            <?php if (!empty($booking->type)): 
                                                $isExtend = strtolower($booking->type) === 'extend';
                                            ?>
                                                <span class="badge rounded-pill p-1 px-2 border-0 mt-1" 
                                                    style="background-color: <?= $isExtend ? 'rgba(16, 185, 129, 0.12)' : 'rgba(107, 114, 128, 0.1)' ?> !important;
                                                           color: <?= $isExtend ? '#059669' : '#6b7280' ?> !important;
                                                           border: 1px solid <?= $isExtend ? 'rgba(16, 185, 129, 0.3)' : 'rgba(107, 114, 128, 0.2)' ?> !important;
                                                           font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                                    <i class="bi <?= $isExtend ? 'bi-arrow-repeat' : 'bi-plus-circle' ?> me-1"></i>
                                                    <?= $isExtend ? 'Extend' : htmlspecialchars($booking->type) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-2">
                                    <div class="d-flex flex-column align-items-center gap-2">
                                        <div class="d-flex align-items-center text-dark small">
                                            <i class="bi bi-calendar me-1"></i>
                                            <?= $booking->service_start_date ?? '--' ?>
                                        </div>
                                        <span class="badge rounded-pill p-2 px-3 border-0 d-flex align-items-center gap-1 "
                                            style="background: rgba(34, 197, 94, 0.1) !important; color: #16a34a !important;
         border: 1px solid rgba(34, 197, 94, 0.2) !important; font-weight: 600;">
                                            <?= $daysLeft ?> days left
                                        </span>
                                    </div>
                                </td>

                                <!-- No of Quotes Column -->
                                <td class="py-2 text-center">
                                    <div class="text-center">
                                        <?php if ($booking->totalQuotes > 0): ?>
                                            <a href="<?= base_url('bookings/walkingQuotes/' . $booking->id) ?>"
                                                class="text-decoration-none fw-medium text-dark">
                                                <?= $booking->totalQuotes ?? 0 ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted"><?= $booking->totalQuotes ?? 0 ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>


                                <!-- Price Column -->
                                <td class="py-2 text-center">
                                    <div class="fw-bold text-primary fs-6">
                                        ₹<?= number_format($booking->total_price ?? 0) ?>
                                    </div>
                                </td>

                                <!-- Provider Column -->
                                <td class="py-2 d-none d-sm-table-cell text-center">
                                    <div class="text-center">
                                        <div class="mt-1">
                                            <button type="button"
                                                class="btn btn-link btn-sm p-0 text-decoration-none view-providers-btn"
                                                data-booking-id="<?= $booking->id ?>"
                                                data-customer-name="<?= htmlspecialchars($booking->user_name ?? 'Customer') ?>"
                                                data-target-price="₹<?= number_format($booking->total_price ?? 0) ?>">
                                                View Nearby Providers
                                            </button>
                                        </div>
                                    </div>
                                </td>

                                <!-- Status Column -->
                                <td class="py-2 text-center">
                                    <span
                                        class="badge d-inline-flex align-items-center gap-2 rounded-pill fw-semibold <?= $statusClass['class'] ?> px-3 py-2">
                                        <i class="<?= $statusClass['icon'] ?>"></i>
                                        <?= ucfirst($status) ?>
                                    </span>
                                </td>

                                <!-- Actions Column -->
                                <td class="py-2 text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-outline-primary btn-sm booking-details-btn"
                                            data-booking='<?= htmlspecialchars(json_encode($booking), ENT_QUOTES, "UTF-8") ?>'>
                                            <i class="bi bi-eye fs-5"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm delete-booking-btn"
                                            data-id="<?php echo $booking->id ?>"
                                            data-service-id="<?php echo $booking->service_id ?>">
                                            <i class="bi bi-trash fs-5"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php $i++;
                        endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex  justify-content-end align-items-center px-3 py-3 border-top">
                <div class="btn-group mt-0 pt-0 border-0" id="paginationControls">
                    <button class="btn bg-primary btn-sm text-white" id="prevPage" disabled>
                        <i class="bi bi-chevron-left"></i> Previous
                    </button>
                    <div class="" id="pageNumbers" style="gap: 2px;">
                    </div>
                    <button class="btn bg-primary btn-sm text-white" id="nextPage">
                        Next <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Booking Details Modal -->
<div id="bookingDetailsModal" class="modal" tabindex="-1" aria-labelledby="bookingDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow" style="height: 80vh;">
            <div class="modal-header p-3" style="background: #c6d2ff !important; border-bottom: 2px solid #a3b4ff;">
                <h2 class="modal-title text-white fw-bold fs-4 mb-0">
                    <i class="bi bi-calendar-check me-2"></i>Booking Details
                </h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick="closeBookingDetails()"></button>
            </div>
            <div class="modal-body p-0"
                style="overflow-y: auto; flex: 1; scrollbar-width: none; -ms-overflow-style: none;">
                <div class="p-2">
                    <div id="bookingDetailsContent"></div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary" onclick="closeBookingDetails()">
=======
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
$totalRequests = count($bookings ?? []);
$totalQuotesCount = 0;
$totalEstRevenue = 0;
$dueTodayCount = 0;
$todayDateStr = date('Y-m-d');

if (!empty($bookings)) {
    foreach ($bookings as $b) {
        $totalQuotesCount += (int)($b->totalQuotes ?? 0);
        $totalEstRevenue += (float)($b->total_price ?? 0);
        if (!empty($b->service_start_date) && substr($b->service_start_date, 0, 10) === $todayDateStr) {
            $dueTodayCount++;
        }
    }
}
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
  .badge-service.walking { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
  .badge-service.grooming { background: #f5f3ff; color: #6d28d9; border: 1px solid #ddd6fe; }
  .badge-service.boarding { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
  .badge-service.sitting { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
  .badge-service.training { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
  .badge-service.default { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }

  .badge-type {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    padding: 2px 7px;
    border-radius: 50px;
    font-size: 10.5px;
    font-weight: 600;
    text-transform: uppercase;
  }
  .badge-type.extend { background: rgba(16, 185, 129, 0.1); color: #059669; border: 1px solid rgba(16, 185, 129, 0.25); }
  .badge-type.new { background: rgba(99, 102, 241, 0.1); color: #4f46e5; border: 1px solid rgba(99, 102, 241, 0.25); }

  .badge-urgency {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 9px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 600;
  }
  .badge-urgency.today { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; font-weight: 700; }
  .badge-urgency.upcoming { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
  .badge-urgency.expired { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

  .badge-quotes-count {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 700;
    transition: all 0.15s ease;
  }
  .badge-quotes-count.active { background: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe; }
  .badge-quotes-count.active:hover { background: #6d28d9; color: #ffffff; transform: scale(1.05); }
  .badge-quotes-count.zero { background: #f1f5f9; color: #94a3b8; border: 1px solid #e2e8f0; font-weight: 500; }

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
  .status-pending { background: #fef3c7; color: #b45309; }
  .status-pending .status-dot { background: #d97706; }
  .status-active { background: #dcfce7; color: #15803d; }
  .status-active .status-dot { background: #16a34a; }
  .status-completed { background: #e0f2fe; color: #0369a1; }
  .status-completed .status-dot { background: #0284c7; }
  .status-cancelled { background: #fee2e2; color: #b91c1c; }
  .status-cancelled .status-dot { background: #dc2626; }
  .status-deleted { background: #f1f5f9; color: #475569; }
  .status-deleted .status-dot { background: #64748b; }
  .status-halted { background: #ffedd5; color: #c2410c; }
  .status-halted .status-dot { background: #ea580c; }

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
  .nl-action-btn.view { color: #4f46e5; }
  .nl-action-btn.view:hover { background: #4f46e5; color: #ffffff; border-color: #4f46e5; }
  .nl-action-btn.delete { color: #ef4444; }
  .nl-action-btn.delete:hover { background: #ef4444; color: #ffffff; border-color: #ef4444; }

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
            <span>Live Queue Status</span>
          </div>
          <h2 class="fw-bold text-white mb-2" style="font-size: 30px; letter-spacing: -0.02em;">Bookings Management</h2>
          <p class="mb-0 text-white text-opacity-75" style="font-size: 13.5px; max-width: 560px; line-height: 1.55;">
            Track and manage real-time pet service requests, rapidly assign nearby verified professionals, and monitor dispatched quotes.
          </p>
        </div>

        <div class="col-lg-5">
          <div class="d-flex align-items-center gap-2 flex-wrap justify-content-lg-end">
            <!-- Total Requests -->
            <div class="nl-stat-card">
              <span class="label">New Requests</span>
              <div class="value"><?= number_format($totalRequests) ?></div>
            </div>
            <!-- Dispatched Quotes -->
            <div class="nl-stat-card">
              <span class="label">Dispatched</span>
              <div class="value" style="color: #6ee7b7;"><?= number_format($totalQuotesCount) ?></div>
            </div>
            <!-- Est. Revenue -->
            <div class="nl-stat-card">
              <span class="label">Est. Revenue</span>
              <div class="value" style="color: #93c5fd; font-size: 20px;">₹<?= number_format($totalEstRevenue) ?></div>
            </div>
            <!-- Due Today -->
            <div class="nl-stat-card">
              <span class="label">Due Today</span>
              <div class="value" style="color: #fde047;"><?= number_format($dueTodayCount) ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Segmented Tabs & Action Bar -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
      <div class="nl-segmented-tabs">
        <a href="<?= base_url('bookingRequests') ?>" class="nl-tab-pill active">
          <i class="bi bi-list-task"></i>
          <span>Normal Requests</span>
          <span class="nl-tab-badge"><?= $normalCount ?? $totalRequests ?></span>
        </a>
        <a href="<?= base_url('premiumRequests') ?>" class="nl-tab-pill">
          <i class="bi bi-star-fill text-warning"></i>
          <span>Premium Requests</span>
          <span class="nl-tab-badge"><?= $premiumCount ?? 0 ?></span>
        </a>
      </div>

      <div class="d-flex align-items-center gap-2">
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
            <input type="text" class="nl-search-input" id="searchBookings"
              placeholder="Search bookings by customer, phone, location, pet...">
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
              <th class="text-center" style="width: 60px;">S.No.</th>
              <th>Customer</th>
              <th>Pet Details</th>
              <th class="text-center">Service Date</th>
              <th class="text-center">Created Date</th>
              <th class="text-center">Quotes</th>
              <th class="text-end">Price</th>
              <th class="text-center">Provider</th>
              <th class="text-center">Status</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            <?php if (!empty($bookings)): ?>
              <?php
              $i = 1;
              foreach ($bookings as $booking):
                $status = strtolower($booking->source ?? 'pending');
                $avatar = getInitialsAvatar($booking->user_name ?? '', $booking->id ?? $i);

                // Service class
                $serviceRaw = strtolower($booking->service_name ?? 'default');
                $serviceClass = 'default';
                if (strpos($serviceRaw, 'walk') !== false) $serviceClass = 'walking';
                elseif (strpos($serviceRaw, 'groom') !== false) $serviceClass = 'grooming';
                elseif (strpos($serviceRaw, 'board') !== false) $serviceClass = 'boarding';
                elseif (strpos($serviceRaw, 'sit') !== false) $serviceClass = 'sitting';
                elseif (strpos($serviceRaw, 'train') !== false) $serviceClass = 'training';

                // Days left calculation
                $startDate = new DateTime($booking->service_start_date ?? date('Y-m-d'));
                $today = new DateTime('today');
                $interval = $today->diff($startDate);
                $daysLeft = (int) $interval->format('%r%a');

                $urgencyClass = $daysLeft <= 0 ? ($daysLeft == 0 ? 'today' : 'expired') : 'upcoming';
              ?>
                <tr class="booking-row" data-status="<?= $status ?>" data-service="<?= $serviceClass ?>">
                  <!-- S.No. -->
                  <td class="text-center align-middle">
                    <span class="fw-bold" style="color: #64748b; font-size: 13px;"><?= sprintf('%02d', $i) ?></span>
                  </td>

                  <!-- Customer Column -->
                  <td class="align-middle">
                    <div class="d-flex align-items-center gap-3">
                      <div class="nl-avatar" style="background: <?= $avatar['bg'] ?>; color: <?= $avatar['color'] ?>;">
                        <?= $avatar['text'] ?>
                      </div>
                      <div>
                        <div class="fw-bold text-dark" style="font-size: 13.5px; line-height: 1.3;">
                          <?= htmlspecialchars($booking->user_name ?? '--') ?>
                        </div>
                        <div class="d-flex align-items-center text-muted small mt-1 gap-1" style="font-size: 11.5px;">
                          <i class="bi bi-telephone text-secondary"></i>
                          <a href="tel:<?= $booking->user_phone ?? '' ?>" class="text-decoration-none text-muted"><?= $booking->user_phone ?? '--' ?></a>
                        </div>
                        <?php if (!empty($booking->state) || !empty($booking->address)): ?>
                          <div class="text-muted small d-flex align-items-center gap-1 mt-0.5" style="font-size: 11px;">
                            <i class="bi bi-geo-alt text-secondary"></i>
                            <span class="text-truncate" style="max-width: 170px;" title="<?= htmlspecialchars($booking->address ?? $booking->state ?? '') ?>">
                              <?= htmlspecialchars($booking->state ?? $booking->address ?? '--') ?>
                            </span>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </td>

                  <!-- Pet Details Column -->
                  <td class="align-middle">
                    <div class="fw-bold text-dark mb-1" style="font-size: 13px;" title="<?= htmlspecialchars($booking->pet_names ?? '--') ?>">
                      <?= htmlspecialchars($booking->pet_names ?? '--') ?>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-1">
                      <span class="badge-service <?= $serviceClass ?> booking-details-btn" style="cursor: pointer;"
                        data-booking='<?= htmlspecialchars(json_encode($booking), ENT_QUOTES, "UTF-8") ?>'
                        title="Click to view booking details">
                        <?= htmlspecialchars($booking->service_name ?? '--') ?>
                      </span>
                      <?php if (!empty($booking->type)): ?>
                        <span class="badge-type <?= strtolower($booking->type) === 'extend' ? 'extend' : 'new' ?>">
                          <i class="bi <?= strtolower($booking->type) === 'extend' ? 'bi-arrow-repeat' : 'bi-plus-circle' ?> me-0.5"></i>
                          <?= htmlspecialchars($booking->type) ?>
                        </span>
                      <?php endif; ?>
                    </div>
                  </td>

                  <!-- Service Date Column -->
                  <td class="align-middle text-center">
                    <div class="d-flex flex-column align-items-center gap-1">
                      <div class="text-dark fw-semibold" style="font-size: 12.5px;">
                        <i class="bi bi-calendar3 me-1 text-muted"></i>
                        <?= $booking->service_start_date ?? '--' ?>
                      </div>
                      <?php if ((int)($booking->service_id ?? 0) === 6 && !empty($booking->days)): ?>
                        <?php
                        $endDateStr = '--';
                        if (!empty($booking->service_start_date)) {
                            $startDateObj = new DateTime($booking->service_start_date);
                            $startDateObj->modify('+' . intval($booking->days) . ' days');
                            $endDateStr = $startDateObj->format('Y-m-d');
                        }
                        ?>
                        <div class="text-muted" style="font-size: 11px;">
                          to <?= $endDateStr ?> (<?= $booking->days ?>d)
                        </div>
                      <?php endif; ?>
                      <span class="badge-urgency <?= $urgencyClass ?>">
                        <?php
                        if ($daysLeft < 0) {
                            echo abs($daysLeft) . "d ago";
                        } elseif ($daysLeft == 0) {
                            echo "Today";
                        } else {
                            echo $daysLeft . "d left";
                        }
                        ?>
                      </span>
                    </div>
                  </td>

                  <!-- Created Date Column -->
                  <td class="align-middle text-center">
                    <?php
                    $createdDateVal = (!empty($booking->created_at) && $booking->created_at !== '0000-00-00 00:00:00')
                        ? $booking->created_at
                        : (!empty($booking->updated_at) ? $booking->updated_at : null);
                    $isAlreadyIst = isset($booking->created_at_utc);
                    $displayDate = $isAlreadyIst
                        ? (!empty($createdDateVal) ? date('M d, Y', strtotime($createdDateVal)) : '--')
                        : utcToIst($createdDateVal, 'M d, Y');
                    $displayTime = $isAlreadyIst
                        ? (!empty($createdDateVal) ? date('h:i A', strtotime($createdDateVal)) : '')
                        : utcToIst($createdDateVal, 'h:i A');
                    ?>
                    <div class="fw-medium text-dark" style="font-size: 12.5px;">
                      <?= $displayDate ?>
                    </div>
                    <div class="text-muted" style="font-size: 11px;">
                      <?= $displayTime ?>
                    </div>
                  </td>

                  <!-- No of Quotes Column -->
                  <td class="align-middle text-center">
                    <?php if (($booking->totalQuotes ?? 0) > 0): ?>
                      <a href="<?= base_url('bookings/walkingQuotes/' . $booking->id) ?>" class="badge-quotes-count active text-decoration-none" title="View Quotes">
                        <i class="bi bi-chat-quote-fill me-1"></i><?= $booking->totalQuotes ?>
                      </a>
                    <?php else: ?>
                      <span class="badge-quotes-count zero">0</span>
                    <?php endif; ?>
                  </td>

                  <!-- Price Column -->
                  <td class="align-middle text-end">
                    <div class="fw-bold" style="color: #4338ca; font-size: 14.5px;">
                      ₹<?= number_format($booking->total_price ?? 0) ?>
                    </div>
                  </td>

                  <!-- Provider Column -->
                  <td class="align-middle text-center">
                    <button type="button"
                      class="nl-btn-nearby view-providers-btn"
                      data-booking-id="<?= $booking->id ?>"
                      data-customer-name="<?= htmlspecialchars($booking->user_name ?? 'Customer') ?>"
                      data-target-price="₹<?= number_format($booking->total_price ?? 0) ?>">
                      <i class="bi bi-geo-alt-fill me-1"></i>Nearby
                    </button>
                  </td>

                  <!-- Status Column -->
                  <td class="align-middle text-center">
                    <span class="status-pill status-<?= $status ?>">
                      <span class="status-dot"></span>
                      <?= ucfirst($status) ?>
                    </span>
                  </td>

                  <!-- Actions Column -->
                  <td class="align-middle text-center">
                    <div class="d-flex align-items-center justify-content-center gap-2">
                      <button type="button" class="nl-action-btn view booking-details-btn"
                        title="View Details"
                        data-booking='<?= htmlspecialchars(json_encode($booking), ENT_QUOTES, "UTF-8") ?>'>
                        <i class="bi bi-eye"></i>
                      </button>
                      <button type="button" class="nl-action-btn delete delete-booking-btn"
                        title="Delete Request"
                        data-id="<?= $booking->id ?>"
                        data-service-id="<?= $booking->service_id ?>">
                        <i class="bi bi-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php
                $i++;
              endforeach;
              ?>
            <?php else: ?>
              <tr>
                <td colspan="10" class="text-center py-5 text-muted">
                  <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                  No booking requests found
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Modern Table Footer & Pagination -->
      <div class="d-flex flex-wrap justify-content-between align-items-center px-4 py-3 border-top" style="background: #fafafa;">
        <div class="text-muted small fw-medium" id="entriesInfo">
          Showing 1 to <?= min(10, $totalRequests) ?> of <?= $totalRequests ?> entries
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

<!-- Booking Details Modal -->
<div id="bookingDetailsModal" class="modal fade" tabindex="-1" aria-labelledby="bookingDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden" style="max-height: 85vh;">
            <div class="modal-header px-4 py-3 border-0 text-white" style="background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 25%, #3730a3 60%, #4f46e5 100%) !important;">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(255, 255, 255, 0.15);">
                        <i class="bi bi-calendar-check text-warning fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-white mb-0">Booking Details</h5>
                        <span class="text-white-50 tiny">Detailed view of service request</span>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white opacity-75 hover-opacity-100" data-bs-dismiss="modal" aria-label="Close" onclick="closeBookingDetails()"></button>
            </div>
            <div class="modal-body p-0" style="overflow-y: auto; flex: 1; scrollbar-width: thin;">
                <div class="p-3">
                    <div id="bookingDetailsContent"></div>
                </div>
            </div>
            <div class="modal-footer border-top bg-light px-4 py-2.5">
                <button type="button" class="btn btn-sm btn-dark rounded-pill px-4 fw-semibold" onclick="closeBookingDetails()">
>>>>>>> origin/main
                    <i class="bi bi-x-circle me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<<<<<<< HEAD
<!-- Nearby Providers Modal (Previous UI) -->
<div class="modal fade" id="providersModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content custom-modal-height">

            <div class="modal-header">
                <h5 class="modal-title">Nearby Service Providers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="providersModalContent">
                <!-- Dynamic content -->
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
=======
<!-- Nearby Providers Modal -->
<div class="modal fade" id="providersModal" tabindex="-1" aria-labelledby="providersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            <div class="modal-header px-4 py-3 border-0 text-white" style="background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 25%, #3730a3 60%, #4f46e5 100%) !important;">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(255, 255, 255, 0.15);">
                        <i class="bi bi-radar text-warning fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-white mb-0" id="providersModalLabel">Nearby Service Providers</h5>
                        <span class="text-white-50 tiny">Smart dispatch & instant push notifications</span>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white opacity-75 hover-opacity-100" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0" id="providersModalContent">
                <!-- Dynamic content -->
            </div>

            <div class="modal-footer border-top bg-light px-4 py-2.5">
                <button type="button" class="btn btn-sm btn-dark rounded-pill px-4 fw-semibold" data-bs-dismiss="modal">
                    Close
                </button>
>>>>>>> origin/main
            </div>

        </div>
    </div>
</div>

<style>
<<<<<<< HEAD
=======
    .swal2-container {
        z-index: 99999 !important;
    }
>>>>>>> origin/main
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
        max-width: 700px;
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
        padding: 0 !important;
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
    }

    .modal-footer {
        border-top: 1px solid #e5e7eb;
        flex-shrink: 0;
    }

<<<<<<< HEAD
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
    }

>>>>>>> origin/main
    /* Table Styling */
    .table-hover tbody tr:hover {
        background-color: rgba(99, 102, 241, 0.03) !important;
    }

    .table thead th {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }

    /* Status Filter Buttons */
    .status-filter-btn {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
        color: #6c757d !important;
    }

    .status-filter-btn:hover {
        background-color: rgba(99, 102, 241, 0.05) !important;
        border-color: #6366f1 !important;
        color: #6366f1 !important;
    }

    .status-filter-btn.active {
        background-color: #6366f1 !important;
        border-color: #6366f1 !important;
        color: white !important;
    }

    .status-filter-btn.active .badge {
        background-color: rgba(255, 255, 255, 0.2) !important;
        color: white !important;
        border: none !important;
    }

    .status-filter-btn .badge {
        font-weight: 500;
        letter-spacing: 0.02em;
    }

    /* Service Name Badge Styling */
    .service-name-badge {
        font-size: 0.85rem;
        letter-spacing: 0.02em;
        text-align: center;
        transition: all 0.3s ease;
        min-width: 100px;
    }


    /* Days Left Badge Styling */
    .days-left-badge {
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.02em;
        min-width: 110px;
        text-align: center;
        transition: all 0.3s ease;
        border: 2px solid transparent !important;
    }

    .days-left-badge.urgent {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(245, 158, 11, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(245, 158, 11, 0);
        }
    }

    .days-left-badge.expired {
        animation: shake 0.5s ease-in-out;
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-5px);
        }

        75% {
            transform: translateX(5px);
        }
    }

    /* Text Color Fixes */
    .text-dark {
        color: #495057 !important;
    }

    .text-muted {
        color: #6c757d !important;
    }

    .text-primary {
        color: #6366f1 !important;
    }

    /* Badge Improvements */
    .badge {
        font-weight: 500;
        letter-spacing: 0.02em;
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

    /* Fixed gradient header */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
    }

    /* Modal Animation */
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

        .modal-header {
            padding: 15px;
        }

        .modal-body {
            padding: 15px !important;
        }

        .modal-footer {
            padding: 15px;
        }

        .service-name-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            min-width: 100px;
        }

        .days-left-badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            min-width: 90px;
        }

<<<<<<< HEAD
        /* Hide pet column on mobile */
        #bookingsTable th:nth-child(2),
        #bookingsTable td:nth-child(2) {
            display: none !important;
        }

        /* Hide provider column on mobile */
        #bookingsTable th:nth-child(5),
        #bookingsTable td:nth-child(5) {
            display: none !important;
        }
=======
>>>>>>> origin/main
    }

    @media (max-width: 576px) {
        .modal-content {
            height: 90vh !important;
        }

        .modal-header h2 {
            font-size: 1.25rem;
        }

        .booking-detail-card {
            padding: 15px;
        }

        .booking-detail-value {
            font-size: 1.1rem;
        }

        .status-filter-btn {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .status-filter-btn span {
            font-size: 0.8rem;
        }
    }
</style>

<style>
<<<<<<< HEAD
    .custom-modal-height {
        max-height: 85vh;
=======
    #providersModal .modal-dialog {
        max-height: 90vh;
    }

    #providersModal .modal-content {
        max-height: 90vh;
>>>>>>> origin/main
        display: flex;
        flex-direction: column;
    }

<<<<<<< HEAD
    .custom-modal-height .modal-body {
        /* overflow-y: auto; */
        flex: 1;
=======
    #providersModal .modal-body {
        overflow-y: auto !important;
        max-height: calc(90vh - 130px);
    }

    #providersModal .modal-body::-webkit-scrollbar {
        width: 6px;
    }

    #providersModal .modal-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
>>>>>>> origin/main
    }

    .status-filter-btn.active .badge {
        background-color: rgba(220, 53, 69, 0.15) !important;
        color: #dc3545 !important;
        border: 1px solid rgba(220, 53, 69, 0.2) !important;
    }

<<<<<<< HEAD
    #providerCardsContainer::-webkit-scrollbar {
        display: none;
        /* Chrome, Safari */
=======
    #providerCardsContainer {
        overflow-y: visible !important;
>>>>>>> origin/main
    }

    /* Prevent body scroll when modal is open */
    body.modal-open {
        overflow: hidden !important;
        padding-right: 0 !important;
    }

    /* Modal backdrop */
    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.7) !important;
    }

    /* Custom scrollbar */
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
</style>

<script>
    // Prevent body scroll when modal is open
    function preventBodyScroll() {
        document.body.classList.add('modal-open');
    }

    function allowBodyScroll() {
        document.body.classList.remove('modal-open');
    }

    // Modal instances
    let bookingModalInstance = null;
    let providersModalInstance = null;

<<<<<<< HEAD
    // Function to show booking details
    function showBookingDetails(booking) {
        preventBodyScroll();

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
        }) : '--';

        // Format addons
        let addonsHtml = '';
        if (booking.addons && Array.isArray(booking.addons) && booking.addons.length > 0) {
            addonsHtml = `
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <h6 class="fw-bold text-primary mb-0">
                        <i class="bi bi-plus-circle me-2"></i>Addons
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex flex-wrap gap-2">
                        ${booking.addons.map(addon => `
                            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">${addon.addon}</span>
                        `).join('')}
                    </div>
                </div>
            </div>
        `;
        }

        // Format packages
        let packagesHtml = '';
        if (booking.package_name) {
            packagesHtml = `
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <h6 class="fw-bold text-primary mb-0">
                        <i class="bi bi-box-seam me-2"></i>Package Information
                    </h6>
                </div>
                <div class=" card-body p-3">
                    <div class="bg-light p-3 rounded">
                        <h6 class="fw-bold text-dark mb-2">${booking.package_name}</h6>
                        ${booking.total_price ? `
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-secondary">Package Price:</span>
                                <span class="fw-bold text-primary h5 mb-0">₹${booking.total_price}</span>
                            </div>
                        ` : ''}
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
                <h6 class="fw-bold mb-2 text-dark" style="font-size: 0.9rem;">Pet Breeds</h6>
                <div class="d-flex flex-wrap gap-2">
                    ${breeds.map(breed => `
                        <span class="badge px-3 py-2 rounded-pill" style="background: #e8ecff !important; border: 1px solid #486ef8 !important; color: #486ef8;">
                            ${breed.trim()}
                        </span>
                    `).join('')}
                </div>
            </div>
        `;
        }

        // Format status badge
        const statusColor = getStatusColor(booking.status);
        const statusBadge = `<span class="badge bg-${statusColor} px-3 py-2">${booking.status ? booking.status.charAt(0).toUpperCase() + booking.status.slice(1) : 'Unknown'}</span>`;

        // Format booking type badge
        const isExtend = (booking.type || '').toLowerCase() === 'extend';
        const typeBadge = booking.type ? `<span class="badge rounded-pill px-3 py-2" style="font-size: 0.8rem; background: ${isExtend ? 'rgba(16, 185, 129, 0.12)' : 'rgba(107, 114, 128, 0.1)'} !important; border: 1px solid ${isExtend ? 'rgba(16, 185, 129, 0.3)' : 'rgba(107, 114, 128, 0.2)'} !important; color: ${isExtend ? '#059669' : '#6b7280'} !important;">
            <i class="bi ${isExtend ? 'bi-arrow-repeat' : 'bi-plus-circle'} me-1"></i>${isExtend ? 'Extend Request' : booking.type}
        </span>` : '';

        // Format days left badge
        const daysLeft = booking.total_days || 0;
        const daysStyle = getDaysLeftStyle(daysLeft, booking.service_name || '');
        const daysBadge = `<span class="badge rounded-pill px-3 py-2" style="font-size: 0.8rem; background: rgba(34, 197, 94, 0.1) !important; border: 1px solid #22c55e !important; color: #22c55e;">
        <i class="bi ${daysStyle.icon} me-1"></i>${daysLeft} days left
    </span>`;

        // Build content
        let content = `
        <!-- Status Summary Bar -->
        <div class="d-flex justify-content-between align-items-center mb-2 p-3 bg-white rounded-3 shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div>
                    <span class="text-secondary small">Status</span>
                    <div class="mt-1">${statusBadge}</div>
                </div>
                ${booking.type ? `
                <div class="vr"></div>
                <div>
                    <span class="text-secondary small">Type</span>
                    <div class="mt-1">${typeBadge}</div>
                </div>
                ` : ''}
                <div class="vr"></div>
                <div>
                    <span class="text-secondary small">Time Left</span>
                    <div class="mt-1">${daysBadge}</div>
                </div>
            </div>
            <div class="text-end">
                <span class="text-secondary small d-block">Total Amount</span>
                <span class="h4 text-primary fw-bold mb-0">₹${booking.total_price || '0'}</span>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-lg-6">
                <!-- Customer Info Card -->
                <div class="card border-0 shadow-sm mb-2">
                    <div class="card-header bg-white border-0 pt-3 pb-0">
                        <h6 class="fw-bold text-primary mb-0">
                            <i class="bi bi-person-circle me-2"></i>Customer Information
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; font-weight: bold; font-size: 1.5rem;">
                                ${booking.user_name ? booking.user_name.charAt(0).toUpperCase() : '?'}
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1 text-dark">${booking.user_name || '--'}</h5>
                                <p class="text-muted mb-1"><i class="bi bi-telephone me-2"></i>${booking.user_phone || '--'}</p>
                                <p class="text-muted mb-0"><i class="bi bi-geo-alt me-2"></i>${booking.state || '--'}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pet Information Card -->
                <div class="card border-0 shadow-sm mb-2">
                    <div class="card-header bg-white border-0 pt-3 pb-0">
                        <h6 class="fw-bold text-primary mb-0">
                            <i class="bi bi-heart-fill me-2"></i>Pet Information
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        ${petBreedsHtml}
                        ${booking.pet_names ? `
                            <div class="bg-light p-3 rounded">
                                <span class="text-secondary small">Pet Names</span>
                                <p class="fw-bold text-dark h6 mb-0 mt-1">${booking.pet_names}</p>
                            </div>
                        ` : ''}
                    </div>
                </div>

                <!-- Location Details -->
                ${booking.address ? `
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 pt-3 pb-0">
                            <h6 class="fw-bold text-primary mb-0">
                                <i class="bi bi-geo-alt-fill me-2"></i>Location Details
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="bg-light p-3 rounded">
                                <p class="mb-2 text-dark"><strong class="text-secondary">Address:</strong> ${booking.address}</p>
                                <div class="row g-3">
                                    ${booking.houseno_floor ? `
                                        <div class="col-6">
                                            <span class="text-secondary small">House/Floor</span>
                                            <p class="fw-bold text-dark mb-0 mt-1">${booking.houseno_floor}</p>
                                        </div>
                                    ` : ''}
                                    ${booking.building_blockno ? `
                                        <div class="col-6">
                                            <span class="text-secondary small">Building/Block</span>
                                            <p class="fw-bold text-dark mb-0 mt-1">${booking.building_blockno}</p>
                                        </div>
                                    ` : ''}
                                    ${booking.landmark_areaname ? `
                                        <div class="col-6">
                                            <span class="text-secondary small">Landmark</span>
                                            <p class="fw-bold text-dark mb-0 mt-1">${booking.landmark_areaname}</p>
                                        </div>
                                    ` : ''}
                                    <div class="col-6">
                                        <span class="text-secondary small">City</span>
                                        <p class="fw-bold text-dark mb-0 mt-1">${booking.city || '--'}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                ` : ''}
            </div>

            <!-- Right Column -->
            <div class="col-lg-6">
                <!-- Service Details Card -->
                <div class="card border-0 shadow-sm mb-2">
                    <div class="card-header bg-white border-0 pt-3 pb-0">
                        <h6 class="fw-bold text-primary mb-0">
                            <i class="bi bi-briefcase me-2"></i>Service Details
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-3 d-flex align-items-center gap-2 flex-wrap">
                            <span class="badge fw-bold rounded-pill px-4 py-2" 
                                  style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                ${booking.service_name || '--'}
                            </span>
                            ${isExtend ? `
                            <span class="badge rounded-pill px-3 py-2" style="background: rgba(16, 185, 129, 0.12); color: #059669; border: 1px solid rgba(16, 185, 129, 0.3); font-weight: 600;">
                                <i class="bi bi-arrow-repeat me-1"></i>Extension
                            </span>
                            ` : ''}
                        </div>
                        ${booking.original_booking_id ? `
                        <div class="alert alert-info py-2 px-3 mb-3 d-flex align-items-center gap-2" style="font-size: 0.85rem; border-radius: 8px;">
                            <i class="bi bi-link-45deg fs-5"></i>
                            <div>
                                <span class="text-muted">Extension for Original Booking:</span>
                                <strong>#${booking.original_booking_id}</strong>
                            </div>
                        </div>
                        ` : ''}
                        <div class="row g-3">
                            <div class="col-6">
                                <span class="text-secondary small">Start Date</span>
                                <p class="fw-bold text-dark mb-0 mt-1"><i class="bi bi-calendar me-1"></i>${startDate}</p>
                            </div>
                            <div class="col-6">
                                <span class="text-secondary small">End Date</span>
                                <p class="fw-bold text-dark mb-0 mt-1"><i class="bi bi-calendar me-1"></i>${endDate}</p>
                            </div>
                            ${booking.service_frequency ? `
                                <div class="col-6">
                                    <span class="text-secondary small">Frequency</span>
                                    <p class="fw-bold text-dark mb-0 mt-1"><i class="bi bi-arrow-repeat me-1"></i>${booking.service_frequency}</p>
                                </div>
                            ` : ''}
                            ${booking.service_days ? `
                                <div class="col-6">
                                    <span class="text-secondary small">Service Days</span>
                                    <p class="fw-bold text-dark mb-0 mt-1"><i class="bi bi-calendar-week me-1"></i>${booking.service_days}</p>
                                </div>
                            ` : ''}
                            ${booking.preferable_time ? `
                                <div class="col-6">
                                    <span class="text-secondary small">Preferable Time</span>
                                    <p class="fw-bold text-dark mb-0 mt-1"><i class="bi bi-clock me-1"></i>${booking.preferable_time}</p>
                                </div>
                            ` : ''}
                            ${booking.walk_duration ? `
                                <div class="col-6">
                                    <span class="text-secondary small">Walk Duration</span>
                                    <p class="fw-bold text-dark mb-0 mt-1"><i class="bi bi-watch me-1"></i>${booking.walk_duration}</p>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>

                <!-- Package Information -->
                ${packagesHtml}

                <!-- Addons Section -->
                ${addonsHtml}
            </div>
        </div>

        <style>
            .modal-content {
                border-radius: 16px;
                overflow: hidden;
            }
            
            .modal-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                padding: 1.5rem;
            }
            
            .modal-header .modal-title {
                color: white;
                font-size: 1.25rem;
                font-weight: 600;
            }
            
            .modal-header .btn-close {
                background: rgba(255,255,255,0.2);
                opacity: 1;
                padding: 0.75rem;
                border-radius: 50%;
            }
            
            .modal-header .btn-close:hover {
                background: rgba(255,255,255,0.3);
            }
            
            .modal-body {
                background: #f8fafc;
                padding: 1.5rem;
            }
            
            .card {
                border-radius: 12px;
                transition: transform 0.2s;
            }
            
            .card:hover {
                transform: translateY(-2px);
            }
            
            .badge {
                font-weight: 500;
                font-size: 0.85rem;
            }
            
            .bg-light {
                background-color: #f8fafc !important;
            }
            
            .text-secondary {
                color: #64748b !important;
            }
            
            .shadow-sm {
                box-shadow: 0 2px 8px rgba(0,0,0,0.05) !important;
            }
            
            .vr {
                width: 1px;
                height: 40px;
                background-color: #e2e8f0;
            }
            
            .modal-body::-webkit-scrollbar {
                width: 8px;
            }
            
            .modal-body::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 10px;
            }
            
            .modal-body::-webkit-scrollbar-thumb {
                background: #c1c1c1;
                border-radius: 10px;
            }
            
            .modal-body::-webkit-scrollbar-thumb:hover {
                background: #a8a8a8;
            }
        </style>
    `;

        document.getElementById('bookingDetailsContent').innerHTML = content;

        // Update modal title
        const modalTitle = document.querySelector('#bookingDetailsModal .modal-title');
        if (modalTitle) {
            modalTitle.innerHTML = '<i class="bi bi-journal-text me-2"></i>Booking Details';
        }

        // Update modal header style
        const modalHeader = document.querySelector('#bookingDetailsModal .modal-header');
        if (modalHeader) {
            modalHeader.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
            modalHeader.style.padding = '1.5rem';
        }

        const modalEl = document.getElementById('bookingDetailsModal');
        bookingModalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
        bookingModalInstance.show();
=======
    function getServiceClass(service) {
        const s = (service || '').toLowerCase();
        if (s.includes('walking')) return 'bg-soft-success text-success';
        if (s.includes('grooming')) return 'bg-soft-info text-info';
        if (s.includes('boarding')) return 'bg-soft-primary text-primary';
        if (s.includes('training')) return 'bg-soft-danger text-danger';
        return 'bg-soft-secondary text-secondary';
    }

    function getBreedClass(breed) {
        return 'badge bg-light text-dark border-0 shadow-none';
    }

    function getStatusClass(status) {
        const s = (status || '').toLowerCase();
        if (s.includes('pending')) return 'status-pending';
        if (s.includes('active')) return 'status-active';
        if (s.includes('completed')) return 'status-completed';
        if (s.includes('cancelled')) return 'status-cancelled';
        if (s.includes('deleted')) return 'status-deleted';
        if (s.includes('halted')) return 'status-halted';
        if (s.includes('confirmed')) return 'status-confirmed';
        if (s.includes('progress')) return 'status-inprogress';
        return 'status-pending';
    }

    // Function to show booking details
    function showBookingDetails(booking) {
        console.log("booking", booking);
        window._currentModalBooking = booking;

        // Helper to format dates
        const formatDate = (dateStr) => {
            if (!dateStr || dateStr === '0000-00-00' || dateStr === '--') return '--';
            try {
                const safeStr = (typeof dateStr === 'string' && dateStr.includes(' ') && !dateStr.includes('T'))
                    ? dateStr.replace(' ', 'T')
                    : dateStr;
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
            return Number(val).toLocaleString('en-IN', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
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
        const sNameLower = (booking.service_name || '').toLowerCase();
        let svcConfig = {
            name: booking.service_name || 'Pet Service',
            icon: 'bi-star-fill',
            themeBg: 'linear-gradient(135deg, #1e1b4b 0%, #312e81 45%, #4338ca 100%)',
            tagClass: 'svc-tag-training',
            iconBg: 'rgba(245, 158, 11, 0.15)',
            iconColor: '#f59e0b'
        };

        if (sNameLower.includes('walk')) {
            svcConfig = {
                name: booking.service_name || 'Dog Walking',
                icon: 'bi-compass-fill',
                themeBg: 'linear-gradient(135deg, #042f2e 0%, #115e59 45%, #0d9488 100%)',
                tagClass: 'svc-tag-walking',
                iconBg: 'rgba(20, 184, 166, 0.15)',
                iconColor: '#14b8a6'
            };
        } else if (sNameLower.includes('groom')) {
            svcConfig = {
                name: booking.service_name || 'Pet Grooming',
                icon: 'bi-scissors',
                themeBg: 'linear-gradient(135deg, #2e1065 0%, #5b21b6 45%, #7c3aed 100%)',
                tagClass: 'svc-tag-grooming',
                iconBg: 'rgba(168, 85, 247, 0.15)',
                iconColor: '#c084fc'
            };
        } else if (sNameLower.includes('train')) {
            svcConfig = {
                name: booking.service_name || 'Pet Training',
                icon: 'bi-award-fill',
                themeBg: 'linear-gradient(135deg, #451a03 0%, #78350f 45%, #b45309 100%)',
                tagClass: 'svc-tag-training',
                iconBg: 'rgba(245, 158, 11, 0.15)',
                iconColor: '#fbbf24'
            };
        } else if (sNameLower.includes('sit') || sNameLower.includes('board')) {
            svcConfig = {
                name: booking.service_name || 'Pet Sitting',
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
            statusBadgeInfo = { label: currentStatus, badgeClass: 'status-pill-emerald', dotClass: 'dot-emerald' };
        } else if (stLower.includes('complete')) {
            statusBadgeInfo = { label: currentStatus, badgeClass: 'status-pill-blue', dotClass: 'dot-blue' };
        } else if (stLower.includes('halt') || stLower.includes('hold') || stLower.includes('pause')) {
            statusBadgeInfo = { label: currentStatus, badgeClass: 'status-pill-purple', dotClass: 'dot-purple' };
        } else if (stLower.includes('cancel') || stLower.includes('delete') || stLower.includes('reject')) {
            statusBadgeInfo = { label: currentStatus, badgeClass: 'status-pill-rose', dotClass: 'dot-rose' };
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
        const modalId = 'booking-modal-' + Date.now();

        // Build Pet Profiles HTML
        let petsHtml = '';
        if (booking.pets && booking.pets.length > 0) {
            petsHtml = booking.pets.map(pet => {
                const petDobFormatted = formatDate(pet.dob);
                const isAggressive = (pet.aggressiveness_level || '').toLowerCase();
                let aggBadge = '<span class="badge bg-success bg-opacity-10 text-dark border border-dark border-opacity-25 px-2.5 py-1 rounded-pill"><i class="bi bi-shield-check me-1"></i>Normal</span>';
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
                                    `<img src="https://petsfolio.in/public/uploads/pets/${pet.image}" class="pet-avatar-img" alt="${pet.name}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
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
                            </div>
                        </div>
                    </div>

                    <!-- Medical & Vaccines Micro Bar (Single Neat Row) -->
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="badge ${isVaccinated ? 'bg-success bg-opacity-10 text-dark border border-success border-opacity-25' : 'bg-light text-secondary border'} px-2.5 py-1.5 rounded-2 tiny">
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
                        <span class="badge bg-success bg-opacity-10 text-dark border border-success border-opacity-25 rounded-pill tiny fw-medium px-2 py-1">
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
                    <span class="badge bg-success bg-opacity-10 text-dark border border-success border-opacity-25 rounded-pill tiny fw-medium px-2 py-1">
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
                                        <span class="badge bg-success bg-opacity-10 text-dark border border-success border-opacity-25 rounded-pill px-2.5 py-1 tiny fw-semibold">
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

                </div>
            </div>

            <style>
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

        // Cleanup and Append
        $('.modal-backdrop').remove();
        $(`.modal[id^="booking-modal-"]`).remove();

        $('body').append(modalContent);
        const myModal = new bootstrap.Modal(document.getElementById(modalId));
        myModal.show();

        // Remove from DOM on close
        document.getElementById(modalId).addEventListener('hidden.bs.modal', function () {
            this.remove();
            allowBodyScroll();
        });
>>>>>>> origin/main
    }

    function closeBookingDetails() {
        allowBodyScroll();
<<<<<<< HEAD
        const modalEl = document.getElementById('bookingDetailsModal');
        bookingModalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
        bookingModalInstance.hide();
    }

=======
        const modal = $(`.modal[id^="booking-modal-"]`);
        if (modal.length) {
            const myModal = bootstrap.Modal.getInstance(modal[0]);
            if (myModal) myModal.hide();
            modal.remove();
        }
    }

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
            return Number(val).toLocaleString('en-IN', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
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
        const footerText = `For more information whatsapp - 8464825652\n\n📲 Apply here:\n👉 Android - https://play.google.com/store/apps/details?id=com.petsfolio.employee\n\n | iOS - https://apps.apple.com/in/app/petsfolio-master/id6747756338\n\nJoin community for latest update\n\nhttps://chat.whatsapp.com/CMS6gk7w3UU4wS5NJ5nx9h?mode`;

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


    // Function to show nearby providers (Previous UI)
    // Function to show nearby providers (Previous UI)
>>>>>>> origin/main
    function showNearbyProviders(bookingId, customerName = 'Customer', targetPrice = '₹0') {
        preventBodyScroll();

        // Show loading state
        document.getElementById('providersModalContent').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-dark">Loading providers...</p>
        </div>
    `;

        // Fix: Use the correct modal ID 'providersModal'
        const modalEl = document.getElementById('providersModal');
        if (!modalEl) {
            console.error('Modal element not found');
            return;
        }

        // Create or get the modal instance
        const providersModal = bootstrap.Modal.getOrCreateInstance(modalEl);
        providersModal.show();

        // Fetch providers data via API
        fetch(`<?= base_url('getNearbyProviders/') ?>${bookingId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data && data.providers) {
                    const sortedProviders = [...data.providers].sort((a, b) => {
<<<<<<< HEAD
                        const ratingA = a.rating || 0;
                        const ratingB = b.rating || 0;
                        if (ratingB !== ratingA) {
                            return ratingB - ratingA;
                        }
                        const distanceA = a.distance || Infinity;
                        const distanceB = b.distance || Infinity;
                        return distanceA - distanceB;
=======
                        const distanceA = parseFloat(a.distance) || 0;
                        const distanceB = parseFloat(b.distance) || 0;
                        if (distanceA !== distanceB) {
                            return distanceA - distanceB;
                        }
                        const ratingA = parseFloat(a.rating) || 0;
                        const ratingB = parseFloat(b.rating) || 0;
                        return ratingB - ratingA;
>>>>>>> origin/main
                    });
                    console.log("data.sortedProviders", sortedProviders)
                    let providersHtml = `
                <div class="p-4 d-flex flex-column h-100 overflow-hidden">
                    <div class="d-flex flex-column h-100">
<<<<<<< HEAD
                        <!-- Header -->
                        <div style="background: #c6d2ff !important;" class=" p-3 rounded border d-flex flex-column flex-sm-row justify-content-between align-items-center mb-2 gap-2">
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
                                <input class="form-check-input me-2" type="checkbox" id="selectAllNearbyProviders" onchange="toggleSelectAllNearby(this)" style="cursor: pointer; transform: scale(1.15);">
                                <label class="form-check-label fw-bold text-dark small mb-0" for="selectAllNearbyProviders" style="cursor: pointer;">
                                    Select All (<span id="nearbySelectedCount">0</span>/<span id="nearbyTotalCount">${sortedProviders.length}</span>)
                                </label>
                            </div>
                            <div>
                                <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-1.5 shadow-sm" id="btnSendNearbyNotifications" onclick="sendNearbyNotifications('${bookingId}')">
                                    <i class="fa-regular fa-paper-plane me-1"></i> Send Notifications (<span id="btnSelectedCount">0</span>)
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
                        <div class="overflow-auto pe-2 " style="max-height: 500px; scrollbar-width:none;" id="providerCardsContainer">
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

                    if (sortedProviders.length > 0) {
                        sortedProviders.forEach((provider, index) => {
                            console.log(provider);
                            const initials = provider.provider_name ?
                                provider.provider_name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2) :
                                'PR';
                            const rating = provider.rating || 0;
                            const ratingDisplay = rating > 0 ? rating.toFixed(1) : 'N/A';
<<<<<<< HEAD
                            const ratingStars = '⭐'.repeat(Math.min(Math.floor(rating), 5));
                            const distance = provider.distance || 0;
                            const distanceDisplay = distance > 0 ? `${distance} km` : 'Nearby';
                            const verificationStatus = provider.completionPercentage || 0;
                            const verificationText = verificationStatus >= 80 ? 'Verified' :
                                verificationStatus >= 50 ? 'Partially Verified' : 'Not Verified';
                            const verificationClass = verificationStatus >= 80 ? 'bg-success text-white' :
                                verificationStatus >= 50 ? 'bg-warning text-dark' : 'bg-secondary text-white';
                            const area = provider.area || 'Unknown area';
=======
                            const distance = provider.distance || 0;
                            const distanceDisplay = distance > 0 ? `${parseFloat(distance).toFixed(1)} km` : 'Nearby';
                            const verificationStatus = provider.completionPercentage || 0;
                            const verificationText = verificationStatus >= 80 ? 'Verified' :
                                verificationStatus >= 50 ? 'Partially Verified' : 'Not Verified';
                            const verificationStyle = verificationStatus >= 80 ? 'background: #ecfdf5 !important; color: #059669 !important; border: 1px solid #a7f3d0 !important;' :
                                verificationStatus >= 50 ? 'background: #fffbeb !important; color: #b45309 !important; border: 1px solid #fde68a !important;' :
                                'background: #f1f5f9 !important; color: #64748b !important; border: 1px solid #e2e8f0 !important;';
                            const area = provider.area || 'Unknown area';
                            const hasToken = provider.device_token && provider.device_token !== 'default_token';
>>>>>>> origin/main
                            let serviceDescription = `Service available in ${area}`;
                            if (distance > 0) {
                                serviceDescription += `, ${distanceDisplay} away`;
                            }
                            if (verificationStatus > 0) {
                                serviceDescription += `, ${verificationStatus.toFixed(0)}% profile complete`;
                            }
                            const basePrice = parseFloat(targetPrice.replace('₹', '')) || 500;
                            const priceVariation = (Math.random() * 200) - 100;
                            const providerPrice = Math.max(100, Math.round(basePrice + priceVariation));
                            const priceClass = providerPrice <= basePrice ? 'text-success' : 'text-danger';

                            providersHtml += `
                            <!-- Provider Card ${index + 1} -->
<<<<<<< HEAD
                            <div class="card mb-3 shadow-sm provider-card border rounded p-1"  style="border-color: #c6d2ff !important;"
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
                                    <div style="background: #c6d2ff !important;" class="mt-3 p-2  border rounded fst-italic small text-muted">
                                        "${serviceDescription}"
=======
                            <div class="card mb-3 shadow-xs provider-card border rounded-3 p-3 position-relative" style="border-color: #e2e8f0 !important; cursor: pointer; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); background: #ffffff;"
                                id="providerCard_${provider.provider_id}"
                                onclick="handleNearbyCardClick(event, '${provider.provider_id}')"
                                data-provider-name="${provider.provider_name || ''}" 
                                data-provider-notes="${serviceDescription.toLowerCase()}">
                                <div class="card-body p-0">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="pt-1" onclick="event.stopPropagation()">
                                            <input type="checkbox" class="form-check-input nearby-provider-checkbox" 
                                                value="${provider.provider_id}" 
                                                id="checkbox_provider_${provider.provider_id}"
                                                data-token="${provider.device_token || ''}"
                                                onchange="updateNearbySelectedCount()" 
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
                    document.getElementById('providersModalContent').innerHTML = providersHtml;
                } else {
                    document.getElementById('providersModalContent').innerHTML = `
                <div class="alert alert-danger m-3">
                    <i class="bi bi-exclamation-triangle"></i>
                    ${data.message || 'Failed to load providers data'}
                </div>
                `;
                }
            })
            .catch(error => {
                console.error('Error fetching providers:', error);
                document.getElementById('providersModalContent').innerHTML = `
            <div class="alert alert-danger m-3">
                <i class="bi bi-exclamation-triangle"></i>
                Failed to load providers. Please try again.
            </div>
            `;
            });
    }

<<<<<<< HEAD
=======
    function handleNearbyCardClick(event, providerId) {
        if (event.target.tagName === 'INPUT' || event.target.tagName === 'A' || event.target.tagName === 'BUTTON' || event.target.closest('.pt-1')) {
            return;
        }
        const checkbox = document.getElementById(`checkbox_provider_${providerId}`);
        if (checkbox) {
            checkbox.checked = !checkbox.checked;
            updateNearbySelectedCount();
        }
    }

    function toggleSelectAllNearby(master) {
        const checkboxes = document.querySelectorAll('.nearby-provider-checkbox');
        checkboxes.forEach(cb => {
            const card = cb.closest('.provider-card');
            if (card && card.style.display !== 'none') {
                cb.checked = master.checked;
            }
        });
        updateNearbySelectedCount();
    }

    function updateNearbySelectedCount() {
        const checkboxes = document.querySelectorAll('.nearby-provider-checkbox');
        const checked = document.querySelectorAll('.nearby-provider-checkbox:checked');
        const count = checked.length;
        
        const countEl = document.getElementById('nearbySelectedCount');
        if (countEl) countEl.innerText = count;
        
        const btnCountEl = document.getElementById('btnSelectedCount');
        if (btnCountEl) btnCountEl.innerText = count;
        
        const master = document.getElementById('selectAllNearbyProviders');
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

    function sendNearbyNotifications(bookingId) {
        const checked = document.querySelectorAll('.nearby-provider-checkbox:checked');
        if (checked.length === 0) {
            Swal.fire('No Providers Selected', 'Please select at least one provider to send notifications.', 'warning');
            return;
        }

        const providerIds = Array.from(checked).map(cb => cb.value);
        const queueOnlyCheckbox = document.getElementById('nearbyQueueOnly');
        const isQueue = queueOnlyCheckbox ? (queueOnlyCheckbox.checked ? 1 : 0) : 1;
        
        const btn = document.getElementById('btnSendNearbyNotifications');
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
                        closeProvidersModal();
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
    function closeProvidersModal() {
        allowBodyScroll();
        const modalEl = document.getElementById('providersModal');
        providersModalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
        providersModalInstance.hide();
    }

    function filterProviderCards() {
        const searchTerm = document.getElementById('providerSearch').value.toLowerCase();
        const cards = document.querySelectorAll('.provider-card');

        let visibleCount = 0;
        cards.forEach(card => {
            const providerName = card.getAttribute('data-provider-name').toLowerCase();
            const providerNotes = card.getAttribute('data-provider-notes');

            if (providerName.includes(searchTerm) || providerNotes.includes(searchTerm)) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
    }

    function getServiceColor(serviceName) {
        const serviceNameLower = serviceName.toLowerCase();
        const colors = {
            'boarding': 'linear-gradient(135deg, #3b82f6, #1d4ed8)',
            'walking': 'linear-gradient(135deg, #10b981, #059669)',
            'grooming': 'linear-gradient(135deg, #8b5cf6, #7c3aed)',
            'sitting': 'linear-gradient(135deg, #f59e0b, #d97706)',
            'training': 'linear-gradient(135deg, #ef4444, #dc2626)',
            'vet': 'linear-gradient(135deg, #ec4899, #db2777)',
            'daycare': 'linear-gradient(135deg, #06b6d4, #0891b2)',
            'taxi': 'linear-gradient(135deg, #64748b, #475569)',
            'spa': 'linear-gradient(135deg, #f97316, #ea580c)'
        };

        for (const [key, color] of Object.entries(colors)) {
            if (serviceNameLower.includes(key)) {
                return color;
            }
        }
        return 'linear-gradient(135deg, #6366f1, #4f46e5)';
    }

    function getDaysLeftStyle(daysLeft, serviceType) {
        const serviceTypeLower = serviceType.toLowerCase();

        if (daysLeft <= 0) {
            return {
                bg: 'linear-gradient(135deg, #ef4444, #dc2626)',
                color: 'white',
                icon: 'bi-exclamation-triangle'
            };
        } else if (daysLeft <= 3) {
            return {
                bg: 'linear-gradient(135deg, #fbbf24, #f59e0b)',
                color: '#92400e',
                icon: 'bi-clock-fill'
            };
        } else {
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

    function getStatusColor(status) {
        const colors = {
            'pending': 'warning',
            'active': 'success',
            'completed': 'info',
            'cancelled': 'danger',
            'deleted': 'secondary',
            'halted': 'warning'
        };
        return colors[status] || 'secondary';
    }

    class PaginationManager {
        constructor() {
            this.currentPage = 1;
            this.entriesPerPage = 10;
<<<<<<< HEAD
            this.totalEntries = <?= !empty($bookings) ? count($bookings) : 0 ?>;
            this.tableBody = document.getElementById('tableBody');
            this.allRows = Array.from(document.querySelectorAll('.booking-row'));
            this.filteredRows = this.allRows; // For filtered results
            this.searchTerm = '';
            this.statusFilter = 'all_bookings';
            this.typeFilter = 'all';
=======
            this.totalEntries = <?= count($bookings) ?>;
            this.tableBody = document.getElementById('tableBody');
            this.allRows = Array.from(document.querySelectorAll('.booking-row'));
            this.filteredRows = this.allRows; // For filtered results
>>>>>>> origin/main
            this.init();
        }

        init() {
            this.setupEventListeners();
            this.updateDisplay();
        }

        setupEventListeners() {
            // Entries per page change
<<<<<<< HEAD
            const entriesSelect = document.getElementById('entriesPerPage');
            if (entriesSelect) {
                entriesSelect.addEventListener('change', (e) => {
                    const value = e.target.value;
                    this.entriesPerPage = value === 'all' ? this.totalEntries : parseInt(value);
                    this.currentPage = 1;
                    this.updateDisplay();
                });
            }

            // Previous page button
            const prevBtn = document.getElementById('prevPage');
            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                        this.updateDisplay();
                    }
                });
            }

            // Next page button
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
=======
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
>>>>>>> origin/main

            // Search input
            const searchInput = document.getElementById('searchBookings');
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
<<<<<<< HEAD
                    this.searchTerm = e.target.value.toLowerCase();
                    this.applyFilters();
                });
            }

            // Type filter select dropdown
            const typeFilterSelect = document.getElementById('typeFilter');
            if (typeFilterSelect) {
                typeFilterSelect.addEventListener('change', (e) => {
                    this.setTypeFilter(e.target.value);
                });
            }

            // Quick filter buttons (All, Normal, Extend)
            document.querySelectorAll('.type-filter-btn').forEach((btn) => {
                btn.addEventListener('click', () => {
                    this.setTypeFilter(btn.dataset.type);
                });
            });

            // Status filter buttons
=======
                    this.applyCombinedFilters();
                });
            }

            // Service filter
            const serviceFilter = document.getElementById('serviceFilter');
            if (serviceFilter) {
                serviceFilter.addEventListener('change', () => {
                    this.applyCombinedFilters();
                });
            }

            // Filter buttons
>>>>>>> origin/main
            document.querySelectorAll('.status-filter-btn').forEach((btn) => {
                btn.addEventListener('click', () => {
                    this.filterByStatus(btn.dataset.status);
                });
            });
<<<<<<< HEAD
=======

            // Clear filters button
            const clearBtn = document.querySelector('button[onclick="clearFilters()"]');
            if (clearBtn) {
                clearBtn.onclick = () => this.clearFilters();
            }
>>>>>>> origin/main
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

<<<<<<< HEAD
=======
            // Update entries info counter
            const entriesInfo = document.getElementById('entriesInfo');
            if (entriesInfo) {
                if (this.filteredRows.length === 0) {
                    entriesInfo.textContent = 'Showing 0 entries';
                } else {
                    entriesInfo.textContent = `Showing ${start + 1} to ${end} of ${this.filteredRows.length} entries`;
                }
            }

>>>>>>> origin/main
            this.updatePaginationControls();
        }

        updatePaginationControls() {
            const totalPages = this.getTotalPages();
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');
            const pageNumbersContainer = document.getElementById('pageNumbers');

            // Update prev/next buttons
<<<<<<< HEAD
            if (prevBtn) prevBtn.disabled = this.currentPage === 1;
            if (nextBtn) nextBtn.disabled = this.currentPage === totalPages || totalPages === 0;

            // Generate page numbers
            if (!pageNumbersContainer) return;
=======
            prevBtn.disabled = this.currentPage === 1;
            nextBtn.disabled = this.currentPage === totalPages || totalPages === 0;

            // Generate page numbers
>>>>>>> origin/main
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
                    pageNumbersHtml += `<span class="btn btn-sm bg-secondary border-0" disabled>...</span>`;
                    pageNumbersHtml += this.getPageButtonHtml(totalPages);
                } else if (this.currentPage >= totalPages - 2) {
                    pageNumbersHtml += this.getPageButtonHtml(1);
                    pageNumbersHtml += `<span class="btn btn-sm bg-secondary border-0" disabled>...</span>`;
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

        setTypeFilter(type) {
            this.typeFilter = type || 'all';
            const typeFilterSelect = document.getElementById('typeFilter');
            if (typeFilterSelect && typeFilterSelect.value !== this.typeFilter) {
                typeFilterSelect.value = this.typeFilter;
            }
            // Update quick filter buttons
            document.querySelectorAll('.type-filter-btn').forEach(btn => {
                const btnType = btn.dataset.type;
                btn.classList.remove('active', 'btn-dark', 'btn-primary', 'btn-success', 'btn-outline-dark', 'btn-outline-primary', 'btn-outline-success');
                if (btnType === this.typeFilter) {
                    btn.classList.add('active');
                    if (btnType === 'all') btn.classList.add('btn-dark');
                    else if (btnType === 'normal') btn.classList.add('btn-primary');
                    else if (btnType === 'extend') btn.classList.add('btn-success');
                } else {
                    if (btnType === 'all') btn.classList.add('btn-outline-dark');
                    else if (btnType === 'normal') btn.classList.add('btn-outline-primary');
                    else if (btnType === 'extend') btn.classList.add('btn-outline-success');
                }
            });
            this.applyFilters();
        }

        searchBookings(query) {
            this.searchTerm = (query || '').toLowerCase();
            this.applyFilters();
        }

        filterByStatus(status) {
            this.statusFilter = status;
=======
            const activeClass = pageNum === this.currentPage ? 'active' : '';
            return `<button class="nl-page-btn ${activeClass} page-btn" data-page="${pageNum}">${pageNum}</button>`;
        }

        applyCombinedFilters() {
            const query = (document.getElementById('searchBookings')?.value || '').toLowerCase().trim();
            const service = (document.getElementById('serviceFilter')?.value || 'all').toLowerCase().trim();

            this.filteredRows = this.allRows.filter(row => {
                const text = row.textContent.toLowerCase();
                const matchesQuery = !query || text.includes(query);
                const rowService = (row.dataset.service || '').toLowerCase();
                const matchesService = service === 'all' || rowService === service || rowService.includes(service);
                return matchesQuery && matchesService;
            });
            this.currentPage = 1;
            this.updateDisplay();
        }

        searchBookings(query) {
            this.applyCombinedFilters();
        }

        filterByStatus(status) {
            if (status === 'all_bookings') {
                this.filteredRows = this.allRows;
            } else {
                this.filteredRows = this.allRows.filter(row => row.dataset.status === status);
            }

            // Update active filter button
>>>>>>> origin/main
            document.querySelectorAll('.status-filter-btn').forEach(btn => {
                btn.classList.remove('active', 'shadow-sm');
                if (btn.dataset.status === status) {
                    btn.classList.add('active', 'shadow-sm');
                }
            });
<<<<<<< HEAD
            this.applyFilters();
        }

        applyFilters() {
            this.filteredRows = this.allRows.filter(row => {
                // Text search
                const matchesSearch = !this.searchTerm || row.textContent.toLowerCase().includes(this.searchTerm);

                // Status match
                const rowStatus = row.dataset.status;
                const matchesStatus = this.statusFilter === 'all_bookings' || rowStatus === this.statusFilter;

                // Type match
                const rowType = (row.dataset.type || 'normal').toLowerCase();
                let matchesType = true;
                if (this.typeFilter === 'extend') {
                    matchesType = rowType === 'extend';
                } else if (this.typeFilter === 'normal') {
                    matchesType = rowType !== 'extend';
                }

                return matchesSearch && matchesStatus && matchesType;
            });
=======

>>>>>>> origin/main
            this.currentPage = 1;
            this.updateDisplay();
        }

        clearFilters() {
<<<<<<< HEAD
            const searchInput = document.getElementById('searchBookings');
            if (searchInput) searchInput.value = '';
            const dateFilter = document.getElementById('dateFilter');
            if (dateFilter) dateFilter.value = '';
            this.searchTerm = '';
            this.statusFilter = 'all_bookings';

            // Reset status buttons
=======
            if (document.getElementById('searchBookings')) document.getElementById('searchBookings').value = '';
            if (document.getElementById('serviceFilter')) document.getElementById('serviceFilter').value = 'all';
            const dateFilter = document.getElementById('dateFilter');
            if (dateFilter) dateFilter.value = '';
            this.filteredRows = this.allRows;
            this.currentPage = 1;

            // Reset active filter button
>>>>>>> origin/main
            document.querySelectorAll('.status-filter-btn').forEach(btn => {
                btn.classList.remove('active', 'shadow-sm');
                if (btn.dataset.status === 'all_bookings') {
                    btn.classList.add('active', 'shadow-sm');
                }
            });

<<<<<<< HEAD
            this.setTypeFilter('all');
=======
            this.updateDisplay();
>>>>>>> origin/main
        }

        // Call this method when new data is loaded (e.g., after AJAX)
        refreshRows() {
            this.allRows = Array.from(document.querySelectorAll('.booking-row'));
            this.filteredRows = this.allRows;
            this.totalEntries = this.allRows.length;
            this.currentPage = 1;
            this.updateDisplay();
        }
    }

    function filterBookings(status) {
<<<<<<< HEAD
        if (window.paginationManager) {
            window.paginationManager.filterByStatus(status);
        }
    }

    function searchBookings(query) {
        if (window.paginationManager) {
            window.paginationManager.searchBookings(query);
        }
    }

    function applyFilters() {
        if (window.paginationManager) {
            const searchInput = document.getElementById('searchBookings');
            const typeFilter = document.getElementById('typeFilter');
            if (searchInput) window.paginationManager.searchTerm = searchInput.value.toLowerCase();
            if (typeFilter) window.paginationManager.typeFilter = typeFilter.value;
            window.paginationManager.applyFilters();
        }
    }

    function clearFilters() {
        if (window.paginationManager) {
            window.paginationManager.clearFilters();
        }
=======
        const rows = document.querySelectorAll('.booking-row');
        let visibleCount = 0;

        rows.forEach(row => {
            if (status === 'all_bookings' || row.dataset.status === status) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Update active filter button
        document.querySelectorAll('.status-filter-btn').forEach(btn => {
            if (btn.dataset.status === status) {
                btn.style.backgroundColor = 'white';
                btn.style.color = '#7c3aed';
                btn.classList.add('shadow-sm');
            } else {
                btn.style.backgroundColor = 'rgba(255, 255, 255, 0.1)';
                btn.style.color = 'white';
                btn.classList.remove('shadow-sm');
            }
        });
    }

    function searchBookings(query) {
        const rows = document.querySelectorAll('.booking-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            if (rowText.includes(query.toLowerCase())) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
    }

    function applyFilters() {
        const searchTerm = document.getElementById('searchBookings').value.toLowerCase();
        const dateFilter = document.getElementById('dateFilter').value;

        const rows = document.querySelectorAll('.booking-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            const rowDate = row.dataset.date;

            const matchesSearch = !searchTerm || rowText.includes(searchTerm);
            const matchesDate = !dateFilter || rowDate === dateFilter;

            if (matchesSearch && matchesDate) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
    }

    function clearFilters() {
        document.getElementById('searchBookings').value = '';
        document.getElementById('dateFilter').value = '';
        filterBookings('all_bookings');
>>>>>>> origin/main
    }

    function animateDaysBadges() {
        const daysBadges = document.querySelectorAll('[class*="days-left-badge"]');
        daysBadges.forEach(badge => {
            const daysText = badge.textContent;
            const daysMatch = daysText.match(/(\d+)\s*days\s*left/);
            if (daysMatch) {
                const days = parseInt(daysMatch[1]);
                if (days <= 0) {
                    badge.classList.add('expired');
                } else if (days <= 3) {
                    badge.classList.add('urgent');
                }
            }
        });
    }

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('modal')) {
            closeBookingDetails();
            closeProvidersModal();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeBookingDetails();
            closeProvidersModal();
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Initialize pagination manager
        window.paginationManager = new PaginationManager();

        // Booking details button click
        document.querySelectorAll('.booking-details-btn').forEach((el) => {
            el.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                const bookingData = JSON.parse(this.dataset.booking);
                showBookingDetails(bookingData);
            });
        });

        // View providers button click - updated to pass customer info
        document.querySelectorAll('.view-providers-btn').forEach((btn) => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                const bookingId = this.getAttribute('data-booking-id');
                const customerName = this.getAttribute('data-customer-name') || 'Customer';
                const targetPrice = this.getAttribute('data-target-price') || '₹0';

                showNearbyProviders(bookingId, customerName, targetPrice);
            });
        });

        // Animate days badges
        animateDaysBadges();

        // Delete booking functionality
        document.querySelectorAll('.delete-booking-btn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const id = this.dataset.id;
                const serviceId = this.dataset.serviceId;

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('<?= base_url() ?>/deleteBooking/' + id + '/' + serviceId, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                            .then(response => {
                                // Check if response is ok
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                // Try to parse JSON if returned
                                return response.text().then(text => {
                                    return text ? JSON.parse(text) : {};
                                });
                            })
                            .then(data => {
                                Swal.fire(
                                    'Deleted!',
                                    'Your item has been deleted.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire(
                                    'Error!',
                                    'There was a problem deleting the item.',
                                    'error'
                                );
                            });
                    }
                });
            });
        });
    });
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

        const headers = ['S.No.', 'Customer Name', 'Phone', 'Location', 'Pet Details', 'Service', 'Service Date', 'Created Date', 'Quotes', 'Price', 'Status'];
        const csvRows = [headers.join(',')];

        let exportIndex = 1;
        rows.forEach((row) => {
            if (row.style.display === 'none') return;
            const cells = row.querySelectorAll('td');
            if (cells.length >= 9) {
                const sNo = exportIndex++;
                const customerName = (cells[1].querySelector('.fw-bold')?.innerText || '').replace(/"/g, '""').trim();
                const phone = (cells[1].querySelector('a[href^="tel:"]')?.innerText || '').replace(/"/g, '""').trim();
                const location = (cells[1].querySelector('.text-truncate')?.innerText || '').replace(/"/g, '""').trim();
                const pet = (cells[2].querySelector('.fw-bold')?.innerText || '').replace(/"/g, '""').trim();
                const service = (cells[2].querySelector('.badge-service')?.innerText || '').replace(/"/g, '""').trim();
                const serviceDate = (cells[3].querySelector('.fw-semibold')?.innerText || '').replace(/"/g, '""').trim();
                const createdDate = (cells[4].innerText || '').replace(/\n/g, ' ').replace(/"/g, '""').trim();
                const quotes = (cells[5].innerText || '0').trim();
                const price = (cells[6].innerText || '').replace(/[₹,]/g, '').trim();
                const status = (cells[8].innerText || '').trim();

                csvRows.push(`"${sNo}","${customerName}","${phone}","${location}","${pet}","${service}","${serviceDate}","${createdDate}","${quotes}","${price}","${status}"`);
            }
        });

        if (csvRows.length === 1) {
            Swal.fire('Notice', 'No visible booking records matching current filters.', 'info');
            return;
        }

        const blob = new Blob([csvRows.join('\n')], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.setAttribute('href', url);
        link.setAttribute('download', `booking_requests_${new Date().toISOString().slice(0, 10)}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
>>>>>>> origin/main
</script>
<?php echo $this->include('admin/footer') ?>
<?php echo $this->endSection() ?>