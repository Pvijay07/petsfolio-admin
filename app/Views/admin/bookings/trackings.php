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
  $(document).ready(function () {
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
      success: function (response) {
        $('#loadingState').addClass('d-none');
        processBookingDetails(response, serviceId);
      },
      error: function (xhr, status, error) {
        $('#loadingState').addClass('d-none');
        console.error(error);
        showError('Error fetching booking details');
      }
    });

    function processBookingDetails(response, serviceId) {
      // Clear previous data
      $('#trackingTable tbody').empty();

      // Hide empty state initially
      $('#emptyState').addClass('d-none');

      // Populate tracking details
      if (response.data.trackings && response.data.trackings.length > 0) {
        console.log("Trackings", response.data.trackings)
        // Set headers based on service type
        if (serviceId == '4') { // Walking service
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
                            <th class="py-3 text-center">Wallet Balance</th>
                        </tr>
                    `);
        } else if (serviceId == '3') {
          $('#trackingDetailsHeader').html(`
                        <tr>
                            <th class="py-3 text-center">Pet Name</th>
                            <th class="py-3 text-center">Breed</th>
                            <th class="py-3 text-center">Addon</th>
                            <th class="py-3 text-center">Date</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3 text-center">Approval</th>
                            <th class="py-3 text-center">Approved At</th>
                            <th class="py-3 text-center">Payment Status</th>
                            <th class="py-3 text-center">Debit</th>
                            <th class="py-3 text-center">Commission</th>
                            <th class="py-3 text-center">Wallet Balance</th>
                        </tr>
                    `);
        } else if (serviceId == '2') { // Grooming service
          $('#trackingDetailsHeader').html(`
                        <tr>
                            <th class="py-3 text-center">Pet Name</th>
                            <th class="py-3 text-center">Breed</th>
                            <th class="py-3 text-center">Addon</th>
                            <th class="py-3 text-center">Date</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3 text-center">Approval</th>
                            <th class="py-3 text-center">Approved At</th>
                            <th class="py-3 text-center">Payment Status</th>
                            <th class="py-3 text-center">Debit</th>
                            <th class="py-3 text-center">Commission</th>
                            <th class="py-3 text-center">Wallet Balance</th>
                        </tr>
                    `);
        } else if (serviceId == '1') { // Boarding service
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

        var creditAmount = parseFloat(response.data.service_amount) || 0;
        var totalSpAmount = 0;
        var totalCommission = 0;
        var totalRefund = 0;

        $.each(response.data.trackings, function (index, tracking) {
          if (serviceId == '4') {
            var payment_status = '';
            var debitAmount = 0;
            var commissionAmount = 0;
            var walletAmount = tracking.service_amount;

            // Determine payment status and amounts
            if (tracking.status == 'Untracked') {
              payment_status = 'Refunded';
              debitAmount = parseFloat(tracking.clnetAmount) || 0;
              commissionAmount = 0;
              totalRefund += debitAmount;
              creditAmount += debitAmount;
            } else if (tracking.status == 'completed' && tracking.is_approved == null) {
              payment_status = 'Processing';
              debitAmount = 0;
              commissionAmount = 0;
            } else if (tracking.status == 'completed' && (tracking.is_approved == "false" || tracking.is_approved == null)) {
              payment_status = 'Rejected';
            } else if (tracking.status == 'in_progress' && tracking.is_approved == null) {
              payment_status = 'Pending';
            } else if (tracking.status == 'rejected') {
              payment_status = 'Refunded';
              debitAmount = parseFloat(tracking.clnetAmount) || 0;
              commissionAmount = 0;
              totalRefund += debitAmount;
              creditAmount += debitAmount;
            } else {
              payment_status = tracking.payment_status;
              debitAmount = parseFloat(tracking.clnetAmount) || 0;
              commissionAmount = parseFloat(tracking.platformCharges) || 0;
              walletAmount = parseFloat(tracking.service_amount) || 0;
              totalSpAmount += debitAmount;
              totalCommission += commissionAmount;
              creditAmount += debitAmount;
            }

            // Determine approval status
            var approvalStatus = '';
            if ((tracking.status == 'completed' || tracking.status == 'in_progress') && tracking.is_approved == null) {
              approvalStatus = 'Pending';
            } else if (tracking.is_approved == true || tracking.is_approved == "true") {
              approvalStatus = 'Approved';
            } else if (tracking.is_approved == false || tracking.is_approved == "false") {
              approvalStatus = 'Rejected';
            } else if (tracking.status == 'rejected') {
              approvalStatus = 'Rejected';
            } else {
              approvalStatus = 'NO WALK';
            }

            console.log("Refunded", payment_status)

            // Get status badge class
            var statusClass = getStatusClass(approvalStatus);
            var paymentStatusClass = getPaymentStatusClass(payment_status);

            $('#trackingTable tbody').append(`
                            <tr>
                                <td class="text-center text-dark">${tracking.pet_name || 'N/A'}</td>
                                <td class="text-center text-dark">${tracking.pet_breed || 'N/A'}</td>
                                <td class="text-center text-dark">${tracking.tracking_date || 'N/A'}</td>
                                <td class="text-center text-dark">${tracking.service_time || 'N/A'}</td>
                                <td class="text-center text-dark">${tracking.start_time ? tracking.start_time.split(' ')[1] : 'N/A'}</td>
                                <td class="text-center text-dark">${tracking.end_time ? tracking.end_time.split(' ')[1] : 'N/A'}</td>
                                <td class="text-center">
                                    <span class="status-badge ${statusClass}">
                                        ${tracking.status ? tracking.status.charAt(0).toUpperCase() + tracking.status.slice(1) : 'N/A'}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="status-badge ${statusClass}">
                                        ${approvalStatus}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="status-badge ${paymentStatusClass}">
                                        ${payment_status}
                                    </span>
                                </td>
                                <td class="text-center fw-bold text-debit">
                                    ${debitAmount > 0 ? '-₹' + debitAmount.toFixed(2) : '₹0.00'}
                                </td>
                                <td class="text-center fw-bold text-secondary">
                                    ${commissionAmount > 0 ? '-₹' + commissionAmount.toFixed(2) : '₹0.00'}
                                </td>
                                <td class="text-center fw-bold text-primary">
                                    ₹${creditAmount.toFixed(2)}
                                </td>
                            </tr>
                        `);

          } else if (serviceId == '3' || serviceId == '2') {
            var approvalStatus = tracking.approved_at ? 'Approved' : 'Pending';
            var statusClass = approvalStatus === 'Approved' ? 'status-approved' : 'status-pending';
            var paymentStatus = tracking.payment_status === '1' ? 'Paid' :
              tracking.payment_status === '0' ? 'Pending' : 'Processing';

            var addon_price = 0;
            var commissionAmount = 0;

            if (approvalStatus === 'Approved') {
              addon_price = parseFloat(tracking.addon_price) || 0;
              commissionAmount = parseFloat(tracking.platformCharges) || (addon_price * 0.10);
              creditAmount += addon_price;
              totalSpAmount += addon_price;
              totalCommission += commissionAmount;
            }
            console.log("tracking.payment_status", typeof tracking.payment_status)

            var paymentStatusClass = tracking.payment_status === '1' ? 'status-approved' :
              tracking.payment_status === '0' ? 'status-pending' : 'status-processing';

            $('#trackingTable tbody').append(`
                            <tr>
                                <td class="text-center text-dark">${tracking.pet_name || 'N/A'}</td>
                                <td class="text-center text-dark">${tracking.pet_breed || 'N/A'}</td>
                                <td class="text-center text-dark">${tracking.addon || 'N/A'}</td>
                                <td class="text-center text-dark">${tracking.tracking_date || 'N/A'}</td>
                                <td class="text-center">
                                    <span class="status-badge ${statusClass}">
                                        ${tracking.status ? tracking.status.charAt(0).toUpperCase() + tracking.status.slice(1) : 'N/A'}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="status-badge ${paymentStatusClass}">
                                        ${tracking.is_approved === '1' ? 'Approved' : tracking.is_approved === '0' ? 'Rejected' : 'Rejected' || 'N/A'}
                                    </span>
                                </td>
                                <td class="text-center text-dark">${tracking.approved_at || 'N/A'}</td>
                                
                                <td class="text-center">
                                    <span class="status-badge ${paymentStatusClass}">
                                        ${tracking.payment_status === '1' ? 'Paid' : tracking.payment_status === '0' ? 'Processing' : 'Processing' || 'N/A'}
                                    </span>
                                </td>
                                <td class="text-center fw-bold text-debit">
                                    ${addon_price > 0 ? '-₹' + addon_price.toFixed(2) : '₹0.00'}
                                </td>
                                <td class="text-center fw-bold text-secondary">
                                    ${commissionAmount > 0 ? '-₹' + commissionAmount.toFixed(2) : '₹0.00'}
                                </td>
                                <td class="text-center fw-bold text-primary">
                                    ₹${creditAmount.toFixed(2)}
                                </td>
                            </tr>
                        `);

          } else if (serviceId == '1') {
            // Boarding service logic
            var approvalStatus = '';
            if ((tracking.status == 'completed' || tracking.status == 'in_progress') && tracking.is_approved == null) {
              approvalStatus = 'Pending';
            } else if (tracking.is_approved == true || tracking.is_approved == "true") {
              approvalStatus = 'Approved';
            } else if (tracking.is_approved == false || tracking.is_approved == "false") {
              approvalStatus = 'Rejected';
            } else {
              approvalStatus = 'N/A';
            }

            var statusClass = getStatusClass(approvalStatus);
            var paymentStatusClass = getPaymentStatusClass(tracking.payment_status);

            $('#trackingTable tbody').append(`
                            <tr>
                                <td class="text-center text-dark">${tracking.pet_name || 'N/A'}</td>
                                <td class="text-center text-dark">${tracking.pet_breed || 'N/A'}</td>
                                <td class="text-center text-dark">${tracking.service_time || 'N/A'}</td>
                                <td class="text-center text-dark">${tracking.start_time ? tracking.start_time.split(' ')[1] : 'N/A'}</td>
                                <td class="text-center text-dark">${tracking.end_time ? tracking.end_time.split(' ')[1] : 'N/A'}</td>
                                <td class="text-center">
                                    <span class="status-badge ${statusClass}">
                                        ${tracking.status ? tracking.status.charAt(0).toUpperCase() + tracking.status.slice(1) : 'N/A'}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="status-badge ${statusClass}">
                                        ${approvalStatus}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="status-badge ${paymentStatusClass}">
                                        ${tracking.payment_status || 'N/A'}
                                    </span>
                                </td>
                            </tr>
                        `);
          }
        });

      } else {
        // Show empty state
        $('#emptyState').removeClass('d-none');
        $('#trackingDetailsHeader').html(`
                    <tr>
                        <th colspan="10" class="py-4 text-center text-muted">No tracking details found</th>
                    </tr>
                `);
      }

      // Update summary cards with wallet details
      var service_amount = 0;
      var service_name = 'Service';
      var provider = 'Provider';
      var netSpAmount = 0;

      if (response.data.walletDetails) {
        const walletDetails = response.data.walletDetails;
        const booking = walletDetails.booking;

        if (serviceId == '4') {
          service_amount = parseFloat(booking.walking_amount || 0);
          service_name = booking.service_name || 'Walking service';
          provider = 'Walker';
        } else if (serviceId == '3') {
          service_amount = parseFloat(booking.training_amount || 0);
          service_name = booking.service_name || 'Training service';
          provider = 'Trainer';
          // Use bid_amount if available for creditAmount starting point
          creditAmount = parseFloat(booking.bid_amount || creditAmount);
        } else if (serviceId == '2') {
          service_amount = parseFloat(booking.grooming_amount || 0);
          service_name = booking.service_name || 'Grooming service';
          provider = 'Groomer';
        } else if (serviceId == '1') {
          service_amount = parseFloat(booking.boarding_amount || 0);
          service_name = booking.service_name || 'Boarding service';
          provider = 'Boarding';
        }
      } else {
        // Fallback defaults if walletDetails is null
        if (serviceId == '4') provider = 'Walker';
        else if (serviceId == '3') provider = 'Trainer';
        else if (serviceId == '2') provider = 'Groomer';
        else if (serviceId == '1') provider = 'Boarding';
        service_amount = parseFloat(response.data.service_amount || 0);
      }

      // Final summary updates
      netSpAmount = totalSpAmount - totalCommission;
      $('#creditAmount').text('₹' + creditAmount.toFixed(2));
      $('#providerPayment').text('₹' + netSpAmount.toFixed(2));
      $('#platformCommission').text('₹' + totalCommission.toFixed(2));
      $('#clientRefund').text('₹' + totalRefund.toFixed(2));

      var balanceAmount = response.data.service_amount || service_amount;
      $('#balanceAmount').text('₹' + parseFloat(balanceAmount).toFixed(2));

      // Update payment card title
      $('.card.bg-payment h6').text(provider + ' Payment');
    }

    // Helper function to get status badge class
    function getStatusClass(status) {
      switch (status.toLowerCase()) {
        case 'approved':
          return 'status-approved';
        case 'pending':
          return 'status-pending';
        case 'rejected':
          return 'status-rejected';
        case 'processing':
          return 'status-processing';
        case 'refunded':
          return 'status-refunded';
        default:
          return 'status-pending';
      }
    }

    // Helper function to get payment status class
    function getPaymentStatusClass(status) {
      switch (status.toLowerCase()) {
        case 'paid':
        case 'completed':
          return 'status-approved';
        case 'pending':
        case 'processing':
          return 'status-processing';
        case 'rejected':
        case 'refunded':
          return 'status-rejected';
        default:
          return 'status-pending';
      }
    }

    // Show error message
    function showError(message) {
      // Create error alert
      const errorAlert = `
                <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;

      // Add error alert before the tracking table
      $('#trackingTable').before(errorAlert);
    }
  });
</script>

<?php echo $this->endSection() ?>