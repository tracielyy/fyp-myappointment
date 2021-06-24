<?php

/*
 * @author yanying (Tracie)
 */

/* Load Config File */
require_once '../resources/config.php';
require_once DB_MOD . '/DbQuery.php';
require_once DB_MOD . '/Database.php';
require_once USER_MOD . '/Patient.php';

require_once ENUMS_PATH . '/User_Type.php';

require_once UTIL_MOD . '/ArrayCreation.php';

/*
 *       # DISPLAY USER ACCOUNT #
 */

class RetrieveAccount {

    //  -- CHECK IF USER EXIST IN THE DATABASE
    public static function check_user_exist(string $email): bool {

        # Email Array
        $emailArr ["credentials"] = array(
            'email' => $email
        );

        # Query For User With The Given Email
        $db = new DbQuery();
        $emails_found = $db->select_exact_match(Database::ACCOUNT_USER, $emailArr);

        # Check If There Are Any Value Returned
        if (($emails_found !== NULL)):
            return True;  // There is existing user
        endif;

        return False;
    }

    // --  RETRIEVE FULL NAME OF ACCOUNT USER
    public static function retrieve_user_fullname(string $email): ?string {

        # Assign Email To Array
        $emailArr ["credentials"] = array(
            'email' => $email
        );

        # Retrieve `Account_User` Object
        $db = new DbQuery();
        $user_data = $db->select_exact_match(Database::ACCOUNT_USER, $emailArr);

        # Filter & Return Full Name
        if ($user_data !== NULL):
            
            # -- CHECK THE USER TYPE
            $name_arr = $user_data['profile']['name'];
            $full_name = $name_arr['firstname'] . " " . $name_arr['lastname'];
            return $full_name;

        endif;

        return null;
    }

    // -- Load User Data (Retrieve & Return User Data) -- //
    public static function retrieve_account_data(array $credentialArr): ?Account_User {

        # Credentials   
        $credentials = array(
            "credentials" => $credentialArr
        );

        # Retrieve User From Given Credentials
        $db = new DbQuery();
        $user_data = $db->select_exact_match(Database::ACCOUNT_USER, $credentials);

        # Store Any User Data In `Account_User` Object
        if ($user_data != NULL):

            # Initialise Object (Patient, Medical_Personnel, Facility_Admin, Super_Admin)
            return self::initialise_account_user($user_data);

        endif;

        return NULL;
    }

    // -- Initialise Account_User
    public static function initialise_account_user(array $user_object): Account_User {

        # Set To UserType
        $usertype = $user_object['accountdetails']['usertype'];
        switch ($usertype):

            // --  PATIENT -- //
            case User_Type::PATIENT:

                # Create Patient Object (WITHOUT STORING THE PASSWORD)
                return Patient::initialise_patient($user_object);

            // -- MEDICAL_PERSONNEL -- // 
            case User_Type::MEDICAL_PERSONNEL:
                break;

            // -- FACILITY_ADMIN -- //
            case User_Type::FACIILITY_ADMIN:
                break;

            // -- SUPER_ADMIN -- //
            case User_Type::SUPER_ADMIN:
                break;

            # Incase No Match Create Patient 
            default:
                return Patient::initialise_patient($user_object);
        endswitch;
    }

}
