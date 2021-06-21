<?php

/*
 * @author yanying (Tracie)
 */
/* Load Config File */
require_once '../resources/config.php';
require_once 'Appointment_Record.php';
require_once 'Normal_User.php';
require_once 'Time.php';
require_once 'Session.php';

require_once ENUMS_PATH . '/User_Type.php';
require_once UTILS_PATH . '/DbQuery.php';
require_once UTILS_PATH . '/Database.php';

require_once UTILS_PATH . '/ArrayCreation.php';


class Patient extends Normal_User {

    // Properties

    private string $patientid;
    private bool $verified; // Newly added
    private array $appointmentrecords;
    private array $medicalrecords;

    // Constructor
    public function __construct(Session $session, string $usertype, Time $createdon, string $firstname, string $lastname,
            string $gender, string $dob, string $contactnumber, string $address, 
            string $patientid, array $appointmentrecords, array $medicalrecords,
            string $email, string $password = NULL) {

        parent::__construct($session, $usertype, $createdon, $firstname, $lastname, $gender,
                $dob, $contactnumber, $address, $email, $password);

        // -- Patient Information -- //
        $this->patientid = $patientid;
        $this->appointmentrecords = $appointmentrecords;
        $this->medicalrecords = $medicalrecords;
    }

    // -- Getters
    public function get_patientid(): string {
        return $this->patientid;
    }

    public function get_appointmentrecords(): array {
        return $this->appointmentrecords;
    }

    public function get_medicalrecords(): array {
        return $this->medicalrecords;
    }

    // Use For Debugging/ Logging Purpose
    public function __toString() {
        return parent::__toString();
    }

}

?>
