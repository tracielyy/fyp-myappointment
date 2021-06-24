<?php

/*
 * @author yanying (Tracie)
 */


/* Load Config File */
require_once '../resources/config.php';

require_once AUTH_MOD . '/Session.php';

require_once DB_MOD . '/DbQuery.php';
require_once DB_MOD . '/Database.php';

require_once USER_MOD . '/Patient.php';

require_once ENUMS_PATH . '/User_Type.php';

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
    public static function authenticate_user(array $credentialArr): bool {

        # Credentials
        $credentials = array(
            "credentials" => $credentialArr
        );

        # Query For User Using Given Credentials
        $db = new DbQuery();
        $user_data = $db->select_exact_match(Database::ACCOUNT_USER, $credentials);

        # Check If There Are Any User Returned From The Query
        if ($user_data != NULL):
            return True;
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
