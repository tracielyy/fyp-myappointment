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

class Normal_Slot extends Appointment_Slot {

    private array $patientlist;
    private array $doctorlist;

    public function __construct(string $slotid, Time $appointmentschedule, array $patientlist, array $doctorlist) {
        parent::__construct($slotid, $appointmentschedule);

        $this->patientlist = $patientlist;
        $this->doctorlist = doctorlist;
    }

    // -- Getters
    public function get_patientlist(): array {
        return $this->patientlist;
    }

    public function get_doctorlist(): array {
        return $this->doctorlist;
    }

    // -- Setters
    // ####################     Database Functions      ################### //
    // -- ADD PATIENT TO NORMAL SLOT (DR CONSULT, CHECKUP)
    public function insert_patient_to_slot(string $slotid, string $patient_doc_id) {
        # Slot id <e.g 1001>~<date>~<e.g. drconsult, checkup>
    }

    // -- REMOVE PATIENT FROM NORMAL SLOT (DR CONSULT, CHECKUP)
    public function remove_patient_from_slot(string $slotid, string $patient_doc_id) {
        
    }

    // -- RETRIEVE APPOINTMENT SLOT (via slot id & appointment type)
    public static function retrieve_apptslot_by_id(string $id, string $appt_type): Appointment_Slot {

        # Split The ID
        $id_data = explode("~", $id);

        $db = new DbQuery();

        # Slot id <e.g 1001>~<date>~<doctor-doc-id>
        switch ($appt_type):
            case Appointment_Type::CHECK_UP:
            case Appointment_Type::DOCTOR_CONSULTATION:
                # -- TBC -- #
                break;

        endswitch;
    }

    public static function generate_slot_id(string $facilityid, string $appointmenttype, string $date) {
        # Order By Facility ID
        $orderBy = array('slotid');

        # Find The Last ID & Increment
        $db = new DbQuery();
        $doc_path = Database::MEDICAL_FACILITY . "/" . $facilityid . "/" . $appointmenttype . "/" . $date . "/" . Database::SLOTS;
        $last_id_slot = $db->get_documentid_ordered($doc_path, $orderBy, false);

        # If There Is Any Present ID In Database
        if ($last_id_slot != null) :
            echo "FOund in db";
            $id_data = explode("~", $last_id_slot);
            $number = ++$id_data[0];
            return $number . "~" . $id_data[1] . "~" . $id_data[2];

        # No ID Present In Database
        else:
            echo "new id";
            return "1001~" . $date . "~" . $appointmenttype;
        endif;
    }

    public static function check_slot_id(string $facilityid, string $slotid) {
        
    }

    // -- PATIENT COUNT OF ALL APPOINTMENT TYPES BY FACILITY
    public static function patient_count_per_date(string $facilityid, string $date): int {
        $patient_counter = 0;

        $db = new DbQuery();

        # -- Check Up
        $checkup_path = Database::MEDICAL_FACILITY . "/" . $facilityid . "/" . Appointment_Type::CHECK_UP . "/" . $date . "/" . Database::SLOTS;
        $checkup_slot_list = $db->get_all_documents($checkup_path);

        foreach ($checkup_slot_list as $slot):
            $patient_counter += count($slot['patientlist']);
        endforeach;

        # -- Doctor Consultation
        $drconsult_path = Database::MEDICAL_FACILITY . "/" . $facilityid . "/" . Appointment_Type::DOCTOR_CONSULTATION . "/" . $date . "/" . Database::SLOTS;
        $drconsult_slot_list = $db->get_all_documents($drconsult_path);

        foreach ($drconsult_slot_list as $slot):
            $patient_counter += count($slot['patientlist']);
        endforeach;
        
        return $patient_counter;
    }

}

?>