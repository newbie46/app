<?php
session_start();

// Get product ID
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Remove the item from the cart if it exists
if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
}

// Redirect back to the cart page
header('Location: cart.php');
exit;
