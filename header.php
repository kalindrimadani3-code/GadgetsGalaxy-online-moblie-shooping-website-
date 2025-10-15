<html>
<head>
    <title> header</title>
    <link rel="stylesheet" href="header.css">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
</head>
<body>
  <?php
$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
$wishCount = isset($_SESSION['wishlist']) ? count($_SESSION['wishlist']) : 0;
?> 
<header>
    <nav>
      <img src="img2.png" class="img">
     <div class="title" > Gadgtes Galaxy</div>

      <a href="contactus.html"class="a"><i class='bx bxs-phone'></i></a>
         
</div>
      
    

<div class="user-dropdown" id="userDropdown">
  <div class="user-icon" onclick="toggleDropdown()">
    <i class='bx bxs-user'></i> ▼
  </div>
  <div class="dropdown-menu" id="dropdownMenu">
    <a href="userdashboard.php">My Account</a>
    <a href="Signin.php">Signin</a>
    <a href= "Signup.php"> Signup</a> 
    <a href="#">orders</a>
    <a href="signout.php">signout</a>
  </div>
</div>

      
        </nav>
  </header>
  <script>
function toggleDropdown() {
  const menu = document.getElementById("dropdownMenu");
  menu.style.display = (menu.style.display === "block") ? "none" : "block";
}

// Close dropdown if clicked outside
document.addEventListener("click", function(event) {
  const dropdown = document.getElementById("userDropdown");
  const menu = document.getElementById("dropdownMenu");
  
  if (!dropdown.contains(event.target)) {
    menu.style.display = "none";
  }
});
</script>

</body>
</html>
