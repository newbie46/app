<?php
    session_start();
    include_once '../config/database.php';
    
    // Get the search query, if there is one
    $search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
    
    // Execute a searchable database query
    $query = "SELECT * FROM products";
    if (!empty($search_query)) {
        $query .= " WHERE name LIKE '%$search_query%' OR description LIKE '%$search_query%'";
    }
    $result = $conn->query($query);
    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Welcome to Our Store!</title>
        <link rel="stylesheet" href="../assets/css/styles.css">
        <link rel="stylesheet" href="../assets/css/main_page_styles.css">
    </head>
    <body>
        <div class="container">
            <?php include 'navigation.php'; ?>
            <div class="main-content">
                <h1 class="title">Welcome to Our Store!</h1>
                <form method="GET" action="index.php" class="search-form">
                    <input type="text" name="search" id="search" class="search-input" placeholder="Type keywords..."
                     title="Enter keywords to search products" value="<?php echo htmlspecialchars($search_query); ?>">
                    <input type="submit" value="Search" class="search-submit">
                </form>
                <div class="index-product-list-wrapper">
                    <ul class="index-product-list">
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <li class="index-product-item">
                            <?php if (!empty($row['image'])): ?>
                            <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="index-product-image">
                            <?php else: ?>
                            <div class="no-image-placeholder">No Image</div>
                            <?php endif; ?>
                            <h2 class="index-product-title"><?php echo htmlspecialchars($row['name']); ?></h2>
                            <p class="index-product-price">Price: $<?php echo number_format($row['price'], 2); ?></p>
                            <a href="product.php?id=<?php echo $row['id']; ?>" class="index-product-link">View Details</a>
                            <a href="add_to_cart.php?id=<?php echo $row['id']; ?>" class="index-product-link">Add to Cart</a>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
            <?php include 'footer.php'; ?>
        </div>
    </body>
</html>