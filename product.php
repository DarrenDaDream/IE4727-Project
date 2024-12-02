<?php include 'cartsession.php';
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
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
            <a href="onstruction.html">Login</a>
            <a href="cart.php">Cart</a>
        </div>
    </header> 

    <!-- Product Details Section -->
    <div class="product-container">
        <?php
        session_start();

        // Initialize the cart if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        // Flag to indicate if item was added to the cart
        $item_added_message = '';

        // Handle adding items to the cart
        if (isset($_GET['add_to_cart'])) {
            $variant_id = (int)$_GET['add_to_cart'];
            $size = $_GET['size'];
            if (isset($_SESSION['cart'][$variant_id])) {
                $_SESSION['cart'][$variant_id]['quantity'] += 1;
            } else {
                $_SESSION['cart'][$variant_id] = array(
                    'variant_id' => $variant_id,
                    'size' => $size,
                    'quantity' => 1
                );
            }
            $item_added_message = "Item added to cart!";
        }

        // Database configuration
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "Project";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get product ID from URL
        $product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        // Fetch main product details
        $sql = "SELECT * FROM products WHERE product_id = $product_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            echo '<div class="product-image"><img src="' . $product['main_image_url'] . '" alt="' . $product['name'] . '"></div>';
            echo '<div class="product-info">';
            echo '<h2>' . $product['name'] . '</h2>';
            echo '<p>' . $product['description'] . '</p>';
            echo '<p>Starting at $' . number_format($product['price'], 2) . '</p>';

            // Fetch available variants for this product (sizes)
            $variant_sql = "SELECT * FROM product_variants WHERE product_id = $product_id ORDER BY size";
            $variant_result = $conn->query($variant_sql);

            if ($variant_result && $variant_result->num_rows > 0) {
                echo '<label for="size">Select Size:</label>';
                echo '<select id="size" name="size">';
                
                // Loop through available variants (sizes)
                while ($variant = $variant_result->fetch_assoc()) {
                    echo '<option value="' . $variant['variant_id'] . '">' . $variant['size'] . '</option>';
                }
                echo '</select>';
                
                // Add to cart button
                echo '<button onclick="addToCart()">Add to Cart</button>';
            } else {
                echo '<p>Out of stock.</p>';
            }
            echo '</div>'; // Closing product-info div
        } else {
            echo '<p>Product not found.</p>';
        }

        $conn->close();
        ?>
    </div>

    <script>
    function addToCart() {
    const selectedSize = document.querySelector('select[name="size"]').value;
    const variantId = document.querySelector('select[name="size"] option:checked').value;
    
    // Add item to the cart by redirecting to the same page with 'add_to_cart' parameter
    window.location.href = "<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $product_id; ?>&add_to_cart=" + variantId + "&size=" + selectedSize;
}

    </script>

    <!-- Display item added message -->
    <?php if ($item_added_message): ?>
        <div class="cart-message">
            <p><?php echo $item_added_message; ?> <a href="cart.php">View Cart</a></p>
        </div>
    <?php endif; ?>
</body>
</html>
