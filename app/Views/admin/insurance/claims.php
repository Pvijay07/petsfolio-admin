<?php echo $this->extend('admin/includes/header'); ?>
<!--start content-->

<?php echo $this->section('content'); ?>

<?php
// Helper for avatar initials and pastel colors matching modern SaaS UI
if (!function_exists('getInitialsAvatar')) {
    function getInitialsAvatar($name, $id = 0) {
        $trimmed = trim($name ?? '');
        if (empty($trimmed) || $trimmed === 'Unknown') {
            return ['text' => '??', 'bg' => '#f1f5f9', 'color' => '#64748b'];
        }
        $words = preg_split('/\s+/', $trimmed);
        if (count($words) >= 2) {
            $initials = mb_strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1));
        } else {
            $initials = mb_strtoupper(mb_substr($trimmed, 0, 2));
        }

        $palettes = [
            ['bg' => '#ede9fe', 'color' => '#6366f1'], // Indigo
            ['bg' => '#dbeafe', 'color' => '#2563eb'], // Blue
            ['bg' => '#dcfce7', 'color' => '#16a34a'], // Green
            ['bg' => '#fce7f3', 'color' => '#db2777'], // Pink
            ['bg' => '#fef3c7', 'color' => '#d97706'], // Amber
            ['bg' => '#ccfbf1', 'color' => '#0d9488'], // Teal
            ['bg' => '#e0e7ff', 'color' => '#4338ca'], // Purple
            ['bg' => '#ffedd5', 'color' => '#ea580c'], // Orange
        ];
        $idx = abs(crc32($trimmed . $id)) % count($palettes);
        return [
            'text'  => $initials,
            'bg'    => $palettes[$idx]['bg'],
            'color' => $palettes[$idx]['color'],
        ];
    }
}

// Compute summary stats for hero banner cards
$totalClaims = count($claims ?? []);
$pendingCount = 0;
$inProgressCount = 0;
$settledCount = 0;
$totalClaimAmount = 0;

if (!empty($claims)) {
    foreach ($claims as $c) {
        $st = strtolower($c['status'] ?? 'pending');
        $totalClaimAmount += (float)($c['amount'] ?? 0);
        if ($st === 'settled') {
            $settledCount++;
        } elseif ($st === 'in progress') {
            $inProgressCount++;
        } else {
            $pendingCount++;
        }
    }
}
?>

<style>
  /* Modern Dashboard UI Styles matching Booking Requests */
  .nl-hero-banner {
    background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 25%, #3730a3 60%, #4f46e5 100%);
    border-radius: 20px;
    padding: 28px 32px;
    color: #ffffff;
    box-shadow: 0 10px 30px -5px rgba(79, 70, 229, 0.25);
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
  }
  .nl-hero-banner::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, rgba(255,255,255,0) 70%);
    border-radius: 50%;
    pointer-events: none;
  }
  .nl-live-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 5px 14px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 600;
    color: #ffffff;
    letter-spacing: 0.03em;
    margin-bottom: 12px;
  }
  .nl-live-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #38bdf8;
    box-shadow: 0 0 10px #38bdf8;
    animation: nl-pulse-blue 2s infinite;
  }
  @keyframes nl-pulse-blue {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(56, 189, 248, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(56, 189, 248, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(56, 189, 248, 0); }
  }
  .nl-stat-card {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.16);
    border-radius: 14px;
    padding: 14px 18px;
    min-width: 115px;
    flex: 1;
    transition: transform 0.15s ease, background 0.15s ease;
  }
  .nl-stat-card:hover {
    transform: translateY(-2px);
    background: rgba(255, 255, 255, 0.12);
  }
  .nl-stat-card .label {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.72);
    margin-bottom: 4px;
    display: block;
  }
  .nl-stat-card .value {
    font-size: 24px;
    font-weight: 800;
    line-height: 1.15;
    color: #ffffff;
  }

  /* Segmented Pill Tabs */
  .nl-segmented-tabs {
    background: #eef2f6;
    padding: 4px;
    border-radius: 50px;
    display: inline-flex;
    align-items: center;
    gap: 3px;
    flex-wrap: wrap;
  }
  .nl-tab-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 18px;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 500;
    color: #64748b;
    text-decoration: none;
    transition: all 0.15s ease;
  }
  .nl-tab-pill:hover {
    color: #1e293b;
  }
  .nl-tab-pill.active {
    background: #ffffff;
    color: #4338ca;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  }
  .nl-tab-badge {
    border-radius: 50px;
    padding: 2px 8px;
    font-size: 11px;
    font-weight: 700;
  }
  .nl-tab-pill.active .nl-tab-badge {
    background: #e0e7ff;
    color: #4338ca;
  }
  .nl-tab-pill:not(.active) .nl-tab-badge {
    background: rgba(0, 0, 0, 0.06);
    color: #64748b;
  }

  /* Action Buttons */
  .nl-btn-action {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 18px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #334155;
    box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    text-decoration: none;
    cursor: pointer;
    transition: all 0.15s ease;
  }
  .nl-btn-action:hover {
    background: #f8fafc;
    color: #0f172a;
    border-color: #94a3b8;
  }
  .nl-btn-primary {
    background: #4f46e5;
    color: #ffffff;
    border-color: #4f46e5;
  }
  .nl-btn-primary:hover {
    background: #4338ca;
    color: #ffffff;
    border-color: #4338ca;
  }

  /* Filter Card */
  .nl-filter-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 16px 20px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.02);
    margin-bottom: 20px;
  }
  .nl-search-wrap {
    position: relative;
    width: 100%;
  }
  .nl-search-wrap .search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    pointer-events: none;
    font-size: 14px;
  }
  .nl-search-input {
    width: 100%;
    padding: 9px 14px 9px 38px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #f8fafc;
    font-size: 13px;
    color: #0f172a;
    transition: all 0.15s ease;
  }
  .nl-search-input:focus {
    background: #ffffff;
    border-color: #6366f1;
    outline: none;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
  }
  .nl-select {
    padding: 8px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #f8fafc;
    font-size: 13px;
    color: #334155;
    font-weight: 500;
    transition: all 0.15s ease;
  }
  .nl-select:focus {
    border-color: #6366f1;
    background: #ffffff;
    outline: none;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
  }

  /* Table Card */
  .nl-table-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    box-shadow: 0 2px 14px rgba(0,0,0,0.025);
    overflow: hidden;
  }
  .nl-table {
    width: 100%;
    margin-bottom: 0;
  }
  .nl-table thead th {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: #64748b;
    padding: 14px 18px;
    vertical-align: middle;
  }
  .nl-table tbody td {
    padding: 16px 18px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
    font-size: 13px;
  }
  .nl-table tbody tr {
    transition: background 0.12s ease;
  }
  .nl-table tbody tr:hover {
    background: rgba(99, 102, 241, 0.025);
  }

  /* Recipient Avatar Initials */
  .nl-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 12.5px;
    flex-shrink: 0;
  }

  .status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 50px;
    font-size: 11.5px;
    font-weight: 600;
  }
  .status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
  }
  .status-pending { background: #fef3c7; color: #b45309; }
  .status-pending .status-dot { background: #d97706; }
  .status-in-progress, .status-inprogress { background: #e0f2fe; color: #0369a1; }
  .status-in-progress .status-dot, .status-inprogress .status-dot { background: #0284c7; }
  .status-settled { background: #dcfce7; color: #15803d; }
  .status-settled .status-dot { background: #16a34a; }
  .status-rejected { background: #fee2e2; color: #b91c1c; }
  .status-rejected .status-dot { background: #dc2626; }

  .nl-action-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.15s ease;
    background: #ffffff;
    text-decoration: none;
  }
  .nl-action-btn.view { color: #4f46e5; }
  .nl-action-btn.view:hover { background: #4f46e5; color: #ffffff; border-color: #4f46e5; }
  .nl-action-btn.edit { color: #059669; }
  .nl-action-btn.edit:hover { background: #059669; color: #ffffff; border-color: #059669; }

  /* Pagination */
  .nl-page-btn {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    text-decoration: none;
    border: 1px solid transparent;
    transition: all 0.12s ease;
    background: transparent;
    cursor: pointer;
  }
  .nl-page-btn:hover:not(.active):not(:disabled) {
    background: #f1f5f9;
    color: #0f172a;
  }
  .nl-page-btn.active {
    background: #4f46e5;
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(79, 70, 229, 0.3);
    border-color: #4f46e5;
  }
  .nl-page-btn:disabled, .nl-page-btn.disabled {
    color: #cbd5e1;
    cursor: not-allowed;
    pointer-events: none;
  }
  .nl-page-btn.nav-btn {
    border: 1px solid #e2e8f0;
    background: #ffffff;
  }

  /* Modal Styling */
  .modal-content {
    border-radius: 20px !important;
    overflow: hidden;
  }
  .modal.show .modal-dialog {
    animation: modalSlideIn 0.25s ease-out;
  }
  @keyframes modalSlideIn {
    0% { transform: translateY(20px) scale(0.98); opacity: 0; }
    100% { transform: translateY(0) scale(1); opacity: 1; }
  }
  .modal-header-gradient {
    background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 25%, #3730a3 60%, #4f46e5 100%) !important;
    padding: 18px 24px;
    border: 0;
    color: #ffffff;
  }
  .modal-icon-badge {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.15);
    color: #ffffff;
    font-size: 18px;
    flex-shrink: 0;
  }
  .modal-info-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px 16px;
  }
</style>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?php echo $this->include('admin/nav_bar') ?>
  </div>

  <div class="container-fluid px-4 content-inner pb-5" id="page_layout">

    <!-- Hero Banner with Stats matching Modern Dashboard -->
    <div class="nl-hero-banner">
      <div class="row align-items-center g-4">
        <div class="col-lg-7">
          <div class="nl-live-pill">
            <span class="nl-live-dot"></span>
            <span>Claims Adjudication Desk</span>
          </div>
          <h2 class="fw-bold text-white mb-2" style="font-size: 30px; letter-spacing: -0.02em;">Insurance Claims</h2>
          <p class="mb-0 text-white text-opacity-75" style="font-size: 13.5px; max-width: 560px; line-height: 1.55;">
            Process, adjudicate, and settle customer veterinary care reimbursements, accidental coverage claims, and surgical hospitalizations.
          </p>
        </div>

        <div class="col-lg-5">
          <div class="d-flex align-items-center gap-2 flex-wrap justify-content-lg-end">
            <div class="nl-stat-card">
              <span class="label">Total Claims</span>
              <div class="value"><?= number_format($totalClaims) ?></div>
            </div>
            <div class="nl-stat-card">
              <span class="label">Pending</span>
              <div class="value" style="color: #fde047;"><?= number_format($pendingCount) ?></div>
            </div>
            <div class="nl-stat-card">
              <span class="label">In Progress</span>
              <div class="value" style="color: #93c5fd;"><?= number_format($inProgressCount) ?></div>
            </div>
            <div class="nl-stat-card">
              <span class="label">Settled</span>
              <div class="value" style="color: #6ee7b7;"><?= number_format($settledCount) ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Segmented Tabs & Action Bar -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
      <div class="nl-segmented-tabs">
        <a href="<?= base_url('enquiries') ?>" class="nl-tab-pill">
          <i class="bi bi-inbox-fill"></i>
          <span>New Enquiries</span>
          <span class="nl-tab-badge"><?= $tabCounts['enquiries'] ?? 0 ?></span>
        </a>
        <a href="<?= base_url('quotations') ?>" class="nl-tab-pill">
          <i class="bi bi-file-earmark-text"></i>
          <span>Quotations</span>
          <span class="nl-tab-badge"><?= $tabCounts['quotations'] ?? 0 ?></span>
        </a>
        <a href="<?= base_url('active') ?>" class="nl-tab-pill">
          <i class="bi bi-shield-check"></i>
          <span>Active Policies</span>
          <span class="nl-tab-badge"><?= $tabCounts['active'] ?? 0 ?></span>
        </a>
        <a href="<?= base_url('expiring') ?>" class="nl-tab-pill">
          <i class="bi bi-clock-history"></i>
          <span>Expiring Soon</span>
          <span class="nl-tab-badge"><?= $tabCounts['expiring'] ?? 0 ?></span>
        </a>
        <a href="<?= base_url('claims') ?>" class="nl-tab-pill active">
          <i class="bi bi-clipboard2-pulse"></i>
          <span>Insurance Claims</span>
          <span class="nl-tab-badge"><?= $tabCounts['claims'] ?? $totalClaims ?></span>
        </a>
      </div>

      <div class="d-flex align-items-center gap-2">
        <button type="button" class="nl-btn-action nl-btn-primary" onclick="openAddClaimModal()">
          <i class="bi bi-plus-lg"></i>
          <span>Add New Claim</span>
        </button>
        <button type="button" class="nl-btn-action" onclick="exportClaimsToCSV()">
          <i class="bi bi-download"></i>
          <span>Export CSV</span>
        </button>
      </div>
    </div>

    <!-- Modern Search and Filter Toolbar -->
    <div class="nl-filter-card">
      <div class="row g-3 align-items-center">
        <div class="col-lg-6 col-md-5">
          <div class="nl-search-wrap">
            <i class="bi bi-search search-icon"></i>
            <input type="text" class="nl-search-input" id="searchClaims"
              placeholder="Search claims by client, contact, policy #, claim ID, type...">
          </div>
        </div>
        <div class="col-lg-3 col-md-4">
          <select class="nl-select w-100" id="statusFilter">
            <option value="all">All Claim Statuses</option>
            <option value="pending">Pending</option>
            <option value="in progress">In Progress</option>
            <option value="settled">Settled</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>
        <div class="col-lg-3 col-md-3">
          <div class="d-flex align-items-center justify-content-md-end gap-2">
            <label class="text-muted small mb-0 fw-semibold">Show</label>
            <select class="nl-select" style="width: 80px;" id="entriesPerPage">
              <option value="10">10</option>
              <option value="25">25</option>
              <option value="50">50</option>
              <option value="100">100</option>
              <option value="all">All</option>
            </select>
            <label class="text-muted small mb-0 fw-semibold">entries</label>
          </div>
        </div>
      </div>
    </div>

    <!-- Claims Data Table Card -->
    <div class="nl-table-card">
      <div class="table-responsive">
        <table class="table nl-table mb-0" id="claimsTable">
          <thead>
            <tr>
              <th class="text-center" style="width: 60px;">S.No.</th>
              <th>Claim Details</th>
              <th>Policy Info</th>
              <th>Claim Type</th>
              <th class="text-end">Claim Amount</th>
              <th class="text-center">Status</th>
              <th>Settlement Date</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            <?php if (!empty($claims)): ?>
              <?php
              $sno = 1;
              foreach ($claims as $claim):
                $avatar = getInitialsAvatar($claim['client_name'] ?? 'Client', $claim['id'] ?? $sno);
                $statusLower = strtolower($claim['status'] ?? 'pending');
                $statusPillClass = 'status-' . str_replace(' ', '-', $statusLower);
                $claimAmount = number_format((float)($claim['amount'] ?? 0), 2);
                $settlementDate = !empty($claim['settlement_date']) ? date('d M Y', strtotime($claim['settlement_date'])) : 'Pending';

                $claimJson = json_encode([
                    "claimId"        => $claim['id'],
                    "policyNumber"   => $claim['policy_number'] ?? 'N/A',
                    "clientName"     => $claim['client_name'] ?? 'N/A',
                    "contact"        => $claim['contact'] ?? 'N/A',
                    "claimType"      => $claim['claim_type'] ?? 'Medical',
                    "claimAmount"    => $claimAmount,
                    "status"         => $claim['status'] ?? 'Pending',
                    "settlementDate" => $claim['settlement_date'] ?? '',
                    "remarks"        => $claim['remarks'] ?? ''
                ]);
              ?>
                <tr class="claim-row"
                    data-status="<?= $statusLower ?>"
                    data-claim="<?= htmlspecialchars($claimJson, ENT_QUOTES, 'UTF-8') ?>">
                  
                  <td class="text-center fw-bold text-muted" style="font-size: 12px;"><?= $sno ?></td>
                  
                  <td>
                    <div class="d-flex align-items-center gap-3">
                      <div class="nl-avatar" style="background: <?= $avatar['bg'] ?>; color: <?= $avatar['color'] ?>;">
                        <?= $avatar['text'] ?>
                      </div>
                      <div>
                        <div class="fw-bold text-dark mb-0"><?= esc($claim['client_name'] ?? 'Unknown') ?></div>
                        <div class="d-flex align-items-center gap-2 text-muted small mt-0.5">
                          <span><i class="bi bi-telephone me-1" style="font-size: 11px;"></i><?= esc($claim['contact'] ?? 'N/A') ?></span>
                          <span>•</span>
                          <span class="font-monospace">Claim #<?= esc($claim['id']) ?></span>
                        </div>
                        <?php if (!empty($claim['remarks'])): ?>
                          <div class="text-muted small mt-1" style="font-size: 11px; color: #6366f1 !important;">
                            <i class="bi bi-chat-left-text me-1"></i>
                            <?= htmlspecialchars(mb_substr($claim['remarks'], 0, 45)) . (mb_strlen($claim['remarks']) > 45 ? '...' : '') ?>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </td>

                  <td>
                    <div class="d-inline-flex align-items-center gap-1.5 font-monospace fw-semibold text-dark" style="font-size: 12.5px;">
                      <i class="bi bi-file-earmark-text text-muted"></i>
                      <span>#<?= esc($claim['policy_number'] ?? 'N/A') ?></span>
                    </div>
                  </td>

                  <td>
                    <div class="badge-service">
                      <i class="bi bi-clipboard-pulse"></i>
                      <span><?= esc($claim['claim_type'] ?? 'Medical') ?></span>
                    </div>
                  </td>

                  <td class="text-end">
                    <div class="fw-bold text-dark fs-6" style="color: #0f172a !important;">
                      ₹<?= $claimAmount ?>
                    </div>
                  </td>

                  <td class="text-center">
                    <span class="status-pill <?= $statusPillClass ?>">
                      <span class="status-dot"></span>
                      <span><?= ucfirst($claim['status'] ?? 'Pending') ?></span>
                    </span>
                  </td>

                  <td>
                    <div class="d-flex align-items-center gap-1.5 text-muted small">
                      <i class="bi bi-calendar-event"></i>
                      <span><?= $settlementDate ?></span>
                    </div>
                  </td>

                  <td class="text-center">
                    <div class="d-flex align-items-center justify-content-center gap-1.5">
                      <button type="button" class="nl-action-btn view" title="View Claim Details"
                        onclick="showClaimDetails(<?= htmlspecialchars($claimJson, ENT_QUOTES, 'UTF-8') ?>)">
                        <i class="bi bi-eye"></i>
                      </button>
                      <button type="button" class="nl-action-btn edit" title="Update Claim Status"
                        onclick="openUpdateClaimModal(<?= htmlspecialchars($claimJson, ENT_QUOTES, 'UTF-8') ?>)">
                        <i class="bi bi-pencil-square"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php
                $sno++;
              endforeach;
              ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center py-5 text-muted">
                  <i class="bi bi-clipboard-x fs-2 d-block mb-2 text-secondary"></i>
                  No insurance claims found
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Modern Table Footer & Pagination -->
      <div class="d-flex flex-wrap justify-content-between align-items-center px-4 py-3 border-top" style="background: #fafafa;">
        <div class="text-muted small fw-medium" id="entriesInfo">
          Showing 1 to <?= min(10, $totalClaims) ?> of <?= $totalClaims ?> entries
        </div>
        <div class="d-flex align-items-center gap-1" id="paginationControls">
          <button class="nl-page-btn nav-btn" id="prevPage" disabled>
            <i class="bi bi-chevron-left"></i>
          </button>
          <div class="d-flex align-items-center gap-1" id="pageNumbers"></div>
          <button class="nl-page-btn nav-btn" id="nextPage">
            <i class="bi bi-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- ==========================================
     MODALS (Matching Booking Requests UI)
========================================== -->

<!-- 1. Claim Details Modal -->
<div class="modal fade" id="claimDetailsModal" tabindex="-1" aria-labelledby="claimDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden" style="max-height: 85vh;">
      <div class="modal-header modal-header-gradient">
        <div class="d-flex align-items-center gap-2.5">
          <div class="modal-icon-badge">
            <i class="bi bi-clipboard2-pulse text-warning"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold text-white mb-0" id="claimDetailsModalLabel">Claim Adjudication Summary</h5>
            <span class="text-white-50 small" id="claimHeaderSub">Claim #0000</span>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white opacity-75 hover-opacity-100" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4" style="overflow-y: auto; flex: 1;">
        <div class="row g-3">
          <div class="col-md-6">
            <div class="modal-info-card h-100">
              <h6 class="fw-bold text-dark mb-3"><i class="bi bi-person me-1.5 text-primary"></i>Claimant Profile</h6>
              <div class="d-flex flex-column gap-2 small">
                <div class="d-flex justify-content-between"><span class="text-muted">Client Name:</span><span class="fw-semibold text-dark" id="modalClaimantName"></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Contact Phone:</span><span class="fw-semibold text-dark" id="modalClaimantPhone"></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Active Policy:</span><span class="fw-semibold text-dark font-monospace" id="modalClaimPolicy"></span></div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="modal-info-card h-100">
              <h6 class="fw-bold text-dark mb-3"><i class="bi bi-currency-rupee me-1.5 text-success"></i>Financials & Settlement</h6>
              <div class="d-flex flex-column gap-2 small">
                <div class="d-flex justify-content-between"><span class="text-muted">Claimed Amount:</span><span class="fw-bold text-dark fs-6" id="modalClaimAmount" style="color: #4f46e5 !important;"></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Claim Category:</span><span class="fw-semibold text-dark" id="modalClaimType"></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Settlement Date:</span><span class="fw-semibold text-dark" id="modalSettlementDate"></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Current Status:</span><span class="badge rounded-pill" id="modalClaimStatusBadge"></span></div>
              </div>
            </div>
          </div>

          <div class="col-12" id="modalClaimRemarksRow" style="display: none;">
            <div class="modal-info-card">
              <h6 class="fw-bold text-dark mb-2"><i class="bi bi-chat-left-text me-1.5 text-info"></i>Adjudication Remarks</h6>
              <p class="mb-0 text-muted small" id="modalClaimRemarks"></p>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer border-top bg-light px-4 py-2.5 d-flex justify-content-between">
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
          Close
        </button>
        <button type="button" class="btn btn-sm btn-primary rounded-pill px-4 fw-semibold" id="claimModalUpdateBtn">
          <i class="bi bi-pencil-square me-1.5"></i>Update Claim
        </button>
      </div>
    </div>
  </div>
</div>

<!-- 2. Add New Claim Modal -->
<div class="modal fade" id="addClaimModal" tabindex="-1" aria-labelledby="addClaimModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden" style="max-height: 85vh;">
      <div class="modal-header modal-header-gradient">
        <div class="d-flex align-items-center gap-2.5">
          <div class="modal-icon-badge">
            <i class="bi bi-plus-circle text-warning"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold text-white mb-0" id="addClaimModalLabel">Lodge New Insurance Claim</h5>
            <span class="text-white-50 small">Record incoming medical or accident reimbursement request</span>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white opacity-75 hover-opacity-100" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4" style="overflow-y: auto; flex: 1;">
        <!-- Policy Search for Claim -->
        <div class="modal-info-card mb-4">
          <label class="form-label fw-bold small text-muted text-uppercase mb-2">Search Active Policy (Number / Client Name / Phone)</label>
          <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control" id="searchPolicyInput" placeholder="e.g. PF123456 or Client Phone" oninput="searchPolicyForClaim(this.value)">
          </div>
          <div id="policySearchResults" class="list-group mt-2 shadow-sm rounded-3" style="max-height: 180px; overflow-y: auto; display: none;"></div>
        </div>

        <form id="addClaimForm">
          <input type="hidden" id="claim_policy_id" required>

          <div class="modal-info-card mb-3" id="selectedPolicyCard" style="display: none;">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <span class="text-muted small d-block">Selected Active Policy:</span>
                <strong class="text-dark" id="selectedPolicyName"></strong>
              </div>
              <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" onclick="clearSelectedPolicy()">Change</button>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">CLAIM TYPE</label>
              <select class="form-select rounded-3" id="claim_type" required>
                <option value="Accidental Injury">Accidental Injury</option>
                <option value="Illness & Hospitalization">Illness & Hospitalization</option>
                <option value="Surgery / Procedure">Surgery / Procedure</option>
                <option value="Routine Vet Consultation">Routine Vet Consultation</option>
                <option value="Medication Reimbursement">Medication Reimbursement</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">CLAIM AMOUNT (₹)</label>
              <div class="input-group">
                <span class="input-group-text bg-white">₹</span>
                <input type="number" class="form-control rounded-end-3" id="claim_amount" placeholder="e.g. 3500" required>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold small text-muted">DESCRIPTION & REMARKS</label>
            <textarea class="form-control rounded-3" id="claim_remarks" rows="3" placeholder="Provide vet diagnosis, hospital details, and bill breakdown..."></textarea>
          </div>
        </form>
      </div>

      <div class="modal-footer border-top bg-light px-4 py-2.5 d-flex justify-content-between">
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
          Cancel
        </button>
        <button type="button" class="btn btn-sm btn-primary rounded-pill px-4 fw-semibold" onclick="submitNewClaim()">
          <i class="bi bi-check2-circle me-1.5"></i>Lodge Claim
        </button>
      </div>
    </div>
  </div>
</div>

<!-- 3. Update Claim Modal -->
<div id="updateClaimModal" class="modal fade" tabindex="-1" aria-labelledby="updateClaimModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
    <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
      <div class="modal-header modal-header-gradient">
        <div class="d-flex align-items-center gap-2.5">
          <div class="modal-icon-badge">
            <i class="bi bi-pencil-square text-warning"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold text-white mb-0" id="updateClaimModalLabel">Update Claim Adjudication</h5>
            <span class="text-white-50 small">Modify settlement status and approved amount</span>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white opacity-75 hover-opacity-100" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4">
        <input type="hidden" id="edit_claim_id">

        <div class="mb-3">
          <label class="form-label fw-bold small text-muted text-uppercase">Claim Status</label>
          <select class="form-select rounded-3" id="edit_claim_status" onchange="toggleSettlementDate(this.value)">
            <option value="Pending">🕒 Pending Review</option>
            <option value="In Progress">🔄 In Progress</option>
            <option value="Settled">✅ Settled</option>
            <option value="Rejected">❌ Rejected</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold small text-muted text-uppercase">Approved Claim Amount (₹)</label>
          <div class="input-group">
            <span class="input-group-text bg-white">₹</span>
            <input type="number" class="form-control rounded-end-3" id="edit_claim_amount">
          </div>
        </div>

        <div class="mb-3" id="editSettlementDateWrap">
          <label class="form-label fw-bold small text-muted text-uppercase">Settlement Date</label>
          <input type="date" class="form-control rounded-3" id="edit_settlement_date">
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold small text-muted text-uppercase">Adjudication Notes</label>
          <textarea class="form-control rounded-3" id="edit_claim_remarks" rows="3" placeholder="Remarks for approval or rejection..."></textarea>
        </div>
      </div>

      <div class="modal-footer border-top bg-light px-4 py-2.5 d-flex justify-content-between">
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
          Cancel
        </button>
        <button type="button" class="btn btn-sm btn-dark rounded-pill px-4 fw-semibold" onclick="submitClaimUpdate()">
          Save Changes
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ==========================================
     JAVASCRIPT: Pagination & Actions
========================================== -->
<script>
class ClaimsPaginationManager {
    constructor() {
        this.currentPage = 1;
        this.entriesPerPage = 10;
        this.allRows = Array.from(document.querySelectorAll('.claim-row'));
        this.filteredRows = this.allRows;
        this.totalEntries = this.allRows.length;
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.updateDisplay();
    }

    setupEventListeners() {
        // Entries per page
        const entriesSelect = document.getElementById('entriesPerPage');
        if (entriesSelect) {
            entriesSelect.addEventListener('change', (e) => {
                const val = e.target.value;
                this.entriesPerPage = val === 'all' ? this.totalEntries : parseInt(val);
                this.currentPage = 1;
                this.updateDisplay();
            });
        }

        // Prev page
        const prevBtn = document.getElementById('prevPage');
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.updateDisplay();
                }
            });
        }

        // Next page
        const nextBtn = document.getElementById('nextPage');
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                const totalPages = this.getTotalPages();
                if (this.currentPage < totalPages) {
                    this.currentPage++;
                    this.updateDisplay();
                }
            });
        }

        // Search input
        const searchInput = document.getElementById('searchClaims');
        if (searchInput) {
            searchInput.addEventListener('input', () => this.applyCombinedFilters());
        }

        // Status filter
        const statusFilter = document.getElementById('statusFilter');
        if (statusFilter) {
            statusFilter.addEventListener('change', () => this.applyCombinedFilters());
        }
    }

    getTotalPages() {
        return Math.ceil(this.filteredRows.length / this.entriesPerPage) || 1;
    }

    applyCombinedFilters() {
        const query = (document.getElementById('searchClaims')?.value || '').toLowerCase().trim();
        const status = (document.getElementById('statusFilter')?.value || 'all').toLowerCase().trim();

        this.filteredRows = this.allRows.filter(row => {
            const text = row.textContent.toLowerCase();
            const matchesQuery = !query || text.includes(query);
            const rowStatus = (row.dataset.status || '').toLowerCase();
            const matchesStatus = status === 'all' || rowStatus === status;
            return matchesQuery && matchesStatus;
        });

        this.currentPage = 1;
        this.updateDisplay();
    }

    updateDisplay() {
        const start = (this.currentPage - 1) * this.entriesPerPage;
        const end = Math.min(start + this.entriesPerPage, this.filteredRows.length);

        this.allRows.forEach(row => row.style.display = 'none');

        for (let i = start; i < end; i++) {
            if (this.filteredRows[i]) {
                this.filteredRows[i].style.display = '';
            }
        }

        // Update info counter
        const entriesInfo = document.getElementById('entriesInfo');
        if (entriesInfo) {
            if (this.filteredRows.length === 0) {
                entriesInfo.textContent = 'Showing 0 entries';
            } else {
                entriesInfo.textContent = `Showing ${start + 1} to ${end} of ${this.filteredRows.length} entries`;
            }
        }

        this.updatePaginationControls();
    }

    updatePaginationControls() {
        const totalPages = this.getTotalPages();
        const prevBtn = document.getElementById('prevPage');
        const nextBtn = document.getElementById('nextPage');
        const pageNumbersContainer = document.getElementById('pageNumbers');

        if (prevBtn) prevBtn.disabled = this.currentPage === 1;
        if (nextBtn) nextBtn.disabled = this.currentPage === totalPages || this.filteredRows.length === 0;

        if (!pageNumbersContainer) return;

        let pageHtml = '';
        const maxPages = 5;

        if (totalPages <= maxPages) {
            for (let i = 1; i <= totalPages; i++) {
                pageHtml += this.getPageButtonHtml(i);
            }
        } else {
            if (this.currentPage <= 3) {
                for (let i = 1; i <= 4; i++) pageHtml += this.getPageButtonHtml(i);
                pageHtml += `<span class="nl-page-btn disabled" disabled>...</span>`;
                pageHtml += this.getPageButtonHtml(totalPages);
            } else if (this.currentPage >= totalPages - 2) {
                pageHtml += this.getPageButtonHtml(1);
                pageHtml += `<span class="nl-page-btn disabled" disabled>...</span>`;
                for (let i = totalPages - 3; i <= totalPages; i++) pageHtml += this.getPageButtonHtml(i);
            } else {
                pageHtml += this.getPageButtonHtml(1);
                pageHtml += `<span class="nl-page-btn disabled" disabled>...</span>`;
                for (let i = this.currentPage - 1; i <= this.currentPage + 1; i++) pageHtml += this.getPageButtonHtml(i);
                pageHtml += `<span class="nl-page-btn disabled" disabled>...</span>`;
                pageHtml += this.getPageButtonHtml(totalPages);
            }
        }

        pageNumbersContainer.innerHTML = pageHtml;

        pageNumbersContainer.querySelectorAll('.page-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.currentPage = parseInt(e.target.dataset.page);
                this.updateDisplay();
            });
        });
    }

    getPageButtonHtml(num) {
        const activeClass = num === this.currentPage ? 'active' : '';
        return `<button class="nl-page-btn ${activeClass} page-btn" data-page="${num}">${num}</button>`;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    window.claimsPagination = new ClaimsPaginationManager();
});

function showClaimDetails(claim) {
    if (typeof claim === 'string') {
        try { claim = JSON.parse(claim); } catch(e){}
    }

    document.getElementById('claimHeaderSub').textContent = `Claim #${claim.claimId || 'N/A'}`;
    document.getElementById('modalClaimantName').textContent = claim.clientName || 'N/A';
    document.getElementById('modalClaimantPhone').textContent = claim.contact || 'N/A';
    document.getElementById('modalClaimPolicy').textContent = '#' + (claim.policyNumber || 'N/A');

    document.getElementById('modalClaimAmount').textContent = '₹' + (claim.claimAmount || '0.00');
    document.getElementById('modalClaimType').textContent = claim.claimType || 'N/A';
    document.getElementById('modalSettlementDate').textContent = claim.settlementDate || 'Pending Settlement';

    const statusBadge = document.getElementById('modalClaimStatusBadge');
    statusBadge.textContent = claim.status || 'Pending';
    statusBadge.className = 'badge rounded-pill';
    const st = (claim.status || '').toLowerCase();
    if (st === 'settled') statusBadge.classList.add('bg-success', 'bg-opacity-10', 'text-success', 'border', 'border-success', 'border-opacity-25');
    else if (st === 'in progress') statusBadge.classList.add('bg-info', 'bg-opacity-10', 'text-primary', 'border', 'border-info', 'border-opacity-25');
    else if (st === 'rejected') statusBadge.classList.add('bg-danger', 'bg-opacity-10', 'text-danger', 'border', 'border-danger', 'border-opacity-25');
    else statusBadge.classList.add('bg-warning', 'bg-opacity-10', 'text-warning', 'border', 'border-warning', 'border-opacity-25');

    if (claim.remarks) {
        document.getElementById('modalClaimRemarks').textContent = claim.remarks;
        document.getElementById('modalClaimRemarksRow').style.display = 'block';
    } else {
        document.getElementById('modalClaimRemarksRow').style.display = 'none';
    }

    document.getElementById('claimModalUpdateBtn').onclick = () => {
        bootstrap.Modal.getInstance(document.getElementById('claimDetailsModal')).hide();
        openUpdateClaimModal(claim);
    };

    const modalEl = document.getElementById('claimDetailsModal');
    let bsModal = bootstrap.Modal.getInstance(modalEl);
    if (!bsModal) bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
}

function openAddClaimModal() {
    document.getElementById('searchPolicyInput').value = '';
    document.getElementById('policySearchResults').style.display = 'none';
    document.getElementById('claim_policy_id').value = '';
    document.getElementById('selectedPolicyCard').style.display = 'none';
    document.getElementById('claim_amount').value = '';
    document.getElementById('claim_remarks').value = '';

    const modalEl = document.getElementById('addClaimModal');
    let bsModal = bootstrap.Modal.getInstance(modalEl);
    if (!bsModal) bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
}

let searchTimeout = null;
function searchPolicyForClaim(val) {
    clearTimeout(searchTimeout);
    val = val.trim();
    const resultsBox = document.getElementById('policySearchResults');

    if (val.length < 2) {
        resultsBox.style.display = 'none';
        return;
    }

    searchTimeout = setTimeout(() => {
        fetch('<?= base_url('insurance/searchActivePolicies') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ query: val })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success && data.policies && data.policies.length > 0) {
                resultsBox.innerHTML = '';
                data.policies.forEach(p => {
                    const item = document.createElement('a');
                    item.href = 'javascript:void(0)';
                    item.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-3 small';
                    item.innerHTML = `
                        <div>
                          <strong>#${p.policy_number}</strong> - ${p.client_name || 'Client'}
                          <span class="text-muted d-block" style="font-size: 11px;">Pet: ${p.pet_name || 'Dog'}</span>
                        </div>
                        <span class="badge bg-primary rounded-pill">Select</span>
                    `;
                    item.onclick = () => selectPolicyForClaim(p);
                    resultsBox.appendChild(item);
                });
                resultsBox.style.display = 'block';
            } else {
                resultsBox.innerHTML = '<div class="p-3 text-muted small text-center">No active policies found matching search.</div>';
                resultsBox.style.display = 'block';
            }
        });
    }, 300);
}

function selectPolicyForClaim(policy) {
    document.getElementById('claim_policy_id').value = policy.id;
    document.getElementById('selectedPolicyName').textContent = `#${policy.policy_number} - ${policy.client_name} (${policy.pet_name || 'Pet'})`;
    document.getElementById('selectedPolicyCard').style.display = 'block';
    document.getElementById('policySearchResults').style.display = 'none';
}

function clearSelectedPolicy() {
    document.getElementById('claim_policy_id').value = '';
    document.getElementById('selectedPolicyCard').style.display = 'none';
    document.getElementById('searchPolicyInput').value = '';
}

function submitNewClaim() {
    const policyId = document.getElementById('claim_policy_id').value;
    const claimType = document.getElementById('claim_type').value;
    const amount = document.getElementById('claim_amount').value;
    const remarks = document.getElementById('claim_remarks').value;

    if (!policyId) {
        alert('Please select an active policy.');
        return;
    }
    if (!amount || parseFloat(amount) <= 0) {
        alert('Please enter a valid claim amount.');
        return;
    }

    fetch('<?= base_url('insurance/addClaim') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ policy_id: policyId, claim_type: claimType, amount: amount, remarks: remarks })
    })
    .then(r => r.json())
    .then(data => {
        if (data.status) {
            alert('Claim lodged successfully!');
            location.reload();
        } else {
            alert(data.message || 'Error creating claim.');
        }
    });
}

function openUpdateClaimModal(claim) {
    if (typeof claim === 'string') {
        try { claim = JSON.parse(claim); } catch(e){}
    }

    document.getElementById('edit_claim_id').value = claim.claimId;
    document.getElementById('edit_claim_status').value = claim.status || 'Pending';
    document.getElementById('edit_claim_amount').value = (claim.claimAmount || '').replace(/,/g, '');
    document.getElementById('edit_settlement_date').value = claim.settlementDate || '';
    document.getElementById('edit_claim_remarks').value = claim.remarks || '';

    toggleSettlementDate(claim.status || 'Pending');

    const modalEl = document.getElementById('updateClaimModal');
    let bsModal = bootstrap.Modal.getInstance(modalEl);
    if (!bsModal) bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
}

function toggleSettlementDate(status) {
    const wrap = document.getElementById('editSettlementDateWrap');
    if (status === 'Settled') {
        wrap.style.display = 'block';
        if (!document.getElementById('edit_settlement_date').value) {
            document.getElementById('edit_settlement_date').value = new Date().toISOString().split('T')[0];
        }
    } else {
        wrap.style.display = 'none';
    }
}

function submitClaimUpdate() {
    const claimId = document.getElementById('edit_claim_id').value;
    const status = document.getElementById('edit_claim_status').value;
    const amount = document.getElementById('edit_claim_amount').value;
    const settlementDate = document.getElementById('edit_settlement_date').value;
    const remarks = document.getElementById('edit_claim_remarks').value;

    const formData = new FormData();
    formData.append('claimId', claimId);
    formData.append('status', status);
    formData.append('claimAmount', amount);
    formData.append('settlementDate', settlementDate);
    formData.append('remarks', remarks);

    fetch('<?= base_url('insurance/updateClaim') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.status) {
            alert('Claim updated successfully!');
            location.reload();
        } else {
            alert(data.message || 'Error updating claim.');
        }
    });
}

function exportClaimsToCSV() {
    const rows = Array.from(document.querySelectorAll('#claimsTable tbody tr.claim-row'));
    if (!rows.length) {
        alert('No claims to export.');
        return;
    }

    let csv = 'S.No,Claim ID,Policy Number,Client Name,Contact,Claim Type,Amount,Status,Settlement Date\n';
    rows.forEach((r, idx) => {
        const d = JSON.parse(r.dataset.claim || '{}');
        csv += `"${idx + 1}","${d.claimId || ''}","${d.policyNumber || ''}","${d.clientName || ''}","${d.contact || ''}","${d.claimType || ''}","${d.claimAmount || ''}","${d.status || ''}","${d.settlementDate || ''}"\n`;
    });

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `insurance_claims_${new Date().toISOString().split('T')[0]}.csv`;
    link.click();
}
</script>

<?php echo $this->endSection(); ?>