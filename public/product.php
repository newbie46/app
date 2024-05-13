<?php
   session_start();
   include_once '../config/database.php';
   
   // Get product ID
   $product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
   
   // Get product data
   $query = "SELECT * FROM products WHERE id = $product_id";
   $result = $conn->query($query);
   $product = $result ? $result->fetch_assoc() : null;
   
   if (!$product) {
       die('Product not found.');
   }
   ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/product_styles.css">
</head>
<body>
    <div class="container">
        <?php include 'navigation.php'; ?>
        <div class="main-content">
            <div class="product-container split-view">
                <div class="product-image">
                    <?php 
                    $imagePath = !empty($product['image']) ? htmlspecialchars($product['image']) : '../assets/images/default.jpg';
                    ?>
                    <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" width="100%">
                </div>
                <div class="product-details">
                    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                    <div class="product-info">
                        <p>Category: <?php echo htmlspecialchars($product['category']); ?></p>
                        <p>Description: <?php echo htmlspecialchars($product['description']); ?></p>
                        <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                    </div>
                    <a href="add_to_cart.php?id=<?php echo $product_id; ?>" class="add-to-cart-button">Add to Cart</a>
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>


