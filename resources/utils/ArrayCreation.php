<?php

/*
 * @author yanying (Tracie)
 */
/* Load Config File */
require_once '../resources/config.php';
require_once UTILS_PATH . '/StringUtils.php';

class ArrayCreation {
    /*
     * --------------------------
     * Default Array Creations
     * --------------------------
     */

    public static function account_creation_array(string $usertype): array {

        // -- Generate Verification Token (Security) 
        $vtoken_length = 25; 
        $vtoken = StringUtils::generate_token($vtoken_length);

        # -- Create New Time Object -- #
        $time = new Time();

        # -- Account Details Array -- #
        $user_data_arr['accountdetails'] = array(
            'usertype' => $usertype,
            'createdon' => array(
                'date' => $time->get_date(),
                'time' => $time->get_time()
            ),
            'verification' => array(
                'verified' => false,
                'vtoken' => $vtoken
            )
        );

        # -- Merge All The Arrays -- #
        $data_arr = array_merge($user_data_arr, self::fresh_session_array(), self::fresh_passwordreset_array());
        return $data_arr;
    }

    public static function account_verified_array(): array {

        # -- Verified Array -- #
        $verification['verification'] = array(
            'verified' => true,
            'vtoken' => ''
        );

        return $verification;
    }

    public static function fresh_session_array(): array {

        # -- Reset The Session Array -- #
        $session_arr["session"] = array(
            "sessionid" => "",
            "isloggedin" => false,
            "token" => "",
            "ipaddress" => ""
        );
        return $session_arr;
    }

    public static function used_session_array(string $sessionid, string $token, string $ipaddress): array {

        # -- Fill The Session Array With Relevant Data -- #
        $session_arr["session"] = array(
            "sessionid" => $sessionid,
            "isloggedin" => true,
            "token" => $token,
            "ipaddress" => $ipaddress
        );
        return $session_arr;
    }

    public static function fresh_passwordreset_array(?string $passwordtoken = ""): array {

        # -- Create New Time Object -- #
        $time = new Time();

        #-- Creating Password Reset Array -- #
        $passwordreset_arr['passwordreset'] = array(
            "passwordtoken" => $passwordtoken,
            "requestedon" => array(
                "date" => $time->get_date(),
                "time" => $time->get_time()
            ),
            "tokenused" => false,
            "usedon" => array(
                "date" => "",
                "time" => ""
            )
        );
        return $passwordreset_arr;
    }

    public static function used_passwordreset_array(): array {

        # -- Create New Time Object -- #
        $time = new Time();

        $passwordreset_arr["passwordreset"] = array(
            "tokenused" => true,
            "usedon" => array(
                "date" => $time->get_current_date(),
                "time" => $time->get_current_time()
            )
        );
        return $passwordreset_arr;
    }

}

?>
