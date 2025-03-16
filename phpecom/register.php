<?php
session_start();
$applyBackground = false;
include ("includes/header.php");

if (isset($_SESSION['auth'])) {
    $_SESSION['message'] = "You are already registered";
    header('Location: index.php');
    exit();

}
?>

<style>
    .py-5 {
        background: url('image/logobesar.png') no-repeat fixed;
        background-size: 100%;
        background-position: bottom right;
        padding: 5%;
        min-height: 100vh;
    }
</style>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <?php
                if (isset($_SESSION['message'])) {
                    ?>

                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Hey!</strong>
                        <?= $_SESSION['message']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php
                    unset($_SESSION['message']);
                }
                ?>
                <div class="card">
                    

                        <h2 style="text-align: center;">
                        <i class="fa-solid fa-user-plus"></i>
                        </h2>

                    <div class="card-body">
                        <form action="functions/authcode.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter Your Name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="number" name="phone" class="form-control"
                                    placeholder="Enter Your phone number">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Email address</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter Your Email"
                                    id="exampleInputEmail1" aria-describedby="emailHelp">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter Password"
                                    id="exampleInputPassword1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="cpassword" class="form-control"
                                    placeholder="Confirm Password">
                            </div>

                            <button type="submit" name="register_btn" class="btn btn-primary">Submit</button>
                        </form>

                    </div>
                </div>


            </div>
        </div>
    </div>
</div>



<?php include ("includes/footer.php"); ?>