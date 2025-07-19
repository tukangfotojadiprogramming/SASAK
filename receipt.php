<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if receipt data exists
if (!isset($_SESSION['receipt'])) {
    header('Location: pesan.php');
    exit;
}

$receipt = $_SESSION['receipt'];
unset($_SESSION['receipt']); // Clear receipt after displaying

// Get user data
require_once 'db.php';
$user_id = $_SESSION['user_id'];
$user_query = $conn->prepare("SELECT username, role FROM users WHERE id = ?");
$user_query->bind_param('i', $user_id);
$user_query->execute();
$user_result = $user_query->get_result();
$user = $user_result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Struk Pemesanan • BALEQARA TRAVEL</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
    body {
      font-family: 'Poppins', sans-serif;
      margin: 40px;
      position: relative;
    }
    
    #map {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      z-index: -10;
      pointer-events: none;
    }
    
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background: url('https://images.fineartamerica.com/images/artworkimages/mediumlarge/3/songket-tenun-geometrik-seamless-pattern-with-creative-bali-lombok-sasak-traditional-village-motif-from-indonesian-batik-julien.jpg') repeat;
      opacity: .06;
      z-index: -1;
    }
    
    .logo {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 24px;
    }
    
    .logo img {
      width: 60px;
      height: 60px;
      border-radius: 50%;
    }
    
    h2 {
      margin: 0;
      font-size: 1.5rem;
      color: #78350f;
    }
    
    .meta {
      color: #555;
      margin-bottom: 20px;
      font-size: .9rem;
    }
    
    table {
      width: 100%;
      border-collapse: collapse;
      border-radius: 8px;
      overflow: hidden;
    }
    
    th, td {
      padding: 8px 10px;
      border-bottom: 1px solid #ddd;
      font-size: .9rem;
    }
    
    th {
      background: #f5f3eb;
      text-align: left;
    }
    
    .ctr {
      text-align: center;
    }
    
    .num {
      text-align: right;
    }
    
    tfoot td {
      font-weight: 600;
      background: #fff8e6;
    }
  </style>
</head>
<body onload="window.print()">
  <div class="logo">
    <img src="assets/sasak.jpg" alt="Logo BALEQARA">
    <h2>Struk Pemesanan<h2>
  </div>

  <p class="meta">
    Tanggal&nbsp;:&nbsp;<?= date('d/m/Y H:i:s') ?>
  </p>
  
  <p class="meta">
    Nama Pemesan: <?= htmlspecialchars($receipt['name']) ?><br>
    Nomor HP: <?= htmlspecialchars($receipt['phone']) ?><br>
    Alamat: <?= nl2br(htmlspecialchars($receipt['address'])) ?><br>
    <?php if ($receipt['departure_date']): ?>
      Tanggal Keberangkatan: <?= htmlspecialchars($receipt['departure_date']) ?><br>
    <?php endif; ?>
    <?php if ($receipt['checkin_date']): ?>
      Check-in: <?= htmlspecialchars($receipt['checkin_date']) ?><br>
      Check-out: <?= htmlspecialchars($receipt['checkout_date']) ?><br>
    <?php endif; ?>
  </p>

  <table>
    <thead>
      <tr>
        <th><?= isset($receipt['items'][0]['category']) && $receipt['items'][0]['category'] === 'hotel' ? 'Hotel' : 'Layanan Bus' ?></th>
        <th class="ctr">Qty</th>
        <th class="num">Harga</th>
        <th class="num">Subtotal</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($receipt['items'] as $item): ?>
        <tr>
          <td><?= htmlspecialchars($item['name']) ?></td>
          <td class="ctr"><?= $item['qty'] ?></td>
          <td class="num">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
          <td class="num">Rp <?= number_format($item['price'] * $item['qty'], 0, ',', '.') ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="3" class="num">Total&nbsp;</td>
        <td class="num">Rp <?= number_format($receipt['total'], 0, ',', '.') ?></td>
      </tr>
    </tfoot>
  </table>

  <p style="margin-top:28px;font-size:.85rem;color:#666;text-align:center">
    Terima kasih telah memesan melalui BALEQARA TRAVEL!<br>
    <?= isset($receipt['items'][0]['category']) && $receipt['items'][0]['category'] === 'hotel' ? 'Silakan tunjukkan struk ini saat check-in' : 'Silakan tunjukkan struk ini saat naik bus' ?>
  </p>

  <script>
    setTimeout(() => {
      window.close();
    }, 3000);
  </script>
</body>
</html>