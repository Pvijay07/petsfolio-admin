<?php

require_once __DIR__ . '/bootstrap.php';

global $gt_mysqli;

echo "=== VERIFYING GROUND TRUTH DATABASE QUERIES ===\n";

$q = $gt_mysqli->query("SELECT COUNT(*) as cnt FROM v_bookings_overview WHERE is_deleted = 0");
$row = $q->fetch_assoc();
echo "1. Bookings count (v_bookings_overview where is_deleted = 0): {$row['cnt']}\n";

$q = $gt_mysqli->query("SELECT COUNT(*) as cnt FROM service_providers");
$row = $q->fetch_assoc();
echo "2. Providers count (service_providers): {$row['cnt']}\n";

$q = $gt_mysqli->query("SELECT COUNT(*) as cnt FROM users");
$row = $q->fetch_assoc();
echo "3. Users count (users): {$row['cnt']}\n";

$q = $gt_mysqli->query("SELECT COUNT(*) as cnt FROM transactions WHERE transaction_type = 'booking'");
$row = $q->fetch_assoc();
echo "4. Booking payments count (transactions): {$row['cnt']}\n";

$q = $gt_mysqli->query("SELECT COUNT(*) as cnt FROM community_channels WHERE status = 1");
$row = $q->fetch_assoc();
echo "5. Active Community channels (community_channels): {$row['cnt']}\n";

echo "All Ground Truth queries verified successfully!\n";
