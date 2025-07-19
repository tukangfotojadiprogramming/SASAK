<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Database connection (MySQLi)
require_once 'db.php';

// Get user data
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id); // "i" for integer
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Get all destinations from database
$result = $conn->query("SELECT * FROM destinations");
$destinations = [];
while ($row = $result->fetch_assoc()) {
    $destinations[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BALEQARA – SASAK WISATA</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body { font-family: 'Poppins', sans-serif; }
    .card:hover { transform: translateY(-4px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    .card p { text-align: justify; }
  </style>
</head>
<body class="bg-amber-50 text-amber-900">
  <!-- Header -->
  <header class="bg-amber-900 text-white p-4 shadow-md flex items-center justify-between relative">
    <a href="index.php" class="text-lg font-bold flex items-center gap-2 hover:text-amber-200 absolute left-4 top-1/2 -translate-y-1/2">
      <span class="text-2xl leading-none">←</span>
      <span class="hidden sm:inline">kembali</span>
    </a>
    <h1 class="font-bold text-xl text-center w-full">SASAK WISATA</h1>
    <span class="text-sm absolute right-4 top-1/2 -translate-y-1/2"><?php echo htmlspecialchars($user['username']); ?></span>
  </header>

  <!-- Intro -->
  <section class="max-w-4xl mx-auto px-4 py-8 text-center">
    <h2 class="text-2xl font-bold mb-2">Yuk Jelajahi <span class="text-amber-700">Destinasi & Cerita</span></h2>
    <p class="text-sm text-gray-700">Jelajahi keindahan alam NTB sambil menyelami cerita rakyat, makna adat, dan filosofi yang hidup di setiap sudut tanah Sasak.</p>
  </section>

  <!-- Grid Destinasi -->
  <section class="max-w-6xl mx-auto grid sm:grid-cols-2 lg:grid-cols-3 gap-6 px-4 pb-24">
    <?php foreach ($destinations as $destination): ?>
    <article id="<?php echo htmlspecialchars(strtolower(str_replace(' ', '-', $destination['name']))); ?>" 
             class="card bg-white rounded-lg overflow-hidden shadow transition-transform">
      <img src="<?php echo htmlspecialchars($destination['image_url']); ?>" 
           alt="<?php echo htmlspecialchars($destination['name']); ?>" 
           class="h-48 w-full object-cover">
      <div class="p-4 space-y-2">
        <h3 class="font-semibold text-lg"><?php echo htmlspecialchars($destination['name']); ?></h3>
        <p class="text-sm"><?php echo htmlspecialchars($destination['short_description']); ?></p>
        <details class="text-sm">
          <summary class="cursor-pointer text-amber-700 font-medium">Baca cerita lengkap</summary>
          <p class="mt-2"><?php echo htmlspecialchars($destination['long_description']); ?></p>
        </details>
        <?php if (!empty($destination['video_url'])): ?>
          <a href="<?php echo htmlspecialchars($destination['video_url']); ?>" 
             class="inline-block mt-2 text-xs underline" target="_blank">🎥 Tonton video</a>
        <?php endif; ?>
       
      </div>
    </article>
    <?php endforeach; ?>
  </section>

  <script>
    // Handle details toggle
    document.querySelectorAll('details').forEach((detail) => {
      const summary = detail.querySelector('summary');
      const originalText = summary.textContent;

      detail.addEventListener('toggle', () => {
        if (detail.open) {
          summary.textContent = "Sembunyikan Bacaan Lengkap";
        } else {
          summary.textContent = originalText;
        }
      });
    });

    // Check for URL hash and scroll to destination
    window.addEventListener('load', function() {
      if (window.location.hash) {
        const element = document.querySelector(window.location.hash);
        if (element) {
          element.scrollIntoView({ behavior: 'smooth' });
        }
      }
    });
  </script>
</body>
</html>