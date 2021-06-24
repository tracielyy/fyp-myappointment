<?php

/*
 * @author yanying (Tracie)
 */

/* Load Config File */
require_once '../resources/config.php';
require_once APPT_MOD . '/Appointment_Record.php';

/*
 *       # VIEW APPOINTMENT #
 */

class RetrieveAppointment {

    private function __construct() {
        // -- Prevent Instantiation
    }

    // -- Retrieve Of Appointment Records Of Certain Type -- //
    private static function retrieve_patient_appointment(array $email, ?array $appointmentstatus = NULL): array {

        # Array Of Appointments
        $appointment_arr = array();

        # Query For Upcoming Appointment Records
        $db = new DbQuery();

        $doc_id = $db->get_document_id(Database::ACCOUNT_USER, $email);
        $doc_path = Database::ACCOUNT_USER . "/" . $doc_id . "/" . Database::APPOINTMENT_RECORD;

        # Check If Need To Filter By Appointment Status
        if ($appointmentstatus == NULL):
            $record_list = $db->get_documents_by_path($doc_path, False);
        else:
            $record_list = $db->get_documents_by_path($doc_path, True, $appointmentstatus);
        endif;

        # Create Appointment Record Object List
        foreach ($record_list as $record) :

            $appointment_arr[] = Appointment_Record::initialise_appointment_record($record);

        endforeach;
        return $appointment_arr;
    }

    // -- GET ALL APPOINTMENT RECORDS
    public static function retrieve_all_appointments(array $email): array {
        return self::retrieve_patient_appointment($email);
    }

    // -- FILTER APPOINTMENTS TO APPOINTMENT STATUS -- //
    public static function sort_appointment_by_status(array $appt_arr): array {

        # Create Empty Container
        $arr ['upcoming'] = array();
        $arr['missed'] = array();
        foreach ($appt_arr as $appt):

            # FILTER
            if ($appt->get_appointmentstatus() == Appointment_Status::UPCOMING):
                $arr['upcoming'][] = $appt;
            elseif ($appt->get_appointmentstatus() == Appointment_Status::MISSED):
                $arr['cancelled'][] = $appt;
            endif;
        endforeach;
        return $arr;
    }

    // -- GET UPCOMING APPOINTMENT RECORDS
    public static function retrieve_upcoming_appointments(array $email): array {

        # Set Default Appointment Status
        $appointmentstatus['appointmentstatus'] = Appointment_Status::UPCOMING;

        # Retrieving List Of Upcoming Appointments
        $upcoming_arr = self::retrieve_patient_appointment($email, $appointmentstatus);

        return $upcoming_arr;
    }

    // -- GET MISSED APPOINTMENT RECORDS  [last 14 days]
    public static function retrieve_missed_appointments(array $email): array {

        # Set Default Appointment Status
        $appointmentstatus['appointmentstatus'] = Appointment_Status::MISSED;

        # Retrieving List Of Missed Appointments
        $missed_arr = self::retrieve_patient_appointment($email, $appointmentstatus);

        return $missed_arr;
    }

}

?>
