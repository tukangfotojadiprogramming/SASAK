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
  <title>Baju Adat Sasak – Warisan Budaya</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body { 
      font-family: 'Poppins', sans-serif; 
      background: linear-gradient(to bottom, #fff7ed, #ffedd5);
    }
    .card:hover { 
      transform: translateY(-4px); 
      box-shadow: 0 10px 20px rgba(0,0,0,0.1); 
    }
    .card p { 
      text-align: justify; 
    }
    .header-gradient {
      background: linear-gradient(to right, #92400e, #b45309);
    }
    .footer-fixed {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      background: linear-gradient(to right, #92400e, #b45309);
    }
    
    /* Gaya khusus untuk details */
    .details-container {
      isolation: isolate;
      margin-top: 0.5rem;
    }
    
    details {
      border: 1px solid #f59e0b30;
      border-radius: 0.5rem;
      transition: all 0.3s ease;
    }
    
    details[open] {
      border-color: #f59e0b;
      box-shadow: 0 0 0 1px #f59e0b;
      padding-bottom: 0.5rem;
    }
    
    details summary {
      list-style: none;
      cursor: pointer;
      padding: 0.5rem;
      color: #b45309;
      font-weight: 500;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    details summary::-webkit-details-marker {
      display: none;
    }
    
    details summary .details-arrow {
      transition: transform 0.2s ease;
      margin-left: 0.5rem;
    }
    
    details[open] summary .details-arrow {
      transform: rotate(180deg);
    }
    
    details .details-content {
      padding: 0.5rem;
      margin-top: 0.5rem;
      background-color: #fffbeb;
      border-radius: 0.25rem;
      animation: fadeIn 0.3s ease-out;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body class="text-amber-900 min-h-screen flex flex-col"> 
    

  <!-- Header -->
  <header class="header-gradient text-white p-4 shadow-md flex items-center justify-between relative">
    <a href="katalog.php" class="text-lg font-bold flex items-center gap-2 hover:text-amber-200 absolute left-4 top-1/2 -translate-y-1/2">
      <span class="text-2xl leading-none">←</span>
      <span class="hidden sm:inline">Kembali</span>
    </a>
    <h1 class="font-bold text-xl text-center w-full">Baju Adat Sasak</h1>
    
    <!-- Mobile Menu Button -->
    <button id="openMenu" aria-label="Buka menu" class="text-3xl cursor-pointer focus:outline-none hover:text-amber-200 transition-colors absolute right-4">
      
    </button>
  </header>

  <!-- Overlay -->
  <div id="overlay" class="fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm hidden z-40"></div>

  <!-- Intro -->
  <section class="max-w-4xl mx-auto px-4 py-8 text-center">
    <h2 class="text-2xl font-bold mb-2">Pesona <span class="text-amber-700">Busana Tradisional</span></h2>
    <p class="text-sm text-gray-700">Baju adat Sasak bukan sekadar pakaian—ia adalah simbol jati diri, makna spiritual, dan kebijaksanaan leluhur yang diwariskan lintas generasi.</p>
  </section>

  <!-- Konten Baju Adat -->
  <section class="max-w-4xl mx-auto px-4 pb-24 grid md:grid-cols-2 gap-6">
    <!-- Baju Adat Pria -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform">
      <img src="assets/sasak.jpeg" alt="Baju Adat Pria Sasak" class="h-64 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Baju Adat Pria Sasak</h3>
        <p class="text-sm">Pria Sasak mengenakan <em>pelet kain songket</em> yang dililitkan di pinggang, kemeja lengan panjang, dan ikat kepala <em>sapuq</em>.</p>
        
        <div class="details-container">
          <details>
            <summary>
              <span>Bagian dan Makna</span>
              <span class="details-arrow">▼</span>
            </summary>
            <div class="details-content">
              <div class="mt-2 space-y-2">
                <div>
                  <strong>Sapuq</strong>: Ikat kepala khas Sasak yang dililit di kepala, melambangkan kehormatan, tanggung jawab, dan kedewasaan pria.
                </div>
                <div>
                  <strong>Selepan</strong>: Kemeja lengan panjang berwarna polos, mencerminkan kesederhanaan, kerapihan, dan tata krama dalam berbusana.
                </div>
                <div>
                  <strong>Pegon</strong>: Jas tradisional luar berwarna hitam atau gelap, dipakai dalam upacara adat atau pernikahan sebagai simbol wibawa.
                </div>
                <div>
                  <strong>Slewoq</strong>: Sarung atau kain tenun songket berwarna cerah yang dililitkan dari pinggang ke bawah, menambah nilai estetika dan fungsi.
                </div>
                <div>
                  <strong>Leang</strong>: Ikat pinggang atau tali yang mengencangkan slewoq agar tetap rapi; simbol kesiapan dan ketegasan.
                </div>
              </div>
            </div>
          </details>
        </div>
      </div>
    </article>

    <!-- Baju Adat Wanita -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform">
      <img src="https://down-id.img.susercontent.com/file/dbe7f3c0c115435060a3594543336aba" alt="Baju Adat Wanita Sasak" class="h-64 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Baju Adat Wanita Sasak</h3>
        <p class="text-sm">Wanita mengenakan <em>kebaya</em> khas Lombok dan <em>kain songket</em> yang ditenun tangan dengan motif simbolik, serta hiasan kepala dan selendang.</p>
        
        <div class="details-container">
          <details>
            <summary>
              <span>Bagian dan Makna</span>
              <span class="details-arrow">▼</span>
            </summary>
            <div class="details-content">
              <div class="mt-2 space-y-2">
                <div>
                  <strong>Lambung</strong>: Atasan tanpa lengan berwarna hitam yang dihiasi benang emas, mencerminkan kesederhanaan, kekuatan, dan keanggunan wanita Sasak.
                </div>
                <div>
                  <strong>Pangkak</strong>: Kain penutup dada yang dililitkan, menambah nilai estetika dan kesopanan dalam berpakaian adat.
                </div>
                <div>
                  <strong>Tongkak</strong>: Hiasan kepala yang dipakai saat acara adat seperti merariq, melambangkan kebanggaan dan keanggunan wanita Sasak.
                </div>
                <div>
                  <strong>Lempot</strong>: Selendang atau kain yang disampirkan di bahu, menambah anggun penampilan dan menunjukkan kesiapan dalam menjalankan peran perempuan.
                </div>
                <div>
                  <strong>Kereng Songket</strong>: Kain tenun songket bermotif khas Sasak yang dikenakan di bagian bawah, menjadi simbol kerja keras, ketrampilan, dan kekayaan budaya.
                </div>
              </div>
            </div>
          </details>
        </div>
      </div>
    </article>
  </section>

  <!-- Busana Pengantin -->
  <section class="max-w-4xl mx-auto px-4 py-8 text-center">
    <h2 class="text-2xl font-bold mb-2">Pesona <span class="text-amber-700">Busana Pengantin Sasak</span></h2>
    <p class="text-sm text-gray-700">Baju pengantin Sasak merupakan puncak dari ekspresi budaya yang kaya akan makna dan filosofi kehidupan.</p>
  </section>

  <!-- Grid Destinasi -->
  <section class="max-w-6xl mx-auto grid sm:grid-cols-2 lg:grid-cols-3 gap-6 px-4 pb-24">
    <!-- Sapu' -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform">
      <img src="https://www.jokembe.com/source/Sapuk%20Pengantin%20Pria%20Suku%20Sasak%2C%20Lombok.jpg" alt="Sapu' Pengantin" class="h-48 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Sapu'</h3>
        <p class="text-sm">Sapu' juga terbagi menjadi dua jenis yakni sapu' nganjeng dan sapu' lepek, cara pemakaian dan fungsinya juga berbeda.</p>
        
        <div class="details-container">
          <details>
            <summary>
              <span>Baca lebih lengkap</span>
              <span class="details-arrow">▼</span>
            </summary>
            <div class="details-content">
              <p class="mt-2">Sapu' nganjeng (ikat kepala destar berdiri) merupakan mahkota pengantin pria di suku Sasak, disebut demikian karena ujung sapu' tersebut berdiri tegak meruncing di bagian depan mempunyai makna bahwa kita sebagai manusia harus selalu mengingat kepada sang pencipta.</p>
            </div>
          </details>
        </div>
      </div>
    </article>

    <!-- Keris -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform">
      <img src="https://www.jokembe.com/source/Keris%20Pengantin%20Pria%20Suku%20Sasak%20terbuat%20dari%20lapisan%20emas.jpg" alt="Keris Sasak" class="h-48 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Keris</h3>
        <p class="text-sm">Keris bagi orang sasak tidak hanya dikenal sebagai hiasan semata, tapi dikenal pula sebagai senjata.</p>
        
        <div class="details-container">
          <details>
            <summary>
              <span>Baca lebih lengkap</span>
              <span class="details-arrow">▼</span>
            </summary>
            <div class="details-content">
              <p class="mt-2">Keris tersebut dengan fungsinya yang pertama, haruslah mempunyai bentuk yang sederhana, kuat dan tajam sehingga dapat digunakan oleh pemiliknya dengan lincah.</p>
            </div>
          </details>
        </div>
      </div>
    </article>

    <!-- Kelambi Pegon -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform">
      <img src="https://www.jokembe.com/source/Klambi%20Pegon%20dan%20motif%20yang%20ada%20pada%20klambi%20pegon%20pengantin%20pria%20Suku%20Sasak%2C%20Lombok.jpg" alt="Klambi Pegon" class="h-48 w-full object-cover object-top">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Klambi Pegon</h3>
        <p class="text-sm">Baju pegon adalah jas tutup yang kerahnya berdiri dengan diberi kancing mulai dari leher terus sampai ke bawah.</p>
        
        <div class="details-container">
          <details>
            <summary>
              <span>Baca lebih lengkap</span>
              <span class="details-arrow">▼</span>
            </summary>
            <div class="details-content">
              <p class="mt-2">Pada bagian belakang baju pegon ini dipotong melengkung dari atas pinggang sampai ujung bagian depan baju. Sehingga tampak depannya meruncing.</p>
            </div>
          </details>
        </div>
      </div>
    </article>

    <!-- Pangkak Kedebong Malang -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform">
      <img src="assets/oar2.jpeg" alt="Pangkak Kedebong Malang" class="h-48 w-full object-cover object-top">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Pangkak Kedebong Malang</h3>
        <p class="text-sm">Pangkak kedebong malang mengandung makna bahwa pemakainya diharapkan mempunyai ketetapan hati yang kokoh.</p>
        
        <div class="details-container">
          <details>
            <summary>
              <span>Baca lebih lengkap</span>
              <span class="details-arrow">▼</span>
            </summary>
            <div class="details-content">
              <p class="mt-2">Bentuk pangkak kedebong malang ini menyerupai angka delapan dengan ukuran lebar kurang lebih 20 cm dan tinggi 8 cm.</p>
            </div>
          </details>
        </div>
      </div>
    </article>

    <!-- Lenteran Suku -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform">
      <img src="assets/payas.jpeg" alt="Lenteran Suku" class="h-48 w-full object-cover object-top">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Lenteran Suku - Suku & Lenteran</h3>
        <p class="text-sm">Mempunyai makna simbolik akan kesuburan. Wanita yang subur bagi orang sasak, karena dapat memberikan keturunan yang banyak.</p>
        
        <div class="details-container">
          <details>
            <summary>
              <span>Baca lebih lengkap</span>
              <span class="details-arrow">▼</span>
            </summary>
            <div class="details-content">
              <p class="mt-2">Mempunyai makna simbolik akan kesuburan, sama dengan lenteran suku- suku. Wanita yang subur bagi orang sasak terutama pada zaman dahulu dianggap sebagai wanita ideal.</p>
            </div>
          </details>
        </div>
      </div>
    </article>

    <!-- Pending/Sabuk Emas -->
    <article class="card bg-white rounded-lg overflow-hidden shadow transition-transform">
      <img src="assets/penganten.jpeg" alt="Pending/Sabuk Emas" class="h-48 w-full object-cover object-top">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg">Pending/Sabuk Emas</h3>
        <p class="text-sm">Pending merupakan perhiasan yang berharga, biasanya dipergunakan di pinggang sebagai sabuk pengantin.</p>
        
        <div class="details-container">
          <details>
            <summary>
              <span>Baca lebih lengkap</span>
              <span class="details-arrow">▼</span>
            </summary>
            <div class="details-content">
              <p class="mt-2">Bagian kepala sabuknya terdapat sebuah permata terbuat dari intan yang berukuran lebih besar terdapat ditengah-tengahnya sehingga menciptakan keserasian dan keindahan tersendiri.</p>
            </div>
          </details>
        </div>
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

    // Enhanced details behavior
    document.querySelectorAll('details').forEach(detail => {
      detail.addEventListener('click', function(e) {
        // Prevent default behavior on arrow click
        if (e.target.classList.contains('details-arrow')) {
          e.preventDefault();
          this.open = !this.open;
        }
        
        // Close other details when one is opened
        if (this.open) {
          document.querySelectorAll('details').forEach(otherDetail => {
            if (otherDetail !== this) {
              otherDetail.open = false;
            }
          });
        }
      });
    });

    // Animation for cards
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate-fadeIn');
          entry.target.style.opacity = '1';
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