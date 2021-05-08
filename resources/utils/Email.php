<?php

/*
 *  Email Class: For System Generated Mail Messages
 */

// Import PHPMailer Class
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email extends PHPMailer {



    public function __construct($exceptions = null) {
        parent::__construct($exceptions);
        $this->isSMTP();
        $this->Host = 'smtp.gmail.com';
        $this->SMTPAuth = true;
        $this->Username = 'tracieqwynn@gmail.com';
        $this->Password = 'tdeguawjiftvobjk'; /* App Password*/
        $this->SMTPSecure = 'tls';
        $this->Port = 587;
        // Default Receipient Settings
        $this->From = "tracieqwynn@gmail.com";
        $this->FromName = "MyAppointment FYP-21-S2-24";
        
    }


    // Override Parent send()
    public function send() {
        echo 'Echo From Subclass';
        return parent::send();
    }

}
?>

