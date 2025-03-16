<?php
include ("functions/userfunctions.php");
include ("includes/header.php");

// Get user's information from the database
$user_id = $_SESSION['auth_user']['user_id'];
$user_info = getUserInfo($user_id);

$is_default_checked = $user_info['is_default'] == 1;

if (isset($_POST['updateProfileBtn'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $is_default = isset($_POST['is_default']) ? 1 : 0; // Update the is_default value based on checkbox state
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $pincode = mysqli_real_escape_string($con, $_POST['pincode']);

    // Check if new password is provided and confirm password matches
    if (!empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
        $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
        $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);
        if ($new_password === $confirm_password) {
            $password = updateUserProfile($user_id, $name, $email, $phone, $is_default, $address, $pincode, $new_password);
            $_SESSION['message'] = 'Profile updated successfully';
        } else {
            $_SESSION['error_message'] = 'Passwords do not match';
        }
    } else {
        // No new password provided, update profile without changing password
        updateUserProfile($user_id, $name, $email, $phone, $is_default, $address, $pincode);
        $_SESSION['message'] = 'Profile updated successfully';
    }

    $is_default_checked = $is_default == 1;
}


?>

<div class="py-5">
    <div class="container">
        <div class="card">
            <div class="card-body shadow">
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Name</label>
                            <input type="text" name="name" required placeholder="Enter your full name"
                                class="form-control" value="<?= $user_info['name'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">E-mail</label>
                            <input type="email" name="email" required placeholder="Enter your email"
                                class="form-control" value="<?= $user_info['email'] ?>" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Phone</label>
                            <input type="text" name="phone" required placeholder="Enter your phone number"
                                class="form-control" value="<?= $user_info['phone'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Address</label>
                            <input type="text" name="address" placeholder="Enter your address" class="form-control"
                                value="<?= $user_info['address'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Pin Code</label>
                            <input type="text" name="pincode" placeholder="Enter your pin code" class="form-control"
                                value="<?= $user_info['pincode'] ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <h5>Change Password</h5>
                            <hr>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">New Password</label>
                            <div class="input-group">
                                <input type="password" name="new_password" placeholder="Enter new password"
                                    class="form-control" id="new_password">
                                <span class="input-group-text"
                                    onclick="togglePasswordVisibility('new_password', 'toggleNewPassword')">
                                    <i class="fas fa-eye-slash" id="toggleNewPassword"></i>
                                </span>
                            </div>
                            <?php if (isset($_SESSION['error_message'])): ?>
                                <small
                                    class="text-danger"><?php echo $_SESSION['error_message'];
                                    unset($_SESSION['error_message']); ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Confirm New Password</label>
                            <div class="input-group">
                                <input type="password" name="confirm_password" placeholder="Confirm new password"
                                    class="form-control" id="confirm_password">
                                <span class="input-group-text"
                                    onclick="togglePasswordVisibility('confirm_password', 'toggleConfirmPassword')">
                                    <i class="fas fa-eye-slash" id="toggleConfirmPassword"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <input type="checkbox" id="defaultCheckbox" name="is_default" <?= $is_default_checked ? 'checked' : '' ?>> Set as default
                        </div>
                    </div>
                    <button type="submit" name="updateProfileBtn" class="btn btn-outline-primary">Update
                        Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include ("includes/footer.php"); ?>

<script>
    function togglePasswordVisibility(inputId, toggleIconId) {
        var passwordInput = document.getElementById(inputId);
        var toggleIcon = document.getElementById(toggleIconId);
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.classList.remove("fa-eye-slash");
            toggleIcon.classList.add("fa-eye");
        } else {
            passwordInput.type = "password";
            toggleIcon.classList.remove("fa-eye");
            toggleIcon.classList.add("fa-eye-slash");
        }
    }
</script>