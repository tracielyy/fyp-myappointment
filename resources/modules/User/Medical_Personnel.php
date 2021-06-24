<?php

/*
 * @author yanying (Tracie)
 */

/* Load Config File */
require_once '../resources/config.php';

require_once USER_MOD . '/Normal_User.php';
require_once TIME_MOD . '/Time.php';

// -- Medical Personnel (e.g. Doctor, Practioner)
class Medical_Personnel extends Normal_User {

    // Properties
    # Name  Of The Medical Facility (e.g. NUH) -- Multiple Places (e.g. mf001, mf002)
    private array $facilityids;

    # "General" or "Cardiology" etc
    private string $specialisation;

    # License Number
    private string $licensenumber;

    // -- Constructor -- //
    public function __construct(Session $session, string $usertype, Time $createdon, string $firstname, string $lastname,
            string $gender, string $dob, string $contactnumber, string $address,
            array $facilityids, string $specialisation, string $licensenumber,
            string $email, string $password = NULL) {

        # -- Parent Constructor -- #
        parent::__construct($session, $firstname, $lastname, $gender, $dob, $contactnumber, $address,
                $usertype, $createdon, $email, $password);

        # -- Medical_Personnel's Properties Assignment -- #
        $this->facilityids = $facilityids;
        $this->specialisation = $specialisation;
        $this->licensenumber = $licensenumber;
    }

    // -- Getters
    public function get_medical_facility() {
        return $this->medicalfacility;
    }

    public function get_license_number() {
        return $this->licensenumber;
    }

    // -- Setters
    // -- Use For Debugging/ Logging Purpose -- //
    public function __toString(): string {
        $str = parent::__toString();
        $str .= nl2br('License Number: ' . $this->licensenumber . PHP_EOL . 'Role Type: ' . $this->roletype .
                PHP_EOL . 'Facility IDs: ');

        # -- Counter Variables -- #
        $count = count($this->facilityids);
        $counter = 0;

        # -- Loop & Display Each Facility IDs -- #
        foreach ($facilityids as $facilityid) {

            # Increment
            $counter++;
            $str .= $facilityid;

            # If The Id Is Not The Last Element
            if ($counter != $count) {
                $str .= ",";
            }
        }
        return $str;
    }

    public static function initialise_medical_personnel(array $personnel_info): Medical_Personnel {
        
        # Session Object
        $session_obj = Session::intialise_session($personnel_info['session']);

        # Time Object
        $createdon = Time::initialise_time($personnel_info['accountdetails']['createdon']);

        # Medical Personnel Object
        $personnel = new Medical_Personnel($session_obj, $personnel_info['accountdetails']['usertype'], 
                $createdon, $personnel_info['profile']['name']['firstname'], $personnel_info['profile']['name']['lastname'], 
                $personnel_info['profile']['gender'], $personnel_info['profile']['dob'],$personnel_info['profile']['contactnumber'], 
                $personnel_info['profile']['address'], $personnel_info['practitionerinfo']['facilityids'], $personnel_info['practitionerinfo']['specialisation'],
                $personnel_info['practitionerinfo']['licensenumber'], $personnel_info['credentials']['email']);
        
        return $personnel;
    }

    //============================================
    //      Methods Accessing Firestore Database 
    //============================================
    // -- Get Medical Personnel (Individual) -- //
    public static function get_medical_personnel() {
        
    }

}

?>
