<?php

class AccountUserFunctions {

    // CONSTANTS
    private const PASSWORD_RESET = "passwordreset";

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
        $login = $db->modify_field(Database::ACCOUNT_USER, $emailArr, $session_arr);
        return $login; # -- Return Bool (Success or Failure) -- #
    }

    //  -- Check If There Are Any Other Login Session -- //
    public static function check_session(Session $db_session, string $sessionid, string $token): bool {

        #  Session Status 
        if ($db_session->get_isloggedin() == false) :
            return true;
        endif;

        # Compare Token
        return ($db_session->get_token() == $token && $db_session->get_sessionid() == $sessionid);
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
        if ($user_data !== NULL):

            $name_arr = $user_data['profile']['name'];
            $full_name = $name_arr['firstname'] . " " . $name_arr['lastname'];
            return $full_name;

        endif;

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
        if ($user_data != NULL):

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
        endif;

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
        if ($user_data != NULL):
            return True;
        endif;

        return False;
    }

    //  -- Check If The User Exist In The Database  -- //
    public static function check_user_exist(string $email): bool {

        # Email Array
        $emailArr ["credentials"] = array(
            'email' => $email
        );

        # Query For User With The Given Email
        $db = new DbQuery();
        $emails_found = $db->query_exact_match(Database::ACCOUNT_USER, $emailArr);

        # Check If There Are Any Value Returned
        if (($emails_found !== NULL)):
            return True;  // There is existing user
        endif;

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
        $db->modify_field(Database::ACCOUNT_USER, $emailArr, $session_arr);
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
        $db->modify_field(Database::ACCOUNT_USER, $emailArr, $passwordreset_arr);
    }

    // -- Validate Password Token -- //
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
            $mapData = $db->get_map_field(Database::ACCOUNT_USER, $email_arr, self::PASSWORD_RESET);

            # Validate The Database's Requested Dates
            if (self::verify_requested_date($mapData['requestedon']['date'], $mapData['requestedon']['time'])):

                # Set The Dates
                $currentDate = new Time();
                $requestedon = new Time($mapData['requestedon']['date'], $mapData['requestedon']['time']);

                # Get token duration (Since Request)
                $duration = (int) Time::datetime_second_diff($currentDate, $requestedon);
                $originaltoken = $mapData["passwordtoken"];

                echo $requestedon->get_current_date();

                # Return bool On Validity
                return self::verify_token($originaltoken, $passwordtoken, $duration, $mapData['tokenused']);

            endif;
            return false;

        endif;
        return false;
    }

    // -- Check If Given Token Is Valid -- //
    private static function verify_token(string $originaltoken, string $emailtoken, int $duration, bool $tokenstatus): bool {

        # Set Valid Duration As 1 Hour In Seconds -- (86,400 Seconds Changed To 3600 Seconds)
        $valid_duration = 60 * 60;

        # Check If Token Match & Duration Validity Suffice
        if (($originaltoken == $emailtoken ) && ($duration < $valid_duration) && (!$tokenstatus)) :
            return true;
        endif;

        return false;
    }

    // -- Check If The Date Is Correct (Further Regex Needed -- NOT IMPLEMENTED) -- //
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
        return $db->modify_field(Database::ACCOUNT_USER, $conditionArr, $changedArr);
    }

    #-------------------------------------------------------------------------#
    # -- Information Update -------------------------------------------------#
    #-------------------------------------------------------------------------#

    // -- Edit Patient Information (Make Sure Patient Has To Provide Credentials For The Change) -- //
    public static function edit_basic_profile(array $credentials_arr, array $profile_changed_arr): bool {

        # Double Check If Patient Exist For The Given Credentials
        $user_data = self::load_user_data($credentials_arr);
        if ($user_data !== null):

            # Modify The Patient Profile Based On The Given Array
            $db = new DbQuery();
            return $db->modify_field(Database::ACCOUNT_USER, $credentials_arr, $profile_changed_arr);
        endif;
        return false;
    }

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

        # Update The New Password
        $db = new DbQuery();
        $db->modify_field(Database::ACCOUNT_USER, $conditionArr, $changedArr);

        # Retrieve Document Again To Check Changes
        $user_data = $db->query_exact_match(Database::ACCOUNT_USER, $conditionArr);

        # Update Token Usage (WIP)
        $passwordreset_arr = ArrayCreation::used_passwordreset_array();

        $db->modify_field(Database::ACCOUNT_USER, $conditionArr, $passwordreset_arr);

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
        $changed = $db->modify_field(Database::ACCOUNT_USER, $credentials, $update_arr);

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
        $changed = $db->modify_field(Database::ACCOUNT_USER, $credentials, $profile_arr);

        # Return Boolean (Success or Failure)
        return $changed;
    }

    // -- When A Certain Facility Is Requested To Be Displayed  (NOT DOCUMENT ID) -- //
    public static function get_facility_by_id(string $facilityid): ?Medical_Facility {
        # Create Facility Array
        $arr['facilityid'] = $facilityid;

        # Query For Facility
        $db = new DbQuery();
        $facility = $db->query_exact_match(Database::MEDICAL_FACILITY, $arr);
        if ($facility != NULL):
            $facility_object = new Medical_Facility($facility['facilityname'], $facility['address'],
                    $facility['contactnumber'], $facility['operatinghours'], $facility['facilityid']);
            return $facility_object;
        endif;
    }

    //============================================
    //      Appointments
    //============================================
    public static function get_all_facilities(): array {

        # Create An Array 
        $facility_arr = array();

        # Query For All The Facilities In The Database
        $db = new DbQuery();
        $facility_list = $db->get_all_documents_ordered(Database::MEDICAL_FACILITY, "facilityname");

        # Loop & Add To Empty Array
        foreach ($facility_list as $facility):
            $facility_object = new Medical_Facility($facility['facilityname'], $facility['address'],
                    $facility['contactnumber'], $facility['operatinghours'], $facility['facilityid']);
            $facility_arr[] = $facility_object;
        endforeach;

        return $facility_arr;
    }

}

?>