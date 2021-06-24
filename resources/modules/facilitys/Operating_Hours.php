<?php

/*
 * @author yanying (Tracie)
 */



/* Load Config File */
require_once '../resources/config.php';

class Operating_Hours {

    // Properties
    private string $openinghour;
    private string $closinghour;
    private bool $is24hours;

    // Constructor
    public function __construct(string $opening, string $closing, bool $is24hours) {
        $this->opening = $opening;
        $this->closing = $closing;
        $this->is24hours = $is24hours;
    }

    // Getters
    public function get_openinghour(): string {
        return $this->openinghour;
    }

    public function get_closinghour(): string {
        return $this->closinghour;
    }

    public function get_is24hours(): bool {
        return $this->is24hours;
    }

    // Setters
    public function set_openinghour(string $openinghour): void {
        $this->openinghour = $openinghour;
    }

    public function set_closinghour(string $closinghour): void {
        $this->closinghour = $closinghour;
    }

    public function set_is24hours(string $is24hours): void {
        $this->is24hours = $is24hours;
    }

    // Debugging: Logging
    public function _toString(): string {

        // Not 24 hours
        if ($this->is24hours == false) {
            return nl2br(PHP_EOL . Time::to_12hours($this->openinghour, false) . ' - ' . Time::to_12hours($this->closinghour, false) . PHP_EOL);
        } else {
            return '24 Hours';
        }
    }

    public static function initialise_operating_hours(array $operatinghours): Operating_Hours {
        $operatinghours_obj = new Operating_Hours($operatinghours['openinghour'], $operatinghours['closinghour'], $operatinghours['is24hours']);
        return $operatinghours_obj;
    }

}
