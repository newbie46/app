<?php
    session_start();
    // Checking the administrator role
    include_once '../config/admin_auth.php';
    
    include_once '../config/database.php';
    
    $errors = [];
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $price = floatval($_POST['price']);
        $image = ''; // Empty string by default
    
        // Check mandatory fields
        if (empty($name) || empty($category) || empty($price)) {
            $errors[] = 'Name, category, and price are required.';
        }
    
        // Process image loading
        if (!empty($_FILES['image']['name'])) {
            // Specify the path to the download folder
            $target_dir = "../uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true); // Create a folder if it does not exist
            }
    
            // Define the full file name
            $target_file = $target_dir . basename($_FILES['image']['name']);
            $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
            // Check permissible image formats
            $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($image_file_type, $valid_extensions)) {
                // Move the downloaded file to the target folder
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    $image = $target_file;
                } else {
                    $errors[] = 'Failed to upload the image. Please check folder permissions.';
                }
            } else {
                $errors[] = 'Invalid image format. Only JPG, JPEG, PNG, and GIF are allowed.';
            }
        }
    
        // Adding the product to the database if there are no errors
        if (empty($errors)) {
            $query = "INSERT INTO products (name, category, description, price, image) VALUES ('$name', '$category', '$description', $price, '$image')";
            if ($conn->query($query)) {
                header('Location: products.php');
                exit;
            } else {
                $errors[] = 'Error adding product.';
            }
        }
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/admin_content_styles.css">
</head>
<body>
    <div class="container">
        <?php include 'admin_navigation.php'; ?>
        <div class="main-content">
            <h1 class="title">Add New Product</h1>
            <?php if (!empty($errors)): ?>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data" class="admin-form">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required><br>
                <label for="category">Category:</label>
                <input type="text" name="category" id="category" required><br>
                <label for="description">Description:</label>
                <textarea name="description" id="description"></textarea><br>
                <label for="price">Price:</label>
                <input type="number" name="price" id="price" step="0.01" required><br>
                <label for="image">Image:</label>
                <input type="file" name="image" id="image"><br>
                <input type="submit" value="Add Product">
            </form>
        </div>
    </div>
</body>
</html>
