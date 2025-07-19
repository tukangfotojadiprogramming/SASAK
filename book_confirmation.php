<?php
session_start();

// Debugging - uncomment to check session data
// echo "<pre>"; print_r($_SESSION); echo "</pre>"; exit;

if (!isset($_SESSION['booking_data'])) {
    // Jika data booking tidak ada, kembalikan ke halaman pesan
    header('Location: pesan.php');
    exit;
}

$booking = $_SESSION['booking_data'];

// Pastikan data items valid
if (is_string($booking['items'])) {
    $items = json_decode($booking['items'], true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Invalid booking data");
    }
} else {
    $items = $booking['items'];
}

$total = $booking['total'];

// Get user data
require_once 'db.php';
$user_id = $_SESSION['user_id'];
$user_query = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$user_query->bind_param('i', $user_id);
$user_query->execute();
$user_result = $user_query->get_result();
$user = $user_result->fetch_assoc();

// Generate booking reference
$booking_ref = 'BQ-' . strtoupper(substr(md5(time() . $user_id), 0, 8));
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <!-- Head content remains the same -->
</head>
<body>
  <!-- Rest of your HTML -->
</body>
</html>