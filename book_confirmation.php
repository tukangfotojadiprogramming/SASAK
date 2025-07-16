<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['booking_success'])) {
    header('Location: index.php');
    exit;
}

require_once 'db.php';

$booking_type = $_GET['type'] ?? '';
$booking_id = $_GET['id'] ?? 0;

// Get booking details based on type
if ($booking_type === 'bus') {
    $stmt = $conn->prepare("SELECT b.*, bus.name as bus_name 
                           FROM bus_bookings b
                           JOIN buses bus ON b.bus_id = bus.id
                           WHERE b.id = ? AND b.user_id = ?");
    $stmt->bind_param('ii', $booking_id, $_SESSION['user_id']);
    $stmt->execute();
    $booking = $stmt->get_result()->fetch_assoc();
} elseif ($booking_type === 'hotel') {
    $stmt = $conn->prepare("SELECT h.*, hotel.name as hotel_name, hotel.stars
                           FROM hotel_bookings h
                           JOIN hotels hotel ON h.hotel_id = hotel.id
                           WHERE h.id = ? AND h.user_id = ?");
    $stmt->bind_param('ii', $booking_id, $_SESSION['user_id']);
    $stmt->execute();
    $booking = $stmt->get_result()->fetch_assoc();
} else {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Booking - BALEQARA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-amber-100 min-h-screen flex flex-col">
    <!-- Navigation -->
    <nav class="bg-amber-900 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="font-bold text-xl">BALEQARA</a>
            <a href="index.php" class="bg-amber-800 px-3 py-1 rounded hover:bg-amber-700">Kembali ke Beranda</a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md overflow-hidden p-6 text-center">
            <div class="text-6xl mb-4">🎉</div>
            <h1 class="text-2xl font-bold text-amber-900 mb-4"><?php echo $_SESSION['booking_success']; ?></h1>
            
            <?php if ($booking_type === 'bus'): ?>
            <div class="text-left mb-6">
                <h2 class="text-xl font-semibold mb-4">Detail Pemesanan Bus</h2>
                <p><strong>Bus:</strong> <?php echo $booking['bus_name']; ?></p>
                <p><strong>Keberangkatan:</strong> <?php echo $booking['departure']; ?> ke <?php echo $booking['destination']; ?></p>
                <p><strong>Tanggal:</strong> <?php echo date('d F Y', strtotime($booking['date'])); ?></p>
                <p><strong>Penumpang:</strong> <?php echo $booking['passengers']; ?> orang</p>
                <p><strong>Nomor Booking:</strong> B-<?php echo str_pad($booking['id'], 6, '0', STR_PAD_LEFT); ?></p>
            </div>
            <?php elseif ($booking_type === 'hotel'): ?>
            <div class="text-left mb-6">
                <h2 class="text-xl font-semibold mb-4">Detail Pemesanan Hotel</h2>
                <p><strong>Hotel:</strong> <?php echo $booking['hotel_name']; ?></p>
                <p><strong>Rating:</strong> <?php echo str_repeat('★', $booking['stars']); ?></p>
                <p><strong>Check-in:</strong> <?php echo date('d F Y', strtotime($booking['checkin'])); ?></p>
                <p><strong>Check-out:</strong> <?php echo date('d F Y', strtotime($booking['checkout'])); ?></p>
                <p><strong>Tamu:</strong> <?php echo $booking['guests']; ?> orang</p>
                <p><strong>Nomor Booking:</strong> H-<?php echo str_pad($booking['id'], 6, '0', STR_PAD_LEFT); ?></p>
            </div>
            <?php endif; ?>
            
            <p class="mb-6">Detail booking telah dikirim ke email Anda di <?php echo $_SESSION['user_email']; ?></p>
            
            <div class="flex justify-center space-x-4">
                <a href="booking.php" class="bg-amber-900 text-white px-6 py-2 rounded hover:bg-amber-800">
                    Buat Booking Baru
                </a>
                <a href="index.php" class="bg-white border border-amber-900 text-amber-900 px-6 py-2 rounded hover:bg-amber-50">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-amber-900 text-white py-4 text-center">
        <p>&copy; 2023 BALEQARA. All rights reserved.</p>
    </footer>
</body>
</html>
<?php
// Clear the booking success message after showing it
unset($_SESSION['booking_success']);
?>