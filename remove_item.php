<?php
session_start();

// Check if the cart exists
if (isset($_SESSION['cart'])) {
    // Check if a variant_id is provided
    if (isset($_POST['variant_id'])) {
        $variant_id = $_POST['variant_id'];

        // Remove the item from the cart
        if (isset($_SESSION['cart'][$variant_id])) {
            unset($_SESSION['cart'][$variant_id]);
        }
    }
}

// Redirect back to the cart page
header("Location: cart.php");
exit();
?>
