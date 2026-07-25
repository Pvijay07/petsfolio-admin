<!-- app/Views/emails/low_sms_alert.php -->
<!DOCTYPE html>
<html>

<head>
  <title>Low SMS Credits Alert</title>
  <style>
    /* Your CSS styles here */
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <div class="alert-icon">⚠️</div>
      <h1>SMS Credits Running Low!</h1>
    </div>

    <div class="info-box">
      <h3>⚠️ Critical Alert</h3>
      <p>Your SMS service credits are running low. Immediate action is required to avoid service disruption.</p>
    </div>

    <h2>Current SMS Balance</h2>

    <div class="info-row">
      <span class="label">Remaining Credits:</span>
      <span class="value"><strong><?= $remaining ?></strong></span>
    </div>

    <div class="info-row">
      <span class="label">Validity Until:</span>
      <span class="value"><?= $validity ?></span>
    </div>

    <div class="info-row">
      <span class="label">Service Route:</span>
      <span class="value"><?= $route ?></span>
    </div>

    <div class="info-row">
      <span class="label">Account Status:</span>
      <span class="value"><?= $status ?></span>
    </div>

    <div style="text-align: center; margin-top: 30px;">
      <a href="https://login.bulksmsgateway.in" class="button">
        🔗 Login to Bulk SMS Gateway
      </a>
    </div>

    <div class="footer">
      <p>This is an automated alert from Petsfolio System.</p>
      <p>Please recharge your SMS account to ensure uninterrupted service.</p>
      <p>Alert Trigger: Credits below 50</p>
    </div>
  </div>
</body>

</html>