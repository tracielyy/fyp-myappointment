<?php

/*
 * @author yanying (Tracie)
 */

abstract class User_Type {

    const SUPER_ADMIN = "Super Admin";
    const FACIILITY_ADMIN = "Facility Admin";
    const MEDICAL_PERSONNEL = "Medical Personnel";
    const PATIENT = "Patient";
    const GUEST = "Guest";

    // -- Check If The User Is Of Certain User Type -- //
    public static function check_user_type(string $allowed_usertype, string $given_usertype): bool {
        if ($allowed_usertype === $given_usertype) {
            return true;
        }
        return false;
    }

}
?>

