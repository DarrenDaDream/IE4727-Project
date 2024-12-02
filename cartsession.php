<?php
session_start();

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

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Calculate total quantity in the cart
$total_quantity = 0;
foreach ($_SESSION['cart'] as $details) {
    $total_quantity += $details['quantity']; // Sum the quantities
}
?>
