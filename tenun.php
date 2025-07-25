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
  <title>Tenun Ikat Sasak – Warisan Budaya</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body { font-family: 'Poppins', sans-serif; }
    .card:hover { transform: translateY(-4px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    .card p { text-align: justify; }
    .submenu {
      scrollbar-width: thin;
      scrollbar-color: #d97706 transparent;
    }
    .submenu::-webkit-scrollbar {
      height: 4px;
    }
    .submenu::-webkit-scrollbar-thumb {
      background-color: #d97706;
      border-radius: 20px;
    }
    .submenu-item {
      transition: all 0.2s ease;
    }
    .submenu-item:hover {
      transform: translateY(-2px);
    }
    .submenu-item.active {
      background-color: #b45309;
      color: white;
      font-weight: 500;
    }
  </style>
</head>
<body class="bg-amber-50 text-amber-900">
  <!-- Header -->
  <header class="bg-amber-900 text-white p-4 shadow-md flex items-center justify-between relative">
    <a href="katalog.php" class="text-lg font-bold flex items-center gap-2 hover:text-amber-200 absolute left-4 top-1/2 -translate-y-1/2">
      <span class="text-2xl leading-none">←</span>
      <span class="hidden sm:inline">Kembali</span>
    </a>
    <h1 class="font-bold text-xl text-center w-full">Tenun Ikat Sasak</h1>
  </header>

  

  <!-- Intro -->
  <section class="max-w-4xl mx-auto px-4 py-8 text-center">
    <h2 class="text-2xl font-bold mb-2">Kain Tradisional <span class="text-amber-700">Suku Sasak</span></h2>
    <p class="text-sm text-gray-700">Tenun ikat Sasak mencerminkan kearifan lokal, nilai spiritual, dan filosofi hidup masyarakat Lombok yang diwariskan turun-temurun.</p>
  </section>

  <!-- Grid Motif Tenun -->
  <section class="max-w-6xl mx-auto grid sm:grid-cols-2 lg:grid-cols-3 gap-6 px-4 pb-24">
    <!-- Motif Subhanale -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform">
      <img src="https://pbs.twimg.com/media/CVHZC-zU4AE5WbD.jpg" alt="Motif Subhanale" class="h-48 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Motif Subhanale</h3>
        <p class="text-sm">Merupakan bentuk mengagungkan asma Allah. Dahulu ada seorang penenun yang ketika merasa puas melihat hasil karya menenunnya, dia mengucapkan "Subhanallah" (artinya Maha Suci Allah), sehingga melahirkan nama Subhanale ini.</p>
        <details class="text-sm">
          <summary class="cursor-pointer text-amber-700 font-medium">Lihat lebih banyak</summary>
          <p class="mt-2">Awalnya berbentuk geometris segi enam yang didalamnya ada dekorasi beragam bunga, seperti bunga tanjung, remawa atau kenanga. Dengan pemilihan warna dasar kain hitam atau merah dengan motif bergaris garis geometris berwarna kuning. Kain tenun motif ini biasa digunakan untuk pakaian pria dan wanita dalam pesta atau upacara adat.</p>
        </details>
      </div>
    </article>

    <!-- Motif Ragi Genep -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform">
      <img src="https://www.jokembe.com/source/Kain%20tenun%20Khas%20Desa%20Sade%2C%20Lombok%20Tengah%20Motif%20Ragi%20Genep.jpg" alt="Motif Ragi Genep" class="h-48 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Motif Ragi Genep</h3>
        <p class="text-sm">Arti Ragi dalam ungkapan bahasa Sasak adalah syarat. Tata cara "Genep" berarti cukup. Ungkapan ini mengandung makna orang yang hendak berpergian sebaiknya berpakaian yang memenuhi syarat.</p>
        <details class="text-sm">
          <summary class="cursor-pointer text-amber-700 font-medium">Lihat lebih banyak</summary>
          <p class="mt-2">Motif ini biasanya ada pada kain sarung sehingga sering digunakan untuk kegiatan sehari-hari, baik oleh pria mau pun wanita. Pria untuk dodot sementara Wanita sebagai Selendang. Kain ini dibuat sesuai dengan tata cara/norma yang berlaku di masyarakat Sasak.</p>
        </details>
      </div>
    </article>

    <!-- Motif Rangrang -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform">
      <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRAnqbwpH-LHrzobkq7GM9zVM6lFv7xR78BTw&s" alt="Motif Rangrang" class="h-48 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Motif Rangrang</h3>
        <p class="text-sm">Motif Rangrang adalah perpaduan antara kain khas Lombok dengan kain tradisional Bali yang memiliki corak zig-zag serta kombinasi warna mencolok.</p>
        <details class="text-sm">
          <summary class="cursor-pointer text-amber-700 font-medium">Lihat lebih banyak</summary>
          <p class="mt-2">Kain ini banyak dikreasikan dalam bentuk pakaian seperti kebaya tradisional, pakaian ibadah, hingga selendang. Corak Rangrang pada setiap helai kain ini menggambarkan cara hidup dan keyakinan masyarakat pada alam dan penguasa alam. Makna yang cukup filosofis ini, tak heran mengapa tenun Rangrang hingga saat ini memiliki nilai jual yang cukup tinggi hingga sekarang.</p>
        </details>
      </div>
    </article>

    <!-- Motif Alang/Lumbung -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform">
      <img src="https://www.jokembe.com/source/kain%20tentun%20songket%20khas%20sukarara%20lombok%20motif%20Alang%20atau%20Lumbung%20.jpg" alt="Motif Alang/Lumbung" class="h-48 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Motif Alang/Lumbung</h3>
        <p class="text-sm">Motif ini terinspirasi dari bentuk lumbung padi, tempat penyimpanan hasil panen yang penting bagi masyarakat agraris.</p>
        <details class="text-sm">
          <summary class="cursor-pointer text-amber-700 font-medium">Lihat lebih banyak</summary>
          <p class="mt-2">Bentuk lumbung yang kokoh dan berfungsi sebagai tempat menyimpan hasil bumi menjadi simbol kesejahteraan dan kemakmuran. Motif ini mencerminkan penghargaan masyarakat Sasak terhadap pertanian sebagai sumber kehidupan.</p>
        </details>
      </div>
    </article>

    <!-- Motif Bintang Empat -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform">
      <img src="https://www.jokembe.com/source/kain%20tentun%20songket%20khas%20sukarara%20lombok%20motif%20bintang%20empat%20.jpg" alt="Motif Bintang Empat" class="h-48 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Motif Bintang Empat</h3>
        <p class="text-sm">Motif ini terinspirasi dari bintang timur yang menandai terbitnya fajar dan arah mata angin.</p>
        <details class="text-sm">
          <summary class="cursor-pointer text-amber-700 font-medium">Lihat lebih banyak</summary>
          <p class="mt-2">Biasanya, motif ini digambarkan dalam bentuk kotak-kotak berwarna merah dan hijau muda, atau garis-garis horizontal merah dan hitam. Melambangkan petunjuk arah hidup dan harapan baru seperti fajar yang menyingsing.</p>
        </details>
      </div>
    </article>

    <!-- Motif Keker atau Merak -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform">
      <img src="https://www.jokembe.com/source/kain%20tentun%20songket%20khas%20sukarara%20lombok%20motif%20Keker%20atau%20Merak%20.jpg" alt="Motif Keker atau Merak" class="h-48 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Motif Keker atau Merak</h3>
        <p class="text-sm">Motif Keker atau Merak pada tenun ikat Lombok memiliki makna kedamaian, kebahagiaan, dan cinta abadi.</p>
        <details class="text-sm">
          <summary class="cursor-pointer text-amber-700 font-medium">Lihat lebih banyak</summary>
          <p class="mt-2">Motif ini sering digambarkan dengan dua ekor burung merak yang saling berhadapan dan bernaung di bawah pohon. Selain itu, motif ini juga dikenal sebagai motif bulan madu karena melambangkan keabadian cinta dan kesetiaan dalam pernikahan.</p>
        </details>
      </div>
    </article>
  </section>

  <!-- Footer -->
  <footer class="bg-amber-900 text-white text-sm text-center py-3">
    <p>&copy; 2025 BALEQARA | Tim Pengembang</p>
  </footer>

  <script>
    // Highlight current page in submenu
    document.addEventListener('DOMContentLoaded', function() {
      const currentPage = window.location.pathname.split('/').pop();
      const menuItems = document.querySelectorAll('.submenu-item');
      
      menuItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href === currentPage) {
          item.classList.add('active');
        } else {
          item.classList.remove('active');
        }
      });
    });
  </script>
</body>
</html>