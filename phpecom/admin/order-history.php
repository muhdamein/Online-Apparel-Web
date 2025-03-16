<?php
include("../middleware/adminMiddleware.php");
include("includes/header.php");

// Handle sorting parameters
$sort = isset($_GET['sort']) ? ($_GET['sort'] == 'asc' ? 'asc' : 'desc') : 'desc'; // Default sort by update_time descending
$status = isset($_GET['status']) ? ($_GET['status'] == 'all' ? null : (int)$_GET['status']) : null; // Default show all statuses

$orders = getOrderHistory($sort, $status);

?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                    <h4 class="text-white mb-0">Orders</h4>
                    <div class="d-flex align-items-center">
                        <div class="dropdown me-3">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Date
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                <li><a class="dropdown-item" href="?sort=asc&status=<?= $status ?>"><i class="fas fa-sort-amount-up"></i> Oldest first</a></li>
                                <li><a class="dropdown-item" href="?sort=desc&status=<?= $status ?>"><i class="fas fa-sort-amount-down"></i> Newest first</a></li>
                            </ul>
                        </div>

                        <div class="dropdown me-3">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="statusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Status
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="statusDropdown">
                                <li><a class="dropdown-item" href="?sort=<?= $sort ?>&status=all"><i class="fas fa-filter"></i> All</a></li>
                                <li><a class="dropdown-item" href="?sort=<?= $sort ?>&status=1"><i class="fas fa-check-circle text-success"></i> Completed</a></li>
                                <li><a class="dropdown-item" href="?sort=<?= $sort ?>&status=2"><i class="fas fa-times-circle text-danger"></i> Cancelled</a></li>
                            </ul>
                        </div>
                        
                        <a href="orders.php" class="btn bg-white"><i class="fa fa-reply"></i> Back</a>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Tracking No</th>
                                <th>Price</th>
                                <th>Time Update</th>
                                <th>Status</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($orders && mysqli_num_rows($orders) > 0) {
                                while ($item = mysqli_fetch_assoc($orders)) {
                                    ?>
                                    <tr>
                                        <td><?= $item['id']; ?></td>
                                        <td><?= $item['name']; ?></td>
                                        <td><?= $item['tracking_no']; ?></td>
                                        <td><?= $item['total_price']; ?></td>
                                        <td><?= $item['update_time']; ?></td>
                                        <td>
                                            <?php
                                            if ($item['status'] == 1) {
                                                echo '<span class="text-success">Completed</span>';
                                            } elseif ($item['status'] == 2) {
                                                echo '<span class="text-danger">Cancelled</span>';
                                            } else {
                                                echo $item['status'];
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="vieworder.php?t=<?= $item['tracking_no']; ?>" class="btn btn-primary">View details</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="7">No orders found</td>
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
