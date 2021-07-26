<?php

session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';
require_once TIME_MOD . '/Time.php';
require_once FACILITY_MOD . '/Operating_Hours.php';
require_once DB_MOD . '/DbQuery.php';
require_once DB_MOD . '/Database.php';
require_once DB_MOD . '/DbStorage.php';


require_once APPT_MOD . '/Normal_Slot.php';
require_once APPT_MOD . '/Special_Slot.php';

require_once USER_MOD . '/Medical_Personnel.php';
require_once USER_MOD . '/Patient.php';
if (!isset($_SESSION['user'])):
    echo '<script>window.location.href = "./../";</script>'; # -- REDIRECT BACK TO THE HOME PAGE
else:
    $user = unserialize($_SESSION["user"]);
    if ($user->get_usertype() !== User_Type::FACIILITY_ADMIN):
        echo '<script>window.location.href = "./../";</script>'; # -- REDIRECT BACK TO THE HOME PAGE
    else: # -- ONLY ALLOW FACILITY ADMIN
        if ($_SERVER["REQUEST_METHOD"] == "POST"):

        endif;
    endif;
endif;
?>

