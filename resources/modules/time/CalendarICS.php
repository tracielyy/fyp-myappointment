<?php

/*
 * @author yanying (Tracie)
 */

/* Load Config File */
//require_once '../resources/config.php';
require_once APPT_MOD . '/Appointment_Record.php';
require_once APPT_MOD . '/Appointment_Slot.php';

/*
 *       # CALENDAR INVITES #
 */

date_default_timezone_set('UTC');

class CalendarICS {

    private string $data;
    private string $title;

    private const ICS_DATE_FORMAT = "Ymd\THis";

    public function __construct(string $start, string $end, string $title, string $description = "", string $location = "") {
        $this->title = $title;
        $this->data = "BEGIN:VCALENDAR" .
                "\nVERSION:2.0" .
                "\nMETHOD:PUBLISH" .
                "\nCALSCALE:GREGORIAN" .
                "\nBEGIN:VEVENT" .
                "\nDTSTART:" . date(self::ICS_DATE_FORMAT, strtotime($start)) .
                "\nDTEND:" . date(self::ICS_DATE_FORMAT, strtotime($end)) .
                "\nLOCATION:" . $location .
                "\nTRANSP: OPAQUE" .
                "\nSEQUENCE:0" .
                "\nUID:" .
                "\nDTSTAMP:" . date(self::ICS_DATE_FORMAT) .
                "\nSUMMARY:" . $title .
                "\nDESCRIPTION:" . $description .
                "\nPRIORITY:1" .
                "\nCLASS:PUBLIC" .
                "\nBEGIN:VALARM" .
                "\nTRIGGER:-PT10080M" .
                "\nACTION:DISPLAY" .
                "\nDESCRIPTION:Reminder" .
                "\nEnd:VALARM" .
                "\nEnd:VEVENT" .
                "\nEnd:VCALENDAR\n";
    }

    public function save(): void {
        file_put_contents($this->title . ".ics", $this->data);
    }

    public function show() {
        header("Content-type:text/calendar");
        header('Content-Disposition: attachment; filename="' . $this->title . '.ics"');
        Header('Content-Length: ' . strlen($this->data));
        Header('Connection: close');
        echo $this->data;
    }

    public function debug_print() {
        echo $this->data;
    }

}

?>
