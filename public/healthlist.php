<!DOCTYPE html>\

<?php
    session_start();
    require_once '../resources/config.php';
    require_once ENTITIES_PATH . '/Account_User.php';
    require_once ENTITIES_PATH . '/Appointment_Record.php';
    require_once ENUMS_PATH . '/User_Type.php';
    require_once FUNCTIONS_PATH . '/PatientFunctions.php';
    require_once FUNCTIONS_PATH . '/AccountUserFunctions.php';
    ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health and Information Tips</title>
</head>
<body>

<?php

if (isset($_SESSION["user"])):
  $user = unserialize($_SESSION["user"]);
  include COMPONENTS_PATH . '/navbar-loggedin.php';
else:
  include COMPONENTS_PATH . '/navbar.php';
endif;

?>
    <div class="container"> 
        <div class="row">


        </div>
    </div>

    
</body>
</html>