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
    private array $operatinghours = array('opening' => '', 'closing' => '');

    // -- Constructor
    public function __construct() {
        
    }

    // -- Getters
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

    public function get_operatinghours(): array {
        return $this->operatinghours;
    }

    public function get_openinghour(): string {
        return $this->operatinghours['opening'];
    }

    public function get_closinghour(): string {
        return $this->operatinghours['closing'];
    }

    // -- Setters
    public function set_facilityname(string $facilityname) {
        $this->facilityname = $facilityname;
    }

    public function set_address(string $address) {
        $this->address = $address;
    }

    public function set_contactnumber(string $contactnumber) {
        $this->contactnumber = $contactnumber;
    }

    public function set_operatinghours(string $openinghour, string $closinghour) {
        $this->operatinghours['opening'] = $openinghour;
        $this->operatinghours['closing'] = $closinghour;
    }

    public function set_openinghour(string $openinghour) {
        $this->operatinghour['opening'] = $openinghour;
    }

    public function set_closinghour(string $closinghour) {
        $this->operatinghour['closing'] = $closinghour;
    }

    // Use For Debugging/ Logging Purpose
    public function __toString(): string {
        $str = nl2br('Facility ID: ' . $this->facility . PHP_EOL . 'Facility Name: ' . $this->facilityname . PHP_EOL . 'Address: ' . $this->address .
                PHP_EOL . 'Contact Number: ' . $this->contactnumber . PHP_EOL . 'Opening Hour: ' . $this->operatinghours['opening'] .
                PHP_EOL . 'Closing Hour: ' . $this->operatinghours['closing']);
        return $str;
    }

    // DATABASE CONSTANT
    //============================================
    //      Methods Accessing Firestore Database 
    //============================================
    // -- When User Request To Display All Medical Facilities -- //
    public static function display_all_facilities() {
        
    }

    // -- When User Request To Display Facilities By Certain Location -- //
    public static function display_facilities_by_location() {
        
    }

    // -- When A Certain Facility Is Requested To Be Displayed -- //
    public static function get_facility_by_id() {
        
    }

}
?>

