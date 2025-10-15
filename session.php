<?php
session_start();

// Optional: define a dummy user for testing
// $_SESSION['user'] = 'john_doe';

// Initialize cart if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>
