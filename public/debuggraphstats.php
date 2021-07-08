<?php
/* Load Config File */
require_once '../resources/config.php';


require_once TIME_MOD . '/Time.php';
require_once FACILITY_MOD . '/Operating_Hours.php';
require_once DB_MOD . '/DbQuery.php';
require_once DB_MOD . '/Database.php';

require_once APPT_MOD . '/Normal_Slot.php';
require_once APPT_MOD . '/Special_Slot.php';

require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Patient.php';



# -- Find The Patient Count (HARDCODE)
$patient_per_day = Normal_Slot::patient_count_per_date("mf001", "15-07-2021");
$patient_per_day += Special_Slot::patient_count_per_date("mf001", "15-07-2021");


# -- Get Slots
$slot_arr = Special_Slot::retrieve_booked_slots_by_date("Medical_Personnel-iBnhkCP6HAhM0MvxeI4O", "15-07-2021");
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
        &nbsp;<?php echo $patient_per_day; ?>
                &nbsp;<?php echo var_dump($slot_arr); ?>
    <hr>
                <?php 
        $pnum = 0;
        $p_perday = Special_Slot::patient_count_per_date("mf001", "15-07-2021");
            foreach($slot_arr as $slot):
            $patientid = $slot->get_patient();
            $patient = Patient::retrieve_patient_by_id($patientid);
            $str = "[\"" . $patient->get_firstname() . "\"," . $patient->get_email() . "\",\"" . $slot->get_appointmentschedule()->get_date() . "\",\"" . $slot->get_appointmentschedule()->get_time()."\"]" ; 
            echo $str;
            $pnum++;
            if ($pnum == $p_perday) :
                echo "";
            else:
                echo ",";
            endif;
            endforeach; 
        ?>

    </body>
</html>