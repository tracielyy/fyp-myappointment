<?php

/* Load Config File */
require_once '../resources/config.php';
require_once UTILS_PATH . '/Database.php';

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
    private string $createdon; // Date which the account is created

    protected const ACCOUNT_USER = "Account_User"; //  'protected' Access For Subclasses.

    // Constructor
    public function __construct($firstname, $lastname, $gender, $dob,
            $contactnumber, $address, $usertype, $createdon, $email, $password = NULL) {

        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->gender = $gender;
        $this->dob = $dob;
        $this->contactnumber = $contactnumber;
        $this->address = $address;
        $this->usertype = $usertype;
        $this->createdon = $createdon;
        $this->email = $email;
        $this->password = $password; // Not Sure How To Store It Yet
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
    public function set_firstname($firstname) {
        $this->firstname = $firstname;
    }

    public function set_lastname(string $lastname) {
        $this->lastname = $lastname;
    }

    public function set_email(string $email) {
        $this->email = $email;
    }

    public function set_gender(string $gender) {
        $this->gender = $gender;
    }

    public function set_dob(string $dob) {
        $this->dob = $dob;
    }

    public function set_address(string $address) {
        $this->address = $address;
    }

    public function set_password($password) {
        $this->password = $password;  // Security Measures & Conditions NOT Applied.
    }

    // Use For Debugging/ Logging Purpose
    public function __toString(): string {
        $str = nl2br('First Name: ' . $this->firstname . PHP_EOL . 'Last Name: ' . $this->lastname . PHP_EOL . 'Email: ' . $this->email .
                PHP_EOL . 'User Type: ' . $this->usertype . PHP_EOL . 'Gender: ' . $this->gender . PHP_EOL . 'DOB: ' . $this->dob .
                PHP_EOL . 'Address: ' . $this->address . PHP_EOL . 'Contact Number: ' . $this->contactnumber);
        return $str;
    }

    //============================================
    //      Methods Accessing Firestore Database 
    //============================================
    // Triggered When The The User Clicks On "Login"
    public static function login(array $credentialArr, string $sessionid): mixed {
        $auth_user = self::authenticate_user($credentialArr);
        $mapArr = array(
            "session" => array(
                "sessionid" => $sessionid,
                "isloggedin" => true
            )
        );
        // Check If There Are Any Other Login Session (Terminate Other Session?)
        // Successfully Authenticated
        if ($auth_user !== NULL) {
            $db = new Database();
            $db->modify_map_field(self::ACCOUNT_USER, $credentialArr['email'], $mapArr);
            return $auth_user;
        } else {
            return NULL;
        }
    }

    // Check If There Are Any Other Login Session
    public static function check_session() {
        
    }

    // Authenticate & Return The User Data If Authenticated Successfully
    private static function authenticate_user(array $credentialArr): mixed {
        $db = new Database();
        $user_data = $db->query_exact_match(self::ACCOUNT_USER, $credentialArr);
        if ($user_data != NULL) {
            return new Account_User($user_data['firstname'], $user_data['lastname'], $user_data['gender'],
                    $user_data['dob'], $user_data['contactnumber'], $user_data['address'], $user_data['usertype'],
                    $user_data['createdon'], $user_data['email']);
        }
        return NULL;  // Failed to authenticate (Will need to display error message)
    }

    // Check If The User Exist In The Database
    public static function check_user_exist(string $email, string $contactnumber): bool {
        $db = new Database();
        $emails_found = $db->query_exact_match(self::ACCOUNT_USER, array('email' => $email));
        $contactnumbers_found = $db->query_exact_match(self::ACCOUNT_USER, array('contactnumber' => $contactnumber));
        if (($emails_found !== NULL) || ($contactnumbers_found !== NULL)) {
            return True;  // There is existing user
        }
        return False;
    }

    // Triggered When User Clicks On "Logout"
    public static function logout($email) {
        $db = new Database();
        $mapArr = array(
            "session" => array(
                "sessionid" => "",
                "isloggedin" => false
            )
        );
        $db->modify_map_field(self::ACCOUNT_USER, $email, $mapArr);
    }

}

?>
