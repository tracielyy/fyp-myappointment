<?php

/*
 * @author yanying (Tracie)
 */
/* Load Config File */
require_once '../resources/config.php';
require_once UTILS_PATH . '/DbQuery.php';
require_once UTILS_PATH . '/Database.php';
require_once UTILS_PATH . '/ArrayCreation.php';
require_once UTILS_PATH . '/StringUtils.php';

require_once ENUMS_PATH . '/Appointment_Status.php';
require_once 'Session.php';
require_once 'Time.php';

class Account_User {

    // Properties
    private string $email;
    private ?string $password;  // ?: Nullable Since We Are Not Storing The Password On Website
    private string $usertype;
    private Time $createdon; // Date which the account is created
    private Session $session; // Session Object
    // Future Possible
    private bool $enabled; # disabled || enabled

    // Constructor
    public function __construct(Session $session, string $usertype, Time $createdon, string $email, string $password = NULL) {

        $this->session = $session;
        $this->usertype = $usertype;
        $this->createdon = $createdon;
        $this->email = $email;
        $this->password = $password; // Not Sure How To Store It Yet
    }

    // Getters
    public function get_session(): Session {
        return $this->session;
    }

    public function get_email(): string {
        return $this->email;
    }

    public function get_password(): string {
        return $this->password;  // Security Measures Not Implemented
    }

    public function get_usertype(): string {
        return $this->usertype;
    }

    public function get_createdon(): string {
        return $this->createdon;
    }

    // Setters
    public function set_session(Session $session): void {
        $this->session = $session;
    }

    public function set_email(string $email): void {
        $this->email = $email;
    }

    public function set_password($password): void {
        $this->password = $password;  // Security Measures & Conditions NOT Applied.
    }

    // -- Use For Debugging/ Logging Purpose -- //
    public function __toString(): string {
        $str = nl2br('Email: ' . $this->email . PHP_EOL . 'User Type: ' . $this->usertype . PHP_EOL . 'Session: ' . $this->session);
        return $str;
    }

}

?>
