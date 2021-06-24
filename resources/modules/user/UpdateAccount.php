<?php

/*
 * @author yanying (Tracie)
 */

/* Load Config File */
require_once '../resources/config.php';

require_once USER_MOD . '/Account_User.php';
require_once DB_MOD . '/DbQuery.php';
require_once DB_MOD . '/Database.php';
require_once ENUMS_PATH . '/User_Type.php';
require_once UTIL_MOD . '/ArrayCreation.php';

class UpdateAccount {

    private function __construct() {
        // -- Prevent Instantiation Of This Class
    }
    
    // -- CHANGE EMAIL
    
    // -- UPDATE  BASIC PROFILE
    
    
    // -- PASSWORD RESET (VALID FOR 24 HOURS) 
    public static function request_password_reset(string $email, string $token) {

        # Email Array
        $emailArr ["credentials"] = array(
            'email' => $email
        );

        # Get Fresh Set Of Password Reset Array For New Password Reset
        $passwordreset_arr = ArrayCreation::fresh_passwordreset_array($token);

        # Update The Array To Database
        $db = new DbQuery();
        $db->update_field(Database::ACCOUNT_USER, $emailArr, $passwordreset_arr);
    }
    
    // -- PASSWORD CHANGE

}

?>
