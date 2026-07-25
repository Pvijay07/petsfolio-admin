<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="apple-touch-icon" sizes="76x76"
    href="https://cmschamps.com/psf-admin/public/assets/images/icons/Petsfolio.png">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<!-- Library / Plugin Css Build -->
<link rel="stylesheet" href="<?= base_url() ?>public/assets/css/core/libs.min.css" />

<!-- Aos Animation Css -->
<link rel="stylesheet" href="<?= base_url() ?>public/assets/vendor/aos/dist/aos.css" />

<!-- Hope Ui Design System Css -->
<link rel="stylesheet" href="<?= base_url() ?>public/assets/css/hope-ui.min.css?v=2.0.0" />

<!-- Custom Css -->
<link rel="stylesheet" href="<?= base_url() ?>public/assets/css/custom.min.css?v=2.0.0" />

<!-- Dark Css -->
<link rel="stylesheet" href="<?= base_url() ?>public/assets/css/dark.min.css" />

<!-- Customizer Css -->
<link rel="stylesheet" href="<?= base_url() ?>public/assets/css/customizer.min.css" />

<!-- RTL Css -->
<link rel="stylesheet" href="<?= base_url() ?>public/assets/css/rtl.min.css" />

<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

<!-- Lucide Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lucide-static@latest/font/lucide.css" />
<!-- html2canvas for ticket generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
        --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        --danger-gradient: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        --glass-bg: rgba(255, 255, 255, 0.8);
        --glass-border: rgba(255, 255, 255, 0.5);
        --text-main: #1e293b;
        --text-muted: #64748b;
    }

    body {
        background: #f1f5f9;
        font-family: 'Roboto', system-ui, -apple-system, sans-serif;
        color: var(--text-main);
    }

    .premium-banner {
        background: var(--primary-gradient);
        border-radius: 24px;
        padding: 3rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        color: white;
    }

    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
    }

    .table-container {
        padding: 1.5rem;
    }

    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 0.75rem;
    }

    .custom-table th {
        background: transparent;
        border: none;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 1rem;
    }

    .custom-table tbody tr {
        background: white;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .custom-table tbody tr:hover {
        transform: scale(1.005);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.05);
        z-index: 10;
    }

    .custom-table td {
        padding: 1.25rem 1rem;
        border: none;
        vertical-align: middle;
    }

    .custom-table td:first-child {
        border-radius: 12px 0 0 12px;
    }

    .custom-table td:last-child {
        border-radius: 0 12px 12px 0;
    }

    .status-pill {
        padding: 0.4rem 0.8rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-active {
        background: #dcfce7;
        color: #166534;
    }

    .status-partial {
        background: #fef9c3;
        color: #854d0e;
    }

    .status-full {
        background: #fee2e2;
        color: #991b1b;
    }

    .qr-preview {
        width: 45px;
        height: 45px;
        padding: 4px;
        background: white;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .qr-preview:hover {
        transform: scale(1.1);
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .btn-soft-primary {
        background: #eef2ff;
        color: #4f46e5;
    }

    .btn-soft-primary:hover {
        background: #4f46e5;
        color: white;
    }

    .search-group {
        position: relative;
        max-width: 400px;
    }

    .search-group i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
    }

    .search-input {
        padding-left: 2.75rem !important;
        border-radius: 12px !important;
        border: 1px solid #e2e8f0 !important;
        height: 45px !important;
    }

    .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
        cursor: pointer;
    }

    .modal-content {
        border-radius: 24px;
        border: none;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .modal-header {
        border-bottom: none;
        padding: 2rem 2rem 1rem;
    }

    .modal-footer {
        border-top: none;
        padding: 1rem 2rem 2rem;
    }

    .modal-body {
        padding: 1rem 2rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 0.5rem;
    }

    .premium-input {
        border-radius: 12px !important;
        border: 1px solid #e2e8f0 !important;
        padding: 0.75rem 1rem !important;
        transition: all 0.2s !important;
    }

    .premium-input:focus {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1) !important;
    }
</style>

</head>
<main class="main-content">
    <div class="container-fluid px-4">
        <!-- Premium Banner -->
        <div class="premium-banner">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-5 fw-bold mb-2">Event Bookings</h1>
                    <p class="fs-5 opacity-75 mb-0">Monitor real-time QR scans and manage attendee entries with
                        precision.</p>
                </div>
                <div class="col-md-4 text-md-end mt-4 mt-md-0">
                    <button type="button"
                        class="btn btn-white btn-lg shadow-sm fw-bold px-4 rounded-pill bg-white text-primary border-0"
                        data-bs-toggle="modal" data-bs-target="#freeTicketModal">
                        <i class="bi bi-plus-circle-fill me-2"></i> Issue Free Ticket
                    </button>
                </div>
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success border-0 shadow-sm rounded-4 px-4 py-3 mb-4">
                <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <!-- Filters & Search -->
        <div class="glass-card p-4">
            <div class="row align-items-center g-3">
                <div class="col-md-6">
                    <div class="search-group">
                        <i class="bi bi-search"></i>
                        <input type="text" id="bookingSearch" class="form-control search-input"
                            placeholder="Search by ID, name or email...">
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="btn-group rounded-pill overflow-hidden shadow-sm" role="group">
                        <input type="radio" class="btn-check" name="statusFilter" id="filterAll" value="all" checked>
                        <label class="btn btn-outline-primary px-3" for="filterAll">All</label>

                        <input type="radio" class="btn-check" name="statusFilter" id="filterActive" value="active">
                        <label class="btn btn-outline-primary px-3" for="filterActive">Active</label>

                        <input type="radio" class="btn-check" name="statusFilter" id="filterPartial" value="partial">
                        <label class="btn btn-outline-primary px-3" for="filterPartial">Partial</label>

                        <input type="radio" class="btn-check" name="statusFilter" id="filterFull" value="full">
                        <label class="btn btn-outline-primary px-3" for="filterFull">Full</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bookings Table -->
        <div class="glass-card overflow-hidden shadow-sm border-0">
            <div class="table-responsive table-container">
                <table class="custom-table" id="bookingsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ticket</th>
                            <th>Customer</th>
                            <th>Package</th>
                            <th>Entries</th>
                            <th>Status</th>
                            <th>WhatsApp</th>
                            <th>Last Scan</th>
                            <!-- <th>Staff</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($bookings)): ?>
                            <?php foreach ($bookings as $booking): ?>

                                <?php
                                $statusClass = 'status-active';
                                $statusText = 'Active';
                                $statusValue = 'active';
                                if ($booking['status'] == 'checked_in' || $booking['used_entries'] >= $booking['max_entries']) {
                                    $statusClass = 'status-full';
                                    $statusText = 'Full Entry';
                                    $statusValue = 'full';
                                } elseif ($booking['used_entries'] > 0) {
                                    $statusClass = 'status-partial';
                                    $statusText = 'Partial';
                                    $statusValue = 'partial';
                                }
                                $petType = 'without_pet';

                                $packages = json_decode($booking['package_details'] ?? '', true);

                                if (!empty($packages) && is_array($packages)) {
                                    foreach ($packages as $key => $value) {

                                        // Convert key to lowercase
                                        $packageKey = strtolower($key);

                                        // Check if key contains 'pet'
                                        if ($booking['pet_name']) {
                                            $petType = 'with_pet';
                                            break;
                                        }
                                    }
                                }

                                // Generate booking code
                                $bookingCode = $booking['booking_code'] ?? strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $booking['booking_id'] . 'BK' . rand(100, 999)), 0, 7));
                                ?>
                                <tr class="booking-row" data-status="<?= $statusValue ?>">
                                    <td class="fw-bold text-primary">
                                        <div>#<?= $booking['booking_id'] ?></div>
                                        <?php if (strpos($booking['order_id'], 'CASH_') === 0): ?>
                                            <span class="badge bg-soft-info text-info border-info mt-1" style="font-size: 0.65rem; background: #e0f2fe;">offline: CASH</span>
                                        <?php elseif (strpos($booking['order_id'], 'COMP_') === 0 || strpos($booking['order_id'], 'GUEST_') === 0): ?>
                                            <span class="badge bg-soft-secondary text-secondary border-secondary mt-1" style="font-size: 0.65rem; background: #f1f5f9;">offline: FREE</span>
                                        <?php else: ?>
                                            <span class="badge bg-soft-success text-success border-success mt-1" style="font-size: 0.65rem; background: #f0fdf4;">ONLINE</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($booking['qr_image'])): ?>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="<?= ('https://beta.petsfolio.in/' . $booking['qr_image']) ?>"
                                                    class="qr-preview" alt="QR" data-bs-toggle="tooltip" title="Click to enlarge">
                                                <button onclick='downloadTicket(<?= json_encode([
                                                                                    "booking_id" => $booking["booking_id"],
                                                                                    "qr_image" => $booking["qr_image"],
                                                                                    "customer_name" => $booking["user_name"] ?? $booking["customer_name"] ?? "Guest",
                                                                                    "package_details" => $booking["package_details"] ?? "",
                                                                                    "booking_code" => $bookingCode,
                                                                                    "max_entries" => $booking["max_entries"],
                                                                                    "pet_type" => $petType
                                                                                ], JSON_HEX_APOS | JSON_HEX_QUOT) ?>)' class="btn-icon btn-soft-primary" title="Download Ticket">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted small">No QR</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span
                                                class="fw-bold search-target text-dark"><?= esc($booking['user_name'] ?? $booking['customer_name'] ?? 'Guest') ?></span>
                                            <small
                                                class="text-muted search-target"><?= esc($booking['customer_email'] ?? '') ?></small>
                                            <small
                                                class="text-muted search-target"><?= esc($booking['customer_phone'] ?? '') ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <?php if (!empty($booking['pet_name'])): ?>
                                                <div class="badge bg-soft-success text-success border-success mb-1 fw-bold shadow-sm d-inline-flex align-items-center px-2 py-1" style="background: #eefdf3; width: fit-content;">
                                                    <i class="bi bi-heart-fill me-1"></i>
                                                    <span>Pet Name: <?= esc($booking['pet_name']) ?></span>
                                                </div>
                                            <?php endif; ?>

                                            <?php
                                            $packages = json_decode($booking['package_details'] ?? '', true);
                                            if (!empty($packages)):
                                                foreach ($packages as $key => $pkg):
                                                    $name = is_array($pkg) && isset($pkg['name']) ? $pkg['name'] : $key;
                                                    $qty = is_array($pkg) ? ($pkg['qty'] ?? $pkg['quantity'] ?? 1) : $pkg;

                                                    // Clean up name: remove 'pet', 'no pet', and underscores
                                                    $displayName = str_replace(['no_pet_', 'pet_', '_', '-'], ['', '', ' ', ' '], $name);
                                                    $displayName = trim(ucwords($displayName));
                                                    if ($displayName == 'Complimentary Pass') $displayName = "Cash Pass";
                                                    if (empty($displayName)) $displayName = "Entry";
                                            ?>
                                                    <div class="badge bg-light text-dark border mb-1 fw-medium shadow-sm d-inline-flex align-items-center px-2 py-1 search-target" style="width: fit-content;">
                                                        <i class="bi bi-ticket-perforated text-primary me-1"></i>
                                                        <span><?= esc($displayName) ?></span>
                                                        <span class="text-primary mx-1">×</span>
                                                        <span class="fw-bold"><?= $qty ?></span>
                                                    </div>
                                                <?php endforeach;
                                            else: ?>
                                                <span class="badge bg-light text-muted fw-normal px-2 py-1" style="width: fit-content;">
                                                    <i class="bi bi-dash-circle me-1"></i><?= esc($booking['package_id'] ?? 'Free Pass') ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1"
                                                style="height: 6px; width: 60px; background-color: #e2e8f0;">
                                                <?php $percent = ($booking['max_entries'] > 0) ? ($booking['used_entries'] / $booking['max_entries'] * 100) : 0; ?>
                                                <div class="progress-bar bg-primary" role="progressbar"
                                                    style="width: <?= $percent ?>%"></div>
                                            </div>
                                            <span
                                                class="small fw-bold"><?= $booking['used_entries'] ?>/<?= $booking['max_entries'] ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-pill <?= $statusClass ?>"><?= $statusText ?></span>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch d-flex align-items-center gap-2">
                                            <input class="form-check-input whatsapp-toggle" type="checkbox"
                                                data-id="<?= $booking['id'] ?>" <?= ($booking['whatsapp_status'] == 'sent') ? 'checked' : '' ?>>
                                            <span
                                                class="badge <?= ($booking['whatsapp_status'] == 'sent') ? 'bg-success' : 'bg-secondary' ?> status-label">
                                                <?= ($booking['whatsapp_status'] == 'sent') ? 'Sent' : 'Pending' ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($booking['checked_in_at']): ?>
                                            <span
                                                class="small fw-bold text-nowrap"><?= date('d M, h:i A', strtotime($booking['checked_in_at'])) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted small">--</span>
                                        <?php endif; ?>
                                    </td>
                                    <!-- <td>
                                        <span
                                            class="badge bg-light text-dark fw-medium border"><?= $booking['checked_in_by'] ?? '--' ?></span>
                                    </td> -->
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="bi bi-inbox display-4 text-muted mb-3 d-block"></i>
                                    <p class="text-muted">No bookings found in the system.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Issue Free Ticket Modal -->
<div class="modal fade" id="freeTicketModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content overflow-hidden">
            <div class="modal-header">
                <h4 class="modal-title fw-bold">Complimentary Ticket</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= ('https://beta.petsfolio.in/api/pet-events/add-free-ticket') ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-4 text-center">
                        <div class="card-icon mx-auto mb-3"
                            style="width: 60px; height: 60px; background: #6366f1; border-radius: 20px; color: white; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-person-badge fs-2"></i>
                        </div>
                        <p class="text-muted mx-auto" style="max-width: 300px;">Issue a free entry pass to special
                            guests or partners.</p>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Guest Name</label>
                            <input type="text" name="name" class="form-control premium-input"
                                placeholder="e.g. John Wick" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control premium-input"
                                placeholder="john@domain.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-control premium-input" placeholder="+91">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pet Name</label>
                            <input type="text" name="pet_name" class="form-control premium-input" placeholder="e.g. Joey">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Mode</label>
                            <select name="payment_mode" class="form-select premium-input">
                                <option value="free">Free / Complimentary</option>
                                <option value="cash">Cash Payment</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Maximum Entries</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 rounded-start-3"><i
                                        class="bi bi-people"></i></span>
                                <input type="number" name="max_entries"
                                    class="form-control premium-input border-start-0" value="1" min="1" required
                                    style="border-radius: 0 12px 12px 0 !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light px-4 rounded-pill"
                        data-bs-dismiss="modal">Discard</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-pill shadow-sm"
                        style="background: #6366f1; border: none;">
                        Generate Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Hidden Ticket Container -->
<div id="hiddenTicketContainer" style="position: absolute; left: -9999px; top: -9999px;"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://petsfolio.in/admin/public/assets/js/core/libs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Search and Filter Logic
        function filterBookings() {
            const searchTerm = $('#bookingSearch').val().toLowerCase();
            const statusFilter = $('input[name="statusFilter"]:checked').val();

            $('.booking-row').each(function() {
                const row = $(this);
                const text = row.find('.search-target').text().toLowerCase() + row.find('td:first-child').text().toLowerCase();
                const status = row.data('status');

                const matchesSearch = text.includes(searchTerm);
                const matchesStatus = (statusFilter === 'all' || status === statusFilter);

                if (matchesSearch && matchesStatus) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        }

        $('#bookingSearch').on('input', filterBookings);
        $('input[name="statusFilter"]').on('change', filterBookings);

        // WhatsApp Toggle
        $('.whatsapp-toggle').on('change', function() {
            const id = $(this).data('id');
            const status = this.checked ? 'sent' : 'pending';
            const label = $(this).siblings('.status-label');

            label.text(status.charAt(0).toUpperCase() + status.slice(1));
            label.toggleClass('bg-success', status === 'sent').toggleClass('bg-secondary', status !== 'sent');

            fetch('<?= base_url('update-whatsapp-status') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: id,
                        status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'Status updated successfully'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update status'
                        });
                    }
                })
                .catch(err => console.error('Error:', err));
        });

        // QR Preview Zoom
        $('.qr-preview').on('click', function() {
            const src = $(this).attr('src');
            Swal.fire({
                imageUrl: src,
                imageWidth: 400,
                imageHeight: 400,
                showConfirmButton: false,
                background: '#fff',
                backdrop: `rgba(0,0,10,0.6) blur(5px)`
            });
        });
    });

    // Function to download ticket
    function downloadTicket(bookingData) {
        console.log("bookingData", bookingData)
        // Parse package details for ticket quantity
        let ticketQuantity = 1;
        let max_entries = bookingData?.
        max_entries || 1
        let packageName = 'Entry Pass';
        const customerName = bookingData.customer_name || 'Guest';
        const petLabel =
            bookingData.pet_type === 'with_pet' ?
            'With Pet 🐾' :
            'Without Pet';
        try {
            const packages = JSON.parse(bookingData.package_details || '[]');
            if (packages && packages.length > 0) {
                ticketQuantity = packages.reduce((total, pkg) => {
                    return total + (pkg.qty || pkg.quantity || 1);
                }, 0);
                packageName = packages[0].name || 'Entry Pass';
            }
        } catch (e) {
            ticketQuantity = 1;
        }

        // Format date
        const today = new Date();
        const formattedDate = today.toLocaleDateString('en-US', {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        }).replace(',', '');
        const formattedTime = today.toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        }).toLowerCase();

        // Create ticket HTML matching the image exactly
        const ticketId = `ticket_${Date.now()}`;
        const ticketHTML = `
           <div id="${ticketId}" 
                style="width: 450px; background: white; font-family: 'Roboto', Arial, sans-serif; line-height: 1.5;">
                <div style="
                padding: 20px;
                background-color: #dd2d4a;
                border-bottom-left-radius: 16px;
                border-bottom-right-radius: 16px;
                margin-bottom:12px;
            ">
                    <div style="font-size: 24px;color: #ffffff; font-weight: 700; margin-bottom: 5px; text-align:center">
                        Petsfolio Furry-Fest
                    </div>
                    <div style="color: #ffffff; font-size: 16px;  text-align:center">
                        Entry Pass
                    </div>
                </div>

                <div style="margin: 0 20px 20px 20px;
                            border: 2px dashed #ccc;
                            border-radius: 12px;
                            padding: 15px;">
                    <div style="display: flex; gap: 18px; align-items: flex-start;">

                        <div style="width: 140px; flex-shrink: 0;">
                            <img 
                                src="https://beta.petsfolio.in/admin/public/assets/images/FurryImage.jpeg"
                                style="width: 100%; border-radius: 12px; object-fit: cover;"
                                crossorigin="anonymous"
                            >
                        </div>

                        <div style="flex: 1;">

                            <div style="font-size: 15px; margin-bottom: 12px;">
                                <div style="margin-bottom: 4px;">
                                    Sun, 08 Mar 2026 @ 4:00pm
                                </div>
                                <div>Hyatt Gachibowli, Hyderabad</div>
                            </div>

                            <div style="border-top: 2px dashed #ccc; margin: 12px 0;"></div>
            <div style="font-size: 18px; font-weight: 700; margin-bottom: 6px;">
            1 ticket
                            </div>
                            <div style="font-size: 14px; margin-bottom: 6px;">
    <strong>Name:</strong> ${customerName}
</div>

<div style="
    display: inline-block;
    padding: 4px 10px;
    font-size: 13px;
    border-radius: 20px;
    background: ${bookingData.pet_type === 'with_pet' ? '#dcfce7' : '#f1f5f9'};
    color: ${bookingData.pet_type === 'with_pet' ? '#166534' : '#334155'};
    font-weight: 600;
    margin-bottom: 10px;
">
    ${petLabel}
</div>

                            <div style="font-size: 14px; font-weight: 700; margin-bottom: 6px;">
                                 ${max_entries > 1 ? 'No.of Entries' : 'No.of Entries'} : ${max_entries}
                            </div>
                            <div style="font-size: 15px;">
                                Booking ID: #${bookingData.booking_id}
                            </div>

                        </div>
                    </div>
                </div>
                <div style="padding: 0 20px 20px 20px;">
                    <div style="font-size: 18px; font-weight: 700; color: #10b981; margin-bottom: 5px;">
                        Congrats !!!
                    </div>
                    <div style="font-size: 15px; margin-bottom: 5px;">
                        Your ticket has been booked
                    </div>
                    <div style="font-size: 14px; color: #666; margin-bottom: 20px;">
                        Scan the QR code at the venue
                    </div>
                    <div style="text-align: center; margin: 20px 0;">
                        <img 
                            src="https://beta.petsfolio.in/${bookingData.qr_image}"
                            style="width: 180px; height: 180px; padding: 10px; background: white; border: 1px solid #ddd; border-radius: 8px;"
                            crossorigin="anonymous"
                        >
                    </div>

                   <div style="border-top: 1px solid #eee; padding: 20px 0 10px 0; text-align: center;">
    
    <div style="display: flex; justify-content: center; align-items: center; gap: 40px; flex-wrap: wrap;">
        
        <img 
            src="https://beta.petsfolio.in/admin/public/assets/images/LogoEvent.png"
            style="height: 60px;"
            crossorigin="anonymous"
        >

        <img 
            src="https://carniwel.com/cdn/shop/files/Carniwel_New_Logo_2.png?v=1718341379&width=280"
            style="height: 60px;"
            crossorigin="anonymous"
        >

    </div>

</div>
                </div>
            </div>
        `;

        // Add to hidden container
        $('#hiddenTicketContainer').html(ticketHTML);

        // Show loading
        Swal.fire({
            title: 'Generating Ticket...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Load images and capture
        let imagesLoaded = 0;
        const images = $(`#${ticketId} img`);
        const totalImages = images.length;

        if (totalImages === 0) {
            setTimeout(() => captureTicket(ticketId, bookingData.booking_id), 500);
        } else {
            images.each(function() {
                if (this.complete) {
                    imagesLoaded++;
                    if (imagesLoaded === totalImages) {
                        setTimeout(() => captureTicket(ticketId, bookingData.booking_id), 500);
                    }
                } else {
                    this.onload = function() {
                        imagesLoaded++;
                        if (imagesLoaded === totalImages) {
                            setTimeout(() => captureTicket(ticketId, bookingData.booking_id), 500);
                        }
                    };
                    this.onerror = function() {
                        imagesLoaded++;
                        if (imagesLoaded === totalImages) {
                            setTimeout(() => captureTicket(ticketId, bookingData.booking_id), 500);
                        }
                    };
                }
            });
        }
    }

    function captureTicket(ticketId, bookingId) {
        const element = document.getElementById(ticketId);

        html2canvas(element, {
            scale: 2,
            backgroundColor: '#ffffff',
            logging: false,
            allowTaint: true,
            useCORS: true,
            windowWidth: 450,
            windowHeight: element.offsetHeight
        }).then(canvas => {
            // Close loading
            Swal.close();

            // Convert to blob and download
            canvas.toBlob(function(blob) {
                const link = document.createElement('a');
                link.download = `Ticket_${bookingId}.png`;
                link.href = URL.createObjectURL(blob);
                link.click();

                // Clean up
                setTimeout(() => {
                    URL.revokeObjectURL(link.href);
                }, 100);

                // Show success
                Swal.fire({
                    icon: 'success',
                    title: 'Downloaded!',
                    text: 'Ticket saved successfully',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000
                });
            }, 'image/png', 1.0);

            // Clean up
            $('#hiddenTicketContainer').empty();
        }).catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to generate ticket. Please try again.'
            });
            $('#hiddenTicketContainer').empty();
        });
    }
</script>