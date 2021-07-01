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

    // -- Initialise Appointment_Slot
    public static function initialise_appt_slot(array $appt_slot, string $date): Special_Slot {

        # Time
        $appt_schedule = new Time($date, $appt_slot['time']);
        $slot_obj = new Special_Slot($appt_slot['slotid'], $appt_schedule, $appt_slot['patient'], $appt_slot['available']);
        return $slot_obj;
    }

    //============================================
    //      Methods Accessing Firestore Database 
    //============================================
    // -- ADD PATIENT TO SPECIAL SLOT (SEPCIALIST)
    public function insert_patient_to_slot(string $slotid, string $patient_doc_id): bool {

        # Split The Slot ID <e.g 1001>~<date>~<doctor-doc-id>
        $slotid_data = explode("~", $slotid);

        # Slot Path 
        $sloth_path = Database::ACCOUNT_USER . "/" . $slotid_data[2] . "/" . Database::APPOINTMENT_SLOTS . "/" . $slotid_data[1] . "/" . Database::SLOTS;
        $db = new DbQuery();
        $db->get_db()->collection($sloth_path)
                ->document($slotid)->update([
            ['path' => 'patient', 'value' => $patient_doc_id]
        ]);
        return True;
    }

    // -- REMOVE PATIENT FROM SPECIAL SLOT (SPECIALIST)
    public function remove_patient_from_slot(string $slotid) {
        # Split The Slot ID <e.g 1001>~<date>~<doctor-doc-id>
        $slotid_data = explode("~", $slotid);

        # Slot Path 
        $sloth_path = Database::ACCOUNT_USER . "/" . $slotid_data[2] . "." . Database::APPOINTMENT_SLOTS . "/" . $slotid_data[1] . "/" . Database::SLOTS;
        $db = new DbQuery();
        $db->get_db()->collection($sloth_path)
                ->document($slotid)->update([
            ['path' => 'patient', 'value' => ""]
        ]);
        return True;
    }

    // -- RETRIEVE APPOINTMENT SLOT (via slot id & appointment type)
    public static function retrieve_apptslot_by_id(string $id): Appointment_Slot {

        # Split The ID
        $id_data = explode("~", $id);

        $db = new DbQuery();

        # Slot id <e.g 1001>~<date>~<doctor-doc-id>
        $doc_path = Database::ACCOUNT_USER . "/" . $id_data[2] . "/" . Database::APPOINTMENT_SLOTS . "/" . $id_data[1] . "/"
                . Database::SLOTS;
        $slot_data = $db->fetch_document_by_id($doc_path, $id);
        return Special_Slot::initialise_appt_slot($slot_data, $id_data[1]);
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

    // -- UPDATE APPOINTMENT SLOT WITH PATIENT DOCUMENT ID
    public static function add_patient_to_slot(string $user_doc_id, array $booking_info): bool {

        # Path For Normal Appointment Slots (Dr Consult & Check Up)
        $slots_doc_path = Database::MEDICAL_FACILITY . "/" . $booking_info['facilityid'] . "/" .
                $booking_info['appointmenttype'] . "/" . $booking_info['date'] . "/" . Database::SLOTS;

        # Update The Appointment Slot (Not Done)
        $db = new DbQuery();
        $db->get_db()->collection($slots_doc_path)->document($booking_info['slotid'])->update([
            ['path' => 'patientlist', 'value' => FieldValue::arrayUnion([$user_doc_id])]
        ]);
        return true;
    }

}
