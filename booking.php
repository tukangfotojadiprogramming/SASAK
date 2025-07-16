<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Database connection
require_once 'db.php';

// Get user data
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Get destination name from URL parameter
$destination_name = isset($_GET['destination']) ? $_GET['destination'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tour & Wisata – Pemesanan Travel Bus & Hotel</title>

  <!-- Poppins & Tailwind -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    body{font-family:'Poppins',sans-serif}
    .tab-active{background:#78350f;color:#fff}
  </style>
</head>
<body class="bg-amber-100 text-gray-800 min-h-screen flex flex-col">

<!-- ===== NAVBAR ===== -->
<nav class="bg-amber-900 text-white p-4 flex items-center justify-between sticky top-0 z-10 shadow">
  <span class="font-semibold text-lg tracking-wide">BALEQARA</span>
  <a href="sasakwisata.php" class="hover:text-amber-200">🏠 Beranda</a>
</nav>

<!-- ===== HEADER ===== -->
<header class="text-center py-8 px-4 bg-amber-100">
  <h1 class="text-3xl font-bold text-amber-900 mb-2">Pesan Travel Bus & Hotel</h1>
  <p class="max-w-xl mx-auto text-gray-700">Atur perjalanan Anda ke <?php echo htmlspecialchars($destination_name); ?> – pesan kursi bus pariwisata serta hotel pilihan dalam satu halaman.</p>
</header>

<!-- ===== TAB BAR ===== -->
<section class="max-w-5xl mx-auto px-4 py-4">
  <div class="flex gap-4 justify-center mb-6">
    <button id="tabBus"   class="tab-btn tab-active px-4 py-2 rounded">Travel Bus</button>
    <button id="tabHotel" class="tab-btn px-4 py-2 rounded">Hotel</button>
  </div>

  <!-- ========== FORM TRAVEL BUS ========== -->
  <form id="busForm" class="space-y-4 bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold text-amber-900 mb-4">Booking Travel Bus</h2>
    <input type="hidden" name="destination" value="<?php echo htmlspecialchars($destination_name); ?>">

    <div class="grid md:grid-cols-2 gap-4">
      <div>
        <label class="block mb-1 font-medium">Kota Keberangkatan</label>
        <input required name="start" class="w-full border p-2 rounded" placeholder="Contoh: Mataram" />
      </div>
      <div>
        <label class="block mb-1 font-medium">Kota Tujuan</label>
        <input required name="dest" class="w-full border p-2 rounded" value="<?php echo htmlspecialchars($destination_name); ?>" readonly />
      </div>
      <div>
        <label class="block mb-1 font-medium">Tanggal Berangkat</label>
        <input required name="date" type="date" class="w-full border p-2 rounded" />
      </div>
      <div>
        <label class="block mb-1 font-medium">Jumlah Penumpang</label>
        <input required name="pax" type="number" min="1" value="1" class="w-full border p-2 rounded" />
      </div>
    </div>

    <button class="bg-amber-900 text-white px-6 py-2 rounded hover:brightness-110">Cari Bus</button>
  </form>

  <!-- ========== FORM HOTEL ========== -->
  <form id="hotelForm" class="space-y-4 bg-white rounded-lg shadow p-6 hidden">
    <h2 class="text-xl font-semibold text-amber-900 mb-4">Booking Hotel</h2>
    <input type="hidden" name="destination" value="<?php echo htmlspecialchars($destination_name); ?>">

    <div class="grid md:grid-cols-2 gap-4">
      <div>
        <label class="block mb-1 font-medium">Lokasi Hotel</label>
        <input required name="city" class="w-full border p-2 rounded" value="<?php echo htmlspecialchars($destination_name); ?>" />
      </div>
      <div>
        <label class="block mb-1 font-medium">Check‑in</label>
        <input required name="checkin" type="date" class="w-full border p-2 rounded" />
      </div>
      <div>
        <label class="block mb-1 font-medium">Check‑out</label>
        <input required name="checkout" type="date" class="w-full border p-2 rounded" />
      </div>
      <div>
        <label class="block mb-1 font-medium">Tamu (Dewasa)</label>
        <input required name="guests" type="number" min="1" value="2" class="w-full border p-2 rounded" />
      </div>
    </div>

    <button class="bg-amber-900 text-white px-6 py-2 rounded hover:brightness-110">Cari Hotel</button>
  </form>

  <!-- ========== HASIL PENCARIAN ========== -->
  <section id="results" class="mt-8"></section>
</section>

<!-- ===== FOOTER ===== -->
<footer class="mt-auto bg-amber-900 text-white text-sm text-center py-3">
  <p>&copy; 2025 BALEQARA | Tim Pengembang</p>
</footer>

<script>
/******************  TAB HANDLER  ******************/
const tabBus   = document.getElementById('tabBus');
const tabHotel = document.getElementById('tabHotel');
const busForm  = document.getElementById('busForm');
const hotelForm= document.getElementById('hotelForm');
const results  = document.getElementById('results');

function switchTab(to){
  if(to==='bus'){
    tabBus.classList.add('tab-active');
    tabHotel.classList.remove('tab-active');
    busForm.classList.remove('hidden');
    hotelForm.classList.add('hidden');
  }else{
    tabHotel.classList.add('tab-active');
    tabBus.classList.remove('tab-active');
    hotelForm.classList.remove('hidden');
    busForm.classList.add('hidden');
  }
  results.innerHTML='';
}

tabBus.onclick = ()=>switchTab('bus');
tabHotel.onclick= ()=>switchTab('hotel');

/******************  DATA SAMPEL  ******************/
const buses=[
  {id:1, name:'Lombok Tour Bus A', seats:30, price:75000},
  {id:2, name:'Mandala Travel Coach', seats:40, price:90000},
  {id:3, name:'Sasak Heritage Bus', seats:25, price:85000}
];

const hotels=[
  {id:1, name:'Senggigi Beach Resort',  stars:4, price:650000, city:'senggigi'},
  {id:2, name:'Kuta Mandalika Inn',     stars:3, price:420000, city:'kuta'},
  {id:3, name:'Rinjani View Hotel',      stars:5, price:880000, city:'senaru'},
  {id:4, name:'Mataram City Hotel',      stars:3, price:380000, city:'mataram'}
];

/******************  RENDERING UTIL  ******************/
function starIcons(n){return '★'.repeat(n)+'☆'.repeat(5-n)}

/******************  SUBMIT HANDLERS  ******************/
busForm.onsubmit = e => {
  e.preventDefault();
  const f=new FormData(busForm);
  const pax=+f.get('pax');
  const list=buses.filter(b=>b.seats>=pax);
  renderBuses(list,f);
};

hotelForm.onsubmit = e => {
  e.preventDefault();
  const f=new FormData(hotelForm);
  const city=f.get('city').toLowerCase();
  const list=hotels.filter(h=>h.city.includes(city));
  renderHotels(list,f);
};

/******************  RENDER RESULTS  ******************/
function renderBuses(arr,form){
  if(!arr.length){results.innerHTML='<p class="text-center text-gray-600">Tidak ada bus tersedia.</p>';return;}
  let html='<h3 class="text-lg font-semibold mb-3">Bus Tersedia</h3><div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">';
  arr.forEach(b=>{
    const subtotal=b.price*+form.get('pax');
    html+=`<div class="bg-white rounded-lg shadow p-4 space-y-2">
      <h4 class="font-medium">${b.name}</h4>
      <p>${b.seats} kursi • Rp ${b.price.toLocaleString('id-ID')}/orang</p>
      <p class="font-semibold text-amber-900">Total: Rp ${subtotal.toLocaleString('id-ID')}</p>
      <button class="w-full bg-amber-900 hover:brightness-110 text-white py-1 rounded" onclick="bookTravel(${b.id}, 'bus')">Pesan</button>
    </div>`;
  });
  html+='</div>';
  results.innerHTML=html;
}

function renderHotels(arr,form){
  if(!arr.length){results.innerHTML='<p class="text-center text-gray-600">Tidak ada hotel ditemukan.</p>';return;}
  let html='<h3 class="text-lg font-semibold mb-3">Hotel Tersedia</h3><div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">';
  arr.forEach(h=>{
    const nights=( (new Date(form.get('checkout')) - new Date(form.get('checkin'))) /86400000 ) || 1;
    const subtotal=h.price*nights;
    html+=`<div class="bg-white rounded-lg shadow p-4 space-y-2">
      <h4 class="font-medium">${h.name}</h4>
      <p>${starIcons(h.stars)} • Rp ${h.price.toLocaleString('id-ID')}/malam</p>
      <p class="font-semibold text-amber-900">Total ${nights} malam: Rp ${subtotal.toLocaleString('id-ID')}</p>
      <button class="w-full bg-amber-900 hover:brightness-110 text-white py-1 rounded" onclick="bookTravel(${h.id}, 'hotel')">Pesan</button>
    </div>`;
  });
  html+='</div>';
  results.innerHTML=html;
}

/******************  BOOKING FUNCTION  ******************/
function bookTravel(id, type) {
  fetch('process_booking.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      id: id,
      type: type,
      destination: '<?php echo $destination_name; ?>',
      userId: <?php echo $user_id; ?>
    })
  })
  .then(response => response.json())
  .then(data => {
    if(data.success) {
      alert('Pemesanan berhasil!');
      // Redirect or update UI as needed
    } else {
      alert('Gagal memesan: ' + data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Terjadi kesalahan saat memproses pemesanan');
  });
}
</script>

</body>
</html>