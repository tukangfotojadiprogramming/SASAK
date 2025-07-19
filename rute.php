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
  <title>Rute Wisata Kustom – Sasak Heritage</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Leaflet -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
  <style>
    body { 
      font-family: 'Poppins', sans-serif; 
      background:#fffdf6; 
      color:#3b2f1f;
      padding: 0;
      margin: 0;
      position: relative;
      min-height: 100vh;
    }
    
    /* Header Styles */
    .gradient-header {
      background: linear-gradient(135deg, #78350f, #f59e0b);
      padding: 1rem 1.5rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: relative;
      z-index: 100;
    }
    
    /* Hamburger Menu Button */
    #openMenu {
      position: relative;
      z-index: 1001;
      cursor: pointer;
      background: transparent;
      border: none;
      font-size: 2rem;
      color: white;
      padding: 0.5rem;
      margin-left: auto;
    }
    
    /* Map Container */
    #map { 
      height: 70vh;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      position: relative;
      z-index: 1;
    }
    
    /* Batik Background Pattern */
    .batik-pattern {
      background-image: url('https://images.fineartamerica.com/images/artworkimages/mediumlarge/3/songket-tenun-geometrik-seamless-pattern-with-creative-bali-lombok-sasak-traditional-village-motif-from-indonesian-batik-julien.jpg');
      background-size: cover;
      background-repeat: repeat;
      opacity: 0.05;
      position: absolute;
      inset: 0;
      z-index: 0;
      pointer-events: none;
    }
    
    /* Mobile Menu Styles */
    #mobileMenu {
      position: fixed;
      top: 0;
      right: 0;
      width: 280px;
      height: 100%;
      background: white;
      box-shadow: -5px 0 15px rgba(0,0,0,0.1);
      z-index: 1000;
      transform: translateX(100%);
      transition: transform 0.3s ease;
      padding-top: 1rem;
    }
    
    #mobileMenu.show {
      transform: translateX(0);
    }
    
    /* Overlay Styles */
    #overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0,0,0,0.5);
      backdrop-filter: blur(5px);
      z-index: 999;
      display: none;
    }
    
    /* Main Content Layout */
    main {
      position: relative;
      z-index: 2;
      padding: 1.5rem;
      max-width: 1200px;
      margin: 0 auto;
      width: 100%;
    }
    
    /* Flex Container for Panels */
    .flex-container {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }
    
    @media (min-width: 1024px) {
      .flex-container {
        flex-direction: row;
      }
      .panel-left {
        width: 35%;
      }
      .panel-right {
        width: 65%;
      }
    }
    
    /* Panel Styles */
    .panel {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      padding: 1.5rem;
    }
    
    /* List Styles */
    #suggestion-list, #selected-list {
      max-height: 300px;
      overflow-y: auto;
      position: relative;
      z-index: 5;
    }
    
    /* Button Styles */
    #generate-btn {
      position: relative;
      z-index: 5;
      width: 100%;
      background: #78350f;
      color: white;
      border: none;
      padding: 0.8rem;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s;
    }
    
    #generate-btn:hover {
      background: #92400e;
    }
    
    #generate-btn:disabled {
      background: #d1d5db;
      cursor: not-allowed;
    }
    
    /* Footer Styles */
    footer {
      position: relative;
      z-index: 10;
      padding: 1rem;
      background: #78350f;
      color: white;
      text-align: center;
    }
    
    /* Utility Classes */
    .text-xl {
      font-size: 1.4rem;
    }
    .text-2xl {
      font-size: 1.7rem;
    }
    .text-sm {
      font-size: 0.95rem;
    }
    .rounded-lg {
      border-radius: 14px;
    }
  </style>
</head>
<body class="min-h-screen flex flex-col bg-amber-50 overflow-x-hidden">
  <!-- Header Section -->
  <header class="gradient-header text-white shadow-md flex items-center relative overflow-hidden">
    <div class="batik-pattern"></div>
    <div class="flex items-center z-10">
      <img src="assets/sasak.jpg" class="rounded-full mr-3" alt="Logo Sasak" width="70" height="70" />
      <span class="text-xl font-bold">BALEQARA</span>
    </div>
    
    <!-- Hamburger Menu Button -->
    <button id="openMenu" aria-label="Buka menu" class="z-10">
      ☰
    </button>
  </header>

  <!-- Mobile Menu -->
  <nav id="mobileMenu" aria-label="Menu utama">
    <button id="closeMenu" aria-label="Tutup menu" class="absolute top-3 right-4 text-3xl leading-none focus:outline-none">&times;</button>
    <ul class="mt-12 space-y-6 px-6 font-semibold">
      <li><a href="sasakwisata.php" class="block hover:text-amber-600 text-lg">Sasak Wisata</a></li>
      <li><a href="katalog.php" class="block hover:text-amber-600 text-lg">Katalog Budaya</a></li>
      <li><a href="umkm.php" class="block hover:text-amber-600 text-lg">UMKM Lokal</a></li>
      <li><a href="suara.php" class="block hover:text-amber-600 text-lg">Suara Lokal</a></li>
      <li><a href="rute.php" class="block hover:text-amber-600 text-lg">Rekomendasi Wisata</a></li>
      <li><a href="pesan.php" class="block hover:text-amber-600 text-lg">Tour & Wisata</a></li>
      <li class="pt-4 border-t">
        <span class="text-gray-500 text-lg">Logged in as: <?php echo htmlspecialchars($user['username']); ?></span>
      </li>
      <li>
        <a href="logout.php" class="block text-red-600 hover:text-red-800 text-lg">Logout</a>
      </li>
    </ul>
  </nav>

  <!-- Overlay -->
  <div id="overlay" class="fixed inset-0 z-40"></div>

  <!-- Main Content -->
  <main class="flex-grow">
    <div class="container mx-auto px-4 py-6 space-y-6">
      <section class="text-center max-w-3xl mx-auto space-y-3">
        <h2 class="text-2xl font-bold">Bangun Rencana Perjalanan Anda</h2>
        <p class="text-sm text-gray-700">Setiap destinasi punya kisah. Susun rute perjalanan budaya Anda sendiri, dari bukit ke desa, dari laut ke tenun. Pilih tujuan, dan temukan jalannya.</p>
      </section>

      <div class="flex-container">
        <!-- Left Panel -->
        <div class="panel panel-left">
          <div class="space-y-4">
            <div class="panel space-y-4">
              <h3 class="font-semibold text-lg">Destinasi Impian ke Mana?</h3>
              <input id="search-input" type="text" placeholder="Yuk Cari Tempat Impianmu..." class="w-full border border-amber-300 rounded px-4 py-3 focus:outline-none focus:ring-2 focus:ring-amber-500" />
              <ul id="suggestion-list" class="space-y-2"></ul>
            </div>

            <div class="panel">
              <h3 class="font-semibold text-lg mb-4">Destinasi Terpilih</h3>
              <ul id="selected-list" class="space-y-3 text-base"></ul>
              <button id="generate-btn" class="mt-5" disabled>Buat Rute</button>
            </div>
          </div>
        </div>

        <!-- Right Panel (Map) -->
        <div class="panel panel-right">
          <div id="map"></div>
          <div class="mt-4 text-center">
            <p class="text-sm text-gray-600">Klik pada peta untuk menentukan titik awal perjalanan</p>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer>
    <p>&copy; 2025 BALEQARA | Dilestarikan dengan cinta budaya.</p>
  </footer>

  <script>
  // ===== DATA DESTINASI STANDAR =====
  const masterDestinations = [
    { name: "Bukit Merese", lat: -8.8825, lng: 116.3492 },
    { name: "Pantai Seger", lat: -8.8839, lng: 116.2794 },
    { name: "Desa Sade", lat: -8.8493, lng: 116.2894 },
    { name: "Sentra Tenun Sukarara", lat: -8.6927, lng: 116.2681 },
    { name: "Gunung Rinjani", lat: -8.4110, lng: 116.4583 },
    { name: "Air Terjun Benang Stokel", lat: -8.5669, lng: 116.3649 }
  ];

  // ===== ELEMENT DOM =====
  const searchInput = document.getElementById('search-input');
  const suggEl      = document.getElementById('suggestion-list');
  const selectEl    = document.getElementById('selected-list');
  const genBtn      = document.getElementById('generate-btn');

  // ===== STATE =====
  const selected = [];
  let startMarker;   // titik awal (klik peta / geolocation)
  let routeLine;     // polyline rute

  // ===== LEAFLET MAP =====
  const map = L.map('map').setView([-8.8, 116.3], 9);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution:'&copy; OpenStreetMap' }).addTo(map);

  // ===== UTILS =====
  function haversine(lat1, lon1, lat2, lon2){
    const R=6371, toRad=d=>d*Math.PI/180;
    const dLat=toRad(lat2-lat1), dLon=toRad(lon2-lon1);
    const a=Math.sin(dLat/2)**2 + Math.cos(toRad(lat1))*Math.cos(toRad(lat2))*Math.sin(dLon/2)**2;
    return 2*R*Math.asin(Math.sqrt(a));
  }

  // ===== SEARCH HANDLER =====
  searchInput.addEventListener('input',()=>{
    const q=searchInput.value.trim().toLowerCase();
    suggEl.innerHTML='';
    if(!q) return;
    const filtered=masterDestinations.filter(d=>d.name.toLowerCase().includes(q) && !selected.find(s=>s.name===d.name));
    filtered.forEach(dest=>{
      const li=document.createElement('li');
      li.className="flex justify-between items-center bg-amber-50 hover:bg-amber-100 px-4 py-3 rounded-lg cursor-pointer text-base";
      li.textContent=dest.name;
      li.addEventListener('click',()=>{
        addDestination(dest);
        searchInput.value='';
        suggEl.innerHTML='';
      });
      suggEl.appendChild(li);
    });
  });

  // ===== ADD DESTINATION =====
  function addDestination(dest){
    if(selected.find(d=>d.name===dest.name)) return; // duplikat
    selected.push(dest);
    renderSelected();
    genBtn.disabled=false;
    // tambahkan marker
    const m=L.marker([dest.lat,dest.lng]).addTo(map).bindPopup(`<strong>${dest.name}</strong>`);
    dest._marker=m;
  }

  // ===== REMOVE DESTINATION =====
  function removeDestination(name){
    const idx=selected.findIndex(d=>d.name===name);
    if(idx>-1){
      map.removeLayer(selected[idx]._marker);
      selected.splice(idx,1);
      renderSelected();
      genBtn.disabled=selected.length===0;
      if(routeLine) map.removeLayer(routeLine);
    }
  }

  // ===== RENDER SELECTED LIST =====
  function renderSelected(){
    selectEl.innerHTML='';
    selected.forEach((d,i)=>{
      const li=document.createElement('li');
      li.className="flex justify-between items-center bg-amber-50 px-4 py-3 rounded-lg";
      li.innerHTML=`<span class="font-medium">${i+1}. ${d.name}</span><button class="rm-btn text-red-600 hover:text-red-800 font-bold">×</button>`;
      li.querySelector('button').addEventListener('click',()=>removeDestination(d.name));
      selectEl.appendChild(li);
    });
  }

  // ===== GENERATE ROUTE =====
  function generateRoute(){
    if(!startMarker){ alert('Silakan tentukan titik awal di peta.'); return; }
    const { lat:startLat, lng:startLng } = startMarker.getLatLng();
    // urutkan destinasi berdasarkan jarak incremental sederhana (Nearest Neighbour)
    const route=[...selected];
    const ordered=[];
    let currLat=startLat, currLng=startLng;
    while(route.length){
      let minIdx=0, minDist=Infinity;
      route.forEach((d,i)=>{
        const dist=haversine(currLat,currLng,d.lat,d.lng);
        if(dist<minDist){minDist=dist;minIdx=i;}
      });
      const next=route.splice(minIdx,1)[0];
      ordered.push(next);
      currLat=next.lat; currLng=next.lng;
    }
    // buat polyline
    if(routeLine) map.removeLayer(routeLine);
    const latlngs=[[startLat,startLng], ...ordered.map(d=>[d.lat,d.lng])];
    routeLine=L.polyline(latlngs,{weight:5,color:'#eab308'}).addTo(map);
    map.fitBounds(latlngs,{padding:[40,40]});
  }

  genBtn.addEventListener('click',generateRoute);

  // ===== CLICK MAP UNTUK START POINT =====
  map.on('click',e=>{
    const {lat,lng}=e.latlng;
    if(startMarker) startMarker.setLatLng([lat,lng]);
    else startMarker=L.marker([lat,lng],{draggable:true}).addTo(map).bindPopup('<strong>Lokasi Anda</strong>').openPopup();
  });

  // ===== AUTO GEOLOCATION =====
  if(navigator.geolocation){
    navigator.geolocation.getCurrentPosition(pos=>{
      const {latitude,longitude}=pos.coords;
      map.setView([latitude,longitude],11);
      map.fire('click',{latlng:{lat:latitude,lng:longitude}});
    });
  }

  // ===== MOBILE MENU FUNCTIONALITY =====
  function openMenu() {
    document.getElementById('mobileMenu').classList.add('show');
    document.getElementById('overlay').style.display = 'block';
    document.body.style.overflow = 'hidden';
  }

  function closeMenu() {
    document.getElementById('mobileMenu').classList.remove('show');
    document.getElementById('overlay').style.display = 'none';
    document.body.style.overflow = '';
  }

  // Event Listeners for Menu
  document.getElementById('openMenu').addEventListener('click', openMenu);
  document.getElementById('closeMenu').addEventListener('click', closeMenu);
  document.getElementById('overlay').addEventListener('click', closeMenu);

  // Close menu when clicking on navigation links
  document.querySelectorAll('#mobileMenu a').forEach(link => {
    link.addEventListener('click', closeMenu);
  });
  </script>
</body>
</html>