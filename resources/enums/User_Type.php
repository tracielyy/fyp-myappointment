<?php

/*
 * @author yanying (Tracy)
 */

abstract class User_Type {

    const ADMIN = "Admin";
    const MEDICAL_PERSONNEL = "Medical_Personnel";
    const PATIENT = "Patient";

    // -- Check If The User Is Of Certain User Type -- //
    public static function check_user_type(string $allowed_usertype, string $given_usertype): bool {
        if ($allowed_usertype === $given_usertype) {
            return true;
        }
        return false;
    }

}
?>

