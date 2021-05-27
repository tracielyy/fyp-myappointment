<!-- 
    Developed By FYP-21-S2-24
-->

<?php
/*
 * @author yanying (Tracy)
 */

/*
 *  Medical Facility (e.g. Hospital, Clinics)
 */
/* Load Config File */
require_once '../resources/config.php';
require_once ENUMS_PATH . '/Appointment_Status.php';
require_once ENTITIES_PATH . '/Medical_Facility.php';
require_once UTILS_PATH . '/DbQuery.php';
require_once UTILS_PATH . '/Database.php';
require_once UTILS_PATH . '/Time.php';

class Medical_Facility {

    // Properties
    # Basic Facility ID (If There Are None In The Database)
    private const FACILITY_ID = "mf001";

    # Medical Facility ID

    private string $facilityid;

    # Medical Facility Name
    private string $facilityname;

    # Medical Facility Address
    private string $address;

    # Medical Facility Contact
    private string $contactnumber;

    # Medical Facility Operating Hours
    private array $operatinghours = array('opening' => '', 'closing' => '', 'nonstop' => false);

    # DATABASE CONSTANT

    protected const MEDICAL_FACILITY = "Medical_Facility";

    // -- Constructor -- //
    public function __construct(string $facilityname, string $address,
            string $contactnumber, array $operatinghours, ?string $facilityid) {

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

    public function get_nonstop(): bool {
        return $this->nonstop;
    }

    public function get_operatinghours(): array {
        return $this->operatinghours;
    }

    public function get_openinghour(): string {
        return $this->operatinghours['opening'];
    }

    public function get_closinghour(): string {
        return $this->operatinghours['closing'];
    }

    public function display_operatinghours(): string {
        if ($this->operatinghours['nonstop'] == false) {
            return Time::to_12hours($this->operatinghours['opening'], false) .
                    ' - ' . Time::to_12hours($this->operatinghours['closing'], false);
        } else {
            return '24 Hours';
        }
    }

    // -- Setters -- //
    private function set_facilityid(string $facilityid): void {
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

    public function set_operatinghours(string $openinghour, string $closinghour): void {
        $this->operatinghours['opening'] = $openinghour;
        $this->operatinghours['closing'] = $closinghour;
    }

    public function set_openinghour(string $openinghour) {
        $this->operatinghour['opening'] = $openinghour;
    }

    public function set_closinghour(string $closinghour) {
        $this->operatinghour['closing'] = $closinghour;
    }

    //  -- Use For Debugging/ Logging Purpose -- //
    public function __toString(): string {
        $str = nl2br(PHP_EOL . 'Facility ID: ' . $this->facilityid . PHP_EOL . 'Facility Name: ' . $this->facilityname . PHP_EOL . 'Address: ' . $this->address .
                PHP_EOL . 'Contact Number: ' . $this->contactnumber . PHP_EOL . $this->display_operatinghours());
        return $str;
    }

    // DATABASE CONSTANT
    //============================================
    //      Methods Accessing Firestore Database 
    //============================================
    // -- Create Medical_Facility -- //
    public static function create_medical_facility(array $facilitydata_arr) {
        # Medical Facility's ID
        $current_id = "";

        # Facility ID (Increment From The Last ID
        $last_id = self::get_last_medical_id();

        # Check If Database Have Any Facility ID (Any Medical_Facility Record)
        if ($last_id !== null) {
            $current_id = ++$last_id;
        } else {
            $current_id = self::FACILITY_ID;
        }
        
        # Set Facility's ID
        $facilitydata_arr['facilityid'] = $current_id;
        
        # -- Store The Facility To Database -- #
        $db = new DbQuery();
        $db->insert_data(Database::MEDICAL_FACILITY, $facilitydata_arr);
    }

    private static function get_last_medical_id(): ?string {
        # Query For Last Facility ID
        $db = new DbQuery();
        $last_id_facilty = $db->get_document_ordered(Database::MEDICAL_FACILITY, 'facilityid', false);
        $last_id = $last_id_facilty['facilityid'];
        return $last_id;
    }

    // -- When User Request To Display All Medical Facilities -- //
    public static function display_all_facilities(): array {
        # Create An Array To Store Medical_Facility Objects
        $mf_arr = array();

        # Query For Facility
        $db = new DbQuery();
        $facility_list = $db->get_all_documents_ordered(Database::MEDICAL_FACILITY, 'facilityid');
        if (!empty($facility_list)) {
            foreach ($facility_list as $facility) {
                $facility_object = new Medical_Facility($facility['facilityname'], $facility['address'],
                        $facility['contactnumber'], $facility['operatinghours'], $facility['facilityid']);
                $mf_arr[] = $facility_object;
            }

            return $mf_arr;
        }
    }

    // -- When User Request To Display Facilities By Certain Location -- //
    public static function display_facilities_by_location() {
        
    }

    // -- When A Certain Facility Is Requested To Be Displayed  (NOT DOCUMENT ID) -- //
    public static function get_facility_by_id(string $facilityid): ?Medical_Facility {
        # Create Facility Array
        $arr['facilityid'] = $facilityid;

        # Query For Facility
        $db = new DbQuery();
        $facility = $db->query_exact_match(Database::MEDICAL_FACILITY, $arr);
        if ($facility != NULL) {
            $facility_object = new Medical_Facility($facility['facilityname'], $facility['address'],
                    $facility['contactnumber'], $facility['operatinghours'], $facility['facilityid']);
            return $facility_object;
        }
    }

}
?>