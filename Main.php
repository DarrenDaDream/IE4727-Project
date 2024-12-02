<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fashion Store</title>
    <link rel="stylesheet" href="style.css">
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
            <a href="cart.php">Cart</a>
        </div>
    </header>

    <!-- Main Section for Featured Content -->
    <main>
        <section class="hero">
            <h1>Discover the Latest Fashion Trends</h1>
            <p>Explore new arrivals, top collections, and more.</p>
            <a href="newarrivals.php" class="shop-now">Shop Now</a>
        </section>

        <!-- Featured Products Section -->
        <section class="featured-products">
            <h2>Featured Products</h2>
            <div class="product-grid">
                <?php
                // Database configuration
                $servername = "localhost";
                $username = "root"; // Your MySQL username
                $password = "";     // Your MySQL password
                $dbname = "Project"; // Your database name

                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch featured products
                $sql = "SELECT * FROM products WHERE is_featured = 'yes' ORDER BY date_added DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="product-card">';
                        echo '<a href="product.php?id=' . $row['product_id'] . '">'; 
                        echo '<img src="' . $row['main_image_url'] . '" alt="' . $row['name'] . '">';
                        echo '<h3>' . $row['name'] . '</h3>';
                        echo '<p>$' . $row['price'] . '</p>';
                        echo '</a>'; // Closing the link
                        echo '</div>';
                    }
                } else {
                    echo '<p>No featured products available.</p>';
                }

                $conn->close();
                ?>
            </div>
        </section>
    </main>

    <!-- Footer Section -->
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3>Customer Service</h3>
                <ul>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Shipping Information</a></li>
                    <li><a href="#">Returns & Exchanges</a></li>
                    <li><a href="#">Size Guide</a></li>
                    <li><a href="#">FAQs</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>About Us</h3>
                <ul>
                    <li><a href="#">Our Story</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Sustainability</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms & Conditions</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Follow Us</h3>
                <ul class="social-media">
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">Pinterest</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 DDD Fashion. All rights reserved.</p>
        </div>
    </footer>    
</body>
</html>
