<?php include 'session.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>AI Health Assistant</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>

  <!-- Font Awesome for icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>

  <style>
    :root {
      --primary-color: #007372;
      --hover-color: #005956;
      --bot-bg: #e9ecef;
      --background: #f4f7fb;
      --text-color: #212529;
    }

    * { box-sizing: border-box; }
    html, body {
      height: 100%;
      margin: 0; padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: var(--background);
      color: var(--text-color);
    }

    /* Sidebar styles */
    .sidebar {
      position: fixed; top: 0; left: 0;
      height: 100vh; width: 230px;
      background-color: #006666;
      padding: 20px 0;
      color: #fff;
      overflow-x: hidden;
      transition: width 0.3s ease;
      z-index: 1000;
    }
    .sidebar.collapsed { width: 70px; }
    .sidebar-logo { font-size: 1.4rem; font-weight: bold; text-align: center; margin-bottom: 30px; }
    .sidebar.collapsed .sidebar-logo,
    .sidebar.collapsed .nav-links span { display: none; }

    .nav-links { list-style: none; padding: 0; margin: 0; }
    .nav-links li { margin-bottom: 15px; }
    .nav-links a {
      display: flex; align-items: center;
      padding: 10px 20px;
      color: #e0e0e0; text-decoration: none;
      border-left: 4px solid transparent;
      transition: background 0.3s, color 0.3s;
    }
    .nav-links a:hover, .nav-links a.active {
      background-color: rgba(255,255,255,0.15);
      color: #ffffff;
      border-left: 4px solid #ffffff;
    }
    .nav-links a i { margin-right: 10px; }

    .settings {
      margin-top: 1rem;
      border-top: 1px solid rgba(255,255,255,0.3);
      padding-top: 0.5rem;
    }
   .theme-option {
  cursor: pointer;
  font-size: 0.95rem;
  padding: 5px 0;
  color: #fff;
}
    
    .theme-option:hover,
.theme-option.active {
  background: rgba(255, 255, 255, 0.15);
  font-weight: bold;
  text-decoration: underline;
}

    /* Chat UI styles */
    .chat-container { display: flex; flex-direction: column; height: 100vh; margin-left: 230px; transition: margin 0.3s; }
    .sidebar.collapsed ~ .chat-container { margin-left: 70px; }

    .chat-header {
      padding: 15px;
      background-color: #ffffff;
      border-bottom: 1px solid #ddd;
      text-align: center;
    }
    .chat-header img { height: 80px; }
    .chat-header h4 { margin-top: 8px; font-weight: bold; }

    #chatBox {
      flex: 1;
      padding: 20px;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
    }

    .message {
      max-width: 75%;
      margin-bottom: 12px;
      padding: 12px 16px;
      border-radius: 14px;
      font-size: 1rem;
      line-height: 1.4;
      word-wrap: break-word;
    }
    .user-message {
      background-color: var(--primary-color);
      color: white;
      align-self: flex-end;
      border-top-right-radius: 0;
    }
    .bot-message {
      background-color: var(--bot-bg);
      color: #000;
      align-self: flex-start;
      border-top-left-radius: 0;
    }

    .chat-input {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px;
      border-top: 1px solid #ddd;
      background-color: #fff;
    }
    .chat-input input {
      flex: 1;
      padding: 10px;
      font-size: 1rem;
      border-radius: 8px;
      border: 1px solid #ccc;
    }
    .btn-send, .mic-btn {
      border: none; border-radius: 8px;
      padding: 10px 14px;
      font-size: 1.1rem;
      background-color: var(--primary-color);
      color: #fff;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }
    .btn-send:hover, .mic-btn:hover {
      background-color: var(--hover-color);
    }

    .back-link { text-align: center; margin: 15px 0; }
    .back-link a {
      color: var(--primary-color);
      font-weight: bold;
      font-size: 1.05rem;
      text-decoration: none;
    }
    .back-link a:hover { text-decoration: underline; }

    /* Dark-mode overrides */
    .dark-mode {
      background-color: #121212;
      color: #f1f1f1;
    }
    .dark-mode .chat-header,
    .dark-mode .chat-input { background-color: #1e1e1e; color: #f1f1f1; }
    .dark-mode input.form-control {
      background-color: #2a2a2a;
      color: #f1f1f1;
      border-color: #444;
    }
    .dark-mode .btn-send:hover,
    .dark-mode .mic-btn:hover {
      background-color: var(--hover-color);
    }
  </style>
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
    <li><a href="reminders.php"><i class="bi bi-bell-fill"></i> <span>Reminders</span></a></li>
    <li><a href="chatbot.php"class="active"><i class="bi bi-robot"></i> <span>Chatbot</span></a></li>
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


  <!-- Chat content -->
  <div class="chat-container">
    <div class="chat-header">
      <button class="btn btn-sm btn-outline-dark d-md-none btn-toggle-sidebar">
        <i class="fas fa-bars"></i>
      </button>
      <img src="css/Chatbot.png" alt="Medical AI Logo" />
      <h4>AI Health Assistant</h4>
    </div>
    <div id="chatBox"></div>
    <div class="chat-input">
  <input type="text" id="userInput" class="form-control" placeholder="Ask your question..." />
  <button class="btn-send" onclick="sendMessage()" title="Send">
    <i class="fas fa-paper-plane"></i>
  </button>
  <button class="mic-btn" onclick="startListening()" title="Voice Input">
    <i class="fas fa-microphone"></i>
  </button>
  <!-- ✅ Avatar Toggle Button -->
  <button class="mic-btn" onclick="toggleAvatar()" title="Toggle Avatar">
    <i class="fas fa-user-circle"></i>
  </button>
</div>


  <div class="back-link">
<a href="dashboard.php"><i class="bi bi-arrow-left-circle-fill me-1"></i> Go To Dashboard</a>

  </div>

  <!-- Shared JavaScript -->
  <script>
  // Sidebar Toggle from icon in sidebar header
  document.getElementById('sidebarToggle').addEventListener('click', () => {
    document.querySelector('.sidebar').classList.toggle('collapsed');
  });

  // Sidebar Toggle from chat-header (for mobile)
  document.querySelector(".btn-toggle-sidebar").addEventListener("click", () => {
    document.getElementById("sidebar").classList.toggle("collapsed");
  });

  // Settings dropdown toggle
  document.getElementById("settingsToggle").addEventListener("click", () => {
    const themeOptions = document.getElementById("themeOptions");
    themeOptions.style.display = (themeOptions.style.display === "none") ? "block" : "none";
  });

  // Theme logic
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

  window.addEventListener('DOMContentLoaded', () => {
    const pref = localStorage.getItem('theme') || 'system';
    const themeEl = document.querySelector(`.theme-option[data-theme="${pref}"]`);
    if (themeEl) themeEl.click();
  });

  // Chat functionality
  const chatBox = document.getElementById("chatBox");
  function appendMessage(text, isUser) {
    const msg = document.createElement("div");
    msg.className = "message " + (isUser ? "user-message" : "bot-message");
    msg.innerText = text;
    chatBox.appendChild(msg);
    chatBox.scrollTop = chatBox.scrollHeight;
  }

  function getBotReply(text) {
    const msg = text.toLowerCase();
    if (msg.includes("heart rate") || msg.includes("bpm")) return "Your current heart rate is 78 BPM. Everything looks normal.";
    if (msg.includes("high") || msg.includes("danger")) return "Your BPM seems high. Please stay calm and take deep breaths.";
    if (msg.includes("help")) return "I can assist you with health tips, BPM info, and reminders.";
    return "I'm sorry, I didn't understand that. Can you rephrase?";
  }

  function sendMessage() {
    const input = document.getElementById("userInput");
    const userText = input.value.trim();
    if (!userText) return;

    appendMessage(userText, true);
    saveMessageToDB(userText, 'user');

    setTimeout(() => {
      const botReply = getBotReply(userText);
      appendMessage(botReply, false);
      saveMessageToDB(botReply, 'bot');
    }, 500);

    input.value = "";
  }

  // ✅ Fixed: proper string interpolation using backticks
  function saveMessageToDB(message, sender) {
    fetch('save_chat.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `message=${encodeURIComponent(message)}&sender=${sender}`
    });
  }

  function startListening() {
    if (!('webkitSpeechRecognition' in window)) return alert("Speech recognition not supported.");
    const r = new webkitSpeechRecognition();
    r.lang = "en-US"; r.interimResults = false; r.maxAlternatives = 1;
    r.onresult = e => {
      document.getElementById("userInput").value = e.results[0][0].transcript;
      sendMessage();
    };
    r.onerror = e => alert("Error: " + e.error);
    r.start();
  }
</script>

<!-- Heygen Avatar Embed -->
<script>
  (function(window) {
    const host = "https://labs.heygen.com";
    const url = host + "/guest/streaming-embed?share=eyJxdWFsaXR5IjoiaGlnaCIsImF2YXRh...";
    const clientWidth = document.body.clientWidth;

    const wrapDiv = document.createElement("div");
    wrapDiv.id = "heygen-streaming-embed";

    const stylesheet = document.createElement("style");
    stylesheet.textContent = `
  #heygen-streaming-embed {
    z-index: 9999;
    position: fixed;
    left: 40px;
    bottom: 40px;
    width: 200px;
    height: 200px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0px 8px 24px rgba(0, 0, 0, 0.12);
    transition: all linear 0.1s;
    overflow: hidden;
    opacity: 0;
    visibility: hidden;
  }
  #heygen-streaming-embed.show {
    opacity: 1;
    visibility: visible;
  }
  #heygen-streaming-embed.expand {
    ${clientWidth < 540
      ? "height: 266px; width: 96%; left: 50%; transform: translateX(-50%);"
      : "height: 366px; width: calc(360px);"}
  }
`;

    

    const container = document.createElement("div");
    container.id = "heygen-streaming-container";
    container.style.width = "100%";
    container.style.height = "100%";
    container.style.border = "none";

    const iframe = document.createElement("iframe");
    iframe.src = url;
    iframe.allow = "camera *; microphone *; autoplay *; encrypted-media *;";
    iframe.style.width = "100%";
    iframe.style.height = "100%";
    iframe.style.border = "none";

    container.appendChild(iframe);
    wrapDiv.appendChild(container);
    document.body.appendChild(stylesheet);
    document.body.appendChild(wrapDiv);
  })(window);
</script>
<script>
  function toggleAvatar() {
    const avatar = document.getElementById("heygen-streaming-embed");
    avatar.classList.toggle("show");
    avatar.classList.toggle("expand");
  }
</script>


</body>
</html>