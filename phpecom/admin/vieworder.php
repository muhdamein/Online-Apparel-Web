<?php
include ("../middleware/adminMiddleware.php");
include ("includes/header.php");

if (isset($_GET['t'])) {
    $tracking_no = mysqli_real_escape_string($con, $_GET['t']);

    $orderData = checkTrackingNoValid($tracking_no);
    if (mysqli_num_rows($orderData) <= 0) {
        echo "<h4>Order not found. Invalid tracking number.</h4>";
        die();
    }

    $data = mysqli_fetch_array($orderData);
} else {
    echo "<h4>Missing tracking number.</h4>";
    die();
}

if (mysqli_error($con)) {
    echo "<h4>Database Error: " . mysqli_error($con) . "</h4>";
    die();
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header bg-primary">
                    <span class="fs-4 text-white">View Order</span>
                    <a href="orders.php" class="btn bg-white float-end"><i class="fa fa-reply"></i> Back</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Delivery Details</h4>
                            <hr>
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <label class="fw-bold">Name</label>
                                    <div class="border p-1">
                                        <?= $data['name'] ?>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label class="fw-bold">Email</label>
                                    <div class="border p-1">
                                        <?= $data['email'] ?>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label class="fw-bold">Phone</label>
                                    <div class="border p-1">
                                        <?= $data['phone'] ?>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label class="fw-bold">Tracking No.</label>
                                    <div class="border p-1">
                                        <?= $data['tracking_no'] ?>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label class="fw-bold">Address</label>
                                    <div class="border p-1">
                                        <?= $data['address'] ?>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label class="fw-bold">Pincode</label>
                                    <div class="border p-1">
                                        <?= $data['pincode'] ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4>Order Details</h4>
                            <hr>
                            <div class="mb-3">
                                <label class="fw-bold">Created At</label>
                                <div class="border p-1">
                                    <?= $data['created_at'] ?>
                                </div>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $order_query = "SELECT o.id as oid, o.tracking_no, o.user_id, oi.*, p.* FROM orders o, order_items oi, products p WHERE oi.order_id=o.id AND p.id=oi.prod_id AND o.tracking_no='$tracking_no'";
                                    $order_query_run = mysqli_query($con, $order_query);

                                    if (mysqli_num_rows($order_query_run) > 0) {
                                        foreach ($order_query_run as $item) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    $images = explode(",", $item['images']);
                                                    $first_image = reset($images); // Get the first image from the array
                                                    ?>
                                                    <img src="../uploads/<?= $first_image; ?>" width="50px" height="50px"
                                                        alt="<?= $item['name']; ?>">

                                                    <?= $item['name']; ?>
                                                </td>
                                                <td class="align-middle">
                                                    RM <?= $item['price'] ?>
                                                </td>

                                                </td>
                                            </tr>
                                            <?php

                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>

                            <hr>
                            <h5> Total Price : <span class="float-end fw-bold">RM <?= $data['total_price']; ?></span>
                            </h5>

                            <hr>

                            <label class="fw-bold">Payment Method</label>
                            <div class="border p-1 mb-3">
                                <?= $data['payment_mode'] ?>
                            </div>


                            <label class="fw-bold">Order Status</label>
                            <div class="mb-3">
                                <form action="code.php" method="POST">
                                    <input type="hidden" name="tracking_no" value="<?= $data['tracking_no'] ?>">
                                    <select name="order_status" class="form-select">
                                        <option value="0" <?= $data['status'] == 0 ? "selected" : "" ?>> Under Process
                                        </option>
                                        <option value="1" <?= $data['status'] == 1 ? "selected" : "" ?>> Completed</option>
                                        <option value="2" <?= $data['status'] == 2 ? "selected" : "" ?>> Cancelled</option>
                                    </select>
                                    <button type="submit" name="update_order_btn"
                                        class="btn btn-primary mt-3">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include ("includes/footer.php");
?>