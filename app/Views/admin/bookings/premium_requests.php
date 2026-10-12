<?php echo $this->extend('admin/includes/header'); ?>
<!--start content-->

<?php echo $this->section('content'); ?>

<?php
// Compute summary stats for hero banner and status tabs
$totalCount = count($requests ?? []);
$statusCounts = [
    'Requested' => 0,
    'Assigned'  => 0,
    'Paid'      => 0,
    'Accepted'  => 0,
    'Completed' => 0,
    'Cancelled' => 0,
];
$totalRevenue = 0;
$todayDateStr = date('Y-m-d');
$dueTodayCount = 0;

if (!empty($requests)) {
    foreach ($requests as $r) {
        $st = $r->status ?? 'Requested';
        if (isset($statusCounts[$st])) {
            $statusCounts[$st]++;
        } else {
            $statusCounts[$st] = 1;
        }
        $totalRevenue += (float)($r->total_price ?? 0);
        if (!empty($r->service_start_date) && substr($r->service_start_date, 0, 10) === $todayDateStr) {
            $dueTodayCount++;
        }
    }
}
?>

<style>
  /* Hero Banner */
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
    font-size: 22px;
    font-weight: 800;
    line-height: 1.15;
    color: #ffffff;
  }

  /* Segmented Category Tabs */
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

  /* Status Filter Tabs Bar */
  .nl-status-tabs-container {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 6px 8px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    flex-wrap: wrap;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
  }
  .nl-status-tab {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 10px;
    font-size: 12.5px;
    font-weight: 600;
    color: #64748b;
    border: 1px solid transparent;
    background: transparent;
    cursor: pointer;
    transition: all 0.15s ease;
  }
  .nl-status-tab:hover {
    color: #1e293b;
    background: #f1f5f9;
  }
  .nl-status-tab.active {
    background: #ede9fe;
    color: #4f46e5;
    border-color: #c7d2fe;
  }
  .nl-status-count {
    border-radius: 50px;
    padding: 1px 7px;
    font-size: 11px;
    font-weight: 700;
    background: rgba(0,0,0,0.06);
    color: #64748b;
  }
  .nl-status-tab.active .nl-status-count {
    background: #4f46e5;
    color: #ffffff;
  }
  .status-dot-sm {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    display: inline-block;
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

  /* Table Card & Header Override (Eliminates header.php gradient bleed) */
  .nl-table-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    box-shadow: 0 2px 14px rgba(0,0,0,0.025);
    overflow: hidden;
  }
  #premiumRequestsTable thead th {
    background: #f8fafc !important;
    color: #475569 !important;
    border-bottom: 2px solid #e2e8f0 !important;
    font-size: 11.5px !important;
    font-weight: 700 !important;
    letter-spacing: 0.05em !important;
    text-transform: uppercase !important;
    padding: 14px 16px !important;
    white-space: nowrap !important;
  }
  #premiumRequestsTable tbody td {
    padding: 14px 16px !important;
    border-bottom: 1px solid #f1f5f9;
  }
</style>

<main class="main-content">
    <div class="position-relative iq-banner">
        <?php echo $this->include('admin/nav_bar') ?>
    </div>

    <div class="container-fluid px-4 content-inner pb-5" id="page_layout">
        <!-- Modern Hero Banner Section -->
        <div class="nl-hero-banner">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <div class="nl-live-pill">
                        <i class="bi bi-star-fill text-warning"></i>
                        <span>Premium Tier Services</span>
                    </div>
                    <h2 class="fw-bold text-white mb-2" style="font-size: 28px; letter-spacing: -0.02em;">Premium Service Requests</h2>
                    <p class="mb-0 text-white text-opacity-75" style="font-size: 13.5px; max-width: 540px; line-height: 1.55;">
                        Manage and assign customized VIP and bespoke premium pet service requests, dispatches, and custom quotes.
                    </p>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex align-items-center gap-2 flex-wrap justify-content-lg-end">
                        <div class="nl-stat-card">
                            <span class="label">Total Premium</span>
                            <div class="value"><?= number_format($totalCount) ?></div>
                        </div>
                        <div class="nl-stat-card">
                            <span class="label">Requested</span>
                            <div class="value" style="color: #fde047;"><?= number_format($statusCounts['Requested'] ?? 0) ?></div>
                        </div>
                        <div class="nl-stat-card">
                            <span class="label">Assigned</span>
                            <div class="value" style="color: #6ee7b7;"><?= number_format($statusCounts['Assigned'] ?? 0) ?></div>
                        </div>
                        <div class="nl-stat-card">
                            <span class="label">Est. Revenue</span>
                            <div class="value" style="color: #93c5fd; font-size: 19px;">₹<?= number_format($totalRevenue) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Segmented Category Tabs & Action Bar -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
            <div class="nl-segmented-tabs">
                <a href="<?= base_url('bookingRequests') ?>" class="nl-tab-pill">
                    <i class="bi bi-list-task"></i>
                    <span>Normal Requests</span>
                    <span class="nl-tab-badge"><?= $normalCount ?? 0 ?></span>
                </a>
                <a href="<?= base_url('premiumRequests') ?>" class="nl-tab-pill active">
                    <i class="bi bi-star-fill text-warning"></i>
                    <span>Premium Requests</span>
                    <span class="nl-tab-badge"><?= $premiumCount ?? $totalCount ?></span>
                </a>
            </div>

            <div class="d-flex align-items-center gap-2">
                <button type="button" class="nl-btn-action" onclick="exportPremiumRequestsToCSV()">
                    <i class="bi bi-download"></i>
                    <span>Export CSV</span>
                </button>
            </div>
        </div>

        <!-- Status Filter Tabs -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div class="nl-status-tabs-container">
                <button type="button" class="nl-status-tab active" data-status="all" onclick="filterByStatus('all', this)">
                    <span>All</span>
                    <span class="nl-status-count"><?= $totalCount ?></span>
                </button>
                <button type="button" class="nl-status-tab" data-status="Requested" onclick="filterByStatus('Requested', this)">
                    <span class="status-dot-sm bg-warning"></span>
                    <span>Requested</span>
                    <span class="nl-status-count"><?= $statusCounts['Requested'] ?? 0 ?></span>
                </button>
                <button type="button" class="nl-status-tab" data-status="Assigned" onclick="filterByStatus('Assigned', this)">
                    <span class="status-dot-sm bg-info"></span>
                    <span>Assigned</span>
                    <span class="nl-status-count"><?= $statusCounts['Assigned'] ?? 0 ?></span>
                </button>
                <button type="button" class="nl-status-tab" data-status="Paid" onclick="filterByStatus('Paid', this)">
                    <span class="status-dot-sm bg-success"></span>
                    <span>Paid</span>
                    <span class="nl-status-count"><?= $statusCounts['Paid'] ?? 0 ?></span>
                </button>
                <button type="button" class="nl-status-tab" data-status="Accepted" onclick="filterByStatus('Accepted', this)">
                    <span class="status-dot-sm" style="background: #10b981;"></span>
                    <span>Accepted</span>
                    <span class="nl-status-count"><?= $statusCounts['Accepted'] ?? 0 ?></span>
                </button>
                <button type="button" class="nl-status-tab" data-status="Completed" onclick="filterByStatus('Completed', this)">
                    <span class="status-dot-sm bg-primary"></span>
                    <span>Completed</span>
                    <span class="nl-status-count"><?= $statusCounts['Completed'] ?? 0 ?></span>
                </button>
                <button type="button" class="nl-status-tab" data-status="Cancelled" onclick="filterByStatus('Cancelled', this)">
                    <span class="status-dot-sm bg-secondary"></span>
                    <span>Cancelled</span>
                    <span class="nl-status-count"><?= $statusCounts['Cancelled'] ?? 0 ?></span>
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
                            placeholder="Search by customer, pet, phone, package, area, ID...">
                    </div>
                </div>
                <div class="col-lg-3 col-md-4">
                    <select class="nl-select w-100" id="packageFilter" onchange="applyPackageFilter(this.value)">
                        <option value="all">All Packages</option>
                        <?php
                        $uniquePackages = [];
                        foreach ($requests ?? [] as $req) {
                            if (!empty($req->package_name) && !in_array($req->package_name, $uniquePackages)) {
                                $uniquePackages[] = $req->package_name;
                            }
                        }
                        sort($uniquePackages);
                        foreach ($uniquePackages as $pkg):
                        ?>
                            <option value="<?= htmlspecialchars($pkg) ?>"><?= htmlspecialchars($pkg) ?></option>
                        <?php endforeach; ?>
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

        <!-- Table Card -->
        <div class="nl-table-card mb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="premiumRequestsTable">
                    <thead>
                        <tr>
                            <th class="text-center">Customer & Pets</th>
                            <th class="text-center">Package & Dates</th>
                            <th class="text-center">Area</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Admin Price</th>
                            <th class="text-center">Actions</th>
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
                                    <div class="fw-bold text-dark pkg-name"><?= $request->package_name ?></div>
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

            <div class="d-flex flex-wrap justify-content-between align-items-center px-4 py-3 border-top bg-white gap-2">
                <div class="text-muted small" id="paginationInfo">
                    Showing 1 to 10 of <?= $totalCount ?> entries
                </div>
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
<div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            <div class="modal-header px-4 py-3 border-0 text-white" style="background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 25%, #3730a3 60%, #4f46e5 100%) !important;">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(255, 255, 255, 0.15);">
                        <i class="bi bi-star-fill text-warning fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-white mb-0" id="assignModalLabel">Assign Premium Provider</h5>
                        <span class="text-white-50 tiny">Manual dispatch & pricing assignment</span>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white opacity-75 hover-opacity-100" data-bs-dismiss="modal" aria-label="Close"></button>
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
            <div class="modal-footer border-top bg-light p-3 px-4">
                <button type="button" class="btn btn-sm btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm" id="submitAssignBtn" disabled
                    onclick="submitAssignment()">
                    <i class="bi bi-check-circle-fill me-1"></i>Confirm & Assign
                </button>
            </div>
        </div>
    </div>
</div>

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
            <div class="modal-body p-0"
                style="overflow-y: auto; flex: 1; scrollbar-width: none; -ms-overflow-style: none;">
                <div class="p-2">
                    <div id="bookingDetailsContent"></div>
                </div>
            </div>
            <div class="modal-footer border-top d-flex justify-content-between align-items-center">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold shadow-xs copy-details-btn" onclick="copyCurrentModalBooking(this)">
                        <i class="bi bi-clipboard-check me-1"></i>Copy Booking Details
                    </button>
                </div>
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
        window._currentModalBooking = booking;

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
                            ${booking.duration_days ? `
                            <div class="col-6">
                                <span class="text-secondary small">Duration</span>
                                <p class="fw-bold text-dark mb-0 mt-1"><i class="bi bi-hourglass-split me-1"></i>${booking.duration_days} Hours</p>
                            </div>
                            ` : ''}
                            ${modalTotalDays ? `
                            <div class="col-6">
                                <span class="text-secondary small">Total Days</span>
                                <p class="fw-bold text-dark mb-0 mt-1"><i class="bi bi-calendar-range me-1"></i>${modalTotalDays} Days</p>
                            </div>
                            ` : ''}
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
            #bookingDetailsModal .modal-header {
                border-bottom: 1px solid #e9ecef;
            }
            #bookingDetailsModal .modal-footer {
                border-top: 1px solid #e9ecef;
            }
            #bookingDetailsModal .card {
                transition: transform 0.2s ease-in-out;
            }
            #bookingDetailsModal .card:hover {
                transform: translateY(-2px);
            }
            #bookingDetailsModal .badge {
                font-size: 0.85rem;
                letter-spacing: 0.5px;
            }
            #bookingDetailsModal .form-label {
                font-size: 0.9rem;
                font-weight: 500;
            }
            #bookingDetailsModal p {
                font-size: 0.95rem;
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
        } else if (booking.houseno_floor || booking.building_blockno || booking.address || booking.formatted_address) {
            const fullAddr = [booking.houseno_floor, booking.building_blockno, booking.address, booking.formatted_address].filter(Boolean).join(', ');
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

    class PaginationManager {
        constructor() {
            this.currentPage = 1;
            this.entriesPerPage = 10;
            this.totalEntries = <?= count($requests ?? []) ?>;
            this.tableBody = document.querySelector('tbody');
            this.allRows = Array.from(document.querySelectorAll('.booking-row'));
            this.filteredRows = this.allRows;
            this.currentStatus = 'all';
            this.currentPackage = 'all';
            this.currentQuery = '';
            this.init();
        }

        init() {
            this.setupEventListeners();
            this.applyFilters();
        }

        setupEventListeners() {
            const entriesSelect = document.getElementById('entriesPerPage');
            if (entriesSelect) {
                entriesSelect.addEventListener('change', (e) => {
                    const value = e.target.value;
                    this.entriesPerPage = value === 'all' ? this.totalEntries : parseInt(value);
                    this.currentPage = 1;
                    this.updateDisplay();
                });
            }

            const prevBtn = document.getElementById('prevPage');
            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                        this.updateDisplay();
                    }
                });
            }

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

            const searchInput = document.getElementById('searchBookings');
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    this.currentQuery = e.target.value;
                    this.applyFilters();
                });
            }
        }

        applyFilters() {
            const query = (this.currentQuery || '').toLowerCase().trim();
            const status = (this.currentStatus || 'all').toLowerCase();
            const pkg = (this.currentPackage || 'all').toLowerCase();

            this.filteredRows = this.allRows.filter(row => {
                // Status match
                if (status !== 'all') {
                    const rowStatus = (row.dataset.status || '').toLowerCase();
                    if (rowStatus !== status) return false;
                }

                // Package match
                if (pkg !== 'all') {
                    const pkgEl = row.querySelector('.pkg-name');
                    const rowPkg = (pkgEl ? pkgEl.innerText : row.innerText || '').toLowerCase();
                    if (!rowPkg.includes(pkg)) return false;
                }

                // Search query match
                if (query) {
                    const rowText = row.textContent.toLowerCase();
                    if (!rowText.includes(query)) return false;
                }

                return true;
            });

            this.currentPage = 1;
            this.updateDisplay();
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

            const paginationInfo = document.getElementById('paginationInfo');
            if (paginationInfo) {
                if (this.filteredRows.length === 0) {
                    paginationInfo.innerText = 'Showing 0 to 0 of 0 entries';
                } else {
                    paginationInfo.innerText = `Showing ${start + 1} to ${end} of ${this.filteredRows.length} entries`;
                }
            }

            this.updatePaginationControls();
        }

        updatePaginationControls() {
            const totalPages = this.getTotalPages();
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');
            const pageNumbersContainer = document.getElementById('pageNumbers');

            if (prevBtn) prevBtn.disabled = this.currentPage === 1;
            if (nextBtn) nextBtn.disabled = this.currentPage === totalPages || totalPages === 0;

            if (!pageNumbersContainer) return;

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
    }

    function filterByStatus(status, btn) {
        document.querySelectorAll('.nl-status-tab').forEach(b => b.classList.remove('active'));
        if (btn) btn.classList.add('active');
        if (window.paginationManager) {
            window.paginationManager.currentStatus = status;
            window.paginationManager.applyFilters();
        }
    }

    function applyPackageFilter(pkg) {
        if (window.paginationManager) {
            window.paginationManager.currentPackage = pkg;
            window.paginationManager.applyFilters();
        }
    }

    function applyFilters() {
        const query = document.getElementById('searchBookings')?.value || '';
        if (window.paginationManager) {
            window.paginationManager.currentQuery = query;
            window.paginationManager.applyFilters();
        }
    }

    function clearFilters() {
        const searchInput = document.getElementById('searchBookings');
        if (searchInput) searchInput.value = '';
        const pkgSelect = document.getElementById('packageFilter');
        if (pkgSelect) pkgSelect.value = 'all';

        document.querySelectorAll('.nl-status-tab').forEach(b => b.classList.remove('active'));
        const allBtn = document.querySelector('.nl-status-tab[data-status="all"]');
        if (allBtn) allBtn.classList.add('active');

        if (window.paginationManager) {
            window.paginationManager.currentQuery = '';
            window.paginationManager.currentStatus = 'all';
            window.paginationManager.currentPackage = 'all';
            window.paginationManager.applyFilters();
        }
    }

    function exportPremiumRequestsToCSV() {
        const table = document.getElementById('premiumRequestsTable');
        if (!table) return;
        const rows = Array.from(table.querySelectorAll('tbody tr.booking-row'));
        if (!rows.length) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Notice', 'No premium request records to export.', 'info');
            } else {
                alert('No premium request records to export.');
            }
            return;
        }

        const headers = ['S.No.', 'Customer Name', 'Pet Details', 'Booking ID', 'Package', 'Dates', 'Days', 'Area', 'City', 'Status', 'Admin Price'];
        const csvRows = [headers.join(',')];

        let exportIndex = 1;
        rows.forEach((row) => {
            if (row.style.display === 'none') return;
            const cells = row.querySelectorAll('td');
            if (cells.length >= 6) {
                const sNo = exportIndex++;
                const customerName = (cells[0].querySelector('.fw-bold')?.innerText || '').replace(/"/g, '""').trim();
                const pets = (cells[0].querySelector('.text-muted')?.innerText || '').replace(/"/g, '""').trim();
                const bookingId = (cells[0].querySelector('.text-primary')?.innerText || '').replace(/ID:\s*/g, '').replace(/"/g, '""').trim();
                const pkg = (cells[1].querySelector('.pkg-name')?.innerText || cells[1].querySelector('.fw-bold')?.innerText || '').replace(/"/g, '""').trim();
                const dates = (cells[1].querySelector('.text-muted')?.innerText || '').replace(/\s+/g, ' ').replace(/"/g, '""').trim();
                const days = (cells[1].querySelector('.badge')?.innerText || '').replace(/"/g, '""').trim();
                const area = (cells[2].querySelector('.text-dark')?.innerText || '').replace(/"/g, '""').trim();
                const city = (cells[2].querySelector('.text-muted')?.innerText || '').replace(/"/g, '""').trim();
                const status = (cells[3].querySelector('.badge')?.innerText || '').replace(/"/g, '""').trim();
                const price = (cells[4].innerText || '').replace(/[₹,]/g, '').trim();

                csvRows.push(`"${sNo}","${customerName}","${pets}","${bookingId}","${pkg}","${dates}","${days}","${area}","${city}","${status}","${price}"`);
            }
        });

        if (csvRows.length === 1) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Notice', 'No visible records matching current filters.', 'info');
            } else {
                alert('No visible records matching current filters.');
            }
            return;
        }

        const blob = new Blob([csvRows.join('\n')], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.setAttribute('href', url);
        link.setAttribute('download', `premium_requests_${new Date().toISOString().slice(0, 10)}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
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