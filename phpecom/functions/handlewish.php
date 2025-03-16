<?php

session_start();
include('../config/dbcon.php');

if (isset($_SESSION['auth'])) {
    if (isset($_POST['scope'])) {
        $scope = $_POST['scope'];
        switch ($scope) {
            case "add":
                $prod_id = $_POST['prod_id'];
                $user_id = $_SESSION['auth_user']['user_id'];

                $check_existing_wish = "SELECT * FROM wishlist WHERE prod_id= '$prod_id' AND user_id= '$user_id'";
                $check_existing_wish_run = mysqli_query($con, $check_existing_wish);

                if (mysqli_num_rows($check_existing_wish_run) > 0) {
                    echo "existing";
                } else {
                    $insert_query = "INSERT INTO wishlist (user_id, prod_id) VALUES ('$user_id', '$prod_id')";
                    $insert_query_run = mysqli_query($con, $insert_query);

                    if ($insert_query_run) {
                        echo 201;
                    } else {
                        echo 500;
                    }
                }
                break;

            case "delete":
                $wish_id = $_POST['wish_id'];
                $user_id = $_SESSION['auth_user']['user_id'];

                $check_existing_wish = "SELECT * FROM wishlist WHERE id= '$wish_id' AND user_id= '$user_id'";
                $check_existing_wish_run = mysqli_query($con, $check_existing_wish);

                if (mysqli_num_rows($check_existing_wish_run) > 0) {
                    $delete_query = "DELETE FROM wishlist WHERE id='$wish_id'";
                    $delete_query_run = mysqli_query($con, $delete_query);

                    if ($delete_query_run) {
                        echo 201;
                    } else {
                        echo "something went wrong";
                    }
                } else {
                    echo "something went wrong";
                }
                break;

            default:
                echo 500;
        }
    }
} else {
    echo 401;
}
?>
