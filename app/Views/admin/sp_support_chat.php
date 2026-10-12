<?php echo $this->extend('admin/includes/header'); ?>

<?php
$db = \Config\Database::connect();
if (!empty($chat->user_id)) {
  $provider = $db->table('service_providers')->where('id', $chat->user_id)->get()->getRow();
  if ($provider) {
<<<<<<< HEAD
    $chat->name    = $provider->name    ?? '';
    $chat->phone   = $provider->phone   ?? $provider->mobile ?? '';
    $chat->profile = $provider->profile ?? '';
=======
    $chat->name    = $provider->name    ?? $chat->name ?? '';
    $chat->phone   = $provider->phone   ?? $provider->mobile ?? $chat->phone ?? '';
    $chat->profile = $provider->profile ?? $chat->profile ?? '';
    $chat->email   = $provider->email   ?? '';
>>>>>>> origin/main
  }
}
?>

<?php echo $this->section('content'); ?>
<style>
<<<<<<< HEAD
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
=======
  /* ── Premium SP Support Console Design Tokens ── */
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
>>>>>>> origin/main
    position: relative;
    flex-shrink: 0;
  }

<<<<<<< HEAD
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
=======
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
>>>>>>> origin/main
    display: flex;
    flex-direction: column;
    gap: 4px;
  }

<<<<<<< HEAD
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
=======
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
>>>>>>> origin/main
    font-weight: 600;
    color: #64748b;
  }

<<<<<<< HEAD
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
=======
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
>>>>>>> origin/main
    color: #94a3b8;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 6px;
<<<<<<< HEAD
    margin-bottom: 6px;
  }

=======
    margin-bottom: 4px;
  }
>>>>>>> origin/main
  .typing-dots span {
    display: inline-block;
    width: 5px;
    height: 5px;
    background: #94a3b8;
    border-radius: 50%;
    animation: blink 1.2s infinite;
  }
<<<<<<< HEAD

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
=======
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
          <!-- Provider Profile & Metadata -->
          <div class="customer-profile-card">
            <div class="customer-avatar-wrap">
              <?php
              $profileImage = !empty($chat->profile) ? "https://beta.petsfolio.in/public/uploads/providers/{$chat->profile}" : '';
              ?>
              <?php if (!empty($profileImage)): ?>
                <img src="<?= $profileImage ?>" class="customer-avatar" alt="Provider"
                  onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="customer-avatar-fallback" style="display:none;"><?= strtoupper(substr($chat->name ?: 'P', 0, 1)) ?></div>
              <?php else: ?>
                <div class="customer-avatar-fallback"><?= strtoupper(substr($chat->name ?: 'P', 0, 1)) ?></div>
              <?php endif; ?>
              <div class="online-indicator"></div>
            </div>

            <div>
              <div class="customer-meta-name">
                <?= esc($chat->name) ?: 'Service Provider' ?>
                <span class="badge rounded-pill bg-light text-secondary border fw-bold" style="font-size: 0.72rem;">
                  #<?= esc($chat->ticket_number ?? $chat->id) ?>
                </span>
                <span class="badge rounded-pill bg-info text-white" style="font-size: 0.65rem;">Provider</span>
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

            <?php if ($chat->status != 'resolved'): ?>
              <button class="btn btn-sm btn-success rounded-pill px-3 fw-bold shadow-sm d-inline-flex align-items-center gap-1" style="font-size: 0.78rem;" onclick="updateStatusd('resolved')">
                <i class="bi bi-check2-circle"></i> Resolve
              </button>
            <?php endif; ?>

            <!-- Back to Support List -->
            <a href="<?= base_url('sp-support') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold d-none d-md-inline-flex align-items-center gap-1" style="font-size: 0.78rem;">
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
                <?= $isSupport ? strtoupper(substr($msg->support_name ?: 'S', 0, 1)) : strtoupper(substr($chat->name ?: 'P', 0, 1)) ?>
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
>>>>>>> origin/main
                        </a>
                      <?php endif; ?>
                    <?php endif; ?>

                    <?php if ($msg->message): ?>
                      <p><?= esc($msg->message) ?></p>
                    <?php endif; ?>
                  </div>
<<<<<<< HEAD

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
=======
                <?php endif; ?>

                <div class="msg-meta-line">
                  <span class="msg-sender-name">
                    <?= $isSupport ? (esc($msg->support_name) ?: 'Support Team') : (esc($chat->name) ?: 'Provider') ?>
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

            </div>
          <?php endforeach; ?>
        </div>

        <!-- ── Chat Desk Footer ── -->
        <div class="chat-desk-footer">
          <div id="typingIndicator" class="typing-indicator-bar"></div>

          <?php if ($chat->status != 'resolved' && $chat->status != 'completed'): ?>

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
              <div class="quick-chip" onclick="setQuickReply('Hello! Welcome to Petsfolio Partner Support. How can we help you today?')">👋 Greeting</div>
              <div class="quick-chip" onclick="setQuickReply('We are reviewing your payout and booking details. Please hold on.')">⏳ Checking Payout</div>
              <div class="quick-chip" onclick="setQuickReply('Could you please upload a screenshot or additional details?')">📸 Need Details</div>
              <div class="quick-chip" onclick="setQuickReply('Your verification documents are approved. You can start accepting bookings.')">✅ KYC Approved</div>
              <div class="quick-chip" onclick="setQuickReply('Your issue has been resolved. Let us know if you need anything else!')">✅ Issue Resolved</div>
            </div>

            <!-- Main Input Dock -->
            <div class="chat-input-dock">
              <i class="bi bi-chat-text text-primary fs-5"></i>
              <input type="text" id="messageInput" placeholder="Type a response or use AI suggestion…"
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
>>>>>>> origin/main
<script src="https://js.pusher.com/8.2/pusher.min.js"></script>
<script>
  const senderName = '<?= session('user')->name ?>';
  const senderId = <?= session('user')->id ?>;
  let currentChatId = "<?= $chat->id ?>";
<<<<<<< HEAD
  const BASE_URL = 'https://beta.petsfolio.in/api/';
=======
  const BASE_URL = 'https://api.petsfolio.in/api/';
>>>>>>> origin/main
  const PUSHER_KEY = '3c3318df787d87828310';

  const barkSound = new Audio('<?= base_url() ?>public/assets/sounds/bark.mp3');

<<<<<<< HEAD
  /* ── Helpers ── */
=======
>>>>>>> origin/main
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
<<<<<<< HEAD
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
=======
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

    let senderType = data.sender_type || data.sender || (data.is_support ? 'support' : 'provider');
    if (senderType === 'user') senderType = 'provider';

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
      ('<?= esc($chat->name) ?>' || 'P').charAt(0).toUpperCase();
>>>>>>> origin/main

    let attach = '';
    if (msg.attachments) {
      attach = /\.(jpg|jpeg|png|gif|webp)$/i.test(msg.attachments) ?
<<<<<<< HEAD
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
=======
        `<a href="https://petsfolio.in/${msg.attachments}" target="_blank">
           <img src="https://petsfolio.in/${msg.attachments}" class="bubble-img-preview" alt="Attachment">
         </a>` :
        `<a href="https://petsfolio.in/${msg.attachments}" target="_blank" class="btn btn-sm btn-light border rounded-3 mb-2 d-inline-flex align-items-center gap-1">
           <i class="bi bi-paperclip"></i> View Document
         </a>`;
    }

    const displayName = isSupport ? (msg.support_name || 'Support Team') : ('<?= esc($chat->name) ?>' || 'Provider');

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
>>>>>>> origin/main

    barkSound.play().catch(() => {});
    scrollToBottom(true);
  }

<<<<<<< HEAD
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
=======
  function updateStatusd(status) {
    fetch('<?= base_url("supportticket/updateStatus") ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
>>>>>>> origin/main
        body: `chat_id=${currentChatId}&status=${status}`
      })
      .then(r => r.json())
      .then(data => {
        if (data.status === 'ok') {
<<<<<<< HEAD
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
=======
          Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Ticket resolved', timer: 2000, showConfirmButton: false });
          setTimeout(() => { window.location.href = '<?= base_url() ?>sp-support'; }, 1000);
        } else {
          Swal.fire('Error', 'Failed to update status.', 'error');
        }
      });
  }

>>>>>>> origin/main
  function setQuickReply(text) {
    const el = document.getElementById('messageInput');
    if (el) {
      el.value = text;
      el.focus();
    }
  }

<<<<<<< HEAD
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
        el.innerHTML = `<span class="typing-dots"><span></span><span></span><span></span></span> <small style="color:#6366f1;font-weight:600">Provider is typing…</small>`;
      } else {
        el.innerHTML = '';
      }
    }

    ch1.bind('typing', onTypingEvent);
    ch2.bind('typing', onTypingEvent);
  }

  /* ── AI MODE & HUMAN MODE COPILOT ENGINE ── */
  let currentSupportMode = 'human';
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
      if (btnAi) btnAi.className = 'segmented-mode-btn active-ai';
      if (btnHuman) btnHuman.className = 'segmented-mode-btn';
      if (aiBox) aiBox.style.display = 'block';
      if (aiModeBadge) {
        aiModeBadge.style.background = '#6366f1';
        aiModeBadge.innerHTML = '<i class="bi bi-robot me-1 text-warning"></i> AI Auto-Send Mode Active';
      }
      if (aiCopilotStatus) {
        aiCopilotStatus.textContent = 'Auto-Reply active for incoming provider inquiries';
      }

      if (saveToServer && prevMode !== 'ai') {
        Swal.fire({
          title: '⚡ AI Auto-Reply Mode Enabled',
          text: 'Incoming provider inquiries will now be automatically answered using the official Petsfolio AI Knowledge Base.',
          icon: 'info',
          confirmButtonColor: '#6366f1',
          confirmButtonText: 'Understood'
        });
      }

      checkUnrepliedCustomerMessage();
    } else {
      if (btnHuman) btnHuman.className = 'segmented-mode-btn active-human';
      if (btnAi) btnAi.className = 'segmented-mode-btn';
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
      body: `ticket_id=${currentChatId}&user_type=provider`
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
      textEl.innerHTML = '<em class="text-muted">Analyzing provider inquiry and crafting response...</em>';
    }

    fetch('<?= base_url("support-ai/generate-reply") ?>', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `ticket_id=${currentChatId}&user_type=provider`
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
        if (textEl) textEl.textContent = data.reply || 'Hello! How can we assist you with your bookings or profile today?';
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
      body: `draft_text=${encodeURIComponent(currentText)}&user_type=provider`
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

    const tempId = 'temp-' + Date.now();
    const tempMessage = {
      id: tempId,
      message: message,
      sender_type: 'support',
      support_name: senderName || 'Support',
      created_at: new Date().toISOString(),
      is_temp: true
    };

    addNewMessage(tempMessage);

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
    initPusher();

    fetch('<?= base_url("support-ai/mode-status/" . $chat->id) ?>')
      .then(res => res.json())
      .then(data => {
        const initialMode = (data && data.mode) ? data.mode : 'human';
        setSupportMode(initialMode, false);
      })
      .catch(() => setSupportMode('human', false));
>>>>>>> origin/main
  });
</script>

<?php echo $this->include('admin/footer') ?>
<?php echo $this->endSection() ?>