<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Medical Assistant | Home</title>
  <link rel="stylesheet" href="css/style.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #006666;
      color: #fff;
      overflow-x: hidden;
    }

    .navbar {
      background-color: #004d4d;
    }

    .navbar-brand {
      font-weight: bold;
      color: #ffffff;
    }

    .navbar-nav .nav-link {
      color: #e0f7f7;
    }

    .navbar-nav .nav-link:hover {
      color: #ffffff;
    }

    .glass-section {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      padding: 60px 40px;
      margin-top: 100px;
      backdrop-filter: blur(10px);
      box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    }

    .btn-green {
      background-color: #00cc99;
      color: #fff;
      border-radius: 30px;
      padding: 10px 30px;
      border: none;
    }

    .btn-green:hover {
      background-color: #00b38f;
    }

    .section-title {
      font-size: 32px;
      margin-bottom: 20px;
      color: #ffffff;
    }

    .feature-card {
      background-color: rgba(255, 255, 255, 0.08);
      border: none;
      border-radius: 16px;
      padding: 30px;
      text-align: center;
      color: #e0f7f7;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
      transition: transform 0.3s ease;
    }

    .feature-card:hover {
      transform: translateY(-8px);
    }

    .medical-image {
      max-width: 100%;
      height: auto;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
    }

    footer {
      background-color: #004d4d;
      text-align: center;
      padding: 20px 0;
      color: #cceeee;
      margin-top: 50px;
    }

    @media (max-width: 768px) {
      .glass-section {
        padding: 40px 20px;
      }

      .section-title {
        font-size: 24px;
      }

      .feature-card {
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top shadow">
  <div class="container">
    <a class="navbar-brand" href="#">Medical Assistant</a>
    <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon bg-light"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="signup.php">Signup</a></li>
        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero Section with Image -->
<section class="container glass-section text-center">
  <div class="row align-items-center">
    <div class="col-md-6 text-md-start text-center mb-4 mb-md-0">
      <h1 class="mb-4">Your Smart Health Companion</h1>
      <p class="lead">
        Monitor your health in real-time, get reminders, and chat with our intelligent assistant – all in one platform.
      </p>
      <a href="signup.php" class="btn btn-green mt-3">Start Monitoring</a>
    </div>
    <div class="col-md-6 text-center">
      <img src="https://hyperbaricsorlando.com/wp-content/uploads/2021/05/illustrated-medical-team.png" alt="Medical Team" class="medical-image">
    </div>
  </div>
</section>

<!-- Features Section -->
<section class="container mt-5 mb-5">
  <h2 class="text-center section-title">Why Choose Us?</h2>
  <div class="row g-4 mt-4">
    <div class="col-md-4">
      <div class="feature-card">
        <h4>Live Health Stats</h4>
        <p>Get instant updates on your pulse and oxygen using IoT-powered sensors and MQTT integration.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="feature-card">
        <h4>AI Chat Support</h4>
        <p>Ask health-related questions from our chatbot — always ready to assist, day or night.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="feature-card">
        <h4>Scheduled Reminders</h4>
        <p>Manage your medicine intake and appointments through personalized reminders.</p>
      </div>
    </div>
  </div>
</section>

<!-- Call to Action -->
<section class="container glass-section text-center">
  <h2 class="mb-3">Your Health, Simplified</h2>
  <p class="lead mb-4">We’re on a mission to empower patients with smart tools for better care. Join us and take control today.</p>
  <a href="signup.php" class="btn btn-green">Create Account</a>
</section>

<!-- Footer -->
<footer>
  <div class="container">
    <p>© 2025 Medical Assistant. All rights reserved.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
