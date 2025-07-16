<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Anda harus login terlebih dahulu']);
    exit;
}

// Database connection
require_once 'db.php';

// Get the raw POST data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Validate input
if (!$data || !isset($data['id']) || !isset($data['type']) || !isset($data['destination'])) {
    echo json_encode(['success' => false, 'message' => 'Data tidak valid']);
    exit;
}

$user_id = $_SESSION['user_id'];
$item_id = intval($data['id']);
$booking_type = $data['type'] === 'bus' ? 'bus' : 'hotel';
$destination = $conn->real_escape_string($data['destination']);

try {
    // Begin transaction
    $conn->begin_transaction();

    // Insert booking record
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, item_id, booking_type, destination, booking_date) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiss", $user_id, $item_id, $booking_type, $destination);
    $stmt->execute();
    
    // Get the booking ID
    $booking_id = $conn->insert_id;

    // For demonstration, we'll just log the booking
    // In a real application, you would:
    // 1. Check availability
    // 2. Process payment
    // 3. Send confirmation email
    // 4. Update inventory
    
    $conn->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Pemesanan berhasil!',
        'booking_id' => $booking_id
    ]);
    
} catch (Exception $e) {
    $conn->rollback();
    error_log("Booking error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan saat memproses pemesanan'
    ]);
}

$stmt->close();
$conn->close();
?>