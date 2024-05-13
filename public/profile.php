<?php
    session_start();
    include_once '../config/database.php';
    
    // Check if the user is authorized
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
    
    $user_id = $_SESSION['user_id'];
    $errors = [];
    
    // Get user data from the database
    $query = "SELECT * FROM users WHERE id = $user_id";
    $result = $conn->query($query);
    $user = $result ? $result->fetch_assoc() : null;
    
    if (!$user) {
        die('User not found.');
    }
    
    // Check if the google_id field is empty
    $is_registered_via_site = empty($user['google_id']);
    
    // Processing of profile update
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = trim(mysqli_real_escape_string($conn, $_POST['name']));
        $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
        $current_password = trim($_POST['current_password']);
        $new_password = trim($_POST['password']);
        $shipping_address = trim(mysqli_real_escape_string($conn, $_POST['shipping_address']));
        $billing_address = trim(mysqli_real_escape_string($conn, $_POST['billing_address']));
    
        if (empty($name) || empty($email)) {
            $errors[] = 'Name and email are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format.';
        } else {
            // Check the current password
            if (!empty($new_password) && !password_verify($current_password, $user['password'])) {
                $errors[] = 'Current password is incorrect.';
            } else {
                // Update basic user information
                $query = "UPDATE users SET name='$name', email='$email' WHERE id=$user_id";
                if ($conn->query($query)) {
                    $_SESSION['user_name'] = $name;
    
                    // Update the password if a new one is entered
                    if (!empty($new_password)) {
                        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                        $conn->query("UPDATE users SET password='$hashed_password' WHERE id=$user_id");
                    }
    
                    // Update addresses additionally
                    $query = "UPDATE users SET shipping_address='$shipping_address', billing_address='$billing_address' WHERE id=$user_id";
                    $conn->query($query);
    
                    // Redirect to profile page after update
                    header('Location: profile.php');
                    exit;
                } else {
                    $errors[] = 'Error updating profile.';
                }
            }
        }
    }
    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Profile</title>
        <link rel="stylesheet" href="../assets/css/styles.css">
        <link rel="stylesheet" href="../assets/css/profile_styles.css">
    </head>
    <body>
        <div class="container">
            <?php include 'navigation.php'; ?>
            <div class="main-content">
                <h1 class="title">Edit Profile</h1>
                <?php if (!empty($errors)): ?>
                <ul class="error-list">
                    <?php foreach ($errors as $error): ?>
                    <li class="error-item"><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                <form class="profile-form" method="POST">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['name']); ?>" title="Your full name" placeholder="Full Name" readonly><br>
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" title="Your email address" placeholder="Email Address" readonly><br>
                    <?php if ($is_registered_via_site): ?>
                    <label for="current_password">Current Password (required to change password):</label>
                    <input type="password" name="current_password" id="current_password" title="Enter your current password to change to a new one" placeholder="Current Password"><br>
                    <label for="password">New Password (optional):</label>
                    <input type="password" name="password" id="password" title="Enter a new password if you want to change it" placeholder="New Password" autocomplete="new-password"><br>
                    <?php endif; ?>
                    <label for="shipping_address">Shipping Address:</label>
                    <textarea name="shipping_address" id="shipping_content" title="Enter your shipping address" placeholder="123 Main St, City, Country"><?php echo htmlspecialchars($user['shipping_address'] ?? ''); ?></textarea><br>
                    <label for="billing_address">Billing Address:</label>
                    <textarea name="billing_address" id="billing_address" title="Enter your billing address" placeholder="Enter your billing address"><?php echo htmlspecialchars($user['billing_address'] ?? ''); ?></textarea><br>
                    <input type="submit" value="Update Profile">
                </form>
            </div>
            <?php include 'footer.php'; ?>
        </div>
    </body>
</html>