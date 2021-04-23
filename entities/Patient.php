<?php

require_once '../entities/Appointment_Record.php';

class Patient extends Account_User {

    // Properties
    protected const PATIENT = "Patient";

    //private $appointment_record = array();  // Appointment_Record Object
    // Constructor
    function __construct($firstname = NULL, $lastname = NULL, $gender = NULL, $dob = NULL,
            $contactnumber = NULL, $address = NULL, $email = NULL, $password = NULL) {
        parent::__construct($firstname, $lastname, $gender, $dob, $contactnumber, $address, $email, $password);
    }

    // Use For Debugging/ Logging Purpose
    public function __toString() {
        return parent::__toString();
    }

    //============================================
    //      Methods Accessing Firestore Database 
    //============================================
    // Insert New Patient To Firestore
    public static function add_patient(array $userDataArr) {
        $db = new Database();
        $userDataArr['session'] = array(
            "sessionid" => "",
            "isloggedin" => true
        );
        $userDataArr['usertype'] = self::PATIENT;
        $db->insert_data(parent::ACCOUNT_USER, $userDataArr);
    }

    // Retrieve Patients Medical Record (Per Doctor Visit?)
    public static function get_patient_medical_record() {
        
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
