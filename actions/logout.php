<?php
// Debug
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

session_start();
// Remove all session variables
session_unset();
// Destroy the session
session_destroy();
// Create a new session
session_start();

$_SESSION['toast'] = "Logged out successfully";
header("Location: ../login.php");
?>