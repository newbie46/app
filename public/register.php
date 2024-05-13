<?php
    include_once '../config/database.php';

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = trim(mysqli_real_escape_string($conn, $_POST['name']));
        $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
        $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

        // Check for errors
        if (empty($name) || empty($email) || empty($_POST['password'])) {
            $errors[] = 'All fields are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format.';
        }

        // Check for the existence of a user with the same email address
        $check_user = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($check_user);
        if ($result && $result->num_rows > 0) {
            $errors[] = 'A user with this email already exists.';
        }

        // Add user if there are no errors
        if (empty($errors)) {
            $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
            if ($conn->query($query)) {
                header("Location: login.php");
            } else {
                $errors[] = 'Registration failed. Please try again.';
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register</title>
        <link rel="stylesheet" href="../assets/css/styles.css">
        <link rel="stylesheet" href="../assets/css/authorization_styles.css">
    </head>
    <body>
        <div class="container">
            <?php include 'navigation.php'; ?>
            <div class="main-content">
                <h1 class="title">Register</h1>
                <?php if (!empty($errors)): ?>
                <ul class="error-list">
                    <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                <form method="POST" class="authorization-form">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" name="name" id="name" class="form-input" title="Enter your full name" placeholder="Full Name" autocomplete="name" required>
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" name="email" id="email" class="form-input" title="Enter your email address" placeholder="Email Address" autocomplete="email" required>
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" name="password" id="password" class="form-input" title="Create a secure password" placeholder="Password" autocomplete="new-password" required>
                    <input type="submit" value="Register" class="form-submit">
                    <a href="google_login.php" class="google-login-link">Login with Google</a>
                </form>
            </div>
            <?php include 'footer.php'; ?>
        </div>
    </body>
</html>
