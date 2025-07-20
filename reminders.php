<?php
include 'session.php';
require 'config.php';

$user_id = $_SESSION['user_id'];
$message = '';

// Insert Reminder
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_reminder'])) {
    $title = trim($_POST['title']);
    $time = $_POST['time'];
    $desc = trim($_POST['description']);

    if (!empty($title) && !empty($time)) {
        $stmt = $pdo->prepare("INSERT INTO reminders (user_id, title, reminder_time, description) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $title, $time, $desc]);
        $message = "Reminder added successfully!";
    }
}

// Delete Reminder
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM reminders WHERE id = ? AND user_id = ?");
    $stmt->execute([$delete_id, $user_id]);
    header("Location: reminders.php");
    exit;
}

// Fetch Reminders
$stmt = $pdo->prepare("SELECT * FROM reminders WHERE user_id = ? ORDER BY reminder_time");
$stmt->execute([$user_id]);
$reminders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Reminders - Medical Assistant</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>

 <!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <div class="sidebar-header d-flex justify-content-between align-items-center px-3">
    <span class="sidebar-logo"><i class="bi bi-heart-pulse-fill me-2"></i>Medical Assistant</span>
    <i class="fas fa-bars toggle-icon" id="sidebarToggle"></i>
  </div>
  <ul class="nav-links">
    <li><a href="dashboard.php" ><i class="bi bi-speedometer2"></i> <span>Dashboard</span></a></li>
    <li><a href="reminders.php"class="active"><i class="bi bi-bell-fill"></i> <span>Reminders</span></a></li>
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


<!-- Content -->
<div class="main-content">
  <div class="topbar d-flex justify-content-start align-items-center px-4 py-2">
    <button class="btn btn-sm btn-outline-dark d-md-none btn-toggle-sidebar"><i class="fas fa-bars"></i></button>
    <h4 class="m-0">Reminders</h4>
  </div>

  <div class="container mt-4">
    <?php if ($message): ?>
      <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm p-4">
      <h4 class="mb-3">Add New Reminder</h4>
      <form method="POST">
        <input type="hidden" name="add_reminder" value="1">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" placeholder="e.g. Take medicine" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Time</label>
            <input type="time" name="time" class="form-control" required>
          </div>
          <div class="col-12">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="2" placeholder="Optional notes..."></textarea>
          </div>
          <div class="col-12">
            <button class="btn btn-success">Add Reminder</button>
          </div>
        </div>
      </form>
    </div>

    <div class="card shadow-sm p-4 mt-4">
      <h4 class="mb-3">Your Reminders</h4>
      <?php if (count($reminders) > 0): ?>
        <ul class="list-group">
          <?php foreach ($reminders as $r): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <strong><?= htmlspecialchars($r['title']) ?></strong> at <?= htmlspecialchars($r['reminder_time']) ?>
                <?php if (!empty($r['description'])): ?>
                  <div class="small text-muted"><?= htmlspecialchars($r['description']) ?></div>
                <?php endif; ?>
              </div>
              <a href="?delete=<?= $r['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this reminder?')">Delete</a>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p class="text-muted">No reminders found.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Shared JS -->
<script>
  // Sidebar Toggle
    document.getElementById('sidebarToggle').addEventListener('click', () => {
      document.querySelector('.sidebar').classList.toggle('collapsed');
    });

  // Theme toggle
  function setTheme(mode) {
    if (mode === 'system') {
      localStorage.removeItem('theme');
      mode = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    } else localStorage.setItem('theme', mode);
    document.body.classList.toggle('dark-mode', mode === 'dark');
    document.querySelectorAll('.theme-option').forEach(el =>
      el.classList.toggle('active', el.getAttribute('data-theme') === mode)
    );
  }

  document.querySelectorAll('.theme-option').forEach(el =>
    el.addEventListener('click', () => setTheme(el.getAttribute('data-theme')))
  );

  document.getElementById('settingsToggle')?.addEventListener('click', () => {
    const menu = document.getElementById('themeOptions');
    menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
  });

  window.addEventListener('DOMContentLoaded', () => {
    const pref = localStorage.getItem('theme') || 'system';
    setTheme(pref);
  });
</script>

</body>
</html>
