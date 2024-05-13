<?php
    session_start();
    // Checking the administrator role
    include_once '../config/admin_auth.php';
    
    include_once '../config/database.php';
    
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $errors = [];
    
    // Get user data by ID
    $query = "SELECT * FROM users WHERE id = $id";
    $result = $conn->query($query);
    $user = $result ? $result->fetch_assoc() : null;
    
    if (!$user) {
        die('User not found.');
    }
    
    // Edit form processing
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $role = mysqli_real_escape_string($conn, $_POST['role']);
    
        // Input check
        if (empty($name) || empty($email)) {
            $errors[] = 'Name and email are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format.';
        } else {
            // Update user information
            $query = "UPDATE users SET name='$name', email='$email', role='$role' WHERE id=$id";
            if ($conn->query($query)) {
                header('Location: users.php');
                exit;
            } else {
                $errors[] = 'Error updating user information.';
            }
        }
    }
    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit User</title>
        <link rel="stylesheet" href="../assets/css/styles.css">
        <link rel="stylesheet" href="../assets/css/admin_content_styles.css">
    </head>
    <body>
        <div class="container">
            <?php include 'admin_navigation.php'; ?>
            <div class="main-content">
                <h1 class="title">Edit User</h1>
                <?php if (!empty($errors)): ?>
                <ul>
                    <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                <form method="POST" class="admin-form">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" title="Enter the user's name" placeholder="Full Name" value="<?php echo htmlspecialchars($user['name']); ?>" autocomplete="name" required><br>
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" title="Enter the user's email address" placeholder="Email Address" value="<?php echo htmlspecialchars($user['email']); ?>" autocomplete="email" required><br>
                    <label for="role">Role:</label>
                    <select name="role" id="role" title="Select the user's role">
                        <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                    </select>
                    <br>
                    <input type="submit" value="Update">
                </form>
            </div>
        </div>
    </body>
</html>