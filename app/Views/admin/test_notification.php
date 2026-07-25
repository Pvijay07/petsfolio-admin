<!DOCTYPE html>
<html>

<head>
  <title>Push Notification Test</title>
</head>

<body>
  <h1>Push Notification Test</h1>
  <button id="enableNotifications">Enable Notifications</button>
  <button id="testNotification">Test Notification</button>
  <button id="toggleSound">Toggle Sound</button>

  <script src="<?= base_url('public/assets/js/push-notifications.js?version='.time()) ?>"></script>
  <script>
    document.getElementById('enableNotifications').addEventListener('click', async () => {
      await pushManager.subscribeToPush();
    });

    document.getElementById('testNotification').addEventListener('click', async () => {
      await fetch('https://beta.petsfolio.in/admin/notifications/send', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'title=Test Notification&body=This is a test notification with sound&sound=true'
      });
    });

    document.getElementById('toggleSound').addEventListener('click', () => {
      const enabled = pushManager.toggleSound();
      alert('Sound ' + (enabled ? 'enabled' : 'disabled'));
    });
  </script>
</body>

</html>