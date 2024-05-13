<?php
    session_start();
    // Checking the administrator role
    include_once '../config/admin_auth.php';
    
    include_once '../config/database.php';
    
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $errors = [];
    
    // Get product data
    $query = "SELECT * FROM products WHERE id = $id";
    $result = $conn->query($query);
    $product = $result ? $result->fetch_assoc() : null;
    
    if (!$product) {
        die('Product not found.');
    }
    
    // Processing of the product update form
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $price = floatval($_POST['price']);
        $image = $product['image']; // Save the existing image
    
        // Check if the input is correct
        if (empty($name) || empty($category) || empty($price)) {
            $errors[] = 'Name, category, and price are required.';
        }
    
        // Process image loading if a new image is selected
        if (!empty($_FILES['image']['name'])) {
            $target_dir = "../uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }
    
            $target_file = $target_dir . basename($_FILES['image']['name']);
            $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    
            if (in_array($image_file_type, $valid_extensions)) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    $image = $target_file;
                } else {
                    $errors[] = 'Failed to upload the image. Please check folder permissions.';
                }
            } else {
                $errors[] = 'Invalid image format. Only JPG, JPEG, PNG, and GIF are allowed.';
            }
        }
    
        // Update product data
        if (empty($errors)) {
            $query = "UPDATE products SET name='$name', category='$category', description='$description', price=$price, image='$image' WHERE id=$id";
            if ($conn->query($query)) {
                header('Location: products.php');
                exit;
            } else {
                $errors[] = 'Error updating product.';
            }
        }
    }
    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Product</title>
        <link rel="stylesheet" href="../assets/css/styles.css">
        <link rel="stylesheet" href="../assets/css/admin_content_styles.css">
    </head>
    <body>
        <div class="container">
            <?php include 'admin_navigation.php'; ?>
            <div class="main-content">
                <h1 class="title">Edit Product</h1>
                <?php if (!empty($errors)): ?>
                <ul>
                    <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                <form method="POST" enctype="multipart/form-data" class="admin-form">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" title="Enter the product name" placeholder="Product Name" value="<?php echo htmlspecialchars($product['name']); ?>" autocomplete="off" required><br>
                    <label for="category">Category:</label>
                    <input type="text" name="category" id="category" title="Enter the product category" placeholder="Category" value="<?php echo htmlspecialchars($product['category']); ?>" autocomplete="off" required><br>
                    <label for="description">Description:</label>
                    <textarea name="description" id="description" title="Enter the product description" placeholder="Description"><?php echo htmlspecialchars($product['description']); ?></textarea><br>
                    <label for="price">Price:</label>
                    <input type="number" name="price" id="price" title="Enter the product price" placeholder="Price" step="0.01" value="<?php echo $product['price']; ?>" required><br>
                    <label for="image">Image (current shown below):</label>
                    <input type="file" name="image" id="image" title="Upload a new image for the product"><br>
                    <?php if (!empty($product['image'])): ?>
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" width="100"><br>
                    <?php endif; ?>
                    <input type="submit" value="Update Product">
                </form>
            </div>
        </div>
    </body>
</html>