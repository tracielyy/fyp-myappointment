<?php

/*
 * @author yanying (Tracie)
 */



/* Load Config File */
//require_once '../resources/config.php';
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

    public function __construct(string $slotid, Time $appointmentschedule, string $patient, bool $available, string $facilityid) {
        parent::__construct($slotid, $appointmentschedule, $facilityid);
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
        $slot_obj = new Special_Slot($slot_data['slotid'], $appt_schedule, $slot_data['patient'], $slot_data['available'], $slot_data['facilityid']);
        return $slot_obj;
    }

    //============================================
    //      Methods Accessing Firestore Database 
    //============================================
    /* This is the static comparing function: */
//    public static function cmp_obj(Special_Slot $a, Special_Slot $b) {
//        $al = strtolower($a->get_appointmentschedule()->get_time());
//        $bl = strtolower($b->get_appointmentschedule()->get_time());
//        if ($al == $bl) {
//            return 0;
//        }
//        return ($al > $bl) ? +1 : -1;
//    }
    // -- CHANGE SLOT AVAILABILITY (whether the doctor wants to work anot)
    public static function set_availability(string $slotid, bool $availability): void {

        # Split The Slot ID <e.g 1001>~<date>~<doctor-doc-id>
        $slotid_data = explode("~", $slotid);

        $sloth_path = Database::ACCOUNT_USER . "/" . $slotid_data[2] . "/" . Database::APPOINTMENT_SLOTS . "/" . $slotid_data[1] . "/" . Database::SLOTS;
        $db = new DbQuery();
        $db->get_db()->collection($sloth_path)
                ->document($slotid)->update([
            ['path' => 'available', 'value' => $availability]
        ]);
    }

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
        $slot_path = Database::ACCOUNT_USER . "/" . $slotid_data[2] . "/" . Database::APPOINTMENT_SLOTS . "/" . $slotid_data[1] . "/" . Database::SLOTS;
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
                ->where("facilityid", "=", $facilityid)
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
        usort($slots_arr, array("Special_Slot", "cmp_obj"));

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
        usort($slots_arr, array("Special_Slot", "cmp_obj"));
        return $slots_arr;
    }

    // -- RETRIEVE APPOINTMENT SLOT (via slot id & appointment type)
    public static function retrieve_apptslot_by_id(string $id, string $facilityid): Appointment_Slot {

        # Split The ID <e.g 1001>~<date>~<doctor-doc-id>
        $id_data = explode("~", $id);

        $db = new DbQuery();

        # Slot id <e.g 1001>~<date>~<doctor-doc-id>
        $doc_path = Database::ACCOUNT_USER . "/" . $id_data[2] . "/" . Database::APPOINTMENT_SLOTS . "/" . $id_data[1] . "/" . Database::SLOTS;
        $slot_data = $db->fetch_document_by_id($doc_path, $id);
        return self::initialise_special_slot($slot_data, $facilityid);
    }

    // -- PATIENT COUNT OF ALL APPOINTMENT TYPES BY FACILITY
    public static function patient_count_per_date(string $facilityid, string $date): int {

        # Initialise Counter As Zero
        $patient_counter = 0;
        $all_slot_list = array();

        # Specialist Consultation (Filter Doctors)
        $db = new DbQuery();
        $mydb = $db->get_db();
        $query = $mydb->collection(Database::ACCOUNT_USER)
                ->where("accountdetails.usertype", "=", User_Type::MEDICAL_PERSONNEL)
                ->where("practitionerinfo.specialisation", "!=", "General")
                ->where("practitionerinfo.facilityids", "array-contains", $facilityid);

        # Container With All Queried Document ID
        $doc_id_arr = $db->retrieve_doc_id_arr($query);

        foreach ($doc_id_arr as $doc_id):
            $specialist_path = Database::ACCOUNT_USER . "/" . $doc_id . "/" . Database::APPOINTMENT_SLOTS . "/" . $date . "/" . Database::SLOTS;
            $all_slot_list[] = $db->get_all_documents($specialist_path);
        endforeach;

        if (!empty($all_slot_list)):
            foreach ($all_slot_list as $slot_list):
                foreach ($slot_list as $slot):
                    if ($slot['patient'] != ""):
                        ++$patient_counter;
                    endif;
                endforeach;
            endforeach;
        endif;

        return $patient_counter;
    }
    
    // check if there is any duplicate time in the same date
    public static function check_slot_time_duplicate(){
        
    }

    // EDIT: only `available` & `time`
    public static function edit_slot(string $doctor_email, string $date, string $slotid, string $new_time,
            bool $available = true): bool {

        # Get Doctor ID
        $doctor_doc_id = Account_User::retrieve_user_doc_id($doctor_email);

        # Edit The Slot Timing
        $db = new DbQuery();
        $path = Database::ACCOUNT_USER . '/' . $doctor_doc_id . '/' . Database::APPOINTMENT_SLOTS . '/' . $date . '/' . Database::SLOTS;
        $doc_ref = $db->get_db()->collection($path)->document($slotid);
        $trnx_result = $db->get_db()->runTransaction(function (Transaction $transaction)
        use ($doc_ref, $new_time, $available) {

            $snapshot = $transaction->snaphot($doc_ref);

            # Check The Time Before Updating
            if (Time::check_datetime_format($new_time, Time::DATE_FORMAT_DEFAULT)):
                if ($snapshot['time'] != $new_time):
                    $transaction->update($doc_ref, [
                        ['path' => 'available', 'value' => $available],
                        ['path' => 'time', 'value' => $new_time]
                    ]);
                endif;
                return true;
            endif;
            return false;
        });
        return $trnx_result;
    }

    private static function generate_id(string $doctor_doc_id, string $date): string {

        # To OrderBy The Appointment ID
        $orderBy = array('slotid');

        # Find The Last ID & Increment
        $db = new DbQuery();
        $doc_path = Database::ACCOUNT_USER . "/" . $doctor_doc_id . "/" . Database::APPOINTMENT_SLOTS . "/" . $date . "/" . Database::SLOTS;
        $last_id = $db->get_first_id_ordered($doc_path, $orderBy, false);

        # If There Is Any Present ID In Database
        $delimiter = "~";
        if ($last_id != null):

            # Slot ID (<dddd>~<date>~<medical_personnel_id>)
            $last_id_arr = explode($delimiter, $last_id);
            $new_id = ++$last_id_arr[0];

            return $new_id . $delimiter . $last_id_arr[1] . $delimiter . $last_id_arr[2]; # -- Incremental Value

        endif;
        return "1001" . $delimiter . $date . $delimiter . $doctor_doc_id; # -- New  ID
    }

    private static function create_slot_date(string $doctor_doc_id, string $date): void {
        $db = new DbQuery();
        $path = Database::ACCOUNT_USER . '/' . $doctor_doc_id . '/' . Database::APPOINTMENT_SLOTS;
        $date_data = $db->fetch_document_by_id($path, $date);
        if ($date_data == null):
            $db->get_db()->collection($path)->document($date)->set(['date' => $date]);
        endif;
    }

    public static function create_slot(string $doctor_email, string $facilityid, string $date, string $time, bool $available = true): bool {


        if (Time::check_datetime_format($date, Time::DATE_FORMAT_DEFAULT) && Time::check_datetime_format($time, Time::TIME_FORMAT_DEFAULT_NOSECONDS)):

            # Get Doctor ID
            $doctor_doc_id = Account_User::retrieve_user_doc_id($doctor_email);

            # Check If The Doctor Exist
            if ($doctor_doc_id !== null):

                # Get Slot ID
                $slotid = self::generate_id($doctor_doc_id, $date);

                # Create A New Slot
                $data = [
                    'available' => $available,
                    'slotid' => $slotid,
                    'time' => $time,
                    'patient' => '',
                    'facilityid' => $facilityid
                ];
                $db = new DbQuery();
                self::create_slot_date($doctor_doc_id, $date); # -- Create Parent Document If Not Exist
                $path = Database::ACCOUNT_USER . '/' . $doctor_doc_id . '/' . Database::APPOINTMENT_SLOTS . '/' . $date . '/' . Database::SLOTS;
                $db->get_db()->collection($path)->document($slotid)->set($data);
                return true;

            endif;
            return false; # -- Doctor Not Found
        endif;
        return false; # -- Date Time Format Incorrect
    }

    // Unfinished
    public static function add_new_slots(string $doctor_email, array $date_range) {
        # Get Doctor ID
        $doctor_doc_id = Account_User::retrieve_user_doc_id($doctor_email);

        # Insert Special Slots
        $db = new DbQuery();
        $slot_path = Database::ACCOUNT_USER . '/' . $doctor_doc_id . '/' . Database::APPOINTMENT_SLOTS;

        foreach ($date_range as $date) {
            $query = $db->get_db()->collection($slot_path)->document($date);
            for ($i = 1001; $i <= 1015; $i++) {
                
            }
        }
    }

}
