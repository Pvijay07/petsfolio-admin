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
$totalEnquiries = count($enquiries ?? []);
$pendingCount = 0;
$approvedCount = 0;
$rejectedCount = 0;

if (!empty($enquiries)) {
    foreach ($enquiries as $e) {
        $st = strtolower($e->status ?? 'pending');
        if ($st === 'approved') {
            $approvedCount++;
        } elseif ($st === 'rejected') {
            $rejectedCount++;
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

  /* Badges & Status Pills */
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
  .status-pending { background: #fef3c7; color: #b45309; }
  .status-pending .status-dot { background: #d97706; }
  .status-approved, .status-active { background: #dcfce7; color: #15803d; }
  .status-approved .status-dot, .status-active .status-dot { background: #16a34a; }
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
  .nl-action-btn.quote { color: #0284c7; }
  .nl-action-btn.quote:hover { background: #0284c7; color: #ffffff; border-color: #0284c7; }
  .nl-action-btn.edit { color: #059669; }
  .nl-action-btn.edit:hover { background: #059669; color: #ffffff; border-color: #059669; }
  .nl-action-btn.delete { color: #ef4444; }
  .nl-action-btn.delete:hover { background: #ef4444; color: #ffffff; border-color: #ef4444; }

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

  /* Modal Styling matching Booking Requests */
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
            <span>Live Insurance Pipeline</span>
          </div>
          <h2 class="fw-bold text-white mb-2" style="font-size: 30px; letter-spacing: -0.02em;">Insurance Enquiries</h2>
          <p class="mb-0 text-white text-opacity-75" style="font-size: 13.5px; max-width: 560px; line-height: 1.55;">
            Review inbound pet insurance enquiries, verify pet medical records, send tailored quotations, and convert prospects into active policies.
          </p>
        </div>

        <div class="col-lg-5">
          <div class="d-flex align-items-center gap-2 flex-wrap justify-content-lg-end">
            <div class="nl-stat-card">
              <span class="label">New Enquiries</span>
              <div class="value"><?= number_format($totalEnquiries) ?></div>
            </div>
            <div class="nl-stat-card">
              <span class="label">Pending Action</span>
              <div class="value" style="color: #fde047;"><?= number_format($pendingCount) ?></div>
            </div>
            <div class="nl-stat-card">
              <span class="label">Approved</span>
              <div class="value" style="color: #6ee7b7;"><?= number_format($approvedCount) ?></div>
            </div>
            <div class="nl-stat-card">
              <span class="label">Rejected</span>
              <div class="value" style="color: #fca5a5;"><?= number_format($rejectedCount) ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Segmented Tabs & Action Bar -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
      <div class="nl-segmented-tabs">
        <a href="<?= base_url('enquiries') ?>" class="nl-tab-pill active">
          <i class="bi bi-inbox-fill"></i>
          <span>New Enquiries</span>
          <span class="nl-tab-badge"><?= $tabCounts['enquiries'] ?? $totalEnquiries ?></span>
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
        <a href="<?= base_url('claims') ?>" class="nl-tab-pill">
          <i class="bi bi-clipboard2-pulse"></i>
          <span>Insurance Claims</span>
          <span class="nl-tab-badge"><?= $tabCounts['claims'] ?? 0 ?></span>
        </a>
      </div>

      <div class="d-flex align-items-center gap-2">
        <button type="button" class="nl-btn-action nl-btn-primary" onclick="showAddEnquiryModal()">
          <i class="bi bi-plus-lg"></i>
          <span>Add Enquiry</span>
        </button>
        <button type="button" class="nl-btn-action" onclick="exportEnquiriesToCSV()">
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
            <input type="text" class="nl-search-input" id="searchEnquiries"
              placeholder="Search enquiries by client name, phone, pet breed, ID...">
          </div>
        </div>
        <div class="col-lg-3 col-md-4">
          <select class="nl-select w-100" id="statusFilter">
            <option value="all">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
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

    <!-- Enquiries Data Table Card -->
    <div class="nl-table-card">
      <div class="table-responsive">
        <table class="table nl-table mb-0" id="enquiriesTable">
          <thead>
            <tr>
              <th class="text-center" style="width: 60px;">S.No.</th>
              <th>Client Details</th>
              <th>Pet Details</th>
              <th>Insurance Type</th>
              <th class="text-center">Enquiry Date</th>
              <th class="text-center">Status</th>
              <th class="text-center" style="width: 140px;">Actions</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            <?php if (!empty($enquiries)): ?>
              <?php
              $sno = 1;
              foreach ($enquiries as $enquiry):
                $avatar = getInitialsAvatar($enquiry->full_name ?? 'Client', $enquiry->id ?? $sno);
                $formattedDate = !empty($enquiry->created_at) ? date('d M Y', strtotime($enquiry->created_at)) : 'N/A';
                $statusLower = strtolower($enquiry->status ?? 'pending');
                $statusPillClass = 'status-' . $statusLower;
                $petJson = json_encode([
                    "id"              => $enquiry->id,
                    "policy_number"   => $enquiry->policy_number ?? '',
                    "full_name"       => $enquiry->full_name ?? 'N/A',
                    "phone"           => $enquiry->phone ?? '',
                    "email"           => $enquiry->email ?? '',
                    "coverage_type"   => $enquiry->coverage_type ?? 'Standard',
                    "duration_years"  => $enquiry->duration_years ?? 1,
                    "start_date"      => $enquiry->start_date ?? '',
                    "end_date"        => $enquiry->end_date ?? '',
                    "status"          => $enquiry->status ?? 'Pending',
                    "remarks"         => $enquiry->remarks ?? '',
                    "created_at"      => $enquiry->created_at ?? '',
                    "pet_name"        => $enquiry->name ?? 'N/A',
                    "breed"           => $enquiry->breed ?? 'N/A',
                    "dob"             => $enquiry->dob ?? 'N/A',
                    "gender"          => $enquiry->gender ?? 'N/A',
                    "last_vaccination"=> $enquiry->last_vaccination ?? 'N/A'
                ]);
              ?>
                <tr class="enquiry-row"
                    data-status="<?= $statusLower ?>"
                    data-pet-details="<?= htmlspecialchars($petJson, ENT_QUOTES, 'UTF-8') ?>">
                  
                  <td class="text-center fw-bold text-muted" style="font-size: 12px;"><?= $sno ?></td>
                  
                  <td>
                    <div class="d-flex align-items-center gap-3">
                      <div class="nl-avatar" style="background: <?= $avatar['bg'] ?>; color: <?= $avatar['color'] ?>;">
                        <?= $avatar['text'] ?>
                      </div>
                      <div>
                        <div class="fw-bold text-dark mb-0"><?= esc($enquiry->full_name ?? 'Unknown') ?></div>
                        <div class="d-flex align-items-center text-muted small mt-0.5">
                          <i class="bi bi-telephone me-1" style="font-size: 11px;"></i>
                          <span><?= esc($enquiry->phone ?? 'N/A') ?></span>
                        </div>
                        <?php if (!empty($enquiry->remarks)): ?>
                          <div class="text-muted small mt-1" style="font-size: 11px; color: #6366f1 !important;">
                            <i class="bi bi-chat-left-text me-1"></i>
                            <?= htmlspecialchars(mb_substr($enquiry->remarks, 0, 45)) . (mb_strlen($enquiry->remarks) > 45 ? '...' : '') ?>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </td>

                  <td>
                    <div>
                      <div class="fw-semibold text-dark"><?= esc($enquiry->name ?? ($enquiry->breed ?? 'Pet')) ?></div>
                      <div class="text-muted small">
                        <span><?= esc($enquiry->breed ?? 'Breed N/A') ?></span>
                        <?php if (!empty($enquiry->gender)): ?>
                          <span class="mx-1">•</span><span><?= esc(ucfirst($enquiry->gender)) ?></span>
                        <?php endif; ?>
                      </div>
                    </div>
                  </td>

                  <td>
                    <div class="badge-service">
                      <i class="bi bi-shield-check"></i>
                      <span><?= esc($enquiry->coverage_type ?? 'Dog Insurance') ?></span>
                    </div>
                  </td>

                  <td class="text-center">
                    <div class="d-inline-flex align-items-center gap-1.5 text-dark fw-medium" style="font-size: 12.5px;">
                      <i class="bi bi-calendar3 text-muted"></i>
                      <span><?= $formattedDate ?></span>
                    </div>
                  </td>

                  <td class="text-center">
                    <span class="status-pill <?= $statusPillClass ?>">
                      <span class="status-dot"></span>
                      <span><?= ucfirst($statusLower) ?></span>
                    </span>
                  </td>

                  <td class="text-center">
                    <div class="d-flex align-items-center justify-content-center gap-1.5">
                      <button type="button" class="nl-action-btn view" title="View Enquiry Details"
                        onclick="showAllDetails(<?= htmlspecialchars($petJson, ENT_QUOTES, 'UTF-8') ?>)">
                        <i class="bi bi-eye"></i>
                      </button>
                      <button type="button" class="nl-action-btn quote" title="Send Quotation"
                        onclick="openSendQuoteFromRow('<?= esc($enquiry->policy_number ?? $enquiry->id) ?>', '<?= esc($enquiry->email ?? '') ?>', '<?= esc($enquiry->full_name ?? '') ?>')">
                        <i class="bi bi-send"></i>
                      </button>
                      <button type="button" class="nl-action-btn edit" title="Update Status"
                        onclick="openUpdateStatusModal(<?= (int)$enquiry->id ?>, '<?= $statusLower ?>', '<?= addslashes($enquiry->remarks ?? '') ?>')">
                        <i class="bi bi-pencil-square"></i>
                      </button>
                      <button type="button" class="nl-action-btn delete" title="Delete Enquiry"
                        onclick="openDeleteEnquiryModal(<?= (int)$enquiry->id ?>, '<?= esc($enquiry->policy_number ?? ('PF' . $enquiry->id)) ?>', '<?= addslashes(esc($enquiry->full_name ?? 'Client')) ?>', '<?= addslashes(esc($enquiry->name ?? ($enquiry->breed ?? 'Pet'))) ?>')">
                        <i class="bi bi-trash"></i>
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
                <td colspan="7" class="text-center py-5 text-muted">
                  <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                  No insurance enquiries found
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Modern Table Footer & Pagination -->
      <div class="d-flex flex-wrap justify-content-between align-items-center px-4 py-3 border-top" style="background: #fafafa;">
        <div class="text-muted small fw-medium" id="entriesInfo">
          Showing 1 to <?= min(10, $totalEnquiries) ?> of <?= $totalEnquiries ?> entries
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

<!-- 1. Enquiry Details Modal -->
<div id="petDetailsModal" class="modal fade" tabindex="-1" aria-labelledby="petDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden" style="max-height: 85vh;">
      <div class="modal-header modal-header-gradient">
        <div class="d-flex align-items-center gap-2.5">
          <div class="modal-icon-badge">
            <i class="bi bi-shield-check text-warning"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold text-white mb-0" id="petDetailsModalLabel">Insurance Enquiry Details</h5>
            <span class="text-white-50 small">Comprehensive policy request summary</span>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white opacity-75 hover-opacity-100" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-0" style="overflow-y: auto; flex: 1; scrollbar-width: thin;">
        <div class="p-4" id="petDetailsContent">
          <!-- Dynamic Content Loaded by JS -->
        </div>
      </div>

      <div class="modal-footer border-top bg-light px-4 py-2.5 d-flex justify-content-between">
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
          Close
        </button>
        <div class="d-flex gap-2" id="enquiryModalActions">
          <!-- Action buttons injected dynamically -->
        </div>
      </div>
    </div>
  </div>
</div>

<!-- 2. Add Enquiry Modal -->
<div id="addEnquiryModal" class="modal fade" tabindex="-1" aria-labelledby="addEnquiryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden" style="max-height: 88vh;">
      <div class="modal-header modal-header-gradient">
        <div class="d-flex align-items-center gap-2.5">
          <div class="modal-icon-badge">
            <i class="bi bi-plus-circle text-warning"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold text-white mb-0" id="addEnquiryModalLabel">Add New Insurance Enquiry</h5>
            <span class="text-white-50 small">Register client and pet insurance interest</span>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white opacity-75 hover-opacity-100" data-bs-dismiss="modal" aria-label="Close" onclick="closeAddEnquiryModal()"></button>
      </div>

      <div class="modal-body p-4" style="overflow-y: auto; flex: 1;">
        <!-- Step 1: Search Client -->
        <div id="searchClientStep">
          <div class="modal-info-card mb-4">
            <label class="form-label fw-bold text-dark small text-uppercase mb-2">Search Registered Client by Phone</label>
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0 text-muted">
                <i class="bi bi-telephone"></i>
              </span>
              <input type="text" class="form-control border-start-0" id="searchPhone" placeholder="Enter 10-digit mobile number">
              <button class="btn btn-primary fw-semibold px-4" type="button" onclick="searchClient()">
                <i class="bi bi-search me-1"></i> Search
              </button>
            </div>
            <div id="searchFeedback" class="mt-2 small"></div>
          </div>
        </div>

        <!-- Step 1b: New Client Section (Hidden initially) -->
        <div id="newClientSection" style="display: none;">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0 text-indigo"><i class="bi bi-person-plus me-1.5"></i>New Client Profile</h6>
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" onclick="backToSearch()">
              <i class="bi bi-arrow-left me-1"></i>Back to Search
            </button>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">FULL NAME</label>
              <input type="text" class="form-control rounded-3" id="new_client_name" placeholder="Enter client full name">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">EMAIL ADDRESS</label>
              <input type="email" class="form-control rounded-3" id="new_client_email" placeholder="client@example.com">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">PHONE NUMBER</label>
              <input type="text" class="form-control rounded-3" id="new_client_phone" placeholder="10-digit phone">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">ADDRESS</label>
              <textarea class="form-control rounded-3" id="new_client_address" rows="1" placeholder="Address & City"></textarea>
            </div>
          </div>

          <h6 class="fw-bold mb-3 text-indigo mt-4"><i class="bi bi-heart-pulse me-1.5"></i>Pet Profile Details</h6>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">PET NAME</label>
              <input type="text" class="form-control rounded-3" id="new_pet_name" placeholder="e.g. Bruno">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">BREED</label>
              <select class="form-select rounded-3" id="new_pet_breed">
                <option value="">Select Breed</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">AGGRESSIVENESS</label>
              <select class="form-select rounded-3" id="new_pet_aggressive">
                <option value="No">No (Friendly)</option>
                <option value="Yes">Yes (Aggressive)</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">VACCINATED</label>
              <select class="form-select rounded-3" id="new_pet_vaccinated">
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold small text-muted">DATE OF BIRTH</label>
              <input type="date" class="form-control rounded-3" id="new_pet_dob">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold small text-muted">GENDER</label>
              <select class="form-select rounded-3" id="new_pet_gender">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold small text-muted">LAST VACCINATION</label>
              <input type="date" class="form-control rounded-3" id="new_pet_vaccination">
            </div>
          </div>
          <hr class="my-4">
        </div>

        <!-- Step 2: Enquiry Form (Hidden initially) -->
        <form id="enquiryForm" style="display: none;">
          <input type="hidden" id="foundClientId" name="user_id">
          <input type="hidden" id="is_new_client" name="is_new_client" value="0">

          <div id="existingClientDisplay">
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label fw-bold small text-muted">CLIENT NAME</label>
                <input type="text" class="form-control bg-light rounded-3" id="displayClientName" readonly name="full_name">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-bold small text-muted">CLIENT EMAIL</label>
                <input type="email" class="form-control bg-light rounded-3" id="displayClientEmail" readonly name="email">
                <input type="hidden" id="displayClientPhone" name="phone">
              </div>
            </div>

            <div class="row g-3 mb-3" id="petSelectRow">
              <div class="col-md-12">
                <label class="form-label fw-bold small text-muted" id="petSelectLabel">SELECT PET</label>
                <select class="form-select rounded-3" id="petSelect" name="pet_id" required>
                  <option value="">Select a pet</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">COVERAGE TYPE</label>
              <select class="form-select rounded-3" name="coverage_type" required>
                <option value="Standard">Standard</option>
                <option value="Premium">Premium</option>
                <option value="Basic">Basic</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">DURATION (YEARS)</label>
              <select class="form-select rounded-3" name="duration_years" required onchange="updateEndDate(this)">
                <option value="1">1 Year</option>
                <option value="2">2 Years</option>
                <option value="3">3 Years</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">START DATE</label>
              <input type="date" class="form-control rounded-3" name="start_date" id="startDate" required onchange="calculateEndDate()">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">END DATE</label>
              <input type="date" class="form-control bg-light rounded-3" name="end_date" id="endDate" readonly required>
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer border-top bg-light px-4 py-2.5 d-flex justify-content-between">
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal" onclick="closeAddEnquiryModal()">
          Cancel
        </button>
        <button type="button" class="btn btn-sm btn-primary rounded-pill px-4 fw-semibold" id="submitEnquiryBtn" style="display: none;" onclick="submitEnquiry()">
          <i class="bi bi-check2-circle me-1"></i>Save Enquiry
        </button>
      </div>
    </div>
  </div>
</div>

<!-- 3. Send Quotation Modal -->
<div id="emailModal" class="modal fade" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden" style="max-height: 85vh;">
      <div class="modal-header modal-header-gradient">
        <div class="d-flex align-items-center gap-2.5">
          <div class="modal-icon-badge">
            <i class="bi bi-send text-warning"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold text-white mb-0" id="emailModalLabel">Send Insurance Quotation</h5>
            <span class="text-white-50 small">Dispatch custom quotation details directly to the pet owner</span>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white opacity-75 hover-opacity-100" data-bs-dismiss="modal" aria-label="Close" onclick="closeEmailModal()"></button>
      </div>

      <div class="modal-body p-4" style="overflow-y: auto; flex: 1;">
        <div class="row g-3">
          <div class="col-md-5 border-end pe-md-4">
            <div class="mb-3">
              <label class="form-label fw-bold small text-muted">ENQUIRY / POLICY ID</label>
              <input type="text" class="form-control bg-light rounded-3" id="enquiryId" readonly name="policy_id">
            </div>
            <div class="mb-3">
              <label class="form-label fw-bold small text-muted">QUOTE AMOUNT (₹)</label>
              <div class="input-group">
                <span class="input-group-text bg-white">₹</span>
                <input type="number" class="form-control rounded-end-3" id="price" name="amount" placeholder="e.g. 4999">
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-bold small text-muted">CLIENT EMAIL</label>
              <input type="email" class="form-control bg-light rounded-3" id="toEmail" readonly name="email">
            </div>
            <div class="mb-3">
              <label class="form-label fw-bold small text-muted">EMAIL SUBJECT</label>
              <input type="text" class="form-control rounded-3" id="emailSubject" value="Petsfolio Dog Insurance Quotation" name="subject">
            </div>
          </div>

          <div class="col-md-7 ps-md-4">
            <label class="form-label fw-bold small text-muted">CUSTOM MESSAGE</label>
            <textarea class="form-control rounded-3" id="emailBody" rows="12" style="font-size: 13px; line-height: 1.6;">Dear Pet Parent,

Thank you for your interest in Petsfolio Pet Insurance. We are delighted to share the customized quotation for your furry family member:

• Quoted Premium: ₹[AMOUNT]
• Coverage Term: 1 Year
• Benefits: Comprehensive Vet Consultations, Emergency Care & Accidental Cover

To confirm this policy or speak with our insurance advisor, simply reply to this email or reach us on 040-XXXXXXX.

Warm regards,
Petsfolio Insurance Team</textarea>
          </div>
        </div>
      </div>

      <div class="modal-footer border-top bg-light px-4 py-2.5 d-flex justify-content-between">
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal" onclick="closeEmailModal()">
          Cancel
        </button>
        <button type="button" class="btn btn-sm btn-primary rounded-pill px-4 fw-semibold" onclick="sendQuotation(event)">
          <i class="bi bi-send me-1.5"></i>Send Quotation
        </button>
      </div>
    </div>
  </div>
</div>

<!-- 4. Update Status Modal -->
<div id="updateStatusModal" class="modal fade" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
    <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
      <div class="modal-header modal-header-gradient">
        <div class="d-flex align-items-center gap-2.5">
          <div class="modal-icon-badge">
            <i class="bi bi-pencil-square text-warning"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold text-white mb-0" id="updateStatusModalLabel">Update Enquiry Status</h5>
            <span class="text-white-50 small">Modify review and approval workflow</span>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white opacity-75 hover-opacity-100" data-bs-dismiss="modal" aria-label="Close" onclick="closeUpdateStatusModal()"></button>
      </div>

      <div class="modal-body p-4">
        <input type="hidden" id="updateEnquiryId">
        
        <div class="mb-3">
          <label class="form-label fw-bold small text-muted text-uppercase">Application Status</label>
          <select class="form-select rounded-3" id="updateEnquiryStatus">
            <option value="pending">🕒 Pending Review</option>
            <option value="approved">✅ Approved</option>
            <option value="rejected">❌ Rejected</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold small text-muted text-uppercase">Internal Remarks / Notes</label>
          <textarea class="form-control rounded-3" id="updateEnquiryRemarks" rows="3" placeholder="Add optional review comments..."></textarea>
        </div>
      </div>

      <div class="modal-footer border-top bg-light px-4 py-2.5 d-flex justify-content-between">
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal" onclick="closeUpdateStatusModal()">
          Cancel
        </button>
        <button type="button" class="btn btn-sm btn-dark rounded-pill px-4 fw-semibold" onclick="submitStatusUpdate()">
          Save Changes
        </button>
      </div>
    </div>
  </div>
</div>

<!-- 5. Delete Enquiry Modal -->
<div id="deleteEnquiryModal" class="modal fade" tabindex="-1" aria-labelledby="deleteEnquiryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
    <div class="modal-content shadow-lg border-0 rounded-4 overflow-hidden">
      <div class="modal-header d-flex align-items-center justify-content-between text-white" style="background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 35%, #b91c1c 70%, #dc2626 100%) !important; padding: 18px 24px; border: 0;">
        <div class="d-flex align-items-center gap-2.5">
          <div class="modal-icon-badge" style="background: rgba(255, 255, 255, 0.2);">
            <i class="bi bi-trash3-fill"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold text-white mb-0" id="deleteEnquiryModalLabel">Delete Insurance Enquiry</h5>
            <small class="text-white text-opacity-75" style="font-size: 11.5px;">Permanent deletion with audit trail logging</small>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white opacity-75 hover-opacity-100" data-bs-dismiss="modal" aria-label="Close" onclick="closeDeleteEnquiryModal()"></button>
      </div>

      <div class="modal-body p-4">
        <input type="hidden" id="deleteEnquiryId">
        
        <div class="alert alert-danger bg-danger bg-opacity-10 border-danger border-opacity-25 d-flex gap-3 align-items-start rounded-3 p-3 mb-3">
          <i class="bi bi-exclamation-triangle-fill text-danger fs-5 mt-0.5"></i>
          <div class="small text-danger">
            <strong>Warning:</strong> You are about to permanently delete enquiry <strong id="deletePolicyNumber"></strong>. This action will be recorded in the system audit logs.
          </div>
        </div>

        <div class="modal-info-card mb-3">
          <div class="row g-2 small">
            <div class="col-6">
              <span class="text-muted d-block">Client Name:</span>
              <strong class="text-dark" id="deleteClientName">-</strong>
            </div>
            <div class="col-6">
              <span class="text-muted d-block">Pet:</span>
              <strong class="text-dark" id="deletePetName">-</strong>
            </div>
          </div>
        </div>

        <div class="mb-2">
          <label class="form-label fw-semibold text-dark small mb-1">Reason for Deletion <span class="text-muted fw-normal">(Optional)</span></label>
          <textarea class="form-control form-control-sm" id="deleteReason" rows="2" placeholder="e.g. Duplicate enquiry, client cancelled request, spam..."></textarea>
          <small class="text-muted" style="font-size: 11px;">This note will be saved in the system activity audit log.</small>
        </div>
      </div>

      <div class="modal-footer bg-light px-4 py-3 border-top d-flex justify-content-between">
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal" onclick="closeDeleteEnquiryModal()">
          Cancel
        </button>
        <button type="button" class="btn btn-sm btn-danger rounded-pill px-4 fw-semibold" id="confirmDeleteBtn" onclick="executeDeleteEnquiry()">
          <i class="bi bi-trash3 me-1"></i> Confirm Delete
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ==========================================
     JAVASCRIPT: Pagination & Actions
========================================== -->
<script>
class InsurancePaginationManager {
    constructor() {
        this.currentPage = 1;
        this.entriesPerPage = 10;
        this.allRows = Array.from(document.querySelectorAll('.enquiry-row'));
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
        const searchInput = document.getElementById('searchEnquiries');
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
        const query = (document.getElementById('searchEnquiries')?.value || '').toLowerCase().trim();
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
    window.insurancePagination = new InsurancePaginationManager();
});

// View Details Modal
function showAllDetails(enquiry) {
    if (typeof enquiry === 'string') {
        try { enquiry = JSON.parse(enquiry); } catch(e){}
    }

    const html = `
      <div class="row g-3">
        <div class="col-md-6">
          <div class="modal-info-card h-100">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-person me-1.5 text-primary"></i>Client Information</h6>
            <div class="d-flex flex-column gap-2 small">
              <div class="d-flex justify-content-between"><span class="text-muted">Full Name:</span><span class="fw-semibold text-dark">${enquiry.full_name || 'N/A'}</span></div>
              <div class="d-flex justify-content-between"><span class="text-muted">Phone:</span><span class="fw-semibold text-dark">${enquiry.phone || 'N/A'}</span></div>
              <div class="d-flex justify-content-between"><span class="text-muted">Email:</span><span class="fw-semibold text-dark">${enquiry.email || 'N/A'}</span></div>
              <div class="d-flex justify-content-between"><span class="text-muted">Policy / Ref ID:</span><span class="fw-semibold text-dark font-monospace">#${enquiry.policy_number || enquiry.id || 'N/A'}</span></div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="modal-info-card h-100">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-heart-pulse me-1.5 text-danger"></i>Pet Details</h6>
            <div class="d-flex flex-column gap-2 small">
              <div class="d-flex justify-content-between"><span class="text-muted">Pet Name:</span><span class="fw-semibold text-dark">${enquiry.pet_name || 'N/A'}</span></div>
              <div class="d-flex justify-content-between"><span class="text-muted">Breed:</span><span class="fw-semibold text-dark">${enquiry.breed || 'N/A'}</span></div>
              <div class="d-flex justify-content-between"><span class="text-muted">DOB / Age:</span><span class="fw-semibold text-dark">${enquiry.dob || 'N/A'}</span></div>
              <div class="d-flex justify-content-between"><span class="text-muted">Gender:</span><span class="fw-semibold text-dark">${enquiry.gender || 'N/A'}</span></div>
              <div class="d-flex justify-content-between"><span class="text-muted">Vaccination:</span><span class="fw-semibold text-dark">${enquiry.last_vaccination || 'N/A'}</span></div>
            </div>
          </div>
        </div>

        <div class="col-12">
          <div class="modal-info-card">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-shield-check me-1.5 text-success"></i>Policy & Coverage Scope</h6>
            <div class="row g-2 small">
              <div class="col-sm-4"><span class="text-muted d-block">Coverage Plan:</span><span class="fw-semibold text-dark">${enquiry.coverage_type || 'Standard'}</span></div>
              <div class="col-sm-4"><span class="text-muted d-block">Duration:</span><span class="fw-semibold text-dark">${enquiry.duration_years || 1} Year(s)</span></div>
              <div class="col-sm-4"><span class="text-muted d-block">Status:</span><span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill">${enquiry.status || 'Pending'}</span></div>
              ${enquiry.remarks ? `<div class="col-12 mt-2 pt-2 border-top"><span class="text-muted d-block">Internal Remarks:</span><span class="text-dark">${enquiry.remarks}</span></div>` : ''}
            </div>
          </div>
        </div>
      </div>
    `;

    document.getElementById('petDetailsContent').innerHTML = html;

    const actionContainer = document.getElementById('enquiryModalActions');
    if (actionContainer) {
        actionContainer.innerHTML = `
            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-medium" onclick="closePetDetailsModal(); openDeleteEnquiryModal(${enquiry.id}, '${(enquiry.policy_number || ('PF' + enquiry.id)).replace(/'/g, "\\'")}', '${(enquiry.full_name || 'Client').replace(/'/g, "\\'")}', '${(enquiry.pet_name || enquiry.breed || 'Pet').replace(/'/g, "\\'")}')">
                <i class="bi bi-trash3 me-1"></i> Delete
            </button>
            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-medium" onclick="closePetDetailsModal(); openSendQuoteFromRow('${(enquiry.policy_number || enquiry.id).replace(/'/g, "\\'")}', '${(enquiry.email || '').replace(/'/g, "\\'")}', '${(enquiry.full_name || '').replace(/'/g, "\\'")}')">
                <i class="bi bi-send me-1"></i> Send Quote
            </button>
            <button type="button" class="btn btn-sm btn-dark rounded-pill px-3 fw-medium" onclick="closePetDetailsModal(); openUpdateStatusModal(${enquiry.id}, '${(enquiry.status || 'pending').toLowerCase()}', '${(enquiry.remarks || '').replace(/'/g, "\\'")}')">
                <i class="bi bi-pencil-square me-1"></i> Update Status
            </button>
        `;
    }

    const modalEl = document.getElementById('petDetailsModal');
    let bsModal = bootstrap.Modal.getInstance(modalEl);
    if (!bsModal) bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
}

function closePetDetailsModal() {
    const modalEl = document.getElementById('petDetailsModal');
    const bsModal = bootstrap.Modal.getInstance(modalEl);
    if (bsModal) bsModal.hide();
}

// Add Enquiry Modal Logic
function showAddEnquiryModal() {
    const modalEl = document.getElementById('addEnquiryModal');
    let bsModal = bootstrap.Modal.getInstance(modalEl);
    if (!bsModal) bsModal = new bootstrap.Modal(modalEl);
    
    // Set default start date to today
    const today = new Date().toISOString().split('T')[0];
    const startDateInput = document.getElementById('startDate');
    if (startDateInput) {
        startDateInput.value = today;
        calculateEndDate();
    }
    
    bsModal.show();
}

function closeAddEnquiryModal() {
    const modalEl = document.getElementById('addEnquiryModal');
    const bsModal = bootstrap.Modal.getInstance(modalEl);
    if (bsModal) bsModal.hide();
}

function searchClient() {
    const phone = document.getElementById('searchPhone').value.trim();
    const feedback = document.getElementById('searchFeedback');

    if (!phone || phone.length < 10) {
        feedback.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-circle me-1"></i>Please enter a valid 10-digit phone number.</span>';
        return;
    }

    feedback.innerHTML = '<span class="text-muted"><i class="bi bi-arrow-repeat spin me-1"></i>Searching client database...</span>';

    fetch('<?= base_url('insurance/searchClient') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ phone: phone })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && data.client) {
            feedback.innerHTML = `<span class="text-success"><i class="bi bi-check-circle me-1"></i>Client found: <strong>${data.client.full_name}</strong></span>`;
            document.getElementById('foundClientId').value = data.client.id;
            document.getElementById('displayClientName').value = data.client.full_name;
            document.getElementById('displayClientEmail').value = data.client.email || '';
            document.getElementById('displayClientPhone').value = data.client.phone || '';
            document.getElementById('is_new_client').value = '0';

            loadClientPets(data.client.id);
            document.getElementById('enquiryForm').style.display = 'block';
            document.getElementById('newClientSection').style.display = 'none';
            document.getElementById('submitEnquiryBtn').style.display = 'inline-flex';
        } else {
            feedback.innerHTML = `
                <div class="alert alert-warning py-2 px-3 small mt-2 mb-0 d-flex justify-content-between align-items-center">
                  <span>Client not registered with this phone.</span>
                  <button type="button" class="btn btn-sm btn-dark rounded-pill" onclick="showNewClientForm('${phone}')">
                    <i class="bi bi-plus me-1"></i>Create New Client
                  </button>
                </div>`;
        }
    })
    .catch(err => {
        feedback.innerHTML = '<span class="text-danger">Error connecting to server.</span>';
    });
}

function showNewClientForm(phone = '') {
    document.getElementById('newClientSection').style.display = 'block';
    document.getElementById('searchClientStep').style.display = 'none';
    document.getElementById('enquiryForm').style.display = 'block';
    document.getElementById('existingClientDisplay').style.display = 'none';
    document.getElementById('is_new_client').value = '1';
    document.getElementById('new_client_phone').value = phone;
    document.getElementById('submitEnquiryBtn').style.display = 'inline-flex';
    loadDogBreeds();
}

function backToSearch() {
    document.getElementById('newClientSection').style.display = 'none';
    document.getElementById('searchClientStep').style.display = 'block';
    document.getElementById('enquiryForm').style.display = 'none';
    document.getElementById('existingClientDisplay').style.display = 'block';
    document.getElementById('is_new_client').value = '0';
    document.getElementById('submitEnquiryBtn').style.display = 'none';
}

function loadDogBreeds() {
    const breedSelect = document.getElementById('new_pet_breed');
    if (breedSelect.options.length > 1) return;

    fetch('<?= base_url('insurance/getDogBreeds') ?>')
    .then(r => r.json())
    .then(data => {
        if (data.success && data.breeds) {
            data.breeds.forEach(b => {
                const opt = document.createElement('option');
                opt.value = b.value;
                opt.textContent = b.value;
                breedSelect.appendChild(opt);
            });
        }
    });
}

function loadClientPets(clientId) {
    const petSelect = document.getElementById('petSelect');
    petSelect.innerHTML = '<option value="">Loading pets...</option>';

    fetch('<?= base_url('insurance/getClientPets') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ client_id: clientId })
    })
    .then(r => r.json())
    .then(data => {
        petSelect.innerHTML = '<option value="">Select a pet</option>';
        if (data.success && data.pets && data.pets.length > 0) {
            data.pets.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = `${p.name} (${p.breed || 'Dog'})`;
                petSelect.appendChild(opt);
            });
        } else {
            petSelect.innerHTML = '<option value="">No registered pets found</option>';
        }
    });
}

function updateEndDate(durationSelect) {
    calculateEndDate();
}

function calculateEndDate() {
    const startDate = document.getElementById('startDate').value;
    const duration = parseInt(document.querySelector('select[name="duration_years"]').value) || 1;
    if (startDate) {
        const start = new Date(startDate);
        const end = new Date(start.setFullYear(start.getFullYear() + duration));
        document.getElementById('endDate').value = end.toISOString().split('T')[0];
    }
}

function submitEnquiry() {
    const isNew = document.getElementById('is_new_client').value === '1';
    const payload = {
        is_new_client: isNew ? 1 : 0,
        coverage_type: document.querySelector('select[name="coverage_type"]').value,
        duration_years: document.querySelector('select[name="duration_years"]').value,
        start_date: document.getElementById('startDate').value,
        end_date: document.getElementById('endDate').value
    };

    if (isNew) {
        payload.new_client_name = document.getElementById('new_client_name').value.trim();
        payload.new_client_email = document.getElementById('new_client_email').value.trim();
        payload.new_client_phone = document.getElementById('new_client_phone').value.trim();
        payload.new_client_address = document.getElementById('new_client_address').value.trim();
        payload.new_pet_name = document.getElementById('new_pet_name').value.trim();
        payload.new_pet_breed = document.getElementById('new_pet_breed').value;
        payload.new_pet_aggressive = document.getElementById('new_pet_aggressive').value;
        payload.new_pet_vaccinated = document.getElementById('new_pet_vaccinated').value;
        payload.new_pet_dob = document.getElementById('new_pet_dob').value;
        payload.new_pet_gender = document.getElementById('new_pet_gender').value;
        payload.new_pet_vaccination = document.getElementById('new_pet_vaccination').value;

        if (!payload.new_client_name || !payload.new_client_phone || !payload.new_pet_name) {
            alert('Please fill all required client and pet details.');
            return;
        }
    } else {
        payload.user_id = document.getElementById('foundClientId').value;
        payload.full_name = document.getElementById('displayClientName').value;
        payload.email = document.getElementById('displayClientEmail').value;
        payload.phone = document.getElementById('displayClientPhone').value;
        payload.pet_id = document.getElementById('petSelect').value;

        if (!payload.pet_id) {
            alert('Please select a pet.');
            return;
        }
    }

    fetch('<?= base_url('insurance/addEnquiry') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {
        if (data.status) {
            alert('Insurance enquiry added successfully!');
            location.reload();
        } else {
            alert(data.message || 'Error saving enquiry.');
        }
    });
}

// Send Quote Modal Logic
function openSendQuoteFromRow(policyId, email, clientName) {
    document.getElementById('enquiryId').value = policyId;
    document.getElementById('toEmail').value = email || '';
    
    const modalEl = document.getElementById('emailModal');
    let bsModal = bootstrap.Modal.getInstance(modalEl);
    if (!bsModal) bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
}

function closeEmailModal() {
    const modalEl = document.getElementById('emailModal');
    const bsModal = bootstrap.Modal.getInstance(modalEl);
    if (bsModal) bsModal.hide();
}

function sendQuotation(e) {
    e.preventDefault();
    const policyId = document.getElementById('enquiryId').value;
    const amount = document.getElementById('price').value;
    const email = document.getElementById('toEmail').value;
    const subject = document.getElementById('emailSubject').value;
    const message = document.getElementById('emailBody').value;

    if (!amount || parseFloat(amount) <= 0) {
        alert('Please enter a valid quote amount.');
        return;
    }

    const formData = new FormData();
    formData.append('policy_id', policyId);
    formData.append('amount', amount);
    formData.append('email', email);
    formData.append('subject', subject);
    formData.append('message', message);

    fetch('<?= base_url('insurance/sendQuotation') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.status) {
            alert('Quotation sent successfully!');
            location.reload();
        } else {
            alert(data.message || 'Failed to send quotation.');
        }
    });
}

// Update Status Modal Logic
function openUpdateStatusModal(id, currentStatus, currentRemarks) {
    document.getElementById('updateEnquiryId').value = id;
    document.getElementById('updateEnquiryStatus').value = currentStatus || 'pending';
    document.getElementById('updateEnquiryRemarks').value = currentRemarks || '';

    const modalEl = document.getElementById('updateStatusModal');
    let bsModal = bootstrap.Modal.getInstance(modalEl);
    if (!bsModal) bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
}

function closeUpdateStatusModal() {
    const modalEl = document.getElementById('updateStatusModal');
    const bsModal = bootstrap.Modal.getInstance(modalEl);
    if (bsModal) bsModal.hide();
}

function submitStatusUpdate() {
    const id = document.getElementById('updateEnquiryId').value;
    const status = document.getElementById('updateEnquiryStatus').value;
    const remarks = document.getElementById('updateEnquiryRemarks').value;

    fetch('<?= base_url('insurance/updateEnquiryStatus') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ id: id, status: status, remarks: remarks })
    })
    .then(r => r.json())
    .then(data => {
        if (data.status) {
            alert('Status updated successfully!');
            location.reload();
        } else {
            alert(data.message || 'Failed to update status.');
        }
    });
}

// Delete Enquiry Logic
function openDeleteEnquiryModal(id, policyNumber, clientName, petName) {
    document.getElementById('deleteEnquiryId').value = id;
    document.getElementById('deletePolicyNumber').textContent = policyNumber || ('#' + id);
    document.getElementById('deleteClientName').textContent = clientName || 'N/A';
    document.getElementById('deletePetName').textContent = petName || 'N/A';
    document.getElementById('deleteReason').value = '';

    const modalEl = document.getElementById('deleteEnquiryModal');
    let bsModal = bootstrap.Modal.getInstance(modalEl);
    if (!bsModal) bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
}

function closeDeleteEnquiryModal() {
    const modalEl = document.getElementById('deleteEnquiryModal');
    const bsModal = bootstrap.Modal.getInstance(modalEl);
    if (bsModal) bsModal.hide();
}

function executeDeleteEnquiry() {
    const id = document.getElementById('deleteEnquiryId').value;
    const reason = document.getElementById('deleteReason').value.trim();
    const btn = document.getElementById('confirmDeleteBtn');

    if (!id) return;

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Deleting...';

    fetch('<?= base_url("insurance/deleteEnquiry") ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ id: id, reason: reason })
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-trash3 me-1"></i> Confirm Delete';
        if (data.status) {
            closeDeleteEnquiryModal();
            closePetDetailsModal();
            alert(data.message || 'Enquiry deleted successfully');
            location.reload();
        } else {
            alert(data.message || 'Failed to delete enquiry');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-trash3 me-1"></i> Confirm Delete';
        console.error(err);
        alert('An error occurred while deleting the enquiry');
    });
}

// Export CSV
function exportEnquiriesToCSV() {
    const rows = Array.from(document.querySelectorAll('#enquiriesTable tbody tr.enquiry-row'));
    if (!rows.length) {
        alert('No enquiries to export.');
        return;
    }

    let csv = 'S.No,Client Name,Phone,Pet Name,Breed,Coverage,Enquiry Date,Status\n';
    rows.forEach(r => {
        const d = JSON.parse(r.dataset.petDetails || '{}');
        csv += `"${d.id || ''}","${d.full_name || ''}","${d.phone || ''}","${d.pet_name || ''}","${d.breed || ''}","${d.coverage_type || ''}","${d.created_at || ''}","${d.status || ''}"\n`;
    });

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `insurance_enquiries_${new Date().toISOString().split('T')[0]}.csv`;
    link.click();
}
</script>

<?php echo $this->endSection(); ?>