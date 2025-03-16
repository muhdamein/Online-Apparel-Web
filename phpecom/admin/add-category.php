<?php

include ("../middleware/adminMiddleware.php");
include ("includes/header.php");

?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Add Category</h4>
                </div>
                <div class="card-body">
                    <form action="code.php" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Name</label>
                                <input type="text" required name="name" placeholder="Enter Category Name"
                                    class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="">Slug</label>
                                <input type="text" required name="slug" placeholder="Enter Slug" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <label for="">Description</label>
                                <textarea row="3" required name="description" placeholder="Enter description"
                                    class="form-control"></textarea>
                            </div>
                            <div class="col-md-12">
    <label for="">Upload Image</label>
    <input type="file" name="image" class="form-control">
</div>

                            <div class="col-md-6">
                                <label for="">Visibility</label>
                                <input type="checkbox" name="status">
                                <label for="">Popular</label>
                                <input type="checkbox" name="popular">
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" name="add_category_btn">Save</button>
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