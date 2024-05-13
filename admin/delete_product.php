<?php
    session_start();
    // Checking the administrator role
    include_once '../config/admin_auth.php';
    
    include_once '../config/database.php';

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        // Deleting a product from the database
    if ($id) {
        $query = "DELETE FROM products WHERE id = $id";
        if ($conn->query($query)) {
            // Successfully deleted
            header('Location: products.php');
        } else {
            // Error handling
            $_SESSION['error'] = "Failed to delete product: " . $conn->error;
            header('Location: products.php');
        }
        exit;
    }
