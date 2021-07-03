<?php
/* Load Config File */
require_once '../resources/config.php';


require_once TIME_MOD . '/Time.php';
require_once FACILITY_MOD . '/Operating_Hours.php';
require_once DB_MOD . '/DbQuery.php';
require_once DB_MOD . '/Database.php';

require_once APPT_MOD . '/Normal_Slot.php';
require_once APPT_MOD . '/Special_Slot.php';

// -- BOOK AN APPOINTMENT  (Put This Function In The Create Appointment Page)
function book_appointment(string $patient_email, array $booking_info): bool {

    $patient_doc_id = Account_User::retrieve_user_doc_id($patient_email);

    # Validate Appointment
    if (Appointment_Record::validate_appt_booking($patient_doc_id, $booking_info)):
        echo "Validate";
        # Create User Appointment Record
        # If User Appointment Record Created Successfully
        Appointment_Record::create_appointment_record($patient_doc_id, $booking_info);
        echo "Appointment Record  Created";

        # Update To Add Patient's ID To Appointment's patient array
        # Do A Switch For Appointment Type Routing To Normal Slot or Special Slot
        add_to_slot($patient_doc_id, $booking_info);
        echo "yes";

        return True;
    else:
        echo "Similar Booking In The Same Day";
    endif;
    return false;
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
        <title>Graph</title>
        <?php require TEMPLATES_PATH . '/bootstrap.php' ?>
    </head>
    <body>
        <?php // echo $patient_per_day;     ?>
        <?php // echo var_dump($slot_arr);     ?>

        <?php
//       $slots_arr = Normal_Slot::retrieve_free_slots_by_date("mf001", Appointment_Type::DOCTOR_CONSULTATION, "15-07-2021");
        $slots_arr = Special_Slot::retrieve_free_slots_by_date("mf001", "wynterz2525@gmail.com", "15-07-2021");
        foreach ($slots_arr as $slot):
            echo nl2br($slot->get_slotid() . PHP_EOL);
        endforeach;
        // -- Testing Of Appt Booking Via HardCode
//        $booking_array = array(
//            "appointmenttype" => Appointment_Type::SPECIALIST_CONSULTATION,
//            "slotid" => "1002~18-07-2021~Medical_Personnel-iBnhkCP6HAhM0MvxeI4O",
//            "facilityid" => "mf001"
//        );
        $booking_array = array(
            "appointmenttype" => Appointment_Type::DOCTOR_CONSULTATION,
            "slotid" => "1001~15-07-2021~Doctor Consultation",
            "facilityid" => "mf001"
        );
        $success = book_appointment("yanying25@outlook.com", $booking_array);
        if ($success):
            echo "sucess";
        else:
            echo "fail";
        endif;
        ?>

    </body>
</html>