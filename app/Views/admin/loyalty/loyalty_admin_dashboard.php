<?= $this->extend ( 'admin/includes/header' ) ?>

<?= $this->section('content') ?>

<div class="content-inner container-fluid pb-0" id="page_layout">
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-0 pt-4 px-4">
                    <div class="header-title">
                        <h4 class="card-title fw-bold text-primary">
                            <i class="fas fa-crown me-2"></i>Loyalty Management
                        </h4>
                        <p class="text-muted mb-0">Monitor user points and manage reward deliveries.</p>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Stats Overview -->
                    <div class="row g-4 mb-5">
                        <div class="col-md-4">
                            <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h3 class="text-white-50">Active Point Holders</h3>
                                <div class="stat-value text-white"><?= count($users) ?></div>
                                <div class="stat-trend text-white-50">Across all tiers</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <h3 class="text-white-50">Pending Rewards</h3>
                                <div class="stat-value text-white"><?= $pending ?></div>
                                <div class="stat-trend text-white-50">Awaiting delivery</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                                <h3 class="text-white-50">Delivered Rewards</h3>
                                <div class="stat-value text-white"><?= $delivered ?></div>
                                <div class="stat-trend text-white-50">Successfully fulfilled</div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs for Navigation -->
                    <ul class="nav nav-pills mb-4" id="loyaltyTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active rounded-pill px-4" id="users-tab" data-bs-toggle="pill" data-bs-target="#users-panel" type="button">
                                <i class="fas fa-users me-2"></i>Users with Points
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- 1. Users with Points Section -->
                        <div class="tab-pane fade show active" id="users-panel">
                            <div class="table-container shadow-sm rounded-4 overflow-hidden border">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>User Name</th>
                                            <th>Points</th>
                                            <th>Source</th>
                                            <th>Service Amt</th>
                                            <th>Earned Date</th>
                                            <th>Exp Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($users)): ?>
                                            <tr><td colspan="7" class="text-center py-5">No users found with loyalty points.</td></tr>
                                        <?php else: foreach($users as $user): ?>
                                            <tr>
                                                <td class="fw-bold text-dark"><?= $user['name'] ?></td>
                                                <td><span class="badge bg-soft-primary text-primary fs-6 px-3"><?= number_format($user['total_points']) ?> pts</span></td>
                                                <td><?= $user['tier'] ?? 'Standard' ?></td>
                                                <td>₹<?= number_format($user['lifetime_points']) ?></td>
                                                <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                                                <td><?= date('d M Y', strtotime(($user['updated_at'] ?? $user['created_at']) . ' + 365 days')) ?></td>
                                                <td><span class="status-badge status-active"><?= $user['tier'] ?? 'Bronze' ?> Member</span></td>
                                            </tr>
                                        <?php endforeach; endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Update Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="<?= base_url('loyalty-admin/updateStatus') ?>" method="POST">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold">Update Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="id" id="order_id">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" id="order_status_select" class="form-select rounded-3" required>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tracking URL</label>
                        <input type="url" name="tracking_url" id="order_tracking_url" class="form-control rounded-3" placeholder="https://tracking-link.com/...">
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-soft-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Update Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.8/js/bootstrap.min.js"></script>
<script>
window.onload = function() {
    // Utility to get bootstrap safely
    function getBootstrap() {
        if (typeof window.bootstrap !== 'undefined') return window.bootstrap;
        if (typeof bootstrap !== 'undefined') return bootstrap;
        return null;
    }

    // Order Update Modal Logic
    document.querySelectorAll('.update-order-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('order_id').value = this.dataset.id;
            document.getElementById('order_status_select').value = this.dataset.status;
            document.getElementById('order_tracking_url').value = this.dataset.tracking || '';
            
            var b = getBootstrap();
            if (b) {
                b.Modal.getOrCreateInstance(document.getElementById('orderModal')).show();
            } else {
                console.error('Bootstrap JS not found. Please ensure it is loaded.');
            }
        });
    });
};
</script>
            </div>
        </div>
    </div>
</div>

<style>
    .stat-card {
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        text-align: left;
        transition: transform 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .nav-pills .nav-link.active {
        background: #667eea;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    .status-badge {
        padding: 5px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .status-active { background: #e8f5e9; color: #2e7d32; }
    .status-pending { background: #fff3e0; color: #f57c00; }
    .bg-soft-primary { background: #eef2ff; }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Handle URL fragment for tab switching (e.g. #orders)
        const hash = window.location.hash;
        if (hash) {
            const targetTab = document.querySelector(`button[data-bs-target="${hash}-panel"]`);
            if (targetTab) {
                const tab = new bootstrap.Tab(targetTab);
                tab.show();
            }
        }

        // Manual fallback for tab switching if needed
        const tabBtns = document.querySelectorAll('[data-bs-toggle="pill"]');
        tabBtns.forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('data-bs-target');
                
                // Remove active from all buttons in this group
                tabBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                // Hide all panes
                document.querySelectorAll('.tab-pane').forEach(p => {
                    p.classList.remove('show', 'active');
                });
                
                // Show target pane
                const targetPane = document.querySelector(targetId);
                if (targetPane) {
                    targetPane.classList.add('show', 'active');
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>
