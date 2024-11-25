<?php
// Start the session to check if the user is logged in
session_start();

// Destroy the session and clear cookies
session_unset(); // Remove session variables
session_destroy(); // Destroy the session

// Optionally, remove cookies if you set them for "Remember me"
setcookie('username', '', time() - 3600, '/'); // Expire the username cookie
setcookie('logged_in', '', time() - 3600, '/'); // Expire the logged_in cookie

// Redirect to the homepage or login page after logging out
header("Location: index.php");
exit(); // Make sure the rest of the code doesn't run
