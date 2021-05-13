<!-- 
    Developed By FYP-21-S2-24
-->
<?php
/*
 * @author yanying (Tracy)
 */

class Time {

    private string $date;
    private string $time;

    function __construct(string $date = "", string $time = "") {
        if ($date == "") {
            $this->date = (string) date("d-m-Y");
        }
        if ($time == "") {
            $this->time = (string) date("H:i:s"); // Default 24 Hours with seconds
        }
    }

    // Getters
    public function get_time() {
        return $this->time;
    }

    public function get_date() {
        return $this->date;
    }

    // Some static methods
    public static function get_current_date(): string {
        return (string) date("d-m-Y");
    }

    public static function get_current_time(): string {
        return (string) date("H:i:s");
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

}
?>