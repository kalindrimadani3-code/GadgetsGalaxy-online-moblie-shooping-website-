<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id'])) {
    die("Please login first.");
}

$user_id = $_SESSION['id'];

if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    
    $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id=? AND product_id=?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();

    // Redirect back to dashboard
    header("Location: userdashborad.php");
    exit;
} else {
    die("Invalid request.");
}
?>
