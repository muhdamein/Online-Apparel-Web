<?php
include ("functions/userfunctions.php");
include ("includes/header.php");

if (isset($_GET['product'])) {
    $product_slug = $_GET['product'];
    $product_data = getSlugActive("products", $product_slug);
    $product = mysqli_fetch_array($product_data);

    if ($product) {

        $category_data = getIDActive("categories", $product['category_id']);
        $category = mysqli_fetch_array($category_data);

        ?>
        <div class="py-3" style="background-color: #776B5D;">
            <div class="container">
                <h6 class="text-white">
                    <a class="text-white" href="index.php">
                        Home /
                    </a>
                    <a class="text-white" href="categories.php">
                        Collection /
                    </a>
                    <a class="text-white" href="products.php?category=<?= $category['slug']; ?>">
                        <?= $category['name']; ?>
                    </a> /
                    <?= $product['name']; ?>
                </h6>
            </div>
        </div>

        <div class="bg-light py-4">

            <div class="container mt-3">
                <div class="row">
                    <div class="col-md-4 viewproduct-carousel-container">
                        <div class="shadow" style="height: 600px;">
                            <div id="productImageSlider" class="carousel slide viewproduct-carousel" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <?php
                                    $images = explode(",", $product['images']);
                                    foreach ($images as $index => $image) {
                                        ?>
                                        <div class="carousel-item <?= $index == 0 ? 'active' : ''; ?>">
                                            <img src="uploads/<?= $image; ?>" class="d-block w-100" style="height: 600px;" alt="Product Image">
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#productImageSlider"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#productImageSlider"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-8">
                        <h4 class="fw-bold" style="color: #594545;">
                            <?= $product['name']; ?>

                            <span class="float-end text-danger"><?php if ($product['trending']) {
                                echo "Trending";
                            } ?></span>
                        </h4>

                        <hr>
                        <div class="row mt-3 mb-4">
                            <div class="col-md-2">
                                <h2> RM <span class="text-success fw-bold"><?= $product['selling_price']; ?></span></h2>
                            </div>
                            <div class="col-md-3">
                                <h5> RM <s class="text-danger"><?= $product['original_price']; ?></s></h5>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <table class="table table-bordered viewproduct-table">
                                    <tbody>
                                        <tr>
                                            <td>Size:</td>
                                            <td><?= $product['size']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Width:</td>
                                            <td><?= $product['width']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Length:</td>
                                            <td><?= $product['length']; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>



                        <div class="row mt-4">
                            <div class="col-md-6">
                                <button class="btn px-4 addToCartBtn" value="<?= $product['id']; ?>"><i
                                        class="fa fa-shopping-cart me-2"></i>ADD TO CART</button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-danger addToWishBtn" value="<?= $product['id']; ?>"><i
                                        class="fa fa-heart"></i></button>
                            </div>
                        </div>


                        <hr>

                        <h6>Details:</h6>
                        <p>
                            <?= $product['description']; ?>
                        </p>
                        <h6 class="text-muted">Remarks:</h6>
                        <p class="text-muted">
                            <?= $product['small_description']; ?>
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <?php
    } else {
        echo "Product Not Found";
    }

} else {
    echo "Something Went Wrong";
}

include ("includes/footer.php");
?>