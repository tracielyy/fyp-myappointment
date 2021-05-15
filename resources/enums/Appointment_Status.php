<!-- 
    Developed By FYP-21-S2-24
-->
<?php
/*
 * @author yanying (Tracy)
 */
abstract class Appointment_Status {

    // -- Status Visible To Patient -- //
    const MISSED = "Missed";
    const OPEN = "Open";
    const UPCOMING = "Upcoming";
    
    // -- Status Not Visible To Patient -- //
    const SEEN = "Seen";

}
?>