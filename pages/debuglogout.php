<!--
   Developed By FYP-21-S2-24
-->
<!-- This File Is Solely Used For Debugging -->
<?php
session_start();

if (isset($_SESSION['user'])) {
    $_SESSION = array();
    session_destroy();
}
//header("Location:debugIndex.php");
?>

