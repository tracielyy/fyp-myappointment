<!--
   Developed By FYP-21-S2-24
-->
<!-- This File Is Solely Used For Debugging -->
<?php
session_start();
/* Load Config File */
require_once '../resources/config.php';
require_once ENTITIES_PATH . '/Account_User.php';
require_once FUNCTIONS_PATH . '/AccountUserFunctions.php';
if (isset($_SESSION['user'])) {
    $current_user = unserialize($_SESSION['user']);
    AccountUserFunctions::session_logout($current_user->get_email());
    $_SESSION = array();
    session_destroy();
    
    echo "Successfully Logout";
}
//header("Location:Index.php");
?>

