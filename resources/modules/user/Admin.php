<?php

/*
 * @author yanying (Tracie)
 */
/* Load Config File */
//require_once '../resources/config.php';
require_once USER_MOD . '/Account_User.php';

require_once AUTH_MOD . '/Session.php';
require_once TIME_MOD . '/Time.php';

use Google\Cloud\Firestore\Transaction;


class Admin extends Account_User {

    private string $adminid;
    private string $adminname;

    // -- Constructor
    public function __construct(Session $session, string $usertype, Time $createdon, string $adminid, string $adminname,
            string $email, string $password = NULL) {

        parent::__construct($session, $usertype, $createdon, $email, $password);
        $this->adminid = $adminid;
        $this->adminname = $adminname;
    }

    // -- Getters
    public function get_adminid(): string {
        return $this->adminid;
    }

    public function get_adminname(): string {
        return $this->adminname;
    }

    // -- Setters
    public function set_adminname(string $adminname): void {
        $this->adminname = $adminname;
    }

    // Debugging: Logging
    public function __toString(): string {
        $str = parent::__toString();
        $str .= nl2br('Admin ID ' . $this->adminid . PHP_EOL . 'Admin  Name: ' . $this->adminname);
        return $str;
    }

    // -- Access Database
    public static function retrieve_admin_name(string $email): string {

        # Assign Email To Array
        $emailArr ["credentials"] = array(
            'email' => $email
        );

        # Retrieve `Account_User` Object
        $db = new DbQuery();
        $user_data = $db->fetch_one_document(Database::ACCOUNT_USER, $emailArr);

        # Filter & Return Full Name
        if ($user_data !== NULL):

            $adminname = $user_data['profile']['adminname'];
            return $adminname;

        endif;

        return "";
    }

    // UPDATE NAME //
    public static function update_adminname(string $admin_id, string $adminname): bool {
        $db = new DbQuery();
        $doc_ref = $db->get_db()->collection(Database::ACCOUNT_USER)->document($admin_id);
        $trnx_result = $db->get_db()->runTransaction(function (Transaction $transaction) use ($doc_ref, $adminname) {

            $snapshot = $transaction->snapshot($doc_ref);
            $db_adminname = $snapshot['profile']['adminname'];

            if ($db_adminname !== $adminname):
                # Update Admin Details
                $transaction->update($doc_ref, [
                    ['path' => 'profile.adminname', 'value' => $adminname]
                ]);
                return true;  # -- Name Not The Same As The One In The DB
            endif;
            return false;
        });
        return $trnx_result;
    }

}

?>

