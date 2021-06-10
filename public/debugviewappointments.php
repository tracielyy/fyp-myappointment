<!--
   Developed By FYP-21-S2-24
-->
<!-- This File Is Solely Used For Debugging -->
<?php
session_start();
/* Load Config File */
require_once '../resources/config.php';
require_once ENTITIES_PATH . '/Account_User.php';
require_once ENTITIES_PATH . '/Appointment_Record.php';
require_once ENUMS_PATH . '/User_Type.php';
require_once FUNCTIONS_PATH . '/PatientFunctions.php';
require_once FUNCTIONS_PATH . '/AccountUserFunctions.php';

// -- Check If User Is Signed In (When Redirect or Load The Page) -- //
if ($_SERVER['REQUEST_METHOD'] == "GET") :
    echo "hello";
    if (isset($_SESSION["user"])) :

        $user = unserialize($_SESSION["user"]);
        $user_type = $user->get_usertype();
        echo $user . "<br/>";

        // Need To Make Sure User Is `Patient`
        if (User_Type::check_user_type(User_Type::PATIENT, $user_type)) {
            $email['credentials']['email'] = $user->get_email();
            //            $email = array ("credentials"=> array("email" => $user->get_email()));
            // -- Upcoming Appointments -- //
            $upcoming_arr = PatientFunctions::get_upcoming_appointments($email);
            if ($upcoming_arr == NULL) {
                echo "<br/>No Upcoming Appointments";
            } else {
                // For Each Upcoming Appointment Record
                echo "<br/>____________________<br/>";
                echo "<div style='color:red;'>UPCOMING APPT</div>";
                echo "<br/>____________________<br/>";
                foreach ($upcoming_arr as $record):
                    echo "<br/>--------------------<br/>";
                    echo $record; // Implicitly calling toString
                    //echo "This is my appointment status " . $record->get_appointmentstatus(); // Return String
                    //$record->get_facility(); will return `Medical_Facility` object
                    // echo "Location Name: " . $record->get_facility()->get_address();
                endforeach;
            }

            // -- Missed Appointments -- //
            $missed_arr = PatientFunctions::get_missed_appointments($email);
            if ($missed_arr == NULL) {
                echo "<br/>No Missed Appointments";
            } else {
                // For Each Missed Appointment Record
                echo "<br/>____________________<br/>";
                echo "<div style='color:red;'>MISSED APPT</div>";
                echo "<br/>____________________<br/>";
                foreach ($missed_arr as $record):
                    echo "<br/>--------------------<br/>";
                    echo $record; // Implicitly calling toString
                    //echo "This is my appointment status " . $record->get_appointmentstatus(); // Return String
                    //$record->get_facility(); will return `Medical_Facility` object
                    // echo "Location Name: " . $record->get_facility()->get_address();
                endforeach;
            }
        } else {
            # Possible Redirect To Index.php
            header("Location:debugIndex.php");
        }
    endif;
endif;
?>