<?php
require("db.php");
$id = intval($_GET['id']);
$product_id = intval($_GET['product_id']);

// Fetch the image path to delete the file physically
$result = $con->query("SELECT image_url FROM products_color WHERE id='$id'");
if ($result && $row = $result->fetch_assoc()) {
    $imagePath = $row['image_url'];
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}

// Delete record from DB
$con->query("DELETE FROM products_color WHERE id='$id'");

// Redirect back to the edit product page
header("Location: edit_product.php?id=$product_id");
exit;
?>
