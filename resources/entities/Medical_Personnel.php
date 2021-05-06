<?php

class Medical_Personnel extends Account_User {

    // Properties
    private string $medical_facility; // Name of the medical facility
    private string $license_number; // License Number

    // Constructor
    public function __construct($firstname, $lastname, $gender, $dob,
            $contactnumber, $address, $usertype, $createdon, $email, $password = NULL) {

        parent::__construct($firstname, $lastname, $gender, $dob, $contactnumber, $address,
                $usertype, $createdon, $email, $password);
    }

    // Getters
    public function get_medical_facility() {
        return $this->medical_facility;
    }

    public function get_license_number() {
        return $this->license_number;
    }

    // Setters
}

?>
