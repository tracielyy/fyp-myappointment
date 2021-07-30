<?php

/*
 *  @author: tracieqwynn
 */

abstract class Health_Info_Type {

    const DOCTOR_ADVICE = "Doctor's Advice";
    const WORLD_HEALTH_NOTICE = "World Health Notice";
    const HEALTH_TIPS = "Health Tips";

    // -- GET ALL CLASS DEFINED CONSTANTS
    private static function get_constants() {
        $appt_type_class = new ReflectionClass(__CLASS__);
        return $appt_type_class->getConstants();
    }

    // -- CHECK IF GIVEN IS ONE OF THE DEFINED CONSTANT
    public static function validate_type(string $type): bool {

        # Get All The Class Defined Const
        $hinfo_type = self::get_constants();

        # Loop Through All Defined Const
        foreach ($hinfo_type as $htype):

            # Check If It Is One Of the Defined Const
            if ($type == $htype):
                return True;
            endif;

        endforeach;

        return False;
    }

}
