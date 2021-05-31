<?php
/*
 * @author yanying (Tracy)
 */
/* Load Config File */
require_once '../config.php';
require_once ENTITIES_PATH . '/Account_User.php';

class Admin extends Account_User {

    // Properties
    // Constructor
    public function __construct($firstname, $lastname, $gender, $dob,
            $contactnumber, $address, $usertype, $createdon, $email, $password = NULL) {

        parent::__construct($firstname, $lastname, $gender, $dob, $contactnumber, $address,
                $usertype, $createdon, $email, $password);
    }

    // Getters
    // Setters
    //============================================
    //      Methods Accessing Firestore Database 
    //============================================
    // Adding Medical Personnel To Database
    public static function create_medical_personnel($admin, $medical_personnel): bool {

        // Double Check If The One Performing The Action Is `ADMIN`
        if (self::check_admin($admin)) {
            // Create Medical Personnel
        }
        return false;
    }

    // Removing Medical Personnel From Database
    public static function remove_medical_personnel($admin, $medical_personnel): bool {
        
        // Double Check If The One Performing The Action Is `ADMIN`
        if (self::check_admin($admin)) {
            // Remove Medical Personnel
        }
        return false;
    }

    // Last Line Of Defense: Check If User Is Admin 
    public static function check_admin(array $infoArr): bool {
        $db = new Database();
        $admin_user = $db->query_exact_match(parent::ACCOUNT_USER, $infoArr);
        if ($admin_user['usertype'] == User_Type::ADMIN) {
            return true;
        }
        return false;
    }

}
?>
