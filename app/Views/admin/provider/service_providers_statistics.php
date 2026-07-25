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
          <h1 class="h2 fw-bold mb-2 text-white">Service Providers Analytics</h1>
          <p class="text-white-75 mb-0">Comprehensive statistics and insights across all service types and locations</p>
        </div>
        <i class="bi bi-people position-absolute end-0 bottom-0 opacity-10"
          style="font-size: 12rem; transform: rotate(12deg); margin-right: -2rem; margin-bottom: -2rem;"></i>
      </div>
    </div>

    <!-- Overview Statistics -->
    <div class="row g-4 mb-4">
      <?php
      $total_walkers = $walker_stats['total'] ?? 0;
      $total_groomers = $groomer_stats['total'] ?? 0;
      $total_trainers = $trainer_stats['total'] ?? 0;
      $total_sp = $total_walkers + $total_groomers + $total_trainers;

      // Helper function to calculate stats
      function calculateSPStats($stats)
      {
        $total = $stats['total'] ?? 0;
        $avg_rating = 0;
        $avg_completion = 0;
        $active = 0;
        $suspended = 0;

        foreach ($stats['states'] ?? [] as $state) {
          foreach ($state['cities'] ?? [] as $city) {
            $avg_rating += $city['avg_rating'] * $city['count'];
            $avg_completion += $city['avg_completion'] * $city['count'];
            $active += $city['active'];
            $suspended += $city['suspended'];

            foreach ($city['areas'] ?? [] as $area) {
              $avg_rating += $area['avg_rating'] * $area['count'];
              $avg_completion += $area['avg_completion'] * $area['count'];
              $active += $area['active'];
              $suspended += $area['suspended'];
            }
          }
        }

        if ($total > 0) {
          $avg_rating = round($avg_rating / $total, 1);
          $avg_completion = round($avg_completion / $total, 1);
        }

        return compact('total', 'avg_rating', 'avg_completion', 'active', 'suspended');
      }

      $walker_calc = calculateSPStats($walker_stats);
      $groomer_calc = calculateSPStats($groomer_stats);
      $trainer_calc = calculateSPStats($trainer_stats);

      $active_sp = $walker_calc['active'] + $groomer_calc['active'] + $trainer_calc['active'];
      $suspended_sp = $walker_calc['suspended'] + $groomer_calc['suspended'] + $trainer_calc['suspended'];
      ?>

      <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div
                class="rounded-circle bg-primary bg-opacity-10 text-white d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-people fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Total Service Providers</p>
                <h3 class="mb-0"><?= number_format($total_sp) ?></h3>
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
              <div
                class="rounded-circle bg-success bg-opacity-10 text-white d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-check-circle fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Active Providers</p>
                <h3 class="mb-0"><?= number_format($active_sp) ?></h3>
              </div>
            </div>
            <div class="mt-3">
              <div class="progress" style="height: 6px;">
                <div class="progress-bar bg-success"
                  style="width: <?= $total_sp > 0 ? ($active_sp / $total_sp * 100) : 0 ?>%">
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
              <div
                class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-person-x fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Suspended Providers</p>
                <h3 class="mb-0"><?= number_format($suspended_sp) ?></h3>
              </div>
            </div>
            <div class="mt-3">
              <div class="progress" style="height: 6px;">
                <div class="progress-bar bg-warning"
                  style="width: <?= $total_sp > 0 ? ($suspended_sp / $total_sp * 100) : 0 ?>%">
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
              <div
                class="rounded-circle bg-info bg-opacity-10 text-white d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-star fs-4"></i>
              </div>
              <div class="ms-3">
                <p class="text-muted small mb-1">Avg. Rating</p>
                <h3 class="mb-0">
                  <?php
                  $total_rating = ($walker_calc['avg_rating'] * $walker_calc['total']) +
                    ($groomer_calc['avg_rating'] * $groomer_calc['total']) +
                    ($trainer_calc['avg_rating'] * $trainer_calc['total']);
                  $avg_rating = $total_sp > 0 ? round($total_rating / $total_sp, 1) : 0;
                  echo $avg_rating;
                  ?>
                </h3>
              </div>
            </div>
            <div class="mt-3">
              <div class="progress" style="height: 6px;">
                <div class="progress-bar bg-info" style="width: <?= ($avg_rating / 5) * 100 ?>%"></div>
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
                  Service Type Distribution
                </h5>
                <p class="text-muted small mb-0">Number of providers by service type</p>
              </div>
              <div class="card-body p-4 pt-0">
                <div class="chart-container" style="position: relative; height: 300px;">
                  <canvas id="serviceTypeChart"></canvas>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-header bg-transparent border-0 p-4">
                <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                  <i class="bi bi-pie-chart-fill text-white"></i>
                  Status Overview
                </h5>
                <p class="text-muted small mb-0">Active vs Suspended providers</p>
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
              Service Type Performance
            </h5>
            <p class="text-muted small mb-0">Based on average ratings</p>
          </div>
          <div class="card-body p-4 pt-0">
            <div class="list-group list-group-flush">
              <?php
              $service_types = [
                [
                  'name' => 'Walkers',
                  'id' => 4,
                  'total' => $walker_calc['total'],
                  'avg_rating' => $walker_calc['avg_rating'],
                  'avg_completion' => $walker_calc['avg_completion'],
                  'active' => $walker_calc['active'],
                  'icon' => 'bi-person-walking'
                ],
                [
                  'name' => 'Groomers',
                  'id' => 2,
                  'total' => $groomer_calc['total'],
                  'avg_rating' => $groomer_calc['avg_rating'],
                  'avg_completion' => $groomer_calc['avg_completion'],
                  'active' => $groomer_calc['active'],
                  'icon' => 'bi-scissors'
                ],
                [
                  'name' => 'Trainers',
                  'id' => 3,
                  'total' => $trainer_calc['total'],
                  'avg_rating' => $trainer_calc['avg_rating'],
                  'avg_completion' => $trainer_calc['avg_completion'],
                  'active' => $trainer_calc['active'],
                  'icon' => 'bi-award'
                ]
              ];

              usort($service_types, function ($a, $b) {
                return $b['avg_rating'] <=> $a['avg_rating'];
              });

              foreach ($service_types as $index => $type):
              ?>
                <div class="list-group-item border-0 px-0 py-3">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                      <div
                        class="rounded-circle bg-primary bg-opacity-10 text-white d-flex align-items-center justify-content-center"
                        style="width: 36px; height: 36px;">
                        <i class="bi <?= $type['icon'] ?>"></i>
                      </div>
                      <div>
                        <h6 class="mb-1"><?= $type['name'] ?></h6>
                        <small class="text-muted"><?= number_format($type['total']) ?> providers</small>
                      </div>
                    </div>
                    <div class="text-end">
                      <div class="fw-bold text-white"><?= $type['avg_rating'] ?>/5</div>
                      <small class="text-muted">Rating</small>
                    </div>
                  </div>
                  <div class="mt-2">
                    <div class="d-flex justify-content-between small text-muted">
                      <span>Active: <?= number_format($type['active']) ?></span>
                      <span>Profile: <?= $type['avg_completion'] ?>%</span>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Service Providers Table -->
    <div class="card border-0 shadow-sm overflow-hidden">
      <div class="card-header bg-transparent border-0 p-4">
        <h4 class="card-title mb-0 d-flex align-items-center gap-2">
          <i class="bi bi-table text-primary"></i>
          Service Providers Statistics
        </h4>
        <p class="text-muted small mb-0">Click on any service type to view detailed statistics</p>
      </div>
      <div class="card-body p-0 pt-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0" id="spTable" style="table-layout: fixed; width: 100%;">
            <thead style="background-color: #c6d2ff!important;">
              <tr>
                <th width="150" class="ps-4 py-3 text-black fw-semibold border-bottom fs-6"
                  style="background: #c6d2ff!important;">
                  Service Type
                </th>
                <th width="100" class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                  style="background: #c6d2ff!important;">
                  Total Providers
                </th>
                <th width="100" class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                  style="background: #c6d2ff!important;">
                  Active
                </th>
                <th width="100" class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                  style="background: #c6d2ff!important;">
                  Suspended
                </th>
                <th width="100" class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                  style="background: #c6d2ff!important;">
                  Avg. Rating
                </th>
                <th width="100" class="py-3 text-black fw-semibold border-bottom text-center fs-6"
                  style="background: #c6d2ff!important;">
                  Profile Completion
                </th>
                <th width="100" class="pe-4 py-3 text-black fw-semibold border-bottom text-center fs-6"
                  style="background: #c6d2ff!important;">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($service_types as $type): ?>
                <tr data-service-type="<?= strtolower($type['name']) ?>">
                  <td class="py-3 text-center" style="font-size: 16px;">
                    <span class="fw-bold text-dark"><?= $type['name'] ?></span>
                  </td>
                  <td class="py-3 text-center" style="font-size: 16px;">
                    <div class="fw-medium"><?= number_format($type['total']) ?></div>
                  </td>
                  <td class="py-3 text-center" style="font-size: 16px;">
                    <span
                      class="badge bg-success bg-opacity-10 text-white border border-success border-opacity-25 px-3 py-1">
                      <?= number_format($type['active']) ?>
                    </span>
                  </td>
                  <td class="py-3 text-center" style="font-size: 16px;">
                    <span
                      class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-1">
                      <?= number_format($type['total'] - $type['active']) ?>
                    </span>
                  </td>
                  <td class="py-3 text-center" style="font-size: 16px;">
                    <div class="fw-medium"><?= $type['avg_rating'] ?>/5</div>
                  </td>
                  <td class="py-3 text-center" style="font-size: 16px;">
                    <span class="badge bg-info bg-opacity-10 text-white border border-info border-opacity-25 px-3 py-1">
                      <?= $type['avg_completion'] ?>%
                    </span>
                  </td>
                  <td class="pe-4 py-3 text-center">
                    <button class="btn btn-sm btn-outline-primary view-details d-flex align-items-center gap-1 px-2"
                      data-service-id="<?= $type['id'] ?>" data-service-name="<?= $type['name'] ?>">
                      <i class="bi bi-eye fs-6"></i>
                      <span class="d-none d-md-inline">View</span>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Details Modal - Modern Tabbed Interface -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
    <div class="modal-content border-0 shadow-lg p-0">
      <div class="modal-header bg-primary text-white border-0">
        <h5 class="modal-title text-white fw-bold" id="modalServiceName">Service Providers Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-0">
        <!-- State Tabs Navigation -->
        <div class="border-bottom bg-light">
          <div class="nav nav-tabs border-0 px-4 py-3 mb-0" id="stateTabs" role="tablist"
            style="overflow-x: auto; flex-wrap: nowrap;">
            <!-- State tabs will be loaded here -->
          </div>
        </div>

        <!-- Tab Content -->
        <div class="tab-content p-3" id="stateTabContent" style="max-height: 500px; overflow-y: auto;">
          <!-- Content will be loaded dynamically -->
        </div>
      </div>

      <div class="modal-footer border-top bg-light py-3">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
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

  #spTable {
    table-layout: fixed !important;
    width: 100% !important;
  }

  /* Modal Styling */
  .modal.fade .modal-dialog {
    transform: translate(0, -50px) scale(0.95);
    transition: transform 0.3s ease-out;
  }

  .modal.show .modal-dialog {
    transform: translate(0, 0) scale(1);
  }

  .modal-content {
    border-radius: 16px !important;
    overflow: hidden;
    border: none;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  }

  .modal-backdrop.show {
    opacity: 0.5;
  }

  body.modal-open {
    overflow: hidden;
    padding-right: 0 !important;
  }

  /* Modal Tab Styling */
  #stateTabs {
    border-bottom: none;
    gap: 8px;
  }

  #stateTabs .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    border-radius: 50px;
    padding: 12px 20px;
    color: #6c757d;
    font-weight: 500;
    background: transparent;
    transition: all 0.2s ease;
    white-space: nowrap;
    min-width: fit-content;
  }


  #stateTabs .nav-link.active {
    background-color: rgba(59, 130, 246, 0.1) !important;
    color: #2563eb !important;
    border: 1px solid rgba(59, 130, 246, 0.2) !important;
  }

  /* City Table Styling */
  .city-table {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  }

  .city-table thead th {
    background-color: #f8f9fa !important;
    color: #000;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 16px;
    border-bottom: 2px solid #dee2e6;
  }

  .city-table tbody td {
    padding: 12px 16px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f3f4;
  }

  .city-table tbody tr:hover {
    background-color: #f8f9fa;
  }

  /* Expandable City Row */
  .city-expandable {
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .city-expandable:hover {
    background-color: rgba(99, 102, 241, 0.05) !important;
  }

  .city-expandable .expand-icon {
    transition: transform 0.3s ease;
    color: #6c757d;
  }

  .city-expandable.expanded .expand-icon {
    transform: rotate(90deg);
    color: #6366f1;
  }

  /* Area Table Styling */
  .area-table {
    background-color: #f8fafc;
    border-radius: 6px;
    margin: 8px 0;
  }

  .area-table th {
    background-color: #e9ecef !important;
    color: #495057;
    font-size: 0.8rem;
    padding: 8px 12px;
  }

  .area-table td {
    padding: 8px 12px;
    font-size: 0.9rem;
  }

  /* Scrollbar Styling */
  #stateTabContent::-webkit-scrollbar {
    width: 6px;
  }

  #stateTabContent::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
  }

  #stateTabContent::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
  }

  #stateTabContent::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
  }

  /* Responsive Design */
  @media (max-width: 768px) {
    #spTable {
      min-width: 800px;
    }

    .table-responsive {
      overflow-x: auto;
    }

    .btn span.d-none.d-md-inline {
      display: none !important;
    }

    .modal-dialog {
      margin: 0.5rem;
    }

    .modal-xl {
      max-width: calc(100% - 1rem);
    }

    #stateTabs {
      overflow-x: auto;
      padding-bottom: 8px;
    }

    #stateTabs .nav-link {
      padding: 8px 12px;
      font-size: 0.9rem;
    }

    .modal-body {
      padding: 0 !important;
    }

    #stateTabContent {
      max-height: 400px;
    }
  }

  @media (max-width: 576px) {
    .modal-dialog {
      margin: 0.25rem;
    }

    #stateTabContent {
      max-height: 350px;
      padding: 1rem !important;
    }
  }

  /* Animation for expanding rows */
  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .area-content {
    animation: fadeIn 0.3s ease;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Service Type Chart
    const serviceTypeCtx = document.getElementById('serviceTypeChart').getContext('2d');
    const serviceTypeChart = new Chart(serviceTypeCtx, {
      type: 'pie',
      data: {
        labels: ['Walkers', 'Groomers', 'Trainers'],
        datasets: [{
          data: [<?= $walker_calc['total'] ?>, <?= $groomer_calc['total'] ?>, <?= $trainer_calc['total'] ?>],
          backgroundColor: ['#3f30e3', '#5555fa', '#6c63ff'],
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
              label: function(context) {
                const label = context.label || '';
                const value = context.raw || 0;
                const total = <?= $total_sp ?>;
                const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                return `${label}: ${value} providers (${percentage}%)`;
              }
            }
          }
        }
      }
    });

    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
      type: 'doughnut',
      data: {
        labels: ['Active', 'Suspended'],
        datasets: [{
          data: [<?= $active_sp ?>, <?= $suspended_sp ?>],
          backgroundColor: ['#10b981', '#f59e0b'],
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

    // Prevent page scrolling when modal is open
    // const detailsModal = document.getElementById('detailsModal');
    // detailsModal.addEventListener('show.bs.modal', function () {
    //   document.body.style.overflow = 'hidden';
    //   document.body.style.paddingRight = '0';
    // });

    // detailsModal.addEventListener('hidden.bs.modal', function () {
    //   document.body.style.overflow = '';
    // });

    // Modal functionality
    const spData = {
      walkers: <?= json_encode($walker_stats) ?>,
      groomers: <?= json_encode($groomer_stats) ?>,
      trainers: <?= json_encode($trainer_stats) ?>
    };

    document.querySelectorAll('.view-details').forEach(button => {
      button.addEventListener('click', function() {
        const serviceId = this.getAttribute('data-service-id');
        window.location.href = `<?= base_url('providerDetailsStatistics') ?>/${serviceId}`;
      });
    });
  });
</script>

<?php echo $this->include('admin/footer') ?>
<?php echo $this->endSection(); ?>