<?php

/*
 *  @author: tracieqwynn
 */

session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';
require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Patient.php';
require_once EMAIL_MOD . '/EmailTemplate.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST'):

    // On Click
    if (isset($_POST['ajax_check_mrid']) && isset($_POST['patientid']) && isset($POST['slotid'])):

      

    else:
        header("Location:" . REGISTER_WEB);
    endif;

endif;
/*
 * Make Sure User Will Be Redirected Away If Accessing This File Directly
 */
if ($_SERVER["REQUEST_METHOD"] == "GET"):
    header("Location:" . REGISTER_WEB);
endif;
?>
