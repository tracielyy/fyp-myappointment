<!--
   Developed By FYP-21-S2-24
-->
<!-- This Will Remove User Session Information -->
<?php
session_start();

// Make Sure User Is Logged In
if (isset($_SESSION['user'])) {
    $_SESSION = array();
    session_destroy();
}
// -- <Insert Redirecting Location Below> --


?>

