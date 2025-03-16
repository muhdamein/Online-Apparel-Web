<?php

session_start();
include('config/dbcon.php'); 

function getAllActive($table)
{
    global $con;
    $query = "SELECT * FROM $table WHERE status = '0'";
    return $query_run = mysqli_query($con, $query);
}

function getAllTrending()
{
    global $con;
    $query = "SELECT * FROM products WHERE trending = '1'";
    return $query_run = mysqli_query($con, $query);
}

function getReviews() {
    global $con;
    $query = "SELECT r.rating, r.feedback, p.product_name, p.images
              FROM reviews r
              JOIN products p ON r.product_id = p.prod_id";
    return mysqli_query($con, $query);
}


function getSlugActive($table, $slug)
{
    global $con;
    $query = "SELECT * FROM $table WHERE slug='$slug' AND status = '0' LIMIT 1 ";
    return $query_run = mysqli_query($con, $query);

}

function getProdByCategory($category_id)
{
    global $con;
    $query = "SELECT * FROM products WHERE category_id='$category_id' AND status = '0'";
    return $query_run = mysqli_query($con, $query);
}

function getIDActive($table, $id)
{
    global $con;
    $query = "SELECT * FROM $table WHERE id='$id' AND status = '0' ";
    return $query_run = mysqli_query($con, $query);

}

function getCartItems()
{
    global $con;
    if (isset($_SESSION['auth_user']) && $_SESSION['auth_user'] !== null) {
        $userId = $_SESSION['auth_user']['user_id'];
        $query = "SELECT c.id as cid, c.prod_id, p.id as pid, p.name, p.images, p.selling_price, p.size, p.width, p.length, p.slug
                FROM carts c
                JOIN products p ON c.prod_id = p.id
                WHERE c.user_id='$userId' 
                ORDER BY c.id DESC";
        $query_run = mysqli_query($con, $query);
        if ($query_run) {
            return $query_run;
        } else {
            // Handle the case where the query fails
            return mysqli_query($con, "SELECT * FROM products WHERE 1=0");
        }
    } else {
        // Return an empty result set if auth_user is not set
        return mysqli_query($con, "SELECT * FROM products WHERE 1=0");
    }
}


function getWishItems()
{
    global $con;
    if (isset($_SESSION['auth_user']) && $_SESSION['auth_user'] !== null) {
        $userId = $_SESSION['auth_user']['user_id'];
        $query = "SELECT w.id as wid, w.prod_id, p.id as pid, p.name, p.images, p.selling_price, p.size, p.width, p.length, p.slug  
                FROM wishlist w, products p WHERE w.prod_id=p.id AND w.user_id='$userId' ORDER BY w.id DESC";
        $query_run = mysqli_query($con, $query);
        if ($query_run) {
            return $query_run;
        } else {
            // Handle the case where the query fails
            return mysqli_query($con, "SELECT * FROM products WHERE 1=0");
        }
    } else {
        // Return an empty result set if auth_user is not set
        return mysqli_query($con, "SELECT * FROM products WHERE 1=0");
    }
}

function getOrders()
{
    global $con;

    if (!isset($_SESSION['auth_user']) || !isset($_SESSION['auth_user']['user_id'])) {
        return null; // Return null if user session is not found
    }

    $userId = $_SESSION['auth_user']['user_id'];

    $query = "SELECT id, tracking_no, total_price, created_at, status FROM orders WHERE user_id='$userId' ORDER BY id DESC";
    return mysqli_query($con, $query);
}

function getOrderDetails($orderId)
{
    global $con;

    $query = "SELECT o.*, u.name, u.email, u.phone 
              FROM orders o 
              JOIN users u ON o.user_id = u.id 
              WHERE o.id='$orderId'";
    return mysqli_query($con, $query);
}

function getProductsByOrderId($orderId)
{
    global $con;

    $query = "SELECT oi.*, p.name AS product_name, p.original_price AS price, p.images, p.size, p.width, p.length
          FROM order_items oi 
          JOIN products p ON oi.prod_id = p.id 
          WHERE oi.order_id='$orderId'";

    return mysqli_query($con, $query);
}


function getTotalStarsForProduct($productId)
{
    global $con;

    $query = "SELECT SUM(rating) AS totalStars FROM reviews WHERE product_id='$productId'";
    $result = mysqli_query($con, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $totalStars = intval($row['totalStars']);
        $starIcons = str_repeat('&#9733;', $totalStars); // Repeat star icon based on the total rating
        return $starIcons;
    } else {
        return '&#9733;'; // Default to one star if no rating found
    }
}


function redirect($url, $message)
{
    $_SESSION['message'] = $message;
        header('Location:' .$url);
        exit(0);
}

function checkTrackingNoValid($trackingNo)
{
    global $con;
    $userId = $_SESSION['auth_user']['user_id'];

    $query = "SELECT * FROM orders WHERE tracking_no='$trackingNo' AND user_id='$userId' ";
    return mysqli_query($con, $query);
}

function updateUserProfile($user_id, $name, $email, $phone, $is_default, $address, $pincode, $new_password = null) {
    global $con;
    
    $query = "UPDATE users SET name = '$name', email = '$email', phone = '$phone', address = '$address', pincode = '$pincode'";
    
    if (!is_null($new_password)) {
        // For now, store the password as plain text (no hash)
        $query .= ", password = '$new_password'";
    }
    
    if ($is_default) {
        $query .= ", is_default = 1";
    } else {
        $query .= ", is_default = 0";
    }
    
    $query .= " WHERE id = '$user_id'";
    
    $result = mysqli_query($con, $query);
    
    // Retrieve the updated password
    $query = "SELECT password FROM users WHERE id = '$user_id'";
    $result = mysqli_query($con, $query);
    $user = mysqli_fetch_assoc($result);

    return $user['password'];
}

function getUserInfo($user_id) {
    global $con;
    $query = "SELECT * FROM users WHERE id = '$user_id'";
    $result = mysqli_query($con, $query);
    $user_info = mysqli_fetch_assoc($result);
    return $user_info;
}


if (isset($_POST['updateProfileBtn'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $is_default = isset($_POST['is_default']) ? 1 : 0; // Update the is_default value based on checkbox state
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $pincode = mysqli_real_escape_string($con, $_POST['pincode']);
    $user_id = $_SESSION['auth_user']['user_id']; // Get the user ID from the session

    updateUserProfile($user_id, $name, $email, $phone, $is_default, $address, $pincode);

    // Always store address and pincode in session
    $_SESSION['user_address'] = $address;
    $_SESSION['user_pincode'] = $pincode;

    $_SESSION['message'] = 'Profile updated successfully';

    // Update the checkbox state
    $is_default_checked = $is_default == 1;
}

?>
