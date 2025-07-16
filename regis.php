<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $email = trim($_POST['email']);
    $full_name = trim($_POST['full_name']);
    
    // Check if username or email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $check->bind_param('ss', $username, $email);
    $check->execute();
    $check_result = $check->get_result();
    
    if ($check_result->num_rows > 0) {
        $_SESSION['register_error'] = 'Username atau email sudah terdaftar';
        header('Location: register.php');
        exit;
    }
    
    // Insert new user (in a real app, you should hash the password)
    $insert = $conn->prepare("INSERT INTO users (username, password, email, full_name, role) VALUES (?, ?, ?, ?, 'user')");
    $insert->bind_param('ssss', $username, $password, $email, $full_name);
    
    if ($insert->execute()) {
        $_SESSION['register_success'] = 'Registrasi berhasil! Silakan login.';
        header('Location: login.php');
        exit;
    } else {
        $_SESSION['register_error'] = 'Registrasi gagal. Silakan coba lagi.';
        header('Location: register.php');
        exit;
    }
}

// If not POST request, show registration form
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Sasak Heritage</title>
  <style>
    /* Your existing CSS styles */
    /* ... */
  </style>
</head>
<body>
  <div class="container">
    <div class="left">
      <img src="assets/sasak_register.jpg" alt="Registration Image">
    </div>
    <div class="right">
      <h2>Daftar Akun Baru</h2>
      <p class="form-title">Bergabung dengan Sasak Heritage</p>
      
      <?php if (isset($_SESSION['register_error'])): ?>
        <div style="color: red; margin-bottom: 15px; text-align: center;">
          <?php echo $_SESSION['register_error']; unset($_SESSION['register_error']); ?>
        </div>
      <?php endif; ?>
      
      <form action="register.php" method="POST">
        <input type="text" name="full_name" placeholder="Nama Lengkap" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" class="btn primary">Daftar</button>
        <div class="bottom-links">
          <span>Sudah punya akun?</span>
          <a href="login.php">Login disini</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>