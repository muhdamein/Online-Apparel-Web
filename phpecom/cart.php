<?php
include ("functions/userfunctions.php");
include ("includes/header.php");
?>

<div class="py-3" style="background-color: #776B5D;">
    <div class="container">
        <h6 class="text-white">
            <a class="text-white" href="index.php">
                Home /
            </a>
            <a class="text-white" href="cart.php">
                Cart
            </a>
        </h6>
    </div>
</div>

<div class="py-5">
    <div class="container">
        <div class="card-body shadow">
            <div class="row big-white" style="background-color: white !important;">
                <div class="col-md-12">
                    <div id="mycart">
                        <?php 
                        $items = getCartItems();

                        if (mysqli_num_rows($items) > 0) {
                            ?>
                            <style>
                                .bold-text {
                                    font-weight: bold;
                                }
                            </style>

                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Name</th>
                                        <th>Size</th>
                                        <th>Width</th>
                                        <th>Length</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($items as $citem) {
                                        $images = explode(",", $citem['images']);
                                        $image = $images[0]; // Display the first image
                                        ?>
                                        <tr>
                                            <td>
                                                <a href="viewproduct.php?product=<?= $citem['slug'] ?>">
                                                    <img src="uploads/<?= $image ?>" alt="Image" width="80px">
                                                </a>
                                            </td>
                                            <td><?= $citem['name'] ?></td>
                                            <td><?= $citem['size'] ?></td>
                                            <td><?= $citem['width'] ?></td>
                                            <td><?= $citem['length'] ?></td>
                                            <td>RM <?= $citem['selling_price'] ?></td>
                                            <td>
                                                <button class="btn btn-danger btn-sm deleteItem" value="<?= $citem['cid'] ?>">
                                                    <i class="fa fa-trash me-2"></i>Remove
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <div class="float-end">
                                <a href="checkout.php" class="btn btn-outline-primary">Checkout</a>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="card card-body shadow text-center">
                                <h4 class="py-3">Empty Cart</h4>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include ("includes/footer.php"); ?>
