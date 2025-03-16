<?php
include ("functions/userfunctions.php");
include ("includes/header.php");
unset($_SESSION['billing_details']);

// Ensure $user_id is set
$user_id = isset($_SESSION['auth_user']['user_id']) ? $_SESSION['auth_user']['user_id'] : null;

if ($user_id) {
    $user_info = getUserInfo($user_id);
    $is_default_checked = $user_info['is_default'] == 1;

    // Fill the checkout form automatically only if is_default is 1
    $defaultName = $is_default_checked ? $user_info['name'] : '';
    $defaultEmail = $is_default_checked ? $user_info['email'] : '';
    $defaultPhone = $is_default_checked ? $user_info['phone'] : '';
    $defaultAddress = $is_default_checked ? $user_info['address'] : '';
    $defaultPincode = $is_default_checked ? $user_info['pincode'] : '';
} else {
    // Handle the case when $user_id is not set, maybe redirect to a login page
    // For now, let's set default values for $user_info and $is_default_checked
    $user_info = [];
    $is_default_checked = false;
    $defaultName = '';
    $defaultEmail = '';
    $defaultPhone = '';
    $defaultAddress = '';
    $defaultPincode = '';
}

?>

<div class="py-3" style="background-color: #776B5D;">
    <div class="container">
        <h6 class="text-white">
            <a class="text-white" href="index.php">
                Home /
            </a>
            <a class="text-white" href="checkout.php">
                Checkout
            </a>
        </h6>
    </div>
</div>

<div class="py-5">
    <div class="container">
        <div class="card">
            <div class="card-body shadow">
                <form id="checkoutForm" action="functions/placeorder.php" method="POST">
                    <div class="row big-white" style="background-color: white !important;">
                        <div class="col-md-7">
                            <div class="row">
                                <h5>Details</h5>
                                <hr>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Name</label>
                                    <input type="text" name="name" required placeholder="Enter your full name"
                                        class="form-control" value="<?= $defaultName ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">E-mail</label>
                                    <input type="email" name="email" required placeholder="Enter your email"
                                        class="form-control" value="<?= $defaultEmail ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Phone</label>
                                    <input type="text" name="phone" required placeholder="Enter your phone number"
                                        class="form-control" value="<?= $defaultPhone ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Pin Code</label>
                                    <input type="text" name="pincode" required placeholder="Enter your pin code"
                                        class="form-control" value="<?= $defaultPincode ?>">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="fw-bold">Address</label>
                                    <textarea id="address" name="address" required class="form-control"
                                        rows="5"><?= $defaultAddress ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <h5>Order Details</h5>
                            <hr>

                            <div id="mycart">

                                <?php
                                $items = getCartItems();
                                $totalPrice = 0;

                                foreach ($items as $citem) {
                                    ?>
                                    <div class="card shadow-sm  mb-3">
                                        <div class="row align-items-center">
                                            <?php
                                            $images = explode(",", $citem['images']);
                                            $first_image = reset($images); // Get the first image from the array
                                            ?>
                                            <div class="col-md-2">
                                                <img src="uploads/<?= $first_image ?>" alt="Image" width="60px">
                                            </div>

                                            <div class="col-md-5">
                                                <label><?= $citem['name'] ?></label>
                                            </div>
                                            <div class="col-md-3">
                                                <label>RM <?= $citem['selling_price'] ?></label>
                                            </div>

                                        </div>
                                    </div>

                                    <?php
                                    $totalPrice += $citem['selling_price'];
                                }

                                ?>
                                <hr>
                                <h5>Total Price : <span id="totalPrice" class="float-end fw-bold">RM <?= $totalPrice ?> </span></h5>

                                <!-- Payment method section -->
                                <div class="row mt-4">
                                    <h5>Payment Method</h5>
                                    <hr>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" id="cod"
                                                value="COD" checked>
                                            <label class="form-check-label" for="cod">
                                                Cash on Delivery (COD)
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                id="credit_card" value="Credit Card">
                                            <label class="form-check-label" for="credit_card">
                                                Credit Card
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Submit button -->
                                <div class="mt-3">
                                    <input type="hidden" name="total_price" value="<?= $totalPrice ?>">
                                    <button type="submit" name="placeOrderBtn"
                                        class="btn btn-outline-primary w-100" id="placeOrderBtn">Confirm Order</button>
                                        
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <script>
                    // JavaScript function to change button text based on selected payment method
                    document.addEventListener('DOMContentLoaded', function() {
                        const codRadio = document.getElementById('cod');
                        const creditCardRadio = document.getElementById('credit_card');
                        const placeOrderBtn = document.getElementById('placeOrderBtn');
                        const totalPriceInput = document.querySelector('input[name="total_price"]');
                        const checkoutForm = document.getElementById('checkoutForm');

                        // Function to update the button text and set the action
                        function updateButtonText() {
                            if (creditCardRadio.checked) {
                                placeOrderBtn.textContent = 'Proceed to Payment';
                                // checkoutForm.action = 'functions/placeorder.php';
                            } else {
                                placeOrderBtn.textContent = 'Confirm Order';
                                checkoutForm.action = 'functions/placeorder.php';
                            }
                        }

                        // Add event listeners to radio buttons
                        codRadio.addEventListener('change', updateButtonText);
                        creditCardRadio.addEventListener('change', updateButtonText);

                        // Initialize button text and form action on page load
                        updateButtonText();

                        // Handle form submission for credit card payment
                        placeOrderBtn.addEventListener('click', function() {
                            if (creditCardRadio.checked) {
                                totalPriceInput.value = <?= $totalPrice ?>;
                                checkoutForm.submit();
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>

<?php include ("includes/footer.php"); ?>

