<?php

/*
 * @author yanying (Tracie)
 * @author nanta
 */

class Security {
    /*
      Password is only hashed without any decryption.
     */

    //Properties
    //Encryption key DO NOT CHANGE!
    private const KEY = 'FYffB2s#k6BO@%6SM&zJny150MlQJg5&@ztZbz!V90@tUYMx';

    private string $iv = '0j8d,f:fsd2T0fq(';

    // -- Constructor -- //
    public function __construct() {
        
    }

    // -- Static Functions -- //
    //encryption of data with AES-256-CBC
    public function encrypt($data) {
        $encrypt_key = base64_decode(self::KEY);
//        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
//        echo $iv;
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encrypt_key, 0, $this->iv);
        return base64_encode($encrypted . '::' . $this->iv);
    }

    //decryption of data with AES-256-CBC
    public function decrypt($data) {
        $decrypt_key = base64_decode(self::KEY);
        list($encrypted_data, $this->iv) = array_pad(explode('::', base64_decode($data), 2), 2, null);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $decrypt_key, 0, $this->iv);
//        return $this->iv;
    }

    //This function only hashes the password or a of string
    public function hash($password): string {
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
        return $hashed_pass;
    }

    //This function verifies the password entered is correct or not 
    public function compareHash($password, $stored_password): string {
        if (password_verify($password, $stored_password)) {
            return 'CORRECT_PASSWORD';
        } else {
            return 'INCORRECT_PASSWORD';
        }
    }

}

?>