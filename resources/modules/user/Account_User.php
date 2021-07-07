<?php

/*
 * @author yanying (Tracie)
 */
/* Load Config File */



//require_once '../resources/config.php';

require_once AUTH_MOD . '/Session.php';
require_once TIME_MOD . '/Time.php';
require_once ENUMS_PATH . '/User_Type.php';

class Account_User {

    // Properties
    private string $email;
    private ?string $password;  // ?: Nullable Since We Are Not Storing The Password On Website
    private string $usertype;
    private Time $createdon; // Date which the account is created
    private Session $session; // Session Object

    private const PASSWORD_RESET = "passwordreset";

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

    //============================================
    //      Methods Accessing Firestore Database 
    //============================================
    // -- GET USER DOCUMENT ID
    public static function retrieve_user_doc_id(string $user_email, ?string $user_password = NULL): string {
        $db = new DbQuery();

        if ($user_password == null):
            $credentials['credentials'] = array('email' => $user_email);
        else:
            $credentials['credentials'] = array('email' => $user_email, 'password' => $user_password);
        endif;

        return $db->get_document_id(Database::ACCOUNT_USER, $credentials);
    }

    // -- RETRIEVE ACCOUNT USER DATA
    public static function retrieve_account_data(array $credentialArr): ?array {

        # Credentials   
        $credentials = array(
            "credentials" => $credentialArr
        );

        # Retrieve User From Given Credentials
        $db = new DbQuery();
        $user_data = $db->fetch_one_document(Database::ACCOUNT_USER, $credentials);

        # Store Any User Data In `Account_User` Object
        if ($user_data != NULL):

            # Data Container Returned
            return $user_data;

        endif;

        return NULL;
    }

    //  -- CHECK IF USER EXIST IN THE DATABASE
    public static function check_user_exist(string $email): bool {

        # Email Array
        $emailArr ["credentials"] = array(
            'email' => $email
        );

        # Query For User With The Given Email
        $db = new DbQuery();
        $user = $db->fetch_one_document(Database::ACCOUNT_USER, $emailArr);

        # Check If There Are Any Value Returned
        if (($user !== NULL)):
            return True;  // There is existing user
        endif;

        return False;
    }

    // -- RETRIEVE USER TYPE
    public static function retrieve_user_type(string $email): string {

        # Email Array
        $emailArr ["credentials"] = array(
            'email' => $email
        );

        # Query For User With The Given Email
        $db = new DbQuery();
        $user = $db->fetch_one_document(Database::ACCOUNT_USER, $emailArr);

        # Check If There Are Any Value Returned
        if (($user !== NULL)):
            return $user['accountdetails']['usertype'];
        else:
            return User_Type::GUEST;
        endif;
    }

    // -- CHANGE EMAIL
    public static function change_email(string $cur_email, string $new_email, string $password): bool {

        # Update User Email        
        $db = new DbQuery();
        $user_doc_id = self::retrieve_user_doc_id($cur_email, $password);

        # If Valid User
        if ($user_doc_id !== NULL):

            # Updating Email To New Email
            $db->get_db()->collection(Database::ACCOUNT_USER)->document($user_doc_id)->update([
                ['path' => 'credentials.email', 'value' => $new_email]
            ]);
            return True;
        endif;

        # Return Boolean (Success or Failure)
        return False;
    }

    // -- UPDATE  BASIC PROFILE (name, address, contact etc)
    public static function update_general_profile(array $profile_arr, string $email, string $password) {

        # Limiting Field Array
        $profile_limit = array(
            "profile.contactnumber" => "",
            "profile.address" => ""
        );

        # Update User Email        
        $db = new DbQuery();
        $user_doc_id = self::retrieve_user_doc_id($email, $password);

        # If Valid User
        if ($user_doc_id !== NULL):
            $db->get_db()->collection(Database::ACCOUNT_USER)->document($user_doc_id)->update([
                ['path' => 'profile.contactnumber', 'value' => $profile_arr['profile.contactnumber']],
                ['path' => 'profile.address', 'value' => $profile_arr['profile.address']]
            ]);

        endif;

        # Return Boolean (Success or Failure)
        return $changed;
    }

    // -- PASSWORD RESET (VALID FOR 24 HOURS) 
    public static function request_password_reset(string $email, string $token) {

        # Email Array
        $emailArr ["credentials"] = array(
            'email' => $email
        );

        # Get Fresh Set Of Password Reset Array For New Password Reset
        $passwordreset_arr = ArrayCreation::fresh_passwordreset_array($token);

        # Update The Array To Database
        $db = new DbQuery();
        $db->update_field(Database::ACCOUNT_USER, $emailArr, $passwordreset_arr);
    }

    // -- VALIDATE GIVEN PASSWORD TOKEN
    public static function validate_password_token(string $email, string $passwordtoken): bool {

        # Need To Make Sure The Email Is Valid
        $exist = self::check_user_exist($email);
        if ($exist):

            # Store Email In An Array
            $email_arr["credentials"] = array(
                'email' => $email
            );

            # Retrieving `passwordreset` Map Fields
            $db = new DbQuery();
            $mapData = $db->fetch_one_document(Database::ACCOUNT_USER, $email_arr);

            # Validate The Database's Requested Dates
            if (self::verify_requested_date($mapData['passwordreset']['requestedon']['date'], $mapData['password']['requestedon']['time'])):

                # Set The Dates
                $currentDate = new Time();
                $requestedon = new Time($mapData['passwordreset']['requestedon']['date'], $mapData['passwordreset']['requestedon']['time']);

                # Get token duration (Since Request)
                $duration = (int) Time::datetime_second_diff($currentDate, $requestedon);
                $originaltoken = $mapData['passwordreset']["passwordtoken"];

                echo $requestedon->get_current_date();

                # Return bool On Validity
                return self::verify_token($originaltoken, $passwordtoken, $duration, $mapData['tokenused']);

            endif;
            return false;

        endif;
        return false;
    }

    // -- CHECK TOKEN DURATION VALIDITY
    private static function verify_token(string $originaltoken, string $emailtoken, int $duration, bool $tokenstatus): bool {

        # Set Valid Duration As 1 Hour In Seconds -- (86,400 Seconds Changed To 3600 Seconds)
        $valid_duration = 60 * 60;

        # Check If Token Match & Duration Validity Suffice
        if (($originaltoken == $emailtoken ) && ($duration < $valid_duration) && (!$tokenstatus)) :
            return true;
        endif;

        return false;
    }

    // -- DOUBLE CHECK THE DATE IN THE DATABASE (before changing password)
    private static function verify_requested_date(string $date, string $time): bool {

        # Sanitize The String 
        $date = StringUtils::clean_input($date);
        $time = StringUtils::clean_input($time);

        # Checks date & time
        if ($date !== "" && $time !== ""):
            return true;
        endif;

        return false;
    }

    // -- PASSWORD CHANGE
    public static function change_password(string $email, string $password): bool {

        # Condition Array (EMAIL)
        $conditionArr['credentials'] = array(
            'email' => $email
        );

        # Changed Array (PASSWORD)
        $changedArr['credentials'] = array(
            'password' => $password
        );

        # Update The New Password
        $db = new DbQuery();
        $db->update_field(Database::ACCOUNT_USER, $conditionArr, $changedArr);

        # Retrieve Document Again To Check Changes
        $user_data = $db->select_exact_match(Database::ACCOUNT_USER, $conditionArr);

        # Update Token Usage (WIP)
        $passwordreset_arr = ArrayCreation::used_passwordreset_array();

        $db->update_field(Database::ACCOUNT_USER, $conditionArr, $passwordreset_arr);

        return StringUtils::string_equal($user_data['credentials']['password'], $password);  // -- Bool -- //
    }

    // -- UPDATE BASIC PROFILE INFORMATION
    public static function edit_basic_profile(array $credentials_arr, array $profile_changed_arr): bool {

        # Double Check If Patient Exist For The Given Credentials
        $user_data = self::retrieve_account_data($credentials_arr);
        if ($user_data !== null):

            # Get User Document ID
            $user_doc_id = self::retrieve_user_doc_id($credentials_arr['email']);

            # Modify The Patient Profile Based On The Given Array
            $db = new DbQuery();
            $user_path = Database::ACCOUNT_USER;

            $db->get_db()->collection($user_path)
                    ->document($user_doc_id)->update([
                ['path' => 'profile.name.firstname', 'value' => $profile_changed_arr["firstname"]],
                ['path' => 'profile.name.lastname', 'value' => $profile_changed_arr['lastname']]
            ]);

            return True;

        endif;
        return false;
    }

    // -- Verify Account (Email Verification) --//
    public static function email_verified(string $email): bool {

        # Condition Array (EMAIL)
        $conditionArr['credentials'] = array(
            'email' => $email
        );

        # Changed Array (VERIFIED)
        $changedArr['accountdetails'] = ArrayCreation::account_verified_array();

        # Update From `Not Verified` To `Verified`
        $db = new DbQuery();
        return $db->udpate_field(Database::ACCOUNT_USER, $conditionArr, $changedArr);
    }

}

?>
