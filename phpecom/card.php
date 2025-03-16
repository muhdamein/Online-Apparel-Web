<?php
include("functions/userfunctions.php");
include("includes/header.php");

// Initialize variables
$totalPrice = isset($_POST['total_price']) ? $_POST['total_price'] : 0;
$billingDetails = $_SESSION['billing_details'] ?? [];
$totalAmount = $billingDetails['totalPrice'] + 10;
$userId = $_SESSION['auth_user']['user_id'];

// Fetch existing cards
$card_query = "SELECT * FROM cards WHERE user_id='$userId'";
$card_query_run = mysqli_query($con, $card_query);

$existingCards = [];
while ($card = mysqli_fetch_assoc($card_query_run)) {
    $existingCards[] = $card;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credit Card</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css" />
    <style>
        .btn-red {
            background-color: #ff0000;
            color: #fff;
            width: 100%;
        }

        .card-custom {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(139, 10, 0, 0.7);
            padding: 20px;
        }

        .form-custom {
            border-radius: 15px;
        }

        .separator {
            border-left: 1px solid #ddd;
            height: 100%;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .payment-amount {
            font-size: 2em;
            margin-top: 50px;
        }

        .btn-red {
            margin-top: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="container-fluid px-1 px-md-2 px-lg-4 py-5 mx-auto">
        <div class="row d-flex justify-content-center">
            <div class="col-xl-7 col-lg-8 col-md-9 col-sm-11">
                <div class="card border-0 card-custom">
                    <div class="row justify-content-center">
                        <div class="header">
                            <h3 class="mb-4">Credit Card</h3>
                            <button type="button" class="btn btn-primary" onclick="window.location.href='checkout.php'"><i class="fa fa-reply"></i>Back</button>
                        </div>
                    </div>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="new-tab" data-toggle="tab" href="#new" role="tab" aria-controls="new" aria-selected="true">New Card</a>
                        </li>
                        <?php if (!empty($existingCards)) : ?>
                            <li class="nav-item">
                                <a class="nav-link" id="existing-tab" data-toggle="tab" href="#existing" role="tab" aria-controls="existing" aria-selected="false">Existing Card</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <!-- New Card Tab Content -->
                        <div class="tab-pane fade show active" id="new" role="tabpanel" aria-labelledby="new-tab">
                            <form method="POST" action="functions/placeorder.php">
                                <div class="row">
                                    <div class="col-sm-7 border-line pb-3 pl-3 pr-3">
                                        <div class="form-group">
                                            <p class="text-muted text-sm mb-0">Name on the card</p>
                                            <input type="text" name="name" placeholder="Name" class="form-control form-custom" required>
                                        </div>
                                        <div class="form-group">
                                            <p class="text-muted text-sm mb-0">Card Number</p>
                                            <input type="text" name="card-num" placeholder="0000 0000 0000 0000" class="form-control form-custom" id="cr_no" minlength="19" maxlength="19" required>
                                        </div>
                                        <div class="form-group">
                                            <p class="text-muted text-sm mb-0">Expiry date</p>
                                            <input type="text" name="exp" placeholder="MM/YY" class="form-control form-custom" id="exp" minlength="5" maxlength="5" required>
                                        </div>
                                        <div class="form-group">
                                            <p class="text-muted text-sm mb-0">CVV/CVC</p>
                                            <input type="password" name="cvv" placeholder="000" class="form-control form-custom" minlength="3" maxlength="3" required>
                                        </div>
                                        <div class="form-group mb-0">
                                            <div class="custom-control custom-checkbox custom-control-inline">
                                                <input id="chk1" type="checkbox" name="chk" class="custom-control-input" checked>
                                                <label for="chk1" class="custom-control-label text-muted text-sm">Save Card</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-1 d-flex justify-content-center align-items-center">
                                        <div class="separator"></div>
                                    </div>
                                    <div class="col-sm-4 text-center justify-content-center pt-4 pb-4">
                                        <div class="row px-3">
                                            <div class="col-sm-6 text-left">
                                                <p>Price:</p>
                                            </div>
                                            <div class="col-sm-6 text-right">
                                                <p><span><?= htmlspecialchars($billingDetails['totalPrice'] ?? 0) ?></span></p>
                                            </div>
                                        </div>
                                        <div class="row px-3">
                                            <div class="col-sm-8 text-left">
                                                <p>Shipping <i class="fas fa-shipping-fast"></i></p>
                                            </div>
                                            <div class="col-sm-4 text-right">
                                                <p>10</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row px-3 justify-content-sm-center">
                                            <h2 class="payment-amount"><span class="text-md font-weight-bold mr-2">RM</span><span><?= $totalAmount ?></span></h2>
                                        </div>
                                        <button type="submit" name="placeOrderCreditCardBtn" class="btn btn-red text-center mt-4">PAY</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Existing Card Tab Content -->
                        <?php if (!empty($existingCards)) : ?>
                            <div class="tab-pane fade" id="existing" role="tabpanel" aria-labelledby="existing-tab">
                                <form method="POST" action="functions/placeorder.php">
                                    <div class="row">
                                        <div class="col-sm-7 border-line pb-3 pl-3 pr-3" style="max-height: 400px; overflow-y: auto;">
                                            <div class="list-group">
                                                <?php foreach ($existingCards as $index => $card) : ?>
                                                    <a href="#" class="list-group-item list-group-item-action">
                                                        <div class="d-flex w-100 justify-content-between">
                                                            <h5 class="mb-1"><?= htmlspecialchars($card['name']) ?></h5>
                                                            <small><?= htmlspecialchars($card['card_number']) ?></small>
                                                        </div>
                                                        <p class="mb-1">Expiry: <?= htmlspecialchars($card['expiry_date']) ?></p>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="selected_card" id="card<?= $index ?>" value="<?= $card['id'] ?>" required>
                                                            <label class="form-check-label" for="card<?= $index ?>">Use this card</label>
                                                            <input type="hidden" name="existingCard" value="true" />
                                                        </div>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>

                                        <div class="col-sm-1 d-flex justify-content-center align-items-center">
                                            <div class="separator"></div>
                                        </div>
                                        <div class="col-sm-4 text-center justify-content-center pt-4 pb-4">
                                            <div class="row px-3">
                                                <div class="col-sm-6 text-left">
                                                    <p>Price:</p>
                                                </div>
                                                <div class="col-sm-6 text-right">
                                                    <p><span><?= htmlspecialchars($billingDetails['totalPrice'] ?? 0) ?></span></p>
                                                </div>
                                            </div>
                                            <div class="row px-3">
                                                <div class="col-sm-8 text-left">
                                                <p>Shipping <i class="fas fa-shipping-fast"></i></p>
                                                </div>
                                                <div class="col-sm-4 text-right">
                                                    <p>10</p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row px-3 justify-content-sm-center">
                                                <h2 class="payment-amount"><span class="text-md font-weight-bold mr-2">RM</span><span><?= $totalAmount ?></span></h2>
                                            </div>
                                            <button type="submit" name="placeOrderCreditCardBtn" class="btn btn-red text-center mt-4">PAY</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>

<?php include("includes/footer.php"); ?>