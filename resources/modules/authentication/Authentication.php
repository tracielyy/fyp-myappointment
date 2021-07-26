<?php

/*
 * @author yanying (Tracie)
 */


/* Load Config File */
//require_once '../resources/config.php';

require_once AUTH_MOD . '/Session.php';

require_once DB_MOD . '/DbQuery.php';
require_once DB_MOD . '/Database.php';

require_once USER_MOD . '/Patient.php';
require_once USER_MOD . '/Medical_Personnel.php';
require_once USER_MOD . '/Super_Admin.php';
require_once USER_MOD . '/Facility_Admin.php';

require_once ENUMS_PATH . '/User_Type.php';

require_once SECURE_MOD . '/Security.php';

require_once UTIL_MOD . '/ArrayCreation.php';

class Authentication {

    public static function login(string $email, string $sessionid, string $token, string $ipaddress): bool {

        # Email Array
        $emailArr ["credentials"] = array(
            'email' => $email
        );

        # Create Array Fields To Update To Google Cloud Firestore
        $session_arr = ArrayCreation::used_session_array($sessionid, $token, $ipaddress);

        # Update Session Field After Success Authentication
        $db = new DbQuery();
        $login = $db->update_field(Database::ACCOUNT_USER, $emailArr, $session_arr);
        return $login; # -- Return Bool (Success or Failure) -- #
    }

    //  -- Check If There Are Any Other Login Session -- //
    public static function check_session(Session $db_session, string $sessionid, string $token): bool {

        #  Session Status 
        if ($db_session->get_isloggedin() == false) :
            return true;
        endif;

        # Compare Token
        return ($db_session->get_token() == $token && $db_session->get_sessionid() == $sessionid);
    }

    // -- Authenticate & Return The User Data If Authenticated Successfully -- //
    public static function authenticate_user(string $user_email, string $usertype, string $password): bool {

        # Credentials
        $credentials['credentials'] = $user_email;

        # User Type
        $account_type ['accountdetails'] = array(
            "usertype" => $usertype
        );

        # Condition Container
        $condition_arr = array_merge($credentials, $account_type);

        # Query For User Using Given Credentials & Condition
        $db = new DbQuery();
        $user_data = $db->fetch_one_document(Database::ACCOUNT_USER, $condition_arr);

        
        /*
         * NANTA TO DO HASH COMPARISON
         */
        $secure = new Security();

        # Check If There Are Any User Returned From The Query
        if ($user_data != NULL):
            $stored_password = $user_data['credentials']['password'];
            $pass_authenticate = $secure->compareHash($password,$stored_password);
            if( $pass_authenticate == 'CORRECT_PASSWORD')
            {
                return True;
            } elseif($pass_authenticate == 'CORRECT_PASSWORD')
            {
                return False;
            }
        endif;

        return False;
    }

    // -- Triggered When User Clicks On "Logout" -- // 
    public static function session_logout(string $email) {

        # Email Array
        $emailArr ["credentials"] = array(
            'email' => $email
        );

        # Declare Session Array With Logged Out Values
        $session_arr = ArrayCreation::fresh_session_array();

        # Update Session Array
        $db = new DbQuery();
        $db->update_field(Database::ACCOUNT_USER, $emailArr, $session_arr);
    }

}

?>