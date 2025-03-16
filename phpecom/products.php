<style>
    .card {
        margin-bottom: 20px; /* Add margin below each card */
    }

    .card-body {
        padding: 10px; /* Add padding inside the card body */
    }

    .card-img-top {
        width: 100%;
        height: 300px; /* Set the height of the image within the card */
        object-fit: cover; /* Ensure the image covers the entire space */
    }

    h4 {
        margin-top: 10px; /* Add margin above the heading */
        margin-bottom: 0; /* Remove default margin below the heading */
    }
</style>

<?php
include ("functions/userfunctions.php");
include ("includes/header.php");

if (isset($_GET['category'])) {
    $category_slug = $_GET['category'];
    $category_data = getSlugActive("categories", $category_slug);

    if ($category_data) {
        $category = mysqli_fetch_array($category_data);

        if ($category) {
            $catID = $category['id'];
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
                        <?= $category['name']; ?>
                    </h6>
                </div>
            </div>

            <div class="py-3">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h2><?= $category['name']; ?></h2>
                            <hr>
                            <div class="row">

                                <?php
                                $products = getProdByCategory($catID);

                                if (mysqli_num_rows($products) > 0) {
                                    foreach ($products as $item) {
                                        $images = explode(",", $item['images']);
                                        $first_image = reset($images); // Get the first image from the array
                                        ?>
                                        <div class="col-md-3 mb-2">
                                            <a href="viewproduct.php?product=<?= $item['slug']; ?>">
                                                <div class="card shadow">
                                                    <img src="uploads/<?= $first_image; ?>" alt="Product Image" class="card-img-top">
                                                    <div class="card-body">
                                                        <h6 class="text-center" style="color: #594545; font-weight: bold;"><?= $item['name']; ?></h6>
                                                        <p class="text-center mt-1 mb-0"style="color: #594545;">RM <?= $item['selling_price']; ?></p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <?php
                                    }
                                } else {
                                    echo "Ooops!! No Products Available";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
        } else {
            echo "Category not found";
        }
    } else {
        echo "Error fetching category data: " . mysqli_error($con);
    }
} else {
    echo "Something Went Wrong";
}

include ("includes/footer.php");
?>
