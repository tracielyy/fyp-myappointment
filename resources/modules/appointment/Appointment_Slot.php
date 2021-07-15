<?php

/*
 * @author yanying (Tracie)
 */



/* Load Config File */
//require_once '../resources/config.php';
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
//    private string $facilityid;

    // Specialist: ONLY 1 Patient, Check Up & Dr Consult: max 10
    // -- Constructor
    public function __construct(string $slotid, Time $appointmentschedule) {
        $this->slotid = $slotid;
        $this->appointmentschedule = $appointmentschedule;
    }

    // -- Getters
    public function get_slotid(): string {
        return $this->slotid;
    }

    // -- Print For Individual Appointment Slots
    public function get_slot_description(): string {
        $date = Time::date_format_change($this->appointmentschedule->get_date(), Time::DATE_FORMAT_APPOINTMENT);
        $time = Time::to_12hours($this->appointmentschedule->get_time(), false);

        return $date . ", " . $time;
    }

    public function get_appointmentschedule(): Time {
        return $this->appointmentschedule;
    }

    // -- Settters
    public function set_slotid(string $slotid): void {
        $this->slotid = $slotid;
    }

    // ---------- ABSTRACT METHODS 
//    public abstract function insert_patient_to_slot(string $slotid, string $patient_doc_id);
//    public abstract function remove_patient_from_slot(string $slotid, string $patient_doc_id);

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
        $slot_obj = new Appointment_Slot($appt_slot['slotid'], $appt_schedule);
        return $slot_obj;
    }

    // ####################     Database Functions      ################### //

    // -- RETRIEVE APPOINTMENT SLOTS BY GIVEN DATE
    public static function retrieve_apptslots_by_date(string $facilityid, string $appointmenttype, string $date, int $max_patients = 20): array {

        # Create Empty Appointment Slots Array
        $appointment_slots = array();

        # Query For The Appointment Slots
        $doc_path = Database::MEDICAL_FACILITY . "/" . $facilityid . "/" . $appointmenttype . "/";
        $db = new DbQuery();
        $path = $doc_path . $date . "/Slots";
        $slot_list = $db->get_documents_by_path($path, false);

        # Loop & Add Slots
        foreach ($slot_list as $slots):

            # - Filtering Of Full Slots (Firestore Not Capable OF Array Count) - #
            if (count($slots['patients']) < $max_patients):

                $appointment_time = new Time($date, $slots['time']);
                $slot_obj = new Appointment_Slot($slots['slotid'], $appointment_time, $slots['patients'], $slots['doctors']);

                $appointment_slots[] = $slot_obj;
            endif;
        endforeach;

        return $appointment_slots;
    }



}

?>