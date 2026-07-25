<?= $this->extend ( 'admin/includes/header' ) ?>

<?= $this->section('content') ?>

<div class="content-inner container-fluid pb-0" id="page_layout">
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-0 pt-4 px-4">
                    <div class="header-title">
                        <h4 class="card-title fw-bold text-primary">
                            <i class="fas fa-box me-2"></i>Reward Orders
                        </h4>
                        <p class="text-muted mb-0">Manage and track pending reward deliveries.</p>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-container shadow-sm rounded-4 overflow-hidden border">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>User</th>
                                    <th>Phone</th>
                                    <th>Reward</th>
                                    <th>Tracking URL</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($pending)): ?>
                                    <tr><td colspan="7" class="text-center py-5">No pending reward orders.</td></tr>
                                <?php else: foreach($pending as $order): ?>
                                    <tr>
                                        <td class="fw-bold">#RY-<?= $order['id'] ?></td>
                                        <td><?= $order['name'] ?></td>
                                        <td><?= $order['phone'] ?></td>
                                        <td><span class="badge bg-light text-dark border"><?= $order['item_name'] ?></span></td>
                                        <td class="text-truncate" style="max-width: 150px;">
                                            <?php if($order['tracking_url']): ?>
                                                <a href="<?= $order['tracking_url'] ?>" target="_blank" class="text-primary"><i class="fas fa-external-link-alt"></i> View</a>
                                            <?php else: ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><span class="status-badge status-pending"><?= $order['order_status'] ?? 'pending' ?></span></td>
                                        <td>
                                            <button class="btn btn-sm btn-icon btn-soft-primary update-order-btn" 
                                                data-id="<?= $order['id'] ?>" 
                                                data-status="<?= $order['order_status'] ?>" 
                                                data-tracking="<?= $order['tracking_url'] ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>
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
    function getBootstrap() {
        if (typeof window.bootstrap !== 'undefined') return window.bootstrap;
        if (typeof bootstrap !== 'undefined') return bootstrap;
        return null;
    }

    document.querySelectorAll('.update-order-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('order_id').value = this.dataset.id;
            document.getElementById('order_status_select').value = this.dataset.status;
            document.getElementById('order_tracking_url').value = this.dataset.tracking || '';
            
            var b = getBootstrap();
            if (b) {
                b.Modal.getOrCreateInstance(document.getElementById('orderModal')).show();
            }
        });
    });
};
</script>

<style>
    .status-badge {
        padding: 5px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .status-pending { background: #fff3e0; color: #f57c00; }
    .bg-soft-primary { background: #eef2ff; }
</style>

<?= $this->endSection() ?>
