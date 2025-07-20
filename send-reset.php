<?php
session_start();
require 'config.php'; // Your database connection file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php'; // Load PHPMailer via Composer

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    
    if (!$email) {
        header("Location: forgot-password.php?error=invalid");
        exit;
    }

    // Check if email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if (!$stmt->fetch()) {
        header("Location: forgot-password.php?error=notfound");
        exit;
    }

    // Generate token
    $token = bin2hex(random_bytes(16));
    $expires = date("Y-m-d H:i:s", strtotime('+20 minutes'));

    // Store token
    $stmt = $pdo->prepare("REPLACE INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
    $stmt->execute([$email, $token, $expires]);

    // ✅ Create PHPMailer instance here
    $mail = new PHPMailer(true);

    try {
        // SMTP setup
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'medicalassistant074@gmail.com'; // ✅ Your Gmail
        $mail->Password = 'dkthrhtvwqctjwmd';         // ✅ Your 16-char Gmail App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Email content
        $mail->setFrom('medicalassistant074@gmail.com', 'AI Health Assistant');
        $mail->addAddress($email);

        // If working locally:
        $resetLink = "http://localhost/medical-assistant/reset-password.php?token=" . urlencode($token);

        $mail->Subject = 'Password Reset Request';
        $mail->isHTML(true);
        $mail->Body = "
            <p>You requested a password reset. Click the link below:</p>
            <a href='$resetLink'>$resetLink</a>
            <p>This link will expire in 20 minutes.</p>
        ";

        $mail->send();
        header("Location: forgot-password.php?success=1");
        exit;
    } catch (Exception $e) {
        echo "Mail error: " . $mail->ErrorInfo;
    }
}
