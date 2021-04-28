<?php

class Medical_Personnel extends Account_User {

    // Properties
    private string $medical_facility; // Name of the medical facility
    
    
    // Constructor
    public function __construct($firstname, $lastname, $gender, $dob,
            $contactnumber, $address, $usertype, $createdon, $email, $password = NULL) {

        parent::__construct($firstname, $lastname, $gender, $dob, $contactnumber, $address,
                $usertype, $createdon, $email, $password);
    }

    // Getters
    // Setters
}

?>
