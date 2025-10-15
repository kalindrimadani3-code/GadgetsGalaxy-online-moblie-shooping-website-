<?php
session_start();
require("db.php");
?>


<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gadgets Galaxy</title>

  <!-- External CSS & Fonts -->
  <link rel="stylesheet" href="index1.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
</head>

<body>

<!-- Header -->
 
<?php include 'header.php'; ?>

<!-- Spacer (for fixed navbar) -->
<div style="height: 100px;"></div>

<!-- Swiper -->
<div class="swiper mySwiper">
  <div class="swiper-wrapper">
  <?php
// DB mathi banner images fetch karo
$bannerQry = "SELECT * FROM banners ORDER BY image DESC";
$bannerResult = mysqli_query($conn, $bannerQry);

if ($bannerResult && mysqli_num_rows($bannerResult) > 0) {
    while ($banner = mysqli_fetch_assoc($bannerResult)) {
        $imagePath = '/admin/banners/' . $banner['image']; // PHP thi path build thay chhe
        ?>
        <div class="swiper-slide">
            <img src="<?= htmlspecialchars($imagePath) ?>" alt="Banner <?= $banner['id'] ?>" style="width:100%; height:auto;">
        </div>
        <?php
    }
} else {
    ?>
    <div class="swiper-slide">
        <p>No banners found!</p>
    </div>
    <?php
}
?>
  </div>

  <!-- Swiper Pagination -->
  <div class="swiper-pagination"></div>
</div>




<?php require "productnav.php" ?>
<!-- ✅ Product Cards -->
<div class="image-row">   
<?php
$qry = "SELECT * FROM products WHERE id=12";
$result = mysqli_query($conn, $qry);

if ($result && mysqli_num_rows($result) > 0) {
   while ($row = mysqli_fetch_assoc($result)) {
    echo '<div class="card">';
    echo '  <div class="hover-card">';
    echo '  <img src="' . htmlspecialchars($row['main_image']) . '" alt="' . htmlspecialchars($row['name']) . '" style="width:200px; height:200px; object-fit:cover;">';
    echo '    <div class="hover-content">';
    echo '      <h2>' . htmlspecialchars($row['name']) . '</h2>';
    echo '      <a href="product_details.php?id=' . $row['id'] . '" style="color:pink; font-size:16px;">More Details --&gt;</a>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';
   }
} else {
    echo "<p>No products found!</p>";
}
?>
<?php
$qry = "SELECT * FROM products WHERE  id=13";
$result = mysqli_query($conn, $qry);

if ($result && mysqli_num_rows($result) > 0) {
   while ($row = mysqli_fetch_assoc($result)) {
    echo '<div class="card">';
    echo '  <div class="hover-card">';
    echo '  <img src="' . htmlspecialchars($row['main_image']) . '" alt="' . htmlspecialchars($row['name']) . '" style="width:200px; height:200px; object-fit:cover;">';
    echo '    <div class="hover-content">';
    echo '      <h2>' . htmlspecialchars($row['name']) . '</h2>';
    echo '      <a href="product_details.php?id=' . $row['id'] . '" style="color:pink; font-size:16px;">More Details --&gt;</a>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';
   }
} else {
    echo "<p>No products found!</p>";
}
?>

<?php
$qry = "SELECT * FROM products WHERE id=14";
$result = mysqli_query($conn, $qry);

if ($result && mysqli_num_rows($result) > 0) {
   while ($row = mysqli_fetch_assoc($result)) {
    echo '<div class="card">';
    echo '  <div class="hover-card">';
    echo '  <img src="' . htmlspecialchars($row['main_image']) . '" alt="' . htmlspecialchars($row['name']) . '" style="width:200px; height:200px; object-fit:cover;">';
    echo '    <div class="hover-content">';
    echo '      <h2>' . htmlspecialchars($row['name']) . '</h2>';
    echo '      <a href="product_details.php?id=' . $row['id'] . '" style="color:pink; font-size:16px;">More Details --&gt;</a>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';
   }
} else {
    echo "<p>No products found!</p>";
}
?>


<?php
$qry = "SELECT * FROM products WHERE id=15";
$result = mysqli_query($conn, $qry);

if ($result && mysqli_num_rows($result) > 0) {
   while ($row = mysqli_fetch_assoc($result)) {
    echo '<div class="card">';
    echo '  <div class="hover-card">';
    echo '  <img src="' . htmlspecialchars($row['main_image']) . '" alt="' . htmlspecialchars($row['name']) . '" style="width:200px; height:200px; object-fit:cover;">';
    echo '    <div class="hover-content">';
    echo '      <h2>' . htmlspecialchars($row['name']) . '</h2>';
    echo '      <a href="product_details.php?id=' . $row['id'] . '" style="color:pink; font-size:16px;">More Details --&gt;</a>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';
   }
} else {
    echo "<p>No products found!</p>";
}
?>

<?php
$qry = "SELECT * FROM products WHERE id=18";
$result = mysqli_query($conn, $qry);

if ($result && mysqli_num_rows($result) > 0) {
   while ($row = mysqli_fetch_assoc($result)) {
    echo '<div class="card">';
    echo '  <div class="hover-card">';
    echo '  <img src="' . htmlspecialchars($row['main_image']) . '" alt="' . htmlspecialchars($row['name']) . '" style="width:200px; height:200px; object-fit:cover;">';
    echo '    <div class="hover-content">';
    echo '      <h2>' . htmlspecialchars($row['name']) . '</h2>';
    echo '      <a href="product_details.php?id=' . $row['id'] . '" style="color:pink; font-size:16px;">More Details --&gt;</a>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';
   }
} else {
    echo "<p>No products found!</p>";
}
?>

<?php
$qry = "SELECT * FROM products WHERE id=17";
$result = mysqli_query($conn, $qry);

if ($result && mysqli_num_rows($result) > 0) {
   while ($row = mysqli_fetch_assoc($result)) {
    echo '<div class="card">';
    echo '  <div class="hover-card">';
    echo '  <img src="' . htmlspecialchars($row['main_image']) . '" alt="' . htmlspecialchars($row['name']) . '" style="width:200px; height:200px; object-fit:cover;">';
    echo '    <div class="hover-content">';
    echo '      <h2>' . htmlspecialchars($row['name']) . '</h2>';
    echo '      <a href="product_details.php?id=' . $row['id'] . '" style="color:pink; font-size:16px;">More Details --&gt;</a>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';
   }
} else {
    echo "<p>No products found!</p>";
}
?>

</div>


<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script>
    var swiper = new Swiper(".mySwiper", {
      loop: true,
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
    });
  </script>


<!-- Footer -->
<?php include 'footer.php'; ?>

</body>
</html>