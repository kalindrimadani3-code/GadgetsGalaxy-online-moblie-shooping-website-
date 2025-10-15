<?php
session_start();

// ✅ Admin check (example: manually set $_SESSION['admin'])
require("db.php");

$result = $con->query("SELECT * FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin - Users List</title>
<link rel="stylesheet" href="user_deatils.css">
</head>
<body>
<div class="container">
    <h2>Registered Users</h2>
    <table>
        <thead>
            <tr>
                <th>Sr No.</th>
                <th>Username</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Password</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if($result->num_rows > 0){
            $i=1;
            while($row = $result->fetch_assoc()){
                echo "<tr>
                        <td>{$i}</td>
                        <td>{$row['username']}</td>
                        <td>{$row['phone']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['password']}</td>
                      </tr>";
                $i++;
            }
        } else {
            echo "<tr><td colspan='5'>No users found</td></tr>";
        }
        ?>
        </tbody>
    </table>
    <a href="dashboard.php">Back</a>
</div>
</body>
</html>
