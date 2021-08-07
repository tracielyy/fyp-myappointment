<?php

/*
 * @author yanying (Tracie)
 */

require_once USER_MOD . '/Medical_Personnel.php';
require_once FACILITY_MOD . '/Medical_Facility.php';
require_once TIME_MOD . '/Time.php';

use Google\Cloud\Firestore\Transaction;

class Medical_Record {
    # Medical Record ID

    private string $medicalrecordid;
    private string $appointmenttype;
    private string $slotid;

    # Medical Facility
    private Medical_Facility $facility;

    # Medical Diagnosis (Some Descriptions)
    private string $diagnosisdesc; /* editable */

    # Prescription -- Multiple Medications. (Possible `Prescription` Class)
    private array $prescriptions; /* editable */

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
        return $this->diagnosisdesc;
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
//            echo "error";
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

        # SET Medical Creation Time
        $createdon = new Time();

        # Get Appointment ID
        $id = self::generate_medical_record_id($patient_doc_id);
        $patient_mr_path = Database::ACCOUNT_USER . "/" . $patient_doc_id . "/" . Database::MEDICAL_RECORD;
        $doc_ref = $db->get_db()->collection($patient_mr_path)->newDocument();
        $medical_record_arr = array(
            'mrid' => $doc_ref->id(),
            'facilityid' => $medical_record_info['facilityid'],
            'slotid' => $medical_record_info['slotid'],
            'createdon' => ['date' => $createdon->get_date(), 'time' => $createdon->get_time()],
            'appointmenttype' => $medical_record_info['appointmenttype'],
            'practitioner' => $medical_record_info['practitioner'],
            'diagnosisdesc' => "",
            'prescriptions' => []
        );

        $doc_ref->set($medical_record_arr);
        return self::initialise_medical_record($medical_record_arr);
    }

    public static function retrieve_medical_record(string $patient_doc_id, string $mrid): ?Medical_Record {

        $db = new DbQuery();
        $mr_path = Database::ACCOUNT_USER . '/' . $patient_doc_id . '/' . Database::MEDICAL_RECORD;
        $mr_data = $db->fetch_document_by_id($mr_path, $mrid);

        return ($mr_data !== null) ? self::initialise_medical_record($mr_data) : null;
    }

    /*
     * EDIT MEDICAL RECORD DETAIL
     *      - Once created only limited amount of info can be edited
     *      - Only diagnosisdesc and prescriptions can be edited
     *      - Only the patient's practitioner can edit the medical record
     */

    public static function update_medical_record(string $practitioner_doc_id, string $patient_doc_id, string $mrid,
            string $diagnosisdesc, array $prescriptions): bool {

        $db = new DbQuery();
        $mr_path = Database::ACCOUNT_USER . '/' . $patient_doc_id . '/' . Database::MEDICAL_RECORD;
        $mr_doc_ref = $db->get_db()->collection($mr_path)->document($mrid);
        $trnx_result = $db->get_db()->runTransaction(function (Transaction $transaction)
        use ($mr_doc_ref, $practitioner_doc_id, $diagnosisdesc, $prescriptions) {

            $snapshot = $transaction->snapshot($mr_doc_ref);
            $db_practitioner = $snapshot['practitioner'];

            # Check If Practitioner Is The One That Is Changing The Information
            if ($db_practitioner === $practitioner_doc_id) {
                $transaction->update($mr_doc_ref, [
                    ['path' => 'diagnosisdesc', 'value' => $diagnosisdesc],
                    ['path' => 'prescriptions', 'value' => $prescriptions]
                ]);
                return true;
            }
            return false; # NOT THE PRACTITIONER WHOM INITIATE THE EDIT
        });

        return $trnx_result;
    }

}
