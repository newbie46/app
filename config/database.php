<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'ecommerce';

// Create a connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
