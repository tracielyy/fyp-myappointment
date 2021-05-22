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

// -- Check If User Is Signed In (When Redirect or Load The Page) -- //
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_SESSION["user"])) {
        $user = unserialize($_SESSION["user"]);
        echo $user . "<br/>";

        // Need To Make Sure User Is `Patient`
        if ($user->get_usertype() == User_Type::PATIENT) {
            $email['credentials']['email'] = $user->get_email();
//            $email = array ("credentials"=> array("email" => $user->get_email()));
            // -- Upcoming Appointments -- //
            $upcoming_arr = Appointment_Record::get_upcoming_appointments($email);
            if ($upcoming_arr == NULL) {
                echo "<br/>No Upcoming Appointments";
            } else {
                // For Each Upcoming Appointment Record
                echo "<br/>____________________<br/>";
                echo "<div style='color:red;'>UPCOMING APPT</div>";
                echo "<br/>____________________<br/>";
                foreach ($upcoming_arr as $record) {
                    echo "<br/>--------------------<br/>";
                    echo $record; // Implicitly calling toString
                    //echo "This is my appointment status " . $record->get_appointmentstatus(); // Return String
                    //$record->get_facility(); will return `Medical_Facility` object
                    // echo "Location Name: " . $record->get_facility()->get_address();
                }
            }

            // -- Missed Appointments -- //
            $missed_arr = Appointment_Record::get_missed_appointments($email);
            if ($missed_arr == NULL) {
                echo "<br/>No Missed Appointments";
            } else {
                // For Each Missed Appointment Record
                echo "<br/>____________________<br/>";
                echo "<div style='color:red;'>MISSED APPT</div>";
                echo "<br/>____________________<br/>";
                foreach ($missed_arr as $record) {
                    echo "<br/>--------------------<br/>";
                    echo $record; // Implicitly calling toString
                    //echo "This is my appointment status " . $record->get_appointmentstatus(); // Return String
                    //$record->get_facility(); will return `Medical_Facility` object
                    // echo "Location Name: " . $record->get_facility()->get_address();
                }
            }
        } else {
            # Possible Redirect To Index.php
            header("Location:debugIndex.php");
        }
    }
}
?>