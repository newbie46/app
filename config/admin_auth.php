<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Check if the user is authorized and has the administrator role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../public/login.php');
    exit;
}
?>
