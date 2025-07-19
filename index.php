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
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BALEQARA</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
    .gradient-header {
      background: linear-gradient(135deg, #78350f, #f59e0b);
    }
    .batik-pattern {
      background-image: url('https://images.fineartamerica.com/images/artworkimages/mediumlarge/3/songket-tenun-geometrik-seamless-pattern-with-creative-bali-lombok-sasak-traditional-village-motif-from-indonesian-batik-julien.jpg');
      background-size: cover;
      background-repeat: repeat;
      opacity: 0.05;
      position: absolute;
      inset: 0;
      z-index: 0;
    }
    /* Mobile menu animation classes */
    .menu-enter {
      transform: translateX(100%);
    }
    .menu-enter-active {
      transform: translateX(0);
      transition: transform 300ms ease-in-out;
    }
    .menu-leave {
      transform: translateX(0);
    }
    .menu-leave-active {
      transform: translateX(100%);
      transition: transform 300ms ease-in-out;
    }
  </style>
</head>
<body class="bg-amber-50 overflow-x-hidden">
  <!-- Header -->
  <header class="gradient-header text-white p-4 shadow-md flex justify-between items-center relative overflow-hidden">
    <div class="batik-pattern"></div>
    <div class="flex items-center z-10">
      <img src="assets/sasak.jpg" class="rounded-full mr-3" alt="Logo Sasak" width="60" height="60" />
      <span class="text-xl font-bold">BALEQARA</span>
    </div>
    <!-- Hamburger Icon -->
    <button id="openMenu" aria-label="Buka menu" class="text-3xl cursor-pointer z-10 focus:outline-none focus:ring-2 focus:ring-white rounded">
      ☰
    </button>
  </header>

  <!-- Off‑canvas Mobile Menu -->
  <nav id="mobileMenu" aria-label="Menu utama" class="fixed top-0 right-0 h-full w-64 bg-white text-amber-900 shadow-lg pt-6 transform translate-x-full transition-transform duration-300 z-50">
    <button id="closeMenu" aria-label="Tutup menu" class="absolute top-3 right-4 text-3xl leading-none focus:outline-none">&times;</button>
    <ul class="mt-12 space-y-6 px-6 font-semibold">
      <li><a href="sasakwisata.php" class="block hover:text-amber-600">Sasak Wisata</a></li>
      <li><a href="katalog.php" class="block hover:text-amber-600">Katalog Budaya</a></li>
      <li><a href="umkm.php" class="block hover:text-amber-600">UMKM Lokal</a></li>
      <li><a href="suara.php" class="block hover:text-amber-600">Suara Lokal</a></li>
      <li><a href="rute.php" class="block hover:text-amber-600">Rekomendasi Wisata</a></li>
      <li><a href="pesan.php" class="block hover:text-amber-600">Tour & Wisata</a></li>
      <li class="pt-4 border-t">
        <span class="text-gray-500">Logged in as: <?php echo htmlspecialchars($user['username']); ?></span>
      </li>
      <li>
        <a href="logout.php" class="block text-red-600 hover:text-red-800">Logout</a>
      </li>
    </ul>
  </nav>

  <!-- Overlay -->
  <div id="overlay" class="fixed inset-0 bg-black bg-opacity-25 backdrop-blur-sm hidden z-40"></div>

  <!-- Hero -->
  <section class="text-center mt-10 px-4">
    <h1 class="text-2xl font-bold text-amber-900">Welcome to BALEQARA</h1>
    <p class="text-sm text-gray-700 mt-2">Temukan kekayaan budaya Sasak dengan tampilan modern dan informatif.</p>
  </section>

  <!-- Galeri destinasi -->
  <section class="grid grid-cols-2 md:grid-cols-4 gap-4 p-6">
    <figure class="text-center">
      <a href="sasakwisata.php#pantai-seger">
        <img src="https://travelspromo.com/wp-content/uploads/2022/02/Keindahan-pantai-seger-lombok-berlatar-bukit.jpeg" class="rounded shadow w-full h-48 object-cover" alt="Pantai Seger" />
        <figcaption class="mt-2 text-sm text-amber-900 font-medium">Pantai Seger</figcaption>
      </a>
    </figure>

    <figure class="text-center">
      <a href="sasakwisata.php#desa-sade">
        <img src="https://cdn.idntimes.com/content-images/community/2020/01/19424765-245953129236714-810761388782780416-n-6fa4cbbd82db936bbc84478e9a40e3fc.jpg" class="rounded shadow w-full h-48 object-cover" alt="Desa Sade" />
        <figcaption class="mt-2 text-sm text-amber-900 font-medium">Desa Adat Sade</figcaption>
      </a>
    </figure>

    <figure class="text-center">
      <a href="sasakwisata.php#gunung-rinjani">
        <img src="https://media.suara.com/pictures/970x544/2025/06/26/40338-ilustrasi-gunung-rinjani.jpg" class="rounded shadow w-full h-48 object-cover" alt="Gunung Rinjani" />
        <figcaption class="mt-2 text-sm text-amber-900 font-medium">Gunung Rinjani</figcaption>
      </a>
    </figure>

    <figure class="text-center">
      <a href="sasakwisata.php#pantai-kuta">
        <img src="http://1.bp.blogspot.com/-UX002Vm9xqY/Uf-WX_fkCwI/AAAAAAAABrY/2Zw9Wh26bdk/s1600/Pantai+kuta+Lombok.jpg" class="rounded shadow w-full h-48 object-cover" alt="Pantai Kuta" />
        <figcaption class="mt-2 text-sm text-amber-900 font-medium">Pantai Kuta Mandalika</figcaption>
      </a>
    </figure>
  </section>

  <!-- Quick Links Section -->
  <section class="px-6 mt-6 mb-24">
    <h2 class="text-lg font-bold text-amber-900 mb-4">Akses Cepat</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
      <a href="pesan.php" class="bg-white p-4 rounded-lg shadow flex items-center justify-center text-center hover:bg-amber-50 transition-colors">
        <div>
          <div class="text-2xl mb-2">🚌</div>
          <span>Pesan Travel</span>
        </div>
      </a>
      
      <a href="katalog.php" class="bg-white p-4 rounded-lg shadow flex items-center justify-center text-center hover:bg-amber-50 transition-colors">
        <div>
          <div class="text-2xl mb-2">🧵</div>
          <span>Katalog Budaya</span>
        </div>
      </a>
      
      <a href="rute.php" class="bg-white p-4 rounded-lg shadow flex items-center justify-center text-center hover:bg-amber-50 transition-colors">
        <div>
          <div class="text-2xl mb-2">🗺️</div>
          <span>Rute Wisata</span>
        </div>
      </a>
      
      <a href="suara.php" class="bg-white p-4 rounded-lg shadow flex items-center justify-center text-center hover:bg-amber-50 transition-colors">
        <div>
          <div class="text-2xl mb-2">🎤</div>
          <span>Suara Lokal</span>
        </div>
      </a>
      
      <a href="umkm.php" class="bg-white p-4 rounded-lg shadow flex items-center justify-center text-center hover:bg-amber-50 transition-colors">
        <div>
          <div class="text-2xl mb-2">🛍️</div>
          <span>UMKM Lokal</span>
        </div>
      </a>
      
      <a href="sasakwisata.php" class="bg-white p-4 rounded-lg shadow flex items-center justify-center text-center hover:bg-amber-50 transition-colors">
        <div>
          <div class="text-2xl mb-2">🏝️</div>
          <span>Sasak Wisata </span>
        </div>
      </a>
    </div>
  </section>

  <!-- Statistik Grafik -->
  <section class="px-6 mt-6 grid grid-cols-1 md:grid-cols-2 gap-6 mb-24">
    <!-- Grafik 1 -->
    <div class="bg-white p-4 rounded shadow">
      <h2 class="text-lg font-bold text-amber-900 mb-3">Destinasi Paling Banyak Dikunjungi</h2>
      <canvas id="chartDestinasi"></canvas>
    </div>

    <!-- Grafik 2 -->
    <div class="bg-white p-4 rounded shadow">
      <h2 class="text-lg font-bold text-amber-900 mb-3">Jumlah Wisatawan ke NTB per Tahun</h2>
      <canvas id="chartWisatawan"></canvas>
    </div>
  </section>

  <footer class="bg-amber-900 text-white text-sm text-center py-3">
    <p>&copy; 2025 BALEQARA | Dilestarikan dengan cinta budaya.</p>
  </footer>

  <script>
    // Mobile menu logic
    const openMenuBtn = document.getElementById('openMenu');
    const closeMenuBtn = document.getElementById('closeMenu');
    const mobileMenu = document.getElementById('mobileMenu');
    const overlay = document.getElementById('overlay');

    function openMenu() {
      mobileMenu.classList.remove('translate-x-full');
      overlay.classList.remove('hidden');
    }

    function closeMenu() {
      mobileMenu.classList.add('translate-x-full');
      overlay.classList.add('hidden');
    }

    openMenuBtn.addEventListener('click', openMenu);
    closeMenuBtn.addEventListener('click', closeMenu);
    overlay.addEventListener('click', closeMenu);

    // Chart Destinasi
    const ctxDestinasi = document.getElementById('chartDestinasi').getContext('2d');
    new Chart(ctxDestinasi, {
      type: 'bar',
      data: {
        labels: ['Pantai Seger', 'Desa Sade', 'Rinjani', 'Pantai Kuta'],
        datasets: [{
          label: 'Jumlah Kunjungan',
          data: [1200, 900, 700, 850],
          backgroundColor: '#f59e0b'
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
        }
      }
    });

    // Chart Wisatawan per Tahun
    const ctxWisatawan = document.getElementById('chartWisatawan').getContext('2d');
    new Chart(ctxWisatawan, {
      type: 'line',
      data: {
        labels: ['2020', '2021', '2022', '2023', '2024'],
        datasets: [{
          label: 'Jumlah Wisatawan (ribu)',
          data: [500, 300, 750, 1200, 1500],
          backgroundColor: 'rgba(245, 158, 11, 0.2)',
          borderColor: '#f59e0b',
          borderWidth: 2,
          tension: 0.3,
          fill: true,
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'top' },
        }
      }
    });
  </script>
</body>
</html>