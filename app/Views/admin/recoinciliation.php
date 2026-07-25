<?php echo $this->extend('admin/includes/header'); ?>

<?php echo $this->section('content'); ?>

<main class="main-content">
    <div class="position-relative iq-banner">
        <?php echo $this->include('admin/nav_bar') ?>
    </div>
    <div class="container-fluid px-4 content-inner pb-0" id="page_layout">
        <div class="row mb-4 recon-gradient-primary p-3 pt-5 rounded-3 position-relative mx-0">
            <div class="col-12">
                <div class="text-white position-relative overflow-hidden" style="border-radius: 16px !important;">

                    <!-- Top Row -->
                    <div class="position-relative z-1 d-flex justify-content-between align-items-start">

                        <!-- Left Content -->
                        <div>
                            <h1 class="h2 fw-bold mb-2 text-white">Financial Reconciliation</h1>
                            <p class="text-white-75 mb-0">
                                Track payments, settlements, and financial health across all transactions
                            </p>
                        </div>

                        <!-- ✅ Moved Select Here (Top Right) -->
                        <div id="userTypeFilterContainer">
                            <select class="form-select bg-white" id="userTypeFilter" onchange="filterByUserType()"
                                style="min-width: 220px; border-radius: 50px; padding: 0.6rem 1.5rem;">
                                <option value="service_provider" selected>Service Provider</option>
                                <option value="client">Client</option>
                                <option value="platform">Platform Level</option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Bottom Filter Bar (UNCHANGED except removed select) -->
            <div class="d-flex flex-wrap align-items-center gap-3 p-2 bg-white rounded-5 shadow-sm mt-4">
                <div class="d-flex gap-2">
                    <button class="recon-tab active" onclick="showTab('wallet')" id="walletTab">
                        <i class="bi bi-wallet2 me-2"></i>Wallet View
                        <span class="badge bg-success bg-opacity-10 text-white ms-2 py-2 rounded">
                            <?= count($clients) + count($walkers) ?>
                        </span>
                    </button>

                    <button class="recon-tab" onclick="showTab('service')" id="serviceTab">
                        <i class="bi bi-calendar-check me-2"></i>Service View
                        <span class="badge bg-primary bg-opacity-10 text-white ms-2 py-2 rounded">
                            <?= count($summary) ?>
                        </span>
                    </button>

                    <button class="recon-tab" onclick="showTab('mismatch')" id="mismatchTab">
                        <i class="bi bi-exclamation-triangle me-2"></i>Discrepancies
                        <span class="badge bg-danger bg-opacity-10 text-danger ms-2 py-2 rounded">
                            <?= count($mismatch_alerts) ?>
                        </span>
                    </button>
                </div>

                <div class="flex-grow-1 mx-3">
                    <div class="input-group"
                        style="border: 1px solid #8292a2; border-radius: 50px; overflow: hidden; padding-left: 0.75rem !important;">
                        <span class="input-group-text bg-transparent border-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input style="padding: 0.75rem 1.5rem 0.75rem 0rem !important;" type="text"
                            placeholder="Search by Client/Service Provider/Transaction ID" class="form-control border-0"
                            id="globalSearchInput" oninput="applyGlobalSearch()" />
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">Financial Overview</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-12 col-md-6 col-lg-3 mt-0">
                        <div
                            class="summary-card d-flex justify-content-between align-items-center p-3 rounded-3 shadow-sm border">
                            <div>
                                <p class="text-secondary mb-1">Total Bookings</p>
                                <p class="h4 fw-bold mb-0 text-primary">
                                    ₹ <?= number_format($total_bookings) ?>
                                </p>
                            </div>
                            <div class="bg-primary p-3 rounded-circle d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-rupee-sign fs-4 text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 mt-0">
                        <div
                            class="summary-card d-flex justify-content-between align-items-center p-3 rounded-3 shadow-sm border">
                            <div>
                                <p class="text-secondary mb-1">Provider Payouts</p>
                                <p class="h4 fw-bold mb-0 text-success">
                                    ₹ <?= number_format($walker_payouts) ?>
                                </p>
                            </div>
                            <div class="bg-success p-3 rounded-circle d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-user-check fs-4 text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 mt-0">
                        <div
                            class="summary-card d-flex justify-content-between align-items-center p-3 rounded-3 shadow-sm border">
                            <div>
                                <p class="text-secondary mb-1">Client Refunds</p>
                                <p class="h4 fw-bold mb-0 text-warning">
                                    ₹ <?= number_format($client_refunds ?? 0) ?>
                                </p>
                            </div>
                            <div class="bg-warning p-3 rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-arrow-left-right fs-5 text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 mt-0">
                        <div
                            class="summary-card d-flex justify-content-between align-items-center p-3 rounded-3 shadow-sm border">
                            <div>
                                <p class="text-secondary mb-1">Platform Commission</p>
                                <p class="h4 fw-bold mb-0 text-purple" style="color:#8b5cf6;">
                                    ₹ <?= number_format($platform_earnings) ?>
                                </p>
                            </div>
                            <div class="p-3 rounded-circle d-flex align-items-center justify-content-center"
                                style="background-color:#8b5cf6;">
                                <i class="bi bi-graph-up fs-5 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4 mx-0">
            <div class="col-12 p-0">
                <div class="glass-card-recon p-4">
                    <div id="walletContent" class="tab-content">
                        <div id="clientWalletSection" class="wallet-section">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="fw-bold mb-0">Client Wallets</h5>
                                <div class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <small>Showing <?= count($clients) ?> clients</small>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle border-0 pagination-table" id="clientSummaryTable" data-table-name="clientSummaryTable">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4 py-2 text-uppercase small fw-bold text-dark" style="width: 60px; background: #c6d2ff !important;">S.No</th>
                                            <th class="py-2 text-uppercase small fw-bold text-dark" style="background: #c6d2ff !important;">Client Name</th>
                                            <th class="py-2 text-uppercase small fw-bold text-dark text-end" style="background: #c6d2ff !important;">Service Balance</th>
                                            <th class="py-2 text-uppercase small fw-bold text-dark text-end" style="background: #c6d2ff !important;">Refund Balance</th>
                                            <th class="py-2 text-uppercase small fw-bold text-dark text-center" style="background: #c6d2ff !important;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1;
                                        foreach ($clients as $clientId => $client):
                                            $totalServiceBalance = end($client['transactions'])['balance'] ?? 0;
                                            $totalRefundBalance = end($client['transactions'])['refundBalance'] ?? 0;
                                        ?>
                                            <tr class="client-accordion" data-client-name="<?= strtolower(esc($client['client_name'])) ?>" data-client-id="<?= esc($clientId) ?>">
                                                <td class="ps-4 py-2"><?= $i++ ?></td>
                                                <td class="py-2">
                                                    <div class="fw-bold text-dark"><?= esc($client['client_name']) ?></div>
                                                    <small class="text-secondary">ID: <?= esc($clientId) ?></small>
                                                </td>
                                                <td class="text-end py-2">
                                                    <span class="badge rounded-pill px-3 py-1" style="background: rgba(37, 99, 235, 0.08); color: #2563eb; font-weight: 600;">
                                                        ₹<?= number_format($totalServiceBalance) ?>
                                                    </span>
                                                </td>
                                                <td class="text-end py-2">
                                                    <span class="badge rounded-pill px-3 py-1" style="background: rgba(16, 185, 129, 0.08); color: #059669; font-weight: 600;">
                                                        ₹<?= number_format($totalRefundBalance) ?>
                                                    </span>
                                                </td>
                                                <td class="text-center py-2">
                                                    <a href="<?= base_url('userDetails/user/' . $clientId) ?>" target="_blank" class="btn btn-sm btn-primary rounded-pill px-3 py-1">
                                                        <i class="bi bi-eye me-1"></i> View Details
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination Footer for Client Summary -->
                            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                                <div>
                                    <label class="me-2">Show 
                                        <select class="form-select d-inline-block" style="width: auto;" onchange="changeTableEntries('clientSummaryTable', this.value)">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> entries
                                    </label>
                                </div>
                                <div class="table-pagination" id="clientSummaryTable-pagination"></div>
                            </div>
                        </div>

                        <div id="serviceProviderWalletSection" class="wallet-section" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="fw-bold mb-0">Service Provider Wallets</h5>
                                <div class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <small>Showing <?= count($walkers) ?> providers</small>
                                </div>
                            </div>
                            <div class="accordion accordion-recon" id="walkerAccordion">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle border-0 pagination-table" id="walkerSummaryTable" data-table-name="walkerSummaryTable">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-4 py-2 text-uppercase small fw-bold text-dark" style="width: 60px; background: #c6d2ff !important;">S.No</th>
                                                <th class="py-2 text-uppercase small fw-bold text-dark" style="background: #c6d2ff !important;">Provider Name</th>
                                                <th class="py-2 text-uppercase small fw-bold text-dark text-end" style="background: #c6d2ff !important;">Service Balance</th>
                                                <th class="py-2 text-uppercase small fw-bold text-dark text-end" style="background: #c6d2ff !important;">Earned Balance</th>
                                                <th class="py-2 text-uppercase small fw-bold text-dark text-center" style="background: #c6d2ff !important;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $j = 1;
                                            foreach ($walkers as $walkerId => $walker):
                                                $totalServiceBalance = end($walker['transactions'])['spbalance_after'] ?? 0;
                                                $totalEarnedBalance = end($walker['transactions'])['earned_balance'] ?? $walker['transactions'][0]['earned_amount'] ?? 0;
                                            ?>
                                                <tr class="walker-accordion" data-walker-name="<?= strtolower(esc($walker['walker_name'])) ?>" data-walker-id="<?= esc($walkerId) ?>">
                                                    <td class="ps-4 py-2"><?= $j++ ?></td>
                                                    <td class="py-2">
                                                        <div class="fw-bold text-dark"><?= esc($walker['walker_name']) ?></div>
                                                        <small class="text-secondary">ID: <?= esc($walkerId) ?></small>
                                                    </td>
                                                    <td class="text-end py-2">
                                                        <span class="badge rounded-pill px-3 py-1" style="background: rgba(37, 99, 235, 0.08); color: #2563eb; font-weight: 600;">
                                                            ₹<?= number_format($totalServiceBalance) ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-end py-2">
                                                        <span class="badge rounded-pill px-3 py-1" style="background: rgba(16, 185, 129, 0.08); color: #059669; font-weight: 600;">
                                                            ₹<?= number_format($totalEarnedBalance) ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center py-2">
                                                        <a href="<?= base_url('userDetails/provider/' . $walkerId) ?>" class="btn btn-sm btn-primary rounded-pill px-3 py-1">
                                                            <i class="bi bi-eye me-1"></i> View Details
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Pagination Footer for Walker Summary -->
                            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                                <div>
                                    <label class="me-2">Show 
                                        <select class="form-select d-inline-block" style="width: auto;" onchange="changeTableEntries('walkerSummaryTable', this.value)">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> entries
                                    </label>
                                </div>
                                <div class="table-pagination" id="walkerSummaryTable-pagination"></div>
                            </div>
                        </div>

                        <div id="platformWalletSection" class="wallet-section" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="fw-bold mb-0">Platform Wallet</h5>
                                <div class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <small>Showing <?= count($platform_transactions) ?> transactions</small>
                                </div>
                            </div>
                            <div class="table-responsive rounded-4">
                                <table class="table table-hover align-middle border-0 pagination-table" id="platformWalletTable" data-table-name="platformWalletTable">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4 py-2 text-uppercase small fw-bold text-dark" style="width: 60px; background: #c6d2ff !important;">S.No</th>
                                            <th class="py-2 text-uppercase small fw-bold text-dark" style="background: #c6d2ff !important;">Txn ID</th>
                                            <th class="py-2 text-uppercase small fw-bold text-dark" style="background: #c6d2ff !important;">Service ID</th>
                                            <th class="py-2 text-uppercase small fw-bold text-dark" style="background: #c6d2ff !important;">Date</th>
                                            <th class="py-2 text-uppercase small fw-bold text-dark" style="background: #c6d2ff !important;">Action</th>
                                            <th class="py-2 text-uppercase small fw-bold text-dark text-end pe-4" style="background: #c6d2ff !important;">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $p = 1;
                                        foreach ($platform_transactions as $row): ?>
                                            <tr data-date="<?= esc($row['date']) ?>" data-service-id="<?= esc($row['service_id']) ?>" data-txn-id="<?= esc($row['txn_id']) ?>">
                                                <td class="ps-4 py-2"><?= $p++ ?></td>
                                                <td class="py-2 text-dark"><?= esc($row['txn_id']) ?></td>
                                                <td class="py-2">
                                                    <span class="badge rounded-pill px-3 py-1" style="background-color: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); color: #2563eb; font-weight: 500;">
                                                        #<?= esc($row['service_id']) ?>
                                                    </span>
                                                </td>
                                                <td class="py-2 text-dark small"><?= esc($row['date']) ?></td>
                                                <td class="py-2 small text-secondary"><?= esc($row['action']) ?></td>
                                                <td class="py-2 text-end pe-4">
                                                    <span class="fw-bold" style="color: #16a34a;">₹<?= number_format($row['amount'], 2) ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php if (empty($platform_transactions)): ?>
                                            <tr>
                                                <td colspan="6" class="p-3 text-center text-muted">No platform wallet transactions found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination Footer for Platform Wallet -->
                            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                                <div>
                                    <label class="me-2">Show 
                                        <select class="form-select d-inline-block" style="width: auto;" onchange="changeTableEntries('platformWalletTable', this.value)">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> entries
                                    </label>
                                </div>
                                <div class="table-pagination" id="platformWalletTable-pagination"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Service View Content -->
                    <div id="serviceContent" class="tab-content" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

                            <!-- Left Side -->
                            <div class="d-flex align-items-center gap-3">
                                <h5 class="fw-bold mb-0">Service-Level Reconciliation</h5>
                                <div class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <small>Showing <?= count($summary) ?> services</small>
                                </div>
                            </div>

                            <!-- Right Side -->
                            <div class="d-flex align-items-center gap-2">

                                <select class="form-select" id="serviceStatusFilter" onchange="applyFilters()"
                                    style="border: 1px solid #8292a2; min-width: 180px;">
                                    <option value="">Status: All</option>
                                    <option value="Balanced">Balanced</option>
                                    <option value="Mismatch">Mismatch</option>
                                    <option value="Pending">Pending</option>
                                </select>

                                <button class="btn bg-primary px-4 py-2 text-white"
                                    onclick="applyFilters()">Filter</button>

                                <button class="btn bg-black px-4 py-2 text-white"
                                    onclick="clearFilters()">Clear</button>

                            </div>

                        </div>
                        <div class="table-responsive rounded-4">
                            <table class="table table-striped table-hover align-middle mb-0 pagination-table" id="serviceTable" data-table-name="serviceTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-2 text-uppercase small fw-bold text-dark" style="width: 60px; background: #c6d2ff !important;">S.No</th>
                                        <th class="py-2 text-uppercase small fw-bold text-dark" style="background: #c6d2ff !important;">Service ID</th>
                                        <th class="py-2 text-uppercase small fw-bold text-dark" style="background: #c6d2ff !important;">Client</th>
                                        <th class="py-2 text-uppercase small fw-bold text-dark" style="background: #c6d2ff !important;">Provider</th>
                                        <th class="py-2 text-uppercase small fw-bold text-dark text-end" style="background: #c6d2ff !important;">Booking Amt</th>
                                        <th class="py-2 text-uppercase small fw-bold text-dark text-end" style="background: #c6d2ff !important;">Refunds</th>
                                        <th class="py-2 text-uppercase small fw-bold text-dark text-end" style="background: #c6d2ff !important;">Provider</th>
                                        <th class="py-2 text-uppercase small fw-bold text-dark text-end" style="background: #c6d2ff !important;">Platform</th>
                                        <th class="py-2 text-uppercase small fw-bold text-dark text-center" style="background: #c6d2ff !important;">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700">
                                    <?php $s = 1;
                                    foreach ($summary as $row): ?>
                                        <tr data-date="<?= esc($row['date'] ?? '') ?>" data-status="<?= esc($row['status']) ?>" data-service-id="<?= esc($row['booking_id']) ?>" data-client-name="<?= strtolower(esc($row['client_name'])) ?>" data-walker-name="<?= strtolower(esc($row['walker_name'])) ?>">
                                            <td class="ps-4 py-2 font-medium"><?= $s++ ?></td>
                                            <td class="py-2 fw-bold text-dark"><?= esc($row['booking_id']) ?></td>
                                            <td class="py-2"><?= esc($row['client_name']) ?></td>
                                            <td class="py-2"><?= esc($row['walker_name']) ?></td>
                                            <td class="py-2 text-end">
                                                <span class="badge rounded-pill px-2 py-1" style="background-color: rgba(59, 130, 246, 0.1); color: #2563eb; border: 1px solid rgba(59, 130, 246, 0.2);">₹<?= number_format($row['booking_amt'], 2) ?></span>
                                            </td>
                                            <td class="py-2 text-end">
                                                <span class="badge rounded-pill px-2 py-1" style="background-color: rgba(59, 130, 246, 0.1); color: #2563eb; border: 1px solid rgba(59, 130, 246, 0.2);">
                                                    <?= $row['refunds'] > 0 ? '₹' . number_format($row['refunds'], 2) : '–' ?>
                                                </span>
                                            </td>
                                            <td class="py-2 text-end">
                                                <span class="badge rounded-pill px-2 py-1" style="background-color: rgba(251, 191, 36, 0.1); color: #d97706; border: 1px solid rgba(251, 191, 36, 0.2);">₹<?= number_format($row['walker_payouts'], 2) ?></span>
                                            </td>
                                            <td class="py-2 text-end">
                                                <span class="badge rounded-pill px-2 py-1" style="background: rgba(34, 197, 94, 0.1); color: #16a34a; border: 1px solid rgba(34, 197, 94, 0.2);">₹<?= number_format($row['pf_earnings'], 2) ?></span>
                                            </td>
                                            <td class="py-2 text-center">
                                                <?php if ($row['status'] == 'Balanced'): ?>
                                                    <span class="badge rounded-pill bg-success py-1 px-3">Balanced</span>
                                                <?php elseif ($row['status'] == 'Mismatch'): ?>
                                                    <span class="badge rounded-pill bg-danger py-1 px-3">Mismatch</span>
                                                <?php else: ?>
                                                    <span class="badge rounded-pill bg-warning py-1 px-3">Pending</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($summary)): ?>
                                        <tr>
                                            <td colspan="9" class="p-3 text-center text-muted">No records found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination Footer for Service Table -->
                        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                            <div>
                                <label class="me-2">Show 
                                    <select class="form-select d-inline-block" style="width: auto;" onchange="changeTableEntries('serviceTable', this.value)">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select> entries
                                </label>
                            </div>
                            <div class="table-pagination" id="serviceTable-pagination"></div>
                        </div>
                    </div>

                    <!-- Discrepancies View Content -->
                    <div id="mismatchContent" class="tab-content" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

                            <!-- Left Side -->
                            <div class="d-flex align-items-center gap-3">
                                <h5 class="fw-bold mb-0">Financial Discrepancies</h5>
                                <div class="text-danger">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    <small><?= count($mismatch_alerts) ?> discrepancies found</small>
                                </div>
                            </div>

                            <!-- Right Side -->
                            <div class="d-flex align-items-center gap-2">

                                <input type="date" class="form-control custom-date" id="mismatchDateFilter"
                                    onchange="applyFilters()" style="min-width: 170px;" />

                                <select class="form-select" id="mismatchStatusFilter" onchange="applyFilters()"
                                    style="border: 1px solid #8292a2; min-width: 170px;">
                                    <option value="">Status: All</option>
                                    <option value="Mismatch">Mismatch</option>
                                    <option value="Resolved">Resolved</option>
                                </select>

                                <button class="btn bg-primary px-4 py-2 text-white"
                                    onclick="applyFilters()">Filter</button>

                                <button class="btn bg-black px-4 py-2 text-white"
                                    onclick="clearFilters()">Clear</button>

                            </div>

                        </div>
                        <div class="table-responsive rounded-4">
                            <table class="table table-striped table-hover align-middle mb-0 pagination-table" id="mismatchTable" data-table-name="mismatchTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-2 text-uppercase small fw-bold text-dark" style="width: 60px; background: #c6d2ff !important;">S.No</th>
                                        <th class="py-2 text-uppercase small fw-bold text-dark" style="background: #c6d2ff !important;">Service ID</th>
                                        <th class="py-2 text-uppercase small fw-bold text-dark" style="background: #c6d2ff !important;">User Type</th>
                                        <th class="py-2 text-uppercase small fw-bold text-dark" style="background: #c6d2ff !important;">User Name</th>
                                        <th class="py-2 text-uppercase small fw-bold text-dark text-end" style="background: #c6d2ff !important;">Expected</th>
                                        <th class="py-2 text-uppercase small fw-bold text-dark text-end" style="background: #c6d2ff !important;">Current</th>
                                        <th class="py-2 text-uppercase small fw-bold text-dark text-end" style="background: #c6d2ff !important;">Diff</th>
                                        <th class="py-2 text-uppercase small fw-bold text-dark" style="background: #c6d2ff !important;">Date</th>
                                        <th class="py-2 text-uppercase small fw-bold text-dark text-center" style="background: #c6d2ff !important;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $m = 1;
                                    foreach ($mismatch_alerts as $row): ?>
                                        <tr data-date="<?= esc($row['date']) ?>" data-user-type="<?= strtolower(esc($row['user_type'])) ?>" data-status="<?= esc($row['status']) ?>" data-service-id="<?= esc($row['service_id']) ?? '' ?>" data-user-name="<?= strtolower(esc($row['user_name'])) ?? '' ?>" class="mismatch-row">
                                            <td class="ps-4 py-2"><?= $m++ ?></td>
                                            <td class="py-2 fw-bold text-dark"><?= esc($row['service_id']) ?? 0 ?></td>
                                            <td class="py-2"><span class="badge rounded-pill px-2 py-1 bg-opacity-10 <?= $row['user_type'] == 'Client' ? 'bg-primary text-primary' : 'bg-warning text-dark' ?>"><?= esc($row['user_type']) ?></span></td>
                                            <td class="py-2"><?= esc($row['user_name']) ?></td>
                                            <td class="py-2 text-end">₹<?= number_format($row['expected_amount'], 2) ?></td>
                                            <td class="py-2 text-end">₹<?= number_format($row['actual_amount'], 2) ?></td>
                                            <td class="py-2 text-end"><span class="text-danger fw-bold">₹<?= number_format(abs($row['difference']), 2) ?></span></td>
                                            <td class="py-2 small text-muted"><?= esc($row['date']) ?></td>
                                            <td class="py-2 text-center">
                                                <span class="badge rounded-pill <?= $row['status'] == 'Resolved' ? 'bg-success' : 'bg-danger' ?> py-1 px-3"><?= esc($row['status']) ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($mismatch_alerts)): ?>
                                        <tr>
                                            <td colspan="9" class="p-3 text-center text-muted">No mismatch alerts found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination Footer for Mismatch Table -->
                        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                            <div>
                                <label class="me-2">Show 
                                    <select class="form-select d-inline-block" style="width: auto;" onchange="changeTableEntries('mismatchTable', this.value)">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select> entries
                                </label>
                            </div>
                            <div class="table-pagination" id="mismatchTable-pagination"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .recon-gradient-primary {
        background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
    }

    .custom-date {
        border: 1px solid #8292a2 !important;
        border-radius: 10px !important;
        height: 40px !important;
        background-color: #fff !important;
    }

    .custom-date::-webkit-calendar-picker-indicator {
        cursor: pointer;
        opacity: 0.7;
    }

    .glass-card-recon {
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(20px) saturate(180%) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        border-radius: 16px !important;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1) !important;
    }

    .summary-card {
        background: rgba(255, 255, 255, 0.95) !important;
        border: 1px solid rgba(67, 97, 238, 0.1) !important;
        border-radius: 12px !important;
    }

    .recon-tab {
        border: none;
        border-radius: 50px;
        padding: 0.75rem 1.5rem;
        color: #6366f1;
        background: transparent;
        transition: all 0.3s ease;
        border: 1px solid #6366f1;
    }

    .recon-tab:hover {
        color: #6366f1;
        background-color: rgba(99, 102, 241, 0.05);
    }

    .recon-tab.active {
        background: #3f30e3;
        color: #ffffff;
        border-bottom: 1px solid #6366f1;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
    }

    .recon-tab.active .badge {
        background-color: rgba(255, 255, 255, 0.2) !important;
        color: white !important;
    }

    .accordion-recon {
        /* border: 1px solid rgba(67, 97, 238, 0.1) !important; */
        border-radius: 12px !important;
        overflow: hidden;
    }

    .accordion-recon .accordion-item {
        border: none;
    }

    .accordion-recon .accordion-button {
        border-radius: 16px !important;
        border: 1px solid #e2e8f0;
        margin-bottom: 8px;
    }

    .accordion-recon .accordion-button:not(.collapsed) {
        background-color: #8b8fff !important;
        box-shadow: none;
    }

    .accordion-recon .accordion-button:not(.collapsed) .badge {
        background: rgba(255, 255, 255, 0.2) !important;
        color: white !important;
    }

    .category-badge {
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: capitalize;
        background: #dfe7ff;
        color: #1a3cb5;
    }

    .table-recon {
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-recon thead th {
        background: rgba(67, 97, 238, 0.05) !important;
        color: #374151;
        font-weight: 600;
        padding: 1rem;
        border-bottom: 2px solid rgba(67, 97, 238, 0.1);
    }

    .table-recon tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .table-recon tbody tr:hover {
        background: rgba(67, 97, 238, 0.03) !important;
    }

    .table-recon tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .input-group:focus-within {
        box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.2);
        border-color: #4361ee;
    }

    /* Pagination Styles */
    .pagination {
        gap: 0.25rem;
    }

    .page-link {
        color: #6366f1;
        border: 1px solid #e5e7eb;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .page-link:hover:not(.disabled) {
        color: #fff;
        background-color: #6366f1;
        border-color: #6366f1;
    }

    .page-item.active .page-link {
        background-color: #6366f1;
        border-color: #6366f1;
        color: #fff;
    }

    .page-item.disabled .page-link {
        color: #9ca3af;
        cursor: not-allowed;
        background-color: #f3f4f6;
        border-color: #e5e7eb;
    }

    /* Show entries select styling */
    .form-select.d-inline-block {
        padding: 0.375rem 2rem 0.375rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
        appearance: none;
        padding-right: 2rem;
    }

    .table-pagination {
        display: flex;
        justify-content: center;
    }
</style>

<?php echo $this->include('admin/footer') ?>

<script>
    let currentMainTab = 'wallet';
    const tableState = {};

    document.addEventListener('DOMContentLoaded', function() {
        showTab('wallet'); // Set Wallet View as default
        document.getElementById('userTypeFilter').value = 'service_provider'; // Set Service Provider as default in dropdown
        document.getElementById('globalSearchInput').value = ''; // Clear search input
        filterByUserType(); // Apply initial filter

        // Initialize pagination for all client and walker tables
        initializeAllPagination();

        // Initialize pagination for main tables
        initializeTablePagination('clientSummaryTable');
        initializeTablePagination('walkerSummaryTable');
        initializeTablePagination('platformWalletTable');
        initializeTablePagination('serviceTable');
        initializeTablePagination('mismatchTable');
    });

    // Handle browser back navigation to clear inputs
    window.addEventListener('pageshow', function(event) {
        document.getElementById('globalSearchInput').value = '';
        document.getElementById('userTypeFilter').value = 'service_provider';
        filterByUserType();
    });

    // Pagination functions for main tables
    function initializeTablePagination(tableId) {
        const table = document.getElementById(tableId);
        if (!table) return;

        const rows = Array.from(table.querySelectorAll('tbody tr:not(:has(td[colspan]))'));
        tableState[tableId] = {
            currentPage: 1,
            rowsPerPage: 10,
            totalRows: rows.length,
            allRows: rows
        };
        updateTablePagination(tableId);
    }

    function changeTableEntries(tableId, entries) {
        if (tableState[tableId]) {
            tableState[tableId].rowsPerPage = parseInt(entries);
            tableState[tableId].currentPage = 1;
            updateTablePagination(tableId);
            displayTablePage(tableId, 1);
        }
    }

    function updateTablePagination(tableId) {
        const state = tableState[tableId];
        if (!state) return;

        const table = document.getElementById(tableId);
        const visibleRows = Array.from(table.querySelectorAll('tbody tr')).filter(row => {
            return row.style.display !== 'none' && !row.textContent.includes('No records') && !row.textContent.includes('No mismatch');
        });

        const totalPages = Math.ceil(visibleRows.length / state.rowsPerPage);
        const paginationContainer = document.getElementById(tableId + '-pagination');

        if (!paginationContainer) return;

        let paginationHTML = '';

        if (totalPages > 1) {
            paginationHTML = '<nav><ul class="pagination mb-0">';
            
            // Previous button
            paginationHTML += '<li class="page-item ' + (state.currentPage === 1 ? 'disabled' : '') + '">';
            paginationHTML += '<a class="page-link" href="#" onclick="changeTablePageNumber(\'' + tableId + '\', ' + (state.currentPage - 1) + '); return false;">Previous</a>';
            paginationHTML += '</li>';

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= state.currentPage - 1 && i <= state.currentPage + 1)) {
                    if (i > 1 && (i === state.currentPage - 2 || (state.currentPage > 3 && i === 2))) {
                        paginationHTML += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }
                    paginationHTML += '<li class="page-item ' + (i === state.currentPage ? 'active' : '') + '">';
                    paginationHTML += '<a class="page-link" href="#" onclick="changeTablePageNumber(\'' + tableId + '\', ' + i + '); return false;">' + i + '</a>';
                    paginationHTML += '</li>';
                }
            }

            // Next button
            paginationHTML += '<li class="page-item ' + (state.currentPage === totalPages ? 'disabled' : '') + '">';
            paginationHTML += '<a class="page-link" href="#" onclick="changeTablePageNumber(\'' + tableId + '\', ' + (state.currentPage + 1) + '); return false;">Next</a>';
            paginationHTML += '</li>';

            paginationHTML += '</ul></nav>';
        }

        paginationContainer.innerHTML = paginationHTML;
        displayTablePage(tableId, state.currentPage);
    }

    function changeTablePageNumber(tableId, pageNumber) {
        const state = tableState[tableId];
        if (!state) return;

        const table = document.getElementById(tableId);
        const visibleRows = Array.from(table.querySelectorAll('tbody tr')).filter(row => {
            return row.style.display !== 'none' && !row.textContent.includes('No records') && !row.textContent.includes('No mismatch');
        });

        const totalPages = Math.ceil(visibleRows.length / state.rowsPerPage);

        if (pageNumber >= 1 && pageNumber <= totalPages) {
            state.currentPage = pageNumber;
            updateTablePagination(tableId);
        }
    }

    function displayTablePage(tableId, pageNumber) {
        const table = document.getElementById(tableId);
        const state = tableState[tableId];
        if (!state || !table) return;

        const allRows = Array.from(table.querySelectorAll('tbody tr'));
        const visibleRows = allRows.filter(row => row.style.display !== 'none' && !row.textContent.includes('No records') && !row.textContent.includes('No mismatch'));

        const start = (pageNumber - 1) * state.rowsPerPage;
        const end = start + state.rowsPerPage;

        allRows.forEach(row => {
            row.style.display = 'none';
        });

        visibleRows.slice(start, end).forEach(row => {
            row.style.display = '';
        });
    }

    // Pagination variables for client tables
    const clientPageState = {};
    const walkerPageState = {};
    const ROWS_PER_PAGE = 10;

    function initializeAllPagination() {
        // Initialize client tables pagination
        <?php for ($k = 1; $k <= count($clients); $k++): ?>
            clientPageState[<?= $k ?>] = {
                currentPage: 1,
                totalRows: document.querySelectorAll('#clientTransactionsTable<?= $k ?> tbody tr').length,
                filteredRows: []
            };
            updateClientPagination(<?= $k ?>);
        <?php endfor; ?>

        // Initialize walker tables pagination
        <?php for ($k = 1; $k <= count($walkers); $k++): ?>
            walkerPageState[<?= $k ?>] = {
                currentPage: 1,
                totalRows: document.querySelectorAll('#walkerTransactionsTable<?= $k ?> tbody tr').length,
                filteredRows: []
            };
            updateWalkerPagination(<?= $k ?>);
        <?php endfor; ?>
    }

    function changeClientPage(accordionId, direction) {
        const state = clientPageState[accordionId];
        const rows = document.querySelectorAll('#clientTransactionsTable' + accordionId + ' tbody tr');
        const totalRows = rows.length;

        if (direction === 'next' && state.currentPage < Math.ceil(totalRows / ROWS_PER_PAGE)) {
            state.currentPage++;
        } else if (direction === 'prev' && state.currentPage > 1) {
            state.currentPage--;
        }

        updateClientPagination(accordionId);
        filterClientTransactions(null, accordionId);
    }

    function changeWalkerPage(accordionId, direction) {
        const state = walkerPageState[accordionId];
        const rows = document.querySelectorAll('#walkerTransactionsTable' + accordionId + ' tbody tr');
        const totalRows = rows.length;

        if (direction === 'next' && state.currentPage < Math.ceil(totalRows / ROWS_PER_PAGE)) {
            state.currentPage++;
        } else if (direction === 'prev' && state.currentPage > 1) {
            state.currentPage--;
        }

        updateWalkerPagination(accordionId);
        filterWalkerTransactions(null, accordionId);
    }

    function updateClientPagination(accordionId) {
        const state = clientPageState[accordionId];
        const rows = document.querySelectorAll('#clientTransactionsTable' + accordionId + ' tbody tr');
        const totalRows = rows.length;
        const totalPages = Math.ceil(totalRows / ROWS_PER_PAGE);

        document.getElementById('clientCurrentPage' + accordionId).textContent = state.currentPage;
        document.getElementById('clientPageInfo' + accordionId).textContent =
            `Showing ${Math.min((state.currentPage - 1) * ROWS_PER_PAGE + 1, totalRows)} - ${Math.min(state.currentPage * ROWS_PER_PAGE, totalRows)} of ${totalRows} entries`;

        document.getElementById('clientPrevBtn' + accordionId).disabled = state.currentPage === 1;
        document.getElementById('clientNextBtn' + accordionId).disabled = state.currentPage === totalPages || totalPages === 0;
    }

    function updateWalkerPagination(accordionId) {
        const state = walkerPageState[accordionId];
        const rows = document.querySelectorAll('#walkerTransactionsTable' + accordionId + ' tbody tr');
        const totalRows = rows.length;
        const totalPages = Math.ceil(totalRows / ROWS_PER_PAGE);

        document.getElementById('walkerCurrentPage' + accordionId).textContent = state.currentPage;
        document.getElementById('walkerPageInfo' + accordionId).textContent =
            `Showing ${Math.min((state.currentPage - 1) * ROWS_PER_PAGE + 1, totalRows)} - ${Math.min(state.currentPage * ROWS_PER_PAGE, totalRows)} of ${totalRows} entries`;

        document.getElementById('walkerPrevBtn' + accordionId).disabled = state.currentPage === 1;
        document.getElementById('walkerNextBtn' + accordionId).disabled = state.currentPage === totalPages || totalPages === 0;
    }

    function showTab(tabName) {
        document.getElementById('walletContent').style.display = 'none';
        document.getElementById('serviceContent').style.display = 'none';
        document.getElementById('mismatchContent').style.display = 'none';

        document.getElementById('walletTab').classList.remove('active');
        document.getElementById('serviceTab').classList.remove('active');
        document.getElementById('mismatchTab').classList.remove('active');

        document.getElementById(tabName + 'Content').style.display = 'block';
        document.getElementById(tabName + 'Tab').classList.add('active');
        currentMainTab = tabName;

        // Hide dropdown in Service View, show in others
        if (tabName === 'service') {
            document.getElementById('userTypeFilterContainer').style.display = 'none';
        } else {
            document.getElementById('userTypeFilterContainer').style.display = 'block';
        }

        // Apply filters when switching tabs
        if (tabName === 'service') {
            applyFilters();
        } else if (tabName === 'wallet') {
            filterByUserType();
        } else if (tabName === 'mismatch') {
            filterByUserType();
        }
    }

    function filterByUserType() {
        const selectedType = document.getElementById('userTypeFilter').value;

        if (currentMainTab === 'wallet') {
            // Hide all wallet sections first
            document.getElementById('clientWalletSection').style.display = 'none';
            document.getElementById('serviceProviderWalletSection').style.display = 'none';
            document.getElementById('platformWalletSection').style.display = 'none';

            // Show the selected section
            if (selectedType === 'client') {
                document.getElementById('clientWalletSection').style.display = 'block';
                if (tableState['clientSummaryTable']) {
                    tableState['clientSummaryTable'].currentPage = 1;
                    updateTablePagination('clientSummaryTable');
                }
            } else if (selectedType === 'service_provider') {
                document.getElementById('serviceProviderWalletSection').style.display = 'block';
                if (tableState['walkerSummaryTable']) {
                    tableState['walkerSummaryTable'].currentPage = 1;
                    updateTablePagination('walkerSummaryTable');
                }
            } else if (selectedType === 'platform') {
                document.getElementById('platformWalletSection').style.display = 'block';
                if (tableState['platformWalletTable']) {
                    tableState['platformWalletTable'].currentPage = 1;
                    updateTablePagination('platformWalletTable');
                }
            }
        } else if (currentMainTab === 'mismatch') {
            // Filter mismatch table based on user type
            const rows = document.querySelectorAll('#mismatchTable tbody tr.mismatch-row');
            let visibleCount = 0;

            rows.forEach(row => {
                if (row.textContent.includes('No records')) return;

                const userType = row.getAttribute('data-user-type');

                // Convert selected type to match data attribute format
                let selectedValue = selectedType;
                if (selectedType === 'client') {
                    selectedValue = 'client';
                } else if (selectedType === 'service_provider') {
                    selectedValue = 'service provider';
                }

                // Check if user type contains the selected value (case insensitive)
                const userTypeLower = userType ? userType.toLowerCase() : '';
                const selectedLower = selectedValue ? selectedValue.toLowerCase() : '';

                if (userTypeLower.includes(selectedLower) || selectedLower.includes(userTypeLower)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Show no records message if needed
            const noRecordsRow = document.querySelector('#mismatchTable tbody tr:last-child');
            if (noRecordsRow && noRecordsRow.textContent.includes('No records')) {
                noRecordsRow.style.display = visibleCount === 0 ? '' : 'none';
            }

            // Update pagination after filtering
            if (tableState['mismatchTable']) {
                tableState['mismatchTable'].currentPage = 1;
                updateTablePagination('mismatchTable');
            }
        }

        // Apply global search after filtering by type
        applyGlobalSearch();
    }

    function applyGlobalSearch() {
        const searchTerm = document.getElementById('globalSearchInput').value.toLowerCase();

        if (currentMainTab === 'service') {
            // Search in service table
            const rows = document.querySelectorAll('#serviceTable tbody tr');
            rows.forEach(row => {
                if (row.textContent.includes('No records')) return;

                const serviceId = row.getAttribute('data-service-id')?.toLowerCase() || '';
                const clientName = row.getAttribute('data-client-name')?.toLowerCase() || '';
                const walkerName = row.getAttribute('data-walker-name')?.toLowerCase() || '';
                const rowText = row.textContent.toLowerCase();

                const matches = !searchTerm ||
                    serviceId.includes(searchTerm) ||
                    clientName.includes(searchTerm) ||
                    walkerName.includes(searchTerm) ||
                    rowText.includes(searchTerm);

                row.style.display = matches ? '' : 'none';
            });

            // Update pagination after search
            if (tableState['serviceTable']) {
                tableState['serviceTable'].currentPage = 1;
                updateTablePagination('serviceTable');
            }
        } else if (currentMainTab === 'wallet') {
            const selectedType = document.getElementById('userTypeFilter').value;

            if (selectedType === 'client') {
                // Search in client wallets
                const accordions = document.querySelectorAll('.client-accordion');
                accordions.forEach(accordion => {
                    const clientName = accordion.getAttribute('data-client-name') || '';
                    const clientId = accordion.getAttribute('data-client-id') || '';

                    const matches = !searchTerm ||
                        clientName.includes(searchTerm) ||
                        clientId.includes(searchTerm);

                    accordion.style.display = matches ? '' : 'none';
                });

                // Update pagination after search
                if (tableState['clientSummaryTable']) {
                    tableState['clientSummaryTable'].currentPage = 1;
                    updateTablePagination('clientSummaryTable');
                }
            } else if (selectedType === 'service_provider') {
                // Search in walker wallets
                const accordions = document.querySelectorAll('.walker-accordion');
                accordions.forEach(accordion => {
                    const walkerName = accordion.getAttribute('data-walker-name') || '';
                    const walkerId = accordion.getAttribute('data-walker-id') || '';

                    const matches = !searchTerm ||
                        walkerName.includes(searchTerm) ||
                        walkerId.includes(searchTerm);

                    accordion.style.display = matches ? '' : 'none';
                });

                // Update pagination after search
                if (tableState['walkerSummaryTable']) {
                    tableState['walkerSummaryTable'].currentPage = 1;
                    updateTablePagination('walkerSummaryTable');
                }
            } else if (selectedType === 'platform') {
                // Search in platform wallet
                const rows = document.querySelectorAll('#platformWalletTable tbody tr');
                rows.forEach(row => {
                    if (row.textContent.includes('No records')) return;

                    const serviceId = row.getAttribute('data-service-id')?.toLowerCase() || '';
                    const txnId = row.getAttribute('data-txn-id')?.toLowerCase() || '';
                    const rowText = row.textContent.toLowerCase();

                    const matches = !searchTerm ||
                        serviceId.includes(searchTerm) ||
                        txnId.includes(searchTerm) ||
                        rowText.includes(searchTerm);

                    row.style.display = matches ? '' : 'none';
                });

                // Update pagination after search
                if (tableState['platformWalletTable']) {
                    tableState['platformWalletTable'].currentPage = 1;
                    updateTablePagination('platformWalletTable');
                }
            }
        } else if (currentMainTab === 'mismatch') {
            // Search in mismatch table
            const rows = document.querySelectorAll('#mismatchTable tbody tr.mismatch-row');
            rows.forEach(row => {
                if (row.textContent.includes('No records')) return;

                const serviceId = row.getAttribute('data-service-id')?.toLowerCase() || '';
                const userName = row.getAttribute('data-user-name')?.toLowerCase() || '';
                const userType = row.getAttribute('data-user-type')?.toLowerCase() || '';
                const rowText = row.textContent.toLowerCase();

                const matches = !searchTerm ||
                    serviceId.includes(searchTerm) ||
                    userName.includes(searchTerm) ||
                    userType.includes(searchTerm) ||
                    rowText.includes(searchTerm);

                // Also respect user type filter
                const selectedType = document.getElementById('userTypeFilter').value;

                // Convert selected type to match data attribute format
                let selectedValue = selectedType;
                if (selectedType === 'client') {
                    selectedValue = 'client';
                } else if (selectedType === 'service_provider') {
                    selectedValue = 'service provider';
                }

                // Check if user type contains the selected value (case insensitive)
                const userTypeLower = userType ? userType.toLowerCase() : '';
                const selectedLower = selectedValue ? selectedValue.toLowerCase() : '';
                const typeMatches = userTypeLower.includes(selectedLower) || selectedLower.includes(userTypeLower);

                row.style.display = (matches && typeMatches) ? '' : 'none';
            });

            // Update pagination after search
            if (tableState['mismatchTable']) {
                tableState['mismatchTable'].currentPage = 1;
                updateTablePagination('mismatchTable');
            }
        }
    }

    function applyFilters() {
        if (currentMainTab === 'service') {
            const statusFilter = document.getElementById('serviceStatusFilter').value;
            const searchTerm = document.getElementById('globalSearchInput').value.toLowerCase();

            const rows = document.querySelectorAll('#serviceTable tbody tr');
            rows.forEach(row => {
                if (row.textContent.includes('No records')) return;

                const rowStatus = row.getAttribute('data-status');
                const matchesStatus = !statusFilter || rowStatus === statusFilter;

                const serviceId = row.getAttribute('data-service-id')?.toLowerCase() || '';
                const clientName = row.getAttribute('data-client-name')?.toLowerCase() || '';
                const walkerName = row.getAttribute('data-walker-name')?.toLowerCase() || '';
                const matchesSearch = !searchTerm ||
                    serviceId.includes(searchTerm) ||
                    clientName.includes(searchTerm) ||
                    walkerName.includes(searchTerm);

                row.style.display = (matchesStatus && matchesSearch) ? '' : 'none';
            });

            // Update pagination after filtering
            if (tableState['serviceTable']) {
                tableState['serviceTable'].currentPage = 1;
                updateTablePagination('serviceTable');
            }
        } else if (currentMainTab === 'mismatch') {
            const dateFilter = document.getElementById('mismatchDateFilter').value;
            const statusFilter = document.getElementById('mismatchStatusFilter').value;
            const searchTerm = document.getElementById('globalSearchInput').value.toLowerCase();
            const selectedType = document.getElementById('userTypeFilter').value;

            const rows = document.querySelectorAll('#mismatchTable tbody tr.mismatch-row');
            rows.forEach(row => {
                if (row.textContent.includes('No records')) return;

                const rowDate = row.getAttribute('data-date');
                const rowStatus = row.getAttribute('data-status');
                const userType = row.getAttribute('data-user-type');

                const matchesDate = !dateFilter || rowDate === dateFilter;
                const matchesStatus = !statusFilter || rowStatus === statusFilter;

                // Convert selected type to match data attribute format
                let selectedValue = selectedType;
                if (selectedType === 'client') {
                    selectedValue = 'client';
                } else if (selectedType === 'service_provider') {
                    selectedValue = 'service provider';
                }

                // Check if user type contains the selected value (case insensitive)
                const userTypeLower = userType ? userType.toLowerCase() : '';
                const selectedLower = selectedValue ? selectedValue.toLowerCase() : '';
                const matchesType = userTypeLower.includes(selectedLower) || selectedLower.includes(userTypeLower);

                const serviceId = row.getAttribute('data-service-id')?.toLowerCase() || '';
                const userName = row.getAttribute('data-user-name')?.toLowerCase() || '';
                const matchesSearch = !searchTerm ||
                    serviceId.includes(searchTerm) ||
                    userName.includes(searchTerm) ||
                    userType?.includes(searchTerm);

                row.style.display = (matchesDate && matchesStatus && matchesType && matchesSearch) ? '' : 'none';
            });

            // Update pagination after filtering
            if (tableState['mismatchTable']) {
                tableState['mismatchTable'].currentPage = 1;
                updateTablePagination('mismatchTable');
            }
        }
    }

    function filterClientTransactions(element, accordionId) {
        const searchTerm = document.getElementById('clientTransactionSearch' + accordionId).value.toLowerCase();
        const typeFilter = document.getElementById('clientTypeFilter' + accordionId).value;
        const categoryFilter = document.getElementById('clientCategoryFilter' + accordionId).value.toLowerCase().replace(/ /g, '_');

        const allRows = document.querySelectorAll('#clientTransactionsTable' + accordionId + ' tbody tr');
        const filteredRows = [];

        // Apply filters to all rows
        allRows.forEach(row => {
            const serviceId = row.getAttribute('data-service-id').toLowerCase();
            const txnId = row.getAttribute('data-txn-id').toLowerCase();
            const rowType = row.getAttribute('data-type');
            const rowCategory = row.getAttribute('data-category');

            const matchesSearch = !searchTerm || serviceId.includes(searchTerm) || txnId.includes(searchTerm);
            const matchesType = !typeFilter || rowType === typeFilter;
            const matchesCategory = !categoryFilter || rowCategory === categoryFilter;

            if (matchesSearch && matchesType && matchesCategory) {
                filteredRows.push(row);
            }
        });

        // Store filtered rows in state
        clientPageState[accordionId].filteredRows = filteredRows;

        // Reset to page 1
        clientPageState[accordionId].currentPage = 1;

        // Update pagination info
        updateClientPagination(accordionId);

        // Show only current page rows
        const start = (clientPageState[accordionId].currentPage - 1) * ROWS_PER_PAGE;
        const end = start + ROWS_PER_PAGE;

        allRows.forEach(row => row.style.display = 'none');
        filteredRows.slice(start, end).forEach(row => row.style.display = '');
    }

    function filterWalkerTransactions(element, accordionId) {
        const searchTerm = document.getElementById('walkerTransactionSearch' + accordionId).value.toLowerCase();
        const typeFilter = document.getElementById('walkerTypeFilter' + accordionId).value;
        const categoryFilter = document.getElementById('walkerCategoryFilter' + accordionId).value.toLowerCase().replace(/ /g, '_');

        const allRows = document.querySelectorAll('#walkerTransactionsTable' + accordionId + ' tbody tr');
        const filteredRows = [];

        // Apply filters to all rows
        allRows.forEach(row => {
            const serviceId = row.getAttribute('data-service-id').toLowerCase();
            const txnId = row.getAttribute('data-txn-id').toLowerCase();
            const rowType = row.getAttribute('data-type');
            const rowCategory = row.getAttribute('data-category');

            const matchesSearch = !searchTerm || serviceId.includes(searchTerm) || txnId.includes(searchTerm);
            const matchesType = !typeFilter || rowType === typeFilter;
            const matchesCategory = !categoryFilter || rowCategory === categoryFilter;

            if (matchesSearch && matchesType && matchesCategory) {
                filteredRows.push(row);
            }
        });

        // Store filtered rows in state
        walkerPageState[accordionId].filteredRows = filteredRows;

        // Reset to page 1
        walkerPageState[accordionId].currentPage = 1;

        // Update pagination info
        updateWalkerPagination(accordionId);

        // Show only current page rows
        const start = (walkerPageState[accordionId].currentPage - 1) * ROWS_PER_PAGE;
        const end = start + ROWS_PER_PAGE;

        allRows.forEach(row => row.style.display = 'none');
        filteredRows.slice(start, end).forEach(row => row.style.display = '');
    }

    function clearFilters() {
        if (currentMainTab === 'service') {
            document.getElementById('serviceStatusFilter').value = '';
        } else if (currentMainTab === 'mismatch') {
            document.getElementById('mismatchDateFilter').value = '';
            document.getElementById('mismatchStatusFilter').value = '';
        }

        document.getElementById('globalSearchInput').value = '';

        // Reset user type filter to default 'service_provider'
        document.getElementById('userTypeFilter').value = 'service_provider';

        // Reset all transaction search inputs and filters
        document.querySelectorAll('[id^="clientTransactionSearch"]').forEach(input => input.value = '');
        document.querySelectorAll('[id^="walkerTransactionSearch"]').forEach(input => input.value = '');
        document.querySelectorAll('[id^="clientTypeFilter"]').forEach(select => select.value = '');
        document.querySelectorAll('[id^="clientCategoryFilter"]').forEach(select => select.value = '');
        document.querySelectorAll('[id^="walkerTypeFilter"]').forEach(select => select.value = '');
        document.querySelectorAll('[id^="walkerCategoryFilter"]').forEach(select => select.value = '');

        // Reset pagination states and show all rows with pagination
        <?php for ($k = 1; $k <= count($clients); $k++): ?>
            clientPageState[<?= $k ?>] = {
                currentPage: 1,
                totalRows: document.querySelectorAll('#clientTransactionsTable<?= $k ?> tbody tr').length,
                filteredRows: []
            };
            updateClientPagination(<?= $k ?>);
            filterClientTransactions(null, <?= $k ?>);
        <?php endfor; ?>

        <?php for ($k = 1; $k <= count($walkers); $k++): ?>
            walkerPageState[<?= $k ?>] = {
                currentPage: 1,
                totalRows: document.querySelectorAll('#walkerTransactionsTable<?= $k ?> tbody tr').length,
                filteredRows: []
            };
            updateWalkerPagination(<?= $k ?>);
            filterWalkerTransactions(null, <?= $k ?>);
        <?php endfor; ?>

        // Reapply filters
        filterByUserType();
        applyFilters();
    }

    function exportReport() {
        alert('Export functionality will be implemented');
    }

    function refreshData() {
        location.reload();
    }
</script>
<?php echo $this->endSection() ?>