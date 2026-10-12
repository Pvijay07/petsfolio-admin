<?php echo $this->extend('admin/includes/header'); ?>
<!--start content-->
<?php echo $this->section('content'); ?>
<style>
    /* SaaS Design System & Hero Banner */
    .nl-hero-banner {
        background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 25%, #3730a3 60%, #4f46e5 100%);
        border-radius: 20px;
        padding: 1.75rem 2rem;
        color: white;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(49, 46, 129, 0.2), 0 8px 10px -6px rgba(49, 46, 129, 0.2);
    }
    .nl-hero-banner::after {
        content: '';
        position: absolute;
        top: -30%;
        right: -10%;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .nl-live-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.3px;
        margin-bottom: 0.5rem;
    }
    .nl-stat-card {
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 14px;
        padding: 0.75rem 1rem;
        transition: transform 0.2s ease, background 0.2s ease;
    }
    .nl-stat-card:hover {
        transform: translateY(-2px);
        background: rgba(255, 255, 255, 0.14);
    }
    .nl-stat-val {
        font-size: 1.5rem;
        font-weight: 800;
        line-height: 1.2;
    }
    .nl-stat-lbl {
        font-size: 0.75rem;
        opacity: 0.85;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 2px;
    }

    /* Segmented Status Tabs */
    .nl-segmented-tabs {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 6px;
        overflow-x: auto;
        white-space: nowrap;
        margin-bottom: 1.25rem;
    }
    .nl-tab-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        background: transparent;
    }
    .nl-tab-pill:hover {
        color: #1e293b;
        background: rgba(255, 255, 255, 0.8);
    }
    .nl-tab-pill.active {
        background: white;
        color: #4338ca;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
    }
    .nl-tab-pill .badge {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.2rem 0.5rem;
        border-radius: 50px;
    }

    /* Filter Toolbar */
    .nl-filter-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 0.75rem 1rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }
    .nl-search-wrap {
        position: relative;
        flex: 1;
        min-width: 240px;
    }
    .nl-search-wrap i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.9rem;
    }
    .nl-search-wrap input {
        padding-left: 36px !important;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        font-size: 0.875rem;
    }
    .nl-select {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        font-size: 0.875rem;
        padding: 0.45rem 1rem 0.45rem 0.75rem;
        background-color: #f8fafc;
        color: #334155;
        font-weight: 500;
    }

    /* Table Container */
    .nl-table-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -2px rgba(0, 0, 0, 0.02);
    }
    .nl-table {
        margin-bottom: 0;
        width: 100%;
    }
    .nl-table thead th {
        background: #f8fafc !important;
        color: #475569 !important;
        font-size: 0.75rem !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding: 0.85rem 0.75rem !important;
        border-bottom: 1px solid #e2e8f0 !important;
        white-space: nowrap;
    }
    .nl-table tbody td {
        padding: 0.85rem 0.75rem !important;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9 !important;
        font-size: 0.85rem;
        color: #1e293b;
    }
    .nl-table tbody tr {
        transition: background-color 0.15s ease;
        cursor: pointer;
    }
    .nl-table tbody tr:hover {
        background-color: #f8fafc !important;
    }

    /* Action Buttons */
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid transparent;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        background: transparent;
        text-decoration: none;
    }
    .action-btn-view {
        color: #0284c7;
        background: #f0f9ff;
        border-color: #e0f2fe;
    }
    .action-btn-view:hover {
        background: #0284c7;
        color: white;
    }
    .action-btn-notify {
        color: #d97706;
        background: #fffbeb;
        border-color: #fef3c7;
    }
    .action-btn-notify:hover {
        background: #d97706;
        color: white;
    }
    .action-btn-edit {
        color: #4f46e5;
        background: #eef2ff;
        border-color: #e0e7ff;
    }
    .action-btn-edit:hover {
        background: #4f46e5;
        color: white;
    }
    .action-btn-delete {
        color: #dc2626;
        background: #fef2f2;
        border-color: #fee2e2;
    }
    .action-btn-delete:hover {
        background: #dc2626;
        color: white;
    }

    /* Edit Modal Styling */
    #form-offcanvas {
        width: 450px !important;
    }
    .client-avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        border: 4px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    #send_push_notification {
        width: 500px !important;
    }
    #clientDetailsDrawer {
        width: 550px !important;
    }

    /* Pagination controls */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        background-color: #f8fafc;
        border-top: 1px solid #e2e8f0;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .pagination-row.hidden {
        display: none !important;
    }
    .pagination-row.search-hidden {
        display: none !important;
    }
</style>

<main class="main-content">
    <div class="position-relative iq-banner">
        <!--Nav Start-->
        <?php echo $this->include('admin/nav_bar') ?>
    </div>

    <div class="container-fluid content-inner px-4" id="bk-pgrs">
        <?php
        $clients = $clients ?? [];
        $totalClientsCount = !empty($clients) ? count($clients) : 0;
        $activeClientsCount = $active_count ?? 0;
        $inactiveClientsCount = $inactive_count ?? 0;
        $incompleteClientsCount = $incomplete_count ?? 0;
        $globalTotalCount = $total_count ?? $totalClientsCount;
        $withPetsCount = 0;
        if (!empty($clients)) {
            foreach ($clients as $c) {
                if (!empty($c->pets)) $withPetsCount++;
            }
        }
        $currStatus = $current_status ?? ($_GET['status'] ?? 'active');
        ?>

        <!-- Hero Banner -->
        <div class="nl-hero-banner">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <div class="nl-live-pill">
                        <i class="bi bi-circle-fill text-success" style="font-size: 0.55rem;"></i> System Active
                    </div>
                    <h1 class="h2 fw-bold text-white mb-1">Clients Network</h1>
                    <p class="text-white-50 mb-0 small">Manage client profiles, wallet balances, registered pets, and push alerts</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-warning d-flex align-items-center gap-1 shadow-sm px-3 py-2 fw-semibold opacity-50"
                        id="send-bulk-notification" data-bs-toggle="offcanvas" data-bs-target="#send_push_notification" disabled>
                        <i class="fa-regular fa-paper-plane me-1"></i> Bulk Notification
                    </button>
                    <a href="javascript:void(0)" onclick="downloadProviders()"
                        class="btn btn-light d-flex align-items-center gap-2 shadow-sm px-3 py-2 fw-semibold border">
                        <i class="fa-solid fa-download text-primary"></i> Download
                    </a>
                </div>
            </div>

            <!-- KPI Metric Stat Cards -->
            <div class="row g-3">
                <div class="col-6 col-md-2">
                    <div class="nl-stat-card">
                        <div class="nl-stat-val text-white"><?= $totalClientsCount ?></div>
                        <div class="nl-stat-lbl text-white-50">Total in View</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="nl-stat-card">
                        <div class="nl-stat-val text-success"><?= $activeClientsCount ?></div>
                        <div class="nl-stat-lbl text-white-50">Active Clients</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="nl-stat-card">
                        <div class="nl-stat-val text-danger"><?= $inactiveClientsCount ?></div>
                        <div class="nl-stat-lbl text-white-50">Blocked / Inactive</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="nl-stat-card">
                        <div class="nl-stat-val text-warning"><?= $incompleteClientsCount ?></div>
                        <div class="nl-stat-lbl text-white-50">Incomplete Profiles</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="nl-stat-card">
                        <div class="nl-stat-val text-info"><?= $withPetsCount ?></div>
                        <div class="nl-stat-lbl text-white-50">With Registered Pets</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Segmented Status Tabs -->
        <div class="nl-segmented-tabs">
            <a href="<?= base_url('clients?status=active') ?>" class="nl-tab-pill <?= $currStatus === 'active' ? 'active' : '' ?>">
                <i class="bi bi-person-check-fill text-success"></i> Active Clients
                <span class="badge <?= $currStatus === 'active' ? 'bg-primary' : 'bg-light text-dark' ?>"><?= $activeClientsCount ?></span>
            </a>
            <a href="<?= base_url('clients?status=inactive') ?>" class="nl-tab-pill <?= $currStatus === 'inactive' ? 'active' : '' ?>">
                <i class="bi bi-person-x-fill text-danger"></i> Blocked Clients
                <span class="badge <?= $currStatus === 'inactive' ? 'bg-danger' : 'bg-light text-dark' ?>"><?= $inactiveClientsCount ?></span>
            </a>
            <a href="<?= base_url('clients?status=incomplete') ?>" class="nl-tab-pill <?= $currStatus === 'incomplete' ? 'active' : '' ?>">
                <i class="bi bi-person-exclamation text-warning"></i> Incomplete Users
                <span class="badge <?= $currStatus === 'incomplete' ? 'bg-warning text-dark' : 'bg-light text-dark' ?>"><?= $incompleteClientsCount ?></span>
            </a>
            <a href="<?= base_url('clients?status=all') ?>" class="nl-tab-pill <?= $currStatus === 'all' ? 'active' : '' ?>">
                <i class="bi bi-people-fill text-primary"></i> All Clients
                <span class="badge <?= $currStatus === 'all' ? 'bg-dark' : 'bg-light text-dark' ?>"><?= $globalTotalCount ?></span>
            </a>
        </div>

        <!-- Filter Toolbar -->
        <div class="nl-filter-card">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="nl-search-wrap">
                    <i class="fas fa-search"></i>
                    <input type="text" class="form-control" id="searchClients"
                        placeholder="Search clients by name, email, phone, location...">
                </div>
                <div class="d-flex align-items-center gap-3 text-nowrap">
                    <div class="d-flex align-items-center gap-2">
                        <label class="text-muted small mb-0">Show:</label>
                        <select class="form-select form-select-sm nl-select" id="entriesPerPageTop" style="width: 85px;">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="10000">All</option>
                        </select>
                        <span class="text-muted small">entries</span>
                    </div>
                    <button type="button" class="btn btn-light border d-flex align-items-center gap-1 text-muted" onclick="resetClientFilters()" title="Reset Search">
                        <i class="fas fa-undo small"></i> Clear
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Table Card -->
        <div class="nl-table-card">
            <div class="table-responsive">
                <table class="table nl-table" id="clientsTable">
                    <thead>
                        <tr>
                            <th style="width: 45px;" class="text-center">
                                <input type="checkbox" class="form-check-input" name="select_all_table"
                                    id="select-all-table" onclick="selectAllTable(this)">
                            </th>
                            <th style="width: 55px;" class="text-center">S.No</th>
                            <th>Client Details</th>
                            <th>Acquisition Source</th>
                            <th>Pets Registered</th>
                            <th>Location</th>
                            <th>Joined Date</th>
                            <th>Wallet</th>
                            <th class="text-center">Device & App</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php
                        $i = 1;
                        foreach ($clients as $client) {
                        ?>
                            <tr class="pagination-row" data-client-data='<?= htmlspecialchars(json_encode($client), ENT_QUOTES, 'UTF-8') ?>'>
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input select-table-row"
                                        id="datatable-row-<?= $client->id ?>" name="datatable_ids[]"
                                        value="<?= $client->id ?>" data-module="users">
                                </td>
                                <td class="text-center text-muted fw-bold"><?= $i ?></td>

                                <!-- Client Column with Email and Mobile -->
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php
                                        $name = trim($client->name ?? '');
                                        $hasName = !empty($name);
                                        $hasEmail = !empty($client->email);
                                        $hasGender = !empty($client->gender);
                                        $isIncomplete = (!$hasName || !$hasEmail || !$hasGender);
                                        $initial = $hasName ? strtoupper(substr($name, 0, 1)) : ($client->phone ? '#' : '?');
                                        ?>
                                        <!-- Avatar -->
                                        <div class="rounded-circle <?= $isIncomplete ? 'bg-warning bg-opacity-10 text-warning border-warning' : 'bg-primary bg-opacity-10 text-primary border-primary' ?> fw-bold d-flex align-items-center justify-content-center flex-shrink-0"
                                            style="width:34px; height:34px; border: 2px solid;">
                                            <?= $initial ?>
                                        </div>

                                        <!-- Details -->
                                        <div>
                                            <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                                <a href="<?= base_url('userDetails/user/' . $client->id) ?>" class="text-decoration-none text-dark" style="transition: color 0.2s;">
                                                    <?= $hasName ? esc($name) : '<span class="text-muted fst-italic">Name Not Provided</span>' ?>
                                                </a>
                                                <?php if ($isIncomplete): ?>
                                                    <span class="badge bg-warning-subtle text-warning border border-warning px-1 py-0" style="font-size: 0.65rem;">
                                                        Incomplete Profile
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="text-muted small" style="font-size: 0.75rem;">
                                                <i class="bi bi-envelope me-1"></i> <?= $hasEmail ? esc($client->email) : '<span class="text-muted opacity-75">No email</span>' ?>
                                            </div>
                                            <div class="text-muted small" style="font-size: 0.75rem;">
                                                <i class="bi bi-telephone me-1"></i> <?= !empty($client->phone) ? esc($client->phone) : '--' ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Acquisition Source Column -->
                                <td>
                                    <?php
                                    $rawSource = strtolower(trim($client->source ?? ''));
                                    if ($rawSource === 'web') {
                                        echo '<span class="badge bg-purple-subtle text-purple border" style="background:#f3e8ff; color:#7e22ce; border-color:#d8b4fe;"><i class="bi bi-globe me-1"></i> Web</span>';
                                    } elseif (!empty($rawSource)) {
                                        echo '<span class="badge bg-light text-dark border"><i class="bi bi-tag me-1"></i> ' . esc(ucfirst($rawSource)) . '</span>';
                                    } elseif (!empty($client->has_app_installed)) {
                                        echo '<span class="badge bg-primary-subtle text-primary border border-primary"><i class="bi bi-phone me-1"></i> Mobile App</span>';
                                    } else {
                                        echo '<span class="badge bg-secondary-subtle text-secondary border border-secondary"><i class="bi bi-phone me-1"></i> App / Direct</span>';
                                    }
                                    ?>
                                </td>


                                <!-- Pets Column -->
                                <td>
                                    <?php if (!empty($client->pets)): ?>
                                        <div class="d-flex flex-wrap gap-1">
                                            <?php foreach ($client->pets as $index => $pet): ?>
                                                <?php if ($index < 3): ?>
                                                    <span class="badge bg-info-subtle text-info border border-info px-2 py-1" style="font-size: 0.72rem;">
                                                        <?= $pet->name ?>
                                                    </span>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                            <?php if (count($client->pets) > 3): ?>
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary px-2 py-1" style="font-size: 0.72rem;">
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
                                            <span class="text-muted small text-truncate" style="max-width: 130px;">
                                                <?= $client->addresses[0]->state ?? 'N/A' ?>
                                            </span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small">--</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="text-muted small"><?= esc(date('d M Y', strtotime($client->created_at)) ?? '') ?></span>
                                </td>
                                <!-- Wallet Amount -->
                                <td>
                                    <span class="badge bg-success-subtle text-success border border-success px-2 py-1" style="font-weight: 600;">
                                        ₹<?= $client->wallet_amount ?? 0 ?>
                                    </span>
                                </td>
                                <!-- Device & App Column -->
                                <td>
                                    <?php if (!empty($client->latest_device)): ?>
                                        <?php $dev = $client->latest_device; ?>
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
                                <!-- Status -->
                                <td class="text-center">
                                    <div class="form-check form-switch d-inline-block m-0">
                                        <input type="checkbox" class="form-check-input status"
                                            data-url="<?= base_url() ?>updateStatus" data-module="users"
                                            data-id="<?= $client->id ?>" value="<?= $client->status ?>"
                                            <?= $client->status == 'active' ? 'checked' : '' ?>>
                                    </div>
                                </td>

                                <!-- Action Column -->
                                <td>
                                    <div class="d-flex gap-1 justify-content-center">
                                        <button type="button" class="action-btn action-btn-view view-client-btn"
                                            data-client-id="<?= $client->id ?>" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        <button type="button" class="action-btn action-btn-notify"
                                            data-assign-module="<?= $client->id ?>" data-id="<?= $client->id ?>"
                                            data-module="users" data-bs-toggle="offcanvas" data-bs-target="#send_push_notification"
                                            title="Send Notification">
                                            <i class="fa-regular fa-paper-plane"></i>
                                        </button>

                                        <button type="button" class="action-btn action-btn-edit"
                                            data-bs-toggle="offcanvas" data-bs-target="#form-offcanvas"
                                            data-edit="<?= $client->id ?>" data-module="users"
                                            data-url="<?= base_url() ?>getUser" data-id="<?= $client->id ?>" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>

                                        <a href="<?= base_url() ?>deleteUser" id="delete-employees-<?= $client->id ?>"
                                            class="action-btn action-btn-delete" data-type="ajax"
                                            data-method="DELETE" data-token="<?= csrf_hash() ?>"
                                            data-id="<?= $client->id ?>"
                                            data-confirm="Are you certain you want to delete this client?" title="Delete">
                                            <i class="fa fa-trash"></i>
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
                            <option value="10000">All</option>

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
            <div class="offcanvas-header" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.08);">
                <div>
                    <h5 class="offcanvas-title text-white fw-bold d-flex align-items-center gap-2 mb-1" style="font-size: 1.05rem;">
                        <i class="fa-regular fa-paper-plane text-warning"></i> Send Push Notification
                    </h5>
                    <p class="text-white-50 mb-0 small" style="font-size: 0.78rem;">Dispatch instant notification alert to selected clients</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body" style="background: #f8fafc; padding: 1.25rem;">
                <div id="notificationRecipientInfo" class="alert alert-primary py-2 px-3 mb-3 d-flex align-items-center gap-2 rounded-3 border-0 shadow-sm" style="background: rgba(99,102,241,0.1); color: #4338ca; font-size: 0.82rem; font-weight: 500;">
                    <i class="fas fa-users-viewfinder fs-6"></i>
                    <span id="recipientCountText">Sending to selected clients</span>
                </div>
                <div class="container-fluid p-0">
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
            <div class="offcanvas-footer border-top p-3 bg-white">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light flex-fill rounded-3 py-2 fw-semibold" data-bs-dismiss="offcanvas">
                        <i class="fas fa-times me-2"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary flex-fill rounded-3 py-2 fw-semibold" id="sendNotificationBtn" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); border: none;">
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
            <div class="offcanvas-header" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.08);">
                <div>
                    <h5 class="offcanvas-title text-white fw-bold d-flex align-items-center gap-2 mb-1" id="form-offcanvasLabel" style="font-size: 1.05rem;">
                        <i class="fas fa-user-edit text-info"></i> Edit Client
                    </h5>
                    <p class="text-white-50 mb-0 small" style="font-size: 0.78rem;">Update client personal details and account status</p>
                </div>
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
        aria-labelledby="clientDetailsDrawerLabel" style="width: 520px; max-width: 92vw;">
        <div class="offcanvas-header" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.08);">
            <div>
                <h5 class="offcanvas-title text-white fw-bold d-flex align-items-center gap-2 mb-1" id="clientDetailsDrawerLabel" style="font-size: 1.05rem;">
                    <i class="bi bi-person-badge text-primary"></i> Client Details
                </h5>
                <p class="text-white-50 mb-0 small" style="font-size: 0.78rem;">Detailed profile, pets, and address directory</p>
            </div>
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
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e9ecef;
    }

    .client-detail-section:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-size: 0.78rem;
        color: #6c757d;
        font-weight: 500;
        margin-bottom: 0.2rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .detail-value {
        font-size: 0.95rem;
        color: #212529;
        font-weight: 600;
    }

    .detail-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 0.85rem 1rem;
        margin-bottom: 0.75rem;
        border: 1px solid #edf2f7;
    }

    .detail-card:last-child {
        margin-bottom: 0;
    }

    /* Avatar in drawer */
    .client-avatar {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        font-weight: bold;
        margin-right: 1rem;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
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
    document.addEventListener("DOMContentLoaded", function() {
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
                // Sync top dropdown
                const topSelect = document.getElementById('entriesPerPageTop');
                if (topSelect) {
                    topSelect.value = this.value;
                }
            });
        }

        // Handle top dropdown change
        const entriesPerPageTop = document.getElementById('entriesPerPageTop');
        if (entriesPerPageTop) {
            entriesPerPageTop.addEventListener('change', function() {
                rowsPerPage = parseInt(this.value);
                currentPage = 1; // Reset to first page
                updatePagination();
                // Update select all checkbox state
                updateSelectAllState();
                // Sync bottom dropdown
                if (perPageSelect) {
                    perPageSelect.value = this.value;
                }
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
                sendBulkNotificationBtn.innerHTML = `<i class="fa-regular fa-paper-plane me-1"></i> Send Push Notification` + 
                    (selectedIds.length > 0 ? ` <span class="badge bg-white text-primary ms-1" style="font-size:0.75rem; vertical-align:middle;">${selectedIds.length}</span>` : '');
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

        // Prevent row selection / details click when clicking checkboxes or buttons
        $(document).on('click', '.select-table-row, .status, .action-btn, a, button', function(e) {
            e.stopPropagation();
        });

        if (sendBulkNotificationBtn) {
            sendBulkNotificationBtn.addEventListener("click", function() {
                let selectedIds = getSelectedIds();
                if (selectedIds.length > 0 && selectedProviderIds) {
                    selectedProviderIds.value = JSON.stringify(selectedIds);
                }
                const countText = document.getElementById('recipientCountText');
                if (countText) {
                    countText.textContent = `Sending to ${selectedIds.length} selected client${selectedIds.length > 1 ? 's' : ''}`;
                }
                $('#sendNotification1').data('id', null);
            });
        }

        // Send notification form submission
        $(document).on('click', '[data-assign-module]', function(e) {
            e.stopPropagation();
            const dataId = $(this).data('id');
            const moduleId = $(this).data('module');
            const targetForm = $('#sendNotification1');
            targetForm.data('id', dataId);
            targetForm.data('module', moduleId);
            if (selectedProviderIds) {
                selectedProviderIds.value = JSON.stringify([dataId]);
            }
            const countText = document.getElementById('recipientCountText');
            if (countText) {
                countText.textContent = `Sending to 1 client (ID: #${dataId})`;
            }
        });

        $('#sendNotification1').on('submit', function(e) {
            e.preventDefault();

            const url = $(this).attr('action');
            let id = $(this).data('id') || $('#selectedProviderIds').val();
            const title = $('#Title').val();
            const message = $('#description').val();
            let module = $(this).data('module') || 'users';

            const submitBtn = $('#sendNotificationBtn');
            const originalBtnHtml = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Sending...');

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
                success: function(response) {
                    submitBtn.prop('disabled', false).html(originalBtnHtml);
                    if (response.status) {
                        const offcanvasEl = document.getElementById('send_push_notification');
                        const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl);
                        if (offcanvas) offcanvas.hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'Sent!',
                            text: 'Notification sent successfully.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error!', response.message || 'Failed to send notification.', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    submitBtn.prop('disabled', false).html(originalBtnHtml);
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

        document.addEventListener('click', function(e) {
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
            searchInput.addEventListener('input', function() {
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

    // Reset filters
    function resetClientFilters() {
        const searchInput = document.getElementById('searchClients');
        if (searchInput) {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
        }
        const topSelect = document.getElementById('entriesPerPageTop');
        if (topSelect) {
            topSelect.value = '10';
            topSelect.dispatchEvent(new Event('change'));
        }
    }
</script>

<?php echo $this->endSection() ?>