<?php
session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';
require_once TIME_MOD . '/Time.php';
require_once FACILITY_MOD . '/Operating_Hours.php';
require_once USER_MOD . '/Account_User.php';
require_once MEDDOC_MOD . '/Medical_Record.php';

require_once DB_MOD . '/DbQuery.php';
require_once DB_MOD . '/Database.php';
require_once DB_MOD . '/DbStorage.php';

require_once APPT_MOD . '/Normal_Slot.php';
require_once APPT_MOD . '/Special_Slot.php';

require_once SECURE_MOD . '/ValidateIC.php';

require_once USER_MOD . '/Medical_Personnel.php';
require_once EMAIL_MOD . '/EmailTemplate.php';
require_once EMAIL_MOD . '/EmailVerify.php';

/*
 * FACILITY ADMIN LANDING PAGE (After Login)
 */
if (!isset($_SESSION['user'])):
    header("Location:/"); # -- REDIRECT USER TO THE LANDING PAGE
else:
    $user = unserialize($_SESSION["user"]);
    $user_type = $user->get_usertype();
    $user_email = $user->get_email();

    // Check If User Is Facility Admin
    if (!User_Type::check_user_type(User_Type::FACIILITY_ADMIN, $user_type)):
        header("Location:/"); # -- REDIRECT USER TO THE LANDING PAGE
    else:
        ?><!DOCTYPE html>
        <html lang="en">
            <head>
                <link href="https://cdn.jsdelivr.net/npm/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />
                <?php
                include TEMPLATES_PATH . '/bootstrap.php';
                include_once TEMPLATES_PATH . '/navbar-loggedin.php';
                ?>
                <meta charset = "UTF-8">
                <meta http-equiv = "X-UA-Compatible" content = "IE=edge">
                <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
                <title>Facility Admin Main Page</title>
                <!--font awesome cdn-->
                <script src = "https://use.fontawesome.com/releases/v5.13.1/js/all.js"></script>
            </head>
            <body>

            </body>
        </html>
    <?php
    endif; # -- END USER TYPE CHECK
endif; # -- END SESSION CHECK
?>