<?php
include ("functions/userfunctions.php");
include ("includes/header.php");

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
} else {
    echo "<h4>Invalid request</h4>";
    die();
}

$orderDetails = getOrderDetails($order_id);
$orderDetails = mysqli_fetch_assoc($orderDetails);

$products = getProductsByOrderId($order_id);

$user_id = $_SESSION['auth_user']['user_id']; // Define $user_id here

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];
    $feedback = $_POST['feedback'];

    // Validate the rating and feedback
    if (!empty($rating) && is_numeric($rating) && $rating >= 1 && $rating <= 5 && !empty($feedback)) {
        // Check if the user has already submitted a review for this product
        $existingReviewQuery = "SELECT * FROM reviews WHERE order_id='$order_id' AND product_id='$product_id' AND user_id='$user_id'";
        $existingReviewResult = mysqli_query($con, $existingReviewQuery);

        if (mysqli_num_rows($existingReviewResult) > 0) {
            echo "<h4>You have already submitted a review for this product.</h4>";
        } else {
            // Insert the new review using prepared statements
            $query = "INSERT INTO reviews (order_id, user_id, product_id, rating, feedback) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "iiids", $order_id, $user_id, $product_id, $rating, $feedback);
            if (mysqli_stmt_execute($stmt)) {
                echo "<h4>Review submitted successfully</h4>";
            } else {
                echo "<h4>Failed to submit review</h4>";
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        echo "<h4>Please provide a valid rating (1-5) and feedback.</h4>";
    }
}
?>

<div class="container py-5">
    <div class="card">
        <div class="card-header">
            <h4>Order Details</h4>
        </div>
        <div class="card-body">
            <p><strong>Name:</strong> <?= $orderDetails['name']; ?></p>
            <p><strong>Email:</strong> <?= $orderDetails['email']; ?></p>
            <p><strong>Phone:</strong> <?= $orderDetails['phone']; ?></p>
            <p><strong>Tracking No:</strong> <?= $orderDetails['tracking_no']; ?></p>
            <p><strong>Address:</strong> <?= $orderDetails['address']; ?></p>
            <p><strong>Pincode:</strong> <?= $orderDetails['pincode']; ?></p>
        </div>
    </div>

    <?php
    if ($products && mysqli_num_rows($products) > 0) {
        while ($product = mysqli_fetch_assoc($products)) {
            ?>
            <div class="card my-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <img src="uploads/<?= $product['images']; ?>" class="img-fluid"
                                alt="<?= $product['product_name']; ?>">
                        </div>
                        <div class="col-md-4">
                            <h5><strong><?= $product['product_name']; ?></strong></h5>
                            <p><strong>Size:</strong> <?= $product['size']; ?></p>
                            <p><strong>Width:</strong> <?= $product['width']; ?></p>
                            <p><strong>Length:</strong> <?= $product['length']; ?></p><br>
                            <p><strong>Price:</strong> RM <?= $product['price']; ?></p>
                        </div>

                        <div class="col-md-6">
                            <?php
                            // Check if the user has already submitted a review for this product
                            $existingReviewQuery = "SELECT * FROM reviews WHERE order_id='$order_id' AND product_id='{$product['prod_id']}' AND user_id='$user_id'";
                            $existingReviewResult = mysqli_query($con, $existingReviewQuery);

                            if (mysqli_num_rows($existingReviewResult) > 0) {
                                $existingReview = mysqli_fetch_assoc($existingReviewResult);
                                ?>
                                <h5>Your Review</h5>
                                <p><strong>Rating:</strong> <span style="font-size: 24px; color: #ffd700;"><?= str_repeat('&#9733;', $existingReview['rating']); ?></span></p>
                                <p><strong>Feedback:</strong> <?= $existingReview['feedback']; ?></p>

                            <?php } else { ?>
                                <form method="POST">
                                    <input type="hidden" name="order_id" value="<?= $order_id; ?>">
                                    <input type="hidden" name="product_id" value="<?= $product['prod_id']; ?>">
                                    <div class="mb-3">
                                        <label for="rating" class="form-label">Rating</label>
                                        <div class="star-rating">
                                            <input type="radio" id="star5-<?= $product['prod_id']; ?>" name="rating" value="5" required><label for="star5-<?= $product['prod_id']; ?>">&#9733;</label>
                                            <input type="radio" id="star4-<?= $product['prod_id']; ?>" name="rating" value="4" required><label for="star4-<?= $product['prod_id'];?>">&#9733;</label>

                                            <input type="radio" id="star3-<?= $product['prod_id']; ?>" name="rating" value="3" required><label for="star3-<?= $product['prod_id']; ?>">&#9733;</label>
                                            <input type="radio" id="star2-<?= $product['prod_id']; ?>" name="rating" value="2" required><label for="star2-<?= $product['prod_id']; ?>">&#9733;</label>
                                            <input type="radio" id="star1-<?= $product['prod_id']; ?>" name="rating" value="1" required><label for="star1-<?= $product['prod_id']; ?>">&#9733;</label>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="feedback" class="form-label">Feedback</label>
                                        <textarea id="feedback" name="feedback" class="form-control" rows="4" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit Review</button>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<h4>No products found for this order.</h4>";
    }
    ?>
</div>

<?php include ("includes/footer.php"); ?>

