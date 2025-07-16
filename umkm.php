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
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Katalog UMKM – Sasak Wisata</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
 body{font-family:'Poppins',sans-serif}
 .product-card:hover{box-shadow:0 6px 16px rgba(0,0,0,.08);transform:translateY(-2px)}
 .line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
 #map{height:380px}
</style>
</head>
<body class="bg-amber-100 text-gray-800">

<!-- ===== NAVBAR ===== -->
<nav class="bg-amber-900 text-white p-4 flex flex-wrap gap-4 items-center sticky top-0 z-10">
  <span class="font-semibold text-lg tracking-wide">BALEQARA</span>

  <!-- Search -->
  <input id="search" type="text" placeholder="Cari produk…" class="flex-1 min-w-[180px] p-2 rounded text-gray-800" />

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
  <button id="cartBtn" class="relative">🛒
    <span id="cartCount" class="absolute -top-2 -right-3 bg-red-600 text-xs rounded-full px-1 hidden">0</span>
  </button>
</nav>

<!-- ===== TEKS PROMOSI ===== -->
<section class="text-center py-6 px-4 bg-amber-100">
  <h1 class="text-2xl font-bold text-amber-900 mb-2">Yuk Jelajahi UMKM LOMBOK</h1>
  <p class="text-gray-700">Filter berdasar kategori, destinasi, atau kata kunci untuk menemukan oleh‑oleh terbaik!</p>
</section>

<!-- ===== KATEGORI ===== -->
<section class="max-w-7xl mx-auto px-4 py-4">
  <div id="catBar" class="flex flex-wrap gap-3">
    <button data-filter="all"       class="filter-btn px-3 py-1 rounded-full">Semua</button>
    <button data-filter="tenun"     class="filter-btn px-3 py-1 rounded-full">Tenun</button>
    <button data-filter="kopi"      class="filter-btn px-3 py-1 rounded-full">Kopi</button>
    <button data-filter="kuliner"   class="filter-btn px-3 py-1 rounded-full">Kuliner</button>
    <button data-filter="kerajinan" class="filter-btn px-3 py-1 rounded-full">Kerajinan</button>
  </div>
</section>

<!-- ===== GRID PRODUK ===== -->
<main id="grid" class="max-w-7xl mx-auto grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 px-4 pb-8">

<!-- ---------- TENUN CONTOH ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Songket Sukarara" data-price="350000" data-cat="tenun"
  data-destin="Desa Adat Sade"
  data-lat="-8.8329" data-lng="116.3204" data-gmaps="Songket Sukarara, Lombok">
  <div class="relative">
    <img src="https://www.freedomnesia.id/wp-content/uploads/2020/12/Kain-songket.jpg"
         alt="Songket" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Tenun Songket Sukarara</h3>
    <p class="text-amber-900 font-bold">Rp 350.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <button onclick="addToCart(this)" class="bg-amber-900 text-white py-1 rounded">Beli Sekarang</button>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Di Tempat</button>
    </div>
  </div>
</article>

<!-- ===== KERAJINAN LOMBOK ===== -->

<!-- Kerajinan 1 - Gerabah Banyumulek -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Gerabah Banyumulek Miniatur" data-price="120000" data-cat="kerajinan"
  data-destin="Desa Banyumulek"
  data-lat="-8.6234" data-lng="116.1087" data-gmaps="Gerabah Banyumulek Lombok">
  <div class="relative">
    <img src="assets/gerabah.jpeg"
    alt="Gerabah Banyumulek" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Gerabah Banyumulek Miniatur</h3>
    <p class="text-amber-900 font-bold">Rp 120.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <button onclick="addToCart(this)" class="bg-amber-900 text-white py-1 rounded">Beli Sekarang</button>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Di Tempat</button>
    </div>
  </div>
</article>

<!-- Kerajinan 2 - Anyaman Rotan -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Tas Anyaman Rotan Sesaot" data-price="95000" data-cat="kerajinan"
  data-destin="Wisata Sesaot"
  data-lat="-8.5723" data-lng="116.2718" data-gmaps="Anyaman Rotan Sesaot">
  <div class="relative">
    <img src="assets/rotan.jpeg"
    alt="Tas Rotan" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Tas Anyaman Rotan Sesaot</h3>
    <p class="text-amber-900 font-bold">Rp 95.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <button onclick="addToCart(this)" class="bg-amber-900 text-white py-1 rounded">Beli Sekarang</button>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Di Tempat</button>
    </div>
  </div>
</article>

<!-- Kerajinan 3 - Ingke (Tempat Nasi Sasak) -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Ingke Tradisional Sasak" data-price="65000" data-cat="kerajinan"
  data-destin="Desa Adat Sade"
  data-lat="-8.8329" data-lng="116.3204" data-gmaps="Ingke Sasak Lombok">
  <div class="relative">
    <img src="assets/ingke.jpeg"
    alt="Ingke Sasak" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Ingke Tradisional Sasak</h3>
    <p class="text-amber-900 font-bold">Rp 65.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <button onclick="addToCart(this)" class="bg-amber-900 text-white py-1 rounded">Beli Sekarang</button>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Di Tempat</button>
    </div>
  </div>
</article>

<!-- Kerajinan 4 - Anyaman Bambu -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Anyaman Bambu Kotak Tisu" data-price="45000" data-cat="kerajinan"
  data-destin="Desa Sukarara"
  data-lat="-8.8329" data-lng="116.3204" data-gmaps="Anyaman Bambu Sukarara">
  <div class="relative">
    <img src="assets/anyam.jpeg"
    alt="Anyaman Bambu" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Anyaman Bambu Kotak Tisu</h3>
    <p class="text-amber-900 font-bold">Rp 45.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <button onclick="addToCart(this)" class="bg-amber-900 text-white py-1 rounded">Beli Sekarang</button>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Di Tempat</button>
    </div>
  </div>
</article>

<!-- ---------- KOPI CONTOH ---------- -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Kopi Arabika Senaru 250 g" data-price="65000" data-cat="kopi"
  data-destin="Gunung Rinjani"
  data-lat="-8.3235" data-lng="116.3997" data-gmaps="Kopi Senaru, Lombok">
  <div class="relative">
    <img src="https://sp-ao.shortpixel.ai/client/to_webp,q_lossy,ret_img,w_750,h_750/https://mobillombok.com/wp-content/uploads/2020/03/Produk-Kopi-Senaru-sumber-ig-hhengkii.jpg"
         alt="Kopi" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Kopi Arabika Senaru</h3>
    <p class="text-amber-900 font-bold">Rp 65.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <button onclick="addToCart(this)" class="bg-amber-900 text-white py-1 rounded">Beli Sekarang</button>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Di Tempat</button>
    </div>
  </div>
</article>

<!-- ---------- 15 PRODUK KULINER (SERAGAM FORMAT) ---------- -->
<!-- Kuliner 1 -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Ares Manis 500 g" data-price="25000" data-cat="kuliner"
  data-lat="-8.583" data-lng="116.116" data-gmaps="Ares Lombok">
  <div class="relative">
    <img src="https://cdn.antaranews.com/cache/800x533/2023/09/18/Arres-Lombok.jpg"
         alt="Ares Manis" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Ares Instan</h3>
    <p class="text-amber-900 font-bold">Rp 25.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <button onclick="addToCart(this)" class="bg-amber-900 text-white py-1 rounded">Beli Sekarang</button>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Di Tempat</button>
    </div>
  </div>
</article>

<!-- Kuliner 2 -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Sate Rembiga Beku 10 Tusuk" data-price="45000" data-cat="kuliner"
  data-lat="-8.581" data-lng="116.102" data-gmaps="Sate Rembiga Lombok">
  <div class="relative">
    <img src="https://down-id.img.susercontent.com/file/17a6417ac57ee3332abb8bb916dbc590"
         alt="Sate Rembiga" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Sate Rembiga Goyang Lidah</h3>
    <p class="text-amber-900 font-bold">Rp 45.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <button onclick="addToCart(this)" class="bg-amber-900 text-white py-1 rounded">Beli Sekarang</button>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Di Tempat</button>
    </div>
  </div>
</article>

<!-- Kuliner 3 -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Ayam Taliwang Frozen 700 g" data-price="68000" data-cat="kuliner"
  data-lat="-8.601" data-lng="116.091" data-gmaps="Ayam Taliwang Original">
  <div class="relative">
    <img src="https://cf.shopee.co.id/file/ac1b9ce04262df6b0b05d47ead6f7423"
         alt="Ayam Taliwang" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Ayam Taliwang Frozen 700 g</h3>
    <p class="text-amber-900 font-bold">Rp 68.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <button onclick="addToCart(this)" class="bg-amber-900 text-white py-1 rounded">Beli Sekarang</button>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Di Tempat</button>
    </div>
  </div>
</article>

<!-- Kuliner 4 - Sambal Beberuk -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Sambal Beberuk Terasi 200 g" data-price="20000" data-cat="kuliner"
  data-lat="-8.55" data-lng="116.12" data-gmaps="Beberuk Terasi">
  <div class="relative">
    <img src="assets/beberuk.webp"
         alt="Sambal Beberuk" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Sambal Beberuk Terasi 200 g</h3>
    <p class="text-amber-900 font-bold">Rp 20.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <button onclick="addToCart(this)" class="bg-amber-900 text-white py-1 rounded">Beli Sekarang</button>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Di Tempat</button>
    </div>
  </div>
</article>

<!-- Kuliner 5 - Dodol Rumput Laut -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Dodol Rumput Laut 250 g" data-price="30000" data-cat="kuliner"
  data-lat="-8.65" data-lng="116.07" data-gmaps="Dodol Rumput Laut Lombok">
  <div class="relative">
    <img src="assets/dodol.webp"
         alt="Dodol Rumput Laut" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Dodol Rumput Laut 250 g</h3>
    <p class="text-amber-900 font-bold">Rp 30.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <button onclick="addToCart(this)" class="bg-amber-900 text-white py-1 rounded">Beli Sekarang</button>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Di Tempat</button>
    </div>
  </div>
</article>

<!-- Kuliner 6 - Abon Ikan Tuna -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Abon Ikan Tuna 100 g" data-price="32000" data-cat="kuliner"
  data-lat="-8.72" data-lng="116.05" data-gmaps="Abon Tuna Lombok">
  <div class="relative">
    <img src="assets/abon.png"
         alt="Abon Tuna" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Abon Ikan Tuna Lombok 100 g</h3>
    <p class="text-amber-900 font-bold">Rp 32.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <button onclick="addToCart(this)" class="bg-amber-900 text-white py-1 rounded">Beli Sekarang</button>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Di Tempat</button>
    </div>
  </div>
</article>

<!-- Kuliner 7 - Keripik Pisang -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Keripik Pisang Bulu 200 g" data-price="18000" data-cat="kuliner"
  data-lat="-8.503" data-lng="116.34" data-gmaps="Keripik Pisang Lombok">
  <div class="relative">
    <img src="assets/pisang.jpeg"
    alt="Keripik Pisang" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Keripik Pisang Bulu 200 g</h3>
    <p class="text-amber-900 font-bold">Rp 18.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <button onclick="addToCart(this)" class="bg-amber-900 text-white py-1 rounded">Beli Sekarang</button>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Di Tempat</button>
    </div>
  </div>
</article>

<!-- Kuliner 9 - Permen Kopi -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Kopi Sembalun Robusta" data-price="15000" data-cat="kuliner"
  data-lat="-8.59" data-lng="116.3" data-gmaps="Kopi Sasak">
  <div class="relative">
    <img src="assets/kopi.jpeg"
         alt="Kopi" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Sasak Coffee Candy Pack</h3>
    <p class="text-amber-900 font-bold">Rp 15.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <button onclick="addToCart(this)" class="bg-amber-900 text-white py-1 rounded">Beli Sekarang</button>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Di Tempat</button>
    </div>
  </div>
</article>

<!-- Kuliner 10 - Emping Jagung -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Emping Jagung 150 g" data-price="17000" data-cat="kuliner"
  data-lat="-8.57" data-lng="116.24" data-gmaps="Emping Jagung Lombok">
  <div class="relative">
    <img src="assets/jagung.jpeg"
    alt="Emping Jagung" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Emping Jagung Manis 150 g</h3>
    <p class="text-amber-900 font-bold">Rp 17.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <button onclick="addToCart(this)" class="bg-amber-900 text-white py-1 rounded">Beli Sekarang</button>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Di Tempat</button>
    </div>
  </div>
</article>


<!-- Kuliner 11 -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Sambal Mbe Lombok 150 g" data-price="28000" data-cat="kuliner"
  data-lat="-8.6" data-lng="116.15" data-gmaps="Sambal Mbe Lombok">
  <div class="relative">
    <img src="assets/sambal.jpeg"
         alt="Sambal Mbe" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Sambal Lombok Begerik 150 g</h3>
    <p class="text-amber-900 font-bold">Rp 28.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <button onclick="addToCart(this)" class="bg-amber-900 text-white py-1 rounded">Beli Sekarang</button>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Di Tempat</button>
    </div>
  </div>
</article>

<!-- Kuliner 12 -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Kerupuk Kulit Ikan 100 g" data-price="26000" data-cat="kuliner"
  data-lat="-8.63" data-lng="116.19" data-gmaps="Kerupuk Kulit Ikan Lombok">
  <div class="relative">
    <img src="assets/kulit.jpeg"
         alt="Kerupuk Ikan" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Kerupuk Kulit Ikan 100 g</h3>
    <p class="text-amber-900 font-bold">Rp 26.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <button onclick="addToCart(this)" class="bg-amber-900 text-white py-1 rounded">Beli Sekarang</button>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Di Tempat</button>
    </div>
  </div>
</article>

<!-- Kuliner 13 -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Jajan Cerorot 12 pcs" data-price="24000" data-cat="kuliner"
  data-lat="-8.58" data-lng="116.22" data-gmaps="Cerorot Lombok">
  <div class="relative">
    <img src="assets/celorot.jpeg"
         alt="Cerorot" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Jajan Cerorot 12 pcs</h3>
    <p class="text-amber-900 font-bold">Rp 24.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <button onclick="addToCart(this)" class="bg-amber-900 text-white py-1 rounded">Beli Sekarang</button>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Di Tempat</button>
    </div>
  </div>
</article>

<!-- Kuliner 14 -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Kacang Mete Madu 100 g" data-price="38000" data-cat="kuliner"
  data-lat="-8.47" data-lng="116.25" data-gmaps="Kacang Mete Lombok">
  <div class="relative">
    <img src="assets/mete.jpeg"
         alt="Kacang Mete" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Kacang Mete Madu 100 g</h3>
    <p class="text-amber-900 font-bold">Rp 38.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <button onclick="addToCart(this)" class="bg-amber-900 text-white py-1 rounded">Beli Sekarang</button>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Di Tempat</button>
    </div>
  </div>
</article>

<!-- Kuliner 15 -->
<article class="product-card bg-white rounded-lg overflow-hidden shadow-sm"
  data-name="Sasak Herbal Tea Box" data-price="45000" data-cat="kuliner"
  data-lat="-8.495" data-lng="116.3" data-gmaps="Herbal Tea Lombok">
  <div class="relative">
    <img src="assets/tea.jpeg"
         alt="Herbal Tea" class="w-full aspect-square object-cover">
  </div>
  <div class="p-2 space-y-1">
    <h3 class="text-sm font-medium line-clamp-2">Sasak Herbal Tea Box</h3>
    <p class="text-amber-900 font-bold">Rp 45.000</p>
    <div class="grid grid-cols-2 gap-1 text-sm">
      <button onclick="addToCart(this)" class="bg-amber-900 text-white py-1 rounded">Beli Sekarang</button>
      <button onclick="onsite(this)" class="bg-green-600 hover:bg-green-700 text-white py-1 rounded">Di Tempat</button>
    </div>
  </div>
</article>

</main>

<!-- ===== PETA ===== -->
<section class="max-w-7xl mx-auto px-4 pb-10">
  <h2 class="text-xl font-bold mb-4">Lokasi UMKM</h2>
  <div id="map" class="rounded-lg shadow"></div>
</section>

<!-- ===== MODAL CHECKOUT ===== -->
<div id="checkoutModal" class="fixed inset-0 bg-black/40 flex items-center justify-center hidden z-20">
  <div class="bg-white w-full max-w-lg rounded-lg shadow-lg p-6 relative">
    <button onclick="closeCheckout()" class="absolute top-2 right-2 text-gray-500 hover:text-black">&times;</button>
    <h3 class="text-lg font-semibold mb-4">Checkout Keranjang</h3>
    <div id="cartList" class="space-y-2 max-h-52 overflow-y-auto mb-4"></div>
    <form id="checkoutForm" class="space-y-3">
      <input id="buyerName" required placeholder="Nama pemesan"
            class="w-full border p-2 rounded" value="<?php echo htmlspecialchars($user['username']); ?>" />

      <!-- ⇩ kolom Nomor HP baru ⇩ -->
      <input id="buyerPhone" required pattern="^[0-9+ ]{8,}$"
            placeholder="Nomor HP (WhatsApp)"
            class="w-full border p-2 rounded" />

      <textarea id="buyerAddr" required rows="3"
                placeholder="Alamat lengkap"
                class="w-full border p-2 rounded"></textarea>

      <button class="w-full bg-amber-900 hover:brightness-110 text-white py-2 rounded">
        Pesan Sekarang
      </button>
    </form>

  </div>
</div>

<script>
/* =======================  STATE  ======================= */
let activeCat  = 'all';
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
  if(btn.dataset.filter==='all') btn.classList.add('bg-amber-900','text-white');
  else                           btn.classList.add('bg-white','text-gray-800');

  btn.onclick = () =>{
    activeCat = btn.dataset.filter;
    // styling
    document.querySelectorAll('.filter-btn').forEach(b=>b.classList.replace('bg-amber-900','bg-white')||b.classList.replace('text-white','text-gray-800'));
    btn.classList.replace('bg-white','bg-amber-900'); btn.classList.replace('text-gray-800','text-white');
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
  window.open(`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(c.dataset.gmaps)}`,'_blank');
}

/* ================  KERANJANG & CHECKOUT (TIDAK DIUBAH) ================ */
let cart=[];

function addToCart(el){
  const c = el.closest('.product-card');
  const it = cart.find(x => x.name === c.dataset.name);
  it ? it.qty++ : cart.push({ name: c.dataset.name, price:+c.dataset.price, qty:1 });

  // perbarui badge
  const n = cart.reduce((s,i)=>s+i.qty,0);
  cartCount.textContent = n;
  cartCount.classList.remove('hidden');

  // —— notifikasi singkat ——  
  // gunakan alert() paling simpel — bisa diganti toast jika ingin lebih elegan
  alert('Belanjaan Anda telah ditambahkan di keranjang');
}


document.getElementById('cartBtn').onclick=()=>{
  if(!cart.length) return alert('Keranjang kosong!');
  const list=document.getElementById('cartList'); list.innerHTML='';
  cart.forEach(i=>list.insertAdjacentHTML('beforeend',`<div class="flex justify-between border p-2 rounded"><span>${i.name} × ${i.qty}</span><span>Rp ${(i.price*i.qty).toLocaleString('id-ID')}</span></div>`));
  checkoutModal.classList.remove('hidden');
};
function closeCheckout(){checkoutModal.classList.add('hidden');}

checkoutForm.onsubmit = e => {
  e.preventDefault();

  // ——— siapkan baris tabel & total ———
  let rows = '', tot = 0;
  cart.forEach(i => {
    const s = i.price * i.qty;
    tot += s;
    rows += `
      <tr>
        <td>${i.name}</td>
        <td class="ctr">${i.qty}</td>
        <td class="num">Rp ${i.price.toLocaleString('id-ID')}</td>
        <td class="num">Rp ${s.toLocaleString('id-ID')}</td>
      </tr>`;
  });

  // ——— buka jendela struk ———
  const w = window.open('', '', 'width=680,height=900');

  w.document.write(`
  <html>
  <head>
    <title>Struk Pembelian • BALEQARA</title>
    <style>
      @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
      body{
        font-family:'Poppins',sans-serif;
        margin:40px;
        position:relative;
      }
      body::before{
        content:'';
        position:fixed; inset:0;
        background:url('https://images.fineartamerica.com/images/artworkimages/mediumlarge/3/songket-tenun-geometrik-seamless-pattern-with-creative-bali-lombok-sasak-traditional-village-motif-from-indonesian-batik-julien.jpg') repeat;
        opacity:.06; z-index:-1;
      }
      .logo{
        display:flex;align-items:center;gap:12px;margin-bottom:24px;
      }
      .logo img{width:60px;height:60px;border-radius:50%}
      h2{margin:0;font-size:1.5rem;color:#78350f}
      .meta{color:#555;margin-bottom:20px;font-size:.9rem}
      table{width:100%;border-collapse:collapse;border-radius:8px;overflow:hidden}
      th,td{padding:8px 10px;border-bottom:1px solid #ddd;font-size:.9rem}
      th{background:#f5f3eb;text-align:left}
      .ctr{text-align:center}
      .num{text-align:right}
      tfoot td{font-weight:600;background:#fff8e6}
    </style>
  </head>
  <body onload="window.print()">

    <div class="logo">
      <img src="https://placehold.co/100x100" alt="Logo BALEQARA">
      <h2>BALEQARA – Struk Pembelian UMKM</h2>
    </div>

    <p class="meta">
      Tanggal&nbsp;:&nbsp;${new Date().toLocaleString('id-ID')}<br>
      Pemesan&nbsp;:&nbsp;${buyerName.value}<br>
      Nomor HP&nbsp;:&nbsp;${buyerPhone.value}<br>
      Alamat&nbsp;:&nbsp;${buyerAddr.value.replace(/\n/g,'<br>')}
    </p>


    <table>
      <thead>
        <tr><th>Produk</th><th class="ctr">Qty</th><th class="num">Harga</th><th class="num">Subtotal</th></tr>
      </thead>
      <tbody>${rows}</tbody>
      <tfoot>
        <tr><td colspan="3" class="num">Total&nbsp;</td><td class="num">Rp ${tot.toLocaleString('id-ID')}</td></tr>
      </tfoot>
    </table>

    <p style="margin-top:28px;font-size:.85rem;color:#666;text-align:center">
      Terima kasih telah mendukung UMKM lokal Lombok!<br>
      #BanggaBuatanIndonesia
    </p>

  </body>
  </html>`);

   w.document.close();

  // —— tampilkan pesan konfirmasi ke pengguna ——
  alert('Terima kasih telah berbelanja! Struk Anda terbuka di jendela baru.');

  cart = [];
  cartCount.classList.add('hidden');
  closeCheckout();
};


/* =======  Panggil sekali agar kondisi awal konsisten  ======= */
refreshGrid();
</script>
</body>
</html>