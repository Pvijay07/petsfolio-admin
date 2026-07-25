<?= $this->extend('admin/includes/header') ?>

<?= $this->section('content') ?>

<div class="content-inner container-fluid pb-0" id="page_layout">
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-0 pt-4 px-4">
                    <div class="header-title">
                        <h4 class="card-title fw-bold text-success">
                            <i class="fas fa-truck me-2"></i>Delivered Orders
                        </h4>
                        <p class="text-muted mb-0">History of successfully fulfilled rewards.</p>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-container shadow-sm rounded-4 overflow-hidden border">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>User</th>
                                    <th>Reward</th>
                                    <th>Tracking</th>
                                    <th>Delivered Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($delivered)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5">No delivered orders yet.</td>
                                    </tr>
                                    <?php else: foreach ($delivered as $order): ?>
                                        <tr>
                                            <td class="fw-bold">#RY-<?= $order['id'] ?></td>
                                            <td><?= $order['name'] ?> (UID-<?= $order['user_id'] ?>)</td>
                                            <td><?= $order['item_name'] ?? 'Reward Item' ?></td>
                                            <td>
                                                <?php if ($order['tracking_url']): ?>
                                                    <a href="<?= $order['tracking_url'] ?>" target="_blank">View History</a>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-success fw-bold"><?= date('d M Y', strtotime($order['updated_at'])) ?></td>
                                        </tr>
                                <?php endforeach;
                                endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-soft-success {
        background: #e8f5e9;
    }
</style>

<?= $this->endSection() ?>