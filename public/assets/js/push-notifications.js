class PushNotifications {
    constructor() {
        this.publicVapidKey = 'BDBmdr_xuDThKDoVtiCNjApH_Jf7d-3lwuoV1ntQJB7VaREN6hYZAxPwUAsKAtrEhjurr8J-1D2LFZpgSiyOJUA'; // Replace with your VAPID key
        this.soundEnabled = true;
        this.init();
    }

    async init() {
        if ('serviceWorker' in navigator && 'PushManager' in window) {
            await this.registerServiceWorker();
            await this.requestNotificationPermission();
        }
    }

    async registerServiceWorker() {
        try {
<<<<<<< HEAD
            const registration = await navigator.serviceWorker.register('https://beta.petsfolio.in/admin/sw.js');
=======
            const registration = await navigator.serviceWorker.register('https://admin.petsfolio.in/sw.js');
>>>>>>> origin/main
            console.log('Service Worker registered');
            return registration;
        } catch (error) {
            console.error('Service Worker registration failed:', error);
        }
    }

    async requestNotificationPermission() {
        try {
            const permission = await Notification.requestPermission();
            return permission === 'granted';
        } catch (error) {
            console.error('Error requesting notification permission:', error);
            return false;
        }
    }

    async subscribeToPush() {
        try {
            const registration = await navigator.serviceWorker.ready;
            const subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: this.urlBase64ToUint8Array(this.publicVapidKey)
            });

            // Send subscription to server
            await this.sendSubscriptionToServer(subscription);
            return subscription;
        } catch (error) {
            console.error('Error subscribing to push:', error);
            return null;
        }
    }

    async sendSubscriptionToServer(subscription) {
        try {
<<<<<<< HEAD
            const response = await fetch('https://beta.petsfolio.in/admin/notifications/subscribe', {
=======
            const response = await fetch('https://admin.petsfolio.in/notifications/subscribe', {
>>>>>>> origin/main
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ subscription })
            });

            const result = await response.json();
            if (result.success) {
                console.log('Subscription saved successfully');
            }
        } catch (error) {
            console.error('Error sending subscription to server:', error);
        }
    }

    urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/\-/g, '+')
            .replace(/_/g, '/');

        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }

    // Play sound on client side
<<<<<<< HEAD
    playNotificationSound(soundUrl = 'https://beta.petsfolio.in/admin/sounds/notification.mp3') {
=======
    playNotificationSound(soundUrl = 'https://admin.petsfolio.in/sounds/notification.mp3') {
>>>>>>> origin/main
        if (this.soundEnabled) {
            const audio = new Audio(soundUrl);
            audio.play().catch(error => {
                console.log('Audio play failed:', error);
            });
        }
    }

    toggleSound() {
        this.soundEnabled = !this.soundEnabled;
        return this.soundEnabled;
    }
}

// Initialize push notifications
const pushManager = new PushNotifications();

// Listen for messages from service worker
navigator.serviceWorker.addEventListener('message', event => {
    if (event.data && event.data.type === 'PLAY_SOUND') {
        pushManager.playNotificationSound(event.data.soundUrl);
    }
});