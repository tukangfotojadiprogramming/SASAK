<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'db.php';

// Get user data
$user_id = $_SESSION['user_id'];
$user_query = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$user_query->bind_param('i', $user_id);
$user_query->execute();
$user_result = $user_query->get_result();
$user = $user_result->fetch_assoc();

// Process booking based on type
$booking_type = $_POST['booking_type'] ?? '';
$booking_data = [];

if ($booking_type === 'hotel' || $booking_type === 'both') {
    $hotel_data = [
        'hotel_name' => $_POST['hotel_name'],
        'room_type' => $_POST['room_type'],
        'check_in' => $_POST['check_in'],
        'check_out' => $_POST['check_out'],
        'guests' => $_POST['guests'],
        'booking_date' => date('Y-m-d H:i:s'),
        'user_id' => $user_id,
        'status' => 'pending'
    ];
    
    // Insert hotel booking to database
    $stmt = $conn->prepare("INSERT INTO hotel_bookings (user_id, hotel_name, room_type, check_in, check_out, guests, booking_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssiss", $hotel_data['user_id'], $hotel_data['hotel_name'], $hotel_data['room_type'], $hotel_data['check_in'], $hotel_data['check_out'], $hotel_data['guests'], $hotel_data['booking_date'], $hotel_data['status']);
    $stmt->execute();
    $hotel_booking_id = $stmt->insert_id;
    $stmt->close();
    
    $booking_data['hotel'] = $hotel_data;
    $booking_data['hotel']['booking_id'] = $hotel_booking_id;
}

if ($booking_type === 'bus' || $booking_type === 'both') {
    $bus_data = [
        'bus_route' => $_POST['bus_route'],
        'departure_date' => $_POST['departure_date'],
        'passengers' => $_POST['passengers'],
        'bus_type' => $_POST['bus_type'],
        'booking_date' => date('Y-m-d H:i:s'),
        'user_id' => $user_id,
        'status' => 'pending'
    ];
    
    // Insert bus booking to database
    $stmt = $conn->prepare("INSERT INTO bus_bookings (user_id, bus_route, departure_date, passengers, bus_type, booking_date, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ississs", $bus_data['user_id'], $bus_data['bus_route'], $bus_data['departure_date'], $bus_data['passengers'], $bus_data['bus_type'], $bus_data['booking_date'], $bus_data['status']);
    $stmt->execute();
    $bus_booking_id = $stmt->insert_id;
    $stmt->close();
    
    $booking_data['bus'] = $bus_data;
    $booking_data['bus']['booking_id'] = $bus_booking_id;
}

// Store booking data in session for receipt
$_SESSION['booking_data'] = $booking_data;
$_SESSION['booking_type'] = $booking_type;

// Redirect to receipt page
header('Location: booking_receipt.php');
exit;
?>