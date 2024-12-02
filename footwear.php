<?php
// Start the session to track cart information
include 'cartsession.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footwear</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
</head>
<body>
    <!-- Logo Section -->
    <div class="logo-row">
        <a href="Main.php" style="align-items: center;">DDD</a>
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
            <a href="cart.php">Cart (<?php echo $total_quantity; ?>)</a> <!-- Display total quantity from the session -->
        </div>
    </header> 

    <!-- Footwear Banner Section -->
    <section class="page-banner">
        <h1>Footwear</h1>
        <p>Discover the latest footwear to our collection</p>
    </section>

    <div class="container">
        <!-- Sidebar Section -->
        <aside class="sidebar">
            <h2>Sort by</h2>
            <ul>
                <li><a href="footwear.php?sort=newest">Newest</a></li>
                <li><a href="footwear.php?sort=oldest">Oldest</a></li>
                <li><a href="footwear.php?sort=price_low">Price: Low to High</a></li>
                <li><a href="footwear.php?sort=price_high">Price: High to Low</a></li>
            </ul>
        </aside>

        <!-- Product Grid Section -->
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

            // Fetch footwear products only
            $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

            // Define the SQL query based on the sorting option
            switch ($sort) {
                case 'oldest':
                    $sql = "SELECT * FROM products WHERE category = 'Footwear' ORDER BY date_added ASC";
                    break;
                case 'price_low':
                    $sql = "SELECT * FROM products WHERE category = 'Footwear' ORDER BY price ASC";
                    break;
                case 'price_high':
                    $sql = "SELECT * FROM products WHERE category = 'Footwear' ORDER BY price DESC";
                    break;
                case 'category':
                    $sql = "SELECT * FROM products WHERE category = 'Footwear' ORDER BY category ASC";
                    break;
                case 'newest':
                default:
                    $sql = "SELECT * FROM products WHERE category = 'Footwear' ORDER BY date_added DESC";
                    break;
            }
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="product">';
                    echo '<a href="product.php?id=' . $row['product_id'] . '">'; // Ensure correct ID is passed
                    echo '<img src="' . $row['main_image_url'] . '" alt="' . $row['name'] . '">';
                    echo '<h2>' . $row['name'] . '</h2>';
                    echo '<p>' . $row['description'] . '</p>';
                    echo '<p>$' . $row['price'] . '</p>';
                    echo '</a>'; // Closing the link
                    echo '</div>';
                }
            } else {
                echo 'No products found.';
            }

            $conn->close();
            ?>
        </div>
    </div>

</body>
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
</html>
