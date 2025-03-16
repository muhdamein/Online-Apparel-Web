<?php
session_start();
include('../config/dbcon.php');

if (!isset($_SESSION['auth'])) {
    header('Location: ../index.php');
    exit(0);
}

if (isset($_POST['payBtn'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $cardNumber = mysqli_real_escape_string($con, $_POST['card-num']);
    $expiryDate = mysqli_real_escape_string($con, $_POST['exp']);
    $cvv = mysqli_real_escape_string($con, $_POST['cvv']);
    $userId = $_SESSION['auth_user']['user_id'];

    if ($name == "" || $cardNumber == "" || $expiryDate == "" || $cvv == "") {
        $_SESSION['message'] = "All fields are mandatory";
        header('Location: ../card.php');
        exit(0);
    }

    // Calculate total price
    $totalPrice = isset($_POST['total_price']) ? $_POST['total_price'] : 0;

    // Generate tracking number
    $tracking_no = "ThirdAlley" . rand(1111, 9999) . substr($cardNumber, -4);

    // Insert card details into database
    $insertCardQuery = "INSERT INTO cards (user_id, name, card_number, expiry_date, cvv, saved, created_at) 
                        VALUES (?, ?, ?, ?, ?, 1, NOW())";
    $stmt = $con->prepare($insertCardQuery);
    $stmt->bind_param("issss", $userId, $name, $cardNumber, $expiryDate, $cvv);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Insert order details
        $insert_order_query = "INSERT INTO orders (tracking_no, user_id, total_price, payment_mode, payment_id) 
                               VALUES (?, ?, ?, 'Credit Card', '')";
        $stmt_order = $con->prepare($insert_order_query);
        $stmt_order->bind_param("sii", $tracking_no, $userId, $totalPrice);
        $stmt_order->execute();

        // Get the order ID
        $orderId = $stmt_order->insert_id;

        // Insert order items and update product visibility
        $insert_items_query = "INSERT INTO order_items (order_id, prod_id, price) 
                               SELECT ?, prod_id, selling_price FROM carts WHERE user_id = ?";
        $stmt_items = $con->prepare($insert_items_query);
        $stmt_items->bind_param("ii", $orderId, $userId);
        $stmt_items->execute();

        // Update product visibility
        $update_product_query = "UPDATE products p
                                 INNER JOIN carts c ON p.id = c.prod_id
                                 SET p.status = 1
                                 WHERE c.user_id = ?";
        $stmt_update_product = $con->prepare($update_product_query);
        $stmt_update_product->bind_param("i", $userId);
        $stmt_update_product->execute();

        // Clear user's cart
        $deleteCartQuery = "DELETE FROM carts WHERE user_id = ?";
        $stmt_delete_cart = $con->prepare($deleteCartQuery);
        $stmt_delete_cart->bind_param("i", $userId);
        $stmt_delete_cart->execute();

        $_SESSION['message'] = 'Order placed successfully';
        header('Location: ../myorders.php');
        exit(0);
    } else {
        $_SESSION['message'] = 'Failed to save card details';
        header('Location: ../card.php');
        exit(0);
    }
} else {
    $_SESSION['message'] = 'Invalid request';
    header('Location: ../card.php');
    exit(0);
}
?>
