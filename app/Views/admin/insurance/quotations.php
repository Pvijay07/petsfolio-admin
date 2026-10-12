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
$totalQuotes = count($quotations ?? []);
$totalQuoteAmount = 0;
if (!empty($quotations)) {
    foreach ($quotations as $q) {
        $totalQuoteAmount += (float)($q->amount ?? 0);
    }
}
$avgQuoteAmount = $totalQuotes > 0 ? round($totalQuoteAmount / $totalQuotes) : 0;
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
    background: #22c55e;
    box-shadow: 0 0 10px #22c55e;
    animation: nl-pulse 2s infinite;
  }
  @keyframes nl-pulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(34, 197, 94, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
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

  .badge-service {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 11px;
    border-radius: 50px;
    font-size: 11.5px;
    font-weight: 600;
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
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
  .status-sent { background: #dcfce7; color: #15803d; }
  .status-sent .status-dot { background: #16a34a; }

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
            <span>Quotation Dispatch Center</span>
          </div>
          <h2 class="fw-bold text-white mb-2" style="font-size: 30px; letter-spacing: -0.02em;">Insurance Quotations</h2>
          <p class="mb-0 text-white text-opacity-75" style="font-size: 13.5px; max-width: 560px; line-height: 1.55;">
            Track sent insurance premium proposals, review customer contact channels, and monitor conversion velocity across active quotes.
          </p>
        </div>

        <div class="col-lg-5">
          <div class="d-flex align-items-center gap-2 flex-wrap justify-content-lg-end">
            <div class="nl-stat-card">
              <span class="label">Dispatched Quotes</span>
              <div class="value"><?= number_format($totalQuotes) ?></div>
            </div>
            <div class="nl-stat-card">
              <span class="label">Total Pipeline</span>
              <div class="value" style="color: #6ee7b7; font-size: 20px;">₹<?= number_format($totalQuoteAmount) ?></div>
            </div>
            <div class="nl-stat-card">
              <span class="label">Avg. Premium</span>
              <div class="value" style="color: #93c5fd; font-size: 20px;">₹<?= number_format($avgQuoteAmount) ?></div>
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
        <a href="<?= base_url('quotations') ?>" class="nl-tab-pill active">
          <i class="bi bi-file-earmark-text"></i>
          <span>Quotations</span>
          <span class="nl-tab-badge"><?= $tabCounts['quotations'] ?? $totalQuotes ?></span>
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
        <a href="<?= base_url('claims') ?>" class="nl-tab-pill">
          <i class="bi bi-clipboard2-pulse"></i>
          <span>Insurance Claims</span>
          <span class="nl-tab-badge"><?= $tabCounts['claims'] ?? 0 ?></span>
        </a>
      </div>

      <div class="d-flex align-items-center gap-2">
        <button type="button" class="nl-btn-action" onclick="exportQuotationsToCSV()">
          <i class="bi bi-download"></i>
          <span>Export CSV</span>
        </button>
      </div>
    </div>

    <!-- Modern Search and Filter Toolbar -->
    <div class="nl-filter-card">
      <div class="row g-3 align-items-center">
        <div class="col-lg-8 col-md-7">
          <div class="nl-search-wrap">
            <i class="bi bi-search search-icon"></i>
            <input type="text" class="nl-search-input" id="searchQuotations"
              placeholder="Search quotations by client name, phone, email, policy ID...">
          </div>
        </div>
        <div class="col-lg-4 col-md-5">
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

    <!-- Quotations Data Table Card -->
    <div class="nl-table-card">
      <div class="table-responsive">
        <table class="table nl-table mb-0" id="quotationsTable">
          <thead>
            <tr>
              <th class="text-center" style="width: 60px;">S.No.</th>
              <th>Client Details</th>
              <th>Policy / Ref ID</th>
              <th>Insurance Type</th>
              <th class="text-end">Quoted Premium</th>
              <th class="text-center">Status</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            <?php if (!empty($quotations)): ?>
              <?php
              $sno = 1;
              foreach ($quotations as $quote):
                $avatar = getInitialsAvatar($quote->full_name ?? 'Client', $sno);
                $formattedAmount = number_format((float)($quote->amount ?? 0), 2);
                $quoteJson = json_encode([
                    "full_name" => $quote->full_name ?? 'N/A',
                    "phone"     => $quote->phone ?? 'N/A',
                    "email"     => $quote->email ?? 'N/A',
                    "policy_id" => $quote->policy_id ?? 'N/A',
                    "amount"    => $formattedAmount,
                    "remarks"   => $quote->remarks ?? ''
                ]);
              ?>
                <tr class="quote-row" data-quote="<?= htmlspecialchars($quoteJson, ENT_QUOTES, 'UTF-8') ?>">
                  <td class="text-center fw-bold text-muted" style="font-size: 12px;"><?= $sno ?></td>
                  
                  <td>
                    <div class="d-flex align-items-center gap-3">
                      <div class="nl-avatar" style="background: <?= $avatar['bg'] ?>; color: <?= $avatar['color'] ?>;">
                        <?= $avatar['text'] ?>
                      </div>
                      <div>
                        <div class="fw-bold text-dark mb-0"><?= esc($quote->full_name ?? 'Unknown') ?></div>
                        <div class="d-flex align-items-center text-muted small mt-0.5">
                          <i class="bi bi-telephone me-1" style="font-size: 11px;"></i>
                          <span><?= esc($quote->phone ?? 'N/A') ?></span>
                        </div>
                        <?php if (!empty($quote->email)): ?>
                          <div class="text-muted small" style="font-size: 11.5px;">
                            <i class="bi bi-envelope me-1"></i>
                            <span><?= esc($quote->email) ?></span>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </td>

                  <td>
                    <div class="d-inline-flex align-items-center gap-1.5 font-monospace fw-semibold text-dark" style="font-size: 12.5px;">
                      <i class="bi bi-file-earmark-text text-muted"></i>
                      <span>#<?= esc($quote->policy_id ?? 'N/A') ?></span>
                    </div>
                  </td>

                  <td>
                    <div class="badge-service">
                      <i class="bi bi-shield-check"></i>
                      <span>Dog Insurance</span>
                    </div>
                  </td>

                  <td class="text-end">
                    <div class="fw-bold text-dark fs-6" style="color: #0f172a !important;">
                      ₹<?= $formattedAmount ?>
                    </div>
                  </td>

                  <td class="text-center">
                    <span class="status-pill status-sent">
                      <span class="status-dot"></span>
                      <span>Quote Sent</span>
                    </span>
                  </td>

                  <td class="text-center">
                    <button type="button" class="nl-action-btn view" title="View Quotation"
                      onclick="showQuotationDetails(<?= htmlspecialchars($quoteJson, ENT_QUOTES, 'UTF-8') ?>)">
                      <i class="bi bi-eye"></i>
                    </button>
                  </td>
                </tr>
              <?php
                $sno++;
              endforeach;
              ?>
            <?php else: ?>
              <tr>
                <td colspan="7" class="text-center py-5 text-muted">
                  <i class="bi bi-file-earmark-text fs-2 d-block mb-2 text-secondary"></i>
                  No insurance quotations found
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Modern Table Footer & Pagination -->
      <div class="d-flex flex-wrap justify-content-between align-items-center px-4 py-3 border-top" style="background: #fafafa;">
        <div class="text-muted small fw-medium" id="entriesInfo">
          Showing 1 to <?= min(10, $totalQuotes) ?> of <?= $totalQuotes ?> entries
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

<!-- Quotation Details Modal (Matching Booking Requests UI) -->
<div class="modal fade" id="quotationDetailsModal" tabindex="-1" aria-labelledby="quotationDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden" style="max-height: 85vh;">
      <div class="modal-header modal-header-gradient">
        <div class="d-flex align-items-center gap-2.5">
          <div class="modal-icon-badge">
            <i class="bi bi-file-earmark-text text-warning"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold text-white mb-0" id="quotationDetailsModalLabel">Quotation Overview</h5>
            <span class="text-white-50 small">Detailed proposal and premium breakdown</span>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white opacity-75 hover-opacity-100" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4" style="overflow-y: auto; flex: 1;">
        <div class="row g-3">
          <div class="col-md-6">
            <div class="modal-info-card h-100">
              <h6 class="fw-bold text-dark mb-3"><i class="bi bi-person me-1.5 text-primary"></i>Recipient Profile</h6>
              <div class="d-flex flex-column gap-2 small">
                <div class="d-flex justify-content-between"><span class="text-muted">Client Name:</span><span class="fw-semibold text-dark" id="modalClientName"></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Phone Number:</span><span class="fw-semibold text-dark" id="modalPhone"></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Email Address:</span><span class="fw-semibold text-dark" id="modalEmail"></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Policy / Ref ID:</span><span class="fw-semibold text-dark font-monospace" id="modalPolicyId"></span></div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="modal-info-card h-100">
              <h6 class="fw-bold text-dark mb-3"><i class="bi bi-currency-rupee me-1.5 text-success"></i>Financial Summary</h6>
              <div class="d-flex flex-column gap-2 small">
                <div class="d-flex justify-content-between"><span class="text-muted">Quoted Premium:</span><span class="fw-bold text-dark fs-5" id="modalAmount" style="color: #4f46e5 !important;"></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Plan Term:</span><span class="fw-semibold text-dark">1 Year Annual Coverage</span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Status:</span><span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill">Dispatched</span></div>
              </div>
            </div>
          </div>

          <div class="col-12" id="modalRemarksRow" style="display: none;">
            <div class="modal-info-card">
              <h6 class="fw-bold text-dark mb-2"><i class="bi bi-chat-left-text me-1.5 text-info"></i>Special Notes</h6>
              <p class="mb-0 text-muted small" id="modalRemarks"></p>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer border-top bg-light px-4 py-2.5 d-flex justify-content-between">
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
          Close
        </button>
        <button type="button" class="btn btn-sm btn-primary rounded-pill px-4 fw-semibold" onclick="window.print()">
          <i class="bi bi-printer me-1.5"></i>Print Quote
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ==========================================
     JAVASCRIPT: Pagination & Actions
========================================== -->
<script>
class QuotationsPaginationManager {
    constructor() {
        this.currentPage = 1;
        this.entriesPerPage = 10;
        this.allRows = Array.from(document.querySelectorAll('.quote-row'));
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
        const searchInput = document.getElementById('searchQuotations');
        if (searchInput) {
            searchInput.addEventListener('input', () => this.applyCombinedFilters());
        }
    }

    getTotalPages() {
        return Math.ceil(this.filteredRows.length / this.entriesPerPage) || 1;
    }

    applyCombinedFilters() {
        const query = (document.getElementById('searchQuotations')?.value || '').toLowerCase().trim();

        this.filteredRows = this.allRows.filter(row => {
            const text = row.textContent.toLowerCase();
            return !query || text.includes(query);
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
    window.quotesPagination = new QuotationsPaginationManager();
});

function showQuotationDetails(quote) {
    if (typeof quote === 'string') {
        try { quote = JSON.parse(quote); } catch(e){}
    }

    document.getElementById('modalClientName').textContent = quote.full_name || 'N/A';
    document.getElementById('modalPhone').textContent = quote.phone || 'N/A';
    document.getElementById('modalEmail').textContent = quote.email || 'N/A';
    document.getElementById('modalPolicyId').textContent = '#' + (quote.policy_id || 'N/A');
    document.getElementById('modalAmount').textContent = '₹' + (quote.amount || '0.00');

    if (quote.remarks) {
        document.getElementById('modalRemarks').textContent = quote.remarks;
        document.getElementById('modalRemarksRow').style.display = 'block';
    } else {
        document.getElementById('modalRemarksRow').style.display = 'none';
    }

    const modalEl = document.getElementById('quotationDetailsModal');
    let bsModal = bootstrap.Modal.getInstance(modalEl);
    if (!bsModal) bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
}

function exportQuotationsToCSV() {
    const rows = Array.from(document.querySelectorAll('#quotationsTable tbody tr.quote-row'));
    if (!rows.length) {
        alert('No quotations to export.');
        return;
    }

    let csv = 'S.No,Client Name,Phone,Email,Policy ID,Quoted Amount\n';
    rows.forEach((r, idx) => {
        const d = JSON.parse(r.dataset.quote || '{}');
        csv += `"${idx + 1}","${d.full_name || ''}","${d.phone || ''}","${d.email || ''}","${d.policy_id || ''}","${d.amount || ''}"\n`;
    });

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `insurance_quotations_${new Date().toISOString().split('T')[0]}.csv`;
    link.click();
}
</script>

<?php echo $this->endSection(); ?>