<?php

session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';
require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Patient.php';
require_once UTIL_MOD . '/Regex.php';
require_once UTIL_MOD . '/StringUtils.php';
require_once EMAIL_MOD . '/EmailTemplate.php';
require_once EMAIL_MOD . '/EmailVerify.php';

require_once SECURE_MOD . '/ValidateIC.php';

function otp_valid(Time $requestedon): bool {
    $current_datetime = new Time();

    // -- Check For Expiration Date
    $time_diff = $current_datetime->datetime_second_diff($current_datetime, $requestedon);
    $validity_duration = 60 * 60; # 1 hour
    if ($time_diff < $validity_duration):
        return true; # -- NOT EXPIRED
    endif;
    return false; # -- EXPIRED
}

// -- Checks If OTP Matches
function compare_otp(string $input_otp, string $db_otp): bool {

    if ($input_otp === $db_otp):

        return true; # -- OTP MATCHES
    endif;

    return false; # -- OTP DOES NOT MATCH
}

function verify_user_email(string $user_email, string $input_otp): bool {

    # STEP 0: INITIALISE OTP INFO
    $email_verify = new EmailVerify($user_email);
    $email_verify->set_verify_data();

    # STEP 1: CHECK IF THE EMAIL HAS REQUESTED FOR OTP & CHECK IF THE OTP IS STILL VALID
    if (($email_verify->has_requested()) && (otp_valid($email_verify->get_requestedon()))):

        # STEP 2: COMPARE THE OTP
        if (compare_otp($input_otp, $email_verify->get_otp())):
            return true;
        else:
            return false;
        endif;
    endif;
    return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST"):
    if (isset($_POST['ajax_otp'])):
        if (verify_user_email($_POST['email'], $_POST['otp'])):
            echo "true";
        else:
            echo "false";
        endif;
    endif; # -- END AJAX REQUEST
endif; # -- END POST REQUEST
?>

