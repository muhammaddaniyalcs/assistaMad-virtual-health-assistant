<?php
session_start();
require 'config.php';

$token = $_GET['token'] ?? '';
$valid = false;
$email = '';

// Check token validity
if ($token) {
    $stmt = $pdo->prepare("SELECT email, expires_at FROM password_resets WHERE token = ?");
    $stmt->execute([$token]);
    $row = $stmt->fetch();

    if ($row && strtotime($row['expires_at']) > time()) {
        $valid = true;
        $email = $row['email'];
    }
}

// Handle password reset form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'], $_POST['confirm_password'])) {
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm_password']);

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // Update password
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$hashed, $_POST['email']]);

        // Delete reset token
        $stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
        $stmt->execute([$_POST['email']]);

        $success = "Password reset successful. You can now <a href='login.html'>log in</a>.";
        $valid = false;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Reset Password</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
<div class="login-container">
  <div class="right-panel">
    <div class="form-box">
      <h2>Reset Your Password</h2>

      <?php if (!empty($success)): ?>
        <div class="alert success"><?= $success ?></div>
      <?php elseif ($valid): ?>
        <?php if (!empty($error)): ?>
          <div class="alert error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
          <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">

          <div class="input-group">
            <input type="password" name="password" placeholder="New Password" required />
          </div>

          <div class="input-group">
            <input type="password" name="confirm_password" placeholder="Confirm Password" required />
          </div>

          <button type="submit" class="login-btn">Reset Password</button>
        </form>

      <?php else: ?>
        <div class="alert error">Invalid or expired reset link.</div>
      <?php endif; ?>

    </div>
  </div>
</div>
</body>
</html>
