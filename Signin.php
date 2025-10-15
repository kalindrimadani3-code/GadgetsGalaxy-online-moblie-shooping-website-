<?php
session_start();
require("db.php");

if (isset($_POST['Signin'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password']; // plain password from form

    // Find user by username
    $query = "SELECT * FROM `users` WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    // ✅ Check if username exists
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // ✅ Check password
        if (password_verify($password, $row['password'])) {
            // Save user info in session
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            echo "<script>alert('Login Successful'); window.location='userdashboard.php';</script>";
            exit();
        } else {
            // Wrong password
            echo "<script>alert('Your password is wrong');</script>";
        }
    } else {
        // Wrong username
        echo "<script>alert('Your username is wrong');</script>";
    }
}
?>



<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title> signin</title>
    
        <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
        <link rel="stylesheet" href="Signin.css">
    </head>
    <body>
      <form method="POST" action="" autocomplete="off">
               <h1> Welcome to our Gadgets Galaxy!</h1>
        
        <div class="wrapper">
         
            <div class="signin-up_box">
            
                <div class="signin-up_header">
                    <span> SIGN IN </span>
                </div>
                <div class="input-box">
                      <input type="text" class="input-field" id="user" name="username" required>
                      <label for="user" class="label"> Username</label>
                       <i class='bx bxs-user'></i> 
                    </div> 
                    
                    <div class="input-box">
                      <input type="password" class="input-field" id="pass"  name="password"required>
                      <label for="pass" class="label"> Password</label>
                       <i class='bx  bxs-lock'></i> 
                    </div> 
                 

                    <div class="input-box">
                       <button  type="submit" class="btn" name="Signin">Sign in</button>
                        </div>
                         
                       <div class="SIGN-UP-IN">
                              <p>Dont Have an account? <a href="Signup.php">Sign Up</a></p>
                        </div>
        </form>
        </div>
    </body>
    </html>
