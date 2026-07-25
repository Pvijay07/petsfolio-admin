<?= $this->extend('admin/includes/header'); ?>

<?= $this->section('content'); ?>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?= $this->include('admin/nav_bar') ?>
  </div>

  <div class="container-fluid px-4 content-inner pb-0" id="page_layout">
    <!-- User Details Row -->
    <div class="row mb-3">
      <div class="col-12">
        <div class="card border-0 shadow-none rounded-4 bg-white mb-0">
          <div class="card-body p-3">
            <div class="d-flex align-items-center gap-3">
              <a href="javascript:history.back()" class="btn btn-light rounded-circle shadow-none" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i class="bi bi-arrow-left fs-5"></i>
              </a>

              <div class="d-flex align-items-center gap-3 pe-4 border-end" style="min-width: 200px;">
                <div class="position-relative">
                  <?php $initial = strtoupper(substr(trim($user->name ?? 'U'), 0, 1)); ?>
                  <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center shadow-none" style="width: 50px; height: 50px; border: 2px solid #fff;">
                    <span class="h4 fw-bold text-white mb-0"><?= $initial ?></span>
                  </div>
                  <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white border-2 rounded-circle"></span>
                </div>
                <div class="overflow-hidden">
                  <h6 class="fw-bold mb-0 text-dark text-truncate"><?= $user->name ?></h6>
                  <p class="text-muted mb-0" style="font-size: 0.8rem;"><i class="bi bi-geo-alt me-1"></i><?= $user->area ?? 'General' ?></p>
                </div>
              </div>

              <div>
                <div class="d-flex align-items-center gap-2 pe-4 border-end">
                  <div class="text-primary opacity-50"><i class="bi bi-hash fs-5"></i></div>
                  <div>
                    <span class="fw-bold text-dark small">ID : <?= $id ?></span>
                  </div>
                </div>

                <div class="d-flex align-items-center gap-2 pe-4 border-end">
                  <div class="text-primary opacity-50"><i class="bi bi-calendar3 fs-5"></i></div>
                  <div>
                    <span class="fw-bold text-dark small">Joined : <?= date('M Y', strtotime($user->created_at ?? 'now')) ?></span>
                  </div>
                </div>
              </div>
              <div class="">
                <div class="d-flex align-items-center gap-2">
                  <div class="text-primary opacity-50"><i class="bi bi-telephone fs-5"></i></div>
                  <div class="small fw-medium text-dark"><?= $user->phone ?></div>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <div class="text-primary opacity-50"><i class="bi bi-envelope fs-5"></i></div>
                  <div class="small fw-medium text-dark"><?= $user->email ?></div>
                </div>
              </div>
              <div id="exportButtonsContainer" class="ms-auto d-flex align-items-center"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="card mb-2 p-2 shadow-none">
      <div class="row g-2 ">
        <div class="col-lg-2 col-md-4 col-sm-6">
          <div class="card glassy-card glassy-primary rounded-4 h-100 border-0">
            <div class="card-body p-3 d-flex flex-column justify-content-center align-items-center text-center">
              <span class="card-title small fw-bold text-uppercase mb-1" style="font-size: 0.70rem;"><?= $user_type === 'client' ? 'Bookings' : 'Total Jobs' ?></span>
              <h4 class="mb-0 fw-bold"><?= $stats['total_bookings'] ?? $stats['total_jobs'] ?></h4>
            </div>
          </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
          <div class="card glassy-card glassy-info rounded-4 h-100 border-0">
            <div class="card-body p-3 d-flex flex-column justify-content-center align-items-center text-center">
              <span class="card-title small fw-bold text-uppercase mb-1" style="font-size: 0.70rem;"><?= $user_type === 'client' ? 'Spent' : 'Working Bal' ?></span>
              <h4 class="mb-0 fw-bold">₹<?= number_format($stats['total_spent'] ?? $stats['total_earned'], 2) ?></h4>
            </div>
          </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
          <div class="card glassy-card glassy-warning rounded-4 h-100 border-0">
            <div class="card-body p-3 d-flex flex-column justify-content-center align-items-center text-center">
              <span class="card-title small fw-bold text-uppercase mb-1" style="font-size: 0.70rem;"><?= $user_type === 'client' ? 'Usable' : 'Withdrawal' ?></span>
              <h4 class="mb-0 fw-bold">₹<?= number_format($stats['wallet_balance'], 2) ?></h4>
            </div>
          </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
          <div class="card glassy-card glassy-success rounded-4 h-100 border-0">
            <div class="card-body p-3 d-flex flex-column justify-content-center align-items-center text-center">
              <span class="card-title small fw-bold text-uppercase mb-1" style="font-size: 0.70rem;">Completed</span>
              <h4 class="mb-0 fw-bold"><?= $stats['completed_bookings'] ?? $stats['completed_jobs'] ?></h4>
            </div>
          </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
          <div class="card glassy-card glassy-danger rounded-4 h-100 border-0">
            <div class="card-body p-3 d-flex flex-column justify-content-center align-items-center text-center">
              <span class="card-title small fw-bold text-uppercase mb-1" style="font-size: 0.70rem;">Cancelled</span>
              <h4 class="mb-0 fw-bold"><?= $stats['cancelled_bookings'] ?? $stats['cancelled_jobs'] ?></h4>
            </div>
          </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
          <div class="card glassy-card glassy-purple rounded-4 h-100 border-0">
            <div class="card-body p-3 d-flex flex-column justify-content-center align-items-center text-center">
              <span class="card-title small fw-bold text-uppercase text-truncate px-1 mb-1" style="font-size: 0.70rem;"><?= $user_type === 'client' ? 'Service Bal' : 'Service Balance' ?></span>
              <h4 class="mb-0 fw-bold">₹<?= number_format($stats['service_balance'] ?? $stats['pending_balance'], 2) ?></h4>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card border-0 shadow-none rounded-4 mb-4">
      <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
        <div>
          <h6 class="fw-bold mb-0">Transaction History</h6>
          <p class="text-muted mb-0" style="font-size: 0.75rem;">Review all financial movements for this <?= $user_type ?></p>
        </div>
        <div class="d-flex gap-3 align-items-center flex-wrap mt-2 mt-md-0">
          <div class="d-flex align-items-center bg-white border border-secondary border-opacity-25 rounded-pill px-3 py-1">
            <i class="bi bi-funnel text-muted small me-2"></i>
            <select id="txnTypeFilter" class="form-select border-0 bg-transparent shadow-none py-1 ps-0 pe-4" style="width: 120px; cursor: pointer; font-size: 0.85rem; font-weight: 500;">
              <option value="">All Types</option>
              <option value="credit">Credit</option>
              <option value="debit">Debit</option>
            </select>
          </div>
          <div class="d-flex align-items-center bg-white border border-secondary border-opacity-25 rounded-pill px-3 py-1" style="width: 280px;">
            <i class="bi bi-search text-muted small me-2"></i>
            <input type="text" class="form-control border-0 bg-transparent shadow-none py-1 px-1" placeholder="Search transactions..." id="txnSearch" style="font-size: 0.85rem;">
          </div>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table align-middle mb-0" id="historyTable">
            <thead style="background: #c6d2ff !important;">
              <tr>
                <th class="ps-4 py-2  small fw-bold text-dark" style="width: 60px; background: #c6d2ff !important;">S.No</th>
                <th class="py-2  small fw-bold text-dark" style="width: 140px; background: #c6d2ff !important;">Txn ID</th>
                <th class="py-2  small fw-bold text-dark" style="width: 140px; background: #c6d2ff !important;">Processed Date</th>
                <th class="py-2  small fw-bold text-dark" style="width: 130px; background: #c6d2ff !important;">Category</th>
                <th class="py-2  small fw-bold text-dark" style="width: 100px; background: #c6d2ff !important;">Type</th>
                <th class="py-2  small fw-bold text-dark" style="background: #c6d2ff !important;">Description</th>
                <th class="py-2 px-3  small fw-bold text-dark" style="width: 120px; background: #c6d2ff !important;">Service Balance</th>
                <th class="py-2 px-3  small fw-bold text-dark" style="width: 100px; background: #c6d2ff !important;">Debit</th>
                <th class="py-2 px-3  small fw-bold text-dark" style="width: 100px; background: #c6d2ff !important;">Credit</th>
                <th class="py-2 px-3  small fw-bold text-dark pe-4" style="width: 120px; background: #c6d2ff !important;"><?= $user_type === 'client' ? 'Wallet Balance' : 'Earned Balance' ?></th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($history)): ?>
                <tr>
                  <td colspan="10" class="text-center py-5 text-muted">
                    <i class="bi bi-wallet-fill display-4 d-block mb-3 opacity-25"></i>
                    No transactions found for this <?= $user_type ?>
                  </td>
                </tr>
              <?php else: ?>
                <?php $sn = 1;
                foreach ($history as $row): ?>
                  <tr class="transaction-row">
                    <td class="ps-4 py-2">
                      <span class="text-secondary small"><?= $sn++ ?></span>
                    </td>
                    <td class="py-2">
                      <code class="text-dark fw-medium small"><?= !empty($row['txn_id']) ? esc($row['txn_id']) : (!empty($row['service_id']) ? '#TXN_' . esc($row['service_id']) : '--') ?></code>
                    </td>
                    <td class="py-2">
                      <div class="d-flex flex-column">
                        <span class="fw-bold text-dark small"><?= $row['service_id'] ? '#' . $row['service_id'] : 'N/A' ?></span>
                        <small class="text-secondary" style="font-size: 0.7rem;"><?= !empty($row['created_at']) ? date('d M Y, h:i A', strtotime($row['created_at'])) : '--' ?></small>
                      </div>
                    </td>
                    <td class="py-2">
                      <?php
                      $category = trim(strtolower($row['category'] ?? ''));
                      $displayCategory = !empty($category) ? str_replace('_', ' ', $category) : 'Activity';
                      $badgeClass = 'bg-secondary text-secondary';

                      // Handling user-specific category labeling requirements
                      if (str_contains($category, 'cancel') || (str_contains($category, 'walking') && str_contains($category, 'amount'))) {
                        if ($user_type === 'client') {
                          $displayCategory = 'Refund Amount';
                          $badgeClass = 'bg-info text-white';
                        } else {
                          $displayCategory = 'Penalty Amount';
                          $badgeClass = 'bg-danger text-danger';
                        }
                      } elseif (str_contains($category, 'approve')) {
                        $displayCategory = 'Approved Amount';
                        $badgeClass = 'bg-success text-white';
                      } elseif (str_contains($category, 'reject')) {
                        $displayCategory = 'Rejected Amount';
                        $badgeClass = 'bg-warning text-warning';
                      } elseif (str_contains($category, 'refund')) {
                        $badgeClass = 'bg-info text-white';
                      } elseif (str_contains($category, 'booking')) {
                        $badgeClass = 'bg-primary text-white';
                      } elseif (str_contains($category, 'wallet')) {
                        $badgeClass = 'bg-success text-success';
                      } elseif (str_contains($category, 'payout')) {
                        $badgeClass = 'bg-warning text-warning';
                      } elseif (str_contains($category, 'penalty')) {
                        $badgeClass = 'bg-danger text-danger';
                      } elseif (str_contains($category, 'withdrawal')) {
                        $badgeClass = 'bg-warning text-warning';
                      }
                      ?>
                      <span class="badge rounded-pill <?= $badgeClass ?> bg-opacity-10 px-2 py-1 text-capitalize fw-bold">
                        <?= $displayCategory ?>
                      </span>
                    </td>
                    <td class="py-2">
                      <span class="badge <?= ($row['transaction_type'] ?? '') === 'credit' ? 'bg-success text-white' : 'bg-danger text-danger' ?> bg-opacity-10 rounded-pill px-2 py-1 text-uppercase small fw-bold">
                        <?= esc($row['transaction_type'] ?? '--') ?>
                      </span>
                    </td>
                    <td class="py-2">
                      <span class="small text-secondary d-block text-truncate" style="max-width: 250px;" title="<?= esc($row['description'] ?? '') ?>">
                        <?= esc($row['description'] ?? '') ?>
                      </span>
                    </td>
                    <td class="py-2 px-3 fw-bold text-dark">
                      <?php
                      $serviceBal = $user_type === 'client' ? ($row['balance'] ?? $row['clbalance_after'] ?? 0) : ($row['spbalance_after'] ?? 0);
                      ?>
                      ₹ <?= $serviceBal ?>
                    </td>
                    <td class="py-2 px-3">
                      ₹ <?= $row['debit'] ?? 0 ?>
                    </td>
                    <td class="py-2 px-3">
                      <?php
                      $credit = $row['credit'] ?? 0;
                      if (($row['category'] ?? '') == 'booking_amount') $credit = 0;
                      ?>
                      ₹ <?= $credit ?>
                    </td>
                    <td class="py-2 px-3 fw-bold text-dark">
                      <?php
                      $walletBal = $user_type === 'client' ? ($row['refundBalance'] ?? $row['refund_balance'] ?? 0) : ($row['earned_balance'] ?? 0);
                      ?>
                      ₹ <?= $walletBal ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<!-- DataTables & Buttons -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<style>
  .form-control:focus,
  .form-select:focus {
    box-shadow: none !important;
  }

  .dt-buttons .btn {
    padding: 0.4rem 1.75rem !important;
    font-size: 0.85rem !important;
    margin-top: 0 !important;
    margin-right: 0.5rem !important;
    border-radius: 50rem !important;
  }

  .btn-group {
    margin-top: 0 !important;
    border-top: none !important;
    padding-top: 0 !important;

  }

  .dataTables_filter {
    display: none;
  }

  .btn-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
  }

  .breadcrumb-item+.breadcrumb-item::before {
    content: "›";
    font-size: 1.2rem;
    line-height: 1;
    vertical-align: middle;
  }

  /* Glassmorphism Cards */
  .glassy-card {
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.4) !important;
    box-shadow: none !important;
  }

  .glassy-primary {
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.12) 0%, rgba(99, 102, 241, 0.04) 100%) !important;
    border: 1px solid rgba(99, 102, 241, 0.25) !important;
  }

  .glassy-primary .card-title {
    color: #4338ca;
  }

  .glassy-primary h4 {
    color: #312e81;
  }

  .glassy-info {
    background: linear-gradient(135deg, rgba(13, 148, 136, 0.12) 0%, rgba(20, 184, 166, 0.04) 100%) !important;
    border: 1px solid rgba(20, 184, 166, 0.25) !important;
  }

  .glassy-info .card-title {
    color: #0f766e;
  }

  .glassy-info h4 {
    color: #115e59;
  }

  .glassy-warning {
    background: linear-gradient(135deg, rgba(217, 119, 6, 0.12) 0%, rgba(245, 158, 11, 0.04) 100%) !important;
    border: 1px solid rgba(245, 158, 11, 0.25) !important;
  }

  .glassy-warning .card-title {
    color: #b45309;
  }

  .glassy-warning h4 {
    color: #92400e;
  }

  .glassy-success {
    background: linear-gradient(135deg, rgba(5, 150, 105, 0.12) 0%, rgba(16, 185, 129, 0.04) 100%) !important;
    border: 1px solid rgba(16, 185, 129, 0.25) !important;
  }

  .glassy-success .card-title {
    color: #047857;
  }

  .glassy-success h4 {
    color: #064e3b;
  }

  .glassy-danger {
    background: linear-gradient(135deg, rgba(225, 29, 72, 0.12) 0%, rgba(244, 63, 94, 0.04) 100%) !important;
    border: 1px solid rgba(244, 63, 94, 0.25) !important;
  }

  .glassy-danger .card-title {
    color: #be123c;
  }

  .glassy-danger h4 {
    color: #881337;
  }

  .glassy-purple {
    background: linear-gradient(135deg, rgba(147, 51, 234, 0.12) 0%, rgba(168, 85, 247, 0.04) 100%) !important;
    border: 1px solid rgba(168, 85, 247, 0.25) !important;
  }

  .glassy-purple .card-title {
    color: #7e22ce;
  }

  .glassy-purple h4 {
    color: #581c87;
  }
</style>

<script>
  $(document).ready(function() {
    const table = $('#historyTable').DataTable({
      dom: 'Brt<"row align-items-center pb-3"<"col-sm-12 col-md-6 text-muted small mt-2"i><"col-sm-12 col-md-6 d-flex justify-content-md-end justify-content-center mt-2 pagination-sm"p>>',
      buttons: [{
          extend: 'excelHtml5',
          text: '<i class="bi bi-file-earmark-excel me-1"></i> Excel',
          className: 'btn shadow-none glassy-card glassy-success text-success fw-bold',
          title: 'Transaction History - <?= $user->name ?? $user->id ?>',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
          }
        },
        {
          extend: 'pdfHtml5',
          text: '<i class="bi bi-file-earmark-pdf me-1"></i> PDF',
          className: 'btn shadow-none glassy-card glassy-danger text-danger fw-bold',
          title: 'Transaction History - <?= $user->name ?? $user->id ?>',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
          }
        }
      ],
      "pageLength": 10,
      "ordering": false,
      "language": {
        "emptyTable": "No transactions found"
      }
    });

    table.buttons().container().appendTo('#exportButtonsContainer');

    $('#txnSearch').on('keyup input clear', function() {
      table.search(this.value).draw();
    });

    $('#txnTypeFilter').on('change', function() {
      var val = $(this).val();

      table.column(6).search('', true, false);
      table.column(7).search('', true, false);

      table.column(4).search(val).draw();
    });
  });
</script>

<?= $this->endSection(); ?>