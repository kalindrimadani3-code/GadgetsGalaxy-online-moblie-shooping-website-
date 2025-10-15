<?php
session_start();
require("db.php");

if(!isset($_SESSION['id'])) exit;

$user_id  = $_SESSION['id'];
$username = $_SESSION['username'] ?? 'User';

$product_id = intval($_GET['id'] ?? 0);
if(!$product_id) die("Invalid product ID");

$product = $conn->query("SELECT * FROM products WHERE id=$product_id")->fetch_assoc();
if(!$product) die("Product not found!");

$selectedColor  = $_GET['color']  ?? '';
$selectedMemory = $_GET['memory'] ?? '';
$selectedPrice  = $_GET['price']  ?? $product['discount_price'];



if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
    $user     = $_POST['user'];
    $phone    = $_POST['phone'];
    $address  = $_POST['address'];
    $quality  = $_POST['quality'];
    $quantity = intval($_POST['quantity']);
    $payment  = $_POST['payment_method'];
    $details  = $_POST['payment_details'] ?? '';
    $status   = 'Pending';
    $created  = date("Y-m-d H:i:s");
    

    list($ram, $storage) = explode("+", $selectedMemory);

   $stmt = $conn->prepare("INSERT INTO orders 
    (user_id, user_name, phone_number, address, product_name, ram, device_storage, color, quality, quantity, price, status, created_at, payment_method, payment_details)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param(
    "issssssssidssss",
    $user_id,
    $user, $phone, $address,
    $product['name'], 
    $ram, $storage, $selectedColor,
    $quality, $quantity, $selectedPrice,
    $status, $created, $payment, $details
);

if($stmt->execute()){
    echo "<script>alert(' Order placed successfully!'); window.location.href='thank_you.php';</script>";
    exit;
} else {
    echo "Error: ".$stmt->error;
}

    }
?>

<!DOCTYPE html>
<html>
<head>
<title>Payment Page</title>
<link rel="stylesheet" href="orders.css">
<link href='https://cdn.boxicons.com/fonts/brands/boxicons-brands.min.css' rel='stylesheet'>
</head>
<body>

<div class="container">
<h2>Payment for <?= htmlspecialchars($product['name']) ?></h2>
<p><strong>Color:</strong> <?= htmlspecialchars($selectedColor) ?></p>
<p><strong>Variant:</strong> <?= htmlspecialchars($selectedMemory) ?></p>
<p><strong>Price:</strong> ₹<span id="unitPrice"><?= htmlspecialchars($selectedPrice) ?></span></p>
<p><strong>Total:</strong> ₹<span id="totalPrice"><?= htmlspecialchars($selectedPrice) ?></span></p>

<form method="POST">
    <label>Name:</label>
    <input type="text" name="user" value="<?= htmlspecialchars($username) ?>" required>

    <label>Phone:</label>
    <input type="tel" name="phone" required>

    <label>Address:</label>
    <textarea name="address" required></textarea>

    <label>Quality:</label>
    <select name="quality" required>
        <option value="New">New</option>
        <option value="Used">Used</option>
    </select>

    <label>Quantity:</label>
    <input type="number" id="quantity" name="quantity" value="1" min="1" required>

    <label>Payment Method:</label>
    <select name="payment_method" id="method" required>
        <option value="" disabled selected>-- Select Payment Method --</option>
        <option value="Cash on Delivery">Cash on Delivery</option>
        <option value="UPI">UPI</option>
        <option value="Card">Credit/Debit Card</option>
    </select>

    <div id="upiBox" class="hidden">
        <label>Enter UPI ID:</label>
        <input type="text" name="upi_details" placeholder="example@upi">
    </div>

    <div id="cardBox" class="hidden">
        <label>Enter Card Number:</label>
        <input type="text" name="card_details" maxlength="16" placeholder="1234 5678 9012 3456">
    </div>

    <button type="submit">Pay & Place Order</button>
</form>

<script>
const method = document.getElementById('method');
const quantityInput = document.getElementById('quantity');
const unitPrice = parseFloat(document.getElementById('unitPrice').innerText);
const totalPrice = document.getElementById('totalPrice');

method.addEventListener('change', () => {
    document.getElementById('upiBox').style.display  = (method.value === 'UPI')  ? 'block' : 'none';
    document.getElementById('cardBox').style.display = (method.value === 'Card') ? 'block' : 'none';
});

// Auto-update total price
quantityInput.addEventListener('input', () => {
    const qty = parseInt(quantityInput.value) || 1;
    totalPrice.innerText = (unitPrice * qty).toLocaleString('en-IN');
});
</script>


</body>
</html>
