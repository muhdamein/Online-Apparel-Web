$(document).ready(function () {
    $('.addToCartBtn').click(function (e) {
        e.preventDefault();

        var prod_id = $(this).val();

        $.ajax({
            method: "POST",
            url: "functions/handlecart.php",
            data: {
                "prod_id": prod_id,
                "scope": "add"
            },
            success: function (response) {
                if (response == 201) {
                    alertify.success("Product added to cart");
                } else if (response == "existing") {
                    alertify.success("Product already exists in cart");
                } else if (response == 401) {
                    alertify.success("Login to continue");
                } else if (response == 500) {
                    alertify.success("Something went wrong");
                }
            }
        });
    });

    $('.addToWishBtn').click(function (e) {
        e.preventDefault();

        var prod_id = $(this).val();

        $.ajax({
            method: "POST",
            url: "functions/handlewish.php",
            data: {
                "prod_id": prod_id,
                "scope": "add"
            },
            success: function (response) {
                if (response == 201) {
                    alertify.success("Product added to wishlist");
                } else if (response == "existing") {
                    alertify.success("Product already exists in wishlist");
                } else if (response == 401) {
                    alertify.success("Login to continue");
                } else if (response == 500) {
                    alertify.success("Something went wrong");
                }
            }
        });
    });

    $(document).on('click', '.deleteItem', function () {
        var cart_id = $(this).val();

        $.ajax({
            method: "POST",
            url: "functions/handlecart.php",
            data: {
                "cart_id": cart_id,
                "scope": "delete"
            },
            success: function (response) {
                if (response == 201) {
                    alertify.success("Item removed successfully");
                    $('#mycart').load(location.href + " #mycart");
                } else {
                    alertify.success(response);
                }
            }
        });
    });

    $(document).on('click', '.deleteItemWish', function () {
        var wish_id = $(this).val();

        $.ajax({
            method: "POST",
            url: "functions/handlewish.php",
            data: {
                "wish_id": wish_id,
                "scope": "delete"
            },
            success: function (response) {
                if (response == 201) {
                    alertify.success("Item removed successfully");
                    $('#mywish').load(location.href + " #mywish");
                } else {
                    alertify.success(response);
                }
            }
        });
    });

    //For Card Number formatted input
    var cardNum = document.getElementById('cr_no');
    cardNum.onkeyup = function (e) {
        if (this.value == this.lastValue) return;
        var caretPosition = this.selectionStart;
        var sanitizedValue = this.value.replace(/[^0-9]/gi, '');
        var parts = [];

        for (var i = 0, len = sanitizedValue.length; i < len; i += 4) {
            parts.push(sanitizedValue.substring(i, i + 4));
        }

        for (var i = caretPosition - 1; i >= 0; i--) {
            var c = this.value[i];
            if (c < '0' || c > '9') {
                caretPosition--;
            }
        }
        caretPosition += Math.floor(caretPosition / 4);

        this.value = this.lastValue = parts.join(' ');
        this.selectionStart = this.selectionEnd = caretPosition;
    }

    //For Date formatted input
    var expDate = document.getElementById('exp');
    expDate.onkeyup = function (e) {
        if (this.value == this.lastValue) return;
        var caretPosition = this.selectionStart;
        var sanitizedValue = this.value.replace(/[^0-9]/gi, '');
        var parts = [];

        for (var i = 0, len = sanitizedValue.length; i < len; i += 2) {
            parts.push(sanitizedValue.substring(i, i + 2));
        }

        for (var i = caretPosition - 1; i >= 0; i--) {
            var c = this.value[i];
            if (c < '0' || c > '9') {
                caretPosition--;
            }
        }
        caretPosition += Math.floor(caretPosition / 2);

        this.value = this.lastValue = parts.join('/');
        this.selectionStart = this.selectionEnd = caretPosition;
    }

});

