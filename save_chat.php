<?php
include 'config.php'; // <-- Your database connection
include 'session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender = $_POST['sender'] ?? '';
    $message = $_POST['message'] ?? '';
    $username = $_SESSION['username'] ?? 'guest';

    if (!empty($message) && in_array($sender, ['user', 'bot'])) {
        $stmt = $conn->prepare("INSERT INTO chat_messages (username, sender, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $sender, $message);
        $stmt->execute();
        $stmt->close();
    }
}
?>
