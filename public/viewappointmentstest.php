<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
    <?php include './css/viewappointments.css';
    ?>
    </style>



    <!--
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->

    <title>View Appointments</title>
</head>

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
require_once ENUMS_PATH . '/User_Type.php';
require_once FUNCTIONS_PATH . '/PatientFunctions.php';
require_once FUNCTIONS_PATH . '/AccountUserFunctions.php';

include COMPONENTS_PATH . '/bootstrap.php';
include COMPONENTS_PATH . '/navbar.php';

// -- Check If User Is Signed In (When Redirect or Load The Page) -- //
if ($_SERVER['REQUEST_METHOD'] == "GET"):
    if (isset($_SESSION["user"])):
        $user = unserialize($_SESSION["user"]);
        $user_type = $user->get_usertype();
?>
    <div class="row bg-light py-4">
        <div class="row bg-light">
            <div class="col-xs-3 col-md-2 px-5 mx-md-1 mx-lg-0">
                <img src="https://via.placeholder.com/100" class="rounded float-start" alt="...">
            </div> <!-- col -->

            <div class="col-xs-8 col-md-6">
                <h1 class="display-6"> <?php echo $user->get_fullname(); ?> </h1>
                <h1 class="lead"> Gender: <?php echo $user->get_gender(); ?> </h1>
                <h1 class="lead"> Date of Birth: <?php echo $user->get_dob();?> </h1>
            </div> <!-- col -->

            <div class="col-md-2">
                <!-- col -->
                <div class="row py-1 col-lg-12 mx-auto">
                    <button type="button" class="btn btn-secondary float-end">Edit Profile</button>
                </div> <!-- col -->
            </div> <!-- col -->
        </div>
    </div>
    <?php 
        if (User_Type::check_user_type(User_Type::PATIENT, $user_type)):
            $email['credentials']['email'] = $user->get_email();
            
            // -- Upcoming Appointments -- //
            $upcoming_arr = PatientFunctions::get_upcoming_appointments($email);
            // -- Missed Appointments -- //
            $missed_arr = PatientFunctions::get_missed_appointments($email);
        ?>

    <div class="container">
        <div class="row mt-3">
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="button" class="btn btn-info btn-lg pb-2">
                    <p class="h4"> Create New Appointment </p>
                </button>
            </div>
        </div>
    </div>

    <div class="container">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-upcoming-tab" data-bs-toggle="tab"
                    data-bs-target="#nav-upcoming" type="button" role="tab" aria-controls="nav-upcoming"
                    aria-selected="true">Upcoming</button>
                <button class="nav-link" id="nav-missed-tab" data-bs-toggle="tab" data-bs-target="#nav-missed"
                    type="button" role="tab" aria-controls="nav-missed" aria-selected="false">Missed</button>
            </div>
        </nav>

        <div class="mt-4">
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-upcoming" role="tabpanel" aria-labelledby="nav-upcoming-tab">
                    <?php    
                        if ($upcoming_arr == null):
                            echo '<br><p class="text-center text-muted display-6">No upcoming appointments </p>' ;
                        else:
                        ?>
                    <!-- Nav tabs -->
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 g-4">
                        <?php // For Each Upcoming Appointment Record
                            foreach ($upcoming_arr as $record):
                            ?>
                        <div class="col">
                            <div class="card" style="border-radius: 10px;">
                                <div class="card-header">
                                    <?php echo $record->get_appointmenttype(); // Return String ?>
                                </div> <!-- CARD HEADER -->
                                <div class="card-body">
                                    Appointment ID:
                                    <?php echo $record->get_appointmentid(); // Return Appointment ID ?>
                                    <br>Appointment Status:
                                    <?php echo $record->get_appointmentstatus(); // Return Appointment status ?>
                                    <br>
                                    <br>Date: <?php echo $record->get_scheduledon()->get_time(); // Returns Date ?>
                                    <br>Time: <?php echo $record->get_scheduledon()->get_time(); // Returns Date ?>
                                    <br>Location:<?php echo $record->get_facility()->get_facilityname(); // Returns Date ?>

                                    <!-- $record->get_facility(); will return `Medical_Facility` object -->
                                    <br>Address: <?php echo $record->get_facility()->get_address(); ?>
                                    <br>Contact Number:
                                    <?php echo $record->get_facility()->get_contactnumber(); ?>
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
                        </div> <!-- COLUMN CARD -->
                        <?php endforeach ?>
                    </div>
                </div> <!-- TAB FOR UPCOMING -->
                <?php
                endif;
                ?>

                <div class="tab-pane fade" id="nav-missed" role="tabpanel" aria-labelledby="nav-missed-tab">
                    <?php    
                        if ($missed_arr == null):
                            echo '<br><p class="text-center text-muted display-6">No missed appointments </p>' ;
                        else:
                        ?>
                    <!-- Nav tabs -->
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 g-4">
                        <?php // For Each Upcoming Appointment Record
                            foreach ($missed_arr as $record):
                            ?>
                        <div class="col">
                            <div class="card" style="border-radius: 10px;">
                                <div class="card-header">
                                    <?php echo $record->get_appointmenttype(); // Return String ?>
                                </div> <!-- CARD HEADER -->
                                <div class="card-body">
                                    Appointment ID:
                                    <?php echo $record->get_appointmentid(); // Return Appointment ID ?>
                                    <br>Appointment Status:
                                    <?php echo $record->get_appointmentstatus(); // Return Appointment status ?>
                                    <br><br>Date: <?php echo $record->get_scheduledon()->get_date(); // Returns Date ?>
                                    <br>Time: <?php $record->get_scheduledon()->get_time(); // Returns Date ?>
                                    <br>Location:<?php echo $record->get_facility()->get_facilityname(); // Returns Date ?>

                                    <!-- $record->get_facility(); will return `Medical_Facility` object -->
                                    <br>Address: <?php echo $record->get_facility()->get_address(); ?>
                                    <br>Contact Number:
                                    <?php echo $record->get_facility()->get_contactnumber(); ?>
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
                        </div> <!-- COLUMN CARD -->
                        <?php endforeach;
                        endif; ?>
                    </div> <!-- TAB-MISSED-CONTENT -->
                </div> <!-- TAB CONTENT -->
            </div>
        </div> <!-- MT-4 -->
    </div><!-- CONTAINER -->
    <?php 
            else: 
                header("Location:debugIndex.php");;
            endif;
        else: header("Location:login.php");;
        endif;
    endif;
?>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous">
    </script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js"
        integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous">
    </script>

</body>

</html>