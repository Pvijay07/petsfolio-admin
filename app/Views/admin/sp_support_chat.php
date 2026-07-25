<?php echo $this->extend('admin/includes/header'); ?>

<?php
$db = \Config\Database::connect();
if (!empty($chat->user_id)) {
  $provider = $db->table('service_providers')->where('id', $chat->user_id)->get()->getRow();
  if ($provider) {
    $chat->name    = $provider->name    ?? '';
    $chat->phone   = $provider->phone   ?? $provider->mobile ?? '';
    $chat->profile = $provider->profile ?? '';
  }
}
?>

<?php echo $this->section('content'); ?>
<style>
  /* ── Reset & tokens ───────────────────────────── */
  :root {
    --pri: #6366f1;
    --pri-light: #eef2ff;
    --pri-hover: #4f46e5;
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --radius-xl: 20px;
    --shadow-sm: 0 1px 3px rgba(0, 0, 0, .06), 0 1px 2px rgba(0, 0, 0, .04);
    --shadow-md: 0 4px 16px rgba(0, 0, 0, .07);
  }

  /* ── Layout ───────────────────────────────────── */
  .chat-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 1.5rem;
  }

  .chat-page-header .breadcrumb {
    font-size: .8rem;
    margin-bottom: .25rem;
  }

  .chat-page-title {
    font-size: 1.35rem;
    font-weight: 600;
    color: #0f172a;
    margin: 0;
  }

  /* ── Chat container ───────────────────────────── */
  .chat-container {
    display: flex;
    flex-direction: column;
    height: 620px;
    max-height: calc(100vh - 180px);
    background: #fff;
    border: 1px solid #e8ecf0;
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-md);
    margin-bottom: 2rem;
  }

  /* ── Header ───────────────────────────────────── */
  .chat-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: .875rem 1.25rem;
    background: #fff;
    border-bottom: 1px solid #f1f5f9;
    flex-shrink: 0;
  }

  .chat-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .ch-avatar-wrap {
    position: relative;
    flex-shrink: 0;
  }

  .ch-avatar {
    width: 44px;
    height: 44px;
    border-radius: var(--radius-md);
    object-fit: cover;
    display: block;
    background: #f1f5f9;
  }

  .ch-online {
    position: absolute;
    bottom: -2px;
    right: -2px;
    width: 12px;
    height: 12px;
    background: #22c55e;
    border: 2.5px solid #fff;
    border-radius: 50%;
  }

  .ch-name {
    font-size: .95rem;
    font-weight: 600;
    color: #0f172a;
    line-height: 1.3;
  }

  .ch-phone {
    font-size: .78rem;
    color: #64748b;
    margin-top: 2px;
  }

  .ch-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 4px;
  }

  .badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 9px;
    border-radius: 20px;
    font-size: .72rem;
    font-weight: 600;
    letter-spacing: .2px;
  }

  .badge-active {
    background: #fff7ed;
    color: #c2410c;
    border: 1px solid #fed7aa;
  }

  .badge-resolved {
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
  }

  .badge-ticket {
    background: #f8fafc;
    color: #475569;
    border: 1px solid #e2e8f0;
  }

  .btn-resolve {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 16px;
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
    border-radius: var(--radius-md);
    font-size: .8rem;
    font-weight: 600;
    cursor: pointer;
    transition: background .2s;
  }

  .btn-resolve:hover {
    background: #dcfce7;
  }

  /* ── Body ─────────────────────────────────────── */
  .chat-body {
    flex: 1;
    overflow-y: auto;
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 8px;
    background: #f8fafc;
    scroll-behavior: smooth;
  }

  .chat-body::-webkit-scrollbar {
    width: 4px;
  }

  .chat-body::-webkit-scrollbar-track {
    background: transparent;
  }

  .chat-body::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 4px;
  }

  /* ── Date divider ─────────────────────────────── */
  .date-divider {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 6px 0;
  }

  .date-divider span {
    font-size: .72rem;
    color: #94a3b8;
    white-space: nowrap;
    font-weight: 500;
  }

  .date-divider::before,
  .date-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e8ecf0;
  }

  /* ── Message rows ─────────────────────────────── */
  .message-row {
    display: flex;
    align-items: flex-end;
    gap: 8px;
    animation: fadeUp .35s ease forwards;
  }

  .message-row.from-sp {
    align-self: flex-start;
    max-width: 78%;
  }

  .message-row.from-support {
    align-self: flex-end;
    max-width: 78%;
    flex-direction: row-reverse;
  }

  @keyframes fadeUp {
    from {
      opacity: 0;
      transform: translateY(10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Avatars */
  .msg-avatar {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    object-fit: cover;
    flex-shrink: 0;
    display: block;
    background: #e8ecf0;
  }

  .msg-avatar-initials {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    flex-shrink: 0;
    background: var(--pri);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 600;
  }

  /* Inner column */
  .msg-inner {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }

  .from-support .msg-inner {
    align-items: flex-end;
  }

  /* Bubble */
  .bubble {
    padding: 9px 13px;
    font-size: .875rem;
    line-height: 1.6;
    word-break: break-word;
  }

  .from-sp .bubble {
    background: #fff;
    color: #0f172a;
    border: 1px solid #e8ecf0;
    border-radius: 4px 16px 16px 16px;
    box-shadow: var(--shadow-sm);
  }

  .from-support .bubble {
    background: var(--pri);
    color: #fff;
    border-radius: 16px 4px 16px 16px;
    box-shadow: 0 4px 12px rgba(99, 102, 241, .25);
  }

  .bubble p {
    margin: 0;
  }

  /* Image attachment */
  .bubble-image {
    max-width: 200px;
    border-radius: 10px;
    display: block;
    border: 1px solid rgba(0, 0, 0, .06);
  }

  .bubble-file {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: rgba(0, 0, 0, .05);
    border-radius: 8px;
    font-size: .8rem;
    font-weight: 500;
    text-decoration: none;
    color: inherit;
    margin-bottom: 6px;
  }

  .from-support .bubble-file {
    background: rgba(255, 255, 255, .2);
    color: #fff;
  }

  /* Meta line */
  .msg-meta {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: .7rem;
    color: #94a3b8;
    padding: 0 3px;
  }

  .msg-meta .sender-name {
    font-weight: 600;
    color: #64748b;
  }

  .from-support .msg-meta {
    flex-direction: row-reverse;
  }

  .from-support .msg-meta .sender-name {
    color: #818cf8;
  }

  /* ── Typing indicator ─────────────────────────── */
  .typing-wrap {
    min-height: 22px;
    font-size: .78rem;
    color: #94a3b8;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 6px;
  }

  .typing-dots span {
    display: inline-block;
    width: 5px;
    height: 5px;
    background: #94a3b8;
    border-radius: 50%;
    animation: blink 1.2s infinite;
  }

  .typing-dots span:nth-child(2) {
    animation-delay: .2s;
  }

  .typing-dots span:nth-child(3) {
    animation-delay: .4s;
  }

  @keyframes blink {

    0%,
    80%,
    100% {
      opacity: .25;
    }

    40% {
      opacity: 1;
    }
  }

  /* ── Footer ───────────────────────────────────── */
  .chat-footer {
    padding: .875rem 1.25rem;
    background: #fff;
    border-top: 1px solid #f1f5f9;
    flex-shrink: 0;
  }

  /* Quick chips */
  .quick-chips {
    display: flex;
    gap: 6px;
    overflow-x: auto;
    margin-bottom: 10px;
    scrollbar-width: none;
    padding-bottom: 2px;
  }

  .quick-chips::-webkit-scrollbar {
    display: none;
  }

  .chip {
    white-space: nowrap;
    padding: 5px 12px;
    background: #f8fafc;
    border: 1px solid #e8ecf0;
    border-radius: 20px;
    font-size: .75rem;
    color: #64748b;
    font-weight: 500;
    cursor: pointer;
    transition: all .18s;
  }

  .chip:hover {
    background: var(--pri-light);
    border-color: #a5b4fc;
    color: var(--pri);
  }

  /* Input row */
  .input-row {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f8fafc;
    border: 1px solid #e8ecf0;
    border-radius: var(--radius-lg);
    padding: 5px 5px 5px 14px;
    transition: border-color .2s, box-shadow .2s;
  }

  .input-row:focus-within {
    border-color: #a5b4fc;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, .1);
    background: #fff;
  }

  .input-row .chat-icon {
    color: #94a3b8;
    font-size: 17px;
    flex-shrink: 0;
  }

  .input-row input {
    flex: 1;
    border: none;
    background: transparent;
    font-size: .875rem;
    color: #0f172a;
    outline: none;
    padding: 6px 0;
  }

  .input-row input::placeholder {
    color: #94a3b8;
  }

  .btn-send {
    width: 38px;
    height: 38px;
    background: var(--pri);
    color: #fff;
    border: none;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    font-size: 15px;
    transition: background .2s, transform .15s;
  }

  .btn-send:hover {
    background: var(--pri-hover);
    transform: scale(1.06);
  }

  .btn-send:active {
    transform: scale(.94);
  }

  /* ── Sealed ticket state ──────────────────────── */
  .ticket-sealed {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: .875rem;
    background: #f8fafc;
    border-radius: var(--radius-md);
    font-size: .78rem;
    font-weight: 600;
    color: #94a3b8;
    letter-spacing: .3px;
    border: 1px dashed #e2e8f0;
  }
</style>

<main class="main-content">
  <div class="position-relative iq-banner">
    <?php echo $this->include('admin/nav_bar') ?>
  </div>

  <div class="container-fluid content-inner pb-0" id="page_layout">
    <div class="row">
      <div class="col-12">

        <!-- Page header -->
        <div class="chat-page-header">
          <div>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item">
                  <a href="<?= base_url('dashboard') ?>" class="text-secondary">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                  <a href="<?= base_url('sp-support') ?>" class="text-secondary">SP Support</a>
                </li>
                <li class="breadcrumb-item active fw-bold" aria-current="page">
                  #<?= esc($chat->ticket_number) ?>
                </li>
              </ol>
            </nav>
            <h3 class="chat-page-title">Support command center</h3>
          </div>
          <a href="<?= base_url('sp-support') ?>"
            class="btn btn-sm btn-light border rounded-pill px-3 d-flex align-items-center gap-1 mt-1">
            <i class="bi bi-arrow-left-short fs-5"></i> Back
          </a>
        </div>

      </div>
    </div><!-- /row -->

    <div class="row justify-content-center">
      <div class="col-lg-11 col-xl-10">

        <div class="chat-container">

          <!-- ── Header ── -->
          <div class="chat-header">
            <div class="chat-header-left">

              <div class="ch-avatar-wrap">
                <img src="<?= $chat->profile
                            ? 'https://beta.petsfolio.in/public/uploads/providers/' . $chat->profile
                            : 'https://petsfolio.in/admin/public/assets/images/avatars/user.png' ?>"
                  class="ch-avatar" alt="Provider"
                  onerror="this.src='https://petsfolio.in/admin/public/assets/images/avatars/user.png'">
                <div class="ch-online"></div>
              </div>

              <div>
                <div class="ch-name"><?= esc($chat->name) ?: 'Service Provider' ?></div>
                <div class="ch-phone">
                  <i class="bi bi-telephone-fill me-1" style="font-size:.7rem"></i>
                  <?= esc($chat->phone ?? $chat->mobile ?? 'N/A') ?>
                </div>
                <div class="ch-meta">
                  <span class="badge <?= $chat->status == 'completed' ? 'badge-resolved' : 'badge-active' ?>">
                    <i class="bi <?= $chat->status == 'completed' ? 'bi-patch-check-fill' : 'bi-lightning-charge-fill' ?>"></i>
                    <?= $chat->status == 'completed' ? 'Resolved' : 'Active' ?>
                  </span>
                  <span class="badge badge-ticket">
                    #<?= esc($chat->ticket_number) ?>
                  </span>
                  <span id="unreadCount" class="badge bg-danger px-2 py-1"
                    style="display:none;font-size:.68rem;">0</span>
                </div>
              </div>

            </div><!-- /left -->

            <?php if ($chat->status != 'completed'): ?>
              <button class="btn-resolve" onclick="updateStatusd('completed')">
                <i class="bi bi-check2-circle"></i> Resolve ticket
              </button>
            <?php endif; ?>
          </div><!-- /chat-header -->

          <!-- ── Body ── -->
          <div class="chat-body" id="chatBody">

            <?php
            $lastDate = null;
            foreach ($messages as $msg):
              $msgDate  = date('Y-m-d', strtotime($msg->created_at));
              $isSupport = ($msg->sender_type == 'support');

              // Date divider
              if ($msgDate !== $lastDate):
                $lastDate = $msgDate;
            ?>
                <div class="date-divider">
                  <span><?= date('M j, Y', strtotime($msg->created_at)) ?></span>
                </div>
              <?php endif; ?>

              <div class="message-row <?= $isSupport ? 'from-support' : 'from-sp' ?>"
                data-msg-id="<?= $msg->id ?>">

                <!-- Avatar -->
                <?php if (!$isSupport): ?>
                  <img src="<?= $chat->profile
                              ? 'https://beta.petsfolio.in/public/uploads/providers/' . $chat->profile
                              : 'https://petsfolio.in/admin/public/assets/images/avatars/user.png' ?>"
                    class="msg-avatar" alt="Provider"
                    onerror="this.src='https://petsfolio.in/admin/public/assets/images/avatars/user.png'">
                <?php else: ?>
                  <div class="msg-avatar-initials" aria-hidden="true">
                    <?= strtoupper(substr($msg->support_name ?: 'S', 0, 1)) ?>
                  </div>
                <?php endif; ?>

                <!-- Bubble -->
                <div class="msg-inner">
                  <div class="bubble">
                    <?php if ($msg->attachments): ?>
                      <?php if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $msg->attachments)): ?>
                        <a href="https://beta.petsfolio.in/<?= $msg->attachments ?>" target="_blank">
                          <img src="https://beta.petsfolio.in/<?= $msg->attachments ?>"
                            class="bubble-image" alt="Attachment">
                        </a>
                      <?php else: ?>
                        <a href="https://beta.petsfolio.in/<?= $msg->attachments ?>"
                          target="_blank" class="bubble-file">
                          <i class="bi bi-file-earmark-arrow-down"></i> View attachment
                        </a>
                      <?php endif; ?>
                    <?php endif; ?>

                    <?php if ($msg->message): ?>
                      <p><?= esc($msg->message) ?></p>
                    <?php endif; ?>
                  </div>

                  <div class="msg-meta">
                    <span class="sender-name">
                      <?= $isSupport
                        ? (esc($msg->support_name) ?: 'Support')
                        : (esc($chat->name) ?: 'Provider') ?>
                    </span>
                    <span>·</span>
                    <span><?= date('H:i', strtotime($msg->created_at)) ?></span>
                  </div>
                </div>

              </div><!-- /message-row -->
            <?php endforeach; ?>

          </div><!-- /chat-body -->

          <!-- ── Footer ── -->
          <div class="chat-footer">
            <div id="typingIndicator" class="typing-wrap"></div>

            <?php if ($chat->status != 'completed'): ?>

              <div class="quick-chips">
                <div class="chip" onclick="setQuickReply('Hello! How can I help you today?')">👋 Greeting</div>
                <div class="chip" onclick="setQuickReply('We\'re looking into this. Please hold on a moment.')">⏳ Investigating</div>
                <div class="chip" onclick="setQuickReply('Could you share more details or a screenshot?')">📸 Need details</div>
                <div class="chip" onclick="setQuickReply('Your issue has been resolved. Is there anything else?')">✅ Resolved</div>
              </div>

              <div class="input-row">
                <i class="bi bi-chat-left-dots chat-icon"></i>
                <input type="text" id="messageInput" placeholder="Write a response…"
                  onkeypress="if(event.keyCode===13) sendMessage()">
                <button class="btn-send" onclick="sendMessage()" aria-label="Send">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                    <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z" />
                  </svg>
              </div>

            <?php else: ?>

              <div class="ticket-sealed">
                <i class="bi bi-lock-fill" style="font-size:.85rem"></i>
                Ticket sealed &amp; archived
              </div>

            <?php endif; ?>
          </div><!-- /chat-footer -->

        </div><!-- /chat-container -->
      </div>
    </div><!-- /row -->
  </div>
</main>

<!-- ── Scripts ── -->
<script src="https://js.pusher.com/8.2/pusher.min.js"></script>
<script>
  const senderName = '<?= session('user')->name ?>';
  const senderId = <?= session('user')->id ?>;
  let currentChatId = "<?= $chat->id ?>";
  const BASE_URL = 'https://beta.petsfolio.in/api/';
  const PUSHER_KEY = '3c3318df787d87828310';

  const barkSound = new Audio('<?= base_url() ?>public/assets/sounds/bark.mp3');

  /* ── Helpers ── */
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
      month: 'short',
      day: 'numeric',
      hour: 'numeric',
      minute: '2-digit',
      hour12: false
    });
  }

  function buildDateDivider(iso) {
    const d = new Date(iso);
    return `<div class="date-divider">
    <span>${d.toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'})}</span>
  </div>`;
  }

  /* ── Add message to DOM ── */
  function addNewMessage(msg) {
    const chatBody = document.getElementById('chatBody');
    if (!chatBody) return;

    const isSupport = msg.sender_type === 'support';
    const avatar = isSupport ?
      `<div class="msg-avatar-initials" aria-hidden="true">${(msg.support_name||'S').charAt(0).toUpperCase()}</div>` :
      `<img src="<?= $chat->profile ? 'https://beta.petsfolio.in/public/uploads/providers/' . $chat->profile : 'https://petsfolio.in/admin/public/assets/images/avatars/user.png' ?>"
            class="msg-avatar" alt="Provider"
            onerror="this.src='https://petsfolio.in/admin/public/assets/images/avatars/user.png'">`;

    let attach = '';
    if (msg.attachments) {
      attach = /\.(jpg|jpeg|png|gif|webp)$/i.test(msg.attachments) ?
        `<a href="https://beta.petsfolio.in/${msg.attachments}" target="_blank">
           <img src="https://beta.petsfolio.in/${msg.attachments}" class="bubble-image" alt="Attachment">
         </a>` :
        `<a href="https://beta.petsfolio.in/${msg.attachments}" target="_blank" class="bubble-file">
           <i class="bi bi-file-earmark-arrow-down"></i> View attachment
         </a>`;
    }

    const displayName = isSupport ?
      (msg.support_name || 'Support') :
      ('<?= esc($chat->name) ?>' || 'Provider');

    chatBody.insertAdjacentHTML('beforeend', `
    <div class="message-row ${isSupport ? 'from-support' : 'from-sp'}" data-msg-id="${msg.id}">
      ${!isSupport ? avatar : ''}
      <div class="msg-inner">
        <div class="bubble">
          ${attach}
          ${msg.message ? `<p>${msg.message}</p>` : ''}
        </div>
        <div class="msg-meta">
          <span class="sender-name">${displayName}</span>
          <span>·</span>
          <span>${fmtTime(msg.created_at)}</span>
        </div>
      </div>
      ${isSupport ? avatar : ''}
    </div>
  `);

    barkSound.play().catch(() => {});
    scrollToBottom(true);
  }

  /* ── Send message ── */
  function sendMessage() {
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    if (!message) return;
    input.value = '';

    const tempId = 'tmp-' + Date.now();
    addNewMessage({
      id: tempId,
      message,
      sender_type: 'support',
      support_name: senderName,
      created_at: new Date().toISOString()
    });

    fetch(BASE_URL + '/chat/send', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `chat_id=${currentChatId}&message=${encodeURIComponent(message)}&sender_id=${senderId}&sender_type=support`
      })
      .then(r => r.json())
      .then(data => {
        if (data.status === 'ok') {
          document.querySelector(`[data-msg-id="${tempId}"]`)?.remove();
        }
      })
      .catch(err => console.error('Send error:', err));
  }

  /* ── Resolve ticket ── */
  function updateStatusd(status) {
    fetch(BASE_URL + '/supportticket/updateStatus', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `chat_id=${currentChatId}&status=${status}`
      })
      .then(r => r.json())
      .then(data => {
        if (data.status === 'ok') {
          alert('Ticket resolved.');
          window.location.href = '<?= base_url() ?>sp-support';
        } else {
          alert('Failed to update status.');
        }
      })
      .catch(err => {
        console.error(err);
        alert('Something went wrong.');
      });
  }

  /* ── Quick reply ── */
  function setQuickReply(text) {
    const el = document.getElementById('messageInput');
    if (el) {
      el.value = text;
      el.focus();
    }
  }

  /* ── Unread badge ── */
  let unreadCount = 0,
    windowFocused = true;
  window.addEventListener('focus', () => {
    windowFocused = true;
    unreadCount = 0;
    updateBadge();
  });
  window.addEventListener('blur', () => {
    windowFocused = false;
  });

  function updateBadge() {
    const b = document.getElementById('unreadCount');
    if (!b) return;
    b.style.display = unreadCount > 0 ? 'inline-block' : 'none';
    b.textContent = unreadCount;
  }

  /* ── Pusher ── */
  function initPusher() {
    const pusher = new Pusher(PUSHER_KEY, {
      cluster: 'ap2',
      forceTLS: true
    });
    const channel = pusher.subscribe('chat-channel-' + currentChatId);

    channel.bind('new-message', ({
      message
    }) => {
      if (!message) return;
      addNewMessage(message);
      if (message.sender_type !== 'support' && !windowFocused) {
        unreadCount++;
        updateBadge();
      }
    });

    channel.bind('typing', ({
      isTyping,
      user_id
    }) => {
      const el = document.getElementById('typingIndicator');
      if (!el) return;
      if (isTyping && user_id !== senderId) {
        el.innerHTML = `<span class="typing-dots"><span></span><span></span><span></span></span>
                      <small style="color:#6366f1;font-weight:600">Provider is typing…</small>`;
      } else {
        el.innerHTML = '';
      }
    });
  }

  /* ── Init ── */
  document.addEventListener('DOMContentLoaded', () => {
    scrollToBottom();
    initPusher();
  });
</script>

<?php echo $this->include('admin/footer') ?>
<?php echo $this->endSection() ?>