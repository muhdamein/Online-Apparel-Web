<?php
include ("functions/userfunctions.php");
include ("includes/header.php");

if (isset($_GET['t'])) {

    $tracking_no = $_GET['t'];

    $orderData = checkTrackingNoValid($tracking_no);
    if (mysqli_num_rows($orderData) < 0) {
        ?>

        <h4>Something went wrong</h4>

        <?php
        die();
    }

} else {
    ?>

    <h4>Something went wrong</h4>

    <?php
    die();
}

$data = mysqli_fetch_array($orderData);

?>

<div class="py-2" style="background-color: #776B5D;">
    <div class="container">
        <h6 class="text-white">
            <a class="text-white" href="index.php">
                Home /
            </a>
            <a class="text-white" href="myorders.php">
                My Orders /
            </a>
            <a class="text-white" href="#">
                View Order
            </a>
        </h6>
    </div>
</div>

<div class="py-5">
    <div class="container">
                        <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <span class = "fs-4">View Order</span>

                            <a href="myorders.php" class="btn btn-primary float-end"><i class="fa fa-reply"></i> Back</a>
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
                                <label class="fw-bold">Latest Update</label>
                                <div class="border p-1">
                                    <?= $data['update_time'] ?>
                                </div>
                            </div>
                                        
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php

                                            $userId = $_SESSION['auth_user']['user_id'];

                                            $order_query = "SELECT o.id as oid, o.tracking_no, o.user_id, oi .*, p.* FROM orders o, order_items oi,
                                            products p WHERE o.user_id='$userId' AND oi.order_id=o.id AND p.id=oi.prod_id
                                            AND o.tracking_no='$tracking_no' ";
                                            $order_query_run = mysqli_query($con, $order_query);

                                            if (mysqli_num_rows($order_query_run) > 0) {


                                                foreach ($order_query_run as $item) {
                                                    $images = explode(',', $item['images']); // Split the images string into an array
                                                    $first_image = $images[0]; // Get the first image
                                                
                                                    ?>
                                                    <tr>
                                                        <td class="align-middle">
                                                            <img src="uploads/<?= $first_image; ?>" width="50px" height="50px" alt="<?= $item['name']; ?>">
                                                            <?= $item['name']; ?>
                                                        </td>
                                                        <td class="align-middle">
                                                            RM <?= $item['price']; ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                
                                            }
                                            ?>
                                        </tbody>
                                    </table>

                                    <hr>
                                    <?php if ($data['payment_mode'] == 'Credit Card') { ?>
                                    <h7>Shipping fee &#128666; : <span class="float-end">RM 10</span></h7>
                                    <div style="margin-bottom: 20px;"></div>
                                    <?php } ?>
                                    <h5> Total Price : <span class="float-end fw-bold">RM <?= $data['total_price']; ?></span></h5>

                                    <hr>
                                    
                                        <label class = "fw-bold">Payment Method</label>
                                        <div class="border p-1 mb-3">
                                        <?= $data['payment_mode']?>
                                    </div>

                                    
                                        <label class = "fw-bold">Order Status</label>
                                        <div class="border p-1 mb-3">
                                        <?php 
                                        
                                        if ($data['status'] ==0)
                                        {
                                            echo "Under Process";

                                        }else if ($data['status'] ==1)
                                        {
                                            echo "Completed";

                                        }else if ($data['status'] ==2)
                                        {
                                            echo "Cancelled";
                                        }
                                        
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
