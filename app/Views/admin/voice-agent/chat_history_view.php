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
    }

    .search-container {
        position: relative;
    }

    .search-container i {
        position: absolute;
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
        font-weight: 500;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .search-container input:focus {
        outline: none;
        background: white;
        border-color: var(--chat-primary);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .search-container input:focus+i {
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
    }

    .conv-name {
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--chat-text-main);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .conv-time {
        font-size: 0.75rem;
        color: var(--chat-text-muted);
        font-weight: 500;
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
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 5;
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
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message-bubble.bot {
        align-self: flex-start;
        background: white;
        color: var(--chat-text-main);
        border-bottom-left-radius: 4px;
        border-top-left-radius: 4px;
        /* Group styling */
    }

    .message-bubble.user {
        align-self: flex-end;
        background: linear-gradient(135deg, var(--chat-primary) 0%, var(--chat-primary-hover) 100%);
        color: white;
        border-bottom-right-radius: 4px;
        border-top-right-radius: 4px;
        /* Group styling */
    }

    .message-time {
        font-size: 0.72rem;
        margin-top: 6px;
        display: block;
        color: var(--chat-text-muted);
        font-weight: 500;
        opacity: 0.8;
    }

    .message-bubble.user .message-time {
        color: rgba(255, 255, 255, 0.8);
        text-align: right;
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
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
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
                                </div>
                            </div>
                            <div class="conv-info">
                                <div class="conv-name-row">
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
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-5 text-center text-muted">
                        <i class="fas fa-history mb-3" style="font-size: 2rem; opacity: 0.2;"></i>
                        <p>No chat history available.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="chat-main" id="chatMain">
            <!-- Empty State -->
            <div class="empty-state" id="emptyState">
                <div class="empty-state-icon">
                    <i class="fas fa-comment-dots"></i>
                </div>
                <h3>Welcome to AI History</h3>
                <p>Select a conversation from the left to view the interactive timeline with the AI agent.</p>
            </div>

            <!-- Chat Content (Hidden initially) -->
            <div id="chatContent" style="display: none; height: 100%; flex-direction: column;">
                <div class="chat-header">
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

<script>
    async function loadConversation(id) {
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
                <p class="mt-3 text-muted" style="font-weight: 500; font-size: 0.9rem;">Retrieving conversation...</p>
            </div>
        `;

        try {
            const response = await fetch(`<?= base_url('admin/insurance/chatMessages') ?>/${id}`);
            const result = await response.json();

            if (result.status === 'success') {
                const { messages, user } = result.data;

                // Update header
                document.getElementById('headerName').innerText = user.user_name || 'Guest User';
                document.getElementById('headerPhone').innerText = user.user_phone || 'Unlinked Account';
                document.getElementById('headerAvatar').innerText = (user.user_name || 'G').substring(0, 1).toUpperCase();

                // Render messages
                container.innerHTML = '';
                if (messages.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-5">
                            <i class="fas fa-ghost mb-3" style="font-size: 2rem; opacity: 0.1;"></i>
                            <p class="text-muted">No messages found in this session.</p>
                        </div>
                    `;
                } else {
                    messages.forEach((msg, index) => {
                        const bubble = document.createElement('div');
                        bubble.className = `message-bubble ${msg.sender === 'bot' ? 'bot' : 'user'}`;
                        bubble.style.animationDelay = `${index * 0.05}s`;

                        const text = msg.message.replace(/\n/g, '<br>');

                        bubble.innerHTML = `
                            <div class="message-text">${text}</div>
                            <span class="message-time">${formatTime(msg.created_at)}</span>
                        `;
                        container.appendChild(bubble);
                    });
                }

                // Scroll to bottom
                container.scrollTo({
                    top: container.scrollHeight,
                    behavior: 'smooth'
                });
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

    function formatTime(dateStr) {
        if (!dateStr) return '';
        const date = new Date(dateStr.replace(' ', 'T'));
        return date.toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // Search functionality with debounce
    let searchTimeout;
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
    });
</script>

<?php echo $this->include('admin/footer') ?>
<?php echo $this->endSection() ?>