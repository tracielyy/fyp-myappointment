<!-- 
    Developed By FYP-21-S2-24
-->
<?php
/*
 * @author yanying (Tracy)
 */

# --  Set It To Singapore TimeZone -- #
date_default_timezone_set('Asia/Singapore');

class Time {

    private ?string $date;
    private ?string $time;

    private const DATE_FORMAT_DEFAULT = "d-m-Y";
    private const TIME_FORMAT_DEFAULT = "H:i:s";

    // -- Constructor -- //
    function __construct(?string $date = NULL, ?string $time = NULL) {

        if ($date == NULL) {
            $this->date = (string) date(self::DATE_FORMAT_DEFAULT);
        } else {
            $this->date = $date;
        }

        if ($time == NULL) {
            $this->time = (string) date(self::TIME_FORMAT_DEFAULT); // -- Default 24 Hours with seconds
        } else {
            $this->time = $time;
        }
    }

    // -- Getters -- //
    public function get_time() {
        return $this->time;
    }

    public function get_date() {
        return $this->date;
    }

    public function get_full_date() {
        return $this->date . " " . $this->time;
    }

    // Some static methods
    public static function get_current_date(): string {
        return (string) date("d-m-Y");
    }

    public static function get_current_time(): string {
        return (string) date("H:i:s");
    }

    // -- Find Difference In Date & Time -- //
    public static function datetime_second_diff(Time $current, Time $comparison): int {
        
        # Make Time Object To DateTime Object To Use The Functions
        $now = new DateTime($current->get_full_date());
        $given_date = new DateTime($comparison->get_full_date());
        
        # Get The Difference In The Dates
        $interval_seconds = $now->getTimestamp() - $given_date->getTimestamp();

        return $interval_seconds;
    }

    // -- Convert The Date & Time -- //
    public static function to_24hours(string $time_12hours, bool $with_seconds): string {
        $format = "";
        if ($with_seconds) {
            $format = "H:i:s";
        } else {
            $format = "H:i";
        }
        return (string) date($format, strtotime($time_12hours));
    }

    public static function to_12hours(string $time_24hours, bool $with_seconds): string {
        $format = "";
        if ($with_seconds) {
            $format = "g:i:s a";
        } else {
            $format = "g:i a";
        }
        return (string) date($format, strtotime($time_24hours));
    }
    
    // -- Change Format -- //
    public static function date_format_change(){
        
    }
    

}
?>