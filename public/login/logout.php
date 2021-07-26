<!--
   Developed By FYP-21-S2-24
-->
<!-- This the official logout page (Not Fully Tested) -->
<?php
session_start();
/* Load Config File */
require_once '../../resources/config.php';
require '../../vendor/autoload.php';
require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Patient.php';
require_once USER_MOD . '/Medical_Personnel.php';
require_once USER_MOD . '/Facility_Admin.php';
require_once USER_MOD . '/Super_Admin.php';
require_once AUTH_MOD . '/Authentication.php';
require_once ENUMS_PATH . '/User_Type.php';

if (isset($_SESSION['user'])) {
    $current_user = unserialize($_SESSION['user']);
    $usertype = $current_user->get_usertype();
    Authentication::session_logout($current_user->get_email());
    $_SESSION = array();
    session_destroy();

    // -- Insert Redirecting Location Below --
    switch ($usertype) {
        case User_Type::FACIILITY_ADMIN:
        case User_Type::SUPER_ADMIN:
            header("Location:" . LOGIN_WEB . "/stafflogin.php");
            break;
        case User_Type::MEDICAL_PERSONNEL:
        case User_Type::PATIENT:
            header("Location:" . LOGIN_WEB);
            break;
    }
}
header("Location:/");
?>

