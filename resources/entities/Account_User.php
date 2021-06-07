<?php

/*
 * @author yanying (Tracy)
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

    // CONSTANTS
    private const PASSWORD_RESET = "passwordreset";

    // Constructor
    public function __construct(Session $session, string $firstname, string $lastname, string $gender, string $dob,
            string $contactnumber, string $address, string $usertype, Time $createdon, string $email, string $password = NULL) {

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

    //============================================
    //      Methods Accessing Firestore Database 
    //============================================
    // -- Change Login Status When User Already Authenticated -- //
    public static function login(string $email, string $sessionid, string $token, string $ipaddress): bool {

        # Email Array
        $emailArr ["credentials"] = array(
            'email' => $email
        );

        # Create Array Fields To Update To Google Cloud Firestore
        $session_arr = ArrayCreation::used_session_array($sessionid, $token, $ipaddress);

        # Update Session Field After Success Authentication
        $db = new DbQuery();
        $login = $db->modify_map_field(Database::ACCOUNT_USER, $emailArr, $session_arr);
        return $login; # -- Return Bool (Success or Failure) -- #
    }

    //  -- Check If There Are Any Other Login Session -- //
    public static function check_session(Session $db_session, string $sessionid, string $token): bool {

        #  Session Status 
        if ($db_session->get_isloggedin() == false) {
            return true;
        } else {

            # Compare Token
            return ($db_session->get_token() == $token && $db_session->get_sessionid() == $sessionid);
        }
    }

    // -- Get User Full Name -- //
    public static function retrieve_user_fullname(string $email): ?string {

        # Assign Email To Array
        $emailArr ["credentials"] = array(
            'email' => $email
        );

        # Retrieve `Account_User` Object
        $db = new DbQuery();
        $user_data = $db->query_exact_match(Database::ACCOUNT_USER, $emailArr);

        # Filter & Return Full Name
        if ($user_data !== NULL) {
            $name_arr = $user_data['profile']['name'];
            $full_name = $name_arr['firstname'] . " " . $name_arr['lastname'];
            return $full_name;
        }
        return null;
    }

    // -- Load User Data (Retrieve & Return User Data) -- //
    public static function load_user_data(array $credentialArr): ?Account_User {

        # Credentials
        $credentials = array(
            "credentials" => $credentialArr
        );

        # Retrieve User From Given Credentials
        $db = new DbQuery();
        $user_data = $db->query_exact_match(Database::ACCOUNT_USER, $credentials);

        # Store Any User Data In `Account_User` Object
        if ($user_data != NULL) {
            $profile_arr = $user_data['profile'];
            $credentials_arr = $user_data['credentials'];
            $session_arr = $user_data['session'];
            $accountdetails_arr = $user_data['accountdetails'];

            # Session Object
            $session_obj = new Session($session_arr['isloggedin'], $session_arr['sessionid'], $session_arr['token'], $session_arr['ipaddress']);

            # Time Object
            $time_obj = new Time($accountdetails_arr['createdon']['date'], $accountdetails_arr['createdon']['time']);

            return new Account_User($session_obj, $profile_arr['name']['firstname'], $profile_arr['name']['lastname'],
                    $profile_arr['gender'], $profile_arr['dob'], $profile_arr['contactnumber'], $profile_arr['address'],
                    $accountdetails_arr['usertype'], $time_obj, $credentials_arr['email']);
        }
        return NULL;
    }

    // -- Authenticate & Return The User Data If Authenticated Successfully -- //
    public static function authenticate_user(array $credentialArr): bool {

        # Credentials
        $credentials = array(
            "credentials" => $credentialArr
        );

        # Query For User Using Given Credentials
        $db = new DbQuery();
        $user_data = $db->query_exact_match(Database::ACCOUNT_USER, $credentials);

        # Check If There Are Any User Returned From The Query
        if ($user_data != NULL) {
            return True;
        }
        return False;
    }

    //  -- Check If The User Exist In The Database  -- //
    public static function check_user_exist(string $email /* , string $contactnumber */): bool {

        # Email Array
        $emailArr ["credentials"] = array(
            'email' => $email
        );

        # Query For User With The Given Email
        $db = new DbQuery();
        $emails_found = $db->query_exact_match(Database::ACCOUNT_USER, $emailArr);

        # Check If There Are Any Value Returned
        if (($emails_found !== NULL)) {
            return True;  // There is existing user
        }
        return False;
    }

    // -- Triggered When User Clicks On "Logout" -- // 
    public static function session_logout(string $email) {

        # Email Array
        $emailArr ["credentials"] = array(
            'email' => $email
        );

        # Declare Session Array With Logged Out Values
        $session_arr = ArrayCreation::fresh_session_array();

        # Update Session Array
        $db = new DbQuery();
        $db->modify_map_field(Database::ACCOUNT_USER, $emailArr, $session_arr);
    }

    // -- To Update The Generated Token To Database (Valid For 24 Hours) -- //
    public static function request_password_reset(string $email, string $token) {

        # Email Array
        $emailArr ["credentials"] = array(
            'email' => $email
        );

        # Get Fresh Set Of Password Reset Array For New Password Reset
        $passwordreset_arr = ArrayCreation::fresh_passwordreset_array($token);

        # Update The Array To Database
        $db = new DbQuery();
        $db->modify_map_field(Database::ACCOUNT_USER, $emailArr, $passwordreset_arr);
    }

    // -- Validate Password Token -- //
    public static function validate_password_token(string $email, string $passwordtoken): bool {

        # Need To Make Sure The Email Is Valid
        $exist = self::check_user_exist($email);
        if ($exist) {

            # Store Email In An Array
            $email_arr["credentials"] = array(
                'email' => $email
            );

            # Retrieving `passwordreset` Map Fields
            $db = new DbQuery();
            $mapData = $db->get_map_field(Database::ACCOUNT_USER, $email_arr, self::PASSWORD_RESET);

            # Validate The Database's Requested Dates
            if (self::verify_requested_date($mapData['requestedon']['date'], $mapData['requestedon']['time'])) {

                # Set The Dates
                $currentDate = new Time();
                $requestedon = new Time($mapData['requestedon']['date'], $mapData['requestedon']['time']);

                # Get token duration (Since Request)
                $duration = (int) Time::datetime_second_diff($currentDate, $requestedon);
                $originaltoken = $mapData["passwordtoken"];

                echo $requestedon->get_current_date();

                # Return bool On Validity
                return self::verify_token($originaltoken, $passwordtoken, $duration, $mapData['tokenused']);
            }
            return false;
        }
        return false;
    }

    // -- Check If Given Token Is Valid -- //
    private static function verify_token(string $originaltoken, string $emailtoken, int $duration, bool $tokenstatus): bool {

        # Set Valid Duration As 1 Hour In Seconds -- (86,400 Seconds Changed To 3600 Seconds)
        $valid_duration = 60 * 60;

        # Check If Token Match & Duration Validity Suffice
        if (($originaltoken == $emailtoken ) && ($duration < $valid_duration) && (!$tokenstatus)) {
            return true;
        }
        return false;
    }

    // -- Check If The Date Is Correct (Further Regex Needed -- NOT IMPLEMENTED) -- //
    private static function verify_requested_date(string $date, string $time): bool {

        # Sanitize The String 
        $date = StringUtils::clean_input($date);
        $time = StringUtils::clean_input($time);

        # Checks date & time
        if ($date !== "" && $time !== "") {
            return true;
        }
        return false;
    }

    #-------------------------------------------------------------------------#
    # -- Information Update -------------------------------------------------#
    #-------------------------------------------------------------------------#

    // -- Password Change -- //
    public static function change_password(string $email, string $password): bool {

        # Condition Array (EMAIL)
        $conditionArr['credentials'] = array(
            'email' => $email
        );

        # Changed Array (PASSWORD)
        $changedArr['credentials'] = array(
            'password' => $password
        );

        // Need To Send Verification Email To User.
        $db = new DbQuery();
        $db->modify_map_field(Database::ACCOUNT_USER, $conditionArr, $changedArr);

        # Retrieve Document Again To Check Changes
        $user_data = $db->query_exact_match(Database::ACCOUNT_USER, $conditionArr);

        # Update Token Usage (WIP)
        $passwordreset_arr = self::used_passwordreset_array();

        $db->modify_map_field(Database::ACCOUNT_USER, $conditionArr, $passwordreset_arr);

        return StringUtils::string_equal($user_data['credentials']['password'], $password);  // -- Bool -- //
    }

    // -- Change Email -- //
    public static function change_email(string $cur_email, string $new_email, string $password): bool {

        # Credential Array (The Condition To Fulfil
        $credentials['credentials'] = array(
            'email' => $cur_email,
            'password' => $password
        );

        # Changed Array
        $update_arr['credentials'] = array(
            'email' => $new_email
        );

        # Update User Email        
        $db = new DbQuery();
        $changed = $db->modify_map_field(Database::ACCOUNT_USER, $credentials, $update_arr);

        # Return Boolean (Success or Failure)
        return $changed;
    }

    // -- Update Profile Information (Names, Contact, Address) -- //
    public static function update_general_profile(array $profile_arr, string $email, string $password) {

        # Credential Array
        $credentials['credentials'] = array(
            'email' => $email,
            'password' => $password
        );

        # Update User Profile
        $db = new DbQuery();
        $changed = $db->modify_map_field(Database::ACCOUNT_USER, $credentials, $profile_arr);

        # Return Boolean (Success or Failure)
        return $changed;
    }

    /*
     * --------------------------
     * Functions To Be Modified By Sub-classes
     * --------------------------
     */
}

?>
