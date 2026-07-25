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
                                <option value="client" selected>Client</option>
                                <option value="service_provider">Service Provider</option>
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
                                <p class="text-muted mb-1">Total Bookings</p>
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
                                <p class="text-muted mb-1">Provider Payouts</p>
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
                                <p class="text-muted mb-1">Client Refunds</p>
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
                                <p class="text-muted mb-1">Platform Commission</p>
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
                            <div class="accordion accordion-recon" id="clientAccordion">
                                <?php $i = 1;
                                foreach ($clients as $clientId => $client):
                                    $totalServiceBalance = end($client['transactions'])['balance'] ?? 0;
                                    $totalRefundBalance = end($client['transactions'])['refundBalance'] ?? 0;
                                    ?>
                                    <div class="accordion-item client-accordion"
                                        data-client-name="<?= strtolower(esc($client['client_name'])) ?>"
                                        data-client-id="<?= esc($clientId) ?>">
                                        <h2 class="accordion-header" id="clientHeading<?= $i ?>">
                                            <button class="accordion-button collapsed py-3 px-4 bg-white" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#clientCollapse<?= $i ?>"
                                                style="box-shadow: none;">

                                                <div class="d-flex justify-content-between align-items-center w-100">

                                                    <!-- Left Side -->
                                                    <div class="d-flex align-items-center gap-3">

                                                        <div>
                                                            <strong class="text-dark fs-6">
                                                                <?= esc($client['client_name']) ?>
                                                            </strong>
                                                        </div>

                                                        <span class="badge rounded-pill px-3 py-2"
                                                            style="background: #f1f5f9; color: #475569; font-weight: 500;">
                                                            ID: <?= esc($clientId) ?>
                                                        </span>

                                                    </div>

                                                    <!-- Right Side -->
                                                    <div class="d-flex gap-3 align-items-center">

                                                        <!-- Service Balance -->
                                                        <span class="badge rounded-pill px-3 py-2" style="background: rgba(37, 99, 235, 0.08);
                       color: #2563eb;
                       border: 1px solid rgba(37, 99, 235, 0.2);
                       font-weight: 600;">

                                                            Service Bal: ₹<?= number_format($totalServiceBalance) ?>

                                                        </span>

                                                        <!-- Refund Balance -->
                                                        <span class="badge rounded-pill px-3 py-2" style="background: rgba(16, 185, 129, 0.08);
                       color: #059669;
                       border: 1px solid rgba(16, 185, 129, 0.2);
                       font-weight: 600;">

                                                            Refund Bal: ₹<?= number_format($totalRefundBalance) ?>

                                                        </span>

                                                    </div>

                                                </div>
                                            </button>
                                        </h2>
                                        <div id="clientCollapse<?= $i ?>" class="accordion-collapse collapse"
                                            data-bs-parent="#clientAccordion">
                                            <div class="accordion-body p-3">
                                                <div class="row mb-3 align-items-center g-3">

                                                    <!-- Search -->
                                                    <div class="col-md-6">
                                                        <input type="text" id="clientTransactionSearch<?= $i ?>"
                                                            class="form-control"
                                                            placeholder="Search transactions by Service ID..."
                                                            onkeyup="filterClientTransactions(this, <?= $i ?>)">
                                                    </div>

                                                    <!-- Type Filter -->
                                                    <div class="col-md-3">
                                                        <select class="form-select filter-type"
                                                            id="clientTypeFilter<?= $i ?>"
                                                            onchange="filterClientTransactions(this, <?= $i ?>)">
                                                            <option value="">All Types</option>
                                                            <option value="debit">Debit</option>
                                                            <option value="credit">Credit</option>
                                                        </select>
                                                    </div>

                                                    <!-- Category Filter -->
                                                    <div class="col-md-3">
                                                        <select class="form-select filter-category"
                                                            id="clientCategoryFilter<?= $i ?>"
                                                            onchange="filterClientTransactions(this, <?= $i ?>)">
                                                            <option value="">All Categories</option>
                                                            <?php foreach (array_unique(array_column($client['transactions'], 'category')) as $cat): ?>
                                                                <option value="<?= ucwords(str_replace("_", " ", $cat)) ?>">
                                                                    <?= ucwords(str_replace("_", " ", $cat)) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                </div>
                                                <div class="table-responsive rounded-4">
                                                    <table class="table table-striped client-transactions"
                                                        id="clientTransactionsTable<?= $i ?>">
                                                        <thead>
                                                            <tr>
                                                                <th style="background: #c6d2ff !important; color:#000;">Txn
                                                                    ID</th>
                                                                <th style="background: #c6d2ff !important; color:#000;">
                                                                    Service ID & Date</th>
                                                                <th style="background: #c6d2ff !important; color:#000;">
                                                                    Category & Action</th>
                                                                <th style="background: #c6d2ff !important; color:#000;">
                                                                    Service Balance</th>
                                                                <th style="background: #c6d2ff !important; color:#000;">
                                                                    Debit</th>
                                                                <th style="background: #c6d2ff !important; color:#000;">
                                                                    Credit</th>
                                                                <th style="background: #c6d2ff !important; color:#000;">
                                                                    Wallet Balance</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $refundUsed = 0;
                                                            $paidExtra = 0;
                                                            foreach ($client['transactions'] as $row):
                                                                if ($row['category'] == 'refund_amount_used')
                                                                    continue;
                                                                if ($row['category'] == 'booking_amount')
                                                                    $row['credit'] = 0;
                                                                ?>
                                                                <tr data-date="<?= esc($row['date']) ?>"
                                                                    data-service-id="<?= esc($row['service_id']) ?>"
                                                                    data-category="<?= esc($row['category']) ?>"
                                                                    data-type="<?= $row['debit'] > 0 ? 'debit' : 'credit' ?>"
                                                                    data-txn-id="<?= esc($row['txn_id']) ?>">
                                                                    <td class="py-2 px-3"><?= esc($row['txn_id']) ?></td>
                                                                    <td class="py-2 px-3">
                                                                        <div
                                                                            class="d-flex flex-column align-items-center gap-2">
                                                                            <div
                                                                                class="d-flex align-items-center text-dark small">
                                                                                <?= esc($row['service_id']) ?>
                                                                            </div>
                                                                            <span
                                                                                class="badge rounded-pill p-2 px-3 border-0 d-flex align-items-center gap-1"
                                                                                style="background-color: rgba(59, 130, 246, 0.1) !important; border: 1px solid rgba(59, 130, 246, 0.2) !important; color: #2563eb !important;">
                                                                                <i class="bi bi-calendar me-1"></i>
                                                                                <?= esc($row['date']) ?>
                                                                            </span>
                                                                        </div>
                                                                    </td>
                                                                    <td class="py-2 px-3">
                                                                        <div class="d-flex flex-column gap-2">
                                                                            <?php
                                                                            $action = esc($row['action']);
                                                                            $isRefund = stripos($action, 'refund') !== false;
                                                                            $bgColor = $isRefund ? 'rgba(239, 68, 68, 0.1)' : 'rgba(34, 197, 94, 0.1)';
                                                                            $textColor = $isRefund ? '#dc2626' : '#16a34a';
                                                                            $borderColor = $isRefund ? 'rgba(239, 68, 68, 0.2)' : 'rgba(34, 197, 94, 0.2)';
                                                                            ?>
                                                                            <div
                                                                                class="d-flex align-items-center text-dark small">
                                                                                <span
                                                                                    class="badge rounded-pill p-2 px-3 border-0 d-flex align-items-center gap-1"
                                                                                    style="background: <?= $bgColor ?> !important; color: <?= $textColor ?> !important; border: 1px solid <?= $borderColor ?> !important; font-weight: 600; font-size: 0.8rem;">
                                                                                    <?= str_replace("_", " ", esc($row['category'])) ?>
                                                                                </span>
                                                                            </div>
                                                                            <span>
                                                                                <?= esc($row['action']) ?>
                                                                            </span>
                                                                        </div>
                                                                    </td>
                                                                    <td class="py-2 px-3">₹ <?= $row['balance'] ?></td>
                                                                    <td class="py-2 px-3">₹ <?= $row['debit'] ?></td>
                                                                    <td class="py-2 px-3">₹ <?= $row['credit'] ?></td>
                                                                    <td class="py-2 px-3">₹ <?= $row['refundBalance'] ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- Pagination Controls -->
                                                <div class="d-flex justify-content-between align-items-center mt-3"
                                                    id="clientPagination<?= $i ?>">
                                                    <div>
                                                        <span class="text-muted small" id="clientPageInfo<?= $i ?>"></span>
                                                    </div>
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-sm btn-outline-primary"
                                                            onclick="changeClientPage(<?= $i ?>, 'prev')"
                                                            id="clientPrevBtn<?= $i ?>">
                                                            <i class="bi bi-chevron-left"></i> Previous
                                                        </button>
                                                        <span class="btn btn-sm btn-light"
                                                            id="clientCurrentPage<?= $i ?>">1</span>
                                                        <button class="btn btn-sm btn-outline-primary"
                                                            onclick="changeClientPage(<?= $i ?>, 'next')"
                                                            id="clientNextBtn<?= $i ?>">
                                                            Next <i class="bi bi-chevron-right"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $i++; endforeach; ?>
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
                                <?php $j = 1;
                                foreach ($walkers as $walkerId => $walker):
                                    $totalServiceBalance = end($walker['transactions'])['spbalance_after'] ?? 0;
                                    $totalEarnedBalance = end($walker['transactions'])['earned_balance'] ?? $walker['transactions'][0]['earned_amount'] ?? 0;
                                    ?>
                                    <div class="accordion-item walker-accordion"
                                        data-walker-name="<?= strtolower(esc($walker['walker_name'])) ?>"
                                        data-walker-id="<?= esc($walkerId) ?>">
                                        <h2 class="accordion-header" id="walkerHeading<?= $j ?>">
                                            <button class="accordion-button collapsed py-3 px-4 bg-white" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#walkerCollapse<?= $j ?>"
                                                style="box-shadow: none;">

                                                <div class="d-flex justify-content-between align-items-center w-100">

                                                    <!-- Left Side -->
                                                    <div class="d-flex align-items-center gap-3">

                                                        <strong class="text-dark fs-6">
                                                            <?= esc($walker['walker_name']) ?>
                                                        </strong>

                                                        <span class="badge rounded-pill px-3 py-2" style="background: #f1f5f9;
                       color: #475569;
                       font-weight: 500;">
                                                            ID: <?= esc($walkerId) ?>
                                                        </span>

                                                    </div>

                                                    <!-- Right Side -->
                                                    <div class="d-flex gap-3 align-items-center">

                                                        <!-- Service Balance -->
                                                        <span class="badge rounded-pill px-3 py-2" style="background: rgba(37, 99, 235, 0.08);
                       color: #2563eb;
                       border: 1px solid rgba(37, 99, 235, 0.2);
                       font-weight: 600;">
                                                            Service Bal: ₹<?= number_format($totalServiceBalance) ?>
                                                        </span>

                                                        <!-- Earned Balance -->
                                                        <span class="badge rounded-pill px-3 py-2" style="background: rgba(16, 185, 129, 0.08);
                       color: #059669;
                       border: 1px solid rgba(16, 185, 129, 0.2);
                       font-weight: 600;">
                                                            Earned: ₹<?= number_format($totalEarnedBalance) ?>
                                                        </span>

                                                    </div>

                                                </div>
                                            </button>
                                        </h2>
                                        <div id="walkerCollapse<?= $j ?>" class="accordion-collapse collapse"
                                            data-bs-parent="#walkerAccordion">
                                            <div class="accordion-body p-3">
                                                <div class="row align-items-center g-3 mb-3">

                                                    <!-- Search -->
                                                    <div class="col-md-6">
                                                        <input type="text" id="walkerTransactionSearch<?= $j ?>"
                                                            class="form-control"
                                                            placeholder="Search transactions by Service ID..."
                                                            onkeyup="filterWalkerTransactions(this, <?= $j ?>)">
                                                    </div>

                                                    <!-- Type Filter -->
                                                    <div class="col-md-3">
                                                        <select class="form-select filter-type"
                                                            id="walkerTypeFilter<?= $j ?>"
                                                            onchange="filterWalkerTransactions(this, <?= $j ?>)">
                                                            <option value="">All Types</option>
                                                            <option value="debit">Debit</option>
                                                            <option value="credit">Credit</option>
                                                        </select>
                                                    </div>

                                                    <!-- Category Filter -->
                                                    <div class="col-md-3">
                                                        <select class="form-select filter-category"
                                                            id="walkerCategoryFilter<?= $j ?>"
                                                            onchange="filterWalkerTransactions(this, <?= $j ?>)">
                                                            <option value="">All Categories</option>
                                                            <?php foreach (array_unique(array_column($walker['transactions'], 'category')) as $cat): ?>
                                                                <option value="<?= ucwords(str_replace("_", " ", $cat)) ?>">
                                                                    <?= ucwords(str_replace("_", " ", $cat)) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                </div>
                                                <div class="table-responsive rounded-4">
                                                    <table class="table table-striped walker-transactions"
                                                        id="walkerTransactionsTable<?= $j ?>">
                                                        <thead>
                                                            <tr>
                                                                <th style="background: #c6d2ff !important; color:#000;">Txn
                                                                    ID</th>
                                                                <th style="background: #c6d2ff !important; color:#000;">
                                                                    Service ID & Date</th>
                                                                <th style="background: #c6d2ff !important; color:#000;">
                                                                    Category & Action</th>
                                                                <th style="background: #c6d2ff !important; color:#000;">
                                                                    Service Balance</th>
                                                                <th style="background: #c6d2ff !important; color:#000;">
                                                                    Debit</th>
                                                                <th style="background: #c6d2ff !important; color:#000;">
                                                                    Credit</th>
                                                                <th style="background: #c6d2ff !important; color:#000;">
                                                                    Earned Balance</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($walker['transactions'] as $row):
                                                                if ($row['category'] == 'booking_amount')
                                                                    $row['credit'] = 0;
                                                                ?>
                                                                <tr data-date="<?= esc($row['date']) ?>"
                                                                    data-service-id="<?= esc($row['service_id']) ?>"
                                                                    data-category="<?= esc($row['category']) ?>"
                                                                    data-type="<?= $row['debit'] > 0 ? 'debit' : 'credit' ?>"
                                                                    data-txn-id="<?= esc($row['txn_id']) ?>">
                                                                    <td class="py-2 px-3"><?= esc($row['txn_id']) ?></td>
                                                                    <td class="py-2 px-3">
                                                                        <div
                                                                            class="d-flex flex-column align-items-center gap-2">
                                                                            <div
                                                                                class="d-flex align-items-center text-dark small fw-bold">
                                                                                <?= esc($row['service_id']) ?>
                                                                            </div>
                                                                            <span
                                                                                class="badge rounded-pill p-2 px-3 border-0 d-flex align-items-center gap-1"
                                                                                style="background-color: rgba(59, 130, 246, 0.1) !important; border: 1px solid rgba(59, 130, 246, 0.2) !important; color: #2563eb !important;">
                                                                                <i class="bi bi-calendar me-1"></i>
                                                                                <?= esc($row['date']) ?>
                                                                            </span>
                                                                        </div>
                                                                    </td>
                                                                    <td class="py-2 px-3">
                                                                        <div class="d-flex flex-column gap-2">
                                                                            <?php
                                                                            $action = esc($row['action']);
                                                                            $isRefund = stripos($action, 'refund') !== false;
                                                                            $bgColor = $isRefund ? 'rgba(239, 68, 68, 0.1)' : 'rgba(34, 197, 94, 0.1)';
                                                                            $textColor = $isRefund ? '#dc2626' : '#16a34a';
                                                                            $borderColor = $isRefund ? 'rgba(239, 68, 68, 0.2)' : 'rgba(34, 197, 94, 0.2)';
                                                                            ?>
                                                                            <div
                                                                                class="d-flex align-items-center text-dark small">
                                                                                <span
                                                                                    class="badge rounded-pill p-2 px-3 border-0 d-flex align-items-center gap-1"
                                                                                    style="background: <?= $bgColor ?> !important; color: <?= $textColor ?> !important; border: 1px solid <?= $borderColor ?> !important; font-weight: 600; font-size: 0.8rem;">
                                                                                    <?= str_replace("_", " ", esc($row['category'])) ?>
                                                                                </span>
                                                                            </div>
                                                                            <span>
                                                                                <?= esc($row['action']) ?>
                                                                            </span>
                                                                        </div>
                                                                    </td>
                                                                    <td class="py-2 px-3">₹ <?= $row['spbalance_after'] ?></td>
                                                                    <td class="py-2 px-3">₹ <?= $row['debit'] ?></td>
                                                                    <td class="py-2 px-3">₹ <?= $row['credit'] ?></td>
                                                                    <td class="py-2 px-3"> ₹ <?= $row['earned_balance'] ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- Pagination Controls -->
                                                <div class="d-flex justify-content-between align-items-center mt-3"
                                                    id="walkerPagination<?= $j ?>">
                                                    <div>
                                                        <span class="text-muted small" id="walkerPageInfo<?= $j ?>"></span>
                                                    </div>
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-sm btn-outline-primary"
                                                            onclick="changeWalkerPage(<?= $j ?>, 'prev')"
                                                            id="walkerPrevBtn<?= $j ?>">
                                                            <i class="bi bi-chevron-left"></i> Previous
                                                        </button>
                                                        <span class="btn btn-sm btn-light"
                                                            id="walkerCurrentPage<?= $j ?>">1</span>
                                                        <button class="btn btn-sm btn-outline-primary"
                                                            onclick="changeWalkerPage(<?= $j ?>, 'next')"
                                                            id="walkerNextBtn<?= $j ?>">
                                                            Next <i class="bi bi-chevron-right"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $j++; endforeach; ?>
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
                                <table class="table table-striped table-hover align-middle mb-0"
                                    id="platformWalletTable">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="background: #c6d2ff !important; color:#000;" class="p-3">Txn ID
                                            </th>
                                            <th style="background: #c6d2ff !important; color:#000;" class="p-3">Service
                                                ID</th>
                                            <th style="background: #c6d2ff !important; color:#000;" class="p-3">Date
                                            </th>
                                            <th style="background: #c6d2ff !important; color:#000;" class="p-3">Action
                                            </th>
                                            <th style="background: #c6d2ff !important; color:#000;" class="p-3">Amount
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($platform_transactions as $row): ?>
                                            <tr data-date="<?= esc($row['date']) ?>"
                                                data-service-id="<?= esc($row['service_id']) ?>"
                                                data-txn-id="<?= esc($row['txn_id']) ?>">
                                                <td class="p-3"><?= esc($row['txn_id']) ?></td>
                                                <td class="p-3"><span
                                                        class="badge rounded-pill p-2 px-3 border-0 d-flex align-items-center gap-1"
                                                        style="background-color: rgba(59, 130, 246, 0.1) !important; border: 1px solid rgba(59, 130, 246, 0.2) !important; color: #2563eb !important; width: max-content;">
                                                        <i class="bi bi-calendar me-1"></i>
                                                        <?= esc($row['service_id']) ?>
                                                    </span></td>
                                                <td class="p-3"><?= esc($row['date']) ?></td>
                                                <td class="p-3"><?= esc($row['action']) ?></td>
                                                <td class="p-3"> <span class="badge bg-success py-2 rounded-5"
                                                        style="background: rgba(34, 197, 94, 0.1) !important; color: #16a34a ; border: 1px solid rgba(34, 197, 94, 0.2) !important; font-weight: 600;">₹
                                                        <?= $row['amount'] ?>
                                                    </span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php if (empty($platform_transactions)): ?>
                                            <tr>
                                                <td colspan="5" class="p-3 text-center text-muted">No platform wallet
                                                    transactions found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
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
                            <table class="table table-striped table-hover align-middle mb-0" id="serviceTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="background: #c6d2ff !important; color:#000;" class="p-3">Service ID
                                        </th>
                                        <th style="background: #c6d2ff !important; color:#000;" class="p-3">Client</th>
                                        <th style="background: #c6d2ff !important; color:#000;" class="p-3">Service
                                            Provider</th>
                                        <th style="background: #c6d2ff !important; color:#000;" class="p-3">Booking Amt
                                        </th>
                                        <th style="background: #c6d2ff !important; color:#000;" class="p-3">Refunds</th>
                                        <th class="p-3"
                                            style="max-width: 80px !important; background: #c6d2ff !important; color:#000;">
                                            Provider</th>
                                        <th class="p-3"
                                            style="max-width: 80px !important; background: #c6d2ff !important; color:#000;">
                                            Platform</th>
                                        <th style="background: #c6d2ff !important; color:#000;" class="p-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700">
                                    <?php foreach ($summary as $row): ?>
                                        <tr data-date="<?= esc($row['date'] ?? '') ?>"
                                            data-status="<?= esc($row['status']) ?>"
                                            data-service-id="<?= esc($row['booking_id']) ?>"
                                            data-client-name="<?= strtolower(esc($row['client_name'])) ?>"
                                            data-walker-name="<?= strtolower(esc($row['walker_name'])) ?>">
                                            <td class="p-3 fw-bold"><?= esc($row['booking_id']) ?></td>
                                            <td class="p-3"><?= esc($row['client_name']) ?></td>
                                            <td class="p-3"><?= esc($row['walker_name']) ?></td>
                                            <td class="p-3">
                                                <span
                                                    class="badge text-primary fw-bold rounded-pill p-2 border-primary border"
                                                    style="background-color: rgba(59, 130, 246, 0.1) !important; color: #2563eb !important; border: 1px solid rgba(59, 130, 246, 0.2) !important; border-radius: 50px ;">₹
                                                    <?= number_format($row['booking_amt'], 2) ?></span>
                                            </td>
                                            <td class="p-3">
                                                <span
                                                    class="badge text-primary fw-bold rounded-pill p-2 border-primary border"
                                                    style="background-color: rgba(59, 130, 246, 0.1) !important; color: #2563eb !important; border: 1px solid rgba(59, 130, 246, 0.2) !important; border-radius: 50px ;">₹
                                                    <?php if ($row['refunds'] > 0): ?>
                                                        <?= number_format($row['refunds'], 2) ?>
                                                    <?php else: ?>
                                                        –
                                                    <?php endif; ?></span>
                                            </td>
                                            <td class="p-3">
                                                <span
                                                    class="badge text-primary fw-bold rounded-pill p-2 border-primary border"
                                                    style="background-color: rgba(251, 191, 36, 0.1) !important; color: #d97706 !important; border: 1px solid rgba(251, 191, 36, 0.2) !important; border-radius: 50px ;">₹
                                                    <?= number_format($row['walker_payouts'], 2) ?></span>
                                            </td>
                                            <td class="p-3"> <span
                                                    class="badge text-primary fw-bold rounded-pill p-2 border-primary border"
                                                    style="background: rgba(34, 197, 94, 0.1) !important; color: #16a34a !important; border: 1px solid rgba(34, 197, 94, 0.2) !important; border-radius: 50px ;">₹
                                                    <?= number_format($row['pf_earnings'], 2) ?></span></td>
                                            <td class="p-3">
                                                <?php if ($row['status'] == 'Balanced'): ?>
                                                    <span class="badge status-badge rounded-pill bg-success">Balanced</span>
                                                <?php elseif ($row['status'] == 'Mismatch'): ?>
                                                    <span class="badge status-badge rounded-pill bg-danger">Mismatch</span>
                                                <?php else: ?>
                                                    <span class="badge status-badge rounded-pill bg-warning">Pending</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($summary)): ?>
                                        <tr>
                                            <td colspan="8" class="p-3 text-center text-muted">No records found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
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
                            <table class="table table-striped table-hover align-middle mb-0" id="mismatchTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="background: #c6d2ff !important; color:#000;" class="p-3">Service ID
                                        </th>
                                        <th style="background: #c6d2ff !important; color:#000;" class="p-3">User Type
                                        </th>
                                        <th style="background: #c6d2ff !important; color:#000;" class="p-3">User Name
                                        </th>
                                        <th style="background: #c6d2ff !important; color:#000;" class="p-3">Expected
                                            Amount</th>
                                        <th style="background: #c6d2ff !important; color:#000;" class="p-3">Current
                                            Amount</th>
                                        <th style="background: #c6d2ff !important; color:#000;" class="p-3">Difference
                                        </th>
                                        <th style="background: #c6d2ff !important; color:#000;" class="p-3">Date</th>
                                        <th style="background: #c6d2ff !important; color:#000;" class="p-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($mismatch_alerts as $row): ?>
                                        <tr data-date="<?= esc($row['date']) ?>"
                                            data-user-type="<?= strtolower(esc($row['user_type'])) ?>"
                                            data-status="<?= esc($row['status']) ?>"
                                            data-service-id="<?= esc($row['service_id']) ?? '' ?>"
                                            data-user-name="<?= strtolower(esc($row['user_name'])) ?? '' ?>"
                                            class="mismatch-row">
                                            <td class="p-3"><?= esc($row['service_id']) ?? 0 ?></td>
                                            <td class="p-3">
                                                <?= esc($row['user_type']) ?>
                                            </td>
                                            <td class="p-3"><?= esc($row['user_name']) ?? 0 ?></td>
                                            <td class="p-3">₹ <?= number_format($row['expected_amount'], 2) ?></td>
                                            <td class="p-3">₹ <?= number_format($row['actual_amount'], 2) ?></td>
                                            <td
                                                class="p-3 font-weight-bold <?= $row['difference'] > 0 ? 'text-danger' : 'text-success' ?>">
                                                ₹ <?= number_format(abs($row['difference']), 2) ?>
                                            </td>
                                            <td class="p-3"><?= esc($row['date']) ?></td>
                                            <td class="p-3">
                                                <span
                                                    class="badge status-badge rounded-pill <?= $row['status'] == 'Mismatch' ? 'bg-danger' : 'bg-success' ?>">
                                                    <?= esc($row['status']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($mismatch_alerts)): ?>
                                        <tr>
                                            <td colspan="8" class="p-3 text-center text-muted">No mismatch alerts found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
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
</style>

<?php echo $this->include('admin/footer') ?>

<script>
    let currentMainTab = 'wallet';

    document.addEventListener('DOMContentLoaded', function () {
        showTab('wallet'); // Set Wallet View as default
        document.getElementById('userTypeFilter').value = 'client'; // Set Client as default in dropdown
        filterByUserType(); // Apply initial filter

        // Initialize pagination for all client and walker tables
        initializeAllPagination();
    });

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
            } else if (selectedType === 'service_provider') {
                document.getElementById('serviceProviderWalletSection').style.display = 'block';
            } else if (selectedType === 'platform') {
                document.getElementById('platformWalletSection').style.display = 'block';
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

        // Reset user type filter to default 'client'
        document.getElementById('userTypeFilter').value = 'client';

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