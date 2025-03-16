<?php
include ("../middleware/adminMiddleware.php");
include ("includes/header.php");
include ("../config/dbcon.php");

// Handle image upload
if (isset($_POST['add_slider_btn'])) {
    $images = $_FILES['images']['name'];
    foreach ($images as $key => $image) {
        $target = "../uploads/" . basename($image);
        $f_name = pathinfo($image, PATHINFO_FILENAME);

        // Get the highest order value and increment it for the new image
        $order_result = mysqli_query($con, "SELECT MAX(`order`) as max_order FROM slider");
        $max_order = mysqli_fetch_assoc($order_result)['max_order'];
        $new_order = $max_order + 1;

        // Save image to database
        $query = "INSERT INTO slider (image, f_name, `order`) VALUES ('$image', '$f_name', '$new_order')";
        if (mysqli_query($con, $query)) {
            move_uploaded_file($_FILES['images']['tmp_name'][$key], $target);
            $_SESSION['message'] = "Slider image added successfully";
        } else {
            $_SESSION['message'] = "Failed to add slider image";
        }
    }
}

// Handle image deletion
if (isset($_POST['delete_slider_btn'])) {
    $slider_id = $_POST['slider_id'];
    $query = "SELECT image FROM slider WHERE id='$slider_id'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $image = $row['image'];

        // Delete image from database
        $query = "DELETE FROM slider WHERE id='$slider_id'";
        if (mysqli_query($con, $query)) {
            $filePath = "../uploads/" . $image;
            if (is_file($filePath)) {
                unlink($filePath);
                $_SESSION['message'] = "Slider image deleted successfully";
            } else {
                $_SESSION['message'] = "Failed to delete slider image file";
            }
        } else {
            $_SESSION['message'] = "Failed to delete slider image from database";
        }
    } else {
        $_SESSION['message'] = "Slider image not found";
    }
}

// Handle image reordering
if (isset($_POST['move_up']) || isset($_POST['move_down'])) {
    $slider_id = $_POST['slider_id'];
    $direction = isset($_POST['move_up']) ? 'up' : 'down';

    // Get current order of the image
    $query = "SELECT `order` FROM slider WHERE id='$slider_id'";
    $result = mysqli_query($con, $query);
    $current_order = mysqli_fetch_assoc($result)['order'];

    if ($direction == 'up') {
        // Find the image that is currently ordered just above this one
        $query = "SELECT id, `order` FROM slider WHERE `order` < '$current_order' ORDER BY `order` DESC LIMIT 1";
    } else {
        // Find the image that is currently ordered just below this one
        $query = "SELECT id, `order` FROM slider WHERE `order` > '$current_order' ORDER BY `order` ASC LIMIT 1";
    }

    $result = mysqli_query($con, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $swap_id = $row['id'];
        $swap_order = $row['order'];

        // Swap the order values
        mysqli_query($con, "UPDATE slider SET `order`='$swap_order' WHERE id='$slider_id'");
        mysqli_query($con, "UPDATE slider SET `order`='$current_order' WHERE id='$swap_id'");
    }
}

$sliders = mysqli_query($con, "SELECT * FROM slider ORDER BY `order` ASC");
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-info">
                    <?php echo $_SESSION['message'];
                    unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>
            <div class="card">
                <div class="card-header">
                    <h4>Manage Slider</h4>
                </div>
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Upload Images</label>
                                <input type="file" name="images[]" class="form-control" multiple id="imageUpload">
                                <button type="submit" class="btn btn-primary mt-3" name="add_slider_btn">Save</button>
                                <span id="fileError" class="text-danger"></span>
                            </div>
                        </div>
                    </form>
                    <script>
                        function validateForm() {
                            var fileInput = document.getElementById('imageUpload');
                            var errorMessage = document.getElementById('fileError');
                            if (fileInput.files.length === 0) {
                                errorMessage.textContent = "Please choose at least one file.";
                                return false;
                            }
                            return true;
                        }
                    </script>
                    
                    <hr>
                    <h5>Current Slider Images</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>File Name</th>
                                <th>Order</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($sliders)): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><img src="../uploads/<?php echo $row['image']; ?>" width="100"></td>
                                    <td><?php echo $row['f_name']; ?></td>
                                    <td><?php echo $row['order']; ?></td>
                                    <td>
                                        <form action="" method="POST" style="display:inline-block;">
                                            <input type="hidden" name="slider_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" name="delete_slider_btn"
                                                class="btn btn-danger">Delete</button>
                                        </form>
                                        <form action="" method="POST" style="display:inline-block;">
                                            <input type="hidden" name="slider_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" name="move_up"
                                                class="btn btn-link text-decoration-none text-dark"><i
                                                    class="fas fa-arrow-alt-circle-up fa-lg"></i></button>
                                        </form>
                                        <form action="" method="POST" style="display:inline-block;">
                                            <input type="hidden" name="slider_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" name="move_down"
                                                class="btn btn-link text-decoration-none text-dark"><i
                                                    class="fas fa-arrow-alt-circle-down fa-lg"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include ("includes/footer.php"); ?>