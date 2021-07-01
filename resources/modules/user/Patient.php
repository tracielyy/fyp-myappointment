<?php

/*
 * @author yanying (Tracie)
 */



/* Load Config File */
require_once '../resources/config.php';

require_once TIME_MOD . '/Time.php';

require_once AUTH_MOD . '/Session.php';
require_once USER_MOD . '/Normal_User.php';
require_once USER_MOD . '/Super_Admin.php';
require_once APPT_MOD . '/Appointment_Record.php';

class Patient extends Normal_User {

    // Properties

    private string $patientid;
    private bool $verified; // Newly added -- To Make Sure Patient Is Verified Before Making Booking
    private array $appointmentrecords;
    private array $medicalrecords;

    // Constructor
    public function __construct(Session $session, string $usertype, Time $createdon, string $firstname, string $lastname,
            string $gender, string $dob, string $contactnumber, string $address,
            string $patientid, array $appointmentrecords, array $medicalrecords, bool $verified,
            string $email, string $password = NULL) {

        parent::__construct($session, $usertype, $createdon, $firstname, $lastname, $gender,
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
        $patient = new Patient($session_obj, $patient_info['accountdetails']['usertype'], $time_obj, $patient_info['profile']['name']['firstname'], $patient_info['profile']['name']['lastname'],
                $patient_info['profile']['gender'], $patient_info['profile']['dob'], $patient_info['profile']['contactnumber'], $patient_info['profile']['address'],
                $patientid, $appointmentrecords, $medicalrecords, $patient_info['accountdetails']['verification']['verified'], $patient_info['credentials']['email']);

        return $patient;
    }

    //============================================
    //      Methods Accessing Firestore Database 
    //============================================
    // -- CREATE PATIENT ACCOUNT
    public static function create_patient(array $patient_info): bool {

        # Declaration Of Basic Information To Include To Account_User
        $account_user_arr = ArrayCreation::account_creation_array(User_Type::PATIENT);

        # Load Basic Account User Fields & Values To Array
        foreach ($account_user_arr as $field => $value) :
            $patient_info[$field] = $value;
        endforeach;

        # Add Patient Data To Database
        $db = new DbQuery();
        return $db->insert_document(Database::ACCOUNT_USER, $patient_info, true);
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

    // -- BOOK AN APPOINTMENT
    public static function book_appointment(string $email, array $booking_info): bool {

        $condition['credentials'] = array('email' => $email);
        $db = new DbQuery();
        $user_doc_id = $db->get_document_id(Database::ACCOUNT_USER, $condition);

        # Validate Appointment
        if (Appointment_Record::validate_appt_booking($user_doc_id, $booking_info)):

            # Create User Appointment Record
            $user_appt_update = Appointment_Record::create_appointment_record($user_doc_id, $booking_info);

            # If User Appointment Record Created Successfully
            if ($user_appt_update):

                # Update To Add Patient's ID To Appointment's patient array
                $appt_slot_update = self::add_to_slot($user_doc_id, $booking_info);

                return ($user_appt_update && $appt_slot_update);
            else:
                return false;
            endif;

        endif;
        return false;
    }

    // -- add patient to normal or special slots
    private static function add_to_slot(string$user_doc_id, array $booking_info ) {
        switch ($booking_info['appointmenttype']):
            case Appointment_Type::CHECK_UP:
            case Appointment_Type::DOCTOR_CONSULTATION:
                $appt_slot_update = Normal_Slot::add_patient_to_slot($user_doc_id, $booking_info);
            case Appointment_Type::SPECIALIST_CONSULTATION:
                $appt_slot_update = Special_Slot::add_patient_to_slot($user_doc_id, $booking_info);
        endswitch;

        return $appt_slot_update;
    }

}

?>
