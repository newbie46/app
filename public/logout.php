<?php
// Initialize session or continue current session
session_start();

// Clearing all session variables
$_SESSION = [];

// Destroying the session
session_destroy();

// Redirect to the main page
header('Location: index.php');
exit;
