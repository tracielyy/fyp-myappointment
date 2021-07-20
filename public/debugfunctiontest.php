<?php
session_start();
/* Load Config File */
require_once '../resources/config.php';
require '../vendor/autoload.php';
require_once TIME_MOD . '/Time.php';
require_once FACILITY_MOD . '/Operating_Hours.php';
require_once USER_MOD . '/Account_User.php';

require_once DB_MOD . '/DbQuery.php';
require_once DB_MOD . '/Database.php';
require_once DB_MOD . '/DbStorage.php';


require_once APPT_MOD . '/Normal_Slot.php';
require_once APPT_MOD . '/Special_Slot.php';

require_once SECURE_MOD . '/ValidateIC.php';

require_once USER_MOD . '/Medical_Personnel.php';
require_once EMAIL_MOD . '/EmailTemplate.php';
require_once EMAIL_MOD . '/EmailVerify.php';

if (isset($_SESSION['email_verified'])):

    unset($_SESSION['email_verified']);
endif;

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
        <?php // echo var_dump($slot_arr);          ?>

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
        $file = $db_storage->retrieve_data_url("facility/facilityicon/sunset.png");
        echo "<img src='{$file}' width=100 height=100/>";


        echo nl2br(PHP_EOL . "Testing SAVE Image -- HARDCODE --" . PHP_EOL);
        ?>
        <form id="facility_form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
            <input type="text" id="testname" name="testname" value="hey"/>

            <input type="file" id="facility_icon" name="facility_icon" accept=".png"/>
            <button type="submit" class="action back btn btn-sm btn-outline-primary">Submit</button>

        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST"):

            if (isset($_POST['testname'])):
                echo $_POST['testname'];
            endif;

            // -- Save The Facility Icon
            if (isset($_FILES['facility_icon'])):
                if ($_FILES["facility_icon"]["error"] > 0) {
                    echo "Error: " . $_FILES["facility_icon"]["error"] . "<br />";
                } else {
                    echo var_dump($_FILES["facility_icon"]);
//                    $file_name = $_FILES["facility_icon"]["name"];
//                    $size = ($_FILES["facility_icon"]["size"] / 1024); # In kb
//                    $type = $_FILES["facility_icon"]["type"];
//                    $tmp_path = $_FILES["facility_icon"]["tmp_name"];
//
//                    echo "Upload: " . $file_name . "<br />";
//                    echo "Type: " . $type . "<br />";
//                    echo "Size: " . $size . " Kb<br />";
//                    echo "Stored in: " . $tmp_path;
//                    echo "<img src='{$_FILES["facility_icon"]["tmp_name"]}' />";
                }
                echo "here";
                $db_storage = new DbStorage();
//                $test_path = "C:\Users\yanyi\OneDrive - University of Wollongong\UOW (SIM)\YEAR 3\Year 3 Quarter 3 - 4\CSIT321 - FYP\img\test";
//                $db_storage->store_data($test_path, 'facility/facilityicon/' . 'test001.png');
//                $db_storage->store_data($type, (int) $size, $tmp_path, 'facility/facilityicon/' . $file_name);

            endif;

        endif;
        echo nl2br(PHP_EOL . "Testing Display Personnel By Facility -- HARDCODE --" . PHP_EOL);
//        $personnel_by_specialisation_arr = Medical_Personnel::retrieve_personnel_by_facility("mf001");
//        foreach ($personnel_by_specialisation_arr as $specialisation => $personnels):
//            echo "<br/><br/>" . $specialisation . "<br/>";
//            foreach ($personnels as $p):
//                echo $p->get_firstname() . ", ";
//            endforeach;
//        endforeach;

        echo nl2br(PHP_EOL . "Testing Password Change -- HARDCODE --" . PHP_EOL);

        function user_change_password(string $user_email, string $current_password, string $new_password): bool {
            $success = Account_User::change_password($user_email, $current_password, $new_password);
            if ($success):
                EmailTemplate::template_passwordchanged("yanying25@outlook.com");
                return true;
            endif;
            return false;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST"):
            if (isset($_POST['changepassword'])):
                $user_email = "yanying25@outlook.com";
                $current_password = "Tracie@123";
                $new_password = "Line@123";
                $status = user_change_password($user_email, $current_password, $new_password);
            endif;
        endif;
        ?>

        <form id="change_password" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <button type="submit" name="changepassword" class="action back btn btn-sm btn-outline-primary">Change Password</button>
        </form>


        <?php
        echo nl2br(PHP_EOL . "Testing Email Verify & Change-- HARDCODE --" . PHP_EOL);

        function otp_valid(Time $requestedon): bool {
            $current_datetime = new Time();

            // -- Check For Expiration Date
            $time_diff = $current_datetime->datetime_second_diff($current_datetime, $requestedon);
            $validity_duration = 60 * 60; # 1 hour
            if ($time_diff < $validity_duration):
                return true; # -- NOT EXPIRED
            endif;
            return false; # -- EXPIRED
        }

        // -- Checks If OTP Matches
        function compare_otp(string $input_otp, string $db_otp): bool {

            if ($input_otp === $db_otp):

                return true; # -- OTP MATCHES
            endif;
            echo "otp false";
            return false; # -- OTP DOES NOT MATCH
        }

        function verify_user_email(string $user_email, string $input_otp): void {

            # STEP 0: INITIALISE OTP INFO
            $email_verify = new EmailVerify($user_email);
            $email_verify->set_verify_data();

            # STEP 1: CHECK IF THE EMAIL HAS REQUESTED FOR OTP & CHECK IF THE OTP IS STILL VALID
            if (($email_verify->has_requested()) && (otp_valid($email_verify->get_requestedon()))):

                # STEP 2: COMPARE THE OTP
                if (compare_otp($input_otp, $email_verify->get_otp())):
                    $_SESSION['email_verified'] = true;
                else:
                    $_SESSION['email_verified'] = false;
                endif;
            endif;
        }

        function user_change_email(string $current_email, string $new_email, string $password): bool {
            $success = Account_User::change_email($current_email, $new_email, $password);
            if ($success):
                EmailTemplate::template_emailchanged($current_email, $new_email);
                return true;
            endif;
            return false;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST"):
            if (isset($_POST['verifyemail'])):
                if (isset($_SESSION['email_verified'])):
                    if ($_SESSION['email_verified'] == true):
                        $otp_requester = $_POST['user_email'];
                        // Generate OTP
                        $otp = StringUtils::generate_otp(6);

                        // Update OTP In Database
                        Account_User::update_email_otp($otp_requester, $otp);

                        // Send OTP To OTP Requester
                        EmailTemplate::template_emailotp($otp_requester, $otp);

//                echo "<style>#submit_email_otp{display:inline-block;}</style>";
                    endif;
                endif;

            endif;
            if (isset($_POST['submitotp'])):
                verify_user_email("lingyanying@gmail.com", $_POST['email_otp']);
            endif;
            if (isset($_POST['changeemail'])):
//                $current_email = "yanying25@outlook.com";
//                $new_email = "lingyanying@gmail.com";
                $new_email = "yanying25@outlook.com";
                $current_email = "lingyanying@gmail.com";
                $password = "Line@123";
                $status = user_change_email($current_email, $new_email, $password);
            endif;
        endif;
        ?>
        <form id="verify_email" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <input type="text" name="user_email" placeholder="New Email" />
            <button type="submit" name="verifyemail" class="action back btn btn-sm btn-outline-primary">Verify Email</button>
        </form>
        <form id="submit_email_otp" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" >
            <input type="text" name="email_otp" placeholder="Email OTP" />
            <button type="submit" name="submitotp" class="action back btn btn-sm btn-outline-primary">Submit OTP</button>
        </form>
        <?php
        if (isset($_SESSION['email_verified'])):
            if ($_SESSION['email_verified'] == true):
                echo "Email Verified";
            endif;
        else:
            echo "Email verified not set";
        endif;
        ?>
        <form id="change_email" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" >
            <button type="submit" name="changeemail" class="action back btn btn-sm btn-outline-primary">Change Email</button>
        </form>
        <?php
        echo nl2br(PHP_EOL . "Testing BASIC DETAILS CHANGE-- HARDCODE --" . PHP_EOL);

        function user_change_basic_details(string $email, string $contact, string $address): bool {
            $success = Account_User::change_basic_details($email, $contact, $address);
            if ($success):
                EmailTemplate::template_basicinfochanged($email);
                return true;
            endif;
            return false;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST"):
            if (isset($_POST['changebasicdetails'])):
                $email = "yanying25@outlook.com";
                $contact = "83452990";

//                $contact = "";
//                $address = "345 Bubble Town";
                $address = "";

                $status = user_change_basic_details($email, $contact, $address);
            endif;
        endif;
        ?>       
        <form id="change_basic_details" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <button type="submit" name="changebasicdetails" class="action back btn btn-sm btn-outline-primary">Change Basic Details</button>
        </form>

        <?php
        echo nl2br(PHP_EOL . "Testing VALIDATE NRIC -- HARDCODE --" . PHP_EOL);
        if ($_SERVER["REQUEST_METHOD"] == "POST"):
            if (isset($_POST['validate_nric'])):
                $validate_nric = new ValidateIC($_POST['nric']);
                if ($validate_nric->validate_nric()):
                    echo "Valid";
                else:
                   echo "Invalid";
                endif;
            endif;
        endif;
        ?>
        <form id="validate_nric" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <input type="text" name="nric" placeholder="NRIC" />
            <button type="submit" name="validate_nric" class="action back btn btn-sm btn-outline-primary">Validate NRIC</button>
        </form>


    </body>
</html>