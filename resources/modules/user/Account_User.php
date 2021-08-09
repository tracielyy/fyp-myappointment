<?php

/*
 * @author yanying (Tracie)
 */
/* Load Config File */



//require_once '../resources/config.php';

require_once AUTH_MOD . '/Session.php';
require_once TIME_MOD . '/Time.php';
require_once ENUMS_PATH . '/User_Type.php';

require_once SECURE_MOD . '/Security.php';

use Google\Cloud\Firestore\Transaction;

class Account_User {

    // Properties
    private string $email;
    private ?string $password;  // ?: Nullable Since We Are Not Storing The Password On Website
    private string $usertype;
    private Time $createdon; // Date which the account is created
    private Session $session; // Session Object

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
        return $this->password;
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
    public static function retrieve_user_doc_id(string $user_email, ?string $user_password = NULL): ?string {
        $db = new DbQuery();

        if ($user_password == null):
            $credentials['credentials'] = array('email' => $user_email);
        else:
            $credentials['credentials'] = array('email' => $user_email, 'password' => $user_password);
        endif;

        # Get The Document ID 
        return $db->get_document_id(Database::ACCOUNT_USER, $credentials);
    }

    public static function retrieve_id_by_nric(string $nric): ?string {
        $db = new DbQuery();
        $ic['profile'] = array("nric" => $nric);
        # Get The Document ID 
        return $db->get_document_id(Database::ACCOUNT_USER, $ic);
    }

    public static function retrieve_password_by_id(string $id): ?string {
        $db = new DbQuery();
        $data = $db->fetch_document_by_id(Database::ACCOUNT_USER, $id);
        return $data['credentials']['password'];
    }

    // -- RETRIEVE ACCOUNT USER DATA
    public static function retrieve_account_data(string $user_email): ?array {

        # Credentials   
        $credentials ['credentials'] = array('email' => $user_email);

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

    public static function check_nric_exist(string $nric): bool {

        $secure = new Security();
        $secure_nric = $secure->hash_256($nric);

        # Query For User With The Given Email
        $db = new DbQuery();
        $snapshot = $db->get_db()->collection(Database::ACCOUNT_USER)->document($secure_nric)->snapshot();

        # Check If There Are Any Value Returned

        if ($snapshot->exists()):
            return true;
        endif;
        return false;
    }

    //  -- CHECK IF USER EXIST IN THE DATABASE
    public static function check_email_exist(string $email): bool {

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
    public static function change_email(string $cur_email, string $new_email): bool {

        # Update User Email        
        $db = new DbQuery();
        $user_doc_id = self::retrieve_user_doc_id($cur_email);

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
    public static function change_basic_details(string $email, string $contactnumber, string $address): bool {

        # Limiting Field Array
        $basic_profile_details = array(
            "profile.contactnumber" => $contactnumber,
            "profile.address" => $address
        );

        # Removing The Key That Have NULL or Empty Value
        foreach ($basic_profile_details as $key => $value) {
            if (is_null($value) || empty($value)):
                unset($basic_profile_details[$key]);
            endif;
        }

        # Get User ID Via Email      
        $db = new DbQuery();
        $user_doc_id = self::retrieve_user_doc_id($email);

        # If Valid User
        if ($user_doc_id !== NULL && !empty($basic_profile_details)):
            $doc_ref = $db->get_db()->collection(Database::ACCOUNT_USER)->document($user_doc_id);
            $db->update_values($doc_ref, $basic_profile_details);
            return true;
        endif;

        # Return Boolean (Success or Failure)
        return false; # -- WRONG CREDENTIALS
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
        $exist = self::check_email_exist($email);
        if ($exist):

            # Store Email In An Array
            $email_arr["credentials"] = array(
                'email' => $email
            );

            # Retrieving `passwordreset` Map Fields
            $db = new DbQuery();
            $mapData = $db->fetch_one_document(Database::ACCOUNT_USER, $email_arr);

            # Validate The Database's Requested Dates
            if (self::verify_requested_date($mapData['passwordreset']['requestedon']['date'], $mapData['passwordreset']['requestedon']['time'])):

                # Set The Dates
                $currentDate = new Time();
                $requestedon = new Time($mapData['passwordreset']['requestedon']['date'], $mapData['passwordreset']['requestedon']['time']);

                # Get token duration (Since Request)
                $duration = (int) Time::datetime_second_diff($currentDate, $requestedon);
                $originaltoken = $mapData['passwordreset']["passwordtoken"];

                # Return bool On Validity
                return self::verify_token($originaltoken, $passwordtoken, $duration, $mapData['passwordreset']['tokenused']);

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
    public static function change_password(string $email, string $new_password, string $current_password): bool {

        # Update The New Password
        $user_doc_id = self::retrieve_user_doc_id($email);

        # If Valid User
        if ($user_doc_id !== NULL):
            $db = new DbQuery();

            # Update To New Password
            $secure = new Security();
            $new_hashed_pw = $secure->hash($new_password);
            $user_doc_ref = $db->get_db()->collection(Database::ACCOUNT_USER)->document($user_doc_id);
            $trnx_result = $db->get_db()->runTransaction(function (Transaction $transaction)
            use ($user_doc_ref, $new_hashed_pw, $current_password) {

                $snapshot = $transaction->snapshot($user_doc_ref);
                $db_password = $snapshot['credentials']['password'];

                $secure = new Security();
                if ($secure->compareHash($current_password, $db_password)):
                    $transaction->update($user_doc_ref, [
                        ['path' => 'credentials.password', 'value' => $new_hashed_pw]
                    ]);
                    return true;
                endif;

                return false; # -- HAVE ISSUES IN CHANGING PASSWORD
            });
        endif; # -- CHECK IF THE USER ENTERS A CORRECT PASSWORD
        return $trnx_result;
    }

    // -- PASSWORD CHANGE
    public static function change_reset_password(string $email, string $new_password): bool {

        # Update The New Password
        $user_doc_id = self::retrieve_user_doc_id($email);

        # If Valid User
        if ($user_doc_id !== NULL):
            $db = new DbQuery();

            # Update To New Password
            $secure = new Security();
            $new_hashed_pw = $secure->hash($new_password);
            $db->get_db()->collection(Database::ACCOUNT_USER)->document($user_doc_id)
                    ->update([
                        ['path' => 'credentials.password', 'value' => $new_hashed_pw]
            ]);

            # Check If Password Updated Correctly
            $new_user_doc_id = self::retrieve_user_doc_id($email, $new_hashed_pw);

            if ($new_user_doc_id !== Null):

                return true; # -- END OF PASSWORD CHANGE PROCESS
            endif; # -- CHECK FOR NEW USER ID
            return false; # -- HAVE ISSUES IN CHANGING PASSWORD
        endif; # -- CHECK IF THE USER ENTERS A CORRECT PASSWORD
        return false; # -- USER ENTER WRONG PASSWORD
    }

    // -- Update the Password Token In The Database After A Success Password Change
    public static function update_password_token(string $email): bool {

        $user_doc_id = self::retrieve_user_doc_id($email);

        # Check If Email Is Correct
        if ($user_doc_id !== Null):

            $passwordreset_arr = ArrayCreation::used_passwordreset_array();
            $db = new DbQuery();

            # Update Token Usage (ONLY FOR PASSWORD RESET)
            $user_doc_ref = $db->get_db()->collection(Database::ACCOUNT_USER)->document($user_doc_id);
            $db->update_values($user_doc_ref, $passwordreset_arr);
            return true;
        endif;
        return false;
    }

    // -- UPDATE BASIC PROFILE INFORMATION (CONTACT AND HOME ADDRESS)
    public static function edit_basic_profile(string $user_email, array $profile_changed_arr): bool {

        # Get User Document ID
        $user_doc_id = self::retrieve_user_doc_id($user_email);

        if ($user_doc_id !== null):

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

    public static function update_email_otp(string $user_email, string $otp): void {

        # Modify The Patient Profile Based On The Given Array
        $db = new DbQuery();
        $email_verify_path = Database::EMAIL_VERIFY;

        $email_verify_arr = ArrayCreation::email_verify_array($otp);

        $db->get_db()->collection($email_verify_path)->document($user_email)->set($email_verify_arr);
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

    //Updates user email with parameter user email to search    
    public static function update_address(string $user_email, string $address): bool {
        # Get User Document ID
        $user_doc_id = self::retrieve_user_doc_id($user_email);

        if ($user_doc_id !== null):

            # Modify The Patient Profile Based On The Given Array
            $db = new DbQuery();
            $user_path = Database::ACCOUNT_USER;

            $db->get_db()->collection($user_path)
                    ->document($user_doc_id)->update([
                ['path' => 'profile.address', 'value' => $address]
            ]);

            return True;
        endif;
        return false;
    }

    //Updates user email with parameter user email to search    
    public static function update_contact_number(string $user_email, string $contactnumber): bool {
        # Get User Document ID
        $user_doc_id = self::retrieve_user_doc_id($user_email);

        if ($user_doc_id !== null):

            # Modify The Patient Profile Based On The Given Array
            $db = new DbQuery();
            $user_path = Database::ACCOUNT_USER;

            $db->get_db()->collection($user_path)
                    ->document($user_doc_id)->update([
                ['path' => 'profile.contactnumber', 'value' => $contactnumber]
            ]);

            return True;
        endif;
        return false;
    }

    // -- Update Password (ONLY USED IN PROFILE, HASHING DONE IN PAGE) 
    public static function update_password(string $email, string $new_password): bool {

        # Update The New Password
        $user_doc_id = self::retrieve_user_doc_id($email);

        # If Valid User
        if ($user_doc_id !== NULL):
            $db = new DbQuery();

            $db->get_db()->collection(Database::ACCOUNT_USER)->document($user_doc_id)
                    ->update([
                        ['path' => 'credentials.password', 'value' => $new_password]
            ]);

            # Check If Password Updated Correctly
            $new_user_doc_id = self::retrieve_user_doc_id($email, $new_password);

            if ($new_user_doc_id !== Null):

                return true; # -- END OF PASSWORD CHANGE PROCESS
            endif; # -- CHECK FOR NEW USER ID
            return false; # -- HAVE ISSUES IN CHANGING PASSWORD
        endif; # -- CHECK IF THE USER ENTERS A CORRECT PASSWORD
        return false; # -- USER ENTER WRONG PASSWORD
    }

}

?>
