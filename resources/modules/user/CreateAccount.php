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
