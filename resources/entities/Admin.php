<?php

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
    public static function create_medical_personnel() {
        
    }
    
    public static function remove_medical_personnel() {
        
    }
    
}

?>
