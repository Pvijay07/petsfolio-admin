<?php echo $this->extend('admin/includes/header'); ?>
<!--start content-->

<?php echo $this->section('content'); ?>
<main class="main-content">
  <div class="position-relative iq-banner">
    <?php echo $this->include('admin/nav_bar') ?>
  </div>
  <div class="container-fluid px-4 mb-4 " style="margin-top: -50px !important;">
    <div class="modern-header">
      <div class="position-relative z-1">
        <h1 class="h2 fw-bold mb-2 text-white">Service Wallet Statement</h1>
        <p class="text-white-75 mb-0">Track payments, commissions, refunds, and wallet balances for service bookings</p>
      </div>
      <i class="bi bi-wallet2 position-absolute end-0 bottom-0 opacity-10"
        style="font-size: 14rem; transform: rotate(12deg); margin-right: -3rem; margin-bottom: -3rem;"></i>
    </div>
    <div class="row">
      <div class="col-xl-12 col-lg-8">
        <!-- Summary Cards -->
        <div class="card border-0 shadow-sm mb-4 rounded-3">
          <div class="card-header p-3" style="background: #c6d2ff !important; border-bottom: 2px solid #a3b4ff;">
            <div class="d-flex justify-content-between align-items-center">
              <div class="header-title">
                <h5 class="card-title mb-0 text-black">
                  <i class="bi bi-graph-up me-2"></i>Financial Summary
                </h5>
              </div>
              <div class="text-muted small">
                <i class="bi bi-info-circle me-1"></i>
                Real-time financial overview
              </div>
            </div>
          </div>
          <div class="card-body">
            <input type="hidden" name="booking_id" value="<?= $booking_id ?>" id="booking_id">
            <input type="hidden" name="service_id" value="<?= $service_id ?>" id="service_id">
            <input type="hidden" name="provider_id" value="<?= $provider_id ?>" id="provider_id">

            <div class="row g-4 summary-cards">
              <!-- Credit Amount Card -->
              <div class="col-sm-6 col-xl-3">
                <div class="card summary-card border-0 shadow-sm bg-credit mb-0">
                  <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                      <div>
                        <h6 class="text-muted mb-1 fw-semibold">Credit Amount</h6>
                        <h5 class="mb-0 fw-bold text-credit" id="creditAmount">₹0</h5>
                      </div>
                      <div class="card-icon">
                        <i class="bi bi-arrow-down-circle-fill text-credit"></i>
                      </div>
                    </div>
                    <div class="small text-muted">
                      <i class="bi bi-calendar-week me-1"></i>
                      Total credited amount
                    </div>
                  </div>
                </div>
              </div>

              <!-- Payment Card -->
              <div class="col-sm-6 col-xl-3">
                <div class="card summary-card border-0 shadow-sm bg-payment mb-0">
                  <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                      <div>
                        <h6 class="text-muted mb-1 fw-semibold">Provider Payment</h6>
                        <h5 class="mb-0 fw-bold text-warning" id="providerPayment">₹0</h5>
                      </div>
                      <div class="card-icon">
                        <i class="bi bi-cash-coin text-warning"></i>
                      </div>
                    </div>
                    <div class="small text-muted">
                      <i class="bi bi-person-badge me-1"></i>
                      Amount paid to provider
                    </div>
                  </div>
                </div>
              </div>

              <!-- Commission Card -->
              <div class="col-sm-6 col-xl-3">
                <div class="card summary-card border-0 shadow-sm bg-commission mb-0">
                  <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                      <div>
                        <h6 class="text-muted mb-1 fw-semibold">Platform Commission</h6>
                        <h5 class="mb-0 fw-bold text-secondary" id="platformCommission">₹0</h5>
                      </div>
                      <div class="card-icon">
                        <i class="bi bi-building text-secondary"></i>
                      </div>
                    </div>
                    <div class="small text-muted">
                      <i class="bi bi-percent me-1"></i>
                      10% platform fee
                    </div>
                  </div>
                </div>
              </div>

              <!-- Refund Card -->
              <div class="col-sm-6 col-xl-3">
                <div class="card summary-card border-0 shadow-sm bg-refund mb-0">
                  <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                      <div>
                        <h6 class="text-muted mb-1 fw-semibold">Client Refund</h6>
                        <h5 class="mb-0 fw-bold text-danger" id="clientRefund">₹0</h5>
                      </div>
                      <div class="card-icon">
                        <i class="bi bi-arrow-counterclockwise text-danger"></i>
                      </div>
                    </div>
                    <div class="small text-muted">
                      <i class="bi bi-arrow-left-right me-1"></i>
                      Total refunded amount
                    </div>
                  </div>
                </div>
              </div>

              <!-- Balance Card -->
              <div class="col-sm-6 col-xl-3">
                <div class="card summary-card border-0 shadow-sm bg-balance mb-0">
                  <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                      <div>
                        <h6 class="text-muted mb-1 fw-semibold">Balance Amount</h6>
                        <h5 class="mb-0 fw-bold text-primary" id="balanceAmount">₹0</h5>
                      </div>
                      <div class="card-icon">
                        <i class="bi bi-wallet2 text-primary"></i>
                      </div>
                    </div>
                    <div class="small text-muted">
                      <i class="bi bi-calculator me-1"></i>
                      Net wallet balance
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tracking Details Table -->
        <div class="card border-0 shadow-sm overflow-hidden rounded-3">
          <div class="card-header p-3" style="background: #c6d2ff !important; border-bottom: 2px solid #a3b4ff;">
            <div class="d-flex justify-content-between align-items-center">
              <div class="header-title">
                <h5 class="card-title mb-0 text-black">
                  <i class="bi bi-clock-history me-2"></i>Tracking Details
                </h5>
              </div>
              <div class="text-muted small">
                <i class="bi bi-filter me-1"></i>
                Real-time transaction log
              </div>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-striped table-hover mb-0" id="trackingTable">
                <thead id="trackingDetailsHeader">
                  <!-- Headers will be inserted here by JavaScript -->
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>

          <!-- Loading State -->
          <div id="loadingState" class="text-center py-5 d-none">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading tracking details...</p>
          </div>

          <!-- Empty State -->
          <div id="emptyState" class="text-center py-5 d-none">
            <div class="mb-3">
              <i class="bi bi-receipt display-1 text-muted"></i>
            </div>
            <h5 class="mb-2 text-dark">No tracking records found</h5>
            <p class="text-muted mb-0">No transaction history available for this booking.</p>
          </div>
        </div>
      </div>
    </div>
  </div>




  <?php echo $this->include('admin/footer') ?>
</main>

<style>
  /* Modern Card Styling */
  .summary-card {
    border: none;
    border-radius: 12px;
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
  }

  .summary-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
  }

  .summary-card .card-body {
    padding: 1.5rem;
    position: relative;
    z-index: 1;
  }

  .summary-card .card-icon {
    position: absolute;
    right: 20px;
    top: 20px;
    font-size: 2.5rem;
    opacity: 0.1;
    z-index: 0;
  }

  /* Custom Scrollbar */
  .table-responsive::-webkit-scrollbar {
    height: 8px;
  }

  .table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
  }

  .table-responsive::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
  }

  .table-responsive::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
  }

  /* Status Badges */
  .status-badge {
    padding: 0.35rem 0.75rem;
    border-radius: 20px;
    font-weight: 500;
    font-size: 0.85rem;
  }

  .status-pending {
    background: rgba(245, 158, 11, 0.1) !important;
    color: #d97706 !important;
    border: 1px solid rgba(245, 158, 11, 0.2) !important;
  }

  .status-approved {
    background: rgba(34, 197, 94, 0.1) !important;
    color: #16a34a !important;
    border: 1px solid rgba(34, 197, 94, 0.2) !important;
  }

  .status-rejected {
    background: rgba(239, 68, 68, 0.1) !important;
    color: #dc2626 !important;
    border: 1px solid rgba(239, 68, 68, 0.2) !important;
  }

  .status-processing {
    background: rgba(59, 130, 246, 0.1) !important;
    color: #2563eb !important;
    border: 1px solid rgba(59, 130, 246, 0.2) !important;
  }

  .status-refunded {
    background: rgba(168, 85, 247, 0.1) !important;
    color: #9333ea !important;
    border: 1px solid rgba(168, 85, 247, 0.2) !important;
  }

  /* Table Styling */
  .table thead th {
    background: #c6d2ff !important;
    color: #1e40af !important;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.05em;
    border: none;
    padding: 1rem 1.5rem;
  }

  .table tbody td {
    padding: 1rem 1.5rem;
    vertical-align: middle;
    border-color: #f1f5f9;
    color: #475569;
  }

  .table tbody tr:hover {
    background-color: rgba(198, 210, 255, 0.05) !important;
  }

  .table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(248, 250, 252, 0.5);
  }

  /* Amount Colors */
  .text-credit {
    color: #16a34a !important;
  }

  .text-debit {
    color: #dc2626 !important;
  }

  .text-warning {
    color: #d97706 !important;
  }

  /* Card Background Colors */
  .bg-credit {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(34, 197, 94, 0.05)) !important;
    border: 1px solid rgba(34, 197, 94, 0.2) !important;
  }

  .bg-payment {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.05)) !important;
    border: 1px solid rgba(245, 158, 11, 0.2) !important;
  }

  .bg-commission {
    background: linear-gradient(135deg, rgba(100, 116, 139, 0.1), rgba(100, 116, 139, 0.05)) !important;
    border: 1px solid rgba(100, 116, 139, 0.2) !important;
  }

  .bg-refund {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05)) !important;
    border: 1px solid rgba(239, 68, 68, 0.2) !important;
  }

  .bg-balance {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.05)) !important;
    border: 1px solid rgba(59, 130, 246, 0.2) !important;
  }

  /* Modern Header */
  .modern-header {
    background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
    color: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
  }

  .modern-header::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 200px;
    height: 200px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    transform: translate(30%, -30%);
  }

  .modern-header h1 {
    font-weight: 700;
    margin-bottom: 0.5rem;
    position: relative;
    z-index: 1;
  }

  .modern-header p {
    opacity: 0.9;
    font-size: 1.1rem;
    position: relative;
    z-index: 1;
  }

  /* Responsive Design */
  @media (max-width: 768px) {
    .modern-header {
      padding: 1.5rem;
    }

    .modern-header h1 {
      font-size: 1.5rem;
    }

    .modern-header p {
      font-size: 1rem;
    }

    .summary-card .card-body {
      padding: 1rem;
    }

    .table thead th,
    .table tbody td {
      padding: 0.75rem;
      font-size: 0.9rem;
    }

    .summary-cards .col-sm-7 {
      flex: 0 0 auto;
      width: 100%;
    }
  }

  @media (max-width: 576px) {
    .modern-header {
      text-align: center;
    }

    .status-badge {
      font-size: 0.75rem;
      padding: 0.25rem 0.5rem;
    }

    .table-responsive {
      font-size: 0.9rem;
    }
  }

  /* Animation for table rows */
  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .table tbody tr {
    animation: fadeIn 0.3s ease-out;
  }

  /* Loading state */
  .loading-shimmer {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
  }

  @keyframes shimmer {
    0% {
      background-position: -200% 0;
    }

    100% {
      background-position: 200% 0;
    }
  }
</style>

<script>
  $(document).ready(function() {
        var bookingId = $('#booking_id').val();
        var serviceId = $('#service_id').val();
        var providerId = $('#provider_id').val();

        // Show loading state
        $('#loadingState').removeClass('d-none');

        $.ajax({
          url: '<?php echo base_url('getBookingDetails') ?>',
          type: 'POST',
          data: {
            booking_id: bookingId,
            service_id: serviceId,
            provider_id: providerId
          },
          dataType: 'json',
          success: function(response) {
            $('#loadingState').addClass('d-none');
            processBookingDetails(response, serviceId);
          },
          error: function(xhr, status, error) {
            $('#loadingState').addClass('d-none');
            console.error(error);
            showError('Error fetching booking details');
          }
        });

        function processBookingDetails(response, serviceId) {
          // Clear previous data & state
          $('#trackingTable tbody').empty();
          $('#emptyState').addClass('d-none');

          let data = response.data || {};
          let trackings = Array.isArray(data.trackings) ? data.trackings : [];
          let financials = data.financials || null;
          let walletDetails = data.walletDetails || null;

          // Provider Label Mapping
          const providerLabels = {
            '1': 'Boarding Host',
            '2': 'Groomer',
            '3': 'Trainer',
            '4': 'Walker',
            '5': 'Veterinarian',
            '6': 'Sitter',
            '7': 'Daycare Host'
          };
          let providerLabel = providerLabels[serviceId] || 'Provider';
          $('.card.bg-payment h6').text(providerLabel + ' Payment');

          // -------------------------------------------------------------
          // 1. ALWAYS UPDATE FINANCIAL SUMMARY CARDS FIRST
          // -------------------------------------------------------------
          let creditAmt = 0;
          let providerAmt = 0;
          let commAmt = 0;
          let refundAmt = 0;
          let balanceAmt = 0;

          if (financials) {
            creditAmt    = parseFloat(financials.credit_amount) || 0;
            providerAmt  = parseFloat(financials.provider_payment) || 0;
            commAmt      = parseFloat(financials.platform_commission) || 0;
            refundAmt    = parseFloat(financials.client_refund) || 0;
            balanceAmt   = parseFloat(financials.balance_amount) || Math.max(0, creditAmt - refundAmt);
          } else if (walletDetails && walletDetails.booking) {
            let b = walletDetails.booking;
            creditAmt   = parseFloat(b.bid_amount || b.total_price || b.final_payable || data.service_amount || 0);
            commAmt     = parseFloat(b.platform_charges || (creditAmt * 0.10));
            providerAmt = parseFloat(b.receivable_amount || (creditAmt - commAmt));
            refundAmt   = 0;
            balanceAmt  = creditAmt;
          } else if (data.service_amount) {
            creditAmt   = parseFloat(data.service_amount) || 0;
            commAmt     = creditAmt * 0.10;
            providerAmt = creditAmt - commAmt;
            balanceAmt  = creditAmt;
          }

          $('#creditAmount').text('₹' + creditAmt.toFixed(2));
          $('#providerPayment').text('₹' + providerAmt.toFixed(2));
          $('#platformCommission').text('₹' + commAmt.toFixed(2));
          $('#clientRefund').text('₹' + refundAmt.toFixed(2));
          $('#balanceAmount').text('₹' + balanceAmt.toFixed(2));

          // -------------------------------------------------------------
          // 2. SET TABLE HEADERS DEPENDING ON SERVICE
          // -------------------------------------------------------------
          if (serviceId == '4' || serviceId == '6') { // Walking / Sitting
            $('#trackingDetailsHeader').html(`
              <tr>
                <th class="py-3 text-center">Pet Name</th>
                <th class="py-3 text-center">Breed</th>
                <th class="py-3 text-center">Date</th>
                <th class="py-3 text-center">Service Time</th>
                <th class="py-3 text-center">Start Time</th>
                <th class="py-3 text-center">End Time</th>
                <th class="py-3 text-center">Status</th>
                <th class="py-3 text-center">Approval</th>
                <th class="py-3 text-center">Payment Status</th>
                <th class="py-3 text-center">Debit</th>
                <th class="py-3 text-center">Commission</th>
                <th class="py-3 text-center">Running Balance</th>
              </tr>
            `);
          } else if (serviceId == '2' || serviceId == '3') { // Grooming / Training
            $('#trackingDetailsHeader').html(`
              <tr>
                <th class="py-3 text-center">Pet Name</th>
                <th class="py-3 text-center">Breed</th>
                <th class="py-3 text-center">Addon / Command</th>
                <th class="py-3 text-center">Date</th>
                <th class="py-3 text-center">Status</th>
                <th class="py-3 text-center">Approved At</th>
                <th class="py-3 text-center">Payment Status</th>
                <th class="py-3 text-center">Debit</th>
                <th class="py-3 text-center">Commission</th>
                <th class="py-3 text-center">Running Balance</th>
              </tr>
            `);
          } else { // Boarding / Vet / Daycare / Others
            $('#trackingDetailsHeader').html(`
              <tr>
                <th class="py-3 text-center">Pet Name</th>
                <th class="py-3 text-center">Breed</th>
                <th class="py-3 text-center">Service Time</th>
                <th class="py-3 text-center">Start Time</th>
                <th class="py-3 text-center">End Time</th>
                <th class="py-3 text-center">Status</th>
                <th class="py-3 text-center">Approval</th>
                <th class="py-3 text-center">Payment Status</th>
              </tr>
            `);
          }

          // -------------------------------------------------------------
          // 3. RENDER TABLE ROWS OR SHOW EMPTY STATE
          // -------------------------------------------------------------
          if (trackings.length === 0) {
            $('#emptyState').removeClass('d-none');
            return;
          }

          let runningCredit = creditAmt;

          $.each(trackings, function(index, tracking) {
            let petName   = tracking.pet_name || 'N/A';
            let petBreed  = tracking.pet_breed || 'N/A';
            let dateStr   = tracking.tracking_date || 'N/A';
            let sTime     = tracking.service_time || 'N/A';
            let startTime = tracking.start_time ? tracking.start_time.split(' ')[1] + ' ' + (tracking.start_time.split(' ')[2] || '') : 'N/A';
            let endTime   = tracking.end_time ? tracking.end_time.split(' ')[1] + ' ' + (tracking.end_time.split(' ')[2] || '') : 'N/A';

            let rawStatus = tracking.status ? tracking.status.toString() : 'Pending';
            let displayStatus = rawStatus.charAt(0).toUpperCase() + rawStatus.slice(1);

            // Determine Approval Status & Class
            let approvalStatus = 'Pending';
            let isApp = tracking.is_approved;

            if (tracking.status === 'Untracked' && tracking.request_reason) {
              if (tracking.request_status === 'approved') approvalStatus = 'Approved (Manual)';
              else if (tracking.request_status === 'rejected') approvalStatus = 'Rejected (Manual)';
              else approvalStatus = 'Pending Approval';
            } else if (isApp === true || isApp === "true" || isApp === 1 || tracking.approved_at) {
              approvalStatus = 'Approved';
            } else if (isApp === false || isApp === "false" || isApp === 0 || rawStatus.toLowerCase() === 'rejected') {
              approvalStatus = 'Rejected';
            } else if (rawStatus.toLowerCase() === 'completed') {
              approvalStatus = 'Approved';
            }

            let statusClass = getStatusClass(approvalStatus);

            // Determine Payment Status Label & Badge Class
            let paymentStatusLabel = formatPaymentStatusText(tracking.payment_status || (approvalStatus === 'Approved' ? 'Paid' : 'Pending'));
            let paymentStatusClass = getPaymentStatusClass(paymentStatusLabel);

            let debit = parseFloat(tracking.clnetAmount || tracking.addon_price || tracking.service_amount || 0);
            let comm  = parseFloat(tracking.platformCharges || (debit * 0.10));

            // Subtract debit from Running Balance if session is approved or paid
            if (paymentStatusLabel === 'Paid' || approvalStatus === 'Approved' || approvalStatus === 'Approved (Manual)') {
              runningCredit = Math.max(0, runningCredit - debit);
            }

            if (serviceId == '4' || serviceId == '6') {
              $('#trackingTable tbody').append(`
                <tr>
                  <td class="text-center text-dark">${petName}</td>
                  <td class="text-center text-dark">${petBreed}</td>
                  <td class="text-center text-dark">${dateStr}</td>
                  <td class="text-center text-dark">${sTime}</td>
                  <td class="text-center text-dark">${startTime}</td>
                  <td class="text-center text-dark">${endTime}</td>
                  <td class="text-center"><span class="status-badge ${statusClass}">${displayStatus}</span></td>
                  <td class="text-center">
                    <span class="status-badge ${statusClass}">${approvalStatus}</span>
                    ${tracking.request_reason ? `<div class="small text-muted mt-1" style="font-size:0.75rem;">Reason: ${tracking.request_reason}</div>` : ''}
                  </td>
                  <td class="text-center"><span class="status-badge ${paymentStatusClass}">${paymentStatusLabel}</span></td>
                  <td class="text-center fw-bold text-debit">${debit > 0 ? '-₹' + debit.toFixed(2) : '₹0.00'}</td>
                  <td class="text-center fw-bold text-secondary">${comm > 0 ? '-₹' + comm.toFixed(2) : '₹0.00'}</td>
                  <td class="text-center fw-bold text-primary">₹${runningCredit.toFixed(2)}</td>
                </tr>
              `);
            } else if (serviceId == '2' || serviceId == '3') {
              let addonName = tracking.addon || tracking.package_name || 'N/A';
              let approvedAt = tracking.approved_at || (approvalStatus === 'Approved' ? dateStr : 'N/A');

              $('#trackingTable tbody').append(`
                <tr>
                  <td class="text-center text-dark">${petName}</td>
                  <td class="text-center text-dark">${petBreed}</td>
                  <td class="text-center text-dark fw-semibold">${addonName}</td>
                  <td class="text-center text-dark">${dateStr}</td>
                  <td class="text-center"><span class="status-badge ${statusClass}">${displayStatus}</span></td>
                  <td class="text-center text-dark">${approvedAt}</td>
                  <td class="text-center"><span class="status-badge ${paymentStatusClass}">${paymentStatusLabel}</span></td>
                  <td class="text-center fw-bold text-debit">${debit > 0 ? '-₹' + debit.toFixed(2) : '₹0.00'}</td>
                  <td class="text-center fw-bold text-secondary">${comm > 0 ? '-₹' + comm.toFixed(2) : '₹0.00'}</td>
                  <td class="text-center fw-bold text-primary">₹${runningCredit.toFixed(2)}</td>
                </tr>
              `);
            } else {
              $('#trackingTable tbody').append(`
                <tr>
                  <td class="text-center text-dark">${petName}</td>
                  <td class="text-center text-dark">${petBreed}</td>
                  <td class="text-center text-dark">${sTime}</td>
                  <td class="text-center text-dark">${startTime}</td>
                  <td class="text-center text-dark">${endTime}</td>
                  <td class="text-center"><span class="status-badge ${statusClass}">${displayStatus}</span></td>
                  <td class="text-center"><span class="status-badge ${statusClass}">${approvalStatus}</span></td>
                  <td class="text-center"><span class="status-badge ${paymentStatusClass}">${paymentStatusLabel}</span></td>
                </tr>
              `);
            }
          });
        }

        // Helper function to get status badge class
        function getStatusClass(status) {
          if (!status) return 'status-pending';
          let s = status.toString().toLowerCase();
          if (s.includes('approved') || s.includes('completed') || s.includes('paid')) return 'status-approved';
          if (s.includes('pending') || s.includes('processing') || s.includes('untracked')) return 'status-pending';
          if (s.includes('rejected') || s.includes('cancelled') || s.includes('refunded')) return 'status-rejected';
          return 'status-pending';
        }

        // Helper function to get payment status badge class
        function getPaymentStatusClass(status) {
          if (!status) return 'status-pending';
          let s = status.toString().toLowerCase();
          if (s === 'paid' || s === 'completed' || s === 'approved' || s === '1') return 'status-approved';
          if (s === 'processing' || s === 'pending' || s === '0' || s === 'in_progress') return 'status-processing';
          if (s === 'refunded' || s === 'rejected' || s === 'cancelled') return 'status-rejected';
          return 'status-pending';
        }

        // Helper function to format human-readable payment status text
        function formatPaymentStatusText(status) {
          if (!status && status !== 0) return 'Pending';
          let s = status.toString().toLowerCase();
          if (s === '1' || s === 'paid' || s === 'completed' || s === 'approved') return 'Paid';
          if (s === '0' || s === 'pending') return 'Pending';
          if (s === 'processing' || s === 'in_progress') return 'Processing';
          if (s === 'refunded') return 'Refunded';
          if (s === 'rejected' || s === 'cancelled') return 'Rejected';
          return status.charAt(0).toUpperCase() + status.slice(1);
        }

        // Show error message
        function showError(message) {
          const errorAlert = `
            <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3" role="alert">
              <i class="bi bi-exclamation-triangle me-2"></i>
              ${message}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          `;
          $('#trackingTable').before(errorAlert);
        }
  });
</script>

<?php echo $this->endSection() ?>