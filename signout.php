<?php
session_start();
session_unset();
session_destroy();
header("Location: Signin.php");
echo "<script>alert('succefully Signout!');</script>";
?>
