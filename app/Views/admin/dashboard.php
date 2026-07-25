<?php echo $this->extend('admin/includes/header'); ?>
<!--start content-->

<?php echo $this->section('content'); ?>

<main class="main-content">
    <div class="position-relative iq-banner">
        <?php echo $this->include('admin/nav_bar') ?>
    </div>

    <div class="container-fluid px-4 content-inner pb-0" id="page_layout">
        <div class="row mb-3">
            <div class="col-12">
                <div class="dashboard-header-gradient rounded-3">
                    <div class="card border-0 overflow-hidden rounded-3 glass-card mb-0">
                        <div class="card-body p-3 position-relative text-white">
                            <div class="row align-items-center">
                                <div class="col-lg-8 mb-4 mb-lg-0">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="glass-pill rounded-circle p-2 me-3 d-flex align-items-center justify-content-center"
                                            style="width: 50px; height: 50px;">
                                            <i class="bi bi-speedometer2 fs-3"></i>
                                        </div>
                                        <div>
                                            <h3 class="fw-bold mb-2 text-white">Dashboard Analytics</h3>
                                            <p class="text-white-75 mb-0">
                                                Welcome back! Here's your business overview
                                            </p>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap gap-3">
                                        <div class="d-flex align-items-center glass-pill rounded-3 px-3 py-2">
                                            <i class="bi bi-calendar-check me-2"></i>
                                            <span><?= date('F d, Y') ?></span>
                                        </div>

                                        <div class="d-flex align-items-center glass-pill rounded-3 px-3 py-2">
                                            <i class="bi bi-currency-rupee me-2"></i>
                                            <span>₹<?= number_format($profit ?? 0) ?> Net Profit</span>
                                        </div>

                                        <div class="d-flex align-items-center glass-pill rounded-3 px-3 py-2">
                                            <i class="bi bi-people me-2"></i>
                                            <span><?= $active_customers_count ?> Active Customers</span>
                                        </div>

                                        <div class="d-flex align-items-center glass-pill rounded-3 px-3 py-2"
                                            title="Cashfree Payout Balance">
                                            <i class="bi bi-wallet2 me-2"></i>
                                            <span>₹<?= number_format($payout_balance['available'] ?? 0, 2) ?> Payout
                                                Balance</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 text-lg-end">
                                    <div class="d-flex flex-column gap-3">
                                        <div class="glass-pill rounded-3 p-3 d-inline-block">
                                            <h4 class="mb-0 fw-bold text-white"><?= date('M Y') ?></h4>
                                            <small class="text-white-75">Current Period</small>
                                        </div>

                                        <button class="btn glass-btn fw-semibold text-black" onclick="generateReport()">
                                            <i class="bi bi-download me-2"></i>Export Report
                                        </button>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Quick Stats Tabs -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="glass-card rounded-5">
                    <div class="card mb-0 rounded-5 border">
                        <div class="card-body p-2 rounded-5 border" style="border-color: #6e90ff !important;">
                            <div class="d-flex flex-wrap gap-5 justify-content-center">
                                <button class="dashboard-tab active" onclick="switchTab('overview')">
                                    <i class="bi bi-speedometer2 me-2"></i>Quick Actions
                                </button>
                                <button class="dashboard-tab" onclick="switchTab('reports')">
                                    <i class="bi bi-bar-chart me-2"></i>Reports
                                </button>
                                <button class="dashboard-tab" onclick="switchTab('financials')">
                                    <i class="bi bi-cash-coin me-2"></i>Financials
                                </button>
                                <button class="dashboard-tab" onclick="switchTab('providers')">
                                    <i class="bi bi-people me-2"></i>Providers
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="overview-tab" class="tab-content active">
            <div class="row mb-4">
                <div class="col-6">
                    <div class="card border-0 shadow-sm rounded-3 mb-0">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center border-bottom border-light">
                                <div>
                                    <h6 class="fw-bold mb-1">Support chat</h6>
                                    <small class="text-muted">Latest Support Messages</small>
                                </div>
                                <!-- <div>
                                    <a href="<?= base_url('/bookingRequests') ?>" class="btn bg-primary text-white">
                                        <i class="bi bi-eye me-2"></i>View Jobs
                                        <i class="bi bi-chevron-right ms-2"></i>
                                    </a>
                                </div> -->
                            </div>
                            <div class="row mt-2">
                                <div class="col-12 mb-3">
                                    <div class="d-flex align-items-center justify-content-between glass-pill rounded-3 px-3 py-2 "
                                        style="background: rgba(16, 185, 129, 0.1);">
                                        <div class="d-flex align-items-center  gap-3">
                                            <div class="stat-icon-wrapper me-2"
                                                style="width: 36px; height: 36px; background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                                <i class="bi bi-chat-dots text-white"></i>
                                            </div>
                                            <div class="text-start">
                                                <span class="fw-bold d-block"
                                                    style="color: #10b981; font-size: 30px;"><?= $providers->client_supports ?>
                                                    <small class=""
                                                        style="font-size: 0.9rem;">Client</small></span>
                                                <small class="d-block text-success" style="font-size: 0.8rem;">Open
                                                    Tickets</small>
                                            </div>
                                        </div>
                                        <div>
                                            <a href="<?= base_url('/support') ?>" class="text-success">
                                                View Messages</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-center justify-content-between glass-pill rounded-3 px-3 py-2 "
                                        style="background: rgba(245, 158, 11, 0.1);">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="stat-icon-wrapper me-2"
                                                style="width: 36px; height: 36px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                                                <i class="bi bi-chat-text text-white"></i>
                                            </div>
                                            <div class="text-start">
                                                <span class="fw-bold d-block"
                                                    style="color: #f59e0b; font-size: 30px;"><?= $providers->sp_supports ?>
                                                    <small class=""
                                                        style="font-size: 0.9rem;">Service
                                                        Provider</small></span>
                                                <small class="d-block text-warning" style="font-size: 0.8rem;">
                                                    Open Tickets</small>
                                            </div>
                                        </div>
                                        <div>
                                            <a href="<?= base_url('/sp-support') ?>" class="text-warning">
                                                View Messages</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-0 shadow-sm rounded-3 mb-0">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center border-bottom border-light">
                                <div>
                                    <h6 class="fw-bold mb-1">Balance</h6>
                                    <small class="text-muted ">SMS & CAHSFREE Remaining Balance</small>
                                </div>

                            </div>
                            <div class="row mt-2">
                                <div class="col-6">
                                    <div
                                        class="card  border-0 h-100 rounded-3 mb-0 <?= ($sms_credits['remaining'] <= 50) ? 'pulse-low' : '' ?>">
                                        <div class="card-body p-3 position-relative">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="text-muted mb-2">SMS Credits</h6>
                                                    <h3 class="fw-bold mb-3" style="color: #8b5cf6;">
                                                        <?= number_format($sms_credits['remaining']) ?>
                                                    </h3>
                                                    <div class="d-flex align-items-center">
                                                        <span
                                                            class="badge-premium <?= ($sms_credits['remaining'] <= 50) ? 'bg-danger bg-opacity-10 text-danger' : 'bg-success bg-opacity-10 text-white' ?> me-2">
                                                            <?= ($sms_credits['remaining'] <= 50) ? 'Low' : 'Good' ?>
                                                        </span>
                                                        <small class="text-muted">
                                                            <?php 
                                                            $validity = $sms_credits['validity'] ?? 'Unlimited';
                                                            $timestamp = strtotime($validity);
                                                            if ($timestamp && $validity != 'Lifetime' && $validity != 'Unlimited') {
                                                                echo 'Valid till ' . date('d M Y', $timestamp);
                                                            } else {
                                                                echo 'Validity: ' . $validity;
                                                            }
                                                            ?>
                                                        </small>
                                                    </div>
                                                </div>
                                                <div
                                                    class="stat-icon-wrapper <?= ($sms_credits['remaining'] <= 50) ? 'dashboard-gradient-red' : 'dashboard-gradient-purple' ?> text-white">
                                                    <i class="bi bi-chat-text"></i>
                                                </div>
                                            </div>
                                            <div class="progress-premium mt-4">
                                                <div class="progress-bar <?= ($sms_credits['remaining'] <= 50) ? 'dashboard-gradient-red' : 'dashboard-gradient-purple' ?>"
                                                    style="width: <?= min(($sms_credits['remaining'] / 100) * 100, 100) ?>%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div
                                        class="card border-0  h-100 rounded-3 mb-0 <?= ($payout_balance['available'] <= 1000) ? 'pulse-low' : '' ?>">
                                        <div class="card-body p-3 position-relative">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="text-muted mb-2">Payout Balance</h6>
                                                    <h3 class="fw-bold mb-3" style="color: #10b981;">
                                                        ₹<?= number_format($payout_balance['available'] ?? 0) ?>
                                                    </h3>
                                                    <div class="d-flex align-items-center">
                                                        <span
                                                            class="badge-premium <?= ($payout_balance['available'] <= 1000) ? 'bg-danger bg-opacity-10 text-danger' : 'bg-success bg-opacity-10 text-white' ?> me-2">
                                                            <?= ($payout_balance['available'] <= 1000) ? 'Low' : 'Good' ?>
                                                        </span>
                                                        <small class="text-muted">Available</small>
                                                    </div>
                                                </div>
                                                <div
                                                    class="stat-icon-wrapper <?= ($payout_balance['available'] <= 1000) ? 'dashboard-gradient-red' : 'dashboard-gradient-green' ?> text-white">
                                                    <i class="bi bi-wallet2"></i>
                                                </div>
                                            </div>
                                            <div class="progress-premium mt-4">
                                                <div class="progress-bar <?= ($payout_balance['available'] <= 1000) ? 'dashboard-gradient-red' : 'dashboard-gradient-green' ?>"
                                                    style="width: 100%">
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
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <div class="card-header p-0 pb-2">
                        <h6 class="fw-bold mb-1">Services OverView</h6>
                    </div>
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card border border-light h-100 rounded-3 mb-0">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="fw-bold mb-0 text-uppercase small text-muted">Jobs Requiring SP
                                        </h6>
                                        <span class="badge bg-primary bg-opacity-10 text-white rounded-circle p-2">
                                            <i class="bi bi-briefcase"></i>
                                        </span>
                                    </div>

                                    <div class="d-flex align-items-baseline mb-2">
                                        <span class="display-6 fw-bold"><?= $noBidsCount['total'] ?? 0 ?></span>
                                        <span class="text-muted ms-2">Total</span>
                                    </div>

                                    <canvas id="jobsRequiringSPChart" style="height: 120px; width: 100%;"></canvas>

                                    <div class="mt-2 small">
                                        <?php
                                        $totalBids = max($noBidsCount['total'] ?? 0, 1);
                                        $walkingBidsPerc = round(($noBidsCount['walking'] / $totalBids) * 100);
                                        $groomingBidsPerc = round(($noBidsCount['grooming'] / $totalBids) * 100);
                                        $trainingBidsPerc = round(($noBidsCount['training'] / $totalBids) * 100);
                                        ?>
                                        <div class="d-flex justify-content-between">
                                            <span>Walking: <?= $noBidsCount['walking'] ?? 0 ?></span>
                                            <span class="text-primary"><?= $walkingBidsPerc ?>%</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Grooming: <?= $noBidsCount['grooming'] ?? 0 ?></span>
                                            <span class="text-primary"><?= $groomingBidsPerc ?>%</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Training: <?= $noBidsCount['training'] ?? 0 ?></span>
                                            <span class="text-primary"><?= $trainingBidsPerc ?>%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-top-0 pt-0 pb-3 px-3">
                                    <a href="<?= base_url('/bookingRequests') ?>"
                                        class="btn btn-sm btn-outline-primary w-100">
                                        <i class="bi bi-eye me-2"></i>View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card border border-light h-100 rounded-3 mb-0">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="fw-bold mb-0 text-uppercase small text-muted">Jobs About to Hold
                                        </h6>
                                        <span class="badge bg-info bg-opacity-10 text-white rounded-circle p-2">
                                            <i class="bi bi-clock"></i>
                                        </span>
                                    </div>

                                    <div class="d-flex align-items-baseline mb-2">
                                        <span class="display-6 fw-bold"><?= $haltedBookingsCount['total'] ?? 0 ?></span>
                                        <span class="text-muted ms-2">Total</span>
                                    </div>

                                    <!-- Mini Chart -->
                                    <canvas id="jobsAboutToHoldChart" style="height: 120px; width: 100%;"></canvas>

                                    <!-- Stats -->
                                    <div class="mt-2 small">
                                        <?php
                                        $totalHalted = max($haltedBookingsCount['total'] ?? 0, 1);
                                        $walkingHaltedPerc = round(($haltedBookingsCount['walking'] / $totalHalted) * 100);
                                        $groomingHaltedPerc = round(($haltedBookingsCount['grooming'] / $totalHalted) * 100);
                                        $trainingHaltedPerc = round(($haltedBookingsCount['training'] / $totalHalted) * 100);
                                        ?>
                                        <div class="d-flex justify-content-between">
                                            <span>Walking: <?= $haltedBookingsCount['walking'] ?? 0 ?></span>
                                            <span class="text-info"><?= $walkingHaltedPerc ?>%</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Grooming: <?= $haltedBookingsCount['grooming'] ?? 0 ?></span>
                                            <span class="text-info"><?= $groomingHaltedPerc ?>%</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Training: <?= $haltedBookingsCount['training'] ?? 0 ?></span>
                                            <span class="text-info"><?= $trainingHaltedPerc ?>%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-top-0 pt-0 pb-3 px-3">
                                    <a href="<?= base_url('/bookings/halted') ?>" class="btn btn-sm btn-outline-primary w-100">
                                        <i class="bi bi-eye me-2"></i>View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="row g-3 ">
                                <div class="col-12">
                                    <div class="card border border-light  rounded-3 mb-0">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="fw-bold mb-0 text-uppercase small text-muted">No Shows Today
                                                </h6>
                                                <span
                                                    class="badge bg-danger bg-opacity-10 text-danger rounded-circle p-2">
                                                    <i class="bi bi-exclamation-triangle"></i>
                                                </span>
                                            </div>

                                            <div class="d-flex align-items-baseline mb-2">
                                                <span
                                                    class="display-6 fw-bold"><?= $noShowsCount['total'] ?? 0 ?></span>
                                                <span class="text-muted ms-2">Total</span>
                                            </div>

                                            <!-- Mini Chart -->
                                            <canvas id="noShowsChart" style="height: 80px; width: 100%;"></canvas>

                                            <!-- Stats -->
                                            <div class="mt-2 small">
                                                <?php
                                                $totalNoShows = max($noShowsCount['total'] ?? 0, 1);
                                                $walkingNoShowsPerc = round(($noShowsCount['walking'] / $totalNoShows) * 100);
                                                $groomingNoShowsPerc = round(($noShowsCount['grooming'] / $totalNoShows) * 100);
                                                ?>
                                                <div class="d-flex justify-content-between">
                                                    <span>Walking: <?= $noShowsCount['walking'] ?? 0 ?></span>
                                                    <span class="text-danger"><?= $walkingNoShowsPerc ?>%</span>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <span>Grooming: <?= $noShowsCount['grooming'] ?? 0 ?></span>
                                                    <span class="text-danger"><?= $groomingNoShowsPerc ?>%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-top-0 pt-0 pb-3 px-3">
                                            <a href="<?= base_url('/missed-walks') ?>"
                                                class="btn btn-sm btn-outline-primary w-100">
                                                <i class="bi bi-eye me-2"></i>View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card border border-light h-100 rounded-3 mb-0">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="fw-bold mb-0 text-uppercase small text-muted">Services Ending Soon
                                        </h6>
                                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-circle p-2">
                                            <i class="fas fa-hourglass-half"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-baseline mb-2">
                                        <span
                                            class="display-6 fw-bold"><?= $servicesEndingSoonCount['total'] ?? 0 ?></span>
                                        <span class="text-muted ms-2">Total</span>
                                    </div>

                                    <canvas id="servicesEndingChart" style="height: 120px; width: 100%;"></canvas>
                                    <div class="mt-2 small">
                                        <?php
                                        $totalEnding = max($servicesEndingSoonCount['total'] ?? 0, 1);
                                        $walkingEndingPerc = round(($servicesEndingSoonCount['walking'] / $totalEnding) * 100);
                                        $trainingEndingPerc = round(($servicesEndingSoonCount['training'] / $totalEnding) * 100);
                                        ?>
                                        <div class="d-flex justify-content-between">
                                            <span>Walking: <?= $servicesEndingSoonCount['walking'] ?? 0 ?></span>
                                            <span class="text-warning"><?= $walkingEndingPerc ?>%</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Training: <?= $servicesEndingSoonCount['training'] ?? 0 ?></span>
                                            <span class="text-warning"><?= $trainingEndingPerc ?>%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-top-0 pt-0 pb-3 px-3">
                                    <a href="<?= base_url('/bookings/completed') ?>"
                                        class="btn btn-sm btn-outline-primary w-100">
                                        <i class="bi bi-eye me-2"></i>View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                     
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 mb-0">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-bold mb-0 text-uppercase small text-muted">Insurance Requests vs
                                    Claims</h6>
                                <span class="badge bg-success bg-opacity-10 text-white rounded-circle p-2">
                                    <i class="fas fa-shield-alt"></i>
                                </span>
                            </div>

                            <div class="d-flex align-items-baseline mb-2">
                                <span class="display-6 fw-bold"><?= $insurance_stats['total'] ?></span>
                                <span class="text-muted ms-2">Total</span>
                            </div>

                            <!-- Mini Chart -->
                            <canvas id="insuranceChart" style="height: 80px; width: 100%;"></canvas>

                            <!-- Stats -->
                            <div class="mt-2 small">
                                <?php
                                $totalInsurance = max($insurance_stats['total'], 1);
                                $requestsPerc = round(($insurance_stats['requests'] / $totalInsurance) * 100);
                                $claimsPerc = round(($insurance_stats['claims'] / $totalInsurance) * 100);
                                ?>
                                <div class="d-flex justify-content-between">
                                    <span>Requests: <?= $insurance_stats['requests'] ?></span>
                                    <span class="text-warning"><?= $requestsPerc ?>%</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Claims: <?= $insurance_stats['claims'] ?></span>
                                    <span class="text-success"><?= $claimsPerc ?>%</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top-0 pt-0 pb-3 px-3">
                            <a href="<?= base_url('/enquiries') ?>" class="btn btn-sm btn-outline-primary w-100">
                                <i class="bi bi-eye me-2"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 mb-0">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-bold mb-0 text-uppercase small text-muted">Service Providers</h6>
                                <span class="badge bg-info bg-opacity-10 text-white rounded-circle p-2">
                                    <i class="bi bi-people"></i>
                                </span>
                            </div>

                            <!-- Provider Stats Grid -->
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="bg-light rounded-3 p-2 text-center">
                                        <span class="d-block fw-bold fs-5" style="color: #4f46e5;"><?= $providers->total ?? 0 ?></span>
                                        <small class="text-muted">Total Providers</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded-3 p-2 text-center">
                                        <span class="d-block fw-bold fs-5" style="color: #ef4444;"><?= $providers->reported ?? 0 ?></span>
                                        <small class="text-muted">Got Reported</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded-3 p-2 text-center">
                                        <span class="d-block fw-bold fs-5" style="color: #f59e0b;"><?= $providers->absents ?? 0 ?></span>
                                        <small class="text-muted">Max. Absents</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded-3 p-2 text-center">
                                        <span class="d-block fw-bold fs-5" style="color: #06b6d4;"><?= $providers->lates ?? 0 ?></span>
                                        <small class="text-muted">Max. Lates to Service</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Required Verification -->
                            <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top">
                                <div>
                                    <span class="fw-semibold">Required Verification</span>
                                    <span class="badge bg-warning bg-opacity-10 text-warning ms-2"><?= $providers->pending_verification ?? 0 ?> Pending</span>
                                </div>
                                <a href="<?= base_url('/providers?status=active') ?>"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-check-circle me-1"></i>Verify
                                </a>
                            </div>

                            <!-- Mini Progress for Verification -->
                            <div class="mt-2">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span>Verified</span>
                                    <span>2/4</span>
                                </div>
                                <div class="progress-premium" style="height: 5px;">
                                    <div class="progress-bar bg-success" style="width: 50%"></div>
                                </div>
                            </div>
                        </div>
                         <!-- Verification Executives Performance -->
                            <?php if(isset($verification_executives) && !empty($verification_executives)): ?>
                            <div class="mt-3 pt-3 border-top">
                                <h6 class="fw-bold mb-2 small text-muted text-uppercase">Verification Executives</h6>
                                <div class="table-responsive" style="max-height: 150px; overflow-y: auto;">
                                    <table class="table table-sm table-hover mb-0" style="font-size: 0.8rem;">
                                        <thead>
                                            <tr>
                                           <th class="fw-normal border-0 pb-1">Name</th>
                                                <th class=" fw-normal border-0 pb-1 text-center text-white" title="Call Verifications">Calls</th>
                                                <th class=" fw-normal border-0 pb-1 text-center text-white" title="Manager Approvals">Apprvls</th>
                                                <th class=" fw-normal border-0 pb-1 text-center text-white" title="Total SPs Processed">Total SP</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($verification_executives as $exec): ?>
                                            <tr>
                                                <td class="fw-semibold text-dark border-0 py-1"><?= htmlspecialchars($exec->name ?? '') ?></td>
                                                <td class="text-center text-primary border-0 py-1"><?= $exec->call_verification_count ?></td>
                                                <td class="text-center text-success border-0 py-1"><?= $exec->manager_approval_count ?></td>
                                                <td class="text-center fw-bold text-dark border-0 py-1"><?= $exec->total_sp_count ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <?php endif; ?>
                        <div class="card-footer bg-transparent border-top-0 pt-0 pb-3 px-3">
                            <a href="<?= base_url('/providers?status=active') ?>"
                                class="btn btn-sm btn-outline-primary w-100">
                                <i class="bi bi-eye me-2"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm rounded-3 h-100 mb-2">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon-wrapper me-3 bg-warning" style="width: 48px; height: 48px; ">
                                        <i class="fas fa-user-graduate text-white "></i>
                                    </div>
                                    <div>
                                        <span class="text-muted small d-block">SP Payment Requests</span>
                                        <div class="d-flex align-items-baseline">
                                            <span class="fw-bold fs-3 text-warning"><?= $payout_request ?></span>
                                            <span
                                                class="badge bg-warning bg-opacity-10 text-warning ms-2">Pending</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span>Awaiting Approval</span>
                                        <span><?= $payout_request ?? 0 ?> requests</span>
                                    </div>
                                    <a href="<?= base_url('/providers-payments') ?>"
                                        class="btn btn-sm bg-warning w-100 mt-2 text-white">
                                        <i class="bi bi-eye me-1"></i>View Requests
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm rounded-3 h-100 mb-0">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon-wrapper me-3 bg-success" style="width: 48px; height: 48px; ">
                                        <i class="bi bi-arrow-return-left text-white"></i>
                                    </div>
                                    <div>
                                        <span class="text-muted small d-block">Client Refund Requests</span>
                                        <div class="d-flex align-items-baseline">
                                            <span class="fw-bold fs-3 text-success"><?= $providers->client_refund_requests ?? 0 ?></span>
                                            <span class="badge bg-danger bg-opacity-10 text-success ms-2">Urgent</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span>Needs Attention</span>
                                        <span><?= $providers->client_refund_requests ?? 0 ?> requests</span>
                                    </div>
                                    <a href="<?= base_url('/client-wallets') ?>"
                                        class="btn btn-sm bg-success text-white w-100 mt-2">
                                        <i class="bi bi-eye me-1"></i>View Refunds
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="reports-tab" class="tab-content" style="display: none;">
            <div class="row g-4">
                <div class="col-xl-8">
                    <div class="card glass-card border-0 shadow-sm rounded-3 mb-0" style="background:linear-gradient(135deg, #4361ee 0%, #4895ef 100%)!important;  
                            border: 1px solid rgba(255, 255, 255, 0.25); backdrop-filter: blur(30px) saturate(180%)">
                        <div
                            class="card-header bg-transparent d-flex justify-content-between align-items-center py-4 border-bottom">
                            <div>
                                <h5 class="mb-1 fw-bold text-white">Business Performance Analytics</h5>
                                <small class="text-white">Comprehensive business insights</small>
                            </div>
                            <div class="dropdown">
                                <button class="btn text-white dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                    style="border: 1px solid rgba(255, 255, 255, 0.7) !important; background-color: rgba(139, 183, 255, 0.1) !important;">
                                    Last 30 Days
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Last 7 Days</a></li>
                                    <li><a class="dropdown-item" href="#">Last 30 Days</a></li>
                                    <li><a class="dropdown-item" href="#">Last 90 Days</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="chart-container-premium">
                                <canvas id="analyticsChart" style="height: 320px; width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Most Requested Cities -->
                    <div class="card glass-card border-0 shadow-sm rounded-3 mt-4">
                        <div class="card-header bg-transparent py-4 border-bottom"
                            style="background:linear-gradient(135deg, #10b981 0%, #34d399 100%)!important;">
                            <h5 class="mb-1 fw-bold text-white">Most Requested Cities</h5>
                            <small class="text-white">Service demand by location</small>
                        </div>
                        <div class="card-body p-0 bg-white">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th style="background: #10b981 !important;" class="border-0 text-white">City
                                            </th>
                                            <th style="background: #10b981 !important;"
                                                class="border-0 text-center text-white">Bookings</th>
                                            <th style="background: #10b981 !important;"
                                                class="border-0 text-center text-white">Clients</th>
                                            <th style="background: #10b981 !important;"
                                                class="border-0 text-center text-white">Providers</th>
                                            <th style="background: #10b981 !important;"
                                                class="border-0 text-center text-white">Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($most_requests as $request) { ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-geo-alt me-2" style="color: #4f46e5;"></i>
                                                        <?= $request['city'] ?? 'N/A' ?>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge-premium bg-purple-10 text-purple"><?= $request['total_bookings'] ?? 0 ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge-premium bg-opacity-10 text-white"
                                                        style="background-color: #10b981;"><?= $request['total_clients'] ?? 0 ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge-premium bg-success bg-opacity-10 text-white"><?= $request['total_providers'] ?? 0 ?></span>
                                                </td>
                                                <td class="text-center fw-semibold" style="color: #10b981;">
                                                    ₹<?= number_format($request['revenue'] ?? 0) ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <!-- Customer Reviews -->
                    <div class="card glass-card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-4 border-bottom"
                            style="background:linear-gradient(135deg, #4361ee 0%, #4895ef 100%)!important;  
                            border: 1px solid rgba(255, 255, 255, 0.25); backdrop-filter: blur(30px) saturate(180%)">
                            <div>
                                <h5 class="mb-1 fw-bold text-white">Recent Reviews</h5>
                                <small class="text-white">Customer feedback</small>
                            </div>
                            <a href="#" class="btn btn-link btn-sm text-decoration-none" style="color: #fff;">View
                                All</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush custom-scrollbar"
                                style="max-height: 400px; overflow-y: auto; scrollbar-width: none;">
                                <?php foreach ($reviews as $review) { ?>
                                    <div class="list-group-item border-0 py-2">
                                        <div class="d-flex align-items-start">
                                            <div class="avatar-premium me-3"
                                                style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                                                <?= strtoupper(substr((string)($review->name ?? ''), 0, 1)) ?>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="mb-0 fw-semibold"><?= $review->name ?></h6>
                                                    <span class="badge-premium bg-warning bg-opacity-10 text-warning">
                                                        <?= str_repeat('★', $review->rating) ?>
                                                    </span>
                                                </div>
                                                <p class="mb-2 text-sm">"<?= $review->comment ?>"</p>
                                                <small class="text-muted">
                                                    <i class="bi bi-clock me-1"></i>
                                                    <?= date('M d, Y', strtotime($review->created_at ?? 'now')) ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FINANCIALS SECTION -->
        <div id="financials-tab" class="tab-content" style="display: none;">
            <div class="row g-4">
                <div class="col-xl-8">
                    <!-- Financial Overview -->
                    <div class="card glass-card border-0 shadow-sm rounded-3" style="background:linear-gradient(135deg, #4361ee 0%, #4895ef 100%)!important;  
                            border: 1px solid rgba(255, 255, 255, 0.25); backdrop-filter: blur(30px) saturate(180%)">
                        <div
                            class="card-header bg-transparent d-flex justify-content-between align-items-center py-4 border-bottom">
                            <div>
                                <h5 class="mb-1 fw-bold text-white">Financial Overview</h5>
                                <small class="text-white">Revenue, profit, and expenses</small>
                            </div>
                            <div class="dropdown">
                                <button class="btn dropdown-toggle" style="background-color: rgba(139, 183, 255, 0.1) !important;
                                        color: #ffffff !important;
                                        border: 1px solid rgba(255, 255, 255, 0.7) !important;"
                                    data-bs-toggle="dropdown">
                                    This Year
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">This Quarter</a></li>
                                    <li><a class="dropdown-item" href="#">This Year</a></li>
                                    <li><a class="dropdown-item" href="#">All Time</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="chart-container-premium">
                                <canvas id="financialChart" style="height: 320px; width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Revenue Distribution -->
                    <div class="card glass-card border-0 shadow-sm rounded-3 mt-4">
                        <div class="card-header bg-transparent py-4 border-bottom"
                            style="background:linear-gradient(135deg, #10b981 0%, #34d399 100%)!important;">
                            <h5 class="mb-1 fw-bold text-white">Revenue Distribution</h5>
                            <small class="text-white">Income by service category</small>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-7">
                                    <canvas id="revenueStreamsChart" style="height: 280px; width: 100%;"></canvas>
                                </div>
                                <div class="col-md-5">
                                    <div class="mt-4">
                                        <?php
                                        $icons = [
                                            'Grooming' => 'bi-scissors',
                                            'Training' => 'bi-award',
                                            'Boarding' => 'bi-house-door',
                                            'Walking' => 'bi-pin-map',
                                            'Insurance' => 'bi-shield-check'
                                        ];
                                        $colors = [
                                            'Grooming' => '#8b5cf6',
                                            'Training' => '#3b82f6',
                                            'Boarding' => '#10b981',
                                            'Walking' => '#f59e0b',
                                            'Insurance' => '#ec4899'
                                        ];

                                        $total_rev = max($revenue, 1);
                                        foreach ($revenue_by_service as $stream) {
                                            $name = $stream['service'];
                                            $amount = $stream['amount'];
                                            $percent = round(($amount / $total_rev) * 100);
                                            $icon = $icons[$name] ?? 'bi-cash';
                                            $color = $colors[$name] ?? '#4f46e5';
                                            echo '
                                            <div class="mb-4">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="service-icon me-3" style="background-color: ' . $color . '20; color: ' . $color . ';">
                                                            <i class="bi ' . $icon . '"></i>
                                                        </div>
                                                        <span class="fw-medium">' . $name . '</span>
                                                    </div>
                                                    <span class="fw-bold" style="color: ' . $color . ';">₹' . number_format($amount) . '</span>
                                                </div>
                                                <div class="progress-premium">
                                                    <div class="progress-bar" style="background-color: ' . $color . '; width: ' . $percent . '%"></div>
                                                </div>
                                                <small class="text-muted">' . $percent . '% of total revenue</small>
                                            </div>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <!-- Financial KPIs -->
                    <div class="card glass-card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-transparent py-4 border-bottom" style="background:linear-gradient(135deg, #4361ee 0%, #4895ef 100%)!important;  
                            border: 1px solid rgba(255, 255, 255, 0.25); backdrop-filter: blur(30px) saturate(180%)">
                            <h5 class="mb-1 fw-bold text-white">Financial KPIs</h5>
                            <small class="text-white">Key financial indicators</small>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="stat-icon-wrapper bg-primary text-white mx-auto mb-2"
                                        style="width: 48px; height: 48px;">
                                        <i class="bi bi-currency-rupee text-white"></i>
                                    </div>
                                    <h4 class="mb-1 fw-bold">₹<?= number_format($revenue ?? 0) ?></h4>
                                    <small class="text-muted">Revenue</small>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="stat-icon-wrapper dashboard-gradient-green text-white mx-auto mb-2"
                                        style="width: 48px; height: 48px;">
                                        <i class="bi bi-graph-up text-white"></i>
                                    </div>
                                    <h4 class="mb-1 fw-bold">₹<?= number_format($profit ?? 0) ?></h4>
                                    <small class="text-muted">Profit</small>
                                </div>
                                <div class="col-6">
                                    <div class="stat-icon-wrapper dashboard-gradient-blue text-white mx-auto mb-2"
                                        style="width: 48px; height: 48px;">
                                        <i class="bi bi-cash-stack"></i>
                                    </div>
                                    <h4 class="mb-1 fw-bold">₹<?= number_format($request_amount ?? 0) ?></h4>
                                    <small class="text-muted">Payouts</small>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="stat-icon-wrapper dashboard-gradient-orange text-white mx-auto mb-2"
                                        style="width: 48px; height: 48px;">
                                        <i class="bi bi-percent"></i>
                                    </div>
                                    <h4 class="mb-1 fw-bold"><?= $margin ?>%</h4>
                                    <small class="text-muted">Margin</small>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="stat-icon-wrapper dashboard-gradient-purple text-white mx-auto mb-2"
                                        style="width: 48px; height: 48px;">
                                        <i class="bi bi-bank"></i>
                                    </div>
                                    <h4 class="mb-1 fw-bold">₹<?= number_format($payout_balance['available'] ?? 0, 2) ?>
                                    </h4>
                                    <small class="text-muted">Available Balance</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card glass-card border-0 shadow-sm rounded-3 mt-4">
                        <div class="card-header bg-transparent py-4 border-bottom"
                            style="background:linear-gradient(135deg, #10b981 0%, #34d399 100%)!important;">
                            <h5 class="mb-1 fw-bold text-white">Quick Actions</h5>
                            <small class="text-white">Common administrative tasks</small>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-3">
                                <button class="btn btn-gradient-primary py-3 fw-semibold"
                                    onclick="exportDashboardData()">
                                    <i class="bi bi-download me-2"></i>Export Financial Report
                                </button>
                                <a href="<?= base_url() ?>providers-payments"
                                    class="btn btn-outline-gradient py-3 fw-semibold text-white"
                                    style="color: #fff !important;">
                                    <i class="bi bi-cash-coin me-2"></i>View Provider Payouts
                                </a>
                                <a href="https://login.bulksmsgateway.in" target="_blank"
                                    class="btn btn-outline-gradient py-3 fw-semibold text-white"
                                    style="color: #fff !important;">
                                    <i class="bi bi-chat-text me-2"></i>Recharge SMS Credits
                                </a>
                                <button class="btn btn-outline-gradient py-3 fw-semibold text-white"
                                    onclick="generateInvoice()" style="color: #fff !important;">
                                    <i class="bi bi-receipt me-2"></i>Generate Invoice
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PROVIDERS SECTION -->
        <div id="providers-tab" class="tab-content" style="display: none;">
            <div class="row g-4">
                <div class="col-xl-8">
                    <!-- Provider Performance -->
                    <div class="card glass-card border-0 shadow-sm rounded-3">
                        <div style="background:linear-gradient(135deg, #4361ee 0%, #4895ef 100%)!important;  
                            border: 1px solid rgba(255, 255, 255, 0.25); backdrop-filter: blur(30px) saturate(180%)"
                            class="card-header bg-transparent d-flex justify-content-between align-items-center py-4 border-bottom">
                            <div>
                                <h5 class="mb-1 fw-bold text-white">Provider Performance</h5>
                                <small class="text-white">Top performing service providers</small>
                            </div>
                            <div class="dropdown">
                                <button class="btn dropdown-toggle" style="background-color: rgba(139, 183, 255, 0.1) !important;
                                        color: #ffffff !important;
                                        border: 1px solid rgba(255, 255, 255, 0.7) !important;"
                                    data-bs-toggle="dropdown">
                                    All Services
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Grooming</a></li>
                                    <li><a class="dropdown-item" href="#">Training</a></li>
                                    <li><a class="dropdown-item" href="#">Walking</a></li>
                                    <li><a class="dropdown-item" href="#">Boarding</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th style="background:linear-gradient(135deg, #4895ef 0%, #4361ee 100%)!important;"
                                                class="border-0 text-white">Provider</th>
                                            <th style="background:linear-gradient(135deg, #4895ef 0%, #4361ee 100%)!important;"
                                                class="border-0 text-center text-white">Service</th>
                                            <th style="background:linear-gradient(135deg, #4895ef 0%, #4361ee 100%)!important;"
                                                class="border-0 text-center text-white">Completed</th>
                                            <th style="background:linear-gradient(135deg, #4895ef 0%, #4361ee 100%)!important;"
                                                class="border-0 text-center text-white">Earnings</th>
                                            <th style="background:linear-gradient(135deg, #4895ef 0%, #4361ee 100%)!important;"
                                                class="border-0 text-center text-white">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $top_providers = [
                                            ['name' => 'John Smith', 'service' => 'Grooming', 'rating' => 4.9, 'completed' => 42, 'earnings' => '₹1,25,400', 'status' => 'active'],
                                            ['name' => 'Emma Wilson', 'service' => 'Training', 'rating' => 4.8, 'completed' => 38, 'earnings' => '₹98,500', 'status' => 'active'],
                                            ['name' => 'Mike Johnson', 'service' => 'Walking', 'rating' => 4.7, 'completed' => 56, 'earnings' => '₹87,200', 'status' => 'active'],
                                            ['name' => 'Sarah Davis', 'service' => 'Boarding', 'rating' => 4.9, 'completed' => 31, 'earnings' => '₹1,05,800', 'status' => 'active'],
                                            ['name' => 'Robert Brown', 'service' => 'Grooming', 'rating' => 4.6, 'completed' => 27, 'earnings' => '₹76,300', 'status' => 'inactive']
                                        ];

                                        foreach ($top_providers as $provider) {
                                            echo '
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-premium me-3" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                                                            ' . strtoupper(substr((string)($provider['name'] ?? ''), 0, 1)) . '
                                                        </div>
                                                       <div class="d-flex flex-column">
                                                        <span class="fw-semibold">' . $provider['name'] . '</span>
                                                        <span class="badge-premium bg-warning bg-opacity-10 text-warning" style="max-width:max-content;">
                                                        ' . $provider['rating'] . ' ★
                                                    </span>
                                                       </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge-premium" style="background-color: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                                                        ' . $provider['service'] . '
                                                    </span>
                                                </td>
                                                <td class="text-center fw-semibold">' . $provider['completed'] . '</td>
                                                <td class="text-center fw-bold" style="color: #10b981;">' . $provider['earnings'] . '</td>
                                                <td class="text-center">
                                                    <span class="badge-premium ' . ($provider['status'] === 'active' ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger') . '">
                                                        ' . ucfirst($provider['status']) . '
                                                    </span>
                                                </td>
                                            </tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <!-- Provider Statistics -->
                    <div class="card glass-card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-transparent py-4 border-bottom" style="background:linear-gradient(135deg, #4361ee 0%, #4895ef 100%)!important;  
                            border: 1px solid rgba(255, 255, 255, 0.25); backdrop-filter: blur(30px) saturate(180%)">
                            <h5 class="mb-1 fw-bold text-white">Provider Statistics</h5>
                            <small class="text-white">Provider network overview</small>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="stat-icon-wrapper bg-primary text-white mx-auto mb-2"
                                        style="width: 48px; height: 48px;">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <h4 class="mb-1 fw-bold"><?= $active_providers ?? 0 ?></h4>
                                    <small class="text-muted">Active Providers</small>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="stat-icon-wrapper dashboard-gradient-green text-white mx-auto mb-2"
                                        style="width: 48px; height: 48px;">
                                        <i class="bi bi-check-circle"></i>
                                    </div>
                                    <h4 class="mb-1 fw-bold">94%</h4>
                                    <small class="text-muted">Satisfaction</small>
                                </div>
                                <div class="col-6">
                                    <div class="stat-icon-wrapper dashboard-gradient-blue text-white mx-auto mb-2"
                                        style="width: 48px; height: 48px;">
                                        <i class="bi bi-clock"></i>
                                    </div>
                                    <h4 class="mb-1 fw-bold"><?= $providers->pending_tracking_approvals ?? 0 ?></h4>
                                    <small class="text-muted">Pending Approval</small>
                                </div>
                                <div class="col-6">
                                    <div class="stat-icon-wrapper dashboard-gradient-orange text-white mx-auto mb-2"
                                        style="width: 48px; height: 48px;">
                                        <i class="bi bi-star"></i>
                                    </div>
                                    <h4 class="mb-1 fw-bold">4.7</h4>
                                    <small class="text-muted">Avg. Rating</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Provider Services -->
                    <div class="card glass-card border-0 shadow-sm rounded-3 mt-4">
                        <div class="card-header bg-transparent py-4 border-bottom" style="background:linear-gradient(135deg, #4361ee 0%, #4895ef 100%)!important;  
                            border: 1px solid rgba(255, 255, 255, 0.25); backdrop-filter: blur(30px) saturate(180%)">
                            <h5 class="mb-1 fw-bold text-white">Provider by Service</h5>
                            <small class="text-white">Provider distribution</small>
                        </div>
                        <div class="card-body">
                            <canvas id="providerServicesChart" style="height: 200px; width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo $this->include('admin/footer') ?>
</main>

<style>
    /* Premium Dashboard Styles */
    .dashboard-gradient-bg {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important;
    }

    .dashboard-gradient-purple {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%) !important;
    }

    .dashboard-gradient-blue {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
    }

    .dashboard-gradient-green {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    }

    .dashboard-gradient-orange {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
    }

    .dashboard-gradient-red {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
    }

    /* Modern Button Styles */
    .btn-gradient-primary {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important;
        border: none !important;
        color: white !important;
        transition: all 0.3s ease !important;
    }

    .btn-gradient-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3) !important;
    }

    .btn-outline-gradient {
        background: transparent !important;
        border: 2px solid transparent !important;
        background-clip: padding-box !important;
        position: relative !important;
        color: #4f46e5 !important;
    }

    .btn-outline-gradient::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border-radius: inherit;
        z-index: -1;
    }

    .btn-outline-gradient:hover {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important;
        color: white !important;
    }

    /* Card Hover Effects */
    .stat-card-hover {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #e5e7eb;
        overflow: hidden;
        position: relative;
    }

    .stat-card-hover::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.7s;
    }

    .stat-card-hover:hover::before {
        left: 100%;
    }

    .stat-card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(79, 70, 229, 0.1), 0 10px 10px -5px rgba(79, 70, 229, 0.04) !important;
        border-color: #c7d2fe;
    }

    /* Icon Styles */
    .stat-icon-wrapper {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        font-size: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .stat-icon-wrapper::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background: inherit;
        opacity: 0.15;
        border-radius: inherit;
    }

    /* Tab Navigation */
    .dashboard-tab {
        padding: 0.65rem 1.3rem;
        border: none;
        background: transparent;
        color: #6b7280;
        font-weight: 500;
        transition: all 0.3s ease;
        position: relative;
        border-radius: 50px;
    }

    .dashboard-tab:hover {
        color: #4f46e5;
        background: rgba(79, 70, 229, 0.05);
    }

    .dashboard-tab.active {
        background-color: rgba(59, 130, 246, 0.2) !important;
        color: #2563eb !important;
        border: 1px solid rgba(59, 130, 246, 0.4) !important;
    }

    /* Progress Bar */
    .progress-premium {
        height: 8px;
        border-radius: 4px;
        overflow: hidden;
        background: rgba(0, 0, 0, 0.05);
    }

    .progress-premium .progress-bar {
        border-radius: 4px;
        transition: width 1s ease-in-out;
    }

    /* Avatar Styles */
    .avatar-premium {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .avatar-premium::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background: inherit;
        opacity: 0.8;
    }

    /* Badge Styles */
    .badge-premium {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.02em;
        border: 1px solid transparent;
    }

    .badge-gradient-primary {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
    }

    .badge-gradient-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    /* Chart Containers */
    .chart-container-premium {
        position: relative;
        height: 320px;
        background: white;
        border-radius: 12px;
        padding: 20px;
    }

    /* Header Gradient */
    .dashboard-header-gradient {
        background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%);
        position: relative;
        overflow: hidden;
    }

    .dashboard-header-gradient::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background:
            radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.2) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(255, 255, 255, 0.1) 0%, transparent 60%);
        opacity: 0.6;
        animation: gradientShift 15s ease-in-out infinite alternate;
    }

    @keyframes gradientShift {
        0% {
            background:
                radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(255, 255, 255, 0.1) 0%, transparent 60%);
        }

        50% {
            background:
                radial-gradient(circle at 30% 70%, rgba(255, 255, 255, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 70% 30%, rgba(255, 255, 255, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 60% 60%, rgba(255, 255, 255, 0.1) 0%, transparent 60%);
        }

        100% {
            background:
                radial-gradient(circle at 25% 75%, rgba(255, 255, 255, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 75% 25%, rgba(255, 255, 255, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 60%);
        }
    }

    /* Service Icons */
    .service-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-right: 12px;
    }

    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border-radius: 3px;
    }

    /* Loading Animation */
    .loading-shimmer {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }

    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }

        100% {
            background-position: 200% 0;
        }
    }

    /* Pulse Animation for Low Credits */
    .pulse-low {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
        }
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .stat-icon-wrapper {
            width: 48px;
            height: 48px;
            font-size: 1.25rem;
        }

        .chart-container-premium {
            height: 280px;
        }

        .dashboard-tab {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(30px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.25);
    }

    .glass-pill {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.25);
    }

    .glass-btn {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(8px);
        border: none;
    }

    canvas {
        height: 180px !important;
        width: 100% !important;
        max-height: 180px;
        min-height: 180px;
    }

    Specific overrides for smaller charts #noShowsChart,
    #insuranceChart {
        height: 180px !important;
        max-height: 180px;
        min-height: 180px;
    }

    #jobsRequiringSPChart,
    #jobsAboutToHoldChart,
    #servicesEndingChart {
        height: 180px !important;
        max-height: 180px;
        min-height: 180px;
    }

    /* Prevent card expansion */
    .card {
        height: auto !important;
        min-height: auto !important;
    }

    .card-body {
        overflow: hidden;
    }

    .chart-container-premium {
        padding: 0 !important;
    }
</style>

<!-- Add Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Store chart instances to properly destroy them before recreating
    let chartInstances = {};

    // Initialize charts when DOM is loaded
    document.addEventListener("DOMContentLoaded", function() {
        initializePremiumCharts();
    });

    // Tab switching function
    function switchTab(tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.style.display = 'none';
        });

        // Show selected tab
        document.getElementById(tabName + '-tab').style.display = 'block';

        // Update tab buttons
        document.querySelectorAll('.dashboard-tab').forEach(btn => {
            btn.classList.remove('active');
        });

        // Find and activate the clicked tab
        const tabs = document.querySelectorAll('.dashboard-tab');

        tabs.forEach(tab => {
            if (tab.textContent.toLowerCase().includes(tabName)) {
                tab.classList.add('active');
            }
        });

        setTimeout(() => {
            initializePremiumCharts();
        }, 100);
    }

    // Initialize premium charts with Chart.js
    // Initialize premium charts with Chart.js
    function initializePremiumCharts() {
        // Destroy existing charts to prevent memory leaks
        Object.values(chartInstances).forEach(chart => {
            if (chart) chart.destroy();
        });
        chartInstances = {};

        // Jobs Requiring SP by Category Chart
        const jobsRequiringSPCtx = document.getElementById('jobsRequiringSPChart')?.getContext('2d');
        if (jobsRequiringSPCtx) {
            chartInstances.jobsRequiringSP = new Chart(jobsRequiringSPCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Walking', 'Grooming', 'Training'],
                    datasets: [{
                        data: [<?= $walkingBidsPerc ?>, <?= $groomingBidsPerc ?>, <?= $trainingBidsPerc ?>],
                        backgroundColor: [
                            '#4f46e5',
                            '#8b5cf6',
                            '#a78bfa'
                        ],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 15,
                                usePointStyle: true,
                                font: {
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.raw + '%';
                                }
                            }
                        },
                        title: {
                            display: true,
                            // text: 'Jobs Requiring SP Distribution',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            padding: {
                                bottom: 10
                            }
                        }
                    },
                    cutout: '60%',
                }
            });
        }

        // Jobs About to Hold by Category Chart
        const jobsAboutToHoldCtx = document.getElementById('jobsAboutToHoldChart')?.getContext('2d');
        if (jobsAboutToHoldCtx) {
            chartInstances.jobsAboutToHold = new Chart(jobsAboutToHoldCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Walking', 'Grooming', 'Training'],
                    datasets: [{
                        data: [<?= $walkingHaltedPerc ?>, <?= $groomingHaltedPerc ?>, <?= $trainingHaltedPerc ?>],
                        backgroundColor: [
                            '#06b6d4',
                            '#0891b2',
                            '#0e7490'
                        ],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 15,
                                usePointStyle: true,
                                font: {
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.raw + '%';
                                }
                            }
                        },
                        title: {
                            display: true,
                            // text: 'Jobs About to Hold',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            padding: {
                                bottom: 10
                            }
                        }
                    },
                    cutout: '60%',
                }
            });
        }

        // No Shows Today Chart
        const noShowsCtx = document.getElementById('noShowsChart')?.getContext('2d');
        if (noShowsCtx) {
            chartInstances.noShows = new Chart(noShowsCtx, {
                type: 'pie',
                data: {
                    labels: ['Walking', 'Grooming'],
                    datasets: [{
                        data: [<?= $walkingNoShowsPerc ?>, <?= $groomingNoShowsPerc ?>],
                        backgroundColor: [
                            '#ef4444',
                            '#f87171'
                        ],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 15,
                                usePointStyle: true,
                                font: {
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.raw + '%';
                                }
                            }
                        },
                        title: {
                            display: true,
                            // text: 'No Shows Today',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            padding: {
                                bottom: 10
                            }
                        }
                    },
                }
            });
        }

        // Insurance Requests vs Claims Chart


        // Services Ending Soon Chart
        const servicesEndingCtx = document.getElementById('servicesEndingChart')?.getContext('2d');
        if (servicesEndingCtx) {
            chartInstances.servicesEnding = new Chart(servicesEndingCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Walking', 'Training'],
                    datasets: [{
                        data: [<?= $walkingEndingPerc ?>, <?= $trainingEndingPerc ?>],
                        backgroundColor: [
                            '#f59e0b',
                            '#fbbf24'
                        ],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 15,
                                usePointStyle: true,
                                font: {
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.raw + '%';
                                }
                            }
                        },
                        title: {
                            display: true,
                            // text: 'Services Ending Soon',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            padding: {
                                bottom: 10
                            }
                        }
                    },
                    cutout: '60%',
                }
            });
        }

        // Analytics Chart
        const analyticsCtx = document.getElementById('analyticsChart')?.getContext('2d');
        if (analyticsCtx) {
            chartInstances.analytics = new Chart(analyticsCtx, {
                type: 'line',
                data: {
                    labels: <?= json_encode(array_column($monthly_revenue, 'month')) ?>,
                    datasets: [{
                        label: 'Revenue',
                        data: <?= json_encode(array_column($monthly_revenue, 'revenue')) ?>,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label === 'Revenue') {
                                        return label + ': ₹' + context.raw.toLocaleString('en-IN');
                                    }
                                    return label + ': ' + context.raw;
                                }
                            }
                        },
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8
                            }
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Revenue (₹)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return '₹' + (value / 1000) + 'k';
                                }
                            },
                            grid: {
                                color: '#e5e7eb',
                                dash: [5, 5]
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Financial Chart
        const financialCtx = document.getElementById('financialChart')?.getContext('2d');
        if (financialCtx) {
            chartInstances.financial = new Chart(financialCtx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode(array_column($monthly_revenue, 'month')) ?>,
                    datasets: [{
                        label: 'Revenue',
                        data: <?= json_encode(array_column($monthly_revenue, 'revenue')) ?>,
                        backgroundColor: '#4f46e5',
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ₹' + context.raw.toLocaleString('en-IN');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₹' + (value / 1000) + 'k';
                                }
                            },
                            grid: {
                                color: '#e5e7eb',
                                dash: [5, 5]
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Revenue Streams Chart
        const revenueStreamsCtx = document.getElementById('revenueStreamsChart')?.getContext('2d');
        if (revenueStreamsCtx) {
            chartInstances.revenueStreams = new Chart(revenueStreamsCtx, {
                type: 'doughnut',
                data: {
                    // labels: ['Grooming (42%)', 'Training (27%)', 'Boarding (18%)', 'Walking (8%)', 'Insurance (5%)'],
                    datasets: [{
                        data: [42, 27, 18, 8, 5],
                        backgroundColor: [
                            '#8b5cf6',
                            '#3b82f6',
                            '#10b981',
                            '#f59e0b',
                            '#ec4899'
                        ],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 15,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.raw + '%';
                                }
                            }
                        }
                    },
                    cutout: '65%',
                }
            });
        }

        // Provider Services Chart
        const providerServicesCtx = document.getElementById('providerServicesChart')?.getContext('2d');
        if (providerServicesCtx) {
            chartInstances.providerServices = new Chart(providerServicesCtx, {
                type: 'doughnut',
                data: {
                    // labels: ['Grooming (28%)', 'Training (24%)', 'Boarding (23%)', 'Walking (15%)', 'Vet Care (10%)'],
                    datasets: [{
                        data: [28, 24, 23, 15, 10],
                        backgroundColor: [
                            '#8b5cf6',
                            '#3b82f6',
                            '#10b981',
                            '#f59e0b',
                            '#06b6d4'
                        ],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 15,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.raw + '%';
                                }
                            }
                        }
                    },
                    cutout: '70%',
                }
            });
        }
        const insuranceCtx = document.getElementById('insuranceChart')?.getContext('2d');
        if (insuranceCtx) {
            chartInstances.insurance = new Chart(insuranceCtx, {
                type: 'pie',
                data: {
                    labels: ['Requests (<?= $requestsPerc ?>%)', 'Claims (<?= $claimsPerc ?>%)'],
                    datasets: [{
                        data: [<?= $requestsPerc ?>, <?= $claimsPerc ?>],
                        backgroundColor: [
                            '#f59e0b', // Orange for Requests
                            '#10b981' // Green for Claims
                        ],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 10,
                                usePointStyle: true,
                                font: {
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.raw + '%';
                                }
                            }
                        }
                    },
                    cutout: '0%', // For pie chart (use '60%' for doughnut)
                }
            });
        }

    

    }

    // Change chart period
    function changeChartPeriod(period) {
        console.log('Changing chart period to:', period);
        // You can implement data refresh logic here
    }

    // Generate report
    function generateReport() {
        alert('Generating comprehensive dashboard report...');
        // Implement report generation logic
    }

    // Generate invoice
    function generateInvoice() {
        alert('Opening invoice generator...');
        // Implement invoice generation logic
    }

    // Export dashboard data
    function exportDashboardData() {
        alert('Exporting dashboard data...');
        // Implement export logic
    }

    // Handle window resize to make charts responsive
    window.addEventListener('resize', function() {
        // Charts will automatically resize if responsive: true
    });
</script>

<?php echo $this->endSection() ?>