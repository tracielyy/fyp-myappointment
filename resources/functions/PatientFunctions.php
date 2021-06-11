<?php

/*
 * @author yanying (Tracy)
 */
# -- Load Config File -- #
require_once '../resources/config.php';
require_once ENTITIES_PATH . '/Account_User.php';
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
        $db->insert_data(Database::ACCOUNT_USER, $userDataArr);
    }

    //============================================
    //      Appointments
    //============================================
    // -- Create Appointment -- //
    public static function create_appointment(array $appointment_info): bool {

        # SET Appointment Creation Time
        $createdon = new Time();
        $appointment_info['createdon'] = array(
            'date' => $createdon->get_date(),
            'time' => $createdon->get_time()
        );

        # SET Default Appointment Status
        $appointment_info['appointmentstatus'] = Appointment_Status::UPCOMING;

        # Add The AppointmentRecord To The Database
        $db = new DbQuery();
        return $db->insert_data(self::APPOINTMENT_RECORD, $appointment_info);
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