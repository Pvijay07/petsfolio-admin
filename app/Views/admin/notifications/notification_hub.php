<?= $this->extend('admin/includes/header'); ?>
<!--start content-->
<?= $this->section('content'); ?>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?= $this->include('admin/nav_bar') ?>
  </div>
  <div class="container-fluid px-4 content-inner pb-0" id="page_layout">

    <!-- Page Header -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="card border-0 overflow-hidden rounded-4 mb-0" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);">
          <div class="card-body p-4 position-relative text-white">
            <div class="row align-items-center">
              <div class="col-lg-8 mb-3 mb-lg-0">
                <div class="d-flex align-items-center mb-2">
                  <div class="rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: rgba(255,255,255,0.15);">
                    <i class="bi bi-megaphone fs-3"></i>
                  </div>
                  <div>
                    <h3 class="fw-bold mb-1 text-white">Notification Hub</h3>
                    <p class="mb-0" style="opacity:0.85;">Manage campaigns, templates & track delivery</p>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 text-lg-end">
                <a href="<?= base_url('notification-manager/campaigns?action=create') ?>" class="btn btn-light fw-semibold me-2">
                  <i class="bi bi-plus-circle me-1"></i> New Campaign
                </a>
                <a href="<?= base_url('notification-manager/templates') ?>" class="btn btn-outline-light fw-semibold">
                  <i class="bi bi-file-earmark-text me-1"></i> Templates
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
      <!-- Sent Today -->
      <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
          <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:rgba(99,102,241,0.1);">
                <i class="bi bi-send-check fs-5" style="color:#6366f1;"></i>
              </div>
              <span class="badge rounded-pill" style="background:rgba(99,102,241,0.1);color:#6366f1;">Today</span>
            </div>
            <h3 class="fw-bold mb-1"><?= number_format($stats['today']->sent_today ?? 0) ?></h3>
            <p class="text-muted small mb-0">Notifications Sent</p>
          </div>
        </div>
      </div>

      <!-- Delivery Rate -->
      <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
          <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:rgba(16,185,129,0.1);">
                <i class="bi bi-check2-all fs-5" style="color:#10b981;"></i>
              </div>
              <span class="badge rounded-pill" style="background:rgba(16,185,129,0.1);color:#10b981;">Rate</span>
            </div>
            <?php
              $totalSent = ($stats['campaigns']->total_sent ?? 0);
              $totalFailed = ($stats['campaigns']->total_failed ?? 0);
              $deliveryRate = $totalSent > 0 ? round((($totalSent - $totalFailed) / $totalSent) * 100, 1) : 0;
            ?>
            <h3 class="fw-bold mb-1"><?= $deliveryRate ?>%</h3>
            <p class="text-muted small mb-0">Delivery Rate</p>
          </div>
        </div>
      </div>

      <!-- Active Campaigns -->
      <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
          <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:rgba(245,158,11,0.1);">
                <i class="bi bi-broadcast fs-5" style="color:#f59e0b;"></i>
              </div>
              <span class="badge rounded-pill" style="background:rgba(245,158,11,0.1);color:#f59e0b;">Active</span>
            </div>
            <h3 class="fw-bold mb-1"><?= ($stats['campaigns']->scheduled_campaigns ?? 0) + ($stats['campaigns']->sending_campaigns ?? 0) ?></h3>
            <p class="text-muted small mb-0">Active Campaigns</p>
          </div>
        </div>
      </div>

      <!-- Templates -->
      <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
          <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:rgba(236,72,153,0.1);">
                <i class="bi bi-file-earmark-text fs-5" style="color:#ec4899;"></i>
              </div>
              <span class="badge rounded-pill" style="background:rgba(236,72,153,0.1);color:#ec4899;">Total</span>
            </div>
            <h3 class="fw-bold mb-1"><?= $templateCount->active ?? 0 ?></h3>
            <p class="text-muted small mb-0">Active Templates</p>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Sends Chart (7 Days) -->
      <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="fw-bold mb-0">Notifications — Last 7 Days</h5>
              <a href="<?= base_url('notification-manager/logs') ?>" class="btn btn-sm btn-outline-primary rounded-pill">View Logs</a>
            </div>
          </div>
          <div class="card-body p-4">
            <canvas id="sendsChart" height="260"></canvas>
          </div>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
          <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
            <h5 class="fw-bold mb-0">Campaign Summary</h5>
          </div>
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
              <span class="text-muted">Total Campaigns</span>
              <span class="fw-bold"><?= $stats['campaigns']->total_campaigns ?? 0 ?></span>
            </div>
            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
              <span class="text-muted">Sent</span>
              <span class="badge bg-success rounded-pill px-3"><?= $stats['campaigns']->sent_campaigns ?? 0 ?></span>
            </div>
            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
              <span class="text-muted">Scheduled</span>
              <span class="badge bg-warning text-dark rounded-pill px-3"><?= $stats['campaigns']->scheduled_campaigns ?? 0 ?></span>
            </div>
            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
              <span class="text-muted">Drafts</span>
              <span class="badge bg-secondary rounded-pill px-3"><?= $stats['campaigns']->draft_campaigns ?? 0 ?></span>
            </div>
            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
              <span class="text-muted">Total Notifications Sent</span>
              <span class="fw-bold text-primary"><?= number_format($stats['campaigns']->total_sent ?? 0) ?></span>
            </div>
            <div class="d-flex justify-content-between align-items-center py-3">
              <span class="text-muted">Failed</span>
              <span class="fw-bold text-danger"><?= number_format($stats['campaigns']->total_failed ?? 0) ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Campaigns -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="fw-bold mb-0">Recent Campaigns</h5>
              <a href="<?= base_url('notification-manager/campaigns') ?>" class="btn btn-sm btn-outline-primary rounded-pill">View All</a>
            </div>
          </div>
          <div class="card-body p-4">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                  <tr>
                    <th class="border-0 rounded-start py-3 ps-3">Campaign</th>
                    <th class="border-0 py-3">Type</th>
                    <th class="border-0 py-3">Channel</th>
                    <th class="border-0 py-3">Audience</th>
                    <th class="border-0 py-3">Sent</th>
                    <th class="border-0 py-3">Status</th>
                    <th class="border-0 rounded-end py-3">Date</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($recentCampaigns)): ?>
                    <?php foreach ($recentCampaigns as $campaign): ?>
                      <tr>
                        <td class="ps-3">
                          <div class="fw-semibold"><?= esc($campaign->name) ?></div>
                          <small class="text-muted"><?= esc(mb_substr($campaign->title, 0, 40)) ?><?= mb_strlen($campaign->title) > 40 ? '...' : '' ?></small>
                        </td>
                        <td>
                          <?php
                          $typeColors = ['promotional' => 'primary', 'reminder' => 'info', 'engagement' => 'warning', 'transactional' => 'dark'];
                          $color = $typeColors[$campaign->type] ?? 'secondary';
                          ?>
                          <span class="badge bg-<?= $color ?> bg-opacity-10 text-<?= $color ?> rounded-pill"><?= ucfirst($campaign->type) ?></span>
                        </td>
                        <td>
                          <?php
                          $channelIcons = ['push' => 'bi-phone', 'sms' => 'bi-chat-dots', 'email' => 'bi-envelope', 'whatsapp' => 'bi-whatsapp', 'all' => 'bi-broadcast'];
                          ?>
                          <i class="bi <?= $channelIcons[$campaign->channel] ?? 'bi-bell' ?> me-1"></i>
                          <?= ucfirst($campaign->channel) ?>
                        </td>
                        <td><?= number_format($campaign->total_audience ?? 0) ?></td>
                        <td>
                          <span class="text-success fw-semibold"><?= number_format($campaign->sent_count ?? 0) ?></span>
                          <?php if (($campaign->failed_count ?? 0) > 0): ?>
                            / <span class="text-danger"><?= $campaign->failed_count ?></span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <?php
                          $statusColors = [
                            'draft' => 'secondary', 'scheduled' => 'warning', 'sending' => 'info',
                            'sent' => 'success', 'cancelled' => 'dark', 'failed' => 'danger', 'paused' => 'warning'
                          ];
                          $sColor = $statusColors[$campaign->status] ?? 'secondary';
                          ?>
                          <span class="badge bg-<?= $sColor ?> rounded-pill"><?= ucfirst($campaign->status) ?></span>
                        </td>
                        <td>
                          <small class="text-muted"><?= date('M d, Y', strtotime($campaign->created_at)) ?></small>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-megaphone fs-1 d-block mb-2 opacity-25"></i>
                        No campaigns yet. Create your first campaign!
                      </td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
  <?= $this->include('admin/footer') ?>
</main>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const chartData = <?= json_encode($stats['chart'] ?? []) ?>;
  const labels = chartData.map(d => d.date);
  const values = chartData.map(d => d.count);

  const ctx = document.getElementById('sendsChart');
  if (ctx) {
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Notifications Sent',
          data: values,
          backgroundColor: 'rgba(99, 102, 241, 0.15)',
          borderColor: '#6366f1',
          borderWidth: 2,
          borderRadius: 8,
          borderSkipped: false,
          barPercentage: 0.6,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: '#1e1b4b',
            padding: 12,
            cornerRadius: 8,
            titleFont: { size: 13 },
            bodyFont: { size: 12 },
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: { color: 'rgba(0,0,0,0.04)' },
            ticks: { font: { size: 11 }, color: '#94a3b8' },
          },
          x: {
            grid: { display: false },
            ticks: { font: { size: 11 }, color: '#94a3b8' },
          }
        }
      }
    });
  }
});
</script>

<?= $this->endSection() ?>
