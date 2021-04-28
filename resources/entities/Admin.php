<?php
/* Load Config File */
require_once '../config.php';
require_once ENTITIES_PATH . '/Account_User.php';

class Admin extends Account_User{
    
    // Properties
    
    
    // Constructor
    function __construct($firstname = NULL, $lastname = NULL, $gender = NULL, $dob = NULL,
            $contactnumber = NULL, $address = NULL, $email = NULL, $password = NULL) {
        parent::__construct($firstname,$lastname, $gender, $dob, $contactnumber,$address, $email, $password );
    
    }
    
    
    // Getters
    
    
    
    // Setters
    
}


?>
