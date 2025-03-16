<?php
session_start();
include('../config/dbcon.php');

// Check if user is authenticated
if (!isset($_SESSION['auth'])) {
    header('Location: ../index.php');
    exit(0);
}
// Check if place order button is clicked
if (isset($_POST['placeOrderBtn'])) {

    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $pincode = mysqli_real_escape_string($con, $_POST['pincode']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $payment_mode = mysqli_real_escape_string($con, $_POST['payment_method']);
    $payment_id = mysqli_real_escape_string($con, $_POST['payment_id']);

    // Check if mandatory fields are filled
    if ($name == "" || $email == "" || $phone == "" || $pincode == "" || $address == "") {
        $_SESSION['message'] = "All fields are mandatory";
        header('Location: ../checkout.php');
        exit(0);
    }

    $userId = $_SESSION['auth_user']['user_id'];

    // Get cart items for the user
    $query = "SELECT c.id as cid, c.prod_id, p.id as pid, p.name, p.image, p.selling_price 
              FROM carts c, products p 
              WHERE c.prod_id = p.id AND c.user_id = ? 
              ORDER BY c.id DESC";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $totalPrice = 0;
    $cartItems = [];

    while ($row = $result->fetch_assoc()) {
        $totalPrice += $row['selling_price'];
        $cartItems[] = $row;
    }

    // Redirect to card.php if payment method is Credit Card
    if ($payment_mode == 'Credit Card') {
        // Store form data in session variables
        $_SESSION['billing_details'] = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'pincode' => $_POST['pincode'],
            'address' => $_POST['address'],
            'payment_method' => $_POST['payment_method'],
            'payment_id' => $_POST['payment_id'],
            'totalPrice' => $totalPrice
        ];

        header('Location: ../card.php');
        exit(0);
    }

    // Generate tracking number
    $tracking_no = "ThirdAlley" . rand(1111, 9999) . substr($phone, 2);

    // Insert order details
    $insert_order_query = "INSERT INTO orders (tracking_no, user_id, name, email, phone, address, pincode, total_price, payment_mode, payment_id) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($insert_order_query);
    $stmt->bind_param("sissssisss", $tracking_no, $userId, $name, $email, $phone, $address, $pincode, $totalPrice, $payment_mode, $payment_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $order_id = $stmt->insert_id;

        // Insert order items and update product visibility and trending
$insert_items_query = "INSERT INTO order_items (order_id, prod_id, price) VALUES (?, ?, ?)";
$stmt_items = $con->prepare($insert_items_query);

$update_product_query = "UPDATE products SET status = 1, trending = 0 WHERE id = ?";
$stmt_update_product = $con->prepare($update_product_query);

foreach ($cartItems as $item) {
    $stmt_items->bind_param("iii", $order_id, $item['prod_id'], $item['selling_price']);
    $stmt_items->execute();

    $stmt_update_product->bind_param("i", $item['prod_id']);
    $stmt_update_product->execute();
}


        // Clear user's cart
        $deleteCartQuery = "DELETE FROM carts WHERE user_id = ?";
        $stmt_delete_cart = $con->prepare($deleteCartQuery);
        $stmt_delete_cart->bind_param("i", $userId);
        $stmt_delete_cart->execute();

        $_SESSION['message'] = 'Order placed successfully';

        // Redirect based on payment method
        if ($payment_mode == 'COD') {
            $_SESSION['message'] = 'Order placed successfully';
            header('Location: ../myorders.php');
            exit(0);
        }
    } else {
        $_SESSION['message'] = 'Order placement failed';
        header('Location: ../checkout.php');
        exit(0);
    }
}

if (isset($_POST['placeOrderCreditCardBtn'])) {

    $billingDetails = $_SESSION['billing_details'] ?? [];
    $name = htmlspecialchars($billingDetails['name'] ?? 'N/A');
    $email = htmlspecialchars($billingDetails['email'] ?? 'N/A');
    $phone = htmlspecialchars($billingDetails['phone'] ?? 'N/A');
    $pincode = htmlspecialchars($billingDetails['pincode'] ?? 'N/A');
    $address = htmlspecialchars($billingDetails['address'] ?? 'N/A');
    $payment_mode = htmlspecialchars($billingDetails['payment_method'] ?? 'N/A');
    $payment_id = htmlspecialchars($billingDetails['payment_id'] ?? 'N/A');
    $totalPrice = htmlspecialchars($billingDetails['totalPrice'] ?? 'N/A');
    $chk = mysqli_real_escape_string($con, $_POST['chk']);
    $existingCard = mysqli_escape_string($con, $_POST['existingCard']);

    $userId = $_SESSION['auth_user']['user_id'];

    // Get cart items for the user
    $query = "SELECT c.id as cid, c.prod_id, p.id as pid, p.name, p.image, p.selling_price 
              FROM carts c, products p 
              WHERE c.prod_id = p.id AND c.user_id = ? 
              ORDER BY c.id DESC";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    // $totalPrice = 0;
    $cartItems = [];

    while ($row = $result->fetch_assoc()) {
        // $totalPrice += $row['selling_price'];
        $cartItems[] = $row;
    }

    //total after shipping RM10
    $totalAfterShipping = $totalPrice + 10;

    // Generate tracking number
    $tracking_no = "ThirdAlley" . rand(1111, 9999) . substr($phone, 2);

    if ($chk == "on") {
        // Insert card details into the cards table

        // if (!isset($existingCard)) {
        $cardNumber = mysqli_escape_string($con, $_POST['card-num']);
        $expiryDate = mysqli_escape_string($con, $_POST['exp']);
        $cvv = mysqli_escape_string($con, $_POST['cvv']);
        $nameOnCard = mysqli_escape_string($con, $_POST['name']);

        $insert_card_query = "INSERT INTO cards (name, card_number, expiry_date, cvv, saved, created_at, user_id) 
                                 VALUES ('$nameOnCard', '$cardNumber', '$expiryDate', '$cvv', 1, NOW(), '$userId')";
        $insert_card_query_run = mysqli_query($con, $insert_card_query);
        // }

        if ($insert_card_query_run) {
            $card_id = mysqli_insert_id($con); // Get the last inserted card_id

            // Insert order details
            $insert_order_query = "INSERT INTO orders (tracking_no, user_id, name, email, phone, address, pincode, total_price, payment_mode, status, payment_id, card_id) 
                                VALUES ('$tracking_no', '$userId', '$name', '$email', '$phone', '$address', '$pincode', '$totalAfterShipping', '$payment_mode', 0, '$payment_id', '$card_id')";
            $insert_order_query_run = mysqli_query($con, $insert_order_query);

            if ($insert_order_query_run) {
                $order_id = mysqli_insert_id($con); // Get the last order id


                // Insert order items and update product visibility and trending
$insert_items_query = "INSERT INTO order_items (order_id, prod_id, price) VALUES (?, ?, ?)";
$stmt_items = $con->prepare($insert_items_query);

$update_product_query = "UPDATE products SET status = 1, trending = 0 WHERE id = ?";
$stmt_update_product = $con->prepare($update_product_query);

foreach ($cartItems as $item) {
    $stmt_items->bind_param("iii", $order_id, $item['prod_id'], $item['selling_price']);
    $stmt_items->execute();

    $stmt_update_product->bind_param("i", $item['prod_id']);
    $stmt_update_product->execute();
}


                // Clear user's cart
                $deleteCartQuery = "DELETE FROM carts WHERE user_id = ?";
                $stmt_delete_cart = $con->prepare($deleteCartQuery);
                $stmt_delete_cart->bind_param("i", $userId);
                $stmt_delete_cart->execute();

                $_SESSION['message'] = 'Order placed successfully';

                // Redirect based on payment method
                if ($payment_mode == 'COD') {
                    $_SESSION['message'] = 'Order placed successfully';
                    header('Location: ../myorders.php');
                    exit(0);
                } else {
                    $_SESSION['message'] = 'Order placed successfully';
                    header('Location: ../myorders.php');
                    exit(0);
                }
            } else {
                $_SESSION['message'] = 'Order placement failed';
                header('Location: ../checkout.php');
                exit(0);
            }
        }
    } else {
        if (isset($existingCard)) {
            $card_id = mysqli_escape_string($con, $_POST['selected_card']);
        }
        echo $card_id;
        $insert_order_query = "INSERT INTO orders (tracking_no, user_id, name, email, phone, address, pincode, total_price, payment_mode, status, payment_id, card_id) 
                                VALUES ('$tracking_no', '$userId', '$name', '$email', '$phone', '$address', '$pincode', '$totalAfterShipping', '$payment_mode', 0, '$payment_id', '$card_id')";
        $insert_order_query_run = mysqli_query($con, $insert_order_query);

        if ($insert_order_query_run) {
            // $order_id = $stmt->insert_id;
            $order_id = mysqli_insert_id($con); // Get the last order id

            // Insert order items and update product visibility and trending
$insert_items_query = "INSERT INTO order_items (order_id, prod_id, price) VALUES (?, ?, ?)";
$stmt_items = $con->prepare($insert_items_query);

$update_product_query = "UPDATE products SET status = 1, trending = 0 WHERE id = ?";
$stmt_update_product = $con->prepare($update_product_query);

foreach ($cartItems as $item) {
    $stmt_items->bind_param("iii", $order_id, $item['prod_id'], $item['selling_price']);
    $stmt_items->execute();

    $stmt_update_product->bind_param("i", $item['prod_id']);
    $stmt_update_product->execute();
}

            // Clear user's cart
            $deleteCartQuery = "DELETE FROM carts WHERE user_id = ?";
            $stmt_delete_cart = $con->prepare($deleteCartQuery);
            $stmt_delete_cart->bind_param("i", $userId);
            $stmt_delete_cart->execute();

            $_SESSION['message'] = 'Order placed successfully';

            // Redirect based on payment method
            if ($payment_mode == 'COD') {
                $_SESSION['message'] = 'Order placed successfully';
                header('Location: ../myorders.php');
                exit(0);
            } else {
                $_SESSION['message'] = 'Order placed successfully';
                header('Location: ../myorders.php');
                exit(0);
            }
        } else {
            $_SESSION['message'] = 'Order placement failed';
            header('Location: ../checkout.php');
            exit(0);
        }
    }
}
