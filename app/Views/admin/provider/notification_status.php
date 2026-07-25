<?php echo $this->extend('admin/includes/header'); ?>

<?php echo $this->section('content'); ?>

<main class="main-content">
    <div class="position-relative iq-banner">
        <?php echo $this->include('admin/nav_bar') ?>
    </div>

    <div class="conatiner-fluid content-inner pb-0" id="page_layout">
        <!-- Header Section -->
        <div class="container-fluid p-0 mb-4">
            <div class="bg-gradient-primary text-white p-5 rounded-3 shadow position-relative overflow-hidden">
                <div class="position-relative z-1">
                    <h1 class="h2 fw-bold mb-2 text-white">Provider Notifications</h1>
                    <p class="text-white-75 mb-0">Monitor push notification delivery status and history for all service providers</p>
                </div>
                <i class="bi bi-bell-fill position-absolute end-0 bottom-0 opacity-10"
                    style="font-size: 14rem; transform: rotate(12deg); margin-right: -3rem; margin-bottom: -3rem;"></i>
            </div>
        </div>

        <!-- Filters and Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                    <div class="card-body p-4 text-center">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-block mb-3">
                            <i class="bi bi-check-circle text-success fs-4"></i>
                        </div>
                        <h6 class="text-muted fw-semibold mb-1">Total Sent</h6>
                        <h3 class="mb-0 fw-bold counter" id="sentCount">0</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                    <div class="card-body p-4 text-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 d-inline-block mb-3">
                            <i class="bi bi-clock-history text-warning fs-4"></i>
                        </div>
                        <h6 class="text-muted fw-semibold mb-1">Pending</h6>
                        <h3 class="mb-0 fw-bold counter" id="pendingCount">0</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                    <div class="card-body p-4 text-center">
                        <div class="rounded-circle bg-danger bg-opacity-10 p-3 d-inline-block mb-3">
                            <i class="bi bi-exclamation-triangle text-danger fs-4"></i>
                        </div>
                        <h6 class="text-muted fw-semibold mb-1">Failed</h6>
                        <h3 class="mb-0 fw-bold counter" id="failedCount">0</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                    <div class="card-body p-4 text-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-inline-block mb-3">
                            <i class="bi bi-lightning text-primary fs-4"></i>
                        </div>
                        <h6 class="text-muted fw-semibold mb-1">Processing</h6>
                        <h3 class="mb-0 fw-bold counter" id="processingCount">0</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Table -->
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden mt-2">
            <div class="card-header bg-white py-3 border-bottom-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Notification History</h5>
                    <div class="d-flex gap-3">
                        <form action="" method="get" class="d-flex gap-2">
                            <select name="provider_id" class="form-select form-select-sm" style="width: 200px;" onchange="this.form.submit()">
                                <option value="">All Providers</option>
                                <?php foreach($providers_list as $p): ?>
                                    <option value="<?= $p->id ?>" <?= $selected_provider == $p->id ? 'selected' : '' ?>><?= htmlspecialchars($p->name) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if($selected_provider): ?>
                                <a href="<?= base_url('provider/notification-sent-status') ?>" class="btn btn-sm btn-outline-secondary">Clear</a>
                            <?php endif; ?>
                        </form>
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control bg-light border-start-0" id="searchTable" placeholder="Search tokens or titles...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="notificationTable">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th class="ps-4">Provider / Token</th>
                            <th>Booking ID</th>
                            <th>Title & Message</th>
                            <th>Status (Sent/Pending)</th>
                            <th>Attempts</th>
                            <th>Sent At</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counts = ['sent' => 0, 'pending' => 0, 'failed' => 0, 'processing' => 0];
                        foreach ($notifications as $row):
                            $counts[$row->status] = ($counts[$row->status] ?? 0) + 1;
                            $statusClass = 'bg-secondary';
                            $statusIcon = 'bi-info-circle';

                            switch ($row->status) {
                                case 'sent':
                                    $statusClass = 'bg-success';
                                    $statusIcon = 'bi-check-circle';
                                    break;
                                case 'pending':
                                    $statusClass = 'bg-warning';
                                    $statusIcon = 'bi-clock';
                                    break;
                                case 'failed':
                                    $statusClass = 'bg-danger';
                                    $statusIcon = 'bi-x-circle';
                                    break;
                                case 'processing':
                                    $statusClass = 'bg-primary';
                                    $statusIcon = 'bi-arrow-repeat';
                                    break;
                            }
                        ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-sm rounded-circle bg-light d-flex align-items-center justify-content-center">
                                            <i class="bi bi-person text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($row->provider_name ?? 'Multiple/Unknown') ?></div>
                                            <div class="text-muted small"><?= substr(htmlspecialchars($row->tokens), 0, 20) ?>...</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"><?= $row->booking_id ?? 'N/A' ?></span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark"><?= htmlspecialchars($row->title) ?></div>
                                    <div class="text-muted small text-truncate" style="max-width: 200px;"><?= htmlspecialchars($row->message) ?></div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill <?= $statusClass ?> bg-opacity-10 text-<?= str_replace('bg-', '', $statusClass) ?> px-3 py-2 border <?= str_replace('bg-', 'border-', $statusClass) ?> border-opacity-25">
                                        <i class="bi <?= $statusIcon ?> me-1"></i>
                                        <?= ucfirst($row->status) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted"><?= $row->attempts ?> / 3</span>
                                </td>
                                <td>
                                    <div class="text-dark small fw-medium"><?= $row->sent_at ? date('d M, Y', strtotime($row->sent_at)) : '--' ?></div>
                                    <div class="text-muted smaller"><?= $row->sent_at ? date('h:i A', strtotime($row->sent_at)) : 'Scheduled: ' . date('h:i A', strtotime($row->scheduled_at)) ?></div>
                                </td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-icon btn-light rounded-circle resend-btn" data-id="<?= $row->id ?>" data-bs-toggle="tooltip" title="Resend Notification">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>
                                    <button class="btn btn-sm btn-icon btn-light rounded-circle" data-bs-toggle="tooltip" title="View Payload">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white py-3 border-top-0 d-flex justify-content-between align-items-center">
                <span class="text-muted small">Showing last 500 notifications</span>
                <nav aria-label="Page navigation" id="pagination">
                    <!-- Pagination will be handled by JS or server-side if needed -->
                </nav>
            </div>
        </div>
    </div>
</main>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%) !important;
    }

    .avatar-sm {
        width: 40px;
        height: 40px;
        min-width: 40px;
    }

    .smaller {
        font-size: 0.75rem;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .table thead th {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        font-weight: 700;
        color: #acb0b4;
        border-bottom: 2px solid #f1f4f8;
    }

    .badge {
        font-size: 0.75rem;
        font-weight: 600;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize stats
        document.getElementById('sentCount').innerText = '<?= $counts['sent'] ?>';
        document.getElementById('pendingCount').innerText = '<?= $counts['pending'] ?>';
        document.getElementById('failedCount').innerText = '<?= $counts['failed'] ?>';
        document.getElementById('processingCount').innerText = '<?= $counts['processing'] ?>';

        // Filter search
        const searchInput = document.getElementById('searchTable');
        const tableLines = document.querySelectorAll('#notificationTable tbody tr');

        searchInput.addEventListener('keyup', function() {
            const query = this.value.toLowerCase();
            tableLines.forEach(line => {
                const text = line.innerText.toLowerCase();
                line.style.display = text.includes(query) ? '' : 'none';
            });
        });

        // Resend Notification AJAX
        document.querySelectorAll('.resend-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const btnRef = this;
                
                if (confirm('Are you sure you want to resend this notification?')) {
                    btnRef.disabled = true;
                    btnRef.innerHTML = '<i class="bi bi-hourglass-split"></i>';

                    fetch('<?= base_url("resend-notification") ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: 'id=' + id
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            alert('Success: ' + data.message);
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                            btnRef.disabled = false;
                            btnRef.innerHTML = '<i class="bi bi-arrow-clockwise"></i>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An unexpected error occurred.');
                        btnRef.disabled = false;
                        btnRef.innerHTML = '<i class="bi bi-arrow-clockwise"></i>';
                    });
                }
            });
        });
    });
</script>

<?php echo $this->section('scripts'); ?>
<!-- Additional scripts if any -->
<?php echo $this->endSection(); ?>

<?php echo $this->endSection(); ?>