<?php

session_start();
/* Load Config File */
require_once '../resources/config.php';
require '../vendor/autoload.php';

require_once TIME_MOD . '/Time.php';
require_once UTIL_MOD . '/StringUtils.php';

require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Patient.php';
require_once USER_MOD . '/Medical_Personnel.php';

require_once ENUMS_PATH . '/User_Type.php';
require_once ENUMS_PATH . '/Appointment_Type.php';

require_once DB_MOD . '/DbQuery.php';
require_once DB_MOD . '/Database.php';

require_once APPT_MOD . '/Appointment_Slot.php';
require_once APPT_MOD . '/Normal_Slot.php';
require_once APPT_MOD . '/Special_Slot.php';
require_once APPT_MOD . '/Appointment_Record.php';

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

$cal_default = Time::CALENDAR_FORMAT_DEFAULT;
$next_day = Time::get_enddate(date($cal_default), 1, $cal_default);



/* Load Appointment Slots */
if ($_SERVER["REQUEST_METHOD"] == "POST"):
    if (isset($_POST['ajax'])):

        $selected_date = Time::date_format_default($_POST['set_date']);
        $appt_date = Time::date_format_change($selected_date, $cal_default);
        $raw_slots = retrieve_slots($_POST['set_facilityid'], $_POST['set_appointmenttype'], $selected_date);

        $slots_arr = StringUtils::object_to_array($raw_slots);
        # Add additional Information
        $slot_box = array();
        foreach ($slots_arr as $slot):
            $date = Time::date_format_change($slot['appointmentschedule']['date'], Time::DATE_FORMAT_APPOINTMENT);
            $time = Time::to_12hours($slot['appointmentschedule']['time'], false);
            $slotdescription = $date . ', ' . $time;
            $slot['slotdescription'] = $slotdescription;
            $slot_box[] = $slot;
        endforeach;
        $encode_slots = json_encode($slot_box);
        echo $encode_slots; 

    else:
        $selected_date = Time::date_format_default($next_day);
        $appt_date = $next_day;
    endif;
else:
    //echo "post not called";
    $selected_date = Time::date_format_default($next_day);
    $appt_date = $next_day;
endif; # -- END POST REQUEST
# -- TESTING
$slot_appt_type = Appointment_Type::DOCTOR_CONSULTATION;
$slot_facilityid = "mf001";
$slot_date = "15-07-2021";
$slot_doctor = "wynterz2525@gmail.com";

//echo nl2br(PHP_EOL . "Test Retrieve Slots" . PHP_EOL);
//$raw_slots = retrieve_slots($slot_facilityid, $slot_appt_type, $slot_date);
////echo var_dump($raw_slots);
//echo nl2br(PHP_EOL . PHP_EOL);
//$arr = array();
//echo nl2br(PHP_EOL . PHP_EOL);
//$slots_arr = StringUtils::object_to_array($raw_slots);
//echo '<pre>';
////    var_dump(json_encode($slots_arr));
//        var_dump(json_encode($slots_arr));
//
//$slot_box = array();
//foreach ($slots_arr as $slot):
//    $date = Time::date_format_change($slot['appointmentschedule']['date'], Time::DATE_FORMAT_APPOINTMENT);
//    $time = Time::to_12hours($slot['appointmentschedule']['date'], false);
//    $slotdescription = $date . ',' . $time;
//    $slot['slotdescription'] = $slotdescription;
//    $slot_box[] = $slot;
//endforeach;
//$encode_slots = json_encode($slot_box);
//echo $encode_slots;

//echo '</pre>';
//
//$test_arr = array("tracie", "qwynn");
//$encoded = "'" . json_encode($test_arr) . "'";
//
//echo $encoded;
?>

