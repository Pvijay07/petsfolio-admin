<?= $this->extend ( 'admin/includes/header' ) ?>

<?= $this->section ( 'content' ) ?>

<div class="content-inner container-fluid pb-0" id="page_layout">
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div
                    class="card-header d-flex justify-content-between align-items-center bg-transparent border-0 pt-4 px-4">
                    <div class="header-title">
                        <h4 class="card-title fw-bold text-primary"><i class="fas fa-cog me-2 text-dark"></i>Loyalty
                            Settings</h4>
                        <p class="text-muted mb-0">General configuration for the loyalty reward program.</p>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="<?= base_url ( 'loyalty-admin/saveSettings' ) ?>" method="POST">
                        <div class="row">
                            <!-- Points & Currency -->
                            <div class="col-md-12 mb-4">
                                <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-coins me-2"></i>Points & Currency
                                </h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Point Value (Rupees)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₹</span>
                                            <input type="number" name="point_value_rupees" class="form-control"
                                                value="<?= $config['point_value_rupees'] ?? 10 ?>"
                                                placeholder="e.g. 10">
                                        </div>
                                        <small class="text-muted">Conversion rate: 1 point = ₹X</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Points Expiry (Days)</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-times"></i></span>
                                            <input type="number" name="points_expiry_days" class="form-control"
                                                value="<?= $config['points_expiry_days'] ?? 365 ?>"
                                                placeholder="e.g. 365">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="mb-4">

                            <!-- Referral Settings -->
                            <div class="col-md-12 mb-4">
                                <h5 class="fw-bold mb-3 text-success"><i class="fas fa-users me-2"></i>Referral Program
                                </h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Referral Points Reward</label>
                                        <input type="number" name="referral_points" class="form-control"
                                            value="<?= $config['referral_points'] ?? 200 ?>" placeholder="e.g. 200">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Referral Condition</label>
                                        <input type="text" name="referral_condition" class="form-control"
                                            value="<?= $config['referral_condition'] ?? 'job_completion' ?>"
                                            placeholder="e.g. signup, first_purchase">
                                    </div>
                                </div>
                            </div>

                            <hr class="mb-4">

                            <!-- Membership Program -->
                            <div class="col-md-12 mb-4">
                                <h5 class="fw-bold mb-3 text-info"><i class="fas fa-id-card me-2"></i>Membership
                                    Settings</h5>
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-bold">Membership Price</label>
                                        <input type="number" name="membership_price" class="form-control"
                                            value="<?= $config['membership_price'] ?? 200 ?>">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-bold">Points Awarded</label>
                                        <input type="number" name="membership_points" class="form-control"
                                            value="<?= $config['membership_points'] ?? 200 ?>">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-bold">Discount %</label>
                                        <input type="number" name="membership_discount_percent" class="form-control"
                                            value="<?= $config['membership_discount_percent'] ?? 10 ?>">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-bold">Duration (Days)</label>
                                        <input type="number" name="membership_duration_days" class="form-control"
                                            value="<?= $config['membership_duration_days'] ?? 180 ?>">
                                    </div>
                                </div>
                            </div>

                            <hr class="mb-4">

                            <!-- Gamification & Logistics -->
                            <div class="col-md-12 mb-4">
                                <h5 class="fw-bold mb-3 text-warning"><i class="fas fa-gamepad me-2"></i>Rewards &
                                    Logistics</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">Spins per Milestone</label>
                                        <input type="number" name="spin_per_milestone" class="form-control"
                                            value="<?= $config['spin_per_milestone'] ?? 1 ?>">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">Default Delivery Detail (Days)</label>
                                        <input type="number" name="default_delivery_days" class="form-control"
                                            value="<?= $config['default_delivery_days'] ?? 3 ?>">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">Enable Spin Game</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="spin_enabled"
                                                value="1" <?= ( $config['spin_enabled'] ?? 1 ) ? 'checked' : '' ?>>
                                            <label class="form-check-label px-2">Active</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-4 text-end">
                                <button type="reset"
                                    class="btn btn-soft-secondary rounded-pill px-5 me-2">Cancel</button>
                                <button type="submit" class="btn btn-primary rounded-pill px-5 shadow"><i
                                        class="fas fa-save me-2"></i>Save Configuration</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .input-group-text {
        background: #f8f9fa;
        border-color: #dee2e6;
        color: #6c757d;
        border-radius: 12px 0 0 12px;
    }

    .form-control,
    .form-select {
        border-radius: 12px;
        border-color: #dee2e6;
        padding: 12px 18px;
        font-weight: 500;
    }

    .form-control:focus,
    .form-select:focus {
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        border-color: #667eea;
    }

    .btn-soft-secondary {
        background: rgba(108, 117, 125, 0.1);
        color: #6c757d;
        font-weight: 700;
    }

    .btn-soft-secondary:hover {
        background: rgba(108, 117, 125, 0.2);
    }
</style>

<?= $this->endSection () ?>