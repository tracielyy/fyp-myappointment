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
    public static function create_appointment(array $appointment_info): void {


        $appointment_info['createdon'] = (string) date("d-m-Y");

        $db = new DbQuery();
        $db->insert_data(self::APPOINTMENT_RECORD, $appointment_info);
    }

    private static function get_patient_appointment(array $email, array $appointmentstatus): array {
        # Array Of Appointments
        $appointment_arr = array();

        # Query For Upcoming Appointment Records
        $db = new DbQuery();
        $record_list = $db->get_nested_collection(Database::ACCOUNT_USER, Database::APPOINTMENT_RECORD,
                $email, $appointmentstatus);

        # Create Appointment Record Object List
        foreach ($record_list as $record) {
            $facility = AccountUserFunctions::get_facility_by_id($record['facilityid']);
            $scheduledon = new Time($record['scheduledon']['date'], $record['scheduledon']['time']);
            $createdon = new Time($record['createdon']['date'], $record['createdon']['time']);
            $record_object = new Appointment_Record($createdon, $scheduledon, $record['appointmentid'], $record['appointmenttype'],
                    $facility, $record['appointmentstatus']);
            $appointment_arr[] = $record_object;
        }
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
    private static function change_appointment_status() {
        
    }

    // -- Remove Appointment_Record When User `Cancel` Their Appointment
    public static function cancel_appointment(string $email, string $appointmentid) {
        
    }

}

?>