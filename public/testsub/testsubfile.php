<?php
session_start();
/* Load Config File */
require_once '../../resources/config.php';
include TEMPLATES_PATH . '/bootstrap.php';
require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Patient.php';


// -- USER IS LOGGED IN
if (isset($_SESSION['user'])):
    $user = unserialize($_SESSION['user']);
    $user_email = $user->get_email();
endif;
?><!DOCTYPE html>
<html>
    <head>
        <title>Test Sub Folder</title>
    </head>
    <body>
        Testing <?php echo $user_email; ?>
    </body>
</html>

