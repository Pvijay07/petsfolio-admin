<?php echo $this->extend('admin/includes/header'); ?>

<?php echo $this->section('content'); ?>

<main class="main-content">
    <div class="position-relative iq-banner">
        <?php echo $this->include('admin/nav_bar') ?>
    </div>
    
    <div class="container-fluid px-4 content-inner pb-5" id="page_layout">
        <!-- Top Banner Header -->
        <div class="row mb-4 recon-gradient-primary p-4 pt-5 rounded-4 position-relative mx-0 shadow-sm">
            <div class="col-12">
                <div class="text-white position-relative z-1 d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-white bg-opacity-25 text-white rounded-pill px-3 py-1 text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Finance</span>
                            <span class="text-white-50">/</span>
                            <span class="text-white fw-semibold small">Reconciliation</span>
                        </div>
                        <h1 class="h2 fw-bold mb-1 text-white">Financial Reconciliation</h1>
                        <p class="text-white-75 mb-0" style="font-size: 0.95rem;">
                            Service-wise ledger, job breakdown, and combined transaction history
                        </p>
                    </div>

                    <!-- Top Right Controls -->
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div class="d-flex align-items-center bg-white rounded-pill p-1 shadow-sm">
                            <select class="form-select border-0 bg-transparent fw-semibold text-dark ps-3 pe-4 py-2" id="userTypeFilter" onchange="handleUserTypeChange()" style="border-radius: 50px; font-size: 0.9rem; min-width: 180px; box-shadow: none;">
                                <option value="service_provider" selected>Service Provider</option>
                                <option value="client">Client</option>
                                <option value="platform">Platform Level</option>
                            </select>
                        </div>

                        <!-- Searchable Entity Picker -->
                        <div id="entityPickerWrapper" class="bg-white rounded-pill p-1 shadow-sm" style="min-width: 260px;">
                            <select class="form-select border-0 bg-transparent fw-semibold text-dark ps-3 pe-4 py-2" id="entitySelect" onchange="handleEntitySelectChange()" style="border-radius: 50px; font-size: 0.9rem; box-shadow: none;">
                                <!-- Dynamic Options -->
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Main Tabs Navigation -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 p-2 bg-white rounded-5 shadow-sm mt-4">
                    <div class="d-flex gap-2 flex-wrap" id="reconNavTabs">
                        <button class="recon-tab active" onclick="switchMainTab('detailed')" id="detailedTab">
                            <i class="bi bi-person-badge me-2"></i>Detailed Ledger
                        </button>
                        <button class="recon-tab" onclick="switchMainTab('list')" id="listTab">
                            <i class="bi bi-wallet2 me-2"></i>All Entities List
                            <span class="badge bg-primary bg-opacity-10 text-primary ms-2 py-1 px-2 rounded-pill">
                                <?= count($walkers) + count($clients) ?>
                            </span>
                        </button>
                        <button class="recon-tab" onclick="switchMainTab('service')" id="serviceTab">
                            <i class="bi bi-calendar-check me-2"></i>Service Overview
                            <span class="badge bg-primary bg-opacity-10 text-primary ms-2 py-1 px-2 rounded-pill">
                                <?= count($summary) ?>
                            </span>
                        </button>
                        <button class="recon-tab" onclick="switchMainTab('mismatch')" id="mismatchTab">
                            <i class="bi bi-exclamation-triangle me-2"></i>Discrepancies
                            <span class="badge bg-danger bg-opacity-10 text-danger ms-2 py-1 px-2 rounded-pill">
                                <?= count($mismatch_alerts) ?>
                            </span>
                        </button>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-2" onclick="refreshReconData()">
                            <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- TAB 1: DETAILED RECONCILIATION (SCREEN 1 & SCREEN 2) -->
        <!-- ================================================================= -->
        <div id="detailedTabContent" class="main-tab-content">
            
            <!-- SCREEN 1: Service-Wise Ledger & Combined History -->
            <div id="reconScreenOverview">
                
                <!-- Profile / Entity Header Card -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="recon-avatar-circle" id="profileAvatarInitial">
                                    V
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h4 class="fw-bold mb-0 text-dark" id="profileNameDisplay">Vishnuvardhan</h4>
                                        <span class="badge rounded-pill bg-light text-secondary border px-3 py-1" id="profileRoleBadge" style="font-weight: 500;">Provider</span>
                                    </div>
                                    <div class="text-muted small d-flex flex-wrap align-items-center gap-2" id="profileMetaDisplay">
                                        <span id="profileAreaText"><i class="bi bi-geo-alt me-1"></i>Chanda Nagar</span>
                                        <span>•</span>
                                        <span id="profileIdText">ID: 6219</span>
                                        <span>•</span>
                                        <span id="profileJoinedText">Joined Jun 2026</span>
                                        <span>•</span>
                                        <span id="profilePhoneText">9765001150</span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3">
                                <div class="text-end d-none d-md-block">
                                    <small class="text-muted d-block" id="profileBalanceLabel">Service Balance</small>
                                    <span class="h4 fw-bold text-primary mb-0" id="profileTopBalance">₹0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Service-Wise Reconciliation -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-dark">Service-wise reconciliation</h5>
                    <div class="d-flex align-items-center gap-2">
                        <select class="form-select form-select-sm rounded-pill border shadow-sm px-3 py-1" id="reconPeriodFilter" onchange="handlePeriodFilterChange()" style="font-size: 0.85rem; width: 140px;">
                            <option value="all">All time</option>
                            <option value="month" selected>This month</option>
                            <option value="week">This week</option>
                            <option value="today">Today</option>
                        </select>
                    </div>
                </div>

                <!-- 5 Service Cards Grid -->
                <div class="row g-3 mb-4" id="serviceCardsGrid">
                    <!-- Cards dynamically rendered via renderServiceCards() -->
                </div>

                <!-- Dark Purple Summary Bar -->
                <div class="recon-purple-banner shadow-sm mb-5">
                    <div class="row text-center text-md-start align-items-center g-3">
                        <div class="col-6 col-md-3">
                            <div class="text-white-50 small mb-1" id="bannerLabel1">Total credited</div>
                            <div class="h3 fw-bold text-white mb-0" id="bannerValTotalCredited">₹0.00</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-white-50 small mb-1" id="bannerLabel2">Total debited</div>
                            <div class="h3 fw-bold text-white mb-0" id="bannerValTotalDebited">₹0.00</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-white-50 small mb-1" id="bannerLabel3">Earned amount</div>
                            <div class="h3 fw-bold text-white mb-0" id="bannerValEarnedRefund">₹0.00</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-white-50 small mb-1" id="bannerLabel4">Net service balance</div>
                            <div class="h3 fw-bold text-white mb-0" id="bannerValNetBalance">₹0.00</div>
                        </div>
                    </div>
                </div>

                <!-- Section: All Service Transactions -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                            <div>
                                <h5 class="fw-bold mb-0 text-dark">All service transactions</h5>
                                <small class="text-muted">Combined transaction history across all services</small>
                            </div>

                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <!-- Service Filter Pills -->
                                <div class="d-flex align-items-center gap-1 flex-wrap" id="serviceFilterPills">
                                    <button class="recon-filter-pill active" onclick="applyServicePillFilter('all')">All services</button>
                                    <button class="recon-filter-pill" onclick="applyServicePillFilter('Walking')">Walking</button>
                                    <button class="recon-filter-pill" onclick="applyServicePillFilter('Grooming')">Grooming</button>
                                    <button class="recon-filter-pill" onclick="applyServicePillFilter('Training')">Training</button>
                                    <button class="recon-filter-pill" onclick="applyServicePillFilter('Boarding')">Boarding</button>
                                    <button class="recon-filter-pill" onclick="applyServicePillFilter('Veterinary')">Vet</button>
                                </div>

                                <!-- Search Input -->
                                <div class="input-group" style="width: 260px;">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-pill ps-3">
                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control bg-light border-start-0 rounded-end-pill py-2" id="combinedTxnSearch" placeholder="Search by txn ID, description" oninput="handleCombinedSearchInput()" style="font-size: 0.85rem; box-shadow: none;">
                                </div>
                            </div>
                        </div>

                        <!-- Combined Transactions Table -->
                        <div class="table-responsive rounded-3">
                            <table class="table table-hover align-middle mb-0" id="combinedTransactionsTable" style="min-width: 960px;">
                                <thead>
                                    <tr style="background-color: #f8fafc;">
                                        <th class="py-3 px-3 text-secondary text-uppercase fw-semibold text-nowrap" style="font-size: 0.75rem; letter-spacing: 0.5px; width: 45px;">#</th>
                                        <th class="py-3 px-3 text-secondary text-uppercase fw-semibold text-nowrap" style="font-size: 0.75rem; letter-spacing: 0.5px; min-width: 150px;">TRANSACTION ID</th>
                                        <th class="py-3 px-3 text-secondary text-uppercase fw-semibold text-nowrap" style="font-size: 0.75rem; letter-spacing: 0.5px; min-width: 110px;">SERVICE</th>
                                        <th class="py-3 px-3 text-secondary text-uppercase fw-semibold text-nowrap" style="font-size: 0.75rem; letter-spacing: 0.5px; min-width: 130px;">DATE & TIME</th>
                                        <th class="py-3 px-3 text-secondary text-uppercase fw-semibold text-nowrap" style="font-size: 0.75rem; letter-spacing: 0.5px; min-width: 90px;">TYPE</th>
                                        <th class="py-3 px-3 text-secondary text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px; min-width: 280px;">DESCRIPTION</th>
                                        <th class="py-3 px-3 text-secondary text-uppercase fw-semibold text-end text-nowrap" style="font-size: 0.75rem; letter-spacing: 0.5px; min-width: 100px;">DEBIT</th>
                                        <th class="py-3 px-3 text-secondary text-uppercase fw-semibold text-end text-nowrap" style="font-size: 0.75rem; letter-spacing: 0.5px; min-width: 100px;">CREDIT</th>
                                        <th class="py-3 px-3 text-secondary text-uppercase fw-semibold text-end text-nowrap" style="font-size: 0.75rem; letter-spacing: 0.5px; min-width: 120px;">SERVICE BAL</th>
                                    </tr>
                                </thead>
                                <tbody id="combinedTransactionsTbody">
                                    <!-- Dynamic rows -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Combined Pagination Controls -->
                        <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 pt-3 border-top gap-2" id="combinedPaginationContainer">
                            <div class="text-muted small" id="combinedPaginationInfo">
                                Showing 1 to 10 of 0 entries
                            </div>
                            <div class="d-flex align-items-center gap-1" id="combinedPaginationButtons">
                                <!-- Page Buttons -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SCREEN 2: Service-Specific Reconciliation & Jobs Breakdown -->
            <div id="reconScreenServiceJobs" style="display: none;">
                
                <!-- Breadcrumb & Back Header -->
                <div class="mb-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <a href="javascript:void(0)" onclick="backToOverviewScreen()" class="text-primary text-decoration-none fw-semibold small d-inline-flex align-items-center">
                            <i class="bi bi-arrow-left me-1"></i> Reconciliation
                        </a>
                        <span class="text-muted">/</span>
                        <span class="text-secondary small fw-semibold" id="s2ServiceBreadcrumb">Walking</span>
                    </div>

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <h2 class="h3 fw-bold mb-1 text-dark" id="s2ServiceHeaderTitle">Walking reconciliation</h2>
                            <p class="text-muted mb-0" id="s2ServiceHeaderSubtitle">Provider #6219 - Vishnuvardhan · 3 walking jobs, shown individually with their own transactions</p>
                        </div>

                        <button class="btn btn-outline-secondary rounded-pill px-3 py-2 text-dark fw-semibold" onclick="backToOverviewScreen()" style="font-size: 0.85rem;">
                            <i class="bi bi-grid me-1"></i> All Services Ledger
                        </button>
                    </div>
                </div>

                <!-- Service Dark Purple Summary Bar -->
                <div class="recon-purple-banner shadow-sm mb-4">
                    <div class="row text-center text-md-start align-items-center g-3">
                        <div class="col-6 col-md-3">
                            <div class="text-white-50 small mb-1">Jobs</div>
                            <div class="h3 fw-bold text-white mb-0" id="s2StatJobsCount">0</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-white-50 small mb-1">Total credited</div>
                            <div class="h3 fw-bold text-white mb-0" id="s2StatTotalCredited">₹0.00</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-white-50 small mb-1">Total debited</div>
                            <div class="h3 fw-bold text-white mb-0" id="s2StatTotalDebited">₹0.00</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-white-50 small mb-1" id="s2StatNetBalanceLabel">Net walking balance</div>
                            <div class="h3 fw-bold text-white mb-0" id="s2StatNetBalance">₹0.00</div>
                        </div>
                    </div>
                </div>

                <!-- Section: Jobs, shown separately -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-dark">Jobs, shown separately</h5>
                    <span class="badge bg-light text-secondary border rounded-pill px-3 py-1" id="s2JobsBadgeCount">0 jobs</span>
                </div>

                <!-- Jobs Container -->
                <div id="s2JobsListContainer">
                    <!-- Dynamic Job Cards -->
                </div>
            </div>

        </div>

        <!-- ================================================================= -->
        <!-- TAB 2: ALL ENTITIES LIST VIEW -->
        <!-- ================================================================= -->
        <div id="listTabContent" class="main-tab-content" style="display: none;">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">All Wallets Directory</h5>
                            <small class="text-muted">Click any row or 'View Ledger' to open detailed service-wise reconciliation</small>
                        </div>

                        <div class="input-group" style="width: 280px;">
                            <span class="input-group-text bg-light border-end-0 rounded-start-pill ps-3">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control bg-light border-start-0 rounded-end-pill py-2" id="listSearchInput" placeholder="Search provider or client..." oninput="handleListSearchInput()" style="font-size: 0.85rem; box-shadow: none;">
                        </div>
                    </div>

                    <!-- Entities Table -->
                    <div class="table-responsive rounded-3">
                        <table class="table table-hover align-middle mb-0" id="allEntitiesTable">
                            <thead>
                                <tr style="background-color: #f8fafc;">
                                    <th class="py-3 px-3 text-secondary text-uppercase fw-semibold" style="font-size: 0.75rem;">TYPE</th>
                                    <th class="py-3 px-3 text-secondary text-uppercase fw-semibold" style="font-size: 0.75rem;">NAME & ID</th>
                                    <th class="py-3 px-3 text-secondary text-uppercase fw-semibold" style="font-size: 0.75rem;">CONTACT</th>
                                    <th class="py-3 px-3 text-secondary text-uppercase fw-semibold text-end" style="font-size: 0.75rem;">SERVICE BAL</th>
                                    <th class="py-3 px-3 text-secondary text-uppercase fw-semibold text-end" style="font-size: 0.75rem;" id="listTableSpecialCol">EARNED / REFUND</th>
                                    <th class="py-3 px-3 text-secondary text-uppercase fw-semibold text-center" style="font-size: 0.75rem;">TXNS</th>
                                    <th class="py-3 px-3 text-secondary text-uppercase fw-semibold text-end" style="font-size: 0.75rem;">ACTION</th>
                                </tr>
                            </thead>
                            <tbody id="allEntitiesTbody">
                                <!-- Dynamic rows -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- TAB 3: SERVICE OVERVIEW VIEW -->
        <!-- ================================================================= -->
        <div id="serviceTabContent" class="main-tab-content" style="display: none;">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">Service-Level Reconciliation</h5>
                            <small class="text-muted">Showing <?= count($summary) ?> booking settlements</small>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <select class="form-select rounded-pill px-3 py-1" id="serviceStatusFilter" onchange="applyServiceTableFilter()" style="font-size: 0.85rem; min-width: 160px;">
                                <option value="">Status: All</option>
                                <option value="Balanced">Balanced</option>
                                <option value="Mismatch">Mismatch</option>
                                <option value="Pending">Pending</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive rounded-3">
                        <table class="table table-hover align-middle mb-0" id="serviceTable">
                            <thead>
                                <tr style="background: #f8fafc;">
                                    <th class="py-3 px-3 text-secondary text-uppercase fw-semibold" style="font-size: 0.75rem;">Service ID</th>
                                    <th class="py-3 px-3 text-secondary text-uppercase fw-semibold" style="font-size: 0.75rem;">Client</th>
                                    <th class="py-3 px-3 text-secondary text-uppercase fw-semibold" style="font-size: 0.75rem;">Provider</th>
                                    <th class="py-3 px-3 text-secondary text-uppercase fw-semibold" style="font-size: 0.75rem;">Booking Amt</th>
                                    <th class="py-3 px-3 text-secondary text-uppercase fw-semibold" style="font-size: 0.75rem;">Refunds</th>
                                    <th class="py-3 px-3 text-secondary text-uppercase fw-semibold" style="font-size: 0.75rem;">Provider Payout</th>
                                    <th class="py-3 px-3 text-secondary text-uppercase fw-semibold" style="font-size: 0.75rem;">Platform Share</th>
                                    <th class="py-3 px-3 text-secondary text-uppercase fw-semibold text-center" style="font-size: 0.75rem;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($summary as $row): ?>
                                    <tr data-status="<?= esc($row['status']) ?>">
                                        <td class="p-3 fw-bold text-dark"><?= esc($row['booking_id']) ?></td>
                                        <td class="p-3"><?= esc($row['client_name']) ?></td>
                                        <td class="p-3"><?= esc($row['walker_name']) ?></td>
                                        <td class="p-3 fw-semibold">₹<?= number_format((float)$row['booking_amt'], 2) ?></td>
                                        <td class="p-3 text-warning fw-semibold"><?= ((float)$row['refunds'] > 0) ? '₹' . number_format((float)$row['refunds'], 2) : '—' ?></td>
                                        <td class="p-3 text-success fw-semibold">₹<?= number_format((float)$row['walker_payouts'], 2) ?></td>
                                        <td class="p-3 text-primary fw-semibold">₹<?= number_format((float)$row['pf_earnings'], 2) ?></td>
                                        <td class="p-3 text-center">
                                            <?php if ($row['status'] == 'Balanced'): ?>
                                                <span class="badge rounded-pill" style="background:#dcfce7; color:#16a34a; font-weight:600; padding:0.35rem 0.85rem;">Balanced</span>
                                            <?php elseif ($row['status'] == 'Mismatch'): ?>
                                                <span class="badge rounded-pill" style="background:#fee2e2; color:#ef4444; font-weight:600; padding:0.35rem 0.85rem;">Mismatch</span>
                                            <?php else: ?>
                                                <span class="badge rounded-pill" style="background:#fef3c7; color:#d97706; font-weight:600; padding:0.35rem 0.85rem;">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($summary)): ?>
                                    <tr>
                                        <td colspan="8" class="p-4 text-center text-muted">No service records found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- TAB 4: DISCREPANCIES VIEW -->
        <!-- ================================================================= -->
        <div id="mismatchTabContent" class="main-tab-content" style="display: none;">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">Financial Discrepancies</h5>
                            <small class="text-danger"><i class="bi bi-exclamation-triangle me-1"></i><?= count($mismatch_alerts) ?> discrepancies flagged</small>
                        </div>
                    </div>

                    <div class="table-responsive rounded-3">
                        <table class="table table-hover align-middle mb-0" id="mismatchTable">
                            <thead>
                                <tr style="background: #f8fafc;">
                                    <th class="py-3 px-3 text-secondary text-uppercase fw-semibold" style="font-size: 0.75rem;">Service ID</th>
                                    <th class="py-3 px-3 text-secondary text-uppercase fw-semibold" style="font-size: 0.75rem;">User Type</th>
                                    <th class="py-3 px-3 text-secondary text-uppercase fw-semibold" style="font-size: 0.75rem;">User Name</th>
                                    <th class="py-3 px-3 text-secondary text-uppercase fw-semibold text-end" style="font-size: 0.75rem;">Expected Amount</th>
                                    <th class="py-3 px-3 text-secondary text-uppercase fw-semibold text-end" style="font-size: 0.75rem;">Actual Amount</th>
                                    <th class="py-3 px-3 text-secondary text-uppercase fw-semibold text-end" style="font-size: 0.75rem;">Difference</th>
                                    <th class="py-3 px-3 text-secondary text-uppercase fw-semibold" style="font-size: 0.75rem;">Date</th>
                                    <th class="py-3 px-3 text-secondary text-uppercase fw-semibold text-center" style="font-size: 0.75rem;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mismatch_alerts as $row): ?>
                                    <tr>
                                        <td class="p-3 fw-bold"><?= esc($row['service_id'] ?? '—') ?></td>
                                        <td class="p-3"><?= esc($row['user_type']) ?></td>
                                        <td class="p-3"><?= esc($row['user_name']) ?></td>
                                        <td class="p-3 text-end">₹<?= number_format((float)$row['expected_amount'], 2) ?></td>
                                        <td class="p-3 text-end">₹<?= number_format((float)$row['actual_amount'], 2) ?></td>
                                        <td class="p-3 text-end font-weight-bold <?= $row['difference'] > 0 ? 'text-danger' : 'text-success' ?>">
                                            ₹<?= number_format(abs((float)$row['difference']), 2) ?>
                                        </td>
                                        <td class="p-3"><?= esc($row['date']) ?></td>
                                        <td class="p-3 text-center">
                                            <span class="badge rounded-pill" style="background:#fee2e2; color:#ef4444; font-weight:600; padding:0.35rem 0.85rem;">
                                                <?= esc($row['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($mismatch_alerts)): ?>
                                    <tr>
                                        <td colspan="8" class="p-4 text-center text-muted">No mismatch alerts found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<style>
    /* Gradient styling */
    .recon-gradient-primary {
        background: linear-gradient(135deg, #3f30e3 0%, #5b47fb 100%) !important;
    }

    /* Tab buttons */
    .recon-tab {
        border: none;
        border-radius: 50px;
        padding: 0.6rem 1.4rem;
        color: #4b5563;
        background: transparent;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.25s ease;
    }

    .recon-tab:hover {
        color: #3f30e3;
        background-color: rgba(63, 48, 227, 0.06);
    }

    .recon-tab.active {
        background: #3f30e3 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(63, 48, 227, 0.25);
    }

    .recon-tab.active .badge {
        background-color: rgba(255, 255, 255, 0.25) !important;
        color: #ffffff !important;
    }

    /* Profile Avatar Initial */
    .recon-avatar-circle {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: #e0e7ff;
        color: #3f30e3;
        font-weight: 700;
        font-size: 1.4rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(63, 48, 227, 0.15);
    }

    /* Dark Purple Summary Banner */
    .recon-purple-banner {
        background: #2b1d6e !important;
        border-radius: 16px;
        padding: 1.5rem 2rem;
        color: #ffffff;
        box-shadow: 0 10px 25px -5px rgba(43, 29, 110, 0.3);
    }

    /* 5 Service Cards */
    .recon-service-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.25rem;
        cursor: pointer;
        transition: all 0.25s ease;
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .recon-service-card:hover {
        border-color: #6366f1;
        transform: translateY(-3px);
        box-shadow: 0 12px 24px -8px rgba(99, 102, 241, 0.2);
    }

    .recon-service-card.active {
        border: 2px solid #6366f1 !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
    }

    /* Filter Pills */
    .recon-filter-pill {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #475569;
        border-radius: 50px;
        padding: 0.4rem 1.1rem;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .recon-filter-pill:hover {
        border-color: #6366f1;
        color: #6366f1;
    }

    .recon-filter-pill.active {
        background: #3f30e3 !important;
        border-color: #3f30e3 !important;
        color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(63, 48, 227, 0.2);
    }

    /* Job Card Styling for Screen 2 */
    .recon-job-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        transition: all 0.2s ease;
    }

    .recon-job-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
    }

    .recon-job-num-badge {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #e0f2fe;
        color: #0284c7;
        font-weight: 700;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Table cell formatting & Wrapping */
    #combinedTransactionsTable th,
    #combinedTransactionsTable td,
    .recon-job-card table th,
    .recon-job-card table td {
        vertical-align: middle;
    }

    .recon-desc-cell {
        min-width: 260px !important;
        max-width: 420px !important;
        white-space: normal !important;
        word-break: break-word !important;
        overflow-wrap: anywhere !important;
        line-height: 1.45 !important;
    }

    /* Pagination button styling */
    .recon-page-btn {
        min-width: 36px;
        height: 36px;
        padding: 0 0.6rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .recon-page-btn:hover:not(:disabled) {
        border-color: #3f30e3;
        color: #3f30e3;
        background: #f8fafc;
    }

    .recon-page-btn.active {
        background: #3f30e3 !important;
        border-color: #3f30e3 !important;
        color: #ffffff !important;
    }

    .recon-page-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }
</style>

<?php echo $this->include('admin/footer') ?>

<script>
    // State datasets from Backend
    const walkersData = <?= json_encode($walkers, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?> || {};
    const clientsData = <?= json_encode($clients, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?> || {};
    const platformData = <?= json_encode($platform, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?> || {};

    // Current State
    let currentUserType = 'service_provider'; // 'service_provider' | 'client' | 'platform'
    let currentEntityId = null;
    let currentEntityData = null;
    let activeServicePill = 'all';
    let currentPeriodFilter = 'month';
    let currentTxnSearchTerm = '';
    let currentPaginationPage = 1;
    const TXN_ROWS_PER_PAGE = 8;
    let activeServiceScreenName = null;

    // Service colors mapping
    const SERVICE_THEMES = {
        'Walking': { bg: 'rgba(16, 185, 129, 0.1)', color: '#059669', border: 'rgba(16, 185, 129, 0.25)' },
        'Grooming': { bg: 'rgba(139, 92, 246, 0.1)', color: '#7c3aed', border: 'rgba(139, 92, 246, 0.25)' },
        'Training': { bg: 'rgba(245, 158, 11, 0.1)', color: '#d97706', border: 'rgba(245, 158, 11, 0.25)' },
        'Boarding': { bg: 'rgba(59, 130, 246, 0.1)', color: '#2563eb', border: 'rgba(59, 130, 246, 0.25)' },
        'Veterinary': { bg: 'rgba(236, 72, 153, 0.1)', color: '#db2777', border: 'rgba(236, 72, 153, 0.25)' }
    };

    document.addEventListener('DOMContentLoaded', function () {
        // Initialize with default Service Provider
        handleUserTypeChange();
        renderAllEntitiesTable();
    });

    // Handle User Type Switch (Provider, Client, Platform)
    function handleUserTypeChange() {
        currentUserType = document.getElementById('userTypeFilter').value;
        const pickerWrapper = document.getElementById('entityPickerWrapper');
        const entitySelect = document.getElementById('entitySelect');
        entitySelect.innerHTML = '';

        if (currentUserType === 'service_provider') {
            pickerWrapper.style.display = 'block';
            const walkerIds = Object.keys(walkersData);
            walkerIds.forEach(id => {
                const w = walkersData[id];
                const opt = document.createElement('option');
                opt.value = id;
                opt.textContent = `${w.walker_name} (#${id})`;
                entitySelect.appendChild(opt);
            });
            currentEntityId = walkerIds.length > 0 ? walkerIds[0] : null;
        } else if (currentUserType === 'client') {
            pickerWrapper.style.display = 'block';
            const clientIds = Object.keys(clientsData);
            clientIds.forEach(id => {
                const c = clientsData[id];
                const opt = document.createElement('option');
                opt.value = id;
                opt.textContent = `${c.client_name} (#${id})`;
                entitySelect.appendChild(opt);
            });
            currentEntityId = clientIds.length > 0 ? clientIds[0] : null;
        } else if (currentUserType === 'platform') {
            pickerWrapper.style.display = 'none';
            currentEntityId = 'platform';
        }

        loadActiveEntityData();
    }

    function handleEntitySelectChange() {
        currentEntityId = document.getElementById('entitySelect').value;
        loadActiveEntityData();
    }

    // Load active entity data and render Screen 1
    function loadActiveEntityData() {
        if (currentUserType === 'service_provider') {
            currentEntityData = walkersData[currentEntityId] || createEmptyEntity('provider', currentEntityId);
        } else if (currentUserType === 'client') {
            currentEntityData = clientsData[currentEntityId] || createEmptyEntity('client', currentEntityId);
        } else {
            currentEntityData = platformData || createEmptyEntity('platform', 'platform');
        }

        activeServicePill = 'all';
        currentPaginationPage = 1;
        currentTxnSearchTerm = '';
        const searchInput = document.getElementById('combinedTxnSearch');
        if (searchInput) searchInput.value = '';

        // Reset to Overview Screen 1
        document.getElementById('reconScreenOverview').style.display = 'block';
        document.getElementById('reconScreenServiceJobs').style.display = 'none';

        renderProfileCard();
        renderServiceCards();
        renderPurpleBanner();
        renderCombinedTransactionsTable();
        renderAllEntitiesTable();
    }

    function createEmptyEntity(type, id) {
        return {
            id: id,
            walker_name: type === 'provider' ? 'Provider #' + id : '',
            client_name: type === 'client' ? 'Client #' + id : '',
            name: type === 'platform' ? 'Petsfolio Platform' : '',
            phone: '—',
            area: 'Hyderabad',
            joined_date: 'Jun 2026',
            total_credited: 0,
            total_debited: 0,
            net_balance: 0,
            earned_amount: 0,
            refund_amount: 0,
            transactions_count: 0,
            services: {
                'Walking': { name: 'Walking', credited: 0, debited: 0, net_balance: 0, earned_amount: 0, refund_amount: 0, txns_count: 0, jobs: {} },
                'Grooming': { name: 'Grooming', credited: 0, debited: 0, net_balance: 0, earned_amount: 0, refund_amount: 0, txns_count: 0, jobs: {} },
                'Training': { name: 'Training', credited: 0, debited: 0, net_balance: 0, earned_amount: 0, refund_amount: 0, txns_count: 0, jobs: {} },
                'Boarding': { name: 'Boarding', credited: 0, debited: 0, net_balance: 0, earned_amount: 0, refund_amount: 0, txns_count: 0, jobs: {} },
                'Veterinary': { name: 'Veterinary', credited: 0, debited: 0, net_balance: 0, earned_amount: 0, refund_amount: 0, txns_count: 0, jobs: {} }
            },
            transactions: []
        };
    }

    // Render Profile Header Card in Screen 1
    function renderProfileCard() {
        const d = currentEntityData;
        let displayName = 'Entity';
        let role = 'Provider';
        let initial = 'P';
        let area = d.area || 'Hyderabad';
        let idStr = `ID: ${d.id || ''}`;
        let joined = `Joined ${d.joined_date || '2026'}`;
        let phone = d.walker_phone || d.client_phone || d.phone || '—';

        if (currentUserType === 'service_provider') {
            displayName = d.walker_name || `Provider #${d.id}`;
            role = 'Service Provider';
            initial = displayName.charAt(0).toUpperCase() || 'P';
            document.getElementById('profileBalanceLabel').textContent = 'Service Balance';
        } else if (currentUserType === 'client') {
            displayName = d.client_name || `Client #${d.id}`;
            role = 'Client';
            initial = displayName.charAt(0).toUpperCase() || 'C';
            document.getElementById('profileBalanceLabel').textContent = 'Service Balance';
        } else {
            displayName = 'Petsfolio Platform';
            role = 'Platform';
            initial = 'PF';
            idStr = 'Platform Level';
            phone = 'service@petsfolio.com';
            document.getElementById('profileBalanceLabel').textContent = 'Platform Earnings';
        }

        document.getElementById('profileAvatarInitial').textContent = initial;
        document.getElementById('profileNameDisplay').textContent = displayName;
        document.getElementById('profileRoleBadge').textContent = role;
        document.getElementById('profileAreaText').innerHTML = `<i class="bi bi-geo-alt me-1"></i>${area}`;
        document.getElementById('profileIdText').textContent = idStr;
        document.getElementById('profileJoinedText').textContent = joined;
        document.getElementById('profilePhoneText').textContent = phone;
        document.getElementById('profileTopBalance').textContent = `₹${formatMoney(d.net_balance)}`;
    }

    // Render 5 Service Cards Grid in Screen 1
    function renderServiceCards() {
        const container = document.getElementById('serviceCardsGrid');
        container.innerHTML = '';

        const services = ['Walking', 'Grooming', 'Training', 'Boarding', 'Veterinary'];

        services.forEach(svcName => {
            const svc = (currentEntityData.services && currentEntityData.services[svcName]) ? currentEntityData.services[svcName] : {
                name: svcName,
                credited: 0,
                debited: 0,
                net_balance: 0,
                earned_amount: 0,
                refund_amount: 0,
                txns_count: 0
            };

            const col = document.createElement('div');
            col.className = 'col-12 col-sm-6 col-lg';

            // Extra line for Earned Amount (SP) or Refund Amount (Client)
            let specialRow = '';
            if (currentUserType === 'service_provider') {
                specialRow = `
                    <div class="d-flex justify-content-between align-items-center text-muted small my-1">
                        <span>Earned</span>
                        <span class="text-purple fw-semibold" style="color: #8b5cf6;">₹${formatMoney(svc.earned_amount || 0)}</span>
                    </div>
                `;
            } else if (currentUserType === 'client') {
                specialRow = `
                    <div class="d-flex justify-content-between align-items-center text-muted small my-1">
                        <span>Refund Bal</span>
                        <span class="text-info fw-semibold" style="color: #0284c7;">₹${formatMoney(svc.refund_amount || 0)}</span>
                    </div>
                `;
            }

            col.innerHTML = `
                <div class="recon-service-card shadow-sm" onclick="openServiceScreen('${svcName}')">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0">${svcName}</h6>
                            <span class="badge rounded-pill bg-light text-muted border px-2 py-1" style="font-size: 0.72rem; font-weight: 500;">
                                ${svc.txns_count || 0} txns
                            </span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center text-muted small my-1">
                            <span>Credited</span>
                            <span class="text-success fw-semibold">₹${formatMoney(svc.credited)}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center text-muted small my-1">
                            <span>Debited</span>
                            <span class="text-danger fw-semibold">₹${formatMoney(svc.debited)}</span>
                        </div>
                        
                        ${specialRow}
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-2 mt-3 border-top">
                        <span class="text-muted small">Net balance</span>
                        <span class="h6 fw-bold text-dark mb-0">₹${formatMoney(svc.net_balance)}</span>
                    </div>
                </div>
            `;
            container.appendChild(col);
        });
    }

    // Render Dark Purple Summary Banner in Screen 1
    function renderPurpleBanner() {
        const d = currentEntityData;
        document.getElementById('bannerValTotalCredited').textContent = `₹${formatMoney(d.total_credited)}`;
        document.getElementById('bannerValTotalDebited').textContent = `₹${formatMoney(d.total_debited)}`;
        document.getElementById('bannerValNetBalance').textContent = `₹${formatMoney(d.net_balance)}`;

        if (currentUserType === 'service_provider') {
            document.getElementById('bannerLabel3').textContent = 'Earned amount';
            document.getElementById('bannerValEarnedRefund').textContent = `₹${formatMoney(d.earned_amount || 0)}`;
        } else if (currentUserType === 'client') {
            document.getElementById('bannerLabel3').textContent = 'Refund balance';
            document.getElementById('bannerValEarnedRefund').textContent = `₹${formatMoney(d.refund_amount || 0)}`;
        } else {
            document.getElementById('bannerLabel3').textContent = 'Total transactions';
            document.getElementById('bannerValEarnedRefund').textContent = d.transactions_count || 0;
        }
    }

    // Render Combined Transactions Table in Screen 1
    function renderCombinedTransactionsTable() {
        const tbody = document.getElementById('combinedTransactionsTbody');
        tbody.innerHTML = '';

        let allTxns = (currentEntityData.transactions || []);

        // Filter by Service Pill
        if (activeServicePill !== 'all') {
            allTxns = allTxns.filter(t => t.service.toLowerCase() === activeServicePill.toLowerCase());
        }

        // Filter by Search Query
        if (currentTxnSearchTerm) {
            const q = currentTxnSearchTerm.toLowerCase();
            allTxns = allTxns.filter(t => 
                (t.txn_id && t.txn_id.toLowerCase().includes(q)) ||
                (t.description && t.description.toLowerCase().includes(q)) ||
                (t.booking_id && t.booking_id.toLowerCase().includes(q)) ||
                (t.service && t.service.toLowerCase().includes(q))
            );
        }

        const totalFiltered = allTxns.length;
        const totalPages = Math.ceil(totalFiltered / TXN_ROWS_PER_PAGE) || 1;
        if (currentPaginationPage > totalPages) currentPaginationPage = totalPages;
        if (currentPaginationPage < 1) currentPaginationPage = 1;

        const start = (currentPaginationPage - 1) * TXN_ROWS_PER_PAGE;
        const end = Math.min(start + TXN_ROWS_PER_PAGE, totalFiltered);
        const pagedTxns = allTxns.slice(start, end);

        if (pagedTxns.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-3 d-block mb-2 text-secondary"></i>
                        No transactions found matching the selected filters.
                    </td>
                </tr>
            `;
        } else {
            pagedTxns.forEach((txn, index) => {
                const rowIndex = start + index + 1;
                const theme = SERVICE_THEMES[txn.service] || { bg: '#f1f5f9', color: '#475569', border: '#e2e8f0' };

                const isCredit = (txn.type === 'Credit');
                const typeBadge = isCredit 
                    ? `<span class="badge rounded-pill" style="background:#dcfce7; color:#16a34a; font-weight:600; padding:0.35rem 0.85rem;">Credit</span>`
                    : `<span class="badge rounded-pill" style="background:#fee2e2; color:#ef4444; font-weight:600; padding:0.35rem 0.85rem;">Debit</span>`;

                const debitText = txn.debit > 0 ? `<span class="text-danger fw-semibold">₹${formatMoney(txn.debit)}</span>` : '<span class="text-muted">—</span>';
                const creditText = txn.credit > 0 ? `<span class="text-success fw-semibold">₹${formatMoney(txn.credit)}</span>` : '<span class="text-muted">—</span>';

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="py-3 px-3 text-muted small text-nowrap">${rowIndex}</td>
                    <td class="py-3 px-3 text-nowrap">
                        <span class="font-monospace text-primary fw-semibold small" style="letter-spacing: 0.3px;">
                            ${txn.txn_id || ('TXN_' + rowIndex)}
                        </span>
                    </td>
                    <td class="py-3 px-3 text-nowrap">
                        <span class="badge rounded-pill" style="background:${theme.bg}; color:${theme.color}; border:1px solid ${theme.border}; font-weight:600; font-size:0.78rem; padding:0.35rem 0.85rem;">
                            ${txn.service}
                        </span>
                    </td>
                    <td class="py-3 px-3 text-secondary small text-nowrap">${txn.date_formatted || txn.date}</td>
                    <td class="py-3 px-3 text-nowrap">${typeBadge}</td>
                    <td class="py-3 px-3 text-dark small recon-desc-cell">${txn.description || 'Service transaction'}</td>
                    <td class="py-3 px-3 text-end text-nowrap">${debitText}</td>
                    <td class="py-3 px-3 text-end text-nowrap">${creditText}</td>
                    <td class="py-3 px-3 text-end fw-bold text-dark text-nowrap">₹${formatMoney(txn.service_balance)}</td>
                `;
                tbody.appendChild(tr);
            });
        }

        // Pagination Info & Buttons
        document.getElementById('combinedPaginationInfo').textContent = totalFiltered > 0 
            ? `Showing ${start + 1} to ${end} of ${totalFiltered} entries`
            : `Showing 0 to 0 of 0 entries`;

        renderPaginationButtons(totalPages);
    }

    function renderPaginationButtons(totalPages) {
        const container = document.getElementById('combinedPaginationButtons');
        container.innerHTML = '';

        if (totalPages <= 1) return;

        // Previous Button
        const prevBtn = document.createElement('button');
        prevBtn.className = 'recon-page-btn';
        prevBtn.innerHTML = '<i class="bi bi-chevron-left"></i>';
        prevBtn.disabled = (currentPaginationPage === 1);
        prevBtn.onclick = () => { currentPaginationPage--; renderCombinedTransactionsTable(); };
        container.appendChild(prevBtn);

        // Numeric Buttons
        for (let p = 1; p <= totalPages; p++) {
            if (p === 1 || p === totalPages || (p >= currentPaginationPage - 2 && p <= currentPaginationPage + 2)) {
                const btn = document.createElement('button');
                btn.className = `recon-page-btn ${p === currentPaginationPage ? 'active' : ''}`;
                btn.textContent = p;
                btn.onclick = () => { currentPaginationPage = p; renderCombinedTransactionsTable(); };
                container.appendChild(btn);
            } else if (p === currentPaginationPage - 3 || p === currentPaginationPage + 3) {
                const dots = document.createElement('span');
                dots.className = 'px-1 text-muted';
                dots.textContent = '...';
                container.appendChild(dots);
            }
        }

        // Next Button
        const nextBtn = document.createElement('button');
        nextBtn.className = 'recon-page-btn';
        nextBtn.innerHTML = '<i class="bi bi-chevron-right"></i>';
        nextBtn.disabled = (currentPaginationPage === totalPages);
        nextBtn.onclick = () => { currentPaginationPage++; renderCombinedTransactionsTable(); };
        container.appendChild(nextBtn);
    }

    function applyServicePillFilter(serviceName) {
        activeServicePill = serviceName;
        currentPaginationPage = 1;

        // Update active class on pill buttons
        const pills = document.querySelectorAll('#serviceFilterPills .recon-filter-pill');
        pills.forEach(btn => {
            if (btn.textContent.trim().toLowerCase() === serviceName.toLowerCase() || 
                (serviceName === 'all' && btn.textContent.includes('All')) ||
                (serviceName === 'Veterinary' && btn.textContent.includes('Vet'))) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });

        renderCombinedTransactionsTable();
    }

    function handleCombinedSearchInput() {
        currentTxnSearchTerm = document.getElementById('combinedTxnSearch').value;
        currentPaginationPage = 1;
        renderCombinedTransactionsTable();
    }

    function handlePeriodFilterChange() {
        currentPeriodFilter = document.getElementById('reconPeriodFilter').value;
        // In a live system this can filter by date period
        renderCombinedTransactionsTable();
    }

    // =========================================================================
    // SCREEN 2: SERVICE RECONCILIATION & JOBS BREAKDOWN
    // =========================================================================
    function openServiceScreen(serviceName) {
        activeServiceScreenName = serviceName;
        document.getElementById('reconScreenOverview').style.display = 'none';
        document.getElementById('reconScreenServiceJobs').style.display = 'block';

        const svc = (currentEntityData.services && currentEntityData.services[serviceName]) ? currentEntityData.services[serviceName] : {
            name: serviceName,
            credited: 0,
            debited: 0,
            net_balance: 0,
            earned_amount: 0,
            refund_amount: 0,
            txns_count: 0,
            jobs: {}
        };

        const entityDisplayName = currentEntityData.walker_name || currentEntityData.client_name || currentEntityData.name || 'Entity';
        const jobsObj = svc.jobs || {};
        const jobKeys = Object.keys(jobsObj);
        const jobsCount = jobKeys.length;

        // Set Breadcrumb & Header Title
        document.getElementById('s2ServiceBreadcrumb').textContent = serviceName;
        document.getElementById('s2ServiceHeaderTitle').textContent = `${serviceName} reconciliation`;
        document.getElementById('s2ServiceHeaderSubtitle').textContent = 
            `Provider #${currentEntityData.id || ''} - ${entityDisplayName} · ${jobsCount} ${serviceName.toLowerCase()} jobs, shown individually with their own transactions`;

        // Set Summary Stats
        document.getElementById('s2StatJobsCount').textContent = jobsCount;
        document.getElementById('s2StatTotalCredited').textContent = `₹${formatMoney(svc.credited)}`;
        document.getElementById('s2StatTotalDebited').textContent = `₹${formatMoney(svc.debited)}`;
        document.getElementById('s2StatNetBalanceLabel').textContent = `Net ${serviceName.toLowerCase()} balance`;
        document.getElementById('s2StatNetBalance').textContent = `₹${formatMoney(svc.net_balance)}`;
        document.getElementById('s2JobsBadgeCount').textContent = `${jobsCount} jobs`;

        // Render Jobs Cards
        renderJobsList(jobsObj, serviceName);

        // Scroll to top of content
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function backToOverviewScreen() {
        document.getElementById('reconScreenServiceJobs').style.display = 'none';
        document.getElementById('reconScreenOverview').style.display = 'block';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function renderJobsList(jobsObj, serviceName) {
        const container = document.getElementById('s2JobsListContainer');
        container.innerHTML = '';

        const jobKeys = Object.keys(jobsObj);

        if (jobKeys.length === 0) {
            container.innerHTML = `
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center text-muted">
                    <i class="bi bi-calendar-x fs-1 text-secondary mb-2"></i>
                    <h6 class="fw-bold text-dark">No individual jobs found for ${serviceName}</h6>
                    <p class="small text-muted mb-0">No job transactions are logged under this service for the current entity.</p>
                </div>
            `;
            return;
        }

        jobKeys.forEach((jobId, idx) => {
            const job = jobsObj[jobId];
            const jobIndex = idx + 1;
            const txns = job.transactions || [];

            // Status Badge
            const status = job.status || 'Completed';
            let statusBadge = `<span class="badge rounded-pill" style="background:#dcfce7; color:#16a34a; font-weight:600; padding:0.4rem 1rem;">Completed</span>`;
            if (status.toLowerCase().includes('cancel') || status.toLowerCase().includes('reject')) {
                statusBadge = `<span class="badge rounded-pill" style="background:#fee2e2; color:#ef4444; font-weight:600; padding:0.4rem 1rem;">Cancelled</span>`;
            } else if (status.toLowerCase().includes('progress') || status.toLowerCase().includes('confirm')) {
                statusBadge = `<span class="badge rounded-pill" style="background:#e0f2fe; color:#0284c7; font-weight:600; padding:0.4rem 1rem;">In Progress</span>`;
            }

            // Extra stat box (Earned for SP, Refund for Client)
            let extraCol = '';
            if (currentUserType === 'service_provider') {
                extraCol = `
                    <div class="col-6 col-md-2">
                        <small class="text-muted d-block">Earned</small>
                        <span class="fw-bold text-purple" style="color:#8b5cf6;">₹${formatMoney(job.earned_amount || 0)}</span>
                    </div>
                `;
            } else if (currentUserType === 'client') {
                extraCol = `
                    <div class="col-6 col-md-2">
                        <small class="text-muted d-block">Refund Bal</small>
                        <span class="fw-bold text-info" style="color:#0284c7;">₹${formatMoney(job.refund_amount || 0)}</span>
                    </div>
                `;
            }

            // Generate transactions table rows for this job
            let txnsRowsHtml = '';
            txns.forEach((t, tIdx) => {
                const isCredit = (t.type === 'Credit');
                const tBadge = isCredit 
                    ? `<span class="badge rounded-pill" style="background:#dcfce7; color:#16a34a; font-weight:600; padding:0.3rem 0.75rem; font-size:0.75rem;">Credit</span>`
                    : `<span class="badge rounded-pill" style="background:#fee2e2; color:#ef4444; font-weight:600; padding:0.3rem 0.75rem; font-size:0.75rem;">Debit</span>`;

                const debitVal = t.debit > 0 ? `<span class="text-danger fw-semibold">₹${formatMoney(t.debit)}</span>` : '<span class="text-muted">—</span>';
                const creditVal = t.credit > 0 ? `<span class="text-success fw-semibold">₹${formatMoney(t.credit)}</span>` : '<span class="text-muted">—</span>';

                txnsRowsHtml += `
                    <tr>
                        <td class="py-2 px-3 text-muted small text-nowrap">${tIdx + 1}</td>
                        <td class="py-2 px-3 text-nowrap"><span class="font-monospace text-primary fw-semibold small">${t.txn_id || ('TXN_' + (tIdx + 1))}</span></td>
                        <td class="py-2 px-3 text-secondary small text-nowrap">${t.date_formatted || t.date}</td>
                        <td class="py-2 px-3 text-nowrap">${tBadge}</td>
                        <td class="py-2 px-3 text-dark small recon-desc-cell">${t.description || `${serviceName} service amount`}</td>
                        <td class="py-2 px-3 text-end text-nowrap">${debitVal}</td>
                        <td class="py-2 px-3 text-end text-nowrap">${creditVal}</td>
                        <td class="py-2 px-3 text-end fw-bold text-dark text-nowrap">₹${formatMoney(t.service_balance)}</td>
                    </tr>
                `;
            });

            const card = document.createElement('div');
            card.className = 'recon-job-card';
            card.innerHTML = `
                <!-- Job Header -->
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="recon-job-num-badge">
                            ${jobIndex}
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0">Job #${job.job_id}</h6>
                            <div class="text-muted small">
                                Booked ${job.booked_date} • ${job.pet_name} (${job.pet_breed})
                            </div>
                        </div>
                    </div>

                    <div>
                        ${statusBadge}
                    </div>
                </div>

                <!-- Job Stats Row -->
                <div class="row g-2 p-3 rounded-3 mb-3" style="background-color: #f8fafc; border: 1px solid #f1f5f9;">
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block">Credited</small>
                        <span class="fw-bold text-success">₹${formatMoney(job.credited)}</span>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block">Debited</small>
                        <span class="fw-bold text-danger">₹${formatMoney(job.debited)}</span>
                    </div>
                    ${extraCol}
                    <div class="col-6 col-md-2">
                        <small class="text-muted d-block">Net job balance</small>
                        <span class="fw-bold ${job.net_balance < 0 ? 'text-danger' : 'text-dark'}">₹${formatMoney(job.net_balance)}</span>
                    </div>
                    <div class="col-6 col-md-2 text-md-end">
                        <small class="text-muted d-block">Transactions</small>
                        <span class="fw-bold text-dark">${txns.length}</span>
                    </div>
                </div>

                <!-- Job Transactions Table -->
                <div class="table-responsive rounded-3 mb-2">
                    <table class="table table-sm table-hover align-middle mb-0" style="min-width: 900px;">
                        <thead>
                            <tr style="background-color: #ffffff; border-bottom: 1px solid #e2e8f0;">
                                <th class="py-2 px-3 text-secondary text-uppercase fw-semibold text-nowrap" style="font-size: 0.72rem; width: 40px;">#</th>
                                <th class="py-2 px-3 text-secondary text-uppercase fw-semibold text-nowrap" style="font-size: 0.72rem; min-width: 140px;">TRANSACTION ID</th>
                                <th class="py-2 px-3 text-secondary text-uppercase fw-semibold text-nowrap" style="font-size: 0.72rem; min-width: 130px;">DATE & TIME</th>
                                <th class="py-2 px-3 text-secondary text-uppercase fw-semibold text-nowrap" style="font-size: 0.72rem; min-width: 80px;">TYPE</th>
                                <th class="py-2 px-3 text-secondary text-uppercase fw-semibold" style="font-size: 0.72rem; min-width: 280px;">DESCRIPTION</th>
                                <th class="py-2 px-3 text-secondary text-uppercase fw-semibold text-end text-nowrap" style="font-size: 0.72rem; min-width: 90px;">DEBIT</th>
                                <th class="py-2 px-3 text-secondary text-uppercase fw-semibold text-end text-nowrap" style="font-size: 0.72rem; min-width: 90px;">CREDIT</th>
                                <th class="py-2 px-3 text-secondary text-uppercase fw-semibold text-end text-nowrap" style="font-size: 0.72rem; min-width: 110px;">SERVICE BAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${txnsRowsHtml}
                        </tbody>
                    </table>
                </div>

                <div class="text-muted small pt-2 border-top">
                    Showing ${txns.length} of ${txns.length} transaction${txns.length !== 1 ? 's' : ''} for this job
                </div>
            `;
            container.appendChild(card);
        });
    }

    // =========================================================================
    // TAB 2: ALL ENTITIES LIST TABLE
    // =========================================================================
    function renderAllEntitiesTable() {
        const tbody = document.getElementById('allEntitiesTbody');
        if (!tbody) return;
        tbody.innerHTML = '';

        const searchQuery = (document.getElementById('listSearchInput')?.value || '').toLowerCase();
        const specialColHeader = document.getElementById('listTableSpecialCol');
        if (specialColHeader) {
            specialColHeader.textContent = (currentUserType === 'service_provider') ? 'EARNED AMOUNT' : 'REFUND AMOUNT';
        }

        let dataset = (currentUserType === 'service_provider') ? walkersData : clientsData;
        if (currentUserType === 'platform') {
            dataset = { 'platform': platformData };
        }

        const ids = Object.keys(dataset);
        let matchingCount = 0;

        ids.forEach(id => {
            const item = dataset[id];
            const name = item.walker_name || item.client_name || item.name || `Entity #${id}`;
            const phone = item.walker_phone || item.client_phone || item.phone || '—';
            const area = item.area || 'Hyderabad';
            const txnsCount = item.transactions_count || (item.transactions ? item.transactions.length : 0);

            if (searchQuery && !name.toLowerCase().includes(searchQuery) && !phone.toLowerCase().includes(searchQuery) && !String(id).includes(searchQuery)) {
                return;
            }
            matchingCount++;

            let specialVal = '—';
            if (currentUserType === 'service_provider') {
                specialVal = `<span class="fw-bold" style="color:#8b5cf6;">₹${formatMoney(item.earned_amount || 0)}</span>`;
            } else if (currentUserType === 'client') {
                specialVal = `<span class="fw-bold text-info">₹${formatMoney(item.refund_amount || 0)}</span>`;
            }

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="p-3">
                    <span class="badge rounded-pill bg-light text-dark border px-3 py-1">
                        ${currentUserType === 'service_provider' ? 'Provider' : (currentUserType === 'client' ? 'Client' : 'Platform')}
                    </span>
                </td>
                <td class="p-3">
                    <div class="fw-bold text-dark">${name}</div>
                    <small class="text-muted">ID: ${id} • ${area}</small>
                </td>
                <td class="p-3 text-muted small">${phone}</td>
                <td class="p-3 text-end fw-bold text-primary">₹${formatMoney(item.net_balance)}</td>
                <td class="p-3 text-end">${specialVal}</td>
                <td class="p-3 text-center">
                    <span class="badge rounded-pill bg-light text-muted border px-2 py-1">${txnsCount}</span>
                </td>
                <td class="p-3 text-end">
                    <button class="btn btn-sm btn-primary rounded-pill px-3 py-1 text-white fw-semibold" onclick="drillDownToEntity('${id}')" style="font-size: 0.82rem;">
                        View Ledger <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        if (matchingCount === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">No entities found matching your search.</td>
                </tr>
            `;
        }
    }

    function drillDownToEntity(id) {
        currentEntityId = id;
        const select = document.getElementById('entitySelect');
        if (select) select.value = id;
        loadActiveEntityData();
        switchMainTab('detailed');
    }

    function handleListSearchInput() {
        renderAllEntitiesTable();
    }

    // =========================================================================
    // TAB NAVIGATION (Detailed, List, Service Overview, Discrepancies)
    // =========================================================================
    function switchMainTab(tabName) {
        document.querySelectorAll('.main-tab-content').forEach(el => el.style.display = 'none');
        document.querySelectorAll('#reconNavTabs .recon-tab').forEach(el => el.classList.remove('active'));

        if (tabName === 'detailed') {
            document.getElementById('detailedTabContent').style.display = 'block';
            document.getElementById('detailedTab').classList.add('active');
        } else if (tabName === 'list') {
            document.getElementById('listTabContent').style.display = 'block';
            document.getElementById('listTab').classList.add('active');
            renderAllEntitiesTable();
        } else if (tabName === 'service') {
            document.getElementById('serviceTabContent').style.display = 'block';
            document.getElementById('serviceTab').classList.add('active');
        } else if (tabName === 'mismatch') {
            document.getElementById('mismatchTabContent').style.display = 'block';
            document.getElementById('mismatchTab').classList.add('active');
        }
    }

    function applyServiceTableFilter() {
        const filterVal = document.getElementById('serviceStatusFilter').value.toLowerCase();
        const rows = document.querySelectorAll('#serviceTable tbody tr');
        rows.forEach(r => {
            const status = (r.getAttribute('data-status') || '').toLowerCase();
            if (!filterVal || status === filterVal) {
                r.style.display = '';
            } else {
                r.style.display = 'none';
            }
        });
    }

    function refreshReconData() {
        location.reload();
    }

    // Helper to format currency
    function formatMoney(amount) {
        const num = parseFloat(amount || 0);
        return num.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
</script>

<?php echo $this->endSection() ?>