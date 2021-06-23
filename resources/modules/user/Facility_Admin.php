<?php

/*
 * @author yanying (Tracie)
 */
# -- Load Config File -- #
require_once '../resources/config.php';
require_once USER_MOD . '/Admin.php';
require_once USER_MOD . '/Account_User.php';
require_once ENUMS_PATH . '/User_Type.php';

// -- Admin That Belongs To Certain Facility That Oversee Their Operations -- //
class Facility_Admin extends Admin {

    // Properties
    private string $facility;

    // -- Constructor
    public function __construct(Sesssion $session, string $usertype, Time $createdon, string $adminid, string $adminname,
            Medical_Facility $facility, string $email, string $password = NULL) {

        parent::__construct($session, $usertype, $createdon, $adminid, $adminname, $email, $password);

        $this->facility = $facility;
    }

    // Getters
    public function get_facility(): Medical_Facility {
        return $this->facility;
    }

    // Use For Debugging/ Logging Purpose
    public function __toString(): string {
        $str = nl2br(PHP_EOL . "Facility: " . $this->facility . PHP_EOL);
        return $str;
    }

}

?>
