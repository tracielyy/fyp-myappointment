<!-- 
    Developed By FYP-21-S2-24
-->
<?php
/*
 * @author yanying (Tracy)
 */
/* Load Config File */
require_once '../resources/config.php';
require_once UTILS_PATH . '/DbQuery.php';
require_once UTILS_PATH . '/Database.php';
require_once UTILS_PATH . '/Time.php';
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
    private bool $enabled; # disabled || enabled

    // CONSTANTS
    private const PASSWORD_RESET = "passwordreset";

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
    public static function login(string $email, string $sessionid, string $token): bool {

        # Create Array Fields To Update To Google Cloud Firestore
        $mapArr = array(
            "session" => array(
                "sessionid" => $sessionid,
                "isloggedin" => true,
                "token" => $token
            )
        );

        # Update Session Field After Success Authentication
        $db = new DbQuery();
        $login = $db->modify_map_field(Database::ACCOUNT_USER, $email, $mapArr);
        return $login; # -- Return Bool (Success or Failure) -- #
    }

    //  -- Check If There Are Any Other Login Session -- //
    public static function check_session(array $db_session, string $sessionid, string $token): bool {

        #  Session Status 
        if ($db_session['isloggedin'] == false) {
            return true;
        } else {

            # Compare Token
            if ($db_session['token'] == $token && $db_session['sessionid'] == $sessionid) {
                return true;
            } else {
                return false;
            }
        }
    }

    // -- Generate Token (Multi-Function Usage) ~ Not Sure If This Should Be In `Account_User` Class -- //
    public static function generate_token(int $length): string {
        $token = "";
        $token_repo = "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; // Upper Case
        $token_repo .= "abcdefghijklmnopqrstuvwxyz"; // Lower Case
        $token_repo .= "0123456789"; // Digits
        $token_repo .= ".-_~!,*:@"; // Special Chars (Plus Sign NOT Included)
        $max = strlen($token_repo);

        # Randomly Pick From The Indexes Of `$token_repo`
        for ($i = 0; $i < $length; $i++) {
            $token .= $token_repo[random_int(0, $max - 1)];
        }

        return $token;
    }

    // -- Load User Data (Retrieve & Return User Data) -- //
    public static function load_user_data(array $credentialArr): ?Account_User {

        # Retrieve User From Given Credentials
        $db = new DbQuery();
        $user_data = $db->query_exact_match(Database::ACCOUNT_USER, $credentialArr);

        # Store Any User Data In `Account_User` Object
        if ($user_data != NULL) {
            return new Account_User($user_data['session'], $user_data['firstname'], $user_data['lastname'], $user_data['gender'],
                    $user_data['dob'], $user_data['contactnumber'], $user_data['address'], $user_data['usertype'],
                    $user_data['createdon'], $user_data['email']);
        }
        return NULL;
    }

    // -- Authenticate & Return The User Data If Authenticated Successfully -- //
    public static function authenticate_user(array $credentialArr): bool {

        # Query For User Using Given Credentials
        $db = new DbQuery();
        $user_data = $db->query_exact_match(Database::ACCOUNT_USER, $credentialArr);

        # Check If There Are Any User Returned From The Query
        if ($user_data != NULL) {
            return True;
        }
        return False;
    }

    //  -- Check If The User Exist In The Database -- //
    public static function check_user_exist(string $email /* , string $contactnumber */): bool {

        # Query For User With The Given Email
        $db = new DbQuery();
        $emails_found = $db->query_exact_match(Database::ACCOUNT_USER, array('email' => $email));

        # Check If There Are Any Value Returned
        if (($emails_found !== NULL)) {
            return True;  // There is existing user
        }
        return False;
    }

    // -- Triggered When User Clicks On "Logout" -- // 
    public static function session_logout(string $email) {

        # Declare Session Array With Logged Out Values
        $mapArr = array(
            "session" => array(
                "sessionid" => "",
                "isloggedin" => false,
                "token" => ""
            )
        );

        # Update Session Array
        $db = new DbQuery();
        $db->modify_map_field(Database::ACCOUNT_USER, $email, $mapArr);
    }

    // -- To Update The Generated Token To Database (Valid For 24 Hours) -- //
    public static function request_password_reset(string $email, string $token) {
        # Create A Time Object
        $time = new Time();

        # Create An Array To Store `passwordreset` Fields
        $userDataArr["passwordreset"] = array(
            "passwordtoken" => $token,
            "requestedon" => array(
                "date" => $time->get_date(),
                "time" => $time->get_time()
            )
        );

        # Update The Array To Database
        $db = new DbQuery();
        $db->modify_map_field(Database::ACCOUNT_USER, $email, $userDataArr);
    }

    // -- Validate Password Token -- //
    public static function validate_password_token(string $email, string $passwordtoken): bool {

        # Need To Make Sure The Email Is Valid
        $exist = self::check_user_exist($email);
        if ($exist) {

            # Store Email In An Array
            $conditionArr['email'] = $email;

            # Retrieving `passwordreset` Map Fields
            $db = new DbQuery();
            $mapData = $db->get_map_field(Database::ACCOUNT_USER, $conditionArr, self::PASSWORD_RESET);

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
                return self::verify_token($originaltoken, $passwordtoken, $duration);
            }
            return false;
        }
        return false;
    }

    // -- Check If Given Token Is Valid -- //
    private static function verify_token(string $originaltoken, string $emailtoken, int $duration): bool {

        # Set Valid Duration As 24 Hours In Seconds -- (86,400 Seconds)
        $valid_duration = 24 * 60 * 60;

        # Check If Token Match & Duration Validity Suffice
        if (($originaltoken == $emailtoken ) && ($duration < $valid_duration)) {
            return true;
        }
        return false;
    }

    // -- Check If The Date Is Correct (Further Regex Needed -- NOT IMPLEMENTED) -- //
    private static function verify_requested_date(string $date, string $time): bool {

        # Sanitize The String 
        $date = self::clean_input($date);
        $time = self::clean_input($time);

        # Checks date & time
        if ($date !== "" && $time !== "") {
            return true;
        }
        return false;
    }

    // -- String Cleaning -- //
    private static function clean_input(string $input): string {
        $input = trim($input);  // Remove leading and trailing whitespace 
        $input = stripslashes($input);  // Remove '\' (slashes)
        $input = htmlspecialchars($input);  // Treat special chars as HTML entities
        $input = strtolower($input);    // All chars to lowercase
        return $input;
    }

    // -- Password Change -- //
    public static function change_password(string $email, string $password): bool {

        # Condition Array (EMAIL)
        $conditionArr['email'] = $email;

        # Changed Array (PASSWORD)
        $changedArr['password'] = $password;

        // Need To Send Verification Email To User.
        $db = new DbQuery();
        $db->modify_field(Database::ACCOUNT_USER, $conditionArr, $changedArr);

        # Retrieve Document Again To Check Changes
        $user_data = $db->query_exact_match(Database::ACCOUNT_USER, $conditionArr);

        if ($user_data['password'] == $password) {
            return true;
        }
        return false;
    }

    // -- Password Reset -- //
    public static function reset_password() {
        // Need To Send OTP Via Email To User.
        # Need To Reset The Fields In The `passwordreset` To Empty
    }

}
?>
