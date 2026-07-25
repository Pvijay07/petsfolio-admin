<?php echo $this->extend('admin/includes/header'); ?>
<?php echo $this->section('content'); ?>

<main class="main-content">
    <div class="position-relative iq-banner">
        <?php echo $this->include('admin/nav_bar') ?>
    </div>

    <div class="conatiner-fluid content-inner px-4">
        <div class="container-fluid p-0 mb-4">
            <div class="bg-gradient-primary text-white p-5 rounded-3 shadow position-relative overflow-hidden">
                <div class="position-relative z-1">
                    <h1 class="h2 fw-bold mb-2 text-white">Scanner Team Statistics</h1>
                    <p class="text-white-75 mb-0">Performance overview of the ticket scanning team.</p>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm overflow-hidden rounded-3">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background: #c6d2ff !important;">
                        <tr>
                            <th class="py-3 text-black">Staff Member</th>
                            <th class="py-3 text-black">Total Tickets Scanned</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($stats)): ?>
                            <?php foreach ($stats as $stat): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                <i class="bi bi-person"></i>
                                            </div>
                                            <span class="fw-bold"><?= esc($stat->scanner_name ?? 'Staff #'.$stat->scanner_id) ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <h5 class="mb-0 text-primary fw-bold"><?= $stat->total_scanned ?></h5>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="text-center py-5">No scanning data available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php echo $this->include('admin/footer') ?>
</main>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
    }
</style>

<?php echo $this->endSection(); ?>
