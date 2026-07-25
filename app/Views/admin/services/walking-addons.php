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
                    <h1 class="h2 fw-bold mb-2 text-white"><?= ucfirst($type) ?> Addons</h1>
                    <p class="text-white-75 mb-0">Manage additional services and features</p>
                </div>
                <i class="bi bi-plus-circle position-absolute end-0 bottom-0 opacity-10"
                    style="font-size: 12rem; transform: rotate(12deg); margin-right: -2rem; margin-bottom: -2rem;"></i>
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
                        <input type="text" class="form-control border-start-0 ps-0" placeholder="Search..."
                            id="searchInput">
                    </div>
                    <button class="btn btn-black bg-black text-white d-flex align-items-center gap-2 add"
                        data-crud-id="0">
                        <i class="bi bi-plus"></i>
                        New Addon
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
                            <th class="ps-4 py-3 text-black fs-6 fw-semibold border-bottom"
                                style="background: #c6d2ff!important;">Addon Details</th>
                            <th class="py-3 text-black fs-6 fw-semibold border-bottom"
                                style="background: #c6d2ff!important;">Price</th>
                            <th class="py-3 text-black fs-6 fw-semibold border-bottom"
                                style="background: #c6d2ff!important;">Last Updated</th>
                            <th class="py-3 text-black fs-6 fw-semibold border-bottom text-center"
                                style="background: #c6d2ff!important;">Status</th>
                            <th class="pe-4 py-3 text-black fs-6 fw-semibold border-bottom text-end"
                                style="background: #c6d2ff!important;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php
                        $i = 1;
                        foreach ($addons as $addon) {
                            $updatedDate = !empty($addon->updated_at) ? date('d-m-Y', strtotime($addon->updated_at)) : date('d-m-Y', strtotime($addon->created_at));
                            ?>
                            <tr data-addon-id="<?= $addon->id ?>">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-white d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 40px; height: 40px; border: 1px solid #e5dbff;">
                                            <?= strtoupper(substr($addon->name, 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?= $addon->name ?></div>
                                            <div class="text-muted small font-monospace mt-1">
                                                ID: #<?= $addon->id ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="fw-bold text-dark fs-6">
                                        ₹<?= number_format($addon->price, 2) ?>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center gap-2 text-dark fw-medium">
                                        <i class="bi bi-calendar text-muted"></i>
                                        <span><?= $updatedDate ?></span>
                                    </div>
                                </td>
                                <td class="py-3 text-center">
                                    <div class="form-check form-switch d-inline-block">
                                        <input type="checkbox" data-url="<?= base_url('updateStatus') ?>"
                                            class="switch-status-change form-check-input status"
                                            id="datatable-row-<?= $addon->id; ?>" name="status"
                                            value="<?= $addon->status; ?>" data-id="<?= $addon->id; ?>" data-module="addons"
                                            <?= $addon->status == 1 ? 'checked' : ''; ?>
                                            style="width: 48px; height: 24px; cursor: pointer;">
                                    </div>
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" class="btn btn-outline-primary btn-sm open-edit-modal"
                                            data-id="<?= $addon->id; ?>"
                                            data-name="<?= htmlspecialchars($addon->name, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-price="<?= $addon->price; ?>" data-status="<?= $addon->status; ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm delete-addon"
                                            data-id="<?= $addon->id; ?>"
                                            data-name="<?= htmlspecialchars($addon->name, ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
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
                Showing <?= count($addons) ?> entries
            </div>
        </div>
    </div>
</main>

<!-- Edit Addon Modal -->
<div class="modal fade" id="editAddonModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-gradient-primary text-white border-0">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div>
                        <h5 class="modal-title text-white fw-bold">Edit Addon</h5>
                        <p class="mb-0 small opacity-75">Update addon information</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <form id="editAddonForm">
                <div class="modal-body p-4">
                    <?= csrf_field() ?>
                    <input type="hidden" id="editAddonId" name="addonId">

                    <div class="mb-3">
                        <label for="editAddonName" class="form-label fw-bold">Addon Name <span
                                class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-tag text-muted"></i>
                            </span>
                            <input type="text" id="editAddonName" name="addonName" class="form-control border-start-0"
                                placeholder="Enter addon name" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="editAddonPrice" class="form-label fw-bold">Price (₹) <span
                                class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-currency-rupee text-muted"></i>
                            </span>
                            <input type="number" id="editAddonPrice" name="addonPrice"
                                class="form-control border-start-0" step="0.01" min="0" placeholder="Enter price"
                                required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="editStatus" class="form-label fw-bold">Status</label>
                        <div class="d-flex align-items-center gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="editStatusActive"
                                    value="1">
                                <label class="form-check-label" for="editStatusActive">Active</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="editStatusInactive"
                                    value="0">
                                <label class="form-check-label" for="editStatusInactive">Inactive</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Addon Modal -->
<div class="modal fade" id="addAddonModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-gradient-primary text-white border-0">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div>
                        <h5 class="modal-title text-white fw-bold">Add New Addon</h5>
                        <p class="mb-0 small opacity-75">Create a new addon</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <form id="addAddonForm">
                <div class="modal-body p-4">
                    <?= csrf_field() ?>
                    <input type="hidden" id="service_id" name="service_id" value="<?= $service_id; ?>">

                    <div class="mb-3">
                        <label for="addAddonName" class="form-label fw-bold">Addon Name <span
                                class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-tag text-muted"></i>
                            </span>
                            <input type="text" id="addAddonName" name="addonName" class="form-control border-start-0"
                                placeholder="Enter addon name" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="addAddonPrice" class="form-label fw-bold">Price (₹) <span
                                class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-currency-rupee text-muted"></i>
                            </span>
                            <input type="number" id="addAddonPrice" name="addonPrice"
                                class="form-control border-start-0" step="0.01" min="0" placeholder="Enter price"
                                required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <div class="d-flex align-items-center gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="addStatusActive"
                                    value="1" checked>
                                <label class="form-check-label" for="addStatusActive">Active</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="addStatusInactive"
                                    value="0">
                                <label class="form-check-label" for="addStatusInactive">Inactive</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Addon</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                        style="width: 70px; height: 70px;">
                        <i class="bi bi-exclamation-triangle fs-1"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-2">Delete Addon?</h5>
                <p class="text-muted small mb-3" id="deleteMessage">Are you sure you want to delete this addon?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->include('admin/footer') ?>

<style>
    /* Gradient header */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
    }

    /* Card styling */
    .card {
        border-radius: 16px !important;
    }

    /* Table styling */
    .table thead th {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding-top: 1rem;
        padding-bottom: 1rem;
        border: none !important;
    }

    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: rgba(99, 102, 241, 0.03) !important;
    }

    .table td {
        border: none !important;
        border-bottom: 1px solid #e5e7eb !important;
        vertical-align: middle;
    }

    .table tbody tr:last-child td {
        border-bottom: none !important;
    }

    /* Modal styling */
    .modal-content {
        border-radius: 16px !important;
        border: none;
    }

    .modal-header.bg-gradient-primary {
        padding: 1.5rem;
    }

    /* Form styling */
    .form-label {
        font-weight: 500;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        color: #495057;
    }

    .input-group-text {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
    }

    .form-control {
        border-color: #dee2e6;
        font-size: 0.9rem;
        padding: 0.6rem 0.75rem;
    }

    .form-control:focus {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25) !important;
    }

    /* Status switch styling */
    .form-check-input:checked {
        background-color: #10b981 !important;
        border-color: #10b981 !important;
    }

    /* Button styling */
    .btn-outline-primary {
        border-color: #dee2e6;
        color: #6c757d;
    }

    .btn-outline-primary:hover {
        border-color: #6366f1;
        color: #6366f1;
        background-color: transparent;
    }

    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: white;
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }

    @media (max-width: 576px) {
        .d-flex.justify-content-between.align-items-center {
            flex-direction: column;
            align-items: stretch !important;
            gap: 1rem;
        }

        .input-group.w-50 {
            width: 100% !important;
        }

        .btn.btn-black {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $(document).ready(function () {
        // Toastr configuration
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };

        // ============================
        // 1. SEARCH FUNCTIONALITY
        // ============================
        $('#searchInput').on('keyup', function () {
            const searchTerm = $(this).val().toLowerCase();
            $('tbody tr').each(function () {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.includes(searchTerm));
            });
        });

        // ============================
        // 2. STATUS TOGGLE FUNCTIONALITY
        // ============================
        $(document).on('change', '.switch-status-change', function () {
            const $checkbox = $(this);
            const addonId = $checkbox.data('id');
            const url = $checkbox.data('url');
            const module = $checkbox.data('module');
            const status = $checkbox.is(':checked') ? 1 : 0;

            // Prevent double submission
            if ($checkbox.data('processing')) {
                return false;
            }

            $checkbox.data('processing', true);
            $checkbox.prop('disabled', true);

            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                },
                data: {
                    id: addonId,
                    status: status,
                    module: module
                },
                success: function (response) {
                    console.log(response)
                    if (response.status) {
                        toastr.success('Status updated successfully');
                    } else {
                        $checkbox.prop('checked', !$checkbox.is(':checked'));
                        toastr.error(response.message || 'Failed to update status');
                    }
                },
                error: function () {
                    $checkbox.prop('checked', !$checkbox.is(':checked'));
                    toastr.error('Error updating status');
                },
                complete: function () {
                    $checkbox.data('processing', false);
                    $checkbox.prop('disabled', false);
                }
            });
        });

        // ============================
        // 3. ADD ADDON FUNCTIONALITY
        // ============================
        $('.add').on('click', function () {
            // Reset form
            $('#addAddonForm')[0].reset();
            $('#addAddonForm input[name="status"][value="1"]').prop('checked', true);

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('addAddonModal'));
            modal.show();
        });

        $('#addAddonForm').on('submit', function (e) {
            e.preventDefault();

            const $form = $(this);
            const $submitBtn = $form.find('button[type="submit"]');
            const originalText = $submitBtn.html();

            $submitBtn.html('<i class="bi bi-spinner bi-spin"></i> Adding...').prop('disabled', true);

            $.ajax({
                url: '<?= base_url('addAddon') ?>',
                type: 'POST',
                data: $form.serialize(),
                success: function (response) {
                    console.log(response)
                    if (response.status) {
                        toastr.success('Addon added successfully');

                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('addAddonModal'));
                        modal.hide();

                        // Reload page after delay
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        toastr.error(response.message || 'Failed to add addon');
                    }
                },
                error: function () {
                    toastr.error('Error adding addon');
                },
                complete: function () {
                    $submitBtn.html(originalText).prop('disabled', false);
                }
            });
        });

        // ============================
        // 4. EDIT ADDON FUNCTIONALITY
        // ============================
        $(document).on('click', '.open-edit-modal', function () {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const price = $(this).data('price');
            const status = $(this).data('status');

            $('#editAddonId').val(id);
            $('#editAddonName').val(name);
            $('#editAddonPrice').val(price);

            // Set status radio
            if (status == 1) {
                $('#editStatusActive').prop('checked', true);
            } else {
                $('#editStatusInactive').prop('checked', true);
            }

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('editAddonModal'));
            modal.show();
        });

        $('#editAddonForm').on('submit', function (e) {
            e.preventDefault();

            const $form = $(this);
            const $submitBtn = $form.find('button[type="submit"]');
            const originalText = $submitBtn.html();

            $submitBtn.html('<i class="bi bi-spinner bi-spin"></i> Saving...').prop('disabled', true);

            $.ajax({
                url: '<?= base_url('updateAddon') ?>',
                type: 'POST',
                data: $form.serialize(),
                success: function (response) {
                    if (response.status) {
                        toastr.success('Addon updated successfully');

                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editAddonModal'));
                        modal.hide();

                        // Reload page after delay
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        toastr.error(response.message || 'Failed to update addon');
                    }
                },
                error: function () {
                    toastr.error('Error updating addon');
                },
                complete: function () {
                    $submitBtn.html(originalText).prop('disabled', false);
                }
            });
        });

        // ============================
        // 5. DELETE ADDON FUNCTIONALITY
        // ============================
        let deleteId = null;

        $(document).on('click', '.delete-addon', function () {
            deleteId = $(this).data('id');
            const name = $(this).data('name');

            $('#deleteMessage').html(`Are you sure you want to delete <strong>${name}</strong>?`);

            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        });

        $('#confirmDelete').on('click', function () {
            if (!deleteId) return;

            const $btn = $(this);
            const originalText = $btn.html();

            $btn.html('<i class="bi bi-spinner bi-spin"></i> Deleting...').prop('disabled', true);

            $.ajax({
                url: '<?= base_url('deleteAddon') ?>',
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                },
                data: {
                    id: deleteId,
                    _method: 'DELETE'
                },
                success: function (response) {
                    if (response.success) {
                        toastr.success('Addon deleted successfully');

                        // Remove row from table
                        $(`tr[data-addon-id="${deleteId}"]`).fadeOut(300, function () {
                            $(this).remove();
                        });

                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                        modal.hide();
                    } else {
                        toastr.error(response.message || 'Failed to delete addon');
                    }
                },
                error: function () {
                    toastr.error('Error deleting addon');
                },
                complete: function () {
                    $btn.html(originalText).prop('disabled', false);
                    deleteId = null;
                }
            });
        });

        // ============================
        // 6. PREVENT BODY SCROLL WHEN MODAL OPEN
        // ============================
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.addEventListener('show.bs.modal', function () {
                document.body.style.overflow = 'hidden';
                document.body.style.paddingRight = '0px';
            });

            modal.addEventListener('hidden.bs.modal', function () {
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            });
        });

        // ============================
        // 7. CLOSE MODAL ON BACKDROP CLICK
        // ============================
        $('.modal').on('click', function (e) {
            if ($(e.target).hasClass('modal')) {
                $(this).modal('hide');
            }
        });

        // ============================
        // 8. RESET FORMS ON MODAL CLOSE
        // ============================
        $('#addAddonModal, #editAddonModal').on('hidden.bs.modal', function () {
            $(this).find('form')[0].reset();
        });
    });
</script>

<?= $this->endSection() ?>