<?php

/*
 * @author yanying (Tracie)
 */



/* Load Config File */
require_once '../resources/config.php';
require_once APPT_MOD . '/Appointment_Record.php';
require_once USER_MOD . '/Account_User.php';
require_once AUTH_MOD . '/Session.php';
require_once TIME_MOD . '/Time.php';
require_once ENUMS_PATH . '/User_Type.php';

require_once UTIL_MOD . '/ArrayCreation.php';
require_once DB_MOD . '/DbQuery.php';
require_once DB_MOD . '/Database.php';
require_once ENUMS_PATH . '/Appointment_Type.php';

class Appointment_Slot {
    # Slot id for specialised appointments <e.g 1001>~<date>~<doctor-doc-id>

    // -- Properties
    private string $slotid;
    private Time $appointmentschedule;
    // Specialist: ONLY 1 Patient, Check Up & Dr Consult: max 10
    private array $patientlist;
    private ?bool $available;

    // -- Constructor
    public function __construct(string $slotid, Time $appointmentschedule, array $patientlist, bool $available = NULL) {
        $this->slotid = $slotid;
        $this->appointmentschedule = $appointmentschedule;
        $this->patientlist = $patientlist;
        $this->available = $available;
    }

    // -- Getters
    public function get_slotid(): string {
        return $this->slotid;
    }

    # -- Print For Individual Appointment Slots

    public function get_slot_description(): string {
        $date = Time::date_format_change($this->appointmentschedule->get_date(), Time::DATE_FORMAT_APPOINTMENT);
        $time = Time::to_12hours($this->appointmentschedule->get_time(), false);

        return $date . ", " . $time;
    }

    public function get_appointmentschedule(): Time {
        return $this->appointmentschedule;
    }

    public function get_patientlist(): array {
        return $this->patientlist;
    }

    public function get_doctorlist(): array {
        return $this->doctorlist;
    }

    // -- Settters
    public function set_slotid(string $slotid): void {
        $this->slotid = $slotid;
    }

    public function set_appointmentschedule(Time $appointmentschedule): void {
        $this->appointmentschedule = $appointmentschedule;
    }

    public function set_patientlist(array $patientlist): void {
        $this->patientlist = $patientlist;
    }

    public function set_doctorlist(array $doctorlist): void {
        $this->doctorlist = $doctorlist;
    }

    // -- Use For Debugging/ Logging Purpose -- //
    public function __toString(): string {
        $str = nl2br('Slotid: ' . $this->slotid . PHP_EOL . 'Schedule' . $this->appointmentschedule . PHP_EOL);

        # Loop & Display Patients For Allotted To The Time Slot
        $str .= nl2br("Patient List" . PHP_EOL);
        foreach ($this->patientlist as $patient):
            $str .= $patient . " ";
        endforeach;


        # Loop & Display Doctors Available At The Time Slot
        $str .= nl2br(PHP_EOL . "Doctor List" . PHP_EOL);
        foreach ($this->doctorlist as $doctor):
            $str .= $doctor . " ";
        endforeach;

        return $str;
    }

    // -- Initialise Appointment_Slot
    public static function initialise_appt_slot(array $appt_slot, string $date): Appointment_Slot {

        # Time
        $appt_schedule = new Time($date, $appt_slot['time']);
        $slot_obj = new Appointment_Slot($appt_slot['slotid'], $appt_schedule, $appt_slot['patientlist'], $appt_slot['available']);
        return $slot_obj;
    }

    // -- Retrieve The Slot Information By ID & APPOINTMENT TYPE 
    public static function retrieve_appt_slot_by_id(string $id, string $appt_type): Appointment_Slot {

        # Split The ID
        $id_data = explode("~", $id);

        $db = new DbQuery();

        # Slot id <e.g 1001>~<date>~<doctor-doc-id>
        switch ($appt_type):
            case Appointment_Type::CHECK_UP:
            case Appointment_Type::DOCTOR_CONSULTATION:
                # -- TBC -- #
                break;

            # -- (SPECIALIST CONSULTATION W DOC DOCUMENT ID)
            case Appointment_Type::SPECIALIST_CONSULTATION:
                $doc_path = Database::ACCOUNT_USER . "/" . $id_data[2] . "/" . Database::APPOINTMENT_SLOTS . "/" . $id_data[1] . "/"
                        . Database::SLOTS;
                $slot_data = $db->get_documentdata_by_id($doc_path, $id);
                return self::initialise_appt_slot($slot_data, $id_data[2]);

        endswitch;
    }

}

?>