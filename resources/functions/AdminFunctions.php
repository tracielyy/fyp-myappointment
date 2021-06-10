<?php

/*
 * @author yanying (Tracy)
 */
# -- Load Config File -- #
require_once '../resources/config.php';
require_once ENTITIES_PATH . '/Account_User.php';
require_once ENTITIES_PATH . '/Admin.php';
require_once ENTITIES_PATH . '/Medical_Personnel.php';
require_once ENUMS_PATH . '/User_Type.php';
require_once UTILS_PATH . '/DbQuery.php';

class AdminFunctions {

    //============================================
    //      Medical Personnel
    //============================================
    // -- Adding Medical Personnel To Database
    public static function create_medical_personnel(array $admin_email, $medical_personnel): bool {

        # -- Double Check If The One Performing The Action Is `ADMIN` -- #
        if (self::check_admin($admin_email)) :
        // Create Medical Personnel
        endif;
        return false;
    }

    // -- Removing Medical Personnel From Database
    public static function remove_medical_personnel(array $admin_email, $medical_personnel): bool {

        # -- Double Check If The One Performing The Action Is `ADMIN` -- #
        if (self::check_admin($admin_email)) :
        // Remove Medical Personnel
        endif;
        return false;
    }

    // -- Last Line Of Defense: Check If User Is Admin By Using The Email 
    public static function check_admin(array $email): bool {

        # -- Retrieve User Information Array -- #
        $db = new DbQuery();
        $admin_user = $db->query_exact_match(Database::ACCOUNT_USER, $email);

        # -- Check If It Match The `Admin` User_Type -- #
        if ($admin_user['accountdetails']['usertype'] == User_Type::ADMIN) :
            return true;
        endif;
        return false;
    }

    // -- Get All The Medical Personnel
    public static function display_all_practitioner(string $admin_email): array {

        # -- Email Array -- #
        $email['credentials'] = array(
            'email' => $admin_email
        );

        # -- Create Patient Object Array -- #
        $patient_arr = array();

        # -- Double Check If User Is Admin -- #
        if (self::check_admin($email)) :

            # -- Conditions -- #
            $condition['accountdetails'] = array('usertype' => User_Type::MEDICAL_PERSONNEL);

            # -- Ordered By -- #
            $orderedBy['profile.name'] = array('firstname', 'lastname');

            # -- Get All The Patient Ordered In Ascending -- #
            $db = new DbQuery();
            $patient_list = $db->get_filtered_documents_ordered(Database::ACCOUNT_USER, $condition, $orderedBy, true);

            # -- Loop & Placed Patient Object To Array -- #
            if (count($patient_list) > 0) :
                $patient_arr = AdminFunctions::initialise_practitioner_arr($patient_list);
            endif;
        endif;

        return $patient_arr;
    }

    // -- Initialise The Medical_Personnel Object In Array -- //
    private static function initialise_practitioner_arr(array $practitioner_list): array {

        # -- Create Array Container --- #
        $practitioner_arr = array();

        # -- Loop & Store In The Container -- #
        foreach ($practitioner_list as $practitioner):

            # -- Session -- #
            $session = $practitioner['session'];
            $session_obj = new Session($session['isloggedin'], $session['sessionid'], $session['token'], $session['ipaddress']);

            # -- Created On (Time) -- #
            $createdon = $practitioner['accountdetails']['createdon'];
            $createdon_obj = new Time($createdon['date'], $createdon['time']);

            # -- Practitioner (Medical_Personnel)  -- #
            $profile = $practitioner['profile'];
            $practitioner_obj = new Medical_Personnel($session_obj, $profile['name']['firstname'], $profile['name']['lastname'], $profile['gender'], $profile['dob'],
                    $profile['contactnumber'], $profile['address'], $practitioner['accountdetails']['usertype'], $createdon_obj,
                    $practitioner['practionerinfo'], $practitioner['credentials']['email']);

            # -- Add To Practioner Object Array -- #
            $practitioner_arr[] = $practitioner_obj;

        endforeach;
        return $practitioner_arr;
    }

    //============================================
    //      Patient
    //============================================
    // -- Get All The Patients
    public static function display_all_patients(string $admin_email): array {

        # -- Email Array -- #
        $email['credentials'] = array(
            'email' => $admin_email
        );

        # -- Create Patient Object Array -- #
        $patient_arr = array();

        # -- Double Check If User Is Admin -- #
        if (self::check_admin($email)) :

            # -- Conditions -- #
            $condition['accountdetails'] = array('usertype' => User_Type::PATIENT);

            # -- Ordered By -- #
            $orderedBy['profile.name'] = array('firstname', 'lastname');

            # -- Get All The Patient Ordered In Ascending -- #
            $db = new DbQuery();
            $patient_list = $db->get_filtered_documents_ordered(Database::ACCOUNT_USER, $condition, $orderedBy, true);

            # -- Loop & Placed Patient Object To Array -- #
            if (count($patient_list) > 0) :
                echo "omg";
                $patient_arr = AdminFunctions::initialise_patient_arr($patient_list);
            endif;
        endif;

        return $patient_arr;
    }

    // -- Initialise The Patient Object In Array -- //
    private static function initialise_patient_arr(array $patient_list): array {

        # -- Create Array Container --- #
        $patient_arr = array();

        # -- Loop & Store In The Container -- #
        foreach ($patient_list as $patient) :

            # -- Session -- #
            $session = $patient['session'];
            $session_obj = new Session($session['isloggedin'], $session['sessionid'], $session['token'], $session['ipaddress']);

            # -- Time -- #
            $createdon = $patient['accountdetails']['createdon'];
            $createdon_obj = new Time($createdon['date'], $createdon['time']);

            # -- Patient  -- #
            $profile = $patient['profile'];
            $patient_obj = new Patient($session_obj, $profile['name']['firstname'], $profile['name']['lastname'], $profile['gender'], $profile['dob'],
                    $profile['contactnumber'], $profile['address'], $patient['accountdetails']['usertype'], $createdon_obj, $patient['credentials']['email']);

            # -- Add To Patient Object Array -- #
            $patient_arr[] = $patient_obj;

        endforeach;
        return $patient_arr;
    }

    //============================================
    //      Medical Facility
    //============================================
    // -- Create Medical_Facility -- //
    public static function create_medical_facility(array $facilitydata_arr) {

        # -- Medical Facility's ID -- #
        $current_id = "";

        # -- Facility ID (Increment From The Last ID -- #
        $last_id = self::get_last_medical_id();

        # -- Check If Database Have Any Facility ID (Any Medical_Facility Record) -- #
        if ($last_id !== null) {
            $current_id = ++$last_id;
        } else {
            $current_id = self::FACILITY_ID;
        }

        # -- Set Facility's ID -- #
        $facilitydata_arr['facilityid'] = $current_id;

        # -- Store The Facility To Database -- #
        $db = new DbQuery();
        $db->insert_data(Database::MEDICAL_FACILITY, $facilitydata_arr);
    }

    // -- Check If There Is Any Medical ID -- //
    private static function get_last_medical_id(): ?string {

        # -- Query For Last Facility ID -- #
        $db = new DbQuery();
        $last_id_facilty = $db->get_document_ordered(Database::MEDICAL_FACILITY, 'facilityid', false);
        $last_id = $last_id_facilty['facilityid'];
        return $last_id;
    }

    // -- When User Request To Display All Medical Facilities -- //
    public static function display_all_facilities(): array {

        # -- Create An Array To Store Medical_Facility Objects -- #
        $mf_arr = array();

        # -- Query For Facility -- #
        $db = new DbQuery();
        $facility_list = $db->get_all_documents_ordered(Database::MEDICAL_FACILITY, 'facilityid');
        if (!empty($facility_list)) :

            # -- Loop & Placed Facility To Array -- #
            foreach ($facility_list as $facility) :
                $facility_object = new Medical_Facility($facility['facilityname'], $facility['address'],
                        $facility['contactnumber'], $facility['operatinghours'], $facility['facilityid']);
                $mf_arr[] = $facility_object;
            endforeach;

            return $mf_arr;
        endif;
    }

// -- When User Request To Display Facilities By Certain Location -- //
    public static function display_facilities_by_location() {
        
    }

// -- Admin To Change practitionerinfo -- //
}

?>