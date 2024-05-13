<?php
    session_start();
    // Checking the administrator role
    include_once '../config/admin_auth.php';
    
    include_once '../config/database.php';
    
    // Get the search query, if there is one
    $search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
    
    // Create SQL query for searching by orders
    $query = "SELECT * FROM orders";
    if (!empty($search_query)) {
        $query .= " WHERE id LIKE '%$search_query%' 
                    OR user_id LIKE '%$search_query%' 
                    OR shipping_address LIKE '%$search_query%' 
                    OR billing_address LIKE '%$search_query%' 
                    OR status LIKE '%$search_query%'";
    }
    $query .= " ORDER BY created_at DESC";
    $result = $conn->query($query);
    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Manage Orders</title>
        <link rel="stylesheet" href="../assets/css/styles.css">
        <link rel="stylesheet" href="../assets/css/admin_content_styles.css">
        <link rel="stylesheet" href="../assets/css/admin_table_styles.css">
    </head>
    <body>
        <div class="container">
            <?php include 'admin_navigation.php'; ?>
            <div class="main-content">
                <h1 class="title">Manage Orders</h1>
                <form method="GET" action="orders.php" class="search-form">
                    <input type="text" name="search" id="search" title="Enter a keyword to search orders" class="search-input"
                    placeholder="Order ID, User ID, Address..." value="<?php echo htmlspecialchars($search_query); ?>">
                    <input type="submit" value="Search" class="search-submit">
                </form>
                <div class="table-container">
                <table border="1">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User ID</th>
                            <th>Total</th>
                            <th>Shipping Address</th>
                            <th>Billing Address</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $order['id']; ?></td>
                            <td><?php echo $order['user_id']; ?></td>
                            <td><?php echo number_format($order['total'], 2); ?></td>
                            <td><?php echo htmlspecialchars($order['shipping_address']); ?></td>
                            <td><?php echo htmlspecialchars($order['billing_address']); ?></td>
                            <td><?php echo htmlspecialchars($order['status']); ?></td>
                            <td><?php echo $order['created_at']; ?></td>
                            <td>
                                <a href="view_order.php?id=<?php echo $order['id']; ?>" class="view-link">View Details</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </body>
</html>