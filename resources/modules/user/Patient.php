<?php

/*
 * @author yanying (Tracie)
 */



/* Load Config File */
require_once '../resources/config.php';

require_once TIME_MOD . '/Time.php';

require_once AUTH_MOD . '/Session.php';
require_once USER_MOD . '/Normal_User.php';
require_once APPT_MOD . '/RetrieveAppointment.php';

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
    
    public function set_appointmentrecords(array $appointmentrecords): void{
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
        $email['credentials']['email'] = $patient_info['credentials']['email'];

        $appointmentrecords = RetrieveAppointment::retrieve_all_appointments($email);
        $medicalrecords = array();

        # Patient Object
        $patient = new Patient($session_obj, $patient_info['accountdetails']['usertype'], $time_obj, $patient_info['profile']['name']['firstname'], $patient_info['profile']['name']['lastname'],
                $patient_info['profile']['gender'], $patient_info['profile']['dob'], $patient_info['profile']['contactnumber'], $patient_info['profile']['address'],
                $patientid, $appointmentrecords, $medicalrecords, $patient_info['accountdetails']['verification']['verified'], $patient_info['credentials']['email']);

        return $patient;
    }

    // Functions
    # - Book Appointment
    # - View Appointment
    # - Cancel Appointment
    # - Update Profile Details
    # - Password Reset
    # - Password Change
    # - View Bill History
    # - View Health Educational Materials
    # - View Health Tips
    # - Calendar Invites (Add To Calendar)
    # - View FAQs
}

?>
