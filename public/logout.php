<!--
   Developed By FYP-21-S2-24
-->
<!-- This the official logout page (Not Fully Tested) -->
<?php
session_start();
/* Load Config File */
require_once '../resources/config.php';
require_once ENTITIES_PATH . '/Account_User.php';
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

