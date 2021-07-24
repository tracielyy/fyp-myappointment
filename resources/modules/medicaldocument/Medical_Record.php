<?php

/*
 * @author yanying (Tracie)
 */

require_once USER_MOD . '/Medical_Personnel.php';
require_once FACILITY_MOD . '/Medical_Facility.php';
require_once TIME_MOD . '/Time.php';

class Medical_Record {
    # Medical Record ID

    private string $medicalrecordid;
    private string $appointmenttype;
    private string $slotid;

    # Medical Facility
    private Medical_Facility $facility;

    # Medical Diagnosis (Some Descriptions)
    private string $diagnosisdesc;

    # Prescription -- Multiple Medications. (Possible `Prescription` Class)
    private array $prescriptions;

    # Attending Medical Personnel
    private Medical_Personnel $practitioner;

    # Date
    private Time $createdon;

    // Constructor
    function __construct(Time $createdon, string $medicalrecordid, string $appointmenttype, string $slotid, Medical_Facility $facility,
            string $diagnosisdesc, array $prescriptions, Medical_Personnel $practitioner) {
        $this->createdon = $createdon;
        $this->medicalrecordid = $medicalrecordid;
        $this->appointmenttype = $appointmenttype;
        $this->slotid = $slotid;
        $this->facility = $facility;
        $this->diagnosisdesc = $diagnosisdesc;
        $this->prescriptions = $prescriptions;
        $this->practitioner = $practitioner;
    }

    public function get_medicalrecordid(): string {
        return $this->medicalrecordid;
    }

    public function get_facility(): Medical_Facility {
        return $this->facility;
    }

    public function get_appointmenttype(): string {
        return $this->appointmenttype;
    }

    public function get_slotid(): string {
        return $this->slotid;
    }

    public function get_diagnosisdesc(): string {
        return $this->diagnosis;
    }

    public function get_prescriptions(): array {
        return $this->prescriptions;
    }

    public function get_practitioner(): Medical_Personnel {
        return $this->practitioner;
    }

    public function get_createdon(): Time {
        return $this->createdon;
    }

    public static function initialise_medical_record(array $record_data): ?Medical_Record {
        try {
            #Time (Createdon)
            $createdon = new Time($record_data['createdon']['date'], $record_data['createdon']['time']);


            # Medical Facility
            $facility = Medical_Facility::retrieve_facility_by_id($record_data['facilityid']);

            # Medical Personnel
            $medical_personnel = Medical_Personnel::retrieve_practitioner_by_id($record_data['practitioner']);

            # Medical Record 
            $medical_record = new Medical_Record($createdon, $record_data['mrid'], $record_data['appointmenttype'],
                    $record_data['slotid'], $facility, $record_data['diagnosisdesc'],
                    $record_data['prescriptions'], $medical_personnel);

            return $medical_record;
        } catch (Exception $ex) {
            return null;
        }
    }

    //============================================
    //      Methods Accessing Firestore Database 
    //============================================
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
    public static function create_medical_record(string $patient_doc_id, array $medical_record_info): ?Medical_Record {
        $db = new DbQuery();

        # SET Appointment Creation Time
        $createdon = new Time();

        # Get Appointment ID
//        $id = self::generate_medical_record_id($patient_doc_id);
        $patient_mr_path = Database::ACCOUNT_USER . "/" . $patient_doc_id . "/" . Database::MEDICAL_RECORD;
        $doc_ref = $db->get_db()->collection($patient_mr_path)->newDocument();
        $medical_record_arr = array(
            'mrid' => $doc_ref->id(),
            'facilityid' => $medical_record_info['facilityid'],
            'slotid' => $medical_record_info['slotid'],
            'createdon' => ['date' => $createdon->get_date(), 'time' => $createdon->get_time()],
            'appointmenttype' => $medical_record_info['appointmenttype'],
            'practitioner' => $medical_record_info['practitioner'],
            'diagnosisdesc' => $medical_record_info['diagnosisdesc'],
            'prescriptions' => $medical_record_info['prescriptions']
        );

//        $db->get_db()->collection($patient_mr_path)->document($id)->set($medical_record_arr);
        $doc_ref->set($medical_record_arr);
        return self::initialise_medical_record($medical_record_arr);
    }

    public static function retrieve_medical_record(string $patient_doc_id, string $mrid): ?Medical_Record {

        $db = new DbQuery();
        $mr_path = Database::ACCOUNT_USER . '/' . $patient_doc_id . '/' . Database::MEDICAL_RECORD;
        $mr_data = $db->fetch_document_by_id($mr_path, $mrid);

        return self::initialise_medical_record($mr_data);
    }

    // -- Edit Medical Record -- //
    public static function edit_medical_record() {
        
    }
    

}
