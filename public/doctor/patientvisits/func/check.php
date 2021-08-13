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

    if (isset($_POST['medrec_submit'])):
        ?> <script>console.log("going isset ")</script><?php
        $practitioneridpost = $_POST['prac_form'];
        $slotidpost = $_POST['slot_form'];
        $facilityidpost = $_POST['facility_form'];
        $patientidpost = $_POST['patient_form'];
        
    
        $medicalrecord_array = array(
            'facilityid'=> $facilityidpost,
            'practitioner' => $practitioneridpost,
            'slotid' => $slotidpost,
            'appointmenttype' => Appointment_Record::retrieve_appointmenttype($patientidpost,$slotidpost)
        );

        $mrid = NULL;
        //$mrid = check_mrid_exist($patientidpost,$slotidpost); //checks MRID but also, if available will put the mrid here

        if(!$mrid):
            $medical_record = Medical_Record::create_medical_record($patientidpost,$medicalrecord_array);
            $mrid = $medical_record->get_medicalrecordid();
            Appointment_Record::set_mrid($patientidpost,$slotidpost,$mrid);
        endif;

        header("Location:" . DOC_WEB . "/patientvisit/index.php?id=".$mrid."&pt=".$patientid);
        
    else:
        echo "test";
        // header("Location:" . "/");
    endif;

endif;
/*
 * Make Sure User Will Be Redirected Away If Accessing This File Directly
 */
if ($_SERVER["REQUEST_METHOD"] == "GET"):
    header("Location:" . "/");
endif;
?>
