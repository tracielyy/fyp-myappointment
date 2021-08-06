<?php

/*
 * @author yanying (Tracie)
 */

/*
 *  Medical Facility (e.g. Hospital, Clinics)
 */

require_once TIME_MOD . '/Time.php';
require_once FACILITY_MOD . '/Operating_Hours.php';
require_once DB_MOD . '/DbQuery.php';
require_once DB_MOD . '/Database.php';

use Google\Cloud\Firestore\Transaction;
use Google\Cloud\Firestore\FieldValue;

class Medical_Facility {

    // Properties
    # Medical Facility ID
    private string $facilityid;

    # Medical Facility Name
    private string $facilityname;

    # Medical Facility Address
    private string $address;

    # Medical Facility Contact
    private string $contactnumber;

    # Medical Facility Operating Hours
    private Operating_Hours $operatinghours;

    # Different Specialisation Categories
    private array $specialisations;

    // -- Constructor -- //
    public function __construct(string $facilityname, string $address,
            string $contactnumber, Operating_Hours $operatinghours, ?string $facilityid, array $specialisations) {

        $this->facilityname = $facilityname;
        $this->address = $address;
        $this->contactnumber = $contactnumber;
        $this->operatinghours = $operatinghours;
        $this->facilityid = $facilityid;
        $this->specialisations = $specialisations;
    }

    // -- Getters -- //
    public function get_facilityid(): string {
        return $this->facilityid;
    }

    public function get_facilityname(): string {
        return $this->facilityname;
    }

    public function get_address(): string {
        return $this->address;
    }

    public function get_contactnumber(): string {
        return $this->contactnumber;
    }

    public function get_is24hours(): bool {
        return $this->is24hour;
    }

    public function get_operatinghours(): Operating_Hours {
        return $this->operatinghours;
    }

    public function get_specialisations(): array {
        return $this->specialisations;
    }

    // -- Setters -- //
    public function set_facilityid(string $facilityid): void {
        $this->facilityid = $facilityid;
    }

    public function set_facilityname(string $facilityname): void {
        $this->facilityname = $facilityname;
    }

    public function set_address(string $address): void {
        $this->address = $address;
    }

    public function set_contactnumber(string $contactnumber): void {
        $this->contactnumber = $contactnumber;
    }

    public function set_operatinghours(Operating_Hours $operatinghours): void {
        $this->operatinghour = $operatinghours;
    }

    public function set_specialisations(array $specialisation): void {
        $this->specialisation = $specialisation;
    }

    //  -- Use For Debugging/ Logging Purpose -- //
    public function __toString(): string {
        $str = nl2br(PHP_EOL . 'Facility ID: ' . $this->facilityid . PHP_EOL . 'Facility Name: ' . $this->facilityname . PHP_EOL . 'Address: ' . $this->address .
                PHP_EOL . 'Contact Number: ' . $this->contactnumber);
        return $str;
    }

    // -- Initialise Medical Facility
    public static function initialise_medical_facility(array $facility): Medical_Facility {

        # Operating Hour
        $operatinghour = Operating_Hours::initialise_operating_hours($facility['operatinghours']);
        $facility_object = new Medical_Facility($facility['facilityname'], $facility['address'],
                $facility['contactnumber'], $operatinghour, $facility['facilityid'], $facility['specialisations']);
        return $facility_object;
    }

    // ####################     Database Functions      ################### //
    // -- GENERATE FACILITY ID
    public static function generated_facility_id(): string {

        # Order By Facility ID
        $orderBy = array('facilityid');

        # Find The Last ID & Increment
        $db = new DbQuery();
        $doc_path = Database::MEDICAL_FACILITY;
        $last_id_facility = $db->get_first_id_ordered($doc_path, $orderBy, false);

        # If There Is Any Present ID In Database
        if ($last_id_facility != null) :

            return ++$last_id_facility;

        # No ID Present In Database
        else:
            return "mf1001";
        endif;
    }

    // -- CREATE NEW MEDICAL FACILTY
    public static function create_medical_facility(array $facility_info): bool {

        # Check If There Is Existing Record Of The Facility
        if (!RetrieveFacility::check_facility_exist($facility_info)):

            # Generate User Defined Facility ID
            $facility_id = self::generated_facility_id();

            $doc_path = Database::MEDICAL_FACILITY;
            $db = new DbQuery();
            return $db->insert_document($doc_path, $facility_info, False, $facility_id);
        endif;
        return False;
    }

    // -- RETRIEVE FACILITY BY ID
    public static function retrieve_facility_by_id(string $facilityid): ?Medical_Facility {

        # Create Facility Array
        $arr['facilityid'] = $facilityid;

        # Query For Facility
        $db = new DbQuery();
        $facility = $db->fetch_one_document(Database::MEDICAL_FACILITY, $arr);
        if ($facility != NULL):
            return self::initialise_medical_facility($facility);
        endif;
    }

    // -- RETRIEVE ALL FACILITIES
    public static function retrieve_all_facilities(): array {

        # Create An Array 
        $facility_arr = array();

        # Query For All The Facilities In The Database
        $db = new DbQuery();
        $facility_list = $db->get_all_documents_ordered(Database::MEDICAL_FACILITY, "facilityname");

        # Loop & Add To Empty Array
        foreach ($facility_list as $facility):
            $facility_arr[] = self::initialise_medical_facility($facility);
        endforeach;

        return $facility_arr;
    }

    public static function retrieve_paginate_facilities(string $startAfter = null): array {
        # Create An Array 
        $facility_arr = array();

        # Query For All The Facilities In The Database
        $db = new DbQuery();
        $doc_ref = $db->get_db()->collection(Database::MEDICAL_FACILITY)->orderBy('facilityid');

        if ($startAfter == null):
            # Beginning Query
            $arr = $doc_ref->limit(2)->documents();
        else:
            # Consecutive Query
            $arr = $doc_ref->startAfter($startAfter)->limit(2)->documents();
        endif;

        # Loop & Add To Container
        foreach ($arr as $doc) {
            if ($doc->exists()) {
                $doc_data = $doc->data();

                $facility_arr[] = self:: initialise_medical_facility($doc_data);
            }
        }

        return $facility_arr;
    }

    // -- CHECK IF THE FACILITY EXIST
    public static function check_facility_exist(array $facility_info): bool {

        # Create Checking Array (Store Conditions To Check)
        $checking_arr = array(
            'facilityname' => $facility_info['facilityname'],
            'contactnumber' => $facility_info['contactnumber']
        );

        # -- Check Name & Contact Number
        $path = Database::MEDICAL_FACILITY;
        $db = new DbQuery();
        $found_facility = $db->fetch_one_document($path, $checking_arr);
        if ($found_facility):
            return True;
        endif;
        return False;
    }

    public static function insert_specialisation(string $facilityid, string $specialisation): bool {

        $db = new DbQuery();
        $spec_ref = $db->get_db()->collection(Database::MEDICAL_FACILITY)->document($facilityid);
        $trnx_result = $db->get_db()->runTransaction(function (Transaction $transaction)
        use ($spec_ref, $specialisation) {

            $snapshot = $transaction->snapshot($spec_ref);
            $specialisations = $snapshot['specialisations'];

            // Check If The Specialisation Exist In The Database
            if (!in_array($specialisation, $specialisations)) :
                $transaction->update($spec_ref, [
                    ['path' => 'specialisations', 'value' => FieldValue::arrayUnion([$specialisation])]
                ]);
                return true;
            endif;
            return false;
        });
        return $trnx_result;
    }

    public static function delete_specialisation(string $facilityid, string $specialisation): void {
        $db = new DbQuery();
        $db->get_db()->collection(Database::MEDICAL_FACILITY)->document($facilityid)->update([
            ['path' => 'specialisations', 'value' => FieldValue::arrayRemove([$specialisation])]
        ]);
    }

}

?>