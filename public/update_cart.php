<?php
session_start();

// Get new quantity values from the form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['quantity'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        $product_id = intval($product_id);
        $quantity = max(1, intval($quantity));

        // Update the quantity of goods in the cart
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        }
    }
}

// Redirect back to the cart page
header('Location: cart.php');
exit;
