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
    private array $facilityids;

    # Types (e.g. Doctor, Nurse)
    private string $roletype;

    # License Number
    private string $licensenumber;

    // -- Constructor -- //
    public function __construct(string $firstname, string $lastname, string $gender, string $dob,
            string $contactnumber, string $address, string $usertype, string $createdon,
            array $practionerinfo, string $email, ?string $password = NULL) {

        # -- Parent Constructor -- #
        parent::__construct($firstname, $lastname, $gender, $dob, $contactnumber, $address,
                $usertype, $createdon, $email, $password);

        # -- Medical_Personnel's Properties Assignment -- #
        $this->facilityids = $practionerinfo['facilityids']; 
        $this->roletype = $practionerinfo['roletype'];
        $this->licensenumber = $practionerinfo['licensenumber'];
            
        
    }

    // -- Getters
    public function get_medical_facility() {
        return $this->medicalfacility;
    }

    public function get_license_number() {
        return $this->licensenumber;
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
    public static function get_medical_personnel() {
        
    }

}
?>
