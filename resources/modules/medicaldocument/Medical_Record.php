<?php

/*
 * @author yanying (Tracie)
 */


/* Load Config File */
//require_once '../resources/config.php';

require_once USER_MOD . '/Medical_Personnel.php';
require_once FACILITY_MOD . '/Medical_Facility.php';
require_once TIME_MOD . '/Time.php';

class Medical_Record {

// Properties
# Medical Record ID
    private string $medicalrecordid;

# Medical Facility
    private Medical_Facility $facility;

# Medical Diagnosis (Some Descriptions)
    private string $diagnosisdesc;

# Prescription -- Multiple Medications. (Possible `Prescription` Class)
    private array $prescription;

# Attending Medical Personnel
    private Medical_Personnel $attendingpersonnel;

# Date
    private Time $createdon;

// Constructor
    function __construct(string $medicalrecordid, Medical_Facility $facility, string $diagnosisdesc, array $prescription,
            Medical_Personnel $attendingpersonnel, Time $createdon) {
        $this->medicalrecordid = $medicalrecordid;
        $this->facility = $facility;
        $this->diagnosisdesc = $diagnosisdesc;
        $this->prescription = $prescription;
        $this->attendingpersonnel = $attendingpersonnel;
        $this->createdon = $createdon;
    }

    public function get_facility(): Medical_Facility {
        return $this->facility;
    }

    public function get_diagnosisdesc(): string {
        return $this->diagnosis;
    }

    public function get_prescription(): array {
        return $this->prescription;
    }

    public function get_attendingpersonnel(): Medical_Personnel {
        return $this->attendingpersonnel;
    }

    public function get_createdon(): Time {
        return $this->createdon;
    }

//============================================
//      Methods Accessing Firestore Database 
//============================================

    /*
     * Logic As Per Discussed:
     * Nurse can create medical record (e.g. Triage and some initial diagnosis)
     * Doctor needs to validate, edit and further validate before it can be saved.
     */


    // Generate Medical Record ID
    public static function generate_medical_record_id(string $user_doc_id /* Patient */): string {

        # Order By Medical ID (mrid-<year>-<4digitnumber>)
        $orderBy = array('medicalid');

        # Path To Medical Record
        $medical_record_path = Database::ACCOUNT_USER . "/" . $user_doc_id . "/" . Database::MEDICAL_RECORD;

        # Find The Last ID (Descending Order) & Increment
        $db = new DbQuery();
        $last_id_medical = $db->get_first_id_ordered($medical_record_path, $orderBy, false);

        # If There Is Any Present ID In Database
        $current_year = Time::get_current_year();
        if ($last_id_medical != null):

            $last_id = explode("-", $last_id_medical);

            # Compare Year
            if ($last_id[1] == $current_year):

                # Increase The Number
                $new_id = ++$last_id[2];
            
                return $last_id[0] . "-" . $last_id[1] . "-" . $new_id; # -- There is A Current Year Id
            endif;
            return "mrid-" . $current_year . "-1000"; # -- There Is No Current Year Id
        endif;
        return "mrid-" . $current_year . "-1000"; # -- Totally No ID Present In Database
    }

    // -- Create Medical Record -- //
    public static function create_medical_record(array $medical_record_info) {
        
    }

    public static function retrieve_medical_record(string $medicalrecordid): Medical_Record {
        
    }

    // -- Edit Medical Record -- //
    public static function edit_medical_record() {
        
    }

}
