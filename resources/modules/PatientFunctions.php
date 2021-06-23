<?php

/*
 * @author yanying (Tracy)
 */
# -- Load Config File -- #
require_once '../resources/config.php';
require_once ENTITIES_PATH . '/Account_User.php';
require_once ENTITIES_PATH . '/Appointment_Slot.php';
require_once ENTITIES_PATH . '/Admin.php';
require_once ENTITIES_PATH . '/Medical_Personnel.php';
require_once ENUMS_PATH . '/User_Type.php';
require_once UTILS_PATH . '/DbQuery.php';
require_once FUNCTIONS_PATH . '/AccountUserFunctions.php';

class PatientFunctions {

    //============================================
    //      Patients
    //============================================
    // -- Insert New Patient To Firestore (Upon Registration) -- //
    public static function create_patient(array $userDataArr): void {

        # Declaration Of Basic Information To Include To Account_User
        $account_user_arr = ArrayCreation::account_creation_array(User_Type::PATIENT);

        # Load Basic Account User Fields & Values To Array
        foreach ($account_user_arr as $field => $value) :
            $userDataArr[$field] = $value;
        endforeach;

        # Add Patient Data To Database
        $db = new DbQuery();
        $db->insert_data(Database::ACCOUNT_USER, $userDataArr, true);
    }

    //============================================
    //      Appointments
    //============================================
    private static function appt_record_init(array $appt_record): Appointment_Record {
        $facility = AccountUserFunctions::get_facility_by_id($appt_record['facilityid']);
        $scheduledon = new Time($appt_record['scheduledon']['date'], $appt_record['scheduledon']['time']);
        $createdon = new Time($appt_record['createdon']['date'], $appt_record['createdon']['time']);
        $record_object = new Appointment_Record($createdon, $scheduledon, $appt_record['appointmentid'], $appt_record['appointmenttype'],
                $facility, $appt_record['appointmentstatus']);
        return $record_object;
    }

    public static function get_appointment_by_id(string $user_email, string $appointmentid): array {
        $condition['credentials'] = array('email' => $user_email);
        $db = new DbQuery();
        $user_doc_id = $db->get_document_id(Database::ACCOUNT_USER, $condition);

        # Query For The Particular Appointment Slot
        $doc_path = Database::ACCOUNT_USER . "/" . $user_doc_id . "/" . Database::APPOINTMENT_RECORD;
        $appt_record = $db->get_document($doc_path, $appointmentid);

        $appt_record_obj = self::appt_record_init($appt_record);

        return $appt_record_obj;
    }

    public static function get_apptslots(string $facilityid, string $appointmenttype, string $date, int $max_patients = 20): array {

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

    // -- Create Appointment -- //
    public static function book_appointment(string $email, array $booking_info): bool {

        $condition['credentials'] = array('email' => $email);
        $db = new DbQuery();
        $user_doc_id = $db->get_document_id(Database::ACCOUNT_USER, $condition);

        # Validate Appointment
        if (self::validate_appt_booking($user_doc_id, $booking_info)):

            # Create User Appointment Record
            $user_appt_update = self::create_appointment_record($db, $user_doc_id, $booking_info);

            # If User Appointment Record Created Successfully
            if ($user_appt_update):

                # Update To Add Patient's ID To Appointment's patient array
                $appt_slot_update = self::add_patient_to_slot($db, $user_doc_id, $booking_info);
                return ($user_appt_update && $appt_slot_update);
            else:
                return false;
            endif;

        endif;
        return false;
    }

    private static function create_appointment_record(DbQuery $db, string $user_doc_id, array $booking_info): bool {

        # SET Appointment Booking Information
        $appointment_info['appointmenttype'] = $booking_info['appointmenttype'];
        $appointment_info['facilityid'] = $booking_info['facilityid'];
        $appointment_info['scheduledon'] = array(
            'date' => $booking_info['date'],
            'time' => $booking_info['time']
        );

        # SET Appointment Creation Time
        $createdon = new Time();
        $appointment_info['createdon'] = array(
            'date' => $createdon->get_date(),
            'time' => $createdon->get_time()
        );

        # SET Default Appointment Status
        $appointment_info['appointmentstatus'] = Appointment_Status::UPCOMING;

        # Get Appointment ID
        $id = self::generate_appointment_id($user_doc_id);
        $appointment_info['appointmentid'] = $id;

        # Add The AppointmentRecord To The Database
        $appt_doc_path = Database::ACCOUNT_USER . "/" . $user_doc_id . "/" . Database::APPOINTMENT_RECORD;
        return $db->insert_data($appt_doc_path, $appointment_info, false, $id);
    }

    private static function add_patient_to_slot(DbQuery $db, string $user_doc_id, array $booking_info): bool {

        # Update The Appointment Slot (Not Done)
        $user_id_arr['patients'] = array($user_doc_id);
        $slots_doc_path = Database::MEDICAL_FACILITY . "/" . $booking_info['facilityid'] . "/" .
                $booking_info['appointmenttype'] . "/" . $booking_info['date'] . "/" . Database::SLOTS;
        return $db->update_array_add($slots_doc_path, $booking_info['slotid'], $user_id_arr);
    }

    // -- Validate Appointment Booking -- //
    private static function validate_appt_booking(string $user_doc_id, array $booking_info): bool {

        # Declaring The Conditions
        $conditions = array(
            'appointmentstatus' => Appointment_Status::UPCOMING,
            'appointmenttype' => $booking_info['appointmenttype'],
            'facilityid' => $booking_info['facilityid'],
            'scheduledon' => array(
                'date' => $booking_info['date'],
                'time' => $booking_info['time']
            )
        );

        # Search For Same Appointment
        $db = new DbQuery();
        $path = Database::ACCOUNT_USER . "/" . $user_doc_id . "/" . Database::APPOINTMENT_RECORD;
        $same_appt = $db->query_exact_match($path, $conditions);
        return $validate_status = ($same_appt == null) ? true : false;
    }

    // -- User-Defined ID -- //
    private static function generate_appointment_id(string $user_doc_id) {

        # To OrderBy The Appointment ID
        $orderBy = array('appointmentid');

        # Find The Last ID & Increment
        $db = new DbQuery();
        $doc_path = Database::ACCOUNT_USER . "/" . $user_doc_id . "/" . Database::APPOINTMENT_RECORD;
        $last_id_appointment = $db->get_documentid_ordered($doc_path, $orderBy, false);

        echo "The last id:" . $last_id_appointment;
        # If There Is Any Present ID In Database
        $current_year = Time::get_current_year();
        if ($last_id_appointment != null) :

            $last_id = explode("-", $last_id_appointment);
            $last_id_date = $last_id[1];

            # Compare Year
            if ($last_id_date == $current_year):

                # Increase The Number
                $new_id = ++$last_id[2];
                echo "Same Year";
                return $last_id[0] . "-" . $last_id[1] . "-" . $new_id;
            else:
                echo "A New Year";
                return $last_id[0] . "-" . $current_year . "-1000";

            endif;

        # No ID Present In Database
        else:
            return "appt-" . $current_year . "-1000";
        endif;
    }

    // -- Retrieve Of Appointment Records Of Certain Type -- //
    private static function get_patient_appointment(array $email, array $appointmentstatus): array {

        # Array Of Appointments
        $appointment_arr = array();

        # Query For Upcoming Appointment Records
        $db = new DbQuery();
        $record_list = $db->get_nested_collection(Database::ACCOUNT_USER, Database::APPOINTMENT_RECORD,
                $email, $appointmentstatus);

        # Create Appointment Record Object List
        foreach ($record_list as $record) :
            $record_object = self::appt_record_init($record);
            $appointment_arr[] = $record_object;
        endforeach;
        return $appointment_arr;
    }

    // -- Get Upcoming Appointment By Patient (Array Of Appointment_Record) -- //
    public static function get_upcoming_appointments(array $email): array {

        # Set Default Appointment Status
        $appointmentstatus['appointmentstatus'] = Appointment_Status::UPCOMING;

        # Retrieving List Of Upcoming Appointments
        $upcoming_arr = self::get_patient_appointment($email, $appointmentstatus);

        return $upcoming_arr;
    }

    // -- Get Missed Appointment By Patient (Array Of Appointment Record)  last 14 days -- //
    public static function get_missed_appointments(array $email): array {

        # Set Default Appointment Status
        $appointmentstatus['appointmentstatus'] = Appointment_Status::MISSED;

        # Retrieving List Of Missed Appointments
        $missed_arr = self::get_patient_appointment($email, $appointmentstatus);

        return $missed_arr;
    }

    // -- Update Appointment Status (e.g. Upcoming > Missed) -- //
    private static function update_appointment_missed() {
        
    }

    // -- Update The Patient's Appointment Schedule -- //
    public static function reschedule_appointment(string $email, string $appointmentid, array $scheduledon) {

        # Conditions (For Outer Collection)
        $condition['credentials'] = array('email' => $email);

        # Sub-Conditions (For Inner Collection)
        $subcondition = array('appointmentid' => $appointmentid);

        # RESET Default Appointment Status (To Cater To MISSED Appointments)
        $changed_info['appointmentstatus'] = Appointment_Status::UPCOMING;

        # Add The Rescheduled Time To Array
        $changed_info['scheduledon'] = $scheduledon;

        # Update The Modified Information
        $db = new DbQuery();
        return $db->modify_nested_collection(Database::ACCOUNT_USER, Database::APPOINTMENT_RECORD,
                        $condition, $subcondition, $changed_info);
    }

    // -- Update Appointment_Record When User `Cancel` Their Appointment
    public static function cancel_appointment(string $email, string $appointmentid): bool {

        # Conditions (For Outer Collection)
        $condition['credentials'] = array('email' => $email);

        # Get User Document ID
        $db = new DbQuery();
        $user_doc_id = $db->get_document_id(Database::ACCOUNT_USER, $condition);

        # SET Appointment Status To Cancelled After Patient Cancel Appointment
        $changed_info['appointmentstatus'] = Appointment_Status::CANCELLED;

        # Update The Patient's Appointment Record
        $user_appt_path = Database::ACCOUNT_USER . "/" . $user_doc_id . "/" . Database::APPOINTMENT_RECORD;
        $user_appt_update = $db->update_document_by_path($user_appt_path, $appointmentid, $changed_info);


        if ($user_appt_update):
            # Get Appointment Record In Array
            $appt_record = $db->get_document($user_appt_path, $appointmentid);

            # Update The Appointment Slot
            $appt_slot_update = self::remove_patient_from_slot($db, $user_doc_id, $appt_record);

            return ($user_appt_update && $appt_slot_update);
        endif;
        return false;
    }

    // -- Update The Appointment Slot  (Remove The Patient From The Array)
    private static function remove_patient_from_slot(DbQuery $db, $user_doc_id, array $appt_record): bool {

        # Appointment Schedule
        $scheduledon = $appt_record['scheduledon'];

        # Patient Document ID In Array (To Be Removed)
        $user_id_arr['patients'] = array($user_doc_id);

        # Get Appointment Slot's ID
        $slot_path = Database::MEDICAL_FACILITY . "/" . $appt_record['facilityid'] . "/" . $appt_record['appointmenttype'] . "/" . $scheduledon['date'] . "/" . Database::SLOTS;
        $slot_condition = array('time' => $scheduledon['time']);
        $slot_doc_id = $db->get_document_id($slot_path, $slot_condition);


        return $db->update_array_remove($slot_path, $slot_doc_id, $user_id_arr);
    }

}

?>