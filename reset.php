<?php
require_once 'db.php';

if (!isset($_SESSION['reset_token']) || !isset($_SESSION['reset_user'])) {
    header('Location: forgot.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $username = $_SESSION['reset_user'];
    
    // Validate passwords match
    if ($new_password !== $confirm_password) {
        $_SESSION['reset_error'] = 'Password tidak cocok';
        header('Location: reset.php');
        exit;
    }
    
    // Update password (in a real app, you should hash this)
    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE username = ?");
    $stmt->bind_param('ss', $new_password, $username);
    
    if ($stmt->execute()) {
        unset($_SESSION['reset_token']);
        unset($_SESSION['reset_user']);
        $_SESSION['reset_success'] = 'Password berhasil diubah. Silakan login.';
        header('Location: login.php');
        exit;
    } else {
        $_SESSION['reset_error'] = 'Gagal mengubah password';
        header('Location: reset.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <!-- Your existing head content -->
  <!-- ... -->
</head>
<body>
  <div class="container">
    <div class="left">
      <img src="assets/sasak_reset.jpg" alt="Reset Password">
    </div>
    <div class="right">
      <h2>Reset Password</h2>
      <p class="form-title">Masukkan password baru Anda</p>
      
      <?php if (isset($_SESSION['reset_error'])): ?>
        <div style="color: red; margin-bottom: 15px; text-align: center;">
          <?php echo $_SESSION['reset_error']; unset($_SESSION['reset_error']); ?>
        </div>
      <?php endif; ?>
      
      <form action="reset.php" method="POST">
        <input type="password" name="new_password" placeholder="Password Baru" required>
        <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
        <button type="submit" class="btn primary">Reset Password</button>
        <div class="bottom-links">
          <a href="login.php">Kembali ke Login</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>