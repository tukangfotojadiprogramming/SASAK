<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $email = trim($_POST['email']);
    $full_name = trim($_POST['full_name']);
    
    // Validate inputs
    if (empty($username) || empty($password) || empty($email) || empty($full_name)) {
        $_SESSION['register_error'] = 'Semua field harus diisi';
        header('Location: register.php');
        exit;
    }

    // Check if username or email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $check->bind_param('ss', $username, $email);
    $check->execute();
    $check_result = $check->get_result();
    
    if ($check_result->num_rows > 0) {
        $_SESSION['register_error'] = 'Username atau email sudah terdaftar';
        header('Location: login.php');
        exit;
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    $insert = $conn->prepare("INSERT INTO users (username, password, email, full_name, role) VALUES (?, ?, ?, ?, 'user')");
    $insert->bind_param('ssss', $username, $hashed_password, $email, $full_name);
    
    if ($insert->execute()) {
        $_SESSION['register_success'] = 'Registrasi berhasil! Silakan login.';
        header('Location: login.php');
        exit;
    } else {
        $_SESSION['register_error'] = 'Registrasi gagal. Silakan coba lagi.';
        header('Location: regis.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Sasak Heritage</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }
    
    body {
      background-color: #f8f9fa;
      color: #333;
      line-height: 1.6;
    }
    
    .container {
      display: flex;
      min-height: 100vh;
    }
    
    .left {
      flex: 1;
      background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('assets/sasak.jpg');
      background-size: cover;
      background-position: center;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
      color: white;
      position: relative;
    }
    
    .left::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(120, 53, 15, 0.8), rgba(245, 158, 11, 0.6));
    }
    
    .left-content {
      position: relative;
      z-index: 1;
      max-width: 500px;
      text-align: center;
    }
    
    .left h1 {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      font-weight: 600;
    }
    
    .left p {
      font-size: 1.1rem;
      opacity: 0.9;
    }
    
    .right {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 3rem;
      max-width: 600px;
      margin: 0 auto;
    }
    
    .right h2 {
      font-size: 2rem;
      color: #78350f;
      margin-bottom: 0.5rem;
      font-weight: 600;
    }
    
    .form-title {
      color: #6b7280;
      margin-bottom: 2rem;
      font-size: 1rem;
    }
    
    form {
      width: 100%;
    }
    
    .form-group {
      margin-bottom: 1.5rem;
      position: relative;
    }
    
    input {
      width: 100%;
      padding: 0.8rem 1rem;
      border: 1px solid #d1d5db;
      border-radius: 8px;
      font-size: 1rem;
      transition: all 0.3s ease;
    }
    
    input:focus {
      outline: none;
      border-color: #f59e0b;
      box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
    }
    
    .btn {
      width: 100%;
      padding: 0.8rem;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .btn.primary {
      background-color: #78350f;
      color: white;
    }
    
    .btn.primary:hover {
      background-color: #92400e;
    }
    
    .bottom-links {
      margin-top: 1.5rem;
      text-align: center;
      color: #6b7280;
    }
    
    .bottom-links a {
      color: #78350f;
      text-decoration: none;
      font-weight: 500;
      margin-left: 0.5rem;
    }
    
    .bottom-links a:hover {
      text-decoration: underline;
    }
    
    .alert {
      padding: 0.8rem 1rem;
      border-radius: 8px;
      margin-bottom: 1.5rem;
      text-align: center;
    }
    
    .alert.error {
      background-color: #fee2e2;
      color: #b91c1c;
      border: 1px solid #fca5a5;
    }
    
    .alert.success {
      background-color: #dcfce7;
      color: #166534;
      border: 1px solid #86efac;
    }
    
    @media (max-width: 768px) {
      .container {
        flex-direction: column;
      }
      
      .left {
        padding: 3rem 1rem;
      }
      
      .right {
        padding: 2rem 1.5rem;
      }
    }
    
    .password-toggle {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #6b7280;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="left">
      <div class="left-content">
        <h1>Sasak Heritage</h1>
        <p>Bergabunglah dengan kami untuk menjelajahi kekayaan budaya Sasak</p>
      </div>
    </div>
    <div class="right">
      <h2>Daftar Akun Baru</h2>
      <p class="form-title">Bergabung dengan Sasak Heritage</p>
      
      <?php if (isset($_SESSION['register_error'])): ?>
        <div class="alert error">
          <?php echo $_SESSION['register_error']; unset($_SESSION['register_error']); ?>
        </div>
      <?php endif; ?>
      
      <?php if (isset($_SESSION['register_success'])): ?>
        <div class="alert success">
          <?php echo $_SESSION['register_success']; unset($_SESSION['register_success']); ?>
        </div>
      <?php endif; ?>
      
      <form action="register.php" method="POST" autocomplete="off">
        <div class="form-group">
          <input type="text" name="full_name" placeholder="Nama Lengkap" required>
        </div>
        
        <div class="form-group">
          <input type="text" name="username" placeholder="Username" required>
        </div>
        
        <div class="form-group">
          <input type="email" name="email" placeholder="Email" required>
        </div>
        
        <div class="form-group" style="position: relative;">
          <input type="password" name="password" id="password" placeholder="Password" required>
          <span class="password-toggle" onclick="togglePassword()">👁️</span>
        </div>
        
        <button type="submit" class="btn primary">Daftar</button>
        
        <div class="bottom-links">
          <span>Sudah punya akun?</span>
          <a href="login.php">Login disini</a>
        </div>
      </form>
    </div>
  </div>

  <script>
    function togglePassword() {
      const passwordField = document.getElementById('password');
      if (passwordField.type === 'password') {
        passwordField.type = 'text';
      } else {
        passwordField.type = 'password';
      }
    }
  </script>
</body>
</html>