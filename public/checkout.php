<?php
    session_start();
    include_once '../config/database.php';
    
    // Check if the user is authorized
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['return_url'] = 'checkout.php';
        header('Location: login.php');
        exit;
    }
    
    $user_id = $_SESSION['user_id'];
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    
    // Check that the cart is not empty
    if (count($cart) == 0) {
        header('Location: cart.php');
        exit;
    }
    
    // Get current user data for autocompletion
    $query = "SELECT shipping_address, billing_address FROM users WHERE id = $user_id";
    $result = $conn->query($query);
    $user = $result ? $result->fetch_assoc() : null;
    
    $shipping_address = $user['shipping_address'] ?? '';
    $billing_address = $user['billing_address'] ?? '';
    
    // Form data processing
    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $shipping_address = trim($_POST['shipping_address']);
        $billing_address = trim($_POST['billing_address']);
    
        // Check addresses for validity
        if (empty($shipping_address) || !preg_match('/^[a-zA-Z0-9,\s.-]+$/', $shipping_address)) {
            $errors[] = 'Invalid shipping address. Please provide a valid address.';
        }
    
        if (empty($billing_address) || !preg_match('/^[a-zA-Z0-9,\s.-]+$/', $billing_address)) {
            $errors[] = 'Invalid billing address. Please provide a valid address.';
        }
    
        // Adding the order to the database if there are no errors
        if (empty($errors)) {
            // Calculate the total cost of the order
            $total = 0;
            foreach ($cart as $product) {
                $total += $product['price'] * $product['quantity'];
            }
    
            // Create a new order
            $query = "INSERT INTO orders (user_id, total, shipping_address, billing_address, status) 
                      VALUES ($user_id, $total, '$shipping_address', '$billing_address', 'Processing')";
            if ($conn->query($query)) {
                $order_id = $conn->insert_id;
    
                // Add each item to the order
                foreach ($cart as $product_id => $product) {
                    $quantity = $product['quantity'];
                    $price = $product['price'];
                    $item_query = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                                   VALUES ($order_id, $product_id, $quantity, $price)";
                    $conn->query($item_query);
                }
    
                // Empty the cart
                $_SESSION['cart'] = [];
    
                // Redirect to the orders page
                header('Location: orders.php');
                exit;
            } else {
                $errors[] = 'Error creating order: ' . $conn->error;
            }
        }
    }
    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Checkout</title>
        <link rel="stylesheet" href="../assets/css/styles.css">
        <link rel="stylesheet" href="../assets/css/admin_content_styles.css">
    </head>
    <body>
        <div class="container">
            <?php include 'navigation.php'; ?>
            <div class="main-content">
                <h1 class="title">Checkout</h1>
                <?php if (!empty($errors)): ?>
                <ul>
                    <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                <form method="POST" class="admin-form">
                    <label for="shipping_address">Shipping Address:</label>
                    <textarea name="shipping_address" id="shipping_address" title="Enter your shipping address" placeholder="123 Main St, City, Country"><?php echo htmlspecialchars($shipping_address); ?></textarea>
                    <br>
                    <label for="billing_address">Billing Address:</label>
                    <textarea name="billing_address" id="billing_address" title="Enter your billing address" placeholder="Enter your billing address"><?php echo htmlspecialchars($billing_address); ?></textarea>
                    <br>
                    <label for="payment_method">Payment Method:</label>
                    <select name="payment_method" id="payment_method" title="Payment method is set to Check" disabled>
                        <option value="Check" selected>Check</option>
                    </select>
                    <br>
                    <input type="submit" value="Confirm Order">
                </form>
            </div>
            <?php include 'footer.php'; ?>
        </div>
    </body>
</html>