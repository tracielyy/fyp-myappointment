<?php
/* Load Config File */
require_once '../resources/config.php';


require_once TIME_MOD . '/Time.php';
require_once FACILITY_MOD . '/Operating_Hours.php';
require_once DB_MOD . '/DbQuery.php';
require_once DB_MOD . '/Database.php';

require_once APPT_MOD . '/Normal_Slot.php';
require_once APPT_MOD . '/Special_Slot.php';



# -- Find The Patient Count (HARDCODE)
$patient_per_day = Normal_Slot::patient_count_per_date("mf001", "15-07-2021");
$patient_per_day += Special_Slot::patient_count_per_date("mf001","15-07-2021");

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
       &nbsp;<?php echo $patient_per_day;?>
    </body>
</html>