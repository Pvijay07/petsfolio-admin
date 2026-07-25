// Service Worker for PetsFolio - Fixed notification display with better logging
const CACHE_NAME = "petsfolio-push-v7";

console.log("🔧 Service Worker: Installing...");

self.addEventListener("install", function (event) {
  console.log("🔧 Service Worker: Install event");
  self.skipWaiting();

   event.waitUntil(
    caches.keys().then(function(cacheNames) {
      return Promise.all(
        cacheNames.map(function(cacheName) {
          if (cacheName !== CACHE_NAME) {
            console.log('🔧 Deleting old cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});

self.addEventListener("activate", function (event) {
  console.log("🔧 Service Worker: Activate event - CLAIMING CLIENTS");
  event.waitUntil(
    Promise.all([
      self.clients.claim(), // Take control immediately
      // Clean up old caches
      caches.keys().then(function(cacheNames) {
        return Promise.all(
          cacheNames.map(function(cacheName) {
            if (cacheName !== CACHE_NAME) {
              console.log('🔧 Removing old cache:', cacheName);
              return caches.delete(cacheName);
            }
          })
        );
      })
    ])
  );
});

self.addEventListener("push", function (event) {
  console.log("🔧 Service Worker: Push event received!", event);

  if (!event.data) {
    console.log("🔧 Service Worker: No data in push event");
    return;
  }

  // Log the raw data first
  console.log("🔧 Service Worker: Raw push data:", event.data);
  
  let data = {};
  try {
    data = event.data.json();
    console.log("🔧 Service Worker: Push data parsed:", JSON.stringify(data, null, 2));
  } catch (e) {
    console.log("🔧 Service Worker: Using text data");
    console.error("🔧 Parse error:", e);
    const textData = event.data.text();
    console.log("🔧 Service Worker: Text data:", textData);
    data = {
      title: "PetsFolio",
      body: textData,
    };
  }

  // FIX: Better title fallback with validation
  console.log("🔧 Service Worker: Data title value:", data.title);
  console.log("🔧 Service Worker: Data title type:", typeof data.title);
  console.log("🔧 Service Worker: Data title is undefined?", data.title === undefined);
  console.log("🔧 Service Worker: Data title is null?", data.title === null);
  console.log("🔧 Service Worker: Data title is empty?", data.title === "");

  const title = data.title || "New Message from PetsFolio";
  const body = this.getNotificationBody(data);

  console.log("🔧 Final notification data - Title:", title, "Body:", body);

  const options = {
    body: body,
    icon: data.icon || "/icons/icon-192x192.png",
    badge: data.badge || "/icons/badge-72x72.png",
    tag: data.chat_id ? `chat-${data.chat_id}` : "general",
    requireInteraction: true,
    data: {
      url: data.url || (data.chat_id ? `/admin/support/chat/${data.chat_id}` : "/admin/support"),
      chat_id: data.chat_id,
      message_id: data.message_id,
      type: data.type || "chat_message",
      timestamp: data.timestamp || Date.now(),
      sender_name: data.sender_name,
      message: data.message,
    },
    actions: [
      {
        action: "open-chat",
        title: "💬 Open Chat",
      },
    ],
  };

  console.log("🔧 Service Worker: Showing notification with title:", title);
  console.log("🔧 Service Worker: Full options:", JSON.stringify(options, null, 2));

  event.waitUntil(
    self.registration
      .showNotification(title, options)
      .then(() => {
        console.log("🔧 Service Worker: Notification displayed successfully with title:", title);
      })
      .catch((error) => {
        console.error("🔧 Service Worker: Notification error:", error);
      })
  );
});

// Enhanced notification body helper
self.getNotificationBody = function (data) {
  console.log("🔧 Creating notification body from data:", JSON.stringify(data, null, 2));
  
  // If body is directly provided and meaningful
  if (data.body && data.body !== "in notification" && data.body.length > 0) {
    console.log("🔧 Using direct body:", data.body);
    return data.body;
  }

  // If we have message data, create a proper body
  if (data.message) {
    const senderName = data.sender_name || "Customer";
    const message = data.message.length > 100 ? data.message.substring(0, 100) + "..." : data.message;
    const finalBody = `${senderName}: ${message}`;
    console.log("🔧 Using message-based body:", finalBody);
    return finalBody;
  }

  // Fallback based on sender
  const senderName = data.sender_name || "Someone";
  const fallbackBody = `${senderName} sent you a message`;
  console.log("🔧 Using fallback body:", fallbackBody);
  return fallbackBody;
};

self.addEventListener("notificationclick", function (event) {
  console.log("🔧 Service Worker: Notification clicked:", event.action);
  console.log("🔧 Notification data:", event.notification.data);
  
  event.notification.close();

  const notificationData = event.notification.data;
  let urlToOpen = notificationData.url || "/admin/support";

  if (event.action === "open-chat" && notificationData.chat_id) {
    urlToOpen = `/admin/support/chat/${notificationData.chat_id}`;
  }

  console.log("🔧 Service Worker: Opening URL:", urlToOpen);

  event.waitUntil(
    clients.matchAll({ type: "window" }).then((windowClients) => {
      for (let client of windowClients) {
        if (client.url.includes(urlToOpen) && "focus" in client) {
          console.log("🔧 Service Worker: Focusing existing tab");
          return client.focus();
        }
      }
      
      console.log("🔧 Service Worker: Opening new tab");
      return clients.openWindow(urlToOpen);
    })
  );
});