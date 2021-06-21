<?php

/*
 * @author yanying (Tracy)
 */

abstract class Role_Type {

    const DOCTOR = "Doctor";
    const NURSE = "Nurse";


    // -- Check If The User Is Of Certain User Type -- //
    public static function check_role_type(string $allowed_roletype, string $given_roletype): bool {
        if ($allowed_roletype === $given_roletype) {
            return true;
        }
        return false;
    }

}
?>
