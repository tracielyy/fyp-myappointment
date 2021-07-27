<?php

/*
 * @author yanying (Tracie)
 */
# -- Load Config File -- #
//require_once '../resources/config.php';
require_once USER_MOD . '/Admin.php';
require_once USER_MOD . '/Account_User.php';
require_once ENUMS_PATH . '/User_Type.php';

// -- Admin That Belongs To Certain Facility That Oversee Their Operations -- //
class Facility_Admin extends Admin {

    // Properties
    private Medical_Facility $facility;

    // -- Constructor
    public function __construct(Session $session, string $usertype, Time $createdon, string $adminid, string $adminname,
            Medical_Facility $facility, string $email, ?string $password = NULL) {

        parent::__construct($session, $usertype, $createdon, $adminid, $adminname, $email, $password);

        $this->facility = $facility;
    }

    // Getters
    public function get_facility(): Medical_Facility {
        return $this->facility;
    }

    // Use For Debugging/ Logging Purpose
    public function __toString(): string {
        $str = nl2br(PHP_EOL . "Facility: " . $this->facility . PHP_EOL);
        return $str;
    }

    // -- Initialise Facility Admin
    public static function initialise_facility_admin(array $facility_admin_info): Facility_Admin {

        # Session Object
        $session_obj = Session::intialise_session($facility_admin_info['session']);

        # Time Object
        $time_obj = Time::initialise_time($facility_admin_info['accountdetails']['createdon']);

        # Facility Object
        $facility_obj = Medical_Facility::retrieve_facility_by_id($facility_admin_info['profile']['facilityid']);

        # Facility Admin Object
        $facility_admin = new Facility_Admin($session_obj, $facility_admin_info['accountdetails']['usertype'], $time_obj, $facility_admin_info['profile']['adminid'],
                $facility_admin_info['profile']['adminname'], $facility_obj, $facility_admin_info['credentials']['email']);

        return $facility_admin;
    }

    //============================================
    //      Methods Accessing Firestore Database 
    //============================================
    // -- CREATE FACILITY ADMIN ACCOUNT
    public static function create_facility_admin(array $fadmin_info): ?Facility_Admin {

        # Basic Account Information To Be Added
        $account_user_arr = ArrayCreation::account_creation_array(User_Type::FACIILITY_ADMIN);

        # Load Basic Account User Fields & Values To Array
        foreach ($account_user_arr as $field => $value) :
            $fadmin_info[$field] = $value;
        endforeach;

        # Add Patient Data To Database (use auto-id)
        $db = new DbQuery();
        $doc_ref = $db->get_db()->collection(Database::ACCOUNT_USER)->newDocument();
        
        # Add The Auto Id To One Of The Field
        $fadmin_info['profile']['adminid'] = $doc_ref->id();
        
        return $doc_ref->set($fadmin_info);
    }

    // -- RETRIEVE  FACILITY ADMIN DATA
    public static function retrieve_facility_admin(string $user_email): Facility_Admin {

        $facility_admin_data = Account_User::retrieve_account_data($user_email);
        return self::initialise_facility_admin($facility_admin_data);
    }

}

?>
