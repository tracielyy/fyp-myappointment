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
    public function get_facilityids(): array {
        return $this->facilityids;
    }

    public function get_specialisation(): string {
        return $this->specialisation;
    }

    public function get_licensenumber(): string {
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
    public static function create_medical_personnel(array $medical_personnel_data): array {


        # Create Default Fields
        $account_user_arr = ArrayCreation::account_creation_array(User_Type::MEDICAL_PERSONNEL);

        # Load Info To Data Container
        foreach ($account_user_arr as $field => $value) :
            $medical_personnel_data[$field] = $value;
        endforeach;

        # Set Default Password 
        $sec = new Security();
        $default_pw = StringUtils::generate_token(12);
        $medical_personnel_data['credentials']['password'] = $sec->hash($default_pw);

        # Set NRIC
        if (isset($medical_personnel_data['profile']['nric'])):
            $temp = $medical_personnel_data['profile']['nric'];
            // Encrypt NRIC As Field
            $medical_personnel_data['profile']['nric'] = $sec->encrypt($temp);
            // Hash NRIC For Document ID
            $id = $sec->hash_256($temp);
        endif;

        # Add Medical Personnel Data To Database
        $db = new DbQuery();
        $db->get_db()->collection(Database::ACCOUNT_USER)
                ->document($id)
                ->set($medical_personnel_data);

        # Create Credentials Array
        $credentials = array('email' => $medical_personnel_data['credentials']['email'], 'password' => $default_pw);

        return $credentials;
    }

    // -- RETRIEVE MEDICAL PERSONNEL BY ID  
    public static function retrieve_practitioner_by_id(string $user_doc_id): ?Medical_Personnel {

        $db = new DbQuery();
        $practitioner_data = $db->fetch_document_by_id(Database::ACCOUNT_USER, $user_doc_id);
        return self::initialise_medical_personnel($practitioner_data);
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
    public static function retrieve_medical_personnel(string $user_email): Medical_Personnel {

        $medical_personnel_data = Account_User::retrieve_account_data($user_email);
        return self::initialise_medical_personnel($medical_personnel_data);
    }

    // -- RETRIEVE MEDICAL PERSONNEL THAT BELONGS TO THE GIVEN FACILITY (GENERAL NOT INCLUDED)
    public static function retrieve_personnel_by_facility_spec(string $facilityid, bool $include_gp = true): array {

        # Create Empty Array To Store Personnel
        $personnel_arr = array();

        $db = new DbQuery();
        if ($include_gp):
            $doc_arr = $db->get_db()->collection(Database::ACCOUNT_USER)
                            ->where("accountdetails.usertype", "=", User_Type::MEDICAL_PERSONNEL)
                            ->where("practitionerinfo.facilityids", "array-contains", $facilityid)->documents();
        else:
            $doc_arr = $db->get_db()->collection(Database::ACCOUNT_USER)
                            ->where("accountdetails.usertype", "=", User_Type::MEDICAL_PERSONNEL)
                            ->where("practitionerinfo.facilityids", "array-contains", $facilityid)
                            ->where("practitionerinfo.specialisation", "!=", "General")->documents();
        endif;

        foreach ($doc_arr as $doc) {
            if ($doc->exists()) {
                $doc_data = $doc->data();
                $personnel_arr[$doc_data['practitionerinfo']['specialisation']][] = self:: initialise_medical_personnel($doc_data);
            }
        }

        return $personnel_arr;
    }

    public static function retrieve_personnel_by_facility(string $facilityid): array {
        
        # Create Empty Array To Store Personnel 
        $personnel_arr = array();

        $db = new DbQuery();
        $doc_arr = $db->get_db()->collection(Database::ACCOUNT_USER)
                        ->orderBy('profile.name.firstname')->orderBy('profile.name.lastname')->orderBy('credentials.email')
                        ->where("accountdetails.usertype", "=", User_Type::MEDICAL_PERSONNEL)
                        ->where("practitionerinfo.facilityids", "array-contains", $facilityid)->documents();
        # Loop & Add To Container
        foreach ($doc_arr as $doc) {
            if ($doc->exists()) {
                $doc_data = $doc->data();
                $personnel_arr[] = self:: initialise_medical_personnel($doc_data);
            }
        }

        return $personnel_arr;
    }

    // -- RETRIEVE MEDICAL PERSONNEL THAT BELONGS TO THE GIVEN FACILITY
    public static function retrieve_personnel_by_facility_limit(string $facilityid, array $startAfter = null): array {

        # Create Empty Array To Store Personnel
        $personnel_arr = array();

        $db = new DbQuery();
        $doc_ref = $db->get_db()->collection(Database::ACCOUNT_USER)
                ->orderBy('profile.name.firstname')->orderBy('profile.name.lastname')->orderBy('credentials.email')
                ->where("accountdetails.usertype", "=", User_Type::MEDICAL_PERSONNEL)
                ->where("practitionerinfo.facilityids", "array-contains", $facilityid);

        if ($startAfter == null):
            # Beginning Query
            $doc_arr = $doc_ref->limit(2)->documents();
        else:
            # Consecutive Query
            $doc_arr = $doc_ref->startAfter($startAfter)->limit(2)->documents();
        endif;

        # Loop & Add To Container
        foreach ($doc_arr as $doc) {
            if ($doc->exists()) {
                $doc_data = $doc->data();
                $personnel_arr[] = self:: initialise_medical_personnel($doc_data);
            }
        }

        return $personnel_arr;
    }

    // -- Comparison Function 
    public static function cmp_obj(Medical_Personnel $a, Medical_Personnel $b) {
        $af = strtolower($a->get_firstname());
        $bf = strtolower($b->get_firstname());

        $al = strtolower($a->get_lastname());
        $bl = strtolower($b->get_lastname());

        if ($af == $bf) {
            if ($al == $bl) {
                return 0;
            }
            return ($al > $bl) ? +1 : -1;
        }
        return ($af > $bf) ? +1 : -1;
    }

    public static function delete_medical_personnel(string $id): void {
        $db = new DbQuery();
        $db->get_db()->collection(Database::ACCOUNT_USER)->document($id)->delete();
    }

}

?>
