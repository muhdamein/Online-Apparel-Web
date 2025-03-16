<?php

include ("../middleware/adminMiddleware.php");
include ("includes/header.php");

?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php
            if (isset($_GET['id'])) {

                $id = $_GET['id'];
                $product = getByID('products', $id);

                if (mysqli_num_rows($product) > 0) {
                    $data = mysqli_fetch_array($product);

                    ?>
                    <div class="card">
                        <div class="card-header">
                            <h4>Edit Product</h4>
                            <a href="products.php" class="btn btn-primary float-end">Back</a>
                        </div>
                        <div class="card-body">
                            <form action="code.php" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="mb-0">Select Category</label>
                                        <select name="category_id" class="form-select mb-2">
                                            <option selected>Select Category</option>

                                            <?php
                                            $categories = getAll("categories");

                                            if (mysqli_num_rows($categories) > 0) {
                                                foreach ($categories as $item) {
                                                    ?>
                                                    <option value="<?= $item['id'] ?>" <?= $data['category_id'] == $item['id'] ? ' selected' : '' ?>>
                                                        <?= $item['name']; ?>
                                                    </option>

                                                    <?php
                                                }
                                            } else {
                                                echo "No category available";
                                            }

                                            ?>

                                        </select>

                                    </div>
                                    <input type="hidden" name="product_id" value="<?= $data['id']; ?>">
                                    <div class="col-md-6">
                                        <label class="mb-0">Name</label>
                                        <input type="text" required name="name" value="<?= $data['name']; ?>"
                                            placeholder="Enter Category Name" class="form-control mb-2 ">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="mb-0">Slug</label>
                                        <input type="text" required name="slug" value="<?= $data['slug']; ?>"
                                            placeholder="Enter Slug" class="form-control mb-2 ">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="mb-0">Small Description</label>
                                            <textarea row="3" required name="small_description"
                                                placeholder="Enter small description"
                                                class="form-control mb-2 "><?= $data['small_description']; ?></textarea>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="mb-0">Description</label>
                                            <textarea row="3" required name="description" placeholder="Enter description"
                                                class="form-control mb-2 "><?= $data['description']; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="mb-0">Original Price</label>
                                            <input type="text" required name="original_price"
                                                value="<?= $data['original_price']; ?>" placeholder="Enter Original Price"
                                                class="form-control mb-2 ">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="mb-0">Selling Price</label>
                                            <input type="text" required name="selling_price"
                                                value="<?= $data['selling_price']; ?>" placeholder="Enter Selling Price"
                                                class="form-control mb-2 ">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="mb-0">Upload New Images</label>
                                        <input type="file" name="images[]" class="form-control mb-2" multiple>
                                        <label class="mb-0">Current Images</label><br>
                                        <?php
                                        $images = explode(",", $data['images']);
                                        foreach ($images as $image) {
                                            ?>
                                            <img src="../uploads/<?= $image; ?>" alt="Product Image" height="50px" width="50px">
                                            <?php
                                        }
                                        ?>
                                        <input type="hidden" name="existing_images" value="<?= $data['images']; ?>">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="mb-0">Size</label>
                                            <input type="text" name="size" value="<?= $data['size']; ?>"
                                                class="form-control mb-2">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="mb-0">Width</label>
                                            <input type="text" name="width" value="<?= $data['width']; ?>"
                                                class="form-control mb-2">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="mb-0">Length</label>
                                            <input type="text" name="length" value="<?= $data['length']; ?>"
                                                class="form-control mb-2">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="mb-0">Visibility</label> <br>
                                        <input type="checkbox" name="status" <?= $data['status'] == '0' ? '' : 'checked' ?>>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="mb-0">Trending</label> <br>
                                        <input type="checkbox" name="trending" <?= $data['trending'] == '0' ? '' : 'checked' ?>>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary" name="update_product_btn">Update</button>
                                </div>

                        </div>
                    </div>
                    </form>
                    <?php
                } else {
                    echo "Product Not Found for given id";
                }

            } else {
                echo "Id missing from url";
            }
            ?>
        </div>
    </div>
</div>
</div>
</div>
<?php include ("includes/footer.php"); ?>