<?php
  require ("db.php");
?>
<html>
    <head>
        <title> admin login</title>
<link rel="stylesheet" href="admin_login.css"> 
  
</head>
<body >
      <div> 
        <div class="logo">
            <img src="logo.png">

        </div>

</div>
         
<form method="POST" action="">
    <div class="wrapper">
            <div class="login-box">
                <div class="login-header">
                    <span> ADMIN </span> <span>LOGIN </span>
                </div>
                <div class="input-box">
                      <input type="text" class="input-field" id="user" name="admin" required>
                      <label for="user" class="label">Admin</label>
                       <i class='bx bxs-user'></i> 
                    </div> 
                    
                    <div class="input-box">
                      <input type="password" class="input-field" id="pass"  name="password"required>
                      <label for="pass" class="label"> Password</label>
                       <i class='bx  bxs-lock'></i> 
                    </div> 
                 

                    <div class="input-box">
                       <button  type="submit" class="btn" name ="Login"> Login</button>
                        </div>
        
</form>
</div>

</body>
</html>
<?php
if (isset($_POST['Login'])) {
    // only read after submit
    $admin = mysqli_real_escape_string($con, $_POST['admin']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // match with DB columns (Username, Password)
    $query = "SELECT * FROM admins WHERE admin='$admin' AND password='$password'";
    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($con));
    }

   
if (mysqli_num_rows($result) == 1) {
    echo "<script>
        alert('correct');
        window.location.href = 'dashboard.php';
    </script>";




    } else 
    {
        echo "<script>alert('incorrect');</script>";
    }
}
?>
