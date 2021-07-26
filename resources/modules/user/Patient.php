<?php

/*
 * @author yanying (Tracie)
 */



/* Load Config File */
//require_once '../resources/config.php';

require_once TIME_MOD . '/Time.php';

require_once AUTH_MOD . '/Session.php';
require_once USER_MOD . '/Normal_User.php';

require_once APPT_MOD . '/Appointment_Record.php';
<<<<<<< HEAD
require_once SECURE_MOD. '/Security.php';
=======
require_once SECURE_MOD . '/Security.php';
>>>>>>> origin/traciedevelop

class Patient extends Normal_User {

    // Properties

    private string $patientid;
    private bool $verified; // Newly added -- To Make Sure Patient Is Verified Before Making Booking
    private array $appointmentrecords;
    private array $medicalrecords;

    // Constructor
    public function __construct(Session $session, string $usertype, Time $createdon, string $nric, string $firstname, string $lastname,
            string $gender, string $dob, string $contactnumber, string $address,
            string $patientid, array $appointmentrecords, array $medicalrecords, bool $verified,
            string $email, string $password = NULL) {

        parent::__construct($session, $usertype, $createdon, $nric, $firstname, $lastname, $gender,
                $dob, $contactnumber, $address, $email, $password);

        // -- Patient Information -- //
        $this->patientid = $patientid;
        $this->verified = $verified;
        $this->appointmentrecords = $appointmentrecords;
        $this->medicalrecords = $medicalrecords;
    }

    // -- Getters
    public function get_patientid(): string {
        return $this->patientid;
    }

    public function get_verified(): bool {
        return $this->verified;
    }

    public function get_appointmentrecords(): array {
        return $this->appointmentrecords;
    }

    public function get_medicalrecords(): array {
        return $this->medicalrecords;
    }

    // -- Setters
    public function set_verified(bool $verified): void {
        $this->verified = $verified;
    }

    public function set_appointmentrecords(array $appointmentrecords): void {
        $this->appointmentrecords = $appointmentrecords;
    }

    // Use For Debugging/ Logging Purpose
    public function __toString() {
        return parent::__toString();
    }

    // -- Generate Patient ID
    public static function generate_patientid(): string {
        
    }

    // -- Initialise Patient
    public static function initialise_patient(array $patient_info): Patient {

        # Session Object
        $session_obj = Session::intialise_session($patient_info['session']);

        # Time Object
        $time_obj = Time::initialise_time($patient_info['accountdetails']['createdon']);

        # Get Patient ID
        $patientid = "";

        # Credentials
        $email['credentials'] = array("email" => $patient_info['credentials']['email']);

        $appointmentrecords = Appointment_Record::retrieve_patient_all_appointments($email);
        $medicalrecords = array();

        # Patient Object
        $patient = new Patient($session_obj, $patient_info['accountdetails']['usertype'], $time_obj, $patient_info['profile']['nric'], $patient_info['profile']['name']['firstname'], $patient_info['profile']['name']['lastname'],
                $patient_info['profile']['gender'], $patient_info['profile']['dob'], $patient_info['profile']['contactnumber'], $patient_info['profile']['address'],
                $patientid, $appointmentrecords, $medicalrecords, $patient_info['accountdetails']['verification']['verified'], $patient_info['credentials']['email']);

        return $patient;
    }

    //============================================
    //      Methods Accessing Firestore Database 
    //============================================
    // -- CREATE PATIENT ACCOUNT
    public static function create_patient(array $patient_info): void {

        $sec = new Security();
        # Declaration Of Basic Information To Include To Account_User
        $account_user_arr = ArrayCreation::account_creation_array(User_Type::PATIENT);

        if (isset($patient_info['credentials']['password'])):
            $patient_info['credentials']['password'] = $sec->hash($patient_info['credentials']['password']);
        endif;

        //Create hash on the password (salt is already generated in the function)
        if (isset($patient_info['profile']['nric'])):
            $patient_info['profile']['nric'] = $sec->encrypt($patient_info['profile']['nric']);
        endif;
        
        # Load Basic Account User Fields & Values To Array
        foreach ($account_user_arr as $field => $value) :
            if  ($field == 'password')
            {
                //Create hash on the password (salt is already generated in the function)
                $patient_info[$field] = $sec->hash($value); 
            }elseif ($field == 'nric')
            {
                $patient_info[$field] = $sec->encrypt($value); 
            }
            else
            {
                $patient_info[$field] = $value;
            }
            
        endforeach;

        # Add Patient Data To Database
        $db = new DbQuery();
        $db->get_db()->collection(Database::ACCOUNT_USER)
                ->document($patient_info['profile']['nric'])
                ->set($patient_info);
    }

    // -- REMOVE PATIENT ACCOUNT
    public static function remove_patient(string $email): bool {

        # Make Sure Person To Be Removed Is Patient
        $usertype = Account_User::retrieve_user_type($email);
        if ($usertype == User_Type::PATIENT):
            $patient_doc_id = Account_User::retrieve_user_doc_id($email);

        endif;
    }

    // -- RETRIEVE PATIENT DATA
    public static function retrieve_patient(array $login_arr): Patient {

        $patient_data = Account_User::retrieve_account_data($login_arr);
        return self::initialise_patient($patient_data);
    }

    // -- RETRIEVE ALL PATIENTS (put patient in container)
    public static function retrieve_all_patients(string $admin_email): array {

        # -- Email Array -- #
        $email['credentials'] = array(
            'email' => $admin_email
        );

        # -- Create Patient Object Array -- #
        $patient_arr = array();

        # -- Double Check If User Is Admin -- #
        if (Super_Admin::check_super_admin($email)) :

            # -- Conditions -- #
            $condition['accountdetails'] = array('usertype' => User_Type::PATIENT);

            # -- Ordered By -- #
            $orderedBy['profile.name'] = array('firstname', 'lastname');

            # -- Get All The Patient Ordered In Ascending -- #
            $db = new DbQuery();
            $patient_list = $db->get_filtered_documents_ordered(Database::ACCOUNT_USER, $condition, $orderedBy, true);

            # -- Loop & Placed Patient Object To Array -- #
            foreach ($patient_list as $patient):
                $patient_arr[] = self::initialise_patient($patient);
            endforeach;

        endif;

        return $patient_arr;
    }

    // -- RETRIEVE PATIENT BY DOCUMENT ID (document id)
    public static function retrieve_patient_by_id(string $patient_id): ?Patient {
        $doc_path = Database::ACCOUNT_USER;

        # Getting Patient Information
        $db = new DbQuery();
        $patient_data = $db->fetch_document_by_id($doc_path, $patient_id);

        # If There Is Patient Data Returned
        if ($patient_data !== NULL):
            return self::initialise_patient($patient_data);
        endif;
        return NULL;
    }

}

?>