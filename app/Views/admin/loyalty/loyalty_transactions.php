<?= $this->extend ( 'admin/includes/header' ) ?>

<?= $this->section ( 'content' ) ?>

<div class="content-inner container-fluid pb-0" id="page_layout">
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div
                    class="card-header d-flex justify-content-between align-items-center bg-transparent border-0 pt-4 px-4">
                    <div class="header-title">
                        <h4 class="card-title fw-bold text-primary"><i class="fas fa-history me-2"></i>Global
                            Transaction History</h4>
                        <p class="text-muted mb-0">Full audit log of point earnings, spins, and redemptions across all
                            users.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm px-3 rounded-pill bg-light border-0"
                            onchange="window.location.href='?type=' + this.value">
                            <option value="">All Types</option>
                            <option value="earn" <?= ( $currentType ?? '' ) === 'earn' ? 'selected' : '' ?>>Credit
                                (Earnings)</option>
                            <option value="redeem" <?= ( $currentType ?? '' ) === 'redeem' ? 'selected' : '' ?>>Debit
                                (Spins)</option>
                        </select>
                        <button class="btn btn-outline-primary btn-sm rounded-pill px-4"><i
                                class="fas fa-download me-2"></i>Export CSV</button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>Date</th>
                                    <th>User</th>
                                    <th>Transaction ID</th>
                                    <th>Transaction Type</th>
                                    <th>Amount</th>
                                    <th>Points Balance</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ( empty ( $transactions ) ) : ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-5">No transactions recorded yet.</td>
                                    </tr>
                                <?php else :
                                    foreach ( $transactions as $t ) : ?>
                                        <tr>
                                            <td class="text-muted small">
                                                <?= date ( 'd M Y, h:i A', strtotime ( $t['created_at'] ) ) ?>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="avatar-30 rounded-circle bg-soft-primary d-flex align-items-center justify-content-center me-2">
                                                        <span class="text-primary small fw-bold">
                                                            <?= substr ( $t['name'] ?? 'U', 0, 1 ) ?>
                                                        </span>
                                                    </div>
                                                    <span class="fw-bold text-dark">
                                                        <?= $t['name'] ?? 'Unknown User' ?>
                                                    </span>
                                                </div>
                                            </td>
                                            <td><code class="text-primary fs-6 fw-bold">#TXN-<?= $t['id'] ?></code></td>
                                            <td>
                                                <?php if ( $t['type'] === 'earn' ) : ?>
                                                    <span class="badge bg-soft-success text-success px-3 rounded-pill"><i
                                                            class="fas fa-arrow-down me-1"></i>Earned</span>
                                                <?php else : ?>
                                                    <span class="badge bg-soft-danger text-danger px-3 rounded-pill"><i
                                                            class="fas fa-arrow-up me-1"></i>Used</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="fw-bold <?= $t['type'] === 'earn' ? 'text-success' : 'text-danger' ?>">
                                                <?= $t['type'] === 'earn' ? '+' : '-' ?>
                                                <?= number_format ( abs ( $t['points'] ) ) ?> pts
                                            </td>
                                            <td><span class="badge bg-light text-muted border text-uppercase">
                                                    <?= str_replace ( '_', ' ', $t['source'] ?? 'General' ) ?>
                                                </span></td>
                                            <td class="text-muted small pe-4">
                                                <?= $t['description'] ?>
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

<style>
    .bg-soft-success {
        background: rgba(56, 239, 125, 0.1) !important;
    }

    .bg-soft-danger {
        background: rgba(245, 87, 108, 0.1) !important;
    }

    .bg-soft-primary {
        background: rgba(102, 126, 234, 0.1) !important;
    }

    .avatar-30 {
        width: 30px;
        height: 30px;
    }

    code {
        font-family: 'Courier New', Courier, monospace;
        letter-spacing: 0.5px;
    }
</style>

<?= $this->endSection () ?>