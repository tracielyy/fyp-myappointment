<?php
/* Load Config File */
require_once '../resources/config.php';
require '../vendor/autoload.php';
require_once TIME_MOD . '/Time.php';
require_once FACILITY_MOD . '/Operating_Hours.php';
require_once DB_MOD . '/DbQuery.php';
require_once DB_MOD . '/Database.php';
require_once DB_MOD . '/DbStorage.php';


require_once APPT_MOD . '/Normal_Slot.php';
require_once APPT_MOD . '/Special_Slot.php';

require_once USER_MOD . '/Medical_Personnel.php';

# ---------------------------------------------  BUSINESS LOGIC START --------------------------------------------- #

// -- BOOK AN APPOINTMENT  (Put This Function In The Create Appointment Page)
function book_appointment(string $patient_email, array $booking_info): bool {

    $patient_doc_id = Account_User::retrieve_user_doc_id($patient_email);

    # Validate Appointment
    $valid = Appointment_Record::validate_appt_booking($patient_doc_id, $booking_info);
    if ($valid):
        echo "Validate";

        # Create User Appointment Record
        Appointment_Record::create_appointment_record($patient_doc_id, $booking_info);
        echo "Appointment Record  Created";

        # Update To Add Patient's ID To Appointment's patient array
        add_to_slot($patient_doc_id, $booking_info);
        echo "yes";

        return True;
    else:
        echo "Similar Booking In The Same Day";
    endif;
    return false;
}

// -- CANCEL APPOINTMENT
function cancel_appointment(string $patient_email, string $appointmentid) {

    $patient_doc_id = Account_User::retrieve_user_doc_id($patient_email);

    # Remove Appointment Record From The Patient DB
    $appt_record = Appointment_Record::remove_appointment_record($patient_doc_id, $appointmentid);
    echo $appt_record->get_appointmentid();

    # Remove Patient From The Slot
    remove_from_slot($patient_doc_id, $appt_record);
}

// -- Call Appropriate Method For Different Appointment Type
function add_to_slot(string $patient_doc_id, array $booking_info): void {
    switch ($booking_info['appointmenttype']):
        case Appointment_Type::CHECK_UP:
        case Appointment_Type::DOCTOR_CONSULTATION:
            Normal_Slot::insert_patient_to_slot($booking_info['slotid'], $patient_doc_id, $booking_info['facilityid']);
            break;
        case Appointment_Type::SPECIALIST_CONSULTATION:
            Special_Slot::insert_patient_to_slot($booking_info['slotid'], $patient_doc_id);
            break;
    endswitch;
}

// Remove Patient From Slot
function remove_from_slot(string $patient_doc_id, Appointment_Record $appt_record): void {

    switch ($appt_record->get_appointmenttype()):
        case Appointment_Type::CHECK_UP:
        case Appointment_Type::DOCTOR_CONSULTATION:
            Normal_Slot::remove_patient_from_slot($appt_record->get_appointmentslot()->get_slotid(), $appt_record->get_facility()->get_facilityid(), $patient_doc_id);
            break;
        case Appointment_Type::SPECIALIST_CONSULTATION:
            Special_Slot::remove_patient_from_slot($appt_record->get_appointmentslot()->get_slotid());
            break;
    endswitch;
}

// -- RESCHEDULE APPOINTMENT
function reschedule_appointment(string $patient_email, string $current_slotid, string $new_slotid) {
    
}

// -- DISPLAY AVAILABLE SLOTS ($doctor_email is optional -- Only when user select specialist)
function retrieve_slots(string $facilityid, string $appointmenttype, string $date, ?string $doctor_email = NULL): array {
    switch ($appointmenttype):
        case Appointment_Type::CHECK_UP:
        case Appointment_Type::DOCTOR_CONSULTATION:
            return Normal_Slot::retrieve_free_slots_by_date($facilityid, $appointmenttype, $date);
        case Appointment_Type::SPECIALIST_CONSULTATION:
            if ($doctor_email != NULL):
                return Special_Slot::retrieve_free_slots_by_date($facilityid, $doctor_email, $date);
        endif;
    endswitch;
}

# ---------------------------------------------  BUSINESS LOGIC END --------------------------------------------- #
# -- Find The Patient Count (HARDCODE)
//$patient_per_day = Normal_Slot::patient_count_per_date("mf001", "15-07-2021");
//$patient_per_day += Special_Slot::patient_count_per_date("mf001", "15-07-2021");
# -- Get Slots
$doctor_doc_id = "Medical_Personnel-iBnhkCP6HAhM0MvxeI4O";
$slot_arr = Special_Slot::retrieve_booked_slots_by_date("wynterz2525@gmail.com", "15-07-2021");
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Debug Function Testing</title>
        <?php require TEMPLATES_PATH . '/bootstrap.php' ?>
    </head>
    <body>
        <?php // echo $patient_per_day;        ?>
        <?php // echo var_dump($slot_arr);         ?>

        <?php
//       $slots_arr = Normal_Slot::retrieve_free_slots_by_date("mf001", Appointment_Type::DOCTOR_CONSULTATION, "15-07-2021");
//        $slots_arr = Special_Slot::retrieve_free_slots_by_date("mf001", "wynterz2525@gmail.com", "15-07-2021");
        foreach ($slot_arr as $slot):
            echo nl2br($slot->get_slotid() . PHP_EOL);
        endforeach;
        // -- Testing Of Appt Booking Via HardCode
        echo nl2br(PHP_EOL . "Testing Book Specialist -- HARDCODE --" . PHP_EOL);
        $booking_array = array(
            "appointmenttype" => Appointment_Type::SPECIALIST_CONSULTATION,
            "slotid" => "1001~15-07-2021~Medical_Personnel-iBnhkCP6HAhM0MvxeI4O",
            "facilityid" => "mf001"
        );
//        echo nl2br(PHP_EOL . "Testing Book Dr Consult -- HARDCODE --" . PHP_EOL);
//        $booking_array = array(
//            "appointmenttype" => Appointment_Type::DOCTOR_CONSULTATION,
//            "slotid" => "1001~15-07-2021~Doctor Consultation",
//            "facilityid" => "mf001"
//        );
//        $success = book_appointment("yanying25@outlook.com", $booking_array);
//        if ($success):
//           echo nl2br(PHP_EOL . "success" . PHP_EOL);
//        else:
//            echo nl2br(PHP_EOL . "fail" . PHP_EOL);
//        endif;

        echo nl2br(PHP_EOL . "Testing Cancel Appointment -- HARDCODE --" . PHP_EOL);
//        cancel_appointment("yanying25@outlook.com", "appt-2021-1003");

        echo nl2br(PHP_EOL . "Testing Reschedule Appointment -- HARDCODE --" . PHP_EOL);
        echo nl2br(PHP_EOL . "Testing Create Medical Personnel -- HARDCODE --" . PHP_EOL);




        echo nl2br(PHP_EOL . "Testing DISPLAY APPT SLOTS -- HARDCODE --" . PHP_EOL);
        # -- Display Specialist Slots
//        $slot_appt_type = Appointment_Type::SPECIALIST_CONSULTATION;
//        $slot_facilityid = "mf001";
//        $slot_date = "15-07-2021";
//        $slot_doctor = "wynterz2525@gmail.com";
        # -- Display Doctor Consultation Slots
        $slot_appt_type = Appointment_Type::DOCTOR_CONSULTATION;
        $slot_facilityid = "mf001";
        $slot_date = "15-07-2021";
        $slot_doctor = "wynterz2525@gmail.com";

//        $free_slots = retrieve_slots($slot_facilityid, $slot_appt_type, $slot_date);
//        foreach ($free_slots as $slot):
//            echo nl2br($slot->get_slotid() . PHP_EOL);
//        endforeach;
        echo nl2br(PHP_EOL . "Testing Show mf001 Specialisations -- HARDCODE --" . PHP_EOL);
//        $mf001 = Medical_Facility::retrieve_facility_by_id("mf001");
//        $s_arr = $mf001->get_specialisations();
//        echo var_dump($s_arr);
//        foreach ($s_arr as $specialisation):
//            if ($specialisation != "General") {
//                echo $specialisation . "<br/>";
//            }
//        endforeach;
        echo nl2br(PHP_EOL . "Testing Show Image -- HARDCODE --" . PHP_EOL);
        $db_storage = new DbStorage();
        $file = $db_storage->retrieve_data_url("facility/facilityicon/mf001.png");
        echo "<img src='{$file}' />";
        ?>

    </body>
</html>