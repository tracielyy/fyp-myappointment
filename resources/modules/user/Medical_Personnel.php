<?php

/*
 * @author yanying (Tracie)
 */

/* Load Config File */
//require_once '../resources/config.php';

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
    public function __construct(Session $session, string $usertype, Time $createdon, string $nric, string $firstname, string $lastname,
            string $gender, string $dob, string $contactnumber, string $address,
            array $facilityids, string $specialisation, string $licensenumber,
            string $email, ?string $password = NULL) {

        # -- Parent Constructor -- #
        parent::__construct($session, $usertype, $createdon, $nric, $firstname, $lastname, $gender, $dob, $contactnumber, $address, $email, $password);

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
        $str .= nl2br('License Number: ' . $this->licensenumber . PHP_EOL . 'Facility IDs: ');

        # -- Counter Variables -- #
        $count = count($this->facilityids);
        $counter = 0;

        # -- Loop & Display Each Facility IDs -- #
        foreach ($this->facilityids as $facilityid) {

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
                $createdon, $personnel_info['profile']['nric'], $personnel_info['profile']['name']['firstname'], $personnel_info['profile']['name']['lastname'],
                $personnel_info['profile']['gender'], $personnel_info['profile']['dob'], $personnel_info['profile']['contactnumber'],
                $personnel_info['profile']['address'], $personnel_info['practitionerinfo']['facilityids'], $personnel_info['practitionerinfo']['specialisation'],
                $personnel_info['practitionerinfo']['licensenumber'], $personnel_info['credentials']['email']);

        return $personnel;
    }

    //============================================
    //      Methods Accessing Firestore Database 
    //============================================
    // -- CREATE MEDICAL PERSONNEL ACCOUNT
    public static function create_medical_personnel(array $medical_personnel_data) {

        # Create Default Fields
        $account_user_arr = ArrayCreation::account_creation_array(User_Type::MEDICAL_PERSONNEL);

        # Load Info To Data Container
        foreach ($account_user_arr as $field => $value) :
            $medical_personnel_data[$field] = $value;
        endforeach;

        # Add Medical Personnel Data To Database
        $db = new DbQuery();
        $db->get_db()->collection(Database::ACCOUNT_USER)
                ->document($medical_personnel_data['profile']['nric'])
                ->set($medical_personnel_data);
//        $added_ref = $db->get_db()->collection(Database::ACCOUNT_USER)->add($medical_personnel_data);
//        return $added_ref ? True : False;
    }

    // -- RETRIEVE ALL MEDICAL PERSONNEL
    public static function retrieve_all_practitioner(string $admin_email): array {

        # -- Email Array -- #
        $email['credentials'] = array(
            'email' => $admin_email
        );

        # -- Create Patient Object Array -- #
        $practitioner_arr = array();

        # -- Double Check If User Is Admin -- #
        if (self::check_admin($email)) :

            # -- Conditions -- #
            $condition['accountdetails'] = array('usertype' => User_Type::MEDICAL_PERSONNEL);

            # -- Ordered By -- #
            $orderedBy['profile.name'] = array('firstname', 'lastname');

            # -- Get All The Patient Ordered In Ascending -- #
            $db = new DbQuery();
            $practitioner_list = $db->get_filtered_documents_ordered(Database::ACCOUNT_USER, $condition, $orderedBy, true);

            # -- Loop & Placed Patient Object To Array -- #
            foreach ($practitioner_list as $practitioner):
                $practitioner_arr[] = self::intialise_medical_personnel($practitioner);
            endforeach;
        endif;

        return $practitioner_arr;
    }

    // -- RETRIEVE  MEDICAL PERSONNEL DATA
    public static function retrieve_medical_personnel(array $login_arr): Medical_Personnel {

        $medical_personnel_data = Account_User::retrieve_account_data($login_arr);
        return self::initialise_medical_personnel($medical_personnel_data);
    }

    // -- RETRIEVE MEDICAL PERSONNEL THAT BELONGS TO THE GIVEN FACILITY
    public static function retrieve_personnel_by_facility(string $facilityid): array {

        # Create Empty Array To Store Personnel
        $personnel_arr = array();

        $db = new DbQuery();
        $doc_arr = $db->get_db()->collection(Database::ACCOUNT_USER)
                ->where("accountdetails.usertype", "=", User_Type::MEDICAL_PERSONNEL)
                ->where("practitionerinfo.facilityids", "array-contains", $facilityid)
                ->documents();
        foreach ($doc_arr as $doc) {
            if ($doc->exists()) {
                $doc_data = $doc->data();
                $personnel_arr[$doc_data['practitionerinfo']['specialisation']][] = self:: initialise_medical_personnel($doc_data);
            }
        }

        return $personnel_arr;
    }

}

?>
