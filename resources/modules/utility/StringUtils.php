<?php

/*
 * @author yanying (Tracie)
 */

class StringUtils {

    // -- String Cleaning -- //
    public static function clean_input(string $input): string {
        $input = trim($input);  // Remove leading and trailing whitespace 
        $input = stripslashes($input);  // Remove '\' (slashes)
        $input = htmlspecialchars($input);  // Treat special chars as HTML entities
        $input = strtolower($input);    // All chars to lowercase
        return $input;
    }

    // -- Private Function For String Comparison -- //
    public static function string_equal(string $str1, string $str2): bool {
        if ($str1 == $str2):
            return true;
        endif;
        return false;
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
        for ($i = 0; $i < $length; $i++) :
            $token .= $token_repo[random_int(0, $max - 1)];
        endfor;

        return $token;
    }

    public static function object_to_array( $obj) {
        if (is_object($obj)) {
            $obj = (array) self::dismount($obj);
        }
        if (is_array($obj)) {
            $new = array();
            foreach ($obj as $key => $val) {
                $new[$key] = self::object_to_array($val);
            }
        } else {
            $new = $obj;
        }

        return $new;
    }

    public static function dismount($object) {
        $reflectionClass = new ReflectionClass(get_class($object));
        $array = array();


        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true); // For Retrieval Of Private or Protected Properties
            $array[$property->getName()] = $property->getValue($object);
            $property->setAccessible(false);
        }

        if ($reflectionClass->getParentClass() != False) {
            foreach ($reflectionClass->getParentClass()->getProperties() as $property) {
                $property->setAccessible(true);
                $array[$property->getName()] = $property->getValue($object);
                $property->setAccessible(false);
            }
        }
        return $array;
    }

}
