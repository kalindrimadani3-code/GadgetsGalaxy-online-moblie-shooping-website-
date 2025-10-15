<?php
// DB Connection
require("db.php");

session_start();

// Login check
if (!isset($_SESSION['id'])) {
    echo "<script>alert('⚠️ Please login first'); window.location.href='Signin.php';</script>";
    exit;
}

$user_id = $_SESSION['id'];
$username = $_SESSION['username'] ?? 'User';
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 1;

// Fetch product info
$product = $conn->query("SELECT * FROM products WHERE id=$product_id")->fetch_assoc();
if (!$product) die("<h2>Product not found!</h2>");

// Fetch images, colors, memory variants
$images = $conn->query("SELECT * FROM products_image WHERE product_id=$product_id");
$colors = $conn->query("SELECT * FROM products_color WHERE product_id=$product_id");
$memory = $conn->query("SELECT * FROM product_memory WHERE product_id=$product_id");

// Price mapping (RAM/Storage → Price)
$priceMap = [];
if ($memory && $memory->num_rows > 0) {
    while ($m = $memory->fetch_assoc()) {
        $priceMap[$m['memory_size']] = $m['price'];
    }
    // Re-fetch for display
    $memory = $conn->query("SELECT * FROM product_memory WHERE product_id=$product_id");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $product['name'] ?? null;

    // Add to Cart
    if (isset($_POST['add_cart']) && $product_name) {
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, product_name, quantity, added_at) VALUES (?, ?, ?, 1, NOW())");
        $stmt->bind_param("iis", $user_id, $product_id, $product_name);

        if ($stmt->execute()) {
            // Success popup + redirect
            echo "<script>alert('✅ Product added to Cart!'); window.location.href='".$_SERVER['PHP_SELF']."?id=".$product_id."';</script>";
            exit;
        } else {
            // Error popup
            echo "<script>alert('❌ Something went wrong while adding to Cart.');</script>";
        }
    }

    // Add to Wishlist
    if (isset($_POST['add_wishlist']) && $product_name) {
        $stmt = $conn->prepare("INSERT INTO wishlist (user_id, product_id, product_name, added_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iis", $user_id, $product_id, $product_name);

        if ($stmt->execute()) {
            echo "<script>alert('💖 Product added to Wishlist!'); window.location.href='".$_SERVER['PHP_SELF']."?id=".$product_id."';</script>";
            exit;
        } else {
            echo "<script>alert('❌ Something went wrong while adding to Wishlist.');</script>";
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($product['name']) ?> | Gadgets Galaxy</title>
<link rel="stylesheet" href="product_details.css">
<link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
</head>
<body>

<div class="container">

  <!-- Thumbnails -->
  <div class="thumbs">
    <?php while ($img = $images->fetch_assoc()) { ?>
      <img src="<?= $img['image_url'] ?>" onclick="changeMain('<?= $img['image_url'] ?>')">
    <?php } ?>
  </div>

  <!-- Main Image -->
  <div class="main-image">
    <img id="mainImage" src="<?= $product['main_image'] ?>" alt="Product Image">
  </div>

  <!-- Product Info -->
  <div class="info">
    <div class="product-title" id="title"><?= $product['name'] ?></div>

    <div class="price" id="price">
      ₹<?= number_format($product['discount_price']) ?>
      <del>₹<?= number_format($product['base_price']) ?></del>
    </div>

    <div class="rating">
      ⭐ <?= $product['rating'] ?> | <?= $product['bought_count'] ?> bought
    </div>

    <!-- Color Selection -->
    <strong>Colour:</strong> <span id="color-name"></span>
    <div class="variant-box">
      <?php while ($clr = $colors->fetch_assoc()) { ?>
        <div class="variant-item" onclick="selectColor('<?= $clr['color_name'] ?>', '<?= $clr['image_url'] ?>', this)">
          <?= $clr['color_name'] ?>
        </div>
      <?php } ?>
    </div>

    <!-- Memory Selection -->
    <strong>Variant (RAM + Storage):</strong>
    <div class="memory-box">
      <?php while ($mem = $memory->fetch_assoc()) { ?>
        <div class="memory-item" onclick="selectMemory('<?= $mem['memory_size'] ?>', this)">
          <?= $mem['memory_size'] ?>
        </div>
      <?php } ?>
    </div>

    <!-- Specs -->
    <table class="specs">
      <tr><td><b>Brand:</b></td><td><?= ucfirst($product['brand']) ?></td></tr>
      <tr><td><b>OS:</b></td><td><?= ucfirst($product['os']) ?></td></tr>
      <tr><td><b>Processor:</b></td><td><?= ucfirst($product['processor']) ?></td></tr>
      <tr><td><b>Battery:</b></td><td><?= ucfirst($product['battery']) ?></td></tr>
    </table>

    <!-- Buttons -->
    <div class="hover-buttons">
      <form id="buyForm" action="orders.php" method="GET" style="display:inline;">
        <input type="hidden" name="id" value="<?= $product['id'] ?>">
        <input type="hidden" id="buyColor" name="color">
        <input type="hidden" id="buyMemory" name="memory">
        <input type="hidden" id="buyPrice" name="price">
        <button type="submit" class="buy"><i class='bx bxs-phone'></i> BUY</button>
      </form>

      <form method="post" >
        <input type="hidden" name="pid" value="<?= $product['id'] ?>">
        <button type="submit" name="add_cart" class="btn-cart"><i class='bxr bxs-cart' style='color:#000000'></i> Add to Cart</button>
      </form>

      <form method="post" >
        <input type="hidden" name="pid" value="<?= $product['id'] ?>">
        <button type="submit" name="add_wishlist" class="btn-wishlist"><i class='bxr bxs-heart' style='color:#000000'></i> Wishlist</button>
      </form>

      <a href="index1.php" class="btn-back">Back To Home</a>
    </div>
  </div>
</div>



<script>
const priceMap = <?= json_encode($priceMap) ?>;

function changeMain(src) {
  document.getElementById('mainImage').src = src;
}

function selectColor(name, image, element) {
  document.getElementById("color-name").innerText = name;
  document.getElementById("mainImage").src = image;
  document.querySelectorAll(".variant-item").forEach(el => el.classList.remove("selected"));
  element.classList.add("selected");
  document.getElementById("buyColor").value = name;
  updateTitle();
}

function selectMemory(size, element) {
  document.querySelectorAll(".memory-item").forEach(el => el.classList.remove("selected"));
  element.classList.add("selected");

  const price = priceMap[size];
  const original = <?= $product['base_price'] ?>;
  const discount = Math.round(((original - price) / original) * 100);

  document.getElementById("price").innerHTML =
    `₹${price.toLocaleString()} <del>₹${original.toLocaleString()}</del> -${discount}%`;

  document.getElementById("buyMemory").value = size;
  document.getElementById("buyPrice").value = price;
  updateTitle();
}

function updateTitle() {
  const color = document.getElementById("color-name").innerText;
  const size = document.querySelector(".memory-item.selected")?.innerText || "";
  document.getElementById("title").innerText =
    "<?= $product['name'] ?>" + " (" + color + ", " + size + ")";
}

window.onload = function() {
  document.querySelector(".variant-item")?.click();
  document.querySelector(".memory-item")?.click();
};



</script>

</body>
</html>
