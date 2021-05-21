<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/viewappointments.css"/> 
    <title>View Appointments</title>
</head>
<?php 

require_once '../resources/config.php';
?>
       <?php include COMPONENT_PATH . '/bootstrap.php'; ?>


       <?php include COMPONENT_PATH . '/navbar.php'; ?>
<body>

<!--
   Developed By FYP-21-S2-24
-->
<!-- This File Is Solely Used For Debugging -->
<?php
session_start();
/* Load Config File */


require_once '../resources/config.php';
require_once ENTITIES_PATH . '/Account_User.php';
require_once ENTITIES_PATH . '/Appointment_Record.php';



// -- Check If User Is Signed In (When Redirect or Load The Page) -- //
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_SESSION["user"])) {
        $user = unserialize($_SESSION["user"]);
        echo '<div class="row bg-light py-4">';
        echo '</div>';
        echo '<div class="row bg-light">';
        echo '<div class="col-xs-3 col-md-2 px-5 mx-md-1 mx-lg-0">';
        echo '<img src="https://via.placeholder.com/100" class="rounded float-start" alt="...">';
        echo '</div>';
        echo '<div class="col-xs-8 col-md-6">';
        echo '<h1 class="display-6">'.$user->get_fullname() . '</h1>';
        echo '<h1 class="lead"> Gender: '.$user->get_gender() . '</h1>';
        echo '<h1 class="lead"> Date of Birth: '.$user->get_dob() . '</h1>';
        $email['email'] = $user->get_email();
        $upcoming_arr = Appointment_Record::get_upcoming_appointments($email);
        echo '</div>';
        echo '<div class="col-md-2">
            <div class="row py-1 col-lg-12 mx-auto">
        <button type="button" class="btn btn-secondary float-end">Edit Profile</button>
            </div>
            
        </div>';
        
        echo '</div>';
        echo '</div>';
        echo '<div class="row bg-light py-4">';
        echo '</div>';
        echo'
        <div class="container">
        <div class="row mt-3">
            <div class="d-grid gap-2 col-6 mx-auto">
        <button type="button" class="btn btn-info btn-lg pb-2"> <p class="h4"> Create New Appointment </p></button>
            </div>
            </div>
            </div>';
        if ($upcoming_arr == NULL) {
            echo "NUll";
        } else {
            echo'<!-- Nav tabs -->
            <div class="container">
            
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab" aria-controls="upcoming" aria-selected="true">Upcoming</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="missed-tab" data-bs-toggle="tab" data-bs-target="#missed" type="button" role="tab" aria-controls="missed" aria-selected="false">Missed</button>
              </li>
            </ul>
            ';
            echo '
            <div class="mt-4">
                <div class="tab-content">
                    <div class="tab-pane active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 g-4">';
            // For Each Upcoming Appointment Record
            foreach ($upcoming_arr as $record) {
                echo '<div class="col">
                        <div class="card" style="border-radius: 10px;">
                            <div class="card-header">';
                echo "" . $record->get_appointmenttype(); // Return String
                echo'        </div> <!-- CARD HEADER -->
                        <div class="card-body">';
                
                echo "Appointment ID: " . $record->get_appointmentid(); // Return Appointment ID
                echo "<br>Appointment Status: " . $record->get_appointmentstatus(); // Return Appointment status
                echo "<br><br>Date: " . $record->get_date(); // Returns Date
                echo "<br>Time: " . $record->get_time(); // Returns Date
                echo "<br>Location: " . $record->get_facility()->get_facilityname(); // Returns Date
                
                // $record->get_facility(); will return `Medical_Facility` object
                echo "<br>Address: " . $record->get_facility()->get_address();
                echo "<br>Contact Number: " . $record->get_facility()->get_contactnumber();
                echo '
                <div class="row m-2 text-center">
                    <div class="col">
                        <button type="button" class="btn btn-danger col-12">Cancel</button>
                    </div> <!-- BUTTON CANCEL COLUMN -->
                    <div class="col">
                        <button type="button" class="btn btn-info col-12">Reschedule</button>
                    </div> <!-- BUTTON RESCEHDULE COLUMN -->
                </div> <!-- BUTTON ROW-->  
                    </div> <!-- CARD BODY -->
                </div> <!-- CARD -->
            </div> <!-- COLUMN CARD -->';
            }
            echo'</div> <!-- TAB FOR UPCOMING -->
                <div class="tab-pane" id="missed" role="tabpanel" aria-labelledby="missed-tab">
                    testing
                </div>
                </div> <!-- TAB-CONTENT -->
            </div> <!-- MT-3 -->
            </div> <!-- CONTAINER -->
            </div>';
        }
    }
}
?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

<!-- Option 2: Separate Popper and Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>


</body>
</html>