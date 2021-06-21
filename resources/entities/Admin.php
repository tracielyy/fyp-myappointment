<?php

/*
 * @author yanying (Tracie)
 */
/* Load Config File */
require_once '../resources/config.php';
require_once 'Account_User.php';
require_once 'Session.php';
require_once 'Time.php';

class Admin extends Account_User {

    private string $adminid;
    private string $adminname;

    // -- Constructor
    public function __construct(Session $session, string $usertype, Time $createdon, string $adminid, string $adminname,
            string $email, string $password = NULL): void {

        parent::__construct($session, $usertype, $createdon, $email, $password);
        $this->adminid = $adminid;
        $this->adminname = $adminname;
    }

    // -- Getters
    public function get_adminid(): string {
        return $this->adminid;
    }

    public function get_adminname(): string {
        return $this->adminname;
    }

    // -- Setters
    public function set_adminname(string $adminname): void {
        $this->adminname = $adminname;
    }

    // Debugging: Logging
    public function __toString(): string {
        $str = parent::__toString();
        $str .= nl2br('Admin ID ' . $this->adminid . PHP_EOL . 'Admin  Name: ' . $this->adminname);
        return $str;
    }

}

?>
