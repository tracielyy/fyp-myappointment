<?php

/*
 * @author yanying (Tracie)
 */

abstract class Appointment_Type {

    const SPECIALIST_CONSULTATION = "Specialist Consultation";
    const DOCTOR_CONSULTATION = "Doctor Consultation";
    const CHECK_UP = "Check Up";

    
    // -- GET ALL CLASS DEFINED CONSTANTS
    public static function get_constants() {
        $appt_type_class = new ReflectionClass(__CLASS__);
        return $appt_type_class->getConstants();
    }

    // -- CHECK IF GIVEN IS ONE OF THE DEFINED CONSTANT
    public static function validate_type(string $type): bool {
        
        # Get All The Class Defined Const
        $appt_types = self::get_constants();
        
        # Loop Through All Defined Const
        foreach($appt_types as $atype):
            
            # Check If It Is One Of the Defined Const
            if($type == $atype):
                return True;
            endif;
            
        endforeach;
        
        return False;
    }

}

?>