<?php

/*
 * @author yanying (Tracie)
 */

/*
 *  Medical Facility (e.g. Hospital, Clinics)
 */
/* Load Config File */
require_once '../resources/config.php';


require_once TIME_MOD . '/Time.php';
require_once FACILITY_MOD . '/Operating_Hours.php';

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

    // -- Constructor -- //
    public function __construct(string $facilityname, string $address,
            string $contactnumber, Operating_Hours $operatinghours, ?string $facilityid) {

        $this->facilityname = $facilityname;
        $this->address = $address;
        $this->contactnumber = $contactnumber;
        $this->operatinghours = $operatinghours;
        $this->facilityid = $facilityid;
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

    public function get_operatinghours(): array {
        return $this->operatinghours;
    }

    public function display_operatinghours(): string {
        if ($this->operatinghours['is24hours'] == false) {
            return Time::to_12hours($this->operatinghours['opening'], false) .
                    ' - ' . Time::to_12hours($this->operatinghours['closing'], false);
        } else {
            return '24 Hours';
        }
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

    //  -- Use For Debugging/ Logging Purpose -- //
    public function __toString(): string {
        $str = nl2br(PHP_EOL . 'Facility ID: ' . $this->facilityid . PHP_EOL . 'Facility Name: ' . $this->facilityname . PHP_EOL . 'Address: ' . $this->address .
                PHP_EOL . 'Contact Number: ' . $this->contactnumber . PHP_EOL . $this->display_operatinghours());
        return $str;
    }

    // -- Initialise Medical Facility
    public static function initialise_medical_facility(array $facility): Medical_Facility {

        # Operating Hour
        $operatinghour = Operating_Hours::initialise_operating_hours($facility['operatinghours']);

        $facility_object = new Medical_Facility($facility['facilityname'], $facility['address'],
                $facility['contactnumber'], $operatinghour, $facility['facilityid']);
        return $facility_object;
    }

    // -- When A Certain Facility Is Requested To Be Displayed  (NOT DOCUMENT ID) -- //
    public static function retrieve_facility_by_id(string $facilityid): ?Medical_Facility {

        # Create Facility Array
        $arr['facilityid'] = $facilityid;

        # Query For Facility
        $db = new DbQuery();
        $facility = $db->query_exact_match(Database::MEDICAL_FACILITY, $arr);
        if ($facility != NULL):
            return self::initialise_medical_facility($facility);
        endif;
    }

    // Retrieval Of All Facilities
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

}

?>