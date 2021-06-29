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

class Special_Slot extends Appointment_Slot {

    private string $patient;
    private bool $available;

    public function __construct(string $slotid, Time $appointmentschedule, string $patient, bool $available) {
        parent::__construct($slotid, $appointmentschedule);
        $this->patient = $patient;
        $this->available = $available;
    }

    // -- Getter
    public function get_patient(): string {
        return $this->patient;
    }

    public function get_available(): bool {
        return $this->available;
    }

    // -- ADD PATIENT TO SPECIAL SLOT (SEPCIALIST)
    public function insert_patient_to_slot(string $slotid, string $patient_doc_id) {
        
    }

    // -- REMOVE PATIENT FROM SPECIAL SLOT (SPECIALIST)
    public function remove_patient_from_slot(string $slotid, string $patient_doc_id) {
        
    }

    // -- PATIENT COUNT OF ALL APPOINTMENT TYPES BY FACILITY
    public static function patient_count_per_date(string $facilityid, string $date): int {

        # Initialise Counter As Zero
        $patient_counter = 0;

        # Specialist Consultation (Filter Doctors)
        $db = new DbQuery();
        $mydb = $db->get_db();
        $query = $mydb->collection(Database::ACCOUNT_USER)
                ->where("accountdetails.usertype", "=", User_Type::MEDICAL_PERSONNEL)
                ->where("practionerinfo.specialisation", "!=", "General")
                ->where("practionerinfo.facilityids", "array-contains", $facilityid);

        # Container With All Queried Document ID
        $doc_id_arr = $db->retrieve_doc_id_arr($query);

        foreach ($doc_id_arr as $doc_id):
            $specialist_path = Database::ACCOUNT_USER . "/" . $doc_id . "/" . Database::APPOINTMENT_SLOTS . "/" . $date . "/" . Database::SLOTS;
            $all_slot_list[] = $db->get_all_documents($specialist_path);
        endforeach;

        foreach ($all_slot_list as $slot_list):
            foreach ($slot_list as $slot):
                if ($slot['patient'] != ""):
                    ++$patient_counter;
                endif;
            endforeach;
        endforeach;

        return $patient_counter;
    }

}
