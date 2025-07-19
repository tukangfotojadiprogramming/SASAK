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
  <title>Makanan Khas Sasak – Warisan Budaya</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body { 
      font-family: 'Poppins', sans-serif; 
      background: linear-gradient(to bottom, #fff7ed, #ffedd5);
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
      transform: translateY(-4px); 
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
      border-color: rgba(251, 146, 60, 0.5);
    }
    .card p { text-align: justify; }
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
    .footer-gradient {
      background: linear-gradient(to right, #92400e, #b45309);
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
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
      animation: fadeIn 0.6s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }
  </style>
</head>
<body class="min-h-screen flex flex-col">

  <!-- Header -->
  <header class="header-gradient text-white p-4 shadow-md flex items-center justify-between relative">
    
    <a href="katalog.php" class="back-link text-lg font-bold flex items-center gap-2 hover:text-amber-200 absolute left-4 top-1/2 -translate-y-1/2 z-10">
      <span class="text-2xl leading-none">←</span>
      <span class="hidden sm:inline">Kembali</span>
    </a>
    <h1 class="font-bold text-xl text-center w-full relative z-10">Makanan Khas Sasak</h1>
  </header>

  <!-- Intro -->
  <section class="max-w-4xl mx-auto px-4 py-8 text-center">
    <h2 class="text-2xl font-bold mb-2">Lezatnya <span class="text-amber-700">Kuliner Tradisional Sasak</span></h2>
    <p class="text-sm text-gray-700">Makanan khas suku Sasak adalah bagian penting dari kekayaan budaya Lombok yang menawarkan cita rasa unik, kuat, dan sarat filosofi.</p>
  </section>

  <!-- Grid Makanan -->
  <section class="max-w-6xl mx-auto grid sm:grid-cols-2 lg:grid-cols-3 gap-6 px-4 pb-24">
    <!-- Ayam Taliwang -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform">
      <img src="https://basfood.id/wp-content/uploads/2023/02/Ayam-Taliwang-Khas-Lombok.jpg" alt="Ayam Taliwang" class="h-48 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Ayam Taliwang</h3>
        <p class="text-sm">Ayam kampung muda yang dibakar dengan sambal khas Taliwang berbahan dasar cabai merah, bawang putih, terasi, dan tomat. Rasanya pedas menggigit dan sangat khas.</p>
        <details class="text-sm">
            <summary class="cursor-pointer text-amber-700 font-medium">Lihat lebih banyak</summary>
            <p class="mt-2">Ayam Taliwang biasa disajikan dengan plecing kangkung dan nasi hangat. Makanan ini berasal dari Kampung Taliwang dan kini telah menjadi ikon kuliner Lombok.</p>
            <h4 class="font-semibold mt-3 text-amber-800">Resep & Cara Membuat:</h4>
            <ul class="list-disc list-inside text-sm mt-1 space-y-1">
                <li><strong>Bahan:</strong> Ayam kampung muda, cabai merah, bawang putih, terasi, tomat, garam, dan gula merah.</li>
                <li><strong>Cara:</strong> Haluskan bumbu, tumis hingga harum, lalu balurkan ke ayam. Bakar ayam sambil dioles bumbu hingga matang dan meresap.</li>
            </ul>
        </details>
        <a href="https://www.youtube.com/watch?si=dEVMI1n2VTiUe-db&embeds_referring_euri=https%3A%2F%2Fid.video.search.yahoo.com%2F&embeds_referring_origin=https%3A%2F%2Fid.video.search.yahoo.com&source_ve_path=MTY0NTA2&v=Iw_KY_HiQWo&feature=youtu.be" class="inline-block mt-2 text-xs underline">Cara Pembuatan</a>
      </div>
    </article>

    <!-- Beberuk Terong -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform">
      <img src="https://i.ytimg.com/vi/9RRZ1_3S4Hc/maxresdefault.jpg" alt="Beberuk Terong" class="h-48 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Beberuk Terong</h3>
        <p class="text-sm">Salad khas Lombok yang terdiri dari terong ungu mentah yang diiris tipis lalu disiram sambal tomat mentah. Segar, pedas, dan menambah selera makan.</p>
        <details class="text-sm">
            <summary class="cursor-pointer text-amber-700 font-medium">Lihat lebih banyak</summary>
            <p class="mt-2">Beberuk Terong adalah lalapan segar dari terong mentah dan sambal mentah khas Sasak.</p>
            <h4 class="font-semibold mt-3 text-amber-800">Resep & Cara Membuat:</h4>
            <ul class="list-disc list-inside text-sm mt-1 space-y-1">
                <li><strong>Bahan:</strong> Terong ungu mentah, tomat, cabai rawit, bawang merah, garam, jeruk limau.</li>
                <li><strong>Cara:</strong> Potong terong, ulek sambal, lalu campur dan aduk hingga rata.</li>
            </ul>
        </details>
        <a href="https://youtu.be/Fx5YXfSp1G8?si=H9Cx0G7gmpiVrTiw" class="inline-block mt-2 text-xs underline">Cara Pembuatan</a>
      </div>
    </article>

    <!-- Plecing Kangkung -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform">
      <img src="https://img-global.cpcdn.com/recipes/603d5133a1c5873c/1200x630cq70/photo.jpg" alt="Plecing Kangkung" class="h-48 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Pelecing Kangkung</h3>
        <p class="text-sm">Kangkung rebus disiram sambal tomat khas Lombok dengan taburan kacang tanah goreng dan perasan jeruk limau. Cocok jadi lauk pendamping utama.</p>
        <details class="text-sm">
            <summary class="cursor-pointer text-amber-700 font-medium">Lihat lebih banyak</summary>
            <p class="mt-2">Plecing Kangkung adalah sayuran khas Lombok yang disiram sambal tomat pedas. Biasanya disajikan sebagai pelengkap Ayam Taliwang.</p>
            <h4 class="font-semibold mt-3 text-amber-800">Resep & Cara Membuat:</h4>
            <ul class="list-disc list-inside text-sm mt-1 space-y-1">
                <li><strong>Bahan:</strong> Kangkung, tomat, cabai rawit, terasi, garam, jeruk limau.</li>
                <li><strong>Cara:</strong> Rebus kangkung, haluskan bumbu lalu siram ke atas kangkung. Sajikan dingin.</li>
            </ul>
        </details>
        <a href="https://youtu.be/CvShDN4CdsU?si=lb9-igPP9ftFh7Yr" class="inline-block mt-2 text-xs underline">Cara Pembuatan</a>
      </div>
    </article>

    <!-- Sate Rembiga -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform">
      <img src="https://cdn0-production-images-kly.akamaized.net/EAbi53ClPFWop0gOwkZQS0J9Rjc=/1x135:1000x698/1200x675/filters:quality(75):strip_icc():format(jpeg)/kly-media-production/medias/4703886/original/010040500_1704118595-shutterstock_2290464261.jpg" alt="Sate Rembiga" class="h-48 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Sate Rembiga</h3>
        <p class="text-sm">Sate daging sapi khas Rembiga, Lombok, yang dibumbui cabai rawit, bawang putih, ketumbar, dan gula merah. Rasanya manis pedas dan sangat juicy.</p>
       <details class="text-sm">
            <summary class="cursor-pointer text-amber-700 font-medium">Lihat lebih banyak</summary>
            <p class="mt-2">Sate Rembiga berasal dari daerah Rembiga, Kota Mataram. Sate ini punya rasa pedas manis khas Lombok.</p>
            <h4 class="font-semibold mt-3 text-amber-800">Resep & Cara Membuat:</h4>
            <ul class="list-disc list-inside text-sm mt-1 space-y-1">
                <li><strong>Bahan:</strong> Daging sapi, bawang putih, cabai rawit, gula merah, ketumbar, garam, terasi.</li>
                <li><strong>Cara:</strong> Marinasi daging dengan bumbu, diamkan 1 jam, lalu tusuk dan bakar hingga matang.</li>
            </ul>
        </details>
        <a href="https://youtu.be/6tFOm4dOBZY?si=Je0mXNjetlS_5EFP" class="inline-block mt-2 text-xs underline">Cara Pembuatan</a>
      </div>
    </article>

    <!-- Sate Pusut -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform">
        <img src="https://cdn0-production-images-kly.akamaized.net/mmup8UVh94NKgpK0uybv68PA5tA=/0x0:5184x2922/1200x675/filters:quality(75):strip_icc():format(jpeg)/kly-media-production/medias/3652549/original/060993000_1638588819-shutterstock_1729690198.jpg" alt="Sate Pusut" class="h-48 w-full object-cover">
        <div class="p-4 space-y-2">
            <h3 class="font-semibold text-lg">Sate Pusut</h3>
            <p class="text-sm">Sate khas Sasak dari daging sapi atau ayam yang dicampur kelapa parut, bumbu rempah, lalu dipusuti (dibungkus) ke tusuk sate sebelum dibakar.</p>
            <details class="text-sm">
                <summary class="cursor-pointer text-amber-700 font-medium">Lihat lebih banyak</summary>
                <p class="mt-2">Cita rasa gurih dari kelapa parut dan bumbu khas membuat sate ini berbeda. Biasanya disajikan dalam acara keluarga dan perayaan adat di Lombok.</p>
                <h4 class="font-semibold mt-3 text-amber-800">Resep & Cara Membuat:</h4>
                <ul class="list-disc list-inside text-sm mt-1 space-y-1">
                    <li><strong>Bahan:</strong> Daging sapi/ayam cincang, kelapa parut, bawang merah, bawang putih, ketumbar, lengkuas, garam, dan gula merah.</li>
                    <li><strong>Cara:</strong> Campur semua bahan, balutkan ke tusuk sate (dipusut), lalu bakar sambil dioles dengan sisa bumbu hingga matang.</li>
                </ul>
            </details>
            <a href="https://youtu.be/uHTzfK94T00?si=jmf-CYmDKQdsdMhn" class="inline-block mt-2 text-xs underline">Cara Pembuatan</a>
        </div>
    </article>

    <!-- Ares -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform">
      <img src="https://cdn.idntimes.com/content-images/community/2022/03/sayur-ares-3-1dfec209c12f3cdc32ed90758fc75423-00cbaa7e23e18bbd7d2fa4c42d8ad3bb.jpg" alt="Ares" class="h-48 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Ares</h3>
        <p class="text-sm">Sayur khas Sasak yang terbuat dari batang pisang muda dimasak dengan santan dan bumbu khas. Rasanya gurih dan teksturnya lembut.</p>
        <details class="text-sm">
            <summary class="cursor-pointer text-amber-700 font-medium">Lihat lebih banyak</summary>
            <p class="mt-2">Ares adalah sayur khas Sasak yang terbuat dari batang pisang muda, dimasak dengan santan dan rempah-rempah.</p>
            <h4 class="font-semibold mt-3 text-amber-800">Resep & Cara Membuat:</h4>
            <ul class="list-disc list-inside text-sm mt-1 space-y-1">
                <li><strong>Bahan:</strong> Batang pisang muda, santan, bawang merah, bawang putih, lengkuas, serai, daun salam.</li>
                <li><strong>Cara:</strong> Rebus batang pisang, tumis bumbu hingga harum, masukkan batang pisang dan santan, masak sampai meresap.</li>
            </ul>
        </details>
        <a href="https://youtu.be/TPDjT7WAh7A?si=J_j9lTjLJAqF5Bei" class="inline-block mt-2 text-xs underline">Cara Pembuatan</a>
      </div>
    </article>
  </section>

   <footer class="bg-amber-900 text-white text-sm text-center py-3">
    <p>&copy; 2025 BALEQARA | Dilestarikan dengan cinta budaya.</p>
  </footer>

  <script>
    // Add animation to cards when page loads
    document.addEventListener('DOMContentLoaded', function() {
      const cards = document.querySelectorAll('.card');
      cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        setTimeout(() => {
          card.style.opacity = '1';
          card.style.transform = 'translateY(0)';
        }, 100 * index);
      });
    });
  </script>
</body>
</html>