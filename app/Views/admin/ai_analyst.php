<?= $this->extend('admin/includes/header'); ?>

<?= $this->section('content'); ?>

<main class="main-content">
    <div class="position-relative iq-banner">
        <?= $this->include('admin/nav_bar') ?>
    </div>

    <div class="container-fluid px-3 px-md-4 content-inner pb-4" id="page_layout">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

            .bi-analyst-wrapper {
                font-family: 'Plus Jakarta Sans', sans-serif;
                color: #0f172a;
            }

            .bi-card-glass {
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 20px;
                box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
                transition: all 0.3s ease;
            }

            .chat-container-card {
                height: calc(100vh - 165px);
                min-height: 560px;
                display: flex;
                flex-direction: column;
                overflow: hidden;
            }

            .executive-header {
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0d424d 100%);
                color: #ffffff;
                padding: 1rem 1.5rem;
                border-top-left-radius: 20px;
                border-top-right-radius: 20px;
            }

            .status-badge-online {
                background: rgba(16, 185, 129, 0.15);
                color: #34d399;
                border: 1px solid rgba(52, 211, 153, 0.3);
                font-size: 0.72rem;
                font-weight: 700;
                padding: 4px 12px;
                border-radius: 9999px;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }

            .status-dot-pulse {
                width: 7px;
                height: 7px;
                background-color: #34d399;
                border-radius: 50%;
                box-shadow: 0 0 0 0 rgba(52, 211, 153, 0.7);
                animation: pulse-green 2s infinite;
            }

            @keyframes pulse-green {
                0% {
                    box-shadow: 0 0 0 0 rgba(52, 211, 153, 0.7);
                }

                70% {
                    box-shadow: 0 0 0 6px rgba(52, 211, 153, 0);
                }

                100% {
                    box-shadow: 0 0 0 0 rgba(52, 211, 153, 0);
                }
            }

            .chat-stream-body {
                background-color: #f8fafc !important;
                flex: 1 1 auto;
                overflow-y: auto;
                padding: 1.5rem 1rem 2.5rem 1rem;
                scroll-behavior: smooth;
                position: relative;
            }

            /* ChatGPT Centered Content Max-Width */
            .user-msg-row,
            .assistant-msg-row,
            .welcome-box {
                max-width: 860px;
                margin-left: auto !important;
                margin-right: auto !important;
                width: 100%;
            }

            /* Floating Scroll to Bottom (Direct Down Option like ChatGPT) */
            .scroll-bottom-container {
                position: relative;
                width: 100%;
                height: 0;
                pointer-events: none;
                z-index: 50;
            }

            .btn-scroll-bottom {
                position: absolute;
                bottom: 16px;
                left: 50%;
                transform: translateX(-50%) translateY(12px);
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: #ffffff;
                border: 1px solid rgba(0, 0, 0, 0.12);
                box-shadow: 0 4px 16px rgba(15, 23, 42, 0.14);
                color: #334155;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.2s cubic-bezier(0.16, 1, 0.3, 1), transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), background-color 0.15s ease, box-shadow 0.15s ease, color 0.15s ease;
            }

            .btn-scroll-bottom.show {
                opacity: 1;
                pointer-events: auto;
                transform: translateX(-50%) translateY(0);
            }

            .btn-scroll-bottom:hover {
                background: #f8fafc;
                color: #0d424d;
                box-shadow: 0 6px 20px rgba(13, 66, 77, 0.2);
                transform: translateX(-50%) translateY(-2px);
            }

            .btn-scroll-bottom:active {
                transform: translateX(-50%) translateY(0);
                box-shadow: 0 2px 8px rgba(13, 66, 77, 0.14);
            }

            /* Sidebar Conversation Items */
            .conversation-item {
                border-radius: 12px;
                padding: 10px 14px;
                margin-bottom: 4px;
                cursor: pointer;
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                border: 1px solid transparent;
            }

            .conversation-item:hover {
                background-color: #f1f5f9;
                transform: translateX(2px);
            }

            .conversation-item.active {
                background-color: #e2e8f0;
                border-color: #cbd5e1;
                border-left: 4px solid #0d424d;
                font-weight: 600;
            }

            .conversation-item .btn-delete-conv {
                opacity: 0;
                transition: opacity 0.15s ease;
            }

            .conversation-item:hover .btn-delete-conv {
                opacity: 0.7;
            }

            .conversation-item .btn-delete-conv:hover {
                opacity: 1 !important;
            }

            /* ChatGPT Prompt Cards */
            .prompt-card {
                cursor: pointer;
                transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
                border: 1px solid #e2e8f0;
                background: #ffffff;
            }

            .prompt-card:hover {
                border-color: #cbd5e1;
                background-color: #f8fafc !important;
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06) !important;
            }

            .prompt-chip {
                background: #ffffff;
                color: #1e293b;
                border: 1px solid #cbd5e1;
                font-weight: 600;
                font-size: 0.82rem;
                padding: 8px 16px;
                border-radius: 9999px;
                transition: all 0.25s ease;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
            }

            .prompt-chip:hover {
                background: #0d424d;
                color: #ffffff;
                border-color: #0d424d;
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(13, 66, 77, 0.18);
            }

            /* ChatGPT User Bubble */
            .chat-bubble-user {
                background: #0d424d;
                color: #ffffff;
                border-radius: 22px 22px 6px 22px;
                padding: 13px 20px;
                max-width: 82%;
                margin-left: auto;
                box-shadow: 0 4px 14px rgba(13, 66, 77, 0.16);
                font-size: 0.93rem;
                line-height: 1.55;
                position: relative;
                transition: all 0.2s ease;
            }

            .chat-bubble-user.in-edit-mode {
                background: #1e293b !important;
                border-radius: 16px;
                width: 100%;
                max-width: 88%;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
                border: 1px solid rgba(255, 255, 255, 0.15);
            }

            .btn-edit-prompt {
                opacity: 0;
                color: rgba(255, 255, 255, 0.7);
                background: rgba(255, 255, 255, 0.1);
                border: none;
                border-radius: 6px;
                padding: 3px 6px;
                font-size: 0.78rem;
                line-height: 1;
                cursor: pointer;
                transition: all 0.15s ease;
            }

            .chat-bubble-user:hover .btn-edit-prompt {
                opacity: 1;
            }

            .btn-edit-prompt:hover {
                color: #ffffff;
                background: rgba(255, 255, 255, 0.25);
            }

            .user-edit-textarea {
                resize: vertical;
                font-size: 0.92rem;
                line-height: 1.5;
                color: #ffffff !important;
            }

            .user-edit-textarea:focus {
                outline: none !important;
                box-shadow: none !important;
            }

            .border-white-25 {
                border-color: rgba(255, 255, 255, 0.18) !important;
            }

            /* ChatGPT Assistant Bubble */
            .chat-bubble-assistant {
                background: #ffffff;
                color: #1e293b;
                border-radius: 18px;
                padding: 20px 24px;
                width: 100%;
                border: 1px solid #eef2f6;
                box-shadow: 0 2px 12px rgba(15, 23, 42, 0.04);
                font-size: 0.94rem;
                line-height: 1.68;
                transition: all 0.2s ease;
            }

            .ai-avatar-circle {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                background: linear-gradient(135deg, #0d424d 0%, #14b8a6 100%);
                color: #ffffff;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 0.95rem;
                box-shadow: 0 2px 6px rgba(13, 66, 77, 0.2);
            }

            /* Formatted Tables inside Markdown Responses */
            .bi-table-wrapper {
                overflow-x: auto;
                border-radius: 12px;
                border: 1px solid #e2e8f0;
                margin: 1.1rem 0;
                background: #ffffff;
                box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
            }

            .bi-data-table {
                width: 100%;
                margin-bottom: 0;
                font-size: 0.88rem;
                border-collapse: separate;
                border-spacing: 0;
            }

            .bi-data-table th {
                background-color: #f8fafc;
                color: #334155;
                font-weight: 700;
                font-size: 0.8rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                padding: 11px 16px;
                border-bottom: 1px solid #e2e8f0;
                white-space: nowrap;
            }

            .bi-data-table td {
                padding: 11px 16px;
                vertical-align: middle;
                border-bottom: 1px solid #f1f5f9;
                color: #1e293b;
            }

            .bi-data-table tbody tr:last-child td {
                border-bottom: none;
            }

            .bi-data-table tbody tr:hover td {
                background-color: #f8fafc;
            }

            /* ChatGPT Style Code Blocks */
            .chat-code-block {
                background: #0f172a;
                border: 1px solid #334155;
                border-radius: 10px;
                overflow: hidden;
                margin: 1rem 0;
            }

            .chat-code-header {
                background: #1e293b;
                padding: 6px 14px;
                color: #94a3b8;
                font-size: 0.78rem;
                border-bottom: 1px solid #334155;
            }

            .chat-code-header button {
                color: #94a3b8;
                font-size: 0.78rem;
                transition: color 0.15s ease;
            }

            .chat-code-header button:hover {
                color: #ffffff;
            }

            .chat-code-block pre {
                margin: 0;
                padding: 12px 16px;
                color: #e2e8f0;
                font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
                font-size: 0.86rem;
                line-height: 1.5;
            }

            /* Metric Highlight Cards in Markdown */
            .metric-stat-card {
                background: #f8fafc;
                border-left: 4px solid #0d424d;
                padding: 12px 16px;
                border-radius: 0 10px 10px 0;
                margin: 10px 0;
            }

            .typing-indicator span {
                display: inline-block;
                width: 7px;
                height: 7px;
                background-color: #0d424d;
                border-radius: 50%;
                animation: typing 1.4s infinite ease-in-out both;
            }

            .typing-indicator span:nth-child(1) {
                animation-delay: 0s;
            }

            .typing-indicator span:nth-child(2) {
                animation-delay: 0.2s;
            }

            .typing-indicator span:nth-child(3) {
                animation-delay: 0.4s;
            }

            @keyframes typing {

                0%,
                80%,
                100% {
                    transform: scale(0.3);
                    opacity: 0.4;
                }

                40% {
                    transform: scale(1);
                    opacity: 1;
                }
            }

            /* Custom Scrollbars */
            .chat-stream-body::-webkit-scrollbar,
            .conversation-scroll::-webkit-scrollbar {
                width: 6px;
                height: 6px;
            }

            .chat-stream-body::-webkit-scrollbar-thumb,
            .conversation-scroll::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 9999px;
            }

            .chat-stream-body::-webkit-scrollbar-track,
            .conversation-scroll::-webkit-scrollbar-track {
                background: transparent;
            }

            @media (max-width: 991.98px) {
                .chat-container-card {
                    height: calc(100vh - 200px);
                    min-height: 480px;
                }

                .chat-bubble-assistant {
                    max-width: 100%;
                    padding: 16px 18px;
                }

                .chat-bubble-user {
                    max-width: 90%;
                }
            }

            /* ChatGPT Docked Pill Input Bar */
            .chat-input-wrapper {
                max-width: 860px;
                margin: 0 auto;
                width: 100%;
            }

            .chat-input-box {
                background: #ffffff;
                border: 1px solid #d1d5db !important;
                border-radius: 28px !important;
                box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
                padding: 6px 10px 6px 18px !important;
            }

            .chat-input-box:focus-within {
                border-color: #0d424d !important;
                box-shadow: 0 6px 24px rgba(13, 66, 77, 0.12) !important;
            }

            .chat-textarea {
                resize: none !important;
                max-height: 160px;
                line-height: 1.5;
                font-size: 0.95rem !important;
                color: #0f172a !important;
                border: none !important;
                outline: none !important;
                box-shadow: none !important;
                background: transparent !important;
                padding: 6px 4px !important;
            }

            .btn-send-circle {
                width: 36px;
                height: 36px;
                border-radius: 50% !important;
                background-color: #0d424d;
                border: none;
                color: #ffffff;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s ease;
                cursor: pointer;
            }

            .btn-send-circle:hover {
                background-color: #125e6e;
                transform: scale(1.05);
            }

            .btn-send-circle.btn-generating {
                background-color: #dc2626 !important;
            }

            .btn-send-circle.btn-generating:hover {
                background-color: #b91c1c !important;
            }

            /* Message Action Bar (ChatGPT Style) */
            .msg-action-bar {
                opacity: 0.85;
                transition: opacity 0.2s ease;
            }

            .chat-bubble-assistant:hover .msg-action-bar {
                opacity: 1;
            }

            .btn-icon-msg {
                color: #64748b;
                background: transparent;
                border: 1px solid transparent;
                padding: 4px 7px;
                border-radius: 6px;
                font-size: 0.84rem;
                line-height: 1;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.15s ease-in-out;
            }

            .btn-icon-msg:hover {
                color: #0d424d;
                background: rgba(13, 66, 77, 0.08);
                border-color: rgba(13, 66, 77, 0.12);
            }

            .btn-icon-msg.active-like {
                color: #059669 !important;
                background: rgba(16, 185, 129, 0.12) !important;
                border-color: rgba(16, 185, 129, 0.25) !important;
            }

            .btn-icon-msg.active-dislike {
                color: #dc2626 !important;
                background: rgba(239, 68, 68, 0.12) !important;
                border-color: rgba(239, 68, 68, 0.25) !important;
            }

            .btn-icon-msg.active-speaking {
                color: #0284c7 !important;
                background: rgba(2, 132, 199, 0.12) !important;
                border-color: rgba(2, 132, 199, 0.25) !important;
                animation: pulse-speak 1.2s infinite;
            }

            @keyframes pulse-speak {

                0%,
                100% {
                    opacity: 1;
                    transform: scale(1);
                }

                50% {
                    opacity: 0.5;
                    transform: scale(0.96);
                }
            }

            .no-caret::after {
                display: none !important;
            }

            /* Custom 3-dots Dropdown */
            .msg-dropdown {
                position: relative;
                display: inline-block;
            }

            .msg-dropdown-menu {
                display: none;
                position: absolute;
                bottom: 100%;
                left: 0;
                margin-bottom: 6px;
                background: #1e293b;
                color: #ffffff;
                min-width: 190px;
                border-radius: 10px;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4);
                padding: 6px 0;
                z-index: 1060;
                list-style: none;
            }

            .msg-dropdown-menu.show {
                display: block;
                animation: fadeInMenu 0.15s ease-out;
            }

            @keyframes fadeInMenu {
                from {
                    opacity: 0;
                    transform: translateY(4px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .msg-dropdown-item {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 8px 16px;
                color: #f1f5f9;
                font-size: 0.83rem;
                text-decoration: none;
                cursor: pointer;
                transition: background 0.15s ease;
            }

            .msg-dropdown-item:hover {
                background: rgba(255, 255, 255, 0.1);
                color: #ffffff;
            }

            .msg-dropdown-header {
                padding: 6px 16px 4px;
                font-size: 0.72rem;
                color: #94a3b8;
                font-weight: 600;
            }

            .msg-dropdown-divider {
                height: 1px;
                margin: 4px 0;
                background: rgba(255, 255, 255, 0.1);
            }

            .chat-toast-container {
                position: fixed;
                bottom: 24px;
                right: 24px;
                z-index: 1090;
                pointer-events: none;
            }

            .chat-toast {
                pointer-events: auto;
                background: #1e293b;
                color: #ffffff;
                padding: 10px 18px;
                border-radius: 10px;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
                font-size: 0.84rem;
                display: flex;
                align-items: center;
                gap: 8px;
                opacity: 0;
                transform: translateY(12px);
                transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .chat-toast.show {
                opacity: 1;
                transform: translateY(0);
            }
        </style>

        <div class="bi-analyst-wrapper">
            <div class="row">
                <!-- Mobile Toggle Bar for Inquiry History -->
                <div class="col-12 d-lg-none mb-3">
                    <button class="btn btn-outline-dark w-100 rounded-pill d-flex justify-content-between align-items-center px-4"
                        type="button" data-bs-toggle="collapse" data-bs-target="#inquiryHistoryCollapse" aria-expanded="false">
                        <span><i class="bi bi-clock-history me-2 text-primary"></i> Inquiry History</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                </div>

                <!-- Left Sidebar: Conversations List -->
                <div class="col-lg-3 col-md-12 mb-4 d-lg-block collapse" id="inquiryHistoryCollapse">
                    <div class="bi-card-glass h-100 d-flex flex-column" style="max-height: calc(100vh - 165px);">
                        <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-white rounded-top-4">
                            <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="bi bi-clock-history text-teal fs-5" style="color: #0d424d;"></i> Inquiry History
                            </h6>
                            <button class="btn btn-sm rounded-pill px-3 text-white fw-semibold d-flex align-items-center gap-1"
                                id="btnNewChat" style="background-color: #0d424d;">
                                <i class="bi bi-plus-lg"></i> New
                            </button>
                        </div>

                        <!-- Search Filter -->
                        <div class="px-3 pt-3 pb-2 bg-white">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-end-0 rounded-start-pill"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" class="form-control bg-light border-start-0 rounded-end-pill" id="searchInquiries" placeholder="Search inquiries...">
                            </div>
                        </div>

                        <div class="p-2 overflow-auto flex-grow-1 conversation-scroll" id="conversationList">
                            <?php if (empty($conversations)): ?>
                                <div class="text-center py-4 text-muted small" id="noConversationsMsg">
                                    <i class="bi bi-chat-left-dots fs-3 d-block mb-2 text-secondary"></i>
                                    No previous conversations yet.
                                </div>
                            <?php else: ?>
                                <?php foreach ($conversations as $conv): ?>
                                    <div class="conversation-item d-flex justify-content-between align-items-center"
                                        data-id="<?= $conv['id'] ?>"
                                        data-title="<?= esc(strtolower($conv['title'])) ?>">
                                        <div class="text-truncate me-2" style="max-width: 170px;">
                                            <div class="fw-semibold text-dark small text-truncate"><?= esc($conv['title']) ?></div>
                                            <small class="text-muted" style="font-size: 0.72rem;">
                                                <i class="bi bi-clock me-1"></i><?= date('d M Y, h:i A', strtotime($conv['created_at'])) ?>
                                            </small>
                                        </div>
                                        <button class="btn btn-link text-danger p-0 border-0 btn-delete-conv opacity-75 opacity-100-hover" data-id="<?= $conv['id'] ?>" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Right Area: Main Executive BI Chat Window -->
                <div class="col-lg-9 col-md-12 mb-4">
                    <div class="bi-card-glass chat-container-card">
                        <!-- Chat Header -->
                        <div class="executive-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle p-2 d-flex align-items-center justify-content-center"
                                    style="width: 46px; height: 46px; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(8px);">
                                    <i class="bi bi-cpu fs-4 text-cyan" style="color: #38bdf8;"></i>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <h5 class="mb-0 text-white fw-bold">AI Business Analyst</h5>
                                        <span class="status-badge-online">
                                            <span class="status-dot-pulse"></span> Executive BI Engine Active
                                        </span>
                                    </div>
                                    <small class="text-white-50" style="font-size: 0.8rem;">Petsfolio Bookings, CRM, Insurance, Loyalty, Community, Payouts & Full Database Analytics</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <!-- Gemini 3.1 Flash Lite Live Rate Limits & Real Credits Badge -->
                                <div class="gemini-rate-limits-badge d-flex align-items-center gap-3 px-3 py-1 rounded-pill"
                                    id="btnGeminiLimitsModal"
                                    style="background: rgba(255, 255, 255, 0.14); border: 1px solid rgba(255, 255, 255, 0.3); color: #ffffff; cursor: pointer; backdrop-filter: blur(8px);"
                                    data-bs-toggle="modal" data-bs-target="#geminiLimitsModal"
                                    title="Click to view Gemini 3.1 Flash Lite Text-out Rate Limits, Real Credits & AI Studio link">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-stars fs-5 text-warning"></i>
                                        <div style="line-height: 1.2;">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-bold text-white" style="font-size: 0.84rem;">Gemini 3.1 Flash Lite</span>
                                                <span class="badge bg-info text-dark fw-bold" style="font-size: 0.65rem; padding: 2px 6px;">Text-out models</span>
                                            </div>
                                            <div class="d-flex align-items-center gap-2 mt-1" style="font-size: 0.76rem; font-family: monospace;">
                                                <span title="Requests Per Minute (RPM)"><span class="text-white-50">RPM:</span> <strong id="gemini-rpm" class="text-cyan" style="color:#38bdf8;"><?= esc($credits['rpm'] ?? '0 / 15') ?></strong></span>
                                                <span class="text-white-50">•</span>
                                                <span title="Tokens Per Minute (TPM)"><span class="text-white-50">TPM:</span> <strong id="gemini-tpm" class="text-info"><?= esc($credits['tpm'] ?? '0 / 250K') ?></strong></span>
                                                <span class="text-white-50">•</span>
                                                <span title="Requests Per Day / Real Credits (RPD)"><span class="text-white-50">RPD:</span> <strong id="gemini-rpd" class="text-warning"><?= esc($credits['rpd'] ?? '0 / 500') ?></strong></span>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="https://aistudio.google.com/rate-limit?timeRange=last-28-days&project=petsfolio-688ca"
                                        target="_blank"
                                        class="btn btn-sm btn-outline-light rounded-pill px-2 py-0 d-none d-md-inline-flex align-items-center gap-1"
                                        style="font-size: 0.72rem; border-color: rgba(255,255,255,0.4);"
                                        title="Open Google AI Studio Rate Limit Dashboard"
                                        onclick="event.stopPropagation();">
                                        <i class="bi bi-box-arrow-up-right" style="font-size: 0.7rem;"></i> AI Studio
                                    </a>
                                </div>

                                <button class="btn btn-outline-light btn-sm rounded-pill px-3" id="btnExportChat" title="Export Conversation">
                                    <i class="bi bi-download me-1"></i> <span class="d-none d-sm-inline">Export</span>
                                </button>
                                <button class="btn btn-outline-light btn-sm rounded-pill px-3" id="btnClearScreen" title="Clear screen">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> <span class="d-none d-sm-inline">Clear</span>
                                </button>
                            </div>
                        </div>

                        <!-- Chat Messages Stream -->
                        <div class="chat-stream-body" id="chatStream">
                            <!-- Welcome / Suggested Prompts (ChatGPT Style) -->
                            <div class="welcome-box text-center py-4 px-3 mb-3 mx-auto">
                                <div class="welcome-avatar-wrapper mb-3">
                                    <div class="d-inline-flex p-3 rounded-circle shadow-sm" style="background: linear-gradient(135deg, rgba(13,66,77,0.1), rgba(20,184,166,0.15)); border: 1px solid rgba(13,66,77,0.15);">
                                        <i class="bi bi-stars fs-2" style="color: #0d424d;"></i>
                                    </div>
                                </div>
                                <h3 class="fw-bold text-dark mb-2" style="letter-spacing: -0.5px;">What can I help you analyze today?</h3>
                                <p class="text-muted small mx-auto mb-4" style="max-width: 620px; font-size: 0.92rem; line-height: 1.5;">
                                    Query live CRM leads, Insurance policies, Loyalty rewards, Provider earnings, Notification queues, and database metrics.
                                </p>

                                <div class="row g-3 text-start" id="suggestedPrompts">
                                    <div class="col-md-6 col-12">
                                        <div class="prompt-card prompt-chip h-100 p-3 rounded-4 border bg-white shadow-sm" data-prompt="How many active insurance policies are there?">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <i class="bi bi-shield-check text-primary fs-5"></i>
                                                <span class="fw-bold text-dark small">Insurance Policies</span>
                                            </div>
                                            <div class="text-muted small">How many active insurance policies are there?</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="prompt-card prompt-chip h-100 p-3 rounded-4 border bg-white shadow-sm" data-prompt="How many total loyalty points there, who has maximum,minimum points?">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <i class="bi bi-gift text-success fs-5"></i>
                                                <span class="fw-bold text-dark small">Loyalty Program</span>
                                            </div>
                                            <div class="text-muted small">Who has maximum and minimum loyalty points?</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="prompt-card prompt-chip h-100 p-3 rounded-4 border bg-white shadow-sm" data-prompt="How much providers earnings average and who earned maximum,minimum?">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <i class="bi bi-wallet2 text-danger fs-5"></i>
                                                <span class="fw-bold text-dark small">Provider Earnings</span>
                                            </div>
                                            <div class="text-muted small">Average provider earnings, maximum and minimum earners</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="prompt-card prompt-chip h-100 p-3 rounded-4 border bg-white shadow-sm" data-prompt="Which provider not receiving notifications and who receiving but not active, active but submitting bids?">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <i class="bi bi-bell text-warning fs-5"></i>
                                                <span class="fw-bold text-dark small">Notifications & Bidding</span>
                                            </div>
                                            <div class="text-muted small">Who is receiving notifications and submitting bids?</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="prompt-card prompt-chip h-100 p-3 rounded-4 border bg-white shadow-sm" data-prompt="How many admin, client, provider posts?">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <i class="bi bi-chat-left-text text-info fs-5"></i>
                                                <span class="fw-bold text-dark small">Community Engagement</span>
                                            </div>
                                            <div class="text-muted small">Post distribution: Admin vs Client vs Service Providers</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="prompt-card prompt-chip h-100 p-3 rounded-4 border bg-white shadow-sm" data-prompt="What is our current Cashfree payout balance and pending withdrawals?">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <i class="bi bi-cash-stack text-secondary fs-5"></i>
                                                <span class="fw-bold text-dark small">Payout Balances</span>
                                            </div>
                                            <div class="text-muted small">Cashfree gateway balance and pending withdrawals</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Direct Down (Scroll to Bottom) Button like ChatGPT -->
                        <div class="scroll-bottom-container">
                            <button type="button" class="btn-scroll-bottom" id="btnScrollBottom" title="Scroll to bottom" aria-label="Direct down to bottom">
                                <i class="bi bi-arrow-down fs-5"></i>
                            </button>
                        </div>

                        <!-- Input Footer (ChatGPT Style) -->
                        <div class="card-footer bg-white border-top p-3 rounded-bottom-4">
                            <div class="chat-input-wrapper">
                                <!-- Reply Mode Selector Bar -->
                                <div class="d-flex align-items-center justify-content-between px-2 mb-2 flex-wrap gap-2">
                                    <div class="reply-mode-selector d-inline-flex align-items-center p-1 rounded-pill bg-light border shadow-sm" style="font-size: 0.78rem;">
                                        <button type="button" class="btn btn-sm rounded-pill px-3 py-1 fw-bold reply-mode-btn active"
                                            data-mode="quick" id="btnModeQuick"
                                            style="background: #ffffff; color: #0d424d; box-shadow: 0 2px 4px rgba(0,0,0,0.08); transition: all 0.2s;">
                                            <i class="bi bi-lightning-charge-fill text-warning me-1"></i> Quick Reply
                                        </button>
                                        <button type="button" class="btn btn-sm rounded-pill px-3 py-1 fw-semibold text-muted reply-mode-btn"
                                            data-mode="detailed" id="btnModeDetailed"
                                            style="background: transparent; color: #64748b; transition: all 0.2s;">
                                            <i class="bi bi-file-text-fill text-info me-1"></i> Full Detailed Reply
                                        </button>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <small class="text-muted" id="replyModeHint" style="font-size: 0.76rem;">
                                            <i class="bi bi-info-circle me-1"></i> <span id="replyModeHintText">Quick: Simple answer & exact results only</span>
                                        </small>
                                    </div>
                                </div>

                                <form id="chatForm" class="chat-input-form position-relative">
                                    <input type="hidden" id="activeConversationId" value="">
                                    <input type="hidden" id="activeReplyMode" value="quick">
                                    <div class="chat-input-box d-flex align-items-center bg-white rounded-pill px-3 py-2 border shadow-sm">
                                        <textarea class="form-control border-0 bg-transparent px-2 py-1 fs-6 text-dark chat-textarea"
                                            id="userInput"
                                            rows="1"
                                            placeholder="Message Petsfolio AI Analyst... (e.g. Total groomer bookings this month?)"
                                            autocomplete="off"></textarea>
                                        <button class="btn btn-send-circle rounded-circle p-0 text-white d-flex align-items-center justify-content-center ms-2 flex-shrink-0"
                                            type="submit" id="btnSend" title="Send message">
                                            <i id="btnSendIcon" class="bi bi-arrow-up fs-5"></i>
                                        </button>
                                    </div>
                                    <div class="text-center mt-2">
                                        <small class="text-muted" style="font-size: 0.73rem;">
                                            Petsfolio AI Analyst can make mistakes. Verify important business metrics against live database records.
                                        </small>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(document).ready(function() {
        let activeConvId = localStorage.getItem('ai_active_conv_id') || '';
        const userExplicitlyClickedNew = sessionStorage.getItem('ai_user_wanted_new') === '1';
        const baseAdminUrl = "<?= base_url() ?>";

        let latestProviderData = null;

        function updateCreditsDisplay(creditsObj) {
            if (!creditsObj) return;

            const rpm = creditsObj.rpm || '0 / 15';
            const tpm = creditsObj.tpm || '0 / 250K';
            const rpd = creditsObj.rpd || '0 / 500';
            const rpdAvail = creditsObj.rpd_available !== undefined ? creditsObj.rpd_available : (creditsObj.available_credits !== undefined ? creditsObj.available_credits : 500);
            const todayUsed = creditsObj.real_queries_today !== undefined ? creditsObj.real_queries_today : (creditsObj.credits_used || 0);
            const totalAll = creditsObj.real_queries_all !== undefined ? creditsObj.real_queries_all : 0;

            $('#gemini-rpm').text(rpm);
            $('#gemini-tpm').text(tpm);
            $('#gemini-rpd').text(rpd);

            $('#modal-gemini-rpm').text(rpm);
            $('#modal-gemini-tpm').text(tpm);
            $('#modal-gemini-rpd').text(rpd);
            $('#modal-rpd-avail').text(rpdAvail);
            $('#modal-queries-today').text(todayUsed);
            $('#modal-queries-avail').text(rpdAvail);
            $('#modal-total-queries-all').text(totalAll + ' Total Queries Logged');

            if (rpdAvail <= 0) {
                $('#gemini-rpd').removeClass('text-warning').addClass('text-danger fw-bold');
            } else {
                $('#gemini-rpd').removeClass('text-danger').addClass('text-warning');
            }
        }

        // Fetch initial credits balance on load
        $.getJSON(baseAdminUrl + '/ai-analyst/credits', function(res) {
            if (res && res.data) {
                updateCreditsDisplay(res.data);
            }
        });

        // Immediately restore active chat on page load / refresh
        if (!userExplicitlyClickedNew) {
            if (!activeConvId) {
                const $firstConv = $('.conversation-item').first();
                if ($firstConv.length) {
                    activeConvId = $firstConv.data('id');
                    localStorage.setItem('ai_active_conv_id', activeConvId);
                }
            }

            if (activeConvId) {
                $('#activeConversationId').val(activeConvId);
                $(`.conversation-item[data-id="${activeConvId}"]`).addClass('active');
                loadConversationMessages(activeConvId);
            }
        }

        // Auto load conversation list in background to sync any updates
        reloadConversationList(activeConvId);

        // Filter inquiries search
        $('#searchInquiries').on('keyup', function() {
            const query = $(this).val().toLowerCase().trim();
            $('.conversation-item').each(function() {
                const title = $(this).data('title') || '';
                if (title.indexOf(query) !== -1) {
                    $(this).removeClass('d-none');
                } else {
                    $(this).addClass('d-none');
                }
            });
        });

        // Handle suggested prompt chips
        $(document).on('click', '.prompt-chip', function() {
            const text = $(this).attr('data-prompt') || $(this).data('prompt') || $(this).text().trim();
            $('#userInput').val(cleanPromptText(text));
            $('#chatForm').submit();
        });

        // New Chat & Clear Screen
        $('#btnNewChat, #btnClearScreen').click(function() {
            activeConvId = '';
            localStorage.removeItem('ai_active_conv_id');
            sessionStorage.setItem('ai_user_wanted_new', '1');
            $('#activeConversationId').val('');
            $('.conversation-item').removeClass('active');
            resetChatStream();
        });

        // Export conversation transcript
        $('#btnExportChat').click(function() {
            const text = $('#chatStream').text();
            if (!text || $('.welcome-box').is(':visible')) {
                alert('No active conversation to export.');
                return;
            }
            const blob = new Blob([text], {
                type: 'text/plain;charset=utf-8'
            });
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = `BI_Analyst_Report_${Date.now()}.txt`;
            a.click();
        });

        // Select existing conversation
        $(document).on('click', '.conversation-item', function(e) {
            if ($(e.target).closest('.btn-delete-conv').length) return;

            const id = $(this).data('id');
            activeConvId = id;
            sessionStorage.removeItem('ai_user_wanted_new');
            localStorage.setItem('ai_active_conv_id', id);
            $('#activeConversationId').val(id);
            $('.conversation-item').removeClass('active');
            $(this).addClass('active');

            // Close collapse on mobile if open
            if ($(window).width() < 992) {
                $('#inquiryHistoryCollapse').collapse('hide');
            }

            loadConversationMessages(id);
        });

        // Delete conversation
        $(document).on('click', '.btn-delete-conv', function(e) {
            e.stopPropagation();
            const id = $(this).data('id');
            if (confirm('Delete this conversation inquiry?')) {
                $.post(baseAdminUrl + 'ai-analyst/delete-conversation/' + id, function() {
                    $(`.conversation-item[data-id="${id}"]`).remove();
                    if (String(activeConvId) === String(id)) {
                        localStorage.removeItem('ai_active_conv_id');
                        sessionStorage.setItem('ai_user_wanted_new', '1');
                        $('#btnNewChat').click();
                    }
                });
            }
        });

        // --- REPLY MODE STATE (QUICK vs FULL DETAILED) ---
        let currentReplyMode = localStorage.getItem('ai_reply_mode') || 'quick';
        setReplyMode(currentReplyMode);

        function setReplyMode(mode) {
            currentReplyMode = mode;
            localStorage.setItem('ai_reply_mode', mode);
            $('#activeReplyMode').val(mode);

            if (mode === 'quick') {
                $('#btnModeQuick')
                    .addClass('active fw-bold')
                    .removeClass('text-muted fw-semibold')
                    .css({
                        'background': '#ffffff',
                        'color': '#0d424d',
                        'box-shadow': '0 2px 4px rgba(0,0,0,0.08)'
                    });
                $('#btnModeDetailed')
                    .removeClass('active fw-bold')
                    .addClass('text-muted fw-semibold')
                    .css({
                        'background': 'transparent',
                        'color': '#64748b',
                        'box-shadow': 'none'
                    });
                $('#replyModeHintText').text('Quick: Simple answer & exact results only (no breakdown)');
            } else {
                $('#btnModeDetailed')
                    .addClass('active fw-bold')
                    .removeClass('text-muted fw-semibold')
                    .css({
                        'background': '#ffffff',
                        'color': '#0d424d',
                        'box-shadow': '0 2px 4px rgba(0,0,0,0.08)'
                    });
                $('#btnModeQuick')
                    .removeClass('active fw-bold')
                    .addClass('text-muted fw-semibold')
                    .css({
                        'background': 'transparent',
                        'color': '#64748b',
                        'box-shadow': 'none'
                    });
                $('#replyModeHintText').text('Detailed: Full executive breakdown, metrics & charts');
            }
        }

        $(document).on('click', '.reply-mode-btn', function() {
            const mode = $(this).data('mode');
            setReplyMode(mode);
        });

        // --- STOP GENERATION STATE (ChatGPT style) ---
        let currentAbortController = null;
        let isGenerating = false;
        let currentTypingId = null;
        let currentAssistantBubbleId = null;

        function setGeneratingState(generating) {
            isGenerating = generating;
            if (generating) {
                $('#btnSend')
                    .addClass('btn-generating')
                    .attr('title', 'Stop generation')
                    .html('<i id="btnSendIcon" class="bi bi-stop-fill fs-5"></i>');
                $('#userInput').prop('disabled', true);
            } else {
                $('#btnSend')
                    .removeClass('btn-generating')
                    .attr('title', 'Send message')
                    .html('<i id="btnSendIcon" class="bi bi-arrow-up fs-5"></i>');
                $('#userInput').prop('disabled', false).focus();
                currentAbortController = null;
                currentTypingId = null;
                currentAssistantBubbleId = null;
            }
        }

        function stopGeneration() {
            if (currentAbortController) {
                currentAbortController.abort();
            }
            if (currentTypingId) {
                $('#' + currentTypingId).remove();
            }
            if (currentAssistantBubbleId) {
                $(`#${currentAssistantBubbleId} .chat-bubble-assistant`).append(
                    '<div class="text-muted small fst-italic mt-2 pt-1 border-top"><i class="bi bi-stop-circle me-1"></i>Generation stopped</div>'
                );
            }
            setGeneratingState(false);
        }

        function executePromptStream(msg, isEdit = false, isRetry = false) {
            if (!msg) return;
            const cleanMsg = cleanPromptText(msg);
            window.lastSubmittedPrompt = cleanMsg;

            const avail = parseInt($('#modal-rpd-avail').text() || '500');
            if (avail <= 0 && parseInt($('#gemini-rpd').text() || '0') >= 500) {
                alert('Your Gemini daily quota of 500 requests has been reached today. Please try again tomorrow or check Google AI Studio.');
                return;
            }

            // Show typing indicator
            const typingId = appendTypingIndicator();
            currentTypingId = typingId;

            // Initialize AbortController and set generating state
            currentAbortController = new AbortController();
            setGeneratingState(true);

            const replyMode = $('#activeReplyMode').val() || currentReplyMode || 'quick';
            const streamUrl = baseAdminUrl + 'ai-analyst/stream?conversation_id=' + encodeURIComponent(activeConvId) +
                '&message=' + encodeURIComponent(cleanMsg) +
                '&reply_mode=' + encodeURIComponent(replyMode) +
                (isRetry ? '&is_retry=1' : '');

            let assistantBubbleId = null;
            let accumulatedText = '';
            let metricsData = [];
            let chartData = null;

            fetch(streamUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'text/event-stream'
                },
                signal: currentAbortController.signal
            }).then(response => {
                const reader = response.body.getReader();
                const decoder = new TextDecoder('utf-8');
                let buffer = '';

                function readChunk() {
                    reader.read().then(({
                        done,
                        value
                    }) => {
                        if (done) {
                            $('#' + typingId).remove();
                            setGeneratingState(false);
                            return;
                        }

                        buffer += decoder.decode(value, {
                            stream: true
                        });
                        const events = buffer.split("\n\n");
                        buffer = events.pop();

                        events.forEach(evtStr => {
                            const lines = evtStr.split("\n");
                            let eventType = 'message';
                            let dataStr = '';

                            lines.forEach(l => {
                                if (l.startsWith('event: ')) {
                                    eventType = l.substring(7).trim();
                                } else if (l.startsWith('data: ')) {
                                    dataStr = l.substring(6).trim();
                                }
                            });

                            if (!dataStr) return;
                            try {
                                const parsed = JSON.parse(dataStr);

                                if (eventType === 'meta') {
                                    if (!activeConvId && parsed.conversation_id) {
                                        activeConvId = parsed.conversation_id;
                                        localStorage.setItem('ai_active_conv_id', activeConvId);
                                        $('#activeConversationId').val(activeConvId);
                                        reloadConversationList(activeConvId);
                                    }
                                    if (parsed.credits) {
                                        updateCreditsDisplay(parsed.credits);
                                    }
                                } else if (eventType === 'token') {
                                    $('#' + typingId).remove();
                                    accumulatedText += parsed.text;
                                    if (!assistantBubbleId) {
                                        assistantBubbleId = createStreamingAssistantBubble();
                                        currentAssistantBubbleId = assistantBubbleId;
                                    }
                                    updateStreamingAssistantBubble(assistantBubbleId, accumulatedText);
                                } else if (eventType === 'done') {
                                    metricsData = parsed.metrics_called || [];
                                    chartData = parsed.chart_config || null;
                                    if (parsed.credits) {
                                        updateCreditsDisplay(parsed.credits);
                                    }
                                    if (assistantBubbleId) {
                                        updateStreamingAssistantFooter(assistantBubbleId, metricsData, chartData);
                                    }
                                    setGeneratingState(false);
                                } else if (eventType === 'error') {
                                    $('#' + typingId).remove();
                                    appendAssistantMessage('Error: ' + (parsed.message || 'Processing error'));
                                    setGeneratingState(false);
                                }
                            } catch (err) {}
                        });

                        if (isGenerating) {
                            readChunk();
                        }
                    }).catch(err => {
                        $('#' + typingId).remove();
                        if (err.name !== 'AbortError') {
                            if (!accumulatedText) {
                                appendAssistantMessage('Stream connection error. Please try again.');
                            }
                        }
                        setGeneratingState(false);
                    });
                }

                readChunk();
            }).catch(err => {
                $('#' + typingId).remove();
                if (err.name !== 'AbortError') {
                    appendAssistantMessage('Network error processing request. Please try again.');
                }
                setGeneratingState(false);
            });
        }

        // Form submit handler with real-time SSE token streaming & Stop support
        $('#chatForm').submit(function(e) {
            e.preventDefault();

            // If currently streaming/generating, button acts as STOP
            if (isGenerating) {
                stopGeneration();
                return;
            }

            const msg = $('#userInput').val().trim();
            if (!msg) return;

            $('#userInput').val('').css('height', 'auto');

            // Append user message bubble and scroll down
            appendUserMessage(msg);
            scrollToBottom(true);

            // Execute prompt stream
            executePromptStream(msg);
        });

        // Auto expand textarea height as user types (ChatGPT style)
        $('#userInput').on('input', function() {
            this.style.height = 'auto';
            const newHeight = Math.min(this.scrollHeight, 160);
            this.style.height = newHeight + 'px';
        });

        // Submit on Enter without Shift (Shift+Enter creates a new line)
        $('#userInput').on('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                $('#chatForm').submit();
            }
        });

        function resetChatStream() {
            $('#chatStream').html(`
            <div class="welcome-box text-center py-4 px-3 mb-3 mx-auto">
                <div class="welcome-avatar-wrapper mb-3">
                    <div class="d-inline-flex p-3 rounded-circle shadow-sm" style="background: linear-gradient(135deg, rgba(13,66,77,0.1), rgba(20,184,166,0.15)); border: 1px solid rgba(13,66,77,0.15);">
                        <i class="bi bi-stars fs-2" style="color: #0d424d;"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-2" style="letter-spacing: -0.5px;">What can I help you analyze today?</h3>
                <p class="text-muted small mx-auto mb-4" style="max-width: 620px; font-size: 0.92rem; line-height: 1.5;">
                    Query live CRM leads, Insurance policies, Loyalty rewards, Provider earnings, Notification queues, and database metrics.
                </p>

                <div class="row g-3 text-start" id="suggestedPrompts">
                    <div class="col-md-6 col-12">
                        <div class="prompt-card prompt-chip h-100 p-3 rounded-4 border bg-white shadow-sm" data-prompt="How many active insurance policies are there?">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-shield-check text-primary fs-5"></i>
                                <span class="fw-bold text-dark small">Insurance Policies</span>
                            </div>
                            <div class="text-muted small">How many active insurance policies are there?</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="prompt-card prompt-chip h-100 p-3 rounded-4 border bg-white shadow-sm" data-prompt="How many total loyalty points there, who has maximum,minimum points?">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-gift text-success fs-5"></i>
                                <span class="fw-bold text-dark small">Loyalty Program</span>
                            </div>
                            <div class="text-muted small">Who has maximum and minimum loyalty points?</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="prompt-card prompt-chip h-100 p-3 rounded-4 border bg-white shadow-sm" data-prompt="How much providers earnings average and who earned maximum,minimum?">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-wallet2 text-danger fs-5"></i>
                                <span class="fw-bold text-dark small">Provider Earnings</span>
                            </div>
                            <div class="text-muted small">Average provider earnings, maximum and minimum earners</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="prompt-card prompt-chip h-100 p-3 rounded-4 border bg-white shadow-sm" data-prompt="Which provider not receiving notifications and who receiving but not active, active but submitting bids?">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-bell text-warning fs-5"></i>
                                <span class="fw-bold text-dark small">Notifications & Bidding</span>
                            </div>
                            <div class="text-muted small">Who is receiving notifications and submitting bids?</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="prompt-card prompt-chip h-100 p-3 rounded-4 border bg-white shadow-sm" data-prompt="How many admin, client, provider posts?">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-chat-left-text text-info fs-5"></i>
                                <span class="fw-bold text-dark small">Community Engagement</span>
                            </div>
                            <div class="text-muted small">Post distribution: Admin vs Client vs Service Providers</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="prompt-card prompt-chip h-100 p-3 rounded-4 border bg-white shadow-sm" data-prompt="What is our current Cashfree payout balance and pending withdrawals?">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-cash-stack text-secondary fs-5"></i>
                                <span class="fw-bold text-dark small">Payout Balances</span>
                            </div>
                            <div class="text-muted small">Cashfree gateway balance and pending withdrawals</div>
                        </div>
                    </div>
                </div>
            </div>
        `);
        }

        function cleanPromptText(raw) {
            if (!raw) return '';
            let cleaned = String(raw).replace(/\b(?:Cancel|Send)\b/gi, ' ').replace(/\s+/g, ' ').trim();
            const parts = cleaned.split('?').map(p => p.trim()).filter(Boolean);
            if (parts.length > 1) {
                const uniqueParts = [];
                for (const p of parts) {
                    if (!uniqueParts.some(u => u.toLowerCase() === p.toLowerCase())) {
                        uniqueParts.push(p);
                    }
                }
                cleaned = uniqueParts.join('? ') + (cleaned.endsWith('?') ? '?' : '');
            }
            return cleaned.trim();
        }

        function appendUserMessage(text) {
            $('.welcome-box').hide();
            const cleanText = cleanPromptText(text);
            window.lastSubmittedPrompt = cleanText;
            const html = `
            <div class="d-flex mb-4 justify-content-end user-msg-row" data-prompt="${escapeHtml(cleanText)}">
                <div class="chat-bubble-user">
                    <div class="user-msg-content-wrapper d-flex align-items-start gap-2">
                        <div class="user-msg-text flex-grow-1">${escapeHtml(cleanText)}</div>
                        <button type="button" class="btn btn-sm btn-edit-prompt" title="Edit prompt">
                            <i class="bi bi-pencil"></i>
                        </button>
                    </div>
                    <div class="user-msg-edit-box d-none">
                        <textarea class="form-control user-edit-textarea border-0 bg-transparent text-white p-0 shadow-none" rows="2" placeholder="Edit your prompt..."></textarea>
                        <div class="d-flex justify-content-end gap-2 mt-2 pt-2 border-top border-white-25">
                            <button type="button" class="btn btn-sm btn-edit-cancel rounded-pill px-3 text-white border-0 bg-transparent opacity-75 opacity-100-hover" style="font-size: 0.8rem;">
                                Cancel
                            </button>
                            <button type="button" class="btn btn-sm btn-edit-send rounded-pill px-3 fw-bold text-dark bg-white shadow-sm" style="font-size: 0.8rem;">
                                Send
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
            $('#chatStream').append(html);
            scrollToBottom(true);
        }

        // --- USER PROMPT EDIT HANDLERS (Edit, Cancel, Send) ---

        // 1. Open prompt editor
        $(document).on('click', '.btn-edit-prompt', function() {
            const $bubble = $(this).closest('.chat-bubble-user');
            const currentText = $bubble.find('.user-msg-text').text().trim();
            const $editBox = $bubble.find('.user-msg-edit-box');
            const $textarea = $editBox.find('.user-edit-textarea');

            $textarea.val(currentText);
            $bubble.find('.user-msg-content-wrapper').addClass('d-none');
            $editBox.removeClass('d-none');
            $bubble.addClass('in-edit-mode');
            $textarea.focus();
            $textarea.css('height', 'auto');
            $textarea.css('height', Math.max(48, $textarea[0].scrollHeight) + 'px');
        });

        // 2. Cancel prompt edit
        $(document).on('click', '.btn-edit-cancel', function() {
            const $bubble = $(this).closest('.chat-bubble-user');
            $bubble.find('.user-msg-edit-box').addClass('d-none');
            $bubble.find('.user-msg-content-wrapper').removeClass('d-none');
            $bubble.removeClass('in-edit-mode');
        });

        // 3. Auto expand textarea as user types
        $(document).on('input', '.user-edit-textarea', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // 4. Send edited prompt
        $(document).on('click', '.btn-edit-send', function() {
            const $bubble = $(this).closest('.chat-bubble-user');
            const $userRow = $bubble.closest('.user-msg-row');
            const newText = cleanPromptText($bubble.find('.user-edit-textarea').val().trim());

            if (!newText) return;

            // Update display text and exit edit mode
            $bubble.find('.user-msg-text').text(newText);
            $userRow.attr('data-prompt', newText);
            window.lastSubmittedPrompt = newText;
            $bubble.find('.user-msg-edit-box').addClass('d-none');
            $bubble.find('.user-msg-content-wrapper').removeClass('d-none');
            $bubble.removeClass('in-edit-mode');

            // Remove all subsequent assistant & user bubbles after this prompt row
            $userRow.nextAll().remove();

            // If currently generating, abort existing stream
            if (isGenerating) {
                stopGeneration();
            }

            // Execute prompt stream with updated text
            executePromptStream(newText, true);
        });

        // 5. Keyboard shortcuts (Enter to send, Escape to cancel)
        $(document).on('keydown', '.user-edit-textarea', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                $(this).closest('.chat-bubble-user').find('.btn-edit-send').click();
            } else if (e.key === 'Escape') {
                $(this).closest('.chat-bubble-user').find('.btn-edit-cancel').click();
            }
        });

        function formatMessageTime(dateStr) {
            if (dateStr) {
                const d = new Date(dateStr);
                if (!isNaN(d.getTime())) {
                    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    let h = d.getHours();
                    const m = d.getMinutes();
                    const ampm = h >= 12 ? 'PM' : 'AM';
                    h = h % 12 || 12;
                    return `${days[d.getDay()]}, ${h}:${m < 10 ? '0' + m : m} ${ampm}`;
                }
            }
            const now = new Date();
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            let h = now.getHours();
            const m = now.getMinutes();
            const ampm = h >= 12 ? 'PM' : 'AM';
            h = h % 12 || 12;
            return `${days[now.getDay()]}, ${h}:${m < 10 ? '0' + m : m} ${ampm}`;
        }

        function renderActionBarHtml(timestamp) {
            const timeStr = formatMessageTime(timestamp);
            return `
            <div class="msg-action-bar d-flex align-items-center gap-1 mt-2 pt-1 border-top" style="border-color: rgba(0,0,0,0.06) !important;">
                <button type="button" class="btn-icon-msg btn-copy-msg" title="Copy response">
                    <i class="bi bi-clipboard"></i>
                </button>
                <button type="button" class="btn-icon-msg btn-like-msg" title="Good response">
                    <i class="bi bi-hand-thumbs-up"></i>
                </button>
                <button type="button" class="btn-icon-msg btn-dislike-msg" title="Bad response">
                    <i class="bi bi-hand-thumbs-down"></i>
                </button>
                <button type="button" class="btn-icon-msg btn-retry-msg" title="Try again">
                    <i class="bi bi-arrow-repeat"></i>
                </button>
                <button type="button" class="btn-icon-msg btn-share-msg" title="Share response">
                    <i class="bi bi-share"></i>
                </button>
                <div class="msg-dropdown">
                    <button type="button" class="btn-icon-msg btn-more-msg" title="More options">
                        <i class="bi bi-three-dots"></i>
                    </button>
                    <div class="msg-dropdown-menu">
                        <div class="msg-dropdown-header">
                            <i class="bi bi-clock me-1"></i> <span class="msg-time-label">${escapeHtml(timeStr)}</span>
                        </div>
                        <div class="msg-dropdown-divider"></div>
                        <a class="msg-dropdown-item btn-branch-chat" href="javascript:void(0);">
                            <i class="bi bi-diagram-3 text-info"></i> <span>Branch in new chat</span>
                        </a>
                        <a class="msg-dropdown-item btn-read-aloud" href="javascript:void(0);">
                            <i class="bi bi-volume-up text-warning"></i> <span class="read-aloud-text">Read aloud</span>
                        </a>
                    </div>
                </div>
            </div>
        `;
        }

        function showToast(message) {
            let $container = $('#chatToastContainer');
            if (!$container.length) {
                $('body').append('<div class="chat-toast-container" id="chatToastContainer"></div>');
                $container = $('#chatToastContainer');
            }
            const toastId = 'toast_' + Date.now();
            const html = `
            <div class="chat-toast" id="${toastId}">
                <i class="bi bi-info-circle text-info"></i>
                <span>${escapeHtml(message)}</span>
            </div>
        `;
            $container.append(html);
            const $toast = $('#' + toastId);
            setTimeout(() => $toast.addClass('show'), 20);
            setTimeout(() => {
                $toast.removeClass('show');
                setTimeout(() => $toast.remove(), 300);
            }, 2800);
        }

        function appendAssistantMessage(text, metrics = [], chartConfig = null, timestamp = null, associatedPrompt = null) {
            $('.welcome-box').hide();
            const msgId = 'chart_' + Date.now();
            const promptAttr = escapeHtml(associatedPrompt || window.lastSubmittedPrompt || '');

            let metricsBadgeHtml = '';

            let chartCanvasHtml = '';
            if (chartConfig) {
                chartCanvasHtml = `
                <div class="mt-3 p-3 bg-white border rounded-4 shadow-sm">
                    <canvas id="${msgId}" style="max-height: 280px; width: 100%;"></canvas>
                </div>
            `;
            }

            const actionBarHtml = renderActionBarHtml(timestamp);

            const html = `
            <div class="d-flex mb-4 justify-content-start assistant-msg-row" data-prompt="${promptAttr}">
                <div class="chat-bubble-assistant">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-light">
                        <div class="d-flex align-items-center gap-2">
                            <div class="ai-avatar-circle">
                                <i class="bi bi-stars"></i>
                            </div>
                            <div>
                                <strong class="text-dark" style="font-size: 0.94rem;">Petsfolio BI Analyst</strong>
                                <span class="badge bg-light text-muted border ms-1" style="font-size: 0.68rem; font-weight: 600;">Executive Intelligence</span>
                            </div>
                        </div>
                        ${timestamp ? `<small class="text-muted" style="font-size: 0.74rem;"><i class="bi bi-clock me-1"></i>${formatMessageTime(timestamp)}</small>` : ''}
                    </div>
                    <div class="markdown-body">${formatMarkdown(text)}</div>
                    ${chartCanvasHtml}
                    ${metricsBadgeHtml}
                    ${actionBarHtml}
                </div>
            </div>
        `;

            $('#chatStream').append(html);
            scrollToBottom(false);

            if (chartConfig && document.getElementById(msgId)) {
                new Chart(document.getElementById(msgId), chartConfig);
            }
        }

        function createStreamingAssistantBubble() {
            $('.welcome-box').hide();
            const bubbleId = 'msg_' + Date.now();
            const promptAttr = escapeHtml(window.lastSubmittedPrompt || '');
            const html = `
            <div class="d-flex mb-4 justify-content-start assistant-msg-row" id="${bubbleId}" data-prompt="${promptAttr}">
                <div class="chat-bubble-assistant">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-light">
                        <div class="d-flex align-items-center gap-2">
                            <div class="ai-avatar-circle">
                                <i class="bi bi-stars"></i>
                            </div>
                            <div>
                                <strong class="text-dark" style="font-size: 0.94rem;">Petsfolio BI Analyst</strong>
                                <span class="badge bg-light text-muted border ms-1" style="font-size: 0.68rem; font-weight: 600;">Executive Intelligence</span>
                            </div>
                        </div>
                    </div>
                    <div class="markdown-body stream-text"></div>
                    <div class="stream-chart-container"></div>
                    <div class="stream-metrics-container"></div>
                </div>
            </div>
        `;
            $('#chatStream').append(html);
            scrollToBottom(false);
            return bubbleId;
        }

        function updateStreamingAssistantBubble(bubbleId, text) {
            $(`#${bubbleId} .stream-text`).html(formatMarkdown(text));
            scrollToBottom(false);
        }

        function updateStreamingAssistantFooter(bubbleId, metrics = [], chartConfig = null) {
            $(`#${bubbleId} .stream-metrics-container`).empty();

            if (chartConfig) {
                const canvasId = 'chart_' + Date.now();
                $(`#${bubbleId} .stream-chart-container`).html(`
                <div class="mt-3 p-3 bg-white border rounded-4 shadow-sm">
                    <canvas id="${canvasId}" style="max-height: 280px; width: 100%;"></canvas>
                </div>
            `);
                if (document.getElementById(canvasId)) {
                    new Chart(document.getElementById(canvasId), chartConfig);
                }
            }

            if (!$(`#${bubbleId} .msg-action-bar`).length) {
                $(`#${bubbleId} .chat-bubble-assistant`).append(renderActionBarHtml());
            }

            scrollToBottom(false);
        }

        function appendTypingIndicator() {
            const id = 'typing_' + Date.now();
            const html = `
            <div class="d-flex mb-4 justify-content-start assistant-msg-row" id="${id}">
                <div class="chat-bubble-assistant py-2 px-3 d-inline-block" style="width: auto; max-width: 260px;">
                    <div class="typing-indicator d-flex gap-2 align-items-center">
                        <div class="ai-avatar-circle" style="width: 24px; height: 24px; font-size: 0.75rem;">
                            <i class="bi bi-stars"></i>
                        </div>
                        <small class="text-muted fw-semibold" style="font-size: 0.8rem;">Thinking</small>
                        <span></span><span></span><span></span>
                    </div>
                </div>
            </div>
        `;
            $('#chatStream').append(html);
            scrollToBottom(false);
            return id;
        }

        function loadConversationMessages(id) {
            if (!id) return;

            $('#chatStream').html(`
            <div class="d-flex justify-content-center align-items-center py-5 my-5 text-muted">
                <div class="spinner-border text-teal spinner-border-sm me-2" role="status" style="color: #0d424d;"></div>
                <span class="small fw-semibold">Loading conversation...</span>
            </div>
        `);

            $.get(baseAdminUrl + 'ai-analyst/messages/' + id, function(res) {
                $('#chatStream').empty();
                if (res.status && res.messages && res.messages.length > 0) {
                    let lastPrompt = '';
                    res.messages.forEach(msg => {
                        if (msg.role === 'user') {
                            lastPrompt = msg.content;
                            appendUserMessage(msg.content);
                        } else {
                            appendAssistantMessage(msg.content, msg.metrics_called, msg.chart_config, msg.created_at, lastPrompt);
                        }
                    });
                    scrollToBottom(true);
                } else {
                    resetChatStream();
                }
            }).fail(function() {
                $('#chatStream').html(`
                <div class="text-center py-5 text-danger small">
                    <i class="bi bi-exclamation-triangle fs-3 d-block mb-2"></i>
                    Failed to load conversation messages. <a href="javascript:void(0);" onclick="loadConversationMessages(${id})">Retry</a>
                </div>
            `);
            });
        }

        // --- MESSAGE ACTION HANDLERS (Copy, Like, Dislike, Retry, Share, Branch, Read Aloud) ---

        // 1. Copy response text to clipboard
        $(document).on('click', '.btn-copy-msg', function() {
            const $bubble = $(this).closest('.chat-bubble-assistant');
            const text = $bubble.find('.markdown-body').text().trim();
            if (!text) return;

            const $btn = $(this);
            navigator.clipboard.writeText(text).then(function() {
                $btn.html('<i class="bi bi-check2 text-success"></i>');
                showToast('Copied to clipboard');
                setTimeout(() => {
                    $btn.html('<i class="bi bi-clipboard"></i>');
                }, 2000);
            }).catch(function() {
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                $btn.html('<i class="bi bi-check2 text-success"></i>');
                showToast('Copied to clipboard');
                setTimeout(() => {
                    $btn.html('<i class="bi bi-clipboard"></i>');
                }, 2000);
            });
        });

        // 2. Like (Good response)
        $(document).on('click', '.btn-like-msg', function() {
            const $btn = $(this);
            const $dislikeBtn = $btn.siblings('.btn-dislike-msg');

            if ($btn.hasClass('active-like')) {
                $btn.removeClass('active-like').html('<i class="bi bi-hand-thumbs-up"></i>');
            } else {
                $btn.addClass('active-like').html('<i class="bi bi-hand-thumbs-up-fill"></i>');
                $dislikeBtn.removeClass('active-dislike').html('<i class="bi bi-hand-thumbs-down"></i>');
                showToast('Thanks for your feedback!');
            }
        });

        // 3. Dislike (Bad response)
        $(document).on('click', '.btn-dislike-msg', function() {
            const $btn = $(this);
            const $likeBtn = $btn.siblings('.btn-like-msg');

            if ($btn.hasClass('active-dislike')) {
                $btn.removeClass('active-dislike').html('<i class="bi bi-hand-thumbs-down"></i>');
            } else {
                $btn.addClass('active-dislike').html('<i class="bi bi-hand-thumbs-down-fill"></i>');
                $likeBtn.removeClass('active-like').html('<i class="bi bi-hand-thumbs-up"></i>');
                showToast('Feedback submitted');
            }
        });

        // 4. Try again (Regenerate)
        $(document).on('click', '.btn-retry-msg', function() {
            const $msgRow = $(this).closest('.assistant-msg-row, .d-flex');
            let promptText = '';

            // 1. Direct attribute on the assistant row itself
            if ($msgRow.attr('data-prompt')) {
                promptText = $msgRow.attr('data-prompt').trim();
            }

            // 2. Look backwards for the preceding user message row
            if (!promptText && $msgRow.length) {
                let $prevUserRow = $msgRow.prevAll('.user-msg-row').first();
                if (!$prevUserRow.length) {
                    $prevUserRow = $msgRow.prevAll('.d-flex').has('.chat-bubble-user, .user-msg-text').first();
                }
                if ($prevUserRow.length) {
                    if ($prevUserRow.attr('data-prompt')) {
                        promptText = $prevUserRow.attr('data-prompt').trim();
                    } else {
                        const $userTextEl = $prevUserRow.find('.user-msg-text');
                        if ($userTextEl.length) {
                            promptText = $userTextEl.text().trim();
                        } else {
                            promptText = $prevUserRow.find('.chat-bubble-user').clone().find('.user-msg-edit-box, .btn-edit-prompt').remove().end().text().trim();
                        }
                    }
                }
            }

            // 3. Fallback to global tracking variable
            if (!promptText && window.lastSubmittedPrompt) {
                promptText = window.lastSubmittedPrompt;
            }

            // 4. Fallback to the latest user message in the chat stream
            if (!promptText) {
                const $lastUserRow = $('#chatStream').find('.user-msg-row, .chat-bubble-user').last();
                if ($lastUserRow.length) {
                    promptText = $lastUserRow.attr('data-prompt') || $lastUserRow.find('.user-msg-text').text().trim();
                }
            }

            // 5. Fallback to current input box value
            if (!promptText) {
                promptText = $('#userInput').val().trim();
            }

            promptText = cleanPromptText(promptText);

            if (promptText) {
                // If currently generating, abort existing stream
                if (isGenerating) {
                    stopGeneration();
                }

                // Remove the assistant row being retried so duplicate/error bubbles don't stack
                if ($msgRow.length) {
                    $msgRow.remove();
                }

                // Ensure a user row exists in the chat stream
                const hasVisibleUserRow = $('#chatStream').find('.user-msg-row').length > 0;
                if (!hasVisibleUserRow) {
                    appendUserMessage(promptText);
                }

                showToast('Regenerating response...');
                executePromptStream(promptText, true, true);
            } else {
                showToast('Could not find original prompt to retry.');
            }
        });

        // 5. Share response
        $(document).on('click', '.btn-share-msg', function() {
            const $bubble = $(this).closest('.chat-bubble-assistant');
            const text = $bubble.find('.markdown-body').text().trim();
            if (!text) return;

            const shareTitle = 'Petsfolio AI Business Analyst Report';
            const shareText = `Petsfolio BI Analyst Report:\n\n${text}\n\nGenerated via Petsfolio AI Analyst`;

            if (navigator.share) {
                navigator.share({
                    title: shareTitle,
                    text: shareText
                }).catch(err => {
                    if (err.name !== 'AbortError') {
                        copyShareText(shareText);
                    }
                });
            } else {
                copyShareText(shareText);
            }

            function copyShareText(str) {
                navigator.clipboard.writeText(str).then(() => {
                    showToast('Shareable report copied to clipboard!');
                }).catch(() => {
                    showToast('Report copied!');
                });
            }
        });

        // 6. Branch in new chat
        $(document).on('click', '.btn-branch-chat', function() {
            const $msgRow = $(this).closest('.assistant-msg-row, .d-flex');
            let promptText = '';

            // 1. Direct attribute on the assistant row
            if ($msgRow.attr('data-prompt')) {
                promptText = $msgRow.attr('data-prompt').trim();
            }

            // 2. Preceding user message row
            if (!promptText && $msgRow.length) {
                let $prevUserRow = $msgRow.prevAll('.user-msg-row').first();
                if (!$prevUserRow.length) {
                    $prevUserRow = $msgRow.prevAll('.d-flex').has('.chat-bubble-user, .user-msg-text').first();
                }
                if ($prevUserRow.length) {
                    if ($prevUserRow.attr('data-prompt')) {
                        promptText = $prevUserRow.attr('data-prompt').trim();
                    } else {
                        const $userTextEl = $prevUserRow.find('.user-msg-text');
                        if ($userTextEl.length) {
                            promptText = $userTextEl.text().trim();
                        } else {
                            promptText = $prevUserRow.find('.chat-bubble-user').clone().find('.user-msg-edit-box, .btn-edit-prompt').remove().end().text().trim();
                        }
                    }
                }
            }

            // 3. Fallbacks
            if (!promptText && window.lastSubmittedPrompt) {
                promptText = window.lastSubmittedPrompt;
            }
            if (!promptText) {
                const $lastUserRow = $('#chatStream').find('.user-msg-row, .chat-bubble-user').last();
                if ($lastUserRow.length) {
                    promptText = $lastUserRow.attr('data-prompt') || $lastUserRow.find('.user-msg-text').text().trim();
                }
            }

            promptText = cleanPromptText(promptText);
            const assistantText = $msgRow.find('.markdown-body').text().trim();

            if (!promptText && !assistantText) {
                showToast('Cannot branch empty message.');
                return;
            }

            showToast('Branching into new chat...');

            $.post(baseAdminUrl + 'ai-analyst/branch-conversation', {
                user_prompt: promptText || 'Branched Inquiry',
                assistant_text: assistantText
            }, function(res) {
                if (res.status && res.conversation_id) {
                    activeConvId = res.conversation_id;
                    $('#activeConversationId').val(activeConvId);
                    reloadConversationList();
                    loadConversationMessages(activeConvId);
                    showToast('Branched into new chat!');
                } else {
                    showToast('Failed to branch chat: ' + (res.message || 'Error'));
                }
            }).fail(function() {
                showToast('Network error while branching chat.');
            });
        });

        // 7. Read aloud (Text-to-Speech)
        let currentSpeakingUtterance = null;
        let currentSpeakingBtn = null;

        $(document).on('click', '.btn-read-aloud', function() {
            const $btn = $(this);
            const $bubble = $btn.closest('.chat-bubble-assistant');
            const text = $bubble.find('.markdown-body').text().trim();

            if (!('speechSynthesis' in window)) {
                alert('Text-to-speech is not supported in this browser.');
                return;
            }

            if (window.speechSynthesis.speaking) {
                window.speechSynthesis.cancel();
                if (currentSpeakingBtn) {
                    currentSpeakingBtn.html('<i class="bi bi-volume-up text-warning"></i> <span class="read-aloud-text">Read aloud</span>');
                    currentSpeakingBtn.closest('.msg-action-bar').find('.btn-icon-msg.dropdown-toggle').removeClass('active-speaking');
                }
                if (currentSpeakingBtn && currentSpeakingBtn[0] === $btn[0]) {
                    currentSpeakingBtn = null;
                    showToast('Speech stopped');
                    return;
                }
            }

            if (!text) return;

            let cleanText = text
                .replace(/```[\s\S]*?```/g, ' Code snippet omitted. ')
                .replace(/`([^`]+)`/g, '$1')
                .replace(/[#*_\-\|]/g, ' ')
                .replace(/\s+/g, ' ')
                .trim();

            const utterance = new SpeechSynthesisUtterance(cleanText);
            utterance.rate = 1.0;
            utterance.pitch = 1.0;

            const $dropdownToggle = $btn.closest('.msg-action-bar').find('.btn-icon-msg.dropdown-toggle');

            utterance.onstart = function() {
                currentSpeakingBtn = $btn;
                $btn.html('<i class="bi bi-stop-circle text-danger"></i> <span class="read-aloud-text">Stop reading</span>');
                $dropdownToggle.addClass('active-speaking');
                showToast('Reading aloud...');
            };

            utterance.onend = function() {
                $btn.html('<i class="bi bi-volume-up text-warning"></i> <span class="read-aloud-text">Read aloud</span>');
                $dropdownToggle.removeClass('active-speaking');
                currentSpeakingBtn = null;
            };

            utterance.onerror = function() {
                $btn.html('<i class="bi bi-volume-up text-warning"></i> <span class="read-aloud-text">Read aloud</span>');
                $dropdownToggle.removeClass('active-speaking');
                currentSpeakingBtn = null;
            };

            window.speechSynthesis.speak(utterance);
        });

        // Toggle 3 dots dropdown menu on click
        $(document).on('click', '.btn-more-msg', function(e) {
            e.stopPropagation();
            const $dropdown = $(this).closest('.msg-dropdown');
            const $menu = $dropdown.find('.msg-dropdown-menu');
            const isShown = $menu.hasClass('show');

            // Close all other open dropdowns
            $('.msg-dropdown-menu').removeClass('show');

            if (!isShown) {
                $menu.addClass('show');
            }
        });

        // Close dropdowns when clicking anywhere outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.msg-dropdown').length) {
                $('.msg-dropdown-menu').removeClass('show');
            }
        });

        // Close dropdown menu when clicking any menu item inside
        $(document).on('click', '.msg-dropdown-item', function() {
            $(this).closest('.msg-dropdown-menu').removeClass('show');
        });

        function formatConversationDateTime(dateStr) {
            if (!dateStr) return 'Just now';
            const d = new Date(dateStr.replace(/-/g, '/'));
            if (isNaN(d.getTime())) return dateStr;

            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const day = d.getDate();
            const month = months[d.getMonth()];
            const year = d.getFullYear();
            let hours = d.getHours();
            const minutes = d.getMinutes();
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12;
            const formattedMinutes = minutes < 10 ? '0' + minutes : minutes;

            return `${day} ${month} ${year}, ${hours}:${formattedMinutes} ${ampm}`;
        }

        function reloadConversationList(preferredId) {
            const userExplicitlyClickedNew = sessionStorage.getItem('ai_user_wanted_new') === '1';
            const targetId = preferredId || activeConvId || localStorage.getItem('ai_active_conv_id') || '';
            $.get(baseAdminUrl + 'ai-analyst/conversations', function(res) {
                if (res.status && res.conversations && res.conversations.length > 0) {
                    let matchedConvId = '';

                    // Find if targetId exists in current conversation list
                    res.conversations.forEach(c => {
                        if (targetId && String(c.id) === String(targetId)) {
                            matchedConvId = c.id;
                        }
                    });

                    // Default to most recent conversation if no matching ID and user didn't click New
                    if (!matchedConvId && !userExplicitlyClickedNew && res.conversations.length > 0) {
                        matchedConvId = res.conversations[0].id;
                    }

                    if (matchedConvId) {
                        activeConvId = matchedConvId;
                        localStorage.setItem('ai_active_conv_id', matchedConvId);
                        $('#activeConversationId').val(matchedConvId);
                    }

                    let html = '';
                    res.conversations.forEach(c => {
                        const activeClass = (String(c.id) === String(activeConvId)) ? 'active' : '';
                        html += `
                        <div class="conversation-item d-flex justify-content-between align-items-center ${activeClass}" 
                             data-id="${c.id}" data-title="${escapeHtml(c.title.toLowerCase())}">
                            <div class="text-truncate me-2" style="max-width: 170px;">
                                <div class="fw-semibold text-dark small text-truncate">${escapeHtml(c.title)}</div>
                                <small class="text-muted" style="font-size: 0.72rem;">
                                    <i class="bi bi-clock me-1"></i>${formatConversationDateTime(c.created_at)}
                                </small>
                            </div>
                            <button class="btn btn-link text-danger p-0 border-0 btn-delete-conv opacity-75 opacity-100-hover" data-id="${c.id}" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    `;
                    });
                    $('#conversationList').html(html);

                    // Auto load conversation messages if activeConvId is set and stream currently empty
                    if (activeConvId && !userExplicitlyClickedNew && ($('.welcome-box').is(':visible') || $('#chatStream').children().length === 0)) {
                        loadConversationMessages(activeConvId);
                    }
                } else {
                    $('#conversationList').html(`
                    <div class="text-center py-4 text-muted small" id="noConversationsMsg">
                        <i class="bi bi-chat-left-dots fs-3 d-block mb-2 text-secondary"></i>
                        No previous conversations yet.
                    </div>
                `);
                }
            });
        }

        let isUserScrolledUp = false;

        function checkScrollPosition() {
            const stream = document.getElementById('chatStream');
            if (!stream) return;

            const threshold = 120; // px from bottom
            const distanceToBottom = stream.scrollHeight - stream.scrollTop - stream.clientHeight;

            // Show direct down button if user scrolled up
            if (stream.scrollHeight > stream.clientHeight + 80 && distanceToBottom > threshold) {
                isUserScrolledUp = true;
                $('#btnScrollBottom').addClass('show');
            } else {
                isUserScrolledUp = false;
                $('#btnScrollBottom').removeClass('show');
            }
        }

        $('#chatStream').on('scroll', checkScrollPosition);

        $('#btnScrollBottom').on('click', function(e) {
            e.preventDefault();
            scrollToBottom(true);
        });

        function scrollToBottom(force = false) {
            const stream = document.getElementById('chatStream');
            if (!stream) return;

            if (force) {
                isUserScrolledUp = false;
                $('#btnScrollBottom').removeClass('show');
                stream.scrollTo({
                    top: stream.scrollHeight,
                    behavior: 'smooth'
                });
            } else if (!isUserScrolledUp) {
                stream.scrollTop = stream.scrollHeight;
            } else {
                $('#btnScrollBottom').addClass('show');
            }
        }

        function escapeHtml(text) {
            return $('<div>').text(text).html();
        }

        // Copy Code Block Handler (ChatGPT style)
        $(document).on('click', '.btn-copy-code', function() {
            const $btn = $(this);
            const code = $btn.closest('.chat-code-block').find('pre code').text();
            navigator.clipboard.writeText(code).then(() => {
                $btn.html('<i class="bi bi-check2 text-success me-1"></i> Copied!');
                setTimeout(() => {
                    $btn.html('<i class="bi bi-clipboard me-1"></i> Copy code');
                }, 2000);
            });
        });

        // Advanced Markdown Formatter supporting GFM Markdown tables, headers, code blocks, lists, bolding & stat callouts
        function formatMarkdown(text) {
            if (!text) return '';
            let lines = text.split('\n');
            let inTable = false;
            let tableHeaderDone = false;
            let inCode = false;
            let codeLang = '';
            let codeLines = [];
            let htmlResult = [];

            for (let i = 0; i < lines.length; i++) {
                let line = lines[i];
                let trimmed = line.trim();

                // Handle Code Blocks ```lang
                if (trimmed.startsWith('```')) {
                    if (!inCode) {
                        if (inTable) {
                            inTable = false;
                            htmlResult.push('</tbody></table></div>');
                        }
                        inCode = true;
                        codeLang = trimmed.substring(3).trim() || 'code';
                        codeLines = [];
                        continue;
                    } else {
                        inCode = false;
                        const safeCode = escapeHtml(codeLines.join('\n'));
                        htmlResult.push(`
                            <div class="chat-code-block mb-3 rounded-3 overflow-hidden border">
                                <div class="chat-code-header d-flex align-items-center justify-content-between px-3 py-1 bg-dark text-white-50" style="font-size: 0.76rem;">
                                    <span class="text-uppercase fw-semibold">${escapeHtml(codeLang)}</span>
                                    <button type="button" class="btn btn-sm btn-link text-white-50 text-decoration-none btn-copy-code p-0" title="Copy code">
                                        <i class="bi bi-clipboard me-1"></i> Copy code
                                    </button>
                                </div>
                                <pre class="m-0 p-3 bg-dark text-light overflow-auto" style="font-size: 0.86rem; line-height: 1.5;"><code>${safeCode}</code></pre>
                            </div>
                        `);
                        continue;
                    }
                }

                if (inCode) {
                    codeLines.push(line);
                    continue;
                }

                // Check for Markdown Table syntax | col1 | col2 |
                if (trimmed.startsWith('|') && trimmed.endsWith('|')) {
                    let cells = trimmed.split('|').slice(1, -1).map(c => c.trim());

                    // Table separator line |---|---|
                    if (cells.every(c => /^:?-+:?$/.test(c))) {
                        tableHeaderDone = true;
                        continue;
                    }

                    if (!inTable) {
                        inTable = true;
                        tableHeaderDone = false;
                        htmlResult.push('<div class="bi-table-wrapper"><table class="table bi-data-table table-striped table-hover mb-0"><thead><tr>');
                        cells.forEach(c => htmlResult.push(`<th>${inlineFormatting(c)}</th>`));
                        htmlResult.push('</tr></thead><tbody>');
                    } else {
                        htmlResult.push('<tr>');
                        cells.forEach(c => htmlResult.push(`<td>${inlineFormatting(c)}</td>`));
                        htmlResult.push('</tr>');
                    }
                    continue;
                } else if (inTable) {
                    inTable = false;
                    htmlResult.push('</tbody></table></div>');
                }

                // Headings
                if (trimmed.startsWith('### ')) {
                    htmlResult.push(`<h6 class="fw-bold text-dark mt-3 mb-2">${inlineFormatting(trimmed.substring(4))}</h6>`);
                } else if (trimmed.startsWith('#### ')) {
                    htmlResult.push(`<strong class="text-dark d-block mt-2 mb-1">${inlineFormatting(trimmed.substring(5))}</strong>`);
                } else if (trimmed.startsWith('• ') || trimmed.startsWith('* ') || trimmed.startsWith('- ')) {
                    htmlResult.push(`<div class="ps-3 mb-1">• ${inlineFormatting(trimmed.substring(2))}</div>`);
                } else if (/^\d+\.\s/.test(trimmed)) {
                    htmlResult.push(`<div class="ps-3 mb-1">${inlineFormatting(trimmed)}</div>`);
                } else if (trimmed.length > 0) {
                    htmlResult.push(`<p class="mb-2">${inlineFormatting(trimmed)}</p>`);
                } else {
                    htmlResult.push('<div class="my-1"></div>');
                }
            }

            if (inCode) {
                const safeCode = escapeHtml(codeLines.join('\n'));
                htmlResult.push(`<pre class="p-3 bg-dark text-light rounded-3 overflow-auto"><code>${safeCode}</code></pre>`);
            }

            if (inTable) {
                htmlResult.push('</tbody></table></div>');
            }

            return htmlResult.join('');
        }

        function inlineFormatting(str) {
            if (!str) return '';
            let s = escapeHtml(str);
            // Bold
            s = s.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            // Italic
            s = s.replace(/\*(.*?)\*/g, '<em>$1</em>');
            // Inline code
            s = s.replace(/`([^`]+)`/g, '<code class="bg-light px-2 py-1 rounded text-danger small">$1</code>');
            return s;
        }
    });
</script>

<!-- Gemini 3.1 Flash Lite Rate Limits & Real Credits Modal -->
<div class="modal fade" id="geminiLimitsModal" tabindex="-1" aria-labelledby="geminiLimitsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0" style="background: #0d424d; color: white;">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="geminiLimitsModalLabel">
                    <i class="bi bi-stars text-warning"></i> Gemini 3.1 Flash Lite Quota & Real Credits
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <div>
                        <div class="fw-bold fs-5 text-dark">Text-out models</div>
                        <div class="text-muted small">Live Google AI Studio Rate Limits & Database Verified Query Usage</div>
                    </div>
                    <span class="badge bg-success px-3 py-2 fs-6 rounded-pill">
                        <i class="bi bi-check-circle-fill me-1"></i> Active (Direct Gemini)
                    </span>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="p-3 rounded-4 bg-light border text-center">
                            <div class="text-muted small text-uppercase fw-bold mb-1">Requests / Min (RPM)</div>
                            <div class="fs-4 fw-bold" id="modal-gemini-rpm" style="color: #0284c7;">
                                <?= esc($credits['rpm'] ?? '0 / 15') ?>
                            </div>
                            <div class="text-muted" style="font-size: 0.75rem;">15 requests per min max</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-4 bg-light border text-center">
                            <div class="text-muted small text-uppercase fw-bold mb-1">Tokens / Min (TPM)</div>
                            <div class="fs-4 fw-bold text-info" id="modal-gemini-tpm">
                                <?= esc($credits['tpm'] ?? '0 / 250K') ?>
                            </div>
                            <div class="text-muted" style="font-size: 0.75rem;">250,000 tokens per min limit</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-4 bg-light border text-center">
                            <div class="text-muted small text-uppercase fw-bold mb-1">Requests / Day (RPD)</div>
                            <div class="fs-4 fw-bold text-warning" id="modal-gemini-rpd">
                                <?= esc($credits['rpd'] ?? '0 / 500') ?>
                            </div>
                            <div class="text-muted" style="font-size: 0.75rem;">Real Credits (<span id="modal-rpd-avail"><?= esc($credits['rpd_available'] ?? 500) ?></span> remaining today)</div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 bg-light rounded-3 p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-dark"><i class="bi bi-clock-history me-1 text-primary"></i> Real Credits & Query Audit</span>
                        <span class="badge bg-primary rounded-pill" id="modal-total-queries-all"><?= esc($credits['real_queries_all'] ?? 0) ?> Total Queries Logged</span>
                    </div>
                    <div class="row g-2 small text-muted">
                        <div class="col-sm-6">
                            • <strong>Today's Live Queries:</strong> <span id="modal-queries-today"><?= esc($credits['real_queries_today'] ?? 0) ?></span> / 500 queries
                        </div>
                        <div class="col-sm-6">
                            • <strong>Available Credits Today:</strong> <span id="modal-queries-avail"><?= esc($credits['rpd_available'] ?? 500) ?></span> queries
                        </div>
                        <div class="col-sm-6">
                            • <strong>Active Model:</strong> <code>gemini-3.1-flash-lite</code>
                        </div>
                        <div class="col-sm-6">
                            • <strong>API Endpoint:</strong> Google Generative Language v1beta
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-2 border-top">
                    <span class="text-muted small">Official Google AI Studio Rate Limit Dashboard:</span>
                    <a href="https://aistudio.google.com/rate-limit?timeRange=last-28-days&project=petsfolio-688ca"
                        target="_blank"
                        class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center gap-1">
                        <span>View Rate Limits in Google AI Studio</span>
                        <i class="bi bi-box-arrow-up-right"></i>
                    </a>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>