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
    public static function get_apptslots(string $facilityid, string $appointmenttype, string $date): array {

        # Create Empty Appointment Slots Array
        $appointment_slots = array();

        $doc_path = Database::MEDICAL_FACILITY . "/" . $facilityid . "/" . $appointmenttype . "/";

        $db = new DbQuery();

        # - Filtering Of Full Slots NOT IMPLEMENTED - #

        $path = $doc_path . $date . "/Slots";
        $slot_list = $db->get_documents_by_path($path, false);

        # Loop & Add Slots For Each `Date` Loop
        foreach ($slot_list as $slots):
            $appointment_time = new Time($date, $slots['time']);
            $slot_obj = new Appointment_Slot($slots['slotid'], $appointment_time, $slots['patients'], $slots['doctors']);
            $appointment_slots[] = $slot_obj;
        endforeach;

        return $appointment_slots;
    }

//    public static function get_apptslots_by_interval(string $facilityid, string $appointmenttype, int $days = 1): array {
//
//        # Create Empty Appointment Slots Array
//        $appointment_slots = array();
//
//
//        $doc_path = Database::MEDICAL_FACILITY . "/" . $facilityid . "/" . $appointmenttype . "/";
//
//        $current_date = Time::get_current_date();
//        $start_date = Time::get_enddate($current_date, 1);
//        $end_date = Time::get_enddate($start_date, $days);
//        $date_arr = Time::get_date_from_range($start_date, $end_date);
//
//        # Loop Through Several Dates (Add Slots From Different Dates)
//        $db = new DbQuery();
//        foreach ($date_arr as $date):
//
//            # - Filtering Of Full Slots NOT IMPLEMENTED - #
//
//            $path = $doc_path . $date . "/Slots";
//            $slot_list = $db->get_documents_by_path($path, false);
//            
//            # Loop & Add Slots For Each `Date` Loop
//            foreach ($slot_list as $slots):
//                $appointment_time = new Time ($date, $slots['time']);
//                $slot_obj = new Appointment_Slot($slots['slotid'], $appointment_time, $slots['patients'], $slots['doctors']);
//                $appointment_slots[] = $slot_obj;
//            endforeach;
//
//        endforeach;
//
//        return $appointment_slots;
//    }
    // -- Create Appointment -- //
    public static function create_appointment_record(string $email, array $booking_info): bool {

        $condition['credentials'] = array('email' => $email);

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
        $id = self::generate_appointment_id($condition);
        $appointment_info['appointmentid'] = $id;

        # Add The AppointmentRecord To The Database
        $db = new DbQuery();
        $user_doc_id = $db->get_document_id(Database::ACCOUNT_USER, $condition);
        $appt_doc_path = Database::ACCOUNT_USER . "/" . $user_doc_id . "/" . Database::APPOINTMENT_RECORD;
        $user_appt_update = $db->insert_data($appt_doc_path, $appointment_info, false, $id);

        # Update The Appointment Slot (Not Done)
        $user_id_arr['patients'] = array($user_doc_id);
        $slots_doc_path = Database::MEDICAL_FACILITY . "/" . $booking_info['facilityid'] . "/" .
                $booking_info['appointmenttype'] . "/" . $booking_info['date'] . "/" . Database::SLOTS;
        $appt_slot_update = $db->update_array_add($slots_doc_path, $booking_info['slotid'], $user_id_arr);
        return ($user_appt_update && $appt_slot_update);
    }

    // -- User-Defined ID -- //
    private static function generate_appointment_id(array $conditionArr) {

        # To OrderBy The Appointment ID
        $orderBy = array('appointmentid');

        # Find The Last ID & Increment
        $db = new DbQuery();
        $last_id_appointment = $db->get_sub_document_ordered(Database::ACCOUNT_USER, Database::APPOINTMENT_RECORD, $conditionArr, $orderBy, false);

        # If There Is Any Present ID In Database
        $current_year = Time::get_current_year();
        if ($last_id_appointment !== null) :

            $last_id = explode($last_id_appointment, "-");
            $last_id_date = $last_id[1];

            # Compare Year
            if ($last_id_date == $current_year):

                # Increase The Number
                $new_id = ++$last_id[2];
                return $last_id[0] . "-" . $last_id[1] . "-" . $new_id;
            else:
                return $last_id[0] . "-" . $current_year . "-001";

            endif;

        # No ID Present In Database
        else:
            return "appt-" . $current_year . "-000";
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
            $facility = AccountUserFunctions::get_facility_by_id($record['facilityid']);
            $scheduledon = new Time($record['scheduledon']['date'], $record['scheduledon']['time']);
            $createdon = new Time($record['createdon']['date'], $record['createdon']['time']);
            $record_object = new Appointment_Record($createdon, $scheduledon, $record['appointmentid'], $record['appointmenttype'],
                    $facility, $record['appointmentstatus']);
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

        # Sub-Conditions (For Inner Collection)
        $subcondition = array('appointmentid' => $appointmentid);

        # SET Appointment Status To Cancelled After Patient Cancel Appointment
        $changed_info['appointmentstatus'] = Appointment_Status::CANCELLED;

        # Update The Modified Information In The Database
        $db = new DbQuery();
        return $db->modify_nested_collection(Database::ACCOUNT_USER, Database::APPOINTMENT_RECORD,
                        $condition, $subcondition, $changed_info);
    }

}

?>