<?php
include("functions/userfunctions.php");
include("includes/header.php");
?>

<div class="py-3"  style="background-color: #776B5D;">
    <div class="container">
        <h6 class = "text-white">
        <a class="text-white" href="index.php">
                Home /
            </a>
            <a class="text-white" href="categories.php">
                Collection
            </a>
        </h6>
    </div>
</div>

<div class="py-5">
    <div class="container">
        <div class="row ">
            <div class="col-md-12">
                <h1>Collections</h1>
                <hr>
                <div class="row">

                    <?php
                    $categories = getAllActive("categories");

                    if (mysqli_num_rows($categories) > 0) {
                        foreach ($categories as $item) {
                            ?>
                            <div class="col-md-4 mb-2">
                                <a href="products.php?category=<?= $item['slug']; ?>">
                                <div class="card shadow">
                                    <div class="card-body p-0" style="height: 400px;">
                                        <img src="uploads/<?= $item['images']; ?>" alt="Category Image" class="w-100" style="height: 370px; object-fit: cover;">
                                        <h4 class="text-center my-2" style="color: #594545;"><?= $item['name']; ?></h4>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <?php
                        }
                    } else {
                        echo "No Categories Available";
                    }
                    ?>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
