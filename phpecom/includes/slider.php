<?php
include("config/dbcon.php");
$slider_query = "SELECT * FROM slider ORDER BY `order` ASC";
$sliders = mysqli_query($con, $slider_query);
?>

<div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <?php
    $first = true;
    while($row = mysqli_fetch_assoc($sliders)):
    ?>
      <div class="carousel-item <?php if ($first) { echo 'active'; $first = false; } ?>">
        <img class="d-block w-100" src="uploads/<?php echo $row['image']; ?>" alt="Slide">
      </div>
    <?php endwhile; ?>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>

<!-- Custom styles to make the carousel fit the whole screen frame -->
<style>
  .carousel-inner {
    height: 100vh; /* Set the height to the viewport height */
  }

  .carousel-item img {
    object-fit: cover;
    height: 100vh; /* Set the height to the viewport height */
    width: 100%; /* Ensure the width is 100% */
  }
</style>
