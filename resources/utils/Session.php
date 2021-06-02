<?php

/*
 * @author yanying (Tracy)
 */
/* Load Config File */
require_once '../resources/config.php';
require_once UTILS_PATH . '/DbQuery.php';
require_once UTILS_PATH . '/Database.php';
require_once UTILS_PATH . '/Time.php';
require_once UTILS_PATH . '/ArrayCreation.php';
require_once UTILS_PATH . '/StringUtils.php';
require_once ENUMS_PATH . '/Appointment_Status.php';

class Session {

    private bool $isloggedin;
    private string $sessionid;
    private string $token;
    private string $ipaddress;

    // -- Constructor -- //
    public function __construct(bool $isloggedin, string $sessionid, string $token, string $ipaddress) {
        $this->isloggedin = $isloggedin;
        $this->sessionid = $sessionid;
        $this->token = $token;
        $this->ipaddress = $ipaddress;
    }

    // -- Getters -- //
    public function get_isloggedin(): bool {
        return $this->isloggedin;
    }

    public function get_sessionid(): string {
        return $this->sessionid;
    }

    public function get_token(): string {
        return $this->token;
    }

    public function get_ipaddress(): string {
        return $this->ipaddress;
    }

    // -- Setters -- //
    public function set_isloggedin(bool $isloggedin): void {
        $this->isloggedin = $isloggedin;
    }

    public function set_sessionid(string $sessionid): void {
        $this->sessionid = $sessionid;
    }

    public function set_token(string $token): void {
        $this->token = $token;
    }

    public function set_ipaddress(string $ipaddress): void {
        $this->ipaddress = $ipaddress;
    }

}

?>