<?php
session_start();
/* Load Config File */
require_once '../../../../resources/config.php';
require '../../../../vendor/autoload.php';

/*
 * LIST OF ALL THE FACILITIES SUBSCRIBED
 */
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>List Of Facilities</title>
        <?php require TEMPLATES_PATH . '/bootstrap.php' ?>
    </head>
    <body>
        <!-- A TABLE OF ALL LISTED FACILITIES (A to Z) -->
    </body>
</html>