

<?php
if (isset($_POST['submit'])) {
    // DB connection
    $conn = new mysqli("localhost", "root", "", "gadgetsgalaxy");
    if ($conn->connect_error) {
        echo "<script>alert('Connection failed: " . $conn->connect_error . "');</script>";
        exit();
    }

    // Get data safely and trim whitespace
    $username = trim($_POST['username'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Basic validation (you can extend this)
    if (empty($username) || empty($phone) || empty($email) || empty($password)) {
        echo "<script>alert('Please fill in all required fields.'); window.history.back();</script>";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare statement
    $stmt = $conn->prepare("INSERT INTO users (username, phone, email, password) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        echo "<script>alert('Prepare failed: " . $conn->error . "');</script>";
        exit();
    }

    $stmt->bind_param("ssss", $username, $phone, $email, $hashed_password);

    if ($stmt->execute())
         {
        echo "<script>alert('✅ Registration successful! You can now Sign In.'); window.location.href = 'Signin.php';</script>";
         }
        

    $stmt->close();
    $conn->close();
}
?>


<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title> signin</title>
        <link rel="stylesheet" href="signup.css">
        <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    </head>
    <body>
<form method="POST" action="" autocomplte="off">
<div class="wrapper">
            <div class="signin-up_box">
                <div class="signin-up_header">
                    <span> SIGN UP </span>
                </div>
                <div class="input-box">
                      <input type="text" class="input-field" id="user" name="username" required>
                      <label for="user" class="label"> Username</label>
                       <i class='bx  bxs-user'></i> 
                    </div> 
                    <div class="input-box">
                      <input type="text" class="input-field" id="user" name="phone" required="10">
                      <label for="user" class="label"> Phone no</label>
                    <i class='bx  bxs-phone'  ></i> 
                    </div> 
                
                    <div class="input-box">
                      <input type="email" class="input-field" id="user"  name="email"required>
                      <label for="user" class="label"> Email</label>
                       <i class='bx  bxs-envelope'  ></i>
                    </div> 
                

                    <div class="input-box">
                      <input type="password" class="input-field" id="pass"name="password" required>
                      <label for="pass" class="label"> Password</label>
                       <i class='bx  bxs-lock'></i> 
                    </div> 
                    <div class="input-box">
                       <button  type="submit" class="btn" name="submit">Sign Up</button>
                        </div>
                         
                       <div class="SIGN-UP-IN">
                             <p> Alredy you have accocnt <a href="Signin.php">Sign in</a></p>
                        </div>
                    </form>
        </div>
    </body>
</html>