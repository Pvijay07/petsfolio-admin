

<style>
    /* Chatbot Widget Styles */
    #pf-chatbot-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        font-family: 'Inter', system-ui, sans-serif;
    }

    #pf-chatbot-toggle {
        background: linear-gradient(135deg, #4F46E5, #3B82F6);
        color: white;
        border: none;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #pf-chatbot-toggle:hover {
        transform: scale(1.05);
    }

    #pf-chatbot-window {
        display: none;
        width: 350px;
        height: 500px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        flex-direction: column;
        overflow: hidden;
        position: absolute;
        bottom: 80px;
        right: 0;
        border: 1px solid rgba(255, 255, 255, 0.5);
    }

    .pf-chatbot-header {
        background: linear-gradient(135deg, #4F46E5, #3B82F6);
        color: white;
        padding: 15px 20px;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .pf-chatbot-close {
        background: none;
        border: none;
        color: white;
        font-size: 20px;
        cursor: pointer;
    }

    .pf-chatbot-messages {
        flex: 1;
        padding: 15px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .pf-chatbot-message {
        max-width: 80%;
        padding: 10px 15px;
        border-radius: 15px;
        font-size: 14px;
        line-height: 1.4;
    }

    .pf-chatbot-message.bot {
        background: #F3F4F6;
        color: #1F2937;
        align-self: flex-start;
        border-bottom-left-radius: 5px;
    }

    .pf-chatbot-message.user {
        background: #4F46E5;
        color: white;
        align-self: flex-end;
        border-bottom-right-radius: 5px;
    }

    .pf-chatbot-input-container {
        padding: 15px;
        border-top: 1px solid #E5E7EB;
        display: flex;
        gap: 10px;
    }

    #pf-chatbot-input {
        flex: 1;
        padding: 10px 15px;
        border: 1px solid #E5E7EB;
        border-radius: 20px;
        outline: none;
        transition: border-color 0.3s;
    }

    #pf-chatbot-input:focus {
        border-color: #4F46E5;
    }

    #pf-chatbot-send {
        background: #4F46E5;
        color: white;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s;
    }

    #pf-chatbot-send:hover {
        background: #3B82F6;
    }

    .pf-typing-indicator {
        display: flex;
        gap: 4px;
        padding: 10px 15px;
        background: #F3F4F6;
        border-radius: 15px;
        align-self: flex-start;
        border-bottom-left-radius: 5px;
    }

    .pf-typing-dot {
        width: 6px;
        height: 6px;
        background: #9CA3AF;
        border-radius: 50%;
        animation: pf-typing 1.4s infinite ease-in-out both;
    }

    .pf-typing-dot:nth-child(1) {
        animation-delay: -0.32s;
    }

    .pf-typing-dot:nth-child(2) {
        animation-delay: -0.16s;
    }

    @keyframes pf-typing {

        0%,
        80%,
        100% {
            transform: scale(0);
        }

        40% {
            transform: scale(1);
        }
    }
</style>
<main class="main-content">
    <div    `id="pf-chatbot-container">
        <div id="pf-chatbot-window">
            <div class="pf-chatbot-header">
                <span>Petsfolio Assistant</span>
                <button class="pf-chatbot-close" onclick="toggleChatbot()">×</button>
            </div>
            <div class="pf-chatbot-messages" id="pf-chatbot-messages">
                <div class="pf-chatbot-message bot">Hello! I'm your Petsfolio Insurance Assistant. How can I help you
                    today?</div>
            </div>
            <div class="pf-chatbot-input-container">
                <input type="text" id="pf-chatbot-input" placeholder="Type your message..."
                    onkeypress="handleChatKeyPress(event)">
                <button id="pf-chatbot-send" onclick="sendChatMessage()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </button>
            </div>
        </div>
        <button id="pf-chatbot-toggle" onclick="toggleChatbot()">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
        </button>
    </div>
</main>

<script>
    let chatHistory = [];
    let chatSessionId = localStorage.getItem('pf_chatbot_session_id');
    if (!chatSessionId) {
        chatSessionId = 'chat_' + Math.random().toString(36).substr(2, 9) + '_' + Date.now();
        localStorage.setItem('pf_chatbot_session_id', chatSessionId);
    }

    function toggleChatbot() {
        const window = document.getElementById('pf-chatbot-window');
        window.style.display = window.style.display === 'flex' ? 'none' : 'flex';
    }

    function handleChatKeyPress(event) {
        if (event.key === 'Enter') {
            sendChatMessage();
        }
    }

    function addMessageToUI(text, sender) {
        const messagesDiv = document.getElementById('pf-chatbot-messages');
        const msgDiv = document.createElement('div');
        msgDiv.className = `pf-chatbot-message ${sender}`;
        // Basic Markdown formatting (bold, links)
        let formattedText = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        formattedText = formattedText.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank" style="color: #4F46E5;">$1</a>');
        formattedText = formattedText.replace(/\n/g, '<br>');
        msgDiv.innerHTML = formattedText;

        messagesDiv.appendChild(msgDiv);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    function showTypingIndicator() {
        const messagesDiv = document.getElementById('pf-chatbot-messages');
        const indicator = document.createElement('div');
        indicator.className = 'pf-typing-indicator';
        indicator.id = 'pf-typing-indicator';
        indicator.innerHTML = '<div class="pf-typing-dot"></div><div class="pf-typing-dot"></div><div class="pf-typing-dot"></div>';
        messagesDiv.appendChild(indicator);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    function removeTypingIndicator() {
        const indicator = document.getElementById('pf-typing-indicator');
        if (indicator) indicator.remove();
    }

    async function sendChatMessage() {
        const input = document.getElementById('pf-chatbot-input');
        const message = input.value.trim();

        if (!message) return;

        input.value = '';
        addMessageToUI(message, 'user');
        showTypingIndicator();

        try {
            const response = await fetch('https://beta.petsfolio.in/admin/insurance/chatbot', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    message: message,
                    history: chatHistory,
                    session_id: chatSessionId
                })
            });

            const data = await response.json();
            removeTypingIndicator();

            if (data.status) {
                addMessageToUI(data.reply, 'bot');
                if (data.history) {
                    chatHistory = data.history;
                }
            } else {
                addMessageToUI("Sorry, there was an error processing your request: " + data.message, 'bot');
            }
        } catch (error) {
            removeTypingIndicator();
            addMessageToUI("Sorry, could not connect to the server.", 'bot');
            console.error('Chatbot error:', error);
        }
    }
</script>
