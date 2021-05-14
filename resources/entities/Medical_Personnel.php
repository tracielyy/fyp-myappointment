<!-- 
    Developed By FYP-21-S2-24
-->
<?php
/*
 * @author yanying (Tracy)
 */

/*
 *  Medical Personnel (e.g. Doctor, Practioner)
 */

class Medical_Personnel extends Account_User {

    // Properties
    # Name  Of The Medical Facility (e.g. NUH) -- Multiple Places (e.g. mf001, mf002)
    private array $facility_ids;

    # Types (e.g. Doctor, Nurse)
    private string $roletype;
    
    # License Number
    private string $license_number;

    // -- Constructor
    public function __construct($firstname, $lastname, $gender, $dob,
            $contactnumber, $address, $usertype, $createdon, $email, $password = NULL) {

        parent::__construct($firstname, $lastname, $gender, $dob, $contactnumber, $address,
                $usertype, $createdon, $email, $password);
    }

    // -- Getters
    public function get_medical_facility() {
        return $this->medical_facility;
    }

    public function get_license_number() {
        return $this->license_number;
    }

    // -- Setters
    // Use For Debugging/ Logging Purpose
    public function __toString(): string {
        return parent::__toString();
    }


    //============================================
    //      Methods Accessing Firestore Database 
    //============================================
    // -- Get Medical Personnel (Individual) -- //
    public static function get_medical_personnel(){

    }


}
?>
