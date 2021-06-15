<?php

/*
 * @author yanying (Tracie)
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
    # private $medical_note;
    # $patient_details (medical_note, blood_type, height, weight, primary_language)
    // Constructor
    public function __construct(Session $session, string $firstname, string $lastname, string $gender, string $dob,
            string $contactnumber, string $address, string $usertype, Time $createdon, string $email, string $password = NULL) {

        parent::__construct($session, $firstname, $lastname, $gender, $dob, $contactnumber, $address,
                $usertype, $createdon, $email, $password);
    }

    // Use For Debugging/ Logging Purpose
    public function __toString() {
        return parent::__toString();
    }

}

?>
