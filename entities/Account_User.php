<?php

require_once '../utils/Database.php';

class Account_User {

    // Properties
    private $firstname;
    private $lastname;
    private $gender;
    private $address;
    private $email;
    private $password;
    private $contactnumber; // Unsure whether to use 'int' or 'string' -- Is Foreign Number Allowed?
    private $dob;       // Date of birth -- DDMMYYYY
    private $usertype;

    protected const ACCOUNT_USER = "Account_User"; //  'protected' Access For Subclasses.

    // Constructor
    public function __construct($firstname = NULL, $lastname = NULL, $gender = NULL, $dob = NULL,
            $contactnumber = NULL, $address = NULL, $usertype = NULL, $email = NULL, $password = NULL) {

        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->gender = $gender;
        $this->dob = $dob;
        $this->contactnumber = $contactnumber;
        $this->address = $address;
        $this->usertype = $usertype;
        $this->email = $email;
        $this->password = $password; // Not Sure How To Store It Yet
    }

    // Getters
    public function get_firstname() {
        return $this->firstname;
    }

    public function get_lastname() {
        return $this->lastname;
    }

    public function get_fullname() {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function get_gender() {
        return $this->gender;
    }

    public function get_address() {
        return $this->address;
    }

    public function get_contactnumber() {
        return $this->contactnumber;
    }

    public function get_dob() {
        return $this->dob;
    }

    public function get_email() {
        return $this->email;
    }

    public function get_password() {
        return $this->password;  // Security Measures Not Implemented
    }

    public function get_usertype() {
        return $this->usertype;
    }

    // Setters
    public function set_firstname($firstname) {
        $this->firstname = $firstname;
    }

    public function set_lastname($lastname) {
        $this->lastname = $lastname;
    }

    public function set_email($email) {
        $this->email = $email;
    }

    public function set_gender($gender) {
        $this->gender = $gender;
    }

    public function set_dob($dob) {
        $this->dob = $dob;
    }

    public function set_address($address) {
        $this->address = $address;
    }

    public function set_password($password) {
        $this->password = $password;  // Security Measures & Conditions NOT Applied.
    }

    // Use For Debugging/ Logging Purpose
    public function __toString() {
        $str = nl2br('First Name: ' . $this->firstname . PHP_EOL . 'Last Name: ' . $this->lastname . PHP_EOL . 'Email: ' . $this->email .
                PHP_EOL . 'User Type: ' . $this->usertype . PHP_EOL . 'Gender: ' . $this->gender . PHP_EOL . 'DOB: ' . $this->dob .
                PHP_EOL . 'Address: ' . $this->address . PHP_EOL . 'Contact Number: ' . $this->contactnumber);
        return $str;
    }

    //============================================
    //      Methods Accessing Firestore Database 
    //============================================

    public static function login (array $credentialArr) : mixed {
        // Successfully Authenticated
        $auth_user = self::authenticate_user($credentialArr);
        if ($auth_user !== NULL) {
            $db = new Database();
            $db->modify_single_field(self::ACCOUNT_USER, $credentialArr['email'], 'isloggedin', true);
            return $auth_user;
        } else {
            return NULL;
        }
    }
    
    private static function authenticate_user(array $credentialArr) : mixed {
        $db = new Database();
        $user_data = $db->query_exact_match(self::ACCOUNT_USER, $credentialArr);
        if ($user_data != NULL) {
            return new Account_User($user_data['firstname'], $user_data['lastname'], $user_data['gender'],
                    $user_data['dob'], $user_data['contactnumber'], $user_data['address'], $user_data['usertype'],
                    $user_data['email']);
        }
        return NULL;  // Failed to authenticate (Will need to display error message)
    }
    
    public static function check_user_exist(string $email, string $contactnumber) : bool {
        $db = new Database();
        $emails_found = $db->query_exact_match(self::ACCOUNT_USER, array('email'=> $email));
        $contactnumbers_found = $db->query_exact_match(self::ACCOUNT_USER, array('contactnumber'=>$contactnumber));
        if(($emails_found !== NULL) || ($contactnumbers_found!== NULL)) {
            return True;  // There is existing user
        }
        return False; 
    }
    
    public static function logout($email) {
        $db = new Database();
        $db->modify_single_field(self::ACCOUNT_USER, $email, 'isloggedin', false);
    }
    


}

?>
