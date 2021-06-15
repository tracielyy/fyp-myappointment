<?php

/*
 * @author yanying (Tracie)
 */
/* Load Config File */
require_once '../resources/config.php';
require_once UTILS_PATH . '/DbQuery.php';
require_once UTILS_PATH . '/Database.php';
require_once UTILS_PATH . '/Time.php';
require_once UTILS_PATH . '/ArrayCreation.php';
require_once UTILS_PATH . '/StringUtils.php';
require_once UTILS_PATH . '/Session.php';
require_once ENUMS_PATH . '/Appointment_Status.php';

class Account_User {

    // Properties
    private string $firstname;
    private string $lastname;
    private string $gender;
    private string $address;
    private string $email;
    private ?string $password;  // ?: Nullable Since We Are Not Storing The Password On Website
    private string $contactnumber; // Unsure whether to use 'int' or 'string' -- Is Foreign Number Allowed?
    private string $dob;       // Date of birth -- DDMMYYYY
    private string $usertype;
    private Time $createdon; // Date which the account is created
    private Session $session; // Session Object
    // Future Possible
    private bool $enabled; # disabled || enabled

    // Constructor
    public function __construct(Session $session, string $firstname, string $lastname, string $gender, string $dob,
            string $contactnumber, string $address, string $usertype, Time $createdon, string $email, string $password = NULL) {

        $this->session = $session;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        if ($gender == 'F'):
            $this->gender = 'Female';
        else:
            $this->gender = 'Male';
        endif;

        $this->dob = $dob;
        $this->contactnumber = $contactnumber;
        $this->address = $address;
        $this->usertype = $usertype;
        $this->createdon = $createdon;
        $this->email = $email;
        $this->password = $password; // Not Sure How To Store It Yet
    }

    // Getters
    public function get_session(): Session {
        return $this->session;
    }

    public function get_firstname(): string {
        return $this->firstname;
    }

    public function get_lastname(): string {
        return $this->lastname;
    }

    public function get_fullname(): string {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function get_gender(): string {
        return $this->gender;
    }

    public function get_address(): string {
        return $this->address;
    }

    public function get_contactnumber(): string {
        return $this->contactnumber;
    }

    public function get_dob(): string {
        return $this->dob;
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
    public function set_firstname(string $firstname): void {
        $this->firstname = $firstname;
    }

    public function set_lastname(string $lastname): void {
        $this->lastname = $lastname;
    }

    public function set_email(string $email): void {
        $this->email = $email;
    }

    public function set_gender(string $gender): void {
        $this->gender = $gender;
    }

    public function set_dob(string $dob): void {
        $this->dob = $dob;
    }

    public function set_address(string $address): void {
        $this->address = $address;
    }

    public function set_password($password): void {
        $this->password = $password;  // Security Measures & Conditions NOT Applied.
    }

    // -- Use For Debugging/ Logging Purpose -- //
    public function __toString(): string {
        $str = nl2br('First Name: ' . $this->firstname . PHP_EOL . 'Last Name: ' . $this->lastname . PHP_EOL . 'Email: ' . $this->email .
                PHP_EOL . 'User Type: ' . $this->usertype . PHP_EOL . 'Gender: ' . $this->gender . PHP_EOL . 'DOB: ' . $this->dob .
                PHP_EOL . 'Address: ' . $this->address . PHP_EOL . 'Contact Number: ' . $this->contactnumber);
        return $str;
    }

}

?>
