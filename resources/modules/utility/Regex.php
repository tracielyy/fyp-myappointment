<?php

/*
 * @author yanying (Tracy)
 */



/*
 *  This Class Does Not Have Setters (Manipulators)
 */


class Regex {

    private const NAME_PATTERN = "/^(?![ .]+$)[a-zA-Z ,]*$/";
    private const EMAIL_PATTERN = '/^[a-zA-Z0-9]+(.[_a-z0-9-]+)(?!.*[~@\%\/\\\&\?\,\'\;\:\!\-]{2}).*@[a-z0-9-]+(.[a-z0-9-]+)(.[a-z]{2,3})$/';
    private const PHONE_PATTERN = "/^[689]{1}[0-9]{7}$/"; // Singapore phone number length
    private const PASSWORD_PATTERN = "/^" . // Pattern Match From Start Of String
            "(?=.*[0-9])" . // At Least 1 Digit
            "(?=.*[a-z])" . // At Least 1 Lower Case Char
            "(?=.*[A-Z])" . // At Least 1 Upper Case Char
            "(?=.*[\*\.!@\$%^&\(\)\{\}\[\]:;<>,.\?\/\~_\+-=\|])" . // At Least 1 Special Chars
            ".{8,32}" . // 8 To 32 Chars In Total
            "$/";   // Pattern Match To End Of String

    // -- Utility Functions (Non-Manipulative) -- //
    public static function validate_email(string $email): bool {
        if (preg_match(self::EMAIL_PATTERN, $email)) {
            return true;
        }
        return false;
    }

    public static function validate_name(string $name): bool {
        if (preg_match(self::NAME_PATTERN, $name)) {
            return true;
        }
        return false;
    }

    public static function validate_phone(string $contactnumber): bool {
        if (preg_match(self::PHONE_PATTERN, $contactnumber)) {
            return true;
        }
        return false;
    }

    public static function validate_password(string $password): bool {
        if (preg_match(self::PASSWORD_PATTERN, $password)) {
            return true;
        }
        return false;
    }

}

?>
