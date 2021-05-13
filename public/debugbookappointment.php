<!--
   Developed By FYP-21-S2-24
-->
<!-- This File Is Solely Used For Debugging -->
<?php
?>
<html>
    <head>
        <!-- Title -->
        <title>FYP-21-S2-24: Book Appointment</title>
        <!-- Styling -->
        <?php require COMPONENT_PATH . '/bootstrap.php' ?>
    </head>
    <body>
        <!-- PHP Logic (Validation) -->
        <?php
        // Used to store correct data
        $appointmentArr = array(
            'date' => '',
            'time' => ''
        );
        $validArr = array();

        // When Submit Appointment
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            /* Load Data to Array */
            foreach ($_POST as $key => $value) {
                if (isset($appointmentArr[$key])) {
                    $appointmentArr[$key] = htmlspecialchars($value);
                    $validArr[$key] = False; // Set All Field Validation Check As False
                }
            }
            // Possible Validation of Email Before Firestore Query
            /* ------------ Start Validation ------------ */
            /* ------------ End Validation ------------ */
            
            // Can Only Book Appointment When Required Fields Are Filled
            if (!in_array(FALSE, $validArr)) {
                
            }
        }
        ?>
        <!-- Appointment Booking Form -->

    </body>
</html>

