<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <style>
<?php include './css/viewappointments.css'; ?>
        </style>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

        <title>View Appointments</title>
    </head>
    <?php
    require_once '../resources/config.php';
    ?>
    <?php include COMPONENTS_PATH . '/bootstrap.php'; ?>
    <?php include COMPONENTS_PATH . '/navbar.php'; ?>
    <body>

        <!--
           Developed By FYP-21-S2-24
        -->
        <!-- This File Is Solely Used For Debugging -->
        <?php
        session_start();
        /* Load Config File */

        require_once '../resources/config.php';
        require_once ENUMS_PATH . '/User_Type.php';
        require_once ENTITIES_PATH . '/Account_User.php';
        require_once ENTITIES_PATH . '/Appointment_Record.php';
        require_once FUNCTIONS_PATH . '/AccountUserFunctions.php';
        require_once FUNCTIONS_PATH . '/PatientFunctions.php';

        // -- Check If User Is Signed In (When Redirect or Load The Page) -- //
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            if (isset($_SESSION["user"])) {
                $user = unserialize($_SESSION["user"]);
                $user_type = $user->get_usertype();

                if (User_Type::check_user_type(User_Type::PATIENT, $user_type)) {
                    echo
                    '<div class="row bg-light py-4">
                    <div class="row bg-light">
                        <div class="col-xs-3 col-md-2 px-5 mx-md-1 mx-lg-0">
                            <img src="https://via.placeholder.com/100" class="rounded float-start" alt="...">
                        </div> <!-- col -->
                    <div class="col-xs-8 col-md-6">';
                    echo '<h1 class="display-6">' . $user->get_fullname() . '</h1>';
                    echo '<h1 class="lead"> Gender: ' . $user->get_gender() . '</h1>';
                    echo '<h1 class="lead"> Date of Birth: ' . $user->get_dob() . '</h1>';
                    echo
                    '   </div>  <!-- col -->';
                    echo
                    '<div class="col-md-2"> <!-- col -->
                <div class="row py-1 col-lg-12 mx-auto">
            <button type="button" class="btn btn-secondary float-end">Edit Profile</button>
                </div> <!-- col -->
            </div> <!-- col -->';

                    echo '<div class="row bg-light py-4">';

                    $email['credentials']['email'] = $user->get_email();

                    $upcoming_arr = PatientFunctions::get_upcoming_appointments($email);
                    echo '
            <div class="container">
            <div class="row mt-3">
                <div class="d-grid gap-2 col-6 mx-auto">
            <button type="button" class="btn btn-info btn-lg pb-2"> <p class="h4"> Create New Appointment </p></button>
                </div>
                </div>
                </div>';
                    if ($upcoming_arr == null) {
                        echo "<br/>No Upcoming Appointments";
                    } else {
                        echo '<!-- Nav tabs -->
                <div class="container">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#home">Upcoming</a></li>
                        <li><a data-toggle="tab" href="#menu1">Missed</a></li>
                    </ul>
                ';
                        echo '
                <div class="mt-4">
                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 g-4">';
                        // For Each Upcoming Appointment Record
                        foreach ($upcoming_arr as $record) {
                            echo '<div class="col">
                            <div class="card" style="border-radius: 10px;">
                                <div class="card-header">';
                            echo "" . $record->get_appointmenttype(); // Return String
                            echo '        </div> <!-- CARD HEADER -->
                            <div class="card-body">';

                            echo "Appointment ID: " . $record->get_appointmentid(); // Return Appointment ID
                            echo "<br>Appointment Status: " . $record->get_appointmentstatus(); // Return Appointment status
                            echo "<br><br>" . $record->get_scheduledon()->get_date(); // Returns Date
                            echo "<br>Time: " . $record->get_scheduledon()->get_time(); // Returns Time
                            echo "<br>Location: " . $record->get_facility()->get_facilityname(); // Returns Facility Name
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
                        echo '</div> <!-- TAB FOR UPCOMING -->
                    <div id="menu1" class="tab-pane fade">
                    <h3>HOME</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    </div> <!-- TAB-MISSED-CONTENT -->
                </div> <!-- MT-3 -->
                </div> <!-- CONTAINER -->
                </div>
                </div>';
                    }
                } else {
                    # Possible Redirect To Index.php
                    header("Location:index.php");
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