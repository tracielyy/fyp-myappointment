<?php

/*
 * @author yanying (Tracie)
 */

/* Load Config File */
require_once '../resources/config.php';
require_once APPT_MOD . '/Appointment_Slot.php';
require_once ENUMS_PATH . '/Appointment_Type.php';

/*
 *       # VIEW APPOINTMENT SLOTS #
 */

class RetrieveSlot {

    // -- Retrieve The Slot Information By ID & APPOINTMENT TYPE 
    public static function retrieve_appt_slot_by_id(string $id, string $appt_type): Appointment_Slot {

        # Split The ID
        $id_data = explode("~", $id);

        $db = new DbQuery();

        # Slot id <e.g 1001>~<date>~<doctor-doc-id>
        switch ($appt_type):
            case Appointment_Type::CHECK_UP:
            case Appointment_Type::DOCTOR_CONSULTATION:
                # -- TBC -- #
                break;

            # -- (SPECIALIST CONSULTATION W DOC DOCUMENT ID)
            case Appointment_Type::SPECIALIST_CONSULTATION:
                $doc_path = Database::ACCOUNT_USER . "/" . $id_data[2] . "/" . Database::APPOINTMENT_SLOTS . "/" . $id_data[1] . "/"
                        . Database::SLOTS;
                $slot_data = $db->get_documentdata_by_id($doc_path, $id);
                return Appointment_Slot::initialise_appt_slot($slot_data, $id_data[2]);

        endswitch;
    }

}

?>
