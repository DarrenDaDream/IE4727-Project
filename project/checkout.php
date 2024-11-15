<?php 
include 'cartsession.php';


// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Project";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get cart items from session
$cart_items = [];
$total_price = 0;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $variant_id => $details) {
        $sql = "SELECT pv.variant_id, p.name, p.description, pv.price, pv.size ,p.main_image_url
                FROM product_variants pv 
                JOIN products p ON pv.product_id = p.product_id 
                WHERE pv.variant_id = $variant_id";
        
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $row['quantity'] = $details['quantity'];
                $cart_items[] = $row;
                $total_price += $row['price'] * $details['quantity'];
            }
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Basic styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        
        .checkout-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Columns for larger screens */
        .checkout-form, .order-summary {
            flex: 1 1 45%; /* Adjusts to 45% width, wraps if screen is small */
            min-width: 300px;
        }

        /* Form styling */
        .checkout-form h3, .order-summary h3 {
            margin-bottom: 10px;
        }
        
        label, input, select {
            display: block;
            width: 100%;
            margin: 5px 0;
        }
        
        input, select {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        
        button {
            padding: 10px;
            width: 100%;
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            margin-top: 10px;
        }

        /* Order summary styling */
        .order-summary table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .order-summary th, .order-summary td {
            padding: 8px;
            border-bottom: 1px solid #ccc;
        }
        .order-summary img 
        { width: 50px; height: 50px; object-fit: cover; margin-right: 10px; }
        
        /* Responsive flex layout */
        @media (max-width: 600px) {
            .checkout-container {
                flex-direction: column;
            }
            
        }
    </style>
</head>
<body>
    <!-- Logo Section -->
    <div class="logo-row">
        <a href="Main.php">DDD</a>
    </div>

<div class="checkout-container">
    <!-- Left column: Payment and Address -->
    <div class="checkout-form">
        <h3>Customer Information</h3>
        <form action="process_checkout.php" method="POST">

        <label for="country">Country:</label>
            <select id="country" name="country" required>
                <option value="SG">Singapore</option>
                <option value="MY">Malaysia</option>
                <option value="TH">Thailand</option>
                <option value="IN">Indonesia</option>
            </select>

            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" required>

            <label for="lastname">Last Name:</label>
            <input type="text" id="lastname" name="lastname" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="address">Shipping Address:</label>
            <input type="text" id="address" name="address" required>
            
            <label for="postal">Postal Code:</label>
            <input type="text" id="postal" name="postal" required>

            <!-- Payment Method Section -->
            <h3>Payment Method</h3>
            <label for="payment">Select Payment Method:</label>
            <select id="payment" name="payment_method" onchange="togglePaymentFields()">
                <option value="credit_card">Credit Card</option>
                <option value="paypal">PayPal</option>
            </select>

            <!-- Credit Card Fields (Always visible) -->
            <div id="credit-card-fields">
                <label for="card_number">Card Number:</label>
                <input type="text" id="card_number" name="card_number" pattern="\d{16}" maxlength="16" placeholder="1234 5678 9012 3456" oninput="formatCardNumber()" required>

                <label for="expiry_date">Expiry Date (MM/YY):</label>
                <input type="text" id="expiry_date" name="expiry_date" pattern="\d{2}/\d{2}" maxlength="5" placeholder="MM/YY" oninput="formatExpiryDate()" required>


                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" pattern="\d{3}" maxlength="3" placeholder="123" required>
            </div>

            <!-- PayPal Fields (Initially hidden) -->
            <div id="paypal-fields" style="display: none;">
                <p>You will be redirected to PayPal for payment.</p>
            </div>
            <button type="submit" name="place_order">Place Order</button>
        </form>
    </div>

    <!-- Right column: Order Summary and Discount Code -->
    <div class="order-summary">
        <h3>Order Summary</h3>
        <table>
            <tr>
                <th></th>
                <th>Item</th>
                <th>Size</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
            <?php if (!empty($cart_items)): ?>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                    <td><img src="<?php echo $item['main_image_url']; ?>" alt="Product Image"></td>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo htmlspecialchars($item['size']); ?></td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">Your cart is empty.</td></tr>
            <?php endif; ?>
        </table>
        <h3>Total: $<?php echo number_format($total_price, 2); ?></h3>

        <!-- Discount Code -->
        <label for="discount">Discount Code:</label>
        <input type="text" id="discount" name="discount" placeholder="Enter code">

        <button type="button" onclick="applyDiscount()">Apply Discount</button>
    </div>
</div>

<script>
function applyDiscount() {
    // Placeholder for discount code application
    alert("Discount applied!");
}
</script>
<script src="updateprice.js"></script>

</body>
</html>
