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