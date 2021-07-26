<?php
session_start();
/* Load Config File */
require_once '../../../resources/config.php';
require '../../../vendor/autoload.php';
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
?><!DOCTYPE html>
<html lang="en">
    <head>
        <link href="https://cdn.jsdelivr.net/npm/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />
        <?php
        include TEMPLATES_PATH . '/bootstrap.php';
        include_once TEMPLATES_PATH . '/navbar.php';
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