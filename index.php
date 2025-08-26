<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Start session
session_start();

// If already logged in, redirect to user dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: backend/user_dashbord.php");
    exit();
}

// Default redirect to login page
header("Location: templates/user_login.html");
exit();
?>
