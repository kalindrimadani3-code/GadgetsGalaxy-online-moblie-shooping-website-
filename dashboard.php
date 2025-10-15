<?php
session_start();

$conn = new mysqli("localhost", "root", "", "gadgetsgalaxy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* 🔹 Step 1: Update Availability when dropdown changes */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $id = $_POST['product_id'];
    $availability = $_POST['availability'];
    $update_sql = "UPDATE products SET availability='$availability' WHERE id=$id";
    $conn->query($update_sql);
    header("Location: " . $_SERVER['PHP_SELF']); // refresh after update
    exit;
}

/* 🔹 Step 2: Fetch latest data */
$sql = "SELECT * FROM products ORDER BY id DESC LIMIT 100";
$result = $conn->query($sql);

/* Count section */
$banner_sql = "SELECT COUNT(*) AS total_banners FROM banners";
$banner_result = $conn->query($banner_sql);
$total_banners = $banner_result->fetch_assoc()['total_banners'];

$product_sql = "SELECT COUNT(*) AS total_products FROM products";
$product_result = $conn->query($product_sql);
$total_products = $product_result->fetch_assoc()['total_products'];

$user_sql = "SELECT COUNT(*) AS total_users FROM users";
$user_result = $conn->query($user_sql);
$total_users = $user_result->fetch_assoc()['total_users'];

$order_sql = "SELECT COUNT(*) AS pending_orders FROM orders WHERE status='pending'";
$order_result = $conn->query($order_sql);
$pending_orders = $order_result->fetch_assoc()['pending_orders'];

$total_orders_sql = "SELECT COUNT(*) AS total_orders FROM orders";
$total_orders_result = $conn->query($total_orders_sql);
$total_orders = $total_orders_result->fetch_assoc()['total_orders'];
?>

<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gadgets Galaxy - Admin Dashboard</title>
  <link rel="stylesheet" href="dashboard1.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar"> 
  <h2>Welcome, Admin!</h2> 
  <div class="logo">
    <img src="logo.png" alt="Admin"> 
    <h2>Gadgets Galaxy</h2> 
    <p>Admin Panel</p> 
  </div> 
  <div class="menu">
    <a href="#"><i class='bx bxs-dashboard'></i> Dashboard</a> 
    <a href="add_product.php"><i class='bx bxs-mobile'></i> Add new Phones</a> 
    <a href="uploads_banner.php"><i class='bx bx-grid-alt'></i> Add Banners</a>
    <a href="orders.php"><i class='bx bx-cart'></i> Orders</a>
    <a href="user_deatils.php"><i class='bx bxs-user'></i> Customers</a> 
    <a href="#"><i class='bx bxs-cog'></i> Settings</a>
  </div>
</div>

<!-- Main Content -->
<div class="main">
  <div class="topbar">
    <h1>Analytics Dashboard</h1>
    <div>
      <button class="btn-logout"><a href="admin_login.php">Logout</a></button>
    </div>
  </div>

  <div class="cards">
    <div class="card">
      <h3>Banner</h3>
      <h1><?php echo $total_banners; ?></h1>
      <p>Total Active Banners</p>
    </div>
    <div class="card">
      <h3>Total Products</h3>
      <h1><?php echo $total_products; ?></h1>
      <p>Available in Store</p>
    </div>
    <div class="card">
      <h3>Total Users</h3>
      <h1><?php echo $total_users; ?></h1>
      <p>Registered Customers</p>
    </div>
    <div class="card">
      <h3>Pending Orders</h3>
      <h1><?php echo $pending_orders; ?></h1>
      <p>Awaiting Processing</p>
    </div>
    <div class="card">
      <h3>Total Orders</h3>
      <h1><?php echo $total_orders; ?></h1>
      <p>Orders Made on Site</p>
    </div>
  </div>

  <?php if ($result->num_rows > 0): ?>
 <table class="tabel">
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Brand</th>
    <th>Price</th>
    <th>Availability</th>
    <th>Actions</th>
    <th>Delete</th>
  </tr>

  <?php
  $result = $conn->query("SELECT id, name, brand, base_price, availability FROM products ORDER BY id DESC");
  while ($row = $result->fetch_assoc()):
  ?>
  <tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo htmlspecialchars($row['name']); ?></td>
    <td><?php echo htmlspecialchars($row['brand']); ?></td>
    <td>₹<?php echo number_format($row['base_price']); ?></td>

    <td>
      <form method="POST" style="margin:0;">
        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
        <select name="availability" onchange="this.form.submit()">
          <option value="Available" <?php if($row['availability']=='Available') echo 'selected'; ?>>Available</option>
          <option value="Unavailable" <?php if($row['availability']=='Unavailable') echo 'selected'; ?>>Unavailable</option>
        </select>
      </form>
    </td>

    <td>
      <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn green">Edit</a>
     
    </td>
    <td>
       <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="btn red" onclick="return confirm('Delete this product?');">Delete</a>
  </td>
  </tr>
  <?php endwhile; ?>
</table>

  <?php endif; ?>
</div>
</body>
</html>
