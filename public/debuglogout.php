<!--
   Developed By FYP-21-S2-24
-->
<!-- This the official logout page (Not Fully Tested) -->
<?php
session_start();
/* Load Config File */
require_once '../resources/config.php';
require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Patient.php';
require_once USER_MOD . '/Medical_Personnel.php';
require_once USER_MOD . '/Facility_Admin.php';
require_once USER_MOD . '/Super_Admin.php';
require_once AUTH_MOD . '/Authentication.php';
if (isset($_SESSION['user'])) {
    $current_user = unserialize($_SESSION['user']);
    Authentication::session_logout($current_user->get_email());
    $_SESSION = array();
    session_destroy();
    
    //echo "Successfully Logout";
}
// -- Insert Redirecting Location Below --
header("Location:./");
?>

