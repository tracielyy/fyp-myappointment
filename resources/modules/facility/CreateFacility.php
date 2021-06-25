<?php

/*
 * @author yanying (Tracie)
 */

/* Load Config File */
require_once '../resources/config.php';
require_once DB_MOD . '/DbQuery.php';
require_once DB_MOD . '/Database.php';
/*
 *       # ADD MEDICAL FACILITY #
 */

class CreateFacility {

    // -- GENERATE FACILITY ID
    public static function generated_facility_id() {

        # Order By Facility ID
        $orderBy = array('facilityid');

        # Find The Last ID & Increment
        $db = new DbQuery();
        $doc_path = Database::MEDICAL_FACILITY;
        $last_id_facility = $db->get_documentid_ordered($doc_path, $orderBy, false);

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
        $db = new DbQuery();

        # Check If There Is Existing Record Of The Facility
        if (!self::check_facility_exist($facility_info)):

            # Generate User Defined Facility ID
            $facility_id = self::generated_facility_id();

            $doc_path = Database::MEDICAL_FACILITY;
            return $db->insert_data($doc_path, $facility_info, False, $facility_id);
        endif;
        return False;
    }

    // -- CHECK IF THE FACILITY EXIST
    private static function check_facility_exist(array $facility_info): bool {

        # Create Checking Array (Store Conditions To Check)
        $checking_arr = array(
            'facilityname' => $facility_info['facilityname'],
            'contactnumber' => $facility_info['contactnumber']
        );

        # -- Check Name & Contact Number
        $path = Database::MEDICAL_FACILITY;
        $db = new DbQuery();
        $found_facility = $db->select_exact_match($path, $checking_arr);
        if ($found_facility):
            return True;
        endif;
        return False;
    }

}

?>
