<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Include your meta tags, title, CSS files, etc. -->
</head>

<body>
    <?php
    include ("functions/userfunctions.php");
    include ("includes/header.php");
    include ("includes/slider.php");
    ?>

    <div class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4>Trending Products</h4>
                    <div class="underline mb-2"></div>
                    <div class="owl-carousel owl-theme">

                        <?php
                        $trendingProducts = getAllTrending();
                        if (mysqli_num_rows($trendingProducts) > 0) {
                            while ($item = mysqli_fetch_assoc($trendingProducts)) {
                                ?>
                                <div class="item">
                                    <a href="viewproduct.php?product=<?= $item['slug']; ?>">
                                        <div class="card shadow">
                                            <div class="card-body p-0">
                                                <img src="uploads/<?= $item['images']; ?>" alt="Product Image" class="w-100"
                                                    style="height: 300px; object-fit: cover;">
                                                <h6 class="text-center my-2" style="color: #594545;"><?= $item['name']; ?></h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <?php
                            }
                        } else {
                            echo "<p>No trending products available.</p>";
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Carousel Section -->
    <div class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4>Customer Reviews</h4>
                    <div class="underline mb-2"></div>
                    <div class="owl-carousel owl-theme">

                        <?php
                        $reviewsQuery = "SELECT r.*, p.images FROM reviews r JOIN products p ON r.product_id = p.id ORDER BY r.created_at DESC";
                        $reviewsResult = mysqli_query($con, $reviewsQuery);
                        if (mysqli_num_rows($reviewsResult) > 0) {
                            while ($review = mysqli_fetch_assoc($reviewsResult)) {
                                $formattedDate = date('d/m/Y', strtotime($review['created_at']));
                                ?>
                                <div class="item">
                                    <div class="card shadow review-card" style="width: 300px; height: 200px;">
                                        <div class="card-body d-flex p-1">
                                            <div style="width: 100px;">
                                                <img src="uploads/<?= explode(',', $review['images'])[0]; ?>"
                                                    alt="Product Image" class="mb-2" style="width: 80px; height: 100px;">
                                            </div>
                                            <div class="ml-3 flex-grow-1">
                                                <div class="rating mb-2">
                                                    <?php for ($i = 0; $i < 5; $i++): ?>
                                                        <i
                                                            class="fa fa-star <?= $i < $review['rating'] ? 'text-warning' : ''; ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                                <p class="small"><?= $review['feedback']; ?></p>
                                            </div>
                                        </div>
                                        <div class="card-footer text-right p-1">
                                            <small class="text-muted"><?= $formattedDate; ?></small>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>


                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="py-5 bg-f2f2f2">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4>About Us</h4>
                    <div class="underline mb-2"></div>
                    <p>"Reimagined Luxury, Rediscovered Value with Third Alley"</p>
                    <p>Discover unique style at unbeatable prices with our curated collection of thrifted apparel.
                        Elevate your wardrobe with one-of-a-kind pieces that make a statement and reduce fashion waste.
                    </p>
                    <p>Shop now and embrace sustainable fashion with every purchase!</p>

                    <h3>Find us:
                        <a href="https://www.instagram.com/thirdalley_?igsh=MXV0MmoxZW03eDZ3eQ==" target="_blank"
                            title="Follow us on Instagram">
                            <img src="image/insta-icon.png" alt="Instagram Icon" class="instagram-icon">
                        </a>
                        <a href="https://www.tiktok.com/@lorongtiga_?_t=8jGnUntxCxM&_r=1" target="_blank"
                            title="Follow us on Tiktok">
                            <img src="image/tiktok-icon.png" alt="Tiktok Icon" class="tiktok-icon">
                        </a>
                        <a href="https://carousell.app.link/HDlSUz7FAGb" target="_blank" title="Follow us on Carousell">
                            <img src="image/carousell-icon.png" alt="Carousell Icon" class="carousell-icon">
                        </a>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="py-5 bg-dark">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h4 class="text-white">E-Shop</h4>
                    <div class="underline mb-2"></div>
                    <a href="index.php" class="text-white"><i class="fa fa-angle-right"></i> Home</a><br>
                    <a href="#" class="text-white"><i class="fa fa-angle-right"></i> About Us</a><br>
                    <a href="cart.php" class="text-white"><i class="fa fa-angle-right"></i> Cart</a><br>
                    <a href="categories.php" class="text-white"><i class="fa fa-angle-right"></i> Our Collections</a>
                </div>
                <div class="col-md-3">
                    <h4 class="text-white">Address</h4>
                    <p class="text-white">
                        Petronas, Seksyen 15,
                        Bandar Baru Bangi, 43000 Kajang,
                        Selangor
                    </p>
                    <a href="tel:+60107061091" class="text-white"><i class=" fa fa-phone"></i> +60 107061091</a><br>
                    <a href="mailto: thirdalley@gamil.com" class="text-white"><i class="fa fa-envelope"></i>
                        xyz@gmail.com</a>
                </div>
                <div class="col-md-6">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3984.587058788374!2d101.7684834!3d2.934348900000006!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cdc96cad0bc9c1%3A0x244f10f88642c950!2sPETRONAS%20-%20Seksyen%2015%20Bandar%20Baru%20Bangi%20(CITIONE)!5e0!3m2!1sen!2smy!4v1717491072343!5m2!1sen!2smy"
                        class="w-100" height="200" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>

                </div>
            </div>
        </div>
    </div>

    <div class="py-2 bg-danger">
        <div class="text-center">
            <p class="mb-0 text-white">All rights reserved. Copyright @ ThirdAlley - <?= date('Y') ?> </p>
        </div>
    </div>

    <?php include ("includes/footer.php"); ?>

    <script>
        $(document).ready(function () {
            $('.owl-carousel').owlCarousel({
                loop: true,
                margin: 10,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                        nav: true
                    },
                    600: {
                        items: 3,
                        nav: false
                    },
                    1000: {
                        items: 4,
                        nav: true,
                        loop: false
                    }
                }
            });
        });
    </script>
</body>

</html>