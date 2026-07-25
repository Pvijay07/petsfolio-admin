<?php echo $this->extend('admin/includes/header'); ?>
<!--start content-->

<?php echo $this->section('content'); ?>

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
        <!-- Tab Navigation -->
        <div class="card border-0 shadow-sm mb-4 rounded-3">
            <div class="card-body p-2">
                <ul class="nav nav-pills nav-fill" id="requestTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active rounded-pill py-2 fw-bold" href="<?= base_url('bookingRequests') ?>">
                            <i class="bi bi-list-task me-2"></i>Normal Requests
                            (<?= $normalCount ?? 0 ?>)
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link rounded-pill py-2 fw-bold text-dark"
                            href="<?= base_url('premiumRequests') ?>">
                            <i class="bi bi-star-fill me-2"></i>Premium Requests
                            (<?= $premiumCount ?? 0 ?>)
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Search and Filter Card -->
        <div class="card border-0 shadow-none mb-3 rounded-3">
            <div class="card-body p-3">
                <div class="row g-3 align-items-center">
                    <div class="col-md-10">
                        <div class="input-group ">
                            <span class="input-group-text bg-black border-end-0 text-white">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" placeholder="Search bookings..."
                                id="searchBookings">
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
                </div>
            </div>
        </div>
        <div class="card border-0 shadow-sm overflow-hidden rounded-3">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="bookingsTable">
                    <thead class="" style="background: #c6d2ff !important;">
                        <tr>
                            <th class=" py-3 text-black  fw-semibold border-bottom text-center fs-6"
                                style="background: #c6d2ff !important;">S.No.</th>
                            <th class=" py-3 text-black  fw-semibold border-bottom text-center fs-6"
                                style="background: #c6d2ff !important;">Customer</th>
                            <th class="py-3 text-black  fw-semibold border-bottom d-none d-lg-table-cell text-center  fs-6"
                                style="background: #c6d2ff !important;">Pet Details</th>
                            <th class="py-3 text-black  fw-semibold border-bottom text-center fs-6"
                                style="background: #c6d2ff !important;">Service Date</th>
                            <th class="py-3 text-black  fw-semibold border-bottom text-center fs-6"
                                style="background: #c6d2ff !important;">Created Date</th>
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
                        foreach ($bookings as $booking):
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
                            // Calculate actual days left to avoid absolute values or incorrect logic from controller
                            $startDate = new DateTime($booking->service_start_date ?? date('Y-m-d'));
                            $today = new DateTime('today');
                            $interval = $today->diff($startDate);
                            $daysLeft = (int) $interval->format('%r%a');

                            $daysStyle = getDaysLeftStyle($daysLeft, $booking->service_name ?? '');
                            ?>
                            <tr class="booking-row" data-status="<?= $status ?>">
                                <td class="text-center align-middle">
                                    <span class="text-dark fw-medium"><?= $i ?></span>
                                </td>
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
                                            <?= htmlspecialchars($booking->pet_names ?? '--') ?>
                                        </div>
                                        <div class="d-flex flex-column align-items-center gap-1">
                                            <span class="badge text-black rounded-pill p-2 border-0 service-name-badge "
                                                style="background-color: rgba(59, 130, 246, 0.1) !important;
                                                        color: #2563eb !important;
                                                        border: 1px solid rgba(59, 130, 246, 0.2) !important;">
                                                <?= htmlspecialchars($booking->service_name ?? '--') ?>
                                            </span>
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
                                    </div>
                                </td>

                                <td class="py-2">
                                    <div class="d-flex flex-column align-items-center gap-2">
                                        <div class="d-flex align-items-center text-dark small text-center flex-column">
                                            <div>
                                                <i class="bi bi-calendar me-1"></i>
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
                                                <div class="text-muted small mt-1">
                                                    to <?= $endDateStr ?> (<?= $booking->days ?> days)
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <span class="badge rounded-pill p-2 px-3 border-0 d-flex align-items-center gap-1 "
                                            style="background: rgba(34, 197, 94, 0.1) !important; color: #16a34a !important;
                                                    border: 1px solid rgba(34, 197, 94, 0.2) !important; font-weight: 600;">
                                            <?php
                                            if ($daysLeft < 0) {
                                                echo abs($daysLeft) . " days ago";
                                            } elseif ($daysLeft == 0) {
                                                echo "Today";
                                            } else {
                                                echo $daysLeft . " days left";
                                            }
                                            ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="py-2 text-center">
                                    <div class="text-dark small d-flex flex-column justify-content-center h-100">
                                        <div>
                                            <?= !empty($booking->updated_at) ? date('M d, Y', strtotime($booking->updated_at)) : date('M d, Y', strtotime($booking->created_at)) ?>
                                        </div>
                                        <div class="text-muted" style="font-size: 0.75rem;">
                                            <?= !empty($booking->updated_at) ? date('h:i A', strtotime($booking->updated_at)) : date('h:i A', strtotime($booking->created_at)) ?>
                                        </div>
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
                    <i class="bi bi-x-circle me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

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
            </div>

        </div>
    </div>
</div>

<style>
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
    .custom-modal-height {
        max-height: 85vh;
        display: flex;
        flex-direction: column;
    }

    .custom-modal-height .modal-body {
        /* overflow-y: auto; */
        flex: 1;
    }

    .status-filter-btn.active .badge {
        background-color: rgba(220, 53, 69, 0.15) !important;
        color: #dc3545 !important;
        border: 1px solid rgba(220, 53, 69, 0.2) !important;
    }

    #providerCardsContainer::-webkit-scrollbar {
        display: none;
        /* Chrome, Safari */
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

        // Helper to format dates
        const formatDate = (dateStr) => {
            if (!dateStr || dateStr === '0000-00-00' || dateStr === '--') return '--';
            try {
                return new Date(dateStr).toLocaleDateString('en-IN', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            } catch (e) {
                return '--';
            }
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

        // Status Badge Logic
        const currentStatus = booking.status || booking.source || 'pending';
        const statusClass = getStatusClass(currentStatus);
        const statusColor = getStatusColor(currentStatus);

        // Modal ID
        const modalId = 'booking-modal-' + Date.now();

        // Build Pet Profiles HTML
        let petsHtml = '';
        if (booking.pets && booking.pets.length > 0) {
            petsHtml = booking.pets.map(pet => `
                <div class="premium-pet-card mb-4">
                    <div class="pet-card-header d-flex align-items-center">
                        <div class="pet-avatar-wrapper shadow-sm">
                            ${pet.image ?
                    `<img src="https://petsfolio.in/public/uploads/pets/${pet.image}" class="pet-img" alt="${pet.name}">` :
                    `<div class="pet-placeholder"><i class="bi bi-paw-fill"></i></div>`
                }
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-0 fw-bold text-dark text-capitalize">${pet.name || 'Unnamed Pet'}</h5>
                            <span class="badge bg-soft-primary text-primary px-3 rounded-pill">${pet.type || 'Pet'} • ${pet.breed || 'Unknown Breed'}</span>
                        </div>
                    </div>
                    
                    <div class="pet-card-body mt-3">
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <div class="info-label">GENDER</div>
                                <div class="info-value text-capitalize">${pet.gender || '--'}</div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="info-label">BIRTHDAY</div>
                                <div class="info-value text-capitalize">${formatDate(pet.dob)}</div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="info-label">AGGRESSION</div>
                                <div class="info-value">
                                    <span class="badge ${pet.aggressiveness_level === 'normal' ? 'bg-success' : 'bg-warning'} bg-opacity-10 ${pet.aggressiveness_level === 'normal' ? 'text-success' : 'text-warning'} border-0">
                                        ${pet.aggressiveness_level || 'Normal'}
                                    </span>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="info-label">INSURED</div>
                                <div class="info-value text-capitalize">${pet.insured || 'No'}</div>
                            </div>
                        </div>

                        <hr class="my-3 opacity-5">

                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <div class="info-label">LICENSED</div>
                                <div class="info-value text-capitalize">${pet.licensed || 'No'}</div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="info-label">VACCINATED</div>
                                <div class="info-value text-capitalize text-success">${pet.vaccinated || 'No'}</div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="info-label">LAST VACCINE</div>
                                <div class="info-value">${formatDate(pet.last_vaccination)}</div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="info-label">DEWORMED</div>
                                <div class="info-value">${formatDate(pet.dewormed_on)}</div>
                            </div>
                        </div>

                        <hr class="my-3 opacity-5">

                        <div class="row g-3">
                            <div class="col-6 col-md-4">
                                <div class="info-label">RABIES STATUS</div>
                                <div class="info-value">
                                    <span class="badge ${pet.vaccinated_rabies === 'yes' ? 'bg-success' : 'bg-danger'} bg-opacity-10 ${pet.vaccinated_rabies === 'yes' ? 'text-success' : 'text-danger'} border-0">
                                        ${pet.vaccinated_rabies === 'yes' ? 'Active' : 'Missing'}
                                    </span>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="info-label">LAST RABIES</div>
                                <div class="info-value">${formatDate(pet.last_rabies_vaccination)}</div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="info-label">LAST VET VISIT</div>
                                <div class="info-value">${formatDate(pet.last_vet_visit)} ${pet.visit_purpose ? `<span class="text-muted small">(${pet.visit_purpose})</span>` : ''}</div>
                            </div>
                        </div>

                        <div class="vet-notice mt-3 p-3 rounded-3 bg-light border-0">
                            <div class="d-flex align-items-center">
                                <div class="p-2 rounded-circle bg-white shadow-sm me-3">
                                    <i class="bi bi-hospital text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-dark">${pet.vet_name || 'No Vet'}</div>
                                    <div class="small text-muted">${pet.vet_phone || ''}</div>
                                </div>
                                ${pet.vet_address ? `<div class="small text-muted text-end" style="max-width: 150px;">${pet.vet_address}</div>` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            petsHtml = `
                <div class="text-center py-5 bg-light rounded-4">
                    <i class="bi bi-info-circle fs-1 text-muted opacity-25"></i>
                    <p class="text-muted mt-2 mb-0">No detailed pet profile found</p>
                </div>
            `;
        }

        // Build Packages & Addons
        let extrasHtml = '';

        // Handle Packages (mostly for Grooming/Training)
        if (booking.packages && booking.packages.length > 0) {
            extrasHtml += booking.packages.map(pkg => {
                let pkgPrice = pkg.package_price || pkg.price || '0';
                let pkgHtml = `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-dark fw-bold"><i class="bi bi-box-fill me-2 text-primary"></i>${pkg.package_name || 'Package'}</span>
                        <span class="fw-bold text-primary">₹${pkgPrice}</span>
                    </div>
                `;

                // Show included addons if they exist (EXCEPT for walking)
                const isWalking = (booking.service_id == 4 || (booking.service_name && booking.service_name.toLowerCase().includes('walking')));
                if (!isWalking && pkg.included_addons && Array.isArray(pkg.included_addons) && pkg.included_addons.length > 0) {
                    pkgHtml += pkg.included_addons.map(incl => `
                        <div class="d-flex justify-content-between align-items-center mb-1 ps-4 opacity-75">
                            <span class="text-muted small"><i class="bi bi-check2-circle me-2 text-success"></i>${typeof incl === 'object' ? (incl.addon_name || incl.name) : incl}</span>
                            <span class="badge bg-soft-success text-success tiny">Included</span>
                        </div>
                    `).join('');
                }
                return pkgHtml;
            }).join('');
        } else if (booking.package_name) {
            // Fallback for single package services
            extrasHtml += `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-dark fw-bold"><i class="bi bi-box-fill me-2 text-primary"></i>${booking.package_name}</span>
                    <span class="fw-bold text-primary">₹${booking.package_price || booking.total_price || '0'}</span>
                </div>
            `;

            // Single package included addons (common in training, EXCEPT for walking)
            const isWalking = (booking.service_id == 4 || (booking.service_name && booking.service_name.toLowerCase().includes('walking')));
            if (!isWalking && booking.includedAddons && Array.isArray(booking.includedAddons) && booking.includedAddons.length > 0) {
                extrasHtml += booking.includedAddons.map(incl => `
                    <div class="d-flex justify-content-between align-items-center mb-1 ps-4 opacity-75">
                        <span class="text-muted small"><i class="bi bi-check2-circle me-2 text-success"></i>${typeof incl === 'object' ? (incl.addon_name || incl.name) : incl}</span>
                        <span class="badge bg-soft-success text-success tiny">Included</span>
                    </div>
                `).join('');
            }
        }

        // Handle Addons (User specified extras)
        if (booking.addons && Array.isArray(booking.addons) && booking.addons.length > 0) {
            extrasHtml += booking.addons.map(addon => {
                const price = addon.addon_price || addon.price || '0';
                return `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted"><i class="bi bi-plus-circle-fill me-2 text-info"></i>${addon.addon || addon.name || 'Addon'}</span>
                        <span class="fw-bold">₹${price}</span>
                    </div>
                `;
            }).join('');
        }

        const modalContent = `
        <div class="modal fade" id="${modalId}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content premium-modal border-0 shadow-lg rounded-4 overflow-hidden">
                    <!-- Header -->
                    <div class="modal-header border-0 p-4" style="background: linear-gradient(135deg, #486ef8 0%, #667eea 100%);">
                        <div class="d-flex align-items-center">
                            <div class="header-icon bg-white bg-opacity-20 rounded-circle p-3 me-3 text-white shadow-sm">
                                <i class="bi bi-receipt fs-4"></i>
                            </div>
                            <div>
                                <h5 class="modal-title text-white fw-bold mb-0">Booking Details</h5>
                                <p class="text-white text-opacity-75 mb-0 small">Reference ID: #${booking.id}</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body p-0" style="background: #f8fafc;">
                        <!-- Top Summary Bar -->
                        <div class="summary-bar px-4 py-3 bg-white border-bottom d-flex justify-content-between align-items-center sticky-top shadow-sm" style="z-index: 10;">
                            <div class="d-flex gap-4 align-items-center">
                                <div class="summary-item">
                                    <span class="d-block tiny text-muted fw-bold mb-1">STATUS</span>
                                    <span class="status-badge-modern ${statusClass}">${currentStatus}</span>
                                </div>
                                <div class="vr bg-light mx-2" style="height: 30px;"></div>
                                <div class="summary-item">
                                    <span class="d-block tiny text-muted fw-bold mb-1">SERVICE TYPE</span>
                                    <span class="fw-bold text-dark"><i class="bi bi-star-fill text-warning me-1"></i>${booking.service_name || 'General Service'}</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="d-block tiny text-muted fw-bold mb-1">TOTAL AMOUNT</span>
                                <span class="h4 mb-0 fw-bolder text-primary">₹${booking.total_price || '0'}</span>
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="row g-4">
                                <!-- Left Column -->
                                <div class="col-lg-7">
                                    <!-- Customer Section -->
                                    <div class="premium-card mb-4">
                                        <div class="card-header bg-transparent border-0 pt-3 px-3 pb-0">
                                            <h6 class="fw-bold text-dark text-uppercase tracking-wider small"><i class="bi bi-person-fill me-2 text-primary"></i>Customer Profile</h6>
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="user-avatar-circle me-3">
                                                    ${booking.user_name ? booking.user_name.charAt(0).toUpperCase() : '?'}
                                                </div>
                                                <div>
                                                    <h5 class="mb-0 fw-bold text-dark">${booking.user_name || 'Unnamed Client'}</h5>
                                                    <div class="text-muted small"><i class="bi bi-telephone-fill me-1"></i>${booking.user_phone || '--'}</div>
                                                </div>
                                            </div>
                                            <div class="address-box p-3 rounded-3 bg-light">
                                                <div class="small fw-bold text-primary text-uppercase mb-1">Service Address</div>
                                                <div class="text-dark fw-medium">${[booking.houseno_floor, booking.building_blockno, booking.landmark_areaname, booking.address].filter(val => val && val !== '--' && val !== '0').join(', ') || '--'}</div>
                                                <div class="small text-muted mt-1"><i class="bi bi-pin-map me-1"></i>${booking.city || ''}, ${booking.state || ''}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pets Section -->
                                    <div class="section-container">
                                        <h6 class="fw-bold text-dark text-uppercase tracking-wider small px-1 mb-3"><i class="bi bi-paw-fill me-2 text-primary"></i>Pet Profiles</h6>
                                        ${petsHtml}
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-lg-5">
                                    <!-- Service Details -->
                                    <div class="premium-card mb-4 bg-white border border-light">
                                        <div class="card-header bg-transparent border-0 pt-3 px-3 pb-0">
                                            <h6 class="fw-bold text-dark text-uppercase tracking-wider small"><i class="bi bi-calendar-event-fill me-2 text-primary"></i>Service Schedule</h6>
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="row g-0 rounded-4 overflow-hidden border mb-3">
                                                <div class="col-6 p-3 text-center border-end">
                                                    <div class="tiny text-muted fw-bold mb-1">START</div>
                                                    <div class="fw-bold text-dark">${startDate}</div>
                                                </div>
                                                <div class="col-6 p-3 text-center bg-light">
                                                    <div class="tiny text-muted fw-bold mb-1">END</div>
                                                    <div class="fw-bold text-dark">${endDate || '--'}</div>
                                                </div>
                                            </div>
                                            
                                            <ul class="list-unstyled mb-0">
                                                ${Number(booking.service_id) === 6 ? `
                                                <li class="d-flex justify-content-between py-2 border-bottom border-light">
                                                    <span class="text-muted">Number of Days</span>
                                                    <span class="fw-bold text-dark">${booking.days || '--'}</span>
                                                </li>
                                                ` : ''}
                                                ${[4].includes(Number(booking.service_id)) ? `
                                                <li class="d-flex justify-content-between py-2 border-bottom border-light">
                                                    <span class="text-muted">Frequency</span>
                                                    <span class="fw-bold text-dark">${booking.service_frequency || '--'}</span>
                                                </li>
                                                ` : ''}
                                                <li class="d-flex justify-content-between py-2 border-bottom border-light">
                                                    <span class="text-muted">Preferred Time</span>
                                                    <span class="fw-bold text-dark">${booking.preferable_time || '--'}</span>
                                                </li>
                                                ${booking.walk_duration ? `
                                                <li class="d-flex justify-content-between py-2 border-bottom border-light">
                                                    <span class="text-muted">Walk Duration</span>
                                                    <span class="fw-bold text-dark">${booking.walk_duration}</span>
                                                </li>` : ''}
                                                ${[4].includes(Number(booking.service_id)) ? `
                                                <li class="d-flex justify-content-between py-2 border-bottom border-light">
                                                    <span class="text-muted">Service Days</span>
                                                    <span class="fw-bold text-dark text-end" style="max-width: 200px;">${booking.service_days || '--'}</span>
                                                </li>
                                                ` : ''}
                                                ${(booking.service_id == 3 && (booking.status === 'Confirmed' || booking.status === 'Confirmed')) ? `
                                                <li class="d-flex justify-content-between py-2 border-bottom border-light">
                                                    <span class="text-muted font-weight-bold">Service Mode</span>
                                                    <span class="fw-bold text-primary text-capitalize">${booking.service_mode || '--'}</span>
                                                </li>
                                                <li class="d-flex justify-content-between py-2">
                                                    <span class="text-muted">Extra Sessions</span>
                                                    <span class="fw-bold text-dark">${booking.extra_sessions || '0'}</span>
                                                </li>` : ''}
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Extras Section -->
                                    ${extrasHtml ? `
                                    <div class="premium-card mb-4 border border-light">
                                        <div class="card-header bg-transparent border-0 pt-3 px-3 pb-0">
                                            <h6 class="fw-bold text-dark text-uppercase tracking-wider small"><i class="bi bi-box-seam-fill me-2 text-primary"></i>Packages & Addons</h6>
                                        </div>
                                        <div class="card-body p-3">
                                            ${extrasHtml}
                                        </div>
                                    </div>` : ''}

                                    <!-- Specialist Section -->
                                    <div class="premium-card border border-light">
                                        <div class="card-header bg-transparent border-0 pt-3 px-3 pb-0">
                                            <h6 class="fw-bold text-dark text-uppercase tracking-wider small"><i class="bi bi-person-badge-fill me-2 text-primary"></i>Assigned Specialist</h6>
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-3 bg-soft-success text-success rounded-circle me-3">
                                                    <i class="bi bi-shield-check fs-4"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 fw-bold text-dark text-capitalize text-truncate" style="max-width: 15rem;">${booking.provider_name || 'Searching...'}</h6>
                                                    <div class="text-muted small"><i class="bi bi-telephone-fill me-1"></i>${booking.provider_phone || '--'}</div>
                                                </div>
                                                ${booking.provider_phone ? `<a href="tel:${booking.provider_phone}" class="btn btn-sm btn-soft-success rounded-pill px-3 fw-bold">Call</a>` : ''}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Notes & Reason Footer -->
                            ${(booking.comment || booking.cancel_reason) ? `
                            <div class="row g-4 mt-2">
                                ${booking.cancel_reason ? `
                                <div class="col-md-6">
                                    <div class="alert alert-soft-danger border-0 rounded-4 p-3 h-100">
                                        <h6 class="fw-bold text-danger mb-2 small"><i class="bi bi-x-octagon-fill me-2"></i>Cancellation Reason</h6>
                                        <div class="text-dark small">${booking.cancel_reason}</div>
                                    </div>
                                </div>` : ''}
                                ${booking.comment ? `
                                <div class="col-md-6">
                                    <div class="alert alert-soft-info border-0 rounded-4 p-3 h-100">
                                        <h6 class="fw-bold text-primary mb-2 small"><i class="bi bi-info-circle-fill me-2"></i>Admin Comments</h6>
                                        <div class="text-dark small">${booking.comment}</div>
                                    </div>
                                </div>` : ''}
                            </div>` : ''}
                        </div>
                    </div>
                </div>
            </div>

            <style>
                .premium-modal { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
                .tracking-wider { letter-spacing: 0.1em; }
                .tiny { font-size: 0.65rem; }
                .bg-soft-primary { background-color: #f0f3ff; }
                .bg-soft-success { background-color: #e6fffa; }
                .alert-soft-info { background-color: #e0f2fe; }
                .alert-soft-danger { background-color: #fee2e2; }
                
                .status-badge-modern {
                    padding: 0.4rem 1rem;
                    border-radius: 100px;
                    font-weight: 700;
                    font-size: 0.75rem;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    display: inline-block;
                }
                
                .user-avatar-circle {
                    width: 50px; height: 50px;
                    background: linear-gradient(135deg, #486ef8 0%, #667eea 100%);
                    color: white; border-radius: 50%;
                    display: flex; align-items: center; justify-content: center;
                    font-weight: 800; font-size: 1.25rem;
                }
                
                .premium-card {
                    background: white; border-radius: 20px;
                    border: 1px solid rgba(0,0,0,0.05);
                    box-shadow: 0 4px 15px rgba(0,0,0,0.02);
                }
                
                .premium-pet-card {
                    background: white; border-radius: 20px;
                    border: 1px solid rgba(0,0,0,0.05); padding: 1.5rem;
                    box-shadow: 0 8px 25px rgba(0,0,0,0.03);
                    transition: transform 0.3s ease;
                }
                .premium-pet-card:hover { transform: translateY(-3px); }
                
                .pet-avatar-wrapper {
                    width: 64px; height: 64px; border-radius: 18px;
                    overflow: hidden; border: 3px solid white;
                }
                .pet-img { width: 100%; height: 100%; object-fit: cover; }
                .pet-placeholder {
                    width:100%; height:100%; background: #f1f5f9;
                    display:flex; align-items:center; justify-content:center;
                    color: #cbd5e1; font-size: 1.5rem;
                }
                
                .info-label { font-size: 0.6rem; font-weight: 800; color: #94a3b8; letter-spacing: 0.05em; margin-bottom: 2px; }
                .info-value { font-size: 0.85rem; font-weight: 700; color: #1e293b; }
                
                .btn-soft-success { background: #e6fffa; color: #0d9488; border: none; }
                .btn-soft-success:hover { background: #b2f5ea; color: #0d9488; }
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
    }

    function closeBookingDetails() {
        allowBodyScroll();
        const modal = $(`.modal[id^="booking-modal-"]`);
        if (modal.length) {
            const myModal = bootstrap.Modal.getInstance(modal[0]);
            if (myModal) myModal.hide();
            modal.remove();
        }
    }


    // Function to show nearby providers (Previous UI)
    // Function to show nearby providers (Previous UI)
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
                        const ratingA = a.rating || 0;
                        const ratingB = b.rating || 0;
                        if (ratingB !== ratingA) {
                            return ratingB - ratingA;
                        }
                        const distanceA = a.distance || Infinity;
                        const distanceB = b.distance || Infinity;
                        return distanceA - distanceB;
                    });
                    console.log("data.sortedProviders", sortedProviders)
                    let providersHtml = `
                <div class="p-4 d-flex flex-column h-100 overflow-hidden">
                    <div class="d-flex flex-column h-100">
                        <!-- Header -->
                        <div style="background: #c6d2ff !important;" class=" p-3 rounded border d-flex flex-column flex-sm-row justify-content-between align-items-center mb-2 gap-2">
                            <div class="text-center text-sm-start">
                                <span class="text-muted">Customer:</span>
                                <span class="fw-semibold text-black ms-1">${customerName}</span>
                            </div>
                            <div class="text-center text-sm-end">
                                <span class="text-muted">Target:</span>
                                <span class="fw-bold text-black ms-1">${targetPrice}</span>
                            </div>
                        </div>

                        <!-- Search -->
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
                `;

                    if (sortedProviders.length > 0) {
                        sortedProviders.forEach((provider, index) => {
                            console.log(provider);
                            const initials = provider.provider_name ?
                                provider.provider_name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2) :
                                'PR';
                            const rating = provider.rating || 0;
                            const ratingDisplay = rating > 0 ? rating.toFixed(1) : 'N/A';
                            const ratingStars = '⭐'.repeat(Math.min(Math.floor(rating), 5));
                            const distance = provider.distance || 0;
                            const distanceDisplay = distance > 0 ? `${parseFloat(distance).toFixed(1)} km` : 'Nearby';
                            const verificationStatus = provider.completionPercentage || 0;
                            const verificationText = verificationStatus >= 80 ? 'Verified' :
                                verificationStatus >= 50 ? 'Partially Verified' : 'Not Verified';
                            const verificationClass = verificationStatus >= 80 ? 'bg-success text-white' :
                                verificationStatus >= 50 ? 'bg-warning text-dark' : 'bg-secondary text-white';
                            const area = provider.area || 'Unknown area';
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
            this.totalEntries = <?= count($bookings) ?>;
            this.tableBody = document.getElementById('tableBody');
            this.allRows = Array.from(document.querySelectorAll('.booking-row'));
            this.filteredRows = this.allRows; // For filtered results
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

            // Search input - override existing search
            const searchInput = document.getElementById('searchBookings');
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    this.searchBookings(e.target.value);
                });
            }

            // Filter buttons - override existing filter
            document.querySelectorAll('.status-filter-btn').forEach((btn) => {
                btn.addEventListener('click', () => {
                    this.filterByStatus(btn.dataset.status);
                });
            });

            // Clear filters button
            const clearBtn = document.querySelector('button[onclick="clearFilters()"]');
            if (clearBtn) {
                clearBtn.onclick = () => this.clearFilters();
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
                    pageNumbersHtml += `<span class="btn btn-sm bg-secondary border-0" disabled>...</span>`;
                    pageNumbersHtml += this.getPageButtonHtml(totalPages);
                } else if (this.currentPage >= totalPages - 2) {
                    pageNumbersHtml += this.getPageButtonHtml(1);
                    pageNumbersHtml += `<span class="btn btn-sm bg-secondary border-0" disabled>...</span>`;
                    for (let i = totalPages - 3; i <= totalPages; i++) {
                        pageNumbersHtml += this.getPageButtonHtml(i);
                    }
                } else {
                    pageNumbersHtml += this.getPageButtonHtml(1);
                    pageNumbersHtml += `<span class="btn btn-sm btn-outline-secondary border-0" disabled>...</span>`;
                    for (let i = this.currentPage - 1; i <= this.currentPage + 1; i++) {
                        pageNumbersHtml += this.getPageButtonHtml(i);
                    }
                    pageNumbersHtml += `<span class="btn btn-sm btn-outline-secondary border-0" disabled>...</span>`;
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
            const activeClass = pageNum === this.currentPage ? 'btn-primary' : 'btn-outline-primary';
            return `<button class="btn btn-sm ${activeClass} page-btn" data-page="${pageNum}">${pageNum}</button>`;
        }


        searchBookings(query) {
            query = query.toLowerCase();
            this.filteredRows = this.allRows.filter(row => {
                return row.textContent.toLowerCase().includes(query);
            });
            this.currentPage = 1;
            this.updateDisplay();
        }

        filterByStatus(status) {
            if (status === 'all_bookings') {
                this.filteredRows = this.allRows;
            } else {
                this.filteredRows = this.allRows.filter(row => row.dataset.status === status);
            }

            // Update active filter button
            document.querySelectorAll('.status-filter-btn').forEach(btn => {
                btn.classList.remove('active', 'shadow-sm');
                if (btn.dataset.status === status) {
                    btn.classList.add('active', 'shadow-sm');
                }
            });

            this.currentPage = 1;
            this.updateDisplay();
        }

        clearFilters() {
            document.getElementById('searchBookings').value = '';
            document.getElementById('dateFilter').value = '';
            this.filteredRows = this.allRows;
            this.currentPage = 1;

            // Reset active filter button
            document.querySelectorAll('.status-filter-btn').forEach(btn => {
                btn.classList.remove('active', 'shadow-sm');
                if (btn.dataset.status === 'all_bookings') {
                    btn.classList.add('active', 'shadow-sm');
                }
            });

            this.updateDisplay();
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
</script>
<?php echo $this->include('admin/footer') ?>
<?php echo $this->endSection() ?>