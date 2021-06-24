<?php

/*
 * @author yanying (Tracie)
 */

/* Load Config File */
require_once '../resources/config.php';
require_once FACILITY_MOD . '/Medical_Facility.php';

/*
 *       # VIEW MEDICAL FACILITY #
 */

class RetrieveFacility {

    // -- RETRIEVE FACILITY BY ID
    public static function retrieve_facility_by_id(string $facilityid): ?Medical_Facility {

        # Create Facility Array
        $arr['facilityid'] = $facilityid;

        # Query For Facility
        $db = new DbQuery();
        $facility = $db->select_exact_match(Database::MEDICAL_FACILITY, $arr);
        if ($facility != NULL):
            return Medical_Facility::initialise_medical_facility($facility);
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
            $facility_arr[] = Medical_Facility::initialise_medical_facility($facility);
        endforeach;

        return $facility_arr;
    }

}
