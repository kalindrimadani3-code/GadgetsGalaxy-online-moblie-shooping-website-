<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include("db.php");

if (!isset($_SESSION['id'])) {
    header("Location: Signin.php");
    exit();
}

$user_id = $_SESSION['id'];

// Fetch user info
$user_query = $conn->query("SELECT username, email, phone, address FROM users WHERE id='$user_id'");
$user = $user_query->fetch_assoc();

// ✅ Handle Profile Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    
    $conn->query("UPDATE users SET username='$name', email='$email', phone='$phone', address='$address' WHERE id='$user_id'");
    $success = "Profile updated successfully!";
}

// ✅ Handle Order Cancellation
if (isset($_POST['cancel_order']) && !empty($_POST['cancel_order_id'])) {
    $order_id = intval($_POST['cancel_order_id']);
    
    // Make sure the order belongs to the logged-in user
    $check = $conn->query("SELECT * FROM orders WHERE id='$order_id' AND user_id='$user_id'");
    
    if ($check->num_rows > 0) {
        // Delete the order
        $conn->query("DELETE FROM orders WHERE id='$order_id'");
        $success = "Order #$order_id has been deleted successfully.";
    } else {
        $success = "Invalid order or permission denied.";
    }
}


// ✅ Fetch Orders
$user_id = $_SESSION['id'];



$user_id = $_SESSION['id'];
$orders = $conn->query("SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY id DESC");




// ✅ Fetch Wishlist
$wishlist = $conn->query("SELECT * FROM wishlist WHERE user_id='$user_id'");

// ✅ Fetch Cart
$cart = $conn->query("SELECT * FROM cart WHERE user_id='$user_id'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="userdashboard.css">
    
</head>
<body>
<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?> 👋</h2>

    <?php if (!empty($success)) echo "<div class='alert success'>$success</div>"; ?>

    <!-- ✅ Profile Section -->
    <div class="card">
        <h3>Update Profile</h3>
        <form method="POST">
            <label>Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label>Phone</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>

            <label>Address</label>
          <input type="text" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" required>



            <button type="submit" name="update_profile"class="update">Update</button>
        </form>
    </div>

    <!-- ✅ Orders Section -->
    <div class="card">
        <h3>Your Orders</h3>
        <table>
            <tr>
                <th>Product</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php 
            if ($orders->num_rows > 0) {
                while($row = $orders->fetch_assoc()) {
                    echo "<tr>
                      
                        <td>{$row['product_name']}</td>
                        <td>{$row['status']}</td>
                        <td>{$row['created_at']}</td>";

                    if ($row['status'] != 'Cancelled' && $row['status'] != 'Delivered') {
                        echo "<td>
                            <form method='POST' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to cancel this order?\");'>
                                <input type='hidden' name='cancel_order_id' value='{$row['id']}'>
                                <button type='submit' name='cancel_order' class='cancel-btn'>Cancel</button>
                            </form>
                        </td>";
                    } else {
                        echo "<td>-</td>";
                    }

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No orders found</td></tr>";
            }
            ?>
        </table>
    </div>

    <!-- ✅ Wishlist Section -->
<div class="card">
    <h3>Your Wishlist</h3>
    <table>
        <tr><th>Product</th><th>Action</th></tr>
        <?php 
        $wishlist = $conn->query("SELECT * FROM wishlist WHERE user_id=$user_id");
        if ($wishlist->num_rows > 0) {
            while($row = $wishlist->fetch_assoc()) {
        ?>
                <tr>
                    <td><?= htmlspecialchars($row['product_name'] ?? 'Unknown Product') ?></td>
                    <td>
                        <!-- BUY button -->
                        <form method="get" action="product_details.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $row['product_id'] ?>">
                        <button type="submit" class="buy">BUY</button>

                        </form>

                        <!-- DELETE button -->
                        <form method="post" action="remove_wishlist.php" style="display:inline;"
                              onsubmit="return confirm('Are you sure you want to remove this item from your wishlist?');">
                            <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                            <button type="submit" class="Delete">DELETE</button>
                        </form>
                    </td>
                </tr>
        <?php 
            }
        } else {
            echo "<tr><td colspan='2'>Your wishlist is empty</td></tr>";
        }
        ?>
    </table>
</div>


    <!-- ✅ Cart Section -->
<div class="card">
    <h3>Your Cart</h3>
    <table>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Action</th>
        </tr>
        <?php 
        $cart = $conn->query("SELECT * FROM cart WHERE user_id=$user_id");
        if ($cart->num_rows > 0) {
            while($row = $cart->fetch_assoc()) {
        ?>
            <tr>
                <td><?= htmlspecialchars($row['product_name'] ?? 'Unknown Product') ?></td>
                <td><?= htmlspecialchars($row['quantity']) ?></td>
                <td>
                    <!-- BUY button -->
                             <form method="get" action="product_details.php" style="display:inline;">
                             <input type="hidden" name="id" value="<?= $row['product_id'] ?>">
                             <button type="submit" class="buy">BUY</button>
                       </form>
                    

                    <!-- DELETE button -->
                    <form method="post" action="remove_cart.php" style="display:inline;" 
                          onsubmit="return confirm('Are you sure you want to remove this item?');">
                        <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                        <button type="submit" class="Delete">DELETE</button>
                    </form>
                </td>
            </tr>
        <?php 
            }
        } else {
            echo "<tr><td colspan='3'>Cart is empty</td></tr>";
        }
        ?>
    </table>
</div>

    <!-- ✅ Buttons -->
    <a href="signout.php" class="back">Logout</a>
    <a href="index1.php" class="back">Back To Home</a>
</div>
</body>
</html>
