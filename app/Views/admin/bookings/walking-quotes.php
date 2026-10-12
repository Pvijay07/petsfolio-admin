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
      <div class="bg-gradient-primary text-white p-5 rounded-3 shadow position-relative overflow-hidden">
        <div class="position-relative z-1">
          <h1 class="h2 fw-bold mb-2 text-white">Service Providers Quotes</h1>
          <p class="text-white-75 mb-0">View and manage all service provider quotations for booking ID:
            <span class="fw-bold"><?= $quotations[0]->booking_id ?? 'N/A' ?></span>
          </p>
        </div>
        <i class="bi bi-file-earmark-text position-absolute end-0 bottom-0 opacity-10"
          style="font-size: 14rem; transform: rotate(12deg); margin-right: -3rem; margin-bottom: -3rem;"></i>
      </div>
    </div>

    <!-- Customer Info Card -->
    <div class="card border-0 shadow-sm mb-4 rounded-3">
      <div class="card-body px-4 py-3">
        <div class="row align-items-center">
          <div class="col-md-6">
            <div class="d-flex align-items-center gap-3">
              <div
                class="rounded-circle bg-primary bg-opacity-10 text-black d-flex align-items-center justify-content-center fw-bold"
                style="width: 60px; height: 60px; border: 2px solid #e5dbff; font-size: 1.5rem; color: #ffffff !important;">
                <?= strtoupper(substr($quotations[0]->user_name ?? '?', 0, 1)) ?>
              </div>
              <div>
                <h4 class="fw-bold mb-1 text-dark"><?= $quotations[0]->user_name ?? 'Customer' ?></h4>
                <p class="text-muted mb-1">
                  <i class="bi bi-telephone me-1"></i>
                  <?= $quotations[0]->user_phone ?? '--' ?>
                </p>
                <p class="text-muted mb-0">
                  <i class="bi bi-calendar-check me-1"></i>
                  Booking ID: <?= $quotations[0]->booking_id ?? '--' ?>
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="<?= base_url() ?>downloadQuotes?id=<?= $quotations[0]->booking_id ?>"
              class="btn btn-primary bg-primary text-white px-4 py-2 rounded-3 shadow-sm d-inline-flex align-items-center gap-2"
              style="background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;">
              <i class="bi bi-download"></i>
              Download Quotes
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Quotations Table Card -->
    <div class="card border-0 shadow-sm overflow-hidden rounded-3">
      <div class="card-body p-0">
        <div class="table-responsive">
          <div id="chat-loader" style="display: none; text-align:center; margin:20px 0;" class="text-center">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading chat messages...</p>
          </div>
          <table class="table table-hover mb-0" id="quotationsTable">
            <thead style="background: #c6d2ff !important;">
              <tr>
                <th style="background: #c6d2ff !important;"
                  class="py-3 text-black fw-semibold border-bottom text-center">S.No.</th>
                <th style="background: #c6d2ff !important;" class="py-3 text-black fw-semibold border-bottom">Provider
                  Details</th>
                <th style="background: #c6d2ff !important;"
                  class="py-3 text-black fw-semibold border-bottom text-center">Bid Amount</th>
                <th style="background: #c6d2ff !important;"
                  class="py-3 text-black fw-semibold border-bottom text-center">Timings</th>
                <th style="background: #c6d2ff !important;"
                  class="py-3 text-black fw-semibold border-bottom text-center">Addon Price</th>
                <th style="background: #c6d2ff !important;"
                  class="py-3 text-black fw-semibold border-bottom text-center">Discount</th>
                <th style="background: #c6d2ff !important;"
                  class="py-3 text-black fw-semibold border-bottom text-center">Distance</th>
                  <th style="background: #c6d2ff !important;"
                  class="py-3 text-black fw-semibold border-bottom text-center">Created At</th>
                <th style="background: #c6d2ff !important;"
                  class="py-3 text-black fw-semibold border-bottom text-center">Chat</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 1;
              foreach ($quotations as $quotation) {
                if (strtolower(trim($quotation->status)) == 'accepted') {
                  continue;
                }
                $distance = $quotation->distance ?? 0;
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
                        class="rounded-circle bg-primary bg-opacity-10 text-black d-flex align-items-center justify-content-center fw-bold"
                        style="width: 40px; height: 40px; font-size: 0.9rem; color: #ffffff !important;">
                        <?= strtoupper(substr($quotation->name ?? '?', 0, 1)) ?>
                      </div>
                      <div>
                        <div class="fw-medium text-dark mb-1"><?= $quotation->name ?? '--' ?></div>
                        <div class="d-flex flex-wrap align-items-center gap-2 flex-column">
                          <span class="text-muted small">
                            <i class="bi bi-telephone me-1"></i><?= $quotation->phone ?>
                          </span>
                          <span class="badge bg-light text-dark border px-2 py-1 small" style="background-color: rgba(59, 130, 246, 0.1) !important;
        color: #2563eb !important;
        border: 1px solid rgba(59, 130, 246, 0.2) !important; border-radius: 50px;">
                            <i class="bi bi-geo-alt me-1"></i><?= $quotation->area ?>
                          </span>
                        </div>
                      </div>
                    </div>
                  </td>

                  <!-- Bid Amount Column -->
                  <td class="text-center">
                    <div class="fw-bold text-primary fs-6" style="background: rgba(34, 197, 94, 0.1) !important; color: #16a34a !important;
        border: 1px solid rgba(34, 197, 94, 0.2) !important; font-weight: 600; border-radius: 50px;">
                      ₹<?= number_format($quotation->bid_amount) ?>
                    </div>
                  </td>

                  <!-- Timings Column (shows Date + Time) -->
                  <td class="text-center">
                    <div class="d-flex flex-column align-items-center">
                      <span class="badge bg-info bg-opacity-10 text-info border-info border-opacity-25 px-3 py-1 mb-1"
                        style=" background-color: rgba(251, 191, 36, 0.1) !important;
        color: #d97706 !important;
        border: 1px solid rgba(251, 191, 36, 0.2) !important; font-size: 0.75rem; border-radius: 50px;">
                        <i class="bi bi-clock me-1"></i><?= $time ?>
                      </span>
                    </div>
                  </td>

                  <!-- Addon Price Column -->
                  <td class="text-center">
                    <span class="text-dark fw-medium">
                      ₹<?= number_format($quotation->addonPrice) ?>
                    </span>
                  </td>

                  <!-- Discount Column -->
                  <td class="text-center">
                    <span class="badge bg-danger bg-opacity-10 text-danger border-danger border-opacity-25 px-3 py-1 "
                      style="border-radius: 50px;">
                      ₹<?= number_format($quotation->discount_amount ?? 0) ?>
                    </span>
                  </td>

                  <!-- Distance Column -->
                  <td class="text-center" data-order="<?= $distance ?>">
                    <span class="badge <?= $distanceBadgeClass ?> px-3 py-1" style="border-radius: 50px;">
                      <i class="bi bi-signpost-split me-1"></i><?= $distance ?> Km
                    </span>
                  </td>
                  <td  class="text-center"><?= date('Y-m-d', strtotime($quotation->created_at)) ?></td>
                  <!-- Chat Column -->
                  <td class="text-center">
                    <a href="#" class="chat-messages text-decoration-none"
                      data-provider="<?= $quotation->name . "_" . $quotation->provider_id ?>"
                      data-user="<?= $quotation->user_name . "_" . $quotation->user_id ?>"
                      data-booking-id="<?= $quotation->booking_id ?>" data-row-id="chat-<?= $quotation->id ?>"
                      data-provider-name="<?= htmlspecialchars($quotation->name) ?>">
                      <div class="d-flex flex-column align-items-center">
                        <span class="chat-link-text text-primary fw-medium mb-1">View Chat</span>
                        <div class="d-flex align-items-center">
                          <span class="chat-indicator" style="display:none; margin-right: 5px;">
                            <i class="bi bi-chat-dots-fill text-success"></i>
                          </span>
                          <span class="chat-count-badge"
                            style="display:none; background: #28a745; color: white; border-radius: 50%; width: 20px; height: 20px; font-size: 0.7rem; line-height: 20px; text-align: center; ">
                          </span>
                        </div>
                      </div>
                    </a>
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

      <!-- Pagination -->
      <div class="card-footer border-0 d-flex justify-content-between align-items-center p-2">
        <div class="text-muted small">
          Showing <?= count($quotations) ?> quotations
        </div>
        <div class="btn-group mt-0 pt-0" style="border-top: none !important;">
          <button class="btn btn-outline-primary btn-sm">
            <i class="bi bi-chevron-left"></i> Previous
          </button>
          <button class="btn btn-primary btn-sm">1</button>
          <button class="btn btn-outline-primary btn-sm">
            Next <i class="bi bi-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Chat Modal -->
<div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow p-0">
      <div class="modal-header" style="background: #c6d2ff !important;">
        <h5 class="modal-title fw-bold text-black" id="chatModalLabel">
          <i class="bi bi-chat-dots me-2"></i>Chat Messages
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <div id="messages-list" style="height: 400px; overflow-y: auto; padding: 20px;">
          <!-- Messages will be loaded here -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
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
  #quotationsTable tbody tr:hover {
    background-color: rgba(99, 102, 241, 0.03) !important;
  }

  #quotationsTable td {
    vertical-align: middle;
  }

 /* ===============================
   CHAT MODAL WRAPPER
================================ */
#chatModal .modal-content {
  border-radius: 14px;
  overflow: hidden;
}

#chatModal .modal-header {
  background: #cfd8ff !important;
  border-bottom: 1px solid #e5e7eb;
}

#chatModal .modal-title {
  font-weight: 600;
  color: #111827;
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
      url: `<?php echo base_url('firestore/getMessages') ?>/${bookingId}/${user}/${provider}`,
      dataType: 'json',
      success: function (response) {
        renderChatMessages(response.messages || []);
      },
      error: function (xhr, status, error) {
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

    messages.forEach(function (message) {
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
    document.querySelectorAll('.chat-messages').forEach(function (link) {
      const bookingId = link.getAttribute('data-booking-id');
      const user = link.getAttribute('data-user');
      const provider = link.getAttribute('data-provider');

      $.ajax({
        type: 'GET',
        url: `<?php echo base_url('firestore/getMessages') ?>/${bookingId}/${user}/${provider}`,
        dataType: 'json',
        success: function (response) {
          if (response.messages && response.messages.length > 0) {
            // Show the chat indicator
            const indicator = link.querySelector('.chat-indicator');
            const badge = link.querySelector('.chat-count-badge');
            const linkText = link.querySelector('.chat-link-text');

            if (indicator) indicator.style.display = 'inline';
            if (badge) {
              badge.textContent = response.messages.length;
              badge.style.display = 'inline';
            }
            if (linkText) linkText.textContent = `${response.messages.length} Messages`;
          }
        },
        error: function (xhr, status, error) {
          console.error("Error checking messages:", error);
        }
      });
    });
  }

  // Initialize when DOM is ready
  document.addEventListener('DOMContentLoaded', function () {
    console.log("DOM loaded, initializing chat functionality");

    // Initialize DataTable
    // if ($.fn.DataTable.isDataTable('#quotationsTable')) {
    //   $('#quotationsTable').DataTable().destroy();
    // }

    // $('#quotationsTable').DataTable({
    //   "order": [[6, "asc"]], // Changed to column 6 for distance sorting
    //   "pageLength": 10,
    //   "responsive": true,
    //   "language": {
    //     "search": "Search quotations:",
    //     "lengthMenu": "Show _MENU_ entries",
    //     "info": "Showing _START_ to _END_ of _TOTAL_ entries"
    //   }
    // });

    // Check for chat messages on page load
    checkChatMessagesForAllRows();

    // Add click event listeners to chat links
    document.addEventListener('click', function (e) {
      if (e.target.closest('.chat-messages')) {
        e.preventDefault();
        const link = e.target.closest('.chat-messages');
        const bookingId = link.getAttribute('data-booking-id');
        const user = link.getAttribute('data-user');
        const provider = link.getAttribute('data-provider');
        const providerName = link.getAttribute('data-provider-name');

        loadChatMessages(bookingId, user, provider, providerName);
      }
    });

    // Clear modal content when closed
    document.getElementById('chatModal').addEventListener('hidden.bs.modal', function () {
      document.getElementById('messages-list').innerHTML = '';
    });
  });

  // Fallback in case DOMContentLoaded already fired
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      // Already handled above
    });
  } else {
    // DOM already loaded, run initialization
    if ($.fn.DataTable.isDataTable('#quotationsTable')) {
      $('#quotationsTable').DataTable().destroy();
    }

    $('#quotationsTable').DataTable({
      "order": [[6, "asc"]],
      "pageLength": 10,
      "responsive": true,
      "language": {
        "search": "Search quotations:",
        "lengthMenu": "Show _MENU_ entries",
        "info": "Showing _START_ to _END_ of _TOTAL_ entries"
      }
    });

    checkChatMessagesForAllRows();

    // Add click event listeners
    $(document).on('click', '.chat-messages', function (e) {
      e.preventDefault();
      const bookingId = $(this).data('booking-id');
      const user = $(this).data('user');
      const provider = $(this).data('provider');
      const providerName = $(this).data('provider-name');

      loadChatMessages(bookingId, user, provider, providerName);
    });
  }
</script>

<?= $this->include('admin/footer') ?>
<?= $this->endSection() ?>