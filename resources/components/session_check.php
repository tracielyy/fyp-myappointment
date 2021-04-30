<!-- This Is To Check If The Session Has Expired -->
<?php
// Max idle period in seconds
$idle = 60 * 10; // 10 mins 
// check to see if $_SESSION['timeout'] is set
if (isset($_SESSION['timeout'])) {
    $session_life = time() - $_SESSION['timeout'];
    // When Time Is Up
    if ($session_life > $idle) {
        // Any Alert
        echo
        '<script type = "text/javascript" >
                alert("Your session will expire soon!");
            </script>';
        // If User Do Not Want To Continue The Session
        // header("Location:'logout.php'");
    }
}
$_SESSION['timeout'] = time();
?>
