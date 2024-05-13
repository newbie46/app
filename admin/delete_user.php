<?php
    session_start();
    // Checking the administrator role
    include_once '../config/admin_auth.php';
    
    include_once '../config/database.php';

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        // Deleting a user from the database
    if ($id) {
        $query = "DELETE FROM users WHERE id = $id";
        if ($conn->query($query)) {
            // Successfully deleted
            header('Location: users.php');
        } else {
            // Error handling
            $_SESSION['error'] = "Failed to delete user: " . $conn->error;
            header('Location: users.php');
        }
        exit;
    }
