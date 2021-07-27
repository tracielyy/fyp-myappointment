<?php
session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';
/*
 * VIEW LIST OF ALL THE DOCTORS THAT IS UNDER THE FACILITY
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
        <title>List Of Doctors</title>
        <!--font awesome cdn-->
        <script src = "https://use.fontawesome.com/releases/v5.13.1/js/all.js"></script>
    </head>
    <body>

    </body>
</html>