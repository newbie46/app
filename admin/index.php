<?php
    session_start();
    // Checking the administrator role
    include_once '../config/admin_auth.php';
    
    include_once '../config/database.php';
    
    // Prepare for displaying the number of users and products
    $product_count_query = "SELECT COUNT(*) AS count FROM products";
    $product_result = $conn->query($product_count_query);
    $product_count = $product_result ? $product_result->fetch_assoc()['count'] : 0;
    
    $user_count_query = "SELECT COUNT(*) AS count FROM users";
    $user_result = $conn->query($user_count_query);
    $user_count = $user_result ? $user_result->fetch_assoc()['count'] : 0;
    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Dashboard</title>
        <link rel="stylesheet" href="../assets/css/styles.css">
    </head>
    <body>
        <div class="container">
            <?php include 'admin_navigation.php'; ?>
            <div class="main-content">
                <h1 class="title">Admin Dashboard</h1>
                <h2 class="welcome-title">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
                <ul class="custom-list">
                    <li class="custom-list-item">Total Products: <?php echo $product_count; ?></li>
                    <li class="custom-list-item">Total Users: <?php echo $user_count; ?></li>
                </ul>
            </div>
        </div>
    </body>
</html>