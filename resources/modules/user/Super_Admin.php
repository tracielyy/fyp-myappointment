<?php

/*
 * @author yanying (Tracie)
 */
# -- Load Config File -- #
require_once '../resources/config.php';
require_once USER_MOD . '/Admin.php';
require_once USER_MOD . '/Account_User.php';
require_once ENUMS_PATH . '/User_Type.php';

// -- The Top Admin Privilege -- //
class Super_Admin extends Admin {

    private string $secretpin;

    // -- Constructor
    public function __construct(Session $session, string $usertype, Time $createdon, string $adminid,
            string $adminname, string $email, string $password = NULL, string $secretpin = NULL) {

        parent::__construct($session, $usertype, $createdon, $adminid, $adminname, $email, $password);
        $this->secretpin = $secretpin;
    }

    // -- Getters
    public function get_secretpin(): string {
        return $this->secretpin;
    }

    // Debugging: Logging
    public function __toString(): string {
        $str = parent::__toString();
        return $str;
    }

    //============================================
    //      Methods Accessing Firestore Database 
    //============================================
    // -- CHECK IF USER IS SUPER ADMIN
    public static function check_super_admin(array $email): bool {

        # -- Retrieve User Information Array -- #
        $db = new DbQuery();
        $admin_user = $db->select_exact_match(Database::ACCOUNT_USER, $email);

        # -- Check If It Match The `Admin` User_Type -- #
        if ($admin_user['accountdetails']['usertype'] == User_Type::SUPER_ADMIN) :
            return true;
        endif;
        return false;
    }

}

?>