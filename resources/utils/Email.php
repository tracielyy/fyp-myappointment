<?php

/*
 *  Email Class: For System Generated Mail Messages
 * 
 */

// Import PHPMailer Class
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email extends PHPMailer {

    private const SYSMAIL = "fyp.21.s2.24@gmail.com";
    private const APP_PASSWORD = "bvggvsbnrvdektra";

    // -- Construct -- //
    public function __construct($exceptions = null) {
        parent::__construct($exceptions);
        $this->isSMTP();
        $this->Host = 'smtp.gmail.com';
        $this->SMTPAuth = true;
        $this->Username = self::SYSMAIL;
        $this->Password = self::APP_PASSWORD; /* @fyp-21-s2-24's App Password */
        $this->SMTPSecure = 'tls';
        $this->Port = 587;

        // -- Default Receipient Settings -- //
        $this->From = self::SYSMAIL;
        $this->FromName = "MyAppointment FYP-21-S2-24";
    }

    // -- Override Parent send() -- //
    public function send() {
        // echo 'Echo From Subclass';
        return parent::send();
    }

}
?>

