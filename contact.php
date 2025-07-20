<?php
// Handle form submission
$successMsg = $errorMsg = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize inputs
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    if (!empty($name) && !empty($email) && !empty($message)) {
        $conn = new mysqli("localhost", "root", "", "medical_assistant");
        if ($conn->connect_error) {
            $errorMsg = "Database connection failed!";
        } else {
            $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $message);
            if ($stmt->execute()) {
                $successMsg = "✅ Thank you! Your message has been sent.";
            } else {
                $errorMsg = "❌ Something went wrong!";
            }
            $stmt->close();
            $conn->close();
        }
    } else {
        $errorMsg = "❌ All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Contact Us | Medical Assistant</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #006666;
      font-family: 'Segoe UI', sans-serif;
      color: #fff;
    }

    .navbar {
      background-color: #004d4d;
    }

    .navbar-brand {
      font-weight: bold;
      color: #fff;
    }

    .navbar-nav .nav-link {
      color: #e0f7f7;
    }

    .navbar-nav .nav-link:hover {
      color: #ffffff;
    }

    .contact-section {
      background: rgba(255, 255, 255, 0.1);
      padding: 40px;
      border-radius: 20px;
      margin: 100px auto;
      max-width: 600px;
      backdrop-filter: blur(8px);
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
    }

    .contact-section h2 {
      text-align: center;
      margin-bottom: 30px;
      font-weight: bold;
    }

    .form-control {
      border-radius: 10px;
      padding: 10px;
    }

    .form-label {
      font-weight: 500;
      margin-bottom: 5px;
    }

    .btn-submit {
      background-color: #00cc99;
      color: #fff;
      border: none;
      padding: 12px 30px;
      border-radius: 30px;
      width: 100%;
    }

    .btn-submit:hover {
      background-color: #00b38f;
    }

    .alert {
      margin-bottom: 20px;
      padding: 12px 20px;
      border-radius: 10px;
    }

    footer {
      background-color: #004d4d;
      color: #cceeee;
      text-align: center;
      padding: 20px 0;
      margin-top: 60px;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">Medical Assistant</a>
    <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon bg-light"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="signup.php">Signup</a></li>
        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <li class="nav-item"><a class="nav-link active" href="#">Contact Us</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Contact Form Section -->
<div class="container">
  <div class="contact-section">
    <h2>Contact Us</h2>

    <?php if ($successMsg): ?>
      <div class="alert alert-success text-center"><?php echo $successMsg; ?></div>
    <?php elseif ($errorMsg): ?>
      <div class="alert alert-danger text-center"><?php echo $errorMsg; ?></div>
    <?php endif; ?>

    <form method="POST" action="" novalidate>
      <div class="mb-3">
        <label for="name" class="form-label">Your Name *</label>
        <input type="text" class="form-control" name="name" required placeholder="Zeeshan Ahmed">
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email Address *</label>
        <input type="email" class="form-control" name="email" required placeholder="you@example.com">
      </div>
      <div class="mb-3">
        <label for="message" class="form-label">Your Message *</label>
        <textarea class="form-control" name="message" rows="5" required placeholder="Type your message..."></textarea>
      </div>
      <button type="submit" class="btn btn-submit mt-3">Send Message</button>
    </form>
  </div>
</div>

<!-- Footer -->
<footer>
  <div class="container">
    <p>© 2025 Medical Assistant. All rights reserved.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
