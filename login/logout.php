<?php
session_start(); // Resume session

// Unset or destroy session variables
$_SESSION = array(); // Unset all session variables

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: index.php");
exit();
?>
