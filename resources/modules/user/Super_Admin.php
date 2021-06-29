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

    private ?string $secretpin;

    // -- Constructor
    public function __construct(Session $session, string $usertype, Time $createdon, string $adminid,
            string $adminname, string $email, string $password = NULL, ?string $secretpin = NULL) {

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

    // -- Initialise Patient
    public static function initialise_super_admin(array $super_admin_info): Super_Admin {

        # Session Object
        $session_obj = Session::intialise_session($super_admin_info['session']);

        # Time Object
        $time_obj = Time::initialise_time($super_admin_info['accountdetails']['createdon']);

        # Super Admin Object
        $super_admin = new Super_Admin($session_obj, $super_admin_info['accountdetails']['usertype'], $time_obj, $super_admin_info['profile']['adminid'],
                $super_admin_info['profile']['adminname'], $super_admin_info['credentials']['email']);

        return $super_admin;
    }

    //============================================
    //      Methods Accessing Firestore Database 
    //============================================
    // -- CHECK IF USER IS SUPER ADMIN
    public static function check_super_admin(array $email): bool {

        # -- Retrieve User Information Array -- #
        $db = new DbQuery();
        $admin_user = $db->fetch_one_document(Database::ACCOUNT_USER, $email);

        # -- Check If It Match The `Admin` User_Type -- #
        if ($admin_user['accountdetails']['usertype'] == User_Type::SUPER_ADMIN) :
            return true;
        endif;
        return false;
    }

    // -- RETRIEVE PATIENT DATA
    public static function retrieve_super_admin(array $login_arr): Super_Admin {

        $super_admin_data = Account_User::retrieve_account_data($login_arr);
        return self::initialise_super_admin($super_admin_data);
    }

}

?>