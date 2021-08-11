<?php

/*
 * @author yanying (Tracie)
 */
# -- Load Config File -- #
//require_once '../resources/config.php';
require_once USER_MOD . '/Admin.php';
require_once USER_MOD . '/Account_User.php';
require_once ENUMS_PATH . '/User_Type.php';

use Google\Cloud\Firestore\Transaction;


// -- The Top Admin Privilege -- //
class Super_Admin extends Admin {

    private ?string $secretpin;

    // -- Constructor
    public function __construct(Session $session, string $usertype, Time $createdon, string $adminid,
            string $adminname, string $email, string $password = NULL, ?string $secretpin = NULL) {

        parent::__construct($session, $usertype, $createdon, $adminid, $adminname, $email, $password);
        $this->secretpin = $secretpin;
    }

    // -- Getters
    public function get_secretpin(): string {
        return $this->secretpin;
    }

    // Debugging: Logging
    public function __toString(): string {
        $str = parent::__toString();
        return $str;
    }

    // -- Initialise Super Admin
    public static function initialise_super_admin(array $super_admin_info): Super_Admin {

        # Session Object
        $session_obj = Session::intialise_session($super_admin_info['session']);

        # Time Object
        $time_obj = Time::initialise_time($super_admin_info['accountdetails']['createdon']);

        # Super Admin Object
        $super_admin = new Super_Admin($session_obj, $super_admin_info['accountdetails']['usertype'], $time_obj, $super_admin_info['profile']['adminid'],
                $super_admin_info['profile']['adminname'], $super_admin_info['credentials']['email']);

        return $super_admin;
    }

    //============================================
    //      Methods Accessing Firestore Database 
    //============================================
    // -- CHECK IF USER IS SUPER ADMIN
    public static function check_super_admin(array $email): bool {

        # -- Retrieve User Information Array -- #
        $db = new DbQuery();
        $admin_user = $db->fetch_one_document(Database::ACCOUNT_USER, $email);

        # -- Check If It Match The `Admin` User_Type -- #
        if ($admin_user['accountdetails']['usertype'] == User_Type::SUPER_ADMIN) :
            return true;
        endif;
        return false;
    }

    // -- RETRIEVE SUPER ADMIN DATA
    public static function retrieve_super_admin(string $user_email): Super_Admin {

        $super_admin_data = Account_User::retrieve_account_data($user_email);
        return self::initialise_super_admin($super_admin_data);
    }

    public static function retrieve_pin_by_id(string $id): ?string {
        $db = new DbQuery();
        $data = $db->fetch_document_by_id(Database::ACCOUNT_USER, $id);
        return $data['credentials']['secretpin'];
    }

    // -- PASSWORD CHANGE
    public static function change_secretpin(string $email, string $new_secretpin): bool {

        # Update The New Password
        $user_doc_id = self::retrieve_user_doc_id($email);

        # If Valid User
        if ($user_doc_id !== NULL):
            $db = new DbQuery();

            # Update To New Password
            $secure = new Security();
            $new_hashed_pin= $secure->hash($new_secretpin);
            $user_doc_ref = $db->get_db()->collection(Database::ACCOUNT_USER)->document($user_doc_id);
            $trnx_result = $db->get_db()->runTransaction(function (Transaction $transaction)
            use ($user_doc_ref, $new_hashed_pin, $new_secretpin) {

                if (!empty($new_secretpin)):
                    $transaction->update($user_doc_ref, [
                        ['path' => 'credentials.secretpin', 'value' => $new_hashed_pin]
                    ]);
                    return true;
                endif;

                return false; # -- HAVE ISSUES IN CHANGING PASSWORD
            });
        endif; # -- CHECK IF THE USER ENTERS A CORRECT PASSWORD
        return $trnx_result;
    }

}

?>