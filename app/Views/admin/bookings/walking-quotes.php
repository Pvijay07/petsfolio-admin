<?= $this->extend('admin/includes/header'); ?>
<!--start content-->

<?= $this->section('content'); ?>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?= $this->include('admin/nav_bar') ?>
  </div>
  <div class="conatiner-fluid content-inner pb-0 px-4" id="page_layout">
    <!-- Modern Header Section -->
    <div class="container-fluid p-0 mb-4">
      <div class="p-4 p-md-5 rounded-4 shadow-lg position-relative overflow-hidden text-white"
        style="background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 25%, #3730a3 60%, #4f46e5 100%);">
        
        <!-- Subtle glass background circles -->
        <div class="position-absolute" style="top: -60px; right: -60px; width: 260px; height: 260px; border-radius: 50%; background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0) 70%); pointer-events: none;"></div>
        <div class="position-absolute" style="bottom: -50px; left: 30%; width: 200px; height: 200px; border-radius: 50%; background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, rgba(255,255,255,0) 70%); pointer-events: none;"></div>
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 position-relative z-1">
          <div>
            <div class="d-flex align-items-center gap-2 mb-2.5">
              <span class="badge rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-2"
                style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); color: #a7f3d0; border: 1px solid rgba(255, 255, 255, 0.2); font-size: 0.8rem;">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: #10b981; display: inline-block; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.35);"></span>
                • Live Quotations Hub
              </span>
              <span class="badge rounded-pill px-2.5 py-1 text-white-50 tiny font-monospace" style="background: rgba(0,0,0,0.25);">
                ID: #<?= $booking_id ?? 'N/A' ?>
              </span>
            </div>
            <h1 class="h2 fw-bold text-white mb-2 tracking-tight">Service Provider Quotes</h1>
            <p class="text-white-50 mb-0 fs-6">
              Compare bids, check provider distance, chat in real-time, and assign specialist for this booking.
            </p>
          </div>
          
          <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="<?= base_url('bookingRequests') ?>"
              class="btn btn-outline-light rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2"
              style="backdrop-filter: blur(10px); border-color: rgba(255,255,255,0.3);">
              <i class="bi bi-arrow-left"></i> Back to Requests
            </a>
            <a href="<?= base_url() ?>downloadQuotes?id=<?= $booking_id ?>"
              class="btn btn-warning rounded-pill px-4 py-2 fw-bold shadow-sm d-inline-flex align-items-center gap-2 text-dark"
              style="background: #f59e0b; border: none;">
              <i class="bi bi-download"></i> Download CSV
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Customer Info Card -->
    <div class="card border-0 shadow-sm mb-4 rounded-4 bg-white overflow-hidden">
      <div class="card-body px-4 py-3.5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
          <div class="d-flex align-items-center gap-3.5">
            <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center shadow-xs flex-shrink-0"
              style="width: 56px; height: 56px; font-size: 1.4rem; background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);">
              <?= strtoupper(substr(!empty($quotations) ? ($quotations[0]->user_name ?? '?') : '?', 0, 1)) ?>
            </div>
            <div>
              <div class="d-flex align-items-center gap-2 mb-0.5">
                <h4 class="fw-bold text-dark mb-0 fs-5"><?= !empty($quotations) ? ($quotations[0]->user_name ?? 'Customer') : 'Customer' ?></h4>
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2.5 py-0.5 tiny fw-semibold">
                  <i class="bi bi-check-circle-fill me-1"></i>Client
                </span>
              </div>
              <div class="d-flex flex-wrap align-items-center gap-3 text-muted small mt-1">
                <span><i class="bi bi-telephone me-1 text-secondary"></i><?= !empty($quotations) ? ($quotations[0]->user_phone ?? '--') : '--' ?></span>
                <span class="opacity-40">•</span>
                <span><i class="bi bi-calendar-check me-1 text-secondary"></i>Booking ID: <strong class="text-dark font-monospace">#<?= $booking_id ?? '--' ?></strong></span>
                <span class="opacity-40">•</span>
                <span><i class="bi bi-people me-1 text-primary"></i>Total Quotes: <strong class="text-primary"><?= count($quotations ?? []) ?></strong></span>
              </div>
            </div>
          </div>
          <div class="d-flex gap-2">
            <?php if (!empty($quotations) && !empty($quotations[0]->user_phone)): ?>
            <a href="tel:<?= $quotations[0]->user_phone ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5">
              <i class="bi bi-telephone-fill"></i> Call Client
            </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Quotations Table Card -->
    <div class="card border-0 shadow-sm overflow-hidden rounded-4 bg-white">
      <div class="card-body p-0">
        <div class="table-responsive">
          <div id="chat-loader" style="display: none; text-align:center; margin:20px 0;" class="text-center">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading chat messages...</p>
          </div>
          <table class="table table-hover align-middle mb-0" id="quotationsTable">
            <thead style="background: #f8fafc !important; border-bottom: 2px solid #e2e8f0;">
              <tr>
                <th class="py-3 px-3 text-center fw-bold" style="background: #f8fafc !important; color: #475569 !important; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid #e2e8f0;">#</th>
                <th class="py-3 px-3 fw-bold" style="background: #f8fafc !important; color: #475569 !important; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid #e2e8f0;">Provider Details</th>
                <th class="py-3 px-3 text-center fw-bold" style="background: #f8fafc !important; color: #475569 !important; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid #e2e8f0;">Bid Amount</th>
                <th class="py-3 px-3 text-center fw-bold" style="background: #f8fafc !important; color: #475569 !important; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid #e2e8f0;">Timings</th>
                <th class="py-3 px-3 text-center fw-bold" style="background: #f8fafc !important; color: #475569 !important; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid #e2e8f0;">Addon Price</th>
                <th class="py-3 px-3 text-center fw-bold" style="background: #f8fafc !important; color: #475569 !important; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid #e2e8f0;">Discount</th>
                <th class="py-3 px-3 text-center fw-bold" style="background: #f8fafc !important; color: #475569 !important; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid #e2e8f0;">Distance</th>
                <th class="py-3 px-3 text-center fw-bold" style="background: #f8fafc !important; color: #475569 !important; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid #e2e8f0;">Created At</th>
                <th class="py-3 px-3 text-center fw-bold" style="background: #f8fafc !important; color: #475569 !important; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid #e2e8f0;">Live Chat</th>
                <th class="py-3 px-3 text-center fw-bold" style="background: #f8fafc !important; color: #475569 !important; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid #e2e8f0;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 1;
              foreach ($quotations as $quotation) {
                if (strtolower(trim($quotation->status)) == 'accepted') {
                  continue;
                }
                $distance = (float)($quotation->distance ?? 0);
                $distanceBadgeClass = '';
                if ($distance <= 2) {
                  $distanceBadgeClass = 'bg-success bg-opacity-10 text-success border-success border-opacity-25';
                } elseif ($distance <= 5) {
                  $distanceBadgeClass = 'bg-warning bg-opacity-10 text-warning border-warning border-opacity-25';
                } else {
                  $distanceBadgeClass = 'bg-secondary bg-opacity-10 text-secondary border-secondary border-opacity-25';
                }

                $time = $quotation->sp_timings ?? 'N/A';
              ?>
                <tr>
                  <td class="text-center fw-bold text-dark"><?= $i ?></td>
                  <td class="py-2">
                    <div class="d-flex align-items-center gap-3">
                      <div
                        class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center shadow-xs flex-shrink-0"
                        style="width: 40px; height: 40px; font-size: 0.95rem; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                        <?= strtoupper(substr($quotation->name ?? '?', 0, 1)) ?>
                      </div>
                      <div>
                        <div class="fw-semibold text-dark mb-1 fs-6"><?= htmlspecialchars($quotation->name ?? '--') ?></div>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                          <span class="text-muted small">
                            <i class="bi bi-telephone me-1 text-secondary"></i><?= htmlspecialchars($quotation->phone ?? '--') ?>
                          </span>
                          <span class="badge rounded-pill px-2.5 py-0.5 small" style="background: #eef2ff !important; color: #4338ca !important; border: 1px solid #c7d2fe !important;">
                            <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($quotation->area ?? 'N/A') ?>
                          </span>
                        </div>
                      </div>
                    </div>
                  </td>

                  <!-- Bid Amount Column -->
                  <td class="text-center">
                    <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; font-size: 0.95rem;">
                      ₹<?= number_format($quotation->bid_amount) ?>
                    </span>
                  </td>

                  <!-- Timings Column -->
                  <td class="text-center">
                    <span class="badge rounded-pill px-2.5 py-1 fw-semibold text-dark border" style="background: #fffbeb; color: #b45309 !important; border-color: #fde68a !important; font-size: 0.75rem;">
                      <i class="bi bi-clock me-1 text-warning"></i><?= htmlspecialchars($time) ?>
                    </span>
                  </td>

                  <!-- Addon Price Column -->
                  <td class="text-center">
                    <?php if (($quotation->addonPrice ?? 0) > 0): ?>
                      <span class="text-dark fw-semibold">₹<?= number_format($quotation->addonPrice) ?></span>
                    <?php else: ?>
                      <span class="text-muted small">₹0</span>
                    <?php endif; ?>
                  </td>

                  <!-- Discount Column -->
                  <td class="text-center">
                    <?php if (($quotation->discount_amount ?? 0) > 0): ?>
                      <span class="badge rounded-pill px-2.5 py-1" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca;">
                        -₹<?= number_format($quotation->discount_amount) ?>
                      </span>
                    <?php else: ?>
                      <span class="text-muted small">₹0</span>
                    <?php endif; ?>
                  </td>

                  <!-- Distance Column -->
                  <td class="text-center" data-order="<?= $distance ?>">
                    <span class="badge <?= $distanceBadgeClass ?> rounded-pill px-3 py-1 fw-semibold">
                      <i class="bi bi-signpost-split me-1"></i><?= number_format($distance, 1) ?> km
                    </span>
                  </td>
                  <td class="text-center fw-medium text-dark"><?= date('Y-m-d', strtotime($quotation->created_at)) ?></td>
                  <!-- Chat Column -->
                  <td class="text-center">
                    <?php $chatCount = (int)($quotation->chat_count ?? 0); ?>
                    <a href="#" class="chat-messages text-decoration-none"
                      data-provider="<?= htmlspecialchars(($quotation->name ?? 'Provider') . '_' . $quotation->provider_id) ?>"
                      data-user="<?= htmlspecialchars(($quotation->user_name ?? 'Customer') . '_' . $quotation->user_id) ?>"
                      data-booking-id="<?= htmlspecialchars($quotation->booking_id) ?>"
                      data-row-id="chat-<?= $quotation->id ?>"
                      data-provider-name="<?= htmlspecialchars($quotation->name ?? 'Provider') ?>">
                      <div class="d-flex flex-column align-items-center">
                        <?php if ($chatCount > 0): ?>
                          <span class="chat-link-text text-primary fw-semibold mb-1"><?= $chatCount ?> Messages</span>
                          <div class="d-flex align-items-center">
                            <span class="chat-indicator" style="display:inline; margin-right: 5px;">
                              <i class="bi bi-chat-dots-fill text-success"></i>
                            </span>
                            <span class="chat-count-badge"
                              style="display:inline; background: #28a745; color: white; border-radius: 50%; width: 20px; height: 20px; font-size: 0.7rem; line-height: 20px; text-align: center;">
                              <?= $chatCount ?>
                            </span>
                          </div>
                        <?php else: ?>
                          <span class="chat-link-text text-muted small mb-1">No Chat</span>
                          <div class="d-flex align-items-center">
                            <span class="chat-indicator" style="display:none; margin-right: 5px;">
                              <i class="bi bi-chat-dots-fill text-success"></i>
                            </span>
                            <span class="chat-count-badge"
                              style="display:none; background: #28a745; color: white; border-radius: 50%; width: 20px; height: 20px; font-size: 0.7rem; line-height: 20px; text-align: center;">
                            </span>
                          </div>
                        <?php endif; ?>
                      </div>
                    </a>
                  </td>
                  <!-- Action Column -->
                  <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger delete-quotation-btn rounded-pill px-3 py-1"
                      data-id="<?= $quotation->id ?>"
                      data-provider-name="<?= htmlspecialchars($quotation->name ?? 'Provider') ?>"
                      title="Delete Quotation">
                      <i class="bi bi-trash me-1"></i>Delete
                    </button>
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
</main>

<!-- Chat Modal -->
<div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden p-0">
      <div class="modal-header px-4 py-3 border-0 text-white" style="background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 25%, #3730a3 60%, #4f46e5 100%) !important;">
        <div class="d-flex align-items-center gap-2.5">
          <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(255, 255, 255, 0.15);">
            <i class="bi bi-chat-dots-fill text-warning fs-5"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold text-white mb-0" id="chatModalLabel">Chat Conversation</h5>
            <span class="text-white-50 tiny">Direct communication between client and provider</span>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white opacity-75 hover-opacity-100" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <div id="messages-list" style="height: 420px; overflow-y: auto; padding: 20px; background: #f8fafc;">
          <!-- Messages will be loaded here -->
        </div>
      </div>
      <div class="modal-footer border-top bg-light px-4 py-2.5">
        <button type="button" class="btn btn-sm btn-dark rounded-pill px-4 fw-semibold" data-bs-dismiss="modal">
          <i class="bi bi-x-circle me-1"></i>Close
        </button>
      </div>
    </div>
  </div>
</div>

<style>
  .chat-messages:hover {
    text-decoration: none !important;
  }

  .chat-count-badge {
    animation: pulse 2s infinite;
  }

  @keyframes pulse {
    0% {
      box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4);
    }

    70% {
      box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
    }

    100% {
      box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
    }
  }
</style>

<style>
  /* Table Styling */
  #quotationsTable thead th {
    background: #f8fafc !important;
    background-color: #f8fafc !important;
    color: #475569 !important;
    font-weight: 700 !important;
    font-size: 0.75rem !important;
    text-transform: uppercase !important;
    letter-spacing: 0.05em !important;
    border-bottom: 2px solid #e2e8f0 !important;
    border-top: none !important;
    padding: 0.85rem 0.75rem !important;
    vertical-align: middle !important;
  }

  #quotationsTable tbody tr:hover {
    background-color: rgba(99, 102, 241, 0.03) !important;
  }

  #quotationsTable td {
    vertical-align: middle;
    padding: 0.85rem 0.75rem !important;
    border-bottom: 1px solid #f1f5f9 !important;
  }

  /* DataTables Modern Styling */
  .dataTables_wrapper {
    padding: 1.25rem 1.25rem 0.75rem 1.25rem;
  }

  .dataTables_wrapper .dataTables_length label,
  .dataTables_wrapper .dataTables_filter label {
    font-size: 0.85rem;
    font-weight: 500;
    color: #64748b;
  }

  .dataTables_wrapper .dataTables_length select {
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    padding: 0.35rem 0.75rem;
    margin: 0 0.4rem;
    outline: none;
    font-size: 0.85rem;
  }

  .dataTables_wrapper .dataTables_filter input {
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    padding: 0.35rem 0.75rem;
    margin-left: 0.5rem;
    outline: none;
    font-size: 0.85rem;
  }

  .dataTables_wrapper .dataTables_filter input:focus,
  .dataTables_wrapper .dataTables_length select:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
  }

  .dataTables_wrapper .dataTables_info {
    font-size: 0.85rem;
    color: #64748b;
    padding-top: 1rem !important;
  }

  .dataTables_wrapper .dataTables_paginate {
    padding-top: 0.75rem !important;
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 8px !important;
    font-size: 0.85rem !important;
    padding: 0.35rem 0.75rem !important;
    margin: 0 2px;
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button.current,
  .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
    background: #4f46e5 !important;
    border-color: #4f46e5 !important;
    color: white !important;
  }

  /* ===============================
   CHAT MODAL WRAPPER
================================ */
  #chatModal .modal-content {
    border-radius: 20px !important;
    overflow: hidden;
  }

  #chatModal .modal-header {
    background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 25%, #3730a3 60%, #4f46e5 100%) !important;
    border-bottom: none;
  }

  #chatModal .modal-title {
    font-weight: 700;
    color: #ffffff !important;
  }

  /* ===============================
   MESSAGES CONTAINER
================================ */
  #messages-list {
    height: 400px;
    overflow-y: auto;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 18px;
    background: #f9fafb;
  }

  /* Scrollbar */
  #messages-list::-webkit-scrollbar {
    width: 6px;
  }

  #messages-list::-webkit-scrollbar-thumb {
    background: #c7c7ff;
    border-radius: 10px;
  }

  /* ===============================
   MESSAGE WRAPPER
================================ */
  .message {
    display: flex;
    flex-direction: column;
    max-width: 70%;
  }

  /* ===============================
   CUSTOMER (LEFT)
================================ */
  .user-message {
    align-self: flex-start;
    text-align: left;
  }

  .user-message .message-content {
    background: #eef2ff;
    color: #1e293b;
    padding: 10px 14px;
    border-radius: 14px;
    border-top-left-radius: 6px;
    font-size: 0.9rem;
    line-height: 1.45;
  }

  /* ===============================
   PROVIDER (RIGHT)
================================ */
  .provider-message {
    align-self: flex-end;
    text-align: right;
  }

  .provider-message .message-content {
    background: #dcfce7;
    color: #065f46;
    padding: 10px 14px;
    border-radius: 14px;
    border-top-right-radius: 6px;
    font-size: 0.9rem;
    line-height: 1.45;
  }

  /* ===============================
   SENDER NAME
================================ */
  .message-sender {
    font-size: 0.75rem;
    font-weight: 600;
    color: #6b7280;
    margin-bottom: 4px;
  }

  /* Align sender names */
  .provider-message .message-sender {
    text-align: right;
  }

  .user-message .message-sender {
    text-align: left;
  }

  /* ===============================
   TIMESTAMP
================================ */
  .message-timestamp {
    font-size: 0.7rem;
    color: #9ca3af;
    margin-top: 4px;
  }

  .provider-message .message-timestamp {
    text-align: right;
  }

  .user-message .message-timestamp {
    text-align: left;
  }

  /* ===============================
   MODAL FOOTER
================================ */
  #chatModal .modal-footer {
    border-top: 1px solid #e5e7eb;
    background: #fff;
  }

  /* ===============================
   MOBILE OPTIMIZATION
================================ */
  @media (max-width: 768px) {
    .message {
      max-width: 85%;
    }

    #messages-list {
      padding: 12px;
    }
  }

  body.modal-open {
    overflow: hidden !important;
    height: 100vh;
  }




  .bg-gradient-primary {
    background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
  }

  /* Responsive Design */
  @media (max-width: 768px) {
    .modal-dialog {
      margin: 20px auto;
    }

    /* Hide some columns on mobile */
    #quotationsTable th:nth-child(4),
    #quotationsTable td:nth-child(4) {
      display: none !important;
    }

    #quotationsTable th:nth-child(5),
    #quotationsTable td:nth-child(5) {
      display: none !important;
    }

    #quotationsTable th:nth-child(6),
    #quotationsTable td:nth-child(6) {
      display: none !important;
    }

    /* Adjust provider details for mobile */
    #quotationsTable td:nth-child(2) .d-flex {
      flex-direction: column;
      align-items: flex-start;
      gap: 8px;
    }

    #quotationsTable td:nth-child(2) .d-flex .rounded-circle {
      width: 35px;
      height: 35px;
      font-size: 0.8rem;
    }
  }
</style>

<script>
  // Function to load chat messages
  function loadChatMessages(bookingId, user, provider, providerName) {
    console.log("Loading chat for:", providerName, bookingId, user, provider);

    // Update modal title
    document.getElementById('chatModalLabel').innerHTML = `<i class="bi bi-chat-dots me-2"></i>Chat with ${providerName}`;

    // Clear previous messages and show loading
    const messagesList = document.getElementById('messages-list');
    messagesList.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Loading messages...</p>
            </div>
        `;

    // Show the modal
    const chatModalElement = document.getElementById('chatModal');
    const chatModal = new bootstrap.Modal(chatModalElement);
    chatModal.show();

    // Load messages via AJAX
    $.ajax({
      type: 'GET',
      url: `<?php echo base_url('firestore/getMessages') ?>/${encodeURIComponent(bookingId)}/${encodeURIComponent(user)}/${encodeURIComponent(provider)}`,
      dataType: 'json',
      success: function(response) {
        renderChatMessages(response.messages || []);
      },
      error: function(xhr, status, error) {
        console.error("Error fetching messages:", error);
        messagesList.innerHTML = `
                    <div class="alert alert-danger m-3">
                        <i class="bi bi-exclamation-triangle"></i>
                        Failed to load chat messages. Please try again.
                    </div>
                `;
      }
    });
  }

  // Function to render chat messages
  function renderChatMessages(messages) {
    const messagesList = document.getElementById('messages-list');
    messagesList.innerHTML = '';

    if (!messages || messages.length === 0) {
      messagesList.innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-chat-dots display-1 text-muted"></i>
                    <p class="mt-3 text-muted">No messages found</p>
                </div>
            `;
      return;
    }

    // Sort messages by timestamp
    messages.sort((a, b) => new Date(a.timestamp) - new Date(b.timestamp));

    messages.forEach(function(message) {
      const isUser = message.isQuestion;
      const messageClass = isUser ? 'user-message' : 'provider-message';
      const senderName = message.sender ? message.sender.split('_')[0] : 'Unknown';
      const senderType = isUser ? ' (Customer)' : ' (Provider)';
      const timestamp = formatTimestamp(message.timestamp);

      const messageDiv = document.createElement('div');
      messageDiv.className = `message ${messageClass}`;
      messageDiv.innerHTML = `
                <div class="message-sender">${senderName}${senderType}</div>
                <div class="message-content">${message.text}</div>
                <div class="message-timestamp">${timestamp}</div>
            `;

      messagesList.appendChild(messageDiv);
    });

    // Scroll to bottom
    messagesList.scrollTop = messagesList.scrollHeight;
  }

  // Function to format timestamp
  function formatTimestamp(timestamp) {
    try {
      const date = new Date(timestamp);
      if (isNaN(date.getTime())) {
        return 'Invalid date';
      }
      return date.toLocaleString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    } catch (error) {
      console.error("Error formatting timestamp:", timestamp, error);
      return timestamp;
    }
  }

  // Function to check for chat messages on page load
  function checkChatMessagesForAllRows() {
    document.querySelectorAll('.chat-messages').forEach(function(link) {
      const bookingId = link.getAttribute('data-booking-id');
      const user = link.getAttribute('data-user');
      const provider = link.getAttribute('data-provider');

      if (!bookingId || !provider) return;

      $.ajax({
        type: 'GET',
        url: `<?php echo base_url('firestore/getMessages') ?>/${encodeURIComponent(bookingId)}/${encodeURIComponent(user)}/${encodeURIComponent(provider)}`,
        dataType: 'json',
        success: function(response) {
          const indicator = link.querySelector('.chat-indicator');
          const badge = link.querySelector('.chat-count-badge');
          const linkText = link.querySelector('.chat-link-text');

          if (response.messages && response.messages.length > 0) {
            // Show the chat indicator and badge
            if (indicator) indicator.style.display = 'inline';
            if (badge) {
              badge.textContent = response.messages.length;
              badge.style.display = 'inline';
            }
            if (linkText) {
              linkText.textContent = `${response.messages.length} Messages`;
              linkText.classList.remove('text-muted', 'small');
              linkText.classList.add('text-primary', 'fw-semibold');
            }
          } else {
            // Hide the chat indicator and badge
            if (indicator) indicator.style.display = 'none';
            if (badge) {
              badge.textContent = '';
              badge.style.display = 'none';
            }
            if (linkText) {
              linkText.textContent = 'No Chat';
              linkText.classList.remove('text-primary', 'fw-semibold');
              linkText.classList.add('text-muted', 'small');
            }
          }
        },
        error: function(xhr, status, error) {
          console.error("Error checking messages:", error);
        }
      });
    });
  }

  function initQuotationsPage() {
    if ($.fn.DataTable.isDataTable('#quotationsTable')) {
      $('#quotationsTable').DataTable().destroy();
    }

    $('#quotationsTable').DataTable({
      "order": [
        [6, "asc"]
      ],
      "pageLength": 10,
      "responsive": true,
      "columnDefs": [
        { "orderable": false, "targets": [8, 9] }
      ],
      "language": {
        "search": "Search quotations:",
        "lengthMenu": "Show _MENU_ entries",
        "info": "Showing _START_ to _END_ of _TOTAL_ entries"
      }
    });

    checkChatMessagesForAllRows();
  }

  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initQuotationsPage);
  } else {
    initQuotationsPage();
  }

  // Global event delegation for chat modal
  $(document).on('click', '.chat-messages', function(e) {
    e.preventDefault();
    const bookingId = $(this).data('booking-id');
    const user = $(this).data('user');
    const provider = $(this).data('provider');
    const providerName = $(this).data('provider-name');

    loadChatMessages(bookingId, user, provider, providerName);
  });

  // Global event delegation for Delete Quotation button
  $(document).on('click', '.delete-quotation-btn', function(e) {
    e.preventDefault();
    const quotationId = $(this).data('id');
    const providerName = $(this).data('provider-name') || 'this provider';
    const $row = $(this).closest('tr');

    Swal.fire({
      title: 'Delete Quotation?',
      text: `Are you sure you want to delete the quotation from ${providerName}?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: 'POST',
          url: `<?= base_url('bookings/deleteQuotation') ?>/${quotationId}`,
          data: { id: quotationId },
          dataType: 'json',
          success: function(response) {
            if (response.status) {
              Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: response.message || 'Quotation has been deleted.',
                timer: 1500,
                showConfirmButton: false
              }).then(() => {
                if ($.fn.DataTable.isDataTable('#quotationsTable')) {
                  $('#quotationsTable').DataTable().row($row).remove().draw(false);
                } else {
                  $row.remove();
                }
              });
            } else {
              Swal.fire('Error!', response.message || 'Failed to delete quotation.', 'error');
            }
          },
          error: function(xhr, status, error) {
            console.error("Error deleting quotation:", error);
            Swal.fire('Error!', 'An unexpected error occurred while deleting the quotation.', 'error');
          }
        });
      }
    });
  });

  // Clear modal content when closed
  $(document).on('hidden.bs.modal', '#chatModal', function() {
    const list = document.getElementById('messages-list');
    if (list) list.innerHTML = '';
  });
</script>

<?= $this->include('admin/footer') ?>
<?= $this->endSection() ?>