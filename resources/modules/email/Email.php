<?php

/*
 *  Email Class: FOR SYSTEM GENERATED MAIL MESSAGES
 *      - Mail Message Functions Can Be Customised
 * 
 */

// -- Import PHPMailer Class -- //
use PHPMailer\PHPMailer\PHPMailer;

//require '../vendor/autoload.php';

// -- Import Other Util Classes -- //
//require_once '../resources/config.php';

class Email extends PHPMailer {

    // -- SYSMAIL CREDENTIALS -- //
    private const SYSMAIL = "fyp.21.s2.24@gmail.com";
    private const APP_PASSWORD = "rzylfksaoemejptd";

    // -- CONSTRUCTOR -- //
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

    // --  Function Called In Each Template Function To Send Email -- //
    public static function sendEmail(string $to, string $subject, string $message): void {

        // -- Create New Email Object
        $mail = new Email();
        $mail->addAddress($to);

        // -- Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = $message;
        $mail->send();
    }

   
}
?>

