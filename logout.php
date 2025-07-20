<?php
session_start();
session_unset();
session_destroy();

// Optional: Clear the user cookie
setcookie("user", "", time() - 3600, "/");

header("Location: login.php");
exit;
