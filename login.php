<?php
session_start();
require 'db_config.php'; // Ganti sesuai path ke file koneksi MySQL

// Tangani proses pendaftaran
if (isset($_POST['register'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        echo "<script>alert('Password tidak cocok!'); window.location.href='login.php';</script>";
        exit;
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, phone, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $firstname, $lastname, $email, $phone, $hashed);

    if ($stmt->execute()) {
        echo "<script>alert('Matur Tampiasih Telah Daftar!'); window.location.href='login.php';</script>";
        exit;
    } else {
        echo "<script>alert('Email sudah digunakan.'); window.location.href='login.php';</script>";
        exit;
    }
}

// Tangani proses login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['firstname'];
        header("Location: SASAK.html");
        exit;
    } else {
        echo "<script>alert('Email atau password salah!'); window.location.href='login.php';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sasak Heritage Explorer - Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .batik-pattern {
      background-image: url('https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/404dc402-5e4a-447f-a820-a00961e3f76f.png');
      background-size: cover;
      opacity: 0.1;
    }
    .auth-container {
      background-color: rgba(245, 243, 240, 0.9);
    }
    .auth-tab.active {
      background-color: #92400e;
      color: white;
    }
  </style>
</head>
<body class="bg-amber-50 font-sans min-h-screen flex items-center justify-center p-4">
  <div class="auth-container rounded-xl shadow-xl overflow-hidden w-full max-w-md relative">
    <div class="batik-pattern absolute inset-0"></div>

    <!-- Tabs -->
    <div class="relative z-10 flex border-b border-amber-200">
      <button id="login-tab" class="auth-tab active flex-1 py-4 font-medium text-amber-900">Login</button>
      <button id="register-tab" class="auth-tab flex-1 py-4 font-medium text-amber-900">Daftar</button>
    </div>

    <!-- Login Form -->
    <form method="POST" id="login-form" class="p-8 relative z-10">
      <h2 class="text-2xl font-bold text-amber-900 mb-6 text-center">Selamat Datang</h2>
      <div class="mb-4">
        <label class="block text-sm mb-2 text-amber-900">Email</label>
        <input type="email" name="email" required class="w-full px-3 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500">
      </div>
      <div class="mb-6">
        <label class="block text-sm mb-2 text-amber-900">Password</label>
        <input type="password" name="password" required class="w-full px-3 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500">
      </div>
      <button type="submit" name="login" class="w-full bg-amber-700 text-white py-3 rounded-lg hover:bg-amber-800 transition">
        Masuk
      </button>
      <div class="mt-6 text-center">
        <p class="text-sm text-amber-800">
          Belum punya akun?
          <a href="#" class="text-amber-700 font-medium hover:text-amber-800" id="switch-to-register">Daftar disini</a>
        </p>
      </div>
    </form>

    <!-- Register Form -->
    <form method="POST" id="register-form" class="hidden p-8 relative z-10">
      <h2 class="text-2xl font-bold text-amber-900 mb-6 text-center">Buat Akun Baru</h2>
      <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
          <label class="block text-sm mb-2 text-amber-900">Nama Depan</label>
          <input type="text" name="firstname" required class="w-full px-3 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500">
        </div>
        <div>
          <label class="block text-sm mb-2 text-amber-900">Nama Belakang</label>
          <input type="text" name="lastname" class="w-full px-3 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500">
        </div>
      </div>
      <div class="mb-4">
        <label class="block text-sm mb-2 text-amber-900">Email</label>
        <input type="email" name="email" required class="w-full px-3 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500">
      </div>
      <div class="mb-4">
        <label class="block text-sm mb-2 text-amber-900">Nomor HP</label>
        <input type="tel" name="phone" required class="w-full px-3 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500">
      </div>
      <div class="mb-4">
        <label class="block text-sm mb-2 text-amber-900">Password</label>
        <input type="password" name="password" required class="w-full px-3 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500">
        <p class="mt-1 text-xs text-amber-600">Minimal 8 karakter dengan kombinasi huruf dan angka</p>
      </div>
      <div class="mb-6">
        <label class="block text-sm mb-2 text-amber-900">Konfirmasi Password</label>
        <input type="password" name="confirm_password" required class="w-full px-3 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500">
      </div>
      <button type="submit" name="register" class="w-full bg-amber-700 text-white py-3 rounded-lg hover:bg-amber-800 transition">
        Daftar Sekarang
      </button>
      <div class="mt-6 text-center">
        <p class="text-sm text-amber-800">
          Sudah punya akun?
          <a href="#" class="text-amber-700 font-medium hover:text-amber-800" id="switch-to-login">Masuk disini</a>
        </p>
      </div>
    </form>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const loginTab = document.getElementById('login-tab');
      const registerTab = document.getElementById('register-tab');
      const loginForm = document.getElementById('login-form');
      const registerForm = document.getElementById('register-form');
      const switchToRegister = document.getElementById('switch-to-register');
      const switchToLogin = document.getElementById('switch-to-login');

      function switchToLoginView() {
        loginTab.classList.add('active');
        registerTab.classList.remove('active');
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');
      }

      function switchToRegisterView() {
        loginTab.classList.remove('active');
        registerTab.classList.add('active');
        loginForm.classList.add('hidden');
        registerForm.classList.remove('hidden');
      }

      loginTab.addEventListener('click', switchToLoginView);
      registerTab.addEventListener('click', switchToRegisterView);
      if (switchToRegister) switchToRegister.addEventListener('click', (e) => { e.preventDefault(); switchToRegisterView(); });
      if (switchToLogin) switchToLogin.addEventListener('click', (e) => { e.preventDefault(); switchToLoginView(); });
    });
  </script>
</body>
</html>
