<?php
require("db.php");

// === Function to save uploaded file ===
function saveFile($file, $subFolder) {
    $adminPath = "uploads/$subFolder/";
    $userPath  = "../userpanel/uploads/$subFolder/";

    if (!file_exists($adminPath)) mkdir($adminPath, 0777, true);
    if (!file_exists($userPath)) mkdir($userPath, 0777, true);

    $fileName = time() . "_" . basename($file["name"]);
    $adminFile = $adminPath . $fileName;
    $userFile  = $userPath . $fileName;

    move_uploaded_file($file["tmp_name"], $adminFile);
    copy($adminFile, $userFile);

    return "uploads/$subFolder/" . $fileName;
}

// === Get product ID ===
$product_id = intval($_GET['id']);
$product = $con->query("SELECT * FROM products WHERE id='$product_id'")->fetch_assoc();

if (!$product) die("Product not found!");

// === Update logic ===
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $base_price = $_POST['base_price'];
    $discount_price = $_POST['discount_price'];
    $rating = $_POST['rating'];
    $bought_count = $_POST['bought_count'];
    $os = $_POST['os'];
    $processor = $_POST['processor'];
    $battery = $_POST['battery'];

    // Update base details
    $con->query("UPDATE products SET 
        name='$name', brand='$brand', base_price='$base_price', discount_price='$discount_price',
        rating='$rating', bought_count='$bought_count', os='$os', processor='$processor', battery='$battery'
        WHERE id='$product_id'");

    // Update main image if new uploaded
    if (!empty($_FILES["main_image"]["name"])) {
        $main_image = saveFile($_FILES["main_image"], "main_image");
        $con->query("UPDATE products SET main_image='$main_image' WHERE id='$product_id'");
    }

    // Add new color images
    if (!empty($_POST['color_name'])) {
        foreach ($_POST['color_name'] as $i => $color_name) {
            if (!empty($_FILES["color_image"]["name"][$i])) {
                $colorFile = [
                    "name" => $_FILES["color_image"]["name"][$i],
                    "tmp_name" => $_FILES["color_image"]["tmp_name"][$i]
                ];
                $color_img = saveFile($colorFile, "color_image");

                $con->query("INSERT INTO products_color (product_id, color_name, image_url)
                              VALUES ('$product_id', '$color_name', '$color_img')");
            }
        }
    }

    // Add new memory variants
    if (!empty($_POST['memory_size'])) {
        foreach ($_POST['memory_size'] as $i => $size) {
            $price = $_POST['memory_price'][$i];
            if (!empty($size) && !empty($price)) {
                $con->query("INSERT INTO product_memory (product_id, memory_size, price)
                              VALUES ('$product_id', '$size', '$price')");
            }
        }
    }

    // Add new side images
    if (!empty($_FILES['side_images']['name'][0])) {
        foreach ($_FILES['side_images']['tmp_name'] as $i => $tmpName) {
            if ($_FILES['side_images']['error'][$i] === 0) {
                $sideFile = [
                    "name" => $_FILES['side_images']['name'][$i],
                    "tmp_name" => $tmpName
                ];
                $sideImagePath = saveFile($sideFile, "side_image");
                $con->query("INSERT INTO products_image (product_id, image_url)
                              VALUES ('$product_id', '$sideImagePath')");
            }
        }
    }

    echo "<script>alert('Product updated successfully!'); window.location.href='dashboard.php';</script>";
    exit;
}

// === Fetch existing color + memory + side images ===
$colors = $con->query("SELECT * FROM products_color WHERE product_id='$product_id'");
$memory = $con->query("SELECT * FROM product_memory WHERE product_id='$product_id'");
$sideImages = $con->query("SELECT * FROM products_image WHERE product_id='$product_id'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="add_product.css">
</head>
<body>
    <h2>Edit Product - <?php echo htmlspecialchars($product['name']); ?></h2>

    <form method="POST" enctype="multipart/form-data">
        <!-- MAIN DETAILS -->
        <div class="form-section">
            <h3>Main Details</h3>
            <label>Name:</label><input type="text" name="name" value="<?= $product['name'] ?>">
            <label>Brand:</label><input type="text" name="brand" value="<?= $product['brand'] ?>">
            <label>Base Price:</label><input type="number" name="base_price" value="<?= $product['base_price'] ?>">
            <label>Discount Price:</label><input type="number" name="discount_price" value="<?= $product['discount_price'] ?>">
            <label>Rating:</label><input type="number" step="0.1" name="rating" value="<?= $product['rating'] ?>">
            <label>Bought Count:</label><input type="number" name="bought_count" value="<?= $product['bought_count'] ?>">
            <label>OS:</label><input type="text" name="os" value="<?= $product['os'] ?>">
            <label>Processor:</label><input type="text" name="processor" value="<?= $product['processor'] ?>">
            <label>Battery:</label><input type="text" name="battery" value="<?= $product['battery'] ?>">

            <label>Main Image:</label>
            <img src="<?= $product['main_image'] ?>" width="100" height="100"><br>
            <input type="file" name="main_image">
        </div>

        <!-- COLOR SECTION -->
        <div class="form-section">
            <h3>Colors</h3>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <?php while($c = $colors->fetch_assoc()): ?>
                    <div style="text-align:center;">
                        <img src="<?= $c['image_url'] ?>" width="100" height="100"><br>
                        <small><?= htmlspecialchars($c['color_name']) ?></small><br>
                        <a href="delete_color_image.php?id=<?= $c['id'] ?>&product_id=<?= $product_id ?>" style="color:red;">Delete</a>
                    </div>
                <?php endwhile; ?>
            </div>

            <div id="colors">
                <label>New Color Name:</label><input type="text" name="color_name[]">
                <label>New Color Image:</label><input type="file" name="color_image[]">
            </div>
            <button type="button" onclick="addColor()">+ Add More Colors</button>
        </div>

        <!-- MEMORY -->
        <div class="form-section">
            <h3>Memory Variants</h3>
            <?php while($m = $memory->fetch_assoc()): ?>
                <p><?= $m['memory_size'] ?> — ₹<?= $m['price'] ?></p>
            <?php endwhile; ?>
            <div id="memory">
                <label>New Memory Size:</label><input type="text" name="memory_size[]">
                <label>Price:</label><input type="number" name="memory_price[]">
            </div>
            <button type="button" onclick="addMemory()">+ Add More Memory</button>
        </div>

        <!-- SIDE IMAGES -->
        <div class="form-section">
            <h3>Side Images</h3>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <?php while($s = $sideImages->fetch_assoc()): ?>
                    <div style="text-align:center;">
                        <img src="<?= $s['image_url'] ?>" width="100" height="100"><br>
                        <a href="delete_side_image.php?id=<?= $s['id'] ?>&product_id=<?= $product_id ?>" style="color:red;">Delete</a>
                    </div>
                <?php endwhile; ?>
            </div>
            <div id="sideImages">
                <label>New Side Image:</label><input type="file" name="side_images[]">
            </div>
            <button type="button" onclick="addSideImage()">+ Add More Side Images</button>
        </div>

        <button class="btn" type="submit">Update Product</button>
    </form>

<script>
function addColor(){
    let div=document.createElement('div');
    div.innerHTML='<label>Color Name:</label><input type="text" name="color_name[]"> <label>Color Image:</label><input type="file" name="color_image[]">';
    document.getElementById('colors').appendChild(div);
}
function addMemory(){
    let div=document.createElement('div');
    div.innerHTML='<label>Memory Size:</label><input type="text" name="memory_size[]"> <label>Price:</label><input type="number" name="memory_price[]">';
    document.getElementById('memory').appendChild(div);
}
function addSideImage(){
    let div=document.createElement('div');
    div.innerHTML='<label>Side Image:</label><input type="file" name="side_images[]">';
    document.getElementById('sideImages').appendChild(div);
}
</script>
</body>
</html>
