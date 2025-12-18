<?php
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Delete persistent login cookie
setcookie("login_token", "", time() - 3600, "/", "", false, true);

// Redirect to login page
header("Location: ../index.php");
exit();
?>
