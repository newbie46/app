<?php
session_start();
include_once '../config/database.php';

// Get product ID
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$redirect_url = 'index.php';

// Check if the product exists in the database
$query = "SELECT * FROM products WHERE id = $product_id";
$result = $conn->query($query);
$product = $result ? $result->fetch_assoc() : null;

if ($product) {
    // Add the item to the session cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // If the item is already in the cart, increase the quantity
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$product_id] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => 1,
        ];
    }
}

// Redirects to the start page or other specified page
header("Location: $redirect_url");
exit;
