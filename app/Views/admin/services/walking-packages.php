<?= $this->extend('admin/includes/header'); ?>
<!--start content-->
<?= $this->section('content'); ?>

<main class="main-content">
    <div class="position-relative iq-banner">
        <!--Nav Start-->
        <?= $this->include('admin/nav_bar') ?>
    </div>
    
    <div class="conatiner-fluid content-inner pb-0" id="page_layout">
        <!-- Modern Header Section -->
        <div class="container-fluid p-0 mb-4">
            <div class="bg-gradient-primary text-white p-5 rounded-3 shadow position-relative overflow-hidden">
                <div class="position-relative z-1">
                    <h1 class="h2 fw-bold mb-2 text-white"><?= ucfirst($type) ?> Packages</h1>
                    <p class="text-white-75 mb-0">Manage service packages and pricing</p>
                </div>
                <i class="bi bi-box position-absolute end-0 bottom-0 opacity-10" style="font-size: 12rem; transform: rotate(12deg); margin-right: -2rem; margin-bottom: -2rem;"></i>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="input-group w-50 w-md-auto">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 ps-0" placeholder="Search..." id="searchInput">
                    </div>
                    <button class="btn btn-black bg-black text-white d-flex align-items-center gap-2 add" data-crud-id="0" data-bs-toggle="modal" data-bs-target="#addPackageModal">
                        <i class="bi bi-plus"></i>
                        New Package
                    </button>
                </div>
            </div>
        </div>

        <!-- Modern Table -->
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="" style="background-color: #c6d2ff!important;">
                        <tr>
                            <th class="ps-4 py-3 text-black fs-6 fw-semibold border-bottom" style="background: #c6d2ff!important;">Package Details</th>
                            <?php if ($service_id != '2'): ?>
                                <th class="py-3 text-black fs-6 fw-semibold border-bottom d-none d-md-table-cell" style="background: #c6d2ff!important;">Duration</th>
                            <?php endif; ?>
                            <th class="py-3 text-black fs-6 fw-semibold border-bottom" style="background: #c6d2ff!important;">Cost</th>
                            <?php if ($service_id != '2'): ?>
                                <th class="py-3 text-black fs-6 fw-semibold border-bottom d-none d-lg-table-cell" style="background: #c6d2ff!important;">Per Walk</th>
                            <?php endif; ?>
                            <?php if ($service_id == '2'): ?>
                                <th class="py-3 text-black fs-6 fw-semibold border-bottom d-none d-lg-table-cell" style="background: #c6d2ff!important;">Addons</th>
                            <?php endif; ?>
                            <th class="py-3 text-black fs-6 fw-semibold border-bottom" style="background: #c6d2ff!important;">Last Updated</th>
                            <th class="py-3 text-black fs-6 fw-semibold border-bottom text-center" style="background: #c6d2ff!important;">Status</th>
                            <th class="pe-4 py-3 text-black fs-6 fw-semibold border-bottom text-end" style="background: #c6d2ff!important;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php
                        $i = 1;
                        foreach ($packages as $package) {
                            $updatedDate = !empty($package->updated_at) ? date('d-m-Y', strtotime($package->updated_at)) : date('d-m-Y', strtotime($package->created_at));
                            $addons = !empty($package->included_addons) ? explode(',', $package->included_addons) : [];
                        ?>
                            <tr data-package-id="<?= $package->id ?>">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; border: 1px solid #e5dbff;">
                                            <?= strtoupper(substr($package->package_name, 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?= $package->package_name ?></div>
                                            <div class="text-muted small font-monospace mt-1">
                                                ID: #<?= $package->id ?>
                                            </div>
                                            <!-- Mobile: Hidden columns -->
                                            <?php if ($service_id != '2'): ?>
                                                <div class="d-md-none mt-2">
                                                    <span class="badge bg-light text-dark border small">
                                                        <i class="bi bi-calendar me-1"></i>
                                                        <?= $package->duration_days ?> days
                                                    </span>
                                                    <span class="badge bg-light text-dark border small">
                                                        <i class="bi bi-walk me-1"></i>
                                                        1 walk/day
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($service_id == '2' && !empty($addons)): ?>
                                                <div class="d-lg-none mt-2">
                                                    <span class="badge bg-light text-dark border small">
                                                        <i class="bi bi-plus-circle me-1"></i>
                                                        <?= count($addons) ?> addons
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <?php if ($service_id != '2'): ?>
                                    <td class="py-3 d-none d-md-table-cell">
                                        <div class="d-flex align-items-center gap-2 text-dark fw-medium">
                                            <i class="bi bi-calendar text-muted"></i>
                                            <span><?= $package->duration_days ?> days</span>
                                        </div>
                                    </td>
                                <?php endif; ?>
                                <td class="py-3">
                                    <div class="fw-bold text-dark fs-6">
                                        ₹<?= number_format($package->price, 2) ?>
                                    </div>
                                </td>
                                <?php if ($service_id != '2'): ?>
                                    <td class="py-3 d-none d-lg-table-cell">
                                        <div class="d-flex align-items-center gap-2 text-dark fw-medium">
                                            <i class="bi bi-walk text-muted"></i>
                                            <span>1 walk/day</span>
                                        </div>
                                    </td>
                                <?php endif; ?>
                                <?php if ($service_id == '2'): ?>
                                    <td class="py-3 d-none d-lg-table-cell">
                                        <?php if (!empty($addons)): ?>
                                            <div class="addons-list">
                                                <?php foreach (array_slice($addons, 0, 2) as $addon): ?>
                                                    <span class="badge bg-light text-dark border mb-1">
                                                        <?= $addon ?>
                                                    </span>
                                                <?php endforeach; ?>
                                                <?php if (count($addons) > 2): ?>
                                                    <span class="badge bg-light text-dark border">
                                                        +<?= count($addons) - 2 ?> more
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted small">No addons</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                                <td class="py-3">
                                    <div class="d-flex align-items-center gap-2 text-dark fw-medium">
                                        <i class="bi bi-calendar text-muted"></i>
                                        <span><?= $updatedDate ?></span>
                                    </div>
                                </td>
                                <td class="py-3 text-center">
                                    <div class="form-check form-switch d-inline-block">
                                        <input
                                            type="checkbox"
                                            data-url="<?= base_url('updateStatus') ?>"
                                            data-token="<?= csrf_hash() ?>"
                                            class="switch-status-change form-check-input status"
                                            id="datatable-row-<?= $package->id; ?>"
                                            name="status"
                                            value="<?= $package->status; ?>"
                                            data-id="<?= $package->id; ?>"
                                            data-module="packages"
                                            <?= $package->status == 1 ? 'checked' : ''; ?>
                                            style="width: 48px; height: 24px; cursor: pointer;">
                                    </div>
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" class="btn btn-outline-primary btn-sm edit-package" 
                                                data-id="<?= $package->id; ?>" 
                                                data-name="<?= $package->package_name; ?>"
                                                data-price="<?= $package->price; ?>" 
                                                data-status="<?= $package->status; ?>"
                                                data-days="<?= $package->duration_days; ?>"
                                                data-addons='<?= !empty($package->included_addons) ? htmlspecialchars(json_encode(explode(',', $package->included_addons))) : '[]' ?>'
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editPackageModal">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <a href="<?= base_url('deletePackage') ?>" 
                                           id="delete-package-<?= $package->id; ?>"
                                           data-id="<?= $package->id; ?>" 
                                           class="btn btn-outline-danger btn-sm" 
                                           data-type="ajax" 
                                           data-method="DELETE"
                                           data-token="<?= csrf_hash(); ?>" 
                                           data-bs-toggle="tooltip"
                                           data-confirm="Are you certain you want to delete this package?"
                                           aria-label="Delete">
                                            <i class="bi bi-trash"></i>
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
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4 px-2">
            <div class="text-muted small">
                Showing <?= count($packages) ?> entries
            </div>
            <div class="btn-group">
                <button class="btn btn-outline-primary btn-sm">Previous</button>
                <button class="btn btn-primary btn-sm">1</button>
                <button class="btn btn-outline-primary btn-sm">Next</button>
            </div>
        </div>
    </div>
</main>

<!-- Edit Package Modal - Bootstrap Modal -->
<div class="modal fade" id="editPackageModal" tabindex="-1" aria-labelledby="editPackageModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow p-0">
            <div class="modal-header" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);">
                <h4 class="modal-title mb-0 text-white">Edit Package</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editPackageForm" action="<?= base_url('updatePackage') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" id="editPackageId" name="packageId">
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="editPackageName" class="form-label text-muted small mb-1">Package Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-tag"></i>
                                </span>
                                <input type="text" id="editPackageName" name="packageName" class="form-control border-start-0" placeholder="Enter package name" required>
                            </div>
                        </div>
                        <?php if ($service_id != '2'): ?>
                            <div class="col-md-6">
                                <label for="editDuration" class="form-label text-muted small mb-1">Duration (Days)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-calendar"></i>
                                    </span>
                                    <input type="number" id="editDuration" name="duration" class="form-control border-start-0" min="1" placeholder="Enter days" required>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="editPrice" class="form-label text-muted small mb-1">Price (₹)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-currency-rupee"></i>
                                </span>
                                <input type="number" id="editPrice" name="price" class="form-control border-start-0" step="0.01" min="0" placeholder="Enter price" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="editStatus" class="form-label text-muted small mb-1">Status</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-toggle-on"></i>
                                </span>
                                <select id="editStatus" name="status" class="form-control border-start-0" required>
                                    <option value="">Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($service_id == '2'): ?>
                        <div class="mb-4">
                            <label class="form-label text-muted small mb-1">Addons (comma separated)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-plus-circle"></i>
                                </span>
                                <textarea id="editAddons" name="addons[]" class="form-control border-start-0" rows="2" placeholder="Enter addons separated by commas"></textarea>
                            </div>
                            <div class="form-text text-muted small">Example: Vaccination, Grooming, Training</div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn bg-success text-white d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle"></i>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Package Modal - Bootstrap Modal -->
<div class="modal fade" id="addPackageModal" tabindex="-1" aria-labelledby="addPackageModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow p-0">
            <div class="modal-header" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);">
                <h4 class="modal-title mb-0 text-white">Add New Package</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="addPackageForm" action="<?= base_url('addPackage') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" id="service_id" name="service_id" value="<?= $service_id; ?>">
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="addPackageName" class="form-label text-muted small mb-1">Package Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-tag"></i>
                                </span>
                                <input type="text" id="addPackageName" name="packageName" class="form-control border-start-0" placeholder="Enter package name" required>
                            </div>
                        </div>
                        <?php if ($service_id != '2'): ?>
                            <div class="col-md-6">
                                <label for="addDuration" class="form-label text-muted small mb-1">Duration (Days)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-calendar"></i>
                                    </span>
                                    <input type="number" id="addDuration" name="duration" class="form-control border-start-0" min="1" placeholder="Enter days" required>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="addPrice" class="form-label text-muted small mb-1">Price (₹)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-currency-rupee"></i>
                                </span>
                                <input type="number" id="addPrice" name="price" class="form-control border-start-0" step="0.01" min="0" placeholder="Enter price" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="addStatus" class="form-label text-muted small mb-1">Status</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-toggle-on"></i>
                                </span>
                                <select id="addStatus" name="status" class="form-control border-start-0" required>
                                    <option value="">Select Status</option>
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($service_id == '2'): ?>
                        <div class="mb-4">
                            <label class="form-label text-muted small mb-1">Addons (comma separated)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-plus-circle"></i>
                                </span>
                                <textarea id="addAddons" name="addons[]" class="form-control border-start-0" rows="2" placeholder="Enter addons separated by commas"></textarea>
                            </div>
                            <div class="form-text text-muted small">Example: Vaccination, Grooming, Training</div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn bg-success text-white d-flex align-items-center gap-2">
                            <i class="bi bi-plus-circle"></i>
                            Add Package
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->include('admin/footer') ?>

<style>
    /* Prevent body scroll when modal is open */
    body.modal-open {
        overflow: hidden !important;
        padding-right: 0 !important;
        position: fixed !important;
        width: 100% !important;
    }

    /* Prevent scrolling on mobile */
    @media (max-width: 768px) {
        body.modal-open {
            position: fixed !important;
            width: 100% !important;
            height: 100% !important;
            touch-action: none !important;
        }
        
        .modal-dialog {
            margin: 0.5rem !important;
            max-height: calc(100vh - 1rem) !important;
        }
    }

    /* Modal backdrop */
    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.7) !important;
        backdrop-filter: blur(2px);
    }

    /* Modal scrollable content */
    .modal-dialog-scrollable .modal-content {
        max-height: calc(100vh - 2rem);
        display: flex;
        flex-direction: column;
    }

    .modal-dialog-scrollable .modal-body {
        overflow-y: auto;
        flex: 1;
        min-height: 0;
    }

    /* Custom scrollbar for modal body */
    .modal-body::-webkit-scrollbar {
        width: 6px;
    }
    
    .modal-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .modal-body::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    .modal-body::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

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
    }
    
    .table tbody tr {
        transition: all 0.2s ease;
    }
    
    .table tbody tr:hover {
        background-color: rgba(99, 102, 241, 0.03) !important;
    }
    
    .modal-content {
        border-radius: 16px !important;
        border: none;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .badge {
        padding: 0.5rem 1rem !important;
        font-weight: 600 !important;
    }
    
    .input-group-text {
        background-color: #f9fafb !important;
        border-color: #e5e7eb !important;
    }
    
    .form-control:focus {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25) !important;
    }
    
    .btn-outline-primary {
        border-color: #dee2e6;
        color: #6c757d;
    }
    
    .btn-outline-primary:hover {
        border-color: #6366f1;
        color: #6366f1;
        background-color: transparent;
    }
    
    /* Form styling */
    .form-label {
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    /* Addons list styling */
    .addons-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
    }
    
    .addons-list .badge {
        padding: 0.25rem 0.5rem !important;
        font-size: 0.75rem;
    }
    
    /* Toggle switch styling */
    .form-check-input:checked {
        background-color: #10b981 !important;
        border-color: #10b981 !important;
    }
    
    .form-check-input:focus {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25) !important;
    }
    
    /* Responsive adjustments */
    @media (max-width: 992px) {
        .table-responsive {
            overflow-x: hidden;
        }
        
        .table {
            width: 100%;
        }
        
        .table thead th,
        .table tbody td {
            padding: 0.75rem 0.5rem;
        }
        
        .badge {
            padding: 0.4rem 0.75rem !important;
            font-size: 0.75rem;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
        
        .fs-6 {
            font-size: 0.9rem !important;
        }
    }
    
    @media (max-width: 768px) {
        .modal-dialog {
            margin: 0.5rem !important;
            max-width: calc(100% - 1rem) !important;
        }
        
        .card-body.p-3 {
            padding: 1rem !important;
        }
        
        .input-group.w-50 {
            width: 100% !important;
            margin-bottom: 1rem;
        }
        
        .d-flex.justify-content-between.align-items-center {
            flex-direction: column;
            align-items: stretch !important;
            gap: 1rem;
        }
        
        .btn.btn-primary.d-flex.align-items-center.gap-2 {
            width: 100%;
            justify-content: center;
        }
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">


<script>
    $(document).ready(function() {
        // Search functionality
        $('#searchInput').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('tbody tr').each(function() {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.includes(searchTerm));
            });
        });

        // Status toggle functionality
        $(document).on('change', '.switch-status-change', function() {
            const isChecked = $(this).is(':checked');
            const packageId = $(this).data('id');
            const url = $(this).data('url');
            const token = $(this).data('token');
            
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    id: packageId,
                    status: isChecked ? 1 : 0,
                    module: 'packages',
                    <?= csrf_token() ?>: token
                },
                success: function(response) {
                    if (response.status === true) {
                        toastr.success('Status updated successfully');
                    } else {
                        toastr.error('Failed to update status');
                        $(this).prop('checked', !isChecked);
                    }
                },
                error: function() {
                    toastr.error('Error updating status');
                    $(this).prop('checked', !isChecked);
                }
            });
        });

        // Delete functionality
        $(document).on('click', 'a[data-type="ajax"]', function(e) {
            e.preventDefault();
            
            const url = $(this).attr('href');
            const method = $(this).data('method');
            const token = $(this).data('token');
            const packageId = $(this).data('id');
            const confirmMsg = $(this).data('confirm');
            
            if (confirm(confirmMsg || 'Are you sure you want to delete this item?')) {
                $.ajax({
                    url: url,
                    type: method,
                    data: {
                        id: packageId,
                        <?= csrf_token() ?>: token
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Package deleted successfully');
                            $(`tr[data-package-id="${packageId}"]`).fadeOut(300, function() {
                                $(this).remove();
                            });
                        } else {
                            toastr.error(response.message || 'Failed to delete package');
                        }
                    },
                    error: function() {
                        toastr.error('Error deleting package');
                    }
                });
            }
        });

        // Load data into edit modal when shown
        $('#editPackageModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget); // Button that triggered the modal
            const id = button.data('id');
            const name = button.data('name');
            const price = button.data('price');
            const status = button.data('status');
            const days = button.data('days');
            const addons = button.data('addons');
            
            $('#editPackageId').val(id);
            $('#editPackageName').val(name);
            $('#editPrice').val(price);
            $('#editStatus').val(status);
            
            <?php if ($service_id != '2'): ?>
                $('#editDuration').val(days);
            <?php endif; ?>
            
            <?php if ($service_id == '2'): ?>
                if (addons && addons.length > 0) {
                    $('#editAddons').val(Array.isArray(addons) ? addons.join(', ') : addons);
                } else {
                    $('#editAddons').val('');
                }
            <?php endif; ?>
        });

        // Reset add modal when shown
        $('#addPackageModal').on('show.bs.modal', function() {
            $('#addPackageForm')[0].reset();
            $('#addStatus').val('1'); // Set default to active
        });

        // Form submission - Edit
        $('#editPackageForm').on('submit', function(e) {
            e.preventDefault();
            
            const formData = $(this).serialize();
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    console.log(response);
                    if (response.status === true) {
                        toastr.success('Package updated successfully');
                        $('#editPackageModal').modal('hide');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        toastr.error(response.message || 'Failed to update package');
                    }
                },
                error: function() {
                    toastr.error('Error updating package');
                }
            });
        });

        // Form submission - Add
        $('#addPackageForm').on('submit', function(e) {
            e.preventDefault();
            
            const formData = $(this).serialize();
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        toastr.success('Package added successfully');
                        $('#addPackageModal').modal('hide');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        toastr.error(response.message || 'Failed to add package');
                    }
                },
                error: function() {
                    toastr.error('Error adding package');
                }
            });
        });

        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>

<?= $this->endSection() ?>