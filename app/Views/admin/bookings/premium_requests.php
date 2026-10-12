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
                    <h1 class="h2 fw-bold mb-2 text-white">Premium Service Requests</h1>
                    <p class="text-white-75 mb-0">Manage and assign customized premium service requests from clients</p>
                </div>
                <i class="bi bi-star-fill position-absolute end-0 bottom-0 opacity-10"
                    style="font-size: 14rem; transform: rotate(12deg); margin-right: -3rem; margin-bottom: -3rem;"></i>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4 rounded-3">
            <div class="card-body p-2">
                <ul class="nav nav-pills nav-fill" id="requestTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link rounded-pill py-2 fw-bold text-dark"
                            href="<?= base_url('bookingRequests') ?>">
                            <i class="bi bi-list-task me-2"></i>Normal Requests
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active rounded-pill py-2 fw-bold" href="<?= base_url('premiumRequests') ?>">
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
                    <div class="col-md-5">
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

                </div>
            </div>
        </div>
        <div class="card border-0 shadow-sm overflow-hidden rounded-3">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="premiumRequestsTable">
                    <thead class="" style="background: #c6d2ff !important;">
                        <tr>
                            <th class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                                style="background: #c6d2ff !important;">Customer & Pets</th>
                            <th class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                                style="background: #c6d2ff !important;">Package & Dates</th>
                            <th class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                                style="background: #c6d2ff !important;">Area</th>
                            <th class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                                style="background: #c6d2ff !important;">Status</th>
                            <th class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                                style="background: #c6d2ff !important;">Admin Price</th>
                            <th class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                                style="background: #c6d2ff !important;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php foreach ($requests as $request):
                            $statusClass = [
                                'Requested' => 'bg-warning bg-opacity-10 text-warning border-warning',
                                'Assigned' => 'bg-info bg-opacity-10 text-info border-info',
                                'Accepted' => 'bg-success bg-opacity-10 text-success border-success',
                                'Rejected' => 'bg-danger bg-opacity-10 text-danger border-danger',
                                'Completed' => 'bg-primary bg-opacity-10 text-primary border-primary',
                                'Cancelled' => 'bg-secondary bg-opacity-10 text-secondary border-secondary'
                            ][$request->status] ?? 'bg-light text-dark';
                            ?>
                            <tr class="booking-row" data-status="<?= $request->status ?>">
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 40px; height: 40px;">
                                            <?= strtoupper(substr($request->user_name, 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($request->user_name) ?>
                                            </div>
                                            <div class="text-muted small"><?= $request->pet_names ?: 'No pets' ?></div>
                                            <div class="small text-primary">ID: <?= $request->booking_id ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="fw-bold text-dark"><?= $request->package_name ?></div>
                                    <div class="small text-muted">
                                        <i class="bi bi-calendar-event me-1"></i>
                                        <?= date('d M', strtotime($request->service_start_date)) ?> -
                                        <?= date('d M Y', strtotime($request->service_end_date)) ?>
                                    </div>
                                    <div class="badge bg-light text-dark border-0 small"><?= $request->service_days ?></div>
                                </td>
                                <td class="text-center">
                                    <div class="text-dark small"><i
                                            class="bi bi-geo-alt-fill text-danger me-1"></i><?= $request->area ?: 'N/A' ?>
                                    </div>
                                    <div class="text-muted smaller"><?= $request->city ?></div>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill border <?= $statusClass ?> px-3 py-2">
                                        <?= $request->status ?>
                                    </span>
                                </td>
                                <td class="text-center fw-bold text-primary">
                                    <?= $request->total_price ? '₹' . number_format($request->total_price, 2) : '₹' . number_format($request->total_price, 2) ?>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-outline-primary btn-sm booking-details-btn"
                                            data-booking='<?= htmlspecialchars(json_encode($request), ENT_QUOTES, "UTF-8") ?>'>
                                            <i class="bi bi-eye fs-5"></i>
                                        </button>
                                        <?php if ($request->status == 'Requested' || $request->status == 'Paid'): ?>
                                            <button class="btn btn-sm btn-primary rounded-pill px-3"
                                                onclick="openAssignModal(<?= htmlspecialchars(json_encode($request)) ?>)">
                                                <i class="bi bi-person-plus me-1"></i>Assign
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" disabled>
                                                <i class="bi bi-check2-all me-1"></i>Assigned
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end align-items-center px-3 py-3 border-top">
                <div class="btn-group mt-0 pt-0 border-0" id="paginationControls">
                    <button class="btn bg-primary btn-sm text-white" id="prevPage" disabled>
                        <i class="bi bi-chevron-left"></i> Previous
                    </button>
                    <div class="d-flex" id="pageNumbers" style="gap: 2px;">
                    </div>
                    <button class="btn bg-primary btn-sm text-white" id="nextPage">
                        Next <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Assign Modal -->
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-bottom-0">
                <h5 class="modal-title fw-bold text-white"><i class="bi bi-star-fill me-2"></i>Assign Premium Provider
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="assignForm">
                    <input type="hidden" name="request_id" id="modal_request_id">

                    <div class="row mb-4 bg-light p-3 rounded-3 mx-0">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label fw-bold text-muted small text-uppercase mb-1">Customer &
                                Pets</label>
                            <div id="modal_user_name" class="fw-bold text-dark fs-5"></div>
                            <div id="modal_pets" class="text-muted small"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small text-uppercase mb-1">Booking &
                                Package</label>
                            <div id="modal_package" class="fw-bold text-primary"></div>
                            <div id="modal_booking_id" class="text-muted small"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Select Quotation (Optional)</label>
                        <select id="quotationSelect" class="form-select border-primary"
                            onchange="onQuotationSelect(this)">
                            <option value="">-- Select a Quotation --</option>
                        </select>
                        <div class="form-text small text-muted"><i class="bi bi-info-circle me-1"></i>Selecting a
                            quotation will auto-fill the price and provider.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Price (₹)</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white border-primary text-primary fw-bold">₹</span>
                            <input type="number" name="price" id="admin_price"
                                class="form-control border-primary fw-bold" placeholder="0.00" required disabled>
                        </div>
                        <!-- <div class="form-text mt-1 text-primary"><i class="bi bi-info-circle me-1"></i>This price will be visible to both the client and provider.</div> -->
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-bold mb-0">Nearby Service Providers</label>
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                                <input type="text" id="providerSearch" class="form-control"
                                    placeholder="Search by name/phone...">
                            </div>
                        </div>
                        <div id="providers_list" class="row g-3"
                            style="max-height: 400px; overflow-y: auto; padding: 5px;">
                            <!-- Providers will be loaded here via AJAX -->
                            <div class="col-12 text-center py-5">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p class="mt-2 text-muted">Searching nearby providers...</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold">Admin Notes</label>
                        <textarea name="admin_notes" class="form-control" rows="2"
                            placeholder="Notes for this assignment..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 p-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold" id="submitAssignBtn" disabled
                    onclick="submitAssignment()">
                    <i class="bi bi-check-circle-fill me-2"></i>Confirm & Assign
                </button>
            </div>
        </div>
    </div>
</div>

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

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
    }

    .provider-card {
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid #f1f5f9;
        position: relative;
    }

    .provider-card:hover {
        border-color: #cbd5e1;
        transform: translateY(-2px);
    }

    .provider-card.selected {
        border-color: #4f46e5;
        background-color: #f5f3ff;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
    }

    .provider-card.selected::after {
        content: '\F272';
        font-family: "bootstrap-icons";
        position: absolute;
        top: -10px;
        right: -10px;
        background: #4f46e5;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .smaller {
        font-size: 0.75rem;
    }

    /* Modal Styling */
    #bookingDetailsModal.modal {
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

    #bookingDetailsModal.modal.show {
        display: flex !important;
    }

    #bookingDetailsModal .modal-dialog {
        margin: auto;
        width: 90%;
        max-width: 700px;
    }

    #bookingDetailsModal .modal-dialog-centered {
        display: flex;
        align-items: center;
        min-height: calc(100% - 3rem);
    }

    #bookingDetailsModal .modal-content {
        background-color: white;
        border-radius: 16px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border: none;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        padding: 0 !important;
    }

    #bookingDetailsModal .modal-header {
        padding: 1.5rem;
        border-bottom: none;
        flex-shrink: 0;
    }

    #bookingDetailsModal .modal-body {
        padding: 0;
        flex: 1 1 auto;
    }

    #bookingDetailsModal .modal-footer {
        border-top: 1px solid #e5e7eb;
        flex-shrink: 0;
    }

    #bookingDetailsModal .modal-header h2 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 600;
    }

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

    .bg-gradient-primary {
        background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
    }
</style>

<!-- Load Bootstrap 5 Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    let selectedProviderId = null;
    let allNearbyProviders = [];
    let bookingModalInstance = null;

    // Prevent body scroll when modal is open
    function preventBodyScroll() {
        document.body.classList.add('modal-open');
    }

    function allowBodyScroll() {
        document.body.classList.remove('modal-open');
    }

    $(document).ready(function () {
        $('.booking-details-btn').on('click', function () {
            const booking = $(this).data('booking');
            showBookingDetails(booking);
        });
    });

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
        const statusColors = {
            'Requested': 'warning',
            'Paid': 'success',
            'Assigned': 'info',
            'Accepted': 'success',
            'Rejected': 'danger',
            'Completed': 'primary',
            'Cancelled': 'secondary'
        };
        const statusColor = statusColors[booking.status] || 'secondary';
        const statusBadge = `<span class="badge bg-${statusColor} px-3 py-2">${booking.status || 'Unknown'}</span>`;

        // Format days left badge
        const daysLeft = booking.total_days || 0;
        const daysBadge = `<span class="badge rounded-pill px-3 py-2" style="font-size: 0.8rem; background: rgba(34, 197, 94, 0.1) !important; border: 1px solid #22c55e !important; color: #22c55e;">
        <i class="bi bi-calendar-check me-1"></i>${daysLeft} days left
    </span>`;

        // Build content
        let content = `
        <div class="d-flex justify-content-between align-items-center mb-2 p-3 bg-white rounded-3 shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div>
                    <span class="text-secondary small">Status</span>
                    <div class="mt-1">${statusBadge}</div>
                </div>
                <div class="vr"></div>
                <div>
                    <span class="text-secondary small">Time Left</span>
                    <div class="mt-1">${daysBadge}</div>
                </div>
            </div>
            <div class="text-end">
                <span class="text-secondary small d-block">Price</span>
                <span class="h4 text-primary fw-bold mb-0">₹${booking.total_price || booking.package_price || '0'}</span>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
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
                                <p class="text-muted mb-0"><i class="bi bi-geo-alt me-2"></i>${booking.city || '--'}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-2">
                    <div class="card-header bg-white border-0 pt-3 pb-0">
                        <h6 class="fw-bold text-primary mb-0">
                            <i class="bi bi-heart-fill me-2"></i>Pet Information
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        ${petBreedsHtml}
                        <div class="bg-light p-3 rounded">
                            <span class="text-secondary small">Pet Names</span>
                            <p class="fw-bold text-dark h6 mb-0 mt-1">${booking.pet_names || '--'}</p>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pt-3 pb-0">
                        <h6 class="fw-bold text-primary mb-0">
                            <i class="bi bi-geo-alt-fill me-2"></i>Location Details
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="bg-light p-3 rounded">
                            <p class="mb-2 text-dark"><strong class="text-secondary">Address:</strong> ${booking.formatted_address || '--'}</p>
                            <div class="row g-3">
                                <div class="col-6">
                                    <span class="text-secondary small">City</span>
                                    <p class="fw-bold text-dark mb-0 mt-1">${booking.city || '--'}</p>
                                </div>
                                <div class="col-6">
                                    <span class="text-secondary small">State</span>
                                    <p class="fw-bold text-dark mb-0 mt-1">${booking.state || '--'}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm mb-2">
                    <div class="card-header bg-white border-0 pt-3 pb-0">
                        <h6 class="fw-bold text-primary mb-0">
                            <i class="bi bi-briefcase me-2"></i>Service Details
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-3">
                            <span class="badge fw-bold rounded-pill px-4 py-2" 
                                  style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                ${booking.package_name || '--'}
                            </span>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <span class="text-secondary small">Start Date</span>
                                <p class="fw-bold text-dark mb-0 mt-1"><i class="bi bi-calendar me-1"></i>${startDate}</p>
                            </div>
                            <div class="col-6">
                                <span class="text-secondary small">End Date</span>
                                <p class="fw-bold text-dark mb-0 mt-1"><i class="bi bi-calendar me-1"></i>${endDate}</p>
                            </div>
                            <div class="col-6">
                                <span class="text-secondary small">Service Days</span>
                                <p class="fw-bold text-dark mb-0 mt-1"><i class="bi bi-calendar-week me-1"></i>${booking.service_days || '--'}</p>
                            </div>
                            <div class="col-6">
                                <span class="text-secondary small">Preferable Time</span>
                                <p class="fw-bold text-dark mb-0 mt-1"><i class="bi bi-clock me-1"></i>${booking.preferable_time || '--'}</p>
                            </div>
                            ${booking.walk_duration ? `
                            <div class="col-6">
                                <span class="text-secondary small">Walk Duration</span>
                                <p class="fw-bold text-dark mb-0 mt-1"><i class="bi bi-watch me-1"></i>${booking.walk_duration}</p>
                            </div>
                            ` : ''}
                            ${booking.service_frequency ? `
                            <div class="col-6">
                                <span class="text-secondary small">Frequency</span>
                                <p class="fw-bold text-dark mb-0 mt-1"><i class="bi bi-arrow-repeat me-1"></i>${booking.service_frequency}</p>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                </div>

                ${booking.admin_notes ? `
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pt-3 pb-0">
                        <h6 class="fw-bold text-primary mb-0">
                            <i class="bi bi-pencil-square me-2"></i>Admin Notes
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="bg-light p-3 rounded">
                            <p class="text-dark mb-0">${booking.admin_notes}</p>
                        </div>
                    </div>
                </div>
                ` : ''}
            </div>
        </div>

        <style>
            #bookingDetailsModal .modal-content {
                border-radius: 16px;
                overflow: hidden;
            }
            
            #bookingDetailsModal .modal-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                padding: 1.5rem;
            }
            
            #bookingDetailsModal .modal-header .modal-title {
                color: white;
                font-size: 1.25rem;
                font-weight: 600;
            }
            
            #bookingDetailsModal .modal-header .btn-close {
                background: rgba(255,255,255,0.2);
                opacity: 1;
                padding: 0.75rem;
                border-radius: 50%;
            }
            
            #bookingDetailsModal .modal-body {
                background: #f8fafc;
                padding: 1.5rem;
            }
            
            #bookingDetailsModal .card {
                border-radius: 12px;
                transition: transform 0.2s;
            }
            
            #bookingDetailsModal .card:hover {
                transform: translateY(-2px);
            }
            
            #bookingDetailsModal .badge {
                font-weight: 500;
                font-size: 0.85rem;
            }
            
            #bookingDetailsModal .bg-light {
                background-color: #f8fafc !important;
            }
            
            #bookingDetailsModal .text-secondary {
                color: #64748b !important;
            }
            
            #bookingDetailsModal .shadow-sm {
                box-shadow: 0 2px 8px rgba(0,0,0,0.05) !important;
            }
            
            #bookingDetailsModal .vr {
                width: 1px;
                height: 40px;
                background-color: #e2e8f0;
            }
        </style>
    `;

        document.getElementById('bookingDetailsContent').innerHTML = content;

        // Update modal title
        const modalTitle = document.querySelector('#bookingDetailsModal .modal-title');
        if (modalTitle) {
            modalTitle.innerHTML = '<i class="bi bi-journal-text me-2"></i>Booking Details';
        }

        const modalEl = document.getElementById('bookingDetailsModal');
        bookingModalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
        bookingModalInstance.show();
    }

    function closeBookingDetails() {
        allowBodyScroll();
        const modalEl = document.getElementById('bookingDetailsModal');
        if (bookingModalInstance) {
            bookingModalInstance.hide();
        } else {
            bootstrap.Modal.getOrCreateInstance(modalEl).hide();
        }
    }

    class PaginationManager {
        constructor() {
            this.currentPage = 1;
            this.entriesPerPage = 10;
            this.totalEntries = <?= count($requests) ?>;
            this.tableBody = document.querySelector('tbody');
            this.allRows = Array.from(document.querySelectorAll('.booking-row'));
            this.filteredRows = this.allRows;
            this.init();
        }

        init() {
            this.setupEventListeners();
            this.updateDisplay();
        }

        setupEventListeners() {
            document.getElementById('entriesPerPage').addEventListener('change', (e) => {
                const value = e.target.value;
                this.entriesPerPage = value === 'all' ? this.totalEntries : parseInt(value);
                this.currentPage = 1;
                this.updateDisplay();
            });

            document.getElementById('prevPage').addEventListener('click', () => {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.updateDisplay();
                }
            });

            document.getElementById('nextPage').addEventListener('click', () => {
                const totalPages = this.getTotalPages();
                if (this.currentPage < totalPages) {
                    this.currentPage++;
                    this.updateDisplay();
                }
            });

            const searchInput = document.getElementById('searchBookings');
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

            this.allRows.forEach(row => row.style.display = 'none');

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

            prevBtn.disabled = this.currentPage === 1;
            nextBtn.disabled = this.currentPage === totalPages || totalPages === 0;

            let pageNumbersHtml = '';
            const maxVisiblePages = 5;

            if (totalPages <= maxVisiblePages) {
                for (let i = 1; i <= totalPages; i++) {
                    pageNumbersHtml += this.getPageButtonHtml(i);
                }
            } else {
                if (this.currentPage <= 3) {
                    for (let i = 1; i <= 4; i++) {
                        pageNumbersHtml += this.getPageButtonHtml(i);
                    }
                    pageNumbersHtml += `<span class="btn btn-sm bg-secondary border-0 text-white" disabled>...</span>`;
                    pageNumbersHtml += this.getPageButtonHtml(totalPages);
                } else if (this.currentPage >= totalPages - 2) {
                    pageNumbersHtml += this.getPageButtonHtml(1);
                    pageNumbersHtml += `<span class="btn btn-sm bg-secondary border-0 text-white" disabled>...</span>`;
                    for (let i = totalPages - 3; i <= totalPages; i++) {
                        pageNumbersHtml += this.getPageButtonHtml(i);
                    }
                } else {
                    pageNumbersHtml += this.getPageButtonHtml(1);
                    pageNumbersHtml += `<span class="btn btn-sm bg-secondary border-0 text-white" disabled>...</span>`;
                    for (let i = this.currentPage - 1; i <= this.currentPage + 1; i++) {
                        pageNumbersHtml += this.getPageButtonHtml(i);
                    }
                    pageNumbersHtml += `<span class="btn btn-sm bg-secondary border-0 text-white" disabled>...</span>`;
                    pageNumbersHtml += this.getPageButtonHtml(totalPages);
                }
            }

            pageNumbersContainer.innerHTML = pageNumbersHtml;

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
    }

    function applyFilters() {
        const query = document.getElementById('searchBookings').value;
        if (window.paginationManager) {
            window.paginationManager.searchBookings(query);
        }
    }

    function clearFilters() {
        document.getElementById('searchBookings').value = '';
        if (window.paginationManager) {
            window.paginationManager.searchBookings('');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        window.paginationManager = new PaginationManager();
    });



    function openAssignModal(request) {
        document.getElementById('modal_request_id').value = request.id;
        document.getElementById('modal_user_name').innerText = request.user_name;
        document.getElementById('modal_pets').innerText = 'Pets: ' + (request.pet_names || 'N/A');
        document.getElementById('modal_package').innerText = request.package_name + ' (₹' + request.total_price + ')';
        document.getElementById('modal_booking_id').innerText = 'Booking ID: ' + request.booking_id;

        // Default admin price to package price
        document.getElementById('admin_price').value = request.total_price;

        // Ensure bootstrap is available
        if (typeof bootstrap !== 'undefined') {
            const modal = new bootstrap.Modal(document.getElementById('assignModal'));
            modal.show();
        } else {
            // Fallback for older bootstrap/jquery if needed, or just log error
            console.error('Bootstrap is not loaded');
            // Try jQuery modal as fallback
            if (typeof $ !== 'undefined' && typeof $.fn.modal !== 'undefined') {
                $('#assignModal').modal('show');
            } else {
                alert('Error: UI libraries not loaded properly.');
            }
        }

        loadProviders(request.booking_id);
        loadQuotations(request.booking_id);
    }

    function loadQuotations(bookingId) {
        const select = document.getElementById('quotationSelect');
        select.innerHTML = '<option value="">-- Loading Quotations... --</option>';

        fetch('<?= base_url("getQuotations") ?>/' + bookingId)
            .then(response => response.json())
            .then(data => {
                select.innerHTML = '<option value="">-- Select a Quotation --</option>';
                if (data.quotations && data.quotations.length > 0) {
                    data.quotations.forEach(q => {
                        const option = document.createElement('option');
                        option.value = q.id;
                        option.dataset.price = q.bid_amount;
                        option.dataset.providerId = q.provider_id;
                        option.innerText = `${q.provider_name} - ₹${q.bid_amount}`;
                        select.appendChild(option);
                    });
                } else {
                    select.innerHTML = '<option value="">No quotations received yet</option>';
                }
            })
            .catch(err => {
                select.innerHTML = '<option value="">Error loading quotations</option>';
            });
    }

    function onQuotationSelect(select) {
        const option = select.selectedOptions[0];
        if (option && option.value) {
            const price = option.dataset.price;
            const providerId = option.dataset.providerId;

            document.getElementById('admin_price').value = price;

            // Highlight and select provider
            const providerCard = document.querySelector(`.provider-card[onclick*="${providerId}"]`);
            if (providerCard) {
                selectProvider(providerCard, providerId);
                // Scroll to provider card
                providerCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                selectedProviderId = providerId;
                document.getElementById('submitAssignBtn').disabled = false;
            }
        }
    }

    function loadProviders(bookingId) {
        const list = document.getElementById('providers_list');
        list.innerHTML = '<div class="col-12 text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-2 text-muted text-uppercase fw-bold small">Searching nearby providers...</p></div>';

        selectedProviderId = null;
        document.getElementById('submitAssignBtn').disabled = true;

        fetch('<?= base_url("getNearbyProviders") ?>/' + bookingId)
            .then(response => response.json())
            .then(data => {
                if (data.providers && data.providers.length > 0) {
                    allNearbyProviders = data.providers;
                    renderProviders(allNearbyProviders);
                } else {
                    list.innerHTML = '<div class="col-12 text-center py-5"><i class="bi bi-exclamation-triangle fs-1 text-warning"></i><p class="mt-2 text-muted fw-bold">No nearby providers found for this service area.</p></div>';
                }
            })
            .catch(err => {
                list.innerHTML = '<div class="col-12 text-center py-5"><i class="bi bi-x-circle fs-1 text-danger"></i><p class="mt-2 text-danger fw-bold">Error loading providers. Please try again.</p></div>';
            });
    }

    function renderProviders(providers) {
        const list = document.getElementById('providers_list');
        let html = '';
        providers.forEach(p => {
            html += `
            <div class="col-md-6 provider-item">
                <div class="card provider-card h-100 rounded-3" onclick="selectProvider(this, ${p.provider_id})">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 45px; height: 45px;">
                                ${p.provider_name.charAt(0)}
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="fw-bold text-dark text-truncate">${p.provider_name}</div>
                                <div class="small text-muted"><i class="bi bi-telephone me-1"></i>${p.provider_phone}</div>
                            </div>
                        </div>
                        <hr class="my-2 opacity-50">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="text-dark small fw-bold"><i class="bi bi-geo-alt-fill text-danger me-1"></i>${p.area || 'N/A'}</div>
                            <div class="badge bg-success bg-opacity-10 text-dark smaller px-2">${parseFloat(p.distance).toFixed(2) || 'N/A'} km</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-warning small fw-bold"><i class="bi bi-star-fill me-1"></i>${parseFloat(p.rating).toFixed(2) || 'N/A'}</div>
                            <div class="text-muted smaller">Verified Completion</div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        });
        list.innerHTML = html;
    }

    // Search filtering
    document.getElementById('providerSearch').addEventListener('input', function (e) {
        const query = e.target.value.toLowerCase();
        const items = document.querySelectorAll('.provider-item');

        items.forEach(item => {
            const text = item.innerText.toLowerCase();
            item.style.display = text.includes(query) ? '' : 'none';
        });
    });

    function selectProvider(card, id) {
        document.querySelectorAll('.provider-card').forEach(c => c.classList.remove('selected'));
        card.classList.add('selected');
        selectedProviderId = id;
        document.getElementById('submitAssignBtn').disabled = false;
    }

    function submitAssignment() {
        const price = document.getElementById('admin_price').value;
        if (!price || !selectedProviderId) {
            Swal.fire('Incomplete', 'Please set a price and select a provider.', 'warning');
            return;
        }

        const formData = new FormData(document.getElementById('assignForm'));
        formData.append('provider_id', selectedProviderId);

        const btn = document.getElementById('submitAssignBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Assigning...';

        fetch('<?= base_url("assignPremiumProvider") ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    Swal.fire({
                        title: 'Assigned!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#4f46e5'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message || 'Failed to assign provider', 'error');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>Confirm & Assign';
                }
            })
            .catch(err => {
                Swal.fire('Error', 'A network error occurred. Please check your connection.', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>Confirm & Assign';
            });
    }
</script>

<?php echo $this->include('admin/footer'); ?>
<?php echo $this->endSection(); ?>