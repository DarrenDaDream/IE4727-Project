<?php 
include('cartsession.php');

// Get the order ID from the URL
$order_id = $_GET['order_id'];

// Fetch order details from the orders table
$sql = "SELECT * FROM orders WHERE order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You!</title>
    <style>
        .logo-row {
    display: flex;
    justify-content: center; /* Centers horizontally */
    align-items: center;     /* Centers vertically */
    padding: 20px;
    background-color: #333;  /* Optional background color */
}
/* Style the logo link */
.logo-row a {
    font-size: 2em;          /* Adjust font size as needed */
    color: #fff;             /* Optional text color */
    text-decoration: none;
    font-weight: bold;
    font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
}
        /* Order Confirmation Container */
        .order-confirmation-container {
            width: 80%;
            max-width: 700px;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin: 20px auto;
        }

        .order-confirmation-title {
            font-size: 28px;
            color: #4CAF50;
            margin-bottom: 10px;
        }

        .order-confirmation-message {
            font-size: 18px;
            color: #666;
            margin-bottom: 20px;
        }

        .order-number, .total-price {
            font-size: 16px;
            color: #333;
            margin: 5px 0;
            font-weight: bold;
        }

        /* Additional styles to ensure high priority */
        body {
            font-family: monospace;
            color: #151414;
            background-color: #ffffff; 
        }

        .order-items-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .order-items-table th, .order-items-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .order-items-table th {
            background-color: #f4f4f4;
        }

        .order-items-table img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            margin-right: 15px;
        }

        .no-items-message {
            font-size: 18px;
            color: #666;
        }

        .error-message {
            font-size: 18px;
            color: red;
        }
    </style>
</head>
<body>

    <!-- Logo -->
    <div class="logo-row">
        <a href="Main.php">DDD</a>
    </div>

    <!-- Order Confirmation Container -->
    <div class="order-confirmation-container">
        <?php if ($order): ?>
            <h1 class="order-confirmation-title">Order Confirmation</h1>
            <p class="order-confirmation-message">Thank you for your order, <?= htmlspecialchars($order['firstname']) . " " . htmlspecialchars($order['lastname']); ?>!</p>
            <p class="order-number">Your order number is: <?= htmlspecialchars($order['order_id']); ?></p>
            <p class="total-price">Total Price: $<?= number_format($order['total_price'], 2); ?></p>

            <h2 class="order-items-title">Order Items</h2>
            <?php
            // Fetch the order items from the order_items table
            $sql_items = "SELECT oi.quantity, pv.size, pv.variant_id, p.name, pv.price, p.main_image_url
                          FROM order_items oi
                          JOIN product_variants pv ON oi.variant_id = pv.variant_id
                          JOIN products p ON pv.product_id = p.product_id
                          WHERE oi.order_id = ?";
            $stmt_items = $conn->prepare($sql_items);
            $stmt_items->bind_param("i", $order_id);

            if ($stmt_items->execute()) {
                $result_items = $stmt_items->get_result();
                if ($result_items->num_rows > 0): ?>
                    <table class="order-items-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Image</th>
                                <th>Size</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($item = $result_items->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['name']); ?></td>
                                    <td><img src="<?= htmlspecialchars($item['main_image_url']); ?>" alt="Product Image"></td>
                                    <td><?= htmlspecialchars($item['size']); ?></td>
                                    <td><?= htmlspecialchars($item['quantity']); ?></td>
                                    <td>$<?= number_format($item['price'], 2); ?></td>
                                    <td>$<?= number_format($item['price'] * $item['quantity'], 2); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="no-items-message">No items found for this order.</p>
                <?php endif;
            } else {
                echo "<p class='error-message'>Error fetching order items: " . $stmt_items->error . "</p>";
            }
            ?>
        <?php else: ?>
            <p class="order-not-found-message">Order not found.</p>
        <?php endif; ?>
    </div>

</body>
</html>

<?php
// Close statements and connection
$stmt->close();
$stmt_items->close();
$conn->close();
?>
