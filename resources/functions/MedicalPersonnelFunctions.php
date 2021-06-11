<?php

/*
 * @author yanying (Tracy)
 */
# -- Load Config File -- #
require_once '../resources/config.php';
require_once ENTITIES_PATH . '/Account_User.php';
require_once ENTITIES_PATH . '/Admin.php';
require_once ENTITIES_PATH . '/Medical_Personnel.php';
require_once ENUMS_PATH . '/User_Type.php';
require_once UTILS_PATH . '/DbQuery.php';
require_once FUNCTIONS_PATH . '/AccountUserFunctions.php';

class MedicalPersonnelFunctions {
    //============================================
    //      Medical Records
    //============================================
    
    // -- A Medical Record Of Diagnosis Tagged To An Appointment Record -- //
    public static function create_medical_record(): void {
        
    }
}
?>

