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
  <title>Alat Musik & Tarian Sasak – Warisan Budaya</title>
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
      transform: translateY(-8px);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
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
      background: linear-gradient(to right, #fed7aa, #fdba74);
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
    .menu-item {
      transition: all 0.2s ease;
      border-radius: 6px;
      padding: 8px 12px;
    }
    .menu-item:hover {
      background: rgba(254, 215, 170, 0.3);
    }
  </style>
</head>
<body class="min-h-screen flex flex-col">

  <!-- Header with Mobile Menu -->
  <header class="header-gradient text-white p-4 shadow-md flex items-center justify-between relative">
    
      <a href="katalog.php" class="text-lg font-bold flex items-center gap-2 hover:text-amber-200 absolute left-4 top-1/2 -translate-y-1/2">
      <span class="text-2xl leading-none">←</span>
      <span class="hidden sm:inline">Kembali</span>
    </a>
      <h1 class="font-bold text-2xl text-center flex-grow px-4 tracking-tight">Alat Musik & Tarian Sasak</h1>
      <button id="openMenu" aria-label="Buka menu" class="text-3xl cursor-pointer focus:outline-none hover:text-amber-200 transition-colors">
        
      </button>
    </div>
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
    <h2 class="text-2xl font-bold mb-2">Ritme dan Gerak Budaya <span class="text-amber-700">Suku Sasak</span></h2>
    <p class="text-sm text-gray-700">Musik dan tarian tradisional Sasak mencerminkan kearifan lokal, semangat komunitas, dan nilai spiritual masyarakat Lombok.</p>
  </section>

  <!-- Grid Alat Musik dan Tarian -->
  <section class="max-w-6xl mx-auto grid sm:grid-cols-2 lg:grid-cols-3 gap-6 px-4 pb-24 relative z-10">
    <!-- Tari Rudat -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform animate-fadeIn">
      <img src="https://insidelombok.id/wp-content/uploads/2021/03/5-9.jpg" alt="Gendang Beleq" class="h-48 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Tari Rudat</h3>
        <p class="text-sm">Tari Rudat ini sangat kental akan nuansa Islami baik dari segi kostum, lagu maupun pengiring pertunjukan. Tari Rudat ini biasanya ditampilkan di berbagai acara seperti Maulid Nabi, peringatan Isra Mi'raj dan acara peringatan hari besar Islam lainnya.</p>
        <details class="text-sm">
          <summary class="cursor-pointer text-amber-700 font-medium">Lihat lebih banyak</summary>
          <p class="mt-2">Tarian ini digunakan para Ulama terdahulu sebagai media penyebaran agama Islam. Banyak yang mengatakan pula bahwa, Tari Rudat ini merupakan perkembangan dari Dzikir Saman dan Budrah. Dzikir Saman merupakan kesenian tari dengan gerakan pencak silat dan disertai dengan dzikir. Sedangkan Budrah merupakan nyanyian yang diiringi dengan iringan seperangkat musik rebana berukuran besar.</p>
        </details>
        <a href="https://id.video.search.yahoo.com/video/play;_ylt=Awrx.bsWCnpofDUcHLccHYpQ;_ylu=c2VjA3NyBHNsawN2aWQEZ3BvcwMz?p=tari+rudat&vid=a6d6e93aef18a269a9b12bec64bf2964&turl=https%3A%2F%2Ftse4.mm.bing.net%2Fth%2Fid%2FOVP.R_pyzC-vZWGYP-_g8kjlJAHgFo%3Fpid%3DApi%26h%3D360%26w%3D480%26c%3D7%26rs%3D1&rurl=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3DRVv6635SdCY&tit=TRADITIONAL+DANCE+FROM+NTB+%7C%7C+%3Cb%3ETARI%3C%2Fb%3E+%3Cb%3ERUDAT%3C%2Fb%3E+LOMBOK+%7C%7C+PGSD+UNRAM+19&c=2&sigr=SRUf1wY0y1db&sigt=bUUSVhck0EUA&sigi=JBbciGgfHdMz&fr2=p%3As%2Cv%3Av&h=360&w=480&l=769&age=1603680466&fr=yhs-fc-2449_9&hsimp=yhs-2449_9&hspart=fc&type=fc_A6943EDBC19_s58_g_e_d062625_n9999_c999&param1=7&param2=eJwljkFPhDAQhf9Kj5q05U1LK6UncOUHGE82PWyAJc2uYEBl4683xcvkZd43896UhuDj64kAo10VeJyDj845F3jMFqyyygQe%2B%2F994DF9Bh%2BphFSkJVVPkvLdNC7BxzQEHr%2FPwceP5TfdbufCSLCHPc3Dsm9s%2FmIECc%2F2NNvSs3se60%2Bdv%2BGRTWN%2FXQoFAgjELmkdL8u9ONwcvB1tc8I2roc2TdO9uBOEMUYLog6i7VoI3T6TbQyqsrKZ7zOsoIyAFRpvoLqkWpcSlt7%2FADdpSGU%3D&tt=b" class="inline-block mt-2 text-xs underline">Tonton Pertunjukan</a>
      </div>
    </article>

    <!-- Tarian Peresean -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform animate-fadeIn">
      <img src="https://helloindonesia.id/wp-content/uploads/2019/03/BicVLwGCQAAvElP.jpglarge.jpeg" alt="Tarian Peresean" class="h-48 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Peresean</h3>
        <p class="text-sm">Pertarungan tradisional antara dua pepadu (petarung) menggunakan rotan dan perisai. Warisan kekayaan budaya di Gumi Lombok Sileparang ini tergolong unik dan menjadi daya tarik tersendiri bagi para wisatawan lokal maupun mancanegara.</p>
        <details class="text-sm">
          <summary class="cursor-pointer text-amber-700 font-medium">Lihat lebih banyak</summary>
          <p class="mt-2">Peresean bukan sekadar tarian, tetapi ritual keberanian dan ajang penguatan mental masyarakat Sasak. Tradisi Peresean bukan hanya untuk ritual dan acara kerajaan, melainkan juga menjadi daya tarik luar biasa untuk menyambut para wisatawan yang berkunjung.

Karena bukan merupakan pertarungan biasa dan mengandung makna filosofis yang kuat. Tari Peresan ini di iringi oleh musik khas sasak seperti Gendang Belek</p>
        </details>
        <a href="https://youtu.be/WP-wQAxF5uA?si=XmAOEpuBbsWfBRrT" class="inline-block mt-2 text-xs underline">Tonton Aksi</a>
      </div>
    </article>

    <!-- Tarian Gandrung Sasak -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform animate-fadeIn">
      <img src="https://1.bp.blogspot.com/-2JFrlGf2k7w/YGfy_MJtqSI/AAAAAAAABwU/6MWbTP8_aZY7Yy4RSX4VQ6KyGTkAWAlKgCLcBGAsYHQ/s1024/Tari%2BGandrung%2BLombok.jpg" alt="Tarian Gandrung Sasak" class="h-48 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Tari Gandrung Sasak</h3>
        <p class="text-sm">Tari pergaulan yang dibawakan oleh penari wanita sebagai ungkapan rasa syukur dan suka cita, biasanya diiringi gamelan Sasak. Tari ini sangat berbeda dari tarian lainnya, dapat ditemukan pada gerakan, kostum maupun penyajian pertunjukannya.</p>
        <details class="text-sm">
          <summary class="cursor-pointer text-amber-700 font-medium">Lihat lebih banyak</summary>
          <p class="mt-2">Tarian ini menggambarkan keanggunan perempuan Sasak serta keramahan dalam menyambut tamu dan merayakan hasil panen.</p>
        </details>
        <a href="https://id.video.search.yahoo.com/video/play;_ylt=Awrx.bvCD3poog0daRIcHYpQ;_ylu=c2VjA3NyBHNsawN2aWQEZ3BvcwMx?p=tari+gandrung+lombok&vid=a4befb9d75ce02d15ce17108a5fe0329&turl=https%3A%2F%2Ftse2.mm.bing.net%2Fth%2Fid%2FOVP.b-5Ai2SfpC_tN_XuUmIRogHgFo%3Fpid%3DApi%26h%3D360%26w%3D480%26c%3D7%26rs%3D1&rurl=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D5f9pobX1fPQ&tit=%3Cb%3ETARI%3C%2Fb%3E+%3Cb%3EGANDRUNG%3C%2Fb%3E+SASAK+%3Cb%3ELOMBOK%3C%2Fb%3E+-+official+video&c=0&sigr=RayCmPRsBXOS&sigt=r2ojGnhk8ivE&sigi=xbNZ3djEBWjj&fr2=p%3As%2Cv%3Av&h=360&w=480&l=452&age=1650931343&fr=yhs-fc-2449_9&hsimp=yhs-2449_9&hspart=fc&type=fc_A6943EDBC19_s58_g_e_d062625_n9999_c999&vm=r&param1=7&param2=eJwljkFPhDAQhf9Kj5q05U1LK6UncOUHGE82PWyAJc2uYEBl4683xcvkZd43896UhuDj64kAo10VeJyDj845F3jMFqyyygQe%2B%2F994DF9Bh%2BphFSkJVVPkvLdNC7BxzQEHr%2FPwceP5TfdbufCSLCHPc3Dsm9s%2FmIECc%2F2NNvSs3se60%2Bdv%2BGRTWN%2FXQoFAgjELmkdL8u9ONwcvB1tc8I2roc2TdO9uBOEMUYLog6i7VoI3T6TbQyqsrKZ7zOsoIyAFRpvoLqkWpcSlt7%2FADdpSGU%3D&tt=b" class="inline-block mt-2 text-xs underline">Tonton Aksi</a>
      </div>
    </article>

    <!-- Gendang Beleq -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform animate-fadeIn">
      <img src="https://pelopor.id/wp-content/uploads/2021/12/Gendang-Beleq.jpg" alt="Gendang Beleq" class="h-48 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Gendang Beleq</h3>
        <p class="text-sm">Gendang raksasa yang dimainkan secara berkelompok, biasanya dalam upacara adat seperti nyongkolan atau penyambutan tamu agung.</p>
        <details class="text-sm">
          <summary class="cursor-pointer text-amber-700 font-medium">Lihat lebih banyak</summary>
          <p class="mt-2">Gendang Beleq terdiri dari dua jenis: gendang mama (jantan) dan gendang nina (betina). Alat ini melambangkan keseimbangan dan harmoni.</p>
        </details>
        <a href="https://id.video.search.yahoo.com/video/play;_ylt=AwrKGQiBEHpo6xgA.Q4cHYpQ;_ylu=c2VjA3NyBHNsawN2aWQEZ3BvcwMx?p=suling+sasak&vid=e78da4667ea076a30f1a536f3206a7c8&turl=https%3A%2F%2Ftse3.mm.bing.net%2Fth%2Fid%2FOVP.nGiuaoqaPeyHEWVYnQaW_gEsDh%3Fpid%3DApi%26h%3D225%26w%3D300%26c%3D7%26rs%3D1&rurl=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3Dbs8dTlxI-hg&tit=%3Cb%3ESuling%3C%2Fb%3E+Tembang+%3Cb%3ESasak%3C%2Fb%3E-Subahnale+%5BOfficial+Music+Video%5D&c=0&sigr=uwlftm7gDwB1&sigt=vvVAifvl.fl4&sigi=mZ8VeQP9gzNI&fr2=p%3As%2Cv%3Av&h=225&w=300&l=482&age=1664972920&fr=yhs-fc-2449_9&hsimp=yhs-2449_9&hspart=fc&type=fc_A6943EDBC19_s58_g_e_d062625_n9999_c999&vm=r&param1=7&param2=eJwljkFPhDAQhf9Kj5q05U1LK6UncOUHGE82PWyAJc2uYEBl4683xcvkZd43896UhuDj64kAo10VeJyDj845F3jMFqyyygQe%2B%2F994DF9Bh%2BphFSkJVVPkvLdNC7BxzQEHr%2FPwceP5TfdbufCSLCHPc3Dsm9s%2FmIECc%2F2NNvSs3se60%2Bdv%2BGRTWN%2FXQoFAgjELmkdL8u9ONwcvB1tc8I2roc2TdO9uBOEMUYLog6i7VoI3T6TbQyqsrKZ7zOsoIyAFRpvoLqkWpcSlt7%2FADdpSGU%3D&tt=b" class="inline-block mt-2 text-xs underline">Tonton Pertunjukan</a>
      </div>
    </article>

    <!-- Suling Sasak -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform animate-fadeIn">
      <img src="https://i.ytimg.com/vi/mx3B_MARm0g/maxresdefault.jpg" alt="Suling Sasak" class="h-48 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Suling Sasak</h3>
        <p class="text-sm">Alat musik tiup tradisional yang menghasilkan melodi lembut dan menyayat. Biasa dimainkan untuk mengiringi tembang atau tarian rakyat.</p>
        <details class="text-sm">
          <summary class="cursor-pointer text-amber-700 font-medium">Lihat lebih banyak</summary>
          <p class="mt-2">Suling dibuat dari bambu dengan lubang nada yang disesuaikan dengan tangga nada khas Lombok. Ditiup dengan teknik khusus agar harmonis.</p>
        </details>
        <a href="https://id.video.search.yahoo.com/video/play;_ylt=AwrKGQiBEHpo6xgA.Q4cHYpQ;_ylu=c2VjA3NyBHNsawN2aWQEZ3BvcwMx?p=suling+sasak&vid=e78da4667ea076a30f1a536f3206a7c8&turl=https%3A%2F%2Ftse3.mm.bing.net%2Fth%2Fid%2FOVP.nGiuaoqaPeyHEWVYnQaW_gEsDh%3Fpid%3DApi%26h%3D225%26w%3D300%26c%3D7%26rs%3D1&rurl=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3Dbs8dTlxI-hg&tit=%3Cb%3ESuling%3C%2Fb%3E+Tembang+%3Cb%3ESasak%3C%2Fb%3E-Subahnale+%5BOfficial+Music+Video%5D&c=0&sigr=uwlftm7gDwB1&sigt=vvVAifvl.fl4&sigi=mZ8VeQP9gzNI&fr2=p%3As%2Cv%3Av&h=225&w=300&l=482&age=1664972920&fr=yhs-fc-2449_9&hsimp=yhs-2449_9&hspart=fc&type=fc_A6943EDBC19_s58_g_e_d062625_n9999_c999&vm=r&param1=7&param2=eJwljkFPhDAQhf9Kj5q05U1LK6UncOUHGE82PWyAJc2uYEBl4683xcvkZd43896UhuDj64kAo10VeJyDj845F3jMFqyyygQe%2B%2F994DF9Bh%2BphFSkJVVPkvLdNC7BxzQEHr%2FPwceP5TfdbufCSLCHPc3Dsm9s%2FmIECc%2F2NNvSs3se60%2Bdv%2BGRTWN%2FXQoFAgjELmkdL8u9ONwcvB1tc8I2roc2TdO9uBOEMUYLog6i7VoI3T6TbQyqsrKZ7zOsoIyAFRpvoLqkWpcSlt7%2FADdpSGU%3D&tt=b" class="inline-block mt-2 text-xs underline">Tonton Pertunjukan</a>
      </div>
    </article>

    <!-- Rebab Sasak -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform animate-fadeIn">
      <img src="https://gomandalika.com/wp-content/uploads/2022/10/PENTING-SASAK-scaled.jpg" alt="Rebab Sasak" class="h-48 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Rebab</h3>
        <p class="text-sm">Alat musik gesek dua dawai dari kayu dan kulit binatang. Rebab digunakan dalam pertunjukan tembang atau tarian klasik Sasak.</p>
        <details class="text-sm">
          <summary class="cursor-pointer text-amber-700 font-medium">Lihat lebih banyak</summary>
          <p class="mt-2">Permainan rebab membutuhkan kehalusan dan perasaan, karena setiap nada mengekspresikan emosi mendalam dari kebudayaan Sasak.</p>
        </details> 
        <a href="https://id.video.search.yahoo.com/video/play;_ylt=Awrx_NuVE3poMycd.R4cHYpQ;_ylu=c2VjA3NyBHNsawN2aWQEZ3BvcwMzNA--?p=Rebab+Sasak+musik&vid=6fa734918eda34086442b5e99708a88d&turl=https%3A%2F%2Ftse1.mm.bing.net%2Fth%2Fid%2FOVP.LozQ7qJczgbSWxFJeydHTgEkII%3Fpid%3DApi%26h%3D520%26w%3D292%26c%3D7%26rs%3D1&rurl=https%3A%2F%2Fwww.tiktok.com%2F%40sasak.begambus%2Fvideo%2F7400322774100626693&tit=%3Cb%3ESasak%3C%2Fb%3E+legendaris+alur+timbak%23sasakgambustunggal+%23sasakgambusvirall+%23balesasakbegambus+%23fyp&c=33&sigr=AHS4dTdUshIA&sigt=M99gWZ_QGQIm&sigi=snXfwS34uVmE&fr2=p%3As%2Cv%3Av&h=520&w=292&l=443&age=1723021922&fr=yhs-fc-2449_9&hsimp=yhs-2449_9&hspart=fc&type=fc_A6943EDBC19_s58_g_e_d062625_n9999_c999&vm=r&param1=7&param2=eJwljkFPhDAQhf9Kj5q05U1LK6UncOUHGE82PWyAJc2uYEBl4683xcvkZd43896UhuDj64kAo10VeJyDj845F3jMFqyyygQe%2B%2F994DF9Bh%2BphFSkJVVPkvLdNC7BxzQEHr%2FPwceP5TfdbufCSLCHPc3Dsm9s%2FmIECc%2F2NNvSs3se60%2Bdv%2BGRTWN%2FXQoFAgjELmkdL8u9ONwcvB1tc8I2roc2TdO9uBOEMUYLog6i7VoI3T6TbQyqsrKZ7zOsoIyAFRpvoLqkWpcSlt7%2FADdpSGU%3D&tt=b" class="inline-block mt-2 text-xs underline">Tonton Pertunjukan</a>
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