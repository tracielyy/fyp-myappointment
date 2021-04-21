<?php

require_once '../entities/Appointment_Record';

class Patient extends Account_User{
    
    // Properties
    //private $appointment_record = array();  // Appointment_Record Object
    
    // Constructor
    function __construct($firstname = NULL, $lastname = NULL, $gender = NULL, $dob = NULL,
            $contactnumber = NULL, $address = NULL, $email = NULL, $password = NULL) {
        parent::__construct($firstname,$lastname, $gender, $dob, $contactnumber,$address, $email, $password );
    
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
