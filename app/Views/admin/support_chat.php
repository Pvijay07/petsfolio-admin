<?php echo $this->extend('admin/includes/header'); ?>
<!--start content-->
<style>
  /* Chat specific styles */
  .message-bubble {
    max-width: 75%;
    word-break: break-word;
  }


  .chat-body {
    scroll-behavior: smooth;
  }

  .chat-body::-webkit-scrollbar {
    width: 6px;
  }

  .chat-body::-webkit-scrollbar-track {
    background: #f1f1f1;
  }

  .chat-body::-webkit-scrollbar-thumb {
    background: #c5c5c5;
    border-radius: 10px;
  }

  /* Status badge styling */
  .status-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.75rem;
  }

  /* Avatar styling */
  .chat-avatar {
    width: 40px;
    height: 40px;
    border: 2px solid #e5dbff;
  }

  /* Attachment styling */
  .chat-image {
    max-width: 200px;
    height: auto;
    border-radius: 8px;
    transition: transform 0.3s ease;
  }

  .chat-image:hover {
    transform: scale(1.05);
  }

  /* Typing indicator */
  .typing-dots {
    display: inline-flex;
    align-items: center;
  }

  .typing-dots span {
    width: 6px;
    height: 6px;
    margin: 0 2px;
    background-color: #6366f1;
    border-radius: 50%;
    animation: typing 1.4s infinite;
  }

  .typing-dots span:nth-child(2) {
    animation-delay: 0.2s;
  }

  .typing-dots span:nth-child(3) {
    animation-delay: 0.4s;
  }

  @keyframes typing {

    0%,
    60%,
    100% {
      transform: translateY(0);
      opacity: 0.6;
    }

    30% {
      transform: translateY(-5px);
      opacity: 1;
    }
  }

  /* Message timestamp */
  .message-time {
    font-size: 0.75rem;
    color: #6c757d;
  }

  /* Rating button styling */
  .rating-btn {
    background: linear-gradient(135deg, #ffb700, #ff9500);
    border: none;
    color: white;
    font-weight: 500;
  }

  .rating-btn:hover {
    background: linear-gradient(135deg, #ff9500, #ff7b00);
    color: white;
  }

  /* File preview */
  .file-preview {
    background: rgba(99, 102, 241, 0.05);
    border: 1px solid rgba(99, 102, 241, 0.2);
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 0.875rem;
  }

  /* Go back button */
  .go-back-btn {
    background: #c6d2ff;
    border: 1px solid #a3b4ff;
    color: #1a1a1a;
    transition: all 0.3s ease;
  }

  .go-back-btn:hover {
    background: #a3b4ff;
    border-color: #8499ff;
    color: #1a1a1a;
  }

  /* New styles for compact design */
  .compact-header {
    padding: 1rem !important;
  }

  .chat-container-wrapper {
    height: calc(100vh - 140px);
    display: flex;
    flex-direction: column;
  }

  .chat-container {
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  .messages-area {
    flex: 1;
    overflow-y: auto;
    padding-bottom: 12px;
  }

  .chat-footer {
    flex-shrink: 0;
    background: #fff;
  }

  /* Message bubble styling with your app's color theme */
  .message-bubble.support {
    background: #c6d2ff !important;
    color: #1a1a1a !important;
    border: 1px solid #a3b4ff;
  }

  .message-bubble.client {
    background: rgba(251, 191, 36, 0.1) !important;
    color: #1a1a1a !important;
    border: 1px solid rgba(251, 191, 36, 0.2);
  }

  .message-bubble.provider {
    background: rgba(34, 197, 94, 0.1) !important;
    color: #1a1a1a !important;
    border: 1px solid rgba(34, 197, 94, 0.2);
  }

  /* Status indicator dot */
  .status-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 6px;
  }

  .status-online {
    background-color: #22c55e;
  }

  .status-offline {
    background-color: #6b7280;
  }

  /* Force styles for dynamically added messages */
  .dynamically-added-message .message-bubble {
    background: #c6d2ff !important;
    color: #1a1a1a !important;
    border: 1px solid #a3b4ff !important;
    max-width: 75% !important;
    word-break: break-word !important;
    padding: 12px 16px !important;
    border-radius: 18px !important;
    margin-bottom: 0 !important;
  }

  .footer-form {
    width: 100%;
  }

  .footer-form .input-group {
    width: 100%;
  }

  main {
    min-height: auto !important;
  }

  /* Quick Replies Styles */
  .quick-replies-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    padding: 12px 16px;
    background: rgba(99, 102, 241, 0.05);
    border-top: 1px solid rgba(99, 102, 241, 0.1);
    border-radius: 12px;
    margin-bottom: 12px;
  }

  .quick-reply-btn {
    font-size: 0.85rem;
    padding: 8px 16px;
    border-radius: 50px;
    background: #ffffff;
    border: 1px solid rgba(99, 102, 241, 0.2);
    color: #4b5563;
    transition: all 0.2s ease;
    white-space: nowrap;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    cursor: pointer;
    font-weight: 500;
  }

  .quick-reply-btn:hover {
    background: #c6d2ff;
    border-color: #a3b4ff;
    color: #1a1a1a;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(99, 102, 241, 0.12);
  }

  /* Refund Button Styling */
  .refund-btn {
    background: rgba(239, 68, 68, 0.1) !important;
    color: #dc2626 !important;
    border: 1px solid rgba(239, 68, 68, 0.2) !important;
    font-weight: 600;
    border-radius: 50px;
    display: none;
    /* Hidden by default */
  }

  .refund-btn:hover {
    background: rgba(239, 68, 68, 0.2) !important;
    color: #b91c1c !important;
  }
</style>
<?php echo $this->section('content'); ?>
<div class="position-relative iq-banner">
  <?php echo $this->include('admin/nav_bar') ?>
</div>
<main class="main-content px-4" style="margin-top: -50px;">
  <div class="card border-0 shadow-sm overflow-hidden rounded-3 chat-container-wrapper " style="margin-top: -50px;">
    <div class="card-header p-3" style=" background: linear-gradient(135deg, #5555fa 0%, #5555fa 100%) !important;">
      <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
          <a href="<?= base_url('support') ?>"
            class="btn go-back-btn btn-sm rounded-circle d-flex align-items-center justify-content-center"
            style="width: 36px; height: 36px;">
            <i class="bi bi-arrow-left fs-4 fw-bold text-white"></i>
          </a>
          <div class="position-relative">
            <?php
            $path = $chat->user_type === 'client' ? 'users' : 'providers';
            $profileImage = $chat->profile
              ? "https://beta.petsfolio.in/public/uploads/{$path}/{$chat->profile}"
              : 'https://beta.petsfolio.in/admin/public/assets/images/avatars/user.png';
            ?>
            <img src="<?= $profileImage ?>" class="rounded-circle chat-avatar " style="width: 40px; height: 40px;"
              alt="User profile">
          </div>
          <div>
            <h6 class="fw-semibold mb-0 text-white fs-5"><?= esc($chat->name) ?></h6>
          </div>
        </div>

        <!-- Status Dropdown -->
        <div class="d-flex align-items-center gap-2">
          <span id="unreadCount" class="badge bg-danger px-2 py-1 small" style="display:none;">0</span>
          <select id="ticketStatus" class="form-select form-select-sm border"
            style="min-width: 110px; font-size: 0.875rem;" onchange="updateStatusd(this.value)">
            <option value="open" <?= $chat->status == 'open' ? 'selected' : '' ?>>Open</option>
            <option value="pending" <?= $chat->status == 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="resolved" <?= $chat->status == 'resolved' ? 'selected' : '' ?>>Resolved</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Chat Body -->
    <div class="card-body p-0 d-flex flex-column flex-grow-1">
      <div class="chat-container d-flex flex-column">
        <div class="chat-body p-3 messages-area" style="height: 400px; max-height: 550px; overflow: scroll;"
          id="chatBody">
          <?php foreach ($messages as $msg):
            $path = $msg->sender_type === 'client' ? 'users' : 'providers';
            $messageClass = '';
            if ($msg->sender_type == 'support') {
              $messageClass = 'support';
            } elseif ($msg->sender_type == 'client') {
              $messageClass = 'client';
            } else {
              $messageClass = 'provider';
            }
          ?>
            <?php
            $inlineStyle = '';
            $bgColor = '';
            $textColor = '';
            $borderColor = '';

            if ($msg->sender_type === 'support') {
              $bgColor = '#c6d2ff';
              $textColor = '#1a1a1a';
              $borderColor = '#a3b4ff';
            } elseif ($msg->sender_type === 'client') {
              $bgColor = 'rgba(251, 191, 36, 0.1)';
              $textColor = '#1a1a1a';
              $borderColor = 'rgba(251, 191, 36, 0.2)';
            } else {
              $bgColor = 'rgba(34, 197, 94, 0.1)';
              $textColor = '#1a1a1a';
              $borderColor = 'rgba(34, 197, 94, 0.2)';
            }

            $inlineStyle = "background: {$bgColor} !important; 
                            color: {$textColor} !important; 
                            border: 1px solid {$borderColor} !important;
                            max-width: 100% !important;
                            word-break: break-word !important;
                            padding: 12px 16px !important;
                            border-radius: 18px !important;
                            margin-bottom: 0 !important;";
            ?>
            <div class="d-flex mb-3 message-item <?= $msg->sender_type == 'support' ? 'justify-content-end' : '' ?>"
              data-msg-id="<?= $msg->id ?>" data-sender-type="<?= $msg->sender_type ?>">

              <?php if ($msg->sender_type != 'support'): ?>
                <div class="me-2 flex-shrink-0">
                  <img src="<?= $msg->sender_type === 'client'
                              ? "https://beta.petsfolio.in/public/uploads/users/4wLKCl5bRMcgYkq8cIjqa5w7ouS.jpg"
                              : "https://beta.petsfolio.in/public/uploads/users/4wLKCl5bRMcgYkq8cIjqa5w7ouS.jpg" ?>"
                    class="rounded-circle chat-avatar" alt="Sender" style="width: 40px; height: 40px;">
                </div>
              <?php endif; ?>

              <div class="message-container" style="max-width: 75%;">
                <div class="message-bubble rounded-3" style="<?= $inlineStyle ?>">
                  <?php if (!empty($msg->attachments)): ?>
                    <div class="mb-2">
                      <?php if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $msg->attachments)): ?>
                        <a href="https://petsfolio.in/<?= $msg->attachments ?>" target="_blank" class="d-inline-block">
                          <img src="https://petsfolio.in/<?= $msg->attachments ?>" class="chat-image" alt="attachment">
                        </a>
                      <?php else: ?>
                        <a href="https://petsfolio.in/<?= $msg->attachments ?>" target="_blank"
                          class="btn btn-sm btn-outline-primary">
                          <i class="bi bi-download me-1"></i> Download File
                        </a>
                      <?php endif; ?>
                    </div>
                  <?php endif; ?>

                  <?php if (!empty($msg->message)): ?>
                    <p class="mb-0"
                      style="margin-bottom: 0 !important; line-height: 1.5 !important; color: inherit !important;">
                      <?= esc($msg->message) ?>
                    </p>
                  <?php endif; ?>
                </div>
                <div class="message-time mt-1 <?= $msg->sender_type == 'support' ? 'text-end' : '' ?>"
                  style="font-size: 0.75rem !important; color: #6c757d !important; margin-top: 4px !important;">
                  <small>
                    <span
                      class="fw-medium"><?= $msg->sender_type == 'support' ? esc($msg->support_name ?? 'Support') : esc($chat->name) ?></span>
                    • <?= date('M j, Y h:i A', strtotime($msg->created_at)) ?>
                  </small>
                </div>
              </div>

              <?php if ($msg->sender_type == 'support'): ?>
                <div class="ms-2 flex-shrink-0 " style="width: 40px; height: 40px;">
                  <div
                    class="rounded-circle bg-primary h-100 w-100 text-white d-flex align-items-center justify-content-center fw-bold chat-avatar">
                    <?= strtoupper(substr($msg->support_name ?? 'S', 0, 1)) ?>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>

        <div id="typingIndicator" class="text-muted small mb-2" style="height: 20px;"></div>

        <?php if ($chat->status !== 'resolved'): ?>
          <div class="card-footer border-top chat-footer p-3" style="background: #ffffff;">

            <div id="filePreview" class="file-preview mb-2" style="display: none;"></div>

            <div class="text-center mb-2 d-flex justify-content-center gap-2">
              <button type="button" class="btn rating-btn btn-sm px-3 py-1" style="background: rgba(34, 197, 94, 0.1) !important; 
             color: #16a34a !important;
             border: 1px solid rgba(34, 197, 94, 0.2) !important; 
             font-weight: 600; 
             border-radius: 50px;" onclick="askForRating()">
                <i class="bi bi-star me-1"></i> Request Rating
              </button>

              <button type="button" id="refundAmountBtn" class="btn refund-btn btn-sm px-3 py-1" onclick="showRefundAmount()">
                <i class="bi bi-currency-dollar me-1"></i> Refund Amount
              </button>
            </div>

            <!-- Quick Replies -->
            <div class="quick-replies-container mb-2 rounded-3">
              <button type="button" class="quick-reply-btn" onclick="setQuickReply('Hello! How can I help you today?')">👋 Greeting</button>
              <button type="button" class="quick-reply-btn" onclick="askForBankDetails()">🏦 Request Bank Details</button>
              <button type="button" class="quick-reply-btn" onclick="setQuickReply('We are currently looking into your request. Please wait a moment.')">⏳ Investigating</button>
              <button type="button" class="quick-reply-btn" onclick="setQuickReply('Could you please provide more details or photos regarding this issue?')">📸 More Details</button>
              <button type="button" class="quick-reply-btn" onclick="setQuickReply('Your issue has been resolved. Is there anything else I can help with?')">✅ Resolved</button>
              <button type="button" class="quick-reply-btn" onclick="setQuickReply('I am transferring this chat to the relevant department.')">🔄 Transfer</button>
            </div>

            <form class="footer-form rounded-4 mb-1" onsubmit="sendMessage(); return false;">
              <div class="input-group input-group-sm d-flex gap-2">
                <input type="text" id="messageInput" class="form-control bg-light focus-0 rounded-4 border-end-0 p-3"
                  style="font-size: 16px;" placeholder="Type your message..." aria-label="Message">

                <button class="btn btn-primary px-3 rounded-4" type="submit" style="background: #c6d2ff !important; 
               border-color: #a3b4ff !important; 
               color: #1a1a1a !important;">
                  <i class="bi bi-send me-1"></i> Send
                </button>
              </div>
            </form>

          </div>
        <?php else: ?>
          <div class="card-footer text-center py-3 bg-light">
            <span class="text-muted fw-semibold">
              🔒 This ticket has been closed. Messaging is disabled.
            </span>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  </div>
</main>

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://js.pusher.com/8.2/pusher.min.js"></script>

<script>
  const senderName = '<?= session('user')->name ?>';
  const senderId = <?= session('user')->id ?>;
  let currentChatId = "<?= $chat->id ?>";
  const BASE_URL = 'https://beta.petsfolio.in/api/';
  const PUSHER_KEY = '3c3318df787d87828310';
  const userId = "<?= $chat->user_id ?>";

  // Audio for notifications
  const barkSound = new Audio('https://petsfolio.in/admin/public/assets/sounds/bark.mp3');

  // Function to apply styles to dynamically added messages
  function applyMessageStyles(messageElement) {
    if (!messageElement) return;

    const senderType = messageElement.getAttribute('data-sender-type') ||
      messageElement.querySelector('[data-sender-type]')?.getAttribute('data-sender-type');

    let bgColor = '';
    let textColor = '';
    let borderColor = '';

    if (senderType === 'support') {
      bgColor = '#c6d2ff';
      textColor = '#1a1a1a';
      borderColor = '#a3b4ff';
    } else if (senderType === 'client') {
      bgColor = 'rgba(251, 191, 36, 0.1)';
      textColor = '#1a1a1a';
      borderColor = 'rgba(251, 191, 36, 0.2)';
    } else {
      bgColor = 'rgba(34, 197, 94, 0.1)';
      textColor = '#1a1a1a';
      borderColor = 'rgba(34, 197, 94, 0.2)';
    }

    // Apply styles to message bubble
    const messageBubble = messageElement.querySelector('.message-bubble');
    if (messageBubble) {
      messageBubble.style.background = bgColor + ' !important';
      messageBubble.style.color = textColor + ' !important';
      messageBubble.style.border = '1px solid ' + borderColor + ' !important';
      messageBubble.style.maxWidth = '100% !important';
      messageBubble.style.wordBreak = 'break-word !important';
      messageBubble.style.whiteSpace = 'pre-wrap !important';
      messageBubble.style.padding = '12px 16px !important';
      messageBubble.style.borderRadius = '18px !important';
      messageBubble.style.marginBottom = '0 !important';
      messageBubble.style.display = 'inline-block !important';
    }

    // Apply styles to paragraph inside message
    const paragraph = messageElement.querySelector('.message-bubble p');
    if (paragraph) {
      paragraph.style.marginBottom = '0 !important';
      paragraph.style.lineHeight = '1.5 !important';
      paragraph.style.color = 'inherit !important';
    }

    // Apply styles to message time
    const messageTime = messageElement.querySelector('.message-time');
    if (messageTime) {
      messageTime.style.fontSize = '0.75rem !important';
      messageTime.style.color = '#6c757d !important';
      messageTime.style.marginTop = '4px !important';
    }
  }

  // Apply styles to all existing messages
  function applyAllMessageStyles() {
    document.querySelectorAll('.message-item').forEach(messageElement => {
      applyMessageStyles(messageElement);
    });
  }

  // Quick Reply Function
  function setQuickReply(text) {
    const messageInput = document.getElementById('messageInput');
    if (messageInput) {
      messageInput.value = text;
      messageInput.focus();
    }
  }

  // Show Refund Amount Logic
  function showRefundAmount() {
    const chatBody = document.getElementById('chatBody');
    const content = chatBody ? chatBody.innerText.toLowerCase() : '';
    const bankKeywords = ['account number', 'ifsc', 'branch', 'bank name', 'account holder'];
    const hasBankDetails = bankKeywords.some(k => content.includes(k));

    if (!hasBankDetails) {
      Swal.fire({
        title: 'Bank Details Missing',
        text: 'No bank details were detected in the chat. Do you want to request them from the user first?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Request Now',
        cancelButtonText: 'I have them manually',
        confirmButtonColor: '#5555fa'
      }).then((result) => {
        if (result.isConfirmed) {
          requestBankDetails();
        } else if (result.dismiss === Swal.DismissReason.cancel) {
          proceedWithRefundPopup();
        }
      });
      return;
    }

    proceedWithRefundPopup();
  }

  function extractBankDetail(chatContent, label) {
    const labels = ["Account Holder", "Bank Name", "Account Number", "IFSC Code", "City", "Branch", "Account No", "IFSC", "Bank", "Bank AC", "A/C"];
    const labelPattern = labels.map(l => l.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')).join('|');
    const regex = new RegExp(`(${label})\\s*[:\\-]?\\s*([\\s\\S]*?)(?=\\s*(?:${labelPattern})\\s*[:\\-]|\\n|$)`, "i");
    const match = chatContent.match(regex);
    if (match && match[2]) {
      return match[2].trim();
    }
    return '';
  }

  function proceedWithRefundPopup() {
    const bubbles = document.querySelectorAll('#chatBody .message-bubble p');

    function getVal(label) {
      for (let i = bubbles.length - 1; i >= 0; i--) {
        const val = extractBankDetail(bubbles[i].innerText, label);
        if (val) return val;
      }
      return '';
    }

    const details = {
      name: getVal('Account Holder'),
      bank: getVal('Bank Name') || getVal('Bank'),
      account: getVal('Account Number') || getVal('Account No') || getVal('Bank AC') || getVal('A/C'),
      ifsc: getVal('IFSC Code') || getVal('IFSC'),
      city: getVal('City'),
      branch: getVal('Branch')
    };
    console.log(details);
    Swal.fire({
      title: 'Refund Details',
      html: `
        <div class="text-start">
          <div class="mb-3">
             <label class="form-label small fw-bold">Wallet Balance (Available)</label>
             <div class="input-group input-group-sm">
                <span class="input-group-text">₹</span>
                <input type="text" id="swal-amount" class="form-control" value="Fetching..." readonly>
             </div>
          </div>
          <div class="mb-2">
            <label class="form-label small fw-bold">Account Holder Name</label>
            <input type="text" id="swal-holder" class="form-control form-control-sm" value="${details.name}">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-bold">Bank Name</label>
            <input type="text" id="swal-bank" class="form-control form-control-sm" value="${details.bank}">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-bold">Account Number</label>
            <input type="text" id="swal-account" class="form-control form-control-sm" value="${details.account}">
          </div>
          <div class="row">
            <div class="col-md-6 mb-2">
               <label class="form-label small fw-bold">IFSC Code</label>
               <input type="text" id="swal-ifsc" class="form-control form-control-sm" value="${details.ifsc}">
            </div>
            <div class="col-md-6 mb-2">
               <label class="form-label small fw-bold">City</label>
               <input type="text" id="swal-city" class="form-control form-control-sm" value="${details.city}">
            </div>
          </div>
          <div class="mb-2">
            <label class="form-label small fw-bold">Branch</label>
            <input type="text" id="swal-branch" class="form-control form-control-sm" value="${details.branch}">
          </div>
        </div>
      `,
      preConfirm: () => {
        return {
          user_id: userId,
          amount: document.getElementById('swal-amount').value,
          account_holder_name: document.getElementById('swal-holder').value,
          account_number: document.getElementById('swal-account').value,
          bank_name: document.getElementById('swal-bank').value,
          ifsc_code: document.getElementById('swal-ifsc').value,
          city: document.getElementById('swal-city').value,
          branch: document.getElementById('swal-branch').value
        }
      },
      icon: 'info',
      confirmButtonText: 'Process Refund',
      showCancelButton: true,
      cancelButtonText: 'Cancel',
      confirmButtonColor: '#dc2626',
      didOpen: () => {
        // Fetch wallet amount
        fetch(`${BASE_URL}chat/getUserWallet/${userId}`)
          .then(res => res.json())
          .then(data => {
            if (data.status === 'ok') {
              document.getElementById('swal-amount').value = data.amount;
            } else {
              document.getElementById('swal-amount').value = '0.00';
            }
          })
          .catch(() => {
            document.getElementById('swal-amount').value = 'Error';
          });
      }
    }).then((result) => {
      if (result.isConfirmed) {
        processRefund(result.value);
      }
    });
  }

  function processRefund(data) {
    if (!data.account_number || !data.amount || data.amount == '0.00' || data.amount == 'Fetching...') {
      Swal.fire('Invalid Data', 'Please ensure wallet balance is loaded and account details are provided.', 'error');
      return;
    }

    Swal.fire({
      title: 'Processing Refund',
      text: 'Please wait...',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });

    fetch(`<?= base_url() ?>chat/processRefund`, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams(data).toString()
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === "ok") {
          Swal.fire('Success', data.message, 'success');
        } else {
          Swal.fire('Error', data.message || 'Failed to process refund.', 'error');
        }
      })
      .catch(err => {
        console.error("Error processing refund:", err);
        Swal.fire('Error', 'Something went wrong.', 'error');
      });
  }

  // Request Bank Details Function
  function requestBankDetails() {
    const msg = "To initiate your refund, please provide your bank details:\n- Account Holder Name:\n- Bank Name:\n- Account Number:\n- IFSC Code:";
    const messageInput = document.getElementById('messageInput');
    if (messageInput) {
      messageInput.value = msg;
      messageInput.focus();
      // Optional: Automatically send? 
      // For now, let the agent review then click send.
    }
  }

  // Function to scan messages for refund keyword
  function checkRefundVisibility() {
    const chatBody = document.getElementById('chatBody');
    const refundBtn = document.getElementById('refundAmountBtn');
    if (!chatBody || !refundBtn) return;

    const content = chatBody.innerText.toLowerCase();
    const keywords = ['refund', 'return money', 'money back', 'reimburse', 'pay back', 'withdraw', 'withdrawn'];
    const found = keywords.some(k => content.includes(k));

    if (found) {
      refundBtn.style.display = 'inline-block';
    } else {
      refundBtn.style.display = 'none';
    }
  }

  // Function to add new message with proper styling
  function addNewMessage(messageData) {
    const chatBody = document.getElementById('chatBody');
    if (!chatBody) return;

    const isSupport = messageData.sender_type === 'support';
    const messageClass = messageData.sender_type === 'support' ? 'support' :
      messageData.sender_type === 'client' ? 'client' : 'provider';

    let bgColor = '';
    let textColor = '';
    let borderColor = '';

    if (messageData.sender_type === 'support') {
      bgColor = '#c6d2ff';
      textColor = '#1a1a1a';
      borderColor = '#a3b4ff';
    } else if (messageData.sender_type === 'client') {
      bgColor = 'rgba(251, 191, 36, 0.1)';
      textColor = '#1a1a1a';
      borderColor = 'rgba(251, 191, 36, 0.2)';
    } else {
      bgColor = 'rgba(34, 197, 94, 0.1)';
      textColor = '#1a1a1a';
      borderColor = 'rgba(34, 197, 94, 0.2)';
    }

    const messageHtml = `
      <div class="d-flex mb-3 message-item ${isSupport ? 'justify-content-end' : ''}" 
           data-msg-id="${messageData.id}" data-sender-type="${messageData.sender_type}">
        ${!isSupport ? `
          <div class="me-2 flex-shrink-0">
            <img src="${messageData.sender_type === 'client'
          ? 'https://beta.petsfolio.in/public/uploads/users/4wLKCl5bRMcgYkq8cIjqa5w7ouS.jpg'
          : 'https://beta.petsfolio.in/public/uploads/users/4wLKCl5bRMcgYkq8cIjqa5w7ouS.jpg'}" 
              class="rounded-circle chat-avatar" alt="Sender" style="width: 40px; height: 40px;">
          </div>
        ` : ''}
        
        <div class="message-container" style="max-width: 75%;">
          <div class="message-bubble rounded-3" 
               style="background: ${bgColor} !important; 
                      color: ${textColor} !important; 
                      border: 1px solid ${borderColor} !important;
                      max-width: 100% !important;
                      word-break: break-word !important;
                      padding: 12px 16px !important;
                      border-radius: 18px !important;
                      margin-bottom: 0 !important;">
            ${messageData.attachments ? `
              <div class="mb-2">
                ${/\.(jpg|jpeg|png|gif|webp)$/i.test(messageData.attachments) ? `
                  <a href="https://petsfolio.in/${messageData.attachments}" target="_blank" class="d-inline-block">
                    <img src="https://petsfolio.in/${messageData.attachments}" class="chat-image" alt="attachment">
                  </a>
                ` : `
                  <a href="https://petsfolio.in/${messageData.attachments}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-download me-1"></i> Download File
                  </a>
                `}
              </div>
            ` : ''}
            
            ${messageData.message ? `
              <p class="mb-0" style="margin-bottom: 0 !important; line-height: 1.5 !important; color: inherit !important;">
                ${messageData.message}
              </p>
            ` : ''}
          </div>
          <div class="message-time mt-1 ${isSupport ? 'text-end' : ''}" 
               style="font-size: 0.75rem !important; color: #6c757d !important; margin-top: 4px !important;">
            <small>
              <span class="fw-medium">${isSupport ? (messageData.support_name || 'Support') : '<?= esc($chat->name) ?>'}</span>
              • ${new Date(messageData.created_at).toLocaleString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
          })}
            </small>
          </div>
        </div>
        
        ${isSupport ? `
          <div class="ms-2 flex-shrink-0" style="width: 40px; height: 40px;">
            <div class="rounded-circle bg-primary h-100 w-100 text-white d-flex align-items-center justify-content-center fw-bold chat-avatar">
              ${(messageData.support_name || 'S').charAt(0).toUpperCase()}
            </div>
          </div>
        ` : ''}
      </div>
    `;

    chatBody.insertAdjacentHTML('beforeend', messageHtml);

    barkSound.play().catch(() => {});
    scrollToBottom(true);
  }

  function updateStatusd(status) {
    console.log(status);
    updateTicketStatus(status);
  }

  function updateTicketStatus(status) {
    fetch(BASE_URL + "/supportticket/updateStatus", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `chat_id=${currentChatId}&status=${status}`,
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === "ok") {
          alert("Status updated successfully.");
          window.location.href = '<?= base_url() ?>/support';
        } else {
          alert("Failed to update status.");
        }
      })
      .catch(err => {
        console.error("Error updating status:", err);
        alert("Something went wrong.");
      });
  }

  function askForRating() {
    return fetch(`${BASE_URL}/chat/askRating/${currentChatId}`, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `sender_id=${senderId}&sender_type=support`
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === "ok") {
          alert("Rating request sent to user.");
        } else {
          alert("Failed to request rating.");
        }
      })
      .catch(err => {
        console.error("Error asking for rating:", err);
        alert("Something went wrong.");
      });
  }

  function askForBankDetails() {
    return fetch(`${BASE_URL}/chat/askBankDetails/${currentChatId}`, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `sender_id=${senderId}&sender_type=support`
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === "ok") {
          alert("Bank details request sent to user.");
        } else {
          alert("Failed to request bank details.");
        }
      })
      .catch(err => {
        console.error("Error asking for bank details:", err);
        alert("Something went wrong.");
      });
  }

  // Auto-scroll to bottom of chat
  function scrollToBottom(force = false) {
    const chatBody = document.getElementById('chatBody');
    if (!chatBody) return;

    const isNearBottom =
      chatBody.scrollHeight - chatBody.scrollTop - chatBody.clientHeight < 120;

    if (force || isNearBottom) {
      requestAnimationFrame(() => {
        chatBody.scrollTop = chatBody.scrollHeight;
      });
    }
  }

  // Initialize on page load
  document.addEventListener('DOMContentLoaded', function() {
    // Apply styles to all existing messages
    applyAllMessageStyles();

    // Scroll to bottom
    scrollToBottom();

    // Check for refund keyword on load
    checkRefundVisibility();

    initializePusher();
  });

  // Initialize Pusher for real-time chat
  function initializePusher() {
    // Enable pusher logging - don't include this in production
    // Pusher.logToConsole = true;

    const pusher = new Pusher(PUSHER_KEY, {
      cluster: 'ap2',
      forceTLS: true
    });

    const channel = pusher.subscribe('chat-channel-' + currentChatId);

    channel.bind('new-message', function(data) {
      if (data.message) {
        addNewMessage(data.message);
        checkRefundVisibility(); // Re-scan messages when new one arrives
      }
    });

    channel.bind('typing', function(data) {
      // Handle typing indicator
      const typingIndicator = document.getElementById('typingIndicator');
      if (typingIndicator) {
        if (data.isTyping && data.user_id !== senderId) {
          typingIndicator.innerHTML = '<span class="typing-dots"><span></span><span></span><span></span></span> Typing...';
        } else {
          typingIndicator.innerHTML = '';
        }
      }
    });
  }

  // Function to send message
  function sendMessage() {
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();

    if (!message) return;

    // Clear input
    messageInput.value = '';

    // Create temporary message (will be replaced by real message from Pusher)
    const tempId = 'temp-' + Date.now();
    const tempMessage = {
      id: tempId,
      message: message,
      sender_type: 'support',
      support_name: senderName,
      created_at: new Date().toISOString()
    };

    addNewMessage(tempMessage);

    // Send message to server
    fetch(BASE_URL + '/chat/send', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `chat_id=${currentChatId}&message=${encodeURIComponent(message)}&sender_id=${senderId}&sender_type=support`
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'ok') {
          // Remove temporary message
          const tempElement = document.querySelector(`[data-msg-id="${tempId}"]`);
          if (tempElement) {
            tempElement.remove();
          }
        }
      })
      .catch(error => {
        console.error('Error sending message:', error);
      });
  }
</script>

<?php echo $this->include('admin/footer') ?>
<script src='<?= base_url() ?>public/assets/js/chat.js?v=<?= time() ?>'></script>
<?php echo $this->endSection() ?>