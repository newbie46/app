<?php
    session_start();
    include_once '../config/database.php';
    
    $errors = [];
    $return_url = isset($_SESSION['return_url']) ? $_SESSION['return_url'] : 'index.php';
    
    // Check the input form
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
    
        // Search for a user by e-mail
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    
        // Check the password and save the data in the session
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
        }

        // Role-based redirection
        if ($user['role'] == 'admin') {
            header('Location: ../admin/index.php');
        } else {
            header('Location: index.php');
        }
        exit;
    }

    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="../assets/css/styles.css">
        <link rel="stylesheet" href="../assets/css/authorization_styles.css">
    </head>
    <body>
        <div class="container">
            <?php include 'navigation.php'; ?>
            <div class="main-content">
                <h1 class="title">Login</h1>
                <?php if (!empty($errors)): ?>
                <ul class="error-list">
                    <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                <form method="POST" class="authorization-form">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" name="email" id="email" class="form-input" title="Enter your email address" placeholder="Email Address" autocomplete="email" required>
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" name="password" id="password" class="form-input" title="Enter your password" placeholder="Password" autocomplete="current-password" required>
                    <input type="submit" value="Login" class="form-submit">
                    <a href="google_login.php" class="google-login-link">Login with Google</a>
                </form>
            </div>
            <?php include 'footer.php'; ?>
        </div>
    </body>
</html>