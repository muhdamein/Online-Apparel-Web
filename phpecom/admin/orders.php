<?php
include("../middleware/adminMiddleware.php");
include("includes/header.php");

// Handle sorting parameter
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'desc'; // Default to descending order
$orders = getAllOrders($sort);

?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header bg-primary">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="text-white">Orders</h4>
                        </div>
                        <div class="col-auto">
                            <div class="dropdown me-3">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                   Date
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                    <li><a class="dropdown-item" href="?sort=asc"><i class="fas fa-sort-amount-up"></i> Oldest first</a></li>
                                    <li><a class="dropdown-item" href="?sort=desc"><i class="fas fa-sort-amount-down"></i> Newest first</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="order-history.php" class="btn bg-white">Order History</a>
                        </div>
                    </div>
                </div>

                <div class="card-body" id="">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Tracking No</th>
                                <th>Price</th>
                                <th>Date</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($orders) > 0) {
                                foreach ($orders as $item) {
                                    ?>
                                    <tr>
                                        <td><?= $item['id']; ?></td>
                                        <td><?= $item['name']; ?></td>
                                        <td><?= $item['tracking_no']; ?></td>
                                        <td><?= $item['total_price']; ?></td>
                                        <td><?= $item['created_at']; ?></td>
                                        <td>
                                            <a href="vieworder.php?t=<?= $item['tracking_no']; ?>" class="btn btn-primary">View details</a>
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

<?php
include ("includes/footer.php");
?>
