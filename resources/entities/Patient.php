<?php

/*
 * @author yanying (Tracy)
 */
/* Load Config File */
require_once '../resources/config.php';
require_once 'Appointment_Record.php';
require_once 'Account_User.php';
require_once ENUMS_PATH . '/User_Type.php';
require_once UTILS_PATH . '/DbQuery.php';
require_once UTILS_PATH . '/Database.php';
require_once UTILS_PATH . '/Time.php';
require_once UTILS_PATH . '/ArrayCreation.php';
require_once UTILS_PATH . '/Session.php';

class Patient extends Account_User {

    // Properties
    //private $appointment_records= array();  // Appointment_Record Object
    //private $medical_records = array();
    // Constructor
    public function __construct(Session $session, $firstname, $lastname, $gender, $dob,
            $contactnumber, $address, $usertype, Time $createdon, $email, $password = NULL) {

        parent::__construct($session, $firstname, $lastname, $gender, $dob, $contactnumber, $address,
                $usertype, $createdon, $email, $password);
    }

    // Use For Debugging/ Logging Purpose
    public function __toString() {
        return parent::__toString();
    }

    //============================================
    //      Methods Accessing Firestore Database 
    //============================================
    // -- Insert New Patient To Firestore (Upon Registration) -- //
    public static function create_patient(array $userDataArr): void {

        # Declaration Of Basic Information To Include To Account_User
        $account_user_arr = ArrayCreation::account_creation_array(User_Type::PATIENT);

        # Load Basic Account User Fields & Values To Array
        foreach ($account_user_arr as $field => $value) {
            $userDataArr[$field] = $value;
        }

        # Add Patient Data To Database
        $db = new DbQuery();
        $db->insert_data(Database::ACCOUNT_USER, $userDataArr);
    }

    // -- Edit Patient Information (Make Sure Patient Has To Provide Credentials For The Change) -- //
    public static function edit_patient_profile(array $credentials_arr, array $profile_changed_arr): bool {

        # Double Check If Patient Exist For The Given Credentials
        $user_data = parent::load_user_data($credentials_arr);
        if ($user_data !== null) {

            # Modify The Patient Profile Based On The Given Array
            $db = new DbQuery();
            return $db->modify_map_field(Database::ACCOUNT_USER, $credentials_arr, $profile_changed_arr);
        }
        return false;
    }

//    // Getters
//    function get_appointment_record() {
//        return $this->$appointment_record;
//    }
//    
//
//    // Setters
//    function set_appointment_record($appointmentRecord) {
//        $this->$appointment_record = new Appointment_Record(); // Params TBC
//    }
}

?>
