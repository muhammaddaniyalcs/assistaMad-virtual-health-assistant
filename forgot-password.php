<?php
$success = isset($_GET['success']) ? true : false;
$errorType = $_GET['error'] ?? null;
?>

<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
<div class="login-container">
  <div class="right-panel">
    <div class="form-box">
      <h2>Forgot Password</h2>

      <?php if ($success): ?>
        <div class="alert success">A reset link has been sent to your email address.</div>
      <?php elseif ($errorType === 'notfound'): ?>
        <div class="alert error">Email not found.</div>
      <?php elseif ($errorType === 'invalid'): ?>
        <div class="alert error">Invalid email format.</div>
      <?php endif; ?>

      <form action="send-reset.php" method="POST">
        <div class="input-group">
          <input type="email" name="email" placeholder="Enter your email" required />
        </div>
        <button type="submit" class="login-btn">Send Reset Link</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
