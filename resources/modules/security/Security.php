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
    private static $key = 'FYffB2s#k6BO@%6SM&zJny150MlQJg5&@ztZbz!V90@tUYMx';

    // -- Constructor -- //
    public function __construct(){

    }

    // -- Static Functions -- //
    public function encrypt($data) {
        $encrypt_key = base64_decode(self::$key);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encrypt_key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
        }
    
    public function decrypt($data) {
        $decrypt_key = base64_decode(self::$key);
        list($encrypted_data, $iv) = array_pad(explode('::', base64_decode($data), 2),2,null);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $decrypt_key, 0, $iv);
        }
    

    //This function only hashes the password or a of string
    public function hash($password) : string {
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
        return $hashed_pass;
    }

    //This function verifies the password entered is correct or not 
    public function compareHash($password, $stored_password) : string
    {
        if(password_verify($password, $stored_password))
        {
            return "CORRECT_PASSWORD";
        }
        else
        {
            return "INCORRECT_PASSWORD";
        }
    }

}
?>