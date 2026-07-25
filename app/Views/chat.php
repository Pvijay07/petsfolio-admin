<?php
$user_id = $_GET['user_id'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>AI Chat</title>
</head>
<body>

<script>
const USER_ID = "<?= $user_id ?>";
console.log(USER_ID);
</script>

<script
id="omnidimension-web-widget"
async
src="https://omnidim.io/web_widget.js?secret_key=cc7096796292801e2941c5e38da370ca">
</script>

</body>
</html>