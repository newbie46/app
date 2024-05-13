<?php
    session_start();
    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Shopping Cart</title>
        <link rel="stylesheet" href="../assets/css/styles.css">
        <link rel="stylesheet" href="../assets/css/cart_styles.css">
    </head>
    <body>
        <div class="container">
            <?php include 'navigation.php'; ?>
            <div class="main-content">
                <h1 class="title">Your Shopping Cart</h1>
                <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                <form method="POST" action="update_cart.php">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $total = 0;
                                foreach ($_SESSION['cart'] as $product_id => $details):
                                    $subtotal = $details['price'] * $details['quantity'];
                                    $total += $subtotal;
                                ?>
                            <tr>
                                <td><?php echo htmlspecialchars($details['name']); ?></td>
                                <td><?php echo number_format($details['price'], 2); ?></td>
                                <td>
                                    <label for="quantity-<?php echo $product_id; ?>">Quantity:</label>
                                    <input type="number" name="quantity[<?php echo $product_id; ?>]" id="quantity-<?php echo $product_id; ?>" value="<?php echo $details['quantity']; ?>" min="1" title="Enter the desired quantity" placeholder="Quantity">
                                </td>
                                <td><?php echo number_format($subtotal, 2); ?></td>
                                <td>
                                    <a href="remove_from_cart.php?id=<?php echo $product_id; ?>" class="cart-action-link">Remove</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="cart-actions">
                        <h2 class="cart-total">Total: <?php echo number_format($total, 2); ?></h2>
                        <input type="submit" value="Update Cart" class="cart-update">
                        <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="checkout.php" class="cart-checkout">Proceed to Checkout</a>
                        <?php else: ?>
                        <a href="login.php" class="cart-checkout">Log in to proceed to checkout</a>
                        <?php endif; ?>
                    </div>
                </form>
                <?php else: ?>
                <p class="content">Your cart is empty.</p>
                <?php endif; ?>
            </div>
            <?php include 'footer.php'; ?>
        </div>
    </body>
</html>