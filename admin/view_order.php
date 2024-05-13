<?php
    session_start();
    // Checking the administrator role
    include_once '../config/admin_auth.php';
    
    include_once '../config/database.php';
    
    // Get order identifier from URL parameters
    $order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    // Get order information
    $query = "SELECT * FROM orders WHERE id = $order_id";
    $result = $conn->query($query);
    $order = $result ? $result->fetch_assoc() : null;
    
    if (!$order) {
        die('Order not found.');
    }
    
    // Get details of items in the order
    $items_query = "SELECT products.name, order_items.quantity, order_items.price 
                    FROM order_items 
                    JOIN products ON order_items.product_id = products.id 
                    WHERE order_id = $order_id";
    $items_result = $conn->query($items_query);
    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Order Details</title>
        <link rel="stylesheet" href="../assets/css/styles.css">
        <link rel="stylesheet" href="../assets/css/orders_styles.css">
    </head>
    <body>
        <div class="container">
            <?php include 'admin_navigation.php'; ?>
            <div class="main-content">
                <h1 class="title">Order #<?php echo $order_id; ?> Details</h1>
                <p class="order-info"><strong>User ID:</strong> <?php echo $order['user_id']; ?></p>
                <p class="order-info"><strong>Total:</strong> $<?php echo number_format($order['total'], 2); ?></p>
                <p class="order-info"><strong>Shipping Address:</strong> <?php echo htmlspecialchars($order['shipping_address']); ?></p>
                <p class="order-info"><strong>Billing Address:</strong> <?php echo htmlspecialchars($order['billing_address']); ?></p>
                <p class="order-info"><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
                <p class="order-info"><strong>Created At:</strong> <?php echo $order['created_at']; ?></p>
                <h2 class="content">Order Items</h2>
                <ul class="product-list">
                    <?php while ($item = $items_result->fetch_assoc()): ?>
                    <li>
                        <?php echo htmlspecialchars($item['name']); ?> - Quantity: <?php echo $item['quantity']; ?> - Price: $<?php echo number_format($item['price'], 2); ?>
                    </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </body>
</html>