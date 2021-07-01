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
    public static function initialise_appt_slot(array $appt_slot, string $date): Normal_Slot {

        # Time
        $appt_schedule = new Time($date, $appt_slot['time']);
        $slot_obj = new Normal_Slot($appt_slot['slotid'], $appt_schedule, $appt_slot['patientlist'], $appt_slot['doctorlist']);
        return $slot_obj;
    }

    // ####################     Database Functions      ################### //
    // -- ADD PATIENT TO NORMAL SLOT (DR CONSULT, CHECKUP)
    public function insert_patient_to_slot(string $slotid, string $patient_doc_id) {
        # Slot id <e.g 1001>~<date>~<e.g. drconsult, checkup>
    }

    // -- REMOVE PATIENT FROM SLOT WHEN CANCELLING APPOINTMENT
    public static function remove_patient_from_slot(string $user_doc_id, array $appt_record): bool {

        # Appointment Schedule
        $scheduledon = $appt_record['scheduledon'];

        # Patient Document ID In Array (To Be Removed)
        $user_id_arr['patients'] = array($user_doc_id);

        # Get Appointment Slot's ID
        $db = new DbQuery();
        $slot_path = Database::MEDICAL_FACILITY . "/" . $appt_record['facilityid'] . "/" . $appt_record['appointmenttype'] . "/" . $scheduledon['date'] . "/" . Database::SLOTS;
        $slot_condition = array('time' => $scheduledon['time']);
        $slot_doc_id = $db->get_document_id($slot_path, $slot_condition);


        return $db->update_array_remove($slot_path, $slot_doc_id, $user_id_arr);
    }

    // -- RETRIEVE APPOINTMENT SLOT (via slot id & appointment type)
    public static function retrieve_apptslot_by_id(string $slotid, string $appt_type, string $facilityid): Appointment_Slot {

        # Split The ID
        $id_data = explode("~", $slotid);

        $db = new DbQuery();

        # Slot id <e.g 1001>~<date>~<appointmenttype>
        $doc_path = Database::MEDICAL_FACILITY . "/" . $facilityid . "/" . $appt_type . "/" . $id_data[1] . "/"
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

?>