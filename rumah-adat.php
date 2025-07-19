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
  <title>Rumah Adat NTB – Warisan Budaya</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to bottom, #fff7ed, #ffedd5);
      color: #431407;
    }
    .card {
      transition: all 0.3s ease;
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(5px);
      border: 1px solid rgba(254, 215, 170, 0.3);
      border-radius: 12px;
      overflow: hidden;
    }
    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
      border-color: rgba(251, 146, 60, 0.5);
    }
    .card p { text-align: justify; }
    .batik-pattern {
      background-image: url('https://images.fineartamerica.com/images/artworkimages/mediumlarge/3/songket-tenun-geometrik-seamless-pattern-with-creative-bali-lombok-sasak-traditional-village-motif-from-indonesian-batik-julien.jpg');
      background-size: 300px;
      background-repeat: repeat;
      opacity: 0.08;
      position: absolute;
      inset: 0;
      z-index: 0;
    }
    .header-gradient {
      background: linear-gradient(to right, #92400e, #b45309);
    }
    .back-link {
      position: relative;
      z-index: 20;
      padding: 8px 12px;
      border-radius: 8px;
      transition: all 0.2s ease;
    }
    .back-link:hover {
      background: rgba(255, 255, 255, 0.1);
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
      animation: fadeIn 0.6s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }
    .menu-item {
      transition: all 0.2s ease;
      border-radius: 6px;
      padding: 8px 12px;
    }
    .menu-item:hover {
      background: rgba(254, 215, 170, 0.3);
    }
    .footer-gradient {
      background: linear-gradient(to right, #fed7aa, #fdba74);
    }
    .title-highlight {
      position: relative;
      display: inline-block;
    }
    .title-highlight:after {
      content: '';
      position: absolute;
      bottom: 2px;
      left: 0;
      width: 100%;
      height: 8px;
      background: rgba(251, 146, 60, 0.3);
      z-index: -1;
      border-radius: 4px;
    }
  </style>
</head>
<body class="min-h-screen flex flex-col">

  <!-- Header with Mobile Menu -->
  <header class="header-gradient text-white p-4 shadow-md flex items-center justify-between relative">
    
    <a href="katalog.php" class="back-link text-lg font-bold flex items-center gap-2 hover:text-amber-200 absolute left-4 top-1/2 -translate-y-1/2 z-10">
      <span class="text-2xl leading-none">←</span>
      <span class="hidden sm:inline">Kembali</span>
    </a>
    <h1 class="font-bold text-xl text-center w-full relative z-10">Rumah Adat Nusa Tenggara Barat</h1>
  </header>

  <!-- Mobile Menu -->
  <nav id="mobileMenu" aria-label="Menu utama" class="fixed top-0 right-0 h-full w-72 bg-white text-amber-900 shadow-xl pt-8 transform translate-x-full transition-transform duration-300 z-50">
    <button id="closeMenu" aria-label="Tutup menu" class="absolute top-4 right-6 text-3xl leading-none focus:outline-none hover:text-amber-600 transition-colors">&times;</button>
    <ul class="mt-16 space-y-3 px-6 font-medium">
      <li><a href="sasakwisata.php" class="menu-item block hover:text-amber-700">Sasak Wisata</a></li>
      <li><a href="katalog.php" class="menu-item block hover:text-amber-700">Katalog Budaya</a></li>
      <li><a href="umkm.php" class="menu-item block hover:text-amber-700">UMKM Lokal</a></li>
      <li><a href="suara.php" class="menu-item block hover:text-amber-700">Suara Lokal</a></li>
      <li><a href="rute.php" class="menu-item block hover:text-amber-700">Rekomendasi Wisata</a></li>
      <li><a href="booking.php" class="menu-item block hover:text-amber-700">Tour & Wisata</a></li>
      <li class="pt-6 mt-6 border-t border-amber-200">
        <span class="text-sm text-gray-500 block mb-2">Logged in as: <span class="font-medium text-gray-700"><?php echo htmlspecialchars($user['username']); ?></span></span>
        <a href="logout.php" class="menu-item block text-red-600 hover:text-red-800 font-medium">Logout</a>
      </li>
    </ul>
  </nav>

  <!-- Overlay -->
  <div id="overlay" class="fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm hidden z-40"></div>

  <!-- Intro -->
  <section class="max-w-4xl mx-auto px-4 py-8 text-center relative z-10">
    <h2 class="text-2xl font-bold mb-2">Rumah Tradisional <span class="text-amber-700">NTB</span></h2>
    <p class="text-sm text-gray-700">Rumah adat mencerminkan nilai budaya, filosofi, dan struktur sosial masyarakat di Nusa Tenggara Barat, khususnya Suku Sasak dan Sumbawa.</p>
  </section>

  <!-- Grid Rumah Adat -->
  <section class="max-w-6xl mx-auto grid sm:grid-cols-2 lg:grid-cols-3 gap-6 px-4 pb-24 relative z-10">
    <!-- Rumah Adat Bale -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform animate-fadeIn">
      <img src="https://akcdn.detik.net.id/community/media/visual/2018/09/24/82338968-8540-45a7-ad20-b68f7186315f_169.jpeg?w=620" alt="Rumah Adat Bale" class="h-48 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Rumah Adat Bale</h3>
        <p class="text-sm">Bale merupakan rumah tradisional suku Sasak yang digunakan sebagai tempat tinggal sehari-hari dan memiliki struktur panggung.</p>
        <details class="text-sm">
          <summary class="cursor-pointer text-amber-700 font-medium">Lihat lebih banyak</summary>
          <p class="mt-2">Rumah Bale memiliki lantai dari tanah liat yang dicampur kotoran kerbau untuk kekuatan. Atapnya terbuat dari alang-alang dengan struktur bambu. Rumah ini biasanya tidak memiliki jendela dan hanya satu pintu sebagai simbol kesederhanaan.</p>
        </details>
      </div>
    </article>

    <!-- Rumah Adat Bale Jajar -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform animate-fadeIn">
      <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQOIY_Ggycfwi3_u5lZ8BPLB172NcWjJqlb4w&s" alt="Rumah Bale Jajar" class="h-48 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Rumah Bale Jajar</h3>
        <p class="text-sm">Rumah Bale Jajar biasanya terdiri dari dua bale yang diletakkan sejajar dan digunakan oleh keluarga besar di Lombok.</p>
        <details class="text-sm">
          <summary class="cursor-pointer text-amber-700 font-medium">Lihat lebih banyak</summary>
          <p class="mt-2">Satu bale digunakan untuk tidur dan yang lainnya untuk menerima tamu atau kegiatan keluarga. Antara kedua bale biasanya terdapat halaman kecil yang digunakan untuk kegiatan sehari-hari seperti menjemur hasil panen.</p>
        </details>
      </div>
    </article>

    <!-- Rumah Bale Lumbung -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform animate-fadeIn">
      <img src="https://akcdn.detik.net.id/community/media/visual/2016/09/25/48aa35ee-b4d5-4101-9caa-38c97666ac12.jpg?w=5616" alt="Rumah Bale Lumbung" class="h-48 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Rumah Bale Lumbung</h3>
        <p class="text-sm">Bale Lumbung berfungsi untuk menyimpan padi dan hasil panen. Memiliki bentuk unik dengan atap seperti tanduk.</p>
        <details class="text-sm">
          <summary class="cursor-pointer text-amber-700 font-medium">Lihat lebih banyak</summary>
          <p class="mt-2">Bentuk atap yang melengkung seperti tanduk kerbau melambangkan kemakmuran. Lumbung ini dibangun tinggi untuk menghindari serangan hama dan memiliki sistem ventilasi alami untuk menjaga kualitas padi.</p>
        </details>
      </div>
    </article>

    <!-- Rumah Berugaq Sekenam -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform animate-fadeIn">
      <img src="https://akcdn.detik.net.id/community/media/visual/2022/11/14/berugaq.jpeg?w=640" alt="Rumah Adat Berugaq Sekenam" class="h-48 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Rumah Berugaq Sekenam</h3>
        <p class="text-sm">Berugaq Sekenam adalah bangunan terbuka tradisional dengan enam tiang utama, biasanya digunakan untuk musyawarah atau istirahat.</p>
        <details class="text-sm">
          <summary class="cursor-pointer text-amber-700 font-medium">Lihat lebih banyak</summary>
          <p class="mt-2">Enam tiang utama melambangkan rukun iman dalam Islam. Bangunan ini tanpa dinding sebagai simbol keterbukaan masyarakat Sasak. Biasanya terletak di depan rumah utama sebagai tempat menerima tamu.</p>
        </details>
      </div>
    </article>

    <!-- Istana Sumbawa -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform animate-fadeIn">
      <img src="https://awsimages.detik.net.id/community/media/visual/2020/11/12/rumah-dalam-loka_169.png?w=1200" alt="Istana Sumbawa" class="h-48 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Istana Sumbawa</h3>
        <p class="text-sm">Istana Bala Kuning merupakan istana kerajaan Sumbawa yang dibangun pada masa kesultanan, simbol kemegahan arsitektur lokal.</p>
        <details class="text-sm">
          <summary class="cursor-pointer text-amber-700 font-medium">Lihat lebih banyak</summary>
          <p class="mt-2">Dibangun dengan 99 tiang yang melambangkan 99 nama Allah dalam Islam. Istana ini menggabungkan arsitektur lokal dengan pengaruh Bugis dan Jawa. Kini berfungsi sebagai museum yang menyimpan berbagai artefak kerajaan.</p>
        </details>
      </div>
    </article>

    <!-- Rumah Bale Tani -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform animate-fadeIn">
      <img src="https://images.genpi.co/resize/1280x860-100/uploads/ntb/arsip/normal/2022/02/27/bale-tani-rumah-tradisi-masyarakat-sasak-foto-majelis-a-o89t.webp" alt="Rumah Bale Tani" class="h-48 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Rumah Bale Tani</h3>
        <p class="text-sm">Bale Tani adalah rumah tradisional masyarakat petani Sasak, terbuat dari bahan alami dengan konstruksi sederhana namun kokoh.</p>
        <details class="text-sm">
          <summary class="cursor-pointer text-amber-700 font-medium">Lihat lebih banyak</summary>
          <p class="mt-2">Memiliki tiga bagian utama: serambi untuk menerima tamu, ruang tengah untuk keluarga, dan dapur di belakang. Atap yang rendah melambangkan kerendahan hati, sementara struktur panggung melindungi dari banjir dan binatang.</p>
        </details>
      </div>
    </article>
  </section>

   <footer class="bg-amber-900 text-white text-sm text-center py-3">
    <p>&copy; 2025 BALEQARA | Dilestarikan dengan cinta budaya.</p>
  </footer>

  <script>
    // Mobile menu functionality
    const openMenuBtn = document.getElementById('openMenu');
    const closeMenuBtn = document.getElementById('closeMenu');
    const mobileMenu = document.getElementById('mobileMenu');
    const overlay = document.getElementById('overlay');

    function openMenu() {
      mobileMenu.classList.remove('translate-x-full');
      overlay.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
      mobileMenu.classList.add('translate-x-full');
      overlay.classList.add('hidden');
      document.body.style.overflow = '';
    }

    openMenuBtn.addEventListener('click', openMenu);
    closeMenuBtn.addEventListener('click', closeMenu);
    overlay.addEventListener('click', closeMenu);

    // Close menu when clicking on navigation links
    document.querySelectorAll('#mobileMenu a').forEach(link => {
      link.addEventListener('click', closeMenu);
    });

    // Add animation to cards when they come into view
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate-fadeIn');
        }
      });
    }, { threshold: 0.1 });

    document.querySelectorAll('.card').forEach(card => {
      card.style.opacity = '0';
      observer.observe(card);
    });
  </script>
</body>
</html>