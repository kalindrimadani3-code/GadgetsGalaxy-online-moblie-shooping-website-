<?php
require("db.php");
function saveFile($file, $subFolder) {
    // Paths for both admin and userpanel
    $adminPath = "uploads/$subFolder/";
    $userPath  = "../userpanel/uploads/$subFolder/"; // adjust path based on location of this file

    // Create folders if not exist
    if (!file_exists($adminPath)) mkdir($adminPath, 0777, true);
    if (!file_exists($userPath)) mkdir($userPath, 0777, true);

    $fileName = time() . "_" . basename($file["name"]);
    $adminFile = $adminPath . $fileName;
    $userFile  = $userPath . $fileName;

    // Save into admin folder
    move_uploaded_file($file["tmp_name"], $adminFile);

    // Copy same file into userpanel folder
    copy($adminFile, $userFile);

    return "uploads/$subFolder/" . $fileName; // relative path for DB
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Main product
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $base_price = $_POST['base_price'];
    $discount_price = $_POST['discount_price'];
    $rating = $_POST['rating'];
    $bought_count = $_POST['bought_count'];
    $os = $_POST['os'];
    $processor = $_POST['processor'];
    $battery = $_POST['battery'];

    // Main image
    $main_image = saveFile($_FILES["main_image"], "main_image");

    $sql = "INSERT INTO products (name, brand, base_price, discount_price, rating, bought_count, os, processor, battery, main_image)
            VALUES ('$name', '$brand', '$base_price', '$discount_price', '$rating', '$bought_count', '$os', '$processor', '$battery', '$main_image')";
    $con->query($sql);
    $product_id = $con->insert_id;

    // Colors
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

    // Memory
    if (!empty($_POST['memory_size'])) {
        foreach ($_POST['memory_size'] as $i => $size) {
            $price = $_POST['memory_price'][$i];
            $con->query("INSERT INTO product_memory (product_id, memory_size, price) 
                          VALUES ('$product_id', '$size', '$price')");
        }
    }

    // Side Images
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

    echo  "<script>
        alert('Product added successfully!');
        window.location.href = 'dashboard.php';
    </script>";
}
?>



 


<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="add_product.css">
</head>
<body>
    <h2> Add New Product</h2>
    <form method="POST" enctype="multipart/form-data">

        <!-- MAIN PRODUCT -->
        <div class="form-section">
            <h3> Main Product Details</h3>
            <label>Name:</label><input type="text" name="name" required>
            <label>Brand:</label><input type="text" name="brand">
            <label>Base Price:</label><input type="number" step="0.01" name="base_price">
            <label>Discount Price:</label><input type="number" step="0.01" name="discount_price">
            <label>Rating:</label><input type="number" step="0.1" name="rating">
            <label>Bought Count:</label><input type="number" name="bought_count">
            <label>OS:</label><input type="text" name="os">
            <label>Processor:</label><input type="text" name="processor">
            <label>Battery:</label><input type="text" name="battery">
            <label>Main Image:</label><input type="file" name="main_image" required>
        </div>

        <!-- COLORS -->
        <div class="form-section">
            <h3>Colors</h3>
            <div id="colors">
                <label>Color Name:</label><input type="text" name="color_name[]">
                <label>Color Image:</label><input type="file" name="color_image[]">
            </div>
            <button type="button" onclick="addColor()">+ Add More Colors</button>
        </div>

        <!-- MEMORY -->
        <div class="form-section">
            <h3> Memory Variants</h3>
            <div id="memory">
                <label>Memory Size:</label><input type="text" name="memory_size[]">
                <label>Price:</label><input type="number" step="0.01" name="memory_price[]">
            </div>
            <button type="button" onclick="addMemory()">+ Add More Memory</button>
        </div>

        <!-- OFFERS -->
<!-- SIDE IMAGES -->
<div class="form-section">
    <h3> Side Images</h3>
    <div id="sideImages">
        <label>Side Image:</label><input type="file" name="side_images[]">
    </div>
    <button type="button" onclick="addSideImage()">+ Add More Side Images</button>
</div>

        <button class="btn" type="submit">Save Product</button>
    </form>

<script>
function addColor() {
    let div = document.createElement('div');
    div.innerHTML = '<label>Color Name:</label><input type="text" name="color_name[]">' +
                    '<label>Color Image:</label><input type="file" name="color_image[]">';
    document.getElementById('colors').appendChild(div);
}
function addMemory() {
    let div = document.createElement('div');
    div.innerHTML = '<label>Memory Size:</label><input type="text" name="memory_size[]">' +
                    '<label>Price:</label><input type="number" step="0.01" name="memory_price[]">';
    document.getElementById('memory').appendChild(div);
}
function addSideImage() {
    let div = document.createElement('div');
    div.innerHTML = '<label>Side Image:</label><input type="file" name="side_images[]">';
    document.getElementById('sideImages').appendChild(div);
}

</script>
</body>
</html>