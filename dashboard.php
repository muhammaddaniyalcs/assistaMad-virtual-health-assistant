<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard - Medical Assistant</title>
<!-- Bootstrap Icons CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <link rel="stylesheet" href="css/style.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</head>
<body>

  <!-- Sidebar -->

 <!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <div class="sidebar-header d-flex justify-content-between align-items-center px-3">
    <span class="sidebar-logo"><i class="bi bi-heart-pulse-fill me-2"></i>Medical Assistant</span>
    <i class="fas fa-bars toggle-icon" id="sidebarToggle"></i>
  </div>
  <ul class="nav-links">
    <li><a href="dashboard.php" class="active"><i class="bi bi-speedometer2"></i> <span>Dashboard</span></a></li>
    <li><a href="reminders.php"><i class="bi bi-bell-fill"></i> <span>Reminders</span></a></li>
    <li><a href="chatbot.php"><i class="bi bi-robot"></i> <span>Chatbot</span></a></li>
    <li><a href="logout.php"><i class="bi bi-box-arrow-right"></i> <span>Logout</span></a></li>
  </ul>

  <div class="settings px-3 text-white">
    <div class="fw-bold mb-1 d-flex justify-content-between align-items-center" id="settingsToggle" style="cursor:pointer;">
      <span><i class="bi bi-gear-fill"></i> Settings</span>
      <i class="fas fa-chevron-down"></i>
    </div>
    <div class="settings-submenu" id="themeOptions" style="display:none;">
      <span class="theme-option d-block mb-1" data-theme="light"><i class="bi bi-sun-fill me-1"></i> Light Mode</span>
      <span class="theme-option d-block" data-theme="dark"><i class="bi bi-moon-fill me-1"></i> Dark Mode</span>
    </div>
  </div>
</div>


  <!-- Main Content -->
  <div class="main-content">
    <div class="topbar d-flex justify-content-between align-items-center px-4 py-2">
      <h4 class="m-0">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h4>
    </div>

    <div class="container py-4">
      <div class="row g-4">
        <!-- BPM Card -->
        <div class="col-12 col-md-6">
          <div class="card shadow text-center">
            <div class="card-body">
              <h5 class="card-title">Live Heart Rate</h5>
              <h1 id="bpmValue" class="display-3 text-success bpm-animate">--</h1>
              <div id="alertBox" class="alert alert-warning mt-3 d-none">⚠️ Abnormal BPM detected!</div>
            </div>
          </div>
        </div>

        <!-- Chart Card -->
        <div class="col-12 col-md-6">
          <div class="card shadow">
            <div class="card-body">
              <h5 class="card-title">BPM Chart</h5>
              <canvas id="bpmChart" height="120"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script>
    // MQTT + Chart
    const client = mqtt.connect('wss://broker.hivemq.com:8884/mqtt');
    let bpmData = [], labels = [];

    const bpmValue = document.getElementById("bpmValue");
    const alertBox = document.getElementById("alertBox");

    const ctx = document.getElementById('bpmChart').getContext('2d');
    const chart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'BPM',
          data: bpmData,
          borderColor: '#198754',
          backgroundColor: 'rgba(25,135,84,0.2)',
          tension: 0.4,
          fill: true,
        }]
      },
      options: {
        responsive: true,
        scales: { y: { min: 40, max: 160 } },
        animation: { duration: 500, easing: 'easeOutQuart' }
      }
    });

    client.on('connect', () => {
      client.subscribe('esp8266/pulse');
    });

    client.on('message', (topic, message) => {
      const bpm = parseInt(message.toString());
      bpmValue.textContent = bpm;

      if (bpm > 100 || bpm < 50) {
        alertBox.classList.remove('d-none');
      } else {
        alertBox.classList.add('d-none');
      }

      const time = new Date().toLocaleTimeString();
      labels.push(time);
      bpmData.push(bpm);
      if (labels.length > 10) {
        labels.shift();
        bpmData.shift();
      }
      chart.update();
    });

    // Sidebar Toggle
    document.getElementById('sidebarToggle').addEventListener('click', () => {
      document.querySelector('.sidebar').classList.toggle('collapsed');
    });

    // Theme Toggle Logic
    function setTheme(mode) {
      if (mode === 'system') {
        localStorage.removeItem('theme');
        mode = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      } else {
        localStorage.setItem('theme', mode);
      }
      document.body.classList.toggle('dark-mode', mode === 'dark');
      document.querySelectorAll('.theme-option').forEach(opt => {
        opt.classList.toggle('active', opt.getAttribute('data-theme') === mode);
      });
    }

    document.querySelectorAll('.theme-option').forEach(opt =>
      opt.addEventListener('click', () => setTheme(opt.getAttribute('data-theme')))
    );

    window.addEventListener('DOMContentLoaded', () => {
      const pref = localStorage.getItem('theme') || 'system';
      setTheme(pref);
    });

    // Settings Submenu Toggle
    document.getElementById('settingsToggle').addEventListener('click', () => {
      const menu = document.getElementById('themeOptions');
      menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
    });
  </script>
</body>
</html>
