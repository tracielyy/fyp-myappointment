<?php

/*
 * @author yanying (Tracie)
 */



/* Load Config File */
require_once '../resources/config.php';
require_once APPT_MOD . '/Appointment_Record.php';
//require_once APPT_MOD . '/Appointment_Slot.php';
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
    public static function initialise_special_slot(array $slot_data): Special_Slot {

        # Extract Date From Slot Id <e.g 1001>~<date>~<doctor-doc-id>
        $slot_date = explode("~", $slot_data['slotid'])[1];

        # Time
        $appt_schedule = new Time($slot_date, $slot_data['time']);
        $slot_obj = new Special_Slot($slot_data['slotid'], $appt_schedule, $slot_data['patient'], $slot_data['available']);
        return $slot_obj;
    }

    //============================================
    //      Methods Accessing Firestore Database 
    //============================================
    // -- ADD PATIENT TO SPECIAL SLOT (SEPCIALIST)
    public static function insert_patient_to_slot(string $slotid, string $patient_doc_id): bool {

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
    public static function remove_patient_from_slot(string $slotid) {
        # Split The Slot ID <e.g 1001>~<date>~<doctor-doc-id>
        $slotid_data = explode("~", $slotid);

        # Slot Path 
        $slot_path = Database::ACCOUNT_USER . "/" . $slotid_data[2] . "." . Database::APPOINTMENT_SLOTS . "/" . $slotid_data[1] . "/" . Database::SLOTS;
        $db = new DbQuery();
        $db->get_db()->collection($slot_path)
                ->document($slotid)->update([
            ['path' => 'patient', 'value' => ""]
        ]);
        return True;
    }

    // -- RETRIEVE APPOINTMENT SLOTS BY DATE
    public static function retrieve_free_slots_by_date(string $facilityid, string $doctor_email, string $date): array {

        $db = new DbQuery();

        # Create Empty Array (Store Appointment Slots)
        $slots_arr = array();

        # Get Doctor Document ID
        $doctor_doc_id = Account_User::retrieve_user_doc_id($doctor_email);

        # Path To Retrieve The Appointment Slot
        $slot_path = Database::ACCOUNT_USER . "/" . $doctor_doc_id . "/" . Database::APPOINTMENT_SLOTS . "/" . $date . "/" . Database::SLOTS;


        # Getting Array Of Document SnapShot
        $slot_snapshot_arr = $db->get_db()->collection($slot_path)
                ->where("facilityid","=", $facilityid)
                ->where("available", "=", True)
                ->where("patient", "=", "")
                ->documents();

        foreach ($slot_snapshot_arr as $slot_snapshot):
            if ($slot_snapshot->exists()):

                $slot_data = $slot_snapshot->data();

                # Add Special Slot To Array (Already Sorted In Ascending Slotid Order
                $slots_arr[] = self::initialise_special_slot($slot_data);

            endif;
        endforeach;

        # -- Return Array Of Appointment Slots
        return $slots_arr;
    }

    // -- RETRIEVE ONLY BOOKED SLOTS
    public static function retrieve_booked_slots_by_date(string $doctor_email, string $date): array {

        # Slot Container
        $slots_arr = array();

        # Get Doctor ID
        $doctor_doc_id = Account_User::retrieve_user_doc_id($doctor_email);

        # Path
        $path = Database:: ACCOUNT_USER . "/" . $doctor_doc_id . "/" . Database::APPOINTMENT_SLOTS . "/" . $date . "/" . Database::SLOTS;

        $db = new DbQuery();
        $doc_snapshot = $db->get_db()->collection($path)
                ->where("available", "=", True)
                ->where("patient", "!=", "")
                ->documents();

        # Loop & Add Slot To Array
        foreach ($doc_snapshot as $doc):
            if ($doc->exists()):
                $slot_data = $doc->data();
                $slots_arr[] = self::initialise_special_slot($slot_data);
            endif;
        endforeach;
        return $slots_arr;
    }

    // -- RETRIEVE APPOINTMENT SLOT (via slot id & appointment type)
    public static function retrieve_apptslot_by_id(string $id): Appointment_Slot {

        # Split The ID <e.g 1001>~<date>~<doctor-doc-id>
        $id_data = explode("~", $id);

        $db = new DbQuery();

        # Slot id <e.g 1001>~<date>~<doctor-doc-id>
        $doc_path = Database::ACCOUNT_USER . "/" . $id_data[2] . "/" . Database::APPOINTMENT_SLOTS . "/" . $id_data[1] . "/" . Database::SLOTS;
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

}
