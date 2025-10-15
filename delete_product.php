<?php

require 'db.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id = intval($_GET['id']);

// Get product image to delete file
$stmt = $con->prepare("SELECT main_image FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows !== 1) {
    header("Location: dashboard.php");
    exit;
}
$product = $res->fetch_assoc();
$stmt->close();

// Delete image file
if ($product['image'] && file_exists("uploads/" . $product['image'])) {
    unlink("uploads/" . $product['image']);
}

// Delete DB record
$stmt = $con->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

$con->close();

header("Location: dashboard.php");
exit;
