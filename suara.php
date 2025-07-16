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
  <title>Suara Lokal - BALEQARA</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
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
    .audio-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .section-title {
      position: relative;
      display: inline-block;
    }
    .section-title:after {
      content: '';
      position: absolute;
      width: 50%;
      height: 3px;
      background: #f59e0b;
      bottom: -8px;
      left: 25%;
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
      <li><a href="booking.php" class="block hover:text-amber-600">Tour & Wisata</a></li>
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

  <!-- Main Content -->
  <main class="container mx-auto px-4 py-8">
    <section class="text-center mb-10">
      <h1 class="text-3xl font-bold text-amber-900 mb-2">Suara Lokal Lombok</h1>
      <p class="text-gray-700">Dengarkan musik, cerita, dan tradisi khas budaya Sasak</p>
    </section>

    <!-- Presean Section -->
    <section class="mb-16">
      <h2 class="text-2xl font-bold text-amber-900 mb-6 text-center section-title">Presean - Seni Bela Diri Sasak</h2>
      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row gap-6">
          <div class="md:w-1/3">
            <img src="https://asset.kompas.com/crops/XZYxABC123/0x0:1200x800/750x500/data/photo/2021/06/15/ABC123.jpg" alt="Presean" class="w-full h-64 object-cover rounded-lg">
          </div>
          <div class="md:w-2/3">
            <h3 class="text-xl font-semibold text-amber-900 mb-3">Suara dan Musik Pengiring Presean</h3>
            <p class="text-gray-700 mb-4">Presean adalah seni bela diri tradisional khas Lombok yang diiringi oleh musik khas. Dengarkan suara rotan yang saling beradu dan irama musik pengiringnya.</p>
            <audio controls class="w-full mb-4">
              <source src="assets/presean.mp3" type="audio/mpeg">
              Browser Anda tidak mendukung elemen audio.
            </audio>
            <p class="text-gray-600 text-sm"><strong>Deskripsi:</strong> Rekaman langsung pertunjukan Presean di Desa Sade, Lombok Tengah. Terdengar suara rotan (penjalin), teriakan pemain, dan musik pengiring.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Gunung Rinjani Section -->
    <section class="mb-16">
      <h2 class="text-2xl font-bold text-amber-900 mb-6 text-center section-title">Legenda Gunung Rinjani</h2>
      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row gap-6">
          <div class="md:w-1/3">
            <img src="https://asset.kompas.com/crops/XZYxABC456/0x0:1200x800/750x500/data/photo/2021/06/15/ABC456.jpg" alt="Gunung Rinjani" class="w-full h-64 object-cover rounded-lg">
          </div>
          <div class="md:w-2/3">
            <h3 class="text-xl font-semibold text-amber-900 mb-3">Kisah Mistis Gunung Rinjani</h3>
            <p class="text-gray-700 mb-4">Dengarkan cerita rakyat tentang asal-usul Gunung Rinjani dan Danau Segara Anak yang disampaikan oleh tetua adat Sasak.</p>
            <audio controls class="w-full mb-4">
              <source src="assets/gunung.mp3" type="audio/mpeg">
              Browser Anda tidak mendukung elemen audio.
            </audio>
            <div class="bg-amber-50 p-4 rounded-lg">
              <h4 class="font-semibold text-amber-900 mb-2">Ringkasan Cerita:</h4>
              <p class="text-gray-700">Konon Gunung Rinjani terbentuk dari peristiwa besar ketika Dewi Anjani, penguasa alam gaib, menancapkan tongkat sakti ke bumi. Danau Segara Anak dipercaya sebagai tempat bersemayam para dewa.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Makam Keramat Section -->
    <section class="mb-16">
      <h2 class="text-2xl font-bold text-amber-900 mb-6 text-center section-title">Makam Keramat Lombok</h2>
      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row gap-6">
          <div class="md:w-1/3">
            <img src="https://asset.kompas.com/crops/XZYxABC789/0x0:1200x800/750x500/data/photo/2021/06/15/ABC789.jpg" alt="Makam Keramat" class="w-full h-64 object-cover rounded-lg">
          </div>
          <div class="md:w-2/3">
            <h3 class="text-xl font-semibold text-amber-900 mb-3">Ritual dan Doa di Makam Keramat</h3>
            <p class="text-gray-700 mb-4">Dengarkan suasana ritual dan doa di makam-makam keramat Lombok seperti Makam Loang Baloq dan Makam Batu Layar.</p>
            <audio controls class="w-full mb-4">
              <source src="assets/gerung.mp3" type="audio/mpeg">
              Browser Anda tidak mendukung elemen audio.
            </audio>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="bg-amber-50 p-4 rounded-lg">
                <h4 class="font-semibold text-amber-900 mb-2">Makam Loang Baloq</h4>
                <p class="text-gray-700 text-sm">Terletak di Mataram, makam ini dipercaya sebagai tempat bersemayam salah satu penyebar agama Islam di Lombok.</p>
              </div>
              <div class="bg-amber-50 p-4 rounded-lg">
                <h4 class="font-semibold text-amber-900 mb-2">Makam Batu Layar</h4>
                <p class="text-gray-700 text-sm">Makam keramat di Lombok Barat yang dikunjungi banyak peziarah, terutama setiap malam Jumat Kliwon.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="mb-16">
  <h2 class="text-2xl font-bold text-amber-900 mb-6 text-center section-title">Legenda Putri Mandalika</h2>
  <div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex flex-col md:flex-row gap-6">
      <div class="md:w-1/3">
        <img src="assets/mandalika.jpeg" alt="Putri Mandalika" class="w-full h-64 object-cover rounded-lg">
      </div>
      <div class="md:w-2/3">
        <h3 class="text-xl font-semibold text-amber-900 mb-3">Cerita dan Tradisi Putri Mandalika</h3>
        <p class="text-gray-700 mb-4">
          Dengarkan kisah legendaris Putri Mandalika, seorang putri cantik dari Kerajaan Tunjung Bitu yang memilih berkorban demi perdamaian. Kisahnya diabadikan dalam tradisi Bau Nyale di pesisir selatan Lombok.
        </p>
        <audio controls class="w-full mb-4">
          <source src="assets/mandalika.mp3" type="audio/mpeg">
          Browser Anda tidak mendukung elemen audio.
        </audio>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="bg-amber-50 p-4 rounded-lg">
            <h4 class="font-semibold text-amber-900 mb-2">Pantai Seger</h4>
            <p class="text-gray-700 text-sm">
              Tempat di mana Putri Mandalika dipercaya menghilang ke laut. Di pantai ini digelar ritual tahunan Bau Nyale, menangkap cacing laut yang dipercaya sebagai jelmaan sang putri.
            </p>
          </div>
          <div class="bg-amber-50 p-4 rounded-lg">
            <h4 class="font-semibold text-amber-900 mb-2">Monumen Putri Mandalika</h4>
            <p class="text-gray-700 text-sm">
              Monumen megah yang dibangun untuk mengenang pengorbanan Putri Mandalika, menjadi ikon budaya dan daya tarik wisata di Mandalika.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


    <!-- Traditional Music Section -->
    <section>
      <h2 class="text-2xl font-bold text-amber-900 mb-6 text-center section-title">Musik Tradisional Sasak</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Gendang Beleq -->
        <div class="audio-card bg-white rounded-lg shadow-md p-4 transition-all duration-300">
          <div class="mb-4">
            <img src="assets/belek.jpeg" alt="Gendang Beleq" class="w-full h-48 object-cover rounded-lg">
          </div>
          <h3 class="text-xl font-semibold text-amber-900 mb-2">Gendang Beleq</h3>
          <p class="text-gray-600 mb-4">Orkestra tradisional khas suku Sasak</p>
          <audio controls class="w-full">
            <source src="assets/belek.mp3" type="audio/mpeg">
            Browser Anda tidak mendukung elemen audio.
          </audio>
        </div>

        <!-- Tari Gandrung -->
        <div class="audio-card bg-white rounded-lg shadow-md p-4 transition-all duration-300">
          <div class="mb-4">
            <img src="https://i.ytimg.com/vi/DEF456/maxresdefault.jpg" alt="Tari Gandrung" class="w-full h-48 object-cover rounded-lg">
          </div>
          <h3 class="text-xl font-semibold text-amber-900 mb-2">Tari Gandrung</h3>
          <p class="text-gray-600 mb-4">Musik pengiring tari tradisional Lombok</p>
          <audio controls class="w-full">
            <source src="assets/gandrung.mp3" type="audio/mpeg">
            Browser Anda tidak mendukung elemen audio.
          </audio>
        </div>

        <!-- bekayat Sasak -->
        <div class="audio-card bg-white rounded-lg shadow-md p-4 transition-all duration-300">
          <div class="relative mb-4">
            <img src="https://asset.kompas.com/crops/XZYxBBK789/0x0:1200x800/750x500/data/photo/2021/06/15/BBK789.jpg" alt="Bebekayat" class="w-full h-48 object-cover rounded-lg">
          </div>
          <h3 class="text-xl font-semibold text-amber-900 mb-2">Bebekayat Doyan Nada</h3>
          <p class="text-gray-600 mb-4">Cerita moral tentang keserakahan dan konsekuensinya</p>
          <audio controls class="w-full">
            <source src="assets/bekayat.mp3" type="audio/mpeg">
            Browser Anda tidak mendukung elemen audio.
          </audio>
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer class="bg-amber-900 text-white text-sm text-center py-3 mt-8">
    <p>&copy; 2025 BALEQARA | Tim Pengembang</p>
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
  </script>
</body>
</html>