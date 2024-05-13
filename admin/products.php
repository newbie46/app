<?php
    session_start();
    // Checking the administrator role
    include_once '../config/admin_auth.php';
    
    include_once '../config/database.php';
    
    // Get the search query, if there is one
    $search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
    
    // Form the SQL query depending on the search query
    $query = "SELECT * FROM products";
    if (!empty($search_query)) {
        $query .= " WHERE name LIKE '%$search_query%' OR category LIKE '%$search_query%' OR description LIKE '%$search_query%'";
    }
    $query .= " ORDER BY id ASC";
    $result = $conn->query($query);
    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Manage Products</title>
        <link rel="stylesheet" href="../assets/css/styles.css">
        <link rel="stylesheet" href="../assets/css/admin_content_styles.css">
        <link rel="stylesheet" href="../assets/css/admin_table_styles.css">
    </head>
    <body>
        <div class="container">
            <?php include 'admin_navigation.php'; ?>
            <div class="main-content">
                <h1 class="title">Manage Products</h1>
                <form method="GET" action="products.php" class="search-form">
                    <input type="text" name="search" id="search" title="Enter a keyword to search for products" class="search-input"
                    placeholder="Product name, category, description..." value="<?php echo htmlspecialchars($search_query ?? ''); ?>">
                    <input type="submit" value="Search" class="search-submit">
                </form>
                <a href="add_product.php" class="add-new-product-link">Add New Product</a>
                <div class="table-container">
                <table border="1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td>
                                <?php if (!empty($row['image'])): ?>
                                    <?php echo basename($row['image']); ?>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['category']); ?></td>
                            <td><?php echo number_format($row['price'], 2); ?></td>
                            <td>
                                <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="edit-delete-link">Edit</a>
                                <a href="delete_product.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?');" class="edit-delete-link">Delete</a>
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