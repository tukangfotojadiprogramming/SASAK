<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Bahasa & Ungkapan Sasak</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
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
    .kamus-card {
      background: linear-gradient(135deg, rgba(255,255,255,0.97) 0%, rgba(254,243,236,0.97) 100%);
      border: 1px solid rgba(251, 146, 60, 0.2);
      box-shadow: 0 15px 35px rgba(0,0,0,0.08);
      backdrop-filter: blur(12px);
      border-radius: 18px;
      overflow: hidden;
      position: relative;
      border-top: 4px solid #b45309;
    }
    .kamus-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 10px;
      background: linear-gradient(90deg, #b45309, #92400e, #b45309);
      opacity: 0.8;
    }
    .search-box {
      background: rgba(255, 255, 255, 0.85);
      border: 1px solid rgba(251, 146, 60, 0.4);
      transition: all 0.3s ease;
      box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .search-box:focus {
      box-shadow: 0 0 0 3px rgba(251, 146, 60, 0.2), 0 4px 12px rgba(251, 146, 60, 0.1);
      border-color: rgba(251, 146, 60, 0.6);
    }
    .kamus-item {
      transition: all 0.25s ease;
      border-left: 4px solid transparent;
      background: rgba(255, 255, 255, 0.7);
      margin: 6px 0;
      border-radius: 10px;
    }
    .kamus-item:hover {
      background: rgba(254, 243, 236, 0.8);
      border-left: 4px solid #b45309;
      transform: translateX(5px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    .kamus-term {
      color: #92400e;
      font-weight: 600;
      letter-spacing: 0.5px;
    }
    .kamus-definition {
      color: #431407;
      font-size: 0.9rem;
      line-height: 1.5;
    }
    .no-results {
      background: rgba(254, 226, 226, 0.6);
      border-left: 4px solid #ef4444;
      border-radius: 10px;
      padding: 16px;
    }
    .kamus-icon {
      background: linear-gradient(135deg, #b45309, #92400e);
      color: white;
      box-shadow: 0 4px 8px rgba(180, 83, 9, 0.2);
    }
    .kamus-header-icon {
      background: linear-gradient(135deg, #b45309, #92400e);
      color: white;
      box-shadow: 0 6px 12px rgba(180, 83, 9, 0.25);
      width: 50px;
      height: 50px;
    }
    .kamus-divider {
      border-top: 1px dashed rgba(180, 83, 9, 0.3);
      margin: 8px 0;
    }
    .kamus-count {
      background: rgba(180, 83, 9, 0.1);
      color: #92400e;
      border-radius: 20px;
      padding: 2px 10px;
      font-size: 0.8rem;
      font-weight: 600;
    }
    .kamus-footer {
      background: rgba(254, 243, 236, 0.8);
      border-top: 1px solid rgba(251, 146, 60, 0.2);
      padding: 12px;
      border-radius: 0 0 18px 18px;
    }
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
<body class="min-h-screen flex flex-col">
  <!-- Header with Back Button -->
  <header class="header-gradient text-white p-4 shadow-md flex items-center justify-between relative">
    <a href="katalog.php" class="text-lg font-bold flex items-center gap-2 hover:text-amber-200 absolute left-4 top-1/2 -translate-y-1/2">
      <span class="text-2xl leading-none">←</span>
      <span class="hidden sm:inline">Kembali</span>
    </a>
    <h1 class="font-bold text-xl text-center w-full">Bahasa & Ungkapan</h1>
  </header>

 

  <!-- Konten -->
  <main class="flex-grow max-w-6xl mx-auto px-4 py-12 relative z-10">
    <h2 class="text-3xl font-bold text-center mb-12">Eksplorasi Warisan Budaya <span class="text-amber-700 title-highlight">Sasak</span></h2>

    <!-- Enhanced Kamus Section -->
    <div class="kamus-card p-8 mb-12">
      <div class="flex items-center justify-between mb-6">
        <div>
          <div class="flex items-center gap-3 mb-2">
            <h3 class="text-2xl font-bold text-amber-900">Kamus Bahasa Halus Sasak</h3>
          </div>
          <p class="text-amber-700 text-sm">Temukan makna kata-kata dalam bahasa Sasak yang halus dan penuh makna</p>
        </div>
        <div class="kamus-header-icon rounded-full flex items-center justify-center">
          <i class="fas fa-scroll text-xl"></i>
        </div>
      </div>
      
      <div class="relative mb-6">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <i class="fas fa-search text-amber-500"></i>
        </div>
        <input 
          type="text" 
          id="searchInput" 
          placeholder="Cari kata dalam bahasa Sasak..." 
          class="search-box w-full pl-10 pr-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-300"
        >
      </div>
      
      <div class="overflow-hidden rounded-lg">
        <ul id="kamusList" class="space-y-2">
          <li class="kamus-item p-4">
            <div class="flex items-start">
              <div class="kamus-icon p-2 rounded-full mr-3 mt-1">
                <i class="fas fa-language text-sm"></i>
              </div>
              <div>
                <span class="kamus-term block font-medium">Tiang</span>
                <span class="kamus-definition block">Saya (halus) - Digunakan untuk menyapa dengan sopan</span>
                <div class="kamus-divider"></div>
                <span class="text-xs text-amber-600 italic">Contoh: "Tiang nunas ampura" (Saya minta maaf)</span>
              </div>
            </div>
          </li>
          <li class="kamus-item p-4">
            <div class="flex items-start">
              <div class="kamus-icon p-2 rounded-full mr-3 mt-1">
                <i class="fas fa-language text-sm"></i>
              </div>
              <div>
                <span class="kamus-term block font-medium">Pelungguh</span>
                <span class="kamus-definition block">Anda (halus) - Sapaan hormat kepada lawan bicara</span>
                <div class="kamus-divider"></div>
                <span class="text-xs text-amber-600 italic">Contoh: "Pelungguh mripat?" (Anda mau pergi?)</span>
              </div>
            </div>
          </li>
          <li class="kamus-item p-4">
            <div class="flex items-start">
              <div class="kamus-icon p-2 rounded-full mr-3 mt-1">
                <i class="fas fa-language text-sm"></i>
              </div>
              <div>
                <span class="kamus-term block font-medium">Deq</span>
                <span class="kamus-definition block">Tidak - Penolakan dengan nada halus</span>
                <div class="kamus-divider"></div>
                <span class="text-xs text-amber-600 italic">Contoh: "Deq tegaq" (Tidak mau)</span>
              </div>
            </div>
          </li>
          <li class="kamus-item p-4">
            <div class="flex items-start">
              <div class="kamus-icon p-2 rounded-full mr-3 mt-1">
                <i class="fas fa-language text-sm"></i>
              </div>
              <div>
                <span class="kamus-term block font-medium">Enggih</span>
                <span class="kamus-definition block">Iya (halus) - Persetujuan dengan sopan</span>
                <div class="kamus-divider"></div>
                <span class="text-xs text-amber-600 italic">Contoh: "Enggih, tiang ngerti" (Ya, saya mengerti)</span>
              </div>
            </div>
          </li>
          <li class="kamus-item p-4">
            <div class="flex items-start">
              <div class="kamus-icon p-2 rounded-full mr-3 mt-1">
                <i class="fas fa-language text-sm"></i>
              </div>
              <div>
                <span class="kamus-term block font-medium">Dados</span>
                <span class="kamus-definition block">Jadi - Menyatakan kesediaan atau kemampuan</span>
                <div class="kamus-divider"></div>
                <span class="text-xs text-amber-600 italic">Contoh: "Dados mangkin" (Bisa sekarang)</span>
              </div>
            </div>
          </li>
          <li class="kamus-item p-4">
            <div class="flex items-start">
              <div class="kamus-icon p-2 rounded-full mr-3 mt-1">
                <i class="fas fa-language text-sm"></i>
              </div>
              <div>
                <span class="kamus-term block font-medium">Jari</span>
                <span class="kamus-definition block">Jadi (bentuk lain) - Variasi dari 'dados'</span>
                <div class="kamus-divider"></div>
                <span class="text-xs text-amber-600 italic">Contoh: "Jari keto" (Jadi begitu)</span>
              </div>
            </div>
          </li>
          <li class="kamus-item p-4">
            <div class="flex items-start">
              <div class="kamus-icon p-2 rounded-full mr-3 mt-1">
                <i class="fas fa-language text-sm"></i>
              </div>
              <div>
                <span class="kamus-term block font-medium">Manjari</span>
                <span class="kamus-definition block">Menjadi - Proses perubahan keadaan</span>
                <div class="kamus-divider"></div>
                <span class="text-xs text-amber-600 italic">Contoh: "Manjari guru" (Menjadi guru)</span>
              </div>
            </div>
          </li>
          <li class="kamus-item p-4">
            <div class="flex items-start">
              <div class="kamus-icon p-2 rounded-full mr-3 mt-1">
                <i class="fas fa-language text-sm"></i>
              </div>
              <div>
                <span class="kamus-term block font-medium">Kemai</span>
                <span class="kamus-definition block">Kemari - Memanggil seseorang mendekat</span>
                <div class="kamus-divider"></div>
                <span class="text-xs text-amber-600 italic">Contoh: "Kemai mriki" (Kemarilah ke sini)</span>
              </div>
            </div>
          </li>
          <li class="kamus-item p-4">
            <div class="flex items-start">
              <div class="kamus-icon p-2 rounded-full mr-3 mt-1">
                <i class="fas fa-language text-sm"></i>
              </div>
              <div>
                <span class="kamus-term block font-medium">Lauk</span>
                <span class="kamus-definition block">Laut - Menyebut wilayah perairan</span>
                <div class="kamus-divider"></div>
                <span class="text-xs text-amber-600 italic">Contoh: "Tiang meli lauk" (Saya membeli ikan laut)</span>
              </div>
            </div>
          </li>
          <li class="kamus-item p-4">
            <div class="flex items-start">
              <div class="kamus-icon p-2 rounded-full mr-3 mt-1">
                <i class="fas fa-language text-sm"></i>
              </div>
              <div>
                <span class="kamus-term block font-medium">Gumi</span>
                <span class="kamus-definition block">Bumi / tanah - Menyebut tanah atau wilayah</span>
                <div class="kamus-divider"></div>
                <span class="text-xs text-amber-600 italic">Contoh: "Gumi Sasak" (Tanah Sasak)</span>
              </div>
            </div>
          </li>
          <li class="kamus-item p-4">
            <div class="flex items-start">
              <div class="kamus-icon p-2 rounded-full mr-3 mt-1">
                <i class="fas fa-language text-sm"></i>
              </div>
              <div>
                <span class="kamus-term block font-medium">Sampun</span>
                <span class="kamus-definition block">Sudah - Menyatakan penyelesaian</span>
                <div class="kamus-divider"></div>
                <span class="text-xs text-amber-600 italic">Contoh: "Sampun maem?" (Sudah makan?)</span>
              </div>
            </div>
          </li>
          <li class="kamus-item p-4">
            <div class="flex items-start">
              <div class="kamus-icon p-2 rounded-full mr-3 mt-1">
                <i class="fas fa-language text-sm"></i>
              </div>
              <div>
                <span class="kamus-term block font-medium">Mangkin</span>
                <span class="kamus-definition block">Sekarang - Menyatakan waktu saat ini</span>
                <div class="kamus-divider"></div>
                <span class="text-xs text-amber-600 italic">Contoh: "Mangkin jadine" (Sekarang jadinya)</span>
              </div>
            </div>
          </li>
          <li class="kamus-item p-4">
            <div class="flex items-start">
              <div class="kamus-icon p-2 rounded-full mr-3 mt-1">
                <i class="fas fa-language text-sm"></i>
              </div>
              <div>
                <span class="kamus-term block font-medium">Nunas</span>
                <span class="kamus-definition block">Meminta - Permohonan dengan sopan</span>
                <div class="kamus-divider"></div>
                <span class="text-xs text-amber-600 italic">Contoh: "Tiang nunas tulung" (Saya minta tolong)</span>
              </div>
            </div>
          </li>
          <li class="kamus-item p-4">
            <div class="flex items-start">
              <div class="kamus-icon p-2 rounded-full mr-3 mt-1">
                <i class="fas fa-language text-sm"></i>
              </div>
              <div>
                <span class="kamus-term block font-medium">Tiang tresna</span>
                <span class="kamus-definition block">Saya cinta - Ungkapan perasaan</span>
                <div class="kamus-divider"></div>
                <span class="text-xs text-amber-600 italic">Contoh: "Tiang tresna ring pelungguh" (Saya cinta padamu)</span>
              </div>
            </div>
          </li>
          <li class="kamus-item p-4">
            <div class="flex items-start">
              <div class="kamus-icon p-2 rounded-full mr-3 mt-1">
                <i class="fas fa-language text-sm"></i>
              </div>
              <div>
                <span class="kamus-term block font-medium">Sukat</span>
                <span class="kamus-definition block">Cukup - Menyatakan kecukupan</span>
                <div class="kamus-divider"></div>
                <span class="text-xs text-amber-600 italic">Contoh: "Sukat mangkin" (Cukup sekarang)</span>
              </div>
            </div>
          </li>
        </ul>
        <div id="noResults" class="no-results p-4 text-center text-red-600 hidden">
          <i class="fas fa-exclamation-circle mr-2"></i> Kata tidak ditemukan dalam kamus kami
        </div>
      </div>
      
      <div class="kamus-footer flex justify-between items-center mt-4">
        <div class="text-sm text-amber-700">
          <i class="fas fa-info-circle mr-1"></i> Total 15 istilah budaya
        </div>
      </div>
    </div>
  </main>

  <footer class="bg-amber-900 text-white text-sm text-center py-3">
    <p>&copy; 2025 BALEQARA | Dilestarikan dengan cinta budaya.</p>
  </footer>
  

  <script>
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const kamusList = document.getElementById('kamusList');
    const listItems = kamusList.querySelectorAll('li');
    const noResults = document.getElementById('noResults');
    
    searchInput.addEventListener('input', function() {
      const filter = this.value.toLowerCase();
      let hasResults = false;
      let visibleCount = 0;
      
      listItems.forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(filter)) {
          item.style.display = 'flex';
          hasResults = true;
          visibleCount++;
        } else {
          item.style.display = 'none';
        }
      });
      
      // Update count display
      document.querySelector('.kamus-count').textContent = `${visibleCount}+ istilah`;
      
      if (hasResults) {
        noResults.classList.add('hidden');
      } else {
        noResults.classList.remove('hidden');
      }
    });

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