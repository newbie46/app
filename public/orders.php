<?php
   session_start();
   include_once '../config/database.php';
   
   // Check if the user is authorized
   if (!isset($_SESSION['user_id'])) {
       header('Location: login.php');
       exit;
   }
   
   $user_id = $_SESSION['user_id'];
   
   // Receive user orders
   $query = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
   $result = $conn->query($query);
   ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/orders_styles.css">
</head>
<body>
    <div class="container">
        <?php include 'navigation.php'; ?>
        <div class="main-content">
            <h1 class="title">Your Orders</h1>
            <?php if ($result && $result->num_rows > 0): ?>
            <ul class="order-list">
                <?php while ($order = $result->fetch_assoc()): ?>
                <li class="order-item">
                    <h3 class="order-header">Order #<?php echo $order['id']; ?></h3>
                    <p class="order-info">Date: <?php echo $order['created_at']; ?></p>
                    <p class="order-info">Status: <?php echo $order['status']; ?></p>
                    <p class="order-info">Total: $<?php echo number_format($order['total'], 2); ?></p>
                    <p class="order-info">Shipping Address: <?php echo htmlspecialchars($order['shipping_address']); ?></p>
                    <p class="order-info">Billing Address: <?php echo htmlspecialchars($order['billing_address']); ?></p>
                    <?php
                        $order_id = $order['id'];
                        $items_query = "SELECT products.name, order_items.quantity, order_items.price 
                                        FROM order_items 
                                        JOIN products ON order_items.product_id = products.id 
                                        WHERE order_id = $order_id";
                        $items_result = $conn->query($items_query);
                    ?>
                    <?php if ($items_result && $items_result->num_rows > 0): ?>
                    <ul class="product-list">
                        <?php while ($item = $items_result->fetch_assoc()): ?>
                        <li>
                            <?php echo htmlspecialchars($item['name']); ?> - Quantity: <?php echo $item['quantity']; ?> - Price: $<?php echo number_format($item['price'], 2); ?>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                    <?php endif; ?>
                </li>
                <?php endwhile; ?>
            </ul>
            <?php else: ?>
            <p class="content">You have no orders.</p>
            <?php endif; ?>
        </div>
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>
