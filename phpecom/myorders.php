<?php
include("functions/userfunctions.php");
include("includes/header.php");
?>

<div class="py-3" style="background-color: #776B5D;">
    <div class="container">
        <h6 class="text-white">
            <a class="text-white" href="index.php">
                Home /
            </a>
            <a class="text-white" href="myorders.php">
                My Orders
            </a>
        </h6>
    </div>
</div>

<div class="py-5">
    <div class="container">
        <div class="card-body shadow">
            <div class="row big-white" style="background-color: white !important;">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tracking No</th>
                                <th>Price</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $orders = getOrders();
                            if ($orders !== null && mysqli_num_rows($orders) > 0) {
                                while ($item = mysqli_fetch_assoc($orders)) {
                                    $statusIcon = '';
                                    $reviewButton = '';
                                    if ($item['status'] == 0) {
                                        $statusIcon = '<i class="fa fa-hourglass-half text-warning" title="Under Process"></i>';
                                    } else if ($item['status'] == 1) {
                                        $statusIcon = '<i class="fa fa-check text-success" title="Completed"></i>';
                                        $reviewButton = '<a href="review.php?order_id=' . $item['id'] . '" class="btn btn-outline-primary">Review</a>';
                                    } else if ($item['status'] == 2) {
                                        $statusIcon = '<i class="fa fa-times text-danger" title="Cancelled"></i>';
                                    }
                                    ?>
                                    <tr>
                                        <td><?= $item['id']; ?></td>
                                        <td><?= $item['tracking_no']; ?></td>
                                        <td><?= $item['total_price']; ?></td>
                                        <td><?= $item['created_at']; ?></td>
                                        <td><?= $statusIcon; ?></td>
                                        <td>
                                            <a href="vieworder.php?t=<?= $item['tracking_no']; ?>" class="btn btn-outline-primary">View details</a>
                                            <?= $reviewButton; ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6">No orders yet</td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
