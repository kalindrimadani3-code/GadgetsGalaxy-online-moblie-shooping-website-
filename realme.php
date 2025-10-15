<?php
session_start();
require("db.php");

?>

<html>
<head>
    <title></title>
  <link rel="stylesheet" href="allphonecss.css">
</head>
<body>
    <?php include 'header.php' ?>
    
    <div class="pro">
   <div class="topnav">
    <?php include 'productnav.php' ?>
</div>  
</div>  
<div class="image-row">   
<?php
$qry = "SELECT * FROM products WHERE brand='realme'";
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
<?php include 'footer.php'?>
</body>
</html>

