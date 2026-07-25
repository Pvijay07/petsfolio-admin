import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-app.js";
import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-messaging.js";

const firebaseConfig = {
  apiKey: "AIzaSyBG-sX-I3LMqhcvkbeuCRgZ3e6sYAGFc0c",
  authDomain: "petsfolio-2204f.firebaseapp.com",
  projectId: "petsfolio-2204f",
  messagingSenderId: "941943976685",
  appId: "1:941943976685:android:192ab6431be79e222812e4",
};

const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

// ✅ Register service worker
navigator.serviceWorker.register("/firebase-messaging-sw.js")
.then((registration) => {
  console.log("Service Worker registered:", registration);
})
.catch((err) => console.error("Service Worker registration failed:", err));

// ✅ Ask for permission & get FCM token
async function initFCM() {
  try {
    const permission = await Notification.requestPermission();
    if (permission === "granted") {
      const token = await getToken(messaging, { vapidKey: "BFMU3uynFbuUUFa9KltTDNErK4iKyMvY1CXQkSp2ctlUOWYi5l7VQWYN7xHuwpo0CQrb2ZziYh7CWRX-MsVn6qw" });
      console.log("✅ FCM Token:", token);
      // send token to backend here
    } else {
      console.warn("❌ Notification permission denied.");
    }
  } catch (err) {
    console.error("FCM error:", err);
  }
}

initFCM();

// ✅ Handle foreground messages - UPDATED WITH SENDER CHECK
onMessage(messaging, (payload) => {
  console.log("Foreground FCM message:", payload);
  
  // Check if sender is Client or Service Provider (not support)
  const senderType = payload.data?.sender_type;
  const isFromClientOrProvider = senderType === 'client' || senderType === 'provider';
  
  if (isFromClientOrProvider) {
    // Play bark sound
    const barkSound = document.getElementById("barkSound");
    if (barkSound) {
      barkSound.volume = 0.7;
      barkSound.play().catch(() => {});
    }
    
    // Show browser notification
    if (Notification.permission === "granted") {
      new Notification(payload.data.sender_name || "New Message", {
        body: payload.data.message || "You have a new message",
        icon: "/assets/icons/bark-icon.png",
      });
    }
  }
});