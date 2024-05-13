<ul class="nav">
    <li><a href="index.php">Home</a></li>
    <li><a href="cart.php">View Cart</a></li>
    <?php if (isset($_SESSION['user_id'])): ?>
        <li><a href="orders.php">View Orders</a></li>
        <li><a href="profile.php">Edit Profile</a></li>
        <li><a href="logout.php">Logout</a></li>
    <?php else: ?>
        <li><a href="register.php">Register</a></li>
        <li><a href="login.php">Login</a></li>
    <?php endif; ?>
</ul>
