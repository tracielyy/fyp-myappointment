<?php
/* NOT SURE IF NEED TO COMBINE WITH `Appointment_Record`. */
class Medical_Record {

    // Properties
    # Medical Facility
    private string $facility_id;

    # Medical Diagnosis (Some Descriptions)
    private string $diagnosis_description;
    
    # Prescription
    private array $prescription;  // Multiple Medications. (Possible `Prescription` Class)

    # Attending Medical Personnel
    private string $personnel_licenseno;

    # Date
    private string $date;

    // Constant
    protected const MEDICAL_RECORD = "Medical_Record";

    // Constructor
    function __construct() {
        
    }

    // Getters
    // Setters
    //============================================
    //      Methods Accessing Firestore Database 
    //============================================
    // Create Appointment
    public static function create_medical_record(array $medical_record_info) {
        
    }

}

?>
