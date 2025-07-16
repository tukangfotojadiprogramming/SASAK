<?php
session_start();
header('Content-Type: application/json');

// 1. Validasi Session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Anda harus login terlebih dahulu']);
    exit;
}

// 2. Koneksi Database
require_once 'db.php';

// 3. Ambil Data JSON
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// 4. Validasi Input
$required_fields = ['hotel_id', 'checkin', 'checkout', 'guests', 'destination'];
foreach ($required_fields as $field) {
    if (!isset($data[$field]) || empty($data[$field])) {
        echo json_encode(['success' => false, 'message' => "Data $field tidak lengkap"]);
        exit;
    }
}

// 5. Hitung Total Harga
try {
    // Contoh data hotel (seharusnya dari database)
    $hotels = [
        1 => ['price' => 650000, 'name' => 'Senggigi Beach Resort'],
        2 => ['price' => 420000, 'name' => 'Kuta Mandalika Inn'],
        3 => ['price' => 880000, 'name' => 'Rinjani View Hotel'],
        4 => ['price' => 380000, 'name' => 'Mataram City Hotel']
    ];

    $hotel_id = (int)$data['hotel_id'];
    if (!isset($hotels[$hotel_id])) {
        throw new Exception("Hotel tidak ditemukan");
    }

    $checkin = new DateTime($data['checkin']);
    $checkout = new DateTime($data['checkout']);
    if ($checkout <= $checkin) {
        throw new Exception("Tanggal checkout harus setelah checkin");
    }

    $nights = $checkout->diff($checkin)->days;
    $total_price = $hotels[$hotel_id]['price'] * $nights * (int)$data['guests'];

    // 6. Simpan ke Database
    $stmt = $conn->prepare("INSERT INTO hotel_bookings (
        user_id, 
        hotel_id,
        hotel_name,
        destination,
        checkin_date,
        checkout_date,
        guests,
        total_price,
        status,
        booked_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'confirmed', NOW())");

    $stmt->bind_param(
        "iissssid",
        $_SESSION['user_id'],
        $hotel_id,
        $hotels[$hotel_id]['name'],
        $data['destination'],
        $data['checkin'],
        $data['checkout'],
        $data['guests'],
        $total_price
    );

    $stmt->execute();
    $booking_id = $conn->insert_id;

    // 7. Response Sukses
    echo json_encode([
        'success' => true,
        'message' => 'Booking hotel berhasil!',
        'booking_id' => $booking_id,
        'details' => [
            'hotel' => $hotels[$hotel_id]['name'],
            'destination' => $data['destination'],
            'checkin' => $data['checkin'],
            'checkout' => $data['checkout'],
            'nights' => $nights,
            'guests' => $data['guests'],
            'total_price' => $total_price
        ]
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$stmt->close();
$conn->close();