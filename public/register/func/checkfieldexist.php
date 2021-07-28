<?php

/*
 *  @author: tracieqwynn
 */

session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';
require_once USER_MOD . '/Account_User.php';
require_once EMAIL_MOD . '/EmailTemplate.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST'):

    // On Click
    if (isset($_POST['ajax_email_check_exist']) && isset($_POST['email'])):

        // Check If Email Exist
        if (!Account_User::check_email_exist($_POST['email'])):
            $otp_requester = $_POST['email'];
            // Generate OTP
            $otp = StringUtils::generate_otp(6);

            // Update OTP In Database
            Account_User::update_email_otp($otp_requester, $otp);

            // Send OTP To OTP Requester
            EmailTemplate::template_emailotp($otp_requester, $otp);

            echo "false"; # -- Email Does Not Exist
        else:
            echo "true"; # -- Email Exist
        endif;

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
