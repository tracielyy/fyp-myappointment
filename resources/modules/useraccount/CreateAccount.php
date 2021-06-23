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

class CreateAccount {

   
    
    private function __construct() {
        // -- Prevent Instantiation Of This Class
    }

    //  -- Check If The User Exist In The Database  -- //
    public static function check_user_exist(string $email): bool {

        # Email Array
        $emailArr ["credentials"] = array(
            'email' => $email
        );

        # Query For User With The Given Email
        $db = new DbQuery();
        $emails_found = $db->query_exact_match(Database::ACCOUNT_USER, $emailArr);

        # Check If There Are Any Value Returned
        if (($emails_found !== NULL)):
            return True;  // There is existing user
        endif;

        return False;
    }

    // -- CREATE PATIENT ACCOUNT
    public static function create_patient(array $userDataArr): void {

        # Declaration Of Basic Information To Include To Account_User
        $account_user_arr = ArrayCreation::account_creation_array(User_Type::PATIENT);

        # Load Basic Account User Fields & Values To Array
        foreach ($account_user_arr as $field => $value) :
            $userDataArr[$field] = $value;
        endforeach;

        # Add Patient Data To Database
        $db = new DbQuery();
        $db->insert_data(Database::ACCOUNT_USER, $userDataArr, true);
    }
    
    // -- CREATE MEDICAL PERSONNEL ACCOUNT
    
    

    // -- CREATE FACILITY ADMIN ACCOUNT
    
    
    
    
}
