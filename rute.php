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
  <title>Rekomendasi Wisata - BALEQARA</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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
    .route-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    #map {
      height: 500px;
    }
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
<body class="bg-amber-50">
  <!-- Header -->
  <header class="gradient-header text-white p-4 shadow-md flex justify-between items-center relative overflow-hidden">
    <div class="batik-pattern"></div>
    <div class="flex items-center z-10">
      <img src="https://placehold.co/60x60" class="rounded-full mr-3" alt="Logo Sasak" />
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
  <main class="max-w-7xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-amber-900 mb-2">Rekomendasi Rute Wisata Lombok</h1>
    <p class="text-gray-700 mb-6">Temukan rute terbaik untuk menjelajahi keindahan Lombok berdasarkan preferensi Anda.</p>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Durasi Perjalanan</label>
          <select id="duration" class="w-full p-2 border rounded">
            <option value="all">Semua Durasi</option>
            <option value="1">1 Hari</option>
            <option value="2">2 Hari</option>
            <option value="3">3 Hari</option>
            <option value="4">4+ Hari</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Wisata</label>
          <select id="type" class="w-full p-2 border rounded">
            <option value="all">Semua Tipe</option>
            <option value="pantai">Pantai</option>
            <option value="alam">Alam</option>
            <option value="budaya">Budaya</option>
            <option value="petualangan">Petualangan</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat Aktivitas</label>
          <select id="activity" class="w-full p-2 border rounded">
            <option value="all">Semua Tingkat</option>
            <option value="ringan">Ringan</option>
            <option value="sedang">Sedang</option>
            <option value="berat">Berat</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Routes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
      <!-- Route 1 -->
      <div class="route-card bg-white rounded-lg shadow overflow-hidden transition-all duration-200">
        <div class="relative h-48">
          <img src="https://travelspromo.com/wp-content/uploads/2022/02/Keindahan-pantai-seger-lombok-berlatar-bukit.jpeg" 
               alt="Rute Pantai Selatan" class="w-full h-full object-cover">
          <span class="absolute top-2 left-2 bg-amber-600 text-white text-xs px-2 py-1 rounded">1 Hari</span>
        </div>
        <div class="p-4">
          <h3 class="font-bold text-lg text-amber-900 mb-2">Jelajah Pantai Selatan</h3>
          <p class="text-sm text-gray-600 mb-3">Pantai Seger, Pantai Kuta, Tanjung Aan</p>
          <div class="flex justify-between items-center">
            <span class="text-xs bg-amber-100 text-amber-800 px-2 py-1 rounded">Pantai</span>
            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Ringan</span>
          </div>
          <button onclick="showRoute('pantai-selatan')" class="w-full mt-3 bg-amber-600 hover:bg-amber-700 text-white py-2 rounded text-sm">
            Lihat Rute
          </button>
        </div>
      </div>

      <!-- Route 2 -->
      <div class="route-card bg-white rounded-lg shadow overflow-hidden transition-all duration-200">
        <div class="relative h-48">
          <img src="https://media.suara.com/pictures/970x544/2025/06/26/40338-ilustrasi-gunung-rinjani.jpg" 
               alt="Rute Rinjani" class="w-full h-full object-cover">
          <span class="absolute top-2 left-2 bg-amber-600 text-white text-xs px-2 py-1 rounded">3 Hari</span>
        </div>
        <div class="p-4">
          <h3 class="font-bold text-lg text-amber-900 mb-2">Pendakian Rinjani</h3>
          <p class="text-sm text-gray-600 mb-3">Senaru, Pos 2, Pos 3, Danau Segara Anak</p>
          <div class="flex justify-between items-center">
            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Alam</span>
            <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Berat</span>
          </div>
          <button onclick="showRoute('rinjani')" class="w-full mt-3 bg-amber-600 hover:bg-amber-700 text-white py-2 rounded text-sm">
            Lihat Rute
          </button>
        </div>
      </div>

      <!-- Route 3 -->
      <div class="route-card bg-white rounded-lg shadow overflow-hidden transition-all duration-200">
        <div class="relative h-48">
          <img src="https://cdn.idntimes.com/content-images/community/2020/01/19424765-245953129236714-810761388782780416-n-6fa4cbbd82db936bbc84478e9a40e3fc.jpg" 
               alt="Rute Budaya" class="w-full h-full object-cover">
          <span class="absolute top-2 left-2 bg-amber-600 text-white text-xs px-2 py-1 rounded">2 Hari</span>
        </div>
        <div class="p-4">
          <h3 class="font-bold text-lg text-amber-900 mb-2">Tur Budaya Sasak</h3>
          <p class="text-sm text-gray-600 mb-3">Desa Sade, Sukarara, Pringgasela</p>
          <div class="flex justify-between items-center">
            <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded">Budaya</span>
            <span class="text-xs bg-amber-100 text-amber-800 px-2 py-1 rounded">Sedang</span>
          </div>
          <button onclick="showRoute('budaya-sasak')" class="w-full mt-3 bg-amber-600 hover:bg-amber-700 text-white py-2 rounded text-sm">
            Lihat Rute
          </button>
        </div>
      </div>
    </div>

    <!-- Map Section -->
    <div class="bg-white rounded-lg shadow p-4 mb-8">
      <h2 class="text-xl font-bold text-amber-900 mb-4">Peta Rute Wisata</h2>
      <div id="map" class="rounded-lg"></div>
    </div>

    <!-- Route Details Modal -->
    <div id="routeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
      <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-6">
          <div class="flex justify-between items-start mb-4">
            <h3 id="routeTitle" class="text-xl font-bold text-amber-900"></h3>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
          </div>
          <div id="routeContent" class="space-y-4"></div>
          <div class="mt-6">
            <button onclick="closeModal()" class="w-full bg-amber-600 hover:bg-amber-700 text-white py-2 rounded">
              Tutup
            </button>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-amber-900 text-white text-sm text-center py-3">
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

    // Initialize map
    const map = L.map('map').setView([-8.65, 116.35], 9);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Sample route data
    const routes = {
      'pantai-selatan': {
        title: 'Jelajah Pantai Selatan Lombok',
        description: 'Rute wisata pantai di Lombok bagian selatan yang menawarkan pemandangan indah dan aktivitas air yang menyenangkan.',
        duration: '1 Hari',
        type: 'Pantai',
        activity: 'Ringan',
        itinerary: [
          '08:00 - Berangkat dari Mataram',
          '09:30 - Tiba di Pantai Seger, eksplorasi pantai',
          '12:00 - Makan siang di warung lokal',
          '13:30 - Lanjut ke Pantai Kuta',
          '15:00 - Berenang dan bersantai di Pantai Kuta',
          '17:00 - Sunset di Tanjung Aan',
          '18:30 - Kembali ke Mataram'
        ],
        waypoints: [
          [-8.8329, 116.3204], // Pantai Seger
          [-8.8988, 116.3001], // Pantai Kuta
          [-8.9115, 116.2923]  // Tanjung Aan
        ]
      },
      'rinjani': {
        title: 'Pendakian Gunung Rinjani',
        description: 'Rute pendakian ke Gunung Rinjani melalui jalur Senaru dengan pemandangan spektakuler dan pengalaman alam yang menantang.',
        duration: '3 Hari',
        type: 'Alam',
        activity: 'Berat',
        itinerary: [
          'Hari 1:',
          '07:00 - Pendaftaran di pos pendakian Senaru',
          '08:00 - Mulai pendakian ke Pos 1',
          '12:00 - Istirahat dan makan siang di Pos 2',
          '15:00 - Tiba di Pos 3, mendirikan tenda',
          '18:00 - Makan malam dan istirahat',
          '',
          'Hari 2:',
          '02:00 - Pendakian menuju puncak',
          '06:00 - Tiba di puncak, menikmati sunrise',
          '08:00 - Turun ke Danau Segara Anak',
          '12:00 - Istirahat dan makan siang di danau',
          '15:00 - Eksplorasi sekitar danau',
          '18:00 - Makan malam dan istirahat',
          '',
          'Hari 3:',
          '07:00 - Turun kembali ke Senaru',
          '12:00 - Tiba di basecamp, perjalanan selesai'
        ],
        waypoints: [
          [-8.3235, 116.3997], // Senaru
          [-8.3100, 116.4100], // Pos 2
          [-8.3000, 116.4200], // Pos 3
          [-8.2900, 116.4300], // Puncak
          [-8.3100, 116.4400]  // Segara Anak
        ]
      },
      'budaya-sasak': {
        title: 'Tur Budaya Sasak',
        description: 'Perjalanan menyusuri warisan budaya suku Sasak di Lombok, mulai dari desa adat hingga sentra kerajinan tradisional.',
        duration: '2 Hari',
        type: 'Budaya',
        activity: 'Sedang',
        itinerary: [
          'Hari 1:',
          '08:00 - Berangkat dari Mataram',
          '09:30 - Kunjungan ke Desa Sade, melihat rumah adat Sasak',
          '12:00 - Makan siang dengan masakan tradisional',
          '14:00 - Ke Sukarara melihat proses tenun tradisional',
          '16:00 - Check-in penginapan lokal',
          '19:00 - Makan malam dengan pertunjukan musik tradisional',
          '',
          'Hari 2:',
          '08:00 - Sarapan pagi',
          '09:30 - Ke Pringgasela melihat pembuatan songket',
          '12:00 - Makan siang',
          '14:00 - Kunjungan ke pasar tradisional Sukarara',
          '16:00 - Kembali ke Mataram'
        ],
        waypoints: [
          [-8.5830, 116.1160], // Desa Sade
          [-8.8329, 116.3204],  // Sukarara
          [-8.5500, 116.5000]   // Pringgasela
        ]
      }
    };

    // Show route details
    function showRoute(routeId) {
      const route = routes[routeId];
      if (!route) return;

      // Update modal content
      document.getElementById('routeTitle').textContent = route.title;
      const content = document.getElementById('routeContent');
      
      // Clear previous content
      content.innerHTML = '';
      
      // Add description
      const desc = document.createElement('p');
      desc.className = 'text-gray-700';
      desc.textContent = route.description;
      content.appendChild(desc);
      
      // Add details
      const details = document.createElement('div');
      details.className = 'grid grid-cols-3 gap-2 mb-4';
      details.innerHTML = `
        <div class="bg-amber-50 p-2 rounded">
          <p class="text-xs text-gray-500">Durasi</p>
          <p class="font-medium">${route.duration}</p>
        </div>
        <div class="bg-amber-50 p-2 rounded">
          <p class="text-xs text-gray-500">Tipe</p>
          <p class="font-medium">${route.type}</p>
        </div>
        <div class="bg-amber-50 p-2 rounded">
          <p class="text-xs text-gray-500">Aktivitas</p>
          <p class="font-medium">${route.activity}</p>
        </div>
      `;
      content.appendChild(details);
      
      // Add itinerary
      const itineraryTitle = document.createElement('h4');
      itineraryTitle.className = 'font-bold text-amber-900 mt-4';
      itineraryTitle.textContent = 'Rincian Perjalanan:';
      content.appendChild(itineraryTitle);
      
      const itineraryList = document.createElement('div');
      itineraryList.className = 'space-y-2';
      route.itinerary.forEach(item => {
        const p = document.createElement('p');
        p.className = item ? 'text-sm' : 'h-3';
        p.textContent = item;
        itineraryList.appendChild(p);
      });
      content.appendChild(itineraryList);
      
      // Show modal
      document.getElementById('routeModal').classList.remove('hidden');
      
      // Update map with route
      updateMapRoute(route.waypoints);
    }

    // Close modal
    function closeModal() {
      document.getElementById('routeModal').classList.add('hidden');
      // Reset map view
      map.setView([-8.65, 116.35], 9);
    }

    // Update map with route waypoints
    function updateMapRoute(waypoints) {
      // Clear existing markers and layers
      map.eachLayer(layer => {
        if (layer instanceof L.Marker || layer instanceof L.Polyline) {
          map.removeLayer(layer);
        }
      });
      
      // Add markers for each waypoint
      waypoints.forEach((wp, i) => {
        L.marker([wp[0], wp[1]]).addTo(map)
          .bindPopup(`Titik ${i + 1}`)
          .openPopup();
      });
      
      // Add polyline connecting waypoints
      if (waypoints.length > 1) {
        L.polyline(waypoints, {color: '#f59e0b'}).addTo(map);
      }
      
      // Fit map to bounds of all waypoints
      const bounds = L.latLngBounds(waypoints);
      map.fitBounds(bounds, {padding: [50, 50]});
    }

    // Filter routes
    document.getElementById('duration').addEventListener('change', filterRoutes);
    document.getElementById('type').addEventListener('change', filterRoutes);
    document.getElementById('activity').addEventListener('change', filterRoutes);

    function filterRoutes() {
      const duration = document.getElementById('duration').value;
      const type = document.getElementById('type').value;
      const activity = document.getElementById('activity').value;
      
      // In a real implementation, you would filter the displayed routes here
      console.log(`Filtering by: Duration=${duration}, Type=${type}, Activity=${activity}`);
      // For now, we'll just show all routes
    }
  </script>
</body>
</html>