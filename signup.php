<?php
session_start();
require 'config.php';

// Initialize message variable
$message = '';
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST["fullname"] ?? ''));
    $emailRaw = trim($_POST["email"] ?? '');
    $email = filter_var($emailRaw, FILTER_VALIDATE_EMAIL);
    $isGmail = preg_match("/^[a-zA-Z0-9._%+-]+@gmail\.com$/", $emailRaw);
    $password = trim($_POST["password"] ?? '');
    $confirm_password = trim($_POST["confirm_password"] ?? '');

    // Server-side validation
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $message = "All fields are required.";
    } elseif (!preg_match("/^[a-zA-Z ]{2,50}$/", $name)) {
        $message = "Name should only contain letters and spaces (2–50 characters).";
    } elseif (!$email || !$isGmail) {
        $message = "Only Gmail addresses (ending with @gmail.com) are allowed.";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $message = "Email already exists. Please try logging in.";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                if ($stmt->execute([$name, $email, $hashedPassword])) {
                    $message = "✅ Registered successfully! You may now log in.";
                    $success = true;
                } else {
                    $message = "Registration failed. Please try again.";
                }
            }
        } catch (PDOException $e) {
            $message = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign Up - AI Health Assistant</title>
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .message-box {
      margin: 15px 0;
      padding: 12px 16px;
      border-radius: 6px;
      font-weight: bold;
      font-size: 14px;
    }
    .success-box {
      background-color: #e8f5e9;
      color: #2e7d32;
      border: 1px solid #2e7d32;
    }
    .error-box {
      background-color: #ffebee;
      color: #c62828;
      border: 1px solid #c62828;
    }
  </style>
</head>
<body>

  <div class="login-container">

    <div class="left-panel">
      <img src="images/loginmedicalvector.png" alt="Medical Illustration" />
    </div>

    <div class="right-panel">
      <div class="form-box">
        <h2>Create Account</h2>

        <?php if ($message): ?>
          <div class="message-box <?= $success ? 'success-box' : 'error-box' ?>">
            <?= $message ?>
          </div>
        <?php endif; ?>

        <form action="signup.php" method="POST">
          <div class="input-group">
            <i class="fas fa-user icon"></i>
            <input type="text" name="fullname" placeholder="Full Name" required />
          </div>

          <div class="input-group">
            <i class="fas fa-envelope icon"></i>
            <input type="email" name="email" placeholder="Email" required />
          </div>

          <div class="input-group">
            <i class="fas fa-lock icon"></i>
            <input type="password" name="password" id="signup-password" placeholder="Password" required />
            <button type="button" class="toggle-password" onclick="toggleSignupPassword()">Show</button>
          </div>

          <div class="input-group">
            <i class="fas fa-lock icon"></i>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required />
          </div>

          <button type="submit" class="login-btn">Sign Up</button>
        </form>

        <div class="signup-link">
          Already have an account? <a href="login.php">Log in</a>
        </div>
      </div>
    </div>

  </div>

<script>
  function toggleSignupPassword() {
    const passwordInput = document.getElementById("signup-password");
    const toggleButton = document.querySelector(".toggle-password");
    const isPassword = passwordInput.type === "password";
    passwordInput.type = isPassword ? "text" : "password";
    toggleButton.textContent = isPassword ? "Hide" : "Show";
  }

  document.querySelector("form").addEventListener("submit", function (e) {
    const fullName = this.fullname.value.trim();
    const email = this.email.value.trim();
    const password = this.password.value;
    const confirmPassword = this.confirm_password.value;

    const namePattern = /^[a-zA-Z ]{2,50}$/;
    const gmailPattern = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;

    if (!fullName || !email || !password || !confirmPassword) {
      alert("Please fill in all fields.");
      e.preventDefault();
      return;
    }

    if (!namePattern.test(fullName)) {
      alert("Full Name should only contain letters and spaces (2–50 characters).");
      e.preventDefault();
      return;
    }

    if (!gmailPattern.test(email)) {
      alert("Only Gmail addresses (ending with @gmail.com) are allowed.");
      e.preventDefault();
      return;
    }

    if (password.length < 6) {
      alert("Password must be at least 6 characters.");
      e.preventDefault();
      return;
    }

    if (password !== confirmPassword) {
      alert("Passwords do not match.");
      e.preventDefault();
      return;
    }
  });
</script>

</body>
</html>
