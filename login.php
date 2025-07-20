<?php
session_start();
require 'config.php';

$message = '';
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emailRaw = trim($_POST["email"] ?? '');
    $password = trim($_POST["password"] ?? '');

    $email = filter_var($emailRaw, FILTER_VALIDATE_EMAIL);
    $isGmail = preg_match("/^[a-zA-Z0-9._%+-]+@gmail\.com$/", $emailRaw);

    if (!$email || !$isGmail || empty($password)) {
        $message = "Invalid email or password.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["name"];

            setcookie("user", $user["name"], time() + (86400 * 7), "/");
            $message = "✅ Login successful. Redirecting...";
            $success = true;

            // Delay redirect for 1.5 seconds to show message
            echo "<script>
              setTimeout(function(){
                window.location.href = 'dashboard.php';
              }, 1500);
            </script>";
        } else {
            $message = "Incorrect email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - AI Health Assistant</title>
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
        <h2>Welcome Back!</h2>

        <?php if ($message): ?>
          <div class="message-box <?= $success ? 'success-box' : 'error-box' ?>">
            <?= $message ?>
          </div>
        <?php endif; ?>

        <form action="login.php" method="POST">

          <div class="input-group">
            <i class="fas fa-envelope icon"></i>
            <input type="email" name="email" placeholder="Email" required />
          </div>

          <div class="input-group">
            <i class="fas fa-lock icon"></i>
            <input type="password" name="password" id="password" placeholder="Password" required />
            <button type="button" class="toggle-password" onclick="togglePassword()">Show</button>
          </div>

          <div class="forgot-password">
            <a href="forgot-password.php">Forgot password?</a>
          </div>

          <button type="submit" class="login-btn">Log In</button>
        </form>

        <div class="signup-link">
          Don't have an account? <a href="signup.php">Sign up</a>
        </div>

      </div>
    </div>
  </div>

<script>
  function togglePassword() {
    const passwordInput = document.getElementById("password");
    const toggleButton = document.querySelector(".toggle-password");
    const isPassword = passwordInput.type === "password";
    passwordInput.type = isPassword ? "text" : "password";
    toggleButton.textContent = isPassword ? "Hide" : "Show";
  }

  // Client-side validation
  document.querySelector("form").addEventListener("submit", function (e) {
    const email = this.email.value.trim();
    const password = this.password.value.trim();

    const gmailPattern = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;

    if (!email || !password) {
      alert("Please fill in all fields.");
      e.preventDefault();
      return;
    }

    if (!gmailPattern.test(email)) {
      alert("Only Gmail addresses are allowed.");
      e.preventDefault();
      return;
    }

    if (password.length < 6) {
      alert("Password must be at least 6 characters.");
      e.preventDefault();
      return;
    }
  });
</script>

</body>
</html>
