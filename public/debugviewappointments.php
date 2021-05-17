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

// -- Check If User Is Signed In (When Redirect or Load The Page) -- //
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_SESSION["user"])) {
        $user = unserialize($_SESSION["user"]);
        echo $user . "<br/>";
        $email['email'] = $user->get_email();
        $upcoming_appt = Appointment_Record::get_upcoming_appointments($email);
        if($upcoming_appt == NULL){
            echo "NUll";
        } else {
            echo $upcoming_appt;
        }
    }
}
?>