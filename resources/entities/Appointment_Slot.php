<?php

/*
 * @author yanying (Tracie)
 */
/* Load Config File */
require_once '../resources/config.php';
require_once 'Appointment_Record.php';
require_once 'Account_User.php';
require_once  'Session.php';
require_once  'Time.php';
require_once ENUMS_PATH . '/User_Type.php';
require_once UTILS_PATH . '/DbQuery.php';
require_once UTILS_PATH . '/Database.php';

require_once UTILS_PATH . '/ArrayCreation.php';


class Appointment_Slot {

    // -- Properties
    private string $slotid;
    private Time $appointmentschedule;
    // Specialist: ONLY 1 Patient, Check Up & Dr Consult: max 10
    private array $patientlist; 
    private ?bool $available;


    // -- Constructor
    public function __construct(string $slotid, Time $appointmentschedule, array $patientlist, bool $available = NULL) {
        $this->slotid = $slotid;
        $this->appointmentschedule = $appointmentschedule;
        $this->patientlist = $patientlist;
        $this->available = $available;

    }

    // -- Getters
    public function get_slotid(): string {
        return $this->slotid;
    }
    
    # -- Print For Individual Appointment Slots
    public function get_slot_description(): string{
        $date = Time::date_format_change($this->appointmentschedule->get_date(), Time::DATE_FORMAT_APPOINTMENT);
        $time = Time::to_12hours($this->appointmentschedule->get_time(), false);
        
        return $date . ", " .$time;
    }

    public function get_appointmentschedule(): Time {
        return $this->appointmentschedule;
    }

    public function get_patientlist(): array {
        return $this->patientlist;
    }

    public function get_doctorlist(): array {
        return $this->doctorlist;
    }

    // -- Settters
    public function set_slotid(string $slotid): void {
        $this->slotid = $slotid;
    }

    public function set_appointmentschedule(Time $appointmentschedule): void {
        $this->appointmentschedule = $appointmentschedule;
    }

    public function set_patientlist(array $patientlist): void {
        $this->patientlist = $patientlist;
    }

    public function set_doctorlist(array $doctorlist): void {
        $this->doctorlist = $doctorlist;
    }

    // -- Use For Debugging/ Logging Purpose -- //
    public function __toString(): string {
        $str = nl2br('Slotid: ' . $this->slotid . PHP_EOL . 'Schedule' . $this->appointmentschedule . PHP_EOL);

        # Loop & Display Patients For Allotted To The Time Slot
        $str .= nl2br("Patient List" . PHP_EOL);
        foreach ($this->patientlist as $patient):
            $str .= $patient . " ";
        endforeach;


        # Loop & Display Doctors Available At The Time Slot
        $str .= nl2br(PHP_EOL . "Doctor List" . PHP_EOL);
        foreach ($this->doctorlist as $doctor):
            $str .= $doctor . " ";
        endforeach;

        return $str;
    }

}

?>