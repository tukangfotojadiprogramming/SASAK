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

// Database query for cultural data
$cultural_data = [];
$query = $conn->query("SELECT * FROM cultural_items");
while ($row = $query->fetch_assoc()) {
    $cultural_data[$row['slug']] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Katalog Budaya Sasak</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
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
  <header class="header-gradient text-white p-4 shadow-lg relative">
    <div class="batik-pattern"></div>
    <div class="container mx-auto flex items-center justify-between relative z-10">
      <a href="index.php" class="back-link text-lg font-semibold flex items-center gap-2 hover:text-amber-100">
        <span class="text-2xl leading-none">←</span>
        <span class="hidden sm:inline">Kembali</span>
      </a>
      <h1 class="font-bold text-2xl text-center flex-grow px-4 tracking-tight">Katalog Budaya Sasak</h1>
      <button id="openMenu" aria-label="Buka menu" class="text-3xl cursor-pointer focus:outline-none hover:text-amber-200 transition-colors">
        ☰
      </button>
    </div>
  </header>

  <!-- Mobile Menu -->
  <nav id="mobileMenu" aria-label="Menu utama" class="fixed top-0 right-0 h-full w-72 bg-white text-amber-900 shadow-xl pt-8 transform translate-x-full transition-transform duration-300 z-50">
    <button id="closeMenu" aria-label="Tutup menu" class="absolute top-4 right-6 text-3xl leading-none focus:outline-none hover:text-amber-600 transition-colors">&times;</button>
    <ul class="mt-16 space-y-3 px-6 font-medium">
      <li><a href="sasakwisata.php" class="menu-item block hover:text-amber-700">Sasak Wisata</a></li>
      <li><a href="katalog.php" class="menu-item block text-amber-700 font-semibold bg-amber-100">Katalog Budaya</a></li>
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

  <!-- Konten -->
  <main class="flex-grow max-w-6xl mx-auto px-4 py-12 relative z-10">
    <h2 class="text-3xl font-bold text-center mb-12">Eksplorasi Warisan Budaya <span class="text-amber-700 title-highlight">Sasak</span></h2>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <?php
      // Data lengkap budaya Sasak
      $cultural_items = [
          [
              'slug' => 'tenun',
              'title' => 'Tenun Ikat & Maknanya',
              'items' => [
                  'Subahnale: Dzikir syukur perempuan Sasak.',
                  'Ragi Genep: Keseimbangan dunia & spiritual.',
                  'Patola: Perlindungan dan kebangsawanan.'
              ]
          ],
          [
              'slug' => 'rumah-adat',
              'title' => 'Struktur Rumah Adat',
              'items' => [
                  'Bale Tani: Rumah inti keluarga petani.',
                  'Bale Kodong: Hunian khusus lansia.',
                  'Atap Alang-Alang: Simbol kesejukan & kesucian.'
              ]
          ],
          [
              'slug' => 'alat-musik',
              'title' => 'Alat Musik & Tarian',
              'items' => [
                  'Gendang Beleq: Irama semangat upacara.',
                  'Sasando Lombok: Petikan melodi lembut.',
                  'Peresean: Duel ritual keberanian.'
              ]
          ],
          [
              'slug' => 'bahasa',
              'title' => 'Bahasa & Ungkapan',
              'items' => [
                  'Kance Tuan: Sahabat karib.',
                  'Saq Inaq, Saq Amaq: Hormati orang tua.',
                  'Lombok: Lurus, jujur, bersih hati.'
              ]
          ],
          [
              'slug' => 'pakaian',
              'title' => 'Pakaian Adat Sasak',
              'items' => [
                  'Pejambon: Busana bangsawan + keris.',
                  'Lambung: Blus hitam keteguhan wanita.',
                  'Dodot: Kain ritual & pernikahan.'
              ]
          ],
          [
              'slug' => 'makanan',
              'title' => 'Makanan Khas Lombok',
              'items' => [
                  'Ayam Taliwang: Ayam bakar pedas khas.',
                  'Plecing Kangkung: Sayur pedas segar.',
                  'Sate Bulayak: Sate dengan lontong khas.'
              ]
          ]
      ];

      // Jika ada data dari database, gunakan itu
      if (!empty($cultural_data)) {
          foreach ($cultural_data as $slug => $item) {
              echo '
              <a href="detail-budaya.php?slug='.$slug.'" class="group no-underline">
                  <article class="card bg-white p-6 hover:shadow-xl">
                      <h3 class="text-xl font-bold mb-3 text-amber-900 group-hover:text-amber-700 transition-colors">'.$item['title'].'</h3>
                      <ul class="list-disc pl-5 text-gray-700 space-y-2">';
                          foreach (json_decode($item['items']) as $point) {
                              echo '<li class="leading-relaxed">'.$point.'</li>';
                          }
                      echo '</ul>
                  </article>
              </a>';
          }
      } else {
          // Fallback ke data sampel jika tidak ada database
          foreach ($cultural_items as $item) {
              echo '
              <a href="detail-budaya.php?slug='.$item['slug'].'" class="group no-underline">
                  <article class="card bg-white p-6 hover:shadow-xl">
                      <h3 class="text-xl font-bold mb-3 text-amber-900 group-hover:text-amber-700 transition-colors">'.$item['title'].'</h3>
                      <ul class="list-disc pl-5 text-gray-700 space-y-2">';
                          foreach ($item['items'] as $point) {
                              echo '<li class="leading-relaxed">'.htmlspecialchars($point).'</li>';
                          }
                      echo '</ul>
                  </article>
              </a>';
          }
      }
      ?>
    </div>
  </main>

  <footer class="footer-gradient text-center py-6 text-sm text-amber-900 relative z-10 shadow-inner">
    <div class="max-w-6xl mx-auto px-4">
      © 2025 Sasak Heritage Explorer. Dilestarikan dengan cinta budaya.
    </div>
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