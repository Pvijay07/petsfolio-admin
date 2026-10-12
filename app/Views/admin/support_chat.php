<?php echo $this->extend('admin/includes/header'); ?>
<<<<<<< HEAD
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
=======

<?php
$db = \Config\Database::connect();
if (!empty($chat->user_id)) {
  $client = $db->table('users')->where('id', $chat->user_id)->get()->getRow();
  if ($client) {
    $chat->name    = $client->name    ?? $chat->name ?? '';
    $chat->phone   = $client->phone   ?? $client->mobile ?? $chat->phone ?? '';
    $chat->profile = $client->profile ?? $chat->profile ?? '';
    $chat->email   = $client->email   ?? '';
  }
}
?>

<?php echo $this->section('content'); ?>
<style>
  /* ── Premium Support Console Design Tokens ── */
  :root {
    --pri-gradient: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
    --pri-color: #4f46e5;
    --pri-hover: #4338ca;
    --pri-subtle: #eef2ff;
    --pri-border: #c7d2fe;
    --slate-bg: #f8fafc;
    --slate-border: #e2e8f0;
    --slate-text: #0f172a;
    --slate-muted: #64748b;
    --radius-bubble: 18px;
    --shadow-card: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.03);
    --shadow-bubble: 0 3px 10px rgba(79, 70, 229, 0.22);
  }

  .chat-desk-wrapper {
    height: calc(100vh - 130px);
    min-height: 600px;
    display: flex;
    flex-direction: column;
    background: #ffffff;
    border: 1px solid var(--slate-border);
    border-radius: 16px;
    box-shadow: var(--shadow-card);
    overflow: hidden;
    margin-bottom: 1.5rem;
  }

  /* ── Top Command Bar ── */
  .chat-desk-header {
    background: #ffffff;
    border-bottom: 1px solid var(--slate-border);
    padding: 12px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
    z-index: 10;
  }

  .customer-profile-card {
    display: flex;
    align-items: center;
    gap: 14px;
  }

  .customer-avatar-wrap {
    position: relative;
    flex-shrink: 0;
  }

  .customer-avatar {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    object-fit: cover;
    background: #f1f5f9;
    border: 2px solid #e2e8f0;
  }

  .customer-avatar-fallback {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
    color: #4338ca;
    font-weight: 700;
    font-size: 1.15rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #e2e8f0;
  }

  .online-indicator {
    width: 13px;
    height: 13px;
    background-color: #22c55e;
    border: 2.5px solid #ffffff;
    border-radius: 50%;
    position: absolute;
    bottom: 0;
    right: 0;
  }

  .customer-meta-name {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--slate-text);
    line-height: 1.2;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .customer-sub-meta {
    font-size: 0.8rem;
    color: var(--slate-muted);
    margin-top: 3px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }

  /* Segmented Mode Switcher */
  .segmented-mode-switcher {
    background: #f1f5f9;
    padding: 3px;
    border-radius: 50px;
    display: inline-flex;
    align-items: center;
    border: 1px solid #e2e8f0;
  }

  .segmented-mode-btn {
    border: none;
    background: transparent;
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--slate-muted);
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
  }

  .segmented-mode-btn.active-human {
    background: #ffffff;
    color: #0369a1;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
  }

  .segmented-mode-btn.active-ai {
    background: #6366f1;
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(99, 102, 241, 0.35);
  }

  /* ── Chat Messages Body ── */
  .chat-desk-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px 24px;
    background: #f8fafc;
    display: flex;
    flex-direction: column;
    gap: 14px;
    scroll-behavior: smooth;
  }

  .chat-desk-body::-webkit-scrollbar {
    width: 6px;
  }
  .chat-desk-body::-webkit-scrollbar-track {
    background: transparent;
  }
  .chat-desk-body::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
  }

  /* Date Badge Divider */
  .chat-timeline-divider {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 8px 0;
    position: relative;
  }

  .chat-timeline-divider::before {
    content: '';
    position: absolute;
    left: 0;
    right: 0;
    height: 1px;
    background: #e2e8f0;
  }

  .chat-timeline-badge {
    position: relative;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    padding: 3px 12px;
    border-radius: 50px;
    font-size: 0.73rem;
    font-weight: 600;
    color: #94a3b8;
    box-shadow: 0 1px 2px rgba(0,0,0,0.03);
  }

  /* Message Rows */
  .msg-row-container {
    display: flex;
    align-items: flex-end;
    gap: 10px;
    max-width: 80%;
    animation: msgFadeIn 0.25s ease forwards;
  }

  @keyframes msgFadeIn {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .msg-row-container.msg-client {
    align-self: flex-start;
  }

  .msg-row-container.msg-support {
    align-self: flex-end;
    flex-direction: row-reverse;
  }

  .msg-avatar-circle {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.85rem;
    margin-bottom: 20px;
  }

  .msg-avatar-circle.client-avatar {
    background: #e2e8f0;
    color: #475569;
  }

  .msg-avatar-circle.support-avatar {
    background: var(--pri-gradient);
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(79, 70, 229, 0.3);
  }

  .msg-bubble-group {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }

  .msg-support .msg-bubble-group {
    align-items: flex-end;
  }

  /* Bubble Styling */
  .msg-bubble {
    padding: 12px 18px;
    font-size: 0.92rem;
    line-height: 1.55;
    word-break: break-word;
    position: relative;
  }

  .msg-client .msg-bubble {
    background: #ffffff;
    color: #1e293b;
    border: 1px solid #e2e8f0;
    border-radius: 18px 18px 18px 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
  }

  .msg-support .msg-bubble {
    background: var(--pri-gradient);
    color: #ffffff;
    border-radius: 18px 18px 4px 18px;
    box-shadow: var(--shadow-bubble);
  }

  .msg-bubble p {
    margin: 0;
  }

  .msg-meta-line {
    font-size: 0.72rem;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 0 4px;
  }

  .msg-support .msg-meta-line {
    flex-direction: row-reverse;
  }

  .msg-sender-name {
    font-weight: 600;
    color: #64748b;
  }

  .msg-support .msg-sender-name {
    color: #818cf8;
  }

  /* Message Attachments */
  .bubble-img-preview {
    max-width: 240px;
    max-height: 200px;
    border-radius: 12px;
    object-fit: cover;
    display: block;
    margin-bottom: 6px;
    border: 1px solid rgba(0,0,0,0.06);
    transition: transform 0.2s;
  }
  .bubble-img-preview:hover {
    transform: scale(1.02);
  }

  /* Delete Action Button */
  .btn-delete-msg {
    opacity: 0.85;
    visibility: visible;
    transition: all 0.2s ease;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #fee2e2;
    color: #ef4444;
    border: 1px solid #fca5a5;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    cursor: pointer;
    align-self: center;
    flex-shrink: 0;
    margin: 0 4px;
  }

  .btn-delete-msg:hover {
    opacity: 1;
    background: #ef4444;
    color: #ffffff;
    transform: scale(1.15);
    box-shadow: 0 2px 6px rgba(239, 68, 68, 0.35);
  }

  .btn-delete-link {
    cursor: pointer;
    font-size: 0.72rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 3px;
    transition: all 0.15s;
  }
  .btn-delete-link:hover {
    text-decoration: underline;
    opacity: 0.8;
  }

  /* Soft Deleted Card */
  .deleted-msg-box {
    background: #f1f5f9 !important;
    border: 1px dashed #cbd5e1 !important;
    color: #64748b !important;
    font-style: italic;
    font-size: 0.84rem;
    padding: 10px 14px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  /* ── Chat Desk Footer ── */
  .chat-desk-footer {
    background: #ffffff;
    border-top: 1px solid var(--slate-border);
    padding: 12px 20px 16px 20px;
    flex-shrink: 0;
  }

  /* AI Copilot Drawer */
  .ai-copilot-drawer {
    background: #fbfcfe;
    border: 1px solid #e0e7ff;
    border-left: 4px solid #6366f1;
    border-radius: 12px;
    padding: 12px 16px;
    margin-bottom: 10px;
    box-shadow: 0 2px 8px rgba(99, 102, 241, 0.06);
  }

  .ai-suggestion-box {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 0.88rem;
    line-height: 1.5;
    color: #1e293b;
    max-height: 110px;
    overflow-y: auto;
    white-space: pre-wrap;
    margin-bottom: 8px;
  }

  /* Quick Response Chips */
  .quick-chips-row {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    padding-bottom: 8px;
    margin-bottom: 8px;
    scrollbar-width: none;
  }

  .quick-chips-row::-webkit-scrollbar {
    display: none;
  }

  .quick-chip {
    white-space: nowrap;
    padding: 5px 13px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 50px;
    font-size: 0.78rem;
    color: #475569;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.18s ease;
  }

  .quick-chip:hover {
    background: var(--pri-subtle);
    border-color: var(--pri-border);
    color: var(--pri-color);
    transform: translateY(-1px);
  }

  /* Main Input Dock */
  .chat-input-dock {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 50px;
    padding: 5px 8px 5px 16px;
    transition: all 0.2s ease;
  }

  .chat-input-dock:focus-within {
    border-color: #6366f1;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
  }

  .chat-input-dock input {
    flex: 1;
    border: none;
    background: transparent;
    font-size: 0.92rem;
    color: #0f172a;
    outline: none;
    padding: 8px 0;
  }

  .btn-polish {
    border: 1px solid #c7d2fe;
    background: #eef2ff;
    color: #4f46e5;
    font-size: 0.78rem;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 50px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.18s;
  }

  .btn-polish:hover {
    background: #e0e7ff;
    transform: scale(1.02);
  }

  .btn-send-main {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--pri-gradient);
    color: #ffffff;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    box-shadow: 0 3px 8px rgba(79, 70, 229, 0.35);
    transition: all 0.18s;
  }

  .btn-send-main:hover {
    transform: scale(1.06);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.45);
  }

  /* Typing Dot Animation */
  .typing-indicator-bar {
    min-height: 22px;
    font-size: 0.76rem;
    color: #94a3b8;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 4px;
  }
  .typing-dots span {
    display: inline-block;
    width: 5px;
    height: 5px;
    background: #94a3b8;
    border-radius: 50%;
    animation: blink 1.2s infinite;
  }
  .typing-dots span:nth-child(2) { animation-delay: .2s; }
  .typing-dots span:nth-child(3) { animation-delay: .4s; }
  @keyframes blink { 0%, 80%, 100% { opacity: .25; } 40% { opacity: 1; } }
</style>

<div class="position-relative iq-banner">
  <?php echo $this->include('admin/nav_bar') ?>
</div>

<div class="container-fluid content-inner pb-0" id="page_layout">
  <div class="row">
    <div class="col-12">
      <!-- Chat Desk Container -->
      <div class="chat-desk-wrapper">

        <!-- ── Top Command Bar ── -->
        <div class="chat-desk-header">
          <!-- Customer Profile & Metadata -->
          <div class="customer-profile-card">
            <div class="customer-avatar-wrap">
              <?php
              $path = ($chat->user_type === 'provider') ? 'providers' : 'users';
              $profileImage = !empty($chat->profile) ? "https://beta.petsfolio.in/public/uploads/{$path}/{$chat->profile}" : '';
              ?>
              <?php if (!empty($profileImage)): ?>
                <img src="<?= $profileImage ?>" class="customer-avatar" alt="Customer"
                  onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="customer-avatar-fallback" style="display:none;"><?= strtoupper(substr($chat->name ?: 'C', 0, 1)) ?></div>
              <?php else: ?>
                <div class="customer-avatar-fallback"><?= strtoupper(substr($chat->name ?: 'C', 0, 1)) ?></div>
              <?php endif; ?>
              <div class="online-indicator"></div>
            </div>

            <div>
              <div class="customer-meta-name">
                <?= esc($chat->name) ?: 'Valued Customer' ?>
                <span class="badge rounded-pill bg-light text-secondary border fw-bold" style="font-size: 0.72rem;">
                  #<?= esc($chat->ticket_number ?? $chat->id) ?>
                </span>
              </div>
              <div class="customer-sub-meta">
                <span><i class="bi bi-telephone me-1 text-primary"></i> <?= esc($chat->phone ?? 'N/A') ?></span>
                <span>·</span>
                <span class="badge rounded-pill <?= $chat->status == 'resolved' ? 'bg-success text-white' : 'bg-warning text-dark' ?>" style="font-size: 0.68rem;">
                  <?= ucfirst($chat->status ?? 'Open') ?>
                </span>
              </div>
            </div>
          </div>

          <!-- Controls & Actions -->
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <!-- Segmented Mode Switcher (Default: Human Mode) -->
            <div class="segmented-mode-switcher" title="Toggle AI Auto-Pilot vs Human Copilot">
              <button type="button" class="segmented-mode-btn active-human" id="btnModeHuman" onclick="setSupportMode('human')">
                <i class="bi bi-person-check-fill"></i> Human Mode <span class="badge bg-light text-dark rounded-pill px-1" style="font-size: 0.62rem;">Default</span>
              </button>
              <button type="button" class="segmented-mode-btn" id="btnModeAI" onclick="setSupportMode('ai')">
                <i class="bi bi-robot text-warning"></i> AI Mode <span class="badge bg-success text-white rounded-pill px-1" style="font-size: 0.62rem;">Auto-Send</span>
              </button>
            </div>

            <!-- Ticket Status -->
            <select id="ticketStatus" class="form-select form-select-sm rounded-pill border fw-bold shadow-sm"
              style="width: 115px; font-size: 0.78rem;" onchange="updateTicketStatus(this.value)">
              <option value="open" <?= $chat->status == 'open' ? 'selected' : '' ?>>🟢 Open</option>
              <option value="pending" <?= $chat->status == 'pending' ? 'selected' : '' ?>>🟡 Pending</option>
              <option value="resolved" <?= $chat->status == 'resolved' ? 'selected' : '' ?>>✅ Resolved</option>
            </select>

            <!-- Back to Support List -->
            <a href="<?= base_url('support') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold d-none d-md-inline-flex align-items-center gap-1" style="font-size: 0.78rem;">
              <i class="bi bi-arrow-left"></i> Tickets
            </a>
          </div>
        </div>

        <!-- ── Chat Messages Body ── -->
        <div class="chat-desk-body" id="chatBody">
          <?php
          $lastDate = null;
          foreach ($messages as $msg):
            $msgDate   = date('Y-m-d', strtotime($msg->created_at));
            $isSupport = ($msg->sender_type == 'support');
            $isDeleted = !empty($msg->is_deleted);

            if ($msgDate !== $lastDate):
              $lastDate = $msgDate;
          ?>
              <div class="chat-timeline-divider">
                <span class="chat-timeline-badge"><?= date('M j, Y', strtotime($msg->created_at)) ?></span>
              </div>
            <?php endif; ?>

            <div class="msg-row-container <?= $isSupport ? 'msg-support' : 'msg-client' ?>"
              data-msg-id="<?= $msg->id ?>" data-sender-type="<?= $msg->sender_type ?>">

              <!-- Avatar -->
              <div class="msg-avatar-circle <?= $isSupport ? 'support-avatar' : 'client-avatar' ?>">
                <?= $isSupport ? strtoupper(substr($msg->support_name ?: 'S', 0, 1)) : strtoupper(substr($chat->name ?: 'C', 0, 1)) ?>
              </div>

              <!-- Delete Action Hover Button -->
              <?php if (!$isDeleted): ?>
                <button type="button" class="btn-delete-msg" onclick="deleteMessage(<?= $msg->id ?>)" title="Delete message">
                  <i class="bi bi-trash3"></i>
                </button>
              <?php endif; ?>

              <!-- Bubble & Meta -->
              <div class="msg-bubble-group">
                <?php if ($isDeleted): ?>
                  <div class="deleted-msg-box">
                    <i class="bi bi-slash-circle text-danger fs-6"></i>
                    <span>This message was deleted by <strong><?= esc($msg->deleted_by ?: 'Admin') ?></strong></span>
                  </div>
                <?php else: ?>
                  <div class="msg-bubble">
                    <?php if ($msg->attachments): ?>
                      <?php if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $msg->attachments)): ?>
                        <a href="https://petsfolio.in/<?= $msg->attachments ?>" target="_blank">
                          <img src="https://petsfolio.in/<?= $msg->attachments ?>" class="bubble-img-preview" alt="Attachment">
                        </a>
                      <?php else: ?>
                        <a href="https://petsfolio.in/<?= $msg->attachments ?>" target="_blank" class="btn btn-sm btn-light border rounded-3 mb-2 d-inline-flex align-items-center gap-1">
                          <i class="bi bi-paperclip"></i> View Document
                        </a>
                      <?php endif; ?>
                    <?php endif; ?>

                    <?php if ($msg->message): ?>
                      <p><?= esc($msg->message) ?></p>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>

                <div class="msg-meta-line">
                  <span class="msg-sender-name">
                    <?= $isSupport ? (esc($msg->support_name) ?: 'Support Team') : (esc($chat->name) ?: 'Customer') ?>
                  </span>
                  <span>·</span>
                  <span><?= date('h:i A', strtotime($msg->created_at)) ?></span>
                  <?php if (!$isDeleted): ?>
                    <span>·</span>
                    <span class="btn-delete-link" onclick="deleteMessage(<?= $msg->id ?>)" title="Delete message">
                      <i class="bi bi-trash3 text-danger"></i> <span class="text-danger">Delete</span>
                    </span>
                  <?php endif; ?>
                </div>
              </div>

>>>>>>> origin/main
            </div>
          <?php endforeach; ?>
        </div>

<<<<<<< HEAD
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

=======
        <!-- ── Chat Desk Footer ── -->
        <div class="chat-desk-footer">
          <div id="typingIndicator" class="typing-indicator-bar"></div>

          <?php if ($chat->status != 'resolved'): ?>

            <!-- AI Copilot Suggestion Card -->
            <div id="aiCopilotBox" class="ai-copilot-drawer">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="d-flex align-items-center gap-2">
                  <span class="badge text-white rounded-pill px-2 py-1 fw-bold" id="aiModeBadge" style="background:#0284c7; font-size: 0.72rem;">
                    <i class="bi bi-stars me-1"></i> AI Copilot Ready
                  </span>
                  <small class="text-muted fw-semibold" id="aiCopilotStatus">
                    Review suggestion below before sending
                  </small>
                </div>
                <div class="d-flex gap-1">
                  <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-2 py-0" style="font-size:0.75rem;" onclick="generateAiReply(true)" title="Regenerate">
                    <i class="bi bi-arrow-clockwise me-1"></i>Regenerate
                  </button>
                  <button type="button" class="btn btn-sm btn-link text-muted p-0 ms-1" onclick="dismissAiSuggestion()" title="Dismiss">
                    <i class="bi bi-x-lg"></i>
                  </button>
                </div>
              </div>
              <div id="aiSuggestedText" class="ai-suggestion-box">
                <em>AI Copilot ready. In Human Mode, AI suggestions will appear here for review.</em>
              </div>
              <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex gap-2">
                  <button type="button" class="btn btn-sm rounded-pill px-3 fw-bold text-white d-flex align-items-center gap-1 shadow-sm" id="btnApplyAndSendAi" onclick="applyAndSendAi()" style="background:#4f46e5; border-color:#4f46e5; font-size:0.8rem;">
                    <i class="bi bi-send-fill"></i> Send AI Reply
                  </button>
                  <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="insertAiIntoInput()" style="font-size:0.8rem;">
                    <i class="bi bi-pencil-square me-1"></i> Insert & Edit
                  </button>
                </div>
                <small class="text-muted" style="font-size:0.72rem;">
                  <i class="bi bi-shield-check text-success me-1"></i>Knowledge Base Guarded
                </small>
              </div>
            </div>

            <!-- Quick Action Chips Row -->
            <div class="quick-chips-row" id="quickChipsContainer">
              <div class="quick-chip" onclick="setQuickReply('Hello! Welcome to Petsfolio. How can I help you and your pet today?')">👋 Greeting</div>
              <div class="quick-chip" onclick="askForBankDetails()">🏦 Request Bank Details</div>
              <div class="quick-chip" onclick="askForRating()">⭐ Request Rating</div>
              <div class="quick-chip" id="refundAmountBtn" style="display:none; color:#dc2626; border-color:#fecaca; background:#fef2f2;" onclick="showRefundAmount()">₹ Process Refund</div>
              <div class="quick-chip" onclick="setQuickReply('We are checking with our technical team. Please hold on.')">⏳ Investigating</div>
              <div class="quick-chip" onclick="setQuickReply('Your request has been resolved. Is there anything else I can assist with?')">✅ Issue Resolved</div>
            </div>

            <!-- Main Input Dock -->
            <div class="chat-input-dock">
              <i class="bi bi-chat-text text-primary fs-5"></i>
              <input type="text" id="messageInput" placeholder="Type a message or use AI suggestion…"
                onkeypress="if(event.keyCode===13) sendMessage()">
              
              <!-- Polish Button -->
              <button type="button" class="btn-polish" id="btnPolishDraft" onclick="polishDraft()" title="Refine message tone with AI">
                <i class="bi bi-magic text-warning"></i> Polish
              </button>

              <!-- Send Button -->
              <button class="btn-send-main" onclick="sendMessage()" aria-label="Send">
                <i class="bi bi-send-fill"></i>
              </button>
            </div>

          <?php else: ?>
            <div class="text-center py-2 text-muted fw-semibold" style="font-size: 0.85rem;">
              🔒 Ticket resolved &amp; archived. Messaging is locked.
            </div>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- SweetAlert2 CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Pusher JS -->
<script src="https://js.pusher.com/8.2/pusher.min.js"></script>
>>>>>>> origin/main
<script>
  const senderName = '<?= session('user')->name ?>';
  const senderId = <?= session('user')->id ?>;
  let currentChatId = "<?= $chat->id ?>";
<<<<<<< HEAD
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
=======
  const BASE_URL = 'https://api.petsfolio.in/api/';
  const PUSHER_KEY = '3c3318df787d87828310';
  const userId = "<?= $chat->user_id ?>";

  const barkSound = new Audio('<?= base_url() ?>public/assets/sounds/bark.mp3');

  function scrollToBottom(force = false) {
    const el = document.getElementById('chatBody');
    if (!el) return;
    const near = el.scrollHeight - el.scrollTop - el.clientHeight < 120;
    if (force || near) requestAnimationFrame(() => {
      el.scrollTop = el.scrollHeight;
    });
  }

  function fmtTime(iso) {
    return new Date(iso).toLocaleString('en-US', {
      hour: 'numeric',
      minute: '2-digit',
      hour12: true
    });
  }

  function normalizeMessage(raw) {
    if (!raw) return null;
    let data = (typeof raw === 'object' && raw.message && typeof raw.message === 'object') ? raw.message : raw;

    let text = '';
    if (typeof data.message === 'string') {
      text = data.message;
    } else if (typeof raw.message === 'string') {
      text = raw.message;
    } else if (data.text) {
      text = data.text;
    }

    let senderType = data.sender_type || data.sender || (data.is_support ? 'support' : 'client');
    if (senderType === 'user') senderType = 'client';

    let id = data.id || raw.id || ('msg-' + Date.now() + '-' + Math.random().toString(36).substring(2, 7));
    let createdAt = data.created_at || raw.created_at || data.timestamp || new Date().toISOString();
    let sName = data.support_name || data.sender_name || data.name || (senderType === 'support' ? (senderName || 'Support') : '<?= esc($chat->name) ?>');
    let attachments = data.attachments || data.file_url || null;
    let isDeleted = data.is_deleted ? 1 : 0;
    let deletedBy = data.deleted_by || null;

    return {
      id: id,
      message: text,
      sender_type: senderType,
      support_name: sName,
      created_at: createdAt,
      attachments: attachments,
      is_deleted: isDeleted,
      deleted_by: deletedBy
    };
  }

  function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  // Delete message functionality
  function deleteMessage(msgId) {
    if (!msgId) return;

    Swal.fire({
      title: 'Delete this message?',
      text: 'The message will be marked as deleted for all participants.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#ef4444',
      cancelButtonColor: '#64748b',
      confirmButtonText: 'Yes, delete it',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        fetch('<?= base_url("support-ai/delete-message") ?>', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `message_id=${encodeURIComponent(msgId)}&ticket_id=${encodeURIComponent(currentChatId)}`
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'ok') {
            onMessageDeleted(data);
            Swal.fire({
              toast: true,
              position: 'top-end',
              icon: 'success',
              title: 'Message deleted',
              showConfirmButton: false,
              timer: 2000
            });
          } else {
            Swal.fire('Error', data.message || 'Could not delete message.', 'error');
          }
        })
        .catch(err => {
          console.error('Delete message error:', err);
          Swal.fire('Error', 'Network error while deleting message.', 'error');
        });
      }
    });
  }

  function onMessageDeleted(data) {
    const msgId = data.message_id;
    const deletedBy = data.deleted_by || 'Admin';
    const msgEl = document.querySelector(`[data-msg-id="${msgId}"]`);
    if (msgEl) {
      const bubbleGroup = msgEl.querySelector('.msg-bubble-group');
      if (bubbleGroup) {
        const bubble = bubbleGroup.querySelector('.msg-bubble');
        if (bubble) {
          bubble.outerHTML = `<div class="deleted-msg-box"><i class="bi bi-slash-circle text-danger fs-6"></i><span>This message was deleted by <strong>${escapeHtml(deletedBy)}</strong></span></div>`;
        }
      }
      const delBtns = msgEl.querySelectorAll('.btn-delete-msg');
      delBtns.forEach(btn => btn.remove());
    }
  }

  function addNewMessage(rawMsg) {
    const msg = normalizeMessage(rawMsg);
    if (!msg || (!msg.message && !msg.attachments)) return;

    const chatBody = document.getElementById('chatBody');
    if (!chatBody) return;

    if (msg.id && document.querySelector(`[data-msg-id="${msg.id}"]`)) {
      return;
    }

    const isSupport = msg.sender_type === 'support';
    const isTemp = (typeof msg.id === 'string' && msg.id.startsWith('temp-'));
    const isDeleted = Boolean(msg.is_deleted);

    if (isSupport && !isTemp) {
      const tempEls = document.querySelectorAll('[data-temp-msg="true"]');
      tempEls.forEach(el => el.remove());
    }

    const avatarInitial = isSupport ?
      (msg.support_name || 'S').charAt(0).toUpperCase() :
      ('<?= esc($chat->name) ?>' || 'C').charAt(0).toUpperCase();

    let attach = '';
    if (msg.attachments) {
      attach = /\.(jpg|jpeg|png|gif|webp)$/i.test(msg.attachments) ?
        `<a href="https://petsfolio.in/${msg.attachments}" target="_blank">
           <img src="https://petsfolio.in/${msg.attachments}" class="bubble-img-preview" alt="Attachment">
         </a>` :
        `<a href="https://petsfolio.in/${msg.attachments}" target="_blank" class="btn btn-sm btn-light border rounded-3 mb-2 d-inline-flex align-items-center gap-1">
           <i class="bi bi-paperclip"></i> View Document
         </a>`;
    }

    const displayName = isSupport ? (msg.support_name || 'Support Team') : ('<?= esc($chat->name) ?>' || 'Customer');

    chatBody.insertAdjacentHTML('beforeend', `
      <div class="msg-row-container ${isSupport ? 'msg-support' : 'msg-client'}" data-msg-id="${msg.id}" data-sender-type="${msg.sender_type}" ${isTemp ? 'data-temp-msg="true"' : ''}>
        <div class="msg-avatar-circle ${isSupport ? 'support-avatar' : 'client-avatar'}">
          ${avatarInitial}
        </div>
        ${!isDeleted ? `
          <button type="button" class="btn-delete-msg" onclick="deleteMessage('${msg.id}')" title="Delete message">
            <i class="bi bi-trash3"></i>
          </button>
        ` : ''}
        <div class="msg-bubble-group">
          ${isDeleted ? `
            <div class="deleted-msg-box">
              <i class="bi bi-slash-circle text-danger fs-6"></i>
              <span>This message was deleted by <strong>${escapeHtml(msg.deleted_by || 'Admin')}</strong></span>
            </div>
          ` : `
            <div class="msg-bubble">
              ${attach}
              ${msg.message ? `<p>${escapeHtml(msg.message)}</p>` : ''}
            </div>
          `}
          <div class="msg-meta-line">
            <span class="msg-sender-name">${displayName}</span>
            <span>·</span>
            <span>${fmtTime(msg.created_at)}</span>
            ${!isDeleted ? `
              <span>·</span>
              <span class="btn-delete-link" onclick="deleteMessage('${msg.id}')" title="Delete message">
                <i class="bi bi-trash3 text-danger"></i> <span class="text-danger">Delete</span>
              </span>
            ` : ''}
          </div>
        </div>
      </div>
    `);

    barkSound.play().catch(() => {});
    scrollToBottom(true);
  }

  function updateTicketStatus(status) {
    fetch('<?= base_url("supportticket/updateStatus") ?>', {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `chat_id=${currentChatId}&status=${status}`,
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === "ok") {
          Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Status updated', timer: 2000, showConfirmButton: false });
          setTimeout(() => { window.location.href = '<?= base_url() ?>support'; }, 1000);
        } else {
          Swal.fire('Error', 'Failed to update status.', 'error');
        }
      })
      .catch(err => {
        console.error("Error updating status:", err);
      });
  }

  function setQuickReply(text) {
    const el = document.getElementById('messageInput');
    if (el) {
      el.value = text;
      el.focus();
    }
  }

  function askForRating() {
    return fetch(`${BASE_URL.replace(/\/+$/, '')}/chat/askRating/${currentChatId}`, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `sender_id=${senderId}&sender_type=support`
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === "ok") {
          Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Rating request sent!', timer: 2500, showConfirmButton: false });
        }
      });
  }

  function askForBankDetails() {
    return fetch(`${BASE_URL.replace(/\/+$/, '')}/chat/askBankDetails/${currentChatId}`, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `sender_id=${senderId}&sender_type=support`
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === "ok") {
          Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Bank details requested!', timer: 2500, showConfirmButton: false });
        }
      });
  }

  function showRefundAmount() {
    proceedWithRefundPopup();
  }

  function proceedWithRefundPopup() {
>>>>>>> origin/main
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
<<<<<<< HEAD
            <input type="text" id="swal-holder" class="form-control form-control-sm" value="${details.name}">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-bold">Bank Name</label>
            <input type="text" id="swal-bank" class="form-control form-control-sm" value="${details.bank}">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-bold">Account Number</label>
            <input type="text" id="swal-account" class="form-control form-control-sm" value="${details.account}">
=======
            <input type="text" id="swal-holder" class="form-control form-control-sm" placeholder="Account Holder">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-bold">Account Number</label>
            <input type="text" id="swal-account" class="form-control form-control-sm" placeholder="Account No">
>>>>>>> origin/main
          </div>
          <div class="row">
            <div class="col-md-6 mb-2">
               <label class="form-label small fw-bold">IFSC Code</label>
<<<<<<< HEAD
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
=======
               <input type="text" id="swal-ifsc" class="form-control form-control-sm" placeholder="IFSC">
            </div>
            <div class="col-md-6 mb-2">
               <label class="form-label small fw-bold">Bank Name</label>
               <input type="text" id="swal-bank" class="form-control form-control-sm" placeholder="Bank Name">
            </div>
          </div>
>>>>>>> origin/main
        </div>
      `,
      preConfirm: () => {
        return {
          user_id: userId,
          amount: document.getElementById('swal-amount').value,
          account_holder_name: document.getElementById('swal-holder').value,
          account_number: document.getElementById('swal-account').value,
          bank_name: document.getElementById('swal-bank').value,
<<<<<<< HEAD
          ifsc_code: document.getElementById('swal-ifsc').value,
          city: document.getElementById('swal-city').value,
          branch: document.getElementById('swal-branch').value
=======
          ifsc_code: document.getElementById('swal-ifsc').value
>>>>>>> origin/main
        }
      },
      icon: 'info',
      confirmButtonText: 'Process Refund',
      showCancelButton: true,
      cancelButtonText: 'Cancel',
      confirmButtonColor: '#dc2626',
      didOpen: () => {
<<<<<<< HEAD
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
=======
        fetch(`${BASE_URL}chat/getUserWallet/${userId}`)
          .then(res => res.json())
          .then(data => {
            document.getElementById('swal-amount').value = (data && data.amount) ? data.amount : '0.00';
          })
          .catch(() => {
            document.getElementById('swal-amount').value = '0.00';
>>>>>>> origin/main
          });
      }
    }).then((result) => {
      if (result.isConfirmed) {
        processRefund(result.value);
      }
    });
  }

  function processRefund(data) {
<<<<<<< HEAD
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
=======
    if (!data.account_number || !data.amount || data.amount == '0.00') {
      Swal.fire('Invalid Data', 'Please provide valid account details and balance.', 'error');
      return;
    }

    fetch(`<?= base_url() ?>chat/processRefund`, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
>>>>>>> origin/main
        body: new URLSearchParams(data).toString()
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === "ok") {
          Swal.fire('Success', data.message, 'success');
        } else {
          Swal.fire('Error', data.message || 'Failed to process refund.', 'error');
        }
<<<<<<< HEAD
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
=======
      });
  }

>>>>>>> origin/main
  function checkRefundVisibility() {
    const chatBody = document.getElementById('chatBody');
    const refundBtn = document.getElementById('refundAmountBtn');
    if (!chatBody || !refundBtn) return;

    const content = chatBody.innerText.toLowerCase();
<<<<<<< HEAD
    const keywords = ['refund', 'return money', 'money back', 'reimburse', 'pay back', 'withdraw', 'withdrawn'];
    const found = keywords.some(k => content.includes(k));

    if (found) {
=======
    const keywords = ['refund', 'return money', 'money back', 'reimburse', 'pay back', 'withdraw'];
    if (keywords.some(k => content.includes(k))) {
>>>>>>> origin/main
      refundBtn.style.display = 'inline-block';
    } else {
      refundBtn.style.display = 'none';
    }
  }

<<<<<<< HEAD
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
=======
  /* ── Pusher ── */
  function initPusher() {
    const pusher = new Pusher(PUSHER_KEY, { cluster: 'ap2', forceTLS: true });
    const ch1 = pusher.subscribe('chat-channel-' + currentChatId);
    const ch2 = pusher.subscribe('ticket-' + currentChatId);

    function onIncomingNewMessage(data) {
      const msgObj = normalizeMessage(data);
      if (!msgObj || (!msgObj.message && !msgObj.attachments)) return;

      addNewMessage(msgObj);
      checkRefundVisibility();

      if (msgObj.sender_type !== 'support') {
        if (currentSupportMode === 'ai') {
          triggerAiAutoSend(msgObj);
        } else {
          generateAiReply(false);
        }
      }
    }

    ch1.bind('new-message', onIncomingNewMessage);
    ch2.bind('new-message', onIncomingNewMessage);
    ch1.bind('message-deleted', onMessageDeleted);
    ch2.bind('message-deleted', onMessageDeleted);

    function onTypingEvent({ isTyping, user_id }) {
      const el = document.getElementById('typingIndicator');
      if (!el) return;
      if (isTyping && user_id !== senderId) {
        el.innerHTML = `<span class="typing-dots"><span></span><span></span><span></span></span> <small style="color:#6366f1;font-weight:600">Customer is typing…</small>`;
      } else {
        el.innerHTML = '';
      }
    }

    ch1.bind('typing', onTypingEvent);
    ch2.bind('typing', onTypingEvent);
  }

  /* ── AI MODE & HUMAN MODE COPILOT ENGINE ── */
  let currentSupportMode = 'human'; // Default to Human Mode
  let currentAiDraft = '';
  let isAiAutoSending = false;

  function setSupportMode(mode, saveToServer = true) {
    const prevMode = currentSupportMode;
    currentSupportMode = mode;
    const btnAi = document.getElementById('btnModeAI');
    const btnHuman = document.getElementById('btnModeHuman');
    const aiBox = document.getElementById('aiCopilotBox');
    const aiModeBadge = document.getElementById('aiModeBadge');
    const aiCopilotStatus = document.getElementById('aiCopilotStatus');

    if (mode === 'ai') {
      if (btnAi) {
        btnAi.className = 'segmented-mode-btn active-ai';
      }
      if (btnHuman) {
        btnHuman.className = 'segmented-mode-btn';
      }
      if (aiBox) aiBox.style.display = 'block';
      if (aiModeBadge) {
        aiModeBadge.style.background = '#6366f1';
        aiModeBadge.innerHTML = '<i class="bi bi-robot me-1 text-warning"></i> AI Auto-Send Mode Active';
      }
      if (aiCopilotStatus) {
        aiCopilotStatus.textContent = 'Auto-Reply active for incoming customer messages';
      }

      if (saveToServer && prevMode !== 'ai') {
        Swal.fire({
          title: '⚡ AI Auto-Reply Mode Enabled',
          text: 'Incoming customer messages will now be automatically answered using the official Petsfolio AI Knowledge Base.',
          icon: 'info',
          confirmButtonColor: '#6366f1',
          confirmButtonText: 'Understood'
        });
      }

      checkUnrepliedCustomerMessage();
    } else {
      if (btnHuman) {
        btnHuman.className = 'segmented-mode-btn active-human';
      }
      if (btnAi) {
        btnAi.className = 'segmented-mode-btn';
      }
      if (aiBox) aiBox.style.display = 'block';
      if (aiModeBadge) {
        aiModeBadge.style.background = '#0284c7';
        aiModeBadge.innerHTML = '<i class="bi bi-person-check-fill me-1"></i> Human Mode';
      }
      if (aiCopilotStatus) {
        aiCopilotStatus.textContent = 'Review AI suggestion below before sending';
      }

      if (saveToServer && prevMode !== 'human') {
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'success',
          title: '👤 Human Mode Activated (Manual Review)',
          showConfirmButton: false,
          timer: 2500
        });
      }

      generateAiReply(false);
    }

    if (saveToServer) {
      fetch('<?= base_url("support-ai/toggle-mode") ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `ticket_id=${currentChatId}&mode=${mode}`
      }).catch(e => console.error('Mode toggle save error:', e));
    }
  }

  function checkUnrepliedCustomerMessage() {
    const messageElements = document.querySelectorAll('#chatBody .msg-row-container');
    if (!messageElements || messageElements.length === 0) return;

    const lastMsgEl = messageElements[messageElements.length - 1];
    const lastSenderType = lastMsgEl.getAttribute('data-sender-type');

    if (lastSenderType && lastSenderType !== 'support') {
      if (currentSupportMode === 'ai') {
        triggerAiAutoSend();
      } else {
        generateAiReply(false);
      }
    }
  }

  function triggerAiAutoSend(triggerMsg = null) {
    if (isAiAutoSending) return;
    isAiAutoSending = true;

    const typingIndicator = document.getElementById('typingIndicator');
    if (typingIndicator) {
      typingIndicator.innerHTML = '<span class="badge text-white rounded-pill px-3 py-1 shadow-sm" style="background:#6366f1; font-size:0.75rem;"><i class="bi bi-robot me-1 text-warning"></i> AI Auto-Replying...</span>';
    }

    fetch('<?= base_url("support-ai/generate-reply") ?>', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `ticket_id=${currentChatId}&user_type=client`
    })
    .then(res => res.json())
    .then(data => {
      if (typingIndicator) typingIndicator.innerHTML = '';
      isAiAutoSending = false;

      if (data.status && data.reply && currentSupportMode === 'ai') {
        currentAiDraft = data.reply;
        const textEl = document.getElementById('aiSuggestedText');
        if (textEl) textEl.textContent = data.reply;
        sendDirectMessage(data.reply);
      }
    })
    .catch(err => {
      if (typingIndicator) typingIndicator.innerHTML = '';
      isAiAutoSending = false;
    });
  }

  function generateAiReply(force = false) {
    const aiBox = document.getElementById('aiCopilotBox');
    const statusEl = document.getElementById('aiCopilotStatus');
    const textEl = document.getElementById('aiSuggestedText');

    if (aiBox) aiBox.style.display = 'block';
    if (statusEl) {
      statusEl.innerHTML = '<span class="spinner-border spinner-border-sm text-primary me-1" style="width: 0.75rem; height: 0.75rem;"></span> Generating smart AI suggestion...';
    }
    if (textEl && force) {
      textEl.innerHTML = '<em class="text-muted">Analyzing customer inquiry and crafting response...</em>';
    }

    fetch('<?= base_url("support-ai/generate-reply") ?>', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `ticket_id=${currentChatId}&user_type=client`
    })
    .then(res => res.json())
    .then(data => {
      if (data.status && data.reply) {
        currentAiDraft = data.reply;
        if (textEl) textEl.textContent = data.reply;
        if (statusEl) {
          statusEl.innerHTML = (currentSupportMode === 'ai')
            ? '<span class="text-success"><i class="bi bi-lightning-charge-fill me-1"></i>AI Auto-Send Ready</span>'
            : '<span class="text-primary"><i class="bi bi-stars me-1"></i>AI Suggestion Ready (Click Send)</span>';
        }
      } else {
        if (textEl) textEl.textContent = data.reply || 'Hello! Welcome to Petsfolio. How can we help you and your pet today?';
        if (statusEl) statusEl.textContent = 'Standard Suggestion';
      }
    })
    .catch(err => {
      if (statusEl) statusEl.textContent = 'AI offline. Ready for manual typing.';
    });
  }

  function applyAndSendAi() {
    const textEl = document.getElementById('aiSuggestedText');
    const msg = textEl ? textEl.textContent.trim() : currentAiDraft;
    if (!msg || msg.startsWith('Analyzing') || msg.startsWith('AI Copilot ready')) return;
    sendDirectMessage(msg);
  }

  function insertAiIntoInput() {
    const textEl = document.getElementById('aiSuggestedText');
    const msg = textEl ? textEl.textContent.trim() : currentAiDraft;
    if (!msg || msg.startsWith('Analyzing') || msg.startsWith('AI Copilot ready')) return;
    const messageInput = document.getElementById('messageInput');
    if (messageInput) {
      messageInput.value = msg;
      messageInput.focus();
    }
  }

  function dismissAiSuggestion() {
    const aiBox = document.getElementById('aiCopilotBox');
    if (aiBox) aiBox.style.display = 'none';
  }

  function polishDraft() {
    const messageInput = document.getElementById('messageInput');
    const btnPolish = document.getElementById('btnPolishDraft');
    const currentText = messageInput ? messageInput.value.trim() : '';

    if (!currentText) {
      Swal.fire('Info', 'Please type a draft message first to polish with AI.', 'info');
      return;
    }

    const origBtnHtml = btnPolish ? btnPolish.innerHTML : '';
    if (btnPolish) {
      btnPolish.disabled = true;
      btnPolish.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Polishing...';
    }

    fetch('<?= base_url("support-ai/rewrite-draft") ?>', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `draft_text=${encodeURIComponent(currentText)}&user_type=client`
    })
    .then(res => res.json())
    .then(data => {
      if (data.status && data.polished_text) {
        if (messageInput) {
          messageInput.value = data.polished_text;
          messageInput.focus();
        }
      }
    })
    .finally(() => {
      if (btnPolish) {
        btnPolish.disabled = false;
        btnPolish.innerHTML = origBtnHtml;
      }
    });
  }

  function sendDirectMessage(messageText) {
    const message = (messageText || '').trim();
    if (!message) return;

>>>>>>> origin/main
    const tempId = 'temp-' + Date.now();
    const tempMessage = {
      id: tempId,
      message: message,
      sender_type: 'support',
<<<<<<< HEAD
      support_name: senderName,
      created_at: new Date().toISOString()
=======
      support_name: senderName || 'Support',
      created_at: new Date().toISOString(),
      is_temp: true
>>>>>>> origin/main
    };

    addNewMessage(tempMessage);

<<<<<<< HEAD
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
=======
    const formData = new FormData();
    formData.append('chat_id', currentChatId);
    formData.append('ticket_id', currentChatId);
    formData.append('sender_type', 'support');
    formData.append('sender_id', senderId || 1);
    formData.append('message', message);

    const localApiUrl = '<?= base_url("support-ai/send-message") ?>';

    fetch(localApiUrl, { method: 'POST', body: formData })
    .then(response => response.json())
    .then(data => {
      if (data.status === 'ok') {
        const tempElement = document.querySelector(`[data-msg-id="${tempId}"]`);
        if (tempElement && data.message_id) {
          tempElement.setAttribute('data-msg-id', data.message_id);
          tempElement.removeAttribute('data-temp-msg');
          const delBtn = tempElement.querySelector('.btn-delete-msg');
          if (delBtn) {
            delBtn.setAttribute('onclick', `deleteMessage(${data.message_id})`);
          }
        }
      }
    })
    .catch(error => {
      const fallbackUrl = BASE_URL.replace(/\/+$/, '') + '/chat/send';
      fetch(fallbackUrl, { method: 'POST', body: formData }).catch(e => console.error('Fallback error:', e));
    });
  }

  function sendMessage() {
    const messageInput = document.getElementById('messageInput');
    const message = messageInput ? messageInput.value.trim() : '';
    if (!message) return;
    messageInput.value = '';
    sendDirectMessage(message);
  }

  document.addEventListener('DOMContentLoaded', () => {
    scrollToBottom();
    checkRefundVisibility();
    initPusher();

    fetch('<?= base_url("support-ai/mode-status/" . $chat->id) ?>')
      .then(res => res.json())
      .then(data => {
        const initialMode = (data && data.mode) ? data.mode : 'human';
        setSupportMode(initialMode, false);
      })
      .catch(() => setSupportMode('human', false));
  });
</script>

<?php echo $this->include('admin/footer') ?>
>>>>>>> origin/main
<?php echo $this->endSection() ?>