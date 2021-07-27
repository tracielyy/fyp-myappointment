<?php
session_start();
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';
/*
 * SUPER ADMIN LANDING PAGE (After Login)
 */
?><!DOCTYPE html>
<html>
    <head>
        <!-- Title -->
        <title>FYP-21-S2-24: Admin</title>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
        <!-- Styling -->
        <?php include TEMPLATES_PATH . '/bootstrap.php'; ?>


    </head>
    <body>
    </body>

</html>