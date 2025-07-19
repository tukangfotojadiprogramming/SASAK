<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get user data
require_once 'db.php';
$user_id = $_SESSION['user_id'];
$user_query = $conn->prepare("SELECT username, role FROM users WHERE id = ?");
$user_query->bind_param('i', $user_id);
$user_query->execute();
$user_result = $user_query->get_result();
$user = $user_result->fetch_assoc();

// Initialize cart in session if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle add to cart action
if (isset($_POST['add_to_cart'])) {
    $product = [
        'name' => $_POST['product_name'],
        'price' => $_POST['product_price'],
        'category' => $_POST['product_category'],
        'destin' => $_POST['product_destin'],
        'qty' => 1
    ];
    
    // Check if product already in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['name'] === $product['name']) {
            $item['qty']++;
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        $_SESSION['cart'][] = $product;
    }
    
    header('Location: pesan.php');
    exit;
}

// Handle checkout
if (isset($_POST['checkout'])) {
    // Process checkout data
    $checkout_data = [
        'name' => $_POST['buyerName'],
        'phone' => $_POST['buyerPhone'],
        'address' => $_POST['buyerAddr'],
        'departure_date' => $_POST['departureDate'] ?? null,
        'checkin_date' => $_POST['checkinDate'] ?? null,
        'checkout_date' => $_POST['checkoutDate'] ?? null,
        'items' => $_SESSION['cart'],
        'total' => array_reduce($_SESSION['cart'], function($sum, $item) {
            return $sum + ($item['price'] * $item['qty']);
        }, 0)
    ];
    
    // Store receipt data in session
    $_SESSION['receipt'] = $checkout_data;
    
    // Clear cart
    $_SESSION['cart'] = [];
    
    // Redirect to receipt page
    header('Location: receipt.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Booking Travel & Hotel – BALEQARA TRAVEL</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
  body { font-family: 'Poppins', sans-serif; }
  .product-card:hover { box-shadow: 0 6px 16px rgba(0,0,0,.08); transform: translateY(-2px); }
  .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
  #map { height: 380px; }
  
  /* Modal improvements */
  #checkoutModal {
    align-items: flex-start;
    padding-top: 20px;
    padding-bottom: 20px;
    overflow-y: auto;
  }
  
  #checkoutModal > div {
    max-height: 90vh;
    overflow-y: auto;
    width: 95%;
    max-width: 500px;
    margin-top: 20px;
    margin-bottom: 20px;
  }
  
  #cartList {
    max-height: 150px;
    overflow-y: auto;
  }
  
  .form-input {
    width: 100%;
    border: 1px solid #d1d5db;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 14px;
  }
  
  .radio-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
  }
  
  .gradient-header {
    background: linear-gradient(135deg, #78350f, #f59e0b);
  }
</style>
</head>
<body class="bg-amber-100 text-gray-800">

<!-- ===== NAVBAR ===== -->
<nav class="gradient-header text-white p-4 flex flex-wrap gap-4 items-center sticky top-0 z-10">
  <span class="font-semibold text-lg tracking-wide">BALEQARA TRAVEL</span>

  <!-- Search -->
  <input id="search" type="text" placeholder="Cari Bus/Hotel..." class="flex-1 min-w-[180px] p-2 rounded text-gray-800" />

  <!-- Dropdown Destinasi -->
  <select id="destSelect" class="p-2 rounded text-gray-800">
    <option value="all">Lokasi Destinasi Anda</option>
    <option>Pantai Seger</option>
    <option>Desa Adat Sade</option>
    <option>Gunung Rinjani</option>
    <option>Pantai Kuta Mandalika</option>
    <option>Bukit Merese</option>
    <option>Air Terjun Tiu Kelep</option>
    <option>Pantai Pink</option>
    <option>Desa Ende</option>
    <option>Masjid Kuno Bayan Beleq</option>
    <option>Makam Keramat</option>
    <option>Wisata Sesaot</option>
    <option>Pura Batu Bolong</option>
    <option>Pasar Seni Sukarara</option>
    <option>Dusun Beleq, Sembalun</option>
    <option>Desa Tete Batu</option>
    <option>Pura Lingsar</option>
    <option>Benteng Selong</option>
    <option>Desa Lingsar</option>
    <option>Desa Bilebante</option>
    <option>Kampung Tenun Pringgasela</option>
    <option>Gili Nanggu &amp; Gili Sudak</option>
  </select>

  <!-- Cart -->
  <button id="cartBtn" class="relative">
    🛒
    <span id="cartCount" class="absolute -top-2 -right-3 bg-red-600 text-xs rounded-full px-1 <?= empty($_SESSION['cart']) ? 'hidden' : '' ?>">
      <?= array_reduce($_SESSION['cart'], function($sum, $item) { return $sum + $item['qty']; }, 0) ?>
    </span>
  </button>
  
  <!-- Back button -->
  <a href="index.php" class="bg-white text-amber-900 px-3 py-1 rounded ml-auto">Kembali</a>
</nav>

<!-- ===== TEKS PROMOSI ===== -->
<section class="text-center py-6 px-4 bg-amber-100">
  <h1 class="text-2xl font-bold text-amber-900 mb-2">Yuk Jelajahi Lombok dengan Mudah & Nyaman!</h1>
  <p class="text-gray-700">Temukan transportasi dan akomodasi terbaik untuk perjalanan Anda di Lombok. 
  Pesan bus travel antar destinasi atau hotel untuk menginap dengan harga terbaik.</p>
</section>

<!-- ===== KATEGORI ===== -->
<section class="max-w-7xl mx-auto px-4 py-4">
  <div id="catBar" class="flex flex-wrap gap-3">
    <button data-filter="all" class="filter-btn px-3 py-1 rounded-full bg-amber-900 text-white">Semua</button>
    <button data-filter="bus" class="filter-btn px-3 py-1 rounded-full bg-white text-gray-800">BUS TRAVEL</button>
    <button data-filter="hotel" class="filter-btn px-3 py-1 rounded-full bg-white text-gray-800">HOTEL</button>
  </div>
</section>

<!-- ===== GRID PRODUK ===== -->
<main id="grid" class="max-w-7xl mx-auto grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 px-4 pb-8">

<!-- ---------- BUS TRAVEL ---------- -->  
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Travel Bus Kuta – Sade – Bandara" data-price="75000" data-cat="bus"
  data-destin="Pantai Kuta Mandalika"
  data-lat="-8.8882" data-lng="116.2779" data-gmaps="Pantai Kuta Mandalika, Lombok">
  <div class="relative">
    <img src="https://cdn-2.tstatic.net/lombok/foto/bank/images/ilustrasi-bus-wsbk-mandalika-2022.jpg"
         alt="Bus Travel Lombok" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Lintas Lombok Travel (Kuta – Sade)</h3>
    <p class="text-amber-900 font-bold">Rp 75.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Travel Bus Kuta – Sade – Bandara">
        <input type="hidden" name="product_price" value="75000">
        <input type="hidden" name="product_category" value="bus">
        <input type="hidden" name="product_destin" value="Pantai Kuta Mandalika">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Rinjani Ekspress" data-price="65000" data-cat="bus"
  data-destin="Pantai Senggigi"
  data-lat="-8.5058" data-lng="116.0456" data-gmaps="Pantai Senggigi, Lombok Barat">
  <div class="relative">
    <img src="https://www.sgmytaxi.com/wp-content/uploads/Cepat-Ekspress-Bus.jpg"
         alt="Rinjani Ekspress" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Rinjani Ekspress (Mandalika – Mataram)</h3>
    <p class="text-amber-900 font-bold">Rp 65.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Rinjani Ekspress">
        <input type="hidden" name="product_price" value="65000">
        <input type="hidden" name="product_category" value="bus">
        <input type="hidden" name="product_destin" value="Pantai Senggigi">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>



<!-- ---------- bus 3 ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="SasakTrans" data-price="95000" data-cat="bus"
  data-destin="Tetebatu, Lombok Timur"
  data-lat="-8.5657" data-lng="116.4333" data-gmaps="Tetebatu, Lombok Timur">
  <div class="relative">
    <img src="https://omnispace.blob.core.windows.net/strapi-prod/2022-09-19/TRAC_Big_Bus_Seat_59_7a8385c32b.png"
         alt="SasakTrans" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">SasakTrans (Tetebatu – Kotaraja – Sembalun)</h3>
    <p class="text-amber-900 font-bold">Rp 95.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="SasakTrans">
        <input type="hidden" name="product_price" value="95000">
        <input type="hidden" name="product_category" value="bus">
        <input type="hidden" name="product_destin" value="Tetebatu, Lombok Timur">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

<!-- ---------- bus 4 ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Wisata Selatan Line" data-price="75000" data-cat="bus"
  data-destin="Bukit Merese"
  data-lat="-8.9016" data-lng="116.3269" data-gmaps="Bukit Merese, Lombok Tengah">
  <div class="relative">
    <img src="https://www.busmustikaholiday.com/assets/images/bus/bigbus_jetbus_3___legrest_f3b.jpg"
         alt="Wisata Selatan Line" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Wisata Selatan Line (Merese – Seger – Kuta)</h3>
    <p class="text-amber-900 font-bold">Rp 75.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Wisata Selatan Line">
        <input type="hidden" name="product_price" value="75000">
        <input type="hidden" name="product_category" value="bus">
        <input type="hidden" name="product_destin" value="Bukit Merese">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

<!-- ---------- bus 5 ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Sesaot Forest Ride" data-price="55000" data-cat="bus"
  data-destin="Wisata Sesaot"
  data-lat="-8.5328" data-lng="116.1784" data-gmaps="Wisata Sesaot, Lombok Barat">
  <div class="relative">
    <img src="https://www.buspariwisata.id/wp-content/uploads/2018/11/bus-pariwisata.id-foto-bus-pariwisata-big-bird-b.jpg"
         alt="Sesaot Forest Ride" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Sesaot Forest Ride (Mataram – Sesaot – Suranadi)</h3>
    <p class="text-amber-900 font-bold">Rp 55.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Sesaot Forest Ride">
        <input type="hidden" name="product_price" value="55000">
        <input type="hidden" name="product_category" value="bus">
        <input type="hidden" name="product_destin" value="Wisata Sesaot">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

<!-- ---------- bus 6 ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Batu Bolong Express" data-price="60000" data-cat="bus"
  data-destin="Pura Batu Bolong"
  data-lat="-8.5370" data-lng="116.0733" data-gmaps="Pura Batu Bolong, Senggigi">
  <div class="relative">
    <img src="https://www.saturental.com/media/uploads/2019/02/saturental-foto-bus-pariwisata-pandawa-87-shd-hdd-48-59-seats-b.jpg"
         alt="Batu Bolong Express" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Batu Bolong Express (Senggigi – Batu Bolong – Mataram)</h3>
    <p class="text-amber-900 font-bold">Rp 60.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Batu Bolong Express">
        <input type="hidden" name="product_price" value="60000">
        <input type="hidden" name="product_category" value="bus">
        <input type="hidden" name="product_destin" value="Pura Batu Bolong">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

<!-- ---------- bus 7 ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Kampung Tenun Liner" data-price="70000" data-cat="bus"
  data-destin="Kampung Tenun Pringgasela"
  data-lat="-8.5246" data-lng="116.5269" data-gmaps="Pringgasela, Lombok Timur">
  <div class="relative">
    <img src="https://ganatransbali.com/wp-content/uploads/2022/12/bus-gana-35-37-seats-1.jpg"
         alt="Kampung Tenun Liner" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Kampung Tenun Liner (Pringgasela – Sukarara – Bilebante)</h3>
    <p class="text-amber-900 font-bold">Rp 70.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Kampung Tenun Liner">
        <input type="hidden" name="product_price" value="70000">
        <input type="hidden" name="product_category" value="bus">
        <input type="hidden" name="product_destin" value="Kampung Tenun Pringgasela">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

<!-- ---------- bus 8 ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Gili Sudak Shuttle" data-price="85000" data-cat="bus"
  data-destin="Gili Nanggu & Gili Sudak"
  data-lat="-8.7485" data-lng="116.0025" data-gmaps="Pelabuhan Tawun, Gili Sudak">
  <div class="relative">
    <img src="https://www.buspariwisata.id/wp-content/uploads/2018/11/bus-pariwisata.id-foto-bus-pariwisata-permata-trans-c.jpg"
         alt="Gili Sudak Shuttle" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Gili Sudak Shuttle (Lembar – Gili Nanggu – Sudak)</h3>
    <p class="text-amber-900 font-bold">Rp 85.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Gili Sudak Shuttle">
        <input type="hidden" name="product_price" value="85000">
        <input type="hidden" name="product_category" value="bus">
        <input type="hidden" name="product_destin" value="Gili Nanggu & Gili Sudak">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

<!-- ---------- bus 9 ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Keramat Pilgrimage Bus" data-price="90000" data-cat="bus"
  data-destin="Makam Keramat"
  data-lat="-8.2963" data-lng="116.4158" data-gmaps="Makam Keramat Bayan, Lombok Utara">
  <div class="relative">
    <img src="https://www.busbeetrans.co.id/assets/images/bus/add/medium_bus_legrest_919.jpg"
         alt="Keramat Pilgrimage Bus" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Keramat Pilgrimage Bus (Mataram – Makam Keramat)</h3>
    <p class="text-amber-900 font-bold">Rp 90.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Keramat Pilgrimage Bus">
        <input type="hidden" name="product_price" value="90000">
        <input type="hidden" name="product_category" value="bus">
        <input type="hidden" name="product_destin" value="Makam Keramat">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

<!-- ---------- bus 10 ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Gili Express Shuttle" data-price="85000" data-cat="bus"
  data-destin="Pura Batu Bolong"
  data-lat="-8.5338" data-lng="116.0624" data-gmaps="Pura Batu Bolong, Senggigi, Lombok">
  <div class="relative">
    <img src="https://www.saturental.com/media/uploads/2019/02/saturental-foto-bus-pariwisata-pandawa-87-shd-hdd-48-59-seats-c.jpg.webp"
         alt="Gili Express Shuttle" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Gili Express Shuttle (Mataram – Batu Bolong – Gili Sudak)</h3>
    <p class="text-amber-900 font-bold">Rp 85.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Gili Express Shuttle">
        <input type="hidden" name="product_price" value="85000">
        <input type="hidden" name="product_category" value="bus">
        <input type="hidden" name="product_destin" value="Pura Batu Bolong">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

<!-- ---------- bus 11 ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Pringgasela Line" data-price="80000" data-cat="bus"
  data-destin="Kampung Tenun Pringgasela"
  data-lat="-8.4772" data-lng="116.5416" data-gmaps="Kampung Tenun Pringgasela, Lombok Timur">
  <div class="relative">
    <img src="https://www.buspariwisata.id/wp-content/uploads/2018/11/bus-pariwisata.id-foto-bus-pariwisata-kupu-kupu-ayu-d.jpg"
         alt="Pringgasela Line" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Pringgasela Line (Sukarara – Pringgasela)</h3>
    <p class="text-amber-900 font-bold">Rp 80.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Pringgasela Line">
        <input type="hidden" name="product_price" value="80000">
        <input type="hidden" name="product_category" value="bus">
        <input type="hidden" name="product_destin" value="Kampung Tenun Pringgasela">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

<!-- ---------- bus 12 ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Lombok Green Bus" data-price="75000" data-cat="bus"
  data-destin="Wisata Sesaot"
  data-lat="-8.5576" data-lng="116.2284" data-gmaps="Wisata Sesaot, Narmada, Lombok Barat">
  <div class="relative">
    <img src="https://www.buspariwisata.id/wp-content/uploads/2018/11/bus-pariwisata.id-foto-bus-pariwisata-esem-abadi-c-480x270.jpg"
         alt="Lombok Green Bus" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Lombok Green Bus (Bilebante – Sesaot)</h3>
    <p class="text-amber-900 font-bold">Rp 75.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Lombok Green Bus">
        <input type="hidden" name="product_price" value="75000">
        <input type="hidden" name="product_category" value="bus">
        <input type="hidden" name="product_destin" value="Wisata Sesaot">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

<!-- ---------- bus 13 ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Pink Beach Route" data-price="100000" data-cat="bus"
  data-destin="Pantai Pink"
  data-lat="-8.7508" data-lng="116.5217" data-gmaps="Pantai Pink, Jerowaru, Lombok Timur">
  <div class="relative">
    <img src="https://www.buspariwisata.id/wp-content/uploads/2018/11/bus-pariwisata.id-foto-bus-pariwisata-sumber-anugrah-c-480x270.jpg"
         alt="Pink Beach Route" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Pink Beach Route (Bandara – Pantai Pink – Tete Batu)</h3>
    <p class="text-amber-900 font-bold">Rp 100.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Pink Beach Route">
        <input type="hidden" name="product_price" value="100000">
        <input type="hidden" name="product_category" value="bus">
        <input type="hidden" name="product_destin" value="Pantai Pink">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

<!-- ---------- bus 14 ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Lombok Timur Heritage Bus" data-price="85000" data-cat="bus"
  data-destin="Benteng Selong"
  data-lat="-8.6439" data-lng="116.5309" data-gmaps="Benteng Selong, Lombok Timur">
  <div class="relative">
    <img src="https://www.saturental.com/media/uploads/2018/09/saturental-foto-big-bus-pariwisata-bin-ilyas-shd-hdd-terbaru-59-seats-a.jpg.webp"
         alt="Lombok Timur Heritage Bus" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Lombok Timur Heritage Bus (Selong – Makam Keramat – Ende)</h3>
    <p class="text-amber-900 font-bold">Rp 85.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Lombok Timur Heritage Bus">
        <input type="hidden" name="product_price" value="85000">
        <input type="hidden" name="product_category" value="bus">
        <input type="hidden" name="product_destin" value="Benteng Selong">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

<!-- ---------- bus 15 ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Ende Village Route" data-price="65000" data-cat="bus"
  data-destin="Desa Ende"
  data-lat="-8.7523" data-lng="116.3342" data-gmaps="Desa Ende, Lombok Tengah">
  <div class="relative">
    <img src="https://www.buspariwisata.id/wp-content/uploads/2018/11/bus-pariwisata.id-foto-bus-pariwisata-big-bird-e-480x270.jpg"
         alt="Ende Village Route" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Ende Village Route (Mataram – Desa Ende)</h3>
    <p class="text-amber-900 font-bold">Rp 65.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Ende Village Route">
        <input type="hidden" name="product_price" value="65000">
        <input type="hidden" name="product_category" value="bus">
        <input type="hidden" name="product_destin" value="Desa Ende">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

<!-- ---------- HOTEL ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Novotel Lombok Resort & Villas" data-price="850000" data-cat="hotel"
  data-destin="Pantai Kuta Mandalika"
  data-lat="-8.9027" data-lng="116.2733" data-gmaps="Novotel Lombok Resort & Villas">
  <div class="relative">
    <img src="https://www.ahstatic.com/photos/0571_rov2a_00_p_1024x768.jpg"
         alt="Novotel Lombok" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Novotel Lombok Resort & Villas</h3>
    <p class="text-amber-900 font-bold">Rp 850.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Novotel Lombok Resort & Villas">
        <input type="hidden" name="product_price" value="850000">
        <input type="hidden" name="product_category" value="hotel">
        <input type="hidden" name="product_destin" value="Pantai Kuta Mandalika">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Pullman Lombok Mandalika Beach Resort" data-price="1200000" data-cat="hotel"
  data-destin="Pantai Seger"
  data-lat="-8.9051" data-lng="116.2907" data-gmaps="Pullman Lombok Mandalika Beach Resort">
  <div class="relative">
    <img src="https://www.luxuriousmagazine.com/wp-content/uploads/2022/10/Pullman-Lombok-Mandalika-Beach-Resort-exterior-1536x957.jpg"
         alt="Pullman Lombok" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Pullman Lombok Mandalika</h3>
    <p class="text-amber-900 font-bold">Rp 1.200.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Pullman Lombok Mandalika Beach Resort">
        <input type="hidden" name="product_price" value="1200000">
        <input type="hidden" name="product_category" value="hotel">
        <input type="hidden" name="product_destin" value="Pantai Seger">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>





<!-- ---------- hotel 3 ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Sade Guesthouse" data-price="250000" data-cat="hotel"
  data-destin="Desa Adat Sade"
  data-lat="-8.8389" data-lng="116.2952" data-gmaps="Sade Guesthouse Lombok">
  <div class="relative">
    <img src="https://authentic-indonesia.com/wp-content/uploads/2020/08/the-sade-house-floors-are-covered-with-buffalo-dung.jpg"
         alt="Sade Guesthouse" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Sade Guesthouse</h3>
    <p class="text-amber-900 font-bold">Rp 250.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Sade Guesthouse">
        <input type="hidden" name="product_price" value="250000">
        <input type="hidden" name="product_category" value="hotel">
        <input type="hidden" name="product_destin" value="Desa Adat Sade">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

<!-- ---------- hotel 4 ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Rinjani Lodge" data-price="900000" data-cat="hotel"
  data-destin="Gunung Rinjani"
  data-lat="-8.3737" data-lng="116.4221" data-gmaps="Rinjani Lodge, Senaru">
  <div class="relative">
    <img src="https://www.rinjanilodge.com/wp-content/uploads/Rinjani-Mount-Lodge-.jpg"
         alt="Rinjani Lodge" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Rinjani Lodge</h3>
    <p class="text-amber-900 font-bold">Rp 900.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Rinjani Lodge">
        <input type="hidden" name="product_price" value="900000">
        <input type="hidden" name="product_category" value="hotel">
        <input type="hidden" name="product_destin" value="Gunung Rinjani">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

<!-- ---------- hotel 5 ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Merese Hill Villas" data-price="700000" data-cat="hotel"
  data-destin="Bukit Merese"
  data-lat="-8.8954" data-lng="116.2769" data-gmaps="Merese Hill Villas">
  <div class="relative">
    <img src="https://tse2.mm.bing.net/th/id/OIP.FwURwTxaK3pvGXG5yiR8zQHaE7?pid=Api&P=0&h=180"
         alt="Merese Hill Villas" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Merese Hill Villas</h3>
    <p class="text-amber-900 font-bold">Rp 700.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Merese Hill Villas">
        <input type="hidden" name="product_price" value="700000">
        <input type="hidden" name="product_category" value="hotel">
        <input type="hidden" name="product_destin" value="Bukit Merese">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

<!-- ---------- hotel 6 ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Tiu Kelep Guesthouse" data-price="150000" data-cat="hotel"
  data-destin="Air Terjun Tiu Kelep"
  data-lat="-8.3363" data-lng="116.4039" data-gmaps="Tiu Kelep Guesthouse, Senaru">
  <div class="relative">
    <img src="https://tse1.mm.bing.net/th/id/OIP.jQTxg1VrC1vvF6vOibC--QHaFj?pid=Api&P=0&h=180"
         alt="Tiu Kelep Guesthouse" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Tiu Kelep Guesthouse</h3>
    <p class="text-amber-900 font-bold">Rp 150.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Tiu Kelep Guesthouse">
        <input type="hidden" name="product_price" value="150000">
        <input type="hidden" name="product_category" value="hotel">
        <input type="hidden" name="product_destin" value="Air Terjun Tiu Kelep">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>


<!-- ---------- hotel 7 ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="PinkCoco Gili Trawangan (Dekat Pantai Pink)" data-price="950000" data-cat="hotel"
  data-destin="Pantai Pink"
  data-lat="-8.8087" data-lng="116.5993" data-gmaps="PinkCoco, Lombok">
  <div class="relative">
    <img src="https://bali.com/wp-content/uploads/2022/09/pinkcoco-gili-trawangan.jpg"
         alt="PinkCoco Hotel" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">PinkCoco Gili</h3>
    <p class="text-amber-900 font-bold">Rp 950.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="PinkCoco Gili Trawangan (Dekat Pantai Pink)">
        <input type="hidden" name="product_price" value="950000">
        <input type="hidden" name="product_category" value="hotel">
        <input type="hidden" name="product_destin" value="Pantai Pink">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

<!-- ---------- hotel 8 ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Ende Sasak Homestay" data-price="180000" data-cat="hotel"
  data-destin="Desa Ende"
  data-lat="-8.8497" data-lng="116.2725" data-gmaps="Ende Sasak Homestay">
  <div class="relative">
    <img src="https://dynamic-media-cdn.tripadvisor.com/media/photo-o/07/4f/e7/ec/rooms-overlooking-the.jpg?w=1200&h=-1&s=1"
         alt="Ende Homestay" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Ende Sasak Homestay</h3>
    <p class="text-amber-900 font-bold">Rp 180.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Ende Sasak Homestay">
        <input type="hidden" name="product_price" value="180000">
        <input type="hidden" name="product_category" value="hotel">
        <input type="hidden" name="product_destin" value="Desa Ende">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

<!-- ---------- hotel 9 ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Bayan Heritage Hotel" data-price="320000" data-cat="hotel"
  data-destin="Masjid Kuno Bayan Beleq"
  data-lat="-8.3036" data-lng="116.3564" data-gmaps="Bayan Heritage Hotel">
  <div class="relative">
    <img src="https://tse4.mm.bing.net/th/id/OIP.O0su0pbZN7hPtvVp2g6D2QHaFj?pid=Api&P=0&h=180"
         alt="Bayan Heritage Hotel" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Bayan Heritage Hotel</h3>
    <p class="text-amber-900 font-bold">Rp 320.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Bayan Heritage Hotel">
        <input type="hidden" name="product_price" value="320000">
        <input type="hidden" name="product_category" value="hotel">
        <input type="hidden" name="product_destin" value="Masjid Kuno Bayan Beleq">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

<!-- ---------- hotel 10 ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Keramat Resort" data-price="400000" data-cat="hotel"
  data-destin="Makam Keramat"
  data-lat="-8.7105" data-lng="116.5082" data-gmaps="Keramat Resort Lombok">
  <div class="relative">
    <img src="https://firstlomboktour.com/wp-content/uploads/2023/02/Homestay-di-Lombok.jpg"
         alt="Keramat Resort" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Keramat Resort</h3>
    <p class="text-amber-900 font-bold">Rp 400.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Keramat Resort">
        <input type="hidden" name="product_price" value="400000">
        <input type="hidden" name="product_category" value="hotel">
        <input type="hidden" name="product_destin" value="Makam Keramat">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

<!-- ---------- hotel 11 ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="The Jayakarta Lombok" data-price="600000" data-cat="hotel"
  data-destin="Pura Batu Bolong"
  data-lat="-8.5274" data-lng="116.0622" data-gmaps="The Jayakarta Lombok Beach Resort">
  <div class="relative">
    <img src="https://tse4.mm.bing.net/th/id/OIP.9_ctah0RReCSk_7d9sQIuQHaEs?pid=Api&P=0&h=180"
         alt="Jayakarta Hotel" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">The Jayakarta Lombok</h3>
    <p class="text-amber-900 font-bold">Rp 600.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="The Jayakarta Lombok">
        <input type="hidden" name="product_price" value="600000">
        <input type="hidden" name="product_category" value="hotel">
        <input type="hidden" name="product_destin" value="Pura Batu Bolong">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

<!-- ---------- hotel 12 ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Sesaot Forest Homestay" data-price="200000" data-cat="hotel"
  data-destin="Hutan Wisata Sesaot"
  data-lat="-8.5821" data-lng="116.2284" data-gmaps="Sesaot Forest Homestay, Lombok">
  <div class="relative">
    <img src="https://tse3.mm.bing.net/th/id/OIP.OS1AtWcRRp6rn_LbCoB8hQHaE8?pid=Api&P=0&h=180"
         alt="Sesaot Homestay" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Sesaot Forest Homestay</h3>
    <p class="text-amber-900 font-bold">Rp 200.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Sesaot Forest Homestay">
        <input type="hidden" name="product_price" value="200000">
        <input type="hidden" name="product_category" value="hotel">
        <input type="hidden" name="product_destin" value="Hutan Wisata Sesaot">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

<!-- ---------- hotel 13 ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Tenun Inn Sukarara" data-price="250000" data-cat="hotel"
  data-destin="Sentra Tenun Sukarara"
  data-lat="-8.6908" data-lng="116.2704" data-gmaps="Tenun Inn Sukarara">
  <div class="relative">
    <img src="https://tse2.mm.bing.net/th/id/OIP.NjszTCViaEp6ENv716-DcwHaFk?pid=Api&P=0&h=180"
         alt="Tenun Inn Sukarara" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Tenun Inn Sukarara</h3>
    <p class="text-amber-900 font-bold">Rp 250.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Tenun Inn Sukarara">
        <input type="hidden" name="product_price" value="250000">
        <input type="hidden" name="product_category" value="hotel">
        <input type="hidden" name="product_destin" value="Sentra Tenun Sukarara">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

<!-- ---------- hotel 14 ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Dusun Beleq Bungalow" data-price="220000" data-cat="hotel"
  data-destin="Dusun Adat Beleq"
  data-lat="-8.7744" data-lng="116.3831" data-gmaps="Dusun Beleq Bungalow">
  <div class="relative">
    <img src="https://authentic-indonesia.com/wp-content/uploads/2019/11/floating-lodges-in-dusun-bambu-lembang-1024x683.jpg"
         alt="Dusun Beleq Bungalow" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Dusun Beleq Bungalow</h3>
    <p class="text-amber-900 font-bold">Rp 220.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Dusun Beleq Bungalow">
        <input type="hidden" name="product_price" value="220000">
        <input type="hidden" name="product_category" value="hotel">
        <input type="hidden" name="product_destin" value="Dusun Adat Beleq">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

<!-- ---------- hotel 15 ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Tete Batu Garden Resort" data-price="380000" data-cat="hotel"
  data-destin="Tete Batu"
  data-lat="-8.5301" data-lng="116.4512" data-gmaps="Tetebatu Garden Resort">
  <div class="relative">
    <img src="https://cf.bstatic.com/xdata/images/hotel/max1024x768/633485462.jpg?k=6b26dd426f7ee4d78e56efeabc17900616de87632530ef7bd391e41c5b2e1f7c&o=&hp=1"
         alt="Tete Batu Garden" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Tete Batu Garden Resort</h3>
    <p class="text-amber-900 font-bold">Rp 380.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <form method="POST" action="pesan.php">
        <input type="hidden" name="product_name" value="Tete Batu Garden Resort">
        <input type="hidden" name="product_price" value="380000">
        <input type="hidden" name="product_category" value="hotel">
        <input type="hidden" name="product_destin" value="Tete Batu">
        <button type="submit" name="add_to_cart" class="w-full bg-amber-900 text-white py-1 rounded">Pesan Sekarang</button>
      </form>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Dari Lokasi</button>
    </div>
  </div>
</article>

</main>

<!-- ===== PETA ===== -->
<section class="max-w-7xl mx-auto px-4 pb-10">
  <h2 class="text-xl font-bold mb-4">Lokasi Transportasi & Akomodasi</h2>
  <div id="map" class="rounded-lg shadow"></div>
</section>

<!-- ===== MODAL CHECKOUT ===== -->
<div id="checkoutModal" class="fixed inset-0 bg-black/40 flex items-start justify-center <?= empty($_SESSION['cart']) ? 'hidden' : '' ?> z-20 py-4">
  <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-4 relative my-4">
    <button onclick="closeCheckout()" class="absolute top-2 right-2 text-gray-500 hover:text-black">&times;</button>
    <h3 class="text-lg font-semibold mb-3">Checkout Pemesanan</h3>
    
    <div id="cartList" class="space-y-2 max-h-[120px] overflow-y-auto mb-3 text-sm">
      <?php foreach ($_SESSION['cart'] as $item): ?>
        <div class="flex justify-between border p-2 rounded text-sm">
          <span><?= htmlspecialchars($item['name']) ?> × <?= $item['qty'] ?></span>
          <span>Rp <?= number_format($item['price'] * $item['qty'], 0, ',', '.') ?></span>
        </div>
      <?php endforeach; ?>
    </div>
    
    <form id="checkoutForm" method="POST" action="pesan.php" class="space-y-3">
      <input id="buyerName" name="buyerName" required placeholder="Nama pemesan"
            class="form-input" value="<?= htmlspecialchars($user['username'] ?? '') ?>" />

      <input id="buyerPhone" name="buyerPhone" required pattern="^[0-9+ ]{8,}$"
            placeholder="Nomor HP (WhatsApp)"
            class="form-input" />

      <!-- Tanggal keberangkatan untuk bus -->
      <div id="busDate" class="<?= array_reduce($_SESSION['cart'], function($hasBus, $item) { return $hasBus || $item['category'] === 'bus'; }, false) ? '' : 'hidden' ?> space-y-2">
        <label class="block text-sm font-semibold">Tanggal Keberangkatan</label>
        <input type="date" id="departureDate" name="departureDate" class="form-input w-full" />
      </div>

      <!-- Tanggal check-in dan check-out untuk hotel -->
      <div id="hotelDates" class="<?= array_reduce($_SESSION['cart'], function($hasHotel, $item) { return $hasHotel || $item['category'] === 'hotel'; }, false) ? '' : 'hidden' ?> space-y-2">
        <label class="block text-sm font-semibold">Tanggal Check-in</label>
        <input type="date" id="checkinDate" name="checkinDate" class="form-input w-full" />

        <label class="block text-sm font-semibold">Tanggal Check-out</label>
        <input type="date" id="checkoutDate" name="checkoutDate" class="form-input w-full" />
      </div>

      <textarea id="buyerAddr" name="buyerAddr" required rows="2"
                placeholder="Alamat lengkap"
                class="form-input"></textarea>

      <button type="submit" name="checkout" class="w-full bg-amber-900 hover:brightness-110 text-white py-2 rounded mt-2">
        Konfirmasi Pemesanan
      </button>
    </form>
  </div>
</div>

<script>
/* =======================  STATE  ======================= */
let activeCat = 'all';
let activeDest = 'all';
let searchTerm = '';

/* ====================  FUNGSI INTI  ==================== */
function refreshGrid(){
  document.querySelectorAll('.product-card').forEach(card=>{
    const cat  = (activeCat  === 'all' || card.dataset.cat     === activeCat);
    const dest = (activeDest === 'all' || card.dataset.destin  === activeDest);
    const srch = card.dataset.name.toLowerCase().includes(searchTerm);
    card.style.display = (cat && dest && srch) ? '' : 'none';
  });
}

/* ==================  KATEGORI BUTTON  ================== */
document.querySelectorAll('.filter-btn').forEach(btn=>{
  btn.onclick = () =>{
    activeCat = btn.dataset.filter;
    // styling
    document.querySelectorAll('.filter-btn').forEach(b=>{
      b.classList.replace('bg-amber-900','bg-white');
      b.classList.replace('text-white','text-gray-800');
    });
    btn.classList.replace('bg-white','bg-amber-900'); 
    btn.classList.replace('text-gray-800','text-white');
    refreshGrid();
  };
});

/* ===================  DESTINASI DROPDOWN  =================== */
document.getElementById('destSelect').onchange = e=>{
  activeDest = e.target.value === 'all' ? 'all' : e.target.value;
  refreshGrid();
};

/* =====================  SEARCH BOX  ===================== */
document.getElementById('search').oninput = e=>{
  searchTerm = e.target.value.toLowerCase();
  refreshGrid();
};

/* =======================  PETA  ======================== */
const map = L.map('map').setView([-8.6,116.1],9);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'&copy; OSM'}).addTo(map);

document.querySelectorAll('.product-card').forEach(card=>{
  const m = L.marker([+card.dataset.lat,+card.dataset.lng]).addTo(map).bindPopup(card.dataset.name);
  card.dataset.markerId = m._leaflet_id;
});

function onsite(btn){
  const c = btn.closest('.product-card');
  map.flyTo([c.dataset.lat,c.dataset.lng],13);
  map._layers[c.dataset.markerId].openPopup();
  window.open(`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(c.dataset.gmaps)}`, '_blank');
}

/* ================  KERANJANG & CHECKOUT  ================ */
document.getElementById('cartBtn').onclick = () => {
  if(<?= empty($_SESSION['cart']) ? 'true' : 'false' ?>) return alert('Keranjang kosong!');
  document.getElementById('checkoutModal').classList.remove('hidden');
};

function closeCheckout(){
  document.getElementById('checkoutModal').classList.add('hidden');
}

/* =======  Panggil sekali agar kondisi awal konsisten  ======= */
refreshGrid();
</script>
</body>
</html>