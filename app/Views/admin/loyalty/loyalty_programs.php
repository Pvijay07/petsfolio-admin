<?= $this->extend ( 'admin/includes/header' ) ?>

<?= $this->section ( 'content' ) ?>

<div class="content-inner container-fluid pb-0" id="page_layout">
    <div class="row">
        <!-- Milestones Config -->
        <div class="col-lg-12 mb-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div
                    class="card-header d-flex justify-content-between align-items-center bg-transparent border-0 pt-4 px-4">
                    <div class="header-title">
                        <h4 class="card-title fw-bold text-primary"><i class="fas fa-flag-checkered me-2"></i>Milestone
                            Configuration</h4>
                        <p class="text-muted mb-0">Set points required and spins reward for each milestone level.</p>
                    </div>
                    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal"
                        data-bs-target="#milestoneModal"><i class="fas fa-plus me-2"></i>Add Milestone</button>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <!-- <th>Level</th> -->
                                    <th>Milestone Name</th>
                                    <th>Points Required</th>
                                    <!-- <th>Spins Reward</th> -->
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ( $milestones as $m ) : ?>
                                    <tr>
                                        <!-- <td><span class="badge bg-soft-primary text-primary px-3 rounded-pill">Lvl
                                            </span></td> -->
                                        <td class="fw-bold">
                                            <?= $m['tier_name'] ?>
                                        </td>
                                        <td><span class="text-dark fw-bold">
                                                <?= number_format ( $m['points_required'] ) ?> pts
                                            </span></td>
                                        <!-- <td><span class="badge bg-light text-dark border">
                                                <?= $m['spin_enabled'] ?> Spins
                                            </span></td> -->
                                        <td><span class="status-badge status-active">Active</span></td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-icon btn-soft-primary edit-milestone"
                                                    data-id="<?= $m['id'] ?>" data-name="<?= $m['tier_name'] ?>"
                                                    data-points="<?= $m['points_required'] ?>"
                                                    data-spins="<?= $m['spin_enabled'] ?>" title="Edit Milestone"><i
                                                        class="fas fa-edit"></i></button>
                                                <a href="<?= base_url ( 'loyalty-admin/deleteMilestone/' . $m['id'] ) ?>"
                                                    class="btn btn-sm btn-icon btn-soft-danger"
                                                    onclick="return confirm('Are you sure?')"><i
                                                        class="fas fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reward Pool Management -->
        <div class="col-lg-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div
                    class="card-header d-flex justify-content-between align-items-center bg-transparent border-0 pt-4 px-4">
                    <div class="header-title">
                        <h4 class="card-title fw-bold text-primary"><i class="fas fa-gift me-2"></i>Reward Distribution
                            Pool</h4>
                        <p class="text-muted mb-0">Manage items and probabilities for each milestone's spins.</p>
                    </div>
                    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal"
                        data-bs-target="#rewardModal"><i class="fas fa-plus me-2"></i>Add
                        Reward</button>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Reward Item</th>
                                    <th>Milestone</th>
                                    <th>Type</th>
                                    <th>Points Required</th>
                                    <!-- <th>Probability</th> -->
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ( $rewards as $r ) : ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="avatar-40 rounded-circle bg-soft-secondary d-flex align-items-center justify-content-center me-3 overflow-hidden">
                                                    <?php if ( !empty ( $r['image'] ) ) : ?>
                                                        <img src="<?= base_url ( $r['image'] ) ?>" class="img-fluid"
                                                            alt="reward">
                                                    <?php else : ?>
                                                        <i class="fas fa-gem text-secondary"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <span class="fw-bold text-dark">
                                                    <?= $r['reward_name'] ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <?= $r['tier_name'] ?>
                                        </td>
                                        <td><span class="badge bg-light text-muted border text-capitalize">
                                                <?= $r['reward_type'] ?>
                                            </span></td>
                                        <td><span class="text-dark fw-bold">
                                                <?= number_format ( $r['points_required'] ) ?> pts
                                            </span></td>
                                        <!-- <td>
                                            <div class="d-flex align-items-center" style="min-width: 150px;">
                                                <div class="progress w-100 me-2" style="height: 6px;">
                                                    <div class="progress-bar bg-primary"
                                                        style="width: <?= $r['probability'] ?>%"></div>
                                                </div>
                                                <span class="text-muted fw-bold">
                                                    <?= $r['probability'] ?>%
                                                </span>
                                            </div>
                                        </td> -->
                                        <td>
                                            <div class="form-check form-switch p-0" style="min-height: auto;">
                                                <input class="form-check-input ms-0" type="checkbox" <?= $r['is_active'] ? 'checked' : '' ?>
                                                    onclick="window.location.href='<?= base_url ( 'loyalty-admin/toggleReward/' . $r['id'] ) ?>'">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-icon btn-soft-primary edit-reward"
                                                    data-id="<?= $r['id'] ?>" data-name="<?= $r['reward_name'] ?>"
                                                    data-milestone="<?= $r['milestone_id'] ?>"
                                                    data-type="<?= $r['reward_type'] ?>"
                                                    data-value="<?= $r['reward_value'] ?>"
                                                    data-image="<?= $r['image'] ?>" data-active="<?= $r['is_active'] ?>"><i
                                                        class="fas fa-edit"></i></button>
                                                <a href="<?= base_url ( 'loyalty-admin/deleteReward/' . $r['id'] ) ?>"
                                                    class="btn btn-sm btn-icon btn-soft-danger"
                                                    onclick="return confirm('Are you sure?')"><i
                                                        class="fas fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Milestone Modal -->
<div class="modal fade" id="milestoneModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="<?= base_url ( 'loyalty-admin/saveMilestone' ) ?>" method="POST">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold" id="milestoneModalLabel">Add/Edit Milestone</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="id" id="milestone_id">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Milestone Name</label>
                        <input type="text" name="tier_name" id="tier_name" class="form-control rounded-3" required
                            placeholder="e.g. Bronze, Silver">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Points Required</label>
                        <input type="number" name="points_required" id="points_required" class="form-control rounded-3"
                            required placeholder="e.g. 500">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Spins Reward</label>
                        <input type="number" name="spin_enabled" id="spin_enabled" class="form-control rounded-3"
                            required placeholder="e.g. 1">
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-soft-secondary rounded-pill px-4"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Save Milestone</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reward Modal -->
<div class="modal fade" id="rewardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="<?= base_url ( 'loyalty-admin/saveReward' ) ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold" id="rewardModalLabel">Add/Edit Reward</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="id" id="reward_id">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Milestone</label>
                        <select name="milestone_id" id="reward_milestone_id" class="form-select rounded-3" required>
                            <?php foreach ( $milestones as $m ) : ?>
                                <option value="<?= $m['id'] ?>"><?= $m['tier_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Reward Name</label>
                        <input type="text" name="reward_name" id="reward_name" class="form-control rounded-3" required
                            placeholder="e.g. Free Spin, Gift Voucher">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Reward Type</label>
                        <select name="reward_type" id="reward_type" class="form-select rounded-3" required>
                            <option value="product">Product</option>
                            <option value="coupon">Coupon</option>
                            <option value="points">Points</option>
                        </select>
                    </div>
                    <div class="mb-3" id="reward_image_container">
                        <label class="form-label fw-bold">Reward Image (for Product/Coupon)</label>
                        <input type="file" name="reward_image" class="form-control rounded-3" accept="image/*">
                        <input type="hidden" name="existing_image" id="existing_image">
                        <div id="image_preview" class="mt-2" style="display:none;">
                            <img src="" id="reward_image_preview" class="img-thumbnail"
                                style="max-height: 100px; border-radius: 8px;">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Reward Value</label>
                        <input type="text" name="reward_value" id="reward_value" class="form-control rounded-3"
                            placeholder="e.g. 50 (for points) or Code (for coupon)">
                    </div>
                    <!-- <div class="mb-3">
                        <label class="form-label fw-bold">Probability (%)</label>
                        <input type="number" name="probability" id="probability" class="form-control rounded-3" required
                            min="0" max="100">
                    </div> -->
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active_check" value="1"
                            checked>
                        <label class="form-check-label px-2">Active</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-soft-secondary rounded-pill px-4"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Save Reward</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.8/js/bootstrap.min.js"></script>
<script>
    window.onload = function () {
        // Utility to get bootstrap safely
        function getBootstrap() {
            if (typeof window.bootstrap !== 'undefined') return window.bootstrap;
            if (typeof bootstrap !== 'undefined') return bootstrap;
            return null;
        }

        // Milestone Edit
        document.querySelectorAll('.edit-milestone').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('milestone_id').value = this.dataset.id;
                document.getElementById('tier_name').value = this.dataset.name;
                document.getElementById('points_required').value = this.dataset.points;
                document.getElementById('spin_enabled').value = this.dataset.spins;

                var b = getBootstrap();
                if (b) {
                    b.Modal.getOrCreateInstance(document.getElementById('milestoneModal')).show();
                } else {
                    console.error('Bootstrap JS not found. Please ensure it is loaded.');
                }
            });
        });

        // Reward Edit
        document.querySelectorAll('.edit-reward').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('reward_id').value = this.dataset.id;
                document.getElementById('reward_milestone_id').value = this.dataset.milestone;
                document.getElementById('reward_name').value = this.dataset.name;
                document.getElementById('reward_type').value = this.dataset.type;
                document.getElementById('reward_value').value = this.dataset.value;
                // document.getElementById('probability').value = this.dataset.probability;
                document.getElementById('is_active_check').checked = this.dataset.active == 1;
                document.getElementById('existing_image').value = this.dataset.image;

                if (this.dataset.image) {
                    document.getElementById('reward_image_preview').src = '<?= base_url () ?>/' + this.dataset.image;
                    document.getElementById('image_preview').style.display = 'block';
                } else {
                    document.getElementById('image_preview').style.display = 'none';
                }
                toggleRewardImage();

                var b = getBootstrap();
                if (b) {
                    b.Modal.getOrCreateInstance(document.getElementById('rewardModal')).show();
                } else {
                    console.error('Bootstrap JS not found. Please ensure it is loaded.');
                }
            });
        });

        // Reset modals on close
        var mModalEl = document.getElementById('milestoneModal');
        if (mModalEl) {
            var b = getBootstrap();
            if (b) {
                mModalEl.addEventListener('hidden.bs.modal', function () {
                    this.querySelector('form').reset();
                    document.getElementById('milestone_id').value = '';
                });
            }
        }

        var rModalEl = document.getElementById('rewardModal');
        if (rModalEl) {
            var b = getBootstrap();
            if (b) {
                rModalEl.addEventListener('hidden.bs.modal', function () {
                    this.querySelector('form').reset();
                    document.getElementById('reward_id').value = '';
                    document.getElementById('image_preview').style.display = 'none';
                    toggleRewardImage();
                });
            }
        }

        // --- CUSTOM JS FOR REWARD IMAGE ---
        const rTypeSelect = document.getElementById('reward_type');
        const rImageContainer = document.getElementById('reward_image_container');

        function toggleRewardImage() {
            const type = rTypeSelect.value;
            if (type === 'product' || type === 'coupon') {
                rImageContainer.style.display = 'block';
            } else {
                rImageContainer.style.display = 'none';
            }
        }

        if (rTypeSelect) {
            rTypeSelect.addEventListener('change', toggleRewardImage);
        }

        const rImageFileInp = document.querySelector('input[name="reward_image"]');
        if (rImageFileInp) {
            rImageFileInp.addEventListener('change', function () {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById('reward_image_preview').src = e.target.result;
                        document.getElementById('image_preview').style.display = 'block';
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
    };
</script>

<style>
    .bg-soft-primary {
        background: rgba(102, 126, 234, 0.1) !important;
    }

    .bg-soft-secondary {
        background: rgba(108, 117, 125, 0.1) !important;
    }

    .bg-soft-danger {
        background: rgba(245, 87, 108, 0.1) !important;
    }

    .avatar-40 {
        width: 40px;
        height: 40px;
    }

    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-active {
        background: #e8f5e9;
        color: #2e7d32;
    }
</style>

<?= $this->endSection () ?>