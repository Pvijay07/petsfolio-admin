<?php echo $this->extend('admin/includes/header'); ?>
<?php echo $this->section('content'); ?>
<?php
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
            return 'status-confirmed';
        if (strpos($statusLower, 'progress') !== false)
            return 'status-inprogress';
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
?>

<main class="main-content">
    <div class="position-relative iq-banner">
        <?php echo $this->include('admin/nav_bar') ?>
    </div>

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
                    <div class="col-md-10">
                        <div class="input-group">
                            <span class="input-group-text bg-black border-end-0 text-white">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" placeholder="Search bookings..."
                                id="searchInput">
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
                    <!-- <div class="col-md-2">
                        <button class="btn btn-black bg-black p-2 text-white w-100" onclick="applyFilters()">
                            <i class="bi bi-funnel me-1"></i>Filter
                        </button>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-black bg-black p-2 text-white w-100" onclick="clearFilters()">
                            <i class="bi bi-x-circle me-1"></i>Clear
                        </button>
                    </div> -->

                </div>
            </div>
        </div>

        <!-- Bookings Table Section -->
        <div class="card border-0 shadow-sm overflow-hidden rounded-3">
            <div class="table-responsive">
                <table class="table table-hover mb-0 mt-0" id="bookingsTable"
                    style="width:100% !important; margin-top: 0 !important;">
                    <thead class="" style="background: #c6d2ff !important;">
                        <tr>
                            <th data-orderable="false"
                                class=" py-3 text-black fw-semibold border-bottom text-center fs-6"
                                style="background: #c6d2ff !important;">S.No.</th>
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
                                    style="background: #c6d2ff !important;">Status</th>
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
                                    style="background: #c6d2ff !important;">Status</th>
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
                                    style="background: #c6d2ff !important;">Status</th>
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
                        <?php if (!empty($bookings)):
                            // var_dump($bookings);die;
                        ?>
                            <?php $i = 1; ?>
                            <?php foreach ($bookings as $booking): ?>
                                <tr id="row_<?= $booking->id ?>" class="booking-row">
                                    <td class="text-center align-middle">
                                        <span class="text-dark fw-medium"><?= $i ?></span>
                                    </td>
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
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div style="min-width: 0;">
                                            <div class="mb-2 text-center">
                                                <a href="javascript:void(0);" class="show-booking-details text-decoration-none"
                                                    data-booking-id="<?= $booking->id ?>">
                                                    <span class="service-badge p-2"
                                                        style="background-color: rgba(59, 130, 246, 0.1) !important; color: #2563eb !important; border: 1px solid rgba(59, 130, 246, 0.2) !important;">
                                                        <?= esc(ucfirst($booking->service_type) ?? '--') ?>
                                                        <?php if (!empty($booking->booking_type) && strtolower($booking->booking_type) === 'premium'): ?>
                                                            (Premium)
                                                        <?php endif; ?>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div style="min-width: 0;">
                                            <div class="price-text text-success text-center">
                                                ₹<?= esc($booking->bid_amount ?? $booking->bid_amount ?? $booking->total_price ?? '0') ?>
                                            </div>
                                        </div>
                                    </td>

                                    <?php if ($status === 'deleted'): ?>
                                        <td class="text-center align-middle">
                                            <span class="status-badge status-deleted">Deleted</span>
                                        </td>
                                        <td class="text-center align-middle"><?= esc($booking->deleted_at ?? '--') ?></td>
                                        <td class="text-center align-middle">
                                            <?= buildCommentSelect($booking->comment ?? '', $booking->id, $booking->service_id) ?>
                                        </td>
                                    <?php elseif ($status === 'cancelled'): ?>
                                        <td class="text-center align-middle d-none d-sm-table-cell">
                                            <?= esc($booking->provider_name ?? '--') ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="status-badge status-cancelled">Cancelled</span>
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
                                                        <div
                                                class="d-flex align-items-center text-muted small mt-1 justify-content-center">
                                                <i class="bi bi-telephone me-1"></i>
                                                <span class="text-truncate">
                                                    <?= esc($booking->provider_phone ?? '--') ?>
                                                </span>
                                            </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="text-center"><?= esc($booking->provider_name ?? '--') ?></div>
                                                
                                                <div
                                                class="d-flex align-items-center text-muted small mt-1 justify-content-center">
                                                <i class="bi bi-telephone me-1"></i>
                                                <span class="text-truncate">
                                                    <?= esc($booking->provider_phone ?? '--') ?>
                                                </span>
                                            </div>

                                            <?php endif; ?>
                                           
                                        </td>
                                        <td class="text-center align-middle">
                                            <?php
                                            $bookingStatusText = $booking->status ?? '';
                                            if (ucfirst(strtolower($bookingStatusText)) === 'Confirmed')
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
                                            <span class="status-badge status-halted">Halted</span>
                                        </td>
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

                                            <?php if ($status === 'completed' || $status === 'cancelled'): ?>
                                                <?php
                                                // For completed services, add Walk History button with tracking link
                                                $trackingUrl = base_url('trackings?booking_id=' . $booking->id . '&service_id=' . $booking->service_id . '&provider_id=' . ($booking->provider_id ?? ''));
                                                ?>
                                                <a href="<?= $trackingUrl ?>" class="btn btn-sm btn-success"
                                                    title="View walk history for this completed service">
                                                    <i class="bi bi-clock-history"></i>History
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                                $i++;
                            endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center py-5 text-muted">No bookings found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

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
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Nearby Providers Modal -->
    <div class="modal fade overflow-hidden" id="nearbyProvidersModal" tabindex="-1"
        aria-labelledby="nearbyProvidersModalLabel" aria-hidden="true">
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
                </div>
            </div>
        </div>
    </div>

    <?php echo $this->include('admin/footer') ?>
</main>

<style>
    .table thead th {
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
        padding-top: 0.6rem;
        padding-bottom: 0.6rem;
        border: none !important;
        white-space: nowrap;
    }

    .table.dataTable {
        margin-top: 0px !important;
        margin-bottom: 0px !important;
    }

    .table td {
        border: none !important;
        border-bottom: 1px solid #e5e7eb !important;
        padding: 0.5rem !important;
        font-size: 0.85rem;
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
            'Completed': '#016544ff',
            'In Progress': '#f59e0b',
            'Confirmed': '#f59e0b',
            'Pending': '#6b7280',
            'New': '#000000',
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
                    this.searchBookings(e.target.value);
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
                    pageNumbersHtml += `<span class="btn btn-sm btn-outline-secondary border-0" disabled>...</span>`;
                    pageNumbersHtml += this.getPageButtonHtml(totalPages);
                } else if (this.currentPage >= totalPages - 2) {
                    pageNumbersHtml += this.getPageButtonHtml(1);
                    pageNumbersHtml += `<span class="btn btn-sm btn-outline-secondary border-0" disabled>...</span>`;
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
            });
            this.currentPage = 1;
            this.updateDisplay();
        }

        refreshRows() {
            this.allRows = Array.from(document.querySelectorAll('.booking-row'));
            this.filteredRows = this.allRows;
            this.totalEntries = this.allRows.length;
            this.currentPage = 1;
            this.updateDisplay();
        }
    }

    // Function to show nearby providers with dynamic API data
    function showNearbyProviders(bookingId, customerName = 'Customer', targetPrice = '₹0') {
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
                            <!-- Header -->
                            <div style="background: #c6d2ff !important;" class="p-3 rounded border d-flex flex-column flex-sm-row justify-content-between align-items-center mb-2 gap-2">
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
                            <div class="overflow-auto pe-2" style="max-height: 500px; scrollbar-width:none;" id="providerCardsContainer">
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
                            const distanceDisplay = distance > 0 ? `${parseFloat(distance).toFixed(1)} km` : 'Nearby';

                            // Format verification status
                            const verificationStatus = provider.completionPercentage || 0;

                            // Area info
                            const area = provider.area || 'Unknown area';

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

    function showBookingDetails(booking) {
        console.log("booking", booking);

        // Helper to format dates
        const formatDate = (dateStr) => {
            if (!dateStr || dateStr === '0000-00-00' || dateStr === '--') return '--';
            return new Date(dateStr).toLocaleDateString('en-IN', {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            });
        };

        const startDate = formatDate(booking.service_start_date);
        const endDate = formatDate(booking.service_end_date);

        // Status Badge Logic
        const statusClass = getStatusClass(booking.status);
        const statusColor = getStatusColor(booking.status);

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
        if (booking.addons && booking.addons.length > 0) {
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
                                    <span class="status-badge-modern ${statusClass}">${booking.status || 'N/A'}</span>
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
                                                ${[ 4].includes(Number(booking.service_id)) ? `
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
        document.getElementById(modalId).addEventListener('hidden.bs.modal', function() {
            this.remove();
        });
    }


    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.remove();
            const index = modalStack.indexOf(modalId);
            if (index !== -1) modalStack.splice(index, 1);
        }
    }

    // Map to store booking data by ID
    const bookingMap = new Map();
    <?php foreach ($bookings as $booking): ?>
        bookingMap.set(<?= json_encode($booking->id) ?>, <?= json_encode($booking) ?>);
    <?php endforeach; ?>

    $(document).ready(function() {
        // Clear search input when page loads
        document.getElementById('searchInput').value = '';

        // Initialize pagination manager
        window.paginationManager = new PaginationManager();

        // Register events
        registerEventHandlers();
        initializeDatepickers();
        initializeAddressTooltips();

        // Search inputs (simplified reload)
        $('#searchInput, #dateFilter').on('keypress', function(e) {
            if (e.which == 13) applyFilters();
        });
    });

    function applyFilters() {
        const search = $('#searchInput').val();
        const date = $('#dateFilter').val();
        let url = new URL(window.location.href);
        if (search) url.searchParams.set('search', search);
        else url.searchParams.delete('search');
        if (date) url.searchParams.set('date', date);
        else url.searchParams.delete('date');
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
                <select class="form-control form-control-sm booking-comment" 
                        data-booking-id="${bookingId}" 
                        data-service-id="${serviceId}">
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
    }

    // All delegated handlers registered ONCE
    function registerEventHandlers() {
        // Datepicker change
        $(document).on('change', '.datepicker', function() {
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
                        success: function(data) {
                            Swal.fire({
                                title: "Success!",
                                text: "Booking date updated successfully.",
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });
                            setTimeout(() => location.reload(), 2000);
                        },
                        error: function() {
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
        $(document).on('click', '.nearby-providers-btn', function() {
            const bookingId = $(this).data('booking-id');
            const customerName = $(this).data('customer-name') || 'Customer';
            const targetPrice = $(this).data('target-price') || '₹0';
            showNearbyProviders(bookingId, customerName, targetPrice);
        });

        // Comment change
        $(document).on('change', '.booking-comment', function() {
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
                success: function(response) {
                    if (response.success) {
                        $statusElement.text('Saved!').removeClass('text-info').addClass('text-success');
                        $select.removeClass('is-invalid');
                        setTimeout(function() {
                            $statusElement.fadeOut();
                        }, 2000);
                    } else {
                        $statusElement.text(response.message).removeClass('text-info').addClass('text-danger');
                    }
                },
                error: function() {
                    $statusElement.text('Request failed').removeClass('text-info').addClass('text-danger');
                }
            });
        });

        // Booking details modal
        $(document).on('click', '.show-booking-details', function() {
            var bookingId = $(this).data('booking-id');
            var bookingData = bookingMap.get(bookingId);
            if (bookingData) showBookingDetails(bookingData);
        });
    }

    function initializeAddressTooltips() {
        requestAnimationFrame(function() {
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
</script>

<?php echo $this->endSection() ?>