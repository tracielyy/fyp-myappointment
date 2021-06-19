<?php
/*
 * @author yanying (Tracy)
 */
# -- Load Config File -- #
require_once '../resources/config.php';
require_once ENTITIES_PATH . '/Account_User.php';

class Admin extends Account_User {

    // Properties
    // -- Constructor
    public function __construct($firstname, $lastname, $gender, $dob,
            $contactnumber, $address, $usertype, $createdon, $email, $password = NULL) {

        parent::__construct($firstname, $lastname, $gender, $dob, $contactnumber, $address,
                $usertype, $createdon, $email, $password);
    }

    // Getters
    // Setters
    
    
    

}
?>
