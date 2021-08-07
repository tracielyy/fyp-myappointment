<?php

/*
 *  @author: tracieqwynn
 */

session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';
require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Patient.php';
require_once EMAIL_MOD . '/EmailTemplate.php';
require_once MEDDOC_MOD . '/Medical_Record.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST'):

    // On Click
    if (isset($_POST['ajax_check_mrid']) && isset($_POST['patientid']) && isset($POST['slotid']) && isset($_POST['practitionerid']) 
    && isset($_POST['facilityid'])):
        $patientid = $_POST['patientid'];
        $slotid = $_POST['slotid'];
        $practitionerid =$_POST['practitionerid'];
        $facilityid = $_POST['facilityid'];
    
        $medicalrecord_array = array(
            'facilityid'=> $facilityid,
            'practitioner' => $practitionerid,
            'slotid' => $slotid,
            'appointmenttype' => Appointment_Record::retrieve_appointmenttype($patientid,$slotid)
        );

        $mrid = check_mrid_exist($patientid,$slotid); //checks MRID but also, if available will put the mrid here

        if(!$mrid):
            $medical_record = Medical_Record::create_medical_record($patientid,$medicalrecord_array);
            $mrid = $medical_record->get_medicalrecordid();
        endif;

        header("Location:" . DOC_WEB . "/patientvisit/index.php?id=".$mrid."&pt=".$patientid);
        

    else:
        header("Location:" . REGISTER_WEB); // NEED TO CHANGE
    endif;

endif;
/*
 * Make Sure User Will Be Redirected Away If Accessing This File Directly
 */
if ($_SERVER["REQUEST_METHOD"] == "GET"):
    header("Location:" . REGISTER_WEB); //// NEED TO CHANGE
endif;
?>
