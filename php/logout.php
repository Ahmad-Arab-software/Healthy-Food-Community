<?php
session_start(); // Start the session
session_destroy(); // Destroy all data registered to a session
unset($_SESSION["email"]); // Unset the email session variable (optional since session_destroy() removes all session variables)
header("Location: ./index.php"); // Redirect to the login page
exit(); // Ensure no further code is executed
?>
