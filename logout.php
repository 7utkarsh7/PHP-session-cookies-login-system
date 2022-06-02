<?php
// Initialize the session

session_start();
$params = session_get_cookie_params();
setcookie(session_name(), $_COOKIE[session_name()], time() - 1, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
// Unset all of the session variables
$_SESSION = array();
 
// Destroy the session.
session_destroy();
 
// Redirect to login page
header("location: login.php");
exit;
?>