<?php

/* Load Config File */
require_once '../resources/config.php';
require '../vendor/autoload.php';

use Google\Cloud\Firestore\FieldValue;

require_once DB_MOD . '/DbQuery.php';
require_once DB_MOD . '/Database.php';
require_once APPT_MOD . '/Normal_Slot.php';
require_once APPT_MOD . '/Special_Slot.php';
require_once TIME_MOD . '/Time.php';
require_once USER_MOD . '/Medical_Personnel.php';

ini_set('max_execution_time', 0);

/* scripts to autogenerate data to database */
$db = new DbQuery();

# -- Medical Facility
$facilityid = "mf001";

# -- Add Dr Consult Appt Slot
$dates_arr = Time::get_date_from_range("01-07-2021", "31-08-2021");

$dr_consultation_slot = array(
    "08:00",
    "08:20",
    "08:40",
    "09:00",
    "09:20",
    "09:40",
    "10:00",
    "10:20",
    "10:40",
    "11:00",
    "11:20", # Last Slot Before Lunch
    "13:20",
    "13:40",
    "14:00",
    "14:20",
    "14:40",
    "15:00",
    "15:20",
    "15:40",
    "16:00",
    "16:20",
    "16:40",
    "17:00",
    "17:20",
    "17:40" # Last Slot Before Day End
);
//$path = Database::MEDICAL_FACILITY . "/" . $facilityid . "/" . Appointment_Type::DOCTOR_CONSULTATION;
# Loop Dr Consultation
//foreach ($dates_arr as $date):
//    $count = 0;
//
//    #-- Initialise The Batch
//    $mydb = $db->get_db();
//    $batch = $mydb->batch();
//
//    // -- BATCH ADD
//    $date_ref = $mydb->collection($path)->document($date);
//    $batch->set($date_ref, [
//        'date' => $date
//    ]);
//    $doc_path = $path . "/" . $date . "/" . Database::SLOTS;
//
//    // -- BATCH DELETE
//    $del_id = "1001";
//    for ($i = 0; $i < 26; $i++):
//        $del_slot_id = $del_id . "~" . $date . "~" . Appointment_Type::DOCTOR_CONSULTATION;
//        $doc_ref = $mydb->collection($doc_path)->document($del_slot_id);
//        $batch->delete($doc_ref);
//        ++$del_id;
//    endfor;
//
//    $id = "1001";
//    foreach ($dr_consultation_slot as $time):
//        $slotid = $id . "~" . $date . "~" . Appointment_Type::DOCTOR_CONSULTATION;
//        $slot_ref = $mydb->collection($doc_path)->document($slotid);
//        $data_info = array(
//            "slotid" => $slotid,
//            "time" => $time,
//            "doctorlist" => array(),
//            "patientlist" => array(),
//        );
//        $batch->set($slot_ref, $data_info);
//        echo ++$count . "  ";
//        echo var_dump($data_info) . "<br/>";
//        ++$id;
//    endforeach;
//    echo "<br/>";
//    $batch->commit();
//endforeach;


$checkup_slot = array(
    "08:00",
    "08:30",
    "09:00",
    "09:30",
    "10:00",
    "1030",
    "11:00", # Last Slot Before Lunch
    "13:30",
    "14:00",
    "14:30",
    "15:00",
    "15:30",
    "16:00",
    "16:30",
    "17:00",
    "17:30" # Last Slot Before Day End
);

//# -- Add Check Up Appt Slot
//$path = Database::MEDICAL_FACILITY . "/" . $facilityid . "/" . Appointment_Type::CHECK_UP;
//foreach ($dates_arr as $date):
//    $count = 0;
//    $date_info = array("date" => $date);
//    $db->insert_data($path, $date_info, False, $date);
//
//    $doc_path = $path . "/" . $date . "/" . Database::SLOTS;
//
//
//    // -- BATCH DELETE
//    $mydb = $db->get_db();
//    $batch = $mydb->batch();
//    $del_id = "1001";
//    for ($i = 0; $i < 16; $i++):
//        $del_slot_id = $del_id . "~" . $date . "~" . Appointment_Type::CHECK_UP;
//        $doc_ref = $mydb->collection($doc_path)->document($del_id);
//        $batch->delete($doc_ref);
//        ++$del_slot_id;
//    endfor;
//    $batch->commit();
//
//    $id = "1001";
//    foreach ($checkup_slot as $time):
////        $slotid = Normal_Slot::generate_slot_id($facilityid, Appointment_Type::DOCTOR_CONSULTATION, $date);
//        $slotid = $id . "~" . $date . "~" . Appointment_Type::CHECK_UP;
//        $data_info = array(
//            "slotid" => $slotid,
//            "time" => $time,
//            "doctorlist" => array(),
//            "patientlist" => array(),
//        );
//        echo ++$count . "  ";
//        $db->insert_data($doc_path, $data_info, False, $slotid);
//        echo var_dump($data_info) . "<br/>";
//        ++$id;
//    endforeach;
//    echo "<br/>";
//endforeach;

$mp_S8967399B = array(
    "credentials" => array("email" => "testdoc001@gmail.com", "password" => "Test@123"),
    "practitionerinfo" => array("facilityids" => ["mf001"], "licensenumber" => "doc-002", "specialisation" => "General"),
    "profile" => array(
        "nric" => "S8967399B",
        "address" => "35 Tampines Road",
        "contactnumber" => "98752364",
        "dob" => "03-05-1990",
        "gender" => "M",
        "name" => array(
            "firstname" => "Keith",
            "lastname" => "Bradley"
        )
    )
);

$mp_S1990250A = array(
    "credentials" => array("email" => "wynterz2525@gmail.com", "password" => "Test@123"),
    "practitionerinfo" => array("facilityids" => ["mf001"], "licensenumber" => "doc-001", "specialisation" => "Cardiology"),
    "profile" => array(
        "nric" => "S1990250A",
        "address" => "34 Medical Avenue",
        "contactnumber" => "82341122",
        "dob" => "08-11-1986",
        "gender" => "F",
        "name" => array(
            "firstname" => "Darc",
            "lastname" => "Wynter"
        )
    )
);

$mp_S8898250I = array(
    "credentials" => array("email" => "checkup@gmail.com", "password" => "Test@123"),
    "practitionerinfo" => array("facilityids" => ["mf001"], "licensenumber" => "doc-001", "specialisation" => "General"),
    "profile" => array(
        "nric" => "S8898250I",
        "address" => "231M Golden Avenue",
        "contactnumber" => "82336122",
        "dob" => "28-05-1980",
        "gender" => "M",
        "name" => array(
            "firstname" => "Walter",
            "lastname" => "Jones"
        )
    )
);
Medical_Personnel::create_medical_personnel($mp_S8898250I); # General Check Up
//$doctor_checkup = Medical_Personnel::initialise_medical_personnel($mp_S8898250I); 
//$doctor_general = Medical_Personnel::initialise_medical_personnel($mp_S8967399B);
//$doctor_cardio = Medical_Personnel::initialise_medical_personnel($mp_S1990250A);
//Medical_Personnel::create_medical_personnel($mp_S8967399B); # General
//Medical_Personnel::create_medical_personnel($mp_S1990250A); # Cardiology
//-- SPECIALIST
//$eg_medical_personnel = $mp_S1990250A['profile']['nric'];
////$eg_medical_personnel = "MedicalPersonnel001";
//$path = Database::ACCOUNT_USER . "/" . $eg_medical_personnel . "/" . Database::APPOINTMENT_SLOTS;
//foreach ($dates_arr as $date):
//    $count = 0;
//
//    #-- Initialise The Batch
//    $mydb = $db->get_db();
//    $batch = $mydb->batch();
//
//    // -- BATCH ADD
//    $date_ref = $mydb->collection($path)->document($date);
//    $batch->set($date_ref, [
//        'date' => $date
//    ]);
//    $doc_path = $path . "/" . $date . "/" . Database::SLOTS;
//
//    // -- BATCH DELETE
//    $del_id = "1001";
//    for ($i = 0; $i < 25; $i++):
//        $del_slot_id = $del_id . "~" . $date . "~" . $eg_medical_personnel;
//        $doc_ref = $mydb->collection($doc_path)->document($del_slot_id);
//        $batch->delete($doc_ref);
//        ++$del_id;
//    endfor;
//
//    $id = "1001";
//    foreach ($checkup_slot as $time):
//        $slotid = $id . "~" . $date . "~" . $eg_medical_personnel;
//        $slot_ref = $mydb->collection($doc_path)->document($slotid);
//        $data_info = array(
//            "available" => True,
//            "slotid" => $slotid,
//            "time" => $time,
//            "patient" => "",
//            "facilityid" => "mf001"
//        );
//        $batch->set($slot_ref, $data_info);
//        echo ++$count . "  ";
//        echo var_dump($data_info) . "<br/>";
//        ++$id;
//    endforeach;
//    echo "<br/>";
//    $batch->commit();
//endforeach;
// EDIT DOCTOR IN SLOT
# -- Add Check Up Appt Slot
$path = Database::MEDICAL_FACILITY . "/" . $facilityid . "/" . Appointment_Type::DOCTOR_CONSULTATION;
foreach ($dates_arr as $date):
    $count = 0;
    $date_info = array("date" => $date);


    $doc_path = $path . "/" . $date . "/" . Database::SLOTS;


    $mydb = $db->get_db();
    $batch = $mydb->batch();

    // -- BATCH DELETE
    $del_id = "1001";
    for ($i = 0; $i < 40; $i++):
        $del_slot_id = $del_id . "~" . $date . "~" . Appointment_Type::DOCTOR_CONSULTATION;
        $doc_ref = $mydb->collection($doc_path)->document($del_slot_id);
        $batch->delete($doc_ref);
        ++$del_id;
    endfor;

    // -- BATCH ADD
    $date_ref = $mydb->collection($path)->document($date);
    $batch->set($date_ref, [
        'date' => $date
    ]);


    $id = "1001";
    foreach ($dr_consultation_slot as $time):
        $slotid = $id . "~" . $date . "~" . Appointment_Type::DOCTOR_CONSULTATION;

        $data_info = array(
            "slotid" => $slotid,
            "time" => $time,
            "patientlist" => [],
            "doctorlist" => [$mp_S8967399B['profile']['nric']],
        );
        $slot_ref = $mydb->collection($doc_path)->document($slotid);
        $batch->set($slot_ref, $data_info);

        ++$id;
    endforeach;
    $batch->commit();
    echo "<br/>";
endforeach;

