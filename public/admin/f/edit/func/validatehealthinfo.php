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
require_once ENUMS_PATH . '/Health_Info_Type.php';
require_once HINFO_MOD . '/Health_Info.php';
require_once UTIL_MOD . '/StringUtils.php';

$health_info_form = array(
    'id' => "",
    'title' => "",
    'descriptions' => ""
);
if ($_SERVER['REQUEST_METHOD'] == 'POST'):
    if (isset($_POST['ajax_submit_health_info'])):
        /* Load Data to Array */
        foreach ($_POST as $key => $value) :
            if (isset($health_info_form[$key])) :
                $health_info_form[$key] = htmlspecialchars($value);
                $validArr[$key] = False; // Set All Field Validation Check As False
                $err_msg[$key] = "";
            endif;
        endforeach;
        ###### -- VALIDATION -- ######
        foreach ($health_info_form as $key => $value):

            # Check Empty
            $health_info_form[$key] = StringUtils::trim_string($value);
            if (!empty($value)):

                $validArr[$key] = true;

            else:
                # Some Error Message
                $err_msg[$key] = "Field cannot be blank";
            endif;

        endforeach;
        ###### -- END VALIDATION -- ######
        if (!in_array(FALSE, $validArr)) :
            # Modify Value In Database
            Health_Info::update_healthinfo($health_info_form['id'], $health_info_form['title'], $health_info_form['descriptions']);
            echo "true";
        else: # Validation Fail
            echo "false";
        endif;

    endif;
endif;
/*
 * Make Sure User Will Be Redirected Away If Accessing This File Directly
 */
if ($_SERVER["REQUEST_METHOD"] == "GET"):
    header("Location:" . LOGIN_WEB);
endif;
?>