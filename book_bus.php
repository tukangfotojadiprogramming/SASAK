<?php
header('Content-Type: application/json');
require_once '../db.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

// In a real app, you would validate and save to database
$booking_id = rand(1000, 9999); // Simulate booking ID

echo json_encode([
    'success' => true,
    'booking_id' => $booking_id,
    'message' => 'Bus booking successful'
]);
?>