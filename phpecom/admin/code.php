<?php

include('../config/dbcon.php');
include('../functions/myfunctions.php');

if (isset($_POST['add_category_btn'])) {
    $name = $_POST['name'];
    $slug = $_POST['slug'];
    $description = $_POST['description'];
    $status = isset($_POST['status']) ? "1" : "0";
    $popular = isset($_POST['popular']) ? "1" : "0";

    $image = $_FILES['image']['name'];

    if ($image != "") {
        $path = "../uploads";
        $image_ext = pathinfo($image, PATHINFO_EXTENSION);
        $filename = time() . '.' . $image_ext;

        $cate_query = "INSERT INTO categories (name, slug, description, status, popular, images)
                       VALUES ('$name', '$slug', '$description', '$status', '$popular', '$filename')";

        $cate_query_run = mysqli_query($con, $cate_query);

        if ($cate_query_run) {
            move_uploaded_file($_FILES['image']['tmp_name'], $path . '/' . $filename);
            redirect("add-category.php", "Category Added Successfully");
        } else {
            redirect("add-category.php", "Something Went Wrong: " . mysqli_error($con));
        }
    } else {
        redirect("add-category.php", "Please upload an image");
    }
} else if (isset($_POST['update_category_btn'])) {
    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $slug = $_POST['slug'];
    $description = $_POST['description'];
    $status = isset($_POST['status']) ? "1" : "0";
    $popular = isset($_POST['popular']) ? "1" : "0";

    $new_image = $_FILES['image']['name'];
    $old_image = $_POST['old_image'];
    $path = "../uploads";

    if ($new_image != "") {
        $image_ext = pathinfo($new_image, PATHINFO_EXTENSION);
        $update_filename = time() . '.' . $image_ext;
    } else {
        $update_filename = $old_image;
    }

    $update_query = "UPDATE categories SET name = '$name', slug = '$slug', description = '$description', status = '$status', popular = '$popular', images = '$update_filename' WHERE id = '$category_id'";

    $update_query_run = mysqli_query($con, $update_query);

    if ($update_query_run) {
        if ($new_image != "") {
            move_uploaded_file($_FILES['image']['tmp_name'], $path . '/' . $update_filename);
            if (file_exists($path . '/' . $old_image)) {
                unlink($path . '/' . $old_image);
            }
        }
        redirect("edit-category.php?id=$category_id", "Category Updated Successfully");
    } else {
        redirect("edit-category.php?id=$category_id", "Something Went Wrong: " . mysqli_error($con));
    }
} else if (isset($_POST['delete_category_btn'])) {
    $category_id = mysqli_real_escape_string($con, $_POST['category_id']);

    $category_query = "SELECT * FROM categories WHERE id = '$category_id'";
    $category_query_run = mysqli_query($con, $category_query);
    $category_data = mysqli_fetch_array($category_query_run);
    $image = $category_data['images'];

    $delete_query = "DELETE FROM categories WHERE id = '$category_id'";
    $delete_query_run = mysqli_query($con, $delete_query);

    if ($delete_query_run) {
        if (file_exists("../uploads/" . $image)) {
            unlink("../uploads/" . $image);
        }
        echo 200;
    } else {
        echo 500;
    }
    
} else if (isset($_POST['add_product_btn'])) {
    $category_id = $_POST['category_id'];

    $name = $_POST['name'];
    $slug = $_POST['slug'];
    $small_description = $_POST['small_description'];
    $description = $_POST['description'];
    $original_price = $_POST['original_price'];
    $selling_price = $_POST['selling_price'];
    $status = isset($_POST['status']) ? "1" : "0";
    $trending = isset($_POST['trending']) ? "1" : "0";
    $size = $_POST['size'];
    $width = $_POST['width'];
    $length = $_POST['length'];

    $path = "../uploads";

    $images = $_FILES['images'];
    $image_paths = [];
    foreach ($images['tmp_name'] as $key => $tmp_name) {
        $image_name = time() . '_' . $images['name'][$key];
        $image_paths[] = $image_name;
        move_uploaded_file($tmp_name, $path . '/' . $image_name);
    }

    $insert_query = "INSERT INTO products(category_id, name, slug, small_description, description, original_price, selling_price, status, trending, images, size, width, length) 
                     VALUES ('$category_id', '$name', '$slug', '$small_description', '$description', '$original_price', '$selling_price', '$status', '$trending', '" . implode(",", $image_paths) . "', '$size', '$width', '$length')";

    $insert_query_run = mysqli_query($con, $insert_query);

    if ($insert_query_run) {
        redirect("add-products.php", "Product Added Successfully");
    } else {
        redirect("add-products.php", "Something went wrong");
    }

} else if (isset($_POST['update_product_btn'])) {

    $product_id = $_POST['product_id'];
    $category_id = $_POST['category_id'];

    $name = $_POST['name'];
    $slug = $_POST['slug'];
    $small_description = $_POST['small_description'];
    $description = $_POST['description'];
    $original_price = $_POST['original_price'];
    $selling_price = $_POST['selling_price'];
    $size = $_POST['size'];
    $width = $_POST['width'];
    $length = $_POST['length'];
    $status = isset($_POST['status']) ? "1" : "0";
    $trending = isset($_POST['trending']) ? "1" : "0";

    $path = "../uploads";

    $new_images = $_FILES['images'];
    $new_image_paths = [];

    // Handle new images
    foreach ($new_images['tmp_name'] as $key => $tmp_name) {
        $new_image_name = time() . '_' . $new_images['name'][$key];
        $new_image_paths[] = $new_image_name;
        move_uploaded_file($tmp_name, $path . '/' . $new_image_name);
    }

    $all_image_paths = implode(",", $new_image_paths);

    // Update product with new data
    $update_product_query = "UPDATE products 
                         SET category_id='$category_id', 
                             name='$name', 
                             slug='$slug', 
                             small_description='$small_description', 
                             description='$description',
                             original_price='$original_price', 
                             selling_price='$selling_price',
                             size='$size', 
                             width='$width', 
                             length='$length',
                             status='$status', 
                             trending='$trending', 
                             images='$all_image_paths' 
                         WHERE id='$product_id'";


    $update_product_query_run = mysqli_query($con, $update_product_query);

    if ($update_product_query_run) {
        redirect("edit-product.php?id=$product_id", "Product Updated Successfully");
    } else {
        redirect("edit-product.php?id=$product_id", "Something went wrong");
    }
} else if (isset($_POST['delete_product_btn'])) {
    $product_id = mysqli_real_escape_string($con, $_POST['product_id']);

    $product_query = "SELECT * FROM products WHERE id ='$product_id'";
    $product_query_run = mysqli_query($con, $product_query);
    $product_data = mysqli_fetch_array($product_query_run);
    $image = $product_data['image'];

    $delete_query = "DELETE FROM products WHERE id='$product_id'";
    $delete_query_run = mysqli_query($con, $delete_query);

    if ($delete_query_run) {
        if (file_exists("../uploads/" . $image)) {
            unlink("../uploads/" . $image);
        }
        //redirect("products.php", "Product deleted successfully");
        echo 200;
    } else {
        //redirect("products.php", "Something went wrong");
        echo 500;
    }

} else if (isset($_POST['update_order_btn'])) {
    $track_no = $_POST['tracking_no'];
    $order_status = $_POST['order_status'];

    $updateOrder_query = "UPDATE orders SET status='$order_status', update_time=CURRENT_TIMESTAMP WHERE tracking_no='$track_no'";
    $updateOrder_query_run = mysqli_query($con, $updateOrder_query);

    if ($order_status == 2) { // If order status is "Cancelled"
        // Update visibility status of products to 0
        $updateVisibility_query = "UPDATE products p, order_items oi, orders o SET p.status=0 WHERE oi.prod_id=p.id AND oi.order_id=o.id AND o.tracking_no='$track_no'";
        $updateVisibility_query_run = mysqli_query($con, $updateVisibility_query);

        if ($updateVisibility_query_run) {
            $_SESSION['message'] = "Order Status Updated Successfully. Products are visible again.";
        } else {
            $_SESSION['error'] = "Failed to update product visibility.";
        }
    }

    if ($updateOrder_query_run) {
        $_SESSION['message'] = "Order Status Updated Successfully";
    } else {
        $_SESSION['error'] = "Failed to update order status";
    }

    redirect("vieworder.php?t=$track_no", $_SESSION['message']);
} else {
    header('Location: ../index.php');
}
?>


?>