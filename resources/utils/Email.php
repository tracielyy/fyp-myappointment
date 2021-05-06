<?php

/*
 *  Email Class: For System Generated Mail Messages
 */

class Email {

    private string $sendTo;
    private string $sendFrom;
    private string $subject;
    private string $message;
    private array $headers;
    private array $errors;

    public function __construct(string $sendTo, string $subject, string $message, ?string $sendFrom = "wynterz2525@gmail.com") {
        $this->sendTo = $sendTo;
        $this->subject = $subject;
        $this->message = $message;
        $this->sendFrom = $sendFrom;
        if ($this->sendTo != NULL) {
            $this->headers = array(
                'From' => $this->sendFrom,
                'Reply-To' => $this->sendTo,
                'X-Mailer' => 'PHP/' . phpversion()
            );
        }
    }

    // Getter
    public function get_send_to() {
        return $this->sendTo;
    }

    public function get_send_from() {
        return $this->sendFrom;
    }

    public function get_subject() {
        return $this->subject;
    }

    public function get_message() {
        return $this->message;
    }

    public function get_headers() {
        return $this->headers;
    }

    // Setter
    public function set_send_to(string $sendTo) {
        $this->sendTo = $sendTo;
    }

    public function set_send_from(string $sendFrom) {
        $this->sendFrom = $sendFrom;
    }

    public function set_subject(string $subject) {
        $this->subject = $subject;
    }

    public function set_message(string $message) {
        $this->message = $message;
    }

    // Send Email 
    public function send_mail() {
        //  -- Need To Check If Relevant Fields Are Not NULL Before Sending Email (** NOT IMPLEMENTED YET **)
        return mail($this->sendTo, $this->subject, $this->message, $this->headers);
    }

}
?>

