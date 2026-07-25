// Sound manager with user interaction handling
class SoundManager {
  constructor() {
    this.audioContext = null;
    this.soundBuffer = null;
    this.isUserInteracted = false;
    this.init();
  }

  init() {
    // Listen for user interaction to unlock audio
    document.addEventListener(
      "click",
      () => {
        this.isUserInteracted = true;
        this.unlockAudio();
      },
      { once: true },
    );

    document.addEventListener(
      "keydown",
      () => {
        this.isUserInteracted = true;
        this.unlockAudio();
      },
      { once: true },
    );
  }

  unlockAudio() {
    if (!this.audioContext) {
      this.audioContext = new (
        window.AudioContext || window.webkitAudioContext
      )();
    }
    if (this.audioContext.state === "suspended") {
      this.audioContext.resume();
    }
  }

  async playBarkSound() {
    if (!this.isUserInteracted) {
      console.log("🔇 Sound blocked: user needs to interact first");
      return false;
    }

    try {
      const barkSound = document.getElementById("barkSound");
      if (barkSound) {
        barkSound.volume = 0.7;
        await barkSound.play();
        console.log("🔊 Bark sound played successfully");
        return true;
      }
    } catch (error) {
      console.warn("🔇 Sound play failed:", error);

      // Fallback: Try to play using AudioContext
      return await this.playFallbackSound();
    }
    return false;
  }

  async playFallbackSound() {
    if (!this.isUserInteracted) return false;

    try {
      if (!this.audioContext) {
        this.audioContext = new (
          window.AudioContext || window.webkitAudioContext
        )();
      }

      if (!this.soundBuffer) {
        // Create a simple beep sound
        const duration = 0.3;
        const sampleRate = this.audioContext.sampleRate;
        const frameCount = sampleRate * duration;
        const buffer = this.audioContext.createBuffer(
          1,
          frameCount,
          sampleRate,
        );
        const data = buffer.getChannelData(0);

        for (let i = 0; i < frameCount; i++) {
          data[i] =
            Math.sin((2 * Math.PI * 800 * i) / sampleRate) *
            Math.exp(-i / (sampleRate * 0.1));
        }
        this.soundBuffer = buffer;
      }

      const source = this.audioContext.createBufferSource();
      source.buffer = this.soundBuffer;
      source.connect(this.audioContext.destination);
      source.start();

      console.log("🔊 Fallback sound played");
      return true;
    } catch (error) {
      console.warn("🔇 Fallback sound also failed:", error);
      return false;
    }
  }
}

// Initialize sound manager
const soundManager = new SoundManager();

// Your existing Pusher code with fixes
const pusher = new Pusher("3c3318df787d87828310", {
  cluster: "ap2",
});

// Enhanced Push Notifications Manager
class ChatPushNotifications {
  constructor() {
    this.publicVapidKey =
      "BNHqXVP4Jik26W4ySvAnBFETvDjnWB_REtwZw7QkFBri-8dFcCNSOuTQzfed_9w2RFFdT3foOm9EsEn2W2w8plU";
    this.soundEnabled = true;
    this.isSubscribed = false;
    this.windowFocused = true;
    this.debugMode = true;
    this.init();
  }

  async init() {
    this.setupFocusTracking();

    if ("serviceWorker" in navigator && "PushManager" in window) {
      try {
        await this.registerServiceWorker();
        await this.checkSubscription();
        console.log("✅ Push notifications initialized");
      } catch (error) {
        console.error("❌ Push init failed:", error);
      }
    } else {
      console.warn("⚠️ Push notifications not supported");
    }
  }

  setupFocusTracking() {
    this.windowFocused = document.hasFocus();

    window.addEventListener("focus", () => {
      this.windowFocused = true;
      this.log("Window focused");
    });

    window.addEventListener("blur", () => {
      this.windowFocused = false;
      this.log("Window blurred");
    });

    document.addEventListener("visibilitychange", () => {
      this.windowFocused = !document.hidden;
      this.log(`Page ${this.windowFocused ? "visible" : "hidden"}`);
    });
  }

  async registerServiceWorker() {
    try {
      const registration = await navigator.serviceWorker.register(
        "https://petsfolio.in/admin/sw.js?v=" + Date.now(),
      );
      this.log("Service Worker registered");

      // Wait for service worker to be ready
      await navigator.serviceWorker.ready;
      this.log("Service Worker ready");

      return registration;
    } catch (error) {
      this.log(`Service Worker registration failed: ${error.message}`, "error");
      throw error;
    }
  }

  async checkSubscription() {
    try {
      const registration = await navigator.serviceWorker.ready;
      const subscription = await registration.pushManager.getSubscription();
      // console.log(subscription)
      if (subscription) {
        this.isSubscribed = true;
        this.log(
          `Already subscribed: ${subscription.endpoint.substring(0, 50)}...`,
        );
      } else {
        this.log("Not subscribed to push, subscribing now...");
        await this.subscribeToPush();
      }
    } catch (error) {
      this.log(`Subscription check failed: ${error.message}`, "error");
    }
  }

  async subscribeToPush() {
    try {
      const registration = await navigator.serviceWorker.ready;

      // Check permission first
      if (Notification.permission !== "granted") {
        this.log("Notification permission not granted");
        return;
      }

      const subscription = await registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: this.urlBase64ToUint8Array(this.publicVapidKey),
      });

      this.log("Push subscription created");
      await this.sendSubscriptionToServer(subscription);
      this.isSubscribed = true;
    } catch (error) {
      this.log(`Subscription failed: ${error.message}`, "error");

      // Log specific error details
      if (error.name === "NotAllowedError") {
        this.log("User denied notification permission", "error");
      } else if (error.name === "AbortError") {
        this.log("Subscription aborted - check VAPID keys", "error");
      }
    }
  }

  async sendSubscriptionToServer(subscription) {
    try {
      const response = await fetch(
        "https://petsfolio.in/admin/notifications/subscribe",
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            subscription,
            type: "admin_chat",
            user_agent: navigator.userAgent,
          }),
        },
      );

      const result = await response.json();
      if (result.success) {
        this.log("Subscription saved to server");
      } else {
        this.log(`Server rejected: ${result.error}`, "error");
      }
    } catch (error) {
      this.log(`Failed to save subscription: ${error.message}`, "error");
    }
  }

  // Add this method to your ChatPushNotifications class
  // Enhanced sendPushNotification method
  async sendPushNotification(messageData, chatId) {
    console.log("📨 RAW MESSAGE DATA:", messageData);

    // ALWAYS send push notifications regardless of window focus
    const senderName = this.getSafeSenderName(messageData);
    const messageBody = messageData.message || "New message";

    console.log("✅ Prepared notification data:", {
      senderName: senderName,
      messageBody: messageBody,
      chatId: chatId,
      senderType: messageData.sender,
    });

    try {
      const response = await fetch(
        "https://petsfolio.in/admin/notifications/send-chat-notification",
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            chat_id: chatId,
            message: messageBody,
            sender_name: senderName,
            sender_type: messageData.sender || "client",
            message_id: messageData.id || "msg_" + Date.now(),
            timestamp: Date.now(),
            source: "chat_system",

            force_push: true,
          }),
        },
      );

      const result = await response.json();
      console.log("📨 Push send result:", result);

      if (result.success) {
        console.log(
          `✅ Push sent successfully to ${result.sent_count || 0} subscribers`,
        );
      } else {
        console.error("❌ Push failed:", result.error);
      }
    } catch (error) {
      console.error("❌ Push request failed:", error);
    }
  }

  getSafeSenderName(messageData) {
    // Multiple fallback strategies for sender name
    if (
      messageData.sender_name &&
      messageData.sender_name !== "undefined" &&
      messageData.sender_name !== "null"
    ) {
      return messageData.sender_name;
    }

    if (messageData.name && messageData.name !== "undefined") {
      return messageData.name;
    }

    if (messageData.user_name && messageData.user_name !== "undefined") {
      return data.user_name;
    }

    if (messageData.sender === "client") {
      return "Customer";
    }

    if (messageData.sender === "provider") {
      return "Service Provider";
    }

    if (messageData.sender_id) {
      return `User ${String(messageData.sender_id).substring(0, 8)}`;
    }

    // Ultimate fallback
    return "Customer";
  }

  showInAppNotification(messageData) {
    // Create a subtle in-app notification
    const notification = document.createElement("div");
    notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            z-index: 10000;
            font-size: 14px;
            max-width: 300px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        `;
    notification.innerHTML = `
            <strong>New message from ${messageData.sender_name}</strong><br>
            <small>${messageData.message.substring(0, 50)}...</small>
        `;

    document.body.appendChild(notification);

    setTimeout(() => {
      if (document.body.contains(notification)) {
        document.body.removeChild(notification);
      }
    }, 3000);
  }

  log(message, type = "info") {
    if (!this.debugMode) return;

    const timestamp = new Date().toLocaleTimeString();
    const icon = type === "error" ? "❌" : type === "success" ? "✅" : "ℹ️";
    console.log(`[Push${icon}] ${timestamp}: ${message}`);
  }

  urlBase64ToUint8Array(base64String) {
    const padding = "=".repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding)
      .replace(/-/g, "+")
      .replace(/_/g, "/");

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
      outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
  }
}

// Initialize push notifications
const chatPushManager = new ChatPushNotifications();

// Your existing chat code with enhanced notification handling
const chatChannel = pusher.subscribe(`ticket-${currentChatId}`);

// Listen for new messages
// Listen for new messages
chatChannel.bind("new-message", function (data) {
  const isMe = data.sender === "support" && data.sender_id == senderId;
  const senderType = data.sender;

  console.log(
    `📨 New message from ${senderType}:`,
    data.message.substring(0, 50) + "...",
  );

  // Handle client/provider messages
  if (senderType === "client" || senderType === "provider") {
    // Try to play sound
    soundManager.playBarkSound().then((soundPlayed) => {
      if (!soundPlayed) {
        console.log("💡 Click anywhere to enable sounds");
      }
    });

    // ALWAYS try to send push notification for client messages
    // Remove the focus check for testing
    setTimeout(() => {
      chatPushManager.sendPushNotification(data, currentChatId);
    }, 1000);
  }

  // Avoid duplicates
  if (document.querySelector(`[data-msg-id='${data.id}']`)) {
    console.log("🔄 Duplicate message, skipping");
    return;
  }

  addMessageToChat(
    data.message,
    data.sender,
    data.sender_name || (isMe ? "You" : "Customer"),
    data.timestamp,
    data.id,
    data.file_url || null,
  );

  if (!isMe) {
    unreadCount++;
    updateUnreadBadge();
  }
});

// Rest of your existing functions remain the same...
chatChannel.bind("typing", function (data) {
  const typingIndicator = document.getElementById("typingIndicator");
  if (!typingIndicator) return;

  if (data.sender_id != senderId && data.sender_type !== "support") {
    if (data.isTyping) {
      typingIndicator.textContent = `${data.name} is typing...`;
    } else {
      typingIndicator.textContent = "";
    }
  }
});

// ... your existing sendMessage, addMessageToChat functions

// Send message function
function sendMessage() {
  const input = document.getElementById("messageInput");

  const message = input.value.trim();

  if (!message) return;

  const formData = new FormData();
  formData.append("chat_id", currentChatId);
  formData.append("sender_type", "support");
  formData.append("sender_id", senderId);

  if (message) {
    formData.append("message", message);
  }

  const sendBtn =
    document.querySelector('button[type="submit"]') ||
    document.querySelector(".btn-send");
  const originalText = sendBtn ? sendBtn.innerHTML : "";

  if (sendBtn) {
    sendBtn.disabled = true;
    sendBtn.innerHTML = '<i class="ti ti-loader"></i> Sending...';
  }

  fetch(BASE_URL + "/chat/send", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.status === "ok") {
        input.value = "";
        
        // Optimistically add message if Pusher event hasn't already added it
        const realId = data.id || (data.data && data.data.id);
        const idToUse = realId || "tmp_" + Date.now();
        
        if (!document.querySelector(`[data-msg-id="${idToUse}"]`)) {
          addMessageToChat(
            message,
            "support",
            "You",
            getCurrentTime(),
            idToUse,
            null,
            "support"
          );
        }
      } else {
        throw new Error(data.message || "Failed to send message");
      }
    })
    .catch((error) => {
      console.error("Error sending message:", error);
      alert("Failed to send message. Please try again.");
    })
    .finally(() => {
      if (sendBtn) {
        sendBtn.disabled = false;
        sendBtn.innerHTML = originalText;
      }
    });
}

function addMessageToChat(
  message,
  senderType, // 'support' or 'user'
  senderName,
  timestamp,
  id,
  fileUrl = null,
  currentUserType = "support",
) {
  const messagesContainer = document.getElementById("chatBody");
  const msgDiv = document.createElement("div");
  msgDiv.className = "message-container";
  msgDiv.setAttribute("data-msg-id", id);
  console.log(currentUserType);
  console.log(senderType);

  // Check if this message is from the current logged-in user
  const isCurrentUser = senderType === currentUserType;

  let content = "";

  if (fileUrl) {
    content += `<div class="mb-2">`;
    if (/\.(jpg|jpeg|png|gif|webp)$/i.test(fileUrl)) {
      content += `
        <a href="${fileUrl}" target="_blank" class="d-inline-block">
          <img src="${fileUrl}" class="chat-image rounded" alt="attachment" style="max-width:200px; height:auto;">
        </a>`;
    } else {
      content += `
        <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-outline-secondary">
          <i class="ti ti-download me-1"></i> Download File
        </a>`;
    }
    content += `</div>`;
  }

  if (message) {
    content += `<p class="mb-0">${escapeHtml(message)}</p>`;
  }

  msgDiv.innerHTML = `
    <div class="message-bubble ${
      isCurrentUser ? "bg-primary text-white" : "bg-light"
    } rounded-3 p-3 position-relative">
      ${content}
    </div>
    <div class="text-muted small mt-1 ${isCurrentUser ? "text-end" : ""}">
      ${isCurrentUser ? "You" : escapeHtml(senderName)} •
      <br>
      ${timestamp}
    </div>
  `;

  // Align message container
  msgDiv.style.display = "flex";
  msgDiv.style.justifyContent = isCurrentUser ? "flex-end" : "flex-start";
  msgDiv.style.marginBottom = "1rem";

  messagesContainer.appendChild(msgDiv);
  messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

// function updateStatusd(status) {
//   fetch(BASE_URL + "supportticket/updateStatus", {
//     method: "POST",
//     headers: {
//       "Content-Type": "application/x-www-form-urlencoded",
//     },
//     body: `chat_id=${currentChatId}&status=${status}`,
//   });
// }

function handleKeyPress(event) {
  if (event.key === "Enter" && !event.shiftKey) {
    sendMessage();
    event.preventDefault();
  }
}

function getCurrentTime() {
  const now = new Date();
  return now.toLocaleTimeString([], {
    hour: "2-digit",
    minute: "2-digit",
  });
}

let typingTimeout;

function notifyTyping(isTyping) {
  fetch(`${BASE_URL}/chat/typing/${currentChatId}`, {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `name=Support&sender_id=${senderId}&sender_type=support&isTyping=${
      isTyping ? 1 : 0
    }`,
  });
}

// Utility function to escape HTML
function escapeHtml(text) {
  const div = document.createElement("div");
  div.textContent = text;
  return div.innerHTML;
}

// Typing indicator
const messageInput = document.getElementById("messageInput");
if (messageInput) {
  messageInput.addEventListener("input", function () {
    if (this.value.trim()) {
      notifyTyping(true);

      clearTimeout(typingTimeout);
      typingTimeout = setTimeout(() => notifyTyping(false), 2000);
    } else {
      notifyTyping(false);
      clearTimeout(typingTimeout);
    }
  });

  // Stop typing when user stops typing
  messageInput.addEventListener("blur", function () {
    notifyTyping(false);
    clearTimeout(typingTimeout);
  });
}

// Initialize
const filePreview = document.getElementById("filePreview");
if (filePreview) {
  filePreview.style.display = "none";
}

// Auto-scroll to bottom on page load
document.addEventListener("DOMContentLoaded", function () {
  const chatBody = document.getElementById("chatBody");
  if (chatBody) {
    chatBody.scrollTop = chatBody.scrollHeight;
  }
});

// let unreadCount = 0;
// let windowFocused = true;

// Track focus/blur
// window.addEventListener("focus", () => {
//   windowFocused = true;
//   unreadCount = 0;
//   updateUnreadBadge();
// });

window.addEventListener("blur", () => {
  windowFocused = false;
});

function updateUnreadBadge() {
  const badge = document.getElementById("unreadCount");
  if (!badge) return;
  if (unreadCount > 0) {
    badge.style.display = "inline-block";
    badge.textContent = unreadCount;
  } else {
    badge.style.display = "none";
  }
}
