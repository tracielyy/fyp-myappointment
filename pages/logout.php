<!--
   Developed By FYP-21-S2-24
-->
<!-- This File Is Solely Used For Debugging -->
<?php
session_start();
require_once '../entities/Account_User.php';
if (isset($_SESSION['user'])) {
    $current_user = unserialize($_SESSION['user']);
    Account_User::logout($current_user->get_email());
    $_SESSION = array();
    session_destroy();
    
    echo "Successfully Logout";
}
// -- Insert Redirecting Location Below --
//header("Location:debugIndex.php");
?>

