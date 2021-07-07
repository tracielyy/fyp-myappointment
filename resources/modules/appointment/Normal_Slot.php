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

use Google\Cloud\Firestore\FieldValue;

class Normal_Slot extends Appointment_Slot {

    private array $patientlist;
    private array $doctorlist;

    public function __construct(string $slotid, Time $appointmentschedule, array $patientlist, array $doctorlist) {
        parent::__construct($slotid, $appointmentschedule);

        $this->patientlist = $patientlist;
        $this->doctorlist = $doctorlist;
    }

    // -- Getters
    public function get_patientlist(): array {
        return $this->patientlist;
    }

    public function get_doctorlist(): array {
        return $this->doctorlist;
    }

    // -- Setters
    // -- Initialise Appointment_Slot
    public static function initialise_normal_slot(array $slot_data): Normal_Slot {

        # Extract Date From Slot Id <e.g 1001>~<date>~<appointmenttype>
        $slot_date = explode("~", $slot_data['slotid'])[1];

        # Time
        $appt_schedule = new Time($slot_date, $slot_data['time']);
        $slot_obj = new Normal_Slot($slot_data['slotid'], $appt_schedule, $slot_data['patientlist'], $slot_data['doctorlist']);
        return $slot_obj;
    }

    // ####################     Database Functions      ################### //
    // -- ADD PATIENT TO NORMAL SLOT (DR CONSULT, CHECKUP)
    public static function insert_patient_to_slot(string $slotid, string $patient_doc_id, string $facilityid): void {

        # Split The Slot ID <e.g 1001>~<date>~<appointmenttype>
        $slotid_data = explode("~", $slotid);

        # Slot Path 
        $slot_path = Database::MEDICAL_FACILITY . "/" . $facilityid . "/" . $slotid_data[2] . "/" . $slotid_data[1] . "/" . Database::SLOTS;
        $db = new DbQuery();
        $db->get_db()->collection($slot_path)
                ->document($slotid)->update([
            ['path' => 'patientlist', 'value' => FieldValue::arrayUnion([$patient_doc_id])]
        ]);
    }

    // -- REMOVE PATIENT FROM SLOT WHEN CANCELLING APPOINTMENT
    public static function remove_patient_from_slot(string $slotid, string $facilityid, string $patient_doc_id): bool {

        # Split The Slot ID <e.g 1001>~<date>~<appointmenttype>
        $slotid_data = explode("~", $slotid);

        # Slot Path 
        $slot_path = Database::MEDICAL_FACILITY . "/" . $facilityid . "/" . $slotid_data[2] . "/" . $slotid_data[1] . "/" . Database::SLOTS;
        $db = new DbQuery();
        $db->get_db()->collection($slot_path)
                ->document($slotid)->update([
            ['path' => 'patientlist', 'value' => FieldValue::arrayRemove([$patient_doc_id])]
        ]);
        return True;
    }

    // -- RETRIEVE APPOINTMENT SLOTS BY DATE
    public static function retrieve_free_slots_by_date(string $facilityid, string $appointmenttype, string $date): array {

        # Create Empty Array (Store Appointment Slots)
        $slots_arr = array();
        $slot_list = array();

        # Path To Retrieve The Appointment Slot
        $slot_path = Database::MEDICAL_FACILITY . "/" . $facilityid . "/" . $appointmenttype . "/" . $date . "/" . Database::SLOTS;
        $db = new DbQuery();

        # Getting Array Of Document SnapShot (Get Slots With At Least ONE DOCTOR
        $slot_snapshot_arr = $db->get_db()->collection($slot_path)
                ->where("doctorlist", "!=", [])
                ->documents();

        # Loop & Check If Slot Is Free
        foreach ($slot_snapshot_arr as $slot_snapshot):
            if ($slot_snapshot->exists()):

                # Extract Data From Snapshot
                $slot_data = $slot_snapshot->data();

                # More Filtering (Make Sure There Is Enough Doctor For Patients) --> 1:1
                self::filter_free_slots($slots_arr, $slot_data);
            endif;
        endforeach;


        # Sorting The Array In Accordance To The Slot Id
        array_multisort(array_column($slots_arr, 'slotid'), $slots_arr);

        # Loop & Store As Normal Slot Object
        foreach ($slots_arr as $slot):
            $slot_list[] = self::initialise_normal_slot($slot);
        endforeach;

        # -- Return Array Of Appointment Slots
        return $slot_list;
    }

    // Filter & Make Sure Ratio Of Doctor To Patient Is 1:1
    private static function filter_free_slots(array &$slots_arr, array $slot_data) {

        # Get All The Counters For Comparison
        $doctor_count = count($slot_data['doctorlist']);
        $patient_count = count($slot_data['patientlist']);

        # More Filtering (Make Sure There Is Enough Doctor For Patients) --> 1:1
        if ($doctor_count > $patient_count):
            # Add Normal Slot To Array
            $slots_arr[] = ($slot_data);
        endif;
    }

    // -- RETRIEVE APPOINTMENT SLOT (via slot id & appointment type)
    public static function retrieve_apptslot_by_id(string $slotid, string $facilityid): Appointment_Slot {

        # Split The ID
        $id_data = explode("~", $slotid);

        $db = new DbQuery();

        # Slot id <e.g 1001>~<date>~<appointmenttype>
        $doc_path = Database::MEDICAL_FACILITY . "/" . $facilityid . "/" . $id_data[2] . "/" . $id_data[1] . "/"
                . Database::SLOTS;
        $slot_data = $db->fetch_document_by_id($doc_path, $slotid);
        return Normal_Slot::initialise_appt_slot($slot_data, $id_data[1]);
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