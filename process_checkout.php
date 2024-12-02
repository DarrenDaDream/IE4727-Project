<?php
include('cartsession.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture the form data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $postal = $_POST['postal'];
    $payment_method = $_POST['payment_method'];
    $total_price = $_SESSION['total_price'];

    // Insert the order data into the `orders` table
    $sql = "INSERT INTO orders (firstname, lastname, email, address, postal, payment_method, total_price) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssssd", $firstname, $lastname, $email, $address, $postal, $payment_method, $total_price);
        
        if ($stmt->execute()) {
            // Get the last inserted order_id
            $order_id = $conn->insert_id;

            // Now insert the items into the `order_items` table
            foreach ($_SESSION['cart'] as $item) {
                $variant_id = $item['variant_id'];
                $quantity = $item['quantity'];

                // Insert each item into the order_items table
                $sql_order_items = "INSERT INTO order_items (order_id, variant_id, quantity)
                                    VALUES (?, ?, ?)";

                if ($stmt_order_items = $conn->prepare($sql_order_items)) {
                    $stmt_order_items->bind_param("iii", $order_id, $variant_id, $quantity);
                    
                    if (!$stmt_order_items->execute()) {
                        echo "Error inserting order item: " . $stmt_order_items->error;
                    }
                    $stmt_order_items->close();
                }
            }

            // Order placed successfully, now clear the session cart and total price
            unset($_SESSION['cart']);
            unset($_SESSION['total_price']);
            
            // Redirect to order confirmation page
            header("Location: order_confirmation.php?order_id=$order_id");
            exit();

        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $conn->error;
    }
}

$conn->close();
?>
