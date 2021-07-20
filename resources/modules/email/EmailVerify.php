<?php

class EmailVerify {

    private string $email;
    private string $otp;
    private Time $requestedon;

    public function __construct(string $email) {
        $this->email = $email;
    }

    public function get_otp(): string {
        return $this->otp;
    }

    public function get_requestedon(): Time {
        return $this->requestedon;
    }

    public function has_requested(): bool {
        $db = new DbQuery();
        $email_verify_data = $db->fetch_document_by_id(Database::EMAIL_VERIFY, $this->email);
        return ($email_verify_data !== null || !empty($email_verify_data)) ? true : false;
    }

    // -- Must Check `has_requested` before setting
    public function set_verify_data(): void {

        if ($this->has_requested()) {
            $db = new DbQuery();
            $email_verify_data = $db->fetch_document_by_id(Database::EMAIL_VERIFY, $this->email);
            $requestedon = new Time($email_verify_data['requestedon']['date'], $email_verify_data['requestedon']['time']);
            $this->otp = $email_verify_data['otp'];
            $this->requestedon = $requestedon;
        }
    }
    
    

}
