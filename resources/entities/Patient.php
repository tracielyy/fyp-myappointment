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

class Patient extends Account_User {

    // Properties
    protected const PATIENT = "Patient";

    //private $appointment_records= array();  // Appointment_Record Object
    //private $medical_records = array();
    // Constructor
    public function __construct($session, $firstname, $lastname, $gender, $dob,
            $contactnumber, $address, $usertype, $createdon, $email, $password = NULL) {

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
    // -- Insert New Patient To Firestore -- //
    public static function create_patient(array $userDataArr): void {

        # Declaration Of Basic Information To Include To Account_User
        $account_user_arr = parent::account_creation_array(User_Type::PATIENT);

        # Load Basic Account User Fields & Values To Array
        foreach ($account_user_arr as $field => $value) {
            $userDataArr[$field] = $value;
        }

        # Add Patient Data To Database
        $db = new DbQuery();
        $db->insert_data(Database::ACCOUNT_USER, $userDataArr);
    }

    // -- Edit Patient Information (Make Sure Patient Has To Provide Credentials For The Change) -- //
    public static function edit_patient_profile(array $credentials_arr, array $profile_changed_arr): void {
        # Double Check If Patient Exist
        $user_data = parent::load_user_data($credentials_arr);
        if ($user_data !== null){
            # TBC #
        }
        
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
