<?php echo $this->extend('admin/includes/header'); ?>
<?php echo $this->section('content'); ?>

<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --chat-sidebar-width: 400px;
        --chat-primary: #6366f1;
        --chat-primary-hover: #4f46e5;
        --chat-primary-light: #f5f3ff;
        --chat-bg-main: #fcfcfd;
        --chat-bg-sidebar: #ffffff;
        --chat-text-main: #0f172a;
        --chat-text-muted: #64748b;
        --chat-border: #f1f5f9;
        --chat-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
        --chat-shadow-lg: 0 10px 40px -4px rgba(0, 0, 0, 0.08);
    }

    body {
        font-family: 'Outfit', sans-serif;
        background-color: #f8fafc;
    }

    .chat-wrapper {
        display: flex;
        height: calc(100vh - 140px);
        background: #fff;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: var(--chat-shadow-lg);
        border: 1px solid var(--chat-border);
        margin: 24px;
        position: relative;
    }

    /* Sidebar */
    .chat-sidebar {
<<<<<<< HEAD
        width: var(--chat-sidebar-width);
        border-right: 1px solid var(--chat-border);
        display: flex;
        flex-direction: column;
        background: var(--chat-bg-sidebar);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 10;
    }

    .sidebar-header {
        padding: 32px 24px 24px;
        border-bottom: 2px solid #f8fafc;
    }

    .sidebar-header h4 {
        margin: 0 0 20px 0;
        font-weight: 700;
        font-size: 1.25rem;
        color: var(--chat-text-main);
        letter-spacing: -0.01em;
=======
        width: 380px !important;
        min-width: 380px !important;
        max-width: 380px !important;
        flex: 0 0 380px !important;
        border-right: 1px solid var(--chat-border);
        display: flex !important;
        flex-direction: column !important;
        background: var(--chat-bg-sidebar);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 10;
        overflow: hidden;
    }

    .chat-sidebar-header {
        padding: 20px 20px 16px;
        border-bottom: 1px solid var(--chat-border);
        background: #ffffff;
        display: flex !important;
        flex-direction: column !important;
        gap: 12px;
        width: 100% !important;
        box-sizing: border-box !important;
        height: auto !important; /* Overrides global .sidebar-header { height: 4rem; } */
        position: static !important;
    }

    .chat-sidebar-header .header-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chat-sidebar-header h4 {
        margin: 0;
        font-weight: 700;
        font-size: 1.15rem;
        color: var(--chat-text-main);
        letter-spacing: -0.01em;
        line-height: 1.2;
    }

    .channel-filter-pills {
        display: flex !important;
        gap: 6px;
        width: 100%;
    }

    .channel-filter-pills .filter-pill {
        flex: 1;
        justify-content: center;
        padding: 6px 4px;
        font-size: 0.76rem;
        white-space: nowrap;
        text-align: center;
>>>>>>> origin/main
    }

    .search-container {
        position: relative;
<<<<<<< HEAD
=======
        width: 100%;
>>>>>>> origin/main
    }

    .search-container i {
        position: absolute;
<<<<<<< HEAD
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--chat-text-muted);
        font-size: 0.9rem;
        transition: color 0.2s;
    }

    .search-container input {
        width: 100%;
        padding: 14px 14px 14px 44px;
        border-radius: 14px;
        border: 1px solid var(--chat-border);
        background: #f8fafc;
        font-size: 0.9rem;
=======
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--chat-text-muted);
        font-size: 0.85rem;
        transition: color 0.2s;
        pointer-events: none;
    }

    .search-container input {
        width: 100% !important;
        box-sizing: border-box !important;
        padding: 10px 14px 10px 38px !important;
        border-radius: 12px;
        border: 1px solid var(--chat-border);
        background: #f8fafc;
        font-size: 0.88rem;
>>>>>>> origin/main
        font-weight: 500;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .search-container input:focus {
        outline: none;
        background: white;
        border-color: var(--chat-primary);
<<<<<<< HEAD
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .search-container input:focus+i {
=======
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
    }

    .search-container input:focus + i {
>>>>>>> origin/main
        color: var(--chat-primary);
    }

    .conversation-list {
        flex: 1;
        overflow-y: auto;
        padding: 12px;
    }

    .conversation-item {
        margin-bottom: 4px;
        padding: 16px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        gap: 16px;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        border: 1px solid transparent;
    }

    .conversation-item:hover {
        background-color: var(--chat-primary-light);
        border-color: rgba(99, 102, 241, 0.05);
    }

    .conversation-item.active {
        background-color: var(--chat-primary-light);
        border-color: rgba(99, 102, 241, 0.1);
    }

    .conversation-item.active::after {
        content: '';
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 6px;
        height: 6px;
        background: var(--chat-primary);
        border-radius: 50%;
        box-shadow: 0 0 10px var(--chat-primary);
    }

    .avatar-wrapper {
        position: relative;
    }

    .avatar {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
    }

    .conv-info {
        flex: 1;
        min-width: 0;
    }

    .conv-name-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 4px;
<<<<<<< HEAD
=======
        gap: 8px;
>>>>>>> origin/main
    }

    .conv-name {
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--chat-text-main);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
<<<<<<< HEAD
    }

    .conv-time {
        font-size: 0.75rem;
        color: var(--chat-text-muted);
        font-weight: 500;
=======
        flex: 1;
        min-width: 0;
    }

    .conv-time {
        font-size: 0.70rem;
        color: var(--chat-text-muted);
        font-weight: 500;
        white-space: nowrap;
        flex-shrink: 0;
>>>>>>> origin/main
    }

    .conv-snippet {
        font-size: 0.85rem;
        color: var(--chat-text-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }

    .badge-call-id {
        display: inline-flex;
        align-items: center;
<<<<<<< HEAD
        font-size: 0.7rem;
        background: white;
        color: #475569;
        padding: 4px 8px;
        border-radius: 8px;
        margin-top: 8px;
        border: 1px solid #f1f5f9;
        font-weight: 600;
    }

    /* Main Chat Area */
    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: var(--chat-bg-main);
        position: relative;
    }

    .chat-header {
        padding: 20px 32px;
        background: white;
        border-bottom: 2px solid #f8fafc;
=======
        font-size: 0.68rem;
        background: #f8fafc;
        color: #475569;
        padding: 2px 7px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        font-weight: 600;
    }

    .badge-channel {
        display: inline-flex;
        align-items: center;
        font-size: 0.68rem;
        font-weight: 600;
        padding: 2px 7px;
        border-radius: 6px;
        gap: 4px;
    }

    .badge-channel.voice {
        background: #ede9fe;
        color: #6366f1;
        border: 1px solid #ddd6fe;
    }

    .badge-channel.chat {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .badge-pet {
        font-size: 0.68rem;
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
        font-weight: 600;
        padding: 2px 7px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
    }

    .badge-plan {
        font-size: 0.68rem;
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
        font-weight: 600;
        padding: 2px 7px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
    }

    .filter-pill {
        border-radius: 20px;
        padding: 5px 12px;
        font-size: 0.78rem;
        font-weight: 600;
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
    }

    .filter-pill:hover {
        background: #e2e8f0;
        color: #1e293b;
        transform: translateY(-1px);
    }

    .filter-pill.active {
        background: #6366f1;
        color: #ffffff;
        border-color: #6366f1;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
    }

    .pill-count {
        background: rgba(0, 0, 0, 0.08);
        border-radius: 10px;
        padding: 1px 6px;
        font-size: 0.7rem;
    }

    .filter-pill.active .pill-count {
        background: rgba(255, 255, 255, 0.28);
        color: #fff;
    }

    /* Main Chat Area */
    .chat-main {
        flex: 1 1 0% !important;
        min-width: 0 !important;
        display: flex !important;
        flex-direction: column !important;
        background: var(--chat-bg-main);
        position: relative;
        overflow: hidden;
    }

    .chat-header {
        padding: 16px 24px;
        background: white;
        border-bottom: 1px solid var(--chat-border);
>>>>>>> origin/main
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 5;
<<<<<<< HEAD
=======
        flex-shrink: 0;
        min-height: 70px;
        box-sizing: border-box;
>>>>>>> origin/main
    }

    .chat-header-info {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .header-status {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.75rem;
        color: #10b981;
        font-weight: 600;
        margin-top: 2px;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        box-shadow: 0 0 8px #10b981;
    }

    .messages-container {
        flex: 1;
        overflow-y: auto;
        padding: 32px;
        display: flex;
        flex-direction: column;
        gap: 24px;
        background-image: radial-gradient(#e2e8f0 0.5px, transparent 0.5px);
        background-size: 24px 24px;
        scroll-behavior: smooth;
    }

    .message-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .message-bubble {
        max-width: 65%;
        padding: 14px 18px;
        border-radius: 20px;
        font-size: 0.95rem;
        line-height: 1.6;
        position: relative;
        box-shadow: var(--chat-shadow);
        animation: bubbleFadeIn 0.3s ease-out forwards;
    }

    @keyframes bubbleFadeIn {
<<<<<<< HEAD
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
=======
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
>>>>>>> origin/main
    }

    .message-bubble.bot {
        align-self: flex-start;
        background: white;
        color: var(--chat-text-main);
        border-bottom-left-radius: 4px;
<<<<<<< HEAD
        border-top-left-radius: 4px;
        /* Group styling */
=======
        border-top-left-radius: 4px; /* Group styling */
>>>>>>> origin/main
    }

    .message-bubble.user {
        align-self: flex-end;
        background: linear-gradient(135deg, var(--chat-primary) 0%, var(--chat-primary-hover) 100%);
        color: white;
        border-bottom-right-radius: 4px;
<<<<<<< HEAD
        border-top-right-radius: 4px;
        /* Group styling */
=======
        border-top-right-radius: 4px; /* Group styling */
>>>>>>> origin/main
    }

    .message-time {
        font-size: 0.72rem;
        margin-top: 6px;
<<<<<<< HEAD
        display: block;
        color: var(--chat-text-muted);
        font-weight: 500;
        opacity: 0.8;
    }

    .message-bubble.user .message-time {
        color: rgba(255, 255, 255, 0.8);
        text-align: right;
=======
        display: flex;
        align-items: center;
        gap: 4px;
        color: var(--chat-text-muted);
        font-weight: 500;
        opacity: 0.85;
    }

    .message-bubble.user .message-time {
        color: rgba(255, 255, 255, 0.85);
        justify-content: flex-end;
    }

    .message-bubble.bot .message-time {
        justify-content: flex-start;
>>>>>>> origin/main
    }

    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: var(--chat-text-muted);
        text-align: center;
        padding: 40px;
        background: white;
        z-index: 100;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
    }

    .empty-state-icon {
        width: 120px;
        height: 120px;
        background: var(--chat-primary-light);
        border-radius: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 24px;
        transition: all 0.5s ease;
    }

    .empty-state-icon i {
        font-size: 3rem;
        color: var(--chat-primary);
    }

    .empty-state:hover .empty-state-icon {
        transform: rotate(10deg) scale(1.05);
    }

    .empty-state h3 {
        font-weight: 700;
        color: var(--chat-text-main);
        margin-bottom: 12px;
    }

    .empty-state p {
        max-width: 300px;
        line-height: 1.6;
    }

    /* Scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: transparent;
    }

    ::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }

    /* Loading Spinner */
    .loader-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 0;
    }

    .modern-spinner {
        width: 40px;
        height: 40px;
        border: 4px solid var(--chat-primary-light);
        border-top: 4px solid var(--chat-primary);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
<<<<<<< HEAD
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
=======
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Responsive adjustments */
    @media (max-width: 991px) {
        .chat-wrapper {
            margin: 14px;
            height: calc(100vh - 120px);
            border-radius: 18px;
        }
        .chat-sidebar {
            width: 320px !important;
            min-width: 320px !important;
            max-width: 320px !important;
            flex: 0 0 320px !important;
        }
    }

    @media (max-width: 767px) {
        .chat-wrapper {
            margin: 10px;
            height: calc(100vh - 100px);
            border-radius: 14px;
            position: relative;
        }
        .chat-sidebar {
            width: 100% !important;
            min-width: 100% !important;
            max-width: 100% !important;
            flex: 1 1 100% !important;
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            background: white;
            z-index: 20;
        }
        .chat-sidebar.mobile-hidden {
            display: none !important;
        }
        .chat-main {
            width: 100%;
        }
        .message-bubble {
            max-width: 88%;
>>>>>>> origin/main
        }
    }
</style>

<main class="main-content">
    <div class="position-relative iq-banner">
        <?php echo $this->include('admin/nav_bar') ?>
    </div>

    <div class="chat-wrapper">
        <!-- Sidebar -->
        <div class="chat-sidebar">
<<<<<<< HEAD
            <div class="sidebar-header">
                <h4>Conversations</h4>
                <div class="search-container">
                    <i class="fas fa-search"></i>
                    <input type="text" id="conversationSearch" placeholder="Search by name or phone...">
                </div>
            </div>
            <div class="conversation-list" id="conversationList">
                <?php if (!empty($conversations)): ?>
                    <?php foreach ($conversations as $conv): ?>
                        <div class="conversation-item" data-id="<?= $conv['id'] ?>"
                            onclick="loadConversation('<?= $conv['id'] ?>')">
                            <div class="avatar-wrapper">
                                <div class="avatar">
                                    <?= strtoupper(substr($conv['user_name'] ?? 'U', 0, 1)) ?>
=======
            <div class="chat-sidebar-header">
                <div class="header-top">
                    <div>
                        <h4 class="d-flex align-items-center gap-2">
                            <i class="fas fa-shield-alt text-primary"></i> Insurance Chats
                        </h4>
                        <small class="text-muted" style="font-size: 0.76rem;">Voice Agent & Chatbot Sessions</small>
                    </div>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1 fw-bold" style="font-size: 0.72rem;">
                        <?= count($conversations ?? []) ?> Sessions
                    </span>
                </div>

                <!-- Channel Filter Pills -->
                <div class="channel-filter-pills" id="channelFilters">
                    <button type="button" class="btn filter-pill active" data-filter="all" onclick="filterChannel('all', this)">
                        All <span class="pill-count"><?= count($conversations ?? []) ?></span>
                    </button>
                    <button type="button" class="btn filter-pill" data-filter="voice" onclick="filterChannel('voice', this)">
                        <i class="fas fa-phone-alt me-1"></i> Voice <span class="pill-count"><?= count(array_filter($conversations ?? [], fn($c) => ($c['channel'] ?? '') === 'voice')) ?></span>
                    </button>
                    <button type="button" class="btn filter-pill" data-filter="chat" onclick="filterChannel('chat', this)">
                        <i class="fas fa-robot me-1"></i> Web Chat <span class="pill-count"><?= count(array_filter($conversations ?? [], fn($c) => ($c['channel'] ?? '') === 'chat')) ?></span>
                    </button>
                </div>

                <div class="search-container">
                    <i class="fas fa-search"></i>
                    <input type="text" id="conversationSearch" placeholder="Search pet, client, call ID, message...">
                </div>
            </div>

            <div class="conversation-list" id="conversationList">
                <?php if (!empty($conversations)): ?>
                    <?php foreach ($conversations as $conv): ?>
                        <div class="conversation-item"
                             data-id="<?= esc($conv['id']) ?>"
                             data-channel="<?= esc($conv['channel'] ?? 'voice') ?>"
                             data-search="<?= esc(strtolower(($conv['user_name'] ?? '') . ' ' . ($conv['user_phone'] ?? '') . ' ' . ($conv['call_id'] ?? '') . ' ' . ($conv['pet_name'] ?? '') . ' ' . ($conv['coverage_type'] ?? '') . ' ' . ($conv['last_message'] ?? ''))) ?>"
                             onclick="loadConversation('<?= esc($conv['id']) ?>')">
                            <div class="avatar-wrapper">
                                <div class="avatar" style="background: <?= $conv['avatar_bg'] ?? '#ede9fe' ?>; color: <?= $conv['avatar_color'] ?? '#6366f1' ?>;">
                                    <?php if (($conv['channel'] ?? '') === 'voice'): ?>
                                        <i class="fas fa-headset" style="font-size: 1.15rem;"></i>
                                    <?php else: ?>
                                        <i class="fas fa-robot" style="font-size: 1.15rem;"></i>
                                    <?php endif; ?>
>>>>>>> origin/main
                                </div>
                            </div>
                            <div class="conv-info">
                                <div class="conv-name-row">
<<<<<<< HEAD
                                    <span class="conv-name"><?= $conv['user_name'] ?: 'Guest User' ?></span>
                                    <span class="conv-time">
                                        <?= !empty($conv['last_message_at']) ? date('H:i', strtotime($conv['last_message_at'])) : '' ?>
                                    </span>
                                </div>
                                <span class="conv-snippet">
                                    <?= $conv['last_message'] ?: 'No recorded messages' ?>
                                </span>
                                <?php if ($conv['call_id']): ?>
                                    <div class="badge-call-id">
                                        <i class="fas fa-phone-alt me-1" style="font-size: 0.6rem;"></i>
                                        ID: <?= substr($conv['call_id'], -8) ?>
                                    </div>
                                <?php endif; ?>
=======
                                    <span class="conv-name" title="<?= esc($conv['user_name']) ?>"><?= esc($conv['user_name'] ?: 'Insurance Consultation') ?></span>
                                    <span class="conv-time" title="<?= !empty($conv['last_message_at']) ? date('d M Y, h:i A', strtotime($conv['last_message_at'])) : '' ?>">
                                        <?= !empty($conv['last_message_at']) ? date('d M Y, h:i A', strtotime($conv['last_message_at'])) : '' ?>
                                    </span>
                                </div>
                                <span class="conv-snippet">
                                    <?= esc($conv['last_message'] ?: 'Pet Insurance Inquiry') ?>
                                </span>
                                <div class="d-flex align-items-center gap-1 mt-2 flex-wrap">
                                    <?php if (($conv['channel'] ?? '') === 'voice'): ?>
                                        <span class="badge-channel voice">
                                            <i class="fas fa-phone-alt"></i> Voice Agent
                                        </span>
                                        <?php if (!empty($conv['call_id'])): ?>
                                            <span class="badge-call-id">
                                                ID: <?= esc(substr($conv['call_id'], -8)) ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if (!empty($conv['pet_name'])): ?>
                                            <span class="badge-pet">
                                                🐾 <?= esc($conv['pet_name']) ?>
                                            </span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge-channel chat">
                                            <i class="fas fa-comment-dots"></i> Web Chatbot
                                        </span>
                                        <?php if (!empty($conv['pet_name'])): ?>
                                            <span class="badge-pet">
                                                🐾 <?= esc($conv['pet_name']) ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if (!empty($conv['coverage_type'])): ?>
                                            <span class="badge-plan">
                                                🛡️ <?= esc($conv['coverage_type']) ?>
                                            </span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
>>>>>>> origin/main
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-5 text-center text-muted">
<<<<<<< HEAD
                        <i class="fas fa-history mb-3" style="font-size: 2rem; opacity: 0.2;"></i>
                        <p>No chat history available.</p>
=======
                        <i class="fas fa-shield-cat mb-3" style="font-size: 2.5rem; opacity: 0.25; color: var(--chat-primary);"></i>
                        <h6 class="fw-bold text-dark">No Insurance Chats Found</h6>
                        <p class="small mb-0">Incoming AI voice calls and chatbot conversations will appear here.</p>
>>>>>>> origin/main
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="chat-main" id="chatMain">
            <!-- Empty State -->
            <div class="empty-state" id="emptyState">
                <div class="empty-state-icon">
<<<<<<< HEAD
                    <i class="fas fa-comment-dots"></i>
                </div>
                <h3>Welcome to AI History</h3>
                <p>Select a conversation from the left to view the interactive timeline with the AI agent.</p>
=======
                    <i class="fas fa-shield-cat"></i>
                </div>
                <h3>Pet Insurance Chat History</h3>
                <p>Select an insurance consultation session from the sidebar to view the interactive conversation timeline, pet details, and AI agent responses.</p>
>>>>>>> origin/main
            </div>

            <!-- Chat Content (Hidden initially) -->
            <div id="chatContent" style="display: none; height: 100%; flex-direction: column;">
                <div class="chat-header">
<<<<<<< HEAD
                    <div class="chat-header-info">
                        <div class="avatar" id="headerAvatar" style="width: 44px; height: 44px; font-size: 0.9rem;">?
                        </div>
                        <div>
                            <div class="conv-name" id="headerName" style="font-size: 1.1rem;">User Name</div>
                            <div class="header-status">
                                <div class="status-dot"></div>
                                <span id="headerPhone">Phone Number</span>
                            </div>
                        </div>
                    </div>
                    <div class="header-actions">
                        <button class="btn btn-sm btn-outline-light text-muted border-0" onclick="location.reload()">
=======
                    <div class="chat-header-info" style="min-width: 0; flex: 1;">
                        <button class="btn btn-sm btn-light d-md-none me-2 p-1 px-2 border flex-shrink-0" onclick="backToConversationList()" title="Back to conversations">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <div class="avatar" id="headerAvatar" style="width: 44px; height: 44px; font-size: 1rem; border-radius: 14px; flex-shrink: 0;">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div style="min-width: 0;">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="conv-name" id="headerName" style="font-size: 1.15rem; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 320px;">Client Consultation</span>
                                <span class="badge bg-light text-dark border fw-semibold" id="headerChannelBadge" style="font-size: 0.72rem;">Channel</span>
                            </div>
                            <div class="header-status">
                                <div class="status-dot"></div>
                                <span id="headerPhone" style="color: #64748b; font-weight: 500;">Insurance AI Session</span>
                            </div>
                        </div>
                    </div>
                    <div class="header-actions d-flex align-items-center gap-2 flex-shrink-0">
                        <div id="headerInsuranceBadges" class="d-flex align-items-center gap-2 flex-wrap"></div>
                        <button class="btn btn-sm btn-outline-secondary border-0" onclick="refreshCurrentConversation()" title="Refresh Session">
>>>>>>> origin/main
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>

                <div class="messages-container" id="messagesContainer">
                    <!-- Messages will be injected here -->
                </div>
            </div>
        </div>
    </div>
</main>

<<<<<<< HEAD
<script>
    async function loadConversation(id) {
=======
<!-- Pusher Client for Real-Time Insurance Chat Updates -->
<script src="https://js.pusher.com/8.2/pusher.min.js"></script>

<script>
    const PUSHER_KEY = '3c3318df787d87828310';
    const PUSHER_CLUSTER = 'ap2';
    let pusherClient = null;
    let activePusherChannel = null;

    let currentFilter = 'all';
    let currentConversationId = null;

    function backToConversationList() {
        const sidebar = document.querySelector('.chat-sidebar');
        if (sidebar) sidebar.classList.remove('mobile-hidden');
    }

    function filterChannel(channel, btn) {
        currentFilter = channel;
        document.querySelectorAll('#channelFilters .filter-pill').forEach(b => b.classList.remove('active'));
        if (btn) btn.classList.add('active');

        applyFilters();
    }

    function applyFilters() {
        const term = (document.getElementById('conversationSearch').value || '').toLowerCase().trim();
        const items = document.querySelectorAll('.conversation-item');
        let visibleCount = 0;

        items.forEach(item => {
            const channel = item.getAttribute('data-channel');
            const searchData = item.getAttribute('data-search') || '';

            const matchesChannel = (currentFilter === 'all' || channel === currentFilter);
            const matchesSearch = (!term || searchData.includes(term));

            if (matchesChannel && matchesSearch) {
                item.style.display = 'flex';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
    }

    async function loadConversation(id) {
        currentConversationId = id;

        // Mobile toggle
        if (window.innerWidth < 768) {
            const sidebar = document.querySelector('.chat-sidebar');
            if (sidebar) sidebar.classList.add('mobile-hidden');
        }

>>>>>>> origin/main
        // Update UI states
        document.querySelectorAll('.conversation-item').forEach(el => el.classList.remove('active'));
        const activeItem = document.querySelector(`.conversation-item[data-id="${id}"]`);
        if (activeItem) activeItem.classList.add('active');

        // Toggle sections
        document.getElementById('emptyState').style.display = 'none';
        const chatContent = document.getElementById('chatContent');
        chatContent.style.display = 'flex';

        const container = document.getElementById('messagesContainer');
        container.innerHTML = `
            <div class="loader-container">
                <div class="modern-spinner"></div>
<<<<<<< HEAD
                <p class="mt-3 text-muted" style="font-weight: 500; font-size: 0.9rem;">Retrieving conversation...</p>
=======
                <p class="mt-3 text-muted" style="font-weight: 500; font-size: 0.9rem;">Loading insurance conversation...</p>
>>>>>>> origin/main
            </div>
        `;

        try {
<<<<<<< HEAD
            const response = await fetch(`<?= base_url('admin/insurance/chatMessages') ?>/${id}`);
            const result = await response.json();

            if (result.status === 'success') {
                const { messages, user } = result.data;

                // Update header
                document.getElementById('headerName').innerText = user.user_name || 'Guest User';
                document.getElementById('headerPhone').innerText = user.user_phone || 'Unlinked Account';
                document.getElementById('headerAvatar').innerText = (user.user_name || 'G').substring(0, 1).toUpperCase();
=======
            const url = `<?= base_url('chat-history/messages') ?>/${encodeURIComponent(id)}`;
            const response = await fetch(url);
            if (!response.ok) {
                console.error('Fetch error for conversation:', response.status, response.statusText);
            }
            const result = await response.json();

            if (result.status === 'success' || result.status === true) {
                const data = result.data || result;
                const messages = data.messages || result.messages || [];
                const user = data.user || {};

                // Update header
                const titleText = user.user_name || 'Insurance Consultation';
                document.getElementById('headerName').innerText = titleText;
                document.getElementById('headerPhone').innerText = user.user_phone || 'Pet Insurance AI Agent';

                const isVoice = user.channel === 'Voice Agent' || !String(id).startsWith('cb_');
                const channelBadge = document.getElementById('headerChannelBadge');
                channelBadge.innerText = isVoice ? 'Voice Agent' : 'Web Chatbot';
                channelBadge.className = isVoice ? 'badge bg-primary-subtle text-primary border' : 'badge bg-success-subtle text-success border';

                const avatar = document.getElementById('headerAvatar');
                if (isVoice) {
                    avatar.innerHTML = '<i class="fas fa-headset"></i>';
                    avatar.style.background = '#ede9fe';
                    avatar.style.color = '#6366f1';
                } else {
                    avatar.innerHTML = '<i class="fas fa-robot"></i>';
                    avatar.style.background = '#dcfce7';
                    avatar.style.color = '#16a34a';
                }

                // Header insurance metadata badges
                const badgesContainer = document.getElementById('headerInsuranceBadges');
                badgesContainer.innerHTML = '';

                const sessionTimestamp = user.last_message_at || user.created_at;
                if (sessionTimestamp) {
                    badgesContainer.innerHTML += `<span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.72rem;" title="Session Date & Time"><i class="far fa-calendar-alt me-1 text-primary"></i>${formatDateTime(sessionTimestamp)}</span>`;
                }
                if (user.call_id) {
                    badgesContainer.innerHTML += `<span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.72rem;"><i class="fas fa-phone-alt me-1 text-primary"></i>Call ID: ${escapeHtml(user.call_id)}</span>`;
                }
                if (user.pet_name) {
                    badgesContainer.innerHTML += `<span class="badge bg-warning-subtle text-dark border px-2 py-1" style="font-size: 0.72rem;">🐾 Pet: ${escapeHtml(user.pet_name)}</span>`;
                }
                if (user.coverage_type) {
                    badgesContainer.innerHTML += `<span class="badge bg-info-subtle text-dark border px-2 py-1" style="font-size: 0.72rem;">🛡️ Plan: ${escapeHtml(user.coverage_type)}</span>`;
                }
                if (user.policy_number) {
                    badgesContainer.innerHTML += `<span class="badge bg-success-subtle text-success border px-2 py-1" style="font-size: 0.72rem;">📜 Policy: ${escapeHtml(user.policy_number)}</span>`;
                }
>>>>>>> origin/main

                // Render messages
                container.innerHTML = '';
                if (messages.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-5">
<<<<<<< HEAD
                            <i class="fas fa-ghost mb-3" style="font-size: 2rem; opacity: 0.1;"></i>
                            <p class="text-muted">No messages found in this session.</p>
=======
                            <i class="fas fa-comment-slash mb-3" style="font-size: 2.2rem; opacity: 0.2;"></i>
                            <p class="text-muted fw-semibold">No messages recorded in this consultation.</p>
>>>>>>> origin/main
                        </div>
                    `;
                } else {
                    messages.forEach((msg, index) => {
                        const bubble = document.createElement('div');
<<<<<<< HEAD
                        bubble.className = `message-bubble ${msg.sender === 'bot' ? 'bot' : 'user'}`;
                        bubble.style.animationDelay = `${index * 0.05}s`;

                        const text = msg.message.replace(/\n/g, '<br>');

                        bubble.innerHTML = `
                            <div class="message-text">${text}</div>
                            <span class="message-time">${formatTime(msg.created_at)}</span>
=======
                        const isBot = (msg.sender === 'bot' || msg.role === 'assistant' || msg.role === 'model');
                        bubble.className = `message-bubble ${isBot ? 'bot' : 'user'}`;
                        bubble.style.animationDelay = `${index * 0.02}s`;

                        const rawMsg = msg.message || msg.content || '';
                        const formattedText = isBot ? formatBotMessage(rawMsg) : escapeHtml(rawMsg).replace(/\n/g, '<br>');

                        const roleLabel = isBot ? (isVoice ? 'Voice Agent' : 'Insurance Bot') : 'Client';
                        const roleIcon = isBot ? (isVoice ? 'fas fa-headset' : 'fas fa-robot') : 'fas fa-user';

                        bubble.innerHTML = `
                            <div class="small fw-bold mb-1 opacity-75 d-flex align-items-center gap-1" style="font-size: 0.72rem;">
                                <i class="${roleIcon}"></i> ${roleLabel}
                            </div>
                            <div class="message-text">${formattedText}</div>
                            <span class="message-time"><i class="far fa-clock" style="font-size: 0.68rem;"></i>${formatDateTime(msg.created_at)}</span>
>>>>>>> origin/main
                        `;
                        container.appendChild(bubble);
                    });
                }

                // Scroll to bottom
                container.scrollTo({
                    top: container.scrollHeight,
                    behavior: 'smooth'
                });
<<<<<<< HEAD
=======

                // Subscribe to Pusher real-time updates for this active session
                subscribeToPusherChannel(id);
            } else {
                container.innerHTML = `
                    <div class="alert alert-warning m-4 border-0 shadow-sm" style="border-radius: 12px;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Unable to load messages for this conversation.
                    </div>
                `;
>>>>>>> origin/main
            }
        } catch (error) {
            console.error('Error loading messages:', error);
            container.innerHTML = `
                <div class="alert alert-danger m-4 border-0 shadow-sm" style="border-radius: 12px; background: #fef2f2; color: #991b1b;">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Failed to fetch messages. Please check your connection and try again.
                </div>
            `;
        }
    }

<<<<<<< HEAD
    function formatTime(dateStr) {
        if (!dateStr) return '';
        const date = new Date(dateStr.replace(' ', 'T'));
        return date.toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit'
        });
=======
    function refreshCurrentConversation() {
        if (currentConversationId) {
            loadConversation(currentConversationId);
        } else {
            location.reload();
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }

    function formatBotMessage(text) {
        if (!text) return '';
        let escaped = escapeHtml(text);
        // Bold
        escaped = escaped.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        // Italic
        escaped = escaped.replace(/\*(.*?)\*/g, '<em>$1</em>');
        // Inline code
        escaped = escaped.replace(/`([^`]+)`/g, '<code style="background:rgba(0,0,0,0.06);padding:2px 6px;border-radius:4px;font-size:0.88em;">$1</code>');
        // Newlines
        escaped = escaped.replace(/\n/g, '<br>');
        return escaped;
    }

    function formatDateTime(dateStr) {
        if (!dateStr) return '';
        try {
            const cleanStr = String(dateStr).trim();
            const isoLike = cleanStr.includes('T') ? cleanStr : cleanStr.replace(' ', 'T');
            const date = new Date(isoLike);
            if (isNaN(date.getTime())) {
                const fallback = new Date(cleanStr);
                if (isNaN(fallback.getTime())) return dateStr;
                return formatParts(fallback);
            }
            return formatParts(date);
        } catch (e) {
            return dateStr;
        }
    }

    function formatParts(d) {
        const day = String(d.getDate()).padStart(2, '0');
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const month = months[d.getMonth()];
        const year = d.getFullYear();
        
        let hours = d.getHours();
        const minutes = String(d.getMinutes()).padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? String(hours).padStart(2, '0') : '12';
        
        return `${day} ${month} ${year}, ${hours}:${minutes} ${ampm}`;
    }

    function formatTime(dateStr) {
        return formatDateTime(dateStr);
    }

    /* ── Pusher Real-Time Handlers ── */
    function initPusher() {
        if (typeof Pusher === 'undefined') return;
        try {
            if (!pusherClient) {
                pusherClient = new Pusher(PUSHER_KEY, {
                    cluster: PUSHER_CLUSTER,
                    forceTLS: true
                });

                // Global insurance chat channel to update sidebar snippets
                const globalChannel = pusherClient.subscribe('insurance-chat');
                globalChannel.bind('new-message', function(data) {
                    onSidebarRealtimeUpdate(data);
                });
            }
        } catch (e) {
            console.warn('Pusher initialization error:', e);
        }
    }

    function subscribeToPusherChannel(id) {
        if (!pusherClient) initPusher();
        if (!pusherClient) return;

        if (activePusherChannel) {
            try {
                pusherClient.unsubscribe(activePusherChannel.name);
            } catch (e) {}
            activePusherChannel = null;
        }

        const channelName = String(id).startsWith('cb_') 
            ? 'chatbot-' + String(id).replace('cb_', '') 
            : 'conversation-' + id;

        try {
            activePusherChannel = pusherClient.subscribe(channelName);
            activePusherChannel.bind('new-message', function(data) {
                onIncomingChatMessage(id, data);
            });
        } catch (e) {
            console.warn('Error subscribing to Pusher channel:', e);
        }
    }

    function onIncomingChatMessage(activeId, data) {
        if (!data || currentConversationId !== activeId) return;

        const container = document.getElementById('messagesContainer');
        if (!container) return;

        const isBot = (data.sender === 'bot' || data.role === 'assistant' || data.role === 'model');
        const isVoice = !String(activeId).startsWith('cb_');
        const rawMsg = data.message || data.content || '';
        const formattedText = isBot ? formatBotMessage(rawMsg) : escapeHtml(rawMsg).replace(/\n/g, '<br>');
        const roleLabel = isBot ? (isVoice ? 'Voice Agent' : 'Insurance Bot') : 'Client';
        const roleIcon = isBot ? (isVoice ? 'fas fa-headset' : 'fas fa-robot') : 'fas fa-user';

        const bubble = document.createElement('div');
        bubble.className = `message-bubble ${isBot ? 'bot' : 'user'}`;
        bubble.innerHTML = `
            <div class="small fw-bold mb-1 opacity-75 d-flex align-items-center gap-1" style="font-size: 0.72rem;">
                <i class="${roleIcon}"></i> ${roleLabel}
            </div>
            <div class="message-text">${formattedText}</div>
            <span class="message-time"><i class="far fa-clock" style="font-size: 0.68rem;"></i>${formatDateTime(data.created_at || new Date().toISOString())}</span>
        `;
        container.appendChild(bubble);

        container.scrollTo({
            top: container.scrollHeight,
            behavior: 'smooth'
        });

        onSidebarRealtimeUpdate(data);
    }

    function onSidebarRealtimeUpdate(data) {
        if (!data) return;
        const sessionId = data.session_id ? ('cb_' + data.session_id) : (data.conversation_id ? String(data.conversation_id) : null);
        if (!sessionId) return;

        const item = document.querySelector(`.conversation-item[data-id="${sessionId}"]`);
        if (item) {
            const snippet = item.querySelector('.conv-snippet');
            if (snippet) {
                snippet.innerText = data.message || data.content || 'New message';
            }
            const timeEl = item.querySelector('.conv-time');
            if (timeEl) {
                timeEl.innerHTML = formatDateTime(data.created_at || new Date().toISOString());
            }
            // Move item to the top of list
            const parent = item.parentElement;
            if (parent && parent.firstElementChild !== item) {
                parent.insertBefore(item, parent.firstElementChild);
            }
        }
>>>>>>> origin/main
    }

    // Search functionality with debounce
    let searchTimeout;
<<<<<<< HEAD
    document.getElementById('conversationSearch').addEventListener('input', function (e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('.conversation-item').forEach(item => {
                const name = item.querySelector('.conv-name').innerText.toLowerCase();
                const snippet = item.querySelector('.conv-snippet').innerText.toLowerCase();
                if (name.includes(term) || snippet.includes(term)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }, 300);
=======
    document.getElementById('conversationSearch').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            applyFilters();
        }, 200);
    });

    // Auto-select first conversation on desktop
    document.addEventListener('DOMContentLoaded', () => {
        initPusher();
        const firstConv = document.querySelector('.conversation-item');
        if (firstConv && window.innerWidth >= 768) {
            const firstId = firstConv.getAttribute('data-id');
            if (firstId) {
                loadConversation(firstId);
            }
        }
>>>>>>> origin/main
    });
</script>

<?php echo $this->include('admin/footer') ?>
<<<<<<< HEAD
<?php echo $this->endSection() ?>
=======
<?php echo $this->endSection() ?>
>>>>>>> origin/main
