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
            <a class="text-white" href="wishlist.php">
                Wishlist
            </a>
        </h6>
    </div>
</div>

<div class="py-5">
    <div class="container">
        <div class="card-body shadow">
            <div class="row big-white" style="background-color: white !important;">
                <div class="col-md-12">
                    <div id="mywish">
                        <?php
                        $items = getWishItems();

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
                                    foreach ($items as $witem) {
                                        $images = explode(",", $witem['images']);
                                        $image = $images[0]; // Display the first image
                                        ?>
                                        <tr>
                                            <td>
                                                <a href="viewproduct.php?product=<?= $witem['slug'] ?>">
                                                    <img src="uploads/<?= $image ?>" alt="Image" width="80px">
                                                </a>
                                            </td>
                                            <td><?= $witem['name'] ?></td>
                                            <td><?= $witem['size'] ?></td>
                                            <td><?= $witem['width'] ?></td>
                                            <td><?= $witem['length'] ?></td>
                                            <td>RM <?= $witem['selling_price'] ?></td>
                                            <td>
                                                <button class="btn btn-danger btn-sm deleteItemWish" value="<?= $witem['wid'] ?>">
                                                    <i class="fa fa-trash me-2"></i>Remove
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?php
                        } else {
                            ?>
                            <div class="card card-body shadow text-center">
                                <h4 class="py-3">Empty Wishlist</h4>
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
