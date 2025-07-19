<?php
session_start();

// Redirect jika tidak ada session user_id
if (!isset($_SESSION['user_id'])) {
    header('Location: regis.php');
    exit;
}

// Ambil data user dari session
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi Berhasil - Sasak Heritage</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      padding: 20px;
    }
    
    .success-container {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      padding: 40px;
      max-width: 500px;
      width: 100%;
      text-align: center;
    }
    
    .success-icon {
      font-size: 80px;
      color: #10b981;
      margin-bottom: 20px;
    }
    
    h1 {
      color: #78350f;
      margin-bottom: 15px;
    }
    
    p {
      color: #6b7280;
      margin-bottom: 25px;
    }
    
    .btn {
      display: inline-block;
      background-color: #78350f;
      color: white;
      padding: 12px 24px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 500;
      transition: background 0.3s;
    }
    
    .btn:hover {
      background-color: #92400e;
    }
  </style>
</head>
<body>
  <div class="success-container">
    <div class="success-icon">✓</div>
    <h1>Registrasi Berhasil!</h1>
    <p>Selamat datang <strong><?php echo htmlspecialchars($username); ?></strong>, akun Anda telah berhasil dibuat.</p>
    <p>Anda sekarang dapat menikmati semua fitur Sasak Heritage.</p>
    <a href="index.php" class="btn">Mulai Jelajahi</a>
  </div>
</body>
</html>