<?php echo $this->extend('admin/includes/header'); ?>
<!--start content-->
<<<<<<< HEAD
<?php echo $this->section('content'); ?>
<main class="main-content">
  <div class="position-relative iq-banner ">
    <!--Nav Start-->
    <?php echo $this->include('admin/nav_bar') ?>
  </div>

  <div class="container-fluid content-inner pb-0 px-4" id="page_layout">
    <!-- Modern Header Section -->
    <div class="container-fluid p-0 bg-gradient-primary rounded-3 mb-4">
      <div class=" text-white p-5  shadow position-relative overflow-hidden">
        <div class="position-relative z-1 d-flex justify-content-between align-items-center">
          <div>
            <h1 class="h2 fw-bold mb-2 text-white">New Enquiries</h1>
            <p class="text-white-75 mb-0">Manage and process new insurance enquiries</p>
          </div>

        </div>
        <i class="bi bi-shield-check position-absolute end-0 bottom-0 opacity-10"
          style="font-size: 14rem; transform: rotate(12deg); margin-right: -3rem; margin-bottom: -3rem;"></i>
      </div>
    </div>

    <!-- Search Bar -->

    <div class="card border-0 shadow-sm overflow-hidden mb-4">
      <div class="card-body p-3">
        <div class="d-flex justify-content-between align-items-center gap-3">
          <div class="input-group w-50 w-md-auto">
            <span class="input-group-text bg-black border-end-0">
              <i class="bi bi-search text-white"></i>
            </span>
            <input type="text" class="form-control border-start-0 ps-0" placeholder="Search..." id="searchInput">
          </div>
          <button class="btn btn-black bg-black text-white fw-bold px-4 py-2 shadow-sm d-flex align-items-center gap-2"
            onclick="showAddEnquiryModal()">
            <i class="bi bi-plus-circle"></i>Add Enquiry
=======

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
>>>>>>> origin/main
          </button>
        </div>
      </div>
    </div>
<<<<<<< HEAD

    <!-- Modern Table -->
    <div class="card border-0 shadow-sm overflow-hidden">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="" style="background-color: #c6d2ff!important;">
            <tr>
              <th class=" py-3 text-black fs-6 fw-semibold border-bottom " style="background: #c6d2ff!important;">Client
                Details</th>
              <th class="py-3 text-black fs-6 fw-semibold border-bottom" style="background: #c6d2ff!important;">
                Insurance Type</th>
              <th class="py-3 text-black fs-6 fw-semibold border-bottom" style="background: #c6d2ff!important;">Enquiry
                Date</th>
              <th class="py-3 text-black fs-6 fw-semibold border-bottom text-center"
                style="background: #c6d2ff!important;">Status</th>
              <th class=" py-3 text-black fs-6 fw-semibold border-bottom text-end"
                style="background: #c6d2ff!important;">Actions</th>
            </tr>
          </thead>
          <tbody class="border-top-0">
            <?php
            $i = 1;
            foreach ($enquiries as $enquiry) {
              $initial = strtoupper(substr($enquiry->full_name, 0, 1));
              $formattedDate = date('d-m-Y', strtotime($enquiry->created_at));
            ?>
              <tr data-pet-details="<?= htmlspecialchars(json_encode([
                                      "breed" => $enquiry->breed,
                                      "age" => $enquiry->dob,
                                      "gender" => $enquiry->gender,
                                      "vaccination" => $enquiry->last_vaccination
                                    ]), ENT_QUOTES, 'UTF-8') ?>">
                <td class="ps-4 py-2">
                  <div class="d-flex align-items-center gap-3">
                    <div
                      class="rounded-circle bg-primary bg-opacity-10 text-white d-flex align-items-center justify-content-center fw-bold"
                      style="width: 44px; height: 44px; border: 2px solid #e5dbff; font-size: 1.2rem;">
                      <?= $initial ?>
                    </div>
                    <div>
                      <div class="fw-bold text-dark "><?= $enquiry->full_name ?></div>
                      <div class="d-flex align-items-center text-muted small mt-1" style="font-size: 12px;">
                        <i class="bi bi-telephone me-1"></i>
                        <?= $enquiry->phone ?>
                      </div>
                      <!-- <div class="text-muted small font-monospace mt-1">ID: #<?= $enquiry->policy_number ?></div> -->
                      <?php if (!empty($enquiry->remarks)): ?>
                        <div class="text-muted small mt-1 italic" style="font-size: 11px; color: #6366f1 !important;">
                          <i class="bi bi-chat-left-text me-1"></i>
                          <?= htmlspecialchars(substr($enquiry->remarks, 0, 50)) . (strlen($enquiry->remarks) > 50 ? '...' : '') ?>
                        </div>
                      <?php endif; ?>
                    </div>
                  </div>
                </td>
                <td class="py-2">
                  <div class="d-inline-flex align-items-center gap-2 bg-light px-3 py-2  border" style="background-color: rgba(59, 130, 246, 0.1) !important;
        color: #2563eb !important;
        border: 1px solid rgba(59, 130, 246, 0.2) !important; border-radius:50px ">
                    <i class="bi bi-shield-check"></i>
                    <span class="fw-medium  small">Dog Insurance</span>
                  </div>
                </td>
                <td class="py-2">
                  <div class="d-flex align-items-center gap-2 text-dark fw-medium">
                    <i class="bi bi-calendar text-muted "></i>
                    <span><?= $formattedDate ?></span>
                  </div>
                </td>
                <td class="py-2 text-center">
                  <?php
                  $statusClass = 'bg-light text-dark border';
                  $icon = 'bi-clock';
                  if ($enquiry->status === 'pending') {
                    $statusClass = 'bg-warning bg-opacity-10 text-warning border-warning border-opacity-25';
                    $icon = 'bi-clock';
                  } elseif ($enquiry->status === 'approved') {
                    $statusClass = 'bg-success bg-opacity-10 text-success border-success border-opacity-25';
                    $icon = 'bi-check-circle';
                  } elseif ($enquiry->status === 'rejected') {
                    $statusClass = 'bg-danger bg-opacity-10 text-danger border-danger border-opacity-25';
                    $icon = 'bi-x-circle';
                  }
                  ?>
                  <span class="badge d-inline-flex align-items-center gap-2  rounded-pill fw-semibold <?= $statusClass ?>"
                    style=" background-color: rgba(251, 191, 36, 0.1) !important;
        color: #d97706 !important;
        border: 1px solid rgba(251, 191, 36, 0.2) !important; font-size: 0.75rem; border-radius:50px">
                    <i class="<?= $icon ?> "></i>
                    <?= ucfirst($enquiry->status) ?>
                  </span>
                </td>
                <td class="pe-4 py-2 text-end">
                  <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-outline-primary btn-sm "
                      onclick="showAllDetails(<?= htmlspecialchars(json_encode($enquiry)) ?>)">
                      <i class="bi bi-eye me-1"></i>Details
                    </button>
                    <button class="btn btn-outline-dark btn-sm"
                      onclick="openUpdateStatusModal(<?= $enquiry->id ?>, '<?= $enquiry->status ?>', '<?= addslashes($enquiry->remarks ?? '') ?>')">
                      <i class="bi bi-pencil me-1"></i>Update
                    </button>
                  </div>
                </td>
              </tr>
            <?php
              $i++;
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
  </div>
  </div>
</main>

<!-- Pet Details Modal -->
<div id="petDetailsModal" class="modal" tabindex="-1" aria-labelledby="petDetailsModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow p-0" style="max-height: 90vh;">
      <div class="modal-header" style="background: #c6d2ff!important; border-bottom: 2px solid #a3b4ff;">
        <h2 class="modal-title text-black fw-bold fs-4 mb-0">
          <i class="bi bi-info-circle me-2"></i>Enquiry Details
        </h2>
        <button type="button" class="btn-close" aria-label="Close" id="closePetDetailsBtn"></button>
      </div>
      <div class="modal-body p-0" style="overflow-y: auto;">
        <div class="p-4">
          <div id="petDetailsContent"></div>
        </div>
      </div>
      <div class="modal-footer border-top bg-light">
        <button type="button" class="btn btn-secondary px-4" id="closePetDetailsFooter"> <i
            class="bi bi-x-circle me-1"></i>Close
        </button>
=======
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
>>>>>>> origin/main
      </div>
    </div>
  </div>
</div>

<<<<<<< HEAD
<!-- Add Enquiry Modal -->
<div id="addEnquiryModal" class="modal" tabindex="-1" aria-labelledby="addEnquiryModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow p-0">
      <div class="modal-header" style="background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%);">
        <h2 class="modal-title text-white fw-bold fs-4 mb-0">
          <i class="bi bi-plus-circle me-2"></i>Add New Enquiry
        </h2>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"
          onclick="closeAddEnquiryModal()"></button>
      </div>
      <div class="modal-body p-4">
        <!-- Step 1: Search Client -->
        <div id="searchClientStep">
          <div class="mb-4">
            <label class="form-label fw-bold text-muted small">SEARCH CLIENT BY PHONE</label>
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0">
                <i class="bi bi-telephone"></i>
              </span>
              <input type="text" class="form-control border-start-0" id="searchPhone" placeholder="Enter phone number">
              <button class="btn bg-primary text-white m-0" type="button" onclick="searchClient()">
                <i class="bi bi-search me-1"></i>Search
=======
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
>>>>>>> origin/main
              </button>
            </div>
            <div id="searchFeedback" class="mt-2 small"></div>
          </div>
        </div>

        <!-- Step 1b: New Client Section (Hidden initially) -->
        <div id="newClientSection" style="display: none;">
          <div class="d-flex justify-content-between align-items-center mb-3">
<<<<<<< HEAD
            <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-person-plus me-2"></i>New Client Details</h5>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="backToSearch()">Back to Search</button>
=======
            <h6 class="fw-bold mb-0 text-indigo"><i class="bi bi-person-plus me-1.5"></i>New Client Profile</h6>
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" onclick="backToSearch()">
              <i class="bi bi-arrow-left me-1"></i>Back to Search
            </button>
>>>>>>> origin/main
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
<<<<<<< HEAD
              <label class="form-label fw-bold text-black small">FULL NAME</label>
              <input type="text" class="form-control" id="new_client_name" placeholder="Enter name">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold text-black small">EMAIL</label>
              <input type="email" class="form-control" id="new_client_email" placeholder="Enter email">
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold text-black small">PHONE</label>
              <input type="text" class="form-control" id="new_client_phone" placeholder="Enter phone">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold text-black small">ADDRESS</label>
              <textarea class="form-control" id="new_client_address" placeholder="Enter address"></textarea>
            </div>
          </div>

          <h5 class="fw-bold mb-3 text-primary mt-4"><i class="bi bi-paw me-2"></i>Pet Details</h5>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold text-black small">PET NAME</label>
              <input type="text" class="form-control" id="new_pet_name" placeholder="Pet name">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold text-black small">BREED</label>
              <select class="form-select" id="new_pet_breed">
                <option value="">Select Breed</option>
              </select>
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold text-black small">AGGRESSIVE</label>
              <select class="form-select" id="new_pet_aggressive">
                <option value="No">No</option>
                <option value="Yes">Yes</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold text-black small">VACCINATED</label>
              <select class="form-select" id="new_pet_vaccinated">
=======
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
>>>>>>> origin/main
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
<<<<<<< HEAD
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <label class="form-label fw-bold text-black small">DOB</label>
              <input type="date" class="form-control" id="new_pet_dob">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold text-black small">GENDER</label>
              <select class="form-select" id="new_pet_gender">
=======
            <div class="col-md-4">
              <label class="form-label fw-bold small text-muted">DATE OF BIRTH</label>
              <input type="date" class="form-control rounded-3" id="new_pet_dob">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold small text-muted">GENDER</label>
              <select class="form-select rounded-3" id="new_pet_gender">
>>>>>>> origin/main
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
            </div>
            <div class="col-md-4">
<<<<<<< HEAD
              <label class="form-label fw-bold text-black small">LAST VACCINATION</label>
              <input type="date" class="form-control" id="new_pet_vaccination">
            </div>
          </div>
          <hr>
=======
              <label class="form-label fw-bold small text-muted">LAST VACCINATION</label>
              <input type="date" class="form-control rounded-3" id="new_pet_vaccination">
            </div>
          </div>
          <hr class="my-4">
>>>>>>> origin/main
        </div>

        <!-- Step 2: Enquiry Form (Hidden initially) -->
        <form id="enquiryForm" style="display: none;">
          <input type="hidden" id="foundClientId" name="user_id">
          <input type="hidden" id="is_new_client" name="is_new_client" value="0">

          <div id="existingClientDisplay">
            <div class="row g-3 mb-3">
              <div class="col-md-6">
<<<<<<< HEAD
                <label class="form-label fw-bold text-black small">CLIENT NAME</label>
                <input type="text" class="form-control bg-light" id="displayClientName" readonly name="full_name">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-bold text-black small">CLIENT EMAIL</label>
                <input type="email" class="form-control bg-light" id="displayClientEmail" readonly name="email">
=======
                <label class="form-label fw-bold small text-muted">CLIENT NAME</label>
                <input type="text" class="form-control bg-light rounded-3" id="displayClientName" readonly name="full_name">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-bold small text-muted">CLIENT EMAIL</label>
                <input type="email" class="form-control bg-light rounded-3" id="displayClientEmail" readonly name="email">
>>>>>>> origin/main
                <input type="hidden" id="displayClientPhone" name="phone">
              </div>
            </div>

            <div class="row g-3 mb-3" id="petSelectRow">
              <div class="col-md-12">
<<<<<<< HEAD
                <label class="form-label fw-bold text-black small" id="petSelectLabel">SELECT PET</label>
                <select class="form-select" id="petSelect" name="pet_id" required>
=======
                <label class="form-label fw-bold small text-muted" id="petSelectLabel">SELECT PET</label>
                <select class="form-select rounded-3" id="petSelect" name="pet_id" required>
>>>>>>> origin/main
                  <option value="">Select a pet</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
<<<<<<< HEAD
              <label class="form-label fw-bold text-black small">COVERAGE TYPE</label>
              <select class="form-select" name="coverage_type" required>
=======
              <label class="form-label fw-bold small text-muted">COVERAGE TYPE</label>
              <select class="form-select rounded-3" name="coverage_type" required>
>>>>>>> origin/main
                <option value="Standard">Standard</option>
                <option value="Premium">Premium</option>
                <option value="Basic">Basic</option>
              </select>
            </div>
            <div class="col-md-6">
<<<<<<< HEAD
              <label class="form-label fw-bold text-black small">DURATION (YEARS)</label>
              <select class="form-select" name="duration_years" required onchange="updateEndDate(this)">
=======
              <label class="form-label fw-bold small text-muted">DURATION (YEARS)</label>
              <select class="form-select rounded-3" name="duration_years" required onchange="updateEndDate(this)">
>>>>>>> origin/main
                <option value="1">1 Year</option>
                <option value="2">2 Years</option>
                <option value="3">3 Years</option>
              </select>
            </div>
<<<<<<< HEAD
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold text-black small">START DATE</label>
              <input type="date" class="form-control" style="padding:0.5rem 3rem 0.5rem 1rem !important; border: 1px solid #eee !important" name="start_date" id="startDate"
                required onchange="calculateEndDate()">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold text-black small">END DATE</label>
              <input type="date" class="form-control bg-light" style="padding:0.5rem 3rem 0.5rem 1rem !important; border: 1px solid #eee !important;" name="end_date"
                id="endDate" readonly required>
=======
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">START DATE</label>
              <input type="date" class="form-control rounded-3" name="start_date" id="startDate" required onchange="calculateEndDate()">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted">END DATE</label>
              <input type="date" class="form-control bg-light rounded-3" name="end_date" id="endDate" readonly required>
>>>>>>> origin/main
            </div>
          </div>
        </form>
      </div>
<<<<<<< HEAD
      <div class="modal-footer bg-light p-3">
        <button type="button" class="btn btn-outline-secondary px-4" onclick="closeAddEnquiryModal()">Cancel</button>
        <button type="button" class="btn bg-primary text-white px-4" id="submitEnquiryBtn" style="display: none;"
          onclick="submitEnquiry()">
          <i class="bi bi-save me-1"></i>Save Enquiry
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Email Modal -->
<div id="emailModal" class="modal" tabindex="-1" aria-labelledby="emailModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow p-0" style="height: 90vh;">
      <div class="modal-header"
        style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); border-bottom: 2px solid #a3b4ff;">
        <h2 class="modal-title text-white fw-bold fs-4 mb-0">
          <i class="bi bi-envelope-plus me-2"></i>Send Quotation
        </h2>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"
          onclick="closeEmailModal()"></button>
      </div>
      <div class="modal-body p-0" style="overflow-y: auto; flex: 1;">
        <div class="row g-0 h-100">
          <!-- Left Column - Form Fields -->
          <div class="col-md-5 p-4 border-end bg-white">
            <div class="mb-4">
              <label class="form-label text-muted small mb-2 fw-semibold">ENQUIRY ID</label>
              <div class="input-group">
                <span class="input-group-text bg-black border-end-0 text-white">
                  <i class="bi bi-file-text "></i>
                </span>
                <input type="text" class="form-control border-start-0 bg-white" id="enquiryId" readonly
                  name="policy_id">
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label text-muted small mb-2 fw-semibold">QUOTE AMOUNT</label>
              <div class="input-group">
                <span class="input-group-text bg-black border-end-0 text-white">
                  <i class="fa-solid fa-rupee-sign "></i>
                </span>
                <input type="number" class="form-control border-start-0" id="price" name="amount" placeholder="0.00">
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label text-muted small mb-2 fw-semibold">CLIENT EMAIL</label>
              <div class="input-group">
                <span class="input-group-text bg-black border-end-0 text-white">
                  <i class="bi bi-envelope "></i>
                </span>
                <input type="email" class="form-control border-start-0 bg-white" id="toEmail" readonly name="email">
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label text-muted small mb-2 fw-semibold">SUBJECT</label>
              <div class="input-group">
                <span class="input-group-text bg-black border-end-0 text-white">
                  <i class="bi bi-chat-text "></i>
                </span>
                <input type="text" class="form-control border-start-0" id="emailSubject" value="Pet Insurance Quotation"
                  name="subject">
              </div>
            </div>
          </div>

          <!-- Right Column - Message -->
          <div class="col-md-7 p-4">
            <label class="form-label text-muted small mb-2 fw-semibold">MESSAGE</label>
            <div class="border rounded bg-white" style="height: 300px;">
              <textarea class="form-control border-0 p-3" id="emailBody" name="message"
                style="height: 100%; resize: none; font-size: 0.95rem; line-height: 1.6;">Dear Customer,

Thank you for your enquiry about pet insurance. We are pleased to provide you with a quotation for your pet insurance.

Please find the details of our quotation below:

- Premium Amount: 
- Coverage Period: 
- Policy Benefits: 
- Terms and Conditions: 

If you have any questions or would like to proceed with this insurance, please don't hesitate to contact us.

Best regards,
Insurance Team</textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer border-top-0 p-4 bg-light">
        <button type="button" class="btn btn-outline-secondary px-4 py-2 " onclick="closeEmailModal()">
          <i class="bi bi-x-circle me-1"></i>Cancel
        </button>
        <button type="button" class="btn bg-primary text-white px-4 py-2 " onclick="sendQuotation(event)">
          <i class="bi bi-send me-1"></i>Send Quotation
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Update Status Modal -->
<div id="updateStatusModal" class="modal fade" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md " style="max-width: 450px !important;">
    <div class="modal-content border-0 shadow-lg rounded-4 p-0">

      <!-- Header -->
      <div class="modal-header border-0 pb-0">
        <div>
          <h5 class="modal-title fw-bold mb-1">Update Status</h5>
          <p class="text-muted small mb-0">Change enquiry status below</p>
        </div>
        <button type="button" class="btn-close bg-primary text-white" aria-label="Close"
          onclick="closeUpdateStatusModal()"></button>
      </div>

      <!-- Body -->
      <div class="modal-body p-3 pb-4">
        <input type="hidden" id="updateEnquiryId">

        <label class="form-label fw-semibold small text-uppercase text-muted mb-2">
          Select Status
        </label>

        <select class="form-select text-black form-select-lg shadow-sm rounded-3 mb-3" id="updateEnquiryStatus">
          <option value="pending" selected>🕒 Pending</option>
          <option value="approved">✅ Approved</option>
          <option value="rejected">❌ Rejected</option>
        </select>

        <label class="form-label fw-semibold small text-uppercase text-muted mb-2">
          Remarks / Comments
        </label>
        <textarea class="form-control rounded-3" id="updateEnquiryRemarks" rows="3" placeholder="Add any internal comments here..."></textarea>
      </div>

      <!-- Footer -->
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-light px-4 rounded-3" onclick="closeUpdateStatusModal()">
          Cancel
        </button>

        <button type="button" class="btn btn-dark px-4 rounded-3" onclick="submitStatusUpdate()">
          Update Status
        </button>
      </div>

=======

      <div class="modal-footer border-top bg-light px-4 py-2.5 d-flex justify-content-between">
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal" onclick="closeAddEnquiryModal()">
          Cancel
        </button>
        <button type="button" class="btn btn-sm btn-primary rounded-pill px-4 fw-semibold" id="submitEnquiryBtn" style="display: none;" onclick="submitEnquiry()">
          <i class="bi bi-check2-circle me-1"></i>Save Enquiry
        </button>
      </div>
>>>>>>> origin/main
    </div>
  </div>
</div>

<<<<<<< HEAD
<style>
  /* Fixed body when modal is open */
  body.modal-open {
    overflow: hidden !important;
    padding-right: 0 !important;
  }

  /* Modal backdrop */
  .modal-backdrop {
    background-color: rgba(0, 0, 0, 0.7) !important;
  }

  /* Custom styles to match the exact design */
  .bg-gradient-primary {
    background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
  }

  .bg-light-primary {
    background-color: rgba(99, 102, 241, 0.08) !important;
  }

  .card {
    border-radius: 16px !important;
  }

  .table thead th {
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    padding-top: 1rem;
    padding-bottom: 1rem;
  }

  .table tbody tr {
    transition: all 0.2s ease;
  }

  .table tbody tr:hover {
    background-color: rgba(99, 102, 241, 0.03) !important;
  }

  .modal {
    display: none;
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    overflow-y: auto !important;
    align-items: center;
    justify-content: center;
  }

  .modal.show {
    display: flex;
  }

  .modal-dialog {
    margin: auto;
    width: 90%;
    max-width: 900px;
  }

  .modal-dialog-centered {
    display: flex;
    align-items: center;
    min-height: calc(100% - 3rem);
  }

  .modal-dialog-scrollable .modal-content {
    max-height: 100%;
    overflow: hidden;
  }

  .modal-dialog-scrollable .modal-body {
    overflow-y: auto;
  }

  .modal-content {
    background-color: white;
    border-radius: 16px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    border: none;
    overflow: hidden;
    display: flex;
    flex-direction: column;
  }

  .modal-header {
    padding: 1.5rem;
    border-bottom: none;
    flex-shrink: 0;
  }

  .modal-header h2 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
  }

  .modal-body {
    padding: 0;
    flex: 1 1 auto;
    overflow-y: auto;
  }

  .modal-footer {
    border-top: 1px solid #e5e7eb;
    flex-shrink: 0;
  }

  .badge {
    padding: 0.6rem 1.2rem !important;
    font-weight: 600 !important;
  }

  .form-label {
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .input-group-text {
    /* background-color: #f9fafb !important; */
    /* border-color: #e5e7eb !important; */
  }

  .form-control:focus {
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25) !important;
  }

  .form-control.bg-light {
    background-color: #f9fafb !important;
  }

  .btn-outline-primary {
    border-color: #dee2e6;
    color: #6c757d;
  }

  .btn-outline-primary:hover {
    border-color: #6366f1;
    color: #6366f1;
    background-color: transparent;
  }

  /* Custom scrollbar for modal body */
  .modal-body::-webkit-scrollbar {
    width: 8px;
  }

  .modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
  }

  .modal-body::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
  }

  .modal-body::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
  }

  /* Pet details content styling */
  .pet-detail-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px solid #dee2e6;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
  }

  .pet-detail-card:hover {
    border-color: #6366f1;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(99, 102, 241, 0.1);
  }

  .pet-detail-label {
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #6c757d;
  }

  .pet-detail-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: #495057;
  }

  /* Modal animation */
  .modal.show .modal-dialog {
    animation: modalSlideIn 0.3s ease-out;
  }

  @keyframes modalSlideIn {
    from {
      transform: translateY(-30px) scale(0.95);
      opacity: 0;
    }

    to {
      transform: translateY(0) scale(1);
      opacity: 1;
    }
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .modal-dialog {
      width: 95%;
      margin: 20px auto;
    }

    .modal-content {
      height: 85vh !important;
    }

    #emailModal .row {
      flex-direction: column;
    }

    #emailModal .border-end {
      border-right: none !important;
      border-bottom: 1px solid #e5e7eb !important;
    }

    #emailModal .col-md-5,
    #emailModal .col-md-7 {
      width: 100%;
    }
  }

  @media (max-width: 576px) {
    .modal-content {
      height: 90vh !important;
    }

    .modal-header {
      padding: 15px;
    }

    .modal-body {
      padding: 15px !important;
    }

    .modal-footer {
      padding: 15px;
    }

    .pet-detail-card {
      padding: 15px;
    }

    .pet-detail-value {
      font-size: 1.1rem;
    }
  }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
  integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
  crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
  // Prevent body scroll when modal is open
  function preventBodyScroll() {
    // document.body.classList.add('modal-open');

    const mainContent = document.querySelector('.main-content');
    if (mainContent) {
      // mainContent.setAttribute('inert', '');
    }
  }

  function allowBodyScroll() {
    document.body.classList.remove('modal-open');

    const mainContent = document.querySelector('.main-content');
    if (mainContent) {
      // mainContent.removeAttribute('inert');
    }
  }

  function focusModal(modalId) {
    setTimeout(() => {
      const modal = document.getElementById(modalId);
      if (modal) {
        const closeBtn = modal.querySelector('.btn-close');
        if (closeBtn) closeBtn.focus();
      }
    }, 100);
  }

  // Pet Details Modal Functions
  function showAllDetails(enquiry) {
    preventBodyScroll();

    const formattedDate = new Date(enquiry.created_at).toLocaleDateString('en-GB');

    const content = `
      <div class="row g-4">
        <!-- Client Information -->
        <div class="col-12">
          <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-person-circle fs-5 text-primary"></i>
            <h5 class="mb-0 fw-bold">Client Information</h5>
          </div>
          <div class="row g-3">
            <div class="col-md-4">
              <div class="pet-detail-card h-100">
                <div class="pet-detail-label">Full Name</div>
                <div class="pet-detail-value mt-1" style="font-size: 1.1rem;">${enquiry.full_name}</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="pet-detail-card h-100">
                <div class="pet-detail-label">Email</div>
                <div class="pet-detail-value mt-1" style="font-size: 1.1rem;">${enquiry.email}</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="pet-detail-card h-100">
                <div class="pet-detail-label">Phone</div>
                <div class="pet-detail-value mt-1" style="font-size: 1.1rem;">${enquiry.phone}</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Pet Information -->
        <div class="col-12 mt-4">
          <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-paw fs-5 text-primary"></i>
            <h5 class="mb-0 fw-bold">Pet Information</h5>
          </div>
          <div class="row g-3">
            <div class="col-md-3">
              <div class="pet-detail-card h-100">
                <div class="pet-detail-label">Name</div>
                <div class="pet-detail-value mt-1" style="font-size: 1.1rem;">${enquiry.name || 'N/A'}</div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="pet-detail-card h-100">
                <div class="pet-detail-label">Breed</div>
                <div class="pet-detail-value mt-1" style="font-size: 1.1rem;">${enquiry.breed || 'N/A'}</div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="pet-detail-card h-100">
                <div class="pet-detail-label">Age/DOB</div>
                <div class="pet-detail-value mt-1" style="font-size: 1.1rem;">${enquiry.dob || 'N/A'}</div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="pet-detail-card h-100">
                <div class="pet-detail-label">Gender</div>
                <div class="pet-detail-value mt-1" style="font-size: 1.1rem;">${enquiry.gender || 'N/A'}</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Policy Information -->
        <div class="col-12 mt-4">
          <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-shield-check fs-5 text-primary"></i>
            <h5 class="mb-0 fw-bold">Policy Request Details</h5>
          </div>
          <div class="row g-3">
            <div class="col-md-4">
              <div class="pet-detail-card h-100 border-primary border-opacity-25" style="background: rgba(99, 102, 241, 0.02);">
                <div class="pet-detail-label">Coverage Type</div>
                <div class="pet-detail-value mt-1 text-primary" style="font-size: 1.1rem;">${enquiry.coverage_type || 'Dog Insurance'}</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="pet-detail-card h-100">
                <div class="pet-detail-label">Duration</div>
                <div class="pet-detail-value mt-1" style="font-size: 1.1rem;">${enquiry.duration_years} Years</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="pet-detail-card h-100">
                <div class="pet-detail-label">Request Date</div>
                <div class="pet-detail-value mt-1" style="font-size: 1.1rem;">${formattedDate}</div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 mt-4">
          <div class="alert alert-info border-0 shadow-sm d-flex align-items-center gap-3 mb-3">
             <i class="bi bi-info-circle-fill fs-4"></i>
             <div>
                <div class="fw-bold">Vaccination Status</div>
                <div>${enquiry.last_vaccination || 'No vaccination records provided for this pet.'}</div>
             </div>
          </div>

          <div class="card border-0 shadow-sm" style="background: #f8f9fa;">
            <div class="card-body">
              <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-chat-left-text text-primary"></i>
                <h6 class="mb-0 fw-bold">Admin Remarks</h6>
              </div>
              <p class="text-muted mb-0 small">${enquiry.remarks || 'No remarks added yet.'}</p>
=======
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
>>>>>>> origin/main
            </div>
          </div>
        </div>
      </div>
    `;

<<<<<<< HEAD
    $('#petDetailsContent').html(content);
    $('#petDetailsModal').attr('aria-hidden', 'false').addClass('show').css('display', 'flex');
    focusModal('petDetailsModal');
  }

  function showPetDetails(button) {
    // Legacy support if needed, but we use showAllDetails now
    const row = button.closest('tr');
    // ...
  }

  function closePetDetails() {
    const modal = $('#petDetailsModal');

    modal.removeClass('show');
    modal.css('display', 'none');
    modal.attr('aria-hidden', 'true');

    allowBodyScroll();


    // Clear content (optional but recommended)
    $('#petDetailsContent').html('');
  }

  // Email Modal Functions
  function showEmailForm(email, enquiryId, price = '') {
    preventBodyScroll();
    $('#toEmail').val(email);
    $('#enquiryId').val(enquiryId);
    $('#price').val(price || '');
    $('#emailModal').attr('aria-hidden', 'false').addClass('show').css('display', 'flex');
    focusModal('emailModal');
  }

  function closeEmailModal() {
    allowBodyScroll();
    $('#emailModal').attr('aria-hidden', 'true').removeClass('show').css('display', 'none');
  }

  function sendQuotation(event) {
    event.preventDefault();

    let formData = {
      email: $('#toEmail').val(),
      policy_id: $('#enquiryId').val(),
      amount: $('#price').val(),
      subject: $('#emailSubject').val(),
      message: $('#emailBody').val(),
    };

    console.log(formData);

    if (!formData.email || !formData.policy_id) {
      alert('Missing required data.');
      return;
    }

    $.ajax({
      url: "<?= base_url('insurance/sendQuotation') ?>",
      method: 'POST',
      data: formData,
      dataType: "json",
      beforeSend: function() {
        $('.btn-primary').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Sending...');
      },
      success: function(response) {
        if (response.status === true) {
          Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Quotation sent successfully!',
            timer: 2000,
            showConfirmButton: false
          });
          closeEmailModal();
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: response.message || 'Failed to send quotation.'
          });
        }
      },
      error: function(xhr) {
        console.error(xhr.responseText);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'An error occurred while sending the quotation.'
        });
      },
      complete: function() {
        $('.btn-primary').prop('disabled', false).html('<i class="bi bi-send me-1"></i>Send Quotation');
      }
    });
  }

  function showNewClientForm() {
    $('#searchClientStep').hide();
    $('#newClientSection').fadeIn();
    $('#enquiryForm').fadeIn();
    $('#existingClientDisplay').hide();
    $('#is_new_client').val('1');
    $('#submitEnquiryBtn').show();

    // Disable required attribute for petSelect when adding new client
    $('#petSelect').prop('required', false);

    // Auto-fill phone if entered in search
    if ($('#searchPhone').val()) {
      $('#new_client_phone').val($('#searchPhone').val());
    }
  }

  function backToSearch() {
    $('#newClientSection').hide();
    $('#enquiryForm').hide();
    $('#existingClientDisplay').show();
    $('#searchClientStep').show();
    $('#is_new_client').val('0');
    $('#submitEnquiryBtn').hide();
    $('#searchFeedback').html('');

    // Re-enable required attribute for petSelect
    $('#petSelect').prop('required', true);
  }

  // Add Enquiry Modal Functions
  function showAddEnquiryModal() {
    preventBodyScroll();
    $('#addEnquiryModal').attr('aria-hidden', 'false').addClass('show').css('display', 'flex');
    focusModal('addEnquiryModal');
    $('#searchClientStep').show();
    $('#enquiryForm').hide();
    $('#newClientSection').hide();
    $('#existingClientDisplay').show();
    $('#is_new_client').val('0');
    $('#submitEnquiryBtn').hide();
    $('#searchPhone').val('');
    $('#searchFeedback').html('');

    // Ensure pet_id is required initially
    $('#petSelect').prop('required', true);

    calculateEndDate();
  }

  function closeAddEnquiryModal() {
    allowBodyScroll();
    $('#addEnquiryModal').attr('aria-hidden', 'true').removeClass('show').css('display', 'none');
  }

  function searchClient() {
    const phone = $('#searchPhone').val();
    if (!phone) {
      $('#searchFeedback').html('<span class="text-danger">Please enter a phone number</span>');
      return;
    }

    const btn = $('#searchClientStep button');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Searching...');

    $.ajax({
      url: "<?= base_url('insurance/searchClient') ?>",
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({
        phone: phone
      }),
      success: function(response) {
        if (response.success) {
          $('#foundClientId').val(response.client.id);
          $('#displayClientName').val(response.client.full_name);
          $('#displayClientEmail').val(response.client.email);
          $('#displayClientPhone').val(response.client.phone);

          // Load pets
          loadClientPets(response.client.id);

          $('#searchClientStep').hide();
          $('#enquiryForm').fadeIn();
          $('#submitEnquiryBtn').show();
        } else {
          $('#searchFeedback').html(`
            <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center justify-content-between p-2 mb-0">
              <span class="small fw-medium"><i class="bi bi-exclamation-triangle me-2"></i>${response.message}</span>
              <button type="button" class="btn btn-sm btn-dark px-3" onclick="showNewClientForm()">Add New Client</button>
            </div>
          `);
        }
      },
      error: function() {
        $('#searchFeedback').html('<span class="text-danger">Connection error. Please try again.</span>');
      },
      complete: function() {
        btn.prop('disabled', false).html('<i class="bi bi-search me-1"></i>Search');
      }
    });
  }

  function loadClientPets(clientId) {
    $.ajax({
      url: "<?= base_url('insurance/getClientPets') ?>",
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({
        client_id: clientId
      }),
      success: function(response) {
        const select = $('#petSelect');
        select.empty().append('<option value="">Select a pet</option>');

        if (response.success && response.pets) {
          response.pets.forEach(pet => {
            select.append(`<option value="${pet.id}">${pet.name} (${pet.breed || 'N/A'})</option>`);
          });
        } else {
          select.append('<option value="" disabled>No pets found</option>');
        }
      }
    });
  }

  function calculateEndDate() {
    const startDate = $('#startDate').val();
    const years = parseInt($('[name="duration_years"]').val()) || 1;

    if (startDate) {
      const date = new Date(startDate);
      date.setFullYear(date.getFullYear() + years);
      $('#endDate').val(date.toISOString().split('T')[0]);
    }
  }

  function updateEndDate() {
    calculateEndDate();
  }

  function submitEnquiry() {
    const form = document.getElementById('enquiryForm');
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    const formData = {};
    const inputs = $(form).serializeArray();
    inputs.forEach(input => {
      formData[input.name] = input.value;
    });

    // Add new client/pet data if applicable
    if ($('#is_new_client').val() === '1') {
      formData.new_client_name = $('#new_client_name').val();
      formData.new_client_email = $('#new_client_email').val();
      formData.new_client_phone = $('#new_client_phone').val();
      formData.new_client_address = $('#new_client_address').val();

      formData.new_pet_name = $('#new_pet_name').val();
      formData.new_pet_breed = $('#new_pet_breed').val();
      formData.new_pet_dob = $('#new_pet_dob').val();
      formData.new_pet_gender = $('#new_pet_gender').val();
      formData.new_pet_vaccination = $('#new_pet_vaccination').val();
      formData.new_pet_aggressive = $('#new_pet_aggressive').val();
      formData.new_pet_vaccinated = $('#new_pet_vaccinated').val();

      // Basic validation for new client
      if (!formData.new_client_name || !formData.new_client_phone || !formData.new_pet_name) {
        Swal.fire('Required Fields', 'Please fill in client name, phone and pet name.', 'warning');
        return;
      }
    }

    const btn = $('#submitEnquiryBtn');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Saving...');

    $.ajax({
      url: "<?= base_url('insurance/addEnquiry') ?>",
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify(formData),
      success: function(response) {
        if (response.status === true) {
          Swal.fire({
            icon: 'success',
            title: 'Enquiry Added',
            text: 'The enquiry has been successfully created.',
            timer: 2000,
            showConfirmButton: false
          }).then(() => {
            location.reload();
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Failed',
            text: response.message || 'Could not add enquiry.'
          });
        }
      },
      error: function() {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'An error occurred while saving.'
        });
      },
      complete: function() {
        btn.prop('disabled', false).html('<i class="bi bi-save me-1"></i>Save Enquiry');
      }
    });
  }

  // Close modals when clicking outside
  $(document).on('click', function(e) {
    if (e.target.id === 'petDetailsModal') {
      closePetDetails();
    }
    if ($(e.target).is('#emailModal')) {
      closeEmailModal();
    }
    if ($(e.target).is('#addEnquiryModal')) {
      closeAddEnquiryModal();
    }
  });

  // Close modals on Escape key
  $(document).on('keydown', function(e) {
    if (e.key === 'Escape') {
      closePetDetails();
      closeEmailModal();
      closeAddEnquiryModal();
    }
  });

  // Search functionality
  // document.getElementById('searchInput').addEventListener('input', function(e) {
  //   const searchTerm = e.target.value.toLowerCase();
  //   const rows = document.querySelectorAll('tbody tr');

  //   rows.forEach(row => {
  //     const text = row.textContent.toLowerCase();
  //     row.style.display = text.includes(searchTerm) ? '' : 'none';
  //   });
  // });

  function loadBreeds() {
    $.ajax({
      url: "<?= base_url('insurance/getDogBreeds') ?>",
      method: "GET",
      success: function(response) {
        if (response.success) {
          let options = '<option value="">Select Breed</option>';
          response.breeds.forEach(function(breed) {
            options += `<option value="${breed.value}">${breed.label}</option>`;
          });
          $('#new_pet_breed').html(options);
        }
      }
    });
  }

  // Initialize on page load
  $(document).ready(function() {
    console.log('Enquiries page loaded');
    loadBreeds();
    calculateEndDate();
    $('#closePetDetailsBtn, #closePetDetailsFooter').on('click', function(e) {
      e.stopPropagation();
      closePetDetails();
    });

    // Initialize tooltips if needed
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    });
  });

  function openUpdateStatusModal(id, currentStatus, remarks = '') {
    console.log('Opening update status modal for ID:', id, 'Current Status:', currentStatus);
    preventBodyScroll();
    $('#updateEnquiryId').val(id);
    $('#updateEnquiryStatus').val(currentStatus.toLowerCase());
    $('#updateEnquiryRemarks').val(remarks);
    $('#updateStatusModal').attr('aria-hidden', 'false').addClass('show').css('display', 'flex');
    focusModal('updateStatusModal');
  }

  function closeUpdateStatusModal() {
    allowBodyScroll();
    $('#updateStatusModal').attr('aria-hidden', 'true').removeClass('show').css('display', 'none');
  }

  function submitStatusUpdate() {
    const id = $('#updateEnquiryId').val();
    const status = $('#updateEnquiryStatus').val();
    const remarks = $('#updateEnquiryRemarks').val();

    $.ajax({
      url: "<?= base_url('insurance/updateEnquiryStatus') ?>",
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({
        id: id,
        status: status,
        remarks: remarks
      }),
      success: function(response) {
        if (response.status) {
          Swal.fire({
            icon: 'success',
            title: 'Updated!',
            text: 'Status updated successfully.',
            timer: 1500,
            showConfirmButton: false
          }).then(() => {
            location.reload();
          });
        } else {
          alert(response.message || 'Failed to update status.');
        }
      },
      error: function() {
        alert('An error occurred while updating status.');
      }
    });
  }
</script>

<?php echo $this->include('admin/footer') ?>
<?php echo $this->endSection() ?>
=======
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
>>>>>>> origin/main
