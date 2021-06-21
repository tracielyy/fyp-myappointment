<?php

/*
 * @author yanying (Tracie)
 */
/* Load Config File */
require_once '../resources/config.php';
require_once 'Account_User.php';
require_once 'Session.php';
require_once 'Time.php';

class Normal_User extends Account_User {

    private string $firstname;
    private string $lastname;
    private string $gender;
    private string $dob; // Date of birth -- DDMMYYYY
    private string $contactnumber; // Unsure whether to use 'int' or 'string' -- Is Foreign Number Allowed?
    private string $address;


    // Constructor
    public function __construct(Session $session, string $usertype, Time $createdon, string $firstname, string $lastname,
            string $gender, string $dob, string $contactnumber, string $address, string $email, string $password = NULL): void{

        parent::__construct($session, $usertype, $createdon, $email, $password);

        $this->firstname = $firstname;
        $this->lastname = $lastname;
        
        # Gender Setting
        if ($gender == 'F'):
            $this->gender = 'Female';
        else:
            $this->gender = 'Male';
        endif;
        
        $this->dob  = $dob;
        $this->contactnumber = $contactnumber;
        $this->address = $address;
    }

    // Getters
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

    public function get_dob(): string {
        return $this->dob;
    }

    public function get_contactnumber(): string {
        return $this->contactnumber;
    }

    public function get_address(): string {
        return $this->address;
    }

    // Setters
    public function set_firstname(string $firstname): void {
        $this->firstname = $firstname;
    }

    public function set_lastname(string $lastname): void {
        $this->lastname = $lastname;
    }

    public function set_gender(string $gender): void {
        $this->gender = $gender;
    }

    public function set_dob(string $dob): void {
        $this->dob = $dob;
    }

    public function set_contactnumber(string $contactnumber): void {
        $this->contactnumber = $contactnumber;
    }

    public function set_address(string $address): void {
        $this->address = $address;
    }

    // Debugging: Logging
    public function __toString(): string {
        $str = parent::__toString();
        $str .= nl2br('First Name: ' . $this->firstname . PHP_EOL . 'Last Name: ' . $this->lastname . PHP_EOL .
                PHP_EOL . 'Gender: ' . $this->gender . PHP_EOL . 'DOB: ' . $this->dob .
                PHP_EOL . 'Address: ' . $this->address . PHP_EOL . 'Contact Number: ' . $this->contactnumber);
        return $str;
    }

}

?>