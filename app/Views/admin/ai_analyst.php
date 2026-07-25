<?= $this->extend('admin/includes/header'); ?>

<?= $this->section('content'); ?>

<main class="main-content">
    <div class="position-relative iq-banner">
        <?= $this->include('admin/nav_bar') ?>
    </div>

    <div class="container-fluid px-4 content-inner pb-4" id="page_layout">
        <div class="row">
            <!-- Left Sidebar: Conversations List -->
            <div class="col-lg-3 col-md-4 mb-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-clock-history text-primary fs-5"></i> Inquiry History
                        </h6>
                        <button class="btn btn-sm btn-primary rounded-pill px-3 d-flex align-items-center gap-1" id="btnNewChat">
                            <i class="bi bi-plus-lg"></i> New
                        </button>
                    </div>
                    <div class="card-body p-2 overflow-auto" style="max-height: 600px;" id="conversationList">
                        <?php if (empty($conversations)): ?>
                            <div class="text-center py-4 text-muted small" id="noConversationsMsg">
                                <i class="bi bi-chat-left-dots fs-3 d-block mb-2 text-secondary"></i>
                                No previous conversations yet.
                            </div>
                        <?php else: ?>
                            <?php foreach ($conversations as $conv): ?>
                                <div class="conversation-item p-2 rounded-3 mb-1 d-flex justify-content-between align-items-center cursor-pointer" 
                                     data-id="<?= $conv['id'] ?>"
                                     style="transition: background-color 0.2s;">
                                    <div class="text-truncate me-2" style="max-width: 170px;">
                                        <div class="fw-semibold text-dark small text-truncate"><?= esc($conv['title']) ?></div>
                                        <small class="text-muted" style="font-size: 0.75rem;"><?= date('M d, g:i a', strtotime($conv['created_at'])) ?></small>
                                    </div>
                                    <button class="btn btn-link text-danger p-0 border-0 btn-delete-conv" data-id="<?= $conv['id'] ?>" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right Area: Main Chat Window -->
            <div class="col-lg-9 col-md-8 mb-4">
                <div class="card border-0 shadow-sm rounded-4 d-flex flex-column" style="height: 680px;">
                    <!-- Chat Header -->
                    <div class="card-header bg-gradient-primary text-white p-3 rounded-top-4 d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="bi bi-cpu fs-4 text-white"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 text-white fw-bold d-flex align-items-center gap-2">
                                    AI Business Analyst <span class="badge bg-success fs-6 rounded-pill">Real-Time BI</span>
                                </h5>
                                <small class="text-white-50">Petsfolio Booking & CRM Unified Analytics</small>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-light btn-sm rounded-pill" id="btnClearScreen" title="Clear current screen">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Clear
                            </button>
                        </div>
                    </div>

                    <!-- Chat Messages Stream -->
                    <div class="card-body p-3 overflow-auto flex-grow-1 bg-light" id="chatStream" style="background-color: #f8fafc !important;">
                        <!-- Welcome / Suggested Prompts -->
                        <div class="welcome-box text-center py-4 px-3 bg-white rounded-4 shadow-sm mb-3">
                            <div class="mb-3">
                                <i class="bi bi-magic fs-1 text-primary"></i>
                            </div>
                            <h5 class="fw-bold text-dark">Welcome to Petsfolio Executive BI Analyst</h5>
                            <p class="text-muted small max-w-xl mx-auto mb-4">
                                Ask any business question in plain English. 
                            </p>
                            
                            <div class="d-flex flex-wrap justify-content-center gap-2" id="suggestedPrompts">
                                <button class="btn btn-outline-primary btn-sm rounded-pill prompt-chip">
                                    <i class="bi bi-funnel me-1"></i> Lead conversion rate this month vs last?
                                </button>
                                <button class="btn btn-outline-primary btn-sm rounded-pill prompt-chip">
                                    <i class="bi bi-graph-up-arrow me-1"></i> Which lead source is converting best?
                                </button>
                                <button class="btn btn-outline-primary btn-sm rounded-pill prompt-chip">
                                    <i class="bi bi-clock-history me-1"></i> Support ticket resolution time by issue?
                                </button>
                                <button class="btn btn-outline-primary btn-sm rounded-pill prompt-chip">
                                    <i class="bi bi-cart-check me-1"></i> What is our cross-sell acceptance rate?
                                </button>
                                <button class="btn btn-outline-primary btn-sm rounded-pill prompt-chip">
                                    <i class="bi bi-person-badge me-1"></i> Show agent & AI bot performance
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Input Box -->
                    <div class="card-footer bg-white border-top p-3 rounded-bottom-4">
                        <form id="chatForm" class="d-flex gap-2 align-items-center">
                            <input type="hidden" id="activeConversationId" value="">
                            <div class="input-group">
                                <input type="text" class="form-control border-2 rounded-pill px-4 py-2" 
                                       id="userInput" 
                                       placeholder="Ask a business question... (e.g. Compare conversion rate between website and whatsapp)" 
                                       autocomplete="off">
                                <button class="btn btn-primary rounded-pill px-4 ms-2 d-flex align-items-center gap-2" type="submit" id="btnSend">
                                    <span>Send</span> <i class="bi bi-send-fill"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
.conversation-item:hover {
    background-color: #f1f5f9;
}
.conversation-item.active {
    background-color: #e2e8f0;
    border-left: 3px solid #3b82f6;
}
.chat-bubble-user {
    background: #3b82f6;
    color: white;
    border-radius: 18px 18px 2px 18px;
    padding: 12px 16px;
    max-width: 80%;
    margin-left: auto;
    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.15);
}
.chat-bubble-assistant {
    background: white;
    color: #1e293b;
    border-radius: 18px 18px 18px 2px;
    padding: 16px 20px;
    max-width: 85%;
    margin-right: auto;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
}
.typing-indicator span {
    display: inline-block;
    width: 6px;
    height: 6px;
    background-color: #94a3b8;
    border-radius: 50%;
    animation: typing 1.4s infinite ease-in-out both;
}
.typing-indicator span:nth-child(1) { animation-delay: 0s; }
.typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
.typing-indicator span:nth-child(3) { animation-delay: 0.4s; }
@keyframes typing {
    0%, 80%, 100% { transform: scale(0); }
    40% { transform: scale(1); }
}
</style>

<script>
$(document).ready(function () {
    let activeConvId = '';
    const baseAdminUrl = "<?= base_url() ?>";

    // Auto load conversation list on page load
    reloadConversationList();

    // Handle suggested prompt chips
    $(document).on('click', '.prompt-chip', function () {
        const text = $(this).text().trim();
        $('#userInput').val(text);
        $('#chatForm').submit();
    });

    // New Chat button
    $('#btnNewChat, #btnClearScreen').click(function () {
        activeConvId = '';
        $('#activeConversationId').val('');
        $('.conversation-item').removeClass('active');
        resetChatStream();
    });

    // Select existing conversation
    $(document).on('click', '.conversation-item', function (e) {
        if ($(e.target).closest('.btn-delete-conv').length) return;

        const id = $(this).data('id');
        activeConvId = id;
        $('#activeConversationId').val(id);
        $('.conversation-item').removeClass('active');
        $(this).addClass('active');

        loadConversationMessages(id);
    });

    // Delete conversation
    $(document).on('click', '.btn-delete-conv', function (e) {
        e.stopPropagation();
        const id = $(this).data('id');
        if (confirm('Delete this conversation inquiry?')) {
            $.post(baseAdminUrl + 'ai-analyst/delete-conversation/' + id, function () {
                $(`.conversation-item[data-id="${id}"]`).remove();
                if (activeConvId == id) {
                    $('#btnNewChat').click();
                }
            });
        }
    });    // Form submit handler with real-time SSE token streaming
    $('#chatForm').submit(function (e) {
        e.preventDefault();
        const msg = $('#userInput').val().trim();
        if (!msg) return;

        $('#userInput').val('');

        // Append user message bubble
        appendUserMessage(msg);

        // Show typing indicator
        const typingId = appendTypingIndicator();

        // Stream response using Fetch API & ReadableStream
        const streamUrl = baseAdminUrl + 'ai-analyst/stream?conversation_id=' + encodeURIComponent(activeConvId) + '&message=' + encodeURIComponent(msg);
        
        let assistantBubbleId = null;
        let accumulatedText = '';
        let metricsData = [];
        let chartData = null;

        fetch(streamUrl, {
            method: 'GET',
            headers: { 'Accept': 'text/event-stream' }
        }).then(response => {
            const reader = response.body.getReader();
            const decoder = new TextDecoder('utf-8');
            let buffer = '';

            function readChunk() {
                reader.read().then(({ done, value }) => {
                    if (done) {
                        $('#' + typingId).remove();
                        return;
                    }

                    buffer += decoder.decode(value, { stream: true });
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
                                    $('#activeConversationId').val(activeConvId);
                                    reloadConversationList();
                                }
                            } else if (eventType === 'token') {
                                $('#' + typingId).remove();
                                accumulatedText += parsed.text;
                                if (!assistantBubbleId) {
                                    assistantBubbleId = createStreamingAssistantBubble();
                                }
                                updateStreamingAssistantBubble(assistantBubbleId, accumulatedText);
                            } else if (eventType === 'done') {
                                metricsData = parsed.metrics_called || [];
                                chartData = parsed.chart_config || null;
                                if (assistantBubbleId) {
                                    updateStreamingAssistantFooter(assistantBubbleId, metricsData, chartData);
                                }
                            } else if (eventType === 'error') {
                                $('#' + typingId).remove();
                                appendAssistantMessage('Error: ' + (parsed.message || 'Processing error'));
                            }
                        } catch (err) {}
                    });

                    readChunk();
                }).catch(err => {
                    $('#' + typingId).remove();
                    if (!accumulatedText) {
                        appendAssistantMessage('Stream connection error. Please try again.');
                    }
                });
            }

            readChunk();
        }).catch(err => {
            $('#' + typingId).remove();
            appendAssistantMessage('Network error processing request. Please try again.');
        });
    });

    function resetChatStream() {
        $('#chatStream').html(`
            <div class="welcome-box text-center py-4 px-3 bg-white rounded-4 shadow-sm mb-3">
                <div class="mb-3"><i class="bi bi-magic fs-1 text-primary"></i></div>
                <h5 class="fw-bold text-dark">Welcome to Petsfolio Executive BI Analyst</h5>
                <p class="text-muted small max-w-xl mx-auto mb-4">
                    Ask any business question in plain English. 
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <button class="btn btn-outline-primary btn-sm rounded-pill prompt-chip"><i class="bi bi-funnel me-1"></i> Lead conversion rate this month vs last?</button>
                    <button class="btn btn-outline-primary btn-sm rounded-pill prompt-chip"><i class="bi bi-graph-up-arrow me-1"></i> Which lead source is converting best?</button>
                    <button class="btn btn-outline-primary btn-sm rounded-pill prompt-chip"><i class="bi bi-clock-history me-1"></i> Support ticket resolution time by issue?</button>
                    <button class="btn btn-outline-primary btn-sm rounded-pill prompt-chip"><i class="bi bi-cart-check me-1"></i> What is our cross-sell acceptance rate?</button>
                    <button class="btn btn-outline-primary btn-sm rounded-pill prompt-chip"><i class="bi bi-person-badge me-1"></i> Show agent & AI bot performance</button>
                </div>
            </div>
        `);
    }

    function appendUserMessage(text) {
        $('.welcome-box').hide();
        const html = `
            <div class="d-flex mb-3 justify-content-end">
                <div class="chat-bubble-user">
                    <div>${escapeHtml(text)}</div>
                </div>
            </div>
        `;
        $('#chatStream').append(html);
        scrollToBottom();
    }

    function appendAssistantMessage(text, metrics = [], chartConfig = null) {
        $('.welcome-box').hide();
        const msgId = 'chart_' + Date.now();

        let metricsBadgeHtml = '';
        if (metrics && metrics.length > 0) {
            let metricNames = metrics.map(m => `<span class="badge bg-light text-dark border me-1"><i class="bi bi-check-circle-fill text-success me-1"></i>${escapeHtml(m.name)}</span>`).join('');
            metricsBadgeHtml = `
                <div class="mt-2 pt-2 border-top">
                    <small class="text-muted fw-semibold">Data Metrics Executed:</small>
                    <div class="mt-1">${metricNames}</div>
                </div>
            `;
        }

        let chartCanvasHtml = '';
        if (chartConfig) {
            chartCanvasHtml = `
                <div class="mt-3 p-3 bg-white border rounded-3">
                    <canvas id="${msgId}" style="max-height: 260px;"></canvas>
                </div>
            `;
        }

        const html = `
            <div class="d-flex mb-3 justify-content-start">
                <div class="chat-bubble-assistant">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-robot text-primary fs-5"></i>
                        <strong class="text-dark">Petsfolio BI Analyst</strong>
                    </div>
                    <div class="markdown-body">${formatMarkdown(text)}</div>
                    ${chartCanvasHtml}
                    ${metricsBadgeHtml}
                </div>
            </div>
        `;

        $('#chatStream').append(html);
        scrollToBottom();

        if (chartConfig && document.getElementById(msgId)) {
            new Chart(document.getElementById(msgId), chartConfig);
        }
    }

    function createStreamingAssistantBubble() {
        $('.welcome-box').hide();
        const bubbleId = 'msg_' + Date.now();
        const html = `
            <div class="d-flex mb-3 justify-content-start" id="${bubbleId}">
                <div class="chat-bubble-assistant">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-robot text-primary fs-5"></i>
                        <strong class="text-dark">Petsfolio BI Analyst</strong>
                    </div>
                    <div class="markdown-body stream-text"></div>
                    <div class="stream-chart-container"></div>
                    <div class="stream-metrics-container"></div>
                </div>
            </div>
        `;
        $('#chatStream').append(html);
        scrollToBottom();
        return bubbleId;
    }

    function updateStreamingAssistantBubble(bubbleId, text) {
        $(`#${bubbleId} .stream-text`).html(formatMarkdown(text));
        scrollToBottom();
    }

    function updateStreamingAssistantFooter(bubbleId, metrics = [], chartConfig = null) {
        if (metrics && metrics.length > 0) {
            let metricNames = metrics.map(m => `<span class="badge bg-light text-dark border me-1"><i class="bi bi-check-circle-fill text-success me-1"></i>${escapeHtml(m.name)}</span>`).join('');
            $(`#${bubbleId} .stream-metrics-container`).html(`
                <div class="mt-2 pt-2 border-top">
                    <small class="text-muted fw-semibold">Data Metrics Executed:</small>
                    <div class="mt-1">${metricNames}</div>
                </div>
            `);
        }

        if (chartConfig) {
            const canvasId = 'chart_' + Date.now();
            $(`#${bubbleId} .stream-chart-container`).html(`
                <div class="mt-3 p-3 bg-white border rounded-3">
                    <canvas id="${canvasId}" style="max-height: 260px;"></canvas>
                </div>
            `);
            if (document.getElementById(canvasId)) {
                new Chart(document.getElementById(canvasId), chartConfig);
            }
        }
        scrollToBottom();
    }

    function appendTypingIndicator() {
        const id = 'typing_' + Date.now();
        const html = `
            <div class="d-flex mb-3 justify-content-start" id="${id}">
                <div class="chat-bubble-assistant py-2">
                    <div class="typing-indicator d-flex gap-1 align-items-center">
                        <small class="text-muted me-2">Analyst thinking</small>
                        <span></span><span></span><span></span>
                    </div>
                </div>
            </div>
        `;
        $('#chatStream').append(html);
        scrollToBottom();
        return id;
    }

    function loadConversationMessages(id) {
        resetChatStream();
        $('.welcome-box').hide();

        $.get(baseAdminUrl + 'ai-analyst/messages/' + id, function (res) {
            if (res.status && res.messages) {
                res.messages.forEach(msg => {
                    if (msg.role === 'user') {
                        appendUserMessage(msg.content);
                    } else {
                        appendAssistantMessage(msg.content, msg.metrics_called, msg.chart_config);
                    }
                });
            }
        });
    }

    function reloadConversationList() {
        $.get(baseAdminUrl + 'ai-analyst/conversations', function (res) {
            if (res.status && res.conversations) {
                let html = '';
                res.conversations.forEach(c => {
                    const activeClass = (c.id == activeConvId) ? 'active' : '';
                    html += `
                        <div class="conversation-item p-2 rounded-3 mb-1 d-flex justify-content-between align-items-center cursor-pointer ${activeClass}" data-id="${c.id}">
                            <div class="text-truncate me-2" style="max-width: 170px;">
                                <div class="fw-semibold text-dark small text-truncate">${escapeHtml(c.title)}</div>
                                <small class="text-muted" style="font-size: 0.75rem;">Just now</small>
                            </div>
                            <button class="btn btn-link text-danger p-0 border-0 btn-delete-conv" data-id="${c.id}" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    `;
                });
                $('#conversationList').html(html);
            }
        });
    }

    function scrollToBottom() {
        const stream = document.getElementById('chatStream');
        stream.scrollTop = stream.scrollHeight;
    }

    function escapeHtml(text) {
        return $('<div>').text(text).html();
    }

    function formatMarkdown(text) {
        if (!text) return '';
        let formatted = escapeHtml(text);
        
        // Headers
        formatted = formatted.replace(/^### (.*$)/gim, '<h6 class="fw-bold text-dark mt-2 mb-1">$1</h6>');
        formatted = formatted.replace(/^#### (.*$)/gim, '<strong class="text-dark d-block mt-2">$1</strong>');
        
        // Bold
        formatted = formatted.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        
        // Lists
        formatted = formatted.replace(/^\* (.*$)/gim, '• $1');
        formatted = formatted.replace(/^- (.*$)/gim, '• $1');

        // Line breaks
        formatted = formatted.replace(/\n/g, '<br>');
        return formatted;
    }
});
</script>

<?= $this->endSection(); ?>
