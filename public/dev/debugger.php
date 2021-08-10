<?php
session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';

require_once ENUMS_PATH . '/Health_Info_Type.php';
require_once ENUMS_PATH . '/User_Type.php';

require_once TIME_MOD . '/Time.php';
require_once FACILITY_MOD . '/Operating_Hours.php';
require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Patient.php';

require_once MEDDOC_MOD . '/Medical_Record.php';
require_once HINFO_MOD . '/Health_Info.php';

require_once DB_MOD . '/DbQuery.php';
require_once DB_MOD . '/Database.php';
require_once DB_MOD . '/DbStorage.php';

require_once APPT_MOD . '/Normal_Slot.php';
require_once APPT_MOD . '/Special_Slot.php';

require_once SECURE_MOD . '/ValidateIC.php';
require_once SECURE_MOD . '/Security.php';

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
//$slot_arr = Special_Slot::retrieve_booked_slots_by_date("wynterz2525@gmail.com", "15-07-2021");
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
        <?php // echo $patient_per_day;          ?>
        <?php // echo var_dump($slot_arr);          ?>

        <?php
//       $slots_arr = Normal_Slot::retrieve_free_slots_by_date("mf001", Appointment_Type::DOCTOR_CONSULTATION, "15-07-2021");
//        $slots_arr = Special_Slot::retrieve_free_slots_by_date("mf001", "wynterz2525@gmail.com", "15-07-2021");
//        foreach ($slot_arr as $slot):
//            echo nl2br($slot->get_slotid() . PHP_EOL);
//        endforeach;
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
//                EmailTemplate::template_passwordchanged("yanying25@outlook.com");
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

        function verify_user_email(string $user_email, string $input_otp): bool {

            # STEP 0: INITIALISE OTP INFO
            $email_verify = new EmailVerify($user_email);
            $email_verify->set_verify_data();

            # STEP 1: CHECK IF THE EMAIL HAS REQUESTED FOR OTP & CHECK IF THE OTP IS STILL VALID
            if (($email_verify->has_requested()) && (otp_valid($email_verify->get_requestedon()))):

                # STEP 2: COMPARE THE OTP
                if (compare_otp($input_otp, $email_verify->get_otp())):
                    return true;
                else:
                    return false;
                endif;
            endif;
            return false;
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
        <?php
        echo nl2br(PHP_EOL . "Testing Medical Record ID Generation -- HARDCODE --" . PHP_EOL);
//        $user_doc_id = Account_User::retrieve_user_doc_id("yanying25@outlook.com");
//        echo Medical_Record::generate_medical_record_id($user_doc_id);

        echo nl2br(PHP_EOL . "Testing Login Redirect Lcoation -- HARDCODE --" . PHP_EOL);
//        echo LOGIN_WEB;
//        header("Location:". LOGIN_WEB);

        echo nl2br(PHP_EOL . "Testing Create Medical Record -- HARDCODE --" . PHP_EOL);
        $spec_medical = array(
            'facilityid' => 'mf001',
            'slotid' => '1002~24-07-2021~S1990250A',
            'appointmenttype' => Appointment_Type::SPECIALIST_CONSULTATION,
            'practitioner' => 'S1990250A',
            'diagnosisdesc' => 'Just a minor flu',
            'prescriptions' => []
        );
//        Medical_Record::create_medical_record('S1499902G', $spec_medical);
//        $mr = Medical_Record::create_medical_record('S1499902G', $spec_medical);
        echo nl2br(PHP_EOL . "Testing Create Medical Record -- HARDCODE --" . PHP_EOL);
        $pt = 'S1499902G';

//        $mr_data = Medical_Record::retrieve_medical_record('S1499902G', 'mrid-2021-1000');
//        echo $mr_data->get_medicalrecordid();
        // -- RETRIEVE MEDICAL RECORD BY APPOINTMENT TYPE
        function get_med_record_apptslot(Medical_Record $medical_record): ?Appointment_Slot {
            $slotid = $medical_record->get_slotid();
            $fid = $medical_record->get_facility()->get_facilityid();
            $appt_slot = null;
            switch ($medical_record->get_appointmenttype()):
                case Appointment_Type::CHECK_UP:
                case Appointment_Type::DOCTOR_CONSULTATION:
                    $appt_slot = Normal_Slot::retrieve_apptslot_by_id($slotid, $fid);
                    break;
                case Appointment_Type::SPECIALIST_CONSULTATION:
                    $appt_slot = Special_Slot::retrieve_apptslot_by_id($slotid);
                    break;
            endswitch;
            return $appt_slot;
        }

        echo nl2br(PHP_EOL . "Testing Retrieve Medical Record -- HARDCODE --" . PHP_EOL);

        // When Click On 1 Of The Patient's Appt Medical Record
//        $appt_slot = get_med_record_apptslot($mr_data);
//
//        // Patient Appointment Info
//        $appt_type = $mr_data->get_appointmenttype();
//        $appt_facility = $mr_data->get_facility()->get_facilityname();
//        $appt_date = $appt_slot->get_appointmentschedule()->get_date();
//        $appt_time = $appt_slot->get_appointmentschedule()->get_time();
//
//        echo nl2br(PHP_EOL . "Consultation Type: " . $appt_type . PHP_EOL . "Facility: " . $appt_facility . PHP_EOL .
//                "Date: " . $appt_date . PHP_EOL . "Time: " . $appt_time);
//        echo nl2br(PHP_EOL . "Testing Multiple Return Type-- HARDCODE --" . PHP_EOL);
//        function multi_return_type (string $str = "1"): string|int{
//            return $str;
//        }
//        echo multi_return_type("omg");
        echo nl2br(PHP_EOL . "Testing Update Medical Record-- HARDCODE --" . PHP_EOL);
//        $mrid = '035f05cbf562436d8f30';
//        $pt_id = 'S1499902G';
////        $dr_id = 'S1990250A';
//        $dr_id = 'S1990250D'; // Fake id
//
//        $diagnosisdesc = "This is a flu. Fever for 4 days straight";
//        $prescriptions = array('Fever Med', 'Flu Med');
//
//        $medical_record_update = Medical_Record::update_medical_record($dr_id, $pt_id, $mrid, $diagnosisdesc, $prescriptions);
//        echo ($medical_record_update) ? "true" : "false";

        echo nl2br(PHP_EOL . "Testing HASH-- HARDCODE --" . PHP_EOL);
//        $password = "Line@123";
//        $sec = new Security();
//        $hash_password = $sec->hash($password);
//
//        echo 'hashed password: ' . $hash_password . "<br/>";
//
//        $input_password = "Line@123";
////        $input_hash = $sec->hash($input_password);
//        echo "input_hashed: " . $input_hash;
//
//        echo $password_verify = $sec->compareHash($input_password, $hash_password);
        echo nl2br(PHP_EOL . "Testing Change Password -- HARDCODE --" . PHP_EOL);
//        $c_password = "Admin@123";
//        $n_password = "Admin@888";
//        $my_email = "tracieqwynn@gmail.com";
//        $change_pw_result = Account_User::change_password($my_email, $n_password, $c_password);
//        echo ($change_pw_result) ? "change success" : "change unsucessful";

        echo nl2br(PHP_EOL . "Testing Nric Exist -- HARDCODE --" . PHP_EOL);
        $nric_plaintext = "S9603505E";
        $nric_plaintext2 = "S9173333A";

        $secure = new Security();
        $en_nric = "SWQ1NkNlMTVyMFg5MnFUZklleERHZz09OjogrYEevxerJdoF34CcQumd";
//        $de_nric = $secure->decrypt($en_nric);
//        echo $de_nric;
//        echo "<br/>" . $e_plainnric = $secure->encrypt($nric_plaintext);
//        echo "<br/>" . $e_plainnric = $secure->encrypt($nric_plaintext);
//        echo "<br/>" .  $secure->encrypt($nric_plaintext2);
//
//        echo "<br/>" . $secure->decrypt($secure->encrypt($nric_plaintext));
//        echo "<br/>" . $secure->decrypt($secure->encrypt($nric_plaintext));

        if ($_SERVER["REQUEST_METHOD"] == "POST"):
            if (isset($_POST['check_nric'])):
                $nric_exist = Account_User::check_nric_exist($_POST['cnric']);
                echo $nric_exist ? "nric exist" : "nric does not exist";
            endif;
        endif;
        ?>

        <form id="check_nric" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <input type="text" name="cnric" placeholder="NRIC" />
            <button type="submit" name="check_nric" class="action back btn btn-sm btn-outline-primary">Check NRIC</button>
        </form>

        <?php
        echo nl2br(PHP_EOL . "Testing Pagination -- HARDCODE --" . PHP_EOL);
//
//        $doctor_by_fid = Medical_Personnel::retrieve_personnel_by_facility('mf001');
////        echo "<pre>";
////        var_dump($doctor_by_fid);
////        echo "</pre>";
//        $last_doc = array();
//        echo "First Page Query <br/>";
//        foreach ($doctor_by_fid as $doctor):
//            echo $doctor->get_firstname() . " " . $doctor->get_lastname();
//            echo "<br/>";
//            $last_doc = array($doctor->get_firstname(), $doctor->get_lastname(), $doctor->get_email());
//        endforeach;
//        echo "The last doctor email is " . $last_doc[2];
//        $doctor_by_id2 = Medical_Personnel::retrieve_personnel_by_facility('mf001', true, $last_doc);
//        echo "<br/><br/>Second Page Query<br/>";
//
//        foreach ($doctor_by_id2 as $doctor):
//            echo $doctor->get_firstname() . " " . $doctor->get_lastname();
//            echo "<br/>";
//            $last_doc = array($doctor->get_firstname(), $doctor->get_lastname(), $doctor->get_email());
//        endforeach;
//        echo "<br/>Third Page Query <br/>";
//        $doctor_by_id2 = Medical_Personnel::retrieve_personnel_by_facility('mf001', true, $last_doc);
//
//        foreach ($doctor_by_id2 as $doctor):
//            echo $doctor->get_firstname() . " " . $doctor->get_lastname();
//            echo "<br/>";
//            $last_doc = $doctor->get_email();
//        endforeach;
//        $fac_arr = Medical_Facility::retrieve_paginate_facilities();
//        foreach ($fac_arr as $doc):
//            echo $fid = $doc->get_facilityid();
//        endforeach;

        echo nl2br(PHP_EOL . "Testing Health Info -- HARDCODE --" . PHP_EOL);
        $health_info_types = Health_Info_Type::get_constants();
        $all_health_info = Health_Info::retrieve_all_healthinfo(); # Page One
        if (empty($all_health_info) || $all_health_info == null):
            echo "There Are No Health Info Records Available";
        else:
        endif;
        $health_info_form = array(
            'title' => '',
            'descriptions' => '',
            'type' => ''
        );
        if ($_SERVER["REQUEST_METHOD"] == "POST"):

            if (isset($_POST['submit_health_info'])):
                /* Load Data to Array */
                foreach ($_POST as $key => $value) :
                    if (isset($health_info_form[$key])) :
                        $health_info_form[$key] = htmlspecialchars($value);
                        $validArr[$key] = False; // Set All Field Validation Check As False

                    endif;
                endforeach;
            endif;
        endif;
        ?>
        <form id="health_info_form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <input type="text" name="title" placeholder="Title" value="<?php echo $health_info_form['title']; ?>" /><br/><br/>
            <textarea style="resize:none;" rows="10" cols='50' name="descriptions" 
                      placeholder="Descriptions" ><?php echo $health_info_form['descriptions']; ?></textarea><br/><br/>
            <select>
                <?php
                foreach ($health_info_types as $type):
                    ?>
                    <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
                    <?php
                endforeach;
                ?>
            </select><br/><br/>
            <button type="submit" name="submit_health_info" class="action back btn btn-sm btn-outline-primary">
                Add Health Info
            </button>
        </form>
        <?php
//        $update_status = Health_Info::update_healthinfo("hinfo-10003", "Change", "New Description");
//        echo ($update_status) ? "updated health info" : "not updated";
//        Health_Info::delete_healthinfo("hinfo-10007");
        ?>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <form id="edit_form" method="get" action="<?php echo FADMIN_WEB . "/edit/healthinfo.php"; ?>">
            <input type="hidden" id ="id"  name="id" value="hinfo-10003"/>
            <button type="submit" name="edit_health_info" id="edit_health_info"  class="action back btn btn-sm btn-outline-primary">
                Edit Health Info
            </button>
        </form>
        <?php
        echo nl2br(PHP_EOL . "Testing Create Special Slot -- HARDCODE --" . PHP_EOL);
//        $slot = Special_Slot::retrieve_apptslot_by_id('1001~03-09-2021~S8967399B', 'mf001');
//        echo var_dump($slot);
//        $spec_slot_arr = Special_Slot::retrieve_free_slots_by_date("mf001", "testdoc001@gmail.com", "03-09-2021");
//        foreach ($spec_slot_arr as $slot):
//            echo nl2br($slot->get_appointmentschedule()->get_time() . PHP_EOL);
//        endforeach;
//        $test = Medical_Personnel::retrieve_personnel_by_facility_spec("mf001");
//        echo var_dump($test);

        if (isset($_POST['create_special_slot'])):
            $test_doctor_email = 'testdoc001@gmail.com';
            $date = "03-09-2021";
            $time = "14:40";
            $facilityid = 'mf001';
            $status = Special_Slot::create_slot($test_doctor_email, $facilityid, $date, $time);
            echo ($status) ? "true" : "false";
        endif;
        ?>
        <form id="health_info_form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <button type="submit" name="create_special_slot" class="action back btn btn-sm btn-outline-primary">
                Create A Special Slot
            </button>
        </form>
        <?php

        function retrieve_user(string $type): array {
            $user_arr = array();
            $arr = Account_User::retrieve_user_by_type($type, 10);
            foreach ($arr as $a):
                $user_arr[] = initialise_user($a, $type);
            endforeach;
            return $user_arr;
        }

        function initialise_user(array $user, string $type): Account_User {
            switch ($type):
                case User_Type::PATIENT:
                    return Patient::initialise_patient($user);
                case User_Type::MEDICAL_PERSONNEL:
                    return Medical_Personnel::initialise_medical_personnel($user);
                case User_Type::FACIILITY_ADMIN:
                    return Facility_Admin::initialise_facility_admin($user);
            endswitch;
        }
        
        $user_list = retrieve_user(User_Type::FACIILITY_ADMIN);
        echo var_dump($user_list);
        foreach($user_list as $user):
            echo $user->get_email() . "<br/>";
        endforeach;
        
        ?>

    </body>
</html>