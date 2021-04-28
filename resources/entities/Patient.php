<?php

/* Load Config File */
require_once '../resources/config.php';
require_once 'Appointment_Record.php';
require_once 'Account_User.php';

class Patient extends Account_User {

    // Properties
    protected const PATIENT = "Patient";

    //private $appointment_record = array();  // Appointment_Record Object
    // Constructor
    public function __construct($firstname, $lastname, $gender, $dob,
            $contactnumber, $address, $usertype, $createdon, $email, $password = NULL) {

        parent::__construct($firstname, $lastname, $gender, $dob, $contactnumber, $address,
                $usertype, $createdon, $email, $password);
    }

    // Use For Debugging/ Logging Purpose
    public function __toString() {
        return parent::__toString();
    }

    //============================================
    //      Methods Accessing Firestore Database 
    //============================================
    // Insert New Patient To Firestore
    public static function create_patient(array $userDataArr) {
        $db = new Database();
        $userDataArr['session'] = array(
            "sessionid" => "",
            "isloggedin" => false
        );
        $userDataArr['usertype'] = self::PATIENT;
        $userDataArr['createdon'] = (string) date("d-m-Y");
        $db->insert_data(parent::ACCOUNT_USER, $userDataArr);
    }

    // Retrieve Patients Medical Record (Per Doctor Visit?)
    public static function get_patient_medical_record() {
        
    }
    
    // Retrieve Patient Appointment Record
    public static function get_patient_appointment_record() {
        
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
