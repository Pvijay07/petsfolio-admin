<?php echo $this->extend('admin/includes/header'); ?>
<?php echo $this->section('content'); ?>

<main class="main-content">
    <div class="position-relative iq-banner">
        <?php echo $this->include('admin/nav_bar') ?>
    </div>

    <div class="conatiner-fluid content-inner pb-0 px-4" id="page_layout">
        <!-- Modern Header Section -->
        <div class="container-fluid p-0 mb-4 bg-gradient-primary rounded-3">
            <div class="text-white p-5 shadow position-relative overflow-hidden">
                <div class="position-relative z-1">
                    <h1 class="h2 fw-bold mb-2 text-white">Client Bookings Analytics</h1>
                    <p class="text-white-75 mb-0">Comprehensive statistics and insights across all states and cities</p>
                </div>
                <i class="bi bi-graph-up position-absolute end-0 bottom-0 opacity-10"
                    style="font-size: 12rem; transform: rotate(12deg); margin-right: -2rem; margin-bottom: -2rem;"></i>
            </div>
            <!-- <div class="p-3">
                <div class="card border-0 shadow-sm mb-0">
                    <div class="card-body p-3">
                        <div
                            class="d-flex flex-column flex-md-row justify-content-between  align-items-start align-items-md-center gap-3">
                             Date Filter 
                            <div class="input-group flex-grow-1 flex-md-grow-0 " style="max-width: 450px;">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-calendar text-muted"></i>
                                </span>
                                <input type="date" class="form-control p-2 ps-0" id="dateFilter">
                            </div>

                            <div class="d-flex align-items-center gap-3">
                                <div class="input-group" style="max-width: 250px;">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-filter text-muted"></i>
                                    </span>
                                    <select class="form-select border-start-0 ps-0" id="stateFilter">
                                        <option value="">All States</option>
                                        <?php foreach ($combined_stats as $state): ?>
                                            <option value="<?= preg_replace('/[^a-zA-Z0-9]/', '_', $state['name']) ?>">
                                                <?= $state['name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>

        <!-- Overview Statistics -->
        <div class="row g-4 mb-4">
            <?php
            // Calculate totals
            $total_clients = 0;
            $total_jobs = 0;
            $total_completed = 0;
            $total_cancelled = 0;

            foreach ($combined_stats as $state) {
                $total_clients += $state['clients_signed_up'];
                $total_jobs += $state['jobs_posted'];
                $total_completed += $state['completed'];
                $total_cancelled += $state['cancelled'];
            }
            ?>

            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-people fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted small mb-1">Total Clients</p>
                                <h3 class="mb-0"><?= number_format($total_clients) ?></h3>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-primary" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-briefcase fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted small mb-1">Total Jobs</p>
                                <h3 class="mb-0"><?= number_format($total_jobs) ?></h3>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-check-circle fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted small mb-1">Completed Jobs</p>
                                <h3 class="mb-0"><?= number_format($total_completed) ?></h3>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-info"
                                    style="width: <?= $total_jobs > 0 ? ($total_completed / $total_jobs * 100) : 0 ?>%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-x-circle fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted small mb-1">Cancelled Jobs</p>
                                <h3 class="mb-0"><?= number_format($total_cancelled) ?></h3>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-danger"
                                    style="width: <?= $total_jobs > 0 ? ($total_cancelled / $total_jobs * 100) : 0 ?>%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-transparent border-0 p-4">
                                <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                                    <i class="bi bi-pie-chart text-primary"></i>
                                    State-wise Distribution
                                </h5>
                                <p class="text-muted small mb-0">Jobs posted by state</p>
                            </div>
                            <div class="card-body p-4 pt-0">
                                <div class="chart-container" style="position: relative; height: 300px;">
                                    <canvas id="stateChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-transparent border-0 p-4">
                                <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                                    <i class="bi bi-pie-chart-fill text-success"></i>
                                    Job Status Overview
                                </h5>
                                <p class="text-muted small mb-0">All states combined</p>
                            </div>
                            <div class="card-body p-4 pt-0">
                                <div class="chart-container" style="position: relative; height: 300px;">
                                    <canvas id="statusChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 p-4">
                        <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                            <i class="bi bi-trophy text-warning"></i>
                            Top Performing States
                        </h5>
                        <p class="text-muted small mb-0">Based on job completion rate</p>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <div class="list-group list-group-flush">
                            <?php
                            $sorted_states = $combined_stats;
                            usort($sorted_states, function ($a, $b) {
                                $rate_a = $a['jobs_posted'] > 0 ? ($a['completed'] / $a['jobs_posted']) : 0;
                                $rate_b = $b['jobs_posted'] > 0 ? ($b['completed'] / $b['jobs_posted']) : 0;
                                return $rate_b <=> $rate_a;
                            });

                            $top_states = array_slice($sorted_states, 0, 4);
                            foreach ($top_states as $index => $state):
                                $completion_rate = $state['jobs_posted'] > 0 ? round(($state['completed'] / $state['jobs_posted']) * 100, 1) : 0;
                                ?>
                                <div class="list-group-item border-0 px-0 py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold"
                                                style="width: 36px; height: 36px;">
                                                <?= $index + 1 ?>
                                            </div>
                                            <div>
                                                <h6 class="mb-1"><?= esc($state['name']) ?></h6>
                                                <small class="text-muted"><?= number_format($state['jobs_posted']) ?>
                                                    jobs</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold text-success"><?= $completion_rate ?>%</div>
                                            <small class="text-muted">Completion</small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- States Table -->
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-transparent border-0 p-4">
                <h4 class="card-title mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-table text-primary"></i>
                    State-wise Statistics
                </h4>
                <p class="text-muted small mb-0">Click on any state to view city details</p>
            </div>
            <div class="card-body p-0 pt-0">
                <div class="table-responsive ">
                    <table class="table table-hover mb-0" id="statesTable" style="table-layout: fixed; width: 100%;">
                        <thead style="background-color: #c6d2ff!important;">
                            <tr>
                                <th width="150" class="ps-4 py-3 text-black  fw-semibold border-bottom"
                                    style="background: #c6d2ff!important;">
                                    State
                                </th>
                                <th width="100" class="py-3 text-black  fw-semibold border-bottom text-center"
                                    style="background: #c6d2ff!important;">
                                    Clients
                                </th>
                                <th width="100" class="py-3 text-black  fw-semibold border-bottom text-center"
                                    style="background: #c6d2ff!important;">
                                    Jobs Posted
                                </th>
                                <th width="100" class="py-3 text-black  fw-semibold border-bottom text-center"
                                    style="background: #c6d2ff!important;">
                                    Unassigned
                                </th>
                                <th width="100" class="py-3 text-black  fw-semibold border-bottom text-center"
                                    style="background: #c6d2ff!important;">
                                    Assigned
                                </th>
                                <th width="100" class="py-3 text-black  fw-semibold border-bottom text-center"
                                    style="background: #c6d2ff!important;">
                                    SP Replaced
                                </th>
                                <th width="100" class="py-3 text-black  fw-semibold border-bottom text-center"
                                    style="background: #c6d2ff!important;">
                                    Cancelled
                                </th>
                                <th width="100" class="py-3 text-black  fw-semibold border-bottom text-center"
                                    style="background: #c6d2ff!important;">
                                    Refunds
                                </th>
                                <th width="100" class="py-3 text-black  fw-semibold border-bottom text-center"
                                    style="background: #c6d2ff!important;">
                                    Completed
                                </th>
                                <th width="100" class="pe-4 py-3 text-black  fw-semibold border-bottom text-center"
                                    style="background: #c6d2ff!important;">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($combined_stats as $state):
                                $sanitizedState = preg_replace('/[^a-zA-Z0-9]/', '_', $state['name']);
                                ?>
                                <tr data-state="<?= $sanitizedState ?>">
                                    <td class="ps-4 py-3">
                                        <span class="fw-bold text-dark"><?= esc($state['name']) ?></span>
                                    </td>
                                    <td class="py-3 text-center">
                                        <div class="fw-medium"><?= number_format($state['clients_signed_up']) ?></div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <div class="fw-medium"><?= number_format($state['jobs_posted']) ?></div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span
                                            class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-1">
                                            <?= number_format($state['unassigned_jobs']) ?>
                                        </span>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span
                                            class="badge bg-info bg-opacity-10 text-white border border-info border-opacity-25 px-3 py-1">
                                            <?= number_format($state['assigned']) ?>
                                        </span>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span
                                            class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-1">
                                            <?= number_format($state['sp_replaced']) ?>
                                        </span>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span
                                            class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-1">
                                            <?= number_format($state['cancelled']) ?>
                                        </span>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span
                                            class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-1">
                                            <?= number_format($state['cancelled']) ?>
                                        </span>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span
                                            class="badge bg-success bg-opacity-10 text-white border border-success border-opacity-25 px-3 py-1">
                                            <?= number_format($state['completed']) ?>
                                        </span>
                                    </td>
                                    <td class="pe-4 py-3 text-center">
                                        <button
                                            class="btn btn-sm btn-outline-primary view-cities d-flex align-items-center gap-1 px-2"
                                            data-state="<?= $sanitizedState ?>" data-state-name="<?= esc($state['name']) ?>"
                                            data-bs-toggle="modal" data-bs-target="#citiesModal">
                                            <i class="bi bi-eye"></i>
                                            <span class="d-none d-md-inline">Cities</span>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Cities Row (Hidden by default) -->
                                <?php if (!empty($state['cities'])): ?>
                                    <tr id="cities-<?= $sanitizedState ?>" class="cities-row" style="display: none;">
                                        <td colspan="10" class="p-0">
                                            <div class="p-4 bg-light">
                                                <h6 class="fw-bold mb-3">Cities in <?= esc($state['name']) ?></h6>
                                                <div class="table-responsive rounded-4">
                                                    <table class="table table-sm table-bordered mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>City</th>
                                                                <th class="text-center">Clients</th>
                                                                <th class="text-center">Jobs Posted</th>
                                                                <th class="text-center">Unassigned</th>
                                                                <th class="text-center">Assigned</th>
                                                                <th class="text-center">SP Replaced</th>
                                                                <th class="text-center">Cancelled</th>
                                                                <th class="text-center">Refunds</th>
                                                                <th class="text-center">Completed</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($state['cities'] as $city): ?>
                                                                <tr>
                                                                    <td><?= esc($city['name']) ?></td>
                                                                    <td class="text-center">
                                                                        <?= number_format($city['clients_signed_up']) ?>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?= number_format($city['jobs_posted']) ?>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?= number_format($city['unassigned_jobs']) ?>
                                                                    </td>
                                                                    <td class="text-center text-white">
                                                                        <?= number_format($city['assigned']) ?>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?= number_format($city['sp_replaced']) ?>
                                                                    </td>
                                                                    <td class="text-center"><?= number_format($city['cancelled']) ?>
                                                                    </td>
                                                                    <td class="text-center"><?= number_format($city['refunds']) ?>
                                                                    </td>
                                                                    <td class="text-center"><?= number_format($city['completed']) ?>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Cities Modal -->
<div class="modal fade" id="citiesModal" tabindex="-1" aria-labelledby="citiesModalLabel" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow-lg p-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="citiesModalLabel">City Statistics</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <h6 class="fw-bold mb-3" id="modalStateName"></h6>
                <div class="table-responsive rounded-4">
                    <table class="table table-bordered mb-0" id="citiesTable">
                        <thead class="table-light">
                            <tr>
                                <th>City</th>
                                <th class="text-center">Clients</th>
                                <th class="text-center">Jobs Posted</th>
                                <th class="text-center">Unassigned</th>
                                <th class="text-center">Assigned</th>
                                <th class="text-center">SP Replaced</th>
                                <th class="text-center">Cancelled</th>
                                <th class="text-center">Refunds</th>
                                <th class="text-center">Completed</th>
                            </tr>
                        </thead>
                        <tbody id="citiesTableBody">
                            <!-- Cities data will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top-0 bg-light p-3">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* Custom styles to match the exact design */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
    }

    .card {
        border-radius: 16px !important;
    }

    .table thead th {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding-top: 1rem;
        padding-bottom: 1rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        background: #c6d2ff !important;
        color: #000 !important;
    }

    .table tbody td {
        vertical-align: middle;
        padding-top: 1rem;
        padding-bottom: 1rem;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: rgba(99, 102, 241, 0.03) !important;
    }

    .badge {
        font-weight: 500 !important;
        white-space: nowrap;
        border-radius: 50px !important;
    }

    .btn-outline-primary {
        border-color: #dee2e6;
        color: #6c757d;
        padding: 0.25rem 0.5rem;
        white-space: nowrap;
    }

    .btn-outline-primary:hover {
        border-color: #6366f1;
        color: #6366f1;
        background-color: transparent;
    }

    /* Fixed table layout */
    #statesTable {
        table-layout: fixed !important;
        width: 100% !important;
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 16px !important;
        overflow: hidden;
    }

    /* Responsive adjustments */
    @media (max-width: 1200px) {
        #statesTable {
            min-width: 1000px;
        }

        .table-responsive {
            overflow-x: auto;
        }
    }

    @media (max-width: 768px) {
        .d-flex.justify-content-between.align-items-center {
            flex-direction: column;
            gap: 1rem;
        }

        .input-group {
            width: 100% !important;
            max-width: 100% !important;
        }

        .table td,
        .table th {
            padding: 0.75rem 0.5rem;
        }

        .row.g-4 .col-md-3 {
            margin-bottom: 1rem;
        }

        .btn span.d-none.d-md-inline {
            display: none !important;
        }

        /* Modal responsive */
        .modal-dialog {
            margin: 0.5rem;
        }

        .modal-xl {
            max-width: calc(100% - 1rem);
        }
    }

    @media (max-width: 576px) {
        .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }

    /* Summary card hover effects */
    .card:hover {
        transform: translateY(-2px);
        transition: transform 0.2s ease;
    }

    /* Cities row styling */
    .cities-row {
        background-color: #f8f9fa;
    }

    .cities-row td {
        border-bottom: 2px solid #dee2e6;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // State Chart Data Preparation
        const stateNames = <?= json_encode(array_keys($combined_stats)) ?>;
        const stateJobs = <?= json_encode(array_column($combined_stats, 'jobs_posted')) ?>;

        // Calculate totals for job status chart
        let totalUnassigned = 0;
        let totalAssigned = 0;
        let totalReplaced = 0;
        let totalCancelled = 0;
        let totalCompleted = 0;

        <?php foreach ($combined_stats as $state): ?>
            totalUnassigned += <?= $state['unassigned_jobs'] ?>;
            totalAssigned += <?= $state['assigned'] ?>;
            totalReplaced += <?= $state['sp_replaced'] ?>;
            totalCancelled += <?= $state['cancelled'] ?>;
            totalCompleted += <?= $state['completed'] ?>;
        <?php endforeach; ?>

        // Colors for charts
        const chartColors = [
            '#3f30e3', '#5555fa', '#6c63ff', '#8a84ff', '#a8a5ff',
            '#6366f1', '#4f46e5', '#4338ca', '#3730a3', '#312e81',
            '#7c3aed', '#8b5cf6', '#a78bfa', '#c4b5fd', '#ddd6fe',
            '#ec4899', '#db2777', '#be185d', '#9d174d', '#831843'
        ];

        // State Distribution Chart
        const stateCtx = document.getElementById('stateChart').getContext('2d');
        const stateChart = new Chart(stateCtx, {
            type: 'pie',
            data: {
                labels: stateNames,
                datasets: [{
                    data: stateJobs,
                    backgroundColor: chartColors.slice(0, stateNames.length),
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                return `${label}: ${value} jobs (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Job Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Unassigned', 'Assigned', 'SP Replaced', 'Cancelled', 'Completed'],
                datasets: [{
                    data: [totalUnassigned, totalAssigned, totalReplaced, totalCancelled, totalCompleted],
                    backgroundColor: [
                        '#f59e0b', // Unassigned - orange
                        '#3b82f6', // Assigned - blue
                        '#6b7280', // SP Replaced - gray
                        '#ef4444', // Cancelled - red
                        '#10b981'  // Completed - green
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    }
                }
            }
        });

        // States data - This is an OBJECT with state names as keys
        const statesData = <?= json_encode($combined_stats) ?>;
        console.log("statesData is an object:", typeof statesData === 'object' && !Array.isArray(statesData));
        console.log("statesData:", statesData);
        console.log("State names in data:", Object.keys(statesData));

        document.querySelectorAll('.view-cities').forEach(button => {
            button.addEventListener('click', function (e) {
                const stateDisplayName = this.dataset.stateName; // This is the original name like "Madhya Pradesh"
                console.log("Clicked state display name:", stateDisplayName);
                
                // Get state data directly from the object using the original state name
                const stateData = statesData[stateDisplayName];
                
                console.log("Found state data:", stateData);

                const tableBody = document.getElementById('citiesTableBody');
                const modalStateName = document.getElementById('modalStateName');
                
                // Clear previous content
                tableBody.innerHTML = '';
                modalStateName.textContent = `Cities in ${stateDisplayName}`;

                // Check if we have valid data
                if (!stateData) {
                    console.error("No state data found for:", stateDisplayName);
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                Error: State data not found
                            </td>
                        </tr>`;
                    return;
                }

                // Check if state has cities
                if (!stateData.cities || typeof stateData.cities !== 'object' || Object.keys(stateData.cities).length === 0) {
                    console.log("No cities found for state:", stateDisplayName);
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                No cities available in ${stateDisplayName}
                            </td>
                        </tr>`;
                    return;
                }

                console.log("Cities found:", stateData.cities);
                
                // Cities is also an object, not an array
                const cities = stateData.cities;
                
                // Add each city to the table
                Object.values(cities).forEach(city => {
                    const row = `
                        <tr>
                            <td>${city.name || 'N/A'}</td>
                            <td class="text-center">${city.clients_signed_up || 0}</td>
                            <td class="text-center">${city.jobs_posted || 0}</td>
                            <td class="text-center">${city.unassigned_jobs || 0}</td>
                            <td class="text-center">${city.assigned || 0}</td>
                            <td class="text-center">${city.sp_replaced || 0}</td>
                            <td class="text-center">${city.cancelled || 0}</td>
                            <td class="text-center">${city.refunds || 0}</td>
                            <td class="text-center">${city.completed || 0}</td>
                        </tr>
                    `;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
                
                console.log("Table populated with", Object.keys(cities).length, "cities");
            });
        });

        // Fix PHP foreach loop to work with object
        // Update your PHP code where you generate the filter options:
        <?php 
        // In your PHP code, change this:
        // foreach ($combined_stats as $state):
        // to this (since $combined_stats is an object/associative array):
        // foreach ($combined_stats as $stateName => $state):
        ?>

        // Also fix the state filter functionality
        const stateFilter = document.getElementById('stateFilter');
        if (stateFilter) {
            stateFilter.addEventListener('change', function (e) {
                const selectedState = e.target.value;
                const rows = document.querySelectorAll('#statesTable tbody tr[data-state]');

                rows.forEach(row => {
                    if (!selectedState || row.getAttribute('data-state') === selectedState) {
                        row.style.display = '';
                        // Show corresponding cities row if it exists
                        const citiesRow = document.getElementById('cities-' + row.getAttribute('data-state'));
                        if (citiesRow) {
                            citiesRow.style.display = '';
                        }
                    } else {
                        row.style.display = 'none';
                        // Hide corresponding cities row if it exists
                        const citiesRow = document.getElementById('cities-' + row.getAttribute('data-state'));
                        if (citiesRow) {
                            citiesRow.style.display = 'none';
                        }
                    }
                });
            });
        }

        // Prevent page scrolling when modal is open
        const citiesModal = document.getElementById('citiesModal');
        if (citiesModal) {
            citiesModal.addEventListener('show.bs.modal', function () {
                document.body.style.overflow = 'hidden';
                document.body.style.paddingRight = '0';
                console.log("Modal opened - scrolling disabled");
            });
            
            citiesModal.addEventListener('hidden.bs.modal', function () {
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
                console.log("Modal closed - scrolling enabled");
            });
        }

        // Row click to toggle cities (alternative to modal)
        document.querySelectorAll('#statesTable tbody tr[data-state]').forEach(row => {
            row.addEventListener('click', function (e) {
                // Don't trigger if clicking on view cities button
                if (!e.target.closest('.view-cities')) {
                    const stateId = this.getAttribute('data-state');
                    const citiesRow = document.getElementById('cities-' + stateId);

                    if (citiesRow) {
                        const isVisible = citiesRow.style.display !== 'none';
                        // Hide all other cities rows
                        document.querySelectorAll('.cities-row').forEach(row => {
                            row.style.display = 'none';
                        });
                        // Toggle this one
                        citiesRow.style.display = isVisible ? 'none' : '';
                    }
                }
            });
        });
    });
</script>

<?php echo $this->include('admin/footer') ?>
<?php echo $this->endSection(); ?>