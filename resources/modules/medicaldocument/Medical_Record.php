<?php

/*
 * @author yanying (Tracie)
 */

namespace Module\MedicalDoc;

/* Load Config File */
require_once '../resources/config.php';

use Module\User\Medical_Personnel;
use Module\Facility\Medical_Facility;
use Module\Time\Time;

class Medical_Record {

    // Properties
    # Medical Facility
    private Medical_Facility $facility;

    # Medical Diagnosis (Some Descriptions)
    private string $diagnosisdescription;

    # Prescription -- Multiple Medications. (Possible `Prescription` Class)
    private array $prescription;

    # Attending Medical Personnel
    private Medical_Personnel $attendingpersonnel;

    # Date
    private Time $createdon;

    // Constructor
    function __construct() {
        
    }

    // Getters
    // Setters
    //============================================
    //      Methods Accessing Firestore Database 
    //============================================

    /*
     * Logic As Per Discussed:
     * Nurse can create medical record (e.g. Triage and some initial diagnosis)
     * Doctor needs to validate, edit and further validate before it can be saved.
     */

    // -- Create Medical Record -- //
    public static function create_medical_record(array $medical_record_info) {
        
    }

    // -- Edit Medical Record -- //
    public static function edit_medical_record() {
        
    }

}
