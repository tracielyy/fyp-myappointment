<!DOCTYPE html>
<html lang="en">

    <?php
    session_start();
    require_once '../resources/config.php';
    require_once ENTITIES_PATH . '/Account_User.php';
    require_once ENTITIES_PATH . '/Appointment_Record.php';
    require_once ENUMS_PATH . '/User_Type.php';
    require_once FUNCTIONS_PATH . '/PatientFunctions.php';
    require_once FUNCTIONS_PATH . '/AccountUserFunctions.php';
    ?>
    <?php $pageName = "viewappointment"; ?>

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

    <title>View Appointments</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
        function upcoming_cancel_appt(appt_info) {
            var info = appt_info.split("~");
            var appt_id = info[0];
            var appt_type = info[1];
            var appt_date = info[2];
            var appt_time = info[3];

            // -- Testing
            console.log(appt_id);

            // jQuery Calls To Set The Modal Information 
            $("#upcoming_cancel_appt_type").html(appt_type);
            $("#upcoming_cancel_appt_date").html(appt_date);
            $("#upcoming_cancel_appt_time").html(appt_time);
            $("#upcoming_cancel_btn").val(appt_id);
        }

    </script>


    <?php
    include COMPONENTS_PATH . '/bootstrap.php';
    ?>
</head>
<?php
// -- Check If User Is Signed In (When Redirect or Load The Page) -- //
if (isset($_SESSION["user"])):

    # "Unboxin" User Information
    $user = unserialize($_SESSION["user"]);
    $user_email = $user->get_email();
    $user_type = $user->get_usertype();
    include_once COMPONENTS_PATH . '/navbar-loggedin.php';

    // -- When User Click On The Buttons -- //
    if ($_SERVER['REQUEST_METHOD'] == "POST"):

        # -- Triggered By Pop Up "Cancel" Button -- #
        if (isset($_POST['cancel'])):

            # Delimit & Get Information
            $appointmentid = $_POST['cancel'];
            echo $appointmentid;

        # -- Cancel The Appointment : Function is working (COMMENT IT FOR  OTHER TESTING PURPOSE) -- #
        PatientFunctions::cancel_appointment($user_email, $appointmentid);

        elseif (isset($_POST['reschedule'])):
        # Ask For Reschedule Date & Time (Could Be Some Pop-Up) -- Return Rescheduled Array #
        endif;

    endif;
    ?>
    <div class="row bg-light py-4">
        <div class="row bg-light">
            <div class="col-xs-3 col-md-2 px-5 mx-md-1 mx-lg-0">
                <img src="https://via.placeholder.com/100" class="rounded shadow float-start" alt="...">
            </div> <!-- col -->

            <div class="col-xs-8 col-md-6">
                <h1 class="display-6"> <?php echo $user->get_fullname(); ?> </h1>
                <h1 class="lead"> Gender: <?php echo $user->get_gender(); ?> </h1>
                <h1 class="lead"> Date of Birth: <?php echo $user->get_dob(); ?> </h1>
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
        $email['credentials']['email'] = $user_email;

        // -- Upcoming Appointments -- //
        $upcoming_arr = PatientFunctions::get_upcoming_appointments($email);
        // -- Missed Appointments -- //
        $missed_arr = PatientFunctions::get_missed_appointments($email);
        ?>

        <div class="container">
            <div class="row mt-3">
                <div class="d-grid gap-2 col-6 mx-auto">
                    <button type="button" class="btn btn-info btn-lg pb-2">
                        <p class="h4 text-light mt-1"> Create New Appointment </p>
                    </button>
                </div>
            </div>
        </div>

        <div class="container">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-upcoming-tab" data-bs-toggle="tab" data-bs-target="#nav-upcoming"
                            type="button" role="tab" aria-controls="nav-upcoming" aria-selected="true">Upcoming</button>
                    <button class="nav-link" id="nav-missed-tab" data-bs-toggle="tab" data-bs-target="#nav-missed" type="button"
                            role="tab" aria-controls="nav-missed" aria-selected="false">Missed</button>
                </div>
            </nav>
            <form id= "apptform" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="mt-4">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-upcoming" role="tabpanel" aria-labelledby="nav-upcoming-tab">
                            <?php
                            if ($upcoming_arr == null):
                                ?>
                                <br>
                                <p class="text-center text-muted display-6">No upcoming appointments </p>
                            </div>
                            <?php
                        else:
                            ?>
                            <!-- Nav tabs -->
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 g-4">
                                <?php
                                // For Each Upcoming Appointment Record
                                foreach ($upcoming_arr as $record):
                                    ?>
                                    <div class="col">
                                        <div class="card shadow" style="border-radius: 10px;">
                                            <div class="card-header">
                                                <?php echo $record->get_appointmenttype(); // Return String  ?>
                                            </div> <!-- CARD HEADER -->
                                            <div class="card-body">
                                                Appointment ID:
                                                <?php echo $record->get_appointmentid(); // Return Appointment ID  ?>
                                                <br>Appointment Status:
                                                <?php echo $record->get_appointmentstatus(); // Return Appointment status  ?>
                                                <br>
                                                <br>Date: <?php echo Time::date_format_change($record->get_scheduledon()->get_date(), Time::DATE_FORMAT_APPOINTMENT); // Returns Date                                                         ?>
                                                <br>Time: <?php echo Time::to_12hours($record->get_scheduledon()->get_time(), false); // Returns Time                                                         ?>
                                                <br>Location: <?php echo $record->get_facility()->get_facilityname(); // Returns Date                                                         ?>

                                                <!-- $record->get_facility(); will return `Medical_Facility` object -->
                                                <br>Address: <?php echo $record->get_facility()->get_address(); ?>
                                                <br>Contact Number:
                                                <?php echo $record->get_facility()->get_contactnumber(); ?>
                                                <div class="row m-2 text-center">
                                                    <?php
                                                    // -- Bunch Of Appointment Info To Be Passed To Button Function -- //
                                                    $appt_info = $record->get_appointmentid() . "~" . $record->get_appointmenttype() .
                                                            "~" . Time::date_format_change($record->get_scheduledon()->get_date(), Time::DATE_FORMAT_APPOINTMENT) .
                                                            "~" . Time::to_12hours($record->get_scheduledon()->get_time(), false);
                                                    ?>
                                                    <div class="col">
                                                        <button value="<?php echo $appt_info; ?>" type="button" class="btn btn-danger col-12" data-bs-toggle="modal" data-bs-target="#cancelappt"
                                                                onclick="upcoming_cancel_appt(this.value)">Cancel</button>
                                                    </div> <!-- BUTTON CANCEL COLUMN -->
                                                    <div class="col">
                                                        <button type="button" class="btn btn-info col-12 text-light">Reschedule</button>
                                                    </div> <!-- BUTTON RESCEHDULE COLUMN -->
                                                    <!-- Modal -->
                                                    <div class="modal hide fade" id="cancelappt" tabindex="-1" aria-labelledby="cancelappointment" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="cancelappointment">Cancellation Confirmation</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>

                                                                <div class="modal-body" id="upcoming_cancel_modal_body">
                                                                    You are about to cancel your appointment of <br><span id="upcoming_cancel_appt_type"></span> on
                                                                    <br>Date: <span id="upcoming_cancel_appt_date"></span>
                                                                    <br>Time: <span id="upcoming_cancel_appt_time"></span>
                                                                    <br> <br> <b> Warning: Action cannot be revoked </b>
                                                                </div>
                                                                <div class="modal-footer">

                                                                    <button type = "button" class = "btn btn-secondary" data-bs-dismiss = "modal">Close</button>
                                                                    <button id="upcoming_cancel_btn" type = "submit" class = "btn btn-danger"  name="cancel" >Cancel</button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div> <!--MODAL END-->
                                                </div> <!--BUTTON ROW-->
                                            </div> <!--CARD BODY-->
                                        </div> <!--CARD-->
                                    </div> <!--COLUMN CARD-->
                                <?php endforeach;
                                ?>
                            </div>
                        </div><!-- TAB-MISSED-CONTENT -->
                    <?php endif; ?>

                    <div class="tab-pane fade" id="nav-missed" role="tabpanel" aria-labelledby="nav-missed-tab">
                        <?php
                        if ($missed_arr == null):
                            ?>
                            <br>
                            <p class="text-center text-muted display-6">No missed appointments </p>
                        </div>
                        <?php
                    else:
                        ?>
                        <!-- Nav tabs -->
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 g-4">
                            <?php
                            // For Each Upcoming Appointment Record
                            foreach ($missed_arr as $record):
                                ?>
                                <div class="col">
                                    <div class="card shadow" style="border-radius: 10px;">
                                        <div class="card-header">
                                            <?php echo $record->get_appointmenttype(); // Return String          ?>
                                        </div> <!-- CARD HEADER -->
                                        <div class="card-body">
                                            Appointment ID:
                                            <?php echo $record->get_appointmentid(); // Return Appointment ID         ?>
                                            <br>Appointment Status:
                                            <?php echo $record->get_appointmentstatus(); // Return Appointment status          ?>
                                            <br>
                                            <br>Date: <?php echo Time::date_format_change($record->get_scheduledon()->get_date(), Time::DATE_FORMAT_APPOINTMENT); // Returns Date                                                         ?>
                                            <br>Time: <?php echo Time::to_12hours($record->get_scheduledon()->get_time(), false); // Returns Time                                                         ?>
                                            <br>Location: <?php echo $record->get_facility()->get_facilityname(); // Returns Date                                                         ?>

                                            <!-- $record->get_facility(); will return `Medical_Facility` object -->
                                            <br>Address: <?php echo $record->get_facility()->get_address(); ?>
                                            <br>Contact Number:
                                            <?php echo $record->get_facility()->get_contactnumber(); ?>
                                            <div class="row m-2 text-center">
                                                <?php
                                                // -- Bunch Of Appointment Info To Be Passed To Button Function -- //
                                                $appt_info = $record->get_appointmentid() . "~" . $record->get_appointmenttype() .
                                                        "~" . Time::date_format_change($record->get_scheduledon()->get_date(), Time::DATE_FORMAT_APPOINTMENT) .
                                                        "~" . Time::to_12hours($record->get_scheduledon()->get_time(), false);
                                                ?>
                                                <div class="col">
                                                    <button type="button" class="btn btn-info col-12 text-light">Reschedule</button>
                                                </div> <!-- BUTTON RESCEHDULE COLUMN -->
                                            </div> <!-- BUTTON ROW-->
                                        </div> <!-- CARD BODY -->
                                    </div> <!-- CARD -->
                                </div> <!-- COLUMN CARD -->
                            <?php endforeach; ?>
                        </div>
                    </div> <!--MT-4 -->
                </form> <!-- Form For "Cancel" & "Reschedule" -->
            <?php endif; ?>
        </div> <!-- CONTAINER -->

        <?php
    else:
        header("Location:login.php");
    endif;
else: header("Location:login.php");
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
<script type="text/javascript">
    $(document).ready(function () {
        var url = window.location;
        $('ul.nav a[href="' + url + '"]').parent().addClass('active');
        $('ul.nav a').filter(function () {
            return this.href == url;
        }).parent().addClass('active');
    });
</script> 
</body>

</html>
