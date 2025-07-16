<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    
    // Check if email exists
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Generate reset token (in a real app, you would send this via email)
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Store token in database
        $conn->query("UPDATE users SET reset_token = '$token', reset_expires = '$expires' WHERE id = {$user['id']}");
        
        // In a real app, you would send an email with the reset link
        $_SESSION['reset_token'] = $token;
        $_SESSION['reset_user'] = $user['username'];
        
        header('Location: reset.php');
        exit;
    } else {
        $_SESSION['reset_error'] = 'Email tidak terdaftar';
        header('Location: forgot.php');
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
      <img src="assets/sasak_forgot.jpg" alt="Forgot Password">
    </div>
    <div class="right">
      <h2>Lupa Password</h2>
      <p class="form-title">Masukkan email Anda</p>
      
      <?php if (isset($_SESSION['reset_error'])): ?>
        <div style="color: red; margin-bottom: 15px; text-align: center;">
          <?php echo $_SESSION['reset_error']; unset($_SESSION['reset_error']); ?>
        </div>
      <?php endif; ?>
      
      <form action="forgot.php" method="POST">
        <input type="email" name="email" placeholder="Email Anda" required>
        <button type="submit" class="btn primary">Kirim Link Reset</button>
        <div class="bottom-links">
          <a href="login.php">Kembali ke Login</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>