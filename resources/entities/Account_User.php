<!-- 
    Developed By FYP-21-S2-24
-->

<?php
/*
 * @author yanying (Tracy)
 */
/* Load Config File */
require_once '../resources/config.php';
require_once UTILS_PATH . '/Database.php';
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
    private string $createdon; // Date which the account is created
    private array $session;
    // Future Possible
    private boolean $enabled; # disabled || enabled

    protected const ACCOUNT_USER = "Account_User"; //  'protected' Access For Subclasses.

    // Constructor
    public function __construct(array $session, string $firstname, string $lastname, string $gender, string $dob,
            string $contactnumber, string $address, string $usertype, string $createdon, string $email, string $password = NULL) {

        $this->session = $session;
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
    public function get_session(): array {
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
    public function set_firstname(string $firstname) {
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
    public static function login(string $email, string $sessionid, string $token): mixed {
        // Successfully Authenticated

        $mapArr = array(
            "session" => array(
                "sessionid" => $sessionid,
                "isloggedin" => true,
                "token" => $token
            )
        );
        $db = new Database();

        $login = $db->modify_map_field(self::ACCOUNT_USER, $email, $mapArr);
        return $login; # Return Account_User Object
    }

    // Check If There Are Any Other Login Session
    public static function check_session(array $db_session, string $sessionid, string $token): bool {
        // Session Status 
        if ($db_session['isloggedin'] == false) {
            return true;
        } else {
            // Compare Token
            if ($db_session['token'] == $token && $db_session['sessionid'] == $sessionid) {
                return true;
            } else {
                return false;
            }
        }
    }

    // Generate Token (Multi-Function Usage) -- Not Sure If This Should Be In `Account_User` Class
    public static function get_token(int $length): string {
        $token = "";
        $token_repo = "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; // Upper Case
        $token_repo .= "abcdefghijklmnopqrstuvwxyz"; // Lower Case
        $token_repo .= "0123456789"; // Digits
        $max = strlen($token_repo);

        for ($i = 0; $i < $length; $i++) {
            $token .= $token_repo[random_int(0, $max - 1)];
        }

        return $token;
    }

    // Load User Data
    public static function load_user_data(array $credentialArr): mixed {
        $db = new Database();
        $user_data = $db->query_exact_match(self::ACCOUNT_USER, $credentialArr);
        if ($user_data != NULL) {
            return new Account_User($user_data['session'], $user_data['firstname'], $user_data['lastname'], $user_data['gender'],
                    $user_data['dob'], $user_data['contactnumber'], $user_data['address'], $user_data['usertype'],
                    $user_data['createdon'], $user_data['email']);
        }
        return NULL;
    }

    // Authenticate & Return The User Data If Authenticated Successfully
    public static function authenticate_user(array $credentialArr): bool {
        $db = new Database();
        $user_data = $db->query_exact_match(self::ACCOUNT_USER, $credentialArr);
        if ($user_data != NULL) {
            return True;
        }
        return False;
    }

    // Check If The User Exist In The Database
    public static function check_user_exist(string $email /* , string $contactnumber */): bool {
        $db = new Database();
        $emails_found = $db->query_exact_match(self::ACCOUNT_USER, array('email' => $email));
        //$contactnumbers_found = $db->query_exact_match(self::ACCOUNT_USER, array('contactnumber' => $contactnumber));
        //if (($emails_found !== NULL) || ($contactnumbers_found !== NULL)) {
        if (($emails_found !== NULL)) {
            return True;  // There is existing user
        }
        return False;
    }

    // Triggered When User Clicks On "Logout"
    public static function session_logout(string $email) {
        $db = new Database();
        $mapArr = array(
            "session" => array(
                "sessionid" => "",
                "isloggedin" => false,
                "token" => ""
            )
        );
        $db->modify_map_field(self::ACCOUNT_USER, $email, $mapArr);
    }

    // To Update The Generated Token To Database (Valid For 24 Hours)
    public static function request_password_reset(string $email) {
        $db = new Database();
    }

    // Password Change
    public static function change_password() {
        // Need To Send Verification Email To User.
    }

    // Password Reset
    public static function reset_password() {
        // Need To Send OTP Via Email To User.
    }

}
?>
