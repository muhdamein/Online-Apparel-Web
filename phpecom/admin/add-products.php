<?php

include ("../middleware/adminMiddleware.php");
include ("includes/header.php");

?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Add Product</h4>
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
                                            <option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                                            <?php
                                        }
                                    } else {
                                        echo "No category available";
                                    }

                                    ?>

                                </select>

                            </div>
                            <div class="col-md-6">
                                <label class="mb-0">Name</label>
                                <input type="text" required name="name" placeholder="Enter Category Name"
                                    class="form-control mb-2 ">
                            </div>
                            <div class="col-md-6">
                                <label class="mb-0">Slug</label>
                                <input type="text" required name="slug" placeholder="Enter Slug"
                                    class="form-control mb-2 ">
                            </div>
                            <div class="col-md-12">
                                <label class="mb-0">Small Description</label>
                                <textarea row="3" required name="small_description"
                                    placeholder="Enter small description" class="form-control mb-2 "></textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="mb-0">Description</label>
                                <textarea row="3" required name="description" placeholder="Enter description"
                                    class="form-control mb-2 "></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="mb-0">Original Price</label>
                                <input type="text" required name="original_price" placeholder="Enter Original Price"
                                    class="form-control mb-2 ">
                            </div>
                            <div class="col-md-6">
                                <label class="mb-0">Selling Price</label>
                                <input type="text" required name="selling_price" placeholder="Enter Selling Price"
                                    class="form-control mb-2 ">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="mb-0">Upload Images</label>
                            <input type="file" required name="images[]" multiple class="form-control mb-2 ">
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="mb-0">Size</label>
                                <input type="text" name="size" value="-" class="form-control mb-2">
                            </div>
                            <div class="col-md-4">
                                <label class="mb-0">Width</label>
                                <input type="text" name="width" value="-" class="form-control mb-2">
                            </div>
                            <div class="col-md-4">
                                <label class="mb-0">Length</label>
                                <input type="text" name="length" value="-" class="form-control mb-2">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="mb-0">Visibility</label> <br>
                                <input type="checkbox" name="status">
                            </div>
                            <div class="col-md-3">
                                <label class="mb-0">Trending</label> <br>
                                <input type="checkbox" name="trending">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary" name="add_product_btn">Save</button>
                        </div>

                </div>
            </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
<?php include ("includes/footer.php"); ?>