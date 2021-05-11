<!-- 
    Developed By FYP-21-S2-24
-->
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
    // Add Medical Personnel
    public static function create_medical_personnel($admin, $medical_personnel): bool {

        if (self::check_admin($admin)) {
            // Create Medical Pesronnel
        }
    }

    public static function remove_medical_personnel($admin, $medical_personnel): bool {
        
        if (self::check_admin($admin)) {
            // Remove Medical Pesronnel
        }
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
