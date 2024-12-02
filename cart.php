<?php
include 'cartsession.php';
// Update cart quantities based on user input, ensuring quantities are valid and don't exceed stock
if (isset($_POST['quantity'])) {
    foreach ($_POST['quantity'] as $variant_id => $requested_quantity) {
        // Enforce a minimum quantity of 1
        if ($requested_quantity < 1) {
            $requested_quantity = 1;
        }

        // Fetch the available stock for this variant from the database
        $sql = "SELECT QNTY FROM product_variants WHERE variant_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $variant_id);
        $stmt->execute();
        $stmt->bind_result($available_quantity);
        $stmt->fetch();
        $stmt->close();

        // Ensure requested quantity doesn't exceed stock
        if ($requested_quantity > $available_quantity) {
            $_SESSION['cart'][$variant_id]['quantity'] = $available_quantity; // Limit to max available
            $_SESSION['cart'][$variant_id]['exceeds_stock'] = true; // Set flag for feedback
        } else {
            $_SESSION['cart'][$variant_id]['quantity'] = $requested_quantity;
            unset($_SESSION['cart'][$variant_id]['exceeds_stock']); // Remove flag if within stock
        }

        // Remove item if quantity is 0
        if ($_SESSION['cart'][$variant_id]['quantity'] == 0) {
            unset($_SESSION['cart'][$variant_id]);
        }
    }
}

// Handle emptying the cart
if (isset($_POST['empty_cart'])) {
    $_SESSION['cart'] = array(); // Clear the cart
    header("Location: cart.php"); // Redirect to the cart page
    exit(); // Make sure to exit after redirect
}



// Fetch item details from the database based on the cart
foreach ($_SESSION['cart'] as $variant_id => $details) {
    $sql = "SELECT pv.variant_id, p.name, p.description, pv.price, pv.size, pv.QNTY 
            FROM product_variants pv 
            JOIN products p ON pv.product_id = p.product_id 
            WHERE pv.variant_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $variant_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $row['quantity'] = $details['quantity']; // Quantity in the cart
            $row['exceeds_stock'] = isset($details['exceeds_stock']); // Feedback flag
            $cart_items[] = $row;
            $total_price += $row['price'] * $details['quantity'];
        }
    }
    $stmt->close();
}
// Store the total price in the session
$_SESSION['total_price'] = $total_price;
// // Add to cart session
// $_SESSION['cart'][$variant_id] = $cart_item;
$conn->close();

// Calculate total quantity of items in the cart
$total_quantity = array_sum(array_column($_SESSION['cart'], 'quantity'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
</head>
<body>
    <!-- Logo Section -->
    <div class="logo-row">
        <a href="Main.php">DDD</a>
    </div>

    <!-- Navigation Section -->
    <header>
        <nav>
            <ul>
                <li><a href="newarrivals.php">New Arrivals</a></li>
                <li><a href="clothing.php">Clothing</a></li>
                <li><a href="footwear.php">Footwear</a></li>
                <li><a href="accessories.php">Accessories</a></li>
                <li><a href="construction.html">Sale</a></li>
            </ul>
        </nav>
        <div class="user-options">
            <a href="construction.html">Login</a>
            <a href="cart.php">Cart (<?php echo $total_quantity; ?>)</a>
        </div>
    </header> 

    <!-- Shopping Cart Section -->
    <div class="cart-container">
        <h2>Your Shopping Cart</h2>
        
        <?php if (empty($cart_items)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <form method="POST" action="cart.php" id="cart-form">
                <table>
                    <tr>
                        <th>Item</th>
                        <th>Description</th>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($cart_items as $item): ?>
                        <tr class="cart-item">
                            <td><?php echo $item['name']; ?></td>
                            <td><?php echo $item['description']; ?></td>
                            <td><?php echo $item['size']; ?></td>
                            <td>
                                <input type="number" class="quantity-input" 
                                       name="quantity[<?php echo $item['variant_id']; ?>]" 
                                       value="<?php echo $item['quantity']; ?>" 
                                       min="1" max="<?php echo $item['QNTY']; ?>" 
                                       style="width: 50px;"
                                       onchange="document.getElementById('cart-form').submit();">
                                <?php if ($item['exceeds_stock']): ?>
                                    <span class="error">Max: <?php echo $item['QNTY']; ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                $<span class="item-price"><?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                            </td>
                            <td>
                                <form method="POST" action="remove_item.php" style="display:inline;">
                                    <input type="hidden" name="variant_id" value="<?php echo $item['variant_id']; ?>">
                                    <button type="submit" name="remove_item">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <h3>Total: $<span id="total-price"><?php echo number_format($total_price, 2); ?></span></h3>
                <button type="submit" name="empty_cart">Empty Cart</button>
                <a href="checkout.php" class="checkout-button">Checkout</a>
            </form>
        <?php endif; ?>
    </div>

    <!-- Footer Section -->
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3>Customer Service</h3>
                <ul>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </div>
        </div>
    </footer>
</body>
</html>
