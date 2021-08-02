<?php

session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';

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

if ($_SERVER["REQUEST_METHOD"] == "POST"):

    if (isset($_POST['load_specialist'])):

        if (isset($_POST['facilityid'])):
            $personnel_by_specialisation_arr = Medical_Personnel::retrieve_personnel_by_facility_spec($_POST['facilityid'], false);

            $personnel_arr = StringUtils::object_to_array($personnel_by_specialisation_arr);
            $encode_personnel = json_encode($personnel_arr);
            echo $encode_personnel;

        endif; # -- END FACILITY ID

    endif; # -- END LOAD SPECIALIST ARRAY


endif; # -- END POST REQUEST
/*
 * Make Sure User Will Be Redirected Away If Accessing This File Directly
 */
if ($_SERVER["REQUEST_METHOD"] == "GET"):
    header("Location:/");
endif;
?>
