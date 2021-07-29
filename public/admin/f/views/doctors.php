<?php
session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';
/*
 * VIEW LIST OF ALL THE DOCTORS THAT IS UNDER THE FACILITY (FACILITY ADMIN)
 */

if (!isset($_SESSION['user'])):
    header("Location:/"); # -- REDIRECT USER TO THE LANDING PAGE
else:
    $user = unserialize((string) $_SESSION["user"]);
    $user_type = $user->get_usertype(); 
    $user_email = $user->get_email();
    $user_facility = $user->get_facility();
    if (!User_Type::check_user_type(User_Type::FACIILITY_ADMIN, $user_type)):
        header("Location:/"); # -- REDIRECT USER TO THE LANDING PAGE
    else:

        $all_practitioners = Medical_Personnel::retrieve_personnel_by_facility_spec($user_facility->get_facilityid());
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
                <title>List Of Doctors</title>
                <!--font awesome cdn-->
                <script src = "https://use.fontawesome.com/releases/v5.13.1/js/all.js"></script>
            </head>
            <body>
                <!-- LIST ALL THE DOCTORS IN THE FACILITY -->
            </body>
        </html>
    <?php
    endif; # -- END USER CHECK
endif; # -- END SESSION CHECK
?>