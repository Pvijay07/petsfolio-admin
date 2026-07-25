<?php echo $this->extend('admin/includes/header'); ?>
<?php echo $this->section('content'); ?>

<main class="main-content">
    <div class="position-relative iq-banner">
        <?php echo $this->include('admin/nav_bar') ?>
    </div>

    <div class="conatiner-fluid content-inner pb-0 px-4" id="page_layout">
        <!-- Modern Header Section with Back Button -->
        <div class="container-fluid p-0 mb-4">
            <div class="bg-gradient-primary text-white p-3 rounded-3 shadow position-relative overflow-hidden">
                <div class="position-relative z-1">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <a href="<?= base_url('provider-statistics') ?>"
                                class="btn btn-light btn-sm rounded-pill px-3 py-2 d-flex align-items-center gap-2"
                                style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white;">
                                <i class="bi bi-arrow-left"></i>
                                <span>Back to Analytics</span>
                            </a>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-sm rounded-pill px-4 py-2" onclick="exportToPDF()"
                                style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white;">
                                <i class="bi bi-file-pdf me-2"></i>Export PDF
                            </button>
                        </div>
                    </div>
                    <h1 class="h2 fw-bold mb-2 text-white"><?= $service_name ?> Service Providers Details</h1>
                    <p class="text-white-75 mb-0">Comprehensive overview of <?= strtolower($service_name) ?> providers across all locations</p>
                </div>
                <i class="bi bi-person-walking position-absolute end-0 bottom-0 opacity-10"
                    style="font-size: 14rem; transform: rotate(12deg); margin-right: -2rem; margin-bottom: -2rem; color: rgba(255,255,255,0.1);"></i>
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border: 1px solid #e9ecef !important; background: linear-gradient(145deg, #ffffff 0%, #f8faff 100%);">
            <div class="card-header bg-transparent border-0">
                <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-bar-chart-line" style="color: #4361ee;"></i>
                    Key Performance Indicators
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4 mt-3">
                        <div class="card border-0 shadow-sm mb-0" style="border: 1px solid #e9ecef !important; background: white;">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted small">Avg. Profile Completion</span>
                                    <span class="badge px-3 py-2" style="background: #4361ee; color: white;"><?= $sp_stats['avg_completion'] ?>%</span>
                                </div>
                                <div class="progress mb-2" style="height: 8px; background: #e9ecef;">
                                    <div class="progress-bar" style="width: <?= $sp_stats['avg_completion'] ?>%; background: linear-gradient(90deg, #4361ee, #7209b7);"></div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">Total: <?= $sp_stats['total'] ?></small>
                                    <small class="text-muted">Active: <?= $sp_stats['active'] ?></small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mt-3">
                        <div class="card border-0 shadow-sm mb-0" style="border: 1px solid #e9ecef !important; background: white;">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted small">Avg. Rating</span>
                                    <span class="badge px-3 py-2" style="background: #06d6a0; color: white;"><?= $sp_stats['avg_rating'] ?>/5</span>
                                </div>
                                <div class="progress mb-2" style="height: 8px; background: #e9ecef;">
                                    <div class="progress-bar" style="width: <?= ($sp_stats['avg_rating'] / 5) * 100 ?>%; background: linear-gradient(90deg, #06d6a0, #1b9aaa);"></div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">5 Star: --</small>
                                    <small class="text-muted">Providers: <?= $sp_stats['total'] ?></small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mt-3">
                        <div class="card border-0 shadow-sm mb-0" style="border: 1px solid #e9ecef !important; background: white;">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted small">Active Rate</span>
                                    <?php
                                    $active_rate = $sp_stats['total'] > 0 ? round(($sp_stats['active'] / $sp_stats['total']) * 100) : 0;
                                    ?>
                                    <span class="badge px-3 py-2" style="background: #fb8b24; color: white;"><?= $active_rate ?>%</span>
                                </div>
                                <div class="progress mb-2" style="height: 8px; background: #e9ecef;">
                                    <div class="progress-bar" style="width: <?= $active_rate ?>%; background: linear-gradient(90deg, #fb8b24, #e36414);"></div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">Suspended: <?= $sp_stats['suspended'] ?></small>
                                    <small class="text-muted">Active: <?= $sp_stats['active'] ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                // Sort providers by rating and completion to get top ones
                $top_sp = $sp;
                usort($top_sp, function ($a, $b) {
                    return ($b->rating <=> $a->rating) ?: ($b->completionPercentage <=> $a->completionPercentage);
                });
                $top_sp = array_slice($top_sp, 0, 3);
                $first_state = !empty($sp_stats['states']) ? array_key_first($sp_stats['states']) : '';
                ?>
                <h5 class="my-3 d-flex align-items-center gap-2">
                    <i class="bi bi-trophy-fill" style="color: #ffb703;"></i>
                    Top <?= $service_name ?>s in <?= $first_state ?>
                </h5>
                <div class="row g-4">
                    <?php foreach ($top_sp as $index => $provider):
                        $colors = [
                            ['bg' => 'linear-gradient(135deg, #4361ee, #7209b7)', 'light' => '#f0f4ff'],
                            ['bg' => 'linear-gradient(135deg, #06d6a0, #1b9aaa)', 'light' => '#f0fff4'],
                            ['bg' => 'linear-gradient(135deg, #fb8b24, #e36414)', 'light' => '#fff4f0']
                        ];
                        $color = $colors[$index] ?? $colors[0];
                    ?>
                        <div class="col-md-4">
                            <div class="card border-0 mb-0 shadow-sm" style="border: 1px solid #e9ecef !important; background: linear-gradient(145deg, #ffffff, <?= $color['light'] ?>);">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between gap-3">
                                        <div class="position-relative">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 45px; height: 45px; background: <?= $color['bg'] ?>;">
                                                <span class="text-white fw-bold fs-4">
                                                    <?php
                                                    $names = explode(' ', $provider->name);
                                                    echo strtoupper(substr($names[0], 0, 1) . (isset($names[1]) ? substr($names[1], 0, 1) : ''));
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <h5 class="fw-bold mb-1 text-truncate walker-name" style="color: #1e293b;"><?= $provider->name ?></h5>
                                            <p class="text-muted small mb-1"><?= $provider->city ?></p>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge px-3 py-1" style="background: #ffb703; color: #1e293b;">⭐ <?= number_format($provider->rating, 1) ?></span>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <small class="text-muted d-block">Completion</small>
                                            <span class="fw-bold fs-4" style="color: #4361ee;"><?= round($provider->completionPercentage) ?>%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="card" style="border: 1px solid #e9ecef !important;">
            <div class="card-body">
                <div class="mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h5 class="mb-0 d-flex align-items-center gap-2">
                            <i class="bi bi-map" style="color: #4361ee;"></i>
                            Location-wise Distribution - <span id="selectedStateName" style="color: #4361ee;"><?= $first_state ?></span>
                        </h5>
                        <div class="d-flex align-items-center gap-3">
                            <label for="stateSelector" class="fw-medium mb-0" style="color: #1e293b;">Select State:</label>
                            <select id="stateSelector"
                                class="form-select form-select-sm w-auto px-4 py-2 rounded-pill"
                                style="background: linear-gradient(145deg, #ffffff, #f8f9fa); cursor: pointer; min-width: 220px; border: 1px solid #e9ecef; color: #1e293b;">
                                <?php foreach ($sp_stats['states'] as $state_name => $state_data): ?>
                                    <option value="<?= strtolower(str_replace(' ', '_', $state_name)) ?>" data-name="<?= $state_name ?>" <?= $state_name == $first_state ? 'selected' : '' ?>>
                                        <?= $state_name ?> (<?= $state_data['count'] ?> <?= $service_name ?>s)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div id="stateContent">
                        <div class="row g-4" id="statesContainer">
                            <?php foreach ($sp_stats['states'] as $state_name => $state_data):
                                $state_id = strtolower(str_replace(' ', '_', $state_name));
                            ?>
                                <div class="state-data" id="<?= $state_id ?>Data" style="display: <?= $state_name == $first_state ? 'flex' : 'none' ?>; flex-wrap: wrap; width: 100%;">
                                    <?php foreach ($state_data['cities'] as $city_name => $city_data):
                                        $city_id = strtolower(str_replace(' ', '_', $city_name));
                                    ?>
                                        <div class="col-md-6 mb-4 px-2">
                                            <div class="city-card card border-0 shadow-sm h-100" style="border: 1px solid #e9ecef !important; border-radius: 12px; overflow: hidden;">
                                                <div class="card-header border-0 d-flex justify-content-between align-items-center p-3"
                                                    style="background: linear-gradient(135deg, #f8f9fa, #ffffff);">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                            style="width: 32px; height: 32px; background: rgba(67, 97, 238, 0.1); color: #4361ee;">
                                                            <i class="bi bi-building fs-6"></i>
                                                        </div>
                                                        <h6 class="fw-bold mb-0" style="color: #1e293b;"><?= $city_name ?></h6>
                                                    </div>
                                                    <span class="badge rounded-pill bg-primary px-3"><?= $city_data['count'] ?> <?= $service_name ?>s</span>
                                                </div>
                                                <div class="card-body p-0">
                                                    <table class="table table-hover mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th class="ps-3 border-0 small text-uppercase text-muted fw-bold">Area</th>
                                                                <th class="border-0 small text-uppercase text-muted fw-bold text-center">Active</th>
                                                                <th class="border-0 small text-uppercase text-muted fw-bold text-center">Susp.</th>
                                                                <th class="border-0 small text-uppercase text-muted fw-bold text-center">Avg. Rating</th>
                                                                <th class="pe-3 border-0 small text-uppercase text-muted fw-bold text-end">Details</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($city_data['areas'] as $area_name => $area_data):
                                                                $area_id = strtolower(str_replace(' ', '_', $area_name));
                                                            ?>
                                                                <tr>
                                                                    <td class="ps-3 py-3 fw-medium text-dark"><?= $area_name ?></td>
                                                                    <td class="py-3 text-center">
                                                                        <span class="text-success fw-bold"><?= $area_data['active'] ?></span>
                                                                    </td>
                                                                    <td class="py-3 text-center">
                                                                        <span class="text-danger"><?= $area_data['suspended'] ?></span>
                                                                    </td>
                                                                    <td class="py-3 text-center">
                                                                        <span class="badge bg-light text-dark border">⭐ <?= number_format($area_data['avg_rating'], 1) ?></span>
                                                                    </td>
                                                                    <td class="pe-3 py-3 text-end">
                                                                        <button class="btn btn-sm btn-icon expand-area" data-area-target="<?= $state_id ?>_<?= $city_id ?>_<?= $area_id ?>"
                                                                            style="background: rgba(67, 97, 238, 0.1); color: #4361ee; width: 28px; height: 28px; padding: 0;">
                                                                            <i class="bi bi-chevron-down"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                <!-- Expandable Area Detail -->
                                                                <tr class="area-detail-row" id="<?= $state_id ?>_<?= $city_id ?>_<?= $area_id ?>_Detail" style="display: none;">
                                                                    <td colspan="5" class="p-0 border-0">
                                                                        <div class="p-3 bg-light border-top">
                                                                            <div class="row g-3">
                                                                                <div class="col-md-6">
                                                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                                                        <div class="rounded-pill px-2 py-1 small fw-bold" style="background: rgba(6, 214, 160, 0.1); color: #06d6a0;">
                                                                                            Performance KPIs
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="small mb-1 d-flex justify-content-between">
                                                                                        <span>Profile Completion:</span>
                                                                                        <span class="fw-bold"><?= $area_data['avg_completion'] ?>%</span>
                                                                                    </div>
                                                                                    <div class="progress" style="height: 4px;">
                                                                                        <div class="progress-bar bg-success" style="width: <?= $area_data['avg_completion'] ?>%;"></div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                                                        <div class="rounded-pill px-2 py-1 small fw-bold" style="background: rgba(251, 139, 36, 0.1); color: #fb8b24;">
                                                                                            Top Provider in Area
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="d-flex align-items-center gap-2">
                                                                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white small" style="width: 24px; height: 24px;">?</div>
                                                                                        <span class="small fw-medium">Based on rating: <?= $area_data['avg_rating'] ?></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</main>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4361ee 0%, #7209b7 100%) !important;
    }

    .walker-name {
        max-width: 140px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .card {
        border-radius: 16px !important;
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
    }

    .table thead th {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding-top: 1rem;
        padding-bottom: 1rem;
        white-space: nowrap;
        font-weight: 600;
    }

    .table tbody td {
        vertical-align: middle;
        padding-top: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e9ecef;
    }

    .table-bordered {
        border-color: #e9ecef !important;
    }

    .table-bordered td,
    .table-bordered th {
        border: 1px solid #e9ecef !important;
    }

    .city-expandable {
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .city-expandable:hover {
        background: linear-gradient(145deg, #f8faff, #f0f4ff) !important;
    }

    .city-expandable .expand-icon {
        transition: transform 0.3s ease;
        color: #6c757d;
    }

    .city-expandable.expanded .expand-icon {
        transform: rotate(90deg);
        color: #4361ee;
    }

    .area-content {
        animation: fadeIn 0.3s ease;
        background: #f8faff !important;
        border-radius: 12px;
        margin: 8px;
    }

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

    .badge {
        font-weight: 500 !important;
        border-radius: 50px !important;
        padding: 0.35rem 0.8rem !important;
    }

    .progress {
        border-radius: 10px;
        background: #e9ecef;
    }

    .progress-bar {
        border-radius: 10px;
    }

    /* Dropdown styling */
    #stateSelector {
        min-width: 220px;
        font-weight: 500;
        border: 1px solid #e9ecef !important;
        background: white !important;
        color: #1e293b !important;
        cursor: pointer;
    }

    #stateSelector:focus {
        box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
        border-color: #4361ee !important;
    }

    #stateSelector option {
        background: white;
        color: #1e293b;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .city-table {
            min-width: 800px;
        }

        #stateSelector {
            min-width: 180px;
        }
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(145deg, #4361ee, #7209b7);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(145deg, #3a56d4, #6508a3);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // State selector change handler
        const stateSelector = document.getElementById('stateSelector');
        const selectedStateName = document.getElementById('selectedStateName');

        if (stateSelector) {
            stateSelector.addEventListener('change', function() {
                const stateId = this.value;
                const stateName = this.options[this.selectedIndex].getAttribute('data-name');

                if (selectedStateName) {
                    selectedStateName.textContent = stateName;
                }

                // Hide all states
                document.querySelectorAll('.state-data').forEach(el => {
                    el.style.display = 'none';
                });

                // Show selected state
                const selectedStateEl = document.getElementById(stateId + 'Data');
                if (selectedStateEl) {
                    selectedStateEl.style.display = 'flex';
                }
            });
        }

        // Expandable Area Details Logic
        document.querySelectorAll('.expand-area').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-area-target');
                const detailRow = document.getElementById(targetId + '_Detail');
                const icon = this.querySelector('i');

                if (detailRow) {
                    if (detailRow.style.display === 'none') {
                        detailRow.style.display = 'table-row';
                        icon.classList.remove('bi-chevron-down');
                        icon.classList.add('bi-chevron-up');
                        this.style.background = '#4361ee';
                        this.style.color = '#ffffff';
                    } else {
                        detailRow.style.display = 'none';
                        icon.classList.remove('bi-chevron-up');
                        icon.classList.add('bi-chevron-down');
                        this.style.background = 'rgba(67, 97, 238, 0.1)';
                        this.style.color = '#4361ee';
                    }
                }
            });
        });
    });

    function exportToPDF() {
        alert('PDF export functionality will be implemented here');
    }

    function printPage() {
        window.print();
    }
</script>
<style>

</style>

<?php echo $this->include('admin/footer') ?>
<?php echo $this->endSection(); ?>