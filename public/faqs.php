<!-- This File Is Solely Used For Debugging -->
<!DOCTYPE html>
<?php
session_start();
/* Load Config File */
require_once '../resources/config.php';
?>

<html>
    <head>
        <!-- Title -->
        <title>FYP-21-S2-24: FAQs</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Styling -->
        <?php require COMPONENTS_PATH . '/bootstrap.php' ?>

    </head>
    <body>
        <!-- Logic -->
        <?php
        require_once ENTITIES_PATH . '/Account_User.php';
        // Code here
        ?>
        <!-- Show Different Sections Of FAQs  (Make Sure Can MInimize and Maximize) -->
        <div>
            <!-- Navigation -->  
            <?php include_once COMPONENTS_PATH . '/navbar.php' ?>
        </div>
        <!-- Footer -->
    </body>
</html>
